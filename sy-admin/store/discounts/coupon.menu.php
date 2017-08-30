<ul class="sidemenus">

<li <?php if(empty($_REQUEST['status'])) { print "class=\"on\""; } ?>><a href="?do=discounts">Available Coupons (<?php print countIt("ms_promo_codes", "WHERE code_id>'0' AND code_date_id<='0' AND code_use_status='0' AND (code_end_date='0000-00-00' OR code_end_date>=NOW()) ");?>)</a></li>

<li <?php if($_REQUEST['status']== "used") { print "class=\"on\""; } ?>><a href="?do=discounts&status=used">Used Coupons (<?php print countIt("ms_promo_codes", "WHERE code_id>'0'  AND code_date_id<='0' AND code_use_status='1'");?>)</a></li>

<li <?php if($_REQUEST['status']== "expired") { print "class=\"on\""; } ?>><a href="?do=discounts&status=expired">Expired Coupons (<?php print countIt("ms_promo_codes", "WHERE code_id>'0'  AND code_date_id<='0' AND code_use_status='0' AND (code_end_date!='0000-00-00' AND code_end_date<NOW()) ");?>)</a></li>
</ul>


<div style="margin: 16px;">
<form method="post" name="fdea" action="index.php">
<p>
<b>Use both types of coupons on the same order?</b>
</p>
<p>
If you only want 1 type of coupon to be used on an order, 1 bonus or 1 standard, select no. If you want someone to be able to use 1 of each type of coupon, select yes.
</p>
<p class="center">
<input type="radio" name="coupon_use_both" id="coupon_use_both0" value="0" <?php if($site_setup['coupon_use_both'] == "0") { print "checked"; } ?>> <label for="coupon_use_both0">No</label> &nbsp;
<input type="radio" name="coupon_use_both" id="coupon_use_both1" value="1" <?php if($site_setup['coupon_use_both'] == "1") { print "checked"; } ?>> <label for="coupon_use_both1">Yes</label>
</p>
<p class="center">
<input type="hidden" name="do" id="do" value="discounts">
<input type="hidden" name="action" id="action" value="couponsettings">
<input type="submit" name="submit" value="Update" class="submit">
</p></form>
</div>

<div>&nbsp;</div>
<div style="margin: 16px;">
<p><b>Direct link to redeem coupon</b></p>
<p>If you want to make a link on a page that includes the coupon code, make the link: </p>
<p><input type="text" name="redeemexample" class="field100" value="javascript:redeemcoupon('','couponcode');"></p>
<p>Full link would look like this:</p>
<p><input type="text" name="redeemexample" class="field100" value="&lt;a href=&quot;javascript:redeemcoupon('','couponcode');&quot;>Redeem Coupon&lt;/a>"></p>
<p>Replace couponcode with the redeem code for the coupon.</p>
</div>