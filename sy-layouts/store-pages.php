<div id="storePage">
	<div class="photos nofloatsmall">
		<div class="inner"><?php pagePhotos(); ?></div>
	</div>
	<div class="content nofloatsmall">
		<div class="inner">
			<div class="title"><h1><?php pageTitle(); ?></h1></div>
			<div class="share"><?php socialShare();?></div>
			<div class="text"><?php pageText(); ?></div>
			<div><?php productCart();?></div>
			<div class="tags"><?php pageTags(); ?></div>
		</div>
	</div>
	<div class="clear"></div>
	<div class="nextPrevious"><?php pageNextPrevious(); ?></div>
</div>
