<?php
include "config.php";

function timeMeasure()
{
    list($msec, $sec) = explode(chr(32), microtime());
    return ($sec+$msec);
}
define('TIMESTART', timeMeasure());

if(!mysql_connect($db_host,$db_user,$db_passwd))
{
echo "<br><br><BIG><CENTER>
Не могу прислюнявится к MySQL!</CENTER></BIG>";
exit;
}
mysql_select_db($db_db);
if ($_POST['pdate']!=null ) {
	$pre_date = $_POST['pdate'];
	$date = spliti(" -> ", $pre_date, 2);
	if ($date[1]==null) {$date[1]=$date[0];}
	}
else {
	$date[0] = date(Y,time()) . "-" . date(m,time()) . "-01";
	$date[1] = date(Y,time()) . "-" . date(m,time()) . "-" . date(d,time()) ;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Internet Statistic</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="css/win2k/win2k.css" />
    <script type="text/javascript" src="js/jscal2.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/plreloader1.js"></script>
    <script type="text/javascript" src="js/lang/ru.js"></script>
    <script type="text/javascript">
	var UserName;

	$(document).ready(function(){
		$(".accordion p").hide();
		
		$("#ip",".accordion h3").click(function(){	
			var Ports = $(this).parents('h3').next("p");
			Ports.slideToggle("slow")
			.siblings("p:visible").slideUp("slow");
			$(this).parents('h3').toggleClass("active");
			$(this).parents('h3').siblings("h3").removeClass("active");
			jQuery.post("ports.php", {"ip": $("#ip").text(),"date0": <?php echo "\"".$date[0]."\"";?>,"date1": <?php echo "\"".$date[1]."\"";?>}, function(data) {	Ports.html(data); });		
		});
	
		$("#name", ".accordion h3").click(function(){
			window.scrollTo(0,0);
			UserName = $(this);
			$('body').css('overflow', 'hidden');
			$('#preloader').css('display','none');
			$('.centerbg1').css('background','black');
			$('#preloaderbg').css('opacity','0').css('display','block').fadeTo("fast",0.3);
			$("#UserEdit").fadeIn("fast");
			jQuery.post("user.php", {"ip": $(this).parents('h3').find('#ip').text()}, function(data) { $("#UserEdit").html(data); });
		});	
	});

	function UserSaveClose(form) {
		var Name = $("#name", form).val();
		var Group = $("#group", form).val();
		var Ip = $("#ip", form).val();
		var Func = $("#func", form).val();
		jQuery.post("post.php", {"name": Name,"group" : Group,"ip": Ip, "func": Func}, function() { });
		$("#UserEdit").fadeOut("slow");
		$('#preloaderbg').fadeOut("slow");
		$('body').css('overflow', 'visible');
		UserName.text(Name);
	};

	function UserClose(form) {
		$("#UserEdit").fadeOut("slow");
		$('#preloaderbg').fadeOut("slow");
		$('body').css('overflow', 'visible');
	};

</script>

</head>
<body>

<div id="UserEdit">
		Loading....
</div>
	
 <div id="preloaderbg" class="centerbg1">
	<div class="centerbg2">
		<div id="preloader"></div>
	</div>
 </div>
  
<script type="text/javascript">
document.getElementById('preloaderbg').style.display = 'block';
document.body.style.overflow = 'hidden';
pbPos = 0;
pbInt = setInterval(function(){
  document.getElementById('preloader').style.backgroundPosition = ++pbPos + 'px 0';
}, 25);
</script>


<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="border-bottom: 1px solid #707680;" width="26%" height="70"><img src="logo.png" width="250" height="70" /></td>
    <td style="border-bottom: 1px solid #707680; border-left: 1px solid #707680; border-right: 1px solid #707680;" width="52%" height="70" valign="top">
		<div id="shap">
			<div id="shap_text"><b>Развитие проекта:</b><div>	
			<div id="news">
				<div>   02.07.09: В статистику внедрена ассинхронизация.<br>
					28.06.09: Добавлена поддержка портов.<br>
					12.06.09: Полностью доработана система управления пользователями.<br>
					30.05.09: Поддержка календаря полностью внедрена. Что бы выбрать статистику в промежутке дат используйте Shift+LeftClick+[OK]
				</div>
			</div>
		</div>
	</td>
    <td style="border-bottom: 1px solid #707680;" width="250" height="70" align="center" valign="top"><strong>Время работы<br>
      сервера:</strong><br>
    <?php include "uptime.php";?></td>
  </tr>
  <tr>
    <td height="358" style="border-right: 1px solid #707680; border-bottom: 1px solid #707680;" align="center" valign="top">
	  <br>
	  <p>
    <a href="/sams/" target="_parent">Подробная статистика</a>	
	</td>

	
    <td style="border-bottom: 1px solid #707680;" align="center" valign="top"><div align="center">
      <?php include "table.php" ?>  
    </div></td>
    <td width="250" style="border-left: 1px solid #707680; border-bottom: 1px solid #707680;" align="center" valign="top">
	
	<!-- Calendar -->
		<table><tr><td>
		  <!-- element that will contain the calendar -->
 		<div id="calendar-container"></div>
		  <!-- here we will display selection information -->
 		<div id="calendar-info" style="text-align: center; margin-top: 0.3em"></div>
		</td></tr></table>
		<script type="text/javascript">
		Calendar.setup({
			inputField : "calendar-inputField",
			cont          : "calendar-container",
			weekNumbers   : true,
			selectionType : Calendar.SEL_MULTIPLE,
			min: 20090408,
			max: <?php echo date(Ymd) ?>,
			selection     : [
			[ <?php echo preg_replace('#\D+#','',$date[0])." , ".preg_replace('#\D+#','',$date[1]);?> ],
			]
			});
		</script>
		<!-- Calendar -->
		<form id="form1" name="form1" method="post" action="index.php">
            <input name="pdate" id="calendar-inputField" type="hidden"/>
            <input name="submit" type="submit" value="OK" />
		</form>	</td>
  </tr>
  <tr>
    <td><font size="1" face"arial">InetStat<br>
Copyright (C) 2009 LLC STRIZH</font></td>
    <td></td>
    <td width="250" valign="bottom" align="right"><?php echo '<font size="1" face"arial">Страница сгенерировалась за '.round(timeMeasure()-TIMESTART, 6).' сек.</font>';?></td>
  </tr>
</table>
</body>

</html>
