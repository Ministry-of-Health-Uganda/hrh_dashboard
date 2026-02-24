-- Approval trail: records pipeline runs and optional snapshot from APM matrix (e.g. Africa CDC matrix 44).
-- Run this once to create the table.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `approval_trail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `source_url` varchar(500) NOT NULL COMMENT 'APM matrix or approval trail URL',
  `event_type` varchar(64) NOT NULL DEFAULT 'national_audit_completed',
  `matrix_id` int(11) DEFAULT NULL COMMENT 'e.g. 44 for APM matrix 44',
  `response_snapshot` longtext DEFAULT NULL COMMENT 'Optional JSON/response from GET source_url',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `event_type` (`event_type`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Approval trail: pipeline runs and APM matrix snapshots';
