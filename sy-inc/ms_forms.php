<link rel="stylesheet" href="<?php print $setup['temp_url_folder'];?>/sy-inc/css/smoothness/jquery-ui.min.css" type="text/css"><script> 
$(document).ready(function(){
	$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'DD, MM d , yy' });
	});
});
</script>
<?php 
if(empty($setup['send_from_email'])) { 
	$setup['send_from_email'] = $site_setup['contact_email'];
}
function logformpost() { 
	global $setup;
	$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	if(!is_dir($setup['path']."/sy-logs")) { 
		// print "No direcory";
		$parent_permissions = substr(sprintf('%o', @fileperms("".$setup['path']."/sy-photos")), -4); 
		if($parent_permissions == "0755") {
			$perms = 0755;
		} elseif($parent_permissions == "0777") {
			$perms = 0777;
		} else {
			$perms = 0755;
		}
		mkdir("".$setup['path']."/sy-logs", $perms);
		chmod("".$setup['path']."/sy-logs", $perms);
		$fp = fopen("".$setup['path']."/sy-logs/index.php", "w");
		fputs($fp, "Nope");
		fclose($fp);
	}

	$lfile = "form-post-logs-".date('Y-m-d').".txt";

	if(!file_exists($setup['path']."/sy-logs/".$lfile)) { 
		$fp = fopen("".$setup['path']."/sy-logs/".$lfile, "w");
		fputs($fp,"");
		fclose($fp);
	}
	foreach($_POST AS $var => $val) { 
		$log .= " | [$var] -> $val ";
	}
	$info =  date('Y-m-d h:i:s')." ".getUserIP()." ".$_SERVER['HTTP_USER_AGENT']." ".$_SERVER['HTTP_REFERER']." ".$_SERVER['REMOTE_HOST']."  $log"; 
	// $info .=  " ".$_SERVER['HTTP_HOST'].urldecode($_SERVER['REQUEST_URI'])." | REFERER: ".$_SERVER['HTTP_REFERER'].""; 

	$info.= "\r\n";

	file_put_contents($setup['path']."/sy-logs/".$lfile, $info, FILE_APPEND);

}


function printForm($form_id) {
	global $setup;
	$form = doSQL("ms_forms", "*", "WHERE form_id='".$form_id."' ");

	//$form['form_captcha'] = "1";

	if(!empty($_POST['check'])) { 
		$message = "<table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" style=\"background-color: #dddddd;\" width=\"100%\">";
		$ffs = whileSQL("ms_form_fields", "*", "WHERE ff_id>'0' AND ff_form='".$form['form_id']."' ORDER BY ff_order ASC  ");
		while ($ff = mysqli_fetch_array($ffs)) {
			if(($ff['ff_required'] == "1")AND(empty($_REQUEST[$form['form_id']."-".$ff['ff_id']]))==true) { 
				$error .= "<div>".$ff['ff_name']." "._is_blank_."</div>";
			}
			if($ff['ff_type'] == "email") {
				$from_email = $_REQUEST[$form['form_id']."-".$ff['ff_id']];
			}
			$add = strip_tags($_POST[$form['form_id']."-".$ff['ff_id']]);
			$add = nl2br($add);
			$message .= "<tr valign=\"top\"><td style=\"background: #FFFFFF; padding: 8px;\">".$ff['ff_name']."</td><td style=\"background: #FFFFFF; padding: 8px;\">".stripslashes($add)."</td></tr>";

		}
		if(!empty($_REQUEST['from_message_to'])) { 
			$error = "Im sorry, but our spam bot protection is thinking you might be a spam bot. If this is in error, please email us directly at (".str_replace("@"," at ", $form['form_email_to'])."). Sorry for any inconvenience";
		}
		if(!empty($_REQUEST['email'])) { 
			$error = "Im sorry, but our spam bot protection is thinking you might be a spam bot. If this is in error, please email us directly at (".str_replace("@"," at ", $form['form_email_to'])."). Sorry for any inconvenience";
		}
		if(!empty($_REQUEST['name'])) { 
			$error = "Im sorry, but our spam bot protection is thinking you might be a spam bot. If this is in error, please email us directly at (".str_replace("@"," at ", $form['form_email_to'])."). Sorry for any inconvenience";
		}


		$message .= "</table><br><br>";

		if(!empty($error)) { 
			$html .= "<div><div class=errorMessage>$error</div></div>";
			$html .= theForm($form);
		} else {
			// logformpost();
			$message .= "-----------------------------------------------------------------------------------------------------------------------------<br><br>";
			$message .= "<table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" style=\"background-color: #dddddd;\" width=\"100%\">";
			$message .= "<tr valign=\"top\"><td style=\"background: #FFFFFF; padding: 8px;\">Sent from IP Address:</td><td style=\"background: #FFFFFF; padding: 8px;\">".getUserIP()."</td></tr>";
			$message .= "<tr valign=\"top\"><td style=\"background: #FFFFFF; padding: 8px;\">On website</td><td style=\"background: #FFFFFF; padding: 8px;\">".$_SERVER['HTTP_HOST']."</td></tr>";
			$message .= "<tr valign=\"top\"><td style=\"background: #FFFFFF; padding: 8px;\">Date</td><td style=\"background: #FFFFFF; padding: 8px;\">".date('l F d, Y g:i A')."</td></tr>";
			$message .="</table>";
			print "<pre>$message</pre>";

			$from_name = str_replace("@"," at ",$from_email);
			$from_name = str_replace("."," . ",$from_name);
			$subject = $form['form_subject'];
			$type = "text";
			print "<li>From: $from_email";
			$message_db = strip_tags($message);
			$message_db = nl2br($message);
			$message_db = sql_safe($message);

			$id = insertSQL("ms_form_submits", "fs_date=NOW(), fs_ip='".getUserIP()."', fs_email='".sql_safe($from_email)."', fs_to='".addslashes(stripslashes($form['form_email_to']))."', fs_message='".addslashes(stripslashes($message_db))."', fs_form='".$form['form_id']."' ");
			$subject = $subject." [$id]";

			$send_to = explode(",",$form['form_email_to']);
			foreach($send_to AS $to_email) {
//				print "<li>$to_email";
				$to_email = trim($to_email);
				sendWebdEmail($to_email, $to_name, $from_email, $from_name, $subject, $message,"1");
			}
			session_write_close();
			if(!empty($form['form_success_url'])) { 
				header("location: ".$form['form_success_url']."");
			} else { 
				if($_REQUEST['view'] == "contact") { 
					header("location: ".$_SERVER['PHP_SELF']."?view=contact&form=success");
				} else { 
					header("location: ".$_SERVER['PHP_SELF']."?form=success");
				}
			}
			exit();
		}
	} else {
		$html .= theForm($form);
	}
	return $html;
}

