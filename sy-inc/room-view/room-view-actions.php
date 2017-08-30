<?php
require("../../sy-config.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html; charset=utf-8');
if($setup['ob_start_only'] == true) { 
	ob_start();  
} else { 
	if ( substr_count( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) ) {  
		ob_start( "ob_gzhandler" );  
	}  
	else {  
		ob_start();  
	}  
}

require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php";
require $setup['path']."/".$setup['inc_folder']."/show/show-functions.php";


$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$store = doSQL("ms_store_settings", "*", "");
$dshow = doSQL("ms_show", "*", "WHERE default_feat='1' ");

date_default_timezone_set(''.$site_setup['time_zone'].'');
$lang = doSQL("ms_language", "*", "");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$storelang = doSQL("ms_store_language", "*", " ");
foreach($storelang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$wdlang = doSQL("ms_wall_language", "*", " ");
foreach($wdlang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}

if($site_setup['include_vat'] == "1") { 
	$def = doSQL("ms_countries", "*", "WHERE def='1' ");
	$site_setup['include_vat_rate'] = $def['vat'];
}

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
			$_REQUEST[$id] = addslashes(stripslashes(stripslashes(strip_tags($value))));
		}
	}
}

class PseudoCrypt {
 
    /* Key: Next prime greater than 62 ^ n / 1.618033988749894848 */
    /* Value: modular multiplicative inverse */
    private static $golden_primes = array(
        '1'                  => '1',
        '41'                 => '59',
        '2377'               => '1677',
        '147299'             => '187507',
        '9132313'            => '5952585',
        '566201239'          => '643566407',
        '35104476161'        => '22071637057',
        '2176477521929'      => '294289236153',
        '134941606358731'    => '88879354792675',
        '8366379594239857'   => '7275288500431249',
        '518715534842869223' => '280042546585394647'
    );
 
    /* Ascii :                    0  9,         A  Z,         a  z     */
    /* $chars = array_merge(range(48,57), range(65,90), range(97,122)) */
    private static $chars62 = array(
        0=>48,1=>49,2=>50,3=>51,4=>52,5=>53,6=>54,7=>55,8=>56,9=>57,10=>65,
        11=>66,12=>67,13=>68,14=>69,15=>70,16=>71,17=>72,18=>73,19=>74,20=>75,
        21=>76,22=>77,23=>78,24=>79,25=>80,26=>81,27=>82,28=>83,29=>84,30=>85,
        31=>86,32=>87,33=>88,34=>89,35=>90,36=>97,37=>98,38=>99,39=>100,40=>101,
        41=>102,42=>103,43=>104,44=>105,45=>106,46=>107,47=>108,48=>109,49=>110,
        50=>111,51=>112,52=>113,53=>114,54=>115,55=>116,56=>117,57=>118,58=>119,
        59=>120,60=>121,61=>122
    );
 
    public static function base62($int) {
        $key = "";
        while(bccomp($int -1, 0) > 0) {
            $mod = bcmod($int, 62);
            $key .= chr(self::$chars62[$mod]);
            $int = bcdiv($int, 62);
        }
        return strrev($key);
    }
 
    public static function hash($num, $len = 5) {
        $ceil = bcpow(62, $len);
        $primes = array_keys(self::$golden_primes);
        $prime = $primes[$len];
        $dec = bcmod(bcmul($num, $prime), $ceil);
        $hash = self::base62($dec);
        return str_pad($hash, $len, "0", STR_PAD_LEFT);
    }
 
    public static function unbase62($key) {
        $int = 0;
        foreach(str_split(strrev($key)) as $i => $char) {
            $dec = array_search(ord($char), self::$chars62);
            $int = bcadd(bcmul($dec, bcpow(62, $i)), $int);
        }
        return $int;
    }
 
    public static function unhash($hash) {
        $len = strlen($hash);
        $ceil = bcpow(62, $len);
        $mmiprimes = array_values(self::$golden_primes);
        $mmi = $mmiprimes[$len];
        $num = self::unbase62($hash);
        $dec = bcmod(bcmul($num, $mmi), $ceil);
        return $dec;
    }
 
}
 

