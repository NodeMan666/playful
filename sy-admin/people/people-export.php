<div id="pageTitle"><a href="index.php?do=people">People</a> <?php print ai_sep;?> Export</div>
<div class="pc">Here you can export your customer information. This can be used to import into a mailing list system.</div>


<form method="post" name="export" action="./export.php" target="_blank">
<div class="left p25">
<div class="underlinelabel">Fields to export</div>

<div class="underline"><input type="checkbox" name="id" id="id"> <label for="id">ID</label></div>
<div class="underline"><input type="checkbox" name="company" id="company"> <label for="company">Company</label></div>
<div class="underline"><input type="checkbox" name="email" id="email" checked> <label for="email">Email Address</label></div>

<div class="underline"><input type="checkbox" name="firstlastName" id="firstlastName" > <label for="firstlastName">First Last Name</label></div>
<div class="underline"><input type="checkbox" name="lastfirstName" id="lastfirstName"> <label for="lastfirstName">Last First Name</label></div>

<div class="underline"><input type="checkbox" name="firstName" id="firstName" checked> <label for="firstName">First Name Only</label></div>
<div class="underline"><input type="checkbox" name="lastName" id="lastName" checked> <label for="lastName">Last Name Only</label></div>
<div class="underline"><input type="checkbox" name="phone" id="phone"> <label for="phone">Phone</label></div>
<div class="underline"><input type="checkbox" name="address" id="address"> <label for="address">Address</label></div>
<div class="underline"><input type="checkbox" name="city" id="city"> <label for="city">City</label></div>
<div class="underline"><input type="checkbox" name="state" id="state"> <label for="state">State</label></div>
<div class="underline"><input type="checkbox" name="zip" id="zip"> <label for="zip">Zip</label></div>
<div class="underline"><input type="checkbox" name="country" id="country"> <label for="country">Country</label></div>
<div class="underline"><input type="checkbox" name="date" id="date"> <label for="date">Date</label></div>

</div>

<div class="right p65">
<div class="underlinelabel">Who to export</div>
<div class="underline"><input type="checkbox" name="registered" id="registered" value="1" checked> <label for="registered">Registered People  (<?php print countIt("ms_people", "");?>)</label></div>
<div class="underline"><input type="checkbox" name="unregistered" id="unregistered" value="1" checked> <label for="unregistered">Unregistered People (<?php 
$orders = whileSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_customer='0' GROUP BY order_email ORDER BY order_id ASC "); 
print mysqli_num_rows($orders); ?>)</label></div>
<div class="underline"><input type="checkbox" name="mailinglist" id="mailinglist" value="1" checked> <label for="mailinglist">Mailing List (<?php print countIt("ms_email_list", "WHERE em_status='0' ");?>)</label></div>

<div class="underlinespacer">&nbsp;</div>
<div class="underlinelabel">Order by</div>
<div class="underline">
<select name="order_by">
<option value="p_id">ID</option>
<option value="p_email">Email Address</option>
<option value="p_last_name">Last Name</option>
</select>

 
	  <select name="acdc">
	<option value="ASC">Acending</option>
	<option value="DESC">Decending</option>
	</select>
	</div>
	<div class="underlinelabel">Separate fields with</div>
	<div class="underline"> <input type="text" name="sep" size="2" value=","> </div>
	<div class="underlinelabel">Do with</div>
		<div class="underline"> <input type="radio" name="dowith" value="download" id="dowidthdownload" checked> <label for="dowidthdownload">Download as CSV</label> 
		<input type="radio" name="dowith" value="view" id="dowithview"> <label for="dowithview">Print to screen</label> 
	</div>

	<div class="pc">
	<input type="hidden" name="action" value="people">
	<input type="submit" name="submit" value="Export" class="submit">
	</div>

	</div>


</div>
<div class="clear"></div>
</form>

