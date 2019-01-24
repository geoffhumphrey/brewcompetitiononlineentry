<?php
/**
 * Module:      common.inc.php
 * Description: This module houses all site-wide function definitions. If a function
 *              or variable is called from multiple modules, it is housed here.
 *
 */


// Define the current version
include (LIB.'date_time.lib.php');
include (INCLUDES.'version.inc.php');

// ------------------ VERSION CHECK ------------------
// Current version is 2.1.10.0, change version in system table if not
// If there are NO database structure or data updates for the current version,
// USE THIS FUNCTION ONLY IF THERE ARE *NOT* ANY DB TABLE OR DATA UPDATES
// OTHERWISE, DEFINE/UPDATE THE VERSION VIA THE UPDATE PROCEDURE

function version_check($version,$current_version,$current_version_date_display) {

	require(CONFIG.'config.php');

	if ($version != $current_version) {

		$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id=%s", $prefix."system", $current_version, $current_version_date_display, "1");
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	}

}

// ---------------------------------------------------

function in_string($haystack,$needle) {
	if (strpos($haystack,$needle) !== false) return TRUE;
}

function designations($judge_array,$display) {
	$return = "";
	$rank1 = explode(",",$judge_array);
	foreach ($rank1 as $rank2) {
		 if ($rank2 != $display) $return .= "<br />".$rank2."";
	}
	return $return;
}

function build_action_link($icon,$base_url,$section,$go,$action,$filter,$id,$dbTable,$alt_title,$method=0,$tooltip_text="default") {

	$return = "";
	//$return .= "<a>";

	if (($method == 1) || ($method == 2)) {
		$return .= "<span class=\"fa fa-lg ".$icon."\"></span>";
		//$return .= "<img src='".$base_url."images/".$icon.".png' border='0' alt='".$alt_title."' title='".$alt_title."'>&nbsp;";
	}

	if ($icon == "fa-trash-o") {
		$return .= "<a class=\"hide-loader\" href=\"".$base_url."includes/process.inc.php?section=".$section."&amp;go=".$go."&amp;dbTable=".$dbTable."&amp;action=".$action."&amp;id=".$id."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$tooltip_text."\" data-confirm=\"".$alt_title."\">";
	}

	else {

		if ($method == 2) { // print form link
			$return .= "<a id=\"modal_window_link\" class=\"hide-loader\" href=\"".$base_url."output/entry.output.php?";
			$return .= "id=".$id;
			$return .= "&amp;bid=".$section;
			$return .= "\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$tooltip_text."\">";
		}

		else {
			$return .= "<a href=\"".$base_url."index.php?section=".$section;
			if ($go != "default") $return .= "&amp;go=".$go;
			if ($action != "default") $return .= "&amp;action=".$action;
			if ($filter != "default") $return .= "&amp;filter=".$filter;
			if ($id != "default") $return .= "&amp;id=".$id;
			$return .= "\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$tooltip_text."\">";
		}
	}

	if (($method == 1) || ($method == 2)) {
		$return .= $tooltip_text;
	}

	else {
		//$return .= "<img src='".$base_url."images/".$icon.".png' border='0' alt='".$alt_title."' title='".$alt_title."'>";
		$return .= "<span class=\"fa fa-lg ".$icon."\"></span>";

	}

	//$return .= "</span>";
	$return .= "</a>";
	return $return;
}

function build_output_link($icon,$base_url,$filename,$section,$go,$action,$filter,$id,$dbTable,$alt_title,$modal_window) {

	$return = "";

	$return .= "<a href=\"".$base_url."output/".$filename."?section=".$section;
	if ($go != "default") $return .= "&amp;go=".$go;
	if ($action != "default") $return .= "&amp;action=".$action;
	if ($filter != "default") $return .= "&amp;filter=".$filter;
	if ($id != "default") $return .= "&amp;id=".$id;
	$return .= "\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$alt_title."\"";
	if ($modal_window) $return .= " id=\"modal_window_link\" class=\"hide-loader\"";
	$return .= ">";
	$return .= "<span class=\"fa ".$icon." text-primary\"></span>";
	$return .= "</span>";
	$return .= "</a>";
	return $return;
}

function build_form_action($base_url,$section,$go,$action,$filter,$id,$dbTable,$check_required) {

	$return = "";
	if (strpos($section, 'step') !== FALSE) $section = "setup"; else $section = $section;
	$return .= "<form class=\"form-horizontal\" method=\"post\" id=\"form1\" name=\"form1\" action=\"".$base_url."includes/process.inc.php?section=".$section."&amp;dbTable=".$dbTable;
	if ($go != "default") $return .= "&amp;go=".$go;
	if ($action != "default") $return .= "&amp;action=".$action;
	if ($filter != "default") $return .= "&amp;filter=".$filter;
	if ($id != "default") $return .= "&amp;id=".$id;
	$return .= "\"";
	if ($check_required) $return .= " data-toggle=\"validator\" role=\"form\"";
	$return .= ">";

	return $return;
}

function build_public_url($section="default",$go="default",$action="default",$id="default",$sef,$base_url) {

	include (CONFIG.'config.php');
	if (NHC) {
		$url = "index.php?section=".$section;
		if ($go != "default") $url .= "&amp;go=".$go;
		if ($action != "default") $url .= "&amp;action=".$action;
		return $url;
	}
	else {
		if ($sef == "true") {
			$url = $base_url."";
			if ($section != "default") $url .= $section."/";
			if ($go != "default") $url .= $go."/";
			if ($action != "default") $url .= $action."/";
			if ($id != "default") $url .= $id."/";
			return rtrim($url,"/");
		}
		if ($sef == "false") {
			$url = $base_url."index.php?section=".$section;
			if ($go != "default") $url .= "&amp;go=".$go;
			if ($action != "default") $url .= "&amp;action=".$action;
			if ($id != "default") $url .= "&amp;id=".$id;
			return $url;
		}
	}
}
/*
function build_admin_url ($section="default",$go="default",$action="default",$id="default",$filter="default",$view="default",$sef="true",$base_url) {
	if ($sef == "true") {
		$url = $base_url."";
		if ($section != "default") $url .= $section."/";
		if ($go != "default") $url .= $go."/";
		if ($action != "default") $url .= $action."/";
		if ($id != "default") $url .= $id."/";
		if ($filter != "default") $url .= $filter."/";
		if ($view != "default") $url .= $view."/";
		return $url;
	}
	else {
		$url = $base_url."index.php?section=".$section;
		if ($go != "default") $url .= "&amp;go=".$go;
		if ($action != "default") $url .= "&amp;action=".$action;
		if ($id != "default") $url .= "&amp;id=".$id;
		if ($filter != "default") $url .= "&amp;filter=".$filter;
		if ($view != "default") $url .= "&amp;view=".$view."/";
		return $url;
	}
}
*/

function display_array_content($arrayname,$method) {
	$a = "";
	while(list($key, $value) = each($arrayname)) {
		if (is_array($value)) {
		$a .= display_array_content($value,'');

		}
	else $a .= "$value";
	if ($method == "1") $a .= "";
	if ($method == "2") $a .= ", ";
	if ($method == "3") $a .= ",";
	}
	$b = rtrim($a, ",&nbsp;");
	return $b;
}

function addOrdinalNumberSuffix($num) {
	if (!is_numeric($num)) return $num;
	else {
		if (!in_array(($num % 100),array(11,12,13))) {
			switch ($num % 10) {
				// Handle 1st, 2nd, 3rd
				case 1:  return $num."st";
				case 2:  return $num."nd";
				case 3:  return $num."rd";
			}
		}
		return $num."th";
	}
}

function purge_entries($type, $interval) {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	if ($type == "unconfirmed") {
		$query_check = sprintf("SELECT id FROM %s WHERE brewConfirmed='0'", $prefix."brewing");
		if ($interval > 0) $query_check .= " AND brewUpdated < DATE_SUB( NOW(), INTERVAL 1 DAY)";
		$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
		$row_check = mysqli_fetch_assoc($check);

		do { $a[] = $row_check['id']; } while ($row_check = mysqli_fetch_assoc($check));

		foreach ($a as $id) {
			$deleteEntries = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $id);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$deleteEntries);
			$result = mysqli_query($connection,$deleteEntries) or die (mysqli_error($connection));
		}
	}

	if ($type == "special") {
		$query_check = sprintf("SELECT a.id, a.brewUpdated, a.brewInfo, a.brewCategorySort, a.brewSubCategory FROM %s as a, %s as b WHERE a.brewCategorySort=b.brewStyleGroup AND a.brewSubCategory=b.brewStyleNum AND b.brewStyleReqSpec=1 AND (a.brewInfo IS NULL OR a.brewInfo='') AND b.brewStyleVersion = '%s'", $prefix."brewing",$prefix."styles",$_SESSION['prefsStyleSet']);
		if ($interval > 0) $query_check .=" AND a.brewUpdated < DATE_SUB( NOW(), INTERVAL 1 DAY)";

		$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
		$row_check = mysqli_fetch_assoc($check);

		do {

			$deleteEntries = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $row_check['id']);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$deleteEntries);
			$result = mysqli_query($connection,$deleteEntries) or die (mysqli_error($connection));

		} while ($row_check = mysqli_fetch_assoc($check));
	}
}

// function to generate random number
function random_generator($digits,$method){
	srand ((double) microtime() * 10000000);

	//Array of alphabet
	if ($method == "1") $input = array ("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","t","d","y","u","b","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9");
	if ($method == "2") $input = array ("0","1","2","3","4","5","6","7","8","9");
	if ($method == "3") $input = array ("0","1","2","3","4");

	$random_generator = "";// Initialize the string to store random numbers

	for ($i=1;$i<$digits+1;$i++) { // Loop the number of times of required digits

		if(rand(1,2) == 1){ // to decide the digit should be numeric or alphabet
			// Add one random alphabet
			$rand_index = array_rand($input);
			$random_generator .=$input[$rand_index]; // One char is added
		}

		if ($method == "3") {
			// Add one numeric digit between 0 and 4
			$random_generator = rand(1,4); // one number is added
		}

		else {
			// Add one numeric digit between 0 and 9
			$random_generator .=rand(1,10); // one number is added
		} // end of if else

	} // end of for loop

	return $random_generator;
} // end of function

function relocate($referer,$page,$msg,$id,$keep_id="default") {

	include (CONFIG.'config.php');

	// Break URL into an array
	$parts = parse_url($referer);
	if (isset($parts['query'])) $referer = $parts['query'];

	// Remove $msg=X from query string
	$pattern = array("/[0-9]/", "/&msg=/");
	$referer = preg_replace($pattern, "", $referer);

	// Remove $id=X from query string
	$pattern = array("/[0-9]/", "/&id=/");
	$referer = preg_replace($pattern, "", $referer);

	if ($keep_id != "default") { // Add $id back in if specified
		$referer .= "&id=".$id;
	}

	// Remove $pg=X from query string
	$pattern = array("/[0-9]/", "/&pg=/");
	$referer = str_replace($pattern,"",$referer);

	// Add back $pg back in if present
	if ($page != "default") {
		$referer .= "&pg=".$page;
	}

	$pattern = array('\'', '"');
	$referer = str_replace($pattern,"",$referer);
	$referer = stripslashes($referer);

	// Reconstruct the URL
	$reconstruct = $base_url."index.php?".$referer;
	return $reconstruct;

}

function check_judging_numbers() {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$query_check = sprintf("SELECT COUNT(*) as count FROM %s WHERE brewJudgingNumber IS NULL", $prefix."brewing");
	$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
	$row_check = mysqli_fetch_assoc($check);

	if ($row_check['count'] == 0) return TRUE;
	else return FALSE;
}


// ---------------------------- Temperature, Weight, and Volume Conversion ----------------------------------

function temp_convert($temp,$t) { // $t = desired output, defined at function call
if ($t == "F") { // Celsius to F if source is C
	$tcon = (($temp - 32) / 1.8);
	return round ($tcon, 1);
	}

if ($t == "C") { // F to Celsius
	$tcon = (($temp - 32) * (5/9));
	return round ($tcon, 1);
	}
}

function weight_convert($weight,$w) { // $w = desired output, defined at function call
if ($w == "pounds") { // kilograms to pounds
	$wcon = ($weight * 2.2046);
	return round ($wcon, 2);
	}

if ($w == "ounces") { // grams to ounces
	$wcon = ($weight * 0.03527);
	return round ($wcon, 2);
	}

if ($w == "grams") { // ounces to grams
	$wcon = ($weight * 28.3495);
	return round ($wcon, 2);
	}

if ($w == "kilograms") { // pounds to kilograms
	$wcon = ($weight * 0.4535);
	return round ($wcon, 2);
	}
}

function volume_convert($volume,$v) {  // $v = desired output, defined at function call
if ($v == "gallons") { // liters to gallons
	$vcon = ($volume * 0.2641);
	return round ($vcon, 2);
	}

if ($v == "ounces") { // milliliters to ounces
	$vcon = ($volume * 29.573);
	return round ($vcon, 2);
	}

if ($v == "liters") { // gallons to liters
	$vcon = ($volume * 3.7854);
	return round ($vcon, 2);
	}

if ($v == "milliliters") { // fluid ounces to milliliters
	$vcon = ($volume * 29.5735) ;
	return round ($vcon, 2);
	}

}

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")  {
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

	require (INCLUDES.'scrubber.inc.php');

	switch ($theType) {
	case "text":
	  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	  break;
	case "long":
	case "int":
	  $theValue = ($theValue != "") ? intval($theValue) : "NULL";
	  break;
	case "double":
	  $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
	  break;
	case "date":
	  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	  break;
	case "defined":
	  $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
	  break;
	case "scrubbed":
	  $theValue = ($theValue != "") ? "'" . strtr($theValue, $html_string) . "'" : "NULL";
	}
	return $theValue;
}

function currency_info($input,$method) {

	if ($method == 1) {

		switch ($input) {

			case "$": $currency_code = "$^USD"; break;
			case "R$": $currency_code = "R$^BRL"; break;
			case "pound": $currency_code = "&pound;^GBP"; break;
			case "czkoruna": $currency_code = "K&#269;^CZK"; break;
			case "euro": $currency_code = "&euro;^EUR"; break;
			case "A$": $currency_code = "$^AUD"; break;
			case "C$": $currency_code = "$^CAD"; break;
			case "H$": $currency_code = "$^HKD"; break;
			case "N$": $currency_code = "$^NZD"; break;
			case "S$": $currency_code = "$^SGD"; break;
			case "T$": $currency_code = "$^TWD"; break;
			case "Ft": $currency_code = "Ft^HUF"; break;
			case "shekel": $currency_code = "&#8362;^ILS"; break;
			case "yen": $currency_code = "&yen;^JPY"; break;
			case "nkr": $currency_code = "kr^NOK"; break;
			case "kr": $currency_code = "kr^DKK"; break;
			case "RM": $currency_code = "RM^MYR"; break;
			case "M$": $currency_code = "$^MXM"; break;
			case "phpeso": $currency_code = "&#8369;^PHP"; break;
			case "pol": $currency_code = "z&#322;^PLN"; break;
			case "p.": $currency_code = "p.^RUB"; break;
			case "skr": $currency_code = "kr^SEK"; break;
			case "sfranc": $currency_code = "&#8355;^CHF"; break;
			case "baht": $currency_code = "&#3647;^THB"; break;
			case "tlira": $currency_code = "&#8356;^TRY"; break;
			case "R": $currency_code = "R^ZAR"; break;
			case "rupee": $currency_code = "&#8360;^INR"; break;

			/*
			case "&pound;": $currency_code = $input."^GBP"; break;
			case "&euro;": $currency_code = $input."^EUR"; break;
			case "&yen;": $currency_code = $input."^JPY"; break;
			case "C$": $currency_code = "$^CAD"; break;
			case "kr": $currency_code = $input."^DKK"; break;
			case "R$": $currency_code = "R$^BRL"; break;
			case "A$": $currency_code = "$^AUD"; break;
			case "N$": $currency_code = "$^NZD"; break;
			case "H$": $currency_code = "$^HKD"; break;
			case "S$": $currency_code = "$^SGD"; break;
			case "skr": $currency_code = "kr^SEK"; break;
			case "pol": $currency_code = "z&#322;^PLN"; break;
			case "nkr": $currency_code = "kr^NOK"; break;
			case "Ft": $currency_code = $input."^HUF"; break;
			case "czkoruna": $currency_code = "K&#269;^CZK"; break;
			case "shekel": $currency_code = "&#8362;^ILS"; break;
			case "M$": $currency_code = "$^MXM"; break;
			case "RM": $currency_code = $input."^MYR"; break;
			case "T$": $currency_code = "$^TWD"; break;
			case "baht": $currency_code = "&#3647;^THB"; break;
			case "tlira": $currency_code = "&#8356;^TRY"; break;
			case "p.": $currency_code = $input."^RUB"; break;
			case "phpeso": $currency_code = "&#8369;^PHP"; break;
			default: $currency_code = $input."^USD";
			*/
		}

	}

	if ($method == 2) {

	$currency_code = array(
			"$^$ Dollar - U.S.^USD",
			"R$^R$ Brazilian Real^BRL",
			"pound^&pound; British Pound^GBP",
			"czkoruna^K&#269; Czech Koruna^CZK",
			"euro^&euro; Euro^EUR",
			"A$^$ Dollar - Australian^AUD",
			"C$^$ Dollar - Canadian^CAD",
			"H$^$ Dollar - Hong Kong^HKD",
			"N$^$ Dollar - New Zealand^NZD",
			"S$^$ Dollar - Singapore^SGD",
			"T$^$ Dollar - Taiwan (New)^TWD",
			"Ft^Ft Hungarian Forint^HUF",
			"shekel^&#8362; Israeli New Shekel^ILS",
			"yen^&yen; Japanese Yen^JPY",
			"nkr^kr Krone - Norwegian^NOK",
			"kr^kr Krone - Danish^DKK",
			"RM^RM Malaysian Ringgit^MYR",
			"M$^$ Mexican Peso^MXM",
			"phpeso^&#8369; Philippine Peso^PHP",
			"pol^z&#322; Polish Zloty^PLN",
			"p.^p. Russian Ruble^RUB",
			"skr^kr Swedish Krona^SEK",
			"sfranc^&#8355; Swiss Franc^CHF",
			"baht^&#3647; Thai Baht^THB",
			"tlira^&#8356; Turkish Lira^TRY",
			" ^---------------------^-----------",
			"R^R South African Rand^ZAR",
			"rupee^&#8360; Rupee^INR"
		);

	}

	return $currency_code;

	/*

	PAYPAL accepted currencies:

	Canadian Dollar CAD
	Euro EUR
	British Pound GBP
	U.S. Dollar USD
	Japanese Yen JPY
	Australian Dollar AUD
	New Zealand Dollar NZD
	Swiss Franc CHF
	Hong Kong Dollar HKD
	Singapore Dollar SGD
	Swedish Krona SEK
	Danish Krone DKK
	Polish Zloty PLN
	Norwegian Krone NOK
	Hungarian Forint HUF
	Czech Koruna CZK
	Israeli New Shekel ILS
	Mexican Peso MXM
	Brazilian Real BRL
	Malaysian Ringgit MYR
	Philippine Peso PHP
	New Taiwan Dollar TWD
	Thai Baht THB
	Turkish Lira TRY
	Russian Ruble RUB

	World Currency Codes:

	Albania, Leke (ALL)
	America-USA, Dollars (USD)
	Afghanistan, Afghanis (AFN)
	Argentina, Pesos (ARS)
	Aruba, Guilders/Florins (AWG)
	Australia, Dollars (AUD)
	Azerbaijan, New Manats (AZN)
	Bahamas, Dollars (BSD)
	Barbados, Dollars (BBD)
	Belarus, Rubles (BYR)
	Belgium, Euro (EUR)
	Belize, Dollars (BZD)
	Bermuda, Dollars (BMD)
	Bolivia, Bolivianos (BOB)
	Bosnia and Herzegovina, Convertible Marka (BAM)
	Botswana, Pulas (BWP)
	Bulgaria, Leva (BGN)
	Brazil, Reais (BRL)
	Britain (UK), Pounds (GBP)
	Brunei Darussalam, Dollars (BND)
	Cambodia, Riels (KHR)
	Canada, Dollars (CAD)
	Cayman Islands, Dollars (KYD)
	Chile, Pesos (CLP)
	China, Yuan Renminbi (CNY)
	Colombia, Pesos (COP)
	Costa Rica, Colón (CRC)
	Croatia, Kuna (HRK)
	Cuba, Pesos (CUP)
	Cyprus, Euro (EUR)
	Czech Republic, Koruny (CZK)
	Denmark, Kroner (DKK)
	Dominican Republic, Pesos (DOP)
	East Caribbean, Dollars (XCD)
	Egypt, Pounds (EGP)
	El Salvador, Colones (SVC)
	England (United Kingdom), Pounds (GBP)
	Estonia, Krooni (EEK)
	Euro (EUR)
	Falkland Islands, Pounds (FKP)
	Fiji, Dollars (FJD)
	France, Euro (EUR)
	Ghana, Cedis (GHC)
	Gibraltar, Pounds (GIP)
	Greece, Euro (EUR)
	Guatemala, Quetzales (GTQ)
	Guernsey, Pounds (GGP)
	Guyana, Dollars (GYD)
	Holland (Netherlands), Euro (EUR)
	Honduras, Lempiras (HNL)
	Hong Kong, Dollars (HKD)
	Hungary, Forint (HUF)
	Iceland, Kronur (ISK)
	India, Rupees (INR)
	Indonesia, Rupiahs (IDR)
	Iran, Rials (IRR)
	Ireland, Euro (EUR)
	Isle of Man, Pounds (IMP)
	Israel, New Shekels (ILS)
	Italy, Euro (EUR)
	Jamaica, Dollars (JMD)
	Japan, Yen (JPY)
	Jersey, Pounds (JEP)
	Kazakhstan, Tenge (KZT)
	Korea (North), Won (KPW)
	Korea (South), Won (KRW)
	Kyrgyzstan, Soms (KGS)
	Laos, Kips (LAK)
	Latvia, Lati (LVL)
	Lebanon, Pounds (LBP)
	Liberia, Dollars (LRD)
	Liechtenstein, Switzerland Francs (CHF)
	Lithuania, Litai (LTL)
	Luxembourg, Euro (EUR)
	Macedonia, Denars (MKD)
	Malaysia, Ringgits (MYR)
	Malta, Euro (EUR)
	Mauritius, Rupees (MUR)
	Mexico, Pesos (MXM)
	Mongolia, Tugriks (MNT)
	Mozambique, Meticais (MZN)
	Namibia, Dollars (NAD)
	Nepal, Rupees (NPR)
	Netherlands Antilles, Guilders /Florins (ANG)
	Netherlands, Euro (EUR)
	New Zealand, Dollars (NZD)
	Nicaragua, Cordobas (NIO)
	Nigeria, Nairas (NGN)
	North Korea, Won (KPW)
	Norway, Krone (NOK)
	Oman, Rials (OMR)
	Pakistan, Rupees (PKR)
	Panama, Balboa (PAB)
	Paraguay, Guarani (PYG)
	Peru, Nuevos Soles (PEN)
	Philippines, Pesos (PHP)
	Poland, Zlotych (PLN)
	Qatar, Rials (QAR)
	Romania, New Lei (RON)
	Russia, Rubles (RUB)
	Saint Helena, Pounds (SHP)
	Saudi Arabia, Riyals (SAR)
	Serbia, Dinars (RSD)
	Seychelles, Rupees (SCR)
	Singapore, Dollars (SGD)
	Slovenia, Euro (EUR)
	Solomon Islands, Dollars (SBD)
	Somalia, Shillings (SOS)
	South Africa, Rand (ZAR)
	South Korea, Won (KRW)
	Spain, Euro (EUR)
	Sri Lanka, Rupees (LKR)
	Sweden, Kronor (SEK)
	Switzerland, Francs (CHF)
	Suriname, Dollars (SRD)
	Syria, Pounds (SYP)
	Taiwan, New Dollars (TWD)
	Thailand, Baht (THB)
	Trinidad and Tobago, Dollars (TTD)
	Turkey, Lira (TRY)
	Turkey, Liras (TRL)
	Tuvalu, Dollars (TVD)
	Ukraine, Hryvnia (UAH)
	United Kingdom, Pounds (GBP)
	United States of America, Dollars (USD)
	Uruguay, Pesos (UYU)
	Uzbekistan, Sums (UZS)
	Vatican City, Euro (EUR)
	Venezuela, Bolivares Fuertes (VEF)
	Vietnam, Dong (VND)
	Yemen, Rials (YER)
	Zimbabwe, Zimbabwe Dollars (ZWD)
	*/

}

