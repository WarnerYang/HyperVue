<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use App\Service\SystemConfigService;
use App\Service\UserService;
use App\Request\LoginRequest;
use App\Request\ChangePwdRequest;

class BaseController extends AbstractController
{
    /**
     * @Inject
     * @var SystemConfigService
     */
    private $systemConfigService;

    /**
     * @Inject
     * @var UserService
     */
    private $userService;

    public function getConfigs()
    {
        $data = $this->systemConfigService->getDataList();
        return success($data);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $username = $this->request->input('username');
        $password = $this->request->input('password');
        $verifyCode = $this->request->input('verifyCode');
        $isRemember = $this->request->input('isRemember', 0);
        $data = $this->userService->login($username, $password, $verifyCode, $isRemember);
        return success($data);
    }

    public function relogin()
    {
        $rememberKey = $this->request->input('rememberKey');
        $decrypt = decrypt($rememberKey);
        $username = $decrypt['username'];
        $password = $decrypt['password'];
        $data = $this->userService->login($username, $password, '', true, true);
        return success($data);
    }

    public function logout()
    {
        $authkey = $this->request->header('authkey');
        $data = $this->userService->removeLoggedInfo($authkey);
        return success();
    }

    public function changePwd(ChangePwdRequest $request)
    {
        $validated = $request->validated();
        $oldPwd = $this->request->input('old_pwd');
        $newPwd = $this->request->input('new_pwd');
        $authKey = $this->request->header('authKey');
        $data = $this->userService->changePwd($authKey, $oldPwd, $newPwd);
        return success($data);
    }

    public function getVerify()
    {
        // $captcha = new HonrayVerify(config('captcha'));
        // return $captcha->entry();
    }
}