function saveroom() { 
	global $setup,$site_setup;

	if(!empty($_POST['pid'])) { 
		$p = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_REQUEST['pid']."' ");
	}
	if(($_REQUEST['save_as_collection'] == "1") && ($setup['demo_mode'] == true) == true) { 

	} else { 

		if($_POST['saveas'] == "new") { 
			// $key = MD5(uniqid().date('Ymdhis'));
			$key = PseudoCrypt::hash(date('Ymdhis'), 5);
			$check_key = doSQL("ms_wall_saves", "*", "WHERE wall_link='".$key."' ");
			if($check_key['wall_id'] > 0) { 
				$key = $key."xox";
			}
			insertSQL("ms_wall_saves","wall_date='".currentdatetime()."', wall_room_data='".$_REQUEST['roomdata']."', wall_items='".$_REQUEST['wallitems']."', wall_link='".$key."', wall_name='".$_REQUEST['savename']."', wall_person='".$p['p_id']."', wall_collection='".$_REQUEST['save_as_collection']."', wall_ip='".getUserIP()."', wall_no_edit='".$_REQUEST['wall_no_edit']."', wall_no_price='".$_REQUEST['wall_no_price']."', wall_date_id='".$_REQUEST['date_id']."', wall_sub_id='".$_REQUEST['sub_id']."' "); 
			print $key;
		} else { 
			if(empty($_REQUEST['wall_id'])) { die("unable to find ID"); } 
			$ck = doSQL("ms_wall_saves", "*", "WHERE wall_link='".$_REQUEST['wall_id']."' ");
			if(empty($ck['wall_id'])) { die("unable to find id"); } 
			updateSQL("ms_wall_saves","wall_date='".currentdatetime()."', wall_room_data='".$_REQUEST['roomdata']."', wall_items='".$_REQUEST['wallitems']."',  wall_name='".$_REQUEST['savename']."', wall_person='".$p['p_id']."', wall_collection='".$_REQUEST['save_as_collection']."', wall_ip='".getUserIP()."', wall_no_edit='".$_REQUEST['wall_no_edit']."' , wall_date_id='".$_REQUEST['date_id']."', wall_sub_id='".$_REQUEST['sub_id']."', wall_no_price='".$_REQUEST['wall_no_price']."' WHERE wall_id='".$ck['wall_id']."' "); 
			print $ck['wall_link'];
		}
	}
	return $key;
	if($_POST['saveandlogin'] == "1") { 
		$_SESSION['saveandlogin'] = $key;
	}
}



