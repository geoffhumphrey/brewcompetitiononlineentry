<?php 
/**
 * Module:      brew.sec.php
 * Description: This module houses the functionality for users to add/edit individual competition
 *              entries - references the "brewing" database table.
 *
 */
include(DB.'styles.db.php'); 
include(DB.'entries.db.php'); 

// Disaable entry add/edit if registration closed and entry window closed
if (($registration_open != 1) && ($entry_window_open != 1) && ($_SESSION['userLevel'] > 1)) {
	echo "<div class='error'>Adding/Editing Entries Not Available</div>";
	if ($registration_open == "0") echo "<p>Entry registration has not opened yet.</p>";
	if ($registration_open == "2") echo "<p>Entry registration has closed.</p>";
}

// Open but entry limit reached
// Only allow editing
elseif (($registration_open == 1) && ($_SESSION['userLevel'] > 1) && ($comp_entry_limit) && ($action == "add") && ($go != "admin")) {
	echo "<div class='error'>Adding Entries Not Available</div>";
	echo "<p>The competition entry limit has been reached.</p>";
}

// Open but personal entry limit reached
// Only allow editing
elseif (($registration_open == 1) && ($entry_window_open == 1) && ($_SESSION['userLevel'] > 1) && ($remaining_entries == 0) && ($action == "add")) {
	echo "<div class='error'>Adding Entries Not Available</div>";
	echo "<p>Your personal entry limit has been reached.</p>";
}

// Registration open, but entry window not
elseif (($registration_open == 1) && ($entry_window_open != 1) && ($_SESSION['userLevel'] > 1) && ($action == "add")) {
	echo "<div class='error'>Adding Entries Not Available</div>";
	echo "<p>You will be able to add entries on or after $entry_open.</p>";
}

// Special for NHC
// Close adding or editing during the entry window as well
elseif ((NHC) && ($_SESSION['userLevel'] > 1) && ($registration_open != 1) && ($prefix != "final_")) {
	echo "<div class='error'>Adding/Editing Entries Not Available</div>";
	echo "<p>NHC registration has closed.</p>";
}

// Special for NHC
// Close adding or editing during the entry window as well
elseif ((NHC) && ($_SESSION['userLevel'] > 1) && ($registration_open != 1) && ($entry_window_open != 1) && ($prefix == "final_")) {
	echo "<div class='error'>Adding/Editing Entries Not Available</div>";
	echo "<p>NHC registration has closed.</p>";
}

else {
	
function limit_subcategory($style,$pref_num,$pref_exception_sub_num,$pref_exception_sub_array,$uid) {
	/*
	$style = Style category and subcategory number
	$pref_num = Subcategory limit number from preferences
	$pref_exception_sub_num = The entry limit of EXCEPTED subcategories
	$pref_exception_sub_array = Array of EXCEPTED subcategories
	*/
	
	$style_break = explode("-",$style);
	
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$pref_exception_sub_array = explode(",",$pref_exception_sub_array);
	
	//$style_trimmed = ltrim($style_break[0],"0");
	
	if ($style_break[0] <= 9) $style_num = "0".$style_break[0];
	else $style_num = $style_break[0];
	
	$query_style = sprintf("SELECT id FROM %s WHERE brewStyleGroup='%s' AND brewStyleNum='%s'",$prefix."styles",$style_num,$style_break[1]); 
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	// Check if the user has a entry in the system in the subcategory
	
	$query_check = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewCategorySort='%s' AND brewSubCategory='%s'", $prefix."brewing",$uid,$style_num,$style_break[1]);
	$check = mysql_query($query_check, $brewing) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	
	if ($row_check['count'] >= $pref_num) $return = "DISABLED";
	else $return = "";
	
	
	// Check for exceptions
	if (($return == "DISABLED") && (!empty($pref_exception_sub_array))) {
		if (in_array($row_style['id'],$pref_exception_sub_array)) {
			// if so, check if the amount in the DB is greater than or equal to the "excepted" limit number
			if ((!empty($pref_exception_sub_num)) && (($row_check['count'] >= $pref_exception_sub_num))) $return = "DISABLED";
			else $return = "";
		}
	}
	//$return = $return." ".$pref_num." ".$pref_exception_sub_num." ".$pref_exception_sub_array." ".$uid;
	return $return;
}
	
function highlight_required($msg,$method) {
	
	if ($method == "0") { // special ingredients OPTIONAL mead/cider
		switch($msg) {
			case "1-24-A":
			case "1-24-B":
			case "1-24-C":
			case "1-25-A":
			case "1-25-B":
			case "1-26-B":
			case "1-26-C":
			case "1-27-B":
			case "1-27-C":
			case "1-28-C":
			return TRUE;
			break;
			
			default: 
			return FALSE;
			break;
		}
	}
	
	if ($method == "1") { // special ingredients REQUIRED beer/mead/cider
		switch($msg) {
			case "1-6-D":		
			case "1-16-E":
			case "1-17-F":
			case "1-20-A":
			case "1-21-A":
			case "1-21-B":
			case "1-22-B":
			case "1-22-C":
			case "1-23-A":
			case "1-25-C":
			case "1-26-A":
			case "1-26-C":
			case "1-27-E":
			case "1-28-A":
			case "1-28-B":
			case "1-28-C":
			case "1-28-D":
			case "4":
			return TRUE;
			break;
			
			default: 
			return FALSE;
			break;
		}
	}

	if ($method == "2") { // mead and cider carb/sweetness
		if (strstr($msg,"1-24")) return TRUE;
		elseif (strstr($msg,"1-25")) return TRUE;
		elseif (strstr($msg,"1-26")) return TRUE;
		elseif (strstr($msg,"1-27")) return TRUE;
		elseif (strstr($msg,"1-28")) return TRUE;
		else return FALSE;
	}
	
	if ($method == "3") { // mead strength
		if (strstr($msg,"1-24")) return TRUE;
		elseif (strstr($msg,"1-25")) return TRUE;
		elseif (strstr($msg,"1-26")) return TRUE;
		else return FALSE;
	}
	
	
	
}

if ($_SESSION['prefsHideRecipe'] == "N") { ?>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/toggle.js"></script>
<?php } ?>
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
}
);
</script>
<?php 

