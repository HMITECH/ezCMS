-- ezSite SQL Dump (FOR UPGRADE FROM Version 3.XXXXX)
-- http://www.hmi-tech.net
--
-- Desc: This sql file will update the database from ezSite Builder to v4
-- Author: Mohd Ahmed (mo.ahmed@hmi-tech.net)
--
-- Host: localhost
--
-- Database: `ezsite_db`
--
-- --------------------------------------------------------

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

ALTER TABLE `pages` 
DROP `showinmenu`,
DROP `isredirected`,
DROP `redirect`,
DROP `cont`,
ADD `nositemap` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'True to skip in sitemap' AFTER `layout`,
ADD `createdby` INT(16) NOT NULL DEFAULT '1' COMMENT 'Id of the user who created this page' AFTER `nositemap`, 
ADD `createdon` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation date and time' AFTER `createdby`,
CHANGE `maincontent`   `maincontent`   LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'main content of page', 
CHANGE `headercontent` `headercontent` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'header content of page', 
CHANGE `footercontent` `footercontent` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'footer content of page', 
CHANGE `sidecontent`   `sidecontent`   LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'side content of page', 
CHANGE `sidercontent`  `sidercontent`  LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'right side-bar content';

ALTER TABLE `site` 
ADD `createdby` INT(16) NOT NULL DEFAULT '1' COMMENT 'User who changed the settings' AFTER `appenddesc`, 
ADD `createdon` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Revision date and time' AFTER `createdby`,
CHANGE `headercontent` `headercontent` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'header content of page', 
CHANGE `footercontent` `footercontent` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'footer content of page', 
CHANGE `sidecontent`   `sidecontent`   LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'side content of page', 
CHANGE `sidercontent`  `sidercontent`  LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'right side-bar content';

CREATE TABLE IF NOT EXISTS `git_files` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id of revision',
  `content` longtext COMMENT 'contents of the file',
  `fullpath` varchar(1000) NOT NULL COMMENT 'Full Path and Name of file',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'Id of the user who created this Revision',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Revision date and time',
  PRIMARY KEY (`id`),
  KEY `fullpath` (`fullpath`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='revision log of files';

CREATE TABLE IF NOT EXISTS `git_pages` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'Revision id of page',
  `page_id` int(16) NOT NULL COMMENT 'id of original page',
  `pagename` text COMMENT 'name of page',
  `title` text COMMENT 'title of page',
  `keywords` text COMMENT 'keywords for page',
  `description` text COMMENT 'decription of page',
  `maincontent` longtext COMMENT 'main content of page',
  `useheader` tinyint(1) DEFAULT NULL COMMENT 'true to use header else defaults',
  `headercontent` longtext COMMENT 'header content of page',
  `usefooter` tinyint(1) DEFAULT NULL COMMENT 'true to use footer else defaults',
  `footercontent` longtext COMMENT 'footer content of page',
  `useside` tinyint(1) DEFAULT NULL COMMENT 'true to use side bar else defaults',
  `sidecontent` longtext COMMENT 'side content of page',
  `published` tinyint(1) DEFAULT NULL COMMENT 'true if pulished on site',
  `parentid` int(16) DEFAULT NULL COMMENT 'id of parent page',
  `place` int(8) NOT NULL DEFAULT '0' COMMENT 'position of the page',
  `url` text COMMENT 'the seo friendly url',
  `sidercontent` longtext COMMENT 'right side-bar content',
  `usesider` tinyint(1) DEFAULT '0' COMMENT 'append keyword',
  `head` text NOT NULL COMMENT 'contents of custom head',
  `layout` text COMMENT 'name of the layout file to use with this page',
  `nositemap` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True to skip in sitemap',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'Id of the user who created this Revision',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Revision date and time',
  PRIMARY KEY (`id`),
  KEY `createdby` (`createdby`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Revision of web pages in the site';

-- --------------------------------------------------------

