<div id="adminfooter">
<div>&nbsp;</div>
 <?php if($setup['unbranded'] !== true) { ?>
<div class="pc center">Sytist Version <?php print $site_setup['sytist_version'];?><br><a href="http://www.picturespro.com/sytist-manual/" target="_blank"><b>Sytist Manual</b></a>  &nbsp; &nbsp;   <a href="http://www.picturespro.com/support-forum/sytist/" target="_blank"><b>Support</b></a>   &nbsp; &nbsp	 <a href="http://www.facebook.com/4sytist/" target="_blank" title="Sytist Facebook Page"><b>Sytist on Facebook</b></a>

</div>
<?php
if((trim($_SESSION['office_admin_login']) == "1") AND (!empty($_SESSION['office_admin']))==true) { ?>
<div class="pageContent" style="text-align: center;"> 
<a href="index.php">Home</a> 
&nbsp; &bull; &nbsp;<a href="index.php?do=look">Site Design</a> 
&nbsp; &bull; &nbsp;<a href="index.php?do=allPhotos">All Photos</a> 
&nbsp; &bull; &nbsp;<a href="index.php?do=news">Site Content</a> 
&nbsp; &bull; &nbsp;<a href="index.php?do=comments">Comments</a> 
&nbsp; &bull; &nbsp;<a href="index.php?do=forms">Forms</a> 
&nbsp; &bull; &nbsp;<a href="index.php?do=look">Design & Layout</a> 
&nbsp; &bull; &nbsp;<a href="index.php?do=settings">Settings</a> 
&nbsp; &bull; &nbsp;<a href="index.php?do=stats">Stats</a> 

</div>
<?php } ?>
<?php } ?>
<?php $reg = doSQL("ms_register", "*, date_format(DATE_ADD(reg_date, INTERVAL 2 HOUR), '%M %e, %Y ')  AS reg_date", ""); ?>
<!-- #### <?php  print "".$reg['reg_key'].""; ?>  - version <?php print $site_setup['sytist_version'];?> ##### -->
 <?php if($setup['unbranded'] !== true) { ?>

<div class="pageContent" style="text-align: center;">&copy; 2012-<?php print date('Y'); ?> <a href="http://www.picturespro.com" target="_blank">picturespro.com</a></div>
<?php } ?>
<div>&nbsp;</div>
<div class="showsmall center">	<?php if($_SESSION['full_site'] !== true) { ?><a href="admin.actions.php?action=viewfullsite">Do not show mobile version of admin for this session.</a><?php } else { ?><a href="admin.actions.php?action=noviewfullsite">Show mobile version of admin when screen is small for this session</a><?php } ?></div>

<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>

</div>

</div>
<div class="clear"></div>
<div id="shadepagecontainer" style="display: none;" ><div id="shadepage" ></div></div>
<div id="shadepagecontent" style="display: none;" ><div id="shadepageenter" style="display: none;"></div></div>
<div id="loadpagecontainer" style="display: none;" ><div id="loadpage" ></div></div>
<?php
 if(is_array($_SESSION['heldPhotos'])) {  
	 if(count($_SESSION['heldPhotos']) > 0) {
		 $display = "block";
	 } else {
		 $display = "none";
	 } 
} else {
	$display = "none";

}
?>
<div id="goToTop"><a href="" onclick="gototop(); return false;"><img src="graphics/top2.png" border="0" title="Go to top of page" style="width: 24px; height: 24px; margin: 4px;"></a></div>



</div>
</div></div>
<div class="clear"></div>
<script>
 $(document).ready(function(){
	 if($(".bottomSave").length > 0) { 
		$("#selectedPhotos").css({"bottom":$(".bottomSave").height()+"px"});
	 }
});
</script>

<div id="selectedPhotos" style="display: <?php print $display;?>">

<div id="thePhotos">
	<?php 	showHeldPhotos(); ?>
</div>
<div id="theActions">
	<div class="inner">

<?php if(!empty($_REQUEST['did'])) { 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['did']."' ");

	?>
	<div class="pc center">
	<h2><a href="admin.actions.php?action=addPhotosToBlog&blog_id=<?php print $_REQUEST['did'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>">Add  photos to <?php print $date['date_title'];?>
	<?php if($_REQUEST['sub_id'] > 0) { 
		$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
		print " > ".$sub['sub_name'];
	}
	?>
	</a></h2>
	</div>

