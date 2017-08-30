<?php
include "../sy-config.php";
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php"; 
require "admin.functions.php"; 
require("admin.icons.php");
require("photos.functions.php");
if($setup['sytist_hosted'] == true) { 
	require $setup['path']."/sy-hosted.php";
}

$sytist_store = true;
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$store = doSQL("ms_store_settings", "*", "");

$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}

if($site_setup['cron_enabled'] !== "1") { die("Cron not enabled"); } 

$email_per  = $site_setup['cron_emails_per'];
$sleep_time = $site_setup['cron_sleep'];
$log_cron = 1;
if($site_setup['cron_test_mode'] == "1") { 
	$and_test = "AND test_email='1' ";
}
$ems = whileSQL("ms_cron_emails", "*", "WHERE date_time_to_send<='".date('Y-m-d H:i:s')."' $and_test ORDER BY priority DESC, id ASC LIMIT ".$email_per." ");
while($em = mysqli_fetch_array($ems)) { 
	 sendWebdEmail($em['to_email'],$em['to_name'],$em['from_email'],$em['from_name'],stripslashes($em['subject']),stripslashes($em['content']),'1');
	print "<li>Sent: ".$em['id'];
	deleteSQL("ms_cron_emails", "WHERE id='".$em['id']."' ", "1");
	sleep($sleep_time);
}