function total_fees($entry_fee, $entry_fee_discount, $entry_discount, $entry_discount_number, $cap_no, $special_discount_number, $bid, $filter, $comp_id) {
	require(CONFIG.'config.php');

	// ----------------------------------------------------------------------
	if (($bid == "default") && ($filter == "default")) {

		mysqli_select_db($connection,$database);
		$query_users = sprintf("SELECT id,user_name FROM %s",$prefix."users");
		$users = mysqli_query($connection,$query_users) or die (mysqli_error($connection));
		$row_users = mysqli_fetch_assoc($users);
		$totalRows_users = mysqli_num_rows($users);

		do { $user_id_1[] = $row_users['id']; } while ($row_users = mysqli_fetch_assoc($users));
		sort($user_id_1);

		foreach ($user_id_1 as $id_1) {
			// Get each entrant's number of entries
			mysqli_select_db($connection,$database);
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s'",$prefix."brewing", $id_1);
			$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
			$row_entries = mysqli_fetch_array($entries, MYSQLI_BOTH);
			$totalRows_entries = $row_entries['count'];

			$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'",$prefix."brewer", $id_1);
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_array($brewer, MYSQLI_BOTH);

			if ($totalRows_entries > 0) {
				if (($row_brewer['brewerDiscount'] == "Y") && ($special_discount_number != "")) {
					if ($entry_discount == "Y") {
						$a = $entry_discount_number * $special_discount_number;
						if ($entry_fee_discount > $special_discount_number) $b = ($totalRows_entries - $entry_discount_number) * $special_discount_number;
						else $b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
						$c = $a + $b;
						$d = $totalRows_entries * $special_discount_number;
						if ($totalRows_entries <= $entry_discount_number) $total = $d;
						if ($totalRows_entries > $entry_discount_number) $total = $c;
					} // end if ($entry_discount == "Y")
					else $total = $totalRows_entries * $special_discount_number;
				} // end if ($row_brewer['brewerDiscount'] == "Y")
				if (($row_brewer['brewerDiscount'] != "Y") || ((($row_brewer['brewerDiscount'] == "Y")) && ($special_discount_number == ""))) {
					if ($entry_discount == "Y") {
						$a = $entry_discount_number * $entry_fee;
						$b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
						$c = $a + $b;
						$d = $totalRows_entries * $entry_fee;
						if ($totalRows_entries <= $entry_discount_number) $total = $d;
						if ($totalRows_entries > $entry_discount_number) $total = $c;
					}
					else $total = $totalRows_entries * $entry_fee;
				} // end if ($row_brewer['brewerDiscount'] != "Y")
				if ($cap_no > 0) {
					if ($total < $cap_no) $total_calc = $total;
					if ($total >= $cap_no) $total_calc = $cap_no;
					}
				else $total_calc = $total;
			} // endif ($totalRows_entries > 0)
			else $total_calc = 0;
			$total_array[] = $total_calc;
		} // end foreach
	$total_fees = array_sum($total_array);
	return $total_fees;
	} // end if (($bid == "default") && ($filter == "default"))
	// ----------------------------------------------------------------------

	// ----------------------------------------------------------------------
	if (($bid != "default") && ($filter == "default")) {
	// Get each entrant's number of entries
	mysqli_select_db($connection,$database);
		$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='1'", $prefix."brewing", $bid);
		$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
		$row_entries = mysqli_fetch_array($entries);
		$totalRows_entries = $row_entries['count'];

		$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'", $prefix."brewer", $bid);
		$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
		$row_brewer = mysqli_fetch_array($brewer);

		if ($totalRows_entries > 0) {
				if (($row_brewer['brewerDiscount'] == "Y") && ($special_discount_number != "")) {
					if ($entry_discount == "Y") {
						$a = $entry_discount_number * $special_discount_number;
						if ($entry_fee_discount > $special_discount_number) $b = ($totalRows_entries - $entry_discount_number) * $special_discount_number;
						else $b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
						$c = $a + $b;
						$d = $totalRows_entries * $special_discount_number;
						if ($totalRows_entries <= $entry_discount_number) $total = $d;
						if ($totalRows_entries > $entry_discount_number) $total = $c;
					} // end if ($entry_discount == "Y")
					else $total = $totalRows_entries * $special_discount_number;
					//echo $total."<br>";
				} // end if ($row_brewer['brewerDiscount'] == "Y")
				if (($row_brewer['brewerDiscount'] != "Y") || ((($row_brewer['brewerDiscount'] == "Y")) && ($special_discount_number == ""))) {
					if ($entry_discount == "Y") {
						$a = $entry_discount_number * $entry_fee;
						$b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
						$c = $a + $b;
						$d = $totalRows_entries * $entry_fee;
						if ($totalRows_entries <= $entry_discount_number) $total = $d;
						if ($totalRows_entries > $entry_discount_number) $total = $c;
					}
					else $total = $totalRows_entries * $entry_fee;
				} // end if ($row_brewer['brewerDiscount'] != "Y")
				if ($cap_no > 0) {
					if ($total < $cap_no) $total_calc = $total;
					if ($total >= $cap_no) $total_calc = $cap_no;
					}
				else $total_calc = $total;
			} // endif ($totalRows_entries > 0)

			else $total_calc = 0;
			//echo $total_calc."<br>";
			$total_array[] = $total_calc;
	$total_fees = array_sum($total_array);
	return $total_fees;
	} // end if (($bid != "default") && ($filter == "default"))
	// ----------------------------------------------------------------------

	// ----------------------------------------------------------------------
	if (($bid == "default") && ($filter != "default")) {

	mysqli_select_db($connection,$database);
		$query_users = sprintf("SELECT id,user_name FROM %s",$prefix."users");
		$users = mysqli_query($connection,$query_users) or die (mysqli_error($connection));
		$row_users = mysqli_fetch_assoc($users);
		$totalRows_users = mysqli_num_rows($users);

		do { $user_id_1[] = $row_users['id']; } while ($row_users = mysqli_fetch_assoc($users));
		sort($user_id_1);

		foreach ($user_id_1 as $id_1) {
			// Get each entrant's number of entries
			mysqli_select_db($connection,$database);
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewCategorySort='%s'",$prefix."brewing",$id_1,$filter);
			$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
			$row_entries = mysqli_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];

			$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'",$prefix."brewer", $id_1);
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_array($brewer);

			if ($totalRows_entries > 0) {
				if (($row_brewer['brewerDiscount'] == "Y") && ($special_discount_number != "")) {
					if ($entry_discount == "Y") {
						$a = $entry_discount_number * $special_discount_number;
						if ($entry_fee_discount > $special_discount_number) $b = ($totalRows_entries - $entry_discount_number) * $special_discount_number;
						else $b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
						$c = $a + $b;
						$d = $totalRows_entries * $special_discount_number;
						if ($totalRows_entries <= $entry_discount_number) $total = $d;
						if ($totalRows_entries > $entry_discount_number) $total = $c;
					} // end if ($entry_discount == "Y")
					else $total = $totalRows_entries * $special_discount_number;
				} // end if ($row_brewer['brewerDiscount'] == "Y")
				if (($row_brewer['brewerDiscount'] != "Y") || ((($row_brewer['brewerDiscount'] == "Y")) && ($special_discount_number == ""))) {
					if ($entry_discount == "Y") {
						$a = $entry_discount_number * $entry_fee;
						$b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
						$c = $a + $b;
						$d = $totalRows_entries * $entry_fee;
						if ($totalRows_entries <= $entry_discount_number) $total = $d;
						if ($totalRows_entries > $entry_discount_number) $total = $c;
					}
					else $total = $totalRows_entries * $entry_fee;
				} // end if ($row_brewer['brewerDiscount'] != "Y")
				if ($cap_no > 0) {
					if ($total < $cap_no) $total_calc = $total;
					if ($total >= $cap_no) $total_calc = $cap_no;
					}
				else $total_calc = $total;
			} // endif ($totalRows_entries > 0)
			else $total_calc = 0;
			$total_array[] = $total_calc;
		} // end foreach
	$total_fees = array_sum($total_array);
	return $total_fees;

	} // end if (($bid != "default") && ($filter == "default"))

}

function total_fees_paid($entry_fee, $entry_fee_discount, $entry_discount, $entry_discount_number, $cap_no, $special_discount_number, $bid, $filter, $comp_id) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	// echo "<br>entry_fee:".$entry_fee."<br>entry_fee_discount:".$entry_fee_discount."<br>entry_discount:".$entry_discount."<br>entry_discount_number:".$entry_discount_number."<br>cap_no:".$cap_no."<br>special_discount_amount:".$special_discount_number."<br>bid:".$bid."<br>filter:".$filter."<br>";
	// ----------------------------------------------------------------------
	if (($bid == "default") && ($filter == "default")) {
		$query_users = sprintf("SELECT id,user_name FROM %s", $prefix."users");
		$users = mysqli_query($connection,$query_users) or die (mysqli_error($connection));
		$row_users = mysqli_fetch_assoc($users);
		$totalRows_users = mysqli_num_rows($users);

		do { $user_id_2[] = $row_users['id']; } while ($row_users = mysqli_fetch_assoc($users));
		sort($user_id_2);

		foreach ($user_id_2 as $id_2) {
			// Get each entrant's number of entries
			mysqli_select_db($connection,$database);
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s'", $prefix."brewing", $id_2);
			$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
			$row_entries = mysqli_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];

			$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewPaid='1'",$prefix."brewing", $id_2);
			$paid = mysqli_query($connection,$query_paid) or die (mysqli_error($connection));
			$row_paid = mysqli_fetch_array($paid);
			$totalRows_paid = $row_paid['count'];

			$totalRows_not_paid = ($row_entries['count'] - $row_paid['count']);

			$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'",$prefix."brewer", $id_2);
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_array($brewer);

			if ($totalRows_entries > 0) {

				if (($row_brewer['brewerDiscount'] == "Y") && ($special_discount_number != "")) {
					if ($entry_discount == "Y") {
						// Determine if the amount paid is equal or less than the discount amount
						// If so, total paid is a simple calculation
						if ($totalRows_paid <= $entry_discount_number) $total_paid = $totalRows_paid * $special_discount_number;
						// If not...
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid < $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $special_discount_number;
							// Next, determine which discounted figure is more: the "member" discount or the "volume";
							if ($special_discount_number >= $entry_fee_discount) $discount_amount = $entry_fee_discount; else $discount_amount = $special_discount_number;
							// Determine how many paid entires are eligible for a discount
							$total_paid_discount = (($totalRows_paid - $entry_discount_number) * $discount_amount);
							$total_paid = $total_paid_regular + $total_paid_discount;
						}
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid == $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $special_discount_number;
							// Next, determine which discounted figure is more: the "member" discount or the "volume";
							if ($special_discount_number >= $entry_fee_discount) $discount_amount = $entry_fee_discount; else $discount_amount = $special_discount_number;
							// Calculate amount of discounted entries
							$total_paid_discount = (($totalRows_entries - $entry_discount_number) * $discount_amount);
							$total_paid = $total_paid_regular + $total_paid_discount;
						}
					} // end if ($entry_discount == "Y")
					else {
						$total_paid = $totalRows_paid * $special_discount_number;
					}
			} // end if ($row_brewer['brewerDiscount'] == "Y")


				if (($row_brewer['brewerDiscount'] != "Y") || ((($row_brewer['brewerDiscount'] == "Y")) && ($special_discount_number == ""))) {
				if ($entry_discount == "Y") {
						// Determine if the amount paid is equal or less than the discount amount
						// If so, total paid is a simple calculation
						if ($totalRows_paid <= $entry_discount_number) $total_paid = $totalRows_paid * $entry_fee;
						// If not...
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid < $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $entry_fee;
							// Determine how many paid entires are eligible for a discount
							$total_paid_discount = (($totalRows_paid - $entry_discount_number) * $entry_fee_discount);
							$total_paid = $total_paid_regular + $total_paid_discount;
							}
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid == $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $entry_fee;
							// Calculate amount of discounted entries
							$total_paid_discount = (($totalRows_entries - $entry_discount_number) * $entry_fee_discount);
							$total_paid = $total_paid_regular + $total_paid_discount;
							}
						} // end if ($entry_discount == "Y")
					else
						$total_paid = $totalRows_paid * $entry_fee;
						//echo $total_paid;
			} // end if ($row_brewer['brewerDiscount'] != "Y")

				if (($cap_no > 0) && ($cap_no != "")) {
					if ($total_paid < $cap_no) $total_calc_paid = $total_paid;
					if ($total_paid >= $cap_no) $total_calc_paid = $cap_no;
					}
				else $total_calc_paid = $total_paid;
			} // end if ($totalRows_entries > 0)
			else $total_calc_paid = 0;
			$total_array_paid[] = $total_calc_paid;
		} // end foreach
		$total_fees_paid = array_sum($total_array_paid);
		return $total_fees_paid;
	} // end if (($bid == "default") && ($filter == "default"))
	// ----------------------------------------------------------------------

	// ----------------------------------------------------------------------
	if (($bid != "default") && ($filter == "default")) {

	// Get the entrant's number of entries
	mysqli_select_db($connection,$database);

	$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s'", $prefix."brewing", $bid);
	$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
	$row_entries = mysqli_fetch_array($entries);
	$totalRows_entries = $row_entries['count'];

	$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewPaid='1'", $prefix."brewing", $bid);
	$paid = mysqli_query($connection,$query_paid) or die (mysqli_error($connection));
	$row_paid = mysqli_fetch_array($paid);
	$totalRows_paid = $row_paid['count'];
	$totalRows_not_paid = ($totalRows_entries - $totalRows_paid);

	$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'", $prefix."brewer", $bid);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_array($brewer);

	//echo "Discount? ".$row_brewer['brewerDiscount']."<br>";
		if ($totalRows_entries > 0) {
			if (($row_brewer['brewerDiscount'] == "Y") && ($special_discount_number != "")) {
					if ($entry_discount == "Y") {
						// Determine if the amount paid is equal or less than the discount amount
						// If so, total paid is a simple calculation
						if ($totalRows_paid <= $entry_discount_number) $total_paid = $totalRows_paid * $special_discount_number;
						// If not...
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid < $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $special_discount_number;
							// Next, determine which discounted figure is more: the "member" discount or the "volume";
							if ($special_discount_number >= $entry_fee_discount) $discount_amount = $entry_fee_discount; else $discount_amount = $special_discount_number;
							// Determine how many paid entires are eligible for a discount
							$total_paid_discount = (($totalRows_paid - $entry_discount_number) * $discount_amount);
							$total_paid = $total_paid_regular + $total_paid_discount;
						}
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid == $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $special_discount_number;
							// Next, determine which discounted figure is more: the "member" discount or the "volume";
							if ($special_discount_number >= $entry_fee_discount) $discount_amount = $entry_fee_discount; else $discount_amount = $special_discount_number;
							// Calculate amount of discounted entries
							$total_paid_discount = (($totalRows_entries - $entry_discount_number) * $discount_amount);
							$total_paid = $total_paid_regular + $total_paid_discount;
						}
					} // end if ($entry_discount == "Y")
					else {
						$total_paid = $totalRows_paid * $special_discount_number;
					}
			} // end if ($row_brewer['brewerDiscount'] == "Y")

			if (($row_brewer['brewerDiscount'] != "Y") || ((($row_brewer['brewerDiscount'] == "Y")) && ($special_discount_number == ""))) {
				if ($entry_discount == "Y") {
						// Determine if the amount paid is equal or less than the discount amount
						// If so, total paid is a simple calculation
						if ($totalRows_paid <= $entry_discount_number) $total_paid = $totalRows_paid * $entry_fee;
						// If not...
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid < $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $entry_fee;
							// Determine how many paid entires are eligible for a discount
							$total_paid_discount = (($totalRows_paid - $entry_discount_number) * $entry_fee_discount);
							$total_paid = $total_paid_regular + $total_paid_discount;
							}
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid == $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $entry_fee;
							// Calculate amount of discounted entries
							$total_paid_discount = (($totalRows_entries - $entry_discount_number) * $entry_fee_discount);
							$total_paid = $total_paid_regular + $total_paid_discount;
							}
						} // end if ($entry_discount == "Y")
					else
						$total_paid = $totalRows_paid * $entry_fee;
						//echo $total_paid;
			} // end if ($row_brewer['brewerDiscount'] != "Y")

			if ($cap_no > 0) {
				if ($total_paid < $cap_no) $total_calc_paid = $total_paid;
				if ($total_paid >= $cap_no) $total_calc_paid = $cap_no;
				}
			else $total_calc_paid = $total_paid;
		} // end if ($totalRows_entries > 0)
		else $total_calc_paid = 0;
		$total_array_paid[] = $total_calc_paid;
		//echo "Total Paid: ".$total_calc_paid."<br>";
		$total_fees_paid = array_sum($total_array_paid);
		return $total_fees_paid;

	} // end if (($bid != "default") && ($filter == "default"))
	// ----------------------------------------------------------------------

	if (($bid == "default") && ($filter != "default")) {

		$query_users = sprintf("SELECT id,user_name FROM %s", $prefix."users");
		$users = mysqli_query($connection,$query_users) or die (mysqli_error($connection));
		$row_users = mysqli_fetch_assoc($users);
		$totalRows_users = mysqli_num_rows($users);

		do { $user_id_2[] = $row_users['id']; } while ($row_users = mysqli_fetch_assoc($users));
		sort($user_id_2);

		foreach ($user_id_2 as $id_2) {
			// Get each entrant's number of entries
			mysqli_select_db($connection,$database);
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewCategorySort='%s'",$prefix."brewing", $id_2, $filter);
			$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
			$row_entries = mysqli_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];

			$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewPaid='1' AND brewCategorySort='%s'",$prefix."brewing", $id_2, $filter);
			$paid = mysqli_query($connection,$query_paid) or die (mysqli_error($connection));
			$row_paid = mysqli_fetch_array($paid);
			$totalRows_paid = $row_paid['count'];

			$totalRows_not_paid = ($row_entries['count'] - $row_paid['count']);

			$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'", $prefix."brewer", $id_2);
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_array($brewer);

			if ($totalRows_entries > 0) {
				if (($row_brewer['brewerDiscount'] == "Y") && ($special_discount_number != "")) {
					if ($entry_discount == "Y") {
						// Determine if the amount paid is equal or less than the discount amount
						// If so, total paid is a simple calculation
						if ($totalRows_paid <= $entry_discount_number) $total_paid = $totalRows_paid * $special_discount_number;
						// If not...
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid < $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $special_discount_number;
							// Next, determine which discounted figure is more: the "member" discount or the "volume";
							if ($special_discount_number >= $entry_fee_discount) $discount_amount = $entry_fee_discount; else $discount_amount = $special_discount_number;
							// Determine how many paid entires are eligible for a discount
							$total_paid_discount = (($totalRows_paid - $entry_discount_number) * $discount_amount);
							$total_paid = $total_paid_regular + $total_paid_discount;
						}
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid == $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $special_discount_number;
							// Next, determine which discounted figure is more: the "member" discount or the "volume";
							if ($special_discount_number >= $entry_fee_discount) $discount_amount = $entry_fee_discount; else $discount_amount = $special_discount_number;
							// Calculate amount of discounted entries
							$total_paid_discount = (($totalRows_entries - $entry_discount_number) * $discount_amount);
							$total_paid = $total_paid_regular + $total_paid_discount;
						}
					} // end if ($entry_discount == "Y")
					else {
						$total_paid = $totalRows_paid * $special_discount_number;
					}
			} // end if ($row_brewer['brewerDiscount'] == "Y")

			if (($row_brewer['brewerDiscount'] != "Y") || ((($row_brewer['brewerDiscount'] == "Y")) && ($special_discount_number == ""))) {
				if ($entry_discount == "Y") {
						// Determine if the amount paid is equal or less than the discount amount
						// If so, total paid is a simple calculation
						if ($totalRows_paid <= $entry_discount_number) $total_paid = $totalRows_paid * $entry_fee;
						// If not...
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid < $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $entry_fee;
							// Determine how many paid entires are eligible for a discount
							$total_paid_discount = (($totalRows_paid - $entry_discount_number) * $entry_fee_discount);
							$total_paid = $total_paid_regular + $total_paid_discount;
							}
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid == $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $entry_fee;
							// Calculate amount of discounted entries
							$total_paid_discount = (($totalRows_entries - $entry_discount_number) * $entry_fee_discount);
							$total_paid = $total_paid_regular + $total_paid_discount;
							}
						} // end if ($entry_discount == "Y")
					else
						$total_paid = $totalRows_paid * $entry_fee;
						//echo $total_paid;
			} // end if ($row_brewer['brewerDiscount'] != "Y")

			if ($cap_no > 0) {
				if ($total_paid < $cap_no) $total_calc_paid = $total_paid;
				if ($total_paid >= $cap_no) $total_calc_paid = $cap_no;
				}
			else $total_calc_paid = $total_paid;


			} // end if ($totalRows_entries > 0)
			else $total_calc_paid = 0;
			$total_array_paid[] = $total_calc_paid;
		} // end foreach
		$total_fees_paid = array_sum($total_array_paid);
		return $total_fees_paid;

	} // end if (($bid == "default") && ($filter != "default"))
	// ----------------------------------------------------------------------
}

