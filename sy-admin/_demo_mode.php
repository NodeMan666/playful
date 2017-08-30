<?php
if($_REQUEST['subdo'] == "deleteCss") {
	$demo = true;
}

if($_REQUEST['subdo'] == "updateSettings") {
	$demo = true;
}
if($_REQUEST['subdo'] == "addPage") {
	$demo = true;
}
if($_REQUEST['action'] == "disableMiniLinks") {
	$demo = true;
}
if($_REQUEST['action'] == "deleteAdmin") {
	$demo = true;
}

if(!empty($_REQUEST['deletePromo'])) {
	$demo = true;
}
if($_REQUEST['subdo'] == "updateNewAccounts") { 
	$demo = true;

}
if($_REQUEST['subdo'] == "disableallcountries") { 
	$demo = true;

}

if($_REQUEST['subdo'] == "removeAllPhotos") {
	$demo = true;
}
if(($_REQUEST['action'] == "language")AND($_REQUEST['submitit'] == "yup")==true) {
	$demo = true;
}

if(($_REQUEST['action'] == "layout")AND($_REQUEST['submitit'] == "yup")==true) {
	$demo = true;
}
if(($_REQUEST['action'] == "captcha")AND($_REQUEST['submitit'] == "yup")==true) {
	$demo = true;
}

if(($_REQUEST['do'] == "comments")AND($_REQUEST['submitit'] == "yup")==true) {
	$demo = true;
}

if($_REQUEST['action'] == "newFolder") {
	$demo = true;
}
if($_REQUEST['action'] == "deleteExpense") {
	$demo = true;
}

if($_REQUEST['subdo'] == "setCss") {
	$demo = true;
}
if($_REQUEST['subdo'] == "duplicateCss") {
	$demo = true;
}

if(($_REQUEST['view'] == "css2")AND($_REQUEST['css_id']< '51')==true) {
	if(!empty($_REQUEST['submitit'])) {
		$demo = true;
	}
}
if(($_REQUEST['view'] == "additionalCSS")AND($_REQUEST['css_id']< '51')==true) {
	if(!empty($_REQUEST['submitit'])) {
		$demo = true;
	}
}
if(($_REQUEST['do'] == "settings")AND(!empty($_REQUEST['submitit']))==true) {
	$demo = true;
}
if(($_REQUEST['do'] == "look")AND(!empty($_REQUEST['submitit']))==true) {
	$demo = true;
}
if(($_REQUEST['action'] == "settings")AND(!empty($_REQUEST['submitit']))==true) {
	$demo = true;
}
if(($_REQUEST['action'] == "text")AND(!empty($_REQUEST['submitit']))==true) {
	$demo = true;
}

if($_REQUEST['subdo'] == "testEmail") {
	$demo = true;
}
if($_REQUEST['view'] == "language") {
	$demo = true;
}
if($_REQUEST['action'] == "createPhotoBlog") {
	$demo = true;
}

if($_REQUEST['subdo'] == "deleteLink") {
	$demo = true;
}
if($_REQUEST['subdo'] == "linkOrder") {
	$demo = true;
}

if(!empty($_REQUEST['dupLanguage'])) {
	$demo = true;
}
if($_REQUEST['action'] == "removefromlist") {
	$demo = true;
}
if($_REQUEST['action'] == "deletePage") {
	$demo = true;
}

if($_REQUEST['action'] == "makeAlbumCover") {
	$demo = true;
}

if($_REQUEST['action'] == "updatePhotoOrder") {
	$demo = true;
}
if($_REQUEST['action'] == "photoBlogSettings") {
	$demo = true;
}


if($_REQUEST['action'] == "updateColors") {
	$demo = true;
}

if($_REQUEST['action'] == "updateAdminNote") {
	$demo = true;
}


if(($_REQUEST['action'] == "editPage") AND (!empty($_REQUEST['submitit']))==true){
	$demo = true;
}
if($_REQUEST['subdo'] == "moveItem") {
	$demo = true;
}
if($_REQUEST['subdo'] == "uploadFile") {
	$demo = true;
}

if($_REQUEST['subdo'] == "useSettings") {
	$demo = true;
}
if($_REQUEST['subdo'] == "featSettings") {
	$demo = true;
}
if($_REQUEST['subdo'] == "saveFrame") {
	$demo = true;
}

if($_REQUEST['subdo'] == "removeTrack") {
	$demo = true;
}
if($_REQUEST['subdo'] == "addTrack") {
	$demo = true;
}
if($_REQUEST['subdo'] == "updateOverlay") {
	$demo = true;
}


if($_REQUEST['deleteBg'] >0) {
	$demo = true;
}


