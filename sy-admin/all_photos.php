<?php if($setup['demo_mode'] == true) { ?>
<div id="demomode">
	<div style="padding: 16px;">
		<div class="pc"><h3>The All Photos section will show you all photos uploaded to galleries</h3></div>
		<div class="pc">Here you can search for photos, sort, view tags and more.</div>
	</div>
</div>
<div>&nbsp;</div>
<?php } ?>



<div id="pageTitle"><a href="index.php?do=allPhotos">All Photos</a></div>
<div class="pc">This section will show you all the photos you have uploaded. If you are wanting to upload photos for a gallery, do not upload here! <a href="index.php?do=news">Create the gallery first</a> and upload them to that gallery.</div>
<div>&nbsp;</div>

<?php if(!empty($_REQUEST['did'])) { 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['did']."' ");

	?>
	<div id="bluenotice" class="center">
	Select the photos you want to add below. When you are done, click the  <a href="admin.actions.php?action=addPhotosToBlog&blog_id=<?php print $_REQUEST['did'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>">Add selected photos to <?php print $date['date_title'];?></a> link in the tray. 
	</div>
<div>&nbsp;</div>
<?php } ?>
<?php if(!empty($_REQUEST['bid'])) { 
	$bill = doSQL("ms_billboards", "*", "WHERE bill_id='".$_REQUEST['bid']."' ");

	?>
	<div id="bluenotice" class="center">
	Select the photos you want to add below. When you are done, click the  <a href="admin.actions.php?action=addPhotosToBillboard&bill_id=<?php print $_REQUEST['bid'];?>&slide_id=<?php print $_REQUEST['slide_id'];?>">Add selected photos to <?php print $bill['bill_name'];?></a> link in the tray. 
	</div>
<div>&nbsp;</div>
<?php } ?>

<!-- <div id="pos" style="position: fixed; background: #999999; padding: 4px; top: 0; left:0;">XX</div> -->

<?php  $show_thumbnails = true;?>




<div id="roundedFormContain">

<div style="">

