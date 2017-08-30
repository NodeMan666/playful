<link rel="stylesheet" href="<?php print $setup['temp_url_folder'];?>/sy-inc/css/smoothness/jquery-ui.min.css" type="text/css"><script> 
$(document).ready(function(){
	$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
});
</script>
<div class="clear"></div>
<form method="get" name="regsearch" id="regsearch" action="index.php">
	<div id="regsearchbar" style="text-align: center;">
	<div style="display: inline-block; text-align: center;"><?php print _search_for_registry_;?></div>

		<div style="display: inline-block; text-align: center;" class="nofloatsmall">
			<div><?php print _name_;?></div>
			<div><input type="text" name="rname" size="20" id="rname"></div>
		</div>
		<div style="display: inline-block; text-align: center;" class="nofloatsmall">	<?php print _or_;?></div>
		<div style="display: inline-block; text-align: center;" class="nofloatsmall">
			<div><?php print _email_address_;?></div>
			<div><input type="text" name="remail" size="20" id="remail"></div>
		</div>
		<div style="display: inline-block; text-align: center;" class="nofloatsmall">	<?php print _or_;?></div>

		<div style="display: inline-block; text-align: center;" class="nofloatsmall">
			<div><?php print _registry_id_;?></div>
			<div><input type="text" name="rid" size="10" id="rid"></div>
		</div>
		<div style="display: inline-block; text-align: center;" class="nofloatsmall">	<?php print _or_;?></div>

		<div style="display: inline-block; text-align: center;" class="nofloatsmall">
			<div><?php print _date_;?></div>
			<div><input type="text" name="edate" size="10" id="edate" class="datepicker"></div>
		</div>



		<div style="display: inline-block; text-align: center;">	
		<input type="hidden" name="ar" value="search" id="ar">
		<input type="submit" name="submit" value="Search" class="submit">
		</div>


	</div>
</form>
<div class="pc center">
<?php if(customerLoggedIn()) { 
		if(countIt("ms_calendar", "WHERE MD5(reg_person)='".$_SESSION['pid']."' ") > 0) { 
			$p = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
			?>
			<h2 style="display: inline;"><a href="index.php?ar=search&remail=<?php print $p['p_email'];?>"><?php print _view_my_registry_;?></a></h2>
		<?php } 
	}
	?>
</div>