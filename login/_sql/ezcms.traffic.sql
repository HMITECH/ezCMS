-- ezcms boilder plate SQL Dump (FOR TRAFFIC TABLES)
-- version 2.0.010413
-- http://www.hmi-tech.net
--
-- Desc: This sql file will create the sql table for traffic
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
-- Table structure for table `phpTrafficA_conf`
--

CREATE TABLE IF NOT EXISTS `phpTrafficA_conf` (
  `variable` varchar(32) NOT NULL DEFAULT '',
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `phpTrafficA_conf`
--

INSERT INTO `phpTrafficA_conf` (`variable`, `value`) VALUES
('browser_list', 'Bot|Crawler\nbot|Crawler\ncrawler|Crawler\nCrawler|Crawler\nOpera Mini|Opera Mini\nOpera/10|Opera 10\nOpera/9|Opera 9\nOpera 9|Opera 9\nOpera 8|Opera 8\nOpera/8|Opera 8\nIEMobile|IEMobile\nMSIE 8|MSIE 8\nMSIE 7|MSIE 7\nMSIE 6|MSIE 6\nFirefox|Firefox\nfirefox|Firefox\nChrome|Chrome\nSafari|Safari\nKonqueror/3|Konqueror 3\nGooglebot|Googlebot\nMediapartners|Google Adwords\narchive.org|Crawler\nmsnbot|Crawler\nSlurp|Crawler\nMSIE 5|MSIE 5\nrobot|Crawler\nclikserver|Crawler\nvbseo|Crawler\nPlayStation Portable|Sony PSP\nPLAYSTATION|Sony Playstation\nNokia|Nokia\nBlackBerry|BlackBerry\nNetscape6|NS 6\nNetscape/6|NS 6\nNetscape7|NS 7\nNetscape/7|NS 7\nGaleon|Galeon\nMinimo|Minimo\nNetFront|NetFront\nGecko|Gecko\niCab|iCab\nMozilla/4|NS 4\nWebCrawler|Crawler\nNutch|Crawler\nia_archiver|Crawler\ninktomi|Crawler\nVoilaBot|Crawler\nTeleport Pro|Crawler\nWebStripper|Crawler\nWebZIP|Crawler\nNetcraft Web|Crawler\nInternetSeer|Crawler\nScooter|Crawler\nKSbot|Crawler\nzyborg|Crawler\nNG|Crawler\nNaverBot|Crawler\nTurnitinBot|Crawler\npsbot|Crawler\nNaverRobot|Crawler\nalmaden.ibm.com|Crawler\nantibot|Crawler\nPompos|Crawler\nHenriLeRobotMirago|Crawler\ngrub|Crawler\nWget|Crawler\nCrawler|Crawler\ncrawler|Crawler\nFranklin|Crawler\nAsk Jeeves|Crawler\nIDBot|Crawler\nValidator|Crawler\nDumbot|Crawler\nBecomeBot|Crawler\nMJ12bot|Crawler\ne-SocietyRobot|Crawler\nStackRambler|Crawler\nYandex|Crawler\nIndy Library|Crawler\nlwp-trivial|Crawler\nSnapbot|Crawler\nMuncher|Crawler\nOnetSzukaj|Crawler\nBaiduspider|Crawler\nSzukacz|Crawler\nGigamega.bot|Crawler \nigdeSpyder|Crawler \nlibwww-perl|Crawler \nCharlotte|Crawler\noBot|Crawler \neSyndiCat|Crawler \nLiteFinder|Crawler \nMail.Ru|Crawler \nJava|Crawler \nMSFrontPage|Crawler\nWebDAV|Crawler\ncazoodle|Crawler\nGigabot|Crawler\nDotBot|Crawler\nWordPress|Crawler\noldscriptfinder|Crawler\nspider|Crawler\nY!J-BSC|Crawler\nproximic|Crawler\nscoutjet|Crawler\nSUNPlex|SunPlex\nMSIE 1|MSIE 1\nMSIE 3|MSIE 3\nMSIE 4|MSIE 4\nLynx/2|Lynx 2\nHTTrack|HTTrack\nOmniWeb|OmniWeb\nOpera 3|Opera 3\nOpera/3|Opera 3\nOpera 4|Opera 4\nOpera/4|Opera 4\nOpera 5|Opera 5\nOpera/5|Opera 5\nOpera 6|Opera 6\nOpera/6|Opera 6\nOpera 7|Opera 7\nOpera/7|Opera 7\nBlazer|Blazer\nMozilla/3|NS 3\nKonqueror/2|Konqueror 2\nMozilla/0.91|Mosaic'),
('os_list', 'Windows NT 6.0|Win Vista\nWindows NT 5.2|Win 2003 \nWindows NT 5.1|Win XP\nWindows XP|Win XP\nMacintosh|Mac\nAndroid|Android\nlinux|Linux\nLinux|Linux\nRedHat|Linux\nPalm|Palm OS\nWindows 2000|Win 2000\nWindows NT 5.0|Win 2000\nWin 9x|Win 98\nWin98|Win 98\nWindows 98|Win 98\niPhone|iPhone\nWindows CE|Win CE\nPlayStation Portable|Sony PSP\nPLAYSTATION|Sony Playstation\nSonyEricsson|Sony Ericsson\nNokia|Nokia\nBlackBerry|BlackBerry\nMac_PPC|Mac\nMac_PowerPC|Mac\nWindows ME|Win Me\nSymbian|Symbian OS\nJ2ME|Java ME\nWin95|Win 95\nWinNT|Win NT\nWin32|Win\nWindows 95|Win 95\nWindows_95|Win 95\nWindows NT|Win NT\nWindows|Win\nSunOS|SunOS\nSUNPlex|SunOS\nOSF1|OSF1\nHP-UX|HP Unix\nIRIX|SGI IRIX\nNetBSD|NetBSD\nFreeBSD|FreeBSD\nOpenBSD|OpenBSD\nbeOS|beOS\nKonqueror|Linux'),
('save_host', '400'),
('search_engines', 'Google|google|as_q:q:query:as_epq\nMSN|msn|q\nYahoo|yahoo|p:va:vp\nLive Search|search.live.com|q\nBing|bing.com|q\n123people|123people|search_term\nGoogle Cache|209.85.229.132|q\nAlice|alice|qs\n7Metasearch|7metasearch.com|q\n7Search|7search.com|qu\nAbout|about.com|terms\nAllTheWeb|alltheweb.com|query:q\nAllTheInternet|alltheinternet.com|q\nAltavista|altavista.com|q\nAOL|aol|query:q:r\nAsk|ask.co.uk|q\nAsk.com|ask.com|q\nATT|att.net|qry\nBellsouth|home.bellsouth.net|Keywords:string\nBiglobe Japan|cgi.search.biglobe.ne.jp|q\nBlueWin Switzerland|bluewin.ch|q:qry\nClub Internet (France)|club-internet.fr|q\nChello|chello|srchText:q1\nDevilfinder.com|devilfinder.com|q\nOpen Directory Project|dmoz.org|search\nDogpile|dogpile.com|q:q_all\nElmundo Spain|ariadna.elmundo.es|q\nExalead|exalead|q\nEniro.fi|eniro.fi|q\nEniro.se|eniro.se|q\nExcite|excite|search:q\ngazeta.pl|szukaj.gazeta.pl|slowo\nGoogle/Gotonet|gotonet.google|q\nGoogle Groups|groups.google|q\nGoogle Images|images.google|prev\nGoogle WAP|wap.google|q:query\nHanafos|hanafos.com|query\nHispatvista|buscar.hispavista.com|cadena\nHotBot|hotbot|query\nIOL|iol|B1:q:query\ninteria.pl|www.google.interia.pl|q\nIXQuick|ixquick.com|query\nIwon.com|iwon.com|searchfor:q\nKartoo.com|kartoo.com|q\nKolumbus Finland|kolumbus.fi|q\nKvasir.no|kvasir|q\nLibero|arianna.libero.it|query\nLookSmart|looksmart.com|key:qt\nLycos|lycos|MT:query\nMamma.com|mamma.com|query\nMirago|mirago|qry:txtSearch\nMSN|msn|MT,q:q\nMyNet|arama.mynet.com|q\nMyWay|myway.com|searchfor\nMyWay|mywebsearch.com|searchfor\nNANA Israel|nana.co.il|string:q\nNaver|naver.com|query:oldquery\nNetBul|kapi.netbul.com|keyword\nNetscape|netscape|search:q:query\nNetsprint.pl|www.netsprint.pl|q\nNomade France|nomade|MT:s\nNeuf.fr|neuf.fr|Keywords\no2.pl|szukaj2.o2.pl|qt\nonet.pl|szukaj.onet.pl|qt\nOpera|search.opera.com|search\nOverture|overture.com|Keywords\nSearch.com|search.com|q\nSeznam.cz|seznam.cz|q\nCentrum.cz|centrum.cz|q \nSzukacz|www.szukacz.pl|q\nSpray Sweden|spray.se|query\nSympatico|sympatico|query\nTele2internet|tele2internet.fr|query\nT-Online(Germany)|t-online.de|q\nToile.com|toile.com|q\nTut.by|tut.by|query\nUOL Argentina|uol.com|q\nVoila|voila|rdata:kw|iso-8859-1\nYa|buscador.ya.com|q:item\nWalla Israel|find.walla.co.il|q\nWeb.de|web.de|su\nWebsearch.com|websearch.com|qkw\nWWW Finland|www.fi|query\nWebalta|webalta.ru|q\nwp.pl|szukaj.wp.pl|szukaj\nya.com|ya.com|q\nzworks|zworks.com|what\nAport|aport.ru|r|Windows-1251\nBigmir.net|bigmir.net|q|Windows-1251\nFast-diets|fast-diets.ru|word|Windows-1251\nGougle|gougle.ru|q|Windows-1251\nMail|mail.ru|q|Windows-1251\nMamont|mmnt.ru|st|Windows-1251\nMeta.ua|meta.ua|q|Windows-1251\nNigma|nigma.ru|q:s:request_str\nPoisk|poisk|text|Windows-1251\nRambler|rambler.ru|query \nRambler|rambler.ru|words|Windows-1251 \nRambler|r0.ru|words|Windows-1251\nRefers|refers.ru|query|Windows-1251\nGoGo|gogo.ru|q|Windows-1251\nReknet|reknet.ru|query|Windows-1251\nYandex|yandex.ru|text\nConduit(google)| â€‹conduit.â€‹com|â€‹q\nYanga|â€‹yanga.co.uk|â€‹q'),
('blacklist', 'miragorobot.com\naolrecherche\nantibot.net\nnetscape\nsearch1-2.free.fr\nsearch1-1.free.fr\n+++++++++++++++\nwww.planetis.com\naolsearch.aol.com\nwww.fastbot.com\nOutpost\nesearchandfind.org\nslt.alexa.com\nnumericable.fr\nonline-pharmacy\nbuy-\nwww.omagiu.net\nrx.paromi.net\nmx.gs\nwww.automatique-marketing.com\nthomasblake.floridavirtualhomes.com\ncams-sex-live\ncollegefuckfest\nallinternal\nbrutalblowjobs\nmedsgenerica\n â€‹ fuckherthroat\nasstraffic\nwrongsideoftown\nlucky-blackjack\nsexyhit\nmp3main.com\nviagra\ncialis\nporno\nbest-replica\ndiet-pill\njanssen-beauty\npills. â€‹ com\nfree--pics.org\nwww.dekorfilm.se/\nwww.ithink.pl/sklep-wedkarski/\nporn\nfiskekommunerna\npornstars\nwww.southfloridacondos.org/\nwww.florida-condos.org'),
('ntop', '10'),
('ntoplong', '15'),
('seref', '0'),
('visitcutoff', '15'),
('stringLengthsFactor', '10'),
('version', '2.3'),
('referrerNewDuration', '4'),
('cleanRefIPKwdPath', '1'),
('cleanRefIPKwdPathInt', '5'),
('lastCleanRefIPKwdPath', '2013-04-09'),
('cleanAccess', '1'),
('cleanAccessInt', '5'),
('lastCleanAccess', '2013-04-09'),
('adminpassword', '64b171738a3c690236613fa004b6d9c6');

-- --------------------------------------------------------

--
-- Table structure for table `phpTrafficA_conf_ipban`
--

CREATE TABLE IF NOT EXISTS `phpTrafficA_conf_ipban` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `ip` bigint(11) NOT NULL DEFAULT '0',
  `range` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `last` date NOT NULL DEFAULT '0000-00-00',
  `count` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `phpTrafficA_conf_ipban`
--

INSERT INTO `phpTrafficA_conf_ipban` (`id`, `ip`, `range`, `date`, `last`, `count`) VALUES
(1, 1503002185, 0, '2011-07-21', '2011-07-21', 0),
(2, 3563010647, 0, '2011-07-21', '2011-07-21', 0),
(3, 1502999742, 0, '2011-07-21', '2011-07-21', 0),
(4, 3563010653, 0, '2011-07-21', '2011-07-21', 0),
(5, 1094150912, 255, '2011-07-21', '2011-07-21', 0),
(6, 1295124391, 0, '2011-07-21', '2011-07-21', 0),
(7, 1094182656, 255, '2011-07-21', '2011-07-21', 0),
(8, 1407821144, 0, '2011-07-21', '2011-07-21', 0),
(9, 3430725442, 0, '2011-07-21', '2011-07-21', 0),
(10, 1358824362, 0, '2011-07-21', '2011-07-21', 0),
(11, 3572198177, 0, '2011-07-21', '2011-07-21', 0),
(12, 2903757061, 0, '2011-07-21', '2011-07-21', 0),
(13, 1387123051, 0, '2011-07-21', '2011-07-21', 0),
(14, 1094151168, 255, '2011-07-21', '2011-07-21', 0),
(15, 1503002125, 0, '2011-07-21', '2011-07-21', 0),
(16, 1094165760, 255, '2011-07-21', '2011-07-21', 0),
(17, 1094182912, 255, '2011-07-21', '2011-07-21', 0),
(18, 1382227456, 127, '2011-07-21', '2011-07-21', 0),
(19, 1094151424, 511, '2011-07-21', '2011-07-21', 0),
(20, 3264889386, 0, '2011-07-21', '2011-07-21', 0),
(21, 3585972875, 0, '2011-07-21', '2011-07-21', 0),
(22, 1086272098, 0, '2011-07-21', '2011-07-21', 0),
(23, 404154472, 0, '2011-07-21', '2011-07-21', 0);

-- --------------------------------------------------------

--
-- Table structure for table `phpTrafficA_conf_sites`
--

CREATE TABLE IF NOT EXISTS `phpTrafficA_conf_sites` (
  `id` mediumint(9) NOT NULL,
  `table` varchar(100) NOT NULL,
  `site` varchar(255) NOT NULL,
  `public` tinyint(1) NOT NULL,
  `trim` tinyint(1) NOT NULL,
  `crawler` tinyint(1) NOT NULL,
  `counter` tinyint(1) NOT NULL,
  `timediff` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `phpTrafficA_conf_sites`
--

INSERT INTO `phpTrafficA_conf_sites` (`id`, `table`, `site`, `public`, `trim`, `crawler`, `counter`, `timediff`) VALUES
(39547, 'traffic_', 'ezSite', 0, 1, 1, 0, 17);

-- --------------------------------------------------------

--
-- Table structure for table `traffic__acces`
--

CREATE TABLE IF NOT EXISTS `traffic__acces` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `label` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `count` mediumint(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `traffic__browser`
--

CREATE TABLE IF NOT EXISTS `traffic__browser` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `count` mediumint(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `traffic__country`
--

CREATE TABLE IF NOT EXISTS `traffic__country` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(4) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `count` mediumint(9) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `traffic__day`
--

CREATE TABLE IF NOT EXISTS `traffic__day` (
  `value` tinyint(4) DEFAULT NULL,
  `count` mediumint(9) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `traffic__day`
--

INSERT INTO `traffic__day` (`value`, `count`) VALUES
(0, 0),
(1, 0),
(2, 0),
(3, 0),
(4, 0),
(5, 0),
(6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `traffic__host`
--

CREATE TABLE IF NOT EXISTS `traffic__host` (
  `date` datetime DEFAULT NULL,
  `host` varchar(20) NOT NULL DEFAULT '',
  `hostname` tinytext NOT NULL,
  `page` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ref` text NOT NULL,
  `agent` tinytext NOT NULL,
  `longIP` bigint(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `traffic__hour`
--

CREATE TABLE IF NOT EXISTS `traffic__hour` (
  `value` tinyint(4) DEFAULT NULL,
  `count` mediumint(9) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `traffic__hour`
--

INSERT INTO `traffic__hour` (`value`, `count`) VALUES
(0, 0),
(1, 0),
(2, 0),
(3, 0),
(4, 0),
(5, 0),
(6, 0),
(7, 0),
(8, 0),
(9, 0),
(10, 0),
(11, 0),
(12, 0),
(13, 0),
(14, 0),
(15, 0),
(16, 0),
(17, 0),
(18, 0),
(19, 0),
(20, 0),
(21, 0),
(22, 0),
(23, 0);

-- --------------------------------------------------------

--
-- Table structure for table `traffic__iplist`
--

CREATE TABLE IF NOT EXISTS `traffic__iplist` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `ip` bigint(11) NOT NULL DEFAULT '0',
  `label` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `first` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `count` mediumint(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `traffic__keyword`
--

CREATE TABLE IF NOT EXISTS `traffic__keyword` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `engine` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `keyword` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `page` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `first` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `count` mediumint(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `traffic__os`
--

CREATE TABLE IF NOT EXISTS `traffic__os` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `count` mediumint(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `traffic__pages`
--

CREATE TABLE IF NOT EXISTS `traffic__pages` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `added` date NOT NULL DEFAULT '0000-00-00',
  `ref` mediumint(9) DEFAULT '0',
  `se` mediumint(9) DEFAULT '0',
  `internal` mediumint(9) DEFAULT '0',
  `other` mediumint(9) DEFAULT '0',
  `old` mediumint(9) DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `traffic__path`
--

CREATE TABLE IF NOT EXISTS `traffic__path` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `first` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `entry` mediumint(9) unsigned DEFAULT '0',
  `exit` mediumint(9) unsigned DEFAULT '0',
  `path` tinytext NOT NULL,
  `length` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `count` mediumint(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `traffic__referrer`
--

CREATE TABLE IF NOT EXISTS `traffic__referrer` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `address` text NOT NULL,
  `page` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `first` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `count` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `visited` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `traffic__resolution`
--

CREATE TABLE IF NOT EXISTS `traffic__resolution` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `count` mediumint(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `traffic__retention`
--

CREATE TABLE IF NOT EXISTS `traffic__retention` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `mode` tinyint(4) NOT NULL DEFAULT '0',
  `length` tinyint(4) NOT NULL DEFAULT '0',
  `count` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `traffic__uniq`
--

CREATE TABLE IF NOT EXISTS `traffic__uniq` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `label` mediumint(9) unsigned DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `count` mediumint(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
