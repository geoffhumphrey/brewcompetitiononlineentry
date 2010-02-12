<?php 
require ('../Connections/config.php'); 
require ('db_connect.inc.php');
require ('url_variables.inc.php');

session_start(); 
//require ('authentication.inc.php'); session_start(); sessionAuthenticate();

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
  require ('scrubber.inc.php');
  switch ($theType) {
  
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
	case "scrubbed":
	  $theValue = ($theValue != "") ? "'" . strtr($theValue, $html_string) . "'" : "NULL";
  }
  return $theValue;
}

if ($action == "delete") {

  if ($go == "participants") {
  mysql_select_db($database, $brewing);
  $query_delete_brewer = sprintf("SELECT * FROM users WHERE user_name='%s'", $username);
  $delete_brewer = mysql_query($query_delete_brewer, $brewing) or die(mysql_error()); 
  $row_delete_brewer = mysql_fetch_assoc($delete_brewer);
  
  $deleteUser = sprintf("DELETE FROM users WHERE id='%s'", $row_delete_brewer['id']);
  mysql_select_db($database, $brewing);
  $Result = mysql_query($deleteUser, $brewing) or die(mysql_error());
  }
  
  /*
  if ($go == "user") {
 	mysql_select_db($database, $brewing);
	$query_delete_user = "SELECT id FROM brewer WHERE brewerEmail='$username'";
	$delete_user = mysql_query($query_delete_user, $brewing) or die(mysql_error());
	$row_delete_usere = mysql_fetch_assoc($delete_user);
  
  $deleteSQL = sprintf("DELETE FROM brewer WHERE id='%s'", $row_delete_user['id']);
  mysql_select_db($database, $brewing);
  $Result1 = mysql_query($deleteSQL, $brewing) or die(mysql_error());
  }
  */
  
  $deleteSQL = sprintf("DELETE FROM $dbTable WHERE id='%s'", $id);
  mysql_select_db($database, $brewing);
  $Result1 = mysql_query($deleteSQL, $brewing) or die(mysql_error());
  
  if ($dbTable == "archive") { 
  $dropTable = "DROP TABLE users_$filter";
  mysql_select_db($database, $brewing);
  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
  
  $dropTable2 = "DROP TABLE brewing_$filter";
  mysql_select_db($database, $brewing);
  $Result2 = mysql_query($dropTable2, $brewing) or die(mysql_error());
  
  $dropTable3 = "DROP TABLE brewer_$filter";
  mysql_select_db($database, $brewing);
  $Result3 = mysql_query($dropTable3, $brewing) or die(mysql_error());
  
  $deleteGoTo = "../index.php?section=admin&go=archive&filter=".$filter."&msg=8";
  header(sprintf("Location: %s", $deleteGoTo));
  }
  
  if ($dbTable != "archive") { 
  $deleteGoTo = "../index.php?section=".$section."&go=".$go."&msg=5";
  header(sprintf("Location: %s", $deleteGoTo));
  }
  
}

// --------------------------- If Adding an Entry ------------------------------- //