function total_entries_brewer($bid) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$query_all = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s'", $prefix."brewing", $bid);
	$all = mysqli_query($connection,$query_all) or die (mysqli_error($connection));
	$row_all = mysqli_fetch_assoc($all);

	return $row_all['count'];
}


function total_not_paid_brewer($bid) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$query_all = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='1'", $prefix."brewing", $bid);
	$all = mysqli_query($connection,$query_all) or die (mysqli_error($connection));
	$row_all = mysqli_fetch_assoc($all);
	$totalRows_all = $row_all['count'];

	$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewPaid='1'", $prefix."brewing", $bid);
	$paid = mysqli_query($connection,$query_paid) or die (mysqli_error($connection));
	$row_paid = mysqli_fetch_assoc($paid);
	$totalRows_paid = $row_paid['count'];

	$total_not_paid = ($totalRows_all - $totalRows_paid);
	return $total_not_paid;
}

function total_paid_received($go,$id) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$query_entry_count =  sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."brewing");
	if (($go == "judging_scores") || ($go == "judging_tables")) $query_entry_count .= " WHERE brewPaid='1' AND brewReceived='1'";
	//if (($go == "entries") && ($id != "default")) $query_entry_count .= " WHERE brewCategorySort='$id'";
	if ($id == 0)  $query_entry_count .= "";
	elseif ($id > 0) $query_entry_count .= " WHERE brewBrewerID='$id' AND brewPaid='1' AND brewReceived='1'";
	$result = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
	$row = mysqli_fetch_array($result);
	return $row['count'];
}

function total_paid() {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$query_entry_count =  sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."brewing");
	$query_entry_count .= " WHERE brewPaid='1'";
	$result = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
	$row = mysqli_fetch_array($result);
	return $row['count'];
}

function total_nopay_received($go, $id, $comp_id) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$query_entry_count =  sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."brewing");
	if ($go == "entries") $query_entry_count .= " WHERE brewPaid='0' AND brewReceived='1'";
	//if (($go == "entries") && ($id != "default")) $query_entry_count .= " WHERE brewCategorySort='$id'";
	if ($id == 0)  $query_entry_count .= "";
	elseif ($id > 0) $query_entry_count .= " WHERE brewBrewerID='$id' AND brewPaid='0' AND brewReceived='1'";
	$result = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
	$row = mysqli_fetch_array($result);
	return $row['count'];
}

