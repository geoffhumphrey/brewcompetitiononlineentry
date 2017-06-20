<?php

if ($section == "brew") {
	$modals = "";
	$carb_str_sweet_special = "";
	$spec_carb_only = "";
	$carb_str_only = "";
}

$replacement1 = array('Entry Instructions:','Commercial Examples:','must specify','may specify','MUST specify','MAY specify','must provide','must be specified','must declare','must either','must supply','may provide','MUST state');
	if ($go == "default") $replacement2 = array('<strong class="text-danger">Entry Instructions:</strong>','<strong class="text-info">Commercial Examples:</strong>','<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify','<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify','<strong><u>MUST</u></strong> provide','<strong><u>MUST</u></strong> declare','<strong><u>MUST</u></strong> either','<strong><u>MUST</u></strong> supply','<strong><u>MAY</u></strong> provide','<strong><u>MUST</u></strong> state');
	else $replacement2 = array('<strong class="text-danger">Entry Instructions:</strong>','<strong class="text-info">Commercial Examples:</strong>','<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify','<strong><u>MUST</u></strong> specify','<u>MAY</u> specify','<u>MUST</u> provide','<strong><u>MUST</u></strong> be specified','<strong><u>MUST</u></strong> declare','<strong><u>MUST</u></strong> either','<strong><u>MUST</u></strong> supply','<strong><u>MAY</u></strong> provide','<strong><u>MUST</u></strong> state');
	$replacement3 = array('Entry Instructions:','Commercial Examples:','<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify (using the <em>Optional Info</em> field below)','<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify (using the <em>Optional Info</em> field below)','<strong><u>MUST</u></strong> provide','<strong><u>MUST</u></strong> be specified','<strong><u>MUST</u></strong> declare','<strong><u>MUST</u></strong> either','<strong><u>MUST</u></strong> supply','<strong><u>MAY</u></strong> provide (using the <em>Optional Info</em> field below)','<strong><u>MUST</u></strong> state');

// --------------------------------------If BJCP Styles --------------------------------------

if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
	$styleSet = str_replace("2"," 2",$_SESSION['prefsStyleSet']);
	
	if ($_SESSION['prefsStyleSet'] == "BJCP2008") {
		$beer_end = 23;
		$mead_array = array('24','25','26');
		$cider_array = array('27','28');
		$category_end = 28;
	}
	
	if ($_SESSION['prefsStyleSet'] == "BJCP2015") {
		$beer_end = 34;
		$mead_array = array('M1','M2','M3','M4');
		$cider_array = array('C1','C2');
		$category_end = 34;
	}
}

// Beer does not require mead/cider strength, carbonation or sweetness
// So, gather all beer styles that require special ingredients
	
if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) $query_spec_beer = sprintf("SELECT * FROM %s WHERE brewStyleOwn='custom' AND (brewStyleType='Beer' OR brewStyleType='1') AND brewStyleReqSpec='1' AND brewStyleCarb = '0' AND brewStyleStrength = '0' AND brewStyleSweet = '0'", $styles_db_table);
else $query_spec_beer = sprintf("SELECT * FROM %s WHERE brewStyleVersion='%s' AND brewStyleGroup <='%s' AND brewStyleReqSpec='1'", $styles_db_table, $_SESSION['prefsStyleSet'],$beer_end);
$spec_beer = mysqli_query($connection,$query_spec_beer) or die (mysqli_error($connection));
$row_spec_beer = mysqli_fetch_assoc($spec_beer);
$totalRows_spec_beer = mysqli_num_rows($spec_beer); 

$special_beer = "";
$special_beer_info = "";

