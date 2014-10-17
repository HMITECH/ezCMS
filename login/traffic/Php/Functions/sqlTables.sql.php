<?php
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2008 ZoneO-soft, Butchu (email: "butchu" with the domain "zoneo.net")

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 More Info About The Licence At http://www.gnu.org/copyleft/gpl.html
****************************************************************************/

$nsql = 46;

$sql = "CREATE TABLE `${table}_pages` ( `id` mediumint(9) unsigned NOT NULL auto_increment, `name` tinytext $utf NOT NULL, `added` date NOT NULL default '0000-00-00',   `ref` mediumint(9) default '0', `se` mediumint(9) default '0',`internal` mediumint(9) default '0', `other` mediumint(9) default '0', `old` mediumint(9) default '0', UNIQUE KEY `id` (`id`) ) TYPE=MyISAM
CREATE TABLE `${table}_acces` ( `id` mediumint(9) unsigned NOT NULL auto_increment, `label` mediumint(9) unsigned NOT NULL default '0', `date` date NOT NULL default '0000-00-00', `count` mediumint(9) unsigned NOT NULL default '0', PRIMARY KEY (`id`) ) TYPE=MyISAM
CREATE TABLE `${table}_host` ( `date` datetime default NULL, `host` varchar(20) NOT NULL default '', `hostname` tinytext NOT NULL, `page` tinytext $utf NOT NULL, `ref` text NOT NULL, `agent` tinytext NOT NULL, `longIP` bigint(11) NOT NULL default '0') TYPE=MyISAM
CREATE TABLE `${table}_country` ( `id` mediumint(9) unsigned NOT NULL auto_increment, `label` varchar(4) NOT NULL default '', `date` date NOT NULL default '0000-00-00', `count` mediumint(9) unsigned NOT NULL default '0', UNIQUE KEY `id` (`id`) ) TYPE=MyISAM
CREATE TABLE `${table}_hour` ( `value` tinyint(4) default NULL, `count` mediumint(9) unsigned default NULL ) TYPE=MyISAM
CREATE TABLE `${table}_day` ( `value` tinyint(4) default NULL, `count` mediumint(9) unsigned default NULL ) TYPE=MyISAM
CREATE TABLE `${table}_os` ( `id` mediumint(9) unsigned NOT NULL auto_increment, `label` varchar(50) NOT NULL default '', `date` date NOT NULL default '0000-00-00', `count` mediumint(9) unsigned NOT NULL default '0', PRIMARY KEY (`id`) ) TYPE=MyISAM
CREATE TABLE `${table}_browser` ( `id` mediumint(9) unsigned NOT NULL auto_increment, `label` varchar(50) NOT NULL default '', `date` date NOT NULL default '0000-00-00', `count` mediumint(9) unsigned NOT NULL default '0', PRIMARY KEY (`id`) ) TYPE=MyISAM
CREATE TABLE `${table}_keyword` ( `id` mediumint(9) unsigned NOT NULL auto_increment, `engine` tinytext $utf NOT NULL, `keyword` text $utf NOT NULL, `page` mediumint(9) unsigned NOT NULL default '0', `first` datetime NOT NULL default '0000-00-00 00:00:00', `last` datetime NOT NULL default '0000-00-00 00:00:00', `count` mediumint(9) unsigned NOT NULL default '0', PRIMARY KEY (`id`) ) TYPE=MyISAM
CREATE TABLE `${table}_referrer` ( `id` mediumint(9) unsigned NOT NULL auto_increment, `address` text NOT NULL, `page` mediumint(9) unsigned NOT NULL default '0', `first` datetime NOT NULL default '0000-00-00 00:00:00', `last` datetime NOT NULL default '0000-00-00 00:00:00', `count` mediumint(9) unsigned NOT NULL default '0', `visited` BOOL NULL DEFAULT '0', PRIMARY KEY (`id`) ) TYPE=MyISAM
CREATE TABLE `${table}_path` ( `id` mediumint(9) unsigned NOT NULL auto_increment,  `first` datetime NOT NULL default '0000-00-00 00:00:00', `last` datetime NOT NULL default '0000-00-00 00:00:00', `entry` mediumint(9) unsigned default '0', `exit` mediumint(9) unsigned default '0', `path` tinytext NOT NULL,`length` mediumint(9) unsigned NOT NULL default '0', `count` mediumint(9) unsigned NOT NULL default '0', PRIMARY KEY (`id`) ) TYPE=MyISAM
CREATE TABLE `${table}_uniq` ( `id` mediumint(9) unsigned NOT NULL auto_increment, `label` mediumint(9) unsigned default '0', `date` date NOT NULL default '0000-00-00', `count` mediumint(9) unsigned NOT NULL default '0', PRIMARY KEY (`id`) ) TYPE=MyISAM
INSERT INTO ${table}_day VALUES (0, 0)
INSERT INTO ${table}_day VALUES (1, 0)
INSERT INTO ${table}_day VALUES (2, 0)
INSERT INTO ${table}_day VALUES (3, 0)
INSERT INTO ${table}_day VALUES (4, 0)
INSERT INTO ${table}_day VALUES (5, 0)
INSERT INTO ${table}_day VALUES (6, 0)
INSERT INTO ${table}_hour VALUES (0, 0)
INSERT INTO ${table}_hour VALUES (1, 0)
INSERT INTO ${table}_hour VALUES (2, 0)
INSERT INTO ${table}_hour VALUES (3, 0)
INSERT INTO ${table}_hour VALUES (4, 0)
INSERT INTO ${table}_hour VALUES (5, 0)
INSERT INTO ${table}_hour VALUES (6, 0)
INSERT INTO ${table}_hour VALUES (7, 0)
INSERT INTO ${table}_hour VALUES (8, 0)
INSERT INTO ${table}_hour VALUES (9, 0)
INSERT INTO ${table}_hour VALUES (10, 0)
INSERT INTO ${table}_hour VALUES (11, 0)
INSERT INTO ${table}_hour VALUES (12, 0)
INSERT INTO ${table}_hour VALUES (13, 0)
INSERT INTO ${table}_hour VALUES (14, 0)
INSERT INTO ${table}_hour VALUES (15, 0)
INSERT INTO ${table}_hour VALUES (16, 0)
INSERT INTO ${table}_hour VALUES (17, 0)
INSERT INTO ${table}_hour VALUES (18, 0)
INSERT INTO ${table}_hour VALUES (19, 0)
INSERT INTO ${table}_hour VALUES (20, 0)
INSERT INTO ${table}_hour VALUES (21, 0)
INSERT INTO ${table}_hour VALUES (22, 0)
INSERT INTO ${table}_hour VALUES (23, 0)
CREATE TABLE `${table}_retention` (id mediumint(8) unsigned NOT NULL auto_increment, date date NOT NULL default '0000-00-00', mode tinyint(4) NOT NULL default '0', length tinyint(4) NOT NULL default '0', count mediumint(9) NOT NULL default '0',PRIMARY KEY  (id)) TYPE=MyISAM
CREATE TABLE `${table}_resolution` ( `id` mediumint(9) unsigned NOT NULL auto_increment, `label` varchar(50) NOT NULL default '', `date` date NOT NULL default '0000-00-00', `count` mediumint(9) unsigned NOT NULL default '0', PRIMARY KEY (`id`) ) TYPE=MyISAM
CREATE TABLE `${table}_iplist` ( `id` mediumint(9) unsigned NOT NULL auto_increment, `ip` bigint(11) NOT NULL default '0', `label` varchar(50) $utf NOT NULL default '', `first` datetime NOT NULL default '0000-00-00 00:00:00', `last` datetime NOT NULL default '0000-00-00 00:00:00', `count` mediumint(9) unsigned NOT NULL default '0', PRIMARY KEY (`id`) ) TYPE=MyISAM";
?>
