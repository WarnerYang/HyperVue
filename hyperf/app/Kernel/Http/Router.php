<?php

declare(strict_types=1);

namespace App\Kernel\Http;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Router\Router as HyperfRouter;

class Router extends HyperfRouter
{
    /**
     * REST路由操作方法定义
     *
     * @var array
     */
    private static $rest = [
        ['get',     '',            '@index'],
        ['get',     '/create',     '@create'],
        ['post',    '',            '@store'],
        ['get',     '/{id}',       '@show'],
        ['get',     '/{id}/edit',  '@edit'],
        ['put',     '/{id}',       '@update'],
        ['delete',  '/{id}',       '@destroy']
    ];

    /**
     * 注册资源路由
     *
     * @param string $prefix
     * @param string $controller
     * @param array $options
     * @return void
     */
    public static function resource($prefix, $controller, $options = []): void
    {
        $prefix = rtrim($prefix, '/');
        foreach (self::$rest as $value) {
            $method = $value[0];
            $route = $prefix . $value[1];
            $handler =  $controller . $value[2];
            HyperfRouter::$method($route, $handler, $options);
        }
    }

    /**
     * 注册any路由
     *
     * @param string $route
     * @param string $handler
     * @param array $options
     * @return void
     */
    public static function any($route, $handler, $options = []): void
    {
        HyperfRouter::addRoute(['GET', 'POST', 'DELETE', 'PATCH', 'HEAD'], $route, $handler, $options);
    }

    /**
     * 获取当前控制器名
     *
     * @return string
     */
    public static function getCurrentControllerName(): string
    {
        return self::getCurrentAction()['controller'];
    }

    /**
     * 获取当前控制器名小写短名
     *
     * @return string
     */
    public static function getCurrentControllerShortName(): string
    {
        $fullName = self::getCurrentAction()['controller'];
        [, , $controller] = explode('\\', $fullName);
        return strtolower(str_replace('Controller', '', $controller));
    }

    /**
     * 获取当前方法名
     *
     * @return string
     */
    public static function getCurrentMethodName(): string
    {
        return self::getCurrentAction()['method'];
    }

    /**
     * 获取规则名
     *
     * @param string $join 连接字符串
     * @return string
     */
    public static function getCurrentRuleName($join = '-'): string
    {
        $controller = self::getCurrentControllerShortName();
        $method = self::getCurrentMethodName();
        return 'admin' . $join . $controller . $join . $method;
    }

    /**
     * 获取当前控制器与方法
     *
     * @return array
     * 
     */
    public static function getCurrentAction(): array
    {
        /** @var RequestInterface $request*/
        $request = di(RequestInterface::class);

        // eg: App\Controllers\TestController@index
        $action = $request->getAttributes()['Hyperf\HttpServer\Router\Dispatched']->handler->callback;
        [$class, $method] = explode('@', $action);

        return ['controller' => $class, 'method' => $method];
    }
}
