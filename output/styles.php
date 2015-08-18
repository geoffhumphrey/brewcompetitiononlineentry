<?php 
session_start(); 
require('../paths.php'); 
require(CONFIG.'bootstrap.php');
if (NHC) $base_url = "../";
$section = "output_styles";
include(DB.'styles.db.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Styles</title>
<link href="<?php echo $base_url; ?>css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/smoothscroll.js"></script>

<style type="text/css">
<!--
/* Links */
a:link {
	color: #00F;
}

a:visited {
	color: #00F;
}

a:hover, a:focus {
	color: #900;
	text-decoration: underline;
}

a:active {
	color: #060;
}
-->
</style>
</head>
<body style="font-size: 12px">
<div id="container">
<div id="content">
	<div id="content-inner-no-nav">
    
    	<div id="header">
        	<div id="header-inner"><a name="top"></a>
            <?php if ($go == "default") { ?>
            <h1>Accepted <?php echo str_replace("2"," 2",$row_styles['brewStyleVersion']); ?> Styles</h1>
            <?php } else { ?>
            <h1><?php echo str_replace("2"," 2",$row_styles['brewStyleVersion']); ?> Style Information</h1>
            <?php } ?>
            </div>
        </div>
<?php if ($totalRows_styles > 0) { ?>
<?php do { 
$replacement1 = array('Entry Instructions:','Commercial Examples:','must specify','may specify','MUST specify','MAY specify','must provide');
if ($go == "default") $replacement2 = array('<br><br><strong>Entry Instructions:</strong>','<br><br><strong>Commercial Examples:</strong>','<span style="text-decoration:underline;">MUST</span> specify','<span style="text-decoration:underline;">MAY</span> specify','<span style="text-decoration:underline;">MUST</span> specify','<span style="text-decoration:underline;">MAY</span> specify','<span style="text-decoration:underline;">MUST</span> provide');
else $replacement2 = array('<br><br><span class="required"></span><strong class="yellow" style="padding: 2px">Entry Instructions:</strong>','<br><br><strong>Commercial Examples:</strong>','<span style="text-decoration:underline;">MUST</span> specify','<span style="text-decoration:underline;">MAY</span> specify','<span style="text-decoration:underline;">MUST</span> specify','<span style="text-decoration:underline;">MAY</span> specify','<span style="text-decoration:underline;">MUST</span> provide');
$info = str_replace($replacement1,$replacement2,$row_styles['brewStyleInfo']);

    if (($sort == "brewStyleSRM") 	&& (($row_styles['brewStyleSRM'] == "") || ($row_styles['brewStyleSRM'] == "N/A"))) echo ""; 
elseif (($sort == "brewStyleIBU") 	&& (($row_styles['brewStyleIBU'] == "") || ($row_styles['brewStyleIBU'] == "N/A"))) echo "";
elseif (($sort == "brewStyleOG") 	&& ($row_styles['brewStyleOG'] == "")) echo "";
elseif (($sort == "brewStyleFG") 	&& ($row_styles['brewStyleFG'] == "")) echo "";
elseif (($sort == "brewStyleABV") 	&& ($row_styles['brewStyleABV'] == "")) echo "";
else { 
?>
<div class="bdr1B_gray" style="padding-bottom: 40px">
<?php if ($row_styles['brewStyleNum'] == "A") { ?><a name="<?php if ($row_styles['brewStyleGroup'] < 10) echo ltrim($row_styles['brewStyleGroup'],"0"); else echo $row_styles['brewStyleGroup']; ?>"></a><?php } ?>
<a name="<?php if ($row_styles['brewStyleGroup'] < 10) echo ltrim($row_styles['brewStyleGroup'],"0"); else echo $row_styles['brewStyleGroup']; echo $row_styles['brewStyleNum']; ?>"></a>
<h2><?php echo $row_styles['brewStyle']; ?></h2>

<table>
  <tr>
  	 <td class="dataLabel">Category:</td>
	 <td class="data"><?php echo style_convert($row_styles['brewStyleGroup'],1);?>
	 </td>
  </tr>
  </tr>
	<td class="dataLabel">Number:</td>
	<td class="data"><?php echo $row_styles['brewStyleGroup']; ?><?php echo $row_styles['brewStyleNum']; ?></td>
  </tr>
</table>
<table>
  <tr>
  	<td class="data-left"><p style="padding-bottom: 15px;"><?php echo $info; ?></p></td>
  </tr>
</table>
<table class="dataTable">
  <tr>
  	<th class="dataLabel data bdr1B">OG Range</th>
    <th class="dataLabel data bdr1B">FG Range</th>
    <th class="dataLabel data bdr1B">ABV Range</th>
    <th class="dataLabel data bdr1B">Bitterness Range</th>
    <th class="dataLabel data bdr1B">Color Range</th>
  </tr>
  <tr>
  	<td class="data-left" nowrap="nowrap" style="background-color:#ddd;">
  	<?php 
						  if ($row_styles['brewStyleOG'] == "") { echo "Varies"; }
						  elseif ($row_styles['brewStyleOG'] != "") { echo $row_styles['brewStyleOG']." &ndash; ".$row_styles['brewStyleOGMax']; }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data" nowrap="nowrap" style="background-color:#ddd;">
	<?php 
						  if ($row_styles['brewStyleFG'] == "") { echo "Varies"; }
						  elseif ($row_styles['brewStyleFG'] != "") { echo $row_styles['brewStyleFG']." &ndash; ".$row_styles['brewStyleFGMax']; }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data" nowrap="nowrap" style="background-color:#ddd;">
	<?php 
						  if ($row_styles['brewStyleABV'] == "") { echo "Varies"; }
						  elseif ($row_styles['brewStyleABV'] != "" ) { echo $row_styles['brewStyleABV']."% &ndash; ".$row_styles['brewStyleABVMax']."%"; } 
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data" nowrap="nowrap" style="background-color:#ddd;">
	<?php 
						  if ($row_styles['brewStyleIBU'] == "")  { echo "Varies"; }
						  elseif ($row_styles['brewStyleIBU'] == "N/A") { echo "N/A"; }
						  elseif ($row_styles['brewStyleIBU'] != "") { echo ltrim($row_styles['brewStyleIBU'], "0")." &ndash; ".ltrim($row_styles['brewStyleIBUMax'], "0")." IBU"; }
						  else { echo "&nbsp;"; }
						  ?>    </td>
    <td class="data" style="background-color:#ddd;">
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
								<table width='75%'>
								<tr>
								<td width='50%' style='text-align: center; background-color: ".srm_color($SRMmin,"srm")."; border: 1px solid #000000; color: ".$color1."'>".$SRMmin."</td><td style='text-align: center; background-color: ".srm_color($SRMmax,"srm")."; border: 1px solid #000000; color: ".$color2."'>".$SRMmax."</td>
								</tr>
								</table>
								"; 
							} 
						   else { echo "&nbsp;"; }
						  ?>
    </td>
   </tr>
  </table>
<p style="padding-top: 20px;"><?php if ($row_styles['brewStyleLink'] != "") { ?><a href="<?php echo $row_styles['brewStyleLink']; ?>" target="_blank">More Info</a> (link to Beer Judge Certification Program Style Guidelines)<?php } else echo "&nbsp;"; ?></p>
</div>
<?php if (($go == "default") && ($view == "default")) { ?>
<p style="padding-top: 10px;"><a href="#top">Top of Page</a></p>
<?php }
	} 
 } while ($row_styles = mysql_fetch_assoc($styles)); ?>
<?php } else echo "<p>Styles in this category are not accepted in this competition.</p>"; ?>
	</div>
</div>
</div>
</body>
</html>