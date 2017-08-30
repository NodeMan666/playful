<div id="pageTitle">Video</div>
<div class="pc">Here you can add  videos to be displayed on a page and select for selling.</div>

<script>
function viewVideo(vid_id) { 
	width = $("#vid_width-"+vid_id).val();
	windowloading();
	$("#pagewindowbgcontainer").fadeIn(100);
	$("#windowedit").css({"top":$(window).scrollTop()+50+"px"});
	if(width > 0) { 
		width = Math.abs(width) + 96;
		$("#windowedit").css({"width":width+"px","margin-left":"-"+width / 2+"px"});
	}
	$.get("video/video-view.php?noclose=1&nofonts=1&nojs=1&vid_id="+vid_id, function(data) {
		$("#windoweditinner").html(data);
		$("#windowedit").slideDown(200, function() { 
			$("#windoweditclose").show();
			windowloadingdone();
		});
	});
}


function editvideo(vid_id) { 
	$(".vidinfo"+vid_id).toggle();
	$(".videdit"+vid_id).toggle();

}

function savevid(vid_id) { 
	$.get("index.php?do=video&action=saveVidInfo&vid_name="+encodeURIComponent($("#vid_name-"+vid_id).val())+"&vid_width="+$("#vid_width-"+vid_id).val()+"&vid_height="+$("#vid_height-"+vid_id).val()+"&vid_id="+vid_id, function(data) {
		$("#name-"+vid_id).html($("#vid_name-"+vid_id).val());
		$("#width-"+vid_id).html($("#vid_width-"+vid_id).val());
		$("#height-"+vid_id).html($("#vid_height-"+vid_id).val());
		editvideo(vid_id);
	});

}

function getposter(vid_id) { 

	pagewindowedit("video/video-thumb.php?noclose=1&nofonts=1&nojs=1&vid_id="+vid_id,'1500');

}
</script>


<?php
require_once('getid3/getid3.php');

if($_REQUEST['action'] == "saveVidInfo") { 
	updateSQL("ms_videos", "vid_name='".addslashes(stripslashes(trim($_REQUEST['vid_name'])))."', vid_width='".$_REQUEST['vid_width']."', vid_height='".$_REQUEST['vid_height']."' WHERE vid_id='".$_REQUEST['vid_id']."' ");
	exit();
}
if($_REQUEST['action'] == "deleteVid") { 
	$vid = doSQL("ms_videos", "*", "WHERE vid_id='".$_REQUEST['vid_id']."' ");
	unlink($setup['path']."/".$setup['photos_upload_folder']."/".$vid['vid_folder']."/".$vid['vid_file']);
	deleteSQL("ms_videos",  "WHERE vid_id='".$vid['vid_id']."' ", "1");
	header("location: index.php?do=video");
	$_SESSION['sm'] = "Video ".$vid['vid_name']." deleted";
	session_write_close();
	exit();
}
?>

<div class="underlinecolumn">
	<div class="left p5">&nbsp;</div>
	<div class="left p25">Name</div>
	<div class="left p10">File Size</div>
	<div class="left p10">Length</div>
	<div class="left p10">Width</div>
	<div class="left p10">Height</div>
	<div class="left p20">Date Uploaded</div>
	<div class="left p10">&nbsp;</div>
	<div class="clear">&nbsp;</div>
</div>

<?php 
$vids = whileSQL("ms_videos", "*,date_format(vid_date, '".$site_setup['date_format']." ')  AS vid_date_show", "ORDER BY vid_date DESC ");
while($vid = mysqli_fetch_array($vids)) { ?>
<div class="underline">
	<div class="left p5">
	<a href="" onclick="editvideo('<?php print $vid['vid_id'];?>'); return false;"><?php print ai_edit;?></a> 
	<a  id="removealllink" class="confirmdelete" confirm-title="Are you sure?" confirm-message="Are you sure you want to delete this?" href="index.php?do=<?php print $_REQUEST['do'];?>&action=deleteVid&vid_id=<?php print $vid['vid_id'];?>"><?php print ai_delete;?></a>
	&nbsp;
	<a href="" onclick="getposter('<?php print $vid['vid_id'];?>'); return false;">poster</a>
	</div>


	<div class="left p25">
		<div class="vidinfo<?php print $vid['vid_id'];?>"><a href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['photos_upload_folder']; ?>/<?php print $vid['vid_folder'];?>/<?php print $vid['vid_file'];?>"  id="name-<?php print $vid['vid_id'];?>" target="_blank"><?php print $vid['vid_name'];?></a></div>
		<div class="hidden  videdit<?php print $vid['vid_id'];?>" id="vidnameedit-<?php print $vid['vid_id'];?>"><input type="text" name="vid_name" id="vid_name-<?php print $vid['vid_id'];?>" size="20" class="field100" value="<?php print $vid['vid_name'];?>"></div>

	</div>
	<div class="left p10">
	<?php 
		if($vid['vid_size'] > 1024000) {
			$bytes  = $vid['vid_size'] / 1024000;
			$bytes = round($bytes,2). "MB";
		} else {
			$bytes  =$vid['vid_size']/ 1024;
			$bytes = round($bytes,0). "KB";
		}
		print $bytes;
	?>
	</div>

		<div class="left p10">
		<div  class="vidinfo<?php print $vid['vid_id'];?>" id="length-<?php print $vid['vid_id'];?>"><?php print $vid['vid_length'];?></a></div>
		<div class="hidden  videdit<?php print $vid['vid_id'];?>" id="vidlengthedit-<?php print $vid['vid_id'];?>"><input type="text" name="vid_length" id="vid_length<?php print $vid['vid_id'];?>" size="4" class="center" value="<?php print $vid['vid_length'];?>"></div>	
	</div>


	<div class="left p10">
		<div  class="vidinfo<?php print $vid['vid_id'];?>" id="width-<?php print $vid['vid_id'];?>"><?php print $vid['vid_width'];?></a></div>
		<div class="hidden  videdit<?php print $vid['vid_id'];?>" id="vidwidthedit-<?php print $vid['vid_id'];?>"><input type="text" name="vid_width" id="vid_width-<?php print $vid['vid_id'];?>" size="4" class="center" value="<?php print $vid['vid_width'];?>"></div>	
	</div>
	<div class="left p10">
		<div class="vidinfo<?php print $vid['vid_id'];?>" id="height-<?php print $vid['vid_id'];?>"><?php print $vid['vid_height'];?></a></div>
		<div class="hidden videdit<?php print $vid['vid_id'];?>" id="vidheightedit-<?php print $vid['vid_id'];?>"><input type="text" name="vid_height" id="vid_height-<?php print $vid['vid_id'];?>" size="4" class="center" value="<?php print $vid['vid_height'];?>"></div>	
	</div>

	<div class="left p20"><?php print $vid['vid_date_show'];?></div>

	<div class="left p10">
		<div  class="hidden videdit<?php print $vid['vid_id'];?>">
		<a href="" onclick="savevid('<?php print $vid['vid_id'];?>'); return false;">Save</a>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php
		/*
$getID3 = new getID3;
$file = $getID3->analyze($setup['path']."/".$setup['photos_upload_folder']."/".$vid['vid_folder']."/".$vid['vid_file']);
echo("Duration: ".$file['playtime_string'].
" / Dimensions: ".$file['video']['resolution_x']." wide by ".$file['video']['resolution_y']." tall".
" / Filesize: ".$file['filesize']." bytes<br />");
*/
?>
<?php } ?>
