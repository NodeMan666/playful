<?php 
define("cat_table", "ms_blog_categories"); 
define("cat_connect_table", "ms_blog_cats_connect"); 
define("items_table", "ms_calendar");
define("items_id", "date_id"); 
define("items_cat_field", "date_cat"); 
define("this_do", "news"); 
define("folder", $setup['content_folder']); 
define("cat_field_url", "date_cat"); 
?>
<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }


if($date_type=="page") { 
	$setup['news_folder'] = $setup['pages_folder'];
} elseif($date_type == "gal") { 
	$setup['news_folder'] = $setup['photos_folder'];
} else {
	$date_type = "news";
}

if(!empty($_REQUEST['submitit'])) {
	if(is_array($_REQUEST['date_feature_cat'])) { 
		foreach($_REQUEST['date_feature_cat'] AS $c) { 
			$com++;
			if($com > 1) { 
				$date_feature_cats.=",";
			}
			print "<li>".$c;
			$date_feature_cats .=$c;
		}
	}
	// print "<h2>feature cat: ".$date_feature_cats."</h2>";
	// print "<pre>"; print_r($_REQUEST); exit();
	if(!empty($error)) {
		print "<div class=error>$error</div><div>&nbsp;</div>";
		regForm();
	} else {
		$_REQUEST['dateembed'] = trim($_REQUEST['dateembed']);
		if($_REQUEST['dateembed'] == "<br />") {
			$_REQUEST['dateembed'] = "";
		}
		if($_REQUEST['dateembed'] == '<br>') {
			$_REQUEST['dateembed'] = "";
		}
		if($_REQUEST['dateembed'] == '\r\n') {
			$_REQUEST['dateembed'] = "";
		}
		if($_REQUEST['dateembed'] == '\r') {
			$_REQUEST['dateembed'] = "";
		}

	//	print "<pre> here: <textarea>"; print $_REQUEST['dateembed']."</textarea>"; die();

		foreach($_REQUEST AS $id => $value) {
			if(!is_array($_REQUEST[$id])) { 
				$_REQUEST[$id] = addslashes(stripslashes($value));
			}
		}

		if((!empty($_REQUEST['page_under']))AND($_REQUEST['page_order'] <= "0")==true) {
			$lpage = doSQL("ms_calendar", "*", "WHERE page_under='".$_REQUEST['page_under']."' ORDER BY page_order DESC ");
			$_REQUEST['page_order'] = $lpage['page_order'] + 1;
		}


		if(!empty($_REQUEST['date_cat_new'])) { 
			$ck_cat = doSQL("ms_blog_categories", "*", "WHERE ".strtolower(cat_name)."='".strtolower($_REQUEST['date_cat_new'])."' ");
			if(empty($ck_cat['cat_id'])) { 
				if(!empty($_REQUEST['date_cat'])) { 
					$up_folder = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['date_cat']."' ");
					if(empty($up_folder['cat_under_ids'])) {
						$cat_under_ids = ("".$up_folder['cat_id']."");
					} else {
						$cat_under_ids = ("".$up_folder['cat_under_ids'].",".$up_folder['cat_id']."");
					}
					$cat_under = $up_folder['cat_id'];
				}				
				$cat_id = insertSQL("ms_blog_categories", "cat_name='".$_REQUEST['date_cat_new']."', cat_under_ids='$cat_under_ids', cat_under='$cat_under', cat_status='1' , cat_type='".$up_folder['cat_type']."' ");
				createCategory($cat_id);
			addNewCatDefaults($cat_id);

				$_REQUEST['date_cat'] = $cat_id;
			} else { 
				$_REQUEST['date_cat'] = $ck_cat['cat_id'];
			}
		}



$mystring = $_REQUEST['date_embed'];
$findme   = 'id=';
$pos = strpos($mystring, $findme);

if ($pos === false) {
	print "can't find in ".$_REQUEST['date_embed']."";
   $_REQUEST['date_embed'] = str_replace('<iframe', '<iframe id="video"', $_REQUEST['date_embed']);
}


		$date_date = $_REQUEST['date_year']."-".$_REQUEST['date_month']."-".$_REQUEST['date_day']."";
		$date_time = $_REQUEST['date_hour'].":".$_REQUEST['date_minute'].":".$_REQUEST['date_second']."";
		$date_expire = $_REQUEST['date_expire_year']."-".$_REQUEST['date_expire_month']."-".$_REQUEST['date_expire_day']."";

	// $_REQUEST['dateembed'] = str_replace('™','&trade;',$_REQUEST['dateembed']);

		$time_now= date('Y-m-d H:i:s');

		$feature_row_1 = $_REQUEST['feature_row_1_1']."|".$_REQUEST['feature_row_1_2']."|".$_REQUEST['feature_row_1_3']."|".$_REQUEST['feature_row_1_4'];
		$feature_row_2 = $_REQUEST['feature_row_2_1']."|".$_REQUEST['feature_row_2_2']."|".$_REQUEST['feature_row_2_3']."|".$_REQUEST['feature_row_2_4'];
		$feature_row_3 = $_REQUEST['feature_row_3_1']."|".$_REQUEST['feature_row_3_2']."|".$_REQUEST['feature_row_3_3']."|".$_REQUEST['feature_row_3_4'];
		if(($_REQUEST['prod_type'] == "download")==true) { 
			$_REQUEST['prod_inventory_control'] = 0;
		}

	if($_REQUEST['show_first_layout'] <=0) { 
		$_REQUEST['home_first_layout'] = 0;
	}


		if($_REQUEST['date_paid_access'] == "1") { 
			$_REQUEST['prod_price'] = $_REQUEST['paid_access_price'];
			$_REQUEST['date_credit'] = $_REQUEST['paid_access_credit'];
		}

		if($_REQUEST['passcode_photos'] == "1") { 
			$_REQUEST['private'] = 0;
		}
		if(!empty($_REQUEST['date_id'])) {


			if($_REQUEST['date_owner'] > 0) { 
				$ck = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$_REQUEST['date_id']."' AND mp_people_id='".$_REQUEST['date_owner']."' ");
				if(empty($ck['mp_id'])) { 
					insertSQL("ms_my_pages", "mp_date_id='".$_REQUEST['date_id']."', mp_people_id='".$_REQUEST['date_owner']."' , mp_date='".date('Y-m-d H:i:s')."' ");
				}
			}




		if($_REQUEST['book_start_time_apm'] == "pm") { 
			if($_REQUEST['book_start_time_hour'] < 12) { 
				$h = $_REQUEST['book_start_time_hour'] + 12;
			} else { 
				$h = 12;
			}
		} else { 
			if($_REQUEST['book_start_time_hour'] == "12") { 
				$h = 0;
			} else { 
				$h = $_REQUEST['book_start_time_hour'];
			}
		}
		$book_special_event_start = $h.":".$_REQUEST['book_start_time_minute'].":00";

		if($_REQUEST['book_end_time_apm'] == "pm") { 
			if($_REQUEST['book_end_time_hour'] < 12) { 
				$h = $_REQUEST['book_end_time_hour'] + 12;
			} else { 
				$h = 12;
			}
		} else { 
			if($_REQUEST['book_end_time_hour'] == "12") { 
				$h = 0;
			} else { 
				$h = $_REQUEST['book_end_time_hour'];
			}
		}
		$book_special_event_end = $h.":".$_REQUEST['book_end_time_minute'].":00";



		if($_REQUEST['book_time_apm'] == "pm") { 
			if($_REQUEST['book_time_hour'] < 12) { 
				$h = $_REQUEST['book_time_hour'] + 12;
			} else { 
				$h = 12;
			}
		} else { 
			if($_REQUEST['book_time_hour'] == "12") { 
				$h = 0;
			} else { 
				$h = $_REQUEST['book_time_hour'];
			}
		}
		$book_once_a_day_time = $h.":".$_REQUEST['book_time_minute'].":01";

			if($_REQUEST['selectdeposit'] == "p") { 
				$_REQUEST['book_deposit_flat'] = 0;
			}
			if($_REQUEST['selectdeposit'] == "f") { 
				$_REQUEST['deposit'] = 0;
			}

			$ckcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['date_cat']."' ");
			if($ckcat['cat_type'] !== "store") { 
				$_REQUEST['prod_inventory_control'] = 0;
				$_REQUEST['prod_qty'] = 0;
			}


			foreach($_REQUEST['disabled_payment_options'] AS $po => $id) { 
				if(!empty($po)) { 
					if($ponum > 0) { 
						$disabled_payment_options .=",";
					}
					$disabled_payment_options .= $po;
					$ponum++;
				}
				// print "<li>".$po." -> ".$id;
			}
			$id = updateSQL("ms_calendar", "date_title='".$_REQUEST['date_title']."', date_public='".$_REQUEST['date_public']."' , date_type='".$_REQUEST['date_type']."', date_where='".$_REQUEST['date_where']."', date_address='".$_REQUEST['date_address']."', date_hp_limit='".$_REQUEST['date_hp_limit']."' , date_date='".$_REQUEST['date_date']."', date_time='".$date_time."'  , date_snippet='".$_REQUEST['date_snippet']."' , date_cat='".$_REQUEST['date_cat']."' , date_cat2='".$_REQUEST['date_cat2']."' , date_cat3='".$_REQUEST['date_cat3']."' , date_cat4='".$_REQUEST['date_cat4']."', date_aff_link='".$_REQUEST['date_aff_link']."', date_aff_text='".$_REQUEST['date_aff_text']."' , private='".$_REQUEST['private']."', password='".$_REQUEST['date_p']."' , last_modified='".$time_now."'
			,date_aff_site='".$_REQUEST['date_aff_site']."'
			, external_link='".$_REQUEST['external_link']."'
			, page_form='".$_REQUEST['page_form']."' 
			, page_home='".$_REQUEST['page_home']."'
			, page_under='".$_REQUEST['page_under']."'
			, page_use_subpage='".$_REQUEST['page_use_subpage']."'
			, page_title_show='".$_REQUEST['page_title_show']."'
			, page_title_no_show='".$_REQUEST['page_title_no_show']."'
			, page_keywords='".addslashes(stripslashes($_REQUEST['page_keywords']))."'
			, date_meta_title='".addslashes(stripslashes($_REQUEST['date_meta_title']))."'
			, page_list_sub_pages='".$_REQUEST['page_list_sub_pages']."'
			, page_billboard='".$_REQUEST['page_billboard']."'
			, page_disable_drop='".$_REQUEST['page_disable_drop']."'
			, page_gallery='".$_REQUEST['page_gallery']."'
			, page_snippet='".$_REQUEST['page_snippet']."'
			, page_disable_fb='".$_REQUEST['page_disable_fb']."'
			, disable_comments='".$_REQUEST['disable_comments']."'
			,page_theme='".$_REQUEST['page_theme']."'
			,page_layout='".$_REQUEST['page_layout']."'
			,date_embed='".$_REQUEST['date_embed']."'
			,page_include_page='".$_REQUEST['page_include_page']."'
			,date_embed_contain='".$_REQUEST['date_embed_contain']."'
			,date_disable_side='".$_REQUEST['date_disable_side']."', 
			prod_inventory_control='".$_REQUEST['prod_inventory_control']."',
			date_feature_cat='".$date_feature_cats."',
			date_feature_limit='".$_REQUEST['date_feature_limit']."',
			date_feature_layout='".$_REQUEST['date_feature_layout']."',
			date_expire='".$_REQUEST['date_expire']."',
			feature_row_1 = '".$feature_row_1."',
			feature_row_2 = '".$feature_row_2."',
			feature_row_3 = '".$feature_row_3."',
			date_feature_title='".$_REQUEST['date_feature_title']."',
			date_feature_text='".$_REQUEST['date_feature_text']."',
			feature_show_titles='".$_REQUEST['feature_show_titles']."',
			audio_file='".$_REQUEST['audio_file']."',
			video_file='".$_REQUEST['video_file']."',
			prod_photos='".$_REQUEST['prod_photos']."',
			prod_order_message='".$_REQUEST['prod_order_message']."',
			prod_no_discount='".$_REQUEST['prod_no_discount']."',
			find_my_photos='".$_REQUEST['find_my_photos']."',
			qty_min='".$_REQUEST['qty_min']."',
			home_first_layout='".$_REQUEST['home_first_layout']."',
			date_credit='".$_REQUEST['date_credit']."',
			reg_person='".$_REQUEST['reg_person']."',
			reg_stop_goal='".$_REQUEST['reg_stop_goal']."',
			reg_goal='".$_REQUEST['reg_goal']."',
			date_paid_access='".$_REQUEST['date_paid_access']."',
			shipping_group='".$_REQUEST['shipping_group']."',
			prod_max_one='".$_REQUEST['prod_max_one']."',
			page_fix_width='".$_REQUEST['page_fix_width']."',
			date_video_download='".$_REQUEST['date_video_download']."',
			date_package='".$_REQUEST['date_package']."',
			date_package_pre_reg='".$_REQUEST['date_package_pre_reg']."',
			date_feature_auto_populate='".$_REQUEST['date_feature_auto_populate']."',
			video_location='".$_REQUEST['video_location']."',
			prod_subscription_price='".$_REQUEST['prod_subscription_price']."',
			add_to_cart_text='".$_REQUEST['add_to_cart_text']."',
			add_to_cart_redirect='".$_REQUEST['add_to_cart_redirect']."',
			green_screen_backgrounds='".$_REQUEST['green_screen_backgrounds']."',
			date_meta_descr='".addslashes(stripslashes($_REQUEST['date_meta_descr']))."',
			book_all_day='".$_REQUEST['book_all_day']."',
			book_length_hours='".$_REQUEST['book_length_hours']."',
			book_length_minutes='".$_REQUEST['book_length_minutes']."',
			deposit='".$_REQUEST['deposit']."',
			book_deposit_flat='".$_REQUEST['book_deposit_flat']."',
			book_require_deposit='".$_REQUEST['book_require_deposit']."',
			book_confirm_no_deposit='".$_REQUEST['book_confirm_no_deposit']."',
			book_auto_confirm='".$_REQUEST['book_auto_confirm']."',
			book_lead_time='".$_REQUEST['book_lead_time']."',
			book_max_days='".$_REQUEST['book_max_days']."',
			book_once_a_day='".$_REQUEST['book_once_a_day']."',
			book_per_time='".$_REQUEST['book_per_time']."',
			book_confirm_email='".$_REQUEST['book_confirm_email']."',
			book_reminder_email='".$_REQUEST['book_reminder_email']."',
			book_special_event='".$_REQUEST['book_special_event']."',
			book_special_event_day_only='".$_REQUEST['book_special_event_day_only']."',
			book_special_event_start='".$book_special_event_start."',
			book_special_event_end='".$book_special_event_end."',
			book_special_event_blocks='".$_REQUEST['book_special_event_blocks']."',
			book_confirm_email_subject='".$_REQUEST['book_confirm_email_subject']."',
			book_reminder_email_subject='".$_REQUEST['book_reminder_email_subject']."',
			book_special_event_date='".$_REQUEST['book_special_event_date']."',
			book_once_a_day_time='".$book_once_a_day_time."',

			genders='".$_REQUEST['genders']."',
			disabled_payment_options='".$disabled_payment_options."',
			date_paid_access_unlock='".$_REQUEST['date_paid_access_unlock']."',

			_deposit_='".$_REQUEST['_deposit_']."',
			_booking_comments_or_notes_='".$_REQUEST['_booking_comments_or_notes_']."',
			_booking_send_request_='".$_REQUEST['_booking_send_request_']."',
			_booking_your_information_='".$_REQUEST['_booking_your_information_']."',
			_booking_success_title_='".$_REQUEST['_booking_success_title_']."',
			_booking_success_message_='".$_REQUEST['_booking_success_message_']."',
			_booking_additional_options_='".$_REQUEST['_booking_additional_options_']."',
			_booking_additional_options_message_='".$_REQUEST['_booking_additional_options_message_']."',
			_booking_deposit_required_message_='".$_REQUEST['_booking_deposit_required_message_']."',
			_booking_select_time_='".$_REQUEST['_booking_select_time_']."',
			_learn_more_='".$_REQUEST['_learn_more_']."',
			_book_now_='".$_REQUEST['_book_now_']."',

			_booking_auto_confirm_text_='".$_REQUEST['_booking_auto_confirm_text_']."',
			_booking_confirm_button_='".$_REQUEST['_booking_confirm_button_']."',

			_booking_your_information_text_above_send_='".$_REQUEST['_booking_your_information_text_above_send_']."',
			_booking_your_information_text_='".$_REQUEST['_booking_your_information_text_']."',
			custom_book_days='".$_REQUEST['custom_book_days']."',
			Monday='".$_REQUEST['Monday']."',
			Tuesday='".$_REQUEST['Tuesday']."',
			Wednesday='".$_REQUEST['Wednesday']."',
			Thursday='".$_REQUEST['Thursday']."',
			Friday='".$_REQUEST['Friday']."',
			Saturday='".$_REQUEST['Saturday']."',
			Sunday='".$_REQUEST['Sunday']."',
			book_start_time='".$_REQUEST['book_start_time']."',
			book_end_time='".$_REQUEST['book_end_time']."',
			book_blocks='".$_REQUEST['book_blocks']."',
			prod_price='".$_REQUEST['prod_price']."', prod_qty='".$_REQUEST['prod_qty']."', prod_cost='".$_REQUEST['prod_cost']."', prod_create_reg_key='".$_REQUEST['prod_create_reg_key']."', prod_sep_order_email='".$_REQUEST['prod_sep_order_email']."', prod_dl_name='".$_REQUEST['prod_dl_name']."', prod_type='".$_REQUEST['prod_type']."',  prod_download_descr='".$_REQUEST['prod_download_descr']."', prod_version='".$_REQUEST['prod_version']."', prod_cat='".$_REQUEST['prod_cat']."', prod_prod_id='".$_REQUEST['prod_prod_id']."', prod_sale_price='".$_REQUEST['prod_sale_price']."', prod_shipping='".$_REQUEST['prod_shipping']."', prod_add_ship='".$_REQUEST['prod_add_ship']."', prod_taxable='".$_REQUEST['prod_taxable']."', prod_no_link='".$_REQUEST['prod_no_link']."', prod_max_qty='".$_REQUEST['prod_max_qty']."', prod_sale_end='".$_REQUEST['prod_sale_end']."', prod_sale_start='".$_REQUEST['prod_sale_start']."' , prod_sale_message='".$_REQUEST['prod_sale_message']."' ,
			date_photo_price_list='".$_REQUEST['date_photo_price_list']."',
			reg_event_date='".$_REQUEST['reg_event_date']."',
			passcode_photos='".$_REQUEST['passcode_photos']."',
			date_gallery_exclusive='".$_REQUEST['date_gallery_exclusive']."',
			date_gallery_exclusive_no_cover='".$_REQUEST['date_gallery_exclusive_no_cover']."', 

			change_price_list='".$_REQUEST['change_price_list']."',
			change_price_list_date='".$_REQUEST['change_price_list_date']."', 
			change_shipping_group='".$_REQUEST['change_shipping_group']."',
			change_shipping_group_date='".$_REQUEST['change_shipping_group_date']."',
			date_owner='".$_REQUEST['date_owner']."'



			  WHERE date_id='".$_REQUEST['date_id']."'  ");   		
			$id = $_REQUEST['date_id'];
			$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");

			updateSQL("ms_calendar", " date_text='".$_REQUEST['dateembed']."'  WHERE date_id='".$_REQUEST['date_id']."' ");

			$ck_folder = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");

			if(!empty($ck_folder['date_link'])) {
				if($_REQUEST['old_cat']!==$_REQUEST['date_cat']) {
					renameNewCat($id,$_REQUEST['old_cat']);
				}
			}
			if(empty($ck_folder['date_link'])) {

				createNewPage($id);
			}

		 if($setup['customfields'] == true) { 
			$fields = whileSQL("ms_custom_fields", "*", "ORDER BY field_name ASC ");
			while($field = mysqli_fetch_array($fields)) {
				$field_id_name = "field-".$field['id'];
				if(!empty($_REQUEST[$field_id_name])) { 
					$ck = doSQL("ms_custom_fields_data", "*", "WHERE data_date='".$_REQUEST['date_id']."' AND data_field='".$field['id']."' ");
					if(empty($ck['data_id'])) { 
						insertSQL("ms_custom_fields_data", "data_field='".$field['id']."', data_date='".$_REQUEST['date_id']."', data_data='".addslashes(stripslashes($_REQUEST[$field_id_name]))."' ");
					} else { 
						updateSQL("ms_custom_fields_data", "data_data='".addslashes(stripslashes($_REQUEST[$field_id_name]))."' WHERE data_field='".$field['id']."' AND data_date='".$_REQUEST['date_id']."' ");
					}
				}
			}
		
		 }
		
		
		} else {

			$def = doSQL("ms_defaults", "*", "WHERE def_cat_id='".$_REQUEST['date_cat']."' ");
			$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['date_cat']."' ");

			if(empty($def['def_id'])) { 
				$def = doSQL("ms_defaults", "*", "ORDER BY def_id DESC");
			}
			$password = substr(md5(date('ymdHis')),0,8);
			if($_REQUEST['date_cat'] <= 0) { 
				$cat['cat_default_status'] = 1;
			}

			$ldate = doSQL("ms_calendar", "*", "WHERE highlights_text!='' ORDER BY date_id DESC ");
			if(!empty($ldate['date_id'])) { 
				$_REQUEST['highlights_text'] = $ldate['highlights_text'];
				$_REQUEST['star_text'] = $ldate['star_text'];
				$_REQUEST['open_highlights'] = $ldate['open_highlights'];
				$_REQUEST['add_highlight_link'] = $ldate['add_highlight_link'];
				$_REQUEST['show_star'] = $ldate['show_star'];
			}
			if($cat['cat_type'] == "booking") { 
				$lastbook = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id","*","WHERE cat_type='booking'  ORDER BY date_id DESC"); 
				if(empty($lastbook['book_confirm_email'])) { 
					$lastbook['book_confirm_email'] = '<p>Hello [FIRST_NAME],
					</p>
					<p>This email is to inform you of your booking confirmation with [WEBSITE_NAME].
					</p>
					<p><strong>[BOOKING_SERVICE]</strong><br>
					</p>
					<p>Date: [BOOKING_DATE]<br>Time: [BOOKING_TIME]
					</p>
					<p>Thank you,<br> [WEBSITE_NAME]<br>
					</p>';
					$lastbook['book_confirm_email_subject'] = "[FIRST_NAME], Your Booking Confirmation With [WEBSITE_NAME]";
				}

				if(empty($lastbook['book_reminder_email'])) { 
					$lastbook['book_reminder_email'] = '<p>Hello [FIRST_NAME],
					</p>
					<p>This is a reminder of your appointment  with [WEBSITE_NAME].
					</p>
					<p><strong>[BOOKING_SERVICE]</strong><br>
					</p>
					<p>Date: [BOOKING_DATE]<br>Time: [BOOKING_TIME]
					</p>
					<p>If for some reason you are unable to make it, please let us know as soon as possible by replying to this email.<br>
					</p>Thank you,<br> [WEBSITE_NAME]';
					$lastbook['book_reminder_email_subject'] = "[FIRST_NAME], Don't Forget Your Appointment on [BOOKING_DATE] With [WEBSITE_NAME]";
				}
				if(empty($lastbook['_book_now_'])) { 
					$lastbook['_deposit_'] ='Deposit';
					$lastbook['_booking_comments_or_notes_'] ='Comments or notes';
					$lastbook['_booking_send_request_'] ='Send Request';
					$lastbook['_booking_your_information_'] ='Your Information';
					$lastbook['_booking_success_title_'] ='Thank You!';
					$lastbook['_booking_success_message_'] ='Thank you for your interest with us! We will send you a confirmation email soon.';
					$lastbook['_booking_additional_options_'] ='Additional Options';
					$lastbook['_booking_additional_options_message_'] ='Select below from the additional options available.';
					$lastbook['_booking_deposit_required_message_'] ='A deposit is required to confirm this booking in the amount of: ';
					$lastbook['_booking_select_time_'] ='Select a Time';
					$lastbook['_learn_more_'] ='Learn More';
					$lastbook['_book_now_'] ='Book Now';
					$lastbook['_booking_auto_confirm_text_'] ='Your appointment is set and you have been sent an email with details.';
					$lastbook['_booking_confirm_button_'] = 'Confirm Booking';
				}
			}

			if(($cat['change_price_list'] > 0) && ($cat['change_price_list_days'] > 0) == true) { 
				$change_price_list = $cat['change_price_list'];
				$change_price_list_date  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d") + $cat['change_price_list_days'], date("Y")));
			}

			if(($cat['change_shipping_group'] > 0) && ($cat['change_shipping_group_days'] > 0) == true) { 
				$change_shipping_group = $cat['change_shipping_group'];
				$change_shipping_group_date  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d") + $cat['change_shipping_group_days'], date("Y")));
			}



			$id = insertSQL("ms_calendar", "date_title='".$_REQUEST['date_title']."', date_text='".addslashes(stripslashes($cat['cat_reg_default_text']))."', date_public='".$cat['cat_default_status']."', date_type='".$_REQUEST['date_type']."' , date_where='".$_REQUEST['date_where']."', date_address='".$_REQUEST['date_address']."', date_hp_limit='".$_REQUEST['date_hp_limit']."' , date_date='".$_REQUEST['date_date']."', date_time='".$date_time."'   , date_snippet='".$_REQUEST['date_snippet']."'  , date_cat='".$_REQUEST['date_cat']."'  , date_cat2='".$_REQUEST['date_cat2']."' , date_cat3='".$_REQUEST['date_cat3']."' , date_cat4='".$_REQUEST['date_cat4']."' ,  date_aff_link='".$_REQUEST['date_aff_link']."', date_aff_text='".$_REQUEST['date_aff_text']."',  private='".$cat['cat_default_private']."', password='".$password."', 
			last_modified='".$time_now."'

			,change_price_list='".$change_price_list."'
			,change_price_list_date='".$change_price_list_date."'
			,change_shipping_group='".$change_shipping_group."'
			,change_shipping_group_date='".$change_shipping_group_date."'
			, external_link='".$_REQUEST['external_link']."'
			, page_form='".$_REQUEST['page_form']."' 
			, page_home='".$_REQUEST['page_home']."'
			, page_under='".$_REQUEST['page_under']."'
			, page_use_subpage='".$_REQUEST['page_use_subpage']."'
			, page_title_show='".$_REQUEST['page_title_show']."'
			, page_title_no_show='".$_REQUEST['page_title_no_show']."'
			, page_keywords='".addslashes(stripslashes($_REQUEST['page_keywords']))."'
			, page_list_sub_pages='1'
			, page_billboard='".$_REQUEST['page_billboard']."'
			, page_disable_drop='".$_REQUEST['page_disable_drop']."'
			, page_gallery='".$_REQUEST['page_gallery']."'
			, page_snippet='".$_REQUEST['page_snippet']."'
			, page_disable_fb='".$_REQUEST['page_disable_fb']."'
			, disable_comments='".$_REQUEST['disable_comments']."'
			,date_embed='".$_REQUEST['date_embed']."'
			,date_embed_contain='".$_REQUEST['date_embed_contain']."'
			,date_aff_site='".$_REQUEST['date_aff_site']."'
			,page_layout='".$_REQUEST['page_layout']."'
			,video_location='".$_REQUEST['video_location']."'
			,prod_subscription_price='".$_REQUEST['prod_subscription_price']."'

			,page_theme='".$_REQUEST['page_theme']."',
			blog_location='".$def['blog_location']."',
			blog_type='".$def['def_type']."',
			blog_contain='".$def['blog_contain']."',
			blog_seconds='".$def['blog_seconds']."',
			blog_enlarge='".$def['blog_enlarge']."',
			blog_kill_side_menu='".$def['blog_kill_side_menu']."',
			blog_progress_bar='".$def['blog_progress_bar']."',
			blog_next_prev='".$def['blog_next_prev']."',
			blog_play_pause='".$def['blog_play_pause']."',
			blog_slideshow='".$def['blog_slideshow']."',
			blog_slideshow_auto_start='".$def['blog_slideshow_auto_start']."',
			disable_controls='".$def['disable_controls']."',
			caption_location='".$def['caption_location']."',
			contain_width='".$def['contain_width']."',
			contain_height='".$def['contain_height']."',
			noupsize='".$def['noupsize']."',
			blog_frame='".$def['blog_frame']."',
			disable_thumbnails='".$def['disable_thumbnails']."',
			disable_help='".$def['disable_help']."',
			disable_animation_bar='".$def['disable_animation_bar']."',
			disable_photo_count='".$def['disable_photo_count']."',
			transition_time='".$def['transition_time']."',
			blog_photo_file='".$def['blog_photo_file']."',
			bg_use='".$def['bg_use']."',
			disable_play_pause='".$def['disable_play_pause']."',
			disable_next_previous='".$def['disable_next_previous']."',
			disable_photo_slider='".$def['disable_photo_slider']."',
			thumb_style='".$def['thumb_style']."',
			thumb_type='".$def['thumb_type']."',
			slideshow_fixed_height='".$def['slideshow_fixed_height']."',
			thumb_scroller_open='".$def['thumb_scroller_open']."',
			photo_social_share='".$def['social_share']."',
			jthumb_height='".$def['jthumb_height']."',
			jthumb_margin='".$def['jthumb_margin']."',
			thumbactions='".$def['thumbactions']."',
			allow_favs='".$def['allow_favs']."',
			disable_filename='".$def['disable_filename']."',
			photo_search='".$def['photo_search']."',
			enable_compare='".$def['enable_compare']."',
			reg_person='".$_REQUEST['reg_person']."',

			thumb_open_first='".$def['thumb_open_first']."',
			thumb_file='".$def['thumb_file']."',
			add_style='".$def['add_style']."',
			disable_icons='".$def['disable_icons']."', 
			stacked_width='".$def['stacked_width']."', 
			stacked_margin='".$def['stacked_margin']."' ,

			book_confirm_email='".addslashes(stripslashes($lastbook['book_confirm_email']))."',
			book_reminder_email='".addslashes(stripslashes($lastbook['book_reminder_email']))."',
			book_confirm_email_subject='".addslashes(stripslashes($lastbook['book_confirm_email_subject']))."',
			book_reminder_email_subject='".addslashes(stripslashes($lastbook['book_reminder_email_subject']))."',
			_deposit_='".addslashes(stripslashes($lastbook['_deposit_']))."',
			_booking_comments_or_notes_='".addslashes(stripslashes($lastbook['_booking_comments_or_notes_']))."',
			_booking_send_request_='".addslashes(stripslashes($lastbook['_booking_send_request_']))."',
			_booking_your_information_='".addslashes(stripslashes($lastbook['_booking_your_information_']))."',
			_booking_success_title_='".addslashes(stripslashes($lastbook['_booking_success_title_']))."',
			_booking_success_message_='".addslashes(stripslashes($lastbook['_booking_success_message_']))."',
			_booking_additional_options_='".addslashes(stripslashes($lastbook['_booking_additional_options_']))."',
			_booking_additional_options_message_='".addslashes(stripslashes($lastbook['_booking_additional_options_message_']))."',
			_booking_deposit_required_message_='".addslashes(stripslashes($lastbook['_booking_deposit_required_message_']))."',
			_booking_select_time_='".addslashes(stripslashes($lastbook['_booking_select_time_']))."',
			_learn_more_='".addslashes(stripslashes($lastbook['_learn_more_']))."',
			_book_now_='".addslashes(stripslashes($lastbook['_book_now_']))."',
			_booking_your_information_text_above_send_='".addslashes(stripslashes($lastbook['_booking_your_information_text_above_send_']))."',
			_booking_your_information_text_='".addslashes(stripslashes($lastbook['_booking_your_information_text_']))."',
			_booking_auto_confirm_text_='".addslashes(stripslashes($lastbook['_booking_auto_confirm_text_']))."',
			_booking_confirm_button_='".addslashes(stripslashes($lastbook['_booking_confirm_button_']))."',


			date_expire='".$_REQUEST['date_expire']."',
			feature_row_1 = '".$feature_row_1."',
			feature_row_2 = '".$feature_row_2."',
			feature_row_3 = '".$feature_row_3."',
			date_feature_title='".$_REQUEST['date_feature_title']."',
			date_feature_text='".$_REQUEST['date_feature_text']."',

			page_include_page='".$_REQUEST['page_include_page']."',
			date_disable_side='".$_REQUEST['date_disable_side']."',
			max_photo_display_width='".$def['max_photo_display_width']."',
			thumb_width='".($photo_setup['blog_th_width'] + 30)."',
			prod_inventory_control='".$_REQUEST['prod_inventory_control']."',
			date_feature_cat='".$date_feature_cats."',
			date_feature_limit='".$_REQUEST['date_feature_limit']."',
			date_feature_layout='".$_REQUEST['date_feature_layout']."',
			audio_file='".$_REQUEST['audio_file']."',
			video_file='".$_REQUEST['video_file']."',
			prod_photos='".$_REQUEST['prod_photos']."',
			prod_order_message='".$_REQUEST['prod_order_message']."',
			date_credit='".$_REQUEST['date_credit']."',
			shipping_group='".$cat['shipping_group']."',

			prod_price='".$_REQUEST['prod_price']."', prod_qty='".$_REQUEST['prod_qty']."', prod_cost='".$_REQUEST['prod_cost']."', prod_create_reg_key='".$_REQUEST['prod_create_reg_key']."', prod_sep_order_email='".$_REQUEST['prod_sep_order_email']."', prod_dl_name='".$_REQUEST['prod_dl_name']."', prod_type='".$_REQUEST['prod_type']."',  prod_download_descr='".$_REQUEST['prod_download_descr']."', prod_version='".$_REQUEST['prod_version']."', prod_cat='".$_REQUEST['prod_cat']."', prod_prod_id='".$_REQUEST['prod_prod_id']."', prod_sale_price='".$_REQUEST['prod_sale_price']."', prod_shipping='".$_REQUEST['prod_shipping']."', prod_add_ship='".$_REQUEST['prod_add_ship']."', prod_taxable='".$_REQUEST['prod_taxable']."', prod_no_link='".$_REQUEST['prod_no_link']."', prod_max_qty='".$_REQUEST['prod_max_qty']."', prod_sale_end='".$_REQUEST['prod_sale_end']."', prod_sale_start='".$_REQUEST['prod_sale_start']."' , prod_sale_message='".$_REQUEST['prod_sale_message']."' ,
			date_photo_price_list='".$_REQUEST['date_photo_price_list']."',
			passcode_photos='".$cat['def_passcode_photos']."',
			date_gallery_exclusive='".$cat['cat_gallery_exclusive']."',
			date_gallery_exclusive_no_cover='".$cat['cat_gallery_exclusive_no_cover']."',

			open_highlights='".$_REQUEST['open_highlights']."', 
			show_star='".$_REQUEST['show_star']."', 
			add_highlight_link='".$_REQUEST['add_highlight_link']."', 
			highlights_text='".addslashes(stripslashes($_REQUEST['highlights_text']))."', 
			star_text='".addslashes(stripslashes($_REQUEST['star_text']))."' 
    ");   		
			$_REQUEST['date_id'] = $id;
		$photo_setup = doSQL("ms_photo_setup", "*", "  ");
		$date = doSQL("ms_calendar", "*", "WHERE date_id='$id' ");
		createNewPage($id);
	}

		if($_REQUEST['r_year'] == "0000") {
			$_REQUEST['r_year'] = date('Y');
		}



		deleteSQL2("ms_blog_cats_connect", "WHERE con_prod='$id' ");
		if(is_array($_REQUEST['prod_add_cats'])) { 
			foreach($_REQUEST['prod_add_cats'] AS $cat_id) { 
				if($cat_id > 0) { 
				//	print "<li>$cat_id";
					insertSQL("ms_blog_cats_connect", "con_prod='$id', con_cat='$cat_id' ");
				}
			}
		}

	// print "<pre>"; print_r($_REQUEST); exit();

			$page_keys = strtolower($_REQUEST['page_keywords']);
			$ekeys = explode(",",$page_keys);

			deleteSQL2("ms_tag_connect", "WHERE tag_date_id='".$id."' ");
			if(is_array($_REQUEST['e_tags'])) { 
				foreach($_REQUEST['e_tags'] AS $tag_id => $val) {
					$tag = doSQL("ms_tags", "*", "WHERE tag_id='".$tag_id."' ");
					if(!in_array($tag['tag_tag'],$ekeys)) { 
						$page_keys .= ",".$tag['tag_tag']."";
					}

					$tag_keys .= $tag['tag_tag'].", ";
					insertSQL("ms_tag_connect", "tag_tag_id='".$tag_id."', tag_date_id='".$id."' ");
				}
			}

			if(!empty($_REQUEST['new_tags'])) { 
				$new_tags = explode(",",$_REQUEST['new_tags']);
				foreach($new_tags AS $tag) { 
					$tag = trim($tag);
					$tag = strtolower($tag);
					if(!in_array($tag,$ekeys)) { 
						$page_keys .= ",$tag";
					}

					$cktag = doSQL("ms_tags", "*", "WHERE tag_tag='".$tag."' ");
					if(empty($cktag['tag_id'])) { 
						$tag_keys .= $tag.", ";

						$tag_id = insertSQL("ms_tags", "tag_tag='".addslashes(stripslashes($tag))."' ");
						createTagFolder($tag_id);
					} else { 
						$tag_id = $cktag['tag_id'];
					}
					$ckcon = doSQL("ms_tag_connect", "*", "WHERE tag_tag_id='".$tag_id."' AND tag_date_id='".$id."' ");
					if(empty($ckcon['id'])) { 
						insertSQL("ms_tag_connect", "tag_tag_id='".$tag_id."', tag_date_id='".$id."' ");
					}
				}
			}
			updateSQL("ms_calendar", "page_keywords='".addslashes(stripslashes($page_keys))."' WHERE date_id='".$_REQUEST['date_id']."'  ");

			if($_REQUEST['page_under'] <=0) { 
				updateSQL("ms_history", "last_category='".$_REQUEST['date_cat']."', last_date='".$date['date_id']."' ");
			}
				updateSiteMap();

		$_SESSION['sm'] = "Page saved";
		session_write_close();
		header("location: index.php?do=".$_REQUEST['do']."&action=addDate&date_id=".$_REQUEST['date_id']."&page_under=".$_REQUEST['page_under']."");
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

	global $tr, $_REQUEST, $setup, $site_setup,$is_gallery,$date_type,$sytist_store,$dbcon;
	if((empty($_REQUEST['date_id']))&&(empty($_REQUEST['date_cat']))&&(empty($_REQUEST['submit']))&&(empty($_REQUEST['page_under']))==true) { 
		$history = doSQL("ms_history LEFT JOIN ms_blog_categories ON ms_history.last_category=ms_blog_categories.cat_id", "*", "WHERE cat_type!='registry' ");
		$_REQUEST['date_cat'] = $history['last_category'];
		$lastdate = doSQL("ms_calendar", "*", "WHERE date_id='".$history['last_date']."' AND date_cat='".$history['last_category']."' ");
		$_REQUEST['prod_taxable'] = $lastdate['prod_taxable'];
		$_REQUEST['prod_inventory_control'] = $lastdate['prod_inventory_control'];
		$_REQUEST['prod_type'] = $lastdate['prod_type'];
		$_REQUEST['video_location'] = $lastdate['video_location'];

	}
	if((empty($_REQUEST['date_id']))&&(!empty($_REQUEST['date_cat']))==true) { 
		$lastdate = doSQL("ms_calendar", "*", "WHERE  date_cat='".$_REQUEST['date_cat']."' ORDER BY date_id DESC");
		$_REQUEST['prod_taxable'] = $lastdate['prod_taxable'];
		$_REQUEST['prod_inventory_control'] = $lastdate['prod_inventory_control'];
		$_REQUEST['prod_type'] = $lastdate['prod_type'];
		$_REQUEST['video_location'] = $lastdate['video_location'];
	}


	if((!empty($_REQUEST['date_id']))AND(empty($_REQUEST['submit']))==true) {
		$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
		if(empty($date['date_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($date AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
		$rdate = explode("-", $date['date_date']);
		$_REQUEST['r_year'] = $rdate[0];
		$_REQUEST['r_month'] = $rdate[1];
		$_REQUEST['r_day'] = $rdate[2];
	} else {
		if(empty($_REQUEST['submitit'])) {
			$_REQUEST['date_hp_limit'] = "500";
			$_REQUEST['date_date'] = date("Y-m-d", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
			$_REQUEST['date_time'] = date("H:i:s", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
			$_REQUEST['date_hp_limit'] = "500";
		}
	}

	if((empty($_REQUEST['date_id']))&&(!empty($_REQUEST['date_cat']))&&(empty($_REQUEST['submit']))==true) { 
		$upcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['date_cat']."' ");
		if(!empty($upcat['cat_under_ids'])) { 
			$scats = explode(",",$upcat['cat_under_ids']);
			if($scat['cat_price_list'] > 0) { 
				$cat_price_list = $scat['cat_price_list'];
			}

			foreach($scats AS $scat) { 
				$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
				if($ctt > 0) { 
					$this_cat_trail .= " > ";
				}
				$this_cat_trail .= "".$tcat['cat_name']." ";
				$ctt++;
			}
		}
		if($ctt > 0) { 
			$this_cat_trail .= " > ";
		}
		if($upcat['cat_price_list'] > 0) { 
			$cat_price_list = $upcat['cat_price_list'];
		}

		$this_cat_trail .= "".$upcat['cat_name'];
		$cat_type = $upcat['cat_type'];
	}
	?>

<div id="pageTitle"><a href="index.php?do=<?php print $_REQUEST['do'];?>">Site Content</a>  
<?php 
if(!empty($date['page_under'])) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	if($uppage['date_cat'] > 0) { 
		$date_cat = $uppage['date_cat'];
	}
}
if(!empty($date['date_cat'])) { 
	$date_cat = $date['date_cat'];
}
if(!empty($date_cat)) { 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date_cat."' ");
	$cat_type = $cat['cat_type'];

	if(!empty($cat['cat_under_ids'])) { 
		$scats = explode(",",$cat['cat_under_ids']);
		foreach($scats AS $scat) { 
			$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
			print " ".ai_sep." <a href=\"index.php?do=news&date_cat=".$tcat['cat_id']."\">".$tcat['cat_name']."</a> ";
			if($ctt > 0) { 
				$this_cat_trail .= " > ";
			}
			$this_cat_trail .= "".$tcat['cat_name']." ";
			$ctt++;

		}
	}
	print " ".ai_sep." ";
	if(!empty($cat['cat_password'])) { print ai_lock." "; } 
	print "<a href=\"index.php?do=news&date_cat=".$cat['cat_id']."\">".$cat['cat_name']."</a>";
	if($ctt > 0) { 
		$this_cat_trail .= " > ";
	}

	$this_cat_trail .= "".$cat['cat_name'];

}
?>
<?php print ai_sep;?>  <?php if(!empty($date['page_under'])) {  $uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");	?>
		<a href="index.php?do=news<?php if(empty($uppage['date_cat'])) { print "&date_cat=none"; } else { print "&date_cat=".$uppage['date_cat']; } ?>#dateid-<?php print $uppage['date_id'];?>"><?php print $uppage['date_title'];?></a> <?php print ai_sep;?>  
		<?php } ?>

 	<?php  if(!empty($_REQUEST['date_id'])) { ?>
		 <span><?php if($date['page_home'] == "1") { print "Home Page"; }  else { print $date['date_title']; } ?> </span>
	<?php  }  else { ?>
		 New 
		 <?php if(!empty($_REQUEST['page_under'])) { 
		$udate = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['page_under']."' ");
		print "Under: ".$udate['date_title'];
	}
	?>
	<?php  } ?>
</div>

<?php 
if($date['date_id'] > 0) { 	
	$passes = whileSQL("ms_calendar", "*", "WHERE password='".$date['password']."' AND date_id!='".$date['date_id']."' AND private>'0' AND password!=''");
	if(mysqli_num_rows($passes) > 0) { ?>
	<div class="error"><b>Warning</b>: The password for this page <b>"<?php print $date['password'];?>"</b> is being used on <?php print mysqli_num_rows($passes);?> other <?php if(mysqli_num_rows($passes) > 1) { print "pages"; } else { print "page"; } ?>. Duplicate passwords could take people to the wrong page when using the access private page form.</div>
	<?php } 
}
?>

<?php if($date['page_404'] == "1") { ?>
	<div class="yellowmessage">This is the page that is shown if someone goes to a page that does not exist.</div>
	<div>&nbsp;</div>
	<?php } ?>

<?php 		if(!empty($_REQUEST['date_id'])) {  ?>

<?php
		$photo_setup = doSQL("ms_photo_setup", "*", "  ");
		$width = $photo_setup['blog_width'];
		$height = $photo_setup['blog_height'];
		$thumb_size = $site_setup['blog_thumb_size'];
		$mini_size = $site_setup['blog_mini_size'];
//		$crop_thumbs = $site_setup['blog_crop_thumb'];
		$is_blog = 1;

?>


<?php } ?>
<?php 	
	
if($date['page_under'] > 0) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$uppage['date_cat']."' "); 
} else { 
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' "); 
}
if($_REQUEST['date_id'] <=0) { ?>
<style>
.hidenew { display: none; } 
</style>
<?php } ?>