// Show/hide special ingredients depending upon the style chosen...
$special_beer = array("6-D","16-E","17-F","20-A","21-A","21-B","22-B","22-C","23-A");
$cider = array("27-A","27-D");
$special_mead = array("24-A","24-B","24-C","25-A","25-B","25-C","26-A","26-B","26-C");
$special_cider = array("27-B","27-C","27-E","28-A","28-B","28-C","28-D");
$all_execptions = array("6-D","16-E","17-F","20-A","21-A","21-B","22-B","22-C","23-A","27-A","27-D","24-A","24-B","24-C","25-A","25-B","25-C","26-A","26-B","26-C","27-B","27-C","27-E","28-A","28-B","28-C","28-D");

if ($totalRows_styles2 > 0) {

	do { 
		$style_special = ltrim($row_styles2['brewStyleGroup'],"0");
		$special_required[] = $style_special."-".$row_styles2['brewStyleNum']; 
	}  while ($row_styles2 = mysql_fetch_assoc($styles2));

//print_r ($special_required);

}

?>
<script type="text/javascript">//<![CDATA[
$(document).ready(function() {
		$("#special").hide("fast");
		$("#mead-cider").hide("fast");;
		$("#mead").hide("fast");
						   
	<?php if (($action == "edit") && (!in_array($view,$all_execptions))) { ?>
		$("#special").hide();
		$("#mead-cider").hide();
		$("#mead").hide();
	<?php } ?>
	<?php if (($action == "edit") && (in_array($view,$special_beer))) { ?>
		$("#special").show("slow");
		$("#mead-cider").hide();
		$("#mead").hide();
	<?php } ?>
	<?php if (($action == "edit") && (in_array($view,$cider))) { ?>
		$("#special").hide();
		$("#mead-cider").show("slow");
		$("#mead").hide();
	<?php } ?>
	<?php if (($action == "edit") && (in_array($view,$special_mead))) { ?>
		$("#special").show("slow");
		$("#mead-cider").show("slow");
		$("#mead").show("slow");
	<?php } ?>
	<?php if (($action == "edit") && (in_array($view,$special_cider))) { ?>
		$("#special").show("slow");
		$("#mead-cider").show("slow");
		$("#mead").hide();
	<?php } ?>
	<?php if (($action == "edit") && (in_array($view,$special_required))) { ?>
		$("#special").show("slow");
		$("#mead-cider").show("slow");
		$("#mead").show("slow");
	<?php } ?>
	<?php if (($action == "edit") && ($msg != "")) { ?>	
		<?php if (highlight_required($msg,0)) { ?>
		$("#special").show("slow");
		<?php } ?>
		<?php if (highlight_required($msg,1)) { ?>
		$("#special").show("slow");
		<?php } ?>
		<?php if (highlight_required($msg,2)) { ?>
		$("#mead-cider").show("slow");						  
		<?php } ?>
		<?php if (highlight_required($msg,3)) { ?>
		$("#mead-cider").show("slow");
		$("#mead").show("slow");
		<?php } ?>
	<?php }?>
	
	$("#type").change(function() {
	 	$("#special").hide("fast");
		$("#mead-cider").hide("fast");;
		$("#mead").hide("fast");
		if ( 
			$("#type").val() == "25-C"){
			$("#special").hide("fast");
			$("#mead-cider").hide("fast");
			$("#mead").hide("fast");
			$("#special").show("slow");
			$("#mead-cider").show("slow");
			$("#mead").show("slow");
		}
		<?php foreach ($cider as $value) { ?>
		else if ( 
			$("#type").val() == "<?php echo $value; ?>"){
			$("#special").hide("fast");
			$("#mead").hide("fast");
			$("#mead-cider").hide("fast");
			$("#mead-cider").show("slow");
			
		}
		<?php } ?>
		
		<?php foreach ($special_mead as $value) { ?>
		else if ( 
			$("#type").val() == "<?php echo $value; ?>"){
			$("#special").hide("fast");
			$("#mead").hide("fast");
			$("#mead-cider").hide("fast");
			$("#special").show("slow");
			$("#mead").show("slow");
			$("#mead-cider").show("slow");
		}
		<?php } ?>
		
		<?php foreach ($special_required as $value) { ?>
		else if ( 
			$("#type").val() == "<?php echo $value; ?>"){
			$("#special").hide("fast");
			$("#mead-cider").hide("fast");
			$("#mead").hide("fast");
			$("#special").show("slow");
		}
		<?php } ?>
		
		<?php foreach ($special_cider as $value) { ?>
		else if ( 
			$("#type").val() == "<?php echo $value; ?>"){
			$("#special").hide("fast");
			$("#mead-cider").hide("fast");
			$("#mead").hide("fast");
			$("#special").show("slow");
			$("#mead-cider").show("slow");
		}
		<?php } ?>
		
		<?php foreach ($special_beer as $value) { ?>
		else if ( 
			$("#type").val() == "<?php echo $value; ?>"){
			$("#special").hide("fast");
			$("#mead-cider").hide("fast");
			$("#mead").hide("fast");
			$("#special").show("slow");
		}
		<?php } ?>
		
		<?php 
		if ($totalRows_custom_styles > 0) {
		foreach ($a as $value) { ?>
		else if ( 
			$("#type").val() == "<?php echo $value; ?>"){
			$("#mead-cider").hide("fast");
			$("#special").show("slow");
			
		}
		<?php } 
		}
		?>
		
		else{
			$("#special").hide("fast");
			$("#mead-cider").hide("fast");
			
		}	
	}
	);
}
);
</script>

<?php 

if (($action != "print") && ($msg != "default")) echo $msg_output;

