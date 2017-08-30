<?php 
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body>
<div id="pageTitle"><a href="index.php?do=people">People</a> <?php print ai_sep;?> Import</div>


<?php 

// $qry = mysqli_query($dbcon,"ALTER TABLE ms_people CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci");
// if (!$qry) {		die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }

function file_get_contents_utf8($fn) {
     $content = file_get_contents($fn);
      return mb_convert_encoding($content, 'UTF-8',
          mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}
$imported = file_get_contents_utf8($_FILES['file']['tmp_name']); 
//$imported = mb_convert_encoding($imported, 'HTML-ENTITIES', "UTF-8");
$import = explode("\r\n",$imported);
foreach($import AS $p) {
	$pp = explode("||",$p);
	if(!empty($pp[0])) { 
		$ck = doSQL("ms_people", "*", "WHERE p_email='".$pp[0]."' ");
		if(empty($ck['p_id'])) { 
			// print "<li>".$pp[0];
			if(!empty($pp[1])) { 
				$total_import++;
				$sql =$pp[1];
				if(@mysqli_query($dbcon,$sql)) {		} else {	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: $sql</div>"); 	}
				$id = mysqli_insert_id($dbcon);
			}
			// print "<li>".$sql;
		}
	}
}
print "<div><h2>$total_import people imported. <a href=\"index.php?do=people\">Click here to view the people section</a></h2></div>";

// print "$import";
?>
