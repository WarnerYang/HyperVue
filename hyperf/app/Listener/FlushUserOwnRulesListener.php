<?php

declare(strict_types=1);

namespace App\Listener;

use Psr\EventDispatcher\EventDispatcherInterface;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Cache\Listener\DeleteListenerEvent;
use App\Event\UserPermissionChanged;
use App\Model\AdminUser;
use App\Model\AdminGroup;
use App\Model\AdminRule;
use App\Service\UserService;

/**
 * @Listener
 */
class FlushUserOwnRulesListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            UserPermissionChanged::class
        ];
    }

    /**
     * @param UserPermissionChanged $event
     */
    public function process(object $event)
    {
        /** @var EventDispatcherInterface $eventDispatcher*/
        $eventDispatcher = di(EventDispatcherInterface::class);
        /** @var UserService $userService*/
        $userService = di(UserService::class);

        $uid = $event->uid;
        $model = $event->model;

        // 变更用户信息 清理相应用户拥有的权限列表缓存
        if ($model instanceof AdminUser) {
            $uid = $event->uid;
            if (!is_array($uid)) $uid = [$uid];
        }

        // 变更用户组或者权限 清理所有用户拥有的权限列表缓存
        if ($model instanceof AdminGroup || $model instanceof AdminRule) {
            $uid = $userService->getOrSetUserOwnRulesIds();
        }

        foreach ($uid as $id) {
            $eventDispatcher->dispatch(new DeleteListenerEvent('getUserOwnRulesUpdate', [$id]));
        }

        return true;
    }
}
