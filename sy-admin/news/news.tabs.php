<?php 
$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "WHERE date_id='".$date['date_id']."' ");

if($date['cat_type'] == "proofing") { 
	include "news.proofing.php";
}
?>

<div class="buttonsgray">
	<ul>
	<?php if(!empty($_REQUEST['date_id'])) { ?>

	<li><a href="index.php?do=news&action=addDate&date_id=<?php print $date['date_id'];?>&page_under=<?php print $date['page_under'];?>" class="tip <?php if($_REQUEST['action'] == "addDate") { print "on"; }  ?>" title="Page text & settings">TEXT & SETTINGS</a></li>

		<li>
		<?php if($date['page_404'] !== "1") { 
		if(!empty($date['date_photo_keywords'])) {
		$and_date_tag = "( ";
		$date_tags = explode(",",$date['date_photo_keywords']);
		foreach($date_tags AS $tag) { 
			$cx++;
			if($cx > 1) { 
				$and_date_tag .= " OR ";
			}
			$and_date_tag .=" key_key_id='$tag' ";
		}
		$and_date_tag .= " OR bp_blog='".$date['date_id']."' ";
		$and_date_tag .= " ) ";
			
			?>
	<a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>" class="tip <?php if($_REQUEST['action'] == "managePhotos") { print "on"; } ?>" title="Upload photos, create sub galleries, photo display settings, etc...">PHOTOS (<?php $piccount = whileSQL("ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE $and_date_tag $and_where AND pic_no_dis<='0' GROUP BY pic_id  ORDER BY ".$date['date_photos_keys_orderby']." ".$date['date_photos_keys_acdc']." "); print mysqli_num_rows($piccount);?>)</a>
		<?php } else { ?>
	<a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>" class="tip <?php if($_REQUEST['action'] == "managePhotos") { print "on"; } ?>" title="Upload photos, create sub galleries, photo display settings, etc...">PHOTOS (<?php print  countIt("ms_blog_photos", "WHERE bp_blog='".$date['date_id']."' ");?>)</a>
		<?php } ?><a href="" onclick="openFrame('w-photos-upload.php?date_id=<?php print $date['date_id'];?>'); return false;" class="tip" title="Upload Photos"><?php print ai_arrow_up;?></a></li>
		<?php } ?>

		<li>
		<?php if($date['page_home'] == "1") { 
			?>
			<a  href="<?php tempFolder();?>/<?php print $site_setup['index_page'];?>" target="_blank" class="tip" title="View this page on your website">
			<?php 
		} elseif($date['page_404'] =="1") { 
			print "<a  href=\"/404.php\" target=\"_blank\">";
		} else { ?>

			<a  href="<?php tempFolder();?><?php print $setup['content_folder']."".$dcat['cat_folder']."/".$date['date_link'];?>/" target="_blank" class="tip" title="View this page on your website">
			<?php 
		}
		?> VIEW</a></li>
		<?php if(($date['page_404'] !== "1") && ($date['cat_type'] !== "proofing")==true) { ?>
		<li><a href="index.php?do=news&action=thumbPreview&date_id=<?php print $date['date_id'];?>" class="tip <?php if($_REQUEST['action'] == "thumbPreview") { print "on"; } ?>" title="Upload a preview / cover photo for this page">PREVIEW PHOTO</a></li>
		<?php } ?>
		<?php if($cat['cat_type'] == "store") { 
			if(($date['prod_type'] !== "download")&&($date['prod_type'] !== "package") == true) { ?>
		<li><a href="index.php?do=news&action=subProds&date_id=<?php print $date['date_id'];?>" class="tip <?php if($_REQUEST['action'] == "subProds") { print "on"; }  ?>"  title="Create or edit sub products"> SUB PRODUCTS</a></li>
		<?php } ?>
		<?php } ?>
		<?php if(($date['page_home']  !== "1")&&($date['page_404'] !=="1")==true) { ?>
		<li><a href=""  class="confirmdeleteoptions tip" confirm-title="Delete Page: <?php print htmlspecialchars($date['date_title']);?>" confirm-message="Select from the options below" option-link-1="index.php?do=news&deleteDate=<?php print $date['date_id'];?>"  option-link-1-text="Delete page and leave photos in the system"  option-link-2="index.php?do=news&deleteDate=<?php print $date['date_id'];?>&deletephotos=1" option-link-2-text="Delete page and delete all photos assigned to the page from the system" title="Delete this page">DELETE</a></li><?php } ?>
		
		<li><a href="index.php?do=news&action=splash&date_id=<?php print $date['date_id'];?>" class="tip <?php if($_REQUEST['action'] == "splash") { print "on"; } ?>" title="Create or edit splash window">SPLASH WINDOW <?php if($date['splash_enable'] == "1") { ?><img  src="graphics/icons/green.png" width="16" height="16" align="absmiddle" title="Enabled"><?php } ?></a></li>

		<?php if($date['cat_type'] !== "proofing") { ?>
			<?php 
			//if($date['cat_type'] == "clientphotos") { 
			$emails = array(); 

			$ps = whileSQL("ms_my_pages LEFT JOIN ms_people ON ms_my_pages.mp_people_id=ms_people.p_id", "*, date_format(DATE_ADD(mp_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS mp_date", "WHERE mp_date_id='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
			while($p = mysqli_fetch_array($ps)) { 
				if(!in_array($p['p_email'],$emails)) { 
					array_push($emails,$p['p_email']);
				}
			}
			$ps = whileSQL("ms_view_page LEFT JOIN ms_people ON ms_view_page.v_person=ms_people.p_id", "*, date_format(DATE_ADD(v_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS v_date", "WHERE v_page='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
			while($p = mysqli_fetch_array($ps)) {
				if(!in_array($p['p_email'],$emails)) { 
					array_push($emails,$p['p_email']);
				}
			}

			$ps = whileSQL("ms_pre_register", "*", "WHERE reg_date_id='".$date['date_id']."'  ");
			while($p = mysqli_fetch_array($ps)) {
				if(!in_array($p['reg_email'],$emails)) { 
					array_push($emails,$p['reg_email']);
				}
			}

		//	$ps = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date", "WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0'   AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  GROUP BY order_email ORDER BY order_last_name ASC ");

				$ps = mysqli_query($dbcon,"
				SELECT *, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date FROM (
				SELECT *  FROM ms_cart 
				 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
				WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  

			  UNION ALL

				SELECT *  FROM ms_cart_archive 
				 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
				WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2' 
				) 
				x 
				GROUP BY order_email ORDER BY order_last_name ASC
				");

				if (!$ps) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }

				while($p = mysqli_fetch_array($ps)) {
					if(!empty($p['order_email'])) { 
						if(!in_array($p['order_email'],$emails)) { 
							array_push($emails,$p['order_email']);
						}
					}
				}				

			if($date['private'] > 0) {
				$emid = doSQL("ms_emails", "*", "WHERE email_id_name='inviteprivate' ");
				$em = $emid['email_id'];
			} else { 
				$emid = doSQL("ms_emails", "*", "WHERE email_id_name='invitepublic' ");
				$em = $emid['email_id'];
			}

			if($date['cat_type'] == "proofing") { 
			?>
			<li><a href="" onclick="newsusersproofing('<?php print $date['date_id'];?>'); return false;">PEOPLE (<?php print count($emails);?>)</a></li>
			<?php } else { ?>
			<li><a href="" onclick="newsusers('<?php print $date['date_id'];?>','<?php print $em;?>'); return false;" class="tip" title="Manage & Email People">PEOPLE (<?php print count($emails);?>) <?php print ai_mail_white;?></a> </li>
			<?php } ?>
			<?php// } ?>
		<?php } ?>


			<?php if($date['cat_type'] == "clientphotos") { 
				$eb = doSQL("ms_promo_codes", "*, date_format(DATE_ADD(code_end_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS exp_show", "WHERE code_date_id='".$date['date_id']."' ");
				if(!empty($eb['code_id'])) { 
					if(($eb['code_end_date'] < date('Y-m-d'))&&($eb['code_end_date'] !== "0000-00-00")==true) { 
						$exp = true;
					}
				}
				?>
			<li><a href="" onclick="editcoupon('<?php print $eb['code_id'];?>','<?php print $date['date_id'];?>'); return false;" title="Early Bird Special <?php if(!empty($eb['code_id'])) { print " expires ".$eb['exp_show']; } ?>" class="tip">EARLY BIRD <?php if(!empty($eb['code_id'])) { if($exp == true) { print "Expired"; } else { ?><img  src="graphics/icons/green.png" width="16" height="16" align="absmiddle" title="Enabled"><?php } } ?></a></li>
			<?php 	$wset = doSQL("ms_wall_settings", "*", "  "); 
			if($wset['admin_link'] == "1") { ?>
			<li><a  href="<?php tempFolder();?><?php print $setup['content_folder']."".$dcat['cat_folder']."/".$date['date_link'];?>/?view=room" target="_blank" class="tip" title="Go to Wall Designer">WALL DESIGNER</a></li>
			<?php }	?>

			<?php } ?>

		<?php /* SWEETNESS */
		if($date['page_home'] == "1") { 
		$show = doSQL("ms_show", "*", "WHERE feat_page_id='".$date['date_id']."' AND enabled='1' AND default_feat<='0' ");

	?>
	<li><a href="" onclick="sweetness('<?php print $show['show_id'];?>','<?php print $date['date_id'];?>',''); return false;">CLF-DISPLAY
			<img class="sweetstatus <?php if((($show['show_id'] > 0)&&($show['enabled'] == "0")||($show['show_id'] <=0))==true) { ?>hidden<?php } ?>" id="sweet-<?php print $date['date_id'];?>-on" src="graphics/icons/green.png" width="16" height="16" align="absmiddle" title="Enabled">
		<img  class="sweetstatus <?php if((($show['show_id'] > 0)&&($show['enabled'] == "1")||($show['show_id'] <=0))==true) { ?>hidden<?php } ?>"  id="sweet-<?php print $date['date_id'];?>-off" src="graphics/icons/red.png" width="16" height="16" align="absmiddle"  title="Not enabled">
	</a>
	</li>

	<?php 
	} ?>
		<li><a href="" onclick="newsstats('<?php print $date['date_id'];?>'); return false;" class="tip" title="View statistics & sales for  this page">STATS</a></li>
<?php } ?>



	<div class="cssClear"></div>
	</ul>
</div>
