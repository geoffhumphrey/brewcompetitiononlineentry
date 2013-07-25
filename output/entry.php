<?php 
session_start(); 
require('../paths.php');
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
include(INCLUDES.'version.inc.php');
include(INCLUDES.'headers.inc.php');
include(INCLUDES.'scrubber.inc.php');
include(INCLUDES.'constants.inc.php');
include(CLASSES.'tiny_but_strong/tbs_class_php5.php');
function pay_to_print($prefs_pay,$entry_paid) { 
	if (($prefs_pay == "Y") && ($entry_paid == "1")) return TRUE;
	elseif (($prefs_pay == "Y") && ($entry_paid == "0")) return FALSE;
	elseif ($prefs_pay == "N") return TRUE;
}
$entry_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "long", "date-no-gmt");

mysql_select_db($database, $brewing);
$query_contest_info = "SELECT * FROM $contest_info_db_table WHERE id=1";
$row_contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
$contest_info = mysql_fetch_assoc($row_contest_info);

if ((NHC) && ($prefix == "final_")) $contest_name = date('Y')." NHC Final Round"; else $contest_name = $contest_info['contestName'];

$query_brewing = sprintf("SELECT * FROM $brewing_db_table WHERE id = '%s'", $id);
$log = mysql_query($query_brewing, $brewing) or die(mysql_error());
$brewing_info = mysql_fetch_assoc($log);

$query_brewer_user = sprintf("SELECT * FROM $users_db_table WHERE id = '%s'", $bid);
$user = mysql_query($query_brewer_user, $brewing) or die(mysql_error());
$row_brewer_user_info = mysql_fetch_assoc($user);

$query_brewer_organizer = "SELECT a.brewerFirstName,a.brewerLastName FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid AND staff_organizer='1'";
$brewer_organizer = mysql_query($query_brewer_organizer, $brewing) or die(mysql_error());
$row_brewer_organizer = mysql_fetch_assoc($brewer_organizer); 

$query_logged_in = sprintf("SELECT * FROM $users_db_table WHERE user_name = '%s'", $_SESSION['loginUsername']);
$logged_in_user = mysql_query($query_logged_in, $brewing) or die(mysql_error());
$row_logged_in_user = mysql_fetch_assoc($logged_in_user);

$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'", $bid);
$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
$brewer_info = mysql_fetch_assoc($brewer);

/*
$query_prefs = "SELECT * FROM $preferences_db_table WHERE id=1";
$prefs = mysql_query($query_prefs, $brewing) or die(mysql_error());
$row_prefs = mysql_fetch_assoc($prefs);
*/

