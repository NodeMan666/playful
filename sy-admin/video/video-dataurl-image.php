<?php
$path = "../../";
require "../w-header.php"; 

$vid = doSQL("ms_videos", "*", "WHERE vid_id='".$_REQUEST['vid']."' ");
$filename = $vid['vid_name']."-poster-".uniqid().".jpg";
$img = $_POST['img'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$file = $setup['path']."/sy-photos/".$filename;
$success = file_put_contents($file, $data);
print $success ? $file : 'Unable to save the file.';

updateSQL("ms_videos", "vid_poster='".addslashes(stripslashes(trim($filename)))."' WHERE vid_id='".$vid['vid_id']."' ");

?>