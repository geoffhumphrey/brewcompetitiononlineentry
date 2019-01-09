<?php
/**
 * Module:      beerxml.sec.php 
 * Description: This module houses and calls the upload mechanism for parsing Beer XML files
 *              and inserting the data into the brewing database table.
 * 
 
echo $php_version;
echo $registration_open."<br>";
echo $entry_window_open."<br>";
echo $remaining_entries."<br>";
echo $_SESSION['userLevel'];

$php_version = "4.0.1";
 */
 

/* ---------------- USER Pages Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Public pages have certain variables in common that build the page:
  
	$primary_page_info = any information related to the page
	$primary_links = top of page links
	$secondary_links = sublinks
	
	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page
	
	$page_infoX = the bulk of the information on the page.
	
	$labelX = the various labels in a table or on a form
	$table_headX = all table headers (column names)
	$table_bodyX = table body info
	$messageX = various messages to display
	
	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	
Declare all variables empty at the top of the script. Add on later...
	$primary_page_info = "";
	$header1_1 = "";
	$page_info1 = "";
	$header1_2 = "";
	$page_info2 = "";
	
	$table_head1 = "";
	$table_body1 = "";
	
	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */
 
if ($php_version >= 5) $php_OK = TRUE; else $php_OK = FALSE;
$upload_form_display = FALSE;

$message1 = "";
$message2 = "";
$message3 = "";
$message4 = "";
$message5 = "";
$message6 = "";
$message7 = "";
$message8 = "";
$message9 = "";

// Build Messages

// Disaable entry add/edit if registration closed and entry window closed
if (($registration_open != 1) && ($entry_window_open != 1) && ($_SESSION['userLevel'] > 1)) {
	$message1 .= sprintf("<p class=\"lead\">%s</p>",$beerxml_text_000);
	if ($registration_open == "0") $message1 .= sprintf("<p>%s</p>",$alert_text_027);
	if ($registration_open == "2") $message1 .= sprintf("<p>%s</p>",$alert_text_028);
}

// Open but entry limit reached
// No importing
elseif (($registration_open == 1) && ($_SESSION['userLevel'] > 1) && ($comp_entry_limit) && ($comp_paid_entry_limit)) {
	$message2 .= sprintf("<p class=\"lead\">%s</p>",$beerxml_text_000);
	$message2 .= sprintf("<p>%s</p>",$alert_text_030);
}

// Open but personal entry limit reached
// No importing
elseif (($registration_open == 1) && ($entry_window_open == 1) && ($_SESSION['userLevel'] > 1) && ($remaining_entries <= 0)) {
	$message3 .= sprintf("<p class=\"lead\">%s</p>",$beerxml_text_000);
	$message3 .= sprintf("<p>%s</p>",$alert_text_031);
}

// Registration open, but entry window not
// No importing
elseif (($registration_open == 1) && ($entry_window_open != 1) && ($_SESSION['userLevel'] > 1)) {
	$message4 .= sprintf("<p class=\"lead\">%s</p>",$beerxml_text_000);
	$message4 .= sprintf("<p>%s</p>",$alert_text_032);
}

// Special for NHC
// No importing
elseif ((NHC) && ($_SESSION['userLevel'] > 1) && ($registration_open != 1) && ($prefix != "final_")) {
	$message5 .= sprintf("<p class=\"lead\">%s</p>",$beerxml_text_000);
	$message5 .= sprintf("<p>%s</p>",$alert_text_028);
}

// Special for NHC
// Close adding or editing during the entry window as well
elseif ((NHC) && ($_SESSION['userLevel'] > 1) && ($registration_open != 1) && ($entry_window_open != 1) && ($prefix == "final_")) {
	$message6 .= sprintf("<p class=\"lead\">%s</p>",$beerxml_text_000);
	$message6 .= sprintf("<p>%s</p>",$alert_text_028);
}

