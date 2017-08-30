<?php
if($_REQUEST['action'] == "deletecontract") { 
	$contract = doSQL("ms_contracts","*,date_format(signed_date, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS signed_date,date_format(my_signed_date, '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS my_signed_date,date_format(signed_date2, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS signed_date2,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified","WHERE contract_id='".$_REQUEST['contract_id']."' ");
	if($contract['contract_id'] > 0) { 
		deleteSQL("ms_contracts", "WHERE contract_id='".$contract['contract_id']."' ","1");
	}
	$_SESSION['sm'] = "Contract deleted";
	if($contract['template'] == "1") { 
		header("location: index.php?do=people&view=allcontracts&sub=templates");
	} else { 

		header("location: index.php?do=people&p_id=".$_REQUEST['p_id']."&view=contracts");
	}
	session_write_close();
	exit();
}
?>
<div class="pc"><a href="index.php?do=people">&larr; People</a></div>
<div class="pc newtitles"><span class="">Contract Templates</span></div> 
<div>Here you can create and manage contract templates to select from when creating a contract for a clients.</div>
<div>&nbsp;</div>
<div class="buttonsgray">
	<ul>
		<li><a href="index.php?do=people&view=allcontracts">CONTRACTS</a></li>
		<li><a href="index.php?do=people&view=allcontracts&sub=templates" class="on">TEMPLATES</a></li>
		<li><a href="index.php?do=people&view=allcontracts&sub=language">TEXT / LANGUAGE</a></li>
	</ul>
</div>
<div class="pc buttons textright"><a href="" onclick="editcontracttemplate('','','1'); return false;">+ Contract Template</a></div>
<div>&nbsp;</div>


<?php $contracts = whileSQL("ms_contracts", "*,date_format(signed_date, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS signed_date,date_format(my_signed_date, '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS my_signed_date,date_format(signed_date2, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS signed_date2,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified_show,date_format(due_date, '".$site_setup['date_format']." ')  AS due_date_show", "WHERE template='1'  ORDER BY last_modified DESC  ");

if(mysqli_num_rows($contracts) <= 0) { ?>
	<div class="pc center">No contracts created</div>
<?php } 
while($contract = mysqli_fetch_array($contracts)) { 
	$p = doSQL("ms_people", "*", "WHERE p_id='".$contract['person_id']."' ");
	$total_signed = 0;?>
<div class="underline">
	<div class="left p15">
		<a href="" onclick="editcontracttemplate('<?php print $contract['contract_id'];?>','<?php print $p['p_id'];?>'); return false;" class="the-icons icon-pencil"></a>
		<a href="index.php?do=people&view=allcontracts&sub=templates&action=deletecontract&contract_id=<?php print $contract['contract_id'];?>" class="the-icons icon-trash-empty" onclick="return confirm('Are you sure you want to delete this contract????');"></a>
	</div>

	<div class="left p25">
	<h3><a href="" onclick="editcontracttemplate('<?php print $contract['contract_id'];?>','<?php print $p['p_id'];?>'); return false;"><?php print $contract['title'];?></a></h3>
	</div>



	<div class="right textright p15">
	<?php print $contract['last_modified_show'];?>
	</div>

	<div class="clear"></div>
	</div>
<?php } ?>
<script>
function showdetails(id) { 
	$("#details"+id).slideToggle(100);
}
</script>