<?php include "news.tabs.php"; ?>
<form name="register" action="index.php" method="post" style="padding:0; margin: 0;"   onSubmit="return checkForm();">

<div id="roundedFormContain">
<div style="width: 73%; float: left;">



	<div >
<?php if($cat_type == "registry") { 
if($_REQUEST['date_id'] <=0) { 	?>
	<div class="underlinelabel">Select Account For Registry</div>
	<div class="underline">
	<div>
	<select name="reg_person" id="reg_person" class="required inputtitle">
	<option value="">Account....</option>
	<?php $ps = whileSQL("ms_people", "*", "ORDER BY p_last_name ASC ");
	while($p = mysqli_fetch_array($ps)) { ?>
	<option value="<?php print $p['p_id'];?>" <?php if($date['reg_person'] == $p['p_id']) { print "selected"; } ?>><?php print $p['p_last_name'].", ".$p['p_name'];?> (<?php print $p['p_email'];?>)</option>
	<?php } ?>
	</select>
	</div>
	<div>If the person does not already have an account, you will need to <a href="?do=people" onclick="editpeople(); return false;">create one</a> first.</div>
	</div>
<?php } else { ?>
<div class="underlinelabel">Registry for account: 
<?php $p = doSQL("ms_people", "*", "WHERE p_id='".$date['reg_person']."' "); ?>
<a href="index.php?do=people&p_id=<?php print $p['p_id'];?>"><?php print $p['p_name']." ".$p['p_last_name'];?></a>

<?php 
	$regs = doSQL("ms_credits", "*,SUM(credit_amount) AS total", "WHERE credit_reg='".$date['date_id']."' ");
	if($regs['total'] > 0) { print " <a href=\"index.php?do=people&p_id=".$date['reg_person']."&view=credits\">".showPrice($regs['total'])."</a> "; } 
?>
<input type="hidden" name="reg_person" id="reg_person" value="<?php print $date['reg_person'];?>">
</div>
<div class="underline"><a href="" onclick="sendregistry('<?php print $date['date_id'];?>'); return false;"><?php print ai_email;?> Send an email with information about this registry to <?php print $p['p_name']." ".$p['p_last_name'];?></a></div>
<?php } ?>
<?php } ?>

