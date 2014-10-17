-- ezcms boilder plate SQL Dump (FOR CMS TABLES)
-- version 2.0.010413
-- http://www.hmi-tech.net
--
-- Desc: This sql file will create the sql table for CMS
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

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id of page',
  `pagename` text COMMENT 'name of page',
  `title` text COMMENT 'title of page',
  `keywords` text COMMENT 'keywords for page',
  `description` text COMMENT 'decription of page',
  `maincontent` text COMMENT 'main content of page',
  `useheader` tinyint(1) DEFAULT NULL COMMENT 'true to use header else defaults',
  `headercontent` text COMMENT 'header content of page',
  `usefooter` tinyint(1) DEFAULT NULL COMMENT 'true to use footer else defaults',
  `footercontent` text COMMENT 'footer content of page',
  `useside` tinyint(1) DEFAULT NULL COMMENT 'true to use side bar else defaults',
  `sidecontent` text COMMENT 'side content of page',
  `published` tinyint(1) DEFAULT NULL COMMENT 'true if pulished on site',
  `showinmenu` tinyint(1) DEFAULT NULL COMMENT 'true if displayed as menu item',
  `parentid` int(16) DEFAULT NULL COMMENT 'id of parent page',
  `isredirected` tinyint(1) DEFAULT '0' COMMENT 'true if page is to be redirected',
  `redirect` text NOT NULL COMMENT 'If specified this page will redirect',
  `place` int(8) NOT NULL DEFAULT '0' COMMENT 'position of the page',
  `url` text COMMENT 'the seo friendly url',
  `sidercontent` text COMMENT 'right side-bar content',
  `showinsubmenu` tinyint(1) DEFAULT '0' COMMENT 'show in sub menu',
  `usesider` tinyint(1) DEFAULT '0' COMMENT 'append keyword',
  `head` text NOT NULL COMMENT 'contents of custom head',
  `cont` text NOT NULL COMMENT 'contents of custom controller',
  `layout` text COMMENT 'name of the layout file to use with this page',
  PRIMARY KEY (`id`),
  KEY `published` (`published`),
  FULLTEXT KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='the dynamic web pages in the site';

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `pagename`, `title`, `keywords`, `description`, `maincontent`, `useheader`, `headercontent`, `usefooter`, `footercontent`, `useside`, `sidecontent`, `published`, `showinmenu`, `parentid`, `isredirected`, `redirect`, `place`, `url`, `sidercontent`, `showinsubmenu`, `usesider`, `head`, `cont`, `layout`) VALUES
(1, 'home', 'Home', '', '', '<h2><span style="color: rgb(0, 0, 255);">Home page conteghjg gjh gjhnt goes here !</span></h2>\r\n\r\n<blockquote>\r\n<p>Use the bhkjhkj hkjhkjhjk .</p>\r\n</blockquote>\r\n', 1, '<h1 style="text-align: center;">Welcome to ezcms</h1>\r\n\r\n<p><span style="color: rgb(105, 105, 105);">Use the Control Panel to add banners, menus, logos and much more here ...</span></p>\r\n', 0, '', 0, '', 1, 0, 0, 0, '', 1, '/', '', 0, 0, '', '', 'layout.home.php'),
(2, 'Page-Not-Found', 'Page Not Found', '', '', '', 0, '', 0, '', 0, '', 1, 0, 0, 0, '', 1, '/Page-Not-Found.html', '', 0, 0, '', '', 'layout.php'),
(3, 'contact', 'Contact', '', '', '<h1>Contents of the Contact Page goes</h1>\r\n\r\n<p>Use the back end of the ezSite builder to add con</p>\r\n', 0, '', 0, '', 0, '', 1, 0, 1, 0, '', 11, '/contact.html', '', 0, 0, '', '', 'layout.php'),
(4, 'about', 'About', '', '', '', 0, '', 0, '', 0, '', 1, 0, 1, 0, '', 3, '/about.html', '', 0, 0, '', '', 'layout.php'),
(5, 'sitemap', 'Sitemap', '', '', '', 1, '<h1 style="text-align: center;">Sitemap Example</h1>\r\n\r\n<p style="text-align: center;">This page is an example of how to dynamically create and display the sitemap.</p>\r\n', 0, '', 1, '<h3><span style="color: rgb(128, 0, 0);">Steps to Create your own</span></h3>\r\n\r\n<ol>\r\n	<li>Create a new layout for the sitemap<br />\r\n	&nbsp;</li>\r\n	<li>In the top of the layout file add php code to create the sitemap from the published pages in the site. See the layout file of this page as an example.<br />\r\n	&nbsp;</li>\r\n	<li>Create a new page and set its layout to one created above.<br />\r\n	&nbsp;</li>\r\n	<li>You can style the page as needed by adding custom css to the page head or layout file directly.</li>\r\n</ol>\r\n', 1, 0, 1, 0, '', 5, '/sitemap.html', '', 0, 0, '<style type="text/css">\r\n	#content .entry-title {\r\n		font-weight:bold;\r\n		font-size:1.2em;\r\n	}\r\n	#content .entry-description {\r\n		font-size:1em;\r\n	}	\r\n	#content .entry-keywords {\r\n		color:#000033;\r\n		font-size:0.9em;\r\n		font-style:italic;\r\n	}\r\n	#content li {\r\n		margin-bottom:5px;\r\n	}\r\n	#content li a {\r\n		color:#003366;\r\n	}		\r\n	#content li a:hover {\r\n		color:#FF0000;\r\n		background-color:#CCCCCC;\r\n	}		\r\n</style>', '', 'layout.sitemap.php'),
(6, 'search', 'Search', '', '', '', 1, '<h1 style="text-align: center;">Search Example</h1>\r\n\r\n<p style="text-align: center;">This page is an example of how to handle search in the site.</p>\r\n', 0, '', 1, '<h3><span style="color: rgb(128, 0, 0);">Steps to Create your own</span></h3>\r\n\r\n<ol>\r\n	<li><span style="color: rgb(105, 105, 105);">Create a new layout for the search and in the php code on the top handle the GET param for the search.</span><br />\r\n	&nbsp;</li>\r\n	<li><span style="color: rgb(105, 105, 105);">Create a page for the search results and display the results from the layout.</span><br />\r\n	&nbsp;</li>\r\n	<li><span style="color: rgb(105, 105, 105);">See the code of this page and this layout for an example.</span></li>\r\n</ol>\r\n', 1, 0, 1, 0, '', 6, '/search.html', '<h3 style="text-align: right;"><span style="color: rgb(128, 0, 0);">Autocomeplete Search</span></h3>\r\n\r\n<p style="text-align: right;">To add auto comeplete functionality to the search box you must include jquery and a plugin.</p>\r\n\r\n<p style="text-align: right;">In&nbsp; this&nbsp; example&nbsp; we have used jQuery UI Autocomplete .</p>\r\n\r\n<p style="text-align: right;">In the layout we have checked for GET param ajax and if set then we have return the results in json.</p>\r\n\r\n<p style="text-align: right;">On the&nbsp; page we&nbsp; have included js to bind the textbox to autocomplete&nbsp; with source as the same page with GET param ajax set.</p>\r\n', 0, 1, '<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />\r\n<script src="http://code.jquery.com/jquery-1.9.1.js"></script>\r\n<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>\r\n<style type="text/css">\r\n.highlight {\r\n	background-color: #FFFF00;\r\n}\r\n#content .entry-title {\r\n	font-weight:bold;\r\n	font-size:1.2em;\r\n}\r\n#content .entry-description {\r\n	font-size:1em;\r\n}	\r\n#content .entry-keywords {\r\n	color:#000033;\r\n	font-size:0.9em;\r\n	font-style:italic;\r\n}\r\n#content li {\r\n	margin-bottom:5px;\r\n}\r\n#content li a {\r\n	color:#003366;\r\n}		\r\n#content li a:hover {\r\n	color:#FF0000;\r\n	background-color:#CCCCCC;\r\n}\r\n			\r\n</style>', '', 'layout.search.php');

