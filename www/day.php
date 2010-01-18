<?php
include "config.php";

if(!mysql_connect($db_host,$db_user,$db_passwd))
{
echo "<br><br><BIG><CENTER>
    MySQL-!</CENTER></BIG>";
exit;
}
//  
mysql_select_db($db_db);
//   ,    -   
$curr_month = date(Y,time()) . "-" . date(m,time());
$old_month = date(Y,time()) . "-" . date('m',strtotime("-1 month"));
//  HTML : 
?>
<HEAD>
<title> </title>

<meta http-equiv="Content-Type" 
content="text/html; charset=UTF-8">
<STYLE type="text/css">
#main_table .hilightoff {BACKGROUND: white}
#main_table .hilighton {BACKGROUND: #ccbbff}
#cheresstrochnaya_table .hilightoff {BACKGROUND: #88ff88}
</STYLE>
<BODY>
<BIG><CENTER> </BIG><br>
<TABLE border="0" cellspacing="0" cellpadding="0" width="100%">
<TBODY>
    <TR>
        <TD width="50%" align="center" valign="top">
<TABLE border="1" cellspacing="0" cellpadding="0" 
width="80%" id="main_table">
<TBODY>
    <TR>
        <TD height="20" align="center" colspan="3">
        <BIG><CENTER>    
        <?php echo $_POST[date];?></CENTER></BIG></TD>
    </TR>
<TR>
        <TD height="20" width="15%" align="center">
        <b>IP</b></TD>
        <TD height="20" width="55%" align="center">
        <b>Фимилии</b></TD>
        <TD height="20" width="30%" align="center">
        <b>Трафик</b></TD>
    </TR>
<?php
//      
$sql = mysql_query("SELECT to_IP, SUM(bytes) AS `bytes` FROM 
`" . $IF_internal_servera . "_" . date(Y,time()) . "` WHERE `date`='".$_POST[date]."' AND from_IP != '" . $ip_out_servera . "'  
AND to_IP != '" . $ip_out_servera . "' AND 
`to_IP` != '" . $ip_internal_servera . "' 
AND `from_IP` != '" . $ip_internal_servera . "' AND `to_IP` 
LIKE '" . $lan_mask . "%' GROUP BY `to_IP` ORDER BY `bytes` DESC");
//      
//     
while ($d = mysql_fetch_assoc($sql)) {
    //    .
    $bytes = $d['bytes'] /1048576;
    $bytes = round($bytes, 2);
    if($d['to_IP'] == '192.168.1.1'){$user_name = 'Terminal';}
    if($d['to_IP'] == '192.168.1.11'){$user_name = 'Zaharova';}
    if($d['to_IP'] == '192.168.1.12'){$user_name = 'Rechetova';}
    if($d['to_IP'] == '192.168.1.14'){$user_name = 'Suhanova';}
    if($d['to_IP'] == '192.168.1.15'){$user_name = 'Kuzmina';}
    if($d['to_IP'] == '192.168.1.16'){$user_name = 'Alexeeva';}
    if($d['to_IP'] == '192.168.1.17'){$user_name = 'Kolesnikava';}
    if($d['to_IP'] == '192.168.1.19'){$user_name = 'Sincevich';}
    if($d['to_IP'] == '192.168.1.20'){$user_name = 'Samoilov';}
    if($d['to_IP'] == '192.168.1.21'){$user_name = 'Zhuravleva';}
    if($d['to_IP'] == '192.168.1.22'){$user_name = 'Leonova';}
    if($d['to_IP'] == '192.168.1.23'){$user_name = 'Shakaryan';}
    if($d['to_IP'] == '192.168.1.24'){$user_name = 'Evteshina';}
    if($d['to_IP'] == '192.168.1.26'){$user_name = 'Mikhailova';}
    if($d['to_IP'] == '192.168.1.27'){$user_name = 'Evdokimova';}
    if($d['to_IP'] == '192.168.1.28'){$user_name = 'Gadjieva';}
    if($d['to_IP'] == '192.168.1.29'){$user_name = 'Bogdasarian';}
    if($d['to_IP'] == '192.168.1.30'){$user_name = 'Kurginyan';}
    if($d['to_IP'] == '192.168.1.31'){$user_name = 'Shirinyan';}
    if($d['to_IP'] == '192.168.1.32'){$user_name = 'Velichkovsky';}
    if($d['to_IP'] == '192.168.1.33'){$user_name = 'Kucherenko';}
    if($d['to_IP'] == '192.168.1.34'){$user_name = 'Tonoyan';}
    if($d['to_IP'] == '192.168.1.35'){$user_name = 'GenDirect';}
    if($d['to_IP'] == '192.168.1.38'){$user_name = 'Ribnikova';}
    if($d['to_IP'] == '192.168.1.47'){$user_name = 'Sidorova';}
    ?>
    <tr class=hilightoff onmouseover="className='hilighton';" 
    onmouseout="className='hilightoff';">
        <td style="border-bottom: 1px solid #707680;" 
        width="" align="left"><?php echo $d['to_IP']; ?></td>
        <td style="border-bottom: 1px solid #707680;" 
        width="" align="left"><?php echo $user_name; ?></td>
        <td style="border-bottom: 1px solid #707680;" 
        width="" align="center"><?php echo $bytes; ?></td>
    </tr>
<?php
unset ($user_name);
}
?>
</TBODY>
</TABLE>
</TD>
</FORM>

<?php
$traffic_old_month = mysql_fetch_array(mysql_query("SELECT SUM(bytes) AS `bytes` 
FROM `" . $IF_out_servera . "_" . date(Y,time()) . "` WHERE 
`to_IP` ='" . $ip_out_servera . "' AND `date`='".date("Y-m-d")."'"));
$traffic_old_meg = $traffic_old_month[bytes] / 1048576;
$traffic_old_meg = round($traffic_old_meg, 2);
?>
  <?php echo "Всего скачено ".$traffic_old_meg; ?> <br>
</BODY>
</HTML>

<?php ?>