function style_convert($number,$type,$base_url="") {
	require(CONFIG.'config.php');
	require(LANG.'language.lang.php');
	$styles_db_table = $prefix."styles";

	mysqli_select_db($connection,$database);
	$query_style = sprintf("SELECT brewStyleNum,brewStyleGroup,brewStyle,brewStyleVersion,brewStyleReqSpec FROM %s WHERE brewStyleGroup='%s' AND (brewStyleVersion='%s' OR brewStyleOwn='custom')",$styles_db_table,$number,$_SESSION['prefsStyleSet']);
	$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
	$row_style = mysqli_fetch_assoc($style);

	$style_convert = "";

	switch ($type) {

		case "1":

		if ($_SESSION['prefsStyleSet'] == "BA") {
			$style_convert = $row_style['brewStyle']." (Custom Style)";
		}

		if ($_SESSION['prefsStyleSet'] == "BJCP2008") {
		switch ($number) {
			case "01": $style_convert = "Light Lager"; break;
			case "02": $style_convert = "Pilsner"; break;
			case "03": $style_convert = "European Amber Lager"; break;
			case "04": $style_convert = "Dark Lager"; break;
			case "05": $style_convert = "Bock"; break;
			case "06": $style_convert = "Light Hybrid Beer"; break;
			case "07": $style_convert = "Amber Hybrid Beer"; break;
			case "08": $style_convert = "English Pale Ale"; break;
			case "09": $style_convert = "Scottish and Irish Ale"; break;
			case "10": $style_convert = "American Ale"; break;
			case "11": $style_convert = "English Brown Ale"; break;
			case "12": $style_convert = "Porter"; break;
			case "13": $style_convert = "Stout"; break;
			case "14": $style_convert = "India Pale Ale (IPA)"; break;
			case "15": $style_convert = "German Wheat and Rye Beer"; break;
			case "16": $style_convert = "Belgian and French Ale"; break;
			case "17": $style_convert = "Sour Ale"; break;
			case "18": $style_convert = "Belgian Strong Ale"; break;
			case "19": $style_convert = "Strong Ale"; break;
			case "20": $style_convert = "Fruit Beer"; break;
			case "21": $style_convert = "Spice/Herb/Vegetable Beer"; break;
			case "22": $style_convert = "Smoke-Flavored and Wood-Aged Beer"; break;
			case "23": $style_convert = "Specialty Beer"; break;
			case "24": $style_convert = "Traditional Mead"; break;
			case "25": $style_convert = "Melomel (Fruit Mead)"; break;
			case "26": $style_convert = "Other Mead"; break;
			case "27": $style_convert = "Standard Cider and Perry"; break;
			case "28": $style_convert = "Specialty Cider and Perry"; break;
			default: $style_convert = $row_style['brewStyle']." (Custom Style)"; break;
			}
		}

		if ($_SESSION['prefsStyleSet'] == "BJCP2015") {
		switch ($number) {
			case "01": $style_convert = "Standard American Beer"; break;
			case "02": $style_convert = "International Lager"; break;
			case "03": $style_convert = "Czech Lager"; break;
			case "04": $style_convert = "Pale Malty European Lager"; break;
			case "05": $style_convert = "Pale Bitter European Beer"; break;
			case "06": $style_convert = "Amber Malty European Lager"; break;
			case "07": $style_convert = "Amber Bitter European Beer"; break;
			case "08": $style_convert = "Dark European Lager"; break;
			case "09": $style_convert = "Strong European Beer"; break;
			case "10": $style_convert = "German Wheat Beer"; break;
			case "11": $style_convert = "British Bitter"; break;
			case "12": $style_convert = "Pale Commonwealth Beer"; break;
			case "13": $style_convert = "Brown British Beer"; break;
			case "14": $style_convert = "Scottish Ale"; break;
			case "15": $style_convert = "Irish Beer"; break;
			case "16": $style_convert = "Dark British Beer"; break;
			case "17": $style_convert = "Strong British Ale"; break;
			case "18": $style_convert = "Pale American Ale"; break;
			case "19": $style_convert = "Amber and Brown American Beer"; break;
			case "20": $style_convert = "American Porter and Stout"; break;
			case "21": $style_convert = "IPA"; break;
			case "22": $style_convert = "Strong American Ale"; break;
			case "23": $style_convert = "European Sour Ale"; break;
			case "24": $style_convert = "Belgian Ale"; break;
			case "25": $style_convert = "Strong Belgian Ale"; break;
			case "26": $style_convert = "Trappist Ale"; break;
			case "27": $style_convert = "Historical Beer"; break;
			case "28": $style_convert = "American Wild Ale"; break;
			case "29": $style_convert = "Fruit Beer"; break;
			case "30": $style_convert = "Spiced Beer"; break;
			case "31": $style_convert = "Alternative Fermentables Beer"; break;
			case "32": $style_convert = "Smoked Beer"; break;
			case "33": $style_convert = "Wood Beer"; break;
			case "34": $style_convert = "Specialty Beer"; break;
			case "M1": $style_convert = "Traditional Mead"; break;
			case "M2": $style_convert = "Fruit Mead"; break;
			case "M3": $style_convert = "Spiced Mead"; break;
			case "M4": $style_convert = "Specialty Mead"; break;
			case "C1": $style_convert = "Standard Cider and Perry"; break;
			case "C2": $style_convert = "Specialty Cider and Perry"; break;
			case "PR": $style_convert = "Provisional Styles"; break;
			default: $style_convert = $row_style['brewStyle']." (Custom Style)"; break;
			}
		}

		break;

		case "2":
		if ($_SESSION['prefsStyleSet'] == "BJCP2008") {
			switch ($number) {
				case "01": $style_convert = "1A,1B,1C,1D,1E"; break;
				case "02": $style_convert = "2A,2B,2C"; break;
				case "03": $style_convert = "3A,3B"; break;
				case "04": $style_convert = "4A,4B,4C"; break;
				case "05": $style_convert = "5A,5B,5C,5D"; break;
				case "06": $style_convert = "6A,6B,6C,6D"; break;
				case "07": $style_convert = "7A,7B,7C"; break;
				case "08": $style_convert = "8A,8B,8C"; break;
				case "09": $style_convert = "9A,9B,9C,9D,9E"; break;
				case "10": $style_convert = "10A,10B,10C"; break;
				case "11": $style_convert = "11A,11B,11C"; break;
				case "12": $style_convert = "12A,12B,12C"; break;
				case "13": $style_convert = "13A,13B,13C,13D,13E,13F"; break;
				case "14": $style_convert = "14A,14B,14C,"; break;
				case "15": $style_convert = "15A,15B,15C,15D,"; break;
				case "16": $style_convert = "16A,16B,16C,16D,16E,"; break;
				case "17": $style_convert = "17A,17B,17C,17D,17E,17F"; break;
				case "18": $style_convert = "18A,18B,18C,18D,18E,"; break;
				case "19": $style_convert = "19A,19B,19C,"; break;
				case "20": $style_convert = "20"; break;
				case "21": $style_convert = "21A,21B"; break;
				case "22": $style_convert = "22A,22B,22C"; break;
				case "23": $style_convert = "23"; break;
				case "24": $style_convert = "24A,24B,24C"; break;
				case "25": $style_convert = "25A,25B,25C"; break;
				case "26": $style_convert = "25A,25B,26C"; break;
				case "27": $style_convert = "27A,27B,27C,27D,27E"; break;
				case "28": $style_convert = "28A,28B,28C,28D"; break;
				default: $style_convert = "Custom Style"; break;
			}
		}

		if ($_SESSION['prefsStyleSet'] == "BJCP2015") {
			switch ($number) {
				case "01": $style_convert = "1A,1B,1C,1D"; break;
				case "02": $style_convert = "2A,2B,2C"; break;
				case "03": $style_convert = "3A,3B,3C,3D"; break;
				case "04": $style_convert = "4A,4B,4C"; break;
				case "05": $style_convert = "5A,5B,5C,5D"; break;
				case "06": $style_convert = "6A,6B,6C"; break;
				case "07": $style_convert = "7A,7B,7C"; break;
				case "08": $style_convert = "8A,8B"; break;
				case "09": $style_convert = "9A,9B,9C"; break;
				case "10": $style_convert = "10A,10B,10C"; break;
				case "11": $style_convert = "11A,11B,11C"; break;
				case "12": $style_convert = "12A,12B,12C"; break;
				case "13": $style_convert = "13A,13B,13C"; break;
				case "14": $style_convert = "14A,14B"; break;
				case "15": $style_convert = "15A,15B,15C"; break;
				case "16": $style_convert = "16A,16B,16C,16D"; break;
				case "17": $style_convert = "17A,17B,17C,17D"; break;
				case "18": $style_convert = "18A,18B"; break;
				case "19": $style_convert = "19A,19B,19C,"; break;
				case "20": $style_convert = "20A,20B,20C"; break;
				case "21": $style_convert = "21A,21B"; break;
				case "22": $style_convert = "22A,22B"; break;
				case "23": $style_convert = "23A,23B,23C,23D,23E,23F"; break;
				case "24": $style_convert = "24A,24B,24C"; break;
				case "25": $style_convert = "25A,25B,25C"; break;
				case "26": $style_convert = "25A,25B,26C,26D"; break;
				case "27": $style_convert = "27A"; break;
				case "28": $style_convert = "28A,28B,28C"; break;
				case "29": $style_convert = "29A,29B,29C"; break;
				case "30": $style_convert = "30A,30B,30C"; break;
				case "31": $style_convert = "31A,31B"; break;
				case "32": $style_convert = "32A,32B"; break;
				case "33": $style_convert = "33A,33B"; break;
				case "34": $style_convert = "34A,34B,34C"; break;
				case "35": $style_convert = "35A,35B,35C"; break;
				case "36": $style_convert = "36A,36B,36C,36D,36E,36F"; break;
				case "37": $style_convert = "37A,37B"; break;
				case "38": $style_convert = "38A,38B,38C"; break;
				case "39": $style_convert = "39A,39B,39C,39D,39E"; break;
				case "40": $style_convert = "40A,40B,40C,40D,40E,40F"; break;
				case "PR": $style_convert = "X1,X2,X3,X4,X5"; break;
				default: $style_convert = "Custom Style"; break;
			}
		}

		break;

		case "3":
		$n = preg_replace('/[^0-9]+/', '', $number);

		if ($_SESSION['prefsStyleSet'] == "BJCP2008") {
			if ($n >= 29) $style_convert = TRUE;
			else {
				switch ($number) {
					case "06D": $style_convert = TRUE; break;
					case "16E": $style_convert = TRUE; break;
					case "17F": $style_convert = TRUE; break;
					case "20A": $style_convert = TRUE; break;
					case "21A": $style_convert = TRUE; break;
					case "21B": $style_convert = TRUE; break;
					case "22B": $style_convert = TRUE; break;
					case "22C": $style_convert = TRUE; break;
					case "23A": $style_convert = TRUE; break;
					case "25C": $style_convert = TRUE; break;
					case "26A": $style_convert = TRUE; break;
					case "26C": $style_convert = TRUE; break;
					case "27E": $style_convert = TRUE; break;
					case "28B": $style_convert = TRUE; break;
					case "28C": $style_convert = TRUE; break;
					case "28D": $style_convert = TRUE; break;
					default: $style_convert = FALSE; break;
				}
			}
		}

		if ($_SESSION['prefsStyleSet'] == "BJCP2015") {
			if ($n >= 29) $style_convert = TRUE;
			else {
				switch ($number) {
					case "21B": $style_convert = TRUE; break;
					case "23F": $style_convert = TRUE; break;
					case "27A": $style_convert = TRUE; break;
					case "28A": $style_convert = TRUE; break;
					case "28B": $style_convert = TRUE; break;
					case "28C": $style_convert = TRUE; break;
					case "29A": $style_convert = TRUE; break;
					case "29B": $style_convert = TRUE; break;
					case "29C": $style_convert = TRUE; break;
					case "30A": $style_convert = TRUE; break;
					case "30B": $style_convert = TRUE; break;
					case "30C": $style_convert = TRUE; break;
					case "31A": $style_convert = TRUE; break;
					case "31B": $style_convert = TRUE; break;
					case "32B": $style_convert = TRUE; break;
					case "33A": $style_convert = TRUE; break;
					case "33B": $style_convert = TRUE; break;
					case "34A": $style_convert = TRUE; break;
					case "34B": $style_convert = TRUE; break;
					case "34C": $style_convert = TRUE; break;
					case "36D": $style_convert = TRUE; break;
					case "36E": $style_convert = TRUE; break;
					case "37A": $style_convert = TRUE; break;
					case "37B": $style_convert = TRUE; break;
					case "38B": $style_convert = TRUE; break;
					case "38C": $style_convert = TRUE; break;
					case "40B": $style_convert = TRUE; break;
					case "40E": $style_convert = TRUE; break;
					case "40F": $style_convert = TRUE; break;
					default: $style_convert = FALSE; break;
				}
			}
		}

		break;

		case "4":
		$replacement1 = array('Entry Instructions:','Commercial Examples:','must specify','may specify','MUST specify','MAY specify','must provide','must be specified','must declare','must either','must supply','may provide','MUST state');
		$replacement2 = array('<strong class="text-danger">Entry Instructions:</strong>','<strong class="text-info">Commercial Examples:</strong>','<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify','<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify','<u>MUST</u> provide','<strong><u>MUST</u></strong> be specified','<strong><u>MUST</u></strong> declare','<strong><u>MUST</u></strong> either','<strong><u>MUST</u></strong> supply','<strong><u>MAY</u></strong> provide','<strong><u>MUST</u></strong> state');

		if ($_SESSION['prefsStyleSet'] == "BA") $styleSet = "Brewers Association";
		else $styleSet = str_replace("2"," 2",$_SESSION['prefsStyleSet']);

		require(CONFIG.'config.php');
		mysqli_select_db($connection,$database);

		$a = explode(",",$number);

		foreach ($a as $value) {

			$styles_db_table = $prefix."styles";
			$query_style = sprintf("SELECT * FROM %s WHERE id='%s'",$styles_db_table,$value);
			$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
			$row_style = mysqli_fetch_assoc($style);
			$trimmed = ltrim($row_style['brewStyleGroup'],"0");

			if ($row_style['brewStyleOwn'] == "custom") $styleSet = "Custom"; else $styleSet = $styleSet;

			$info = str_replace($replacement1,$replacement2,"<p>".$row_style['brewStyleInfo']."</p>");

			if (!empty($row_style['brewStyleComEx'])) $info .= str_replace($replacement1,$replacement2,"<p>Commercial Examples: ".$row_style['brewStyleComEx']."</p>");
			if (!empty($row_style['brewStyleEntry'])) $info .= str_replace($replacement1,$replacement2,"<p>Entry Instructions: ".$row_style['brewStyleEntry']."</p>");

			if (empty($row_style['brewStyleOG'])) $styleOG = "Varies";
			else $styleOG = number_format((float)$row_style['brewStyleOG'], 3, '.', '')." &ndash; ".number_format((float)$row_style['brewStyleOGMax'], 3, '.', '');

			if (empty($row_style['brewStyleFG'])) $styleFG = "Varies";
			else $styleFG = number_format((float)$row_style['brewStyleFG'], 3, '.', '')." &ndash; ".number_format((float)$row_style['brewStyleFGMax'], 3, '.', '');

			if (empty($row_style['brewStyleABV'])) $styleABV = "Varies";
			else $styleABV = $row_style['brewStyleABV']." &ndash; ".$row_style['brewStyleABVMax'];

			if (empty($row_style['brewStyleIBU']))  $styleIBU = "Varies";
			elseif ($row_style['brewStyleIBU'] == "N/A") $styleIBU =  "N/A";
			elseif (!empty($row_style['brewStyleIBU'])) $styleIBU = ltrim($row_style['brewStyleIBU'], "0")." &ndash; ".ltrim($row_style['brewStyleIBUMax'], "0")." IBU";
			else $styleIBU = "&nbsp;";

			if (empty($row_style['brewStyleSRM'])) $styleColor = "Varies";
			elseif ($row_style['brewStyleSRM'] == "N/A") $styleColor = "N/A";
			elseif (!empty($row_style['brewStyleSRM'])) {
				$SRMmin = ltrim ($row_style['brewStyleSRM'], "0");
				$SRMmax = ltrim ($row_style['brewStyleSRMMax'], "0");
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
				<th class=\"dataLabel data bdr1B\">OG</th>
				<th class=\"dataLabel data bdr1B\">FG</th>
				<th class=\"dataLabel data bdr1B\">ABV</th>
				<th class=\"dataLabel data bdr1B\">".$label_bitterness."</th>
				<th class=\"dataLabel data bdr1B\">".$label_color."</th>
			</tr>
			<tr>
				<td nowrap>".$styleOG."</td>
				<td nowrap>".$styleFG."</td>
				<td nowrap>".$styleABV."</td>
				<td nowrap>".$styleIBU."</td>
				<td>".$styleColor."</td>
			</tr>
			</table>";

			$style_convert_1[] = "<a href=\"#\" data-target=\"#".$trimmed.$row_style['brewStyleNum']."\" data-toggle=\"modal\" data-tooltip=\"true\" title=\"".$row_style['brewStyle']."\">".$trimmed.$row_style['brewStyleNum']."</a>";
			$style_modal[] = "
				<!-- Modal -->
				<div class=\"modal fade\" id=\"".$trimmed.$row_style['brewStyleNum']."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$trimmed.$row_style['brewStyleNum']."Label\">
				  <div class=\"modal-dialog modal-lg\" role=\"document\">
					<div class=\"modal-content\">
					  <div class=\"modal-header\">
						<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"".$label_close."\"><span aria-hidden=\"true\">&times;</span></button>
						<h4 class=\"modal-title\" id=\"".$trimmed.$row_style['brewStyleNum']."Label\">".$styleSet." Style ".$trimmed.$row_style['brewStyleNum'].": ".$row_style['brewStyle']."</h4>
					  </div>
					  <div class=\"modal-body\">".$info."</div>
					  <div class=\"modal-footer\">
						<button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">".$label_close."</button>
					  </div>
					</div>
				  </div>
				</div>";

		} // end foreach

		$style_convert = rtrim(implode(", ",$style_convert_1),", ")."|".implode("^",$style_modal);


		break;

		case "5":
		$n = preg_replace('/[^0-9]+/', '', $number);
		if (($_SESSION['prefsStyleSet'] == "BJCP2008") && ($n >= 24)) $style_convert = TRUE;
		if (($_SESSION['prefsStyleSet'] == "BJCP2015") && ($n >= 35)) $style_convert = TRUE;
		break;

		case "6":
		$a = explode(",",$number);
		require(CONFIG.'config.php');
		mysqli_select_db($connection,$database);
		foreach ($a as $value) {
			$styles_db_table = $prefix."styles";
			$query_style = sprintf("SELECT brewStyleGroup,brewStyleNum,brewStyle FROM %s WHERE id='%s'",$styles_db_table,$value);
			$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
			$row_style = mysqli_fetch_assoc($style);
			$style_convert1[] = ltrim($row_style['brewStyleGroup'],"0").$row_style['brewStyleNum'];
		}
		$style_convert = rtrim(implode(", ",$style_convert1),", ");
		break;

		case "7":
		$a = explode(",",$number);
		$style_convert = "";
		$style_convert .= "<ul>";
		foreach ($a as $value) {
			$styles_db_table = $prefix."styles";
			$query_style = sprintf("SELECT brewStyleGroup,brewStyleNum,brewStyle,brewStyleOwn FROM %s WHERE id='%s'",$styles_db_table,$value);
			$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
			$row_style = mysqli_fetch_assoc($style);

			if ($row_style['brewStyle'] == "Soured Fruit Beer") $style_name = "Wild Specialty Beer";
			else $style_name = $row_style['brewStyle'];

			if ($row_style['brewStyleOwn'] == "bcoe") {
				if ($_SESSION['prefsStyleSet'] == "BA") $style_convert .= "<li>".$style_name."</li>";
				else $style_convert .= "<li>".ltrim($row_style['brewStyleGroup'],"0").$row_style['brewStyleNum'].": ".$style_name."</li>";
			}
			else $style_convert .= "<li>".$label_custom_style.": ".$row_style['brewStyle']."</li>";
		}
		$style_convert .= "</ul>";

		break;

		// Get Style Name
		case "8":
		$query_styles = sprintf("SELECT brewStyle,brewStyleNum,brewStyleGroup FROM %s WHERE id='%s'",$styles_db_table,$number);
		$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
		$row_styles = mysqli_fetch_assoc($styles);

		if ($row_styles['brewStyle'] == "Soured Fruit Beer") $style_name = "Wild Specialty Beer";
		else $style_name = $row_styles['brewStyle'];

		$style_convert = $row_styles['brewStyleGroup'].",".$row_styles['brewStyleNum'].",".$style_name;

		break;

		//
		case "9":
		$number = explode("^",$number);
		$query_style = sprintf("SELECT brewStyleNum,brewStyleGroup,brewStyle,brewStyleVersion,brewStyleReqSpec,brewStyleStrength,brewStyleCarb,brewStyleSweet FROM %s WHERE brewStyleGroup='%s' AND brewStyleNum='%s' AND (brewStyleVersion='%s' OR brewStyleOwn='custom')",$styles_db_table,$number[0],$number[1],$number[2]);
		$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
		$row_style = mysqli_fetch_assoc($style);

		if ($row_style['brewStyle'] == "Soured Fruit Beer") $style_name = "Wild Specialty Beer";
		else $style_name = $row_style['brewStyle'];

		$style_convert = $row_style['brewStyleGroup']."^".$row_style['brewStyleNum']."^".$style_name."^".$row_style['brewStyleVersion']."^".$row_style['brewStyleReqSpec']."^".$row_style['brewStyleStrength']."^".$row_style['brewStyleCarb']."^".$row_style['brewStyleSweet'];
		break;
	}
	return $style_convert;
}

function get_table_info($input,$method,$table_id,$dbTable,$param) {

	// Define Vars
	require(CONFIG.'config.php');
	require(LANG.'language.lang.php');
	mysqli_select_db($connection,$database);

	if ($dbTable == "default") {
		$judging_tables_db_table = $prefix."judging_tables";
		$judging_locations_db_table = $prefix."judging_locations";
		$judging_scores_db_table = $prefix."judging_scores";
		$judging_scores_bos_db_table = $prefix."judging_scores_bos";
		$styles_db_table = $prefix."styles";
		$brewing_db_table = $prefix."brewing";
		$styleSet = $_SESSION['prefsStyleSet'];
	}

	// Archives
	else {
		$suffix_1 = ltrim(get_suffix($dbTable), "_");
		$suffix = "_".$suffix_1;
		$judging_tables_db_table = $prefix."judging_tables".$suffix;
		$judging_locations_db_table = $prefix."judging_locations".$suffix;
		$judging_scores_db_table = $prefix."judging_scores".$suffix;
		$judging_scores_bos_db_table = $prefix."judging_scores_bos".$suffix;
		$styles_db_table = $prefix."styles";
		$archive_db_table = $prefix."archive";
		$brewing_db_table = $prefix."brewing".$suffix;

		$query_archive = sprintf("SELECT * FROM %s WHERE archiveSuffix='%s'",$archive_db_table,$suffix_1);
		$archive = mysqli_query($connection,$query_archive) or die (mysqli_error($connection));
		$row_archive = mysqli_fetch_assoc($archive);
		$styleSet = $row_archive['archiveStyleSet'];
	}

	// Get info about the table from the DB
	$query_table = "SELECT * FROM $judging_tables_db_table";
	if ($table_id != "default") $query_table .= " WHERE id='$table_id'";
	if ($param != "default") $query_table .= " WHERE tableLocation='$param'";
	$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
	$row_table = mysqli_fetch_assoc($table);

	// Only return basic info (table number, name, location, id)
	if ($method == "basic") {
		$return = $row_table['tableNumber']."^".$row_table['tableName']."^".$row_table['tableLocation']."^".$row_table['id'];
		return $return;
	}

	// Return the table's location
	if ($method == "location") { // used in output/assignments.php and output/pullsheets.php
		$query_judging_location = sprintf("SELECT * FROM %s WHERE id='%s'", $prefix."judging_locations", $input);
		$judging_location = mysqli_query($connection,$query_judging_location) or die (mysqli_error($connection));
		$row_judging_location = mysqli_fetch_assoc($judging_location);

		$return = $row_judging_location['judgingDate']."^".$row_judging_location['judgingTime']."^".$row_judging_location['judgingLocName'];
		return $return;
	}

	/*
	// Check if any styles have not been assigned to a table
	if ($method == "unassigned") {
		$return = "";

		$query_styles = "SELECT id,brewStyle FROM $styles_db_table";
		$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
		$row_styles = mysqli_fetch_assoc($styles);

		do {
			$a[] = $row_styles['id'];
		} while ($row_styles = mysqli_fetch_assoc($styles));

		sort($a);
		//echo "<p>"; print_r($a); echo "</p>";
		foreach ($a as $value) {
			//echo $input."<br>";
			$b = array(explode(",",$input));
			//echo "<p>".print_r($b)."</p>";
			//echo "<p>-".$value."-</p>";
			if (in_array($value,$b)) {
				//echo "Yes. The style ID is $value.<br>";
				//$query_styles1 = "SELECT brewStyle FROM $styles_db_table WHERE id='$value'";
				//$styles1 = mysqli_query($connection,$query_styles1) or die (mysqli_error($connection));
				//$row_styles1 = mysqli_fetch_assoc($styles1);
				//echo "<p>".$row_styles1['brewStyle']."</p>";
			}
			//else echo "No.<br>";
		}
	return $return;
	}

	// Check if style is assigned to a particular table
	if ($method == "styles") {

		$a = "";

		do {
			$a .= $row_table['tableStyles'].",";
				$a = explode(",", $row_table['tableStyles']);
				$b = $input;
				foreach ($a as $value) {
					if ($value == $input) return TRUE;
				}
		} while ($row_table = mysqli_fetch_assoc($table));
		$a = explode(",", $row_table['tableStyles']);
		//$b = rtrim($a,",");
		//$c = explode(",",$b);
		if (in_array($input,$a)) return TRUE;
		else return FALSE;
	}
	*/

	if ($method == "styles") {

		if ($table_id == "default") {
			$a = "";
			do {
				$a .= $row_table['tableStyles'].",";
			} while ($row_table = mysqli_fetch_assoc($table));
			$b = explode(",", $a);
			if (in_array($input,$b)) return TRUE;
			else return FALSE;
		}

		else {
			$a = explode(",", $row_table['tableStyles']);
			if (in_array($input,$a)) return TRUE;
			else return FALSE;
		}

	}

	// Display if style already assigned to a table
	if ($method == "assigned") {

		$query_table_info = "SELECT id,tableNumber,tableName,tableStyles FROM $judging_tables_db_table";
		$table_info = mysqli_query($connection,$query_table_info) or die (mysqli_error($connection));
		$row_table_info = mysqli_fetch_assoc($table_info);
		$totalRows_table_info = mysqli_num_rows($table_info);

		if ($totalRows_table_info > 0) {

			do {

				$table_styles_array = explode(",",$row_table_info['tableStyles']);

				if (in_array($input,$table_styles_array)) return "<br><em>".$label_assigned_to_table." ".$row_table_info['tableNumber'].": <a href='index.php?section=admin&go=judging_tables&action=edit&id=".$row_table_info['id']."'>".$row_table_info['tableName']."</a></em>.";

			} while ($row_table_info = mysqli_fetch_assoc($table_info));

		}

	}

	// Get list of styles at table
	if ($method == "list") {

		if (!empty($row_table['tableStyles'])) {

			$a = explode(",", $row_table['tableStyles']);

				foreach ($a as $value) {

					require(CONFIG.'config.php');
					mysqli_select_db($connection,$database);
					$query_styles = sprintf("SELECT * FROM %s WHERE id='%s'", $styles_db_table, $value);
					$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
					$row_styles = mysqli_fetch_assoc($styles);
					if ($styleSet == "BA") $c[] = $row_styles['brewStyle'].",&nbsp;";
					else $c[] = ltrim($row_styles['brewStyleGroup'].$row_styles['brewStyleNum'],"0").",&nbsp;";

				}

			$d = array($c);
			return $d;

		}

	}

	// Get count of entries
	if (($method == "count_total") && ($param == "default")) {

		$a = explode(",", $row_table['tableStyles']);

		$debug = "";

		foreach ($a as $value) {

			require(CONFIG.'config.php');
			mysqli_select_db($connection,$database);
			$query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value);
			$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
			$row_styles = mysqli_fetch_assoc($styles);

			$query_style_count = sprintf("SELECT COUNT(*) as count FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table, $row_styles['brewStyleGroup'], $row_styles['brewStyleNum']);

			$style_count = mysqli_query($connection,$query_style_count) or die (mysqli_error($connection));
			$row_style_count = mysqli_fetch_assoc($style_count);

			$debug .= $query_style_count."<br>";

			$c[] = $row_style_count['count'];

		}

		$d = array_sum($c);
		// $d = $debug;

	return $d;

	}

	// Get total number of scored entries at table
	if (($method == "score_total") && ($param == "default")) {

		$query_score_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE scoreTable='%s'", $judging_scores_db_table, $table_id);
		$score_count = mysqli_query($connection,$query_score_count) or die (mysqli_error($connection));
		$row_score_count = mysqli_fetch_assoc($score_count);

		return $row_score_count['count'];
	}

	if (($method == "count_total") && ($param != "default")) {

		require(CONFIG.'config.php');
		mysqli_select_db($connection,$database);

		$debug = "";

			do {
				$a = explode(",", $row_table['tableStyles']);

				foreach ($a as $value) {

					$query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value);
					$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
					$row_styles = mysqli_fetch_assoc($styles);

					$query_style_count = sprintf("SELECT COUNT(*) as count FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table, $row_styles['brewStyleGroup'], $row_styles['brewStyleNum']);
					$style_count = mysqli_query($connection,$query_style_count) or die (mysqli_error($connection));
					$row_style_count = mysqli_fetch_assoc($style_count);

					$debug .= $query_style_count."<br>";

					$c[] = $row_style_count['count'];

				}

			} while ($row_table = mysqli_fetch_assoc($table));

		$d = array_sum($c);
		//$d = $debug;
		return $d;
	}


	if ($method == "count") {
		require(CONFIG.'config.php');
		mysqli_select_db($connection,$database);

		//$row_styles['brewStyleNum']."^".$row_styles['brewStyleGroup']
		$input = explode("^",$input);

		$query = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $prefix."brewing",
		$input[1], $input[0]);
		$result = mysqli_query($connection,$query) or die (mysqli_error($connection));
		$num_rows = mysqli_fetch_array($result);

		//echo $query."<br>";
		//echo $num_rows['count']."<br>";
		//$num_rows = mysqli_num_rows($result);
		//echo $num_rows."<br>";

		return $num_rows['count'];

	}

	if ($method == "count_scores") {
		$a = explode(",", $row_table['tableStyles']);

		foreach ($a as $value) {

			$query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value);
			$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
			$row_styles = mysqli_fetch_assoc($styles);

			$query_style_count = sprintf("SELECT COUNT(*) as count FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table, $row_styles['brewStyleGroup'], $row_styles['brewStyleNum']);
			$style_count = mysqli_query($connection,$query_style_count) or die (mysqli_error($connection));
			$row_style_count = mysqli_fetch_assoc($style_count);


			$c[] = $row_style_count['count'];

		}

		$e = array_sum($c);

		if ($e == $row_score_count['count']) return true;
	}

	if ($method == "count_single_table") {

		$query_score_count = sprintf("SELECT COUNT(*) as 'count' FROM $judging_scores_db_table WHERE scoreTable='%s'", $input);
		$score_count = mysqli_query($connection,$query_score_count) or die (mysqli_error($connection));
		$row_score_count = mysqli_fetch_assoc($score_count);
		return $row_score_count['count'];

	}

} // end get_table_info()

function style_type($type,$method,$source) {
	if ($method == "1") {
		switch($type) {
			case "Mead": $type = "3"; break;
			case "Cider": $type = "2"; break;
			case "Mixed": $type = "1"; break;
			case "Ale": $type = "1"; break;
			case "Lager": $type = "1"; break;
			default: $type = $type; break;
		}
	}

	if (($method == "2") && ($source == "bcoe")) {
		switch($type) {
			case "3": $type = "Mead"; break;
			case "2": $type = "Cider"; break;
			case "1": $type = "Beer"; break;
			case "Lager": $type = "Beer"; break;
			case "Ale": $type = "Beer"; break;
			case "Mixed": $type = "Beer"; break;
			default: $type = $type; break;
		}
	}

	if (($method == "2") && ($source == "custom")) {
		require(CONFIG.'config.php');
		mysqli_select_db($connection,$database);

		$query_style_type = sprintf("SELECT styleTypeName FROM %s WHERE id='%s'", $prefix."style_types", $type);
		$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
		$row_style_type = mysqli_fetch_assoc($style_type);
		$type = $row_style_type['styleTypeName'];
	}

	if ($method == "3") {
		require(CONFIG.'config.php');
		mysqli_select_db($connection,$database);

		$query_style_type = sprintf("SELECT styleTypeName FROM %s WHERE id='%s'", $prefix."style_types", $type);
		$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
		$row_style_type = mysqli_fetch_assoc($style_type);
		$type = $row_style_type['styleTypeName'];
	}
	return $type;
}
/*
function check_bos_loc($id) {
	require(CONFIG.'config.php');
	$query_judging = sprintf("SELECT judgingLocName,judgingDate FROM %s WHERE id='$id'", $prefix."judging_locations");
	$judging = mysqli_query($connection,$query_judging) or die (mysqli_error($connection));
	$row_judging = mysqli_fetch_assoc($judging);
	$totalRows_judging = mysqli_num_rows($judging);
	$bos_loc = $row_judging['judgingLocName']." (".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time").")";
	return $bos_loc;
}
*/


