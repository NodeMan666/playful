<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }

if(!empty($_REQUEST['deleteBillboard'])) {

	$slides = whileSQL("ms_billboard_slides", "*", "WHERE slide_billboard='".$_REQUEST['deleteBillboard']."' ");
	while($slide = mysqli_fetch_array($slide)) { 
		$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$slide['slide_pic']."' ");
		deleteSQL2("ms_billboard_slides", "WHERE slide_id='".$slide['slide_id']."' ");
	}




	deleteSQL("ms_billboards", "WHERE bill_id='".$_REQUEST['deleteBillboard']."' ", "1");
	updateSQL("ms_calendar", "page_billboard='0' WHERE page_billboard='".$_REQUEST['deleteBillboard']."' ");
	$_SESSION['sm'] = "Billboard deleted";
	session_write_close();
	header("location: index.php?do=look&action=billboardsList");
	exit();
}

if($_REQUEST['subdo'] == "deleteSlide") { 
	$slide = doSQL("ms_billboard_slides", "*", "WHERE slide_id='".$_REQUEST['slide_id']."' ");
	$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$slide['slide_pic']."' ");
	deleteSQL2("ms_billboard_slides", "WHERE slide_id='".$slide['slide_id']."' ");
	session_write_close();
	header("location: index.php?do=look&action=billboardSlideshow&bill_id=".$_REQUEST['bill_id']."");
	exit();

}

