<div class="preview">
		<div class="image nofloatsmall"><?php listingPhoto($page); ?></div>
		<div class="headline"><h2><?php listingTitle($page); ?></h2></div>
		<?php if($page['prod_price'] > 0) { ?><div class="previewtext"><h3><?php print  showPrice($page['prod_price']); ?></h3></div><?php } ?>
		<div class="previewtext"><?php listingFullText($page); ?> </div>
	<div class="cssClear"></div>
</div>