if ($totalRows_spec_beer > 0) {
	
	do { 
		$value = $row_spec_beer['brewStyleGroup']."-".$row_spec_beer['brewStyleNum'];
		$special_beer[] = $value;
		$special_beer_info["$value"] = str_replace($replacement1,$replacement3,$row_spec_beer['brewStyleEntry']);
		$info = str_replace($replacement1,$replacement2,"<p>".$row_spec_beer['brewStyleInfo']."</p>");
			
		if ($row_spec_beer['brewStyleOwn'] == "custom") $styleSet = "Custom"; else $styleSet = $styleSet;
		
		if (!empty($row_spec_beer['brewStyleComEx'])) $info .= str_replace($replacement1,$replacement2,"<p>Commercial Examples: ".$row_spec_beer['brewStyleComEx']."</p>");
		if (!empty($row_spec_beer['brewStyleEntry'])) $info .= str_replace($replacement1,$replacement2,"<p>Entry Instructions: ".$row_spec_beer['brewStyleEntry']."</p>");
		
		if (empty($row_spec_beer['brewStyleOG'])) $styleOG = "Varies";
		else $styleOG = number_format((float)$row_spec_beer['brewStyleOG'], 3, '.', '')." &ndash; ".number_format((float)$row_spec_beer['brewStyleOGMax'], 3, '.', '');
	
		if (empty($row_spec_beer['brewStyleFG'])) $styleFG = "Varies";
		else $styleFG = number_format((float)$row_spec_beer['brewStyleFG'], 3, '.', '')." &ndash; ".number_format((float)$row_spec_beer['brewStyleFGMax'], 3, '.', '');
		
		if (empty($row_spec_beer['brewStyleABV'])) $styleABV = "Varies";
		else $styleABV = $row_spec_beer['brewStyleABV']." &ndash; ".$row_spec_beer['brewStyleABVMax'];
		
		if (empty($row_spec_beer['brewStyleIBU']))  $styleIBU = "Varies";
		elseif ($row_spec_beer['brewStyleIBU'] == "N/A") $styleIBU =  "N/A"; 
		elseif (!empty($row_spec_beer['brewStyleIBU'])) $styleIBU = ltrim($row_spec_beer['brewStyleIBU'], "0")." &ndash; ".ltrim($row_spec_beer['brewStyleIBUMax'], "0")." IBU";
		else $styleIBU = "&nbsp;";
		
		if (empty($row_spec_beer['brewStyleSRM'])) $styleColor = "Varies";
		elseif ($row_spec_beer['brewStyleSRM'] == "N/A") $styleColor = "N/A";
		elseif (!empty($row_spec_beer['brewStyleSRM'])) { 
					$SRMmin = ltrim ($row_spec_beer['brewStyleSRM'], "0"); 
					$SRMmax = ltrim ($row_spec_beer['brewStyleSRMMax'], "0"); 
					if ($SRMmin >= "15") $color1 = "#ffffff"; else $color1 = "#000000"; 
					if ($SRMmax >= "15") $color2 = "#ffffff"; else $color2 = "#000000"; 
					
					$styleColor = "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmin,"srm")."; color: ".$color1."\">&nbsp;".$SRMmin."&nbsp;</span>";
					$styleColor .= " &ndash; ";
					$styleColor .= "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmax,"srm")."; color: ".$color2."\">&nbsp;".$SRMmax."&nbsp;</span> <small class=\"text-muted\"><em>SRM</em></small>";
							
				} 
				else $styleColor = "&nbsp;";
		
		
		$info .= "
		<table class=\"table table-bordered table-striped\">
		<tr>
			<th class=\"dataLabel data bdr1B\">OG Range</th>
			<th class=\"dataLabel data bdr1B\">FG Range</th>
			<th class=\"dataLabel data bdr1B\">ABV Range</th>
			<th class=\"dataLabel data bdr1B\">Bitterness Range</th>
			<th class=\"dataLabel data bdr1B\">Color Range</th>
		</tr>
		<tr>
			<td nowrap>".$styleOG."</td>
			<td nowrap>".$styleFG."</td>
			<td nowrap>".$styleABV."</td>
			<td nowrap>".$styleIBU."</td>
			<td>".$styleColor."</td>
		</tr>
	</table>";
		
		
		if ($section == "brew") {
			$special_beer_modal[] = $row_spec_beer['brewStyleGroup']."-".$row_spec_beer['brewStyleNum']."|".$row_spec_beer['brewStyle'];  
			
			$modals .= "
					<!-- Modal -->
					<div class=\"modal fade\" id=\"".$value."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$value."Label\">
					  <div class=\"modal-dialog modal-lg\" role=\"document\">
						<div class=\"modal-content\">
						  <div class=\"modal-header\">
							<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
							<h4 class=\"modal-title\" id=\"".$value."Label\">".$styleSet." Style ".$row_spec_beer['brewStyleGroup'].$row_spec_beer['brewStyleNum'].": ".$row_spec_beer['brewStyle']."</h4>
						  </div>
						  <div class=\"modal-body\"><p>".$info."</div>
						  <div class=\"modal-footer\">
							<button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Close</button>
						  </div>
						</div>
					  </div>
					</div>
					";
		
		}
		
	} while ($row_spec_beer = mysqli_fetch_assoc($spec_beer));

}

// Check for styles that require all elements: special, carb, strength, sweetness
if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) $query_spec_all = sprintf("SELECT * FROM %s WHERE brewStyleOwn='custom' AND brewStyleStrength='1' AND brewStyleCarb='1' AND brewStyleReqSpec='1' AND brewStyleSweet='1'", $styles_db_table);
else $query_spec_all = sprintf("SELECT * FROM %s WHERE brewStyleVersion='%s' AND (brewStyleType='Mead' OR brewStyleType='Cider') AND brewStyleStrength='1' AND brewStyleCarb='1' AND brewStyleReqSpec='1' AND brewStyleSweet='1'", $styles_db_table, $_SESSION['prefsStyleSet']);
$spec_all = mysqli_query($connection,$query_spec_all) or die (mysqli_error($connection));
$row_spec_all = mysqli_fetch_assoc($spec_all);
$totalRows_spec_all = mysqli_num_rows($spec_all); 

$carb_str_sweet_special = "";
$carb_str_sweet_special_info = "";

