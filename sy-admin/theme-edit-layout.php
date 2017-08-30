<style>
p { padding: 0px; } 
</style>
<div id="additionalcss" style="display: none; width: 80%; margin: auto;">
<div class="pc"><h2>Additional CSS</h2><br>
Here you can add additional CSS to use with your theme. This additional CSS will not show in the preview if the theme editor, only on your website. This is for advanced users.
</div>
	<div id="">
		<div class="underlinelabel">Enter additional CSS here</div>
		<div class="underline">
		<textarea name="add_css" rows="20" cols="40" style="width: 98%"><?php print $css['add_css'];?></textarea>
		</div>

		<div class="underlinelabel">Css external file</div>
		<div class="underline">
			<div>If you wish to include an external CSS file, enter the link to that file here.</div>
			<div><input type="text" name="css_external" size="40" style="width: 98%" value="<?php print $css['css_external'];?>"></div>
		</div>

		<div class="underlinelabel">Override Header</div>
		<div class="underline">
			<div>Anything entered here will disable your main header and use this code instead.</div>
			<div><textarea name="header_code" rows="20" cols="40" style="width: 98%"><?php print $css['header_code'];?></textarea></div>
		</div>

	</div>
	</div>
</div>

<div id="mainstyle" style="font-family: <?php print font_family;?>;margin: auto; ">
	<div id="outside_bg">
	<div id="bgFadeContainer"  style="position: fixed; z-index: 1 ;" ><div id="bgFade" style="width: 100%; height: 100%;"></div></div>
	<div style="z-index: 2; position: relative;">
	<?php if(countIt("ms_menu_links", "WHERE link_status='1' AND link_location='shop' ") > 0) { ?>
	<div id="shopmenu">
		<div id="shopmenuinner" style="text-align: right;">
			<div id="shopmenuinner2">
				<span class="shopmenuitem">Top Mini Menu </span> <span class="sm_link shopmenuitem">Top Mini Menu Link </span> <span class="sm_link shopmenuitem">Top Mini Menu Link</span>
			</div>
		</div>
	</div>

	<?php } ?>
