/*
 Navicat MySQL Data Transfer

 Source Server         : KOHALIK
 Source Server Type    : MySQL
 Source Server Version : 90300
 Source Host           : localhost:3306
 Source Schema         : qcubed-4

 Target Server Type    : MySQL
 Target Server Version : 90300
 File Encoding         : 65001

 Date: 21/07/2025 11:21:33
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for slider_list_status
-- ----------------------------
DROP TABLE IF EXISTS `slider_list_status`;
CREATE TABLE `slider_list_status` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `is_enabled` int NOT NULL,
  `written_status` varchar(255) NOT NULL DEFAULT '2',
  `drawn_status` varchar(255) NOT NULL,
  `visibility` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_enabled` (`is_enabled`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of slider_list_status
-- ----------------------------
BEGIN;
INSERT INTO `slider_list_status` VALUES (1, 1, 'Public slider', '<i class=\"fa fa-circle fa-lg\" aria-hidden=\"true\" style=\"color: #449d44; line-height: .1;\"></i>  Public slider', 1);
INSERT INTO `slider_list_status` VALUES (2, 2, 'Hidden slider', '<i class=\"fa fa-circle fa-lg\" aria-hidden=\"true\" style=\"color: #ff0000; line-height: .1;\"></i> Hidden slider', 1);
COMMIT;

-- ----------------------------
-- Table structure for sliders
-- ----------------------------
DROP TABLE IF EXISTS `sliders`;
CREATE TABLE `sliders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int unsigned DEFAULT NULL,
  `file_id` int unsigned DEFAULT NULL,
  `order` int unsigned DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vi_0900_ai_ci DEFAULT '',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vi_0900_ai_ci DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vi_0900_ai_ci DEFAULT NULL,
  `extension` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vi_0900_ai_ci DEFAULT NULL,
  `dimensions` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vi_0900_ai_ci DEFAULT NULL,
  `width` int unsigned DEFAULT NULL,
  `height` int unsigned DEFAULT NULL,
  `top` int DEFAULT NULL,
  `status` int unsigned DEFAULT NULL,
  `post_date` datetime DEFAULT NULL,
  `post_update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status_idx` (`status`) USING BTREE,
  KEY `group_id_idx` (`group_id`) USING BTREE,
  KEY `order_id_idx` (`order`) USING BTREE,
  CONSTRAINT `status_sliders_ibfk` FOREIGN KEY (`status`) REFERENCES `status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vi_0900_ai_ci;


-- ----------------------------
-- Table structure for sliders_list
-- ----------------------------
DROP TABLE IF EXISTS `sliders_list`;
CREATE TABLE `sliders_list` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vi_0900_ai_ci NOT NULL,
  `status` int unsigned DEFAULT '2',
  `post_date` datetime DEFAULT NULL,
  `post_update_date` datetime DEFAULT NULL,
  `post_update_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status_idx` (`status`) USING BTREE,
  KEY `id_idx` (`id`) USING BTREE,
  KEY `post_update_user` (`post_update_user`) USING BTREE,
  CONSTRAINT `sliders_list_ibfk_2` FOREIGN KEY (`status`) REFERENCES `slider_list_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vi_0900_ai_ci;

-- ----------------------------
-- Records of sliders_list
-- ----------------------------
BEGIN;
INSERT INTO `sliders_list` VALUES (1, 'Advertisements', 1, '2024-03-07 21:24:41', '2025-07-20 11:01:56', NULL);
INSERT INTO `sliders_list` VALUES (2, 'Sponsors', 1, '2024-03-06 22:26:00', '2025-07-20 13:17:28', NULL);
COMMIT;

-- ----------------------------
-- Table structure for status
-- ----------------------------
DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `is_enabled` int NOT NULL,
  `written_status` varchar(255) NOT NULL DEFAULT '2',
  `drawn_status` varchar(255) NOT NULL,
  `visibility` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_enabled` (`is_enabled`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of status
-- ----------------------------
BEGIN;
INSERT INTO `status` VALUES (1, 1, 'Published', '<i class=\"fa fa-circle fa-lg\" aria-hidden=\"true\" style=\"color: #449d44; line-height: .1;\"></i>  Published', 1);
INSERT INTO `status` VALUES (2, 2, 'Hidden', '<i class=\"fa fa-circle fa-lg\" aria-hidden=\"true\" style=\"color: #ff0000; line-height: .1;\"></i> Hidden', 1);
INSERT INTO `status` VALUES (3, 3, 'Draft', '<i class=\"fa fa-circle-o fa-lg\" aria-hidden=\"true\" style=\"color: #000000; line-height: .1;\"></i> Draft', 1);
INSERT INTO `status` VALUES (4, 4, 'Waiting...', '<i class=\"fa fa-circle fa-lg\" aria-hidden=\"true\" style=\"color: #ffb00c; line-height: .1;\"></i> Waiting...', 1);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
