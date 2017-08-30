<?php if(($date['date_expire'] !== "0000-00-00")AND($date['date_expire'] < date("Y-m-d"))==true) { ?>
<h1><?php print $date['date_title'];?> <?php print _has_expired_;?></h1>
<div class="pc"><?php print nl2br(_expired_message_);?></div>
<?php } else { ?> 

<?php 
if(($date['cat_require_email'] > 0) &&(!customerLoggedIn())&&(!isset($_SESSION['office_admin_login'])) ==true){ 
	if(isset($_COOKIE['myemail'])) { 
		$cke = doSQL("ms_pre_register", "*", "WHERE reg_email='".$_COOKIE['myemail']."' AND reg_date_id='".$date['date_id']."' AND toview='1'  ");
		if(empty($cke['reg_id'])) { 
			require $setup['path']."/sy-inc/email_collect.php"; 
			$no_splash = true;
		}
	} else { 
		 require $setup['path']."/sy-inc/email_collect.php"; 
			$no_splash = true;
	}
}


 ?>
<?php if($date['splash_enable'] == "1") { ?>
<div id="splashtext" style="display: none;"><?php print $date['splash_text'];?><div>&nbsp;</div><div class="pc center"><a href="#" class="checkout"><?php print $date['splash_close'];?></a></div><div class="clear"></div></div>
<?php if(!is_array($_SESSION['splashes'])) { 
	$_SESSION['splashes'] = array();
}
if(!in_array($date['date_id'],$_SESSION['splashes'])) { 
array_push($_SESSION['splashes'],$date['date_id']);
?>
<script>
$(document).ready(function(){
	<?php if($no_splash !== true) { ?>
	window.location="#splash";
	<?php } ?>
});
</script>
<?php } ?>
<script>
      jQuery(window).hashchange(function() {
		  if(document.location.hash == "#" || document.location.hash == "") { 
			closesplash();
		  } else { 
			  hash = document.location.hash;
			  hash = hash.replace("#","");
			if(hash == "splash") { 
				getsplash();
			}
		  }
//        alert(document.location.hash);
      });


</script>

<?php } ?>


<?php
if(((empty($_REQUEST['view'])) || ($_REQUEST['view'] == "highlights")) == true) { 

	$eb = doSQL("ms_promo_codes", "*, date_format(DATE_ADD(code_end_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS exp_show", "WHERE code_date_id='".$date['date_id']."' AND (code_end_date>=CURDATE() OR code_end_date='0000-00-00') " );
	if(!empty($eb['code_id'])) { ?>
	<div id="ebmessage"><?php 
		
	$ebtext = str_replace("[DATE]",$eb['exp_show'],$eb['code_descr']);
	$diss = whileSQL("ms_promo_codes_discounts", "*", "WHERE dis_promo='".$eb['code_id']."'  ");
	while($dis = mysqli_fetch_array($diss)) { 
		$n++;
		if($dis['dis_to'] > 9999) { 
			$dis_to = _or_more_;
		} else { 
			$dis_to = "- ".showPrice($dis['dis_to']);
		}

		if($eb['code_discount_type'] == "flat") { 
			$eb_discounts .= " ".showPrice($dis['dis_from'])."  ".$dis_to."  ".showPrice($dis['dis_flat'])." "._discount_off_."";
		} else { 
			$eb_discounts .= " ".showPrice($dis['dis_from'])."  ".$dis_to."  ".$dis['dis_percent']."% "._discount_off_."";
		}
		if($n <= (mysqli_num_rows($diss) - 1)) { $eb_discounts .= ", "; } 
	}
	$ebtext = str_replace("[DISCOUNT_AMOUNT]",$eb_discounts,$ebtext);

	print $ebtext;

?>

</div>
<?php 

	}
	
} ?>

<?php
$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
?>
<?php 
if(!empty($_REQUEST['sub'])) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' AND sub_link='".$_REQUEST['sub']."' ");
	$ids = explode(",",$sub['sub_under_ids']);
	if(!empty($sub['sub_pass'])){ 
		$pass = $sub['sub_pass'];
		$sub_pass_id = $sub['sub_id'];
	} 
	if(empty($pass)) { 
		foreach($ids AS $val) { 
			if($val > 0) { 
				$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
				if(!empty($upsub['sub_pass'])) { 
					$pass = $upsub['sub_pass'];
					$sub_pass_id = $upsub['sub_id'];
				}
			}
		}
	}

	if(!empty($pass)){ 
		if(isset($_SESSION['office_admin_login'])) { 
			print "<div class=\"pc center\"><i>This is a password protected page but since you are logged into the admin you have direct access.</i></div>";
		} else if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) && ($person['p_id'] > 0) == true) { 

		} else { 
			if(!is_array($_SESSION['privateAccess'])) {
				$_SESSION['privateAccess'] = array();
			}
			if(customerLoggedIn()) { 
				$cka = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$date['date_id']."' AND mp_sub_id='".$sub_pass_id."' AND MD5(mp_people_id)='".$_SESSION['pid']."' "); 
				if(empty($cka['mp_id'])) { 
					include $setup['path']."/".$setup['inc_folder']."/password_protected_sub.php";
					galPassword($date['date_id'],$sub_pass_id);
					exit();
				} 
			} else { 
				if(!in_array("sub".$sub_pass_id,$_SESSION['privateAccess'])) {
					include $setup['path']."/".$setup['inc_folder']."/password_protected_sub.php";
					galPassword($date['date_id'],$sub_pass_id);
					exit();
				}
			}
		}
	}
}

if(($date['passcode_photos'] == "1") && (!empty($_REQUEST['passcode'])) && ($_SESSION['passcode_did'] !== $date['date_id'])== true) { 
	unset($_SESSION['passcode']);
	unset($_SESSION['passcode_did']);
	unset($_SESSION['passcode_sid']);
	header("location: index.php");
	session_write_close();
	exit();
}
if(($date['passcode_photos'] == "1") && (!empty($_REQUEST['passcode'])) && (!empty($_SESSION['passcode_sid']))== true) { 
	if($_SESSION['passcode_sid'] !== $sub['sub_id']) { 
		unset($_SESSION['passcode']);
		unset($_SESSION['passcode_did']);
		unset($_SESSION['passcode_sid']);
		header("location: index.php");
		session_write_close();
		exit();
	}
}

if($_REQUEST['passcodeclear'] == "1") { 
	unset($_SESSION['passcode']);
	unset($_SESSION['passcode_did']);
	unset($_SESSION['passcode_sid']);
	header("location: index.php");
	session_write_close();
	exit();
}

if(($date['passcode_photos'] == "1") && (empty($_REQUEST['passcode'])) == true) { 
	// include $setup['path']."/sy-inc/find_photos.php";
} else { 
	if(($date['blog_type'] == "gallery")==true){ 
		if(countIt("ms_blog_photos", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$sub['sub_id']."' ") > 0) { 
			$show_thumbnails = true;
		}
	}
}


if($date['page_layout'] > 0) { 
	$layout_id = $date['page_layout'];
} else { 
	if($bcat['cat_page_layout'] <=0) { 
		$topcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$top_section."' ");
		$layout_id = $topcat['cat_page_layout'];
	} else { 
		$layout_id = $bcat['cat_page_layout'];
	}
}
if(empty($_REQUEST['view'])) { 
	if($date['enable_time_blocks'] == "1") { 
		print "<div class=\"right\">";
		pageTimeBlocks();
		print "</div>";
	}
	if($date['photo_search'] == "1") { 
		pageSearchPhotos();
	}
}
if($layout_id <=0) { 
	$pagelayout = doSQL("ms_category_layouts", "*", "WHERE layout_type='page' AND layout_default='1' ");
} else { 
	$pagelayout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$layout_id."' ");
}
if(!empty($_REQUEST['previewLayout'])) { 
	$pagelayout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$_REQUEST['previewLayout']."' ");
}

if($date['date_gallery_exclusive'] == "1") { 
	if(!empty($ge['page_layout'])) { 
		$pagelayout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$ge['page_layout']."' ");
		if(!empty($pagelayout['layout_folder'])) { 
			include $setup['path']."/".$pagelayout['layout_folder']."/".$pagelayout['layout_file'];
		} else { 
			include $setup['path']."/".$setup['layouts_folder']."/".$pagelayout['layout_file'].""; 
		}
	} else { 
		include $setup['path']."/sy-inc/gallery.exclusive.layout.php";
	}
} else { 
	if(!empty($pagelayout['layout_folder'])) { 
		include $setup['path']."/".$pagelayout['layout_folder']."/".$pagelayout['layout_file'];
	} else { 
		include $setup['path']."/".$setup['layouts_folder']."/".$pagelayout['layout_file'].""; 
	}
}
?>
<?php 
if(($bcat['cat_comments'] == "1") && (empty($_REQUEST['view'])) == true) { 
	pageComments(); 
}
?>


<?php } ?>

	<?php if(!empty($date['date_aff_link'])) { ?>
	<div class="pageContent" style="text-align:center;"><h2 class="affLink"><?php print "<a href=\"/viewsite.php?site=".base64_encode($date['date_aff_link'])."\"  target=\"_blank\">".$date['date_aff_text'];?></a></h2></div>
	<?php } ?>
<div>&nbsp;</div>

