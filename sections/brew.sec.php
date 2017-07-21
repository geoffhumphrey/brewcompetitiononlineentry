<?php 
/**
 * Module:      brew.sec.php
 * Description: This module houses the functionality for users to add/edit individual competition
 *              entries - references the "brewing" database table.
 *
 */
include (DB.'styles.db.php'); 
include (DB.'entries.db.php');
if (strpos($styleSet,"BABDB") !== false) include (INCLUDES.'ba_constants.inc.php');
include (DB.'styles_special.db.php');

// Disable fields trigger
if ((($action == "add") && ($remaining_entries <= 0) && ($_SESSION['userLevel'] == 2)) || (($action == "add") && ($entry_window_open == "2") && ($_SESSION['userLevel'] == 2))) $disable_fields = TRUE; else $disable_fields = FALSE;
	
// Specific code for Style select
if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {
	$style_set = "Brewers Association";
}
else $style_set = str_ireplace("2"," 2",$_SESSION['prefsStyleSet']);
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

$all_special_ing_styles = array();
if (is_array($special_beer)) $all_special_ing_styles = array_merge($all_special_ing_styles,$special_beer);
if (is_array($carb_str_sweet_special)) $all_special_ing_styles = array_merge($all_special_ing_styles,$carb_str_sweet_special);
if (is_array($spec_sweet_carb_only)) $all_special_ing_styles = array_merge($all_special_ing_styles,$spec_sweet_carb_only);
if (is_array($spec_carb_only)) $all_special_ing_styles = array_merge($all_special_ing_styles,$spec_carb_only);
if (strpos($styleSet,"BABDB") !== false) $all_special_ing_styles = array_merge($all_special_ing_styles,$ba_special);
$all_special_ing_styles = array_unique($all_special_ing_styles);

$all_special_ing_styles_info = array();
if (is_array($special_beer_info)) $all_special_ing_styles_info = array_merge($all_special_ing_styles_info,$special_beer_info);
if (is_array($carb_str_sweet_special_info)) $all_special_ing_styles_info = array_merge($all_special_ing_styles_info,$carb_str_sweet_special_info);
if (is_array($spec_sweet_carb_only_info)) $all_special_ing_styles_info = array_merge($all_special_ing_styles_info,$spec_sweet_carb_only_info);

$all_special_ing_styles_info = array_unique($all_special_ing_styles_info);


$proEdition = FALSE;
if ($_SESSION['prefsProEdition'] == 1) $proEdition = TRUE;

$adminUser = FALSE;
if ($_SESSION['userLevel'] <= 1) $adminUser = TRUE;

$adminUserAddDisable = FALSE;