function addtocron($to_email,$to_first_name,$to_last_name,$email_id,$date_id,$book_id,$date_time_to_send,$priority,$order_id,$gc_id) { 
	global $setup,$site_setup;
	if($book_id > 0) { 
		$ck = doSQL("ms_cron_emails", "*", "WHERE to_email='".addslashes(stripslashes($to_email))."' AND from_book_id='".$book_id."' ");
	} else if($order_id > 0) { 
		$ck = doSQL("ms_cron_emails", "*", "WHERE to_email='".addslashes(stripslashes($to_email))."' AND from_order_id='".$order_id."' ");
	} else if($gc_id > 0) { 
		$ck = doSQL("ms_cron_emails", "*", "WHERE to_email='".addslashes(stripslashes($to_email))."' AND from_gc_id='".$gc_id."' ");
	} else { 
		$ck = doSQL("ms_cron_emails", "*", "WHERE to_email='".addslashes(stripslashes($to_email))."' AND from_date_id='".$date_id."' AND from_email_id='".$email_id."' ");
	}
	if(empty($ck['id'])) { 

		if($date_id > 0) { 
			$em = doSQL("ms_emails", "*", "WHERE email_id='".$email_id."' ");
			$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show, date_format(reg_event_date, '".$site_setup['date_format']." ')  AS reg_event_date_show", "WHERE date_id='".$date_id."' ");
			$subject = $em['email_subject'];
			$message = $em['email_message'];
			$sending_subject = $em['email_subject'];
			$eb = doSQL("ms_promo_codes", "*, date_format(code_end_date, '".$site_setup['date_format']." ')  AS code_end_date", "WHERE code_date_id='".$date['date_id']."' ");
		}
		if($book_id > 0) { 
			$book = doSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." ')  AS book_date, time_format(book_time, '".$site_setup['date_time_format']."')  AS book_time_show ,date_format(DATE_ADD(book_date_added, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." %l:%i %p')  AS book_date_added",  "WHERE book_id='".$book_id."' ");
			$subject = $book['book_reminder_email_subject'];
			$message = $book['book_reminder_email'];
			$sending_subject =  $book['book_reminder_email_subject'];
		}

		if($order_id > 0) { 
			$em = doSQL("ms_emails", "*", "WHERE email_id_name='paymentreminder' ");
			$order = doSQL("ms_payment_schedule LEFT JOIN ms_orders ON ms_payment_schedule.order_id=ms_orders.order_id", "*,date_format(due_date, '".$site_setup['date_format']."')  AS due_date_show,date_format(payment_date, '".$site_setup['date_format']."')  AS payment_date_show", "WHERE ms_payment_schedule.order_id='".$order_id."' ");
			$subject = $em['email_subject'];
			$message = $em['email_message'];
			$sending_subject = $em['email_subject'];
		}
		if($gc_id > 0) { 
			$em = doSQL("ms_emails", "*", "WHERE email_id_name='giftcertificate' ");
			$gc = doSQL("ms_gift_certificates", "*", "WHERE id='".$gc_id."' ");
			$subject = $em['email_subject'];
			$message = $em['email_message'];
			$sending_subject = $em['email_subject'];
		}


		if(!empty($to_email)) { 
			if(empty($em['email_from_email'])) {
				$from_email = $site_setup['contact_email'];
			} else {
				$from_email = $em['email_from_email'];
			}

			if(empty($em['email_from_name'])) {
				$from_name = $site_setup['website_title'];
			} else {
				$from_name = $em['email_from_name'];
			}
			if($book['book_service'] <= 0) { 
				$message = str_replace("[BOOKING_SERVICE]",$book['book_event_name'], $message);
			} else { 
				$message = str_replace("[BOOKING_SERVICE]",$book['date_title'], $message);
			}
			$message = str_replace("[BOOKING_TIME]",$book['book_time_show'], $message);
			$message = str_replace("[BOOKING_DATE_TIME]",$book['book_date']." ".$book['book_time_show'], $message);
			$message = str_replace("[BOOKING_DATE]",$book['book_date'], $message);

			$opts = explode("\n",$book['book_options']);
			foreach($opts AS $opt) { 
				if(!empty($opt)) { 
					$o = explode("|",$opt);
					if(!empty($o[0])) { 
						$options .= $o[0]; if(!empty($o[1])) { $options .= ": ".$o[1];} if($o[2] > 0) { $options .= "  ".showPrice($o[2]); } $options .= "<br>";
					}
				}
			}
			### Gift Cards ########## 
			$glang = doSQL("ms_gift_certificate_language", "*", " ");

			if($gc['id'] > 0) { 
				$card = $glang['gift_card_style'];
				$card = str_replace("[REDEEM_CODE]",$gc['redeem_code'], $card);
				$card = str_replace("[WEBSITE_NAME]",$site_setup['website_title'], $card);
				$card = str_replace("[AMOUNT]","".showPrice($gc['amount'])."", $card);
				$card = str_replace("contenteditable","", $card);
				$message = str_replace("[GIFT_CARD]",$card,$message);

				$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
				$message = str_replace("[AMOUNT]","".showPrice($gc['amount'])."", "$message");
				$message = str_replace("[WEBSITE_NAME]","<a href=\"".$setup['url'].$setup['temp_url_folder']."\">".$site_setup['website_title']."</a>", "$message");
				$message = str_replace("[NAME]",$gc['to_name'], "$message");
				$message = str_replace("[EMAIL_ADDRESS]",$p['p_email'], "$message");
				$message = str_replace("[MESSAGE]",nl2br($gc['message']), "$message");
				$message = str_replace("[BUYER_NAME]",$gc['from_name'], "$message");
				$message = str_replace("[BUYER_EMAIL]",$gc['from_email'], "$message");
				$message = str_replace("[REDEEM_CODE]",$gc['redeem_code'], "$message");

				$message = str_replace("[LINK]",$setup['url'].$setup['temp_url_folder'].$date['cat_folder']."/".$date['date_link']."/", "$message");


				$subject = str_replace("[NAME]",$gc['to_name'], "$subject");
				$subject = str_replace("[WEBSITE_NAME]",$site_setup['website_title'], "$subject");
				$subject = str_replace("[AMOUNT]","".showPrice($gc['amount']), "$subject");
				$subject = str_replace("[BUYER_NAME]",$gc['from_name'], "$subject");
				updateSQL("ms_gift_certificates", "emailed_date='".date('Y-m-d')."' WHERE id='".$gc['id']."' ");
			}
			## For scheduled payments ##
				$scs = whileSQL("ms_payment_schedule", "*,date_format(due_date, '".$site_setup['date_format']."')  AS due_date_show,date_format(payment_date, '".$site_setup['date_format']."')  AS payment_date_show", "WHERE order_id='".$order['order_id']."' ORDER BY due_date ASC ");

				$add .= "<p>";
				while($sc = mysqli_fetch_array($scs)) { 
					if($sc['payment'] > 0) { 
						$add .= showPrice($sc['payment']) ." "._paid_." ".$sc['payment_date_show'];
					} else { 
						$add .= showPrice($sc['amount'])." "._due_." ".$sc['due_date_show'];
					}
					$add .= "<br>";
				 }
				$add.= "</p>";

				$message = str_replace("[SCHEDULED_PAYMENTS]",$add, "$message");


				$subject = str_replace("[INVOICE_NUMBER]",$order['order_id'], "$subject");
				$message = str_replace("[INVOICE_NUMBER]",$order['order_id'], "$message");

	

			$message = str_replace("[BOOKING_OPTIONS]",$options, $message);


			$message = str_replace("[EARLY_BIRD_SPECIAL_DATE]",$eb['code_end_date'], $message);


			$message = str_replace("[URL]",$site_setup['cron_site_url'], "$message");
			$message = str_replace("[ACCOUNT_LINK]","<a href=\"".$site_setup['cron_site_url']."/index.php?view=account\">".$site_setup['cron_site_url']."/index.php?view=account</a>", "$message");
			$message = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$message");
			$message = str_replace("[EXPIRATION_DATE]",$date['date_expire_show'], "$message");
			$message = str_replace("[LINK]","<a href=\"".$site_setup['cron_site_url'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$site_setup['cron_site_url'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/</a>", "$message");
			$message = str_replace("[link]","<a href=\"".$site_setup['cron_site_url'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$site_setup['cron_site_url'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/</a>", "$message");
			$message = str_replace("[PASSWORD]",$date['password'], "$message");
			$message = str_replace("[PAGE_TITLE]","<a href=\"".$site_setup['cron_site_url'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$date['date_title']."</a>", "$message");
			$message = str_replace("[PAGE_LINK]","<a href=\"".$site_setup['cron_site_url'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."\">", $message);
			$message = str_replace("[/PAGE_LINK]","</a>", $message);
			$message = str_replace("[LINK_TO_WEBSITE]","<a href=\"".$site_setup['cron_site_url']."\">", $message);
			$message = str_replace("[/LINK_TO_WEBSITE]","</a>", $message);
			if($site_setup['checkout_ssl'] == "1") { 
				$message = str_replace("[INVOICE_LINK]","<a href=\"".$site_setup['cron_site_url']."/index.php?view=order&action=orderk&oe=".MD5($order['order_email'])."&on=".MD5($order['order_id'])."\">", $message);
			} else { 
				$message = str_replace("[INVOICE_LINK]","<a href=\"".$site_setup['cron_site_url']."/index.php?view=order&action=orderk&oe=".MD5($order['order_email'])."&on=".MD5($order['order_id'])."\">", $message);
			}
			$message = str_replace("[/INVOICE_LINK]","</a>", $message);
			$message = str_replace("[/LINK]","</a>", $message);
			$message = str_replace("[INVOICE_DUE]",$order['order_due_date_show'], $message);
			$message = str_replace("[INVOICE_TOTAL]","".showPrice($order['order_total'])."", "$message");
			$message = str_replace("[EMAIL]",$to_email, "$message");

			$message = str_replace("[FIRST_NAME]",stripslashes($to_first_name), "$message");
			$message = str_replace("[LAST_NAME]",stripslashes($to_last_name), "$message");

			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'   AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
			if(empty($pic['pic_id'])) {
				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
			}
			$pic['full_url'] = true;
			$sizel = getimagefiledems($pic,'pic_large');
			$sizes = getimagefiledems($pic,'pic_pic');
			$sizet = getimagefiledems($pic,'pic_th');

			$message = str_replace("[IMAGE_LARGE]","<a href=\"".$site_setup['cron_site_url'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getcronimagefile($pic,'pic_large')."\" style=\"width:100%; max-width: ".$sizel[0]."px; height: auto;\"></a>", $message);
			$message = str_replace("[IMAGE_SMALL]","<a href=\"".$site_setup['cron_site_url'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getcronimagefile($pic,'pic_pic')."\" style=\"width:100%; max-width: ".$sizes[0]."px; height: auto;\"></a>", $message);
			$message = str_replace("[IMAGE_THUMBNAIL]","<a href=\"".$site_setup['cron_site_url'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getcronimagefile($pic,'pic_th')."\" style=\"width:100%; max-width: ".$sizet[0]."px; height: auto;\"></a>", $message);


			if($date_id > 0) { 
				$unsubscribe = str_replace("[UNSUBSCRIBE_LINK]","<a href=\"".$site_setup['cron_site_url']."/index.php?view=unsubscribenotices&em=".strtolower($to_email)."\">",$site_setup['cron_unsubscribe']);
				$unsubscribe = str_replace("[/UNSUBSCRIBE_LINK]","</a>",$unsubscribe);
				$message = $message."<p><center>".$unsubscribe."</center></p>";
			}
			$subject = str_replace("[PAGE_TITLE]",$date['date_title'], "$subject");
			$subject = str_replace("[EXPIRATION_DATE]",$date['date_expire'], "$subject");
			$subject = str_replace("[BOOKING_DATE]",$book['book_date'], $subject);
			$subject = str_replace("[BOOKING_TIME]",$book['book_time_show'], $subject);
			$subject = str_replace("[BOOKING_DATE_TIME]",$book['book_date']." ".$book['book_time_show'], $subject);
			$subject = str_replace("[EARLY_BIRD_SPECIAL_DATE]",$eb['code_end_date'], $subject);


			$subject = str_replace("[FIRST_NAME]",stripslashes($to_first_name), "$subject");
			$subject = str_replace("[LAST_NAME]",stripslashes($to_last_name), "$subject");

			$subject = str_replace("[URL]",$site_setup['cron_site_url'], "$subject");
			$subject = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$subject");



			insertSQL("ms_cron_emails", "
			to_email='".addslashes(stripslashes($to_email))."', 
			to_name='".addslashes(stripslashes($to_first_name." ".$to_last_name))."', 
			from_email='".addslashes(stripslashes($from_email))."', 
			from_name='".addslashes(stripslashes($from_name))."', 
			subject='".addslashes(stripslashes($subject))."', 
			content='".addslashes(stripslashes($message))."', 
			date_time_to_send='".addslashes(stripslashes($date_time_to_send))."', 
			from_date_id='".addslashes(stripslashes($date_id))."', 
			from_book_id='".addslashes(stripslashes($book_id))."', 
			from_email_id='".addslashes(stripslashes($email_id))."', 
			priority='".addslashes(stripslashes($priority))."'
			");

			if($book_id > 0) { 

				$subject = "COPY: ".$subject;
				$message = "<p>This is a copy of the reminder email just sent to ".$to_first_name." ".$to_last_name." (".$to_email.")</p><p>------------------------------------------</p>".$message;

				insertSQL("ms_cron_emails", "
				to_email='".addslashes(stripslashes($from_email))."', 
				to_name='".addslashes(stripslashes($from_name))."', 
				from_email='".addslashes(stripslashes($from_email))."', 
				from_name='".addslashes(stripslashes($from_name))."', 
				subject='".addslashes(stripslashes($subject))."', 
				content='".addslashes(stripslashes($message))."', 
				date_time_to_send='".addslashes(stripslashes($date_time_to_send))."', 
				from_date_id='".addslashes(stripslashes($date_id))."', 
				from_book_id='".addslashes(stripslashes($book_id))."', 
				from_email_id='".addslashes(stripslashes($email_id))."', 
				priority='".addslashes(stripslashes($priority))."'
				");


			}
		}
	}
}



function getcronimagefile($pic,$pic_file) { 
	global $setup,$full_url,$site_setup;
	if($pic['pic_amazon'] == "1") { 
		return "http://".$site_setup['amazon_endpoint']."/".$pic['pic_bucket']."/".$pic['pic_bucket_folder']."/".urlencode($pic[$pic_file]);
	} else { 
		return $site_setup['cron_site_url']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file];
	}
}

function removeFromArr($arr, $val){
    unset($arr[array_search($val, $arr)]);
    return array_values($arr);
}


##    Make option to make regular emails get sent by cron like when emailing people that a gallery is active. 
##	Make those a priority


### Getting cron jobs for expiring galleries
$crons = whileSQL("ms_crons", "*", "WHERE cron_what='galleries' AND  cron_status='1' AND cron_check_date<'".date('Y-m-d')."' ORDER BY cron_id ASC ");
while($cron = mysqli_fetch_array($crons)) { 
	print "<li>cron id: ".$cron['cron_id'].": ".date('Y-m-d');

	## Creating expiration date
	$expire = date("Y-m-d", mktime(0, 0, 0, date('m'),date('d') + $cron['cron_days'], date('Y'))); 


	## Get the mailout to send
	$mailout = doSQL("ms_emails", "*", "WHERE email_id='".$cron['cron_email']."' ");
	if(empty($mailout['email_id'])) { die(); } 

	### Getting galleries that are expiring on the date
	$dates = whileSQL("ms_calendar", "*", "WHERE date_expire='".$expire."' AND date_public='1' ORDER BY date_id ASC ");
	while($date = mysqli_fetch_array($dates)) { 
		unset($emails);
		$emails = array();

	
		if($cron['cron_gal_owner'] == "1") { 
			### People with access to gallery #### 
			$ps = whileSQL("ms_people","*","WHERE p_id='".$date['date_owner']."' AND p_email!='' ");
			while($p = mysqli_fetch_array($ps)) { 
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['p_email'])))."' ");
				if(empty($ck['id'])) { 
					if(!in_array($p['p_email'],$emails)) { 
						array_push($emails,$p['p_email']);
					}
					addtocron($p['p_email'],$p['p_name'],$p['p_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
				}
			}
		}



		if($cron['cron_gal_access'] == "1") { 
			### People with access to gallery #### 
			$ps = whileSQL("ms_my_pages LEFT JOIN ms_people ON ms_my_pages.mp_people_id=ms_people.p_id", "*, date_format(DATE_ADD(mp_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS mp_date", "WHERE mp_date_id='".$date['date_id']."' AND p_id>'0' AND p_deactivated<='0' AND p_email!='' ORDER BY p_id ASC ");
			while($p = mysqli_fetch_array($ps)) { 
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['p_email'])))."' ");
				if(empty($ck['id'])) { 
					if(!in_array($p['p_email'],$emails)) { 
						array_push($emails,$p['p_email']);
					}
					addtocron($p['p_email'],$p['p_name'],$p['p_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
				}
			}
		}

		if($cron['cron_gal_preregister'] == "1") { 
			### People that have pre-registered #### 
			$ps = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."'  AND toview<='0'  ORDER BY reg_id DESC");
			while($p = mysqli_fetch_array($ps)) {
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['reg_email'])))."' ");
				if(empty($ck['id'])) { 
					addtocron($p['reg_email'],$p['reg_first_name'],$p['reg_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
					if(!in_array($p['p_email'],$emails)) { 
						array_push($emails,$p['reg_email']);
					}
				}
			}
		}

		if($cron['cron_gal_viewed'] == "1") { 
			### Registered people who have viewed the page ###
			$ps = whileSQL("ms_view_page LEFT JOIN ms_people ON ms_view_page.v_person=ms_people.p_id", "*, date_format(DATE_ADD(v_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS v_date", "WHERE v_page='".$date['date_id']."' AND p_id>'0'  AND p_deactivated<='0' AND p_email!='' ORDER BY p_last_name ASC ");
			while($p = mysqli_fetch_array($ps)) {
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['p_email'])))."' ");
				if(empty($ck['id'])) { 
					addtocron($p['p_email'],$p['p_name'],$p['p_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
					if(!in_array($p['p_email'],$emails)) { 
						array_push($emails,$p['p_email']);
					}
				}
			}
		}

		if($cron['cron_gal_purchased'] == "1") { 
			### People who have purchased ###
			// $ps = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date", "WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  GROUP BY order_email ORDER BY order_last_name ASC ");

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
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['order_email'])))."' ");
				if(empty($ck['id'])) { 
					addtocron($p['order_email'],$p['order_first_name'],$p['order_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
					if(!in_array($p['order_email'],$emails)) { 
						array_push($emails,$p['p_email']);
					}
				}
			}
		}
		if($cron['cron_gal_collected_email'] == "1") { 
			### Emails collected to view gallery ###
			$ps = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."'  AND toview='1'  ORDER BY reg_id DESC");
			while($p = mysqli_fetch_array($ps)) {
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['reg_email'])))."' ");
				if(empty($ck['id'])) { 
					addtocron($p['reg_email'],$p['reg_first_name'],$p['reg_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
					if(!in_array($p['reg_email'],$emails)) { 
						array_push($emails,$p['p_email']);
					}
				}
			}
		}

		if($cron['cron_gal_no_order'] == "1") { 
			### People who have purchased ###
		//	$ps = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date", "WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  GROUP BY order_email ORDER BY order_last_name ASC ");

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
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['order_email'])))."' ");
				if(empty($ck['id'])) { 
					if(in_array($p['order_email'],$emails)) { 
						removeFromArr($emails,$p['order_email']);
						// print "<li>Removing: ".$p['order_email'];

						$ckcron = deleteSQL2("ms_cron_emails", "WHERE LOWER(to_email)='".strtolower($p['order_email'])."' AND from_date_id='".$date['date_id']."' AND from_email_id='".$mailout['email_id']."' ");
					} else { 
						addtocron($p['order_email'],$p['order_first_name'],$p['order_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
						if(!in_array($p['order_email'],$emails)) { 
							array_push($emails,$p['p_email']);
						}
					}
				}
			}
		}

		## Send notice to admin. 
		$this_subject = "Automated Emails For ".$date['date_title']." Set To Send at ".$site_setup['website_title']." ".date('Y-m-d');
		$this_message = "<p>Notice of automated emails at ".$site_setup['cron_site_url']."</p><p>The email with the subject \"".$mailout['email_subject']."\" for gallery \"".$date['date_title']."\" is set to send to the following people at ".$cron['cron_time']."</p><p><ul>";
		foreach($emails AS $email) { 
			$this_message .= "<li>".$email;
		}
		$this_message .="</ul></p><p>&nbsp;</p>";

			if(empty($mailout['email_from_email'])) {
				$from_email = $site_setup['contact_email'];
			} else {
				$from_email = $mailout['email_from_email'];
			}

			if(empty($mailout['email_from_name'])) {
				$from_name = $site_setup['website_title'];
			} else {
				$from_name = $mailout['email_from_name'];
			}

		addemailtocron($from_email,$from_name,"",$from_email,$from_name,$this_subject,$this_message,1);


	} ## End getting expiring galleries



	updateSQL("ms_crons", "cron_check_date='".date('Y-m-d')."' WHERE cron_id='".$cron['cron_id']."' ");

} ## End getting the crons 


