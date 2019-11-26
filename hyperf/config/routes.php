<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

// use Hyperf\HttpServer\Router\Router;
use App\Kernel\Http\Router;
use App\Middleware\PermissionMiddleware;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::addRoute(['GET', 'POST'], '/admin/base/getConfigs', 'App\Controller\BaseController@getConfigs');
Router::post('/admin/base/login', 'App\Controller\BaseController@login');
Router::post('/admin/base/relogin', 'App\Controller\BaseController@relogin');
Router::post('/admin/base/logout', 'App\Controller\BaseController@logout');
Router::post('/admin/base/changePwd', 'App\Controller\BaseController@changePwd');

Router::resource('/admin/systemConfigs', 'App\Controller\SystemConfigsController', ['middleware' => [PermissionMiddleware::class]]);

Router::resource('/admin/users', 'App\Controller\UsersController', ['middleware' => [PermissionMiddleware::class]]);
Router::post('/admin/users/deletes', 'App\Controller\UsersController@deletes', ['middleware' => [PermissionMiddleware::class]]);
Router::post('/admin/users/enables', 'App\Controller\UsersController@enables', ['middleware' => [PermissionMiddleware::class]]);

Router::resource('/admin/groups', 'App\Controller\GroupsController', ['middleware' => [PermissionMiddleware::class]]);
Router::post('/admin/groups/deletes', 'App\Controller\GroupsController@deletes', ['middleware' => [PermissionMiddleware::class]]);
Router::post('/admin/groups/enables', 'App\Controller\GroupsController@enables', ['middleware' => [PermissionMiddleware::class]]);

Router::resource('/admin/menus', 'App\Controller\MenusController', ['middleware' => [PermissionMiddleware::class]]);
Router::post('/admin/menus/deletes', 'App\Controller\MenusController@deletes', ['middleware' => [PermissionMiddleware::class]]);
Router::post('/admin/menus/enables', 'App\Controller\MenusController@enables', ['middleware' => [PermissionMiddleware::class]]);

Router::resource('/admin/posts', 'App\Controller\PostsController', ['middleware' => [PermissionMiddleware::class]]);
Router::post('/admin/posts/deletes', 'App\Controller\PostsController@deletes', ['middleware' => [PermissionMiddleware::class]]);
Router::post('/admin/posts/enables', 'App\Controller\PostsController@enables', ['middleware' => [PermissionMiddleware::class]]);

Router::resource('/admin/rules', 'App\Controller\RulesController', ['middleware' => [PermissionMiddleware::class]]);
Router::post('/admin/rules/deletes', 'App\Controller\RulesController@deletes', ['middleware' => [PermissionMiddleware::class]]);
Router::post('/admin/rules/enables', 'App\Controller\RulesController@enables', ['middleware' => [PermissionMiddleware::class]]);

Router::resource('/admin/structures', 'App\Controller\StructuresController', ['middleware' => [PermissionMiddleware::class]]);
Router::post('/admin/structures/deletes', 'App\Controller\StructuresController@deletes', ['middleware' => [PermissionMiddleware::class]]);
Router::post('/admin/structures/enables', 'App\Controller\StructuresController@enables', ['middleware' => [PermissionMiddleware::class]]);
