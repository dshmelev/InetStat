<?php
$file = "http://github.com/api/v2/xml/commits/list/avik/InetStat/master/";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
$count=0;

function print_news($name,$mail,$url,$id,$date,$message)
{
//$date[10]=" ";
echo strtok($date,"T")." ".$name." (<a href=mailto:".$mail.">".$mail."</a>)<br>";
echo "Message:".$message."<br>\n";
echo "<a href=".$url.">".$id."</a><p>";
}

function characterData($parser, $data) 
{
	global $count;
	global $name;
	global $mail;
	global $url;
	global $id;
	global $date;
	global $message;
	switch ($count) 
	{
		case 15:
			$url = $data;
			break;
		case 17:
			$id = $data;
			break;
		case 19:
			$date = $data;
			break;
		case 23:
			$message = $data;
			break;
		case 28:
			$name = $data;
			break;
		case 31:
			$mail = $data;
			break;
		case 34:
			$count=5;
			print_news($name,$mail,$url,$id,$date,$message);
			break;
	}
	$count++;
}

$xml_parser = xml_parser_create();
xml_set_character_data_handler($xml_parser, "characterData");
if (!($fp = fopen($file, "r"))) {
    die("could not open XML input");
}
while ($data = fread($fp, 4096)) {
    if (!xml_parse($xml_parser, $data, feof($fp))) {
        die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($xml_parser)),
                    xml_get_current_line_number($xml_parser)));
    }
}
xml_parser_free($xml_parser);
?>