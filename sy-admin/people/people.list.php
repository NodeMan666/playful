

<div id="pageTitle"><a href="index.php?do=people">People</a>
<?php
if(!empty($_REQUEST['q'])) {
	$and_where .= "AND ( p_email LIKE '%".addslashes($_REQUEST['q'])."%'  OR   p_name LIKE '%".addslashes($_REQUEST['q'])."%'  OR   p_company LIKE '%".addslashes($_REQUEST['q'])."%'  OR  p_last_name LIKE '%".addslashes($_REQUEST['q'])."%'  OR   p_city LIKE '%".addslashes($_REQUEST['q'])."%'   OR   p_state LIKE '%".addslashes($_REQUEST['q'])."%'  OR   p_address1 LIKE '%".addslashes($_REQUEST['q'])."%' )";
	print " > search for ".$_REQUEST['q']."";
}

if($_REQUEST['orderby'] == "p_company") { 
	$and_where .= " AND p_company!='' ";
}
if(!empty($_REQUEST['p_state'])) { 
	$and_where .= "AND p_state='".$_REQUEST['p_state']."' ";
}
if(!empty($_REQUEST['starts'])) { 
	$and_where .= "AND (p_last_name LIKE '".$_REQUEST['starts']."%' OR p_name LIKE '".$_REQUEST['starts']."%')  ";
}
?>
</div>
<div class="pc">You can also allow people to create accounts / log in with their Facebook account. This can be enabled in <a href="index.php?do=settings&action=fb">Settings -> Share & Facebook</a></div>

<div class="pc center" style="margin: 8px 0px;">
<?php $ab = "A B C D E F G H I J K L M N O P Q R S T U V W X Y Z";
$abs = explode(" ",$ab);
foreach($abs AS $a) { 

	if(countIt("ms_people", "WHERE (p_last_name LIKE '".$a."%' OR p_name LIKE '".$a."%') ") > 0) { ?>
	<a href="index.php?do=people&starts=<?php print $a;?>" class="tip <?php if($_REQUEST['starts'] == $a) { ?>bold<?php } ?>"  title="First or last name starts with <?php print $a;?>"><?php print $a;?></a> 
	<?php 	} else { ?>
	<span class="muted"><?php print $a;?></span>
	<?php  }

}
?>
</div>
<div class="clear"></div>
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
	$orderby = "p_id";
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
$NPvars = array("do=people", "q=".$_REQUEST['q']."","orderby=".$orderby."", "acdc=".$acdc."", "starts=".$_REQUEST['starts']."");
$sq_page = $pg * $per_page - $per_page;	

$total = countIt("ms_people",  "WHERE p_id>'0' $and_where "); 
$psc = countIt("ms_people",  "WHERE p_id>'0' AND p_company!=''  "); 

if($psc > 0) { 
	$c[1] = "20%";
} else { 
	$c[1] = "40%";
}
$c[2] = "20%";
$c[4] = "15%";
$c[5] = "15%";
$c[6] = "10%";
if($psc > 0) { 
	$c[7] = "20%";
} else { 
	$c[7] = "0%";
}
?>
<?php 
	$show_text = false;
	if($total > $per_page) {
		print "<div class=\"right textright\">".nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $_REQUEST)."</div>"; 
		?>
<div class="clear"></div>
<div>&nbsp;</div>
<?php }
	$show_text = true;
?>

<div class="underlinecolumn">
	<?php if($psc > 0) { ?>
	<div class="left" style="width: <?php print $c[7];?>"><a href="index.php?do=people&q=<?php print $_REQUEST['q'];?>&orderby=p_company&acdc=<?php if($orderby == "p_company") { print $oposit; } else { print "ASC"; } ?>">Company</a></div>
	<?php } ?>
	<div class="left" style="width: <?php print $c[1];?>"><a href="index.php?do=people&q=<?php print $_REQUEST['q'];?>&orderby=p_last_name&acdc=<?php if($orderby == "p_last_name") { print $oposit; } else { print "ASC"; } ?>">Name</a></div>
	<div class="left" style="width: <?php print $c[2];?>"><a href="index.php?do=people&q=<?php print $_REQUEST['q'];?>&orderby=p_email&acdc=<?php if($orderby == "p_email") { print $oposit; } else { print "ASC"; } ?>">Email</a></div>
	<div class="left" style="width: <?php print $c[4];?>"><a href="index.php?do=people&q=<?php print $_REQUEST['q'];?>&orderby=p_date&acdc=<?php if($orderby == "p_date") { print $oposit; } else { print "ASC"; } ?>">Registered</a></div>
	<div class="left" style="width: <?php print $c[5];?>"><a href="index.php?do=people&q=<?php print $_REQUEST['q'];?>&orderby=p_last_active&acdc=<?php if($orderby == "p_last_active") { print $oposit; } else { print "DESC"; } ?>">Last Active</a></div>
	<div class="right textright" style="width: <?php print $c[6];?>">Sales</div>
	<div class="clear"></div>
