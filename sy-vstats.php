<?php
include "sy-config.php";
session_start();
if(empty($vstat_id)) { 
	$vstat_id = "vid";
}

include $setup['inc_folder']."/functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');

function spambot_prevention($reff){
	//List of known spambots. The dots allow for any TLD to trigger with fewer false-positives.
	$known_spambots = array('semalt.', 'ilovevitaly.', 'darodar.', 'econom.', 'makemoneyonline.', 'buttons-for-website.', 'myftpupload.', 'co.lumb', 'iskalko.', 'o-o-8-o-o.', 'o-o-6-o-o.', 'cenoval.', 'priceg.', 'cenokos.', 'seoexperimenty.', 'gobongo.', 'vodkoved.', 'adcash.', 'websocial.', 'cityadspix.', 'luxup.', 'ykecwqlixx.', 'superiends.', 'slftsdybbg.', 'edakgfvwql.', 'socialseet.', 'screentoolkit.', 'savetubevideo.','responsive-test.','videos-for-your-business.');

	if ( isset($reff) && contains(strtolower($reff), $known_spambots) ) {
		return true;
	}
}

function contains($str, array $arr) {
	foreach( $arr as $a ) {
		if ( stripos($str, $a) !== false ) {
			return true;
		}
	}
	return false;
	}
$time=time()+3600*24*365;
$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
$cookie_url = ".$domain";
foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
			$_REQUEST[$id] = addslashes(stripslashes(stripslashes(strip_tags(trim($value)))));
		}
	}
}

SetCookie("vtest",date('Ymd'),$time,"/",null);

function remover($string, $sep1, $sep2) {
   $string = substr($string, 0, strpos($string,$sep2));
   $string = substr(strstr($string, $sep1), 5);
   return $string;
}

$cururl = substr(strstr($_SERVER['REQUEST_URI'], "cururl="), 7);
$string = $_SERVER['REQUEST_URI'];
$str1 = "reff=";
$str2 = "&js=";
$reff =  remover($string, $str1, $str2);
if(spambot_prevention($reff) == true) { 
	die();
}



if(!isset($_SESSION[$vstat_id])) {
	if((isset($_COOKIE[$vstat_id])) AND ($_COOKIE['lv'] == date('Ymd'))==true) {
		$message .= "\r\n ############ RESET VISITOR SESSION FROM COOKIE ################";
		$_SESSION[$vstat_id] = $_COOKIE[$vstat_id];
		$_SESSION['vdate'] = date('Ymd');
	}else {
		$message .= "\r\n ############ COULD NOT  SESSION FROM COOKIE ################";

	}


	}

$message .= "\r\n REQUEST_URI: ".$_SERVER['REQUEST_URI'];


if($_REQUEST['js'] == "no") {
	$cururl = $_SERVER['HTTP_REFERER'];
	$_REQUEST['res'] = "unknown";
	$_REQUEST['colord'] = "unknown";
	$js = "no";
	$_REQUEST['ptitle'] = $_SERVER['HTTP_REFERER'];
}
if(isset($_COOKIE['vtest'])) {
	$cookies_on = true;
}
if(($site_setup['stats_ignore_admin'] == "1")AND(isset($_SESSION['office_admin_login']))==true){ 
	$ignore_stats = true;
}