function theForm($form) {
	global $setup;

	$ver_phrase = genRandomString();
	$_SESSION['verifyPost'] = $ver_phrase;
	$_SESSION['fc'] = _captcha_text_color_;
	$_SESSION['bc'] = _captcha_background_color_;

	// $form['form_captcha'] = 1;

	$cols = $form['form_cols']; 
	if($cols < 1) { $cols = 1; } 
	$x = 1;
	if($form['form_max_width'] <=0) { 
		$form['form_max_width'] = 800;
	}

		

	$html .= '<div  style="width: 100%; max-width: '.$form['form_max_width'].'px; margin: auto;">
		<form method="post" name="contactform" id="contactform" action="index.php" onSubmit="return checkContactForm(); " >
		';
	$ffs = whileSQL("ms_form_fields", "*", "WHERE ff_id>'0' AND ff_form='".$form['form_id']."' ORDER BY ff_order ASC  ");
	while ($ff = mysqli_fetch_array($ffs)) {
		$fc++;
		if($fc == 1) { 
			$first_field = $form['form_id']."-".$ff['ff_id'];
		}

			$html .= '<div>';
			if($ff['ff_span_across'] == "1") { 
				$x = 2; 
				$html .='<div class="clear"></div>';
				$html .='<div style="width: 100%;" class="contactformfields">';
			} else { 
				if($cols == "2") {
					$html .='<div style="width: 50%; float: left;" class="contactformfields nofloatsmallleft">';
				} else { 
					$html .='<div style="width: auto;" class="contactformfields">';
				} 
			} 

			if($ff['ff_type'] == "checkbox") { 
				$html .= "<div class=\"pageContent\"><input type=\"checkbox\" name=\"".$form['form_id']."-".$ff['ff_id']."\" value=\"Selected\" class=\"checkbox\"> ".$ff['ff_name']."</div>";
				if(!empty($ff['ff_descr'])) { 
					$html .='<div class="pc">'.nl2br($ff['ff_descr']).'</div>';
				}

			} else { 

				$html .='<div class="pc">';
				if($ff['ff_required'] == "1") { $html .= "* "; }
				$html .= "".$ff['ff_name']."</div>";
				$html .= '<div class="pc" style="margin-right: 24px;">';
				$html .= showFormField($form,$ff);
				$html .= "</div>";
				if(!empty($ff['ff_descr'])) { 
					$html .='<div class="pc">'.nl2br($ff['ff_descr']).'</div>';
				}
			}
			$html .="</div></div>";
		if($x == $cols) {
			$html .='<div class="clear"></div>';
			$x = 0;
		}
		$x++;
	}
	$html .= '<div class="clear"></div>';

	if($form['form_captcha'] == "1") { 

		$fn = rand(1,4);
		$ln = rand(1,4);
		$total = $fn+$ln;
	
		$html .='<div class="pc">'.$fn.' + '.$ln.' = <input type="text" size="2" name="d_h"  id="d_h" value=""  class="required mathq center" data-total="'.$total.'"> '.$form['form_math_question'].'</div>';
	}

	$html .= "<div id=\"contactresponse\" class=\"hide pc\"  emptymessage=\"".htmlspecialchars($form['form_empty_fields'])."\" invalidemail=\"".htmlspecialchars($form['form_invalid_email'])."\"  mathincorrect=\"".htmlspecialchars($form['form_math_incorrect'])."\"></div>";
	$html .= "<div class=\"pageContent\">";
	$html .= '<input type="text" name="from_message_to" id="from_message_to" size="40" class="from_message_to" em="'.str_replace("@"," at ", $form['form_email_to']).'" >';
	$html .= '<input autofill="off" type="text" name="email" id="email" class="hide">'; 
	$html .= '<input autofill="off" type="text" name="name" id="name" class="hide">'; 
	$html .= "<input type=\"hidden\" name=\"check\" value=\"yes\">";
	if($_REQUEST['view'] == "contact") { 
	$html .= "<input type=\"hidden\" name=\"view\" value=\"contact\">";
	}
	$html .= "<input  type=\"submit\" name=\"submit\" class=\"submit\" id=\"submitButton\" value=\"".$form['form_button']."\">";
	$html .= "</div>";
	$html .= "</form></div>";
	$html .='<div class="clear"></div>';

	return $html;
}