### Getting cron jobs for expiring early bird specials
$crons = whileSQL("ms_crons", "*", "WHERE cron_what='earlybird' AND  cron_status='1' AND cron_check_date<'".date('Y-m-d')."' ORDER BY cron_id ASC ");
while($cron = mysqli_fetch_array($crons)) { 
	print "<li>cron id: ".$cron['cron_id'].": ".date('Y-m-d');

	## Creating expiration date
	$expire = date("Y-m-d", mktime(0, 0, 0, date('m'),date('d') + $cron['cron_days'], date('Y'))); 


	## Get the mailout to send
	$mailout = doSQL("ms_emails", "*", "WHERE email_id='".$cron['cron_email']."' ");
	if(empty($mailout['email_id'])) { die(); } 

	### Getting galleries that are expiring on the date
	$dates = whileSQL("ms_calendar LEFT JOIN ms_promo_codes ON ms_calendar.date_id=ms_promo_codes.code_date_id", "*", "WHERE code_end_date='".$expire."' AND date_public='1' ORDER BY date_id ASC ");
	while($date = mysqli_fetch_array($dates)) { 
		unset($emails);
		$emails = array();
		print "<li>".$date['date_id'];
	
		if($cron['cron_gal_owner'] == "1") { 
			### People with access to gallery #### 
			$ps = whileSQL("ms_people","*","WHERE p_id='".$date['date_owner']."'  AND p_email!='' ");
			while($p = mysqli_fetch_array($ps)) { 
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['p_email'])))."' ");
				if(empty($ck['id'])) { 
					if(!in_array($p['p_email'],$emails)) { 
						array_push($emails,$p['p_email']);
					}
					addtocron($p['p_email'],$p['p_name'],$p['p_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
				}
			}
		}



		if($cron['cron_gal_access'] == "1") { 
			### People with access to gallery #### 
			$ps = whileSQL("ms_my_pages LEFT JOIN ms_people ON ms_my_pages.mp_people_id=ms_people.p_id", "*, date_format(DATE_ADD(mp_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS mp_date", "WHERE mp_date_id='".$date['date_id']."' AND p_id>'0'  AND p_deactivated<='0' AND p_email!='' ORDER BY p_id ASC ");
			while($p = mysqli_fetch_array($ps)) { 
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['p_email'])))."' ");
				if(empty($ck['id'])) { 
					if(!in_array($p['p_email'],$emails)) { 
						array_push($emails,$p['p_email']);
					}
					addtocron($p['p_email'],$p['p_name'],$p['p_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
				}
			}
		}

		if($cron['cron_gal_preregister'] == "1") { 
			### People that have pre-registered #### 
			$ps = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."'  AND toview<='0'  ORDER BY reg_id DESC");
			while($p = mysqli_fetch_array($ps)) {
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['reg_email'])))."' ");
				if(empty($ck['id'])) { 
					addtocron($p['reg_email'],$p['reg_first_name'],$p['reg_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
					if(!in_array($p['p_email'],$emails)) { 
						array_push($emails,$p['reg_email']);
					}
				}
			}
		}

		if($cron['cron_gal_viewed'] == "1") { 
			### Registered people who have viewed the page ###
			$ps = whileSQL("ms_view_page LEFT JOIN ms_people ON ms_view_page.v_person=ms_people.p_id", "*, date_format(DATE_ADD(v_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS v_date", "WHERE v_page='".$date['date_id']."' AND p_id>'0'  AND p_deactivated<='0' AND p_email!='' ORDER BY p_last_name ASC ");
			while($p = mysqli_fetch_array($ps)) {
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['p_email'])))."' ");
				if(empty($ck['id'])) { 
					addtocron($p['p_email'],$p['p_name'],$p['p_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
					if(!in_array($p['p_email'],$emails)) { 
						array_push($emails,$p['p_email']);
					}
				}
			}
		}

		if($cron['cron_gal_purchased'] == "1") { 
			### People who have purchased ###
		//	$ps = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date", "WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  GROUP BY order_email ORDER BY order_last_name ASC ");

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
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['order_email'])))."' ");
				if(empty($ck['id'])) { 
					addtocron($p['order_email'],$p['order_first_name'],$p['order_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
					if(!in_array($p['order_email'],$emails)) { 
						array_push($emails,$p['p_email']);
					}
				}
			}
		}
		if($cron['cron_gal_collected_email'] == "1") { 
			### Emails collected to view gallery ###
			$ps = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."'  AND toview='1'  ORDER BY reg_id DESC");
			while($p = mysqli_fetch_array($ps)) {
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['reg_email'])))."' ");
				if(empty($ck['id'])) { 
					addtocron($p['reg_email'],$p['reg_first_name'],$p['reg_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
					if(!in_array($p['reg_email'],$emails)) { 
						array_push($emails,$p['p_email']);
					}
				}
			}
		}

		if($cron['cron_gal_no_order'] == "1") { 
			### People who have purchased ###
			$ps = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date", "WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  GROUP BY order_email ORDER BY order_last_name ASC ");


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
				$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower(addslashes(stripslashes($p['order_email'])))."' ");
				if(empty($ck['id'])) { 
					if(in_array($p['order_email'],$emails)) { 
						removeFromArr($emails,$p['order_email']);
						// print "<li>Removing: ".$p['order_email'];

						$ckcron = deleteSQL2("ms_cron_emails", "WHERE LOWER(to_email)='".strtolower($p['order_email'])."' AND from_date_id='".$date['date_id']."' AND from_email_id='".$mailout['email_id']."' ");
					} else { 
						addtocron($p['order_email'],$p['order_first_name'],$p['order_last_name'],$mailout['email_id'],$date['date_id'],$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],0,0,0);
						if(!in_array($p['order_email'],$emails)) { 
							array_push($emails,$p['p_email']);
						}
					}
				}
			}
		}

		## Send notice to admin. 
		$this_subject = "Automated Emails For ".$date['date_title']." Set To Send at ".$site_setup['website_title']." ".date('Y-m-d');
		$this_message = "<p>Notice of automated emails at ".$site_setup['cron_site_url']."</p><p>The email with the subject \"".$mailout['email_subject']."\" for gallery \"".$date['date_title']."\" is set to send to the following people at ".$cron['cron_time']."</p><p><ul>";
		foreach($emails AS $email) { 
			$this_message .= "<li>".$email;
		}
		$this_message .="</ul></p><p>&nbsp;</p>";

			if(empty($mailout['email_from_email'])) {
				$from_email = $site_setup['contact_email'];
			} else {
				$from_email = $mailout['email_from_email'];
			}

			if(empty($mailout['email_from_name'])) {
				$from_name = $site_setup['website_title'];
			} else {
				$from_name = $mailout['email_from_name'];
			}

		addemailtocron($from_email,$from_name,"",$from_email,$from_name,$this_subject,$this_message,1);


	} ## End getting expiring galleries



	updateSQL("ms_crons", "cron_check_date='".date('Y-m-d')."' WHERE cron_id='".$cron['cron_id']."' ");

} ## End getting the crons for early bird specials







