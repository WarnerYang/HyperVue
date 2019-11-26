<?php

declare(strict_types=1);

namespace App\Service;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Context;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\DbConnection\Db;
use Hyperf\Cache\Annotation\Cacheable;
use App\Model\AdminUser;
use App\Model\AdminMenu;
use App\Model\AdminRule;
use App\Model\AdminAccess;
use App\Kernel\Tree;
use App\Service\Service;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Event\UserPermissionChanged;

class UserService extends Service
{
    /**
     * @Inject
     * @var AdminUser
     */
    protected $model;

    /**
     * @Inject
     * @var Tree
     */
    protected $tree;

    /**
     * @Inject()
     * @var ValidatorFactoryInterface
     */
    protected $validationFactory;

    /**
     * 超级账号id
     */
    const SUPPER_USER_ID = 1;

    /**
     * 列表
     * 
     * @param string $keywords 关键字
     * @param integer $page 当前页数
     * @param integer $limit 每页数量
     * @return array
     */
    public function getDataList(string $keywords = null, int $page, int $limit)
    {
        $query = $this->model;
        if ($keywords) {
            $keywords = rtrim(ltrim($keywords));
            $query = $query->where('realname', 'like', "%{$keywords}%");
            $query = $query->orWhere('username', 'like', "%{$keywords}%");
        }
        // 除去超级管理员
        $query = $query->where('admin_user.id', '<>', self::SUPPER_USER_ID);
        $dataCount = $query->count();
        $list = $query
            ->leftJoin('admin_structure as structure', 'structure.id', '=', 'admin_user.structure_id')
            ->leftJoin('admin_post as post', 'post.id', '=', 'admin_user.post_id')
            ->select('admin_user.*', 'structure.name as s_name', 'post.name as p_name')
            ->paginate($limit);

        $list = $list->toArray()['data'];
        $result = [
            'list' => $list,
            'dataCount' => $dataCount,
        ];
        return $result;
    }

    public function createData($params)
    {
        Db::beginTransaction();
        try {
            $params['password'] = user_md5($params['password']);
            $groups = $params['groups'];
            unset($params['groups']);
            $insertGetId = $this->model->insertGetId($params);
            foreach ($groups as $id) {
                $userGroup['user_id'] = $insertGetId;
                $userGroup['group_id'] = $id;
                $userGroups[] = $userGroup;
            }
            AdminAccess::insert($userGroups);
            Db::commit();
            return ['id' => $insertGetId];
        } catch (\Throwable $th) {
            Db::rollback();
            throw new BusinessException(ErrorCode::INSERT_ERROR, $th->getMessage());
        }
    }

    public function updateDataById($params, $id)
    {
        if ($this->isSupperUser($id)) {
            throw new BusinessException(ErrorCode::ILLEGAL_PERATION);
        }

        if (empty($params['password'])) {
            unset($params['password']);
        } else {
            $params['password'] = user_md5($params['password']);
        }

        Db::beginTransaction();
        try {
            AdminAccess::where('user_id', $id)->delete();
            $groups = $params['groups'];
            unset($params['groups']);
            $update = $this->model->where('id', $id)->update($params);
            foreach ($groups as $group) {
                $userGroup['user_id'] = $id;
                $userGroup['group_id'] = $group;
                $userGroups[] = $userGroup;
            }
            AdminAccess::insert($userGroups);
            Db::commit();
            $this->eventDispatcher->dispatch(new UserPermissionChanged($this->model, $id));
            return ['id' => $id];
        } catch (\Throwable $th) {
            Db::rollback();
            throw new BusinessException(ErrorCode::UPDATE_ERROR, $th->getMessage());
        }
    }

    public function getDataById($id)
    {
        $result = $this->model->find($id);
        if (!$result) {
            throw new BusinessException(ErrorCode::RECORD_NOT_EXIST);
        }
        $result['groups'] = $result->groups()->get();
        return $result;
    }

    public function delDataById($id, $delSon = false)
    {
        if ($this->isSupperUser($id)) {
            throw new BusinessException(ErrorCode::ILLEGAL_PERATION);
        }
        $result = $this->model->where('id', $id)->delete();
        if (!$result) {
            throw new BusinessException(ErrorCode::DELETE_ERROR);
        }

        $this->dispatcher->dispatch(new UserPermissionChanged($this->model, $id));
        return ['id' => $id];
    }

