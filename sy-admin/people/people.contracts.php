<?php showContractDir(); ?>
<?php if($_REQUEST['contract_id'] > 0) { 
	$contract = doSQL("ms_contracts","*,date_format(signed_date, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS signed_date,date_format(my_signed_date, '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS my_signed_date,date_format(signed_date2, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS signed_date2,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified","WHERE contract_id='".$_REQUEST['contract_id']."' ");


if($_REQUEST['action'] == "deletecontract") { 
	if($contract['contract_id'] > 0) { 
		if($setup['demo_mode'] !== true) { 
			deleteSQL("ms_contracts", "WHERE contract_id='".$contract['contract_id']."' ","1");
		}
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
<div class="pc"><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=contracts">&larr; Contracts</a></div>
<div class="pc buttons">
<a href="" onclick="editcontract('<?php print $contract['contract_id'];?>','<?php print $p['p_id'];?>'); return false;">EDIT</a>
<a href="<?php print $setup['temp_url_folder']."/".$site_setup['contract_folder'];?>/?contract=<?php print $contract['link'];?>" target="_blank">VIEW / SIGN / PRINT</a>
<a href="" onclick="emailcontract('<?php print $contract['contract_id'];?>','<?php print $p['p_id'];?>'); return false;">EMAIL</a>
<a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=contracts&action=deletecontract&contract_id=<?php print $contract['contract_id'];?>" onclick="return confirm('Are you sure you want to delete this contract????');">DELETE</a>

</div>


<?php } else { ?>
<div class="pc buttons textright"><a href="" onclick="editcontract('','<?php print $p['p_id'];?>'); return false;">+ Contract</a></div>
<div class="underlinelabel">Contracts</div>
<?php $contracts = whileSQL("ms_contracts", "*,date_format(signed_date, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS signed_date,date_format(my_signed_date, '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS my_signed_date,date_format(signed_date2, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS signed_date2,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified_show,date_format(due_date, '".$site_setup['date_format']." ')  AS due_date_show", "WHERE person_id='".$p['p_id']."' ORDER BY last_modified DESC  ");

if(mysqli_num_rows($contracts) <= 0) { ?>
	<div class="pc center">No contracts created for <?php print $p['p_name']." ".$p['p_last_name'];?></div>
<?php } else { ?>
<div class="underlinecolumn">
	<div class="left p25">Name</div>
	<div class="left p20">Status</div>
	<div class="left p15">Due</div>
	<div class="left p10">PIN</div>
	<div class="right p20 textright">Last Modified</div>
	<div class="clear"></div>
</div>
<?php } 
while($contract = mysqli_fetch_array($contracts)) { 
	$total_signed = 0;?>
<div class="underline">
<!-- 
	<div class="left p15">
		<a href="" onclick="editcontract('<?php print $contract['contract_id'];?>','<?php print $p['p_id'];?>'); return false;"class="the-icons icon-pencil"></a>
		<a href="<?php print $setup['temp_url_folder']."/".$site_setup['contract_folder'];?>/?contract=<?php print $contract['link'];?>" target="_blank" class="the-icons icon-eye"></a>
		<a href="" onclick="emailcontract('<?php print $contract['contract_id'];?>','<?php print $p['p_id'];?>'); return false;" class="the-icons icon-mail"></a>
		<a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=contracts&action=deletecontract&contract_id=<?php print $contract['contract_id'];?>" class="the-icons icon-trash-empty" onclick="return confirm('Are you sure you want to delete this contract????');"></a>
	</div>
	-->
	<div class="left p25">
	<h3><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=contracts&contract_id=<?php print $contract['contract_id'];?>"><?php print $contract['title'];?></a></h3>
	</div>

	<?php
	$total_sign = 1;
	$signed_good = false;
	if(!empty($contract['signature_name2'])) { 
		$total_sign = 2;
	}
	if((!empty($contract['signature'])) || (!empty($contract['signature_svg'])) == true) {
	 $total_signed++;
	}
	if((!empty($contract['signature2'])) || (!empty($contract['signature2_svg'])) == true) {
	 $total_signed++;
	}
	?>

	<div class="left p20">
		<?php 
		if($total_sign == "2") { 
			if($total_signed == "2") { 
				$signed_good = true;
				?><span class="signed"  style="color: #008900">Signed</span>
			<?php 
			} else { 
				?><span class="pending"><?php print $total_signed;?> of <?php print $total_sign;?> Signatures</span>
		<?php } 
	} else { 
			if((!empty($contract['signature'])) || (!empty($contract['signature_svg'])) == true) {
				$signed_good = true;

			?><span class="signed" style="color: #008900">Signed</span>
			<?php 
			} else { 
				?><span class="pending">Unsigned</span>
			<?php 
			}
	}
	?>
	<?php if((empty($contract['my_signature'])) && (empty($contract['my_signature_svg'])) == true) { ?><div><span style="color: #890000;">I need to sign</span></div><?php } ?>

	</div>
	<div class="left p15"><?php 
	if(($contract['due_date'] < date('Y-m-d')) && ($signed_good !== true) == true) { ?>
	<span style="color: #890000; font-weight: bold;"><?php print $contract['due_date_show']; ?></span>
	<?php } else { 
	print $contract['due_date_show'];
	}
	?></div>


	<div class="left p10">
	<?php print $contract['pin'];?>
	</div>

	<div class="right textright p20">
	<?php print $contract['last_modified_show'];?>
	</div>

	<div class="clear"></div>
	</div>
<?php } ?>
<?php } ?>
<script>
function showdetails(id) { 
	$("#details"+id).slideToggle(100);
}
</script>

<?php


if($_REQUEST['contract_id'] > 0) { 
	$content = $contract['content'];
	$content = str_replace("[NAME]",$contract['signature_name'],$content);
	$content = str_replace("[NAME2]",$contract['signature_name2'],$content);
	$content = str_replace("[MY_NAME]",$contract['my_name'],$content);
	$content = preg_replace_callback('~\[TEXT_INPUT_OPTIONAL\]~', "replacetextinputoption", $content);
	$content = preg_replace_callback('~\[TEXT_INPUT_REQUIRED\]~', "replacetextinputrequired", $content);
	$content = preg_replace_callback('~\[TEXT_INPUT_SHORT_OPTIONAL\]~', "replacetextinputshortoption", $content);
	$content = preg_replace_callback('~\[TEXT_INPUT_SHORT_REQUIRED\]~', "replacetextinputshortrequired", $content);

	$content = preg_replace_callback('~\[CHECKBOX_OPTIONAL\]~', "replacecheckboxoption", $content);
	$content = preg_replace_callback('~\[CHECKBOX_REQUIRED\]~', "replacecheckboxoptionrequired", $content);

	if(!empty($contract['content_signed'])) { 
		$content = $contract['content_signed'];
	}

	?>
	<div>&nbsp;</div>
	<?php if($contract['invoice'] > 0) { ?>
	<div class="pc"><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $contract['invoice'];?>">Attached invoice #<?php print $contract['invoice'];?></a></div>
	<?php } ?>
	<div class="pc">PIN: <?php print $contract['pin'];?></div>
	<?php if(!empty($contract['signature_name'])) { ?>
		<?php if((empty($contract['signature'])) && (empty($contract['signature_svg'])) == true) { ?>
		<div class="pc bold"><?php print $contract['signature_name'];?> has not signed</div>
		<?php } else { ?>
		<div class="pc"><span class="bold"><?php print $contract['signature_name'];?> signed on <?php print $contract['signed_date'];?></span> <a href="" onclick="showdetails('1'); return false;">details</a></div>
		<div id="details1" class="hide">
			<div class="pc">IP address: <?php print $contract['ip_address'];?></div>
			<div class="pc">Browser info: <?php print $contract['browser_info'];?></div>
		</div>
		<?php } ?>
	<?php } ?>

	<?php if(!empty($contract['signature_name2'])) { ?>
		<?php if((empty($contract['signature2'])) && (empty($contract['signature2_svg'])) == true) { ?>
		<div class="pc bold"><?php print $contract['signature_name2'];?> has not signed</div>
		<?php } else { ?>
		<div class="pc"><span class="bold"><?php print $contract['signature_name2'];?> signed on <?php print $contract['signed_date2'];?></span> <a href="" onclick="showdetails('2'); return false;">details</a></div>
		<div id="details2" class="hide">
			<div class="pc">IP address: <?php print $contract['ip_address2'];?></div>
			<div class="pc">Browser info: <?php print $contract['browser_info2'];?></div>
		</div>
		<?php } ?>
	<?php } ?>

	<?php if(!empty($contract['my_name'])) { ?>
		<?php if((empty($contract['my_signature'])) && (empty($contract['my_signature_svg'])) == true) { ?>
		<div class="pc bold"><?php print $contract['my_name'];?> has not signed</div>
		<?php } else { ?>
		<div class="pc"><span class="bold"><?php print $contract['my_name'];?> signed on <?php print $contract['my_signed_date'];?></span> <a href="" onclick="showdetails('3'); return false;">details</a></div>
		<div id="details3" class="hide">
			<div class="pc">IP address: <?php print $contract['my_ip_address'];?></div>
			<div class="pc">Browser info: <?php print $contract['my_browser_info'];?></div>
		</div>
		<?php } ?>
	<?php } ?>

	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div id="contract" style="max-width: 800px; ">
	<div class="pc">
	<?php print $content;?>
	</div>


<link href='//fonts.googleapis.com/css?family=Satisfy' rel='stylesheet' type='text/css'>


<style>
.signature { font-family: Satisfy; font-size: 21px; border-top: 0px; border-left: 0px; border-right: 0px; border-bottom: dashed #000000  2px; padding:  4px; margin-right: 12px; min-width: 400px; }
.signeddate { color: #008900;  padding:  4px; } 
</style>






	</div>

<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<?php } ?>
