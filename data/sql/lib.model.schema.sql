
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- sf_guard_user_profile
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `sf_guard_user_profile`;


CREATE TABLE `sf_guard_user_profile`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_id` INTEGER  NOT NULL,
	`email` VARCHAR(50)  NOT NULL,
	`avatar` VARCHAR(128),
	`modified_at` DATETIME  NOT NULL,
	`last_commented` DATETIME,
	`last_uploaded` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `sf_guard_user_profile_FI_1` (`user_id`),
	CONSTRAINT `sf_guard_user_profile_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `sf_guard_user` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- replay_game_type
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `replay_game_type`;


CREATE TABLE `replay_game_type`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(8),
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- replay
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `replay`;


CREATE TABLE `replay`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_id` INTEGER  NOT NULL,
	`game_type_id` INTEGER  NOT NULL,
	`category_id` INTEGER  NOT NULL,
	`game_info` TEXT  NOT NULL,
	`description` TEXT  NOT NULL,
	`avg_apm` SMALLINT(2) UNSIGNED  NOT NULL,
	`players` VARCHAR(255)  NOT NULL,
	`map_name` VARCHAR(255)  NOT NULL,
	`download_count` SMALLINT(3) UNSIGNED default 0 NOT NULL,
	`published_at` DATETIME,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	`reported_count` SMALLINT(3) UNSIGNED default 0,
	PRIMARY KEY (`id`),
	INDEX `replay_FI_1` (`user_id`),
	CONSTRAINT `replay_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `sf_guard_user` (`id`),
	INDEX `replay_FI_2` (`game_type_id`),
	CONSTRAINT `replay_FK_2`
		FOREIGN KEY (`game_type_id`)
		REFERENCES `replay_game_type` (`id`),
	INDEX `replay_FI_3` (`category_id`),
	CONSTRAINT `replay_FK_3`
		FOREIGN KEY (`category_id`)
		REFERENCES `replay_category` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- replay_category
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `replay_category`;


CREATE TABLE `replay_category`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`code` VARCHAR(50)  NOT NULL,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- replay_category_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `replay_category_i18n`;


CREATE TABLE `replay_category_i18n`
(
	`name` VARCHAR(50)  NOT NULL,
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	CONSTRAINT `replay_category_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `replay_category` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- replay_comment
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `replay_comment`;


CREATE TABLE `replay_comment`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_id` INTEGER  NOT NULL,
	`replay_id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	`comment` TEXT  NOT NULL,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `replay_comment_FI_1` (`user_id`),
	CONSTRAINT `replay_comment_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `sf_guard_user` (`id`)
		ON DELETE CASCADE,
	INDEX `replay_comment_FI_2` (`replay_id`),
	CONSTRAINT `replay_comment_FK_2`
		FOREIGN KEY (`replay_id`)
		REFERENCES `replay` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- replay_oftheweek
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `replay_oftheweek`;


CREATE TABLE `replay_oftheweek`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`replay_id` INTEGER  NOT NULL,
	`description` TEXT  NOT NULL,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `replay_oftheweek_FI_1` (`replay_id`),
	CONSTRAINT `replay_oftheweek_FK_1`
		FOREIGN KEY (`replay_id`)
		REFERENCES `replay` (`id`)
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
