<?php
function dbConnect($setup) {
	global $db_user_array;
	if(is_array($db_user_array)) {
		$db_user = $db_user_array[ rand( 0, ( sizeof($db_user_array) -1 ) ) ];  
	} else {
		$db_user = $setup['pc_db_user'];
	}
	if(empty($setup['db_port'])) { 
		$setup['db_port'] = null;
	}
	if(empty($setup['db_socket'])) { 
		$setup['db_socket'] = null;
	}
	$dbcon = @mysqli_connect($setup['pc_db_location'],$db_user,$setup['pc_db_pass'],$setup['pc_db'],$setup['db_port'],$setup['db_socket']);
	if (!$dbcon) {	echo( "Unable to connect to the database" .mysqli_connect_error($dbcon));	exit(); }
	//if (! @mysqli_select_db("".$setup['pc_db']."") ) {	echo( "Unable to locate the database: ".$setup['pc_db']."");	exit(); }
	mysqli_query($dbcon,"SET NAMES 'utf8'");
	mysqli_query($dbcon,"SET CHARACTER SET utf8");
	mysqli_query($dbcon,"SET COLLATION_CONNECTION = 'utf8_unicode_ci'");
	mysqli_query($dbcon,"SET SESSION sql_mode = '' ");
	// mysqli_query("SET time_zone = '".$site_setup['time_zone']."'");
	if(!empty($setup['lc_time_names'])) { 
		mysqli_query($dbcon,"SET lc_time_names = '".$setup['lc_time_names']."' ");
	}
	return $dbcon;
}

function sql_safe($value) { 
	global $dbcon;
	if(function_exists(htmlspecialchars_decode)) { 
		$value = htmlspecialchars_decode($value);
	}
	$value = rejectCode($value);
	$value = strip_tags($value);
	if (get_magic_quotes_gpc()) { 
			$value = stripslashes($value); 
	} 
	if(function_exists('mysqli_real_escape_string')) {
	   $value = mysqli_real_escape_string($dbcon,$value); 
	}
	$value = str_replace("UNION","",$value);
	$value = str_replace("CONCAT","",$value);
	$value = str_replace("union","",$value);
	$value = str_replace("concat","",$value);
	$value = str_replace("=","",$value);
	return $value;
} 

function rejectCode($value) { 
	$codes = array("script>","alert(","<script","<img", "prompt(", "prompt (");
	foreach($codes AS $code) { 
		$pos = strpos(strtolower(urldecode($value)), $code);
		if ($pos !== false) {
			$value = "";
		}
	}
	return $value;
}