if($_REQUEST['subdo'] == "newbillcat") { 
	if($_REQUEST['bill_cat'] !== "999999999") { 
		$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['bill_cat']."' ");
		$cat_name = "Newest pages in \"".$cat['cat_name']."\" ";
	} else { 
		$cat_name = "New pages from all section";
	}

	$id = insertSQL("ms_billboards", "
	bill_name='".addslashes(stripslashes($cat_name))."', bill_slideshow='1',  bill_width='1024', bill_height='500', bill_seconds='5', bill_trans_time='400' , bill_loop='0', bill_transition='neatbbfade', bill_show_nav='1', bill_pic='".$_REQUEST['bill_pic']."', bill_fixed='1', bill_placement='insidecontainer',
	bill_border_color='242424',
	bill_border_size='1',
	bill_padding='0',
	bill_nav_background='FFFFFF',
	bill_nav_color='000000',
	bill_nav_border='FFFFFF',
	bill_limit='10',
	bill_cat='".$_REQUEST['bill_cat']."'	");

	$sql = "INSERT INTO `ms_billboard_slides` ( `slide_pic`, `slide_order`, `slide_link`, `slide_billboard`, `slide_text1`, `slide_text2`, `slide_text_1_color`, `slide_text_1_size`, `slide_text_1_shadow`, `slide_text_1_font`, `slide_text_1_effect`, `slide_text_1_time`, `slide_text_2_color`, `slide_text_2_size`, `slide_text_2_shadow`, `slide_text_2_font`, `slide_text_2_effect`, `slide_text_2_time`, `slide_text_align`, `slide_top_margin`, `slide_left_margin`) VALUES( 7569, 2, '', '$id', 'id::1||color::FFFFFF||text::<p>[TITLE]<br></p>||font-family::Bitter||font-size::60||font-weight::normal||font-style::normal||text-shadow-h::1||text-shadow-v::1||text-shadow-b::2||text-shadow-c::000000||slide_text_1_time::600||slide_text_1_effect::fadeIn||x::2.7858225803594703||y::57.08002727665898||type::text|:|id::2||color::FFFFFF||text::<p>[TEXT]<br></p>||font-family::Wire One||font-size::35||font-weight::normal||font-style::normal||text-shadow-h::1||text-shadow-v::1||text-shadow-b::0||text-shadow-c::000000||slide_text_1_time::500||slide_text_1_effect::fadeIn||x::3.0038722501179973||y::73.7189299137119||type::text|:|', '', 'DDFF80', 61, '3px 2px 6px #000000', 'Advent Pro', 'fadeIn', 600, 'FFFFFF', 41, '1px 2px 3px #000000', 'BenchNine', 'slideDown', 1000, 'center', 50, 0);";
	if(@mysqli_query($dbcon,$sql)) {		} else {	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: $sql</div>"); 	}




	$_SESSION['sm'] = "New billboard created. Adjust settings below";
	session_write_close();
	header("location: index.php?do=look&action=billboardSlideshow&bill_id=".$id."");
	exit();

}






if(!empty($_REQUEST['submitit'])) {
	if(empty($_REQUEST['bill_name'])) {
		$error = "<div>Please enter an billboard name</div>";
	}
	if(!empty($error)) {
		print "<div class=error>$error</div><div>&nbsp;</div>";
		regForm();
	} else {

		$_REQUEST['bill_html'] = trim($_REQUEST['bill_html']);

			if($_REQUEST['bill_html'] == "<br />") {
				$_REQUEST['bill_html'] = "";
			}


	if($_REQUEST['bill_id'] <= 0) { 

		$id = insertSQL("ms_billboards", " bill_name='".addslashes(stripslashes($_REQUEST['bill_name']))."', bill_slideshow='1',  bill_width='".addslashes(stripslashes($_REQUEST['bill_width']))."', bill_height='".addslashes(stripslashes($_REQUEST['bill_height']))."', bill_seconds='".addslashes(stripslashes($_REQUEST['bill_seconds']))."', bill_trans_time='".$_REQUEST['bill_trans_time']."' , bill_loop='".$_REQUEST['bill_loop']."', bill_transition='".$_REQUEST['bill_transition']."', bill_show_nav='".$_REQUEST['bill_show_nav']."', bill_pic='".$_REQUEST['bill_pic']."', bill_fixed='".$_REQUEST['bill_fixed']."', bill_placement='".$_REQUEST['bill_placement']."',
		bill_border_color='".$_REQUEST['bill_border_color']."',
		bill_border_size='".$_REQUEST['bill_border_size']."',
		bill_padding='".$_REQUEST['bill_padding']."',
		bill_nav_background='".$_REQUEST['bill_nav_background']."',
		bill_nav_color='".$_REQUEST['bill_nav_color']."',
		bill_burns='".$_REQUEST['bill_burns']."',
		bill_nav_border='".$_REQUEST['bill_nav_border']."'
		");


	} else { 

		$ssimgs = whileSQL("ms_photos LEFT JOIN ms_billboard_slides ON ms_photos.pic_id=ms_billboard_slides.slide_pic", "*", "WHERE slide_billboard='".$_REQUEST['bill_id']."' ORDER BY slide_order ASC ");
		while($ssimg = mysqli_fetch_array($ssimgs)) { 
			updateSQL("ms_billboard_slides", "slide_order='".$_REQUEST['slide_order'][$ssimg[slide_id]]."' WHERE slide_id='".$ssimg['slide_id']."' ");

//			print "<li>Slide order: ".$ssimg['slide_id'].": ".$_REQUEST['slide_order'][$ssimg[slide_id]];
		}


		updateSQL("ms_billboards", " bill_name='".addslashes(stripslashes($_REQUEST['bill_name']))."', bill_slideshow='1',  bill_width='".addslashes(stripslashes($_REQUEST['bill_width']))."', bill_height='".addslashes(stripslashes($_REQUEST['bill_height']))."', bill_seconds='".addslashes(stripslashes($_REQUEST['bill_seconds']))."', bill_trans_time='".$_REQUEST['bill_trans_time']."' , bill_loop='".$_REQUEST['bill_loop']."', bill_transition='".$_REQUEST['bill_transition']."', bill_show_nav='".$_REQUEST['bill_show_nav']."', bill_pic='".$_REQUEST['bill_pic']."', bill_fixed='".$_REQUEST['bill_fixed']."', bill_placement='".$_REQUEST['bill_placement']."',
		bill_border_color='".$_REQUEST['bill_border_color']."',
		bill_border_size='".$_REQUEST['bill_border_size']."',
		bill_padding='".$_REQUEST['bill_padding']."',
		bill_nav_background='".$_REQUEST['bill_nav_background']."',
		bill_nav_color='".$_REQUEST['bill_nav_color']."',
		bill_nav_border='".$_REQUEST['bill_nav_border']."',
		bill_burns='".$_REQUEST['bill_burns']."',
		bill_limit='".$_REQUEST['bill_limit']."'

		
		WHERE bill_id='".$_REQUEST['bill_id']."' ");
		$id = $_REQUEST['bill_id'];


	}

		$_SESSION['sm'] = "Billboard saved";
		session_write_close();
		header("location: index.php?do=look&action=billboardSlideshow&bill_id=".$id."");
		exit();
	?>
	<?php 
	}
	} else {
	regForm();
}
?>	

<?php  
function regForm() {
	global $tr, $_REQUEST, $setup, $site_setup;
	if((!empty($_REQUEST['bill_id']))AND(empty($_REQUEST['submit']))==true) {
		$bill = doSQL("ms_billboards", "*", "WHERE bill_id='".$_REQUEST['bill_id']."' ");
		if(empty($bill['bill_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($bill AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}

	if($_REQUEST['bill_id'] <= 0 ) { 
		$lbill = doSQL("ms_billboards", "*", "WHERE bill_slideshow='1' ORDER BY bill_id DESC ");
		if($lbill['bill_id'] > 0) { 
			$bill['bill_fixed'] = $lbill['bill_fixed'];
			$bill['bill_height'] = $lbill['bill_height'];
			$bill['bill_width'] = $lbill['bill_width'];
			$bill['bill_seconds'] = $lbill['bill_seconds'];
			$bill['bill_trans_time'] = $lbill['bill_trans_time'];
			$bill['bill_loop'] = $lbill['bill_loop'];
			$bill['bill_transition'] = $lbill['bill_transition'];
			$bill['bill_show_nav'] = $lbill['bill_show_nav'];
			$bill['bill_pic'] = $lbill['bill_pic'];
			$bill['bill_placement'] = $lbill['bill_placement'];

			$bill['bill_border_color'] = $lbill['bill_border_color'];
			$bill['bill_border_size'] = $lbill['bill_border_size'];
			$bill['bill_padding'] = $lbill['bill_padding'];
			$bill['bill_nav_background'] = $lbill['bill_nav_background'];
			$bill['bill_nav_color'] = $lbill['bill_nav_color'];
			$bill['bill_nav_border'] = $lbill['bill_nav_border'];

		} else { 
			$bill['bill_fixed'] = 0;
			$bill['bill_height'] = 250;
			$bill['bill_width'] = 1024;
			$bill['bill_seconds'] = 5;
			$bill['bill_trans_time'] = 500;
			$bill['bill_loop'] = 0;
			$bill['bill_transition'] = 'neatbbfade';
			$bill['bill_show_nav'] = '1';
			$bill['bill_pic'] = 'pic_large';
			$bill['bill_placement'] = "insidecontainer";
			$bill['bill_border_color'] = "000000";
			$bill['bill_border_size'] = "1";
			$bill['bill_padding'] = "4";
			$bill['bill_nav_background'] = "FFFFFF";
			$bill['bill_nav_color'] = "242424";
			$bill['bill_nav_border'] = "FFFFFF";

		}
	}


	?>
<div id="pageTitle" class="left"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?>   <a href="index.php?do=look&action=billboardsList">Billboards</a> <?php print ai_sep;?>  
 	<?php  if(!empty($_REQUEST['bill_id'])) { ?>
		 Editing Billboard
	<?php  }  else { ?>
		 Adding Billboard
	<?php  } ?>
</div>
<div class="right textright"><a href="https://www.picturespro.com/sytist-manual/site-design/billboards/" target="_blank"><span class="the-icons icon-info-circled"></span>Billboards in manual</a></div>
<div class="clear"></div>
<div>&nbsp;</div>
<?php isFullScreenLarge();?> 

<script>
	function selectlocation() { 
		if($('input:radio[name=bill_placement]:checked').val() == "full") {
			$(".fixedheightoptions").slideUp(100);
			$("#fullscreeninfo").slideDown(100);
		} else { 
			$(".fixedheightoptions").slideDown(100);
			$("#fullscreeninfo").slideUp(100);
		}
}

	function selectnavs() { 
		if($("#bill_show_nav").attr("checked")) {
			$("#navcolors").slideDown(100);
		} else { 
			$("#navcolors").slideUp(100);
		}
	}
</script>
<div class="buttonsgray">
	<ul>

	<?php if(!empty($_REQUEST['bill_id'])) { ?>
	<?php if(($bill['bill_cat'] <=0) && ($bill['bill_page'] <=0)==true){ ?>
	<li><a href="" onclick="openFrame('w-photos-upload.php?bill_id=<?php print $bill['bill_id'];?>'); return false;" class="tip" title="Upload Photos">UPLOAD SLIDES</a></li>	
	<?php } ?>
	<li><a href="<?php tempFolder();?>/<?php print $site_setup['index_page'];?>?previewBillboard=<?php print $bill['bill_id'];?>" target="_blank">PREVIEW BILLBOARD ON WEBSITE</a></li>
	<?php if($bill['bill_page'] <=0) { ?>
	<li><?php print "<a href=\"index.php?do=look&action=billboardSlideshow&deleteBillboard=".$bill['bill_id']."\" onClick=\"return confirm('Are you sure you want to delete this billboard? Deleting this will permanently remove it and can not be reversed! ');\"> DELETE THIS BILLBOARD</a>"; ?></li>	
	<?php } ?>
	<?php } ?>
	<div class="cssClear"></div>
	</ul>
</div>

<div id="roundedFormContain">
<form name="register" id="register" action="index.php" method="post" style="padding:0; margin: 0;" onSubmit="return checkForm();">
<div style="width: 49%; float: left;">
<div >
	<div class="underline">
		<div class="label">Enter a name for your reference</div>
		<div><input type="text" name="bill_name" id="bill_name" value="<?php print htmlspecialchars($bill['bill_name']);?>" class="required field100 inputtitle"></div>
	</div>

	<?php 	
	if(($bill['bill_cat'] > 0)==true){ ?>
	<div class="underline">
		<div class="label">Show <input type="text" name="bill_limit" id="bill_limit" class="required" size="2" value="<?php print $bill['bill_limit'];?>"> most recent pages.</div>

	</div>
	<?php } ?>

	<div class="underlinelabel">Placement</div>
	<div class="underline">
		<div>
		<input type="radio" name="bill_placement" id="bill_placement1" value="insidecontainer" <?php if($bill['bill_placement'] == "insidecontainer") { print "checked"; } ?> onchange="selectlocation();"> <label for="bill_placement1">Inside page content container (fixed width / height ratio)</label> &nbsp; <br>
		<input type="radio" name="bill_placement" id="bill_placement2" value="belowmenu" <?php if($bill['bill_placement'] == "belowmenu") { print "checked"; } ?> onchange="selectlocation();"> <label for="bill_placement2">Below menu & above page content container (fixed width / height ratio)</label>&nbsp; <br>
		<input type="radio" name="bill_placement" id="bill_placement3" value="full" <?php if($bill['bill_placement'] == "full") { print "checked"; } ?> onchange="selectlocation();"> <label for="bill_placement3">Full Screen (screen height)</label><br>
		
		</div>
	</div>
	<div class="underlinespacer <?php if($bill['bill_placement'] !== "full") { ?>hide<?php } ?>" id="fullscreeninfo"><i>When using the full screen option, you can have the billboard show behind the header & add buttons that overlay the photos to link to different pages. <a href="<?php tempFolder();?>/<?php print $site_setup['index_page'];?>?previewBillboard=<?php print $bill['bill_id'];?>" target="_blank">Click here to manage those options</a>. (Save any changes made here before managing those options and be sure you have uploaded at least one photo to this billboard.)</i></div>
	<div class="underline fixedheightoptions <?php if($bill['bill_placement'] == "full") { ?>hide<?php } ?>">
		<div class="left" style="width: 50%;">
		<div class="label">Height</div>
		<div><input type="text" name="bill_height" id="bill_height" class="required center" size="4" value="<?php print $bill['bill_height'];?>">px &nbsp; </div>
		</div>
		<div class="left" style="width: 50%;">
		<input type="hidden" name="bill_pic" value="pic_large">
		</div>
	<div class="clear"></div>
	</div>


	<div class="underline fixedheightoptions <?php if($bill['bill_placement'] == "full") { ?>hide<?php } ?>">
		<div class="left" style="width: 33%;">
		<div class="label">Padding</div>
		<div><input type="text" name="bill_padding" id="bill_padding"  size="2" class="center" value="<?php print $bill['bill_padding'];?>"> px &nbsp; </div>
		</div>

		<div class="left" style="width: 33%;">
		<div class="label">Border Color </div>
		<div><input type="text"  size="10" name="bill_border_color" id="bill_border_color" value="<?php  print $bill['bill_border_color'];?>" class="color center"></div>
		</div>
		<div class="left" style="width: 33%;">
		<div class="label">Border Size </div>
		<div><input type="text"  size="2" name="bill_border_size" id="bill_border_size" class="center" value="<?php  print $bill['bill_border_size'];?>"> px</div>
		</div>

		<div class="clear"></div>
	</div>



<?php if($bill['bill_page'] <=0) { ?>

	<div class="underline">
		<div class="left" style="width: 50%;">
		<div class="label">Seconds between slides </div>
		<div><input type="text" name="bill_seconds"size="2" class="center" value="<?php print $bill['bill_seconds'];?>"> &nbsp; </div>
		</div>
		<div class="left" style="width: 50%;">
		<div class="label">Transition Speed</div>
		<div>
		<select name="bill_trans_time">
		<?php $s = 0;
		while($s <=5000) { ?>
		<option value="<?php print $s;?>" <?php if($s == $bill['bill_trans_time']) { print "selected"; } ?>><?php print number_format($s / 1000,2); ?> </option> 
		<?php
			$s = $s + 100;
		}
		?>
		</select> seconds
		 </div>

		</div>
		<div class="clear"></div>
	</div>
<?php } ?>


	<div class="underline" id="burnsoption">
		<div class="label">
		<input type="checkbox" name="bill_burns" id="bill_burns" value="1" <?php if($bill['bill_burns'] == "1") { print "checked"; } ?>> <label for="bill_burns">Add slow zoom in / out effect to photos</label>
		</div>
	</div>

<?php if($bill['bill_page'] <=0) { ?>

	<div class="underline">
		<div class="label">
		<input type="checkbox" name="bill_loop" id="bill_loop" value="1" <?php if($bill['bill_loop'] == "1") { print "checked"; } ?>> <label for="bill_loop">Loop the slides. Leave unchecked to stop on the last slide</label>
		</div>
	</div>






	<div class="underline">
		<div class="label">
		<input type="checkbox" name="bill_show_nav" id="bill_show_nav" value="1" <?php if($bill['bill_show_nav'] == "1") { print "checked"; } ?> onChange="selectnavs();"> <label for="bill_show_nav">Show small navigation buttons</label>
		</div>
	</div>

	<div  id="navcolors" <?php if($bill['bill_show_nav'] !== "1") { print "style=\"display: none;\""; } ?>>
	<div class="underline">Navigation buttons color</div>
	<div class="underline">
		<div class="left" style="width: 33%;">
		<div class="label">Border Color</div>
		<div><input type="text"  size="10" name="bill_nav_border" id="bill_nav_border" value="<?php  print $bill['bill_nav_border'];?>" class="color center"></div>
		</div>

		<div class="left" style="width: 33%;">
		<div class="label">Filled Color</div>
		<div><input type="text"  size="10" name="bill_nav_background" id="bill_nav_background" value="<?php  print $bill['bill_nav_background'];?>" class="color center"></div>
		</div>
		<!-- <div class="left" style="width: 33%;">
		<div class="label">Text </div>
		<div><input type="text"  size="10" name="bill_nav_color" id="bill_nav_color" value="<?php  print $bill['bill_nav_color'];?>" class="color center"></div>
		</div>
		-->
		<div class="clear"></div>
	</div>
</div>

<?php } ?>
<input type="hidden" name="bill_transition" value="neatbbfade">
<!-- 
	<div class="underline">
		<div class="label">Transition effect</div>
		<div>
		<select name="bill_transition">
		<option value="neatbbfade" <?php if($bill['bill_transition'] == "neatbbfade") { print "selected"; } ?>>neatbbfade</option>
		<option value="neatbbslidedown" <?php if($bill['bill_transition'] == "neatbbslidedown") { print "selected"; } ?>>neatbbslidedown</option>
		<option value="neatbbslideswap" <?php if($bill['bill_transition'] == "neatbbslideswap") { print "selected"; } ?>>neatbbslideswap</option>
		<option value="neatbbslidelr" <?php if($bill['bill_transition'] == "neatbbslidelr") { print "selected"; } ?>>neatbbslidelr</option>
		</select> 
		</div>
	</div>
	-->
	<div class="bottomSave">
	<input type="hidden" name="do" value="look">
	<input type="hidden" name="action" value="billboardSlideshow">
	<input type="hidden" name="submitit" value="yup">
	<input type="hidden" name="bill_id" value="<?php  print $_REQUEST['bill_id'];?>">
	<?php if($bill['bill_id'] <=0) { ?>
	<input  type="submit" name="submit" value="Create Billboard" class="submit">
	<?php } else { ?>
	<input  type="submit" name="submit" value="Update Billboard" class="submit">
	<?php } ?>
</div>
	</div>

</div>

<div style="width: 49%; float: right;">

<?php if(($bill['bill_cat'] > 0) OR ($bill['bill_page'] > 0)==true){ ?>

	<?php
		$ssimgs = whileSQL("ms_billboard_slides ", "*", "WHERE slide_billboard='".$bill['bill_id']."' ORDER BY slide_order ASC ");
		while($ssimg = mysqli_fetch_array($ssimgs)) { 
			$slide_ids = $ssimg['slide_id'];
			$ssi++;
			?>
		<div class="pc"><h3><a href="" onclick="pagewindowedit('/<?php print $setup['temp_url_folder'];?><?php print $setup['manage_folder'];?>/w-billboard-slide.php?do=editOption&noclose=1&nofonts=1&nojs=1&editslide=<?php print $ssimg['slide_id'];?>','1200'); return false;">Click here to edit the text color & placement for the page titles</a>.</h3></div>
			
			<div class="underline">
			<div class="pc"><a href="" onclick="pagewindowedit('<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/w-billboard-slide.php?do=editOption&noclose=1&nofonts=1&nojs=1&editslide=<?php print $ssimg['slide_id'];?>','1200'); return false;"><img src="graphics/photo2.jpg" border="0"></a></div>
			<div class="pc"><a href="" onclick="pagewindowedit('<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/w-billboard-slide.php?do=editOption&noclose=1&nofonts=1&nojs=1&editslide=<?php print $ssimg['slide_id'];?>','1200'); return false;">Edit title and text placement</a>
			<?php 
			print "</div>";
			?>


			</div>
			<?php 
		}
	?>






<?php } ?>

		 
	<?php if(($bill['bill_cat'] <=0) && ($bill['bill_page'] <=0)==true){ ?>

		 
<div class="pageContent"><h2>Billboard Slides</h2></div>


<?php if($_REQUEST['bill_id'] <=0) { ?>
<div class="pc">Once you create your billboard, you will be able to upload photos here.</div>
<?php } else { ?>


<div class="pc"><a href="" onclick="openFrame('w-photos-upload.php?bill_id=<?php print $bill['bill_id'];?>'); return false;" class="tip" title="Upload Photos">Upload Slides</a></div>
<div>&nbsp;</div>
<?php
$ssimgs = whileSQL("ms_photos LEFT JOIN ms_billboard_slides ON ms_photos.pic_id=ms_billboard_slides.slide_pic", "*", "WHERE slide_billboard='".$bill['bill_id']."' ORDER BY slide_order ASC ");
if(mysqli_num_rows($ssimgs) <=0) { 
	print "<div class=\"pageContent\" style=\"text-align: center\"><div class=\"error\">No slides uploaded</div></div>";
} else { ?>

<div>


	<?php
		$ssimgs = whileSQL("ms_photos LEFT JOIN ms_billboard_slides ON ms_photos.pic_id=ms_billboard_slides.slide_pic", "*", "WHERE slide_billboard='".$bill['bill_id']."' ORDER BY slide_order ASC ");
		while($ssimg = mysqli_fetch_array($ssimgs)) { 
			$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$ssimg['pic_folder']."/".$ssimg['pic_th'].""); 
			$slide_ids = $ssimg['slide_id'];
			$ssi++;
			?>
			<div class="underline">
			<div class="pc"><a href="" onclick="pagewindowedit('w-billboard-slide.php?do=editOption&noclose=1&nofonts=1&nojs=1&editslide=<?php print $ssimg['slide_id'];?>','1200'); return false;"><img src="<?php print getimagefile($ssimg,'pic_th');?>" border="0"></a></div>
			<div class="pc"><a href="" onclick="pagewindowedit('w-billboard-slide.php?do=editOption&noclose=1&nofonts=1&nojs=1&editslide=<?php print $ssimg['slide_id'];?>','1200'); return false;">Edit</a> &nbsp; <?php print "<a href=\"index.php?do=look&action=billboardSlideshow&subdo=deleteSlide&slide_id=".$ssimg['slide_id']."&bill_id=".$bill['bill_id']."\" onClick=\"return confirm('Are you sure you want to delete this? This will delete this from the billboard but the photo will remain in your system.'); return false;\">Delete</a> &nbsp; ";
			?>
			<a href="" onclick="openFrame('w-photos-upload.php?bill_id=<?php print $bill['bill_id'];?>&slide_id=<?php print $ssimg['slide_id'];?>'); return false;" class="tip" title="Replace Photo">Replace</a> &nbsp;
			<?php
			print "Display order: <input type=\"text\" name=\"slide_order[".$ssimg['slide_id']."]\" value=\"".$ssimg['slide_order']."\" size=\"2\"> &nbsp; &nbsp; ";
			print "</div>";
			?>


			</div>
			<?php 
		}
	?>
				
</div>
<?php } ?>
<?php } ?>
<?php } ?>


</div>
</form>
<div class="cssClear"></div>


<?php  } ?>
</div>