<?php if(empty($_REQUEST['date_id'])) { ?>
	<?php if($cat_type == "registry") { ?>
	<div class="underlinelabel">Enter the name, names or event name below for this registry</div>
<?php } else { ?>
	<script>
	$( document ).ready(function() {
	  $("#date_title").focus();
	  selectProductConfig();
	});
	</script>
	<div class="underlinelabel"><h3>Enter a  title to get started</h3></div>
<?php } ?>
<?php } ?>

<script>
function custommeta() { 
	$("#custommetadiv").slideToggle(200);
}

function selectdepositoption() { 
	if($("#selectdeposit").attr("checked")) { 
		$("#percentagedeposit").show();
		$("#flatdeposit").hide();
		// $("#book_deposit_flat").val('0.00');
	}
	if($("#selectdepositf").attr("checked")) { 
		$("#percentagedeposit").hide();
		$("#flatdeposit").show();
		// $("#deposit").val('0');
	}
}

	</script>
		<div class="underline">
			<div class="pad"><input type="text" class="inputtitle field100 <?php if($date['page_home'] !== "1") { ?>required<?php } ?>" size=40 name="date_title" id="date_title" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['date_title']));?>"></div>

			<div class="pad hidenew"><a href="" onclick="custommeta(); return false;">Enter custom meta title</a></div>
			<div class="pad hidden" id="custommetadiv">
			<div><input type="text" name="date_meta_title" id="date_meta_title" class="field100"  value="<?php  print htmlspecialchars(stripslashes($_REQUEST['date_meta_title']));?>"></div>
			<div>The meta title is what is shown at the top of the browser and in search engine results as the name of the page.</div>
			</div>
	</div>

		<div class="storestuff hidenew" <?php if(($cat_type !== "store") && ($cat_type !== "booking") == true) { ?>style="display: none;"<?php } ?>>
			<div class="underline">
				<div class="label" style="float: left; margin-right: 16px;">Price <input type="text" id="prod_price" name="prod_price" size="8" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['prod_price']));?>"  class="inputtip inputtitle center" title="Enter in the price to charge for this product without the currency sign">
				
				<?php if($cat_type == "booking") { 
				
				?>
				<span id="depositoptions" class="<?php if($_REQUEST['book_require_deposit'] !== "1") { ?>hide<?php } ?>">&nbsp;&nbsp; Deposit: 
					<input type="radio" name="selectdeposit" id="selectdeposit" value="p" <?php if($_REQUEST['deposit'] > 0) { ?>checked<?php } ?> onchange="selectdepositoption()"> <label for="selectdeposit">Percentage</label> 

					<span id="percentagedeposit"  class="<?php if($_REQUEST['deposit'] <= 0) { ?>hide<?php } ?>"><input type="text" id="deposit" name="deposit" size="4" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['deposit']));?>"  class="inputtip inputtitle center" title="Enter in the percentage of deposit required without the % sign.">% &nbsp</span> &nbsp;

					<input type="radio" name="selectdeposit" id="selectdepositf" value="f" <?php if($_REQUEST['book_deposit_flat'] > 0) { ?>checked<?php } ?>  onchange="selectdepositoption()"> <label for="selectdepositf">Flat Rate</label>

					
					<span id="flatdeposit"  class="<?php if($_REQUEST['book_deposit_flat'] <= 0) { ?>hide<?php } ?>"><?php print $site_setup['currency_sign'];?><input type="text" id="book_deposit_flat" name="book_deposit_flat" size="8" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['book_deposit_flat']));?>"  class="inputtip inputtitle center" title="Enter in the amount for deposit without the currency sign."></span>
					&nbsp; &nbsp; </span>
				<?php } ?>
				</div>
				<div class="label subscription <?php if($date['prod_type'] !== "subscript") { ?>hidden<?php } ?>" style="float: left; margin-right: 16px;">Monthly <input type="text" id="prod_subscription_price" name="prod_subscription_price" size="6" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['prod_subscription_price']));?>"  class="inputtip inputtitle center" title="Enter in the price to charge for this product without the currency sign"></div>

			<div style="float: left; margin-right: 16px; line-height: 32px;"><input type="checkbox" name="prod_taxable" id="prod_taxable" value="1" <?php  if($_REQUEST['prod_taxable'] == "1") { print "checked"; } ?> class="inputtip" title="Check this box if this product is taxable."> <label for="prod_taxable">Taxable</label></div>


				<div style="float: left; margin-right: 16px; line-height: 32px;" <?php if($cat_type == "booking") { ?>class="hide"<?php } ?>><input type="checkbox" name="prod_no_discount" id="prod_no_discount" value="1" <?php if($_REQUEST['prod_no_discount'] == "1") { print "checked"; } ?>> <label for="prod_no_discount">Do not allow discounts with coupon</label> <div class="moreinfo" info-data="nodiscount"><div class="info"></div></div></div>
	
				<div style="float: right; margin-right: 16px; line-height: 32px;" class="<?php if($cat_type == "booking") { ?>hidden<?php } ?>"><a href="" onclick="prodquantitydiscount('<?php print $date['date_id'];?>'); return false;" class="tip" title="Save any changes before editing quantity discounts">Quantity discounts</a></div>




				<div class="clear"></div>
			</div>
		</div>
			<?php if($cat_type == "booking") { ?>
			<script>
				function depositoptions() { 
					if($("#book_require_deposit").attr("checked")) { 
						$("#depositoptions").slideDown(100);
						$("#book_confirm_no_deposit").attr("checked",false)
					} else { 
						$("#depositoptions").slideUp(100);
					}

				}
				function nodepositconfirm() { 
					if($("#book_confirm_no_deposit").attr("checked")) { 
						$("#book_require_deposit").attr("checked",false);
						$("#book_auto_confirm").attr("checked",false);
						$("#depositoptions").slideUp(100);
						$("#book_deposit_flat").val(0);
						$("#deposit").val(0);
					}

				}
			

			</script>
			<div class="underline hidenew">
			<div style="float: left; margin-right: 16px; line-height: 32px;" ><input type="checkbox" onchange="depositoptions();" name="book_require_deposit" id="book_require_deposit" value="1" <?php if($_REQUEST['book_require_deposit'] == "1") { print "checked"; } ?>> <label for="book_require_deposit">Require deposit when booking</label> <div class="moreinfo" info-data="requiredeposit"><div class="info"></div></div></div>
			
			<div style="float: left; margin-right: 16px; line-height: 32px;" ><input type="checkbox" name="book_auto_confirm" id="book_auto_confirm" value="1" <?php if($_REQUEST['book_auto_confirm'] == "1") { print "checked"; } ?>> <label for="book_auto_confirm">Auto confirm with deposit.</label> <div class="moreinfo" info-data="autoconfirmdeposit"><div class="info"></div></div></div>

			<div style="float: right; margin-right: 16px; line-height: 32px;" ><input type="checkbox" onchange="nodepositconfirm();" name="book_confirm_no_deposit" id="book_confirm_no_deposit" value="1" <?php if($_REQUEST['book_confirm_no_deposit'] == "1") { print "checked"; } ?>> <label for="book_confirm_no_deposit">Auto Confirm With No Deposit</label> <div class="moreinfo" info-data="autoconfirmnodeposit"><div class="info"></div></div></div>
			<div class="clear"></div>
			</div>



			<?php  } ?>


<?php
	if($_REQUEST['date_text'] == "<br />") {
		$_REQUEST['date_text'] = "";
	}
?>
<?php
	if($_REQUEST['date_id'] <=0) { ?>
<div>
<center>
<div id="submitButtonLoading"><img src="graphics/loading1.gif"></div>
	<input type="hidden" name="date_id" value="<?php  print $_REQUEST['date_id'];?>">
	<?php  if(!empty($_REQUEST['date_id'])) { ?>
		<input type="submit" name="submit" class="submit" id="submitButton" value="SAVE CHANGES">
	<?php  } else { ?>
		<input type="submit" name="submit" class="submit" id="submitButton" value="Continue">
	<?php  } ?>
</center>
</div>
<?php } ?>
		<div class="pc hidenew">

			<div class="pad"><textarea name="dateembed" id="dateembed" rows="6" cols="50" wrap="virtual" class=textfield><?php  print htmlspecialchars(stripslashes($_REQUEST['date_text']));?></textarea>
			
			<?php 
			$_REQUEST['uploadlink'] = $date['date_link'];	
			addEditor("dateembed","1", "500", "0"); ?>
				</div>
			</div>
	</div>
	<div>&nbsp;</div>

	<?php if($_REQUEST['page_home'] == "1") { 
	include "news.home.page.features.php";
	
	} ?>

<?php if(($date['page_home']  !== "1")&&($date['page_404'] !=="1")&&(empty($_REQUEST['page_under']))==true) { ?>
<?php if($_REQUEST['date_cat'] > 0) { 
		$rcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['date_cat']."' ");
		if($rcat['cat_type'] == "registry") { ?>
		<input type="hidden" name="date_cat" id="date_cat" value="<?php print $_REQUEST['date_cat'];?>">
		<input type="hidden" name="old_cat" value="<?php print $_REQUEST['date_cat'];?>">

		<?php 
			$no_cats = true;
		}
	}
		?>

	
		<?php if($setup['customfields'] == true) { ?>
	<div>
		<div id="" class="hidenew">
		<div class="underlinelabel subeditclick">Custom Fields</div>
			<div class="">
				<?php 
				$fields = whileSQL("ms_custom_fields", "*", "ORDER BY field_name ASC ");
				while($field = mysqli_fetch_array($fields)) { 
					$val = doSQL("ms_custom_fields_data", "*", "WHERE data_date='".$date['date_id']."' AND data_field='".$field['id']."' "); 
					?>
				<div class="underline">
					<div class="left p40"><?php print $field['field_name'];?></div>
					<div class="left p60"><input type="text" name="field-<?php print $field['id'];?>" id="field-<?php print $field['id'];?>" size="20" class="field100" value="<?php print htmlspecialchars($val['data_data']);?>"></div>
					<div class="clear"></div>
				</div>
				<?php } ?>

			</div>
		</div>
		</div>
	<div>&nbsp;</div>	
	
	<?php } ?>
	

<?php if($cat_type == "booking") { ?>
	<div>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Confirmation Email</div>
			<div class="subedit ">
				<div class="p70 left">
				<div>Subject</div>
				<div><input type="text" name="book_confirm_email_subject" id="book_confirm_email_subject" size="20" class="field100" value="<?php print htmlspecialchars($_REQUEST['book_confirm_email_subject']);?>"></div>
			<div>&nbsp;</div>
			<div class="pad"><textarea name="book_confirm_email" id="book_confirm_email" rows="6" cols="50" wrap="virtual" class=textfield><?php  print htmlspecialchars(stripslashes($_REQUEST['book_confirm_email']));?></textarea>
			
			<?php 
			$_REQUEST['uploadlink'] = $date['date_link'];	
			addEditor("book_confirm_email","1", "500", "0"); ?>
				</div>
				</div>
				<div class="p30 left">
					<div style="padding: 0px 16px;">
					<p>Replace codes</p>
						<p>[BOOKING_DATE] = Date</p>
						<p>[BOOKING_TIME] = Time</p>
						<p>[BOOKING_SERVICE] = Service name</p>
						<p>[BOOKING_OPTIONS] = Any options selected</p>
						<p>[FIRST_NAME] = Customer's first name</p>
						<p>[LAST_NAME] = Customer's last name</p>
						<p>[URL] = The URL to your store</p>
						<p>[WEBSITE_NAME] = The name of your website </p>

					</div>
				</div>
				<div class="clear"></div>

			</div>
		</div>
	</div>
	<div>&nbsp;</div>

	<div>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Reminder Email</div>
			<div class="subedit ">
			<div class="p70 left">
				<div>Subject</div>
				<div><input type="text" name="book_reminder_email_subject" id="book_reminder_email_subject" size="20" class="field100" value="<?php print htmlspecialchars($_REQUEST['book_reminder_email_subject']);?>"></div>
			<div>&nbsp;</div>
			<div class="pad"><textarea name="book_reminder_email" id="book_reminder_email" rows="6" cols="50" wrap="virtual" class=textfield><?php  print htmlspecialchars(stripslashes($_REQUEST['book_reminder_email']));?></textarea>
			
			<?php 
			$_REQUEST['uploadlink'] = $date['date_link'];	
			addEditor("book_reminder_email","1", "500", "0"); ?>
				</div>
				</div>
				<div class="p30 left">
					<div style="padding: 0px 16px;">
					<p>Replace codes</p>
						<p>[BOOKING_DATE] = Date</p>
						<p>[BOOKING_TIME] = Time</p>
						<p>[BOOKING_SERVICE] = Service name</p>
						<p>[BOOKING_OPTIONS] = Any options selected</p>
						<p>[FIRST_NAME] = Customer's first name</p>
						<p>[LAST_NAME] = Customer's last name</p>
						<p>[URL] = The URL to your store</p>
						<p>[WEBSITE_NAME] = The name of your website </p>

					</div>
				</div>
				<div class="clear"></div>
			
			</div>
		</div>
	</div>
	<div>&nbsp;</div>


	<div>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Additional Text</div>
			<div class="subedit ">
			<div class="underlinespacer">Below is text that may be shown during the booking process.</div>
			<div class="underline">
				<div class="label">Additional options title</div>
				<div><input type="text" name="_booking_additional_options_" class="field100" value="<?php print htmlspecialchars($date['_booking_additional_options_']);?>"></div>
			</div>

			<div class="underline">
				<div class="label">Additional options text</div>
				<div><input type="text" name="_booking_additional_options_message_" class="field100" value="<?php print htmlspecialchars($date['_booking_additional_options_message_']);?>"></div>
			</div>


			<div class="underline">
				<div class="label">Comments or notes</div>
				<div><input type="text" name="_booking_comments_or_notes_" class="field100" value="<?php print htmlspecialchars($date['_booking_comments_or_notes_']);?>"></div>
			</div>

			<div class="underline">
				<div class="label">Send Request</div>
				<div><input type="text" name="_booking_send_request_" class="field100" value="<?php print htmlspecialchars($date['_booking_send_request_']);?>"></div>
			</div>

			<div class="underline">
				<div class="label">Your Information</div>
				<div><input type="text" name="_booking_your_information_" class="field100" value="<?php print htmlspecialchars($date['_booking_your_information_']);?>"></div>
			</div>

			<div class="underline">
				<div class="label">Your Information text</div>
				<div><input type="text" name="_booking_your_information_text_" class="field100" value="<?php print htmlspecialchars($date['_booking_your_information_text_']);?>"></div>
			</div>

			<div class="underline">
				<div class="label">Text above send request button</div>
				<div><input type="text" name="_booking_your_information_text_above_send_" class="field100" value="<?php print htmlspecialchars($date['_booking_your_information_text_above_send_']);?>"></div>
			</div>

			<div class="underline">
				<div class="label">Request sent success title</div>
				<div><input type="text" name="_booking_success_title_" class="field100" value="<?php print htmlspecialchars($date['_booking_success_title_']);?>"></div>
			</div>

			<div class="underline">
				<div class="label">Request sent success message</div>
				<div><input type="text" name="_booking_success_message_" class="field100" value="<?php print htmlspecialchars($date['_booking_success_message_']);?>"></div>
			</div>

			<div class="underline">
				<div class="label">Deposit or payment required message</div>
				<div><input type="text" name="_booking_deposit_required_message_" class="field100" value="<?php print htmlspecialchars($date['_booking_deposit_required_message_']);?>"></div>
			</div>
			<div class="underline">
				<div class="label">Deposit</div>
				<div><input type="text" name="_deposit_" class="field100" value="<?php print htmlspecialchars($date['_deposit_']);?>"></div>
			</div>


			<div class="underline">
				<div class="label">Book Now</div>
				<div><input type="text" name="_book_now_" class="field100" value="<?php print htmlspecialchars($date['_book_now_']);?>"></div>
			</div>

			<div class="underline">
				<div class="label">Auto Confirm With No Deposit Book Now Button</div>
				<div><input type="text" name="_booking_confirm_button_" class="field100" value="<?php print htmlspecialchars($date['_booking_confirm_button_']);?>"></div>
			</div>

			<div class="underline">
				<div class="label">Auto Confirm With No Deposit Success Text</div>
				<div><input type="text" name="_booking_auto_confirm_text_" class="field100" value="<?php print htmlspecialchars($date['_booking_auto_confirm_text_']);?>"></div>
			</div>




			</div>
		</div>
	</div>





	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>

<?php } ?>

