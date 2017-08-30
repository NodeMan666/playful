	<?php 
	$fr1cats = explode("|",$date['feature_row_1']);
	?>

	<script>
function showFirtLayout() { 
	if($("#show_first_layout").is(":checked")) { 
		$("#first_layout_form").slideDown(100);
	} else { 
		$("#first_layout_form").slideUp(100);
	}
}


	</script>
<div class="hidenew">
	<div class="underlinelabel subeditclick">Featured Content / Galleries</div>
	<div class="subedit">
		<div class="underline hidenew">Here you can select content to feature on your home page from sections / categories ... like client galleries. This will be placed below any text above.</div>
		<div class="underline hidenew">
			<div class="left pc">
				<div>Select a category or categories</div>
				<div>
				<div><?php print featuredCategories($date);?></div>
				<div>Hold down your CTRL key to select multiple categories.</div>
				</div>
			<!-- 
			<div class="underline">
			<div><input type="checkbox" id="show_first_layout" name="show_first_layout" value="1"<?php if($date['home_first_layout'] > 0) { print "checked"; } ?> onchange="showFirtLayout();"> Use a different layout for first item</div>
			<div id="first_layout_form" <?php if($date['home_first_layout'] <=0) { ?>class="hidden" <?php } ?>>

			<select name="home_first_layout" id="home_first_layout" >
			<option value="">Do not use</option>
			<?php 
			$layouts = whileSQL("ms_category_layouts", "*", "WHERE layout_type='listing' ORDER BY layout_name ASC ");
			while($layout = mysqli_fetch_array($layouts)) { 
				print "<option value=\"".$layout['layout_id']."\" "; if($date['home_first_layout'] == $layout['layout_id']) { print "selected"; } print ">".$layout['layout_name']."</optoin>";
			}
			?>
			</select>
			</div>
			</div>
			--> 

			</div>

			<div class="left pc">
				<div>Limit </div>
				<div><input type="text" name="date_feature_limit" size="2" class="center" value="<?php print $_REQUEST['date_feature_limit'];?>"> </div>
				<div>&nbsp;</div>
				<div><input type="checkbox" name="date_feature_auto_populate" id="date_feature_auto_populate" value="1" <?php if($date['date_feature_auto_populate'] == "1") { print "checked"; } ?>> <label for="date_feature_auto_populate">Auto populate content when scrolling. <br>Unchecked will stop after the above limit is reached.</label>  </div>
			</div>

				<div class="left pc">
					<div>Layout Style</div>
					<div>
					<select name="date_feature_layout">
					<?php
					$lays = whileSQL("ms_category_layouts", "*", "WHERE layout_type='listing' ORDER BY layout_name ASC ");
					while($lay = mysqli_fetch_array($lays)) { ?>
					<option value="<?php print $lay['layout_id'];?>" <?php if($_REQUEST['date_feature_layout'] == $lay['layout_id']) { print "selected"; } ?>><?php print $lay['layout_name'];?></option>
					<?php } ?>
					</select>
					</div>
				</div>

				<div class="clear"></div>

			</div>



			<div class="underline hidenew">
				<div class="label">Title</div>
				<input type="text" name="date_feature_title" size="20" class="field100" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['date_feature_title']));?>">
			</div>
			<div class="underline hidenew">
				<div class="label">Text</div>
				<textarea name="date_feature_text" cols="20" rows="3" class="field100"><?php  print htmlspecialchars(stripslashes($_REQUEST['date_feature_text']));?></textarea>
			</div>

		</div>
		
	</div>
		
			<div>&nbsp;</div>









		
		
		
	<div  class="hidenew">
		<div class="underlinelabel subeditclick">Featured Sections</div>
		<div class="subedit">
			<div class="underline">Here you can display featured sections on the home page that will display a graphic and link to that section. <u>For this to work, you must upload section  preview photos</u> by editing the section and clicking the preview photo tab.</div>



			<div class="underline"><input type="checkbox" name="feature_show_titles" value="1" <?php if($date['feature_show_titles'] == "1") { print "checked"; } ?>> Display section titles under graphics</div>

			<div class="underline hidenew">
				<div class="" style="float: left; width: 25%;">
					<select name="feature_row_1_1">
					<option value="">None</option>

					<?php $cats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='0' ORDER BY cat_name ASC ");
					while($cat = mysqli_fetch_array($cats)) { ?>
					<option value="<?php print $cat['cat_id'];?>" <?php if($fr1cats[0] == $cat['cat_id']) { print "selected"; } ?>><?php print $cat['cat_name'];?></option>
					<?php } ?>
					</select>

				</div>

				<div class="" style="float: left; width: 25%;">
					<select name="feature_row_1_2">
					<option value="">None</option>
					<?php $cats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='0' ORDER BY cat_name ASC ");
					while($cat = mysqli_fetch_array($cats)) { ?>
					<option value="<?php print $cat['cat_id'];?>" <?php if($fr1cats[1] == $cat['cat_id']) { print "selected"; } ?>><?php print $cat['cat_name'];?></option>
					<?php } ?>
					</select>
				</div>


				<div class="" style="float: left; width: 25%;">
					<select name="feature_row_1_3">
					<option value="">None</option>
					<?php $cats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='0' ORDER BY cat_name ASC ");
					while($cat = mysqli_fetch_array($cats)) { ?>
					<option value="<?php print $cat['cat_id'];?>" <?php if($fr1cats[2] == $cat['cat_id']) { print "selected"; } ?> ><?php print $cat['cat_name'];?></option>
					<?php } ?>
					</select>
				</div>


				<div class="" style="float: left; width: 25%;">
					<select name="feature_row_1_4">
					<option value="">None</option>

					<?php $cats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='0' ORDER BY cat_name ASC ");
					while($cat = mysqli_fetch_array($cats)) { ?>
					<option value="<?php print $cat['cat_id'];?>" <?php if($fr1cats[3] == $cat['cat_id']) { print "selected"; } ?>><?php print $cat['cat_name'];?></option>
					<?php } ?>
					</select>
				</div>
			<div class="clear"></div>
		</div>







	<?php 
	$fr2cats = explode("|",$date['feature_row_2']);
	?>

	<div class="underline hidenew">
		<div class="" style="float: left; width: 25%;">
			<select name="feature_row_2_1">
			<option value="">None</option>

			<?php $cats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='0' ORDER BY cat_name ASC ");
			while($cat = mysqli_fetch_array($cats)) { ?>
			<option value="<?php print $cat['cat_id'];?>"  <?php if($fr2cats[0] == $cat['cat_id']) { print "selected"; } ?>><?php print $cat['cat_name'];?></option>
			<?php } ?>
			</select>
		</div>

		<div class="" style="float: left; width: 25%;">
			<select name="feature_row_2_2">
			<option value="">None</option>

			<?php $cats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='0' ORDER BY cat_name ASC ");
			while($cat = mysqli_fetch_array($cats)) { ?>
			<option value="<?php print $cat['cat_id'];?>"  <?php if($fr2cats[1] == $cat['cat_id']) { print "selected"; } ?>><?php print $cat['cat_name'];?></option>
			<?php } ?>
			</select>
		</div>


		<div class="" style="float: left; width: 25%;">
			<select name="feature_row_2_3">
			<option value="">None</option>
			<?php $cats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='0' ORDER BY cat_name ASC ");
			while($cat = mysqli_fetch_array($cats)) { ?>
			<option value="<?php print $cat['cat_id'];?>"  <?php if($fr2cats[2] == $cat['cat_id']) { print "selected"; } ?>><?php print $cat['cat_name'];?></option>
			<?php } ?>
			</select>
		</div>


		<div class="" style="float: left; width: 25%;">
			<select name="feature_row_2_4">
			<option value="">None</option>
			<?php $cats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='0' ORDER BY cat_name ASC ");
			while($cat = mysqli_fetch_array($cats)) { ?>
			<option value="<?php print $cat['cat_id'];?>"  <?php if($fr2cats[3] == $cat['cat_id']) { print "selected"; } ?>><?php print $cat['cat_name'];?></option>
			<?php } ?>
			</select>
		</div>
		<div class="clear"></div>

		</div>
	</div>