if($_REQUEST['action'] == "addalltocart") { 
	if(!empty($_POST['pid'])) { 
		$p = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_REQUEST['pid']."' ");
	}
	$cp_settings = doSQL("ms_canvas_settings", "*", "");
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$_REQUEST['plid']."' ");
	$key = MD5(uniqid().date('Ymdhis'));
	$wall_id = insertSQL("ms_wall_saves","wall_date='".currentdatetime()."', wall_room_data='".$_POST['roomdata']."', wall_items='".$_POST['wallitems']."', wall_link='".$key."', wall_name='".$_POST['savename']."', wall_person='".$p['p_id']."', wall_cart='1', wall_ip='".getUserIP()."',  wall_date_id='".$_REQUEST['date_id']."', wall_sub_id='".$_REQUEST['sub_id']."'   "); 
	$product_name = "Wall Collection";


	$items = explode("||",$_POST['wallitems']);
	$x = 0;
	foreach($items AS $item) { 
		$item = trim($item);
		if(!empty($item)) { 
			$x++;
			$i = explode(",",$item);
			foreach($i AS $p) { 
				$t = explode("=",$p);
				// print "\r\n<li>".$p;
				$data[trim($t[0])][$x] = trim($t[1]);
				// print "<li>XXX".$t[0]." ".$x." ".$t[1];
			}
		}	
	}


	$i = 0;
	while($i < $x) { 
		$i++;
		$pic = doSQL("ms_photos","*","WHERE pic_key='".$data['data-pic-key'][$i]."' ");
		$date = doSQL("ms_calendar", "*", "WHERE date_id='".$data['data-date-id'][$i]."' ");
		if($data['data-style-id'][$i] > 0) { 
			$style = doSQL("ms_frame_styles","*","WHERE style_id='".$data['data-style-id'][$i]."' ");
			$frame = doSQL("ms_frame_sizes","*","WHERE frame_id='".$data['data-frame-id'][$i]."' ");

			if($data['data-frame-mat-size'][$i] > 0) { 
				$cart_product_name = ($data['data-mat-print-width'][$i] * 1)." x ".($data['data-mat-print-height'][$i] * 1);
				$price = $frame['frame_mat_price'];
			} else { 
				$cart_product_name = ($data['data-show-width'][$i] * 1)." x ".($data['data-show-height'][$i] * 1);
				$price = $frame['frame_price'];
			}
			$taxable = $style['style_taxable']; 
			$no_discount = $style['style_no_discount'];
			$cart_ship = $frame['frame_shipable'];
			$cart_extra_ship = $frame['frame_add_shipping'];
		} else { 
			$cp = doSQL("ms_canvas_prints", "*", "WHERE cp_id='".$data['data-canvas-id'][$i]."' ");

			if($cp['cp_price_product'] > 0) { 
				$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$cp['cp_price_product']."' ");
				$con = doSQL("ms_photo_products_connect", "*", "WHERE pc_list='".$list['list_id']."' AND pc_prod='".$prod['pp_id']."' ");
				if($con['pc_price'] > 0) { 
					$cp['cp_opt1'] = $con['pc_price'];
				} else { 
					$cp['cp_opt1'] = $prod['pp_price'];
				}
			}

			$price = $cp['cp_opt1'];
			$taxable = $cp['cp_taxable'];
			$no_discount = $cp['cp_no_discount'];
			$cart_product_name = ($data['data-show-width'][$i] * 1)." x ".($data['data-show-height'][$i] * 1)." ".$cp['cp_name'];
			$cart_ship = $cp['cp_shipable'];
			$cart_extra_ship = $cp['cp_add_shipping'];
		}


		$cart_id = insertSQL("ms_cart", "cart_qty='1', 
		cart_room_view='".$wall_id."', 
		cart_photo_prod='99999999', 
		cart_photo_prod_connect='".$con['pc_id']."', 
		cart_product_name='".$cart_product_name."', 
		cart_sku='".addslashes(stripslashes($prod['pp_internal_name']))."', 
		cart_price='".$price."', 
		cart_ship='$cart_ship', 
		cart_download='$cart_download', 
		cart_disable_download='".$prod['pp_disable_download']."',  
		cart_session='".$_SESSION['ms_session']."' , 
		cart_client='".$_SESSION['pid']."', 
		cart_date='".date('Y-m-d H:i:s')."', 
		cart_taxable='".$taxable."', 
		cart_ip='".getUserIP()."' , 
		cart_pic_id='".$pic['pic_id']."', 
		cart_pic_date_id='".$data['data-date-id'][$i]."', 
		cart_pic_org='".addslashes(stripslashes($pic['pic_org']))."', 
		cart_pic_date_org='".addslashes(stripslashes($date['date_title']))."', 
		cart_cost='".$prod['pp_cost']."', 
		cart_color_id='".$color['color_id']."', 
		cart_color_name='".addslashes(stripslashes($color['color_name']))."', 
		cart_sub_gal_id='".$data['data-sub-id'][$i]."', 
		cart_group_id='".$group['group_id']."', 
		cart_allow_notes='".$list['list_allow_notes']."', 
		cart_dis_on='".$dis_on."' $add_for_package, 
		cart_min_order='".$list['list_min_order']."', 
		cart_extra_ship='".$cart_extra_ship."', 
		cart_no_discount='".$no_discount."', 
		cart_photo_bg='".$bgphoto['pic_id']."',
		cart_package_photo_extra_on='".$cart_package_photo_extra_on."', 
		
		cart_frame_style='".$data['data-style-id'][$i]."',
		cart_frame_size='".$data['data-frame-id'][$i]."', 
		cart_frame_image='".$data['data-color-id'][$i]."',
		cart_mat_size='".$data['data-frame-mat-size'][$i]."', 
		cart_canvas_id='".$data['data-canvas-id'][$i]."', 
		cart_mat_color='".$data['data-mat-color'][$i]."'
		
		
		");

		### GET THE IMAGE OPTIONS ###### 

		if(!empty($_REQUEST['imageoptions'])) { 
			$iops = explode("|",$_REQUEST['imageoptions']);
			foreach($iops AS $iop) { 
				if(!empty($iop)) { 
					$o++;
					$topt = explode(",",$iop);
					if($topt[1] == $pic['pic_key']) { 
						print "<li>ADD THIS";
						$opt = doSQL("ms_image_options","*","WHERE opt_id='".$topt[0]."' ");
						if(!empty($opt['opt_id'])) { 
							insertSQL("ms_cart_options", "co_opt_id='".$opt['opt_id']."', 
							co_discountable='".$opt['opt_discountable']."' , 
							co_opt_name='".addslashes(stripslashes($opt['opt_name']))."', 
							co_price='".$opt['opt_price']."', 
							co_cart_id='".$cart_id."', 
							co_pic_id='".$pic['pic_id']."', 
							co_taxable='".$opt['opt_taxable']."' ");
						}					
					}
				}
			}
		}
	}


	print $key;
	exit();
}