if ((($action == "add") && ($remaining_entries == 0) && ($_SESSION['userLevel'] == 2)) || (($action == "add") && ($registration_open == "2") && ($_SESSION['userLevel'] == 2))) $disable_fields = TRUE; else $disable_fields = FALSE;

if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_top.inc.php');
if (($action == "add") || (($action == "edit") && (($_SESSION['user_id'] == $row_log['brewBrewerID']) || ($_SESSION['userLevel'] <= 1)))) {
	if ($filter == "default") { ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/help.png"  /></span><a id="modal_window_link" href="http://help.brewcompetition.com/files/add_edit_entries.html" title="BCOE&amp;M Help: Add/Edit an Entry">Add/Edit an Entry Help</a></p>
<p>The more complete you are here, the more information will be reflected on your required entry forms (generated by clicking the printer icon from your <a href="<?php echo build_public_url("list","default","default",$sef,$base_url); ?>">list of entries</a>).</p>
<?php if (($action == "add") && ($_SESSION['prefsHideRecipe'] == "N") && ($remaining_entries > 0)) { ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/page_code.png"  /></span>You can also <a href="<?php echo build_public_url("beerxml","default","default",$sef,$base_url); ?>">import your entry's BeerXML document</a>.</p>
<?php } } ?>
<script>
	$(function() {
		$( "#brewDate" ).datepicker({ dateFormat: 'yy-mm-dd', showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true });;
		$( "#brewBottleDate" ).datepicker({ dateFormat: 'yy-mm-dd', showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true });;
	});
	</script>
<?php 
function admin_relocate($user_level,$go,$referrer) {
	
	if (strstr($referrer,"list")) $list = TRUE;
	if (strstr($referrer,"entries")) $list = FALSE;
	if (($user_level <= 1) && ($go == "entries") && ($list == FALSE)) $output = "admin";
	elseif (($user_level <= 1) && ($go == "entries") && ($list == TRUE)) $output = "list";
	else $output = "list";
	return $output;
	
}

if ($action == "edit") $collapse_icon = '<span class="icon"><img src="'.$base_url.'images/pencil.png"  /></span>';
else $collapse_icon = '<span class="icon"><img src="'.$base_url.'images/add.png" /></span>';
?>
<form action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo admin_relocate($_SESSION['userLevel'],$go,$_SERVER['HTTP_REFERER']);?>&amp;action=<?php echo $action; ?>&amp;go=<?php echo $go;?>&amp;dbTable=<?php echo $brewing_db_table; ?>&amp;filter=<?php echo $filter; if ($id != "default") echo "&amp;id=".$id; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<?php if ($_SESSION['userLevel'] == 2) { ?>
<input type="hidden" name="brewBrewerID" value="<?php echo $_SESSION['user_id']; ?>">
<input type="hidden" name="brewBrewerFirstName" value="<?php echo $_SESSION['brewerFirstName']; ?>">
<input type="hidden" name="brewBrewerLastName" value="<?php echo $_SESSION['brewerLastName']; ?>">
<?php } ?> 
<h2>Required Information</h2>
<p><input type="submit" class="button" value="Submit Entry" alt="Submit Entry" <?php if (($action == "add") && ($remaining_entries == 0) && ($_SESSION['user_level'] == 2)) echo "DISABLED"; ?> /></p>
<table>
<?php
if (($filter == "admin") || ($filter == "default")) $brewer_id = $_SESSION['user_id']; else $brewer_id = $filter; 

$query_brewer = sprintf("SELECT uid,brewerFirstName,brewerLastName FROM $brewer_db_table WHERE uid='%s'",$brewer_id);
$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
$row_brewer = mysql_fetch_assoc($brewer);

?>
<tr>
   <td class="dataLabel">Brewer:</td>
   <td class="data">
   <?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?>
   <input type="hidden" name="brewBrewerID" value="<?php echo $row_brewer['uid']; ?>">
   </td>
   <td class="data">&nbsp;</td>
</tr>
<tr>
  <td class="dataLabel">Co-Brewer:</td>
  <td class="data">
  <?php if ((NHC) && ($prefix == "final_") && ($action == "edit") && ($_SESSION['userLevel'] == 2)) echo $row_log['brewCoBrewer']; else { ?>
  <input type="text"  name="brewCoBrewer" value="<?php if ($disable_fields) echo "Not Available"; if ($action == "edit") echo $row_log['brewCoBrewer']; ?>" size="30" <?php if ($disable_fields) echo "DISABLED";  ?>>
  <?php } ?>
  </td>
</tr>
<tr>
   <td class="dataLabel">Entry Name:</td>
   <td class="data">
    <?php if ((NHC) && ($prefix == "final_") && ($action == "edit") && ($_SESSION['userLevel'] == 2)) { ?>
    <?php echo $row_log['brewName']; ?>
	<input type="hidden" name="brewName" id="type" value="<?php echo $row_log['brewName'] ?>">
	<?php } else { ?>
   <input type="text"  name="brewName" value="<?php if ($disable_fields) echo "Not Available"; if ($action == "edit") echo $row_log['brewName']; ?>" size="30" <?php if ((($action == "add") && ($remaining_entries == 0) && ($registration_open == 1) && ($filter != "default")) || (($action == "add") && ($registration_open == "2") && ($_SESSION['userLevel'] > 1))) echo "DISABLED";  ?>>
   <?php } ?>
   </td>
   <td class="data"><?php if (!NHC) { ?><span class="required">Required for all entries</span><?php } ?></td>
</tr>
<tr>
   <td class="dataLabel">Style:</td>
   <td class="data">
   <?php if ((NHC) && ($prefix == "final_") && ($action == "edit") && ($_SESSION['userLevel'] == 2)) { ?>
   <input type="hidden" name="brewStyle" id="type" value="<?php echo $view; ?>"><?php echo $row_log['brewCategory'].$row_log['brewSubCategory'].": ".$row_log['brewStyle']; ?>
   <?php } elseif (((NHC) && ($prefix == "final_") && ($_SESSION['userLevel'] < 2) && (($action == "add") || ($action == "edit"))) || ((NHC) && ($prefix != "final_")) || (!NHC)) { ?>
	<select name="brewStyle" id="type">
	 	<?php
		do {
			// Build style drop-down
			
			// Option value variable
			$style_value = ltrim($row_styles['brewStyleGroup'], "0")."-".$row_styles['brewStyleNum'];
			if (empty($row_limits['prefsUserSubCatLimit'])) $user_subcat_limit = "99999";
			else $user_subcat_limit = $row_limits['prefsUserSubCatLimit'];
			
			if (empty($row_limits['prefsUSCLExLimit'])) $user_subcat_limit_exception = "99999";
			else $user_subcat_limit_exception = $row_limits['prefsUSCLExLimit'];
			
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
			if (($action == "add") && ($remaining_entries > 0) && (!$disable_fields)) $selected_disabled = $subcat_limit; 
			elseif ($disable_fields) $selected_disabled = "DISABLED";
			
			// Build selection variable
			$selection = ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum']." ".$row_styles['brewStyle'];
			if ($selected_disabled == "DISABLED") $selection .= " [disabled - subcategory entry limit reached]";
		if (!empty($row_styles['brewStyleGroup'])) {?>
		<option value="<?php echo $style_value; ?>" <?php echo $selected_disabled; ?>><?php echo $selection; ?></option>
   		<?php }
		} while ($row_styles = mysql_fetch_assoc($styles)); ?>
   	</select>
   	<?php } ?>
   	</td>
   	<td class="data"><span class="required">Required for all entries</span><span class="icon"><img src="<?php echo $base_url; ?>images/information.png" /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/styles.php">View Accepted Styles</a></td>
</tr>
</table>
<div id="special">
<table>
<tr>
   <td class="dataLabel">Special Ingredients and/or Classic Style:</td>
</tr>

<?php if ((NHC) && ($prefix == "final_") && ($action == "edit") && ($_SESSION['userLevel'] == 2)) { ?>
<tr>
	<td class="dataLeft">
	<?php echo $row_log['brewInfo']; ?>
    <input type="hidden" name="brewInfo" id="type" value="<?php echo $row_log['brewInfo'] ?>">
    </td>
</tr>
<?php } else { ?>
<tr>
   <td class="dataLeft">
   
   	<p>
    <span class="required"><em>Required for categories 6D, 16E, 17F, 20, 21, 22B, 22C, 23, 25C, 26A, 26C, 27E, and 28B-D.</em></span><br />
   	<span class="required"><em>Base style required for categories 20, 21, and 22B</em>.</span> Specify if the entry is based on a classic style (e.g., Blonde Ale or Belgian Tripel). Otherwise, more general categories are acceptable (e.g., &ldquo;wheat ale&rdquo; or &ldquo;porter&rdquo;). 	  </p>
   	<ul>
   	  <li><strong><?php echo $_SESSION['prefsSpecialCharLimit']; ?> character limit</strong> - use keywords and abbreviations. <?php if ($_SESSION['prefsHideRecipe'] == "N") echo "Use the Brewer's Specifics field under &ldquo;General&rdquo; area below to add information <strong>NOT essential to judging your entry</strong>."; ?></li>
    	<li>Enter the base style (if appropriate) and specialty nature of your beer/mead/cider in the following format: <em>base style, special nature</em>.
    	    <ul>
    	        <li>Beer example: <em>robust porter, clover honey, sour cherries</em> or <em>wheat ale, anaheim/jalape&ntilde;o chiles</em>, etc.</li>
    	        <li>Mead example: <em>wildflower honey, blueberries</em> or <em>traditional tej with gesho</em>, etc.</li>
    	        <li>Cider example: <em>golden russet apples, clove, cinnamon</em> or <em>strawberry and rhubarb</em>, etc.</li>
    	        </ul>
	    </li>
    </ul>
   
  </td>
</tr>
<tr>
   <td class="dataLeft"><input type="text" <?php if (highlight_required($msg,"1")) echo "class=\"special-required\""; ?> name="brewInfo" id="brewInfo" value="<?php if ($action == "edit") echo $row_log['brewInfo'];?>" maxlength="<?php echo $_SESSION['prefsSpecialCharLimit']; ?>" size="<?php echo $_SESSION['prefsSpecialCharLimit']; ?>"></td>
</tr>
<tr>
   <td class="dataLeft">Characters remaining: <span id="count" style="font-weight:bold"><?php echo $_SESSION['prefsSpecialCharLimit']; ?></span></td>
</tr>
 <?php } ?>
</table>
</div>
<div id="mead-cider" <?php if (highlight_required($msg,"2")) echo "class=\"special-required\""; ?>>
<table>
<tr>
   <td class="dataLabel" colspan="2">For Mead and Cider:</td>
</tr>
<tr>
   <td class="dataLeft" colspan="2"><em>Required for categories 24, 25, 26, 27, and 28</em></td>
</tr>
<?php if ((NHC) && ($prefix == "final_") && ($action == "edit") && ($_SESSION['userLevel'] == 2)) { ?>
<tr>
	<td width="1%" class="dataLeft">Carbonation:</td>
    <td class="data">
	<?php echo $row_log['brewMead1']; ?>
     <input type="hidden" name="brewMead1" id="type" value="<?php echo $row_log['brewMead1'] ?>">
     <input type="hidden" name="brewMead2" id="type" value="<?php echo $row_log['brewMead2'] ?>">
    </td>
</tr>
<tr>
	<td class="dataLeft">Sweetness:</td>
    <td class="data"><?php echo $row_log['brewMead2']; ?></td>
</tr>
<?php } else { ?>
<tr>
   <td class="dataLeft">Carbonation (Choose ONE):</td>
   <td class="dataLeft">Sweetness (Choose ONE):</td>
</tr>
<tr>
   <td class="data"><input type="radio" name="brewMead1" value="Still" id="brewMead1_0" <?php if (($action == "edit") && ($row_log['brewMead1'] == "Still")) echo "CHECKED";  ?>/> Still<br /><input type="radio" name="brewMead1" value="Petillant" id="brewMead1_1"  <?php if (($action == "edit") && ($row_log['brewMead1'] == "Petillant")) echo "CHECKED";  ?>/> Petillant<br /><input type="radio" name="brewMead1" value="Sparkling" id="brewMead1_2"  <?php if (($action == "edit") && ($row_log['brewMead1'] == "Sparkling")) echo "CHECKED";  ?>/> Sparkling</td>
   <td class="data"><input type="radio" name="brewMead2" value="Dry" id="brewMead2_0"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Dry")) echo "CHECKED";  ?> /> Dry<br /><input type="radio" name="brewMead2" value="Semi-Sweet" id="brewMead2_1"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Semi-Sweet")) echo "CHECKED";  ?>/> Semi-Sweet<br /><input type="radio" name="brewMead2" value="Sweet" id="brewMead2_2"  <?php if (($action == "edit") && ($row_log['brewMead2'] == "Sweet")) echo "CHECKED";  ?>/> Sweet</td>
