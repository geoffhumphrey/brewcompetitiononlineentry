<?php
/**
 * Module:      brew.pub.php
 * Description: This module houses the functionality for users to add/edit individual competition
 *              entries - references the "brewing" database table.
 *
 */

/*
// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
*/

include (DB.'styles.db.php');
if ($_SESSION['prefsStyleSet'] == "BA") include (INCLUDES.'ba_constants.inc.php');
include (DB.'styles_special.db.php');

// Disable fields trigger
if ((($action == "add") && ($remaining_entries <= 0) && ($_SESSION['userLevel'] == 2)) || (($action == "add") && ($entry_window_open == "2") && ($_SESSION['userLevel'] == 2))) $disable_fields = TRUE; else $disable_fields = FALSE;

// Specific code for Style select
$style_set = $_SESSION['style_set_short_name'];

if (empty($row_limits['prefsUserSubCatLimit'])) $user_subcat_limit = "99999";
else $user_subcat_limit = $row_limits['prefsUserSubCatLimit'];

if (empty($row_limits['prefsUSCLExLimit'])) $user_subcat_limit_exception = "99999";
else $user_subcat_limit_exception = $row_limits['prefsUSCLExLimit'];

$view_explodies = explode("-",$view);

// Define vars
$add_entry_disable = FALSE;
$edit_entry_disable = FALSE;
$special_required = FALSE;
$expanded_on_load = FALSE;
$add_or_edit = TRUE;
$selected_disabled = "";
$darkLightPale = "";
$darkLightAmber = "";
$saisonTable = "";
$saisonStandard = "";
$saisonSuper = "";
$IPASession = "";
$IPAStandard  = "";
$IPADouble = "";
$lambicSweetLow = "";
$lambicSweetMed = "";
$lambicSweetHigh = "";
$lambicCarbLow = "";
$lambicCarbMed = "";
$lambicCarbHigh = "";
$BDGBlonde = "";
$BDGAmber = "";
$BDGBrown = "";
$brewInfo = "";

$highlight_sweetness = "";
$highlight_special = "";
$highlight_carb = "";
$highlight_strength = "";

$proEdition = FALSE;
if ($_SESSION['prefsProEdition'] == 1) $proEdition = TRUE;

$adminUser = FALSE;
if ($_SESSION['userLevel'] <= 1) $adminUser = TRUE;

$adminUserAddDisable = FALSE;

if ($action == "add") {
	$submit_icon = "plus";
	$submit_text = $label_add_entry;
}
if ($action == "edit") {
	$submit_icon = "pencil";
	$submit_text = $label_edit_entry;
}

if (($_SESSION['userLevel'] == 2) && ($action == "edit")) {

	// Fix fatal error when using [] operator on strings
	$user_entries = array();

	// Check whether user is "authorized" to edit the entry in DB
	$query_brews = sprintf("SELECT id FROM $brewing_db_table WHERE brewBrewerID = '%s'", $_SESSION['user_id']);
	$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
	$row_brews = mysqli_fetch_assoc($brews);

	do { $user_entries[] = $row_brews['id'];  } while ($row_brews = mysqli_fetch_assoc($brews));

	if (!in_array($id,$user_entries)) {
		$edit_entry_disable = TRUE; echo "<p>You are only able to edit your own entries.</p>";
	}
}

// Adding an entry not allowed conditionals for non-admins
if ($action == "add") {

	// Registration and entry windows open; comp entry limit reached
	if (($registration_open == 1) && ($entry_window_open == 1) && ($_SESSION['userLevel'] == 2)) {
		if ($comp_entry_limit) $add_entry_disable = TRUE;
		elseif ($remaining_entries <= 0) $add_entry_disable = TRUE;
		elseif ($comp_paid_entry_limit) $add_entry_disable = TRUE;
		elseif (($proEdition) && (!isset($_SESSION['brewerBreweryName'])))  $add_entry_disable = TRUE;
		else $add_entry_disable = FALSE;
	}

	// Registration closed and entry window open; comp entry limit reached
	elseif ((($registration_open == 0) || ($registration_open == 2)) && ($entry_window_open == 1) && ($_SESSION['userLevel'] == 2)) {
		if ($comp_entry_limit) $add_entry_disable = TRUE;
		elseif ($remaining_entries <= 0) $add_entry_disable = TRUE;
		elseif ($comp_paid_entry_limit) $add_entry_disable = TRUE;
		elseif (($proEdition) && (!isset($_SESSION['brewerBreweryName']))) $add_entry_disable = TRUE;
		else $add_entry_disable = FALSE;
	}

}

// Registration and entry not open
if (($registration_open == 0) && ($entry_window_open == 0) && ($_SESSION['userLevel'] == 2)) {
	$add_entry_disable = TRUE;
	$edit_entry_disable = TRUE;
}

// Entry window closed
if (($entry_window_open == 2) && ($_SESSION['userLevel'] == 2)) {
	// Disable adding of entries
	$add_entry_disable = TRUE;

	// Can edit until either drop off closes or judging dates pass
	if (($judging_started) || (time() > $_SESSION['contestDropoffDeadline'])) $edit_entry_disable = TRUE;
}

if (($proEdition) && (!isset($_SESSION['brewerBreweryName'])) && ($_SESSION['userLevel'] == 2)) {
	$add_entry_disable = TRUE;
	$edit_entry_disable = TRUE;
	$adminUserAddDisable = TRUE;
}

// Construct styles drop-down
$styles_dropdown = "";
$styles_dropdown_count = 0;
$styles_disabled_count = 0;
$style_types_warning = 0;

do {

	if (array_key_exists($row_styles['id'], $styles_selected)) {

		$styles_dropdown_count++;

		$style_value = style_number_const($row_styles['brewStyleGroup'],$row_styles['brewStyleNum'],$_SESSION['style_set_system_separator'],999);
		$style_value_edit = style_number_const($row_styles['brewStyleGroup'],$row_styles['brewStyleNum'],$_SESSION['style_set_system_separator'],1);

		if (($_SESSION['userLevel'] <= 1) && ($bid != "default")) $subcat_limit = limit_subcategory($style_value,$user_subcat_limit,$user_subcat_limit_exception,$row_limits['prefsUSCLEx'],$bid);
		else $subcat_limit = limit_subcategory($style_value,$user_subcat_limit,$user_subcat_limit_exception,$row_limits['prefsUSCLEx'],$_SESSION['user_id']);

		// Build selected/disabled variable
		$selected_disabled = "";
		$selected = "";
		
		if ($action == "edit") {
			if ($row_styles['brewStyleGroup'].$row_styles['brewStyleNum'] == $row_log['brewCategorySort'].$row_log['brewSubCategory']) $selected_disabled = "SELECTED";
			if (($row_styles['brewStyleGroup'].$row_styles['brewStyleNum'] != $row_log['brewCategorySort'].$row_log['brewSubCategory']) && ($subcat_limit)) $selected_disabled = "DISABLED";
			$styles_disabled_count++;
		}
		
		if (($remaining_entries > 0) && (!$disable_fields) && ($subcat_limit)) $selected_disabled = "DISABLED";
		elseif ($disable_fields) $selected_disabled = "DISABLED";
		elseif (!empty($style_type_limits)) {
			
			if (($action == "add") && ($_SESSION['userLevel'] > 1) && (isset($style_type_limits[$row_styles['brewStyleType']])) && ($style_type_limits[$row_styles['brewStyleType']] == 1)) {
				$selected_disabled = "DISABLED";
				$style_types_warning++;
				$styles_disabled_count++;
			}
			
			if (($action == "edit") && ($_SESSION['userLevel'] > 1) && (isset($style_type_limits[$row_styles['brewStyleType']])) && ($style_type_limits[$row_styles['brewStyleType']] == 1)) {

				if ($row_styles['brewStyleType'] != $row_log['brewStyleType']) {
					$selected_disabled = "DISABLED";
					$style_types_warning++;
					$styles_disabled_count++;
				}
				
			}
		
		}

		if (($action == "edit") && ($view == $style_value_edit)) {
			$selected = " SELECTED";
			$selected_disabled = "";
		}
		
		// Build selection variable
		$selection = style_number_const($row_styles['brewStyleGroup'],$row_styles['brewStyleNum'],$_SESSION['style_set_display_separator'],0)." ".$row_styles['brewStyle'];

		if ($row_styles['brewStyleReqSpec'] == 1) $selection .= " &spades;";
		if ($row_styles['brewStyleStrength'] == 1) $selection .= " &diams;";
		if ($row_styles['brewStyleCarb'] == 1) $selection .= " &clubs;";
		if ($row_styles['brewStyleSweet'] == 1) $selection .= " &hearts;";
		if ($selected_disabled == "DISABLED") $selection .= " ".$brew_text_003;

		if (!empty($row_styles['brewStyleGroup'])) {
			$styles_dropdown .= "<option value=\"".$style_value."\"";
			$styles_dropdown .= $selected_disabled;
			$styles_dropdown .= $selected;
			$styles_dropdown .= ">";
			$styles_dropdown .= $selection;
			$styles_dropdown .= "</option>\n";
		}

	}

} while ($row_styles = mysqli_fetch_assoc($styles));

