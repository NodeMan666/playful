<?php
function addEditor($field, $num, $height, $header) { 
	global $setup, $site_setup,$email_style,$date,$full_file_url;
	if($_REQUEST['email_id'] > 0) { 
		$email_style = true;
	}
	if($email_style == true) { 
		$style_sheet = "/".$setup['manage_folder']."/css/plain.css";
	} else { 
		$style_sheet = $setup['temp_url_folder']."/sy-style.php?csst=".$site_setup['css']."&admin_edit=1&header=$header";
	}

$fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$site_setup['css']."' ORDER BY font ASC ");
	if(mysqli_num_rows($fonts) > 0) { 
	while($font = mysqli_fetch_array($fonts)) { 
		if($f > 0) { 
			$add_fonts .= "|";
		}
		$f = explode(":",$font['font']);
		$add_fonts .=  str_replace(" ","+",$font['font']);
		$editor_fonts .= "'".$f[0]."',";
		$f++;
	}
 } 

$hash = $site_setup['salt']; 
$timestamp = date('Ymdhis');
$token =  md5($hash.$timestamp);

$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$site_setup['css']."' "); 

?>


<script src="js/redactor/fontfamily.js?v=<?php print $site_setup['sytist_version'];?>"></script>
<script src="js/redactor/fontcolor.js?v=<?php print $site_setup['sytist_version'];?>"></script>
<script src="js/redactor/fontsize.js?v=<?php print $site_setup['sytist_version'];?>"></script>
<script src="js/redactor/video.js?v=<?php print $site_setup['sytist_version'];?>"></script>
<script src="js/redactor/table.js?v=<?php print $site_setup['sytist_version'];?>"></script><?php
if($email_style == true) { 
?>
<style>
.redactor-editor {
 background: #FFFFFF !important; color: #000000 !important; text-shadow: none;
}

.redactor_editor div,
.redactor_editor p,
.redactor_editor ul,
.redactor_editor ol,
.redactor_editor table,
.redactor_editor dl,
.redactor_editor blockquote,
.redactor_editor pre {
  font-family: 'Arial';
  color: #000000;
  
}
.redactor_editor h1,
.redactor_editor h2,
.redactor_editor h3,
.redactor_editor h4,
.redactor_editor h5,
.redactor_editor h6 {
  font-family: 'Arial';
  color: #000000;
  text-shadow: none;

}
.redactor-dropdown { z-index: 1000; } 
</style>
<?php 
} else { 

?>
<?php if($field == "header") { ?>
<style>
.redactor-editor {
 background: <?php print "#".$css['header_bg'];?>; color: <?php print "#".$css['header_font_color'];?>; text-shadow: none;  font-family: "<?php print $css['header_font'];?>"; font-size: <?php print $css['header_font_size'];?>px !important;
}
.redactor_editor div,
.redactor_editor p,
.redactor_editor ul,
.redactor_editor ol,
.redactor_editor table,
.redactor_editor dl,
.redactor_editor blockquote,
.redactor_editor pre {
 background: <?php print "#".$css['header_bg'];?>; color: <?php print "#".$css['header_font_color'];?>; text-shadow: none;  font-family: "<?php print $css['header_font'];?>"; font-size: <?php print $css['header_font_size'];?>px !important;
  
}

.redactor-editor h1,
.redactor-editor h2,
.redactor-editor h3,
.redactor-editor h4,
.redactor-editor h5,
.redactor-editor h6 {  background: <?php print "#".$css['header_bg'];?>; color: <?php print "#".$css['header_font_color'];?>; text-shadow: none;  font-family: "<?php print $css['header_font'];?>"; font-size: <?php print $css['header_font_size'];?>px !important;

}
</style>

<?php } else { ?>

<style>
.redactor-editor {
 background: <?php print "#".$css['inside_bg'];?>; color: <?php print "#".$css['font_color'];?>; text-shadow: none;  font-family: '<?php print $css['css_font_family_main'];?>'; font-size: <?php print $css['font_size']."px";?> !important; 
}
.redactor-editor p{
 background:  font-size: <?php print $css['font_size']."px";?> !important; 
}

.redactor_editor div,
.redactor_editor p,
.redactor_editor ul,
.redactor_editor ol,
.redactor_editor table,
.redactor_editor dl,
.redactor_editor blockquote,
.redactor_editor pre {
	background: <?php print "#".$css['inside_bg'];?>; color: <?php print "#".$css['font_color'];?>; text-shadow: none;  font-family: '<?php print $css['css_font_family_main'];?>'; font-size: <?php print $css['font_size']."px";?>  !important; 
  
}

.redactor-editor h1,
.redactor-editor h2,
.redactor-editor h3,
.redactor-editor h4,
.redactor-editor h5,
.redactor-editor h6 {  font-family: '<?php print $css['css_title_font_family_main']; ?>';
  color: <?php print "#".$css['page_title'];?>;
  text-shadow: none;

}
</style>
<?php } ?>
<?php } ?>

<script type="text/javascript">
var fonts = [ <?php print $editor_fonts;?>'Arial', 'Helvetica', 'Georgia', 'Times New Roman', 'Monospace' ];

$(document).ready(function(){

$("#<?php print $field;?>").redactor({
	iframe: false,
	toolbarFixed: true,
	toolbarFixedTopOffset: 42,
	// allowedTags: ['p', 'h1', 'h2', 'pre','body','html','script'],
	convertDivs: false,
	replaceDivs: false,
	removeEmptyTags: false,
	buttonSource: true,
	cleanSpaces: false,
	css: ['<?php print $style_sheet;?>','http://fonts.googleapis.com/css?family=<?php print $add_fonts;?>','js/redactor/tables.css'],
	minHeight: '<?php print $height;?>',
	// dragUpload: false,
	<?php if($setup['demo_mode'] !== true) { ?>
	imageUpload: 'editor-upload.php?folder=<?php print $_REQUEST['uploadlink'];?>&token=<?php print $token;?>&timestamp=<?php print $timestamp;?>&full_file_url=<?php print $full_file_url;?>',
	<?php } ?>
	buttons: ['html', '|', 'formatting', '|', 'bold', 'italic', 'deleted', '|','unorderedlist', 'orderedlist', 'outdent', 'indent', '|','image', 'video', 'file', 'table', 'link', '|', '|','alignleft', 'aligncenter', 'alignment', '|', 'horizontalrule'],
	plugins: ['video','fontfamily','fontsize', 'fontcolor','table'],
	imageUploadCallback: function(image, json) { 
//		alert(json.width);
		$(image).css({"max-width":json.width+"px","max-height":json.height+"px","width":"100%","height":"auto", "margin":"auto", "display":"block"});
		$(image).click();
		// image = this is DOM element of image
		// json = for example: { "filelink": "/images/img.jpg" }
		},

	imageUploadErrorCallback: function(json) {
		alert("Image failed to upload");
	}


});
});
</script>
<?php } ?>