if ($totalRows_spec_all > 0) { 
	do { 
		$value = $row_spec_all['brewStyleGroup']."-".$row_spec_all['brewStyleNum'];
		$carb_str_sweet_special[] = $value;
		$carb_str_sweet_special_info["$value"] = str_replace($replacement1,$replacement3,$row_spec_all['brewStyleEntry']);
		$info = str_replace($replacement1,$replacement2,"<p>".$row_spec_all['brewStyleInfo']."</p>");
		
		if ($row_spec_all['brewStyleOwn'] == "custom") $styleSet = "Custom"; else $styleSet = $styleSet;
		
		if (!empty($row_spec_all['brewStyleComEx'])) $info .= str_replace($replacement1,$replacement2,"<p>Commercial Examples: ".$row_spec_all['brewStyleComEx']."</p>");
		if (!empty($row_spec_all['brewStyleEntry'])) $info .= str_replace($replacement1,$replacement2,"<p>Entry Instructions: ".$row_spec_all['brewStyleEntry']."</p>");
		
		if (empty($row_spec_all['brewStyleOG'])) $styleOG = "Varies";
		else $styleOG = number_format((float)$row_spec_all['brewStyleOG'], 3, '.', '')." &ndash; ".number_format((float)$row_spec_all['brewStyleOGMax'], 3, '.', '');
	
		if (empty($row_spec_all['brewStyleFG'])) $styleFG = "Varies";
		else $styleFG = number_format((float)$row_spec_all['brewStyleFG'], 3, '.', '')." &ndash; ".number_format((float)$row_spec_all['brewStyleFGMax'], 3, '.', '');
		
		if (empty($row_spec_all['brewStyleABV'])) $styleABV = "Varies";
		else $styleABV = $row_spec_all['brewStyleABV']." &ndash; ".$row_spec_all['brewStyleABVMax'];
		
		if (empty($row_spec_all['brewStyleIBU']))  $styleIBU = "Varies";
		elseif ($row_spec_all['brewStyleIBU'] == "N/A") $styleIBU =  "N/A"; 
		elseif (!empty($row_spec_all['brewStyleIBU'])) $styleIBU = ltrim($row_spec_all['brewStyleIBU'], "0")." &ndash; ".ltrim($row_spec_all['brewStyleIBUMax'], "0")." IBU";
		else $styleIBU = "&nbsp;";
		
		if (empty($row_spec_all['brewStyleSRM'])) $styleColor = "Varies";
		elseif ($row_spec_all['brewStyleSRM'] == "N/A") $styleColor = "N/A";
		elseif (!empty($row_spec_all['brewStyleSRM'])) { 
					$SRMmin = ltrim ($row_spec_all['brewStyleSRM'], "0"); 
					$SRMmax = ltrim ($row_spec_all['brewStyleSRMMax'], "0"); 
					if ($SRMmin >= "15") $color1 = "#ffffff"; else $color1 = "#000000"; 
					if ($SRMmax >= "15") $color2 = "#ffffff"; else $color2 = "#000000"; 
					
					$styleColor = "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmin,"srm")."; color: ".$color1."\">&nbsp;".$SRMmin."&nbsp;</span>";
					$styleColor .= " &ndash; ";
					$styleColor .= "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmax,"srm")."; color: ".$color2."\">&nbsp;".$SRMmax."&nbsp;</span> <small class=\"text-muted\"><em>SRM</em></small>";
					
				} 
				else $styleColor = "&nbsp;";
		
		
		$info .= "
		<table class=\"table table-bordered table-striped\">
		<tr>
			<th class=\"dataLabel data bdr1B\">OG Range</th>
			<th class=\"dataLabel data bdr1B\">FG Range</th>
			<th class=\"dataLabel data bdr1B\">ABV Range</th>
			<th class=\"dataLabel data bdr1B\">Bitterness Range</th>
			<th class=\"dataLabel data bdr1B\">Color Range</th>
		</tr>
		<tr>
			<td nowrap>".$styleOG."</td>
			<td nowrap>".$styleFG."</td>
			<td nowrap>".$styleABV."</td>
			<td nowrap>".$styleIBU."</td>
			<td>".$styleColor."</td>
		</tr>
	</table>";
		
		if ($section == "brew") {
			$carb_str_sweet_special_modal[] = $row_spec_all['brewStyleGroup']."-".$row_spec_all['brewStyleNum']."|".$row_spec_all['brewStyle'];
			
			$modals .= "
					<!-- Modal -->
					<div class=\"modal fade\" id=\"".$value."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$value."Label\">
					  <div class=\"modal-dialog modal-lg\" role=\"document\">
						<div class=\"modal-content\">
						  <div class=\"modal-header\">
							<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
							<h4 class=\"modal-title\" id=\"".$value."Label\">".$styleSet." Style ".$row_spec_all['brewStyleGroup'].$row_spec_all['brewStyleNum'].": ".$row_spec_all['brewStyle']."</h4>
						  </div>
						  <div class=\"modal-body\"><p>".$info."</div>
						  <div class=\"modal-footer\">
							<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
						  </div>
						</div>
					  </div>
					</div>
					";
		
			}
		
	} while ($row_spec_all = mysqli_fetch_assoc($spec_all));
}