if (($style_types_warning >= $styles_dropdown_count) && ($_SESSION['userLevel'] > 1)) $add_entry_disable = TRUE;
if (($styles_disabled_count >= $styles_dropdown_count) && ($_SESSION['userLevel'] > 1)) $add_entry_disable = TRUE;

$add_edit_message = "";

// Disable display of add/edit form elements
// Display messaging if adding and/or editing is disabled
if (($add_entry_disable) && ($edit_entry_disable))  {
	$add_or_edit = FALSE;
	$add_edit_message .= "<p class=\"lead\">".$alert_text_083."</p>";
	if (($_SESSION['userLevel'] > 1) && ($remaining_entries <= 0)) $add_edit_message .= sprintf("<p>%s</p>",$alert_text_031);
	if (($adminUser) && ($adminUserAddDisable)) $add_edit_message .= "<p>".$brew_text_029."</p>";
}

if (($add_entry_disable) && (!$edit_entry_disable) && ($action == "add"))  {

	$add_or_edit = FALSE;

	if (($proEdition) && (!isset($_SESSION['brewerBreweryName']))) {
		$add_edit_message .= "<p class=\"lead\">".$alert_text_082."</p>";
		if ($adminUser) $add_edit_message .= "<p class=\"lead\"><small>".$alert_text_084."</small></p>";
	}

	else $add_edit_message .= "<p class=\"lead\">".$alert_text_029."</p>";
	$style_types_disabled = "";
	if (!empty($style_type_limits_alert)) {
	  foreach ($style_type_limits_alert as $key => $value) {
	    if ($value > 0) {
	    	if (array_key_exists($key, $style_types_translations)) $style_types_disabled .= strtolower($style_types_translations[$key]).", ";
	      	else $style_types_disabled .= strtolower($key).", ";
	    }
	  }
	  $style_types_disabled = rtrim($style_types_disabled,", ");
	}

	if (!empty($style_types_disabled)) $add_edit_message .= "<p>".$alert_text_094.": ".$style_types_disabled.".</p>";
	if (($_SESSION['userLevel'] > 1) && ($remaining_entries <= 0)) $add_edit_message .= sprintf("<p>%s</p>",$alert_text_031);
	if (($adminUser) && ($adminUserAddDisable)) $add_edit_message .= "<p>".$brew_text_029."</p>";

}

echo $add_edit_message;

