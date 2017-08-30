<?php 
function listResults($list,$listCols,$NPvars) {
	global $_REQUEST, $setup;
	$html .= "<div id=tbspace>";
	if(!empty($list['title'])) { $html .= "<div><span class=title>".$list['title']."</span></div>"; }
	if(!empty($list['description'])) { $html .= "<div id=\"info\">".$list['description']."</div>"; }
	if(!empty($list['links'])) {
		$html .= "<div id=\"subMenusContainer\"><div id=\"subMenus\" class=\"textright\">";
		$links = explode(",",$list['links']);
		foreach($links AS $link) {		
			$html .= "$link";
		}
		$html .= "</div></div>";
	}

	$html .= "<table width=100% cellpadding=0 cellspacing=0 border=0><tr><form method=\"post\" name=\"listForm\" id=\"listForm\" action=\"index.php\"><td colspan=2 style=\"padding-right: 1px;\">";
	$html .= "<table width=100% cellpadding=0 cellspacing=0 border=0 class=listbox>";
	$xres = 0;
	$html .= "<tr valign=bottom>";
	while($xres < count($listCols)) {
		if($listCols[$xres][new_line]!=="1") {

			if(!empty($listCols[$xres][width])) {
				$html .= "<td class=tdtop width=\"".$listCols[$xres][width]."\">";
			} else {
				$html .= "<td class=tdtop>";
			}
			$html .= "".$listCols[$xres][name]."";
			$html .= "</td>";
		}
		$xres++;
	}
	$html .= "</tr>";

	if(empty($_REQUEST['pg'])) {
		$pg = "1";
	} else {
		$pg = $_REQUEST['pg'];
	}
	$per_page = $list['limit'];
	$total_results = countIt("".$list['table']."", "".$list['where']." ORDER BY ".$list['orderBy']."" );

	$sq_page = $pg * $per_page - $per_page;

//	if($total_results <= 0) { print "<center>No images found</center>"; } else { 
//	print "<li>".$list['table']."". " ".$list['select']."". " ".$list['where']." ORDER BY ".$list['orderBy']." ".$list['acdc']." LIMIT  $sq_page,".$list['limit']." ";

	$vars = whileSQL("".$list['table']."", "".$list['select']."", "".$list['where']." ORDER BY ".$list['orderBy']." ".$list['acdc']." LIMIT  $sq_page,".$list['limit']." ");
	$num_results = mysqli_num_rows($vars);
	if($num_results > 0) {
		while($var = mysqli_fetch_array($vars)) {
			$rc++;
			if($rc%2) {
				$rclass = "tdrows1";
			} else {
				$rclass = "tdrows2";
			}
			$html .= "<tr valign=top>";
			$xres = 0;
			while($xres < count($listCols)) {
				if($listCols[$xres][new_line]=="1") { 
					$html .= "</td></tr><tr><td colspan=".$listCols[$xres][col_span]." class=$rclass>";
				} else {
					$html .= "<td class=$rclass>";
				}
				if(!empty($listCols[$xres][var_popup_info])) {
					$html .= "<span onmouseover=\"Tip('".$listCols[$xres][var_popup_info]."')\">";
				}
				if(!empty($listCols[$xres][var_popup_info_function])) {
					$inval = $var[$listCols[$xres][var_function_pass]];
					$html .= "<span onmouseover=\"Tip('".$listCols[$xres][var_popup_info_function]("$inval")."')\">";
				}
				if(!empty($listCols[$xres][var_link])) {
					$html .= "".str_replace("[ID]", $var[$list['id']], $listCols[$xres][var_link])."";
				} elseif(!empty($listCols[$xres][var_link_to])) {
					$reps = explode(",",$listCols[$xres][var_replace]);
					$new_link = $listCols[$xres][var_link_to];
					foreach($reps AS $rep) {
						$new_link = "".str_replace($rep, $var[$rep], $new_link);
					}
						if($listCols[$xres][add_breaks] == "yes") {
							$new_link = nl2br($new_link);
						}

					$html .= "$new_link";
				} elseif(!empty($listCols[$xres][var_function])) {
					$inval = $var[$listCols[$xres][var_function_pass]];
					$html .= $listCols[$xres][var_function]("$inval");
				} elseif($listCols[$xres][var_order_field] == "1") {
					$html .= "<input type=\"text\" size=\"3\" name=\"".$listCols[$xres][var_field]."[".$var[$list['id']]."]\" value=\"".$var[$listCols[$xres][var_field]]."\" style=\"text-align: center;\">";
				} elseif($listCols[$xres][var_checkbox] == "1") {
					$html .= "<input type=\"checkbox\" size=\"3\" name=\"".$list['id']."[]\" value=\"".$var[$list['id']]."\" class=\"toselect\">";
				} elseif($listCols[$xres][var_status] == "1") {
					if($var[$listCols[$xres][var_field]] == "0") {
						$html .= "".icon_red."";
					}
					if($var[$listCols[$xres][var_field]] == "1") {
						$html .= "".icon_green."";
					}
				} elseif($listCols[$xres][var_status] == "2") {
					if($var[$listCols[$xres][var_field]] == "1") {
						$html .= "".icon_red."";
					}
					if($var[$listCols[$xres][var_field]] == "0") {
						$html .= "".icon_green."";
					}
				} elseif($listCols[$xres][var_price] == "1") {
					$html .= "".this_currency."".$var[$listCols[$xres][var_field]]."";
				} else {		
					$html .= "".$var[$listCols[$xres][var_field]]."";
				}
				if(!empty($listCols[$xres][var_popup_info])) {
					$html .= "</span>";
				}
				if(!empty($listCols[$xres][var_popup_info_function])) {
					$html .= "</span>";
				}
				$html .= "</td>";
				$xres++;
			}
			$html .= "</tr>";
		}
	}
	$html .= "</table>";
	if($num_results > 0) {

		$html .= "</td><tr><tr valign=top><td id=tbspace>";
		if($list['checkbox'] == "1") {
			$html .= "<div style=\"margin-left: 8px;\"><input type=\"checkbox\" onclick=\"checkAll(document.getElementById('listForm'), 'toselect');\">Select All</div>";
		}

		if(($list['order_form'] == "1")OR($list['checkbox'] == "1")==true) {
			$html .= "<div style=\"margin-left: 10px; float: left;\" id=tbspace>";
			$html .= "<input type=\"hidden\" name=\"do\" value=\"".$list['do']."\">";
			$html .= "<input type=\"hidden\" name=\"pg\" value=\"".$_REQUEST['pg']."\">";
			$html .= "<input type=\"hidden\" name=\"category\" value=\"".$_REQUEST['category']."\">";
			$html .= "<input type=\"hidden\" name=\"tags\" value=\"".$_REQUEST['tags']."\">";
//			$html .= "<li>".$list['order_form_hiddens'];
			if(!empty($list['order_form_hiddens'])) {
				$hiddens = explode(",", $list['order_form_hiddens']);
				foreach($hiddens AS $hidden) {
					$hid = explode("|", $hidden);
						$html .= "<input type=\"hidden\" name=\"$hid[0]\" value=\"$hid[1]\">";
				}
			}

			$html .= "<select name=\"action\">";
			if($list['order_form'] == "1") {
				$html .= "<option value=\"".$list['order_action']."\">".$list['order_select_option']."</option>";
			}
			if($list['checkbox'] == "1") {
				$actions = explode(",", $list['checkbox_actions']);
				foreach($actions AS $action) {
					$act = explode("|", $action);
					$html .= "<option value=\"$act[0]\">$act[1]</option>";
				}
			}
			$html .= "</select>";
			$html .= "<input type=\"submit\" name=\"submit\" value=\"".$list['form_submit_button']."\" class=\"submit\"></div>";
		}

		$html.= "</td><td id=tbspace>";
		$html .= "<table width=100% cellpadding=0 cellspacing=0 border=0><tr valign=top><td width=100% align=right>";
		$html .= nextprevHTMLMenu($total_results, $pg, $per_page,  $NPvars, $_REQUEST);
		$html .= "</td></tr></table>";
		if(!empty($list['bottom_notes'])) {
			$html .= "</td></tr><tr><td>".nl2br($list['bottom_notes'])."";
		}
	} else {
		$html .= "<center><br><br><B>No data found</B></center>";
	}
	$html .= "</td></form></tr></table>";

	return $html;
}

