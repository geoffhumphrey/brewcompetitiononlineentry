<?php 
/**
 * Module:      brew.sec.php
 * Description: This module houses the functionality for users to add/edit individual competition
 *              entries - references the "brewing" database table.
 *
 */
include(DB.'styles.db.php'); 
include(DB.'entries.db.php');

// Define vars
$add_entry_disable = FALSE;
$edit_entry_disable = FALSE;
$special_required = FALSE;
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

// Adding an entry not allowed conditionals for non-admins
if ($action == "add") {
	
	// Registration and entry windows open; comp entry limit reached
	if (($registration_open == 1) && ($entry_window_open == 1) && ($_SESSION['userLevel'] == 2)) { 
		if ($comp_entry_limit) $add_entry_disable = TRUE;
		if ($comp_paid_entry_limit)  $add_entry_disable = TRUE;
	}
	
	// Registration closed and entry window open; comp entry limit reached
	elseif ((($registration_open == 0) || ($registration_open == 2)) && ($entry_window_open == 1) && ($_SESSION['userLevel'] == 2)) {
		if ($comp_entry_limit) $add_entry_disable = TRUE;
		if ($comp_paid_entry_limit) $add_entry_disable = TRUE;
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

if (((!$add_entry_disable) && (!$edit_entry_disable) && ($remaining_entries > 0)) || ($_SESSION['userLevel'] <= 1)) {
	
	// Decalre variables
	if ($_SESSION['prefsStyleSet'] == "BJCP2008") $beer_end = 23;
	if ($_SESSION['prefsStyleSet'] == "BJCP2015") $beer_end = 34;
	
	if ($action == "edit") $collapse_icon = "fa-plus-circle";
	else $collapse_icon = "fa-pencil";
	
	if (($filter == "admin") || ($filter == "default")) $brewer_id = $_SESSION['user_id']; else $brewer_id = $filter; 

	$brewer_info = brewer_info($brewer_id);
	$brewer_info = explode("^",$brewer_info);
	
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
				$a .= "<a href=\"#\" data-toggle=\"modal\" data-target=\"#".$value[0]."\" data-tooltip=\"true\" title=\"Click for specifics about style ".$e.": ".$value[1]."\">".$e."</a>";
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
		if (($user_level <= 1) && ($go == "entries") && (!$list)) $output = "admin";
		elseif (($user_level <= 1) && ($go == "entries") && ($list)) $output = "list";
		else $output = "list";
		return $output;
	}
	
	// get information from database
	include(DB.'styles_special.db.php');
	$all_special_ing_styles = array_merge($special_beer_modal,$carb_str_sweet_special_modal,$spec_sweet_carb_only_modal,$spec_carb_only_modal);
	$all_special_ing_styles = array_unique($all_special_ing_styles);
	$all_special_ing_styles_info = array_merge($special_beer_info,$carb_str_sweet_special_info,$spec_sweet_carb_only_info,$spec_carb_only_info);
	
	$specials = display_array_content_style($all_special_ing_styles,3,$base_url); 
	$specials = rtrim($specials,", "); 
	
	$highlight_sweetness = "";
	$highlight_special = "";
	$highlight_carb = "";
	$highlight_strength = "";
	
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
		
		if ($view == "21-B") {
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
	
	// Disable fields trigger
	if ((($action == "add") && ($remaining_entries == 0) && ($_SESSION['userLevel'] == 2)) || (($action == "add") && ($entry_window_open == "2") && ($_SESSION['userLevel'] == 2))) $disable_fields = TRUE; else $disable_fields = FALSE;
	
?>
<!-- Load JS Character Counter -->
<script type="text/javascript">
// Based upon http://www.9lessons.info/2010/04/live-character-count-meter-with-jquery.html
$(document).ready(function()
{
	$("#brewInfo").keyup(function()
	{
		var box=$(this).val();
		var main = box.length * 100;
		var value= (main / <?php echo $_SESSION['prefsSpecialCharLimit']; ?>);
		var count= <?php echo $_SESSION['prefsSpecialCharLimit']; ?> - box.length;
		
		if(box.length <= <?php echo $_SESSION['prefsSpecialCharLimit']; ?>)
		{
		$('#count').html(count);
		}
		return false;
	}
	);
	
	$("#brewComments").keyup(function()
	{
		var box=$(this).val();
		var main = box.length * 100;
		var value= (main / <?php echo $_SESSION['prefsSpecialCharLimit']; ?>);
		var count= <?php echo $_SESSION['prefsSpecialCharLimit']; ?> - box.length;
		
		if(box.length <= <?php echo $_SESSION['prefsSpecialCharLimit']; ?>)
		{
		$('#count-comments').html(count);
		}
		return false;
	}
	);
}
);
</script>

<?php echo $modals; ?>
<form data-toggle="validator" role="form" class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo admin_relocate($_SESSION['userLevel'],$go,$_SERVER['HTTP_REFERER']);?>&amp;action=<?php echo $action; ?>&amp;go=<?php echo $go;?>&amp;dbTable=<?php echo $brewing_db_table; ?>&amp;filter=<?php echo $filter; if ($id != "default") echo "&amp;id=".$id; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<?php if ($_SESSION['userLevel'] > 1) { ?>
<input type="hidden" name="brewBrewerID" value="<?php echo $_SESSION['user_id']; ?>">
<input type="hidden" name="brewBrewerFirstName" value="<?php echo $_SESSION['brewerFirstName']; ?>">
<input type="hidden" name="brewBrewerLastName" value="<?php echo $_SESSION['brewerLastName']; ?>">
<?php } ?> 
<input type="hidden" name="brewJudgingNumber" value="<?php echo $row_log['brewJudgingNumber']; ?>">
	<!-- Enter or Select Brewer Name -->
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewBrewerID" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Brewer</label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <input class="form-control" name="" type="text" value="<?php echo $brewer_info[0]." ".$brewer_info[1]; ?>" disabled>
                <input type="hidden" name="brewBrewerID" value="<?php echo $brewer_info[7]; ?>">
                <span class="input-group-addon" id="brewBrewerID-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->
    <!-- Enter Co-Brewer(s) Name(s) -->
	<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
        <label for="brewCoBrewer" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Co-Brewer</label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<!-- Input Here -->
            <input class="form-control" name="brewCoBrewer" type="text" value="<?php if ($disable_fields) echo "Not Available"; if ($action == "edit") echo $row_log['brewCoBrewer']; ?>" placeholder="" <?php if ($disable_fields) echo "disabled";  ?>>
        </div>
    </div><!-- ./Form Group -->
    <!-- Enter Entry Name -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewName" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Entry Name</label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <input class="form-control" name="brewName" type="text" value="<?php if ($disable_fields) echo "Not Available"; if ($action == "edit") echo $row_log['brewName']; ?>" <?php if ((($action == "add") && ($remaining_entries == 0) && ($entry_window_open == 1) && ($filter != "default")) || (($action == "add") && ($entry_window_open == "2") && ($_SESSION['userLevel'] > 1))) echo "disabled";?> placeholder="" data-error="The entry's name is required" required>
                <span class="input-group-addon" id="brewName-addon2"><span class="fa fa-star"></span></span>
            </div>
            <div class="help-block with-errors"></div>
            <div class="help-block">Judges will not know the name of your entry.</div>
            
        </div>
    </div><!-- ./Form Group -->
    <!-- Choose Style -->
	<?php 
	// Specific code for Style select
	// Move to top after testing
	$style_set = str_ireplace("2"," 2",$_SESSION['prefsStyleSet']);
	if (empty($row_limits['prefsUserSubCatLimit'])) $user_subcat_limit = "99999";
	else $user_subcat_limit = $row_limits['prefsUserSubCatLimit'];
		
	if (empty($row_limits['prefsUSCLExLimit'])) $user_subcat_limit_exception = "99999";
	else $user_subcat_limit_exception = $row_limits['prefsUSCLExLimit'];
	
	?>
	<div class="form-group"><!-- Form Group REQUIRED Select -->
        <label for="brewStyle" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $style_set; ?> Style</label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="brewStyle" id="type" data-live-search="true" data-size="10" data-width="auto">
            <?php
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
					if (($selected_disabled == "DISABLED") && ($filter == "default")) $selection .= " [disabled - style entry limit reached]";
					if (($selected_disabled == "DISABLED") && ($filter != "default")) $selection .= " [disabled - style entry limit reached for user]";
					
				if (!empty($row_styles['brewStyleGroup'])) {
				// Display
				 ?>
				<option value="<?php echo $style_value; ?>" <?php echo $selected_disabled; ?>><?php echo $selection; ?></option>
				<?php }
				} while ($row_styles = mysqli_fetch_assoc($styles)); ?>
        </select>
        <span id="helpBlock" class="help-block">&spades; = Specific type, special ingredients, classic style, strength (for beer styles), and/or color are required.<br />&diams; = Strength Required<br />&clubs; = Carbonation Level Required<br />&hearts; = Sweetness Level Required</p></span>
        </div>
    </div><!-- ./Form Group -->
        
    <!-- Entry Requirements -->
	<div id="specialInfo" class="form-group">
    	<label for="brewInfo" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Requirements for <span id="specialInfoName">Style Name</span></label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        <p><strong class="text-primary">This style requires that you provide specific information for entry. Instructions are below.</strong>
        	<div id="specialInfoText" class="form-control-static">Entry info goes here.</div>
        </div>
    </div>
    
    <!-- Enter Special Ingredients -->
	<div id="special" class="form-group <?php if ($highlight_special) echo "has-error"; elseif (($action == "edit") && ($special_required)) echo "has-warning"; ?>"><!-- Form Group REQUIRED Text Input -->
        <label for="brewInfo" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Required Info</label>
        	<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">  
        		<textarea class="form-control" rows="8" name="brewInfo" id="brewInfo" data-error="This style requires more information. Please provide above." maxlength="<?php echo $_SESSION['prefsSpecialCharLimit']; ?>" <?php if ($highlight_special) echo "autofocus"; elseif (($action == "edit") && ($special_required)) echo "autofocus"; ?>><?php echo $brewInfo; ?></textarea>      	
            	 
            <div class="help-block with-errors"></div>
            <div id="helpBlock" class="help-block"><p><strong><?php echo $_SESSION['prefsSpecialCharLimit']; ?> character limit</strong> - use keywords and abbreviations if space is limited. Characters remaining: <span id="count"><?php echo $_SESSION['prefsSpecialCharLimit']; ?></span></p></div>
        </div>
    </div><!-- ./Form Group -->
    
    <div id="carbLambic" class="form-group">
    	<label for="carbLambic" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Carbonation</label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<label class="radio-inline">
              	<input type="radio" name="carbLambic" id="carbLambic1" value="Low Carbonation" <?php if ($action == "edit") echo $lambicCarbLow; ?>> Low
            </label>
            <label class="radio-inline">
              	<input type="radio" name="carbLambic" id="carbLambic2" value="Medium Carbonation" <?php if ($action == "edit") echo $lambicCarbMed; ?>> Medium
            </label>
            <label class="radio-inline">
              	<input type="radio" name="carbLambic" id="carbLambic3" value="High Carbonation" <?php if ($action == "edit") echo $lambicCarbHigh; ?>> High
            </label>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    
    <div id="sweetnessLambic" class="form-group">
    	<label for="sweetnessLambic" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Sweetness</label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<label class="radio-inline">
              	<input type="radio" name="sweetnessLambic" id="sweetnessLambic1" value="Low/None Sweetness" <?php if ($action == "edit") echo $lambicSweetLow; ?>> Low/None
            </label>
            <label class="radio-inline">
              	<input type="radio" name="sweetnessLambic" id="sweetnessLambic2" value="Medium Sweetness" <?php if ($action == "edit") echo $lambicSweetMed; ?>> Medium
            </label>
            <label class="radio-inline">
              	<input type="radio" name="sweetnessLambic" id="sweetnessLambic3" value="High Sweetness" <?php if ($action == "edit") echo $lambicSweetHigh; ?>> High
            </label>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    
    
    
    <div id="strengthSaison" class="form-group">
    	<label for="strengthSaison" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Strength</label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<label class="radio-inline">
              	<input type="radio" name="strengthSaison" id="strengthSaison1" value="Table Strength" <?php echo $saisonTable; ?>> Table
            </label>
            <label class="radio-inline">
              	<input type="radio" name="strengthSaison" id="strengthSaison2" value="Standard Strength" <?php echo $saisonStandard; ?>> Standard
            </label>
            <label class="radio-inline">
              	<input type="radio" name="strengthSaison" id="strengthSaison3" value="Super Strength" <?php echo $saisonSuper; ?>> Super
            </label>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    
    <div id="strengthIPA" class="form-group">
    	<label for="strengthIPA" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Strength</label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<label class="radio-inline">
              	<input type="radio" name="strengthIPA" id="strengthIPA1" value="Session Strength" <?php if ($action == "edit") echo $IPASession; ?>> Session
            </label>
            <label class="radio-inline">
              	<input type="radio" name="strengthIPA" id="strengthIPA2" value="Standard Strength" <?php if ($action == "edit") echo $IPAStandard; ?>> Standard
            </label>
            <label class="radio-inline">
              	<input type="radio" name="strengthIPA" id="strengthIPA3" value="Double Strength" <?php if ($action == "edit") echo $IPADouble; ?>> Double
            </label>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    
    <div id="BDGColor" class="form-group">
    	<label for="BDGColor" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Color</label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<label class="radio-inline">
              	<input type="radio" name="BDGColor" id="BDGColor1" value="Blonde Color" <?php if ($action == "edit") echo $BDGBlonde; ?>> Blonde
            </label>
            <label class="radio-inline">
              	<input type="radio" name="BDGColor" id="BDGColor2" value="Amber Color" <?php if ($action == "edit") echo $BDGAmber; ?>> Amber
            </label>
            <label class="radio-inline">
              	<input type="radio" name="BDGColor" id="BDGColor3" value="Brown Color" <?php if ($action == "edit") echo $BDGBrown; ?>> Brown
            </label>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    
    <div id="darkLightColor" class="form-group">
    	<label for="darkLightColor" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Color</label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<label class="radio-inline">
              	<input type="radio" name="darkLightColor" id="darkLightColor1" value="Pale Color" <?php echo $darkLightPale; ?>> Pale
            </label>
            <label class="radio-inline">
              	<input type="radio" name="darkLightColor" id="darkLightColor2" value="Amber/Dark Color" <?php echo $darkLightAmber; ?>> Amber/Dark
            </label>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    
    
    <!-- Select Strength -->
    <div id="strength">	
    	<div class="form-group"><!-- Form Group Radio INLINE -->
            <label for="brewMead3" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label <?php if ($highlight_strength) echo "text-danger"; ?>">Strength</label>
            <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <label class="radio-inline">
                        <input type="radio" name="brewMead3" value="Hydromel" id="brewMead3_0"  <?php if (($action == "edit") && ($row_log['brewMead3'] == "Hydromel")) echo "CHECKED";  ?> /> Hydromel (light)
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead3" value="Standard" id="brewMead3_1"  <?php if (($action == "edit") && ($row_log['brewMead3'] == "Standard")) echo "CHECKED";  ?> /> Standard
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead3" value="Sack" id="brewMead3_2"  <?php if (($action == "edit") && ($row_log['brewMead3'] == "Sack")) echo "CHECKED";  ?> /> Sack (strong)
                    </label>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div><!-- ./Form Group -->
    </div>
    
	<!-- Select Carbonation -->
    <div id="carbonation">
        <div id="selectCarbonation" class="form-group"><!-- Form Group Radio INLINE -->
            <label for="brewMead1" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Carbonation</label>
            <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <label class="radio-inline">
                        <input type="radio" name="brewMead1" value="Still" id="brewMead1_0" <?php if (($action == "edit") && ($row_log['brewMead1'] == "Still")) echo "CHECKED";  ?>/> Still
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead1" value="Petillant" id="brewMead1_1"  <?php if (($action == "edit") && ($row_log['brewMead1'] == "Petillant")) echo "CHECKED";  ?>/> Petillant
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead1" value="Sparkling" id="brewMead1_2"  <?php if (($action == "edit") && ($row_log['brewMead1'] == "Sparkling")) echo "CHECKED";  ?>/> Sparkling
                    </label>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div><!-- ./Form Group -->
     </div>
     <!-- Select Sweetness -->
     <div id="sweetness">
    	<div class="form-group <?php if (($highlight_carb) || ($highlight_sweetness)) echo "has-error"; ?>"><!-- Form Group Radio INLINE -->
            <label for="brewMead2" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Sweetness</label>
            <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <label class="radio-inline">
                        <input type="radio" name="brewMead2" value="Dry" id="brewMead2_0"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Dry")) echo "CHECKED";  ?> /> Dry
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead2" value="Medium Dry" id="brewMead2_1"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Medium Dry")) echo "CHECKED";  ?>/> Medium Dry
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead2" value="Medium" id="brewMead2_2"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Medium")) echo "CHECKED";  ?>/> Medium
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead2" value="Medium Sweet" id="brewMead2_3"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Medium Sweet")) echo "CHECKED";  ?>/> Medium Sweet
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewMead2" value="Sweet" id="brewMead2_4"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Sweet")) echo "CHECKED";  ?>/> Sweet
                    </label>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div><!-- ./Form Group -->
    </div>
    


    <!-- Enter Brewer's Specifics -->
    <div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
        <label for="brewComments" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Brewer&rsquo;s Specifics</label>
        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
        	<!-- Input Here -->
            <textarea rows="6" class="form-control" name="brewComments" id="brewComments" maxlength="<?php echo $_SESSION['prefsSpecialCharLimit']; ?>"><?php if ($action == "edit") echo $row_log['brewComments']; ?></textarea>
            <span id="helpBlock" class="help-block">
            	<!-- <p><strong class="text-danger">DO NOT use this field to specify special ingredients, classic style, strength (for beer entries), or color.</strong></p>-->
                <p>Use to record specifics that you would like judges to consider when evaluating your entry (e.g., mash technique, hop variety, honey variety, grape variety, pear variety, etc.). <p><strong class="text-primary">Provide only if you wish the judges to fully consider what you specify when evaluating and scoring your entry.</strong></p>
                <p><strong><?php echo $_SESSION['prefsSpecialCharLimit']; ?> character limit</strong> - use keywords and abbreviations. Characters remaining: <span id="count-comments"><?php echo $_SESSION['prefsSpecialCharLimit']; ?></span></p>
            </span>
        </div>
    </div><!-- ./Form Group -->
