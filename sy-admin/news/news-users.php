<?php 
$path = "../../";
require "../w-header.php"; 
$email_style = true;	
$full_file_url = 1;
$date = doSQL("ms_calendar  LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, date_format(DATE_ADD(date_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS date_show_date,date_format(date_expire, '".$site_setup['date_format']."')  AS date_expire", "WHERE date_id='".$_REQUEST['date_id']."' ");

if($_REQUEST['action'] == "addaccess") { 
	$ck = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$date['date_id']."' AND mp_people_id='".$_REQUEST['p_id']."' ");
	if(empty($ck['mp_id'])) { 
		insertSQL("ms_my_pages", "mp_date_id='".$date['date_id']."', mp_people_id='".$_REQUEST['p_id']."' , mp_date='".date('Y-m-d H:i:s')."' ");
	}
}

if($_REQUEST['action'] == "removeaccess") { 
	$ck = doSQL("ms_my_pages", "*", "WHERE mp_id='".$_REQUEST['mp_id']."' ");
	if(!empty($ck['mp_id'])) { 
		deleteSQL("ms_my_pages", "WHERE mp_id='".$_REQUEST['mp_id']."' ", "1");
	}
}

if($_REQUEST['action'] == "removeviewed") { 
	$ck = doSQL("ms_view_page", "*", "WHERE v_id='".$_REQUEST['v_id']."' ");
	if(!empty($ck['v_id'])) { 
		deleteSQL("ms_view_page", "WHERE v_id='".$_REQUEST['v_id']."' ", "1");
	}
}

if($_REQUEST['action'] == "removeprereg") { 
	$ck = doSQL("ms_pre_register", "*", "WHERE reg_id='".$_REQUEST['reg_id']."' ");
	if(!empty($ck['reg_id'])) { 
		deleteSQL("ms_pre_register", "WHERE reg_id='".$_REQUEST['reg_id']."' ", "1");
	}
}

if($_REQUEST['action'] == "removeviewedall") { 
	deleteSQL2("ms_view_page", "WHERE v_page='".$_REQUEST['date_id']."' ");
}


if($_REQUEST['action'] == "sendemail") { 

	$emails = array();
	if($_POST['send_access'] =="1") { 
		$ps = whileSQL("ms_my_pages LEFT JOIN ms_people ON ms_my_pages.mp_people_id=ms_people.p_id", "*, date_format(DATE_ADD(mp_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS mp_date", "WHERE mp_date_id='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
		while($p = mysqli_fetch_array($ps)) { 
			if(!in_array($p['p_email'],$emails)) { 
				if(!empty($p['p_email'])) { 
					senddateemail($p['p_email'],$p['p_name'],$p['p_last_name']);
					array_push($emails,$p['p_email']);
					$sent_to .= "<li>".$p['p_email'];
					$total_sent ++; 
				}
			}
		}
	}
	if($_POST['send_pre_reg'] == "1") { 
		$ps = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."' AND toview<='0'  ORDER BY reg_id DESC");
		while($p = mysqli_fetch_array($ps)) {
			if(!in_array($p['reg_email'],$emails)) { 
				senddateemail($p['reg_email'],$p['reg_first_name'],$p['reg_last_name']);
				array_push($emails,$p['reg_email']);
				$sent_to .= "<li>".$p['reg_email'];
				$total_sent ++; 
			}
		 } 
	}

	if($_POST['send_collected_emails'] == "1") { 
		$ps = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."' AND toview='1'  ORDER BY reg_id DESC");
		while($p = mysqli_fetch_array($ps)) {
			if(!in_array($p['reg_email'],$emails)) { 
				senddateemail($p['reg_email'],$p['reg_email'],$p['reg_email']);
				array_push($emails,$p['reg_email']);
				$sent_to .= "<li>".$p['reg_email'];
				$total_sent ++; 
			}
		 } 
	}



	if($_POST['send_viewed'] == "1") {  
		$ps = whileSQL("ms_view_page LEFT JOIN ms_people ON ms_view_page.v_person=ms_people.p_id", "*, date_format(DATE_ADD(v_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS v_date", "WHERE v_page='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
		while($p = mysqli_fetch_array($ps)) {
			if(!in_array($p['p_email'],$emails)) { 
				senddateemail($p['p_email'],$p['p_name'],$p['p_last_name']);
				array_push($emails,$p['p_email']);
				$sent_to .= "<li>".$p['p_email'];
				$total_sent ++; 
			}
		 } 
	}

	if($_POST['send_purchased'] == "1") { 
//		$ps = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date", "WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  GROUP BY order_email ORDER BY order_last_name ASC ");

		$ps = mysqli_query($dbcon,"
		SELECT *, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date FROM (
		SELECT *  FROM ms_cart 
		 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
		WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  

	  UNION ALL

		SELECT *  FROM ms_cart_archive 
		 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
		WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2' 
		) 
		x 
		GROUP BY order_email ORDER BY order_last_name ASC
		");

		if (!$ps) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }


		while($p = mysqli_fetch_array($ps)) {
			if(!empty($p['order_email'])) { 
				if(!empty($p['order_email'])) { 

					if(!in_array($p['order_email'],$emails)) { 
						senddateemail($p['order_email'],$p['order_first_name'],$p['order_last_name']);
						array_push($emails,$p['order_email']);
						$sent_to .= "<li>".$p['order_email'];
						$total_sent ++; 
					}
				}
			}
		 } 
	}

	if(!empty($_POST['emails_tos'])) {
		$to_emails = explode(",", $_POST['emails_tos']);
		foreach($to_emails AS $to_email) {
			$to_email = trim($to_email);
			if(!empty($to_email)) {
				if(!in_array($to_email,$emails)) { 
					$total_sent++;
					$sent_to .= "<li>".$to_email;
					senddateemail($to_email,"","");
				}
			}
		}
	}
	print "<pre>"; print_r($_POST);
	print "########## <pre>".$_POST['emails_tos']."</pre> ########### ";
	print $sent_to;
	print "<div id=\"emailresults\">Email sent to $total_sent people. </div>";
	exit();

}