// End listResults
?>

<?php 
function dataForm($ef,$efCols) {
	global $_REQUEST,$settings, $setup;
//	print_r($_REQUEST);
	if($_REQUEST['check'] == "submit") {
		foreach($efCols AS $line => $var) {
			if(($var['required'] == 1)AND(empty($_REQUEST[$var[field]]))==true) {
				$error .= "<li>".$var['name']." is blank";
			}
			if($var['check_dup'] == 1) {
				$ckdup = doSQL("".$ef['table']."", "*", "WHERE  ".$var['field']."='".$_REQUEST[$var[field]]."' AND ".$ef['id']."!='".$_REQUEST[$ef[id]]."' ");
				if(!empty($ckdup[$var[field]])) {
					$error .= "<li>".$var[name]." ".$_REQUEST[$var[field]]." already exists - please select another";
				}
			}

			if(($var['allow_new'] == "yes")AND(!empty($_REQUEST[$var[allow_new_name]]))==true) {
				$create_new_table = $var['table'];
				$create_new_field = $var['allow_new_name'];
				$create_new_under = $_REQUEST[$var[field]];
				$create_new_this_field = $var['field'];
				$create_new_value = $_REQUEST[$var[allow_new_name]];
			} elseif($var['type'] == "tags") {
				if(!empty($_REQUEST['new_tags'])) {
					$makenewtags = array();
					$tag_table = $var['tag_table'];
					$newtags = explode(",",$_REQUEST['new_tags']);
					foreach($newtags AS $name => $tag) {			
						$tag = strtolower(trim($tag));
						if($tag!='') {
							$sql_tags .= "$tag,";
							if(countIt("".$var['tag_table']."", "WHERE tag_tag='".stripslashes($tag)."' ")<= 0) {
								array_push($makenewtags, $tag);
							}
						}
					}
				}
				if(!empty($_REQUEST['etags'])) {
					foreach($_REQUEST['etags'] AS $etag) {
						$sql_tags .= "$etag,";
		//				print "<li>".$sql_tags;
					}
				}
				if($addcount>0) { $sql_add.=", "; } 
				$sql_add .= "".$var['field']."='".addslashes(stripslashes($sql_tags))."' ";
				$addcount++;

			} else {
				if($var['nosave']!=="1") {
					if($addcount>0) { $sql_add.=", "; } 
					$sql_add .= "".$var['field']."='".addslashes(stripslashes($_REQUEST[$var[field]]))."' ";
					$addcount++;
				}
			}
		}
		if(!empty($error)) {

			$html .= "<div id=pageContent> <div class=errorMessage>Looks like there is an error. Correct the error(s) below and re-submit the form.<ul>$error</ul></div></div><div>&nbsp;</div>";
			$html .=  editForm($ef,$efCols);
		} else {
			if(!empty($create_new_table)) {
				$newcat = insertSQL("$create_new_table", "$create_new_field='".addslashes(stripslashes($create_new_value))."', cat_under='$create_new_under' ");
				$sql_add .= ", $create_new_this_field='$newcat' ";
				$_REQUEST[$create_new_this_field] = $newcat;
			}
			if(!empty($makenewtags)) {
				foreach($makenewtags AS $new_tag) {
					insertSQL("$tag_table", "tag_tag='".addslashes(stripslashes($new_tag))."' ");
				}
			}
	//		print "<li>".$_REQUEST[$create_new_this_field]; die();
			
			if((empty($_REQUEST[$ef[id]]))AND($ef['no_id_form'] !== 1)==true) {
				$id = insertSQL("".$ef['table']."", "$sql_add");   
			} else {
				if($ef['no_id_form'] !== 1) {
					$awhere = " WHERE ".$ef['id']."='".$_REQUEST[$ef[id]]."'  ";
				}
				$id = updateSQL("".$ef['table']."", "$sql_add $awhere ");   
				$id = $_REQUEST[$ef[id]];
			}
			$success_message = str_replace("".$ef['success_replace'], $_REQUEST[$ef[success_replace]], $ef['success_message']);
			$ef['success_url'] = str_replace("[ID]", $id, $ef['success_url']);
			$ef['success_url'] = str_replace("[OLD_CAT_UNDER]", $_REQUEST['old_cat_under'], $ef['success_url']);

			// print_r($_REQUEST);

			if(!empty($ef['add_form_submit_function'])) {
	//			print "<li>In for: $id";
				$_REQUEST['opt_id'] = $id;
				$_REQUEST['package_id'] = $id;
				$onid = $ef['id'];
				$_REQUEST[$onid] = $id;
			//	print_r($_POST);
				$ef['add_form_submit_function']();
			}
			if($ef['proccessadditionalcats'] == true) { 
				deleteSQL2("ms_products_cats_connect", "WHERE con_prod='$id' ");
				foreach($_REQUEST['prod_add_cats'] AS $cat_id) { 
					insertSQL("ms_products_cats_connect", "con_prod='$id', con_cat='$cat_id' ");
				}
			}
			$_SESSION['sm'] = "".stripslashes($success_message)."";
			if($ef['error_check']!==1) {


//				print "<li>DO YOU SEE THIS MESSAGE?";
				$g2url = $ef['success_url'];
			//	print_r($_REQUEST);
				session_write_close();
				header("location: $g2url");
				exit();
			}
			exit();

		}
	} else {
		$html .= editForm($ef,$efCols);
	}
	return $html;
}
/* For Editor */
function encodeHTML($sHTML) {
	$sHTML=preg_replace("/&/","&amp;",$sHTML);
	$sHTML=preg_replace("/</","&lt;",$sHTML);
	$sHTML=preg_replace("/>/","&gt;",$sHTML);
	return $sHTML;
}