function table_location($table_id,$date_format,$time_zone,$time_format,$method) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$query_table = sprintf("SELECT tableLocation FROM %s WHERE id='%s'", $prefix."judging_tables", $table_id);
	$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
	$row_table = mysqli_fetch_assoc($table);

	if ($method == "some-variable") {
		// Future use
	}

	if ($method == "default") {

		$query_location = sprintf("SELECT judgingLocName,judgingDate,judgingTime FROM %s WHERE id='%s'", $prefix."judging_locations", $row_table['tableLocation']);
		$location = mysqli_query($connection,$query_location) or die (mysqli_error($connection));
		$row_location = mysqli_fetch_assoc($location);
		$totalRows_location = mysqli_num_rows($location);

		if ($totalRows_location == 1) {
			$table_location = $row_location['judgingLocName'].", ".getTimeZoneDateTime($time_zone, $row_location['judgingDate'], $date_format,  $time_format, "long", "date-time-no-gmt");
		}
		else $table_location = "";
	}

	return $table_location;
}

function score_count($table_id,$method) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_scores = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE scoreTable='%s'", $prefix."judging_scores", $table_id);
	$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
	$row_scores = mysqli_fetch_assoc($scores);

	switch($method) {
		case "1": if ($row_scores['count'] > 0) return true; else return false;
		break;

		case "2": return $row_scores['count'];
		break;
	}

}

function best_brewer_points($bid, $places, $entry_scores, $points_prefs, $tiebreaker) {
	// Main points
	$pts_first = $points_prefs[0]*$places[0]; // points for each first place position
	$pts_second = $points_prefs[1]*$places[1]; // points for each secon place position
	$pts_third = $points_prefs[2]*$places[2]; // points for each third place position
	$pts_fourth = $points_prefs[3]*$places[3]; // points for each fourth place position
	$pts_hm = $points_prefs[4]*$places[4]; // points for each honorable mention
	// Tie breakers

	$pts_tb_num_places = 0;
	$pts_tb_first_places = 0;
	$pts_tb_num_entries = 0;
	$pts_tb_min_score = 0;
	$pts_tb_max_score = 0;
	$pts_tb_avg_score = 0;
	$pts_tb_bos = 0;

	$power = 0;

	$imax = count($tiebreaker) - 1;

	$number_of_entries = total_paid_received("",$bid);

	for ($i = 0; $i<= $imax; $i++) {
		switch ($tiebreaker[$i]) {
			// points for the number of 1st, 2nd, and 3rd places
			case "TBTotalPlaces" :
				$power  += 2;
				$pts_tb_num_places = array_sum(array_slice($places,0,3))/pow(10,$power);
				break;
			// points for the number of 1st, 2nd, 3rd, 4th, HM places
			case "TBTotalExtendedPlaces" :
				$power  += 2;
				$pts_tb_num_places = array_sum($places)/pow(10,$power);
				break;
			// points for number of first places
			case "TBFirstPlaces" :
				$power  += 2;
				$pts_tb_first_places = $places[0]/pow(10,$power);
				break;
			// points for the number of competing entries (the smallest the better, of course)
			case "TBNumEntries" :
				$power  += 4;
				$pts_tb_num_entries = floor(100/$number_of_entries)/pow(10,$power);
				break;
			// points for the minimum score
			case "TBMinScore" :
				$power  += 4;
				$pts_tb_min_score = floor(10*min($entry_scores))/pow(10,$power);
				break;
			// points for the maximum score
			case "TBMaxScore" :
				$power  += 4;
				$pts_tb_max_score = floor(10*max($entry_scores))/pow(10,$power);
				break;
			// points for the average score
			case "TBAvgScore" :
				$power  += 4;
				$pts_avg_score = floor(10*array_sum($entry_scores)/$number_of_entries)/pow(10,$power);
				break;
		}

	}

	$points = $pts_first + $pts_second + $pts_third + $pts_fourth + $pts_hm + $pts_tb_num_places + $pts_tb_first_places + $pts_tb_num_entries + $pts_tb_min_score + $pts_tb_max_score + $pts_tb_avg_score;
	return $points;
}

function bjcp_rank($rank,$method) {
	if ($method == "1") {
		switch($rank) {
			case "Apprentice":
			case "Provisional":
			case "Rank Pending":
			$return = "Level 1:"; break;
			case "Recognized": $return = "Level 2:"; break;
			case "Certified": $return = "Level 3:"; break;
			case "National": $return = "Level 4:"; break;
			case "Master": $return = "Level 5:"; break;
			case "Grand Master": $return = "Level 6:"; break;
			case "Honorary Master": $return = "Level 5:"; break;
			case "Honorary Grand Master": $return = "Level 6:"; break;
			case "Experienced": $return = "Level 0:"; break;
			case "Professional Brewer":
			case "Beer Sommelier":
			case "Certified Cicerone":
			case "Master Cicerone":
			case "Judge with Sensory Training":
			$return = "Level 2:"; break;
			case "Mead Judge": $return = "Level 3:"; break;
			default: $return = "Level 0:";
		}
	if (($rank != "None") && ($rank != "")) $return .= " ".$rank;
	else $return .= " Non-BJCP Judge";
	}

	if ($method == "2") {
		switch($rank) {
			case "None":
			case "":
			case "Novice";
			case "Non-BJCP";
			case "Experienced":
			$return = "Non-BJCP Judge"; break;
			case "Professional Brewer":
			case "Beer Sommelier":
			case "Certified Cicerone":
			case "Master Cicerone":
			case "Judge with Sensory Training":
			$return = $rank; break;
			default: $return = "BJCP ".$rank." Judge";
		}
	}

	return $return;
}

function srm_color($srm,$method) {
	if ($method == "ebc") $srm = (1.97 * $srm); else $srm = $srm;

	if ($srm >= 1 && $srm < 2) $return = "#f3f993";
	elseif ($srm >= 2 && $srm < 3) $return = "#f5f75c";
	elseif ($srm >= 3 && $srm < 4) $return = "#f6f513";
	elseif ($srm >= 4 && $srm < 5) $return = "#eae615";
	elseif ($srm >= 5 && $srm < 6) $return = "#e0d01b";
	elseif ($srm >= 6 && $srm < 7) $return = "#d5bc26";
	elseif ($srm >= 7 && $srm < 8) $return = "#cdaa37";
	elseif ($srm >= 8 && $srm < 9) $return = "#c1963c";
	elseif ($srm >= 9 && $srm < 10) $return = "#be8c3a";
	elseif ($srm >= 10 && $srm < 11) $return = "#be823a";
	elseif ($srm >= 11 && $srm < 12) $return = "#c17a37";
	elseif ($srm >= 12 && $srm < 13) $return = "#bf7138";
	elseif ($srm >= 13 && $srm < 14) $return = "#bc6733";
	elseif ($srm >= 14 && $srm < 15) $return = "#b26033";
	elseif ($srm >= 15 && $srm < 16) $return = "#a85839";
	elseif ($srm >= 16 && $srm < 17) $return = "#985336";
	elseif ($srm >= 17 && $srm < 18) $return = "#8d4c32";
	elseif ($srm >= 18 && $srm < 19) $return = "#7c452d";
	elseif ($srm >= 19 && $srm < 20) $return = "#6b3a1e";
	elseif ($srm >= 20 && $srm < 21) $return = "#5d341a";
	elseif ($srm >= 21 && $srm < 22) $return = "#4e2a0c";
	elseif ($srm >= 22 && $srm < 23) $return = "#4a2727";
	elseif ($srm >= 23 && $srm < 24) $return = "#361f1b";
	elseif ($srm >= 24 && $srm < 25) $return = "#261716";
	elseif ($srm >= 25 && $srm < 26) $return = "#231716";
	elseif ($srm >= 26 && $srm < 27) $return = "#19100f";
	elseif ($srm >= 27 && $srm < 28) $return = "#16100f";
	elseif ($srm >= 28 && $srm < 29) $return = "#120d0c";
	elseif ($srm >= 29 && $srm < 30) $return = "#100b0a";
	elseif ($srm >= 30 && $srm < 31) $return = "#050b0a";
	elseif ($srm > 31) $return = "#000000";
	else $return = "#ffffff";
return $return;
}

function get_contact_count() {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_contact_count = sprintf("SELECT COUNT(*) as 'count' FROM %s",$prefix."contacts");
	$result = mysqli_query($connection,$query_contact_count) or die (mysqli_error($connection));
	$row = mysqli_fetch_assoc($result);
	$contactCount = $row['count'];
	return $contactCount;
}

function brewer_info($uid,$filter="default") {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	if ($filter == "default") $brewer_db_table = $prefix."brewer";
	else $brewer_db_table = $prefix."brewer_".$filter;
	$query_brewer_info = sprintf("SELECT * FROM %s WHERE uid='%s'", $brewer_db_table, $uid);
	$brewer_info = mysqli_query($connection,$query_brewer_info) or die (mysqli_error($connection));
	$row_brewer_info = mysqli_fetch_assoc($brewer_info);
	$r = "";
	$r .= $row_brewer_info['brewerFirstName']."^"; 		// 0
	$r .= $row_brewer_info['brewerLastName']."^"; 		// 1
	$r .= $row_brewer_info['brewerPhone1']."^"; 		// 2
	if (isset($row_brewer_info['brewerJudgeRank'])) {
		if (($row_brewer_info['brewerJudgeMead'] == "Y") && ($row_brewer_info['brewerJudgeRank'] == "Non-BJCP")) $r .= "Non-BJCP Beer^";
		else $r .= $row_brewer_info['brewerJudgeRank']."^";
	}
	else $r .= "Non-BJCP^"; 							// 3
	if (isset($row_brewer_info['brewerJudgeID'])) $r .= $row_brewer_info['brewerJudgeID']."^"; else $r .= "&nbsp;^"; // 4
	$r .= "&nbsp;^"; // 5 deprecated 2.1.14
	$r .= $row_brewer_info['brewerEmail']."^";			// 6
	$r .= $row_brewer_info['uid']."^";					// 7
	if (isset($row_brewer_info['brewerClubs'])) $r .= $row_brewer_info['brewerClubs']."^"; else $r .= "&nbsp;^"; // 8
	if (isset($row_brewer_info['brewerDiscount'])) $r .= $row_brewer_info['brewerDiscount']."^"; else $r .= "&nbsp;^"; // 9
	$r .= $row_brewer_info['brewerAddress']."^";		// 10
	$r .= $row_brewer_info['brewerCity']."^";			// 11
	$r .= $row_brewer_info['brewerState']."^";			// 12
	$r .= $row_brewer_info['brewerZip']."^";			// 13
	$r .= $row_brewer_info['brewerCountry']."^";		// 14
	if ($_SESSION['prefsProEdition'] == 1) $r .= $row_brewer_info['brewerBreweryName']."^"; else $r .= "&nbsp;^"; // 15
	if ($row_brewer_info['brewerJudgeMead'] == "Y") $r .= "Certified Mead Judge"; else $r .= "&nbsp;^"; // 16
	if ($_SESSION['prefsProEdition'] == 1) $r .= $row_brewer_info['brewerBreweryTTB']."^"; else $r .= "&nbsp;^";// 17
	return $r;
}

function get_entry_count($method) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s",$prefix."brewing");
	if ($method == "paid") $query_paid .= " WHERE brewPaid='1'";
	if ($method == "received") $query_paid .= " WHERE brewReceived='1'";
	if ($method == "paid-received") $query_paid .= " WHERE brewReceived='1' AND brewPaid='1'";
	if ($method == "unpaid-received") $query_paid .= " WHERE brewReceived='1' AND brewPaid='0'";
	if ($method == "paid-not-received") $query_paid .= " WHERE brewReceived='0' AND brewPaid='1'";
	if ($method == "total-logged") $query_paid .= "";
	$paid = mysqli_query($connection,$query_paid) or die (mysqli_error($connection));
	$row_paid = mysqli_fetch_assoc($paid);
	$r = $row_paid['count'];
	return $r;
}

function get_participant_count($type) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	if ($type == 'default') $query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s",$prefix."brewer");
	if ($type == 'judge') $query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewerJudge='Y'",$prefix."brewer");
	if ($type == 'steward') $query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewerSteward='Y'",$prefix."brewer");
	if ($type == 'received-entrant') $query_participant_count = sprintf("SELECT COUNT(DISTINCT brewBrewerID) as 'count' FROM %s WHERE brewReceived='1'",$prefix."brewing");
	if ($type == 'received-club') $query_participant_count = sprintf("SELECT COUNT(DISTINCT b.brewerClubs) as 'count' FROM %s a, %s b WHERE b.uid = a.brewBrewerID AND b.brewerClubs IS NOT NULL", $prefix."brewing", $prefix."brewer");
	$participant_count = mysqli_query($connection,$query_participant_count) or die (mysqli_error($connection));
	$row_participant_count = mysqli_fetch_assoc($participant_count);

	return $row_participant_count['count'];
}

function display_place($place,$method) {

	require(CONFIG.'config.php');

	if ($method == "0") {
		$place = addOrdinalNumberSuffix($place);
	}

	if ($method == "1") {
		switch($place){
			case "1": $place = addOrdinalNumberSuffix($place); break;
			case "2": $place = addOrdinalNumberSuffix($place); break;
			case "3": $place = addOrdinalNumberSuffix($place); break;
			case "4": $place = addOrdinalNumberSuffix($place); break;
			case "5":
			case "HM": $place = "HM"; break;
		default: $place = "N/A";
		}
	}

	if ($method == "2") {
		switch($place){
			case "1": $place = "<span class='fa fa-lg fa-trophy text-gold'></span> ".addOrdinalNumberSuffix($place); break;
			case "2": $place = "<span class='fa fa-lg fa-trophy text-silver'></span> ".addOrdinalNumberSuffix($place); break;
			case "3": $place = "<span class='fa fa-lg fa-trophy text-bronze'></span> ".addOrdinalNumberSuffix($place); break;
			case "4": $place = "<span class='fa fa-lg fa-trophy text-purple'></span> ".addOrdinalNumberSuffix($place); break;
			case "5":
			case "HM": $place = "<span class='fa fa-lg fa-trophy text-teal'></span> HM"; break;
			default: $place = "N/A";
			}
	}

	if ($method == "3") {
		switch($place){
			case "1": $place = "<span class='fa fa-lg fa-trophy text-gold'></span> ".addOrdinalNumberSuffix($place); break;
			case "2": $place = "<span class='fa fa-lg fa-trophy text-silver'></span> ".addOrdinalNumberSuffix($place); break;
			case "3": $place = "<span class='fa fa-lg fa-trophy text-bronze'></span> ".addOrdinalNumberSuffix($place); break;
			case "4": $place = "<span class='fa fa-lg fa-trophy text-purple'></span> ".addOrdinalNumberSuffix($place); break;
			case "HM":  $place = "<span class='fa fa-lg fa-trophy text-teal'></span> HM"; break;
			default: $place = "<span class='fa fa-lg fa-trophy text-forest-green'></span> ".addOrdinalNumberSuffix($place);
			}
	}

	return $place;
}

function entry_info($id) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_entry_info = sprintf("SELECT brewName,brewCategory,brewCategorySort,brewSubCategory,brewStyle,brewCoBrewer,brewJudgingNumber FROM %s WHERE id='%s'", $prefix."brewing", $id);
	$entry_info = mysqli_query($connection,$query_entry_info) or die (mysqli_error($connection));
	$row_entry_info = mysqli_fetch_assoc($entry_info);
	$r = $row_entry_info['brewName']."^".$row_entry_info['brewCategorySort']."^".$row_entry_info['brewSubCategory']."^".$row_entry_info['brewStyle']."^".$row_entry_info['brewCoBrewer']."^".$row_entry_info['brewCategory']."^".$row_entry_info['brewJudgingNumber'];
	return $r;
}

function get_suffix($dbTable) {
	$suffix = strrchr($dbTable,"_");
	$suffix = ltrim($suffix, "_");
	return $suffix;
}

function score_check($id,$judging_scores_db_table) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_scores = sprintf("SELECT scoreEntry FROM %s WHERE eid='%s'",$judging_scores_db_table,$id);
	$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
	$row_scores = mysqli_fetch_assoc($scores);

	$r = $row_scores['scoreEntry'];
	return $r;
}

function minibos_check($id,$judging_scores_db_table) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_scores = sprintf("SELECT scoreMiniBOS FROM %s WHERE eid='%s'",$judging_scores_db_table,$id);
	$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
	$row_scores = mysqli_fetch_assoc($scores);

	if ($row_scores['scoreMiniBOS'] == "1") return TRUE;
	else return FALSE;
}

function winner_check($id,$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$method) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	if ($method == 6) { // reserved for NHC admin advance
		$r = "Administrative Advance";
	}

	if ($method < 6) {
	$query_scores = sprintf("SELECT eid,scorePlace,scoreTable FROM %s WHERE eid='%s'",$judging_scores_db_table,$id);
	$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
	$row_scores = mysqli_fetch_assoc($scores);

	if ($row_scores['scorePlace'] >= "1") {

		if ($method == "0") {  // Display by Table

		$query_table = sprintf("SELECT tableName FROM $judging_tables_db_table WHERE id='%s'", $row_scores['scoreTable']);
		$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
		$row_table = mysqli_fetch_assoc($table);
		$r = display_place($row_scores['scorePlace'],1).": ".$row_table['tableName'];
		}

		if ($method == "1") {  // Display by Category
			$query_entry = sprintf("SELECT brewCategorySort,brewSubCategory FROM $brewing_db_table WHERE id='%s'", $row_scores['eid']);
			$entry = mysqli_query($connection,$query_entry) or die (mysqli_error($connection));
			$row_entry = mysqli_fetch_assoc($entry);
			if ($_SESSION['prefsStyleSet'] != "BA") $r = display_place($row_scores['scorePlace'],1).": ".style_convert($row_entry['brewCategorySort'],1);
			else {

					if (is_numeric($row_entry['brewSubCategory'])) {
						$style = $_SESSION['styles']['data'][$row_entry['brewSubCategory'] - 1]['category']['name'];
						if ($style == "Hybrid/mixed Beer") $style = "Hybrid/Mixed Beer";
						elseif ($style == "European-germanic Lager") $style = "European-Germanic Lager";
						else $style = ucwords($style);
					}
					else $style = "Custom Style";

				$r = display_place($row_scores['scorePlace'],1).": ".$style;
			}
		}

		if ($method == "2") {  // Display by Sub-Category

		$query_entry = sprintf("SELECT brewCategorySort,brewCategory,brewSubCategory FROM $brewing_db_table WHERE id='%s'", $row_scores['eid']);
		$entry = mysqli_query($connection,$query_entry) or die (mysqli_error($connection));
		$row_entry = mysqli_fetch_assoc($entry);

		$query_style = sprintf("SELECT brewStyle FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles", $_SESSION['prefsStyleSet'], $row_entry['brewCategorySort'],$row_entry['brewSubCategory']);
		$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
		$row_style = mysqli_fetch_assoc($style);

		$r = display_place($row_scores['scorePlace'],1).": ".$row_style['brewStyle']." (".$row_entry['brewCategory'].$row_entry['brewSubCategory'].")";
		}
	}
	else $r = "";
	}
	//$r = "<td class=\"dataList\">".$query_scores."<br>".$query_table."</td>";
	return $r;
}