<?php } else if(!empty($_REQUEST['bid'])) { ?>
	<div class="pc center">
	<?php if($_REQUEST['slide_id'] > 0) { ?>
	<h1><a href="admin.actions.php?action=addPhotosToBillboard&bill_id=<?php print $_REQUEST['bid'];?>&slide_id=<?php print $_REQUEST['slide_id'];?>">Replace billboard slide with selected photo</a></h1>

	<?php } else { ?>
	<h1><a href="admin.actions.php?action=addPhotosToBillboard&bill_id=<?php print $_REQUEST['bid'];?>&slide_id=<?php print $_REQUEST['slide_id'];?>">Add selected photos to <?php print $bill['bill_name'];?> billboard</a></h1>
	<?php } ?>
	</div>

<?php } else if(!empty($_REQUEST['cid'])) { ?>
	<div class="pc center">
	<h1><a href="admin.actions.php?action=addPhotoToCat&cat_id=<?php print $_REQUEST['cid'];?>">Make selected photo category preview</h1>
	</div>
<?php } else if($_SESSION['selectclf'] > 0) { ?>
<script>
	function addheldtoclf() { 
		$.get("sweetness.php?action=addPhotos",function (data) { 
				pagewindowedit('w-clf-photos-order.php?show_id=<?php print $_SESSION['selectclf'];?>&nofonts=1&nojs=1&noclose=1');
				$("#selectedPhotos").hide(100);
		});
	}
	</script>
	<div class="pc center">
	<h1><a href="" onclick="addheldtoclf(); return false;">Add Selected Photos</h1>
	</div>

<?php } else { ?>
	<div class="pageContent left" style="position: relative;">
	<form method="post" name="editphotoinfo" id="productForm" action="javascript:addKeyWords('add_keywords', 'keywords-saved','');" >
	Add tags to photos in tray<br>
	<input type="text" name="add_keywords" id="add_keywords" size="20">

	<input type="submit" name="submit" value="add" class="submitSmall">
	</form>
	<div id="keywords-saved" style="position: absolute; top: 0; left: 0; z-index: 5;"></div>
	</div>
	<?php if(!empty($_REQUEST['date_id'])) { ?>
		<a href="admin.actions.php?action=addPhotosToBlog&blog_id=<?php print $_REQUEST['date_id'];?>" class="confirmdeleteoptions tip" confirm-title="Add Photos To This <?php if($_REQUEST['sub_id'] > 0) { ?>sub gallery<?php } else { ?> page<?php } ?>" confirm-message="Select from the options below" option-link-1="admin.actions.php?action=addPhotosToBlog&blog_id=<?php print $_REQUEST['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>"  option-link-1-text="Just add these photos to this page"  option-link-2="admin.actions.php?action=addPhotosToBlog&blog_id=<?php print $_REQUEST['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&removefromothers=1" option-link-2-text="Add photos to this page and remove from any other pages (move photos)" title="Add selected photos to this page">Add photos to this <?php if($_REQUEST['sub_id'] > 0) { ?>sub gallery<?php } else { ?> page<?php } ?></a> 

		 | <a href="" onclick="changepricelist('<?php print $_REQUEST['date_id'];?>'); return false;">Change Price List</a> 
	<?php } else { ?>
		<a href="index.php?do=news&action=addDate">Create new page</a> 
	<?php } ?>
		<?php if($setup['batch_pages_from_photos'] == true) { ?>  | <a href="" onclick="pagewindowedit('w-photos-pages.php?sub_id=&nofonts=1&nojs=1&noclose=1'); return false;" class="tip" title="Create a new page for each photo selected">Create Pages</a> <?php } ?>
		<br>
		<a href="javascript:clearHeldPhotos();" class="tip" title="Clear photos from the tray">Clear</a> &nbsp; | &nbsp;
		<span id="rotatephotos">Rotate <a href="" onclick="rotatephotos('right'); return false;" class="tip" title="Rotate selected photos 90&#186; left">Left</a>   <a href="" onclick="rotatephotos('left'); return false;"  class="tip" title="Rotate selected photos 90&#186; right">Right</a></span> &nbsp; | &nbsp;
		<a href="javascript:deleteHeldPhotos();"  class="confirmdelete tip" title="Delete photos held in the tray" confirm-title="Delete held photos" confirm-message="Are you sure you want to delete all the photos in the tray? This will permanently delete these from the server and remove them from any pages they are assigned to. " >Delete</a> 
		<?php if($photo_setup['enable_amazon'] == "1") { ?> &nbsp; | &nbsp; <a href="" onclick="movetoamazon(''); return false;" class="tip" title="Move photos in tray to Amazon S3">Move to S3</a><?php } ?>
		<!-- <a href="admin.actions.php?action=addPhotosToBackground"  onClick="return confirm('Are you sure you want to add these to your random backgrounds? '); return false;">Add to random background</a> &nbsp; | &nbsp;
		<a href="admin.actions.php?action=regenThumbs&keyWord=<?php print $_REQUEST['keyWord'];?>&orderBy=<?php print $_REQUEST['orderBy'];?>&acdc=<?php print $_REQUEST['acdc'];?>&pic_camera_model=<?php print $_REQUEST['pic_camera_model'];?>&page=<?php print $_REQUEST['page'];?>"  onClick="return confirm('Doing this will regenerate the thumbnails and mini files for the selected photos to your default settings now.\r\n\r\nThumbnails <?php print $photo_setup['blog_th_width'];?>px max width & <?php print $photo_setup['blog_th_height'];?>px max height.\r\nMini thumbs to <?php print $photo_setup['mini_size'];?>px square.\r\n\r\nClick OK to continue.'); return false;">Regenerate Thumbnails</a> &nbsp;  &nbsp;
		-->

	<?php } ?>
	</div>
