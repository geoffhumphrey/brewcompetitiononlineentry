<?php
/**
 * Module:      beerxml.sec.php 
 * Description: This module houses and calls the upload mechanism for parsing Beer XML files
 *              and inserting the data into the brewing database table.
 * 
 */

if ($php_version >= "5") { 

function check_exension($file_ext) {

	switch($file_ext) {
		case "xml": return TRUE;
		break;
	
		case "":  // Handle file extension for files ending in '.'
		case NULL: 
		return FALSE;  // Handle no file extension
		break;
		
		default: return FALSE;
		break;
	}
	
}

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
	print "<script>window.location.href='index.php?section=".$section."&action=importXML&msg=1</script>";
}
elseif (!$_FILES['userfile']) $message .= "Please choose your BeerXML file to upload.";
else $message .= "Invalid file specified.";

if (($action != "print") && ($msg != "default")) echo $msg_output;
if (!empty($message)) echo "<div class='error'>".$message."</div>";
if (entries_unconfirmed($_SESSION['user_id']) > 0) { 
	
	echo "<div class='error'>You have unconfirmed entries. Please go to <a href='".build_public_url("list","default","default",$sef,$base_url)."'>your entry list</a> to confirm all your entry data. Unconfirmed entry data will be deleted every 24 hours.</div>";
}

$query_check = sprintf("SELECT COUNT(*) as count FROM %s WHERE brewBrewerID='%s'", $prefix."brewing",$_SESSION['user_id']);
$check = mysql_query($query_check, $brewing) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
			
if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_top.inc.php');
?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/help.png"  /></span><a id="modal_window_link" href="http://help.brewcompetition.com/files/beerxml_import.html" title="BCOE&amp;M Help: Beer XML Import">BeerXML Import Help</a></p>
<?php if (((!empty($row_limits['prefsUserEntryLimit'])) && ($row_check['count'] < $row_limits['prefsUserEntryLimit'])) || (empty($row_limits['prefsUserEntryLimit']))) { ?>
<p>Browse for your BeerXML compliant file on your hard drive that you exported from BeerSmith, BrewBlogger, etc. and click <em>Upload</em>.</p>
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
<?php } else { ?>
<span class="icon"><img src="<?php echo $base_url; ?>images/exclamation.png"  /></span><strong>You have reached the limit of <?php echo readable_number($row_limits['prefsUserEntryLimit']); ?> entries per participant in this competition.</strong>
<?php } ?>
<?php } else { ?>
<div id="header">
	<div id="header-inner"><h1>Import Beer XML File</h1></div>
</div>
<div class="error">Your server's version of PHP does not support the BeerXML import feature.</div>
<p>PHP version 5.x or higher is required &mdash; this server is running PHP version <?php echo $php_version; ?>.</p>
<?php } 
if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_bottom.inc.php');
?>