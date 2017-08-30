<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<div id="pageTitle"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?> Header & Footer</div> 
<?php if($setup['unbranded'] !== true) { ?><div class="pc"><a href="https://www.picturespro.com/sytist-manual/articles/making-sure-your-header-logo-fits-mobile-screens/" target="_blank">See this article on making sure your header / logo fits mobile screens</a>.</div><?php } ?>
<?php 
if($_REQUEST['action'] == "updateEditor") {
	updateSQL("ms_settings", "html_editor='".$_REQUEST['html_editor']."' ");
	$_SESSION['sm'] =  "Editor updated";
	session_write_close();
	header("location: index.php?do=look&view=header");
	exit();
}


if($_REQUEST['submitit'] == "yes") {
	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes($value));
	}
	$id = updateSQL("ms_settings", "header='".$_REQUEST['header']."', footer='".$_REQUEST['footer']."' , header_ext='".$_REQUEST['header_ext']."' , footer_ext='".$_REQUEST['footer_ext']."' , add_head='".$_REQUEST['dateembed']."', mobile_header_use='".$_REQUEST['mobile_header_use']."', mobile_header='".$_REQUEST['mobile_header']."', page_template='".$_REQUEST['page_template']."' ");   
	$_SESSION['sm'] =  "Changes Saved";
	session_write_close();
	header("location: index.php?do=look&view=header");
	exit();
} else {
	printForm($pay_total);
}
	?>