</tr>
<?php } ?>
</table>
</div>
<div id="mead" <?php if (highlight_required($msg,"3")) echo "class=\"special-required\"" ?>>
<table>
<tr>
   <td class="dataLabel" colspan="2">For Mead:</td>
</tr>
<tr>
   <td class="dataLeft" colspan="2"><em>Required for categories 24, 25, and 26</em></td>
</tr>
<?php if ((NHC) && ($prefix == "final_") && ($action == "edit") && ($_SESSION['userLevel'] == 2)) { ?>
<tr>
	<td width="1%" class="dataLeft">Strength:</td>
    <td class="data">
	<?php echo $row_log['brewMead3']; ?>
    <input type="hidden" name="brewMead3" id="type" value="<?php echo $row_log['brewMead3'] ?>">
    </td>
</tr>
<?php } else { ?>
<tr>
   <td class="dataLeft" colspan="2">Strength (Choose ONE):</td>
</tr>
<tr>
   <td class="data"><input type="radio" name="brewMead3" value="Hydromel" id="brewMead3_0"  <?php if (($action == "edit") && ($row_log['brewMead3'] == "Hydromel")) echo "CHECKED";  ?> /> Hydromel (light)<br /><input type="radio" name="brewMead3" value="Standard" id="brewMead3_1"  <?php if (($action == "edit") && ($row_log['brewMead3'] == "Standard")) echo "CHECKED";  ?> /> Standard<br /><input type="radio" name="brewMead3" value="Sack" id="brewMead3_2"  <?php if (($action == "edit") && ($row_log['brewMead3'] == "Sack")) echo "CHECKED";  ?> /> Sack (strong)</td>
   <td>&nbsp;</td>
</tr>
<?php } ?>
</table>
</div>
<?php if ($_SESSION['prefsHideRecipe'] == "N") { 
if (NHC) { ?>
<h2>Recipe Information</h2>
<p>A condition of your entry into the final round of the National Homebrew Competition is providing your entry's recipe.</p>
<p>Click the headings below to expand and collapse each category, filling out all applicable information.</p>
<?php } else { ?>
<h2>Optional Information</h2>
<p>The information below is not required to process your entry. However, the more information you provide about your entry, the more complete the required entry documentation will be.</p>
<p>Click the headings below to expand and collapse each category.</p>
<?php } ?>

<div id="menu_container"> 
<div id="outer"> 
<div class="menus">
<h4 class="trigger"><?php echo $collapse_icon; ?>General</h4>
<div class="toggle_container">
<table>
<tr>
   <td class="dataLabel">Amount Brewed:</td>
   <td class="data"><input name="brewYield" type="text" size="10" value="<?php if ($action == "edit") echo $row_log['brewYield']; ?>">&nbsp;<?php echo $_SESSION['prefsLiquid2']; ?></td>
</tr>
<tr>
  <td class="dataLabel">Color (SRM)</td>
  <td class="data"><input name="brewWinnerCat" type="text" size="10" value="<?php if ($action == "edit") echo $row_log['brewWinnerCat']; ?>" /></td>
</tr>
    <tr>
       <td class="dataLabel"><a name="specifics"></a>Brewer's Specifics:</td>
       <td class="data"><em>Only use to record special procedures, brewing techniques, etc.</em></td>
	</tr>
    <tr>
    	<td colspan="2"><textarea name="brewComments" cols="60" rows="5" id="brewComments"><?php if ($action == "edit") echo $row_log['brewComments']; ?></textarea></td>
    </tr>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?>Dates</h4>
<div class="toggle_container">
<table>
<tr>
  <td class="dataLabel">Brewing Date:</td>
  <td class="data"><input type="text" id="brewDate"  name="brewDate" value="<?php if ($action == "edit") echo $row_log['brewDate']; ?>" size="20">&nbsp;YYYY-MM-DD</td>
</tr>
<tr>
  <td class="dataLabel">Bottling Date:</td>
  <td class="data"><input type="text" id="brewBottleDate" name="brewBottleDate" value="<?php if ($action == "edit") echo $row_log['brewBottleDate']; ?>" size="20">&nbsp;YYYY-MM-DD</td>
</tr>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?>Specific Gravities</h4>
<div class="toggle_container">
<table>
<tr>
   <td  class="dataLabel">Original:</td>
   <td class="data"><input name="brewOG" type="text" size="10" tooltipText="<?php echo $toolTip_gravity; ?>" value="<?php if ($action == "edit") echo $row_log['brewOG']; ?>"></td>
</tr>
<tr>
   <td class="dataLabel">Final:</td>
   <td class="data"><input name="brewFG" type="text" size="10" tooltipText="<?php echo $toolTip_gravity; ?>" value="<?php if ($action == "edit") echo $row_log['brewFG']; if ($action == "importCalc") echo round ($brewFG, 3); ?>"></td>
</tr>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?>Fermentables: Malt Extracts</h4>
<div class="toggle_container">
<table>
<?php for($i=1; $i<=5; $i++) { ?>
<tr>
   <td class="dataLabel">Extract <?php echo $i; ?>:</td>
   <td class="data"><input name="brewExtract<?php echo $i; ?>" type="text" value="<?php if ($action == "edit") echo $row_log['brewExtract'.$i]; ?>"></td>
   <td class="dataLabel">Weight:</td>
   <td class="data"><input name="brewExtract<?php echo $i; ?>Weight" type="text" id="brewExtract<?php echo $i; ?>Weight" value="<?php if ($action == "edit") echo $row_log['brewExtract'.$i.'Weight']; ?>" size="5">&nbsp;<?php echo $_SESSION['prefsWeight2']; ?></td>
   <td class="dataLabel">Use:</td>
   <td class="data">
   		<select name="brewExtract<?php echo $i; ?>Use" id="brewExtract<?php echo $i; ?>Use">
			<option value=""></option>
			<option value="Mash" <?php if ($action == "edit") { if (!(strcmp($row_log['brewExtract'.$i.'Use'], "Mash"))) echo "SELECTED"; }?>>Mash</option>
        	<option value="Steep" <?php if ($action == "edit") { if (!(strcmp($row_log['brewExtract'.$i.'Use'], "Steep"))) echo "SELECTED"; }?>>Steep</option>
        	<option value="Other" <?php if ($action == "edit") { if (!(strcmp($row_log['brewExtract'.$i.'Use'], "Other"))) echo "SELECTED"; }?>>Other</option>
        </select>
   </td>
</tr>
<?php } ?>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?>Fermentables: Grain</h4>
<div class="toggle_container">
<table>
<?php for($i=1; $i<=20; $i++) { ?>
<tr>
   <td class="dataLabel">Grain <?php echo $i; ?>:</td>
   <td class="data"><input name="brewGrain<?php echo $i; ?>" type="text" id="brewGrain<?php echo $i; ?>" value="<?php if ($action == "edit") echo $row_log['brewGrain'.$i]; ?>"></td> 
   <td class="dataLabel">Weight:</td>
   <td class="data"><input name="brewGrain<?php echo $i; ?>Weight" type="text" id="brewGrain<?php echo $i; ?>Weight" value="<?php if ($action == "edit") echo $row_log['brewGrain'.$i.'Weight']; ?>" size="5">&nbsp;<?php echo $_SESSION['prefsWeight2']; ?></td>
   <td class="dataLabel">Use:</td>
   <td class="data">
   		<select name="brewGrain<?php echo $i; ?>Use" id="brewGrain<?php echo $i; ?>Use">
			<option value=""></option>
			<option value="Mash" <?php if ($action == "edit") { if (!(strcmp($row_log['brewGrain'.$i.'Use'], "Mash"))) echo "SELECTED"; }?>>Mash</option>
        	<option value="Steep" <?php if ($action == "edit") { if (!(strcmp($row_log['brewGrain'.$i.'Use'], "Steep"))) echo "SELECTED"; }?>>Steep</option>
        	<option value="Other" <?php if ($action == "edit") { if (!(strcmp($row_log['brewGrain'.$i.'Use'], "Other"))) echo "SELECTED"; }?>>Other</option>
        </select>
   </td>
</tr>
<?php } ?>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?>Other Ingredients</h4>
<div class="toggle_container">
<table>
<?php for($i=1; $i<=20; $i++) { ?>
<tr>
   <td class="dataLabel">Other <?php echo $i; ?>:</td>
   <td class="data"><input name="brewAddition<?php echo $i; ?>" type="text" id="brewAddition<?php echo $i; ?>" value="<?php if ($action == "edit") echo $row_log['brewAddition'.$i]; ?>"></td> 
   <td class="dataLabel">Weight:</td>
   <td class="data"><input name="brewAddition<?php echo $i; ?>Amt" type="text" value="<?php if ($action == "edit") echo $row_log['brewAddition'.$i.'Amt']; ?>" size="10" maxlength="20">&nbsp;<?php echo $_SESSION['prefsWeight2']; ?></td>
   <td class="dataLabel">Use:</td>
   <td class="data">
   		<select name="brewAddition<?php echo $i; ?>Use" id="brewAddition<?php echo $i; ?>Use">
			<option value=""></option>
			<option value="Mash" 	<?php if ($action == "edit") { if (!(strcmp($row_log['brewAddition'.$i.'Use'], "Mash")))  echo "SELECTED"; }?>>Mash</option>
        	<option value="Steep" 	<?php if ($action == "edit") { if (!(strcmp($row_log['brewAddition'.$i.'Use'], "Steep"))) echo "SELECTED"; }?>>Steep</option>
        	<option value="Other" 	<?php if ($action == "edit") { if (!(strcmp($row_log['brewAddition'.$i.'Use'], "Other"))) echo "SELECTED"; }?>>Other</option>
        </select>
   </td>
</tr>
<?php } ?>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?>Hops</h4>
<div class="toggle_container">
<table>
<tr>
   <td>&nbsp;</td>
   <td class="dataLabel data">Name</td>
   <td class="dataLabel data">Weight</td>
   <td class="dataLabel data">AA%</td>
   <td class="dataLabel data">Time</td>
   <td class="dataLabel data">Use</td>
   <td class="dataLabel data">Type</td>
   <td class="dataLabel data">Form</td>
</tr>
<?php for($i=1; $i<=20; $i++) { ?>
<tr>
   <td class="dataLabel">Hop <?php echo $i; ?>:</td>
   <td class="data"><input name="brewHops<?php echo $i; ?>" type="text" id="brewHops<?php echo $i; ?>" value="<?php if ($action == "edit") echo $row_log['brewHops'.$i]; ?>"></td> 
   <td class="data"><input name="brewHops<?php echo $i; ?>Weight" type="text" id="brewHops<?php echo $i; ?>Weight" size="5" value="<?php if ($action == "edit") echo $row_log['brewHops'.$i.'Weight']; ?>">&nbsp;<?php echo $_SESSION['prefsWeight1']; ?></td>
   <td class="data"><input name="brewHops<?php echo $i; ?>IBU" type="text" id="brewHops<?php echo $i; ?>IBU" size="5" value="<?php if ($action == "edit") echo $row_log['brewHops'.$i.'IBU']; ?>">&nbsp;%</td>
   <td class="data"><input type="text" name="brewHops<?php echo $i; ?>Time" size="5" value="<?php if ($action == "edit") echo $row_log['brewHops'.$i.'Time']; ?>">&nbsp;min.</td>
   <td class="data">
   <select name="brewHops<?php echo $i; ?>Use" id="brewHops<?php echo $i; ?>Use">
        <option value=""></option>
		<option value="Boil" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "Boil"))) echo "SELECTED"; }?>>Boil</option>
        <option value="Dry Hop" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "Dry Hop"))) echo "SELECTED"; }?>>Dry Hop</option>
        <option value="Mash" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "Mash"))) echo "SELECTED"; }?>>Mash</option>
        <option value="Aroma" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "Aroma"))) echo "SELECTED"; }?>>Aroma</option>
        <option value="First Wort" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Use'], "First Wort"))) echo "SELECTED"; }?>>First Wort</option>
	</select>
    </td>
    <td class="data">
    <select name="brewHops<?php echo $i; ?>Type" id="brewHops<?php echo $i; ?>Type">
        <option value=""></option>
		<option value="Bittering" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Type'], "Bittering"))) echo "SELECTED"; } ?>>Bittering</option>
        <option value="Aroma" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Type'], "Aroma"))) echo "SELECTED"; } ?>>Aroma</option>
        <option value="Both" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Type'], "Both"))) echo "SELECTED"; } ?>>Both</option>
 	</select>
    </td>
   	<td class="data">
    <select name="brewHops<?php echo $i; ?>Form" id="brewHops<?php echo $i; ?>Form">
        <option value=""></option>
		<option value="Pellets" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Form'], "Pellets"))) echo "SELECTED"; } ?>>Pellets</option>
        <option value="Plug" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Form'], "Plug"))) echo "SELECTED"; } ?>>Plug</option>
        <option value="Leaf" <?php if ($action == "edit") { if (!(strcmp($row_log['brewHops'.$i.'Form'], "Leaf"))) echo "SELECTED"; } ?>>Leaf</option>
    </select>            
    </td>
  </tr>
