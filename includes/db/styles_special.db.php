<?php

if ($section == "brew") {
	$modals = "";
	$carb_str_sweet_special = "";
	$spec_carb_only = "";
	$carb_str_only = "";
	
	$styleSet = str_replace("2"," 2",$_SESSION['prefsStyleSet']);
	
	$replacement1 = array('Entry Instructions:','Commercial Examples:','must specify','may specify','MUST specify','MAY specify','must provide','Currently Defined Types:');
if ($go == "default") $replacement2 = array('<p><strong class="text-danger">Entry Instructions:</strong>','<p><strong class="text-info">Commercial Examples:</strong>','<strong><u>MUST</u> specify</strong>','<strong><u>MAY</u> specify</strong>','<strong><u>MUST</u> specify</strong>','<strong><u>MAY</u> specify</strong>','<strong><u>MUST</u> provide</strong>','<p><strong class="text-success">Currently Defined Types:</strong>');
else $replacement2 = array('<p><strong class="text-danger">Entry Instructions:</strong>','<p><strong class="text-info">Commercial Examples:</strong>','<strong><u>MUST</u> specify</strong>','<strong><u>MAY</u> specify</strong>','<strong><u>MUST</u> specify</strong>','<strong><u>MAY</u> specify</strong>','<strong><u>MUST</u> provide</strong>','<p><strong class="text-success">Currently Defined Types:</strong>');
}

// Beer does not require mead/cider strength, carbonation or sweetness
// So, gather all beer styles that require special ingredients

