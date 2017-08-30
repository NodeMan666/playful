<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?><?php 
$rpc="t69"; 
$reg = doSQL("ms_register", "*, date_format(DATE_ADD(reg_date, INTERVAL 2 HOUR), '%M %e, %Y ')  AS reg_date", ""); if((empty($reg['reg_key']))&&(($_REQUEST['do']!=="register"))==true) { session_write_close(); header("location: index.php?do=register&md=1"); die(); } 
?>