    /**
     * 登录
     *
     * @param string $username 用户名
     * @param string $password 密码
     * @param string $verifyCode 验证码
     * @param boolean $isRemember 是否记住密码
     * @param boolean $isAutologin 是否自动登录
     * @return array
     */
    public function login($username, $password, $verifyCode = '', $isRemember = false, $isAutologin = false)
    {
        // 验证码校验
        if (config('IDENTIFYING_CODE') && !$isAutologin) {
            if (!$verifyCode) {
                throw new BusinessException(ErrorCode::VERIFYCODE_REQUIRED);
            }
            $captcha = new HonrayVerify(config('captcha'));
            if (!$captcha->check($verifyCode)) {
                throw new BusinessException(ErrorCode::VERIFYCODE_ERROR);
            }
        }

        // 用户信息校验
        $userInfo = AdminUser::where('username', $username)->first();
        if (!$userInfo) {
            throw new BusinessException(ErrorCode::USER_NOT_EXIST);
        }
        // dump(user_md5($password));
        if (user_md5($password) !== $userInfo['password']) {
            throw new BusinessException(ErrorCode::PASSWORD_ERROR);
        }
        if ((int) $userInfo['status'] === 0) {
            throw new BusinessException(ErrorCode::USER_DISABLED);
        }

        // 获取菜单和权限
        $getUserOwnRulesAndMenus = $this->getUserOwnRulesAndMenus($userInfo['id']);
        if (!$getUserOwnRulesAndMenus['menusList']) {
            throw new BusinessException(ErrorCode::PERMISSION_DENIED);
        }

        // 构造返回收据
        $authKey = $this->getAuthKey($userInfo['id']);
        $loggedInfo = [
            'authKey' => $authKey,
            'sessionId' => unique_id(),
            'userInfo' => $userInfo->toArray(),
        ];

        // 缓存用户已登录信息
        $this->setLoggedInfo($authKey, $loggedInfo);

        // 记住密码返回加密后的 key 用于 relogin
        if ($isRemember) {
            $loggedInfo['rememberKey'] = encrypt(['username' => $username, 'password' => $password]);
        }

        $loggedInfo['menusList'] = $getUserOwnRulesAndMenus['menusList'];
        $loggedInfo['authList'] = $getUserOwnRulesAndMenus['rulesList'];

        unset($loggedInfo['userInfo']['password']);

        return $loggedInfo;
    }

    /**
     * 修改密码
     *
     * @param string $oldPwd 旧密码
     * @param string $newPwd 新密码
     * @return string
     */
    public function changePwd($authKey, $oldPwd, $newPwd)
    {
        if ($newPwd === $oldPwd) {
            throw new BusinessException(ErrorCode::PASSWORD_SAME);
        }

        // 获取已登录信息
        $loggedInfo = $this->getLoggedInfo($authKey);
        $userInfo = $loggedInfo['userInfo'];
        
        // 对比原密码
        if ($userInfo['password'] !== user_md5($oldPwd)) {
            throw new BusinessException(ErrorCode::OLD_PASSWORD_ERROR);
        }

        // 更新密码
        $update = $this->model->where('id', $userInfo['id'])->update(['password' => user_md5($newPwd)]);
        if (!$update) {
            throw new BusinessException(ErrorCode::UPDATE_ERROR);
        }

        // 更新用户已登录信息的缓存
        $loggedInfo['userInfo']['password'] = user_md5($newPwd);
        $loggedInfo['sessionId'] =  unique_id();
        $this->setLoggedInfo($authKey, $loggedInfo);

        unset($loggedInfo['userInfo']['password']);

        return $loggedInfo;
    }

    /**
     * 获取 authKey
     *
     * @param integer $uid 用户id
     * @return string
     */
    public function getAuthKey($uid)
    {
        $authKey = user_md5($uid);
        return $authKey;
    }

    /**
     * 缓存用户已登录信息
     *
     * @param string $authKey
     * @param array $loggedInfo
     * @return void
     */
    public function setLoggedInfo($authKey, $loggedInfo)
    {
        cache(config('logged_info_cache_key') . $authKey, $loggedInfo, (int) config('LOGIN_SESSION_VALID'));
    }

