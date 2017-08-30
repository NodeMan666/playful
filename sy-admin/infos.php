<?php
/* To add an information icon, add the following code: 
<div class="moreinfo" info-data="noaccountemail"><div class="info"></div></div>

info-data attribute is the ID of the information div to show.
*/
?>
<script>
/*
 $(document).ready(function(){


	$(".moreinfo").click(function() { 
		id = $(this).attr("info-data");
		// alert(id);
		$("#sypopupbg").attr("current-id",id);
		$("#sypopupbg").after('<div id="'+id+'" class="sypopup"><div style="float:right; margin: 8px;"><span class="the-icons icon-cancel" onclick="closesypopup(); return false;"></span></div><div class="inner">'+$("#"+id).html()+'</div></div>');
		topp = $(this).position();
		// alert(topp.top);
		newtop = topp.top - 100;
		if(newtop < 50) { 
			newtop = 50;
		}
		$("#"+id).css({"top":newtop+"px"});

		$("#sypopupbg").fadeIn(50, function() { 
			$("#"+id).fadeIn(50);
		});
	});


});

function closesypopup() { 
	$(".sypopup").fadeOut(50, function() { 
		$("#sypopupbg").fadeOut(50);
		$("#"+$("#sypopupbg").attr("current-id")).remove();
	});
}
*/
</script>

<div id="sypopupbg"  data-window="" onclick="closesypopup(); return false;" ></div>


<div class="infos" id="prodgroups">Product groups allow you to group products. Example: Gift Prints group, Downloads group, Collections group, etc...
		<br><br>When you have more than one product group, it will show buttons above the products to show that group of products. 
</div>

<div class="infos" id="prodgroupsrequirepurchase">Selecting this option will make it so someone has to select a product from this group before they can select a product from another group. If they were to remove this item from their cart, it will remove any other items selected so they are forced to purchase a product from this group before purchasing other items.</div>

<div class="infos" id="addproductstocollection">Select products from your product base to add to this collection. If there is a product you want to add that is not on this list, you will first need to add it to your <a href="index.php?do=photoprods&view=base">product base</a>. </div>

<div class="infos" id="packageshippable">Checking this means it is available for shipping. Unselected, no shipping will be calculated on it.</div>
<div class="infos" id="selectonlypackage">This option means they will just be selecting photos only and no actual products are included. <br><br>Example, for an album you just need them to select 30 photos. You can use this option for that.</div>
<div class="infos" id="assignphotospackage">This option means you will select what products from your product base are included in the package.<br><br>Example: 1: 8x10, 2: 5x7, etc...</div>

<div class="infos" id="catcontentpage">This option allows you to show the content of a page in this category when this section is viewed.<br><br>Example: if you have an about page in this section and you choose about here, then when someone goes to this category, they will see the contents of the about page.</div>

<div class="infos" id="nodiscount">Selecting this will not allow for this product to be discounted with coupons.</div>
<div class="infos" id="homerecentpages">Here you can set a section to show recent pages from.</div>
<div class="infos" id="exportfirstlast">Example: Firstname Lastname</div>
<div class="infos" id="exportlastfirst">Example: Lastname Firstname </div>
<div class="infos" id="selectfromallphotos">Select from photos you have already uploaded to the system.</div>
<div class="infos" id="printcreditshipping">Select this if you want this print credit be available for shipping and apply any shipping charged.</div>

<div class="infos" id="packageextraphoto">If you want your customers to be able to purchase additional photos of an existing pose in the collection at a discounted rate, enter in the amount for the additional photos below. Enter 0.00 to not allow purchasing of additional photos.<br><br><u>If using this feature you MUST have a price for both!</u></div>
<div class="infos" id="packageextraphotonew">If you want your customers to be able to purchase additional photos of a NEW pose at a discounted rate, enter in the amount for the additional photos below. Enter 0.00 to not allow purchasing of additional photos.<br><br><u>If using this feature you MUST have a price for both!</u></div>


<div class="infos" id="copyphotostofolder">This feature will copy all of the original photos files on this order into 1 folder and also create a zip file. Example, they will go into a folder like /sy-photos/order-photos/<?php print $order['order_id'];?>/hashed-name</div>

<div class="infos" id="prodgroupsbuyall">You must select this option if you are placing Buy Alls in this product group so it can show the amount of photos above the products. </div>
<div class="infos" id="prodnodiscount">Selecting this option will not allow any discounts on this products<br> (like when using a coupon)</div>
<div class="infos" id="prodnoshipping">Selecting this option will not calculate shipping and no shipping options will be offered.</div>
<div class="infos" id="productgroupstoreitems">Selecting this option means you have created a section for store items and want to feature some of those items in this price list. This would be items your customers may select photos for.</div>
<div class="infos" id="nosubgals">Selecting this option this page will not show the sub galleries and show the find my photos form only.</div>

<div class="infos" id="catmetatitle">The meta title is showing at the top of the browser, not on the actual page. Leave this blank to use the section / category name as the meta title. </div>
<div class="infos" id="nodownloadphoto">When selecting this option for a download photo, the download file will not be immediately available when a customer places an order.
<br><br>
This will allow you to upload a file for them to download like an edited file or if you don't want to store large photos on the server then upload the large file for them to download.
<?php if($setup['unbranded'] !== true) { ?>
<br><br><a href="http://www.picturespro.com/sytist-manual/articles/do-not-allow-download-until-i-upload-a-replacement-file/" target="_blank">See this article for more information on this option</a>.<?php } ?>
</div>

<div class="infos" id="timeblocks">This option will create a drop down of time blocks in a gallery for the visitor to select from and pull up photos based on the date / time taken of the photo. <?php if($setup['unbranded'] !== true) { ?>
<br><br><a href="http://www.picturespro.com/sytist-manual/articles/sorting-photos-by-time-blocks/" target="_blank">See this article for more information</a>.<?php } ?></div>

<div class="infos" id="paidaccessinfo">Paid access will require the customer to pay to view the page. You can also enter in a credit that will be applied to their account when purchased.<br><br>If you use this option you MUST select "Require create an account" under <a href="index.php?do=settings&action=checkout">Customer Account Options On Checkout</a> in Settings -> Checkout.</div>
<div class="infos" id="moveamazon">Since you have Amazon S3 enabled, you can select to move the photos to the Amazon S3 server now. <b>Note this will slow down the upload process</b>. Alternatively you can move the photos to Amazon S3 after the upload process has finished.</div>
<div class="infos" id="nolistcatpages">By selecting this option, it will not list out pages in this section when someone views this page. This option is good if you just want people to enter in a password to access their gallery or photos.</div>
<div class="infos" id="collectionincludecollection">This means you are creating a master collection that includes other collections.</div>
<div class="infos" id="freedownload">You would only select this option if adding a free download to a price list where the customers can download for free right from the gallery. Items marked as free download can not be purchased.</div>

<div class="infos" id="noaccountemail">This can happen when someone logs in with Facebook and they don't have a confirmed email account or created their facebook account with a phone number or choose not to share their email address.</div>


<div class="infos" id="requiredeposit">Selecting this option, the customer will have to go through checkout and pay the deposit amount for the booking request to go through. Otherwise they will just fill out the information with no payment and you will receive a notification of the booking request. </div>

<div class="infos" id="autoconfirmdeposit">Selecting this option the booking request will automatically confirm and send out the confirmation email. Un-selected, the booking request will come in as unconfirmed until you confirm it.</div>
<div class="infos" id="autoconfirmnodeposit">Selecting this option, once someone submits the booking request form, the appointment is automatically confirmed and they are sent the confirmation email and they will not make a payment. </div>