$query_spec_beer = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleGroup <='%s' OR brewStyleType ='1') AND brewStyleReqSpec='1'", $styles_db_table, $_SESSION['prefsStyleSet'],$beer_end);
$spec_beer = mysqli_query($connection,$query_spec_beer) or die (mysqli_error($connection));
$row_spec_beer = mysqli_fetch_assoc($spec_beer);
do { 
	$special_beer[] = $row_spec_beer['brewStyleGroup']."-".$row_spec_beer['brewStyleNum']; 
	if ($section == "brew") {
		$special_beer_modal[] = $row_spec_beer['brewStyleGroup']."-".$row_spec_beer['brewStyleNum']."|".$row_spec_beer['brewStyle'];  
		$value = $row_spec_beer['brewStyleGroup']."-".$row_spec_beer['brewStyleNum'];
		$info = str_replace($replacement1,$replacement2,$row_spec_beer['brewStyleInfo']);
		$modals .= "
				<!-- Modal -->
				<div class=\"modal fade\" id=\"".$value."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$value."Label\">
				  <div class=\"modal-dialog\" role=\"document\">
					<div class=\"modal-content\">
					  <div class=\"modal-header\">
						<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
						<h4 class=\"modal-title\" id=\"".$value."Label\">".$styleSet." Style ".$row_spec_beer['brewStyleGroup'].$row_spec_beer['brewStyleNum'].": ".$row_spec_beer['brewStyle']."</h4>
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
} while ($row_spec_beer = mysqli_fetch_assoc($spec_beer));


// Check for non-beer styles that require all elements: special, carb, strength, sweetness
$query_spec_all = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Mead' OR brewStyleType='Cider' OR brewStyleType >='2') AND brewStyleStrength='1' AND brewStyleCarb='1' AND brewStyleReqSpec='1' AND brewStyleSweet='1'", $styles_db_table, $_SESSION['prefsStyleSet']);
$spec_all = mysqli_query($connection,$query_spec_all) or die (mysqli_error($connection));
$row_spec_all = mysqli_fetch_assoc($spec_all);
do { 
	$carb_str_sweet_special[] = $row_spec_all['brewStyleGroup']."-".$row_spec_all['brewStyleNum'];
	
	if ($section == "brew") {
		$carb_str_sweet_special_modal[] = $row_spec_all['brewStyleGroup']."-".$row_spec_all['brewStyleNum']."|".$row_spec_all['brewStyle'];
	
		$value = $row_spec_all['brewStyleGroup']."-".$row_spec_all['brewStyleNum'];
		$info = str_replace($replacement1,$replacement2,$row_spec_all['brewStyleInfo']);
		$modals .= "
				<!-- Modal -->
				<div class=\"modal fade\" id=\"".$value."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$value."Label\">
				  <div class=\"modal-dialog\" role=\"document\">
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


// Check for non-beer styles that require strength and carbonation  only (mostly mead styles - no styles in BJCP strength, carbonation, or sweetness singley)
$query_str_carb = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Mead' OR brewStyleType='Cider' OR brewStyleType >='2') AND brewStyleReqSpec='0' AND brewStyleStrength='1' AND brewStyleCarb='1' AND brewStyleSweet='0'", $styles_db_table, $_SESSION['prefsStyleSet']);
$str_carb = mysqli_query($connection,$query_str_carb) or die (mysqli_error($connection));
$row_str_carb = mysqli_fetch_assoc($str_carb);
do { 
	$carb_str_only[] = $row_str_carb['brewStyleGroup']."-".$row_str_carb['brewStyleNum'];
	
} while ($row_str_carb = mysqli_fetch_assoc($str_carb));


// Check for non-beer styles that require carb and sweetness only (mostly cider styles - no styles in BJCP strength, carbonation, or sweetness singley)
$query_sweet_carb = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Mead' OR brewStyleType='Cider' OR brewStyleType >='2') AND brewStyleReqSpec='0' AND brewStyleStrength='0' AND brewStyleCarb='1' AND brewStyleSweet='1'", $styles_db_table, $_SESSION['prefsStyleSet']);
$sweet_carb = mysqli_query($connection,$query_sweet_carb) or die (mysqli_error($connection));
$row_sweet_carb = mysqli_fetch_assoc($sweet_carb);
do { 
	$sweet_carb_only[] = $row_sweet_carb['brewStyleGroup']."-".$row_sweet_carb['brewStyleNum'];
	
} while ($row_sweet_carb = mysqli_fetch_assoc($sweet_carb));

// Check for non-beer styles that require strength, carbonation, and sweetness only (mostly mead styles - no styles in BJCP strength, carbonation, or sweetness singley)
$query_str_sweet_carb = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Mead' OR brewStyleType='Cider' OR brewStyleType >='2') AND brewStyleReqSpec='0' AND brewStyleStrength='1' AND brewStyleCarb='1' AND brewStyleSweet='1'", $styles_db_table, $_SESSION['prefsStyleSet']);
$str_sweet_carb = mysqli_query($connection,$query_str_sweet_carb) or die (mysqli_error($connection));
$row_str_sweet_carb = mysqli_fetch_assoc($str_sweet_carb);
do { 
	$sweet_carb_str_only[] = $row_str_sweet_carb['brewStyleGroup']."-".$row_str_sweet_carb['brewStyleNum'];
	
} while ($row_str_sweet_carb = mysqli_fetch_assoc($str_sweet_carb));


// Check for non-beer styles that require special ingredients carbonation, and sweetness only (mostly ciders styles)
$query_spec_sweet_carb = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Mead' OR brewStyleType='Cider' OR brewStyleType >='2') AND brewStyleReqSpec='1' AND brewStyleStrength='0' AND brewStyleCarb='1' AND brewStyleSweet='1'", $styles_db_table, $_SESSION['prefsStyleSet']);
$spec_sweet_carb = mysqli_query($connection,$query_spec_sweet_carb) or die (mysqli_error($connection));
$row_spec_sweet_carb = mysqli_fetch_assoc($spec_sweet_carb);
do { 
	$spec_sweet_carb_only[] = $row_spec_sweet_carb['brewStyleGroup']."-".$row_spec_sweet_carb['brewStyleNum'];
	if ($section == "brew") {
		$spec_sweet_carb_only_modal[] = $row_spec_sweet_carb['brewStyleGroup']."-".$row_spec_sweet_carb['brewStyleNum']."|".$row_spec_sweet_carb['brewStyle'];
		$value = $row_spec_sweet_carb['brewStyleGroup']."-".$row_spec_sweet_carb['brewStyleNum'];
		$info = str_replace($replacement1,$replacement2,$row_spec_sweet_carb['brewStyleInfo']);
		$modals .= "
				<!-- Modal -->
				<div class=\"modal fade\" id=\"".$value."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$value."Label\">
				  <div class=\"modal-dialog\" role=\"document\">
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


// Check for non-beer styles that require special ingredients and carbonation only (mostly ciders styles)
$query_spec_carb = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Mead' OR brewStyleType='Cider' OR brewStyleType >='2') AND brewStyleReqSpec='1' AND brewStyleStrength='0' AND brewStyleCarb='1' AND brewStyleSweet='0'", $styles_db_table, $_SESSION['prefsStyleSet']);
$spec_carb = mysqli_query($connection,$query_spec_carb) or die (mysqli_error($connection));
$row_spec_carb = mysqli_fetch_assoc($spec_carb);
do { 
	$spec_carb_only[] = $row_spec_carb['brewStyleGroup']."-".$row_spec_carb['brewStyleNum'];
	
	if ($section == "brew") {
		$spec_carb_only_modal[] = $row_spec_carb['brewStyleGroup']."-".$row_spec_carb['brewStyleNum']."|".$row_spec_carb['brewStyle'];
		$value = $row_spec_carb['brewStyleGroup']."-".$row_spec_carb['brewStyleNum'];
		$info = str_replace($replacement1,$replacement2,$row_spec_carb['brewStyleInfo']);
		$modals .= "
				<!-- Modal -->
				<div class=\"modal fade\" id=\"".$value."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$value."Label\">
				  <div class=\"modal-dialog\" role=\"document\">
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


























/*
$query_spec_beer = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM $styles_db_table WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleGroup <='%s' OR brewStyleType ='1') AND brewStyleReqSpec='1'", $_SESSION['prefsStyleSet'],$beer_end);
$spec_beer = mysqli_query($connection,$query_spec_beer) or die (mysqli_error($connection));
$row_spec_beer = mysqli_fetch_assoc($spec_beer);
do { 
	$special_beer[] = $row_spec_beer['brewStyleGroup']."-".$row_spec_beer['brewStyleNum']; 
	if ($section == "brew") {
		$value = $row_spec_beer['brewStyleGroup']."-".$row_spec_beer['brewStyleNum'];
		$replacement1 = array('Entry Instructions:','Commercial Examples:','must specify','may specify','MUST specify','MAY specify','must provide');
	if ($go == "default") $replacement2 = array('<p><strong class="text-danger">Entry Instructions:</strong>','<p><strong class="text-info">Commercial Examples:</strong>','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> provide');
	else $replacement2 = array('<p><strong class="text-danger">Entry Instructions:</strong>','<p><strong class="text-info">Commercial Examples:</strong>','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> provide');
		$info = str_replace($replacement1,$replacement2,$row_spec_beer['brewStyleInfo']);
		$modals .= "
				<!-- Modal -->
				<div class=\"modal fade\" id=\"".$value."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$value."Label\">
				  <div class=\"modal-dialog\" role=\"document\">
					<div class=\"modal-content\">
					  <div class=\"modal-header\">
						<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
						<h4 class=\"modal-title\" id=\"".$value."Label\">BJCP Style ".$row_spec_beer['brewStyleGroup'].$row_spec_beer['brewStyleNum'].": ".$row_spec_beer['brewStyle']."</h4>
					  </div>
					  <div class=\"modal-body\"><p>".$info."</div>
					  <div class=\"modal-footer\">
						<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
					  </div>
					</div>
				  </div>
				</div>
				";
		$special_js .= "";
	}
} while ($row_spec_beer = mysqli_fetch_assoc($spec_beer));
//print_r($special_beer); echo "<br>";

$query_carb_mead = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM $styles_db_table WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Mead' OR brewStyleType ='3') AND brewStyleReqSpec='0' AND brewStyleCarb='1'", $_SESSION['prefsStyleSet']);
$carb_mead = mysqli_query($connection,$query_carb_mead) or die (mysqli_error($connection));
$row_carb_mead = mysqli_fetch_assoc($carb_mead);
do { $mead[] = $row_carb_mead['brewStyleGroup']."-".$row_carb_mead['brewStyleNum']; } while ($row_carb_mead = mysqli_fetch_assoc($carb_mead));
//print_r($mead); echo "<br>";

$query_str_mead = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM $styles_db_table WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Mead' OR brewStyleType ='3') AND brewStyleReqSpec='0' AND brewStyleStrength='1'", $_SESSION['prefsStyleSet']);
$str_mead = mysqli_query($connection,$query_str_mead) or die (mysqli_error($connection));
$row_str_mead = mysqli_fetch_assoc($str_mead);
do { $strength_mead[] = $row_str_mead['brewStyleGroup']."-".$row_str_mead['brewStyleNum']; } while ($row_str_mead = mysqli_fetch_assoc($str_mead));
//print_r($strength_mead); echo "<br>";

$query_spec_mead = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM $styles_db_table WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Mead' OR brewStyleType ='3') AND brewStyleReqSpec='1'", $_SESSION['prefsStyleSet']);
$spec_mead = mysqli_query($connection,$query_spec_mead) or die (mysqli_error($connection));
$row_spec_mead = mysqli_fetch_assoc($spec_mead);
do { 
	$special_mead[] = $row_spec_mead['brewStyleGroup']."-".$row_spec_mead['brewStyleNum'];
	
	if ($section == "brew") {
		$value = $row_spec_mead['brewStyleGroup']."-".$row_spec_mead['brewStyleNum'];
		$replacement1 = array('Entry Instructions:','Commercial Examples:','must specify','may specify','MUST specify','MAY specify','must provide');
if ($go == "default") $replacement2 = array('<p><strong class="text-danger">Entry Instructions:</strong>','<p><strong class="text-info">Commercial Examples:</strong>','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> provide');
else $replacement2 = array('<p><strong class="text-danger">Entry Instructions:</strong>','<p><strong class="text-info">Commercial Examples:</strong>','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> provide');
		$info = str_replace($replacement1,$replacement2,$row_spec_mead['brewStyleInfo']);
		$modals .= "
				<!-- Modal -->
				<div class=\"modal fade\" id=\"".$value."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$value."Label\">
				  <div class=\"modal-dialog\" role=\"document\">
					<div class=\"modal-content\">
					  <div class=\"modal-header\">
						<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
						<h4 class=\"modal-title\" id=\"".$value."Label\">BJCP Style ".$row_spec_mead['brewStyleGroup'].$row_spec_mead['brewStyleNum'].": ".$row_spec_mead['brewStyle']."</h4>
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
	
} while ($row_spec_mead = mysqli_fetch_assoc($spec_mead));
//print_r($special_mead); echo "<br>";

$query_carb_cider = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM $styles_db_table WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Cider' OR brewStyleType ='2') AND brewStyleReqSpec='0' AND brewStyleCarb='1'", $_SESSION['prefsStyleSet']);
$carb_cider = mysqli_query($connection,$query_carb_cider) or die (mysqli_error($connection));
$row_carb_cider = mysqli_fetch_assoc($carb_cider);
do { $cider[] = $row_carb_cider['brewStyleGroup']."-".$row_carb_cider['brewStyleNum']; } while ($row_carb_cider = mysqli_fetch_assoc($carb_cider));
	
$query_spec_cider = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM $styles_db_table WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Cider' OR brewStyleType ='2') AND brewStyleReqSpec='1'", $_SESSION['prefsStyleSet']);
$spec_cider = mysqli_query($connection,$query_spec_cider) or die (mysqli_error($connection));
$row_spec_cider = mysqli_fetch_assoc($spec_cider);
do { 
$special_cider[] = $row_spec_cider['brewStyleGroup']."-".$row_spec_cider['brewStyleNum']; 
	if ($section == "brew") {
		$value = $row_spec_cider['brewStyleGroup']."-".$row_spec_cider['brewStyleNum'];
		$replacement1 = array('Entry Instructions:','Commercial Examples:','must specify','may specify','MUST specify','MAY specify','must provide');
if ($go == "default") $replacement2 = array('<p><strong class="text-danger">Entry Instructions:</strong>','<p><strong class="text-info">Commercial Examples:</strong>','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> provide');
else $replacement2 = array('<p><strong class="text-danger">Entry Instructions:</strong>','<p><strong class="text-info">Commercial Examples:</strong>','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> provide');
		$info = str_replace($replacement1,$replacement2,$row_spec_cider['brewStyleInfo']);
		$modals .= "
				<!-- Modal -->
				<div class=\"modal fade\" id=\"".$value."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$value."Label\">
				  <div class=\"modal-dialog\" role=\"document\">
					<div class=\"modal-content\">
					  <div class=\"modal-header\">
						<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
						<h4 class=\"modal-title\" id=\"".$value."Label\">BJCP Style ".$row_spec_cider['brewStyleGroup'].$row_spec_cider['brewStyleNum'].": ".$row_spec_cider['brewStyle']."</h4>
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
} while ($row_spec_cider = mysqli_fetch_assoc($spec_cider));

// Custom Styles
$special_custom = "";
$query_spec_custom = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE brewStyleOwn='custom' AND brewStyleType >= 4 AND brewStyleReqSpec='1'", $styles_db_table);
$spec_custom = mysqli_query($connection,$query_spec_custom) or die (mysqli_error($connection));
$row_spec_custom = mysqli_fetch_assoc($spec_custom);
$totalRows_spec_custom = mysqli_num_rows($spec_custom);
if ($totalRows_spec_custom > 0) {
	do { 
		$special_custom[] = $row_spec_custom['brewStyleGroup']."-".$row_spec_custom['brewStyleNum'];
		if ($section == "brew") {
			$value = $row_spec_custom['brewStyleGroup']."-".$row_spec_custom['brewStyleNum'];
			$replacement1 = array('Entry Instructions:','Commercial Examples:','must specify','may specify','MUST specify','MAY specify','must provide');
	if ($go == "default") $replacement2 = array('<p><strong class="text-danger">Entry Instructions:</strong>','<p><strong class="text-info">Commercial Examples:</strong>','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> provide');
	else $replacement2 = array('<p><strong class="text-danger">Entry Instructions:</strong>','<p><strong class="text-info">Commercial Examples:</strong>','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> specify','<u>MAY</u> specify','<u>MUST</u> provide');
			$info = str_replace($replacement1,$replacement2,$row_spec_custom['brewStyleInfo']);
			$modals .= "
					<!-- Modal -->
					<div class=\"modal fade\" id=\"".$value."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$value."Label\">
					  <div class=\"modal-dialog\" role=\"document\">
						<div class=\"modal-content\">
						  <div class=\"modal-header\">
							<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
							<h4 class=\"modal-title\" id=\"".$value."Label\">Custom Style ".$row_spec_custom['brewStyleGroup'].$row_spec_custom['brewStyleNum'].": ".$row_spec_custom['brewStyle']."</h4>
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
	} while ($row_spec_custom = mysqli_fetch_assoc($spec_custom));
}

$query_carb_custom = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE brewStyleOwn='custom' AND brewStyleCarb='1'", $styles_db_table);
$carb_custom = mysqli_query($connection,$query_carb_custom) or die (mysqli_error($connection));
$row_carb_custom = mysqli_fetch_assoc($carb_custom);
do { $carbonation_custom[] = $row_carb_custom['brewStyleGroup']."-".$row_carb_custom['brewStyleNum']; } while ($row_carb_custom = mysqli_fetch_assoc($carb_custom));

$query_str_custom = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE brewStyleOwn='custom' AND brewStyleStrength='1'", $styles_db_table);
$str_custom = mysqli_query($connection,$query_str_custom) or die (mysqli_error($connection));
$row_str_custom = mysqli_fetch_assoc($str_custom);
do { $strength_custom[] = $row_str_custom['brewStyleGroup']."-".$row_str_custom['brewStyleNum']; } while ($row_str_custom = mysqli_fetch_assoc($str_custom));

$query_all_customs = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleInfo FROM %s WHERE brewStyleOwn='custom' AND brewStyleStrength='1' AND brewStyleCarb='1' AND brewStyleReqSpec='1'", $styles_db_table);
$all_customs = mysqli_query($connection,$query_all_customs) or die (mysqli_error($connection));
$row_all_customs = mysqli_fetch_assoc($all_customs);
do { $all_custom[] = $row_all_customs['brewStyleGroup']."-".$row_all_customs['brewStyleNum']; } while ($row_all_customs = mysqli_fetch_assoc($all_customs));
*/
?>