<?php if ($_SESSION['prefsHideRecipe'] == "N") { ?>
<div class="bcoem-form-accordion">
    <div class="panel-group" id="accordion">
    
    	<!-- General Panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseGeneral">General</a>
                </h4>
            </div>
            <div id="collapseGeneral" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewYield" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Amount Brewed</label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" name="brewYield" type="text" value="<?php if ($action == "edit") echo $row_log['brewYield']; ?>" placeholder="<?php echo $_SESSION['prefsLiquid2']; ?>" ?>
                        </div>
            		</div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewWinnerCat" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Color</label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" name="brewWinnerCat" type="text" value="<?php if ($action == "edit") echo $row_log['brewWinnerCat']; ?>" placeholder="SRM" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewWinnerCat" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Brewing Date</label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewDate"  name="brewDate" value="<?php if ($action == "edit") echo $row_log['brewDate']; ?>" placeholder="YYYY-MM-DD" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewDate" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Bottling Date</label>
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
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseGrav">Specific Gravities</a>
                </h4>
            </div>
            <div id="collapseGrav" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewOG" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Original Gravity</label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewOG" name="brewOG" value="<?php if ($action == "edit") echo $row_log['brewOG']; ?>" placeholder="e.g., 1.060" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewDate" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Final Gravity</label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewFG" name="brewFG" value="<?php if ($action == "edit") echo $row_log['brewFG']; if ($action == "importCalc") echo round ($brewFG, 3); ?>" placeholder="e.g., 1.010" ?>
                        </div>
                    </div><!-- ./Form Group -->
                </div>
            </div>
        </div>
        
        <!-- Extract Panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseExtract">Fermentables - Malt Extract</a>
                </h4>
            </div>
            <div id="collapseExtract" class="panel-collapse collapse">
                <div class="panel-body">
                <!-- Form Element(s) Begin -->
				<?php for($i=1; $i<=5; $i++) { ?>
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewExtract<?php echo $i; ?>" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Extract <?php echo $i; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewExtract<?php echo $i; ?>" name="brewExtract<?php echo $i; ?>" value="<?php if ($action == "edit") echo $row_log['brewExtract'.$i]; ?>" placeholder="Type of extract (e.g., light, dark) or brand" ?>
                        </div>
                    </div><!-- ./Form Group -->
                	<div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewExtract<?php echo $i; ?>Weight" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Extract <?php echo $i; ?> Weight</label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewExtract<?php echo $i; ?>Weight" name="brewExtract<?php echo $i; ?>Weight" value="<?php if ($action == "edit") echo $row_log['brewExtract'.$i.'Weight']; ?>" placeholder="<?php echo $_SESSION['prefsWeight2']; ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewMead3" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Extract <?php echo $i; ?> Use</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewExtract<?php echo $i; ?>Use" id="brewExtract<?php echo $i; ?>Use" value="Mash" <?php if ($action == "edit") { if (!(strcmp($row_log['brewExtract'.$i.'Use'], "Mash"))) echo "CHECKED"; }?>> Mash
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewExtract<?php echo $i; ?>Use" id="brewExtract<?php echo $i; ?>Use" value="Steep" <?php if ($action == "edit") { if (!(strcmp($row_log['brewExtract'.$i.'Use'], "Steep"))) echo "CHECKED"; }?>> Steep
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewExtract<?php echo $i; ?>Use" id="brewExtract<?php echo $i; ?>Use" value="Other" <?php if ($action == "edit") { if (!(strcmp($row_log['brewExtract'.$i.'Use'], "Other"))) echo "CHECKED"; }?>> Other
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
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseGrains">Fermentables - Grains</a>
                </h4>
            </div>
            <div id="collapseGrains" class="panel-collapse collapse">
                <div class="panel-body">
                <!-- Form Element(s) Begin -->
				  <?php for($i=1; $i<=20; $i++) { ?>
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewGrain<?php echo $i; ?>" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Grain <?php echo $i; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewGrain<?php echo $i; ?>" name="brewGrain<?php echo $i; ?>" value="<?php if ($action == "edit") echo $row_log['brewGrain'.$i]; ?>" placeholder="Type of grain (e.g., pilsner, pale ale, etc.)" ?>
                        </div>
                    </div><!-- ./Form Group -->
                	<div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewGrain<?php echo $i; ?>Weight" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Grain <?php echo $i; ?> Weight</label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewGrain<?php echo $i; ?>Weight" name="brewGrain<?php echo $i; ?>Weight" value="<?php if ($action == "edit") echo $row_log['brewGrain'.$i.'Weight']; ?>" placeholder="<?php echo $_SESSION['prefsWeight2']; ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewMead3" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Grain <?php echo $i; ?> Use</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewGrain<?php echo $i; ?>Use" id="brewGrain<?php echo $i; ?>Use" value="Mash" <?php if ($action == "edit") { if (!(strcmp($row_log['brewGrain'.$i.'Use'], "Mash"))) echo "CHECKED"; }?>> Mash
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewGrain<?php echo $i; ?>Use" id="brewGrain<?php echo $i; ?>Use" value="Steep" <?php if ($action == "edit") { if (!(strcmp($row_log['brewGrain'.$i.'Use'], "Steep"))) echo "CHECKED"; }?>> Steep
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewGrain<?php echo $i; ?>Use" id="brewGrain<?php echo $i; ?>Use" value="Other" <?php if ($action == "edit") { if (!(strcmp($row_log['brewGrain'.$i.'Use'], "Other"))) echo "CHECKED"; }?>> Other
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
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseMisc">Miscellaneous Ingredients</a>
                </h4>
            </div>
            <div id="collapseMisc" class="panel-collapse collapse">
                <div class="panel-body">
                	<!-- Form Element(s) Begin -->
					<?php for($i=1; $i<=20; $i++) { ?>
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewAddition<?php echo $i; ?>" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Miscellaneous <?php echo $i; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewAddition<?php echo $i; ?>" name="brewAddition<?php echo $i; ?>" value="<?php if ($action == "edit") echo $row_log['brewAddition'.$i]; ?>" placeholder="Type of ingredient or name" ?>
                        </div>
                    </div><!-- ./Form Group -->
                	<div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewAddition<?php echo $i; ?>Weight" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Miscellaneous <?php echo $i; ?> Weight</label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewAddition<?php echo $i; ?>Weight" name="brewAddition<?php echo $i; ?>Weight" value="<?php if (($action == "edit") && (isset($row_log['brewAddition'.$i.'Weight']))) echo $row_log['brewAddition'.$i.'Weight']; ?>" placeholder="<?php echo $_SESSION['prefsWeight2']; ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewMead3" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Miscellaneous <?php echo $i; ?> Use</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewAddition<?php echo $i; ?>Use" id="brewAddition<?php echo $i; ?>Use" value="Mash" <?php if ($action == "edit") { if (!(strcmp($row_log['brewAddition'.$i.'Use'], "Mash"))) echo "CHECKED"; }?>> Mash
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewAddition<?php echo $i; ?>Use" id="brewAddition<?php echo $i; ?>Use" value="Steep" <?php if ($action == "edit") { if (!(strcmp($row_log['brewAddition'.$i.'Use'], "Steep"))) echo "CHECKED"; }?>> Steep
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewAddition<?php echo $i; ?>Use" id="brewAddition<?php echo $i; ?>Use" value="Other" <?php if ($action == "edit") { if (!(strcmp($row_log['brewAddition'.$i.'Use'], "Other"))) echo "CHECKED"; }?>> Other
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
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseHops">Hops</a>
                </h4>
            </div>
            <div id="collapseHops" class="panel-collapse collapse">
                <div class="panel-body">
                	<?php for($i=1; $i<=20; $i++) { ?>
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewHops<?php echo $i; ?>" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Hop <?php echo $i; ?></label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewHops<?php echo $i; ?>" name="brewHops<?php echo $i; ?>" value="<?php if (($action == "edit") && (isset($row_log['brewHops'.$i]))) echo $row_log['brewHops'.$i]; ?>" placeholder="Hop name" ?>
                        </div>
                    </div><!-- ./Form Group -->
                	<div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewHops<?php echo $i; ?>Weight" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Hop <?php echo $i; ?> Weight</label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewHops<?php echo $i; ?>Weight" name="brewHops<?php echo $i; ?>Weight" value="<?php if ($action == "edit") echo $row_log['brewHops'.$i.'Weight']; ?>" placeholder="<?php echo $_SESSION['prefsWeight1']; ?>" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewHops<?php echo $i; ?>IBU" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Hop <?php echo $i; ?> Alpha Acid %</label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewHops<?php echo $i; ?>IBU" name="brewHops<?php echo $i; ?>IBU" value="<?php if ($action == "edit") echo $row_log['brewHops'.$i.'IBU']; ?>" placeholder="Numbers only (e.g., 12.2, 6.6, etc.)" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group NOT REQUIRED Text Input -->
                        <label for="brewHops<?php echo $i; ?>Time" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Hop <?php echo $i; ?> Time</label>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <!-- Input Here -->
                            <input class="form-control" type="text" id="brewHops<?php echo $i; ?>Time" name="brewHops<?php echo $i; ?>Time" value="<?php if ($action == "edit") echo $row_log['brewHops'.$i.'Time']; ?>" placeholder="Minutes" ?>
                        </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewHops<?php echo $i; ?>Use" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Hop <?php echo $i; ?> Use</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Use" id="brewHops<?php echo $i; ?>Use" value="First Wort" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "First Wort"))) echo "CHECKED"; }?>> First Wort
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Use" id="brewHops<?php echo $i; ?>Use" value="Mash" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "Mash"))) echo "CHECKED"; }?>> Mash
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Use" id="brewHops<?php echo $i; ?>Use" value="Boil" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "Boil"))) echo "CHECKED"; }?>> Boil
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Use" id="brewHops<?php echo $i; ?>Use" value="Aroma" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "Aroma"))) echo "CHECKED"; }?>> Aroma
                            </label>
                            
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Use" id="brewHops<?php echo $i; ?>Use" value="Dry Hop" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "Dry Hop"))) echo "CHECKED"; }?>> Dry Hop
                            </label>
                        </div>
                    </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewHops<?php echo $i; ?>Type" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Hop <?php echo $i; ?> Type</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Type" id="brewHops<?php echo $i; ?>Type" value="Bittering" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Type'], "Bittering"))) echo "CHECKED"; }?>> Bittering
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Type" id="brewHops<?php echo $i; ?>Type" value="Aroma" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Type'], "Aroma"))) echo "CHECKED"; }?>> Aroma
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Type" id="brewHops<?php echo $i; ?>Type" value="Both" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Type'], "Both"))) echo "CHECKED"; }?>> Both
                            </label>
                        </div>
                    </div>
                    </div><!-- ./Form Group -->
                    <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewHops<?php echo $i; ?>Form" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Hop <?php echo $i; ?> Form</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Form" id="brewHops<?php echo $i; ?>Form" value="Pellets" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Form'], "Pellets"))) echo "CHECKED"; }?>> Pellets
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Form" id="brewHops<?php echo $i; ?>Form" value="Plug" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Form'], "Plug"))) echo "CHECKED"; }?>> Plug
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Form" id="brewHops<?php echo $i; ?>Form" value="Leaf" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Form'], "Leaf"))) echo "CHECKED"; }?>> Leaf
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewHops<?php echo $i; ?>Form" id="brewHops<?php echo $i; ?>Form" value="Extract" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Form'], "Extract"))) echo "CHECKED"; }?>> Extract
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
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseMash">Mash Schedule</a>
                </h4>
            </div>
            <div id="collapseMash" class="panel-collapse collapse">
                <div class="panel-body">
                <?php for($i=1; $i<=10; $i++) { ?>
				<div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewMashStep<?php echo $i; ?>Name" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Step <?php echo $i; ?> Name</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewMashStep<?php echo $i; ?>Name" type="text" value="<?php if ($action == "edit") echo $row_log['brewMashStep'.$i.'Name']; ?>" placeholder="Saccharification rest, etc.">
                    </div>
                </div><!-- ./Form Group -->    
                    
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewMashStep<?php echo $i; ?>Time" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Step <?php echo $i; ?> Time</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewMashStep<?php echo $i; ?>Time" type="text" value="<?php if ($action == "edit") echo $row_log['brewMashStep'.$i.'Time']; ?>" placeholder="Minutes">
                    </div>
                </div><!-- ./Form Group -->
                
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewMashStep<?php echo $i; ?>Temp" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Step <?php echo $i; ?> Temperature</label>
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
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseWater">Water</a>
                </h4>
            </div>
            <div id="collapseWater" class="panel-collapse collapse">
                <div class="panel-body">
                	<div class="form-group form-group-sm"><!-- Form Group NOT-REQUIRED Text Area -->
                        <label for="brewWaterNotes" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Type/Amount</label>
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
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseYeast">Yeast</a>
                </h4>
            </div>
            <div id="collapseYeast" class="panel-collapse collapse">
                <div class="panel-body">                
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewYeast" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Yeast Name</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewYeast" type="text" value="<?php if ($action == "edit") echo $row_log['brewYeast']; ?>" placeholder="Name of strain (e.g., 1056 American Ale)">
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewYeastMan" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Lab</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewYeastMan" type="text" value="<?php if ($action == "edit") echo $row_log['brewYeastMan']; ?>" placeholder="Wyeast, White Labs, etc.">
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewYeastStarter" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Starter?</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastStarter" id="brewYeastStarter" value="Y" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastStarter'], "Y"))) echo "CHECKED"; }?>> Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastStarter" id="brewYeastStarter" value="N" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastStarter'], "N"))) echo "CHECKED"; }?>> No
                            </label>
                        </div>
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewYeastAmount" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Amount</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                    	<!-- Input Here -->
                    	<input class="form-control" name="brewYeastAmount" type="text" value="<?php if ($action == "edit") echo $row_log['brewYeastAmount']; ?>" placeholder="1 smackpack, 2 vials, 2000 ml, etc.">
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewYeastNutrients" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Nutrients</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                    	<!-- Input Here -->
                    	<input class="form-control" name="brewYeastNutrients" type="text" value="<?php if ($action == "edit") echo $row_log['brewYeastNutrients']; ?>" placeholder="">
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewYeastForm" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Form</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastForm" id="brewYeastForm" value="Liquid" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastForm'], "Liquid"))) echo "CHECKED"; }?>> Liquid
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastForm" id="brewYeastForm" value="Dry" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastForm'], "Dry"))) echo "CHECKED"; }?>> Dry
                            </label>
                        </div>
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewYeastType" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Type</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastType" id="brewYeastType" value="Ale" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Ale"))) echo "CHECKED"; }?>> Ale
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastType" id="brewYeastType" value="Lager" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Mash"))) echo "CHECKED"; }?>> Lager
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastType" id="brewYeastType" value="Wheat" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Wheat"))) echo "CHECKED"; }?>> Wheat
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastType" id="brewYeastType" value="Wine" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Wine"))) echo "CHECKED"; }?>> Wine
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastType" id="brewYeastType" value="Champagne" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Champagne"))) echo "CHECKED"; }?>> Champagne
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewYeastType" id="brewYeastType" value="Other" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Other"))) echo "CHECKED"; }?>> Other
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
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseBoil">Boil, Fermentation, Finishing, Carbonation</a>
                </h4>
            </div>
            <div id="collapseBoil" class="panel-collapse collapse">
                <div class="panel-body">
                
                <!-- Boil -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewBoilHours" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Boil Hours</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewBoilHours" type="text" value="<?php if ($action == "edit") echo $row_log['brewBoilHours']; ?>" placeholder="">
                    </div>
                </div><!-- ./Form Group -->   
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewBoilMins" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Boil Minutes</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewBoilMins" type="text" value="<?php if ($action == "edit") echo $row_log['brewBoilMins']; ?>" placeholder="">
                    </div>
                </div><!-- ./Form Group --> 
                
                <!-- Finings -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewFinings" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Finings</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewFinings" type="text" value="<?php if ($action == "edit") echo $row_log['brewFinings']; ?>" placeholder="">
                    </div>
                </div><!-- ./Form Group -->
                
                <!-- Fermentation -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewPrimary" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Primary Fermentation</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewPrimary" type="text" value="<?php if ($action == "edit") echo $row_log['brewPrimary']; ?>" placeholder="Primary fermentation in days">
                    </div>
                </div><!-- ./Form Group -->   
                
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewPrimaryTemp" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Primary Temperature</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewPrimaryTemp" type="text" value="<?php if ($action == "edit") echo $row_log['brewPrimaryTemp']; ?>" placeholder="&deg;<?php echo $_SESSION['prefsTemp']; ?>">
                    </div>
                </div><!-- ./Form Group --> 
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewSecondary" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Secondary Fermentation</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewSecondary" type="text" value="<?php if ($action == "edit") echo $row_log['brewSecondary']; ?>" placeholder="Secondary fermentation in days">
                    </div>
                </div><!-- ./Form Group -->   
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewSecondaryTemp" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Secondary Temperature</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewSecondaryTemp" type="text" value="<?php if ($action == "edit") echo $row_log['brewSecondaryTemp']; ?>" placeholder="&deg;<?php echo $_SESSION['prefsTemp']; ?>">
                    </div>
                </div><!-- ./Form Group --> 
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewOther" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Other Fermentation</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewOther" type="text" value="<?php if ($action == "edit") echo $row_log['brewOther']; ?>" placeholder="Other fermentation in days">
                    </div>
                </div><!-- ./Form Group -->   
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewOtherTemp" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Other Temperature</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewOtherTemp" type="text" value="<?php if ($action == "edit") echo $row_log['brewOtherTemp']; ?>" placeholder="&deg;<?php echo $_SESSION['prefsTemp']; ?>">
                    </div>
                </div><!-- ./Form Group --> 
                
                <!-- Carbonation -->
                <div class="form-group form-group-sm"><!-- Form Group Radio INLINE -->
                    <label for="brewCarbonationMethod" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Carbonation Method</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group small">
                            <!-- Input Here -->
                            <label class="radio-inline">
                                <input type="radio" name="brewCarbonationMethod" id="brewCarbonationMethod_0" value="Y" <?php if ($action == "edit") { if (!(strcmp($row_log['brewCarbonationMethod'], "Y"))) echo "CHECKED"; }?>> Forced CO<sub>2</sub>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="brewCarbonationMethod" id="brewCarbonationMethod_1" value="N" <?php if ($action == "edit") { if (!(strcmp($row_log['brewCarbonationMethod'], "N"))) echo "CHECKED"; }?>> Bottle Conditioned
                            </label>
                        </div>
                    </div>
                </div><!-- ./Form Group -->
                <div class="form-group form-group-sm"><!-- Form Group Text Input -->
                    <label for="brewCarbonationVol" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Carbonation Volumes of CO<sub>2</sub></label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <input class="form-control" name="brewCarbonationVol" type="text" value="<?php if ($action == "edit") echo $row_log['brewCarbonationVol']; ?>" placeholder="">
                    </div>
                </div><!-- ./Form Group --> 
                <div class="form-group form-group-sm"><!-- Form Group NOT-REQUIRED Text Area -->
                    <label for="brewCarbonationNotes" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Carbonation Type/Amount</label>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                        <!-- Input Here -->
                        <textarea class="form-control" name="brewCarbonationNotes" id="brewCarbonationNotes" rows="6"><?php if ($action == "edit") echo $row_log['brewWaterNotes']; ?></textarea>
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
	$submit_text = "Add Entry";
}

if ($action == "edit") {
	$submit_icon = "pencil";
	$submit_text = "Edit Entry";
}

?>
<div class="bcoem-admin-element hidden-print">
<div class="form-group">
    <div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-3 col-xs-12">
        <!-- Input Here -->
		<button name="submit" type="submit" class="btn btn-primary <?php if ($disable_fields) echo "disabled"; ?>" ><?php echo $submit_text; ?> <span class="fa fa-<?php echo $submit_icon; ?>"></span> </button>
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
include(INCLUDES.'form_js.inc.php');
}  // end adding and editing allowed (line 52 or so)
else {
	
if (($add_entry_disable) && ($edit_entry_disable))  echo "<p class=\"lead\">Adding and edting of entries is not available.</p>"; 
if (($add_entry_disable) && (!$edit_entry_disable))  echo "<p class=\"lead\">Adding entries is not available.</p>";
}

?>