// Check for non-beer styles that require strength and carbonation  only (mostly mead styles - no styles in BJCP strength, carbonation, or sweetness singley)
if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) $query_str_carb = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE brewStyleOwn='custom' AND brewStyleReqSpec='0' AND brewStyleStrength='1' AND brewStyleCarb='1' AND brewStyleSweet='0'", $styles_db_table);
else $query_str_carb = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE brewStyleVersion='%s' AND (brewStyleType='Mead' OR brewStyleType='Cider' OR brewStyleType >='2') AND brewStyleReqSpec='0' AND brewStyleStrength='1' AND brewStyleCarb='1' AND brewStyleSweet='0'", $styles_db_table, $_SESSION['prefsStyleSet']);
$str_carb = mysqli_query($connection,$query_str_carb) or die (mysqli_error($connection));
$row_str_carb = mysqli_fetch_assoc($str_carb);
$totalRows_str_carb = mysqli_num_rows($str_carb);

$carb_str_only = "";

if ($totalRows_str_carb > 0) {
	
	do { 
		$carb_str_only[] = $row_str_carb['brewStyleGroup']."-".$row_str_carb['brewStyleNum'];
	} while ($row_str_carb = mysqli_fetch_assoc($str_carb));

}

// Check for non-beer styles that require carb and sweetness only (mostly cider styles - no styles in BJCP strength, carbonation, or sweetness singley)
if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) $query_sweet_carb = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE brewStyleOwn='custom' AND brewStyleReqSpec='0' AND brewStyleStrength='0' AND brewStyleCarb='1' AND brewStyleSweet='1'", $styles_db_table);
else $query_sweet_carb = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE brewStyleVersion='%s' AND (brewStyleType='Mead' OR brewStyleType='Cider' OR brewStyleType >='2') AND brewStyleReqSpec='0' AND brewStyleStrength='0' AND brewStyleCarb='1' AND brewStyleSweet='1'", $styles_db_table, $_SESSION['prefsStyleSet']);
$sweet_carb = mysqli_query($connection,$query_sweet_carb) or die (mysqli_error($connection));
$row_sweet_carb = mysqli_fetch_assoc($sweet_carb);
$totalRows_sweet_carb = mysqli_num_rows($sweet_carb);

$sweet_carb_only = "";

if ($totalRows_sweet_carb > 0) {

	do { 
		$sweet_carb_only[] = $row_sweet_carb['brewStyleGroup']."-".$row_sweet_carb['brewStyleNum'];
	} while ($row_sweet_carb = mysqli_fetch_assoc($sweet_carb));

}

// Check for non-beer styles that require strength, carbonation, and sweetness only (mostly mead styles - no styles in BJCP strength, carbonation, or sweetness singley)
if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) $query_str_sweet_carb = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE brewStyleOwn='custom' AND brewStyleReqSpec='0' AND brewStyleStrength='1' AND brewStyleCarb='1' AND brewStyleSweet='1'", $styles_db_table);
else $query_str_sweet_carb = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE brewStyleVersion='%s' AND (brewStyleType='Mead' OR brewStyleType='Cider' OR brewStyleType >='2') AND brewStyleReqSpec='0' AND brewStyleStrength='1' AND brewStyleCarb='1' AND brewStyleSweet='1'", $styles_db_table, $_SESSION['prefsStyleSet']);
$str_sweet_carb = mysqli_query($connection,$query_str_sweet_carb) or die (mysqli_error($connection));
$row_str_sweet_carb = mysqli_fetch_assoc($str_sweet_carb);
$totalRows_str_sweet_carb = mysqli_num_rows($str_sweet_carb);

$sweet_carb_str_only = "";

if ($totalRows_str_sweet_carb > 0) {
	
	do { 
		$sweet_carb_str_only[] = $row_str_sweet_carb['brewStyleGroup']."-".$row_str_sweet_carb['brewStyleNum'];
	} while ($row_str_sweet_carb = mysqli_fetch_assoc($str_sweet_carb));

}

// Check for non-beer styles that require special ingredients carbonation, and sweetness only (mostly ciders styles)
if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) $query_spec_sweet_carb = sprintf("SELECT * FROM %s WHERE brewStyleOwn='custom' AND brewStyleReqSpec='1' AND brewStyleStrength='0' AND brewStyleCarb='1' AND brewStyleSweet='1'", $styles_db_table);
else $query_spec_sweet_carb = sprintf("SELECT * FROM %s WHERE brewStyleVersion='%s' AND (brewStyleType='Mead' OR brewStyleType='Cider' OR brewStyleType >='2') AND brewStyleReqSpec='1' AND brewStyleStrength='0' AND brewStyleCarb='1' AND brewStyleSweet='1'", $styles_db_table, $_SESSION['prefsStyleSet']);
$spec_sweet_carb = mysqli_query($connection,$query_spec_sweet_carb) or die (mysqli_error($connection));
$row_spec_sweet_carb = mysqli_fetch_assoc($spec_sweet_carb);
$totalRows_spec_sweet_carb = mysqli_num_rows($spec_sweet_carb);