// Check access restrictions
if (($brewer_info['brewerEmail'] != $_SESSION['loginUsername']) && ($row_logged_in_user['userLevel'] > 1)) { 
  	echo "<html><head><title>Error</title></head><body>";
  	echo "<p>You do not have sufficient access priveliges to view this page.</p>";
  	echo "</body>";
  	exit();
}
if ((!pay_to_print($_SESSION['prefsPayToPrint'],$brewing_info['brewPaid'])) && ($go != "recipe")) {
	echo "<html><head><title>Error</title></head><body>";
  	echo "<p>You must pay for your entry to print its entry form and bottle labels.</p>";
  	echo "</body>";
  	exit();
}
if ($prefix == "final_") $brewing_info['id'] = sprintf("%06s",$brewing_info['id']);
else $brewing_info['id'] = sprintf("%04s",$brewing_info['id']);
$brewer_info['brewerFirstName'] = strtr($brewer_info['brewerFirstName'],$html_remove);
$brewing_info['brewName'] = strtr($brewing_info['brewName'],$html_remove);
$style_entry = $brewing_info['brewCategory']."-".$brewing_info['brewSubCategory'];
$special_ingredients_required = array("6-D","16-E","17-F","20-A","21-A","21-B","22-B","22-C","23-A","24-A","24-B","24-C","25-A","25-B","25-C","26-A","26-B","26-C","27-B","27-C","27-E","28-A","28-B","28-C","28-D");
if (in_array($style_entry,$special_ingredients_required)) $brewing_info['brewInfo'] = strtr($brewing_info['brewInfo'],$html_remove); else $brewing_info['brewInfo'] = "";
$brewer_info['brewerFirstName'] = strtr($brewer_info['brewerFirstName'],$html_remove);
$brewer_info['brewerLastName'] = strtr($brewer_info['brewerLastName'],$html_remove);
$brewer_info['brewerAddress'] = strtr($brewer_info['brewerAddress'],$html_remove);
$brewer_info['brewerCity'] = strtr($brewer_info['brewerCity'],$html_remove);
$brewer_info['brewerState'] = strtr($brewer_info['brewerState'],$html_remove);
$brewer_info['brewerClubs'] = strtr($brewer_info['brewerClubs'],$html_remove);
$brewer_info['brewerEmail'] = strtr($brewer_info['brewerEmail'],$html_remove);
$organizer = $row_brewer_organizer['brewerFirstName']." ".$row_brewer_organizer['brewerLastName'];
// Get some values that are easier to work with in the templates
$brewing_info['carbonation'] = 'unknown';
if ($brewing_info['brewCarbonationMethod'] == "Y") {
  $brewing_info['carbonation'] = 'forced';
}
if ($brewing_info['brewCarbonationMethod'] == "N") {
  $brewing_info['carbonation'] = 'bottleConditioned';
}
// Various mead and cider info
switch ($brewing_info['brewMead1']) {
  case "Still":
    $brewing_info['sparkling'] = 'Still';
    break;
  case "Petillant":
    $brewing_info['sparkling'] = 'Petillant';
    break;
  case "Sparkling":
    $brewing_info['sparkling'] = 'Sparkling';
    break;
  default:
    $brewing_info['sparkling'] = '';
}
switch ($brewing_info['brewMead2']) {
  case "Dry":
    $brewing_info['sweetness'] = 'Dry';
    break;
  case "Semi-Sweet":
    $brewing_info['sweetness'] = 'Semi-sweet';
    break;
  case "Sweet":
    $brewing_info['sweetness'] = 'Sweet';
    break;
  default:
    $brewing_info['sweetness'] = '';
}
switch ($brewing_info['brewMead3']) {
  case "Hydromel":
    $brewing_info['meadType']='Hydromel';
    break;
  case "Standard":
    $brewing_info['meadType']='Standard';
    break;
  case "Sack":
    $brewing_info['meadType']='Sack';
    break;
  default:
    $brewing_info['meadType']='';
    break;
}
// Style name
if ($brewing_info['brewCategory'] < 29) { 
  	$brewing_info['styleName'] = $brewing_info['brewStyle'];
 	$brewing_info['styleCat'] = style_convert($brewing_info['brewCategory'],1);
}
else
  $brewing_info['styleName'] = $_SESSION['contestName']." Style: ".$brewing_info['brewStyle']; 