<?php } ?>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?>Mash Schedule</h4>
<div class="toggle_container">
<table>
<?php for($i=1; $i<=10; $i++) { ?>
<tr>
   <td class="dataLabel">Step <?php echo $i; ?> Name:</td>
   <td class="data"><input name="brewMashStep<?php echo $i; ?>Name" type="text" id="brewMashStep<?php echo $i; ?>Name" size="30" value="<?php if ($action == "edit") echo $row_log['brewMashStep'.$i.'Name']; ?>"></td>
   <td class="dataLabel">Time:</td>
   <td class="data"><input name="brewMashStep<?php echo $i; ?>Time" type="text" id="brewMashStep<?php echo $i; ?>Time" size="5" value="<?php if ($action == "edit") echo $row_log['brewMashStep'.$i.'Time']; ?>">&nbsp;min.</td>
   <td class="dataLabel">Temp:</td>
   <td class="data"><input name="brewMashStep<?php echo $i; ?>Temp" type="text" id="brewMashStep<?php echo $i; ?>Temp" size="5" value="<?php if ($action == "edit") echo $row_log['brewMashStep'.$i.'Temp']; ?>">&nbsp;&deg;<?php echo $_SESSION['prefsTemp']; ?></td>
</tr>
<?php } ?>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?>Water Treatment</h4>
<div class="toggle_container">
<table>
<tr>
   <td class="dataLabel">Type/Amount:</td>
   <td class="data"><textarea name="brewWaterNotes" cols="60" rows="5" id="brewWaterNotes"><?php if ($action == "edit") echo $row_log['brewWaterNotes']; ?></textarea></td>