-- --------------------------------------------------------

--
-- Table structure for table `site`
--

CREATE TABLE IF NOT EXISTS `site` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'id of site settings',
  `title` text NOT NULL COMMENT 'title of the site',
  `keywords` text NOT NULL COMMENT 'default keywords',
  `description` text NOT NULL COMMENT 'default description',
  `headercontent` text COMMENT 'default header content ',
  `footercontent` text COMMENT 'default footer content',
  `sidecontent` text COMMENT 'default side bar content',
  `sidercontent` text COMMENT 'right side-bar content',
  `appendtitle` tinyint(1) DEFAULT '0' COMMENT 'append title',
  `appendkey` tinyint(1) DEFAULT '0' COMMENT 'append keyword',
  `appenddesc` tinyint(1) DEFAULT '0' COMMENT 'append desc',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='the default settings used in the site';

--
-- Dumping data for table `site`
--

INSERT INTO `site` (`id`, `title`, `keywords`, `description`, `headercontent`, `footercontent`, `sidecontent`, `sidercontent`, `appendtitle`, `appendkey`, `appenddesc`) VALUES
(1, '', '', '', '<h1 style="text-align: center;"><span style="color: rgb(0, 128, 128);">Default HEADER</span></h1>\r\n\r\n<p><span style="color: rgb(105, 105, 105);">Use the Control Panel to add banners, menus, logos and much more here ...</span></p>\r\n', '<h4 style="text-align: center;"><span style="color: rgb(105, 105, 105);">Default FOOTER</span></h4>\r\n\r\n<p style="text-align: center;"><span style="color: rgb(105, 105, 105);">Use the Control Panel to add banners, menus, logos and much more here ...</span></p>\r\n', '<h3><span style="color: rgb(128, 0, 0);">Default<br />\r\nLEFT SIDEBAR</span></h3>\r\n\r\n<blockquote>\r\n<p><span style="color: rgb(105, 105, 105);">Use the Control Panel to add banners, menus, logos and much more here ...</span></p>\r\n</blockquote>\r\n', '<h3 style="text-align: right;"><span style="color: rgb(128, 0, 0);">Default<br />\r\nRIGHT SIDEBAR</span></h3>\r\n\r\n<blockquote>\r\n<p style="text-align: right;"><span style="color: rgb(105, 105, 105);">Use the Control Panel to add banners, menus, logos and much more here ...</span></p>\r\n</blockquote>\r\n', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'id of user',
  `username` text NOT NULL COMMENT 'name of user',
  `email` text NOT NULL COMMENT 'email address of user',
  `passwd` text NOT NULL COMMENT 'password for the user',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'able to login',
  `editpage` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit page',
  `delpage` tinyint(1) NOT NULL DEFAULT '0',
  `viewstats` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'can view site statistics',
  `edituser` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'can administer other users',
  `deluser` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'delete users',
  `editsettings` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit settings',
  `editcont` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit controller',
  `editlayout` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit layout',
  `editcss` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit css',
  `editjs` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit js',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='the users of this site';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `passwd`, `active`, `editpage`, `delpage`, `viewstats`, `edituser`, `deluser`, `editsettings`, `editcont`, `editlayout`, `editcss`, `editjs`) VALUES
(1, 'Webmaster', 'admin', 'admin', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'HMI Support Team', 'support@hmi-tech.net', 'support@hmi', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
