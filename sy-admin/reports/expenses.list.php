<?php
$no_trim = true;
if(!empty($_REQUEST['tag_id'])) { 
	$thistag = doSQL("ms_expenses_tags", "*", "WHERE tag_id='".$_REQUEST['tag_id']."' ");
	$and_tag = " AND con_tag_id='".$thistag['tag_id']."' ";
}
?>
<div id="pageTitle"><h1><a href="index.php?do=reports&year=<?php print $_REQUEST['year'];?>">Reports</a> <?php print ai_sep;?> <?php print $_REQUEST['year'];?> Expenses <?php if(!empty($thistag['tag_id'])) { ?><?php print ai_sep;?> <?php print $thistag['name'];?><?php } ?></div>


<?php 
if(!empty($_REQUEST['order_by'])) { 
	$order_by = $_REQUEST['order_by'];
} else { 
	$order_by = "exp_date";
}
if(!empty($_REQUEST['acdc'])) { 
	$acdc = $_REQUEST['acdc'];
	if($_REQUEST['acdc'] == "ASC") { 
		$nacdc = "DESC";
	} else { 
		$nacdc = "ASC";
	}
} else { 
	$acdc = "DESC";
	$nacdc = "ASC";
}
if(empty($_REQUEST['year'])) { 
	$year = date('Y');
} else { 
	$year = $_REQUEST['year'];
}
?>
<div class="underlinecolumn">
	<div class="left p10"><a href="index.php?do=reports&action=expenses&tag_id=<?php print $_REQUEST['tag_id'];?>&year=<?php print $_REQUEST['year'];?>&order_by=exp_amount&acdc=<?php print $nacdc; ?>">Amount</a></div>
	<div class="left p15"><a href="index.php?do=reports&action=expenses&tag_id=<?php print $_REQUEST['tag_id'];?>&year=<?php print $_REQUEST['year'];?>&order_by=exp_date&acdc=<?php print $nacdc; ?>">Date</a></div>
	<div class="left p10">Order #</div>
	<div class="left p25">Labels</div>
	<div class="left p30">Notes</div>
	<div class="left p10 textright">
	&nbsp;</div>

	<div class="clear"></div>
</div>

<?php 
$exps = whileSQL("ms_expenses LEFT JOIN ms_expenses_tags_connect ON ms_expenses.exp_id=ms_expenses_tags_connect.con_exp_id", "*,date_format(DATE_ADD(exp_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS exp_date_show", "WHERE  YEAR(exp_date)='".$year."' $and_tag  GROUP BY exp_id ORDER BY $order_by $acdc");

if(mysqli_num_rows($exps)<=0) { ?>
<div class="center pc"><h3>Nothing Found</h3></div>
<?php if(!empty($thistag['tag_id'])) { ?>
	<div class="pc center"><a  id="deletetag" class="confirmdelete" confirm-title="Are you sure?" confirm-message="Are you sure you want to delete this label?" href="index.php?do=reports&action=deleteTag&tag_id=<?php print $thistag['tag_id'];?>"><?php print ai_delete;?> Delete the label <?php print $thistag['name'];?></a></div>
<?php } ?>
<?php 
}

while($exp = mysqli_fetch_array($exps)) { ?>
<div class="underline">
	<div class="left p10"><a href="" onclick="editexpense('<?php print $exp['exp_id'];?>'); return false;"><?php print showPrice($exp['exp_amount']);?></a></div>
	<div class="left p15"><?php print $exp['exp_date_show'];?></div>
	<div class="left p10"><?php if($exp['exp_order'] > 0) { ?><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $exp['exp_order'];?>"><?php print $exp['exp_order'];?></a><?php } else { print "&nbsp;"; } ?></div>
	<div class="left p25">
	<?php $tags = whileSQL("ms_expenses_tags_connect LEFT JOIN ms_expenses_tags ON ms_expenses_tags_connect.con_tag_id=ms_expenses_tags.tag_id", "*","WHERE con_exp_id='".$exp['exp_id']."' ORDER BY name ASC ");
	while($tag = mysqli_fetch_array($tags)) {
		print $tag['name']." &nbsp; ";
	}
	?>
		&nbsp;</div>
	<div class="left p30">
	<?php print nl2br($exp['exp_notes']);?>
	&nbsp;
	</div>
	<div class="left p10 textright">
	<a href="" onclick="editexpense('<?php print $exp['exp_id'];?>'); return false;"><?php print ai_edit;?></a> <a  id="deleteexpense" class="confirmdelete" confirm-title="Are you sure?" confirm-message="Are you sure you want to delete this expense?" href="index.php?do=reports&action=deleteExpense&exp_id=<?php print $exp['exp_id'];?>&year=<?php print $_REQUEST['year'];?>&tag_id=<?php print $thistag['tag_id'];?>" ><?php print ai_delete;?></a></div>

	<div class="clear"></div>
</div>
<?php } ?>


<div>&nbsp;</div>
