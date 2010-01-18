<?php
include "config.php";

if(!mysql_connect($db_host,$db_user,$db_passwd))
{
echo "<br><br><BIG><CENTER>
Не могу прислюнявится к MySQL!</CENTER></BIG>";
exit;
}
mysql_select_db($db_db);

$date[0]=$_POST['date0'];
$date[1]=$_POST['date1'];
$ip=$_POST['ip'];

$port_sql = mysql_query("SELECT port_from_IP, SUM(bytes) AS `bytes` FROM 
`" . $IF_internal_servera . "_" . date(Y,time()) . "` WHERE 
`date` >= '" . $date[0] . "' AND `date` <= '" . $date[1] . "' 
AND from_IP != '" . $ip_out_servera . "'  AND  `to_IP` = '".$ip."' 
AND `to_IP` GROUP BY `port_from_IP` ORDER BY `bytes` DESC");
?>
<table width="100%">
<tr>
		<td width="50%">Ports:</td>
		<td width="50%" align="right">MB:</td>
</tr>
<?php
while ($t = mysql_fetch_assoc($port_sql)) {
$bytes_2 = $t['bytes'] /1048576;
$bytes_2 = round($bytes_2, 3);
?>
<tr>
		<td width="50%"><?php echo $t['port_from_IP']; ?></td>
		<td width="50%" align="right"><?php echo $bytes_2; ?></td>
</tr>
<?php
}
?>
</table>