<?php function socialShare() { 
	global $date,$setup,$bcat,$site_setup,$fb; 
	if($date['page_disable_fb'] !== "1") { 
		if($fb['share_type'] == "1") { ?><div id="socialShare"><?php include $setup['path']."/".$setup['inc_folder']."/share_icons.php"; ?><div class="clear"></div></div><?php } else { ?><div id="socialShare"><?php include $setup['path']."/".$setup['inc_folder']."/share.php"; ?><div class="clear"></div></div>
	<?php } ?>
<?php }
} ?>
<?php 
function pageTitle() { 
	global $date,$setup,$sub;
	
	/*
	if($sub['sub_id'] > 0) { 
		print "<a href=\"index.php\">";
	}
	if($date['page_title_no_show'] !== "1") { 
		if(!empty($date['page_title_show'])) { 
			print $date['page_title_show'];
		} else { 
			print $date['date_title'];
		}
	}
	if($sub['sub_id'] > 0) { 
		print "</a>";
	}
	
	if($sub['sub_id'] > 0) { 

		$ids = explode(",",$sub['sub_under_ids']);
		foreach($ids AS $val) { 
			if($val > 0) { 
				$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
				print " > <a href=\"index.php?sub=".$upsub['sub_link']."\">".$upsub['sub_name']."</a> ";
			}
		}
		
		print " > ".$sub['sub_name'];
	}*/
	if($sub['sub_id'] > 0) { 

		$ids = explode(",",$sub['sub_under_ids']);
		foreach($ids AS $val) { 
			if($val > 0) { 
				$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
				print " > <a href=\"index.php?sub=".$upsub['sub_link']."\">".$upsub['sub_name']."</a> ";
			}
		}
		
		print $sub['sub_name'];
	}
}
function pageDate() { 
	global $date,$setup;
	print $date['date_show_date'];
}
function pageExpireDate() { 
	global $date,$setup;
	if(!empty($date['date_expire_show'])) { 
		print _page_expires_on_." ".$date['date_expire_show'];
	}
}

function pageTime() { 
	global $date,$setup;
	print $date['date_time_show'];
}

function pageText() { 
	global $date,$setup,$sub,$site_setup,$show,$mobile,$store;

	if(($date['passcode_photos'] == "1") && (!empty($_REQUEST['passcode'])) == true) { 
		print "<div class=\"pc center\"><a href=\"index.php?passcodeclear=1\">"._find_other_passcode_photos_."</a></div>";
	}
	if(($date['passcode_photos'] == "1") && (empty($_REQUEST['passcode'])) == true) { 
		$date['hide_sub_gals'] = 1;

		include $setup['path']."/sy-inc/find_photos.php";
		$find_photos_included = true;
			print "<div>&nbsp;</div>";
			print "<div>&nbsp;</div>";
			print "<div>&nbsp;</div>";
	} else { 
		if($date['page_home'] !== "1") { 
			if((empty($_REQUEST['view'])) == true) { 
				if($date['date_gallery_exclusive'] <= 0) { 
					require $setup['path']."/sy-inc/store/store_download_all_free.php";
				}
				buyallphotos();
				print "<div class=\"clear\"></div>";
			}
		}
	}
	if(($sub['sub_id'] > 0) && (!empty($sub['sub_descr'])) == true) { 
		$html = nl2br($sub['sub_descr']);
	} else { 

		if($show['show_page_text'] !== "1") {
			if($date['date_text'] !=="<br />") { 
				if($date['date_add_breaks'] == "1") {
					$html = nl2br($date['date_text']);
				} else { 
					$html =  $date['date_text'];
				}
			}
		}
		if(($date['splash_enable'] == "1") && ($date['splah_view']!=="")==true) { 
			$html = "<div class=\"pc splashviewagain\"><a href=\"#splash\">".$date['splash_view']."</a></div>".$html;
		}
		if(!empty($date['page_form'])) {
			if($date['page_under'] > 0) { 
				$f_path =  "../../"; 
			} elseif($date['cat_content'] == $date['date_id']) { 
				$f_path =  "../"; 
			} elseif($date['date_cat'] > 0) { 
				if(!empty($date['cat_under_ids'])) { 
					$cids = explode(",",$date['cat_under_ids']);
					$f_path .= "../../";

					foreach($cids AS $cid) { 
						$f_path .= "../";
					}
				} else {	 
					$f_path =  "../../"; 
				}
			} elseif($date['page_home'] == "1") { 
				$f_path = "";
			} else {
				$f_path =  "../"; 
			}
			if(!empty($setup['content_folder'])) { 
				$f_path .= "../";
			}
			include  "$f_path".$setup['inc_folder']."/ms_forms.php"; 


			if(!empty($_REQUEST['check'])) {
				$html =  printForm("".$date['page_form']."");
			} elseif($_REQUEST['form'] == "success") {
				$form = doSQL("ms_forms", "*", "WHERE form_id='".$date['page_form']."' ");
				$html = nl2br($form['form_end_message']);
			} else {
				$form_html = printForm("".$date['page_form']."");
				$html = preg_replace('#\[form]#i', $form_html,$html);  
			}
		}
		if(!empty($html)) { 
			$html = preg_replace('#\[FAV_LINKS]#i', favLinks(),$html);  
			$html = preg_replace_callback('#\[JOIN_MAILING_LIST]#i',includemailform,$html);  
			$html = preg_replace_callback('#\[GIFT_CERTIFICATES]#i',includegiftcertificateform,$html); 
		}
	}



	$datas = whileSQL("ms_css", "*", "ORDER BY css_name ASC  ");
	while($data = mysqli_fetch_array($datas)) {
		$theme_html .= "<div class=\"pc\"><a href=\"/index.php?previewTheme=".$data['css_id']."\">".$data['css_name']."</a></div>";
	}
	$theme_html .="<div class=\"cssClear\"></div>";




	$date['date_embed'] = trim($date['date_embed']);
	if((!empty($date['date_embed']))&&($date['video_location'] == "0")==true) { 
		if((empty($_REQUEST['sub'])) && ((empty($_REQUEST['view'])) || ($_REQUEST['view'] == "highlights")) == true) { 
			print "<div id=\"fsVideo\" class=\"responsive-embed-youtube\" style=\" text-align: center;\">".$date['date_embed']."</div><div>&nbsp;</div>";
		}
	}

	print $html;

	if((!empty($date['date_embed']))&&($date['video_location'] == "1")==true) { 
		if((empty($_REQUEST['sub'])) && ((empty($_REQUEST['view'])) || ($_REQUEST['view'] == "highlights")) == true) { 
			print "<div>&nbsp;</div><div id=\"fsVideo\" class=\"responsive-embed-youtube\" style=\" text-align: center;\">".$date['date_embed']."</div>";
		}
	}

	 if(($date['find_my_photos'] == "1") && ($find_photos_included !== true) == true) {
		include $setup['path']."/sy-inc/find_photos.php"; 
			print "<div>&nbsp;</div>";
			print "<div>&nbsp;</div>";
			print "<div>&nbsp;</div>";
	 }


	if(!empty($date['page_include_page'])) { 
		include $date['page_include_page'];
	}
}

function pageMiniTop() { 
	global $date,$setup;
	if(!empty($date['date_mini'])){ 
		if(file_exists($setup['path']."".$setup['content_folder']."".$dcat['cat_folder']."/".$date['date_link']."/".$date['date_mini'])) {
			$size = @GetImageSize("".$setup['path']."".$setup['content_folder']."".$dcat['cat_folder']."/".$date['date_link']."/".$date['date_mini']); 
		?>
		<img src="<?php print $setup['temp_url_folder'];?>/<?php print $setup['content_folder']."".$dcat['cat_folder']."/".$date['date_link']."/".$date['date_mini'];?>" class="thumbnail mini" <?php print $size[3];?>>
		<?php 
		}
	}  
} 
?>

<?php function pageCategories() { 
	global $date, $setup;

	if($date['page_under'] > 0) { 
		$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
		if($uppage['date_cat'] > 0) { 
			$date_cat = $uppage['date_cat'];
		}
	}
	 if($date['date_cat'] > 0) { 
		 $date_cat = $date['date_cat'];
	 }
	if($date_cat > 0) { 
		$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date_cat."' ");
		if($cat['cat_content'] !== $date['date_id']) { 
			if(!empty($cat['cat_under_ids'])) { 
				$scats = explode(",",$cat['cat_under_ids']);
				foreach($scats AS $scat) { 
					$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
					print " <a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$tcat['cat_folder']."/\">".$tcat['cat_name']."</a> > ";
				}
			}
			print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/\">".$cat['cat_name']."</a> ";
			if($uppage['date_id'] > 0) { 
				print " > <a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/".$uppage['date_link']."/\">".$uppage['date_title']."</a>";
			}
		}
	}
} 

function pageSearchPhotos() { 
?>
	<div id="pagephotosearch" class="textright">
		<div class="pc">
		<form method="get" name="searchphotosform" action="index.php">
		<input type="text" name="keyWord" size="20" value="<?php print _search_photos_;?>" class="defaultfield" default="<?php print _search_photos_;?>">
		<input type="hidden" name="sub" value="<?php print $_REQUEST['sub'];?>"> 
		<input type="submit" name="submit" value="<?php print _search_photos_button_;?>" class="submit">
		</form>
		</div>
		<div class="textright pc"><a href="" onclick="photokeywords(); return false;"><?php print _view_all_photo_tags_;?></a></div>
<?php 
	if(!empty($_REQUEST['kid'])) {
	$key = doSQL("ms_photo_keywords", "*", "WHERE id='".$_REQUEST['kid']."' ");
	?>
	<div class="pc"><span id="tagresults"><b><?php print _showing_tag_results_;?> "<?php print $key['key_word'];?>"</a></b></span> <span id="tagnotfound" class="hide">Tag not found</span> (<a href="index.php<?php if(!empty($_REQUEST['sub'])) { print "?sub=".$_REQUEST['sub']; }?>">clear</a>)</div>
	<?php } ?>
<?php 
	if(!empty($_REQUEST['keyWord'])) {
	?>
	<div class="pc"><b><?php print _showing_search_results_;?> "<?php print htmlspecialchars($_REQUEST['keyWord']);?>"</a></b> (<a href="index.php<?php if(!empty($_REQUEST['sub'])) { print "?sub=".$_REQUEST['sub']; }?>">clear</a>)</div>
	<?php } ?>
	</div>
	<?php 
}

