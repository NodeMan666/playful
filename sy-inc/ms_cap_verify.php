<?php
include "../sy-config.php";
session_start();
srand ((double) microtime( )*1000000);
$verify_background = $_SESSION['bc'];
$verify_text = $_SESSION['fc'];
$code= $_SESSION['verifyPost'];
unset($_SESSION['verifyPost']);
unset($_SESSION['bc']);
unset($_SESSION['fc']);

// Convert hex color codes to R, G, B array
function ddfm_hex_to_rgb($h) {

	if (strpos($h, '#') === 0) { 
		$h = substr($h, 1);
	}
	$color = array();
	if (strlen($h) == 6) {
		$color[] = (int)hexdec(substr($h, 0, 2));
		$color[] = (int)hexdec(substr($h, 2, 2));
		$color[] = (int)hexdec(substr($h, 4, 2));
	} else if (strlen($h) == 3) {
		$color[] = (int)hexdec(substr($h, 0, 1) . substr($h, 0, 1));
		$color[] = (int)hexdec(substr($h, 1, 1) . substr($h, 1, 1));
		$color[] = (int)hexdec(substr($h, 2, 1) . substr($h, 2, 1));
	}
	return $color;
}


// Choose image type
$type = '';
if (function_exists("imagegif")) {
	$type = 'gif';
} else if (function_exists("imagepng")) {
	$type = 'png';
} else if (function_exists("imagejpeg")) {
	$type = 'jpeg';
}

// Generate image
header("Content-type: image/" . $type);
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 1 Jan 1990 01:00:00 GMT"); // Date in the past
$image = imagecreate(100, 20);

list($br, $bg, $bb) = ddfm_hex_to_rgb($verify_background);
list($rr, $rg, $rb) = ddfm_hex_to_rgb($verify_text);

$background_color = imagecolorallocate ($image, $br, $bg, $bb);
$text_color = imagecolorallocate($image, $rr, $rg, $rb);

imagestring($image, 5, 8, 2, $code, $text_color);

switch ($type) {
	case 'gif': imagegif($image); break;
	case 'png': imagepng($image); break;
	case 'jpeg': imagejpeg($image); break;
}		

imagedestroy($image);

?>
