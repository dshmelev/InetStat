#!/bin/sh
#
#
#	It is config:
#	#!/bin/sh
#	#
#	#
#	iface_WAN="192.168.0.2"
#	iface_LAN="192.168.1.1"
#	ip_WAN="ra0"
#	ip_LAN="xl1"
#
#	LAN_mask="192.168.0"
#
#	db_host="localhost"
#	db_user="trafd"
#	db_passwd="trafd"
#	db_db="trafd"
#
#	mysql="/usr/local/bin/mysql"
#
. ./config

echo "su -l root -c \"cd /usr/local/inetstat/ && git pull >> var/update.log\"" >> bin/update.sh
chmod 755 bin/update.sh
chmod 755 bin/traffic.sh

echo "*/10	*	*	*	*	/usr/local/inetstat/bin/traffic.sh" >> /var/cron/tabs/root
echo "*	*	*	*	*	/usr/local/inetstat/bin/update.sh" >> /var/cron/tabs/root

echo "Alias /inetstat /usr/local/inetstat/www/
<Directory \"/usr/local/inetstat/www/\">
	Options none
	AllowOverride None
	Order Deny,Allow
	Allow from all
</Directory>" >> /usr/local/etc/apache22/httpd.conf

echo "<?php
\$ip_out_servera = \"$ip_WAN\";
\$ip_internal_servera = \"$ip_LAN\";
\$IF_out_servera = \"$iface_WAN\";
\$IF_internal_servera = \"$iface_LAN\";
\$lan_mask = \"$LAN_mask\";
\$db_host = \"$db_host\";
\$db_user = \"$db_user\";
\$db_passwd = \"$db_passwd\";
\$db_db = \"$db_db\";
?>" >> www/config.php

echo "trafd_enable=\"YES\"
trafd_ifaces=\"$iface_WAN $iface_LAN\"
trafd_flags=\"\"
trafd_log=\"/var/log/traffic.log\"" >> /etc/rc.conf

mysql="/usr/local/bin/mysql"
sql_preffix="${mysql} --host=${db_host} \
--user=${db_user} --password=${db_passwd} --database=${db_db}"

${sql_preffix} --execute="CREATE TABLE \`users\` \
(\`id\` INT( 5 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
\`ip\` VARCHAR( 15 ) NOT NULL ,
\`names\` VARCHAR( 15 ) NOT NULL ,
\`group\` VARCHAR( 6 ) NOT NULL
) ENGINE = MYISAM" 2>/dev/null