function pageTimeBlocks() { 
	global $date;
?>

	<div class="pc">
	<form method="get" name="time" action="index.php" id="timesearch">
	<?php 
	$from_time = $date['from_time'];
	$end_time = $date['to_time'];
	$search_length = $date['search_length'];
	$blocks = 0;

	$sdate = explode("-",$search_date);
	if(empty($_REQUEST['search_date'])) { 
		$search_date = date('Y-m-d');
	} else { 
		$search_date = $_REQUEST['search_date'];
	}
	?>
	<input type="hidden" name="search_date" id="search_date" value="<?php print $date['date_date'];?>" class="center" size="10">

	<select name="from_time" id="from_time" onchange="this.form.submit();">
	<option value=""><?php print _event_time_;?></option>
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
	<input type="hidden" name="sub" value="<?php print $_REQUEST['sub'];?>"> 
	<input type="hidden" name="search_length" id="search_length" value="<?php print $search_length;?>">

	</form>
	</div>
	<div class="clear"></div>



	<?php 
}

function pageTotalPhotos() { 
	global $date,$lang;
	$total_photos = countIt("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id",  "WHERE bp_blog='".$date['date_id']."'  ");
	if($total_photos > 0) { 
		 print "$total_photos ".$lang['_photos_word_photos_'].""; 
	}
}

function pageCommentTotal() { 
	global $date, $dcat;
	if(!empty($fb['facebook_app_id'])) { 
		$coms = numFBComments($setup['url'].$setup['temp_url_folder']."".$setup['content_folder']."".$dcat['cat_folder']."/".$date['date_link']."/"); 
	}
	$scoms = countIt("ms_comments", "WHERE com_table='ms_calendar' AND com_table_id='".$date['date_id']."' AND com_approved='1' ");
	$tcoms = $coms + $scoms;

	if($tcoms > 0) { print " | <a href=\"#listComments\">".$tcoms ." "; if($tcoms == "1") { print _comment_; } else { print _comments_; } print "</a>"; } 
}


function pagePhotos() { 
	global $date,$setup,$site_type,$ipad,$css,$bcat,$mobile,$sytist_store,$fb,$show,$show_thumbnails,$landingpage,$person;
	if(($bcat['cat_layout'] <=0)&&($bcat['cat_under'] > 0)==true) { 
		$upcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$bcat['cat_under']."' ");
		$cat_layout = $upcat['cat_layout'];
		if($cat_layout <= 0) { 
			$upcat2 = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$upcat['cat_under']."' ");
			$cat_layout = $upcat2['cat_layout'];
			if($cat_layout <= 0) { 
				$upcat3 = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$upcat2['cat_under']."' ");
				$cat_layout = $upcat3['cat_layout'];
				if($cat_layout <= 0) { 
					$upcat4 = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$upcat3['cat_under']."' ");
					$cat_layout = $upcat4['cat_layout'];
					if($cat_layout <= 0) { 
						$upcat5 = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$upcat4['cat_under']."' ");
						$cat_layout = $upcat5['cat_layout'];
					}
				}
			}

		}
	} else { 
		$cat_layout = $bcat['cat_layout'];
	}


	if($date['blog_type'] == "gallery") {
	if(countIt("ms_photo_products_groups", "WHERE group_package='1' AND group_list='".$date['date_photo_price_list']."' ") > 0)  { ?><div class="right thumbnailpackagelink"><a href="" onclick="buyphoto('1'); return false;"><span  class="icon-picture the-icons"></span><?php print _buy_packages_thumbnail_page_;?></a></div><div class="clear"></div><?php } 
	}  

	if(!empty($_REQUEST['sub'])) { 
		$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' AND sub_link='".$_REQUEST['sub']."' ");
		$sub_under = $sub['sub_id'];
	} else { 
		$sub_under = 0;
	}
	$total_photos = countIt("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id",  "WHERE bp_blog='".$date['date_id']."'  AND bp_sub='".$sub['sub_id']."'  ");

	if((empty($_REQUEST['sub']))&&($date['hide_sub_gals'] == "1")&&($date['passcode_photos'] !== "1")==true) { 
		if($find_photos_included !== true) { 
			include $setup['path']."/sy-inc/find_photos.php"; 
			print "<div>&nbsp;</div>";
			print "<div>&nbsp;</div>";
			print "<div>&nbsp;</div>";
		}
	 }
	$layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$cat_layout."' ");
	if(($date['hide_sub_gals'] <= 0) || (($date['hide_sub_gals'] == "1") && ($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) && ($person['p_id'] > 0)) == true) { 

		if((countIt("ms_sub_galleries",  "WHERE sub_date_id='".$date['date_id']."' AND sub_under='".$sub_under."' ") >  0)&&(empty($_REQUEST['kid']))==true) { 
			$show_subs = true;
			?>
			<?php if($show_thumbnails !== true) { ?><!-- <div id="loadingSubs" class="center"><div class="loadingspinner"></div></div> --><?php } ?>
			<div id="<?php print $layout['layout_css_id'];?>" class="sub-galleries-populate"></div>
			<div class="cssClear"></div>
			<div id="endsubgalleries" style="position: absolute;"></div>
			<div class="clear"></div>
			<div>&nbsp;</div>
	<?php 
		}
	}

	// if($total_photos > 0) { 
			if($date['blog_type'] == "onephoto") { 

				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "WHERE bp_blog='".$date['date_id']."'  ORDER BY bp_order ASC  ");
				if(!empty($pic['pic_id'])) { 
					$pic_file_select = selectPhotoFile($date['blog_photo_file'],$pic);
					// $size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file_select].""); 
					$size = getimagefiledems($pic,$pic_file_select);

					print "<img src=\"".getimagefile($pic,'pic_pic')."\" "; if(($date['photo_align'] == "left")||(empty($date['photo_align']))==true) { print "style=\"float: left; margin: 0 20px 20px 0 ;\""; } if($date['photo_align'] == "right") { print "style=\"float: right; margin: 0 0 20px 20px ;\""; } print ">";
				}
			}

			if(($date['passcode_photos'] == "1") && (empty($_REQUEST['passcode'])) == true) { 
				// include $setup['path']."/sy-inc/find_photos.php";
			} else { 
				if(((empty($show['show_id'])) || ((!empty($show['show_id'])) && $date['page_home'] !== "1")) && ((empty($landingpage['id'])) || (!empty($landingpage['id'])) && ($date['blog_type'] !=="nextprevious"))==true) { 

					if($date['blog_type'] == "onpagewithminis") { 
						include $setup['path']."/".$setup['inc_folder']."/photos_standard.php";
					}
	
					if($date['blog_type'] == "nextprevious") { 
						$page_gallery = true;
						include $setup['path']."/".$setup['inc_folder']."/photos_nextprevious.php";
					}
					if($date['blog_type'] == "gallery") { 
						$page_gallery = true;
						include $setup['path']."/".$setup['inc_folder']."/photos_nextprevious.php";
					}
					if($date['blog_type'] == "frame") { 
						$page_gallery = true;
						include $setup['path']."/".$setup['inc_folder']."/photos_nextprevious.php";
					}
					if($date['blog_type'] == "gifs") { 
						include "photos_gifs.php";
					}

					if(($date['blog_type'] == "standardlist")OR(empty($date['blog_type']))==true) { 
						include "photos_scroller.php";
					}

					if(($date['blog_type'] == "fullscreen")) { 
						$page_gallery = true;
						if($ipad == true) { 
							include "photos_scroller.php";
						} else {
							include $setup['path']."/".$setup['inc_folder']."/fullscreen.php";
						}
					}
				}
			}
		// }
	if(($sub['sub_id'] > 0)&&($date['passcode_photos']<=0) && ($date['date_gallery_exclusive'] !== "1")==true) { 
		if(countIt("ms_sub_galleries", "WHERE sub_date_id='".$date['date_id']."' AND (sub_under>'0' OR sub_pass!='') ") <=0) { 
			 $msubs = whileSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' ORDER BY sub_order ASC, sub_name ASC  ");
			if(mysqli_num_rows($msubs) > 0) { ?>
			<div class="pc center subgallerylinks">

				<?php 
				while($msub = mysqli_fetch_array($msubs)) {
				$ms++;?>
				<a href="index.php?sub=<?php print $msub['sub_link'];?>" <?php if($msub['sub_id'] == $sub['sub_id']) { ?>style="font-weight: bold;"<?php } ?>><?php print $msub['sub_name'];?></a>
				<?php 
					if($ms< mysqli_num_rows($msubs)) { print "&nbsp; &bull; &nbsp;"; } 
				} 
				print "</div>";
				}
		}
	}
}

