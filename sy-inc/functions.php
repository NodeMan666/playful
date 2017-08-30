<?php
include "functions.core.php";
if($setup['pls'] == true) { 
	include $setup['path']."/sy-inc/pls/pls.functions.php";
}
function includemailform() { 
	global $setup;
	include($setup['path'].'/sy-inc/email_form.php');
}
function includegiftcertificateform() { 
	global $setup,$glang,$site_setup,$person,$store;
	include($setup['path'].'/sy-inc/store/store_gift_certificate_include.php');
}

function tempFolder() { 
	global $setup;
	if(!empty($setup['temp_url_folder'])) { 
		print $setup['temp_url_folder'];
	}
}

function is_referer() {
	global $setup;
	if (!isset($_SERVER['HTTP_REFERER'])) return false;
    $url = parse_url($_SERVER['HTTP_REFERER']);
    if ($url['host'] == $_SERVER['HTTP_HOST']) return true;
    else return false;
}

function showSocialLinks() {
	$links = whileSQL("ms_social_links", "*", "WHERE link_status='1' ORDER BY link_order ASC");
	while($link = mysqli_fetch_array($links)) {
		$html .= "<a href=\"".$link['link_url']."\" target=\"_blank\" title=\"".$link['link_text']."\" class=\"the-icons icon-".$link['link_name']."\"></a>\r\n";
	}
	return $html;
}

function cleanPageLink($pl) {
	global $site_setup;
	$page_link = stripslashes(trim(strtolower($pl)));
	$page_link = strip_tags($page_link);
	$page_link = str_replace(" ","".$site_setup['sep_page_names']."",$page_link);
	$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);
	return $page_link;
}

function checkhttps() { 
	if(empty($_SERVER['HTTPS'])) {
		return false;		
	} else { 
		return true;
	}
}

function gotosecure() { 
	global $sytist_store,$store,$setup;
	if($sytist_store == true) { 
		if($store['checkout_ssl'] == "1") { 
			if(!empty($store['checkout_ssl_link'])) { 
				return $store['checkout_ssl_link'];
			} else { 
				return "https://".$_SERVER['HTTP_HOST'];
			}
		}
	}
}

function removesecure() { 
	if(checkhttps() == true) { 
		return "http://".$_SERVER['HTTP_HOST'];
	}
}

function customerLoggedIn() {
	 if(( $_SESSION['loggedin'] == true) AND  ($_SESSION['pid'] !== NULL ) == true) { 
		 return true;
	 }
}
function makesalt() { 
   $characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
    $salt = '';
    for ($i = 0; $i < 5; $i++) { 
        $salt .= $characters[mt_rand(0, 39)];
	}
	return $salt;
}

function sqlslash($t) {
	return addslashes(stripslashes($t));
}
function facebookLogin() { 
	global $setup,$site_setup,$fb;
	if($fb['facebook_login'] == "1") { 
		include_once($setup['path']."/sy-inc/facebook/config.php");
		$fbuser = null;
		if(!$fbuser){
			$fbuser = null;
			$loginUrl = $facebook->getLoginUrl(array('redirect_uri'=>$homeurl,'scope'=>$fbPermissions));
		
			print '<div class="pc" id="facebooklogin"><a class="" href="'.$loginUrl.'"><span class="the-icons icon-facebook"></span>'._log_in_with_facebook_.'</a></div>';
			print '<div class="pc center">'._or_.'</div>';
		}
	}
}

function getimagefile($pic,$pic_file) { 
	global $setup,$full_url,$site_setup;
	if($pic['pic_amazon'] == "1") { 
		if($pic['full_url'] == true) { 
			return "http://".$site_setup['amazon_endpoint']."/".$pic['pic_bucket']."/".$pic['pic_bucket_folder']."/".urlencode($pic[$pic_file]);
		} else { 
			return "//".$site_setup['amazon_endpoint']."/".$pic['pic_bucket']."/".$pic['pic_bucket_folder']."/".urlencode($pic[$pic_file]);
		}
	} else { 
		if($pic['full_url'] == true) { 
			return $setup['url'].$setup['temp_url_folder']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file];
		} else { 
			return $setup['temp_url_folder']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file];
		}
	}
}

function getimagefiledems($pic,$pic_file) { 
	global $setup;
	if($pic_file == "pic_th") { 
		if($pic['pic_th_width'] > 0) { 
			$size[0] = $pic['pic_th_width'];
			$size[1] = $pic['pic_th_height'];
		} else { 
			$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file]); 
		}
	}
	if($pic_file == "pic_pic") { 
		if($pic['pic_small_width'] > 0) { 
			$size[0] = $pic['pic_small_width'];
			$size[1] = $pic['pic_small_height'];
		} else { 
			$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file]); 
		}
	}
	if($pic_file == "pic_large") { 
		if($pic['pic_large_width'] > 0) { 
			$size[0] = $pic['pic_large_width'];
			$size[1] = $pic['pic_large_height'];
		} else { 
			$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file]); 
		}
	}
	if($pic_file == "pic_full") { 
		if($pic['pic_width'] > 0) { 
			$size[0] = $pic['pic_width'];
			$size[1] = $pic['pic_height'];
		} else { 
			$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file]); 
		}
	}
	return $size;
}

function msIncluded() {
	return true;
}

function selectPhotoFile($pic_file,$pic) { 
	global $ipad,$mobile,$billboard;
	if($billboard['bill_id'] > 0) { 
		$mobile = 0;
	}
	$pic_file_select = $pic_file;
	if(($mobile =="1")&&($pic_file!=="pic_pic")==true) { 
		$pic_file_select = "pic_pic";
	}

	if(($pic_file_select == "pic_large") AND (empty($pic['pic_large']))==true) { 
		$pic_file_select = "pic_full";
		if(empty($pic['pic_full'])) { 
			$pic_file_select = "pic_med";
		}
	}
	if(($pic_file_select == "pic_full") && (empty($pic['pic_full']))==true) { 
		$pic_file_select = "pic_large";
		if(empty($pic['pic_large'])) { 
			$pic_file_select = "pic_med";
		}
	}
	if(($pic_file_select == "pic_med") && (empty($pic['pic_med']))==true) { 
		$pic_file_select = "pic_large";
		if(empty($pic['pic_large'])) { 
			$pic_file_select = "pic_full";
			if(empty($pic['pic_full'])) { 
				$pic_file_select = "pic_pic";
			}
		}
	}


	if(empty($pic_file_select)) { 
		$pic_file_select = "pic_pic";
	}

	return $pic_file_select;
}



function currentdatetime() { 
	return date('Y-m-d H:i:s');
}

