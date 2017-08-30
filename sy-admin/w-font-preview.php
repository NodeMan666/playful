<?php require "w-header.php"; ?>

<?php $fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$_REQUEST['css_id']."' ORDER BY font ASC ");
	while($font = mysqli_fetch_array($fonts)) { 
		if($f > 0) { 
			$add_fonts .= "|";
		}
		$add_fonts .= str_replace(" ","+",$font['font']);
		$f++;
	}
?>
<link href='//fonts.googleapis.com/css?family=<?php if(!empty($_REQUEST['font'])) { print $_REQUEST['font']."|"; } ?><?php print $add_fonts;?>' rel='stylesheet' type='text/css'>
<?php 
$font_use = explode(":",$_REQUEST['font']);
?>
<style>
.fontpreview { font-family: <?php print $font_use[0];?>; color: #000000; } 
.fontpreview h1 { font-size: 27px; } 
</style>
<div class="pc"><h1>Google Web Fonts</h1></div>
<div style="width: 49%; float: left;">
<div class="pc">
<form name="f" action="w-font-preview.php" method="get">
<select name="font" id="font" onchange="this.form.submit();">
<option value="">Select a font to preview</option>
<?php
$fonts = explode("\r\n",$site_setup['google_fonts']);
foreach($fonts AS $font) { 
	if(!empty($font)) { ?>
	<option value="<?php print $font;?>" <?php if($_REQUEST['font'] == $font) { print "selected"; } ?>><?php print $font;?></option>
<?php }
	} ?>
</select>
<input type="hidden" name="changesmade" id="changesmade" value="">
<input type="hidden" name="css_id" value="<?php print $_REQUEST['css_id'];?>">
</form>
</div>

<div class="pc">Google offers fonts you can use on your website for free. There are hundreds of fonts to choose from.  Select fonts from the drop down menu to preview. It may be easier to preview the fonts on <a href="http://www.google.com/webfonts" target="_blank">google.com/webfonts</a>.</div>

<?php if(!empty($_REQUEST['font'])) { ?>
<div class="fontpreview">
<div>&nbsp;</div>
<div class="pc">&rarr;<a href="" onClick="addFont('<?php print $_REQUEST['font']; ?>', '<?php print $_REQUEST['css_id'];?>'); return false;" style="font-size: 17px; text-decoration: underline;">Add this font</a>&larr;</div>
<div>&nbsp;</div><div class="pc" style="font-size: 32px;">Font Preview of <?php print $_REQUEST['font'];?></div>
<div class="pc" style="font-size: 17px;">The cow jumped over the moon.</div>
<div class="pc">Google offers fonts you can use on your website for free. There are hundreds of fonts to choose from.  Select fonts from the drop down menu to preview. It may be easier to preview the fonts on <a href="http://www.google.com/webfonts" target="_blank">google.com/webfonts</a>.</div>
<div>&nbsp;</div>
<div class="pc">ABCDEFGHIJKLMNOPQRSTUVWXY</div>
<div class="pc">abcdefghijklmnopqrstuvwxy</div>
<div class="pc">0123456789 #@$%&-</div>
<div class="pc" style="font-weight: bold;">ABCDEFGHIJKLMNOPQRSTUVWXY</div>
<div class="pc" style="font-weight: bold;">abcdefghijklmnopqrstuvwxy</div>
<div class="pc" style="font-weight: bold;">0123456789 #@$%&-</div>
<div class="pc" style="font-size: 32px;">THIS IS A TITLE  AT 32PX</div>
<div class="pc" style="font-size: 32px;">This Is A Title at 32px</div>
<div class="pc" style="font-size: 21px;">The cow jumped over the moon.</div>

</div>
<?php } ?>
</div>
<div style="width: 49%; float: right;">
<div id="savefontchanges">
<div class="pc">After you add or remove fonts, you need to save and reload the theme in the theme editor.</div>

<div class="pc buttons" style="margin-top: 8px;"><a href="#" onclick="window.parent.$('#themeeditor').submit();" class="savechanges" id="savechanges">Save & Reload Theme</a></div>
</div>
<div>&nbsp;</div>
<div class="pc"><h2>Selected Google Fonts</h2></div>
<div class="pc">It is best not to add more than 4-5 different fonts to your list because it can cause the pages to load slower.</div>
	<div id="googlefonts">
		<?php listFonts(); ?>
	</div>
</div>
<div class="clear"></div>
<div>&nbsp;</div>


<?php require "w-footer.php"; ?>
