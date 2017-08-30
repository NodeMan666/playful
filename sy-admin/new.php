<style>
.gettingstarted { } 
.gettingstarted .inner{ padding: 16px; border-bottom: dashed 2px  #c4c4c4;  margin: 8px; } 
.gettingstarted .inner h3 a { font-weight: bold; text-shadow: 0px 0px 2px #FFFFFF;font-family:  arial; font-size: 17px; }
</style>
 <?php if($setup['unbranded'] !== true) { ?>
<div class="underlinelabel center"><h2>Getting Started With Sytist</h2></div>
<?php } else { ?>
<div class="underlinelabel center"><h2>Getting Started</h2></div>
<?php } ?>
<div>
 <?php if($setup['unbranded'] !== true) { ?>

<div class="pc center"><a href="https://www.picturespro.com/sytist-manual/" target="_blank">Sytist Manual</a>  &nbsp; &nbsp;   
<a href="https://www.picturespro.com/sytist-manual/video-tutorials/" target="_blank">Video Tutorials</a>   &nbsp; &nbsp;   
<a href="https://www.picturespro.com/support-forum/sytist/" target="_blank">Support</a></div>
<div class="pc center">Here is a little help to get you going with your new Sytist installation. 
<br><br>In the <a href="https://www.picturespro.com/sytist-manual/video-tutorials/" target="_blank">video tutorials area</a> of the <a href="https://www.picturespro.com/sytist-manual/" target="_blank">manual</a> there are several tutorials. See <a href="https://www.picturespro.com/sytist-manual/video-tutorials/getting-started/" target="_blank">Getting Started</a> for an overview of the admin and <a href="https://www.picturespro.com/sytist-manual/video-tutorials/creating-a-section-for-selling-photos/" target="_blank">Creating a Section For Selling Photos</a>.
</div>
<?php } ?>
<?php include "new-info.php"; ?>


<?php if($site_setup['index_page'] == "indexnew.php") { ?>
<div class="gettingstarted">
	<div class="inner">
		<h3><a href="index.php?do=activateSite">Make Your Website Live!</a></h3>
		When you are ready to go live with your new website, <a href="index.php?do=activateSite">Click here</a>!
	</div>
</div>
<?php } ?>
<div class="clear"></div>
<div class="pc center">
<!-- <a href="" onclick="newwizard(); return false;">Show the first getting started wizard</a> -->
<br><a href="index.php?do=settings&action=hideHelp" onClick="return confirm('This will remove the getting started help from the admin home page and remove the side tab. You can always turn it back on in Settings > Admin / Main Settings. Click OK to continue.');"> Click here to hide getting started</a></div>
</div>
