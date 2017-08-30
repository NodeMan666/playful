<ul class="sidemenus">
<li <?php if((empty($_REQUEST['p_id']))&&(empty($_REQUEST['type'])) == true) { print "class=\"on\""; } ?>><a href="" onclick="editexpense(''); return false;">Enter Expense</a></li>

</ul>


	<?php 
if(empty($_REQUEST['year'])) { 
	$year = date('Y');
} else {
	$year = $_REQUEST['year'];
}
?>

<div class="pc center"><h3><?php print $year;?> Expenses</h3></div>
<div style="padding: 12px 12px 4px 12px; font-size: 17px; ">

<?php 
	$tags = whileSQL("ms_expenses_tags", "*","ORDER BY name ASC ");
	while($tag = mysqli_fetch_array($tags)) {
		$tot = doSQL("ms_expenses_tags_connect LEFT JOIN ms_expenses ON ms_expenses_tags_connect.con_exp_id=ms_expenses.exp_id", "*, SUM(exp_amount) AS tot","WHERE con_tag_id='".$tag['tag_id']."' AND  YEAR(exp_date)='".$year."' GROUP BY con_tag_id ");
		?>
		<div class="sideunderline">
			<div class="left" style="font-size: 17px;"><a href="index.php?do=reports&action=expenses&tag_id=<?php print $tag['tag_id'];?>&year=<?php print $year;?>"><?php print $tag['name'];?></a></div>
			<div class="right" style="font-size: 17px;"><?php print showPrice($tot['tot']);?></div>
			<div class="clear"></div>
		</div> 
		<?php 
	}
?>
</div>
<div class="pc center"><a href="index.php?do=reports&action=expenses&year=<?php print $year;?>">View All</a></div>