// Run normally
if ($add_or_edit) {

	// Define vars
	if ($action == "edit") $collapse_icon = "fa-plus-circle";
	else $collapse_icon = "fa-pencil";
	$possible_allergens = array($brew_text_030,$brew_text_031,$brew_text_032,$brew_text_033,$brew_text_034,$brew_text_035,$brew_text_036,$brew_text_037);

	// Get the brewer's information from the function
	if ((($filter == "admin") || ($filter == "default")) && ($bid == "default")) $brewer_id = $_SESSION['user_id'];
	elseif ((($filter == "admin") || ($filter == "default")) && ($bid != "default")) $brewer_id = $bid;
	else $brewer_id = $filter;
	$brewer_info = brewer_info($brewer_id);
	$brewer_info = explode("^",$brewer_info);
	if (($action == "edit") && (!empty($row_log['brewInfo']))) $brewInfo = $row_log['brewInfo'];

	// Define whether the entry has been paid or not, default is 0 (no)
	$brewPaid = 0;

	// Check to see if the amount the entrant has paid is equal to or exceeds the entry fee cap (if defined)
	if ((isset($_SESSION['contestEntryCap'])) && ($_SESSION['contestEntryCap'] > 0)) {
		// if so, mark this entry as paid
		if ($total_paid_entry_fees >= $_SESSION['contestEntryCap']) $brewPaid = 1;
	}

	// If there's no entry fee (zero), mark as paid
	if ($_SESSION['contestEntryFee'] == 0) $brewPaid = 1;

	// If editing the entry, grab the value from the recordset
	if ($action == "edit") {
		
		$brewPaid = $row_log['brewPaid'];

		if (strlen(strstr($view,"21-B")) > 0) {

			$exploder = explode("^",$row_log['brewInfo']);

			/** 
			 * If the catch-all specialty IPA category, explode to 
			 * gather the strength and the required info
			 */
			
			if ($view == "21-B") {
				$exploder_ipa = $exploder[1];
				$brewInfo = $exploder[0];
			}

			/** 
			 * For the speciality IPA substyles that are pre-defined
			 * by the BJCP, only grab the strength (info not required)
			 *
			 * @fixes https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1108
			 */

			else {
				$exploder_ipa = $exploder[0];
				$brewInfo = "";
			}

			if ($exploder_ipa == "Session Strength") $IPASession = "CHECKED";
			if ($exploder_ipa == "Standard Strength") $IPAStandard = "CHECKED";
			if ($exploder_ipa == "Double Strength") $IPADouble = "CHECKED"; 
		}

		elseif ($view == "23-F") {
			$exploder = explode("^",$row_log['brewInfo']);
			$brewInfo = $exploder[0];
			if ($exploder[1] == "Low/None Sweetness") $lambicSweetLow = "CHECKED";
			if ($exploder[1] == "Medium Sweetness") $lambicSweetMed = "CHECKED";
			if ($exploder[1] == "High Sweetness") $lambicSweetHigh = "CHECKED";
			if ($exploder[2] == "Low Carbonation") $lambicCarbLow = "CHECKED";
			if ($exploder[2] == "Medium Carbonation") $lambicCarbMed = "CHECKED";
			if ($exploder[2] == "High Carbonation") $lambicCarbHigh = "CHECKED";

		}

		elseif ($view == "25-B") {
			$exploder = explode("^",$row_log['brewInfo']);
			if ($exploder[0] == "Table Strength") $saisonTable = "CHECKED";
			if ($exploder[0] == "Standard Strength") $saisonStandard = "CHECKED";
			if ($exploder[0] == "Super Strength") $saisonSuper = "CHECKED";
			if ($exploder[1] == "Pale Color") $darkLightPale = "CHECKED";
			if ($exploder[1] == "Amber/Dark Color") $darkLightAmber = "CHECKED";
			$brewInfo = "";
		}

		elseif ($view == "24-C") {
			if ($row_log['brewInfo'] == "Blonde Color") $BDGBlonde .= "CHECKED";
			if ($row_log['brewInfo'] == "Amber Color") $BDGAmber .= "CHECKED";
			if ($row_log['brewInfo'] == "Brown Color") $BDGBrown .= "CHECKED";
			$brewInfo = "";
		}

		else {
			if ($row_log['brewInfo'] == "Pale Color") $darkLightPale = "CHECKED";
			if ($row_log['brewInfo'] == "Amber/Dark Color") $darkLightAmber = "CHECKED";
			$brewInfo = $row_log['brewInfo'];
		}

	}
	
    if ($action == "edit") {
    	$entry_name = html_entity_decode($row_log['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
    	$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");
    }
    
    $entry_disabled = "";
    $entry_disabled_placeholder = "";
    if ((($action == "add") && ($remaining_entries <= 0) && ($entry_window_open == 1) && ($filter != "default") && ($_SESSION['userLevel'] > 1)) || (($action == "add") && ($entry_window_open == "2") && ($_SESSION['userLevel'] > 1))) {
    	$entry_disabled = "disabled";
    	$entry_disabled_placeholder = sprintf("%s %s",$sidebar_text_020,$row_limits['prefsUserEntryLimit']);
			if ($row_limits['prefsUserEntryLimit'] > 1) $entry_disabled_placeholder .= sprintf(" %s ",strtolower($label_entries));
			else $entry_disabled_placeholder .= sprintf(" %s ",strtolower($label_entry));
	}
	
	$entry_style_warning = "<div class=\"help-block\">";
    $entry_style_warning .= "<strong class=\"text-teal\">".$brew_text_050."</strong>"; 
    
    if ($_SESSION['userLevel'] > 1) {
    	$style_types_disabled = "";
    	  if (!empty($style_type_limits_alert)) {
    	      foreach ($style_type_limits_alert as $key => $value) {
    	        if ($value > 0) {
    	        	if (array_key_exists($key, $style_types_translations)) $style_types_disabled .= strtolower($style_types_translations[$key]).", ";
    	          	else $style_types_disabled .= strtolower($key).", ";
    	        }
    	      }
    	      $style_types_disabled = rtrim($style_types_disabled,", ");
    	    }
    	if (!empty($style_types_disabled)) $entry_style_warning .= " ".$alert_text_094.": ".$style_types_disabled.".";
    }

    $entry_style_warning .= "</div>";

    if (($action == "edit") && (!empty($row_log['brewPouring']))) $pouring_arr = json_decode($row_log['brewPouring'],true);
    else $pouring_arr = array(
    	"pouring" => "",
    	"pouring_rouse" => "",
    	"pouring_notes" => ""
    );

echo $add_edit_entry_modals;
if (!isset($_SERVER['HTTP_REFERER'])) $relocate_referrer = "list";
else $relocate_referrer = $_SERVER['HTTP_REFERER'];

if ($_SESSION['prefsStyleSet'] == "NWCiderCup") { 

	$juice_select_options = array(
		"BC" => "British Columbia (BC)",
		"ID" => "Idaho (ID)",
		"MT" => "Montana (MT)",
		"OR" => "Oregon (OR)",
		"WA" => "Washington (WA)",
		"Other" => "Others States/Provinces in US/Canada",
		"Outside of US/Canada" => "Outside of US/Canada",
		"Unknown" => "I Don't Know"
	);

	$juice_select_dropdown = "";
	$juice_select_dropdown_other = "";
	$juice_source_arr = array();

	if (($action == "edit") && (!empty($row_log['brewJuiceSource']))) {
		$juice_src_arr = json_decode($row_log['brewJuiceSource'],true);
	}

	$juice_options = FALSE;
	$juice_options_other = FALSE;
	if ((isset($juice_src_arr['juice_src'])) && (is_array($juice_src_arr['juice_src']))) $juice_options = TRUE;
	if ((isset($juice_src_arr['juice_src_other'])) && (is_array($juice_src_arr['juice_src_other']))) $juice_options_other = TRUE;

	foreach ($juice_select_options as $key => $value) {

		$juice_select_dropdown .= "<option value=\"".$key."\"";
		if ($juice_options) {
	        if (in_array($key,$juice_src_arr['juice_src'])) $juice_select_dropdown .= " SELECTED";
	    }
		$juice_select_dropdown .= ">".$value."</option>";

		$juice_select_dropdown_other .= "<option value=\"".$key."\"";
		if ($juice_options_other) {
	        if (in_array($key,$juice_src_arr['juice_src_other'])) $juice_select_dropdown_other .= " SELECTED";
	    }
		$juice_select_dropdown_other .= ">".$value."</option>";

	}

}

?>

<form id="submit-form" role="form" class="form-horizontal hide-loader-form-submit needs-validation" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo admin_relocate($_SESSION['userLevel'],$go,$relocate_referrer);?>&amp;action=<?php echo $action; ?>&amp;go=<?php echo $go;?>&amp;dbTable=<?php echo $brewing_db_table; ?>&amp;filter=<?php echo $filter; if ($id != "default") echo "&amp;id=".$id; ?>" method="POST" name="form1" novalidate>
	<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
<?php if ($_SESSION['userLevel'] > 1) { ?>
	<input type="hidden" name="brewBrewerID" value="<?php echo $_SESSION['user_id']; ?>">
	<input type="hidden" name="brewBrewerFirstName" value="<?php echo $_SESSION['brewerFirstName']; ?>">
	<input type="hidden" name="brewBrewerLastName" value="<?php echo $_SESSION['brewerLastName']; ?>">
<?php } ?>
<?php if ($action == "edit") { ?>
	<input type="hidden" name="brewEditStyle" value="<?php echo style_number_const($row_log['brewCategorySort'],$row_log['brewSubCategory'],$_SESSION['style_set_system_separator'],999); ?>">
	<input type="hidden" name="brewJudgingNumber" value="<?php echo $row_log['brewJudgingNumber']; ?>">
	<input type="hidden" name="brewReceived" value="<?php if (empty($row_log['brewReceived'])) echo "0"; else echo $row_log['brewReceived']; ?>">
<?php } ?>
	<input type="hidden" name="brewBrewerID" id="brewBrewerID" value="<?php echo $brewer_info[7]; ?>">

<?php if ($_SESSION['prefsProEdition'] == 0) { ?>
	<div class="mb-3 row">
		<label for="" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_brewer; ?></strong></label>
		<div class="col-xs-12 col-sm-9 col-lg-10">
			<input type="text" readonly class="form-control-plaintext" id="brewerName" value="<?php echo $brewer_info[0]." ".$brewer_info[1]; ?>">
		</div>
	</div>
	<div class="mb-3 row">
		<label for="brewCoBrewer" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_cobrewer; ?></strong></label>
		<div class="col-xs-12 col-sm-9 col-lg-10">
			<input class="form-control" name="brewCoBrewer" id="brewCoBrewer" type="text" value="<?php if ($disable_fields) echo "Not Available"; if ($action == "edit") echo $row_log['brewCoBrewer']; ?>" placeholder="" <?php if ($disable_fields) echo "disabled";  ?>>
		</div>
	</div>
<?php } ?>
	
<?php if ($_SESSION['prefsProEdition'] == 1) { ?>
    <div class="mb-3 row">
    	<label for="" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_organization; ?></strong></label>
    	<div class="col-xs-12 col-sm-9 col-lg-10">
    		<input type="text" readonly class="form-control-plaintext" id="brewerOrganization" value="<?php echo $brewer_info[15]; ?>">
    	</div>
    </div>
<?php } ?>
    
    <!-- Entry Name -->
	<div id="entry-name">
		<div class="mb-3 row">
			<label for="brewName" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i><?php echo $label_entry_name; ?></strong></label>
			<div class="col-xs-12 col-sm-9 col-lg-10">
				<input class="form-control" name="brewName" id="brewName" type="text" value="<?php if ($disable_fields) echo "Not Available"; if ($action == "edit") echo $entry_name; ?>"
                <?php echo $entry_disabled; ?> placeholder="<?php echo $entry_disabled_placeholder; ?>" required autofocus>
				<div class="help-block mb-1 invalid-feedback text-danger"><?php echo $brew_text_011; ?></div>
				<div class="help-block mb-1"><?php echo $brew_text_001; ?></div>
			</div>
		</div>
    </div>

    <!-- Style -->
    <div class="mb-3 row">
    	<label for="" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i><?php if ($_SESSION['prefsStyleSet'] == "NWCiderCup") echo $style_set." ".$label_category; else echo $style_set." ".$label_style; ?></strong></label>
    	<div class="col-xs-12 col-sm-9 col-lg-10">
	        <select class="form-select mb-1 bootstrap-select" name="brewStyle" id="type" placeholder="<?php echo $label_select_style; ?>" title="<?php echo $label_select_style; ?>" required>
	        	<?php if ($action == "add") { ?>
	        	<option value=""><?php echo $label_select_style; ?></option>
	        	<?php } ?>

	        	<?php if (($action == "edit") && ($view == "00-A")) { ?>
				<option><?php echo $header_text_107; ?></option>
	        	<?php } 
	            if (($action == "add") && ($styles_disabled_count > 1)) echo "<option value='00-00'></option>";
	            echo $styles_dropdown; 
	        	?>
	        </select>
    		<div class="help-block mb-1 invalid-feedback text-danger"><?php echo $header_text_107; ?></div>
    		<div id="helpBlock" class="help-block mb-1">
    			<div class="mt-1" id="req-special" style="margin:0; padding:0">&spades; = <?php echo $brew_text_004; ?></div>
    			<div class="mt-1" id="req-strength" style="margin:0; padding:0">&diams; = <?php echo $brew_text_005; ?></div>
    			<div class="mt-1" id="req-carbonation" style="margin:0; padding:0">&clubs; = <?php echo $brew_text_006; ?></div>
    			<div class="mt-1" id="req-sweetness" style="margin:0; padding:0">&hearts; = <?php echo $brew_text_007; ?></div>
    		</div>
	        <?php if (($style_types_warning > 0) && ($_SESSION['userLevel'] > 1)) echo $entry_style_warning; ?>
    	</div>
    </div>

    <!-- Entry Requirements -->
	<div id="specialInfo" class="mb-3 row">
    	<label for="brewInfo" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $brew_text_009; ?> <span id="specialInfoName">Style Name</span></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
        <p class="form-control-static mb-2"><strong class="text-teal"><?php echo $brew_text_008; ?></strong></p> 
        <p class="form-control-static alert alert-teal" id="specialInfoText">Entry info goes here.</p>
        </p>
        </div>
    </div>
    
    <!-- Special Ingredients -->
	<div id="special" class="mb-3 row">
        <label for="brewInfo" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i><?php echo $label_required_info; ?></strong></label>
        	<div class="col-xs-12 col-sm-9 col-lg-10">
        		<textarea class="form-control" rows="8" name="brewInfo" id="brewInfo" maxlength="<?php echo $_SESSION['prefsSpecialCharLimit']; ?>" <?php if ($highlight_special) echo "autofocus"; elseif (($action == "edit") && ($special_required)) echo "autofocus"; ?>><?php echo $brewInfo; ?></textarea>
        	<div class="help-block mb-1 invalid-feedback text-danger"><?php echo $brew_text_010; ?></div>
            <div class="help-block mb-1 invalid-feedback text-danger"><?php if ((strpos($styleSet,"BABDB") !== false) && ($view_explodies[0] < 28)) echo $brew_text_027; ?></div>
            <div id="helpBlock" class="help-block mb-1"><p><?php echo $_SESSION['prefsSpecialCharLimit'].$label_character_limit; ?><span id="countInfo"><?php if ($action == "edit") echo mb_strlen($brewInfo); else echo "0" ?></span></p></div>
        </div>
    </div>
    
    <!-- Optional Information -->
    <div id="optional" class="mb-3 row">
        <label for="brewInfoOptional" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_optional_info; ?></strong></label>
        	<div class="col-xs-12 col-sm-9 col-lg-10">
        		<textarea class="form-control" rows="8" name="brewInfoOptional" id="brewInfoOptional" maxlength="<?php echo $_SESSION['prefsSpecialCharLimit']; ?>" placeholder="<?php echo $brew_text_028; ?>"><?php if ((isset($row_log['brewInfoOptional'])) && (!empty($row_log['brewInfoOptional']))) echo $row_log['brewInfoOptional']; ?></textarea>
            <div id="helpBlock" class="help-block"><p><?php echo $_SESSION['prefsSpecialCharLimit'].$label_character_limit; ?><span id="countInfoOptional">0</span></p></div>
        </div>
    </div>

    <!-- Lambic Carbonation -->
    <div id="carbLambic" class="mb-3 row">
    	<label for="carbLambic" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i><?php echo $label_carbonation; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="carbLambic" id="carbLambic1" value="Low Carbonation" <?php if ($action == "edit") echo $lambicCarbLow; ?>>
        		<label class="form-check-label" for="carbLambic1"><?php echo $label_low; ?></label>
        	</div>
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="carbLambic" id="carbLambic2" value="Medium Carbonation" <?php if ($action == "edit") echo $lambicCarbMed; ?>>
        		<label class="form-check-label" for="carbLambic2"><?php echo $label_med; ?></label>
        	</div>
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="carbLambic" id="carbLambic3" value="High Carbonation" <?php if ($action == "edit") echo $lambicCarbHigh; ?>>
        		<label class="form-check-label" for="carbLambic3"><?php echo $label_high; ?></label>
        	</div>
            <div>
	           <input class="display-d" type="radio" name="carbLambic" value="" style="display: none;">
	           <div class="help-block invalid-feedback text-danger"><?php echo $brew_text_056; ?></div>
	        </div>
        </div>
    </div>

    <!-- Lambic Sweetness -->
    <div id="sweetnessLambic" class="mb-3 row">
    	<label for="sweetnessLambic" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i><?php echo $label_sweetness; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="sweetnessLambic" id="sweetnessLambic1" value="Low/None Sweetness" <?php if ($action == "edit") echo $lambicSweetLow; ?>>
        		<label class="form-check-label" for="sweetnessLambic1"><?php echo $label_low; ?></label>
        	</div>
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="sweetnessLambic" id="sweetnessLambic2" value="Medium Sweetness" <?php if ($action == "edit") echo $lambicSweetMed; ?>>
        		<label class="form-check-label" for="sweetnessLambic2"><?php echo $label_med; ?></label>
        	</div>
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="sweetnessLambic" id="sweetnessLambic3" value="High Sweetness" <?php if ($action == "edit") echo $lambicSweetHigh; ?>>
        		<label class="form-check-label" for="sweetnessLambic3"><?php echo $label_high; ?></label>
        	</div>
            <div>
	           <input class="display-d" type="radio" name="sweetnessLambic" value="" style="display: none;">
	           <div class="help-block invalid-feedback text-danger"><?php echo $brew_text_057; ?></div>
	        </div>
        </div>
    </div>

    <!-- Saison Strength -->
    <div id="strengthSaison" class="mb-3 row">
    	<label for="strengthSaison" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i><?php echo $label_strength; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="strengthSaison" id="strengthSaison1" value="Table Strength" <?php echo $saisonTable; ?>>
        		<label class="form-check-label" for="strengthSaison1"><?php echo $label_table; ?></label>
        	</div>
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="strengthSaison" id="strengthSaison2" value="Standard Strength" <?php echo $saisonStandard; ?>>
        		<label class="form-check-label" for="strengthSaison2"><?php echo $label_standard; ?></label>
        	</div>
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="strengthSaison" id="strengthSaison3" value="Super Strength" <?php echo $saisonSuper; ?>>
        		<label class="form-check-label" for="strengthSaison3"><?php echo $label_super; ?></label>
        	</div>
            <div>
	           <input class="display-d" type="radio" name="strengthSaison" value="" style="display: none;">
	           <div class="help-block invalid-feedback text-danger"><?php echo $brew_text_058; ?></div>
	        </div>
        </div>
    </div>

    <!-- IPA Strength -->
    <div id="strengthIPA" class="mb-3 row">
    	<label for="strengthIPA" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i><?php echo $label_strength; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="strengthIPA" id="strengthIPA1" value="Session Strength" <?php if ($action == "edit") echo $IPASession; ?>>
        		<label class="form-check-label" for="strengthIPA1"><?php echo $label_session; ?></label>
        	</div>
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="strengthIPA" id="strengthIPA2" value="Standard Strength" <?php if ($action == "edit") echo $IPAStandard; ?>>
        		<label class="form-check-label" for="strengthIPA2"><?php echo $label_standard; ?></label>
        	</div>
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="strengthIPA" id="strengthIPA3" value="Double Strength" <?php if ($action == "edit") echo $IPADouble; ?>>
        		<label class="form-check-label" for="strengthIPA3"><?php echo $label_double; ?></label>
        	</div>
            <div>
	           <input class="display-d" type="radio" name="strengthIPA" value="" style="display: none;">
	           <div class="help-block invalid-feedback text-danger"><?php echo $brew_text_058; ?></div>
	        </div>
        </div>
    </div>

 	<!-- Biere de Garde Color -->
    <div id="BDGColor" class="mb-3 row">
    	<label for="BDGColor" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i><?php echo $label_color; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="BDGColor" id="BDGColor1" value="Blonde Color" <?php if ($action == "edit") echo $BDGBlonde; ?>>
        		<label class="form-check-label" for="BDGColor1"><?php echo $label_blonde; ?></label>
        	</div>
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="BDGColor" id="BDGColor2" value="Amber Color" <?php if ($action == "edit") echo $BDGAmber; ?>>
        		<label class="form-check-label" for="BDGColor2"><?php echo $label_amber; ?></label>
        	</div>
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="BDGColor" id="BDGColor3" value="Brown Color" <?php if ($action == "edit") echo $BDGBrown; ?>>
        		<label class="form-check-label" for="BDGColor3"><?php echo $label_brown; ?></label>
        	</div>
        	<div>
	           <input class="display-d" type="radio" name="BDGColor" value="" style="display: none;">
	           <div class="help-block invalid-feedback text-danger"><?php echo $brew_text_059; ?></div>
	        </div>
        </div>
    </div>

    <!-- Light or Dark Color -->
    <div id="darkLightColor" class="mb-3 row">
    	<label for="darkLightColor" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i><?php echo $label_color; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="darkLightColor" id="darkLightColor1" value="Pale Color" <?php if ($action == "edit") echo $darkLightPale; ?>>
        		<label class="form-check-label" for="darkLightColor1"><?php echo $label_pale; ?></label>
        	</div>
        	<div class="form-check form-check-inline">
        		<input class="form-check-input" type="radio" name="darkLightColor" id="darkLightColor2" value="Amber/Dark Color" <?php if ($action == "edit") echo $darkLightAmber; ?>>
        		<label class="form-check-label" for="darkLightColor2"><?php echo $label_dark; ?></label>
        	</div>
        	<div>
	           <input class="display-d" type="radio" name="darkLightColor" value="" style="display: none;">
	           <div class="help-block invalid-feedback text-danger"><?php echo $brew_text_059; ?></div>
	        </div>
        </div>
    </div>

    <!-- International Pale Lager Regional Variation -->
	<div id="regionalVariation" class="mb-3 row">
        <label for="regionalVar" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_regional_variation; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="regionalVar" name="regionalVar" type="text" value="<?php if (($action == "edit") && ($view == "02-A") && ($_SESSION['prefsStyleSet'] == "BJCP2021") && (!empty($row_log['brewInfo']))) echo $row_log['brewInfo']; ?>" placeholder="<?php echo $brew_text_041; ?>" maxlength="100">
        </div>
    </div>

    <!-- Strength -->
    <div id="strength">
    	<div id="fg-strength" class="mb-3 row">
            <label for="brewMead3" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i><?php echo $label_strength; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
            	<div class="form-check form-check-inline">
            		<input class="form-check-input" type="radio" name="brewMead3" value="Hydromel" id="brewMead3_0" <?php if (($action == "edit") && ($row_log['brewMead3'] == "Hydromel")) echo "CHECKED";  ?> />
            		<label class="form-check-label" for="brewMead3_0"><?php echo $label_hydromel; ?></label>
            	</div>
            	<div class="form-check form-check-inline">
            		<input class="form-check-input" type="radio" name="brewMead3" value="Standard" id="brewMead3_1" <?php if (($action == "edit") && ($row_log['brewMead3'] == "Standard")) echo "CHECKED";  ?> />
            		<label class="form-check-label" for="brewMead3_1"><?php echo $label_standard; ?></label>
            	</div>
            	<div class="form-check form-check-inline">
            		<input class="form-check-input" type="radio" name="brewMead3" value="Standard" id="brewMead3_2" <?php if (($action == "edit") && ($row_log['brewMead3'] == "Sack")) echo "CHECKED";  ?> />
            		<label class="form-check-label" for="brewMead3_2"><?php echo $label_sack; ?></label>
            	</div>
            	<div>
    	           <input class="display-d" type="radio" name="brewMead3" value="" style="display: none;">
    	           <div class="help-block invalid-feedback text-danger"><?php echo $brew_text_058; ?></div>
    	        </div>
            </div>
        </div>
    </div>

	<!-- Carbonation -->
    <div id="carbonation">
        <div id="fg-carbonation" class="mb-3 row">
            <label for="brewMead1" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i><?php echo $label_carbonation; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewMead1" value="Still" id="brewMead1_0" <?php if (($action == "edit") && ($row_log['brewMead1'] == "Still")) echo "CHECKED";  ?>/>
                	<label class="form-check-label" for="brewMead1_0"><?php echo $label_still; ?></label>
                </div>
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewMead1" value="Petillant" id="brewMead1_1"  <?php if (($action == "edit") && ($row_log['brewMead1'] == "Petillant")) echo "CHECKED";  ?>/>
                	<label class="form-check-label" for="brewMead1_1"><?php echo $label_petillant; ?></label>
                </div>
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewMead1" value="Sparkling" id="brewMead1_2"  <?php if (($action == "edit") && ($row_log['brewMead1'] == "Sparkling")) echo "CHECKED";  ?>/>
                	<label class="form-check-label" for="brewMead1_2"><?php echo $label_sparkling; ?></label>
                </div>
                <div>
    	           <input class="display-d" type="radio" name="brewMead1" value="" style="display: none;">
    	           <div class="help-block invalid-feedback text-danger"><?php echo $brew_text_056; ?></div>
    	        </div>
            </div>
        </div>
     </div>

     <!-- Sweetness -->
     <div id="sweetness-mead">
    	<div id="fg-sweetness" class="mb-3 row">	
            <label for="brewMead2" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i><?php echo $label_sweetness; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
            	<div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewMead2-mead" value="Dry" id="brewMead2_0" <?php if (($action == "edit") && ($row_log['brewMead2'] == "Dry")) echo "CHECKED";  ?> />
                	<label class="form-check-label" for="brewMead2_0"><?php echo $label_dry; ?></label>
                </div>
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewMead2-mead" value="Medium Dry" id="brewMead2_1" <?php if (($action == "edit") && ($row_log['brewMead2'] == "Medium Dry")) echo "CHECKED";  ?>/>
                	<label class="form-check-label" for="brewMead2_1"><?php echo $label_med_dry; ?></label>
                </div>
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewMead2-mead" value="Medium" id="brewMead2_2"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Medium")) echo "CHECKED";  ?>/>
                	<label class="form-check-label" for="brewMead2_2"><?php echo $label_med; ?></label>
                </div>
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewMead2-mead" value="Medium Sweet" id="brewMead2_3"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Medium Sweet")) echo "CHECKED";  ?>/>
                	<label class="form-check-label" for="brewMead2_3"><?php echo $label_med_sweet; ?></label>
                </div>
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewMead2-mead" value="Sweet" id="brewMead2_4" <?php if (($action == "edit") && ($row_log['brewMead2'] == "Sweet")) echo "CHECKED";  ?>/>
                	<label class="form-check-label" for="brewMead2_4"><?php echo $label_sweet; ?></label>
                </div>
                <div>
    	           <input class="display-d" type="radio" name="brewMead2-mead" value="" style="display: none;">
    	           <div class="help-block invalid-feedback text-danger"><?php echo $brew_text_057; ?></div>
    	        </div>
            </div>
        </div>
    </div>

    <div id="sweetness-cider">
    	<div id="fg-sweetness" class="mb-3 row">
            <label for="brewMead2" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i><?php echo $label_sweetness; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
            	<div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewMead2-cider" value="Dry" id="brewMead2_5" <?php if (($action == "edit") && ($row_log['brewMead2'] == "Dry")) echo "CHECKED";  ?> />
                	<label class="form-check-label" for="brewMead2_5"><?php echo $label_dry; ?></label>
                </div>
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewMead2-cider" value="Semi-Dry" id="brewMead2_6"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Semi-Dry")) echo "CHECKED";  ?>/> 
                	<label class="form-check-label" for="brewMead2_6"><?php echo $label_semi_dry; ?></label>
                </div>
                <?php if ($_SESSION['prefsStyleSet'] != "NWCiderCup") { ?>
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewMead2-cider" value="Medium" id="brewMead2_7" <?php if (($action == "edit") && ($row_log['brewMead2'] == "Medium")) echo "CHECKED";  ?>/>
                	<label class="form-check-label" for="brewMead2_7"><?php echo $label_med; ?></label>
                </div>
            	<?php } ?>
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewMead2-cider" value="Semi-Sweet" id="brewMead2_8" <?php if (($action == "edit") && ($row_log['brewMead2'] == "Semi-Sweet")) echo "CHECKED";  ?>/>
                	<label class="form-check-label" for="brewMead2_8"><?php echo $label_semi_sweet; ?></label>
                </div>
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewMead2-cider" value="Sweet" id="brewMead2_9" <?php if (($action == "edit") && ($row_log['brewMead2'] == "Sweet")) echo "CHECKED";  ?>/>
                	<label class="form-check-label" for="brewMead2_9"><?php echo $label_sweet; ?></label>
                </div>
                <div>
    	           <input class="display-d" type="radio" name="brewMead2-cider" value="" style="display: none;">
    	           <div class="help-block invalid-feedback text-danger"><?php echo $brew_text_057; ?></div>
    	        </div>
            </div>
        </div>
    </div>
    
    <!-- Pouring Instrucitons -->
    <div id="specify-pouring">
		<div class="mb-3 row">
		    <label for="brewPouringInst" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_pouring; ?></strong></label>
		    <div class="col-xs-12 col-sm-9 col-lg-10">
		        <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewPouringInst" id="brewPouringInst-Fast" value="<?php echo $label_fast; ?>" <?php if (($action == "edit") && ($pouring_arr['pouring'] == $label_fast)) echo "CHECKED"; ?>>
                	<label class="form-check-label" for="brewPouringInst-Fast"><?php echo $label_fast; ?></label>
                </div>
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewPouringInst" id="brewPouringInst-Normal" value="<?php echo $label_normal; ?>" <?php if ($action == "add") echo "CHECKED"; if (($action == "edit") && ($pouring_arr['pouring'] == $label_normal)) echo "CHECKED"; ?>>
                	<label class="form-check-label" for="brewPouringInst-Normal"><?php echo $label_normal; ?></label>
                </div>
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewPouringInst" id="brewPouringInst-Slow" value="<?php echo $label_slow; ?>" <?php if (($action == "edit") && ($pouring_arr['pouring'] == $label_slow)) echo "CHECKED"; ?>>
                	<label class="form-check-label" for="brewPouringInst-Slow"><?php echo $label_slow; ?></label>
                </div>
                <div>
    	           <input class="display-d" type="radio" name="brewPouringInst" value="" style="display: none;">
    	           <div class="help-block invalid-feedback text-danger"><?php echo $brew_text_047; ?></div>
    	        </div>
			</div>
		</div>
		<div class="mb-3 row">
		    <label for="brewPouringRouse" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_rouse_yeast; ?></strong></label>
		    <div class="col-xs-12 col-sm-9 col-lg-10">
		    	<div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewPouringRouse" id="brewPouringRouse-Yes" value="<?php echo $label_yes; ?>" <?php if (($action == "edit") && ($pouring_arr['pouring_rouse'] == $label_yes)) echo "CHECKED"; ?>>
                	<label class="form-check-label" for="brewPouringRouse-Yes"><?php echo $label_yes; ?></label>
                </div>
                <div class="form-check form-check-inline">
                	<input class="form-check-input" type="radio" name="brewPouringRouse" id="brewPouringRouse-No" value="<?php echo $label_no; ?>" <?php if (($action == "edit") && ($pouring_arr['pouring_rouse'] == $label_no)) echo "CHECKED"; ?>>
                	<label class="form-check-label" for="brewPouringRouse-No"><?php echo $label_no; ?></label>
                </div>
                <div>
    	           <input class="display-d" type="radio" name="brewPouringRouse" value="" style="display: none;">
    	           <div class="help-block invalid-feedback text-danger"><?php echo $brew_text_048; ?></div>
    	        </div>
			</div>
		</div>
		<div class="mb-3 row">
	        <label for="brewPouringNotes" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_pouring_notes; ?></strong></label>
	        <div class="col-xs-12 col-sm-9 col-lg-10">
	            <input class="form-control" name="brewPouringNotes" id="brewPouringNotes" type="text" maxlength="255" value="<?php if (($action == "edit") && (isset($pouring_arr['pouring_notes']))) echo $pouring_arr['pouring_notes']; ?>">
	            <div class="help-block"><?php echo $brew_text_049; ?></div>
	        </div>
	    </div>
	</div>

    <!-- ABV (Optional for all except NW Cider Cup) -->
	<div class="mb-3 row">
        <label for="brewABV" class="col-xs-12 col-sm-3 col-lg-2 col-form-label <?php if ($_SESSION['prefsStyleSet'] == "NWCiderCup") echo "text-teal"; ?>"><strong><?php if ($_SESSION['prefsStyleSet'] == "NWCiderCup") echo "<i class=\"fa fa-star me-1\"></i>"; echo $label_abv; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" name="brewABV" id="brewABV" type="number" min="0" step=".01" value="<?php if ($disable_fields) echo "Not Available"; if ($action == "edit") echo $row_log['brewABV']; ?>" placeholder="" <?php if ($disable_fields) echo "disabled "; if ($_SESSION['prefsStyleSet'] == "NWCiderCup") echo "required "; ?>>
            <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $brew_text_042; ?></div>
            <div class="help-block"><?php echo $brew_text_043; ?></div>
        </div>
    </div>

