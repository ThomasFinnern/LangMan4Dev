CREATE TABLE IF NOT EXISTS `#__lang4dev_base` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  
  `xxx` tinyint NOT NULL DEFAULT '1',

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__lang4dev_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL,
  `params` text NOT NULL,
  
  
  `xxx` tinyint NOT NULL DEFAULT '1',
  `yyy` tinyint NOT NULL DEFAULT '1',
  
  
  
  `checked_out` int unsigned NOT NULL DEFAULT 0,
  `checked_out_time` datetime, 
  `created` datetime NOT NULL,
  `created_by` int unsigned NOT NULL DEFAULT 0,
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  
  `version` int unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

