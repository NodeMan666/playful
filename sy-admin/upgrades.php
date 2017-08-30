<?php
ini_set('max_execution_time',8000);
set_time_limit(60);

$sytist_version = $site_setup['sytist_version'];
if($sytist_version <= "0.0.6") {
	$new_version = "0.0.7";
	$uq = "

	ALTER TABLE `ms_menu_links` ADD `link_shop_menu` VARCHAR( 20 ) NOT NULL ||

	INSERT INTO ms_menu_links SET link_text='Log In', link_main='login', link_status='1', link_location='shop', link_no_delete='1', link_shop_menu='accountmenu'||
	INSERT INTO ms_menu_links SET link_text='Create Account', link_main='newaccount', link_status='1', link_location='shop', link_no_delete='1', link_shop_menu='accountmenu'||
	INSERT INTO ms_menu_links SET link_text='Log Out', link_main='logout', link_status='1', link_location='shop', link_no_delete='1', link_shop_menu='accountmenu'||

	UPDATE ms_menu_links SET link_shop_menu='shopmenu' WHERE link_main='cart'||
	UPDATE ms_menu_links SET link_shop_menu='shopmenu' WHERE link_main='checkout'||
	UPDATE ms_menu_links SET link_shop_menu='accountmenu' WHERE link_main='myaccount'||

	ALTER TABLE `ms_store_settings` ADD `min_order_amount` DECIMAL( 10, 2 ) NOT NULL ||

	CREATE TABLE IF NOT EXISTS `ms_tax_zips` (
	  `id` int(11) NOT NULL auto_increment,
	  `zip` int(11) NOT NULL default '0',
	  `tax` decimal(10,4) NOT NULL default '0.0000',
	  `state` varchar(4) NOT NULL default '',
	  `city` varchar(100) NOT NULL default '',
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ||

	ALTER TABLE `ms_orders` ADD `order_vat` DECIMAL( 10, 2 ) NOT NULL ||
	ALTER TABLE `ms_orders` ADD `order_vat_percentage` DECIMAL( 10, 4 ) NOT NULL ||

	ALTER TABLE `ms_pending_orders` ADD `order_vat` DECIMAL( 10, 2 ) NOT NULL ||
	ALTER TABLE `ms_pending_orders` ADD `order_vat_percentage` DECIMAL( 10, 4 ) NOT NULL ||
	ALTER TABLE `ms_orders` ADD `order_admin_notes` TEXT NOT NULL ||

	ALTER TABLE `ms_orders` ADD `order_customer` INT NOT NULL ||
	ALTER TABLE `ms_pending_orders` ADD `order_customer` INT NOT NULL ||

	ALTER TABLE `ms_cart` ADD `cart_service` INT NOT NULL ||





	ALTER TABLE `ms_people` ADD `p_last_active_ip` VARCHAR( 30 ) NOT NULL ||

	ALTER TABLE `ms_people` ADD `p_deactivated` INT NOT NULL ||


	DROP TABLE IF EXISTS `ms_language`||
	CREATE TABLE IF NOT EXISTS `ms_language` (
	  `lang_id` int(11) NOT NULL auto_increment,
	  `lang_name` varchar(50) NOT NULL default '',
	  `lang_status` int(11) NOT NULL default '0',
	  `lang_default` int(11) NOT NULL default '0',
	  `_no_image_available_` varchar(255) NOT NULL default '',
	  `_captcha_background_color_` varchar(60) NOT NULL default '',
	  `_captcha_text_color_` varchar(60) NOT NULL default '',
	  `_date_` varchar(60) NOT NULL default '',
	  `_is_blank_` varchar(60) NOT NULL default '',
	  `_home_` varchar(60) NOT NULL default '',
	  `_nav_total_results_` varchar(60) NOT NULL default '',
	  `_nav_first_` varchar(60) NOT NULL default '',
	  `_nav_previous_` varchar(60) NOT NULL default '',
	  `_nav_next_` varchar(60) NOT NULL default '',
	  `_nav_last_` varchar(60) NOT NULL default '',
	  `_captcha_description_` varchar(255) NOT NULL default '',
	  `_captcha_not_correct_` varchar(60) NOT NULL default '',
	  `_indicates_required_field_` varchar(60) NOT NULL default '',
	  `_photos_of_` varchar(60) NOT NULL default '',
	  `_photos_word_photos_` varchar(60) NOT NULL default '',
	  `_continue_` varchar(50) NOT NULL default '',
	  `_leave_comment_title_` varchar(50) NOT NULL default '',
	  `_leave_comment_text_` text NOT NULL,
	  `_leave_comment_name_` varchar(50) NOT NULL default '',
	  `_leave_comment_email_` varchar(255) NOT NULL default '',
	  `_leave_comment_website_` varchar(30) NOT NULL default '',
	  `_leave_comment_comment_` varchar(50) NOT NULL default '',
	  `_leave_comment_button_` varchar(50) NOT NULL default '',
	  `_leave_comment_success_message_` text NOT NULL,
	  `_comments_` varchar(60) NOT NULL default '',
	  `_no_comments_` varchar(60) NOT NULL default '',
	  `_comment_said_` varchar(40) NOT NULL default '',
	  `_private_gallery_enter_password_` varchar(100) NOT NULL default '',
	  `_private_gallery_submit_password_` varchar(50) NOT NULL default '',
	  `_private_gallery_text_` text NOT NULL,
	  `_page_` varchar(30) NOT NULL default '',
	  `_of_` varchar(10) NOT NULL default '',
	  `_categories_` varchar(200) NOT NULL default '',
	  `_in_` varchar(50) NOT NULL default '',
	  `_comment_` varchar(100) NOT NULL default '',
	  `_uncategorized_` varchar(100) NOT NULL default '',
	  `_leave_comment_comments_` varchar(150) NOT NULL default '',
	  `_leave_comment_no_comments_` text NOT NULL,
	  `_leave_comment_approved_message_` text NOT NULL,
	  `_show_thumbnails_` varchar(100) NOT NULL default '',
	  `_close_thumbnails_` varchar(100) NOT NULL default '',
	  `_play_slideshow_` varchar(100) NOT NULL default '',
	  `_pause_slideshow_` varchar(100) NOT NULL default '',
	  `_older_post_` varchar(100) NOT NULL default '',
	  `_newer_post_` varchar(100) NOT NULL default '',
	  `_private_gallery_password_incorrect_` text NOT NULL,
	  `facebook_tab` varchar(255) NOT NULL default '',
	  `_tags_` varchar(150) NOT NULL default '',
	  PRIMARY KEY  (`lang_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ||

	INSERT INTO `ms_language` (`lang_id`, `lang_name`, `lang_status`, `lang_default`, `_no_image_available_`, `_captcha_background_color_`, `_captcha_text_color_`, `_date_`, `_is_blank_`, `_home_`, `_nav_total_results_`, `_nav_first_`, `_nav_previous_`, `_nav_next_`, `_nav_last_`, `_captcha_description_`, `_captcha_not_correct_`, `_indicates_required_field_`, `_photos_of_`, `_photos_word_photos_`, `_continue_`, `_leave_comment_title_`, `_leave_comment_text_`, `_leave_comment_name_`, `_leave_comment_email_`, `_leave_comment_website_`, `_leave_comment_comment_`, `_leave_comment_button_`, `_leave_comment_success_message_`, `_comments_`, `_no_comments_`, `_comment_said_`, `_private_gallery_enter_password_`, `_private_gallery_submit_password_`, `_private_gallery_text_`, `_page_`, `_of_`, `_categories_`, `_in_`, `_comment_`, `_uncategorized_`, `_leave_comment_comments_`, `_leave_comment_no_comments_`, `_leave_comment_approved_message_`, `_show_thumbnails_`, `_close_thumbnails_`, `_play_slideshow_`, `_pause_slideshow_`, `_older_post_`, `_newer_post_`, `_private_gallery_password_incorrect_`, `facebook_tab`, `_tags_`) VALUES
	(1, 'English', 1, 1, '<img src=\"/ms_graphics/no-image-100.jpg\" border=0 class=\"storeAlbumImageBorder\" width=\"100\" height=\"100\">', '#949494', '#242424', 'Date', 'is blank', 'Home', 'Total Results', 'First', 'Previous', 'Next', 'Last', 'Verification code: <br>Please enter the text you see below into the space beside it.', 'The verification code you entered did not match.', '* <i>indicates a required field</i>', 'of', 'photos', 'Continue', 'Leave A Comment', 'Fill out the form above to leave a comment.Your email address will not be posted and  is never shared!', 'Name', 'Email Address', 'Website address', 'Comment', 'Submit Comment', 'Thank you for leaving your comment. Your comment is currently awaiting approval  from  the administrator.', 'View / Make Comments', 'No comments', 'said:', 'Enter password to access this page', 'Submit', 'This page requires a password to view. Please enter the password above and submit the form.', 'Page', 'of', 'Categories', 'in', 'comment', 'Uncategorized', 'Comments', 'No one yet! Be the first and fill out the form to the left.', 'Thank you for leaving a comment! Your comment has been posted.', 'Show Thumbnails', 'Close Thumbnails', 'Play Slideshow', 'Pause Slideshow', 'older post &rarr;', '&larr; newer post', 'Password incorrect', 'F<br>A<br>C<br>E<br>B<br>O<br>O<br>K', 'Tags')||


	DROP TABLE IF EXISTS `ms_store_language`||
	CREATE TABLE IF NOT EXISTS `ms_store_language` (
	  `id` int(11) NOT NULL auto_increment,
	  `_cart_` varchar(50) NOT NULL default '',
	  `_add_to_cart_` varchar(50) NOT NULL default '',
	  `_added_to_cart_` varchar(50) NOT NULL default '',
	  `_view_cart_` varchar(50) NOT NULL default '',
	  `_in_my_cart_` varchar(50) NOT NULL default '',
	  `_remove_from_cart_` varchar(50) NOT NULL default '',
	  `_continue_shopping_` varchar(50) NOT NULL default '',
	  `_checkout_` varchar(50) NOT NULL default '',
	  `_items_` varchar(50) NOT NULL default '',
	  `_items_in_your_cart_` varchar(50) NOT NULL default '',
	  `_select_on_product_configurations_` varchar(50) NOT NULL default '',
	  `_qty_` varchar(50) NOT NULL default '',
	  `_on_` varchar(50) NOT NULL default '',
	  `_my_cart_` varchar(50) NOT NULL default '',
	  `_vat_` varchar(50) NOT NULL default '',
	  `_tax_` varchar(50) NOT NULL default '',
	  `_shipping_` varchar(50) NOT NULL default '',
	  `_total_` varchar(50) NOT NULL default '',
	  `_subtotal_` varchar(50) NOT NULL default '',
	  `_grand_total_` varchar(50) NOT NULL default '',
	  `_billing_address_` varchar(50) NOT NULL default '',
	  `_shipping_address_` varchar(50) NOT NULL default '',
	  `_checkout_progress_your_info_` varchar(50) NOT NULL default '',
	  `_checkout_progress_shipping_` varchar(50) NOT NULL default '',
	  `_checkout_progress_payment_` varchar(50) NOT NULL default '',
	  `_contact_` varchar(50) NOT NULL default '',
	  `_first_name_` varchar(50) NOT NULL default '',
	  `_last_name_` varchar(50) NOT NULL default '',
	  `_select_country_` varchar(50) NOT NULL default '',
	  `_order_order_number_` varchar(50) NOT NULL default '',
	  `_date_` varchar(50) NOT NULL default '',
	  `_email_address_` varchar(50) NOT NULL default '',
	  `_retype_email_address_` varchar(60) NOT NULL default '',
	  `_name_` varchar(50) NOT NULL default '',
	  `_phone_` varchar(50) NOT NULL default '',
	  `_address_` varchar(50) NOT NULL default '',
	  `_city_` varchar(50) NOT NULL default '',
	  `_state_` varchar(50) NOT NULL default '',
	  `_select_state_` varchar(50) NOT NULL default '',
	  `_country_` varchar(50) NOT NULL default '',
	  `_zip_` varchar(50) NOT NULL default '',
	  `_create_an_account_` varchar(50) NOT NULL default '',
	  `_create_an_account_message_` text NOT NULL,
	  `_create_account_no_account_checkbox_` text NOT NULL,
	  `_password_` varchar(50) NOT NULL default '',
	  `_re_type_password_` varchar(50) NOT NULL default '',
	  `_ship_to_mailing_address_` varchar(100) NOT NULL default '',
	  `_next_` varchar(50) NOT NULL default '',
	  `_back_` varchar(50) NOT NULL default '',
	  `_cancel_` varchar(50) NOT NULL default '',
	  `_shipped_` varchar(50) NOT NULL default '',
	  `_by_` varchar(50) NOT NULL default '',
	  `_product_` varchar(50) NOT NULL default '',
	  `_price_` varchar(50) NOT NULL default '',
	  `_extended_` varchar(50) NOT NULL default '',
	  `_discount_` varchar(50) NOT NULL default '',
	  `_store_cart_title_` varchar(50) NOT NULL default '',
	  `_checkout_now_` varchar(100) NOT NULL default '',
	  `_store_shopping_cart_empty_` text NOT NULL,
	  `_store_cart_text_` text NOT NULL,
	  `_store_cart_bottom_text_` text NOT NULL,
	  `_store_order_completed_text_` text NOT NULL,
	  `_store_order_shipping_` text NOT NULL,
	  `_store_order_download_` text NOT NULL,
	  `_store_order_service_` text NOT NULL,
	  `_store_continue_shopping_` varchar(100) NOT NULL default '',
	  `_store_order_completed_title_` varchar(255) NOT NULL default '',
	  `_store_order_pending_title_` varchar(100) NOT NULL default '',
	  `_store_order_pending_text_` text NOT NULL,
	  `_store_find_order_` varchar(100) NOT NULL default '',
	  `_store_find_order_text_` text NOT NULL,
	  `_store_order_can_not_find_` text NOT NULL,
	  `_store_find_order_number_` varchar(255) NOT NULL default '',
	  `_store_find_order_email_` varchar(255) NOT NULL default '',
	  `_store_find_order_zip_` varchar(255) NOT NULL default '',
	  `_store_find_order_button_` varchar(255) NOT NULL default '',
	  `_order_find_another_` varchar(255) NOT NULL default '',
	  `_store_order_total_` varchar(50) NOT NULL default '',
	  `_store_order_payment_type_` varchar(60) NOT NULL default '',
	  `_store_order_coupon_` varchar(60) NOT NULL default '',
	  `_store_order_payment_status_` varchar(60) NOT NULL default '',
	  `_store_order_pending_reason_` varchar(60) NOT NULL default '',
	  `_store_order_bottom_text_` varchar(60) NOT NULL default '',
	  `_store_download_link_` varchar(60) NOT NULL default '',
	  `_store_download_attempts_left_` varchar(60) NOT NULL default '',
	  `_checkout_how_would_you_like_to_pay_` varchar(255) NOT NULL default '',
	  `_checkout_order_total_` varchar(60) NOT NULL default '',
	  `_checkout_credit_cart_number_` varchar(60) NOT NULL default '',
	  `_checkout_card_type_` varchar(60) NOT NULL default '',
	  `_checkout_expiration_date_` varchar(60) NOT NULL default '',
	  `_checkout_cvv_` varchar(255) NOT NULL default '',
	  `_checkout_process_order_` varchar(80) NOT NULL default '',
	  `_checkout_card_declined_` text NOT NULL,
	  `_shopping_cart_link_` varchar(40) NOT NULL default '',
	  `_checkout_menu_link_` varchar(40) NOT NULL default '',
	  `_my_cart_empty_` varchar(60) NOT NULL default '',
	  `_enter_pomo_code_` text NOT NULL,
	  `_enter_pomo_code_text_` text NOT NULL,
	  `_promo_code_` varchar(40) NOT NULL default '',
	  `_promo_button_` varchar(30) NOT NULL default '',
	  `_promo_savings_` varchar(60) NOT NULL default '',
	  `_promo_code_invalid_` varchar(255) NOT NULL default '',
	  `_promo_code_good_` varchar(255) NOT NULL default '',
	  `_new_account_success_` varchar(150) NOT NULL default '',
	  `_new_account_success_message_` text NOT NULL,
	  `_new_account_page_title_` varchar(150) NOT NULL default '',
	  `_new_account_page_message_` text NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ||


	INSERT INTO `ms_store_language` (`id`, `_cart_`, `_add_to_cart_`, `_added_to_cart_`, `_view_cart_`, `_in_my_cart_`, `_remove_from_cart_`, `_continue_shopping_`, `_checkout_`, `_items_`, `_items_in_your_cart_`, `_select_on_product_configurations_`, `_qty_`, `_on_`, `_my_cart_`, `_vat_`, `_tax_`, `_shipping_`, `_total_`, `_subtotal_`, `_grand_total_`, `_billing_address_`, `_shipping_address_`, `_checkout_progress_your_info_`, `_checkout_progress_shipping_`, `_checkout_progress_payment_`, `_contact_`, `_first_name_`, `_last_name_`, `_select_country_`, `_order_order_number_`, `_date_`, `_email_address_`, `_retype_email_address_`, `_name_`, `_phone_`, `_address_`, `_city_`, `_state_`, `_select_state_`, `_country_`, `_zip_`, `_create_an_account_`, `_create_an_account_message_`, `_create_account_no_account_checkbox_`, `_password_`, `_re_type_password_`, `_ship_to_mailing_address_`, `_next_`, `_back_`, `_cancel_`, `_shipped_`, `_by_`, `_product_`, `_price_`, `_extended_`, `_discount_`, `_store_cart_title_`, `_checkout_now_`, `_store_shopping_cart_empty_`, `_store_cart_text_`, `_store_cart_bottom_text_`, `_store_order_completed_text_`, `_store_order_shipping_`, `_store_order_download_`, `_store_order_service_`, `_store_continue_shopping_`, `_store_order_completed_title_`, `_store_order_pending_title_`, `_store_order_pending_text_`, `_store_find_order_`, `_store_find_order_text_`, `_store_order_can_not_find_`, `_store_find_order_number_`, `_store_find_order_email_`, `_store_find_order_zip_`, `_store_find_order_button_`, `_order_find_another_`, `_store_order_total_`, `_store_order_payment_type_`, `_store_order_coupon_`, `_store_order_payment_status_`, `_store_order_pending_reason_`, `_store_order_bottom_text_`, `_store_download_link_`, `_store_download_attempts_left_`, `_checkout_how_would_you_like_to_pay_`, `_checkout_order_total_`, `_checkout_credit_cart_number_`, `_checkout_card_type_`, `_checkout_expiration_date_`, `_checkout_cvv_`, `_checkout_process_order_`, `_checkout_card_declined_`, `_shopping_cart_link_`, `_checkout_menu_link_`, `_my_cart_empty_`, `_enter_pomo_code_`, `_enter_pomo_code_text_`, `_promo_code_`, `_promo_button_`, `_promo_savings_`, `_promo_code_invalid_`, `_promo_code_good_`, `_new_account_success_`, `_new_account_success_message_`, `_new_account_page_title_`, `_new_account_page_message_`) VALUES
	(1, 'Cart', 'Add To Cart', 'Added To Your Cart', 'View  Cart', 'In My Cart', 'Remove', 'Continue Shopping', 'Checkout', 'Items', 'items in your cart', 'Select', 'Qty', 'on', 'My Cart', 'VAT', 'Tax', 'Shipping', 'Total', 'Subtotal', 'Grand Total', 'Billing Address', 'Shipping Address', 'Your Information', 'Shipping', 'Payment', 'Contact', 'First Name', 'Last Name', 'Select Country', 'Order #', 'Date', 'Email Address', 'Re-type Email Address', 'Name', 'Phone', 'Address', 'City', 'State', 'Select State', 'Country', 'Postal Code', 'Create an Account', 'You can create an account for faster future purchases and track orders. Enter  a password below to create an account. ', 'Check this box if you do not wish to create an account.', 'Password', 'Re-type Password', 'Ship to billing address', 'Next', 'Back', 'cancel', 'Shipped', 'by', 'Product', 'Price', 'Extended', 'Discount', 'Shopping Cart', 'Proceed to Checkout', 'Your shopping cart is empty', '', '', 'You have also been sent an email regarding your order. If you do not receive the email within 30 minutes please check your spam / bulk folders. If you still do not have it after an hour contact us to resend it to you.', 'Once we ship your items when will send you an email with tracking information.', 'To download your item(s), click the download link next to the item name.', '', 'Continue Shopping', 'Thank you for your purchase!', 'Pending Order', 'Your order below is currently pending. Most likely the payment was an eCheck and just waiting for it to clear. Once it has, you will receive an email about it and a link to download your items. ', 'Find My Order', 'Fill in the fields below to find your order. All fields are required. ', 'Sorry, can not find an order with that information', 'Your Order Number', 'Your Email Address', 'Your Postal Code', 'Find Order', 'Find another order', 'Total', 'Payment Type', 'Coupon', 'Payment Status', 'Pending Reason', '', 'Download', 'attempts left:', 'How would you like to pay?', 'Order Total', 'Credit Card Number', 'Card Type', 'Expiration Date', 'Card Security (CVV) Code', 'Process Order', 'I''m sorry, but your transaction was not successful . You can try again below. You can also pay with credit card with the PayPal option and do not need a PayPal account. Response: ', 'My Cart', 'Checkout', 'Cart Empty', 'Coupon Code', 'If you have a coupon code, enter that code below.', 'Coupon Code', 'Redeem', 'with a savings of', 'Sorry, that coupon code is invalid.', 'Your discount has been applied to your shopping cart.', 'Success!', 'Thank you for registering with us! You can visit your account by clicking <a href=\"/index.php?view=account\">My Account</a>. <br><br><a href=\"/\">Click here to return to the home page</a>', 'Create Account', 'Fill  out the form below to create a new account.')||


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}

if($sytist_version <= "0.0.7") {
	$new_version = "0.0.8";
	$uq = "

	INSERT INTO ms_payment_options SET pay_name='PayPal Express Checkout', pay_descr='PayPal Express Checkout adds a button on the view cart page to go directly to PayPal to start the checkout process. When the customer returns from PayPal, they will complete their transaction.', pay_option='paypalexpress', pay_dev_status='1' , pay_url='https://www.paypal.com/webapps/mpp/express-checkout'; 

	ALTER TABLE `ms_payment_options` ADD `pay_express_download_address` INT NOT NULL ;

	ALTER TABLE `ms_store_language` ADD `_your_transaction_is_complete_` TEXT NOT NULL ,
	ADD `_pp_express_place_your_order_description_` TEXT NOT NULL ,
	ADD `_pp_express_place_your_order_` VARCHAR( 200 ) NOT NULL ,
	ADD `_pp_express_items_in_cart_` VARCHAR( 150 ) NOT NULL ,
	ADD `_pp_express_create_account_` VARCHAR( 150 ) NOT NULL ,
	ADD `_pp_express_create_account_description_` TEXT NOT NULL ,
	ADD `_pp_express_log_in_` VARCHAR( 200 ) NOT NULL ;

	UPDATE ms_store_language SET _your_transaction_is_complete_='Your transaction is complete',
	_pp_express_place_your_order_description_='Click the place order button below to complete this purchase.',
	_pp_express_place_your_order_='Place Your Order',
	_pp_express_items_in_cart_='Items In Your Cart',
	_pp_express_create_account_='Would you like to create an account?',
	_pp_express_create_account_description_='If you would like to create an account to be able to log in to track orders and more, enter in a password to use and click the create account button.',
	_pp_express_log_in_='Log into an existing account' ;


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}

if($sytist_version <= "0.0.8") {
	$new_version = "0.0.9";
	$uq = "

	CREATE TABLE IF NOT EXISTS `ms_side_menu` (
	  `side_id` int(11) NOT NULL auto_increment,
	  `side_feature` varchar(50) NOT NULL default '',
	  `side_cat` int(11) NOT NULL default '0',
	  `side_order` int(11) NOT NULL default '0',
	  `side_html` text NOT NULL,
	  `side_limit` int(11) NOT NULL default '0',
	  `side_minis` int(11) NOT NULL default '0',
	  `side_label` varchar(255) NOT NULL default '',
	  `side_text` text NOT NULL,
	  `side_show_date` int(11) NOT NULL default '0',
	  `side_show_time` int(11) NOT NULL default '0',
	  `side_fb_width` int(11) NOT NULL default '0',
	  `side_fb_color` varchar(20) NOT NULL default '',
	  `side_fb_faces` int(11) NOT NULL default '0',
	  `side_fb_header` int(11) NOT NULL default '0',
	  `side_fb_stream` int(11) NOT NULL default '0',
	  `side_fb_link` varchar(255) NOT NULL default '',
	  `side_include` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`side_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

	ALTER TABLE `ms_fb` ADD `facebook_link` VARCHAR( 255 ) NOT NULL ;
	ALTER TABLE `ms_fb` ADD `fb_show_faces` VARCHAR( 10 ) NOT NULL ,
	ADD `fb_stream` VARCHAR( 10 ) NOT NULL ,
	ADD `fb_header` VARCHAR( 10 ) NOT NULL ;

	ALTER TABLE `ms_menu_links` ADD `link_login_page` INT NOT NULL ;


	TRUNCATE TABLE `ms_social_links`;

	INSERT INTO `ms_social_links` (`link_id`, `link_text`, `link_url`, `link_img`, `link_status`, `link_order`) VALUES
	(1, 'MySpace', '', '/sy-graphics/socialicons/my_space.png', 0, 3),
	(2, 'FaceBoook', '', '/sy-graphics/socialicons/facebook.png', 0, 4),
	(3, 'Flickr', '', '/sy-graphics/socialicons/flickr.png', 0, 1),
	(4, 'Twitter', '', '/sy-graphics/socialicons/twitter.png', 0, 2),
	(5, 'Yahoo!', '', '/sy-graphics/socialicons/yahoo.png', 0, 5),
	(6, 'YouTube', '', '/sy-graphics/socialicons/you_tube.png', 0, 6),
	(7, 'Picasa', '', '/sy-graphics/socialicons/picasa.png', 0, 7),
	(10, 'Pinterest', '', '/sy-graphics/socialicons/pinterest.png', 0, 1),
	(11, 'RSS Feed', '/feed/', '/sy-graphics/socialicons/rss.png', 0, 0);

	ALTER TABLE `ms_css2` ADD `side_menu_font_size` INT NOT NULL ,
	ADD `side_menu_label_size` INT NOT NULL ;



	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}



if($sytist_version <= "0.0.9") {
	$new_version = "0.1.0";
	$uq = "

	CREATE TABLE IF NOT EXISTS `ms_cart_options` (
	  `co_id` int(11) NOT NULL auto_increment,
	  `co_opt_id` int(11) NOT NULL default '0',
	  `co_opt_name` varchar(255) NOT NULL default '',
	  `co_select_id` int(11) NOT NULL default '0',
	  `co_select_name` varchar(255) NOT NULL default '',
	  `co_price` decimal(10,2) NOT NULL default '0.00',
	  `co_cart_id` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`co_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=283 ;


	CREATE TABLE IF NOT EXISTS `ms_color_options` (
	  `color_id` int(11) NOT NULL auto_increment,
	  `color_name` varchar(50) NOT NULL default '',
	  `color_r` int(11) NOT NULL default '0',
	  `color_g` int(11) NOT NULL default '0',
	  `color_b` int(11) NOT NULL default '0',
	  `color_opc` int(11) NOT NULL default '0',
	  `color_order` int(11) NOT NULL default '0',
	  `color_cost` decimal(10,2) NOT NULL default '0.00',
	  `color_bw` int(11) NOT NULL default '0',
	  `color_pg` int(11) NOT NULL default '0',
	  `color_status` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`color_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

	INSERT INTO `ms_color_options` (`color_id`, `color_name`, `color_r`, `color_g`, `color_b`, `color_opc`, `color_order`, `color_cost`, `color_bw`, `color_pg`, `color_status`) VALUES
	(1, 'Black & White', 0, 0, 0, 100, 1, 0.00, 1, 0, 1),
	(2, 'Sepia Tone', 255, 160, 100, 90, 2, 0.00, 0, 0, 0),
	(3, 'Selenium', 50, 50, 200, 90, 3, 0.00, 0, 0, 0);



	CREATE TABLE IF NOT EXISTS `ms_favs` (
	  `fav_id` int(11) NOT NULL auto_increment,
	  `fav_pic` int(11) NOT NULL default '0',
	  `fav_date_id` int(11) NOT NULL default '0',
	  `fav_person` int(11) NOT NULL default '0',
	  `fav_date_time` datetime NOT NULL default '0000-00-00 00:00:00',
	  `fav_comment` text NOT NULL,
	  PRIMARY KEY  (`fav_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;



	CREATE TABLE IF NOT EXISTS `ms_free_downloads` (
	  `free_id` int(11) NOT NULL auto_increment,
	  `free_pic` int(11) NOT NULL default '0',
	  `free_date` datetime NOT NULL default '0000-00-00 00:00:00',
	  `free_date_id` int(11) NOT NULL default '0',
	  `free_person` int(11) NOT NULL default '0',
	  `free_ip` varchar(30) NOT NULL default '',
	  `free_prod` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`free_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;



	CREATE TABLE IF NOT EXISTS `ms_photo_products` (
	  `pp_id` int(11) NOT NULL auto_increment,
	  `pp_name` varchar(255) NOT NULL default '',
	  `pp_descr` text NOT NULL,
	  `pp_width` decimal(10,2) NOT NULL default '0.00',
	  `pp_height` decimal(10,2) NOT NULL default '0.00',
	  `pp_cost` decimal(10,2) NOT NULL default '0.00',
	  `pp_price` decimal(10,2) NOT NULL default '0.00',
	  `pp_download_dem` int(11) NOT NULL default '0',
	  `pp_taxable` int(11) NOT NULL default '0',
	  `pp_type` varchar(20) NOT NULL default '',
	  `pp_free` int(11) NOT NULL default '0',
	  `pp_free_watermark` int(11) NOT NULL default '0',
	  `pp_free_logo` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`pp_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;



	INSERT INTO `ms_photo_products` (`pp_id`, `pp_name`, `pp_descr`, `pp_width`, `pp_height`, `pp_cost`, `pp_price`, `pp_download_dem`, `pp_taxable`, `pp_type`, `pp_free`, `pp_free_watermark`, `pp_free_logo`) VALUES
	(1, '4x6', '', 4.00, 6.00, 0.30, 4.00, 0, 1, 'print', 0, 0, 0),
	(2, 'Small Download', '', 0.00, 0.00, 0.00, 5.00, 800, 0, 'download', 0, 0, 0),
	(8, '13x9', '', 13.00, 9.00, 4.00, 30.00, 0, 1, 'print', 0, 0, 0),
	(4, '8x10', '', 10.00, 8.00, 3.00, 15.00, 0, 1, 'print', 0, 0, 0),
	(6, 'Large Download', '', 0.00, 0.00, 0.00, 20.00, 0, 0, 'download', 0, 0, 0),
	(7, '5x7', '', 5.00, 7.00, 2.00, 5.00, 0, 1, 'print', 0, 0, 0),
	(9, 'Set of 8 wallets', '', 1.50, 2.00, 1.50, 10.00, 0, 1, 'print', 0, 0, 0),
	(10, '11x14', '', 11.00, 14.00, 6.00, 40.00, 0, 1, 'print', 0, 0, 0),
	(14, 'Medium Download', '', 0.00, 0.00, 0.00, 10.00, 1200, 0, 'download', 0, 0, 0),
	(34, 'Free Download', '', 0.00, 0.00, 0.00, 0.00, 800, 0, 'download', 1, 1, 1);



	CREATE TABLE IF NOT EXISTS `ms_photo_products_connect` (
	  `pc_id` int(11) NOT NULL auto_increment,
	  `pc_list` int(11) NOT NULL default '0',
	  `pc_prod` int(11) NOT NULL default '0',
	  `pc_order` int(11) NOT NULL default '0',
	  `pc_group` int(11) NOT NULL default '0',
	  `pc_price` decimal(10,2) NOT NULL default '0.00',
	  PRIMARY KEY  (`pc_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;



	INSERT INTO `ms_photo_products_connect` (`pc_id`, `pc_list`, `pc_prod`, `pc_order`, `pc_group`, `pc_price`) VALUES
	(1, 1, 1, 1, 2, 0.00),
	(102, 1, 7, 2, 2, 0.00),
	(23, 1, 8, 6, 2, 0.00),
	(6, 1, 10, 5, 2, 0.00),
	(37, 1, 9, 4, 2, 0.00),
	(38, 1, 4, 3, 2, 0.00),
	(99, 1, 14, 0, 4, 0.00),
	(62, 1, 2, 3, 4, 0.00),
	(63, 1, 5, 0, 4, 0.00),
	(65, 1, 6, 2, 4, 0.00);



	CREATE TABLE IF NOT EXISTS `ms_photo_products_groups` (
	  `group_id` int(11) NOT NULL auto_increment,
	  `group_name` varchar(255) NOT NULL default '',
	  `group_list` int(11) NOT NULL default '0',
	  `group_descr` text NOT NULL,
	  `group_order` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`group_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;


	INSERT INTO `ms_photo_products_groups` (`group_id`, `group_name`, `group_list`, `group_descr`, `group_order`) VALUES
	(2, 'Prints', 1, '', 1),
	(4, 'Downloads', 1, '', 2);


	CREATE TABLE IF NOT EXISTS `ms_photo_products_lists` (
	  `list_id` int(11) NOT NULL auto_increment,
	  `list_name` varchar(255) NOT NULL default '',
	  `list_default` int(11) NOT NULL default '0',
	  `list_filters` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`list_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;


	INSERT INTO `ms_photo_products_lists` (`list_id`, `list_name`, `list_default`, `list_filters`) VALUES
	(1, 'Sample Price List', 1, 1);



	CREATE TABLE IF NOT EXISTS `ms_sub_galleries` (
	  `sub_id` int(11) NOT NULL auto_increment,
	  `sub_date_id` int(11) NOT NULL default '0',
	  `sub_name` varchar(255) NOT NULL default '',
	  `sub_descr` text NOT NULL,
	  `sub_order` int(11) NOT NULL default '0',
	  `sub_under` int(11) NOT NULL default '0',
	  `sub_link` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`sub_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;




	ALTER TABLE `ms_photos` ADD `pic_key` VARCHAR( 255 ) NOT NULL ;
	ALTER TABLE `ms_cart` ADD `cart_photo_prod` INT NOT NULL ,
	ADD `cart_pic_id` INT NOT NULL ;
	ALTER TABLE `ms_cart` ADD `cart_pic_date_id` INT NOT NULL ;

	ALTER TABLE `ms_cart` ADD `cart_pic_org` VARCHAR( 255 ) NOT NULL ,
	ADD `cart_pic_date_org` VARCHAR( 255 ) NOT NULL ;

	ALTER TABLE `ms_cart` ADD `cart_cost` DECIMAL( 10, 2 ) NOT NULL ;

	ALTER TABLE `ms_product_options` ADD `opt_date` INT NOT NULL ,
	ADD `opt_photo_prod` INT NOT NULL ;

	ALTER TABLE `ms_product_options` ADD `opt_price` DECIMAL( 10, 2 ) NOT NULL ;

	ALTER TABLE `ms_product_options` ADD `opt_text_field_size` INT NOT NULL ;
	ALTER TABLE `ms_product_options` ADD `opt_price_checked` DECIMAL( 10, 2 ) NOT NULL ;

	ALTER TABLE `ms_product_options` ADD `opt_order` INT NOT NULL ;

	ALTER TABLE `ms_calendar` ADD `date_photo_price_list` INT NOT NULL ;

	ALTER TABLE `ms_cart` ADD `cart_color_id` INT NOT NULL ;

	ALTER TABLE `ms_cart` ADD `cart_color_name` VARCHAR( 30 ) NOT NULL ;

	ALTER TABLE `ms_cart` ADD `cart_crop_x1` DECIMAL( 10, 2 ) NOT NULL ,
	ADD `cart_crop_y1` DECIMAL( 10, 2 ) NOT NULL ,
	ADD `cart_crop_x2` DECIMAL( 10, 2 ) NOT NULL ,
	ADD `cart_crop_y2` DECIMAL( 10, 2 ) NOT NULL ;

	ALTER TABLE `ms_cart` ADD `cart_crop_rotate` INT NOT NULL ;

	ALTER TABLE `ms_defaults` ADD `social_share` INT NOT NULL ;

	ALTER TABLE `ms_calendar` ADD `photo_social_share` INT NOT NULL ;

	ALTER TABLE `ms_css2` ADD `page_icon_color` VARCHAR( 10 ) NOT NULL ,
	ADD `back_icon_color` VARCHAR( 10 ) NOT NULL ;

	ALTER TABLE `ms_calendar` ADD `jthumb_height` INT NOT NULL ,
	ADD `jthumb_margin` INT NOT NULL ;

	ALTER TABLE `ms_defaults` ADD `jthumb_height` INT NOT NULL ,
	ADD `jthumb_margin` INT NOT NULL ;

	ALTER TABLE `ms_blog_photos` ADD `bp_sub` INT NOT NULL ;

	ALTER TABLE `ms_cart` ADD `cart_sub_gal_id` INT NOT NULL ;

	ALTER TABLE `ms_product_options` ADD `opt_label` VARCHAR( 200 ) NOT NULL ;

	INSERT INTO ms_menu_links SET link_text='Favorites', link_main='favorites', link_status='1', link_location='shop', link_no_delete='1', link_shop_menu='accountmenu';

	ALTER TABLE `ms_calendar` ADD `date_expire` DATE NOT NULL ;

	ALTER TABLE `ms_blog_categories` ADD `cat_expire_days` INT NOT NULL ;



	ALTER TABLE `ms_store_language` ADD `_welcome_top_` VARCHAR( 200 ) NOT NULL ,
	ADD `_expired_message_` TEXT NOT NULL ,
	ADD `_expires_` VARCHAR( 50 ) NOT NULL,
	ADD `_has_expired_` VARCHAR( 120 ) NOT NULL ;

	UPDATE ms_store_language SET _welcome_top_='Welcome',
	_expired_message_='Sorry, but this page has expired. If you have any questions please contact us.',
	_expires_='Expires',
	_has_expired_='Has Expired';

	ALTER TABLE `ms_store_language` ADD `_buy_photo_` VARCHAR( 100 ) NOT NULL ,
	ADD `_favorite_`  VARCHAR( 100 ) NOT NULL ,
	ADD `_my_favorites_` VARCHAR( 100 ) NOT NULL,
	ADD `_log_in_` VARCHAR( 100 ) NOT NULL,
	ADD `_create_account_` VARCHAR( 100 ) NOT NULL,
	ADD `_share_` VARCHAR( 50 ) NOT NULL,
	ADD `_filter_` VARCHAR( 50 ) NOT NULL,
	ADD `_original_` VARCHAR( 50 ) NOT NULL,
	ADD `_to_add_to_favorites_` VARCHAR( 120 ) NOT NULL ;



	UPDATE ms_store_language SET _buy_photo_='Buy Photo',
	_favorite_='Favorite',
	_my_favorites_='My Favorites',
	_create_account_='create an account',
	_share_='Share',
	_filter_='Filter',
	_original_='Original',
	_to_add_to_favorites_='To add to favorites: ',
	_log_in_='Log In';


	ALTER TABLE `ms_store_language` 
	ADD `_crop_cancel_` VARCHAR( 60 ) NOT NULL ,
	ADD `_crop_rotate_`  VARCHAR( 60 ) NOT NULL ,
	ADD `_adjust_crop_`  VARCHAR( 60 ) NOT NULL ,
	ADD `_crop_save_`  VARCHAR( 60 ) NOT NULL ;

	UPDATE ms_store_language SET _crop_cancel_='Cancel', _crop_rotate_='Rotate', _crop_save_='Save', _adjust_crop_='Adjust Crop'; 

	ALTER TABLE `ms_store_language`  ADD `_free_download_` VARCHAR( 80 ) NOT NULL ;
	UPDATE ms_store_language SET _free_download_='Download' ; 

	UPDATE ms_store_language SET _store_find_order_text_='If you have an account, <a href=/index.php?view=account>Click here to log in</a>, otherwise fill in the fields below to find your order. All fields are required. ';


	INSERT INTO `ms_defaults` (`def_id`, `def_type`, `blog_contain`, `blog_seconds`, `blog_enlarge`, `blog_kill_side_menu`, `blog_progress_bar`, `blog_next_prev`, `blog_play_pause`, `blog_slideshow`, `blog_slideshow_auto_start`, `disable_controls`, `caption_location`, `contain_width`, `contain_height`, `noupsize`, `blog_frame`, `disable_thumbnails`, `disable_help`, `disable_animation_bar`, `disable_photo_count`, `disable_photo_slider`, `date_type`, `transition_time`, `blog_photo_file`, `bg_use`, `bg_color`, `disable_play_pause`, `disable_next_previous`, `blog_location`, `thumb_type`, `thumb_style`, `thumb_width`, `def_cat_id`, `max_photo_display_width`, `slideshow_fixed_height`, `thumb_scroller_open`, `social_share`, `jthumb_height`, `jthumb_margin`) VALUES
	(9, 'gallery', 0, 4, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 400, 'pic_large', 0, '', 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 1, 250, 4);


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}


if($sytist_version <= "0.1.0") {
	$new_version = "0.1.1";
	$uq = "
	DROP TABLE IF EXISTS `ms_emails`||
	CREATE TABLE IF NOT EXISTS `ms_emails` (
	  `email_id` int(11) NOT NULL auto_increment,
	  `email_name` varchar(255) NOT NULL default '',
	  `email_descr` text NOT NULL,
	  `email_codes` text NOT NULL,
	  `email_from_email` varchar(255) NOT NULL default '',
	  `email_from_name` varchar(255) NOT NULL default '',
	  `email_subject` varchar(255) NOT NULL default '',
	  `email_message` text NOT NULL,
	  `email_type` int(1) NOT NULL default '0',
	  `email_no_delete` int(11) NOT NULL default '0',
	  `email_download_descr` text NOT NULL,
	  `email_shipping_descr` text NOT NULL,
	  PRIMARY KEY  (`email_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ||

	INSERT INTO `ms_emails` (`email_id`, `email_name`, `email_descr`, `email_codes`, `email_from_email`, `email_from_name`, `email_subject`, `email_message`, `email_type`, `email_no_delete`, `email_download_descr`, `email_shipping_descr`) VALUES
	(3, 'Email Customer', 'This is the default email when you email a customer from this administration area.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s first name\r\n[URL] = The URL to your store\r\n[WEBSITE_NAME] = The name of your website', '', '', '[FIRST_NAME] , Message from [WEBSITE_NAME]', '\r\n<p>Hello [FIRST_NAME] [LAST_NAME],</p>\r\n\r\n<p>Thank you,</p>\r\n<p>[WEBSITE_NAME]</p>\r\n<p>[URL]</p>', 0, 1, '', ''),
	(6, 'Order Email', 'This is the automated email sent to the customer after their payment has been processed.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s last name\r\n[EMAIL_ADDRESS] = Customer''s email address\r\n[ADDRESS] = Address\r\n[CITY] = City\r\n[STATE] = State\r\n[ZIP] = Postal code\r\n[SHIP_FIRST_NAME]  = Ship to first name\r\n[SHIP_LAST_NAME] = Ship to last name\r\n[SHIP_ADDRESS] = Ship to address\r\n[SHIP_CITY]  = Ship to city\r\n[SHIP_STATE] = Ship to state\r\n[SHIP_ZIP] = Ship to postal code\r\n[ORDER_NUMBER] = The order / invoice number\r\n[ORDER_TOTAL] = The order total\r\n[TOTAL_ITEMS] = Number of items on the order\r\n[ORDER_ITEMS] = Lists the items on the order\r\n[ORDER_DATE] = Current date\r\n[ORDER_LINK] = Link to order\r\n[URL] = Link to your website\r\n[WEBSITE_NAME] = The name of your website\r\n[DOWNLOAD_DESCRIPTION] = If there are download items on the order, this will be replaced with the download description. \r\n[SHIPPING_DESCRIPTION]  = If there are items to be shipping on the order, this will be replaced with shipping description.', '', '', 'Your Purchase at [WEBSITE_NAME] - #[ORDER_NUMBER]', '\r\n<p>\r\n	<p><span style=\"font-size: 14pt;\">Hello [FIRST_NAME] [LAST_NAME]</span>,</p>\r\n	\r\n	<p>Thank you for your purchase at&nbsp; [WEBSITE_NAME]!</p>\r\n	\r\n	<p><span style=\"font-size: 10pt;\">Your Order # [ORDER_NUMBER]</span></p>\r\n	<p>\r\n		<p>Date: [ORDER_DATE]</p>\r\n		<p>Email address: [EMAIL_ADDRESS]</p>\r\n		\r\n		<p>Click the following link to view your order online:</p>\r\n		<p>[ORDER_LINK] </p>\r\n		\r\n		<p>[DOWNLOAD_DESCRIPTION]</p>\r\n		<p>[SHIPPING_DESCRIPTION] </p>\r\n		<p><br />\r\n			</p></p>\r\n	<p>\r\n		<table cellpadding=\"8\" style=\"border-collapse:collapse;width:100%;\">\r\n			<tbody>\r\n				<tr>\r\n					<td style=\"vertical-align: top; border-color: rgb(199, 199, 199); border-width: 1px; border-style: solid; background-color: rgb(242, 242, 242);\">\r\n						<p><span style=\"font-size: 12pt; font-weight: bold;\">BILLING</span></p>\r\n						<p>[FIRST_NAME] [LAST_NAME]</p>\r\n						<p>[ADDRESS]</p>\r\n						<p>[CITY], [STATE] [ZIP]</p>\r\n						</td>\r\n					<td style=\"vertical-align: top; border-color: rgb(199, 199, 199); border-width: 1px; border-style: solid; background-color: rgb(242, 242, 242);\" id=\"shippingaddress\">\r\n						<p id=\"ship1\"><span style=\"font-size: 12pt; font-weight: bold;\">SHIPPING</span></p>\r\n						<p id=\"ship2\">[SHIP_FIRST_NAME] [SHIP_LAST_NAME]<br />\r\n							</p>\r\n						<p id=\"ship3\">[SHIP_ADDRESS]</p>\r\n						<p id=\"ship4\">[SHIP_CITY] [SHIP_STATE], [SHIP_ZIP]</p></td>\r\n				</tr>\r\n			</tbody>\r\n		</table></p>\r\n	[ORDER_ITEMS]\r\n	\r\n	<p>Thank you for your business! <br />\r\n		</p>\r\n	<p><span style=\"font-size: 12pt;\">[WEBSITE_NAME]</span></p>\r\n	<p>[URL]</p>\r\n	&nbsp;</p>', 0, 1, 'To download your item(s), view the order on the website with the link above and click the download link next to the item(s).', 'Once your order has been processed and shipped, we will send you an email with the shipping details.'),
	(7, 'Order PENDING Email', 'This is the automated email sent to the customer if their order is pending usually from choosing a pay offline option.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s last name\r\n[EMAIL_ADDRESS] = Customer''s email address\r\n[ADDRESS] = Address\r\n[CITY] = City\r\n[STATE] = State\r\n[ZIP] = Postal code\r\n[SHIP_FIRST_NAME]  = Ship to first name\r\n[SHIP_LAST_NAME] = Ship to last name\r\n[SHIP_ADDRESS] = Ship to address\r\n[SHIP_CITY]  = Ship to city\r\n[SHIP_STATE] = Ship to state\r\n[SHIP_ZIP] = Ship to postal code\r\n[ORDER_NUMBER] = The order / invoice number\r\n[ORDER_TOTAL] = The order total\r\n[TOTAL_ITEMS] = Number of items on the order\r\n[ORDER_ITEMS] = Lists the items on the order\r\n[ORDER_DATE] = Current date\r\n[URL] = Link to your website\r\n[WEBSITE_NAME] = The name of your website', '', '', 'Your Pending Order At [WEBSITE_NAME]', '<span style=\"font-size: 14pt;\">Hello [FIRST_NAME] [LAST_NAME]</span>\r\n\r\n<p>Thank you for your purchase from [WEBSITE_NAME]! Currently you order is <span style=\"font-weight: bold;\">pending</span>. Once it the order status changes, you will receive an email \r\nnotification and if completed, your order will be processed.</p>\r\n\r\n<p><span style=\"font-size: 10pt;\">Your Order # [ORDER_NUMBER]</span></p>\r\n<p>\r\n	<p>Date: [ORDER_DATE]</p>\r\n	<p>Email address: [EMAIL_ADDRESS]</p>\r\n	<p><br />\r\n		</p></p>\r\n<p>\r\n	<table cellpadding=\"8\" style=\"border-collapse:collapse;width:100%;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"vertical-align: top; border-color: rgb(199, 199, 199); border-width: 1px; border-style: solid; background-color: rgb(242, 242, 242);\">\r\n					<p><span style=\"font-size: 12pt; font-weight: bold;\">BILLING</span></p>\r\n					<p>[FIRST_NAME] [LAST_NAME]</p>\r\n					<p>[ADDRESS]</p>\r\n					<p>[CITY], [STATE] [ZIP]</p>\r\n					</td>\r\n				<td id=\"shippingaddress\" style=\"vertical-align: top; border-color: rgb(199, 199, 199); border-width: 1px; border-style: solid; background-color: rgb(242, 242, 242);\">\r\n					<p id=\"ship1\"><span style=\"font-size: 12pt; font-weight: bold;\">SHIPPING</span></p>\r\n					<p id=\"ship2\">[SHIP_FIRST_NAME] [SHIP_LAST_NAME]<br />\r\n						</p>\r\n					<p id=\"ship3\">[SHIP_ADDRESS]</p>\r\n					<p id=\"ship4\">[SHIP_CITY] [SHIP_STATE], [SHIP_ZIP]</p></td>\r\n			</tr>\r\n		</tbody>\r\n	</table></p>\r\n[ORDER_ITEMS]\r\n\r\n<p>Thank you for your business! <br />\r\n	</p>\r\n<p><span style=\"font-size: 12pt;\">[WEBSITE_NAME]</span></p>\r\n<p>[URL]</p>\r\n&nbsp;\r\n', 0, 1, '', ''),
	(19, 'Forgot Password', 'This is the email sent to a customer that uses the forgot password form and sends them a new password.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s last name\r\n[EMAIL_ADDRESS] = Customer''s email address\r\n[PASSWORD] = New password\r\n[URL] = Link to your website\r\n[WEBSITE_NAME] = The name of your website ', '', '', 'Your new password at [WEBSITE_NAME]', '[URL]<br />\r\n&nbsp;<br />\r\nHello [FIRST_NAME],<br />\r\n&nbsp;<br />\r\nYou&nbsp; have requested to have your password reset at [WEBSITE_NAME].&nbsp; Here is your new log information.<br />\r\n&nbsp;<br />\r\nEmail : [EMAIL_ADDRESS]<br />\r\nPassword: [PASSWORD]<br />\r\n&nbsp;<br />\r\nOnce you log in you should change your password to something you can remember. You can do this in your My Account section.<br />\r\n&nbsp;<br />\r\nThank you for visiting!<br />\r\n&nbsp;[WEBSITE_NAME]<br />\r\n[URL]', 0, 1, '', ''),
	(18, 'Order Shipped Email', 'The default email sent to customer when you add shipping to an order. ', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s last name\r\n[EMAIL_ADDRESS] = Customer''s email address\r\n[ADDRESS] = Address\r\n[CITY] = City\r\n[STATE] = State\r\n[ZIP] = Postal code\r\n[SHIP_FIRST_NAME]  = Ship to first name\r\n[SHIP_LAST_NAME] = Ship to last name\r\n[SHIP_ADDRESS] = Ship to address\r\n[SHIP_CITY]  = Ship to city\r\n[SHIP_STATE] = Ship to state\r\n[SHIP_ZIP] = Ship to postal code\r\n[ORDER_NUMBER] = The order / invoice number\r\n[ORDER_TOTAL] = The order total\r\n[TOTAL_ITEMS] = Number of items on the order\r\n[ORDER_ITEMS] = Lists the items on the order\r\n[ORDER_DATE] = Current date\r\n[URL] = Link to your website\r\n[WEBSITE_NAME] = The name of your website\r\n[SHIPPING_DATE] - Date shipped\r\n[SHIPPING_METHOD] = How it was shipping\r\n[TRACKING_INFORMATION] = Link to track package', '', '', '[WEBSITE_NAME] - Your Order Has Shipped! #[ORDER_NUMBER]', '\r\n<p><span style=\"font-size: 14pt;\">Hello [FIRST_NAME] [LAST_NAME]</span>,</p>\r\n\r\n<p>Thank you for your purchase at&nbsp; [WEBSITE_NAME]! This email is to let you know your order is on its way. </p>\r\n\r\n<p>Shipping date: [SHIPPING_DATE]</p>\r\n<p>Shipping Method: [SHIPPING_METHOD]</p>\r\n<p>[TRACKING_INFORMATION] <br />\r\n	</p>\r\n\r\n<p>Order Number: [ORDER_NUMBER]</p>\r\n<p>\r\n	<p>Date: [ORDER_DATE]</p>\r\n	<p>Email address: [EMAIL_ADDRESS]</p></p>\r\n\r\n<p>\r\n	<table cellpadding=\"8\" style=\"border-collapse:collapse;width:100%;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"vertical-align: top; border-color: rgb(199, 199, 199); border-width: 1px; border-style: solid; background-color: rgb(242, 242, 242);\">\r\n					<p><span style=\"font-size: 12pt; font-weight: bold;\">BILLING</span></p>\r\n					<p>[FIRST_NAME] [LAST_NAME]</p>\r\n					<p>[ADDRESS]</p>\r\n					<p>[CITY], [STATE] [ZIP]</p>\r\n					</td>\r\n				<td style=\"vertical-align: top; border-color: rgb(199, 199, 199); border-width: 1px; border-style: solid; background-color: rgb(242, 242, 242);\" id=\"shippingaddress\">\r\n					<p id=\"ship1\"><span style=\"font-size: 12pt; font-weight: bold;\">SHIPPING</span></p>\r\n					<p id=\"ship2\">[SHIP_FIRST_NAME] [SHIP_LAST_NAME]<br />\r\n						 </p>\r\n					<p id=\"ship3\">[SHIP_ADDRESS]</p>\r\n					<p id=\"ship4\">[SHIP_CITY] [SHIP_STATE], [SHIP_ZIP]</p></td>\r\n			</tr>\r\n		</tbody>\r\n	</table><br />\r\n	</p>\r\n<p>&nbsp;[ORDER_ITEMS]</p>\r\n\r\n<p>Thank you for your business! <br />\r\n	</p>\r\n<p><span style=\"font-size: 12pt;\">[WEBSITE_NAME]</span></p>\r\n<p>[URL]</p>\r\n\r\n\r\n', 0, 1, '', ''),
	(20, 'Invite to view password protected page', 'This is the default email when sending someone a link and password to a password protected page.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s first name\r\n[URL] = The URL to your store\r\n[WEBSITE_NAME] = The name of your website\r\n[LINK] = Link to the page\r\n[PAGE_TITLE] = Page title\r\n[PASSWORD] = Password to the page.', '', '', 'Invitation to view a page at [WEBSITE_NAME]', '<p>Hello,</p>\r\n\r\n<p>Here is the link to the password protected page [PAGE_TITLE] I would like you to view:</p>\r\n\r\n<p>[LINK]</p>\r\n\r\n<p>Password: [PASSWORD]</p>\r\nThank you,<br />\r\n\r\n<p>\r\n	<p><span style=\"font-size: 12pt;\">[WEBSITE_NAME]</span></p>\r\n	<p>[URL]</p>\r\n	</p>', 0, 1, '', '')||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}
	$sytist_version = $new_version;
}

if($sytist_version <= "0.1.1") {
	$new_version = "0.1.2";
	$uq = "

	ALTER TABLE `ms_calendar` ADD `thumb_scroller` INT NOT NULL ,
	ADD `allow_favs` INT NOT NULL ;

	ALTER TABLE `ms_language` ADD `_full_screen_` VARCHAR( 150 ) NOT NULL ;
	UPDATE ms_language SET _full_screen_='Full Screen';

	ALTER TABLE `ms_defaults` ADD `thumb_scroller` INT NOT NULL ,
	ADD `allow_favs` INT NOT NULL ;

	ALTER TABLE `ms_store_language`  ADD `_view_crop_` VARCHAR( 80 ) NOT NULL ;
	UPDATE ms_store_language SET _view_crop_='View Crop' ; 

	INSERT INTO `ms_watermarking` (`wm_thumbs`, `wm_thumbs_location`, `wm_images`, `wm_images_location`, `wm_add_logo`, `wm_add_logo_location`, `wm_images_file`, `wm_thumbs_file`, `wm_logo_file`, `wm_zoom`) VALUES
	(0, '', 0, 'center', 0, 'bright', 'sy-photos/watermarks/copyrighted-photo-do-not-duplicate-center.png', '', '', 0);


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;


}

if($sytist_version <= "0.1.2") {
	$new_version = "0.1.3";
	$uq = "

	ALTER TABLE `ms_css2` ADD `h1_upper` INT NOT NULL ;
	ALTER TABLE `ms_google_fonts` ADD `theme` INT NOT NULL ;

	ALTER TABLE `ms_language`  ADD `_page_expires_on_` TEXT NOT NULL ;
	UPDATE ms_language SET _page_expires_on_='Gallery expires on' ; 

	ALTER TABLE `ms_language`  ADD `_loading_more_` TEXT NOT NULL ;
	UPDATE ms_language SET _loading_more_='Loading More Photos' ; 

	ALTER TABLE `ms_photo_products_lists` ADD `list_show_crop` INT NOT NULL ;

	ALTER TABLE `ms_store_language`  ADD `_center_crop_preview_` TEXT NOT NULL, ADD `_crop_add_to_cart_`  TEXT NOT NULL;
	UPDATE ms_store_language SET _center_crop_preview_='Center Crop Preview', _crop_add_to_cart_='You can adjust the crop once you add to cart.' ; 

	ALTER TABLE `ms_blog_categories` ADD `cat_show_title` INT NOT NULL ;
	UPDATE ms_blog_categories SET cat_show_title='1' ;

	UPDATE ms_calendar SET disable_controls='0' WHERE page_home='1' ;

	ALTER TABLE `ms_calendar` ADD `feature_row_1` VARCHAR( 255 ) NOT NULL ,
	ADD `feature_row_2` VARCHAR( 255 ) NOT NULL ,
	ADD `feature_row_3` VARCHAR( 255 ) NOT NULL ;

	ALTER TABLE `ms_calendar` ADD `date_feature_title` VARCHAR( 255 ) NOT NULL ,
	ADD `date_feature_text` TEXT NOT NULL ;


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "0.1.3") {
	$new_version = "0.1.4";
	$uq = "

	TRUNCATE TABLE `ms_category_layouts`||

	INSERT INTO `ms_category_layouts` (`layout_id`, `layout_name`, `layout_html`, `layout_description`, `layout_css_id`, `layout_js_function`, `layout_width`, `layout_height`, `layout_photo_class`, `layout_photo_size`, `layout_photo_width`, `layout_photo_height`, `layout_spacing`, `layout_per_page`, `layout_preview_text_length`, `layout_type`, `layout_file`, `layout_default`, `layout_css`, `layout_no_delete`) VALUES
	(1, 'Standard Layout', '<div class=\"preview\">\r\n		<div class=\"image\">[IMAGE]</div>\r\n		<div class=\"headline\">[TITLE]</div>\r\n		<div class=\"sub\">\r\n			<span class=\"newsDate\"><span class=\"theDate\">[DATE]</span> <span class=\"theTime\">[TIME]</span></span>\r\n			<span class=\"category\">[CATEGORIES]</span>\r\n		</div>\r\n		<div class=\"previewtext\">[PREVIEW_TEXT]<br></div>\r\n		<div class=\"comments\">[COMMENTS]</div>\r\n	<div class=\"cssClear\"></div>\r\n</div>', 'Standard listing of pages down the page.', 'previews', '', 0, 0, 'thumbnail', 'pic_pic', 0, 0, 0, 0, 500, 'listing', '', 0, '', 1),
	(3, 'On Photo', '<div class=\"preview\">\r\n[IMAGE]\r\n<div class=\"text\">\r\n<div class=\"headline\">[TITLE]</div>\r\n<div class=\"previewtext\">[EXPIRE_DATE]</div>\r\n<div class=\"previewtext\">[PREVIEW_TEXT]</div>\r\n</div>\r\n</div>', 'This layout shows the titles on over the photo.', 'onphotoPreviews', '', 0, 0, 'onphotophoto', 'pic_pic', 0, 0, 0, 0, 100, 'listing', '', 0, '', 1),
	(4, 'Gallery Thumbnails', '<div class=\"preview\">\r\n		<div class=\"thumbimage\">[IMAGE]</div>\r\n		<div class=\"text\">\r\n                 <div>[TITLE]</div>\r\n		<div>[EXPIRE_DATE]</div>\r\n                </div>	\r\n</div>', 'Fixed thumbnail container sizes with thumbnails displayed and title underneath. ', 'thumbnaillisting', '', 0, 0, 'thumbnail', 'pic_th', 0, 0, 0, 0, 0, 'listing', '', 0, '', 1),
	(9, 'Standard Page', '', 'A standard page designed to show 1 photo aligned to the left or right with social share options (if enabled).', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'standard-page.php', 1, '#standardPage {  }\r\n#standardPage .title { padding: 4px; } \r\n#standardPage .text { padding: 4px; } \r\n#standardPage .expire { padding: 4px; } \r\n#standardPage .photos { padding: 4px; float: left; margin: 0 10px 10px 0;  } \r\n\r\n', 1),
	(10, 'Blog Pages', '', 'Displays pages with category link at top, showing date page posted, photos, share options (if enabled), comments options, tags and next & previous links at the bottom.', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'blog-pages.php', 0, '#blogPage { } \r\n#blogPage .title { padding: 4px; } \r\n#blogPage .categories { padding: 4px; } \r\n#blogPage .date { padding: 4px; } \r\n#blogPage .comments { padding: 4px; } \r\n#blogPage .text { padding: 4px; } \r\n#blogPage .photos { } \r\n#blogPage .tags { padding: 4px; } \r\n#blogPage .nextPrevious { padding: 4px; } \r\n', 1),
	(11, 'Store Pages', '', 'Pages are displayed with the page photo aligned to the left of the page with the product description to the right with the add to cart options.', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'store-pages.php', 0, '#storePage {}\r\n#storePage .categories { padding: 4px; }\r\n#storePage .title { padding: 4px; }\r\n#storePage .text { padding: 4px; }\r\n#storePage .nextPrevious { padding: 4px; }\r\n#storePage .photos {  float: left; width: 50%;  }\r\n#storePage .photos .inner { padding: 16px; } \r\n#storePage .content { float: right; width: 50%;  }\r\n#storePage .content .inner { padding: 16px; } \r\n', 1),
	(22, 'Client Galleries', '', 'Displays pages with expiration date (if it has one), photos, social share options (if enabled) and page comments. ', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'client-galleries.php', 0, '#clientGalleries { } \r\n#clientGalleries .title { padding: 4px; } \r\n#clientGalleries .categories { padding: 4px; } \r\n#clientGalleries .expire { padding: 4px; } \r\n#clientGalleries .comments { padding: 4px; } \r\n#clientGalleries .text { padding: 4px; } \r\n#clientGalleries .photos { } \r\n#clientGalleries .nextPrevious { padding: 4px; } ', 0),
	(12, 'Home Page', '', 'Designated for the home page including photos & home page features that are enabled. ', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'home-page.php', 0, '#homePage {  }\r\n#homePage .title { padding: 4px; } \r\n#homePage .text { padding: 4px; } \r\n#homePage .photos { padding: 4px; } \r\n#homePage .subpages { padding: 4px; } \r\n', 1),
	(18, 'Product list with add to cart', '<div class=\"preview\">\r\n<div class=\"right textright\">\r\n		<div class=\"pc large1\">[PRICE]</div>\r\n		<div class=\"pc\">[ADD_TO_CART]</div>\r\n</div>		\r\n\r\n<div class=\"image\">[IMAGE]</div>\r\n		<div class=\"headline\">[TITLE_ONLY]</div>\r\n\r\n		<div class=\"previewtext\">[PREVIEW_TEXT]<br></div>\r\n\r\n	<div class=\"cssClear\"></div>\r\n</div>', 'This lists the pages in the category with an add to cart link, but no link to the actual page. This would be like a buy page. ', 'previews', '', 0, 0, 'thumbnail', 'pic_pic', 0, 0, 0, 0, 500, 'listing', '', 0, '', 0),
	(19, 'Gallery Thumbnails With Prices', '<div class=\"preview\">\r\n		<div class=\"thumbimage\">[IMAGE]</div>\r\n		<div class=\"text\">\r\n                 <div>[TITLE]</div>\r\n		<div>[PRICE]</div>\r\n                </div>	\r\n</div>', 'Fixed thumbnail container sizes with thumbnails displayed and title underneath. ', 'thumbnaillisting', '', 0, 0, 'thumbnail', 'pic_th', 0, 0, 0, 0, 0, 'listing', '', 0, '', 0),
	(21, 'Blog Layout - 1 Photo', '<div class=\"preview\">\r\n		<div class=\"headline\"><h2>[TITLE]</h2></div>\r\n<div class=\"pc\">[TOTAL_PHOTOS]</div>\r\n		<div class=\"sub\">\r\n\r\n			<span class=\"newsDate\"><span class=\"theDate\">[DATE]</span> <span class=\"theTime\">[TIME]</span></span>\r\n			<span class=\"category\">[CATEGORIES]</span>\r\n		</div>\r\n		<div class=\"previewtext\">[PREVIEW_TEXT]<br></div>\r\n		<div class=\"photos\">[FIRST_PHOTO]</div>\r\n\r\n	<div class=\"cssClear\"></div>\r\n</div>\r\n<div>&nbsp;</div>', 'Standard listing of pages down the page.', 'bloglayout', '', 0, 0, 'thumbnail', 'pic_large', 0, 0, 0, 0, 0, 'listing', '', 0, '', 0),
	(23, 'Galleries', '', 'Displays pages  photos, social share options (if enabled) and page comments. ', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'galleries.php', 0, '#galleries { } \r\n#galleries .title { padding: 4px; } \r\n#galleries .categories { padding: 4px; } \r\n#galleries .comments { padding: 4px; } \r\n#galleries .text { padding: 4px; } \r\n#galleries .photos { } \r\n#galleries .nextPrevious { padding: 4px; } \r\n', 0)||


	ALTER TABLE `ms_calendar` ADD `photo_align` VARCHAR( 10 ) NOT NULL ||


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;


}

if($sytist_version <= "0.1.4") {
	$new_version = "0.1.5";
	$uq = "

	ALTER TABLE `ms_cart` ADD `cart_download_log` TEXT NOT NULL ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}

if($sytist_version <= "0.1.5") {
	$new_version = "0.1.6";
	$uq = "

	ALTER TABLE `ms_blog_categories` ADD `cat_order_by` VARCHAR( 10 ) NOT NULL ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "0.1.6") {
	$new_version = "0.1.7";
	$uq = "
	CREATE TABLE IF NOT EXISTS `ms_packages` (
	  `package_id` int(11) NOT NULL auto_increment,
	  `package_name` varchar(255) NOT NULL default '',
	  `package_descr` text NOT NULL,
	  `package_price` decimal(10,2) NOT NULL default '0.00',
	  `package_taxable` int(11) NOT NULL default '0',
	  `package_select_only` int(11) NOT NULL default '0',
	  `package_select_amount` int(11) NOT NULL default '0',
	  `package_require_all` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`package_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

	CREATE TABLE IF NOT EXISTS `ms_packages_connect` (
	  `con_id` int(11) NOT NULL auto_increment,
	  `con_package` int(11) NOT NULL default '0',
	  `con_product` int(11) NOT NULL default '0',
	  `con_qty` int(11) NOT NULL default '0',
	  `con_order` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`con_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;


	ALTER TABLE `ms_photo_products_connect` ADD `pc_package` INT NOT NULL ;

	ALTER TABLE `ms_cart` ADD `cart_package` INT NOT NULL ,
	ADD `cart_package_photo` INT NOT NULL ;

	ALTER TABLE `ms_store_language`  ADD `_no_photo_selected_` TEXT NOT NULL;
	UPDATE ms_store_language SET _no_photo_selected_='No photo selected for this product.' ; 

	ALTER TABLE `ms_store_language`  ADD `_add_to_package_` TEXT NOT NULL;
	UPDATE ms_store_language SET _add_to_package_='Add Photo' ; 

	ALTER TABLE `ms_store_language`  ADD `_remove_photo_from_package_` TEXT NOT NULL;
	UPDATE ms_store_language SET _remove_photo_from_package_='Remove Photo' ; 

	ALTER TABLE `ms_store_language`  ADD `_package_complete_` TEXT NOT NULL,  ADD `_package_instructions_` TEXT NOT NULL,   ADD `_close_package_window_` TEXT NOT NULL;
	UPDATE ms_store_language SET _package_complete_='Your collection is complete', _package_instructions_='<h3>Click the Add Photo link next to the product you want to add this photo to. <br><br>Your selected photos for this collection will display here.</h3>', _close_package_window_='Close Window'; 

	ALTER TABLE `ms_store_language`  ADD `_cart_selected_package_photos_` TEXT NOT NULL;
	UPDATE ms_store_language SET _cart_selected_package_photos_='Below are the photos selected for the above collection' ; 

	ALTER TABLE `ms_store_language`  ADD `_add_photo_to_package_` TEXT NOT NULL;
	UPDATE ms_store_language SET _add_photo_to_package_='Add Photo To Collection' ; 

	ALTER TABLE `ms_photo_products` ADD `pp_internal_name` VARCHAR( 255 ) NOT NULL ;

	ALTER TABLE `ms_cart` ADD `cart_sku` VARCHAR( 255 ) NOT NULL ;

	ALTER TABLE `ms_photo_products_groups` ADD `group_package` INT NOT NULL ;

	ALTER TABLE `ms_store_language`  ADD `_buy_packages_` TEXT NOT NULL;
	UPDATE ms_store_language SET _buy_packages_='Collections' ; 

	ALTER TABLE `ms_store_language`  ADD `_package_added_instructions_` TEXT NOT NULL;
	UPDATE ms_store_language SET _package_added_instructions_='To add photos to your collection, click the Add Photo To Collection link while viewing your photos. ' ; 

	ALTER TABLE `ms_blog_photos` ADD `bp_package` INT NOT NULL ;

	ALTER TABLE `ms_store_language`  ADD `_package_includes_` TEXT NOT NULL;
	UPDATE ms_store_language SET _package_includes_='Collection includes ' ; 

	ALTER TABLE `ms_photo_products_groups` ADD `group_require_purchase` INT NOT NULL ;

	ALTER TABLE `ms_cart` ADD `cart_group_id` INT NOT NULL ;

	ALTER TABLE `ms_photo_products_groups` ADD `group_require_message` TEXT NOT NULL ;

	ALTER TABLE `ms_store_language` 
	ADD `_remove_required_package_`  TEXT NOT NULL ,
	ADD `_remove_package_confirm_`  TEXT NOT NULL ,
	ADD `_each_` VARCHAR( 50 ) NOT NULL,
	ADD `_yes_` VARCHAR( 50 ) NOT NULL,
	ADD `_no_` VARCHAR( 50 ) NOT NULL;

	UPDATE ms_store_language SET _remove_from_cart_='Remove', _remove_required_package_='This is a required purchase before purchasing other products and you have  additional product(s) in your cart. Removing this will also remove those other product(s).<br><br> Continue? ',_remove_package_confirm_='Are you sure you want to remove this collection and all the photos selected for it?', _each_='each', _yes_='Yes', _no_='No'; 

	ALTER TABLE `ms_packages` ADD `package_select_only` INT NOT NULL ,
	ADD `package_select_amount` INT NOT NULL ;

	ALTER TABLE `ms_store_language` 
	ADD `_photo_selected_` VARCHAR( 100 ) NOT NULL ,
	ADD `_selected_` VARCHAR( 50 ) NOT NULL;

	UPDATE ms_store_language SET _photo_selected_='(photo selected)', _selected_='selected'; 

	ALTER TABLE `ms_packages` ADD `package_require_all` INT NOT NULL ;

	ALTER TABLE `ms_store_language`  ADD `_checkout_stop_title_` TEXT NOT NULL, ADD `_checkout_stop_package_incomplete_` TEXT NOT NULL;
	UPDATE ms_store_language SET _checkout_stop_title_='Oops! Hold on',  _checkout_stop_package_incomplete_='You have a collection in your shopping cart you have not completed. Please select a photo for all products in your collection.'; 



	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}



if($sytist_version <= "0.1.7") {
	$new_version = "0.1.8";
	$uq = "
	CREATE TABLE IF NOT EXISTS `ms_my_pages` (
	  `mp_id` int(11) NOT NULL auto_increment,
	  `mp_date_id` int(11) NOT NULL default '0',
	  `mp_people_id` int(11) NOT NULL default '0',
	  `mp_date` date NOT NULL default '0000-00-00',
	  PRIMARY KEY  (`mp_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;


	ALTER TABLE `ms_watermarking` ADD `wm_def_wm` INT NOT NULL ,
	ADD `wm_def_logo` INT NOT NULL ;

	ALTER TABLE `ms_css2` ADD `sm_pin_top` INT NOT NULL ;

	ALTER TABLE `ms_cart` CHANGE `cart_client` `cart_client` VARCHAR( 255 ) NOT NULL;

	ALTER TABLE `ms_store_language` ADD `_item_` VARCHAR( 30 ) NOT NULL ;
	UPDATE ms_store_language SET _item_='item';

	ALTER TABLE `ms_packages` ADD `package_ship` INT NOT NULL ;

	ALTER TABLE `ms_store_language` ADD `_buy_packages_thumbnail_page_` TEXT NOT NULL ;
	UPDATE ms_store_language SET _buy_packages_thumbnail_page_='View available photo collections';

	INSERT INTO ms_menu_links SET link_text='Find My Photos', link_main='findphotos', link_status='0', link_location='shop', link_no_delete='1', link_shop_menu='accountmenu';


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;


}


if($sytist_version <= "0.1.8") {
	$new_version = "0.1.9";
	$uq = "
		ALTER TABLE `ms_blog_categories` ADD `cat_private_button` INT NOT NULL ,
		ADD `cat_private_page` INT NOT NULL ;

		ALTER TABLE `ms_language` ADD `_access_private_photos_button_` VARCHAR( 80 ) NOT NULL, ADD `_access_private_photos_title_` VARCHAR( 150 ) NOT NULL, ADD `_access_private_photos_text_` TEXT NOT NULL, ADD `_access_private_photos_password_` VARCHAR( 150 ) NOT NULL, ADD `_access_private_photos_submit_` VARCHAR( 80 ) NOT NULL, ADD `_access_private_photos_not_found_` VARCHAR( 180 ) NOT NULL, ADD `_my_photos_` VARCHAR( 100 ) NOT NULL ;

		UPDATE ms_language SET _access_private_photos_button_='Access Private Photos', _access_private_photos_title_='Find My Photos', _access_private_photos_text_='If you have been given a password to access your private photos, enter that password below. ', _access_private_photos_password_='Password', _access_private_photos_submit_='Enter', _access_private_photos_not_found_='Gallery not found', _my_photos_='My Photos' ;

		ALTER TABLE `ms_favs` ADD `fav_sub_id` INT NOT NULL ;

		ALTER TABLE `ms_payment_options` ADD `pay_offline_descr` TEXT NOT NULL ;

		ALTER TABLE `ms_orders` ADD `order_payment` DECIMAL( 10, 2 ) NOT NULL ;

		ALTER TABLE `ms_orders` ADD `order_offline` INT NOT NULL ;

		ALTER TABLE `ms_emails` ADD `email_paypal_pending` TEXT NOT NULL ,
		ADD `email_offline_pending` TEXT NOT NULL ;

		ALTER TABLE `ms_orders` ADD `order_payment_date` DATE NOT NULL ;

		ALTER TABLE `ms_orders` ADD `order_payment_reference` VARCHAR( 200 ) NOT NULL ;

		ALTER TABLE `ms_orders` ADD `order_payment_info` TEXT NOT NULL ;

		ALTER TABLE `ms_store_settings` ADD `terms_conditions_link` TEXT NOT NULL ;
		UPDATE ms_store_settings SET terms_conditions_link='I agree to the terms & conditions';

		UPDATE ms_store_language SET _checkout_card_declined_='Sorry, but your transaction was not successful. Try again if you believe this was in error. If you continue to have problems, please contact use with the following response: Response: ';

		ALTER TABLE `ms_payment_options` ADD `pay_paypal_exp_button` VARCHAR( 150 ) NOT NULL ;

		UPDATE ms_emails SET email_message='<span style=\"font-size: 14pt;\">Hello [FIRST_NAME] [LAST_NAME]</span>
		
		<p>Thank you for your purchase from [WEBSITE_NAME]!&nbsp;</p>
		
		<p>[ORDER_PENDING_MESSAGE] <br />
			</p>
		
		<p><span style=\"font-size: 10pt;\">Your Order # [ORDER_NUMBER]</span></p>
		<p>
			<p>Date: [ORDER_DATE]</p>
			<p>Email address: [EMAIL_ADDRESS]</p>
			<p><br />
				</p></p>
		<p>
			<table cellpadding=\"8\" style=\"border-collapse:collapse;width:100%;\">
				<tbody>
					<tr>
						<td style=\"vertical-align: top; border-color: rgb(199, 199, 199); border-width: 1px; border-style: solid; background-color: rgb(242, 242, 242);\">
							<p><span style=\"font-size: 12pt; font-weight: bold;\">BILLING</span></p>
							<p>[FIRST_NAME] [LAST_NAME]</p>
							<p>[ADDRESS]</p>
							<p>[CITY], [STATE] [ZIP]</p>
							</td>
						<td id=\"shippingaddress\" style=\"vertical-align: top; border-color: rgb(199, 199, 199); border-width: 1px; border-style: solid; background-color: rgb(242, 242, 242);\">
							<p id=\"ship1\"><span style=\"font-size: 12pt; font-weight: bold;\">SHIPPING</span></p>
							<p id=\"ship2\">[SHIP_FIRST_NAME] [SHIP_LAST_NAME]<br />
								</p>
							<p id=\"ship3\">[SHIP_ADDRESS]</p>
							<p id=\"ship4\">[SHIP_CITY] [SHIP_STATE], [SHIP_ZIP]</p></td>
					</tr>
				</tbody>
			</table></p>
		[ORDER_ITEMS]
		
		<p>Thank you for your business! <br />
			</p>
		<p><span style=\"font-size: 12pt;\">[WEBSITE_NAME]</span></p>
		<p>[URL]</p>
		&nbsp;
		' WHERE email_id='7' ;



		TRUNCATE TABLE `ms_payment_options`;

		INSERT INTO `ms_payment_options` (`pay_id`, `pay_name`, `pay_num`, `pay_key`, `pay_email`, `pay_descr`, `pay_text`, `pay_button`, `pay_status`, `pay_cards`, `pay_option`, `pay_page_title`, `pay_order`, `pay_title`, `pay_description`, `pay_ssl`, `pay_url`, `paypal_curl`, `pay_emulator`, `test_mode`, `pay_short_description`, `pay_full_page`, `pay_dev_status`, `pay_currency`, `pay_select_graphic`, `pay_form`, `pay_express_download_address`, `pay_offline_descr`, `pay_paypal_exp_button`) VALUES
		(8, 'SecurePay', '', '', '', 'Visit SecurePay at <a href=\"http://www.securepay.com\" target=\"_blank\">securepay.com</a> for more information.\r\n\r\nTo use this option, you will need to have a Security Certificate (SSL) on your website.\r\n\r\nIf you have a SecurePay account, enter your merchant ID below and change status to \"Active\".   \r\n(test account number: 41502)', 'Click here to pay', '', 0, 'Visa\r\nMasterCard\r\nAmerican Express\r\nDiscover', 'securepay', '', 8, 'Securepay', 'Pay online now with Visa, Master Card, Discover or American express.', 1, 'http://www.securepay.com', 0, '', 0, '', 0, 0, '', '', '', 0, '', ''),
		(9, 'USA ePay', '', '', '', 'Visit USA ePay at <a href=\"http://www.usaepay.com\" target=\"_blank\">usaepay.com</a> for more information.\r\n\r\nTo use this option, you will need to have a Security Certificate (SSL) on your website.\r\n\r\nIf you have an USA ePay account, enter your transaction key below and change status to \"Active\".\r\n\r\n(test key: yCaWGYQsSVR0S48B6AKMK07RQhaxHvGu  | payment must be 1.00)', 'Click here to pay', '', 0, 'Visa\r\nMasterCard\r\nAmerican Express\r\nDiscover', 'usaepay', '', 7, 'Pay With Credit Card', 'Pay online now with Visa, Master Card, Discover or American express.', 1, 'http://www.usaepay.com', 0, '', 0, '', 0, 0, '', '', '', 0, '', ''),
		(3, 'Authorize.net (SIM)', '', '', '', 'Visit Authorize.net at <a href=\"http://www.authorize.net/\" target=\"_blank\">authorize.net</a> for more information.\r\n\r\nThis is using their SIM (Server Integration Method) and you do not need to have a Security Certificate(SSL) installed on your website for this option. When the customer goes to pay, they will be taken to a secure page on the authorize.net site to securely enter in their payment information.\r\n\r\nIf you have an authorize.net account, enter your login id & transaction key below and change status to \"Active\".', 'Pay Now', '', 0, '', 'authorizenetsim', '', 2, 'Pay With Credit Card', 'Pay online now with Visa, Master Card, Discover or American express.', 0, 'http://www.authorize.net/', 0, '', 1, '', 0, 1, '', 'sy-misc/creditcards/visamcdiscoveramex.jpg', 'authorizenetsim', 0, '', ''),
		(4, 'eProcessingNetwork', '', '', '', 'Visit eProcessingNetwork at <a href=\"http://www.eprocessingnetwork.com/\" target=\"_blank\">eprocessingnetwork.com</a> for more information.\r\n\r\nYou do not need to have a Security Certificate (SSL) for this option.', 'Click here to pay', '', 0, '', 'eprocessingnetwork', '', 5, 'Pay With Credit Card', 'Pay online now with Visa, Master Card, Discover or American express.', 0, 'http://www.eprocessingnetwork.com/', 0, '', 0, '', 0, 0, '', '', '', 0, '', ''),
		(5, 'Collect & Manually Process', '', '', '', 'This option allows you to collect credit card information so you can manually process the card. The card information is split up and part of it is emailed to you and the other part is stored in the database with the order. It is split up like that so it is not stored in tact in one location.\r\n\r\nExample, a customer enters in their card information and submits it.\r\n\r\nYou will get an email with half the card information like this: 41102234XXXXXXXX\r\nWhen you view the order that was paid in the admin, you will see the rest of the information and the other half of the credit card like this:  XXXXXXXX33445566\r\n\r\nThen you can take to two halves to manually process. You will need an SSL / Security Certificate installed on your website to use this option.\r\n\r\nIf using this option, the order will be marked as pending until you manually run the card and then update the payment information for the order.', 'Process Order', '', 0, 'Visa\r\nMasterCard\r\nDiscover\r\nAmerican Express', 'emailform', '', 4, 'Pay With Credit Card now', 'Pay online now with Visa, Master Card, Discover or American express.', 1, '', 0, '', 0, 'Collect credit card information to manually process.', 0, 1, '', 'sy-misc/creditcards/visamcdiscoveramex.jpg', 'manualcard', 0, 'Thank you for your purchase. Your order is currently pending approval. You will be notified when the transaction is complete. ', ''),
		(11, 'eWay (AU)', '', '', '', 'eWay is an Australia processing company. Visit eWay at <a href=\"http://www.eway.com.au/\" target=\"_blank\">eway.com.au</a> for more information.\r\n\r\nYou do not need to have a Security Certificate (SSL) for this option.', 'Pay Now', '', 0, '', 'eway', '', 5, 'Pay With eWay', 'Pay online now with with eWay', 0, 'http://www.eway.com.au/', 0, '', 0, '', 0, 0, '', '', '', 0, '', ''),
		(17, 'PayFast', '', '', '', 'Secure, online payment processing for South Africa.\r\n\r\nIn test mode, you can use the customer log in sbtu01@payfast.co.za and password: clientpass for testing. Test mode merchant ID: 10000103, merchant key: 479f49451e829', 'Pay Now', '', 0, '', 'payfast', '', 0, 'Pay with PayFast', 'Click the button below to pay securely with PayFast ', 0, 'https://www.payfast.co.za/', 0, '', 0, '', 0, 0, '', '', '', 0, '', ''),
		(1, 'Pay Offline (mail, in person, cash, etc...)', '', '', '', 'This option gives the customer information on how to pay by mail, in person, etc...', 'Place Order', '', 0, '', 'payoffline', '', 10, 'Pay by mail', 'To pay offline, click the Place Order button below. You will be shown the address to send payment to on your order.', 0, '', 0, '', 0, 'This option gives the customer information on how to pay by mail, in person, etc...', 0, 1, '', '', 'payoffline', 0, 'Please send payment payable to COMPANY NAME to:\r\n\r\nCOMPANY NAME\r\nADDRESS\r\nCITY STATE ZIP\r\n\r\nBe sure you make note of your order number.', ''),
		(2, 'PayPal Standard Business', '', '', '', 'The PayPal business account is the fastest and easiest way to accept payments. \r\n\r\nThis option does not require an SSL.\r\n\r\n<a href=\"https://www.paypal.com/cgi-bin/webscr?cmd=_wp-standard-overview-outside\" target=\"_blank\">Click here to learn more about PayPal</a>', 'Continue to PayPal', '/sy-graphics/paypal_checkout.gif', 0, '', 'paypalstandard', '', 2, 'Pay With PayPal', 'Click the Continue to PayPal button below to complete your transaction. You do not have to have a PayPal account to pay, you can use your credit / debit card as well.', 0, 'https://www.paypal.com/cgi-bin/webscr?cmd=_wp-standard-overview-outside', 0, '', 1, '', 1, 1, '', 'sy-misc/creditcards/paypal.jpg', 'paypalstandard', 0, '', ''),
		(10, 'PayPal Website Payments Pro', '', '', '', 'PayPal Website Payments Pro is a merchant account from PayPal. This is not the same as a PayPal Standard Business Account. \r\n\r\n<a href=\"https://www.paypal.com/cgi-bin/webscr?cmd=_wp-pro-overview-outside\" target=\"_blank\">Click here to learn more</a>\r\n\r\nYou do need a Security Certificate / SSL installed on your website to use this option.\r\n', 'Process Order', '', 0, 'Visa\r\nMasterCard\r\nDiscover\r\nAmerican Express', 'paypalpro', '', 0, 'Pay with Credit Card or PayPal', 'Select from the options below to pay.', 1, 'https://www.paypal.com/cgi-bin/webscr?cmd=_wp-pro-overview-outside', 0, '', 1, '', 0, 1, '', 'sy-misc/creditcards/visamcdiscoveramexpaypal.jpg', 'paypalpro', 0, '', 'Continue To PayPal'),
		(13, 'Collect & Manually Process - UK', '', '', '', 'This option allows you to collect credit card information so you can manually process the card. The card information is split up and part of it is emailed to you and the other part is stored in the database with the order. It is split up like that so it is not stored in tact in one location.\r\n\r\nExample, a customer enters in their card information and submits it.\r\n\r\nYou will get an email with half the card information like this: 41102234XXXXXXXX\r\nWhen you view the order that was paid in the admin, you will see the rest of the information and the other half of the credit card like this:  XXXXXXXX33445566\r\n\r\nThen you can take to two halves to manually process. You will need an SSL / Security Certificate to use this option.\r\n\r\nThe difference between this and the other way to collect information to manually process is that this form ask for a valid from and valid to date and also an issue number.', 'Pay with switch/maestro', '', 0, 'switch/maestro\r\n', 'emailformuk', '', 4, 'Pay With Switch/Maestro', 'Pay online now with Switch/Maestro', 1, '', 0, '', 0, 'Collect credit card information to manually process.', 0, 0, '', '', '', 0, '', ''),
		(14, 'PayJunction', '', '', '', 'Visit PayJunction at <a href=\"http://payjunction.com\" target=_blank>payjunction.com</a> for more information. \r\n\r\nTo use this option, you will need to have a Security Certificate (SSL) installed on your website. ', 'Process Order', '', 0, 'Visa \r\nMasterCard \r\nAmerican Express \r\nDiscover', 'payjunction', '', 1, 'Pay With Credit Card', 'Pay online now with Visa, Master Card, Discover.', 1, 'http://signup.payjunction.com', 0, '', 1, '', 0, 1, '', 'sy-misc/creditcards/visamcdiscover.jpg', 'payjunction', 0, '', ''),
		(15, 'WorldPay', '', '', '', 'Visit WorldPay at <a href=\"http://www.rbsworldpay.com/\" target=\"_blank\">rbsworldpay.com</a> for more information.\r\n', 'Continue', '', 0, '', 'worldpay', '', 9, 'Pay Now', 'Complete your order and pay with credit card online over a secure connection', 0, 'http://www.worldpay.com', 0, '', 1, '', 0, 0, '', '', '', 0, '', ''),
		(18, 'TEST ONLY', 'testonly', 'testonly', '', 'This is for testing / demoing only. Nothing gets processed.', 'Credit Card Test Only', '', 0, 'Visa\r\nMC', 'testonly', '', 0, 'Demo Test Only', 'This is for testing / demoing only. Nothing gets processed.', 0, '', 0, '', 0, '', 0, 1, '', '', 'cardtestonly', 0, '', ''),
		(19, 'PayPal Express Checkout', '', '', '', 'PayPal Express Checkout adds a button on the view cart page to go directly to PayPal to start the checkout process. When the customer returns from PayPal, they will complete their transaction.\r\n\r\nA drawback for this option is that it won''t require the customer to create an account. \r\n\r\nYou can use this option with a standard PayPal business account or with a PayPal Website Payments Pro account. ', '', '', 0, '', 'paypalexpress', '', 0, '', 'PayPal users log in to pay quickly. ', 0, '', 0, '', 1, '', 0, 1, '', '', '', 0, '', ''),
		(20, 'Authorize.net (AIM)', '', '', '', 'Visit Authorize.net at <a href=\"http://www.authorize.net/\" target=\"_blank\">authorize.net</a> for more information.\r\n\r\nThis is using the Advanced Integration Method (AIM) and you need to have a Security Certificate (SSL) installed on your website for this option. When using this option, the customer stays on your website when entering in their payment information. \r\n\r\nIf you have an authorize.net account, enter your login id & transaction key below and change status to \"Active\".', 'Process Order', '', 0, 'Visa \r\nMasterCard \r\nAmerican Express \r\nDiscover', 'authorizenetaim', '', 1, 'Pay With Credit Card', 'Pay online now with Visa, Master Card, Discover or American express.', 1, 'http://www.authorize.net/', 0, '', 1, '', 0, 1, '', 'sy-misc/creditcards/visamcdiscoveramex.jpg', 'authorizenetaim', 0, '', '');



	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "0.1.9") {
	$new_version = "0.2.0";
	$uq = "
	ALTER TABLE `ms_language` ADD `_scroll_to_top_` VARCHAR( 80 ) NOT NULL;
	UPDATE ms_language SET _scroll_to_top_='Scroll To Top';
	ALTER TABLE `ms_css2` ADD `boxes_borders_where` INT NOT NULL ;
	ALTER TABLE `ms_css2` ADD `inside_margin_top` INT NOT NULL ;

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}
	$sytist_version = $new_version;
}

if($sytist_version <= "0.2.0") {
	$new_version = "0.2.1";
	$uq = "
	ALTER TABLE `ms_cart` ADD `cart_reg_key` VARCHAR( 100 ) NOT NULL ;
	ALTER TABLE `ms_billboards` ADD `bill_cat` INT NOT NULL ;

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}
	$sytist_version = $new_version;
}

if($sytist_version <= "0.2.1") {
	$new_version = "0.2.2";
	$uq = "
	ALTER TABLE `ms_billboards` ADD `bill_limit` INT NOT NULL ;

	ALTER TABLE `ms_cart` ADD `cart_photo_prod_connect` INT NOT NULL ;

	ALTER TABLE `ms_photo_products_connect` ADD `pc_qty_descr` TEXT NOT NULL ;
	ALTER TABLE `ms_photo_products_connect` ADD `pc_qty_on` INT NOT NULL ;
	ALTER TABLE `ms_cart` ADD `cart_dis_on` INT NOT NULL ;

	ALTER TABLE `ms_store_language` 
	ADD `_quantity_discounts_` VARCHAR( 100 ) NOT NULL ,
	ADD `_price_based_on_quantity_discount_` TEXT NOT NULL;

	UPDATE ms_store_language SET _quantity_discounts_='quantity discounts', _price_based_on_quantity_discount_='* Price based on quantity discounts'; 


	CREATE TABLE IF NOT EXISTS `ms_photo_products_discounts` (
	  `dis_id` int(11) NOT NULL auto_increment,
	  `dis_prod` int(11) NOT NULL default '0',
	  `dis_qty_from` int(11) NOT NULL default '0',
	  `dis_qty_to` int(11) NOT NULL default '0',
	  `dis_price` decimal(10,2) NOT NULL default '0.00',
	  `dis_on` varchar(10) NOT NULL default '',
	  PRIMARY KEY  (`dis_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000 ;

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}
	$sytist_version = $new_version;
}
if($sytist_version <= "0.2.2") {
	$new_version = "0.2.3";
	$uq = "
	ALTER TABLE `ms_blog_categories` ADD `cat_search` INT NOT NULL ;
	ALTER TABLE `ms_language` ADD `_search_button_` VARCHAR( 80 ) NOT NULL, ADD `_search_` VARCHAR( 80 ) NOT NULL ;
	UPDATE ms_language SET _search_='Search', _search_button_='Search';
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}
	$sytist_version = $new_version;
}
if($sytist_version <= "0.2.3") {
	$new_version = "0.2.4";
	$uq = "
	ALTER TABLE `ms_css2` ADD `page_width_type` VARCHAR( 10 ) NOT NULL ,
	ADD `page_width_max` INT NOT NULL ;
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}
	$sytist_version = $new_version;
}
if($sytist_version <= "0.2.4") {
	$new_version = "0.2.5";
	$uq = "
	ALTER TABLE `ms_language` ADD `_menu_mobile_` VARCHAR( 200 ) NOT NULL;
	UPDATE ms_language SET _menu_mobile_='MENU';

	UPDATE ms_category_layouts SET layout_description='Lists 1 photo from the page that links to view the full page' WHERE layout_id='21';
	UPDATE ms_category_layouts SET layout_description='Lists all photos from the page on the screen' WHERE layout_id='20';
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}
	$sytist_version = $new_version;
}

if($sytist_version <= "0.2.5") {
	$new_version = "0.2.6";
	$uq = "
	UPDATE ms_comments_settings SET com_form_type='long' ; 

	ALTER TABLE `ms_css2` ADD `header_pin_top` INT NOT NULL ;
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode(";", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}
	$sytist_version = $new_version;
}


if($sytist_version <= "0.2.6") {
	$new_version = "0.2.7";
	$uq = "

	DROP TABLE IF EXISTS `ms_social_links`||
	CREATE TABLE IF NOT EXISTS `ms_social_links` (
	  `link_id` int(11) NOT NULL auto_increment,
	  `link_text` varchar(100) NOT NULL default '',
	  `link_url` varchar(255) NOT NULL default '',
	  `link_img` varchar(255) NOT NULL default '',
	  `link_status` int(11) NOT NULL default '0',
	  `link_order` int(11) NOT NULL default '0',
	  `link_name` varchar(20) NOT NULL default '',
	  PRIMARY KEY  (`link_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ||

	INSERT INTO `ms_social_links` (`link_id`, `link_text`, `link_url`, `link_img`, `link_status`, `link_order`, `link_name`) VALUES
	(13, 'Instagram', '', '', 0, 4, 'instagram'),
	(2, 'FaceBoook', '', '', 0, 1, 'facebook'),
	(3, 'Flickr', '', '', 0, 7, 'flickr'),
	(4, 'Twitter', '', '', 0, 3, 'twitter'),
	(12, 'LinkedIn', '', '', 0, 5, 'linkedin'),
	(6, 'YouTube', '', '', 0, 8, 'youtube'),
	(7, 'Picasa', '', '', 0, 9, 'picasa'),
	(14, 'Tumblr', '', '', 0, 10, 'tumblr'),
	(10, 'Pinterest', '', '', 0, 2, 'pinterest'),
	(15, 'Vimeo', '', '', 0, 6, 'vimeo')||


	UPDATE ms_settings SET footer='
	<div style=\"text-align: center\">
		[SITE_NAME] [MENU_LINKS]
	</div>
	<div style=\"text-align: center\">
		[SOCIAL_LINKS]
	</div>
	<div style=\"text-align: center\">
		&copy;[YEAR] All Rights Reserved.  Content may not be used without prior express written consent. 
	<br><a href=\"https://www.picturespro.com/sytist/\" target=\"_blank\">Made with Sytist</a>
</div>'||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}
if($sytist_version <= "0.2.7") {
	$new_version = "0.2.8";
	$uq = "

	ALTER TABLE `ms_blog_categories` ADD `cat_cat_layout` INT NOT NULL ||

	DROP TABLE IF EXISTS `ms_category_layouts`||
	CREATE TABLE IF NOT EXISTS `ms_category_layouts` (
	  `layout_id` int(11) NOT NULL AUTO_INCREMENT,
	  `layout_name` varchar(200) NOT NULL DEFAULT '',
	  `layout_html` text NOT NULL,
	  `layout_description` text NOT NULL,
	  `layout_css_id` varchar(20) NOT NULL DEFAULT '',
	  `layout_js_function` varchar(50) NOT NULL DEFAULT '',
	  `layout_width` int(11) NOT NULL DEFAULT '0',
	  `layout_height` int(11) NOT NULL DEFAULT '0',
	  `layout_photo_class` varchar(40) NOT NULL DEFAULT '',
	  `layout_photo_size` varchar(20) NOT NULL DEFAULT '',
	  `layout_photo_width` int(11) NOT NULL DEFAULT '0',
	  `layout_photo_height` int(11) NOT NULL DEFAULT '0',
	  `layout_spacing` int(11) NOT NULL DEFAULT '0',
	  `layout_per_page` int(11) NOT NULL DEFAULT '0',
	  `layout_preview_text_length` int(11) NOT NULL DEFAULT '0',
	  `layout_type` varchar(20) NOT NULL DEFAULT '',
	  `layout_file` varchar(255) NOT NULL DEFAULT '',
	  `layout_default` int(11) NOT NULL DEFAULT '0',
	  `layout_css` text NOT NULL,
	  `layout_no_delete` int(11) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`layout_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ||


	INSERT INTO `ms_category_layouts` (`layout_id`, `layout_name`, `layout_html`, `layout_description`, `layout_css_id`, `layout_js_function`, `layout_width`, `layout_height`, `layout_photo_class`, `layout_photo_size`, `layout_photo_width`, `layout_photo_height`, `layout_spacing`, `layout_per_page`, `layout_preview_text_length`, `layout_type`, `layout_file`, `layout_default`, `layout_css`, `layout_no_delete`) VALUES
	(1, 'Standard Layout', '', 'Standard listing of pages down the page.', 'listing-standard', '', 0, 0, 'thumbnail', 'pic_pic', 0, 0, 0, 0, 500, 'listing', 'listing-standard-layout.php', 0, '', 1),
	(3, 'On Photo', '', 'This layout shows the titles on over the photo.', 'listing-onphoto', '', 0, 0, 'onphotophoto', 'pic_pic', 0, 0, 0, 0, 100, 'listing', 'listing-on-photo.php', 0, '', 1),
	(4, 'Gallery Thumbnails', '', 'Fixed thumbnail container sizes with thumbnails displayed and title underneath. ', 'listing-thumbnail', '', 0, 0, 'thumbnail', 'pic_th', 0, 0, 0, 0, 0, 'listing', 'listing-gallery-thumbnails.php', 0, '', 1),
	(9, 'Standard Page', '', 'A standard page designed to show 1 photo aligned to the left or right with social share options (if enabled).', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'standard-page.php', 1, '#standardPage {  }\r\n#standardPage .title { padding: 4px; } \r\n#standardPage .text { padding: 4px; } \r\n#standardPage .expire { padding: 4px; } \r\n#standardPage .photos { padding: 4px; float: left; margin: 0 10px 10px 0;  } \r\n\r\n', 1),
	(10, 'Blog Pages', '', 'Displays pages with category link at top, showing date page posted, photos, share options (if enabled), comments options, tags and next & previous links at the bottom.', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'blog-pages.php', 0, '#blogPage { } \r\n#blogPage .title { padding: 4px; } \r\n#blogPage .categories { padding: 4px; } \r\n#blogPage .date { padding: 4px; } \r\n#blogPage .comments { padding: 4px; } \r\n#blogPage .text { padding: 4px; } \r\n#blogPage .photos { } \r\n#blogPage .tags { padding: 4px; } \r\n#blogPage .nextPrevious { padding: 4px; } \r\n', 1),
	(11, 'Store Pages', '', 'Pages are displayed with the page photo aligned to the left of the page with the product description to the right with the add to cart options.', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'store-pages.php', 0, '#storePage {}\r\n#storePage .categories { padding: 4px; }\r\n#storePage .title { padding: 4px; }\r\n#storePage .text { padding: 4px; }\r\n#storePage .nextPrevious { padding: 4px; }\r\n#storePage .photos {  float: left; width: 50%;  }\r\n#storePage .photos .inner { padding: 16px; } \r\n#storePage .content { float: right; width: 50%;  }\r\n#storePage .content .inner { padding: 16px; } \r\n', 1),
	(22, 'Client Galleries', '', 'Displays pages with expiration date (if it has one), photos, social share options (if enabled) and page comments. ', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'client-galleries.php', 0, '#clientGalleries { } \r\n#clientGalleries .title { padding: 4px; } \r\n#clientGalleries .categories { padding: 4px; } \r\n#clientGalleries .expire { padding: 4px; } \r\n#clientGalleries .comments { padding: 4px; } \r\n#clientGalleries .text { padding: 4px; } \r\n#clientGalleries .photos { } \r\n#clientGalleries .nextPrevious { padding: 4px; } ', 0),
	(12, 'Home Page', '', 'Designated for the home page including photos & home page features that are enabled. ', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'home-page.php', 0, '#homePage {  }\r\n#homePage .title { padding: 4px; } \r\n#homePage .text { padding: 4px; } \r\n#homePage .photos { padding: 4px; } \r\n#homePage .subpages { padding: 4px; } \r\n', 1),
	(18, 'Product list with add to cart', '', 'This lists the pages in the category with an add to cart link, but no link to the actual page. This would be like a buy page. ', 'listing-standard', '', 0, 0, 'thumbnail', 'pic_pic', 0, 0, 0, 0, 500, 'listing', 'listing-product-list-with-add-to-cart.php', 0, '', 0),
	(19, 'Gallery Thumbnails With Prices', '', 'Fixed thumbnail container sizes with thumbnails displayed and title underneath. ', 'listing-thumbnail', '', 0, 0, 'thumbnail', 'pic_th', 0, 0, 0, 0, 0, 'listing', 'listing-gallery-thumbnails-with-prices.php', 0, '', 0),
	(21, 'Blog Layout - 1 Photo', '', 'Lists 1 photo from the page that links to view the full page', 'listing-blog', '', 0, 0, 'thumbnail', 'pic_large', 0, 0, 0, 0, 0, 'listing', 'listing-blog-layout---1-photo.php', 0, '', 0),
	(23, 'Galleries', '', 'Displays pages  photos, social share options (if enabled) and page comments. ', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'galleries.php', 0, '#galleries { } \r\n#galleries .title { padding: 4px; } \r\n#galleries .categories { padding: 4px; } \r\n#galleries .comments { padding: 4px; } \r\n#galleries .text { padding: 4px; } \r\n#galleries .photos { } \r\n#galleries .nextPrevious { padding: 4px; } \r\n', 0)||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}

if($sytist_version <= "0.2.8") {
	$new_version = "0.2.9";
	$uq = "


	ALTER TABLE `ms_menu_links` ADD `link_dropdown` VARCHAR( 10 ) NOT NULL ||
	ALTER TABLE `ms_calendar` ADD `thumbactions` INT NOT NULL ||
	ALTER TABLE `ms_defaults` ADD `thumbactions` INT NOT NULL ||

	TRUNCATE TABLE `ms_category_layouts`||

	INSERT INTO `ms_category_layouts` (`layout_id`, `layout_name`, `layout_html`, `layout_description`, `layout_css_id`, `layout_js_function`, `layout_width`, `layout_height`, `layout_photo_class`, `layout_photo_size`, `layout_photo_width`, `layout_photo_height`, `layout_spacing`, `layout_per_page`, `layout_preview_text_length`, `layout_type`, `layout_file`, `layout_default`, `layout_css`, `layout_no_delete`) VALUES
	(1, 'Standard Layout', '', 'Standard listing of pages down the page.', 'listing-standard', '', 0, 0, 'thumbnail', 'pic_pic', 0, 0, 0, 0, 500, 'listing', 'listing-standard-layout.php', 0, '', 1),
	(3, 'On Photo', '', 'This layout shows the titles on over the photo.', 'listing-onphoto', '', 0, 0, 'onphotophoto', 'pic_pic', 0, 0, 0, 0, 100, 'listing', 'listing-on-photo.php', 0, '', 1),
	(4, 'Thumbnail', '', 'Thumbnail in a styled container with the title underneath. ', 'listing-thumbnail', '', 0, 0, 'thumbnail', 'pic_th', 0, 0, 0, 0, 400, 'listing', 'listing-gallery-thumbnails.php', 0, '', 1),
	(9, 'Standard Page', '', 'A standard page designed to show 1 photo aligned to the left or right with social share options (if enabled).', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'standard-page.php', 1, '#standardPage {  }\r\n#standardPage .title { padding: 4px; } \r\n#standardPage .text { padding: 4px; } \r\n#standardPage .expire { padding: 4px; } \r\n#standardPage .photos { padding: 4px; float: left; margin: 0 10px 10px 0;  } \r\n\r\n', 1),
	(10, 'Blog Pages', '', 'Displays pages with category link at top, showing date page posted, photos, share options (if enabled), comments options, tags and next & previous links at the bottom.', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'blog-pages.php', 0, '#blogPage { } \r\n#blogPage .title { padding: 4px; } \r\n#blogPage .categories { padding: 4px; } \r\n#blogPage .date { padding: 4px; } \r\n#blogPage .comments { padding: 4px; } \r\n#blogPage .text { padding: 4px; } \r\n#blogPage .photos { } \r\n#blogPage .tags { padding: 4px; } \r\n#blogPage .nextPrevious { padding: 4px; } \r\n', 1),
	(11, 'Store Pages', '', 'Pages are displayed with the page photo aligned to the left of the page with the product description to the right with the add to cart options.', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'store-pages.php', 0, '#storePage {}\r\n#storePage .categories { padding: 4px; }\r\n#storePage .title { padding: 4px; }\r\n#storePage .text { padding: 4px; }\r\n#storePage .nextPrevious { padding: 4px; }\r\n#storePage .photos {  float: left; width: 50%;  }\r\n#storePage .photos .inner { padding: 16px; } \r\n#storePage .content { float: right; width: 50%;  }\r\n#storePage .content .inner { padding: 16px; } \r\n', 1),
	(22, 'Client Galleries', '', 'Displays pages with expiration date (if it has one), photos, social share options (if enabled) and page comments. ', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'client-galleries.php', 0, '#clientGalleries { } \r\n#clientGalleries .title { padding: 4px; } \r\n#clientGalleries .categories { padding: 4px; } \r\n#clientGalleries .expire { padding: 4px; } \r\n#clientGalleries .comments { padding: 4px; } \r\n#clientGalleries .text { padding: 4px; } \r\n#clientGalleries .photos { } \r\n#clientGalleries .nextPrevious { padding: 4px; } ', 0),
	(12, 'Home Page', '', 'Designated for the home page including photos & home page features that are enabled. ', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'home-page.php', 0, '#homePage {  }\r\n#homePage .title { padding: 4px; } \r\n#homePage .text { padding: 4px; } \r\n#homePage .photos { padding: 4px; } \r\n#homePage .subpages { padding: 4px; } \r\n', 1),
	(18, 'Product list with add to cart', '', 'This lists the pages in the category with an add to cart link, but no link to the actual page. This would be like a buy page. ', 'listing-standard', '', 0, 0, 'thumbnail', 'pic_pic', 0, 0, 0, 0, 500, 'listing', 'listing-product-list-with-add-to-cart.php', 0, '', 0),
	(19, 'Thumbnail With Prices', '', 'Thumbnail in a styled container with the title & price underneath. For displaying products for sale.', 'listing-thumbnail', '', 0, 0, 'thumbnail', 'pic_th', 0, 0, 0, 0, 0, 'listing', 'listing-gallery-thumbnails-with-prices.php', 0, '', 0),
	(21, 'Blog Layout - 1 Photo', '', 'Lists 1 photo from the page that links to view the full page', 'listing-blog', '', 0, 0, 'thumbnail', 'pic_large', 0, 0, 0, 0, 0, 'listing', 'listing-blog-layout---1-photo.php', 0, '', 0),
	(23, 'Galleries', '', 'Displays pages  photos, social share options (if enabled) and page comments. ', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'galleries.php', 0, '#galleries { } \r\n#galleries .title { padding: 4px; } \r\n#galleries .categories { padding: 4px; } \r\n#galleries .comments { padding: 4px; } \r\n#galleries .text { padding: 4px; } \r\n#galleries .photos { } \r\n#galleries .nextPrevious { padding: 4px; } \r\n', 0)||


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}


if($sytist_version <= "0.2.9") {
	$new_version = "0.3.0";
	$uq = "
	ALTER TABLE `ms_defaults` ADD `disable_filename` INT NOT NULL ||

	ALTER TABLE `ms_calendar` ADD `disable_filename` INT NOT NULL ||

	ALTER TABLE `ms_calendar` ADD `feature_show_titles` INT NOT NULL ||

	ALTER TABLE `ms_admin_logins` ADD `log_admin_id` INT NOT NULL ||

	ALTER TABLE `ms_admin_logins` ADD `login_failed` INT NOT NULL ||

	ALTER TABLE `ms_admin_logins` ADD `login_failed_pass` VARCHAR( 255 ) NOT NULL ||

	ALTER TABLE `ms_admin_reset` ADD `reset_admin` INT NOT NULL ||


	CREATE TABLE IF NOT EXISTS `ms_admins` (
	  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
	  `admin_name` varchar(255) NOT NULL,
	  `admin_email` varchar(255) NOT NULL,
	  `admin_pass` varchar(255) NOT NULL,
	  `admin_salt` varchar(50) NOT NULL,
	  `admin_master` int(11) NOT NULL,
	  `design` int(11) NOT NULL,
	  `comments` int(11) NOT NULL,
	  `forms` int(11) NOT NULL,
	  `settings` int(11) NOT NULL,
	  `orders` int(11) NOT NULL,
	  `people` int(11) NOT NULL,
	  `photoprods` int(11) NOT NULL,
	  `coupons` int(11) NOT NULL,
	  `stats` int(11) NOT NULL,
	  `content` int(11) NOT NULL,
	  `contentpublish` int(11) NOT NULL,
	  `contentdelete` int(11) NOT NULL,
	  `uploadphotos` int(11) NOT NULL,
	  `deletephotos` int(11) NOT NULL,
	  `admin_user` varchar(200) NOT NULL,
	  `admin_full_access` int(11) NOT NULL,
	  `allphotos` int(11) NOT NULL,
	  PRIMARY KEY (`admin_id`),
	  KEY `admin_id` (`admin_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ||


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}
if($sytist_version <= "0.3.0") {
	$new_version = "0.3.1";
	$uq = "
	ALTER TABLE `ms_people` ADD `p_pass_def` VARCHAR( 255 ) NOT NULL ||
	ALTER TABLE `ms_orders` ADD `order_credit` DECIMAL( 10, 2 ) NOT NULL ||
	ALTER TABLE `ms_pending_orders` ADD `order_credit` DECIMAL( 10, 2 ) NOT NULL ||

	ALTER TABLE `ms_store_language` ADD `_account_credit_` VARCHAR( 150 ) NOT NULL||
	UPDATE ms_store_language SET _account_credit_='Account Credit' || 

	ALTER TABLE `ms_store_language` 
	ADD `_place_order_no_payment_` VARCHAR( 150 ) NOT NULL,
	ADD `_place_order_no_payment_text_` TEXT NOT NULL||

	UPDATE ms_store_language SET _place_order_no_payment_='Place Your Order', _place_order_no_payment_text_='Your payment total is zero so no payment is needed. Click the place your button order to complete the order.'|| 

	ALTER TABLE `ms_store_language`  ADD `_your_have_credit_in_account_` TEXT NOT NULL||

	UPDATE ms_store_language SET _your_have_credit_in_account_='You have a credit in your account of '||

	ALTER TABLE `ms_store_language` ADD `_pay_invoice_` TEXT NOT NULL||

	UPDATE ms_store_language SET _pay_invoice_='Pay Now' ||

	ALTER TABLE `ms_orders` ADD `order_invoice` INT NOT NULL||

	CREATE TABLE IF NOT EXISTS `ms_credits` (
	  `credit_id` int(11) NOT NULL AUTO_INCREMENT,
	  `credit_customer` int(11) NOT NULL DEFAULT '0',
	  `credit_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
	  `credit_notes` text NOT NULL,
	  `credit_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `credit_order` int(11) NOT NULL DEFAULT '0',
	  `credit_reg` int(11) NOT NULL DEFAULT '0',
	  `credit_reg_order` int(11) NOT NULL DEFAULT '0',
	  `credit_reg_message` text NOT NULL,
	  `credit_reg_buyer` int(11) NOT NULL DEFAULT '0',
	  `credit_expire` date NOT NULL,
	  PRIMARY KEY (`credit_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=163 ||

	ALTER TABLE `ms_pending_orders` ADD `order_order_id` INT NOT NULL ||



	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "0.3.1") {
	$new_version = "0.3.2";
	$uq = "
	ALTER TABLE `ms_cart` ADD `cart_invoice` INT NOT NULL ||
	ALTER TABLE `ms_blog_categories` ADD `cat_expire_hide` INT NOT NULL||
	ALTER TABLE `ms_css2` ADD `header_code` TEXT NOT NULL ||

	ALTER TABLE `ms_promo_codes` ADD `code_descr` TEXT NOT NULL ||

	ALTER TABLE `ms_calendar` ADD `photo_search` INT NOT NULL ||

	ALTER TABLE `ms_defaults` ADD `photo_search` INT NOT NULL ||


	ALTER TABLE `ms_language` ADD `_search_photos_` VARCHAR( 200 ) NOT NULL, ADD `_search_photos_button_` VARCHAR( 200 ) NOT NULL||
	UPDATE ms_language SET _search_photos_='Search Photos', _search_photos_button_='Search'||

	ALTER TABLE `ms_store_language` ADD `_coupon_used_` TEXT NOT NULL, ADD `_coupon_expired_` TEXT NOT NULL||
	UPDATE ms_store_language SET _coupon_used_='You have already used that coupon on a previous order and has been removed from your shopping cart.', _coupon_expired_='Sorry, but the coupon in your cart has expired and has been removed from your shopping cart.'||

	ALTER TABLE `ms_photo_keywords_connect` ADD INDEX ( `key_key_id` )||
	ALTER TABLE `ms_photo_keywords_connect` ADD INDEX ( `key_pic_id` )||

	ALTER TABLE `ms_blog_photos` ADD INDEX ( `bp_pic` )||
	ALTER TABLE `ms_calendar` ADD INDEX ( `date_cat` )||
	ALTER TABLE `ms_billboard_slides` ADD INDEX ( `slide_pic` )||
	ALTER TABLE `ms_billboard_slides` ADD INDEX ( `slide_billboard` )||
	ALTER TABLE `ms_blog_photos` ADD INDEX ( `bp_order` )||

	ALTER TABLE `ms_language` ADD `_view_all_photo_tags_` VARCHAR( 200 ) NOT NULL, ADD `_view_all_photo_tags_descr_` TEXT NOT NULL||
	UPDATE ms_language SET _view_all_photo_tags_='View all tags', _view_all_photo_tags_descr_='Below are the tags for photos in this gallery. Click on the tag to view those photos.'||

	ALTER TABLE `ms_language` ADD `_showing_tag_results_` TEXT NOT NULL, ADD `_showing_search_results_` TEXT NOT NULL||
	UPDATE ms_language SET _showing_tag_results_='Showing photos with the tag', _showing_search_results_='Showing results for search'||



	INSERT INTO `ms_emails` (`email_name`, `email_descr`, `email_codes`, `email_from_email`, `email_from_name`, `email_subject`, `email_message`, `email_type`, `email_no_delete`, `email_download_descr`, `email_shipping_descr`, `email_paypal_pending`, `email_offline_pending`) VALUES ('Email Order To Customer', 'The default email when emailing an order to a customer from the admin.', '[FIRST_NAME] = Customer''s first name
	[LAST_NAME] = Customer''s last name
	[EMAIL_ADDRESS] = Customer''s email address
	[ADDRESS] = Address
	[CITY] = City
	[STATE] = State
	[ZIP] = Postal code
	[SHIP_FIRST_NAME]  = Ship to first name
	[SHIP_LAST_NAME] = Ship to last name
	[SHIP_ADDRESS] = Ship to address
	[SHIP_CITY]  = Ship to city
	[SHIP_STATE] = Ship to state
	[SHIP_ZIP] = Ship to postal code
	[ORDER_NUMBER] = The order / invoice number
	[ORDER_TOTAL] = The order total
	[TOTAL_ITEMS] = Number of items on the order
	[ORDER_ITEMS] = Lists the items on the order
	[ORDER_DATE] = Current date
	[URL] = Link to your website
	[WEBSITE_NAME] = The name of your website', '', '', '[WEBSITE_NAME] - Your Invoice  #[ORDER_NUMBER]', '
	<p><span style=\"font-size: 14pt;\">Hello [FIRST_NAME] [LAST_NAME]</span>,</p>
	
	<p>Below are the details of your invoice. To pay, please log into our website and click My Account in the top menu. Once logged in, click on the order number. </p>
	

	<p>Order Number: [ORDER_NUMBER]</p>
	<p>
		<p>Date: [ORDER_DATE]</p>
		<p>Email address: [EMAIL_ADDRESS]</p></p>
	
	<p>
		<table cellpadding=\"8\" style=\"border-collapse:collapse;width:100%;\">
			<tbody>
				<tr>
					<td style=\"vertical-align: top; border-color: rgb(199, 199, 199); border-width: 1px; border-style: solid; background-color: rgb(242, 242, 242);\">
						<p><span style=\"font-size: 12pt; font-weight: bold;\">BILLING</span></p>
						<p>[FIRST_NAME] [LAST_NAME]</p>
						<p>[ADDRESS]</p>
						<p>[CITY], [STATE] [ZIP]</p>
						</td>
					<td style=\"vertical-align: top; border-color: rgb(199, 199, 199); border-width: 1px; border-style: solid; background-color: rgb(242, 242, 242);\" id=\"shippingaddress\">
						<p id=\"ship1\"><span style=\"font-size: 12pt; font-weight: bold;\">SHIPPING</span></p>
						<p id=\"ship2\">[SHIP_FIRST_NAME] [SHIP_LAST_NAME]<br />
							 </p>
						<p id=\"ship3\">[SHIP_ADDRESS]</p>
						<p id=\"ship4\">[SHIP_CITY] [SHIP_STATE], [SHIP_ZIP]</p></td>
				</tr>
			</tbody>
		</table><br />
		</p>
	<p>&nbsp;[ORDER_ITEMS]</p>
	
	<p>Thank you for your business! <br />
		</p>
	<p><span style=\"font-size: 12pt;\">[WEBSITE_NAME]</span></p>
	<p>[URL]</p>
	
	
	', '0', '1', '', '', '', '')||


	CREATE TABLE IF NOT EXISTS `ms_promo_codes_discounts` (
	  `dis_id` int(11) NOT NULL AUTO_INCREMENT,
	  `dis_from` decimal(10,2) NOT NULL,
	  `dis_to` decimal(10,2) NOT NULL,
	  `dis_percent` decimal(10,2) NOT NULL,
	  `dis_flat` decimal(10,2) NOT NULL,
	  `dis_promo` int(11) NOT NULL,
	  PRIMARY KEY (`dis_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ||


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}


if($sytist_version <= "0.3.2") {
	$new_version = "0.3.3";
	$uq = "
	ALTER TABLE `ms_sub_galleries` ADD `sub_under_ids` VARCHAR( 255 ) NOT NULL ||

	ALTER TABLE `ms_store_language` ADD `_download_zip_file_` TEXT NOT NULL, ADD `_downloading_zip_file_` TEXT NOT NULL||
	UPDATE ms_store_language SET _download_zip_file_='Download Photos In A Zip File', _downloading_zip_file_='Downloading. When prompt save to your computer.'||

	ALTER TABLE `ms_cart` ADD `cart_photo_order` INT NOT NULL ||

	ALTER TABLE `ms_history` ADD `export_dowith` VARCHAR( 15 ) NOT NULL ,
	ADD `export_sepwith` VARCHAR( 15 ) NOT NULL ,
	ADD `print_withthumbs` INT NOT NULL ,
	ADD `print_invoiceheader` INT NOT NULL ||

	UPDATE ms_history SET export_sepwith=', '||
	ALTER TABLE `ms_forms` ADD `form_cols` INT NOT NULL ||

	ALTER TABLE `ms_forms` ADD `form_max_width` INT NOT NULL ||
	ALTER TABLE `ms_form_fields` ADD `ff_span_across` INT NOT NULL ||
	ALTER TABLE `ms_category_layouts` ADD `layout_folder` VARCHAR( 50 ) NOT NULL ||




	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}


if($sytist_version <= "0.3.3") {
	$new_version = "0.3.4";
	$uq = "
	TRUNCATE TABLE `ms_css`||
	TRUNCATE TABLE `ms_css2`||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}

if($sytist_version <= "0.3.4") {
	$new_version = "0.3.5";
	$uq = "

	ALTER TABLE `ms_admins` ADD `reports` INT NOT NULL ||

	CREATE TABLE IF NOT EXISTS `ms_expenses` (
	  `exp_id` int(11) NOT NULL AUTO_INCREMENT,
	  `exp_amount` decimal(10,2) NOT NULL,
	  `exp_date` date NOT NULL,
	  `exp_notes` text NOT NULL,
	  `exp_order` int(11) NOT NULL,
	  `exp_reference` varchar(200) NOT NULL,
	  PRIMARY KEY (`exp_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ||

	CREATE TABLE IF NOT EXISTS `ms_expenses_tags` (
	  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(200) NOT NULL,
	  PRIMARY KEY (`tag_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ||


	CREATE TABLE IF NOT EXISTS `ms_expenses_tags_connect` (
	  `con_id` int(11) NOT NULL AUTO_INCREMENT,
	  `con_exp_id` int(11) NOT NULL,
	  `con_tag_id` int(11) NOT NULL,
	  PRIMARY KEY (`con_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ||

	
	
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "0.3.5") {
	$new_version = "0.3.6";
	$uq = "

	ALTER TABLE `ms_defaults` ADD `thumb_open_first` INT NOT NULL ||
	ALTER TABLE `ms_calendar` ADD `thumb_open_first` INT NOT NULL ||
	ALTER TABLE `ms_calendar` ADD `audio_file` VARCHAR( 255 ) NOT NULL ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "0.3.6") {
	$new_version = "0.3.7";
	$uq = "

	ALTER TABLE `ms_calendar` ADD `splash_enable` INT NOT NULL ,
	ADD `splash_text` TEXT NOT NULL ,
	ADD `splash_close` VARCHAR( 255 ) NOT NULL ||

	ALTER TABLE `ms_history` ADD `splash_close` VARCHAR( 255 ) NOT NULL ,
	ADD `splash_text` TEXT NOT NULL ||

	ALTER TABLE `ms_calendar` ADD `splash_view` VARCHAR( 255 ) NOT NULL ||
	ALTER TABLE `ms_history` ADD `splash_view` VARCHAR( 255 ) NOT NULL ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}

if($sytist_version <= "0.3.7") {
	$new_version = "0.3.8";
	$uq = "

	ALTER TABLE `ms_shipping_methods` ADD `method_pickup` INT NOT NULL ||

	ALTER TABLE `ms_photo_setup` ADD `ftp_process` INT NOT NULL ,
	ADD `ftp_rest` INT NOT NULL ||

	UPDATE ms_photo_setup SET ftp_process='10', ftp_rest='10'||
	ALTER TABLE `ms_pending_orders` ADD `order_ship_pickup` INT NOT NULL ||
	ALTER TABLE `ms_orders` ADD `order_ship_pickup` INT NOT NULL ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "0.3.8") {
	$new_version = "0.3.9";
	$uq = "

ALTER TABLE `ms_orders` ADD `order_aff` INT NOT NULL ,
ADD `order_aff_perc` DECIMAL( 10, 2 ) NOT NULL ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "0.3.9") {
	$new_version = "0.4.0";
	$uq = "

	ALTER TABLE `ms_stats_site_visitors` ADD `st_aff` INT NOT NULL ||
	ALTER TABLE `ms_calendar` ADD `add_style` INT NOT NULL ||
	ALTER TABLE `ms_defaults` ADD `add_style` INT NOT NULL ||

	ALTER TABLE `ms_calendar` ADD `thumb_file` VARCHAR( 10 ) NOT NULL ||
	ALTER TABLE `ms_defaults` ADD `thumb_file` VARCHAR( 10 ) NOT NULL ||
	ALTER TABLE `ms_calendar` ADD `disable_icons` INT NOT NULL ||
	ALTER TABLE `ms_defaults` ADD `disable_icons` INT NOT NULL ||

	UPDATE ms_photo_setup SET blog_th_width='200', blog_th_height='300' ||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}


if($sytist_version <= "0.4.0") {
	$new_version = "0.4.1";
	$uq = "

	ALTER TABLE `ms_calendar` ADD `stacked_width` INT NOT NULL ,
	ADD `stacked_margin` INT NOT NULL ||


	ALTER TABLE `ms_defaults` ADD `stacked_width` INT NOT NULL ,
	ADD `stacked_margin` INT NOT NULL ||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}
if($sytist_version <= "0.4.1") {
	$new_version = "0.4.2";
	$uq = "
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "0.4.2") {
	$new_version = "0.4.3";
	$uq = "
	ALTER TABLE `ms_states` CHANGE `state_abr` `state_abr` VARCHAR( 120 )||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}
	$states = whileSQL("ms_states", "*", "WHERE state_abr='' ");
	while($state = mysqli_fetch_array($states)) { 
		updateSQL("ms_states", "state_abr='".$state['state_name']."' WHERE state_id='".$state['state_id']."' ");
	}

	$sytist_version = $new_version;

}


if($sytist_version <= "0.4.3") {
	$new_version = "0.4.4";
	$uq = "
	ALTER TABLE `ms_settings` ADD `salt` VARCHAR( 50 ) NOT NULL ||

	ALTER TABLE `ms_cart` CHANGE `cart_qty` `cart_qty` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0'||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
	$salt = '';
	for ($i = 0; $i < 5; $i++) { 
		$salt .= $characters[mt_rand(0, 39)];
	}
	updateSQL("ms_settings", "salt='". addslashes(stripslashes($salt))."' ");

	updateSQL("ms_emails", "
	email_codes='[FIRST_NAME] = Customer\'s first name
	[LAST_NAME] = Customer\'s last name
	[EMAIL_ADDRESS] = Customer\'s email address
	[NEW_LOGIN_INFO] = When you have created a customer account in the admin and has a temp password
	[ADDRESS] = Address
	[CITY] = City
	[STATE] = State
	[ZIP] = Postal code
	[SHIP_FIRST_NAME]  = Ship to first name
	[SHIP_LAST_NAME] = Ship to last name
	[SHIP_ADDRESS] = Ship to address
	[SHIP_CITY]  = Ship to city
	[SHIP_STATE] = Ship to state
	[SHIP_ZIP] = Ship to postal code
	[ORDER_NUMBER] = The order / invoice number
	[ORDER_TOTAL] = The order total
	[TOTAL_ITEMS] = Number of items on the order
	[ORDER_ITEMS] = Lists the items on the order
	[ORDER_DATE] = Current date
	[URL] = Link to your website
	[WEBSITE_NAME] = The name of your website',
	email_message='
	<p><span style=\"font-size: 14pt;\">Hello [FIRST_NAME] [LAST_NAME]</span>,</p>
	
	<p>Below are the details of your invoice. To pay, please log into our website and click My Account in the top menu. Once logged in, click on the order number. </p>
	<p>[NEW_LOGIN_INFO] <br />
		</p>
	<p>Order Number: [ORDER_NUMBER]</p>
	<p>
		<p>Date: [ORDER_DATE]</p>
		<p>Email address: [EMAIL_ADDRESS]</p></p>
	
	<p>
		<table cellpadding=\"8\" style=\"border-collapse:collapse;width:100%;\">
			<tbody>
				<tr>
					<td style=\"vertical-align: top; border-color: rgb(199, 199, 199); border-width: 1px; border-style: solid; background-color: rgb(242, 242, 242);\">
						<p><span style=\"font-size: 12pt; font-weight: bold;\">BILLING</span></p>
						<p>[FIRST_NAME] [LAST_NAME]</p>
						<p>[ADDRESS]</p>
						<p>[CITY], [STATE] [ZIP]</p>
						</td>
					<td id=\"shippingaddress\" style=\"vertical-align: top; border-color: rgb(199, 199, 199); border-width: 1px; border-style: solid; background-color: rgb(242, 242, 242);\">
						<p id=\"ship1\"><span style=\"font-size: 12pt; font-weight: bold;\">SHIPPING</span></p>
						<p id=\"ship2\">[SHIP_FIRST_NAME] [SHIP_LAST_NAME]<br />
							 </p>
						<p id=\"ship3\">[SHIP_ADDRESS]</p>
						<p id=\"ship4\">[SHIP_CITY] [SHIP_STATE], [SHIP_ZIP]</p></td>
				</tr>
			</tbody>
		</table><br />
		</p>
	<p>&nbsp;[ORDER_ITEMS]</p>
	
	<p>Thank you for your business! <br />
		</p>
	<p><span style=\"font-size: 12pt;\">[WEBSITE_NAME]</span></p>
	<p>[URL]</p>
	
	
	' WHERE email_id='21' ");

	$sytist_version = $new_version;
}


if($sytist_version <= "0.4.4") {
	$new_version = "0.4.5";
	$uq = "
	ALTER TABLE `ms_history` ADD `db_backup` DATE NOT NULL ||
	ALTER TABLE `ms_history` ADD `db_backup_no_show` INT NOT NULL ||
	UPDATE ms_history SET db_backup=NOW()||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}
if($sytist_version <= "0.4.5") {
	$new_version = "0.4.6";
	$uq = "
	ALTER TABLE `ms_calendar` CHANGE `date_text` `date_text` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}

if($sytist_version <= "0.4.6") {
	$new_version = "0.4.7";
	$uq = "
	ALTER TABLE `ms_calendar` ADD `video_file` INT NOT NULL ||
	ALTER TABLE `ms_photo_products_lists` ADD `list_products_placement` INT NOT NULL ||

	ALTER TABLE `ms_store_language` ADD `_view_my_collection_` TEXT NOT NULL, ADD `_back_to_products_` TEXT NOT NULL||
	UPDATE ms_store_language SET _view_my_collection_='View My Collection', _back_to_products_='Back To Product List'||

	ALTER TABLE `ms_store_language` ADD `_selected_photos_` TEXT NOT NULL||
	UPDATE ms_store_language SET _selected_photos_='Selected Photos'||


	ALTER TABLE `ms_store_language`  ADD `_package_one_instructions_` TEXT NOT NULL||
	UPDATE ms_store_language SET  _package_instructions_='Click the Add Photo link next to the product you want to add this photo to.', _package_one_instructions_='Click add photo below to add this photo to the collection.'||

	UPDATE ms_photo_products_lists SET list_products_placement='1' ||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}

if($sytist_version <= "0.4.7") {
	$new_version = "0.4.8";
	$uq = "
	ALTER TABLE `ms_store_language` ADD `_empty_cart_` VARCHAR( 100 ) NOT NULL  ||
	UPDATE ms_store_language SET _empty_cart_='Empty Cart' ||
	ALTER TABLE `ms_store_language` ADD `_not_available_mobile_` TEXT NOT NULL  ||
	UPDATE ms_store_language SET _not_available_mobile_='Sorry, this item is not available on mobile phone. Please use a computer or tablet to purchase this item. '||

	CREATE TABLE IF NOT EXISTS `ms_videos` (
	  `vid_id` int(11) NOT NULL AUTO_INCREMENT,
	  `vid_file` varchar(255) NOT NULL,
	  `vid_folder` varchar(255) NOT NULL,
	  `vid_name` varchar(255) NOT NULL,
	  `vid_width` int(11) NOT NULL,
	  `vid_height` int(11) NOT NULL,
	  `vid_date` date NOT NULL,
	  `vid_key` varchar(255) NOT NULL,
	  `vid_size` int(11) NOT NULL,
	  PRIMARY KEY (`vid_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}


if($sytist_version <= "0.4.8") {
	$new_version = "0.4.9";
	$uq = "
	ALTER TABLE `ms_store_language` ADD `_clear_favorites_` VARCHAR( 100 ) NOT NULL , ADD `_no_favorites_` TEXT NOT NULL ||
	UPDATE ms_store_language SET _clear_favorites_='Remove all favorites', _no_favorites_='You have no favorites'||

	ALTER TABLE `ms_cart` ADD `cart_product_photo` INT NOT NULL ||

	ALTER TABLE `ms_calendar` ADD `prod_photos` INT NOT NULL ||
	ALTER TABLE `ms_cart` ADD `cart_product_select_photos` INT NOT NULL ||

	ALTER TABLE `ms_store_language` ADD `_add_photo_product_instructions_` TEXT NOT NULL, ADD `_product_complete_` TEXT NOT NULL ||
	UPDATE ms_store_language SET  _add_photo_product_instructions_='Click add photo below to select this photo this product. ', _add_to_package_='Add Photo ', _product_complete_='You have select all photos for this product.'||

	ALTER TABLE `ms_language` ADD `_to_` VARCHAR( 20 ) NOT NULL ||
	UPDATE ms_language SET _to_='to'||

	ALTER TABLE `ms_store_language` ADD `_photo_not_available_for_collection_` TEXT NOT NULL ||
	UPDATE ms_store_language SET _photo_not_available_for_collection_='Sorry, this photo is not available for your collection.'||

	ALTER TABLE `ms_store_language` ADD `_cart_selected_product_photos_` TEXT NOT NULL ||
	UPDATE ms_store_language SET _cart_selected_product_photos_='Below are the photos selected for the above product.'||

	ALTER TABLE `ms_store_language` ADD `_select_photos_for_product_message_` TEXT NOT NULL ||
	UPDATE ms_store_language SET _select_photos_for_product_message_='When viewing your photos now, you will see the option to select the photos for this product.'||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}


if($sytist_version <= "0.4.9") {
	$new_version = "0.5.0";
	$uq = "
	INSERT INTO `ms_payment_options` (`pay_id`, `pay_name`, `pay_num`, `pay_key`, `pay_email`, `pay_descr`, `pay_text`, `pay_button`, `pay_status`, `pay_cards`, `pay_option`, `pay_page_title`, `pay_order`, `pay_title`, `pay_description`, `pay_ssl`, `pay_url`, `paypal_curl`, `pay_emulator`, `test_mode`, `pay_short_description`, `pay_full_page`, `pay_dev_status`, `pay_currency`, `pay_select_graphic`, `pay_form`, `pay_express_download_address`, `pay_offline_descr`, `pay_paypal_exp_button`) VALUES
	(21, 'Stripe', ' ', '', '', 'Stripe is a simple way to accept payments online. <a href=\"https://stripe.com/\" target=\"_blank\">Go to stripe.com for more information</a>.\r\n\r\nWhen you have a Stripe.com account, go to <a href=\"https://manage.stripe.com/account/apikeys\" target=\"_blank\">https://manage.stripe.com/account/apikeys</a> to get your API keys. \r\n\r\nYou have test keys you can use for testing purposes as well. ', 'Pay Now', '', 0, '', 'stripe', '', 0, 'Pay With Credit Card', '', 1, 'https://stripe.com', 0, '', 0, '', 0, 1, '', '', 'stripe', 0, '', '')||

	UPDATE ms_payment_options SET pay_dev_status='0' WHERE pay_id='19'||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}

if($sytist_version <= "0.5.0") {
	$new_version = "0.5.1";
	$uq = "
	ALTER TABLE `ms_menu_links` ADD `link_show_cart` INT NOT NULL ||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "0.5.1") {
	$new_version = "0.5.2";
	$uq = "
	ALTER TABLE `ms_photo_products` ADD `pp_free_limit` INT NOT NULL ,
	ADD `pp_free_req_login` INT NOT NULL ||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}
if($sytist_version <= "0.5.2") {
	$new_version = "0.5.3";
	$uq = "
	ALTER TABLE `ms_blog_categories` ADD `cat_content` INT NOT NULL ||
	ALTER TABLE `ms_calendar` ADD `prod_order_message` TEXT NOT NULL ||
	ALTER TABLE `ms_cart` ADD `cart_order_message` TEXT NOT NULL ||

	ALTER TABLE `ms_blog_categories` ADD `cat_disable_side` INT NOT NULL ||
	ALTER TABLE `ms_menu_links` ADD `link_html` TEXT NOT NULL ||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}

if($sytist_version <= "0.5.3") {
	$new_version = "0.5.4";
	$uq = "
	ALTER TABLE `ms_cart` ADD `cart_no_discount` INT NOT NULL ||
	ALTER TABLE `ms_calendar` ADD `prod_no_discount` INT NOT NULL ||
	ALTER TABLE `ms_history` ADD `home_recent_pages` INT NOT NULL ||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}

if($sytist_version <= "0.5.4") {
	$new_version = "0.5.5";
	$uq = "
	ALTER TABLE `ms_history` ADD `upgrade_check` DATE NOT NULL ,
	ADD `upgrade_message` TEXT NOT NULL ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}
if($sytist_version <= "0.5.5") {
	$new_version = "0.5.6";
	$uq = "
	ALTER TABLE `ms_blog_categories` ADD `cat_forum_categories` TEXT NOT NULL ||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}

if($sytist_version <= "0.5.6") {
	$new_version = "0.5.7";
	$uq = "
	ALTER TABLE `ms_history` ADD `installingon` VARCHAR( 20 ) NOT NULL ||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}
	$sytist_version = $new_version;
}

if($sytist_version <= "0.5.7") {
	$new_version = "0.5.8";
	$uq = "
	ALTER TABLE `ms_store_language` 
	ADD `_view_my_orders_` VARCHAR( 100 ) NOT NULL , 
	ADD `_view_my_photos_` VARCHAR( 100 ) NOT NULL , 
	ADD `_change_password_` VARCHAR( 100 ) NOT NULL , 
	ADD `_change_my_email_address_` VARCHAR( 150 ) NOT NULL , 
	ADD `_change_my_address_` VARCHAR( 150 ) NOT NULL , 
	ADD `_find_an_order_` VARCHAR( 100 ) NOT NULL , 
	ADD `_no_orders_found_` TEXT NOT NULL,
	ADD `_my_account_page_text_` TEXT NOT NULL ||




	UPDATE ms_store_language SET 
	_view_my_orders_='View My Orders', 
	_view_my_photos_='Find My Photos', 
	_change_password_='Change Password', 
	_change_my_email_address_='Change Email Address', 
	_change_my_address_='Change Name / Address', 
	_find_an_order_='Find An Order', 
	_no_orders_found_='No orders found.',
	_my_account_page_text_='Welcome to your account section.' ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}
	$sytist_version = $new_version;
}

if($sytist_version <= "0.5.8") {
	$new_version = "0.5.9";
	$uq = "
	CREATE TABLE IF NOT EXISTS `ms_new_accounts` (
	  `id` int(11) NOT NULL,
	  `first_name_ask` int(11) NOT NULL,
	  `first_name_req` int(11) NOT NULL,
	  `last_name_ask` int(11) NOT NULL,
	  `last_name_req` int(11) NOT NULL,
	  `address_ask` int(11) NOT NULL,
	  `address_req` int(11) NOT NULL,
	  `city_ask` int(11) NOT NULL,
	  `city_req` int(11) NOT NULL,
	  `state_ask` int(11) NOT NULL,
	  `state_req` int(11) NOT NULL,
	  `zip_ask` int(11) NOT NULL,
	  `zip_req` int(11) NOT NULL,
	  `country_ask` int(11) NOT NULL,
	  `country_req` int(11) NOT NULL,
	  `company_ask` int(11) NOT NULL,
	  `company_req` int(11) NOT NULL,
	  `phone_ask` int(11) NOT NULL,
	  `phone_req` int(11) NOT NULL,
	  `co_first_name_ask` int(11) NOT NULL,
	  `co_first_name_req` int(11) NOT NULL,
	  `co_last_name_ask` int(11) NOT NULL,
	  `co_last_name_req` int(11) NOT NULL,
	  `co_address_ask` int(11) NOT NULL,
	  `co_address_req` int(11) NOT NULL,
	  `co_city_ask` int(11) NOT NULL,
	  `co_city_req` int(11) NOT NULL,
	  `co_state_ask` int(11) NOT NULL,
	  `co_state_req` int(11) NOT NULL,
	  `co_zip_ask` int(11) NOT NULL,
	  `co_zip_req` int(11) NOT NULL,
	  `co_country_ask` int(11) NOT NULL,
	  `co_country_req` int(11) NOT NULL,
	  `co_company_ask` int(11) NOT NULL,
	  `co_company_req` int(11) NOT NULL,
	  `co_phone_ask` int(11) NOT NULL,
	  `co_phone_req` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8||


	INSERT INTO `ms_new_accounts` (`id`, `first_name_ask`, `first_name_req`, `last_name_ask`, `last_name_req`, `address_ask`, `address_req`, `city_ask`, `city_req`, `state_ask`, `state_req`, `zip_ask`, `zip_req`, `country_ask`, `country_req`, `company_ask`, `company_req`, `phone_ask`, `phone_req`, `co_first_name_ask`, `co_first_name_req`, `co_last_name_ask`, `co_last_name_req`, `co_address_ask`, `co_address_req`, `co_city_ask`, `co_city_req`, `co_state_ask`, `co_state_req`, `co_zip_ask`, `co_zip_req`, `co_country_ask`, `co_country_req`, `co_company_ask`, `co_company_req`, `co_phone_ask`, `co_phone_req`) VALUES
	(1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0)||

	ALTER TABLE `ms_store_language` 
	ADD `_my_account_` VARCHAR( 100 ) NOT NULL , 
	ADD `_company_` VARCHAR( 100 ) NOT NULL ||

	UPDATE ms_store_language SET 
	_my_account_='My Account', 
	_company_='Company Name' ||



	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}
	$sytist_version = $new_version;
}


if($sytist_version <= "0.5.9") {
	$new_version = "0.6.0";
	$uq = "
	ALTER TABLE `ms_language` 
	ADD `_request_access_` TEXT NOT NULL , 
	ADD `_request_access_text_` TEXT NOT NULL , 
	ADD `_request_access_send_` VARCHAR( 100 ) NOT NULL , 
	ADD `_request_access_sent_` TEXT NOT NULL , 
	ADD `_request_access_message_` TEXT NOT NULL ||

	UPDATE ms_language SET 
	_request_access_='Request access to this page', 
	_request_access_text_='Fill out this form to request access to this page and let us know the reason for your request. Thank you.',
	_request_access_send_ = 'Send Request', 
	 _request_access_sent_='Your request has been sent. We will get back to you as soon as possible.' , 
	_request_access_message_='Message'||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}
	$sytist_version = $new_version;
}




if($sytist_version <= "0.6.0") {
	$new_version = "0.6.1";
	$uq = "
	ALTER TABLE `ms_language` 

	ADD `_create_account_button_` VARCHAR( 100 ) NOT NULL , 
	ADD `_email_already_exists_` TEXT NOT NULL, 
	ADD `_passwords_do_not_match_` TEXT NOT NULL, 
	ADD `_email_addresses_do_not_match_` TEXT NOT NULL, 
	ADD `_email_address_not_found_` TEXT NOT NULL, 
	ADD `_forgot_password_instructions_` TEXT NOT NULL, 
	ADD `_forgot_password_send_` VARCHAR( 50 ) NOT NULL , 
	ADD `_log_in_button_` VARCHAR( 50 ) NOT NULL , 
	ADD `_log_in_incorrect_` TEXT NOT NULL, 
	ADD `_your_account_has_been_created_` TEXT NOT NULL, 
	ADD `_new_password_` VARCHAR( 50 ) NOT NULL , 
	ADD `_retype_new_password_` VARCHAR( 70 ) NOT NULL , 
	ADD `_current_email_` VARCHAR( 70 ) NOT NULL , 
	ADD `_enter_new_email_` VARCHAR( 70 ) NOT NULL , 
	ADD `_retype_new_email_` VARCHAR( 70 ) NOT NULL , 
	ADD `_update_` VARCHAR( 50 ) NOT NULL , 
	ADD `_my_orders_` VARCHAR( 70 ) NOT NULL , 
	ADD `_check_your_email_forgot_password_` TEXT NOT NULL, 
	ADD `_your_password_has_been_updated_` TEXT NOT NULL, 
	ADD `_your_email_has_been_updated_` TEXT NOT NULL, 
	ADD `_your_info_has_been_updated_` TEXT NOT NULL,
	ADD `_forgot_password_` VARCHAR( 70 ) NOT NULL ||




	UPDATE ms_language SET 
	_create_account_button_='Create Account',
	_email_already_exists_='That email address already exists. Please log into your account using the log in form.',
	_passwords_do_not_match_='Your passwords did not match. Re-enter your new password.',
	_email_addresses_do_not_match_='Your email addresses did not match. Please check your email address.',
	_email_address_not_found_='Email address not found in our system.',
	_forgot_password_instructions_='Enter in your email address to reset your password.',
	_forgot_password_send_='Send',
	_log_in_button_='Log In',
	_log_in_incorrect_='Log in incorrect',
	_your_account_has_been_created_='Your account has been created',
	_new_password_='Enter new password',
	_retype_new_password_='Re-type new password',
	_current_email_='Current Email',
	_enter_new_email_='Enter new email address',
	_retype_new_email_='Re-type new email address',
	_update_='Update',
	_my_orders_='My Orders',
	_check_your_email_forgot_password_='Check your email for your password.',
	_your_password_has_been_updated_='Your password has been updated',
	_your_email_has_been_updated_='Your email address has been updated',
	_your_info_has_been_updated_='Your information has been updated',
	_forgot_password_='Forgot Password'||


	INSERT INTO ms_menu_links SET link_text='Redeem Print Credit', link_main='printcredit', link_status='0', link_order='12', link_location='shop', link_shop_menu='shopmenu', link_no_delete='1' ||
	ALTER TABLE `ms_cart` ADD `cart_print_credit` VARCHAR( 100 ) NOT NULL ||

	ALTER TABLE `ms_language` 
	ADD `_redeem_print_credit_` VARCHAR( 100 ) NOT NULL , 
	ADD `_redeem_print_credit_instructions_` TEXT NOT NULL , 
	ADD `_redeem_print_credit_code_` VARCHAR( 100 ) NOT NULL , 
	ADD `_redeem_print_credit_button_` VARCHAR( 100 ) NOT NULL , 
	ADD `_redeem_print_credit_good_` VARCHAR( 100 ) NOT NULL , 
	ADD `_redemm_print_credit_add_photos_` TEXT NOT NULL , 
	ADD `_print_credit_already_in_cart_` TEXT NOT NULL , 
	ADD `_print_credit_not_valid_` TEXT NOT NULL,
	ADD `_print_credit_` VARCHAR( 100 ) NOT NULL ||

	UPDATE ms_language SET 
	_redeem_print_credit_='Redeem Print Credit',
	_redeem_print_credit_instructions_='If you have been given a print credit, please enter the code below to redeem your print credit.',
	_redeem_print_credit_code_='Code', 
	_redeem_print_credit_button_='Redeem',
	_redeem_print_credit_good_='Print Credit Successfully Redeemed', 
	_redemm_print_credit_add_photos_='Now when viewing your photos, you will see the option on the right of the screen to select photos for your print credit.',
	_print_credit_already_in_cart_='This print credit is already in your shopping cart',
	_print_credit_not_valid_='Sorry, but that Print Credit code is not valid.',
	_print_credit_='Print Credit' ||


	ALTER TABLE `ms_language` 
	ADD `_can_not_have_same_first_last_name_` TEXT NOT NULL , 
	ADD `_empty_fields_` TEXT NOT NULL ||

	UPDATE ms_language SET 
	_can_not_have_same_first_last_name_='Sorry, but your first & last name can not be the same.',
	_empty_fields_='You have required fields empty'||

	CREATE TABLE IF NOT EXISTS `ms_print_credits` (
	  `pc_id` int(11) NOT NULL AUTO_INCREMENT,
	  `pc_name` varchar(255) NOT NULL,
	  `pc_package` int(11) NOT NULL,
	  `pc_code` varchar(100) NOT NULL,
	  `pc_descr` text NOT NULL,
	  `pc_expire` date NOT NULL,
	  `pc_order` int(11) NOT NULL,
	  `pc_ship` int(11) NOT NULL,
	  PRIMARY KEY (`pc_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}
	$sytist_version = $new_version;
}




if($sytist_version <= "0.6.1") {
	$new_version = "0.6.2";
	$uq = "
	ALTER TABLE `ms_cart` ADD `cart_credit` DECIMAL( 10, 2 ) NOT NULL ||
	ALTER TABLE `ms_packages` ADD `package_limit` INT NOT NULL ,
	ADD `package_credit` DECIMAL( 10, 2 ) NOT NULL ||

	ALTER TABLE `ms_language`  ADD `_collection_credit_cart_message_` TEXT NOT NULL ||
	UPDATE ms_language SET  _collection_credit_cart_message_=' credit included to use toward purchasing other photos.'||

	ALTER TABLE `ms_language`  ADD `_credit_` VARCHAR( 100 ) NOT NULL ||
	UPDATE ms_language SET  _credit_='Credit'||

	ALTER TABLE `ms_language`  ADD `_poses_` VARCHAR( 100 ) NOT NULL,
	ADD `_max_poses_error_message_` TEXT NOT NULL ||

	UPDATE ms_language SET  _poses_='poses', _max_poses_error_message_='You have reached the maximum poses for this collection. You will need to either select a pose that exists in your collection, or remove a pose from your collection to select more photos.'||

	ALTER TABLE `ms_cart` ADD `cart_package_photo_extra` INT NOT NULL ||
	ALTER TABLE `ms_packages_connect` ADD `con_extra` INT NOT NULL ,
	ADD `con_extra_price` DECIMAL( 10, 2 ) NOT NULL ||
	ALTER TABLE `ms_packages_connect` ADD `con_extra_price_new_photo` DECIMAL( 10, 2 ) NOT NULL ||



	ALTER TABLE `ms_language`  ADD `_poses_selected_` VARCHAR( 150 ) NOT NULL,
	 ADD `_package_extra_photos_title_` TEXT NOT NULL,
	 ADD `_package_extra_photos_` TEXT NOT NULL||

	UPDATE ms_language SET  _poses_selected_='Poses Selected',
	_package_extra_photos_title_='Purchase Additional Photos',
	_package_extra_photos_='You can purchase additional photos at a discounted price below.'||


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}
	$sytist_version = $new_version;
}


if($sytist_version <= "0.6.2") {
	$new_version = "0.6.3";
	$uq = "
	ALTER TABLE `ms_stats_site_visitors` CHANGE `st_refer` `st_refer` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ||

	ALTER TABLE `ms_language`  ADD `_free_` VARCHAR( 100 ) NOT NULL,
	 ADD `_pending_` VARCHAR( 100 ) NOT NULL,
	 ADD `_completed_` VARCHAR( 100 ) NOT NULL ||

	UPDATE ms_language SET  
	_free_='Free',
	_pending_='Pending', 
	_completed_='Completed' ||



	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}
	$sytist_version = $new_version;
}



if($sytist_version <= "0.6.3") {
	$new_version = "0.6.4";
	$uq = "
	ALTER TABLE `ms_settings` ADD `auto_check_updates` INT NOT NULL ||
	UPDATE ms_settings SET auto_check_updates='1'||
	ALTER TABLE `ms_blog_categories` ADD `cat_default_private` INT NOT NULL ||
	ALTER TABLE `ms_language`  ADD `_unable_to_find_order_` VARCHAR( 150 ) NOT NULL,
	 ADD `_free_download_login_message_` TEXT NOT NULL|| 
	UPDATE ms_language SET  
	_unable_to_find_order_='Unable to find order' ,
	_free_download_login_message_='You must be logged in to use the free download feature. <a href=\"/index.php?view=account\">Click here to log in</a> or <a href=\"/index.php?view=newaccount\">create an account</a>.' ||
	ALTER TABLE `ms_photo_products_lists` ADD `list_require_login` INT NOT NULL ||
	ALTER TABLE `ms_language`  ADD `_login_to_buy_photos_` TEXT NOT NULL,  ADD `_return_to_last_gallery_page_` TEXT NOT NULL|| 
	UPDATE ms_language SET  
	_login_to_buy_photos_='Please <a href=\"/index.php?view=account\">log in</a> or <a href=\"/index.php?view=newaccount\">create an account</a> to purchase photos.' , _return_to_last_gallery_page_='Click here if you would like to return to ' ||
	ALTER TABLE `ms_photo_products` ADD `pp_collapse_options` INT NOT NULL ||
	UPDATE  `ms_photo_products`  SET pp_collapse_options='1' ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}
	$sytist_version = $new_version;
}

if($sytist_version <= "0.6.4") {
	$new_version = "0.6.5";
	$uq = "
	ALTER TABLE `ms_language`  ADD `_select_option_` VARCHAR( 150 ) NOT NULL || 
	UPDATE ms_language SET  _select_option_='Please select'|| 
	UPDATE ms_store_language SET  _package_instructions_='Click <span class=\"the-icons icon-check\"></span> next to the product you want to add this photo to.'|| 

	INSERT INTO ms_payment_options SET pay_name='Pay Offline 2 (mail, in person, cash, etc...)', pay_descr='This option gives the customer information on how to pay by mail, in person, etc...', pay_text='Place Order', pay_option='payoffline2', pay_order='11', pay_title='Pay offline', pay_description='To pay offline, click the Place Order button below. You will be shown the address to send payment to on your order.', pay_short_description='This option gives the customer information on how to pay by mail, in person, etc...', pay_dev_status='1', pay_form='payoffline2', pay_offline_descr='Please send payment payable to COMPANY NAME to:

	COMPANY NAME
	ADDRESS
	CITY STATE ZIP

	Be sure you make note of your order number.'||

	ALTER TABLE `ms_language`  ADD `_clear_favorites_confirm_` TEXT NOT NULL,
	 ADD `_empty_cart_confirm_` TEXT NOT NULL,
	 ADD `_loading_` VARCHAR( 50 ) NOT NULL,
	  ADD `_or_` VARCHAR( 50 ) NOT NULL|| 

	UPDATE ms_language SET  _clear_favorites_confirm_='Are you sure you want to remove all your favorites? ', 
	_empty_cart_confirm_='Are you sure you want to empty your shopping cart?', _loading_='Loading', _or_='or'|| 

	ALTER TABLE `ms_cart` ADD `cart_notes` TEXT NOT NULL ||
	ALTER TABLE `ms_orders` ADD `order_notes` TEXT NOT NULL ||

	ALTER TABLE `ms_language`    ADD `_add_notes_` VARCHAR( 100 ) NOT NULL,  ADD `_update_cart_note_` VARCHAR( 100 ) NOT NULL|| 
	UPDATE ms_language SET  _add_notes_='Make a note ',_update_cart_note_='update note'|| 

	ALTER TABLE `ms_photo_products_lists` ADD `list_allow_notes` INT NOT NULL ||

	ALTER TABLE `ms_cart` ADD `cart_allow_notes` INT NOT NULL ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}
	$sytist_version = $new_version;
}


if($sytist_version <= "0.6.5") {
	$new_version = "0.6.6";
	$uq = "
	ALTER TABLE `ms_emails` ADD `email_id_name` VARCHAR( 20 ) NOT NULL ||

	CREATE TABLE IF NOT EXISTS `ms_view_page` (
	  `v_id` int(11) NOT NULL AUTO_INCREMENT,
	  `v_person` int(11) NOT NULL,
	  `v_page` int(11) NOT NULL,
	  `v_date` datetime NOT NULL,
	  PRIMARY KEY (`v_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ||



	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}
	insertSQL("ms_emails", "
	email_codes='[FIRST_NAME] = Customer\'s first name
	[LAST_NAME] = Customer\'s last name
	[EMAIL_ADDRESS] = Customer\'s email address
	[URL] = Link to your website
	[LINK] = Link to the page
	[PAGE_TITLE] = Page title
	[PASSWORD] = Password to the page. 
	[EXPIRATION_DATE] = Expiration date
	[WEBSITE_NAME] = The name of your website',
	email_message='
	<p><span style=\"font-size: 14pt;\">Hello [FIRST_NAME]</span>,</p>
	
	<p>We wanted to let you know the gallery \"[PAGE_TITLE]\" will be expiring on [EXPIRATION_DATE].</p>
	
	<p>[LINK]</p>
	<p>Thank you,<br>[WEBSITE_NAME]<br>[URL]</p>
	
	
	',
	email_name='Expiring Gallery / Page',
	email_descr='This is the default email for sending information about an expiring gallery / page.',
	email_subject='Information about [PAGE_TITLE] at [WEBSITE_NAME]',
	email_no_delete='1',
	email_id_name='expireemail' ");
	$sytist_version = $new_version;

}


if($sytist_version <= "0.6.6") {
	$new_version = "0.6.7";
	$uq = "
	ALTER TABLE `ms_menu_links` ADD `link_logged_in` INT NOT NULL ||
	UPDATE ms_menu_links SET link_logged_in='1' WHERE link_main='printcredit'||

	INSERT INTO ms_share SET share_name='Google+', share_descr='Add Google+ button', share_id_name='google' || 

	ALTER TABLE `ms_language`  ADD `_remove_` VARCHAR( 100 ) NOT NULL, 
	ADD `_one_moment_please_` VARCHAR( 150 ) NOT NULL || 

	UPDATE ms_language SET  _remove_='Remove', _one_moment_please_='One Moment Please' || 

	UPDATE ms_social_links SET link_text='Facebook' WHERE link_name='facebook' || 
	INSERT INTO ms_social_links SET link_text='Google+', link_order='5', link_name='gplus' || 
	ALTER TABLE `ms_blog_photos` ADD `bp_sub_preview` INT NOT NULL ||

	ALTER TABLE `ms_blog_categories` ADD `cat_default_status` INT NOT NULL ||

	UPDATE ms_blog_categories SET cat_default_status='2' || 
	ALTER TABLE `ms_people` ADD `p_price_list` INT NOT NULL ||

	ALTER TABLE `ms_photo_products_lists` ADD `list_min_order` DECIMAL( 10, 2 ) NOT NULL ||
	ALTER TABLE `ms_cart` ADD `cart_min_order` DECIMAL( 10, 2 ) NOT NULL ||

	ALTER TABLE `ms_language`  
	ADD `_proof_approve_` VARCHAR( 100 ) NOT NULL, 
	ADD `_proof_approved_` VARCHAR( 100 ) NOT NULL, 
	ADD `_proof_change_` VARCHAR( 100 ) NOT NULL, 
	ADD `_proof_revise_message_` TEXT NOT NULL, 
	ADD `_proof_revision_requested_` TEXT NOT NULL, 
	ADD `_proof_revise_save_` VARCHAR( 100 ) NOT NULL, 
	ADD `_proof_approve_tip_` TEXT NOT NULL, 
	ADD `_proof_empty_comment_message_` TEXT NOT NULL, 
	ADD `_proof_pending_review_` VARCHAR( 100 ) NOT NULL, 
	ADD `_proof_reviewed_` VARCHAR( 100 ) NOT NULL, 
	ADD `_proof_submit_review_` TEXT NOT NULL, 
	ADD `_proof_admin_pending_` TEXT NOT NULL, 
	ADD `_proof_project_closed_` TEXT NOT NULL, 
	ADD `_proof_pending_your_review_` TEXT NOT NULL, 
	ADD `_proof_my_proofs_` VARCHAR( 150 ) NOT NULL, 
	ADD `_proof_revise_` VARCHAR( 150 ) NOT NULL || 

	UPDATE ms_language SET  
	_proof_approve_='Approve',
	_proof_revise_='Request Revision',
	_proof_approved_='Approved',
	_proof_change_='change',
	_proof_revise_message_='Please enter a comment about the revision',
	_proof_revision_requested_='Revision requested',
	_proof_revise_save_='Save',
	_proof_approve_tip_='Tip: You can press A on your keyboard to Approve',
	_proof_empty_comment_message_='Please enter a comment.',
	_proof_pending_review_='Pending Review',
	_proof_reviewed_='reviewed',
	_proof_submit_review_='Please submit your review',
	_proof_admin_pending_='Project currently pending administration review',
	_proof_project_closed_='This project is closed',
	_proof_pending_your_review_='This project is pending your review',
	_proof_my_proofs_='Review My Proofs' ||


	ALTER TABLE `ms_language`  
	ADD `_proof_review_complete_` TEXT NOT NULL, 
	ADD `_proof_review_complete_message_` TEXT NOT NULL, 
	ADD `_proof_send_review_` VARCHAR( 100 ) NOT NULL, 
	ADD `_proof_cancel_send_review_` VARCHAR( 100 ) NOT NULL, 
	ADD `_proof_review_submited_message_` TEXT NOT NULL, 
	ADD `_proof_close_review_complete_` VARCHAR( 100 ) NOT NULL|| 

	UPDATE ms_language SET  
	_proof_review_complete_='Please Submit Your Review',
	_proof_review_complete_message_='You have reviewed all files in this project. Please click submit review to let us know you are done and leave a message if needed.',
	_proof_send_review_='Submit Review',
	_proof_cancel_send_review_='Cancel',
	_proof_review_submited_message_='Thank you for submitting your review. The project is now awaiting administrator action. You will be notified of any other actions needed.',
	_proof_close_review_complete_='Click here to close'|| 
	ALTER TABLE `ms_blog_categories` ADD `cat_req_login` INT NOT NULL ||


	CREATE TABLE IF NOT EXISTS `ms_proofing` (
	  `proof_id` int(11) NOT NULL AUTO_INCREMENT,
	  `proof_date_id` int(11) NOT NULL,
	  `proof_pic_id` int(11) NOT NULL,
	  `proof_status` int(11) NOT NULL,
	  `proof_comment` text NOT NULL,
	  `proof_person` int(11) NOT NULL,
	  `proof_date` datetime NOT NULL,
	  PRIMARY KEY (`proof_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ||


	CREATE TABLE IF NOT EXISTS `ms_proofing_revisions` (
	  `rev_id` int(11) NOT NULL AUTO_INCREMENT,
	  `rev_main_pic` int(11) NOT NULL,
	  `rev_this_pic` int(11) NOT NULL,
	  `rev_date_id` int(11) NOT NULL,
	  `rev_prior_pic` int(11) NOT NULL,
	  PRIMARY KEY (`rev_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ||



	CREATE TABLE IF NOT EXISTS `ms_proofing_status` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `date_id` int(11) NOT NULL,
	  `person` int(11) NOT NULL,
	  `date` datetime NOT NULL,
	  `ip` varchar(30) NOT NULL,
	  `status` int(11) NOT NULL,
	  `notes` text NOT NULL,
	  `re_opened` datetime NOT NULL,
	  `closed` datetime NOT NULL,
	  `emailed_to` text NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ||



	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}

	insertSQL("ms_emails", "
	email_codes='[FIRST_NAME] = Customer\'s first name
	[LAST_NAME] = Customer\'s last name
	[EMAIL_ADDRESS] = Customer\'s email address
	[URL] = Link to your website
	[LINK] = Link to the page
	[PAGE_TITLE] = Page title
	[PASSWORD] = Password to the page. 
	[WEBSITE_NAME] = The name of your website
	[ACCOUNT_LINK] = Link to the My Acount  / Login Page
	[NEW_LOGIN_INFO] = When you have created a customer account in the admin and has a temp password, this will show them the temp password. If there is not a temp password, then it will show nothing for this. ',
	email_message='
	<p><span style=\"font-size: 14pt;\">Hello [FIRST_NAME]</span>,</p>
	
	<p>Your proofs are ready for your review and approval. Please log into your account at [ACCOUNT_LINK] and in the My Account section, click on \"[PAGE_TITLE]\" under Review My Proofs.</p>
	
	[NEW_LOGIN_INFO]
	<p>Please review each one and select Approve or if you need a revision select Revise and give us a reason why.</p>
	
	<p>Thank you,<br>[WEBSITE_NAME]<br>[URL]</p>
	
	
	',
	email_name='Review Your Proofs Email',
	email_descr='This is the default email for sending information about a proofing project.',
	email_subject='[FIRST_NAME], Your Proofs Are Ready To Review [WEBSITE_NAME]',
	email_no_delete='1',
	email_id_name='viewproofs' ");


	insertSQL("ms_emails", "
	email_codes='[FIRST_NAME] = Customer\'s first name
	[LAST_NAME] = Customer\'s last name
	[EMAIL_ADDRESS] = Customer\'s email address
	[URL] = Link to your website
	[LINK] = Link to the page
	[PAGE_TITLE] = Page title
	[PASSWORD] = Password to the page. 
	[WEBSITE_NAME] = The name of your website
	[ACCOUNT_LINK] = Link to the My Acount  / Login Page ',
	email_message='
	<p><span style=\"font-size: 14pt;\">Hello [FIRST_NAME]</span>,</p>
	
	<p>We have uploaded revisions for your project \"[PAGE_TITLE]\" . Please log into your account at [ACCOUNT_LINK] and in the My Account section, click on \"[PAGE_TITLE]\" under Review My Proofs.</p>
	
	<p>Please review each one and select Approve or if you need a revision select Revise and give us a reason why.</p>
	
	<p>Thank you,<br>
	[WEBSITE_NAME]
	<br>[URL]</p>
	
	
	',
	email_name='Review Your Revised Proofs Email',
	email_descr='This is the default email for sending information about a proofing project that has been revised.',
	email_subject='[FIRST_NAME], Your Revised Proofs Are Ready To Review [WEBSITE_NAME]',
	email_no_delete='1',
	email_id_name='viewproofsrevised' ");

	insertSQL("ms_emails", "
	email_codes='[FIRST_NAME] = Customer\'s first name
	[LAST_NAME] = Customer\'s last name
	[EMAIL_ADDRESS] = Customer\'s email address
	[URL] = Link to your website
	[LINK] = Link to the page
	[PAGE_TITLE] = Page title
	[PASSWORD] = Password to the page. 
	[WEBSITE_NAME] = The name of your website
	[ACCOUNT_LINK] = Link to the My Acount  / Login Page ',
	email_message='
	<p><span style=\"font-size: 14pt;\">Hello [FIRST_NAME]</span>,</p>
	
	<p>This is to inform you the project  \"[PAGE_TITLE]\" is now closed and will be finishing up shortly.</p>
	
	<p>Thank you for your participation and have a nice day.</p>
	<p>Thank you,<br>[WEBSITE_NAME]<br>[URL]</p>
	
	
	',
	email_name='Proofs Closed Project Email',
	email_descr='This is the default email for sending to client when a proofing project is completed.',
	email_subject='[FIRST_NAME], Project [PAGE_TITLE] is Completed at [WEBSITE_NAME]',
	email_no_delete='1',
	email_id_name='viewproofsclosed' ");


	insertSQL("ms_emails", "
	email_codes='[FIRST_NAME] = Customer\'s first name
	[LAST_NAME] = Customer\'s last name
	[EMAIL_ADDRESS] = Customer\'s email address
	[URL] = Link to your website
	[LINK] = Link to the page
	[PAGE_TITLE] = Page title
	[PASSWORD] = Password to the page. 
	[WEBSITE_NAME] = The name of your website
	[TOTAL_APPROVED] =total approved
	[TOTAL_REVISIONS] = total revisions
	[TOTAL_REJECTED] = total rejected',
	email_message='
	
	<p>This is to inform you that [FIRST_NAME] [LAST_NAME] has finished reviewing the  project  \"[PAGE_TITLE]\".</p>
	
	<p>[TOTAL_APPROVED] approved</p>
	<p>[TOTAL_REVISIONS] revisions</p>
	<p>[TOTAL_REJECTED] rejected</p>

	<p>Log into your admin to view the project.</p>
	
	<p>
	<p><span style=\"font-size: 12pt;\">[WEBSITE_NAME]</span></p>
	<p>[URL]</p>
	
	
	',
	email_name='Proofs - Sent to admin when client done reviewing',
	email_descr='This is the default email sent to you when a customer finished reviewing a proofing project.',
	email_subject='[FIRST_NAME] has finished reviewing the proofing project [PAGE_TITLE] ',
	email_no_delete='1',
	email_id_name='viewproofsadmin' ");
	$sytist_version = $new_version;

}



if($sytist_version <= "0.6.7") {
	$new_version = "0.6.8";
	$uq = "
	CREATE TABLE IF NOT EXISTS `ms_pre_register` (
	  `reg_id` int(11) NOT NULL AUTO_INCREMENT,
	  `reg_email` varchar(255) NOT NULL,
	  `reg_first_name` varchar(255) NOT NULL,
	  `reg_last_name` varchar(255) NOT NULL,
	  `reg_date_id` int(11) NOT NULL,
	  `reg_date` datetime NOT NULL,
	  `reg_ip` varchar(30) NOT NULL,
	  `reg_person` int(11) NOT NULL,
	  PRIMARY KEY (`reg_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1272 ||

	ALTER TABLE `ms_language`  
	ADD `_pre_register_title_` TEXT NOT NULL, 
	ADD `_pre_register_preview_text_` TEXT NOT NULL, 
	ADD `_pre_register_message_` TEXT NOT NULL, 
	ADD `_pre_register_success_` TEXT NOT NULL, 
	ADD `_pre_register_send_` VARCHAR( 100 ) NOT NULL|| 

	UPDATE ms_language SET  
	_pre_register_title_='Sign up to be notified when this gallery becomes active.',
	_pre_register_message_='Fill out the form below to be notified by email when this gallery becomes available.',
	_pre_register_success_='Thank you! We will let you know when this gallery becomes available.',
	_pre_register_send_='Submit',
	_pre_register_preview_text_='This gallery is coming soon. Click here to get notified when this gallery becomes available.'|| 

	UPDATE ms_emails SET email_id_name='inviteprivate' WHERE email_id='20'|| 

	ALTER TABLE `ms_packages` ADD `package_buy_all` INT NOT NULL ,
	ADD `package_buy_all_product` INT NOT NULL ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}

	insertSQL("ms_emails", "
	email_codes='[FIRST_NAME] = Customer\'s first name
	[LAST_NAME] = Customer\'s first name
	[URL] = The URL to your store
	[WEBSITE_NAME] = The name of your website
	[LINK] = Link to the page
	[PAGE_TITLE] = Page title ',
	email_message='
	<p><span style=\"font-size: 14pt;\">Hello [FIRST_NAME]</span>,</p>
	
	<p>This is to let you know the gallery [PAGE_TITLE] is now available for viewing at the following link.</p>
	
	<p>[link]</p>
	<p>Thank you,<br>
	[WEBSITE_NAME]<br>
	[URL]</p>
	
	
	',
	email_name='Invite to view public page',
	email_descr='This is the default email for sending information about a page / gallery that does not require a password.',
	email_subject='[FIRST_NAME], [PAGE_TITLE] Gallery Now Available at [WEBSITE_NAME]',
	email_no_delete='1',
	email_id_name='invitepublic' ");



	$sytist_version = $new_version;
}




if($sytist_version <= "0.6.8") {
	$new_version = "0.6.9";
	$uq = "
	ALTER TABLE `ms_orders` ADD `order_photos_folder` VARCHAR( 255 ) NOT NULL ||

	ALTER TABLE `ms_product_options` ADD `opt_package` INT NOT NULL ||

	ALTER TABLE `ms_settings` ADD `backup_reminder` INT NOT NULL ||

	UPDATE ms_settings SET backup_reminder='1'|| 

	ALTER TABLE `ms_history` ADD `id` INT NOT NULL FIRST , ADD PRIMARY KEY ( `id` ) ||

	ALTER TABLE `ms_orders` ADD `order_open_status` INT NOT NULL ||

	CREATE TABLE IF NOT EXISTS `ms_order_status` (
	  `status_id` int(11) NOT NULL AUTO_INCREMENT,
	  `status_name` varchar(100) NOT NULL,
	  `status_descr` text NOT NULL,
	  `status_show_order` int(11) NOT NULL,
	  PRIMARY KEY (`status_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ||

	ALTER TABLE `ms_language`  
	ADD `_view_package_details_` TEXT NOT NULL|| 

	UPDATE ms_language SET  
	_view_package_details_='View details'||

	ALTER TABLE `ms_photo_products` ADD `pp_add_ship` DECIMAL( 10, 2 ) NOT NULL ||
	ALTER TABLE `ms_packages` ADD `package_add_ship` DECIMAL( 10, 2 ) NOT NULL ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}



	$sytist_version = $new_version;

}


if($sytist_version <= "0.6.9") {
	$new_version = "0.7.0";
	$uq = "
	ALTER TABLE `ms_cart` ADD INDEX ( `cart_session` , `cart_client` , `cart_order` , `cart_package_photo` ) ||

	ALTER TABLE `ms_sub_galleries` ADD INDEX ( `sub_date_id` )||
	ALTER TABLE `ms_blog_photos` ADD INDEX ( `bp_sub` )||

	ALTER TABLE `ms_blog_photos` ADD `bp_pl` INT NOT NULL ||


	ALTER TABLE `ms_store_language` 
	ADD `_compare_close_` VARCHAR( 100 ) NOT NULL , 
	ADD `_compare_` VARCHAR( 100 ) NOT NULL , 
	ADD `_compare_view_` VARCHAR( 100 ) NOT NULL , 
	ADD `_compare_remove_` VARCHAR( 100 ) NOT NULL , 
	ADD `_compare_photos_` VARCHAR( 100 ) NOT NULL , 
	ADD `_compare_clear_all_` VARCHAR( 100 ) NOT NULL , 
	ADD `_compare_close_and_clear_` VARCHAR( 100 ) NOT NULL ||

	UPDATE ms_store_language SET 
	_compare_close_='Close', 
	_compare_='Compare', 
	_compare_view_='View', 
	_compare_remove_='Remove', 
	_compare_photos_='Compare Photos', 
	_compare_clear_all_='clear all', 
	_compare_close_and_clear_='Clear All & Close' ||

	ALTER TABLE `ms_calendar` ADD `enable_compare` INT NOT NULL ||
	ALTER TABLE `ms_defaults` ADD `enable_compare` INT NOT NULL ||

	ALTER TABLE `ms_store_language` 
	ADD `_select_from_options_above_` VARCHAR( 150 ) NOT NULL ||

	UPDATE ms_store_language SET 
	_select_from_options_above_='Please select the options above'|| 

	ALTER TABLE `ms_calendar` ADD `find_my_photos` INT NOT NULL ||

	ALTER TABLE `ms_sub_galleries` ADD `sub_pass` VARCHAR( 255 ) NOT NULL ||

	ALTER TABLE `ms_my_pages` ADD `mp_sub_id` INT NOT NULL ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}



	$sytist_version = $new_version;
}


if($sytist_version <= "0.7.0") {
	$new_version = "0.7.1";
	$uq = "
	CREATE TABLE IF NOT EXISTS `ms_packages_buy_all` (
	  `ba_id` int(11) NOT NULL AUTO_INCREMENT,
	  `ba_package` int(11) NOT NULL DEFAULT '0',
	  `ba_from` int(11) NOT NULL DEFAULT '0',
	  `ba_to` int(11) NOT NULL DEFAULT '0',
	  `ba_price` decimal(10,2) NOT NULL DEFAULT '0.00',
	  `ba_order` int(11) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`ba_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000 ||

	ALTER TABLE `ms_packages` ADD `package_buy_all_price_type` INT NOT NULL ,
	ADD `package_buy_all_each` DECIMAL( 10, 2 ) NOT NULL ||

	ALTER TABLE `ms_cart` ADD `cart_package_buy_all` INT NOT NULL ||

	ALTER TABLE `ms_store_language` 
	ADD `_all_photos_from_` VARCHAR( 150 ) NOT NULL ||

	UPDATE ms_store_language SET 
	_all_photos_from_='All photos from'|| 

	ALTER TABLE `ms_store_language` 
	ADD `_with_key_word_` VARCHAR( 150 ) NOT NULL ||

	UPDATE ms_store_language SET 
	_with_key_word_='with key word'|| 

	ALTER TABLE `ms_store_language` 
	ADD `_buy_all_photos_` VARCHAR( 150 ) NOT NULL,
	ADD `_purchase_all_favorites_` VARCHAR( 150 ) NOT NULL||

	UPDATE ms_store_language SET 
	_buy_all_photos_='Buy All Photos',
	_purchase_all_favorites_='Purchase All Favorites'|| 


	ALTER TABLE `ms_cart` ADD `cart_buy_all_location` TEXT NOT NULL ||

	ALTER TABLE `ms_photo_products_groups` ADD `group_buy_all` INT NOT NULL ||

	ALTER TABLE `ms_photo_products_groups` ADD `group_buy_all_favs` TEXT NOT NULL ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}



	$sytist_version = $new_version;
}



if($sytist_version <= "0.7.1") {
	$new_version = "0.7.2";
	$uq = "
	UPDATE ms_pay_attempts SET all_data=''|| 
	ALTER TABLE `ms_photo_products` ADD `pp_no_discount` INT NOT NULL ||
	ALTER TABLE `ms_photo_products` ADD `pp_no_ship` INT NOT NULL ||
	ALTER TABLE `ms_packages` ADD `package_no_discount` INT NOT NULL ||
	ALTER TABLE `ms_promo_codes` ADD `code_date_id` INT NOT NULL ||

	ALTER TABLE `ms_store_language` 
	ADD `_early_bird_special_` VARCHAR( 200 ) NOT NULL,
	ADD `_early_bird_special_text_` TEXT NOT NULL ||

	UPDATE ms_store_language SET 
	_early_bird_special_='Early Bird Special',
	_early_bird_special_text_='Purchase your photos before [DATE] and receive a discount on orders [DISCOUNT_AMOUNT].'|| 
	ALTER TABLE `ms_orders` ADD `order_eb_discount` DECIMAL( 10, 2 ) NOT NULL ||
	ALTER TABLE `ms_pending_orders` ADD `order_eb_discount` DECIMAL( 10, 2 ) NOT NULL ||

	ALTER TABLE `ms_store_language`  ADD `_discount_off_` VARCHAR( 100 ) NOT NULL||
	UPDATE ms_store_language SET  _discount_off_='off'||

	ALTER TABLE `ms_store_language`  ADD `_or_more_` VARCHAR( 100 ) NOT NULL||
	UPDATE ms_store_language SET  _or_more_='or more'||

	ALTER TABLE `ms_blog_categories` ADD `cat_eb_days` INT NOT NULL ||

	UPDATE ms_blog_categories SET cat_eb_days='14'||

	ALTER TABLE `ms_store_settings` ADD `coupon_checkout_page` INT NOT NULL ||

	ALTER TABLE `ms_store_settings` ADD `login_checkout_page` INT NOT NULL ||

	UPDATE ms_store_settings SET login_checkout_page='1' ||
	
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}



	$sytist_version = $new_version;
}



if($sytist_version <= "0.7.2") {
	$new_version = "0.7.3";
	$uq = "
	ALTER TABLE `ms_blog_categories` ADD `cat_page_no_header` INT NOT NULL ||
	ALTER TABLE `ms_forms` ADD `form_success_url` VARCHAR( 255 ) NOT NULL ||
	ALTER TABLE `ms_billboards` ADD `bill_page` INT NOT NULL ||
	ALTER TABLE `ms_blog_categories` ADD `cat_page_billboard` INT NOT NULL ||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	if(!empty($_SESSION['installingon'])) {
		updateSQL("ms_history", "installingon='".$_SESSION['installingon']."' ");
		unset($_SESSION['installingon']);
	}

	$cat_name = "Page Masthead";

	$id = insertSQL("ms_billboards", "
	bill_name='".addslashes(stripslashes($cat_name))."', bill_slideshow='1',  bill_width='1024', bill_height='500', bill_seconds='5', bill_trans_time='400' , bill_loop='0', bill_transition='neatbbfade', bill_show_nav='1', bill_pic='pic_large', bill_fixed='1', bill_placement='insidecontainer',
	bill_border_color='242424',
	bill_border_size='1',
	bill_padding='0',
	bill_nav_background='e4e4e4',
	bill_nav_color='000000',
	bill_nav_border='242424',
	bill_limit='10',
	bill_page='1',
	bill_cat=''	");

	$sql = "INSERT INTO `ms_billboard_slides` ( `slide_pic`, `slide_order`, `slide_link`, `slide_billboard`, `slide_text1`, `slide_text2`, `slide_text_1_color`, `slide_text_1_size`, `slide_text_1_shadow`, `slide_text_1_font`, `slide_text_1_effect`, `slide_text_1_time`, `slide_text_2_color`, `slide_text_2_size`, `slide_text_2_shadow`, `slide_text_2_font`, `slide_text_2_effect`, `slide_text_2_time`, `slide_text_align`, `slide_top_margin`, `slide_left_margin`) VALUES( 7569, 2, '', '$id', 'id::1||color::FFFFFF||text::<p>[TITLE]<br></p>||font-family::Bitter||font-size::60||font-weight::normal||font-style::normal||text-shadow-h::1||text-shadow-v::1||text-shadow-b::2||text-shadow-c::000000||slide_text_1_time::600||slide_text_1_effect::fadeIn||x::2.7858225803594703||y::57.08002727665898||type::text|:|id::2||color::FFFFFF||text::<p>[TEXT]<br></p>||font-family::Wire One||font-size::35||font-weight::normal||font-style::normal||text-shadow-h::1||text-shadow-v::1||text-shadow-b::0||text-shadow-c::000000||slide_text_1_time::500||slide_text_1_effect::fadeIn||x::3.0038722501179973||y::73.7189299137119||type::text|:|', '', 'DDFF80', 61, '3px 2px 6px #000000', 'Advent Pro', 'fadeIn', 600, 'FFFFFF', 41, '1px 2px 3px #000000', 'BenchNine', 'slideDown', 1000, 'center', 50, 0);";
	if(@mysqli_query($dbcon,$sql)) {		} else {	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: $sql</div>"); 	}



	$sytist_version = $new_version;
}


if($sytist_version <= "0.7.3") {
	$new_version = "0.7.4";
	$uq = "
	ALTER TABLE `ms_photos` ADD `pic_large_width` INT NOT NULL ,
	ADD `pic_large_height` INT NOT NULL ,
	ADD `pic_small_width` INT NOT NULL ,
	ADD `pic_small_height` INT NOT NULL ||
	ALTER TABLE `ms_cart_options` ADD `co_pic_id` INT NOT NULL ||
	ALTER TABLE `ms_cart_options` ADD `co_taxable` INT NOT NULL ||

	CREATE TABLE IF NOT EXISTS `ms_image_options` (
	  `opt_id` int(11) NOT NULL AUTO_INCREMENT,
	  `opt_name` varchar(255) NOT NULL,
	  `opt_descr` text NOT NULL,
	  `opt_order` int(11) NOT NULL,
	  `opt_taxable` int(11) NOT NULL,
	  `opt_list` int(11) NOT NULL,
	  `opt_price` decimal(10,2) NOT NULL,
	  `opt_downloads` int(11) NOT NULL,
	  PRIMARY KEY (`opt_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ||

	ALTER TABLE `ms_calendar` CHANGE `date_feature_cat` `date_feature_cat` VARCHAR( 200 ) NOT NULL DEFAULT '0'||

	ALTER TABLE `ms_calendar` ADD `date_qty_descr` TEXT NOT NULL ||

	ALTER TABLE `ms_calendar` ADD `qty_min` INT NOT NULL ||

	ALTER TABLE `ms_store_language` 
	ADD `_min_qty_required_` VARCHAR( 255 ) NOT NULL||

	UPDATE ms_store_language SET 
	_min_qty_required_='There is a minimum quantity requirement of '||

	CREATE TABLE IF NOT EXISTS `ms_products_discounts` (
	  `dis_id` int(11) NOT NULL AUTO_INCREMENT,
	  `dis_prod` int(11) NOT NULL DEFAULT '0',
	  `dis_qty_from` int(11) NOT NULL DEFAULT '0',
	  `dis_qty_to` int(11) NOT NULL DEFAULT '0',
	  `dis_price` decimal(10,2) NOT NULL DEFAULT '0.00',
	  `dis_on` varchar(10) NOT NULL DEFAULT '',
	  PRIMARY KEY (`dis_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1012 ||

	ALTER TABLE `ms_store_language` 
	ADD `_image_option_selected_` VARCHAR( 150 ) NOT NULL||

	UPDATE ms_store_language SET 
	_image_option_selected_='Already selected for this image '||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}


	$sytist_version = $new_version;

}


if($sytist_version <= "0.7.4") {
	$new_version = "0.7.5";
	$uq = "
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "0.7.5") {
	$new_version = "0.7.6";
	$uq = "
	ALTER TABLE `ms_pre_register` ADD `toview` INT NOT NULL ||
	ALTER TABLE `ms_blog_categories` ADD `cat_require_email` INT NOT NULL ||
	ALTER TABLE `ms_blog_categories` ADD `cat_first_layout` INT NOT NULL ||
	ALTER TABLE `ms_calendar` ADD `home_first_layout` INT NOT NULL ||
	ALTER TABLE `ms_product_options` ADD `opt_photos` INT NOT NULL ||
	ALTER TABLE `ms_product_options_sel` ADD `sel_photos` INT NOT NULL ||
	ALTER TABLE `ms_photo_products_groups` ADD `group_store` INT NOT NULL ||
	ALTER TABLE `ms_photo_products_connect` ADD `pc_store_item` INT NOT NULL ||
	ALTER TABLE `ms_store_language` 
	ADD `_please_select_option_` TEXT NOT NULL||

	UPDATE ms_store_language SET 
	_please_select_option_='You have a required option not selected highlighed above. '||

	ALTER TABLE `ms_cart` ADD `cart_download_file` TEXT NOT NULL ||
	ALTER TABLE `ms_photo_products` ADD `pp_include_download` INT NOT NULL ||
	ALTER TABLE `ms_product_options` ADD `opt_download_size` INT NOT NULL ||
	ALTER TABLE `ms_product_options` ADD `opt_price_download` DECIMAL( 10, 2 ) NOT NULL ||
	ALTER TABLE `ms_cart_options` ADD `co_download` INT NOT NULL ||
	ALTER TABLE `ms_cart_options` ADD `co_download_size` INT NOT NULL ||


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}




if($sytist_version <= "0.7.6") {
	$new_version = "0.7.7";
	$uq = "
	ALTER TABLE `ms_history` ADD `check_rotate` INT NOT NULL ||
	UPDATE ms_history SET check_rotate='1'|| 
	ALTER TABLE `ms_settings` ADD `ftp_host_name` VARCHAR( 255 ) NOT NULL ,
	ADD `ftp_user` VARCHAR( 255 ) NOT NULL ||
	ALTER TABLE `ms_settings` ADD `ftp_pass` VARCHAR( 255 ) NOT NULL ||

	ALTER TABLE `ms_settings` ADD `mysqli_lang` VARCHAR( 30 ) NOT NULL ||
	ALTER TABLE `ms_calendar` ADD `hide_sub_gals` INT NOT NULL ||

	ALTER TABLE `ms_photo_setup` ADD `zip_limit` INT NOT NULL ||
	UPDATE ms_photo_setup SET zip_limit='50' ||

	ALTER TABLE `ms_store_language` 
	ADD `_zip_files_` VARCHAR( 150 ) NOT NULL,
	ADD `_download_zip_files_text_` TEXT NOT NULL||

	UPDATE ms_store_language SET _zip_files_='zip files', _download_zip_files_text_='Click each number below to download that zip file' ||

	ALTER TABLE `ms_calendar` ADD `enable_time_blocks` INT NOT NULL ,
	ADD `from_time` INT NOT NULL ,
	ADD `to_time` INT NOT NULL ,
	ADD `search_length` INT NOT NULL ||

	ALTER TABLE `ms_photo_setup` ADD `thumb_limit` INT NOT NULL ||
	UPDATE ms_photo_setup SET thumb_limit='10'||

	ALTER TABLE `ms_photo_setup` ADD `resize_quality` INT NOT NULL ||
	UPDATE ms_photo_setup SET resize_quality='85'|| 

	ALTER TABLE `ms_store_language` 
	ADD `_event_time_` VARCHAR( 200 ) NOT NULL||

	UPDATE ms_store_language SET _event_time_='Any time during event' ||


	ALTER TABLE `ms_language` 
	ADD `_no_photos_found_` TEXT NOT NULL||

	UPDATE ms_language SET _no_photos_found_='No photos found' ||

	ALTER TABLE `ms_cart` ADD `cart_disable_download` INT NOT NULL ||
	ALTER TABLE `ms_photo_products` ADD `pp_disable_download` INT NOT NULL ||


	ALTER TABLE `ms_language` 
	ADD `_download_pending_` TEXT NOT NULL||

	UPDATE ms_language SET _download_pending_='Your download is currently pending. We will notify you when it becomes available.' ||
	ALTER TABLE `ms_settings` ADD `email_header` TEXT NOT NULL ,
	ADD `email_footer` TEXT NOT NULL ||

	ALTER TABLE `ms_blog_categories` ADD `cat_meta_title` VARCHAR( 255 ) NOT NULL ||

	ALTER TABLE `ms_comments_settings` ADD `fb_color` VARCHAR( 20 ) NOT NULL ||

	ALTER TABLE `ms_settings` ADD `order_emails` TEXT NOT NULL ||


	ALTER TABLE `ms_language` 
	ADD `_does_not_meet_min_order_` TEXT NOT NULL||

	UPDATE ms_language SET _does_not_meet_min_order_='Sorry, but your order does not meet the minimum order amount of ' ||


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}



	insertSQL("ms_emails", "
	email_codes='[FIRST_NAME] = Customer\'s first name
	[LAST_NAME] = Customer\'s last name
	[EMAIL_ADDRESS] = Customer\'s email address
	[URL] = Link to your website
	[WEBSITE_NAME] = The name of your website
	[ACCOUNT_LINK] = Link to your My Account section',
	email_message='
	<p><span style=\"font-size: 14pt;\">Hello [FIRST_NAME]</span>,</p>
	
	<p>This is to inform you your photos are now ready to download from your recent purchase.</p>
	
	<p>Please go to our website and log in and go to My Account and click on your order number to download your photos:</p>
	<p>[ACCOUNT_LINK]</p>
	
	<p>Thank you,<br>[WEBSITE_NAME]<br>[URL]</p>
	
	
	',
	email_name='Photos available for download',
	email_descr='This is the default email for sending information about downloading photos on an order if the photos are set to pending by default.',
	email_subject='Your Photos Are Available For Download - [WEBSITE_NAME]',
	email_no_delete='1',
	email_id_name='downloademail' ");

	$states = "Álava, Albacete, Alicante, Almería, Asturias, Ávila, Badajoz, Barcelona, Burgos, Cáceres, Cádiz, Cantabria, Castellón, Ciudad Real, Córdoba, A Coruña, Cuenca, Gerona, Granada, Guadalajara, Guipúzcoa, Huelva, Huesca, Islas Baleares, Jaén, León, Lérida, Lugo, Madrid, Málaga, Murcia, Baleares, Lugo, Madrid, Málaga, Murcia, Navarra, Orense, Palencia, Las Palmas, Pontevedra, La Rioja, Salamanca, Segovia, Sevilla, Soria, Tarragona, Santa Cruz de Tenerife, Teruel, Toledo, Valencia, Valladolid, Vizcaya, Zamora, Zaragoza";
	$states =  explode(",",$states);
	foreach($states AS $state) { 
		$state = trim($state);
		if(!empty($state)) { 
			$ck = doSQL("ms_states", "*", "WHERE state_name='".utf8_encode($state)."' AND state_country='Spain' ");
			if(empty($ck['state_id'])) { 
				insertSQL("ms_states", "state_name='".utf8_encode($state)."', state_abr='".utf8_encode($state)."', state_country='Spain', state_ship_to='1' ");
				// print "<li>".$state;
			}
		}
	}








	$sytist_version = $new_version;
}




if($sytist_version <= "0.7.7") {
	$new_version = "0.7.8";
	$uq = "
ALTER TABLE `ms_packages` ADD `package_buy_all_set_price` DECIMAL( 10, 2 ) NOT NULL ||
ALTER TABLE `ms_packages` ADD `package_internal_name` VARCHAR( 255 ) NOT NULL ||
ALTER TABLE `ms_photo_products` ADD `pp_no_display_dems` INT NOT NULL ||

DELETE FROM ms_states WHERE state_name='Sussex'||
INSERT INTO ms_states SET state_name='East Sussex', state_abr='East Sussex', state_country='United Kingdom', state_ship_to='1' ||
INSERT INTO ms_states SET state_name='West Sussex', state_abr='West Sussex', state_country='United Kingdom', state_ship_to='1' ||

UPDATE ms_orders SET order_open_status='0' WHERE order_status='1' || 
ALTER TABLE `ms_photo_products` ADD `pp_free_all` INT NOT NULL ||

ALTER TABLE `ms_language` ADD `_download_all_free_` TEXT NOT NULL, ADD `_download_free_now_button_` TEXT NOT NULL||

UPDATE ms_language SET _download_all_free_='Download All Photos', _download_free_now_button_='Download Now' ||

ALTER TABLE `ms_sub_galleries` ADD `sub_price_list` INT NOT NULL ||
ALTER TABLE `ms_calendar` ADD `date_credit` DECIMAL( 10, 2 ) NOT NULL ||
ALTER TABLE `ms_cart` ADD `cart_account_credit` DECIMAL( 10, 2 ) NOT NULL ||

ALTER TABLE `ms_language` ADD `_includes_` TEXT NOT NULL||

UPDATE ms_language SET _includes_='Includes' ||
ALTER TABLE `ms_blog_categories` ADD `cat_reg_amounts` TEXT NOT NULL ||
ALTER TABLE `ms_calendar` ADD `reg_person` INT NOT NULL ||
ALTER TABLE `ms_cart` ADD `cart_account_credit_for` INT NOT NULL ||
ALTER TABLE `ms_calendar` ADD `reg_event_date` DATE NOT NULL ||

ALTER TABLE `ms_language` ADD `_please_select_amount_` TEXT NOT NULL||
UPDATE ms_language SET _please_select_amount_='Please Select Amount' ||
ALTER TABLE `ms_credits` ADD `credit_reg_buyer_name` VARCHAR( 255 ) NOT NULL ||

ALTER TABLE `ms_cart` ADD `cart_reg_message` TEXT NOT NULL ,
ADD `cart_reg_message_name` VARCHAR( 255 ) NOT NULL ||

ALTER TABLE `ms_calendar` ADD `reg_goal` DECIMAL( 10, 2 ) NOT NULL ,
ADD `reg_stop_goal` INT NOT NULL ||
ALTER TABLE `ms_calendar` ADD `reg_message` TEXT NOT NULL ||

ALTER TABLE `ms_cart` ADD `cart_reg_no_display_amount` INT NOT NULL ||
ALTER TABLE `ms_credits` ADD `credit_reg_no_display_amount` INT NOT NULL ||
ALTER TABLE `ms_blog_categories` ADD `cat_reg_no_address` INT NOT NULL ||
ALTER TABLE `ms_credits` ADD `credit_reg_buyer_email` VARCHAR( 255 ) NOT NULL ||

ALTER TABLE `ms_blog_categories` ADD `cat_reg_default_text` TEXT NOT NULL ||

ALTER TABLE `ms_blog_categories` ADD `cat_price_list` INT NOT NULL ||


ALTER TABLE `ms_language` 
ADD `_no_registry_search_results_` TEXT NOT NULL,
ADD `_search_for_registry_` TEXT NOT NULL,
ADD `_registry_id_` VARCHAR(100) NOT NULL,
ADD `_view_my_registry_` TEXT NOT NULL,
ADD `_registry_goal_reached_` TEXT NOT NULL,
ADD `_registry_event_date_` TEXT NOT NULL,
ADD `_registry_anonymous_` TEXT NOT NULL,
ADD `_registry_do_not_show_amount_` TEXT NOT NULL,
ADD `_registry_your_display_name_` TEXT NOT NULL,
ADD `_registry_guestbook_message_` TEXT NOT NULL,
ADD `_registry_amount_hidden_` TEXT NOT NULL,
ADD `_registry_guestbook_` TEXT NOT NULL,
ADD `_registry_guestbook_no_results_` TEXT NOT NULL,
ADD `_registry_for_` TEXT NOT NULL,
ADD `_registry_edit_message_` TEXT NOT NULL||


UPDATE ms_language SET 
_no_registry_search_results_='No results for your search',
_search_for_registry_='Search For Registry',
_registry_id_='Registry ID',
_view_my_registry_='View My Registry',
_registry_goal_reached_='goal reached',
_registry_event_date_='Event Date',
_registry_anonymous_='(anonymous)',
_registry_do_not_show_amount_='Do not display my purchase amount in the public guestbook. By selecting this option only the registry owner will see the amount.',
_registry_your_display_name_='Your name to display in the guestbook (optional)',
_registry_guestbook_message_='Enter a message to display in the guestbook (optional)',
_registry_amount_hidden_='hidden',
_registry_guestbook_='Guestbook',
_registry_guestbook_no_results_='There are no results yet!',
_registry_for_='Registry for',
_registry_edit_message_='Edit this message'||

ALTER TABLE `ms_language` ADD `_registry_only_visible_` TEXT NOT NULL||

UPDATE ms_language SET _registry_only_visible_='(only visible to you)'||

ALTER TABLE `ms_language` ADD `_option_add_price_` VARCHAR(20) NOT NULL, ADD `_option_negative_price_` VARCHAR(20) NOT NULL||
UPDATE ms_language SET _option_add_price_='+', _option_negative_price_='-'||


INSERT INTO `ms_category_layouts` (`layout_id`, `layout_name`, `layout_html`, `layout_description`, `layout_css_id`, `layout_js_function`, `layout_width`, `layout_height`, `layout_photo_class`, `layout_photo_size`, `layout_photo_width`, `layout_photo_height`, `layout_spacing`, `layout_per_page`, `layout_preview_text_length`, `layout_type`, `layout_file`, `layout_default`, `layout_css`, `layout_no_delete`, `layout_folder`) VALUES
(100, 'Titles Only', '', 'This will only list the titles of the pages.', 'listing-standard', '', 0, 0, 'thumbnail', 'pic_mini', 0, 0, 0, 0, 0, 'listing', 'listing-registry-results.php', 0, '', 0, ''),
(101, 'Registry Page', '', 'This is the page that displays a registry.', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'listing-registry-page.php', 0, '#registryPage {}\r\n#registryPage .title { padding: 4px 0px; }\r\n#registryPage .eventdate{ padding: 4px 0px; }\r\n#registryPage .text { padding: 4px;  font-size: 17px; }\r\n#registryPage .regid { padding: 4px 0px; }\r\n#registryPage .goal { padding: 4px 0px; }\r\n#registryPage .shareonfb { padding: 4px 0px; }\r\n#registryPage .reginstructions{ padding: 4px 0px; }\r\n#registryPage .regguestbook{ padding: 4px 0px; }\r\n#registryPage .regaddtocart { padding: 4px 0px; }\r\n#registryPage .photos {  float: left; width: 50%;  }\r\n#registryPage .photos .inner { padding: 16px; } \r\n#registryPage .content { float: right; width: 50%;  }\r\n#registryPage .content .inner { padding: 16px; } \r\n', 0, '')||

ALTER TABLE `ms_calendar` ADD `date_paid_access` INT NOT NULL ||
ALTER TABLE `ms_cart` ADD `cart_credit_access` INT NOT NULL ||
ALTER TABLE `ms_credits` ADD `credit_date_id_only` INT NOT NULL ||
ALTER TABLE `ms_cart` ADD `cart_paid_access` INT NOT NULL ||



ALTER TABLE `ms_language` ADD `_purchase_access_for_` TEXT NOT NULL, ADD `_purchase_access_` TEXT NOT NULL, ADD `_purchase_access_message_` TEXT NOT NULL, ADD `_purchase_includes_credit_` TEXT NOT NULL, ADD `_purchase_view_page_` TEXT NOT NULL||

UPDATE ms_language SET _purchase_access_for_='Purchase access to', _purchase_access_='Purchase Access', _purchase_includes_credit_='credit included to use to purchase photo products', _purchase_access_message_='In order to view this gallery, you must first purchase access.', _purchase_view_page_='View This Page Now' ||

ALTER TABLE `ms_language` ADD `_registry_purchase_` TEXT NOT NULL,  ADD `_access_to_` TEXT NOT NULL||

UPDATE ms_language SET _registry_purchase_='Registry Purchase', _access_to_='Access to: '||


INSERT INTO `ms_emails` ( `email_name`, `email_descr`, `email_codes`, `email_from_email`, `email_from_name`, `email_subject`, `email_message`, `email_type`, `email_no_delete`, `email_download_descr`, `email_shipping_descr`, `email_paypal_pending`, `email_offline_pending`, `email_id_name`) VALUES('Your Registry Is Ready', 'This is the default email you send when you create a registry for a customer.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s first name\r\n[URL] = The URL to your store\r\n[WEBSITE_NAME] = The name of your website\r\n[URL] = Link to your website\r\n[LINK] = Link to the page\r\n[PAGE_TITLE] = Page title\r\n', '', '', '[FIRST_NAME], Your Registry is Set Up at [WEBSITE_NAME]', '<p>Hello [FIRST_NAME],</p><p>Your registry is now set up on our website! You can send your friends & family a direct link to the [PAGE_TITLE] <br> registry here.</p><p>[LINK]</p><p>You will receive an email notification each time someone makes a purchase on your registry.</p><p>Thank you for choosing [WEBSITE_NAME]!<br>[URL]</p>', 0, 1, '', '', '', '', 'registry')||
INSERT INTO `ms_emails` (`email_name`, `email_descr`, `email_codes`, `email_from_email`, `email_from_name`, `email_subject`, `email_message`, `email_type`, `email_no_delete`, `email_download_descr`, `email_shipping_descr`, `email_paypal_pending`, `email_offline_pending`, `email_id_name`) VALUES('Received Registry Gift', 'This is the email sent to a registry owner when someone makes a purchase.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s first name\r\n[URL] = The URL to your store\r\n[WEBSITE_NAME] = The name of your website \r\n[URL] = Link to your website\r\n[LINK] = Link to the page\r\n[PAGE_TITLE] = Page title\r\nBUYER_FIRST_NAME] = Registry buyer''s first \r\n[BUYER_LAST_NAME] = Registry buyer''s last name\r\n[BUYER_EMAIL] = Registry buyer''s email address\r\n[REGISTRY_PURCHASE_AMOUNT] = Amount purchased', '', '', '[FIRST_NAME], You have received a registry gift at [WEBSITE_NAME]!', '<p>Hello [FIRST_NAME],</p><p>You have received a gift on your registry at [WEBSITE_NAME] from [BUYER_FIRST_NAME] [BUYER_LAST_NAME] ([BUYER_EMAIL]) in the amount of [REGISTRY_PURCHASE_AMOUNT]! </p><p>To view your registry, go to the following link:&nbsp;<br>[LINK]</p><p>Have a great day!<br>[WEBSITE_NAME]<br>[URL]</p>', 0, 1, '', '', '', '', 'registrypurchase')||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}









	$sytist_version = $new_version;

}



if($sytist_version <= "0.7.8") {
	$new_version = "0.7.9";
	$uq = "

		ALTER TABLE `ms_css2` ADD `top_menu_bg_hover` VARCHAR( 10 ) NOT NULL ,
		ADD `top_menu_border_l` VARCHAR( 10 ) NOT NULL ,
		ADD `top_menu_border_r` VARCHAR( 10 ) NOT NULL ||
		ALTER TABLE `ms_css2` ADD `top_menu_side_borders` INT NOT NULL ||
		ALTER TABLE `ms_calendar` ADD `date_meta_title` VARCHAR( 255 ) NOT NULL ||
		ALTER TABLE `ms_css2` ADD `top_menu_button_transparent` INT NOT NULL ||

		ALTER TABLE `ms_product_subs` ADD `sub_pic_id` INT NOT NULL ||

		CREATE TABLE IF NOT EXISTS `ms_cookie_warn` (
		  `id` int(11) NOT NULL,
		  `message` text NOT NULL,
		  `reject_url` varchar(255) NOT NULL,
		  `approve_button` varchar(255) NOT NULL,
		  `reject_link` varchar(255) NOT NULL,
		  `cookie_status` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8||


		INSERT INTO `ms_cookie_warn` (`id`, `message`, `reject_url`, `approve_button`, `reject_link`, `cookie_status`) VALUES
		(1, 'This website uses cookies to improve your experience. We''ll assume you''re ok with that, but you can opt-out if you wish.', 'http://www.google.com', 'Accept', 'Leave', 0)||

		ALTER TABLE `ms_fb` ADD `fb_photo_share` TEXT NOT NULL ||


		DROP TABLE IF EXISTS `ms_show`||
		CREATE TABLE IF NOT EXISTS `ms_show` (
		  `show_id` int(11) NOT NULL AUTO_INCREMENT,
		  `catphotoratio` decimal(10,2) NOT NULL,
		  `mainphotoratio` decimal(10,2) NOT NULL,
		  `catsubrecent` int(11) NOT NULL,
		  `initialopacity` decimal(10,2) NOT NULL,
		  `hoveropacity` decimal(10,2) NOT NULL,
		  `main_full_screen` int(11) NOT NULL,
		  `main_link_spacing` int(11) NOT NULL,
		  `main_photo_bg_color` varchar(30) NOT NULL,
		  `slide_speed` int(11) NOT NULL,
		  `mainfeaturetimer` int(11) NOT NULL,
		  `feature_type` varchar(20) NOT NULL,
		  `feat_cat_id` int(11) NOT NULL,
		  `feat_page_id` int(11) NOT NULL,
		  `fullscreen_width` int(11) NOT NULL,
		  `feat_side_cats` varchar(255) NOT NULL,
		  `feat_main_cats` varchar(255) NOT NULL,
		  `feat_main_limit` int(11) NOT NULL,
		  `feat_side_limit` int(11) NOT NULL,
		  `feat_side_title` text NOT NULL,
		  `feat_side_text` text NOT NULL,
		  `feat_links` text NOT NULL,
		  `title_color` varchar(20) NOT NULL,
		  `title_size` int(11) NOT NULL,
		  `title_font` varchar(100) NOT NULL,
		  `title_textshadow` varchar(10) NOT NULL,
		  `default_feat` int(11) NOT NULL,
		  `enabled` int(11) NOT NULL,
		  `behind_text_color` varchar(10) NOT NULL,
		  `behind_text_opacity` decimal(10,2) NOT NULL,
		  `enable_side` int(11) NOT NULL,
		  `change_effect` varchar(20) NOT NULL,
		  `enable_nav` int(11) NOT NULL,
		  `logo_text` text NOT NULL,
		  `enable_logo` int(11) NOT NULL,
		  `loading_bg_color` varchar(20) NOT NULL,
		  `loading_font_color` varchar(20) NOT NULL,
		  `loading_text` text NOT NULL,
		  `logo_file` varchar(255) NOT NULL,
		  `font_size` int(11) NOT NULL,
		  `font_color` varchar(20) NOT NULL,
		  `font_textshadow` varchar(20) NOT NULL,
		  `text_title_width` int(11) NOT NULL,
		  `text_placement` varchar(10) NOT NULL,
		  `logo_placement` varchar(10) NOT NULL,
		  `show_mini_menu` int(11) NOT NULL,
		  `nav_placement` varchar(5) NOT NULL,
		  `sm_bg` varchar(10) NOT NULL,
		  `sm_bt` varchar(10) NOT NULL,
		  `sm_bb` varchar(10) NOT NULL,
		  `sm_font_color` varchar(10) NOT NULL,
		  `sm_font_size` int(11) NOT NULL,
		  `smh_bg` varchar(10) NOT NULL,
		  `smh_bt` varchar(10) NOT NULL,
		  `smh_font_color` varchar(10) NOT NULL,
		  `smh_bb` varchar(10) NOT NULL,
		  `sm_padding` int(11) NOT NULL,
		  `title_placement` varchar(10) NOT NULL,
		  `show_preview_text` int(11) NOT NULL,
		  `main_menu` int(11) NOT NULL,
		  `main_menu_placement` varchar(10) NOT NULL,
		  `main_menu_bg` varchar(10) NOT NULL,
		  `main_menu_font` text NOT NULL,
		  `main_menu_font_size` int(11) NOT NULL,
		  `nav_bg` varchar(10) NOT NULL,
		  `nav_color` varchar(10) NOT NULL,
		  `nav_font_size` int(11) NOT NULL,
		  `menu_icon` int(11) NOT NULL,
		  `show_cat_name` int(11) NOT NULL,
		  `sm_title_color` varchar(10) NOT NULL,
		  `sm_text_color` varchar(10) NOT NULL,
		  `sm_find_photos` int(11) NOT NULL,
		  `show_page_text` int(11) NOT NULL,
		  `disable_page_listing` int(11) NOT NULL,
		  `side_menu_photo_ratio` decimal(10,2) NOT NULL,
		  `side_menu_photo_width` int(11) NOT NULL,
		  `side_menu_photo_file` varchar(20) NOT NULL,
		  `side_title_placement` varchar(10) NOT NULL,
		  `side_show_date` int(11) NOT NULL,
		  `side_show_cat_name` int(11) NOT NULL,
		  `side_show_snippet` int(11) NOT NULL,
		  `side_snippet_length` int(11) NOT NULL,
		  `page_text_preview_length` int(11) NOT NULL,
		  `side_page_title_color` varchar(10) NOT NULL,
		  `side_page_title_hover` varchar(10) NOT NULL,
		  `nav_display_count` int(11) NOT NULL,
		  PRIMARY KEY (`show_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ||


		INSERT INTO `ms_show` (`show_id`, `catphotoratio`, `mainphotoratio`, `catsubrecent`, `initialopacity`, `hoveropacity`, `main_full_screen`, `main_link_spacing`, `main_photo_bg_color`, `slide_speed`, `mainfeaturetimer`, `feature_type`, `feat_cat_id`, `feat_page_id`, `fullscreen_width`, `feat_side_cats`, `feat_main_cats`, `feat_main_limit`, `feat_side_limit`, `feat_side_title`, `feat_side_text`, `feat_links`, `title_color`, `title_size`, `title_font`, `title_textshadow`, `default_feat`, `enabled`, `behind_text_color`, `behind_text_opacity`, `enable_side`, `change_effect`, `enable_nav`, `logo_text`, `enable_logo`, `loading_bg_color`, `loading_font_color`, `loading_text`, `logo_file`, `font_size`, `font_color`, `font_textshadow`, `text_title_width`, `text_placement`, `logo_placement`, `show_mini_menu`, `nav_placement`, `sm_bg`, `sm_bt`, `sm_bb`, `sm_font_color`, `sm_font_size`, `smh_bg`, `smh_bt`, `smh_font_color`, `smh_bb`, `sm_padding`, `title_placement`, `show_preview_text`, `main_menu`, `main_menu_placement`, `main_menu_bg`, `main_menu_font`, `main_menu_font_size`, `nav_bg`, `nav_color`, `nav_font_size`, `menu_icon`, `show_cat_name`, `sm_title_color`, `sm_text_color`, `sm_find_photos`, `show_page_text`, `disable_page_listing`, `side_menu_photo_ratio`, `side_menu_photo_width`, `side_menu_photo_file`, `side_title_placement`, `side_show_date`, `side_show_cat_name`, `side_show_snippet`, `side_snippet_length`, `page_text_preview_length`, `side_page_title_color`, `side_page_title_hover`, `nav_display_count`) VALUES
		(1, '0.00', '0.00', 2, '0.80', '1.00', 0, 0, 'FFFFFF', 1000, 5000, '', 0, 0, 75, '', '', 0, 0, '', '', '', 'FFFFFF', 40, '0', '000000', 1, 0, '2B2B2B', '0.00', 0, 'slide', 1, '', 0, 'FFFFFF', '787878', 'Loading', '', 18, 'FCFCFC', '000000', 100, '', 'tl', 0, 'tr', 'EBEBEB', 'FFFFFF', 'D4D4D4', '6E6E6E', 21, 'FFFFFF', 'FCFCFC', '000000', 'C9C9C9', 12, 'bl', 1, 0, '', '', '', 0, 'FFFFFF', '4D4D4D', 13, 0, 0, '1F1F1F', '707070', 0, 0, 0, '0.60', 40, 'pic_pic', 'below', 0, 0, 1, 100, 200, '636363', '000000', 1)||

		ALTER TABLE `ms_css2` ADD `header_100` INT NOT NULL||

		ALTER TABLE `ms_language` ADD `_expired_pages_in_cart_` TEXT NOT NULL ||
		UPDATE ms_language SET _expired_pages_in_cart_='Sorry, but you have photos in your cart that have expired. Please contact us if you wish to purchase the photos from the expired gallery.'||

		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "0.7.9") {
	$new_version = "0.8.0";
	$uq = "

	ALTER TABLE `ms_menu_links` ADD `link_no_click` INT NOT NULL ||

	ALTER TABLE `ms_photos` ADD `pic_amazon` INT NOT NULL ,
	ADD `pic_bucket` VARCHAR( 200 ) NOT NULL ,
	ADD `pic_bucket_folder` VARCHAR( 200 ) NOT NULL ||


	ALTER TABLE `ms_photo_setup` ADD `upload_amazon` INT NOT NULL ,
	ADD `awsAccessKey` VARCHAR( 255 ) NOT NULL ,
	ADD `awsSecretKey` VARCHAR( 255 ) NOT NULL ,
	ADD `awsBucketName` VARCHAR( 255 ) NOT NULL ||

	ALTER TABLE `ms_photos` ADD `pic_th_width` INT NOT NULL ,
	ADD `pic_th_height` INT NOT NULL ||

	ALTER TABLE `ms_photos` ADD `pic_filesize_large` INT NOT NULL ,
	ADD `pic_filesize_small` INT NOT NULL ,
	ADD `pic_filesize_thumb` INT NOT NULL ||

	ALTER TABLE `ms_photo_setup` ADD `enable_amazon` INT NOT NULL ||

	ALTER TABLE `ms_settings` ADD `amazon_endpoint` VARCHAR( 100 ) NOT NULL ||


	ALTER TABLE `ms_videos` ADD `vid_length` VARCHAR( 20 ) NOT NULL ||


		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}



if($sytist_version <= "0.8.0") {
	$new_version = "0.8.1";
	$uq = "

	ALTER TABLE `ms_photo_setup` ADD `discard_original` INT NOT NULL ||

	CREATE TABLE IF NOT EXISTS `ms_shipping_groups` (
	  `sg_id` int(11) NOT NULL AUTO_INCREMENT,
	  `sg_name` varchar(255) CHARACTER SET utf8 NOT NULL,
	  `sg_default` int(11) NOT NULL,
	  PRIMARY KEY (`sg_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ||


	INSERT INTO `ms_shipping_groups` (`sg_id`, `sg_name`, `sg_default`) VALUES (1, 'Default', 1)||
	UPDATE ms_shipping_methods SET method_group='1'||
	ALTER TABLE `ms_calendar` ADD `shipping_group` INT NOT NULL ||
	ALTER TABLE `ms_blog_categories` ADD `shipping_group` INT NOT NULL ||
	ALTER TABLE `ms_calendar` ADD `prod_max_one` INT NOT NULL ||


	ALTER TABLE `ms_language` ADD `_add_note_to_order_` TEXT NOT NULL||

	UPDATE ms_language SET _add_note_to_order_='Add a message to this order' ||
	ALTER TABLE `ms_pending_orders` ADD `order_notes` TEXT NOT NULL ||

	ALTER TABLE `ms_language` ADD `_customer_notes_` TEXT NOT NULL||
	UPDATE ms_language SET _customer_notes_='Customer Notes' ||

	ALTER TABLE `ms_pending_orders` ADD `order_notes` TEXT NOT NULL ||
	ALTER TABLE `ms_store_settings` ADD `checkout_notes` INT NOT NULL ||
	UPDATE ms_store_settings SET checkout_notes='1' ||
	ALTER TABLE `ms_fb` ADD `fb_lang` VARCHAR( 5 ) NOT NULL ||
	UPDATE ms_fb SET fb_lang='en_US'||


		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$orders = whileSQL("ms_orders", "*", "WHERE order_payment_status='Completed' AND order_payment_date='0000-00-00' ORDER BY order_id DESC ");
	while($order = mysqli_fetch_array($orders)) { 
		updateSQL("ms_orders", "order_payment_date='".$order['order_date']."' WHERE order_id='".$order['order_id']."' ");
	}


	$sytist_version = $new_version;
}



if($sytist_version <= "0.8.1") {
	$new_version = "0.8.2";
	$uq = "

		ALTER TABLE `ms_show` ADD `contain_portrait` INT NOT NULL ,
		ADD `contain_landscape` INT NOT NULL ||
		ALTER TABLE `ms_blog_photos` ADD `bp_clf` INT NOT NULL ,
		ADD `bp_clf_order` INT NOT NULL ||
		ALTER TABLE `ms_show` ADD `show_photos` VARCHAR( 20 ) NOT NULL ,
		ADD `show_photos_subs` VARCHAR( 20 ) NOT NULL ||
		ALTER TABLE `ms_language` ADD `_scroll_down_to_view_photos_` TEXT NOT NULL, ADD `_view_more_galleries_` TEXT NOT NULL||
		UPDATE ms_language SET _scroll_down_to_view_photos_='Scroll down to view photos' , _view_more_galleries_='View More Galeries'||
		ALTER TABLE `ms_show` ADD `show_photos_bg` VARCHAR( 10 ) NOT NULL ,
		ADD `show_photos_border` VARCHAR( 10 ) NOT NULL ,
		ADD `show_photos_text` VARCHAR( 10 ) NOT NULL ,
		ADD `show_photos_text_size` VARCHAR( 10 ) NOT NULL ,
		ADD `show_photos_text_shadow` VARCHAR( 10 ) NOT NULL ||

		UPDATE ms_show SET show_photos_text_shadow='FFFFFF', show_photos_text_size='19', show_photos_text='242424', show_photos_border='b4b4b4', show_photos_bg='e4e4e4'|| 
		ALTER TABLE `ms_calendar` ADD `page_fix_width` INT NOT NULL ||
		ALTER TABLE `ms_blog_categories` ADD `cat_max_width` INT NOT NULL ||


		ALTER TABLE `ms_language` ADD `_email_custom_crop_message_` TEXT NOT NULL||
		UPDATE ms_language SET _email_custom_crop_message_='A custom crop was selected for<br>this photo but not shown here.<br> Please refer to the order on the website<br> to view the cropping selected.'||

		ALTER TABLE `ms_free_downloads` ADD `free_zip` INT NOT NULL ,
		ADD `free_zip_number` INT NOT NULL ||
		ALTER TABLE `ms_packages` ADD `package_buy_all_no_sub_gal` INT NOT NULL ||
		ALTER TABLE `ms_packages` ADD `package_buy_all_subs_main` INT NOT NULL ||
		ALTER TABLE `ms_cart_options` ADD `co_disable_download` INT NOT NULL ||
		ALTER TABLE `ms_product_options` ADD `opt_disable_download` INT NOT NULL ||
		ALTER TABLE `ms_people` ADD `p_notes` TEXT NOT NULL ||


		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}


	$sytist_version = $new_version;
}


if($sytist_version <= "0.8.2") {
	$new_version = "0.8.3";
	$uq = "

		ALTER TABLE `ms_calendar` ADD `date_video_download` INT NOT NULL ||
		ALTER TABLE `ms_calendar` ADD `date_package` INT NOT NULL ||
		ALTER TABLE `ms_cart` ADD `cart_no_delete` INT NOT NULL ||
		ALTER TABLE `ms_calendar` ADD `date_package_pre_reg` INT NOT NULL ||
		ALTER TABLE `ms_cart` ADD `cart_pre_reg` INT NOT NULL ||

		ALTER TABLE `ms_language` ADD `_pre_paid_` TEXT NOT NULL, ADD `_select_prereg_page_` TEXT NOT NULL ||
		UPDATE ms_language SET _pre_paid_='(Prepaid)', _select_prereg_page_='Please select an event' ||



		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}


	$sytist_version = $new_version;
}


if($sytist_version <= "0.8.3") {
	$new_version = "0.8.4";
	$uq = "

		CREATE TABLE IF NOT EXISTS `ms_shares` (
		  `share_id` int(11) NOT NULL AUTO_INCREMENT,
		  `share_ip` varchar(20) NOT NULL,
		  `share_person` int(11) NOT NULL,
		  `share_page` int(11) NOT NULL,
		  `share_photo` int(11) NOT NULL,
		  `share_date` datetime NOT NULL,
		  `share_where` varchar(20) NOT NULL,
		  PRIMARY KEY (`share_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ||

		ALTER TABLE `ms_fb` ADD `share_type` INT NOT NULL , ADD `share_add_like` INT NOT NULL , ADD `share_text` TEXT NOT NULL ||
		ALTER TABLE `ms_fb` ADD `share_text_placement` VARCHAR(10) NOT NULL ||
		UPDATE ms_fb SET share_text='Share ', share_type='1' ||

		ALTER TABLE `ms_language` ADD `_mini_cart_bottom_text_` TEXT NOT NULL ||

		ALTER TABLE `ms_orders` ADD `order_subscription` INT NOT NULL ,
		ADD `order_due_date` DATE NOT NULL ||
		ALTER TABLE `ms_calendar` ADD `prod_subscription_price` DECIMAL( 10, 2 ) NOT NULL ||

		INSERT INTO `ms_category_layouts` (`layout_name`, `layout_html`, `layout_description`, `layout_css_id`, `layout_js_function`, `layout_width`, `layout_height`, `layout_photo_class`, `layout_photo_size`, `layout_photo_width`, `layout_photo_height`, `layout_spacing`, `layout_per_page`, `layout_preview_text_length`, `layout_type`, `layout_file`, `layout_default`, `layout_css`, `layout_no_delete`, `layout_folder`) VALUES
		('Stacked', '', 'Page listings are stacked similar to Pinterest. ', 'listing-stacked', '', 0, 0, 'thumbnail', 'pic_pic', 0, 0, 0, 0, 0, 'listing', 'listing-stacked-2.php', 0, '', 1, '')||


		ALTER TABLE `ms_blog_categories` ADD `cat_auto_populate` INT NOT NULL ||
		ALTER TABLE `ms_calendar` ADD `date_feature_auto_populate` INT NOT NULL ||

		ALTER TABLE `ms_language` ADD `_loading_more_pages_` TEXT NOT NULL ||
		UPDATE ms_language SET _loading_more_pages_='Loading more pages' ||
		ALTER TABLE `ms_language` ADD `_related_content_` TEXT NOT NULL ||
		UPDATE ms_language SET _related_content_='Related Content' ||

		CREATE TABLE IF NOT EXISTS `ms_order_export` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `fn` varchar(30) NOT NULL,
		  `fo` int(11) NOT NULL,
		  `fs` int(11) NOT NULL,
		  `fl` varchar(40) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=73 ||


		INSERT INTO `ms_order_export` (`id`, `fn`, `fo`, `fs`, `fl`) VALUES
		(1, 'order_id', 1, 1, 'OrderID'),
		(3, 'order_date', 3, 1, 'Date'),
		(4, 'order_total', 4, 1, 'OrderTotal'),
		(5, 'order_email', 5, 1, 'Email'),
		(6, 'order_first_name', 6, 1, 'FirstName'),
		(7, 'order_last_name', 7, 1, 'LastName'),
		(8, 'order_address', 8, 1, 'Address'),
		(9, 'order_address_2', 9, 0, 'Address2'),
		(10, 'order_city', 10, 1, 'City'),
		(11, 'order_state', 11, 1, 'State'),
		(12, 'order_zip', 12, 1, 'Zip'),
		(13, 'order_country', 13, 1, 'Country'),
		(14, 'order_ip', 14, 0, 'IPaddress'),
		(15, 'order_shipping', 15, 1, 'ShippingAmount'),
		(16, 'order_tax', 16, 1, 'TaxAmount'),
		(17, 'order_ship_first_name', 17, 1, 'ShipFirstName'),
		(18, 'order_ship_last_name', 18, 1, 'ShipLastName'),
		(19, 'order_ship_address', 19, 1, 'ShipAddress'),
		(20, 'order_ship_addres_2', 20, 1, 'ShipAddress2'),
		(21, 'order_ship_city', 21, 1, 'ShipCity'),
		(22, 'order_ship_state', 22, 1, 'ShipState'),
		(23, 'order_ship_zip', 23, 1, 'ShipZip'),
		(24, 'order_ship_country', 24, 1, 'ShipCountry'),
		(25, 'order_phone', 25, 1, 'Phone'),
		(26, 'order_fees', 26, 1, 'Fees'),
		(27, 'order_pay_type', 27, 1, 'PaymentType'),
		(28, 'order_payment_status', 28, 1, 'PaymentStatus'),
		(32, 'order_business_name', 32, 1, 'Company'),
		(40, 'order_ship_business', 40, 1, 'ShipCompany'),
		(41, 'order_discount', 41, 1, 'Discount'),
		(42, 'order_shipping_option', 42, 1, 'ShippingOption'),
		(43, 'order_sub_total', 43, 1, 'SubTotal'),
		(45, 'order_coupon_name', 45, 0, 'CouponName'),
		(46, 'order_tax_percentage', 46, 1, 'TaxPercentage'),
		(47, 'order_taxable_amount', 47, 1, 'TaxableAmount'),
		(48, 'order_shipped_by', 48, 0, 'ShippedBy'),
		(49, 'order_shipped_date', 49, 1, 'ShipDate'),
		(50, 'order_shipped_track', 50, 0, 'ShipTrack'),
		(53, 'order_vat', 53, 1, 'Vat'),
		(54, 'order_vat_percentage', 54, 1, 'VatPercentage'),
		(55, 'order_admin_notes', 55, 0, 'AdminNotes'),
		(57, 'order_payment', 57, 1, 'PaymentAmount'),
		(59, 'order_payment_date', 59, 1, 'PaymentDate'),
		(62, 'order_credit', 62, 1, 'Credit'),
		(67, 'order_notes', 67, 0, 'Notes'),
		(70, 'order_eb_discount', 70, 1, 'EarlyBirdDiscount')||

		ALTER TABLE `ms_store_settings` ADD `ship_group_extra_shipping_charge` INT NOT NULL ||

		ALTER TABLE `ms_calendar` ADD `video_location` INT NOT NULL ||
		UPDATE ms_payment_options SET pay_form='worldpay', pay_num='', pay_dev_status='1' WHERE pay_option='worldpay' ||


		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}


	$sytist_version = $new_version;
}



if($sytist_version <= "0.8.4") {
	$new_version = "0.8.5";
	$uq = "

	ALTER TABLE `ms_photos` ADD `pic_delete` INT NOT NULL ||
	ALTER TABLE `ms_menu_links` ADD `link_icon` VARCHAR(30) NOT NULL ||

	ALTER TABLE `ms_sub_galleries` ADD INDEX(`sub_under`)||

	ALTER TABLE `ms_css2` ADD `header_font` VARCHAR( 100 ) NOT NULL ,
	ADD `header_font_size` INT NOT NULL ||
	UPDATE ms_css2 SET header_font_size='30'||
	ALTER TABLE `ms_history` ADD `forum_feed` INT NOT NULL ||
	ALTER TABLE `ms_settings` ADD `email_new_customer` INT NOT NULL ||

	ALTER TABLE `ms_history` ADD `do_reload` INT NOT NULL ||
	ALTER TABLE `ms_cart` ADD `cart_custom` TEXT NOT NULL ||
	ALTER TABLE `ms_cart` ADD `cart_subscription` INT NOT NULL ||

	ALTER TABLE `ms_calendar` ADD `add_to_cart_text` VARCHAR( 100 ) NOT NULL ,
	ADD `add_to_cart_redirect` TEXT NOT NULL ||

	ALTER TABLE `ms_css2` ADD `stacked_listing_padding` INT NOT NULL ||
	ALTER TABLE `ms_history` ADD `per_page` INT NOT NULL ||
	UPDATE ms_history SET per_page='20'|| 
	UPDATE ms_css2 SET header_font_size='14'||
	UPDATE ms_photo_products_lists SET list_products_placement = '0'||

		ALTER TABLE `ms_photo_products_groups` ADD `group_no_favs` INT NOT NULL ||		

		ALTER TABLE `ms_history` ADD `homenewpeople` INT NOT NULL ,
		ADD `homenotes` INT NOT NULL ,
		ADD `homeshoppingcarts` INT NOT NULL ,
		ADD `homeforum` INT NOT NULL ,
		ADD `homenewpages` INT NOT NULL ,
		ADD `homenewphotos` INT NOT NULL ||
		UPDATE ms_history SET homenewpages='1', homenewphotos='1'||

		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$thms = whileSQL("ms_css2", "*", "ORDER BY css2_id ASC");
	while($thm = mysqli_fetch_array($thms)) {
		updateSQL("ms_css2", "stacked_listing_padding='".$thm['thumb_listing_padding']."' WHERE css2_id='".$thm['css2_id']."' ");
	}

	$theme = doSQL("ms_css", "*", "WHERE css_id='".$site_setup['css']."' ");
	updateSQL("ms_css2", "header_font='".$theme['css_font_family_main']."' WHERE parent_css_id='".$theme['css_id']."' ");

	$sytist_version = $new_version;

}



if($sytist_version <= "0.8.5") {
	$new_version = "0.8.6";
	$uq = "

	ALTER TABLE `ms_stats_site_pv` ADD INDEX ( `date_id` ) ||
	ALTER TABLE ms_photo_keywords CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci||
	ALTER TABLE `ms_photos` ADD INDEX ( `pic_delete` ) ||
	ALTER TABLE `ms_css2` ADD `menu_sep` VARCHAR( 150 ) NOT NULL ,
	ADD `footer_outside` INT NOT NULL ,
	ADD `footer_bg` VARCHAR( 10 ) NOT NULL ,
	ADD `footer_text_color` VARCHAR( 10 ) NOT NULL ,
	ADD `footer_link_color` VARCHAR( 10 ) NOT NULL ,
	ADD `footer_link_hover` VARCHAR( 10 ) NOT NULL ||
	ALTER TABLE `ms_css2` ADD `footer_padding` INT NOT NULL ,
	ADD `footer_font_size` INT NOT NULL ||

	ALTER TABLE `ms_calendar` ADD `passcode_photos` INT NOT NULL ||
	ALTER TABLE `ms_blog_categories` ADD `def_passcode_photos` INT NOT NULL ||

	ALTER TABLE `ms_language` ADD `_find_other_passcode_photos_` TEXT NOT NULL ||
	UPDATE ms_language SET _find_other_passcode_photos_='Find Other Photos' ||

	ALTER TABLE `ms_blog_categories` ADD `cat_no_list` INT NOT NULL ||
	ALTER TABLE `ms_photo_setup` ADD `passcode_photos_find` VARCHAR( 20 ) NOT NULL ||
	UPDATE ms_photo_setup SET passcode_photos_find='title' ||

	ALTER TABLE `ms_packages_connect` ADD `con_package_include` INT NOT NULL ||
	ALTER TABLE `ms_cart` ADD `cart_package_include` INT NOT NULL ||
	ALTER TABLE `ms_cart` ADD `cart_package_no_select` INT NOT NULL ||

	INSERT INTO ms_order_export SET fn='PAGENAME', fo='71', fs='0',fl='GalleryName'|| 
	INSERT INTO ms_order_export SET fn='EXPENSES', fo='72', fs='0',fl='Expenses'|| 

	ALTER TABLE `ms_photo_setup` ADD `no_meta` INT NOT NULL ||

	ALTER TABLE `ms_payment_options` ADD `pay_issue_date` INT NOT NULL ||

	UPDATE ms_payment_options SET pay_descr='About eWAY: eWAY is an Australian based company with 18\'000+ active merchants. We\'re able to service merchants in Australia, the United Kingdom, New Zealand, Singapore, Malaysia, Hong Kong.<br><br>Website: <a href=\"https://www.eway.com.au/\" target=\"_blank\">https://www.eway.com.au/</a><br>', pay_text='Pay Now', pay_status='0', pay_title='Pay with Credit Card', pay_ssl='1', pay_dev_status='1', pay_form='eway', pay_description='', pay_short_description='', pay_name='eWAY'  WHERE pay_option='eway'||

	ALTER TABLE `ms_language` ADD `_enter_email_address_to_view_gallery_` TEXT NOT NULL,  ADD `_enter_email_address_to_view_submit_` TEXT NOT NULL  ||
	UPDATE ms_language SET _enter_email_address_to_view_submit_='Submit', _enter_email_address_to_view_gallery_='Please enter your email address below to view this gallery.'||

	ALTER TABLE `ms_language` ADD `_payment_amount_` TEXT NOT NULL||
	UPDATE ms_language SET _payment_amount_='Payment Amount'||

	ALTER TABLE `ms_cart` ADD `cart_package_photo_extra_on` INT NOT NULL ||



		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}



	$sytist_version = $new_version;



	$csss = whileSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "");
	while($css = mysqli_fetch_array($csss)) { 
		if($css['footer_font_size'] <= 0) { 
			updateSQL("ms_css2", "footer_font_size='".$css['font_size']."', footer_padding='".$css['inside_padding']."', footer_text_color='".$css['font_color']."', footer_link_color='".$css['link_color']."', footer_link_hover='".$css['link_color_hover']."', footer_bg='".$css['inside_bg']."' WHERE css2_id='".$css['css2_id']."' ");

		}
	}

}


if($sytist_version <= "0.8.6") {
	$new_version = "0.8.7";
	$uq = "

	ALTER TABLE `ms_cart` ADD `cart_photo_bg` INT NOT NULL ||
	ALTER TABLE `ms_calendar` ADD `green_screen_backgrounds` INT NOT NULL ||

	ALTER TABLE `ms_calendar` ADD `green_screen_gallery` INT NOT NULL ||

	INSERT INTO ms_calendar SET green_screen_gallery='1', date_title='Green Screen Backgrounds'|| 

	ALTER TABLE `ms_sub_galleries` ADD `sub_green_screen_backgrounds` INT NOT NULL ,
	ADD `sub_no_green_screen` INT NOT NULL ||

	ALTER TABLE `ms_cart` ADD `cart_thumb` TEXT NOT NULL ||

	ALTER TABLE `ms_favs` ADD `fav_bg` INT NOT NULL ||

	ALTER TABLE `ms_language` ADD `_select_background_` TEXT NOT NULL, ADD `_select_background_description_` TEXT NOT NULL, ADD `_select_background_got_it_` TEXT NOT NULL ||
	UPDATE ms_language SET _select_background_='SELECT BACKGROUND', _select_background_description_='Use the option below to select a different background for your photos.', _select_background_got_it_='Got It'  ||

		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}



	$sytist_version = $new_version;

}



if($sytist_version <= "0.8.7") {
	$new_version = "0.8.8";
	$uq = "

		ALTER TABLE `ms_blog_photos` ADD INDEX ( `bp_package` ) ||

		ALTER TABLE `ms_blog_categories` ADD `cat_using_editor` INT NOT NULL ||
		ALTER TABLE `ms_language` ADD `_favorites_page_text_` TEXT NOT NULL ||
		ALTER TABLE `ms_calendar` ADD `date_meta_descr` TEXT NOT NULL ||
		ALTER TABLE `ms_blog_categories` ADD `cat_meta_descr` TEXT NOT NULL ||
		ALTER TABLE `ms_blog_categories` ADD `cat_key_words` TEXT NOT NULL ||

		ALTER TABLE `ms_forms` ADD `form_empty_fields` TEXT NOT NULL ,
		ADD `form_invalid_email` TEXT NOT NULL ,
		ADD `form_math_question` TEXT NOT NULL ,
		ADD `form_math_incorrect` TEXT NOT NULL ||

		UPDATE ms_forms SET form_empty_fields='You have required fields empty above.', form_invalid_email='Your email address seems to be formatted incorrectly.', form_math_question='This helps us prevent spambots.', form_math_incorrect='Your math answer is incorrect.'||

		ALTER TABLE `ms_css2` ADD `mobile_header_height` INT NOT NULL ||

		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}



	$sytist_version = $new_version;
	// include "themes.php";

}


if($sytist_version <= "0.8.8") {
	$new_version = "0.8.9";
	$uq = "
		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}



	$sytist_version = $new_version;
	// include "themes.php";

	updateSQL("ms_history", "upgrade_check='', upgrade_message='' ");

}

if($sytist_version <= "0.8.9") {
	$new_version = "0.9.0";
	$uq = "
		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}



	$sytist_version = $new_version;
	// include "themes.php";

	updateSQL("ms_history", "upgrade_check='', upgrade_message='' ");


}
if($sytist_version <= "0.9.0") {
	$new_version = "0.9.1";
	$uq = "

		ALTER TABLE `ms_payment_options` ADD `pay_private_key` TEXT NOT NULL ||

		ALTER TABLE `ms_orders` ADD `order_extra_field_1` TEXT NOT NULL ,
		ADD `order_extra_val_1` TEXT NOT NULL ,
		ADD `order_extra_field_2` TEXT NOT NULL ,
		ADD `order_extra_val_2` TEXT NOT NULL ,
		ADD `order_extra_field_3` TEXT NOT NULL ,
		ADD `order_extra_val_3` TEXT NOT NULL ,
		ADD `order_extra_field_4` TEXT NOT NULL ,
		ADD `order_extra_val_4` TEXT NOT NULL ,
		ADD `order_extra_field_5` TEXT NOT NULL ,
		ADD `order_extra_val_5` TEXT NOT NULL ||


		ALTER TABLE `ms_photo_products_lists` ADD `list_extra_1` TEXT NOT NULL ,
		ADD `list_extra_2` TEXT NOT NULL ,
		ADD `list_extra_3` TEXT NOT NULL ,
		ADD `list_extra_4` TEXT NOT NULL ,
		ADD `list_extra_5` TEXT NOT NULL ||

		ALTER TABLE `ms_photo_products_lists` ADD `list_extra_1_req` INT NOT NULL ,
		ADD `list_extra_2_req` INT NOT NULL ,
		ADD `list_extra_3_req` INT NOT NULL ,
		ADD `list_extra_4_req` INT NOT NULL ,
		ADD `list_extra_5_req` INT NOT NULL ||

		ALTER TABLE `ms_calendar` ADD `date_photo_keywords` TEXT NOT NULL||

		ALTER TABLE `ms_calendar` ADD `date_photos_keys_orderby` VARCHAR(30) NOT NULL , ADD `date_photos_keys_acdc` VARCHAR(5) NOT NULL||

		ALTER TABLE `ms_proofing_status` ADD `proof_action` VARCHAR(20) NOT NULL ||

		ALTER TABLE `ms_language` ADD `_proof_reject_` TEXT NOT NULL, ADD `_proof_rejected_` TEXT NOT NULL||
		UPDATE ms_language SET _proof_reject_='Reject', _proof_rejected_='Rejected'  ||

		ALTER TABLE `ms_calendar` ADD `proofing_disable_revise` INT NOT NULL , 
		ADD `proofing_disable_reject` INT NOT NULL ||



		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}



	$sytist_version = $new_version;

}
if($sytist_version <= "0.9.1") {
	$new_version = "1.0.0";
	$uq = "

		ALTER TABLE `ms_pending_orders` ADD `order_extra_field_1` TEXT NOT NULL ,
		ADD `order_extra_val_1` TEXT NOT NULL ,
		ADD `order_extra_field_2` TEXT NOT NULL ,
		ADD `order_extra_val_2` TEXT NOT NULL ,
		ADD `order_extra_field_3` TEXT NOT NULL ,
		ADD `order_extra_val_3` TEXT NOT NULL ,
		ADD `order_extra_field_4` TEXT NOT NULL ,
		ADD `order_extra_val_4` TEXT NOT NULL ,
		ADD `order_extra_field_5` TEXT NOT NULL ,
		ADD `order_extra_val_5` TEXT NOT NULL ||

		ALTER TABLE `ms_print_credits` ADD `pc_batch` VARCHAR(100) NOT NULL||

		ALTER TABLE `ms_photo_products` ADD `pp_watermark_file` VARCHAR(200) NOT NULL , 
		ADD `pp_watermark_location` VARCHAR(20) NOT NULL , 
		ADD `pp_logo_file` VARCHAR(200) NOT NULL , 
		ADD `pp_logo_location` VARCHAR(20) NOT NULL||

		ALTER TABLE `ms_product_options` ADD `pp_watermark_file` VARCHAR(200) NOT NULL ,
		ADD `pp_watermark_location` VARCHAR(20) NOT NULL , 
		ADD `pp_logo_file` VARCHAR(200) NOT NULL , 
		ADD `pp_logo_location` VARCHAR(20) NOT NULL ||

		ALTER TABLE `ms_product_options` ADD `pp_watermark` INT NOT NULL , 
		ADD `pp_logo` INT NOT NULL ||

		ALTER TABLE `ms_register` ADD `reg_update_expire` DATE NOT NULL||



		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}



	$sytist_version = $new_version;
}

if($sytist_version <= "1.0.0") {
	$new_version = "1.1.0";
	$uq = "

	CREATE TABLE IF NOT EXISTS `ms_email_list` (
	  `em_id` int(11) NOT NULL AUTO_INCREMENT,
	  `em_email` varchar(255)  NOT NULL,
	  `em_name` varchar(255)  NOT NULL,
	  `em_ip` varchar(50)  NOT NULL,
	  `em_date` datetime NOT NULL,
	  `em_do_not_send` int(11) NOT NULL,
	  `em_location` varchar(10)  NOT NULL,
	  `em_date_id` int(11) NOT NULL,
	  `em_last_name` varchar(255)  NOT NULL,
	  `em_mailchimp_ud` varchar(100)  NOT NULL,
	  `em_sent_to_mailchimp` int(11) NOT NULL,
	  `em_sent_to_mailchimp_doi` int(11) NOT NULL,
	  `em_status` int(11) NOT NULL,
	  `em_key` varchar(200)  NOT NULL,
		PRIMARY KEY (`em_id`)

	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000||

	ALTER TABLE `ms_email_list` ADD INDEX(`em_email`)||


	CREATE TABLE IF NOT EXISTS `ms_email_list_settings` (
	  `id` int(11) NOT NULL,
	  `signup_text` text  NOT NULL,
	  `signup_text_below` text  NOT NULL,
	  `signup_success` text  NOT NULL,
	  `first_name` int(11) NOT NULL,
	  `last_name` int(11) NOT NULL,
	  `signup_popup` int(11) NOT NULL,
	  `first_name_req` int(11) NOT NULL,
	  `last_name_req` int(11) NOT NULL,
	  `signup_button` text  NOT NULL,
	  `popup_time` int(11) NOT NULL,
	  `popup_background` varchar(10)  NOT NULL,
	  `popup_text` varchar(10)  NOT NULL,
	  `mailchimp_enable` int(11) NOT NULL,
	  `mailchimp_key` varchar(200)  NOT NULL,
	  `join_at_checkout` int(11) NOT NULL,
	  `blank_fields` text  NOT NULL,
	  `invalid_email` text  NOT NULL,
	  `cancel_link` varchar(255)  NOT NULL,
	  `join_at_checkout_text` text  NOT NULL,
	  `join_at_checkout_desc` text  NOT NULL,
	  `join_at_checkout_default` int(11) NOT NULL,
	  `mailchimp_list_id` varchar(200)  NOT NULL,
	  `mailchimp_auto` int(11) NOT NULL,
	  `mailchimp_double_optin` int(11) NOT NULL,
	  `mailchimp_double_optin_checkout` int(11) NOT NULL,
	  `double_opt_in` int(11) NOT NULL,
	  `send_welcome_email` int(11) NOT NULL,
	  `email_confirmed_title` text  NOT NULL,
	  `email_confirmed_text` text  NOT NULL,
	  `email_removed_title` text  NOT NULL,
	  `email_removed_text` text  NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8||


	INSERT INTO `ms_email_list_settings` (`id`, `signup_text`, `signup_text_below`, `signup_success`, `first_name`, `last_name`, `signup_popup`, `first_name_req`, `last_name_req`, `signup_button`, `popup_time`, `popup_background`, `popup_text`, `mailchimp_enable`, `mailchimp_key`, `join_at_checkout`, `blank_fields`, `invalid_email`, `cancel_link`, `join_at_checkout_text`, `join_at_checkout_desc`, `join_at_checkout_default`, `mailchimp_list_id`, `mailchimp_auto`, `mailchimp_double_optin`, `mailchimp_double_optin_checkout`, `double_opt_in`, `send_welcome_email`, `email_confirmed_title`, `email_confirmed_text`, `email_removed_title`, `email_removed_text`) VALUES
	(0, 'Sign up for email promotions.', '<i>Your information is safe with us and won''t be shared.</i>', 'Thank you for signing up!', 0, 0, 0, 0, 0, 'Sign Up', 2, '000000', 'EFEFEF', 0, '', 0, 'Please fill in the required fields.', 'The email address you entered does not seem to be formatted correctly. Please check your email address.', 'no thanks', 'Receive promotions by email', '', 1, '', 0, 1, 0, 0, 1, 'Thank you!', 'Thank you for confirming your email address and subscribing to our mailing list.', 'Sorry to see you go!', 'Your email address has been unsubscribed.')||

	ALTER TABLE `ms_email_list_settings` ADD PRIMARY KEY (`id`)||

	ALTER TABLE `ms_email_list_settings` ADD `webhook_hash` VARCHAR(200) NOT NULL||

	INSERT INTO `ms_emails` (`email_name`, `email_descr`, `email_codes`, `email_from_email`, `email_from_name`, `email_subject`, `email_message`, `email_type`, `email_no_delete`, `email_download_descr`, `email_shipping_descr`, `email_paypal_pending`, `email_offline_pending`, `email_id_name`) VALUES
	( 'Mailing List Confirm Subscription', 'This is the email sent for someone to confirm their subscription to your mailing list (if the option is selected).', '[LINK_TO_WEBSITE] = The start link to your website\r\n[/LINK_TO_WEBSITE] = The end link to your website\r\n[CONFIRM_SUBSCRIPTION_LINK] = The start link to confirm\r\n[/CONFIRM_SUBSCRIPTION_LINK] = The end link to confirm\r\n[URL] = Link to your website\r\n[WEBSITE_NAME] = The name of your website ', '', '', 'Please Confirm Your Email Subscription for [WEBSITE_NAME]', '<p>Hello!</p><p>Thank you for subscribing to our mailing list at [LINK_TO_WEBSITE][WEBSITE_NAME][/LINK_TO_WEBSITE]. Please confirm your subscription by clicking the link below:</p><p>[CONFIRM_SUBSCRIPTION_LINK]Yes, subscribe me[/CONFIRM_SUBSCRIPTION_LINK]</p><p>If you received this email by mistake, simply delete it. You will not be subscribed if you do not click the confirmation link above.</p><p>Thank you!<br>[LINK_TO_WEBSITE][WEBSITE_NAME][/LINK_TO_WEBSITE]<br></p>', 0, 1, '', '', '', '', 'maillistconfirm'),
	( 'Mailing List Welcome Email', 'This is the email sent to a person that has subscribed to your mailing list.', '[LINK_TO_WEBSITE] = The start link to your website\r\n[/LINK_TO_WEBSITE] = The end link to your website\r\n[UNSUBSCRIBE_LINK] = The start link to unsubscribe\r\n[/UNSUBSCRIBE_LINK] = The end link to unsubscribe\r\n[URL] = Link to your website\r\n[WEBSITE_NAME] = The name of your website ', '', '', 'Thank You For Subscribing! [WEBSITE_NAME]', '<p>Thank you for your subscription to our mailing list [LINK_TO_WEBSITE][WEBSITE_NAME][/LINK_TO_WEBSITE]!&nbsp;</p><p>If you received this in error, did not sign up or wish to be removed, [UNSUBSCRIBE_LINK]click here to unsubscribe[/UNSUBSCRIBE_LINK].&nbsp;</p><p>Thank you!<br>[LINK_TO_WEBSITE][WEBSITE_NAME][/LINK_TO_WEBSITE]<br></p>', 0, 1, '', '', '', '', 'maillistwelcome')||

	ALTER TABLE `ms_photo_setup` ADD `no_search_filename` INT NOT NULL||

	ALTER TABLE `ms_language` ADD `_i_agree_` TEXT NOT NULL, ADD `_i_do_not_agree_` TEXT NOT NULL, ADD `_product_combination_out_of_stock_` TEXT NOT NULL, ADD `_out_of_stock_` TEXT NOT NULL ||
	UPDATE ms_language SET _i_agree_='I Agree', _i_do_not_agree_='I Do Not Agree', _product_combination_out_of_stock_='Product Combination Out Of Stock', _out_of_stock_='Out of stock'  ||

	ALTER TABLE `ms_image_options` ADD `opt_discountable` INT NOT NULL||
	ALTER TABLE `ms_cart_options` ADD `co_discountable` INT NOT NULL||


	ALTER TABLE `ms_language` ADD `_add_all_favorites_to_` TEXT NOT NULL,  ADD `_add_all_favorites_message_` TEXT NOT NULL,  ADD `_add_all_yes_` TEXT NOT NULL,  ADD `_add_all_done_` TEXT NOT NULL||
	UPDATE ms_language SET _add_all_favorites_to_ = 'Add all favorites to [COLLECTION_NAME]?', _add_all_favorites_message_ = 'This will add [NUMBER_TO_ADD] of [NUMBER_OF_FAVORITES] favorites to the collection.', _add_all_yes_ = 'Yes, add these', _add_all_done_= 'Favorites have been added'||

		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}



	$sytist_version = $new_version;
	// include "themes.php";
}

if($sytist_version <= "1.1.0") {
	$new_version = "1.1.1";
	$uq = "
		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}



	$sytist_version = $new_version;
	// include "themes.php";
}


if($sytist_version <= "1.1.1") {
	$new_version = "1.2.0";
	$uq = "
		ALTER TABLE `ms_menu_links` ADD `link_dropdown_links` TEXT NOT NULL||
		ALTER TABLE `ms_menu_links` ADD `link_mobile_cats` INT NOT NULL||
		ALTER TABLE `ms_blog_categories` ADD `cat_gallery_exclusive` INT NOT NULL||
		ALTER TABLE `ms_new_accounts` ADD `retype_email` INT NOT NULL , ADD `retype_password` INT NOT NULL ||
		UPDATE ms_new_accounts SET retype_email='1', retype_password='1'||

		ALTER TABLE `ms_calendar` ADD `date_gallery_exclusive` INT NOT NULL ||

		ALTER TABLE `ms_calendar` ADD `date_owner` INT NOT NULL ||

		ALTER TABLE `ms_photos` ADD `pic_hide` INT NOT NULL , ADD `pic_hide_by` INT NOT NULL||
		ALTER TABLE `ms_photos` ADD `pic_fav_admin` INT NOT NULL ||

		ALTER TABLE `ms_language` ADD `_manage_favorites_login_` TEXT NOT NULL,  ADD `_log_into_existing_account_` TEXT NOT NULL,  ADD `_hide_unhide_photo_` TEXT NOT NULL, ADD `_hidden_photo_` TEXT NOT NULL, ADD `_invalid_email_format_` TEXT NOT NULL, ADD `_log_in_with_facebook_` TEXT NOT NULL,  ADD `_gallery_owner_login_` TEXT NOT NULL||

		UPDATE ms_language SET _manage_favorites_login_='To keep track of your favorites, you will need to be logged into your account.', _log_into_existing_account_='Log in if you already have an account', _hide_unhide_photo_='Hide / Unhide', _hidden_photo_='Hidden Photo', _invalid_email_format_='It appears your email address is not formatted correctly' , _log_in_with_facebook_='Log in with Facebook' , _gallery_owner_login_='Gallery Owner Login'   ||

		ALTER TABLE `ms_calendar` ADD `open_highlights` INT NOT NULL, ADD `show_star` INT NOT NULL , ADD `star_text`  VARCHAR(200) NOT NULL , ADD `add_highlight_link` INT NOT NULL , ADD `highlights_text` VARCHAR(200) NOT NULL ||

		ALTER TABLE `ms_photo_products` CHANGE `pp_download_dem` `pp_download_dem` TEXT NOT NULL||

		ALTER TABLE `ms_fb` ADD `page_share_text` TEXT NOT NULL||
		UPDATE ms_fb SET page_share_text='You will also need share the password \"[PASSWORD]\" for others to view the photos.'||

		ALTER TABLE `ms_people` ADD `p_fb_id` VARCHAR(200) NOT NULL||
		ALTER TABLE `ms_people` ADD `p_fb_link` VARCHAR(255) NOT NULL ||
		ALTER TABLE `ms_fb` ADD `facebook_app_secret` VARCHAR(255) NOT NULL||
		ALTER TABLE `ms_fb` ADD `facebook_login` INT NOT NULL||


		INSERT INTO `ms_emails` ( `email_name`, `email_descr`, `email_codes`, `email_from_email`, `email_from_name`, `email_subject`, `email_message`, `email_type`, `email_no_delete`, `email_download_descr`, `email_shipping_descr`, `email_paypal_pending`, `email_offline_pending`, `email_id_name`) VALUES
		('Gallery Owner Email', 'This is the email you can send after you assign a customer as a gallery owner to a gallery.', '[FIRST_NAME] = Gallery owner first name\r\n\r\n[LAST_NAME] = Gallery owner last name\r\n\r\n[URL] = The URL to your website\r\n\r\n[WEBSITE_NAME] = The name of your website \r\n\r\n[LINK] = The link to the gallery\r\n\r\n[PASSWORD] = The password to the gallery.\r\n\r\n[LINK_TO_WEBSITE][WEBSITE_NAME][/LINK_TO_WEBSITE] = Creates a clickable link to your website.', '', '', 'Your Photos Are Ready At [WEBSITE_NAME]', '<p>Hello [FIRST_NAME],</p><p>I am letting you know that your photos are ready to be viewed at [LINK_TO_WEBSITE][WEBSITE_NAME][/LINK_TO_WEBSITE].</p><p>Before you view, I wanted to let you know about a special feature you have available. When viewing your photos you have the option to hide certain photos by clicking the x icon under the thumbnail. When you hide a photo, you will still be able to view it, but anyone else you share the gallery with will not be able to see those photos.</p><p>Also if someone requests to view your photos you have not supplied the password to, you will receive an email and decide if you want them to have the password.</p><p>You have a special link to view your photos. <strong>Once you visit that link, please log into your account</strong>. You will log in with your email address: [EMAIL] and your account password (this is not the same as your gallery password).&nbsp;</p><p>If you want others to view your photos, then you can share your special link and gallery password.&nbsp;</p><p>Your special link: [LINK]<br>Gallery password: [PASSWORD]</p><p>Thank you and please let us know if you have any question.</p><p>[LINK_TO_WEBSITE][WEBSITE_NAME][/LINK_TO_WEBSITE]<br></p>', 0, 1, '', '', '', '', 'galleryowner'),
		( 'Request Access Email Sent To Gallery Owner', 'This is the email sent to a gallery owner (if one is selected) when someone requests access to the gallery.', '[FIRST_NAME] = Gallery owner first name\r\n\r\n[LAST_NAME] = Gallery owner last name\r\n\r\n[URL] = The URL to your website\r\n\r\n[WEBSITE_NAME] = The name of your website \r\n\r\n[FROM_NAME]  = The name of the person requesting access.\r\n\r\n[FROM_MESSAGE] = The message from the person requesting access.\r\n\r\n[PAGE_LINK][PAGE_TITLE][/PAGE_LINK] = This creates a clickable link to the page with the page title being the clickable text. \r\n\r\n[LINK_TO_WEBSITE][WEBSITE_NAME][/LINK_TO_WEBSITE] = Creates a clickable link to your website.', '', '', '[FROM_NAME] has requested access to your gallery at [WEBSITE_NAME]', '<p>Hello [FIRST_NAME],</p><p>[FROM_NAME] ([FROM_EMAIL]) has requested the password for your gallery [PAGE_LINK][PAGE_TITLE][/PAGE_LINK] at  [LINK_TO_WEBSITE][WEBSITE_NAME][/LINK_TO_WEBSITE].&nbsp;</p><p>They also added the following message: [FROM_MESSAGE]<br></p><p>If you wish to allow them to view your photos, you can reply to this email with the password that was provided to you for your gallery.</p><p>Thank you,<br> [LINK_TO_WEBSITE][WEBSITE_NAME][/LINK_TO_WEBSITE]<br></p><p><br></p>', 0, 1, '', '', '', '', 'requestaccess')||


		CREATE TABLE `ms_gal_exclusive` (
		  `gal_id` int(11) NOT NULL,
		  `gal_site_title` varchar(255) NOT NULL,
		  `gal_contact_page` int(11) NOT NULL,
		  `gal_sub_gal_limit` int(11) NOT NULL,
		  `gal_phone` varchar(20) NOT NULL,
		  `gal_show_cover_password` int(11) NOT NULL,
		  `gal_remove_footer` int(11) NOT NULL,
		  `gal_footer` text NOT NULL,
		  `gal_site_link` varchar(200) NOT NULL,
		  `facebook` varchar(255) NOT NULL,
		  `twitter` varchar(255) NOT NULL,
		  `pinterest` varchar(255) NOT NULL,
		  `email` varchar(255) NOT NULL,
		  `share_on` varchar(255) NOT NULL,
		  `more` varchar(255) NOT NULL,
		  `share` varchar(255) NOT NULL,
		  `my_favorites` varchar(255) NOT NULL,
		  `my_cart` varchar(255) NOT NULL,
		  `my_account` varchar(255) NOT NULL,
		  `contact` varchar(255) NOT NULL,
		  `gallery_home` varchar(255) NOT NULL,
		  `all_photos` varchar(255) NOT NULL,
		  `page_layout` int(11) NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8||


		INSERT INTO `ms_gal_exclusive` (`gal_id`, `gal_site_title`, `gal_contact_page`, `gal_sub_gal_limit`, `gal_phone`, `gal_show_cover_password`, `gal_remove_footer`, `gal_footer`, `gal_site_link`, `facebook`, `twitter`, `pinterest`, `email`, `share_on`, `more`, `share`, `my_favorites`, `my_cart`, `my_account`, `contact`, `gallery_home`, `all_photos`, `page_layout`) VALUES
		(1, '', 0, 4, '', 1, 0, '', '', 'Facebook', 'Twitter', 'Pinterest', 'Email', 'Share on', 'More', 'Share', 'My Favorites', 'My Cart', 'My Account', 'Contact', 'Gallery Home', 'All Photos', 0)||

		ALTER TABLE `ms_gal_exclusive` ADD PRIMARY KEY (`gal_id`)||

		ALTER TABLE `ms_gal_exclusive` MODIFY `gal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2||

		ALTER TABLE `ms_photo_setup` ADD `gallery_favicon` INT NOT NULL||

		ALTER TABLE `ms_language` ADD `_now_logged_in_facebook_` TEXT NOT NULL||
		UPDATE ms_language SET _now_logged_in_facebook_='You are now logged in as'||


		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$site_setup = doSQL("ms_settings", "*", "");
	updateSQL("ms_gal_exclusive", "gal_site_title='".addslashes(stripslashes($site_setup['website_title']))."' ");

	$sytist_version = $new_version;
	include "themes.php";
	if($setup['sytist_hosted'] == true) { 
		updateSQL("ms_fb", "facebook_app_secret='4bab944daa18ebff22deec4fd9cb59fc', facebook_login='1'  ");
		if($site_setup['css'] <= 0) { 
			updateSQL("ms_settings", "css='1' ");
		}
	}
}

if($sytist_version <= "1.2.0") {
	$new_version = "1.2.1";
	$uq = "
		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}


	$sytist_version = $new_version;
}


if($sytist_version <= "1.2.1") {
	$new_version = "1.2.2";
	$uq = "
		ALTER TABLE `ms_gal_exclusive` ADD `gal_disable_share` INT NOT NULL||
		UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}


	$sytist_version = $new_version;
}


if($sytist_version <= "1.2.2") {
	$new_version = "1.3.0";
	$uq = "
	ALTER TABLE `ms_calendar`
	  DROP `date_dm_link`,
	  DROP `date_new_blog`,
	  DROP `prod_name`,
	  DROP `prod_status`,
	  DROP `prod_descr`,
	  DROP `prod_long_descr`,
	  DROP `prod_image`,
	  DROP `prod_order`,
	  DROP `prod_link`,
	  DROP `prod_page_link`,
	  DROP `prod_beta`,
	  DROP `prod_beta_link`,
	  DROP `prod_beta_dl_name`||

	ALTER TABLE `ms_calendar` 
	ADD `_deposit_` TEXT NOT NULL,
	 ADD `_booking_comments_or_notes_` TEXT NOT NULL,
	 ADD `_booking_send_request_` TEXT NOT NULL,
	 ADD `_booking_your_information_` TEXT NOT NULL,
	 ADD `_booking_success_title_` TEXT NOT NULL,
	 ADD `_booking_success_message_` TEXT NOT NULL,
	 ADD `_booking_additional_options_` TEXT NOT NULL,
	 ADD `_booking_additional_options_message_` TEXT NOT NULL,
	 ADD `_booking_deposit_required_message_` TEXT NOT NULL,
	 ADD `_booking_select_time_` TEXT NOT NULL,
	 ADD `_learn_more_` TEXT NOT NULL,
	 ADD `_book_now_` TEXT NOT NULL ||

	ALTER TABLE `ms_calendar` 
	ADD `_booking_your_information_text_` TEXT NOT NULL,
	ADD `_booking_your_information_text_above_send_` TEXT NOT NULL||


	ALTER TABLE `ms_product_options` ADD `opt_service` INT NOT NULL , ADD `opt_addition_time` INT NOT NULL ||
	ALTER TABLE `ms_product_options_sel` ADD `sel_add_time` INT NOT NULL||


	ALTER TABLE `ms_calendar` ADD `deposit` INT NOT NULL, ADD `book_all_day` INT NOT NULL , ADD `book_length_hours` INT NOT NULL , ADD `book_length_minutes` INT NOT NULL,  ADD `book_require_deposit` INT NOT NULL,  ADD `book_auto_confirm` INT NOT NULL, ADD `book_lead_time` INT NOT NULL , ADD `book_once_a_day` INT NOT NULL, ADD `book_confirm_email` TEXT NOT NULL ,
	ADD `book_reminder_email` TEXT NOT NULL ,
	ADD `book_special_event` INT NOT NULL ,
	ADD `book_special_event_day_only` INT NOT NULL,  ADD `book_special_event_start` TIME NOT NULL ,
	ADD `book_special_event_end` TIME NOT NULL ,
	ADD `book_special_event_blocks` INT NOT NULL, ADD `book_confirm_email_subject` VARCHAR( 255 ) NOT NULL ,
	ADD `book_reminder_email_subject` VARCHAR( 255 ) NOT NULL, ADD `book_special_event_date` DATE NOT NULL ||

	ALTER TABLE `ms_calendar` ADD `date_gallery_exclusive_no_cover` INT NOT NULL ||
	ALTER TABLE `ms_blog_categories` ADD `cat_gallery_exclusive_no_cover` INT NOT NULL ||

	ALTER TABLE `ms_cart` ADD `cart_booking` INT NOT NULL||
	ALTER TABLE `ms_category_layouts` ADD `layout_id_name` VARCHAR( 20 ) NOT NULL ||
	ALTER TABLE `ms_orders` ADD `order_booking_confirm` INT NOT NULL ||

	ALTER TABLE `ms_email_logs` ADD `log_cron` INT NOT NULL ||

	ALTER TABLE `ms_settings` ADD `cron_enabled` INT NOT NULL ,
	ADD `cron_emails_per` INT NOT NULL ,
	ADD `cron_sleep` INT NOT NULL,
	ADD `cron_site_url` VARCHAR( 255 ) NOT NULL ,
	ADD `cron_test_mode` INT NOT NULL ,
	ADD `cron_unsubscribe` TEXT NOT NULL ,
	ADD `unsubscribe_text` TEXT NOT NULL ||

	ALTER TABLE `ms_settings` ADD `smtp_secure` VARCHAR( 15 ) NOT NULL ,
	ADD `smtp_port` INT NOT NULL ||

	ALTER TABLE `ms_settings` ADD `download_tax` DECIMAL( 10, 2 ) NOT NULL ||

	UPDATE ms_settings SET cron_emails_per='10', cron_test_mode='1', cron_unsubscribe='If you no longer wish to receive this type of email from us, [UNSUBSCRIBE_LINK]click here[/UNSUBSCRIBE_LINK].', unsubscribe_text='You will no longer receive these types of emails. Thank you.' ||


	CREATE TABLE IF NOT EXISTS `ms_bookings` (
	  `book_id` int(11) NOT NULL AUTO_INCREMENT,
	  `book_event_name` varchar(255) CHARACTER SET utf8 NOT NULL,
	  `book_date` date NOT NULL,
	  `book_time` time NOT NULL,
	  `book_first_name` varchar(255) CHARACTER SET utf8 NOT NULL,
	  `book_last_name` varchar(255) CHARACTER SET utf8 NOT NULL,
	  `book_email` varchar(255) CHARACTER SET utf8 NOT NULL,
	  `book_phone` varchar(255) CHARACTER SET utf8 NOT NULL,
	  `book_account` int(11) NOT NULL,
	  `book_service` int(11) NOT NULL,
	  `book_customer_notes` text CHARACTER SET utf8 NOT NULL,
	  `book_notes` text CHARACTER SET utf8 NOT NULL,
	  `book_recurring_dom` int(11) NOT NULL,
	  `book_recurring_dow` varchar(20) CHARACTER SET utf8 NOT NULL,
	  `book_recurd` varchar(5) CHARACTER SET utf8 NOT NULL,
	  `book_recurring_y` int(11) NOT NULL,
	  `book_unavailable` int(11) NOT NULL,
	  `book_length` int(11) NOT NULL,
	  `book_unavailable_day` int(11) NOT NULL,
	  `book_confirmed` int(11) NOT NULL,
	  `book_all_day` int(11) NOT NULL,
	  `book_options` text CHARACTER SET utf8 NOT NULL,
	  `book_submitted` int(11) NOT NULL,
	  `book_date_added` datetime NOT NULL,
	  `book_order_id` int(11) NOT NULL,
	  `book_total` decimal(10,2) NOT NULL,
	  `book_deposit` decimal(10,2) NOT NULL,
	  `book_ip` varchar(30) CHARACTER SET utf8 NOT NULL,
	  `book_cancel_notes` text CHARACTER SET utf8 NOT NULL,
	  `book_reminder` int(11) NOT NULL,
	  PRIMARY KEY (`book_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ||


	CREATE TABLE `ms_bookings_settings` (
	  `Monday` int(11) NOT NULL,
	  `Tuesday` int(11) NOT NULL,
	  `Wednesday` int(11) NOT NULL,
	  `Thursday` int(11) NOT NULL,
	  `Friday` int(11) NOT NULL,
	  `Saturday` int(11) NOT NULL,
	  `Sunday` int(11) NOT NULL,
	  `start_time` time NOT NULL,
	  `end_time` time NOT NULL,
	  `time_blocks` int(11) NOT NULL,
	  `Monday_ado` int(11) NOT NULL,
	  `Tuesday_ado` int(11) NOT NULL,
	  `Wednesday_ado` int(11) NOT NULL,
	  `Thursday_ado` int(11) NOT NULL,
	  `Friday_ado` int(11) NOT NULL,
	  `Saturday_ado` int(11) NOT NULL,
	  `Sunday_ado` int(11) NOT NULL,
	  `Monday_start_time` time NOT NULL,
	  `Monday_end_time` time NOT NULL,
	  `Tuesday_start_time` time NOT NULL,
	  `Tuesday_end_time` time NOT NULL,
	  `Wednesday_start_time` time NOT NULL,
	  `Wednesday_end_time` time NOT NULL,
	  `Thursday_start_time` time NOT NULL,
	  `Thursday_end_time` time NOT NULL,
	  `Friday_start_time` time NOT NULL,
	  `Friday_end_time` time NOT NULL,
	  `Saturday_start_time` time NOT NULL,
	  `Saturday_end_time` time NOT NULL,
	  `Sunday_start_time` time NOT NULL,
	  `Sunday_end_time` time NOT NULL,
	  `Monday_time_blocks` int(11) NOT NULL,
	  `Tuesday_time_blocks` int(11) NOT NULL,
	  `Wednesday_time_blocks` int(11) NOT NULL,
	  `Thursday_time_blocks` int(11) NOT NULL,
	  `Friday_time_blocks` int(11) NOT NULL,
	  `Saturday_time_blocks` int(11) NOT NULL,
	  `Sunday_time_blocks` int(11) NOT NULL,
	  `do_not_show_on_people_list` int(11) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8||


	INSERT INTO `ms_bookings_settings` (`Monday`, `Tuesday`, `Wednesday`, `Thursday`, `Friday`, `Saturday`, `Sunday`, `start_time`, `end_time`, `time_blocks`, `Monday_ado`, `Tuesday_ado`, `Wednesday_ado`, `Thursday_ado`, `Friday_ado`, `Saturday_ado`, `Sunday_ado`, `Monday_start_time`, `Monday_end_time`, `Tuesday_start_time`, `Tuesday_end_time`, `Wednesday_start_time`, `Wednesday_end_time`, `Thursday_start_time`, `Thursday_end_time`, `Friday_start_time`, `Friday_end_time`, `Saturday_start_time`, `Saturday_end_time`, `Sunday_start_time`, `Sunday_end_time`, `Monday_time_blocks`, `Tuesday_time_blocks`, `Wednesday_time_blocks`, `Thursday_time_blocks`, `Friday_time_blocks`, `Saturday_time_blocks`, `Sunday_time_blocks`, `do_not_show_on_people_list`) VALUES
	(1, 1, 1, 1, 1, 0, 0, '00:00:00', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, '09:00:01', '17:00:01', '09:00:01', '17:00:01', '09:00:01', '17:00:01', '09:00:01', '17:00:01', '09:00:01', '17:00:01', '10:00:01', '18:00:01', '12:00:01', '17:30:01', 30, 30, 30, 30, 30, 30, 30, 0)||



	INSERT INTO `ms_category_layouts` (`layout_name`, `layout_html`, `layout_description`, `layout_css_id`, `layout_js_function`, `layout_width`, `layout_height`, `layout_photo_class`, `layout_photo_size`, `layout_photo_width`, `layout_photo_height`, `layout_spacing`, `layout_per_page`, `layout_preview_text_length`, `layout_type`, `layout_file`, `layout_default`, `layout_css`, `layout_no_delete`, `layout_folder`, `layout_id_name`) VALUES
	('Booking Services With Book Now Buttons', '', 'This will display the services you offer in a booking section with the Book Now buttons directly on the services listing page.', 'listing-standard', '', 0, 0, 'thumbnailnoborder', 'pic_pic', 0, 0, 0, 0, 0, 'listing', 'listing-booking-with-book-now-buttons.php', 0, '', 0, '', 'bookinglisting'),
	( 'Booking Services Page', '', '', '', '', 0, 0, '', '', 0, 0, 0, 0, 0, 'page', 'booking-page.php', 0, '', 0, '', 'bookingpage'),
	('Booking Services With No Book Now Buttons', '', 'This will display the services you offer in a booking section that links to each service. ', 'listing-standard', '', 0, 0, 'thumbnailnoborder', 'pic_pic', 0, 0, 0, 0, 0, 'listing', 'listing-booking-no-book-now.php', 0, '', 0, '', '')||


	INSERT INTO `ms_emails` (`email_name`, `email_descr`, `email_codes`, `email_from_email`, `email_from_name`, `email_subject`, `email_message`, `email_type`, `email_no_delete`, `email_download_descr`, `email_shipping_descr`, `email_paypal_pending`, `email_offline_pending`, `email_id_name`) VALUES
	( 'Booking Request Received', 'The email you receive when someone books through the booking calendar.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s last name\r\n[URL] = The URL to your website\r\n[WEBSITE_NAME] = The name of your website\r\n[BOOKING_DATE] = Date\r\n[BOOKING_TIME] = Time\r\n[BOOKING_SERVICE] = Service name\r\n[EMAIL_ADDRESS] = Customer mmail address\r\n[PHONE] = Customer phone\r\n[BOOKING_DATE] = Date & time\r\n[BOOKING_SERVICE] = Service booked\r\n[BOOKING_CONFIRMED] = If auto confirmed\r\n[BOOKING_DEPOSIT] = Deposit amount if paid', '', '', 'Booking Request Received at [WEBSITE_NAME], [FIRST_NAME] [LAST_NAME]', '<p>Hello,</p><p>You have just received a booking request from your website [WEBSITE_NAME]</p><p>Name: [FIRST_NAME] [LAST_NAME]<br>Email: [EMAIL_ADDRESS]<br>Phone: [PHONE]<br>Date / Time: [BOOKING_DATE]<br>Service: [BOOKING_SERVICE]<br>[BOOKING_OPTIONS]<br>Auto Confirmed: [BOOKING_CONFIRMED]<br>Deposit Received: [BOOKING_DEPOSIT]<br></p><p>Log into your admin to view details and manage this booking request.:<br>[LINK_TO_ADMIN]</p><p>~Your Sytist Site<br><br></p>', 0, 1, '', '', '', '', 'bookingrequest'),
	( 'Booking Invoice', 'This is the email sent when sending an invoice for a booking calendar service.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s last name\r\n[URL] = The URL to your website\r\n[WEBSITE_NAME] = The name of your website\r\n[BOOKING_DATE] = Date\r\n[BOOKING_TIME] = Time\r\n[BOOKING_SERVICE] = Service name\r\n[EMAIL_ADDRESS] = Customer mmail address\r\n[PHONE] = Customer phone\r\n[BOOKING_DATE_TIME] = Date & time\r\n[BOOKING_SERVICE] = Service booked\r\n[BOOKING_CONFIRMED] = If auto confirmed\r\n[BOOKING_DEPOSIT] = Deposit amount if paid\r\n[INVOICE_TOTAL] = Invoice total\r\n[INVOICE_DUE] = Due date\r\n[INVOICE_LINK] = Opens link to invoice\r\n[/INVOICE_LINK] = Closes link to invoice', '', '', '[FIRST_NAME], Your Booking Confirmation & Invoice With [WEBSITE_NAME] [INVOICE_NUMBER]', '<p>Hello [FIRST_NAME],\r\n</p>\r\n<p>Your invoice is ready for your booking confirmation with [WEBSITE_NAME].\r\n</p>\r\n<p><strong>[BOOKING_SERVICE]</strong><br>When: [BOOKING_DATE_TIME]\r\n</p>\r\n<p>Amount: [INVOICE_TOTAL]<br>Due: [INVOICE_DUE]<br>\r\n</p>\r\n<p>[INVOICE_LINK]Click here to view and pay your invoice[/INVOICE_LINK]<br>\r\n</p>Thank you,<br> [WEBSITE_NAME]', 0, 1, '', '', '', '', 'bookinginvoice')||


	CREATE TABLE IF NOT EXISTS `ms_crons` (
	  `cron_id` int(11) NOT NULL AUTO_INCREMENT,
	  `cron_email` int(11) NOT NULL,
	  `cron_what` varchar(20) NOT NULL,
	  `cron_days` int(11) NOT NULL,
	  `cron_time` time NOT NULL,
	  `cron_check_date` date NOT NULL,
	  `cron_status` int(11) NOT NULL,
	  `cron_gal_access` int(11) NOT NULL,
	  `cron_gal_preregister` int(11) NOT NULL,
	  `cron_gal_viewed` int(11) NOT NULL,
	  `cron_gal_purchased` int(11) NOT NULL,
	  `cron_gal_collected_email` int(11) NOT NULL,
	  PRIMARY KEY (`cron_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ||


	INSERT INTO `ms_crons` (`cron_id`, `cron_email`, `cron_what`, `cron_days`, `cron_time`, `cron_check_date`, `cron_status`, `cron_gal_access`, `cron_gal_preregister`, `cron_gal_viewed`, `cron_gal_purchased`, `cron_gal_collected_email`) VALUES
	(5, 22, 'galleries', 7, '09:00:00', '2016-06-05', 0, 1, 1, 1, 1, 1),
	(2, 0, 'booking', 1, '08:00:00', '2016-06-05', 1, 0, 0, 0, 0, 0),
	(3, 22, 'galleries', 0, '09:00:00', '2016-06-05', 1, 1, 1, 1, 1, 1),
	(4, 22, 'galleries', 3, '09:00:00', '2016-06-05', 0, 1, 1, 1, 1, 1)||


	CREATE TABLE IF NOT EXISTS `ms_cron_emails` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `to_email` varchar(255) NOT NULL,
	  `to_name` varchar(255) NOT NULL,
	  `from_email` varchar(255) NOT NULL,
	  `from_name` varchar(255) NOT NULL,
	  `subject` varchar(255) NOT NULL,
	  `content` text NOT NULL,
	  `date_time_to_send` datetime NOT NULL,
	  `from_date_id` int(11) NOT NULL,
	  `from_book_id` int(11) NOT NULL,
	  `from_email_id` int(11) NOT NULL,
	  `priority` int(11) NOT NULL,
	  `test_email` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=157 ||


	CREATE TABLE IF NOT EXISTS `ms_people_no_email` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `email` varchar(255) NOT NULL,
	  `date` datetime NOT NULL,
	  `ip` varchar(100) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ||


	INSERT INTO `ms_payment_options` (`pay_name`, `pay_num`, `pay_key`, `pay_email`, `pay_descr`, `pay_text`, `pay_button`, `pay_status`, `pay_cards`, `pay_option`, `pay_page_title`, `pay_order`, `pay_title`, `pay_description`, `pay_ssl`, `pay_url`, `paypal_curl`, `pay_emulator`, `test_mode`, `pay_short_description`, `pay_full_page`, `pay_dev_status`, `pay_currency`, `pay_select_graphic`, `pay_form`, `pay_express_download_address`, `pay_offline_descr`, `pay_paypal_exp_button`, `pay_issue_date`, `pay_private_key`) VALUES
	('Sisow / iDeal', '', '', '', '', 'Continue', '', 0, '', 'sisow', '', 2, 'Pay With iDeal', '', 0, 'https://www.sisow.nl/', 0, '', 0, '', 0, 1, '', '', 'sisow', 0, '', '', 0, '')||


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}


	$sytist_version = $new_version;

}

if($sytist_version <= "1.3.0") {
	$new_version = "1.3.1";
	$uq = "

	ALTER TABLE `ms_crons` ADD `cron_gal_owner` INT NOT NULL, ADD `cron_gal_no_order` INT NOT NULL ||
	INSERT INTO ms_crons SET cron_what='earlybird', 	cron_days='2', cron_time='09:00:00'||

	INSERT INTO `ms_emails` (`email_name`, `email_descr`, `email_codes`, `email_from_email`, `email_from_name`, `email_subject`, `email_message`, `email_type`, `email_no_delete`, `email_download_descr`, `email_shipping_descr`, `email_paypal_pending`, `email_offline_pending`, `email_id_name`) VALUES
	('Early Bird Special Expiring Email', 'The email sent to notify people associated with a gallery that the early bird special is expiring.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s last name\r\n[URL] = The URL to your website\r\n[PAGE_TITLE] = A clicking link with the gallery title\r\n[WEBSITE_NAME] = The name of your website\r\n[EMAIL_ADDRESS] = Customer mmail address\r\n[PHONE] = Customer phone\r\n[PAGE_LINK] = Opening link to gallery\r\n[/PAGE_LINK] = Closes link to gallery\r\n[EARLY_BIRD_SPECIAL_DATE] = The date the early bird special expires', '', '', 'Early Bird Special Expiring for [PAGE_TITLE] on [EARLY_BIRD_SPECIAL_DATE] at [WEBSITE_NAME]', '<p>Hello [FIRST_NAME],\r\n</p><p>We wanted to let you know that the early bird special promotion for the gallery \"[PAGE_TITLE]\" will be expiring on [EARLY_BIRD_SPECIAL_DATE]\r\n</p><p>[PAGE_LINK]Click here to view the photos and purchase before the special ends[/PAGE_LINK]!\r\n</p><p>Thank you,<br>[WEBSITE_NAME]<br>[URL]<br>\r\n</p>', 0, 1, '', '', '', '', 'earlybirdspecial')||

	ALTER TABLE `ms_calendar` ADD `book_once_a_day_time` TIME NOT NULL ||




	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$ebem = doSQL("ms_emails", "*", "WHERE email_id_name='earlybirdspecial' ");
	updateSQL("ms_crons", "cron_email='".$ebem['email_id']."' WHERE cron_what='earlybird' ");
	updateSQL("ms_bookings", "book_confirmed='1' WHERE book_unavailable='1' ");
	$sytist_version = $new_version;

}



if($sytist_version <= "1.3.1") {
	$new_version = "1.4.0";
	$uq = "

	ALTER TABLE `ms_promo_codes` ADD `code_min` DECIMAL(10,2) NOT NULL, ADD `code_print_credit` INT NOT NULL||
	ALTER TABLE `ms_print_credits` ADD `pc_coupon` INT NOT NULL||
	ALTER TABLE `ms_cart` ADD `cart_bonus_coupon` INT NOT NULL AFTER `cart_booking`||
	ALTER TABLE `ms_promo_codes` ADD `code_redeem_success` VARCHAR(255) NOT NULL , ADD `code_redeem_instructions` TEXT NOT NULL , ADD `code_min_amount_error` TEXT NOT NULL , ADD `code_not_selected_error` TEXT NOT NULL||


	ALTER TABLE `ms_language` ADD `_promo_code_exists_in_cart_` TEXT NOT NULL,  ADD `_bonus_coupon_` TEXT NOT NULL,  ADD `_view_my_bonus_coupon_` TEXT NOT NULL, ADD `_view_my_collection_and_coupon_` TEXT NOT NULL, ADD `_coupon_in_cart_error_` TEXT NOT NULL,  ADD `_redeem_coupon_` TEXT NOT NULL,  ADD `_coupon_type_exists_in_cart_` TEXT NOT NULL, ADD `_add_photo_to_coupon_` TEXT NOT NULL,  ADD `_add_photo_to_collection_or_coupon_` TEXT NOT NULL||

	UPDATE ms_language SET  _promo_code_exists_in_cart_='Coupon code exists in your shopping cart', _bonus_coupon_='Bonus Coupon', _view_my_bonus_coupon_='View My Bonus Coupon', _view_my_collection_and_coupon_='View My Collection & Coupon', _coupon_in_cart_error_='That coupon is already in your shopping cart', _redeem_coupon_ = 'Redeem Coupon', _coupon_type_exists_in_cart_ = 'Sorry, but that type of coupon is already in your shopping cart',_add_photo_to_collection_or_coupon_='Add photo to collection or coupon', _add_photo_to_coupon_='Select photo for coupon'||


	INSERT INTO `ms_menu_links` ( `link_url`, `link_text`, `link_main`, `link_status`, `link_order`, `link_open`, `link_location`, `link_page`, `link_cat`, `link_no_delete`, `link_shop_menu`, `link_login_page`, `link_dropdown`, `link_show_cart`, `link_html`, `link_logged_in`, `link_no_click`, `link_icon`, `link_dropdown_links`, `link_mobile_cats`) VALUES
	( '', 'Redeem Coupon', 'redeemcoupon', 0, 16, '_top', 'shop', 0, 0, 1, 'shopmenu', 0, '', 0, '', 0, 0, '', '', 0)||


	ALTER TABLE `ms_gal_exclusive` ADD `gal_redeem_credit` INT NOT NULL , ADD `redeem_credit` VARCHAR(255) NOT NULL, ADD `gal_redeem_coupon` INT NOT NULL , ADD `redeem_coupon` VARCHAR(255) NOT NULL ||

	UPDATE ms_gal_exclusive SET redeem_credit='Redeem Credit', redeem_coupon='Redeem Coupon'||

	ALTER TABLE `ms_settings` ADD `coupon_use_both` INT NOT NULL||
	ALTER TABLE `ms_promo_codes` ADD `code_price` DECIMAL(10,2) NOT NULL , ADD `code_taxable` INT NOT NULL,  ADD `code_no_discount` INT NOT NULL,  ADD `code_batch` VARCHAR(100) NOT NULL ||

	ALTER TABLE `ms_calendar` ADD `Monday` INT NOT NULL, ADD `Tuesday` INT NOT NULL , ADD `Wednesday` INT NOT NULL , ADD `Thursday` INT NOT NULL , ADD `Friday` INT NOT NULL , ADD `Saturday` INT NOT NULL , ADD `Sunday` INT NOT NULL , ADD `custom_book_days` INT NOT NULL , ADD `book_start_time` TIME NOT NULL , ADD `book_end_time` TIME NOT NULL ,  ADD `book_blocks` INT NOT NULL||

	ALTER TABLE `ms_language` ADD `_print_credit_select_from_options_below_` TEXT NOT NULL||
	UPDATE ms_language SET _print_credit_select_from_options_below_='Please select from any options below'|| 

	ALTER TABLE `ms_calendar` ADD `book_deposit_flat` DECIMAL(10,2) NOT NULL||
	ALTER TABLE `ms_menu_links` ADD `link_logged_out` INT NOT NULL||

	CREATE TABLE `ms_gallery_free` (
	  `free_id` int(11) NOT NULL AUTO_INCREMENT,
	  `free_person` int(11) NOT NULL,
	  `free_product` int(11) NOT NULL,
	  `free_gallery` int(11) NOT NULL,
	  `free_sub` int(11) NOT NULL,
	 PRIMARY KEY (`free_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000||


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}


if($sytist_version <= "1.4.0") {
	$new_version = "1.4.1";
	$uq = "

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}


if($sytist_version <= "1.4.1") {
	$new_version = "1.4.2";
	$uq = "

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}



if($sytist_version <= "1.4.2") {
	$new_version = "1.4.5";
	$uq = "

	CREATE TABLE IF NOT EXISTS `ms_canvas_prints` (
	  `cp_id` int(11) NOT NULL AUTO_INCREMENT,
	  `cp_name` varchar(255) NOT NULL,
	  `cp_width` decimal(10,2) NOT NULL,
	  `cp_height` decimal(10,2) NOT NULL,
	  `cp_price` decimal(10,4) NOT NULL,
	  `cp_shipable` int(11) NOT NULL,
	  `cp_add_shipping` decimal(10,4) NOT NULL,
	  `cp_order` int(11) NOT NULL,
	  `cp_opt1` decimal(10,4) NOT NULL,
	  `cp_opt2` decimal(10,4) NOT NULL,
	  `cp_opt3` decimal(10,4) NOT NULL,
	  `cp_opt4` decimal(10,4) NOT NULL,
	  `cp_opt5` decimal(10,4) NOT NULL,
	  `cp_opt6` decimal(10,4) NOT NULL,
	  `cp_opt7` decimal(10,4) NOT NULL,
	  `cp_opt8` decimal(10,4) NOT NULL,
	  `cp_taxable` int(11) NOT NULL,
	  `cp_no_discount` int(11) NOT NULL,
	  PRIMARY KEY (`cp_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ||


	INSERT INTO `ms_canvas_prints` (`cp_id`, `cp_name`, `cp_width`, `cp_height`, `cp_price`, `cp_shipable`, `cp_add_shipping`, `cp_order`, `cp_opt1`, `cp_opt2`, `cp_opt3`, `cp_opt4`, `cp_opt5`, `cp_opt6`, `cp_opt7`, `cp_opt8`, `cp_taxable`, `cp_no_discount`) VALUES
	(1, 'Canvas', '8.00', '10.00', '0.0000', 1, '2.0000', 1, '100.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', 1, 1),
	(2, 'Canvas', '11.00', '14.00', '50.0000', 1, '0.0000', 4, '200.0000', '95.0000', '150.0000', '150.0000', '0.0000', '0.0000', '0.0000', '0.0000', 0, 0),
	(3, 'Canvas', '16.00', '20.00', '67.0000', 1, '0.0000', 5, '280.0000', '84.0000', '200.0000', '200.0000', '0.0000', '0.0000', '0.0000', '0.0000', 0, 0),
	(7, 'Canvas', '12.00', '12.00', '0.0000', 0, '0.0000', 3, '150.0000', '50.0000', '90.0000', '90.0000', '0.0000', '0.0000', '0.0000', '0.0000', 1, 1),
	(8, 'Canvas', '8.00', '12.00', '0.0000', 1, '0.0000', 2, '120.0000', '45.0000', '80.0000', '80.0000', '0.0000', '0.0000', '0.0000', '0.0000', 1, 1),
	(9, 'Canvas', '20.00', '30.00', '0.0000', 1, '0.0000', 7, '400.0000', '125.0000', '200.0000', '225.0000', '0.0000', '0.0000', '0.0000', '0.0000', 1, 0),
	(10, 'Canvas', '20.00', '24.00', '0.0000', 1, '0.0000', 6, '300.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', 1, 1)||


	CREATE TABLE IF NOT EXISTS `ms_canvas_settings` (
	  `cp_opt1_use` int(11) NOT NULL,
	  `cp_opt1` varchar(200) NOT NULL,
	  `cp_opt2_use` int(11) NOT NULL,
	  `cp_opt2` varchar(200) NOT NULL,
	  `cp_opt3_use` int(11) NOT NULL,
	  `cp_opt3` varchar(200) NOT NULL,
	  `cp_opt4_use` int(11) NOT NULL,
	  `cp_opt4` varchar(200) NOT NULL,
	  `cp_opt5_use` int(11) NOT NULL,
	  `cp_opt5` varchar(200) NOT NULL,
	  `cp_opt6_use` int(11) NOT NULL,
	  `cp_opt6` varchar(200) NOT NULL,
	  `cp_opt7_use` int(11) NOT NULL,
	  `cp_opt7` varchar(200) NOT NULL,
	  `cp_opt8_use` int(11) NOT NULL,
	  `cp_opt8` varchar(200) NOT NULL,
	  `cp_opt_default` int(11) NOT NULL,
	  `canvas_name` varchar(255) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8||


	INSERT INTO `ms_canvas_settings` (`cp_opt1_use`, `cp_opt1`, `cp_opt2_use`, `cp_opt2`, `cp_opt3_use`, `cp_opt3`, `cp_opt4_use`, `cp_opt4`, `cp_opt5_use`, `cp_opt5`, `cp_opt6_use`, `cp_opt6`, `cp_opt7_use`, `cp_opt7`, `cp_opt8_use`, `cp_opt8`, `cp_opt_default`, `canvas_name`) VALUES
	(1, '1.5\" Deep', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, 'Canvas')||


	CREATE TABLE IF NOT EXISTS `ms_frame_images` (
	  `img_id` int(11) NOT NULL AUTO_INCREMENT,
	  `img_style` int(11) NOT NULL,
	  `img_small` varchar(255) NOT NULL,
	  `img_large` varchar(255) NOT NULL,
	  `img_order` int(11) NOT NULL,
	  `img_color` varchar(100) NOT NULL,
	  `img_default` int(11) NOT NULL,
	  `img_corners` varchar(40) NOT NULL,
	  PRIMARY KEY (`img_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ||


	INSERT INTO `ms_frame_images` (`img_id`, `img_style`, `img_small`, `img_large`, `img_order`, `img_color`, `img_default`, `img_corners`) VALUES
	(49, 2, '/sy-inc/room-view/frames/standard-frame-white.jpg', '/sy-inc/room-view/frames/standard-frame-white.jpg', 1, 'White', 0, '6-7-5.5-7'),
	(50, 2, '/sy-inc/room-view/frames/standard-frame-black.jpg', '/sy-inc/room-view/frames/standard-frame-black.jpg', 2, 'Black', 0, '5-6-4-6')||


	CREATE TABLE IF NOT EXISTS `ms_frame_mat_colors` (
	  `color_id` int(11) NOT NULL AUTO_INCREMENT,
	  `color_name` varchar(255) NOT NULL,
	  `color_color` varchar(10) NOT NULL,
	  `color_order` int(11) NOT NULL,
	  PRIMARY KEY (`color_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ||


	INSERT INTO `ms_frame_mat_colors` (`color_id`, `color_name`, `color_color`, `color_order`) VALUES
	(1, 'White', 'FFFFFF', 1),
	(2, 'Black', '333333', 3)||


	CREATE TABLE IF NOT EXISTS `ms_frame_sizes` (
	  `frame_id` int(11) NOT NULL AUTO_INCREMENT,
	  `frame_style` int(11) NOT NULL,
	  `frame_width` decimal(10,2) NOT NULL,
	  `frame_height` decimal(10,2) NOT NULL,
	  `frame_mattable` int(11) NOT NULL,
	  `frame_mat_width` decimal(10,2) NOT NULL,
	  `frame_price` decimal(10,4) NOT NULL,
	  `frame_mat_price` decimal(10,4) NOT NULL,
	  `frame_mat_print_width` decimal(10,2) NOT NULL,
	  `frame_mat_print_height` decimal(10,2) NOT NULL,
	  `frame_default` int(11) NOT NULL,
	  `frame_order` int(11) NOT NULL,
	  `frame_shipable` int(11) NOT NULL,
	  `frame_add_shipping` decimal(10,4) NOT NULL,
	  PRIMARY KEY (`frame_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92 ||


	INSERT INTO `ms_frame_sizes` (`frame_id`, `frame_style`, `frame_width`, `frame_height`, `frame_mattable`, `frame_mat_width`, `frame_price`, `frame_mat_price`, `frame_mat_print_width`, `frame_mat_print_height`, `frame_default`, `frame_order`, `frame_shipable`, `frame_add_shipping`) VALUES
	(65, 2, '5.00', '7.00', 0, '0.00', '30.0000', '0.0000', '0.00', '0.00', 0, 1, 1, '0.0000'),
	(66, 2, '8.00', '10.00', 0, '1.50', '40.0000', '40.0000', '5.00', '7.00', 0, 2, 1, '0.0000'),
	(67, 2, '8.00', '12.00', 0, '1.50', '50.0000', '50.0000', '6.00', '10.00', 1, 3, 1, '0.0000'),
	(68, 2, '10.00', '10.00', 0, '2.00', '50.0000', '50.0000', '8.00', '8.00', 0, 5, 1, '0.0000'),
	(69, 2, '11.00', '14.00', 0, '2.00', '60.0000', '60.0000', '8.00', '10.00', 0, 7, 1, '0.0000'),
	(70, 2, '16.00', '20.00', 0, '3.00', '70.0000', '70.0000', '14.00', '17.00', 0, 9, 1, '0.0000')||


	CREATE TABLE IF NOT EXISTS `ms_frame_styles` (
	  `style_id` int(11) NOT NULL AUTO_INCREMENT,
	  `style_name` varchar(255) NOT NULL,
	  `style_descr` text NOT NULL,
	  `style_frame_width` decimal(10,2) NOT NULL,
	  `style_frame_corners` varchar(100) NOT NULL,
	  `style_order` int(11) NOT NULL,
	  `style_mat_colors` text NOT NULL,
	  `style_taxable` int(11) NOT NULL,
	  `style_no_discount` int(11) NOT NULL,
	  PRIMARY KEY (`style_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ||


	INSERT INTO `ms_frame_styles` (`style_id`, `style_name`, `style_descr`, `style_frame_width`, `style_frame_corners`, `style_order`, `style_mat_colors`, `style_taxable`, `style_no_discount`) VALUES
	(2, 'Standard Frame', '<p>Standard black or white frame.</p>', '1.25', '5-6-4-6', 1, '1,', 1, 0)||


	CREATE TABLE IF NOT EXISTS `ms_wall_language` (
	  `_wd_instructions_title_` varchar(255) NOT NULL,
	  `_wd_instructions_` text NOT NULL,
	  `_wd_frame_color_` varchar(255) NOT NULL,
	  `_wd_matting_` varchar(255) NOT NULL,
	  `_wd_no_mat_` varchar(255) NOT NULL,
	  `_wd_total_width_with_frame_` varchar(255) NOT NULL,
	  `_wd_print_size_` varchar(255) NOT NULL,
	  `_wd_select_photos_error_title_` varchar(255) NOT NULL,
	  `_wd_select_photos_error_text_` text NOT NULL,
	  `_wd_review_your_photos_` varchar(255) NOT NULL,
	  `_wd_review_your_photos_text_` text NOT NULL,
	  `_wd_added_to_cart_` varchar(255) NOT NULL,
	  `_wd_return_to_gallery_` varchar(255) NOT NULL,
	  `_wd_purchase_` varchar(255) NOT NULL,
	  `_wd_upload_or_take_photo_` varchar(255) NOT NULL,
	  `_wd_upload_room_photo_instructions_` text NOT NULL,
	  `_wd_reset_` varchar(255) NOT NULL,
	  `_wd_zoom_in_` varchar(255) NOT NULL,
	  `_wd_zoom_out_` varchar(255) NOT NULL,
	  `_wd_done_` varchar(255) NOT NULL,
	  `_wd_set_room_measurement_` varchar(255) NOT NULL,
	  `_wd_set_room_measurement_select_area_error_` text NOT NULL,
	  `_wd_set_room_measurement_enter_size_error_` text NOT NULL,
	  `_wd_set_room_measurement_instructions_` text NOT NULL,
	  `_wd_enter_width_or_height_` text NOT NULL,
	  `_wd_width_` varchar(255) NOT NULL,
	  `_wd_or_height_` varchar(255) NOT NULL,
	  `_wd_set_` varchar(255) NOT NULL,
	  `_wd_framed_print_` varchar(255) NOT NULL,
	  `_wd_canvas_print_` varchar(255) NOT NULL,
	  `_wd_bw_` varchar(255) NOT NULL,
	  `_wd_original_` varchar(255) NOT NULL,
	  `_wd_adjust_` varchar(255) NOT NULL,
	  `_wd_choose_photo_` varchar(255) NOT NULL,
	  `_wd_rotate_` varchar(255) NOT NULL,
	  `_wd_remove_` varchar(255) NOT NULL,
	  `_wd_room_measurement_` varchar(255) NOT NULL,
	  `_wd_change_room_` varchar(255) NOT NULL,
	  `_wd_wall_collections_` varchar(255) NOT NULL,
	  `_wd_add_new_print_` varchar(255) NOT NULL,
	  `_wd_my_saved_rooms_` varchar(255) NOT NULL,
	  `_wd_save_` varchar(255) NOT NULL,
	  `_wd_my_room_photos_` varchar(255) NOT NULL,
	  `_wd_stock_room_photos_` varchar(255) NOT NULL,
	  `_wd_save_view_` varchar(255) NOT NULL,
	  `_wd_save_as_new_` varchar(255) NOT NULL,
	  `_wd_copy_link_` varchar(255) NOT NULL,
	  `_wd_close_` varchar(255) NOT NULL,
	  `_wd_you_have_no_saved_collections_` text NOT NULL,
	  `_wd_click_photo_on_wall_for_options_` text NOT NULL,
	  `_wd_upload_your_own_photo_` text NOT NULL,
	  `_wd_view_saved_` text NOT NULL,
	  `_wd_view_saved_text_` text NOT NULL,
	  `_wd_frames_title_` varchar(255) NOT NULL,
	  `_wd_frames_text_` text NOT NULL,
	  `_wd_canvases_title_` varchar(255) NOT NULL,
	  `_wd_canvases_text_` text NOT NULL,
	  `_wd_collections_title_` varchar(255) NOT NULL,
	  `_wd_collections_text_` text NOT NULL,
	  `_wd_wall_designer_tab_` varchar(255) NOT NULL,
	  `_wd_wall_designer_text_` text NOT NULL,
	  `_wd_view_` varchar(255) NOT NULL,
	  `_wd_inches_` varchar(255) NOT NULL,
	  `_wd_centimeters_` varchar(255) NOT NULL,
	  `_wd_print_` varchar(255) NOT NULL

	) ENGINE=MyISAM DEFAULT CHARSET=utf8||


	INSERT INTO `ms_wall_language` (`_wd_instructions_title_`, `_wd_instructions_`, `_wd_frame_color_`, `_wd_matting_`, `_wd_no_mat_`, `_wd_total_width_with_frame_`, `_wd_print_size_`, `_wd_select_photos_error_title_`, `_wd_select_photos_error_text_`, `_wd_review_your_photos_`, `_wd_review_your_photos_text_`, `_wd_added_to_cart_`, `_wd_return_to_gallery_`, `_wd_purchase_`, `_wd_upload_or_take_photo_`, `_wd_upload_room_photo_instructions_`, `_wd_reset_`, `_wd_zoom_in_`, `_wd_zoom_out_`, `_wd_done_`, `_wd_set_room_measurement_`, `_wd_set_room_measurement_select_area_error_`, `_wd_set_room_measurement_enter_size_error_`, `_wd_set_room_measurement_instructions_`, `_wd_enter_width_or_height_`, `_wd_width_`, `_wd_or_height_`, `_wd_set_`, `_wd_framed_print_`, `_wd_canvas_print_`, `_wd_bw_`, `_wd_original_`, `_wd_adjust_`, `_wd_choose_photo_`, `_wd_rotate_`, `_wd_remove_`, `_wd_room_measurement_`, `_wd_change_room_`, `_wd_wall_collections_`, `_wd_add_new_print_`, `_wd_my_saved_rooms_`, `_wd_save_`, `_wd_my_room_photos_`, `_wd_stock_room_photos_`, `_wd_save_view_`, `_wd_save_as_new_`, `_wd_copy_link_`, `_wd_close_`, `_wd_you_have_no_saved_collections_`, `_wd_click_photo_on_wall_for_options_`, `_wd_upload_your_own_photo_`, `_wd_view_saved_`, `_wd_view_saved_text_`, `_wd_frames_title_`, `_wd_frames_text_`, `_wd_canvases_title_`, `_wd_canvases_text_`, `_wd_collections_title_`, `_wd_collections_text_`, `_wd_wall_designer_tab_`, `_wd_wall_designer_text_`, `_wd_view_`, `_wd_inches_`, `_wd_centimeters_`,`_wd_print_`) VALUES
	('Using the Wall Designer', '<p>Use the menu below to select a framed or canvas print and view it on the wall. </p><p>You can change the room, add additional photos, move around the photos,&nbsp;and select from photos from your gallery using the Choose Photo option.</p>', 'Frame Color:', 'Matting:', 'No Mat', 'Approximate total width with frame:', 'Print size:', 'Oops!', 'It appears you have not selected a photo for each product. Please select a photo for each of the products on the screen by selected choose photos.', 'Review Your Photos', 'Below are the photos you have selected for your products. Review any available options and add to cart at the bottom of the window.', 'Added To Cart', 'Gallery', 'Purchase', 'Upload or take room photo ', 'You can upload or take a photo of the wall of your room to see the photos on. When taking a photo, be sure there is plently of light. Once you have uploaded the photo, you will need to enter in the measurement of the wall in the photo.', 'Reset', 'Zoom In', 'Zoom Out', 'Done', 'Set Room Measurement', 'Please select an area on the photo closest to the wall.', 'Please enter a width or height of the selected area', 'Select an area with your mouse on the photo closest to the wall and enter in the [INCHES_CENTIMETRES] of the width or height of the selection below.', 'Enter the width OR the height in [INCHES_CENTIMETRES] of the selected area below and click Calculate.', 'Width', 'or Height: ', 'Set', 'Framed Print', 'Canvas Print', 'B&W', 'Original', 'Adjust', 'Choose Photo', 'Rotate', 'Remove', 'Room Measurement', 'Rooms', 'Collections', 'Add Print', 'My Saved Rooms', 'Save', 'My Uploaded Rooms', 'Stock Rooms', 'Save View', 'Save As New', 'Copy Link', 'Close', 'You have no saved collections', 'Click photo on wall for options', 'UPLOAD YOUR OWN ROOM PHOTO', 'Your wall designer has been saved.', 'You can copy the link below to share this.', 'Framed Prints', '<p>Select from the available frames styles.</p>', 'Canvas Prints', '<p>Select from the available canvas sizes.</p>', 'Collections', '<p>Select from these collections to view them on the wall.</p>', 'Wall Designer', '<p>With the Wall Designer you can view your photos in frames and canvases on a wall of a room.\r\n</p><p style=\"text-align: center;\"><img src=\"https://s3-us-west-2.amazonaws.com/sytist1/wall-designer.jpg\" style=\"max-width: 400px; width: 100%; height: auto; margin: auto;\" rel=\"max-width: 400px; width: 100%; height: auto; margin: auto;\">\r\n</p>', 'Go To Wall Designer', 'inches', 'centimetres','print')||


	CREATE TABLE IF NOT EXISTS `ms_wall_rooms` (
	  `room_id` int(11) NOT NULL AUTO_INCREMENT,
	  `room_name` varchar(255) NOT NULL,
	  `room_small` varchar(255) NOT NULL,
	  `room_large` varchar(255) NOT NULL,
	  `room_photo_width` int(11) NOT NULL,
	  `room_photo_height` int(11) NOT NULL,
	  `room_width` decimal(10,2) NOT NULL,
	  `room_center` decimal(10,4) NOT NULL,
	  `room_base` decimal(10,4) NOT NULL,
	  `room_order` int(11) NOT NULL,
	  `room_person` int(11) NOT NULL,
	  PRIMARY KEY (`room_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ||


	INSERT INTO `ms_wall_rooms` (`room_id`, `room_name`, `room_small`, `room_large`, `room_photo_width`, `room_photo_height`, `room_width`, `room_center`, `room_base`, `room_order`, `room_person`) VALUES
	(16, 'Stock Room 1', '/sy-inc/room-view/frames/living-room-3.jpg', '/sy-inc/room-view/frames/living-room-3.jpg', 1200, 800, '180.00', '0.4933', '0.4392', 1, 0)||


	CREATE TABLE IF NOT EXISTS `ms_wall_saves` (
	  `wall_id` int(11) NOT NULL AUTO_INCREMENT,
	  `wall_person` int(11) NOT NULL,
	  `wall_date` datetime NOT NULL,
	  `wall_room_data` text NOT NULL,
	  `wall_items` text NOT NULL,
	  `wall_link` varchar(255) NOT NULL,
	  `wall_name` varchar(255) NOT NULL,
	  `wall_cart` int(11) NOT NULL,
	  `wall_ip` varchar(60) NOT NULL,
	  `wall_collection` int(11) NOT NULL,
	  `wall_collection_order` int(11) NOT NULL,
	  `wall_no_edit` int(11) NOT NULL,
	  `wall_date_id` int(11) NOT NULL,
	  `wall_sub_id` int(11) NOT NULL,
	  `wall_no_price` int(11) NOT NULL,
	  PRIMARY KEY (`wall_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=197 ||


	CREATE TABLE IF NOT EXISTS `ms_wall_settings` (
	  `size_symbol` varchar(10) NOT NULL,
	  `math_type` varchar(255) NOT NULL,
	  `admin_link` int(11) NOT NULL,
	  `offer_bw` int(11) NOT NULL,
	  `offer_frames` int(11) NOT NULL,
	  `offer_canvas` int(11) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8||


	INSERT INTO `ms_wall_settings` (`size_symbol`, `math_type`, `admin_link`, `offer_bw`, `offer_frames`, `offer_canvas`) VALUES
	('\"', 'inches', 0, 1, 1, 1)||


	ALTER TABLE `ms_cart` ADD `cart_room_view` INT NOT NULL , ADD `cart_frame_style` INT NOT NULL, ADD `cart_frame_size` INT NOT NULL , ADD `cart_frame_image` INT NOT NULL||
	ALTER TABLE `ms_cart` ADD `cart_mat_size` DECIMAL(10,2) NOT NULL , ADD `cart_mat_color` VARCHAR(20) NOT NULL||
	ALTER TABLE `ms_cart` ADD `cart_canvas_id` INT NOT NULL, ADD `cart_canvas_option` VARCHAR(255) NOT NULL ||

	ALTER TABLE `ms_photo_products_lists` ADD `list_wall_designer` INT NOT NULL ||

	ALTER TABLE `ms_packages` ADD `package_collapse_options` INT NOT NULL ||
	UPDATE ms_packages SET packages_collapse_options='1' ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}
if($sytist_version <= "1.4.5") {
	$new_version = "1.5.0";
	$uq = "

	ALTER TABLE `ms_packages` ADD `package_collapse_options` INT NOT NULL ||
	UPDATE ms_packages SET packages_collapse_options='1' ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}
if($sytist_version <= "1.5.0") {
	$new_version = "1.5.1";
	$uq = "

	CREATE TABLE IF NOT EXISTS `ms_cart_archive` (
	  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
	  `cart_product` int(11) NOT NULL DEFAULT '0',
	  `cart_store_product` int(11) NOT NULL DEFAULT '0',
	  `cart_sub_id` int(11) NOT NULL DEFAULT '0',
	  `cart_product_name` varchar(255) NOT NULL DEFAULT '',
	  `cart_price` decimal(10,2) NOT NULL DEFAULT '0.00',
	  `cart_taxable` int(11) NOT NULL DEFAULT '0',
	  `cart_ship` int(11) NOT NULL DEFAULT '0',
	  `cart_extra_ship` decimal(10,2) NOT NULL DEFAULT '0.00',
	  `cart_download` int(11) NOT NULL DEFAULT '0',
	  `cart_session` varchar(80) NOT NULL DEFAULT '0',
	  `cart_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `cart_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
	  `cart_client` varchar(255) NOT NULL,
	  `cart_order` int(11) NOT NULL DEFAULT '0',
	  `cart_download_attempts` int(11) NOT NULL DEFAULT '0',
	  `cart_download_cutoff_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `cart_download_ip` varchar(20) NOT NULL DEFAULT '',
	  `cart_download_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `cart_coupon` int(11) NOT NULL DEFAULT '0',
	  `cart_coupon_name` varchar(255) NOT NULL DEFAULT '',
	  `cart_discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
	  `cart_ip` varchar(50) NOT NULL DEFAULT '',
	  `cart_service` int(11) NOT NULL,
	  `cart_photo_prod` int(11) NOT NULL,
	  `cart_pic_id` int(11) NOT NULL,
	  `cart_pic_date_id` int(11) NOT NULL,
	  `cart_pic_org` varchar(255) NOT NULL,
	  `cart_pic_date_org` varchar(255) NOT NULL,
	  `cart_cost` decimal(10,2) NOT NULL,
	  `cart_color_id` int(11) NOT NULL,
	  `cart_color_name` varchar(30) NOT NULL,
	  `cart_crop_x1` decimal(10,2) NOT NULL,
	  `cart_crop_y1` decimal(10,2) NOT NULL,
	  `cart_crop_x2` decimal(10,2) NOT NULL,
	  `cart_crop_y2` decimal(10,2) NOT NULL,
	  `cart_crop_rotate` int(11) NOT NULL,
	  `cart_sub_gal_id` int(11) NOT NULL,
	  `cart_download_log` text NOT NULL,
	  `cart_package` int(11) NOT NULL,
	  `cart_package_photo` int(11) NOT NULL,
	  `cart_sku` varchar(255) NOT NULL,
	  `cart_group_id` int(11) NOT NULL,
	  `cart_reg_key` varchar(100) NOT NULL,
	  `cart_photo_prod_connect` int(11) NOT NULL,
	  `cart_dis_on` int(11) NOT NULL,
	  `cart_invoice` int(11) NOT NULL,
	  `cart_photo_order` int(11) NOT NULL,
	  `cart_product_photo` int(11) NOT NULL,
	  `cart_product_select_photos` int(11) NOT NULL,
	  `cart_order_message` text NOT NULL,
	  `cart_no_discount` int(11) NOT NULL,
	  `cart_print_credit` varchar(100) NOT NULL,
	  `cart_credit` decimal(10,2) NOT NULL,
	  `cart_package_photo_extra` int(11) NOT NULL,
	  `cart_notes` text NOT NULL,
	  `cart_allow_notes` int(11) NOT NULL,
	  `cart_min_order` decimal(10,2) NOT NULL,
	  `cart_package_buy_all` int(11) NOT NULL,
	  `cart_buy_all_location` text NOT NULL,
	  `cart_download_file` text NOT NULL,
	  `cart_disable_download` int(11) NOT NULL,
	  `cart_account_credit` decimal(10,2) NOT NULL,
	  `cart_account_credit_for` int(11) NOT NULL,
	  `cart_reg_message` text NOT NULL,
	  `cart_reg_message_name` varchar(255) NOT NULL,
	  `cart_reg_no_display_amount` int(11) NOT NULL,
	  `cart_credit_access` int(11) NOT NULL,
	  `cart_paid_access` int(11) NOT NULL,
	  `cart_no_delete` int(11) NOT NULL,
	  `cart_pre_reg` int(11) NOT NULL,
	  `cart_custom` text NOT NULL,
	  `cart_subscription` int(11) NOT NULL,
	  `cart_package_include` int(11) NOT NULL,
	  `cart_package_no_select` int(11) NOT NULL,
	  `cart_package_photo_extra_on` int(11) NOT NULL,
	  `cart_photo_bg` int(11) NOT NULL,
	  `cart_thumb` text NOT NULL,
	  `cart_booking` int(11) NOT NULL,
	  `cart_bonus_coupon` int(11) NOT NULL,
	  `cart_room_view` int(11) NOT NULL,
	  `cart_frame_style` int(11) NOT NULL,
	  `cart_frame_size` int(11) NOT NULL,
	  `cart_frame_image` int(11) NOT NULL,
	  `cart_mat_size` decimal(10,2) NOT NULL,
	  `cart_mat_color` varchar(20) NOT NULL,
	  `cart_canvas_id` int(11) NOT NULL,
	  `cart_canvas_option` varchar(255) NOT NULL,
	  PRIMARY KEY (`cart_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ||

	ALTER TABLE  `ms_orders` ADD  `order_archive_table` INT NOT NULL ||
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}

if($sytist_version <= "1.5.1") {
	$new_version = "1.6.0";
	$uq = "
	CREATE TABLE IF NOT EXISTS `ms_contracts` (
	  `contract_id` int(11) NOT NULL AUTO_INCREMENT,
	  `title` varchar(255) NOT NULL,
	  `template` int(11) NOT NULL,
	  `due_date` date NOT NULL,
	  `content` text NOT NULL,
	  `my_name` varchar(255) NOT NULL,
	  `my_signature` text NOT NULL,
	  `person_id` int(11) NOT NULL,
	  `first_name` varchar(255) NOT NULL,
	  `last_name` varchar(255) NOT NULL,
	  `signature_name` varchar(255) NOT NULL,
	  `signature` text NOT NULL,
	  `signature_name2` varchar(255) NOT NULL,
	  `signature2` text NOT NULL,
	  `ip_address` varchar(100) NOT NULL,
	  `browser_info` text NOT NULL,
	  `last_modified` datetime NOT NULL,
	  `link` varchar(255) NOT NULL,
	  `my_ip_address` varchar(100) NOT NULL,
	  `my_browser_info` text NOT NULL,
	  `ip_address2` varchar(100) NOT NULL,
	  `browser_info2` text NOT NULL,
	  `my_signed_date` datetime NOT NULL,
	  `signed_date` datetime NOT NULL,
	  `signed_date2` datetime NOT NULL,
	  `signature_svg` text NOT NULL,
	  `signature2_svg` text NOT NULL,
	  `my_signature_svg` text NOT NULL,
	  `pin` varchar(10) NOT NULL,
	  `email` varchar(255) NOT NULL,
	  `email2` varchar(255) NOT NULL,
	  `invoice` int(11) NOT NULL,
	  PRIMARY KEY (`contract_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ||

	INSERT INTO `ms_contracts` (`contract_id`, `title`, `template`, `due_date`, `content`, `my_name`, `my_signature`, `person_id`, `first_name`, `last_name`, `signature_name`, `signature`, `signature_name2`, `signature2`, `ip_address`, `browser_info`, `last_modified`, `link`, `my_ip_address`, `my_browser_info`, `ip_address2`, `browser_info2`, `my_signed_date`, `signed_date`, `signed_date2`, `signature_svg`, `signature2_svg`, `my_signature_svg`, `pin`, `email`, `email2`, `invoice`) VALUES
	(4, 'Example', 1, '0000-00-00', '<div>This is just an example of a contract template. You will need to create your own contracts and templates.</div><p>There are a few replace codes you can use in your templates to discount some information.</p><p>[NAME] will display the name entered into Signature 1.</p><p>[NAME2] will display the name entered into&nbsp;Signature 2 if there is one.</p><p>[MY_NAME] will display your name as entered in for the My Name field</p>', '', '', 0, '', '', '', '', '', '', '', '', '2016-10-26 10:57:26', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', 0)||

	CREATE TABLE IF NOT EXISTS `ms_contracts_language` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `_contract_header_` text NOT NULL,
	  `_contract_instructions_` text NOT NULL,
	  `_write_your_name_` text NOT NULL,
	  `_type_your_name_` text NOT NULL,
	  `_did_not_sign_name_error_` text NOT NULL,
	  `_name_doesnt_match_` text NOT NULL,
	  `_name_is_blank_` text NOT NULL,
	  `_signed_on_` text NOT NULL,
	  `_done_` text NOT NULL,
	  `_clear_` text NOT NULL,
	  `_sign_contract_` text NOT NULL,
	  `_type_` text NOT NULL,
	  `_print_` text NOT NULL,
	  `_print_contract_` text NOT NULL,
	  `_enter_pin_message_` text NOT NULL,
	  `_incorrect_pin_` text NOT NULL,
	  `_submit_pin_` text NOT NULL,
	  `_success_title_` text NOT NULL,
	  `_success_message_` text NOT NULL,
	  `_success_message_additional_signature_` text NOT NULL,
	  `_success_message_with_invoice_` text NOT NULL,
	  `_pay_invoice_link_` varchar(200) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ||

	INSERT INTO `ms_contracts_language` (`id`, `_contract_header_`, `_contract_instructions_`, `_write_your_name_`, `_type_your_name_`, `_did_not_sign_name_error_`, `_name_doesnt_match_`, `_name_is_blank_`, `_signed_on_`, `_done_`, `_clear_`, `_sign_contract_`, `_type_`, `_print_`, `_print_contract_`, `_enter_pin_message_`, `_incorrect_pin_`, `_submit_pin_`, `_success_title_`, `_success_message_`, `_success_message_additional_signature_`, `_success_message_with_invoice_`, `_pay_invoice_link_`) VALUES
	(1, 'Contract', 'Please read the contract and sign at the bottom.', 'Sign your name with your mouse or finger on touch devices.', 'Type your name', 'Please sign your name first', 'Your name does match. Enter your name as typed or if this is intentional, click Sign Contract again.', 'Your name is blank', 'Signed', 'Done', 'Clear', 'Sign Contract', 'Type', 'Print', 'Print Contract', 'Please enter the pin number provided to view this contract', 'Incorrect PIN number', 'Submit', 'Thank You', 'Your contract has been signed. You have been emailed a copy of this contract to the email address.', 'Your contract has been signed and awaiting an additional signature. You have been emailed a copy of this contract to the email address.', 'Your contract has been signed. You have been emailed a copy of this contract to the email address. Select the Pay Invoice option below to view and pay.', 'View & Pay Invoice ')||

	CREATE TABLE IF NOT EXISTS `ms_payment_schedule` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `order_id` int(11) NOT NULL,
	  `amount` decimal(10,4) NOT NULL,
	  `due_date` date NOT NULL,
	  `payment` decimal(10,4) NOT NULL,
	  `payment_date` datetime NOT NULL,
	  `pay_transaction` varchar(200) NOT NULL,
	  `payment_option` varchar(200) NOT NULL,
	  `payment_ip` varchar(200) NOT NULL,
	  `payment_type` varchar(200) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ||

	INSERT INTO `ms_emails` (`email_name`, `email_descr`, `email_codes`, `email_from_email`, `email_from_name`, `email_subject`, `email_message`, `email_type`, `email_no_delete`, `email_download_descr`, `email_shipping_descr`, `email_paypal_pending`, `email_offline_pending`, `email_id_name`) VALUES
	( 'Scheduled Payment Reminder', 'This is the email sent to to the customer for a reminder a scheduled payment is due.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s last name\r\n[URL] = The URL to your website\r\n[WEBSITE_NAME] = The name of your website\r\n[INVOICE_NUMBER] = Invoice number\r\n[SCHEDULED_PAYMENTS] = Details of the scheduled payments\r\n[INVOICE_LINK] = Starts link to invoice\r\n[/LINK] = Closes link to invoice', '', '', 'Payment Due For Invoice #[INVOICE_NUMBER] at [WEBSITE_NAME]', '<p>Hello [FIRST_NAME],</p><p>This is a friendly reminder that a payment is due for invoice [INVOICE_NUMBER].</p><p>[SCHEDULED_PAYMENTS]</p><p><strong>[INVOICE_LINK]Click here to view details&nbsp;&amp; pay your invoice[/LINK]</strong></p><p>Thank you,<br></p><p>[WEBSITE_NAME]</p>', 0, 1, '', '', '', '', 'paymentreminder'),
	( 'Email Invoice', 'This is the email used when emailing someone an invoice.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s last name\r\n[URL] = The URL to your website\r\n[WEBSITE_NAME] = The name of your website\r\n[PAYMENT_AMOUNT] = Amount paid\r\n[SCHEDULED_PAYMENTS] = Details of the scheduled payments', '', '', 'Your Invoice #[INVOICE_NUMBER] at [WEBSITE_NAME]', '<p>Hello [FIRST_NAME],</p><p>Invoice [INVOICE_NUMBER] has been created.&nbsp;</p><p>Amount: [INVOICE_TOTAL]<br>Date: [DATE]<br>Due Date: [DUE_DATE]<br>[SCHEDULED_PAYMENTS]</p><p><strong>[INVOICE_LINK]Click here to view details&nbsp;&amp; pay your invoice[/LINK]</strong></p><p>Thank you,<br>[WEBSITE_NAME]</p>', 0, 1, '', '', '', '', 'emailinvoice'),
	( 'Scheduled Payment Paid', 'This is the email sent to the customer when someone pays on a scheduled payment.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s last name\r\n[URL] = The URL to your website\r\n[WEBSITE_NAME] = The name of your website\r\n[PAYMENT_AMOUNT] = Amount paid\r\n[SCHEDULED_PAYMENTS] = Details of the scheduled payments', '', '', 'Payment Received For Invoice #[INVOICE_NUMBER] at [WEBSITE_NAME]', '<p>Hello [FIRST_NAME],</p><p>Thank you for your payment of [PAYMENT_AMOUNT]. Below is the status of your scheduled payments.</p><p>[SCHEDULED_PAYMENTS]</p><p>Thank you,<br>[WEBSITE_NAME]</p>', 0, 1, '', '', '', '', 'scheduledpayment'),
	('Contract Signed', 'This is the email sent to the customer when someone signs a contract.', '[NAME] = Name on contract\r\n[URL] = The URL to your website\r\n[WEBSITE_NAME] = The name of your website\r\n[CONTRACT] = Content of the contract\r\n[INVOICE_DUE] = Due date\r\n[CONTRACT_LINK] = Opens link to view contract\r\n[/LINK] = Closes link to view contract\r\n[PIN] = Contract PIN number', '', '', 'Contract Signed [WEBSITE_NAME] ', '<p>Hello [NAME],\r\n</p><p>Thank you for signing the contract sent to you. Below is a copy of the contract. [CONTRACT_LINK]You can view it online here[/LINK].</p><p>Pin number to view contract: [PIN]</p><p>[CONTRACT]</p><p>Thank you,<br>[WEBSITE_NAME]<br></p>', 0, 1, '', '', '', '', 'contractsigned'),
	('Contract', 'This is the email sent when sending a contract to someone.', '[NAME] = Name on contract\r\n[URL] = The URL to your website\r\n[WEBSITE_NAME] = The name of your website\r\n\r\n[DUE_DATE] = Contract due date\r\n[INVOICE_DUE] = Due date\r\n[CONTRACT_LINK] = Opens link to view contract\r\n[/LINK] = Closes link to view contract\r\n[PIN] = Contract PIN number', '', '', 'You have received a contract from [WEBSITE_NAME] ', '<p>Hello [NAME],\r\n</p><p>You have received a contract from [WEBSITE_NAME]. Please review and sign the contract by [DUE_DATE]</p><p>Pin number to view contract: [PIN]</p><p><strong>[CONTRACT_LINK]Review &amp; Sign Contract[/LINK]</strong></p><p>Thank you,<br>[WEBSITE_NAME]<br></p>', 0, 1, '', '', '', '', 'sendcontract')||

	ALTER TABLE `ms_settings` ADD `contract_folder` VARCHAR(100) NOT NULL ||
	ALTER TABLE `ms_settings` ADD `default_sig` VARCHAR(255) NOT NULL , ADD `default_sig_svg` TEXT NOT NULL ||
	ALTER TABLE `ms_notes` ADD `note_ip` VARCHAR(200) NOT NULL||
	ALTER TABLE `ms_notes` ADD `note_admin` INT NOT NULL ||
	ALTER TABLE `ms_notes` ADD `note_is_note` INT NOT NULL ||
	ALTER TABLE `ms_language` ADD `_due_` TEXT NOT NULL,  ADD `_paid_` TEXT NOT NULL,  ADD `_select_payment_amount_` TEXT NOT NULL||
	UPDATE ms_language SET _due_='Due', _paid_='Paid', _select_payment_amount_='Select payment amount'  ||
	ALTER TABLE `ms_language` ADD `_invoice_` TEXT NOT NULL,  ADD `_ty_for_payment_` TEXT NOT NULL,  ADD `_make_payment_` TEXT NOT NULL||
	UPDATE ms_language SET _invoice_='Invoice', _ty_for_payment_='Thank you for your payment', _make_payment_='Make Payment'  ||
	ALTER TABLE `ms_language` ADD `_my_contracts_` TEXT NOT NULL||
	UPDATE ms_language SET _my_contracts_='My Contracts'  ||

	INSERT INTO `ms_crons` (`cron_id`, `cron_email`, `cron_what`, `cron_days`, `cron_time`, `cron_check_date`, `cron_status`, `cron_gal_access`, `cron_gal_preregister`, `cron_gal_viewed`, `cron_gal_purchased`, `cron_gal_collected_email`, `cron_gal_owner`, `cron_gal_no_order`) VALUES ('0', '0', 'payments', '1', '08:00:00', '2016-09-27', '0', '0', '0', '0', '0', '0', '0', '0')||

	ALTER TABLE `ms_cron_emails` ADD `from_order_id` INT NOT NULL||

	CREATE TABLE IF NOT EXISTS `ms_order_export_items` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `field_name` varchar(50) NOT NULL,
	  `label` varchar(100) NOT NULL,
	  `function` varchar(50) NOT NULL,
	  `display_order` int(11) NOT NULL,
	  `separate_products` varchar(5) NOT NULL,
	  `status` int(11) NOT NULL,
	  `product_format` varchar(100) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8||


	INSERT INTO `ms_order_export_items` (`id`, `field_name`, `label`, `function`, `display_order`, `separate_products`, `status`, `product_format`) VALUES
	(1, 'order_id', 'ORDER #', 'fieldname', 1, '', 1, ''),
	(2, 'products', 'PRODUCTS', 'getproducts', 7, '; ', 1, '([QTY]) [PRODUCT]'),
	(3, 'order_date', 'DATE', 'fieldname', 2, '', 0, ''),
	(4, 'order_first_name', 'First Name', 'fieldname', 3, '', 1, ''),
	(5, 'order_last_name', 'Last Name', 'fieldname', 4, '', 1, ''),
	(6, 'file_name', 'FILE NAME', 'getfilename', 6, '', 1, ''),
	(7, 'options', 'OPTIONS', 'getoptions', 8, '', 1, ''),
	(8, 'order_extra_val_1', 'Child Name', 'fieldname', 5, '', 1, ''),
	(9, 'productskus', 'PRODUCTS', 'getproductskus', 7, '; ', 0, '([QTY]) [PRODUCT]'),
	(10, 'file_link', 'FILE LINK', 'getfilelink', 6, '', 0, '')||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}

if($sytist_version <= "1.6.0") {
	$new_version = "1.7.0";
	$uq = "
	INSERT INTO ms_menu_links SET link_text='eGift Cards', link_status='0', link_order='20', link_open='_top', link_no_delete='1', link_location='shop', link_shop_menu='shopmenu', link_main='giftcertificates'||

	ALTER TABLE `ms_cart` ADD `cart_gift_certificate` INT NOT NULL , ADD `cart_gift_certificate_to_name` VARCHAR(255) NOT NULL , ADD `cart_gift_certificate_to_email` VARCHAR(255) NOT NULL , ADD `cart_gift_certificate_from_name` VARCHAR(255) NOT NULL , ADD `cart_gift_certificate_from_email` VARCHAR(255) NOT NULL , ADD `cart_gift_certificate_message` TEXT NOT NULL ||

	ALTER TABLE `ms_cart_archive` ADD `cart_gift_certificate` INT NOT NULL , ADD `cart_gift_certificate_to_name` VARCHAR(255) NOT NULL , ADD `cart_gift_certificate_to_email` VARCHAR(255) NOT NULL , ADD `cart_gift_certificate_from_name` VARCHAR(255) NOT NULL , ADD `cart_gift_certificate_from_email` VARCHAR(255) NOT NULL , ADD `cart_gift_certificate_message` TEXT NOT NULL ||

	ALTER TABLE `ms_orders` ADD `order_gift_certificate` DECIMAL(10,2) NOT NULL ||

	ALTER TABLE `ms_pending_orders` ADD `order_gift_certificate` DECIMAL(10,2) NOT NULL ||

	ALTER TABLE `ms_orders` ADD `order_gift_certificate_id` VARCHAR(200) NOT NULL ||

	ALTER TABLE `ms_pending_orders` ADD `order_gift_certificate_id` VARCHAR(200) NOT NULL ||

	CREATE TABLE IF NOT EXISTS `ms_gift_certificate_language` (
	  `_gift_certificate_name_` text NOT NULL,
	  `_gift_certificate_title_` text NOT NULL,
	  `_gift_certificate_text_` text NOT NULL,
	  `_gift_certificate_to_name_` text NOT NULL,
	  `_gift_certificate_to_email_` text NOT NULL,
	  `_gift_certificate_from_name_` text NOT NULL,
	  `_gift_certificate_from_email_` text NOT NULL,
	  `_gift_certificate_message_` text NOT NULL,
	  `_gift_certificate_enter_amount_` text NOT NULL,
	  `_gift_certificate_select_amount_` text NOT NULL,
	  `_gift_certificate_select_amount_error_` text NOT NULL,
	  `_gift_certificate_add_another_` text NOT NULL,
	  `_gift_certificate_from_` text NOT NULL,
	  `_gift_certificate_to_` text NOT NULL,
	  `_gift_certificate_redeem_` text NOT NULL,
	  `_gift_certificate_bottom_text_` text NOT NULL,
	  `_gift_certificate_redeem_title_` text NOT NULL,
	  `_gift_certificate_redeem_text_` text NOT NULL,
	  `_gift_certificate_redeem_success_` text NOT NULL,
	  `_gift_certificate_redeem_fail_` text NOT NULL,
	  `_gift_certificate_redeem_button_` text NOT NULL

	) ENGINE=MyISAM  DEFAULT CHARSET=utf8||

	INSERT INTO ms_gift_certificate_language SET 
	_gift_certificate_title_='eGift Card',
	_gift_certificate_text_='Purchase an eGift Card for a friend or loved one that can be used to purchase products and services from us!',
	_gift_certificate_to_name_='Recipient Name',
	_gift_certificate_to_email_='Recipient Email Address',
	_gift_certificate_from_name_='From Name',
	_gift_certificate_from_email_='From Email',
	_gift_certificate_message_='Message (optional)',
	_gift_certificate_enter_amount_='Enter Amount',
	_gift_certificate_select_amount_='Select Amount',
	_gift_certificate_select_amount_error_='Please select an amount',
	_gift_certificate_add_another_='Add Another eGift Card',
	_gift_certificate_name_='eGift Card',
	_gift_certificate_from_='From',
	_gift_certificate_to_='To',
	_gift_certificate_bottom_text_='',
	_gift_certificate_redeem_='Redeem eGift Card',
	_gift_certificate_redeem_title_='Redeem eGift Card',
	_gift_certificate_redeem_text_='If you have received a eGift Card, enter your redeem code below.',
	_gift_certificate_redeem_success_='',
	_gift_certificate_redeem_fail_='That is not a valid redeem code',
	_gift_certificate_redeem_button_='Redeem'||


	ALTER TABLE `ms_gift_certificate_language` ADD `amounts` TEXT NOT NULL||
	ALTER TABLE `ms_gift_certificate_language` ADD `gift_card_style` TEXT NOT NULL||
	ALTER TABLE `ms_gift_certificate_language` ADD `other_amount` INT NOT NULL||

	UPDATE ms_gift_certificate_language SET amounts='10,20,25,50,75,100', other_amount='1', gift_card_style='<div id=\"giftcard\" style=\"width: 100%; max-width: 360px; max-height: 240px; height: auto;  background-color: rgb(61, 112, 130); border-radius: 12px; margin: auto; text-align: center; color: rgb(255, 255, 255);\">
		<div id=\"giftcardinner\" style=\"padding: 24px;\">
			<div id=\"giftcardsitename\" class=\"edittext\" style=\"padding: 4px;\" contenteditable=\"\">[WEBSITE_NAME]</div>
			<div id=\"giftcardtitle\" class=\"edittext\" style=\"padding: 4px;\" contenteditable=\"\">eGift Card</div>
			<div id=\"giftcardamount\" class=\"edittext\" style=\"padding: 4px; font-size: 40px; \">[AMOUNT]</div>
			<div id=\"giftcardredeem\" class=\"edittext\" style=\"padding: 4px;\" contenteditable=\"\">Redeem Code: </div>
			<div id=\"giftcardredeemcode\" class=\"edittext\" style=\"padding: 4px;\">[REDEEM_CODE]</div>
		</div>
	</div>'||

	ALTER TABLE `ms_store_language` ADD `_edit_` VARCHAR(200) NOT NULL ||
	UPDATE ms_store_language SET _edit_='Edit'||


	INSERT INTO `ms_emails` (`email_name`, `email_descr`, `email_codes`, `email_from_email`, `email_from_name`, `email_subject`, `email_message`, `email_type`, `email_no_delete`, `email_download_descr`, `email_shipping_descr`, `email_paypal_pending`, `email_offline_pending`, `email_id_name`) VALUES
	('eGift Card Received', 'This is the email sent to to the person receiving an eGift Card.', '[GIFT_CARD] = Where the eGfit card is displayed.\r\n[NAME] = Person receiving the eGift Card name\r\n[BUYER_NAME] = The buyer''s name\r\n[BUYER_EMAIL] = The buyer''s email address\r\n[WEBSITE_NAME] = The name of your website which is linked to your site\r\n[AMOUNT] = Amount received\r\n[REDEEM_CODE] = eGift Card redeem code\r\n[MESSAGE] = Buyer message\r\n', '', '', '[NAME], You''ve Received an eGift Card from [BUYER_NAME] at [WEBSITE_NAME]', '<p>Hello [NAME],\r\n</p><p style=\"text-align: center;\">You''ve received an&nbsp;eGift Card&nbsp;from [BUYER_NAME] at &nbsp;[WEBSITE_NAME]!\r\n</p><p style=\"text-align: center;\">[GIFT_CARD]</p><p><strong>[BUYER_NAME]</strong> ([BUYER_EMAIL]) has sent you an eGift Card in the amount of [AMOUNT].\r\n</p><p>Message from [BUYER_NAME]: <em>[MESSAGE]</em>\r\n</p><p>To use your&nbsp;eGift Card, when purchasing from our website [WEBSITE_NAME]&nbsp;and checking out, click the Redeem&nbsp;eGift Card option and enter in your redeem code: <strong>[REDEEM_CODE]</strong>\r\n</p><p>Thank you,<br>[WEBSITE_NAME]\r\n</p>', 0, 1, '', '', '', '', 'giftcertificate')||

	INSERT INTO `ms_emails` (`email_name`, `email_descr`, `email_codes`, `email_from_email`, `email_from_name`, `email_subject`, `email_message`, `email_type`, `email_no_delete`, `email_download_descr`, `email_shipping_descr`, `email_paypal_pending`, `email_offline_pending`, `email_id_name`) VALUES
	('Downloads are ready', 'This is the email you can send to notify customers their downloads are ready when you replace photos on an order.', '[FIRST_NAME] = Customer''s first name\r\n[LAST_NAME] = Customer''s last name\r\n[URL] = The URL to your website\r\n[WEBSITE_NAME] = The name of your website\r\n[ORDER_NUMBER] = Order number.\r\n[SCHEDULED_PAYMENTS] = Details of the scheduled payments\r\n[ORDER_LINK] = Starts link to order\r\n[/LINK] = Closes link to order', '', '', '[FIRST_NAME], Your Download Photo(s) Are Ready at  [WEBSITE_NAME]', '<p>Hello [FIRST_NAME],</p><p>Your download photo(s) are ready from your recent purchase at [WEBSITE_NAME] on order #[ORDER_NUMBER].</p><p><strong>[ORDER_LINK]Click here to view your order and download your photo(s)[/LINK].</strong></p><p>Thank you,<br>[WEBSITE_NAME]\r\n</p>', 0, 1, '', '', '', '', 'downloadsready')||



	CREATE TABLE IF NOT EXISTS `ms_gift_certificates` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `redeem_code` varchar(200) NOT NULL,
	  `to_name` varchar(255) NOT NULL,
	  `to_email` varchar(255) NOT NULL,
	  `from_name` varchar(255) NOT NULL,
	  `from_email` varchar(205) NOT NULL,
	  `message` text NOT NULL,
	  `date_purchased` date NOT NULL,
	  `amount` int(11) NOT NULL,
	  `purchased_order_id` int(11) NOT NULL,
	  `used_order` int(11) NOT NULL,
	  `admin_created` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ||




	ALTER TABLE `ms_gift_certificates` ADD `delivery_date` DATE NOT NULL ||
	ALTER TABLE `ms_cart` ADD `cart_delivery_date` DATE NOT NULL ||
	ALTER TABLE `ms_cart_archive` ADD `cart_delivery_date` DATE NOT NULL ||
	ALTER TABLE `ms_gift_certificates` ADD `emailed_date` DATE NOT NULL||
	ALTER TABLE `ms_gift_certificate_language` ADD `show_send_date` INT NOT NULL||

	ALTER TABLE `ms_gift_certificate_language` ADD `_gift_certificate_delivery_date_` TEXT NOT NULL||
	UPDATE ms_gift_certificate_language SET _gift_certificate_delivery_date_='Delivery Date'||

	ALTER TABLE `ms_gift_certificate_language` ADD `_today_` TEXT NOT NULL||
	UPDATE ms_gift_certificate_language SET _today_='Today'||

	INSERT INTO `ms_crons` (`cron_email`, `cron_what`, `cron_days`, `cron_time`, `cron_check_date`, `cron_status`, `cron_gal_access`, `cron_gal_preregister`, `cron_gal_viewed`, `cron_gal_purchased`, `cron_gal_collected_email`, `cron_gal_owner`, `cron_gal_no_order`) VALUES ( '0', 'giftcards', '1', '08:00:00', '2016-09-27', '1', '0', '0', '0', '0', '0', '0', '0')||

	ALTER TABLE `ms_cron_emails` ADD `from_gc_id` INT NOT NULL ||

	INSERT INTO ms_order_export_items SET field_name='Gallery Name', label='Gallery',function='getgalleryname', display_order='10'||

	ALTER TABLE ms_settings ADD `export_ignore_downloads` int(11) NOT NULL||
	UPDATE ms_settings SET export_ignore_downloads='0' ||

	UPDATE ms_order_export_items SET field_name='File Name' WHERE function='getfilename'||
	UPDATE ms_order_export_items SET field_name='File Link' WHERE function='getfilelink'||
	DELETE FROM ms_order_export_items WHERE id='8' ||

	ALTER TABLE `ms_order_export_items` ADD `strip_ext` INT NOT NULL||


	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "1.7.0") {
	$new_version = "1.7.1";
	$uq = "
	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "1.7.1") {
	$new_version = "1.7.2";
	$uq = "

	ALTER TABLE  `ms_calendar` ADD  `genders` VARCHAR( 20 ) NOT NULL ||

	ALTER TABLE  `ms_blog_photos` ADD INDEX (  `bp_blog` ) ||

	ALTER TABLE `ms_store_settings` ADD `pickup_tax_rate` DECIMAL(10,4) NOT NULL ||
	ALTER TABLE `ms_calendar` ADD `change_price_list` INT NOT NULL , ADD `change_price_list_date` DATE NOT NULL||
	ALTER TABLE `ms_blog_categories` ADD `change_price_list` INT NOT NULL , ADD `change_price_list_days` INT NOT NULL ||
	ALTER TABLE `ms_canvas_prints` ADD `cp_price_product` INT NOT NULL ||

	INSERT INTO `ms_payment_options` (`pay_name`, `pay_num`, `pay_key`, `pay_email`, `pay_descr`, `pay_text`, `pay_button`, `pay_status`, `pay_cards`, `pay_option`, `pay_page_title`, `pay_order`, `pay_title`, `pay_description`, `pay_ssl`, `pay_url`, `paypal_curl`, `pay_emulator`, `test_mode`, `pay_short_description`, `pay_full_page`, `pay_dev_status`, `pay_currency`, `pay_select_graphic`, `pay_form`, `pay_express_download_address`, `pay_offline_descr`, `pay_paypal_exp_button`, `pay_issue_date`, `pay_private_key`) VALUES
	( 'Square', '', '', '', '<a href=\"https://squareup.com/\" target=\"_blank\">SquareUp.com</a>\r\n\r\nTo use Square, you first must have a Square account. Once you have a Square account, you will need to get some credentials to enter in below.\r\n\r\nTo get your credentials, <a href=\"https://squareup.com/developers\" target=\"_blank\">click here to go to the developer portal and log into your Square account</a>.\r\n\r\nYou will see an option to create a new application.  Once it is created, you will have a Application ID and Personal Access Token.\r\n\r\nEnter that information below. If you wish to just test it out, use the Sandbox credentials (test card #: 4532 7597 3454 5858, test CVV: 111). Otherwise, use the regular credentials.\r\n', 'Pay Now', '', 0, '', 'square', '', 1, 'Pay With Credit Card', '', 1, 'https://squareup.com/', 0, '', 0, 'Accept payments using your Square account.', 0, 1, '', '', 'square', 0, '', '', 0, '')||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}


if($sytist_version <= "1.7.2") {
	$new_version = "1.8.0";
	$uq = "

	ALTER TABLE `ms_menu_links` ADD `link_mobile_order` INT NOT NULL , ADD `link_mobile_top` INT NOT NULL , ADD `link_mobile_hide` INT NOT NULL ||
	ALTER TABLE `ms_calendar` ADD `book_max_days` INT NOT NULL||
	ALTER TABLE `ms_calendar` ADD `book_per_time` INT NOT NULL ||

	ALTER TABLE `ms_calendar` ADD `change_shipping_group` INT NOT NULL , ADD `change_shipping_group_date` DATE NOT NULL||
	ALTER TABLE `ms_blog_categories` ADD `change_shipping_group` INT NOT NULL , ADD `change_shipping_group_days` INT NOT NULL ||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}
if($sytist_version <= "1.8.0") {
	$new_version = "1.8.1";
	$uq = "

	INSERT INTO ms_order_export_items SET field_name='Green Screen Background',label='Background',function='gsbackground',display_order='10'||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;
}
if($sytist_version <= "1.8.1") {
	$new_version = "1.8.2";
	$uq = "

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	$sytist_version = $new_version;

}
if($sytist_version <= "1.8.2") {
	$new_version = "1.9.0";
	$uq = "

	ALTER TABLE `ms_photo_products` ADD `pp_no_crop` INT NOT NULL ||

	ALTER TABLE `ms_language` ADD `_reset_crop_` TEXT NOT NULL||
	UPDATE ms_language SET _reset_crop_='Reset' ||

	UPDATE ms_states SET state_name='South Yorkshire' WHERE state_name='South yorkshire'||

	ALTER TABLE `ms_packages` ADD `package_preview_text` TEXT NOT NULL||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	// Adding poppins font
	updateSQL("ms_settings","google_fonts='Poppins:300,600\r\n".$site_setup['google_fonts']."' ");


	$sytist_version = $new_version;

}


if($sytist_version <= "1.9.0") {
	$new_version = "2.0.0";
	$uq = "

	INSERT INTO ms_order_export_items SET field_name='Sub Gallery Name', label='Sub Gallery',function='getsubgalleryname', display_order='12'||
	INSERT INTO ms_order_export_items SET field_name='Product Notes', label='Product Notes',function='getproductnotes', display_order='13'||
	ALTER TABLE `ms_calendar` ADD `book_confirm_no_deposit` INT NOT NULL||
	ALTER TABLE `ms_calendar` ADD `_booking_auto_confirm_text_` TEXT NOT NULL, ADD `_booking_confirm_button_` TEXT NOT NULL||
	UPDATE ms_calendar SET _booking_auto_confirm_text_='Your appointment is set and you have been sent an email with details.', _booking_confirm_button_='Confirm Booking'||
	ALTER TABLE `ms_calendar` ADD `disabled_payment_options` TEXT NOT NULL||
	ALTER TABLE `ms_cart` ADD `cart_email` VARCHAR(255) NOT NULL||
	ALTER TABLE `ms_cart_archive` ADD `cart_email` VARCHAR(255) NOT NULL||
	ALTER TABLE `ms_my_pages` ADD INDEX(`mp_date_id`)||
	ALTER TABLE `ms_view_page` ADD INDEX(`v_page`)||
	ALTER TABLE `ms_menu_links` ADD `link_open_drop_mobile` INT NOT NULL||
	ALTER TABLE `ms_store_language` ADD `_log_in_text_` TEXT NOT NULL AFTER `_log_in_`||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}

	// Adding poppins font
	$links = whileSQL("ms_menu_links","*","WHERE link_status='1' AND (link_location='topmain' OR link_location='side')  ORDER BY link_order ASC ");
	while($link = mysqli_fetch_array($links)) { 
		$or++;
		updateSQL("ms_menu_links", "link_mobile_order='".$or."' WHERE link_id='".$link['link_id']."' ");
	}

	$links = whileSQL("ms_menu_links","*","WHERE link_status='1' AND link_location='shop' ORDER BY link_order ASC ");
	while($link = mysqli_fetch_array($links)) { 
		$or++;
		updateSQL("ms_menu_links", "link_mobile_order='".$or."' WHERE link_id='".$link['link_id']."' ");
	}



	$sytist_version = $new_version;
}
if($sytist_version <= "2.0.0") {
	$new_version = "2.0.1";
	$uq = "

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}


	$sytist_version = $new_version;
}

if($sytist_version <= "2.0.1") {
	$new_version = "2.1.0";
	$uq = "
	ALTER TABLE `ms_billboards` ADD `abs_header` INT NOT NULL , ADD `header_opacity` DECIMAL(10,2) NOT NULL , ADD `hide_shop_menu` INT NOT NULL, ADD `header_bg` VARCHAR(20) NOT NULL, ADD `menu_bg` VARCHAR(20) NOT NULL , ADD `menu_opacity` DECIMAL(10,2) NOT NULL ||
	ALTER TABLE `ms_billboards` ADD `bill_content_row1` TEXT NOT NULL , ADD `bill_content_row2` TEXT NOT NULL ||
	ALTER TABLE `ms_billboards` ADD `bill_burns` INT NOT NULL||
	ALTER TABLE `ms_calendar` ADD `date_paid_access_unlock` INT NOT NULL||
	ALTER TABLE `ms_billboards` ADD `bill_parallax` INT NOT NULL||
	ALTER TABLE `ms_billboards` CHANGE `bill_nav_border` `bill_nav_border` VARCHAR(10) NOT NULL DEFAULT 'FFFFFF'||
	ALTER TABLE `ms_billboards` ADD `bill_nav_align` VARCHAR(20) NOT NULL||
	UPDATE ms_billboards SET bill_nav_border='FFFFFF', bill_nav_background='FFFFFF' || 
	ALTER TABLE `ms_contracts_language` ADD `_required_fields_empty_` TEXT NOT NULL||
	UPDATE ms_contracts_language SET _required_fields_empty_='You have required fields that are empty or not selected. See highlighted fields.'||
	ALTER TABLE `ms_bookings` ADD `book_google` INT NOT NULL||
	ALTER TABLE `ms_contracts` ADD `content_signed` TEXT NOT NULL||

	UPDATE ms_settings  SET sytist_version='$new_version' 
	";

		$quy = explode("||", $uq);

		foreach($quy AS $sql2) {
			$xx++;
			if(($sql2 !== '' ) AND (!empty($sql2))==true) {
			$sql = stripslashes(addslashes($sql2));
			 if(@mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			}
		}


	$sytist_version = $new_version;
	include "themes.php";
	updateSQL("ms_history", "upgrade_check='', upgrade_message='' ");
	sytistreg(true,$new_version);
	$_SESSION['sm']  = "Sytist upgraded to version $new_version";
	session_write_close();
	header("location: index.php");
	exit();

}

?>