    /**
     * 获取用户已登录信息
     *
     * @param string $authKey
     * @return array|null
     */
    public function getLoggedInfo($authKey)
    {
        return cache(config('logged_info_cache_key') . $authKey);
    }

    /**
     * 清除用户已登录信息
     *
     * @param string $authKey
     * @return boolean
     */
    public function removeLoggedInfo($authKey)
    {
        return cache(config('logged_info_cache_key') . $authKey, null);
    }

    /**
     * 获取菜单和权限
     *
     * @param integer $uid 用户id
     * @return array
     */
    public function getUserOwnRulesAndMenus($uid): array
    {
        if ($this->isSupperUser($uid)) {
            $rulesList = [];
            $menusList = AdminMenu::where('status', 1)->orderBy('sort')->get();
        } else {
            $getUserOwnRules = $this->getUserOwnRules($uid);
            $rulesList = $getUserOwnRules['rulesList'];
            $ruleIds = $getUserOwnRules['ruleIds'];
            // 获取具有权限的菜单
            if (!empty($ruleIds)) {
                $menusList = AdminMenu::where('status', 1)->whereIn('id', $ruleIds)->orderBy('sort')->get();
            }
        }

        $menusList = $menusList ? $menusList->toArray() : [];
        // 处理菜单成树状
        $menusList = $this->tree->list_to_tree($menusList, 'id', 'pid', 'child', 0, true, ['pid']);

        $result = [
            'menusList' => $menusList,
            'rulesList' => $rulesList
        ];

        return  $result;
    }

    /**
     * 获取用户拥有的权限列表并缓存
     * 缓存清理机制在监听器 \App\Listener\FlushUserOwnRulesListener
     *
     * @param integer $uid 用户id
     * @return array
     * 
     * @Cacheable(prefix="getUserOwnRules", ttl=9000, listener="getUserOwnRulesUpdate")
     */
    public function getUserOwnRules($uid): array
    {
        $rulesList = [];
        $ruleIds = [];

        if (!$this->isSupperUser($uid)) {
            $groups = AdminUser::find($uid)->groups()->get();
            $groups = $groups ? $groups->toArray() : [];
            foreach ($groups as $group) {
                if ($group['status'] === 1) {
                    $ruleIds = array_merge($ruleIds, explode(',', trim($group['rules'])));
                }
            }

            $ruleIds = array_unique($ruleIds);
            $rulesList = AdminRule::where('status', 1)->whereIn('id', $ruleIds)->get();
            $rulesList = $rulesList ? $rulesList->toArray() : [];

            $ruleIds = [];
            foreach ($rulesList as $k => $rule) {
                $ruleIds[] = $rule['id'];
                $rulesList[$k]['name'] = strtolower($rule['name']);
            }

            // 处理规则成树状
            $rulesList = $this->tree->list_to_tree($rulesList, 'id', 'pid', 'child', 0, true, ['pid']);
            // 给树状规则表处理成 module-controller-action
            $rulesList = rules_deal($rulesList);
        }

        $result = [
            'rulesList' => $rulesList,
            'ruleIds' => $ruleIds
        ];

        // 记录缓存的uid
        $this->getOrSetUserOwnRulesIds($uid);

        return  $result;
    }

    /**
     * 设置或读取已经缓存权限的用户id
     * 便于监听器删除所有的缓存key
     *
     * @param int|null $uid
     * @return mixed
     */
    public function getOrSetUserOwnRulesIds($uid = null)
    {
        $key = 'getUserOwnRules:hasCacheIds';
        if ($uid) {
            $ids = cache($key) ?? [];
            array_push($ids, $uid);
            $ids = array_unique($ids);
            return cache($key, $ids);
        }
        return cache($key) ?? [];
    }

    /**
     * 账号是否可用
     * @param integer $uid 用户id
     * @return boolean
     */
    public function isEnable($uid): bool
    {
        $result = $this->model->where(['id' => $uid, 'status' => 1])->select('id')->first();
        return $result ? true : false;
    }

    /**
     * 是否超级账号
     * 
     * @param integer|null $uid 用户id，不传就取当前登录用户id
     * @return boolean
     */
    public function isSupperUser($uid = null): bool
    {
        if (!$uid) {
            $userInfo = Context::get('userInfo');
            $uid = $userInfo['id'];
        }

        return self::SUPPER_USER_ID === (int) $uid ? true : false;
    }
}