<div id="listPhotosContainer" style="	min-height: 1px;">
	<div id="photo-results" class="pageContent">
	<?php 
	$and_where = getSearchString();
	$search = getSearchOrder();

	if($_REQUEST['view'] == "unblogged") { 
		$unblogged = whileSQL("ms_photos LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE ms_blog_photos.bp_pic IS NULL AND  pic_no_dis='0'   AND pic_client='".$_REQUEST['pic_client']."'  " );
		$piccount = mysqli_num_rows($unblogged);
	} elseif(!empty($_REQUEST['key_id'])) { 
		$piccount = countIt("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "WHERE key_key_id='".$_REQUEST['key_id']."'   AND pic_client='".$_REQUEST['pic_client']."' ");
	} else { 
		$piccount = countIt("ms_photos", "WHERE  pic_no_dis='0' $and_where  ORDER BY ".$search['orderby']." ".$search['acdc']." $and_acdc");
	}


	print "<div><span class=\"h2\">".$piccount." Results ";
	if(!empty($_REQUEST['key_id'])) { 
		$kw = doSQL("ms_photo_keywords", "*", "WHERE id='".$_REQUEST['key_id']."' ");
		print " | ".$kw['key_word']." <a href=\"index.php?do=allPhotos\" title=\"remove\">".ai_clear_search."</a>";
	}
	if(!empty($_REQUEST['pic_camera_model'])) { print " |  ".$_REQUEST['pic_camera_model']." </span> <a href=\"index.php?do=allPhotos&keyWord=".$_REQUEST['keyWord']."&orderBy=".$_REQUEST['orderBy']."&acdc=".$_REQUEST['acdc']."\" title=\"remove\">".ai_clear_search."</a>"; } 

	if(!empty($_REQUEST['pic_upload_session'])) { print " |  upload session ".$_REQUEST['pic_upload_session']." </span> <a href=\"index.php?do=allPhotos\" title=\"remove\">".ai_clear_search."</a>"; } 




	if($_REQUEST['view'] == "unblogged") { print " | Photos Not Shown On Website</span> <a href=\"index.php?do=allPhotos\" title=\"remove\">".ai_clear_search."</a>"; } 

	if(!empty($_REQUEST['keyWord'])) { print " |  ".$_REQUEST['keyWord']." <a href=\"index.php?do=allPhotos\" title=\"remove\">".ai_clear_search."</a>"; } 

	print "</span></div>";
	?>

	<?php if(!empty($_REQUEST['pic_upload_session'])) { ?>
	<div class="pc"><a href="admin.actions.php?action=selectAllFromSession&pic_upload_session=<?php print $_REQUEST['pic_upload_session'];?>&pic_client=<?php print $_REQUEST['pic_client'];?>&did=<?php print $_REQUEST['did'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>">Select All</a></div>

	<div class="pc">
	<form method="post" name="editphotoinfo" id="productForm" action="javascript:addKeyWords('add_keywords_2', 'keywords-saved_2','<?php print $_REQUEST['pic_upload_session'];?>');" >
Add tags to photos in this upload session<br>
<input type="text" name="add_keywords_2" id="add_keywords_2" size="30">

<input type="submit" name="submit" value="add tags" class="submitSmall">
</form>
<div id="keywords-saved_2" style="position: absolute; top: 0; left: 0; z-index: 5;"></div>
</div>
<?php } ?>
	<?php 
	$pages = floor($piccount / $thumbs_per_page + 1);
	if($pages > 1) {  ?>
	<div>



		<form method="get" name="pagejump" action="index.php">
	Jump to page: 
	<input type="hidden" name="do" value="allPhotos">
	<input type="hidden" name="keyWord" value="<?php print $_REQUEST['keyWord'];?>">
	<input type="hidden" name="key_id" value="<?php print $_REQUEST['key_id'];?>">
	<input type="hidden" name="orderBy" value="<?php print $_REQUEST['orderBy'];?>">
	<input type="hidden" name="acdc" value="<?php print $_REQUEST['acdc'];?>">
	<input type="hidden" name="pic_camera_model" value="<?php print $_REQUEST['pic_camera_model'];?>">
	<input type="hidden" name="pic_client" value="<?php print $_REQUEST['pic_client'];?>">
	<select name="page" onchange="this.form.submit();">

	<?php 
	$x = 1;	
	while($x <= $pages) { 
		print "<option value=\"$x\""; if($_REQUEST['page'] == $x) { print " selected"; } print ">$x</option> ";
		$x++;
	}
	?>
	</select>
	</form></div>
	<?php } ?>
	</div>

	<div class="pc right textright">
	<script>
	$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});

	</script>
	Search by date / time 
	<form method="get" name="time" action="index.php" id="timesearch">
	<?php 
	$search_date = "2014-04-15";
	$from_time = "0";
	$end_time = "24";
	$search_length = 15;
	$blocks = 0;

	$sdate = explode("-",$search_date);
	if(empty($_REQUEST['search_date'])) { 
		$search_date = date('Y-m-d');
	} else { 
		$search_date = $_REQUEST['search_date'];
	}
	?>
	<input type="text" name="search_date" id="search_date" value="<?php print $search_date;?>" class="datepicker center" size="10">

	<select name="from_time" id="from_time">
	<option value="">All Times</option>
	<?php 
	while($from_time < $end_time) { 
		$this_time = date("H:i", mktime($from_time, 0+($search_length * $blocks), 0, 04, 15, 2014));
		?><option value="<?php print $this_time;?>" <?php if($this_time == $_REQUEST['from_time']) { print "selected"; } ?>>
		<?php 
		echo date("h:i A", mktime($from_time, 0+($search_length * $blocks), 0, 04, 15, 2014));
		print " to ";
		$blocks++;
		echo date("h:i A", mktime($from_time, 0+($search_length * $blocks), 0, 04, 15, 2014));
		?>
		</option>
		<?php 
		if($blocks >= (60 / $search_length)) { 
			$from_time++;
			$blocks = 0;
		}
	}
	?>
	</select>
	<input type="hidden" name="do" id="do" value="allPhotos">
	<input type="hidden" name="search_length" id="search_length" value="<?php print $search_length;?>">
	<input type="submit" name="submit" value="go" class="submitSmall">

	</form>
	</div>
	<div class="clear"></div>
	<div id="photo-filter">
		<div class="pageContent">
		<form method="get" name="keys" action="index.php" id="photosearch">
		<?php if(!empty($_REQUEST['keyWord'])) { ?> <a href="index.php?do=allPhotos&orderBy=<?php print $_REQUEST['orderBy'];?>&acdc=<?php print $_REQUEST['acdc'];?>"><?php print ai_clear_search;?></a><?php } ?>
		<input type="hidden" name="do" value="allPhotos">
		<input type="hidden" name="did" value="<?php print $_REQUEST['did'];?>">
		<input type="hidden" name="sub_id" value="<?php print $_REQUEST['sub_id'];?>">
		<input type="hidden" name="bid" value="<?php print $_REQUEST['bid'];?>">
		<input type="hidden" name="slide_id" value="<?php print $_REQUEST['slide_id'];?>">
		<input type="hidden" name="search_length" value="<?php print $_REQUEST['search_lenght'];?>">
		<input type="hidden" name="search_date" value="<?php print $_REQUEST['search_date'];?>">
		<input type="hidden" name="from_time" value="<?php print $_REQUEST['from_time'];?>">
		<input type="hidden" name="pic_camera_model" value="<?php print $_REQUEST['pic_camera_model'];?>">
		<input type="hidden" name="key_id" value="<?php print $_REQUEST['key_id'];?>">
		<input type="hidden" name="view" id="view" value="<?php print $_REQUEST['view'];?>">
		<input type="hidden" name="pic_upload_session" value="<?php print $_REQUEST['pic_upload_session'];?>">
		<input type="text" name="keyWord" <?php if(!empty($_REQUEST['keyWord'])) { print "value=\"".htmlspecialchars(stripslashes($_REQUEST['keyWord']))."\""; } else { ?>value="<?php print _default_search_text_;?>"  class="ff-default-value" onfocus="if (this.value == '<?php print _default_search_text_;?>') {this.value = ''; this.className='ff-input';}" onblur="if (this.value == '') {this.value = '<?php print _default_search_text_;?>'; this.className='ff-default-value';}" <?php } ?> > 
		<input type="submit" name="submitform" value="go" class="submitSmall">
		<select name="orderBy" id="orderBy" onchange="this.form.submit();">
		<option value="" disabled="disabled" style="font-style: italic;">Sort by</option>
		<option value="pic_id" <?php if(($_REQUEST['orderBy'] == "pic_id")  OR((empty($_REQUEST['orderBy']))AND($photo_setup['def_all_orderby']=="pic_id"))==true){ print "selected"; } ?>>Upload Date / Time</option>
		<option value="pic_date_taken" <?php if(($_REQUEST['orderBy'] == "pic_date_taken")  OR((empty($_REQUEST['orderBy']))AND($photo_setup['def_all_orderby']=="pic_date_taken"))==true) { print "selected"; } ?>>Date / Time Taken</option>
		<option value="pic_org" <?php if(($_REQUEST['orderBy'] == "pic_org")   OR((empty($_REQUEST['orderBy']))AND($photo_setup['def_all_orderby']=="pic_org"))==true) { print "selected"; } ?>>File Name</option>
		</select>
		<select name="acdc" id="acdc" onchange="this.form.submit();">
		<option value="ASC" <?php if(($_REQUEST['acdc'] == "ASC") OR((empty($_REQUEST['acdc']))AND($photo_setup['def_all_acdc']=="ASC"))==true){  print "selected"; } ?>>Ascending</option>
		<option value="DESC" <?php if(($_REQUEST['acdc'] == "DESC")  OR((empty($_REQUEST['acdc']))AND($photo_setup['def_all_acdc']=="DESC"))==true){ print "selected"; } ?>>Descending</option>
		</select>

		<select name="orientation" id="orientation" onchange="this.form.submit();">
		<option value="" disabled="disabled" style="font-style: italic;">Photo Orientation</option>
		<option value="">All Orientations</option>

		<option value="portrait" <?php if($_REQUEST['orientation'] == "portrait") {  print "selected"; } ?>>Portrait</option>
		<option value="landscape" <?php if($_REQUEST['orientation'] == "landscape") {  print "selected"; } ?>>Landscape</option>
		<option value="square" <?php if($_REQUEST['orientation'] == "square") {  print "selected"; } ?>>Square</option>
		</select>
		<!-- 
		<select name="pic_client" id="pic_client" onchange="this.form.submit();">
		<option value="0" <?php if($_REQUEST['pic_client'] <=0 ) {  print "selected"; } ?>>Public Photos</option>
		<option value="1" <?php if($_REQUEST['pic_client'] == "1" ) {  print "selected"; } ?>>Client Photos</option>
		</select>
		-->
		</form>
		</div>
		<div class="muted">&nbsp;To search for file name, type file: then file name. Example: file:image_001</div>
	</div>
<div class="cssClear"></div>

<div id="photoGallery">
<div id="showThumbnails"></div>
</div>

<div class="cssClear"></div>

<div id="endpage" style="position: absolute;"></div>

</div>
</div>

<div class="cssClear"></div>
</div>


