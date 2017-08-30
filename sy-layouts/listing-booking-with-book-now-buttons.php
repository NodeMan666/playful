<div class="preview">
		<div class="image nofloatsmall"><?php listingPhotoNoLink($page); ?></div>
		<div class="headline"><h2><?php listingTitleOnly($page); ?></h2></div>
		<?php if($page['prod_price'] > 0) { 
		if(($page['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			 $page['prod_price'] = $page['prod_price'] + (($page['prod_price'] * $site_setup['include_vat_rate']) / 100);
		}
		?><div class="previewtext"><h3><?php print  showPrice($page['prod_price']); ?></h3></div><?php } ?>
		<div class="previewtext"  style="margin: 4px 0px;"><span onclick="showbookingcalendar('<?php print MD5($page['date_id']);?>'); return false;" class="addtocart" style="padding: 8px;"><?php print $page['_book_now_'];?></span></div>
		<div class="previewtext"><?php listingFullText($page); ?> </div>
	<div class="cssClear"></div>
</div>