<!-- 
		<div id="page_wrapper">
		<style>#headercontent { z-index: 500; } </style>
    <p id="btn-save" style="display: none;">
    <span onclick="saveRedactor();">Save</span>
    </p>
    <script type="text/javascript">
    $(function()
    {
    $('#headercontent').on('click', loadRedactor);
    });
     
    function loadRedactor()
    {
    $('#headercontent').redactor({
    iframe: true,
    startCallback: function()
    {
    var marker = this.selection.getMarker();
    this.insert.node(marker);
    },
    initCallback: function()
    {
    this.selection.restore();
    $('#headercontent').off('click', loadRedactor);
     
    $('#btn-save').show();
    },
    destroyCallback: function()
    {
    console.log('destroy');
    $('#headercontent').on('click', loadRedactor);
    }
    });
    }
     
    function saveRedactor()
    {
    // save content if you need
    var html = $('#headercontent').redactor('code.get');
     
    // destroy editor
    $('#headercontent').redactor('core.destroy');
    $('#btn-save').hide();
    }
    </script>
	-->

			<div id="headerAndMenu" style="position: relative;">
				<div id="headerContainer" style="margin: auto;">
					<div id="headerContainerInner">
						<div id="header">
							<div id="headerinner">
								<div id="headercontent"><span onclick="showDialog(); return false;"><?php print $site_setup['header'];?></span></div>
							</div>
						</div>
					</div>
					<div id="mainMenuContainerOuter">
						<div id="mainMenuContainer" style="z-index: 2; position: relative;">
							<div id="main_menu">
								<div id="main_menu_inner">
								<div id="topmain-menu">
								<?php 	$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' AND link_location='topmain' ORDER BY link_order ASC ");
								if(mysqli_num_rows($links) <=0) { ?>
								<span class="topmenulink">Sample Menu</span>
								<span class="topmenulink">Sample Menu</span>
								<span class="topmenulink">Sample Menu</span>
								<span class="topmenulink">Sample Menu</span>
								<?php 
								}
								while($link = mysqli_fetch_array($links)) { 
									$lc++;
									?><span class="topmenulink"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></span>
								<?php if($lc < mysqli_num_rows($links)) { ?><span class="menusep"><?php print $css['menu_sep'];?></span><?php } ?>
								<?php } ?>
								</div>
								<div id="additional-menu">
								<?php 	$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' AND link_location='side' ORDER BY link_order ASC ");
								if(mysqli_num_rows($links) <=0) { ?>
								<span class="topmenulink">Sample Menu</span>
								<span class="topmenulink">Sample Menu</span>
								<span class="topmenulink">Sample Menu</span>
								<span class="topmenulink">Sample Menu</span>
								<?php 
								}
								while($link = mysqli_fetch_array($links)) { ?><span class="topmenulink"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></span><?php } ?>
								</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<div id="inside_bg"  style="margin: auto;  ">
				<div id="inside_bg_inner">

				<div id="sidemenu" style="">
					<div id="sidemenulabel">Side Bar Label Text</div>
						<div>&nbsp;</div>

					<div id="sidemenuinner" style="">
						<div id="sidemenumain-menu" class="fontsize">
						<?php 	$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' AND link_location='topmain' ORDER BY link_order ASC ");
						if(mysqli_num_rows($links) <=0) { ?>
						<div class="sidemenuitem">Sample Side Bar Link</div>
						<div class="sidemenuitem">Sample Side Bar Link</div>
						<div class="sidemenuitem">Sample Side Bar Link</div>
						<div class="sidemenuitem">Sample Side Bar Link</div>
						<div class="sidemenuitem">Sample Side Bar Link</div>
						<?php 
						}
						while($link = mysqli_fetch_array($links)) { ?>
						<div class="sidemenuitem"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></div>
						<?php } ?>
						</div>
						<div id="sidemenuadditional-menu">
						<?php 	$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' AND link_location='side' ORDER BY link_order ASC ");
						if(mysqli_num_rows($links) <=0) { ?>
						<div class="sidemenuitem">Sample Side Bar Link</div>
						<div class="sidemenuitem">Sample Side Bar Link</div>
						<div class="sidemenuitem">Sample Side Bar Link</div>
						<div class="sidemenuitem">Sample Side Bar Link</div>
						<div class="sidemenuitem">Sample Side Bar Link</div>
						<?php 
						}

						while($link = mysqli_fetch_array($links)) { ?>
						<div class="sidemenuitem"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></div>
						<?php } ?>
						</div>


					</div>
					<div>&nbsp;</div>
					<div id="sidemenuinner2">
					Misc. text in side bar.
					
					</div>
				</div>
				<div id="pagecontent" style="display: block; float: right; width: 80%;">
					<div id="mainarea" class="editarea">

						<div id="pageTitleContainer" class="pc">
						<span id="page_title" class="pagetitle">This Is The Page Title</span> 
						<br>
						<span id="h2" style="font-size: <?php print $css['h2_size'];?>;" class="pagetitle">H2 Text</span> <span id="h3" style="font-size: <?php print $css['h3_size'];?>;" class="pagetitle">H3 Text</span>
						</div>
						<div class="pc fontsize fontcolor">
						Here you can easily design your website. The left side of the screen are the different sections you can edit. When you select a section to edit, your options for that section show above.
						<br><br>
						When you make changes you will see an instant preview of your change. These changes are not saved until you click the Save Changes button in the upper right corner of the page.
						<br><br>
						You can also preview this theme on your website by clicking the Preview On Website at the top of the page, but be sure to click Save Changes before doing that.
						<p>This is the main text color on the pages. This is the main text color on the pages. This is the main text color on the pages. This is the main text color on the pages. This is the main text color on the pages. This is the main text color on the pages. This is the main text color on the pages. This is the main text color on the pages. This is the main text color on the pages. 
						<br><br>
						<span id="linkContainer">
						<span id="link_color">This is the color for the links on pages.</span>		
						</span>
						
						<br><br>This is the main text color on the pages. This is the main text color on the pages. This is the main text color on the pages. This is the main text color on the pages. This is the main text color on the pages. </p>
						</div>
						</div>



				<div id="formarea" class="editarea" style="display: none; width: 600px; margin: auto;">
				<div class="pc"><h1 class="pagetitle">Contact Forms</h1></div>
				<div class="pc fontsize">Here you can style the forms you can create and add to your websiite.</div>
				<div class="pc fontsize">
				<div class="fontsize">Form Input</div>
				<div><input type="text" name="form1" id="form1" size="50" value="Text in field"></div>
				</div>
				<div class="pc fontsize">
				<div class="fontsize">Form Input</div>
				<div><input type="text" name="form2" id="form2" size="50" value="Text in field"></div>
				</div>

				<div class="pc fontsize">
				<div class="fontsize">Text Area</div>
				<div><textarea name="form3" id="form3" cols="50" rows="5">This is a form text area. This is a form text area. This is a form text area. This is a form text area. This is a form text area. This is a form text area. This is a form text area. This is a form text area. This is a form text area. This is a form text area. This is a form text area. This is a form text area. This is a form text area. </textarea></div>
				</div>
				<div class="pc fontsize">
				<input type="submit" name="form4" id="form4" value="Submit Button" onClick="return false;">  <input type="submit" name="form5" id="form5" value="Submit Disabled" onClick="return false;"> <input type="submit" name="form6" id="form6" value="Submit Hover" onClick="return false;">
				</div>
				</div>



				<div id="boxedarea" class="editarea" style="display: none;">
				<div class="pc pagetitle">Cart / Standard Page Listing</div>
				<div class="pc fontsize">Here you can adjust the style of the standard page listing  & view cart option. This is an option when listing content in a section or category and the view cart page.</div> 

				
				<div class="styled_content fontsize">
				<img style="float: left; margin: 0 8px 8px 0; " src="graphics/photo1.jpg"   class="boxes_img" width="800" height="696" ww="800" hh="696">

				<span class="styled_content_title">Sample Page Title</span>
				<br>This could be a blog post being listed on  a page or a list of pages, etc... This could be a blog post being listed on  a page or a list of pages, etc... This could be a blog post being listed on  a page or a list of pages, etc... This could be a blog post being listed on  a page or a list of pages, etc... 
				<div class="clear"></div>
				</div>

				<div>&nbsp;</div>
				<div class="styled_content fontsize">
				<img style="float: left; margin: 0 8px 8px 0; " src="graphics/photo1.jpg"    class="boxes_img" width="800" height="696" ww="800" hh="696">
				<span class="styled_content_title">Sample Page Title</span>
				<br>This could be a blog post being listed on  a page or a list of pages, etc... This could be a blog post being listed on  a page or a list of pages, etc... This could be a blog post being listed on  a page or a list of pages, etc... This could be a blog post being listed on  a page or a list of pages, etc... 
					<div class="clear"></div>
			</div>


				<div>&nbsp;</div>
				<div class="styled_content fontsize">
				<img style="float: left; margin: 0 8px 8px 0; " src="graphics/photo1.jpg"    class="boxes_img" width="800" height="696" ww="800" hh="696">
				<span class="styled_content_title">Sample Page Title</span>
				<br>This could be a blog post being listed on  a page or a list of pages, etc... This could be a blog post being listed on  a page or a list of pages, etc... This could be a blog post being listed on  a page or a list of pages, etc... This could be a blog post being listed on  a page or a list of pages, etc... 
				<div class="clear"></div>
				</div>
				<div>&nbsp;</div>
				<div class="styled_content fontsize">
				<img style="float: left; margin: 0 8px 8px 0; " src="graphics/photo1.jpg" class="boxes_img" width="800" height="696" ww="800" hh="696">
				<span class="styled_content_title">Sample Page Title</span>
				<br>This could be a blog post being listed on  a page or a list of pages, etc... This could be a blog post being listed on  a page or a list of pages, etc... This could be a blog post being listed on  a page or a list of pages, etc... This could be a blog post being listed on  a page or a list of pages, etc... 
					<div class="clear"></div>
			</div>

				<div>&nbsp;</div>
				<div>&nbsp;</div>
				<div>&nbsp;</div>

				</div>

		<div id="photosarea" class="editarea" style="display: none;">
		<div class="pc pagetitle">Style Photos</div>
		<div class="pc fontsize">Here you can style the photos & thumbnails that are shown on the pages.</div>
			<div class="left" style="margin-right: 16px;">
			<div class="pc fontsize">PAGE PHOTO </div>
			<div class="pc"><img src="<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/graphics/sample-photo.jpg" id="photo_page"></div>			
			</div>

			<div class="left" style="margin-right: 16px;">
			<div class="pc fontsize">Thumbnail</div>
			<div class="pc"><img src="<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/graphics/photo-sample.jpg" id="thumbnail_photo"></div>
			</div>
			<div  style="margin-right: 16px; width: 400px;" class="left">
			<div class="pc fontsize">Captions</div>
			<div id="captionarea" class="pc">

			<div class="pc fontsize">This is the caption area than can be shown on the photo. This is the caption area than can be shown on the photo. This is the caption area than can be shown on the photo.</div>
			</div>
			
			<div>&nbsp;</div>
			<!--
			<div class="pc">Scrolling thumbnails</div>
			<div id="scrollthumbscontainer"  style="width: 100%; background: #ffffff;padding: 4px; ">
				<div id="scrollthumbstext">1/12 &nbsp; Thumbnails</div>
				<div id="scrollthumbsthumbs" style="overflow: hidden;">
				<div style="width: 800px;">
					<img class="tlthumbnail" style="display: inline; height: 100px; width: auto;" src="graphics/photo2.jpg" >
					<img class="tlthumbnail" style="display: inline; height: 100px; width: auto;" src="graphics/photo2.jpg" >
					<img class="tlthumbnail" style="display: inline; height: 100px; width: auto;" src="graphics/photo2.jpg" >
					<img class="tlthumbnail" style="display: inline; height: 100px; width: auto;" src="graphics/photo2.jpg" >
					<img class="tlthumbnail" style="display: inline; height: 100px; width: auto;" src="graphics/photo2.jpg" >
					</div>
				</div>

				<div id="sc" style=" background: #949494; position: relative; margin: 4px 0 0 0px; clear: both; height: 20px;border-radius: 4px;"><div id="scroll_handle_bg" style="#890000; width: 100%; height:15px; position: relative;"><div id="scroll_handle" style="width: 100px; margin-left: 20px;background-color: #d4d4d4; border-radius: 4px; height: 16px; cursor: pointer; overflow: hidden; position: absolute; left: 0; top: 2px; box-shadow: 0px 0px 7px 0px rgba(0,0,0,0.2) ,0px 0px 6px 6px rgba(255,255,255,0.3) inset;"> </div></div></div>
				
				
				</div>
	-->

			</div>

			<div class="cssClear"></div>
		</div>