function sendWebdEmail($to_email, $to_name, $from_email, $from_name, $subject, $message,$type) {
	global $site_setup,$_POST,$setup,$do_not_log_email,$log_cron,$debugmail;
	date_default_timezone_set(''.$site_setup['time_zone'].'');
	// header('Content-Type: text/html; charset=utf-8');

	$reply_to = $from_email;
	if(!empty($setup['send_from_email'])) { 
		$reply_to = $from_email;
		$from_email = $setup['send_from_email'];
	}
	if(empty($setup['mail_sending_line_breaks'])) { 
		$setup['mail_sending_line_breaks'] = "\r\n";
	}
	ini_set('sendmail_from', ''.$from_email.'');
	$message = $site_setup['email_header']." ".$message." ".$site_setup['email_footer'];

	if($site_setup['mail_type'] == "1") {
		if(empty($site_setup['smtp_phpmailer_url'])) { 
			include_once($setup['path']."/sy-inc/PHPmailer/class.phpmailer.php");
			include_once($setup['path']."/sy-inc/PHPmailer/class.smtp.php");
		} else { 
			$smtp_phpmailer_url = "".$site_setup['smtp_phpmailer_url']."";
		}
		$smtp_host = "".$site_setup['smtp_host']."";
		$smtp_username = "".$site_setup['smtp_username']."";
		$smtp_password = "".$site_setup['smtp_password']."";
		$smtp_from = "$from_email";
		$smtp_from_name = "$from_name";
		$smtp_to = "$to_email";
		$message = stripslashes($message);
		$subject = stripslashes($subject);

		if(!empty($smtp_phpmailer_url)) { 
			include_once("$smtp_phpmailer_url");
		}


		$mail = new PHPMailer();
		$mail->CharSet = 'utf-8';
		if($debugmail == true) { 
			$mail->SMTPDebug  = 2; 
		} else { 
			$mail->SMTPDebug  = false; 
		}
		$mail->IsSMTP();
		$mail->Host = "$smtp_host";
		$mail->SMTPAuth = TRUE;
		$mail->Username = "$smtp_username";
		$mail->Password = "$smtp_password";

	/*	if ($smtp_host == 'smtp.gmail.com') {
			$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
			$mail->Port = 587;
			$mail->Priority = 1;
			$mail->AddReplyTo($reply_to,$from_name);
		}
	*/
		if(!empty($site_setup['smtp_secure'])) { 
			$mail->SMTPSecure = $site_setup['smtp_secure']; // secure transfer enabled REQUIRED for GMail
		}
		if($site_setup['smtp_port'] > 0) { 
			$mail->Port = $site_setup['smtp_port'];
		}
		$mail->Priority = 1;
		$mail->AddReplyTo($reply_to,$from_name);


		$mail->From = "$smtp_from";
		$mail->FromName = "$smtp_from_name";
		$mail->AddAddress("$smtp_to"); 
		$mail->IsHTML(true);
		$new_subject = '=?utf-8?B?'.base64_encode($subject).'?=';
		$mail->Subject = "$new_subject";
		$mail->Body = "$message";
		$mail->Send();
	} else {
		if($site_setup['all_email_from'] == "1") {
			// $from_name = $site_setup['website_name'];
			// $from_email = $site_setup['contact_email'];
		}
		// STANDARD MAIL
		$to = "$to_email";
		$subject = "$subject";
		$headers .= 'Reply-To: '.$from_name.' <'.$reply_to.'>' . "".$setup['mail_sending_line_breaks']."" .
		'X-Sender: '.$from_name.' <'.$from_email.'>' . "".$setup['mail_sending_line_breaks']."" . 
		'Return-path: '.$from_email.'' . "".$setup['mail_sending_line_breaks']."" .
		'X-Return-path: '.$from_email.'' . "".$setup['mail_sending_line_breaks']."" .
		'X-Path: '.$from_email.'' . "".$setup['mail_sending_line_breaks']."";

		$from = "$from_email";
		$headers .= "From: $from_name <$from_email>".$setup['mail_sending_line_breaks']."";
		// $headers .= "Return-Path: <$from_email>" . "".$setup['mail_sending_line_breaks']."";

		if($type == "1") {
			$semi_rand = md5(time());
			$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
			$headers .= "MIME-Version: 1.0".$setup['mail_sending_line_breaks']."" .
				//	 "Content-Type: text/html;".$setup['mail_sending_line_breaks']."" .
					 " boundary=\"{$mime_boundary}\"".$setup['mail_sending_line_breaks']."";


		//	$headers .= "--{$mime_boundary}".$setup['mail_sending_line_breaks']."";
			$headers .= "Content-Type: text/html; charset=\"utf-8\"".$setup['mail_sending_line_breaks']."";
			$headers .= "Content-Transfer-Encoding: 7bit".$setup['mail_sending_line_breaks']."";
			// $headers .= "".$tms['cant_view_text'].": ".$tms['tmail_url']."/index.php?mid=".md5($mailout['mail_id'])."\n\n";

			// $headers .= "--$mime_boundary".$setup['mail_sending_line_breaks']."";
			// $headers.= "Content-Type: text/html; charset=\"utf-8\"".$setup['mail_sending_line_breaks']."";
			// $headers.= "Content-Transfer-Encoding: 7bit".$setup['mail_sending_line_breaks']."";

		//	$message = "".$layout['header'].""."$message"."".$layout['opt_out'].""."".$layout['footer']."";
		} else {


			$message = strip_tags("$message");
			$message = stripslashes($message);
			$message = strip_tags("$message");
		}
		$oldphpself = $_SERVER['PHP_SELF']; 
		$oldremoteaddr = getUserIP();
		$_SERVER['PHP_SELF'] = "/"; 
		$_SERVER['REMOTE_ADDR'] = $_SERVER['SERVER_ADDR'];

		$message = stripslashes($message);
		$subject = stripslashes($subject);
		if($setup['demo_mode'] !== true) { 
			if($site_setup['mail_return_path'] == "1") {
				mail($to,'=?utf-8?B?'.base64_encode($subject).'?=',$message,$headers,'-f'.$from_email);
			} else {
				mail($to,'=?utf-8?B?'.base64_encode($subject).'?=',$message,$headers);
			}
		}
		$_SERVER['PHP_SELF'] = $oldphpself; 
		$_SERVER['REMOTE_ADDR'] = $oldremoteaddr;
	
	}

	if(($_REQUEST['do'] != "setup")AND($_REQUEST['action']!="payment")AND($do_not_log_email!==true)==true) {
		$from_email = sql_safe("$from_email");
		$to_email = sql_safe("$to_email");
		$subject = sql_safe("$subject");

		insertSQL("ms_email_logs", "log_from='".addslashes(stripslashes($reply_to))."', log_to='".addslashes(stripslashes($to_email))."', log_subject='".addslashes(stripslashes($subject))."', log_text='".addslashes(stripslashes($message))."', log_date='".currentdatetime()."', log_cron='".$log_cron."' ");
	}
}





function nextprevHTMLMenu($total_results, $pg, $per_page,  $NPvars) {
	global $show_text;
	$html .= "<table cellpadding=2 cellspacing=0 border=0 style=\"padding-top: 4px; padding-bottom: 4px;\"><tr>";
	if($total_results > $per_page) {

		if(empty($pg)) {	$pg = "1";		}
		$vw1 = ($pg * $per_page) - $per_page + 1; 
		$vw2 = $vw1 + ($per_page - 1);
		if($pg * $per_page > $total_results) {
			$vw2 = (($pg - 1) * $per_page) + ($total_results - (($pg - 1) * $per_page));
		}
		foreach($NPvars AS $vari) {
			$qstring .= "&$vari";
		}

		if($pg > 1) {
			$prev = $pg - 1;
			$html .= "<td><a href=\"index.php?pg=$prev" . "$qstring\" class=np>&nbsp;&laquo;&nbsp;</a></td>";
		} else {
			$html .= "<td><span class=np>&nbsp;&laquo;&nbsp;</span></td>";
		}
		$pages = $total_results / $per_page + 1;
		if($pg <= 10) {
			$np = 1;
		} else {
			$np = $pg - 2;
		}
		$pct = 1;
		while($np  < $total_results / $per_page + 1 AND $pct <= 10) {
			if($np == $pg) {
				$html .=  "<td><span class=np>&nbsp;$np&nbsp;</span></td>" ;
			} else {
				$html .=  "<td><a href=\"index.php?pg=$np" . "$qstring\" class=np>&nbsp;$np&nbsp;</a></td>" ;
			}
			$np++;
			$pct++;
		}
		if($pg < $total_results / $per_page ) {
			$next = $pg + 1;
			$html .= "<td><a href=\"index.php?pg=$next" . "$qstring\" class=np>&nbsp;&raquo;&nbsp;</a></td>";
		} else {
			$html .= "<td><span class=np>&nbsp;&raquo;&nbsp;</span></td>";
		}
	}
	$html .= "</tr></table>";
	if($show_text !== false) { 
		$html .= "<div class=\"pc center\"><nobr>$total_results Results </nobr></div>";
		$html .= "<div class=\"pc center\">Viewing $vw1 - $vw2</div>";
	}
	return $html;
}

function getPageThumbnail($date,$size) { 
	global $setup;
	if($size == "thumb") { 
		$this_size = "date_thumb";
		$this_pic = "pic_th";
	}
	if($size == "mini") { 
		$this_size = "date_mini";
		$this_pic = "pic_mini";
	}
	if($size == "small") { 
		$this_size = "date_thumb";
		$this_pic = "pic_pic";
	}

	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'   AND bp_sub_preview<='0'  ORDER BY bp_order ASC LIMIT  1 ");
	if(!empty($pic['pic_id'])) {
		return $setup['url'].$setup['temp_url_folder']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$this_pic];
	} else { 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
		if(!empty($pic['pic_id'])) {
			return $setup['url'].$setup['temp_url_folder']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$this_pic];
		}
	}
}


function stripreplaceand($val) {
	$val = str_replace("[AND]", "&", $val);
	$val = stripslashes($val);
	return $val;
}


function numFBComments($url) {
   $request_url ="https://graph.facebook.com/?ids=" .
        $url;

    $requests = @file_get_contents($request_url);
	return getBetween($requests, '"comments":', '}');
}

function getBetween($content,$start,$end){
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}

function favLinks() { 
if(countIt("ms_menu_links", "WHERE link_status='1' AND link_location='favs' ") > 0) {
	$html .= "<div id=\"linksMenuContainer\">";
	$html .= "<div id=\"linksMenu\">";
	$html .= "<div class=\"menuHeader\"><div class=\"title\">"._favorite_links_."</div></div>";

	$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' AND link_location='favs' ORDER BY link_order ASC ");
		while($link = mysqli_fetch_array($links)) {  
			if(!empty($link['link_main'])) { 
			$html .= "<div class=\"sideMenuItem\"><a href=\"/".$setup[$link['link_main']]."/\"  target=\"".$link['link_open']."\">".$link['link_text']."</a></div>"; 
			if(($link['link_main'] == "photos_folder")AND($photos_page == true)==true) {
				$photo_setup = doSQL("ms_photo_setup", "*", "  ");

				$sgals = whileSQL("ms_galleries", "*, date_format(DATE_ADD(gal_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS gal_date_show", "WHERE gal_id>'0' AND gal_status='1' AND gal_private<='1'   ORDER BY ".$photo_setup['album_order_by']." ".$photo_setup['album_acdc']."" );
				while($sgal = mysqli_fetch_array($sgals)) {
					$html .= "<div class=\"sideMenuItem\"><a href=\"index.php?do=photos&viewGallery=".$sgal['gal_id']."\"><nobr>".$sgal['gal_title']."</nobr></a></div>"; 
				}
			}
		} elseif($link['link_page'] > 0) {
			$lpage = doSQL("ms_pages", "*", "WHERE page_id='".$link['link_page']."' ");
			if($lpage['page_home'] == "1") {
				$html .= "<div class=\"sideMenuItem\"><a href=\"/\"  target=\"".$link['link_open']."\">".$link['link_text']."</a></div>"; 
			} else {
				$html .= "<div class=\"sideMenuItem\"><a href=\"/".$setup['pages_folder']."/".$lpage['page_link']."/\"  target=\"".$link['link_open']."\">".$link['link_text']."</a></div>"; 

				if(countIt("ms_pages", "WHERE page_under='".$lpage['page_id']."' ")>0) {
					$side_menu_pages[$link['link_id']] = array();
					array_push($side_menu_pages[$link['link_id']],$lpage['page_id']);

					$spages = whileSQL("ms_pages", "page_under,page_status,page_id", "WHERE page_under='".$lpage['page_id']."' AND page_status='1'   ORDER BY page_order ASC " );
					while($spage = mysqli_fetch_array($spages)) {
						array_push($side_menu_pages[$link['link_id']],$spage['page_id']);
					}
					if(in_array($page_id,$side_menu_pages[$link['link_id']])) {
						$spages = whileSQL("ms_pages", "page_under,page_status,page_id,page_title,page_link,page_external_link", "WHERE page_under='".$lpage['page_id']."' AND page_status='1'   ORDER BY page_order ASC " );
						while($spage = mysqli_fetch_array($spages)) {
							if(!empty($spage['page_external_link'])) {
								$html .= "<div class=\"sideMenuItem\"><a href=\"".$spage['page_external_link']."\">".$spage['page_title']."</a></div>"; 
							} else {
								$html .= "<div class=\"sideMenuItem\"><a href=\"/".$setup['pages_folder']."/".$spage['page_link']."/\">".$spage['page_title']."</a></div>"; 
							}
						}
					}
				}
			}
		} elseif((empty($link['link_url']))AND(empty($link['link_page']))==true) {  
			$html .= "<div class=\"label\" >".$link['link_text']."</div>"; 
		} else { 
			$html .= "<div class=\"sideMenuItem\"><a href=\"".$link['link_url']."\" target=\"".$link['link_open']."\">".$link['link_text']."</a></div>"; 
		}  
	}

	$html .="</div><div class=\"cssClear\"></div></div><div>&nbsp;</div>";
}
	return $html;
} 

