<?php $p = doSQL("ms_people", "*,date_format(DATE_ADD(p_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS p_date, date_format(DATE_ADD(p_last_active, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS p_last_active", "WHERE p_id='".$_REQUEST['p_id']."' ");
$no_trim = true;
	if($setup['demo_mode'] == true) { 
		$p['p_name'] = get_starred($p['p_name']);
		$p['p_last_name'] = get_starred($p['p_last_name']);
		$p['p_email'] = "demo@demo.mode";
	}

?>

<?php
if($_REQUEST['action'] == "deleteCredit") { 
	deleteSQL("ms_credits", "WHERE credit_id='".$_REQUEST['credit_id']."' ", "1");
	$_SESSION['sm'] = "Credit Deleted";
	header("location: ".$setup['temp_url_folder']."/".$setup['manage_folder']."/index.php?do=people&p_id=".$_REQUEST['p_id']."&view=credits");
	session_write_close();
	exit();
}
?>
<div class="pc"><a href="index.php?do=people">&larr; People</a></div>
<div class="pc newtitles"><span class=""><?php print $p['p_name']." ".$p['p_last_name']; ?></span></div> 

<!-- New people menu -->
		<div class="pc">
		<div class="left">Status: <?php if($p['p_deactivated'] <=0) { print "<span class=\"green\">active</span>"; } if($p['p_deactivated'] =="1") { print "<span class=\"red\">deactivated</span>"; } ?>

		<a href="" onclick="editpeople('<?php print $p['p_id'];?>'); return false;">edit</a> &nbsp; 
		<?php if($p['p_deactivated'] =="1") { ?>
		<a href="index.php?do=people&action=reactivate&p_id=<?php print $p['p_id'];?>" onClick="return confirm('Are you sure you want to reactivate this account? ');">reactivate</a> 
		<?php } else { ?>
		<a href="index.php?do=people&action=deactivate&p_id=<?php print $p['p_id'];?>" onClick="return confirm('Are you sure you want to deactivate this account? This account will not be able to log in if you do so. ');">deactivate</a> 
		<?php } ?>
		 &nbsp;  <a href="index.php?do=people&action=delete&p_id=<?php print $p['p_id'];?>" onClick="return confirm('Are you sure you want to DELETE this account? This will permantly delete this account. \r\n This also means they can create an account at a later time.');">delete</a>  &nbsp; 
		<a  id="login" class="confirmdelete tip" confirm-title="Log in as customer" confirm-message="Are you sure you want to log into the website as <?php print $p['p_name']." ".$p['p_last_name'];?>?<br><br><b>Note you will stay logged in as this customer until you actually log out of the front end of the website.</b>" href="admin.actions.php?action=accountlogin&pid=<?php print MD5($p['p_id']);?>" target="_blank" title="Log in as this customer">log In</a>

		</div>
		<div class="clear"></div>
		</div>

<div class="buttonsgray">
	<ul>
		<?php 
		if($setup['no_expired_print_credits'] == true) { 
			$ctotal = doSQL("ms_credits", "SUM(credit_amount) AS tot", "WHERE credit_customer='".$p['p_id']."'  "); 
		} else { 
			$ctotal = doSQL("ms_credits", "SUM(credit_amount) AS tot", "WHERE credit_customer='".$p['p_id']."'  AND (credit_expire='0000-00-00' OR credit_expire>='".date('Y-m-d')."' )  "); 
		}
		$etotal = doSQL("ms_credits", "SUM(credit_amount) AS tot", "WHERE credit_customer='".$p['p_id']."' AND  credit_expire!='0000-00-00' AND credit_expire<'".date('Y-m-d')."'   "); 

		$order = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE order_customer='".$p['p_id']."'  AND order_status<'2'");
		$totalemails =  countIt("ms_email_logs", "WHERE (log_from='".$p['p_email']."' OR log_to='".$p['p_email']."') ORDER BY log_id DESC ");
		
		// print "<li>".$ctotal['tot'];
		// print "<li>".$etotal['tot'];
		?>

	<li  id="taborders"><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=orders" class="gtab <?php if((empty($_REQUEST['view'])) || ($_REQUEST['view'] == "orders") == true) { print "on"; } ?>">ORDERS &nbsp;
	 <?php if(countIt("ms_orders", "WHERE order_customer='".$p['p_id']."'  AND order_status<'2'") > 0) { ?> <span class="numberCircle"><span class="height_fix"></span><span class="content favoritestotaltop"><?php print countIt("ms_orders", "WHERE order_customer='".$p['p_id']."' ");?></span></span> <?php print showPrice($order['tot']);?><?php } ?>
	</a></li>


	<?php $stotal = homeShoppingCartTotal(MD5($p['p_id']),0);
	if($stotal['total_items'] > 0) { ?>
	<li><a href="" onclick="viewcustomercart('<?php print MD5($p['p_id']);?>','0'); return false;">Cart &nbsp;  <span class="numberCircle"><span class="height_fix"></span><span class="content favoritestotaltop"><?php print $stotal['total_items'];?></span></span><?php print showPrice($stotal['show_cart_total']);?></a></li>
	<?php } ?>


	<li id="tabcredits"><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=credits" class="gtab <?php if($_REQUEST['view'] == "credits") { print "on"; } ?>">CREDITS <?php if($ctotal['tot'] > 0) { print " - ".showPrice($ctotal['tot']);  } ?></a></li>


	<li id=""><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=invoice" class="<?php if($_REQUEST['view'] == "invoice") { print "on"; } ?>">CREATE INVOICE </a></li>