</tr>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?>Yeast Culture</h4>
<div class="toggle_container">
<table>
<tr>
   <td class="dataLabel">Name:</td>
   <td class="data"><input type="text" name="brewYeast" size="30" tooltipText="Enter the name of the yeast and the catalog number." value="<?php if ($action == "edit") echo $row_log['brewYeast']; ?>"></td>
   <td class="dataLabel">Manufacturer:</td>
   <td class="data"><input name="brewYeastMan" type="text" id="brewYeastMan" size="30" tooltipText="Indicate the yeast manufacturer's name." value="<?php if ($action == "edit") echo $row_log['brewYeastMan']; ?>"></td>
</tr>
<tr>
   <td class="dataLabel">Form:</td>
   <td class="data">
   <select name="brewYeastForm" id="brewYeastForm">
        <option value=""></option>
	    <option value="Liquid" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastForm'], "Liquid"))) echo "SELECTED"; }?>>Liquid</option>
        <option value="Dry" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastForm'], "Dry"))) echo "SELECTED"; }?>>Dry</option>
   </select>
   </td>
   <td class="dataLabel">Type:</td>
   <td class="data">
   <select name="brewYeastType" id="brewYeastType">
		<option value=""></option>
        <option value="Ale" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Ale"))) echo "SELECTED"; }?>>Ale</option>
        <option value="Lager" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Lager"))) echo "SELECTED"; }?>>Lager</option>
        <option value="Wheat" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Wheat"))) echo "SELECTED"; }?>>Wheat</option>
        <option value="Wine" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Wine"))) echo "SELECTED"; }?>>Wine</option>
        <option value="Champagne" <?php if ($action == "edit") { if (!(strcmp($row_log['brewYeastType'], "Champagne"))) echo "SELECTED"; }?>>Champagne</option>
   </select></td>