## Booking calendar 
$crons = whileSQL("ms_crons", "*", "WHERE cron_what='booking' AND  cron_status='1' AND cron_check_date<'".date('Y-m-d')."' ORDER BY cron_id ASC ");
while($cron = mysqli_fetch_array($crons)) { 
	print "<li>cron id: ".$cron['cron_id'].": ".date('Y-m-d');

	## Creating expiration date
	$date = date("Y-m-d", mktime(0, 0, 0, date('m'),date('d') + $cron['cron_days'], date('Y'))); 
	$d = explode("-",$date);
	$day = date("M d, Y", mktime(0, 0, 0, $d[1], $d[2], $d[0])); 
	$dow = date("l", mktime(0, 0, 0, $d[1], $d[2], $d[0])); 

	$books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '".$site_setup['date_time_format']."')  AS book_time_show", "WHERE book_confirmed='2' AND ((book_date='".$date."' AND book_recurring_dom='' AND book_recurring_dow='') OR book_recurring_dom='".$d[2]."') OR book_recurring_dow='".$dow."'  GROUP BY book_id    ORDER BY book_time ASC ");

	while($book = mysqli_fetch_array($books)) { 
		if(($book['date_id'] > 0) && (!empty($book['book_reminder_email_subject']))  && (!empty($book['book_email'])) == true) { 
			addtocron($book['book_email'],$book['book_first_name'],$book['book_last_name'],0,0,$book['book_id'],date('Y-m-d')." ".$cron['cron_time'],1,0,0);
		}
	}

	updateSQL("ms_crons", "cron_check_date='".date('Y-m-d')."' WHERE cron_id='".$cron['cron_id']."' ");

}