function senddateemail($email,$first_name,$last_name) { 
	global $site_setup;
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	$subject = str_replace("[FIRST_NAME]",stripslashes($first_name), "$subject");
	$subject = str_replace("[LAST_NAME]",stripslashes($last_name), "$subject");
	$message = str_replace("[FIRST_NAME]",stripslashes($first_name), "$message");
	$message = str_replace("[LAST_NAME]",stripslashes($last_name), "$message");

	if(($site_setup['cron_enabled'] == "1") && ($site_setup['cron_test_mode'] !== "1") == true) { 
		addemailtocron($email,$first_name,$last_name,$_POST['from_email'],$_POST['from_name'],$subject,$message,1);
	} else { 
		sendWebdEmail("".$email."", "".$first_name." ".$last_name."", "".$_POST['from_email']."", "".$_POST['from_name']."", $subject, $message,"1");
	}
}

?>
<div class="buttons">
<a href="" onclick="selectemail('peoplelist'); return false;">List</a> 
<a href="" onclick="selectemail('emailform'); return false;">Email</a> 
<a href="" onclick="selectemail('exportform'); return false;">Export</a>
</div>
<div>&nbsp;</div>
<script>
function addaccess() { 
	if($("#mp_people_id").val()<=0) { 
		alert("Select a person");
	} else { 
		windowloading();
		$.get("news/news-users.php?action=addaccess&date_id="+$("#date_id").val()+"&p_id="+$("#mp_people_id").val()+"&noclose=1&nofonts=1&nojs=1", function(data) {
			$("#windoweditinner").html(data);
			$("#windowedit").slideDown(200, function() { 
				$("#windoweditclose").show();
				windowloadingdone();
			});
		});
	}
}
function removeaccess(id) { 
	windowloading();
	$.get("news/news-users.php?action=removeaccess&date_id="+$("#date_id").val()+"&mp_id="+id+"&noclose=1&nofonts=1&nojs=1", function(data) {
		$("#windoweditinner").html(data);
		$("#windowedit").slideDown(200, function() { 
			$("#windoweditclose").show();
			windowloadingdone();
		});
	});
}


function removeviewed(id) { 
	windowloading();
	$.get("news/news-users.php?action=removeviewed&date_id="+$("#date_id").val()+"&v_id="+id+"&noclose=1&nofonts=1&nojs=1", function(data) {
		$("#windoweditinner").html(data);
		$("#windowedit").slideDown(200, function() { 
			$("#windoweditclose").show();
			windowloadingdone();
		});
	});
}
function removeprereg(id) { 
	windowloading();
	$.get("news/news-users.php?action=removeprereg&date_id="+$("#date_id").val()+"&reg_id="+id+"&noclose=1&nofonts=1&nojs=1", function(data) {
		$("#windoweditinner").html(data);
		$("#windowedit").slideDown(200, function() { 
			$("#windoweditclose").show();
			windowloadingdone();
		});
	});
}

