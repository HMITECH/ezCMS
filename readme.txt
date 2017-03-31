2016-10-03 Mon
  set SITE=ezsite
  Adding a SITE to xampp:
    cd C:/xampp/apache/conf/extra/
    Add this line at the bottom of the httpd-vhosts.conf file:
      Include "mosh-hmi-vhosts.conf"

  vi C:/xampp/apache/conf/extra/mosh-hmi-vhosts.conf
    <VirtualHost *:80>
      DocumentRoot "C:/xampp/htdocs/hmi/$SITE/"
      ServerName  $SITE.local
      ServerAlias www.$SITE.local
      ErrorLog "logs/$SITE.errlog"
      CustomLog "logs/$SITE.log" common
      <directory "C:/xampp/htdocs/hmi/$SITE">
        Options Indexes FollowSymLinks
        AllowOverride all
        Order Deny,Allow
        Deny from all
        Allow from 127.0.0.1
      </directory>
    </VirtualHost>
 
  vi C:/Windows/system32/drivers/etc/hosts
    127.0.0.1      $SITE.local
    127.0.0.1      www.$SITE.local
    # ---
    127.0.0.1      talenttutors.com
    ::1            talenttutors.com
    127.0.0.1      dev.cfar.nu
    ::1            dev.cfar.nu
    127.0.0.1      ezcms.com

2016-10-04 Tue 12:11
  diff --git a/config.php b/config.php
  +       $databaseUser   = 'ezsite_user';
  +       $databasePasswd = 'mosh123';

2016-10-06 Thu 21:00
  c:\ht\hmi\ezsite> git stash
    Saved working directory and index state WIP on master: a31800c
    README.md updated with ezSite
    Builder info HEAD is now at a31800c
    README.md updated with ezSite Builder info

2016-10-19 Wed 18:03
  Added /mode: "application/x-httpd-php",/ to login/layouts.php

2017-03-31 Fri 19:20
  Restarting work.