$spec_sweet_carb_only = "";
$spec_sweet_carb_only_info = "";

if ($totalRows_spec_sweet_carb > 0) {

	do { 
		$value = $row_spec_sweet_carb['brewStyleGroup']."-".$row_spec_sweet_carb['brewStyleNum'];
		$spec_sweet_carb_only[] = $value;
		$info = str_replace($replacement1,$replacement2,"<p>".$row_spec_sweet_carb['brewStyleInfo']."</p>");
		
		$spec_sweet_carb_only_info["$value"] = str_replace($replacement1,$replacement3,$row_spec_sweet_carb['brewStyleEntry']);
		
		if ($row_spec_sweet_carb['brewStyleOwn'] == "custom") $styleSet = "Custom"; else $styleSet = $styleSet;
		
		
		if (!empty($row_spec_sweet_carb['brewStyleComEx'])) $info .= str_replace($replacement1,$replacement2,"<p>Commercial Examples: ".$row_spec_sweet_carb['brewStyleComEx']."</p>");
		if (!empty($row_spec_sweet_carb['brewStyleEntry'])) $info .= str_replace($replacement1,$replacement2,"<p>Entry Instructions: ".$row_spec_sweet_carb['brewStyleEntry']."</p>");
		
		if (empty($row_spec_sweet_carb['brewStyleOG'])) $styleOG = "Varies";
		else $styleOG = number_format((float)$row_spec_sweet_carb['brewStyleOG'], 3, '.', '')." &ndash; ".number_format((float)$row_spec_sweet_carb['brewStyleOGMax'], 3, '.', '');
	
		if (empty($row_spec_sweet_carb['brewStyleFG'])) $styleFG = "Varies";
		else $styleFG = number_format((float)$row_spec_sweet_carb['brewStyleFG'], 3, '.', '')." &ndash; ".number_format((float)$row_spec_sweet_carb['brewStyleFGMax'], 3, '.', '');
		
		if (empty($row_spec_sweet_carb['brewStyleABV'])) $styleABV = "Varies";
		else $styleABV = $row_spec_sweet_carb['brewStyleABV']." &ndash; ".$row_spec_sweet_carb['brewStyleABVMax'];
		
		if (empty($row_spec_sweet_carb['brewStyleIBU']))  $styleIBU = "Varies";
		elseif ($row_spec_sweet_carb['brewStyleIBU'] == "N/A") $styleIBU =  "N/A"; 
		elseif (!empty($row_spec_sweet_carb['brewStyleIBU'])) $styleIBU = ltrim($row_spec_sweet_carb['brewStyleIBU'], "0")." &ndash; ".ltrim($row_spec_sweet_carb['brewStyleIBUMax'], "0")." IBU";
		else $styleIBU = "&nbsp;";
		
		if (empty($row_spec_sweet_carb['brewStyleSRM'])) $styleColor = "Varies";
		elseif ($row_spec_sweet_carb['brewStyleSRM'] == "N/A") $styleColor = "N/A";
		elseif (!empty($row_spec_sweet_carb['brewStyleSRM'])) { 
					$SRMmin = ltrim ($row_spec_sweet_carb['brewStyleSRM'], "0"); 
					$SRMmax = ltrim ($row_spec_sweet_carb['brewStyleSRMMax'], "0"); 
					if ($SRMmin >= "15") $color1 = "#ffffff"; else $color1 = "#000000"; 
					if ($SRMmax >= "15") $color2 = "#ffffff"; else $color2 = "#000000"; 
					
					$styleColor = "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmin,"srm")."; color: ".$color1."\">&nbsp;".$SRMmin."&nbsp;</span>";
					$styleColor .= " &ndash; ";
					$styleColor .= "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmax,"srm")."; color: ".$color2."\">&nbsp;".$SRMmax."&nbsp;</span> <small class=\"text-muted\"><em>SRM</em></small>";
					
				} 
				else $styleColor = "&nbsp;";
		
		
		$info .= "
		<table class=\"table table-bordered table-striped\">
		<tr>
			<th class=\"dataLabel data bdr1B\">OG Range</th>
			<th class=\"dataLabel data bdr1B\">FG Range</th>
			<th class=\"dataLabel data bdr1B\">ABV Range</th>
			<th class=\"dataLabel data bdr1B\">Bitterness Range</th>
			<th class=\"dataLabel data bdr1B\">Color Range</th>
		</tr>
		<tr>
			<td nowrap>".$styleOG."</td>
			<td nowrap>".$styleFG."</td>
			<td nowrap>".$styleABV."</td>
			<td nowrap>".$styleIBU."</td>
			<td>".$styleColor."</td>
		</tr>
	</table>";
		
		if ($section == "brew") {
			$spec_sweet_carb_only_modal[] = $row_spec_sweet_carb['brewStyleGroup']."-".$row_spec_sweet_carb['brewStyleNum']."|".$row_spec_sweet_carb['brewStyle'];
			$modals .= "
					<!-- Modal -->
					<div class=\"modal fade\" id=\"".$value."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$value."Label\">
					  <div class=\"modal-dialog modal-lg\" role=\"document\">
						<div class=\"modal-content\">
						  <div class=\"modal-header\">
							<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
							<h4 class=\"modal-title\" id=\"".$value."Label\">".$styleSet." Style ".$row_spec_sweet_carb['brewStyleGroup'].$row_spec_sweet_carb['brewStyleNum'].": ".$row_spec_sweet_carb['brewStyle']."</h4>
						  </div>
						  <div class=\"modal-body\"><p>".$info."</div>
						  <div class=\"modal-footer\">
							<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
						  </div>
						</div>
					  </div>
					</div>
					";
		}
			
	} while ($row_spec_sweet_carb = mysqli_fetch_assoc($spec_sweet_carb));
	
}
	
