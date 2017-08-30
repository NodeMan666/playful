<?php 
$path = "../../";
require "../w-header.php"; 
$date = doSQL("ms_calendar", "*, date_format(DATE_ADD(date_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS date_show_date,date_format(last_modified, '".$site_setup['date_format']." %h:%i %p')  AS last_modified", "WHERE date_id='".$_REQUEST['date_id']."' ");
if($_REQUEST['sub_id'] > 0) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
}
if($_REQUEST['action'] == "save") { 
	insertSQL("ms_gallery_free", "free_gallery='".$_REQUEST['date_id']."', free_sub='".$_REQUEST['sub_id']."', free_person='".$_REQUEST['free_person']."', free_product='".$_REQUEST['free_product']."' ");
	exit();
}
if($_REQUEST['action'] == "delete") { 
	deleteSQL("ms_gallery_free", "WHERE free_id='".$_REQUEST['free_id']."' ","1");
	exit();
}

?>
<script>
function savefree() { 
	if($("#free_person").val() == "") {
		alert("Select a person");
	} else if($("#free_product").val() == "") { 
		alert("Select a product");
	} else { 
		$.get("news/news-gallery-free-download.php?action=save&date_id="+$("#date_id").val()+"&sub_id="+$("#sub_id").val()+"&free_person="+$("#free_person").val()+"&free_product="+$("#free_product").val(), function(data) {
			galleryfreedownload($("#date_id").val(),$("#sub_id").val());
		});
	}
}
function deletefree(id) { 
	$.get("news/news-gallery-free-download.php?action=delete&free_id="+id, function(data) {
		galleryfreedownload($("#date_id").val(),$("#sub_id").val());
	});
}
</script>
<div class="pc"><h2><?php print $date['date_title'];?> <?php if($sub['sub_id'] > 0) { ?><br>> <b><?php print $sub['sub_name'];?></b><?php } ?></h2></div>
<div class="pc">This function allows you to assign customers to download photos for free from this gallery. When someone is assigned here, they can view the gallery and download the photos instantly. 
<?php if($sub['sub_id'] > 0) { ?>
<br><br>When assigning someone to a sub gallery, they will only be able to download from the assigned sub gallery.
<?php } ?>
<?php if(($sub['sub_id'] <= 0) && (countIt("ms_sub_galleries", "WHERE sub_date_id='".$date['date_id']."' ") > 0) == true) { ?>
<br><br>Assigning someone to a main gallery that has sub galleries, the customer will be able to download from all sub galleries. If you only want them to download from a sub gallery, assign them to that sub gallery by viewing the sub gallery first. 
<?php } ?>
</div>
<div class="p50 left">
	<div style="padding: 24px;">
	
	<div class="underlinelabel">Assign Someone To Download For Free</div>
		<div id="peopleselect" class="underline">
			<div>Select Customer </div>
			<div>
				<select name="free_person" id="free_person" class=" formfield">
				<option value="">Select</option>
				<?php $ps = whileSQL("ms_people", "*", "ORDER BY p_last_name ASC ");
				while($p = mysqli_fetch_array($ps)) { ?>
				<option value="<?php print $p['p_id'];?>" <?php if($book['book_account'] == $p['p_id']) { print "selected"; } ?> first_name="<?php print htmlspecialchars($p['p_name']);?>"  last_name="<?php print htmlspecialchars($p['p_last_name']);?>"  email="<?php print htmlspecialchars($p['p_email']);?>"  phone="<?php print htmlspecialchars($p['p_phone']);?>" ><?php print $p['p_last_name'].", ".$p['p_name'];?> (<?php print $p['p_email'];?>)</option>
				<?php } ?>
				</select>
			</div>
		<div><a href="" onclick="showHide('peoplesearch','book_account'); return false;">Search</a></div>
		<div id="peoplesearch" class="hide">
			<div class="p80 left">
			<input type="text" name="pq" id="pq" class="field100"></div>
			<div class="p20 center left"><a href="" onclick="searchpeople('peopleselect','free_person'); return false;">go</a></div>
			<div class="clear"></div>
		</div>
		</div>

	<div class="underline">
		<div>Select Download Product</div>
		<div>
		<select name="free_product" id="free_product">
		<option value="">Select</option>
		<?php $prods = whileSQL("ms_photo_products", "*", "WHERE pp_type='download' ORDER BY pp_name ASC ");
		while($prod = mysqli_fetch_array($prods)) { ?>
		<option value="<?php print $prod['pp_id'];?>"><?php print $prod['pp_name'];?> <?php if(!empty($prod['pp_internal_name'])) { print " (".$prod['pp_internal_name'].")"; } ?></option>
		<?php } ?>
		</select>
		</div>

	</div>
	<input type="hidden" id="date_id" value="<?php print $date['date_id'];?>">
	<input type="hidden" id="sub_id" value="<?php print $sub['sub_id'];?>">
	<div>&nbsp;</div>
	<div class="pc buttons center">
	<a href="" id="saveform" onclick="savefree('formfield'); return false;">Assign</a>
	</div>



	</div>
</div>

<div class="p50 left">
	<div style="padding: 24px;">
		<div class="underlinelabel">Assigned Free Downloads</div>
		<?php $frees = whileSQL("ms_gallery_free LEFT JOIN ms_people ON ms_gallery_free.free_person=ms_people.p_id LEFT JOIN ms_photo_products ON ms_gallery_free.free_product=ms_photo_products.pp_id", "*", "WHERE free_gallery='".$date['date_id']."' AND free_sub='".$sub['sub_id']."' ");
		if(mysqli_num_rows($frees) <= 0 ) { ?><div class="pc center">No customers have been selected</div><?php } ?>
		<?php 
		while($free = mysqli_fetch_array($frees)) { ?>
		<div class="underline">
			<div class="left p10"><a href="javascript:deletefree('<?php print $free['free_id'];?>');"  onClick="return confirm('Are you sure you want remove?'); return false;" class="the-icons icon-cancel tip" title="remove"></a></div>
			<div class="left p90">
			<div><a href="index.php?do=people&p_id=<?php print $free['p_id'];?>"><?php print $free['p_name'];?> <?php print $free['p_last_name'];?> <?php print $free['p_email'];?></a></div>
			<div><?php print $free['pp_name'];?> <?php if(!empty($free['pp_internal_name'])) { print " (".$free['pp_internal_name'].")"; } ?></div>
			</div>
			<div class="clear"></div>
		</div>


		<?php } ?>

	</div>
</div>

<div class="clear"></div>

<?php require "../w-footer.php"; ?>
