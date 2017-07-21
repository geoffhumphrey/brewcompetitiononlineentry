<?php 
$section = "styles";
include (DB.'styles.db.php');
?>
    <div class="page-header"><a name="top"></a>
    <?php if ($go == "default") { ?>
    <h1>Accepted <?php echo str_replace("2"," 2",$row_styles['brewStyleVersion']); ?> Styles</h1>
    <?php } else { ?>
    <h1><?php echo str_replace("2"," 2",$row_styles['brewStyleVersion']); ?> Style Information</h1>
    <?php } ?>
    </div>
<?php if ($totalRows_styles > 0) { ?>
<?php do { 
$replacement1 = array('$label_entry_instructions:','$label_commerical_examples:','must specify','may specify','MUST specify','MAY specify','must provide');
if ($go == "default") $replacement2 = array('<p><strong class="text-danger">$label_entry_instructions:</strong>','<p><strong class="text-info">$label_commercial_examples:</strong>','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> provide');
else $replacement2 = array('<p><strong class="text-danger">$label_entry_instructions:</strong>','<p><strong class="text-info">$label_commercial_examples:</strong>','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> provide');
$info = str_replace($replacement1,$replacement2,$row_styles['brewStyleInfo']);

$comEx = sprintf("<strong class=\"text-info\">%s:</strong> %s",$label_commercial_examples,$row_styles['brewStyleComEx']);
$entryReq = sprintf("<strong class=\"text-danger\">%s:</strong> %s",$label_entry_instructions, $row_styles['brewStyleEntry']);

    if (($sort == "brewStyleSRM") 	&& (($row_styles['brewStyleSRM'] == "") || ($row_styles['brewStyleSRM'] == "N/A"))) echo ""; 
elseif (($sort == "brewStyleIBU") 	&& (($row_styles['brewStyleIBU'] == "") || ($row_styles['brewStyleIBU'] == "N/A"))) echo "";
elseif (($sort == "brewStyleOG") 	&& ($row_styles['brewStyleOG'] == "")) echo "";
elseif (($sort == "brewStyleFG") 	&& ($row_styles['brewStyleFG'] == "")) echo "";
elseif (($sort == "brewStyleABV") 	&& ($row_styles['brewStyleABV'] == "")) echo "";
else { 
?>
<?php if ($row_styles['brewStyleNum'] == "A") { ?><a name="<?php if ($row_styles['brewStyleGroup'] < 10) echo ltrim($row_styles['brewStyleGroup'],"0"); else echo $row_styles['brewStyleGroup']; ?>"></a><?php } ?>
<a name="<?php if ($row_styles['brewStyleGroup'] < 10) echo ltrim($row_styles['brewStyleGroup'],"0"); else echo $row_styles['brewStyleGroup']; echo $row_styles['brewStyleNum']; ?>"></a>
<h2><?php echo $row_styles['brewStyle']; ?></h2>
<ul class="list-inline">
    <li><?php echo sprintf("<strong>%s:</strong>",$label_category); ?></li>
    <li><?php echo style_convert($row_styles['brewStyleGroup'],1);?></li>
</ul>
<ul class="list-inline">
	<li><?php echo sprintf("<strong>%s:</strong>",$label_number); ?></li>
    <li><?php echo $row_styles['brewStyleGroup']; ?><?php echo $row_styles['brewStyleNum']; ?></li>
</ul>
<?php 
echo "<p>".$info."</p>"; 
if (!empty($row_styles['brewStyleComEx'])) echo "<p>".$comEx."</p>";
if (!empty($row_styles['brewStyleEntry'])) echo "<p>".$entryReq."</p>"; 
?>
<?php //echo "<p>".$row_styles['brewStyleInfo']."</p>"; ?>
<table class="table table-bordered table-striped">
    <tr>
        <th class="dataLabel data bdr1B">OG</th>
        <th class="dataLabel data bdr1B">FG</th>
        <th class="dataLabel data bdr1B">ABV</th>
        <th class="dataLabel data bdr1B"><?php echo $label_bitterness; ?></th>
        <th class="dataLabel data bdr1B"><?php echo $label_color; ?></th>
    </tr>
    <tr>
        <td nowrap>
        <?php 
			if ($row_styles['brewStyleOG'] == "") { echo "Varies"; }
			elseif ($row_styles['brewStyleOG'] != "") { echo number_format((float)$row_styles['brewStyleOG'], 3, '.', '')." &ndash; ".number_format((float)$row_styles['brewStyleOGMax'], 3, '.', ''); }
			else { echo "&nbsp;"; }
		?>
		</td>
        <td nowrap>
        <?php 
			if ($row_styles['brewStyleFG'] == "") { echo "Varies"; }
			elseif ($row_styles['brewStyleFG'] != "") { echo number_format((float)$row_styles['brewStyleFG'], 3, '.', '')." &ndash; ".number_format((float)$row_styles['brewStyleFGMax'], 3, '.', ''); }
			else { echo "&nbsp;"; }
		?>
        </td>
        <td nowrap>
        <?php 
			if ($row_styles['brewStyleABV'] == "") { echo "Varies"; }
			elseif ($row_styles['brewStyleABV'] != "" ) { echo number_format((float)$row_styles['brewStyleABV'], 1, '.', '')."% &ndash; ".number_format((float)$row_styles['brewStyleABVMax'], 1, '.', '')."%"; } 
			else { echo "&nbsp;"; }
		?>
        </td>
        <td nowrap>
        <?php 
			  if ($row_styles['brewStyleIBU'] == "")  { echo "Varies"; }
			  elseif ($row_styles['brewStyleIBU'] == "N/A") { echo "N/A"; }
			  elseif ($row_styles['brewStyleIBU'] != "") { echo ltrim($row_styles['brewStyleIBU'], "0")." &ndash; ".ltrim($row_styles['brewStyleIBUMax'], "0")." IBU"; }
			  else { echo "&nbsp;"; }
		?>    
        </td>
        <td>
        <?php
			if ($row_styles['brewStyleSRM'] == "") { echo "Varies"; }
			elseif ($row_styles['brewStyleSRM'] == "N/A") { echo "N/A"; }
			elseif ($row_styles['brewStyleSRM'] != "") { 
				$SRMmin = ltrim ($row_styles['brewStyleSRM'], "0"); 
				$SRMmax = ltrim ($row_styles['brewStyleSRMMax'], "0"); 
				if ($SRMmin >= "15") $color1 = "#ffffff"; else $color1 = "#000000"; 
				if ($SRMmax >= "15") $color2 = "#ffffff"; else $color2 = "#000000";
				
				$styleColor = "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmin,"srm")."; color: ".$color1."\">&nbsp;".$SRMmin."&nbsp;</span>";
				$styleColor .= " &ndash; ";
				$styleColor .= "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmax,"srm")."; color: ".$color2."\">&nbsp;".$SRMmax."&nbsp;</span> <small class=\"text-muted\"><em>SRM</em></small>";
				
				echo $styleColor;
				 
			} 
			else { echo "&nbsp;"; }
		?>
        </td>
    </tr>
</table>
<p class="hidden-print"><?php if ($row_styles['brewStyleLink'] != "") { ?><a href="<?php echo $row_styles['brewStyleLink']; ?>" target="_blank"><?php echo $label_more_info; ?></a><?php echo " - ".$output_text_027; } else echo "&nbsp;"; ?></p>
<?php if (($go == "default") && ($view == "default")) { ?>
<?php }
	} 
 } while ($row_styles = mysqli_fetch_assoc($styles)); ?>
<?php } else echo spritf("<p>%s</p>",$output_text_026); ?>
