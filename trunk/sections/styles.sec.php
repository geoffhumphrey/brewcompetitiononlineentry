<?php 
$section = "styles";
require ('../Connections/config.php');
require ('../includes/url_variables.inc.php');
include ('../includes/plug-ins.inc.php');
mysql_select_db($database, $brewing);
if ($filter != "default") $query_styles = "SELECT * FROM styles WHERE brewStyleActive='Y' AND brewStyleGroup='$filter' ORDER BY brewStyleGroup,brewStyleNum";

else $query_styles = "SELECT * FROM styles WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
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
<link href="../css/thickbox.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="../js_includes/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../js_includes/thickbox.js"></script>
<script type="text/javascript">
<!--
function jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
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
  	<td class="data">
  	<form name="form2" method="post" action="">
          	<select name="styleMenu" onChange="jumpMenu('self',this,0)" class="left">
		  	<option value="styles.sec.php">All</option> 
            <option value="styles.sec.php?filter=0" <?php if ($filter == "0") echo "SELECTED"; ?>>Unique to this Competition</option>
            <option value="styles.sec.php?filter=01" <?php if ($filter == "01") echo "SELECTED"; ?>>Light Lager</option>
			<option value="styles.sec.php?filter=02" <?php if ($filter == "02") echo "SELECTED"; ?>>Pilsner</option>
            <option value="styles.sec.php?filter=03" <?php if ($filter == "03") echo "SELECTED"; ?>>European Amber Lager</option>
			<option value="styles.sec.php?filter=04" <?php if ($filter == "04") echo "SELECTED"; ?>>Dark Lager</option>
            <option value="styles.sec.php?filter=05" <?php if ($filter == "05") echo "SELECTED"; ?>>Bock</option>
            <option value="styles.sec.php?filter=06" <?php if ($filter == "06") echo "SELECTED"; ?>>Light Hybrid Beer</option>
            <option value="styles.sec.php?filter=07" <?php if ($filter == "07") echo "SELECTED"; ?>>Amber Hybrid Beer</option>
            <option value="styles.sec.php?filter=08" <?php if ($filter == "08") echo "SELECTED"; ?>>English Pale Ale</option>
			<option value="styles.sec.php?filter=09" <?php if ($filter == "09") echo "SELECTED"; ?>>Scottish and Irish Ale</option>
          	<option value="styles.sec.php?filter=10" <?php if ($filter == "10") echo "SELECTED"; ?>>American Ale</option>
			<option value="styles.sec.php?filter=11" <?php if ($filter == "11") echo "SELECTED"; ?>>English Brown Ale</option>
            <option value="styles.sec.php?filter=12" <?php if ($filter == "12") echo "SELECTED"; ?>>Porter</option>
			<option value="styles.sec.php?filter=13" <?php if ($filter == "13") echo "SELECTED"; ?>>Stout</option>
            <option value="styles.sec.php?filter=14" <?php if ($filter == "14") echo "SELECTED"; ?>>India Pale Ale (IPA)</option>
            <option value="styles.sec.php?filter=15" <?php if ($filter == "15") echo "SELECTED"; ?>>German Wheat and Rye Beer</option>
            <option value="styles.sec.php?filter=16" <?php if ($filter == "16") echo "SELECTED"; ?>>Belgian and French Ale</option>
            <option value="styles.sec.php?filter=17" <?php if ($filter == "17") echo "SELECTED"; ?>>Sour Ale</option>
			<option value="styles.sec.php?filter=18" <?php if ($filter == "18") echo "SELECTED"; ?>>Belgian Strong Ale</option>
			<option value="styles.sec.php?filter=19" <?php if ($filter == "19") echo "SELECTED"; ?>>Strong Ale</option>
          	<option value="styles.sec.php?filter=20" <?php if ($filter == "20") echo "SELECTED"; ?>>Fruit Beer</option>
			<option value="styles.sec.php?filter=21" <?php if ($filter == "21") echo "SELECTED"; ?>>Spice/Herb/Vegetable Beer</option>
            <option value="styles.sec.php?filter=22" <?php if ($filter == "22") echo "SELECTED"; ?>>Smoke-Flavored and Wood-Aged Beer</option>
			<option value="styles.sec.php?filter=23" <?php if ($filter == "23") echo "SELECTED"; ?>>Specialty Beer</option>
            <option value="styles.sec.php?filter=24" <?php if ($filter == "24") echo "SELECTED"; ?>>Traditional Mead</option>
            <option value="styles.sec.php?filter=25" <?php if ($filter == "25") echo "SELECTED"; ?>>Melomel (Fruit Mead)</option>
            <option value="styles.sec.php?filter=26" <?php if ($filter == "26") echo "SELECTED"; ?>>Other Mead</option>
            <option value="styles.sec.php?filter=27" <?php if ($filter == "27") echo "SELECTED"; ?>>Standard Cider and Perry</option>
			<option value="styles.sec.php?filter=28" <?php if ($filter == "28") echo "SELECTED"; ?>>Specialty Cider and Perry</option>
			</select>
		</form>
	  </td>
    </tr>
  </table>
