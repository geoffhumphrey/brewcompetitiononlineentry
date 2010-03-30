<?php require_once('../Connections/config.php'); 
session_start();
//include ('../includes/db_connect.inc.php');
include ('../includes/plug-ins.inc.php');
include ('../includes/url_variables.inc.php');

mysql_select_db($database, $brewing);
$query_contest_info = "SELECT * FROM contest_info WHERE id=1";
$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
$row_contest_info = mysql_fetch_assoc($contest_info);

$query_log = sprintf("SELECT * FROM brewing WHERE id = '%s'", $id);
$log = mysql_query($query_log, $brewing) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log);

$query_user = sprintf("SELECT * FROM users WHERE id = '%s'", $bid);
$user = mysql_query($query_user, $brewing) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

$query_style1 = sprintf("SELECT * FROM styles WHERE brewStyle = '%s'", $style);
$style1 = mysql_query($query_style1, $brewing) or die(mysql_error());
$row_style1 = mysql_fetch_assoc($style1);
$totalRows_style1 = mysql_num_rows($style1);

$query_style2 = "SELECT * FROM styles";
$style2 = mysql_query($query_style2, $brewing) or die(mysql_error());
$row_style2 = mysql_fetch_assoc($style2);
$totalRows_style2 = mysql_num_rows($style2);

$query_club = "SELECT * FROM brewer WHERE id = '1'";
$club = mysql_query($query_club, $brewing) or die(mysql_error());
$row_club = mysql_fetch_assoc($club);
$totalRows_club = mysql_num_rows($club);

$query_brewer = sprintf("SELECT * FROM brewer WHERE brewerEmail = '%s'", $row_user['user_name']);
$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
$row_brewer = mysql_fetch_assoc($brewer);
$totalRows_brewer = mysql_num_rows($brewer);

