<?php
date_default_timezone_set('America/Chicago');
// print_r($_SERVER);

$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
// print "<li>".getUserIP()." ".date('Y-m-d h:i:s')." ".$_SERVER['HTTP_USER_AGENT']." ".$url;

if(!is_dir($setup['path']."/sy-logs")) { 
	print "No direcory";
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

$lfile = "log-".date('Y-m-d')."-".$log_append.".txt";

if(!file_exists($setup['path']."/sy-logs/".$lfile)) { 
	$fp = fopen("".$setup['path']."/sy-logs/".$lfile, "w");
	fputs($fp,"");
	fclose($fp);
}

if($_SERVER['SCRIPT_NAME'] !== "/sy-vstats.php") { 
	if(isset($_POST)) { 
		foreach($_POST AS $id => $val) { 
			$postarray .= "[".$id." = ".$val."] ";
		}
	}
	foreach($_GET AS $id => $val) { 
		$getarray .= "[".$id." = ".$val."] ";
	}

	$info =  $_SERVER['REMOTE_ADDR']." ".date('Y-m-d h:i:s')." URL: ".$_SERVER['HTTP_HOST'].urldecode($_SERVER['REQUEST_URI'])." | REFERER: ".$_SERVER['HTTP_REFERER'].""; 
	if(!empty($postarray)) { 
		$info.= " | POST: ".$postarray;
	}
	if(!empty($getarray)) { 
		$info.= " | GET: ".$getarray;
	}
	$info.= "\r\n\r\n";

	file_put_contents($setup['path']."/sy-logs/".$lfile, $info, FILE_APPEND);
}
?>
