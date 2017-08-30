<div id="pageTitle"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?> Themes</div>

<?php 


if($_REQUEST['fromlist'] == "1") { 
	$css = doSQL("ms_css", "*", "WHERE css_id='".$_REQUEST['css_id']."' ");

	?>
<div id="bluenotice"><?php print $css['css_name'];?> is now set as your theme. <a href="theme-edit.php?css_id=<?php print $css['css_id'];?>">Edit my theme</a> or 

<?php if($site_setup['index_page'] == "indexnew.php") { ?><a href="<?php tempFolder(); ?>/indexnew.php" target="_blank"><?php } else { ?><a href="<?php tempFolder(); ?>/" target="_blank"><?php } ?>view my website</a>

</div>

<?php } ?>


<?php
	if($_REQUEST['action'] == "uploadTheme") {
		include "upload.theme.php";

	}



	if($_REQUEST['subdo'] == "duplicateCss") {
	$css = doSQL("ms_css", "*", "WHERE css_id='".$_REQUEST['css_id']."' " );
	foreach($css AS $id => $val) {
		if((!is_numeric($id)) AND($id!=="css_id")AND($id!=="thumb")AND($id!=="codeid")AND($id!=="css_order")==true) {
			if($ct>0) {
				$qry .= ", ";
			}
			if($id == "css_name") {
				$qry.="$id='$val - COPY' ";
			} else {
				$qry.="$id='".addslashes(stripslashes($val))."' ";
			}
			$ct++;
		}


	}
	$newcss = insertSQL("ms_css", " $qry ");
	$css2 = doSQL("ms_css2", "*", "WHERE parent_css_id='".$css['css_id']."' ");
		$qry2 = "parent_css_id='".$newcss."'";

	foreach($css2 AS $id => $val) {
		if((!is_numeric($id)) AND($id!=="css2_id")AND($id!=="parent_css_id")==true) {
			$qry2.=", $id='".addslashes(stripslashes($val))."' ";
			$ct++;
		}
	}
	$newcss2 = insertSQL("ms_css2", " $qry2");

$fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$css['css_id']."' ");
while($font = mysqli_fetch_array($fonts)) { 
	insertSQL("ms_google_fonts", "font='".$font['font']."', theme='".$newcss."' ");
}



	$_SESSION['sm'] = "New theme created";
	session_write_close();
	header("location: theme-edit.php?css_id=$newcss");
	exit();
	}
	if($_REQUEST['subdo'] == "setCss") {
		updateSQL("ms_settings", "css='".$_REQUEST['css_id']."' ");
		$_SESSION['sm'] = "This is now your current theme.";
		unset($_SESSION['previewTheme']);
		session_write_close();
		if($_REQUEST['fromlist'] == "1") { 
			header("location: index.php?do=look&fromlist=1&css_id=".$_REQUEST['css_id']."");
		} else { 
			header("location: theme-edit.php?css_id=".$_REQUEST['css_id']."");
		}
		exit();

	}
	if($_REQUEST['subdo'] == "deleteCss") {
		$css = doSQL("ms_css", "*", "WHERE css_id='".$_REQUEST['css_id']."' ");
		if(!empty($css['css_id'])) {
			deleteSQL("ms_css", "WHERE css_id='".$css['css_id']."' ", "1");
			deleteSQL("ms_css2", "WHERE parent_css_id='".$css['css_id']."' ", "1");
			deleteSQL2("ms_google_fonts", "WHERE theme='".$css['css_id']."' ");
		}
		$_SESSION['sm'] = "Theme ".$css['css_name']." deleted";
		session_write_close();
		header("location: index.php?do=look&view=css");
		exit();


	}





	// This determines the size of the columns 
	$cw1 = "10%";
	$cw2 = "30%";
	$cw3 = "30%";
	$cw4 = "30%";
	$cw5 = "17%";
	$cw6 = "30%";
	$cw7 = "30%";
	?>


<div>&nbsp;</div>
<div id="themeContainer">
<?php 
$data = doSQL("ms_css", "*", "WHERE css_id='".$site_setup['css']."' ORDER BY css_order ASC  ");
?>
<div class="buttonsgray">
	<ul>

	<li><a href="index.php?do=look&view=css&site_type=standard" <?php if(((($css['site_type'] == 0)AND(empty($_REQUEST['site_type'])))OR($_REQUEST['site_type']=="standard"))AND(empty($_REQUEST['action']))==true) { ?> class="on"<?php } ?>>THEMES</a></li>
	<?php if($site_setup['css'] > 0) { ?>
	<li><a href="theme-edit.php?css_id=<?php print $data['css_id']; ?>"><?php print ai_edit;?>  EDIT MY THEME</a></li>
	<?php } ?>
	<li><a href="index.php?do=look&view=css&action=import" <?php if($_REQUEST['action'] == "import") { ?>class="on" <?php } ?>>IMPORT THEME</a></li>

	<div class="cssClear"></div>
	</ul>
