import login from './components/base/login'
import refresh from './components/base/refresh'
import home from './components/home'
import menusList from './components/menus/list'
import menusAdd from './components/menus/add'
import menusEdit from './components/menus/edit'
import configs from './components/configs/add'
import rulesList from './components/rules/list'
import rulesAdd from './components/rules/add'
import rulesEdit from './components/rules/edit'
import postsList from './components/posts/list'
import postsAdd from './components/posts/add'
import postsEdit from './components/posts/edit'
import structuresList from './components/structures/list'
import structuresAdd from './components/structures/add'
import structuresEdit from './components/structures/edit'
import groupsList from './components/groups/list'
import groupsAdd from './components/groups/add'
import groupsEdit from './components/groups/edit'
import usersList from './components/users/list'
import usersAdd from './components/users/add'
import usersEdit from './components/users/edit'

/**
 * meta参数解析
 * activeMenu 高亮菜单
 */

const routes = [
  { path: '/', component: login, name: 'login' },
  {
    path: '/refresh',
    component: home,
    children: [
      { path: '/refresh', component: refresh, name: 'refresh' }
    ]
  },
  {
    path: '/home',
    component: home,
    children: [
      { path: 'menus/list', component: menusList, name: 'menusList', meta: { activeMenu: '/home/menus/list' } },
      { path: 'menus/add', component: menusAdd, name: 'menusAdd', meta: { activeMenu: '/home/menus/list' } },
      { path: 'menus/edit/:id', component: menusEdit, name: 'menusEdit', meta: { activeMenu: '/home/menus/list' } }
    ]
  },
  {
    path: '/home',
    component: home,
    children: [
      { path: 'configs/add', component: configs, name: 'configs', meta: { activeMenu: '/home/configs/add' } }
    ]
  },

  {
    path: '/home',
    component: home,
    children: [
      { path: 'rules/list', component: rulesList, name: 'rulesList', meta: { activeMenu: '/home/rules/list' } },
      { path: 'rules/add', component: rulesAdd, name: 'rulesAdd', meta: { activeMenu: '/home/rules/list' } },
      { path: 'rules/edit/:id', component: rulesEdit, name: 'rulesEdit', meta: { activeMenu: '/home/rules/list' } }
    ]
  },
  {
    path: '/home',
    component: home,
    children: [
      { path: 'posts/list', component: postsList, name: 'postsList', meta: { activeMenu: '/home/posts/list' } },
      { path: 'posts/add', component: postsAdd, name: 'postsAdd', meta: { activeMenu: '/home/posts/list' } },
      { path: 'posts/edit/:id', component: postsEdit, name: 'postsEdit', meta: { activeMenu: '/home/posts/list' } }
    ]
  },
  {
    path: '/home',
    component: home,
    children: [
      { path: 'structures/list', component: structuresList, name: 'structuresList', meta: { activeMenu: '/home/structures/list' } },
      { path: 'structures/add', component: structuresAdd, name: 'structuresAdd', meta: { activeMenu: '/home/structures/list' } },
      { path: 'structures/edit/:id', component: structuresEdit, name: 'structuresEdit', meta: { activeMenu: '/home/structures/list' } }
    ]
  },
  {
    path: '/home',
    component: home,
    children: [
      { path: 'groups/list', component: groupsList, name: 'groupsList', meta: { activeMenu: '/home/groups/list' } },
      { path: 'groups/add', component: groupsAdd, name: 'groupsAdd', meta: { activeMenu: '/home/groups/list' } },
      { path: 'groups/edit/:id', component: groupsEdit, name: 'groupsEdit', meta: { activeMenu: '/home/groups/list' } }
    ]
  },
  {
    path: '/home',
    component: home,
    children: [
      { path: 'users/list', component: usersList, name: 'usersList', meta: { activeMenu: '/home/users/list' } },
      { path: 'users/add', component: usersAdd, name: 'usersAdd', meta: { activeMenu: '/home/users/list' } },
      { path: 'users/edit/:id', component: usersEdit, name: 'usersEdit', meta: { activeMenu: '/home/users/list' } }
    ]
  },
]
export default routes
