<div class="right textright"><a href="https://www.picturespro.com/sytist-manual/people/contracts/" target="_blank" class="the icons icon-info-circled">Contracts in Manual</a></div>

<div class="pc"><a href="index.php?do=people">&larr; People</a></div>
<div class="pc newtitles"><span class="">Contracts</span></div> 

<div class="buttonsgray">
	<ul>
		<li><a href="index.php?do=people&view=allcontracts" class="on">CONTRACTS</a></li>
		<li><a href="index.php?do=people&view=allcontracts&sub=templates">TEMPLATES</a></li>
		<li><a href="index.php?do=people&view=allcontracts&sub=language">TEXT / LANGUAGE</a></li>
		<li><a href="index.php?do=people&view=allcontracts&sub=folder">DIRECTORY</a></li>
	</ul>
</div>
<div class="clear"></div>
<div>&nbsp;</div>
<?php $contracts = whileSQL("ms_contracts", "*,date_format(signed_date, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS signed_date,date_format(my_signed_date, '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS my_signed_date,date_format(signed_date2, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS signed_date2,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified_show,date_format(due_date, '".$site_setup['date_format']." ')  AS due_date_show", "WHERE person_id>'0'  ORDER BY last_modified DESC  ");

if(mysqli_num_rows($contracts) <= 0) { ?>
	<div class="pc center">No contracts created</div>
	<div class="pc" style="font-size: 17px;">
	<p>
	You can create and email contracts to your clients and have them electronically sign. To get started, you might want to <a href="index.php?do=people&view=allcontracts&sub=templates">create new template contracts</a>. 
	</p>
	<p>
	To create a contract for a customer, go to their account and click the Contracts tab. If they don't have an existing account, you will want to <a href="?do=people" onclick="editpeople(); return false;">create one for them first</a>.
	</p>
	<p>
	When you have created contracts for a client, all contracts will be shown here.
	</p>
	</div>



<?php } else { ?>
<div class="underlinecolumn">
	<div class="left p15">Customer</div>
	<div class="left p25">Name</div>
	<div class="left p20">Status</div>
	<div class="left p15">Due</div>
	<div class="left p10">PIN</div>

	<div class="right p15 textright">Last Modified</div>
	<div class="clear"></div>
</div>
<?php } 
while($contract = mysqli_fetch_array($contracts)) { 
	$p = doSQL("ms_people", "*", "WHERE p_id='".$contract['person_id']."' ");
	$total_signed = 0;?>
<div class="underline">
	<div class="left p15">
		<h3><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=contracts&contract_id=<?php print $contract['contract_id'];?>"><?php print $p['p_name']." ".$p['p_last_name'];?></a></h3>
	</div>

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

