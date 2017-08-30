<script language="JavaScript" type="text/javascript">
<!--
function checkPreviewUpload(form) {
	  if (document.getElementById("upitprev").image.value == "") {
//			alert( "<?php  print $opt['opt_name'];?> IS BLANK" );
			document.getElementById("upitprev_error").style.display = 'inline';
      document.getElementById("upitprev_error").innerHTML = "<div class=error> Please select a file to upload </div>"
			return false ;
		}
    Form=document.upitprev; 
	Form.submission.disabled = true;
	Form.submission.value = 'Uploading....';
	Form.submit();  

  return true ;
}
//-->
</script>

		<form name="upitprev" id="upitprev"  method="POST" action="index.php" enctype="multipart/form-data" >

	<div id="roundedForm">
		<div class="label">Select theme file to import from your computer.</div>
		
<?php 
$line_count = 3;

print "<div class=\"row\">";
print "<div id=\"upitprev_error\" style=\"display: none;\">Error ....</div>";
print "<div><input type=\"file\" name=\"image\" size=50 id=\"image\" ></div>";
?>

</div>
<div class="row">
<?php  print "<input type=\"hidden\" name=\"do\" value=\"look\">"; ?>
<?php  print "<input type=\"hidden\" name=\"view\" value=\"css\">"; ?>
<?php  print "<input type=\"hidden\" name=\"action\" value=\"uploadTheme\">"; ?>

<input name="submission" id="submission"  type="submit" onClick="return  checkPreviewUpload(this)" class="submit" value="Upload Theme File">
</div>
</form>
</div>