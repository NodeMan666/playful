<?php 
if($_REQUEST['action'] == "deleteGiftCertificate") { 
	deleteSQL("ms_gift_certificates", "WHERE id='".$_REQUEST['id']."' ","1");
	$_SESSION['sm'] = "eGift Card Deleted";
	session_write_close();
	header("location: index.php?do=people&view=giftcertificates");
	exit();
}
if($_REQUEST['action'] == "sendgiftcard") { 
	$gc = doSQL("ms_gift_certificates", "*", "WHERE id='".$_REQUEST['gcid']."' ");
	sendgiftcertificateemail($gc['id']);
	$_SESSION['sm'] = "eGift Card Sent to ".$gc['to_email']."";
	session_write_close();
	header("location: index.php?do=people&view=giftcertificates");
	exit();
}

?>
<div class="right textright"><a href="https://www.picturespro.com/sytist-manual/people/egift-cards/" target="_blank" class="the icons icon-info-circled">eGift Cards in Manual</a></div>
<div class="pc"><a href="index.php?do=people">&larr; People</a></div>
<div class="pc newtitles"><span class="">eGift Cards</span></div> 
<div class="buttonsgray">
<ul>
	<li><a href="index.php?do=people&view=giftcertificates" class="on">eGIFT CARDS</a></li>
	<li><a href="index.php?do=people&view=giftcertificates&sub=settings">eCARD STYLE</a></li>
	<li><a href="index.php?do=people&view=giftcertificates&sub=language">LANGUAGE</a></li>
	<li><a href="index.php?do=people&view=giftcertificates&sub=amounts" >AMOUNTS & SETTINGS</a></li>


</ul>
</div>
<?php $em = doSQL("ms_emails", "*", "WHERE email_id_name='giftcertificate' "); ?>
<div class="clear"></div>
<div>&nbsp;</div><div class="pc">You can offer eGift Cards to be purchased by visitors for other people in which the recipient will receive an email with a redeem code to redeem the eGift Card.<br><br><span class="bold">To enable this, you just need to enable the link in your menu in <a href="index.php?do=look&view=links">Design -> Menu Links</a></span>. <br><a href="index.php?do=settings&action=defaultemailsedit&email_id=<?php print $em['email_id'];?>">Edit default email sent when someone receives an eGift Card</a></div>
<?php 
if(empty($_REQUEST['acdc'])) { 
	$acdc = "DESC";
	$oposit = "ASC";
} else { 
	$acdc = $_REQUEST['acdc'];
	if($acdc == "ASC") { 
		$oposit = "DESC";
	}
	if($acdc == "DESC") { 
		$oposit = "ASC";
	}

}
if(empty($_REQUEST['orderby'])) { 
	$orderby = "id";
} else { 
	$orderby = $_REQUEST['orderby'];
}
if(empty($_REQUEST['pg'])) {
	$pg = "1";
} else {
	$pg = $_REQUEST['pg'];
}

if($_REQUEST['per_page'] > 0) { 
	$per_page = $_REQUEST['per_page'];
	updateSQL("ms_history", "per_page='".$_REQUEST['per_page']."' ");
	$history['per_page'] = $per_page;
} else { 
	$per_page = $history['per_page'];
}
$total  = countIt("ms_gift_certificates",  "WHERE id>='0' $and_where ORDER BY $orderby $acdc ");

