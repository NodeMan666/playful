<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }

?>

<div id="pageTitle"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?>  Billboards</div> 
<?php isFullScreenLarge();?> 
<script>
function createbill() { 
	$("#newbill").slideToggle(200);
}
function newcatbill() { 
	$("#newcatbill").slideToggle(200);
}

</script>



<div style="float: left; width: 25%;">
	<div style="padding-right: 16px;">
		<div>&nbsp;</div>


<div >
		<div class="pc buttons"><a href="" onClick="createbill(); return false;">Create New Billboard</a></div>
		<div class="" style="display: none; margin-top: 8px;" id="newbill">
		<div class="pc">Select from below</div>
		<div class="pc"><a href="index.php?do=look&action=billboardSlideshow">I will upload slides</a></div>
		<div class="pc"><a href="" onclick="newcatbill(); return false;">Feature most recent pages from a section</a></div>
		</div>
		<div id="newcatbill" style="display: none;">
		<form method="get" name="newbill" action="index.php">

				<div class="pc">Select a section to feature pages from.</div>
				<div class="pc">
				<select name="bill_cat">
				<?php
				$sections = whileSQL("ms_blog_categories", "*", "WHERE cat_under='0' ORDER BY cat_name ASC ");
				while($section = mysqli_fetch_array($sections)) { ?>
				<option value="<?php print $section['cat_id'];?>" <?php if($_REQUEST['date_feature_cat'] == $section['cat_id']) { print "selected"; } ?>><?php print $section['cat_name'];?></option>
				<?php } ?>
				</select>
				</div>
				<div class="pc">
				<input type="hidden" name="do" value="look">
				<input type="hidden" name="subdo" value="newbillcat">
				<input type="hidden" name="action" value="billboardSlideshow">
				<input type="submit" name="submit" value="Continue" class="submit">
				</div>

		</form>
		</div>



		</div>
<div class="clear"></div>
		<div>&nbsp;</div>

	<div class="pageContent">
		Billboards are photos you can show as a slideshow that can be placed either between the top menu and the page content of your website, or at the top of the page content. These can be places in sections, categories, or on pages. You can add slides, add text to the slides, and also link to other pages.
		<br><br>
		<h3>Adding a billboard to a page or section</h3>
		To add a billboard to a section, click the edit link under the category name from the <a href="index.php?do=news">site content section</a> in the left menu and look for the Billboard option.
		<br><br>
		To add a billboard to a page, edit the page and look for the billboard option.
		</div>
		<div>&nbsp;</div>
	</div>
</div>


<div style="float: right; width: 75%;">
	<div id="">
	<?php
	$boards = whileSQL("ms_billboards","*", "ORDER BY bill_name ASC ");
	if(mysqli_num_rows($boards) <=0) { 
		print "<div class=\"row\">No billboards created</div>";
	}

	while($board = mysqli_fetch_array($boards)) { ?>
		<div class="underline">
		<div style="width: 60%; float: left;">
		<div><h3><a href="index.php?do=look&action=billboardSlideshow&bill_id=<?php print $board['bill_id'];?>"><?php print ai_edit;?> <?php print $board['bill_name'];?></a></h3></div>
		</div>

		<div style="width: 40%; float: left;">

		<div>
		<?php if($board['bill_cat'] > 0) { ?>
		Showing <?php print $board['bill_limit'];?> most recent pages
		<?php } else if($board['bill_page'] == "1") { ?>
		This is the layout of you choose to show a masthead on pages in a section. To enable mastheads on pages, edit your section you wish to display them in and select that option.
		<?php } else { ?>
		<?php print countIt("ms_billboard_slides", "WHERE slide_billboard='".$board['bill_id']."' ");?> Slides
		<?php } ?>
		</div>
		</div>

	<div class="cssClear"></div>
	</div>
	<div>&nbsp;</div>
	<?php } ?>

	</div>
</div>
<div class="clear"></div>