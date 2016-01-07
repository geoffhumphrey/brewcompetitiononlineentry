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

// Build Links
$help_link = "<p><span class='icon'><img src='".$base_url."images/help.png' /></span><a id='modal_window_link' href='http://help.brewcompetition.com/files/beerxml_import.html' title='BCOE&amp;M Help: BeerXML'>My Account Help</a></p>";

// Build Messages

// Disaable entry add/edit if registration closed and entry window closed
if (($registration_open != 1) && ($entry_window_open != 1) && ($_SESSION['userLevel'] > 1)) {
	$message1 .= "<div class='error'>Importing Entries Not Available</div>";
	if ($registration_open == "0") $message1 .= "<p>Entry registration has not opened yet.</p>";
	if ($registration_open == "2") $message1 .= "<p>Entry registration has closed.</p>";
}

// Open but entry limit reached
// No importing
elseif (($registration_open == 1) && ($_SESSION['userLevel'] > 1) && ($comp_entry_limit)) {
	$message2 .= "<div class='error'>Importing Entries Not Available</div>";
	$message2 .= "<p>The competition entry limit has been reached.</p>";
}

// Open but personal entry limit reached
// No importing
elseif (($registration_open == 1) && ($entry_window_open == 1) && ($_SESSION['userLevel'] > 1) && ($remaining_entries == 0)) {
	$message3 .= "<div class='error'>Adding Entries Not Available</div>";
	$message3 .= "<p>Your personal entry limit has been reached.</p>";
}

// Registration open, but entry window not
// No importing
elseif (($registration_open == 1) && ($entry_window_open != 1) && ($_SESSION['userLevel'] > 1)) {
	$message4 .= "<div class='error'>Importing Entries Not Available</div>";
	$message4 .= "<p>You will be able to add or import entries on or after $entry_open.</p>";
}

// Special for NHC
// No importing
elseif ((NHC) && ($_SESSION['userLevel'] > 1) && ($registration_open != 1) && ($prefix != "final_")) {
	$message5 .= "<div class='error'>Importing Entries Not Available</div>";
	$message5 .= "<p>NHC registration has closed.</p>";
}

// Special for NHC
// Close adding or editing during the entry window as well
elseif ((NHC) && ($_SESSION['userLevel'] > 1) && ($registration_open != 1) && ($entry_window_open != 1) && ($prefix == "final_")) {
	$message6 .= "<div class='error'>Importing Entries Not Available</div>";
	$message6 .= "<p>NHC registration has closed.</p>";
}

else {

	if ($php_OK) {
		$upload_form_display = TRUE;
		$message = "";
		$return = "";
		include (INCLUDES.'beerXML/input_beer_xml.inc.php');
		$MAX_SIZE = 2000000;
		$FILE_MIMES = array('text/xml');
		$FILE_EXTS  = array('.xml');
		$DELETABLE  = false;
		$url_this =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		
		if(!$_REQUEST['inserted'] == "true") $_SESSION['recipes'] = "";
		
		if ($_FILES['userfile']) {
			$file_type = $_FILES['userfile']['type'];
			$file_name = $_FILES['userfile']['name'];
			$file_ext = strtolower(substr($file_name,strrpos($file_name,".")));  	
			
			// File size check
			if ($_FILES['userfile']['size'] > $MAX_SIZE) 
			$message .= "The file size is over 2MB.  Please adjust the size and try again.";
			  
			//File type and extension check
			elseif (!in_array($file_type, $FILE_MIMES) && !in_array($file_ext, $FILE_EXTS))
			$message .= "Sorry, that file type is not allowed to be uploaded.  Only .xml file extensions are allowed.";
			  
			// Check if file uploaded, if so, parse.
			elseif(is_uploaded_file($_FILES['userfile']['tmp_name'])) {
				
				$input = new InputBeerXML($_FILES['userfile']['tmp_name']);
				
				if($_POST["insert_type"] == "recipes"){
					$insertedRecipes = $input->insertRecipes();
					if (count($insertedRecipes) > 1) $message .= ucwords(readable_number(count($insertedRecipes))) . " entries added.";
					else $message .= ucwords(readable_number(count($insertedRecipes))) . " entry added.";
				} 
				
				$_SESSION['recipes'] = $recipes;
			}
			print "<script>window.location.href='index.php?section=".$section."&action=importXML&msg=1'</script>";
		}
		elseif (!$_FILES['userfile']) $message .= "";
		else $message .= "Invalid file specified.";
		if (!empty($message)) $message7 .= "<div class='error'>".$message."</div>";
		if (entries_unconfirmed($_SESSION['user_id']) > 0) { 
			$message8 .=  "<div class='error'>You have unconfirmed entries. Please go to <a href='".build_public_url("list","default","default","default",$sef,$base_url)."'>your entry list</a> to confirm all your entry data. Unconfirmed entry data will be deleted every 24 hours.</div>";
		} 
	}
}
if (!$php_OK) $message9 .= "<div class='error'>Your server's version of PHP does not support the BeerXML import feature.</div><p>PHP version 5.x or higher is required &mdash; this server is running PHP version ".$php_version.".</p>";


// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

echo $message1;
echo $message2;
echo $message3;
echo $message4;
echo $message5;
echo $message6;
echo $message7;
echo $message8;
echo $message9;
echo $help_link;

if ($upload_form_display) { ?>
<p>Browse for your BeerXML compliant file on your hard drive click <em>Upload</em>.</p>
<form name="upload" id="upload" ENCTYPE="multipart/form-data" method="post">
<table>
<tr>
    <td class="dataLabel">BeerXML File:</td>
    <td class="data"><input name="userfile" type="file" class="texta" id="userfile" size="60"></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td class="data"><input name="upload" type="submit" class="button" value="Upload" /></td>
</table>
<input type="hidden" name="insert_type" value="recipes" />
<input type="hidden" name="brewBrewerID" value="<?php echo $_SESSION['user_id']; ?>" />
<input type="hidden" name="brewBrewerFirstName" value="<?php echo $_SESSION['brewerFirstName']; ?>" />
<input type="hidden" name="brewBrewerLastName" value="<?php echo $_SESSION['brewerLastName']; ?>" />
</form>
<?php } ?>


<!-- Public Page Rebuild completed 08.27.15 --> 