if($ignore_stats !== true) { 
	$st_date = date('Y-m-d');
	$st_time = date('H:i:s');

	if((!isset($_SESSION[$vstat_id]))OR($_SESSION['vdate']!==date('Ymd'))==true) {
		
		$st_date = date('Y-m-d');
		$st_time = date('H:i:s');
		$remote_host = @getHostByAddr(getUserIP());
		$engines = whileSQL("ms_stats_engines", "*", "");
		while($engine = mysqli_fetch_array($engines)) {
			if(!empty($engine['engine_bot_check'])) {
				if(empty($bot)) {
					if(strstr($remote_host, $engine['engine_bot_check']))  {
						$bot = $engine['engine_name'];
						$pv = insertSQL("ms_stats_site_pv", "pv_date='$st_date', pv_time='$st_time', pv_page='$cururl', pv_page_title='".stripslashes($_REQUEST['ptitle'])."', pv_ref_id='', pv_bot='$bot' , page_viewed='".addslashes(stripslashes($_REQUEST['page_viewed']))."', date_id='".$_REQUEST['date_id']."' ");
						$ignore_stats = true;
					}
				}
			}
		}

		if($ignore_stats !== true) { 

			if(!isset($_COOKIE['lv'])) {
				SetCookie("lv",date('Ymd'),$time,"/",null);
				$message .= "no cookie set.";
			} elseif($_COOKIE['lv'] < date('Ymd')) {
				$message .= "Setting cookie.";
				$st_last_visit = $_COOKIE['lv'];
			} else {
				$message .= "Cookie Date: ".$_COOKIE['lv']."";
				$st_last_visit = $_COOKIE['lv'];
			}
			if(!isset($_SESSION['log_member_id'])) {
				$_SESSION['vmem'] = "no";
			}
			foreach($_REQUEST AS $id => $value) {
				$_REQUEST[$id] = addslashes(stripslashes($value));
			}
			if(!empty($_REQUEST['page_id'])) { 
				if(!is_numeric($_REQUEST['page_id'])) {
					$_REQUEST['page_id'] == "";
				}
			}
			if(!empty($_REQUEST['date_id'])) { 
				if(!is_numeric($_REQUEST['date_id'])) {
					$_REQUEST['date_id'] == "";
				}
			}
			$str = substr($remote_host, -4, 4);
			$ct = strstr($str, ".");
			$st_country = getCountry($ct, $remote_host);
			$st_browser = getBrowser($_SERVER['HTTP_USER_AGENT']);
			$st_browser_version = getBrowserVersion($_SERVER['HTTP_USER_AGENT']);
			include $setup['path']."/sy-inc/mobile.detect.php";
			$detect = new Mobile_Detect;

			if($detect->isTablet()){
				$st_ipad = 1;
			}

			if ($detect->isMobile() && !$detect->isTablet()) {
				$st_mobile = 1;
			}
			if(!empty($_REQUEST['pid'])) { 
				$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_REQUEST['pid']."' ");
			}
			if($setup['affiliate_program'] == true) { 
				$add_stats = ", st_aff='".addslashes(stripslashes($_REQUEST['afc']))."' ";
			}

			$visitor = insertSQL("ms_stats_site_visitors", "st_date='$st_date', st_time='$st_time', st_ip='".getUserIP()."', st_refer='$reff', st_page='$cururl', st_agent='".$_SERVER['HTTP_USER_AGENT']."', st_session='".$_REQUEST['PHPSESSID']."', st_remote_host='$remote_host', st_screen='".$_REQUEST['res']."', st_colord='".$_REQUEST['colord']."', st_page_title='".$_REQUEST['ptitle']."', st_member='".$person['p_id']."', st_js='$js', st_last_visit='$st_last_visit', st_bot='$bot' , st_country='". addslashes(stripslashes($st_country))."', st_browser='". addslashes(stripslashes($st_browser))."', st_browser_version='". addslashes(stripslashes($st_browser_version))."', st_mobile='$st_mobile', st_ipad='$st_ipad' $add_stats");
			$pv = insertSQL("ms_stats_site_pv", "pv_date='$st_date', pv_time='$st_time',  pv_page='$cururl', pv_page_title='".$_REQUEST['ptitle']."', pv_channel='".$_REQUEST['channel']."', pv_sub_channel='".$_REQUEST['sub_channel']."', pv_ref_id='$visitor', pv_bot='$bot' , page_viewed='".addslashes(stripslashes($_REQUEST['page_viewed']))."', date_id='".$_REQUEST['date_id']."'  ");
			SetCookie("vid",$visitor,$time,"/",null);
			SetCookie("lv",date('Ymd'),$time,"/",null);
			$_SESSION[$vstat_id] = $visitor;
			$_SESSION['vdate'] = date('Ymd');
		}
	} else {
		$pv = insertSQL("ms_stats_site_pv", "pv_date='$st_date', pv_time='$st_time', pv_channel='".$_REQUEST['channel']."', pv_sub_channel='".$_REQUEST['sub_channel']."',  pv_page='$cururl', pv_page_title='".stripslashes($_REQUEST['ptitle'])."', pv_ref_id='".$_SESSION[$vstat_id]."', pv_bot='$bot' , page_viewed='".addslashes(stripslashes($_REQUEST['page_viewed']))."', date_id='".$_REQUEST['date_id']."' ");
		if(!empty($_REQUEST['pid'])) { 
			$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_REQUEST['pid']."' ");
			$vis = doSQL("ms_stats_site_visitors", "*", "WHERE st_id='".$_SESSION[$vstat_id]."' ");
			if($vis['st_member'] <=0) { 
				updateSQL("ms_stats_site_visitors", "st_member='".$person['p_id']."' WHERE st_id='".$vis['st_id']."' ");
			}
		}

		
	}
}



exit();

if((strstr($st_remote_host, "google")) OR (strstr($st_remote_host, "teoma")) OR (strstr($st_remote_host, "alexa")) OR (strstr($st_remote_host, "msn")) OR (strstr($st_remote_host, "inktomisearch"))== true) {
$time = date("h:i A");
$date = date("m/d/y");
$bot_path = "$DOCUMENT_ROOT";
// $fp = fopen("$bot_path/bot.txt", "a+");
// $info =  "$date||$time||$st_ip||$st_remote_host||$st_page||$st_agent||";
// fputs($fp, "$info\n");
// fclose($fp);
exit();
}
?>