</tr>
<tr>
   <td class="dataLabel">Amount:</td>
   <td class="data"><input name="brewYeastAmount" type="text" id="brewYeastAmount" size="30" tooltipText="<?php echo $toolTip_decimal; ?>" value="<?php if ($action == "edit") echo $row_log['brewYeastAmount']; ?>"></td>
   <td class="dataLabel">Starter?</td>
   <td class="data"><input type="radio" name="brewYeastStarter" value="Y" id="brewYeastStarter_0"  <?php if (($action == "edit") && ($row_log['brewYeastStarter'] == "Y")) echo "CHECKED"; ?> /> 
   Yes&nbsp;&nbsp;
   <input type="radio" name="brewYeastStarter" value="N" id="brewYeastStarter_1" <?php if (($action == "edit") && ($row_log['brewYeastStarter'] == "N")) echo "CHECKED"; ?>/> 
   No</td>
</tr>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?></span>Yeast Nutrients</h4>
<div class="toggle_container">
<table>
<tr>
   <td class="dataLabel">Type/Amount:</td>
   <td class="data"><input type="text" name="brewYeastNutrients" id="brewYeastNutrients" size="75" value="<?php if ($action == "edit") echo $row_log['brewYeastNutrients']; ?>" /></td>
</tr>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?>Carbonation</h4>
<div class="toggle_container">
<table>
<tr>
   <td class="dataLabel">Method:</td>
   <td class="data"><input type="radio" name="brewCarbonationMethod" value="Y" id="brewCarbonationMethod_0"  <?php if (($action == "edit") && ($row_log['brewCarbonationMethod'] == "Y")) echo "CHECKED"; ?> /> Forced CO<sub>2</sub>&nbsp;&nbsp;<input type="radio" name="brewCarbonationMethod" value="N" id="brewCarbonationMethod_1" <?php if (($action == "edit") && ($row_log['brewCarbonationMethod'] == "N")) echo "CHECKED"; ?>/> Bottle Conditioned</td>
