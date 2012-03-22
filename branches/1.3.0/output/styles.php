<?php 
session_start(); 
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(DB.'common.db.php');
require(INCLUDES.'version.inc.php');
require(DB.'styles.db.php');
/*
$query_styles = "SELECT * FROM $styles_active";
if ($filter == "default") $query_styles .= " WHERE style_active='1' ORDER BY style_cat,style_subcat";
else $query_styles .= " WHERE style_active='1' AND style_cat='$filter' ORDER BY style_cat,style_subcat";
$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
$row_styles = mysql_fetch_assoc($styles);
$totalRows_styles = mysql_num_rows($styles);
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Styles</title>
<link href="../css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js_includes/smoothscroll.js"></script>
</head>
<body>
<div id="container">
<div id="content">
	<div id="content-inner-no-nav">
    	<div id="header">
        	<div id="header-inner"><h1>Accepted Styles: Main Style Set (<?php echo $row_styles_active['style_name']." ".$row_styles_active['style_year']; ?>)</h1></div>
        </div>
<?php if ($totalRows_styles_custom > 0) { ?>
<p><a href="#custom">Skip to Custom Styles</a></p>
<?php } ?>
<?php if ($totalRows_styles > 0) { ?>
<?php do { 
    if (($sort == "style_srm_min") 	&& (($row_styles['style_srm_min'] == "") || ($row_styles['style_srm_min'] == "N/A"))) echo ""; 
elseif (($sort == "style_ibu_min") 	&& (($row_styles['style_ibu_min'] == "") || ($row_styles['style_ibu_min'] == "N/A"))) echo "";
elseif (($sort == "style_og_min") 	&& ($row_styles['style_og_min'] == "")) echo "";
elseif (($sort == "style_fg_min") 	&& ($row_styles['style_fg_min'] == "")) echo "";
elseif (($sort == "style_abv_min") 	&& ($row_styles['style_abv_min'] == "")) echo "";
else { 
?>
<h2><?php echo $row_styles['style_name']; ?></h2>
<table>
  <tr>
  	 <td class="dataLabel" width="1%" nowrap="nowrap">Category:</td>
	 <td class="data" width="20%"><?php if ($styles_active == "styles_bjcp_2008") echo style_convert($row_styles['style_cat'],1); else echo $row_styles['style_cat']; ?></td>
	<td class="dataLabel"><?php if ($styles_active == "styles_bjcp_2008") echo "Number:"; else echo "Sub-Category:"; ?></td>
	<td class="data"><?php if ($styles_active == "styles_bjcp_2008") echo $row_styles['style_cat']." ".$row_styles['style_subcat']; else echo $row_styles['style_subcat']; ?></td>
  </tr>
</table>
<table>
  <tr>
  	<td class="data-left"><p><?php echo $row_styles['style_info']; ?></td>
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
						  if (($row_styles['style_og_min'] == "") || ($row_styles['style_og_min'] == "0")) { echo "Varies"; }
						  elseif ($row_styles['style_og_min'] != "") { echo number_format($row_styles['style_og_min'],3)." &ndash; ".number_format($row_styles['style_og_max'],3); }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data" nowrap="nowrap">
	<?php 
						  if (($row_styles['style_fg_min'] == "") || ($row_styles['style_fg_min'] == "0")) { echo "Varies"; }
						  elseif ($row_styles['style_fg_min'] != "") { echo number_format($row_styles['style_fg_min'],3)." &ndash; ".number_format($row_styles['style_fg_max'],3); }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data" nowrap="nowrap">
	<?php 
						  if (($row_styles['style_abv_min'] == "") || ($row_styles['style_abv_min'] == "0")) { echo "Varies"; }
						  elseif ($row_styles['style_abv_min'] != "" ) { echo $row_styles['style_abv_min']."% &ndash; ".$row_styles['style_abv_max']."%"; } 
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data" nowrap="nowrap">
	<?php 
						  if (($row_styles['style_ibu_min'] == "") || ($row_styles['style_ibu_min'] == "0"))  { echo "Varies"; }
						  elseif ($row_styles['style_ibu_min'] == "N/A") { echo "N/A"; }
						  elseif ($row_styles['style_ibu_min'] != "") { echo ltrim($row_styles['style_ibu_min'], "0")." &ndash; ".ltrim($row_styles['style_ibu_max'], "0")." IBU"; }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data">
	<?php
						  if (($row_styles['style_srm_min'] == "") || ($row_styles['style_srm_min'] == "0")) { echo "Varies"; }
						  elseif ($row_styles['style_srm_min'] == "N/A") { echo "N/A"; }
						  elseif ($row_styles['style_srm_min'] != "") 
						  	{ 
						  	$SRMmin = ltrim ($row_styles['style_srm_min'], "0"); 
						  	$SRMmax = ltrim ($row_styles['style_srm_max'], "0"); 
							if ($SRMmin >= "15") $color1 = "#ffffff"; else $color1 = "#000000"; 
							if ($SRMmax >= "15") $color2 = "#ffffff"; else $color2 = "#000000"; 
						  		echo "
								<table width='100%'>
								<tr>
								<td width='50%' style='text-align: center; background-color: ".srm_color($SRMmin,"srm")."; border: 1px solid #000000; color: ".$color1."'>".$SRMmin."</td><td width='50%' style='text-align: center; background-color: ".srm_color($SRMmax,"srm")."; border: 1px solid #000000; color: ".$color2."'>".$SRMmax."</td>
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
    		<td class="data-left"><?php if ($row_styles['style_link'] != "") { ?><a href="<?php echo $row_styles['style_link']; ?>" target="_blank">More Info</a><?php } else echo "&nbsp;"; ?></td>
  		</tr>
  	</table>
<?php } 
 } while ($row_styles = mysql_fetch_assoc($styles)); ?>
<?php } else echo "<p>Styles in this category are not accepted in this competition.</p>"; ?>
<?php if ($totalRows_styles_custom > 0) { ?>
		<div id="header">
        	<div id="header-inner"><a name="custom"></a><h1>Accepted Styles: Custom</h1></div>
        </div>
<?php do { ?>
<h2><?php echo $row_styles_custom['style_name']; ?></h2>
<table>
  <tr>
  	 <td class="dataLabel" width="1%" nowrap="nowrap">Category:</td>
	 <td class="data" width="20%"><?php echo $row_styles_custom['style_cat']; ?></td>
	<td class="dataLabel">Sub-Category:</td>
	<td class="data"><?php echo $row_styles_custom['style_subcat']; ?></td>
  </tr>
</table>
<table>
  <tr>
  	<td class="data-left"><p><?php echo $row_styles_custom['style_info']; ?></td>
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
						  if (($row_styles_custom['style_og_min'] == "") || ($row_styles_custom['style_og_min'] == "0")) { echo "Varies"; }
						  elseif ($row_styles_custom['style_og_min'] != "") { echo $row_styles_custom['style_og_min']." &ndash; ".$row_styles_custom['style_og_max']; }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data" nowrap="nowrap">
	<?php 
						  if (($row_styles_custom['style_fg_min'] == "") || ($row_styles_custom['style_fg_min'] == "0")) { echo "Varies"; }
						  elseif ($row_styles_custom['style_fg_min'] != "") { echo $row_styles_custom['style_fg_min']." &ndash; ".$row_styles_custom['style_fg_max']; }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data" nowrap="nowrap">
	<?php 
						  if (($row_styles_custom['style_abv_min'] == "") || ($row_styles_custom['style_abv_min'] == "0")) { echo "Varies"; }
						  elseif ($row_styles_custom['style_abv_min'] != "" ) { echo $row_styles_custom['style_abv_min']."% &ndash; ".$row_styles_custom['style_abv_max']."%"; } 
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data" nowrap="nowrap">
	<?php 
						  if (($row_styles_custom['style_ibu_min'] == "") || ($row_styles_custom['style_ibu_min'] == "0"))  { echo "Varies"; }
						  elseif ($row_styles_custom['style_ibu_min'] == "N/A") { echo "N/A"; }
						  elseif ($row_styles_custom['style_ibu_min'] != "") { echo ltrim($row_styles_custom['style_ibu_min'], "0")." &ndash; ".ltrim($row_styles_custom['style_ibu_max'], "0")." IBU"; }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data">
	<?php
						  if (($row_styles_custom['style_srm_min'] == "") || ($row_styles_custom['style_srm_min'] == "0")) { echo "Varies"; }
						  elseif ($row_styles_custom['style_srm_min'] == "N/A") { echo "N/A"; }
						  elseif ($row_styles_custom['style_srm_min'] != "") 
						  	{ 
						  	$SRMmin = ltrim ($row_styles_custom['style_srm_min'], "0"); 
						  	$SRMmax = ltrim ($row_styles_custom['style_srm_max'], "0"); 
							if ($SRMmin >= "15") $color1 = "#ffffff"; else $color1 = "#000000"; 
							if ($SRMmax >= "15") $color2 = "#ffffff"; else $color2 = "#000000"; 
						  		echo "
								<table width='100%'>
								<tr>
								<td width='50%' style='text-align: center; background-color: ".srm_color($SRMmin,"srm")."; border: 1px solid #000000; color: ".$color1."'>".$SRMmin."</td><td width='50%' style='text-align: center; background-color: ".srm_color($SRMmax,"srm")."; border: 1px solid #000000; color: ".$color2."'>".$SRMmax."</td>
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
    		<td class="data-left"><?php if ($row_styles_custom['style_link'] != "") { ?><a href="<?php echo $row_styles_custom['style_link']; ?>" target="_blank">More Info</a><?php } else echo "&nbsp;"; ?></td>
  		</tr>
  	</table>
<?php } while ($row_styles_custom = mysql_fetch_assoc($styles_custom)); ?>
<?php } ?>    
	</div>
</div>
</div>
</body>
</html>