// Check for non-beer styles that require special ingredients and carbonation only (mostly ciders styles)
if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) $query_spec_carb = sprintf("SELECT * FROM %s WHERE brewStyleOwn='custom' AND brewStyleReqSpec='1' AND brewStyleStrength='0' AND brewStyleCarb='1' AND brewStyleSweet='0'", $styles_db_table);
else $query_spec_carb = sprintf("SELECT * FROM %s WHERE brewStyleVersion='%s' AND (brewStyleType='Mead' OR brewStyleType='Cider' OR brewStyleType >='2') AND brewStyleReqSpec='1' AND brewStyleStrength='0' AND brewStyleCarb='1' AND brewStyleSweet='0'", $styles_db_table, $_SESSION['prefsStyleSet']);
$spec_carb = mysqli_query($connection,$query_spec_carb) or die (mysqli_error($connection));
$row_spec_carb = mysqli_fetch_assoc($spec_carb);
$totalRows_spec_carb = mysqli_num_rows($spec_carb);

$spec_carb_only = "";
$spec_carb_only_info = "";

if ($totalRows_spec_carb > 0) {

	do { 
		$value = $row_spec_carb['brewStyleGroup']."-".$row_spec_carb['brewStyleNum'];
		$spec_carb_only[] = $value;
		$spec_carb_only_info["$value"] = str_replace($replacement1,$replacement3,$row_spec_carb['brewStyleEntry']);
		$info = str_replace($replacement1,$replacement2,$row_spec_carb['brewStyleInfo']);
			
			if ($row_spec_carb['brewStyleOwn'] == "custom") $styleSet = "Custom"; else $styleSet = $styleSet;
				
			if (!empty($row_spec_carb['brewStyleComEx'])) $info .= str_replace($replacement1,$replacement2,"<p>Commercial Examples: ".$row_spec_carb['brewStyleComEx']."</p>");
			if (!empty($row_spec_carb['brewStyleEntry'])) $info .= str_replace($replacement1,$replacement2,"<p>Entry Instructions: ".$row_spec_carb['brewStyleEntry']."</p>");
			
			if (empty($row_spec_carb['brewStyleOG'])) $styleOG = "Varies";
			else $styleOG = number_format((float)$row_spec_carb['brewStyleOG'], 3, '.', '')." &ndash; ".number_format((float)$row_spec_carb['brewStyleOGMax'], 3, '.', '');
			
		
			if (empty($row_spec_carb['brewStyleFG'])) $styleFG = "Varies";
			else $styleFG = number_format((float)$row_spec_carb['brewStyleFG'], 3, '.', '')." &ndash; ".number_format((float)$row_spec_carb['brewStyleFGMax'], 3, '.', '');
			
			if (empty($row_spec_carb['brewStyleABV'])) $styleABV = "Varies";
			else $styleABV = $row_spec_carb['brewStyleABV']." &ndash; ".$row_spec_carb['brewStyleABVMax'];
			
			if (empty($row_spec_carb['brewStyleIBU']))  $styleIBU = "Varies";
			elseif ($row_spec_carb['brewStyleIBU'] == "N/A") $styleIBU =  "N/A"; 
			elseif (!empty($row_spec_carb['brewStyleIBU'])) $styleIBU = ltrim($row_spec_carb['brewStyleIBU'], "0")." &ndash; ".ltrim($row_spec_carb['brewStyleIBUMax'], "0")." IBU";
			else $styleIBU = "&nbsp;";
			
			if (empty($row_spec_carb['brewStyleSRM'])) $styleColor = "Varies";
			elseif ($row_spec_carb['brewStyleSRM'] == "N/A") $styleColor = "N/A";
			elseif (!empty($row_spec_carb['brewStyleSRM'])) { 
						$SRMmin = ltrim ($row_spec_carb['brewStyleSRM'], "0"); 
						$SRMmax = ltrim ($row_spec_carb['brewStyleSRMMax'], "0"); 
						if ($SRMmin >= "15") $color1 = "#ffffff"; else $color1 = "#000000"; 
						if ($SRMmax >= "15") $color2 = "#ffffff"; else $color2 = "#000000"; 
						
						$styleColor = "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmin,"srm")."; color: ".$color1."\">&nbsp;".$SRMmin."&nbsp;</span>";
						$styleColor .= " &ndash; ";
						$styleColor .= "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmax,"srm")."; color: ".$color2."\">&nbsp;".$SRMmax."&nbsp;</span> <small class=\"text-muted\"><em>SRM</em></small>";
						 
					} 
					else $styleColor = "&nbsp;";
			
			
			$info .= "
			<table class=\"table table-bordered table-striped\">
			<tr>
				<th class=\"dataLabel data bdr1B\">OG Range</th>
				<th class=\"dataLabel data bdr1B\">FG Range</th>
				<th class=\"dataLabel data bdr1B\">ABV Range</th>
				<th class=\"dataLabel data bdr1B\">Bitterness Range</th>
				<th class=\"dataLabel data bdr1B\">Color Range</th>
			</tr>
			<tr>
				<td nowrap>".$styleOG."</td>
				<td nowrap>".$styleFG."</td>
				<td nowrap>".$styleABV."</td>
				<td nowrap>".$styleIBU."</td>
				<td>".$styleColor."</td>
			</tr>
		</table>";
			
			if ($section == "brew") {
				$spec_carb_only_modal[] = $row_spec_carb['brewStyleGroup']."-".$row_spec_carb['brewStyleNum']."|".$row_spec_carb['brewStyle'];
				$modals .= "
						<!-- Modal -->
						<div class=\"modal fade\" id=\"".$value."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$value."Label\">
						  <div class=\"modal-dialog modal-lg\" role=\"document\">
							<div class=\"modal-content\">
							  <div class=\"modal-header\">
								<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
								<h4 class=\"modal-title\" id=\"".$value."Label\">".$styleSet." Style ".$row_spec_carb['brewStyleGroup'].$row_spec_carb['brewStyleNum'].": ".$row_spec_carb['brewStyle']."</h4>
							  </div>
							  <div class=\"modal-body\"><p>".$info."</div>
							  <div class=\"modal-footer\">
								<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
							  </div>
							</div>
						  </div>
						</div>
						";
			}
		
	} while ($row_spec_carb = mysqli_fetch_assoc($spec_carb));

}