// Some metric/US conversions
$brewing_info['brewSize']['us']=$brewing_info['brewYield'];
$brewing_info['brewSize']['metric']=round($brewing_info['brewYield']*3.78541,2);
if ($_SESSION['prefsEntryForm'] == "N") { 
	
	if (strstr($prefix,"region")) {  // Only for NHC - comment out in general release
		include (MODS.'nhc_region.php');
		$region_city = nhc_region($prefix,1);
		$region = nhc_region($prefix,3);
	}
	
	// Barcode for nhc-entry.html template
	$barcode = $brewing_info['id'];
	
	if ((NHC) && ($prefix == "final_")) $barcode = sprintf("%06s",$barcode);
	
	// Using code from http://www.barcodephp.com
	//$barcode_link = "http://example.barcodephp.com/html/image.php?filetype=PNG&dpi=300&scale=1&rotation=0&font_family=Arial.ttf&font_size=10&text=".$barcode."&thickness=50&checksum=&code=BCGcode39";
	$barcode_link = "http://www.brewcompetition.com/includes/barcode/html/image.php?filetype=PNG&dpi=300&scale=1&rotation=0&font_family=Arial.ttf&font_size=10&text=".$barcode."&thickness=50&checksum=&code=BCGcode39";
}
//if (($_SESSION['prefsEntryForm'] != "N") || ($go == "recipe")) { 
	// Convert fermentation temperature
	$brewPrimaryTemp=$brewing_info['brewPrimaryTemp'];
	$brewing_info['brewPrimaryTemp']=array();
	if ($_SESSION['prefsTemp'] == "Celsius") {
	  $brewing_info['brewPrimaryTemp']['c'] = $brewPrimaryTemp;
	  $brewing_info['brewPrimaryTemp']['f']  = 
		  round((9/5)*$brewPrimaryTemp+32, 0);
	} else {
	  $brewing_info['brewPrimaryTemp']['f'] = $brewPrimaryTemp;
	  $brewing_info['brewPrimaryTemp']['c']  = 
		  round((5/9)*($brewPrimaryTemp-32), 0);
	}
	
	$brewSecondaryTemp=$brewing_info['brewSecondaryTemp'];
	$brewing_info['brewSecondaryTemp']=array();
	if ($_SESSION['prefsTemp'] == "Celsius") {
	  $brewing_info['brewSecondaryTemp']['c'] = $brewSecondaryTemp;
	  $brewing_info['brewSecondaryTemp']['f']  = 
		  round((9/5)*$brewSecondaryTemp+32, 0);
	} else {
	  $brewing_info['brewSecondaryTemp']['f'] = $brewSecondaryTemp;
	  $brewing_info['brewSecondaryTemp']['c']  = 
		  round((5/9)*($brewSecondaryTemp-32), 0);
	}
