<?php
include_once($setup['path']."/sy-inc/facebook/inc/facebook.php"); //include facebook SDK
######### Facebook API Configuration ##########
$appId = $fb['facebook_app_id']; //Facebook App ID
$appSecret = $fb['facebook_app_secret']; // Facebook App Secret
$homeurl = $setup['url'].$setup['temp_url_folder'].'/sy-inc/facebook/index.php';  //return to home
$fbPermissions = 'email';  //Required facebook permissions
//Call Facebook API
$facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $appSecret

));
$fbuser = $facebook->getUser();
?>