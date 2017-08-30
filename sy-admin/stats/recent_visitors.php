<?php
	$x = 1;
	if(!empty($_REQUEST['member'])) {
//		$mem = doSQL("tsg_members", "member_id, member_user", "WHERE member_user='".$_REQUEST['member']."' ");
		$and_mem = "WHERE st_member='".$mem['member_id']."' ";
	}
	if(!empty($_REQUEST['q'])) {
		$_REQUEST['q'] = trim($_REQUEST['q']);
		$and_mem .= "WHERE st_ip='".$_REQUEST['q']."' ";
	}

	if(!empty($_REQUEST['st_aff'])) { 
		$aff = doSQL("ms_affiliate", "*", "WHERE aff_id='".$_REQUEST['st_aff']."' ");
		if($aff['aff_track'] == "1") { 
			$and_mem = "WHERE st_aff='".$_REQUEST['st_aff']."' ";

		} else { 
			$and_mem = "WHERE st_aff='".$_REQUEST['st_aff']."' ";
		}
	}
	if($_REQUEST['affiliateclicks'] == "1") { 
		$and_mem = "WHERE st_aff>'0'  ";
	}
	if((!empty($_REQUEST['st_aff'])) && ($aff['aff_track'] <=0) == true) { 
		$ress = whileSQL("ms_affiliate_click  LEFT JOIN ms_stats_site_visitors ON ms_affiliate_click.click_id=ms_stats_site_visitors.st_aff", "*, date_format(DATE_ADD(st_date, INTERVAL 0 HOUR), '%m/%e')  AS st_date,  time_format(st_time, '%h:%i %p')  AS st_time", "WHERE ms_affiliate_click.click_aff='".$_REQUEST['st_aff']."' AND st_id>'0' ");
		$res = mysqli_num_rows($ress);
	} else { 

		$res = countIt("ms_stats_site_visitors",  " $and_mem");
	}
	$perPage = 20;
	$x = 1;
	if((empty($_REQUEST['pg'])) || ($_REQUEST['pg'] == "1")==true){
		$pg = 1;
		$start = 0;
	} else {
		$pg = $_REQUEST['pg'];
		$start = ($perPage * ($pg-1));
	}
	$end = ($start + $perPage);
	$NPvars = array("do=stats", "action=recentVisitors", "st_member=".$_REQUEST['member']."", "st_aff=".$_REQUEST['st_aff']."", "affiliateclicks=".$_REQUEST['affiliateclicks']."");

	$a1 = "5%";
	$a2 = "25%";
	$a3 = "12%";
	$a4 = "23%";
	$a5 = "30%";
	$a6 = "5%";
?>

<div class="pc">Click on IP address to view details and pages viewed.</div>
<div id="">
<div class="underlinelabel">
 <div style="width: <?php print $a1;?>; float: left;">#</div>
 <div style="width: <?php print $a2;?>; float: left;">IP Address</div>
 <div style="width: <?php print $a3;?>; float: left;">Date / Time</div>
 <div style="width: <?php print $a4;?>; float: left;">Referral</div>
 <div style="width: <?php print $a5;?>; float: left;">Entry Page</div>
 <div style="width: <?php print $a6;?>; float: left;">&nbsp;</div>