function pageVideo() { 
	global $date,$setup,$bcat,$layout;
	if($date['video_file'] > 0) { 

	$vid = doSQL("ms_videos", "*", "WHERE vid_id='".$date['video_file']."' ");
	if(!empty($vid['vid_poster'])) {
		$poster = $setup['temp_url_folder']."/sy-photos/".$vid['vid_poster'];
	} else { 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
		if(empty($pic['pic_id'])) { 
			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."' ORDER BY bp_order ASC LIMIT  1 ");
		}
	}
	?>
  <link href="<?php print $setup['temp_url_folder'];?>/sy-inc/video/video-js.css" rel="stylesheet" type="text/css">
  <script src="<?php print $setup['temp_url_folder'];?>/sy-inc/video/video.js"></script>
  <script>
    videojs.options.flash.swf = "<?php print $setup['temp_url_folder'];?>/sy-inc/video/video-js.swf";
  </script>
		<video class="video-js vjs-default-skin" id="syvideo" controls="" <?php if($vid['vid_width'] > 0) { ?>width="<?php print $vid['vid_width'];?>"<?php } ?> <?php if($vid['vid_height'] > 0) { ?>height="<?php print $vid['vid_height'];?>"<?php } ?> <?php if(!empty($poster)) { ?> poster="<?php print $poster;?>"<?php } ?> class="nofloatsmall" style="max-width:<?php print $vid['vid_width'];?>px; max-height: <?php print $vid['vid_height'];?>px; width: 100%; height: auto;  ">  
		<source type="video/mp4" src="/<?php print $setup['photos_upload_folder']; ?>/<?php print $vid['vid_folder'];?>/<?php print $vid['vid_file'];?>">
		</source>
	   <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>	</video>
	<?php 
	}
}


function pageSubPages() { 
	global $date,$setup,$bcat,$layout;
	if(($date['page_list_sub_pages'] == "1") OR($mobile == true)==true){ 

	listContent(0,0, $date['date_id'],0,0,0) ;

}
	?>
	<div>&nbsp;</div>


<?php } ?>

	<?php 
function pageTags() { 
	global $date,$setup;
	if(countIt("ms_tag_connect",  "WHERE tag_date_id='".$date['date_id']."' ")>0) { ?>
	<?php print _tags_;?> 
	<?php $tags = whileSQL("ms_tag_connect LEFT JOIN ms_tags ON ms_tag_connect.tag_tag_id=ms_tags.tag_id", "*", "WHERE tag_date_id='".$date['date_id']."' ORDER BY ms_tags.tag_tag ASC ");
		while($tag = mysqli_fetch_array($tags)) { 
			if($tn > 0) { print ", "; } 
			print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."/tags/".$tag['tag_folder']."/\">".$tag['tag_tag']."</a>";
			$tn++;
		}
	} 
}

?>
<?php 
function pageNextPrevious() { 
	global $date,$setup,$bcat,$from_photo;
	if($from_photo !== true) { 
		$cur_time =date('Y-m-d H:i:s');
		if($listing !== true) { 
			$and_where .= "AND date_cat='".$bcat['cat_id']."' AND (date_expire='0000-00-00' OR date_expire >='".date("Y-m-d")."') ";

				print "<div id=\"pageNextPrevious\">";
				if($bcat['cat_order_by'] == "pageorder") { 
					$newer = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime", "WHERE date_id>'0'  AND ((cat_no_show_posts!='1' OR date_cat='0') OR (cat_id='".$date['date_cat']."')) AND date_public='1' AND  private<='1' AND date_type='news' AND page_order<'".$date['page_order']."'  AND date_id!='".$date['date_id']."' $and_where AND page_home!='1' AND page_404!='1' AND date_cat!='0' ORDER BY page_order DESC LIMIT 1 ");


				} else { 
					$newer = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime", "WHERE date_id>'0'  AND ((cat_no_show_posts!='1' OR date_cat='0') OR (cat_id='".$date['date_cat']."')) AND date_public='1' AND  private<='1' AND date_type='news' AND CONCAT(date_date, ' ', date_time)>'".$date['date_date']." ".$date['date_time']."' AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND date_id!='".$date['date_id']."' $and_where AND page_home!='1' AND page_404!='1' AND date_cat!='0' ORDER BY datetime ASC LIMIT 1 ");
				}


				if(!empty($newer['date_id'])) { 
					print "<div class=\"newer\">";

					print "<div class=\"inside\">";
					print "<div class=\"left\"><a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$newer['cat_folder']."/".$newer['date_link']."/\"><img src=\"".$setup['temp_url_folder']."/sy-graphics/icons/nav_left.png\" style=\"height: 51px; width: auto; border: 0;\" border=\"0\"></a></div>";
					$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$newer['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
					if(!empty($pic['pic_id'])) {
						// $size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
						print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$newer['cat_folder']."/".$newer['date_link']."/\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\"  border=\"0\" title=\"".$newer['date_title']."\"></a>";
					} else { 
						$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$newer['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
						if(!empty($pic['pic_id'])) {
							$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
							print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$newer['cat_folder']."/".$newer['date_link']."/\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\"   border=\"0\"  title=\"".$newer['date_title']."\"></a>";
						}
					}

				print "<h3><a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$newer['cat_folder']."/".$newer['date_link']."/\">".$newer['date_title']."";
				if(($cset['show_photo_count'] == "1") && (countIt("ms_blog_photos", "WHERE bp_blog='".$newer['date_id']."' ") > 1)==true){ 
					print " <nobr>(".countIt("ms_blog_photos", "WHERE bp_blog='".$newer['date_id']."' ")." ".$lang['_photos_word_photos_'].")</nobr>"; 
				} 		
				print "</a></h3>";
				print "</div></div>";
				}
				if($bcat['cat_order_by'] == "pageorder") { 

					$older = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime", "WHERE date_id>'0'  AND ((cat_no_show_posts!='1' OR date_cat='0') OR (cat_id='".$date['date_cat']."')) AND date_public='1' AND  private<='1' AND date_type='news' AND page_order>'".$date['page_order']."'  AND date_id!='".$date['date_id']."' $and_where AND page_home!='1' AND page_404!='1' AND date_cat!='0' ORDER BY page_order ASC LIMIT 1 ");
				} else { 

					$older = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,CONCAT(date_date, ' ', date_time) AS datetime", "WHERE date_id>'0' AND ((cat_no_show_posts!='1' OR date_cat='0') OR (cat_id='".$date['date_cat']."')) AND date_public='1' AND  private<='1' AND date_type='news' AND CONCAT(date_date, ' ', date_time)<'".$date['date_date']." ".$date['date_time']."'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND date_id!='".$date['date_id']."'  $and_where AND page_home!='1' AND page_404!='1' AND date_cat!='0'  ORDER BY CONCAT(date_date,date_time) DESC LIMIT 1 ");
				}


				if(!empty($older['date_id'])) { 
					print "<div class=\"older\">";

					print "<div class=\"inside\">";
					print "<div class=\"right\"><a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$older['cat_folder']."/".$older['date_link']."/\"><img src=\"".$setup['temp_url_folder']."/sy-graphics/icons/nav_right.png\" style=\"height: 51px; width: auto; border: 0;\" border=\"0\"></a></div>";

					$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$older['date_id']."'   AND bp_sub_preview<='0'  ORDER BY bp_order ASC LIMIT  1 ");
					if(!empty($pic['pic_id'])) {
						$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
						print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$older['cat_folder']."/".$older['date_link']."/\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\"  border=\"0\"  title=\"".$older['date_title']."\"></a>";
					} else { 
						$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$older['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
						if(!empty($pic['pic_id'])) {
							$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
							print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$older['cat_folder']."/".$older['date_link']."/\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\"   border=\"0\"  title=\"".$older['date_title']."\"></a>";
						}
					}

					print "<h3><a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$older['cat_folder']."/".$older['date_link']."/\">".$older['date_title']."";
					if(($cset['show_photo_count'] == "1") && (countIt("ms_blog_photos", "WHERE bp_blog='".$older['date_id']."' ") > 1)==true){ 
						print " <nobr>(".countIt("ms_blog_photos", "WHERE bp_blog='".$older['date_id']."' ")." ".$lang['_photos_word_photos_'].")</nobr>"; 
					} 		
					
					print "</a></h3> ";
					print "</div></div>";
				}
				print "<div class=\"cssClear\"></div>";
				print "</div>";
				print "<div>&nbsp;</div>";
			}
	}
	}
?>

<?php function productCart() { 
	global $date,$setup,$store,$site_setup,$from_photo;
	if(countIt("ms_product_subs", "WHERE sub_main_prod='".$date['date_id']."' ") > 0) { 
	 $has_subs = true;
	}
	?>
<script>
$(document).ready(function(){

	$(".qtydiscount").hoverIntent(
	  function () {
		$(this).find('.proddiscount').slideDown(100);
	  },
	  function () {
		$(this).find('.proddiscount').slideUp(100);
	  }
	);

   $(".hval").val("");
   $(".productoption li").click(function(){
		$(this).parent().children().removeClass('on');
		$(this).addClass('on');
		$("#"+$(this).attr('opt')).val($(this).attr('val'));
		// checkStock();

		checkStockNew($(this).attr('id'));

		spid = $(this).attr("sub_pic_id");
		if(spid > 0) { 
			$(".pmphoto").hide();
			$("#p-"+spid).show();
		}



    });

   $(".productoptionselect li").click(function(){
		$(this).parent().children().removeClass('on');
		$(this).addClass('on');
		$("#"+$(this).attr('fid')).val($(this).attr('sel_id'));
		// checkStock();

		// checkStockNew($(this).attr('id'));

    });


   $(".inputtabs").each(function(){
		$(this).val($(this).attr("defval"));
   });

});

</script>

<div class="pc">
<?php 
$price = productPrice($date);
if($price['onsale'] == true) { 
	if(($date['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
		 $price['onsale'] = $price['onsale']+ (($price['onsale'] * $site_setup['include_vat_rate']) / 100);
		 $price['org'] = $price['org'] + (($price['org'] * $site_setup['include_vat_rate']) / 100);
	}
	print "<div class=\"onsaleprice\">".showPrice($price['org'])."</div>";
}
	$this_price = $price['price'];
	if(($date['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
		 $this_price = $this_price + (($this_price * $site_setup['include_vat_rate']) / 100);
	}
	 
		print "<div class=\"productprice\"><span id=\"prodprice\" org=\"".showPrice($price['price'])."\">"; if($this_price > 0) { print showPrice($this_price); } print "</span></div>";

	?>
			<?php if(countIt("ms_products_discounts","WHERE dis_prod='".$date['date_id']."' ") > 0) { ?>
			
			<div class=" qtydiscount sub" style=" z-index: 4;">
				<div><a href="" onclick="return false;"><?php print _quantity_discounts_;?></a></div>
				<div class="proddiscount left">
					<div class="inner">
					<?php if(!empty($date['date_qty_descr'])) { ?>
						<div class="pc"><?php print nl2br($date['date_qty_descr']);?></div>
						<?php } ?>
						<?php 
						$diss = whileSQL("ms_products_discounts", "*", "WHERE dis_prod='".$date['date_id']."' ORDER BY dis_price DESC");
						while($dis = mysqli_fetch_array($diss)) { ?>
						<div class="pc">
							<div style="float: left; width: 60%;">
							<?php if($dis['dis_qty_to'] <=0) { ?>
								<?php print $dis['dis_qty_from']." +"; ?>
							<?php } else { ?>
								<?php print $dis['dis_qty_from']." - ".$dis['dis_qty_to']; ?>
							<?php } ?>
							</div>
							<div style="float: right; width: 40%;" class="textright">
							<?php
								if(($prod['pp_taxable'] && $site_setup['include_vat'] == "1")==true) { 
									$dis['dis_price'] = $dis['dis_price']+ (($dis['dis_price'] * $site_setup['include_vat_rate']) / 100);
								}
								print showPrice($dis['dis_price'])." "._each_; ?>
							</div>
							<div class="clear"></div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<?php } ?>
</div>
<?php if($price['onsale'] == true) { ?><div class="pageContent"><?php print nl2br($date['prod_sale_message']);?></div><?php } ?>

<div class="producttocart">


<?php if(($date['prod_inventory_control'] == "1")&&($has_subs !== true)&&($date['prod_qty'] <=0)==true) { ?>
<div id="prodmessage" class="pc"><?php print _out_of_stock_;?></div>
<?php if($setup['restock'] == true) { ?>
<script>
function emailrestock(classname) { 
	var fields = {};

	var rf = false;
	var mes;
	var stop;
	if($("#restock_email").val() == $("#restock_email").attr("default")) { 
		$("#restock_email").val("");
	}

	$(".restockrequire").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('inputError');
			rf = true;
		} else { 
			$('#'+this_id).removeClass('inputError');
		}
	} );


	if(rf == true || stop == true) {
		if(rf == true) {
			 $("#accresponse").html('<div class="pc"><div class="error">You have required fields empty</div></div>');
		}
		return false;
	} else { 

		$('.'+classname).each(function(){
			var $this = $(this);
			if( $this.attr('type') == "radio") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] = $this.val(); 
	//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
				}
			} else if($this.attr('type') == "checkbox") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] += ","+$this.val(); 
	//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
				}

			} else { 
				fields[$this.attr('id')] = $this.val(); 
				//fields[$this.attr('name')] = $this.val(); 
			}
		});

			$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 

			data = $.trim(data);
			// alert(data);
			if(data == "good") { 
				$("#restockform").slideUp(200, function() { 
					$("#restockrequest").slideDown();
					$("#requestaccesslink").hide();
				});
			}
			 // $("#accresponse").html(data);

		});
	}
	return false;
}
	</script>



	<div>

		<div id="fsignupcontainer" class="fsignupcontainer">
			<div class="fsignupcontainerinner">
			<div  id="restockform">
				<div class="pc">Want to know when this is back in stock?<br>Enter your email address below and we will let you know!</div>

				<form method=POST name="requestaccess" id="requestaccess" action="<?php print $site_setup['index_page'];?>" onSubmit="emailrestock('restock'); return false;" >
					<div >
						<div class="pc"><input type="text"  id="restock_email" size="20" value="<?php print _email_address_;?>" class="defaultfield restock restockrequire field100" style="max-width: 300px;" default="<?php print _email_address_;?>"> </div>
						<div class="pc"><input type="submit" name="submit" class="submit" value="Submit"></div>
					</div>

				<div class="cssClear"></div>
				<input type="hidden" class="restock" name="action" id="action" value="restockemail">
				<input type="hidden" class="restock"  id="restockitem" value="<?php print $date['date_id'];?>">

				</form>
			</div>
		<div id="restockrequest" class="hide pc">Thank you! We will let you know when we get more in.</div>
		</div>
	</div>