<?php if ($_SESSION['prefsStyleSet'] == "NWCiderCup") { ?>
    <div class="mb-3 row">
        <label for="brewPackaging" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i>Packaging</strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
        	<section>
        		<!--
        		<div class="form-check form-check-inline">
	        		<input class="form-check-input" type="radio" name="brewPackaging" id="brewPackaging_0" value="375" <?php if (($action == "edit") && ($row_log['brewPackaging'] == "375")) echo "CHECKED"; ?> />
	        		<label class="form-check-label" for="brewPackaging_0">375 ml <?php echo $label_bottle; ?></label>
	        	</div>
        		-->
	        	<div class="form-check form-check-inline">
	        		<input class="form-check-input" type="radio" name="brewPackaging" id="brewPackaging_1" value="750" <?php if (($action == "edit") && ($row_log['brewPackaging'] == "750")) echo "CHECKED"; ?> required />
	        		<label class="form-check-label" for="brewPackaging_1">750 ml <?php echo $label_bottle; ?></label>
	        	</div>
	        	<div class="form-check form-check-inline">
	        		<input class="form-check-input" type="radio" name="brewPackaging" id="brewPackaging_2" value="500" <?php if (($action == "edit") && ($row_log['brewPackaging'] == "500")) echo "CHECKED"; ?> />
	        		<label class="form-check-label" for="brewPackaging_2">500 ml <?php echo $label_bottle; ?></label>
	        	</div>
	            <div class="form-check form-check-inline">
	        		<input class="form-check-input" type="radio" name="brewPackaging" id="brewPackaging_5" value="Other-Bottle" <?php if (($action == "edit") && ($row_log['brewPackaging'] == "Other-Bottle")) echo "CHECKED"; ?> />
	        		<label class="form-check-label" for="brewPackaging_5"><?php echo $label_bottle." - ".$label_other_size; ?></label>
	        	</div>
	        </section>
	        <section>
	        	<div class="form-check form-check-inline">
	        		<input class="form-check-input" type="radio" name="brewPackaging" id="brewPackaging_6" value="19.2" <?php if (($action == "edit") && ($row_log['brewPackaging'] == "19.2")) echo "CHECKED"; ?> />
	        		<label class="form-check-label" for="brewPackaging_6">19.2 ounce <?php echo $label_can; ?></label>
	        	</div>
	        	<div class="form-check form-check-inline">
	        		<input class="form-check-input" type="radio" name="brewPackaging" id="brewPackaging_7" value="16" <?php if (($action == "edit") && ($row_log['brewPackaging'] == "16")) echo "CHECKED"; ?> />
	        		<label class="form-check-label" for="brewPackaging_7">116ounce <?php echo $label_can; ?></label>
	        	</div>
	        	<div class="form-check form-check-inline">
	        		<input class="form-check-input" type="radio" name="brewPackaging" id="brewPackaging_8" value="12" <?php if (($action == "edit") && ($row_log['brewPackaging'] == "12")) echo "CHECKED"; ?> />
	        		<label class="form-check-label" for="brewPackaging_8">12 ounce <?php echo $label_can; ?></label>
	        	</div>
	        	<div class="form-check form-check-inline">
	        		<input class="form-check-input" type="radio" name="brewPackaging" id="brewPackaging_9" value="Other-Can" <?php if (($action == "edit") && ($row_log['brewPackaging'] == "Other-Can")) echo "CHECKED"; ?> />
	        		<label class="form-check-label" for="brewPackaging_9"><?php echo $label_can." - ".$label_other_size; ?></label>
	        	</div>
	        </section>
        </div>
    </div>

    <!-- Juice Source -->
	<div class="mb-3 row">
        <label for="brewJuiceSource" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-fw fa-sm fa-star"></i><?php echo $label_juice_source; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
	        <!-- Input Here -->
	        <select class="form-select selectpicker" multiple name="brewJuiceSource[]" id="brewJuiceSource" data-live-search="false" data-size="10" data-width="auto" data-show-tick="true" data-header="<?php echo ucwords(str_replace(".","",$brew_text_045)); ?>" title="<?php echo ucwords(str_replace(".","",$brew_text_045)); ?>" required>
				<?php echo $juice_select_dropdown; ?>
	        </select>
	        <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $brew_text_045; ?></div>
	        <div class="help-block"><?php echo $brew_text_054; ?></div>
        </div>
    </div>

    <div id="brewJuiceSourceOther" class="mb-3 row">
        <label for="other-juice-source" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_fruit_add_source; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <select class="form-select selectpicker" multiple name="brewJuiceSourceOther[]" id="brewJuiceSourceOther" data-live-search="false" data-size="10" data-width="auto" data-show-tick="true" data-header="<?php echo ucwords(str_replace(".","",$brew_text_045)); ?>" title="<?php echo ucwords(str_replace(".","",$brew_text_045)); ?>">
    			<?php echo $juice_select_dropdown_other; ?>
    		</select>
    		<div class="help-block mb-1 invalid-feedback text-danger"><?php echo $brew_text_045; ?></div>
            <div class="help-block"><?php echo $brew_text_046; ?></div>
        </div>
    </div>

    <!-- Sweetness Level -->
	<div class="mb-3 row">
        <label for="brewSweetnessLevel" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_final_gravity; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" name="brewSweetnessLevel" id="brewSweetnessLevel" type="number" min="0" step=".001" value="<?php if ($disable_fields) echo "Not Available"; if ($action == "edit") echo $row_log['brewSweetnessLevel']; ?>" placeholder="" <?php if ($disable_fields) echo "disabled "; ?>>
            <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $brew_text_044; ?></div>
        </div>
    </div>
<?php } // end if ($_SESSION['prefsStyleSet'] == "NWCiderCup") ?>

