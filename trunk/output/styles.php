<?php 
session_start(); 
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
require(INCLUDES.'version.inc.php');

$query_styles = "SELECT * FROM $styles_db_table";
if ($filter == "default") $query_styles .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
else $query_styles .= " WHERE brewStyleActive='Y' AND brewStyleGroup='$filter' ORDER BY brewStyleGroup,brewStyleNum";
$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
$row_styles = mysql_fetch_assoc($styles);
$totalRows_styles = mysql_num_rows($styles);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Styles</title>
<link href="<?php echo $base_url; ?>/css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jump_menu.js"></script>
</head>
<body>
<div id="container">
<div id="content">
	<div id="content-inner-no-nav">
    	<div id="header">
        	<div id="header-inner"><h1>Accepted Styles</h1></div>
        </div>
    <table>
	<tr>
  	<td class="dataLabel">Filter By Style Name</td>
  	<td class="data"><?php echo style_choose($section,"default","default",$filter,$view,"index.php","none"); ?></td>
    </tr>
  </table>
<?php if ($totalRows_styles > 0) { ?>
<?php do { 
    if (($sort == "brewStyleSRM") 	&& (($row_styles['brewStyleSRM'] == "") || ($row_styles['brewStyleSRM'] == "N/A"))) echo ""; 
elseif (($sort == "brewStyleIBU") 	&& (($row_styles['brewStyleIBU'] == "") || ($row_styles['brewStyleIBU'] == "N/A"))) echo "";
elseif (($sort == "brewStyleOG") 	&& ($row_styles['brewStyleOG'] == "")) echo "";
elseif (($sort == "brewStyleFG") 	&& ($row_styles['brewStyleFG'] == "")) echo "";
elseif (($sort == "brewStyleABV") 	&& ($row_styles['brewStyleABV'] == "")) echo "";
else { 
?>
<h2><?php echo $row_styles['brewStyle']; ?></h2>
<table>
  <tr>
  	 <td class="dataLabel">Category:</td>
	 <td class="data"><?php echo style_convert($row_styles['brewStyleGroup'],1)?>
	 </td>
  </tr>
  </tr>
	<td class="dataLabel">Number:</td>
	<td class="data"><?php echo $row_styles['brewStyleGroup']; ?><?php echo $row_styles['brewStyleNum']; ?></td>
  </tr>
</table>
<table>
  <tr>
  	<td class="data-left"><p><?php echo $row_styles['brewStyleInfo']; ?></td>
  </tr>
</table>
<table class="dataTable">
  <tr>
  	<th class="dataLabel">OG Range</th>
    <th class="dataLabel data">FG Range</th>
    <th class="dataLabel data">ABV Range</th>
    <th class="dataLabel data">Bitterness Range</th>
    <th class="dataLabel data">Color Range</th>
  </tr>
  <tr>
  	<td class="data-left" nowrap="nowrap">
  	<?php 
						  if ($row_styles['brewStyleOG'] == "") { echo "Varies"; }
						  elseif ($row_styles['brewStyleOG'] != "") { echo $row_styles['brewStyleOG']." &ndash; ".$row_styles['brewStyleOGMax']; }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data" nowrap="nowrap">
	<?php 
						  if ($row_styles['brewStyleFG'] == "") { echo "Varies"; }
						  elseif ($row_styles['brewStyleFG'] != "") { echo $row_styles['brewStyleFG']." &ndash; ".$row_styles['brewStyleFGMax']; }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data" nowrap="nowrap">
	<?php 
						  if ($row_styles['brewStyleABV'] == "") { echo "Varies"; }
						  elseif ($row_styles['brewStyleABV'] != "" ) { echo $row_styles['brewStyleABV']."% &ndash; ".$row_styles['brewStyleABVMax']."%"; } 
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data" nowrap="nowrap">
	<?php 
						  if ($row_styles['brewStyleIBU'] == "")  { echo "Varies"; }
						  elseif ($row_styles['brewStyleIBU'] == "N/A") { echo "N/A"; }
						  elseif ($row_styles['brewStyleIBU'] != "") { echo ltrim($row_styles['brewStyleIBU'], "0")." &ndash; ".ltrim($row_styles['brewStyleIBUMax'], "0")." IBU"; }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data">
	<?php
						  if ($row_styles['brewStyleSRM'] == "") { echo "Varies"; }
						  elseif ($row_styles['brewStyleSRM'] == "N/A") { echo "N/A"; }
						  elseif ($row_styles['brewStyleSRM'] != "") 
						  	{ 
						  	$SRMmin = ltrim ($row_styles['brewStyleSRM'], "0"); 
						  	$SRMmax = ltrim ($row_styles['brewStyleSRMMax'], "0"); 
							if ($SRMmin >= "15") $color1 = "#ffffff"; else $color1 = "#000000"; 
							if ($SRMmax >= "15") $color2 = "#ffffff"; else $color2 = "#000000"; 
						  		echo "
								<table width='100%'>
								<tr>
								<td style='text-align: center; background-color: ".srm_color($SRMmin,"srm")."; border: 1px solid #000000; color: ".$color1."'>".$SRMmin."</td><td style='text-align: center; background-color: ".srm_color($SRMmax,"srm")."; border: 1px solid #000000; color: ".$color2."'>".$SRMmax."</td>
								</tr>
								</table>
								"; 
							} 
						   else { echo "&nbsp;"; }
						  ?>
    </td>
   </tr>
  </table>
<table>
   <tr>
    <td class="data-left"><?php if ($row_styles['brewStyleLink'] != "") { ?><a href="<?php echo $row_styles['brewStyleLink']; ?>" target="_blank">More Info</a> (link to Beer Judge Certification Program Style Guidelines)<?php } else echo "&nbsp;"; ?></td>
   </tr>
  </table>
<?php } 
 } while ($row_styles = mysql_fetch_assoc($styles)); ?>
<?php } else echo "<p>Styles in this category are not accepted in this competition.</p>"; ?>
	</div>
</div>
</div>
</body>
</html>