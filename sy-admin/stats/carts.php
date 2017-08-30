<script>
function deletecart(ses) { 
	$.get("admin.actions.php?action=deletecart&cart_session="+ses+"", function(data) {
		$("#cart-"+ses).slideUp(200);
	});


}

</script>

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
	$orderby = "cart_id";
} else { 
	$orderby = $_REQUEST['orderby'];
}
if(empty($_REQUEST['pg'])) {
	$pg = "1";
} else {
	$pg = $_REQUEST['pg'];
}

$per_page = 20;
$NPvars = array("do=stats", "q=".$_REQUEST['q']."","orderby=".$orderby."", "acdc=".$acdc."", "view=carts" );
$sq_page = $pg * $per_page - $per_page;	

if($_REQUEST['action'] == "deletecarts") { 
	$thirty = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-$_REQUEST['when'], date("Y")));
	print "<li>".$thirty;
	$carts = whileSQL("ms_cart", "*,date_format(DATE_ADD(cart_date, INTERVAL ".$site_setup['time_diff']." HOUR), '%b %e %y - ".$site_setup['date_time_format']." ')  AS cart_date", "WHERE  cart_order<='0' AND cart_ip!='' AND cart_date<='".$thirty."' AND cart_date!='0000-00-00 00:00:00' ORDER BY cart_id DESC" );
	print "<li>".mysqli_num_rows($carts);
	while($cart = mysqli_fetch_array($carts)) { 
		 deleteSQL("ms_cart", "WHERE cart_id='".$cart['cart_id']."' ","1");
		print "<li>".$cart['cart_id']."- ".$cart['cart_date']." - ".$cart['cart_product_name']." - ".$cart['cart_order']." - ".$cart['cart_coupon']." ".$cart['cart_coupon_name'];
	}
	$_SESSION['sm'] = "Carts older than ".$_REQUEST['when']." have been deleted";
	header("location: index.php?do=stats&view=carts");
	session_write_close();
	exit();
}

$carts = whileSQL("ms_cart", "*,date_format(DATE_ADD(cart_date, INTERVAL ".$site_setup['time_diff']." HOUR), '%b %e - ".$site_setup['date_time_format']." ')  AS cart_date", "WHERE  cart_order<='0' AND cart_ip!='' GROUP BY cart_session ORDER BY $orderby $acdc " );
$total = mysqli_num_rows($carts);
?>

<div class="right textright">Delete carts older than <a href="index.php?do=stats&view=carts&action=deletecarts&when=7">7</a>,  <a href="index.php?do=stats&view=carts&action=deletecarts&when=14">14</a>,  <a href="index.php?do=stats&view=carts&action=deletecarts&when=30">30</a>,  or <a href="index.php?do=stats&view=carts&action=deletecarts&when=60">60</a> days</div>
<div id="pageTitle"><a href="index.php?do=stats">Stats</a> <?php print ai_sep;?> Shopping Carts</div>
<div class="clear"></div>
<div id="">
<?php
$carts = whileSQL("ms_cart", "*,date_format(DATE_ADD(cart_date, INTERVAL ".$site_setup['time_diff']." HOUR), '%b %e - ".$site_setup['date_time_format']." ')  AS cart_date", "WHERE  cart_order<='0' AND cart_ip!='' GROUP BY cart_session ORDER BY $orderby $acdc LIMIT $sq_page,$per_page" );
if(mysqli_num_rows($carts)<=0) { ?>
	<div id="underline" style="text-align: center;">No active shopping carts</div>
<?php }
while($cart = mysqli_fetch_array($carts)) {  
	?>
<div class="underline" id="cart-<?php print $cart['cart_session'];?>">
	<div class="p20 left"><?php $stotal = homeShoppingCartTotal($cart['cart_client'],$cart['cart_session']); 
	?>
	<a href="" onclick="viewcustomercart('<?php print $cart['cart_client'];?>','<?php print $cart['cart_session'];?>'); return false;"><?php print showPrice($stotal['show_cart_total']);?></a>
	</div>
	<div class="p30 left">
	<?php if(!empty($cart['cart_client'])) { 
		$p = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$cart['cart_client']."' ");
		if(!empty($p['p_email'])) { 
		?><a href="" onclick="pagewindowedit('w-send-email2.php?email_to=<?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $p['p_email']; } ?>&email_to_first_name=<?php print addslashes($p['p_name']);?>&email_to_last_name=<?php print addslashes($p['p_last_name']);?>&noclose=1&nofonts=1&nojs=1'); return false;" title="Send email" class="tip"><span class="the-icons icon-mail"></span></a>   
	<?php 
		}
		print "<a href=\"index.php?do=people&p_id=".$p['p_id']."\" title=\"View Account\" class=\"tip\">"; if((empty($p['p_name'])) && (empty($p['p_last_name'])) == true) { print $p['p_email']; } else { print $p['p_name']." ".$p['p_last_name']; } print "</a> &nbsp;";
	} else { 
		?>
		<a href="index.php?do=stats&action=recentVisitors&q=<?php print "".$cart['cart_ip'];?>"><?php print $cart['cart_ip'];?></a><?php if(!empty($cart['cart_email'])) { ?> <a href="" onclick="pagewindowedit('w-send-email2.php?email_to=<?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $cart['cart_email']; } ?>&noclose=1&nofonts=1&nojs=1'); return false;" title="Send email" class="tip"><span class="the-icons icon-mail"></span></a><span title="Collected to view gallery" class="tip"><?php print $cart['cart_email'];?></span><?php } ?>
		<?php } ?>
		</div>
	<div class="p30 left textright"><?php print "".$cart['cart_date']."";?></div>
	<div class="p20 left textright"><a href="" onclick="deletecart('<?php print $cart['cart_session'];?>'); return false;" class="tip" title="<nobr>Delete this  cart</nobr>">delete</a></div>
	<div class="cssClear"></div>
 </div>
<?php } ?>
</div>
<div>&nbsp;</div>
<div class="pc center"><center><?php print nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $req);?></center></div> 
