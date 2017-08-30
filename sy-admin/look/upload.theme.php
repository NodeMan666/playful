<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}


$FILENAME=$_FILES['image']['name']; 
$tempFile = $_FILES['image']['tmp_name'];
$size_upfull = @GetImageSize($_FILES['image']['tmp_name']); 
$destination = ("".$setup['path']."/".$setup['misc_folder']."/".$FILENAME);
// print "<li>".$_FILES['image']['name'];
copy($tempFile, $destination);

$content =file_get_contents($destination);
// print "$content";
$c = explode("||",$content);
foreach($c AS $qry) { 
	$qry = trim($qry);
	if(!empty($qry)) { 
		if($x == 2) { 
			$fonts = $qry;
		} else { 
			$qry = $qry.";";

			$sql = mysqli_query($dbcon,$qry);
			if (!$sql) {	echo( " It looks like this isn't a proper theme file to upload. There are errors. Details: <br><br>" . mysqli_error($dbcon) . ".");	unlink($destination); exit(); }
		//	return $id = mysqli_fetch_array($sql);
			if($x <=0) { 
				$this_id = $id;
			} 
		}
		$x++;
	}
}
unlink($destination);
$_SESSION['sm'] = "Theme imported";
$new = doSQL("ms_css", "*","ORDER BY css_id DESC ");
$new2 = doSQL("ms_css2","*", "ORDER BY css2_id DESC ");
updateSQL("ms_css2", "parent_css_id='".$new['css_id']."' WHERE css2_id='".$new2['css2_id']."' ");
$fs = explode("-",$fonts);
foreach($fs AS $f) { 
	$f = trim($f);
	if(!empty($f)) { 
		insertSQL("ms_google_fonts", "font='$f', theme='".$new['css_id']."' ");
	}
}

session_write_close();
header("location: theme-edit.php?css_id=".$new['css_id']."");
exit();
?>