</div>

<?php } ?>
<?php } else { ?>

<?php ############################## NEW ######################################### ?>
 <?php if(countIt("ms_product_subs", "WHERE sub_main_prod='".$date['date_id']."' ") > 0) {   $disable_submit = true; } ?>

<script>
	<?php $price = productPrice($date); ?>
	thisprice = <?php print $price['price']; ?>;
	priceformat = "<?php print $store['price_format'];?>";
	currency_sign = "<?php print $store['currency_sign'];?>";
	decimals = (<?php print $store['price_decimals'];?>);
		inventorycontrol = '<?php print $date['prod_inventory_control'];?>';
	function checkStockNew(id) { 
		// $("#log").show().append($("#"+id).attr('val')+" - ");
		t = $("#"+id).attr('opt');
		$("#"+t).val($("#"+id).attr('val'));
		// $("#log").show().append(t+" = "+$("#"+id).attr('val'));

		instock = $("input[class='sub_products']<?php if(!empty($date['prod_opt1'])) { ?>[data-opt1='"+$("#product_opt1").val()+"']<?php } ?><?php if(!empty($date['prod_opt2'])) { ?>[data-opt2='"+$("#product_opt2").val()+"']<?php } ?><?php if(!empty($date['prod_opt3'])) { ?>[data-opt3='"+$("#product_opt3").val()+"']<?php } ?><?php if(!empty($date['prod_opt4'])) { ?>[data-opt4='"+$("#product_opt4").val()+"']<?php } ?><?php if(!empty($date['prod_opt5'])) { ?>[data-opt5='"+$("#product_opt5").val()+"']<?php } ?>").val();

		sub_id = $("input[class='sub_products']<?php if(!empty($date['prod_opt1'])) { ?>[data-opt1='"+$("#product_opt1").val()+"']<?php } ?><?php if(!empty($date['prod_opt2'])) { ?>[data-opt2='"+$("#product_opt2").val()+"']<?php } ?><?php if(!empty($date['prod_opt3'])) { ?>[data-opt3='"+$("#product_opt3").val()+"']<?php } ?><?php if(!empty($date['prod_opt4'])) { ?>[data-opt4='"+$("#product_opt4").val()+"']<?php } ?><?php if(!empty($date['prod_opt5'])) { ?>[data-opt5='"+$("#product_opt5").val()+"']<?php } ?>").attr('sub_id');

		add_price = $("input[class='sub_products']<?php if(!empty($date['prod_opt1'])) { ?>[data-opt1='"+$("#product_opt1").val()+"']<?php } ?><?php if(!empty($date['prod_opt2'])) { ?>[data-opt2='"+$("#product_opt2").val()+"']<?php } ?><?php if(!empty($date['prod_opt3'])) { ?>[data-opt3='"+$("#product_opt3").val()+"']<?php } ?><?php if(!empty($date['prod_opt4'])) { ?>[data-opt4='"+$("#product_opt4").val()+"']<?php } ?><?php if(!empty($date['prod_opt5'])) { ?>[data-opt5='"+$("#product_opt5").val()+"']<?php } ?>").attr('data-addprice');

		// $("#log").append("In stock = "+instock+" sub_id = "+sub_id+" add price: "+add_price);

		
		$("#log").append("instock: "+instock+" ");
		stopit = false;
		<?php if(!empty($date['prod_opt1'])) { ?>
		if($("#product_opt1").val()=='') { 
			stopit = true;
		} 
		<?php } ?>
		<?php if(!empty($date['prod_opt2'])) { ?>
		if($("#product_opt2").val()=='') { 
			stopit = true;
		} 
		<?php } ?>

		<?php if(!empty($date['prod_opt3'])) { ?>
		if($("#product_opt3").val()=='') { 
			stopit = true;
		} 
		<?php } ?>

		<?php if(!empty($date['prod_opt4'])) { ?>
		if($("#product_opt4").val()=='') { 
			stopit = true;
		} 
		<?php } ?>

		<?php if(!empty($date['prod_opt5'])) { ?>
		if($("#product_opt5").val()=='') { 
			stopit = true;
		} 
		<?php } ?>



		if(stopit !== true) { 
			if(instock> 0 || inventorycontrol == 0) { 
			if(add_price > 0) {

				newprice = (Math.abs(add_price) + Math.abs(thisprice));
				checkdecimals = newprice.toString();
				splitprice = checkdecimals.split('.');
				if(!splitprice[1]) { 
				//	alert(add_price+" - "+newprice+" - "+splitprice[1]);
					newprice = newprice.toFixed(decimals);
				} else { 
					newprice = newprice.toFixed(2);
				}

				newformat = priceformat.replace("[PRICE]", newprice); // value = 9:61
				newformat = newformat.replace("[CURRENCY_SIGN]", currency_sign); // value = 9:61
			//	alert(add_price+" = "+ (parseInt(add_price) + parseInt(thisprice)));
				$("#prodprice").html(newformat);
			} else { 
				$("#prodprice").html($("#prodprice").attr("org"));

			}

			$("#prod_qty option").remove();

			var mySelect = $('#prod_qty');
			var this_qty = instock;
			var q = 1;
			while(q <= this_qty) { 
				mySelect.append(
					$('<option></option>').val(q).html(q)
				);
				q++;
			}

				 $("#addtocart").show();
				 $("#addtocartdisabled").hide();
				$("#prodmessage").html("");
				$("#prodmessage").hide();
				$("#spid").val(sub_id);
			} else { 
				 $("#addtocartdisabled").show();;
				 $("#addtocart").hide();
				$("#prodprice").html($("#prodprice").attr("org"));

				$("#spid").val(0);
				$("#prodmessage").html($("#prodmessage").attr("data-not-in-stock"));
				$("#prodmessage").show();
				$("#prod_qty option").remove();
				$('#prod_qty').append(
					$('<option></option>').val("1").html("1")
				);

			}

		}



	}

</script>
<?php $subs = whileSQL("ms_product_subs", "*", "WHERE sub_main_prod='".$date['date_id']."' ORDER BY sub_id ASC");
while($sub = mysqli_fetch_array($subs)) { ?>
<div><input type="hidden" size="2"  class="sub_products" id="sub-<?php print $sub['sub_id'];?>" sub_id="<?php print $sub['sub_id'];?>" value="<?php print $sub['sub_qty'];?>" data-opt1="<?php print htmlspecialchars($sub['opt1_value']);?>" data-opt2="<?php print htmlspecialchars($sub['opt2_value']);?>" data-opt3="<?php print htmlspecialchars($sub['opt3_value']);?>" data-opt4="<?php print htmlspecialchars($sub['opt4_value']);?>" data-opt5="<?php print htmlspecialchars($sub['opt5_value']);?>" data-addprice="<?php print $sub['sub_add_price'];?>"></div>
<?php } ?>



	<?php if(!empty($date['prod_opt1'])) { ?>
		<div class="productconfigs"><?php print _select_on_product_configurations_;?> <?php print $date['prod_opt1'];?></div>
			<div class="productconfigsoptions">
			<input name="product_opt1" id="product_opt1" value="" type="hidden" class="hval">
			<ul class="productoption" id="ul-product_opt1">
			<?php $subs = whileSQL("ms_product_subs", "*", "WHERE sub_main_prod='".$date['date_id']."' GROUP BY opt1_value ORDER BY sub_id ASC"); ?>

			<?php
			while($sub = mysqli_fetch_array($subs)) { ?>
			 <?php if(!empty($sub['opt1_value'])) { ?>
			<li id="product_opt1-<?php print htmlspecialchars($sub['opt1_value']);?>" val="<?php print htmlspecialchars($sub['opt1_value']);?>" opt="product_opt1" sub_id="<?php print $sub['sub_id'];?>" sub_pic_id="<?php print $sub['sub_pic_id'];?>" class=""><?php print $sub['opt1_value'];?></li>
		<?php } ?>
		<?php } ?>
		</ul>
		</div>
		<div class="clear"></div>
	<div>&nbsp;</div>

		<?php } ?>

	<?php if(!empty($date['prod_opt2'])) { ?>
		<?php $subs = whileSQL("ms_product_subs", "*", "WHERE sub_main_prod='".$date['date_id']."' GROUP BY opt2_value ORDER BY opt2_value ASC"); ?>

	<div class="productconfigs"><?php print _select_on_product_configurations_;?> <?php print $date['prod_opt2'];?></div>
		<div class="productconfigsoptions">
		<input name="product_opt2" id="product_opt2" value="" type="hidden" class="hval">
		<ul class="productoption" id="ul-product_opt2">
			<?php
			while($sub = mysqli_fetch_array($subs)) { ?>
				 <?php if(!empty($sub['opt2_value'])) { ?>
			<li id="product_opt2-<?php print htmlspecialchars($sub['opt2_value']);?>" val="<?php print htmlspecialchars($sub['opt2_value']);?>" opt="product_opt2" sub_id="<?php print $sub['sub_id'];?>" class=""><?php print $sub['opt2_value'];?></li>
			<?php } ?>
			<?php } ?>
			</ul>
		</div>
		<div class="clear"></div>
	<div>&nbsp;</div>
	<?php } ?>

	<?php if(!empty($date['prod_opt3'])) { ?>
		<?php $subs = whileSQL("ms_product_subs", "*", "WHERE sub_main_prod='".$date['date_id']."' GROUP BY opt3_value ORDER BY opt3_value ASC"); ?>

	<div class="productconfigs"><?php print _select_on_product_configurations_;?> <?php print $date['prod_opt3'];?></div>
		<div class="productconfigsoptions">
		<input name="product_opt3" id="product_opt3" value="" type="hidden" class="hval">
		<ul class="productoption" id="ul-product_opt3">
			<?php
			while($sub = mysqli_fetch_array($subs)) { ?>
				 <?php if(!empty($sub['opt3_value'])) { ?>
			<li id="product_opt3-<?php print htmlspecialchars($sub['opt3_value']);?>" val="<?php print htmlspecialchars($sub['opt3_value']);?>" opt="product_opt3" sub_id="<?php print $sub['sub_id'];?>" class=""><?php print $sub['opt3_value'];?></li>
			<?php } ?>
			<?php } ?>
			</ul>
		</div>
		<div class="clear"></div>
	<div>&nbsp;</div>
	<?php } ?>


	<?php if(!empty($date['prod_opt4'])) { ?>
		<?php $subs = whileSQL("ms_product_subs", "*", "WHERE sub_main_prod='".$date['date_id']."' GROUP BY opt4_value ORDER BY opt4_value ASC"); ?>

	<div class="productconfigs"><?php print _select_on_product_configurations_;?> <?php print $date['prod_opt4'];?></div>
		<div class="productconfigsoptions">
		<input name="product_opt4" id="product_opt4" value="" type="hidden" class="hval">
		<ul class="productoption" id="ul-product_opt4">
			<?php
			while($sub = mysqli_fetch_array($subs)) { ?>
				 <?php if(!empty($sub['opt4_value'])) { ?>
			<li id="product_opt4-<?php print htmlspecialchars($sub['opt4_value']);?>" val="<?php print htmlspecialchars($sub['opt4_value']);?>" opt="product_opt4" sub_id="<?php print $sub['sub_id'];?>" class=""><?php print $sub['opt4_value'];?></li>
			<?php } ?>
			<?php } ?>
			</ul>
		</div>
		<div class="clear"></div>
	<div>&nbsp;</div>
	<?php } ?>


	<?php if(!empty($date['prod_opt5'])) { ?>
		<?php $subs = whileSQL("ms_product_subs", "*", "WHERE sub_main_prod='".$date['date_id']."' GROUP BY opt5_value ORDER BY opt5_value ASC"); ?>

	<div class="productconfigs"><?php print _select_on_product_configurations_;?> <?php print $date['prod_opt5'];?></div>
		<div class="productconfigsoptions">
		<input name="product_opt5" id="product_opt5" value="" type="hidden" class="hval">
		<ul class="productoption" id="ul-product_opt5">
			<?php
			while($sub = mysqli_fetch_array($subs)) { ?>
				 <?php if(!empty($sub['opt5_value'])) { ?>
			<li id="product_opt5-<?php print htmlspecialchars($sub['opt5_value']);?>" val="<?php print htmlspecialchars($sub['opt5_value']);?>" opt="product_opt5" sub_id="<?php print $sub['sub_id'];?>" class=""><?php print $sub['opt5_value'];?></li>
			<?php } ?>
			<?php } ?>
			</ul>
		</div>
		<div class="clear"></div>
	<div>&nbsp;</div>
	<?php } ?>


<?php $opts = whileSQL("ms_product_options", "*", "WHERE opt_date='".$date['date_id']."' ORDER BY opt_order ASC "); ?>
<?php 
while($opt = mysqli_fetch_array($opts))  { ?>
	<div  style="margin-bottom: 16px;"><?php productStoreOptions($opt,'cartoption',$date); ?></div>
<?php } ?>
	<?php if(($date['prod_type'] == "package")&&($date['date_package_pre_reg'] == "1") == true){ ?>
	<div>
	<select name="cart_pre_reg" id="cart_pre_reg" class="cartoption itemrequired">
	<option value=""><?php print _select_prereg_page_;?></option>
	<?php $pdates = whileSQL("ms_calendar", "*", "WHERE date_public='3' ORDER BY date_title ASC ");
	while($pdate = mysqli_fetch_array($pdates)) { ?>
	<option value="<?php print $pdate['date_id'];?>"><?php print $pdate['date_title'];?></option>
	<?php } ?>
	</select>
	</div>
	<div>&nbsp;</div>
	<?php } ?>

<?php ############################## END NEW ######################################### ?>
	<?php if($date['reg_person'] > 0) { 
		 if((customerLoggedIn()) && (MD5($date['reg_person']) == $_SESSION['pid'])==true) { 
			$my_reg = true;
		 }	
	}
	// if($my_reg !== true) { 
	?>

	<?php if($date['reg_person'] > 0) { 
	?>

	<div style="margin-bottom: 8px;">
	<select name="reg_amount" id="reg_amount" class="cartoption itemrequired registryamount">
	<option value=""><?php print _please_select_amount_;?></option>
	<?php
		$ams = explode("\r\n",$date['cat_reg_amounts']);

		foreach($ams AS $am) { 
			$am = trim($am);
			if(!empty($am)) { 
				?>
			<option value="<?php print $am;?>"><?php print showPrice($am);?></option>
		<?php 
			}
		}
		?>
		</select>
	</div>


	<div style="margin-bottom: 8px;">
	<div><?php print _registry_your_display_name_;?></div>
	<div><input type="text" name="reg_message_name" id="reg_message_name" class="field100 cartoption" ></div>
	</div>
	<div style="margin-bottom: 8px;">
	<div><?php print _registry_guestbook_message_;?></div>
	<div><textarea name="reg_message" id="reg_message" class="field100 cartoption" rows="4"></textarea></div>
	</div>

	<div style="margin-bottom: 8px;">
	<div><input type="checkbox" name="no_show" id="no_show" value="1" class="cartoption"> <label for="no_show"><?php print _registry_do_not_show_amount_;?></label></div>
	</div>
	<div>&nbsp;</div>

	<div class="clear"></div>
	<?php } ?>

<div id="prodmessage" class="pc hide" data-not-in-stock="<?php print htmlspecialchars(_product_combination_out_of_stock_);?>"></div>
<div id="submitinfo"></div>
<div class="error hide" id="min_qty_message" style="margin-bottom: 8px;"><?php print _min_qty_required_?> <?php if($date['qty_min'] > 0) { print $date['qty_min']; } else { print "1"; } ?></div>
<div class="error hide" id="select_option_message" style="margin-bottom: 8px;"><?php print _please_select_option_?></div>
<script>
$(document).ready(function(){
	$('.cartoption').keypress(function(e){
		if ( e.which == 13 ) return false;
		//or...
		if ( e.which == 13 ) e.preventDefault();
	});
});

</script>
<div id="storeproductform">


<form name="purchase" action="/" method="post">
	<input type="hidden" name="action" id="action" class="cartoption" value="addToCart">
	<input type="hidden" name="qty_min" id="qty_min" class="cartoption" value="<?php if($date['qty_min']<=0) { print "1"; } else { print $date['qty_min']; } ?>">
	<input type="hidden" name="spid" id="spid" class="cartoption">
	<?php if($from_photo == true) { 
		if((countIt("ms_product_options", "WHERE opt_date='".$date['date_id']."' AND opt_photos='1' ") || $date['prod_photos'] > 0)==true) { 
			if($date['prod_photos'] == "1") { 
				$num_photos = 1;
			} else { 
				$num_photos = 2;
			}
		}
	} ?>
	<input type="hidden" name="from_photo" id="from_photo" value="<?php print $num_photos;?>" class="cartoption">
	<input type="hidden" name="did" id="did" value="<?php print MD5($date['date_id']);?>" class="cartoption">
	<?php if($date['reg_person'] > 0) { ?>
	<input type="hidden" name="prod_qty" id="prod_qty" class="cartoption" value="1">
	<?php } elseif($date['prod_max_one'] == "1")  { ?>
	<input type="hidden"  name="prod_qty" id="prod_qty" class="cartoption center" size="2" value="1">
	<?php } else { ?>

	<div id="qty" style="float: left; margin-right: 8px;"><span class="storeqty"><?php print _qty_;?>: </span>
	<?php if($date['prod_inventory_control'] == "1") { ?>
	<select name="prod_qty" id="prod_qty" class="cartoption">
	<?php if($has_subs == true) { ?>
	<option value="1">1</option>
	<?php } else { ?>
	<?php 
		$x = 1;
	while($x <= $date['prod_qty']) { ?>	
		<option value="<?php print $x;?>"><?php print $x;?></option>
		<?php $x++;
	}
	?>


	<?php } ?>
	</select>

	
	<?php } else { ?>
	<input type="text"  name="prod_qty" id="prod_qty" class="cartoption center" size="2" value="<?php if($date['qty_min'] > 0) { print $date['qty_min']; } else { print "1"; } ?>" style="padding: 4px;">
	<?php } ?>
	</div>
<?php } ?>
<?php if(!empty($date['add_to_cart_redirect'])) {?>
<input type="hidden" name="addaction" id="addaction" value="<?php print $date['add_to_cart_redirect'];?>" data-redirect="1" class="cartoption">
<?php } ?>
<div id="addtocartloading"><img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading.gif"></div>
<div id="addtocart" onClick="sendtocart('cartoption'); return false;" style=" <?php if($has_subs == true) { ?>display: none; <?php } ?> float: left; cursor: pointer; " ><?php if(!empty($date['add_to_cart_text'])) { print $date['add_to_cart_text'];} else { print _add_to_cart_; } ?></div>
<?php if($has_subs == true) { ?><div id="addtocartdisabled" class="tip" title="<?php print _select_from_options_above_;?>"><?php if(!empty($date['add_to_cart_text'])) { print $date['add_to_cart_text'];} else { print _add_to_cart_; } ?></div><?php } ?>

<div class="clear"></div>
<?php if($from_photo == true) { ?>
<div class="pc"><a href="" onclick="closestoreitem(); return false;"><?php print _cancel_;?></a></div>
<?php } ?>
<div>&nbsp;</div>
<?php // } // END NOT MY REGISTRY ?>
</form></div>
<?php } ?>