<?php if(($date['page_home']  !== "1")&&($date['page_404'] !=="1")&&(empty($_REQUEST['page_under']))==true) { ?>

	<?php 
			if($no_cats !== true) { ?>
	<div>
		<div id="">
			<div class="underlinelabel subeditclick"><span class="left">Category</span> <span class="right textirght"><span id="newcatname"> <?php print $this_cat_trail;?></span></span><div class="clear"></div></div>
			<div class="subedit" <?php if($_REQUEST['date_id'] <=0) { ?>style="display: block;"<?php } ?>>
				<div class="underline">
				<div class="fieldLabel">Main Section or Category</div>
				<div><?php print multiLevelSelect($_REQUEST['date_cat']);?></div>
				<input type="hidden" name="old_cat" value="<?php print $_REQUEST['date_cat'];?>">
				</div>

				<div class="underline hidenew" id="addCats" <?php if($_REQUEST[''.items_cat_field.'']<=0) { print "style=\"display: none;\""; } ?>>
				<div class="fieldLabel">Additional Categories</div>
				<div><?php print productAdditionalCategories($req);?></div>
				</div>
				<div class="underline hidenew">
					<div class="pad">
					<span id="newsubcat" <?php if($_REQUEST[''.items_cat_field.'']<=0) { print "style=\"display: none;\""; } ?>>Create new sub category</span> 

					<input <?php if($_REQUEST[''.items_cat_field.'']<=0) { print "style=\"display: none;\""; } ?> type="text" class="textfield" size=40 name="date_cat_new" id="date_cat_new" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['date_cat_new']));?>">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div>&nbsp;</div>
	<?php } ?>
<?php } ?>
<?php } ?>

	<div>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Metadata</div>
			<div class="subedit underline">
				<div class="sub hidenew bold">Meta Description</div>
				<div class="sub hidenew"><textarea name="date_meta_descr" class="textfield" rows="5" cols="40"style="width: 98%;"><?php  print htmlspecialchars(stripslashes($_REQUEST['date_meta_descr']));?></textarea></div>
				<div class="sub hidenew">The meta description is automatically generated from the page text. If you enter in a meta description here, it will use this instead. The meta description is a  snippet of text, a tag in HTML, that summarizes a page's content. Search engines show the meta description in search results mostly when the searched for phrase is contained in the description. This is not actually shown on the page but in the source code. Less than 160 characters.</div>
				<div class="sub hidenew bold">Meta Keywords</div>
				<div class="sub hidenew"><input type="text" class="textfield" size=40 name="page_keywords" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['page_keywords']));?>" style="width: 98%;"></div>
				<div class="sub hidenew">Enter in key words or phrases relevant to this page separated by commas.</div>


			</div>
		</div>
	</div>
	<div>&nbsp;</div>	

	<div>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Tags (<?php print countIt("ms_tag_connect",  "WHERE tag_date_id='".$date['date_id']."' ");?>)</div>
			<div class="subedit ">
				<div class="underline hidenew">Select from existing tags</div>
				<div class="underline hidenew" style="max-height: 150px; overflow-y: scroll;">
				<?php 
				$tags = whileSQL("ms_tags", "*", "ORDER BY tag_tag ASC ");
				if(mysqli_num_rows($tags) <=0) { print "No tags have been created"; } 
				while($tag = mysqli_fetch_array($tags)) { 
					$cktag = doSQL("ms_tag_connect", "*", "WHERE tag_tag_id='".$tag['tag_id']."' AND tag_date_id='".$date['date_id']."' "); 	?>
				<span id="span-tag-<?php print $tag['tag_id'];?>" class="<?php if(!empty($cktag['id'])) { print "tagselected"; } else { print "tagunselected"; }  ?>"><nobr><input type="checkbox" id="e-tag-<?php print $tag['tag_id'];?>" name="e_tags[<?php print $tag['tag_id'];?>]" value="<?php print $tag['tag_id'];?>" <?php if(!empty($cktag['id'])) { print "checked"; } ?> onclick="checkTag('<?php print $tag['tag_id'];?>');"> <?php print $tag['tag_tag'];?> </nobr></span>, 
				<?php } ?>

				</div>
				<div class="underline hidenew">
				<div class="underline">Create new tags</div>
				<div class="descr"><input type="text" name="new_tags" value="" size="20"  style="width: 98%;"></div>
				<div class="descr">Enter in new tags separated with a comma</div>
				</div>
				</div>
			</div>
		</div>
	<div>&nbsp;</div>

<?php 
	if(countIt("ms_billboards", "")>0) { ?>
	<div>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Billboard <?php if($_REQUEST['page_billboard'] > 0) { $bill = doSQL("ms_billboards", "*", "WHERE bill_id='".$_REQUEST['page_billboard']."' "); print ": ".$bill['bill_name']; } ?></div>
			<div class="subedit">
				<div class="underline hidenew">
				<select name="page_billboard">
				<option value="0">No</option>
				<?php 
				$bills = whileSQL("ms_billboards", "*", "ORDER BY bill_name ASC ");
				while($bill = mysqli_fetch_array($bills)) { 
					print "<option value=\"".$bill['bill_id']."\" "; if($_REQUEST['page_billboard'] == $bill['bill_id']) { print "selected"; } print ">".$bill['bill_name']."</optoin>";
				}
				?>
				</select>
				<div><a href="index.php?do=look&action=billboardsList">Manage billboards</a></div>
				</div>
			</div>
		</div>
	</div>
	<div>&nbsp;</div>	

<?php } ?>


	<div>
		<div id="" class="hidenew">
		<div class="underlinelabel subeditclick">Video</div>
			<div class="subedit">
				<?php 
				$vids = whileSQL("ms_videos", "*", "ORDER BY vid_name ASC ");

				if(mysqli_num_rows($vids) > 0) { ?>
				<div class="underline"><b>Select from uploaded videos</b></div>
				<div class="underline">
				<select name="video_file" id="video_file">
				<option value="">No video</option>
				<?php while($vid = mysqli_fetch_array($vids)) { ?>
				<option value="<?php print $vid['vid_id'];?>" <?php if($date['video_file'] == $vid['vid_id']) { print "selected"; } ?>><?php print $vid['vid_name'];?></option>
				<?php } ?>
				</select>
				</div>
				<?php } ?>
				
				<div class="underline hidenew"><b>Embed Video</b><br>This allows you to easily embed a video from YouTube, Vimeo, etc. Just copy the embed code from the video and paste it below.</div>
				<div class="underline hidenew"><textarea name="date_embed" class="textfield" rows="5" cols="40"style="width: 98%;"><?php  print htmlspecialchars(stripslashes($_REQUEST['date_embed']));?></textarea></div>
				<div class="underline hidenew">Location: <input type="radio" name="video_location" id="video_location0" value="0" <?php if($_REQUEST['video_location'] == "0") { print "checked"; } ?>> <label for="video_location0">Above Text</label>  &nbsp; &nbsp;<input type="radio" name="video_location"  id="video_location1" value="1" <?php if($_REQUEST['video_location'] == "1") { print "checked"; } ?>> <label for="video_location1">Below Text</label>  </div>

				
				<!-- <div class="row hidenew"><input type="checkbox" name="date_embed_contain" <?php if($_REQUEST['date_embed_contain'] == "1") { print "checked"; } ?> value="1"> Check this box to contain video to viewing area of the screen. Otherwise it will go full screen which might cut off the player controls.</div> -->
			</div>
		</div>
		</div>
	<div>&nbsp;</div>



<script>
function showmorepageoptions() { 
	$("#pagemoreoptions").slideToggle(300);
}
</script>



<div class="pc center"><a href="" onclick="showmorepageoptions(); return false;">More Options</a></div>

<div id="pagemoreoptions" class="hidden">



	<div>
		<div  class="hidenew">
			<div class="underlinelabel subeditclick">Play Music</div>
			<div class="subedit">
			<div class="underline">
			<?php 
			$audio_files = array();
			if(file_exists("".$setup['path']."/".$setup['misc_folder']."")) {
				$theFiles = array();
				$theFolders = array();
				$misc_path = $setup['path']."/".$setup['misc_folder']."/audiofiles";
				if(is_dir($misc_path)) { 
					$dir = opendir($misc_path); 
					while ($file = readdir($dir)) { 
						if (($file != ".") && ($file != "..")) {
							$file_count++;
							if(!is_dir($misc_path."/".$file)) {
								$ext = pathinfo("".$setup['path']."/".$setup['misc_folder']."/audiofiles/".$file, PATHINFO_EXTENSION);
								if($ext == "mp3") { 
									$stripped = str_replace("mp3", "",$file);
									if(file_exists($setup['path']."/".$setup['misc_folder']."/audiofiles/".$stripped."ogg")) {
										$has_ogg =  1;
									}
									array_push($audio_files, $file);
									// print "<li>".$ext." ogg: ".$has_ogg." - File: <a href=\"/".$setup['misc_folder']."/audiofiles/$file\">/".$setup['misc_folder']."/audiofiles/$file</a> - $stripped";
									unset($has_ogg);
								}			

							}
						}
					}
				closedir($dir); 
				}
			}
?>
		<?php if(count($audio_files) <=0) { ?>
		<div class="center pc">No audio files have been uploaded. Go to <a href="index.php?do=look&view=miscFiles&folder=audiofiles">Site Design > Misc. Images & Files > audiofiles folder</a> to upload audio files.</div>
		<?php } else { ?>
			<select name="audio_file" id="audio_file">
			<option value="">No Music</option>
			<?php foreach($audio_files AS $file) { ?>
			<option value="<?php print $file;?>" <?php if($date['audio_file'] == $file) { print "selected"; } ?>><?php print $file; ?></option>
			<?php } ?>
			</select>
			<?php } ?>
			</div>

			</div>
		</div>
	</div>
	<div>&nbsp;</div>	



<?php if($date['page_home'] !== "1") { ?>
	<div>
		<div  class="hidenew">
			<div class="underlinelabel subeditclick">Page Title Shown ON page</div>
			<div class="subedit">
				<div class="underline hidenew"><input type="text" class="textfield" size=40 name="page_title_show" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['page_title_show']));?>" style="width: 98%;"><br>
				Leave this blank to use the page title above
				</div>
		<div class="underline hidenew">
			<div class="pad"><input type="checkbox" value="1" name="page_title_no_show" <?php if($_REQUEST['page_title_no_show'] == "1") { print " checked"; } ?>> Check this box to show no page title on the page</div>
		</div>
	</div>
		</div>
		</div>
	<div>&nbsp;</div>
	<?php } ?>


<?php if(!empty($_REQUEST['page_under'])) { ?>
	<div id="" class="hidenew">
	<div class="underlinelabel">Page listing description</div>
	<div class="underline hidenew">If you have chosen to "List sub pages on this page", you can enter in the description you want to use here. Leave blank and it will use part of the page text above.</div>
	<div class="underline hidenew"><textarea name="page_snippet" cols="40" rows="5" style="width: 98%;"><?php  print htmlspecialchars(stripslashes($_REQUEST['page_snippet']));?></textarea></div>
	</div>
	<div>&nbsp;</div>	

<?php } ?>




<?php if(($date['page_home']  !== "1")&&($date['page_404'] !=="1")==true) { ?>
	<div>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Preview Snippet</div>
			<div class="subedit underline">
			<div class="sub">This text is show if when your pages are being listed (like blog posts). If you leave it bank, it will show a portion of the content as the preview.</div>
			<div class="sub"><textarea name="date_snippet" id="date_snippet" rows="3" cols="50" wrap="virtual" class=textfield style="width: 98%;"><?php  print htmlspecialchars(stripslashes($_REQUEST['date_snippet']));?></textarea></div>
		</div>
	</div>
	</div>
	<div>&nbsp;</div>	
<?php } ?>



	<div>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Page Form<?php if($_REQUEST['page_form'] > 0) { $form = doSQL("ms_forms", "*", "WHERE form_id='".$_REQUEST['page_form']."' "); print ": ".$form['form_name']; } ?></div>
			<div class="subedit underline">
				<div class="sub hidenew">
					<select name="page_form">
					<option value="0">No form</option>
					<?php $forms = whileSQL("ms_forms", "*", "ORDER BY form_name ASC ");
					while($form = mysqli_fetch_array($forms)) {
						print "<option value=\"".$form['form_id']."\""; if($_REQUEST['page_form'] == $form['form_id']) { print " selected"; } print ">".$form['form_name']."</option>";
					}
					?>
					</select>
					</div>
					<div class="sub hidenew">
					If you selected a form to add to this page above, you have to put the bracket code <b>[FORM]</b> in the text area where you want the form to appear. 
					</div>
				</div>
		</div>
	</div>
	<div>&nbsp;</div>	



	<div>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Share & Comments Options</div>
			<div class="subedit">
				<div class="underline hidenew"><input type="checkbox" name="page_disable_fb" value="1" <?php if($_REQUEST['page_disable_fb'] == "1") { print " checked"; } ?>> Disable social share options  for this page (if in use)</div>
				<div class="underline hidenew"><input type="checkbox" name="disable_comments" value="1" <?php if($_REQUEST['disable_comments'] == "1") { print " checked"; } ?>> Disable comments for this page (if in use)</div>
			</div>
		</div>
	</div>
	<div>&nbsp;</div>	
	
	<div>
		<div id="" class="hidenew">
		<div class="underlinelabel subeditclick">Page Max Width

		</span><div class="clear">
		</div></div>
			<div class="subedit ">
			<div class="underlinespacer">Here you can set the max width of this page if you want it smaller than the width of the content area of your theme.</div>
			<div class="underline hidenew">

		<select name="page_fix_width" id="page_fix_width">
		<option value="0" <?php if($_REQUEST['page_fix_width'] == "0") { print "selected"; } ?>>No Fixed Width</option>
		<option value="1024" <?php if($_REQUEST['page_fix_width'] == "1024") { print "selected"; } ?>>1024px</option>
		<option value="1200" <?php if($_REQUEST['page_fix_width'] == "1200") { print "selected"; } ?>>1200px</option>
		<option value="1400" <?php if($_REQUEST['page_fix_width'] == "1400") { print "selected"; } ?>>1400px</option>
		</select> 
		</div>
	</div>
	</div>
	</div>
	<div>&nbsp;</div>	




	<div>
		<div id="" class="hidenew">
		<div class="underlinelabel subeditclick">Page Theme<?php if($_REQUEST['page_theme'] > 0) { $theme = doSQL("ms_css", "*", "WHERE css_id='".$_REQUEST['page_theme']."' "); print ": ".$theme['css_name']; } ?></div>
			<div class="subedit">
				<div class="underline hidenew">You can select another theme to use that will apply to this page.</div>
				<div class="underline hidenew">
				<select name="page_theme">
				<option value="0">Use default</option>
				<?php 
				$themes = whileSQL("ms_css", "*", "ORDER BY css_name ASC ");
				while($theme = mysqli_fetch_array($themes)) { 
					print "<option value=\"".$theme['css_id']."\" "; if($_REQUEST['page_theme'] == $theme['css_id']) { print "selected"; } print ">".$theme['css_name']."</optoin>";
				}
				?>
				</select>
				</div>
			</div>
		</div>
	</div>
<div>&nbsp;</div>
<div>
	<div id="" class="hidenew">
		<div class="underlinelabel <?php if($top_section !== true) { print "subeditclick"; } ?>">Page Display  Layout <?php if($_REQUEST['page_layout'] <=0) { print ": Default"; } else { $layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$_REQUEST['page_layout']."' "); print ": ".$layout['layout_name'].""; } ?></div>
		<div class="<?php if($top_section !== true) { print "subedit"; } ?>">
			<div class="underline hidenew">
			<select name="page_layout" id="page_layout" >
			<option value="">Use default section layout</option>
			<option value="" disabled id="pdor">--- or select a different layout below ---</option>
			<?php 
			$layouts = whileSQL("ms_category_layouts", "*", "WHERE layout_type='page' ORDER BY layout_name ASC ");
			while($layout = mysqli_fetch_array($layouts)) { 
				print "<option value=\"".$layout['layout_id']."\" "; if($_REQUEST['page_layout'] == $layout['layout_id']) { print "selected"; } print ">".$layout['layout_name']."</optoin>";
			}
			?>
			</select>
		</div>
	<div class="underline">This determines how the page is laid out. Selecting a layout here will override the section layout settings. <a href="index.php?do=look&view=layouts">See page displaylayout</a>.</div>
	</div>
</div>
</div>
<div>&nbsp;</div>