// Arrays for ingredients and mashing
//
	$totalFermentables=0;
	$totalHops=0;
	$totalMash=0;
	// Grains
	$brewing_info['grains']=array();
	for ($i=1; $i <= 20; $i++) {
	  if ($brewing_info['brewGrain'.$i] != "") {
		$brewing_info['grains'][$i]['name']=strtr($brewing_info['brewGrain'.$i],$html_remove);;
	
		// Metric/US conversion
		if ($_SESSION['prefsWeight2'] == "kilograms") {
		  $brewing_info['grains'][$i]['weight']['kg'] = $brewing_info['brewGrain'.$i.'Weight'];
		  $brewing_info['grains'][$i]['weight']['lb'] = 
			   round($brewing_info['brewGrain'.$i.'Weight']*2.204,2);
		 } else {
		   $brewing_info['grains'][$i]['weight']['lb'] = $brewing_info['brewGrain'.$i.'Weight'];
		   $brewing_info['grains'][$i]['weight']['kg'] = 
			   round($brewing_info['brewGrain'.$i.'Weight']/2.204,2);
		 }
		 
		 $totalFermentables+=$brewing_info['grains'][$i]['weight']['kg'];
		 $brewing_info['grains'][$i]['use']=$brewing_info['brewGrain'.$i.'Use'];
	  }
	}
	
	// Extracts
	$brewing_info['extracts']=array();
	for ($i=1; $i <= 10; $i++) {
	  if ($brewing_info['brewExtract'.$i] != "") {
		$brewing_info['extracts'][$i]['name']=strtr($brewing_info['brewExtract'.$i],$html_remove);;
	
		// Metric/US conversion
		if ($_SESSION['prefsWeight2'] == "kilograms") {
		  $brewing_info['extracts'][$i]['weight']['kg'] = $brewing_info['brewExtract'.$i.'Weight'];
		  $brewing_info['extracts'][$i]['weight']['lb'] = 
			   round($brewing_info['brewExtract'.$i.'Weight']*2.204,2);
		 } else {
		   $brewing_info['extracts'][$i]['weight']['lb'] = $brewing_info['brewExtract'.$i.'Weight'];
		   $brewing_info['extracts'][$i]['weight']['kg'] = 
			   round($brewing_info['brewExtract'.$i.'Weight']/2.204,2);
		 }
		 
		 $totalFermentables+=$brewing_info['extracts'][$i]['weight']['kg'];
		 $brewing_info['extracts'][$i]['use']=$brewing_info['brewExtract'.$i.'Use'];
	  }
	}
	
	// Adjuncts
	$brewing_info['adjuncts']=array();
	for ($i=1; $i <= 20; $i++) {
	  if ($brewing_info['brewAddition'.$i] != "") {
		$brewing_info['adjuncts'][$i]['name']=strtr($brewing_info['brewAddition'.$i],$html_remove);;
	
		// Metric/US conversion
		if ($_SESSION['prefsWeight2'] == "kilograms") {
		  $brewing_info['adjuncts'][$i]['weight']['kg'] = $brewing_info['brewAddition'.$i.'Amt'];
		  $brewing_info['adjuncts'][$i]['weight']['lb'] = 
			   round($brewing_info['brewAddition'.$i.'Amt']*2.204,2);
		 } else {
		   $brewing_info['adjuncts'][$i]['weight']['lb'] = $brewing_info['brewAddition'.$i.'Amt'];
		   $brewing_info['adjuncts'][$i]['weight']['kg'] = 
			   round($brewing_info['brewAddition'.$i.'Amt']/2.204,2);
		 }
		 
		 $totalFermentables+=$brewing_info['adjuncts'][$i]['weight']['kg'];
		 $brewing_info['adjuncts'][$i]['use']=$brewing_info['brewAddition'.$i.'Use'];
	  }
	}
	
	/* Calculate percentages
	if ($totalFermentables != 0) {
	  reset ($brewing_info['grains']);
	  foreach ($brewing_info['grains'] as &$fermentable)
		$fermentable['weight']['percent'] = 
		   round(($fermentable['weight']['kg']/$totalFermentables)*100,2);
	
	  reset ($brewing_info['extracts']);
	  foreach ($brewing_info['extracts'] as &$fermentable)
		$fermentable['weight']['percent'] = 
		   round(($fermentable['weight']['kg']/$totalFermentables)*100,2);
	
	  reset ($brewing_info['adjuncts']);
	  foreach ($brewing_info['adjuncts'] as &$fermentable)
		$fermentable['weight']['percent'] = 
		   round(($fermentable['weight']['kg']/$totalFermentables)*100,2);
	}
	  
	*/
	// Hops
	$brewing_info['hops']=array();
	for ($i=1; $i <= 20; $i++) {
	  if ($brewing_info['brewHops'.$i] != "") {
		$brewing_info['hops'][$i]['name'] = strtr($brewing_info['brewHops'.$i],$html_remove);
		$brewing_info['hops'][$i]['alphaAcid'] = $brewing_info['brewHops'.$i.'IBU'];
		$brewing_info['hops'][$i]['minutes'] = $brewing_info['brewHops'.$i.'Time'];
		$brewing_info['hops'][$i]['use'] = $brewing_info['brewHops'.$i.'Use'];
		$brewing_info['hops'][$i]['form'] = $brewing_info['brewHops'.$i.'Form'];
		
		// Metric/US conversion
		if ($_SESSION['prefsWeight1'] == "grams") {
		  $brewing_info['hops'][$i]['weight']['g'] = $brewing_info['brewHops'.$i.'Weight'];
		  $brewing_info['hops'][$i]['weight']['oz'] = round($brewing_info['brewHops'.$i.'Weight']/28.3495,2);
			   
		  
		 } else {
		   $brewing_info['hops'][$i]['weight']['oz'] = $brewing_info['brewHops'.$i.'Weight'];
		   $brewing_info['hops'][$i]['weight']['g'] = round($brewing_info['brewHops'.$i.'Weight']*28.3495,1);
		 }
		
		$totalHops+=$brewing_info['brewHops'.$i.'Weight'];	 

	  }
	}
	
	// Mashing
	$brewing_info['mashSteps']=array();
	for ($i=1; $i <= 10; $i++) {
	  if ($brewing_info['brewMashStep'.$i.'Temp'] != 0) {
		$brewing_info['mashSteps'][$i]['name']=strtr($brewing_info['brewMashStep'.$i.'Name'],$html_remove);
		$brewing_info['mashSteps'][$i]['minutes']=$brewing_info['brewMashStep'.$i.'Time'];
		$totalMash+=$brewing_info['mashSteps'][$i]['minutes'];
		// Metric/US conversion
		if ($_SESSION['prefsTemp'] == "Celsius") {
		  $brewing_info['mashSteps'][$i]['temp']['c'] = $brewing_info['brewMashStep'.$i.'Temp'];
		  $brewing_info['mashSteps'][$i]['temp']['f']  = 
			   round((9/5)*$brewing_info['brewMashStep'.$i.'Temp']+32, 0);
		 } else {
		  $brewing_info['mashSteps'][$i]['temp']['f'] = $brewing_info['brewMashStep'.$i.'Temp'];
		  $brewing_info['mashSteps'][$i]['temp']['c']  = 
			   round((5/9)*($brewing_info['brewMashStep'.$i.'Temp']-32), 0);
		 }
	  }
	}
