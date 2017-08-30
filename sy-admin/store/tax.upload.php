<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}
adminsessionCheck();
$FILENAME=$_FILES['image']['name']; 
$uploaded_array=explode(".",$FILENAME); 
$image_extension = strtolower($uploaded_array[1]); 

$tempFile = $_FILES['image']['tmp_name'];
$size_upfull = GetImageSize($_FILES['image']['tmp_name']); 

$destination = ("".$setup['path']."/".$setup['gallery_folder']."/".$FILENAME);

copy($tempFile, $destination);

print "<div id=\"info\">The following zip codes have been added from the uploaded CSV file.<br><br><a href=\"index.php?do=settings&action=tax&type=zip\" class=hllink>Click here to continue</a></div>";
print "<li>File: ".$_FILES['image']['name'];
print "<table cellpadding=0 cellspacing=0 border=0 class=listbox>";
print "<tr><td class=tdtop>Zip</td><td class=tdtop>Tax rate</td><td class=tdtop>City</td><td class=tdtop>State</td><td class=tdtop>Status</td></tr>";
$row = 1;
$handle = fopen("$destination", "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    echo "<p> $num fields in line $row: <br /></p>\n";
    $row++;
    for ($c=0; $c < $num; $c++) {
		if($row <= 2) {
			if(strtolower(trim($data[$c])) == "zip") {
				$zipCol = $c;
			}
			if(strtolower(trim($data[$c])) == "tax") {
				$taxCol = $c;
			}
 			if(strtolower(trim($data[$c])) == "state") {
				$stateCol = $c;
			}
			if(strtolower(trim($data[$c])) == "city") {
				$cityCol = $c;
			}
		} 
//		echo $data[$c] . "<br />\n";

    }
		if($row > 2) {
			if((!empty($data[$zipCol]))&(!empty($data[$taxCol]))==true) {
				print "<tr><td class=tdlines>".$data[$zipCol]."</td><td class=tdlines>".$data[$taxCol]."&nbsp;</td><td class=tdlines>".$data[$cityCol]."&nbsp;</td><td class=tdlines>".$data[$stateCol]."&nbsp;</td>";
			print "Zip: ".$data[$zipCol]." - Tax: ".$data[$taxCol]." - State: ".$data[$stateCol]." - City: ".$data[$cityCol].""; 
	//			print "<li>$c = <li>zip: $zipCol <li>tax: $taxCol <li>state: $stateCol <li>city: $cityCol ";
				$zip = doSQL("ms_tax_zips", "id,zip", "WHERE zip='".$data[$zipCol]."' ");
				if(empty($zip['id'])) {
					if($data[$taxCol] > 0) { 
						insertSQL("ms_tax_zips", "zip='".$data[$zipCol]."', tax='".$data[$taxCol]."', state='".$data[$stateCol]."', city='".addslashes(stripslashes($data[$cityCol]))."' "); 
						print "<td class=tdlines>Added</td>";
					}
				} else {
					if($data[$taxCol] <=0) { 
						deleteSQL("ms_tax_zips", "WHERE  zip='".$data[$zipCol]."'", "1");
					} else { 
						updateSQL("ms_tax_zips", "tax='".$data[$taxCol]."', state='".$data[$stateCol]."', city='".addslashes(stripslashes($data[$cityCol]))."' WHERE zip='".$data[$zipCol]."'  "); 
						print "<td class=tdlines>Updated</td>";
					}
				}
				print "</tr>";
				$totalAdded++;
		}
		}
}
fclose($handle);

unlink($destination);
$_SESSION['sm'] = "$totalAdded zip codes added or updated";
header("location: index.php?do=settings&action=tax");
session_write_close();
exit();