## Scheduled payments 
$em = doSQL("ms_emails", "*", "WHERE email_id_name='paymentreminder' ");

$crons = whileSQL("ms_crons", "*", "WHERE cron_what='payments' AND  cron_status='1' AND cron_check_date<'".date('Y-m-d')."' ORDER BY cron_id ASC ");
while($cron = mysqli_fetch_array($crons)) { 
	print "<li>cron id: ".$cron['cron_id'].": ".date('Y-m-d');

	## Creating expiration date
	$date = date("Y-m-d", mktime(0, 0, 0, date('m'),date('d') + $cron['cron_days'], date('Y'))); 


	$payments = whileSQL("ms_payment_schedule LEFT JOIN ms_orders ON ms_payment_schedule.order_id=ms_orders.order_id", "*,date_format(due_date, '".$site_setup['date_format']."')  AS due_date_show,date_format(payment_date, '".$site_setup['date_format']."')  AS payment_date_show", "WHERE due_date='".$date."' AND payment<='0' ORDER BY id ASC  ");

	while($payment = mysqli_fetch_array($payments)) { 

		addtocron($payment['order_email'],$payment['order_first_name'],$payment['order_last_name'],$em['email_id'],0,0,date('Y-m-d')." ".$cron['cron_time'],1,$payment['order_id'],0);
	}

	updateSQL("ms_crons", "cron_check_date='".date('Y-m-d')."' WHERE cron_id='".$cron['cron_id']."' ");

}

## eGift Cards 
$em = doSQL("ms_emails", "*", "WHERE email_id_name='giftcertificate' ");

$crons = whileSQL("ms_crons", "*", "WHERE cron_what='giftcards' AND  cron_status='1' AND cron_check_date<'".date('Y-m-d')."' ORDER BY cron_id ASC ");
while($cron = mysqli_fetch_array($crons)) { 
	print "<li>cron id: ".$cron['cron_id'].": ".date('Y-m-d');

	## Creating expiration date
	$date = date("Y-m-d", mktime(0, 0, 0, date('m'),date('d') + $cron['cron_days'], date('Y'))); 


	$gcs = whileSQL("ms_gift_certificates", "*", "WHERE delivery_date='".date('Y-m-d')."' AND emailed_date='0000-00-00' ORDER BY id ASC  ");

	while($gc = mysqli_fetch_array($gcs)) { 

		addtocron($gc['to_email'],$gc['to_name'],'',$em['email_id'],0,0,date('Y-m-d')." ".$cron['cron_time'],1,0,$gc['id']);
	}

	updateSQL("ms_crons", "cron_check_date='".date('Y-m-d')."' WHERE cron_id='".$cron['cron_id']."' ");

}

?>