if (($action == "add") && ($dbTable == "brewing")) { 

$query_user = sprintf("SELECT * FROM users WHERE user_name = '%s'", $_SESSION["loginUsername"]);
$user = mysql_query($query_user, $brewing) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

if ($row_user['userLevel'] == 1) { 
$nameBreak = $_POST['brewBrewerID'];
$name = explode('-', $nameBreak);
$brewBrewerID = $name[0];
$brewBrewerLastName = $name[1];
$brewBrewerFirstName = $name[2];
}
else {
$brewBrewerID = $_POST['brewBrewerID'];
$brewBrewerLastName = $_POST['brewBrewerLastName']; 
$brewBrewerFirstName = $_POST['brewBrewerFirstName'];
}

$styleBreak = $_POST['brewStyle'];
$style = explode('-', $styleBreak);
$styleTrim = ltrim($style[0], "0"); 
if ($style [0] < 10) $styleFix = "0".$style[0]; else $styleFix = $style[0];

// Get style name from broken parts
mysql_select_db($database, $brewing);
$query_style_name = "SELECT * FROM styles WHERE brewStyleGroup='$styleFix' AND brewStyleNum='$style[1]'";
$style_name = mysql_query($query_style_name, $brewing) or die(mysql_error());
$row_style_name = mysql_fetch_assoc($style_name);

$insertSQL = sprintf("INSERT INTO brewing (brewName, brewStyle, brewCategory, brewCategorySort, brewSubCategory, brewBottleDate, brewDate, brewYield, brewInfo, brewMead1, brewMead2, brewMead3, brewExtract1, brewExtract1Weight, brewExtract2, brewExtract2Weight, brewExtract3, brewExtract3Weight, brewExtract4, brewExtract4Weight, brewExtract5, brewExtract5Weight, brewGrain1, brewGrain1Weight, brewGrain2, brewGrain2Weight, brewGrain3, brewGrain3Weight, brewGrain4, brewGrain4Weight, brewGrain5, brewGrain5Weight, brewGrain6, brewGrain6Weight, brewGrain7, brewGrain7Weight, brewGrain8, brewGrain8Weight, brewGrain9, brewGrain9Weight, brewAddition1, brewAddition1Amt, brewAddition2, brewAddition2Amt, brewAddition3, brewAddition3Amt, brewAddition4, brewAddition4Amt, brewAddition5, brewAddition5Amt, brewAddition6, brewAddition6Amt, brewAddition7, brewAddition7Amt, brewAddition8, brewAddition8Amt, brewAddition9, brewAddition9Amt, brewHops1, brewHops1Weight, brewHops1IBU, brewHops1Time, brewHops2, brewHops2Weight, brewHops2IBU, brewHops2Time, brewHops3, brewHops3Weight, brewHops3IBU, brewHops3Time, brewHops4, brewHops4Weight, brewHops4IBU, brewHops4Time, brewHops5, brewHops5Weight, brewHops5IBU, brewHops5Time, brewHops6, brewHops6Weight, brewHops6IBU, brewHops6Time, brewHops7, brewHops7Weight, brewHops7IBU, brewHops7Time, brewHops8, brewHops8Weight, brewHops8IBU, brewHops8Time, brewHops9, brewHops9Weight, brewHops9IBU, brewHops9Time, brewHops1Use, brewHops2Use, brewHops3Use, brewHops4Use, brewHops5Use, brewHops6Use, brewHops7Use, brewHops8Use, brewHops9Use, brewHops1Type, brewHops2Type, brewHops3Type, brewHops4Type, brewHops5Type, brewHops6Type, brewHops7Type, brewHops8Type, brewHops9Type, brewHops1Form, brewHops2Form, brewHops3Form, brewHops4Form, brewHops5Form, brewHops6Form, brewHops7Form, brewHops8Form, brewHops9Form, brewYeast, brewYeastMan, brewYeastForm, brewYeastType, brewYeastAmount, brewYeastStarter, brewYeastNutrients, brewOG, brewFG, brewPrimary, brewPrimaryTemp, brewSecondary, brewSecondaryTemp, brewOther, brewOtherTemp, brewComments, brewMashStep1Name, brewMashStep1Temp, brewMashStep1Time, brewMashStep2Name, brewMashStep2Temp, brewMashStep2Time, brewMashStep3Name, brewMashStep3Temp, brewMashStep3Time, brewMashStep4Name, brewMashStep4Temp, brewMashStep4Time, brewMashStep5Name, brewMashStep5Temp, brewMashStep5Time, brewFinings, brewWaterNotes, brewBrewerID, brewCarbonationMethod, brewCarbonationVol, brewCarbonationNotes, brewBoilHours, brewBoilMins, brewBrewerFirstName, brewBrewerLastName, brewExtract1Use, brewExtract2Use, brewExtract3Use, brewExtract4Use, brewExtract5Use, brewGrain1Use, brewGrain2Use, brewGrain3Use, brewGrain4Use, brewGrain5Use, brewGrain6Use, brewGrain7Use, brewGrain8Use, brewGrain9Use, brewAddition1Use, brewAddition2Use, brewAddition3Use, brewAddition4Use, brewAddition5Use, brewAddition6Use, brewAddition7Use, brewAddition8Use, brewAddition9Use) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['brewName'], "scrubbed"),
                       GetSQLValueString($row_style_name['brewStyle'], "text"),
					   GetSQLValueString($styleTrim, "text"),
					   GetSQLValueString($styleFix, "text"),
					   GetSQLValueString($style[1], "text"),
                       GetSQLValueString($_POST['brewBottleDate'], "date"),
                       GetSQLValueString($_POST['brewDate'], "date"),
                       GetSQLValueString($_POST['brewYield'], "text"),
                       GetSQLValueString($_POST['brewInfo'], "scrubbed"),
                       GetSQLValueString($_POST['brewMead1'], "text"),
                       GetSQLValueString($_POST['brewMead2'], "text"),
                       GetSQLValueString($_POST['brewMead3'], "text"),
                       GetSQLValueString($_POST['brewExtract1'], "scrubbed"),
                       GetSQLValueString($_POST['brewExtract1Weight'], "text"),
                       GetSQLValueString($_POST['brewExtract2'], "scrubbed"),
                       GetSQLValueString($_POST['brewExtract2Weight'], "text"),
                       GetSQLValueString($_POST['brewExtract3'], "scrubbed"),
                       GetSQLValueString($_POST['brewExtract3Weight'], "text"),
                       GetSQLValueString($_POST['brewExtract4'], "scrubbed"),
                       GetSQLValueString($_POST['brewExtract4Weight'], "text"),
                       GetSQLValueString($_POST['brewExtract5'], "scrubbed"),
                       GetSQLValueString($_POST['brewExtract5Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain1'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain1Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain2'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain2Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain3'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain3Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain4'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain4Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain5'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain5Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain6'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain6Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain7'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain7Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain8'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain8Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain9'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain9Weight'], "text"),
                       GetSQLValueString($_POST['brewAddition1'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition1Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition2'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition2Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition3'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition3Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition4'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition4Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition5'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition5Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition6'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition6Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition7'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition7Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition8'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition8Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition9'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition9Amt'], "text"),
                       GetSQLValueString($_POST['brewHops1'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops1Weight'], "text"),
                       GetSQLValueString($_POST['brewHops1IBU'], "text"),
                       GetSQLValueString($_POST['brewHops1Time'], "text"),
                       GetSQLValueString($_POST['brewHops2'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops2Weight'], "text"),
                       GetSQLValueString($_POST['brewHops2IBU'], "text"),
                       GetSQLValueString($_POST['brewHops2Time'], "text"),
                       GetSQLValueString($_POST['brewHops3'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops3Weight'], "text"),
                       GetSQLValueString($_POST['brewHops3IBU'], "text"),
                       GetSQLValueString($_POST['brewHops3Time'], "text"),
                       GetSQLValueString($_POST['brewHops4'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops4Weight'], "text"),
                       GetSQLValueString($_POST['brewHops4IBU'], "text"),
                       GetSQLValueString($_POST['brewHops4Time'], "text"),
                       GetSQLValueString($_POST['brewHops5'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops5Weight'], "text"),
                       GetSQLValueString($_POST['brewHops5IBU'], "text"),
                       GetSQLValueString($_POST['brewHops5Time'], "text"),
                       GetSQLValueString($_POST['brewHops6'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops6Weight'], "text"),
                       GetSQLValueString($_POST['brewHops6IBU'], "text"),
                       GetSQLValueString($_POST['brewHops6Time'], "text"),
                       GetSQLValueString($_POST['brewHops7'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops7Weight'], "text"),
                       GetSQLValueString($_POST['brewHops7IBU'], "text"),
                       GetSQLValueString($_POST['brewHops7Time'], "text"),
                       GetSQLValueString($_POST['brewHops8'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops8Weight'], "text"),
                       GetSQLValueString($_POST['brewHops8IBU'], "text"),
                       GetSQLValueString($_POST['brewHops8Time'], "text"),
                       GetSQLValueString($_POST['brewHops9'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops9Weight'], "text"),
                       GetSQLValueString($_POST['brewHops9IBU'], "text"),
                       GetSQLValueString($_POST['brewHops9Time'], "text"),
                       GetSQLValueString($_POST['brewHops1Use'], "text"),
                       GetSQLValueString($_POST['brewHops2Use'], "text"),
                       GetSQLValueString($_POST['brewHops3Use'], "text"),
                       GetSQLValueString($_POST['brewHops4Use'], "text"),
                       GetSQLValueString($_POST['brewHops5Use'], "text"),
                       GetSQLValueString($_POST['brewHops6Use'], "text"),
                       GetSQLValueString($_POST['brewHops7Use'], "text"),
                       GetSQLValueString($_POST['brewHops8Use'], "text"),
                       GetSQLValueString($_POST['brewHops9Use'], "text"),
                       GetSQLValueString($_POST['brewHops1Type'], "text"),
                       GetSQLValueString($_POST['brewHops2Type'], "text"),
                       GetSQLValueString($_POST['brewHops3Type'], "text"),
                       GetSQLValueString($_POST['brewHops4Type'], "text"),
                       GetSQLValueString($_POST['brewHops5Type'], "text"),
                       GetSQLValueString($_POST['brewHops6Type'], "text"),
                       GetSQLValueString($_POST['brewHops7Type'], "text"),
                       GetSQLValueString($_POST['brewHops8Type'], "text"),
                       GetSQLValueString($_POST['brewHops9Type'], "text"),
                       GetSQLValueString($_POST['brewHops1Form'], "text"),
                       GetSQLValueString($_POST['brewHops2Form'], "text"),
                       GetSQLValueString($_POST['brewHops3Form'], "text"),
                       GetSQLValueString($_POST['brewHops4Form'], "text"),
                       GetSQLValueString($_POST['brewHops5Form'], "text"),
                       GetSQLValueString($_POST['brewHops6Form'], "text"),
                       GetSQLValueString($_POST['brewHops7Form'], "text"),
                       GetSQLValueString($_POST['brewHops8Form'], "text"),
                       GetSQLValueString($_POST['brewHops9Form'], "text"),
                       GetSQLValueString($_POST['brewYeast'], "scrubbed"),
                       GetSQLValueString($_POST['brewYeastMan'], "scrubbed"),
                       GetSQLValueString($_POST['brewYeastForm'], "text"),
                       GetSQLValueString($_POST['brewYeastType'], "text"),
                       GetSQLValueString($_POST['brewYeastAmount'], "text"),
                       GetSQLValueString($_POST['brewYeastStarter'], "text"),
                       GetSQLValueString($_POST['brewYeastNutrients'], "scrubbed"),
                       GetSQLValueString($_POST['brewOG'], "text"),
                       GetSQLValueString($_POST['brewFG'], "text"),
                       GetSQLValueString($_POST['brewPrimary'], "text"),
                       GetSQLValueString($_POST['brewPrimaryTemp'], "text"),
                       GetSQLValueString($_POST['brewSecondary'], "text"),
                       GetSQLValueString($_POST['brewSecondaryTemp'], "text"),
                       GetSQLValueString($_POST['brewOther'], "text"),
                       GetSQLValueString($_POST['brewOtherTemp'], "text"),
                       GetSQLValueString($_POST['brewComments'], "scrubbed"),
                       GetSQLValueString($_POST['brewMashStep1Name'], "scrubbed"),
                       GetSQLValueString($_POST['brewMashStep1Temp'], "text"),
                       GetSQLValueString($_POST['brewMashStep1Time'], "text"),
                       GetSQLValueString($_POST['brewMashStep2Name'], "scrubbed"),
                       GetSQLValueString($_POST['brewMashStep2Temp'], "text"),
                       GetSQLValueString($_POST['brewMashStep2Time'], "text"),
                       GetSQLValueString($_POST['brewMashStep3Name'], "scrubbed"),
                       GetSQLValueString($_POST['brewMashStep3Temp'], "text"),
                       GetSQLValueString($_POST['brewMashStep3Time'], "text"),
                       GetSQLValueString($_POST['brewMashStep4Name'], "scrubbed"),
                       GetSQLValueString($_POST['brewMashStep4Temp'], "text"),
                       GetSQLValueString($_POST['brewMashStep4Time'], "text"),
                       GetSQLValueString($_POST['brewMashStep5Name'], "scrubbed"),
                       GetSQLValueString($_POST['brewMashStep5Temp'], "text"),
                       GetSQLValueString($_POST['brewMashStep5Time'], "text"),
                       GetSQLValueString($_POST['brewFinings'], "scrubbed"),
                       GetSQLValueString($_POST['brewWaterNotes'], "scrubbed"),
                       GetSQLValueString($brewBrewerID, "text"),
					   GetSQLValueString($_POST['brewCarbonationMethod'], "text"),
					   GetSQLValueString($_POST['brewCarbonationVol'], "text"),
					   GetSQLValueString($_POST['brewCarbonationNotes'], "scrubbed"),
					   GetSQLValueString($_POST['brewBoilHours'], "text"),
					   GetSQLValueString($_POST['brewBoilMins'], "text"),
					   GetSQLValueString($brewBrewerFirstName, "text"),
					   GetSQLValueString($brewBrewerLastName, "text"),
					   GetSQLValueString($_POST['brewExtract1Use'], "text"), 
					   GetSQLValueString($_POST['brewExtract2Use'], "text"), 
					   GetSQLValueString($_POST['brewExtract3Use'], "text"), 
					   GetSQLValueString($_POST['brewExtract4Use'], "text"), 
					   GetSQLValueString($_POST['brewExtract5Use'], "text"),
					   GetSQLValueString($_POST['brewGrain1Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain2Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain3Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain4Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain5Use'], "text"),
					   GetSQLValueString($_POST['brewGrain6Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain7Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain8Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain9Use'], "text"),
					   GetSQLValueString($_POST['brewAddition1Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition2Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition3Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition4Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition5Use'], "text"),
					   GetSQLValueString($_POST['brewAddition6Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition7Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition8Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition9Use'], "text")
					   );

  	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());

  if ($section == "admin") $insertGoTo = "../index.php?section=admin&go=entries";
  else $insertGoTo = "../index.php?section=".$section."&go=".$go."&filter=".$filter."&action=".$action."&msg=1";
  header(sprintf("Location: %s", $insertGoTo));
}

// --------------------------- If Editing an Entry ------------------------------- //

if (($action == "edit") && ($dbTable == "brewing")) {

$query_user = sprintf("SELECT * FROM users WHERE user_name = '%s'", $_SESSION["loginUsername"]);
$user = mysql_query($query_user, $brewing) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

if ($row_user['userLevel'] == 1) { 
$nameBreak = $_POST['brewBrewerID'];
$name = explode('-', $nameBreak);
$brewBrewerID = $name[0];
$brewBrewerLastName = $name[1];
$brewBrewerFirstName = $name[2];
}
else {
$brewBrewerID = $_POST['brewBrewerID'];
$brewBrewerLastName = $_POST['brewBrewerLastName']; 
$brewBrewerFirstName = $_POST['brewBrewerFirstName'];
}

$styleBreak = $_POST['brewStyle'];
$style = explode('-', $styleBreak);
$styleTrim = ltrim($style[0], "0"); 
if ($style [0] < 10) $styleFix = "0".$style[0]; else $styleFix = $style[0];

// Get style name from broken parts
mysql_select_db($database, $brewing);
$query_style_name = "SELECT * FROM styles WHERE brewStyleGroup='$styleFix' AND brewStyleNum='$style[1]'";
$style_name = mysql_query($query_style_name, $brewing) or die(mysql_error());
$row_style_name = mysql_fetch_assoc($style_name);

$updateSQL = sprintf("UPDATE brewing SET brewName=%s, brewStyle=%s, brewCategory=%s, brewCategorySort=%s, brewSubCategory=%s, brewBottleDate=%s, brewDate=%s, brewYield=%s, brewInfo=%s, brewMead1=%s, brewMead2=%s, brewMead3=%s, brewExtract1=%s, brewExtract1Weight=%s, brewExtract2=%s, brewExtract2Weight=%s, brewExtract3=%s, brewExtract3Weight=%s, brewExtract4=%s, brewExtract4Weight=%s, brewExtract5=%s, brewExtract5Weight=%s, brewGrain1=%s, brewGrain1Weight=%s, brewGrain2=%s, brewGrain2Weight=%s, brewGrain3=%s, brewGrain3Weight=%s, brewGrain4=%s, brewGrain4Weight=%s, brewGrain5=%s, brewGrain5Weight=%s, brewGrain6=%s, brewGrain6Weight=%s, brewGrain7=%s, brewGrain7Weight=%s, brewGrain8=%s, brewGrain8Weight=%s, brewGrain9=%s, brewGrain9Weight=%s, brewAddition1=%s, brewAddition1Amt=%s, brewAddition2=%s, brewAddition2Amt=%s, brewAddition3=%s, brewAddition3Amt=%s, brewAddition4=%s, brewAddition4Amt=%s, brewAddition5=%s, brewAddition5Amt=%s, brewAddition6=%s, brewAddition6Amt=%s, brewAddition7=%s, brewAddition7Amt=%s, brewAddition8=%s, brewAddition8Amt=%s, brewAddition9=%s, brewAddition9Amt=%s, brewHops1=%s, brewHops1Weight=%s, brewHops1IBU=%s, brewHops1Time=%s, brewHops2=%s, brewHops2Weight=%s, brewHops2IBU=%s, brewHops2Time=%s, brewHops3=%s, brewHops3Weight=%s, brewHops3IBU=%s, brewHops3Time=%s, brewHops4=%s, brewHops4Weight=%s, brewHops4IBU=%s, brewHops4Time=%s, brewHops5=%s, brewHops5Weight=%s, brewHops5IBU=%s, brewHops5Time=%s, brewHops6=%s, brewHops6Weight=%s, brewHops6IBU=%s, brewHops6Time=%s, brewHops7=%s, brewHops7Weight=%s, brewHops7IBU=%s, brewHops7Time=%s, brewHops8=%s, brewHops8Weight=%s, brewHops8IBU=%s, brewHops8Time=%s, brewHops9=%s, brewHops9Weight=%s, brewHops9IBU=%s, brewHops9Time=%s, brewHops1Use=%s, brewHops2Use=%s, brewHops3Use=%s, brewHops4Use=%s, brewHops5Use=%s, brewHops6Use=%s, brewHops7Use=%s, brewHops8Use=%s, brewHops9Use=%s, brewHops1Type=%s, brewHops2Type=%s, brewHops3Type=%s, brewHops4Type=%s, brewHops5Type=%s, brewHops6Type=%s, brewHops7Type=%s, brewHops8Type=%s, brewHops9Type=%s, brewHops1Form=%s, brewHops2Form=%s, brewHops3Form=%s, brewHops4Form=%s, brewHops5Form=%s, brewHops6Form=%s, brewHops7Form=%s, brewHops8Form=%s, brewHops9Form=%s, brewYeast=%s, brewYeastMan=%s, brewYeastForm=%s, brewYeastType=%s, brewYeastAmount=%s, brewYeastStarter=%s, brewYeastNutrients=%s, brewOG=%s, brewFG=%s, brewPrimary=%s, brewPrimaryTemp=%s, brewSecondary=%s, brewSecondaryTemp=%s, brewOther=%s, brewOtherTemp=%s, brewComments=%s, brewMashStep1Name=%s, brewMashStep1Temp=%s, brewMashStep1Time=%s, brewMashStep2Name=%s, brewMashStep2Temp=%s, brewMashStep2Time=%s, brewMashStep3Name=%s, brewMashStep3Temp=%s, brewMashStep3Time=%s, brewMashStep4Name=%s, brewMashStep4Temp=%s, brewMashStep4Time=%s, brewMashStep5Name=%s, brewMashStep5Temp=%s, brewMashStep5Time=%s, brewFinings=%s, brewWaterNotes=%s, brewBrewerID=%s, brewCarbonationMethod=%s, brewCarbonationVol=%s, brewCarbonationNotes=%s, brewBoilHours=%s, brewBoilMins=%s, brewBrewerFirstName=%s, brewBrewerLastName=%s, brewExtract1Use=%s, brewExtract2Use=%s, brewExtract3Use=%s, brewExtract4Use=%s, brewExtract5Use=%s, brewGrain1Use=%s, brewGrain2Use=%s, brewGrain3Use=%s, brewGrain4Use=%s, brewGrain5Use=%s, brewGrain6Use=%s, brewGrain7Use=%s, brewGrain8Use=%s, brewGrain9Use=%s, brewAddition1Use=%s, brewAddition2Use=%s, brewAddition3Use=%s, brewAddition4Use=%s, brewAddition5Use=%s, brewAddition6Use=%s, brewAddition7Use=%s, brewAddition8Use=%s, brewAddition9Use=%s WHERE id=%s",
                       GetSQLValueString($_POST['brewName'], "scrubbed"),
                       GetSQLValueString($row_style_name['brewStyle'], "text"),
					   GetSQLValueString($styleTrim, "text"),
					   GetSQLValueString($styleFix, "text"),
					   GetSQLValueString($style[1], "text"),
                       GetSQLValueString($_POST['brewBottleDate'], "date"),
                       GetSQLValueString($_POST['brewDate'], "date"),
                       GetSQLValueString($_POST['brewYield'], "text"),
                       GetSQLValueString($_POST['brewInfo'], "text"),
                       GetSQLValueString($_POST['brewMead1'], "text"),
                       GetSQLValueString($_POST['brewMead2'], "text"),
                       GetSQLValueString($_POST['brewMead3'], "text"),
                       GetSQLValueString($_POST['brewExtract1'], "scrubbed"),
                       GetSQLValueString($_POST['brewExtract1Weight'], "text"),
                       GetSQLValueString($_POST['brewExtract2'], "scrubbed"),
                       GetSQLValueString($_POST['brewExtract2Weight'], "text"),
                       GetSQLValueString($_POST['brewExtract3'], "scrubbed"),
                       GetSQLValueString($_POST['brewExtract3Weight'], "text"),
                       GetSQLValueString($_POST['brewExtract4'], "scrubbed"),
                       GetSQLValueString($_POST['brewExtract4Weight'], "text"),
                       GetSQLValueString($_POST['brewExtract5'], "scrubbed"),
                       GetSQLValueString($_POST['brewExtract5Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain1'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain1Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain2'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain2Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain3'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain3Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain4'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain4Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain5'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain5Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain6'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain6Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain7'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain7Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain8'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain8Weight'], "text"),
                       GetSQLValueString($_POST['brewGrain9'], "scrubbed"),
                       GetSQLValueString($_POST['brewGrain9Weight'], "text"),
                       GetSQLValueString($_POST['brewAddition1'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition1Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition2'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition2Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition3'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition3Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition4'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition4Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition5'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition5Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition6'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition6Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition7'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition7Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition8'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition8Amt'], "text"),
                       GetSQLValueString($_POST['brewAddition9'], "scrubbed"),
                       GetSQLValueString($_POST['brewAddition9Amt'], "text"),
                       GetSQLValueString($_POST['brewHops1'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops1Weight'], "text"),
                       GetSQLValueString($_POST['brewHops1IBU'], "text"),
                       GetSQLValueString($_POST['brewHops1Time'], "text"),
                       GetSQLValueString($_POST['brewHops2'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops2Weight'], "text"),
                       GetSQLValueString($_POST['brewHops2IBU'], "text"),
                       GetSQLValueString($_POST['brewHops2Time'], "text"),
                       GetSQLValueString($_POST['brewHops3'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops3Weight'], "text"),
                       GetSQLValueString($_POST['brewHops3IBU'], "text"),
                       GetSQLValueString($_POST['brewHops3Time'], "text"),
                       GetSQLValueString($_POST['brewHops4'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops4Weight'], "text"),
                       GetSQLValueString($_POST['brewHops4IBU'], "text"),
                       GetSQLValueString($_POST['brewHops4Time'], "text"),
                       GetSQLValueString($_POST['brewHops5'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops5Weight'], "text"),
                       GetSQLValueString($_POST['brewHops5IBU'], "text"),
                       GetSQLValueString($_POST['brewHops5Time'], "text"),
                       GetSQLValueString($_POST['brewHops6'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops6Weight'], "text"),
                       GetSQLValueString($_POST['brewHops6IBU'], "text"),
                       GetSQLValueString($_POST['brewHops6Time'], "text"),
                       GetSQLValueString($_POST['brewHops7'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops7Weight'], "text"),
                       GetSQLValueString($_POST['brewHops7IBU'], "text"),
                       GetSQLValueString($_POST['brewHops7Time'], "text"),
                       GetSQLValueString($_POST['brewHops8'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops8Weight'], "text"),
                       GetSQLValueString($_POST['brewHops8IBU'], "text"),
                       GetSQLValueString($_POST['brewHops8Time'], "text"),
                       GetSQLValueString($_POST['brewHops9'], "scrubbed"),
                       GetSQLValueString($_POST['brewHops9Weight'], "text"),
                       GetSQLValueString($_POST['brewHops9IBU'], "text"),
                       GetSQLValueString($_POST['brewHops9Time'], "text"),
                       GetSQLValueString($_POST['brewHops1Use'], "text"),
                       GetSQLValueString($_POST['brewHops2Use'], "text"),
                       GetSQLValueString($_POST['brewHops3Use'], "text"),
                       GetSQLValueString($_POST['brewHops4Use'], "text"),
                       GetSQLValueString($_POST['brewHops5Use'], "text"),
                       GetSQLValueString($_POST['brewHops6Use'], "text"),
                       GetSQLValueString($_POST['brewHops7Use'], "text"),
                       GetSQLValueString($_POST['brewHops8Use'], "text"),
                       GetSQLValueString($_POST['brewHops9Use'], "text"),
                       GetSQLValueString($_POST['brewHops1Type'], "text"),
                       GetSQLValueString($_POST['brewHops2Type'], "text"),
                       GetSQLValueString($_POST['brewHops3Type'], "text"),
                       GetSQLValueString($_POST['brewHops4Type'], "text"),
                       GetSQLValueString($_POST['brewHops5Type'], "text"),
                       GetSQLValueString($_POST['brewHops6Type'], "text"),
                       GetSQLValueString($_POST['brewHops7Type'], "text"),
                       GetSQLValueString($_POST['brewHops8Type'], "text"),
                       GetSQLValueString($_POST['brewHops9Type'], "text"),
                       GetSQLValueString($_POST['brewHops1Form'], "text"),
                       GetSQLValueString($_POST['brewHops2Form'], "text"),
                       GetSQLValueString($_POST['brewHops3Form'], "text"),
                       GetSQLValueString($_POST['brewHops4Form'], "text"),
                       GetSQLValueString($_POST['brewHops5Form'], "text"),
                       GetSQLValueString($_POST['brewHops6Form'], "text"),
                       GetSQLValueString($_POST['brewHops7Form'], "text"),
                       GetSQLValueString($_POST['brewHops8Form'], "text"),
                       GetSQLValueString($_POST['brewHops9Form'], "text"),
                       GetSQLValueString($_POST['brewYeast'], "scrubbed"),
                       GetSQLValueString($_POST['brewYeastMan'], "scrubbed"),
                       GetSQLValueString($_POST['brewYeastForm'], "text"),
                       GetSQLValueString($_POST['brewYeastType'], "text"),
                       GetSQLValueString($_POST['brewYeastAmount'], "scrubbed"),
                       GetSQLValueString($_POST['brewYeastStarter'], "text"),
                       GetSQLValueString($_POST['brewYeastNutrients'], "scrubbed"),
                       GetSQLValueString($_POST['brewOG'], "text"),
                       GetSQLValueString($_POST['brewFG'], "text"),
                       GetSQLValueString($_POST['brewPrimary'], "text"),
                       GetSQLValueString($_POST['brewPrimaryTemp'], "text"),
                       GetSQLValueString($_POST['brewSecondary'], "text"),
                       GetSQLValueString($_POST['brewSecondaryTemp'], "text"),
                       GetSQLValueString($_POST['brewOther'], "text"),
                       GetSQLValueString($_POST['brewOtherTemp'], "text"),
                       GetSQLValueString($_POST['brewComments'], "text"),
                       GetSQLValueString($_POST['brewMashStep1Name'], "scrubbed"),
                       GetSQLValueString($_POST['brewMashStep1Temp'], "text"),
                       GetSQLValueString($_POST['brewMashStep1Time'], "text"),
                       GetSQLValueString($_POST['brewMashStep2Name'], "scrubbed"),
                       GetSQLValueString($_POST['brewMashStep2Temp'], "text"),
                       GetSQLValueString($_POST['brewMashStep2Time'], "text"),
                       GetSQLValueString($_POST['brewMashStep3Name'], "scrubbed"),
                       GetSQLValueString($_POST['brewMashStep3Temp'], "text"),
                       GetSQLValueString($_POST['brewMashStep3Time'], "text"),
                       GetSQLValueString($_POST['brewMashStep4Name'], "scrubbed"),
                       GetSQLValueString($_POST['brewMashStep4Temp'], "text"),
                       GetSQLValueString($_POST['brewMashStep4Time'], "text"),
                       GetSQLValueString($_POST['brewMashStep5Name'], "scrubbed"),
                       GetSQLValueString($_POST['brewMashStep5Temp'], "text"),
                       GetSQLValueString($_POST['brewMashStep5Time'], "text"),
                       GetSQLValueString($_POST['brewFinings'], "scrubbed"),
                       GetSQLValueString($_POST['brewWaterNotes'], "scrubbed"),
					   GetSQLValueString($brewBrewerID, "text"),
					   GetSQLValueString($_POST['brewCarbonationMethod'], "text"),
					   GetSQLValueString($_POST['brewCarbonationVol'], "text"),
					   GetSQLValueString($_POST['brewCarbonationNotes'], "scrubbed"),
					   GetSQLValueString($_POST['brewBoilHours'], "text"),
					   GetSQLValueString($_POST['brewBoilMins'], "text"),
					   GetSQLValueString($brewBrewerFirstName, "text"),
					   GetSQLValueString($brewBrewerLastName, "text"),
					   GetSQLValueString($_POST['brewExtract1Use'], "text"), 
					   GetSQLValueString($_POST['brewExtract2Use'], "text"), 
					   GetSQLValueString($_POST['brewExtract3Use'], "text"), 
					   GetSQLValueString($_POST['brewExtract4Use'], "text"), 
					   GetSQLValueString($_POST['brewExtract5Use'], "text"),
					   GetSQLValueString($_POST['brewGrain1Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain2Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain3Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain4Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain5Use'], "text"),
					   GetSQLValueString($_POST['brewGrain6Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain7Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain8Use'], "text"), 
					   GetSQLValueString($_POST['brewGrain9Use'], "text"),
					   GetSQLValueString($_POST['brewAddition1Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition2Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition3Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition4Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition5Use'], "text"),
					   GetSQLValueString($_POST['brewAddition6Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition7Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition8Use'], "text"), 
					   GetSQLValueString($_POST['brewAddition9Use'], "text"),
                       GetSQLValueString($id, "int"));
  
  mysql_select_db($database, $brewing);
  $Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
  
  if ($go == "beerXML") {
  $updateGoTo = "../index.php?section=".$section."&go=".$go."&filter=".$filter."&msg=3";
  header(sprintf("Location: %s", $updateGoTo));
  } else {
  $updateGoTo = "../index.php?section=".$section."&go=".$go."&filter="; if ($section == "admin") $updateGoTo .= "default"; else $updateGoTo .= $filter; $updateGoTo .= "&msg=2";
  header(sprintf("Location: %s", $updateGoTo));
  }
  
  
}

// --------------------------- If Adding a Participant ------------------------------- //

if (($action == "add") && ($dbTable == "users") && (($section == "register") || ($section == "admin"))) {
// Check to see if email address is already in the system. If so, redirect.
$username = $_POST['user_name'];

if ((strstr($username,'@')) && (strstr($username,'.'))) {
mysql_select_db($database, $brewing);
$query_userCheck = "SELECT user_name FROM users WHERE user_name = '$username'";
$userCheck = mysql_query($query_userCheck, $brewing) or die(mysql_error());
$row_userCheck = mysql_fetch_assoc($userCheck);
$totalRows_userCheck = mysql_num_rows($userCheck);

if ($totalRows_userCheck > 0) {
  if ($section == "admin") $msg = "10"; else $msg = "2";
  header("Location: ../index.php?section=".$section."&go=".$go."&action=".$action."&msg=".$msg);
  }
  else 
  {
  $password = md5($_POST['password']);
  $insertSQL = sprintf("INSERT INTO users (user_name, userLevel, password, userQuestion, userQuestionAnswer) VALUES (%s, %s, %s, %s, %s)", 
                       GetSQLValueString($username, "text"),
					   GetSQLValueString($_POST['userLevel'], "text"),
                       GetSQLValueString($password, "text"),
					   GetSQLValueString($_POST['userQuestion'], "text"),
					   GetSQLValueString($_POST['userQuestionAnswer'], "text"));
  	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
	
	if ($section != "admin") {

	mysql_select_db($database, $brewing);
	$query_login = "SELECT password FROM users WHERE user_name = '$username' AND password = '$password'";
	$login = mysql_query($query_login, $brewing) or die(mysql_error());
	$row_login = mysql_fetch_assoc($login);
	$totalRows_login = mysql_num_rows($login);

	session_start();
		// Authenticate the user
		if ($totalRows_login == 1)
			{
  			// Register the loginUsername
  			$_SESSION["loginUsername"] = $username;

  			// If the username/password combo is OK, relocate to the "protected" content index page
  			header("Location: ../index.php?action=add&section=brewer&go=".$go."&msg=1");
  			exit;
			}
		else
			{
  			// If the username/password combo is incorrect or not found, relocate to the login error page
  			header("Location: ../index.php?section=login&go=".$go."&msg=1");
  			session_destroy();
  			exit;
			}
	}
	
	if ($section == "admin") {
	header("Location: ../index.php?section=".$section."&go=".$go."&action=".$action."&filter=info&msg=1&username=".$username);
	
	}
	
/*
  $insertGoTo = "../index.php?section=login&username=".$username;
  header(sprintf("Location: %s", $insertGoTo));
*/
  }
 }
 else 
 {
 header("Location: ../index.php?section=".$section."&go=".$go."&action=".$action."&msg=3");
 }
}

// --------------------------- If Editing a Participant ------------------------------- //


if (($action == "edit") && ($dbTable == "users")) {
// Check to see if email address is already in the system. If so, redirect.
$username = $_POST['user_name'];
$usernameOld = $_POST['user_name_old'];
if ((strstr($username,'@')) && (strstr($username,'.'))) {

mysql_select_db($database, $brewing);
$query_brewerCheck = "SELECT brewerEmail FROM brewer WHERE brewerEmail = '$usernameOld'";
$brewerCheck = mysql_query($query_brewerCheck, $brewing) or die(mysql_error());
$row_brewerCheck = mysql_fetch_assoc($brewerCheck);
$totalRows_brewerCheck = mysql_num_rows($brewerCheck);

mysql_select_db($database, $brewing);
$query_userCheck = "SELECT * FROM users WHERE user_name = '$username'";
$userCheck = mysql_query($query_userCheck, $brewing) or die(mysql_error());
$row_userCheck = mysql_fetch_assoc($userCheck);
$totalRows_userCheck = mysql_num_rows($userCheck);

// --------------------------- If Changing a Participant's User Level ------------------------------- //
if ($go == "make_admin") {
$updateSQL = sprintf("UPDATE users SET userLevel=%s WHERE user_name=%s", 
					   GetSQLValueString($_POST['userLevel'], "text"),
                       GetSQLValueString($_POST['user_name'], "text"));
					   
  mysql_select_db($database, $brewing);
  $Result = mysql_query($updateSQL, $brewing) or die(mysql_error());
  
  $updateGoTo = "../index.php?section=".$section."&go=participants&msg=2";
  header(sprintf("Location: %s", $updateGoTo));  
}

// --------------------------- If Changing a Participant's User Name ------------------------------- //
if ($go == "username") {
if ($totalRows_userCheck > 0) {
  header("Location: ../index.php?section=user&action=username&id=".$id."&msg=1");
  }
  else 
  {  
  $updateSQL = sprintf("UPDATE users SET user_name=%s, userLevel=%s WHERE id=%s", 
                       GetSQLValueString($_POST['user_name'], "text"),
					   GetSQLValueString($_POST['userLevel'], "text"),
                       GetSQLValueString($id, "text")); 

  mysql_select_db($database, $brewing);
  $Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());

  $update2SQL = sprintf("UPDATE brewer SET brewerEmail=%s WHERE brewerEmail=%s", 
                       GetSQLValueString($_POST['user_name'], "text"),
                       GetSQLValueString($usernameOld, "text")); 

  mysql_select_db($database, $brewing);
  $Result2 = mysql_query($update2SQL, $brewing) or die(mysql_error());

  	mysql_select_db($database, $brewing);
  	$query_login = "SELECT user_name FROM users WHERE user_name = '$username'";
  	$login = mysql_query($query_login, $brewing) or die(mysql_error());
	$row_login = mysql_fetch_assoc($login);
	$totalRows_login = mysql_num_rows($login);
	
	session_destroy;
	session_start();
		// Authenticate the user
		if ($totalRows_login == 1)
			{
  			// Register the loginUsername
  			$_SESSION["loginUsername"] = $username;

  			// If the username/password combo is OK, relocate to the "protected" content index page
  			header("Location: ../index.php?section=list&msg=3");
  			exit;
			}
		else
			{
  			// If the username/password combo is incorrect or not found, relocate to the login error page
  			header("Location: ../index.php?section=user&action=username&msg=2");
  			session_destroy();
  			exit;
			}
/*
  $insertGoTo = "../index.php?section=login&username=".$username;
  header(sprintf("Location: %s", $insertGoTo));
*/
  }
 }
}
 else 
 {
 header("Location: ../index.php?section=user&action=username&msg=4&id=".$id);
 }

// --------------------------- If Changing a Paricipant's Password ------------------------------- //
if ($go == "password") {

// Check if old password is correct; if not redirect
$passwordOld = md5($_POST['passwordOld']);
$passwordNew = md5($_POST['password']);
mysql_select_db($database, $brewing);
$query_userPass = "SELECT password FROM users WHERE password = '$passwordOld'";
$userPass = mysql_query($query_userPass, $brewing) or die(mysql_error());
$row_userPass = mysql_fetch_assoc($userPass);
$totalRows_userPass = mysql_num_rows($userPass);

if ($passwordOld != $row_userPass['password']) {
  header("Location: ../index.php?section=user&action=password&msg=3&id=".$id);
  }
  else 
  {  
  $updateSQL = sprintf("UPDATE users SET password=%s WHERE id=%s", 
                       GetSQLValueString($passwordNew, "text"),
                       GetSQLValueString($id, "text")); 

  mysql_select_db($database, $brewing);
  $Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
  
  header("Location: ../index.php?section=list&id=".$id."&msg=4");
  }
 }
}

// --------------------------- SETUP: Adding the Admin Participant ------------------------------- //
if (($action == "add") && ($dbTable == "users") && ($section == "setup")) {
  	
	$username = $_POST['user_name'];
  	if ((strstr($username,'@')) && (strstr($username,'.'))) {
	
	$password = md5($_POST['password']);
    $insertSQL = sprintf("INSERT INTO users (user_name, userLevel, password, userQuestion, userQuestionAnswer) VALUES (%s, %s, %s, %s, %s)", 
                       GetSQLValueString($_POST['user_name'], "text"),
					   GetSQLValueString($_POST['userLevel'], "text"),
                       GetSQLValueString($password, "text"), 
					   GetSQLValueString($_POST['userQuestion'], "text"),
					   GetSQLValueString($_POST['userQuestionAnswer'], "text"));

  	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());

	$insertGoTo = "../setup.php?section=step4&go=".$username;
	header(sprintf("Location: %s", $insertGoTo));	
	
	session_start();
  	$_SESSION["loginUsername"] = $username;
	
	}
	else header("Location: ../setup.php?section=step3&msg=1");
}
// --------------------------- SETUP: Adding Admin User's Info ------------------------------- //

if (($action == "add") && ($dbTable == "brewer") && ($section == "setup")) {

  $insertSQL = sprintf("INSERT INTO brewer (brewerFirstName, brewerLastName, brewerAddress, brewerCity, brewerState, brewerZip, brewerPhone1, brewerPhone2, brewerClubs, brewerEmail, brewerSteward, brewerJudge) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['brewerFirstName'], "text"),
                       GetSQLValueString($_POST['brewerLastName'], "text"),
                       GetSQLValueString($_POST['brewerAddress'], "text"),
                       GetSQLValueString($_POST['brewerCity'], "text"),
                       GetSQLValueString($_POST['brewerState'], "text"),
                       GetSQLValueString($_POST['brewerZip'], "text"),
                       GetSQLValueString($_POST['brewerPhone1'], "text"),
                       GetSQLValueString($_POST['brewerPhone2'], "text"),
                       GetSQLValueString($_POST['brewerClubs'], "text"),
                       GetSQLValueString($_POST['brewerEmail'], "text"),
					   GetSQLValueString($_POST['brewerSteward'], "text"),
					   GetSQLValueString($_POST['brewerJudge'], "text")
					   );

	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
	
	$insertGoTo = "../index.php";
	header(sprintf("Location: %s", $insertGoTo));					   
}

// --------------------------- If Registering a Participant's Information ------------------------------- //

if (($action == "add") && ($dbTable == "brewer") && ($section != "setup")) {
  $insertSQL = sprintf("INSERT INTO brewer (brewerFirstName, brewerLastName, brewerAddress, brewerCity, brewerState, brewerZip, brewerPhone1, brewerPhone2, brewerClubs, brewerEmail, brewerSteward, brewerJudge) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['brewerFirstName'], "text"),
                       GetSQLValueString($_POST['brewerLastName'], "text"),
                       GetSQLValueString($_POST['brewerAddress'], "text"),
                       GetSQLValueString($_POST['brewerCity'], "text"),
                       GetSQLValueString($_POST['brewerState'], "text"),
                       GetSQLValueString($_POST['brewerZip'], "text"),
                       GetSQLValueString($_POST['brewerPhone1'], "text"),
                       GetSQLValueString($_POST['brewerPhone2'], "text"),
                       GetSQLValueString($_POST['brewerClubs'], "text"),
                       GetSQLValueString($_POST['brewerEmail'], "text"),
					   GetSQLValueString($_POST['brewerSteward'], "text"),
					   GetSQLValueString($_POST['brewerJudge'], "text")
					   );

	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());	
	
	if ($_POST['brewerJudge'] == "Y") $insertGoTo = "../index.php?section=judge&go=judge";
	elseif ($section == "admin") $insertGoTo = "../index.php?section=admin&go=participants&msg=1&username=".$username;
	else $insertGoTo = "../index.php?section=list&msg=1"; 
    header(sprintf("Location: %s", $insertGoTo));			   
					   
}

// --------------------------- If Editing a Participant's Information ------------------------------- //

if (($action == "edit") && ($dbTable == "brewer")) {
$updateSQL = sprintf("UPDATE brewer SET brewerFirstName=%s, brewerLastName=%s, brewerAddress=%s, brewerCity=%s, brewerState=%s, brewerZip=%s, brewerPhone1=%s, brewerPhone2=%s, brewerClubs=%s, brewerEmail=%s, brewerSteward=%s, brewerJudge=%s, brewerJudgeID=%s, brewerJudgeRank=%s, brewerJudgeLikes=%s, brewerJudgeDislikes=%s WHERE id=%s",
                       GetSQLValueString($_POST['brewerFirstName'], "text"),
                       GetSQLValueString($_POST['brewerLastName'], "text"),
                       GetSQLValueString($_POST['brewerAddress'], "text"),
                       GetSQLValueString($_POST['brewerCity'], "text"),
                       GetSQLValueString($_POST['brewerState'], "text"),
                       GetSQLValueString($_POST['brewerZip'], "text"),
                       GetSQLValueString($_POST['brewerPhone1'], "text"),
                       GetSQLValueString($_POST['brewerPhone2'], "text"),
                       GetSQLValueString($_POST['brewerClubs'], "text"),
                       GetSQLValueString($_POST['brewerEmail'], "text"),
                       GetSQLValueString($_POST['brewerSteward'], "text"),
                       GetSQLValueString($_POST['brewerJudge'], "text"),
                       GetSQLValueString($_POST['brewerJudgeID'], "text"),
                       GetSQLValueString($_POST['brewerJudgeRank'], "text"),
                       GetSQLValueString($_POST['brewerJudgeLikes'], "text"),
                       GetSQLValueString($_POST['brewerJudgeDislikes'], "text"),
                       GetSQLValueString($id, "int"));
  
  
  
  mysql_select_db($database, $brewing);
  $Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
  
  if ($_POST['brewerJudge'] == "Y") $updateSQL = "UPDATE brewer SET brewerNickname='judge' WHERE id='".$id."'"; 
  elseif (($_POST['brewerJudge'] == "N") && ($_POST['brewerSteward'] == "Y")) $updateSQL = "UPDATE brewer SET brewerNickname='steward' WHERE id='".$id."'"; 
  else $updateSQL = "UPDATE brewer SET brewerNickname=NULL WHERE id='".$id."'"; 
  
  mysql_select_db($database, $brewing);
  $Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());

  if ($go == "register") {
  $updateGoTo = "../index.php?section=brew&msg=2";
  header(sprintf("Location: %s", $updateGoTo));
  }   
  elseif ($go == "judge") { 
  $updateGoTo = "../index.php?section=list&go=".$go."&filter=default&msg=7";
  header(sprintf("Location: %s", $updateGoTo));
  }
  elseif ($go == "default") { 
  $updateGoTo = "../index.php?section=list&go=".$go."&filter=default&msg=2";
  header(sprintf("Location: %s", $updateGoTo));
  }
  else {
  $updateGoTo = "../index.php?section=".$section."&go=".$go."&filter=default&msg=2";
  header(sprintf("Location: %s", $updateGoTo));
  }
}

// --------------------------- SETUP: Adding General Contest Info ------------------------------- // 

if (($action == "add") && ($dbTable == "contest_info")) {

$insertSQL = sprintf("INSERT INTO contest_info (
contestName, 
contestHost, 
contestHostWebsite, 
contestHostLocation, 
contestDate, 
contestDate2, 
contestDate3, 
contestRegistrationDeadline, 
contestEntryDeadline, 
contestRules, 

contestJudgingLocation, 
contestAwardsLocation, 
contestContactName, 
contestContactEmail, 
contestEntryFee, 
contestCategories, 
contestBottles, 
contestShippingAddress, 
contestDropOff, 
contestAwards,

contestWinnersComplete,
contestEntryCap,
contestDate4,
contestDate5,
contestJudgingLocName2,
contestJudgingLocation2,
contestJudgingLocName3,
contestJudgingLocation3,
contestJudgingLocName4,
contestJudgingLocation4,
contestJudgingLocName5,
contestJudgingLocation5,
contestAwardsLocName,
contestEntryFee2,
contestEntryFeeDiscount,
contestEntryFeeDiscountNum,

id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['contestName'], "text"),
                       GetSQLValueString($_POST['contestHost'], "text"),
                       GetSQLValueString($_POST['contestHostWebsite'], "text"),
                       GetSQLValueString($_POST['contestHostLocation'], "text"),
                       GetSQLValueString($_POST['contestDate'], "date"),
					   GetSQLValueString($_POST['contestDate2'], "date"),
					   GetSQLValueString($_POST['contestDate3'], "date"),
                       GetSQLValueString($_POST['contestRegistrationDeadline'], "date"),
					   GetSQLValueString($_POST['contestEntryDeadline'], "date"),
                       GetSQLValueString($_POST['contestRules'], "text"),
                       GetSQLValueString($_POST['contestJudgingLocation'], "text"),
                       GetSQLValueString($_POST['contestAwardsLocation'], "text"),
                       GetSQLValueString($_POST['contestContactName'], "text"),
                       GetSQLValueString($_POST['contestContactEmail'], "text"),
                       GetSQLValueString($_POST['contestEntryFee'], "text"),
                       GetSQLValueString($_POST['contestCategories'], "text"),
                       GetSQLValueString($_POST['contestBottles'], "text"),
                       GetSQLValueString($_POST['contestShippingAddress'], "text"),
                       GetSQLValueString($_POST['contestDropOff'], "text"),
                       GetSQLValueString($_POST['contestAwards'], "text"),
					   GetSQLValueString($_POST['contestWinnersComplete'], "text"),
					   GetSQLValueString($_POST['contestEntryCap'], "text"),
					   GetSQLValueString($_POST['contestWinnersComplete'], "text"),
					   GetSQLValueString($_POST['contestEntryCap'], "text"),
					   GetSQLValueString($_POST['contestDate4'], "text"),
					   GetSQLValueString($_POST['contestDate5'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocName2'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocation2'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocName3'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocation3'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocName4'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocation4'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocName5'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocation5'], "text"),
					   GetSQLValueString($_POST['contestAwardsLocName'], "text"),
					   GetSQLValueString($_POST['contestEntryFee2'], "text"),
					   GetSQLValueString($_POST['contestEntryFeeDiscount'], "text"),
					   GetSQLValueString($_POST['contestEntryFeeDiscountNum'], "text"),
                       GetSQLValueString($id, "int"));

  mysql_select_db($database, $brewing);
  $Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());

  $insertGoTo = "../setup.php?section=step3";
  header(sprintf("Location: %s", $insertGoTo));

}

// --------------------------- If Editing General Contest Info ------------------------------- // 

if (($action == "edit") && ($dbTable == "contest_info")) {

$updateSQL = sprintf("UPDATE contest_info SET 
contestName=%s, 
contestHost=%s, 
contestHostWebsite=%s, 
contestHostLocation=%s, 
contestDate=%s, 
contestDate2=%s, 
contestDate3=%s, 
contestRegistrationDeadline=%s, 
contestEntryDeadline=%s, 
contestRules=%s, 
contestJudgingLocation=%s, 
contestAwardsLocation=%s, 
contestContactName=%s, 
contestContactEmail=%s, 
contestEntryFee=%s, 
contestCategories=%s, 
contestBottles=%s, 
contestShippingAddress=%s, 
contestDropOff=%s, 
contestAwards=%s, 
contestLogo=%s, 
contestBOSAward=%s,
contestWinnersComplete=%s,
contestEntryCap=%s,
contestWinnersComplete=%s,
contestEntryCap=%s,
contestDate4=%s,
contestDate5=%s,
contestJudgingLocName2=%s,
contestJudgingLocation2=%s,
contestJudgingLocName3=%s,
contestJudgingLocation3=%s,
contestJudgingLocName4=%s,
contestJudgingLocation4=%s,
contestJudgingLocName5=%s,
contestJudgingLocation5=%s,
contestAwardsLocName=%s,
contestEntryFee2=%s,
contestEntryFeeDiscount=%s,
contestEntryFeeDiscountNum=%s
WHERE id=%s",
                       GetSQLValueString($_POST['contestName'], "text"),
                       GetSQLValueString($_POST['contestHost'], "text"),
                       GetSQLValueString($_POST['contestHostWebsite'], "text"),
                       GetSQLValueString($_POST['contestHostLocation'], "text"),
                       GetSQLValueString($_POST['contestDate'], "date"),
					   GetSQLValueString($_POST['contestDate2'], "date"),
					   GetSQLValueString($_POST['contestDate3'], "date"),
                       GetSQLValueString($_POST['contestRegistrationDeadline'], "date"),
					   GetSQLValueString($_POST['contestEntryDeadline'], "date"),
                       GetSQLValueString($_POST['contestRules'], "text"),
                       GetSQLValueString($_POST['contestJudgingLocation'], "text"),
                       GetSQLValueString($_POST['contestAwardsLocation'], "text"),
                       GetSQLValueString($_POST['contestContactName'], "text"),
                       GetSQLValueString($_POST['contestContactEmail'], "text"),
                       GetSQLValueString($_POST['contestEntryFee'], "text"),
                       GetSQLValueString($_POST['contestCategories'], "text"),
                       GetSQLValueString($_POST['contestBottles'], "text"),
                       GetSQLValueString($_POST['contestShippingAddress'], "text"),
                       GetSQLValueString($_POST['contestDropOff'], "text"),
                       GetSQLValueString($_POST['contestAwards'], "text"),
					   GetSQLValueString($_POST['contestLogo'], "text"),
					   GetSQLValueString($_POST['contestBOSAward'], "text"),
					   GetSQLValueString($_POST['contestWinnersComplete'], "text"),
					   GetSQLValueString($_POST['contestEntryCap'], "text"),
					   GetSQLValueString($_POST['contestWinnersComplete'], "text"),
					   GetSQLValueString($_POST['contestEntryCap'], "text"),
					   GetSQLValueString($_POST['contestDate4'], "text"),
					   GetSQLValueString($_POST['contestDate5'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocName2'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocation2'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocName3'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocation3'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocName4'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocation4'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocName5'], "text"),
					   GetSQLValueString($_POST['contestJudgingLocation5'], "text"),
					   GetSQLValueString($_POST['contestAwardsLocName'], "text"),
					   GetSQLValueString($_POST['contestEntryFee2'], "text"),
					   GetSQLValueString($_POST['contestEntryFeeDiscount'], "text"),
					   GetSQLValueString($_POST['contestEntryFeeDiscountNum'], "text"),
                       GetSQLValueString($id, "int"));

  mysql_select_db($database, $brewing);
  $Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());

  $updateGoTo = "../index.php?section=admin&msg=2";
  header(sprintf("Location: %s", $updateGoTo));

}

// --------------------------- SETUP: Adding Preferences ------------------------------- //

if (($action == "add") && ($dbTable == "preferences")) {

$insertSQL = sprintf("INSERT INTO preferences (
prefsTemp, 
prefsWeight1, 
prefsWeight2, 
prefsLiquid1, 
prefsLiquid2, 
prefsPaypal, 
prefsPaypalAccount, 
prefsCurrency, 
prefsCash, 
prefsCheck, 
prefsCheckPayee, 
prefsTransFee,
prefsSponsors,
prefsSponsorLogos,
prefsSponsorLogoSize,
prefsCompLogoSize,
prefsDisplayWinners,
id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['prefsTemp'], "text"),
					   GetSQLValueString($_POST['prefsWeight1'], "text"),
                       GetSQLValueString($_POST['prefsWeight2'], "text"),
                       GetSQLValueString($_POST['prefsLiquid1'], "text"),
                       GetSQLValueString($_POST['prefsLiquid2'], "text"),
                       GetSQLValueString($_POST['prefsPaypal'], "text"),
                       GetSQLValueString($_POST['prefsPaypalAccount'], "text"),
					   GetSQLValueString($_POST['prefsCurrency'], "text"),
                       GetSQLValueString($_POST['prefsCash'], "text"),
					   GetSQLValueString($_POST['prefsCheck'], "text"),
					   GetSQLValueString($_POST['prefsCheckPayee'], "text"),
					   GetSQLValueString($_POST['prefsTransFee'], "text"),
					   GetSQLValueString("Y", "text"),
					   GetSQLValueString("Y", "text"),
					   GetSQLValueString("175", "text"),
					   GetSQLValueString("250", "text"),
					   GetSQLValueString("Y", "text"),
                       GetSQLValueString($id, "int"));
					   
	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());

  	$insertGoTo = "../setup.php?section=step2";
  	header(sprintf("Location: %s", $insertGoTo));
}


// --------------------------- If Editing Preferences ------------------------------- //

if (($action == "edit") && ($dbTable == "preferences")) {

$updateSQL = sprintf("UPDATE preferences SET prefsTemp=%s, prefsWeight1=%s, prefsWeight2=%s, prefsLiquid1=%s, prefsLiquid2=%s, prefsPaypal=%s, prefsPaypalAccount=%s, prefsCurrency=%s, prefsCash=%s, prefsCheck=%s, prefsCheckPayee=%s, prefsTransFee=%s, prefsSponsors=%s, prefsSponsorLogos=%s, prefsSponsorLogoSize=%s, prefsCompLogoSize=%s, prefsDisplayWinners=%s WHERE id=%s",
                       GetSQLValueString($_POST['prefsTemp'], "text"),
					   GetSQLValueString($_POST['prefsWeight1'], "text"),
                       GetSQLValueString($_POST['prefsWeight2'], "text"),
                       GetSQLValueString($_POST['prefsLiquid1'], "text"),
                       GetSQLValueString($_POST['prefsLiquid2'], "text"),
                       GetSQLValueString($_POST['prefsPaypal'], "text"),
                       GetSQLValueString($_POST['prefsPaypalAccount'], "text"),
                       GetSQLValueString($_POST['prefsCurrency'], "text"),
					   GetSQLValueString($_POST['prefsCash'], "text"),
					   GetSQLValueString($_POST['prefsCheck'], "text"),
					   GetSQLValueString($_POST['prefsCheckPayee'], "text"),
					   GetSQLValueString($_POST['prefsTransFee'], "text"),
					   GetSQLValueString($_POST['prefsSponsors'], "text"),
					   GetSQLValueString($_POST['prefsSponsorLogos'], "text"),
					   GetSQLValueString($_POST['prefsSponsorLogoSize'], "text"),
					   GetSQLValueString($_POST['prefsCompLogoSize'], "text"),
					   GetSQLValueString($_POST['prefsDisplayWinners'], "text"),
                       GetSQLValueString($id, "int"));
					   
	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());

  	$updateGoTo = "../index.php?section=admin&msg=2";
  	header(sprintf("Location: %s", $updateGoTo));
}

// --------------------------- If updating records in the brewing table en masse ------------------------------- //

if (($action == "update") && ($dbTable == "brewing")) {

foreach($_POST['id'] as $id)

	{ 
		$updateSQL = "UPDATE brewing SET 
		brewPaid='".$_POST["brewPaid".$id]."',
		brewWinner='".$_POST["brewWinner".$id]."',
		brewWinnerCat='".$_POST["brewWinnerCat".$id]."', 
		brewWinnerSubCat='".$_POST["brewWinnerSubCat".$id]."', 
		brewWinnerPlace='".$_POST["brewWinnerPlace".$id]."',
		brewBOSRound='".$_POST["brewBOSRound".$id]."',
		brewBOSPlace='".$_POST["brewBOSPlace".$id]."',
		brewReceived='".$_POST["brewReceived".$id]."'
		WHERE id='".$id."'"; 
		mysql_select_db($database, $brewing);
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());	
		//echo $updateSQL;
	} 

if($result1){ 
	header("location:../index.php?section=admin&go=entries&action=default&filter=".$filter."&bid=".$bid."&sort=".$sort."&dir=".$dir."&msg=9");  
	}
}

// --------------------------- If Editing Sponsors ------------------------------- //

if (($action == "edit") && ($dbTable == "sponsors")) {

  $updateSQL = sprintf("UPDATE sponsors SET sponsorName=%s, sponsorURL=%s, sponsorImage=%s, sponsorText=%s, sponsorLocation=%s WHERE id=%s",
                       GetSQLValueString($_POST['sponsorName'], "text"),
                       GetSQLValueString($_POST['sponsorURL'], "text"),
                       GetSQLValueString($_POST['sponsorImage'], "text"),
                       GetSQLValueString($_POST['sponsorText'], "text"),
					   GetSQLValueString($_POST['sponsorLocation'], "text"),
					   GetSQLValueString($id, "int"));

	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateGoTo = "../index.php?section=admin&go=sponsors&msg=2";
	header(sprintf("Location: %s", $updateGoTo));					   
}

// --------------------------- If Add Sponsors ------------------------------- //

if (($action == "add") && ($dbTable == "sponsors")) {

  $insertSQL = sprintf("INSERT INTO sponsors (sponsorName, sponsorURL, sponsorImage, sponsorText, sponsorLocation) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['sponsorName'], "text"),
                       GetSQLValueString($_POST['sponsorURL'], "text"),
                       GetSQLValueString($_POST['sponsorImage'], "text"),
                       GetSQLValueString($_POST['sponsorText'], "text"),
					   GetSQLValueString($_POST['sponsorLocation'], "text")
					   );

	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
	
	$insertGoTo = "../index.php?section=admin&go=sponsors&msg=1";
	header(sprintf("Location: %s", $insertGoTo));					   
}

?>