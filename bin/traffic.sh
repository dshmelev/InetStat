
#!/bin/sh
#
#
IP_MySQL_servera="localhost"
username="trafd"
user_passw="trafd"
db_name="trafd"


day="`date +%Y-%m-%d`"
year="`date +%Y`"
month="`date +%m`"
curr_time="`date +%H:%M:00`"
NewDir="/usr/local/inetstat/var/traffic/${year}/${month}"
mkdir -p ${NewDir}
cd ${NewDir}

mysql="/usr/local/bin/mysql"
sql_preffix="${mysql} --host=${IP_MySQL_servera} \
--user=${username} --password=${user_passw} --database=${db_name}"

. /etc/rc.conf

for iface in ${trafd_ifaces}
do
/usr/local/bin/trafsave ${iface}
/usr/local/bin/traflog -i ${iface} -a -n -s > /tmp/summary.${iface} 2>/dev/null
cat /dev/null > /usr/local/var/trafd/trafd.${iface}
cat /tmp/summary.${iface} >> ${NewDir}/summary.${iface}
${sql_preffix} --execute="CREATE TABLE \`traffic_tmp\` \
(\`date\` DATE NOT NULL, \`time\` TIME NOT NULL, \
\`from_IP\` CHAR(16) NOT NULL, \`port_from_IP\` CHAR(8) NOT NULL, \
\`to_IP\` CHAR(16) NOT NULL, \`port_to_IP\` CHAR(8) NOT NULL, \
\`protocol\` ENUM('icmp','tcp','udp') NOT NULL, \`bytes\` int(16) NOT NULL, \
\`all_bytes\` int(16) NOT NULL) TYPE=MyISAM COMMENT='tmp_table'" 2>/dev/null

${sql_preffix} --execute="DELETE FROM \`traffic_tmp\`"
grep -v "^ " /tmp/summary.${iface} |
{
while read stroka
do
from_IP=`echo "${stroka}" | awk '{print $1}'`
port_from_IP=`echo "${stroka}" | awk '{print $2}'`
to_IP=`echo "${stroka}" | awk '{print $3}'`
port_to_IP=`echo "${stroka}" | awk '{print $4}'`
protocol=`echo "${stroka}" | awk '{print $5}'`
bytes=`echo "${stroka}" | awk '{print $6}'`
all_bytes=`echo "${stroka}" | awk '{print $7}'`
${sql_preffix} --execute="INSERT INTO \`traffic_tmp\` (\`date\`, \
\`time\`, \`from_IP\`, \`port_from_IP\`, \`to_IP\`, \`port_to_IP\`, \
\`protocol\`, \`bytes\`, \`all_bytes\`) \
values ('${day}', '${curr_time}', '${from_IP}', \
'${port_from_IP}', '${to_IP}', '${port_to_IP}', \
'${protocol}', '${bytes}', '${all_bytes}')"
done
}
${sql_preffix} --execute="DELETE FROM \`traffic_tmp\` WHERE from_IP='' AND \
port_from_IP='' AND to_IP='' AND port_to_IP='' AND protocol=''"
${sql_preffix} --execute="DELETE FROM \`traffic_tmp\` WHERE all_bytes='0'"
${sql_preffix} --execute="CREATE TABLE \`${iface}_${year}\` \
(\`unic_id\` INT(16) NOT NULL AUTO_INCREMENT, \
\`date\` DATE NOT NULL, \`time\` TIME NOT NULL, \
\`from_IP\` CHAR(16) NOT NULL, \`port_from_IP\` CHAR(8) NOT NULL, \
\`to_IP\` CHAR(16) NOT NULL, \`port_to_IP\` CHAR(8) NOT NULL, \
\`protocol\` ENUM('icmp','tcp','udp') NOT NULL, \`bytes\` int(16) NOT NULL, \
\`all_bytes\` int(16) NOT NULL, \
PRIMARY KEY (\`unic_id\`), \
KEY \`date\`(\`date\`) \
) TYPE=MyISAM COMMENT='База \
данных траффика по (${iface}) интерфейсу за ${year} год'" 2>/dev/null
${sql_preffix} --execute="INSERT INTO \`${iface}_${year}\`\
(\`date\`, \`time\`, \`from_IP\`, \`port_from_IP\`, \`to_IP\`,\
\`port_to_IP\`, \`protocol\`, \`bytes\`, \`all_bytes\`) \
SELECT \`date\`, \`time\`, \`from_IP\`, \`port_from_IP\`,\
\`to_IP\`, \`port_to_IP\`, \`protocol\`, sum(\`bytes\`) as \`bytes\`,\
sum(\`all_bytes\`) as \`all_bytes\` FROM \
\`traffic_tmp\` GROUP BY \`date\`, \`time\`, \`from_IP\`, \`port_from_IP\`,\
 \`to_IP\`, \`port_to_IP\`, \`protocol\`"
${sql_preffix} --execute="DELETE FROM \`${iface}_${year}\` WHERE \`from_IP\` = '192.168.1.200' OR \`to_IP\` = '192.168.1.200'"
done

cat /dev/null > /var/log/traffic.log
