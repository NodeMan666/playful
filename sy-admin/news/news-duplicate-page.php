<?php 
$path = "../../";
require "../w-header.php"; 
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");

if($_REQUEST['doit'] == "yes") { 

	$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_calendar");
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			if(($row['Field'] !== "date_id")&&($row['Field'] !== "date_title")&&($row['Field'] !== "date_public")&&($row['Field'] !== "date_date")&&($row['Field'] !== "date_time")==true) { 
				if($x > 0) { $lqry.=","; } 
				$x++;
				$lqry .= $row['Field']."='".addslashes(stripslashes($date[$row['Field']]))."' ";
	//	        print "<li>".$row['Field']." = ".$list[$row['Field']]."</li>";
			}
		}
	}
	$date_date = date("Y-m-d", mktime(date('H'), date('i'), date('s'), date('m') , date('d'), date('Y')));
	$date_time = date("H:i:s", mktime(date('H'), date('i'), date('s'), date('m') , date('d'), date('Y')));

	$lqry .= ",date_title='".addslashes(stripslashes($_REQUEST['date_title']))."', date_public='".$_REQUEST['date_public']."', date_date='".$date_date."', date_time='".$date_time."' ";
	$id = insertSQL("ms_calendar", "$lqry" );
	createNewPage($id);


	####### Sub Products  ############### 
	$ios = whileSQL("ms_product_subs", "*", "WHERE sub_main_prod='".$date['date_id']."' ORDER BY sub_id ASC");
	while($io = mysqli_fetch_array($ios)) { 
		$x = 0;
		$qry = "";
		$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_product_subs");
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				if($row['Field'] !== "sub_id") { 
					if($x > 0) { $qry.=","; } 
					$x++;
					if($row['Field'] == "sub_main_prod") { 
						$qry .= $row['Field']."='".addslashes(stripslashes($id))."' ";
					} else { 
						$qry .= $row['Field']."='".addslashes(stripslashes($io[$row['Field']]))."' ";
					}
				//    print "<li>".$row['Field']." = ".$group[$row['Field']]."</li>";
				}
			}
		}
		$opt_id = insertSQL("ms_product_subs", "$qry" );
	}


	####### Product Options  ############### 
	$ios = whileSQL("ms_product_options", "*", "WHERE opt_date='".$date['date_id']."' ORDER BY opt_id ASC");
	while($io = mysqli_fetch_array($ios)) { 
		$x = 0;
		$qry = "";
		$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_product_options");
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				if($row['Field'] !== "opt_id") { 
					if($x > 0) { $qry.=","; } 
					$x++;
					if($row['Field'] == "opt_date") { 
						$qry .= $row['Field']."='".addslashes(stripslashes($id))."' ";
					} else { 
						$qry .= $row['Field']."='".addslashes(stripslashes($io[$row['Field']]))."' ";
					}
				//    print "<li>".$row['Field']." = ".$group[$row['Field']]."</li>";
				}
			}
		}
		$opt_id = insertSQL("ms_product_options", "$qry" );

		############ Product Options Selections ###############
		$optsels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$io['opt_id']."' ORDER BY sel_id ASC");
		while($optsel = mysqli_fetch_array($optsels)) { 
			$x = 0;
			$qry = "";
			$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_product_options_sel");
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					if($row['Field'] !== "sel_id") { 
						if($x > 0) { $qry.=","; } 
						$x++;
						if($row['Field'] == "sel_opt") { 
							$qry .= $row['Field']."='".addslashes(stripslashes($opt_id))."' ";
						} else { 
							$qry .= $row['Field']."='".addslashes(stripslashes($optsel[$row['Field']]))."' ";
						}
					//    print "<li>".$row['Field']." = ".$group[$row['Field']]."</li>";
					}
				}
			}
			$sel_id = insertSQL("ms_product_options_sel", "$qry" );
		}
	}

	if($_REQUEST['dup_subs'] == "1") { 

		$subs = whileSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' AND sub_under='0' ORDER BY sub_id ASC");
		while($sub = mysqli_fetch_array($subs)) { 
			$s++;
			$x = 0;
			$qry = "";
			$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_sub_galleries");
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					if($row['Field'] !== "sub_id") { 
						if($x > 0) { $qry.=","; } 
						$x++;
						if($row['Field'] == "sub_date_id") { 
							$qry .= $row['Field']."='".addslashes(stripslashes($id))."' ";
						} elseif($row['Field'] == "sub_link") { 
							$page_link = MD5($s.$id.date('ymdhis'));
							$qry .= $row['Field']."='".addslashes(stripslashes($page_link))."' ";
						} else { 
							$qry .= $row['Field']."='".addslashes(stripslashes($sub[$row['Field']]))."' ";
						}
					//    print "<li>".$row['Field']." = ".$group[$row['Field']]."</li>";
					}
				}
			}
			$opt_id = insertSQL("ms_sub_galleries", "$qry" );
		}
	}

	$_SESSION['sm'] = "Page Duplicated";
	header("location: ../index.php?do=news&action=addDate&date_id=".$id."");
	session_write_close();
	exit();
}
?>
<div class="pc"><h2>Duplicate <?php print $date['date_title'];?></div>
<div class="pc">This will duplicate this page and create a new one with any options and sub products. This will not duplicate any photos uploaded to a gallery.</div>
<form method="post" name="duppage" action="<?php print $_SERVER['PHP_SELF'];?>"   onSubmit="return checkForm();">
<div class="underline">
	<div class="label">Title</div>
	<div><input type="text" name="date_title" id="date_title" class="required inputtitle field100" value="<?php print htmlspecialchars($date['date_title']);?>"></div>
</div>
<?php if(countIt("ms_sub_galleries", "WHERE sub_date_id='".$date['date_id']."' ") > 0) { ?>
<div class="underline">
	<div class="label"><input type="checkbox" name="dup_subs" id="dup_subs" value="1"> <label for="dup_subs">Duplicate top level sub galleries</label></div>
</div>
<?php } ?>
<div class="underline">
<div class="label">Status</div>
	<select name="date_public" class="inputtitle">
	<option value="2"  <?php  if($date['date_public']=="2") { print " selected"; } ?>>Draft</option>
	<option value="1"  <?php  if($date['date_public']=="1") { print " selected"; } ?>>Publish</option>
	<?php if($cat_type == "clientphotos") { ?>
	<option value="3" <?php  if($date['date_public']=="3") { print " selected"; } ?>>Pre-Register</option>
	<?php } ?>
	</select>
</div>
<div>&nbsp;</div>

<div class="pc center">
<input type="hidden" name="doit" id="doit" value="yes">
<input type="hidden" name="date_id" id="date_id" value="<?php print $date['date_id'];?>">
<input type="submit" name="submit" class="submit" value="Duplicate">
</form>

<?php require "../w-footer.php"; ?>