</div>
<?php 


$ps = whileSQL("ms_people", "*,date_format(DATE_ADD(p_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS p_date_show, date_format(DATE_ADD(p_last_active, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS p_last_active_show", "WHERE p_id>'0' $and_where ORDER BY $orderby $acdc LIMIT $sq_page,$per_page "); 
if(mysqli_num_rows($ps) <=0) { ?><div class="pc center">
<?php if(!empty($_REQUEST['q'])) { ?><h3>No results for your search</h3><?php } else { ?>
<h3>You have no registered people</h3>
<?php } ?>
</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<?php } ?>
<?php 
while($p = mysqli_fetch_array($ps)) { 
	$order = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE order_customer='".$p['p_id']."' AND order_status<'2' ");
	if($setup['demo_mode'] == true) { 
		$p['p_name'] = get_starred($p['p_name']);
		$p['p_last_name'] = get_starred($p['p_last_name']);
	}
	?>
<div class="underline">
	<?php if($psc > 0) { ?>

	<div class="left" style="width: <?php print $c[7];?>"><h3><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>"><?php if(!empty($p['p_company'])) { print $p['p_company']; } else { print "&nbsp; "; } ?></div>
	<?php } ?>
	<div class="left" style="width: <?php print $c[1];?>">
	<div><h3>


	<?php if((!empty($p['p_fb_id'])) && ($setup['demo_mode'] == true) == true) { ?><span class="the-icons icon-facebook"  title="Logged in via Facebook"></span><?php } ?>
	<?php if($setup['demo_mode'] !== true) { ?><?php if(!empty($p['p_fb_link'])) { ?><a href="<?php print $p['p_fb_link'];?>" target="_blank" class="the-icons icon-facebook" title="Logged in via Facebook"></a><?php } ?><?php } ?> <?php if((!empty($p['p_fb_id'])) && (empty($p['p_fb_link'])) == true) { ?><span class="the-icons icon-facebook"  title="Logged in via Facebook"></span><?php } ?><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>">
	<?php if((empty($p['p_last_name'])) && (empty($p['p_name'])) == true) { print "No Name Supplied"; } else {  if(!empty($p['p_last_name'])) { print $p['p_last_name'].", "; } print $p['p_name']; } ?></a></h3></div>
	<div>
		<?php
	if($booksettings['do_not_show_on_people_list'] !== "1") { 
		$books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m %e ')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE book_account='".$p['p_id']."' AND (book_date>='".date('Y-m-d')."' OR book_recurring_y='1')  AND book_confirmed='2' GROUP BY book_id    ORDER BY book_date_order ASC , book_time ASC ");
		$tb = mysqli_num_rows($books);
		?>
		<a href="" onclick="viewday('0','0','0','','<?php print $p['p_id'];?>','<?php print $p['p_id'];?>'); return false;" title="Calendar"><span class="the-icons icon-calendar"></span><?php if($tb > 0) { ?><span class="numberCircle"><span class="height_fix"></span><span class="content"><?php print $tb;?></span><?php } ?></a>
	<?php } ?>
		<?php 
		$notes = countIt("ms_notes",  "WHERE note_table='ms_people' AND note_table_id='".$p['p_id']."' AND note_is_note='1' ");
		?>
		<a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=notes" title="Notes"><span class="the-icons icon-sticky-note" style="color: #F7D840;"></span><?php if($notes > 0) { ?><span class="numberCircle"><span class="height_fix"></span><span class="content"><?php print $notes;?></span><?php } ?></a>

<?php if(countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "WHERE fav_person='".$p['p_id']."' AND ms_photos.pic_id>'0'  ") > 0) { ?>
		<a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=favorites" title="Favorites"><span class="the-icons icon-heart" style="color: #c44444;"></span><span class="numberCircle"><span class="height_fix"></span><span class="content"><?php print countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id  LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "WHERE fav_person='".$p['p_id']."'  AND ms_photos.pic_id>'0'  ");?></span></span>

	<?php } ?>

	<?php $stotal = homeShoppingCartTotal(MD5($p['p_id']),0);
	if($stotal['total_items'] > 0) { ?>

	<a href="" onclick="viewcustomercart('<?php print MD5($p['p_id']);?>','0'); return false;" title="<?php print showPrice($stotal['show_cart_total']);?>"><span  class="the-icons icon-basket"></span><span class="numberCircle"><span class="height_fix"></span><span class="content"><?php print $stotal['total_items'] ;?></span></a>
	<?php } ?>

	</div>

	</div>
	<div class="left" style="width: <?php print $c[2];?>">
	<div><?php if(empty($p['p_email'])) { ?>not provided <div class="moreinfo" info-data="noaccountemail"><div class="info"></div></div><?php  } else { ?><a href="" onclick="pagewindowedit('w-send-email2.php?email_to=<?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $p['p_email']; } ?>&email_to_first_name=<?php print addslashes($p['p_name']);?>&email_to_last_name=<?php print addslashes($p['p_last_name']);?>&noclose=1&nofonts=1&nojs=1'); return false;" title="Send email" class="tip"><span class="the-icons icon-mail"></span></a>  <?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $p['p_email']; } ?><?php } ?></div>
	<div><?php if(!empty($_REQUEST['p_state'])) { ?><?php print $p['p_city'];?><br><?php } ?><?php print $p['p_state'];?> <?php print $p['p_country'];?>&nbsp;</div>
	</div>
	<div class="left" style="width: <?php print $c[4];?>"><?php print $p['p_date_show'];?></div>
	<div class="left" style="width: <?php print $c[5];?>"><?php print $p['p_last_active_show'];?></div>
	<div class="right textright" style="width: <?php print $c[6];?>">
	<div><?php if(countIt("ms_orders", "WHERE order_customer='".$p['p_id']."'  AND order_status<'2'") > 0) { ?>
	<a href="index.php?do=orders&order_customer=<?php print $p['p_id'];?>">(<?php print countIt("ms_orders", "WHERE order_customer='".$p['p_id']."' ");?>) <?php print showPrice($order['tot']);?><?php } else { print "&nbsp;"; } ?></a></div>

	<?php 
	if($setup['no_expired_print_credits'] == true) { 
		$ctotal = doSQL("ms_credits", "SUM(credit_amount) AS tot", "WHERE credit_customer='".$p['p_id']."'  "); 
	} else { 
		$ctotal = doSQL("ms_credits", "SUM(credit_amount) AS tot", "WHERE credit_customer='".$p['p_id']."'  AND (credit_expire='0000-00-00' OR credit_expire>='".date('Y-m-d')."' )  "); 
	}
	
	if($ctotal['tot'] > 0) { ?>
	<div><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=credits"><?php print showPrice($ctotal['tot']);?> credit</a></div>
	<?php } ?>
	</div>

	<div class="clear"></div>
</div>

<?php } ?>
<div>&nbsp;<div>
<?php if($total > $per_page) {?>

<div class="pc center"><center><?php print nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $req);?></center></div> 
<div>&nbsp;<div>
<?php 
foreach($_GET AS $gn => $gr) { 
	if($gn !== "per_page") { 
		$strv .= "&".$gn."=".$gr;	
	}
}
?>
<div class="pc center">
	Show per page: <?php if($history['per_page'] !== "20") { print "<a href=\"index.php?per_page=20".$strv."\" class=\"np\">20</a>"; } else { print "<span class=\"np\">20</span>"; } ?>
	<?php if($history['per_page'] !== "50") { print "<a href=\"index.php?per_page=50".$strv."\" class=\"np\">50</a>"; } else { print "<span class=\"np\">50</span>"; } ?>
	<?php if($history['per_page'] !== "100") { print "<a href=\"index.php?per_page=100".$strv."\" class=\"np\">100</a>"; } else { print "<span class=\"np\">100</span>"; } ?>
</div>
<?php } ?>
<div>&nbsp;<div>