</div>
<div id="roundedFormContain">
<?php if($_REQUEST['action'] == "import") { 
	include "look.import.theme.php";

	} else { ?>

<div id="themeContainer">
<script>
$(document).ready(function(){

	$(".screenshot").hover(
	  function () {
		$(this).css({"height":"auto", "position":"absolute", "box-shadow":"0px 0px 8px rgba(0,0,0, .5)"});
	  },
	  function () {
		$(this).css({"height":"60px", "position":"static", "box-shadow":"none"});
	  }
	);


});
</script>
<?php  
$datas = whileSQL("ms_css", "*", "$site_type ORDER BY css_name ASC   ");
$tftotal = mysqli_num_rows($datas);
	if(mysqli_num_rows($datas)>0) { 
		?>
	<div class="pageContent">Themes are a combination of colors, fonts & menu placement for the most part and these are easily edited in the theme editor. Below are the themes available. Click on the preview link to preview the theme on the website. 
	<br><br>Once you select a theme to use, you can modify it by clicking on the edit link. You can change & edit your theme any time.</div>
	<div>&nbsp;</div>
	<?php
	while ($data = mysqli_fetch_array($datas)) {
	?>
	<div class="theme" <?php if($mm%2) { print "style=\"width: 49%; float: right;\""; } else { print "style=\"width: 49%; float: left;\""; } ?>>
	<div id="roundedSide">
	<div class="pc center">
		<?php 
	if(!empty($data['theme_screen'])) { 
		if(file_exists($setup['path']."/".$setup['manage_folder']."/graphics/themes/".$data['theme_screen'])) { 
			print "<a href=\"theme-edit.php?css_id=".$data['css_id']."\"><img src=\"graphics/themes/".$data['theme_screen']."\" class=\"thumbnail\" style=\"width: 100%; height: auto; float: left; margin-right: 8px; border: solid 1px #444444;\" border=\"0\"></a>";
		} else { 
			print "<h2>".$data['css_name']."</h2>";
		}
	}
	?>
	</div>
	<div class="pc textright">
	<a href="theme-edit.php?css_id=<?php print $data['css_id'];?>"><?php print $data['css_name'];?></a>
	</div>
	<div class="pc center" id="themeselectmenu">
	<a href="theme-edit.php?css_id=<?php print $data['css_id'];?>"><?php print ai_edit;?> edit</a> &nbsp; 
	<a href="<?php tempFolder();?>/<?php print $site_setup['index_page'];?>?previewTheme=<?php print $data['css_id']; ?>" target="_blank"><?php print ai_web;?>  preview</a>  &nbsp;  
	<a href="index.php?do=look&view=css&subdo=duplicateCss&css_id=<?php print $data['css_id']; ?>"  onClick="return confirm('Are you sure you want to duplicate the theme  <?php print strip_tags($data['css_name']);?>  and create a new one ? ');"><?php print ai_copy;?> duplicate</a>  &nbsp;  
	<a href="export.theme.php?css_id=<?php print $data['css_id']; ?>" ><?php print ai_export;?> export</a>   &nbsp;  
	<?php if($site_setup['css'] !== $data['css_id']) { ?> 
	<a href="index.php?do=look&view=css&subdo=setCss&css_id=<?php print $data['css_id']; ?>&fromlist=1"  onClick="return confirm('Are you sure you want to set your  theme to <?php print strip_tags($data['css_name']);?> ? ');"><nobr><?php print ai_add;?> make this my theme</nobr></a> 
	<?php } else { print "<span class=\"bold\">Your current theme</span>"; } ?>
</div>
			<div class="clear"></div>

		<?php if(!empty($data['descr'])) { print "<div class=\"pageContent\">".nl2br($data['descr'])."</div>"; } ?>	

	</div>
	<div>&nbsp;</div></div>
	<?php 	 if($mm%2) { print "<div class=\"cssClear\"></div>"; } ?>

	<?php 			$mm++;
  	if($mm  == $tftotal) { 
		print "<div class=\"cssClear\"></div>";
	}
} ?>

	</div>
</div>


<?php } ?>


<div class="cssClear"></div>
		<div>&nbsp;</div>

<?php } ?>

