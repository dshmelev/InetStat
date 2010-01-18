<?php
include "config.php";

if(!mysql_connect($db_host,$db_user,$db_passwd))
{
echo "<br><br><BIG><CENTER>
Не могу прислюнявится к MySQL!</CENTER></BIG>";
exit;
}
mysql_select_db($db_db);

$ip = $_POST['ip'];

if ($group = mysql_query("SELECT `group` FROM `users` WHERE `ip` = '".$ip. "'")) {
	if (mysql_num_rows($group)==0)
		$group='';
	else
		$group = mysql_result($group,0);
}


if ($name = mysql_query("SELECT `names` FROM `users` WHERE `ip` = '".$ip. "'")) {
	if (mysql_num_rows($name)==0) {
		$name='';
		$func = "new";
}
	else {
		$name = mysql_result($name,0);
		$func = "edit";
}
}
?>
<body>
<div class="window"><div>Редактирование пользователя</div><input type="button" value="X" onclick="UserClose()"></div>
<form method="post" action="post.php">
Username:
<input type="text" id="name" value="<?php echo $name?>"/>
<br>
Group:
<select id="group">
<option <?php if ($group=="fullgp") {echo "selected";}?> value="fullgp">Full</option>
<option <?php if ($group=="filtgp") {echo "selected";}?> value="filtgp">Filtered</option>
<option <?php if ($group=="none") {echo "selected";}?> value="none">None</option>
</select>
<input type="hidden" id="ip" value="<?php echo $ip;?>">
<input type="hidden" id="func" value="<?php echo $func; ?>">
<input type="button" value="Save" onclick="UserSaveClose(this.form)">
</form>
</body>
