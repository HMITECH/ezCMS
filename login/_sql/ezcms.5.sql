-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2018 at 09:48 PM
-- Server version: 5.7.9-log
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ezsite_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `git_files`
--

DROP TABLE IF EXISTS `git_files`;
CREATE TABLE IF NOT EXISTS `git_files` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id of revision',
  `content` longtext COMMENT 'contents of the file',
  `fullpath` varchar(1000) NOT NULL COMMENT 'Full Path and Name of file',
  `revmsg` TEXT NULL DEFAULT NULL COMMENT 'Revision Message',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'Id of the user who created this Revision',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Revision date and time',
  PRIMARY KEY (`id`),
  KEY `fullpath` (`fullpath`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='revision log of files';

-- --------------------------------------------------------

--
-- Table structure for table `git_pages`
--

DROP TABLE IF EXISTS `git_pages`;
CREATE TABLE IF NOT EXISTS `git_pages` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'Revision id of page',
  `page_id` int(16) NOT NULL COMMENT 'id of original page',
  `pagename` varchar(512) NOT NULL COMMENT 'name of page',
  `title` varchar(1024) NOT NULL COMMENT 'title of page',
  `keywords` varchar(1024) DEFAULT NULL COMMENT 'keywords for page',
  `description` varchar(1024) DEFAULT NULL COMMENT 'decription of page',
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Revision of web pages in the site';

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id of page',
  `pagename` varchar(512) NOT NULL COMMENT 'name of page',
  `title` varchar(1024) NOT NULL COMMENT 'title of page',
  `keywords` varchar(1024) DEFAULT NULL COMMENT 'keywords for page',
  `description` varchar(1024) DEFAULT NULL COMMENT 'decription of page',
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
  `url` varchar(2048) NOT NULL COMMENT 'the seo friendly url',
  `sidercontent` longtext COMMENT 'right side-bar content',
  `usesider` tinyint(1) DEFAULT '0' COMMENT 'append keyword',
  `head` text NOT NULL COMMENT 'contents of custom head',
  `layout` text COMMENT 'name of the layout file to use with this page',
  `nositemap` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True to skip in sitemap',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'Id of the user who created this page',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation date and time',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `published` (`published`),
  KEY `createdby` (`createdby`),
  KEY `place` (`place`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='the dynamic web pages in the site';

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `pagename`, `title`, `keywords`, `description`, `maincontent`, `useheader`, `headercontent`, `usefooter`, `footercontent`, `useside`, `sidecontent`, `published`, `parentid`, `place`, `url`, `sidercontent`, `usesider`, `head`, `layout`, `nositemap`, `createdby`, `createdon`) VALUES
(1, 'home', 'Home', '', '', '<!--  Content  -->\r\n<h1>Welcome to ezCMS - Home page</h1>\r\n\r\n<p>Edit this content from the ezCMS using the <strong>''Pages'' - ''Home''</strong> Menu.</p>\r\n\r\n<p><a target="_blank" href="/login/setting.php">go to editor</a></p>', 1, '<!--  Header  -->\r\n<h2>CUSTOM HOMEPAGE HEADER <a target="_blank" href="/login/pages.php#header">editor link</a></h2>\r\n<nav>\r\n	<a href="/">HOME</a>\r\n	<a href="/about">ABOUT</a>\r\n	<a href="/about/team">TEAM</a>	\r\n	<a href="/contact">CONTACT</a>	\r\n</nav>', 1, '<!--  Footer  -->\r\n<p>CUSTOM HOMEPAGE FOOTER <a target="_blank" href="/login/pages.php#footers">editor link</a></p>', 0, '<!--  Aside 1  -->', 1, 0, 1, '/', '<!--  Aside 2  -->', 0, '<!--  Head content  -->', 'layout.php', 0, 1, '2016-10-17 11:18:34'),
(2, 'Page-Not-Found', 'Page Not Found', '', '', '<!--  Content  -->\r\n<h1>404 Page not Found !</h1>\r\n\r\n<p>Edit this content from the ezCMS using the <strong>''Pages'' - ''Page Not Found''</strong> Menu.</p>\r\n\r\n<p><a target="_blank" href="/login/pages.php?id=2">go to editor</a></p>', 0, '', 0, '', 0, '', 1, 0, 1, '/Page-Not-Found.html', '', 0, '', 'layout.php', 0, 1, '2016-10-17 11:18:34'),
(3, 'contact', 'Contact', '', '', '<!--  Content  -->\r\n<h1>Contact page</h1>\r\n\r\n<p>Edit this content from the ezCMS using the <strong>''Pages'' - ''Contact''</strong> Menu.</p>\r\n\r\n<p><a target="_blank" href="/login/pages.php">go to editor</a></p>', 0, '', 0, '', 0, '', 1, 1, 11, '/contact', '', 0, '', 'layout.rightASIDE.php', 0, 1, '2016-10-17 11:18:34'),
(4, 'about', 'About Page', '', '', '<!--  Content  -->\r\n<h1>About page</h1>\r\n\r\n<p>Edit this content from the ezCMS using the <strong>''Pages'' - ''About Page''</strong> Menu.</p>\r\n\r\n<p><a target="_blank" href="/login/pages.php">go to editor</a></p>', 0, '', 0, '', 0, '', 1, 1, 0, '/about', '', 0, '', 'layout.leftASIDE.php', 0, 1, '2018-01-06 17:02:03'),
(5, 'team', 'Team', '', '', '<!--  Content  -->\r\n<h1>About / TEAM page</h1>\r\n\r\n<p>Edit this content from the ezCMS using the <strong>''Pages'' - ''TEAM''</strong> Menu.</p>\r\n\r\n<p><a target="_blank" href="/login/pages.php">go to editor</a></p>', 0, '', 0, '', 0, '', 1, 4, 33, '/about/team', '', 0, '', 'layout.leftASIDE.php', 0, 1, '2018-01-10 20:40:02');

-- --------------------------------------------------------

--
-- Table structure for table `site`
--

DROP TABLE IF EXISTS `site`;
CREATE TABLE IF NOT EXISTS `site` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'id of site settings',
  `headercontent` longtext COMMENT 'header content of page',
  `footercontent` longtext COMMENT 'footer content of page',
  `sidecontent` longtext COMMENT 'side content of page',
  `sidercontent` longtext COMMENT 'right side-bar content',
  `revmsg` TEXT NULL DEFAULT NULL COMMENT 'Revision Message',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'User who changed the settings',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Revision date and time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='the default settings used in the site';

--
-- Dumping data for table `site`
--

INSERT INTO `site` (`id`, `headercontent`, `footercontent`, `sidecontent`, `sidercontent`, `createdby`, `createdon`) VALUES
(1, '<!--  Header  -->\r\n<h2>DEFAULT SITE HEADER <a target="_blank" href="/login/setting.php">editor link</a></h2>\r\n<nav>\r\n	<a href="/">HOME</a>\r\n	<a href="/about">ABOUT</a>\r\n	<a href="/about/team">TEAM</a>	\r\n	<a href="/contact">CONTACT</a>	\r\n</nav>', '<!--  Footer  -->\r\n<p><em>DEFAULT SITE FOOTER</em> <a target="_blank" href="/login/setting.php">editor link</a></p>', '<!--  Aside 1  -->\r\n<h4>DEFAULT SITE ASIDE 1 </h4>\r\n\r\n<p>Edit this content from the ezCMS using the <strong>''Template'' - ''Default settings Menu''</strong>.<br>\r\n<a target="_blank" href="/login/setting.php">go to editor</a></p>', '<!--  Aside 2  -->\r\n<h4>DEFAULT SITE ASIDE 2</h4>\r\n\r\n<p>Edit this content from the ezCMS using the <strong>''Template'' - ''Default settings Menu''</strong>.<br>\r\n<a target="_blank" href="/login/setting.php">go to editor</a></p>', 1, '2018-01-10 21:42:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'id of user',
  `username` varchar(512) NOT NULL COMMENT 'name of user',
  `email` varchar(512) NOT NULL COMMENT 'email address of user',
  `passwd` varchar(512) NOT NULL COMMENT 'password has for the user',
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
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Revision date and time',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `passwd` (`passwd`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='the users of this site';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `passwd`, `active`, `editpage`, `delpage`, `viewstats`, `edituser`, `deluser`, `editsettings`, `editcont`, `editlayout`, `editcss`, `editjs`, `createdon`) VALUES
(1, 'Webmaster', 'admin', '8450eca01665516d9aeb5317764902b78495502637c96192c81b1683d32d691a0965cf037feca8b9ed9ee6fc6ab8f27fce8f77c4fd9b4a442a00fc317b8237e6', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2018-01-10 21:28:15');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
