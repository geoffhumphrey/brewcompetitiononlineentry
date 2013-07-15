<?php
/**
 * Module:      beerxml.sec.php 
 * Description: This module houses and calls the upload mechanism for parsing Beer XML files
 *              and inserting the data into the brewing database table.
 * 
 */

if ($php_version >= "5") { 
$return = "";
include (INCLUDES.'beerXML/input_beer_xml.inc.php');
//Mmaximum file size.
$MAX_SIZE = 2000000;

//Allowable file Mime Types.
$FILE_MIMES = array('image/jpeg','image/jpg','image/gif','image/png','application/msword');

//Allowable file ext. names.
$FILE_EXTS  = array('.xml','.txt');

//Allow file delete? no, if only allow upload only
$DELETABLE  = false;
/*
$recipeList = "";
function formatInsertedRecipes($recipes) {
    foreach($recipes as $id=>$name){
        $recipelist .= "<tr><td><a href=\"index.php?page=recipeDetail&amp;id=" .$id . "\">".$name."</a></td></tr>";
    }
    return $recipelist;
}
*/
$url_this =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

if(!$_REQUEST['inserted'] == "true") $_SESSION['recipes'] = "";
if ($_FILES['userfile']) {
  	$file_type = $_FILES['userfile']['type'];
  	$file_name = $_FILES['userfile']['name'];
  	$file_ext = strtolower(substr($file_name,strrpos($file_name,".")));
    $message = $_FILES['userfile']['tmp_name'];
  //File Size Check
  if ($_FILES['userfile']['size'] > $MAX_SIZE) 
    $message = "The file size is over 2MB.  Please adjust the size and try again.";
  //File Type/Extension Check
  else if (!in_array($file_type, $FILE_MIMES) && !in_array($file_ext, $FILE_EXTS))
    $message = "Sorry, that file type is not allowed to be uploaded.  Acceptable file type extensions are: .xml, .txt";
  else if(is_uploaded_file($_FILES['userfile']['tmp_name'])){
    $input = new InputBeerXML($_FILES['userfile']['tmp_name']);
    if($_POST["insert_type"] == "recipes"){
        $insertedRecipes = $input->insertRecipes();
        /*
		$recipes = '';
        foreach($insertedRecipes as $id=>$name){
            $recipes .= "<tr><td class=\"dataList\"><a href=\"index.php?section=brew&amp;action=edit&amp;go=beerXML&amp;filter=".count($insertedRecipes)."&amp;id=" .$id . "\">". $name . "</a></td><td class=\"data\"><span class=\"required\"></span></td></tr>";
        }
		*/
		if (count($insertedRecipes) > 1) $message = count($insertedRecipes) . " entries inserted";
        else $message = count($insertedRecipes) . " entry inserted";
    }else if($_POST["insert_type"] == "brewBlog"){
        $insertedRecipes = $input->insertBlogs();
        $recipes = '';
        foreach($insertedRecipes as $id=>$name){
            $recipes .= "<tr><td><a href=\"".$base_url."index.php?page=brewBlogDetail&amp;id=" .$id . "\">" . $name . "</a></td></tr>";
        }
        $message = count($insertedRecipes) . " BrewBlogs Inserted";
    }
    $_SESSION['recipes'] = $recipes;
   }

   print "<script>window.location.href='index.php?section=".$section."&action=importXML&msg=1</script>";
}
else if (!$_FILES['userfile']) $message = "userfile check failed";
else
	$message = "Invalid file specified.";

?>

<?php if (($action != "print") && ($msg != "default")) echo $msg_output; ?>
<?php 
if (entries_unconfirmed($_SESSION['user_id']) > 0) echo "<div class='error'>You have unconfirmed entries. Please go to <a href='".build_public_url("list","default","default",$sef,$base_url)."'>your entry list</a> to confirm all your entry data. Unconfirmed entry data will be deleted every 24 hours.</div>";

//$return = "index.php?section=brew&action=edit&id=".$row_entry_check['id']."&msg=10";
//echo $return;	

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