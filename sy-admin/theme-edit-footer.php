<?php
$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' $and_link AND link_location!='favs' AND link_location!='shop' AND link_location!='side' ORDER BY link_order ASC ");
		while($link = mysqli_fetch_array($links)) { 
			if(!empty($link['link_main'])) { 


	//			$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/".$setup[$link['link_main']]."/\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 


				if($link['link_main'] == "favorites") { 
					$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=favorites\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 
				}
				if($link['link_main'] == "findphotos") {
					$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=findphotos\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 	
				}
				if(($link['link_main'] == "myaccount")&&(customerLoggedIn())==true) { 
					$fl .= "<a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=account\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 	
				} 

				 if(($link['link_main'] == "newaccount")&&(!customerLoggedIn())==true) {  
					$fl .= "<a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=newaccount\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 	
				} 
				if(($link['link_main'] == "login")&&(!customerLoggedIn())==true) {
					$fl .= "<a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=account\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 	
				} 

				if(($link['link_main'] == "logout")&&(customerLoggedIn())==true) { 
					$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=logout\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 	
				} 

			} elseif($link['link_page'] > 0) {
				$lpage = doSQL("ms_calendar", "*", "WHERE date_id='".$link['link_page']."' ");
				if($lpage['page_home'] == "1") {
					if($site_setup['index_page'] == "indexnew.php") { 
						$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/indexnew.php\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 
					} else { 
						$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 
					}
				} else {
					$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/".$lpage['date_link']."/\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 
				}
				} elseif($link['link_cat'] > 0) {
					$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$link['link_cat']."' ");
				
				$fl .= "<a href=\"".$setup['temp_url_folder']."".$cat['cat_folder']."/\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; ";

			} else { $fl .="<a href=\"".$link['link_url']."\" target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp;"; } 
	} 
	?>
	<?php 
	$footer = $site_setup['footer'];
	$footer = str_replace("[YEAR]", date('Y'), $footer);
	$footer = preg_replace('#\[SOCIAL_LINKS]#i', showSocialLinks(),$footer);  
	$footer = preg_replace('#\[MENU_LINKS]#i', $fl,$footer);  
	$footer = str_replace("[SITE_NAME]", "<a href=\"".$setup['temp_url_folder']."/\">".$site_setup['website_title']."</a>&nbsp; ", $footer);

	print $footer;
	?>