// -------------------------------------- If BA Styles -------------------------------------- 

if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {
	
	$styleSet = explode("|",$_SESSION['prefsStyleSet']);
	$styleSet = $styleSet[0];
	
	/*	
	$special_beer_info = $ba_special_beer;
	$carb_str_sweet_special_info = $ba_special_carb_str_sweet;
	$carb_str_only = $ba_carb_str;
	
	$sweet_carb_only = $ba_carb_sweet;
	$sweet_carb_str_only = $ba_carb_str_sweet;
	$spec_sweet_carb_only_info = $ba_carb_special;
	$spec_carb_only_info = $ba_carb_sweet_special;
	*/
	
} // END if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false)

$custom_entry_information = "";

if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
	
	// Check for custom styles
	$query_custom_entry_info = sprintf("SELECT * FROM %s WHERE brewStyleOwn='custom'", $styles_db_table, $_SESSION['prefsStyleSet']);
	$custom_entry_info = mysqli_query($connection,$query_custom_entry_info) or die (mysqli_error($connection));
	$row_custom_entry_info = mysqli_fetch_assoc($custom_entry_info);
	$totalRows_custom_entry_info = mysqli_num_rows($custom_entry_info); 
	
	if ($totalRows_custom_entry_info > 0) {
		
		do { 
		
			$all_entry_info = "";
			if (!empty($row_custom_entry_info['brewStyleEntry'])) $all_entry_info .= $row_custom_entry_info['brewStyleEntry'];
			if ($row_custom_entry_info['brewStyleStrength'] == 1) $all_entry_info .= " Entrant must specify strength.";
			if ($row_custom_entry_info['brewStyleCarb'] == 1) $all_entry_info .= " Entrant must specify carbonation level.";
			if ($row_custom_entry_info['brewStyleSweet'] == 1) $all_entry_info .= " Entrant must specify sweetness level.";
			
			$value = $row_custom_entry_info['brewStyleGroup']."|".$row_custom_entry_info['brewStyleNum']."|".$row_custom_entry_info['brewStyleReqSpec']."|".$row_custom_entry_info['brewStyleStrength']."|".$row_custom_entry_info['brewStyleCarb']."|".$row_custom_entry_info['brewStyleSweet'];
			$index = $row_custom_entry_info['brewStyleGroup']."-".$row_custom_entry_info['brewStyleNum'];
			$info = str_replace($replacement1,$replacement2,$row_custom_entry_info['brewStyleInfo']);
			$custom_entry["$index"] = $value;
			$custom_entry_information["$index"] = str_replace($replacement1,$replacement3,$all_entry_info);
			
				if (!empty($row_custom_entry_info['brewStyleEntry'])) $info .= str_replace($replacement1,$replacement2,"<p>Entry Instructions: ".$all_entry_info."</p>");
				
				if (empty($row_custom_entry_info['brewStyleOG'])) $styleOG = "Varies";
				else $styleOG = number_format((float)$row_custom_entry_info['brewStyleOG'], 3, '.', '')." &ndash; ".number_format((float)$row_custom_entry_info['brewStyleOGMax'], 3, '.', '');
			
				if (empty($row_custom_entry_info['brewStyleFG'])) $styleFG = "Varies";
				else $styleFG = number_format((float)$row_custom_entry_info['brewStyleFG'], 3, '.', '')." &ndash; ".number_format((float)$row_custom_entry_info['brewStyleFGMax'], 3, '.', '');
				
				if (empty($row_custom_entry_info['brewStyleABV'])) $styleABV = "Varies";
				else $styleABV = $row_custom_entry_info['brewStyleABV']." &ndash; ".$row_custom_entry_info['brewStyleABVMax'];
				
				if (empty($row_custom_entry_info['brewStyleIBU']))  $styleIBU = "Varies";
				elseif ($row_custom_entry_info['brewStyleIBU'] == "N/A") $styleIBU =  "N/A"; 
				elseif (!empty($row_custom_entry_info['brewStyleIBU'])) $styleIBU = ltrim($row_custom_entry_info['brewStyleIBU'], "0")." &ndash; ".ltrim($row_custom_entry_info['brewStyleIBUMax'], "0")." IBU";
				else $styleIBU = "&nbsp;";
				
				if (empty($row_custom_entry_info['brewStyleSRM'])) $styleColor = "Varies";
				elseif ($row_custom_entry_info['brewStyleSRM'] == "N/A") $styleColor = "N/A";
				elseif (!empty($row_custom_entry_info['brewStyleSRM'])) { 
							$SRMmin = ltrim ($row_custom_entry_info['brewStyleSRM'], "0"); 
							$SRMmax = ltrim ($row_custom_entry_info['brewStyleSRMMax'], "0"); 
							if ($SRMmin >= "15") $color1 = "#ffffff"; else $color1 = "#000000"; 
							if ($SRMmax >= "15") $color2 = "#ffffff"; else $color2 = "#000000";
							
							$styleColor = "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmin,"srm")."; color: ".$color1."\">&nbsp;".$SRMmin."&nbsp;</span>";
							$styleColor .= " &ndash; ";
							$styleColor .= "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmax,"srm")."; color: ".$color2."\">&nbsp;".$SRMmax."&nbsp;</span> <small class=\"text-muted\"><em>SRM</em></small>";
							
						} 
						else $styleColor = "&nbsp;";
				
				
				$info .= "
				<table class=\"table table-bordered table-striped\">
				<tr>
					<th class=\"dataLabel data bdr1B\">OG Range</th>
					<th class=\"dataLabel data bdr1B\">FG Range</th>
					<th class=\"dataLabel data bdr1B\">ABV Range</th>
					<th class=\"dataLabel data bdr1B\">Bitterness Range</th>
					<th class=\"dataLabel data bdr1B\">Color Range</th>
				</tr>
				<tr>
					<td nowrap>".$styleOG."</td>
					<td nowrap>".$styleFG."</td>
					<td nowrap>".$styleABV."</td>
					<td nowrap>".$styleIBU."</td>
					<td>".$styleColor."</td>
				</tr>
			</table>";
				
				if ($section == "brew") {
					
					$custom_entry_info_modal[] = $row_custom_entry_info['brewStyleGroup']."-".$row_custom_entry_info['brewStyleNum']."|".$row_custom_entry_info['brewStyle'];
					$modals .= "
							<!-- Modal -->
							<div class=\"modal fade\" id=\"".$index."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$index."Label\">
							  <div class=\"modal-dialog modal-lg\" role=\"document\">
								<div class=\"modal-content\">
								  <div class=\"modal-header\">
									<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
									<h4 class=\"modal-title\" id=\"".$index."Label\">Custom Style ".$row_custom_entry_info['brewStyleGroup'].$row_custom_entry_info['brewStyleNum'].": ".$row_custom_entry_info['brewStyle']."</h4>
								  </div>
								  <div class=\"modal-body\"><p>".$info."</div>
								  <div class=\"modal-footer\">
									<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
								  </div>
								</div>
							  </div>
							</div>
							";
				}
			
		} while ($row_custom_entry_info = mysqli_fetch_assoc($custom_entry_info));
	
	}

}
?>