function faceBookLikeBox() {
	global $hp,$css,$on_homepage,$setup;
	$fb = doSQL("ms_fb", "*", "");
	if(empty($fb['fb_show_faces'])) { $fb['fb_show_faces'] = "false"; } 
	if(empty($fb['fb_stream'])) { $fb['fb_stream'] = "false"; } 
	if(empty($fb['fb_header'])) { $fb['fb_header'] = "false"; } 
	$html .= "<div style=\"margin: auto;width: 292px; text-align: center;\" id=\"facebookLikeBox\"><center><script src=\"https://connect.facebook.net/". $fb['fb_lang']."/all.js#xfbml=1\"></script><fb:like-box href=\"".$fb['facebook_link']."\" width=\"292\" colorscheme=\"".$css['fb_color']."\" show_faces=\"".$fb['fb_show_faces']."\" stream=\"".$fb['fb_stream']."\" header=\"".$fb['fb_header']."\"></fb:like-box></center></div>";
	$html .= "<div>&nbsp;</div>";
	return $html;
}



function newsHeadlines() {
	global $site_setup,$hp,$setup,$com_settings,$mobile;
	$hp = doSQL("ms_home_page", "*", "");

	$cset = doSQL("ms_calendar_settings", "*", "  ");
	$hpf = doSQL("ms_home_page_items", "*", "WHERE widget='NEWS' ");
	if(!empty($hpf['feat_label'])) { 
		$html .= "<div id=\"homePageLabels\">".$hpf['feat_label']."</div>";
	}

	 if(($cset['preview_type'] == "stacked")AND($mobile!==true)==true) { $bid =  "stackedPreviews"; } else { $bid = "previews"; }
	 $html = $html."<div id=\"$bid\">";
	$time = strtotime("".$site_setup['time_diff']." hours");
	$cur_time =date('Y-m-d H:i:s', $time);


	$total_posts = countIt("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "WHERE date_id>'0'  AND ((cat_no_show_posts!='1' OR date_cat='0') OR (cat_id='".$bcat['cat_id']."'))  AND date_public='1' AND private<='1'  AND date_type='news' ");
	$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show", "WHERE date_id>'0'  AND ((cat_no_show_posts!='1' OR date_cat='0') OR (cat_id='".$bcat['cat_id']."'))  AND date_public='1' AND private<='1'  AND date_type='news'  AND CONCAT(date_date, ' ', date_time)<='$cur_time'   ORDER BY date_date DESC,date_time DESC LIMIT ".$hp['news_limit']." ");
	if(mysqli_num_rows($dates)<=0) {
		$html .= "<div class=pageContent style=\"text-align: center;\">No news added</div>";
	}
		while($date = mysqli_fetch_array($dates)) {

	if($hp['news_show_type'] == "1") { 
		$html .= showBlogPreview($date);
	} else { 



			$html .= "<div id=\"blogPost\">";
			$html .= "<div class=\"blogTop\">";
			?>
			<?php if(!empty($date['date_mini'])) { 
				if(file_exists($setup['path']."/".$setup['misc_folder']."/blog_thumbnails/".$date['date_mini'])) {
					$size = @GetImageSize("".$setup['path']."/".$setup['misc_folder']."/blog_thumbnails/".$date['date_mini']); 
					$html .= "<a href=\"/".$setup['news_folder']."/".$date['date_link']."/\"><img src=\"".$setup['misc_folder']."/blog_thumbnails/".$date['date_mini']."\" class=\"thumbnail\" ".$size[3]."></a>";
					} 
				} 
			$html .= "<div><a href=\"/".$setup['news_folder']."/".$date['date_link']."/\" class=\"title\">".$date['date_title']."</a></div>";
			$html .= "<div>";
			$html .="<div class=\"newsDate\"><nobr>".$date['date_show_date']."</nobr></div></div>";
			if($date['date_cat'] > 0) { 
				$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
				$html .= "<div class=\"category\">"._in_." <a href=\"/".$setup['news_folder']."/".$cat['cat_folder']."/\">".$cat['cat_name']."</a>";

				if($date['date_cat2'] > 0) { 
					$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat2']."' ");
					$html .= ", <a href=\"/".$setup['news_folder']."/".$cat['cat_folder']."/\">".$cat['cat_name']."</a>";
				}
				if($date['date_cat3'] > 0) { 
					$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat3']."' ");
					$html .= ", <a href=\"/".$setup['news_folder']."/".$cat['cat_folder']."/\">".$cat['cat_name']."</a>";
				}
				if($date['date_cat4'] > 0) { 
					$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat4']."' ");
					$html .= ", <a href=\"/".$setup['news_folder']."/".$cat['cat_folder']."/\">".$cat['cat_name']."</a>";
				}
				
				
				$html .="</div>";
			} 

			$html .= "<div class=\"cssClear\"></div>";
			$html .="<div id=\"newsHome\">";
			$html .= "<div class=\"pageContent\">".$date['date_text']."</div>";
			$html .= "</div>";

			$gallery = doSQL("ms_galleries", "*", "WHERE gal_blog='".$date['date_id']."' ");
			if(!empty($gallery['gal_id'])) { 
				$photo_setup = doSQL("ms_photo_setup", "*", "  ");

				$pics_where = "WHERE pic_gal='".$gallery['gal_id']."' AND pic_no_dis='0' ";

				$pics = whileSQL("ms_photos LEFT JOIN ms_galleries ON ms_photos.pic_gal=ms_galleries.gal_id", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_q ORDER BY ".$photo_setup['order_by']." ".$photo_setup['acdc']." $limit ");
				while ($pic = mysqli_fetch_array($pics)){
					$dsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['gal_folder']."/".$pic['pic_pic'].""); 
					$html .="<div class=\"blogPhoto\">";
					$html .= "<img src=\"/".$setup['photos_upload_folder']."/".$pic['gal_folder']."/".$pic['pic_pic']."\" ".$dsize[3].">";
					if(!empty($pic['pic_text'])) { 
						$html .= "<div class=\"pageContent\">".nl2br($pic['pic_text'])."</div>";
					}
					$html .= "</div>";
				}
			}

			$html .= "</div>";


			$html .= "<div id=\"newsSeparator\">&nbsp;</div>";

		}
	}
	$html = $html."</div>";

	if($total_posts > mysqli_num_rows($dates)) { 
		$html .= "<div class=pageContent><div id=\"homePageMore\"><a href=\"".$setup['news_folder']."/\">"._home_page_more_news_."</a></div></div>";
	}
	return $html;
}

function justNewsHeadlines() {
	global $site_setup,$hp,$setup,$com_settings;
	$hpf = doSQL("ms_home_page_items", "*", "WHERE widget='JUST_HEADLINES' ");
	if(!empty($hpf['feat_label'])) { 
		$html .= "<div class=pageContent><div id=\"homePageLabels\">".$hpf['feat_label']."</div></div>";
	}


	$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show", "WHERE date_id>'0' AND ((cat_no_show_posts!='1' OR date_cat='0') OR (cat_id='".$bcat['cat_id']."'))  AND date_public='1' AND  private<='1'  AND date_type='news' ORDER BY date_date DESC,date_time DESC LIMIT ".$hp['headlines_limit']."");
	if(mysqli_num_rows($dates)<=0) {
		$html .= "<div class=pageContent style=\"text-align: center;\">No news added</div>";
	}
		while($date = mysqli_fetch_array($dates)) {
			$html .= "<div class=pageContent>";
			$html .= "<div><a href=\"/".$setup['news_folder']."/".$date['date_link']."/\">".$date['date_title']."</a></div>";
			$html .= "</div>";
		}
//	$html .= "<div class=pageContent><div id=\"homePageMore\"><a href=\"".$setup['news_folder']."/\">"._home_page_more_news_."</a></div></div>";
	$html .= "<div>&nbsp;</div>";

	return $html;
}


function photoCartLogin($item) {
	global $hp;
	$hpf = doSQL("ms_home_page_items", "*", "WHERE widget='PHOTO_CART_LOGIN' ");
	if(!empty($hpf['feat_label'])) { 
		$html .= "<div class=pageContent><div id=\"homePageLabels\">".$hpf['feat_label']."</div></div>";
	}
	if(!empty($item['item_text'])) {
		$html .= "<div class=pageContent>".$item['item_text']."</div>";
	}

	$html .="<div id=\"photoCartLogin\">";
	$html .= "<form name=\"login\" method=\"POST\" action=\"".$hp['photo_cart_url']."\" style=\"padding: 0; margin: 0;\">";
	$html .= "<div class=pageContent><div>"._login_email_."</div><div><input type=text name=\"email\" size=25 class=\"textfield\" style=\"width: 97%;\"></div></div>";
	$html .="<div class=pageContent><div>"._login_password_."</div><div><input type=password name=\"password\" size=25 class=\"textfield\"  style=\"width: 97%;\"></div></div>";
	$html .="<div class=pageContent style=\"text-align: center;\">";
	$html .= "<input type=\"hidden\" name=\"do\" value=\"login\">";
	$html .= "<input type=\"hidden\" name=\"submitit\" value=\"submit\">";
	$html .= "<button type=\"submit\" name=\"submit\">"._login_button_."</button>";
	$html .= "</div></form></div>";
	$html .= "<div>&nbsp;</div>";
	return $html;
}

function featuredPages() { 
	global $setup, $hp, $site_setup;
	$hpf = doSQL("ms_home_page_items", "*", "WHERE widget='FEATURED_PAGES' ");
	if(!empty($hpf['feat_label'])) { 
		$html .= "<div class=pageContent><div id=\"homePageLabels\">".$hpf['feat_label']."</div></div>";
	}

	$fps = whileSQL("ms_featured_pages", "*", "ORDER BY feat_order ASC ");
	while($fp = mysqli_fetch_array($fps)) { 
		$subpage = doSQL("ms_calendar", "*", "WHERE date_id='".$fp['feat_item_id']."' ");
		if(!empty($subpage['date_id'])) { 
			$html .= "<div class=\"subPage\">";
			if(!empty($subpage['date_thumb'])) { 
				if(file_exists($setup['path']."/".$setup['pages_folder']."/".$subpage['date_link']."/".$subpage['date_thumb'])) {
					$size = @GetImageSize("".$setup['path']."/".$setup['pages_folder']."/".$subpage['date_link']."/".$subpage['date_thumb']); 
				$html .= "<a href=\"/".$setup['pages_folder']."/".$subpage['date_link']."/\"><img src=\"/".$setup['pages_folder']."/".$subpage['date_link']."/".$subpage['date_thumb']."\" class=\"thumbnail\" ".$size[3]."></a>";
				}
			}
			$html .= "<div><h2><a href=\"/".$setup['pages_folder']."/".$subpage['date_link']."/\">".$subpage['date_title']."</a></h2></div>";
			if(!empty($subpage['page_snippet'])) {
				$sub_descr = $subpage['page_snippet'];
				$html .= "$sub_descr"; 
			} else { 
				$sub_descr = strip_tags($subpage['date_text']);
				$sub_descr = preg_replace('/\s\s+/', ' ', $sub_descr);
				if(strlen($sub_descr) > $site_setup['page_trim_sub_descr']) {

					$sub_descr = (substr_replace(strip_tags(trim($sub_descr)), "", $site_setup['page_trim_sub_descr']). "");
					$sub_descr = str_replace('"',"",$sub_descr);
					$sub_descr = str_replace('&nbsp;'," ",$sub_descr);
					$sub_descr = trim($sub_descr);
					$html .= "$sub_descr......."; 
				} else { 
					$html .= "$sub_descr"; 
				}
			}

		$html .= "<div class=\"cssClear\"></div></div>";
		}
	} 
	return $html;
} 

 function eventHeadlines() {
	global $site_setup,$hp,$setup;
		$html .= "<div class=pageContent><div id=\"homePageLabels\">"._home_page_events_."</div></div>";
		$dates = whileSQL("ms_calendar", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show", "WHERE date_id>'0' AND date_date>='".date('Y-m-d')."' AND date_public='1' AND date_type='cal' ORDER BY date_date ASC,date_time ASC LIMIT ".$hp['events_limit']." ");
		if(mysqli_num_rows($dates)<=0) {
			$html .= "<div class=pageContent style=\"text-align: center;\">No events added</div>";
		}
			while($date = mysqli_fetch_array($dates)) {
				$html .= "<div>";
				$html .= "<div class=pageContent><div id=\"newsHeadlines\"><a href=\"/".$setup['calendar_folder']."/".$date['date_link']."/\">".$date['date_title']."</a></div></div>";
				$html .= "<div class=pageContent><div class=\"newsDate\">".$date['date_show_date']."";
				if(!empty($date['date_where'])) {
					$html .= " <div style=\"float: right;\">".$date['date_where']."</div>";;
				}
				$html .= "</div></div>";
				$html .= "</div>";
				$html .= "<div id=\"newsSeparator\">&nbsp;</div>";

			}
			$html .= "<div class=pageContent><div id=\"homePageMore\"><a href=\"".$setup['calendar_folder']."/\">"._home_page_more_events_."</a></div></div>";
		$html .= "<div>&nbsp;</div>";

	return $html;
}

function photoAlbums() {
	global $site_setup,$hp,$setup;
	$photo_setup = doSQL("ms_photo_setup", "*", "  ");
	if($photo_setup['album_order_by'] == "gal_date" ) {
		$gal_order_by = "date_date";
	}
	if($photo_setup['album_order_by'] == "gal_title" ) {
		$gal_order_by = "date_title";
	}

	$thumb_max_width = $hp['photos_size'];
	$hpf = doSQL("ms_home_page_items", "*", "WHERE widget='PHOTO_ALBUMS' ");
	if(!empty($hpf['feat_label'])) { 
		$html .= "<div class=pageContent><div id=\"homePageLabels\">".$hpf['feat_label']."</div></div>";
	}

	$thumb_nails = array();
	$html .= "<div class=pageContent>";
	$gals= whileSQL("ms_calendar", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show", "WHERE  date_type='gal'  AND date_public='1' AND private<='1' ORDER BY $gal_order_by ".$photo_setup['album_acdc']."" );


	while($gal = mysqli_fetch_array($gals)) {
		if(!empty($gal['date_mini'])) {
			$size = @GetImageSize("".$setup['path']."/".$setup['photos_folder']."/".$gal['date_link']."/".$gal['date_mini'].""); 
			$gal_link = "<a href=\"/".$setup['photos_folder']."/".$gal['date_link']."/\"><img src=\"/".$setup['photos_folder']."/".$gal['date_link']."/".$gal['date_mini']."\"  width=\"".$size[0]."px\" height=\"".$size[1]."px\" border=\"0\" class=\"homePageThumb\"></a>";
		} else {

			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$gal['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
			if(!empty($pic['pic_id'])) {
				$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'].""); 
				$gal_link = "<a href=\"/".$setup['photos_folder']."/".$gal['date_link']."/\"><img src=\"/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']."\"  width=\"".$size[0]."px\" height=\"".$size[1]."px\" border=\"0\" class=\"homePageThumb\"></a>";

			}
		}

			$html .= "<div class=\"homePagePhotoAlbums\">\r\n";
			$html .= "$gal_link";
			$html .= "<h2><a href=\"/".$setup['photos_folder']."/".$gal['date_link']."/\">".$gal['date_title']."</a></h2>";
			$html .= "<div style=\"clear: both;\"></div>\r\n";
			$html .= "</div>";
			$html .= "<div>&nbsp;</div>";
		
	}
	$html .= "<div style=\"clear: both;\"></div>";
	$html .= "</div>";
	return $html;
}


$string = " this is where I wan't to add [WIDGET]23[/WIDGET] widget thing.";
$starttagname = "[WIDGET]";
$endtagname = "[/WIDGET]";
$widget_id = extract_unit($string, $starttagname, $endtagname);

function extract_unit($string, $start, $end){
	$pos = stripos($string, $start); 
	$str = substr($string, $pos);
	$str_two = substr($str, strlen($start));
	$second_pos = stripos($str_two, $end);
	$str_three = substr($str_two, 0, $second_pos);
	$unit = trim($str_three); // remove whitespaces
	return $unit;
}



function getCountry($ct, $st_remote_host) {

   if (preg_match("/.com/i", $ct)) {
      $ctlist['USA']++;
      }
      elseif (preg_match("/.net/i", $ct)) {
      $ctlist['USA']++;
      }
      elseif (preg_match("/.org/i", $ct)) {
      $ctlist['USA']++;
      }
      elseif (preg_match("/.edu/i", $ct)) {
      $ctlist['USA']++;
      }
      elseif (preg_match("/.us/i", $ct)) {
      $ctlist['USA']++;
      }
      elseif (preg_match("/.mil/i", $ct)) {
      $ctlist['USA']++;
      }
      elseif (preg_match("/.ca/i", $ct)) {
      $ctlist['Canada']++;
      }
      elseif (preg_match("/.uk/i", $ct)) {
      $ctlist['UK']++;
      }
      elseif (preg_match("/.de/i", $ct)) {
      $ctlist['Germany']++;
      }
      elseif (preg_match("/.nl/i", $ct)) {
      $ctlist['Netherlands']++;
      }
      elseif (preg_match("/.fr/i", $ct)) {
      $ctlist['France']++;
      }
      elseif (preg_match("/.jp/i", $ct)) {
      $ctlist['Japan']++;
      }

      elseif (preg_match("/.ch/i", $ct)) {
      $ctlist['Switzerland']++;
      }
      elseif (preg_match("/.cr/i", $ct)) {
      $ctlist['Costa Rica']++;
      }
      elseif (preg_match("/.my/i", $ct)) {
      $ctlist['Malaysia']++;
      }
      elseif (preg_match("/.pl/i", $ct)) {
      $ctlist['Poland']++;
      }
      elseif (preg_match("/.pk/i", $ct)) {
      $ctlist['Pakistan']++;
      }

      elseif (preg_match("/.no/i", $ct)) {
      $ctlist['Norway']++;
      }
      elseif (preg_match("/.yu/i", $ct)) {
      $ctlist['Yugoslavia']++;
      }
      elseif (preg_match("/.ma/i", $ct)) {
      $ctlist['Morocco']++;
      }
      elseif (preg_match("/.mx/i", $ct)) {
      $ctlist['Mexico']++;
      }
      elseif (preg_match("/.gov/i", $ct)) {
      $ctlist['USA Gov.']++;
      }
      elseif (preg_match("/.tr/i", $ct)) {
      $ctlist['Turkey']++;
      }
      elseif (preg_match("/.gr/i", $ct)) {
      $ctlist['Greece']++;
      }
      elseif (preg_match("/.be/i", $ct)) {
      $ctlist['Belgium']++;
      }
      elseif (preg_match("/.cz/i", $ct)) {
      $ctlist['Czech Republic']++;
      }
      elseif (preg_match("/.sk/i", $ct)) {
      $ctlist['Slovakia']++;
      }

      elseif (preg_match("/.lt/i", $ct)) {
      $ctlist['Lithuania']++;
      }
      elseif (preg_match("/.ar/i", $ct)) {
      $ctlist['Argentina']++;
      }
      elseif (preg_match("/.at/i", $ct)) {
      $ctlist['Austria']++;
      }
      elseif (preg_match("/.us/i", $ct)) {
      $ctlist['USA']++;
      }

      elseif (preg_match("/.bm/i", $ct)) {
      $ctlist['Bermuda']++;
      }
      elseif (preg_match("/.nz/i", $ct)) {
      $ctlist['New Zealand']++;
      }
      elseif (preg_match("/.hu/i", $ct)) {
      $ctlist['Hungary']++;
      }
      elseif (preg_match("/.fi/i", $ct)) {
      $ctlist['Finland']++;
      }
      elseif (preg_match("/.gb/i", $ct)) {
      $ctlist['Great Britain']++;
      }
      elseif (preg_match("/.br/i", $ct)) {
      $ctlist['Brazil']++;
      }
      elseif (preg_match("/.za/i", $ct)) {
      $ctlist['South Africa']++;
      }

      elseif (preg_match("/.au/i", $ct)) {
      $ctlist['Australia']++;
      }
      elseif (preg_match("/.it/i", $ct)) {
      $ctlist['Italy']++;
      }
      elseif (preg_match("/.vi/i", $ct)) {
      $ctlist['U.S. Virgin Islands']++;
      }
      elseif (preg_match("/.es/i", $ct)) {
      $ctlist['Spain']++;
      }
      elseif (preg_match("/.dk/i", $ct)) {
      $ctlist['Denmark']++;
      }

      elseif (preg_match("/.se/i", $ct)) {
      $ctlist['Sweden']++;
      }
      elseif (preg_match("/.th/i", $ct)) {
      $ctlist['Thailand']++;
      }
      elseif (preg_match("/.id/i", $ct)) {
      $ctlist['Indonesia']++;
      }
      elseif (preg_match("/.ie/i", $ct)) {
      $ctlist['Ireland']++;
      }
      elseif (preg_match("/.il/i", $ct)) {
      $ctlist['Israel']++;
      }
      elseif (preg_match("/.in/i", $ct)) {
      $ctlist['India']++;
      }
      elseif (preg_match("/.jm/i", $ct)) {
      $ctlist['Jamaica']++;
      }
      elseif (preg_match("/.is/i", $ct)) {
      $ctlist['Iceland']++;
      }
      elseif (preg_match("/.iq/i", $ct)) {
      $ctlist['Iraq']++;
      }
	  elseif (preg_match("/.ir/i", $ct)) {
      $ctlist['Iran']++;
      }

  	  elseif (preg_match("/.ru/i", $ct)) {
      $ctlist['Russia']++;
      }
	  elseif (preg_match("/.pr/i", $ct)) {
      $ctlist['Puerto Rico']++;
      }
	  elseif (preg_match("/.pt/i", $ct)) {
      $ctlist['Portugal']++;
      }
	  elseif (preg_match("/.sa/i", $ct)) {
      $ctlist['Saudi Arabia']++;
      }
	  elseif (preg_match("/.sg/i", $ct)) {
      $ctlist['Singapore']++;
      }
	  elseif (preg_match("/.kw/i", $ct)) {
      $ctlist['Kuwait']++;
      }
	  elseif (preg_match("/.sc/i", $ct)) {
      $ctlist['Seychelles']++;
      }
	  elseif (preg_match("/.ph/i", $ct)) {
      $ctlist['Philippines']++;
      }
	  elseif (preg_match("/.ee/i", $ct)) {
      $ctlist['Estonia']++;
      }
	  elseif (preg_match("/.hr/i", $ct)) {
      $ctlist['Croatia/Hrvatska']++;
      }

	  else {
		if(is_numeric($ct) == true) {

		$ctlist['Unknown']++;

		} else {
			$ctlist['Other']++;
		}
	}
	
	arsort($ctlist, SORT_NUMERIC); 
	foreach($ctlist AS $oc => $cc) {
		if($cc > 0) {
			return $oc;
		}
	}

}




function getBrowser($browser) {


		  if (preg_match("/opera/i", $browser)) {
			  $browserlist['Opera']++;
			  }
		 elseif (preg_match("/konqueror/i", $browser)) {
				 $browserlist['Konqueror']++;
				 }
		 elseif (preg_match("/ipad/i", $browser)) {
				 $browserlist['iPad']++;
				 }
		 elseif (preg_match("/ipod/i", $browser)) {
				 $browserlist['iPod']++;
				 }
		 elseif (preg_match("/iphone/i", $browser)) {
				 $browserlist['iPhone']++;
				 }
		 elseif (preg_match("/android/i", $browser)) {
				 $browserlist['Android']++;
				 }
		 elseif (preg_match("/blackberry/i", $browser)) {
				 $browserlist['Blackberry']++;
				 }

		 
		 
		 elseif (preg_match("/chrome/i", $browser)) {
				 $browserlist['Chrome']++;
				 }
		  elseif (preg_match("/msie/i", $browser)) {
				 $browserlist['Internet Explorer']++;
				 }
//		  elseif (preg_match("/lynx/i", $browser)) {
//				 $browserlist['Lynx']++;
//				 }
		  elseif (preg_match("/rv:11/i", $browser)) {
				 $browserlist['Internet Explorer']++;
				 }


		  elseif (preg_match("/firefox/i", $browser)) {
				 $browserlist['Firefox']++;
				 }
		  elseif (preg_match("/firebird/i", $browser)) {
				 $browserlist['Firebird']++;
				 }
		  elseif (preg_match("/safari/i", $browser)) {
				 $browserlist['Safari']++;
				 }
		  elseif (preg_match("/mozilla\/4/i", $browser)) {
				 $browserlist['Netscape 4']++;
				 }

	  elseif (preg_match("/grub/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/spider/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/msnbot/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/networkquality/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/bot.html/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/Gigabot/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/scooter/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/InternetSeer.com/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/Teoma/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/SurveyBot/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/linksmanager/i", $browser)) {
      $browserlist['Spider']++;
	  }

	  elseif (preg_match("/BecomeBot/i", $browser)) {
      $browserlist['Spider']++;
	  }

	  elseif (preg_match("/slurp/i", $browser)) {
      $browserlist['Spider']++;
	  }

	  elseif (preg_match("/crawler/i", $browser)) {
      $browserlist['Spider']++;
	  }

		  elseif (preg_match("/mozilla\/5/i", $browser)) {
				 $browserlist['Netscape 5/6']++;
				 } else {
					 $browserlist[Other]++;
					 $other .= "$browser, ";
				 }

			arsort($browserlist, SORT_NUMERIC); 
			foreach($browserlist AS $oc => $cc) {
				if($cc > 0) {
					return $oc;
				}
			}

		}


function getBrowserVersion($browser) {


		  if (preg_match("/opera/i", $browser)) {
			  $browserlist['Opera']++;
			  }
		 elseif (preg_match("/konqueror/i", $browser)) {
				 $browserlist['Konqueror']++;
				 }
		 elseif (preg_match("/ipad/i", $browser)) {
				 $browserlist['iPad']++;
				 }
		 elseif (preg_match("/ipod/i", $browser)) {
				 $browserlist['iPod']++;
				 }
		 elseif (preg_match("/iphone/i", $browser)) {
				 $browserlist['iPhone']++;
				 }
		 elseif (preg_match("/android/i", $browser)) {
				 $browserlist['Android']++;
				 }
		 elseif (preg_match("/blackberry/i", $browser)) {
				 $browserlist['Blackberry']++;
				 }

		 
		 
		 elseif (preg_match("/chrome/i", $browser)) {
				 $browserlist['Chrome']++;
				 }
		  elseif (preg_match("/msie 3/i", $browser)) {
				 $browserlist['Internet Explorer 3']++;
				 }
		  elseif (preg_match("/msie 4/i", $browser)) {
				 $browserlist['Internet Explorer 4']++;
				 }
		  elseif (preg_match("/msie 5/i", $browser)) {
				 $browserlist['Internet Explorer 5']++;
				 }
		  elseif (preg_match("/msie 6/i", $browser)) {
				 $browserlist['Internet Explorer 6']++;
				 }
		  elseif (preg_match("/msie 7/i", $browser)) {
				 $browserlist['Internet Explorer 7']++;
				 }
		  elseif (preg_match("/msie 8/i", $browser)) {
				 $browserlist['Internet Explorer 8']++;
				 }
		  elseif (preg_match("/msie 9/i", $browser)) {
				 $browserlist['Internet Explorer 9']++;
				 }
		  elseif (preg_match("/msie 10/i", $browser)) {
				 $browserlist['Internet Explorer 10']++;
				 }
		  elseif (preg_match("/rv:11/i", $browser)) {
				 $browserlist['Internet Explorer 11']++;
				 }
		  elseif (preg_match("/msie/i", $browser)) {
				 $browserlist['Internet Explorer ?']++;
				 }
//		  elseif (preg_match("/lynx/i", $browser)) {
//				 $browserlist['Lynx']++;
//				 }

		  elseif (preg_match("/firefox\/10/i", $browser)) {
				 $browserlist['Firefox 10']++;
				 }
		  elseif (preg_match("/firefox\/3/i", $browser)) {
				 $browserlist['Firefox 3']++;
				 }

		  elseif (preg_match("/firefox\/4/i", $browser)) {
				 $browserlist['Firefox 4']++;
				 }
		  elseif (preg_match("/firefox\/5/i", $browser)) {
				 $browserlist['Firefox 5']++;
				 }
		  elseif (preg_match("/firefox\/6/i", $browser)) {
				 $browserlist['Firefox 6']++;
				 }
		  elseif (preg_match("/firefox\/7/i", $browser)) {
				 $browserlist['Firefox 7']++;
				 }
		  elseif (preg_match("/firefox\/8/i", $browser)) {
				 $browserlist['Firefox 8']++;
				 }
		  elseif (preg_match("/firefox\/9/i", $browser)) {
				 $browserlist['Firefox 9']++;
				 }
		  elseif (preg_match("/firefox\/11/i", $browser)) {
				 $browserlist['Firefox 11']++;
				 }
		  elseif (preg_match("/firefox\/12/i", $browser)) {
				 $browserlist['Firefox 12']++;
				 }
		  elseif (preg_match("/firefox\/13/i", $browser)) {
				 $browserlist['Firefox 13']++;
				 }
		  elseif (preg_match("/firefox\/14/i", $browser)) {
				 $browserlist['Firefox 14']++;
				 }
		  elseif (preg_match("/firefox\/15/i", $browser)) {
				 $browserlist['Firefox 15']++;
				 }
		  elseif (preg_match("/firefox\/16/i", $browser)) {
				 $browserlist['Firefox 16']++;
				 }
		  elseif (preg_match("/firefox\/17/i", $browser)) {
				 $browserlist['Firefox 17']++;
				 }
		  elseif (preg_match("/firefox\/18/i", $browser)) {
				 $browserlist['Firefox 18']++;
				 }
		  elseif (preg_match("/firefox\/19/i", $browser)) {
				 $browserlist['Firefox 19']++;
				 }
		  elseif (preg_match("/firefox\/20/i", $browser)) {
				 $browserlist['Firefox 20']++;
				 }
		  elseif (preg_match("/firefox\/21/i", $browser)) {
				 $browserlist['Firefox 21']++;
				 }
		  elseif (preg_match("/firefox\/22/i", $browser)) {
				 $browserlist['Firefox 22']++;
				 }
		  elseif (preg_match("/firefox\/23/i", $browser)) {
				 $browserlist['Firefox 23']++;
				 }
		  elseif (preg_match("/firefox\/24/i", $browser)) {
				 $browserlist['Firefox 24']++;
				 }
		  elseif (preg_match("/firefox\/25/i", $browser)) {
				 $browserlist['Firefox 25']++;
				 }
		  elseif (preg_match("/firefox\/2/i", $browser)) {
				 $browserlist['Firefox 1']++;
				 }
		  
		  
		  
		  
		  elseif (preg_match("/firefox/i", $browser)) {
				 $browserlist['Firefox']++;
				 }
		  elseif (preg_match("/firebird/i", $browser)) {
				 $browserlist['Firebird']++;
				 }
		  elseif (preg_match("/safari/i", $browser)) {
				 $browserlist['Safari']++;
				 }
		  elseif (preg_match("/mozilla\/4/i", $browser)) {
				 $browserlist['Netscape 4']++;
				 }

	  elseif (preg_match("/grub/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/spider/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/msnbot/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/networkquality/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/bot.html/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/Gigabot/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/scooter/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/InternetSeer.com/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/Teoma/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/SurveyBot/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/linksmanager/i", $browser)) {
      $browserlist['Spider']++;
	  }

	  elseif (preg_match("/BecomeBot/i", $browser)) {
      $browserlist['Spider']++;
	  }

	  elseif (preg_match("/slurp/i", $browser)) {
      $browserlist['Spider']++;
	  }

	  elseif (preg_match("/crawler/i", $browser)) {
      $browserlist['Spider']++;
	  }

		  elseif (preg_match("/mozilla\/5/i", $browser)) {
				 $browserlist['Netscape 5/6']++;
				 } else {
					 $browserlist[Other]++;
					 $other .= "$browser, ";
				 }

			arsort($browserlist, SORT_NUMERIC); 
			foreach($browserlist AS $oc => $cc) {
				if($cc > 0) {
					return $oc;
				}
			}

		}

function countCatBlogPosts($cat_id) {
	global $_REQUEST;
	$cats = whileSQL("ms_blog_categories", "*", "WHERE cat_id='$cat_id' ORDER BY cat_name ASC ");
	while($cat = mysqli_fetch_array($cats)) { 
		$posts = $posts + countIt("ms_calendar", "WHERE date_cat='".$cat['cat_id']."'  AND date_public='1' ");
		$categories++;			
		$substats = countCatSubBlogPosts($cat,$cfield,$doselect); 
		$posts = $posts + $substats['posts'];
		$categories = $categories + $substats['categories'];
	} 
	$gstats['posts'] = $posts;
	$gstats['categories'] = $categories;
	return $gstats;
 }

function sytistreg($upgrade,$version) { 
	global $setup,$site_setup;
	$reg = doSQL("ms_register", "*", ""); 
	if($upgrade == true) { 
		$upgrade = 1;
	}
	if(empty($version)) { 
		$version = $site_setup['sytist_version'];
	}
	$url = $_SERVER['HTTP_HOST'].$setup['temp_url_folder']."/".$setup['manage_folder'];
	$up = @url_get_contents("https://www.picturespro.com/sytistupdated.php?version=".$version."&reg=".$reg['reg_key']."&url=".urlencode($url)."&em=".urlencode($site_setup['contact_email'])."&ip=".urlencode($_SERVER['REMOTE_ADDR'])."&upgrade=".$upgrade."");
}


function countCatSubBlogPosts($cat,$cfield,$doselect) {
	global $_REQUEST;
	$cats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='".$cat['cat_id']."' ORDER BY cat_name ASC ");
	while($cat = mysqli_fetch_array($cats)) {
		$up_gal = $cat['cat_under'];
		while(!empty($up_gal)) {
			$tgal = doSQL("ms_blog_categories", "*", "WHERE cat_id='$up_gal' ");
			$up_gal = $tgal['cat_under'];
		}
		$posts = $posts + countIt("ms_calendar", "WHERE date_cat='".$cat['cat_id']."' AND date_public='1'  ");
		$categories++;			
		$substats = countCatSubBlogPosts($cat,$cfield,$doselect); 
		$posts = $posts + $substats['posts'];
		$categories = $categories + $substats['categories'];
	 } 
	 $substats['posts'] = $posts;
	 $substats['categories'] = $categories;
	 return $substats;
}

function replcSpecChar($string){
	$string = str_replace("æ", "&aelig;", $string);
	$string = str_replace("ø", "&oslash;", $string);
	$string = str_replace("å", "&aring;", $string);
	$string = str_replace("Æ", "&AElig;", $string);
	$string = str_replace("Ø", "&Oslash;", $string);
	$string = str_replace("Å", "&Aring;", $string);

	$string = str_replace("Ã¦", "æ",$string);
	$string = str_replace("Ã¸", "ø",$string);
	$string = str_replace("Ã¥", "å",$string);
	$string = str_replace("Ã†", "Æ",$string);
	$string = str_replace("Ã˜", "Ø",$string);
	$string = str_replace("Ã…", "Å",$string);

	$string = str_replace("Ã¡", "á",$string);
	$string = str_replace("Ã©", "é",$string);
	$string = str_replace("Ã­", "í",$string);
	$string = str_replace("Ã³", "ó",$string);
	$string = str_replace("Ãº", "ú",$string);
	$string = str_replace("Ã±", "ñ",$string);




  return $string;
}
function sendgiftcertificateemail($gcid) { 
	global $site_setup,$setup;
	
	$gc = doSQL("ms_gift_certificates", "*", "WHERE id='".$gcid."' ");

	$to_email = $gc['to_email'];
	$to_name = $gc['to_name'];

	$em = doSQL("ms_emails", "*", "WHERE email_id_name='giftcertificate' ");
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

	$subject = "".$em['email_subject']."";
	$message = $em['email_message'];

	$card = gift_card_style;
	$card = str_replace("[REDEEM_CODE]",$gc['redeem_code'], $card);
	$card = str_replace("[WEBSITE_NAME]",$site_setup['website_title'], $card);
	$card = str_replace("[AMOUNT]",showPrice($gc['amount']), $card);
	$card = str_replace("contenteditable","", $card);
	$message = str_replace("[GIFT_CARD]",$card,$message);

	$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
	$message = str_replace("[AMOUNT]","".showPrice($gc['amount']), "$message");
	$message = str_replace("[WEBSITE_NAME]","<a href=\"".$setup['url'].$setup['temp_url_folder']."\">".$site_setup['website_title']."</a>", "$message");
	$message = str_replace("[NAME]",$to_name, "$message");
	$message = str_replace("[EMAIL_ADDRESS]",$p['p_email'], "$message");
	$message = str_replace("[MESSAGE]",nl2br($gc['message']), "$message");
	$message = str_replace("[BUYER_NAME]",$gc['from_name'], "$message");
	$message = str_replace("[BUYER_EMAIL]",$gc['from_email'], "$message");
	$message = str_replace("[REDEEM_CODE]",$gc['redeem_code'], "$message");

	$message = str_replace("[LINK]",$setup['url'].$setup['temp_url_folder'].$date['cat_folder']."/".$date['date_link']."/", "$message");


	$subject = str_replace("[NAME]",$to_name, "$subject");
	$subject = str_replace("[WEBSITE_NAME]",$site_setup['website_title'], "$subject");
	$subject = str_replace("[AMOUNT]","".showPrice($gc['amount']), "$subject");
	$subject = str_replace("[BUYER_NAME]",$gc['from_name'], "$subject");
	stripslashes($message);
	stripslashes($subject);

	sendWebdEmail($to_email, $to_name, $from_email, $from_name, $subject, $message,"1");
	updateSQL("ms_gift_certificates", "emailed_date='".date('Y-m-d')."' WHERE id='".$gc['id']."' ");

}

function sendregistrynotification($icart,$order_id) { 
	global $site_setup,$setup;

	$order = doSQL("ms_orders", "*", "WHERE order_id='".$order_id."' ");
	$cart = doSQL("ms_cart", "*", "WHERE cart_id='".$icart['cart_id']."' ");
	$p = doSQL("ms_people", "*", "WHERE p_id='".$cart['cart_account_credit_for']."' ");
	$to_email = $p['p_email'];
	$to_name = $p['p_name']." ".$p['p_last_name'];

	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_store_product']."' ");
	if(empty($p['p_id'])) { exit(); } 


	$em = doSQL("ms_emails", "*", "WHERE email_id_name='registrypurchase' ");
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
	$subject = "".$em['email_subject']."";

	$message = $em['email_message'];

	$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
	$message = str_replace("[REGISTRY_PURCHASE_AMOUNT]","".showPrice($cart['cart_account_credit']), "$message");
	$message = str_replace("[WEBSITE_NAME]",$site_setup['website_title'], "$message");
	$message = str_replace("[ORDER_NUMBER]",$order_id, "$message");
	$message = str_replace("[FIRST_NAME]",$p['p_name'], "$message");
	$message = str_replace("[LAST_NAME]",$p['p_last_name'], "$message");
	$message = str_replace("[EMAIL_ADDRESS]",$p['p_email'], "$message");
	$message = str_replace("[BUYER_FIRST_NAME]",$order['order_first_name'], "$message");
	$message = str_replace("[BUYER_LAST_NAME]",$order['order_last_name'], "$message");
	$message = str_replace("[BUYER_EMAIL]",$order['order_email'], "$message");
	$message = str_replace("[PAGE_TITLE]",$date['date_title'], "$message");
	$message = str_replace("[LINK]",$setup['url'].$setup['temp_url_folder'].$date['cat_folder']."/".$date['date_link']."/", "$message");


	$subject = str_replace("[FIRST_NAME]",$p['p_name'], "$subject");
	$subject = str_replace("[LAST_NAME]",$p['p_last_name'], "$subject");
	$subject = str_replace("[WEBSITE_NAME]",$site_setup['website_title'], "$subject");
	$subject = str_replace("[ORDER_NUMBER]",$order_id, "$subject");
	stripslashes($message);
	stripslashes($subject);

	sendWebdEmail($to_email, $to_name, $from_email, $from_name, $subject, $message,"1");

}

 function showOrderExtraFields($order) { 
	 if(!empty($order['order_extra_field_1'])) { ?>
	<div>&nbsp;</div>
		<div class="row underline pc">
			<div style="width: 30%; float: left;"><?php print $order['order_extra_field_1'];?></div>
			<div style="float: left;"><?php print $order['order_extra_val_1'];?></div>
			<div class="clear"></div>
		</div>
		<?php if(!empty($order['order_extra_field_2'])) { ?>
		<div class="row underline pc">
			<div style="width: 30%; float: left;"><?php print $order['order_extra_field_2'];?></div>
			<div style="float: left;"><?php print $order['order_extra_val_2'];?></div>
			<div class="clear"></div>
		</div>
		<?php } ?>
		<?php if(!empty($order['order_extra_field_3'])) { ?>
		<div class="row underline pc">
			<div style="width: 30%; float: left;"><?php print $order['order_extra_field_3'];?></div>
			<div style="float: left;"><?php print $order['order_extra_val_3'];?></div>
			<div class="clear"></div>
		</div>
		<?php } ?>
		<?php if(!empty($order['order_extra_field_4'])) { ?>
		<div class="row underline pc">
			<div style="width: 30%; float: left;"><?php print $order['order_extra_field_4'];?></div>
			<div style="float: left;"><?php print $order['order_extra_val_4'];?></div>
			<div class="clear"></div>
		</div>
		<?php } ?>
		<?php if(!empty($order['order_extra_field_5'])) { ?>
		<div class="row underline pc">
			<div style="width: 30%; float: left;"><?php print $order['order_extra_field_5'];?></div>
			<div style="float: left;"><?php print $order['order_extra_val_5'];?></div>
			<div class="clear"></div>
		</div>
		<?php } ?>
		<div>&nbsp;</div>
	<?php } ?>
<?php } 


function joinmailinglist($email,$first_name,$last_name,$location) { 
	global $setup,$site_setup;
	$em_settings = doSQL("ms_email_list_settings", "*", "  ");
	$email = strtolower(trim($email));
	$email = sql_safe($email);
	$email = str_replace(" ","",$email);
	$name = trim($first_name);
	$name = sql_safe($name);
	$last_name = trim($last_name);
	$last_name = sql_safe($last_name);

	$ck = doSQL("ms_email_list", "*", "WHERE em_email='".$email."' AND em_do_not_send='0' ");
	if(empty($ck['em_id'])) { 
		$key = MD5($email.makesalt());
		if(($em_settings['double_opt_in'] == "1") && ($em_settings['mailchimp_enable'] !== "1") == true) { 
			$em_status = "1";
		} 

		if($em_settings['mailchimp_enable'] == "1") { 
			if($em_settings['mailchimp_double_optin'] == "1") { 
				$em_status = "1";
			}
		}
		$id = insertSQL("ms_email_list", "em_email='".addslashes(stripslashes($email))."' , em_name='".addslashes(stripslashes($name))."', em_last_name='".addslashes(stripslashes($last_name))."', em_ip='".getUserIP()."', em_date='".date('Y-m-d H:i:s')."', em_location='".sql_safe($location)."', em_status='".$em_status."', em_key='".$key."' ");

		if(($em_settings['double_opt_in'] == "1") && ($em_settings['mailchimp_enable'] !== "1") == true) { 
			sendmailinglistemail($email,$first_name,$last_name,$key,'maillistconfirm');
		}
		if(($em_settings['send_welcome_email'] == "1") && ($em_status !== "1") == true) { 
			sendmailinglistemail($email,$first_name,$last_name,$key,'maillistwelcome');
		}
		if($em_settings['mailchimp_enable'] == "1") { 
			include $setup['path']."/sy-inc/mail.chimp.functions.php";
			if($em_settings['mailchimp_double_optin'] == "1") { 
				$dop = 1;
			}

			$eid = mailchimpsubscribe($email,$name,$last_name);
			updateSQL("ms_email_list", "em_mailchimp_ud='".$eid."' , em_sent_to_mailchimp='1' , em_sent_to_mailchimp_doi='".$dop."' WHERE em_id='".$id."' ");
		}

	}
}


function sendmailinglistemail($email,$first_name,$last_name,$key,$defemail) { 
	global $setup, $site_setup;
		$em = doSQL("ms_emails", "*", "WHERE email_id_name='".$defemail."' ");
		if(!empty($em['email_id'])) { 
			$message = $em['email_message'];
			$subject = "".$em['email_subject']."";
			$subject = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$subject");
			$subject = str_replace("[FIRST_NAME]",stripslashes($first_name), "$subject");
			$subject = str_replace("[LAST_NAME]",stripslashes($last_name), "$subject");
			$message = str_replace("[FIRST_NAME]",stripslashes($first_name), "$message");
			$message = str_replace("[LAST_NAME]",stripslashes($last_name), "$message");
			$message = str_replace("[EMAIL]",stripslashes($email), "$message");
			$message = str_replace("[LINK_TO_WEBSITE]","<a href=\"".$setup['url'].$setup['temp_url_folder']."/\">", "$message");
			$message = str_replace("[/LINK_TO_WEBSITE]","</a>", "$message");
			$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
			$message = str_replace("[WEBSITE_NAME]","<a href=\"".$setup['url'].$setup['temp_url_folder']."\">".$site_setup['website_title']."</a>", "$message");

			$remove_link = $setup['url'].$setup['temp_url_folder']."/index.php?view=removeem&eid=".$key;	
			$confirm_link = $setup['url'].$setup['temp_url_folder']."/index.php?view=confirmemail&eid=".$key;	

			$message = str_replace("[CONFIRM_SUBSCRIPTION_LINK]","<a href=\"".$confirm_link."\">", "$message");
			$message = str_replace("[/CONFIRM_SUBSCRIPTION_LINK]","</a>", "$message");


			$message = str_replace("[UNSUBSCRIBE_LINK]","<a href=\"".$remove_link."\">", "$message");
			$message = str_replace("[/UNSUBSCRIBE_LINK]","</a>", "$message");
			if(empty($name)) { 
				$to_name = $name;
			}
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
			$to_name = $first_name." ".$last_name;
			if(empty($to_name)) { 
				$to_name = $email;
			}

			sendWebdEmail($email, $to_name, $from_email, $from_name, $subject, $message,"1");
		}
}


function bookingemail($id,$deposit,$confirmed) { 
	global $setup,$site_setup;
	$book = doSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*", "WHERE book_id='".$id."' ");

	$em = doSQL("ms_emails", "*", "WHERE email_id_name='bookingrequest' ");

	$subject = "".$em['email_subject']."";
	$to_email = $site_setup['contact_email'];
	$to_name = $site_setup['contact_email'];
	$from_email = $site_setup['contact_email'];
	$from_name = $site_setup['website_title'];
	
	$subject = str_replace("[WEBSITE_NAME]",$site_setup['website_title'], "$subject");
	$subject = str_replace("[FIRST_NAME]",$book['book_first_name'], "$subject");
	$subject = str_replace("[LAST_NAME]",$book['book_last_name'], "$subject");

	$message = $em['email_message'];
	$message = str_replace("[WEBSITE_NAME]","<a href=\"".$setup['url'].$setup['temp_url_folder']."\">".$site_setup['website_title']."</a>", "$message");
	$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");

	$message = str_replace("[FIRST_NAME]",$book['book_first_name'], "$message");
	$message = str_replace("[LAST_NAME]",$book['book_last_name'], "$message");
	$message = str_replace("[EMAIL_ADDRESS]",$book['book_email'], "$message");
	$message = str_replace("[PHONE]",$book['book_phone'], "$message");
	$message = str_replace("[BOOKING_SERVICE]",$book['date_title'], "$message");
	$d = explode("-",$book['book_date']);
	if($book['book_time'] == "00:00:01") { 
		$strdate =  strftime("%a %B %e, %Y ", strtotime(date("Y-m-d H:i:s", mktime($t[0],$t[1],0,$d[1],$d[2],$d[0]))));
		if(empty($strdate)) { 
			$strdate = date("D F j, Y ", mktime($t[0],$t[1],0,$d[1],$d[2],$d[0]));
		}
	} else { 
		$t = explode(":",$book['book_time']);
		$strdate =  strftime("%a %B %e, %Y %l:%M %P", strtotime(date("Y-m-d H:i:s", mktime($t[0],$t[1],0,$d[1],$d[2],$d[0]))));
		if(empty($strdate)) { 
			$strdate = date("D F j, Y  g:i A", mktime($t[0],$t[1],0,$d[1],$d[2],$d[0]));
		}
	}
	$message = str_replace("[BOOKING_DATE]",$strdate, "$message");
	$message = str_replace("[BOOKING_DEPOSIT]",$deposit, "$message");
	$message = str_replace("[BOOKING_CONFIRMED]",$confirmed, "$message");
	$message = str_replace("[LINK_TO_ADMIN]",$setup['url'].$setup['temp_url_folder']."/".$setup['manage_folder']."/", "$message");
	$opts = explode("\n",$book['book_options']);
	foreach($opts AS $opt) { 
		if(!empty($opt)) { 
			$o = explode("|",$opt);
			if(!empty($o[0])) { 
				$options .= $o[0]; if(!empty($o[1])) { $options .= ": ".$o[1];} if($o[2] > 0) { $options .= "  ".showPrice($o[2]); } $options .= "<br>";
			}
		}
	}
	$message = str_replace("[BOOKING_OPTIONS]",$options, $message);
	stripslashes($message);
	stripslashes($subject);

	sendWebdEmail($to_email, $to_name, $from_email, $from_name, $subject, $message,"1");
}




function bookingconfirmemail($book) { 
	global $setup, $site_setup;
	$message = $book['book_confirm_email'];
	$subject = "".$book['book_confirm_email_subject']."";
	$from_email = $site_setup['contact_email'];
	$from_name = $site_setup['website_title'];
	$to_email = $book['book_email'];
	$to_name = stripslashes($book['book_first_name'])." ".stripslashes($book['book_last_name']);
	$message = str_replace("[BOOKING_DATE]",$book['book_date'], $message);
	$message = str_replace("[BOOKING_TIME]",$book['book_time_show'], $message);
	if($book['book_service'] <= 0) { 
		$message = str_replace("[BOOKING_SERVICE]",$book['book_event_name'], $message);
	} else { 
		$message = str_replace("[BOOKING_SERVICE]",$book['date_title'], $message);
	}
	$opts = explode("\n",$book['book_options']);
	foreach($opts AS $opt) { 
		if(!empty($opt)) { 
			$o = explode("|",$opt);
			if(!empty($o[0])) { 
				$options .= $o[0]; if(!empty($o[1])) { $options .= ": ".$o[1];} if($o[2] > 0) { $options .= "  ".showPrice($o[2]); } $options .= "<br>";
			}
		}
	}
	$message = str_replace("[BOOKING_OPTIONS]",$options, $message);


	$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
	$message = str_replace("[ACCOUNT_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder']."/index.php?view=account\">".$setup['url'].$setup['temp_url_folder']."/index.php?view=account</a>", "$message");
	$message = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$message");
	$message = str_replace("[FIRST_NAME]",stripslashes($book['book_first_name']), "$message");
	$message = str_replace("[LAST_NAME]",stripslashes($book['book_last_name']), "$message");
	$message = str_replace("[EXPIRATION_DATE]",$date['date_expire'], "$message");
	$message = str_replace("[EMAIL]",$to_email, "$message");
	$message = str_replace("[PAGE_TITLE]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$date['date_title']."</a>", "$message");

	$message = str_replace("[LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/</a>", "$message");
	$message = str_replace("[link]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/</a>", "$message");
	$message = str_replace("[PASSWORD]",$date['password'], "$message");

	$message = str_replace("[PAGE_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."\">", $message);
	$message = str_replace("[/PAGE_LINK]","</a>", $message);
	$message = str_replace("[LINK_TO_WEBSITE]","<a href=\"".$setup['url'].$setup['temp_url_folder']."\">", $message);
	$message = str_replace("[/LINK_TO_WEBSITE]","</a>", $message);

	$subject = str_replace("[PAGE_TITLE]",$date['date_title'], "$subject");
	$subject = str_replace("[EXPIRATION_DATE]",$date['date_expire'], "$subject");
	$subject = str_replace("[BOOKING_DATE]",$book['book_date'], $subject);

	$subject = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$subject");
	$subject = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$subject");
	$subject = str_replace("[FIRST_NAME]",stripslashes($book['book_first_name']), "$subject");
	$subject = str_replace("[LAST_NAME]",stripslashes($book['book_last_name']), "$subject");
	sendWebdEmail($to_email, $to_name, $from_email, $from_name, $subject, $message,"1");
}
function copy_amazon_file($source, $destination) {
	/* Function used if allow_url_fopen is set to off */
  $resource = curl_init();
  curl_setopt($resource, CURLOPT_URL, $source);
  curl_setopt($resource, CURLOPT_HEADER, false);
  curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($resource, CURLOPT_CONNECTTIMEOUT, 30);
 
  $content = curl_exec($resource);
 curl_error($resource);
  curl_close($resource);
  if($content != '')
  {
	$fp = fopen($destination, 'w');
	$fw = fwrite($fp, $content);
	fclose($fp);
 
	if($fw != false)
	{
	  return true;
	}
  }
 
  return false;
}
function addNote($table,$table_id,$message,$admin) { 
	insertSQL("ms_notes","note_date='".currentdatetime()."', note_table='".$table."', note_table_id='".$table_id."', note_note='".addslashes(stripslashes($message))."', note_ip='".getUserIP()."', note_admin='".$admin."' ");
}

function replacetextinputoption() { 
	return '<input type="text" name="text" id="'.rand(100000,10000000).'" size="20" class="contractfield textinput">';
}
function replacetextinputrequired() { 
	return '<input type="text" name="text" id="'.rand(100000,10000000).'" size="20" class="contractfield textinput contractrequired">';
}

function replacetextinputshortoption() { 
	return '<input type="text" name="text" id="'.rand(100000,10000000).'" size="3" class="contractfield textinput" style="text-align: center;">';
}
function replacetextinputshortrequired() { 
	return '<input type="text" name="text" id="'.rand(100000,10000000).'" size="3" class="contractfield textinput contractrequired"  style="text-align: center;">';
}

function replacecheckboxoption() { 
	return '<input type="checkbox" name="checkbox" id="'.rand(100000,10000000).'"  class="contractfield checkboxinput" value="1">';
}
function replacecheckboxoptionrequired() { 
	return '<input type="checkbox" name="checkbox" id="'.rand(100000,10000000).'"  class="contractfield checkboxinput contractrequired" value="1">';
}


?>