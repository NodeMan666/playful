<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<div id="pageTitle"><a href="index.php?do=orders">Orders</a> <?php print ai_sep;?> Packing Slip </div>
<div class="pc">Here you can enter in what you want to show at the top and bottom of printable packing slips.</div>
<div>&nbsp;</div>


<?php  
if($_POST['update'] == "yes") {
	updateSQL("ms_store_settings", "packing_slip_top='".addslashes(stripslashes($_REQUEST['packing_slip_top']))."', packing_slip_bottom='".addslashes(stripslashes($_REQUEST['packing_slip_bottom']))."' ");
	$_SESSION['sm'] = "Updated";
	header("location: index.php?do=orders&action=packingslip");
	session_write_close();
	exit();


} else {
	slipForm();

}


function slipForm() {
	global $store;
	?>
<div id="roundedFormContain">
	<form name="login" method=POST action="index.php" style="margin: 0px; padding: 0px;">
	<div id="roundedForm">
	<div class="row">
		<div  style="width: 30%; float: left;">
			<div>Top - Your company name / contact</div>
			<div>
			<textarea name="packing_slip_top" id="packing_slip_top" rows="7" cols="30"><?php print htmlspecialchars($store['packing_slip_top']);?></textarea>
			</div>
		</div>
		<div style="width: 69%; float: right;">
			<div>Bottom</div>
			<div>
			<textarea name="packing_slip_bottom" id="packing_slip_bottom" rows="7" cols="30" class="field100" style="text-align: center;"><?php print htmlspecialchars($store['packing_slip_bottom']);?></textarea>
			</div>
		</div>
		<div class="clear"></div>
		</div>
	<input type="hidden" name="do" value="orders">
	<input type="hidden" name="action" value="packingslip">
	<input type="hidden" name="update" value="yes">
<div class="row" style="text-align: center;"><input  type="submit" name="submit" class="submit" value=" Save "></div>
	</div>
</form></div>



<?php  } ?>