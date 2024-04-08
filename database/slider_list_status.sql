/*
 Navicat MySQL Data Transfer

 Source Server         : KOHALIK
 Source Server Type    : MySQL
 Source Server Version : 80030
 Source Host           : localhost:3306
 Source Schema         : qcubed-5

 Target Server Type    : MySQL
 Target Server Version : 80030
 File Encoding         : 65001

 Date: 04/04/2024 02:44:36
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of slider_list_status
-- ----------------------------
BEGIN;
INSERT INTO `slider_list_status` VALUES (1, 1, 'Public slider', '<i class=\"fa fa-circle fa-lg\" aria-hidden=\"true\" style=\"color: #449d44; line-height: .1;\"></i>  Public slider', 1);
INSERT INTO `slider_list_status` VALUES (2, 2, 'Hidden slider', '<i class=\"fa fa-circle fa-lg\" aria-hidden=\"true\" style=\"color: #ff0000; line-height: .1;\"></i> Hidden slider', 1);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