if(!function_exists('ctype_alnum')) { 
	function ctype_alnum($string){
		if (eregi('[A-Za-z0-9]', $string)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

if (!function_exists("stripos")) {
  function stripos($str,$needle,$offset=0)
  {
      return strpos(strtolower($str),strtolower($needle),$offset);
  }
}

function basicSQL($table, $where) {
	global $setup,$dbcon;
	if($setup['log_mysqli_queries'] == true) { 
		logmysqlqueries("SELECT * FROM $table $where");
	}
	$qry = @mysqli_query($dbcon,"SELECT * FROM $table $where");
	if (!$qry) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: $table $where</div>");  }
	return $output = mysqli_fetch_array($qry);
}

function doSQL($table, $what, $where) {
	global $setup,$dbcon;
	if($setup['log_mysqli_queries'] == true) { 
		logmysqlqueries("SELECT $what FROM $table $where");
	}
	$qry = mysqli_query($dbcon,"SELECT $what FROM $table $where");
	if(!is_array($_SESSION['query'])) { 
		$_SESSION['query'] = array();
	}

	if (!$qry) {	 logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }
	return $output = mysqli_fetch_array($qry);
	mysqli_free_result($qry);
}

function whileSQL($table, $what, $where) {
		global $setup,$dbcon;
	// $starttime = microtime(true);
	if($setup['log_mysqli_queries'] == true) { 
		logmysqlqueries("SELECT $what FROM $table $where");
	}

	if(!is_array($_SESSION['query'])) { 
		$_SESSION['query'] = array();
	}
	//array_push($_SESSION['query'], "$table -> $what -> $where");
	$qry = mysqli_query($dbcon,"SELECT $what FROM $table $where");
	if (!$qry) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }
	/*
	$endtime = microtime(true);
	$duration = $endtime - $starttime;
	if($duration > .01) { 
		print "<span style=\"background: #ffff00;\" title=\"".$table." ".$what." ".$where."\">time: ".$duration."</span>";
	}
	*/
	return $qry;

}

function countIt($table, $where) {
	global $setup,$dbcon;
	if($setup['log_mysqli_queries'] == true) { 
		logmysqlqueries("SELECT COUNT(*) AS total FROM $table $where");
	}
	if(!is_array($_SESSION['query'])) { 
		$_SESSION['query'] = array();
	}
	//array_push($_SESSION['query'], "$table -> $what -> $where");

	$qry = @mysqli_query($dbcon,"SELECT COUNT(*) AS total FROM $table $where");
	if (!$qry) {	logmysqlerrors(mysqli_error($dbcon)); die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT COUNT(*) AS total FROM $table $where</div>");  }
	$output = mysqli_fetch_array($qry);	
	return $output['total'];
}

function countIt2($table, $where) {
	global $setup,$dbcon;
	if($setup['log_mysqli_queries'] == true) { 
		logmysqlqueries("SELECT COUNT(*) AS total FROM $table $where");
	}

	$qry = @mysqli_query($dbcon,"SELECT COUNT(*) AS total FROM $table $where");
	if (!$qry) {	 logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT COUNT(*) AS total FROM $table $where</div>");  }
	$output = mysqli_fetch_array($qry);	
	return $output['total'];
}

function insertSQL($table, $what) {
	global $setup,$dbcon;
	if($setup['log_mysqli_queries'] == true) { 
		logmysqlqueries("INSERT INTO $table SET $what");
	}
	$qry = mysqli_query($dbcon,"INSERT INTO $table SET $what");
	if (!$qry) {	logmysqlerrors(mysqli_error($dbcon)); die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: $sql</div>"); 	}
	$id = mysqli_insert_id($dbcon);
	return $id;
}

function updateSQL($table, $what) {
	global $setup,$dbcon;
	if($setup['log_mysqli_queries'] == true) { 
		logmysqlqueries("UPDATE $table SET $what");
	}


	$qry = mysqli_query($dbcon,"UPDATE $table SET $what");
	if (!$qry) {	logmysqlerrors(mysqli_error($dbcon)); die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: UPDATE $table SET $what</div>"); 	}
	$id = mysqli_insert_id($dbcon);
	return $id;
}

function deleteSQL($table, $where, $limit) {
	global $setup,$dbcon;
	if($setup['log_mysqli_queries'] == true) { 
		logmysqlqueries("DELETE FROM $table  $where LIMIT $limit");
	}
	$sql = "DELETE FROM $table  $where LIMIT $limit ";
	if(@mysqli_query($dbcon,$sql)) { } else {	 logmysqlerrors(mysqli_error($dbcon)); die("<div class=\"error\">MYSQL ERROR > " . mysqli_error($dbcon) . " <br><br>Query: $sql</div>");  }
	return $sql;
}
function deleteSQL2($table, $where) {
	global $setup,$dbcon;
	if($setup['log_mysqli_queries'] == true) { 
		logmysqlqueries("DELETE FROM $table  $where");
	}

	$sql = "DELETE FROM $table  $where";
	if(@mysqli_query($dbcon,$sql)) { } else { logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR > " . mysqli_error($dbcon) . " <br><br>Query: $sql</div>");  }
	return $sql;
}

function logmysqlqueries($qry) { 
	global $setup,$dbcon;
	$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	if(!is_dir($setup['path']."/sy-logs")) { 
		// print "No direcory";
		$parent_permissions = substr(sprintf('%o', @fileperms("".$setup['path']."/sy-photos")), -4); 
		if($parent_permissions == "0755") {
			$perms = 0755;
		} elseif($parent_permissions == "0777") {
			$perms = 0777;
		} else {
			$perms = 0755;
		}
		mkdir("".$setup['path']."/sy-logs", $perms);
		chmod("".$setup['path']."/sy-logs", $perms);
		$fp = fopen("".$setup['path']."/sy-logs/index.php", "w");
		fputs($fp, "Nope");
		fclose($fp);
	}

	$lfile = "mysql-log-".date('Y-m-d').".txt";

	if(!file_exists($setup['path']."/sy-logs/".$lfile)) { 
		$fp = fopen("".$setup['path']."/sy-logs/".$lfile, "w");
		fputs($fp,"");
		fclose($fp);
	}

	$info =  date('Y-m-d h:i:s')." ".getUserIP()." ".$_SERVER['HTTP_USER_AGENT']."  QRY: ".$qry.""; 
	// $info .=  " ".$_SERVER['HTTP_HOST'].urldecode($_SERVER['REQUEST_URI'])." | REFERER: ".$_SERVER['HTTP_REFERER'].""; 

	$info.= "\r\n";

	file_put_contents($setup['path']."/sy-logs/".$lfile, $info, FILE_APPEND);

}

function logmysqlerrors($qry) { 
	global $setup,$dbcon;
	$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	if(!is_dir($setup['path']."/sy-logs")) { 
		// print "No direcory";
		$parent_permissions = substr(sprintf('%o', @fileperms("".$setup['path']."/sy-photos")), -4); 
		if($parent_permissions == "0755") {
			$perms = 0755;
		} elseif($parent_permissions == "0777") {
			$perms = 0777;
		} else {
			$perms = 0755;
		}
		mkdir("".$setup['path']."/sy-logs", $perms);
		chmod("".$setup['path']."/sy-logs", $perms);
		$fp = fopen("".$setup['path']."/sy-logs/index.php", "w");
		fputs($fp, "Nope");
		fclose($fp);
	}

	$lfile = "mysql-errors-log-".date('Y-m-d').".txt";

	if(!file_exists($setup['path']."/sy-logs/".$lfile)) { 
		$fp = fopen("".$setup['path']."/sy-logs/".$lfile, "w");
		fputs($fp,"");
		fclose($fp);
	}

	$info =  date('Y-m-d h:i:s')." ".getUserIP()." ".$_SERVER['HTTP_USER_AGENT']."  QRY: ".$qry.""; 
	// $info .=  " ".$_SERVER['HTTP_HOST'].urldecode($_SERVER['REQUEST_URI'])." | REFERER: ".$_SERVER['HTTP_REFERER'].""; 

	$info.= "\r\n";

	file_put_contents($setup['path']."/sy-logs/".$lfile, $info, FILE_APPEND);

}
function logdata($what) { 
	global $setup,$dbcon;
	$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	if(!is_dir($setup['path']."/sy-logs")) { 
		// print "No direcory";
		$parent_permissions = substr(sprintf('%o', @fileperms("".$setup['path']."/sy-photos")), -4); 
		if($parent_permissions == "0755") {
			$perms = 0755;
		} elseif($parent_permissions == "0777") {
			$perms = 0777;
		} else {
			$perms = 0755;
		}
		mkdir("".$setup['path']."/sy-logs", $perms);
		chmod("".$setup['path']."/sy-logs", $perms);
		$fp = fopen("".$setup['path']."/sy-logs/index.php", "w");
		fputs($fp, "Nope");
		fclose($fp);
	}

	$lfile = "log-".date('Y-m-d').".txt";

	if(!file_exists($setup['path']."/sy-logs/".$lfile)) { 
		$fp = fopen("".$setup['path']."/sy-logs/".$lfile, "w");
		fputs($fp,"");
		fclose($fp);
	}

	//$info =  date('Y-m-d h:i:s')." ".getUserIP()." ".$_SERVER['HTTP_USER_AGENT']."  QRY: ".$qry.""; 
	// $info .=  " ".$_SERVER['HTTP_HOST'].urldecode($_SERVER['REQUEST_URI'])." | REFERER: ".$_SERVER['HTTP_REFERER'].""; 
	$info = $what;
	$info.= "\r\n";

	file_put_contents($setup['path']."/sy-logs/".$lfile, $info, FILE_APPEND);

}
function securityCheck() {
	global $site_setup;
	if(!empty($site_setup['pc_ip_block'])) {
//		print "<li>".$site_setup['pc_ip_block'];
		$ips = explode("\r\n", $site_setup['pc_ip_block']);
//		print_r($ips);
		if(in_array(getUserIP(), $ips)) {
			insertSQL("pc_blocks", "block_ip='".getUserIP()."', block_date=NOW(), block_refer='".$_SERVER['HTTP_REFERER']."', block_agent='".$_SERVER['HTTP_USER_AGENT']."' ");
			die("<br><br><center><font color=\"#890000\">".$site_setup['pc_block_message']."</font></center>");
		}
	}
	if(!empty($site_setup['pc_block_refer'])) {
		$domains = explode("\r\n", $site_setup['pc_block_refer']);

		$info = explode('//', $_SERVER['HTTP_REFERER']); 
		$d1 = $info[1]; 
		$info2 = explode('/', $d1); 
		$d2 = $info2[0]; 
		$domain = str_replace("www.", "", "$d2");
		// print "<li>$domain";
		if(in_array($domain, $domains)) {
			die("<br><br><center><font color=\"#890000\">".$site_setup['pc_block_message']."</font></center>");
		}
	}


}


function anString($string) {
	if(!preg_match('/^[a-z0-9]+$/i', $string)) { die("<span class=error>An error has occured. Press back on your browser to return to the previous page</span>"); }  
}
function nextprevM($total_results, $vp, $per_page,  $NPvars) {
	global $_REQUEST,$tr;
	$pages = ceil($total_results / $per_page);
	$tpl = 10;
	if($pages<$tpl) {
		$tpl = $pages;
	}
	print "<div id=\"pageMenu\">";
	print "<span class=\"totalResults\">$pages ".$tr['pages']."</span>";


	if(empty($vp)) {	$vp = "1";		}
	$vw1 = ($vp * $per_page) - $per_page + 1; 
	$vw2 = $vw1 + ($per_page - 1);
	if($vp * $per_page > $total_results) {
		$vw2 = (($vp - 1) * $per_page) + ($total_results - (($vp - 1) * $per_page));
	}
	foreach($NPvars AS $vari) {
		$qstring .= "&$vari";
	}
	if(!empty($_REQUEST['q'])) {
		$qstring .= "&q=".$_REQUEST['q']."";
	}
	if(!empty($_REQUEST['ingals'])) {
		$qstring .= "&ingals=".$_REQUEST['ingals']."";
	}

	if($vp <= ($tpl/2)) {
		$np = 1;
	} else {
		$np = $vp - (($tpl/2)-1);
		if($pages > $tpl) {
			print "<a class=\"selectPage\" href=\"index.php?vp=1" . "$qstring\">".$tr['first']."</a>";
		}
	}
	if($vp>=($pages - ($tpl/2))) {
		$np = $pages - $tpl + 1;
	}

	if($vp > 1) {
		$prev = $vp - 1;
		print "<a  class=\"selectPage\" href=\"index.php?vp=$prev" . "$qstring\">".$tr['previous']."</a>";
	} else {
		print "<div class=\"selectedPage\">&nbsp;".$tr['previous']."&nbsp;</div>";
	}

	$pct = 1;
	while($np  < $total_results / $per_page + 1 AND $pct <= $tpl) {
		if($np == $vp) {
			print  "<div class=\"selectedPage\">&nbsp;$np&nbsp;</div>" ;
		} else {
			print  "<a class=\"selectPage\"  href=\"index.php?vp=$np" . "$qstring\">$np</a>" ;
		}
		$np++;
		$pct++;
	}
	if($vp < $total_results / $per_page ) {
		$next = $vp + 1;
		print "<a class=\"selectPage\" href=\"index.php?vp=$next" . "$qstring\">".$tr['next']."</a>";
	} else {
		print "<div class=\"selectedPage\">&nbsp;".$tr['next']."&nbsp;</div>";
	}
	if($np<=$pages) {
		print "<a class=\"selectPage\"  href=\"index.php?vp=$pages" . "$qstring\">".$tr['last']."</a>";
	}
	print "</div>";
}


function clean_num($num){
  return trim(trim(trim($num), "0"), ".");
}
function cleanJsQuotes($val) {
	$val = str_replace('"', "&#34;", $val);
	$val = str_replace("'", "\'", $val);
	return $val;
}
if(!function_exists(utf8_encode)) {

	function utf8_encode($val) {
		return $val;
	}
}

function genRandomString() {
    $length = rand(5,7);
    $characters = "23456789abcdefghijmnpqrstvwxyz";
    for ($p = 0; $p < $length; $p++) {
		$char = $characters[rand(0, (strlen($characters))-1)];
        $string .= $char;
    }

    return $string;
}

function detect_mobile()
{
    if(preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipaq|ipod|j2me|java|midp|mini|mmp|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT']))
        return true;
 
    else
        return false;
}
function getUserIP() {
	global $setup;
	if($setup['only_use_remote_address_for_ip'] == true) { 
		return $_SERVER['REMOTE_ADDR'];
	} else { 
		if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
			if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
				$addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
				return trim($addr[0]);
			} else {
				return $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		}
		else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}
}

function logs3errors($uploadFile) { 
	global $setup;
	$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	if(!is_dir($setup['path']."/sy-logs")) { 
		// print "No direcory";
		$parent_permissions = substr(sprintf('%o', @fileperms("".$setup['path']."/sy-photos")), -4); 
		if($parent_permissions == "0755") {
			$perms = 0755;
		} elseif($parent_permissions == "0777") {
			$perms = 0777;
		} else {
			$perms = 0755;
		}
		mkdir("".$setup['path']."/sy-logs", $perms);
		chmod("".$setup['path']."/sy-logs", $perms);
		$fp = fopen("".$setup['path']."/sy-logs/index.php", "w");
		fputs($fp, "Nope");
		fclose($fp);
	}

	$lfile = "s3-errors-log-".date('Y-m-d').".txt";

	if(!file_exists($setup['path']."/sy-logs/".$lfile)) { 
		$fp = fopen("".$setup['path']."/sy-logs/".$lfile, "w");
		fputs($fp,"");
		fclose($fp);
	}

	$info =  date('Y-m-d h:i:s')." ".getUserIP()." ".$_SERVER['HTTP_USER_AGENT']."  Unable to move file: $uploadFile"; 
	// $info .=  " ".$_SERVER['HTTP_HOST'].urldecode($_SERVER['REQUEST_URI'])." | REFERER: ".$_SERVER['HTTP_REFERER'].""; 

	$info.= "\r\n";

	file_put_contents($setup['path']."/sy-logs/".$lfile, $info, FILE_APPEND);

}

?>