</div>
</div>

<?php mysqli_close($dbcon); ?>
<div id="savingdata">
	<div class="spinner64"></div>
</div>
</BODY>
<?php if($has_subs==true) { ?>
<?php if(empty($_REQUEST['page'])) { $_REQUEST['page'] = 1; }?>

	<script>
	
 $(document).ready(function(){

	$.get("sub-galleries.php?page=<?php print $_REQUEST['page'];?>&date_id=<?php print $_REQUEST['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&pic_client=<?php print $_REQUEST['pic_client'];?>&keyWord=<?php print $_REQUEST['keyWord'];?>&view=<?php print $_REQUEST['view'];?>&untagged=<?php print $_REQUEST['untagged'];?>&pic_camera_model=<?php print $_REQUEST['pic_camera_model'];?>&pic_upload_session=<?php print $_REQUEST['pic_upload_session'];?>&orderBy=<?php print $_REQUEST['orderBy'];?>&acdc=<?php print $_REQUEST['acdc'];?>&orientation=<?php print $_REQUEST['orientation'];?>&key_id=<?php print $_REQUEST['key_id'];?>&p_id=<?php print $_REQUEST['p_id'];?>&from_time=<?php print $_REQUEST['from_time'];?>&search_date=<?php print $_REQUEST['search_date'];?>&search_length=<?php print $_REQUEST['search_length'];?>", function(data) {
		$("#sub-gallery-list").append(data);
		setTimeout(hideLoadingMore,1000);
		setTimeout(function() { getSubGalleries()},1000);


	});
	$("#vinfo").attr("subGalleryPageID",<?php print $_REQUEST['page'];?>);

});
	
	
	</script>


<?php } ?>

<?php if($show_thumbnails==true) { ?>
	<script>getDivPosition('endpage');</script>
<?php } ?>

<?php if(empty($_REQUEST['page'])) { $_REQUEST['page'] = 1; }?>
<script type="text/javascript">
<?php if($show_thumbnails==true) { ?>

 $(document).ready(function(){
	$.get("thumbnails.php?page=<?php print $_REQUEST['page'];?>&date_id=<?php print $_REQUEST['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&pic_client=<?php print $_REQUEST['pic_client'];?>&keyWord=<?php print $_REQUEST['keyWord'];?>&view=<?php print $_REQUEST['view'];?>&untagged=<?php print $_REQUEST['untagged'];?>&passcode=<?php print $_REQUEST['passcode'];?>&pic_camera_model=<?php print $_REQUEST['pic_camera_model'];?>&pic_upload_session=<?php print $_REQUEST['pic_upload_session'];?>&orderBy=<?php print $_REQUEST['orderBy'];?>&acdc=<?php print $_REQUEST['acdc'];?>&orientation=<?php print $_REQUEST['orientation'];?>&key_id=<?php print $_REQUEST['key_id'];?>&p_id=<?php print $_REQUEST['p_id'];?>&from_time=<?php print $_REQUEST['from_time'];?>&search_date=<?php print $_REQUEST['search_date'];?>&search_length=<?php print $_REQUEST['search_length'];?>", function(data) {
		$("#showThumbnails").append(data);
		setTimeout(hideLoadingMore,1000);
	});
	$("#vinfo").attr("thumbPageID",<?php print $_REQUEST['page'];?>);

});
<?php } ?>


