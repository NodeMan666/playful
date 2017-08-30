<script>
function deletesaved(id) { 
	$.get("index.php?do=photoprods&view=roomview&sub=created&action=deletesaved&wall_id="+id, function(data) {
		$("#ws-"+id).slideUp(200);
	});
}
</script>
<?php 
if($_REQUEST['action'] == "deletesaved") { 
	if($setup['demo_mode'] !== true) { 
		deleteSQL("ms_wall_saves", "WHERE wall_id='".$_REQUEST['wall_id']."' ","1");
	}
	exit();
}
?>

<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=roomview">Wall Designer</a>   <?php print ai_sep;?> Customer Created</div>
<div class="clear"></div>
<div class="pc">Below are saved wall designs created by customers.</div>

<?php
if(empty($_REQUEST['pg'])) {
	$pg = "1";
} else {
	$pg = $_REQUEST['pg'];
}


$per_page = 20;
$NPvars = array("do=photoprods", "view=roomview", "sub=created" );
$sq_page = $pg * $per_page - $per_page;	
$total = countIt("ms_wall_saves",  " WHERE wall_cart<='0' AND wall_collection<='0' AND wall_person>'0'  "); 

$walls = whileSQL("ms_wall_saves LEFT JOIN ms_people ON ms_wall_saves.wall_person=ms_people.p_id","*,date_format(wall_date, '".$site_setup['date_format']." %h:%i %p ')  AS wall_date_show","WHERE wall_cart<='0' AND wall_collection<='0' AND wall_person>'0' ORDER BY wall_date DESC");
if(mysqli_num_rows($walls) <= 0) { ?>None created<?php } ?>

<?php while($wall = mysqli_fetch_array($walls)) { ?>
<div class="underline" id="ws-<?php print $wall['wall_id'];?>">
<div class="p5 left"><a href="javascript:deletesaved('<?php print $wall['wall_id'];?>');" onclick="return confirm('Are you sure you want to delete this?');" class="the-icons icon-trash-empty"></a></div>
	<div class="p20 left"><a href="index.php?do=people&p_id=<?php print $wall['p_id'];?>"><?php print $wall['p_name']." ".$wall['p_last_name'];?></a></div>
	<div class="p20 left"><?php print $wall['wall_date_show'];?></a></div>
	<div class="p20 left"><a href="<?php print $setup['url'].$setup['temp_url_folder']."/index.php?wd=".$wall['wall_link'];?>" target="_blank">View</a></div>
	<div class="clear"></div>
</div>

<?php } ?>




<?php if($total > $per_page) {?>

<div class="pc center"><center><?php print nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $req);?></center></div> 
<?php } ?>


