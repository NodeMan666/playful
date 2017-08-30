<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php adminsessionCheck(); ?>
<?php if($setup['demo_mode'] == true) { die("No access to this page in demo mode."); } ?>
<?php if($setup['sytist_hosted'] == true) { die(); } ?>
<div class="pc"><h1>PHP Info File</h1>
<b>This displays the PHP configuration on your hosting.</b>
</div>
<div>&nbsp;</div>
<?php phpinfo(); ?>
