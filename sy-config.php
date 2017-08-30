<?php
$setup['path']= dirname(__FILE__);
$setup['url'] = "http://".$_SERVER['HTTP_HOST']."";

$setup['pc_db_location'] = "localhost";
$setup['pc_db'] = "dbjrange_sytist";
$setup['pc_db_user'] = "dbjrange_sytist";
$setup['pc_db_pass'] = 'Tim2c0Ol!';
$setup['manage_folder'] = "sy-admin";
$setup['inc_folder'] = "sy-inc";
$setup['graphics_folder'] = "sy-graphics";
$setup['misc_folder'] = "sy-misc";
$setup['photos_upload_folder'] = "sy-photos";
$setup['layouts_folder'] = "sy-layouts";
$setup['tags_folder'] = "tags";
$setup['downloads_folder'] = "sy-downloads";
error_reporting(E_ALL ^ E_NOTICE);
ini_set('memory_limit', '512M');
ini_set('upload_max_filesize', '30M');
ini_set('post_max_size', '30M');
ini_set('log_errors', 'on');
ini_set('error_log', $setup['path']."/errors.txt");
ini_set('session.gc_maxlifetime',   3600);
ini_set('session.save_path', $setup['path']."/sy-phpsessions");


// Below is used when installing on a temporary URL. If you have your domain pointing to this hosting account now and no longer using a temporary URL, remove the line bellow

$setup['temp_url_folder'] = "/clients";

// If you need to make adjustments to this file do so below this line before the closing PHP tag. 


?>