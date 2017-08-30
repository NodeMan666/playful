<?php
session_start();
header("Cache-control: private"); 
date_default_timezone_set('America/Los_Angeles');
$gosite = substr(strstr($_SERVER['REQUEST_URI'], "goto="), 5);
print "<html><head>";
print "<title>One moment please</title>";
print "</head>";
print "<link rel=\"stylesheet\" href=\"css/white.css\" type=\"text/css\">";

print "<body>";
?>
<table align=center cellpadding=8 cellspacing=0 border=0 width=400><tr><td width=100%><br><br>
Preparing report - please wait<span id="ellipsis"></span>
</td></tr></table>
<script language="JavaScript">
function animate()
{
	var ellipsis = document.all['ellipsis'];
        ellipsis.innerHTML = ellipsis.innerHTML + '.';
        if (ellipsis.innerHTML == '....') ellipsis.innerHTML = '';
        setTimeout("animate()", 200);
}
if (document.all) setTimeout("animate()", 200);
location = '<?php print "$gosite"; ?>';
</script>
<?php
//print "<meta http-equiv=\"Refresh\" content=\".5;url=$gosite\">";
// print "<B>One moment please ..... </B><br>Report is building.</center>";

print "</body></html>";
?>