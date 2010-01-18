<?php 
// format the uptime in case the browser doesn't support dhtml/javascript
// static uptime string
function format_uptime($seconds) {
  $msecs = intval($seconds % 10);
  $secs = intval($seconds/10 % 60);
  $mins = intval($seconds/600 % 60);
  $hours = intval($seconds/36000 % 24);
  $days = intval($seconds/864000);
  $uptimeString=$hours.":".$mins.":".$secs.":".$msecs;
  echo $days." days";
  return $uptimeString;
}

// read in the uptime (using exec)
$uptime = exec("sysctl -a | grep ngetmicrotime");
$uptime = split(" ",$uptime);
$uptimeSecs = $uptime[1]/10;

// get the static uptime
$staticUptime = format_uptime($uptimeSecs);
?>
<html>
<head>
<script language="javascript">
<!--
var upSeconds=<?php echo $uptimeSecs; ?>;
function doUptime() {
var msecs = parseInt(upSeconds % 10)
var secs = parseInt(upSeconds/10 % 60);
var mins = parseInt(upSeconds/10 / 60 % 60);
var hours = parseInt(upSeconds/10 / 3600 % 24);
var days = parseInt(upSeconds/10 / 86400);
var uptimeString = hours + ":" + mins + ":" + secs + ":" + msecs;
var span_el = document.getElementById("uptime");
var replaceWith = document.createTextNode(uptimeString);
span_el.replaceChild(replaceWith, span_el.childNodes[0]);
upSeconds++;
setTimeout("doUptime()",100);
}
// -->
</script>
</head>
<body onLoad="doUptime();">

<!-- Uses the DIV tag, but SPAN can be used as well -->
<div id="uptime" style="font-weight:normal; font-size: 9px;"><?php echo $staticUptime; ?></div>

</body>
</html>
