<?php if($setup['demo_mode'] == true) { ?>
<div id="demomode">
	<div style="padding: 16px;">
		<div class="pc"><h3>Offer discounts with Coupons</h3></div>
		<div class="pc">You can create a variable discount (percentage or flat rate) based on order total with coupons your customers can redeem. </div>
	</div>
</div>
<div>&nbsp;</div>
<?php } ?>
<?php

if($_REQUEST['action'] == "couponsettings") { 
	updateSQL("ms_settings", "coupon_use_both='".$_REQUEST['coupon_use_both']."' ");
	$_SESSION['sm'] = "Coupon Settings Updated";
	session_write_close();
	header("location: index.php?do=discounts");
	exit();
}

if($_REQUEST['action'] == "editCoupon") { 
	include "coupon.edit.php";
} else { 
	include "coupons.list.php";
}
?>