?>
<?php
		function showFormField($form,$data) {
			if($data['ff_type'] == "text") { 
				$html .= "<input type=\"text\" name=\"".$form['form_id']."-".$data['ff_id']."\" id=\"".$form['form_id']."-".$data['ff_id']."\"  value=\"".$_REQUEST[$form['form_id']."-".$data['ff_id']]."\" style=\"width: 100%; max-width:".($data['ff_size'] * 10)."px;\" size=\"".$data['ff_size']."\" "; if($data['ff_required'] == "1") { $html .= "class=\"required\""; } $html .= ">";
			}
			if($data['ff_type'] == "date") { 
				$html .= "<input type=\"text\" name=\"".$form['form_id']."-".$data['ff_id']."\" id=\"".$form['form_id']."-".$data['ff_id']."\"  value=\"".$_REQUEST[$form['form_id']."-".$data['ff_id']]."\" style=\"width: 100%; max-width:".(24 * 10)."px;\" size=\"24\" class=\""; if($data['ff_required'] == "1") { $html .= "required"; } $html .= " datepicker\">";
			}
			if($data['ff_type'] == "email") { 
				$html .= "<input type=\"text\" name=\"".$form['form_id']."-".$data['ff_id']."\" id=\"".$form['form_id']."-".$data['ff_id']."\" value=\"".$_REQUEST[$form['form_id']."-".$data['ff_id']]."\" style=\"width: 100%; max-width:".($data['ff_size'] * 10)."px;\" size=\"".$data['ff_size']."\" "; $html .= 'class="email'; if($data['ff_required'] == "1") { $html .= " required"; } $html .= '">';
			}

			if($data['ff_type'] == "textarea") { 
				$html .= "<textarea name=\"".$form['form_id']."-".$data['ff_id']."\" id=\"".$form['form_id']."-".$data['ff_id']."\"  rows=\"".$data['ff_rows']."\" style=\"width: 100%; max-width:".($data['ff_cols'] * 10)."px;\" cols=\"".$data['ff_cols']."\" "; if($data['ff_required'] == "1") { $html .= "class=\"required\""; } $html .= ">".$_REQUEST[$form['form_id']."-".$data['ff_id']]."</textarea>";
			}
			if($data['ff_type'] == "dropdown") { 
				$html .= "<select  name=\"".$form['form_id']."-".$data['ff_id']."\" id=\"".$form['form_id']."-".$data['ff_id']."\" "; if($data['ff_required'] == "1") { $html .= "class=\"required\""; } $html .= ">\n";
				$html .= "<option value=\"\">".$data['ff_label']."</option>\n";
				$opts = explode("\r\n", $data['ff_opts']);
				foreach($opts AS $option) { 
					$option = str_replace("$","&#36;",$option);
					$html .= "<option value='$option'"; if($_REQUEST[$form['form_id']."-".$data['ff_id']] == $option) { $html .= "selected"; } $html .= ">$option</option>\n";
				}
				$html .= "</select>";
			}
			if($data['ff_type'] == "radio") { 
				$opts = explode("\r\n", $data['ff_opts']);
				foreach($opts AS $option) { 
					$html .= "<input type=\"radio\" class=\"checkbox\" name=\"".$form['form_id']."-".$data['ff_id']."\"  value='$option' "; if($_REQUEST[$form['form_id']."-".$data['ff_id']] == $option) { $html .= " checked"; } $html .= "> $option &nbsp;";
				}
			}
			if($data['ff_type'] == "checkbox") { 
				$html .= "<input type=\"checkbox\" name=\"".$form['form_id']."-".$data['ff_id']."\" value=\"checked\" class=\"checkbox\">";
			}



			return $html;
		}
		?>

