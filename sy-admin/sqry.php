<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php adminsessionCheck(); ?>
<?php if($setup['demo_mode'] == true) { die("No access to this page in demo mode."); } ?>

<div class="pc"><h1>MySQL Query</h1>
<b>This tool is used to run MySQL queries to the Sytist database. ONLY RUN A QUERY IF YOU HAVE BEEN INSTRUCTED TO OR KNOW WHAT YOU ARE DOING. </b>
</div>
<div>&nbsp;</div>

<?php if(!empty($_POST['qry'])) { 
	$qrys  = explode(";",$_POST['qry']);
	foreach($qrys AS $qry) { 
		if(($qry !== '' ) AND (!empty($qry))==true) {
			$sql = stripslashes(addslashes($qry));
			 if(mysqli_query($dbcon,stripslashes($sql))) { print $qry."<br>"; } else { print "<div class=\"error\">".mysqli_error($dbcon)."</div>"; }
		}
	}
}
?>
<form method="post" name="dso" action="index.php">
<input type="hidden" name="do" value="sqry">
<div class="pc center">
<textarea name="qry" rows="30" cols="50" class="field100"></textarea>
</div>
<div class="pc center">
<input type="submit" name="submit" value="Run" class="submit">
</div>
</form>
