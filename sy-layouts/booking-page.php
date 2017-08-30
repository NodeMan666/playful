<div id="storePage">
	<div class="photos nofloatsmall">
		<div class="inner"><?php pagePhotos(); ?></div>
	</div>
	<div class="content nofloatsmall">
		<div class="inner">
			<div class="title"><h1><?php pageTitle(); ?></h1></div>
			<div class="share"><?php socialShare();?></div>
			<div class="text"><?php pageText(); ?></div>
			<div>&nbsp;</div>
			<div><span onclick="showbookingcalendar('<?php print MD5($date['date_id']);?>'); return false;" class="addtocart" style="padding: 8px;"><?php print $date['_book_now_'];?></span></div>
		</div>
	</div>
	<div class="clear"></div>
</div>
