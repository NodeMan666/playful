<?php
function mailchimpsubscribe($email,$first_name,$last_name) { 
	global $setup;
	$em_settings = doSQL("ms_email_list_settings", "*", "  ");
	if(!empty($em_settings['mailchimp_key'])) { 
		include $setup['path']."/sy-inc/MailChimp.php";
		$MailChimp = new \Drewm\MailChimp($em_settings['mailchimp_key']);
		if($em_settings['mailchimp_double_optin'] == "1") { 
			$dop = true;
		} else { 
			$dop = false;
		}
		if(!empty($em_settings['mailchimp_list_id'])) { 
			$result = $MailChimp->call('lists/subscribe', array(
				'id'                => $em_settings['mailchimp_list_id'],
				'email'             => array('email'=> $email),
				'merge_vars'        => array('FNAME'=>stripslashes($first_name), 'LNAME'=>stripslashes($last_name)),
				'double_optin'      => $dop,
				'update_existing'   => true,
				'replace_interests' => false,
				'send_welcome'      => false,
			));
			if($result[status] == "error") { 
				// print "An errror has occurred"; print "<pre>"; print_r($result); print "</pre>";
			} else { 
				return $result['euid'];
				// print "<li>".$result['euid'];
				// print "<pre>"; print_r($result); print "</pre>";
			}
		}
	}
}

function mailchimpunsubscribe($email,$first_name,$last_name) { 
	global $setup;
	$em_settings = doSQL("ms_email_list_settings", "*", "  ");
	if(!empty($em_settings['mailchimp_key'])) { 
		include $setup['path']."/sy-inc/MailChimp.php";
		$MailChimp = new \Drewm\MailChimp($em_settings['mailchimp_key']);
		if(!empty($em_settings['mailchimp_list_id'])) { 
			$result = $MailChimp->call('lists/unsubscribe', array(
				'id'                => $em_settings['mailchimp_list_id'],
				'email'             => array('email'=> $email),
				'send_goodbye'   => false,
			));
			if($result[status] == "error") { 
				// print "An errror has occurred"; print "<pre>"; print_r($result); print "</pre>";
			} else { 
				return $result['euid'];
				// print "<li>".$result['euid'];
				// print "<pre>"; print_r($result); print "</pre>";
			}
		}
	}
}

function mailchimpdelete($email,$first_name,$last_name) { 
	global $setup;
	$em_settings = doSQL("ms_email_list_settings", "*", "  ");
	if(!empty($em_settings['mailchimp_key'])) { 
		include $setup['path']."/sy-inc/MailChimp.php";
		$MailChimp = new \Drewm\MailChimp($em_settings['mailchimp_key']);
		if(!empty($em_settings['mailchimp_list_id'])) { 
			$result = $MailChimp->call('lists/unsubscribe', array(
				'id'                => $em_settings['mailchimp_list_id'],
				'email'             => array('email'=> $email),
				'send_goodbye'   => false,
				'delete_member'   => true,
			));
			if($result[status] == "error") { 
				// print "An errror has occurred"; print "<pre>"; print_r($result); print "</pre>";
			} else { 
				return $result['euid'];
				// print "<li>".$result['euid'];
				// print "<pre>"; print_r($result); print "</pre>";
			}
		}
	}
}

?>