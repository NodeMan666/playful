<?php
$lang = doSQL("ms_gift_certificate_language", "*", " ");
if($_REQUEST['action'] == "saveamounts") { 
	$_REQUEST['other_amount'] = trim($_REQUEST['other_amount']);
	$_POST['amounts'] = trim($_POST['amounts']);

	updateSQL("ms_gift_certificate_language", "amounts='".addslashes(stripslashes($_POST['amounts']))."', other_amount='".$_REQUEST['other_amount']."', show_send_date='".$_REQUEST['show_send_date']."' ");
	$_SESSION['sm'] = "Settings Saved";
	session_write_close();
	header("location: index.php?do=people&view=giftcertificates&sub=amounts");
	exit();
}

?>
<div class="right textright"><a href="https://www.picturespro.com/sytist-manual/people/egift-cards/" target="_blank" class="the icons icon-info-circled">eGift Cards in Manual</a></div>
<div class="pc"><a href="index.php?do=people">&larr; People</a></div>
<div class="pc newtitles"><span>eGift Amounts</span></div> 
<div class="buttonsgray">
<ul>
	<li><a href="index.php?do=people&view=giftcertificates" <?php if(empty($_REQUEST['sub'])) { ?>class="on"<?php } ?>>eGIFT CARDS</a></li>
	<li><a href="index.php?do=people&view=giftcertificates&sub=settings">eCARD STYLE</a></li>
	<li><a href="index.php?do=people&view=giftcertificates&sub=language">LANGUAGE</a></li>
	<li><a href="index.php?do=people&view=giftcertificates&sub=amounts" class="on">AMOUNTS & SETTINGS</a></li>
</ul>
</div>
<div class="clear"></div>
<div class="pc">Here you can set the amounts to select from when someone is purchasing an eGift Card. </div>

<form method="post" name="gc" id="gc" action="index.php">

<div style="max-width: 800px; margin: auto;">
	<div style="padding: 24px;">

		<div >
		<div class="underlinelabel">Available Amounts</div>
		<div class="pc">Enter the amounts you want to be selected from without the currency sign separated with a comma.</div>
		<div class="pc"><input type="text"  id="amounts" name="amounts" class="field100 inputtitle" value="<?php print $lang['amounts'];?>"></div>
		<div class="pc"> Example: 10,20,50 etc .... </div>
		</div>
		<div>&nbsp;</div>
		<div class="pc">

		</div>
		<div class="underlinelabel"><input type="checkbox" name="other_amount" id="other_amount" value="1" <?php if($lang['other_amount'] == "1") { print "checked"; } ?>> <label for="other_amount">Add other amount option for customers to enter in their own amount</label>
		</div>
		<div class="underlinelabel"><input type="checkbox" name="show_send_date" id="show_send_date" value="1" <?php if($lang['show_send_date'] == "1") { print "checked"; } ?>> <label for="show_send_date">Show send date</label>
		</div>
		<div class="underlinespacer">Selecting "Show send date" will allow the person purchasing to select a date for the eGift Card to be emailed. Not selected, the eGift Card is sent when purchased.<br><br>If you have <a href="index.php?do=settings&action=cron">automated emails</a> enabled, then it will automatically send the eGift Card email on the date selected. If you do not have automated emails enabled, then you will have to send the email on that date. You will have a notice on your admin home screen when eGift Cards are due to send.</div>


		<div class="pc">
		<input type="hidden" name="do" value="people">
		<input type="hidden" name="view" value="giftcertificates">
		<input type="hidden" name="sub" value="amounts">
		<input type="hidden" name="action" value="saveamounts">
		<input type="submit" name="submit" value="Save" class="submit">

		</div>
	</div>
</div>
</form>

<div class="clear"></div>


<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
