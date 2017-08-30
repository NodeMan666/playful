<?php require "w-header.php"; ?>
<?php $date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
if(!empty($_REQUEST['sub_id'])) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
}
	?>

<?php if($date['green_screen_gallery'] == "1") { ?>
<div class="pc"><h1>Create Folder Or Folders For Green Screen Backgrounds</h1></div>
<?php } else { ?>
<div class="pc"><h1>Sub Galleries for <?php print $date['date_title'];?> 
			<?php 
			if($sub['sub_id'] > 0) { 	
				$ids = explode(",",$sub['sub_under_ids']);
				foreach($ids AS $val) { 
					if($val > 0) { 
						$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
						print " > ".$upsub['sub_name']." ";
					}
				}
			print " > ".$sub['sub_name'];
			}
			?>


</h1></div>
<?php } ?>
<?php

if($_POST['submitit']=="yes") { 
	if(!empty($sub['sub_id'])) { 
		if(empty($sub['sub_under_ids'])) { 
			$sub_under_ids = $sub['sub_id'];
		} else { 
			$sub_under_ids = $sub['sub_under_ids'].",".$sub['sub_id'];
		}
	}

	foreach($_POST['sub_gal'] AS $id => $val) { 
		$val = trim($val);
		if(!empty($val)) {
			$pass = trim($_REQUEST['sub_pass'][$id]);
			//print "<li>".$val;

			$order++;	
			$page_link = stripslashes(trim(strtolower($val)));
			$page_link = strip_tags($page_link);
			$page_link = str_replace(" ","".$site_setup['sep_page_names']."",$page_link);
			$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);
			if(countIt("ms_sub_galleries", "WHERE sub_date_id='".$_REQUEST['date_id']."' AND sub_under='".$_REQUEST['sub_id']."' AND sub_link='".$page_link."' ") > 0) { 
				$page_link = $page_link.date('Ymdhis');
			}
			$page_link = MD5($page_link.$date['date_id'].$order.date('ymdhis'));
			//print "<li>".$page_link;
			insertSQL("ms_sub_galleries", "sub_name='".addslashes(stripslashes($val))."', sub_pass='".addslashes(stripslashes($pass))."', sub_date_id='".$_REQUEST['date_id']."', sub_under='".$_REQUEST['sub_id']."', sub_under_ids='".$sub_under_ids."' , sub_order='$order', sub_link='".$page_link."' ");
		}
	}
//	die();
	$_SESSION['sm'] = "Sub galleries created";
	header("location: index.php?do=news&action=managePhotos&date_id=".$_REQUEST['date_id']."&sub_id=".$_REQUEST['sub_id']." ");
	session_write_close();
	exit();
}
?>



	<form name="editoptionform" action="<?php print $_SERVER['PHP_SELF'];?>" method="post"   onSubmit="return checkForm('.optrequired');">
	
	<div style="width: 25%; float: left;">
	<?php if($date['green_screen_gallery'] == "1") { ?>
	<div class="pc">Enter in the names of the folder or folders you want to create.</div>
	<?php } else { ?>
	<div class="pc">Enter in the names of the sub galleries you want to create to the right. Once you create the sub galleries, you will be able to upload photos to them.</div>
	<?php } ?>
	</div>
	<div style="width: 75%; float: left;">
		<div class="underlinelabel">
			<div class="p60 left">
				<div>Name</div>
			</div>
			<div class="p40 left">
			<?php if($date['green_screen_gallery'] == "1") { ?>
				&nbsp;
			<?php } else { ?>
				<div>Password (optional)</div>
				<?php } ?>
			</div>
		<div class="clear"></div>
		</div>

	<?php 
	$x = 0;
	while($x <= 8) { 
		$x++;
		?>
		<div class="underline">
			<div class="p60 left">
				<div><input type="text" name="sub_gal[]" id="sub_gal-<?php print $x;?>" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['opt_name']));?>" class="" size="40"></div>
			</div>
			<div class="p40 left">
				<?php if($date['green_screen_gallery'] == "1") { ?>
					&nbsp;
				<?php } else { ?>
				<div><input type="text" name="sub_pass[]" id="sub_gal-<?php print $x;?>" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['opt_name']));?>" class="" size="12"></div>
				<?php } ?>
			</div>
		<div class="clear"></div>
		</div>
	<?php } ?>

	</div>
	<div class="clear"></div>
	<div class="pc center">
	<input type="hidden" name="submitit" value="yes">
	<input type="hidden" name="date_id" value="<?php print $date['date_id'];?>">
	<input type="hidden" name="sub_id" value="<?php print $sub['sub_id'];?>">
	<input type="submit" name="submit" id="submitButton" class="submit" value="	<?php if($date['green_screen_gallery'] == "1") { ?>Create Folders<?php } else { ?>Create Sub Galleries<?php } ?>">
	</div>

	</form>

<?php require "w-footer.php"; ?>