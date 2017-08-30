<?php
$cat_id = $billboard['bill_cat'];
$billboard_ss_id = "ssbill_".$cat_id."";
$time = strtotime("".$site_setup['time_diff']." hours");
$cur_time =date('Y-m-d H:i:s', $time);
$prev_len = "200";
$blimit = $billboard['bill_limit'];
if($date['page_bill'] > 0) { 
	$bqry = "WHERE  date_id='".$date['page_bill']."' ";
} else { 
	$bqry = "WHERE  date_public='1' AND date_type='news'  AND  (ms_calendar.date_cat='$cat_id' OR ms_blog_cats_connect.con_cat='$cat_id' $and_sub_cat )  AND page_under='0'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND  private<='1'   AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) GROUP BY date_id   ORDER BY date_date DESC,date_time DESC  LIMIT $blimit";
}

	$bdates = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." %h:%i %p')  AS last_modified , date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "$bqry");
	$billboard_slides = mysqli_num_rows($bdates);

	if(($billboard['bill_cat'] > 0)||($billboard['bill_page'] > 0)==true) {
		if($_REQUEST['previewBillboard'] > 0) { 
			$billboard_slides = 1;
		}
	}
?>

<div id="billboardOuter">
	<div id="billboardContainer">
		<div id="billboard">
		<div id="neatbb">
			<div id="neatbbslides" data-parallax="<?php print $billboard['bill_parallax'];?>" cbb="" timeron="1" numslides="<?php print $billboard_slides;?>"  bbheight="<?php print $billboard['bill_height'];?>" bbwidth="<?php print $billboard['bill_width'];?>" bbspeed="<?php print $billboard['bill_trans_time'];?>" bbtime="<?php print $billboard['bill_seconds']*1000;?>" loopslides="<?php if($billboard['bill_loop'] == "1") { ?>true<?php } else { ?>false<?php } ?>" trans="<?php print $billboard['bill_transition'];?>" innermaxwidth="<?php print $css['page_width_max'];?>" bh="<?php print $billboard['bill_height'];?>" bill_placement = "<?php print $billboard['bill_placement'];?>">
		<div id="loadingbb"><div  class="loadingspinner"></div></div>

	<?php if(!empty($billboard['bill_content_row1'])) { print $billboard['bill_content_row1']; } ?>

	<?php 
	$s = 1;
		if(($billboard['bill_cat'] > 0)||($billboard['bill_page'] > 0)==true) {
			if($_REQUEST['previewBillboard'] > 0) { 
			$dsize = @GetImageSize("".$setup['path']."/".$setup['manage_folder']."/graphics/photo-large.jpg"); 
			if($billboard['bill_burns'] == "1") { 
				if($s%2) { 
					$burns = "burnsout";
				} else { 
					$burns = "burnsin";
				}
			}
			?>
		<div id="slide1"  data-effect="<?php print $burns;?>" class="<?php if($s == 1) { print "neatbbslide"; } else { print "neatbbslide"; } ?> <?php if($s == "1") { print $burns; } ?>" <?php print $cl; ?>>
			<?php if(!empty($slide['slide_link'])) { print "<a href=\"".$slide['slide_link']."\" style=\"cursor: pointer;\">"; } ?><img id="slide1g" src="<?php print $setup['temp_url_folder']."/".$setup['manage_folder'];?>/graphics/photo-large.jpg" data-margin-top="null" width="<?php print $dsize[0];?>" height="<?php print $dsize[1];?>" border="0" ww="<?php print $dsize[0];?>" hh="<?php print $dsize[1];?>" class="billboardphoto "  <?php print $cl; ?>><?php if(!empty($slide['slide_link'])) { print "</a>"; } ?>
		</div>
	<?php } 
	}
	?>

	<?php 
	$s = 1;


	while($bdate = mysqli_fetch_array($bdates)) { 
		$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$bdate['date_cat']."' ");



		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$bdate['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
		if(!empty($pic['pic_id'])) {
			$dsize = getimagefiledems($pic,'pic_large');
			$img = getimagefile($pic,'pic_large');
		} else { 
			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$bdate['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
			if(!empty($pic['pic_id'])) {
				$dsize = getimagefiledems($pic,'pic_large');
				$img = getimagefile($pic,'pic_large');
			}
		}
		$cl = "";

		if($billboard['bill_burns'] == "1") { 
			if($s%2) { 
				$burns = "burnsout";
			} else { 
				$burns = "burnsin";
			}
		}

		?>

		<div id="slide<?php print $s;?>" data-effect="<?php print $burns;?>" class="<?php if($s == 1) { print "neatbbslide"; } else { print "neatbbslide"; } ?> <?php if($s == "1") { print $burns; } ?>" >
			<?php if($date['page_bill'] <= 0) {  print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder'].''.$cat['cat_folder'].'/'.$bdate['date_link']."\" style=\"cursor: pointer;\">"; } ?><img id="slide<?php print $s;?>g" src="<?php print $img;?>" width="<?php print $dsize[0];?>" height="<?php print $dsize[1];?>" border="0" ww="<?php print $dsize[0];?>" hh="<?php print $dsize[1];?>" class="billboardphoto"><?php print "</a>";  ?>
		</div>
		<?php 
			$s++;
	}
	?>

	</div>
	<?php if(($billboard['bill_show_nav'] == "1") &&($billboard_slides > 1)==true){ ?>
	<div id="neatbbmenu" class="hidesmall">
	<ul class="slidenav">
	<?php 
	$x = 1;
	while($x <= $billboard_slides) { ?>
	<li onClick="neatbbswapclick('<?php print $x;?>'); return false;" id="bn<?php print $x;?>"  class="<?php if($x == 1) { print "bnon"; } ?>" ><?php // print $x;?></li> 
	<?php $x++;
	} ?>
	</ul>
	</div>
	<?php } ?>
	<div id="billtext" style="position: absolute; top: 0; left: 0; text-align: left; z-index: 2;  width: 100%; margin: auto; " >

	<?php
	$s = 0;
	$bdates = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." %h:%i %p')  AS last_modified , date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "$bqry");
	while($bdate = mysqli_fetch_array($bdates)) { 
		$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$bdate['date_cat']."' ");

	$s++;
	?>

			

<?php 
	$slide = doSQL("ms_billboard_slides", "*", "WHERE slide_billboard='".$billboard['bill_id']."' ORDER BY slide_order ASC");

	if(!empty($bdate['date_snippet'])) { 
		$snippet .= "".nl2br($bdate['date_snippet']).""; 
	} else { 
		if($prev_len > 0) { 
			if(strlen($bdate['date_text']) > $prev_len) { 
				$sub_descr = strip_tags($bdate['date_text']);
				$sub_descr = preg_replace('/\s\s+/', ' ', $sub_descr);
				$sub_descr = (substr_replace(strip_tags(trim($sub_descr)), "", $prev_len). "");
				$sub_descr = str_replace('"',"",$sub_descr);
				$sub_descr = str_replace('&nbsp;'," ",$sub_descr);
				$sub_descr = trim($sub_descr);
				$snippet .= "$sub_descr ......"; 
			} else {
				$snippet .= "".$bdate['date_text'].""; 
			}
		} else { 
			$snippet .= "".$bdate['date_text'].""; 
		}
		if($snippet == "<br>") { 
			$snippet = "";
		}
	}

	$slide['slide_text1'] = str_replace("[TEXT]",$snippet,$slide['slide_text1']);
	$slide['slide_text1'] = str_replace("[TITLE]",$bdate['date_title'],$slide['slide_text1']);
	unset($snippet);
	$texts = explode("|:|", $slide['slide_text1']);
	foreach($texts AS $ss1) { 
		if(!empty($ss1)) { 
			$ssx = explode("||", $ss1);
			foreach($ssx AS $vals1) {
				list($id1,$v1) = explode("::",$vals1);
				$valt[$id1] = $v1;
				//print "<li>$id1 = $v1";
			}
		 if($date['page_bill'] <= 0) {		
			$cl =  'onClick=location.href="'.$setup['temp_url_folder'].''.$setup['content_folder'].''.$cat['cat_folder'].'/'.$bdate['date_link'].'" style="cursor: pointer;" '; 
		 }
			$h .= '<div id="billtext'.$s.''.$valt['id'].'" textid="'.$valt['id'].'" style="position: absolute; left: '.$valt['x'].'%; top: '.$valt['y'].'%; display: none;" tp='.$valt['y'].' lp='.$valt['x'].'  class="billtextinner"><div id="text'.$s.''.$valt['id'].'" textid="'.$valt['id'].'" color="'.$valt['color'].'" font-family="'.$valt['font-family'].'" font-size="'.$valt['font-size'].'" font-weight="'.$valt['font-weight'].'" font-style="'.$valt['font-style'].'" text-shadow-h="'.$valt['text-shadow-h'].'" text-shadow-v="'.$valt['text-shadow-v'].'" text-shadow-b="'.$valt['text-shadow-b'].'" text-shadow-c="'.$valt['text-shadow-c'].'" slide_text_1_time="'.$valt['slide_text_1_time'].'" slide_text_1_effect="'.$valt['slide_text_1_effect'].'" x="'.$valt['x'].'" y="'.$valt['y'].'" class="thebbtext'.$s.'" style="color: #'.$valt['color'].'; font-size: '.$valt['font-size'].'px; font-family: '.$valt['font-family'].'; font-weight: '.$valt['font-weight'].'; font-style: '.$valt['font-style'].'; text-shadow: '.$valt['text-shadow-h'].'px '.$valt['text-shadow-v'].'px '.$valt['text-shadow-b'].'px #'.$valt['text-shadow-c'].'; float: left;" ><span '.$cl.'>'.$valt['text'].'</span></div></div>';
			unset($cl);
		}
	}
	print $h;
	unset($h);
	?>



<?php } ?>
	</div>
</div></div></div>
</div>