function brewer_assignment($user_id,$method,$id,$dbTable,$filter,$archive="default") {

	require(CONFIG.'config.php');
	require(LANG.'language.lang.php');
	mysqli_select_db($connection,$database);

	if ($archive != "default") $staff_db_table = $prefix."staff_".$archive;
	else $staff_db_table = $prefix."staff";

	$totalRows_staff_check = 0;
	$assignment = "";

	$query_staff_check = sprintf("SELECT * FROM %s WHERE uid='%s'", $staff_db_table, $user_id);
	$staff_check = mysqli_query($connection,$query_staff_check) or die (mysqli_error($connection));
	$row_staff_check = mysqli_fetch_assoc($staff_check);
	$totalRows_staff_check = mysqli_num_rows($staff_check);

	if ($row_staff_check['staff_judge'] == "1") $assignment = strtolower($label_judges);
	elseif ($row_staff_check['staff_steward'] == "1") $assignment = strtolower($label_stewards);
	else $assignment = "";

	if ($totalRows_staff_check > 0) {
		$r[] = "";
			switch($method) {
				case "1": //
					if ($row_staff_check['staff_organizer'] == "1") $r[] .= strtolower($label_organizer);
					if ($row_staff_check['staff_judge_bos'] == "1") $r[] .= "BOS";
					if ($row_staff_check['staff_judge'] == "1") $r[] .= $label_judge;
					if ($row_staff_check['staff_steward'] == "1") $r[] .= $label_steward;
					if ($row_staff_check['staff_staff'] == "1") $r[] .= $label_staff;
				break;
				case "staff_judge": // for $filter URL variable
					if ($row_staff_check['staff_judge'] == "1") $r = "CHECKED";
					elseif ($a == "stewards") $r = "S";
					elseif ($a == "staff") $r = "X";
					elseif ($a == "bos") $r = "Y";
					else $r = "";
				break;
			}
		if (!empty($r)) $r = implode(", ",$r);
		$r = rtrim($r,", ");
		$r = ltrim($r,", ");
	}
	else $r = "";

	if ($method == "3") {
		if ($filter == "judges") $r = $label_judges;
		elseif ($filter == "stewards") $r = $label_stewards;
		elseif ($filter == "staff") $r = $label_staff;
		elseif ($filter == "bos") $r = "BOS ".$label_judges;
		else $r = "";
	}

return $r;
}

function entries_unconfirmed($user_id) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_entry_check = sprintf("SELECT id FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='0'", $prefix."brewing", $user_id);
	$entry_check = mysqli_query($connection,$query_entry_check) or die (mysqli_error($connection));
	$row_entry_check = mysqli_fetch_assoc($entry_check);
	$totalRows_entry_check = mysqli_num_rows($entry_check);

	if ($totalRows_entry_check > 0)	{

		do {
			$r[] = $row_entry_check['id'];
		} while ($row_entry_check = mysqli_fetch_assoc($entry_check));

	}
	else $r = array("0");
	return $r;
}

function check_special_ingredients($style,$style_version) {

	include (CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$style_explodies = explode("-",$style);

	$query_brews = sprintf("SELECT brewStyleReqSpec FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles", $style_version, $style_explodies[0], $style_explodies[1]);
	$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
	$row_brews = mysqli_fetch_assoc($brews);

	if ($row_brews['brewStyleReqSpec'] == 1) return TRUE;
	else return FALSE;
}

function entries_no_special($user_id) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$query_entry_check = sprintf("SELECT brewCategorySort, brewSubCategory FROM %s WHERE brewBrewerID='%s' AND brewInfo IS NULL", $prefix."brewing", $user_id);
	$entry_check = mysqli_query($connection,$query_entry_check) or die (mysqli_error($connection));
	$row_entry_check = mysqli_fetch_assoc($entry_check);

	do {
		$brew_style[] = $row_entry_check['brewCategorySort']."-".$row_entry_check['brewSubCategory'];
	} while ($row_entry_check = mysqli_fetch_assoc($entry_check));

	foreach ($brew_style as $style) {

		if (check_special_ingredients($style,$_SESSION['prefsStyleSet'])) $totalRows_entry_check[] = 1; else $totalRows_entry_check[] = 0;

	}

	if (array_sum($totalRows_entry_check) > 0)	return TRUE;
	else return FALSE;
}


function data_integrity_check() {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	// Match user emails against the record in the brewer table,
	// Compare user's id against uid,
	// If no match, replace uid with user's id

	$query_user_check = sprintf("SELECT id,user_name FROM %s", $prefix."users");
	$user_check = mysqli_query($connection,$query_user_check) or die (mysqli_error($connection));
	$row_user_check = mysqli_fetch_assoc($user_check);

	do {

		// Get Brewer Info
		$query_brewer = sprintf("SELECT id,uid,brewerEmail,brewerFirstName,brewerLastname FROM %s WHERE brewerEmail='%s'",$prefix."brewer",$row_user_check['user_name']);
		$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
		$row_brewer = mysqli_fetch_assoc($brewer);
		$totalRows_brewer = mysqli_num_rows($brewer);

		// Check to see if info is matching up. If not...
		if (($row_brewer['brewerEmail'] == $row_user_check['user_name']) && ($row_brewer['uid'] != $row_user_check['id']) && ($totalRows_brewer == 1)) {
			// ...Update to the correct uid
			$updateSQL = sprintf("UPDATE %s SET uid='%s' WHERE id='%s'", $prefix."brewer", $row_user_check['id'], $row_brewer['id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Change all associated entries to the correct uid (brewBrewerID row) in the "brewing" table
			$query_brewer_entries = sprintf("SELECT id FROM %s WHERE brewBrewerLastName='%s' AND brewBrewerFirstName='%s'",$prefix."brewing",$row_brewer['brewerLastName'],$row_brewer['brewerLastName']);
			$brewer_entries = mysqli_query($connection,$query_brewer_entries) or die (mysqli_error($connection));
			$row_brewer_entries = mysqli_fetch_assoc($brewer_entries);
			$totalRows_brewer_entries = mysqli_num_rows($brewer_entries);

			if ($totalRows_brewer_entries > 0) {
				do {
					$updateSQL = sprintf("UPDATE %s SET brewBrewerID='%s' WHERE id='%s'", $prefix."brewing", $row_brewer_entries['id'], $row_brewer['id']);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				} while ($row_brewer_entries = mysqli_fetch_assoc($brewer_entries));
			}
		} // end if (($row_brewer['brewerEmail'] == $row_user_check['user_name']) && ($row_brewer['uid'] != $row_user_check['id']) && ($totalRows_brewer == 1))


		// Delete user record if no record of the user's extended information is found in the "brewer" table
		if ($totalRows_brewer == 0) {
			$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."users", $row_user_check['id']);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

			// Check to see if there are entries under that uid. If so, delete.
			$query_brewer_entries = sprintf("SELECT id FROM %s WHERE brewBrewerID='%s'",$prefix."brewing",$row_user_check['id']);
			$brewer_entries = mysqli_query($connection,$query_brewer_entries) or die (mysqli_error($connection));
			$row_brewer_entries = mysqli_fetch_assoc($brewer_entries);
			$totalRows_brewer_entries = mysqli_num_rows($brewer_entries);

			if ($totalRows_brewer_entries > 0) {
				do {
					$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $row_brewer_entries['id']);
					mysqli_real_escape_string($connection,$deleteSQL);
					$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
				} while ($row_brewer_entries = mysqli_fetch_assoc($brewer_entries));
			}
		} // end if ($totalRows_brewer == 0)
	} while ($row_user_check = mysqli_fetch_assoc($user_check));

	// Check if there are "blank" entries. If so, delete.
	$query_blank = sprintf("SELECT id FROM %s WHERE (brewStyle IS NULL OR brewStyle = '') AND (brewCategory IS NULL OR brewCategory = '') AND (brewCategorySort IS NULL OR brewCategorySort = '') AND (brewBrewerID IS NULL OR brewBrewerID = '')",$prefix."brewing");
	$blank = mysqli_query($connection,$query_blank) or die (mysqli_error($connection));
	$row_blank = mysqli_fetch_assoc($blank);
	$totalRows_blank = mysqli_num_rows($blank);

	if ($totalRows_blank > 0) {
		do {
			$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $row_blank['id']);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
		} while ($row_blank = mysqli_fetch_assoc($blank));
	}

	// Check if there are "blanks" in the brewer table. If so, delete.
	$query_blank1 = sprintf("SELECT id FROM %s WHERE (brewerFirstName IS NULL OR brewerFirstName = '') AND (brewerLastName IS NULL OR brewerLastName = '')",$prefix."brewer");
	$blank1 = mysqli_query($connection,$query_blank1) or die (mysqli_error($connection));
	$row_blank1 = mysqli_fetch_assoc($blank1);
	$totalRows_blank1 = mysqli_num_rows($blank1);

	if ($totalRows_blank1 > 0) {
		do {
			$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewer", $row_blank1['id']);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
		} while ($row_blank1 = mysqli_fetch_assoc($blank1));
	}

	// Look for duplicate entries in the judging_scores table
	$query_judging_duplicates = sprintf("SELECT eid FROM %s",$prefix."judging_scores");
	$judging_duplicates = mysqli_query($connection,$query_judging_duplicates) or die (mysqli_error($connection));
	$row_judging_duplicates = mysqli_fetch_assoc($judging_duplicates);
	$totalRows_judging_duplicates = mysqli_num_rows($judging_duplicates);

	if ($totalRows_judging_duplicates > 2) {

	do { $a[] = $row_judging_duplicates['eid']; } while ($row_judging_duplicates = mysqli_fetch_assoc($judging_duplicates));

		foreach ($a as $eid) {

			$query_duplicates = sprintf("SELECT id FROM %s WHERE eid='%s'",$prefix."judging_scores",$eid);
			$duplicates = mysqli_query($connection,$query_duplicates) or die (mysqli_error($connection));
			$row_duplicates = mysqli_fetch_assoc($duplicates);
			$totalRows_duplicates = mysqli_num_rows($duplicates);

			if ($totalRows_duplicates > 1) {

				for($i=1; $i<$totalRows_duplicates; $i++) {

					$query_duplicate = sprintf("SELECT id FROM %s WHERE eid='%s'",$prefix."judging_scores",$eid);
					$duplicate = mysqli_query($connection,$query_duplicate) or die (mysqli_error($connection));
					$row_duplicate = mysqli_fetch_assoc($duplicate);

					$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."judging_scores", $row_duplicate['id']);
					mysqli_real_escape_string($connection,$deleteSQL);
					$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
				}

			}
		}
	}

	if ($_SESSION['prefsAutoPurge'] == 1) {
		// Next, purge all entries that are unconfirmed
		purge_entries("unconfirmed", 1);
		purge_entries("special", 1);
	}

	// Last update the "system" table with the date/time the function ended
	$updateSQL = sprintf("UPDATE %s SET data_check=%s WHERE id='1'", $prefix."system", "NOW( )");
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

} // END function


function readable_number($a){

// http://www.iamcal.com/publish/articles/php/readable_numbers/

	$bits_a = array("thousand", "million", "billion", "trillion", "quadrillion");
	$bits_b = array("ten", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety");
	$bits_c = array("one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen");

	if ($a == 0){
		return 'zero';
	}

	$out = ($a < 0) ? 'minus ' : '';

	$a = abs($a);

	for($i=count($bits_a); $i>0; $i--) {
		$p = pow(1000, $i);
		if ($a > $p){
			$b = floor($a/$p);
			$a -= $p * $b;
			$out .= readable_number($b).' '.$bits_a[$i-1];
			$out .= (($a)?', ':'');
		}
	}

	if ($a > 100){
		$b = floor($a/100);
		$a -= 100 * $b;
		$out .= readable_number($b).' hundred'.(($a)?' and ':' ');
	}

	if ($a >= 20){
		$b = floor($a/10);
		$a -= 10 * $b;
		$out .= $bits_b[$b-1].' ';
	}

	if ($a) {
		$out .= $bits_c[$a-1];
	}

	return $out;
}

function winner_method($type,$output_type) {

	require(LANG.'language.lang.php');

	$output = "";

	if ($output_type == 1) {
		switch ($type) {
			case 0: $output = $label_by_table; break;
			case 1: $output = $label_by_category; break;
			case 3: $output = $label_by_subcategory; break;
		}
	}

	if ($output_type == 2) {
		switch ($type) {
			case 0: $output = sprintf("<p>%s</p>",$winners_text_002); break;
			case 1: $output = sprintf("<p>%s</p>",$winners_text_003); break;
			case 3: $output = sprintf("<p>%s</p>",$winners_text_004); break;
		}
	}

	return $output;
}


function table_exists($table_name) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	// taken from http://snippets.dzone.com/posts/show/3369
	$query_exists = "SHOW TABLES LIKE '".$table_name."'";
	$exists = mysqli_query($connection,$query_exists) or die (mysqli_error($connection));
	$totalRows_exists = mysqli_num_rows($exists);
	if ($totalRows_exists > 0) return TRUE;
	else return FALSE;
}


function table_assignments($uid,$method,$time_zone,$date_format,$time_format,$method2) {

	// Gather and output the judging or stewarding assignments for a user
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	if ($method2 == 2) {
		$output = array();
	}

	else {
		$output = "";
	}


	$query_table_assignments = sprintf("SELECT assignTable FROM %s WHERE bid='%s' AND assignment='%s'",$prefix."judging_assignments",$uid,$method);
	$table_assignments = mysqli_query($connection,$query_table_assignments) or die (mysqli_error($connection));
	$row_table_assignments = mysqli_fetch_assoc($table_assignments);
	$totalRows_table_assignments = mysqli_num_rows($table_assignments);

	if ($totalRows_table_assignments > 0) {

		do {
			$table_info = explode("^",get_table_info(1,"basic",$row_table_assignments['assignTable'],"default","default"));
			$location = explode("^",get_table_info($table_info[2],"location",$row_table_assignments['assignTable'],"default","default"));
			//$output .= "\t<table class='dataTableCompact' style='margin-left: -5px'>\n";

			if ($method2 == 0) {
				$output .= "\t\t<tr>\n";
				$output .= "\t\t\t<td class='dataList'>".$location[2]."</td>\n";
				$output .= "\t\t\t<td class='dataList'>".getTimeZoneDateTime($time_zone, $location[0], $date_format,  $time_format, "long", "date-time")."</td>\n";
				$output .= "\t\t\t<td class='dataList'>Table ".$table_info[0]." - ".$table_info[1]."</td>\n";
				$output .= "\t\t</tr>\n";
			}

			elseif ($method2 == 1) {
				if ($method == "J") $output .= "<a href='".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=judges&id=".$table_info[3]."' data-toggle=\"tooltip\" title='Assign/Unassign Judges to Table ".$table_info[0]." - ".$table_info[1]."'>".$table_info[0]."</a>,&nbsp;";
				if ($method == "S") $output .= "<a href='".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=stewards&id=".$table_info[3]."' data-toggle=\"tooltip\" title='Assign/Unassign Stewards to Table ".$table_info[0]." - ".$table_info[1]."'>".$table_info[0]."</a>,&nbsp;";

			}

			elseif ($method2 == 2) {
				$output[] = $table_info[3];
			}

			else {
				$output .= "\t\t\t<td class='dataList bdr1B'>".$location[2]."</td>\n";
				$output .= "\t\t\t<td class='dataList bdr1B'>".getTimeZoneDateTime($time_zone, $location[0], $date_format,  $time_format, "long", "date-time")."</td>\n";
				$output .= "\t\t\t<td class='dataList bdr1B'>Table ".$table_info[0]." - ".$table_info[1]."</td>\n";
				$output .= "\t\t</tr>\n";
			}

			//$output .= "\t</table>\n";

		} while ($row_table_assignments = mysqli_fetch_assoc($table_assignments));

	}

	//if (($totalRows_table_assignments == 0) && ($method2 == "1")) $output_extend = "No assignment(s)";
	if ($method2 == 2) $output = array_unique($output);
	return $output;
}

function available_at_location($location,$role,$round) {
	// Returns the number of judges available per location/date
	// Takes into account assignments in the judging_assignments table
	// and returns a total number available less those who have been
	// assigned to the location and round.
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	if ($role == "judges") $query_available = sprintf("SELECT brewerJudgeLocation FROM %s WHERE brewerJudgeLocation IS NOT NULL", $prefix."brewer");
	if ($role == "stewards") $query_available = sprintf("SELECT brewerStewardLocation FROM %s WHERE brewerStewardLocation IS NOT NULL", $prefix."brewer");
	$available = mysqli_query($connection,$query_available) or die (mysqli_error($connection));
	$row_available = mysqli_fetch_assoc($available);
	$totalRows_available = mysqli_num_rows($available);

	$return = "";

	do {
		if ($role == "judges") $available_location = explode(",",$row_available['brewerJudgeLocation']);
		if ($role == "stewards") $available_location =  explode(",",$row_available['brewerStewardLocation']);
		if (in_array("Y-".$location,$available_location)) $count[] = 1; else $count[] = 0;

		//$return .= $row_available['brewerJudgeLocation']."<br>";

	} while ($row_available = mysqli_fetch_assoc($available));

	$return = array_sum($count);
	//$return = print_r;
	return $return;
}

function str_osplit($string, $offset){
	return isset($string[$offset]) ? array(substr($string, 0, $offset), substr($string, $offset)) : false;
 }

function readable_judging_number($style,$number) {

	if (strlen($number) == 5) {
		$judging_number = str_osplit($number, 2);
		return sprintf("%06s",$judging_number[0]."-".$judging_number[1]);
	}

	if (strlen($number) == 4) {
		$judging_number = str_osplit($number, 1);
		return sprintf("%06s",$judging_number[0]."-".$judging_number[1]);
	}

	else {
		return sprintf("%06s",$number);
	}
}

function dropoff_location($input) {
	require(CONFIG.'config.php');
	require(LANG.'language.lang.php');
	mysqli_select_db($connection,$database);
	$query_dropoff = sprintf("SELECT dropLocationName FROM %s WHERE id='%s'",$prefix."drop_off",$input);
	$dropoff = mysqli_query($connection,$query_dropoff) or die (mysqli_error($connection));
	$row_dropoff = mysqli_fetch_assoc($dropoff);
	if ($input > 0)	return $row_dropoff['dropLocationName'];
	else return $label_shipping_entries;
}


function judge_steward_availability($input,$method,$prefix) {

	require(LANG.'language.lang.php');

	if (($input == "Y-") || ($input == "")) {
		if ($method == "1") $return = strtolower(ucfirst($label_no_availability));
		else $return = "";
	}
	else {

		$a = explode(",",$input);
		//$a = explode(",",$row_sql['brewerJudgeLocation']);

		$return = "";
		foreach ($a as $value) {
			$b = explode("-",$value);

			if ($b[0] == "Y") {
			require(CONFIG.'config.php');
			mysqli_select_db($connection,$database);
			$query_location = sprintf("SELECT judgingLocName FROM %s WHERE id='%s'", $prefix."judging_locations", $b[1]);
			$location = mysqli_query($connection,$query_location) or die (mysqli_error($connection));
			$row_location = mysqli_fetch_assoc($location);
				if (!empty($row_location['judgingLocName'])) {
					$return .= $row_location['judgingLocName']." ";
					if ($method == "1") $return .= "<br>";
					elseif ($method == "2") $return .= " | ";
					else $return .= " ";
				 }
				else $return .= "";
			}

		}

	}

	if ($method == "1")	return rtrim($return,"<br>");
	elseif ($method == "2") return rtrim($return," | ");
	else return $return;
}

function judge_entries($uid,$method) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_judge_entries = sprintf("SELECT brewStyle, brewCategory, brewSubCategory, brewCategorySort FROM %s WHERE brewBrewerID='%s' ORDER BY brewCategorySort ASC",$prefix."brewing",$uid);
	$judge_entries = mysqli_query($connection,$query_judge_entries) or die (mysqli_error($connection));
	$row_judge_entries = mysqli_fetch_assoc($judge_entries);
	$totalRows_judge_entries = mysqli_num_rows($judge_entries);

	if ($totalRows_judge_entries > 0) {
		do {

			if ($_SESSION['prefsStyleSet'] == "BA") {
				if ($method == 1) $entries[] = "<a href=\"".$base_url."index.php?section=admin&amp;go=entries&amp;filter=".$row_judge_entries['brewCategorySort']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View the ".$row_judge_entries['brewStyle']." Entries\">".$row_judge_entries['brewStyle']."</a>";
				else $entries[] = $row_judge_entries['brewStyle'];
			}

			else {
				if ($method == 1) $entries[] = "<a href=\"".$base_url."index.php?section=admin&amp;go=entries&amp;filter=".$row_judge_entries['brewCategorySort']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View the ".$row_judge_entries['brewStyle']." Entries\">".$row_judge_entries['brewCategory'].$row_judge_entries['brewSubCategory']."</a>";
				else $entries[] = $row_judge_entries['brewCategory'].$row_judge_entries['brewSubCategory'];
			}

		} while ($row_judge_entries = mysqli_fetch_assoc($judge_entries));
		$return = implode(", ",$entries);
		$return = rtrim($return,", ");
	}
	else $return = "";
	return $return;
}

