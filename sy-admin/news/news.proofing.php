	<script>
	function updateproofing(date_id,f) { 
		$("#pload").show();
		$("#pupdates").hide();
		if($("#"+f).attr("checked")) { 
			val = 1;
		} else { 
			val = 0;
		}
		$.get("admin.actions.php?action=updateproofingoptions&date_id="+date_id+"&pf="+f+"&f="+val, function(data) {
			$("#pload").hide();
			$("#pupdates").show();
		});
	}
	</script>


<div class="right textright">
	<input type="checkbox" name="proofing_disable_revise" id="proofing_disable_revise" value="1" <?php if($date['proofing_disable_revise'] == "1") { print "checked"; } ?>  onchange="updateproofing('<?php print $date['date_id'];?>','proofing_disable_revise');"> <label for="proofing_disable_revise">Disable Revise Option</label></a> &nbsp; 
	<input type="checkbox" name="proofing_disable_reject" id="proofing_disable_reject" value="1" <?php if($date['proofing_disable_reject'] == "1") { print "checked"; } ?>  onchange="updateproofing('<?php print $date['date_id'];?>','proofing_disable_reject');"> <label for="proofing_disable_reject">Disable Reject Option</label></a>  &nbsp; (Do not disable both)
		<br><span><span id="pload" class="hidden"><?php print ai_loadingsmall;?></span><span id="pupdates" class="hidden">changes saved</span></span>

	</div>
	<div class="clear"></div>
	<div>&nbsp;</div>
<?php
if(!empty($_REQUEST['proofaction'])) { 
	if($_REQUEST['proofaction'] == "closeproject") { 
		$cks = doSQL("ms_proofing_status", "*", "WHERE date_id='".$date['date_id']."' ORDER BY id DESC");
		updateSQL("ms_proofing_status", "status='2', closed='".date('Y-m-d H:i:s')."' WHERE id='".$cks['id']."' ");
	}
	if($_REQUEST['proofaction'] == "revision") { 
		$cks = doSQL("ms_proofing_status", "*", "WHERE date_id='".$date['date_id']."' ORDER BY id DESC");
		updateSQL("ms_proofing_status", "status='0' WHERE id='".$cks['id']."' ");
		insertSQL("ms_proofing_status", "status='0', date_id='".$_REQUEST['date_id']."', emailed_to='".$emails_to."', re_opened='".date('Y-m-d H:i:s')."' ");
	}
	if($_REQUEST['proofaction'] == "reopen") { 
		$cks = doSQL("ms_proofing_status", "*", "WHERE date_id='".$date['date_id']."' ORDER BY id DESC");
		updateSQL("ms_proofing_status", "status='0', re_opened='".date('Y-m-d H:i:s')."' WHERE id='".$cks['id']."' ");
	}

	if($_REQUEST['proofaction'] == "deleterejected") { 
		$cks = doSQL("ms_proofing_status", "*", "WHERE date_id='".$date['date_id']."' ORDER BY id DESC");

		$pics = whileSQL("ms_proofing LEFT JOIN ms_photos ON ms_proofing.proof_pic_id=ms_photos.pic_id", "*", "WHERE proof_date_id='".$date['date_id']."' AND proof_status='3' ");
		while($pic = mysqli_fetch_array($pics)) { 
			deleteOnePic($pic);
			deleteSQL("ms_proofing", "WHERE proof_id='".$pic['proof_id']."' ", "1");
			if($pd>0) { $and_deleted .= ", "; } 
			$and_deleted .= $pic['pic_org'];
			$pd++;

		}
		insertSQL("ms_proofing_status", "date_id='".$date['date_id']."', date='".date('Y-m-d H:i:s')."', notes='Files deleted: ".addslashes(stripslashes($and_deleted))."', proof_action='deleterejects', status='".$cks['status']."' ");

	}

	header("location: index.php?do=".$_REQUEST['do']."&action=".$_REQUEST['action']."&date_id=".$_REQUEST['date_id']."");
	session_write_close();
	exit();
}
?>
<?php
$pemails = array(); 