</div>
<div class="clear"></div>
<div>&nbsp;</div>
<?php } // end product option to cart ?>
<?php
function pageComments() { 
	global $bcat,$setup,$site_setup,$date;

	$coms = doSQL("ms_comments_settings", "*", "");
	if(($coms['use_facebook'] == "1") || ($coms['use_standard'] == "1") == true) { 
		$fb = doSQL("ms_fb", "*", "");
		$com_table = "ms_calendar";
		$com_table_id = $date['date_id'];
		$com_title = $date['date_title'];
		$com_link = $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$dcat['cat_folder']."/".$date['date_link']."/";
		if($site_type == "mobile") { 
			$coms['com_form_type'] = "long";
		}
		$com_table = "ms_calendar";
		$com_table_id = $date['date_id'];
		$com_title = $date['date_title'];
		$show_comment = true;
		$com_link = $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$bcat['cat_folder']."/".$date['date_link']."/";
		if($date['disable_comments'] == 1) { 
			$show_comment = false;
		}

	?>

	<div class="cssClear"></div>

	<?php if($show_comment == true) { 
		include $setup['path']."/sy-inc/comments.php";
		} 
	}
}
?>

<?php function recentCategoryItems($category, $limit, $layout) { 
	listContent($category,$tag_id,0,$category,$layout,$limit); 

}