function judging_winner_display($display_date) {
			if (time() > $display_date) return TRUE;
			else return FALSE;
	}

function format_phone_us($phone = '', $convert = true, $trim = true) {
	// If we have not entered a phone number just return empty
	if (empty($phone)) {
		return false;
	}

	// Strip out any extra characters that we do not need only keep letters and numbers
	$phone = preg_replace("/[^0-9A-Za-z]/", "", $phone);
	// Keep original phone in case of problems later on but without special characters
	$OriginalPhone = $phone;

	// If we have a number longer than 11 digits cut the string down to only 11
	// This is also only ran if we want to limit only to 11 characters
	if ($trim == true && strlen($phone)>11) {
		$phone = substr($phone, 0, 11);
	}

	// Do we want to convert phone numbers with letters to their number equivalent?
	// Samples are: 1-800-TERMINIX, 1-800-FLOWERS, 1-800-Petmeds
	if ($convert == true && !is_numeric($phone)) {
		$replace = array('2'=>array('a','b','c'),
						 '3'=>array('d','e','f'),
						 '4'=>array('g','h','i'),
						 '5'=>array('j','k','l'),
						 '6'=>array('m','n','o'),
						 '7'=>array('p','q','r','s'),
						 '8'=>array('t','u','v'),
						 '9'=>array('w','x','y','z'));

		// Replace each letter with a number
		// Notice this is case insensitive with the str_ireplace instead of str_replace
		foreach($replace as $digit=>$letters) {
			$phone = str_ireplace($letters, $digit, $phone);
		}
	}

	$length = strlen($phone);
	// Perform phone number formatting here
	switch ($length) {
		case 7:
			// Format: xxx-xxxx
			return preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "$1-$2", $phone);
		case 10:
			// Format: (xxx) xxx-xxxx
			return preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "($1) $2-$3", $phone);
		case 11:
			// Format: x(xxx) xxx-xxxx
			return preg_replace("/([0-9a-zA-Z]{1})([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "$1($2) $3-$4", $phone);
		default:
			// Return original phone if not 7, 10 or 11 digits long
			return $OriginalPhone;
	}
}

function check_judging_flights() {
	// Checks if the count of received entries is the same as the count in judging_flights table
	// If so, return TRUE
	// If not, return FALSE

	include (CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$query_check_tables = sprintf("SELECT COUNT(*) AS 'count' FROM %s", $prefix."judging_tables");
	$check_tables = mysqli_query($connection,$query_check_tables) or die (mysqli_error($connection));
	$row_check_tables = mysqli_fetch_assoc($check_tables);

	$query_check_received = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewReceived='1'", $prefix."brewing");
	$check_received = mysqli_query($connection,$query_check_received) or die (mysqli_error($connection));
	$row_check_received = mysqli_fetch_assoc($check_received);

	$query_check_flights = sprintf("SELECT COUNT(*) AS 'count' FROM %s", $prefix."judging_flights");
	$check_flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
	$row_check_flights = mysqli_fetch_assoc($check_flights);

	if (($row_check_received['count'] > 0) && ($row_check_flights['count'] > 0) && ($row_check_tables['count'] > 0) && ($row_check_received['count'] == $row_check_flights['count'])) return TRUE;
	if (($row_check_received['count'] > 0) && ($row_check_flights['count'] > 0) && ($row_check_tables['count'] > 0) && ($row_check_received['count'] != $row_check_flights['count'])) return FALSE;

}

function get_archive_count($table) {
	include (CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_archive_count = "SELECT COUNT(*) as 'count' FROM `$table`";
	$archive_count = mysqli_query($connection,$query_archive_count) or die (mysqli_error($connection));
	$row_archive_count = mysqli_fetch_assoc($archive_count);
	return $row_archive_count['count'];
}

function number_pad($number,$n) {
	return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
}


function open_or_closed($now,$date1,$date2) {

	$output = 0;

	if ((isset($date1)) && (isset($date2))) {

		// First date has not passed yet
		if ($now < $date1) $output = 0;

		// First date has passed, but second has not
		if (($now >= $date1) && ($now < $date2)) $output = 1;

		// Both dates have passed
		if ($now > $date2) $output = 2;

	}

	return $output;

}

function limit_subcategory($style,$pref_num,$pref_exception_sub_num,$pref_exception_sub_array,$uid) {

	/*
	$style = Style category and subcategory number
	$pref_num = Subcategory limit number from preferences
	$pref_exception_sub_num = The entry limit of EXCEPTED subcategories
	$pref_exception_sub_array = Array of EXCEPTED subcategories
	*/

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$style_break = explode("-",$style);

	$pref_exception_sub_array = explode(",",$pref_exception_sub_array);

	if ($style_break[0] <= 9) $style_num = sprintf('%02d',$style_break[0]);
	else $style_num = $style_break[0];

	$query_style = sprintf("SELECT id FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'",$prefix."styles",$_SESSION['prefsStyleSet'],$style_num,$style_break[1]);
	$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
	$row_style = mysqli_fetch_assoc($style);

	$style_id = $row_style['id'];

	// BA Styles
	if ($_SESSION['prefsStyleSet'] == "BA") {
		$query_check = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewSubCategory='%s'", $prefix."brewing", $uid, $style_break[1]);
		$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
		$row_check = mysqli_fetch_assoc($check);
	}

	// Others
	else {
		$query_check = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewCategorySort='%s' AND brewSubCategory='%s'", $prefix."brewing", $uid, $style_num, $style_break[1]);
		$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
		$row_check = mysqli_fetch_assoc($check);
	}

	if ($row_check['count'] >= $pref_num) $return = "DISABLED";
	else $return = "";

	// Check for exceptions
	if (($return == "DISABLED") && (!empty($pref_exception_sub_array))) {
		if (in_array($style_id,$pref_exception_sub_array)) {
			// if so, check if the amount in the DB is greater than or equal to the "excepted" limit number
			if ((!empty($pref_exception_sub_num)) && (($row_check['count'] >= $pref_exception_sub_num))) $return = "DISABLED";
			else $return = "";
		}
	}

	return $return;
}

function highlight_required($msg,$method,$style_version) {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$explodies = explode("-",$msg);

	if ($method == "0") { // mead cider sweetness

		$query_check = sprintf("SELECT brewStyleSweet FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles",$style_version,$explodies[1],$explodies[2]);
		$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
		$row_check = mysqli_fetch_assoc($check);
		$totalRows_check = mysqli_num_rows($check);

		if ($row_check['brewStyleSweet'] == 1) return TRUE;
		else return FALSE;

	}

	if ($method == "1") { // special ingredients REQUIRED beer/mead/cider

		$query_check = sprintf("SELECT brewStyleReqSpec FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles",$style_version,$explodies[1],$explodies[2]);
		$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
		$row_check = mysqli_fetch_assoc($check);

		if ($row_check['brewStyleReqSpec'] == 1) return TRUE;
		else return FALSE;

	}

	if ($method == "2") { // mead cider carb

		$query_check = sprintf("SELECT brewStyleCarb FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles",$style_version,$explodies[1],$explodies[2]);
		$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
		$row_check = mysqli_fetch_assoc($check);

		if ($row_check['brewStyleCarb'] == 1) return TRUE;
		else return FALSE;

	}

	if ($method == "3") { // mead strength

		$query_check = sprintf("SELECT brewStyleStrength FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles",$style_version,$explodies[1],$explodies[2]);
		$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
		$row_check = mysqli_fetch_assoc($check);

		if ($row_check['brewStyleStrength'] == 1) return TRUE;
		else return FALSE;

	}

}

function user_check($user_name) {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$query_userCheck = sprintf("SELECT * FROM %s WHERE user_name = '%s'",$prefix."users",$user_name);
	$userCheck = mysqli_query($connection,$query_userCheck) or die (mysqli_error($connection));
	$row_userCheck = mysqli_fetch_assoc($userCheck);
	$totalRows_userCheck = mysqli_num_rows($userCheck);
	//$return = $query_userCheck;
	$return = $totalRows_userCheck."^".$row_userCheck['userQuestion']."^".$row_userCheck['id'];

	return $return;

}

function judging_location_info($id) {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation,judgingTime FROM %s WHERE id='%s'", $prefix."judging_locations", $id);
	$judging_loc3 = mysqli_query($connection,$query_judging_loc3) or die (mysqli_error($connection));
	$row_judging_loc3 = mysqli_fetch_assoc($judging_loc3);
	$totalRows_judging_loc3 = mysqli_num_rows($judging_loc3);

	$return =
	$totalRows_judging_loc3."^". // 0
	$row_judging_loc3['judgingLocName']."^". // 1
	$row_judging_loc3['judgingDate']."^". // 2
	$row_judging_loc3['judgingLocation']."^". // 3
	$row_judging_loc3['judgingTime']; // 4

	return $return;

}

function yes_no($input,$base_url,$method=0) {
	require(LANG.'language.lang.php');
	$output = "";

	if ($method != 3) {

		if (($input == "Y") || ($input == 1)) {
			$output = "<span class=\"fa fa-lg fa-check text-success\"></span> ";
			if ($method == 0) $output = $label_yes;
		}

		else {
			$output .= "<span class=\"fa fa-lg fa-times text-danger\"></span> ";
			if ($method == 0) $output = $label_no;
		}

	}

	if ($method == 3) {

		if (($input == "Y") || ($input == 1)) $output = $label_yes;
		else $output = $label_no;

	}

	return $output;
}

function styles_active($method) {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	if ($method == 0) { // Active Styles

		$query_styles = sprintf("SELECT brewStyleGroup FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleActive='Y' ORDER BY brewStyleGroup ASC",$prefix."styles",$_SESSION['prefsStyleSet']);
		$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
		$row_styles = mysqli_fetch_assoc($styles);
		$totalRows_styles = mysqli_num_rows($styles);
		do { $a[] = $row_styles['brewStyleGroup']; } while ($row_styles = mysqli_fetch_assoc($styles));

		sort($a);
		return $a;
	}

	if ($method == 1) { // Style Types

		$query_style_types_active = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE styleTypeBOS='Y'", $prefix."style_types");
		$style_types_active = mysqli_query($connection,$query_style_types_active) or die (mysqli_error($connection));
		$row_style_types_active = mysqli_fetch_assoc($style_types_active);

		return $row_style_types_active['count'];

	}

	if ($method == 2) {

		$query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum,brewStyle FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum ASC",$prefix."styles",$_SESSION['prefsStyleSet']);
		$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
		$row_styles = mysqli_fetch_assoc($styles);
		$totalRows_styles = mysqli_num_rows($styles);
		do { $a[] = $row_styles['brewStyleGroup']."^".$row_styles['brewStyleNum']."^".$row_styles['brewStyle']; } while ($row_styles = mysqli_fetch_assoc($styles));

		return $a;

	}

}


function check_exension($file_ext) {

	switch($file_ext) {
		case "xml": return TRUE;
		break;

		case "":
		case NULL:
		return FALSE;
		break;

		default: return FALSE;
		break;
	}

}

function open_limit($total,$limit,$registration_open) {
	// Check to see if the limit of entries has been reached
	if ($limit != "") {
		if (($total >= $limit) && ($registration_open == "1")) return TRUE;
		else return FALSE;
	}
	else return FALSE;
}

// For hosted accounts on brewcompetition.com or brewcomp.com
function check_hosted_gh() {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$gh_user_name = "geoff@zkdigital.com";

	$query_gh_user = sprintf("SELECT * FROM %s WHERE user_name='%s'",$prefix."users",$gh_user_name);
	$gh_user = mysqli_query($connection,$query_gh_user) or die (mysqli_error($connection));
	$row_gh_user = mysqli_fetch_assoc($gh_user);
	$totalRows_gh_user = mysqli_num_rows($gh_user);

	$gh_password = "d9efb18ba2bc4a434ddf85013dbe58f8";
	$random1 = random_generator(7,2);
	$random2 = random_generator(7,2);
	require(CLASSES.'phpass/PasswordHash.php');
	$hasher = new PasswordHash(8, false);
	$hash = $hasher->HashPassword($gh_password);

	if (($totalRows_gh_user == 1) && ($row_gh_user['userLevel'] > 0)) {
		$updateSQL = sprintf("UPDATE %s SET userLevel='0' WHERE id='%s';",$prefix."users",$row_gh_user['id']);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	}

	if ($totalRows_gh_user == 0) {

		$sql = sprintf("INSERT INTO `%s` (`id`, `user_name`, `password`, `userLevel`, `userQuestion`, `userQuestionAnswer`,`userCreated`) VALUES (NULL, '%s', '%s', '0', '%s', '%s', NOW());", $prefix."users",$gh_user_name,$hash,$random1,$random2);
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

		$query_gh_user = sprintf("SELECT id FROM %s WHERE user_name='%s'",$prefix."users",$gh_user_name);
		$gh_user = mysqli_query($connection,$query_gh_user) or die (mysqli_error($connection));
		$row_gh_user = mysqli_fetch_assoc($gh_user);

		$sql = sprintf("INSERT INTO `%s` (`id`, `uid`, `brewerFirstName`, `brewerLastName`, `brewerAddress`, `brewerCity`, `brewerState`, `brewerZip`, `brewerCountry`, `brewerPhone1`, `brewerPhone2`, `brewerClubs`, `brewerEmail`, `brewerStaff`, `brewerSteward`, `brewerJudge`, `brewerJudgeID`, `brewerJudgeMead`, `brewerJudgeRank`, `brewerJudgeLikes`, `brewerJudgeDislikes`, `brewerJudgeLocation`, `brewerStewardLocation`, `brewerJudgeExp`, `brewerJudgeNotes`, `brewerAssignment`, `brewerJudgeWaiver`, `brewerDiscount`, `brewerJudgeBOS`, `brewerAHA`) VALUES	(NULL, %s, 'Geoff', 'Humphrey', '1234 Main Street', 'Anytown', 'CO', '80126', 'United States', '303-555-5555', '303-555-5555', NULL, '%s', 'N', 'N', 'N', 'A0000', NULL, 'Certified', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);",$prefix."brewer",$row_gh_user['id'],$gh_user_name);
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

	}

}

// Simple encrypt and decrypt
// Useful for URL passed strings that need to be obfuscated for *casual* users
// Thanks to https://bhoover.com/using-php-openssl_encrypt-openssl_decrypt-encrypt-decrypt-data/
// Thanks to http://markgoldsmith.me/blog/url-safe-php-encryption-and-decryption-script/

function obfuscateURL($data,$key) {

	$dirty = array("+", "/", "=");
	$clean = array("_p_", "_s_", "_e_");

	// Remove the base64 encoding from our key
	$encryption_key = base64_decode($key);
	if (function_exists(openssl_encrypt)) {
		// Generate an initialization vector
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

		// Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
		$encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);

		// The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
		$encrypted_data = base64_encode($encrypted . '::' . $iv);

		// Do a little clean up of stuff we don't want in URLs - just in case
		return str_replace($dirty, $clean, $encrypted_data);
	}

	// Use mcrypt if openssl not available; deprecated as of PHP 7.1
	elseif (function_exists(mcrypt_encrypt)) {
		$salt = "rdwhahb"; // should be the same as the $salt var in the deobfuscateURL function
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($salt), str_replace($clean, $dirty, $data), MCRYPT_MODE_CBC, md5(md5($salt))));
		return $encrypted;
	}

	// Fallback is simple obfuscation with base64 if allowed by function call params
	else {
		return str_replace($dirty, $clean, base64_encode($data));
	}

}

function deobfuscateURL($data,$key) {

	$dirty = array("+", "/", "=");
	$clean = array("_p_", "_s_", "_e_");

	// Remove the base64 encoding from our key
	$encryption_key = base64_decode($key);

	if (function_exists(openssl_encrypt)) {
		// To decrypt, split the encrypted data from our IV - our unique separator used was "::"
		// Get the data "dirty" again and remove base64 encoding
		list($encrypted_data, $iv) = explode('::', base64_decode(str_replace($clean, $dirty, $data)), 2);

		return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
	}

	elseif (function_exists(mcrypt_decrypt)) {
		$salt = "rdwhahb"; // should be the same as the $salt var in the encryptString function
		$decode = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($salt), base64_decode(str_replace($clean, $dirty, $data)), MCRYPT_MODE_CBC, md5(md5($salt))), "\0");
		return $decode;
	}

	else {
		return base64_decode(str_replace($clean, $dirty, $data));
	}

}

function get_ba_style_info($id) {

	$return = "";

	foreach ($_SESSION['styles'] as $styles => $stylesData) {

		if (is_array($stylesData) || is_object($stylesData)) {

			foreach ($stylesData as $key => $ba_style) {

				if ($ba_style['id'] === $id) {
					$return = $ba_style['name']."|";
					$return .= $ba_style['category']['id']."|";
					$return .= $ba_style['category']['name'];
					if (isset($ba_style['description'])) $return .= $ba_style['description']."|";
				}

			} // end foreach ($stylesData as $data => $ba_style)

		} // end if (is_array($stylesData) || is_object($stylesData))

	} // end foreach ($_SESSION['styles'] as $styles => $stylesData)

	return $return;
}


function convert_to_ba() {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	include (INCLUDES.'ba_constants.inc.php');

	$query_check = sprintf("SELECT id, brewCategory, brewCategorySort, brewSubCategory, brewStyle, brewMead1, brewMead2, brewMead3, brewInfo FROM %s", $prefix."brewing");
	$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
	$row_check = mysqli_fetch_assoc($check);

	$return = "";

	$carb = array("Still","Petillant","Sparkling");
	$sweet = array("Dry","Medium Dry","Medium","Medium Sweet","Sweet");
	$strength = array("Hydromel","Standard","Sack");

	do {

		$ba_category = "";
		$ba_category_sort = "";
		$ba_sub_category = "";
		$ba_carb = "";
		$ba_strength = "";
		$ba_sweetness = "";
		$ba_style = "";
		$ba_style_info = "";
		$ba_category_id = "";

		$query_ba_random = sprintf("SELECT * FROM %s WHERE brewStyleVersion='BA' ORDER BY RAND() LIMIT 1", $prefix."styles");
		$ba_random = mysqli_query($connection,$query_ba_random) or die (mysqli_error($connection));
		$row_ba_random = mysqli_fetch_assoc($ba_random);

		if ($row_ba_random['brewStyleReqSpec'] == 1) $brew_info = "Special ingredients, yo.";
		else $brew_info = "";

		$ba_category = ltrim($row_ba_random['brewStyleGroup'],"0");
		$ba_style = $row_ba_random['brewStyle'];
		$ba_sub_category = $row_ba_random['brewStyleNum'];

		if ($row_ba_random['brewStyleCarb'] == 1) $ba_carb = $carb[array_rand($carb)];
		if ($row_ba_random['brewStyleStrength'] == 1) $ba_strength = $strength[array_rand($strength)];
		if ($row_ba_random['brewStyleSweet'] == 1) $ba_sweetness = $sweet[array_rand($sweet)];

		$ba_category_sort = $row_ba_random['brewStyleGroup'];

		$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s', brewMead1='%s', brewMead2='%s', brewMead3='%s', brewInfo='%s' WHERE id=%s;",$prefix."brewing",$ba_category,$ba_category_sort,$ba_sub_category,$ba_style,$ba_carb,$ba_sweetness,$ba_strength,$brew_info,$row_check['id']);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		//$return .= $updateSQL."<br>";

		//$return .= $ba_style_info."<br>";

	} while ($row_check = mysqli_fetch_assoc($check));

	// Change preference
	session_start();
	$_SESSION['prefsStyleSet'] = "BA";

	$updateSQL = sprintf("UPDATE %s SET brewStyleActive='Y' WHERE brewStyleVersion='BA';",$prefix."brewing");
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	return $return;

}

//convert_to_ba();

