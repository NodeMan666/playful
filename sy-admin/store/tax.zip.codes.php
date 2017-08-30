<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}
adminsessionCheck();
if($_REQUEST['upload'] == "yes") {
	include "tax.upload.php";
	exit();
}
?>
<script language="JavaScript" type="text/javascript">
<!--
function checkPreviewUpload(form) {
	  if (document.getElementById("upitprev").image.value == "") {
//			alert( "<?php  print $opt['opt_name'];?> IS BLANK" );
			document.getElementById("upitprev_error").style.display = 'inline';
			javascript:ajaxpage('message.page.php?ck_secure=<?php  print $_SESSION['secure_page'];?>&message=Please select a file ', 'upitprev_error');
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
<div id="info">
This section allows you to charge tax by zip codes. 
<br>
1) You need a spreadsheet with at least the zip codes and tax rate. All possible options are Zip Code, Tax Rate, City, and State, but only zip code and tax rate are used.
<br>
2) Each column must be labeled (first row of the spreadsheet) as: zip, tax, city, state. It doesn’t matter what order or if there are other columns.
<br>
3) Save your spread sheet as a CSV file.
<br>
4) Click the browse button to find the spreadsheet on your computer then click the upload CSV file.
<br>
If the zip code does not exist in the database, it will add it. If it does exist, then it will update it.
<br>
If a customer has a zip code that doesn’t exist in the database, then it will use the tax percentage you have set for your <a href="index.php?do=settings&action=tax">STATE in the settings</a>.

</div>

<form name="upitprev" id="upitprev"  method="POST" action="index.php" enctype="multipart/form-data" >

<table align=center cellpadding=0 cellspacing=0 border=0 width=100% class=listbox>
<?php 
$line_count = 3;

print "<tr><td class=tdlines >";
print "<div id=\"upitprev_error\" class=error style=\"display: none;\">Error ....</div>";
print "<div><input type=\"file\" name=\"image\" size=20 id=\"image\" ></div>";
print "</td>";
?>
<td align=center  class=tdlines>
<?php  print "<input type=\"hidden\" name=\"do\" value=\"settings\">"; ?>
<?php  print "<input type=\"hidden\" name=\"action\" value=\"tax\">"; ?>
<?php  print "<input type=\"hidden\" name=\"type\" value=\"zip\">"; ?>
<input type="hidden" name="upload" value="yes">
<input type="button" name="submission" id="submission"  value="Upload CSV file" class="submit" onClick="return  checkPreviewUpload(this)">

</td></tr></table></form>
<br>
<?php 
$list['do'] = "photocart";
// $list['title'] = "Email log";
$list['description'] = "";
$list['bottom_notes'] = "";
$list['table'] = "ms_tax_zips";

$list['select'] = "*";
$list['where'] = " WHERE id>'0'";


$list['orderBy'] = "zip";
$list['acdc'] = "ASC";
$list['limit'] = "50";
$list['id'] = "id";
$list['order_form'] = "0";
$list['order_action'] = "";
$list['order_select_option'] = "".faq_update_order."";
$list['form_submit_button'] = "Go";
$list['checkbox'] = "0";
$list['checkbox_actions'] = "deleteFAQs|".faq_delete_drop.",swapFAQStatuses|".faq_swap_drop."";
// $list['links'] = faqMenu();

$listCols = array(

	array("name" => "Zip", "var_field" => "zip", "width" => "25%", "var_status" => "0"),
	array("name" => "Tax Rateer", "var_field" => "tax", "width" => "25%", "var_status" => "0"),
	array("name" => "City", "var_field" => "city", "width" => "25%", "var_status" => "0"),
	array("name" => "State", "var_field" => "state", "width" => "25%", "var_status" => "0"),
);

$NPvars = array("do=photocart", "settings=tax", "type=zip");

print listResults($list,$listCols,$NPvars);

?>