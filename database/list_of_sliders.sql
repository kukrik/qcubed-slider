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

 Date: 04/04/2024 02:52:02
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for list_of_sliders
-- ----------------------------
DROP TABLE IF EXISTS `list_of_sliders`;
CREATE TABLE `list_of_sliders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vi_0900_ai_ci NOT NULL,
  `admin_status` int unsigned DEFAULT '2',
  `status` int unsigned DEFAULT '2',
  `post_date` datetime DEFAULT NULL,
  `post_update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status_idx` (`status`) USING BTREE,
  KEY `id_idx` (`id`) USING BTREE,
  KEY `admin_status_idx` (`admin_status`),
  CONSTRAINT `list_of_sliders_ibfk_2` FOREIGN KEY (`status`) REFERENCES `slider_list_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `list_of_sliders_ibfk_3` FOREIGN KEY (`admin_status`) REFERENCES `status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vi_0900_ai_ci;

-- ----------------------------
-- Records of list_of_sliders
-- ----------------------------
BEGIN;
INSERT INTO `list_of_sliders` VALUES (1, 'Sponsors', 1, 1, '2024-03-06 22:26:00', '2024-04-03 08:38:20');
INSERT INTO `list_of_sliders` VALUES (2, 'Advertising', 1, 1, '2024-03-07 21:24:41', '2024-04-03 08:53:56');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
