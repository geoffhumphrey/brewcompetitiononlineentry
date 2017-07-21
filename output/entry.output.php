<?php 
require('../paths.php');
session_name($prefix_session);
require(CONFIG.'bootstrap.php');
require(LIB.'output.lib.php');
include (CLASSES.'tiny_but_strong/tbs_class.php');
include (DB.'output_entry.db.php');

$entry_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "long", "date-no-gmt");
$contest_name = $contest_info['contestName'];

// Check access restrictions
$restricted = FALSE;
if (($_SESSION['user_id'] != $brewing_info['brewBrewerID']) && ($_SESSION['userLevel'] > 1)) $restricted = TRUE;

if ($restricted) { 
  	echo "<html><head><title>Error</title></head><body>";
  	echo "<p>You do not have sufficient access privileges to view this page.</p>";
  	echo "</body>";
  	exit();
}

if ((!pay_to_print($_SESSION['prefsPayToPrint'],$brewing_info['brewPaid'])) && ($go != "recipe")) {
	echo "<html><head><title>Error</title></head><body>";
  	echo "<p>You must pay for your entry to print its entry form (if applicable) and any bottle labels.</p>";
  	echo "</body>";
  	exit();
}

if ($_SESSION['prefsStyleSet'] == "BJCP2008") $category_end = 23; else $category_end = 34;
if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {
	$brewing_info['brewCategory'] = "";
	$brewing_info['brewSubCategory'] = "";
}
$brewing_id = sprintf("%04s",$brewing_info['id']);
$brewer_info['brewerFirstName'] = strtr($brewer_info['brewerFirstName'],$html_remove);
$brewing_info['brewName'] = strtr($brewing_info['brewName'],$html_remove);
$style_entry = $brewing_info['brewCategory']."-".$brewing_info['brewSubCategory'];

$brewing_info['brewInfo'] = str_replace("^","; ",$brewing_info['brewInfo']);
$brewing_info['brewInfo'] = strtr($brewing_info['brewInfo'],$html_remove); 
$brewer_info['brewerFirstName'] = strtr($brewer_info['brewerFirstName'],$html_remove);
$brewer_info['brewerLastName'] = strtr($brewer_info['brewerLastName'],$html_remove);
$brewer_info['brewerAddress'] = strtr($brewer_info['brewerAddress'],$html_remove);
$brewer_info['brewerCity'] = strtr($brewer_info['brewerCity'],$html_remove);
$brewer_info['brewerState'] = strtr($brewer_info['brewerState'],$html_remove);
$brewer_info['brewerClubs'] = strtr($brewer_info['brewerClubs'],$html_remove);
$brewer_info['brewerEmail'] = strtr($brewer_info['brewerEmail'],$html_remove);
$organizer = $row_brewer_organizer['brewerFirstName']." ".$row_brewer_organizer['brewerLastName'];

