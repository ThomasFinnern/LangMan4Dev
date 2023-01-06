





--
-- main projects
--

CREATE TABLE IF NOT EXISTS `#__lang4dev_projects`
(
	`id`               int                                                    NOT NULL AUTO_INCREMENT,
	`title`            varchar(255)                                           NOT NULL DEFAULT '',
	`name`             varchar(255)                                           NOT NULL DEFAULT '',

	`alias`            varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
	`notes`            text                                                   NOT NULL,
	`root_path`        varchar(255)                                           NOT NULL DEFAULT '',
	`prjType`          int                                                    NOT NULL DEFAULT 0,

	`params`           text                                                   NOT NULL,

	`checked_out`      int unsigned                                           NOT NULL DEFAULT 0,
	`checked_out_time` datetime,
	`created`          datetime                                               NOT NULL,
	`created_by`       int unsigned                                           NOT NULL DEFAULT 0,
	`created_by_alias` varchar(255)                                           NOT NULL DEFAULT '',
	`modified`         datetime                                               NOT NULL,
	`modified_by`      int unsigned                                           NOT NULL DEFAULT 0,

	`twin_id`          int                                                    NOT NULL DEFAULT 0,

	`approved`         tinyint unsigned                                       NOT NULL DEFAULT '1',
	`asset_id`         int                                                    NOT NULL DEFAULT 0,
	`access`           int unsigned                                           NOT NULL DEFAULT 0,

	`version`          int unsigned                                           NOT NULL DEFAULT 1,

	`ordering`         int unsigned                                           NOT NULL DEFAULT '0',

	PRIMARY KEY (`id`),
	KEY `idx_access` (`access`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  DEFAULT COLLATE = utf8mb4_unicode_ci;



--
-- subprojects
--

CREATE TABLE IF NOT EXISTS `#__lang4dev_subprojects`
(
	`id`                  int                                                    NOT NULL AUTO_INCREMENT,
	`title`               varchar(255)                                           NOT NULL DEFAULT '',
	`alias`               varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
	`prjId`               varchar(255)                                           NOT NULL DEFAULT '',

	`subPrjType`          int                                                    NOT NULL DEFAULT 0,
	`root_path`           varchar(255)                                           NOT NULL DEFAULT '',

	`prefix`              varchar(255)                                           NOT NULL DEFAULT '',
	`notes`               text                                                   NOT NULL,
	`isLangAtStdJoomla`   int                                                    NOT NULL DEFAULT 0,

	`prjXmlPathFilename`  varchar(255)                                           NOT NULL DEFAULT '',
	`installPathFilename` varchar(255)                                           NOT NULL DEFAULT '',

	`parent_id`           int                                                    NOT NULL DEFAULT 0,
	`twin_id`             int                                                    NOT NULL DEFAULT 0,

	`lang_path_type`      varchar(255)                                           NOT NULL DEFAULT '',
	`lang_ids`            text                                                   NOT NULL,
	`params`              text                                                   NOT NULL,
	`ordering`            int unsigned                                           NOT NULL DEFAULT '0',

	`checked_out`         int unsigned                                           NOT NULL DEFAULT 0,
	`checked_out_time`    datetime,
	`created`             datetime                                               NOT NULL,
	`created_by`          int unsigned                                           NOT NULL DEFAULT 0,
	`created_by_alias`    varchar(255)                                           NOT NULL DEFAULT '',
	`modified`            datetime                                               NOT NULL,
	`modified_by`         int unsigned                                           NOT NULL DEFAULT 0,

	`published`           tinyint                                                NOT NULL DEFAULT '1',

	`approved`            tinyint unsigned                                       NOT NULL DEFAULT '1',
	`asset_id`            int unsigned                                           NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	`access`              int                                                    NOT NULL DEFAULT 0,

	`version`             int unsigned                                           NOT NULL DEFAULT 1,

	PRIMARY KEY (`id`),
	KEY `idx_access` (`access`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  DEFAULT COLLATE = utf8mb4_unicode_ci;
