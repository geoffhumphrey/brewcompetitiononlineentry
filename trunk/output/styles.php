<?php 
require('output.bootstrap.php');

$query_styles = "SELECT * FROM styles";
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
<link href="../css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js_includes/jump_menu.js"></script>
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
  	<td class="data"><?php echo style_choose($section,"default","default",$filter); ?></td>
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
include ('../includes/style_convert.inc.php');
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
<table>
  <tr>
  	<td class="dataLabel">OG Range</td>
    <td class="dataLabel data">FG Range</td>
    <td class="dataLabel data">ABV Range</td>
    <td class="dataLabel data">Bitterness Range</td>
    <td class="dataLabel data">Color Range</td>
  </tr>
  <tr>
  	<td class="data-left">
  	<?php 
						  if ($row_styles['brewStyleOG'] == "") { echo "Varies"; }
						  elseif ($row_styles['brewStyleOG'] != "") { echo $row_styles['brewStyleOG']." &ndash; ".$row_styles['brewStyleOGMax']; }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data">
	<?php 
						  if ($row_styles['brewStyleFG'] == "") { echo "Varies"; }
						  elseif ($row_styles['brewStyleFG'] != "") { echo $row_styles['brewStyleFG']." &ndash; ".$row_styles['brewStyleFGMax']; }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data">
	<?php 
						  if ($row_styles['brewStyleABV'] == "") { echo "Varies"; }
						  elseif ($row_styles['brewStyleABV'] != "" ) { echo $row_styles['brewStyleABV']."% &ndash; ".$row_styles['brewStyleABVMax']."%"; } 
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data">
	<?php 
						  if ($row_styles['brewStyleIBU'] == "")  { echo "Varies"; }
						  elseif ($row_styles['brewStyleIBU'] == "N/A") { echo "N/A"; }
						  elseif ($row_styles['brewStyleIBU'] != "") { $IBUmin = ltrim ($row_styles['brewStyleIBU'], "0"); $IBUmax = ltrim ($row_styles['brewStyleIBUMax'], "0"); echo $IBUmin." &ndash; ".$IBUmax." IBU"; }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data">
	<?php
						  if (($page == "reference") || ($page == "brewBlogCurrent") || ($page == "brewBlogDetail") || ($page == "recipeDetail")  || ($page == "recipeList")  || ($page == "brewBlogList") || ($page == "awardsList")) { include ('includes/colorStyle.inc.php'); } else { include ('../includes/colorStyle.inc.php'); }
						  if ($row_styles['brewStyleSRM'] == "") { echo "Varies"; }
						  elseif ($row_styles['brewStyleSRM'] == "N/A") { echo "N/A"; }
						  elseif ($row_styles['brewStyleSRM'] != "") 
						  	{ 
						  	$SRMmin = ltrim ($row_styles['brewStyleSRM'], "0"); 
						  	$SRMmax = ltrim ($row_styles['brewStyleSRMMax'], "0"); 
							if ($SRMmin > "15") $color = "#ffffff"; else $color = "#000000"; 
							if ($SRMmax > "15") $color2 = "#ffffff"; else $color2 = "#000000"; 
						  		echo "
								<table>
								<tr>
								<td class='colorTabletd' width='49%' style='background-color: ".$beercolorMin."; border: 1px solid #000000; color: ".$color."'>".$SRMmin."</td>		
								<td class='colorTabletd' width='2%'>&ndash;</td>
								<td class='colorTabletd' width='49%' style='background-color: ".$beercolorMax."; border: 1px solid #000000; color: ".$color2."'>".$SRMmax."</td>
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