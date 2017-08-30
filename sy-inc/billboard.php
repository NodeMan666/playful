<?php
$billboard_ss_id = "ssbill_".$billboard['bill_id']."";
$billboard_slides = 	countIt("ms_photos LEFT JOIN ms_billboard_slides ON ms_photos.pic_id=ms_billboard_slides.slide_pic",  "WHERE slide_billboard='".$billboard['bill_id']."' ");

?>
<div id="billboardOuter">
	<div id="billboardContainer">
		<div id="billboard">
		<div id="neatbb">
			<div id="neatbbslides" data-parallax="<?php print $billboard['bill_parallax'];?>" cbb="" timeron="1" numslides="<?php print $billboard_slides;?>"  bbheight="<?php print $billboard['bill_height'];?>" bbwidth="<?php print $billboard['bill_width'];?>" bbspeed="<?php print $billboard['bill_trans_time'];?>" bbtime="<?php print $billboard['bill_seconds']*1000;?>" loopslides="<?php if($billboard['bill_loop'] == "1") { ?>true<?php } else { ?>false<?php } ?>" trans="<?php print $billboard['bill_transition'];?>" innermaxwidth="<?php print $css['page_width_max'];?>" bh="<?php print $billboard['bill_height'];?>" bill_placement = "<?php print $billboard['bill_placement'];?>">
		<div id="loadingbb"><div  class="loadingspinner"></div></div>

	<?php  ## Add static content here ### ?>
	<?php if(!empty($billboard['bill_content_row1'])) { print $billboard['bill_content_row1']; } ?>
	<?php 
		if(($billboard['bill_cat'] > 0)||($billboard['bill_page'] > 0)==true) {
			$dsize = @GetImageSize("".$setup['path']."/".$setup['manage_folder']."/graphics/photo-large.jpg"); 
			?>
		<div id="slide1"  data-effect="<?php print $burns;?>" class="<?php if($s == 1) { print "neatbbslide"; } else { print "neatbbslide"; } ?> <?php if($s == "1") { print $burns; } ?>" <?php print $cl; ?>>
			<?php if(!empty($slide['slide_link'])) { print "<a href=\"".$slide['slide_link']."\" style=\"cursor: pointer;\">"; } ?><img id="slide<?php print $s;?>g" src="<?php $setup['temp_url_folder']."/".$setup['manage_folder'];?>/graphics/photo-large.jpg" data-margin-top="null" width="<?php print $dsize[0];?>" height="<?php print $dsize[1];?>" border="0" ww="<?php print $dsize[0];?>" hh="<?php print $dsize[1];?>" class="billboardphoto "  <?php print $cl; ?>><?php if(!empty($slide['slide_link'])) { print "</a>"; } ?>
		</div>
	<?php } ?>
	<?php 
	$s = 1;
	$slides = whileSQL("ms_photos LEFT JOIN ms_billboard_slides ON ms_photos.pic_id=ms_billboard_slides.slide_pic", "*", "WHERE slide_billboard='".$billboard['bill_id']."' ORDER BY slide_order ASC");
	while($slide = mysqli_fetch_array($slides)) { 
			$dsize = getimagefiledems($slide,selectPhotoFile($billboard['bill_pic'],$slide));
		$cl = "";
		if($billboard['bill_burns'] == "1") { 
			if($s%2) { 
				$burns = "burnsin";
			} else { 
				$burns = "burnsout";
			}
		}
		?>
		<?php if(!empty($slide['slide_link'])) { $cl =  'onClick=location.href='.$slide['slide_link'].' style="cursor: pointer;" '; } ?>

		<div id="slide<?php print $s;?>"  data-effect="<?php print $burns;?>" class="<?php if($s == 1) { print "neatbbslide"; } else { print "neatbbslide"; } ?> <?php if($s == "1") { print $burns; } ?>" <?php print $cl; ?>>
			<?php if(!empty($slide['slide_link'])) { print "<a href=\"".$slide['slide_link']."\" style=\"cursor: pointer;\">"; } ?><img id="slide<?php print $s;?>g" src="<?php print getimagefile($slide,selectPhotoFile($billboard['bill_pic'],$slide));?>" data-margin-top="null" width="<?php print $dsize[0];?>" height="<?php print $dsize[1];?>" border="0" ww="<?php print $dsize[0];?>" hh="<?php print $dsize[1];?>" class="billboardphoto "  <?php print $cl; ?>><?php if(!empty($slide['slide_link'])) { print "</a>"; } ?>
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
				<div id="billtext" style="position: absolute; top: 0; left: 0; text-align: left; z-index: 2;  width: 100%;" >
				<?php
				$s = 0;
				$slides = whileSQL("ms_photos LEFT JOIN ms_billboard_slides ON ms_photos.pic_id=ms_billboard_slides.slide_pic", "*", "WHERE slide_billboard='".$billboard['bill_id']."' ORDER BY slide_order ASC");
				while($slide = mysqli_fetch_array($slides)) { 
					$s++;?>

					<?php 
					// $slide = doSQL("ms_photos LEFT JOIN ms_billboard_slides ON ms_photos.pic_id=ms_billboard_slides.slide_pic", "*", "WHERE slide_id='105' ORDER BY slide_order ASC");

					$texts = explode("|:|", $slide['slide_text1']);
					foreach($texts AS $ss1) { 
						if(!empty($ss1)) { 
							$ssx = explode("||", $ss1);
							foreach($ssx AS $vals1) {
								list($id1,$v1) = explode("::",$vals1);
								$valt[$id1] = $v1;
								//print "<li>$id1 = $v1";
							}
							$cl = "";
							if(!empty($slide['slide_link'])) { $cl =  'onClick=location.href='.$slide['slide_link'].' style="cursor: pointer;" '; } 
							$h .= '<div id="billtext'.$s.''.$valt['id'].'" textid="'.$valt['id'].'" style="position: absolute; left: '.$valt['x'].'%; top: 50%; display: none;" tp='.$valt['y'].' lp='.$valt['x'].' class="billtextinner"><div id="text'.$s.''.$valt['id'].'" textid="'.$valt['id'].'" color="'.$valt['color'].'" font-family="'.$valt['font-family'].'" font-size="'.$valt['font-size'].'" font-weight="'.$valt['font-weight'].'" font-style="'.$valt['font-style'].'" text-shadow-h="'.$valt['text-shadow-h'].'" text-shadow-v="'.$valt['text-shadow-v'].'" text-shadow-b="'.$valt['text-shadow-b'].'" text-shadow-c="'.$valt['text-shadow-c'].'" slide_text_1_time="'.$valt['slide_text_1_time'].'" slide_text_1_effect="'.$valt['slide_text_1_effect'].'" x="'.$valt['x'].'" y="'.$valt['y'].'" class="thebbtext'.$s.'" style="color: #'.$valt['color'].'; font-size: '.$valt['font-size'].'px; font-family: '.$valt['font-family'].'; font-weight: '.$valt['font-weight'].'; font-style: '.$valt['font-style'].'; text-shadow: '.$valt['text-shadow-h'].'px '.$valt['text-shadow-v'].'px '.$valt['text-shadow-b'].'px #'.$valt['text-shadow-c'].'; float: left;" ><span '.$cl.'>'.$valt['text'].'</span></div></div>';
							unset($cl);
						}
					}
					print $h;
					unset($h);
					
				 } ?>
				</div>
			</div>
		</div>
	</div>
</div>