/*
Navicat MySQL Data Transfer

Source Server         : zmy
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : laravel_web

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2019-03-15 20:20:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `image_url` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户头像',
  `is_super` enum('1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '是否超管 1非超管 2超管',
  `status` enum('1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '用户状态1正常 2停用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of admin_users
-- ----------------------------
INSERT INTO `admin_users` VALUES ('1', 'admin', '46f94c8de14fb36680850768ff1b7f2a', '/images/photos/blog2.jpg', '2', '1', null, null);
INSERT INTO `admin_users` VALUES ('3', 'admin_author', '46f94c8de14fb36680850768ff1b7f2a', '/uploads/2019-03-14/201903141044419880.jpeg', '1', '1', null, null);
INSERT INTO `admin_users` VALUES ('6', 'admin_category', '46f94c8de14fb36680850768ff1b7f2a', '/uploads/2019-03-14/201903141128034588.jpeg', '1', '1', null, '2019-03-14 11:30:50');

-- ----------------------------
-- Table structure for author
-- ----------------------------
DROP TABLE IF EXISTS `author`;
CREATE TABLE `author` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '作者名字',
  `author_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '作者简介',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of author
-- ----------------------------
INSERT INTO `author` VALUES ('1', '唐家三少', '代表作：斗破苍穹', null, null);

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '小说分类',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('1', '玄幻', null, null);
INSERT INTO `category` VALUES ('3', '校园', null, null);

-- ----------------------------
-- Table structure for chapter
-- ----------------------------
DROP TABLE IF EXISTS `chapter`;
CREATE TABLE `chapter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `novel_id` int(11) NOT NULL DEFAULT '0' COMMENT '小说Id',
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '章节标题',
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '章节内容',
  `sort` int(11) NOT NULL DEFAULT '1' COMMENT '章节排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of chapter
-- ----------------------------
INSERT INTO `chapter` VALUES ('2', '1', '6666', '<p>56465456456</p>', '1', null, '2019-03-15 09:29:23');

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `novel_id` int(11) NOT NULL COMMENT '小说id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT '评论内容',
  `status` enum('1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '评论审核状态 1未审核 2已审核',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of comment
-- ----------------------------
INSERT INTO `comment` VALUES ('1', '1', '1', '123123123123', '1', null, null);
INSERT INTO `comment` VALUES ('2', '1', '1', '123123', '1', null, null);
INSERT INTO `comment` VALUES ('3', '1', '1', '123123123123', '1', null, null);
INSERT INTO `comment` VALUES ('4', '1', '1', '123123123123', '1', null, null);
INSERT INTO `comment` VALUES ('5', '1', '1', '123123123123', '1', null, null);
INSERT INTO `comment` VALUES ('6', '1', '1', '123123123123', '1', null, null);
INSERT INTO `comment` VALUES ('7', '1', '1', '123123123123', '1', null, null);
INSERT INTO `comment` VALUES ('8', '1', '1', '123123123123', '1', null, null);
INSERT INTO `comment` VALUES ('9', '1', '1', '123123123123', '1', null, null);
INSERT INTO `comment` VALUES ('10', '2', '2', '123123123123', '1', null, null);
INSERT INTO `comment` VALUES ('11', '2', '2', '123123123123', '1', null, null);
INSERT INTO `comment` VALUES ('12', '2', '2', '123123123123', '1', null, null);
INSERT INTO `comment` VALUES ('13', '2', '2', '123123123123', '1', null, null);
INSERT INTO `comment` VALUES ('14', '2', '2', '123123123123', '1', null, null);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('1', '2019_03_08_015645_create_admin_users_table', '1');
INSERT INTO `migrations` VALUES ('2', '2019_03_08_015852_create_admin_role_table', '1');
INSERT INTO `migrations` VALUES ('3', '2019_03_08_021719_create_admin_user_table', '1');
INSERT INTO `migrations` VALUES ('4', '2019_03_08_021810_create_admin_permissions_table', '1');
INSERT INTO `migrations` VALUES ('5', '2019_03_08_021847_create_admin_role_permissions_table', '1');
INSERT INTO `migrations` VALUES ('6', '2019_03_14_023007_create_novel_table', '2');
INSERT INTO `migrations` VALUES ('7', '2019_03_14_023155_create_author_table', '2');
INSERT INTO `migrations` VALUES ('8', '2019_03_14_023324_create_category_table', '2');
INSERT INTO `migrations` VALUES ('9', '2019_03_14_023452_create_chapter_table', '2');
INSERT INTO `migrations` VALUES ('10', '2019_03_14_023730_create_comment_table', '3');
INSERT INTO `migrations` VALUES ('11', '2019_03_14_023853_create_user_table', '4');

-- ----------------------------
-- Table structure for novel
-- ----------------------------
DROP TABLE IF EXISTS `novel`;
CREATE TABLE `novel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_id` int(11) NOT NULL COMMENT '小说类型Id',
  `a_id` int(11) NOT NULL COMMENT '作者Id',
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '小说名字',
  `image_url` varchar(120) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '小说封面',
  `tags` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '小说标签',
  `desc` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '小说简介',
  `status` enum('1','2') COLLATE utf8_unicode_ci NOT NULL COMMENT '小说状态 1连载 2完结',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of novel
-- ----------------------------
INSERT INTO `novel` VALUES ('1', '3', '1', '我是标题A', '/uploads/2019-03-15/201903150257191309.jpeg', 'qwe', '<p><strong>dddddddddda</strong></p>', '2', null, null);
INSERT INTO `novel` VALUES ('3', '1', '1', '我是标题B', '/uploads/2019-03-15/201903150346055349.jpeg', '1', '<p>666666666666</p>', '1', null, '2019-03-15 03:46:05');
INSERT INTO `novel` VALUES ('4', '1', '1', '213123123', '/uploads/2019-03-15/201903150308398747.jpeg', '123123123', '<p><strong>123123123</strong><br/></p>', '2', null, null);

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '父类ID',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限名字',
  `url` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限的url地址',
  `is_menu` enum('1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '是否显示菜单 1否 2是',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '权限排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES ('1', '0', '首页', 'admin.home', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('2', '0', '系统设置', '#', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('3', '2', '权限列表', 'admin.permission.list', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('4', '2', '权限添加', 'admin.permission.create', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('5', '2', '执行权限添加', 'admin.permission.doCreate', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('7', '0', '用户管理', '#', '1', '102', null, null);
INSERT INTO `permissions` VALUES ('8', '7', '用户添加', 'admin.user.add', '1', '103', null, null);
INSERT INTO `permissions` VALUES ('9', '7', '用户列表', 'admin.user.list', '1', '104', null, null);
INSERT INTO `permissions` VALUES ('28', '0', '小说管理', '#', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('16', '2', '权限删除', 'admin.permission.del', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('14', '2', '角色列表', 'admin.role.list', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('15', '2', '角色添加', 'admin.role.create', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('17', '2', 'ajax权限列表数据接口', 'admin.get.permission.list', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('19', '7', '执行用户添加', 'admin.user.store', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('20', '7', '删除用户', 'admin.user.del', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('21', '7', '用户编辑', 'admin.user.edit', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('22', '2', '执行角色添加', 'admin.role.store', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('23', '2', '角色删除', 'admin.role.del', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('24', '2', '角色编辑', 'admin.role.edit', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('25', '2', '执行角色编辑', 'admin.role.doEdit', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('26', '2', '角色权限编辑', 'admin.role.permission', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('27', '2', '执行角色权限编辑', 'admin.role.permission.save', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('34', '28', '作者列表', 'admin.author.list', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('30', '28', '添加作者', 'admin.author.create', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('31', '28', '执行作者添加', 'admin.author.store', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('33', '28', '删除作者', 'admin.author.del', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('39', '28', '分类列表', 'admin.category.list', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('40', '28', '分类添加', 'admin.category.create', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('37', '28', '执行分类添加', 'admin.category.store', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('38', '28', '删除分类', 'admin.category.del', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('41', '28', '小说列表', 'admin.novel.list', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('42', '28', '小说添加', 'admin.novel.create', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('43', '28', '执行小说添加', 'admin.novel.store', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('44', '28', '删除小说', 'admin.novel.del', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('45', '28', '小说编辑', 'admin.novel.edit', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('46', '28', '小说执行编辑', 'admin.novel.doEdit', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('56', '28', '编辑小说章节列表', 'admin.chapter.edit', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('52', '28', '删除小说章节', 'admin.chapter.del', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('50', '28', '小说章节列表', 'admin.chapter.list', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('55', '28', '执行编辑小说章节列表', 'admin.chapter.doEdit', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('57', '28', '小说评论列表', 'admin.novel.comment.list', '1', '100', null, null);
INSERT INTO `permissions` VALUES ('58', '28', '小说评论删除', 'admin.novel.comment.del', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('59', '28', '小说评论审核', 'admin.novel.comment.check', '2', '100', null, null);
INSERT INTO `permissions` VALUES ('60', '28', '小说数据', 'admin.novel.comment.data', '2', '100', null, null);

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名字',
  `role_desc` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色描述',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', '权限管理员', '管理权限的', null, '2019-03-14 08:17:37');
INSERT INTO `role` VALUES ('2', '用户管理员', '管理用户的', null, '2019-03-14 08:17:55');
INSERT INTO `role` VALUES ('3', '角色管理员', '管理角色的', null, '2019-03-14 08:18:11');
INSERT INTO `role` VALUES ('4', '小说作者管理员', '管理小说作者的', null, '2019-03-14 10:43:35');
INSERT INTO `role` VALUES ('5', '小说分类管理员', '管理小说分类的', null, null);
INSERT INTO `role` VALUES ('6', '小说内容管理员', '管理小说内容的', null, null);

-- ----------------------------
-- Table structure for role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL COMMENT '角色id',
  `p_id` int(11) NOT NULL COMMENT '权限id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of role_permissions
-- ----------------------------
INSERT INTO `role_permissions` VALUES ('91', '4', '33');
INSERT INTO `role_permissions` VALUES ('90', '4', '31');
INSERT INTO `role_permissions` VALUES ('89', '4', '30');
INSERT INTO `role_permissions` VALUES ('88', '4', '34');
INSERT INTO `role_permissions` VALUES ('5', '1', '1');
INSERT INTO `role_permissions` VALUES ('6', '1', '2');
INSERT INTO `role_permissions` VALUES ('7', '1', '3');
INSERT INTO `role_permissions` VALUES ('8', '1', '4');
INSERT INTO `role_permissions` VALUES ('9', '1', '5');
INSERT INTO `role_permissions` VALUES ('10', '1', '16');
INSERT INTO `role_permissions` VALUES ('11', '1', '17');
INSERT INTO `role_permissions` VALUES ('12', '2', '1');
INSERT INTO `role_permissions` VALUES ('13', '2', '7');
INSERT INTO `role_permissions` VALUES ('14', '2', '19');
INSERT INTO `role_permissions` VALUES ('15', '2', '20');
INSERT INTO `role_permissions` VALUES ('16', '2', '21');
INSERT INTO `role_permissions` VALUES ('17', '2', '8');
INSERT INTO `role_permissions` VALUES ('18', '2', '9');
INSERT INTO `role_permissions` VALUES ('94', '5', '39');
INSERT INTO `role_permissions` VALUES ('93', '5', '28');
INSERT INTO `role_permissions` VALUES ('92', '5', '1');
INSERT INTO `role_permissions` VALUES ('87', '4', '28');
INSERT INTO `role_permissions` VALUES ('73', '3', '27');
INSERT INTO `role_permissions` VALUES ('72', '3', '26');
INSERT INTO `role_permissions` VALUES ('71', '3', '25');
INSERT INTO `role_permissions` VALUES ('70', '3', '24');
INSERT INTO `role_permissions` VALUES ('86', '4', '1');
INSERT INTO `role_permissions` VALUES ('69', '3', '23');
INSERT INTO `role_permissions` VALUES ('68', '3', '22');
INSERT INTO `role_permissions` VALUES ('67', '3', '15');
INSERT INTO `role_permissions` VALUES ('66', '3', '14');
INSERT INTO `role_permissions` VALUES ('65', '3', '2');
INSERT INTO `role_permissions` VALUES ('64', '3', '1');
INSERT INTO `role_permissions` VALUES ('95', '5', '40');
INSERT INTO `role_permissions` VALUES ('96', '5', '37');
INSERT INTO `role_permissions` VALUES ('97', '5', '38');
INSERT INTO `role_permissions` VALUES ('98', '6', '1');
INSERT INTO `role_permissions` VALUES ('99', '6', '28');
INSERT INTO `role_permissions` VALUES ('100', '6', '42');
INSERT INTO `role_permissions` VALUES ('101', '6', '43');
INSERT INTO `role_permissions` VALUES ('102', '6', '44');
INSERT INTO `role_permissions` VALUES ('103', '6', '41');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `phone` char(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `password` char(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户密码',
  `image_url` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户头像',
  `token` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'token值',
  `expired_at` datetime NOT NULL COMMENT '过期时间',
  `status` enum('1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '用户状态 1启用 2封号',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user
-- ----------------------------

-- ----------------------------
-- Table structure for user_role
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user_role
-- ----------------------------
INSERT INTO `user_role` VALUES ('2', '14', '2');
INSERT INTO `user_role` VALUES ('4', '3', '4');
INSERT INTO `user_role` VALUES ('8', '6', '5');
