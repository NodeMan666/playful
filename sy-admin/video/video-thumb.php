<?php 
$path = "../../";
require "../w-header.php"; 

$vid = doSQL("ms_videos", "*", "WHERE vid_id='".$_REQUEST['vid_id']."' ");
?>

<script>

(function() {
    "use strict";
 
    var video, $output;
    var scale = 1;
 
    var initialize = function() {
        $output = $("#output");
        video = $("#video").get(0);
        $("#capture").click(captureImage);                
    };
 
    var captureImage = function() {
        var canvas = document.createElement("canvas");
        canvas.width = video.videoWidth * scale;
        canvas.height = video.videoHeight * scale;
        canvas.getContext('2d')
              .drawImage(video, 0, 0, canvas.width, canvas.height);
 
        var img = document.createElement("img");
        img.src = canvas.toDataURL();
		convertImage(img.src);
        $output.prepend(img);
    };
 
    $(initialize);            
 
}());

function convertImage(dataurl) { 

	$.ajax({
	  type: "POST",
	  url: "video/video-dataurl-image.php",
	  data: { 
		"img": dataurl,
		"vid": $("#vid_id").val()
	  }
	}).done(function(o) {
	  console.log('saved'); 
	  // If you want the file to be visible in the browser 
	  // - please modify the callback in javascript. All you
	  // need is to return the url to the file, you just saved 
	  // and than put the image in your browser.
	});
}

$(document).ready(function(){
	setTimeout(function(){
		// $("#capture").click();
	}, 2000);
});

</script>

</head>
<div class="center pc">
	<video id="video" controls="controls" autoplay  <?php if($vid['vid_width'] > 0) { ?>width="<?php print $vid['vid_width'];?>"<?php } ?> <?php if($vid['vid_height'] > 0) { ?>height="<?php print $vid['vid_height'];?>"<?php } ?> >
		<source src="<?php print $setup['temp_url_folder'];?>/<?php print $setup['photos_upload_folder']; ?>/<?php print $vid['vid_folder'];?>/<?php print $vid['vid_file'];?>" />
	</video>
</div> 
<input type="hidden" id="vid_id" value="<?php print $vid['vid_id'];?>">
<div class="center pc"><button id="capture">Capture Frame</button></div>
 
<div id="output" class="center pc"></div>

<?php require "../w-footer.php"; ?>