<?php if((!empty($date['date_link']))&&($date['page_home']!=="1")==true) { ?>


	<div>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Page Link</div>
			<div class="subedit">
			<div class="underline hidenew">
			<?php 
			$page_link = explode("/",$date['date_link']);
			$fds = count($page_link) - 1;
				//print "<li>$fds <li>$page_link";
				if(!empty($page_link[$fds])) { 
					$link = $page_link[$fds];
					$x = 0;
					while($x < $fds) { 
						$folder .= $page_link[$x]."/";
						$x++;
					}
				} else {
					$link = $page_link[0];
				}

		?>
			<div class="pad">
			<div style="float: left;"><?php print $setup['url'].$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/".$folder.""; ?></div>
		   <div style="float: left;" id="ex_link"><span class="highlight" id="ex_link_hl"><?php print $link;?></span>/  <a href="javascript:editBlogLink();">edit</a></div>
		   <div style="foat:left; display:none;" id="edit_ex_link" >
			<input type="text" name="new_link" size="30" id="new_link" value="<?php print $link;?>">
			<a href="javascript:saveEditBlogLink('<?php print $date['date_id'];?>', 'new_link', 'edit_ex_link','<?php print $site_setup['sep_page_names'];?>');">save</a> |  <a href="javascript:cancelEditBlogLink();">cancel</a>
			</div>
			<div id="savedLink"></div>
			<div class="cssClear"></div>
			</div>
		</div>
	</div>
	</div>
	</div>
	<div>&nbsp;</div>

<?php } ?>

	<div>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">External Link</div>
			<div class="subedit">
				<div class="underline hidenew">Entering in an external link here, when someone goes to this page, they will be re-directed to this link.  <?php if($setup['unbranded'] !== true) { ?>
Example, enter in http:///www.picturespro.com, when someone goes to this page, they will not see this page but be re-directed to http://www.picturespro.com<?php } ?></div>
				<div class="underline hidenew"><input type="text" class="textfield" size=40 name="external_link" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['external_link']));?>" style="width: 98%;"></div>
			</div>
		</div>
	</div>



	<div>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Include a PHP  file for page content</div>
			<div class="subedit">
				<div class="underline hidenew">This will allow you to include a PHP file to use as the content instead of the content in the text editor above.</div>
				<div class="underline hidenew"><input type="text" class="textfield" size=40 name="page_include_page" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['page_include_page']));?>" style="width: 98%;"></div>
			</div>
		</div>
	</div>



	<?php if($date['page_home']  == "1") { ?>
	<div class="underline hidenew">
	<div class="label"><input type="checkbox" name="find_my_photos" id="find_my_photos" value="1" <?php if($_REQUEST['find_my_photos'] == "1") { print " checked"; } ?>> <label for="find_my_photos">Add find my photos form</label></div>
	</div>
	<?php } ?>

	<div class="underline hidenew">

<div class="label"><input type="checkbox" name="date_disable_side" id="date_disable_side" value="1" <?php if($_REQUEST['date_disable_side'] == "1") { print " checked"; } ?>> <label for="date_disable_side">Disable Side Bar if in use</label></div>
</div>
<div>&nbsp;</div>
<?php if($cat_type == "store") { ?>
<div class="underline hidenew">
<div class="label"><input type="checkbox" name="prod_no_link" value="1" <?php if($_REQUEST['prod_no_link'] == "1") { print " checked"; } ?>> Do not link to a page from cart</div>
</div>
<div>&nbsp;</div>
<?php } ?>

</div>


</div>



<!-- START RIGHT SIDE -->



<div style="width: 23%; float: right;">
<input type="hidden" name="page_home" value="<?php print $date['page_home']; ?>">
<?php if($_REQUEST['date_id'] > 0) { ?>
<div class="bottomSave">
<center>
<div id="submitButtonLoading"><img src="graphics/loading1.gif"></div>
	<input type="hidden" name="date_id" value="<?php  print $_REQUEST['date_id'];?>">
	<?php  if(!empty($_REQUEST['date_id'])) { ?>
		<input type="submit" name="submit" class="submit" id="submitButton" value="SAVE CHANGES">
	<?php  } else { ?>
		<input type="submit" name="submit" class="submit" id="submitButton" value="SAVE NOW">
	<?php  } ?>
</center>
</div>
<?php } ?>
<?php if(($date['page_home']  !== "1")&&($date['page_404'] !=="1")==true) { ?>
<?php if(($date_type == "news")OR($date_type == "page" AND $date['page_under'] > 0)==true) { ?>
	<div id="" class="hidenew">
		<div class="underline hidenew">

<?php if($cat_type == "booking") { ?>
<?php if($setup['unbranded'] !== true) { ?><div class="pc center"><a href="https://www.picturespro.com/sytist-manual/calendar/" target="_blank" class="the icons icon-info-circled"><i>Booking Calendar Information</i></a></div><?php } ?>
<?php } ?>

			<div>

			<select name="date_public"  class="h3">
			<option value="2" class="h3" <?php  if($_REQUEST['date_public']=="2") { print " selected"; } ?>>Draft</option>
			<option value="1" class="h3" <?php  if($_REQUEST['date_public']=="1") { print " selected"; } ?>>Publish</option>
			<?php if($cat_type == "clientphotos") { ?>
			<option value="3" class="h3" <?php  if($_REQUEST['date_public']=="3") { print " selected"; } ?>>Pre-Register</option>
			<?php } ?>
			</select>
			<!-- 	<nobr><input type="radio" name="date_public" id="date_public2"  value="2" <?php if(empty($_REQUEST['date_public'])) { print " checked"; } if($_REQUEST['date_public']=="2") { print " checked"; } ?>> <span class="h3"><label for="date_public2">Draft</label></span></nobr>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<nobr><input type="radio" name="date_public" id="date_public1" value="1" <?php if($_REQUEST['date_public']=="1") { print " checked"; }?>> <span class="h3"><label for="date_public1">Publish</label></span></nobr>
			-->
			</div>
		</div>
	</div>
	<div class="hidenew">&nbsp;</div>	
<?php } else { ?>

<input type="hidden" name="date_public" value="1">
<?php } ?>
<?php } else { ?>
<input type="hidden" name="date_public" value="1">
<?php } ?>



<!-- ######## Add sale option ############## -->
<?php if(($cat_type == "store")  == true) { ?>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Sale <?php if(($date['prod_sale_price'] > 0)&&(date('Y-m-d') <=$date['prod_sale_end'])&&(date('Y-m-d') >=$date['prod_sale_start'])==true) { print " ".showPrice($date['prod_sale_price'])." through ".$date['prod_sale_end'].""; } ?></div>
			<div class="subedit">

				<div class="underline hidenew">
					<div class="label">Start Date</div>
					<div><input type="text" id="prod_sale_start" name="prod_sale_start" size="20" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['prod_sale_start']));?>"  class="inputtip datepicker" title="Must be in YYYY-MM-DD format"></div>
				</div>
				<div class="underline">
					<div class="label">End Date</div>
					<div><input type="text" id="prod_sale_end" name="prod_sale_end" size="20" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['prod_sale_end']));?>"  class="inputtip datepicker" title="Must be in YYYY-MM-DD format"></div>
				</div>

				<div class="underline hidenew">
					<div class="label">Sale Price</div>
					<div><input type="text" id="prod_sale_price" name="prod_sale_price" size="20" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['prod_sale_price']));?>"  class="" title=""></div>
				</div>
			<div class="underline hidenew">
				<div class="fieldLabel">On sale message</div>
				<div><input type="text" id="prod_sale_message" name="prod_sale_message" size="40" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['prod_sale_message']));?>"  class="field100" title=""></div>
			</div>
		</div>
	</div>
	<div>&nbsp;</div>

<?php } ?>


	<div class="<?php if($cat_type == "booking") { ?>hidden<?php } ?>">
		<div id="" class="hidenew">
		<div class="underlinelabel subeditclick">Password Protect<?php if($_REQUEST['private'] > 0) { print "<div class=\"underlinesmall\">Yes (".$_REQUEST['password'].")</div>"; } ?>
		 <?php if($date['date_paid_access'] == "1") { ?><div>Paid Access: <?php print showPrice($date['prod_price']); if($date['date_credit'] > 0) { print " / ".showPrice($date['date_credit']); } ?></div><?php } ?> 
		</span><div class="clear"></div></div>
			<div class="subedit ">

			<div class="underline hidenew">
			<input type="radio" name="private" onclick="selectpass();" id="private0" value="0" <?php if(empty($_REQUEST['private'])) { print " checked"; } if($_REQUEST['private']=="0") { print " checked"; } ?>> <label for="private0">No</label>
			</div>
			<div  id="passdiv" class="<?php if($_REQUEST['private'] <=0) { print "hidden"; } ?>">
				<div class="underline">
				<div class="label"><b>Enter Password</b></div>
				<div><input type="text" class="textfield <?php if($_REQUEST['private'] >0) { print "required"; } ?>" size=40 name="date_p" id="date_p" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['password']));?>"  style="width: 98%;"></div>
				<div><i>Do not use the + or % signs in passwords.</i></div>
				</div>
			</div>

			<div class="underline hidenew">
			<input type="radio" name="private"  onclick="selectpass();" id="private1"  value="1" <?php if($_REQUEST['private']=="1") { print " checked"; } ?>> <label for="private1">Yes - List with other galleries then ask for password</label><!-- <br>This option means it will be displayed with the list of galleries and when clicked, it will ask for the password. -->
			</div>
			<div class="underline hidenew">
			<input type="radio" name="private"  onclick="selectpass();" id="private2"  value="2" <?php if($_REQUEST['private']=="2") { print " checked"; } ?>> <label for="private2">Yes - Do not list with galleries and no preview is shown</label><!-- <br>This option means it will not be displayed with galleries on the website and the customer will just enter in the password in the Find My Photos form and directed to the gallery. -->
			</div>
			<div class="underlinelabel paidaccess <?php if($_REQUEST['private'] <=0) { print "hidden"; } ?>"><input type="checkbox" name="date_paid_access" id="date_paid_access" value="1" <?php if($date['date_paid_access'] == "1") { print "checked"; } ?> onchange="showpaidaccess();"> <label for="date_paid_access">Paid Access</label> <div class="moreinfo" info-data="paidaccessinfo"><div class="info"></div></div></div>
			<div class="paidaccessprices <?php if(($_REQUEST['private'] <=0)||($date['date_paid_access'] <=0)==true) { print "hidden"; } ?>">
			<div class="underlinelabel">
				<div class="left p50">PRICE</div>
				<div class="left p50"><input type="text" size="6" class="center" name="paid_access_price" id="paid_access_price" value="<?php print $date['prod_price'];?>"></div>
				<div class="clear"></div>
			</div>
			<div class="underlinelabel">

				<div class="left p50">CREDIT</div>
				<div class="left p50"><input type="text" size="6" class="center" name="paid_access_credit" id="paid_access_credit" value="<?php print $date['date_credit'];?>"></div>
				<div class="clear"></div>
			</div>

			<div class="underlinespacer">
			<input type="checkbox" name="date_paid_access_unlock" id="date_paid_access_unlock" value="1" <?php if($date['date_paid_access_unlock'] == "1") { ?>checked<?php } ?>> <label for="date_paid_access_unlock">Make public gallery once a purchase has been made</label><br>Selecting this option, once 1 purchase is made, the gallery will be changed to a public gallery for anyone to view and paid access removed.
			</div>
				<div class="underline">
				Paid access will require the customer to pay to view the page. You can also enter in a credit that will be applied to their account when purchased.<br><br>If you use this option you MUST select "Require create an account" under <a href="index.php?do=settings&action=checkout">Customer Account Options On Checkout</a> in Settings -> Checkout. <b>Do not give out the password when using Paid Access unless you want them to view without paying. If someone has the password, they can access the gallery without paying</b>.<br><br><a href="https://www.picturespro.com/sytist-manual/articles/paid-access/">More about the Paid Access Feature</a>.
				</div>
			</div>
			</div>
	</div>
	</div>
	<div>&nbsp;</div>	


<?php if($sytist_store == true) { ?>
<div id="clientgalleries"  <?php if($cat_type !== "clientphotos") { ?>style="display: none;"<?php } ?>>
<?php if((empty($_REQUEST['date_id']))&&($cat_type == "clientphotos")==true) { 
	if($cat_price_list  > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$cat_price_list."' ");
	} else { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_default='1' ");
	}
	$_REQUEST['date_photo_price_list'] = $list['list_id'];
}
?>



<div id="" class="hidenew">
	<div class="underlinelabel subeditclick">Price List<?php if($_REQUEST['date_photo_price_list'] > 0) { $list = doSQL("ms_photo_products_lists",  "*", "WHERE list_id='".$_REQUEST['date_photo_price_list']."' "); print "<div class=\"underlinesmall\">".$list['list_name']."</div>"; } else { print "<div>None selected</div>"; } ?></span><div class="clear"></div></div>
	<div class="subedit">
		<div class="underline hidenew">
		<select name="date_photo_price_list" id="date_photo_price_list">
		<option value="0">No price list - not for sale</option>
		<?php $lists = whileSQL("ms_photo_products_lists", "*", "ORDER BY list_name ASC ");
			while($list = mysqli_fetch_array($lists)) { ?>
			<option value="<?php print $list['list_id'];?>" <?php if($list['list_id'] == $_REQUEST['date_photo_price_list']) { print "selected"; } ?>><?php print $list['list_name'];?></option>
			<?php } ?>
			</select>
		</div>
			<div class="underlinespacer">To manage your photo products and price lists, go to <a href="index.php?do=photoprods">Photo Products</a> in the main menu.</div>


			<div class="underlinelabel">Change price list after a certain date?</div>
			<div class="underlinespacer">If you want the price list to change to a different one after a certain date, enter in the date to change and the price list to change to. Otherwise leave both blank.</div>

			<div class="underline">
		After <input type="text" id="change_price_list_date" name="change_price_list_date" size="20" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['change_price_list_date']));?>"  class="inputtip datepicker" title="Must be in YYYY-MM-DD format">

		</div>
	
		<div class="underline">
		<div>Change to: </div>
		<div>
			<select name="change_price_list" id="change_price_list">
			<option value="0">Do not use</option>
			<?php $lists = whileSQL("ms_photo_products_lists", "*", "ORDER BY list_name ASC ");
				while($list = mysqli_fetch_array($lists)) { ?>
				<option value="<?php print $list['list_id'];?>" <?php if($list['list_id'] == $_REQUEST['change_price_list']) { print "selected"; } ?>><?php print $list['list_name'];?></option>
			<?php } ?>
			</select>
		</div>
		</div>


	</div>
	</div>
	<div>&nbsp;</div>

	<div class="hidenew">

		<?php if($date['date_owner'] > 0) { 
		$dp = doSQL("ms_people","*","WHERE p_id='".$date['date_owner']."' ");
		$eminvite = doSQL("ms_emails", "*", "WHERE email_id_name='galleryowner' ");?>
		<div class="underlinelabel">
			<div>Gallery Owner</div>
			<div class="underlinesmall"><a href="index.php?do=people&p_id=<?php print $dp['p_id'];?>"><?php print $dp['p_name']." ".$dp['p_last_name'];?></a> <br>
			<span class="underlinesmall"><a href="" onclick="pagewindowedit('w-send-email2.php?date_id=<?php print $date['date_id'];?>&email_to=<?php print $dp['p_email'];?>&noclose=1&nofonts=1&nojs=1&email_id=<?php print $eminvite['email_id'];?>'); return false;"><?php print ai_email;?> email information</a></span></div> 
		</div>
		<div class=" subeditclick">change</div>
		<?php } else { ?>
		<div class="underlinelabel subeditclick">Gallery Owner </div>
		<?php } ?>

		<div class="subedit">
			<div class="underline hidenew">
			<select name="date_owner" id="date_owner">
			<option value="0">None</option>
			<?php $ps = whileSQL("ms_people", "*", "ORDER BY p_last_name ASC ");
			while($p = mysqli_fetch_array($ps)) { ?>
				<option value="<?php print $p['p_id'];?>" <?php if($date['date_owner'] == $p['p_id']) { ?>selected<?php } ?> title="<?php print $p['p_email'];?>"><?php print $p['p_last_name'].", ".$p['p_name'];?></option>
			<?php } ?>
			</select>
			</div>
			<div class="underlinespacer">The gallery owner will have the option to hide photos in their gallery so anyone they invite to view their photos can not see those hidden photos. 
		<?php if($setup['unbranded'] !== true) { ?><a href="https://www.picturespro.com/sytist-manual/articles/gallery-owner/" target="blank">Learn more about this feature</a><?php } ?></div>

		</div>
	</div>
	<div>&nbsp;</div>
</div>
<?php if($cat_type == "clientphotos") { ?>
<div class="hidenew">
	<div class="underlinelabel subeditclick">Gallery Exclusive Mode<?php if($date['date_gallery_exclusive'] == "1") { ?>: On<?php } else { ?>: Off<?php } ?></div>
	<div class="subedit">
		<div class="underline">
		<div><input type="checkbox"  name="date_gallery_exclusive" id="date_gallery_exclusive" value="1" <?php if($date['date_gallery_exclusive'] == "1") { ?>checked<?php } ?>> <label for="date_gallery_exclusive">Enable Gallery Exclusive</label></div>
		<div>
		<input type="checkbox"  name="date_gallery_exclusive_no_cover" id="date_gallery_exclusive_no_cover" value="1" <?php if($date['date_gallery_exclusive_no_cover'] == "1") { ?>checked<?php } ?>> <label for="date_gallery_exclusive_no_cover">Do not display opening full screen photo</label></div>
		</div>
		<?php if($setup['unbranded'] !== true) { ?><div class="underlinespacer"><a href="https://www.picturespro.com/sytist-manual/articles/gallery-exclusive-mode/" target="blank">Learn more about this feature</a></div><?php } ?>
	</div>
</div>
	<div>&nbsp;</div>
<?php } ?>