$ps = whileSQL("ms_my_pages LEFT JOIN ms_people ON ms_my_pages.mp_people_id=ms_people.p_id", "*, date_format(DATE_ADD(mp_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS mp_date", "WHERE mp_date_id='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
while($p = mysqli_fetch_array($ps)) { 
	if(!in_array($p['p_email'],$pemails)) { 
		array_push($pemails,$p['p_email']);
	}
}

$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id ";
$picsd = whileSQL("$pics_tables", "*", "$pics_where $and_where GROUP BY pic_id   ");
$total_images = mysqli_num_rows($picsd);
while($picd = mysqli_fetch_array($picsd)) { 
	if(countIt("ms_proofing",  "WHERE proof_date_id='".$date['date_id']."' AND proof_pic_id='".$picd['pic_id']."' AND proof_status='1' ")> 0) { 
		$total_done++;	
	}
	if(countIt("ms_proofing",  "WHERE proof_date_id='".$date['date_id']."' AND proof_pic_id='".$picd['pic_id']."' AND proof_status='2' ")> 0) { 
		$total_rev++;	
	}
	if(countIt("ms_proofing",  "WHERE proof_date_id='".$date['date_id']."' AND proof_pic_id='".$picd['pic_id']."' AND proof_status='3' ")> 0) { 
		$total_rejected++;	
	}

}
$cks = doSQL("ms_proofing_status", "*", "WHERE date_id='".$date['date_id']."' ORDER BY id DESC");
if((empty($cks['status'])) || ($cks['status']== 0)==true) { 
	?>
	<div class="blue">
	<div class="dpmenucontainer left">
	<div class="dpmenu bold">Select Action
	<div class="dpinner">
	<div class="pc"></div>
	<div class="pc"><a href="" onclick="newsusersproofing('<?php print $date['date_id'];?>'); return false;">Assign Client & Send Email (<?php print count($pemails);?>)</a></div>
	<?php if(!empty($cks['id'])) { ?>
		<div class="pc"><a href="" onclick="proofingrevise('<?php print $date['date_id'];?>'); return false;">Send Revision Email & Re-open</a></div>
		<div class="pc"><a href="" onclick="proofingclose('<?php print $date['date_id'];?>'); return false;">Close Project & Send Email To Client</a></div>
		<div class="pc"><a  id="removealllink" class="confirmdelete" confirm-title="Are you sure?" confirm-message="Are you sure you want to close this project?" href="index.php?do=<?php print $_REQUEST['do'];?>&action=<?php print $_REQUEST['action'];?>&date_id=<?php print $date['date_id'];?>&proofaction=closeproject" >Close Project</a></div>
	<?php } ?>
	</div></div> 
	<div>&nbsp;</div>
	</div>
	<div class="left" style="margin-right: 16px; font-size: 17px;">Pending Client Review </div>
	<div class="right textright"  style="font-size: 17px;">
	<?php
	if($total_done > 0) { 
		print $total_done." Approved &nbsp;&nbsp; ";
	} 
	if($total_rev > 0) { 
		print $total_rev." Revisions Requested  &nbsp;&nbsp; ";
	}
	if($total_rejected > 0) { 
		print $total_rejected." Rejected";
	}
	?>	
	</div>
	<div class="clear"></div>
	</div>

	<?php 
}
if($cks['status']== "1") { ?>
	<div class="yellowmessage">
	<div class="dpmenucontainer left">
	<div class="dpmenu bold">Select Action
	<div class="dpinner">
	<div class="pc"></div>
	<div class="pc"><a href="" onclick="proofingrevise('<?php print $date['date_id'];?>'); return false;">Send Revision Email & Re-open</a></div>
	<div class="pc"><a  id="removealllink" class="confirmdelete" confirm-title="Are you sure?" confirm-message="Are you sure you want to re-open this project without sending an email to the client?" href="index.php?do=<?php print $_REQUEST['do'];?>&action=<?php print $_REQUEST['action'];?>&date_id=<?php print $date['date_id'];?>&proofaction=revision" >Re-open without sending email</a></div>

	<div class="pc"><a href="" onclick="proofingclose('<?php print $date['date_id'];?>'); return false;">Close Project & Send Email To Client</a></div>
	<div class="pc"><a  id="removealllink" class="confirmdelete" confirm-title="Are you sure?" confirm-message="Are you sure you want to close this project?" href="index.php?do=<?php print $_REQUEST['do'];?>&action=<?php print $_REQUEST['action'];?>&date_id=<?php print $date['date_id'];?>&proofaction=closeproject" >Close Project</a></div>
	</div></div> 

					<div>&nbsp;</div>

	</div>
	<div class="left" style="margin-right: 16px; font-size: 17px;">Awaiting your action </div>
	<div class="right textright" style="font-size: 17px;">
	<?php
	if($total_done > 0) { 
		print $total_done." Approved  &nbsp;&nbsp; ";
	} 
	if($total_rev > 0) { 
		print $total_rev." Revisions Requested  &nbsp;&nbsp; ";
	}
	if($total_rejected > 0) { 
		print $total_rejected." Rejected "; ?>
		(<a  id="deleterejected" class="confirmdelete" confirm-title="Are you sure?" confirm-message="Are you sure you want to delete all rejected photos from this project?" href="index.php?do=<?php print $_REQUEST['do'];?>&action=<?php print $_REQUEST['action'];?>&date_id=<?php print $date['date_id'];?>&proofaction=deleterejected">delete rejected</a>)
		<?php } ?>	
	</div>

	<div class="clear"></div>
	</div>
<?php 
}


if($cks['status']== "2") { ?>
	<div class="greymessage">
	<div class="dpmenucontainer left">
	<div class="dpmenu bold">Select Action
	<div class="dpinner">
	<div class="pc"></div>
	<div class="pc"><a  id="removealllink" class="confirmdelete" confirm-title="Are you sure?" confirm-message="Are you sure you want to re-open this project?" href="index.php?do=<?php print $_REQUEST['do'];?>&action=<?php print $_REQUEST['action'];?>&date_id=<?php print $date['date_id'];?>&proofaction=reopen" >Re-open Project</a></div>
	</div></div> 

					<div>&nbsp;</div>

	</div>
	<div class="left" style="margin-right: 16px; font-size: 17px;">Project is closed</div>
	<div class="right textright" style="font-size: 17px;">
	<?php
	if($total_done > 0) { 
		print $total_done." Approved  &nbsp;&nbsp; ";
	} 
	if($total_rev > 0) { 
		print $total_rev." Revisions Requested";
	}

	?>	
	</div>

	<div class="clear"></div>
	</div>

<?php 
}
$cks = whileSQL("ms_proofing_status LEFT JOIN ms_people ON ms_proofing_status.person=ms_people.p_id", "*, date_format(date, '".$site_setup['date_format']." %h:%i %p')  AS date_show, date_format(re_opened, '".$site_setup['date_format']." %h:%i %p')  AS re_opened_show, date_format(closed, '".$site_setup['date_format']." %h:%i %p')  AS closed_show", "WHERE date_id='".$date['date_id']."' ORDER BY id DESC");
while($ck = mysqli_fetch_array($cks)) { 
	print "<div class=\"underline\">";
	if((!empty($ck['emailed_to']))&&($ck['date'] > 0)==true) { 
		print "<b>".$ck['date_show']."</b> Sent project email to ".$ck['emailed_to']." ";
	} 
	if(!empty($ck['p_id'])) { 
		print "<b>".$ck['date_show']."</b> ".$ck['p_name']." ".$ck['p_last_name']." completed reviewing the project. ";
	}
	if($ck['re_opened'] > 0) { 
		print "<b>".$ck['re_opened_show']."</b> - Re-opened Project ";
		if(!empty($ck['emailed_to'])) { 
			print " Sent project email to ".$ck['emailed_to']." ";
		} 

	}
	if(!empty($ck['proof_action'])) { 
		if($ck['proof_action'] == "deleterejects") { 
			print "<b>".$ck['date_show']."</b> Rejected photos deleted. ".$ck['notes']."";
		}
	}  else { 
		if(!empty($ck['notes'])) { print " and wrote \"<i>".$ck['notes']."</i>\""; } 
	}
	if($ck['closed'] > 0) { 
		print "<br><b>Closed ".$ck['closed_show']."</b>";
	}

	print "</div>";
}
if(mysqli_num_rows($cks) > 0) { ?>
	<div>&nbsp;</div>
<?php } 
?>