<?php 
function printForm($pay_total) {
	global $_REQUEST,$site_setup, $setup,$asettings;
	?>
	<div>&nbsp;</div>
	<script>
	function headersection(d) { 
		$(".headeroption").hide();
		$("#"+d).show();
		$(".gtab").removeClass("on");

		$("."+d+" a").addClass("on");
		
	}
	</script>
<div class="buttonsgray">
	<ul>

	<li class="headerfooter"><a href="" onClick="headersection('headerfooter'); return false;" class="<?php if(empty($_SESSION['new_logo_mobile'])) { ?>on<?php } ?> gtab">Header & Footer</a></li>
	<li class="mobileheader"><a href="" onClick="headersection('mobileheader'); return false;" class="<?php if(!empty($_SESSION['new_logo_mobile'])) { ?>on <?php } ?>gtab">Mobile Header</a></li>
	<li class="externalLinks"><a href="" onClick="headersection('externalLinks'); return false;" class="gtab">External Header & Footer Files</a></li>
	<li class="headcode"><a href="" onClick="headersection('headcode'); return false;" class="gtab">&lt;head> Code</a></li>
	<li class="footerscript"><a href="" onClick="headersection('footerscript'); return false;" class="gtab">&lt;head> Script</a></li>
	<div class="cssClear"></div>
	</ul>
</div>


<div id="roundedFormContain">

<div class="pc headeroption logoupload" id="logoUpload" style="display: none;">
<form name="upitprev" id="upitprev"  method="POST" action="index.php" enctype="multipart/form-data"  onSubmit="return checkForm('.logoreq','logoupload');">
<p>Click the browse button below to select your logo from your computer then click upload file. Once uploaded it will replace the content of your current header.</p>

<?php 
$line_count = 3;
?>
<div class="underline">
<div>
<input type="file" name="image" size="20" id="image"  class="logoreq"> 
<input type="hidden" name="do" value="look">
<input type="hidden" name="view" value="miscFiles">
<input type="hidden" name="logo" value="yes">
<input type="hidden" name="action" value="upload">
<input type="hidden" name="replace" value="yes">
<input type="hidden" name="overwrite" value="on">
<input type="submit" name="submission" id="logoupload"  value="Upload File" class="submitSmall">
</div>
</div>

<div class="specialMessage">You can upload jpg, png & gif files. A transparent png file usually works best. Be sure your logo isn't too large and is the size you want to use. A recommended height for your logo is no more than 150 pixels and width no more than 600 pixels but you should experiment with what you this looks best. </div>
</form>
<div>&nbsp;</div>
</div>




<div class="pc headeroption" id="logoUploadMobile" style="display: none;">
<form name="upitprevmobile" id="upitprevmobile"  method="POST" action="index.php" enctype="multipart/form-data"  onSubmit="return checkForm('.logomobilereq','logomobileupload');">
<p>Click the browse button below to select your logo from your computer then click upload file. Once uploaded it will replace the content of your current header.</p>

<?php 
$line_count = 3;
?>
<div class="underline">
<div id="upitprev_error" style="display: none; margin-bottom: 10px;">Error ....</div>
<div>
<input type="file" name="image" size="20" id="imagemobile"  class="logomobilereq"> 
<input type="hidden" name="do" value="look">
<input type="hidden" name="view" value="miscFiles">
<input type="hidden" name="logo" value="yes">
<input type="hidden" name="mobilelogo" value="yes">
<input type="hidden" name="action" value="upload">
<input type="hidden" name="replace" value="yes">
<input type="hidden" name="overwrite" value="on">
<input type="submit" name="submission" id="logomobileupload"  value="Upload File" class="submitSmall">
</div>
</div>

<div class="specialMessage">You can upload jpg, png & gif files. A transparent png file usually works best. <b>The width of your mobile logo should be around 220 pixels</b>. </div>
</form>
<div>&nbsp;</div>
</div>


<form method="post" name="theForm" action="index.php"  onSubmit="return checkForm('','submit');">
	<div id="externalLinks" class="headeroption" style="<?php if((!empty($site_setup['header_ext']))OR(!empty($site_setup['footer_ext']))==true) { print "display: block;"; } else { print "display: none;"; } ?>">
	<div class="pageContent"><h2>External links</h2></div>
	<div class="pageContent">You can alernativly use your own external files for your header & footer. Enter in the path to those files here. Example: /myheader.php or /folder/myheader.php. This will override the code in the header & footer content below.</div>
	<div class="pageContent">Header File: <input type="text" name="header_ext" value="<?php print $site_setup['header_ext'];?>" size="40"></div>
	<div class="pageContent">Footer File: <input type="text" name="footer_ext" value="<?php print $site_setup['footer_ext'];?>" size="40"></div>
	<div>&nbsp;</div>
</div>

<div id="headerfooter" class="headeroption <?php if(!empty($_SESSION['new_logo_mobile'])) { ?>hide<?php } ?>">
	<div class="pageContent">
		<div class="pageContent"><h2>Header</h2></div>
		<div class="pageContent">The header is shown at the top of all your pages. This is where you would add your logo or name. The background color in the header section is determined by your settings for the header in the Themes section.</div>
			<div>&nbsp;</div>
		<div class="pc"><a href="" onClick="showHide('logoUpload'); return false;" >Click here to simply upload a logo to add to your header</a>.</div>
		<div>&nbsp;</div>
		<?php if(!empty($_SESSION['new_logo'])) { ?>
		<div class="pc">Your new logo has been added to the header below. Click <b>save changes</b> below to save this.</div>
		<div>&nbsp;</div>
		<?php } ?>




		<?php if(empty($_SESSION['new_logo'])) { ?>
		<div class="pc">Or use the text editor below to manage your header.</div>
		<?php } ?>
		<textarea name="header" id="header" cols="40" rows="12" style="width: 98%;"><?php if(!empty($_SESSION['new_logo'])) { print htmlspecialchars(stripslashes($_SESSION['new_logo'])); unset($_SESSION['new_logo']); } else { print htmlspecialchars(stripslashes($site_setup['header'])); } ?></textarea>
		<?php if($site_setup['html_editor'] !=="1") { ?>
			<?php addEditor("header", "1", "300", "1"); ?>
		<?php } ?>
	</div>

	<div class="pageContent">
		<div class="pageContent"><h2>Footer</h2></div>

		<textarea name="footer" id="footer" cols="40" rows="6"  rows="12" style="width: 98%;"><?php print htmlspecialchars(stripslashes($site_setup['footer']));?></textarea>
		<?php if($site_setup['html_editor'] !=="1") { ?>
			<?php addEditor("footer", "2", "300", "0"); ?>
		<?php } ?>
		</div>

	<div class="pc">In the footer, you can add these bracket codes to display links<br>
	[MENU_LINKS] will show the main links from your main menu.<br>
	[SOCIAL_LINKS] will show the links from your <a href="index.php?do=look&view=social">Social Links</a> section.</div>
	<div>&nbsp;</div>
</div>



<div id="mobileheader" class=" headeroption <?php if(empty($_SESSION['new_logo_mobile'])) { ?>hide<?php } ?>">
	<div class="pageContent">
		<div class="pageContent"><h2>Mobile Header</h2></div>
		<div class="pageContent">The mobile header kicks in when the screen width goes below 800 pixels generally when viewing on a phone Entering in a small logo file or text is recommended. </div>
		<div>&nbsp;</div>
		<div class="pc"><a href="" onClick="showHide('logoUploadMobile'); return false;" >Click here to simply upload a logo to add to your mobile header</a>.</div>
		<div>&nbsp;</div>
		<?php if(!empty($_SESSION['new_logo_mobile'])) { ?>
		<div class="pc">Your new logo has been added to the mobile header below. Click <b>save changes</b> below to save this.</div>
		<div>&nbsp;</div>
		<?php } ?>
		<div class="pc"><input type="checkbox" name="mobile_header_use" id="mobile_header_use" value="1" <?php if($site_setup['mobile_header_use'] == "1") { print "checked"; } ?>> <label for="mobile_header_use">Enable Mobile Header</label></div>
		<div>&nbsp;</div>

		<textarea name="mobile_header" id="mobile_header" cols="40" rows="12" style="width: 500px;"><?php if(!empty($_SESSION['new_logo_mobile'])) { print htmlspecialchars(stripslashes($_SESSION['new_logo_mobile'])); unset($_SESSION['new_logo_mobile']); } else { print htmlspecialchars(stripslashes($site_setup['mobile_header'])); } ?></textarea>
		<?php if($site_setup['html_editor'] !=="1") { ?>
			<?php addEditor("mobile_header", "1", "300", "1"); ?>
		<?php } ?>
	</div>
</div>





<div class="pc headeroption hidden" id="headcode">
	<div class="pageContent"><h2>&lt;HEAD> Code</h2></div>
	<div class="pageContent">You can add additional code to place inside the head tags. You can use this to add additional meta tags or other things like javascript files.</h2></div>
	<textarea name="dateembed" id="dateembed" cols="40" rows="12"  style="width: 98%;"><?php print htmlspecialchars(stripslashes($site_setup['add_head']));?></textarea>
	</div>
<div>


<div class="pc headeroption hidden" id="footerscript">
	<div class="pageContent"><h2>&lt;HEAD>  Script</h2></div>
	<div class="pageContent">To add javascript to the head section of the page (like for Google Analytics), enter the script code here. <br><br><b>Do not enter the &lt;script> and &lt;/script> tags. Enter in the code between the tags.</b></h2></div>
	<textarea name="page_template" id="page_template" cols="40" rows="12"  style="width: 98%;"><?php print htmlspecialchars(stripslashes($site_setup['page_template']));?></textarea>
	</div>
<div>

	<div  class="bottomSave">
<input type="hidden" name="do" value="look">
 <input type="hidden" name="view" value="header">
 <input type="hidden" name="submitit" value="yes">
 <input type="submit" name="submit" id="submit" value="Save Changes" class="submit">
 </div>
</form>
</div>

<div>&nbsp;</div>
	<div class="pageContent" id="es" style="text-align: right;">
	<form name="typ" action="index.php" method="post">
	Select how to edit your header & footer.<select name="html_editor">
	<option value="0">Use the WYSIWYG Editor</option>
	<option value="1" <?php if($site_setup['html_editor'] == "1") { print "selected"; } ?>>Use a Plain Text Area</option>
	</select>
	<input type="hidden" name="do" value="look">
	<input type="hidden" name="view" value="header">
	<input type="hidden" name="action" value="updateEditor">
	<input type="submit" name="submit" value="Update" class="submitSmall">
	</form>
	<div>&nbsp;</div>
	</div>




<?php  } ?>
