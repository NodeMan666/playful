<?php require "w-header.php"; ?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>

<?php adminsessionCheck(); ?>


<?php 
if($_REQUEST['action'] == "save") { 
			$lw = explode("||",$_REQUEST['link_page']);
			if(!empty($lw[1])) { 
				$add_this = "link_cat='".$lw[1]."' ";
			} else { 
				$add_this = "link_page = '".$_REQUEST['link_page']."' ";
			}
		$_REQUEST['link_html'] = trim($_REQUEST['link_html']);
		if($_REQUEST['link_id'] >0) {
			updateSQL("ms_menu_links", "link_status='".addslashes(stripslashes($_REQUEST['link_status']))."' ,link_url='".addslashes(stripslashes(trim($_REQUEST['link_url'])))."', link_text='".addslashes(stripslashes(trim($_REQUEST['link_text'])))."', link_open='".addslashes(stripslashes($_REQUEST['link_open']))."' , link_location='".addslashes(stripslashes($_REQUEST['link_location']))."' , $add_this, link_shop_menu='".$_REQUEST['link_shop_menu']."', link_login_page='".$_REQUEST['link_login_page']."', link_dropdown='".$_REQUEST['link_dropdown']."' , link_show_cart='".$_REQUEST['link_show_cart']."',link_html='".addslashes(stripslashes($_REQUEST['link_html']))."' , 
			link_logged_in='".$_REQUEST['link_logged_in']."', 
			link_logged_out='".$_REQUEST['link_logged_out']."', 
			link_open_drop_mobile='".$_REQUEST['link_open_drop_mobile']."',
			
			link_no_click='".$_REQUEST['link_no_click']."', link_icon='".$_REQUEST['link_icon']."', link_dropdown_links='".addslashes(stripslashes(trim($_REQUEST['link_dropdown_links'])))."', link_mobile_cats='".$_REQUEST['link_mobile_cats']."' WHERE link_id='".$_REQUEST['link_id']."' ");
		} else {
			$link_order = doSQL("ms_menu_links", "*", "WHERE link_location='".$_REQUEST['link_location']."' ORDER BY link_order DESC ");
			$this_link_order = $link_order['link_order'] + 1;
			insertSQL("ms_menu_links", "link_status='".addslashes(stripslashes($_REQUEST['link_status']))."' ,link_order='$this_link_order' ,link_url='".addslashes(stripslashes(trim($_REQUEST['link_url'])))."', link_text='".addslashes(stripslashes(trim($_REQUEST['link_text'])))."', link_open='".addslashes(stripslashes($_REQUEST['link_open']))."' , link_location='".addslashes(stripslashes($_REQUEST['link_location']))."' , $add_this  , link_shop_menu='".$_REQUEST['link_shop_menu']."', link_dropdown='".$_REQUEST['link_dropdown']."', link_show_cart='".$_REQUEST['link_show_cart']."',link_html='".addslashes(stripslashes($_REQUEST['link_html']))."', 
			link_logged_in='".$_REQUEST['link_logged_in']."', 
			link_logged_out='".$_REQUEST['link_logged_out']."', 
			link_no_click='".$_REQUEST['link_no_click']."', link_icon='".$_REQUEST['link_icon']."', link_dropdown_links='".addslashes(stripslashes(trim($_REQUEST['link_dropdown_links'])))."', 
			link_open_drop_mobile='".$_REQUEST['link_open_drop_mobile']."', link_mobile_cats='".$_REQUEST['link_mobile_cats']."' ");
		}

		if($_REQUEST['from_menu'] == "1") { 
			?>
		<div class="pc"><h3>Link Saved</h3></div>
		<div class="pc">
		<a href="javascript:closeFrame();">Close this window</a> or <a href="index.php?do=look&view=links" target="_parent">manage menu links</a>.
			<?php 

		} else { 
		$_SESSION['sm'] = "Link Saved";
		header("location: index.php?do=look&view=links");

		
		} 
		
		exit();

}
?>

<div class="pc"><h1><?php if($_REQUEST['link_id'] >0) { ?>Edit Menu Link<?php } else { ?>Add New Menu Link<?php } ?></div>

<script>
function getOptionText() { 
	if($("#link_page").val() <= 0) { 
		$("#externallink").show();

	} else { 
		$("#externallink").hide();
		$("#link_text").val($('#link_page option:selected').text());
	}
}
function selectLeftRight() { 
	if($("#link_location").val() == "shop") { 
		$("#leftrightmenu").show();
	} else { 
		$("#leftrightmenu").hide();
	}

}

</script>
<?php 
if(!empty($_REQUEST['link_id'])) { 
	$link = doSQL("ms_menu_links", "*", "WHERE link_id='".$_REQUEST['link_id']."' ");
	$_REQUEST['link_text'] = $link['link_text'];
	$_REQUEST['link_url'] = $link['link_url'];
	$_REQUEST['link_order'] = $link['link_order'];
	$_REQUEST['link_open'] = $link['link_open'];
	$_REQUEST['link_status'] = $link['link_status'];
	$_REQUEST['link_location'] = $link['link_location'];
	$_REQUEST['link_page'] = $link['link_page'];
	$_REQUEST['link_login_page'] = $link['link_login_page'];
	$_REQUEST['link_dropdown'] = $link['link_dropdown'];
	$_REQUEST['link_no_click'] = $link['link_no_click'];
	
	if($link['link_cat'] > 0) { 
		$_REQUEST['link_page'] = "||".$link['link_cat'];
	}
}
?>



	<div id="">
			<form method="post" name="newLink" action="w-edit-link.php" style="padding:0; margin:0;"   onSubmit="return checkForm();">
			<div class="underline">
				<div style="width:30%; float: left;">&nbsp;</div><div style="width:70%; float: right;"><input type="checkbox" name="link_status" id="link_status" value="1" <?php if(($_REQUEST['link_status']=="1")OR($link['link_id'] <=0)==true) { print "checked"; } ?>> <label for="link_status">Active Link</label></div>
				<div class="cssClear"></div>
			</div>

			<div class="underline">
				<div style="width:30%; float: left;">Location</div>
				<div style="width:70%; float: right;">
				<select name="link_location" id="link_location" onchange="selectLeftRight();">
				<option value="topmain" <?php if($_REQUEST['link_location']=="topmain") { print "selected"; } ?>>Main Menu</option> 
				<?php if($sytist_store == true) { ?>
				<option value="shop" <?php if($_REQUEST['link_location']=="shop") { print "selected"; } ?>>Top Mini Menu</option> 
				<?php } ?>

				<option value="side" <?php if($_REQUEST['link_location']=="side") { print "selected"; } ?>>Side Bar Menu</option> 
				</select>
				</div>
				<div class="cssClear"></div>
			</div>
			<?php if(empty($link['link_main'])) { ?>
			<div class="underline">
				<?php if($_REQUEST['link_cat']>0) { 
				$_REQUEST['link_page'] = "||".$_REQUEST['link_cat'];
				}
				?>

				<div style="width:30%; float: left;">Link to page: </div><div style="width:70%; float: right;">
				<select name="link_page" id="link_page" onChange="getOptionText();">
				<option value="0">Select Section Or Top Level Page</option>
				<option value="0">External Link</option>
				<option value="" disabled>Sections</option>
				<?php $cats = whileSQL("ms_blog_categories", "*", " WHERE cat_under='0' ORDER BY cat_name ASC");
				while($cat = mysqli_fetch_array($cats)) {
					print "<option value=\"cat||".$cat['cat_id']."\" "; if($_REQUEST['link_page'] == "||".$cat['cat_id']) { print " selected"; } print ">";  print $cat['cat_name'];  print "</option>";
				}
				?>
				<option value="" disabled>Top Level Pages</option>

				<?php $pages = whileSQL("ms_calendar", "*", " WHERE page_under='0' AND date_cat='0' AND page_404='0' ORDER BY date_title ASC");
				while($page = mysqli_fetch_array($pages)) {
					print "<option value=\"".$page['date_id']."\" "; if($_REQUEST['link_page'] == $page['date_id']) { print " selected"; } print ">"; if($page['page_home'] == "1") { print "Home Page"; }  else { print $page['date_title']; } print "</option>";
				}
				?>

				</select>
				
				</div>
				<div class="cssClear"></div>
			</div>

			<div class="underline" id="externallink" <?php if(($link['link_page'] > 0)||($link['link_cat'] > 0)||($_REQUEST['link_cat']>0) ==true) { print "style=\"display: none;\""; } ?>>
				<div style="width:30%; float: left;">URL<br>This is the address you want the link to go to.</div><div style="width:70%; float: right;">
				<?php if(empty($link['link_main'])) { ?>
					<input type="text" name="link_url" value="<?php if(!empty($_REQUEST['link_url'])) { print htmlspecialchars(stripslashes($_REQUEST['link_url'])); } ?>" size="60"><br>TIP: Leave this blank to create a label to separate links / menus.
				<?php } else { ?>
				This links to <?php  print "<a href=\"".$setup['url'].$setup['temp_url_folder']."/".$setup[$link['link_main']]."/\" target=\"_blank\">".$setup['url'].$setup['temp_url_folder']."/".$setup[$link['link_main']]."/</a>"; } ?>
					</div>
				<div class="cssClear"></div>
			</div>
			<?php } else { ?>
			<div class="underline"><div style="width:30%; float: left;">&nbsp;</div><div style="width:70%; float: right;">
				<?php 
				if($link['link_main'] == "cart") { 
					print "View Cart Page";
					$hide_drop = true;
				}
				if($link['link_main'] == "checkout") { 
					print "Checkout Page";
					$hide_drop = true;
				}
				if($link['link_main'] == "myaccount") { 
					print "My Account Page";
					$hide_drop = true;
				}
				if($link['link_main'] == "login") { 
					print "Log In";
					$hide_drop = true;
				}
				if($link['link_main'] == "newaccount") { 
					print "New Account Page";
					$hide_drop = true;
				}
				if($link['link_main'] == "logout") { 
					print "Log out link";
					$hide_drop = true;
				}
				if($link['link_main'] == "printcredit") { 
					print "Redeem print credit";
					$hide_drop = true;
				}
				if($link['link_main'] == "giftcertificates") { 
					print "eGift Cards";
					$hide_drop = true;
				}
				if($link['link_main'] == "findphotos") { 
					print "Find My Photos";
					$hide_drop = true;
				}
				if($link['link_main'] == "favorites") { 
					print "Favorites";
					$hide_drop = true;
				}

				if($link['link_main'] == "redeemcoupon") { 
					print "Redeem coupon";
					$hide_drop = true;
				}

			?>
			</div>
			<div class="clear"></div>
			</div>

			<?php } ?>
			

		<?php if(($link['link_main'] == "cart")||($link['link_main'] == "checkout")==true)  { ?>
		<div class="underline">
			<input type="checkbox" name="link_show_cart" id="link_show_cart" value="1" <?php if($link['link_show_cart'] == "1") { print "checked"; } ?>> Only show when products are in the shopping cart.
		</div>
		<?php } ?>

			<div class="underline" id="leftrightmenu" <?php if($_REQUEST['link_location']!=="shop") { ?>style="display: none;"<?php } ?>>
			<div style="width:30%; float: left;">WHERE</div><div style="width:70%; float: right;">
			<select name="link_shop_menu">
			<option value="accountmenu" <?php if($link['link_shop_menu'] == "accountmenu") { print "selected"; } ?>>Left Side</option>
			<option value="shopmenu" <?php if($link['link_shop_menu'] == "shopmenu") { print "selected"; } ?>>Right Side</option>
			</select>
			</div>
			<div class="clear"></div>
			</div>

			<?php
				if($link['link_main'] == "login") { ?>
			<div class="underline" >
			<div style="width:30%; float: left;">Action when clicking the log in link</div><div style="width:70%; float: right;">
			<input type="radio" name="link_login_page" <?php if($_REQUEST['link_login_page'] == "0") { print "checked"; } ?> value="0"> Open login on screen.
			<input type="radio" name="link_login_page" <?php if($_REQUEST['link_login_page'] == "1") { print "checked"; } ?> value="1"> Link to account login page.
			</div>
			<div class="clear"></div>
			</div>
				<?php } ?>

			<div class="underline">
				<div style="width:30%; float: left;">Link Text</div><div style="width:70%; float: right;"><input type="text" name="link_text" id="link_text"  value="<?php if(!empty($_REQUEST['link_text'])) { print htmlspecialchars(stripslashes($_REQUEST['link_text'])); } ?>" size="40" class=""></div>
				<div class="cssClear"></div>
			</div>

			<div class="underline">
				<div style="width:30%; float: left;">Link Icon</div><div style="width:70%; float: right;">
				<input type="radio" name="link_icon" id="icon-none" value="" <?php if(empty($link['link_icon'])) { print "checked"; } ?>> <label for="icon-none">None</label> &nbsp; 
				
				<input type="radio" name="link_icon" id="icon-home" value="icon-home" <?php if($link['link_icon']=="icon-home") { print "checked"; } ?>> <label for="icon-home"><span class="the-icons icon-home"></span></label>  &nbsp; 
				<input type="radio" name="link_icon" id="icon-facebook" value="icon-facebook" <?php if($link['link_icon']=="icon-facebook") { print "checked"; } ?>> <label for="icon-facebook"><span class="the-icons icon-facebook"></span></label>  &nbsp; 

				<input type="radio" name="link_icon" id="icon-twitter" value="icon-twitter" <?php if($link['link_icon']=="icon-twitter") { print "checked"; } ?>> <label for="icon-twitter"><span class="the-icons icon-twitter"></span></label>  &nbsp; 

				<input type="radio" name="link_icon" id="icon-mail" value="icon-mail" <?php if($link['link_icon']=="icon-mail") { print "checked"; } ?>> <label for="icon-mail"><span class="the-icons icon-mail"></span></label>  &nbsp; 



				<input type="radio" name="link_icon" id="icon-gplus" value="icon-gplus" <?php if($link['link_icon']=="icon-gplus") { print "checked"; } ?>> <label for="icon-gplus"><span class="the-icons icon-gplus"></span></label>  &nbsp; 

				<input type="radio" name="link_icon" id="icon-pinterest" value="icon-pinterest" <?php if($link['link_icon']=="icon-pinterest") { print "checked"; } ?>> <label for="icon-pinterest"><span class="the-icons icon-pinterest"></span></label>  &nbsp; 

				<input type="radio" name="link_icon" id="icon-instagram" value="icon-instagram" <?php if($link['link_icon']=="icon-instagram") { print "checked"; } ?>> <label for="icon-instagram"><span class="the-icons icon-instagram"></span></label>  &nbsp; 

				</nobr><input type="radio" name="link_icon" id="icon-linkedin" value="icon-linkedin" <?php if($link['link_icon']=="icon-linkedin") { print "checked"; } ?>> <label for="icon-linkedin"><span class="the-icons icon-linkedin"></span></label>  &nbsp; <nobr>

				<nobr><input type="radio" name="link_icon" id="icon-youtube" value="icon-youtube" <?php if($link['link_icon']=="icon-youtube") { print "checked"; } ?>> <label for="icon-youtube"><span class="the-icons icon-youtube"></span></label>  &nbsp; </nobr>

				<nobr><input type="radio" name="link_icon" id="icon-basket" value="icon-basket" <?php if($link['link_icon']=="icon-basket") { print "checked"; } ?>> <label for="icon-basket"><span class="the-icons icon-basket"></span></label>  &nbsp; </nobr>

				<nobr><input type="radio" name="link_icon" id="icon-down-open" value="icon-down-open" <?php if($link['link_icon']=="icon-down-open") { print "checked"; } ?>> <label for="icon-down-open"><span class="the-icons icon-down-open"></span></label>  &nbsp; </nobr>


				<nobr><input type="radio" name="link_icon" id="icon-heart" value="icon-heart" <?php if($link['link_icon']=="icon-heart") { print "checked"; } ?>> <label for="icon-heart"><span class="the-icons icon-heart"></span></label>  &nbsp; </nobr>



				<input type="radio" name="link_icon" id="icon-user" value="icon-user" <?php if($link['link_icon']=="icon-user") { print "checked"; } ?>> <label for="icon-user"><span class="the-icons icon-user"></span></label>  &nbsp; 


				<input type="radio" name="link_icon" id="icon-info-circled" value="icon-info-circled" <?php if($link['link_icon']=="icon-info-circled") { print "checked"; } ?>> <label for="icon-info-circled"><span class="the-icons icon-info-circled"></span></label>  &nbsp; 

				<input type="radio" name="link_icon" id="icon-calendar" value="icon-calendar" <?php if($link['link_icon']=="icon-calendar") { print "checked"; } ?>> <label for="icon-calendar"><span class="the-icons icon-calendar"></span></label>  &nbsp; 

				</div>
				<div class="cssClear"></div>
			</div>


			<div class="underline">
				<div style="width:30%; float: left;">Open in</div><div style="width:70%; float: right;"><input type="radio" name="link_open" value="_top" <?php if((empty($_REQUEST['subdo']))OR($_REQUEST['link_open']=="_top")==true) { print "checked"; } ?>> Same Window<br><input type="radio" name="link_open" value="_blank" <?php if((!empty($_REQUEST['subdo']))AND($_REQUEST['link_open']=="_blank")==true) { print "checked"; } ?>> New Window</div>
				<div class="cssClear"></div>
			</div>

			

		<script>
		function linkmoreoptions(){ 
			$("#linkmoreoptions").slideToggle(200);
		}
		</script>
		<div class="pc center"><a href="" onclick="linkmoreoptions(); return false;">more options</a></div>

			<div id="linkmoreoptions" class="hide"> 
			<div class="underline">
				<input type="checkbox" name="link_logged_in" id="link_logged_in" value="1" <?php if($link['link_logged_in'] == "1") { print "checked"; } ?>> <label for="link_logged_in">Only show this link when visitor is logged into their account.</label>
			</div>
			<div class="underline">
				<input type="checkbox" name="link_logged_out" id="link_logged_out" value="1" <?php if($link['link_logged_out'] == "1") { print "checked"; } ?>> <label for="link_logged_out">Only show this link when visitor is not logged into their account.</label>
			</div>

			<?php if($hide_drop !== true) { ?>
			<div class="underline">
				<div class="label">Drop Down Menu</div>
					<div class="underlinespacer">
					<div style="width:30%; float: left;">If this link is a top section, you can create a drop down menu. This only works for sections (not pages).</div><div style="width:70%; float: right;">
					
					<input type="radio" name="link_dropdown" id="link_dropdown1" value="" <?php if(empty($_REQUEST['link_dropdown'])) { print "checked"; } ?>> <label for="link_dropdown1">No drop down</label><br>
					<input type="radio" name="link_dropdown" id="link_dropdown2" value="cats" <?php if($_REQUEST['link_dropdown']=="cats") { print "checked"; } ?>> <label for="link_dropdown2">Categories in section</label><br>
					<input type="radio" name="link_dropdown" id="link_dropdown3" value="pages" <?php if($_REQUEST['link_dropdown']=="pages"){ print "checked"; } ?>> <label for="link_dropdown3">Pages in section</label></div>
					<div class="cssClear"></div>
				</div>
			</div>
			<?php if($setup['sytistsite'] == true) { ?>
			<div class="underline">
			<div>HTML menu. </div>
			<textarea name="link_html" id="link_html" rows="4" class="field100"><?php print htmlspecialchars($link['link_html']);?></textarea>
			</div>
			<?php } ?>

			<div class="underline">
				<div class="label">Custom Drop Down Menu Links </div>
				<div class="underlinespacer">Here you can create your own drop down menu links. If you enter anything in here, it will override the options in the above drop down menu option.
				<br><br>
				The format  is link|text , one per line. It MUST start with http:// or https://, Example: <br>
				<span style="color: #002200;">http://www.goggle.com|Google</span>
				</div>
				<div><textarea name="link_dropdown_links" id="link_dropdown_links" rows="6" class="field100"><?php print htmlspecialchars($link['link_dropdown_links']);?></textarea></div>
				<div class="underlinespacer">If you want the link to open in a new tab, add |_blank at the end. Example: <br>
				<span style="color: #002200;">http://www.goggle.com|Google|_blank</span>
				</div>
			</div>

			<div class="underline">
				<input type="checkbox" name="link_no_click" id="link_no_click" value="1" <?php if($_REQUEST['link_no_click'] == "1") { print "checked"; } ?>> <label for="link_no_click">If using a drop down menu, select this option to make the main link not clickable</label>
			</div>


			<div class="underline">
				<input type="checkbox" name="link_open_drop_mobile" id="link_open_drop_mobile" value="1" <?php if($link['link_open_drop_mobile'] == "1") { print "checked"; } ?>> <label for="link_open_drop_mobile">Drop down menu items on mobile, display as opened all the time. Otherwise, dropdown items will be shown when menu item is touched.</label>
			</div>


			<div class="underline">
				<input type="checkbox" name="link_mobile_cats" id="link_mobile_cats" value="1" <?php if($link['link_mobile_cats'] == "1") { print "checked"; } ?>> <label for="link_mobile_cats">Show categories in sections on mobile menu even if not using a drop down in the main menu</label>
			</div>

			<?php } ?>
		</div>
			<div class="underline" style="text-align: center;">
			<input type="hidden" name="action" value="save">
			<input type="hidden" name="link_id" value="<?php print $_REQUEST['link_id'];?>">
			<input type="hidden" name="from_menu" value="<?php print $_REQUEST['from_menu'];?>">
			<input type="submit" name="submit" value="Save Link" class="submit">

			<div class="cssClear"></div>
	</form>
	<div >&nbsp;</div>
</div>
<?php require "w-footer.php"; ?>