<?php 
	$dates = whileSQL("ms_my_pages LEFT JOIN ms_calendar ON ms_my_pages.mp_date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE mp_people_id='".$p['p_id']."' AND date_id>'0' ");
?>
	<li id=""><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=galleries"  class="<?php if($_REQUEST['view'] == "galleries") { print "on"; } ?>">GALLERIES <?php if(mysqli_num_rows($dates) > 0) { ?>&nbsp;<span class="numberCircle"><span class="height_fix"></span><span class="content"><?php print mysqli_num_rows($dates);?></span></span><?php } ?></a></li>
	<?php $contracts = countIt("ms_contracts", "WHERE person_id='".$p['p_id']."' "); ?>

	<li id=""><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=contracts"  class="<?php if($_REQUEST['view'] == "contracts") { print "on"; } ?>">CONTRACTS <?php if($contracts > 0) { ?>&nbsp;<span class="numberCircle"><span class="height_fix"></span><span class="content"><?php print $contracts;?></span></span><?php } ?></a></li>

	<li id=""><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=notes"  class="<?php if($_REQUEST['view'] == "notes") { print "on"; } ?>">NOTES / ACTIVITY</a></li>
<?php
	$books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m %e ')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE book_account='".$p['p_id']."' AND (book_date>='".date('Y-m-d')."' OR book_recurring_y='1')  AND book_confirmed='2' GROUP BY book_id    ORDER BY book_date_order ASC , book_time ASC ");
	$tb = mysqli_num_rows($books);
	?>
	<li><a href="" onclick="viewday('0','0','0','','<?php print $p['p_id'];?>','<?php print $p['p_id'];?>'); return false;" title="Calendar"><span class="the-icons icon-calendar" style="font-size: 19px; color: #FFFFFF;"></span><?php if($tb > 0) { ?><span class="numberCircle"><span class="height_fix"></span><span class="content favoritestotaltop"><?php print $tb;?></span><?php } ?></a></li>

	<li><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=favorites" title="Favorites" class="<?php if($_REQUEST['view'] == "favorites") { print "on"; } ?>"><span class="the-icons icon-heart" style="color: #c44444; font-size: 19px;"></span><span class="numberCircle"><span class="height_fix"></span><span class="content"><?php print countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id  LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "WHERE fav_person='".$p['p_id']."'  AND ms_photos.pic_id>'0'  ");?></span></span></a></li>

	<li id="tabemails"><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=emaillog" title="Email Log" class="gtab <?php if($_REQUEST['view'] == "emaillog") { print "on"; } ?>"><span class="the-icons icon-mail" style="color: #FFFFFF; font-size: 19px;"></span><?php if($totalemails > 0) { ?> <span class="numberCircle"><span class="height_fix"></span><span class="content favoritestotaltop"><?php print $totalemails; ?></span></span><?php } ?></a></li>

	
	<div class="clear"></div>
	</ul>
</div>





