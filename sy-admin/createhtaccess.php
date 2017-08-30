<?php
$domain = str_replace("www.","",$_SERVER['HTTP_HOST']);
	
if(file_exists($setup['path']."/.htaccess")) { 
	rename($setup['path']."/.htaccess", $setup['path']."/.htaccess-".date('ymdhis')."");
}

$in.= "ErrorDocument 404 /404.php\r\n";
$in .= "RewriteEngine On\r\n";
// $in .= "Options +FollowSymLinks\r\n";
$in .= "RewriteCond %{HTTP_HOST} ^(".$domain.")$ [NC]\r\n";
$in .= "RewriteRule ^(.*)$ http://www.%1/$1 [R=301,L]\r\n";
$in .= '<ifModule mod_deflate.c>  
AddOutputFilterByType DEFLATE text/html text/plain text/xml application/xml application/xhtml+xml text/css text/javascript application/javascript application/x-javascript  
</ifModule>  

<FilesMatch "\.(jpg|jpeg|png|gif|swf|js|css)$">
Header set Cache-Control "max-age=604800, public"
</FilesMatch>';


$fp = fopen("".$setup['path']."/.htaccess", "w");
fputs($fp, "$in\n");
fclose($fp);
?>