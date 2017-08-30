	<div >
		<div>
			<div class="pc"><?php print _first_name_;?></div>
			<div class="pc"><input type="text" name="first_name" id="first_name" size="20" value="<?php print htmlspecialchars($order['order_first_name']);?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
		</div>
	</div>
	<div >
		<div>
			<div class="pc"><?php print _last_name_;?></div>
			<div class="pc"><input type="text" name="last_name" id="last_name" size="20" value="<?php print htmlspecialchars($order['order_last_name']);?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
		</div>
	</div>
<div class="cssClear"></div>

	<div>
		<div>
			<div class="pc"><?php print _address_;?></div>
			<div class="pc"><input type="text" name="address" id="address" size="40"    value="<?php print htmlspecialchars($order['order_address']);?>" class="newacc field100 required"></div>
		</div>
	</div>
<div class="cssClear"></div>


	<div >
		<div>
			<div class="pc"><?php print _city_;?></div>
			<div class="pc"><input type="text" name="city" id="city"  size="30" value="<?php print htmlspecialchars($order['order_city']);?>" class="newacc field100 required"></div>
		</div>
	</div>


	<div style="float: left;">
			<div class="pc"><?php print _state_;?></div>
			<div class="pc">
			<select name="state" id="state" class="newacc required" >
			<option value=""><?php print _select_state_;?></option>
			<?php $states = whileSQL("ms_states LEFT JOIN ms_countries ON ms_states.state_country=ms_countries.country_name", "*", "WHERE ms_countries.ship_to='1' AND ms_states.state_ship_to='1' ORDER BY state_name ASC ");
			while($state = mysqli_fetch_array($states)) { ?>
			<option value="<?php print $state['state_abr'];?>" <?php if($order['order_state'] == $state['state_abr']) { print "selected"; } ?>><?php print $state['state_name']." (".$state['abr'].")"; ?></option>
			<?php } ?>
			</select>
		</div>
		</div>

		<div style="float: left;">
			<div class="pc"><?php print _zip_;?></div>
			<div class="pc"><input type="text" name="zip" id="zip" size="8" value="<?php print htmlspecialchars($order['order_zip']);?>" class="newacc required" ></div>
		</div>
		<div class="cssClear"></div>

		<div>
			<div class="pc"><?php print _country_;?></div>
			<div class="pc">
			<select name="country"  id="country"  class="newacc required" >
			<?php
			$cts = whileSQL("ms_countries", "*", " WHERE ship_to='1' ORDER BY def DESC, country_name ASC");

			while($ct = mysqli_fetch_array($cts)) {
				print "<option value=\"".$ct['country_name']."\" "; if($order['order_country'] == $ct['country_name']) { print " selected"; } print ">".$ct['country_name']."</option>";
			}
			print "</select>";
			?>
		</div>
		</div>
		<div>&nbsp;</div>