<?php if ($totalRows_styles > 0) { ?>
<?php do { 
    if (($sort == "brewStyleSRM") 	&& (($row_styles['brewStyleSRM'] == "") || ($row_styles['brewStyleSRM'] == "N/A"))) echo ""; 
elseif (($sort == "brewStyleIBU") 	&& (($row_styles['brewStyleIBU'] == "") || ($row_styles['brewStyleIBU'] == "N/A"))) echo "";
elseif (($sort == "brewStyleOG") 	&& ($row_styles['brewStyleOG'] == "")) echo "";
elseif (($sort == "brewStyleFG") 	&& ($row_styles['brewStyleFG'] == "")) echo "";
elseif (($sort == "brewStyleABV") 	&& ($row_styles['brewStyleABV'] == "")) echo "";
else { ?>
<h2><?php echo $row_styles['brewStyle']; ?></h2>
<table>
  <tr>
  	 <td class="dataLabel">Category:</td>
	 <td class="data">
	 <?php
						if ($row_styles['brewStyleGroup'] == "0") echo "Unique to this Competition";
						if ($row_styles['brewStyleGroup'] == "01") echo "Light Lager";
						if ($row_styles['brewStyleGroup'] == "02") echo "Pilsner";
						if ($row_styles['brewStyleGroup'] == "03") echo "European Amber Lager";
						if ($row_styles['brewStyleGroup'] == "04") echo "Dark Lager";
						if ($row_styles['brewStyleGroup'] == "05") echo "Bock";
						if ($row_styles['brewStyleGroup'] == "06") echo "Light Hybrid Beer";
						if ($row_styles['brewStyleGroup'] == "07") echo "Amber Hybrid Beer";
						if ($row_styles['brewStyleGroup'] == "08") echo "English Pale Ale";
						if ($row_styles['brewStyleGroup'] == "09") echo "Scottish and Irish Ale";
						if ($row_styles['brewStyleGroup'] == "10") echo "American Ale";
						if ($row_styles['brewStyleGroup'] == "11") echo "English Brown Ale";
						if ($row_styles['brewStyleGroup'] == "12") echo "Porter";
						if ($row_styles['brewStyleGroup'] == "13") echo "Stout";
						if ($row_styles['brewStyleGroup'] == "14") echo "India Pale Ale (IPA)";
						if ($row_styles['brewStyleGroup'] == "15") echo "German Wheat and Rye Beer";
						if ($row_styles['brewStyleGroup'] == "16") echo "Belgian and French Ale";
						if ($row_styles['brewStyleGroup'] == "17") echo "Sour Ale";
						if ($row_styles['brewStyleGroup'] == "18") echo "Belgian Strong Ale";
						if ($row_styles['brewStyleGroup'] == "19") echo "Strong Ale";
						if ($row_styles['brewStyleGroup'] == "20") echo "Fruit Beer";
						if ($row_styles['brewStyleGroup'] == "21") echo "Spice/Herb/Vegetable Beer";
						if ($row_styles['brewStyleGroup'] == "22") echo "Smoke-Flavored and Wood-Aged Beer";
						if ($row_styles['brewStyleGroup'] == "23") echo "Specialty Beer";
						if ($row_styles['brewStyleGroup'] == "24") echo "Traditional Mead";
						if ($row_styles['brewStyleGroup'] == "25") echo "Melomel (Fruit Mead)";
						if ($row_styles['brewStyleGroup'] == "26") echo "Other Mead";
						if ($row_styles['brewStyleGroup'] == "27") echo "Standard Cider and Perry";
						if ($row_styles['brewStyleGroup'] == "28") echo "Specialty Cider and Perry";
						?>
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