<!-- STORE STUFF -->
<div id="storestuff" class="storestuff" <?php if($cat_type !== "store") { ?>style="display: none;"<?php } ?>>
	<script>
		function selectProductConfig() { 
			if($("#prod_type").val() == "download") { 
				$(".physical").hide();
				$(".download").show();
				$(".package").hide();
				$(".subscription").hide();
			}
			if($("#prod_type").val() == "ship") { 
				$(".physical").show();
				$(".download").hide();
				$(".package").hide();
				$(".subscription").hide();
			}
			if($("#prod_type").val() == "service") { 
				$(".physical").hide();
				$(".download").hide();
				$(".package").hide();
				$(".subscription").hide();
			}

			if($("#prod_type").val() == "package") { 
				$(".physical").hide();
				$(".download").hide();
				$(".package").show();
				$(".subscription").hide();
			}

			if($("#prod_type").val() == "subscript") { 
				$(".physical").hide();
				$(".download").hide();
				$(".package").hide();
				$(".subscription").show();
			}

		}
	</script>
	<div id="" class="hidenew">


			<div class="underline">
				<div class="label">Product Type</div>
				<div>
				<select name="prod_type" id="prod_type" class="<?php if($cat_type == "store") { ?><?php if($_REQUEST['date_id'] > 0) { ?>required<?php  } ?><?php } ?>" onChange="selectProductConfig();">
				<option value="">Select</option>
				<option value="ship" <?php if($_REQUEST['prod_type'] == "ship") { print "selected"; } ?>>Physical product to be shipped</option>
				<option value="download" <?php if($_REQUEST['prod_type'] == "download") { print "selected"; } ?>>Download product</option>
				<option value="service" <?php if($_REQUEST['prod_type'] == "service") { print "selected"; } ?>>Service product</option>
				<?php if($setup['subscriptions'] == true) { ?>
				<option value="subscript" <?php if($_REQUEST['prod_type'] == "subscript") { print "selected"; } ?>>Subscription</option>
				<?php } ?>
				<option value="package" <?php if($_REQUEST['prod_type'] == "package") { print "selected"; } ?>>Pre-sell Collection</option>
				</select>
				</div>
			</div>

	<div class="hidenew package <?php if($date['prod_type'] !== "package") { print "hidden"; } ?>">
	<div class="underline">
	<select name="date_package" id="date_package" class="field100">
	<option value="">Select a collection</option>
	<?php $packs = whileSQL("ms_packages", "*", "WHERE package_buy_all='0' ORDER BY package_name ASC "); 
	while($pack = mysqli_fetch_array($packs)) { ?>
	<option value="<?php print $pack['package_id'];?>" <?php if($pack['package_id'] == $date['date_package']) { print "selected"; } ?>><?php print $pack['package_name'];?></option>

	<?php } ?>
	</select>
	</div>
	<div class="underline">
		<div class="label"><input type="checkbox" id="date_package_pre_reg" name="date_package_pre_reg" value="1" <?php if($date['date_package_pre_reg'] == "1") { print "checked"; } ?>> <label for="date_package_pre_reg">Have customer select from pre-reigstration pages</label></div>
		<div>Selecting this option will have the customer select from pages / galleries you have set to pre-register and will automatically add them to the pre-registration list  and access list for that page.</div>
	</div>
	<div class="underline">
		<div class="label"><input type="checkbox" id="prod_shipping" name="prod_shipping" value="1" <?php if($date['prod_shipping'] == "1") { print "checked"; } ?>> <label for="prod_shipping">Enable shipping calculations</label></div>
	</div>

	
	<div>&nbsp;</div>
	</div>



		<div class="hidenew download <?php if($date['prod_type'] !== "download") { print "hidden"; } ?>">
		<div class="underline" id="downloadfileupload"><div style="display: block; z-index: 5;padding: 2px;" >
		<input type="file" name="file_upload" id="file_upload" />
		<?php 
		$hash = $site_setup['salt']; 
		$timestamp = date('Ymdhis');
		?>
		<script>
		$(function() {
			$('#file_upload').uploadify({
				 'multi'    : false,
				'method'   : 'post',
				'fileTypeExts' : '*',
				'fileTypeDesc' : 'all files',
				'buttonText' : 'Upload Download File',
				 'formData' : {'timestamp' : '<?php echo $timestamp; ?>', 
					 'token' : '<?php echo md5($hash.$timestamp); ?>', 
					'folder':'<?php print $_REQUEST['folder'];?>',
					 'date_id':'<?php print $date['date_id']; ?>' },
				'onQueueComplete' : function(queueData) {
					$.get("admin.actions.php?action=getNewUploadedFile&date_id=<?php print $date['date_id'];?>", function(data) {
						// alert(data);
						$("#downloadfile").show().html(data);
					});
				}, 
					'onUploadError' : function(file, errorCode, errorMsg, errorString) {
					alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
					}, 

				// 'debug'    : true,	
				'width'    : 200,
				'swf'      : 'uploadify/uploadify.swf',
				'uploader' :'prod_upload.php'
				// Put your options here
			});
		});
		</script>


	</div>
	</div>
		<div id="downloadfile" class="underline hidenew center <?php if(empty($date['prod_file'])) { ?>hidden<?php } ?>">
		<?php if(!empty($date['prod_file'])) { ?>
		<a href="" onClick="deleteProductFile('<?php print $date['date_id'];?>'); return false;" class="tip" title="Delete This File"><?php print ai_delete;?></a> <a href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['downloads_folder']."/".$date['prod_file'];?>" target="blank"><?php print $date['prod_file'];?></a>
		<?php } else { ?>
		No file uploaded
		<?php } ?>
		</div>


		<!-- VIDEO TEST --> 


				<?php 
				$vids = whileSQL("ms_videos", "*", "ORDER BY vid_name ASC ");

				if(mysqli_num_rows($vids) > 0) { ?>
				<div class="underline">
				<div class="label">Or select a video to download</div>
				<select name="date_video_download" id="date_video_download" class="field100">
				<option value="">No video</option>
				<?php while($vid = mysqli_fetch_array($vids)) { ?>
				<option value="<?php print $vid['vid_id'];?>" <?php if($date['date_video_download'] == $vid['vid_id']) { print "selected"; } ?>><?php print $vid['vid_name'];?></option>
				<?php } ?>
				</select>
				</div>
				<?php } ?>



			<div class="underline hidenew">
				<div class="fieldLabel">File Download Name</div>
				<div><input type="text" id="prod_dl_name" name="prod_dl_name" size="40" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['prod_dl_name']));?>"  class="inputtip field100" title="This is the name of the file when the customer downloads it."></div>
			</div>
		<?php if($setup['sytistsite'] == true) { ?>
			<div class="underline hidenew">
				<div class="fieldLabel ">Current Version</div>
				<div><input type="text" id="prod_version" name="prod_version" size="40" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['prod_version']));?>"  class="inputtip field100" title="Optional"></div>
			</div>


			<div class="underline"><input type="checkbox" name="prod_create_reg_key" id="prod_create_reg_key" value="1" <?php  if($_REQUEST['prod_create_reg_key'] == "1") { print "checked"; } ?> class="inputtip" title="Create a registration key when purchased."> Create Registration Key</div>
		<?php } ?>

		</div>
		<div>&nbsp;</div>
			</div>



				<!-- 
				<div id="" class="hidenew">
					<div class="underlinelabel "><span class="left">Genders</span>

					<div class="clear"></div>
					</div>
					<div class="pc">
					<select name="genders" id="genders">
				<option value="">Both</option>
				<option value="mens" <?php if($date['genders'] == "mens") { ?>selected<?php } ?>>Men's Only</option>
				<option value="womens" <?php if($date['genders'] == "womens") { ?>selected<?php } ?>>Womens's Only</option>
					</select>
					</div>
				</div>

			<div>&nbsp;</div>
			-->




				<div id="" class="hidenew">
					<div class="underlinelabel subeditclick"><span class="left">Options</span>
					<?php $num_opts = countIt("ms_product_options", "WHERE opt_date='".$date['date_id']."' ORDER BY opt_order ASC ");
					if($num_opts > 0) { ?><span class="right"><?php print $num_opts;?></span><?php } ?>

					<div class="clear"></div>
					</div>
					<div class="subedit">

						<?php $opts = whileSQL("ms_product_options", "*", "WHERE opt_date='".$date['date_id']."' ORDER BY opt_order ASC ");
						if(mysqli_num_rows($opts) <= 0 ) {?>
						<div class="underline center">No options created</div> 
						<?php } ?>
						<?php 
							while($opt = mysqli_fetch_array($opts))  { 
							$total_opts++;
							?>
							<div class="underline"><a href="" onclick="editstoreoption('<?php print $date['date_id'];?>','<?php print $opt['opt_id'];?>'); return false;" title="Save any changes before editing options" class="tip"><?php print $opt['opt_name'];?></div>
							<?php
						 } ?>
						<div class="underline center">
							<a href="" onclick="editstoreoption('<?php print $date['date_id'];?>'); return false;"  title="Save any changes before editing options" class="tip">Create a new option</a>
						</div>


					</div>
				</div>

			<div>&nbsp;</div>


			<div class="physical" <?php if($_REQUEST['prod_type'] !== "ship") { print "style=\"display: none;\""; } ?>>
			<!-- 
			<div class="label">Cost</div>
			<div class="row "><input type="text" id="prod_cost" name="prod_cost" size="8" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['prod_cost']));?>"  class="inputtip inputtitle" title="Enter in the price to charge for this product without the currency sign">
			</div>
			-->


				<div id="" class="hidenew">
					<div class="underlinelabel subeditclick"><span class="left">Inventory</span><?php if($_REQUEST['prod_qty'] > 0) { ?><span class="right"><?php print $_REQUEST['prod_qty'];?></span><?php } ?>
					<div class="clear"></div></div>
					<div class="subedit">
						<div class="underline">
							<input type="text" id="prod_qty" name="prod_qty" size="3" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['prod_qty']));?>"  class="center"> 
							<input type="checkbox" name="prod_inventory_control" id="prod_inventory_control" value="1" <?php if($_REQUEST['prod_inventory_control'] == "1") { print "checked"; } ?>> Inventory Control
						</div>
					</div>
				</div>

			<div>&nbsp;</div>

			</div>

		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick"><span class="left"">Credit</span><?php if($_REQUEST['date_credit'] > 0) { ?><span class="right"><?php print showPrice($_REQUEST['date_credit']);?></span><?php } ?>
			<div class="clear"></div>
			</div>
			<div class="subedit">
				<div class="underline">
					<div><input type="text" id="date_credit" name="date_credit" size="6" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['date_credit']));?>"  class="center"> </div>
					<div>By entering a credit amount here, when someone purchases this product, a credit in this amount will automatically be added to their account.<br><br>
					 You will need to add information about the credit in the description so the customers know about it.</div>
				</div>
			</div>
		</div>
		<div>&nbsp;</div>


		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick"><span class="left"">Min. Order</span><?php if($_REQUEST['qty_min'] > 0) { ?><span class="right"><?php print $_REQUEST['qty_min'];?></span><?php } ?>
			<div class="clear"></div>
			</div>
			<div class="subedit">
				<div class="underline">
					<div><input type="text" id="qty_min" name="qty_min" size="3" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['qty_min']));?>"  class="center"> </div>
					<div>If you want to set a minimum quantity that can be purchased, enter the minimum amount here.</div>
				</div>
			</div>
		</div>
		<div>&nbsp;</div>


		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick"><span class="left"">Max. Order</span><?php if($_REQUEST['prod_max_one'] > 0) { ?><span class="right"><?php print $_REQUEST['prod_max_one'];?></span><?php } ?>
			<div class="clear"></div>
			</div>
			<div class="subedit">
				<div class="underline">
					<div class="label"><input type="checkbox"  id="prod_max_one" name="prod_max_one" value="1" <?php if($_REQUEST['prod_max_one'] ==  "1") { print "checked"; } ?>> <label for="prod_max_one">Only allow one to be purchased</label>
					</div>
				</div>
			</div>
		</div>
		<div>&nbsp;</div>




		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick"><span class="left"">Photo Selections</span><?php if($_REQUEST['prod_photos'] > 0) { ?><span class="right"><?php print $_REQUEST['prod_photos'];?></span><?php } ?>
			<div class="clear"></div>
			</div>
			<div class="subedit">
				<div class="underline">
					<div ><input type="text" id="prod_photos" name="prod_photos" size="4" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['prod_photos']));?>" class="center"> </div>
					<div >If you want customers to be able to select photos from a gallery to be added to this product, enter in the maximum photos they can select. </div>
				</div>
			</div>
		</div>
		<div>&nbsp;</div>
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Product ID / SKU</div>
			<div class="subedit">
				<div class="underline">
					<div ><input type="text" id="prod_prod_id" name="prod_prod_id" size="15" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['prod_prod_id']));?>"></div>
				</div>
			</div>
		</div>


		<div>&nbsp;</div>


		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Additional Shipping</div>
			<div class="subedit">
				<div><input type="text" id="prod_add_ship" name="prod_add_ship" size="8" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['prod_add_ship']));?>"  class="" title=""></div>
				<div>This additonal shipping charge is added to your shipping calculation totals.</div>
			
			</div>
		</div>

	<div>&nbsp;</div>




		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Add To Cart Text</div>
			<div class="subedit">
			<div>Custom text for the add to cart button</div>
			<div><input type="text" name="add_to_cart_text" id="add_to_cart_text" class="field100" value="<?php print $date['add_to_cart_text'];?>"></div>
			<div>&nbsp;</div>
			<div><b>Add to cart redirect</b></div>
			<div><input type="text" name="add_to_cart_redirect" id="add_to_cart_redirect" class="field100"  value="<?php print $date['add_to_cart_redirect'];?>"></div>			
			<div>If you want the customer to be redirected after adding to cart, enter the URL here. Example to checkout, <?php print $setup['url'];?>/index.php?view=checkout</div>
			</div>
		</div>

	<div>&nbsp;</div>



	</div><?php // END STORE STUFF ?>

<?php } ?>






<?php if($setup['affiliate_links'] == 1) { ?>
	<div class="pageContent"><h3>Affiliate Site</h3></div>

	
	<div id="" class="hidenew">
		<div class="underline hidenew">
		<select name="date_aff_site">
			<option value="0">Select site to promote</option>
<?php 
$sites = whileSQL("ms_aff_info", "*", "WHERE  aff_id>'0'  ORDER BY aff_name ASC");
while($site = mysqli_fetch_array($sites)) { ?>
	<option value="<?php print $site['aff_id'];?>" <?php if($date['date_aff_site'] == $site['aff_id']) { print "selected"; } ?>><?php print $site['aff_name'];?></option>
<?php } ?>


		</select>
	</div>
	<div class="underline hidenew">Alternative links</div>
		<div class="underline hidenew">
				<div style="width:20%;" class="cssCell"><span class="bold">Link to: </div><div style="width:80%;" class="cssCell"><input type="text" class="textfield" size="40" name="date_aff_link" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['date_aff_link']));?>" style="width: 98%;"></div>
				<div class="cssClear"></div>
			</div>
		<div class="underline hidenew">
				<div style="width:20%;" class="cssCell"><span class="bold">Text: </div><div style="width:80%;" class="cssCell"><input type="text" class="textfield" size=40 name="date_aff_text" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['date_aff_text']));?>"  style="width: 98%;"></div>
				<div class="cssClear"></div>
			</div>

</div>

