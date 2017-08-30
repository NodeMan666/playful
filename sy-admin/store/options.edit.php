<?php
if($_REQUEST['action'] == "deleteOption") { 
	$opt = doSQL("ms_product_options", "*", "WHERE opt_id='".$_REQUEST['opt_id']."' "); 
	if(!empty($opt['opt_id'])) { 
		deleteSQL("ms_product_options", "WHERE opt_id='".$opt['opt_id']."' ", "1");
		deleteSQL2("ms_product_options_sel", "WHERE sel_opt='".$opt['opt_id']."' ");
	}
	$_SESSION['sm'] = "Option deleted";
	session_write_close();
	if($opt['opt_date'] > 0) { 
		header("location: index.php?do=news&action=addDate&date_id=".$opt['opt_date']."");
	} else { 
		header("location: index.php?do=photoprods&view=base");
	}
	exit();

}
?>

<script>


function editoption(opt_date,opt_photo_prod,opt_id,package_id) { 
	pagewindowedit("w-product-options.php?do=editOption&noclose=1&nofonts=1&nojs=1&opt_photo_prod="+opt_photo_prod+"&opt_date="+opt_date+"&opt_id="+opt_id+"&opt_package="+package_id);
}


function copyoption(opt_date,opt_photo_prod,opt_id,pp_type) { 
	windowloading();
	$("#windowedit").css({"top":$(window).scrollTop()+50+"px"});
		$.get("w-product-options.php?do=copyOption&noclose=1&nofonts=1&nojs=1&opt_photo_prod="+opt_photo_prod+"&opt_date="+opt_date+"&opt_id="+opt_id+"&pp_type="+pp_type, function(data) {
			$("#windoweditinner").html(data);
			$("#windowedit").slideDown(200, function() { 
				$("#windoweditclose").show();
				windowloadingdone();
			});
		});
}


</script>