function pageHomeFeatured() { 
	global $date,$setup,$site_setup;
		$pr = 3;
		$x = 1;
		$fcats1 = array();
		$cats = explode("|",$date['feature_row_1']);
		foreach($cats AS $cat) { 
			if(!empty($cat)) { 
				array_push($fcats1,$cat);
			}
		}
		if(count($fcats1) > 0) { 
			$home_feature = true;
		?>
	<div id="homepagefeaturedsections">
		<?php 
		$w = 100 / count($fcats1);
		foreach($fcats1 AS $tcat) { 
			$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='$tcat' ");
			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_cat='".$cat['cat_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
			if(!empty($pic['pic_id'])) {
			$size = getimagefiledems($pic,'pic_pic');

			//	$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic']); 
				?>
			<div style=" width:<?php print $w;?>%; float: left;" class="homepagefeaturedsectioncontainer nofloatsmall">
			<div style="padding: 8px; position: relative; display: block; text-align: center;" class="homepagefeaturedsectioninner">
				<?php 
					print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/\"><img src=\"".getimagefile($pic,'pic_pic')."\" border=\"0\" style=\"max-width: ".$size[0]."px; max-height: ".$size[1]."px; width:100%; height: auto;\" class=\"photo\" width=\"".$size[0]."\" height=\"".$size[1]."\"></a>";
				?>
				<?php if($date['feature_show_titles'] == "1") { ?><h1><?php print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/\">".$cat['cat_name']."</a>";?></h1><?php } ?>
				</div>
			</div>

		<?php 				
			}
		}

	?>
<div class="clear"></div>
</div>
<?php } ?>



<?php 
		$fcats2 = array();
		$cats = explode("|",$date['feature_row_2']);
		foreach($cats AS $cat) { 
			if(!empty($cat)) { 
				array_push($fcats2,$cat);
			}
		}
		if(count($fcats2) > 0) { 
			$home_feature = true;
		?>
	<div id="homepagefeaturedsections">
		<?php 
		$w = 100 / count($fcats2);
		foreach($fcats2 AS $tcat) { 
			$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='$tcat' ");
			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_cat='".$cat['cat_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
			if(!empty($pic['pic_id'])) {
			$size = getimagefiledems($pic,'pic_pic');
				?>
			<div style=" width:<?php print $w;?>%; float: left;" class="homepagefeaturedsectioncontainer nofloatsmall">
			<div style="padding: 8px; position: relative; display: block; text-align: center;" class="homepagefeaturedsectioninner">
				<?php 
					print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/\"><img src=\"".getimagefile($pic,'pic_pic')."\" border=\"0\" style=\"max-width: ".$size[0]."px; max-height: ".$size[1]."px; width:100%; height: auto;\" class=\"photo\" width=\"".$size[0]."\" height=\"".$size[1]."\"></a>";
				?>
				<?php if($date['feature_show_titles'] == "1") { ?><h1><?php print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/\">".$cat['cat_name']."</a>";?></h1><?php } ?>
				</div>
			</div>

		<?php 				
			}
		}

	?>
</div>
<div class="clear"></div>
<?php } ?>
<?php if($home_feature == true) { print "<div>&nbsp;</div>"; } ?>
	<?php 
	if($date['date_feature_cat'] > 0) {
	$fats = explode(",",$date['date_feature_cat']);
	if(count($fats) > 0) { 
		foreach($fats AS $fat) { 
			$fbcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='$fat' ");
			if(!empty($fbcat['cat_id'])) { 
				$fc++;
			}
		}
	}
	if(($fc > 0) || ($date['date_feature_cat'] == "999999999") == true){ 
		if(!empty($date['date_feature_title'])) { ?>
		<div class="pc"><h1><?php print $date['date_feature_title'];?></h1></div>
		<?php }
		if(!empty($date['date_feature_text'])) { ?>
		<div class="pc"><?php print nl2br($date['date_feature_text']);?></div>
		<?php }
			$no_cats = true;
			listContent($date['date_feature_cat'],$tag_id,0,$date['date_feature_cat'],$date['date_feature_layout'],$date['date_feature_limit'],$no_cats); 
		}
	}
}