if($_REQUEST['action'] == "deletesaved") { 
	$ck = doSQL("ms_wall_saves", "*", "WHERE wall_link='".$_REQUEST['walllink']."' ");
	if(!empty($ck['wall_id'])) { 
		deleteSQL("ms_wall_saves", "WHERE wall_id='".$ck['wall_id']."'","1");
	}
	exit();
}
if($_REQUEST['action'] == "mysaved") { 
	$walls = whileSQL("ms_wall_saves", "*,date_format(wall_date, '".$site_setup['date_format']." %h:%i %p ')  AS wall_date_show", "WHERE MD5(wall_person)='".$_REQUEST['pid']."' AND wall_cart<='0' AND wall_collection<='0' ORDER BY wall_date DESC");
	if(mysqli_num_rows($walls) <= 0) { ?>
	<div class="pc center"><?php print _wd_you_have_no_saved_collections_;?></div>

	<?php } else { ?>

	
	<?php
	while($wall = mysqli_fetch_array($walls)) { ?>
	<div id="mysaved-<?php print $wall['wall_link'];?>">
	<div class="pc underline"><a href="javascript:deletesavedwall('<?php print $wall['wall_link'];?>');" onClick="return confirm('Are you sure you want to delete it?');" class="the-icons icon-trash-empty"></a> <a href="<?php print $setup['temp_url_folder']."/index.php?wd=".$wall['wall_link'];?>"><b><?php print $wall['wall_name'];?></b><br><?php print $wall['wall_date_show'];?></a>
	</div>
	<div>&nbsp;</div>
	</div>
	<?php } ?>
	
	<?php } 
	exit();
}



if($_POST['action'] == "saveroom") { 
	$key = saveroom();
	if($_POST['saveandlogin'] == "1") { 
		$_SESSION['saveandlogin'] = $key;
	}
	exit();
}

