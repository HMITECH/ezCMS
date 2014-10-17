-- ezCMS SQL Dump (FOR UPGRADE FROM SITE BUILDER)
-- version 2.0.010413
-- http://www.hmi-tech.net
--
-- Desc: This sql file will update the database from ezSite Builder to ezCMS
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

DROP TABLE `advance`;

ALTER TABLE `pages` 
	ADD `head` TEXT NULL COMMENT 'contents of custom head',
	ADD `cont` TEXT NULL COMMENT 'contents of custom controller',
	ADD `layout` TEXT NULL COMMENT 'name of the layout file to use with this page';
	
/* RUN THIS IS 	phpTrafficA appears as phptraffica */
RENAME TABLE `phptraffica_conf` TO `phpTrafficA_conf`;
RENAME TABLE `phptraffica_conf_ipban` TO `phpTrafficA_conf_ipban`;
RENAME TABLE `phptraffica_conf_sites` TO `phpTrafficA_conf_sites`;
/* END - RUN FOR phpTrafficA appears as phptraffica */
	
UPDATE `phpTrafficA_conf_sites` SET `trim` = 0 WHERE `id` =39547;
UPDATE `phpTrafficA_conf` SET `value` = '1' WHERE `variable` = 'cleanAccess' LIMIT 1 ;
UPDATE `phpTrafficA_conf` SET `value` = '1' WHERE `variable` = 'cleanRefIPKwdPath' LIMIT 1 ;

ALTER TABLE `users` DROP `publishpage`;
ALTER TABLE `users` DROP `usemailer`;
ALTER TABLE `users` DROP `addpage`;

ALTER TABLE `users` ADD `deluser` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'delete users';
ALTER TABLE `users` ADD `editsettings` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'edit settings';
ALTER TABLE `users` ADD `editcont` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'edit controller';
ALTER TABLE `users` ADD `editlayout` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'edit layout';
ALTER TABLE `users` ADD `editcss` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'edit css';
ALTER TABLE `users` ADD `editjs` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'edit js';

ALTER TABLE `users` CHANGE `admin` `edituser` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'can administer other users';
ALTER TABLE `users` CHANGE `viewstats` `viewstats` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'can view site statistics';
ALTER TABLE `users` CHANGE `delpage` `delpage` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'delete page';
ALTER TABLE `users` CHANGE `editpage` `editpage` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'edit page';
ALTER TABLE `users` CHANGE `active` `active` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'able to login';		

ALTER TABLE `users` CHANGE `passwd` `passwd` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'password for the user';
ALTER TABLE `users` CHANGE `email` `email` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'email address of user';
ALTER TABLE `users` CHANGE `username` `username` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'name of user';