<?php if (($_SESSION['prefsSpecific'] == 0) && ($_SESSION['prefsStyleSet'] != "BA")) { ?>
    <!-- Brewer's Specifics -->
    <div class="mb-3 row">
        <label for="brewComments" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_brewer_specifics; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <textarea rows="6" class="form-control" name="brewComments" id="brewComments" maxlength="<?php echo $_SESSION['prefsSpecialCharLimit']; ?>" placeholder="<?php echo $brew_text_012; ?>"><?php if ($action == "edit") echo $row_log['brewComments']; ?></textarea>
            <div id="helpBlock" class="help-block">
            	<div class="mt-1"><strong class="text-danger"><?php echo $brew_text_013; ?></strong></div>
                <div class="mt-1"><strong class="text-primary"><?php echo $brew_text_014; ?></strong></div>
                <div class="mt-1"><?php echo $_SESSION['prefsSpecialCharLimit'].$label_character_limit; ?><span id="countComments"><?php if ($action == "edit") echo mb_strlen($row_log['brewComments']); else echo "0" ?></span></div>
            </div>
        </div>
    </div>
<?php } ?>

	<!-- Possible Allergens -->
    <div class="mb-3 row">
	    <label for="" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_possible_allergens; ?></strong></label>
	    <div class="col-xs-12 col-sm-9 col-lg-10">
	    	<div class="form-check form-check-inline">
	    		<input class="form-check-input" type="radio" name="possible-allergens" id="possAllergens_0" value="1" <?php if (($action == "edit") && (!empty($row_log['brewPossAllergens']))) echo "CHECKED"; ?> />
	    		<label class="form-check-label" for="possAllergens_0"><?php echo $label_yes; ?></label>
	    	</div>
	    	<div class="form-check form-check-inline">
	    		<input class="form-check-input" type="radio" name="possible-allergens" id="possAllergens_1" value="0" <?php if (($action == "edit") && (empty($row_log['brewPossAllergens']))) echo "CHECKED"; if ($action == "add") echo "CHECKED"; ?> />
	    		<label class="form-check-label" for="possAllergens_1"><?php echo $label_no; ?></label>
	    	</div>
	        <div id="helpBlockAllergens" class="help-block"><?php if ($_SESSION['prefsStyleSet'] == "NWCiderCup") $brew_text_040 = ""; echo sprintf("<strong>%s</strong> %s",$brew_text_038,$brew_text_040);  ?></div>
	    </div>
	</div>

	<div class="mb-3 row" id="possible-allergens">
		<label for="possible_allergens-list" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"></label>
		<div class="col-xs-12 col-sm-9 col-lg-10" id="possible_allergens-list">
			<input type="text" class="form-control" name="brewPossAllergens" value="<?php if (($action == "edit") && (!empty($row_log['brewPossAllergens']))) echo $row_log['brewPossAllergens']; ?>">
			<div class="help-block mb-1 invalid-feedback text-danger"><?php echo $brew_text_039; ?>.</div>
		</div>
	</div>

