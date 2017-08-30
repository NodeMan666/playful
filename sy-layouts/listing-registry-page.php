<div id="registryPage">
	<div class="photos nofloatsmall">
		<div class="inner">
			<div class="text"><?php registryMessage();?></div>
			<?php pagePhotos(); ?>
		</div>
	</div>
	<div class="content nofloatsmallleft">
		<div class="inner">
			<div class="share"><?php socialShare();?></div>
			<div class="title"><h1>Registry for: <?php pageTitle(); ?></h1></div>
			<div class="eventdate"><h2><?php registryEventDate(); ?></hs></div>
			<div class="regid"><?php registryId(); ?></div>
			<div class="shareonfb"><?php shareonfb();?></div>
			<div class="goal"><?php registryGoal();?></div>
			<div class="reginstructions"><?php registryInstructions();?></div>			
			<div class="regaddtocart"><?php productCart();?></div>
			<div class="regguestbook"><?php registryGuestBook();?></div>			
		</div>
	</div>
	<div class="clear"></div>
</div>
