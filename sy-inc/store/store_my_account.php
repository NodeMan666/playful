<?php if(!customerLoggedIn()) {  ?>
<div id="accountloginpage">
<?php require $setup['path']."/sy-inc/store/store_login.php"; ?>
	</div>
<?php } else { 
$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' "); 
?>
<script>
function changeaccount(where) { 
	$(".accountoptions:visible").fadeOut(200, function() { 
			$("#"+where).fadeIn(200);
	});
}

</script>
<?php if($_REQUEST['n'] == "1") { ?>
<div class="success"><?php print _your_account_has_been_created_;?></div>
<?php } ?>
<?php 

		if($setup['no_expired_print_credits'] == true) { 
			$credit = doSQL("ms_credits", "SUM(credit_amount) AS tot", "WHERE credit_customer='".$person['p_id']."' ");
		} else { 
			$credit = doSQL("ms_credits", "SUM(credit_amount) AS tot", "WHERE credit_customer='".$person['p_id']."' AND (credit_expire='0000-00-00' OR credit_expire>='".date('Y-m-d')."' ) ");
		}

	if($credit['tot'] > 0) { ?>
<div class="pc creditmessage"><?php print _your_have_credit_in_account_; ?> <?php print showPrice($credit['tot']);?>.</div>
<?php } ?>
<?php $acc = doSQL("ms_new_accounts", "*", ""); ?>

<div style="width: 30%; float: left;" class="nofloatsmallleft">
	<div class="pc"><h2><?php print _my_account_;?></h2></div>
	<div class="pc">
	<ul>
	<?php $pdates = whileSQL("ms_my_pages LEFT JOIN ms_calendar ON ms_my_pages.mp_date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE MD5(mp_people_id)='".$_SESSION['pid']."'  AND date_id>'0' AND date_public='1' AND cat_type='proofing' ORDER BY date_id DESC ");
	if(mysqli_num_rows($pdates) > 0) { ?>
		<li id="account-my-proofs"><a href="" onClick="changeaccount('myproofs'); return false;"><?php print _proof_my_proofs_;?></a></li>
	<?php } ?>
	<?php $favtext = doSQL("ms_menu_links", "*", "WHERE link_main='favorites' "); ?>
	<li><a href="index.php?view=favorites"><?php print $favtext['link_text'];?></a></li>
	<li id="account-orders"><a href="" onClick="changeaccount('myorders'); return false;"><?php print _view_my_orders_;?></a></li>
	<li id="account-my-photos"><a href="" onClick="findphotos(); return false;"><?php print _view_my_photos_;?></a></li>
	<?php if(countIt("ms_contracts", "WHERE person_id='".$person['p_id']."' ") > 0) { ?>
	<li><a href="" onClick="changeaccount('changecontracts'); return false;"><?php print _my_contracts_;?></a></li>
	<?php } ?>

	<li><a href="" onClick="changeaccount('changepassword'); return false;"><?php print _change_password_;?></a></li>
	<li><a href="" onClick="changeaccount('changeemail'); return false;"><?php print _change_my_email_address_;?></a></li>
	<li><a href="" onClick="changeaccount('changeaddress'); return false;"><?php print _change_my_address_;?></a></li>
	<?php $logouttext = doSQL("ms_menu_links", "*", "WHERE link_main='logout' "); ?>
	<li><a href="index.php?view=logout"><?php print $logouttext['link_text'];?></a></li>
	</ul>
	</div>
	<?php if($setup['affiliate_program'] == true) { ?>
	<?php $cka = doSQL("ms_affiliate", "*", "WHERE aff_person='".$person['p_id']."' ");
	if(empty($cka['aff_id'])) { ?>
	<div>&nbsp;</div>

	<div class="pc"><h3>Affiliate Program</h3><a href="/affiliates/">Program information</a>  |  <a href="/affiliates/?view=join">Join now</a>!</div>
	<?php } else { ?>
	<div>&nbsp;</div>

	<div class="pc"><h3>Affiliate Program</h3><a href="/affiliates/?view=member">Go to dashboard &rarr;</a></div>


	<?php } ?>
	<?php } ?>

</div>
<div style="width: 70%; float: left;"  class="nofloatsmallleft">
	<div class="pc"><?php print _my_account_page_text_;?></div>

	<?php 
	$pdates = whileSQL("ms_my_pages LEFT JOIN ms_calendar ON ms_my_pages.mp_date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE MD5(mp_people_id)='".$_SESSION['pid']."'  AND date_id>'0' AND date_public='1' AND cat_type='proofing' ORDER BY date_id DESC ");
	if(mysqli_num_rows($pdates) > 0) { ?>

	<div id="myproofs" class="accountoptions">
		<div class="pc"><h2><?php print _proof_my_proofs_?></h2></div>
		<?php
			while($pdate = mysqli_fetch_array($pdates)) { 
				print "<div class=\"pc\">";
					$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$pdate['date_id']."'   AND bp_sub_preview<='0'  ORDER BY bp_order ASC LIMIT  1 ");
					if(!empty($pic['pic_id'])) {
						print "<a href=\"".$setup['temp_url_folder'].$setup['content_folder']."".$pdate['cat_folder']."/".$pdate['date_link']."/\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\" style=\"margin-right: 8px;\" width=\"50\" height=\"50\" border=\"0\"></a>";
					} else { 
						$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$pdate['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
						if(!empty($pic['pic_id'])) {
							print "<a href=\"".$setup['temp_url_folder'].$setup['content_folder']."".$pdate['cat_folder']."/".$pdate['date_link']."/\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\" style=\"margin-right: 8px;\" width=\"50\" height=\"50\" ></a>";
						}
					}
				print "<h2><a href=\"".$setup['temp_url_folder'].$setup['content_folder']."".$pdate['cat_folder']."/".$pdate['date_link']."/\">".$pdate['date_title']."</a></h2>";
				$pics_where = "WHERE bp_blog='".$pdate['date_id']."' $and_sub ";
				$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id ";
				$picsd = whileSQL("$pics_tables", "*", "$pics_where $and_where GROUP BY pic_id   ");
				$total_images = mysqli_num_rows($picsd);
				$total_done = 0;
				while($picd = mysqli_fetch_array($picsd)) { 
					if(countIt("ms_proofing",  "WHERE proof_date_id='".$pdate['date_id']."' AND proof_pic_id='".$picd['pic_id']."' AND proof_status='1' ")> 0) { 
						$total_done++;	
					}
					if(countIt("ms_proofing",  "WHERE proof_date_id='".$pdate['date_id']."' AND proof_pic_id='".$picd['pic_id']."' AND proof_status='2' ")> 0) { 
						$total_rev++;	
					}
				}
				print "<div>".$total_done." "._of_. " ".$total_images." "._proof_approved_."</div>";
				$cks = doSQL("ms_proofing_status", "*", "WHERE date_id='".$pdate['date_id']."' ORDER BY id DESC");
				if((empty($cks['status'])) || ($cks['status']== 0)==true) { 
					print "<div><b>"._proof_pending_your_review_."</b></div>";
				}
				if($cks['status']== "1") { 
					print "<div>"._proof_admin_pending_."</div>";
				}
				if($cks['status']== "2") { 
					print "<div>"._proof_project_closed_."</div>";
				}

				print "<div class=\"clear\"></div>";
				print "</div>";
				print "<div>&nbsp;</div>";
			}		
			?>
	</div>
	<?php } ?>


	<div id="myorders" class="accountoptions">
		<div class="pc"><h2><?php print _my_orders_?></h2></div>
		<?php
		$no_trim = true;
		$orders = whileSQL("ms_orders","*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_customer='".$person['p_id']."' AND order_status<='1'  ORDER BY order_id DESC");
		if(mysqli_num_rows($orders)<=0) { ?>
		<div class="pc"><?php print _no_orders_found_;?></div>
		<?php } 
		while($order = mysqli_fetch_array($orders)) { 
			$add_class = "";
			?>
		<?php if($order['order_due_date'] != "0000-00-00") { ?><?php if((date('Y-m-d') > $order['order_due_date'])&&($order['order_payment'] <=0) ==true) { $add_class = "bold"; }  } ?>
		<div class="pc myaccountorders">
			<div style="width: 20%;" class="left <?php print $add_class;?>"><a href="<?php print $site_setup['index_page'];?>?view=order&myorder=<?php print $order['order_id'];?>"><?php print $order['order_id'];?></a></div>
			<div style="width: 20%;" class="left <?php print $add_class;?>"><?php print $order['order_date'];?></div>
			<div style="width: 20%;" class="left <?php print $add_class;?>"><?php print showPrice($order['order_total']);?></div>
			<?php if($order['order_due_date'] != "0000-00-00") { ?>
					<?php if((date('Y-m-d') > $order['order_due_date'])&&($order['order_payment'] <=0) ==true) { ?><div style="width: 20%;" class="left"><span style="color: #890000; font-weight: bold;">Unpaid / Past Due</span></div><?php } else { ?>  

				<?php if($order['order_payment'] <=0) { ?><div style="width: 20%;" class="left"><span class="expired">Unpaid</span></div><?php } else { ?>&nbsp;<?php } ?>  
				<?php } ?>
			<?php } ?>

			<div class="clear"></div>
		</div>
		<div>&nbsp;</div>
		<?php } ?>
		<div class="pc"><a href="<?php print $site_setup['index_page'];?>?view=order"><?php print _find_an_order_;?></a></div>
	</div>

	<div id="changecontracts"  class="accountoptions hide">
		<div class="pc"><h2><?php print _my_contracts_;?></h2></div>
		<?php $contracts = whileSQL("ms_contracts", "*", "WHERE person_id='".$person['p_id']."' ORDER BY contract_id DESC ");
		while($contract = mysqli_fetch_array($contracts)) { ?>
		<div class="pc"><a href="<?php print $setup['temp_url_folder']."/".$site_setup['contract_folder']."/index.php?contract=".$contract['link'];?>" target="_blank"><?php print $contract['title'];?></a></div>
		<?php } ?>
		<div>&nbsp;</div>

	</div>




	<div id="changepassword" style="display: none;" class="accountoptions">
		<div class="pc"><h2><?php print _change_password_;?></h2></div>
		<form method="post" name="changepass" id="changepass" action="<?php print $site_setup['index_page'];?>"  onSubmit="changepassword('newpass'); return false;" >
		<div class="pc"><?php print _new_password_;?></div>
		<div class="pc"><input type="password" name="newpass" id="newpass" class="newpass cprequired" size="20"></div>
		<div class="pc"><?php print _retype_new_password_;?></div>
		<div class="pc"><input type="password" name="renewpass" id="renewpass" size="20" class="newpass cprequired" ></div>
		<div id="changepasswordresponse" class="pc hide" success="<?php print htmlspecialchars(_your_password_has_been_updated_);?>"></div>
		<div class="pc">
		<input type="hidden" name="action" id="action" value="changepassword" class="newpass">
		<input type="hidden" name="pid" id="pid" value="<?php print md5($person['p_id']);?>" class="newpass">
		<input type="submit" name="submit" value="<?php print _update_;?>" class="submit">
		</div>
		</form>
		<div>&nbsp;</div>

	</div>



	<div id="changeemail" style="display: none;" class="accountoptions">
		<div class="pc"><h2><?php print _change_my_email_address_;?></h2></div>
		<div class="pc"><?php print _current_email_;?>: <?php print $person['p_email'];?></div>
		<form method="post" name="changeemail" id="changeemail" action="<?php print $site_setup['index_page'];?>"  onSubmit="changeemailaddress('newemail'); return false;" >
		<div class="pc"><?php print _enter_new_email_;?></div>
		<div class="pc"><input type="text" name="newemail" id="newemail" class="newemail emrequired" size="40"></div>
		<div class="pc"><?php print _retype_new_email_;?></div>
		<div class="pc"><input type="text" name="renewemail" id="renewemail" size="40" class="newemail emrequired" ></div>
		<div id="changeemailresponse" class="pc hide" success="<?php print htmlspecialchars(_your_email_has_been_updated_);?>"></div>
		<div class="pc">
		<input type="hidden" name="action" id="action" value="changeemail" class="newemail">
		<input type="hidden" name="pid" id="pid" value="<?php print md5($person['p_id']);?>" class="newemail">
		<input type="submit" name="submit" value="<?php print _update_;?>" class="submit">
		</div>
		</form>
		<div>&nbsp;</div>

	</div>


<div id="changeaddress" style="display: none;" class="accountoptions">
	<form method="post" name="changeemail" id="changeemail" action="<?php print $site_setup['index_page'];?>"  onSubmit="changeaddress('newaddress'); return false;" >
	<div style="width: 49%; float: left;" >
		<div >
			<div class="pc"><?php print _company_;?></div>
			<div class="pc"><input type="text"  id="company" name="company" size="20"  value="<?php print htmlspecialchars($person['p_company']);?>" class="newaddress field100 <?php if($acc['company_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
<div class="cssClear"></div>

	<div style="width: 49%; float: left;">
		<div>
			<div class="pc"><?php print _first_name_;?></div>
			<div class="pc"><input type="text" name="first_name" id="first_name" size="20" value="<?php print htmlspecialchars($person['p_name']);?>" <?php print "class=\"newaddress field100 required\"";  ?>></div>
		</div>
	</div>
	<div style="width: 49%; float: right;">
		<div>
			<div class="pc"><?php print _last_name_;?></div>
			<div class="pc"><input type="text" name="last_name" id="last_name" size="20" value="<?php print htmlspecialchars($person['p_last_name']);?>" <?php print "class=\"newaddress field100 required\"";  ?>></div>
		</div>
	</div>
<div class="cssClear"></div>

	<div>
		<div>
			<div class="pc"><?php print _address_;?></div>
			<div class="pc"><input type="text" name="address" id="address" size="40" style="width: 98%;"   value="<?php print htmlspecialchars($person['p_address1']);?>" class="newaddress field100"></div>
		</div>
	</div>
<div class="cssClear"></div>


	<div >
		<div>
			<div class="pc"><?php print _city_;?></div>
			<div class="pc"><input type="text" name="city" id="city"  size="30" style="width: 98%;"   value="<?php print htmlspecialchars($person['p_city']);?>" class="newaddress field100"></div>
		</div>
	</div>


	<div style="float: left;">
			<div class="pc"><?php print _state_;?></div>
			<div class="pc">
			<select name="state" id="state" class="newaddress" onChange="getTax();">
			<option value=""><?php _select_state_;?></option>
			<?php $states = whileSQL("ms_states LEFT JOIN ms_countries ON ms_states.state_country=ms_countries.country_name", "*", "WHERE ms_countries.ship_to='1' AND ms_states.state_ship_to='1' ORDER BY def DESC, country_name, state_name ASC ");
			while($state = mysqli_fetch_array($states)) { ?>
			<option value="<?php print $state['state_abr'];?>" <?php if($person['p_state'] == $state['state_abr']) { print "selected"; } ?>><?php print $state['state_name']." (".$state['abr'].")"; ?></option>
			<?php } ?>
			</select>
		</div>
		</div>

		<div style="float: left;">
			<div class="pc"><?php print _zip_;?></div>
			<div class="pc"><input type="text" name="zip" id="zip" size="8" value="<?php print htmlspecialchars($person['p_zip']);?>" class="newaddress"></div>
		</div>
		<div class="cssClear"></div>

	<div>
		<div>
			<div class="pc"><?php print _country_;?></div>
			<div class="pc">
			<select name="country"  id="country"  class="newaddress required"  onChange="getTax();">
			<?php
			$cts = whileSQL("ms_countries", "*", " WHERE ship_to='1' ORDER BY def DESC, country_name ASC");

			while($ct = mysqli_fetch_array($cts)) {
				print "<option value=\"".$ct['country_name']."\" "; if($person['p_country'] == $ct['country_name']) { print " selected"; } print ">".$ct['country_name']."</option>";
			}
			print "</select>";
			?>
		</div>
		</div>
		<div>&nbsp;</div>
</div>

	<div style="width: 49%; float: left;">
		<div >
			<div class="pc"><?php print _phone_;?></div>
			<div class="pc"><input type="text"  id="phone" name="phone" size="20"  value="<?php print htmlspecialchars($person['p_phone']);?>" class="newaddress field100 <?php if($acc['phone_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
<div class="cssClear"></div>


		<div id="changeaddressresponse" class="pc hide" success="<?php print htmlspecialchars(_your_info_has_been_updated_);?>"></div>

		<div class="pc">
		<input type="hidden" name="action" id="action" value="changeaddress" class="newaddress">
		<input type="hidden" name="pid" id="pid" value="<?php print md5($person['p_id']);?>" class="newaddress">
		<input type="submit" name="submit" value="<?php print _update_;?>" class="submit">
		</div>
	</form>
</div>


</div>

<?php } ?>