<div id="onphotopreviewarea" class="editarea" style="display: none;">
<div class="pagetitle pc">On Photo Listing Option</div>
<div class="pc  fontsize">On Photo Listing is another option to display content in a section or category. The photo fills the container and the page title and preview text is show on the photo. You can adjust the width, height, spacing, etc... </div>
	<div id="onphotoPreviews">
	<?php $x=1;
		while($x <=12) { 
			?>
		<div class="onphotopreview preview">
		<img style="display: inline;" src="graphics/photo1.jpg" class="onphotophoto" id="th-<?php print $x;?>" width="800" height="696" ww="800" hh="696">
		<div class="onphotopreview-text text">
		<div class="onphotopreview-headline headline">This Is The Title Here</div>
		<div class="onphotopreview-previewtext previewtext fontsize" style="display: none;">Some preview text will appear here.</div>
		</div>
		</div>
		<?php 
				$x++;
		}
		?>
		<div class="clear"></div>

	</div>
</div>

<div id="thumbnaillistingarea" class="editarea" style="display: none;">
<div class="pagetitle pc">Thumbnail & Stacked Listing Options</div>
<div class="pc fontsize">This is another option you have to list content in a section or category.   You can adjust the width, height, spacing, etc... </div>
	<div id="thumbnaillisting">
	<?php $x=1;
		while($x <=12) { 
			?>
		<div class="thumbnaillistingpreview preview">
		<div class="thumbnaillistingpreview-image tlthumb">
		<?php if($x%2) { ?>
			<img class="tlthumbnail pt" style="display: inline;" src="graphics/photo3.jpg" id="thl-<?php print $x;?>" width="150" height="200" ww="150" hh="200">
		<?php } else { ?>
			<img class="tlthumbnail ls" style="display: inline;" src="graphics/photo2.jpg" id="thl-<?php print $x;?>" width="200" height="150" ww="200" hh="150">
		<?php } ?>
		</div>
		<div class="thumbnaillistingpreview-text text">
		<div class="thumbnaillistingpreview-headline headline">This Is The Title Here</div>
		<div class="thumbnaillistingpreview-other other fontsize"><?php print date('M d, Y');?></div>
		</div>
		</div>
		<?php 
				$x++;
		}
		?>
		<div class="clear"></div>

	</div>