<div style="float: left; width: 25%;" class="nofloatsmall">

	<div style="padding:8px 32px 8px 0px;">
		<div>
		<div class="infoboxes">
			<div class="infoinner">
		<?php if(!empty($p['p_company'])) { ?>
		<?php print $p['p_company'];?><br>
		<?php } ?>
		<?php print $p['p_name']." ".$p['p_last_name'];?><br>

		<?php if(!empty($p['p_phone'])) { ?>
		<a href="tel:<?php print $p['p_phone'];?>"><?php print $p['p_phone'];?></a><br>
		<?php } ?>

		<?php if(!empty($p['p_address1'])) {  print $p['p_address1']; ?><br><?php } ?>
		<?php 
		print $p['p_city'];?><?php if(!empty($p['p_city'])) { print ", "; } ?> <?php print $p['p_state'];?> <?php print $p['p_zip'];?> <?php print $p['p_country'];?>
		<br>
		<a href="" onclick="pagewindowedit('w-send-email2.php?email_to=<?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $p['p_email']; } ?>&email_to_first_name=<?php print addslashes($p['p_name']);?>&email_to_last_name=<?php print addslashes($p['p_last_name']);?>&noclose=1&nofonts=1&nojs=1'); return false;" title="Send email" class="tip"><span class="the-icons icon-mail"></span>  <?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $p['p_email']; } ?></a>
		</div>
		</div>

		<div class="pc">
		Account created: <?php print $p['p_date'];?>
		</div>

		<div class="pc">
		Last Active: <a href="index.php?do=stats&action=recentVisitors&q=<?php print $p['p_last_active_ip'];?>" title="View stats for this IP address" class="tip"><?php print $p['p_last_active'];?></a>
		</div>

	<script>
	function updatepricelist() { 
		$("#savecp").hide();
		$("#savecploading").show();
		$("#savecpdone").hide();

		$.get("admin.actions.php?action=updateCustomerPriceList&p_price_list="+$("#p_price_list").val()+"&p_id="+$("#p_id").val()+"", function(data) {
			// $("#downloadfile").html(data);
			if($("#p_price_list").val() > 0) { 
				$("#cpl").show();
			} else { 
				$("#cpl").hide();
			}
			$("#savecp").show();
			$("#savecploading").hide();
			$("#savecpdone").show();

		});
		
	}

	function opencustompricelist() { 
		$("#custompricelist").slideToggle(200);
	}
	</script>


		<div class="pc"><span id="cpl" style="margin-right: 8px;" class="<?php if($p['p_price_list'] <= 0) { ?>hide<?php } ?>"><?php print ai_green;?> Enabled</span><a href="" onclick="opencustompricelist(); return false;">Custom Price List</a></div>
		<div id="custompricelist" class="hide pc">
			<div>Selecting a price list here will allow <?php print $p['p_name']." ".$p['last_name'];?> to purchase photos with this price list overriding any price list assigned to a gallery.</div>
			<div>
			<input type="hidden" name="do" value="people">
			<input type="hidden" name="p_id" id="p_id" value="<?php print $p['p_id'];?>">

			<select name="p_price_list" id="p_price_list">
			<option value="0">No Custom Price List</option>
			<?php $pls = whileSQL("ms_photo_products_lists", "*", "ORDER BY list_name ASC ");
			while($pl = mysqli_fetch_array($pls)) { ?>
			<option value="<?php print $pl['list_id'];?>" <?php if($p['p_price_list'] == $pl['list_id']) { print "selected"; } ?>><?php print $pl['list_name'];?></option>
			<?php } ?>
			</select>

			</div>
			<div>
			<span id="savecp"><a href="" onclick="updatepricelist(); return false;">Update</a></span>
			<span id="savecploading" class="hidden"><img src="graphics/loading2.gif"></span>
			<span id="savecpdone" class="hidden">Custom price list updated</span>
			</div>
		</div>
	</div>
	<div>&nbsp;</div>



<?php 
$notes = whileSQL("ms_notes", "*,date_format(note_date, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS note_date_show", "WHERE note_table='ms_people' AND note_table_id='".$p['p_id']."' ORDER BY note_date DESC LIMIT 3 ");
if(mysqli_num_rows($notes) > 0) { ?>
<div class="pc"><h3>Recent Notes / Activity</h3></div>
<?php } ?>
<?php 
while($note = mysqli_fetch_array($notes)) { ?>
<div class="underline"><?php if($note['note_is_note'] == "1") { ?><span class="the-icons icon-sticky-note" style="color: #F7D840;"></span><?php } ?><?php print $note['note_date_show'];?><br><?php print $note['note_note'];?></div>
<?php } ?>
<?php if(countIt("ms_notes","WHERE note_table='ms_people' AND note_table_id='".$p['p_id']."' ") > 3) { ?>
<div class="pc center"><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=notes">view all</a></div>
<?php } ?>
</div>
</div>


<div style="float: left; width: 75%;"  class="nofloatsmall">
<?php 
$c[1] = "25%";
$c[2] = "30%";
$c[3] = "20%";
$c[4] = "25%";
$c[5] = "10%";
?>
<script>
function selectSection(w) { 
	$(".peoplesection").fadeOut(100);
	$("#"+w).fadeIn(100);
	$(".gtab").removeClass("on");
	$("#tab"+w+" a").addClass("on");
}
</script>

<div id="roundedFormContain">
<?php 
if(empty($_REQUEST['view'])) { 
	include "people.orders.php";
}
if($_REQUEST['view'] == "orders") { 
	include "people.orders.php";
}
if($_REQUEST['view'] == "emaillog") { 
	include "people.email.log.php";
}
if($_REQUEST['view'] == "credits") { 
	include "people.credits.php";
}
if($_REQUEST['view'] == "contracts") { 
	include "people.contracts.php";
}
if($_REQUEST['view'] == "galleries") { 
	include "people.galleries.php";
}
if($_REQUEST['view'] == "favorites") { 
	include "people.favorites.php";
}

if($_REQUEST['view'] == "notes") { 
	include "people.notes.php";
}
if($_REQUEST['view'] == "invoice") { 
	include "people.invoice.php";
}

?>



	<div class="clear"></div>
	</div>
</div>
<div class="clear"></div>