else {

	if ($php_OK) {
		$upload_form_display = TRUE;
		
		if ($go == "upload") {
			$message = "";
			$return = "";
			include (INCLUDES.'beerXML/input_beer_xml.inc.php');
			$MAX_SIZE = 2000000;
			$FILE_MIMES = array('text/xml');
			$FILE_EXTS  = array('.xml');
			$DELETABLE  = false;
			$url_this =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
			
			// if(!$_REQUEST['inserted'] == "true") $_SESSION['recipes'] = "";
			
			if ($_FILES['userfile']) {
				
				
				$file_type = $_FILES['userfile']['type'];
				$file_name = $_FILES['userfile']['name'];
				$file_ext = strtolower(substr($file_name,strrpos($file_name,".")));  	
				
				// File size check
				if ($_FILES['userfile']['size'] > $MAX_SIZE) 
				$message .= $beerxml_text_003;
				  
				//File type and extension check
				elseif (!in_array($file_type, $FILE_MIMES) && !in_array($file_ext, $FILE_EXTS))
				$message .= $beerxml_text_002;
				  
				// Check if file uploaded, if so, parse.
				elseif(is_uploaded_file($_FILES['userfile']['tmp_name'])) {
					
					$input = new InputBeerXML($_FILES['userfile']['tmp_name']);
					
					if($_POST["insert_type"] == "recipes"){
						$insertedRecipes = $input->insertRecipes();
						// if (count($insertedRecipes) > 1) $message .= ucwords(readable_number(count($insertedRecipes))) . " ".$beerxml_text_011.".";
						// else $message .= ucwords(readable_number(count($insertedRecipes))) . " ".$beerxml_text_012.".";
					} 
					
					// $_SESSION['recipes'] = $recipes;
					$message .= sprintf(" ".$_FILES['userfile']['name']." %s", $beerxml_text_001);
				}
				
				
				/*
				//header(sprintf("Location: %s", stripslashes($updateGoTo)));
				//print "<script>window.location.href='index.php?section=".$section."&action=importXML&msg=1'</script>";
				*/
			}
			elseif (!$_FILES['userfile']) $message .= "";
			else $message .= $beerxml_text_004;
			if (!empty($message)) $message7 .= sprintf("<p><strong class=\"text-danger\">".$message."</strong> %s</p>",$beerxml_text_005);
		}
	}
}
if (!$php_OK) $message9 .= sprintf("<div class=\"alert alert-danger hidden-print fade in\"><strong>%s</strong> %s</div>",$beerxml_text_006,$beerxml_text_007);


// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

echo $message1;
echo $message2;
echo $message3;
echo $message4;
echo $message5;
echo $message6;
echo $message9;
if ($upload_form_display) { ?>
<p class="lead"><?php echo $beerxml_text_008; ?></p>
<?php echo $message7; ?>
<form name="upload" id="upload" ENCTYPE="multipart/form-data" method="post" action="<?php echo $base_url; ?>index.php?section=beerxml&amp;go=upload">
<div class="fileinput fileinput-new" data-provides="fileinput">
    <span class="btn btn-default btn-file"><span><?php echo $beerxml_text_009; ?></span><input type="file" name="userfile" /></span>
    <span class="fileinput-filename text-success"></span> <span class="fileinput-new text-danger"><?php echo $beerxml_text_010; ?></span>
</div>
<p><input class="btn btn-primary" name="upload" type="submit" class="button" value="<?php echo $label_upload; ?>" /></p>
<input type="hidden" name="insert_type" value="recipes" />
<input type="hidden" name="brewBrewerID" value="<?php echo $_SESSION['user_id']; ?>" />
<input type="hidden" name="brewBrewerFirstName" value="<?php echo $_SESSION['brewerFirstName']; ?>" />
<input type="hidden" name="brewBrewerLastName" value="<?php echo $_SESSION['brewerLastName']; ?>" />
</form>
<?php } ?>