</div>
	<div>&nbsp;</div>
<?php 
function featuredCategories($date) {
	global $dbcon;
	$cats = explode(",",$date['date_feature_cat']);

	$fn = "gal_under";
	$match = $_REQUEST['gal_under'];
	$html .=  "<select name=\"date_feature_cat[]\" multiple size=\"6\">";
	$html .=  "<option value=\"0\">None</option>";
	$html .=  "<option value=\"999999999\" "; if(in_array("999999999",$cats)) { $html .= "selected"; } $html .= ">All Sections</option>";

	$resultt = @mysqli_query($dbcon,"SELECT * FROM ".cat_table." WHERE cat_under='0' ORDER BY cat_name ASC");
	if (!$resultt) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error"); 	exit();	}
	while ( $type = mysqli_fetch_array($resultt) ) {
	if($type["cat_id"] == $match) { $selected = "selected"; }
	$html .=  "<option value=\"".$type["cat_id"]."\" id=\"subcatm-".$type['cat_id']."\" class=\"multioption\"  ";  if(in_array($type['cat_id'],$cats)) { $html .= "selected"; } if($_REQUEST[''.items_cat_field.''] == $type['cat_id']) { $html .= " style=\"font-weight: bold; display: none;\""; } else { $html .= " style=\"font-weight: bold; \"";  } $html .= ">".$type["cat_name"]."</option>";
	unset($selected);
		$parent_id = $type["cat_id"];
		$parent = $type['cat_name'];

			$html .= featuredCategoriesSubs($fn, $match, $parent_id, $level, $sec_under,$parent,$req,$cats);
	}
	$html .=  "</select>";
	return $html;
}

function featuredCategoriesSubs($fn, $match, $parent_id, $level, $sec_under,$parent,$req,$cats) {
	global $dbcon;
	$level++;
	$subs = @mysqli_query($dbcon,"SELECT *  FROM ".cat_table." WHERE cat_under='$parent_id' ORDER BY cat_name ASC");
	if (!$subs) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
	while($row = mysqli_fetch_array($subs)) {

		$sub_sec_id = $row["cat_id"];
		$sub_sec_name = $row["cat_name"];
		$sub_sec_folder = $row["cat_folder"];


		$html .= "<option  value=\"".$sub_sec_id."\" id=\"subcatm-".$sub_sec_id."\" class=\"multioption\" ";  if(in_array($sub_sec_id,$cats)) { $html .= "selected"; } 
		if($_REQUEST[''.items_cat_field.''] == $sub_sec_id) { $html .= "style=\"display: none;\" "; } $html .= ">"; 
  
		$dashes = 0;
		$html .=  "$parent ->  $sub_sec_name</option>"; 

		$sub2=@mysqli_query($dbcon,"SELECT COUNT(*) AS how_many FROM ".cat_table." WHERE cat_under='$sub_sec_id'");
		if (!$sub2) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
		$row = mysqli_fetch_array($sub2);
		$how_many= $row["how_many"];
		if(!empty($how_many)) { 
			$parent = $parent." -> ".$sub_sec_name;
			$parent_id = $sub_sec_id;
			$html .= featuredCategoriesSubs($fn, $match, $parent_id, $level, $sec_under,$parent,$req,$cats);
		}
	}
		$level = 1;
		return $html;
}



?>
