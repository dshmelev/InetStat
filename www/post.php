<?php
include "config.php";

$ip = $_POST['ip'];

$func = $_POST['func'];
if ($func=="new") {
	$name = $_POST['name'];
	$group = $_POST['group'];
	mysql_query ("INSERT INTO `trafd`.`users` (`id`, `ip`, `names`, `group`) VALUES  (NULL, '" . $ip . "', '" . $name . "', '". $group ."');");
}
if ($func=="edit") {	
	$name =$_POST['name'];
	$group=$_POST['group'];
	mysql_query("UPDATE `trafd`.`users` SET `group` = '" . $group . "' , `names` = '" . $name . "' WHERE `ip` = '" . $ip . "'");
}
?>
<script language='javascript'>  
setTimeout("document.location=\"/index.php\"",3000);
</script> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Internet Statistic</title>
Подождите 3 сек... Или перейдите по <a href="/index.php">ссылке</a> вручную
</head>
</html>
