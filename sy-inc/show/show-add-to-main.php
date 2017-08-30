<?php /* SWEETNESS */

if($css['sweetness'] == "1") { 
$dshow = doSQL("ms_show", "*", "WHERE default_feat='1' ");
?>
<script>
var catphotoratio = '<?php print $catphotoratio;?>';
var mainphotoratio = '<?php print $mainphotoratio;?>';
var catminwidth = '<?php print $catminwidth; ?>';
var catmaxwidth = '<?php print $catmaxwidth; ?>';
var catmaxrow = '<?php print $catmaxrow;?>';
var initialopacity ='.8';
var hoveropacity = '1';
var mslide = 1;
var main_full_screen = '1';
var gettingfeature;
var featid;
var showminimenu = '<?php print $dshow['show_mini_menu'];?>';
var logoplacement = '<?php print $dshow['logo_placement'];?>';
var titleplacement = '<?php print $dshow['title_placement'];?>';
var navplacement = '<?php print $dshow['nav_placement'];?>';

</script>

<?php require $setup['path']."/".$setup['inc_folder']."/show/show-functions.php"; ?>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/show/show-js.js?".MD5($site_setup['sytist_version'])."" ?>"></script>
<link rel="stylesheet" href="<?php tempFolder();?>/<?php print $setup['inc_folder'];?>/show/show-css.php?<?php print MD5($site_setup['sytist_version']); ?>">
<?php } ?>


<?php /* ADD ABOUT HEADERANDMENU */ ?>
<?php if($css['sweetness'] == "1") { include $setup['path']."/sy-inc/show/show-side-menu.php"; } ?>

Add to page_home.php
<?php 
if(!empty($_REQUEST['sweetness'])) { 
	$show = doSQL("ms_show", "*", "WHERE MD5(show_id)='".$_REQUEST['sweetness']."'  ");

} else { 
	$show = doSQL("ms_show", "*", "WHERE feat_page_id='".$date['date_id']."' AND enabled='1' AND default_feat<='0' ");
}
if($css['sweetness'] == "1") { 
	require $setup['path']."/".$setup['inc_folder']."/show/show.php";
} else {
?>

Add to main index inclide: 
<?php 
	if($date['date_id'] > 0) { 
	$show = doSQL("ms_show", "*", "WHERE feat_page_id='".$date['date_id']."' AND enabled='1' ");
}
if(($bcat['cat_id'] > 0) && ($date['date_id'] <=0)==true)  { 
	$show = doSQL("ms_show", "*", "WHERE feat_cat_id='".$bcat['cat_id']."' AND enabled='1' ");
}


?>

Add under headerAndMenu shit
			<?php if($css['sweetness'] == "1") { ?>
		<div class="pc center sfoot">
		<?php 
		$footer = $site_setup['footer'];
		$footer = str_replace("[YEAR]", date('Y'), $footer);
		$footer = preg_replace('#\[SOCIAL_LINKS]#i', showSocialLinks(),$footer);  
		$footer = preg_replace('#\[MENU_LINKS]#i', "",$footer);  
		$footer = str_replace("[SITE_NAME]", "<a href=\"".$setup['temp_url_folder']."/\">".$site_setup['website_title']."</a>&nbsp; ", $footer);

		print $footer;
		?>
		</div>
		<div class="clear" id="tmmb"></div>

		 <?php  } ?>