<div>&nbsp;</div>
<?php } ?>
<?php if(($date['page_home']  !== "1")&&($date['page_404'] !=="1")==true) { ?>

<?php if($cat_type == "registry") { 
	if(!empty($_REQUEST['date_id'])) { 
		$rdate = explode("-", $date['reg_event_date']);
		$year = $rdate[0];
		$month = $rdate[1];
		$day = $rdate[2];
	}
	?>
	<div>
		<div id="" class="hidenew">
		<div class="underlinelabel subeditclick"><span class="left">Event Date </span> <span class="right textirght"><?php if($_REQUEST['reg_event_date'] > 0) {  print $month."/".$day."/".$year; } ?></span><div class="clear"></div></div>
			<div class="subedit">
			<script>
			$(function() {
				$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
			});

			</script>

				<div class="underline hidenew">
				<input type="text" name="reg_event_date" id="reg_event_date" size="12" class="datepicker" value="<?php print $date['reg_event_date'];?>">
				</div>
			</div>
		</div>
	</div>
	<div>&nbsp;</div>

<?php } ?>


<?php
	if(!empty($_REQUEST['date_id'])) { 
		$rdate = explode("-", $date['date_date']);
		$year = $rdate[0];
		$month = $rdate[1];
		$day = $rdate[2];
		$rtime= explode(":", $date['date_time']);
		$hour = $rtime[0];
		$minute = $rtime[1];
		$second = $rtime[2];

	} else { 
		$year = date("Y", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
		$month = date("m", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
		$day = date("d", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
		$hour = date("H", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
		$minute = date("i", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
		$second = date("s", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
		$date['date_date'] = $year."-".$month."-".$day;
	}
?>

	<div <?php if(($cat_type == "registry") || ($cat_type == "booking") == true) { ?>class="hidden" <?php } ?>>
		<div id="" class="hidenew">
		<div class="underlinelabel subeditclick"><span class="left">Date </span> <span class="right textirght"><?php print $month."/".$day."/".$year;?></span><div class="clear"></div></div>
			<div class="subedit">
			<script>
			$(function() {
				$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
			});

			</script>

				<div class="underline hidenew">
				<input type="text" name="date_date" id="date_date" size="12" class="datepicker" value="<?php print $date['date_date'];?>">
				  @ 
				<input type="text" class="textfield" size="2"  maxlength="2"  name="date_hour" value="<?php  print htmlspecialchars(stripslashes($hour));?>"> :
				<input type="text" class="textfield" size="2"  maxlength="2"  name="date_minute" value="<?php  print htmlspecialchars(stripslashes($minute));?>"> 
				</div>
			</div>
		</div>
	<div>&nbsp;</div>	</div>



<?php
	if(!empty($_REQUEST['date_id'])) { 
		$rdate = explode("-", $date['date_expire']);
		$year = $rdate[0];
		$month = $rdate[1];
		$day = $rdate[2];

	} else { 
		if($upcat['cat_expire_days'] <=0) { 
			$year = "0000";
			$day = "00";
			$month = "00";
		} else { 
			$def_expire  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d") + $upcat['cat_expire_days'], date("Y")));
			$rdate = explode("-", $def_expire);
			$year = $rdate[0];
			$month = $rdate[1];
			$day = $rdate[2];
		}
		$date['date_expire'] = $year."-".$month."-".$day;
	}
?>

	<div class="<?php if($cat_type == "booking") { ?>hidden<?php } ?>">
		<div id="" class="hidenew ">
		<div class="underlinelabel subeditclick"><span class="left">Expires </span> <span class="right textirght"><?php if($year<=0) { print "n/a"; }  else { print $month."/".$day."/".$year; } ?></span><div class="clear"></div></div>
			<div class="subedit">
				<div class="underline hidenew">

				<input type="text" name="date_expire" id="date_expire" size="12" class="datepicker" value="<?php print $date['date_expire'];?>">

				</div>
			</div>
		</div>
	<div>&nbsp;</div>
	</div>

	<?php if($cat_type == "booking") {  ############################# Booking Calendar Options #######################################  
		$booksettings = doSQL("ms_bookings_settings", "*", "");

	?>
		<div class="hidenew">
			<div class="underlinelabel subeditclick"><span class="left">Options</span><?php $num_opts = countIt("ms_product_options", "WHERE opt_date='".$date['date_id']."' ORDER BY opt_order ASC ");
				if($num_opts > 0) { ?><span class="right"><?php print $num_opts;?></span><?php } ?><div class="clear"></div>
			</div>
				<div class="subedit">
					<?php $opts = whileSQL("ms_product_options", "*", "WHERE opt_date='".$date['date_id']."' ORDER BY opt_order ASC ");
					if(mysqli_num_rows($opts) <= 0 ) {?>
					<div class="underline center">No options created</div> 
					<?php } ?>
					<?php 
						while($opt = mysqli_fetch_array($opts))  { 
						$total_opts++;
						?>
						<div class="underline"><a href="" onclick="editstoreoption('<?php print $date['date_id'];?>','<?php print $opt['opt_id'];?>'); return false;" title="Save any changes before editing options" class="tip"><?php print $opt['opt_name'];?></div>
						<?php
					 } ?>
					<div class="underline center">
						<a href="" onclick="editstoreoption('<?php print $date['date_id'];?>'); return false;"  title="Save any changes before editing options" class="tip">Create a new option</a>
					</div>
				</div>
			</div>



		<div class="underline hidenew <?php if($date['book_all_day'] == "1") { ?>hide<?php } ?>" id="blength">
			<div class="left p20">
			<div>&nbsp;</div>
				<div class="label">Length</div>
			</div>
			<div class="left p80">
				<div class="left" style="margin-right: 16px;">
					<div class="label">Hours</div>
					<div>
					<select name="book_length_hours"  id="book_length_hours" class="">
						<?php
						$h = 0;
						while($h <=12) { ?>
						<option value="<?php print $h;?>" <?php if($h == $date['book_length_hours']) { ?>selected<?php } ?>><?php print $h;?></option>
						<?php
						$h++;
						} ?>
						</select>
					</div>
				</div>
				<div class="left"  style="margin-right: 48px;">
					<div class="label">Minutes</div>
					<div>
						<select name="book_length_minutes"  id="book_length_minutes" class="">
						<?php
						$m = 0;
						while($m < 60) { ?>
						<option value="<?php print $m;?>" <?php if($m == $date['book_length_minutes']) { ?>selected<?php } ?>><?php print $m;?></option>
						<?php
						$m = $m + 1;
;
						} ?>
						</select>
					</div>
				</div>

				<div class="clear"></div>
			</div>
			<div class="clear"></div>

		</div>
	<div class="hidenew">
		<div class="underlinelabel"><input type="checkbox" name="book_all_day" id="book_all_day" value="1" <?php if($date['book_all_day'] == "1") { ?>checked<?php } ?> onchange="selectdaytime('book_all_day');"> <label for="book_all_day">All Day Event</label></div>
		<div class="underlinelabel"><input type="checkbox" name="book_once_a_day" id="book_once_a_day" value="1" <?php if($date['book_once_a_day'] == "1") { ?>checked<?php } ?>  onchange="selectdaytime('book_once_a_day');"> <label for="book_once_a_day">Once Per Day Event</label></div>
	</div>
	
	<div id="book_once_a_day_div" <?php if($date['book_once_a_day'] <= 0) { ?>class="hide"<?php } ?>>
	<div class="underline">
		<div class="label">Select a time to be removed from availability on the booking calendar</div>
		
		<div>
			<?php
			$bt = explode(":",$date['book_once_a_day_time']);
			$bh = $bt[0];
			if($bh >= 12) {
				$bamp = "pm";
			} else { 
				$bamp = "am";
			}
			if($bh > 12) { 
				$bh = $bh - 12;
			} else if($bh == 0) {
				$bh = 12;
			}
			$bm = $bt[1];
			?>
			<select name="book_time_hour" id="book_time_hour" class="formfield center">
			<?php $h = 1;
			while($h <= 12) { ?>
			<option value="<?php print $h; ?>" <?php if($h == $bh) { ?>selected<?php } ?>><?php print $h;?></option>
			<?php
			$h++;
			}
			?>
			</select>
			<select name="book_time_minute" id="book_time_minute" class="formfield">
			<?php $m = 0;
			while($m <= 55) { 
				if($m < 10) { 
					$m = "0".$m;
				}?>
			<option value="<?php print $m; ?>" <?php if($m == $bm) { ?>selected<?php } ?>><?php print $m;?></option>
			<?php
			$m = $m+5;
			}
			?>
			</select>

			<select name="book_time_apm" id="book_time_apm" class="formfield">
			<option value="am" <?php if($bamp == "am") { ?>selected<?php } ?>>AM</option>
			<option value="pm" <?php if($bamp == "pm") { ?>selected<?php } ?>>PM</option>
			</select>
			</div>


		</div>

	</div>


<script>
function selectdaytime(id) { 
	if(id == "book_all_day") { 
		$("#book_once_a_day").attr("checked",false);
		$("#book_special_event").attr("checked",false);
		$("#specialevent").slideUp(200);
		$("#blength").slideUp(200);
		$("#leadtime").slideDown(200);
		$("#book_once_a_day_div").slideUp(200);
	}
	if(id == "book_once_a_day") { 
		$("#book_special_event").attr("checked",false);
		$("#specialevent").slideUp(200);
		$("#book_all_day").attr("checked",false);
		$("#blength").slideDown(200);
		$("#leadtime").slideDown(200);
		$("#book_once_a_day_div").slideDown(200);
	}
	if($("#book_all_day").attr("checked") !== "checked" && $("#book_once_a_day").attr("checked") !== "checked") { 
		$("#blength").slideDown(200);
		$("#book_once_a_day_div").slideUp(200);

	}
}
function specialevent() { 
	if($("#book_special_event").attr("checked")) { 
		$("#specialevent").slideDown(200);
		$("#book_all_day").attr("checked",false);
		$("#book_once_a_day").attr("checked",false);
		$("#blength").slideDown(200);
		$("#leadtime").slideUp(200);
		$("#book_once_a_day_div").slideUp(200);
		$("#custom_book_days").attr("checked",false);
		$("#custom_book_days_days").slideUp(200);
		$("#customavailabledays").slideUp(200);

	} else { 
		$("#specialevent").slideUp(200);
		$("#leadtime").slideDown(200);
		$("#customavailabledays").slideDown(200);
	}
}

function customdays() { 
	if($("#custom_book_days").attr("checked")) { 
		$("#custom_book_days_days").slideDown(200);
	} else { 
		$("#custom_book_days_days").slideUp(200);
	}
}
</script>
	<div class="">
		<div id="" class="hidenew ">
		<div class="underlinelabel"><input type="checkbox" name="book_special_event" id="book_special_event" value="1" <?php if($date['book_special_event'] == "1") { ?>checked<?php } ?> onchange="specialevent();"> <label for="book_special_event">Special 1 Day Event</label></div>


	
			<div id="specialevent" class="<?php if($date['book_special_event'] !== "1") { ?>hide<?php } ?>">
				<div class="underline">
				<div class="label">Date</div>
				<div><input type="text" name="book_special_event_date" id="book_special_event_date" size="12" class="datepicker" value="<?php print $date['book_special_event_date'];?>"></div>
				</div>

				<div class="underline">
					<div class="label">Start Time</div>
					<div>
					<?php
					$bt = explode(":",$date['book_special_event_start']);
					$bh = $bt[0];
					if($bh >= 12) {
						$bamp = "pm";
					} else { 
						$bamp = "am";
					}
					if($bh > 12) { 
						$bh = $bh - 12;
					} else if($bh == 0) {
						$bh = 12;
					}
					$bm = $bt[1];
					?>
					<select name="book_start_time_hour" id="book_start_time_hour" class="formfield center">
					<?php $h = 1;
					while($h <= 12) { ?>
					<option value="<?php print $h; ?>" <?php if($h == $bh) { ?>selected<?php } ?>><?php print $h;?></option>
					<?php
					$h++;
					}
					?>
					</select>
					<select name="book_start_time_minute" id="book_start_time_minute" class="formfield">
					<?php $m = 0;
					while($m <= 55) { 
						if($m < 10) { 
							$m = "0".$m;
						}?>
					<option value="<?php print $m; ?>" <?php if($m == $bm) { ?>selected<?php } ?>><?php print $m;?></option>
					<?php
					$m = $m+5;
					}
					?>
					</select>

					<select name="book_start_time_apm" id="book_start_time_apm" class="formfield">
					<option value="am" <?php if($bamp == "am") { ?>selected<?php } ?>>AM</option>
					<option value="pm" <?php if($bamp == "pm") { ?>selected<?php } ?>>PM</option>
					</select>					
					</div>
				</div>




				<div class="underline">
					<div class="label">End Time</div>
					<div>
					<?php
					$bt = explode(":",$date['book_special_event_end']);
					$bh = $bt[0];
					if($bh >= 12) {
						$bamp = "pm";
					} else { 
						$bamp = "am";
					}
					if($bh > 12) { 
						$bh = $bh - 12;
					} else if($bh == 0) {
						$bh = 12;
					}
					$bm = $bt[1];
					?>
					<select name="book_end_time_hour" id="book_end_time_hour" class="formfield center">
					<?php $h = 1;
					while($h <= 12) { ?>
					<option value="<?php print $h; ?>" <?php if($h == $bh) { ?>selected<?php } ?>><?php print $h;?></option>
					<?php
					$h++;
					}
					?>
					</select>
					<select name="book_end_time_minute" id="book_end_time_minute" class="formfield">
					<?php $m = 0;
					while($m <= 55) { 
						if($m < 10) { 
							$m = "0".$m;
						}?>
					<option value="<?php print $m; ?>" <?php if($m == $bm) { ?>selected<?php } ?>><?php print $m;?></option>
					<?php
					$m = $m+5;
					}
					?>
					</select>

					<select name="book_end_time_apm" id="book_end_time_apm" class="formfield">
					<option value="am" <?php if($bamp == "am") { ?>selected<?php } ?>>AM</option>
					<option value="pm" <?php if($bamp == "pm") { ?>selected<?php } ?>>PM</option>
					</select>					
					</div>
				</div>
			</div>
		</div>
	<div>&nbsp;</div>
	</div>


	<div class="<?php if($date['book_special_event'] == "1") { ?>hide<?php } ?>" id="leadtime">
		<div class="underline hidenew ">
		<div class="label">
			<select name="book_lead_time"  id="book_lead_time" class="">
					<?php
					$h = 0;
					while($h <=30) { ?>
					<option value="<?php print $h;?>" <?php if($h == $date['book_lead_time']) { ?>selected<?php } ?>><?php print $h;?></option>
					<?php
					$h++;
					} ?>
					</select> Days Lead Time
				</div>
				<div>This sets how many days ahead someone can book this. Example, set it to 1 and someone can book an appointment starting the following day. Set it to 2 and someone can book an appointment starting 2 days from the current date.</div>
		</div>


		<div class="underline hidenew ">
		<div class="label">
			<select name="book_max_days"  id="book_max_days" class="">
					<?php
					$h = 0;
					while($h <=365) { ?>
					<option value="<?php print $h;?>" <?php if($h == $date['book_max_days']) { ?>selected<?php } ?>><?php print $h;?></option>
					<?php
					$h++;
					} ?>
					</select> Max Days Advance Booking
				</div>
				<div>This sets how far in advance the service can be booked. Example, select 365 days for a year in advance. 180 days for 6 months. Select 0 for no limit.</div>
		</div>


</div>
		<div class="underline hidenew ">
			<div class="label">
			<select name="book_per_time"  id="book_per_time" class="">
					<?php
					$h = 1;
					while($h <=100) { ?>
					<option value="<?php print $h;?>" <?php if($h == $date['book_per_time']) { ?>selected<?php } ?>><?php print $h;?></option>
					<?php
					$h++;
					} ?>
					</select> Appointments Per Time</div>
				<div>How many appointments can be made per time available. </div>
		</div>

			<div>&nbsp;</div>



	<div class="hidenew <?php if($date['book_special_event'] == "1") { ?>hide<?php } ?>" id="customavailabledays" >
		<div class="underlinelabel"><input type="checkbox" name="custom_book_days" id="custom_book_days" value="1" <?php if($date['custom_book_days'] == "1") { print "checked"; } ?>  onchange="customdays();"> <label for="custom_book_days">Custom Available Days</label></div>
		
		<div id="custom_book_days_days" <?php if($date['custom_book_days'] !== "1") { ?>class="hide"<?php } ?>>
		<div class="underlinespacer">With this option checked here, you can set custom days & time for this service that will override the settings in Caledar -> Booking Settings.</div>
			<div class="underline"><input type="checkbox" name="Sunday" id="Sunday" value="1" <?php if($date['Sunday'] == "1") { print "checked"; } ?>> <label for="Sunday">Sunday</label></div>
			<div class="underline"><input type="checkbox" name="Monday" id="Monday" value="1" <?php if($date['Monday'] == "1") { print "checked"; } ?>> <label for="Monday">Monday</label></div>
			<div class="underline"><input type="checkbox" name="Tuesday" id="Tuesday" value="1" <?php if($date['Tuesday'] == "1") { print "checked"; } ?>> <label for="Tuesday">Tuesday</label></div>
			<div class="underline"><input type="checkbox" name="Wednesday" id="Wednesday" value="1" <?php if($date['Wednesday'] == "1") { print "checked"; } ?>> <label for="Wednesday">Wednesday</label></div>
			<div class="underline"><input type="checkbox" name="Thursday" id="Thursday" value="1" <?php if($date['Thursday'] == "1") { print "checked"; } ?>> <label for="Thursday">Thursday</label></div>
			<div class="underline"><input type="checkbox" name="Friday" id="Friday" value="1" <?php if($date['Friday'] == "1") { print "checked"; } ?>> <label for="Friday">Friday</label></div>
			<div class="underline"><input type="checkbox" name="Saturday" id="Saturday" value="1" <?php if($date['Saturday'] == "1") { print "checked"; } ?>> <label for="Saturday">Saturday</label></div>

<div class="underlinelabel">
From  <select name="book_start_time" id="book_start_time" class="">
<?php 
while($tm < 24) {
	while($tmm < 60) {
		if(date("H:i:s", mktime($tm,$tmm,1,1,1,1)) == $date['book_start_time']) { $selected = "selected"; }
	print "<option value=\"".date("H:i:s", mktime($tm,$tmm,1,1,1,1))."\" $selected>".date("h:i a", mktime($tm,$tmm,1,1,1,1))."</option>"; 
	unset($selected);
	$tmm = $tmm + 15;
	}
$tm++;
$tmm = 0;
}

?></select>

To

<select name="book_end_time" id="book_end_time" class="">
<?php 
$tm = 0;
$tmm = 0;
while($tm < 24) {
	while($tmm < 60) {
		if(date("H:i:s", mktime($tm,$tmm,1,1,1,1)) == $date['book_end_time']) { $selected = "selected"; }
	print "<option value=\"".date("H:i:s", mktime($tm,$tmm,1,1,1,1))."\" $selected>".date("h:i a", mktime($tm,$tmm,1,1,1,1))."</option>"; 
	unset($selected);
	$tmm = $tmm + 15;
	}
$tm++;
$tmm = 0;
}

?></select>
</div>

<div class="underlinelabel">
 Blocks

 <select name="book_blocks" id="book_blocks" class="">
 <option value="1" <?php if($date['book_blocks'] == "1") { ?>selected<?php } ?>>1 Minute</option>
 <option value="2" <?php if($date['book_blocks'] == "2") { ?>selected<?php } ?>>2 Minutes</option>
 <option value="3" <?php if($date['book_blocks'] == "3") { ?>selected<?php } ?>>3 Minutes</option>
 <option value="4" <?php if($date['book_blocks'] == "4") { ?>selected<?php } ?>>4 Minutes</option>
 <option value="5" <?php if($date['book_blocks'] == "5") { ?>selected<?php } ?>>5 Minutes</option>
 <option value="6" <?php if($date['book_blocks'] == "6") { ?>selected<?php } ?>>6 Minutes</option>
 <option value="7" <?php if($date['book_blocks'] == "7") { ?>selected<?php } ?>>7 Minutes</option>
 <option value="8" <?php if($date['book_blocks'] == "8") { ?>selected<?php } ?>>8 Minutes</option>
 <option value="9" <?php if($date['book_blocks'] == "9") { ?>selected<?php } ?>>9 Minutes</option>
 <option value="10" <?php if($date['book_blocks'] == "10") { ?>selected<?php } ?>>10 Minutes</option>
 <option value="11" <?php if($date['book_blocks'] == "11") { ?>selected<?php } ?>>11 Minutes</option>
 <option value="12" <?php if($date['book_blocks'] == "12") { ?>selected<?php } ?>>12 Minutes</option>
 <option value="13" <?php if($date['book_blocks'] == "13") { ?>selected<?php } ?>>13 Minutes</option>
 <option value="14" <?php if($date['book_blocks'] == "14") { ?>selected<?php } ?>>14 Minutes</option>
 <option value="15" <?php if($date['book_blocks'] == "15") { ?>selected<?php } ?>>15 Minutes</option>
 <option value="16" <?php if($date['book_blocks'] == "16") { ?>selected<?php } ?>>16 Minutes</option>
 <option value="17" <?php if($date['book_blocks'] == "17") { ?>selected<?php } ?>>17 Minutes</option>
 <option value="18" <?php if($date['book_blocks'] == "18") { ?>selected<?php } ?>>18 Minutes</option>
 <option value="19" <?php if($date['book_blocks'] == "19") { ?>selected<?php } ?>>19 Minutes</option>
 <option value="20" <?php if($date['book_blocks'] == "20") { ?>selected<?php } ?>>20 Minutes</option>
 <option value="25" <?php if($date['book_blocks'] == "25") { ?>selected<?php } ?>>25 Minutes</option>
 <option value="30" <?php if($date['book_blocks'] == "30") { ?>selected<?php } ?>>30 Minutes</option>
 <option value="45" <?php if($date['book_blocks'] == "45") { ?>selected<?php } ?>>45 Minutes</option>
 <option value="60" <?php if($date['book_blocks'] == "60") { ?>selected<?php } ?>>1 Hour</option>
 <option value="75" <?php if($date['book_blocks'] == "75") { ?>selected<?php } ?>>1 Hour 15 Minutes</option>
 <option value="90" <?php if($date['book_blocks'] == "90") { ?>selected<?php } ?>>1 Hour 30 Minutes</option>
 <option value="105" <?php if($date['book_blocks'] == "105") { ?>selected<?php } ?>>1 Hour 45 Minutes</option>
 <option value="120" <?php if($date['book_blocks'] == "120") { ?>selected<?php } ?>>2 Hours</option>
 <option value="180" <?php if($date['book_blocks'] == "180") { ?>selected<?php } ?>>3 Hours</option>
 <option value="240" <?php if($date['book_blocks'] == "240") { ?>selected<?php } ?>>4 Hours</option>

</select>
</div>

		</div>
	</div>
	<div>&nbsp;</div>



	<div class="hidenew">
		<div class="underlinelabel subeditclick">Book Now Button</div>
		<div class="subedit">
			<div>To add a book now button to <b>a different page</b> in your Sytist, copy and paste the following code in the < > HTML area of the page. <b>Do not add it to this page, it is automatically added.</b></div>
			<div> &lt;span onclick="showbookingcalendar('<?php print MD5($date['date_id']);?>'); return false;"  class="addtocart" style="padding: 8px;">Book Now&lt;/span></div>
		</div>
	</div>




			<div>&nbsp;</div>


<?php } ?>
		<div id="" class="hidenew <?php if(($cat_type !== "store") && ($cat_type  !== "booking") == true) { ?>hide<?php } ?>">
			<div class="underlinelabel subeditclick">Add Message To Order</div>
			<div class="subedit">
				<div><textarea name="prod_order_message" id="prod_order_message" class="field100" rows="3"><?php  print htmlspecialchars(stripslashes($_REQUEST['prod_order_message']));?></textarea></div>
				<div>This message will be added under this product on the order.</div>
			
			</div>
	<div>&nbsp;</div>
		</div>



<?php if($cat_type == "registry") { ?>

	<div id="" class="hidenew">
		<div class="underlinelabel">Goal Amount</div>
		<div class="center"><input type="text" id="reg_goal" name="reg_goal" size="8" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['reg_goal']));?>"  class="center" title=""></div>
		<!-- <div><input type="checkbox" name="reg_stop_goal" id="reg_stop_goal" value="1" <?php if($date['reg_stop_goal'] == "1") { print "checked"; } ?>> <label for="reg_stop_goal">Stop when goal is complete</label></div> -->
		<div>Here you can set a goal amount that will show on the registry page and show a total reached for the goal. Leave at 0.00 to not show a goal.</div>
	</div>
<?php } ?>

<script>
	function selectpass() { 
	if($("#private1").attr("checked") || $("#private2").attr("checked")) { 
		$("#passdiv").slideDown(200);
		$("#date_p").addClass("required");
		$(".paidaccess").slideDown(200);
	} else { 
		$("#passdiv").slideUp(200);
		$("#date_p").removeClass("required");
		$(".paidaccess").slideUp(200);
		$("#date_paid_access").attr("checked",false);
		$(".paidaccessprices").slideUp(200);
	}
}

function showpaidaccess() { 
	if($("#date_paid_access").attr("checked")) { 
		$(".paidaccessprices").slideDown(200);
	} else { 
		$(".paidaccessprices").slideUp(200);
	}

}
</script>

	<?php if(($cat_type == "clientphotos")||($cat_type == "standard") == true) { ?> 


	<?php if(countIt("ms_sub_galleries LEFT JOIN ms_calendar ON ms_sub_galleries.sub_date_id=ms_calendar.date_id", "WHERE  ms_calendar.green_screen_gallery='1' ") > 0) {  ?>
	<div class="<?php if($cat_type == "booking") { ?>hidden<?php } ?>">
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Green Screen Backgrounds
			<?php if($date['green_screen_backgrounds'] > 0) { 
				$g = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$date['green_screen_backgrounds']."' ");
				print "<div>".$g['sub_name']."</div>";
				} else { 
					print " n/a ";
				}
			?>
			<div class="clear"></div></div>
			<div class="subedit ">
				<div class="underline hidenew">
				<select name="green_screen_backgrounds" id="green_screen_backgrounds">
				<option value="">Select folder of backgrounds</option>
				<?php $subs = whileSQL("ms_sub_galleries LEFT JOIN ms_calendar ON ms_sub_galleries.sub_date_id=ms_calendar.date_id", "*", "WHERE  ms_calendar.green_screen_gallery='1'  ORDER BY sub_name ASC ");
				while($sub = mysqli_fetch_array($subs)) { ?>
				<option value="<?php print $sub['sub_id'];?>" <?php if($date['green_screen_backgrounds'] == $sub['sub_id']) { print "selected"; } ?>><?php print $sub['sub_name'];?></option>
				<?php } ?>
				</select>

				</div>
			<div class="underline">This option is for displaying green screen photos over backgrounds. <?php if($setup['unbranded'] !== true) { ?><a href="https://www.picturespro.com/sytist-manual/articles/green-screen/" target="_blank">Learn more about this feature</a>.
			<?php } ?>
			<br>	<?php $gdate = doSQL("ms_calendar", "*", "WHERE green_screen_gallery='1' ORDER BY date_id ASC "); ?>
			<a href="index.php?do=news&action=managePhotos&date_id=<?php print $gdate['date_id'];?>">Manage your backgrounds</a>

			</div>

			</div>
		</div>
	</div>
	<div>&nbsp;</div>	



	<?php } ?>



	<div class="<?php if($cat_type == "booking") { ?>hidden<?php } ?>">
		<div id="" class="hidenew">
			<div class="underlinelabel subeditclick">Passcode Photos<?php if($_REQUEST['passcode_photos'] > 0) { print "<div class=\"right textright\">Yes </div>"; } ?></span><div class="clear"></div></div>
			<div class="subedit ">
			<div class="underline">This option is for password protecting individual photos in a gallery. <?php if($setup['unbranded'] !== true) { ?><a href="https://www.picturespro.com/sytist-manual/articles/password-protecting-individual-photos-in-a-gallery/" target="_blank">Click here to learn about this feature</a>.<?php } ?>
			</div>
				<div class="underline hidenew">
				<input type="checkbox" name="passcode_photos" id="passcode_photos" value="1" <?php if($_REQUEST['passcode_photos'] == "1") { print "checked"; } ?>> <label for="passcode_photos">Enable passcode photos feature</label>
				</div>
			</div>
		</div>
	</div>
	<div>&nbsp;</div>	


<?php } ?>
<?php } ?>

	<?php if(($cat_type == "clientphotos")||($cat_type == "store") == true) { ?> 
	<div>
		<div id="" class="hidenew">
		<div class="underlinelabel subeditclick">Shipping Group <div>
		<div class="underlinesmall"><?php if($_REQUEST['shipping_group'] > 0) {
		$sg = doSQL("ms_shipping_groups", "*", "WHERE sg_id='".$_REQUEST['shipping_group']."' ");
		print "".$sg['sg_name'];
		} else { 
			print "Default";
		}
		?>
		</div>
		</div>
		<div class="clear">
		</div></div>
			<div class="subedit ">
		<div class="underlinespacer">If you have created different <a href="index.php?do=settings&action=states">shipping groups</a>, you can select which shipping group to use here.</div>

			<div class="underline hidenew">

		<select name="shipping_group" id="shipping_group">
		<?php 
		$groups = whileSQL("ms_shipping_groups", "*", "ORDER BY sg_name ASC ");
		while($group = mysqli_fetch_array($groups)) { 
			?>
			<option value="<?php print $group['sg_id'];?>" <?php if($group['sg_id'] == $_REQUEST['shipping_group']) { print "selected"; } ?>><?php print $group['sg_name'];?></option>
			<?php 
		}
		?>
		</select> 
		</div>


		<div class="underlinelabel">Change shipping group after a certain date?</div>
			<div class="underlinespacer">You can have the shipping options change after a certain date.</div>

			<div class="underline">
		After <input type="text" id="change_shipping_group_date" name="change_shipping_group_date" size="20" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['change_shipping_group_date']));?>"  class="inputtip datepicker" title="Must be in YYYY-MM-DD format">

		</div>
	
		<div class="underline">
		<div>Change to: </div>
		<div>
			<select name="change_shipping_group" id="change_shipping_group">
			<option value="0">Do not use</option>
			<?php 	
			$groups = whileSQL("ms_shipping_groups", "*", "ORDER BY sg_name ASC ");
				while($group = mysqli_fetch_array($groups)) { 
			?>
				<option value="<?php print $group['sg_id'];?>" <?php if($group['sg_id'] == $_REQUEST['change_shipping_group']) { print "selected"; } ?>><?php print $group['sg_name'];?></option>
			<?php } ?>
			</select>
		</div>
		</div>


	</div>
	</div>
	</div>
	<div>&nbsp;</div>	
<?php } ?>


	<?php if(($cat_type == "clientphotos")||($cat_type == "store") == true) { ?> 
	<div>
		<div id="" class="hidenew">
		<div class="underlinelabel subeditclick">Disable Payment Options<div>
		<div class="underlinesmall"><?php if(!empty($_REQUEST['disabled_payment_options'])) {
			print "ON";
		}
		?>
		</div>
		</div>
		<div class="clear">
		</div></div>
			<div class="subedit ">
			<?php if($cat_type == "store") { ?>
			<div class="underlinespacer">Disable certain payment options when purchasing this product.</div>
			<?php } else { ?>
			<div class="underlinespacer">Disable certain payment options for when purchasing from this gallery. </div>
			<?php } ?>
			<div class="underlinespacer bold">Below are your active payment options. Select the payment option(s) you want DISABLED.</div>
			<div class="hidenew">
			<?php
			$disabled_payment_options = explode(",",$_REQUEST['disabled_payment_options']);
			$pays = whileSQL("ms_payment_options", "*", "WHERE pay_dev_status='1' AND pay_status='1' ORDER BY pay_name ASC" );
			while($pay = mysqli_fetch_array($pays)) { ?>
			<div class="pc"><input type="checkbox" name="disabled_payment_options[<?php print $pay['pay_option'];?>]" id="<?php print $pay['pay_option'];?>" <?php if(in_array($pay['pay_option'],$disabled_payment_options)) { ?>checked<?php } ?>> <label for="<?php print $pay['pay_option'];?>"><?php print $pay['pay_name'];?></label></div>
			<?php  } ?>

			</div>


	</div>
	</div>
	</div>
	<div>&nbsp;</div>	
<?php } ?>











<?php 
if((!empty($_REQUEST['date_id']))AND($_REQUEST['page_under']<=0)AND(countIt("ms_calendar", "WHERE page_under='".$_REQUEST['date_id']."' ")>0)==true){ ?>

	<div>
		<div id="roundedForm" class="hidenew">
			<div class="label subeditclick">This page content</div>
			<div class="subedit">
			<div class="row hidenew">
				Since this page has sub pages, you can either make this page use the text content of one of the sub pages or use the content you enter in the text editor here.<br>
				<select name="page_use_subpage">
				<option value="0">Use this content</option>
				<?php 
				$spages = whileSQL("ms_calendar", "*", "WHERE page_under='".$_REQUEST['date_id']."' ORDER BY date_title ASC ");
				while($spage = mysqli_fetch_array($spages)) { 
					print "<option value=\"".$spage['date_id']."\" "; if($_REQUEST['page_use_subpage'] == $spage['date_id']) { print "selected"; } print ">".$spage['date_title']."</optoin>";
				}
				?>
				</select>
				</div>

			<div class="row hidenew">
				<input type="checkbox" name="page_list_sub_pages" value="1" <?php if($_REQUEST['page_list_sub_pages'] == "1") { print " checked"; } ?>> Check this box to list sub pages on this page.
			</div>

		<?php if($_REQUEST['date_cat'] <=0) { ?>
		<div class="row hidenew">
			<input type="checkbox" name="page_disable_drop" value="1" <?php if($_REQUEST['page_disable_drop'] == "1") { print " checked"; } ?>> Check this box to disable the drop down menu that list sub pages in the main top menu.
		</div>
		<?php } ?>
		</div>
	</div>
</div>
<div>&nbsp;</div>

	<div>
		<div id="roundedForm" class="hidenew">
			<div class="label subeditclick">Side Bar Text</div>
			<div class="subedit">
			<div class="row hidenew">
			If you are using sub pages with this page, you can enter in text that will show in the side bar under the section title.</div>
			<div class="row hidenew"><input type="text" class="textfield" size=40 name="page_snippet" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['page_snippet']));?>" style="width: 98%;"></div>
			</div>
			</div>
		</div>

