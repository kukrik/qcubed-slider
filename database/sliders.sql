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

 Date: 06/03/2024 20:19:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for sliders
-- ----------------------------
DROP TABLE IF EXISTS `sliders`;
CREATE TABLE `sliders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int unsigned DEFAULT NULL,
  `order` int unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_vi_0900_ai_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vi_0900_ai_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_vi_0900_ai_ci DEFAULT NULL,
  `dimensions` varchar(255) COLLATE utf8mb4_vi_0900_ai_ci DEFAULT NULL,
  `width` int DEFAULT NULL,
  `top` int DEFAULT NULL,
  `status` int unsigned DEFAULT NULL,
  `post_date` datetime DEFAULT NULL,
  `post_update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status_idx` (`status`) USING BTREE,
  KEY `group_id_idx` (`group_id`) USING BTREE,
  CONSTRAINT `group_id_sliders_ibfk` FOREIGN KEY (`group_id`) REFERENCES `list_of_sliders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `status_sliders_ibfk` FOREIGN KEY (`status`) REFERENCES `status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vi_0900_ai_ci;

SET FOREIGN_KEY_CHECKS = 1;
