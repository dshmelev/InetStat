<?php
$news_file = "http://github.com/api/v2/xml/commits/list/avik/InetStat/master/";
$news_count=0;
$news_count_current=1;
function print_news($news_name,$news_mail,$news_url,$news_id,$news_date,$news_message)
{
echo "<b>".$news_date."</b><a href=mailto:".$news_mail.">".$news_mail."</a><br>";
echo "<b>Message:</b><a href=".$news_url.">".$news_message."</a><p>";
}

function characterData($news_parser, $news_data) 
{
	global $news_count_current;
	global $news_count;
	global $news_name;
	global $news_mail;
	global $news_url;
	global $news_id;
	global $news_date;
	global $news_message;
	if ($news_count_current==0) {
		switch ($news_count) 
		{
			case 17:
				$news_url = $news_data;
				break;
			case 19:
				$news_id = $news_data;
				break;
			case 21:
				$news_date=substr($news_data, 0, 10);
				break;
			case 25:
				$news_message = $news_data;
				break;
			case 30:
				$news_name = $news_data;
				break;
			case 33:
				$news_mail = $news_data;
				break;
			case 34:
				$news_count_current=0;
				$news_count=0;
				print_news($news_name,$news_mail,$news_url,$news_id,$news_date,$news_message);
				break;
		}
	}
	else
	{
		switch ($news_count) 
		{
			case 16:
				$news_url = $news_data;
				break;
			case 19:
				$news_id = $news_data;
				break;
			case 22:
				$news_date=substr($news_data, 0, 10);
				break;
			case 24:
				$news_message = $news_data;
				break;
			case 30:
				$news_name = $news_data;
				break;
			case 33:
				$news_mail = $news_data;
				break;
			case 34:
				$news_count_current=0;
				$news_count=0;
				print_news($news_name,$news_mail,$news_url,$news_id,$news_date,$news_message);
				break;
		}
	}
		$news_count++;
}

$news_xml_parser = xml_parser_create();
xml_set_character_data_handler($news_xml_parser, "characterData");
if (!($news_fp = fopen($news_file, "r"))) {
    die("could not open XML input");
}
while ($news_data = fread($news_fp, 4096)) {
    if (!xml_parse($news_xml_parser, $news_data, feof($news_fp))) {
        die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($news_xml_parser)),
                    xml_get_current_line_number($news_xml_parser)));
    }
}
xml_parser_free($news_xml_parser);
?>