</tr>
<tr>
   <td class="dataLabel">Volumes of CO<sub>2</sub>:</td>
   <td class="data"><input name="brewCarbonationVol" type="text" id="brewCarbonationVol" size="10" value="<?php if ($action == "edit") echo $row_log['brewCarbonationVol']; ?>"></td>
</tr>
<tr>
   <td class="dataLabel">Type/Amount:</td>
   <td class="data"><input type="text" name="brewCarbonationNotes" size="75" value="<?php if ($action == "edit") echo $row_log['brewCarbonationNotes']; ?>" /></td>
</tr>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?>Boil</h4>
<div class="toggle_container">
<table>
<tr>
   <td class="dataLabel">Hours:</td>
   <td class="data"><input name="brewBoilHours" type="text" id="brewBoilHours" size="10" value="<?php if ($action == "edit") echo $row_log['brewBoilHours']; ?>"></td>
</tr>
<tr>
   <td class="dataLabel">Minutes:</td>
   <td class="data"><input name="brewBoilMins" type="text" id="brewBoilMins" size="10" value="<?php if ($action == "edit") echo $row_log['brewBoilMins']; ?>"></td>
</tr>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?>Fermentation</h4>
<div class="toggle_container">
<table>
<tr>
   <td class="dataLabel">Primary:</td>
   <td class="data"><input type="text" name="brewPrimary" size="5" tooltipText="<?php echo $toolTip_decimal; ?>" value="<?php if ($action == "edit") echo $row_log['brewPrimary']; ?>">&nbsp;days @&nbsp;<input name="brewPrimaryTemp" type="text" id="brewPrimaryTemp" size="5" tooltipText="<?php echo $toolTip_decimal; ?>" value="<?php if ($action == "edit") echo $row_log['brewPrimaryTemp']; ?>">&nbsp;&deg;<?php echo $_SESSION['prefsTemp']; ?></td>
</tr>
<tr>  
   <td class="dataLabel">Secondary:</td>
   <td class="data"><input type="text" name="brewSecondary" size="5" tooltipText="<?php echo $toolTip_decimal; ?>" value="<?php if ($action == "edit") echo $row_log['brewSecondary']; ?>">&nbsp;days @&nbsp;<input name="brewSecondaryTemp" type="text" id="brewSecondaryTemp" size="5" tooltipText="<?php echo $toolTip_decimal; ?>" value="<?php if ($action == "edit") echo $row_log['brewSecondaryTemp']; ?>">&nbsp;&deg;<?php echo $_SESSION['prefsTemp']; ?></td>
</tr>
<tr>
   <td class="dataLabel">Other:</td>
   <td class="data"><input type="text" name="brewOther" size="5" tooltipText="<?php echo $toolTip_decimal; ?>" value="<?php if ($action == "edit") echo $row_log['brewOther']; ?>">&nbsp;days @&nbsp;<input name="brewOtherTemp" type="text" id="brewOtherTemp" size="5" tooltipText="<?php echo $toolTip_decimal; ?>" value="<?php if ($action == "edit") echo $row_log['brewOtherTemp']; ?>">&nbsp;&deg;<?php echo $_SESSION['prefsTemp']; ?></td>
</tr>
</table>
</div>
<h4 class="trigger"><?php echo $collapse_icon; ?>Finings</h4>
<div class="toggle_container">
<table>
<tr>
   <td class="dataLabel">Type/Amount:</td>
   <td class="data"><input type="text" name="brewFinings" id="brewFinings" value="<?php if ($action == "edit") echo $row_log['brewFinings']; ?>" /></td>
</tr>
</table>
</div>
<?php } ?>
<p><input type="submit" class="button" value="Submit Entry" alt="Submit Entry" <?php if ($disable_fields) echo "DISABLED"; ?> /></p>
<input type="hidden" name="brewConfirmed" value="1">
<input type="hidden" name="relocate" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
</form>
<?php } 
else echo "<div class=\"error\">The requested entry was not entered under the currently logged in user's credentials.</div>";
if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_bottom.inc.php');
?>
</div>
</div>
</div>
<?php } ?>
