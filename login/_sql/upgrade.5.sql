-- ezCMS SQL Dump (FOR UPGRADE FROM SITE BUILDER)
-- version 2.0.010413
-- http://www.hmi-tech.net
--
-- Desc: This sql file will update the database to ezCMS 5
-- Author: Mohd Ahmed (mo.ahmed@hmi-tech.net)
--
-- Host: localhost
-- Generation Time: Apr 05, 2013 at 11:01 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ezsite_db`
--

-- --------------------------------------------------------

ALTER TABLE `git_files` ENGINE=InnoDB;
ALTER TABLE `git_pages` ENGINE=InnoDB;
ALTER TABLE `pages` ENGINE=InnoDB;
ALTER TABLE `site` ENGINE=InnoDB;
ALTER TABLE `users` ENGINE=InnoDB;

DROP TABLE 	`phptraffica_conf`, 
			`phptraffica_conf_ipban`, 
			`phptraffica_conf_sites`, 
			`traffic__acces`, 
			`traffic__browser`, 
			`traffic__country`, 
			`traffic__day`, 
			`traffic__host`, 
			`traffic__hour`, 
			`traffic__iplist`, 
			`traffic__keyword`, 
			`traffic__os`, 
			`traffic__pages`, 
			`traffic__path`, 
			`traffic__referrer`, 
			`traffic__resolution`, 
			`traffic__retention`, 
			`traffic__uniq`;

ALTER TABLE `pages`
  DROP `showinmenu`,
  DROP `isredirected`,
  DROP `redirect`,
  DROP `cont`;

ALTER TABLE `users`
  DROP `viewstats`;
  
ALTER TABLE `site` 
	DROP `title` ,
	DROP `keywords` ,
	DROP `description` ,
	DROP `appendtitle` ,
	DROP `appendkey` ,
	DROP `appenddesc` ;


UPDATE `users` SET `passwd` = SHA2 (`passwd`, 512);

ALTER TABLE `users` 
ADD `editor` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'Editor to use in the cms',
ADD `createdon` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on';

ALTER TABLE `users` CHANGE `username` `username` VARCHAR(512) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'name of user';
ALTER TABLE `users` CHANGE `email` `email` VARCHAR(512) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'email address of user';
ALTER TABLE `users` CHANGE `passwd` `passwd` VARCHAR( 512 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'password has for the user'
ALTER TABLE `users` ADD INDEX ( `email` , `passwd` ) ;

ALTER TABLE `pages` CHANGE `pagename` `pagename` VARCHAR(512) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'name of page';
ALTER TABLE `pages` CHANGE `title` `title` VARCHAR(1024) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'title of page';
ALTER TABLE `pages` CHANGE `keywords` `keywords` VARCHAR(1024) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'keywords for page';
ALTER TABLE `pages` CHANGE `description` `description` VARCHAR(1024) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'decription of page';
ALTER TABLE `pages` CHANGE `url` `url` VARCHAR(2048) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'the seo friendly url';
ALTER TABLE `pages` ADD UNIQUE(`url`);
ALTER TABLE `pages` ADD INDEX(`createdby`);
ALTER TABLE `pages` ADD INDEX(`place`);





















