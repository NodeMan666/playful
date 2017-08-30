<?php
if($_POST['submitit']=="yes") { 

	if(is_array($_REQUEST['e_tags'])) { 
		foreach($_REQUEST['e_tags'] AS $id => $val) {
			$x++;
			$tag = doSQL("ms_photo_keywords", "*", "WHERE id='".$id."' ");
			if($x > 1) { 
				$tag_keys .= ",".$tag['id'];
			} else { 
				$tag_keys .= $tag['id'];
			}
		//	insertSQL("ms_tag_connect", "tag_tag_id='".$id."', tag_date_id='".$date['date_id']."' ");
		}
	}

	if($_REQUEST['cat_under'] > 0) {
		$up_folder = doSQL("".cat_table."", "*", "WHERE cat_id='".$_REQUEST['cat_under']."' ");
		if(empty($up_folder['cat_under_ids'])) {
			$cat_under_ids = ("".$up_folder['cat_id']."");
		} else {
			$cat_under_ids = ("".$up_folder['cat_under_ids'].",".$up_folder['cat_id']."");
		}
	}




	$_REQUEST['cat_default_status'] = "2";
	$_REQUEST['blog_photo_file'] = "pic_large";


	if(($_REQUEST['cat_type'] == "store") || ($_REQUEST['cat_type'] == "booking") == true){ 
		$_REQUEST['cat_order_by'] = "pageorder";
	} else { 
		$_REQUEST['cat_order_by'] = "date";
	}

	if($_REQUEST['cat_type'] == "registry") { 
		$_REQUEST['blog_photo_file'] = "pic_pic";
		$cat_reg_amounts = $_REQUEST['cat_reg_amounts'];
		$cat_reg_default_text = "Welcome to our registry! If you wish to give us a gift, we would love it to be for our photography so we can cherish these memories in photos and artwork forever.";
		$cat_text_under_subs = "To give a gift to this registry, select an amount below and optionally leave a message to be shown in the guestbook below.";

	}


	$cat_id = insertSQL("".cat_table."", "cat_name='".addslashes(stripslashes($_REQUEST['cat_name']))."' , 
	cat_text='".addslashes(stripslashes($_REQUEST['cat_text']))."' , 
	cat_text_under_content='".addslashes(stripslashes($_REQUEST['cat_text_under_content']))."' , 
	cat_text_under_subs='".addslashes(stripslashes($cat_text_under_subs))."' , 
	cat_billboard='".$_REQUEST['cat_billboard']."' , 
	cat_status='1' , 
	cat_under='".$_REQUEST['cat_under']."', 
	cat_under_ids='".$cat_under_ids."', 
	cat_password='".addslashes(stripslashes($_REQUEST['cat_password']))."', 
	cat_no_show='".addslashes(stripslashes($_REQUEST['cat_no_show']))."', 
	cat_no_show_posts='".addslashes(stripslashes($_REQUEST['cat_no_show_posts']))."' , 
	cat_theme='".addslashes(stripslashes($_REQUEST['cat_theme']))."', 
	cat_list_sub_cat_posts='".$_REQUEST['cat_list_sub_cat_posts']."', 
	cat_billboard_posts='".$_REQUEST['cat_billboard_posts']."', 
	cat_type='".$_REQUEST['cat_type']."', 
	cat_pic_tags='$tag_keys' , 
	cat_show_sub_cats_page='1', 
	cat_layout='".$_REQUEST['cat_layout']."' , 
	cat_page_layout='".$_REQUEST['cat_page_layout']."', 
	cat_cat_layout='3', cat_per_page='".$_REQUEST['cat_per_page']."', 
	cat_watermark='".$_REQUEST['cat_watermark']."' , 
	cat_logo='".$_REQUEST['cat_logo']."', 
	cat_order='".$_REQUEST['cat_order']."', 
	cat_comments='".$_REQUEST['cat_comments']."', 
	cat_next_prev='".$_REQUEST['cat_next_prev']."', 
	cat_expire_days='".$_REQUEST['cat_expire_days']."', 
	cat_show_title='1', 
	cat_max_width='".$_REQUEST['cat_max_width']."',
	cat_private_button='".$_REQUEST['cat_private_button']."', 
	cat_private_page='".$_REQUEST['cat_private_page']."', 
	cat_order_by='".$_REQUEST['cat_order_by']."', 
	cat_default_private='".$_REQUEST['cat_default_private']."', 
	cat_default_status='".$_REQUEST['cat_default_status']."', 
	cat_eb_days='14', 
	cat_reg_amounts='".$cat_reg_amounts."', 
	cat_auto_populate='".$_REQUEST['cat_auto_populate']."', 
	cat_reg_no_address='1', 
	cat_reg_default_text='".$cat_reg_default_text."' ");
	createCategory($cat_id);


	insertSQL("ms_defaults", "
	def_type='".$_REQUEST['def_type']."',
	thumb_file='".$_REQUEST['thumb_file']."',
	disable_filename='".$_REQUEST['disable_filename']."',
	thumb_style='".$_REQUEST['thumb_style']."',
	social_share='".$_REQUEST['photos_social_share']."',
	allow_favs='".$_REQUEST['allow_favs']."', 
	enable_compare='".$_REQUEST['enable_compare']."',
	photo_search='".$_REQUEST['photo_search']."',
	disable_icons='".$_REQUEST['disable_icons']."',
	blog_seconds='4',
	blog_kill_side_menu='0',
	stacked_width='300', 
	stacked_margin='4', 

	transition_time='600',
	blog_photo_file='".$_REQUEST['blog_photo_file']."',
	slideshow_fixed_height='1',
	jthumb_height='250',
	jthumb_margin='4',

	disable_controls='".$_REQUEST['disable_controls']."', 
	blog_slideshow_auto_start='".$_REQUEST['blog_slideshow_auto_start']."',



	def_cat_id='".$cat_id."' ");	
		
	if($_REQUEST['cg'] == "1") { 
		updateSQL("ms_calendar", "date_feature_cat='".$cat_id."', date_feature_auto_populate='1', date_feature_limit='20' WHERE page_home='1' ");
		if($setup['sytist_hosted'] == true) { 
			updateSQL("ms_blog_categories", "cat_gallery_exclusive='1', cat_default_status='1' WHERE cat_id='".$cat_id."' ");
			updateSQL("ms_photo_setup","gallery_favicon='1' ");
		}
	}

	updateSiteMap();
	session_write_close();
	header("location: index.php?do=news&cat_type=".$_REQUEST['cat_type']."&date_cat=$cat_id&new=1");

	exit();
}
?>

<style>
.wizlabel { font-size: 24px;  padding: 8px; color: #000000; } 
.wiztitle h2 { color: #999999; }
.wizrow { padding: 8px; } 
.wizinfo { padding: 4px 8px; } 
.size21 { font-size: 21px; } 
.size17 { font-size: 17px; color: #111111; } 
.wizoptions { display: none; } 
</style>
<script>

function deselectsection(name) { 
	$("#sectionoptions").slideUp(200, function() { 
		$("#sectionlist").slideDown(400);
	});

}


function selectsection(name) { 
	$("#sectionoptions").attr("data-new-section",name);
	$("#cattypename").html($("#"+name).attr("data-cat-type-name"));
	$("#sectionurl").html($("#"+name).attr("data-cat-name").toLowerCase());
	$("#layout-descr").html($("#"+name).attr("layout-descr"));
	$("#layout-descr-more").html($("#"+name).attr("layout-descr-more"));
	$(".wiznav").hide();
	$("."+$("#"+name).attr("nav-class")).show();

	$("#cat_name").val($("#"+name).attr("data-cat-name"));
	$("#cat_layout").val($("#"+name).attr("cat_layout"));
	$("#disable_filename").val($("#"+name).attr("disable_filename"));
	$("#def_type").val($("#"+name).attr("def_type"));
	$("#cat_expire_days").val($("#"+name).attr("cat_expire_days"));

	$("#slideshow_stop_end").val($("#"+name).attr("slideshow_stop_end"));
	$("#disable_controls").val($("#"+name).attr("disable_controls"));
	$("#blog_slideshow_auto_start").val($("#"+name).attr("blog_slideshow_auto_start"));



	$("#"+$("#"+name).attr("thumbnails")).prop("checked", true)
	$("#"+$("#"+name).attr("passwordprotect")).prop("checked", true)

	if($("#"+name).attr("photos_social_share") == "true") { 
		$("#photos_social_share").prop("checked",true);
	} else { 
		$("#photos_social_share").prop("checked",false);
	}

	if($("#"+name).attr("allow_favs") == "true") { 
		$("#allow_favs").prop("checked",true);
	} else { 
		$("#allow_favs").prop("checked",false);
	}

	if($("#"+name).attr("photo_search") == "true") { 
		$("#photo_search").prop("checked",true);
	} else { 
		$("#photo_search").prop("checked",false);
	}

	if($("#"+name).attr("enable_compare") == "true") { 
		$("#enable_compare").prop("checked",true);
	} else { 
		$("#enable_compare").prop("checked",false);
	}

	if($("#"+name).attr("cat_watermark") == "true") { 
		$("#cat_watermark").prop("checked",true);
	} else { 
		$("#cat_watermark").prop("checked",false);
	}



	$("#cat_type").val($("#"+name).attr("cat_type"));
	$("#cat_page_layout").val($("#"+name).attr("cat_page_layout"));
	$("#cat_max_width").val($("#"+name).attr("cat_max_width"));


	$("#sectionlist").slideUp(200, function() { 
		$("#sectionoptions").slideDown(400);
	});


	$("#cat_layout").children('option').hide();

	var substr = $("#"+name).attr("cat_layout_options").split(',');

	var i;
	for (i = 0; i < substr.length; ++i) {
		if(substr[i] !== "") { 
			$("#cat_layout").children("option[value^="+substr[i]+"]").show()
		}
		// do something with `substr[i]`
	}
	previewlayout();
}
 $(document).ready(function(){
	$('#cat_name').on('input',function(e){
		newurl = $("#cat_name").val().toLowerCase();
		newurl = newurl.replace(".", "");
		newurl = newurl.replace(" ", "-");
		newurl = newurl.replace("'", "-");
		newurl = newurl.replace('"', "-");
		 $("#sectionurl").html(newurl);
		});
 });

 	function previewlayout() { 
		$(".layoutpreview").hide(10, function() { 
			$(".L"+$("#cat_layout").val()).show();
		});
	}

	function selectwizsection(show,hide) { 
		$("#"+hide).slideUp(200, function() { 
			$("#"+show).slideDown(400);
		});

	}
	function thumbfile(f,disable_icons,disable_filename) { 
		$("#thumb_file").val(f);
		$("#disable_icons").val(disable_icons);
		$("#disable_filename").val(disable_filename);
	}

	function privategals(cat_private_button,cat_private_page) { 
		$("#cat_private_button").val(cat_private_button);
		$("#cat_private_page").val(cat_private_page);

	}

$(document).on("keypress", "form", function(event) { 
    return event.keyCode != 13;
});
</script>
<?php if($_REQUEST['cg'] == "1") { ?>
<script>
 $(document).ready(function(){
	selectsection('clientphotos');
});
</script>
<?php } ?>



<?php
define("cat_table", "ms_blog_categories");
?>
<?php if((empty($_REQUEST['cat_id']))&&($_REQUEST['cat_under'] <=0) ==true)  { $top_section = true;  } ?>
<?php if((!empty($_REQUEST['cat_id']))&&($cat['cat_under'] == "0" ) ==true) { $top_section = true;  } ?>

<?php 	$cat = doSQL("".cat_table."", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' "); ?>

<form method="post" name="catwiz" action="index.php"    onSubmit="return checkForm();">

<div id="sectionoptions" class="hide" data-new-section="">
	<div class="wizrow wiztitle center"><h2>Creating a section for: <span  id="cattypename"></span></h2></div>









	<div id="sectionname" class="center">
		<div class="wizlabel">Enter the name for this section</div>
		<div class="wizrow" ><input type="text" id="cat_name" name="cat_name" value="<?php print $cat['cat_name'];?>" size="20" class="inputtitle required center" style="font-size: 32px;"></div>
		<?php if($setup['sytist_hosted'] !== true) { ?><div class="wizrow size21">This is also part of the URL: <?php print $setup['url'].$setup['temp_url_folder'];?>/<span id="sectionurl"></span></div><?php } ?>
		<div class="wizrow buttons size21 storenav gallerynav wiznav standardnav blognav"><a href="" onclick="deselectsection(); return false;">Back</a> &nbsp; <a href="" onclick="selectwizsection('cat_layout_select','sectionname'); return false;" class="submit">Next</a></div>

		<div class="wizrow buttons wiznav buynav center proofingnav registrynav"><a href=""  onclick="deselectsection(); return false;">Back</a> &nbsp;		<input type="submit" name="submit" value="Finish & Create Section" id="submitButton"  class="submit"></div>
		<div class="wiznav buynav">
		<p class="size21">A Buy page will list out the products in a section where they can be purchased right from the section page like in the screen shot below </p>
		<p>You will create a new "Page" for each product you want to offer.</p>
		<p><img src="graphics/layouts/product-list-with-add-to-cart.jpg" style="max-width: 100%;"></p></div>
	</div>









	<div id="cat_layout_select" class="hide">
	<div class="wizlabel center" id="layout-descr"></div>


	<div class="wizrow buttons wiznav gallerynav center size21"><a href=""  onclick="selectwizsection('sectionname','cat_layout_select'); return false;">Back</a> &nbsp; <a href="" onclick="selectwizsection('cat_thumbnail_select','cat_layout_select'); return false;" class="submit">Next</a></div>
	
	<div class="wizrow buttons wiznav storenav center standardnav blognav"><a href=""  onclick="selectwizsection('sectionname','cat_layout_select'); return false;">Back</a> &nbsp;		<input type="submit" name="submit" value="Finish & Create Section" id="submitButton"  class="submit"></div>

	

	<div class="wizrow center" id="layout-descr-more"></div>
	<div class=" pc center">Preview & Select Layout</div>
	<div class="pc center">
	<select name="cat_layout" id="cat_layout" onchange="previewlayout();" class="inputtitle">
	<?php $layouts = whileSQL("ms_category_layouts", "*", "WHERE layout_type='listing' ORDER BY layout_name ASC ");
	while($layout = mysqli_fetch_array($layouts)) { ?>
	<option value="<?php print $layout['layout_id'];?>" class="<?php print $layout['layout_name'];?>"><?php print $layout['layout_name'];?></option>
	<?php } ?>
	</select>
	</div>
		<div class="pc center">
		<img src="graphics/layouts/stacked.jpg" style="max-width: 100%;" class="layoutpreview L102 hide">
		<img src="graphics/layouts/blog-1-photo.jpg" style="max-width: 100%;" class="layoutpreview L21 hide">
		<img src="graphics/layouts/onphoto.jpg" style="max-width: 100%;" class="layoutpreview L3 hide">
		<img src="graphics/layouts/product-list-with-add-to-cart.jpg" style="max-width: 100%;" class="layoutpreview L18 hide">
		<img src="graphics/layouts/standard.jpg" style="max-width: 100%;" class="layoutpreview L1 hide">
		<img src="graphics/layouts/thumbnails.jpg" style="max-width: 100%;" class="layoutpreview L4 hide">
		<img src="graphics/layouts/thumbnails-prices.jpg" style="max-width: 100%;" class="layoutpreview L19 hide">
		<img src="graphics/layouts/title-only.jpg" style="max-width: 100%;" class="layoutpreview L100 hide">
		</div>
	</div>


	<div id="cat_thumbnail_select" class="hide">
		<div class="wizlabel center">Thumbnail Display</div>
		<div class="wizrow center">Select below how you want your photo thumbnails to display in the galleries.</div>
		<div>&nbsp;</div>

		<div class="wizrow buttons  center size21"><a href=""  onclick="selectwizsection('cat_layout_select','cat_thumbnail_select'); return false;">Back</a> &nbsp; <a href="" onclick="selectwizsection('cat_gallery_settings','cat_thumbnail_select'); return false;" class="submit">Next</a></div>
		<div>&nbsp;</div>


		<div style="float: left; width: 33%;" class="center">
			<div class="pc"><input name="thumb_style" id="thumbtypejustified" value="0"  type="radio"  onchange="thumbfile('pic_pic','1','0');">	<label for="thumbtypejustified" style="font-size: 21px; color: #000000;">Justified</label></div>
			<div class="pc"><label for="thumbtypejustified"><img src="graphics/thumbs-justified.jpg" border="0" style="width: 100%; ,max-width: 800px;"></label></div>
		</div>
		<div style="float: left; width: 33%;" class="center">
			<div class="pc"><input name="thumb_style" id="thumbtypestacked" value="2" type="radio" checked  onchange="thumbfile('pic_pic','1','1');">		<label for="thumbtypestacked" style="font-size: 21px; color: #000000;">Stacked</label></div>
			<div class="pc"><label for="thumbtypestacked"><img src="graphics/thumbs-stacked.jpg" style="width: 100%; ,max-width: 800px;"border="0"></label></div>
		</div>
		<div style="float: left; width: 33%;" class="center">
			<div class="pc"> <input name="thumb_style" id="thumbtypestandard" value="1"  type="radio"  onchange="thumbfile('pic_th','0','0');">		<label for="thumbtypestandard" style="font-size: 21px; color: #000000;">Standard</label></div>
			<div class="pc"><label for="thumbtypestandard"><img src="graphics/thumbs-standard.jpg" border="0" style="width: 100%; ,max-width: 800px;"></label></div>
		</div>
	</div>


	<div id="cat_gallery_settings" class="hide">
		<div class="wizlabel">Additional Gallery Options</div>
		<div class="wizrow size17">These are default options when creating new galleries and can be changed at any time and changed on a per gallery basis.</div>
		<div class="underlinelabel">Expire Galleries?</div>
		<div class="underline">
			Automatically expire galleries after <input type="text" name="cat_expire_days" id="cat_expire_days" value="30" size="2"> days. If you don't want galleries to expire, change to 0. The expiration date can be changed at any time.
		</div>
		<div>&nbsp;</div>

		<div class="underlinelabel">Password Protect Galleries</div>
		<div class="underlinespacer">Do you want to password protect galleries? If so, a random password will be generated automatically when a gallery is created which you can change at any time.</div>
			<div class="underline hidenew">
			<input name="cat_default_private" id="private0" value="0" checked="" type="radio"  onchange="privategals('0','0');"> <label for="private0">No</label>
			</div>
			<div class="underline hidenew">
			<input name="cat_default_private" id="private1" value="1" type="radio"  onchange="privategals('1','0');"> <label for="private1">Yes - Display in gallery list in this section on the website then ask for password when accessed</label>
			</div>
			<div class="underline hidenew">
			<input name="cat_default_private" id="private2" value="2" type="radio"  onchange="privategals('0','1');"> <label for="private2">Yes - But do not display in this section on the website. Selecting this you would want to email the direct link to the customer and then they will enter the password. </label>
			</div>
	<div>&nbsp;</div>
		<div class="underline"><input name="photos_social_share" id="photos_social_share" value="1" type="checkbox" > <label for="photos_social_share">Enable social share of individual photos</label></div>
		<div class="underline"><input class="checkbox" name="allow_favs" id="allow_favs" value="1"  type="checkbox"> <label for="allow_favs">Enable Add to Favorites to allow customers to add photos to their favorites</label> </div>
		<div class="underline"><input class="checkbox" name="photo_search" id="photo_search" value="1" type="checkbox"> <label for="photo_search">Enable photo search to allow customers to search for photos by keyword within a gallery.</label> </div>
		<div class="underline"><input class="checkbox" name="enable_compare" id="enable_compare" value="1"  type="checkbox"> <label for="enable_compare">Enable compare photos where customers can select photos to compare and compare side by side</label> </div>
		<div>&nbsp;</div>

			<div class="underline"><input name="cat_watermark" id="cat_watermark" value="1" type="checkbox" checked> <label for="cat_watermark">Autocheck to watermark photos when uploading photos to galleries in this section</label></div>
			<!-- <div class="underline"><input name="cat_logo" id="cat_logo" value="1" type="checkbox"> <label for="cat_logo">Autocheck to add a logo photos when uploading photos to galleries in this section</label></div> -->
			<div class="underline">You can upload your own watermark file in Settings -> Watermarking and the options can be changed during upload.</div>

		<input type="hidden" name="do" value="news">
		<input type="hidden" name="action" value="editCategory">
		<input type="hidden" name="submitit" value="yes">
		<input type="hidden" name="cg" value="<?php print $_REQUEST['cg'];?>">
		<?php ############# Hidden values ############# ?>

		<input type="hidden" name="cat_max_width" id="cat_max_width" value="">
		<input type="hidden" name="cat_page_layout" id="cat_page_layout" value="">
		<input type="hidden" name="cat_type" id="cat_type" value="">
		<input type="hidden" name="cat_per_page" id="cat_per_page" value="20">
		<input type="hidden" name="cat_auto_populate" id="cat_auto_populate" value="1">
		<input type="hidden" name="thumb_file" id="thumb_file" value="pic_pic">
		<input type="hidden" name="def_type" id="def_type" value="">
		<input type="hidden" name="disable_filename" id="disable_filename" value="">
		<input type="hidden" name="disable_icons" id="disable_icons" value="">
		<input type="hidden" name="cat_private_page" id="cat_private_page" value="">
		<input type="hidden" name="cat_private_button" id="cat_private_button" value="">

		<input type="hidden" name="slideshow_stop_end" id="slideshow_stop_end" value="">
		<input type="hidden" name="disable_controls" id="disable_controls" value="">
		<input type="hidden" name="blog_slideshow_auto_start" id="blog_slideshow_auto_start" value="">




<textarea name="cat_reg_amounts" id="cat_reg_amounts" rows="30" cols="20" style="display: none;">
10
20
25
50
75
100
125
150
175
200
250
300
350
400
450
500</textarea>






		<div class="wizrow buttons"><a href=""  onclick="selectwizsection('cat_thumbnail_select','cat_gallery_settings'); return false;">Back</a> &nbsp; 
		
		<input type="submit" name="submit" value="Finish & Create Section" id="submitButton"  class="submit">
		</div>

	</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>

</div>
</form>


<div id="sectionlist">
<div id="pageTitle" class="center">What type of section would you like to create?</div>
<div class="pc size17 center">Sections are basically top level categories which you create content in (like pages & galleries). <br>The section determines which features and functions are available for the content in the section.  
</div>	<div>&nbsp;</div>
<div class="p50 left">
	<div style="padding: 16px;">
	<div class="underline" id="clientphotos"  data-cat-name="Clients" cat_type="clientphotos" data-cat-type-name="Client Gallery / Selling Photos" cat_layout="3" cat_layout_options="3,4,100,102" data-nav="" cat_page_layout="22" cat_per_page="20" def_type="gallery" disable_filename="0" 
		cat_expire_days="30" 
		layout-descr="Choose the layout style of how you want the gallery listing displayed on the website" 
		layout-descr-more="Below is a preview of the layout, but the exact colors, fonts, etc ... depend on the theme you are using. This can be changed at any time by editing this section." 
		nav-class="gallerynav" 
		thumbnails="thumbtypestacked"  
		passwordprotect="private1" 
		photos_social_share="true" 
		allow_favs="true" 
		photo_search="false" 
		enable_compare="true" 
		cat_watermark="true" 
		slideshow_stop_end="0" 
		disable_controls="0" 
		blog_slideshow_auto_start="0" 
	>
		<div class="pc"><h2><a href="" onclick="selectsection('clientphotos'); return false;">Client Galleries & Selling Photos</a></h2></div>
		<div class="pc size17">Create galleries and upload photos to those galleries for people to purchase photos. Also select this for client galleries and not purchasing photos.</div>
	</div>
	<div>&nbsp;</div>
	<div class="underline" id="albums" data-cat-name="Galleries" 
		data-cat-type-name="Photo Galleries"  
		cat_type="standard" 
		cat_layout="3" 
		cat_layout_options="3,4,100,102"  
		cat_page_layout="23" 
		cat_per_page="20" 
		def_type="gallery" 
		disable_filename="0" 
		cat_expire_days="0" 
		thumbnails="thumbtypestacked" 
		layout-descr="Choose the layout style of how you want the gallery listing displayed on the website"
		layout-descr-more="Below is a preview of the layout, but the exact colors, fonts, etc ... depend on the theme you are using. This can be changed at any time by editing this section."
		nav-class="gallerynav" 
		passwordprotect="private0" 
		photos_social_share="true" 
		allow_favs="false" 
		photo_search="false" 
		enable_compare="false" 
		cat_watermark="false" 
		slideshow_stop_end="0" 
		disable_controls="0" 
		blog_slideshow_auto_start="0" 

	>
		<div class="pc"><h2><a href="" onclick="selectsection('albums'); return false;">Photo Galleries</a></h2></div>
		<div class="pc size17">Create galleries to display your photos for sample photos or portfolios. There are no photo selling options.</div>
		</div>
	<div>&nbsp;</div>


	<div class="underline"  id="store" 
		data-cat-type-name="Store" 
		data-cat-name="Store"  
		cat_type="store" 
		cat_layout="19" 
		cat_layout_options="19,102,3,100" 
		cat_page_layout="11" 
		cat_per_page="20" 
		def_type="onpagewithminis" 
		disable_filename="1" 
		cat_expire_days="0" 
		thumbnails="thumbtypestacked" 
		passwordprotect="private0" 

		layout-descr="Choose the style of how you want the product  listing displayed on the website" 
		layout-descr-more="Below is a preview of the layout, but the exact colors, fonts, etc ... depend on the theme you are using. This can be changed at any time by editing this section."  
		nav-class="storenav" 
		photos_social_share="false" 
		allow_favs="false" 
		photo_search="false" 
		enable_compare="false" 
		cat_watermark="false" 
		slideshow_stop_end="0" 
		disable_controls="0" 
		blog_slideshow_auto_start="0" 

	
	>
		<div class="pc"><h2><a href="" onclick="selectsection('store'); return false;">Store</a></h2></div>
		<div class="pc size17">Create a store section to sell products other than photos like services, physical products & download products. You can also use this to pre-sell packages.</div>
	</div>
	<div class="clear"></div>
	<div>&nbsp;</div>


	<div class="underline"  id="buy" 
		data-cat-type-name="Buy Page" 
		data-cat-name="Buy"  
		cat_type="store" 
		cat_layout="18" 
		cat_layout_options="18" 
		cat_page_layout="11" 
		cat_per_page="20" 
		def_type="onpagewithminis" 
		disable_filename="1" 
		cat_expire_days="0" 
		thumbnails="thumbtypestacked" 
		layout-descr="Choose the style of how you want the product  listing displayed on the website" 
		layout-descr-more="Below is a preview of the layout, but the exact colors, fonts, etc ... depend on the theme you are using. This can be changed at any time by editing this section."  
		nav-class="buynav" 
		passwordprotect="private0" 
		photos_social_share="false" 
		allow_favs="false" 
		photo_search="false" 
		enable_compare="false" 
		cat_watermark="false" 
		slideshow_stop_end="0" 
		disable_controls="0" 
		blog_slideshow_auto_start="0" 

		>
		<div class="pc"><h2><a href="" onclick="selectsection('buy'); return false;">Buy Page</a></h2></div>
			<div class="pc size17">A Buy Page is like a Store section, except people will add to cart right from the main page instead of click on the product.</div>
		</div>
		<div class="clear"></div>
		<div>&nbsp;</div>

		</div>
	</div>

<div class="p50 left">
	<div style="padding: 16px;">

		<div class="underline"  id="proofing" 
			data-cat-type-name="Project Proofing" 
			data-cat-name="Proofs"  
			cat_type="proofing" 
			cat_layout="3" 
			cat_layout_options="3,4,100,102"  
			cat_page_layout="23" 
			cat_per_page="20" 
			def_type="gallery" 
			disable_filename="0" 
			cat_expire_days="0" 
			thumbnails="thumbtypestandard" 
			passwordprotect="private2" 
			layout-descr="Choose the style of how you want the product  listing displayed on the website" 
			layout-descr-more="Below is a preview of the layout, but the exact colors, fonts, etc ... depend on the theme you are using. This can be changed at any time by editing this section."  
			nav-class="proofingnav" 
			photos_social_share="false" 
			allow_favs="false" 
			photo_search="false" 
			enable_compare="false" 
			cat_watermark="true" 
			slideshow_stop_end="0" 
			disable_controls="0" 
			blog_slideshow_auto_start="0" 

			>
			<div class="pc"><h2><a href="" onclick="selectsection('proofing'); return false;">Project Proofing</a></h2></div>
			<div class="pc size17">This section is used to create projects and upload images to have your clients review them. They will have the option to Approve, Reject & Request revisions for each image in the project. </div>
		</div>
		<div class="clear"></div>
		<div>&nbsp;</div>


		<div class="underline"  id="registry" 
			data-cat-type-name="Registry" 
			data-cat-name="Registry"  
			cat_type="registry" 
			cat_layout="100" 
			cat_layout_options="100"  
			cat_page_layout="101" 
			cat_per_page="20" 
			def_type="nextprevious" 
			disable_filename="1" 
			cat_expire_days="0" 
			thumbnails="thumbtypestandard" 
			passwordprotect="private0" 
			layout-descr="Choose the style of how you want the product  listing displayed on the website" 
			layout-descr-more="Below is a preview of the layout, but the exact colors, fonts, etc ... depend on the theme you are using. This can be changed at any time by editing this section."  
			nav-class="registrynav" 
			photos_social_share="false" 
			allow_favs="false" 
			photo_search="false" 
			enable_compare="false" 
			cat_watermark="false" 
			slideshow_stop_end="1" 
			disable_controls="1" 
			blog_slideshow_auto_start="1" 
			>
			<div class="pc"><h2><a href="" onclick="selectsection('registry'); return false;">Registry</a></h2></div>
			<div class="pc size17">Create registries for your clients (like  a wedding registry) where people can add money to clients accounts.</div>
		</div>
		<div class="clear"></div>
		<div>&nbsp;</div>





		<?php 
		$listlayout = doSQL("ms_category_layouts", "*", "WHERE layout_id_name='bookinglisting' ");
		$pagelayout =  doSQL("ms_category_layouts", "*", "WHERE layout_id_name='bookingpage' ");
		?>
		<div class="underline"  id="booking" 
			data-cat-type-name="Booking Calendar" 
			data-cat-name="Services"  
			cat_type="booking" 
			cat_max_width="1024" 

			cat_layout="<?php print $listlayout['layout_id'];?>" 
			cat_layout_options="<?php print $listlayout['layout_id'];?>" 
			cat_page_layout="<?php print $pagelayout['layout_id'];?>" 
			cat_per_page="20" 
			def_type="onpagewithminis" 
			disable_filename="1" 
			cat_expire_days="0" 
			thumbnails="thumbtypestacked" 
			passwordprotect="private0" 

			layout-descr="Choose the style of how you want the product  listing displayed on the website" 
			layout-descr-more="Below is a preview of the layout, but the exact colors, fonts, etc ... depend on the theme you are using. This can be changed at any time by editing this section."  
			nav-class="registrynav" 
			photos_social_share="false" 
			allow_favs="false" 
			photo_search="false" 
			enable_compare="false" 
			cat_watermark="false" 
			slideshow_stop_end="1" 
			disable_controls="1" 
			blog_slideshow_auto_start="1" 
			>
			<div class="pc"><h2><a href="" onclick="selectsection('booking'); return false;">Booking Calendar</a></h2></div>
			<div class="pc size17">Create a section to offer services for people to book integrated into the <a href="index.php?do=booking">calendar</a> </div>
		</div>
		<div class="clear"></div>
		<div>&nbsp;</div>





		<div class="underline"  id="standard" 
			data-cat-type-name="Standard Pages" 
			data-cat-name="Information"  
			cat_type="standard" 
			cat_layout="1" 
			cat_layout_options="1,21,3,4,100,102"  
			cat_page_layout="9" 
			cat_per_page="20" 
			def_type="gallery" 
			disable_filename="1" 
			cat_expire_days="0" 
			thumbnails="thumbtypestacked" 
			passwordprotect="private0" 
			layout-descr="Choose the style of how you want the pages listed on the website" 
			layout-descr-more="Below is a preview of the layout, but the exact colors, fonts, etc ... depend on the theme you are using. This can be changed at any time by editing this section."  
			nav-class="standardnav" 
			photos_social_share="false" 
			allow_favs="false" 
			photo_search="false" 
			enable_compare="false" 
			cat_watermark="false" 
			slideshow_stop_end="0" 
			disable_controls="0" 
			blog_slideshow_auto_start="0" 
			>
			<div class="pc"><h2><a href="" onclick="selectsection('standard'); return false;">Standard Pages</a></h2></div>
			<div class="pc size17">Standard pages within the section.</div>
		</div>
		<div class="clear"></div>
		<div>&nbsp;</div>



		<div class="underline"  id="blog" 
			data-cat-type-name="Blog" 
			data-cat-name="Blog"  
			cat_type="standard" 
			cat_layout="11" 
			cat_layout_options="1,21,3,4,100,102"  
			cat_page_layout="10" 
			cat_per_page="20" 
			def_type="standardlist" 
			disable_filename="1" 
			cat_expire_days="0" 
			thumbnails="thumbtypestacked" 
			passwordprotect="private0" 
			layout-descr="Choose the style of how you want the posts listed on the website" 
			layout-descr-more="Below is a preview of the layout, but the exact colors, fonts, etc ... depend on the theme you are using. This can be changed at any time by editing this section."  
			nav-class="blognav" 
			photos_social_share="false" 
			allow_favs="false" 
			photo_search="false" 
			enable_compare="false" 
			cat_watermark="false" 
			slideshow_stop_end="0" 
			disable_controls="0" 
			blog_slideshow_auto_start="0" 
			>
			<div class="pc"><h2><a href="" onclick="selectsection('blog'); return false;">Blog</a></h2></div>
			<div class="pc size17">Create a blog section to post photos, news, updates, etc...</div>
		</div>
		<div class="clear"></div>
		<div>&nbsp;</div>
	</div>
</div>
</div>


<div class="clear"></div>
<div>&nbsp;</div>



<?php

function createCategory($cat_id) {
	global $site_setup,$setup;
	$cat = doSQL("".cat_table."", "*", "WHERE cat_id='".$cat_id."' ");
	$cat_id = $cat['cat_id'];
	$page_link = stripslashes(trim(strtolower($cat['cat_name'])));
	$page_link = strip_tags($page_link);
	$page_link = str_replace(" ","".$site_setup['sep_page_names']."",$page_link);
	$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);

	if($cat['cat_under'] > 0) {
		$up_folder = doSQL("".cat_table."", "*", "WHERE cat_id='".$cat['cat_under']."' ");
		$page_link = "".$up_folder['cat_folder']."/".$page_link;
	} else { 
		$page_link = "/".$page_link;
	}

	if(file_exists($setup['path']."".$setup['content_folder']."/".$page_link)) {
		$_SESSION['existing_folder'] = $page_link;
		$page_link = $page_link."".$site_setup['sep_page_names']."".$cat['cat_id']."";
		$_SESSION['cat_folder_exists'] = true;
	}



	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."".$setup['content_folder']."")), -4); 
if($parent_permissions == "0755") {
	$perms = 0755;
//	print "<li>A";
} elseif($parent_permissions == "0777") {
	$perms = 0777;
//	print "<li>B";
} else {
		$perms = 0755;
//	print "<li>C";
}
//print "<li>$parent_permissions<li>$page_link<li>$perms<li>";

	mkdir("".$setup['path']."".$setup['content_folder']."/$page_link", $perms);
	chmod("".$setup['path']."".$setup['content_folder']."/$page_link", $perms);
	updateSQL("".cat_table."", "cat_folder='".$page_link."' WHERE cat_id='".$cat_id."' ");
	print "Create: ".$setup['path']."".$setup['content_folder']."/".$page_link."/index.php";
//	copy("".$setup['path']."".$setup['content_folder']."/default.php", "".$setup['path']."".$setup['content_folder']."/".$page_link."/index.php");

	$fp = fopen("".$setup['path']."".$setup['content_folder']."/".$page_link."/index.php", "w");
	$add_path .="../";

	if(!empty($cat['cat_under_ids'])) { 
		$ids = explode(",",$cat['cat_under_ids']);
		foreach($ids AS $num) { 
			$add_path .="../";
		}
		$info =  "<?php\n\$date_cat_id = $cat_id; \n\$to_path = \"$add_path\"; \ninclude \"".$add_path."".$setup['inc_folder']."/main_index_include.php\";\n?>"; 

	} else { 
		$info =  "<?php\n\$date_cat_id = $cat_id; \n\$to_path = \"$add_path\"; \ninclude \"".$add_path."".$setup['inc_folder']."/main_index_include.php\";\n?>"; 
	}
	fputs($fp, "$info\n");
	fclose($fp);

//	exit();

}
?>

<?php

function getMultiCats($fn, $match) {
	global $dbcon;
	print "<select name=\"$fn\" id=\"$fn\" onChange=\"reqLayout();\">";
//	print "<option value=\"0\">Top Level";

	$resultt = @mysqli_query($dbcon,"SELECT * FROM ".cat_table." WHERE cat_under='0' ORDER BY cat_name ASC");
	if (!$resultt) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error"); 	exit();	}
	while ( $type = mysqli_fetch_array($resultt) ) {
	if($type["cat_id"] == $match) { $selected = "selected"; }
	print "<option value=\"".$type["cat_id"]."\" $selected class=fl style=\"font-weight: bold;\">".$type["cat_name"]."";
	unset($selected);
		$parent_id = $type["cat_id"];
			getSubsDrop2($fn, $match, $parent_id, $level, $sec_under);
	}
	print "</select>";
}

function getSubsDrop2($fn, $match, $parent_id, $level, $sec_under) {
	global $dbcon;
	$level++;
	$subs = @mysqli_query($dbcon,"SELECT *  FROM ".cat_table." WHERE cat_under='$parent_id' ORDER BY cat_name ASC");
	if (!$subs) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
	while($row = mysqli_fetch_array($subs)) {
		$sub_sec_id = $row["cat_id"];
		$sub_sec_name = $row["cat_name"];
		$sub_sec_folder = $row["cat_folder"];

		?>
		<option  value="<?php  print "$sub_sec_id"; ?>" <?php  if ($match == $sub_sec_id) { print "selected"; } ?>> <?php  
			while($dashes < $level) {
			print "----";
			$dashes++;
		}
		$dashes = 0;
		print "$sub_sec_name"; 

		$sub2=@mysqli_query($dbcon,"SELECT COUNT(*) AS how_many FROM ".cat_table." WHERE cat_under='$sub_sec_id'");
		if (!$sub2) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
		$row = mysqli_fetch_array($sub2);
		$how_many= $row["how_many"];
		if(!empty($how_many)) { 
			$parent_id = $sub_sec_id;
			getSubsDrop2($fn, $match, $parent_id, $level, $sec_under);
		}
	}
		$level = 1;
}
?>