?>
<div id="endpagelistings" style="position: absolute;"></div>
<div id="listingpage-2" style="display: none; width: 100%; height: 30px;" class="thumbPageLoading"></div>

<?php if((!empty($date['audio_file']))&&(file_exists($setup['path']."/".$setup['misc_folder']."/audiofiles/".$date['audio_file']))==true) { 	?>
<div id="slideshowaudio">
	<div style="padding: 16px;">

<?php 


	$track_name = str_replace(".mp3","",$date['audio_file']);
?>

	<audio autoplay  id="ssAudio">
	<?php if(file_exists($setup['path']."/".$setup['misc_folder']."/audiofiles/".$track_name.".ogg")) { ?>
	  <source src="<?php print "".$setup['temp_url_folder']."/".$setup['misc_folder']."/audiofiles/".$track_name.".ogg";?>" type="audio/ogg" / >
	 <?php } ?>
	<?php if(file_exists($setup['path']."/".$setup['misc_folder']."/audiofiles/".$track_name.".mp3")) { ?>
	  <source src="<?php print "".$setup['temp_url_folder']."/".$setup['misc_folder']."/audiofiles/".$track_name.".mp3";?>" type="audio/mpeg" />
	  <?php } ?>
	  Your browser does not support the audio element.
	</audio> 
	<span id="ssAudioPlay"  <?php if($_SESSION['ipad'] !== true) { print "style=\"display: none;\""; } ?> class="the-icons icon-volume-off" onclick="playSSAudio();" title="Play Music"></span>
	<span id="ssAudioPause" <?php if($_SESSION['ipad'] == true) { print "style=\"display: none;\""; } ?> class="the-icons icon-volume-up" onclick="pauseSSAudio();" title="Pause Music"></span>
</div>
</div>
<?php }

function shareonfb() { 
	global $setup,$site_setup,$date,$fb;
	?>
	<div class="fb-share-button" data-href="<?php print $setup['url'].$setup['temp_url_folder'].$date['cat_folder']."/".$date['date_link'];?>/" data-type="link"></div>

	<?php 
 }


function registryGoal() { 
	global $date,$setup,$site_setup;
	if($date['reg_goal'] > 0) { 
	$regs = doSQL("ms_credits", "*,SUM(credit_amount) AS total", "WHERE credit_reg='".$date['date_id']."' ");
		print "<h3>".showPrice($regs['total'])." "._of_." ".showPrice($date['reg_goal'])." "._registry_goal_reached_."</h3>";
	}


}

function registryMessage() { 
	global $date,$setup,$site_setup;
	print "<div id=\"regm\">".$date['date_text']."</div>";
	 if((customerLoggedIn()) && (MD5($date['reg_person']) == $_SESSION['pid'])==true) { 
		print "<div class=\"pc\"><a href=\"\" onclick=\"editregmessage(); return false;\" class=\"the-icons icon-pencil\"><i>"._registry_edit_message_."</i></a></div>";
		?>
		<div id="editrm" style="display: none;\">
		<div><textarea name="regme" id="regme" rows="6" class="field100" did="<?php print MD5($date['date_id']);?>"><?php print strip_tags($date['date_text']);?></textarea></div>
		<div><a href="" onclick="saveregmessage(); return false;"><?php print _proof_revise_save_;?></a> &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href="" onclick="editregmessage(); return false;"><?php print _cancel_;?></a></div>
		</div>
		<script>
			function editregmessage() { 
			$("#editrm").slideToggle(400);
			$("#regm").slideToggle(400);
		}
		function saveregmessage() { 
			var fields = {};
			fields['did'] = $("#regme").attr("did");
			fields['newm'] = $("#regme").val();
			fields['action'] = "saveregmessage";

			$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
				$("#regm").html("").append(data);
				$("#editrm").slideToggle(400);
				$("#regm").slideToggle(400);

			});

		}
		</script>
		<?php 
	 }
}

function registryId() { 
	global $date;
	print _registry_id_.": ".$date['date_id'];
}
function registryEventDate() { 
	global $date;
	if($date['reg_event_date'] > 0) { 
		print _registry_event_date_.": ".$date['reg_event_date_show'];
	}
}
function registryInstructions() { 
	global $date;
	if($my_reg !== true) { 
		print $date['cat_text_under_subs'];
	}
}
function registryGuestBook() { 
	global $date,$setup,$site_setup;
	$entries = whileSQL("ms_credits", "*, date_format(DATE_ADD(credit_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS credit_date_show", "WHERE credit_reg='".$date['date_id']."' ORDER BY credit_id DESC ");
	 if((customerLoggedIn()) && (MD5($date['reg_person']) == $_SESSION['pid'])==true) { 
		$my_reg = true;
	 }	
	?>
	<div class="pc"><h2><?php print _registry_guestbook_;?></h2></div>
	<?php 
		if(mysqli_num_rows($entries) <= 0) { ?>
		<div class="pc center"><?php print _registry_guestbook_no_results_; ?></div>
		<?php } ?>
	<div id="regguestbook">
	<?php 
	while($entry = mysqli_fetch_array($entries)) { ?>
	<div class="preview">
		<div class="pc">
			<div style="width: 70%; float: left;"><h3><?php if(!empty($entry['credit_reg_buyer_name'])) { print $entry['credit_reg_buyer_name']; } else { print _registry_anonymous_; } ?></h3></div>
			<div style="width: 30%; float: right; text-align: right;"><h3>
			<?php if(($entry['credit_reg_no_display_amount'] == "1")&&($my_reg !== true)==true) { print _registry_amount_hidden_; } else { print showPrice($entry['credit_amount']); } ?></h3></div>
			<div class="clear"></div>
		</div>
	<?php if(($my_reg == true)&&(!empty($entry['credit_reg_buyer_email']))==true) { ?>
		<div class="pc"><a href="mailto:<?php print $entry['credit_reg_buyer_email'];?>"><?php print $entry['credit_reg_buyer_email'];?></a> <?php print _registry_only_visible_;?></div>
	<?php } ?>
	<?php if(!empty($entry['credit_reg_message'])) { ?>
	<div class="pc"><?php print nl2br($entry['credit_reg_message']);?></div>
	<?php } ?>
	</div>
	<?php } ?>
	</div>
	<?php 
}

function customField($field_name) { 
	global $date;
	$c = doSQL("ms_custom_fields LEFT JOIN ms_custom_fields_data ON ms_custom_fields.id=ms_custom_fields_data.data_field", "*", "WHERE field_data_id='".$field_name."' AND data_date='".$date['date_id']."' ");
	if(!empty($c['data_data'])) { 
		if($c['field_type'] == "price") { 
			// print $c['field_name'].": ".showPrice($c['data_data']);
			print showPrice($c['data_data']);
		} else { 
		//	print $c['field_name'].": ".$c['data_data'];
			print $c['data_data'];
		}
	}
	if(!empty($c['field_link'])) { 
		print "<br>".$c['field_link']."";
	}
	return $c['data_data'];
}

function customFieldData($field_name) { 
	global $date;
	$c = doSQL("ms_custom_fields LEFT JOIN ms_custom_fields_data ON ms_custom_fields.id=ms_custom_fields_data.data_field", "*", "WHERE field_data_id='".$field_name."' AND data_date='".$date['date_id']."' ");
	return $c['data_data'];
}

function pageRelatedContent() { 
	global $date,$setup,$site_setup;
	if(countIt("ms_tag_connect",  "WHERE tag_date_id='".$date['date_id']."' ")>0) { 
		$tag_id = array();
		array_push($tag_id,$date['date_id']);
		$tags = whileSQL("ms_tag_connect LEFT JOIN ms_tags ON ms_tag_connect.tag_tag_id=ms_tags.tag_id", "*", "WHERE tag_date_id='".$date['date_id']."' ORDER BY ms_tags.tag_tag ASC ");
		while($tag = mysqli_fetch_array($tags)) { 
			array_push($tag_id,$tag['tag_id']);
			$tn++;
		}
	} 
	if(count($tag_id) > 0){ 

			$get_tag = "(";
			foreach($tag_id AS $tid) { 
				$ts++;
				// first in array is the date_id so we don't show a duplicate. 
				if($ts == "1") { 
					$and_no_date = "AND date_id!='".$tid."' ";
				} else { 
					if($ts > 2) { 
						$get_tag .= " OR ";
					}
					$get_tag .= "ms_tag_connect.tag_tag_id='".$tid."' ";
				}
			}
			$get_tag .= ")";
			$group_by = "GROUP BY date_id ";
		$time = strtotime("".$site_setup['time_diff']." hours");
		$cur_time =date('Y-m-d H:i:s', $time);



		$dates = whileSQL("ms_calendar LEFT JOIN ms_tag_connect ON ms_calendar.date_id=ms_tag_connect.tag_date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show, date_format(reg_event_date, '".$site_setup['date_format']." ')  AS reg_event_date_show", "WHERE  ms_calendar.date_public='1' AND  ms_calendar.private<='1' AND  $get_tag  AND CONCAT(date_date, ' ', date_time)<='$cur_time'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) $and_no_date $group_by ORDER BY date_date DESC, date_time DESC");
		$total_results = mysqli_num_rows($dates);

		if($total_results > 0) { 
			?>
			<div class="pc center relatedcontent"><h3><?php print _related_content_;?></h3></div>
			<?php 
			listContent($date_cat_id,$tag_id,0,0,0,0,0); 
		}
	}


}
?>