function loadAll() {
<?php if($show_thumbnails==true) { ?>


<?php if($_REQUEST['do'] == "allPhotos") { ?>
	$("#photo-tags").append("<center><br><img src='graphics/loading1.gif'></center>");
	$.get("tags.php?did=<?php print $_REQUEST['did'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&bid=<?php print $_REQUEST['bid'];?>&slide_id=<?php print $_REQUEST['slide_id'];?>&pic_upload_session=<?php print $_REQUEST['pic_upload_session'];?>", function(data) {
		$("#photo-tags").html("").append(data);
	});
<?php } ?>
<?php } ?>
	<?php if($loadrecentpages == true) { ?>	
	javascript:ajaxpage('home_recent_pages.php', 'homeRecentPages');

<?php } ?>


}

</script>


<script>
window.onload=loadAll;
window.onunload = function(){};
</script>

<?php if($show_thumbnails==true) { ?>

<script>
 $(document).ready(function(){
	$("#thumbPageID").val('<?php print $page;?>');
	$(window).resize(function() {
	resizeImgToContainer($("#photoview-"+$("#currentViewPhoto").val()),'viewPhoto');
	});

});

	var texturl=location.href;
	resT = texturl.indexOf("page=");
	resI = texturl.indexOf("image=");
			if(resI > 0) {

				var getHash = location.href.split("image=");
				if(getHash[1]==null) {
					var goImage = 1;
				} else {
					var goImage = getHash[1];
				}
				var stay_pic_id = goImage;
				 var position = $("#thumb-"+goImage).attr("pos");
				 var hh = $("#thumb-"+goImage).attr("hh");
				 var ww = $("#thumb-"+goImage).attr("ww");
				var gal_id;

				viewPhoto(goImage,gal_id,ww,hh,position);
			}

	closephoto = texturl.indexOf("thumbs");
	if((closephoto < 0)&&(resI < 0) ){ 
		 window.location.href = '#page=thumbs';
	}
</script>

<script>
 $(document).ready(function(){
	$("#thumbPageID").val('<?php print $page;?>');
	$(window).resize(function() {
	resizeImgToContainer($("#photoview-"+$("#currentViewPhoto").val()),'viewPhoto');
	});

});

	if ("onhashchange" in window) { // event supported?
		window.onhashchange = function () {
			var texturl=location.href;
			resT = texturl.indexOf("page=");
			resI = texturl.indexOf("image=");
			closephoto = texturl.indexOf("thumbs");

			if(closephoto > 0) { 
				closeBGContainer();
			}
			if(resT > 0) {
				var getHash = location.href.split("page=");
				if(getHash[1]==null) {
					var goPage = 1;
				} else {
					var goPage = getHash[1];
				}
			}
			if(resI > 0) {

				var getHash = location.href.split("image=");
				if(getHash[1]==null) {
					var goImage = 1;
				} else {
					var goImage = getHash[1];
				}
				var stay_pic_id = goImage;
				 var position = $("#thumb-"+goImage).attr("pos");
				 var hh = $("#thumb-"+goImage).attr("hh");
				 var ww = $("#thumb-"+goImage).attr("ww");
				var gal_id;

				viewPhoto(goImage,gal_id,ww,hh,position);
			}
		}
	}
	else { // event not supported:
		var storedHash = window.location.hash;

		window.setInterval(function () {
			var texturl=location.href;
			resT = texturl.indexOf("page=");
			resI = texturl.indexOf("image=");
			closephoto = texturl.indexOf("thumbs");
			if(closephoto > 0) { 
				closeBGContainer();
			}
			if (window.location.hash != storedHash) {
				if(resT > 0) {

					storedHash = window.location.hash;
		  //          hashChanged(storedHash);
					var getHash = storedHash.split("page=");
					if(getHash[1]==null) {
						var goPage = 1;
					} else {
						var goPage = getHash[1];
					}
				}
				

				if(resI > 0) {
					storedHash = window.location.hash;

					var getHash = storedHash.split("image=");
					if(getHash[1]==null) {
						var goImage = 1;
					} else {
						var goImage = getHash[1];
					}
					var stay_pic_id = goImage;
					 var position = $("#thumb-"+goImage).attr("pos");
				 var hh = $("#thumb-"+goImage).attr("hh");
				 var ww = $("#thumb-"+goImage).attr("ww");
				var gal_id;

				viewPhoto(goImage,gal_id,ww,hh,position);

				}

			}




		}, 100);
	}
</script>
<?php  } ?>
</div>
</HTML>
