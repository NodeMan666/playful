<div id="pageTitle"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?> Social Links</div> 
<div id="info">Here you can create links to your social sites that will show as icons in the footer section of your website. Click the edit icon to add a link. 
<br><br>
For these to show in your footer, you will need to have the bracket code [SOCIAL_LINKS] in the footer somewhere. It is there by default.</div>
<div id="message-box" class="pageContent"><?php echo $message; ?></div>
	<script>
	jQuery(document).ready(function() {
		sortItems('sortable-list-social','sort_order-social','orderSocialLinks');
	});
	</script>
	<form id="dd-form-social" action="admin.action.php" method="post">
	<input type="hidden" name="action" value="orderSocialLinks">
	<?php
	unset($order);
	$links = whileSQL("ms_social_links", "*", "WHERE link_id>'0'  ORDER BY link_order ASC  ");
	while($link = mysqli_fetch_array($links)) {
		$order[] = $link['link_id'];
	}
	?>
	<input type="hidden" name="sort_order" id="sort_order-social" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
	<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
	<p style="display: none;">
	  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
	  <label for="autoSubmit">Automatically submit on drop event</label>
	</p>

	<div style="padding: 8px;">
	<ul id="sortable-list-social" class="sortable-list">

	<?php 
	$links = whileSQL("ms_social_links", "*", "WHERE link_id>'0'  ORDER BY link_order ASC  ");
	while($link = mysqli_fetch_array($links)) {
		?>
		<li title="<?php print $link['link_id'];?>">

		<div class="underline">
			<div style="width: 5%;" class="left"><a href="" onclick="pagewindowedit('w-social-link.php?do=editLink&noclose=1&nofonts=1&nojs=1&link_id=<?php print $link['link_id'];?>','800'); return false;"><?php print ai_edit;?></a> <?php print ai_sort; ?> </div>
			<div style="width: 5%;" class="left">
			<?php 
			if($link['link_status'] == "1") {
				print " <span title=\"Active\">".ai_green."</span>";
			} else {
				print " <span title=\"Inactive\">".ai_red."</span>";
			}
			?>
			</div>
			<div style="width: 35%;" class="left"><?php print $link['link_text'];?></div>
			<div style="width: 55%;" class="left"><?php print $link['link_url'];?></div>
			<div class="clear"></div>
		</div></li>
		<?php 
	}
	?>
	</ul>
	</div>
	</form>