function convert_to_pro() {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	include (INCLUDES.'ba_constants.inc.php');

	$query_check = sprintf("SELECT id FROM %s", $prefix."brewer");
	$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
	$row_check = mysqli_fetch_assoc($check);

	$return = "";

	$breweries = array(
"10 Barrel Brewing Company","105 West Brewing Company","12 Degree Brewing","14er Brewing Company","3 Freaks Brewery","300 Suns Brewing","38 State Brewing Company","4 Noses Brewing Company","4B&rsquo;s Brewery","7 Hermits Brewing Company","Alpine Dog Brewing Company","Anheuser-Busch","Animas Brewing Company","Asher Brewing Company","Aspen Brewing Company","Avalanche Brewing Company","Avery Brewing Company","Backcountry Brewery","Baere Brewing Company","Banded Oak Brewing Company","Barnett &amp; Son Brewing Company","Barrels &amp; Bottles Brewery","Beer By Design","Berthoud Brewing Company","Beryl&rsquo;s Beer Company","Bierstadt Lagerhaus","BierWerks Brewery","Big Beaver Brewing Company","Big Thompson Brewery","BJ&rsquo;s Restaurant &amp; Brewery ","Black Bottle Brewery","Black Project Spontaneous &amp; Wild Ales","Black Shirt Brewing Company","Black Sky Brewery","Blue Moon Brewing Co. at the Sandlot","Blue Moon Brewing Company","Blue Spruce Brewing Company","Boggy Draw Brewery","Bonfire Brewing","Bootstrap Brewing Company","Bottom Shelf Brewery"," Beer Company"," Brewery"," Brewery &amp; Pub","BREW Pub &amp; Kitchen","Brewability Lab","Brewery Rickoli","Briar Common Brewery + Eatery","Bristol Brewing Company","Brix Taphouse &amp; Brewery","Broken Compass Brewery","Broken Plow Brewery","BRU Handbuilt Ales &amp; Eats","Brues Alehouse Brewing Company","Bruz Beers","Buckhorn Brewers","Bull &amp; Bush Pub &amp; Brewery","Butcherknife Brewing Company","Call to Arms Brewing Company","Cannonball Creek Brewing Company","Capitol Creek Brewery","Carbondale Beer Works","Carver Brewing Company","Casey Brewing &amp; Blending"," Beer Company","CAUTION: Brewing Company","CB &amp; Potts Restaurant &amp; Brewery Englewood","CB &amp; Potts Restaurant &amp; Brewery Flatirons","CB &amp; Potts Restaurant &amp; Brewery ","CB &amp; Potts Restaurant &amp; Brewery ","CB &amp; Potts Restaurant &amp; Brewery ","Cellar West Artisan Ales","Cerberus Brewing Company","Cerebral Brewing","Chain Reaction Brewing","Cheluna Brewing Company","City Star Brewing","CO-Brew","Cogstone Brewing Company","Colorado Boy Pizzeria &amp; Brewery","Colorado Boy Pub &amp; Brewery","Colorado Mountain Brewery","Colorado Mountain Brewery at the Roundhouse","Colorado Plus Brew Pub","Comrade Brewing Company","CooperSmith&rsquo;s Pub &amp; Brewing Company","Copper Club Brewing Company","Copper Kettle Brewing Company","Crabtree Brewing Company","Crazy Mountain Brewing Company","Crazy Mountain Tap Room","Creede Brewing Company","Crestone Brewing Company","Crooked Stave Artisan Beer Project","Crow Hop Brewing Company","Crystal Springs Brewing Company","Dad &amp; Dude&rsquo;s Breweria","DC Oakes Brewhouse and Eatery","De Steeg Brewing","Dead Hippie Brewing","Declaration Brewing","Deep Draft Brewing Company","Arvada Beer Company","Diebolt Brewing Company","Dillon DAM Brewery","Dodgeton Creek Brewing Company","Dolores River Brewery","Dostal Alley Brewpub &amp; Casino","Dry Dock Brewing Company North","Dry Dock Brewing Company South","Echo Brewing Cask and Barrel","Echo Brewing Company","Eddyline Brewery","El Rancho Brewing Company","Elevation Beer Company","Elk Mountain Brewing Company","Epic Brewing Company","Equinox Brewing Company","Estes Park Brewery","Evergreen Tap House &amp; Brewery","Factotum Brewhouse","FATE Brewing Company","FERMÆNTRA","Fiction Beer Company","Fieldhouse Brewing Company","Finkel &amp; Garf Brewing Company","Floodstage Ale Works","Florence Brewing Company","Fossil Craft Beer Company","Front Range Brewing Company","Funkwerks","Gilded Goat Brewing","Glenwood Canyon Brewing Company","Gold Camp Brewing Company","Goldspot Brewing Company","Gordon Biersch Brewery","Gore Range Brewery","Grand Lake Brewing Tavern","Grandma&rsquo;s House","Gravity Brewing","Great Divide Brewing Company","Great Frontier Brewing Company","Great Storm Brewing","Green Mountain Beer Company","Grimm Brothers Brewhouse Taproom","Grist Brewing Company","Grist Brewing Company Lab","Großen Bart Brewery","Guanella Pass Brewing Company","Gunbarrel Brewing Company","Halfpenny Brewing Company","Hideaway Park Brewery","High Alpine Brewing Company","High Hops Brewery","Hogshead Brewery","Holidaily Brewing Company","Horse and Dragon Brewing Company","Horsefly Brewing Company","Intersect Brewing","Iron Bird Brewing Company","Ironworks Brewery &amp; Pub","J Wells Brewery","J. Fargo&rsquo;s Family Dining &amp; Micro Brewery","Jagged Mountain Craft Brewery","JAKs Brewing Company","James Peak Brewery &amp; Smokehouse","Jessup Farm Barrel House","Joyride Brewing Company","Kannah Creek Brewing Company","Kettle and Spoke Brewery","Kokopelli Beer Company","LandLocked Ales","Lariat Lodge Brewing","Launch Pad Brewery","Left Hand Brewing Company","Liquid Mechanics Brewing Company","Little Machine Beer","Living The Dream Brewing Company","Local Relic","Locavore Beer Works","Lone Tree Brewing Company","Lost Highway Brewing Company","Lowdown Brewery + Kitchen","Lumpy Ridge Brewing Company","Mad Jack&rsquo;s Mountain Brewery","Mahogany Ridge Brewery and Grill","Main Street Brewery &amp; Restaurant","Mancos Brewing Company","Manitou Brewing Company","Mash Lab Brewing","Maxline Brewing","McClellan&rsquo;s Brewing Company","MillerCoors Brewing Company","Mockery Brewing","Moffat Station Restaurant and Brewery","Moonlight Pizza &amp; Brewery","Mother Tucker Brewery","Mountain Sun Pub &amp; Brewery","Mountain Tap Brewery","Mountain Toad Brewing","Nano 108 Brewing Company","Never Summer Brewing Company","New Belgium Brewing Company","New Image Brewing Company","New Terrain Brewing Company","Nighthawk Brewery","Odd13 Brewing","Odell Brewing Company","Odyssey Beerwerks","Old Colorado Brewing Company","Open Door Brewing Company","Oskar Blues Grill &amp; Brew","Oskar Blues Tasty Weasel Tap Room (Main Brewery)","Our Mutual Friend Brewing Company","Ouray Brewery","Ourayle House Brewery (Mr. Grumpy Pants)","Outer Range Brewing Company","Pagosa Brewing Company","Palisade Brewing Company","Paradox Beer Company","Parts and Labor Brewing","PDub Brewing Company","Peak to Peak Tap &amp; Brew","Peaks N Pines Brewing Company","Periodic Brewing","Phantom Canyon Brewing Company","Pikes Peak Brewing Company","Pints Pub Brewery &amp; Freehouse","Pitchers Sports Restaurant","Platt Park Brewing Company","Powder Keg Brewing Company","Prost Brewing Company","Pug Ryan&rsquo;s Brewery","Pumphouse Brewery","Rails End Beer Company","Rally King Brewing","Ratio Beerworks","Red Leg Brewing Company","Renegade Brewing Company","Resolute Brewing Company","Revolution Brewing","Riff Raff Brewing Company","River North Brewery","Roaring Fork Beer Company","Rock Bottom Brewery ","Rock Cut Brewing Company","Rockslide Brewery &amp; Restaurant","Rocky Mountain Brewery","Rockyard American Grill &amp; Brewing Company","Royal Gorge Brewing Company","Saint Patrick&rsquo;s Brewing Company","San Luis Valley Brewing Company","Sanitas Brewing Company","Seedstock Brewery","Shamrock Brewing Company","Shine Brewing Company","Shoes &amp; Brews","Ska Brewing Company","SKEYE Brewing","Smiling Toad Brewery","Smugglers Brewpub","Snowbank Brewing","SomePlace Else Brewery","Something Brewery","Soulcraft Brewing","South Park Brewing","Southern Sun Pub &amp; Brewery","Spangalang Brewery","Spice Trade Brewing Company","Square Peg Brewerks","Station 26 Brewing Company","Steamworks Brewing Company","Storm Peak Brewing Company","Storybook Brewing","Strange Craft Beer Company","Suds Brothers Brewery II","Telluride Brewing Company","The Bakers&rsquo; Brewery","The Brew on Broadway","The Eldo Brewery &amp; Taproom","The Industrial Revolution Brewing Company","The Intrepid Sojourner Beer Project","The Peak Bistro &amp; Brewery","The Post Brewing Company","Three Barrel Brewing Company","Three Four Beer Company","Tivoli Brewing Company","Tommyknocker Brewery &amp; Pub","Trinity Brewing Company","Triple S Brewing Company","TRVE Brewing Company","Twisted Pine Brewing Company","Two Rascals Brewing","Two22 Brew","Upslope Brewing Company","Ursula Brewery","Ute Pass Brewing Company","UTurn BBQ","Vail Brewing Company","Verboten Brewing","Very Nice Brewing Company","Veteran Brothers Brewing Company","Vindication Brewing Company","Vine Street Pub &amp; Brewery","Vision Quest Brewing Company","Walter Brewing Company","WeldWerks Brewing Company","West Flanders Brewing Company","Westbound &amp; Down Brewing Company","WestFax Brewing Company"," Brewing Company","Whistle Pig Brewing Company","White Labs Tasting Room","Wibby Brewing","Wild Woods Brewery","WildEdge Brewing Collective","Wiley Roots Brewing Company","Wit&rsquo;s End Brewing Company","Wolfe Brewing Company","Wonderland Brewing Company","Wynkoop Brewing Company","Yampa Valley Brewing Company","Zephyr Brewing Company","Zuni Street Brewing Company","Zwei Brewing"
	);

	do {

		$key = (array_rand($breweries,1));
		$value = $breweries[$key];

		$query_check_brewery = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewerBreweryName='%s'", $prefix."brewer",$value);
		$check_brewery = mysqli_query($connection,$query_check_brewery) or die (mysqli_error($connection));
		$row_check_brewery = mysqli_fetch_assoc($check_brewery);

		$update = TRUE;

		//while ($update) {

			if ($row_check_brewery['count'] > 0) {
				$update = TRUE;
			}

			else {
				$update = FALSE;
				$updateSQL = sprintf("UPDATE %s SET brewerBreweryName='%s' WHERE id=%s;",$prefix."brewer",$value,$row_check['id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}

		//}

	// $return .= $updateSQL."<br>";

	} while ($row_check = mysqli_fetch_assoc($check));

	return $return;

}

function remove_sensitive_data() {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	include (INCLUDES.'constants.inc.php');

	$result = "";

	$first_name_array = array(
	"Herbert",
	"John",
	"Kvothe",
	"Jeoffry",
	"Cersi",
	"Danne",
	"Bruce",
	"Wade",
	"Marnie",
	"Dan",
	"Chandler",
	"Mario",
	"Michelle",
	"Kjell",
	"Clark",
	"Gerion",
	"Rudolph",
	"Albert",
	"Justin",
	"Jon",
	"Toby",
	"William",
	"Christian",
	"Weston",
	"Zak",
	"Neil",
	"Tyson"
	);

	$last_name_array = array(
	"Herbert",
	"Johnson",
	"Hull",
	"Havens",
	"Palmer",
	"Lannister",
	"Black",
	"Snow",
	"Wilson",
	"Payne",
	"Chandler",
	"Gutierrez",
	"Jones",
	"Carlton",
	"Clark",
	"Gerion",
	"Rudolph",
	"Albertson",
	"Ziegler",
	"Bartlett",
	"Watson",
	"Williams",
	"Potter",
	"Jones",
	"Owens",
	"O&rsquo;Neil",
	"Wainright",
	"Bennett",
	"Humboldt",
	"Gould",
	"Frasier"
	);

	$query_check_user = sprintf("SELECT * FROM %s", $prefix."users");
	$check_user = mysqli_query($connection,$query_check_user) or die (mysqli_error($connection));
	$row_check_user = mysqli_fetch_assoc($check_user);

	$user_array = "";

	do {
			if ($row_check_user['userLevel'] > 1) {
			$random = random_generator(7,2);
			$updateSQL = sprintf("UPDATE %s
						 SET
						 user_name='prost@brewcompetition.com',
						 password='f52dde34d49c8d69ab7fa5ee9ca13c72',
						 userQuestion='Randomly generated.',
						 userQuestionAnswer='%s'
						 WHERE id='%s'",  $prefix."users", $random, $row_check_user['id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			$user_array[] = $row_check_user['id'];
			}
	} while ($row_check_brewer = mysqli_fetch_assoc($check_brewer));

	$query_check_brewer = sprintf("SELECT * FROM %s", $prefix."brewer");
	$check_brewer = mysqli_query($connection,$query_check_brewer) or die (mysqli_error($connection));
	$row_check_brewer = mysqli_fetch_assoc($check_brewer);

	do {

			if ((is_array($user_array)) && (in_array($row_check_brewer['uid'],$user_array))) {

				$update = TRUE;

				while($update) {

					$first_name_key = (array_rand($first_name_array,1));
					$first_name = $first_name_array[$first_name_key];

					$last_name_key = (array_rand($last_name_array,1));
					$last_name = $last_name_array[$last_name_key];

					$club_name = "";
					if (!empty($row_check_brewer['brewerClubs'])) {
						 $club_name_key = (array_rand($club_array,1));
						 $club_name_1 = $club_array[$club_name_key];
						 $club_name_2 = explode("|",$club_name_1);
						 $club_name = $club_name_2[0];
					}

					$query_check_name = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewerFirstName='%s' AND brewerLastName='%s'", $prefix."brewer", $first_name, $last_name);
					$check_name = mysqli_query($connection,$query_check_name) or die (mysqli_error($connection));
					$row_check_name = mysqli_fetch_assoc($check_name);

					if ($row_check_name['count'] > 0) {
						$update = TRUE;
					}

					else {
						$update = FALSE;
						$updateSQL = sprintf("UPDATE %s
							 SET
							 brewerFirstName='%s',
							 brewerLastName='%s',
							 brewerAddress='1234 Main Street',
							 brewerCity='Anytown',
							 brewerState='CO',
							 brewerZip='',
							 brewerPhone1='303-555-1234',
							 brewerPhone2='303-555-9876',
							 brewerEmail='prost@brewcompetition.com',
							 brewerJudgeID='A0000',
							 brewerClubs='%s'
							 WHERE id='%s'", $prefix."brewer", $first_name, $last_name, $club_name, $row_check_brewer['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					}

				}

				// $result .= $updateSQL."<br>";
			}
	} while ($row_check_brewer = mysqli_fetch_assoc($check_brewer));

	return $result;

}

function verify_token($token,$time) {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	// Token is not valid by default
	$return = 1;

	$query_check_user = sprintf("SELECT userToken, userTokenTime FROM %s WHERE userToken='%s'", $prefix."users", $token);
	$check_user = mysqli_query($connection,$query_check_user) or die (mysqli_error($connection));
	$row_check_user = mysqli_fetch_assoc($check_user);
	$totalRows_check_user = mysqli_num_rows($check_user);

	if ($totalRows_check_user == 1) {

		// Give the user 24 hours to reset their password
		if (isset($row_check_user['userTokenTime'])) $expired_time = ($row_check_user['userTokenTime'] + 86400);

		// If the token time wasn't recorded for some reason, default to 4 hours
		else $expired_time = ($time + 14400);

		// If within the prescribed timeframe, valid
		if ($time <= $expired_time) $return = 0;

		// Otherwise, expired
		else $return = 2;

	}

	return $return;

}

// Moved from logincheck.inc.php
// Clean the data collected in the <form>

function sterilize ($sterilize = NULL) {

	if ($sterilize == NULL) {
		return NULL;
	}

	$sterilize = trim($sterilize);

	if (is_numeric($sterilize)) {
		if (is_float($sterilize)) $sterilize = filter_var($sterilize,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		if (is_int($sterilize)) $sterilize = filter_var($sterilize,FILTER_SANITIZE_NUMBER_INT);
	}

	else $sterilize = filter_var($sterilize,FILTER_SANITIZE_STRING);

	$sterilize = strip_tags($sterilize);
	$sterilize = stripcslashes($sterilize);
	$sterilize = stripslashes($sterilize);
	$sterilize = addslashes($sterilize);

	return $sterilize;
}

function tiebreak_rule($rule) {

	require (LANG.'language.lang.php');

	switch ($rule) {
		case "TBTotalPlaces" :
			$return = $best_brewer_text_006;
			break;
		case "TBTotalExtendedPlaces" :
			$return = $best_brewer_text_007;
			break;
		case "TBFirstPlaces" :
			$return = $best_brewer_text_008;
			break;
		case "TBNumEntries" :
			$return = $best_brewer_text_009;
			break;
		case "TBMinScore" :
			$return = $best_brewer_text_010;
			break;
		case "TBMaxScore" :
			$return = $best_brewer_text_011;
			break;
		case "TBAvgScore" :
			$return = $best_brewer_text_012;
			break;
		default:
			$return = $best_brewer_text_013;
			break;
	}

	return $return;
}

if (!function_exists('mime_content_type')) {

	function mime_content_type($filename) {

		$mime_types = array(
			'txt' => 'text/plain',
			'png' => 'image/png',
			'jpe' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg' => 'image/jpeg',
			'gif' => 'image/gif',
			'bmp' => 'image/bmp',
			'ico' => 'image/vnd.microsoft.icon',
			'tiff' => 'image/tiff',
			'tif' => 'image/tiff',
			'svg' => 'image/svg+xml',
			'svgz' => 'image/svg+xml',
			'pdf' => 'application/pdf',
			/*
			'doc' => 'application/msword',
			'docx' => 'application/msword',
			'rtf' => 'application/rtf',
			'xls' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',
			'odt' => 'application/vnd.oasis.opendocument.text',
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			'zip' => 'application/zip',
			'rar' => 'application/x-rar-compressed',
			'exe' => 'application/x-msdownload',
			'msi' => 'application/x-msdownload',
			'cab' => 'application/vnd.ms-cab-compressed',
			'mp3' => 'audio/mpeg',
			'qt' => 'video/quicktime',
			'mov' => 'video/quicktime',
			'htm' => 'text/html',
			'html' => 'text/html',
			'php' => 'text/html',
			'css' => 'text/css',
			'js' => 'application/javascript',
			'json' => 'application/json',
			'xml' => 'application/xml',
			'swf' => 'application/x-shockwave-flash',
			'flv' => 'video/x-flv',
			'psd' => 'image/vnd.adobe.photoshop',
			'ai' => 'application/postscript',
			'eps' => 'application/postscript',
			'ps' => 'application/postscript',
			*/
		);

		$ext = strtolower(array_pop(explode('.',$filename)));

		if (array_key_exists($ext, $mime_types)) {
			return $mime_types[$ext];
		}

		elseif (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME);
			$mimetype = finfo_file($finfo, $filename);
			finfo_close($finfo);
			return $mimetype;
		}

		else {
			return 'application/octet-stream';
		}

	}

}

function is_dir_empty($dir) {
	foreach (new DirectoryIterator($dir) as $fileInfo) {
		if($fileInfo->isDot()) continue;
		return false;
	}
	return true;
}

function pro_am_check($uid) {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$query_check_proam = sprintf("SELECT brewerProAm FROM %s WHERE uid='%s'", $prefix."brewer", $uid);
	$check_proam = mysqli_query($connection,$query_check_proam) or die (mysqli_error($connection));
	$row_check_proam = mysqli_fetch_assoc($check_proam);

	return $row_check_proam['brewerProAm'];

}

?>