function editForm($ef,$efCols) {
	global $_REQUEST,$settings, $setup,$site_setup;

	if(!empty($ef['page_title'])) { 
		$html .= "<div id=\"pageTitle\">".$ef['page_title']."</div>";
	}
	if(!empty($ef['page_text'])) { 
		$html .= "<div class=\"pageContent\">".$ef['page_text']."</div>";
	}

	if(((!empty($_REQUEST[$ef[id]]))AND(empty($_REQUEST['check'])))OR(($ef['no_id_form'] == 1)AND(empty($_REQUEST['check'])))==true) {
		if($ef['no_id_form'] !== 1) {
			$awhere = " WHERE ".$ef['id']."='".$_REQUEST[$ef[id]]."'  ";
		}
		$vals = doSQL("".$ef['table']."", "*", " $awhere");
		foreach($vals AS $fname => $val) {
			if(!is_numeric($fname)) {
				$_REQUEST[$fname] = $val;
			}
		}
		$_REQUEST['f'] = "EDIT";
	}
	if((empty($_REQUEST[$ef[id]]))AND(empty($_REQUEST['check']))AND(!empty($ef['defaults']))==true) {
		$defs = explode(",",$ef['defaults']);
		foreach($defs AS $def) {
			$ad = explode("|", $def);
			$_REQUEST[$ad[0]] = $ad[1];
		}
	}
	if(!empty($ef['post_page'])) { 
		$post_page = $ef['post_page'];
	} else { 
		$post_page = "index.php";
	}
	$html .= "<form method=\"post\" name=\"theForm\" id=\"theForm\" action=\"".$post_page."\" onSubmit=\"return checkForm('','submit');\">";
	if(empty($_REQUEST[$ef[id]])) { 
		$html .= "<div class=\"pageContent\">".$ef['new_message']."</div>";
	} else {
		$html .= "<div class=\"pageContent\">".$ef['edit_message']."</div>";
	}


	$html .= "<div id=\"roundedForm\">";
	$new_row = 1;
	foreach($efCols AS $line => $var) {
		if($var['type'] == "hidden") {
			$html .= "<input type=\"hidden\" name=\"".$var['field']."\" size=\"".$var['size']."\" value=\"".htmlspecialchars(stripslashes($_REQUEST[$var[field]]))."\">";
		} else {

			if($new_row == 1) {
				$html .= "<div class=\"row\">";
			}
			$new_row++;
			//if($rc%2) { $rclass = "tdrows1";	} else {	$rclass = "tdrows2";	}
			if($var['colspan'] < 3) {
				$html .= "<div style=\"width: 50%;\" class=\"left\">";
			} 


			$html .= "<div class=\"fieldLabel\">";
			if($var['type'] == "checkbox") {
				 $html .= "<input type=\"checkbox\" name=\"".$var['field']."\" value=\"".$var['value']."\""; if($_REQUEST[$var[field]] == $var['value']) { $html .= " checked"; } $html .= "> ";
			}
			if($var['type'] == "info") { 
				$html .= "<h2>".$var['name']."</h2>";
			} else { 
				$html .= "".$var['name']."";
			}
			$html .= "</div>";
			if(!empty($var['notes1'])) { 
				$html .= "<div class=\"fieldDescription\">".$var['notes1']."</div>";
			}
			if(!empty($var['colspan'])) {
				$new_row = $new_row + $var['colspan'];
			} else {
				$new_row++;
			}
			$html .= "<div>";

			if($var['type'] == "text") {
				$html .= "<input type=\"text\" name=\"".$var['field']."\" id=\"".$var['field']."\" size=\"".$var['size']."\" value=\"".htmlspecialchars(stripslashes($_REQUEST[$var[field]]))."\" class=\"".$var['class']."";
				if($var['required'] == 1) { $html .=" required"; } 

				$html .="\">";
			}

			if($var['type'] == "textarea") {
				$html .= "<textarea name=\"".$var['field']."\" id=\"".$var['field']."\" rows=\"".$var['rows']."\" cols=\"".$var['cols']."\" style=\"width: 96%; padding: 6px;\">".htmlspecialchars(stripslashes($_REQUEST[$var[field]]))."</textarea>";
			}
			if($var['type'] == "htmltextarea") {
				$html .= "<textarea id=\"".$var['field']."\" id=\"".$var['field']."\" name=\"".$var['field']."\"  rows=4 cols=30>";

				if(isset($_REQUEST[$var[field]])) {
					$sContent=stripslashes($_REQUEST[$var[field]]); //Remove slashes
					$html .= encodeHTML($sContent);
				}
				$html .= "</textarea>";

				$style_sheet = "/sy-style.php?csst=".$site_setup['css']."&admin_edit=1";

				$html .= "<script>";
				$html .= "var oEdit1 = new InnovaEditor(\"oEdit1\");";
				$html .= "oEdit1.width=\"100%\";";
				$html .= "oEdit1.height=\"400px\";";
				$html .= "oEdit1.css=\"".$style_sheet."\";";
				$html .= "oEdit1.btnStyles=true;";

				$html .= "oEdit1.cmdAssetManager=\"modalDialogShow('/".$setup['manage_folder']."/assetmanager/assetmanager.php',640,445);\";";
				$html .= "oEdit1.REPLACE(\"".$var['field']."\");";
				$html .= "</script>";
			}
			if($var['type'] == "function") {
				if($var['functionname'] == "getMultiCats") { 
					$html .= getMultiCats('item_cat',$_REQUEST['item_cat']);
				} elseif($var['functionname'] == "getMultiCatsCat") { 
					$html .= getMultiCats('cat_under',$_REQUEST['cat_under']);
				} elseif($var['functionname'] == "productAdditionalCategories") { 
					$html .= productAdditionalCategories($_REQUEST['prod_id']);
				} else { 
					//$var[functionname]();
				}	
			}


			if($var['type'] == "multiLevelSelect") {
				$html .= multiLevelSelect($_REQUEST[$var[field]]);
			}

			if($var['type'] == "multileveldrop") {
				if($var['allow_new'] == "yes") {
					$html .= "<div id=\"newo_".$var['allow_new_name']."\"  style=\"display: block;\">";
					$html .= multiLevelCats("".$var['table']."","".$var['and_where_edit']."","".$var['under_field']."","".$var['show_name']."","".$var['order_field']."","".$var['order_type']."","".$var['field']."", "".$var['match']."", "".$var['select_title']."", "".$var['allow_new']."", "".$var['allow_new_name']."");
				
					$html .= "<br><a href=\"\" onclick=\"openClose('newc_".$var['allow_new_name']."','newo_".$var['allow_new_name']."'); return false;\">Create new category</a></div>";
					$html .= "<div id=\"newc_".$var['allow_new_name']."\"  style=\"display: none;\">Category Name: <input type=\"text\" name=\"".$var['allow_new_name']."\" size=\"12\" value=\"".$_REQUEST[$var[allow_new_name]]."\"><br><br>Under Category: ";
					$html .= multiLevelCats("".$var['table']."","".$var['and_where_edit']."","".$var['under_field']."","".$var['show_name']."","".$var['order_field']."","".$var['order_type']."","".$var['field']."", "".$var['match']."", "".$var['allow_new_select_title']."", "".$var['allow_new']."", "".$var['allow_new_name']."");
					
					$html .= "<br><a href=\"\" onclick=\"openClose('newo_".$var['allow_new_name']."','newc_".$var['allow_new_name']."'); return false;\">cancel</a><br></div>";
				} else {
					$html .= multiLevelCats("".$var['table']."","".$var['and_where_edit']."","".$var['under_field']."","".$var['show_name']."","".$var['order_field']."","".$var['order_type']."","".$var['field']."","".$var['select_field']."", "".$var['match']."", "".$var['select_title']."", "".$var['allow_new']."", "".$var['allow_new_name']."");
				}
			}
			if($var['type'] == "radio") {
				$opts = explode(",",$var['options']);
				foreach($opts AS $opt) {
					$val = explode("|",$opt);
					$html .= "<input type=\"radio\" style=\"border: 0px; background: transparent;box-shadow: 0;\" name=\"".$var['field']."\" value=\"$val[0]\""; if($_REQUEST[$var[field]] == $val[0]) { $html .= " checked"; } $html .= "> $val[1] <br>";
				}
			}


			if($var['type'] == "tags") {
				$tags = explode(",",$_REQUEST[$var[field]]);
				$html .= manageTags("".$var['tag_table']."",$tags);
			}
			if($var['type'] == "dropdown") {
				$opts = explode(",",$var['options']);
				$html .= "<select name=\"".$var['field']."\" id=\"".$var['field']."\" >";
				foreach($opts AS $opt) {
					$val = explode("|",$opt);
					$html .= "<option value=\"$val[0]\""; if($_REQUEST[$var[field]] == $val[0]) { $html .= " selected"; } $html .= "> $val[1]</option>";
				}
				$html .= "</select>";
			}

			if($var['type'] == "dropdownarray") {

				$html .= "<select name=\"".$var['field']."\" id=\"".$var['field']."\" >";
				foreach($var['options'] AS $lin => $option) {

				if($_REQUEST[$var[field]] == $option[val]) { $selected = "selected"; }
				$html .= "<option value=\"$option[val]\" $selected>$option[name]";
				unset($selected);
			}

				$html .= "</select>";
			}

			if($var['type'] == "selectDropDown") {
				$html .= "<select name=\"".$var['field']."\"  id=\"".$var['field']."\" >";
				if(!empty($var['select_empty'])) {
					$html .= "<option value=\"0\"> ".$var['select_empty']."</option>";
				}
				$datas = whileSQL("".$var['table']."","*", "".$var['where']."");
				while($data = mysqli_fetch_array($datas)) {
					$html .= "<option value=\"".$data[$var[select_field]]."\""; if($_REQUEST[$var[field]] == $data[$var[select_field]]) { $html .= " selected"; } $html .= "> ".$data[$var[show_field]]."</option>";
				}
				$html .= "</select>";
			}
			$html .= "</div>";


			if(!empty($var['notes2'])) { 
				$html .= "<div class=\"fieldDescription\">".$var['notes2']."</div>";
			}

//			$html .= "</td>\r";
			if($var['colspan'] < 3) {
				$html .= "</div>";
			}
			if($new_row>= $ef['totalspan']) {
//				$html .= "</tr>\r";
				$html .= "<div class=\"clear\"></div>";
				$html .= "</div>";
				
				$new_row = 1;
				$rc++;
			}
		}

	}
	// $html .= "</td></tr></table>\r";
	if(!empty($ef['add_form_function'])) {
		$html .= $ef['add_form_function'];
	}

	$html .= "<div class=\"bottomSave\">";
	$html .= "<input type=\"hidden\" name=\"do\" value=\"".$ef['do']."\">";
	$html .= "<input type=\"hidden\" name=\"".$ef['eaction']."\" value=\"".$ef['action']."\">";
	if(!empty($ef['subdo'])) {
		$html .= "<input type=\"hidden\" name=\"subdo\" value=\"".$ef['subdo']."\">";
	}
	if(!empty($ef['cmd'])) {
		$html .= "<input type=\"hidden\" name=\"cmd\" value=\"".$ef['cmd']."\">";
	}
	if(!empty($ef['add_hidden'])) {
		$add_this = explode("|",$ef['add_hidden']);
		$html .= "<input type=\"hidden\" name=\"".$add_this[0]."\" value=\"".$add_this[1]."\">";
	}

	$html .= "<input type=\"hidden\" name=\"".$ef['id']."\" value=\"".$_REQUEST[$ef[id]]."\">";
	$html .= "<input type=\"hidden\" name=\"check\" value=\"submit\">";
	$html .="	<input type=\"hidden\" name=\"submited\" id=\"submited\" value=\"0\">";
	$html .= "<div>";
	if(empty($_REQUEST[$ef[id]])) {
		$html .= "<input type=\"submit\" name=\"submit\" value=\"".$ef['new_button']."\" class=\"submit\"  id=\"submit\">";
	} else {
		$html .= "<input type=\"submit\" name=\"submit\"  value=\"".$ef['save_button']."\" class=\"submit\"  id=\"submit\">";
	}
	$html .= "</div>";
	$html .= "<div id=\"submitButtonLoading\" style=\"display: none;\">".ai_loading."</div>";
	if(!empty($ef['cancel_link'])) {
//		$html .= "</td></tr><tr><td id=tbspace align=center class=buttonRow>";
		$html .= "<div><a href=\"".$ef['cancel_link']."\" target=\"_parent\">Cancel</a></div>";
	}
//	$html .= "</td></form></tr></table></td></tr></table>";
	$html .= "</div></form>";
	return $html;
}


?>