if($_REQUEST['action'] == "deleteForm") {
	$demo = true;
}
if($_REQUEST['subdo'] == "orderFields") {
	$demo = true;
}
if($_REQUEST['saveform'] == "yes") {
	$demo = true;
}
if($_REQUEST['savemainform'] == "yes") {
	$demo = true;
}
if($_REQUEST['subdo'] == "deleteFF") {
	$demo = true;
}
if(!empty($_REQUEST['deleteDate'])) {
	$demo = true;
}
if(($_REQUEST['action'] == "addDate")AND(!empty($_REQUEST['submitit']))==true) {
		$demo = true;
}
if($_REQUEST['action'] == "uploadTheme") {
	$demo = true;
}

if($_REQUEST['action'] == "deleteGallery") {
	$demo = true;
}
if($_REQUEST['action'] == "deletePic") {
	$demo = true;
}

if($_REQUEST['action'] == "deleteTrack") {
	$demo = true;
}
if($_REQUEST['check'] == "submit") {
	$demo = true;
}
if($_REQUEST['action'] == "batchArchive") {
	$demo = true;
}
if($_REQUEST['action'] == "batchTrash") {
	$demo = true;
}
if($_REQUEST['action'] == "trashOrder") {
	$demo = true;
}
if($_REQUEST['action'] == "archiveOrder") {
	$demo = true;
}
if($_REQUEST['action'] == "deleteBatch") {
	$demo = true;
}
if($_REQUEST['action'] == "password") {
	$demo = true;
}
if($_REQUEST['action'] == "upload") {
	$demo = true;
}
if($_REQUEST['action'] == "deleteFile") {
	$demo = true;
}
if($_REQUEST['action'] == "uploadGraphic") {
	$demo = true;
}

if($_REQUEST['createit'] == "yes") {
	$demo = true;
}
if(!empty($_REQUEST['setAsTheme'])) {
	$demo = true;
}
if($_REQUEST['subdo'] == "deleteLink") {
	$demo = true;
}
if($_REQUEST['subdo'] == "orderLinks") {
	$demo = true;
}
if($_REQUEST['savelink'] == "yes") {
	$demo = true;
}
if($_REQUEST['subdo'] == "addNewLink") {
	$demo = true;
}
if(!empty($_REQUEST['deleteFile'])) {
	$demo = true;
}

if(!empty($_REQUEST['deleteCategoryThumb'])) { 
	$demo = true;
}

if(!empty($_REQUEST['deleteShippingMethod'])) { 
	$demo = true;
}

if($_POST['submitit']=="yes") { 
	$demo = true;
}
if($_REQUEST['action']=="updateEditor") { 
	$demo = true;
}
if($_REQUEST['action']=="save") { 
	$demo = true;
}
if($_REQUEST['action']=="duplicate") { 
	$demo = true;
}
if(!empty($_REQUEST['deleteBillboard'])) { 
	$demo = true;
}
if($_REQUEST['subdo'] == "updateCurrency") { 
	$demo = true;
}
if($_REQUEST['send'] == "yes") { 
	$demo = true;
}
if($_REQUEST['update'] == "yes") { 
	$demo = true;
}
if($_REQUEST['subdo'] == "savefonts") { 
	$demo = true;
}

if($_REQUEST['save'] == "yes") { 
	$demo = true;
}
if($_REQUEST['action']=="delete") { 
	$demo = true;
}
if($_REQUEST['action']=="deactivate") { 
	$demo = true;
}
if($_REQUEST['action']=="removeaccess") { 
	$demo = true;
}

if($_REQUEST['action']=="saveproduct") { 
	$demo = true;
}

if($_REQUEST['action']=="deletePriceList") { 
	$demo = true;
}
if(!empty($_REQUEST['deleteProd'])) { 
	$demo = true;
}
if($_REQUEST['action']=="deletePackage") { 
	$demo = true;
}
if($_REQUEST['action']=="duplicatePackage") { 
	$demo = true;
}
if($_REQUEST['action']=="addproduct") { 
	$demo = true;
}
if($_REQUEST['action']=="deletePackageProd") { 
	$demo = true;
}
if($_REQUEST['action']=="updatepackage") { 
	$demo = true;
}
if($_REQUEST['action']=="newgroup") { 
	$demo = true;
}
if($_REQUEST['action']=="editgroup") { 
	$demo = true;
}
if($_REQUEST['action']=="deleteGroup") { 
	$demo = true;
}
if($_REQUEST['action']=="addtolist") { 
	$demo = true;
}
if($_REQUEST['action']=="addall") { 
	$demo = true;
}

if($demo == true) { ?>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
	<div style="background-color: #efefef; color: #000000; border: solid 1px #b4b4b4; padding: 12px; margin: 12px; text-align: center; width: 50%; margin: auto; box-shadow: 0px 0px 12px #949494; "><h1>DEMO MODE</h1><br>Sorry, but hat function is disabled for the demo. 
<a href="" onClick="history.back(); return false;">Click here to return to the previous page</a>
</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
	<?php include "footer.php"; 
	die();
	?>

<?php } ?>