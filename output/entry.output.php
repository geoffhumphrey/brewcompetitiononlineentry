<?php
$entry_forms = array("0","1","2","E","C");

// If using non-TBS bottle labels, redirect
if (!in_array($_SESSION['prefsEntryForm'],$entry_forms)) {
	header(sprintf("Location: %s?section=entry-form-multi&id=%s&bid=%s", $base_url."includes/output.inc.php", $id, $bid));
}

// Otherwise run normally.
$bottleNum = $_SESSION['jPrefsBottleNum'];
$bottle_labels_001 = strtoupper($bottle_labels_001);

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

if ((!pay_to_print($_SESSION['prefsPayToPrint'],$brewing_info['brewPaid'])) && ($go != "recipe") && ($filter != "admin")) {
	echo "<html><head><title>Error</title></head><body>";
	echo "<p>You must pay for your entry to print its entry form (if applicable) and any bottle labels.</p>";
	echo "</body>";
	exit();
}

$category_end = $_SESSION['style_set_category_end'];
$brewing_id = sprintf("%06s",$brewing_info['id']);
$brewer_info['brewerFirstName'] = html_entity_decode($brewer_info['brewerFirstName']);
$brewing_info['brewName'] = html_entity_decode($brewing_info['brewName']);
$style_entry = $brewing_info['brewCategory']."-".$brewing_info['brewSubCategory'];

$brewing_info['brewInfo'] = str_replace("^"," | ",$brewing_info['brewInfo']);
$brewing_info['brewInfo'] = html_entity_decode($brewing_info['brewInfo']);
if ($_SESSION['prefsProEdition'] == 1) {
	$label_name = $label_contact;
	$label_phone = $label_contact." ".$label_phone;
	$label_email = $label_contact." ".$label_email;
}

$brewer_info['brewerFirstName'] = html_entity_decode($brewer_info['brewerFirstName']);
$brewer_info['brewerLastName'] = html_entity_decode($brewer_info['brewerLastName']);

$brewer_info['brewerAddress'] = html_entity_decode($brewer_info['brewerAddress']);
$brewer_info['brewerCity'] = html_entity_decode($brewer_info['brewerCity']);
$brewer_info['brewerState'] = html_entity_decode($brewer_info['brewerState']);
$brewer_info['brewerClubs'] = html_entity_decode($brewer_info['brewerClubs']);
$brewer_info['brewerEmail'] = html_entity_decode($brewer_info['brewerEmail']);

if ($brewer_info['brewerCountry'] == "United States") {
	$brewer_info['brewerPhone1'] = format_phone_us($brewer_info['brewerPhone1']);
	$brewer_info['brewerPhone2'] = format_phone_us($brewer_info['brewerPhone2']);
}

if ($row_brewer_organizer) $organizer = $row_brewer_organizer['brewerFirstName']." ".$row_brewer_organizer['brewerLastName'];
else $organizer = "";

if (in_array($_SESSION['prefsEntryForm'],$barcode_qrcode_array)) {

	// Generate Barcode
	$barcode_link = "https://admin.brewingcompetitions.com/includes/barcode/html/image.php?filetype=PNG&dpi=300&scale=1&rotation=0&font_family=Arial.ttf&font_size=10&text=".$brewing_id."&thickness=50&code=BCGcode39";

	// Generate QR Code
	require_once (CLASSES.'qr_code/qrClass.php');
	$qr = new qRClas();

	$qrcode_url = $base_url."qr.php?id=".$brewing_info['id'];
	$qrcode_url = urlencode($qrcode_url);

	$qr->qRCreate($qrcode_url,"90x90","UTF-8");
	$qrcode_link = $qr->url;

}

$brewing_info['sparkling'] = $brewing_info['brewMead1'];
$brewing_info['sweetness'] = $brewing_info['brewMead2'];
$brewing_info['meadType'] = $brewing_info['brewMead3'];

// Paid or not
if ($brewing_info['brewPaid'] == 1) $brewing_paid = sprintf("*** %s ***",strtoupper($label_paid));
else $brewing_paid = "";

// Style name
if ($brewing_info['brewCategory'] < $category_end) {
  $brewing_info['styleName'] = $brewing_info['brewStyle'];
 	$brewing_info['styleCat'] = style_convert($brewing_info['brewCategory'],1,$base_url);
}

else $brewing_info['styleName'] = $brewing_info['brewStyle'];

if (($_SESSION['prefsStyleSet'] == "BJCP2021") && ($brewing_info['brewCategorySort'] == "02") && ($brewing_info['brewSubCategory'] == "A")) $label_required_info = $label_regional_variation;

$TBS = new clsTinyButStrong;
$TBS->SetOption('noerr',TRUE);

if ($go == "default") {

	if ($_SESSION['prefsEntryForm'] == "0") {
		$TBS->LoadTemplate(TEMPLATES.'anon-entry.html');
	}

	elseif ($_SESSION['prefsEntryForm'] == "1") {
		$TBS->LoadTemplate(TEMPLATES.'bcoem-entry.html');
	}

	elseif ($_SESSION['prefsEntryForm'] == "2") {
		$TBS->LoadTemplate(TEMPLATES.'bcoem-entry-barcode.html');
	}

	elseif ($_SESSION['prefsEntryForm'] == "E") {
		$TBS->LoadTemplate(TEMPLATES.'bjcp-entry-label-only.html');
	}

	elseif ($_SESSION['prefsEntryForm'] == "C") {
		$TBS->LoadTemplate(TEMPLATES.'barcode-entry-label-only.html');
		$TBS->MergeBlock('dropOffLocation',$brewing,'SELECT * FROM '.$prefix.'drop_off ORDER BY dropLocationName ASC');
	}

}

$TBS->NoErr;
$TBS->Show();

?>