if($_REQUEST['action'] == "getframestyleoptions") { 
	if(!is_numeric($_REQUEST['styleid'])) { die(); } 
	$frames = whileSQL("ms_frame_sizes", "*", "WHERE frame_style='".$_REQUEST['styleid']."' ORDER BY frame_order ASC ");
	$style = doSQL("ms_frame_styles", "*", "WHERE style_id='".$_REQUEST['styleid']."' ");
	?>
	<div id="frame-options-<?php print $style['style_id'];?>" class="frameoptions">
	<div style="margin-bottom: 8px;">
	<ul style="display: inline; ">
	<?php
	while($frame = mysqli_fetch_array($frames)) { 
		if(($style['style_taxable'] == "1") && ($site_setup['include_vat'] == "1")==true) { 
			$frame['frame_price'] = $frame['frame_price'] + (($frame['frame_price'] * $site_setup['include_vat_rate']) / 100);
			$frame['frame_mat_price'] = $frame['frame_mat_price'] + (($frame['frame_mat_price'] * $site_setup['include_vat_rate']) / 100);
		}
		?>
		<li><a id="frame-<?php print $style['style_id'];?>-<?php print $frame['frame_id'];?>" class="frameselections" 
		data-mat-width="<?php print $frame['frame_mat_width'];?>" 
		data-frame-width="<?php print $frame['frame_width'];?>" 
		data-frame-height="<?php print $frame['frame_height'];?>" 
		data-frame-price="<?php print $frame['frame_price'];?>" 
		data-frame-mat-price="<?php print $frame['frame_mat_price'];?>" 
		data-mat-print-width="<?php print $frame['frame_mat_print_width'];?>"
		data-mat-print-height="<?php print $frame['frame_mat_print_height'];?>"

		href=""  onclick="changeframe('<?php print $style['style_frame_image'];?>','<?php print $frame['frame_width'];?>','<?php print $frame['frame_height'];?>','<?php print $style['style_frame_width'];?>','','<?php print $style['style_frame_corners'];?>','<?php print $frame['frame_id'];?>','<?php print $frame['frame_style'];?>','','<?php print $frame['frame_price'];?>','0','1'); return false;"><?php print ($frame['frame_width'] + 0);?>x<?php print ($frame['frame_height']+ 0);?></a><li>


	<?php } ?>
	</ul>
</div>
<?php 
$colors = whileSQL("ms_frame_images", "*", "WHERE img_style='".$style['style_id']."' ORDER BY img_order ASC ");
if(mysqli_num_rows($colors) > 1) { 
?>
	<div style="margin-bottom: 8px; display: inline;" >
	<ul id="frame-colors-<?php print $style['style_id'];?>" style="display: inline; "><li><?php print _wd_frame_color_;?></li>
<?php 
while($color = mysqli_fetch_array($colors)) {
	if(empty($color['img_corners'])) { 
		$corners = $style['style_frame_corners'];
	} else { 
		$corners = $color['img_corners'];
	}

	$bgsizes = explode(",",$corners);
	
	?><li><span  id="framecolor-<?php print $style['style_id'];?>-<?php print $color['img_id'];?>" class="framecolorselections"  onclick="changeframecolor('<?php print $color['img_id'];?>','<?php print $setup['temp_url_folder'].$color['img_small'];?>','<?php print $corners;?>');  return false;"  style="height: 20px; width: 20px; background-image: url('<?php print $setup['temp_url_folder'].$color['img_small'];?>'); background-size: <?php print (100 / $bgsizes[0]) * 100;?>%; display: inline-block">&nbsp;</span></li>

<?php } ?>
</ul></div>
<?php } ?>
	
		<?php if(!empty($style['style_mat_colors'])) { ?>
		<div style="margin-bottom: 8px; display: inline;">
		<ul id="mat-options-<?php print $style['style_id'];?>" style="display: inline; ">
		<li><?php print _wd_matting_;?></li>
		<?php 
		$matcolors = explode(",",$style['style_mat_colors']);
		$mats = whileSQL("ms_frame_mat_colors", "*", "ORDER BY color_order ASC ");
		while($mat = mysqli_fetch_array($mats)) { 
			if(in_array($mat['color_id'],$matcolors)) { 	
				?>
		<li><span  id="matcolor-<?php print $style['style_id'];?>-<?php print $mat['color_color'];?>" class="matcolorselections" style="width: 20px; height: 20px; display: inline-block; border: solid 1px #d4d4d4; background: #<?php print $mat['color_color'];?>;" onclick="changemat('<?php print $mat['color_color'];?>','<?php print $mat['color_id'];?>'); return false;">&nbsp;</span></li>

		<?php }
		} ?>
		<li><a href="" onclick="removemat(); return false;"><?php print _wd_no_mat_;?></a></li>
		</ul>

		</div>
		<?php } ?>
	<div class="clear"></div>
	</div>
	<?php 
}


if($_REQUEST['action'] == "saveframeadjust") { 
	if($setup['demo_mode'] !== true) { 

		$style = doSQL("ms_frame_styles", "*"," WHERE style_id='".$_REQUEST['styleid']."' ");

		if(($_REQUEST['setasdefault'] =="1") || (empty($style['style_frame_corders'])) == true) { 
			updateSQL("ms_frame_styles", "style_frame_corners='".$_REQUEST['corners']."' WHERE style_id='".$_REQUEST['styleid']."' ");
		}
		updateSQL("ms_frame_images", "img_corners='".$_REQUEST['corners']."' WHERE img_id='".$_REQUEST['imgid']."' ");
	}
	exit();
}

if($_REQUEST['action'] == "saveroommeasurement") { 
	$room = doSQL("ms_wall_rooms", "*", "WHERE room_small='".$_REQUEST['photourl']."' ");
	if(!empty($room['room_id'])) { 
		updateSQL("ms_wall_rooms", "room_width='".$_REQUEST['total']."' WHERE room_id='".$room['room_id']."' ");
	}
	exit();
}

if($_REQUEST['action'] == "deleteroomphoto") { 
	$room = doSQL("ms_wall_rooms", "*", "WHERE room_id='".$_REQUEST['room_id']."' AND room_person='".$_REQUEST['pid']."' ");
	if($room['room_id'] > 0) { 
		deleteSQL("ms_wall_rooms", "WHERE room_id='".$room['room_id']."' ","1");
		if(file_exists($setup['path'].$room['room_small'])) { 
			unlink($setup['path'].$room['room_small']);
		}
	}
	exit();
}