// } // end if ($_SESSION['prefsEntryForm'] != "N")
$TBS = new clsTinyButStrong;

if ($go == "default") {
	if ($_SESSION['prefsEntryForm'] == "B") { 
	$TBS->LoadTemplate(TEMPLATES.'bjcp-entry.html');
	}
	if ($_SESSION['prefsEntryForm'] == "M") { 
	$TBS->LoadTemplate(TEMPLATES.'simple-metric-entry.html');
	}
	if ($_SESSION['prefsEntryForm'] == "U") { 
	$TBS->LoadTemplate(TEMPLATES.'simple-us-entry.html');
	}
	if ($_SESSION['prefsEntryForm'] == "N") { 
	/*
	$query_drop_off = "SELECT * FROM $drop_off_db_table";
	$drop_off = mysql_query($query_drop_off, $brewing) or die(mysql_error());
	$row_drop_off = mysql_fetch_assoc($drop_off);
	$drop_off['drop_off_location'] = array();
	do {
		$drop_off['drop_off_location'] = $row_drop_off['dropLocationName'].$row_drop_off['dropLocation'].$row_drop_off['dropLocationNotes'];	
	} while ($row_drop_off = mysql_fetch_assoc($drop_off));
	*/
	if ((NHC) && ($prefix == "final_")) $TBS->LoadTemplate(TEMPLATES.'nhc-entry-final-round.html');
	elseif (NHC) $TBS->LoadTemplate(TEMPLATES.'nhc-entry.html');
	else $TBS->LoadTemplate(TEMPLATES.'barcode-entry.html');
	$TBS->MergeBlock('dropOffLocation',$brewing,'SELECT * FROM '.$prefix.'drop_off ORDER BY dropLocationName ASC');
	}
}

if ($go == "recipe") {
	$TBS->LoadTemplate(TEMPLATES.'recipe.html');
}

//if ((($go == "default") && ($_SESSION['prefsEntryForm'] != "N")) || ($go == "recipe")) { 
	$TBS->MergeBlock('grains',$brewing_info['grains']);
	$TBS->MergeBlock('extracts',$brewing_info['extracts']);
	$TBS->MergeBlock('adjuncts',$brewing_info['adjuncts']);
	$TBS->MergeBlock('hops',$brewing_info['hops']);
	$TBS->MergeBlock('mashSteps',$brewing_info['mashSteps']);
//}
$TBS->Show();
?>