<div class="cssClear"></div>
</div>
<style>
</style>
 <?php 
	
	$xl = 1;
	if((!empty($_REQUEST['st_aff'])) && ($aff['aff_track'] <=0) == true) { 
			$visitors = whileSQL("ms_affiliate_click  LEFT JOIN ms_stats_site_visitors ON ms_affiliate_click.click_id=ms_stats_site_visitors.st_aff", "*, date_format(DATE_ADD(st_date, INTERVAL 0 HOUR), '%m/%e')  AS st_date,  time_format(st_time, '%h:%i %p')  AS st_time", "WHERE ms_affiliate_click.click_aff='".$_REQUEST['st_aff']."' AND st_id>'0' ORDER BY st_id DESC LIMIT $start,$perPage");

		} else { 
			$visitors = whileSQL("ms_stats_site_visitors", "*, date_format(DATE_ADD(st_date, INTERVAL 0 HOUR), '%m/%e')  AS st_date,  time_format(st_time, '%h:%i %p')  AS st_time", " $and_mem ORDER BY st_id DESC LIMIT $start,$perPage");
		}
		// $visitors = whileSQL("ms_stats_site_visitors", "*, date_format(DATE_ADD(st_date, INTERVAL 0 HOUR), '%m/%e')  AS st_date,  time_format(st_time, '%h:%i %p')  AS st_time", " $and_mem ORDER BY st_id DESC LIMIT $start,$perPage");
		while ( $visitor = mysqli_fetch_array($visitors) ) { ?>
		<div class="underline">
		<?php 

		if(strlen($visitor['st_page_title']) > 50) {
			$st_page_show = substr_replace($visitor['st_page_title'], "...", 47);
		} else {
			$st_page_show = $visitor['st_page_title'];
		}
		if(empty($st_page_show)) {
			$st_page_show = "unknown";
		}
		$sxl = $xl + ($pg * $perPage) - $perPage;
		?>
		<div style="width: <?php print $a1;?>; float: left;"><?php print $sxl;?></div>
		<div style="width: <?php print $a2;?>; float: left;">
		<div class="pc">
	<div class="pageviews" title="Page Views"><span><?php print countIt("ms_stats_site_pv", "WHERE pv_ref_id='".$visitor['st_id']."' ");?></span></div>
<?php
		if($visitor['st_last_visit'] > 0) {
			print " <div class=\"returnvis\" title=\"Return Visitor ".$visitor['st_last_visit']."\"><span>R</span></div>";
		} else {
			print " <div class=\"newvis\" title=\"New Visitor\"><span>N</span></div>";
		}
		 if($visitor['st_mobile'] == "1") { print "<div class=\"mobile\" title=\"Mobile\"><span>M</span></div> &nbsp; "; } if($visitor['st_ipad'] == "1") { print "<div class=\"tablet\" title=\"Tablet\"><span>T</span></div> &nbsp; "; } ?>


	<?php print "<a href=\"index.php?do=stats&action=visitordetails&pv_ref_id=".$visitor['st_id']."\" class=\"h2\">".$visitor['st_ip']."</a>";
		print "</div>";
		print "<div class=\"pc\"><span class=\"muted\">";

		$str = substr($visitor['st_remote_host'], -4, 4);

		$ct = strstr($str, ".");
		
		print getCountry($ct, $visitor['st_remote_host']);
		print  "&nbsp;".getBrowser($visitor['st_agent']);

		print "&nbsp; ".$visitor['st_screen']."";
		?>
		</span></div>
		<?php if($visitor['st_member'] > 0) { 
			$person = doSQL("ms_people", "*", "WHERE p_id='".$visitor['st_member']."' ");
			if($setup['demo_mode'] == true) { 
				$person['p_name'] = get_starred($person['p_name']);
				$person['p_last_name'] = get_starred($person['p_last_name']);
				$person['p_email'] = "demo@demo.mode";
			}

			?>
			<div class="pc"><h3><a href="index.php?do=people&p_id=<?php print $person['p_id'];?>"><?php print $person['p_name']." ".$person['p_last_name'];?></a></h3></div>
			<?php } ?>

			<?php if($person['p_id'] <=0) { 
				$order = doSQL("ms_orders", "*,date_format(order_date, '".$site_setup['date_format']." %h:%i %p')  AS order_date", "WHERE order_ip='".$visitor['st_ip']."' ORDER BY order_id DESC ");
				if(!empty($order['order_id'])) { ?>
				<div class="pc">Purchased on <?php print $order['order_date'];?><br><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $order['order_id'];?>">#<?php print $order['order_id'];?> <?php print $order['order_first_name']." ".$order['order_last_name'];?></a></div>
				<?php } ?>
				<?php } ?>
<?php if($visitor['st_aff'] > 0) { 
	$aff = doSQL("ms_affiliate", "*", "WHERE aff_id='".$visitor['st_aff']."' "); 
	if($aff['aff_id'] <=0) { 
		$aff = doSQL("ms_affiliate_click LEFT JOIN ms_affiliate ON ms_affiliate_click.click_aff=ms_affiliate.aff_id", "*", "WHERE click_id='".$visitor['st_aff']."' ");
	}
	?>
	<div class="pc" style="background: #ffff00;"><?php if($aff['aff_track'] == "0") { print "Affiliate: "; }  print $aff['aff_site'];?></div>
	<?php } ?>


		</div>

		<div style="width: <?php print $a3;?>; float: left;"><?php print "".$visitor['st_date']." - ".$visitor['st_time'].""; ?></div>
		<div style="width: <?php print $a4;?>; float: left;">
		<?php 
		if(empty($visitor['st_refer'])) {
			print "Direct Hit";
		} else {
			$info = explode('//', $visitor['st_refer']); 

			if(empty($info[1])) {
				$info = explode('/', $visitor['st_refer']); 
				$show_this = str_replace("www.","",$info[0]);
			} else {
				$d1 = $info[1]; 
				$info2 = explode('/', $d1); 
				$d2 = $info2[0]; 
				$show_this = str_replace("www.","",$d2);
			}
			print "<a href=\"http://".$visitor['st_refer']."\" target=\"_Blank\" class=smr title=\"".$visitor['st_refer']."\">".$show_this."</a>";

			$engines = whileSQL("ms_stats_engines", "*", " ORDER BY engine_id ASC");
			while($engine = mysqli_fetch_array($engines)) {
				if(strpos($visitor['st_refer'],$engine['engine_check']) !== false) {
					if($engine['engine_check'] == "google") { 
						if(strpos($visitor["st_refer"],'imgres') === false) {

							$query_str = $engine['engine_query_str'];
							$st_refer = $visitor['st_refer'];
							$qr = strstr($st_refer, "?");
							$qr = str_replace("?", "&", "$qr");
							$qr = parse_str($qr);
							if(!empty($$query_str)) { 
						print "<br><a href=\"".$engine['engine_search_url']."".$$query_str."\" target=\"_Blank\" class=\"muted\">".$$query_str."</a>";
							}
						}
					} else { 
					$query_str = $engine['engine_query_str'];
					$st_refer = $visitor['st_refer'];
					$qr = strstr($st_refer, "?");
					$qr = str_replace("?", "&", "$qr");
					$qr = parse_str($qr);
					if(!empty($$query_str)) { 
						print "<br><a href=\"".$engine['engine_search_url']."".$$query_str."\" target=\"_Blank\" class=\"muted\">".$$query_str."</a>";
					}
					}
				}
			}
			unset($$query_str);
		}
		?>
		</div>
		<div style="width: <?php print $a5;?>; float: left;">
		<?php 
		$page = doSQL("ms_stats_site_pv", "*", "WHERE pv_ref_id='".$visitor['st_id']."' ORDER BY pv_id ASC");
		showVisPage($page);
		?>
		</div>
		<div style="width: <?php print $a6;?>; float: left;">
		<?php 
		// $views = countIt("ms_stats_site_pv", "WHERE pv_ref_id='".$visitor['st_id']."' ");
		// print "$views";
		$xl++;
		?>
		</div>
		<div class="cssClear"></div>
		</div>
		<?php 	} ?>
		</div>
		
		<?php 


		print "<table align=center cellpadding=2 cellspacing=0 border=0 width=100%><tr align=bottom><td width=100% align=right>";
			nextprev($_REQUEST, $setting, $mem, $res, $pg, $perPage,  $NPvars, $what);
			print "</td></tr></table>";
?>
