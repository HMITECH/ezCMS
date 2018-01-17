DBUSER=ezcms
DBPASS=ezcms

DRYRUN=
LOGDIR=$(TMP)
XAMPP=c:/xampp

MYSQL_EXE=$(XAMPP)/mysql/bin/mysql
MYSQL=$(DRYRUN) $(MYSQL_EXE) -u$(DBUSER) -p$(DBPASS) --silent --tee=$(LOGDIR)/mysql.log

make-user::
	$(MYSQL_EXE) -umosh -p -e "\
	CREATE USER ezcms@localhost IDENTIFIED BY 'ezcms'; \
	GRANT ALL PRIVILEGES ON ezcms.* TO 'ezcms'@'localhost';  \
	FLUSH PRIVILEGES; \
	"

db::
	$(MYSQL) < login/_sql/ezcms.5.sql

tags::
	perl -S moshtags.pl *.php */*.php login/class/*.php

