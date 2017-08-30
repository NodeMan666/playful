<?php include $setup['path']."/sy-inc/MailChimp.php"; ?>
<div id="mailchimpsettings" class="<?php if($em_settings['mailchimp_enable'] !== "1") { print "hide"; } ?>">
	<div class="underline">
		<div class="label">MailChimp API Key</div>
		<div><input type="text" name="mailchimp_key" id="mailchimp_key" value="<?php print $em_settings['mailchimp_key'];?>" size="30" class="field100"></div>
		<div>In your MailChimp account, click your Name in the upper right corner, select  Account. Select Extras then API Keys.</div>
	</div>
	<div class="underline">
		<div class="label">MailChimp List</div>
		<div>
		<?php if(empty($em_settings['mailchimp_key'])) { ?>
		You will first need to enter your API key and update settings before the lists can be populated. <a href="https://www.picturespro.com/sytist-manual/articles/mailchimp/" target="_blank">Learn how to find your API key</a>.
		<?php } else { 
		$MailChimp = new \Drewm\MailChimp($em_settings['mailchimp_key']);
		
		?>
		<?php 
		$lists = $MailChimp->call('lists/list');
		if($lists['total'] <= 0 ) { ?>Looks like you have not created any lists in your MailChimp account. You will need to first create one list. After you create a list, refresh this page.
		<?php } else { ?>
		<select name="mailchimp_list_id" id="mailchimp_list_id" class="required">
		<option value="">Select a list (<?php print $lists['total'];?> available)</option>
		<?php
			$x = 0;
			while($x < $lists[total]) { ?>
			<option value="<?php print $lists[data][$x][id];?>" <?php if($em_settings['mailchimp_list_id'] == $lists[data][$x][id]) { print "selected"; } ?>><?php print $lists[data][$x][name];?> (<?php print $lists[data][$x][stats][member_count];?> members)</option>
			<?php 
				$x++;
			}
			?>
			</select>
			<?php 
					}
			}
			?>
		</div>
	</div>
		<div class="underline"><input type="checkbox" name="mailchimp_double_optin" id="mailchimp_double_optin" value="1" <?php if($em_settings['mailchimp_double_optin'] == "1") { print "checked"; } ?>> <label for="mailchimp_double_optin">MailChimp Double Opt-In</label><br>This option means when they sign up, they will be sent an email to confirm their subscription to your mailing list. It is recommended you use this option.</div>

		<div class="underline">
			<div class="label">Webhook URL</div>
			<div><input type="text" class="field100" id="webhook" name="webhook" value="<?php print $setup['url'].$setup['temp_url_folder']."/sy-inc/mailchimp-webhooks.php?mch=".$em_settings['webhook_hash'];?>"></div>
			<div>This URL you should add to your list in your MailChimp account. By using this, when someone confirms their subscription or unsubscribes, it will update it in this database. <a href="https://www.picturespro.com/sytist-manual/articles/mailchimp/" target="_blank">See the Webhook section in this article on where to add it</a>.</div>
		</div>

</div>