<?php if (($action == "add") && ($remaining_entries > 1)) { ?>
	<!-- Return to Add Another Entry -->
	<div class="mb-3 row">
		<div class="col-xs-12 col-sm-3 col-lg-2"></div>
		<div class="col-xs-12 col-sm-9 col-lg-10">
			<input class="form-check-input" type="checkbox" name="return-to-add" value="1" id="return-to-add-entry"> 
			<label class="form-check-label" for="return-to-add-entry"><?php echo $brew_text_055; ?></label>
		</div>
	</div>
<?php } ?>

<?php if ((($filter == "admin") || ($filter == "default")) && ($bid != "default") && ($_SESSION['userLevel'] < 2)) { ?>
	
	<!--
	
	<div class="mb-3 row">
	    <label for="brewPaid" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_paid; ?></strong></label>
	    <div class="col-xs-12 col-sm-9 col-lg-10">
	    	<div class="form-check form-check-inline">
	    		<input class="form-check-input" type="radio" name="brewPaid" id="brewPaid_0" value="1" <?php if (($row_log) && ($row_log['brewPaid'] == 1)) echo "CHECKED"; ?> />
	    		<label class="form-check-label" for="brewPaid_0"><?php echo $label_yes; ?></label>
	    	</div>
	    	<div class="form-check form-check-inline">
	    		<input class="form-check-input" type="radio" name="brewPaid" id="brewPaid_1" value="" <?php if (($action == "edit") && ((empty($row_log['brewPaid'])) || ($row_log['brewPaid'] == 0))) echo "CHECKED"; if ($action == "add") echo "CHECKED"; ?> />
	    		<label class="form-check-label" for="brewPaid_1"><?php echo $label_no; ?></label>
	    	</div>
	    </div>
	</div>

	<div class="mb-3 row">
	    <label for="brewReceived" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_received; ?></strong></label>
	    <div class="col-xs-12 col-sm-9 col-lg-10">
	    	<div class="form-check form-check-inline">
	    		<input class="form-check-input" type="radio" name="brewReceived" id="brewReceived_0" value="1" <?php if (($row_log) && ($row_log['brewReceived'] == 1)) echo "CHECKED"; ?> />
	    		<label class="form-check-label" for="brewReceived_0"><?php echo $label_yes; ?></label>
	    	</div>
	    	<div class="form-check form-check-inline">
	    		<input class="form-check-input" type="radio" name="brewReceived" id="brewReceived_1" value="" <?php if (($action == "edit") && ((empty($row_log['brewReceived'])) || ($row_log['brewReceived'] == 0))) echo "CHECKED"; if ($action == "add") echo "CHECKED"; ?> />
	    		<label class="form-check-label" for="brewReceived_1"><?php echo $label_no; ?></label>
	    	</div>
	    </div>
	</div>

	<div class="mb-3 row">
        <label for="brewAdminNotes" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong>Admin Notes</strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <textarea rows="6" class="form-control" name="brewAdminNotes" id="brewAdminNotes" maxlength="<?php echo $_SESSION['prefsSpecialCharLimit']; ?>" placeholder=""><?php if ($action == "edit") echo $row_log['brewAdminNotes']; ?></textarea>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="brewStaffNotes" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong>Staff Notes</strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <textarea rows="6" class="form-control" name="brewStaffNotes" id="brewStaffNotes" maxlength="<?php echo $_SESSION['prefsSpecialCharLimit']; ?>" placeholder=""><?php if ($action == "edit") echo $row_log['brewStaffNotes']; ?></textarea>
        </div>
    </div>

    <div class="mb-3 row">
		<label for="brewBoxNum" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong>Box/Location</strong></label>
		<div class="col-xs-12 col-sm-9 col-lg-10">
			<input type="text" class="form-control" placeholder="" name="brewBoxNum" value="<?php if ($action == "edit") echo $row_log['brewBoxNum']; ?>">
		</div>
	</div>

	-->

<?php } else { ?>
	
	<input type="hidden" name="brewAdminNotes" value="<?php if (($action == "edit") && (isset($row_log['brewAdminNotes']))) echo $row_log['brewAdminNotes']; ?>">
	<input type="hidden" name="brewStaffNotes" value="<?php if (($action == "edit") && (isset($row_log['brewStaffNotes']))) echo $row_log['brewStaffNotes']; ?>">
	<input type="hidden" name="brewBoxNum" value="<?php if (($action == "edit") && (isset($row_log['brewBoxNum']))) echo $row_log['brewBoxNum']; ?>">
<?php } if ($bid == "default") { ?>
	<input type="hidden" name="brewPaid" value="<?php echo $brewPaid; ?>">
<?php } ?>
	<input type="hidden" name="brewConfirmed" value="1">
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
	<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
	<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=list","default",$msg,$id); ?>">
