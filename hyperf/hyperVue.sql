/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 50643
 Source Host           : localhost:3306
 Source Schema         : hyperf

 Target Server Type    : MySQL
 Target Server Version : 50643
 File Encoding         : 65001

 Date: 24/11/2019 15:55:45
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for oa_admin_access
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_access`;
CREATE TABLE `oa_admin_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `group_id` int(11) NOT NULL COMMENT '用户组ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='权限关系表';

-- ----------------------------
-- Records of oa_admin_access
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_access` VALUES (62, 4, 15);
INSERT INTO `oa_admin_access` VALUES (77, 3, 15);
INSERT INTO `oa_admin_access` VALUES (79, 2, 15);
COMMIT;

-- ----------------------------
-- Table structure for oa_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_group`;
CREATE TABLE `oa_admin_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `rules` varchar(4000) DEFAULT NULL COMMENT '权限 半角逗号分隔',
  `pid` int(11) DEFAULT '0' COMMENT '父级ID',
  `remark` varchar(100) DEFAULT NULL COMMENT '备注',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态: 1启用 0禁用',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户组表';

-- ----------------------------
-- Records of oa_admin_group
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_group` VALUES (15, '普通会员', '10,30,31,32,37,33,34,35,36,11,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,38,39,40,41,42,43,44,45', 0, '普通组别', 1, NULL, '2019-11-24 15:34:06');
INSERT INTO `oa_admin_group` VALUES (20, 'sfasfafafa', '10', 15, '', 1, '2019-11-24 14:49:54', '2019-11-24 15:34:06');
INSERT INTO `oa_admin_group` VALUES (21, '哈哈', '10', 15, '', 1, '2019-11-24 15:10:09', '2019-11-24 15:34:06');
COMMIT;

-- ----------------------------
-- Table structure for oa_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_menu`;
CREATE TABLE `oa_admin_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '上级菜单ID',
  `title` varchar(32) DEFAULT '' COMMENT '菜单名称',
  `url` varchar(127) DEFAULT '' COMMENT '链接地址',
  `icon` varchar(64) DEFAULT '' COMMENT '图标',
  `menu_type` tinyint(4) DEFAULT NULL COMMENT '菜单类型',
  `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序（同级有效）',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态: 1启用 0禁用',
  `rule_id` int(11) DEFAULT NULL COMMENT '权限ID',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='菜单表';

-- ----------------------------
-- Records of oa_admin_menu
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_menu` VALUES (53, 0, '系统配置', '/home/rules/list', 'el-icon-s-tools', 1, 0, 1, 10, NULL, '2019-11-24 14:09:37');
INSERT INTO `oa_admin_menu` VALUES (54, 53, '菜单管理', '/home/menus/list', '', 1, 1, 1, 21, NULL, NULL);
INSERT INTO `oa_admin_menu` VALUES (55, 53, '配置管理', '/home/configs/add', '', 1, 2, 1, 29, NULL, NULL);
INSERT INTO `oa_admin_menu` VALUES (56, 53, '权限管理', '/home/rules/list', '', 1, 3, 1, 13, NULL, NULL);
INSERT INTO `oa_admin_menu` VALUES (57, 0, '组织架构', '/home/groups/list', 'el-icon-menu', 1, 1, 1, 10, NULL, '2019-11-24 14:09:51');
INSERT INTO `oa_admin_menu` VALUES (58, 57, '岗位管理', '/home/posts/list', '', 1, 1, 1, 31, NULL, NULL);
INSERT INTO `oa_admin_menu` VALUES (59, 57, '部门管理', '/home/structures/list', '', 1, 2, 1, 39, NULL, '2019-11-23 22:26:35');
INSERT INTO `oa_admin_menu` VALUES (60, 57, '用户组管理', '/home/groups/list', '', 1, 3, 1, 47, NULL, '2019-11-23 22:29:00');
INSERT INTO `oa_admin_menu` VALUES (62, 57, '用户管理', '/home/users/list', '', 1, 4, 1, 54, NULL, '2019-11-24 14:04:24');
INSERT INTO `oa_admin_menu` VALUES (64, 0, '关于项目', 'https://github.com/WarnerYang/HyperVue', 'el-icon-info', 2, 2, 1, 10, NULL, '2019-11-23 15:53:21');
COMMIT;

-- ----------------------------
-- Table structure for oa_admin_post
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_post`;
CREATE TABLE `oa_admin_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(200) DEFAULT NULL COMMENT '岗位名称',
  `remark` varchar(200) DEFAULT NULL COMMENT '岗位备注',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态: 1启用 0禁用',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='岗位表';