</div>




<div id="thumb_nailsarea" class="editarea" style="display: none;">
<div class="pagetitle pc">Styled Thumbnail Gallery</div>
<div class="pc fontsize">When you display thumbnail galleries, you have an option for styled thumbnails. This allows you to display the thumbnails in a styled container. Here you can adjust the style of those.</div>
	<div id="thumb_naillis">
	<?php $x=1;
		while($x <=12) { 
			?>
		<div class="thumb_nailspreview preview">
		<div class="thumb_nailspreview-image tlthumb">
		<?php if($x%2) { ?>
			<img class="tnthumbnail tnpt" style="display: inline;" src="graphics/photo3.jpg" id="thl-<?php print $x;?>" width="150" height="200" ww="150" hh="200">
		<?php } else { ?>
			<img class="tnthumbnail tnls" style="display: inline;" src="graphics/photo2.jpg" id="thl-<?php print $x;?>" width="200" height="150" ww="200" hh="150">
		<?php } ?>
		</div>
		<div class="thumb_nailspreview-text text">
		<div class="thumb_nailspreview-headline headline">Filename or icons</div>
		</div>
		</div>
		<?php 
				$x++;
		}
		?>
		<div class="clear"></div>

	</div>
</div>



</div>
<div class="clear"></div>
</div>



		<div id="fullscreenphotosarea" class="editarea" style="display: none;">
				<div>&nbsp;</div>
				<div>&nbsp;</div>
				<div>&nbsp;</div>

				<div>&nbsp;</div>
				<div>&nbsp;</div>
				<div>&nbsp;</div>

				<div>&nbsp;</div>
				<div>&nbsp;</div>
				<div>&nbsp;</div>


		<div id="fullscreenbackground"></div>
		<div style=" position: fixed; top: 200px; left: 10%; width: 90%; height: 80%;z-index: 25; text-align: center;">
			<div class="pc" ><img src="<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/graphics/sample-photo.jpg" id="enlarged_photo" style="margin: auto;"></div>



			<div id="photo_caption" class="fontsize">FULL SCREEN PHOTO CAPTIONS<br>
			If the photo is set to fill the screen, this will be placed by the placement options. If the photo is set to fit to screen, then it will be placed over the bottom of the photo.
			</div>
			<div class="cssClear"></div>
		</div>
		</div>

		<div id="footerinside" class="footer" style="background: #000000; color: #FFFFFF; text-align: center;"><?php include "theme-edit-footer.php";?></div>

		</div></div></div></div>

		<div id="footeroutside" class="footer" style="background: #000000; color: #FFFFFF; text-align: center;"><?php include "theme-edit-footer.php";?></div>


			</div>
			</div>
		</div>
	</div>
	</div>
</div>

<div>&nbsp;</div>