if($_REQUEST['action'] == "addtocartoptions") { 
	if(($_REQUEST['listid'] > 0) && (!is_numeric($_REQUEST['listid'])) == true) { die("Invalid ID"); } 
	if($_REQUEST['totalphotofiles'] < $_REQUEST['totalitems']) { ?>
	<div class="pc center"><h2><?php print _wd_select_photos_error_title_;?></h2></div>
	<!-- <div class="pc center"><?php print $_REQUEST['totalitems'];?> items</div> -->
	<div class="pc"><?php print _wd_select_photos_error_text_;?></div>

	<?php 
	exit();
	}
	?>
	
	<!-- <div class="pc center"><h2><?php print showPrice($_REQUEST['subtotal']);?></h2></div> -->
	<div class="pc center"><h2><?php print _wd_review_your_photos_;?></h2></div>
<!-- <div class="pc center"><?php print $_REQUEST['totalitems'];?> items</div> -->
<div class="pc"><?php print _wd_review_your_photos_text_;?></div>
	<?php 
	$pics = explode("|",$_REQUEST['files']);
	foreach($pics AS $p) { 
		if(!empty($p)) { 
			$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$p."' ");
			$photo_file = getimagefile($pic,'pic_pic');
			?>
			<div class="pc">
				<div><img src="<?php print $photo_file;?>" style="margin:0px 0px 12px 0px; width: 100%;"></div>
				<div><?php print $pic['pic_org'];?></div>
			</div>
			<?php 
			$list = doSQL("ms_photo_products_lists","*","WHERE list_id='".$_REQUEST['listid']."' ");
			$iopts = whileSQL("ms_image_options", "*", "WHERE opt_list='".$list['list_id']."' $and_opt ORDER BY opt_id ASC ");

			while($iopt = mysqli_fetch_array($iopts))  { ?>
				<div  class="pc" style="margin-bottom: 16px;">
				<?php 
				
				$ckopt = doSQL("ms_cart LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=ms_cart.cart_id", "*", "WHERE  ".checkCartSession()." AND  cart_order<='0'  AND co_pic_id='".$pic['pic_id']."' AND co_opt_id='".$iopt['opt_id']."' ");

					$iopt_price = $iopt['opt_price'] + (($iopt['opt_price'] * $site_setup['include_vat_rate']) / 100);
			?>
			
					<div class="ioptselected<?php print $iopt['opt_id'];?> <?php if(empty($ckopt['co_id'])) { ?>hide<?php } ?>"><?php print $iopt['opt_name'];?> - <?php print _image_option_selected_;?></div>

					<div class="ioptselect<?php print $iopt['opt_id'];?> <?php if(!empty($ckopt['co_id'])) { ?>hide<?php } ?>">
					
					<div>
					
					<input type="checkbox" class="imageoption" id="opt-<?php print $iopt['opt_id'];?>-<?php print $pic['pic_key'];?>" data-pic="<?php print $pic['pic_key'];?>"  data-opt-id="<?php print $iopt['opt_id'];?>" value="1"> <label for="opt-<?php print $iopt['opt_id'];?>-<?php print $pic['pic_key'];?>"><?php print $iopt['opt_name'];?><?php if($iopt['opt_price'] > 0) { print " +".showPrice($iopt_price); } ?></label>
					
					</div>
						<div><?php print nl2br($iopt['opt_descr']);?></div>

					</div>
				</div>
			<?php } 
		}
		print "<div>&nbsp;</div>";
	}
?>

<div class="pc center"><a href="" onclick="saveroom('0','1'); return false;" class="the-icons icon-basket checkout" style="text-shadow: none; width: 100%; display: block; box-sizing: border-box;"><?php print _add_to_cart_;?></a></div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>

		<?php 
	exit();
}

