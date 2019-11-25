<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Utils\Context;
use Hyperf\HttpServer\Contract\RequestInterface;
use App\Exception\BusinessException;
use App\Constants\ErrorCode;
use App\Service\UserService;
use App\Kernel\Http\Router;

class PermissionMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var UserService
     */
    protected $userService;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authKey = $this->request->header('authKey');
        $sessionId = $this->request->header('sessionId');

        //获取用户信息
        $getLoggedInfo = $this->userService->getLoggedInfo($authKey);
        if (!$getLoggedInfo) {
            throw new BusinessException(ErrorCode::LOGIN_INVALID);
        }

        $userInfo = $getLoggedInfo['userInfo'];
        $uid = $userInfo['id'];
        $username = $userInfo['username'];
        // 检查用户状态
        if (!$this->userService->isEnable($uid)) {
            throw new BusinessException(ErrorCode::USER_DELETED_OR_DISABLED);
        }

        // 多处登录校验
        if ($getLoggedInfo['sessionId'] !== $sessionId && !config('ALLOW_MULTIPLE_LOGINS')) {
            throw new BusinessException(ErrorCode::LOGGED_ELSEWHERE);
        }

        // 获取路由名
        $ruleName = Router::getCurrentRuleName();

        // // 用户id 写入日志
        // $data['uid']       = $uid;
        // $data['name']      = $username;
        // Log::record($data, 'admin_param');

        // 权限校验
        $isSupperUser = $this->userService->isSupperUser($uid);
        if (!$isSupperUser && !$this->check($ruleName, $uid)) {
            throw new BusinessException(ErrorCode::PERMISSION_DENIED);
        }

        // 保存用户信息到上下文
        Context::set('userInfo', $userInfo);

        return $handler->handle($request);
    }

    /**
     * 权限校验
     * 
     * @param string|array $name 需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param integer $uid  认证用户的id
     * @return boolean 通过验证返回true 失败返回false
     */
    private function check($name, $uid, $relation = 'or'): bool
    {
        // 获取用户具有权限的规则列表
        $getUserOwnRules = $this->userService->getUserOwnRules($uid);
        $rulesList = $getUserOwnRules['rulesList'];

        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array($name);
            }
        }
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $name[$k] = strtolower($v);
            }
        }

        // 保存验证通过的规则名
        $list = [];
        foreach ($rulesList as $rule) {
            if (in_array($rule, $name)) {
                $list[] = $rule;
            }
        }
        if ($relation == 'or' && !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation == 'and' && empty($diff)) {
            return true;
        }
        return false;
    }
}
