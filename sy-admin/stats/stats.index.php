<?php if($setup['demo_mode'] == true) { ?>
<div id="demomode">
	<div style="padding: 16px;">
		<div class="pc"><h3>Visitor Statistics</h3></div>
		<div class="pc">This section will show you information on people that have visited, pages viewed, referrers, emails sent and customers with shopping carts.</div>
	</div>
</div>
<div>&nbsp;</div>
<?php } ?>
<?php
if($_REQUEST['view'] == "emails") {
	include "stats.email.logs.php";
	} elseif($_REQUEST['view'] == "plays") {
		include "stats.plays.php";
	} elseif($_REQUEST['view'] == "ffd") {
		include "stats.ffd.php";
	} elseif($_REQUEST['view'] == "carts") {
		include "carts.php";
	} elseif($_REQUEST['view'] == "shares") {
		include "stats.shares.php";
	} else {
		include "_stats_index.php";
	}
?>