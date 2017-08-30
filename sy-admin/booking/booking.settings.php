<?php
if($_REQUEST['action'] == "save") { 
	updateSQL("ms_bookings_settings", "
	Sunday='".$_REQUEST['Sunday']."',
	Monday='".$_REQUEST['Monday']."',
	Tuesday='".$_REQUEST['Tuesday']."',
	Wednesday='".$_REQUEST['Wednesday']."',
	Thursday='".$_REQUEST['Thursday']."',
	Friday='".$_REQUEST['Friday']."',
	Saturday='".$_REQUEST['Saturday']."',
	start_time='".$_REQUEST['start_time']."',
	end_time='".$_REQUEST['end_time']."',
	time_blocks='".$_REQUEST['time_blocks']."',
	Monday_ado='".$_REQUEST['Monday_ado']."',
	Tuesday_ado='".$_REQUEST['Tuesday_ado']."',
	Wednesday_ado='".$_REQUEST['Wednesday_ado']."',
	Thursday_ado='".$_REQUEST['Thursday_ado']."',
	Friday_ado='".$_REQUEST['Friday_ado']."',
	Saturday_ado='".$_REQUEST['Saturday_ado']."',
	Sunday_ado='".$_REQUEST['Sunday_ado']."',
	Monday_start_time='".$_REQUEST['Monday_start_time']."',
	Monday_end_time='".$_REQUEST['Monday_end_time']."',
	Tuesday_start_time='".$_REQUEST['Tuesday_start_time']."',
	Tuesday_end_time='".$_REQUEST['Tuesday_end_time']."',
	Wednesday_start_time='".$_REQUEST['Wednesday_start_time']."',
	Wednesday_end_time='".$_REQUEST['Wednesday_end_time']."',
	Thursday_start_time='".$_REQUEST['Thursday_start_time']."',
	Thursday_end_time='".$_REQUEST['Thursday_end_time']."',
	Friday_start_time='".$_REQUEST['Friday_start_time']."',
	Friday_end_time='".$_REQUEST['Friday_end_time']."',
	Saturday_start_time='".$_REQUEST['Saturday_start_time']."',
	Saturday_end_time='".$_REQUEST['Saturday_end_time']."',
	Sunday_start_time='".$_REQUEST['Sunday_start_time']."',
	Sunday_end_time='".$_REQUEST['Sunday_end_time']."',
	Monday_time_blocks='".$_REQUEST['Monday_time_blocks']."',
	Tuesday_time_blocks='".$_REQUEST['Tuesday_time_blocks']."',
	Wednesday_time_blocks='".$_REQUEST['Wednesday_time_blocks']."',
	Thursday_time_blocks='".$_REQUEST['Thursday_time_blocks']."',
	Friday_time_blocks='".$_REQUEST['Friday_time_blocks']."',
	Saturday_time_blocks='".$_REQUEST['Saturday_time_blocks']."',
	Sunday_time_blocks='".$_REQUEST['Sunday_time_blocks']."',
	do_not_show_on_people_list='".$_REQUEST['do_not_show_on_people_list']."'
	");
	
	$_SESSION['sm'] = "Settings saved";
	session_write_close();
	header("location: index.php?do=booking&view=settings");
	exit();

}
?>

<div id="pageTitle">Booking Calendar Settings</div>
<?php if(countIt("ms_blog_categories", "WHERE cat_type='booking' ")<=0) { ?>
<div id="bluenotice" style="font-size: 17px; text-align: center;">To offer services people can book online, you must first create a new section as a booking calendar and add services for them to select from. <br><a href="index.php?do=news&action=editCategory">Click here to create a new section</a>.</div>
<div>&nbsp;</div>

<?php } ?>



<form name="register" action="index.php" method="post" style="padding:0; margin: 0;"  onSubmit="return checkForm('','submit');">
<style>
<?php if($booksettings['Monday'] == "0") { ?>.Monday { display: none; } <?php } ?>
<?php if($booksettings['Tuesday'] == "0") { ?>.Tuesday { display: none; } <?php } ?>
<?php if($booksettings['Wednesday'] == "0") { ?>.Wednesday { display: none; } <?php } ?>
<?php if($booksettings['Thursday'] == "0") { ?>.Thursday { display: none; } <?php } ?>
<?php if($booksettings['Friday'] == "0") { ?>.Friday { display: none; } <?php } ?>
<?php if($booksettings['Saturday'] == "0") { ?>.Saturday { display: none; } <?php } ?>
<?php if($booksettings['Sunday'] == "0") { ?>.Sunday { display: none; } <?php } ?>
</style>
<div class="underlinelabel">Available Days</div>
<div class="underlinespacer">Select the days & times to be available on the booking calendar. All day services only means that only services marked as all day can only be booked on those days.</div>
<div class="underline">
	<div class="left p25"><input type="checkbox" value="1" name="Sunday" id="Sunday" <?php if($booksettings['Sunday'] == "1") { ?>checked<?php } ?> onchange="updateavday('Sunday');"> <label for="Sunday">Sunday</label></div>
	<div class="left p25 Sunday <?php if($booksettings['Sunday'] == "0") { ?>hide<?php } ?>"><input type="checkbox" value="1" name="Sunday_ado" id="Sunday_ado" <?php if($booksettings['Sunday_ado'] == "1") { ?>checked<?php } ?> onchange="updateado('Sunday_ado');"> <label for="Sunday_ado">All day services only</label></div>
	<div class="left p50 Sunday_ado Sunday  <?php if($booksettings['Sunday_ado'] == "1") { ?>hide<?php } ?>"><?php  daytimes('Sunday','Sunday_start_time','Sunday_end_time','Sunday_time_blocks');?></div>
	<div class="clear"></div>
</div>
<div class="underline">
	<div class="left p25"><input type="checkbox" value="1" name="Monday" id="Monday" <?php if($booksettings['Monday'] == "1") { ?>checked<?php } ?> onchange="updateavday('Monday');"> <label for="Monday">Monday</label></div>
	<div class="left p25 Monday"><input type="checkbox" value="1" name="Monday_ado" id="Monday_ado" <?php if($booksettings['Monday_ado'] == "1") { ?>checked<?php } ?> onchange="updateado('Monday_ado');"> <label for="Monday_ado">All day services only</label></div>
	<div class="left p50 Monday_ado Monday <?php if($booksettings['Monday_ado'] == "1") { ?>hide<?php } ?>"><?php  daytimes('Monday','Monday_start_time','Monday_end_time','Monday_time_blocks');?></div>
	<div class="clear"></div>
</div>

<div class="underline">
	<div class="left p25"><input type="checkbox" value="1" name="Tuesday" id="Tuesday" <?php if($booksettings['Tuesday'] == "1") { ?>checked<?php } ?> onchange="updateavday('Tuesday');"> <label for="Tuesday">Tuesday</label></div>
	<div class="left p25 Tuesday"><input type="checkbox" value="1" name="Tuesday_ado" id="Tuesday_ado" <?php if($booksettings['Tuesday_ado'] == "1") { ?>checked<?php } ?> onchange="updateado('Tuesday_ado');"> <label for="Tuesday_ado">All day services only</label></div>
	<div class="left p50 Tuesday_ado Tuesday <?php if($booksettings['Tuesday_ado'] == "1") { ?>hide<?php } ?>"><?php  daytimes('Tuesday','Tuesday_start_time','Tuesday_end_time','Tuesday_time_blocks');?></div>
	<div class="clear"></div>
</div>

<div class="underline">
	<div class="left p25"><input type="checkbox" value="1" name="Wednesday" id="Wednesday" <?php if($booksettings['Wednesday'] == "1") { ?>checked<?php } ?> onchange="updateavday('Wednesday');"> <label for="Wednesday">Wednesday</label></div>
	<div class="left p25 Wednesday"><input type="checkbox" value="1" name="Wednesday_ado" id="Wednesday_ado" <?php if($booksettings['Wednesday_ado'] == "1") { ?>checked<?php } ?> onchange="updateado('Wednesday_ado');"> <label for="Wednesday_ado">All day services only</label></div>
	<div class="left p50 Wednesday_ado Wednesday <?php if($booksettings['Wednesday_ado'] == "1") { ?>hide<?php } ?>"><?php  daytimes('Wednesday','Wednesday_start_time','Wednesday_end_time','Wednesday_time_blocks');?></div>
	<div class="clear"></div>
</div>

<div class="underline">
	<div class="left p25"><input type="checkbox" value="1" name="Thursday" id="Thursday" <?php if($booksettings['Thursday'] == "1") { ?>checked<?php } ?> onchange="updateavday('Thursday');"> <label for="Thursday">Thursday</label></div>
	<div class="left p25 Thursday"><input type="checkbox" value="1" name="Thursday_ado" id="Thursday_ado" <?php if($booksettings['Thursday_ado'] == "1") { ?>checked<?php } ?> onchange="updateado('Thursday_ado');"> <label for="Thursday_ado">All day services only</label></div>
	<div class="left p50 Thursday_ado Thursday <?php if($booksettings['Thursday_ado'] == "1") { ?>hide<?php } ?>"><?php  daytimes('Thursday','Thursday_start_time','Thursday_end_time','Thursday_time_blocks');?></div>
	<div class="clear"></div>
</div>

<div class="underline">
	<div class="left p25"><input type="checkbox" value="1" name="Friday" id="Friday" <?php if($booksettings['Friday'] == "1") { ?>checked<?php } ?> onchange="updateavday('Friday');"> <label for="Friday">Friday</label></div>
	<div class="left p25 Friday"><input type="checkbox" value="1" name="Friday_ado" id="Friday_ado" <?php if($booksettings['Friday_ado'] == "1") { ?>checked<?php } ?> onchange="updateado('Friday_ado');"> <label for="Friday_ado">All day services only</label></div>
	<div class="left p50 Friday_ado Friday <?php if($booksettings['Friday_ado'] == "1") { ?>hide<?php } ?>"><?php  daytimes('Friday','Friday_start_time','Friday_end_time','Friday_time_blocks');?></div>
	<div class="clear"></div>
</div>

<div class="underline">
	<div class="left p25"><input type="checkbox" value="1" name="Saturday" id="Saturday" <?php if($booksettings['Saturday'] == "1") { ?>checked<?php } ?> onchange="updateavday('Saturday');"> <label for="Saturday">Saturday</label></div>
	<div class="left p25 Saturday"><input type="checkbox" value="1" name="Saturday_ado" id="Saturday_ado" <?php if($booksettings['Saturday_ado'] == "1") { ?>checked<?php } ?> onchange="updateado('Saturday_ado');"> <label for="Saturday_ado">All day services only</label></div>
	<div class="left p50 Saturday_ado Saturday <?php if($booksettings['Saturday_ado'] == "1") { ?>hide<?php } ?>"><?php  daytimes('Saturday','Saturday_start_time','Saturday_end_time','Saturday_time_blocks');?></div>
	<div class="clear"></div>
</div>

<div>&nbsp;</div>
<div class="pc"><input type="checkbox" name="do_not_show_on_people_list" id="do_not_show_on_people_list" value="1" <?php if($booksettings['do_not_show_on_people_list'] =="1") { ?>checked<?php } ?>> <label for="do_not_show_on_people_list">Do not show calendar icon on people list</label>
</div>
<div  class="bottomSave">
	<input type="hidden" name="do" value="booking">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="view" value="settings">
	<input type="submit" name="submit" id="submit" class="submit" value="Update Settings">
</div>
</form>
<div>&nbsp;</div>
<div>&nbsp;</div>

<div class="underlinelabel">Unavailable Times <a href="" onclick="editunavailable('','edit'); return false;">Add</a></div>
<div class="underlinespacer">Here you can set times that you are unavailable each day. For example, lunch at 12:00 for 1 hour.</div>
<?php 		$books = whileSQL("ms_bookings", "*, time_format(book_time, '%l:%i %p')  AS book_time_show", "WHERE book_unavailable='1' GROUP BY book_id ORDER BY book_time ASC ");
while($book = mysqli_fetch_array($books)) { ?>
<div class="underline">
	<div class="left p5">
	<a href="" onclick="editunavailable('<?php print $book['book_id'];?>','edit'); return false;"><?php print ai_edit;?></a> 
	<a href="index.php?do=booking&delete_unavailable=<?php print $book['book_id'];?>" class="confirmdelete" title="Delete Unavailable Time" confirm-title="Delete <?php print $book['book_event_name'];?>" confirm-message="Are you sure you want to delete this?" ><?php print ai_delete;?></a>
	</div>
	<div class="left p20">
	<?php 
	$t = explode(":",$book['book_time']);
	$to = date("g:i A", mktime($t[0],$t[1] + $book['book_length'], 0, 1, 1, date('Y'))); 

	print $book['book_time_show']." - ".$to;?>
	
	</a>
	</div>
	<div class="left p75">

	<?php print $book['book_event_name'];?></a>
	</div>
	<div class="clear"></div>
	</div>
<?php } ?>
<div>&nbsp;</div>



<?php function daytimes($d,$dstarttime,$dendtime,$dtimeblocks) { 
global $booksettings;
?>
<select name="<?php print $dstarttime;?>" id="<?php print $dstarttime;?>" class="formfield required inputtitle">
<?php 
while($tm < 24) {
	while($tmm < 60) {
		if(date("H:i:s", mktime($tm,$tmm,1,1,1,1)) == $booksettings[$dstarttime]) { $selected = "selected"; }
	print "<option value=\"".date("H:i:s", mktime($tm,$tmm,1,1,1,1))."\" $selected>".date("h:i a", mktime($tm,$tmm,1,1,1,1))."</option>"; 
	unset($selected);
	$tmm = $tmm + 15;
	}
$tm++;
$tmm = 0;
}

?></select>

To

<select name="<?php print $dendtime;?>" id="<?php print $dendtime;?>" class="formfield required inputtitle">
<?php 
$tm = 0;
$tmm = 0;
while($tm < 24) {
	while($tmm < 60) {
		if(date("H:i:s", mktime($tm,$tmm,1,1,1,1)) == $booksettings[$dendtime]) { $selected = "selected"; }
	print "<option value=\"".date("H:i:s", mktime($tm,$tmm,1,1,1,1))."\" $selected>".date("h:i a", mktime($tm,$tmm,1,1,1,1))."</option>"; 
	unset($selected);
	$tmm = $tmm + 15;
	}
$tm++;
$tmm = 0;
}

?></select>

 Blocks

 <select name="<?php print $dtimeblocks;?>" id="<?php print $dtimeblocks;?>" class="formfield required inputtitle">
 <option value="1" <?php if($booksettings[$dtimeblocks] == "1") { ?>selected<?php } ?>>1 Minute</option>
 <option value="2" <?php if($booksettings[$dtimeblocks] == "2") { ?>selected<?php } ?>>2 Minutes</option>
 <option value="3" <?php if($booksettings[$dtimeblocks] == "3") { ?>selected<?php } ?>>3 Minutes</option>
 <option value="4" <?php if($booksettings[$dtimeblocks] == "4") { ?>selected<?php } ?>>4 Minutes</option>
 <option value="5" <?php if($booksettings[$dtimeblocks] == "5") { ?>selected<?php } ?>>5 Minutes</option>
 <option value="6" <?php if($booksettings[$dtimeblocks] == "6") { ?>selected<?php } ?>>6 Minutes</option>
 <option value="7" <?php if($booksettings[$dtimeblocks] == "7") { ?>selected<?php } ?>>7 Minutes</option>
 <option value="8" <?php if($booksettings[$dtimeblocks] == "8") { ?>selected<?php } ?>>8 Minutes</option>
 <option value="9" <?php if($booksettings[$dtimeblocks] == "9") { ?>selected<?php } ?>>9 Minutes</option>
 <option value="10" <?php if($booksettings[$dtimeblocks] == "10") { ?>selected<?php } ?>>10 Minutes</option>
 <option value="11" <?php if($booksettings[$dtimeblocks] == "11") { ?>selected<?php } ?>>11 Minutes</option>
 <option value="12" <?php if($booksettings[$dtimeblocks] == "12") { ?>selected<?php } ?>>12 Minutes</option>
 <option value="13" <?php if($booksettings[$dtimeblocks] == "13") { ?>selected<?php } ?>>13 Minutes</option>
 <option value="14" <?php if($booksettings[$dtimeblocks] == "14") { ?>selected<?php } ?>>14 Minutes</option>
 <option value="15" <?php if($booksettings[$dtimeblocks] == "15") { ?>selected<?php } ?>>15 Minutes</option>
 <option value="16" <?php if($booksettings[$dtimeblocks] == "16") { ?>selected<?php } ?>>16 Minutes</option>
 <option value="17" <?php if($booksettings[$dtimeblocks] == "17") { ?>selected<?php } ?>>17 Minutes</option>
 <option value="18" <?php if($booksettings[$dtimeblocks] == "18") { ?>selected<?php } ?>>18 Minutes</option>
 <option value="19" <?php if($booksettings[$dtimeblocks] == "19") { ?>selected<?php } ?>>19 Minutes</option>
 <option value="20" <?php if($booksettings[$dtimeblocks] == "20") { ?>selected<?php } ?>>20 Minutes</option>
 <option value="30" <?php if($booksettings[$dtimeblocks] == "30") { ?>selected<?php } ?>>30 Minutes</option>
 <option value="45" <?php if($booksettings[$dtimeblocks] == "45") { ?>selected<?php } ?>>45 Minutes</option>
 <option value="60" <?php if($booksettings[$dtimeblocks] == "60") { ?>selected<?php } ?>>1 Hour</option>
 <option value="75" <?php if($booksettings[$dtimeblocks] == "75") { ?>selected<?php } ?>>1 Hour 15 Minutes</option>
 <option value="90" <?php if($booksettings[$dtimeblocks] == "90") { ?>selected<?php } ?>>1 Hour 30 Minutes</option>
 <option value="105" <?php if($booksettings[$dtimeblocks] == "105") { ?>selected<?php } ?>>1 Hour 45 Minutes</option>
 <option value="120" <?php if($booksettings[$dtimeblocks] == "120") { ?>selected<?php } ?>>2 Hours</option>
 <option value="180" <?php if($booksettings[$dtimeblocks] == "180") { ?>selected<?php } ?>>3 Hours</option>
 <option value="240" <?php if($booksettings[$dtimeblocks] == "240") { ?>selected<?php } ?>>4 Hours</option>

</select>
 <?php } ?>