if (in_array($_SESSION['prefsEntryForm'],$barcode_qrcode_array)) {
	
	// Generate Barcode
	$barcode = $brewing_id;
	if ((NHC) && ($prefix == "final_")) $barcode = sprintf("%06s",$barcode);
	$barcode_link = "http://www.brewcompetition.com/includes/barcode/html/image.php?filetype=PNG&dpi=300&scale=1&rotation=0&font_family=Arial.ttf&font_size=10&text=".$barcode."&thickness=50&checksum=&code=BCGcode39";

	// Generate QR Code
	$qrcode_url = $base_url."qr.php?id=".$brewing_info['id'];
	$qrcode_url = urlencode($qrcode_url);
	$qrcode_link = "http://qrickit.com/api/qr?d=".$qrcode_url."&qrsize=90&t=p&e=m&addtext=".$brewing_id;
	
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
  case "Medium Dry":
    $brewing_info['sweetness'] = 'Medium Dry';
    break;
  case "Medium":
    $brewing_info['sweetness'] = 'Medium';
    break;
  case "Medium Sweet":
    $brewing_info['sweetness'] = 'Medium Sweet';
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

// Paid or not
if ($brewing_info['brewPaid'] == 1) $brewing_paid = "*** PAID ***";
else $brewing_paid = "";

// Style name
if ($brewing_info['brewCategory'] < $category_end) { 
  	$brewing_info['styleName'] = $brewing_info['brewStyle'];
 	$brewing_info['styleCat'] = style_convert($brewing_info['brewCategory'],1);
}

else $brewing_info['styleName'] = $brewing_info['brewStyle']; 

if (!in_array($_SESSION['prefsEntryForm'],$no_entry_form_array)) {
	
	// Get some values that are easier to work with in the templates
	$brewing_info['carbonation'] = 'unknown';
	if ($brewing_info['brewCarbonationMethod'] == "Y") {
	  $brewing_info['sparkling'] = 'forced';
	}
	if ($brewing_info['brewCarbonationMethod'] == "N") {
	  $brewing_info['sparkling'] = 'bottleConditioned';
	}
	
	// Some metric/US conversions
	$brewing_info['brewSize']['us']=$brewing_info['brewYield'];
	$brewing_info['brewSize']['metric']=round($brewing_info['brewYield']*3.78541,2);
	$brewing_info['styleCat'] = "";
	
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
	  if (isset($brewing_info['brewExtract'.$i])) {
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
	  if (isset($brewing_info['brewAddition'.$i])) {
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
	  if (isset($brewing_info['brewHops'.$i])) {
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
	  if (isset($brewing_info['brewMashStep'.$i.'Temp'])) {
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
} // end if (in_array($_SESSION['prefsEntryForm'],$no_entry_form_array))

$TBS = new clsTinyButStrong;
$TBS->SetOption('noerr',TRUE);

if ($go == "default") {
	
	if ($_SESSION['prefsEntryForm'] == "0") { 
		$TBS->LoadTemplate(TEMPLATES.'anon-entry.html');
	}
	
	if ($_SESSION['prefsEntryForm'] == "1") { 
		$TBS->LoadTemplate(TEMPLATES.'bcoem-entry.html');
	}
	
	if ($_SESSION['prefsEntryForm'] == "2") { 
		$TBS->LoadTemplate(TEMPLATES.'bcoem-entry-barcode.html');
	}
	
	if ($_SESSION['prefsEntryForm'] == "B") { 
		$TBS->LoadTemplate(TEMPLATES.'bjcp-entry.html');
	}
	
	if ($_SESSION['prefsEntryForm'] == "E") { 
		$TBS->LoadTemplate(TEMPLATES.'bjcp-entry-label-only.html');
	}
	
	if ($_SESSION['prefsEntryForm'] == "M") { 
		$TBS->LoadTemplate(TEMPLATES.'simple-metric-entry.html');
	}
	
	if ($_SESSION['prefsEntryForm'] == "3") { 
		$TBS->LoadTemplate(TEMPLATES.'simple-metric-entry-barcode.html');
	}
	
	if ($_SESSION['prefsEntryForm'] == "U") { 
		$TBS->LoadTemplate(TEMPLATES.'simple-us-entry.html');
	}
	
	if ($_SESSION['prefsEntryForm'] == "4") { 
		$TBS->LoadTemplate(TEMPLATES.'simple-us-entry-barcode.html');
	}
	
	if ($_SESSION['prefsEntryForm'] == "N") { 
		$TBS->LoadTemplate(TEMPLATES.'barcode-entry.html');
		$TBS->MergeBlock('dropOffLocation',$brewing,'SELECT * FROM '.$prefix.'drop_off ORDER BY dropLocationName ASC');
	}
	
	if ($_SESSION['prefsEntryForm'] == "C") { 
		$TBS->LoadTemplate(TEMPLATES.'barcode-entry-label-only.html');
		$TBS->MergeBlock('dropOffLocation',$brewing,'SELECT * FROM '.$prefix.'drop_off ORDER BY dropLocationName ASC');
	}
}

if ($go == "recipe") {
	$TBS->LoadTemplate(TEMPLATES.'recipe.html');
}

if (isset($brewing_info['grains'])) $TBS->MergeBlock('grains',$brewing_info['grains']);
if (isset($brewing_info['extracts'])) $TBS->MergeBlock('extracts',$brewing_info['extracts']);
if (isset($brewing_info['adjuncts'])) $TBS->MergeBlock('adjuncts',$brewing_info['adjuncts']);
if (isset($brewing_info['hops'])) $TBS->MergeBlock('hops',$brewing_info['hops']);
if (isset($brewing_info['mashSteps'])) $TBS->MergeBlock('mashSteps',$brewing_info['mashSteps']);

$TBS->NoErr;
$TBS->Show();
?>