function removeviewedall() { 
	windowloading();
	$.get("news/news-users.php?action=removeviewedall&date_id="+$("#date_id").val()+"&noclose=1&nofonts=1&nojs=1", function(data) {
		$("#windoweditinner").html(data);
		$("#windowedit").slideDown(200, function() { 
			$("#windoweditclose").show();
			windowloadingdone();
		});
	});
}



function selectemail(id) {
	$(".useroptions").slideUp(200);
	$("#"+id).slideDown(200);
}

function sendemail() { 
	var fields = {};

	pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	fields['date_id'] = $("#date_id").val();
	fields['p_ids'] = $("#p_ids").val();
	fields['action'] = "sendemail";
	fields['subject'] = $("#subject").val();
	fields['message'] = $("#message").val();
	fields['subject'] = $("#subject").val();
	fields['from_email'] = $("#from_email").val();
	fields['from_name'] = $("#from_name").val();
	fields['emails_tos'] = $("#emails_tos").val();
	if($("#send_access").attr("checked")) { 
		fields['send_access'] = "1";
	}
	if($("#send_pre_reg").attr("checked")) { 
		fields['send_pre_reg'] = "1";
	}
	if($("#send_collected_emails").attr("checked")) { 
		fields['send_collected_emails'] = "1";
	}

	if($("#send_viewed").attr("checked")) { 
		fields['send_viewed'] = "1";
	}
	if($("#send_purchased").attr("checked")) { 
		fields['send_purchased'] = "1";
	}





	if($("#email_confirmed").attr("checked") !== "checked") { 
		alert("Please check the confirm checkbox");
	} else { 
	$("#submitsend").hide();
	$("#sending").show();

		windowloading();

		$.post('news/news-users.php', fields,	function (data) { 
 	
			// alert(data);
			windowloadingdone();
			$("#emailcomplete").append($("#emailresults").html()).slideDown(200);		
			$("#sending").hide();

		});
	}
}
function selectdefaultemail() {
	id = $("#email_id").val();
	windowloading();
	$.get("news/news-users.php?date_id="+$("#date_id").val()+"&email_id="+id+"&noclose=1&nofonts=1&nojs=1", function(data) {
		$("#windoweditinner").html(data);
		$("#windowedit").slideDown(200, function() { 
			$("#windoweditclose").show();
			windowloadingdone();
		});
	});
}

function toggleid(id) { 
	$("#"+id).slideToggle(200);
}

</script>

<?php 
$emails = array(); 
$peeps = array();


	?>
<div id="peoplelist" class="useroptions <?php if($_REQUEST['email_id'] > 0) { print "hidden"; } ?>">
<?php // if($date['cat_type'] == "clientphotos") {  ?>
<?php if($date['private'] > 0) { ?>
	<div class="pc"><h2>Registered people with access to <?php print $date['date_title'];?> (private page)</h2></div>
	<?php } else { ?>
	<div class="pc"><h2>Registered people assigned to <?php print $date['date_title'];?> (public page)</h2></div>
	<?php } ?>
	<?php 
	$ps = whileSQL("ms_people", "*", "WHERE p_id>'0' ORDER BY p_last_name ASC ");
	if(mysqli_num_rows($ps) > 0) { ?>
	<div class="underline">
	<input type="hidden" id="date_id" value="<?php print $date['date_id'];?>">
	<select name="mp_people_id" id="mp_people_id"  onchange="addaccess(); ">
	<option value=""><?php if($date['private'] > 0) { ?>Add Access To<?php } else { ?>Add People<?php } ?></option>
	<?php 
		while($p = mysqli_fetch_array($ps)) { 
			if(countIt("ms_my_pages", "WHERE mp_date_id='".$date['date_id']."' AND mp_people_id='".$p['p_id']."' ")<=0) { ?>
			<option value="<?php print $p['p_id'];?>"><?php print $p['p_last_name'].", ".$p['p_name'];?> (<?php print $p['p_email'];?>)</option>
			<?php } ?>
		<?php } ?>
		</select>
	</div>
	<?php } ?>

	<?php 	
	$ps = whileSQL("ms_my_pages LEFT JOIN ms_people ON ms_my_pages.mp_people_id=ms_people.p_id", "*, date_format(DATE_ADD(mp_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS mp_date", "WHERE mp_date_id='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
	if(mysqli_num_rows($ps) <=0) { ?>
	<div class="pc">No people found</div>
	<?php } ?>

	<?php 
	while($p = mysqli_fetch_array($ps)) { 
		$first_email = $p['p_email'];
		$first_name = $p['p_name']." ".$p['p_last_name'];
		if(!in_array($p['p_email'],$emails)) { 
			array_push($emails,$p['p_email']);
		}
		if(!in_array($p['p_id'],$peeps)) { 
			$p_ids = $p_ids."|".$p['p_id'];
			array_push($peeps,$p['p_id']);
		}

		?>
	<div class="underline">
		<div class="left p10"><a href="" onclick="removeaccess('<?php print $p['mp_id'];?>'); return false;">remove</a></div>
		<div class="left p35"><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>"><?php print $p['p_last_name'].", ".$p['p_name'];?></a></div>
		<div class="left p35"><?php print $p['p_email'];?></div>
		<div class="left p20 textright"><?php print $p['mp_date'];?></div>
		<div class="clear"></div>
	</div>

	<?php } ?>
	<div>&nbsp;</div>
<?php // } ?>
<?php
$ps = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."'  AND toview<='0'  ORDER BY reg_id DESC");
if(mysqli_num_rows($ps) > 0) { ?>

<div class="pc"><h2>People who have pre-registered to <?php print $date['date_title'];?></h2></div>
<?php 
while($p = mysqli_fetch_array($ps)) {
		$first_email = $p['reg_email'];
		$first_name = $p['reg_first_name']." ".$p['reg_last_name'];

	if(!in_array($p['reg_email'],$emails)) { 
		array_push($emails,$p['reg_email']);
	}
	?>
<div class="underline">
	<div class="left p10"><a href="" onclick="removeprereg('<?php print $p['reg_id'];?>'); return false;">remove</a></div>
	<div class="left p35"><?php print $p['reg_last_name'].", ".$p['reg_first_name'];?></div>
	<div class="left p35"><?php print $p['reg_email'];?></div>
	<div class="left p20 textright"><?php print $p['reg_date'];?></div>
	<div class="clear"></div>
</div>

<?php } ?>

<div>&nbsp;</div>
<?php  } ?>







