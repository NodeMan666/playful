<?php 
define("_store_email_address_for_ml_", "Search in comments");
?>
	<div style="float: right;">
		<form method="get" name="search" action="index.php" style="padding: 0px; margin: 0px;"><input type="hidden" name="do" value="comments"> <input type="text" class="ff-default-value"  name="comq" size="40" <?php if(!empty($_REQUEST['comq'])) { ?> value="<?php print $_REQUEST['comq'];?>"<?php } else { ?>  value="<?php print _store_email_address_for_ml_;?>" onfocus="if (this.value == '<?php print _store_email_address_for_ml_;?>') {this.value = ''; this.className='ff-input';}" onblur="if (this.value == '') {this.value = '<?php print _store_email_address_for_ml_;?>'; this.className='ff-default-value';}" <?php } ?> ><button type="submit" class="submit">Search</button></form></div>

<div id="pageTitle"><a href="index.php?do=comments">Comments</a> 



<?php 
		$ACDC = "ASC";
if(empty($_REQUEST['comq'])) {

	if($_REQUEST['status']=="approved") {
		$and_where .= " AND com_approved='1'";
		$ACDC = "DESC";
		print " ".ai_sep."  Approved Comments";
	} else if($_REQUEST['status']=="trash") {
		$and_where .= " AND com_approved='2' ";
		print " ".ai_sep."  Trashed Comments";
		if(countIt("ms_comments", "WHERE com_approved='2' ")>0) {
			print " (<a href=\"index.php?do=comments&action=emptyTrash\"  onClick=\"return confirm('Are you sure you want to delete all the comments in the trash? ');\">Empty Trash</a>)";
		}
	} else {
		$and_where .= " AND com_approved='0' ";
		print " ".ai_sep."  Pending Comments";
	}
}

if(!empty($_REQUEST['comq'])) {
	$and_where .= "AND ( com_email LIKE '%".addslashes($_REQUEST['comq'])."%'  OR   com_name LIKE '%".addslashes($_REQUEST['comq'])."%'  ) ";
	print " ".ai_sep."  search for ".$_REQUEST['comq']."";
}


?>
</div>
<div class="cssClear">&nbsp;</div>
<div >&nbsp;</div>



<?php
$total = countIt("ms_comments", "WHERE com_id>'0' $and_where "); 
if($total <= 0) { ?>
	<div id="cssMainContainer">
		<div  class=cssRowContainer style="text-align:center;">No comments found</div>
	</div>
<?php } else { ?>

	<?php 	
	if(empty($_REQUEST['pg'])) {
		$pg = "1";
	} else {
		$pg = $_REQUEST['pg'];
	}

	$per_page = 20;
	$NPvars = array("do=comments", "comq=".$_REQUEST['comq']."","status=".$_REQUEST['status']." " );
	$sq_page = $pg * $per_page - $per_page;	
	?>

	<form method="post" name="listForm" id="listForm" action="index.php" style="margin:0px;padding:0px;">

	

	<div id="roundedSide">

	
		<?php
		$datas = whileSQL("ms_comments", "*,date_format(DATE_ADD(com_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS com_date", "WHERE com_id>'0' $and_where ORDER BY com_id  $ACDC  LIMIT $sq_page,$per_page  ");
		while ($data = mysqli_fetch_array($datas)) {
			$rownum++;
			?>


	<div class="roundedSideSep">
		<div style="width:10%;" class="cssCell">

				<?php
				if($total > 1) {
					print "<input  name=\"com_id[]\" value=\"".$data['com_id']."\" class=\"toselect\" type=\"checkbox\" style=\"padding: 0px; margin: 0px; verticle-align: middle;\"> ";
				} else {
					print "&nbsp;";
				}
				?>
			<?php  
			if($data['com_approved']=="0") { print "<a href=\"index.php?do=comments&status=".$_REQUEST['status']."&approve=".$data['com_id']."\">".ai_message_approve."</a>"; 
				print " &nbsp; <a href=\"index.php?do=comments&status=".$_REQUEST['status']."&trash=".$data['com_id']."\">".ai_message_delete."</a> "; }
				 if($data['com_approved']=="2") { print "<a href=\"index.php?do=comments&status=".$_REQUEST['status']."&approve=".$data['com_id']."\">".ai_message_approve."</a>"; }
				?>
				</div>
			<div style="width:30%; float: left;">
			<span class="bold"><?php print $data['com_name']; ?> (<?php print $data['com_email']; ?>)</span>
			<div>Comment on: <a href="<?php print $data['com_link'];?>" target="_blank"><?php print $data['com_title'];?></a></div>
			</div>
			
			<div style="width:20%; float: left;"><?php print $data['com_date']; ?></div>
			<div style="width:20%; float: left;">IP: <?php print $data['com_ip']; ?></div>
			<div style="width:20%; float: left;"><?php if(!empty($data['com_website'])) {  print "<a href=\"http://".str_replace("http://", "", $data['com_website'])."\" target=\"_blank\">".$data['com_website']."</a>"; }?></div>
			<div class="cssClear"></div>
		</div>

		
	<div class="roundedSideSep">
		<div style="width:10%;" class="cssCell">&nbsp;</div>
			<div style="width:90%; padding: 10px;">
			<?php if(!empty($data['com_comment'])) { ?><div><div class="commentMessage"><?php print nl2br($data['com_comment']);?></div></div><?php } ?>
			</div>

		<div class="cssClear"></div>
	</div>



			<?php } ?>
			<div class="cssClear"></div>
</div>

		<?php 	if($total > 1) { ?>
<div>&nbsp;</div>
		<div>
		<div style="float: left;">
		<input type="checkbox" onclick="checkAll(document.getElementById('listForm'), 'toselect');">Select all
		<input type="hidden" name="do" value="comments">
		<input type="hidden" name="status" value="<?php print $_REQUEST['status'];?>">
		<input type="hidden" name="comq" value="<?php print $_REQUEST['comq'];?>">
		<select name="action">
		<option value="">Action for selected items</option>
		<?php if(empty($_REQUEST['status'])) { ?>
			<option value="batchApprove">Approve</option>
			<option value="batchTrash">Send to trash</option>
		<?php } ?>
		<?php if($_REQUEST['status']=="approved") { ?>
			<option value="batchTrash">Send to trash</option>
		<?php } ?>
		<?php if($_REQUEST['status']=="trash") { ?>
			<option value="batchApprove">Approve</option>
		<?php } ?>


		</select>
		<button type="submit" class="submit">Go</button>
		</div>
		<div style="float: right;">
			<?php 
	if($total > $per_page) {
		print "<center>".nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $req)."</center>"; 
		}
?>
</div>

		</div>
		<?php } ?>

		</div></form>
		</div>
<?php } ?>