$NPvars = array("do=people", "q=".$_REQUEST['q']."","orderby=".$orderby."", "acdc=".$acdc."", "starts=".$_REQUEST['starts']."","view=giftcertificates");
$sq_page = $pg * $per_page - $per_page;	
?>
<div id="credits">
	<div class="pc buttons textright"><a href="" onclick="editgiftcertificate(0); return false;">Add eGift Card</a></div>
	<div class="clear"></div>
		<div class="underlinecolumn">			
			<div class="p5 left">&nbsp;</div>
			<div class="p5 left">Amount</div>
			<div class="p10 left" >Date Purchased</div>
			<div class="p15 left">From</div>
			<div class="p15 left">To</div>
			<div class="p10 left">Purchased Order</div>
			<div class="p10 left">Redeemed Order</div>
			<div class="p10 left">Redeem Code</div>
			<div class="p10 left">To Send Date</div>
			<div class="p10 left">Sent Date</div>
			<div class="clear"></div>
		</div>
		<?php $gcs  = whileSQL("ms_gift_certificates", "*,date_format(DATE_ADD(date_purchased, INTERVAL ".$site_setup['time_diff']." HOUR), '%c/%e/%y ')  AS date_purchased,date_format(DATE_ADD(delivery_date, INTERVAL ".$site_setup['time_diff']." HOUR), '%c/%e/%y')  AS delivery_date_show,date_format(DATE_ADD(emailed_date, INTERVAL ".$site_setup['time_diff']." HOUR), '%c/%e/%y')  AS emailed_date_show", "WHERE id>='0' $and_where ORDER BY $orderby $acdc  LIMIT $sq_page,$per_page ");
		if(mysqli_num_rows($gcs) <= 0) { ?>
			<div>&nbsp;</div>
			<div>&nbsp;</div>
			<div class="pc center"><h3>No eGift Cards</h3></div>
			<div>&nbsp;</div>
			<div>&nbsp;</div>
			<div>&nbsp;</div>
			<div>&nbsp;</div>
		<?php } ?>
		<?php 
		while($gc = mysqli_fetch_array($gcs)) { ?>
		<div class="underline">			
			<div class="p5 left"><a href="" onclick="editgiftcertificate('<?php print $gc['id'];?>'); return false;" class="the-icons icon-pencil"></a>
			<a  id="removealllink" class="confirmdelete the-icons icon-trash-empty" confirm-title="Delete Gift Certificate" confirm-message="Are you sure you want to delete this gift certificate?" href="index.php?do=people&view=giftcertificates&id=<?php print $gc['id'];?>&action=deleteGiftCertificate"></a> 
			</div>

			<div class="p5 left"><h3><?php print showPrice($gc['amount']);?></h3></div>
			<div  class="p10 left"><?php print $gc['date_purchased'];?></div>
			<div  class="p15 left">
				<div><?php print $gc['from_name'];?></div>
				<div><?php print $gc['from_email'];?></div>
				<div><i><?php print nl2br($gc['message']);?></i></div>
			</div>
			<div  class="p15 left">
				<div><?php print $gc['to_name'];?></div>
				<div><?php print $gc['to_email'];?></div>
			</div>
			<div  class="p10 left">
				<?php if($gc['admin_created'] == "1") { ?>Admin Created<?php } else { ?><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $gc['purchased_order_id'];?>"><?php print $gc['purchased_order_id'];?></a><?php } ?>
			</div>
			<div  class="p10 left">
				<div><?php if($gc['used_order'] > 0) { ?><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $gc['used_order'];?>"><?php print $gc['used_order'];?></a><?php } else { ?><span class="muted">N/A</span><?php } ?></div>
			</div>
			<div  class="p10 left">
				<div><?php print $gc['redeem_code'];?></div>
			</div>
			<div  class="p10 left">
				<div><?php if(($gc['delivery_date'] <= date('Y-m-d')) && ($gc['emailed_date'] <=0) == true) { ?><span class="unpaid"><?php print $gc['delivery_date_show'];?></span> <?php  } else { print $gc['delivery_date_show']; }?>&nbsp;</div>
			</div>
			<div  class="p10 left">
				<div><?php if($gc['emailed_date'] <=0) { ?>Not Sent<br><a href="index.php?do=people&view=giftcertificates&action=sendgiftcard&gcid=<?php print $gc['id'];?>" class="the-icons icon-mail confirmdelete" title="Email eGift Card" confirm-title="Email eGift Card" confirm-message="Are you sure you want to email this eGift Card now?">Send Now</a><?php  } else { print $gc['emailed_date_show']; ?><br><a href="index.php?do=people&view=giftcertificates&action=sendgiftcard&gcid=<?php print $gc['id'];?>" class="the-icons icon-mail confirmdelete" title="Email eGift Card" confirm-title="Email eGift Card" confirm-message="Are you sure you want to email this eGift Card now?" style="font-size: 12px; color: #999999;">Resend</a> <?php }?></div>
			</div>



			<div class="clear"></div>
		</div>
		<?php } ?>
	<div>&nbsp;</div>
<?php
	if($total > $per_page) {
		print "<div class=\"center\"><center>".nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $_REQUEST)."</center></div>"; 
	}
?>
	</div>