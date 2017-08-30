<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }
if(!empty($_REQUEST['submitit'])) {

	if(!empty($error)) {
		print "<div class=error>$error</div><div>&nbsp;</div>";
		regForm();
	} else {

		foreach($_REQUEST AS $id => $value) {
			$_REQUEST[$id] = addslashes(stripslashes($value));
		//	print "<li>$id = $value";
		}


	updateSQL("ms_settings", "rss_feed_start='".$_REQUEST['rss_feed_start']."' ");   		
		$_SESSION['sm'] = "Settings saved";
		session_write_close();
		header("location: index.php?do=settings&action=rss");
		exit();
	?>
	<?php 
	}
	} else {
	regForm();
}
?>	

<?php  
function regForm() {
	global $tr, $_REQUEST, $setup, $site_setup;
	?>
<div id="pageTitle"><a href="index.php?do=settings">Settings</a> <?php print ai_sep;?> RSS Feed</div>



<div style="width: 60%; float: left;">
<div class="pageContent">
<p>RSS stands for "Really Simple Syndication" and is integrated into your Blog posts. <a href="http://www.google.com/search?hl=&q=how+does+rss+feed+work&sourceid=navclient-ff&rlz=1B3GGGL_enUS338US339&ie=UTF-8">Here is a google search on how RSS feeds work</a></p>
<p>&nbsp;</p>
<p>In <i>sytist</i>, you can also use the RSS feeds to automatically add your blog posts to your Facebook Page Wall. (This would be your Facebook "Page" for your business, not your personal profile).</p>
<p>&nbsp;</p>
<p><span class="bold">Your RSS Links</span></p>
<p>Standard RSS Feed: <a href="/feed/"><?php print $setup['url'];?>/feed/</a></p>
<p>Facebook RSS Feed: <a href="/fbfeed/"><?php print $setup['url'];?>/fbfeed/</a></p>
<p>&nbsp;</p>
<p>The only difference between the 2 are how the blog thumbnails are used since Facebook doesn't handle it like oither readers</p>
<p>&nbsp;</p>

<p><span class="bold">Adding your blog posts to your Facebook "Page” wall via RSS feed.</span></p>
<p>This is for your Facebook "Page”, not your Facebook personal "Profile”. (Refer to the manual for instructions with screen shots).</p>
<p>&nbsp;</p>
<p>
<ul>
<li>1) Go to your Facebook page.</li>
<li>2) Click on Edit Page</li>
<li>3) Click on Apps in the left menu</li>
<li>4) Under Notes, click on Edit Settings</li>
<li>5) In the Additional Permissions tab, check the Publish content to my Wall and click Okay.</li>
<li>6) Click Go to app</li>
<li>7) Bottom left of the page, click "Edit import Settings”</li>
<li>8) Enter in yourwebsite address / fbfeed … like http://www.sytist.com/fbfeed/ . Replace sytist with your website. Click start importing.</li>
<li>The next page you will be able to review the import before applying.</li>
</ul>

</div>
</div>
<div style="width: 38%; float: right;">

<form name="register" action="index.php" method="post" style="padding:0; margin: 0;">
<div id="roundedSide">
	<div class="label">Facebook RSS Feed Start Date</div>
	<div class="roundedSideSep">When you add your feed to your Facebook page, it will post all your blog posts for the first time. Instead, you can enter in the date to start adding from below.</div>
	<div class="roundedSideSep"><input type="text" name="rss_feed_start" value="<?php print $site_setup['rss_feed_start'];?>"><br>YYYY-MM-DD Format</div>
	<div class="roundedSideSep">
	<input type="hidden" name="do" value="settings">
	<input type="hidden" name="action" value="rss">
	<input type="hidden" name="submitit" value="yup">
	<input  type="submit" name="submit" value="Update Settings" class="submit">
	</div>
</div>
</form>
</div>

<div>&nbsp;</div>

<div class="cssClear"></div>
<div>&nbsp;</div>

<?php  } ?>
