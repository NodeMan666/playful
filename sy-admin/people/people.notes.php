<?php if($_REQUEST['sub'] == "savenote") { 
	$table = "ms_people";
	$table_id = $_REQUEST['p_id'];
	$message = trim($_REQUEST['note_note']);
	insertSQL("ms_notes","note_date='".currentdatetime()."', note_table='".$table."', note_table_id='".$table_id."', note_note='".addslashes(stripslashes($message))."', note_ip='".getUserIP()."', note_admin='1', note_is_note='1'  ");
	header("location: index.php?do=people&p_id=".$_REQUEST['p_id']."&view=notes");
	$_SESSION['sm'] = "Note Saved";
	session_write_close();
	exit();
}
?>

<?php if($_REQUEST['sub'] == "deletenote") { 
	if($_REQUEST['note_id'] > 0) { 
		deleteSQL("ms_notes", "WHERE note_id='".$_REQUEST['note_id']."' ","1");
	}
	header("location: index.php?do=people&p_id=".$_REQUEST['p_id']."&view=notes");
	$_SESSION['sm'] = "Note Deleted";
	session_write_close();
	exit();
}
?>


<!-- 
<form method="post" name="note" id="note" action="index.php">
<div class="pc"><textarea name="note_note" id="note_note" rows="3"></textarea></div>
<?php addEditor("note_note","1", "500", "0"); ?>
<div>
<input type="hidden" name="do" id="do" value="people">
<input type="hidden" name="p_id" id="p_id" value="<?php print $p['p_id'];?>">
<input type="hidden" name="view" id="view" value="notes">
<input type="hidden" name="sub" id="sub" value="savenote">
<input type="submit" name="submit" class="submit" value="Save Note">
</div>
</form>
-->

<div class="pc buttons textright"><a href="" onclick="peoplenotes('0','<?php print $p['p_id'];?>'); return false;" >+ NOTE</a></div>
	<div id="emails">
		<div>
		<div class="underlinelabel">Notes & Activity</div>
		<?php 
		$notes = whileSQL("ms_notes", "*,date_format(note_date, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS note_date_show", "WHERE note_table='ms_people' AND note_table_id='".$p['p_id']."' ORDER BY note_date DESC ");
		if(mysqli_num_rows($notes) <= 0) { ?>
		<div class="pc center">No notes or activity added.</div>
		<?php } ?>
		<?php 
		while($note = mysqli_fetch_array($notes)) { ?>
		<div class="underline">
		<div class="left"><?php if($note['note_is_note'] == "1") { ?><span class="the-icons icon-sticky-note" style="color: #F7D840;"></span><?php } ?> <?php print $note['note_date_show'];?></div>
		<div class="right textright">
		<?php if($note['note_is_note'] == "1") { ?>
		<a href="" onclick="peoplenotes('<?php print $note['note_id'];?>','<?php print $note['note_table_id'];?>'); return false;" class="the-icons icon-pencil"></a>
		<a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=notes&note_id=<?php print $note['note_id'];?>&sub=deletenote" onclick="return confirm('Are you sure you want to delete this note?');" class="the-icons icon-trash-empty"></a>
		<?php } ?>
		</div>
		<div class="clear"></div>
		
		<div><?php print $note['note_note'];?></div>
		<?php if($note['note_admin'] !== "1") { 
		if(!empty($note['note_ip'])) { ?>
		<div><a href="index.php?do=stats&action=recentVisitors&q=<?php print $note['note_ip'];?>"><?php print $note['note_ip'];?></a></div><?php } ?>
		<?php } ?>
		</div>
		<?php } ?>

		</div>
	</div>
