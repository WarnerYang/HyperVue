<?php

declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("登录已失效")
     */
    const LOGIN_INVALID = 1001;

    /**
     * @Message("没有权限")
     */
    const PERMISSION_DENIED = 1002;

    /**
     * @Message("账号已被删除或禁用")
     */
    const USER_DELETED_OR_DISABLED = 1003;

    /**
     * @Message("用户不存在或密码错误")
     */
    const USER_NOT_EXIST = 1004;

    /**
     * @Message("用户不存在或密码错误")
     */
    const PASSWORD_ERROR = 1005;

    /**
     * @Message("帐号已被禁用")
     */
    const USER_DISABLED = 1006;

    /**
     * @Message("已在另一处登录")
     */
    const LOGGED_ELSEWHERE = 1007;
    

    /**
     * @Message("参数错误")
     */
    const PARAMS_INVALID = 3000;

    /**
     * @Message("验证码不能为空")
     */
    const VERIFYCODE_REQUIRED = 3001;

    /**
     * @Message("验证码错误")
     */
    const VERIFYCODE_ERROR = 3002;

    /**
     * @Message("新旧密码不能一致")
     */
    const PASSWORD_SAME = 3003;

    /**
     * @Message("原密码错误")
     */
    const OLD_PASSWORD_ERROR= 3004;


    /**
     * @Message("操作失败")
     */
    const OPERATION_FAILED = 4000;

    /**
     * @Message("无此数据")
     */
    const RECORD_NOT_EXIST = 4001;

    /**
     * @Message("新增失败")
     */
    const INSERT_ERROR = 4002;

    /**
     * @Message("更新失败")
     */
    const UPDATE_ERROR = 4003;

    /**
     * @Message("删除失败")
     */
    const DELETE_ERROR = 4004;

    /**
     * @Message("非法操作")
     */
    const ILLEGAL_PERATION = 4005;


    /**
     * @Message("Server Error！")
     */
    const SERVER_ERROR = 500;
}