if($_REQUEST['action'] == "getcarttotal") { 

	$total = shoppingCartTotal($mssess);
	?>
<div class="pc center"><h2><?php print _wd_added_to_cart_;?></h2></div>
<!-- <div class="pc center"><h3><?php print $total['total_items']." items / ".showPrice($total['show_cart_total']);?></h3></div> -->
<div class="pc" id="viewcartminilinks">
<div class="center viewcartminilinks"><a href="/<?php print $site_setup['index_page'];?>?view=cart" onClick="viewcart(); return false;"><?php print _view_cart_;?></a>  
	<?php 
	if(!empty($_SESSION['last_gallery'])) { 
		$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
		if($date['date_gallery_exclusive'] == "1") { 
			$ge_return_link = $setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."/";
		}
	}
	if(!empty($ge_return_link)) { ?>
	<a href="<?php print gotosecure();?><?php print $ge_return_link;?>?view=checkout"><?php print _checkout_;?></a>
	<?php	} else {  ?>
	<a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=checkout"><?php print _checkout_;?></a>
	<?php } ?>

	<a href="" onclick="closesstuffwindow(); return false;"><?php print _compare_close_;?></a>

	</div>
	<?php 

}

if($_REQUEST['action'] == "getwallcollections") { 
	if(isset($_SESSION['pid'])) { 
		$p = doSQL("ms_people","*","WHERE MD5(p_id)='".$_SESSION['pid']."' ");
		if($p['p_id'] > 0) { 
			$room_photos = doSQL("ms_wall_rooms", "*", "WHERE room_width>'0' AND room_person='".$p['p_id']."' ORDER BY room_id DESC  ");
		}
	}
	if(empty($room_photos['room_id'])) { 
		$room_photos = doSQL("ms_wall_rooms", "*", "WHERE room_person<='0' ORDER BY room_order ASC ");
	}
	$room_photo = $setup['temp_url_folder'].$room_photos['room_large'];
	$data_room_photo_width = $room_photos['room_photo_width'];
	$data_room_photo_height = $room_photos['room_photo_height'];
	$data_room_width = $room_photos['room_width'];
	$data_center = $room_photos['room_center'];
	$data_base = $room_photos['room_base'];

// Local:  
$collages = array("152","151","153");

// Dev site
// $collages = array("122","123","124");

$collages = whileSQL("ms_wall_saves","*","WHERE wall_collection='1' ORDER BY wall_collection_order ASC ");
while($col = mysqli_fetch_array($collages)) { 
	$cn++;
	?>

	<div id="roompreviewcontain-<?php print $cn;?>" class="roompreviewcontain" style="width: 100%; max-width: 300px; cursor: pointer;" data-room-photo-width="<?php print $data_room_photo_width;?>" data-room-photo-height="<?php print $data_room_photo_height;?>" data-room-width="<?php print $data_room_width;?>" data-center="<?php print $data_center;?>" data-base="<?php print $data_base;?>" onclick="selectcollagenew('<?php print $cn;?>');">
		
		<div class="roompreviewbackground" id="roompreviewbackground-<?php print $cn;?>"><img src="<?php print $room_photo;?>" style="width: 100%; max-width: <?php print $data_room_photo_width;?>px; height: auto;"></div>
			<?php 

				$items = explode("||",$col['wall_items']);
				$x = 0;
				foreach($items AS $item) { 
					$item = trim($item);
					if(!empty($item)) { 
						$x++;
						$i = explode(",",$item);
						foreach($i AS $p) { 
							$t = explode("=",$p);
							// print "\r\n<li>".$p;
							$data[trim($t[0])][$x] = trim($t[1]);
							// print "<li>XXX".$t[0]." ".$x." ".$t[1];
						}
					}	
				}

					$i = 0;
					while($i < $x) { 

					$i++;
					$p = explode(",",$print);

					if($data['data-frame-id'][$i] > 0) { 
						$frame = doSQL("ms_frame_sizes", "*", "WHERE frame_id='".$data['data-frame-id'][$i]."' ");
						if($data['data-frame-mat_size'][$i] > 0) { 
							$price = $frame['frame_mat_price'];
						} else { 
							$price = $frame['frame_price'];
						}
					}
					if($data['data-canvas-id'][$i] > 0) { 
						$canvas = doSQL("ms_canvas_prints", "*", "WHERE cp_id='".$data['data-canvas-id'][$i]."' ");
						$price = $canvas['cp_opt1'];
					}


					// $pic = doSQL("ms_photos", "*", "WHERE pic_key='".$photos[$i]."' ");
					?>
						<div id="roomphotocontainer-<?php print $i; ?>" class="roomphotocontainer hide <?php if($data['data-frame-id'][$i] <=0) { ?>collageshadow<?php } ?>"  
						data-width="<?php print $data['data-width'][$i];?>" 
						data-height="<?php print $data['data-height'][$i];?>" 
						data-show-width="<?php print $data['data-show-width'][$i];?>" 
						data-show-height="<?php print $data['data-show-height'][$i];?>" 
						data-from-center="<?php print $data['data-from-center'][$i];?>" 
						data-from-base="<?php print $data['data-from-base'][$i];?>" 
						data-photo-number="<?php print $i; ?>" 
						data-price="<?php print $price;?>"  
						data-color-id="<?php print $data['data-color-id'][$i];?>" 
						data-style-id="<?php print $data['data-style-id'][$i];?>" 
						data-canvas-id="<?php print $data['data-canvas-id'][$i];?>" 
						data-frame-id="<?php print $data['data-frame-id'][$i];?>"  
						data-frame-file="<?php print $data['data-frame-file'][$i];?>"  
						data-print-product-id="<?php print $data['data-print-product-id'][$i];?>"  
						data-frame-mat-size="<?php print $data['data-frame-mat-size'][$i];?>"  
						data-mat-color="<?php print $data['data-mat-color'][$i];?>"
						data-mat-color-id="<?php print $data['data-mat-color-id'][$i];?>"
						data-frame-width="<?php print $data['data-frame-width'][$i];?>" 
						data-mat-print-width="<?php print $data['data-mat-print-width'][$i];?>" 
						data-mat-print-height="<?php print $data['data-mat-print-height'][$i];?>" 
						data-frame-corners="<?php print $data['data-frame-corners'][$i];?>" 
						data-canvas-depth="<?php print $data['data-canvas-depth'][$i];?>" 
						data-canvas-edge="<?php print $data['data-canvas-edge'][$i];?>" 
						data-default="<?php print $data['data-default'][$i];?>" 
						<?php 
						if(!empty($data['data-pic-key'][$i])) { 
							//$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$data['data-pic-key'][$i]."' "); 
							// $size = getimagefiledems($pic,"pic_pic");
						} 
						?>

						<?php if($data['data-frame-id'][$i] > 0) { 
							$corner = explode("-",$data['data-frame-corners'][$i]);
						?> style="border-image: url('<?php print $data['data-frame-file'][$i];?>') <?php print $corner[0];?>% <?php print $corner[1];?>% <?php print $corner[2];?>% <?php print $corner[3];?>%  round round;"
						<?php } ?>>
							<div id="roomphotomatte-<?php print $i; ?>" class="roomphotomatte <?php if($data['data-frame-mat-size'][$i] > 0) { ?>matshadow<?php } ?>" style="background: #<?php print $data['data-mat-color'][$i];?>;">
								<div id="roomphoto-<?php print $i; ?>" class="roomphoto <?php if($data['data-frame-id'][$i] > 0) { ?>photoshadow<?php } ?>" 
								style="" 
								data-photo-file="<?php  print getimagefile($pic,'pic_pic'); ?>" data-pic-key="<?php  print $pic['pic_key']; ?>" 
								data-bw="<?php print $data['data-bw'][$i];?>"  
								data-pic-width="<?php print $size[0];?>" 
								data-pic-height="<?php print $size[1];?>" 
								data-y-pos="<?php print $data['data-y-pos'][$i];?>" 
								data-x-pos="<?php print $data['data-x-pos'][$i];?>" 
								data-date-id="<?php print $data['data-date-id'][$i];?>"  
								data-sub-id="<?php print $data['data-sub-id'][$i];?>"
								data-zoom="<?php print $data['data-zoom'][$i];?>"
								>
								</div>
							</div>
						</div>

					<?php 
				}
			?>
		
		</div>
	<?php if(isset($_SESSION['office_admin_login'])) { ?>
	<div class="pc center"><a href="index.php?view=room&rw=<?php print $col['wall_link']?>" class="the-icons icon-pencil">Edit</a> <a href="javascript:deletewallcollage('<?php print $col['wall_link'];?>');" onClick="return confirm('Are you sure you want to delete it?');" class="the-icons icon-trash-empty">Delete</a></div>
	<div class="pc center" style="font-size: 12px;"><i>The edit / delete options are only available because you are logged into the admin.</i></div>
	<?php } ?>

	<div>&nbsp;</div>
<?php } ?>




	<div>&nbsp;</div>


<?php } ?>