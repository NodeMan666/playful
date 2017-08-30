<?php if($site_setup['css'] <=0) { ?>
<div class="gettingstarted">
	<div class="inner">
		<h3><a href="index.php?do=look&view=css">Select A Theme - Design</a></h3>
		The theme controls how your website looks. It is a combination of fonts, color, etc .... You can adjust and change your theme at any time.
	</div>
</div>
<?php  } ?>
<div class="gettingstarted">
	<div class="inner">
		<h3><a href="index.php?do=news&action=editCategory">Create Sections For Galleries & Other Content</a></h3>
		Sections are like categories. "Client Galleries" would be an example of a section. Then you can create pages & galleries within those sections. You can also create top level pages for pages like Contact, About Us, etc...  
		 <?php if($setup['unbranded'] !== true) { ?>
		<br><br>
		<a href="https://www.picturespro.com/sytist-manual/site-content/section/" target="_blank">More information on sections in the manual</a>
		<?php } ?>
	</div>
</div>

<div class="gettingstarted">
	<div class="inner">
		<h3><a href="index.php?do=photoprods">Photo Products</a></h3>
		For selling photos, you can set up the products & collections you want to offer.
		 <?php if($setup['unbranded'] !== true) { ?>
		<br><br><a href="https://www.picturespro.com/sytist-manual/photo-products/" target="_blank">More information on Photo Products in the manual</a>
		<?php } ?>
	</div>
</div>

<div class="gettingstarted">
	<div class="inner">
		<h3><a href="index.php?do=look&view=header">Upload a Logo or Edit Your Header</a></h3>
		The header is what is shown at the top of all your pages ... your name or logo. The name you entered as your website name was automatically added when registered. 
	</div>
</div>


<div class="gettingstarted">
	<div class="inner">
		<h3><a href="index.php?do=news&action=addDate&date_id=1047">Manage Your Home Page</a></h3>
		Once you have added some content, you can choose to have some of that content featured on your home page, add a slideshow or some text.	
	</div>
</div>

<?php if(empty($site_setup['meta_descr'])) { ?>
<div class="gettingstarted">
	<div class="inner">
		<h3><a href="index.php?do=settings&action=meta">Metadata</a></h3>
		Metadata is not shown on the pages, but used by search engines to help find you. 
	</div>
</div>
<?php } ?>