<div>&nbsp;</div>

<?php } ?>


<?php if(($date['page_home'] !== "1") && ($date['page_404'] !== "1") == true) { ?><div class="pc center hidenew"><a href="" onclick="duplicatepage('<?php print $date['date_id'];?>'); return false;">Duplicate This Page</a></div><?php } ?>







<div>&nbsp;</div>
<div>&nbsp;</div>

<div class="pageContent">
	<div style="width:100%;" class="cssCell" style="text-align: center;">
	<center>
		<input type="hidden" name="page_under" value="<?php print $_REQUEST['page_under'];?>">
		<input type="hidden" name="page_order" value="<?php print $_REQUEST['page_order'];?>">

	<input type="hidden" name="do" value="<?php print $_REQUEST['do'];?>">
	<input type="hidden" name="action" value="addDate">
	<input type="hidden" name="submitit" value="yup">
	<input type="hidden" name="date_type" value="<?php print $date_type;?>">
	<input type="hidden" name="date_id" value="<?php  print $_REQUEST['date_id'];?>">
	<?php  if(!empty($_REQUEST['date_id'])) { ?>
		<!-- <input type="submit" name="submit" class="submitBig" value="SAVE CHANGES"> -->
	<?php  } ?>
	</center>
	</div>

	</div>
</div>
<div class="cssClear"></div>
	</form>
</div>
<div>&nbsp;</div>



<?php  } ?>

<?php 


function renameNewCat($id,$old_cat) {
	global $site_setup,$setup,$date_type;
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$id."' ");
	if(!empty($date['date_cat'])) {
		$parent_page = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
		$folder_link = $parent_page['cat_folder']."/";
	}
	if(!empty($old_cat)) {
		$ocat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$old_cat."' ");
	}

	$page_link = stripslashes(trim(strtolower($date['date_title'])));
	$page_link = strip_tags($page_link);
	$page_link = str_replace(" ","".$site_setup['sep_page_names']."",$page_link);
	$link = preg_replace("/[^a-z_0-9-]/","", $page_link);
	
	$new_page_link = $folder_link."".$link;
	$old = $setup['path']."".$setup['content_folder']."".$ocat['cat_folder']."/".$date['date_link'];
	$new  =  $setup['path']."".$setup['content_folder']."/".$folder_link."".$link;
	rename($old,$new);
	updateSQL("ms_calendar", "date_link='".$link."' WHERE date_id='".$id."' ");
	$fp = fopen($new."/index.php", "w");
	$file_include = "main_index_include.php";
		$add_path .= "../";
		if(!empty($date['date_cat'])) { 
			$add_path .= "../";
			if(!empty($parent_page['cat_under_ids'])) { 
				$ids = explode(",",$parent_page['cat_under_ids']);
				foreach($ids AS $num) { 
					$add_path .="../";
				}
			}
			$info =  "<?php\n\$date_id = ".$id."; \n\$to_path = \"$add_path\"; \ninclude \"".$add_path."".$setup['inc_folder']."/$file_include\";\n?>"; 
		} else { 
			$info =  "<?php\n\$date_id = ".$id."; \n\$to_path = \"$add_path\"; \ninclude \"".$add_path."".$setup['inc_folder']."/$file_include\";\n?>"; 
		}
	fputs($fp, "$info\n");
	fclose($fp);

//	print "<li>$old";
//	print "<li>$new";
//  exit();
}


function createCategory($cat_id) {
	global $site_setup,$setup;
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$cat_id."' ");

	$page_link = stripslashes(trim(strtolower($cat['cat_name'])));
	$page_link = strip_tags($page_link);
	$page_link = str_replace(" ","".$site_setup['sep_page_names']."",$page_link);
	$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);
	$date_folder = $setup['content_folder'];
	if($cat['cat_under'] > 0) {
		$up_folder = doSQL("".cat_table."", "*", "WHERE cat_id='".$cat['cat_under']."' ");
		$page_link = "".$up_folder['cat_folder']."/".$page_link;
	} else { 
		$page_link = "/".$page_link;
	}

	if(file_exists($setup['path']."".$date_folder."/".$page_link)) {
		$page_link = $cat_id."".$site_setup['sep_page_names']."".$page_link;
	}
	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."".$date_folder."")), -4); 
	if($parent_permissions == "0755") {
		$perms = 0755;
		print "<li>A";
	} elseif($parent_permissions == "0777") {
		$perms = 0777;
		print "<li>B";
	} else {
			$perms = 0755;
		print "<li>C";
	}
	print "<li>$parent_permissions<li>$page_link<li>$perms<li>";

	mkdir("".$setup['path']."".$date_folder."/$page_link", $perms);
	chmod("".$setup['path']."".$date_folder."/$page_link", $perms);
	updateSQL("ms_blog_categories", "cat_folder='".$page_link."' WHERE cat_id='".$cat_id."' ");
	print "Create: ".$setup['path']."".$date_folder."/".$page_link."/index.php";


	$fp = fopen("".$setup['path']."".$date_folder."/".$page_link."/index.php", "w");
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




function createTagFolder($tag_id) {
	global $site_setup,$setup;
	$tag = doSQL("ms_tags", "*", "WHERE tag_id='".$tag_id."' ");

	$page_link = stripslashes(trim(strtolower($tag['tag_tag'])));
	$page_link = strip_tags($page_link);
	$page_link = str_replace(" ","".$site_setup['sep_page_names']."",$page_link);
	$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);

	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."".$setup['content_folder']."")), -4); 
	if($parent_permissions == "0755") {
		$perms = 0755;
		print "<li>A";
	} elseif($parent_permissions == "0777") {
		$perms = 0777;
		print "<li>B";
	} else {
			$perms = 0755;
		print "<li>C";
	}
	print "<li>$parent_permissions<li>$page_link<li>$perms<li>";
	$add_path .="../";

	if(!is_dir($setup['path']."".$setup['content_folder']."/".$setup['tags_folder']."")) { 
		mkdir("".$setup['path']."".$setup['content_folder']."/".$setup['tags_folder']."", $perms);
		chmod("".$setup['path']."".$setup['content_folder']."/".$setup['tags_folder']."", $perms);
		$fp = fopen("".$setup['path']."".$setup['content_folder']."/".$setup['tags_folder']."/index.php", "w");
		$info =  "<?php\n\$tag_home = true;  \n\$to_path = \"$add_path\";  \ninclude \"../".$setup['inc_folder']."/main_index_include.php\";\n?>"; 
		fputs($fp, "$info\n");
		fclose($fp);
	}


	$date_folder = $setup['content_folder']."/".$setup['tags_folder']."";

	if(file_exists($setup['path']."".$date_folder."/".$page_link)) {
		$page_link = $tag_id."".$site_setup['sep_page_names']."".$page_link;
	}

	mkdir("".$setup['path']."".$date_folder."/$page_link", $perms);
	chmod("".$setup['path']."".$date_folder."/$page_link", $perms);
	updateSQL("ms_tags", "tag_folder='".$page_link."' WHERE tag_id='".$tag_id."' ");
	print "Create: ".$setup['path']."".$date_folder."/".$page_link."/index.php";
//	copy("".$setup['path']."".$date_folder."/default.php", "".$setup['path']."".$date_folder."/".$page_link."/index.php");
	$add_path .="../";

	$fp = fopen("".$setup['path']."".$date_folder."/".$page_link."/index.php", "w");
	$info =  "<?php\n\$tag_id = $tag_id;  \n\$to_path = \"$add_path\"; \ninclude \"../../".$setup['inc_folder']."/main_index_include.php\";\n?>"; 
	fputs($fp, "$info\n");
	fclose($fp);

//	exit();

}

?>

<?php 
function productAdditionalCategories($req) {
	global $dbcon;
	$fn = "gal_under";
	$match = $_REQUEST['gal_under'];
	$html .=  "<select name=\"prod_add_cats[]\" multiple size=\"6\">";
	$html .=  "<option value=\"0\">None";

	$resultt = @mysqli_query($dbcon,"SELECT * FROM ".cat_table." WHERE cat_under='0' ORDER BY cat_name ASC");
	if (!$resultt) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error"); 	exit();	}
	while ( $type = mysqli_fetch_array($resultt) ) {
	if($type["cat_id"] == $match) { $selected = "selected"; }
	$html .=  "<option value=\"".$type["cat_id"]."\" id=\"subcatm-".$type['cat_id']."\" class=\"multioption\"  ";  if(countIt("".cat_connect_table."", "WHERE con_cat='".$type['cat_id']."' AND con_prod='".$_REQUEST[''.items_id.'']."' ") > 0) { $html .= "selected"; } if($_REQUEST[''.items_cat_field.''] == $type['cat_id']) { $html .= " style=\"font-weight: bold; display: none;\""; } else { $html .= " style=\"font-weight: bold; \"";  } $html .= ">".$type["cat_name"]."</option>";
	unset($selected);
		$parent_id = $type["cat_id"];
		$parent = $type['cat_name'];

			$html .= productAdditionalCategoriesSubs($fn, $match, $parent_id, $level, $sec_under,$parent,$req);
	}
	$html .=  "</select>";
	return $html;
}

function productAdditionalCategoriesSubs($fn, $match, $parent_id, $level, $sec_under,$parent,$req) {
	global $dbcon;
	$level++;
	$subs = @mysqli_query($dbcon,"SELECT *  FROM ".cat_table." WHERE cat_under='$parent_id' ORDER BY cat_name ASC");
	if (!$subs) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
	while($row = mysqli_fetch_array($subs)) {

		$sub_sec_id = $row["cat_id"];
		$sub_sec_name = $row["cat_name"];
		$sub_sec_folder = $row["cat_folder"];


		$html .= "<option  value=\"".$sub_sec_id."\" id=\"subcatm-".$sub_sec_id."\" class=\"multioption\" ";  if(countIt("".cat_connect_table."", "WHERE con_cat='".$sub_sec_id."' AND con_prod='".$_REQUEST[''.items_id.'']."' ") > 0) { $html .= "selected"; }  if($_REQUEST[''.items_cat_field.''] == $sub_sec_id) { $html .= "style=\"display: none;\" "; } $html .= ">"; 
  
		$dashes = 0;
		$html .=  "$parent ->  $sub_sec_name</option>"; 

		$sub2=@mysqli_query($dbcon,"SELECT COUNT(*) AS how_many FROM ".cat_table." WHERE cat_under='$sub_sec_id'");
		if (!$sub2) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
		$row = mysqli_fetch_array($sub2);
		$how_many= $row["how_many"];
		if(!empty($how_many)) { 
			$parent = $parent." -> ".$sub_sec_name;
			$parent_id = $sub_sec_id;
			$html .= productAdditionalCategoriesSubs($fn, $match, $parent_id, $level, $sec_under,$parent,$req);
		}
	}
		$level = 1;
		return $html;
}



?>

<script>
function selectCategory() { 
	var test = $('#date_cat option:selected').attr('type');
	if(test == "store") { 
		$(".storestuff").show();
		$("#prod_type").addClass("required");
	} else { 
		$(".storestuff").hide();
		$("#prod_type").removeClass("required");
	}

	if(test == "clientphotos") { 
		$("#clientgalleries").show();
	} else { 
		$("#clientgalleries").hide();
	}

//	alert(test);
	if($("#<?php print items_cat_field;?>").val() == '0') { 
		$("#addCats").hide();
		$("#newsubcat").hide();
		$("#date_cat_new").hide();
		$("#date_cat_new").val("");
		$("#newmaincat").show();
	} else { 
		$("#addCats").show();
		$("#newsubcat").show();
		$("#date_cat_new").show();
		$("#newmaincat").hide();

	$(".multioption").each(function(i){
		var this_id = this.id;
		$("#"+this_id).fadeIn('fast', function() {  });
			} );

		$("#subcatm-"+$("#<?php print items_cat_field;?>").val()).hide();
	}
}
</script>

<?php 
function multiLevelSelect($match) {
	global $dbcon;
	$fn = "gal_under";
//	$match = $_REQUEST['gal_under'];
	$html .=  "<select name=\"".items_cat_field."\" id=\"".items_cat_field."\"  onchange=\"selectCategory();\">";
	$html .=  "<option value=\"0\" tyle=\"standard\">Top Level Page";

	$resultt = @mysqli_query($dbcon,"SELECT * FROM ".cat_table." WHERE cat_under='0' AND cat_type!='registry' ORDER BY cat_name ASC");
	if (!$resultt) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error"); 	exit();	}
	while ( $type = mysqli_fetch_array($resultt) ) {
	if($type["cat_id"] == $match) { $selected = "selected"; }
	$html .=  "<option value=\"".$type["cat_id"]."\"  ";  if($type["cat_id"] == $match) { $html .= "selected"; } $html .= " style=\"font-weight: bold;\" type=\"".$type['cat_type']."\">".$type["cat_name"]."</option>";
	unset($selected);
		$parent_id = $type["cat_id"];
		$parent = $type['cat_name'];


			$html .= multiLevelSelectSubs($fn, $match, $parent_id, $level, $sec_under,$parent);
	}
	$html .=  "</select>";
	return $html;
}

function multiLevelSelectSubs($fn, $match, $parent_id, $level, $sec_under,$parent) {
	global $dbcon;
	$level++;
	$subs = @mysqli_query($dbcon,"SELECT *  FROM ".cat_table." WHERE cat_under='$parent_id'  AND cat_type!='registry'  ORDER BY cat_name ASC");
	if (!$subs) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
	while($row = mysqli_fetch_array($subs)) {

		$sub_sec_id = $row["cat_id"];
		$sub_sec_name = $row["cat_name"];
		$sub_sec_folder = $row["cat_folder"];


			$html .= "<option  value=\"".$sub_sec_id."\" ";  if($row["cat_id"] == $match) { $html .= "selected"; } $html .= "> "; 
  
		$html .=  "$parent -> $sub_sec_name </option>"; 

		$sub2=@mysqli_query($dbcon,"SELECT COUNT(*) AS how_many FROM ".cat_table." WHERE cat_under='$sub_sec_id'");
		if (!$sub2) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
		$row = mysqli_fetch_array($sub2);
		$how_many= $row["how_many"];
		if(!empty($how_many)) { 
			$parent = $parent." -> ".$sub_sec_name;
			$parent_id = $sub_sec_id;
			$html .= multiLevelSelectSubs($fn, $match, $parent_id, $level, $sec_under,$parent);
		}
	}
		$level = 1;
		return $html;
}



?>