<?php	
$ps = whileSQL("ms_view_page LEFT JOIN ms_people ON ms_view_page.v_person=ms_people.p_id", "*, date_format(DATE_ADD(v_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS v_date", "WHERE v_page='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
if(mysqli_num_rows($ps) >0) {  ?>
<div>&nbsp;</div>

<div class="pc"><h2 style="display: inline;">Registered people who have viewed <?php print $date['date_title'];?></h2> <a href="" onclick="removeviewedall(); return false;">remove all</a></div>

<?php 	
$ps = whileSQL("ms_view_page LEFT JOIN ms_people ON ms_view_page.v_person=ms_people.p_id", "*, date_format(DATE_ADD(v_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS v_date", "WHERE v_page='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
if(mysqli_num_rows($ps) <=0) { ?>
<div class="pc">No people found</div>
<?php } else { ?>

<?php } ?>
<?php 
while($p = mysqli_fetch_array($ps)) {
		$first_email = $p['p_email'];
		$first_name = $p['p_name']." ".$p['p_last_name'];

	if(!in_array($p['p_email'],$emails)) { 
		array_push($emails,$p['p_email']);
	}
	if(!in_array($p['p_id'],$peeps)) { 
		$p_ids = $p_ids."|".$p['p_id'];
		array_push($peeps,$p['p_id']);
	}
	?>
<div class="underline">
	<div class="left p10"><a href="" onclick="removeviewed('<?php print $p['v_id'];?>'); return false;">remove</a></div>
	<div class="left p35"><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>"><?php print $p['p_last_name'].", ".$p['p_name'];?></a></div>
	<div class="left p35"><?php print $p['p_email'];?></div>
	<div class="left p20 textright"><?php print $p['v_date'];?></div>
	<div class="clear"></div>
</div>

<?php } ?>
<?php  } ?>


<?php 
$ps = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date", "WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  GROUP BY order_email ORDER BY order_last_name ASC ");
if(mysqli_num_rows($ps) > 0) { ?>
<div>&nbsp;</div>

<div class="pc"><h2 style="display: inline;">People who have purchased from <?php print $date['date_title'];?></h2></div>

<?php 	
$ps = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date", "WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  GROUP BY order_email ORDER BY order_last_name ASC ");








	$ps = mysqli_query($dbcon,"
    SELECT *, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date FROM (
	SELECT *  FROM ms_cart 
	 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  

  UNION ALL

    SELECT *  FROM ms_cart_archive 
	 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2' 
	) 
	x 
	GROUP BY order_email ORDER BY order_last_name ASC
	");

	if (!$ps) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }




if(mysqli_num_rows($ps) <=0) { ?>
<div class="pc">No people found</div>
<?php } else { ?>

<?php } ?>
<?php 
while($p = mysqli_fetch_array($ps)) {
	$first_email = $p['order_email'];
	$first_name = $p['order_first_name']." ".$p['order_last_name'];
	if(!empty($p['order_email'])) { 
		if(!in_array($p['order_email'],$emails)) { 
			array_push($emails,$p['order_email']);
		}

		?>
	<div class="underline">
		<div class="left p10">&nbsp;</div>
		<div class="left p35"><a href="index.php?do=orders&q=<?php print $p['order_email'];?>"><?php print $p['order_last_name'].", ".$p['order_first_name'];?></a></div>
		<div class="left p35"><?php print $p['order_email'];?></div>
		<div class="left p20 textright"><?php print $p['order_date'];?></div>
		<div class="clear"></div>
	</div>
	<?php } ?>
<?php } ?>
<?php } ?>
<?php
$ps = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."'  AND toview='1'  ORDER BY reg_id DESC");
if(mysqli_num_rows($ps) > 0) { ?>
<div>&nbsp;</div>

<div class="pc"><h2>Emails collected to view <?php print $date['date_title'];?></h2></div>
<?php 
while($p = mysqli_fetch_array($ps)) {
		$first_email = $p['reg_email'];
		$first_name = $p['reg_first_name']." ".$p['reg_last_name'];

	if(!in_array($p['reg_email'],$emails)) { 
		array_push($emails,$p['reg_email']);
	}
	?>
<div class="underline">
	<div class="left p45"><a href="" onclick="removeprereg('<?php print $p['reg_id'];?>'); return false;">remove</a></div>

	<div class="left p35"><?php print $p['reg_email'];?></div>
	<div class="left p20 textright"><?php print $p['reg_date'];?></div>
	<div class="clear"></div>
</div>

<?php } ?>

<div>&nbsp;</div>
<?php  } ?>

</div>
<div id="exportform"  class="useroptions hidden">
<div class="clear"></div>

<?php if(count($emails) <=0) { ?>
<div class="error">No people to export</div>
<?php } else { ?>

<form method="post" name="export" action="./export.php" target="_blank">
<div class="left p25">
<div class="underlinelabel">Fields to export</div>

<div class="underline"><input type="checkbox" name="id" id="id"> <label for="id">ID</label></div>
<div class="underline"><input type="checkbox" name="company" id="company"> <label for="company">Company</label></div>
<div class="underline"><input type="checkbox" name="email" id="email" checked> <label for="email">Email Address</label></div>

<div class="underline"><input type="checkbox" name="firstlastName" id="firstlastName" checked> <label for="firstlastName">First Last Name</label></div>
<div class="underline"><input type="checkbox" name="lastfirstName" id="lastfirstName"> <label for="lastfirstName">Last First Name</label></div>

<div class="underline"><input type="checkbox" name="firstName" id="firstName"> <label for="firstName">First Name Only</label></div>
<div class="underline"><input type="checkbox" name="lastName" id="lastName"> <label for="lastName">Last Name Only</label></div>
<div class="underline"><input type="checkbox" name="phone" id="phone"> <label for="phone">Phone</label></div>
<div class="underline"><input type="checkbox" name="address" id="address"> <label for="address">Address</label></div>
<div class="underline"><input type="checkbox" name="city" id="city"> <label for="city">City</label></div>
<div class="underline"><input type="checkbox" name="state" id="state"> <label for="state">State</label></div>
<div class="underline"><input type="checkbox" name="zip" id="zip"> <label for="zip">Zip</label></div>
<div class="underline"><input type="checkbox" name="country" id="country"> <label for="country">Country</label></div>
<div class="underline"><input type="checkbox" name="date" id="date"> <label for="date">Date</label></div>

</div>

<div class="right p65">
	<div class="underline">
	<?php
	$ps = whileSQL("ms_my_pages LEFT JOIN ms_people ON ms_my_pages.mp_people_id=ms_people.p_id", "*, date_format(DATE_ADD(mp_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS mp_date", "WHERE mp_date_id='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
	$access = mysqli_num_rows($ps);

	$ps = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."'  AND toview<='0'   ORDER BY reg_id DESC");
	$prereg = mysqli_num_rows($ps);

	$ps = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."'  AND toview='1'   ORDER BY reg_id DESC");
	$emailscollected = mysqli_num_rows($ps);

	$ps = whileSQL("ms_view_page LEFT JOIN ms_people ON ms_view_page.v_person=ms_people.p_id", "*, date_format(DATE_ADD(v_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS v_date", "WHERE v_page='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
	$page_viewed = mysqli_num_rows($ps);

//	$ps = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date", "WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  GROUP BY order_email ORDER BY order_last_name ASC ");

	$ps = mysqli_query($dbcon,"
    SELECT *, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date FROM (
	SELECT *  FROM ms_cart 
	 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  

  UNION ALL

    SELECT *  FROM ms_cart_archive 
	 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2' 
	) 
	x 
	GROUP BY order_email ORDER BY order_last_name ASC
	");

	if (!$ps) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }


	$purchased = mysqli_num_rows($ps);
	?>
	<div class="label">
	<?php if($access > 0) { ?>
		<input type="checkbox" name="xsend_access" id="xsend_access" checked value="1"> <label for="xsend_access">People who have access to this page (<?php print $access;?>)</label> <br>
		<?php } ?>
		<?php if($prereg > 0) { ?>
		<input type="checkbox" name="xsend_pre_reg" id="xsend_pre_reg" checked value="1"> <label for="xsend_pre_reg">People who have pre-registered (<?php print $prereg;?>)</label>  <br>
		<?php } ?>
		<?php if($emailscollected > 0) { ?>
		<input type="checkbox" name="xsend_emails_collected" id="xsend_emails_collected" checked value="1"> <label for="xsend_emails_collected">Emails collected to view (<?php print $emailscollected;?>)</label>  <br>
		<?php } ?>
		<?php if($page_viewed > 0) { ?>
		<input type="checkbox" name="xsend_viewed" id="xsend_viewed" checked value="1"> <label for="xsend_viewed">Registered people who have viewed (<?php print $page_viewed;?>)</label>  <br>
		<?php } ?>
		<?php if($purchased > 0) { ?>
		<input type="checkbox" name="xsend_purchased" id="xsend_purchased" checked value="1"> <label for="xsend_purchased">People who have purchased (<?php print $purchased;?>)</label>
		<?php } ?>
		</div>
		<div><i>Note: if someone is in multiple categories above, they will only be exported once.</i></div>
	</div>

<input type="hidden" name="date_id" id="date_id" value="<?php print $date['date_id'];?>">
	<div class="underlinelabel">Separate fields with</div>
	<div class="underline"> <input type="text" name="sep" size="2" value=","> </div>
	<div class="underlinelabel">Do with</div>
		<div class="underline"> <input type="radio" name="dowith" value="download" id="dowidthdownload" checked> <label for="dowidthdownload">Download as CSV</label> 
		<input type="radio" name="dowith" value="view" id="dowithview"> <label for="dowithview">Print to screen</label> 
	</div>

	<div class="pc">
	<input type="hidden" name="action" value="people">
	<input type="submit" name="submit" value="Export" class="submit">
	</div>

	</div>


</div>
<div class="clear"></div>
</form>
<?php } ?>
</div>




<div id="emailform"  class="useroptions <?php if($_REQUEST['email_id'] <=0) { print "hidden"; } ?>">
	<div class="pc"><h3>Send email to: 
	<?php if(count($emails) == "1") { ?>
	<?php print $first_name." &lt".$first_email.">";?>
	<?php } ?>
	</h3></div>
	<?php if(count($emails) > 0) { ?>
	<div class="underline">

	<?php
	$ps = whileSQL("ms_my_pages LEFT JOIN ms_people ON ms_my_pages.mp_people_id=ms_people.p_id", "*, date_format(DATE_ADD(mp_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS mp_date", "WHERE mp_date_id='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
	$access = mysqli_num_rows($ps);

	$ps = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."'  AND toview<='0'  ORDER BY reg_id DESC");
	$prereg = mysqli_num_rows($ps);

	$ps = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."'  AND toview='1'  ORDER BY reg_id DESC");
	$emailcollect = mysqli_num_rows($ps);

	$ps = whileSQL("ms_view_page LEFT JOIN ms_people ON ms_view_page.v_person=ms_people.p_id", "*, date_format(DATE_ADD(v_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS v_date", "WHERE v_page='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
	$page_viewed = mysqli_num_rows($ps);

	// $ps = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date", "WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  GROUP BY order_email ORDER BY order_last_name ASC ");

	$ps = mysqli_query($dbcon,"
    SELECT *, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date FROM (
	SELECT *  FROM ms_cart 
	 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  

  UNION ALL

    SELECT *  FROM ms_cart_archive 
	 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2' 
	) 
	x 
	GROUP BY order_email ORDER BY order_last_name ASC
	");

	if (!$ps) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }


	$purchased = mysqli_num_rows($ps);
	?>
	<div class="label">
	<?php if($access > 0) { ?>
		<div><input type="checkbox" name="send_access" id="send_access" checked value="1"> <label for="send_access">People who have access to this page (<?php print $access;?>)</label> &nbsp;</div>
		<?php } ?>
		<?php if($prereg > 0) { ?>
		<div><input type="checkbox" name="send_pre_reg" id="send_pre_reg" checked value="1"> <label for="send_pre_reg">People who have pre-registered (<?php print $prereg;?>)</label>  &nbsp;</div>
		<?php } ?>
		<?php if($emailcollect > 0) { ?>
		<div><input type="checkbox" name="send_collected_emails" id="send_collected_emails" checked value="1"> <label for="send_collected_emails">Emails collected to view (<?php print $emailcollect;?>)</label>  &nbsp;</div>
		<?php } ?>

		<?php if($page_viewed > 0) { ?>
		<div><input type="checkbox" name="send_viewed" id="send_viewed" checked value="1"> <label for="send_viewed">Registered people who have viewed (<?php print $page_viewed;?>)</label>  &nbsp;</div>
		<?php } ?>
		<?php if($purchased > 0) { ?>
		<div><input type="checkbox" name="send_purchased" id="send_purchased" checked value="1"> <label for="send_purchased">People who have purchased (<?php print $purchased;?>)</label></div>
		<?php } ?>
		</div>
		<div><i>Note: if someone is in multiple categories above, they will only be sent 1 email. They will not be sent multiple emails.</i></div>
	</div>
	<?php } ?>
	<div class="underline">
	<select name="email_id" id="email_id" onchange="selectdefaultemail();">
	<option value="">Select a different default email</option>
	<?php $semails = whileSQL("ms_emails", "*", "WHERE email_id!='6' AND  email_id!='7' AND  email_id!='18' AND  email_id!='7' AND  email_id!='19' AND  email_id!='21'  AND email_id_name!='viewproofs' AND email_id_name!='viewproofsrevised' AND email_id_name!='viewproofsclosed' AND email_id_name!='viewproofsadmin' ORDER BY email_name ASC ");
	while($semail = mysqli_fetch_array($semails)) { ?>
	<option value="<?php print $semail['email_id'];?>"><?php print $semail['email_name'];?></option>
	<?php } ?>
	</select>
	</div>

	<div class="pc"><a href="" onclick="toggleid('otheremails'); return false;">Enter additional email addresses</a></div>


		<div id="otheremails" class="<?php if(count($emails) > 0) { print "hidden"; } ?>">
			<div class="underline">
				<div class="label"><h3>Enter email addresses to send to separated by a comma (,)</h3></div>
				<div><input type="text" name="emails_tos" id="emails_tos"  class="field100"></textarea></div>
			</div>
		<div>&nbsp;</div>
		</div>
	<input type="hidden" id="p_ids" value="<?php print $p_ids;?>">
	<?php 
		if($_REQUEST['email_id']>0) { 
			$em = doSQL("ms_emails", "*", "WHERE email_id='".$_REQUEST['email_id']."' ");
		} else { 
			$em = doSQL("ms_emails", "*", "WHERE email_id_name='expireemail' ");
		}
		if(empty($em['email_from_email'])) {
			$from_email = $site_setup['contact_email'];
		} else {
			$from_email = $em['email_from_email'];
		}

		if(empty($em['email_from_name'])) {
			$from_name = $site_setup['website_title'];
		} else {
			$from_name = $em['email_from_name'];
		}
		$subject = "".$em['email_subject']."";
		
		$eb = doSQL("ms_promo_codes", "*, date_format(code_end_date, '".$site_setup['date_format']." ')  AS code_end_date", "WHERE code_date_id='".$date['date_id']."' ");
		
		$to_email = $_REQUEST['email_to'];
		$to_name = stripslashes($_REQUEST['email_to_first_name'])." ".stripslashes($_REQUEST['email_to_last_name']);
		$message = $em['email_message'];


		$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
		$message = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$message");
		// $message = str_replace("[FIRST_NAME]",stripslashes($_REQUEST['email_to_first_name']), "$message");
		// $message = str_replace("[LAST_NAME]",stripslashes($_REQUEST['email_to_last_name']), "$message");
		$message = str_replace("[EXPIRATION_DATE]",$date['date_expire'], "$message");
		$message = str_replace("[EMAIL]",$to_email, "$message");
		$message = str_replace("[PAGE_TITLE]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$date['date_title']."</a>", "$message");
		$message = str_replace("[LINK_TO_PAGE]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">", "$message");
		$message = str_replace("[/LINK_TO_PAGE]","</a>", "$message");

		$message = str_replace("[EARLY_BIRD_SPECIAL_DATE]",$eb['code_end_date'], $message);
		$message = str_replace("[PAGE_LINK]","<a href=\"".$site_setup['cron_site_url'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."\">", $message);
		$message = str_replace("[/PAGE_LINK]","</a>", $message);
		$message = str_replace("[LINK_TO_WEBSITE]","<a href=\"".$site_setup['cron_site_url']."\">", $message);
		$message = str_replace("[/LINK_TO_WEBSITE]","</a>", $message);



		$message = str_replace("[LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/</a>", "$message");
		$message = str_replace("[link]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/</a>", "$message");
		$message = str_replace("[PASSWORD]",$date['password'], "$message");

		$message = str_replace("[LINK_TO_WEBSITE]","<a href=\"".$setup['url'].$setup['temp_url_folder']."/\">", "$message");
		$message = str_replace("[/LINK_TO_WEBSITE]","</a>", "$message");



	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'   AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
	if(empty($pic['pic_id'])) {
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
	}
	$pic['full_url'] = true;
	$sizel = getimagefiledems($pic,'pic_large');
	$sizes = getimagefiledems($pic,'pic_pic');
	$sizet = getimagefiledems($pic,'pic_th');
	$message = str_replace("[IMAGE_LARGE]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getimagefile($pic,'pic_large')."\" style=\"width:100%; max-width: ".$sizel[0]."px; height: auto;\"></a>", $message);
	$message = str_replace("[IMAGE_SMALL]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getimagefile($pic,'pic_pic')."\" style=\"width:100%; max-width: ".$sizes[0]."px; height: auto;\"></a>", $message);
	$message = str_replace("[IMAGE_THUMBNAIL]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getimagefile($pic,'pic_th')."\" style=\"width:100%; max-width: ".$sizet[0]."px; height: auto;\"></a>", $message);




		$subject = str_replace("[PAGE_TITLE]",$date['date_title'], "$subject");
		$subject = str_replace("[EXPIRATION_DATE]",$date['date_expire'], "$subject");
			$subject = str_replace("[EARLY_BIRD_SPECIAL_DATE]",$eb['code_end_date'], $subject);
		// $subject = str_replace("[FIRST_NAME]",stripslashes($_REQUEST['email_to_first_name']), "$subject");
		// $subject = str_replace("[LAST_NAME]",stripslashes($_REQUEST['email_to_last_name']), "$subject");
		$subject = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$subject");
	?>
	<script>
	function hidebutton() { 
		$("#submitsend").hide();
		$("#sending").show();
	}

	</script>

		
		<div>
		<div id="emailsend">
			<div class="underline">
				<div class="left p50">
					<div class="label">From Email</div>
					<div><input type="text" id="from_email" value="<?php print $from_email;?>" size="40"></div>
				</div>
				<div class="left p50">
					<div class="label">From name: </div>
					<div><input type="text" id="from_name" value="<?php print $from_name;?>" size="40"></div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="underline">
				<div class="label">Subject: </div>
				<div><input type="text" id="subject" value="<?php print $subject;?>" size="40" class="field100"></div>
			</div>
			<div class="underline">
				<div class="label">Message (note [FIRST_NAME] & [LAST_NAME] will be replaced when the email is sent). You can edit this default email in Settings > Default Emails > <?php print $em['email_name'];?>.</div> 
				<div>
					<textarea name="message" id="message" cols="40" rows="12"><?php print $message;?></textarea>
					<?php 
					$email_style = true;	
					addEditor("message", "1", "300", "1"); ?>

				</div>
			</div>
			<div id="emailcomplete" class="hidden success">Emails are on the way!</div>

			<div class="pc center">
				<input type="checkbox" id="email_confirmed" value="1"> Check to confirm sending this message 
			</div>
			<div class="pc center">
			<input type="hidden" name="date_id" value="<?php print $date['date_id'];?>">
			<input type="hidden" name="do" value="news">
			<input type="hidden" name="action" value="email">
			<input type="hidden" name="submitit" value="send">
			<input type="submit" name="submit" class="submit" value="Send Message" onClick="sendemail();" id="submitsend">
			<div id="sending" style="display: none;"><h3>SENDING....</h3></div>
			</div>
		</div>


		</div>
		<div class="clear"></div>
		</div>
		<div>&nbsp;</div>
		<div>&nbsp;</div>

</div>
<?php require "../w-footer.php"; ?>