if (($_SESSION['userLevel'] == 2) && ($action == "edit")) {
	
	$user_entries = "";
	
	// Check whether user is "authorized" to edit the entry in DB
	$query_brews = sprintf("SELECT id FROM $brewing_db_table WHERE brewBrewerId = '%s'", $_SESSION['user_id']);
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
// Registration and entry windows closed
if (($registration_open == 2) && ($entry_window_open == 2) && ($_SESSION['userLevel'] == 2)) { 
	$add_entry_disable = TRUE;
	$edit_entry_disable = TRUE; 
}

if (($proEdition) && (!isset($_SESSION['brewerBreweryName'])) && ($adminUser) && ($filter == "admin")) {
	$add_entry_disable = TRUE;
	$adminUserAddDisable = TRUE;
}

if (($add_entry_disable) || ($edit_entry_disable))  echo "<p class=\"lead\">".$alert_text_083."</p>";
//if ($add_entry_disable) echo "<p>Add disabled.</p>";
//if ($edit_entry_disable) echo "<p>Editing disabled.</p>";
if (($adminUser) && ($adminUserAddDisable)) echo "<p>".$brew_text_029."</p>";
elseif (($add_entry_disable) && (!$edit_entry_disable))  {
	if (($proEdition) && (!isset($_SESSION['brewerBreweryName']))) { 
		echo "<p class=\"lead\">".$alert_text_082."</p>";
		if ($adminUser) echo "<p class=\"lead\"><small>".$alert_text_084."</small></p>";
	}
	else echo "<p class=\"lead\">".$alert_text_029."</p>";
}

else {	
	// Decalre variables
	if ($_SESSION['prefsStyleSet'] == "BJCP2008") $beer_end = 23;
	if ($_SESSION['prefsStyleSet'] == "BJCP2015") $beer_end = 34;
	
	if ($action == "edit") $collapse_icon = "fa-plus-circle";
	else $collapse_icon = "fa-pencil";
	
	if (($filter == "admin") || ($filter == "default")) $brewer_id = $_SESSION['user_id']; else $brewer_id = $filter; 
	$brewer_info = brewer_info($brewer_id);
	$brewer_info = explode("^",$brewer_info);
	if (($action == "edit") && (!empty($row_log['brewInfo']))) $brewInfo = $row_log['brewInfo'];
	
	$brewPaid = 0;
	
	// Check to see if the amount the entrant has paid is equal to or exceeds the entry fee cap (if defined)
	if (isset($_SESSION['contestEntryCap'])) {
		// if so, mark this entry as paid
		if ($total_paid_entry_fees >= $_SESSION['contestEntryCap']) $brewPaid = 1;
	}
	
	if ($_SESSION['contestEntryFee'] == 0) $brewPaid = 1;
	if ($action == "edit") $brewPaid = $row_log['brewPaid'];
	
	// Define custom functions
	function display_array_content_style($arrayname,$method,$base_url) {
		include (LANG.'language.lang.php'); 
		$a = "";
		sort($arrayname);
		while(list($key, $value) = each($arrayname)) {
			if (is_array($value)) {
				$c = display_array_content($value,'');
				$d = ltrim($c,"0");
				$d = str_replace("-","",$c);
				$a .= "<a href=\"#\" data-toggle=\"modal\" data-target=\"#".$d."\">".$d."</a>";
				
			}
			else {
				
				$value = explode("|",$value);
				
				$e = str_replace("-","",$value[0]);
				$e = ltrim($e,"0");
				$a .= sprintf("<a href=\"#\" data-toggle=\"modal\" data-target=\"#".$value[0]."\" data-tooltip=\"true\" title=\"%s ".$e.": ".$value[1]."\">".$e."</a>",$brew_text_000);
				//$a .= "<a id='modal_window_link' href='".$base_url."output/print.output.php?section=styles&amp;view=".$value."&amp;tb=true'>".$e."</a>"; 
			}
			if ($method == "1") $a .= "";
			if ($method == "2") $a .= "&nbsp;&nbsp;";
			if ($method == "3") $a .= ", ";
		}
		$b = rtrim($a, "&nbsp;&nbsp;");
		$b = rtrim($a, ", ;");
		$b = rtrim($b, "  ");
		
		return $b;
	}
	
	function admin_relocate($user_level,$go,$referrer) {
		$list = FALSE;
		if (strstr($referrer,"list")) $list = TRUE;
		if (strstr($referrer,"entries")) $list = FALSE;
		if (strstr($referrer,"0-A")) $list = FALSE;
		if (($user_level <= 1) && ($go == "entries") && (!$list)) $output = "admin";
		elseif (($user_level <= 1) && ($go == "entries") && ($list)) $output = "list";
		else $output = "list";
		return $output;
	}
	
	// BA
	if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {
		
		$styles_options = "";
		
		$styleSet_explodies = explode("|",$_SESSION['prefsStyleSet']);
		$style_explodies = explode(",",$styleSet_explodies[2]);
		
		$ba_styles_accepted[] = "";
		
		foreach ($_SESSION['styles'] as $styles => $stylesData) {
			
			if (is_array($stylesData) || is_object($stylesData)) {
				
				foreach ($stylesData as $key => $style) { 
					
					if (in_array($style['id'],$style_explodies)) {
					
						   $ba_styles_accepted[] .= $style['name']."|".$style['id']."|".$style['category']['name']."|".$style['category']['id'];

					}
				
				} // end foreach ($stylesData as $data => $style)
				
			} // end if (is_array($stylesData) || is_object($stylesData))
			
		} // end foreach ($_SESSION['styles'] as $styles => $stylesData)
			
		// Check for any custom styles 
		if ($totalRows_styles_custom > 0) {
	
			do {
				
				if ($row_styles['brewStyleActive'] == "Y") {
					$ba_styles_accepted[] .= $row_styles['brewStyle']." (".$label_custom_style.")"."|".$row_styles['id']."|Custom|".$row_styles['brewStyleGroup']."|".$row_styles['brewStyleReqSpec']."|".$row_styles['brewStyleStrength']."|".$row_styles['brewStyleCarb']."|".$row_styles['brewStyleSweet'];
				}

			} while($row_styles = mysqli_fetch_assoc($styles_custom));
		
		}
		
		sort($ba_styles_accepted);
		
		foreach ($ba_styles_accepted as $value) {
			
			if (!empty($value)) {
				
				$ba_style_explodies = explode("|",$value);
				if ($ba_style_explodies[2] == "Custom") $style_value = $ba_style_explodies[3]."-A";
				else $style_value = $ba_style_explodies[3]."-".$ba_style_explodies[1];
				$style_value_lookup = $ba_style_explodies[3]."-".$ba_style_explodies[1]."-".$ba_style_explodies[2]."-".$ba_style_explodies[0];
				/*
				if ($ba_style_explodies[2] == "Hybrid/mixed Beer") $categoryName = "Hybrid/Mixed Beer"; 
				elseif ($ba_style_explodies[2] == "European-germanic Lager") $categoryName = "European-Germanic Lager";
				else $categoryName = ucwords($ba_style_explodies[2]);
				*/
				
				$selected_disabled = "";
							
				// Determine if the subcategory limit has been reached for various conditions
				if ($_SESSION['userLevel'] == 2) $subcat_limit = limit_subcategory($style_value_lookup,$user_subcat_limit,$user_subcat_limit_exception,$row_limits['prefsUSCLEx'],$_SESSION['user_id']);
				elseif (($_SESSION['userLevel'] <= 1) && ($filter != "admin") && ($id == "default")) $subcat_limit = limit_subcategory($style_value_lookup,$user_subcat_limit,$user_subcat_limit_exception,$row_limits['prefsUSCLEx'],$filter);
				elseif (($_SESSION['userLevel'] <= 1) && ($filter != "admin") && ($id != "default")) $subcat_limit = limit_subcategory($style_value_lookup,$user_subcat_limit,$user_subcat_limit_exception,$row_limits['prefsUSCLEx'],$row_log['brewBrewerID']);
				elseif (($_SESSION['userLevel'] <= 1) && ($filter == "admin")) $subcat_limit = limit_subcategory($style_value_lookup,$user_subcat_limit,$user_subcat_limit_exception,$row_limits['prefsUSCLEx'],$_SESSION['user_id']);
				
				// Build selected/disabled variable
				if ($action == "edit") { 
				   if (sprintf('%02d',$ba_style_explodies[1]) == $row_log['brewSubCategory']) $selected_disabled = "SELECTED"; 
				   if (sprintf('%02d',$ba_style_explodies[1]) != $row_log['brewSubCategory']) $selected_disabled = $subcat_limit; 
				} 
				if (($action == "add") && ($remaining_entries > 0) && (!$disable_fields) && ($_SESSION['userLevel'] == 2)) $selected_disabled = $subcat_limit; 
				elseif (($disable_fields) && ($view != $style_value)) $selected_disabled = "DISABLED";		
				
				// Determine if style should be selected if editing
				$selected = "";
				
				if (($action == "edit") && ($view == $style_value)) $selected = " SELECTED";
				
				// Build display name for drop-down
				$selection = "";
				// $selection .= $style['id']." ";
				$selection .= $ba_style_explodies[0];
				// $selection .= "(".$categoryName.") ";
				
				// Add indicators for special ingredients, strength, carbonation, sweetness
				if ($ba_style_explodies[2] == "Custom") {
					if ($ba_style_explodies[4] == 1) $selection .= " &spades;";
					if ($ba_style_explodies[5] == 1) $selection .= " &diams;";
					if ($ba_style_explodies[6] == 1) $selection .= " &clubs;";
					if ($ba_style_explodies[7] == 1) $selection .= " &hearts;";
				}
				
				else {
					if (in_array($style_value,$ba_special)) $selection .= " &spades;";
					if (in_array($style_value,$ba_strength)) $selection .= " &diams;";
					if (in_array($style_value,$ba_carb)) $selection .= " &clubs;";
					if (in_array($style_value,$ba_sweetness)) $selection .= " &hearts;";	
				}			
				
				if ($view != $style_value) {
					if (($selected_disabled == "DISABLED") && ($filter == "default")) $selection .= " ".$brew_text_002;
					if (($selected_disabled == "DISABLED") && ($filter != "default")) $selection .= " ".$brew_text_003;
				}
				
				$styles_options .= "<option value=\"".$style_value."\" ".$selected_disabled.$selected.">".$selection."</option>\n";
			}
		}
		
	}
	
	// BJCP Styles housed in local DB
	else {
		// get information from database
		
		$specials = display_array_content_style($all_special_ing_styles,3,$base_url); 
		$specials = rtrim($specials,", "); 
		
		if (($action == "edit") && ($msg != "default")) {
			$view = ltrim($msg,"1-"); 
			$highlight_sweetness  = highlight_required($msg,0,$_SESSION['prefsStyleSet']);
			$highlight_special    = highlight_required($msg,1,$_SESSION['prefsStyleSet']);
			$highlight_carb       = highlight_required($msg,2,$_SESSION['prefsStyleSet']);
			$highlight_strength   = highlight_required($msg,3,$_SESSION['prefsStyleSet']);
		}
		
		elseif ($action == "edit") { 
			$view = $view;
			if (in_array($view,$all_special_ing_styles)) $special_required = TRUE; 
			else $special_required = FALSE;
		}
		
		if ($action == "edit") {
			
			
			if (strlen(strstr($view,"21-B")) > 0) {
			// if ($view == "21-B") {
				$exploder = explode("^",$row_log['brewInfo']);
				$brewInfo = $exploder[0];
				if ($exploder[1] == "Session Strength") $IPASession = "CHECKED";
				if ($exploder[1] == "Standard Strength") $IPAStandard = "CHECKED";
				if ($exploder[1] == "Double Strength") $IPADouble = "CHECKED";
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
				if ($row_log['brewInfo'] == "Pale Color") $darkLightPale .= "CHECKED"; 
				if ($row_log['brewInfo'] == "Amber/Dark Color") $darkLightAmber .= "CHECKED";
				$brewInfo = $row_log['brewInfo'];
			}
			
		}
	
	}
?>
<script>
	
	$(document).ready(function() {

		$("#brewInfo").keyup(function()	{
			var cs = $(this).val().length;
			$('#countInfo').text(cs);
		});

		$("#brewInfo").keydown(function() {
			var cs = $(this).val().length;
			$('#countInfo').text(cs);
		});

		$("#brewInfoOptional").keyup(function()	{
			var cs = $(this).val().length;
			$('#countInfoOptional').text(cs);
		});

		$("#brewInfoOptional").keydown(function() {
			var cs = $(this).val().length;
			$('#countInfoOptional').text(cs);
		});

		$("#brewComments").keyup(function()	{
			var cs = $(this).val().length;
			$('#countComments').text(cs);
		});

		$("#brewComments").keydown(function() {
			var cs = $(this).val().length;
			$('#countComments').text(cs);
		});
	
	});

</script>

<?php echo $modals; ?>
<form data-toggle="validator" role="form" class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo admin_relocate($_SESSION['userLevel'],$go,$_SERVER['HTTP_REFERER']);?>&amp;action=<?php echo $action; ?>&amp;go=<?php echo $go;?>&amp;dbTable=<?php echo $brewing_db_table; ?>&amp;filter=<?php echo $filter; if ($id != "default") echo "&amp;id=".$id; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<?php if ($_SESSION['userLevel'] > 1) { ?>
<input type="hidden" name="brewBrewerID" value="<?php echo $_SESSION['user_id']; ?>">
<input type="hidden" name="brewBrewerFirstName" value="<?php echo $_SESSION['brewerFirstName']; ?>">
<input type="hidden" name="brewBrewerLastName" value="<?php echo $_SESSION['brewerLastName']; ?>">
<?php } ?> 
<input type="hidden" name="brewJudgingNumber" value="<?php echo $row_log['brewJudgingNumber']; ?>">
	<?php if ($_SESSION['prefsProEdition'] == 0) { ?>
	<!-- Enter or Select Brewer Name -->
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewBrewerID" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_brewer; ?></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <p class="form-control-static"><?php echo $brewer_info[0]." ".$brewer_info[1]; ?></p>
                <input type="hidden" name="brewBrewerID" value="<?php echo $brewer_info[7]; ?>">
            </div>
        </div>
    </div><!-- ./Form Group -->
    <!-- Enter Co-Brewer(s) Name(s) -->
	<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
        <label for="brewCoBrewer" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_cobrewer; ?></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<!-- Input Here -->
            <input class="form-control" name="brewCoBrewer" type="text" value="<?php if ($disable_fields) echo "Not Available"; if ($action == "edit") echo $row_log['brewCoBrewer']; ?>" placeholder="" <?php if ($disable_fields) echo "disabled";  ?>>
        </div>
    </div><!-- ./Form Group -->
    <?php } ?>
    <?php if ($_SESSION['prefsProEdition'] == 1) { ?>
	<!-- Enter or Select Brewer Name -->
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewBrewerID" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_organization; ?></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <p class="form-control-static"><?php echo $brewer_info[15]; ?></p>
                <input type="hidden" name="brewBrewerID" value="<?php echo $brewer_info[7]; ?>">
            </div>
        </div>
    </div><!-- ./Form Group -->
    <?php } ?>
    <!-- Enter Entry Name -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewName" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_entry_name; ?></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <input class="form-control" name="brewName" type="text" value="<?php if ($disable_fields) echo "Not Available"; if ($action == "edit") echo $row_log['brewName']; ?>" <?php if ((($action == "add") && ($remaining_entries <= 0) && ($entry_window_open == 1) && ($filter != "default")) || (($action == "add") && ($entry_window_open == "2") && ($_SESSION['userLevel'] > 1))) echo "disabled";?> placeholder="" data-error="<?php echo $brew_text_011; ?>" required autofocus>
                <span class="input-group-addon" id="brewName-addon2"><span class="fa fa-star"></span></span>
            </div>
            <div class="help-block with-errors"></div>
            <div class="help-block"><?php echo $brew_text_001; ?></div>
            
        </div>
    </div><!-- ./Form Group -->
    <!-- Choose Style -->
	<div class="form-group"><!-- Form Group REQUIRED Select -->
        <label for="brewStyle" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $style_set." ".$label_style; ?></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="brewStyle" id="type" data-live-search="true" data-size="5" data-width="auto" data-show-tick="true" data-header="<?php echo $label_select_style; ?>" title="<?php echo $label_select_style; ?>" required>
			<option value="0-A" <?php if (($action == "add") || (($action == "edit") && ($view == "00-A"))) echo "selected"; ?>>Choose a Style</option>
            <option data-divider="true"></option>
            <?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) echo $styles_options;
			else {
				// Build style drop-down
				do {
					// Option value variable
					$style_value = ltrim($row_styles['brewStyleGroup'], "0")."-".$row_styles['brewStyleNum'];
					
					// Determine if the subcategory limit has been reached for various conditions
					if ($_SESSION['userLevel'] == 2) $subcat_limit = limit_subcategory($style_value,$user_subcat_limit,$user_subcat_limit_exception,$row_limits['prefsUSCLEx'],$_SESSION['user_id']);
					elseif (($_SESSION['userLevel'] <= 1) && ($filter != "admin") && ($id == "default")) $subcat_limit = limit_subcategory($style_value,$user_subcat_limit,$user_subcat_limit_exception,$row_limits['prefsUSCLEx'],$filter);
					elseif (($_SESSION['userLevel'] <= 1) && ($filter != "admin") && ($id != "default")) $subcat_limit = limit_subcategory($style_value,$user_subcat_limit,$user_subcat_limit_exception,$row_limits['prefsUSCLEx'],$row_log['brewBrewerID']);
					elseif (($_SESSION['userLevel'] <= 1) && ($filter == "admin")) $subcat_limit = limit_subcategory($style_value,$user_subcat_limit,$user_subcat_limit_exception,$row_limits['prefsUSCLEx'],$_SESSION['user_id']);
					
					// Build selected/disabled variable
					if ($action == "edit") { 
					   if ($row_styles['brewStyleGroup'].$row_styles['brewStyleNum'] == $row_log['brewCategorySort'].$row_log['brewSubCategory']) $selected_disabled = "SELECTED"; 
					   if ($row_styles['brewStyleGroup'].$row_styles['brewStyleNum'] != $row_log['brewCategorySort'].$row_log['brewSubCategory']) $selected_disabled = $subcat_limit; 
					} 
					if (($action == "add") && ($remaining_entries > 0) && (!$disable_fields) && ($_SESSION['userLevel'] == 2)) $selected_disabled = $subcat_limit; 
					elseif ($disable_fields) $selected_disabled = "DISABLED";
					
					// Build selection variable
					if (preg_match("/^[[:digit:]]+$/",$row_styles['brewStyleGroup'])) $selection = sprintf('%02d',$row_styles['brewStyleGroup']).$row_styles['brewStyleNum']." ".$row_styles['brewStyle'];
					else $selection = $row_styles['brewStyleGroup'].$row_styles['brewStyleNum']." ".$row_styles['brewStyle'];
					if ($row_styles['brewStyleReqSpec'] == 1) $selection .= " &spades;";
					if ($row_styles['brewStyleStrength'] == 1) $selection .= " &diams;";
					if ($row_styles['brewStyleCarb'] == 1) $selection .= " &clubs;";
					if ($row_styles['brewStyleSweet'] == 1) $selection .= " &hearts;";
					if (($selected_disabled == "DISABLED") && ($filter == "default")) $selection .= " ".$brew_text_002;
					if (($selected_disabled == "DISABLED") && ($filter != "default")) $selection .= " ".$brew_text_003;
					
				if (!empty($row_styles['brewStyleGroup'])) {
				// Display
				 ?>
				<option value="<?php echo $style_value; ?>" <?php echo $selected_disabled; ?>><?php echo $selection; ?></option>
				<?php }
				} while ($row_styles = mysqli_fetch_assoc($styles)); 
			} ?>
        </select>
        <span id="helpBlock" class="help-block">&spades; = <?php echo $brew_text_004; ?><br />&diams; = <?php echo $brew_text_005; ?><br />&clubs; = <?php echo $brew_text_006; ?><br />&hearts; = <?php echo $brew_text_007; ?></p></span>
        </div>
    </div><!-- ./Form Group -->
    <!-- Entry Requirements -->
	<div id="specialInfo" class="form-group">
    	<label for="brewInfo" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $brew_text_009; ?> <span id="specialInfoName">Style Name</span></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        <p><strong class="text-primary"><?php echo $brew_text_008; ?></strong>
        	<div id="specialInfoText" class="form-control-static">Entry info goes here.</div>
        </div>
    </div>
    <!-- Enter Special Ingredients -->
	<div id="special" class="form-group <?php if ($highlight_special) echo "has-error"; elseif (($action == "edit") && ($special_required)) echo "has-warning"; ?>"><!-- Form Group REQUIRED Text Input -->
        <label for="brewInfo" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_required_info; ?></label>
        	<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">  
        		<textarea class="form-control" rows="8" name="brewInfo" id="brewInfo" data-error="<?php echo $brew_text_010; ?>" maxlength="<?php echo $_SESSION['prefsSpecialCharLimit']; ?>" <?php if ($highlight_special) echo "autofocus"; elseif (($action == "edit") && ($special_required)) echo "autofocus"; ?>><?php echo $brewInfo; ?></textarea>
            <div class="help-block with-errors"><?php if ((strpos($styleSet,"BABDB") !== false) && ($view_explodies[0] < 28)) echo $brew_text_027; ?></div>
            <div id="helpBlock" class="help-block"><p><?php echo $_SESSION['prefsSpecialCharLimit'].$label_character_limit; ?><span id="countInfo"><?php echo $_SESSION['prefsSpecialCharLimit']; ?></span></p></div>
        </div>
    </div><!-- ./Form Group -->
    <!-- Optional Information -->
    <div id="optional" class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewInfoOptional" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_optional_info; ?></label>
        	<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">  
        		<textarea class="form-control" rows="8" name="brewInfoOptional" id="brewInfoOptional" data-error="<?php echo $brew_text_010; ?>" maxlength="<?php echo $_SESSION['prefsSpecialCharLimit']; ?>" placeholder="<?php echo $brew_text_028; ?>"><?php echo $row_log['brewInfoOptional']; ?></textarea>      	
            	 
            <div class="help-block with-errors"><?php if ((strpos($styleSet,"BABDB") !== false) && ($view_explodies[0] < 28)) echo $brew_text_027; ?></div>
            <div id="helpBlock" class="help-block"><p><?php echo $_SESSION['prefsSpecialCharLimit'].$label_character_limit; ?><span id="countInfoOptional"><?php echo $_SESSION['prefsSpecialCharLimit']; ?></span></p></div>
        </div>
    </div><!-- ./Form Group -->
    <!-- Lambic Carbonation -->
    <div id="carbLambic" class="form-group">
    	<label for="carbLambic" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_carbonation; ?></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<label class="radio-inline">
              	<input type="radio" name="carbLambic" id="carbLambic1" value="Low Carbonation" <?php if ($action == "edit") echo $lambicCarbLow; ?>> <?php echo $label_low; ?>
            </label>
            <label class="radio-inline">
              	<input type="radio" name="carbLambic" id="carbLambic2" value="Medium Carbonation" <?php if ($action == "edit") echo $lambicCarbMed; ?>> <?php echo $label_med; ?>
            </label>
            <label class="radio-inline">
              	<input type="radio" name="carbLambic" id="carbLambic3" value="High Carbonation" <?php if ($action == "edit") echo $lambicCarbHigh; ?>> <?php echo $label_high; ?>
            </label>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <!-- Lambic Sweetness -->
    <div id="sweetnessLambic" class="form-group">
    	<label for="sweetnessLambic" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_sweetness; ?></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<label class="radio-inline">
              	<input type="radio" name="sweetnessLambic" id="sweetnessLambic1" value="Low/None Sweetness" <?php if ($action == "edit") echo $lambicSweetLow; ?>> <?php echo $label_low; ?>
            </label>
            <label class="radio-inline">
              	<input type="radio" name="sweetnessLambic" id="sweetnessLambic2" value="Medium Sweetness" <?php if ($action == "edit") echo $lambicSweetMed; ?>> <?php echo $label_med; ?>
            </label>
            <label class="radio-inline">
              	<input type="radio" name="sweetnessLambic" id="sweetnessLambic3" value="High Sweetness" <?php if ($action == "edit") echo $lambicSweetHigh; ?>> <?php echo $label_high; ?>
            </label>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <!-- Saison Strength -->
    <div id="strengthSaison" class="form-group">
    	<label for="strengthSaison" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_strength; ?></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<label class="radio-inline">
              	<input type="radio" name="strengthSaison" id="strengthSaison1" value="Table Strength" <?php echo $saisonTable; ?>> <?php echo $label_table; ?>
            </label>
            <label class="radio-inline">
              	<input type="radio" name="strengthSaison" id="strengthSaison2" value="Standard Strength" <?php echo $saisonStandard; ?>> <?php echo $label_standard; ?>
            </label>
            <label class="radio-inline">
              	<input type="radio" name="strengthSaison" id="strengthSaison3" value="Super Strength" <?php echo $saisonSuper; ?>> <?php echo $label_super; ?>
            </label>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <!-- IPA Strength -->
    <div id="strengthIPA" class="form-group">
    	<label for="strengthIPA" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_strength; ?></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<label class="radio-inline">
              	<input type="radio" name="strengthIPA" id="strengthIPA1" value="Session Strength" <?php if ($action == "edit") echo $IPASession; ?>> <?php echo $label_session; ?>
            </label>
            <label class="radio-inline">
              	<input type="radio" name="strengthIPA" id="strengthIPA2" value="Standard Strength" <?php if ($action == "edit") echo $IPAStandard; ?>> <?php echo $label_standard; ?>
            </label>
            <label class="radio-inline">
              	<input type="radio" name="strengthIPA" id="strengthIPA3" value="Double Strength" <?php if ($action == "edit") echo $IPADouble; ?>> <?php echo $label_double; ?>
            </label>
            <div class="help-block with-errors"></div>
        </div>
    </div>
 	<!-- Biere de Garde Color -->
    <div id="BDGColor" class="form-group">
    	<label for="BDGColor" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_color; ?></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<label class="radio-inline">
              	<input type="radio" name="BDGColor" id="BDGColor1" value="Blonde Color" <?php if ($action == "edit") echo $BDGBlonde; ?>> <?php echo $label_blonde; ?>
            </label>
            <label class="radio-inline">
              	<input type="radio" name="BDGColor" id="BDGColor2" value="Amber Color" <?php if ($action == "edit") echo $BDGAmber; ?>> <?php echo $label_amber; ?>
            </label>
            <label class="radio-inline">
              	<input type="radio" name="BDGColor" id="BDGColor3" value="Brown Color" <?php if ($action == "edit") echo $BDGBrown; ?>> <?php echo $label_brown; ?>
            </label>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <!-- Light or Dark Color -->
    <div id="darkLightColor" class="form-group">
    	<label for="darkLightColor" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_color; ?></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<label class="radio-inline">
              	<input type="radio" name="darkLightColor" id="darkLightColor1" value="Pale Color" <?php echo $darkLightPale; ?>> <?php echo $label_pale; ?>
            </label>
            <label class="radio-inline">
              	<input type="radio" name="darkLightColor" id="darkLightColor2" value="Amber/Dark Color" <?php echo $darkLightAmber; ?>> <?php echo $label_amber."/".$label_dark; ?>
            </label>
            <div class="help-block with-errors"></div>
        </div>
    </div>    
    <!-- Select Strength -->
    <div id="strength">	
    	<div class="form-group"><!-- Form Group Radio INLINE -->
            <label for="brewMead3" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label <?php if ($highlight_strength) echo "text-danger"; ?>"><?php echo $label_strength; ?></label>
            <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <label class="radio-inline">
                        <input type="radio" name="brewMead3" value="Hydromel" id="brewMead3_0"  <?php if (($action == "edit") && ($row_log['brewMead3'] == "Hydromel")) echo "CHECKED";  ?> /> <?php echo $label_hydromel; ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead3" value="Standard" id="brewMead3_1"  <?php if (($action == "edit") && ($row_log['brewMead3'] == "Standard")) echo "CHECKED";  ?> /> <?php echo $label_standard; ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead3" value="Sack" id="brewMead3_2"  <?php if (($action == "edit") && ($row_log['brewMead3'] == "Sack")) echo "CHECKED";  ?> /> <?php echo $label_sack; ?>
                    </label>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div><!-- ./Form Group -->
    </div>
	<!-- Select Carbonation -->
    <div id="carbonation">
        <div id="selectCarbonation" class="form-group"><!-- Form Group Radio INLINE -->
            <label for="brewMead1" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_carbonation; ?></label>
            <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <label class="radio-inline">
                        <input type="radio" name="brewMead1" value="Still" id="brewMead1_0" <?php if (($action == "edit") && ($row_log['brewMead1'] == "Still")) echo "CHECKED";  ?>/> <?php echo $label_still; ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead1" value="Petillant" id="brewMead1_1"  <?php if (($action == "edit") && ($row_log['brewMead1'] == "Petillant")) echo "CHECKED";  ?>/> <?php echo $label_petillant; ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead1" value="Sparkling" id="brewMead1_2"  <?php if (($action == "edit") && ($row_log['brewMead1'] == "Sparkling")) echo "CHECKED";  ?>/> <?php echo $label_sparkling; ?>
                    </label>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div><!-- ./Form Group -->
     </div>
     <!-- Select Sweetness -->
     <div id="sweetness">
    	<div class="form-group <?php if (($highlight_carb) || ($highlight_sweetness)) echo "has-error"; ?>"><!-- Form Group Radio INLINE -->
            <label for="brewMead2" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_sweetness; ?></label>
            <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <label class="radio-inline">
                        <input type="radio" name="brewMead2" value="Dry" id="brewMead2_0"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Dry")) echo "CHECKED";  ?> /><?php echo $label_dry; ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead2" value="Medium Dry" id="brewMead2_1"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Medium Dry")) echo "CHECKED";  ?>/> <?php echo $label_med_dry; ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead2" value="Medium" id="brewMead2_2"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Medium")) echo "CHECKED";  ?>/> <?php echo $label_med; ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead2" value="Medium Sweet" id="brewMead2_3"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Medium Sweet")) echo "CHECKED";  ?>/> <?php echo $label_med_sweet; ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead2" value="Sweet" id="brewMead2_4"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Sweet")) echo "CHECKED";  ?>/> <?php echo $label_sweet; ?>
                    </label>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div><!-- ./Form Group -->
    </div>
    <?php if (($_SESSION['prefsSpecific'] == 0) && (strpos($_SESSION['prefsStyleSet'],"BABDB") === false)) { ?>
    <!-- Enter Brewer's Specifics -->
    <div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
        <label for="brewComments" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_brewer_specifics; ?></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<!-- Input Here -->
            <textarea rows="6" class="form-control" name="brewComments" id="brewComments" maxlength="<?php echo $_SESSION['prefsSpecialCharLimit']; ?>" placeholder="<?php echo $brew_text_012; ?>"><?php if ($action == "edit") echo $row_log['brewComments']; ?></textarea>
            <span id="helpBlock" class="help-block">
            	<p><strong class="text-danger"><?php echo $brew_text_013; ?></strong></p>
                <p><strong class="text-primary"><?php echo $brew_text_014; ?></strong></p>
                <p><?php echo $_SESSION['prefsSpecialCharLimit'].$label_character_limit; ?><span id="countComments"><?php echo $_SESSION['prefsSpecialCharLimit']; ?></span></p>
            </span>
        </div>
    </div><!-- ./Form Group -->
    <?php } ?>
<?php if ($_SESSION['prefsHideRecipe'] == "N") { ?>
<div class="bcoem-form-accordion">
    <div class="panel-group" id="accordion">
    	<!-- General Panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseGeneral"><?php echo $label_general; ?></a>
                </h4>
            </div>
            <div id="collapseGeneral" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewYield" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_amount_brewed; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" name="brewYield" type="text" value="<?php if ($action == "edit") echo $row_log['brewYield']; ?>" placeholder="<?php echo ucwords($_SESSION['prefsLiquid2']); ?>" ?>
                        </div>
            		</div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewWinnerCat" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_color; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" name="brewWinnerCat" type="text" value="<?php if ($action == "edit") echo $row_log['brewWinnerCat']; ?>" placeholder="SRM" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewWinnerCat" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_date; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewDate"  name="brewDate" value="<?php if ($action == "edit") echo $row_log['brewDate']; ?>" placeholder="YYYY-MM-DD" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewDate" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_bottled; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewBottleDate" name="brewBottleDate" value="<?php if ($action == "edit") echo $row_log['brewBottleDate']; ?>" placeholder="YYYY-MM-DD" ?>
                        </div>
                    </div><!-- ./Form Group -->
                </div>
            </div>
        </div>
        <!-- Gravities Panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseGrav"><?php echo $label_specific_gravity; ?></a>
                </h4>
            </div>
            <div id="collapseGrav" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewOG" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_og; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewOG" name="brewOG" value="<?php if ($action == "edit") echo $row_log['brewOG']; ?>" placeholder="1.060, 1.078, etc." ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewDate" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_fg; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewFG" name="brewFG" value="<?php if ($action == "edit") echo $row_log['brewFG']; if ($action == "importCalc") echo round ($brewFG, 3); ?>" placeholder="1.010, 1.014, etc" ?>
                        </div>
                    </div><!-- ./Form Group -->
                </div>
            </div>
        </div>
        <!-- Extract Panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseExtract"><?php echo $label_fermentables." - ".$label_malt_extract; ?></a>
                </h4>
            </div>
            <div id="collapseExtract" class="panel-collapse collapse">
                <div class="panel-body">
                <!-- Form Element(s) Begin -->
				<?php for($i=1; $i<=5; $i++) { ?>
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewExtract<?php echo $i; ?>" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_extract." ".$i ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewExtract<?php echo $i; ?>" name="brewExtract<?php echo $i; ?>" value="<?php if ($action == "edit") echo $row_log['brewExtract'.$i]; ?>" placeholder="<?php echo $brew_text_015; ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                	<div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewExtract<?php echo $i; ?>Weight" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_extract." ".$i." ".$label_weight; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewExtract<?php echo $i; ?>Weight" name="brewExtract<?php echo $i; ?>Weight" value="<?php if ($action == "edit") echo $row_log['brewExtract'.$i.'Weight']; ?>" placeholder="<?php echo $_SESSION['prefsWeight2']; ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewMead3" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_extract." ".$i." ".$label_use; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewExtract<?php echo $i; ?>Use" id="brewExtract<?php echo $i; ?>Use" value="Mash" <?php if ($action == "edit") { if (!(strcmp($row_log['brewExtract'.$i.'Use'], "Mash"))) echo "CHECKED"; }?>> <?php echo $label_mash; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewExtract<?php echo $i; ?>Use" id="brewExtract<?php echo $i; ?>Use" value="Steep" <?php if ($action == "edit") { if (!(strcmp($row_log['brewExtract'.$i.'Use'], "Steep"))) echo "CHECKED"; }?>> <?php echo $label_steep; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewExtract<?php echo $i; ?>Use" id="brewExtract<?php echo $i; ?>Use" value="Other" <?php if ($action == "edit") { if (!(strcmp($row_log['brewExtract'.$i.'Use'], "Other"))) echo "CHECKED"; }?>> <?php echo $label_other; ?>
                            </label>
                        </div>
                    </div>
                </div><!-- ./Form Group -->
                <?php } ?>
                </div><!-- ./New Panel -->
            </div>
        </div>
        <!-- Grains Panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseGrains"><?php echo $label_fermentables." - ".$label_grain; ?></a>
                </h4>
            </div>
            <div id="collapseGrains" class="panel-collapse collapse">
                <div class="panel-body">
                <!-- Form Element(s) Begin -->
				  <?php for($i=1; $i<=20; $i++) { ?>
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewGrain<?php echo $i; ?>" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_grain." ".$i ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewGrain<?php echo $i; ?>" name="brewGrain<?php echo $i; ?>" value="<?php if ($action == "edit") echo $row_log['brewGrain'.$i]; ?>" placeholder="<?php echo $brew_text_016; ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                	<div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewGrain<?php echo $i; ?>Weight" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_grain." ".$i." ".$label_weight; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewGrain<?php echo $i; ?>Weight" name="brewGrain<?php echo $i; ?>Weight" value="<?php if ($action == "edit") echo $row_log['brewGrain'.$i.'Weight']; ?>" placeholder="<?php echo ucwords($_SESSION['prefsWeight2']); ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewMead3" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_grain." ".$i." ".$label_use; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewGrain<?php echo $i; ?>Use" id="brewGrain<?php echo $i; ?>Use" value="Mash" <?php if ($action == "edit") { if (!(strcmp($row_log['brewGrain'.$i.'Use'], "Mash"))) echo "CHECKED"; }?>> <?php echo $label_mash; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewGrain<?php echo $i; ?>Use" id="brewGrain<?php echo $i; ?>Use" value="Steep" <?php if ($action == "edit") { if (!(strcmp($row_log['brewGrain'.$i.'Use'], "Steep"))) echo "CHECKED"; }?>> <?php echo $label_steep; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewGrain<?php echo $i; ?>Use" id="brewGrain<?php echo $i; ?>Use" value="Other" <?php if ($action == "edit") { if (!(strcmp($row_log['brewGrain'.$i.'Use'], "Other"))) echo "CHECKED"; }?>> <?php echo $label_other; ?>
                            </label>
                        </div>
                    </div>
                </div><!-- ./Form Group -->
                <?php } ?>  
                </div>
            </div>
        </div><!-- ./New Panel -->
        <!-- Misc Panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseMisc"><?php echo $label_misc; ?></a>
                </h4>
            </div>
            <div id="collapseMisc" class="panel-collapse collapse">
                <div class="panel-body">
                	<!-- Form Element(s) Begin -->
					<?php for($i=1; $i<=20; $i++) { ?>
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewAddition<?php echo $i; ?>" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_misc." ".$i; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewAddition<?php echo $i; ?>" name="brewAddition<?php echo $i; ?>" value="<?php if ($action == "edit") echo $row_log['brewAddition'.$i]; ?>" placeholder="<?php echo $brew_text_017; ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                	<div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewAddition<?php echo $i; ?>Weight" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_misc." ".$i." ".$label_weight; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewAddition<?php echo $i; ?>Weight" name="brewAddition<?php echo $i; ?>Weight" value="<?php if (($action == "edit") && (isset($row_log['brewAddition'.$i.'Weight']))) echo $row_log['brewAddition'.$i.'Weight']; ?>" placeholder="<?php echo ucwords($_SESSION['prefsWeight2']); ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewMead3" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_misc." ".$i." ".$label_use; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewAddition<?php echo $i; ?>Use" id="brewAddition<?php echo $i; ?>Use" value="Mash" <?php if ($action == "edit") { if (!(strcmp($row_log['brewAddition'.$i.'Use'], "Mash"))) echo "CHECKED"; }?>> <?php echo $label_mash; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewAddition<?php echo $i; ?>Use" id="brewAddition<?php echo $i; ?>Use" value="Steep" <?php if ($action == "edit") { if (!(strcmp($row_log['brewAddition'.$i.'Use'], "Steep"))) echo "CHECKED"; }?>> <?php echo $label_steep; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewAddition<?php echo $i; ?>Use" id="brewAddition<?php echo $i; ?>Use" value="Other" <?php if ($action == "edit") { if (!(strcmp($row_log['brewAddition'.$i.'Use'], "Other"))) echo "CHECKED"; }?>> <?php echo $label_other; ?>
                            </label>
                        </div>
                    </div>
                </div><!-- ./Form Group -->
                <?php } ?>
                </div>
            </div>
        </div><!-- ./New Panel -->
        <!-- Hops Panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseHops"><?php echo $label_hops; ?></a>
                </h4>
            </div>
            <div id="collapseHops" class="panel-collapse collapse">
                <div class="panel-body">
                	<?php for($i=1; $i<=20; $i++) { ?>
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewHops<?php echo $i; ?>" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_hop." ".$i; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewHops<?php echo $i; ?>" name="brewHops<?php echo $i; ?>" value="<?php if (($action == "edit") && (isset($row_log['brewHops'.$i]))) echo $row_log['brewHops'.$i]; ?>" placeholder="<?php echo $brew_text_018; ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                	<div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewHops<?php echo $i; ?>Weight" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_hop." ".$i." ".$label_weight; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewHops<?php echo $i; ?>Weight" name="brewHops<?php echo $i; ?>Weight" value="<?php if ($action == "edit") echo $row_log['brewHops'.$i.'Weight']; ?>" placeholder="<?php echo ucwords($_SESSION['prefsWeight1']); ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewHops<?php echo $i; ?>IBU" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_hop." ".$i; ?> AA%</label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewHops<?php echo $i; ?>IBU" name="brewHops<?php echo $i; ?>IBU" value="<?php if ($action == "edit") echo $row_log['brewHops'.$i.'IBU']; ?>" placeholder="<?php echo $brew_text_019; ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewHops<?php echo $i; ?>Time" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_hop." ".$i." ".$label_time; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewHops<?php echo $i; ?>Time" name="brewHops<?php echo $i; ?>Time" value="<?php if ($action == "edit") echo $row_log['brewHops'.$i.'Time']; ?>" placeholder="<?php echo $label_minutes; ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewHops<?php echo $i; ?>Use" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_hop." ".$i." ".$label_use; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Use" id="brewHops<?php echo $i; ?>Use" value="First Wort" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "First Wort"))) echo "CHECKED"; }?>> <?php echo $label_first_wort; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Use" id="brewHops<?php echo $i; ?>Use" value="Mash" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "Mash"))) echo "CHECKED"; }?>> <?php echo $label_mash; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Use" id="brewHops<?php echo $i; ?>Use" value="Boil" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "Boil"))) echo "CHECKED"; }?>> <?php echo $label_boil; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Use" id="brewHops<?php echo $i; ?>Use" value="Aroma" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "Aroma"))) echo "CHECKED"; }?>> <?php echo $label_aroma; ?>
                            </label>
                            
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Use" id="brewHops<?php echo $i; ?>Use" value="Dry Hop" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "Dry Hop"))) echo "CHECKED"; }?>> <?php echo $label_dry_hop; ?>
                            </label>
                        </div>
                    </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewHops<?php echo $i; ?>Type" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_hop." ".$i." ".$label_type; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Type" id="brewHops<?php echo $i; ?>Type" value="Bittering" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Type'], "Bittering"))) echo "CHECKED"; }?>> <?php echo $label_bittering; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Type" id="brewHops<?php echo $i; ?>Type" value="Aroma" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Type'], "Aroma"))) echo "CHECKED"; }?>> <?php echo $label_aroma; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Type" id="brewHops<?php echo $i; ?>Type" value="Both" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Type'], "Both"))) echo "CHECKED"; }?>> <?php echo $label_both; ?>
                            </label>
                        </div>
                    </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewHops<?php echo $i; ?>Form" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_hop." ".$i." ".$label_form; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Form" id="brewHops<?php echo $i; ?>Form" value="Pellets" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Form'], "Pellets"))) echo "CHECKED"; }?>> <?php echo $label_pellets; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Form" id="brewHops<?php echo $i; ?>Form" value="Plug" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Form'], "Plug"))) echo "CHECKED"; }?>> <?php echo $label_plug; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Form" id="brewHops<?php echo $i; ?>Form" value="Leaf" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Form'], "Leaf"))) echo "CHECKED"; }?>> <?php echo $label_whole; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Form" id="brewHops<?php echo $i; ?>Form" value="Extract" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Form'], "Extract"))) echo "CHECKED"; }?>> <?php echo $label_extract; ?>
                            </label>
                        </div>
                    </div>
                    </div><!-- ./Form Group -->
                <?php } ?>
                </div>
            </div>
        </div><!-- ./New Panel -->
        <!-- Mash Panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseMash"><?php echo $label_mash; ?></a>
                </h4>
            </div>
            <div id="collapseMash" class="panel-collapse collapse">
                <div class="panel-body">
                <?php for($i=1; $i<=10; $i++) { ?>
				<div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewMashStep<?php echo $i; ?>Name" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_step." ".$i; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewMashStep<?php echo $i; ?>Name" type="text" value="<?php if ($action == "edit") echo $row_log['brewMashStep'.$i.'Name']; ?>" placeholder="<?php echo $brew_text_024; ?>">
                    </div>
                </div><!-- ./Form Group -->    
                    
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewMashStep<?php echo $i; ?>Time" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_step." ".$i." ".$label_time; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewMashStep<?php echo $i; ?>Time" type="text" value="<?php if ($action == "edit") echo $row_log['brewMashStep'.$i.'Time']; ?>" placeholder="<?php echo $label_minutes;?>">
                    </div>
                </div><!-- ./Form Group -->
                
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewMashStep<?php echo $i; ?>Temp" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_step." ".$i." ".$label_temperature; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewMashStep<?php echo $i; ?>Temp" type="text" value="<?php if ($action == "edit") echo $row_log['brewMashStep'.$i.'Temp']; ?>" placeholder="&deg;<?php echo $_SESSION['prefsTemp']; ?>">
                    </div>
                </div><!-- ./Form Group -->
                <?php } ?>
                </div>
            </div>
        </div><!-- ./New Panel -->
        <!-- Water Panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseWater"><?php echo $label_water; ?></a>
                </h4>
            </div>
            <div id="collapseWater" class="panel-collapse collapse">
                <div class="panel-body">
                	<div class="form-group form-group-sm"><!-- Form Group NOT-REQUIRED Text Area -->
                        <label for="brewWaterNotes" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_type."/".$label_amount; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <textarea class="form-control" name="brewWaterNotes" id="brewWaterNotes" rows="6"><?php if ($action == "edit") echo $row_log['brewWaterNotes']; ?></textarea>
                         </div>
                    </div><!-- ./Form Group -->
                </div>
            </div>
        </div><!-- ./New Panel -->
        <!-- Yeast Panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseYeast"><?php echo $label_yeast; ?></a>
                </h4>
            </div>
            <div id="collapseYeast" class="panel-collapse collapse">
                <div class="panel-body">                
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewYeast" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_yeast." ".$label_name; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewYeast" type="text" value="<?php if ($action == "edit") echo $row_log['brewYeast']; ?>" placeholder="<?php echo $brew_text_020; ?>">
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewYeastMan" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_manufacturer; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewYeastMan" type="text" value="<?php if ($action == "edit") echo $row_log['brewYeastMan']; ?>" placeholder="<?php echo $brew_text_021; ?>">
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewYeastStarter" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_starter; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastStarter" id="brewYeastStarter" value="Y" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastStarter'], "Y"))) echo "CHECKED"; }?>> <?php echo $label_yes; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastStarter" id="brewYeastStarter" value="N" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastStarter'], "N"))) echo "CHECKED"; }?>> <?php echo $label_no; ?>
                            </label>
                        </div>
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewYeastAmount" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_amount; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                    	<!-- Input Here -->
                    	<input class="form-control" name="brewYeastAmount" type="text" value="<?php if ($action == "edit") echo $row_log['brewYeastAmount']; ?>" placeholder="<?php echo $brew_text_022; ?>">
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewYeastNutrients" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_nutrients; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                    	<!-- Input Here -->
                    	<input class="form-control" name="brewYeastNutrients" type="text" value="<?php if ($action == "edit") echo $row_log['brewYeastNutrients']; ?>" placeholder="">
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewYeastForm" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_form; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastForm" id="brewYeastForm" value="Liquid" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastForm'], "Liquid"))) echo "CHECKED"; }?>> <?php echo $label_liquid; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastForm" id="brewYeastForm" value="Dry" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastForm'], "Dry"))) echo "CHECKED"; }?>> <?php echo $label_dry; ?>
                            </label>
                        </div>
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewYeastType" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_type; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastType" id="brewYeastType" value="Ale" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Ale"))) echo "CHECKED"; }?>> <?php echo $label_ale; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastType" id="brewYeastType" value="Lager" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Mash"))) echo "CHECKED"; }?>> <?php echo $label_lager; ?>
                            </label>
                            
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastType" id="brewYeastType" value="Wine" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Wine"))) echo "CHECKED"; }?>> <?php echo $label_wine; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastType" id="brewYeastType" value="Champagne" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Champagne"))) echo "CHECKED"; }?>> <?php echo $label_champagne; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastType" id="brewYeastType" value="Other" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Other"))) echo "CHECKED"; }?>> <?php echo $label_other; ?>
                            </label>
                        </div>
                    </div>
                </div><!-- ./Form Group -->
                </div>
            </div>
        </div><!-- ./New Panel -->
        <!-- Boil, Fermentation, Finishing, Carbonation Panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseBoil"><?php echo $label_boil.", ".$label_fermentation.", ".$label_finishing.", ".$label_carbonation; ?></a>
                </h4>
            </div>
            <div id="collapseBoil" class="panel-collapse collapse">
                <div class="panel-body">
                
                <!-- Boil -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewBoilHours" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_boil." ".$label_hours; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewBoilHours" type="text" value="<?php if ($action == "edit") echo $row_log['brewBoilHours']; ?>" placeholder="">
                    </div>
                </div><!-- ./Form Group -->   
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewBoilMins" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_boil." ".$label_minutes; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewBoilMins" type="text" value="<?php if ($action == "edit") echo $row_log['brewBoilMins']; ?>" placeholder="">
                    </div>
                </div><!-- ./Form Group --> 
                
                <!-- Finings -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewFinings" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_finings; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewFinings" type="text" value="<?php if ($action == "edit") echo $row_log['brewFinings']; ?>" placeholder="">
                    </div>
                </div><!-- ./Form Group -->
                <!-- Fermentation -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewPrimary" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_primary." ".$label_fermentation; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewPrimary" type="text" value="<?php if ($action == "edit") echo $row_log['brewPrimary']; ?>" placeholder="<?php echo $brew_text_023; ?>">
                    </div>
                </div><!-- ./Form Group -->   
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewPrimaryTemp" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_primary." ".$label_temperature; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewPrimaryTemp" type="text" value="<?php if ($action == "edit") echo $row_log['brewPrimaryTemp']; ?>" placeholder="&deg;<?php echo $_SESSION['prefsTemp']; ?>">
                    </div>
                </div><!-- ./Form Group --> 
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewSecondary" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_secondary." ".$label_fermentation; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewSecondary" type="text" value="<?php if ($action == "edit") echo $row_log['brewSecondary']; ?>" placeholder="<?php echo $brew_text_025; ?>">
                    </div>
                </div><!-- ./Form Group -->   
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewSecondaryTemp" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_secondary." ".$label_temperature; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewSecondaryTemp" type="text" value="<?php if ($action == "edit") echo $row_log['brewSecondaryTemp']; ?>" placeholder="&deg;<?php echo $_SESSION['prefsTemp']; ?>">
                    </div>
                </div><!-- ./Form Group --> 
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewOther" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_other." ".$label_fermentation; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewOther" type="text" value="<?php if ($action == "edit") echo $row_log['brewOther']; ?>" placeholder="<?php echo $brew_text_026; ?>">
                    </div>
                </div><!-- ./Form Group -->   
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewOtherTemp" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_other." ".$label_temperature; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewOtherTemp" type="text" value="<?php if ($action == "edit") echo $row_log['brewOtherTemp']; ?>" placeholder="&deg;<?php echo $_SESSION['prefsTemp']; ?>">
                    </div>
                </div><!-- ./Form Group --> 
                <!-- Carbonation -->
                <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewCarbonationMethod" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_carbonation; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewCarbonationMethod" id="brewCarbonationMethod_0" value="Y" <?php if ($action == "edit") { if (!(strcmp($row_log['brewCarbonationMethod'], "Y"))) echo "CHECKED"; }?>> <?php echo $label_forced; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewCarbonationMethod" id="brewCarbonationMethod_1" value="N" <?php if ($action == "edit") { if (!(strcmp($row_log['brewCarbonationMethod'], "N"))) echo "CHECKED"; }?>> <?php echo $label_bottle_cond; ?>
                            </label>
                        </div>
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewCarbonationVol" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_volume; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewCarbonationVol" type="text" value="<?php if ($action == "edit") echo $row_log['brewCarbonationVol']; ?>" placeholder="">
                    </div>
                </div><!-- ./Form Group --> 
                <div class="form-group form-group-sm"><!-- Form Group NOT-REQUIRED Text Area -->
                    <label for="brewCarbonationNotes" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_carbonation." ".$label_type."/".$label_amount; ?></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <textarea class="form-control" name="brewCarbonationNotes" id="brewCarbonationNotes" rows="6"><?php if ($action == "edit") echo $row_log['brewCarbonationNotes']; ?></textarea>
                     </div>
                </div><!-- ./Form Group -->
                </div>
            </div>
        </div><!-- ./New Panel -->
    </div>
</div>
<?php } // end if ($_SESSION['prefsHideRecipe'] == "N") 
if ($action == "add") {
	$submit_icon = "plus";
	$submit_text = $label_add_entry;
}
if ($action == "edit") {
	$submit_icon = "pencil";
	$submit_text = $label_edit_entry;
}
?>
<div class="bcoem-admin-element hidden-print">
<div class="form-group">
    <div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-3 col-xs-12">
        <!-- Input Here -->
		<button name="submit" type="submit" class="btn btn-primary <?php if ($disable_fields) echo "disabled"; ?>" ><?php echo $submit_text; ?> <span class="fa fa-<?php echo $submit_icon; ?>"></span></button>
	</div>
</div><!-- Form Group -->
</div>
<input type="hidden" name="brewPaid" value="<?php echo $brewPaid; ?>">
<input type="hidden" name="brewConfirmed" value="1">
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=list","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php
// Load Show/Hide
include (INCLUDES.'form_js.inc.php');
}  // end adding and editing allowed (line 52 or so)
?>