<TABLE border="0" cellspacing="0" cellpadding="0" width="80%" id="main_table">
<TBODY>
    <TR>
        <TD height="20" width="33%" align="center">
        <b>IP</b></TD>
        <TD height="20" width="33%" align="center">
        <b>Users</b></TD>
        <TD height="20" width="33%" align="center">
        <b>Traffic</b></TD>
    </TR><TR><TD colspan="3">   <div class="accordion">
<?php


$sql = mysql_query("SELECT to_IP, SUM(bytes) AS `bytes` FROM 
`" . $IF_internal_servera . "_" . date(Y,time()) . "` WHERE `date` 
>= '" . $date[0] . "' AND `date` 
<= '" . $date[1] . "' AND from_IP != '" . $ip_out_servera . "'  
AND to_IP != '" . $ip_out_servera . "' AND 
`to_IP` != '" . $ip_internal_servera . "' 
AND `from_IP` != '" . $ip_internal_servera . "' AND `to_IP` 
LIKE '" . $lan_mask . "%' GROUP BY `to_IP` ORDER BY `bytes` DESC");

while ($d = mysql_fetch_assoc($sql)) {
    $bytes = $d['bytes'] /1048576;
    $bytes = round($bytes, 2);
    ?>
 <h3><table width="100%" cellspacing="0px" cellpadding="6px">
	<tr>
		<td width="33%" id="ip"><?php echo $d['to_IP']; ?></td>
		<td width="33%" align="center" id="name">
			<?php 
			$sql_users = mysql_query("SELECT names FROM `users` WHERE `ip` = '".$d['to_IP']. "'");
			if (mysql_num_rows( $sql_users)>0) {
			echo mysql_result($sql_users,0);
			}
			else {
			echo "[new]";}
			?>
		</td>
        	<td  width="33%" align="center"><?php echo $bytes; ?></td>
    </tr></table></h3><p class="accordion" id="ports" align="center">
Loading...
</p>
<?php
}
?>
</div>
</TD></TR></TBODY>
</TABLE>
