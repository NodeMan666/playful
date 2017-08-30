<?php 
$search_page = true;

?>
<div id="pageTitle">Search</div>

<?php  $var = mysqli_real_escape_string($dbcon,$_REQUEST['q']); ?>
<?php
	/* STARTING NEW SEARCH FUNCTION */
if(empty($_REQUEST['q'])) { ?>
<div class="pc center">You did not enter a search term</div>
<?php }



if(!empty($_REQUEST['q'])) { 
// Add  IN BOOLEAN MODE 
$in_bool = " IN BOOLEAN MODE";

 $ignore_words = array("by", "and", "of", "the", "for", "why", "in", " ", "&", "to", "how", "i", "have", "been", "a", "if");

	$trimmed = trim($var);
	$trimmed1 = trim($var);
	//separate key-phrases into keywords
	$trimmed_array = explode(" ",$trimmed);
	$trimmed_array1 = explode(" ",$trimmed1);
	 
	// check for an empty string and display a message.
	if ($trimmed == "") {
		$resultmsg =  "<p>Search Error</p><p>Please enter a search...</p>" ;
	}
	 
	// check for a search parameter
	if (!isset($var)){
		$resultmsg =  "<p>Search Error</p><p>We don't seem to have a search parameter! </p>" ;
	}
	$adid_array = array();
	$word_array = array();
	$word_array2 = array();
	$short_words = array();
	// Build SQL Query for each keyword entered
	foreach ($trimmed_array as $trimm){
		if(!in_array(strtolower($trimm), $ignore_words)) { 
			$trimm = str_replace('"', "", $trimm);
			$trimm = str_replace('?', "", $trimm);
			$trimm = trim(stripslashes(stripslashes($trimm)));
			if(!empty($trimm)) { 
				if(strlen($trimm)==3) {
					array_push($short_words, $trimm);
				} else {
					$searching .= " $trimm";
						$word_count ++;

				}
			}
		}
	}

	foreach($short_words AS $tw) { 
		$and_sql .= " AND date_title LIKE '%$tw%' OR date_text LIKE '%$tw%'  OR page_keywords LIKE '%$tw%'";
	}
	// print "<li>searching: $searching | $and_sql";
	$subject_weight = 3;
	$text_weight = 1;
	$keyword_weight = 2;
	$searching = mysqli_real_escape_string($dbcon,$searching);
	$searching = trim($searching);


	$datass = "SELECT * , MATCH (date_title) AGAINST ('".$searching."' $in_bool)  * $subject_weight + MATCH (date_text) AGAINST ('".$searching."'  $in_bool) * $text_weight  + MATCH (page_keywords) AGAINST ('".$searching."'  $in_bool) * $keyword_weight  AS score FROM ms_calendar WHERE date_id>'0'  $search_what AND  MATCH (date_title, date_text,page_keywords) AGAINST ('+".$searching."'  $in_bool)  ORDER BY score  DESC";
	$datas=mysqli_query($dbcon,$datass);
	if (!$datas) {	echo( "MySQL error: " . mysqli_error($dbcon) . "");	exit(); }
	$total_results = mysqli_num_rows ($datas);
	// print $datass." total: ".$total_results;


	 if(($total_results < 1)AND(count($short_words)>0)==true){
		$datass = "SELECT * FROM ms_calendar WHERE date_type='news' $search_what AND date_id>'0'  $and_sql  ORDER BY date_id DESC LIMIT 5";
		$datas=mysqli_query($dbcon,$datass);
		if (!$datas) {	echo( "MySQL error: " . mysqli_error($dbcon) . "");	exit(); }
		$total_results = mysqli_num_rows ($datas);
		$do_short = true;
	 }


?>
<?php 
// print "<li>$word_count";
		$tmtotal = mysqli_num_rows($datas);
		if(mysqli_num_rows($datas)<=0) { ?><div class="pageContent center"><h2>Sorry, no results for your search term</h2> </div><?php } else { ?>
		<div class="pageContent"><h1>Search results</h1></div>
		<?php
		while ($data = mysqli_fetch_array($datas)) {
			$totalLinks = mysqli_num_rows($datas);
			$rownum++;
			$thisLink++;
			if((empty($data['score']))OR((!empty($data['score']))AND($data['score'] > 1))==true) { 
				$tt++;
			?>
				<div id="row-<?php print $rownum;?>" class="pageContent">
<?php 
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$data['date_cat']."' ");
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$data['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
	if(!empty($pic['pic_id'])) {
		// $size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
		print "<a href=\"index.php?do=news&action=addDate&date_id=".$data['date_id']."\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left \" width=\"50\" height=\"50\"  id=\"th-".$data['date_id']."\" border=\"0\" style=\"margin-right: 12px;\"></a>";
	} else { 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$data['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
		if(!empty($pic['pic_id'])) {
			// $size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
			print "<a href=\"index.php?do=news&action=addDate&date_id=".$data['date_id']."\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\" width=\"50\" height=\"50\"  id=\"th-".$data['date_id']."\" border=\"0\" style=\"margin-right: 12px;\"></a>";
		}
	}

?>
				<h3><?php print $thisLink;?>) <?php print "<a href=\"index.php?do=news&action=addDate&date_id=".$data['date_id']."\">".$data['date_title']."</a>"; ?></h3><br>
				<?php 
				$introcontent = strip_tags($data['date_text']);
				if(strlen($introcontent) > 200) { 
					$introcontent = substr($introcontent, 0, 200)."...";
				} 
					print $introcontent;?></div>
	

				<div class="clear"></div>
				<div>&nbsp;</div>
				<?php } ?>
			<?php } ?>
		<?php if($tt <=0) { ?>
		<div class="pageContent"><h2>Sorry, no results for your search term</h2></div>
<?php } ?>
	<div>&nbsp;</div>
<?php } ?>


<?php 
		}

?>
<div>&nbsp;</div>