-- ----------------------------
-- Records of oa_admin_post
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_post` VALUES (5, '后端开发工程师', '', 1, NULL, NULL);
INSERT INTO `oa_admin_post` VALUES (6, '前端开发工程师', '', 1, NULL, NULL);
INSERT INTO `oa_admin_post` VALUES (7, '设计师', '', 1, NULL, NULL);
INSERT INTO `oa_admin_post` VALUES (11, '文案策划', '', 1, NULL, NULL);
INSERT INTO `oa_admin_post` VALUES (12, '产品助理', '', 1, NULL, NULL);
INSERT INTO `oa_admin_post` VALUES (15, '总经理', '', 1, NULL, NULL);
INSERT INTO `oa_admin_post` VALUES (20, '项目经理', '', 1, NULL, NULL);
INSERT INTO `oa_admin_post` VALUES (26, '项目助理', '', 1, NULL, NULL);
INSERT INTO `oa_admin_post` VALUES (27, '测试工程师', '', 1, NULL, NULL);
INSERT INTO `oa_admin_post` VALUES (28, '人事经理', '', 1, NULL, NULL);
INSERT INTO `oa_admin_post` VALUES (29, 'CEO', '', 1, NULL, NULL);
INSERT INTO `oa_admin_post` VALUES (30, '品牌策划', '', 1, NULL, '2019-11-19 16:33:38');
COMMIT;

-- ----------------------------
-- Table structure for oa_admin_rule
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_rule`;
CREATE TABLE `oa_admin_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `title` varchar(100) DEFAULT '' COMMENT '名称',
  `name` varchar(100) DEFAULT '' COMMENT '定义',
  `level` tinyint(5) DEFAULT NULL COMMENT '级别: 1模块 2控制器 3操作',
  `pid` int(11) DEFAULT '0' COMMENT '父级ID',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态: 1启用 0禁用',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='权限表';

-- ----------------------------
-- Records of oa_admin_rule
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_rule` VALUES (10, '基础功能', 'admin', 1, 0, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (11, '权限管理', 'rules', 2, 10, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (13, '权限列表', 'index', 3, 11, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (14, '权限详情', 'show', 3, 11, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (15, '编辑权限', 'update', 3, 11, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (16, '删除权限', 'destroy', 3, 11, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (17, '添加权限', 'store', 3, 11, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (18, '批量删除权限', 'deletes', 3, 11, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (19, '批量启用/禁用权限', 'enables', 3, 11, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (20, '菜单管理', 'menus', 2, 10, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (21, '菜单列表', 'index', 3, 20, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (22, '菜单详情', 'show', 3, 20, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (23, '编辑菜单', 'update', 3, 20, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (24, '删除菜单', 'destroy', 3, 20, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (25, '添加菜单', 'store', 3, 20, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (26, '批量删除菜单', 'deletes', 3, 20, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (27, '批量启用/禁用菜单', 'enables', 3, 20, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (28, '系统配置', 'systemConfigs', 2, 10, 1, NULL, '2019-11-24 13:38:34');
INSERT INTO `oa_admin_rule` VALUES (29, '编辑系统配置', 'store', 3, 28, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (30, '岗位管理', 'posts', 2, 10, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (31, '岗位列表', 'index', 3, 30, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (32, '岗位详情', 'show', 3, 30, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (33, '编辑岗位', 'update', 3, 30, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (34, '删除岗位', 'destroy', 3, 30, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (35, '添加岗位', 'store', 3, 30, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (36, '批量删除岗位', 'deletes', 3, 30, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (37, '批量启用/禁用岗位', 'enables', 3, 30, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (38, '部门管理', 'structures', 2, 10, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (39, '部门列表', 'index', 3, 38, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (40, '部门详情', 'show', 3, 38, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (41, '编辑部门', 'update', 3, 38, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (42, '删除部门', 'destroy', 3, 38, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (43, '添加部门', 'store', 3, 38, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (44, '批量删除部门', 'deletes', 3, 38, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (45, '批量启用/禁用部门', 'enables', 3, 38, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (46, '用户组管理', 'groups', 2, 10, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (47, '用户组列表', 'index', 3, 46, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (48, '用户组详情', 'show', 3, 46, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (49, '编辑用户组', 'update', 3, 46, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (50, '删除用户组', 'destroy', 3, 46, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (51, '添加用户组', 'store', 3, 46, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (52, '批量删除用户组', 'deletes', 3, 46, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (53, '批量启用/禁用用户组', 'enables', 3, 46, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (54, '用户管理', 'users', 2, 10, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (55, '用户列表', 'index', 3, 54, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (56, '用户详情', 'show', 3, 54, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (57, '编辑用户', 'update', 3, 54, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (58, '删除用户', 'destroy', 3, 54, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (59, '添加用户', 'store', 3, 54, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (60, '批量删除用户', 'deletes', 3, 54, 1, NULL, NULL);
INSERT INTO `oa_admin_rule` VALUES (61, '批量启用/禁用用户', 'enables', 3, 54, 1, NULL, '2019-11-21 23:46:23');
COMMIT;

-- ----------------------------
-- Table structure for oa_admin_structure
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_structure`;
CREATE TABLE `oa_admin_structure` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(200) DEFAULT '' COMMENT '部门名称',
  `pid` int(11) DEFAULT '0' COMMENT '父级ID',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态: 1启用 0禁用',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='部门表';

-- ----------------------------
-- Records of oa_admin_structure
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_structure` VALUES (1, '深圳某某公司', 0, 1, NULL, '2019-11-23 21:13:26');
INSERT INTO `oa_admin_structure` VALUES (5, '设计部', 1, 1, NULL, '2019-11-23 21:13:26');
INSERT INTO `oa_admin_structure` VALUES (37, '总经办', 1, 1, NULL, '2019-11-23 21:13:26');
INSERT INTO `oa_admin_structure` VALUES (52, '项目部', 1, 1, NULL, '2019-11-23 21:13:26');
INSERT INTO `oa_admin_structure` VALUES (53, '测试部', 1, 1, NULL, '2019-11-23 21:13:26');
INSERT INTO `oa_admin_structure` VALUES (54, '开发部', 1, 1, NULL, '2019-11-23 21:13:26');
INSERT INTO `oa_admin_structure` VALUES (55, '市场部', 1, 1, NULL, '2019-11-23 21:13:26');
INSERT INTO `oa_admin_structure` VALUES (56, '研发部', 1, 1, NULL, '2019-11-23 21:13:26');
COMMIT;

-- ----------------------------
-- Table structure for oa_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_user`;
CREATE TABLE `oa_admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `username` varchar(100) DEFAULT NULL COMMENT '用户名',
  `password` varchar(100) DEFAULT NULL COMMENT '密码',
  `remark` varchar(100) DEFAULT NULL COMMENT '备注',
  `realname` varchar(100) DEFAULT '' COMMENT '真实姓名',
  `structure_id` int(11) DEFAULT NULL COMMENT '部门ID',
  `post_id` int(11) DEFAULT NULL COMMENT '岗位ID',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态: 1启用 0禁用',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_username` (`username`) USING BTREE COMMENT '用户名唯一索引'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='后台用户表';

-- ----------------------------
-- Records of oa_admin_user
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_user` VALUES (1, 'admin', 'c3fc5b866eb4f90ac625214a8e4c8bc9', '', '超级管理员', 1, 5, 1, NULL, '2019-10-31 14:30:20');
INSERT INTO `oa_admin_user` VALUES (2, 'warner', 'c3fc5b866eb4f90ac625214a8e4c8bc9', '备注dsddgs', 'warner', 1, 5, 1, NULL, '2019-11-24 15:33:21');
INSERT INTO `oa_admin_user` VALUES (3, 'demo', 'c3fc5b866eb4f90ac625214a8e4c8bc9', '', 'demo', 1, 6, 1, NULL, '2019-11-24 14:00:11');
COMMIT;

-- ----------------------------
-- Table structure for oa_system_config
-- ----------------------------
DROP TABLE IF EXISTS `oa_system_config`;
CREATE TABLE `oa_system_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `title` varchar(50) DEFAULT NULL COMMENT '配置标题',
  `key` varchar(50) DEFAULT '' COMMENT '配置键 大写加下划线',
  `value` varchar(100) DEFAULT '' COMMENT '配置值',
  `group` tinyint(4) unsigned DEFAULT '0' COMMENT '配置分组',
  `need_auth` tinyint(1) unsigned DEFAULT '1' COMMENT '是否需要登录才能获取: 1是 0否',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `参数名` (`key`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统配置表';

-- ----------------------------
-- Records of oa_system_config
-- ----------------------------
BEGIN;
INSERT INTO `oa_system_config` VALUES (1, '系统名称', 'SYSTEM_NAME', 'HyperVue', 0, 0, NULL, '2019-11-24 14:01:14');
INSERT INTO `oa_system_config` VALUES (2, '系统LOGO', 'SYSTEM_LOGO', 'https://hyperf.oss-cn-hangzhou.aliyuncs.com/hyperf.png', 0, 0, NULL, '2019-11-24 14:01:14');
INSERT INTO `oa_system_config` VALUES (3, '登录有效期(秒)', 'LOGIN_SESSION_VALID', '86400', 0, 0, NULL, '2019-11-24 14:01:14');
INSERT INTO `oa_system_config` VALUES (4, '是否开启验证码: 1是 0否', 'IDENTIFYING_CODE', '0', 0, 0, NULL, '2019-11-24 14:01:14');
INSERT INTO `oa_system_config` VALUES (5, 'LOGO类型: 1图片 2文字', 'LOGO_TYPE', '2', 0, 0, NULL, '2019-11-24 14:01:14');
INSERT INTO `oa_system_config` VALUES (6, '允许多处登录: 1是 0否', 'ALLOW_MULTIPLE_LOGINS', '0', 0, 0, NULL, NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
