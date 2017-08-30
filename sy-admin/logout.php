<?php  
require("../sy-config.php");
session_start(); 
session_unset();
session_write_close();
sleep(1);
header ("location: index.php");
exit();
?>