// User Friendly Style Names //
include ('../includes/style_convert.inc.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_log['brewName']; ?> Entry/Recipe Form</title>
<script type="text/javascript" src="../js_includes/smoothscroll.js"></script>
<style type="text/css">
<!--
body 					{ background-color: #ffffff; color: #000000; font-family: Arial; font-size: 10pt; }
table					{ border-collapse: collapse; width: 100%; }
table td				{ padding: 2px; }
.headerTitle 			{ font-size: 22px; font-weight: bold; margin: 0; padding: 0; }
.headerTitleSm 			{ font-size: 15px; font-weight: bold; margin: 0; padding: 0; }
.bottle, .medium 		{ font-size: .9em; } 
.small 					{ font-size: .7em; margin: 0; } 
#printContainer 		{ width: 100%; }
.bdr1_thick	 			{ border: 5px solid #000000; }
.bdr1B_thick	 		{ border-bottom: 5px solid #000000; }
.bdr1B_thick_dotted 	{ border-bottom: 3px dotted #000000; }
.bdr1B 					{ border-bottom: 1px solid #000000; }
.bdr1L 					{ border-left: 1px solid #000000; }
.bdr1R 					{ border-right: 1px solid #000000; }
.bdr1T 					{ border-top: 1px solid #000000; }
.bdr1R_dashed			{ border-right: 1px dashed #000000; }
.bdr1B_dashed			{ border-bottom: 1px dashed #000000; }
.headerItalic 		    { font-size: 8px; font-style: italic; }
table.small	td			{ padding: 1px 2px 1px 3px; }
table.bottleLabel		{ border-collapse: collapse; width: 700px; }
table.bottleLabel-inner	{ margin: 15px; width: 325px }
table.bottleLabel-inner	td { padding: 5px; text-align: left; }
.error 					{ color: #FF0000; font-size: 1em; font-weight: bold; margin: 0 0 1em 0; padding: .5em .5em .5em 1.5em; background-image: url(../images/error.png); background-position: center left; background-repeat: no-repeat; }
-->
</style>
</head>
<body <?php if ($action == "print") echo "onload=\"javascript:window.print()\""; ?>>
<div id="printContainer">
<a name="labels_top" id="labelsOtop"></a>
<?php if ($action != "print") { ?>
<table>
  <tr>
    <td><span class="error">If any items are missing, close this window and edit the entry.</span></td>
    <td width="5%" align="right" nowrap="nowrap"><img src = "../images/eye.png" align="absmiddle" />&nbsp;<a href="#labels">View Bottle Labels</a>&nbsp;&nbsp;&nbsp;</td>
    <td width="5%" align="right" nowrap="nowrap"><img src = "../images/printer.png" align="absmiddle" />&nbsp;<a href="?action=print&id=<?php echo $id; ?>&bid=<?php echo $bid; ?>">Print</a></td>
  </tr>
</table>
<?php } ?>
<table>
  <tr>
    <td class="bdr1B_thick" width="10%" valign="top"><img src="../images/bjcp_logo.jpg" alt="BJCP Logo" width="65" height="60" align="left" /></td>
    <td class="bdr1B_thick" width="80%" align="left" valign="bottom">
    <table>
      <tr>
        <td class="headerTitleSm">AHA/BJCP Sanctioned Competition Program</td>
      </tr>
      <tr>
        <td class="headerTitle">ENTRY/RECIPE FORM</td>
      </tr>
    </table>    </td>
    <td class="bdr1B_thick" width="10%" valign="top"><img src="../images/aha_logo.jpg" alt="AHA Logo" width="110" height="60" border="0" align="right" /></td>
  </tr>
</table>
<table>
  <tr>
    <td><table>
      <tr>
        <td width="5%" nowrap="nowrap" class="headerTitleSm">Brewer(s) Information</td>
        <td class="bdr1B_thick_dotted">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
<table>
  <tr>
    <td width="10%" nowrap="nowrap">Name(s) </td>
    <td class="bdr1B"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></td>
    <td width="10%" nowrap="nowrap">Street Address</td>
    <td width="40%" class="bdr1B"><?php echo $row_brewer['brewerAddress']; ?></td>
    </tr>
  <tr>
    <td width="10%">City</td>
    <td class="bdr1B"><?php echo $row_brewer['brewerCity']; ?></td>
    <td width="10%">State/Zip</td>
    <td width="40%" class="bdr1B"><?php echo $row_brewer['brewerState']." ".$row_brewer['brewerZip']; ?></td>
    </tr>
</table>
<table>
  <tr>
    <td width="15%">Phone (h)</td>
    <td class="bdr1B"><?php echo $row_brewer['brewerPhone1']; ?></td>
    <td width="60">Phone (w)</td>
    <td class="bdr1B"><?php echo $row_brewer['brewerPhone2']; ?></td>
    <td width="75" nowrap="nowrap">Email Address</td>
    <td class="bdr1B"><?php echo $row_brewer['brewerEmail']; ?></td>
  </tr>
  <tr>
    <td width="15%">Club Name</td>
    <td colspan="5" class="bdr1B"><?php echo $row_brewer['brewerClubs']; ?></td>
  </tr>
</table>
<table>
  <tr>
    <td colspan="6"><table>
      <tr>
        <td width="5%" nowrap="nowrap" class="headerTitleSm">Entry Information</td>
        <td class="bdr1B_thick_dotted">&nbsp;</td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td width="10%" nowrap="nowrap">Name of Brew</td>
    <td width="30%" class="bdr1B"><?php echo $row_log['brewName']; ?></td>
    <td width="10%" nowrap="nowrap">Category (No.)</td>
    <td width="20%" class="bdr1B"><?php echo $row_log['brewCategory']; ?></td>
    <td width="5%" nowrap="nowrap">Subcategory (A-F)</td>
    <td class="bdr1B"><?php echo $row_log['brewSubCategory']; ?></td>
  </tr>
  <tr>
    <td colspan="2" nowrap="nowrap">Category/Subcategory (print full names)</td>
    <td colspan="4" class="bdr1B"><?php if ($row_log['brewCategory'] < 29) echo $styleConvert; else echo $row_contest_info['contestName']." Style"; echo ": ".$row_log['brewStyle']; ?></td>
  </tr>
</table>
<table>
  <tr>
    <td width="49%" align="left" valign="top">
    <table>
      <tr>
        <td colspan="4">For Mead and Cider</td>
        <td colspan="2">For Mead</td>
        </tr>
      <tr>
        <td width="2%"><?php if ($row_log['brewMead1'] == "Still") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else echo "<img src=\"../images/box.jpg\" border=\"0\">";  ?></td>
        <td width="23%" nowrap="nowrap">Still</td>
        <td width="2%"><?php if ($row_log['brewMead2'] == "Dry") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else  echo "<img src=\"../images/box.jpg\" border=\"0\">";  ?></td>
        <td width="23%" nowrap="nowrap">Dry</td>
        <td width="2%"><?php if ($row_log['brewMead3'] == "Hydromel") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else  echo "<img src=\"../images/box.jpg\" border=\"0\">";  ?></td>
        <td nowrap="nowrap">Hydromel (light mead)</td>
      </tr>
      <tr>
        <td width="2%"><?php if ($row_log['brewMead1'] == "Petillant") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else  echo "<img src=\"../images/box.jpg\" border=\"0\">"; ?></td>
        <td width="23%" nowrap="nowrap">Petillant</td>
        <td width="2%"><?php if ($row_log['brewMead2'] == "Semi-Sweet") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else  echo "<img src=\"../images/box.jpg\" border=\"0\">"; ?></td>
        <td width="23%" nowrap="nowrap">Semi-Sweet</td>
        <td width="2%"><?php if ($row_log['brewMead3'] == "Standard Mead") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else  echo "<img src=\"../images/box.jpg\" border=\"0\">"; ?></td>
        <td nowrap="nowrap">Standard Mead</td>
      </tr>
      <tr>
        <td width="2%"><?php if ($row_log['brewMead1'] == "Sparkling") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else  echo "<img src=\"../images/box.jpg\" border=\"0\">";?></td>
        <td width="23%" nowrap="nowrap">Sparkling</td>
        <td width="2%"><?php if ($row_log['brewMead2'] == "Sweet") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else  echo "<img src=\"../images/box.jpg\" border=\"0\">"; ?></td>
        <td width="23%" nowrap="nowrap">Sweet</td>
        <td width="2%"><?php if ($row_log['brewMead3'] == "Sparkling") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else  echo "<img src=\"../images/box.jpg\" border=\"0\">"; ?></td>
        <td nowrap="nowrap">Sack (strong mead)</td>
      </tr>
    </table>
    </td>
    <td width="2%" align="left" valign="top">&nbsp;</td>
    <td width="49%" align="left" valign="top">
    <table>
      <tr>
        <td>Special Ingredients/Classic Style</td>
      </tr>
      <tr>
        <td class="small">(required for categories 6D, 16E, 17F, 20, 21, 22B, 22C, 23, 25C, 26A, 26C, 27E, 28B-D)</td>
      </tr>
      <tr>
        <td height="50" class="bdr1B bdr1L bdr1T bdr1R"><?php echo $row_log['brewInfo']; ?></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table>
      <tr>
        <td>
        <table>
          <tr>
            <td width="5%" nowrap="nowrap" class="headerTitleSm">Ingredients and Procedures</td>
            <td class="bdr1B_thick_dotted">&nbsp;</td>
          </tr>
        </table>
        </td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td width="49%" rowspan="4" align="left" valign="top">
    <table>
      <tr>
        <td width="5%" nowrap="nowrap">Number of U.S. gallons brewed for this recipe</td>
        <td class="bdr1B"><?php echo $row_log['brewYield']; ?></td>
        </tr>
      <tr>
        <td>WATER TREATMENT Type/Amount</td>
        <td class="bdr1B"><?php echo $row_log['brewWaterNotes']; ?></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
    </table>
      <table>
        <tr>
          <td colspan="2" nowrap="nowrap"><em><strong>YEAST CULTURE</strong></em></td>
          <td width="1%"><?php if ($row_log['brewYeastForm'] == "Liquid") echo "<img src=\"../images/check.jpg\" border=\"0\">";  else echo "<img src=\"../images/box.jpg\" border=\"0\">"; ?></td>
          <td>Liquid</td>
          <td width="1%"><?php if ($row_log['brewYeastForm'] == "Dry") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else  echo "<img src=\"../images/box.jpg\" border=\"0\">"; ?></td>
          <td>Dried</td>
        </tr>
        <tr>
          <td colspan="2" nowrap="nowrap">Did you use a starter?</td>
          <td width="1%"><?php if ($row_log['brewYeastStarter'] == "Y") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else  echo "<img src=\"../images/box.jpg\" border=\"0\">";  ?>
          <td>Yes</td>
          <td width="1%"><?php if ($row_log['brewYeastStarter'] == "N") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else  echo "<img src=\"../images/box.jpg\" border=\"0\">"; ?></td>
          <td>No</td>
        </tr>
        <tr>
          <td width="5%">Type</td>
          <td colspan="5" class="bdr1B"><?php echo $row_log['brewYeast']; ?></td>
        </tr>
        <tr>
          <td width="5%">Brand</td>
          <td colspan="5" class="bdr1B"><?php echo $row_log['brewYeastMan']; ?></td>
        </tr>
        <tr>
          <td width="5%">Amount</td>
          <td colspan="5" class="bdr1B"><?php echo $row_log['brewYeastAmount']; ?></td>
        </tr>
        <tr>
          <td colspan="2"><em><strong>YEAST NUTRIENTS </strong></em>Type/Amount</td>
          <td colspan="4" class="bdr1B"><?php echo $row_log['brewYeastNutrients']; ?></td>
        </tr>
      </table>
      <table>
        <tr>
          <td align="left" valign="top"><strong><em>CARBONATION</em></strong></td>
          <td width="5%"><?php if ($row_log['brewCarbonationMethod'] == "Y") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else  echo "<img src=\"../images/box.jpg\" border=\"0\">"; ?></td>
          <td>forced CO<sub>2</sub></td>
          <td width="5%"><?php if ($row_log['brewCarbonationMethod'] == "N") echo "<img src=\"../images/check.jpg\" border=\"0\">"; else  echo "<img src=\"../images/box.jpg\" border=\"0\">"; ?></td>
          <td>Bottle Conditioned</td>
        </tr>
        <tr>
          <td>Volumes of CO<sub>2</sub></td>
          <td colspan="4" class="bdr1B"><?php echo $row_log['brewCarbonationVol']; ?></td>
        </tr>
        <tr>
      	  <td>Type/Amount of Priming Sugar</td>
          <td colspan="4" class="bdr1B"><?php echo $row_log['brewCarbonationNotes']; ?></td>
          </tr>
      </table>
      <table>
        <tr>
          <td colspan="2" nowrap="nowrap"><em><strong>SPECIFIC GRAVITIES </strong></em></td>
          <td width="20%" align="right">Original</td>
          <td width="50%" class="bdr1B"><?php echo $row_log['brewOG']; ?></td>
        </tr>
        <tr>
          <td colspan="2" nowrap="nowrap">&nbsp;</td>
          <td width="20%" align="right">Terminal</td>
          <td width="50%" class="bdr1B"><?php echo $row_log['brewFG']; ?></td>
        </tr>
      </table>
      <table>
        <tr>
          <td nowrap="nowrap"><em><strong>FERMENTATION</strong></em></td>
          <td width="5%">&nbsp;</td>
          <td align="center">Duration (days)</td>
          <td width="5%">&nbsp;</td>
          <td align="center">Temperature (&deg;F)</td>
        </tr>
        <tr>
          <td align="right">Primary</td>
          <td width="5%">&nbsp;</td>
          <td align="center" class="bdr1B"><?php echo $row_log['brewPrimary']; ?></td>
          <td width="5%">&nbsp;</td>
          <td align="center" class="bdr1B"><?php echo $row_log['brewPrimaryTemp']; ?></td>
        </tr>
        <tr>
          <td align="right">Secondary</td>
          <td width="5%">&nbsp;</td>
          <td align="center" class="bdr1B"><?php echo $row_log['brewSecondary']; ?></td>
          <td width="5%">&nbsp;</td>
          <td align="center" class="bdr1B"><?php echo $row_log['brewSecondaryTemp']; ?></td>
        </tr>
        <?php if ($row_log['brewTertiary'] != "") { ?>
        <tr>
          <td align="right">Tertiary</td>
          <td width="5%">&nbsp;</td>
          <td align="center" class="bdr1B"><?php echo $row_log['brewTertiary']; ?></td>
          <td width="5%">&nbsp;</td>
          <td align="center" class="bdr1B"><?php echo $row_log['brewTertiaryTemp']; ?></td>
        </tr>
        <?php } 
		if ($row_log['brewLager'] != "") { ?>
        <tr>
          <td align="right">Lager</td>
          <td width="5%">&nbsp;</td>
          <td align="center" class="bdr1B"><?php echo $row_log['brewLager']; ?></td>
          <td width="5%">&nbsp;</td>
          <td align="center" class="bdr1B"><?php echo $row_log['brewLagerTemp']; ?></td>
        </tr>
        <?php } ?>
      </table>
      <table>
        <tr>
          <td width="5%" nowrap="nowrap"><em><strong>BREWING DATE</strong></em></td>
          <td class="bdr1B"><?php $date = $row_log['brewDate']; $realdate = dateconvert($date,2); echo $realdate; ?></td>
        </tr>
        <tr>
          <td width="5%" nowrap="nowrap"><em><strong>BOTTLING DATE</strong></em></td>
          <td class="bdr1B"><?php $date = $row_log['brewBottleDate']; $realdate = dateconvert($date,2); echo $realdate; ?></td>
        </tr>
      </table></td>
    <td width="2%" rowspan="4" align="left" valign="top">&nbsp;</td>
    <td width="49%" align="left" valign="top">
    <table class="small">
      <tr>
        <td colspan="3" class="bdr1B"><strong>FERMENTABLES</strong> (MALT, MALT EXTRACT, ADJUNCTS, HONEY OR OTHER SUGARS)</td>
        </tr>
      <tr>
        <td align="left" valign="top" class="headerItalic bdr1B bdr1L">AMOUNT (LB.)</td>
        <td align="left" valign="top" class="headerItalic bdr1B bdr1L">TYPE/BRAND</td>
        <td align="left" valign="top" class="headerItalic bdr1B bdr1L bdr1R">USE (MASH/STEEP)</td>
      </tr>
      <?php if ($row_log['brewGrain1'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewGrain1Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewGrain1Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewGrain1']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewGrain1Use'];  ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewGrain2'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewGrain2Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewGrain2Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewGrain2']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewGrain2Use'];  ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewGrain3'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewGrain3Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewGrain3Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewGrain3']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewGrain3Use'];  ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewGrain4'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewGrain4Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewGrain4Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewGrain4']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewGrain4Use'];  ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewGrain5'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewGrain5Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewGrain5Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewGrain5']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewGrain5Use'];  ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewGrain6'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewGrain6Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewGrain6Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewGrain6']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewGrain6Use'];  ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewGrain7'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewGrain7Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewGrain7Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewGrain7']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewGrain7Use'];  ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewGrain8'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewGrain8Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewGrain8Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewGrain8']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewGrain8Use'];  ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewGrain9'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewGrain9Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewGrain9Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewGrain9']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewGrain9Use'];  ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewExtract1'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewExtract1Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewExtract1Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewExtract1']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewExtract1Use'];  ?></td>      </tr>
      <?php } ?>
      <?php if ($row_log['brewExtract2'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewExtract2Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewExtract2Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewExtract2']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewExtract2Use'];  ?></td>      </tr>
      <?php } ?>
      <?php if ($row_log['brewExtract3'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewExtract3Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewExtract3Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewExtract3']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewExtract3Use'];  ?></td>      </tr>
      <?php } ?>
	  <?php if ($row_log['brewExtract4'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewExtract4Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewExtract4Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewExtract4']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewExtract4Use'];  ?></td>      </tr>
      <?php } ?>
      <?php if ($row_log['brewExtract5'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewExtract5Weight'] * 2.204; echo round($convert,2); } else echo $row_log['brewExtract5Weight']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewExtract5']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewExtract5Use'];  ?></td>      </tr>
      <?php } ?>
      <?php if ($row_log['brewAddition1'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewAddition1Amt'] * 2.204; echo round($convert,2); } else echo $row_log['brewAddition1Amt']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewAddition1']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewAddition1Use'];  ?></td>      </tr>
      <?php } ?>
      <?php if ($row_log['brewAddition2'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewAddition2Amt'] * 2.204; echo round($convert,2); } else echo $row_log['brewAddition2Amt']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewAddition2']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewAddition2Use'];  ?></td>      </tr>
      <?php } ?>
      <?php if ($row_log['brewAddition3'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewAddition3Amt'] * 2.204; echo round($convert,2); } else echo $row_log['brewAddition3Amt']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewAddition3']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewAddition3Use'];  ?></td>      </tr>
      <?php } ?>
      <?php if ($row_log['brewAddition4'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewAddition4Amt'] * 2.204; echo round($convert,2); } else echo $row_log['brewAddition4Amt']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewAddition4']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewAddition4Use'];  ?></td>      </tr>
      <?php } ?>
      <?php if ($row_log['brewAddition5'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewAddition5Amt'] * 2.204; echo round($convert,2); } else echo $row_log['brewAddition5Amt']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewAddition5']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewAddition5Use'];  ?></td>      </tr>
      <?php } ?>
      <?php if ($row_log['brewAddition6'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewAddition6Amt'] * 2.204; echo round($convert,2); } else echo $row_log['brewAddition6Amt']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewAddition6']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewAddition6Use'];  ?></td>      </tr>
      <?php } ?>
      <?php if ($row_log['brewAddition7'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewAddition7Amt'] * 2.204; echo round($convert,2); } else echo $row_log['brewAddition7Amt']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewAddition7']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewAddition7Use'];  ?></td>      </tr>
      <?php } ?>
      <?php if ($row_log['brewAddition8'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewAddition8Amt'] * 2.204; echo round($convert,2); } else echo $row_log['brewAddition8Amt']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewAddition8']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewAddition8Use'];  ?></td>      </tr>
      <?php } ?>
      <?php if ($row_log['brewAddition9'] != "") { ?>
      <tr>
        <td width="30%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight2'] == "kilograms") { $convert = $row_log['brewAddition9Amt'] * 2.204; echo round($convert,2); } else echo $row_log['brewAddition9Amt']; ?></td>
        <td width="35%" align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewAddition9']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewAddition9Use'];  ?></td>      </tr>
      <?php } ?>
    </table>
    </td>
  </tr>
  <tr>
    <td width="49%" align="left" valign="top">
    <table class="small">
      <tr>
        <td colspan="6" align="left" valign="top"><strong>HOPS</strong></td>
        </tr>
      <tr>
        <td width="20%" align="left" valign="top" class="headerItalic bdr1B bdr1L bdr1T">AMOUNT (OZ.)</td>
        <td align="left" valign="top" class="headerItalic bdr1B bdr1L bdr1T">PELLETS OR<br /> WHOLE?</td>
        <td align="left" valign="top" class="headerItalic bdr1B bdr1L bdr1T">TYPE</td>
        <td align="left" valign="top" class="headerItalic bdr1B bdr1L bdr1T">%A ACID</td>
        <td align="left" valign="top" class="headerItalic bdr1B bdr1L bdr1T">USE (BOIL,<br />STEEP, DRY, ETC.)</td>
        <td align="left" valign="top" class="headerItalic bdr1B bdr1L bdr1T bdr1R">MIN. FROM<br />END OF BOIL</td>
      </tr>
      <?php if ($row_log['brewHops1'] != "") { ?>
      <tr>
        <td width="20%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight1'] == "grams") { $convert = ($row_log['brewHops1Weight'] * 0.0352); echo round ($convert,2); } else echo $row_log['brewHops1Weight']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php if (($row_log['brewHops1Form'] == "Leaf") || ($row_log['brewHops1Form'] == "Plug")) echo "Whole"; else echo "Pellets"; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops1']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops1IBU']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops1Use']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewHops1Time']; ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewHops2'] != "") { ?>
      <tr>
        <td width="20%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight1'] == "grams") { $convert = ($row_log['brewHops2Weight'] * 0.0352); echo round ($convert,2); } else echo $row_log['brewHops2Weight']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php if (($row_log['brewHops2Form'] == "Leaf") || ($row_log['brewHops2Form'] == "Plug")) echo "Whole"; else echo "Pellets"; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops2']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops2IBU']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops2Use']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewHops2Time']; ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewHops3'] != "") { ?>
      <tr>
        <td width="20%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight1'] == "grams") { $convert = ($row_log['brewHops3Weight'] * 0.0352); echo round ($convert,2); } else echo $row_log['brewHops3Weight']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php if (($row_log['brewHops3Form'] == "Leaf") || ($row_log['brewHops3Form'] == "Plug")) echo "Whole"; else echo "Pellets"; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops3']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops3IBU']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops3Use']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewHops3Time']; ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewHops4'] != "") { ?>
      <tr>
        <td width="20%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight1'] == "grams") { $convert = ($row_log['brewHops4Weight'] * 0.0352); echo round ($convert,2); } else echo $row_log['brewHops4Weight']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php if (($row_log['brewHops4Form'] == "Leaf") || ($row_log['brewHops4Form'] == "Plug")) echo "Whole"; else echo "Pellets"; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops4']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops4IBU']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops4Use']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewHops4Time']; ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewHops5'] != "") { ?>
      <tr>
        <td width="20%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight1'] == "grams") { $convert = ($row_log['brewHops5Weight'] * 0.0352); echo round ($convert,2); } else echo $row_log['brewHops5Weight']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php if (($row_log['brewHops5Form'] == "Leaf") || ($row_log['brewHops5Form'] == "Plug")) echo "Whole"; else echo "Pellets"; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops5']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops5IBU']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops5Use']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewHops5Time']; ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewHops6'] != "") { ?>
      <tr>
        <td width="20%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight1'] == "grams") { $convert = ($row_log['brewHops6Weight'] * 0.0352); echo round ($convert,2); } else echo $row_log['brewHops6Weight']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php if (($row_log['brewHops6Form'] == "Leaf") || ($row_log['brewHops6Form'] == "Plug")) echo "Whole"; else echo "Pellets"; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops6']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops6IBU']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops6Use']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewHops6Time']; ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewHops7'] != "") { ?>
      <tr>
        <td width="20%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight1'] == "grams") { $convert = ($row_log['brewHops7Weight'] * 0.0352); echo round ($convert,2); } else echo $row_log['brewHops7Weight']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php if (($row_log['brewHops7Form'] == "Leaf") || ($row_log['brewHops7Form'] == "Plug")) echo "Whole"; else echo "Pellets"; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops7']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops7IBU']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops7Use']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewHops7Time']; ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewHops8'] != "") { ?>
      <tr>
        <td width="20%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight1'] == "grams") { $convert = ($row_log['brewHops8Weight'] * 0.0352); echo round ($convert,2); } else echo $row_log['brewHops8Weight']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php if (($row_log['brewHops8Form'] == "Leaf") || ($row_log['brewHops8Form'] == "Plug")) echo "Whole"; else echo "Pellets"; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops8']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops8IBU']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops8Use']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewHops8Time']; ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_log['brewHops9'] != "") { ?>
      <tr>
        <td width="20%" align="left" valign="top" class="medium bdr1B bdr1L"><?php if ($row_prefs['prefs_weight1'] == "grams") { $convert = ($row_log['brewHops9Weight'] * 0.0352); echo round ($convert,2); } else echo $row_log['brewHops9Weight']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php if (($row_log['brewHops9Form'] == "Leaf") || ($row_log['brewHops9Form'] == "Plug")) echo "Whole"; else echo "Pellets"; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops9']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops9IBU']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L"><?php echo $row_log['brewHops9Use']; ?></td>
        <td align="left" valign="top" class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewHops9Time']; ?></td>
      </tr>
      <?php } ?>
    </table>
    </td>
  </tr>
  <tr>
    <td width="49%" align="left" valign="top">
    <table class="small">
      <tr>
        <td colspan="3"><strong>MASH SCHEDULE</strong></td>
        </tr>
      <tr>
        <td width="33%" class="headerItalic bdr1B bdr1L bdr1T">STEP</td>
        <td width="34%" class="headerItalic bdr1B bdr1L bdr1T">TEMPERATURE</td>
        <td width="33%" class="headerItalic bdr1B bdr1L bdr1T bdr1R">TIME</td>
      </tr>
      <tr>
        <td class="medium bdr1B bdr1L"><?php echo $row_log['brewMashStep1Name']."&nbsp;"; ?></td>
        <td width="34%" class="medium bdr1B bdr1L"><?php echo $row_log['brewMashStep1Temp']; ?></td>
        <td class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewMashStep1Time']; ?></td>
      </tr>
      <tr>
        <td class="medium bdr1B bdr1L"><?php echo $row_log['brewMashStep2Name']."&nbsp;"; ?></td>
        <td width="34%" class="medium bdr1B bdr1L"><?php echo $row_log['brewMashStep2Temp']; ?></td>
        <td class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewMashStep2Time']; ?></td>
      </tr>
      <tr>
        <td class="medium bdr1B bdr1L"><?php echo $row_log['brewMashStep3Name']."&nbsp;"; ?></td>
        <td width="34%" class="medium bdr1B bdr1L"><?php echo $row_log['brewMashStep3Temp']; ?></td>
        <td class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewMashStep3Time']; ?></td>
      </tr>
      <tr>
        <td class="medium bdr1B bdr1L"><?php echo $row_log['brewMashStep4Name']."&nbsp;"; ?></td>
        <td width="34%" class="medium bdr1B bdr1L"><?php echo $row_log['brewMashStep4Temp']; ?></td>
        <td class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewMashStep4Time']; ?></td>
      </tr>
      <tr>
        <td class="medium bdr1B bdr1L"><?php echo $row_log['brewMashStep5Name']."&nbsp;"; ?></td>
        <td width="34%" class="medium bdr1B bdr1L"><?php echo $row_log['brewMashStep5Temp']; ?></td>
        <td class="medium bdr1B bdr1L bdr1R"><?php echo $row_log['brewMashStep5Time']; ?></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td width="49%" align="left" valign="top">
    <table>
      <tr>
        <td colspan="2">Finings</td>
        </tr>
      <tr>
        <td width="5%">Type/Amount</td>
        <td class="bdr1B"><?php echo $row_log['brewFinings']; ?></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php if ($row_log['brewComments'] != "") { ?><br style="page-break-after:always;"><?php echo $row_log['brewComments']; } ?>
<p class="small">This form is emulates the BJCP Entry/Recipe Form Copyright ©2006 Beer Judge Certification Program rev. 070307</p>
<br style="page-break-after:always;">
<br />
<a name="labels" id="labels"></a>
<table align="center" class="bottleLabel">
  <tr>
    <td align="center" valign="middle">
    <table class="bdr1_thick bottle bottleLabel-inner">
      <tr>
        <td colspan="4"><div align="center"><img src="../images/bottleID.jpg" alt="Bottle ID Form" width="230" height="20" /></div></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Name</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Street Address</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerAddress']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">City</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerCity']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">State</td>
        <td class="bdr1B"><?php echo $row_brewer['brewerState']; ?></td>
        <td width="5%">Zip</td>
        <td width="50%" class="bdr1B"><?php echo $row_brewer['brewerZip']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Phone Number</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerPhone1']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Email Address</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerEmail']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Name of Beer</td>
        <td colspan="3" class="bdr1B"><?php echo $row_log['brewName']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Category Entered</td>
        <td colspan="3" class="bdr1B"><?php echo $row_log['brewCategory']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Subcategory Entered</td>
        <td colspan="3" class="bdr1B"><?php echo $row_log['brewSubCategory']; ?></td>
      </tr>
      <tr>
        <td nowrap="nowrap">Homebrew Club</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerClubs']; ?></td>
      </tr>
      <tr>
        <td colspan="4"><div align="center"><img src="../images/bottleID_attach.jpg" alt="Attach To Each Bottle" width="230" height="20" /></div></td>
      </tr>
    </table></td>
    <td align="center" valign="middle">
      <table class="bdr1_thick bottle bottleLabel-inner">
      <tr>
        <td colspan="4"><div align="center"><img src="../images/bottleID.jpg" alt="Bottle ID Form" width="230" height="20" /></div></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Name</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Street Address</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerAddress']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">City</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerCity']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">State</td>
        <td class="bdr1B"><?php echo $row_brewer['brewerState']; ?></td>
        <td width="5%">Zip</td>
        <td width="50%" class="bdr1B"><?php echo $row_brewer['brewerZip']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Phone Number</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerPhone1']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Email Address</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerEmail']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Name of Beer</td>
        <td colspan="3" class="bdr1B"><?php echo $row_log['brewName']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Category Entered</td>
        <td colspan="3" class="bdr1B"><?php echo $row_log['brewCategory']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Subcategory Entered</td>
        <td colspan="3" class="bdr1B"><?php echo $row_log['brewSubCategory']; ?></td>
      </tr>
      <tr>
        <td nowrap="nowrap">Homebrew Club</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerClubs']; ?></td>
      </tr>
      <tr>
        <td colspan="4"><div align="center"><img src="../images/bottleID_attach.jpg" alt="Attach To Each Bottle" width="230" height="20" /></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="middle"><table class="bdr1_thick bottle bottleLabel-inner">
      <tr>
        <td colspan="4"><div align="center"><img src="../images/bottleID.jpg" alt="Bottle ID Form" width="230" height="20" /></div></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Name</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Street Address</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerAddress']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">City</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerCity']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">State</td>
        <td class="bdr1B"><?php echo $row_brewer['brewerState']; ?></td>
        <td width="5%">Zip</td>
        <td width="50%" class="bdr1B"><?php echo $row_brewer['brewerZip']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Phone Number</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerPhone1']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Email Address</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerEmail']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Name of Beer</td>
        <td colspan="3" class="bdr1B"><?php echo $row_log['brewName']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Category Entered</td>
        <td colspan="3" class="bdr1B"><?php echo $row_log['brewCategory']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Subcategory Entered</td>
        <td colspan="3" class="bdr1B"><?php echo $row_log['brewSubCategory']; ?></td>
      </tr>
      <tr>
        <td nowrap="nowrap">Homebrew Club</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerClubs']; ?></td>
      </tr>
      <tr>
        <td colspan="4"><div align="center"><img src="../images/bottleID_attach.jpg" alt="Attach To Each Bottle" width="230" height="20" /></div></td>
      </tr>
    </table></td>
    <td align="center" valign="middle">
      <table class="bdr1_thick bottle bottleLabel-inner">
      <tr>
        <td colspan="4"><div align="center"><img src="../images/bottleID.jpg" alt="Bottle ID Form" width="230" height="20" /></div></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Name</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Street Address</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerAddress']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">City</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerCity']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">State</td>
        <td class="bdr1B"><?php echo $row_brewer['brewerState']; ?></td>
        <td width="5%">Zip</td>
        <td width="50%" class="bdr1B"><?php echo $row_brewer['brewerZip']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Phone Number</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerPhone1']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Email Address</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerEmail']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Name of Beer</td>
        <td colspan="3" class="bdr1B"><?php echo $row_log['brewName']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Category Entered</td>
        <td colspan="3" class="bdr1B"><?php echo $row_log['brewCategory']; ?></td>
      </tr>
      <tr>
        <td width="5%" nowrap="nowrap">Subcategory Entered</td>
        <td colspan="3" class="bdr1B"><?php echo $row_log['brewSubCategory']; ?></td>
      </tr>
      <tr>
        <td nowrap="nowrap">Homebrew Club</td>
        <td colspan="3" class="bdr1B"><?php echo $row_brewer['brewerClubs']; ?></td>
      </tr>
      <tr>
        <td colspan="4"><div align="center"><img src="../images/bottleID_attach.jpg" alt="Attach To Each Bottle" width="230" height="20" /></div></td>
      </tr>
    </table>
    </td>
  </tr>
</table>
<?php if ($action != "print") { ?><p align="right"><img src = "../images/arrow_up.png" align="absmiddle" /><a href="#labels_top">Top</a>&nbsp;&nbsp;&nbsp;<img src = "../images/printer.png" align="absmiddle" />&nbsp;<a href="?action=print&id=<?php echo $id; ?>&bid=<?php echo $bid; ?>">Print</a></p><?php } ?>

</div>
</body>
</html>