<?php } ?>

	<div class="bcoem-admin-element d-print-none">
		<div class="mb-3 mt-5 row">
			<div class="col-xs-12 col-sm-3 col-lg-2"></div>
		    <div class="col-xs-12 col-sm-9 col-lg-10 d-grid">
				<button name="submit" type="submit" class="btn btn-lg btn-primary <?php if ($disable_fields) echo "disabled"; ?>" ><?php echo $submit_text; ?><i class="ms-2 fa fa-fw fa-<?php echo $submit_icon; ?>"></i></button>
			</div>
		</div>
	</div>

</form>
<?php } // end adding and editing allowed ?>

<script type="text/javascript">
    var style_set = "<?php echo $_SESSION['prefsStyleSet']; ?>";
    var req_pouring = <?php echo json_encode($req_pouring); ?>;
    var req_special_ing_styles = <?php echo json_encode($req_special_ing_styles); ?>;
    var req_strength_styles = <?php echo json_encode($req_strength_styles); ?>;
    var req_sweetness_styles = <?php echo json_encode($req_sweetness_styles); ?>;
    var req_carb_styles = <?php echo json_encode($req_carb_styles); ?>;
    var cider_sweetness_custom_styles = <?php echo json_encode($cider_sweetness_custom_styles); ?>;
    var mead_sweetness_custom_styles = <?php echo json_encode($mead_sweetness_custom_styles); ?>;
    var optional_info_styles = <?php echo json_encode($optional_info_styles); ?>;
    var edit_style = "<?php echo ltrim($view,"0"); ?>";
    var label_this_style = "<?php echo $label_this_style; ?>";
    var req_special_ing_style_info = <?php echo json_encode($styles_entry_text, JSON_UNESCAPED_SLASHES); ?>;
    <?php if (($action == "edit") && (!empty($row_log['brewPossAllergens']))) { ?>
    var possible_allergens = "<?php echo $row_log['brewPossAllergens']; ?>";
    <?php } else { ?>
    possible_allergens = null;      
    <?php } ?>
</script>
<script src="<?php echo $js_add_edit_entry_url; ?>"></script>