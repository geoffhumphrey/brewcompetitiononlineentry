<?php
/**
 * Module:      common.inc.php
 * Description: This module houses all site-wide function definitions. If a function
 *              or variable is called from multiple modules, it is housed here.
 *
 */

include (INCLUDES.'date_time.inc.php');

// ------------------ VERSION CHECK ------------------  
// Current version is 1.3.0.3, change version in system table if not
// There are NO database structure or data updates for version 1.3.0.3
// USE THIS FUNCTION ONLY IF THERE ARE *NOT* ANY DB TABLE OR DATA UPDATES
// OTHERWISE, DEFINE/UPDATE THE VERSION VIA THE UPDATE PROCEDURE

include(INCLUDES.'version.inc.php');
function version_check($version) {
	
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	if ($version != "1.3.0.3") {
		$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='%s'",$prefix."system","1.3.0.3","2013-10-25","1");
		mysql_select_db($database, $brewing);
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	}
}
	
version_check($version);

// ---------------------------------------------------  

function in_string($haystack,$needle) {
	if (strpos($haystack,$needle) !== false) return TRUE;
}

function designations($judge_array,$display) {
				
	$return = "";
	$rank1 = explode(",",$judge_array);
	foreach ($rank1 as $rank2) {
		 if ($rank2 != $display) $return .= "<br />".$rank2."";
		 else $return .= "";
	}
	return $return;	
}

function build_action_link($icon,$base_url,$section,$go,$action,$filter,$id,$dbTable,$alt_title,$method=0,$link_text="default") {

	$return = "";
	$return .= "<span class='icon'>";
	
	if (($method == 1) || ($method == 2)) {
		$return .= "<img src='".$base_url."images/".$icon.".png' border='0' alt='".$alt_title."' title='".$alt_title."'>&nbsp;"; 
	}

	if ($icon == "bin_closed") {
		$return .= "<a href=\"javascript:DelWithCon('includes/process.inc.php?section=".$section."&amp;dbTable=".$dbTable."&amp;action=".$action."','id',".$id.",'".$alt_title."');\" title=\"".$alt_title."\">";
	}

	else {
		
		if ($method == 2) { // print form link
			$return .= "<a id='modal_window_link' href='".$base_url."output/entry.php?";
			$return .= "id=".$id;
			$return .= "&amp;bid=".$section;
			$return .= "' title='".$alt_title."'>";	
		} 
		
		else {
			$return .= "<a href='".$base_url."index.php?section=".$section;
			if ($go != "default") $return .= "&amp;go=".$go;
			if ($action != "default") $return .= "&amp;action=".$action;
			if ($filter != "default") $return .= "&amp;filter=".$filter;
			if ($id != "default") $return .= "&amp;id=".$id;
			$return .= "' title='".$alt_title."'>";	
		}
	}
	
	if (($method == 1) || ($method == 2)) {
		$return .= $link_text;
		$return .= "</a>";
		$return .= "</span>";
	}
	
	else {
		$return .= "<img src='".$base_url."images/".$icon.".png' border='0' alt='".$alt_title."' title='".$alt_title."'>"; 
		$return .= "</a>";
		$return .= "</span>";
	}

	return $return;
}

function build_output_link($icon,$base_url,$filename,$section,$go,$action,$filter,$id,$dbTable,$alt_title,$modal_window) {

	$return = "";
	
	$return .= "<a href='".$base_url."output/".$filename."?section=".$section;
	if ($go != "default") $return .= "&amp;go=".$go;
	if ($action != "default") $return .= "&amp;action=".$action;
	if ($filter != "default") $return .= "&amp;filter=".$filter;
	if ($id != "default") $return .= "&amp;id=".$id;
	$return .= "' title='".$alt_title."'";
	if ($modal_window) $return .= " id='modal_window_link'";
	$return .= ">";	
	$return .= "<img src='".$base_url."images/".$icon.".png' border='0' alt='".$alt_title."' title='".$alt_title."'></a>";
	$return .= "</span>";

	return $return;
}



function build_form_action($base_url,$section,$go,$action,$filter,$id,$dbTable,$check_reqired) {
	$return = "";
	$return .= "<form method='post' id='form1' name='form1' action='".$base_url."includes/process.inc.php?section=".$section."&amp;dbTable=".$dbTable;
	if ($go != "default") $return .= "&amp;go=".$go;
	if ($action != "default") $return .= "&amp;action=".$action;
	if ($filter != "default") $return .= "&amp;filter=".$filter;
	if ($id != "default") $return .= "&amp;id=".$id;
	$return .= "'";
	if ($check_reqired) $return .= " onsubmit='return CheckRequiredFields()'";
	$return .= ">";
	
	return $return;
}

function build_public_url($section="default",$go="default",$action="default",$sef,$base_url) {
	
	include(CONFIG.'config.php');
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
			return rtrim($url,"/");		
		}
		if ($sef == "false") {
			$url = $base_url."index.php?section=".$section;
			if ($go != "default") $url .= "&amp;go=".$go;
			if ($action != "default") $url .= "&amp;action=".$action;
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
	if ($method == "2") $a .= ", ";
	if ($method == "1") $a .= "";
	if ($method == "3") $a .= ",";
  	}
	$b = rtrim($a, ",&nbsp;");
 	return $b;
}

function addOrdinalNumberSuffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
      }
    }
    return $num.'th';
  }

function purge_entries($type, $interval) {
	
	require(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	
	if ($type == "unconfirmed") {
		$query_check = sprintf("SELECT id FROM %s WHERE brewConfirmed='0'", $prefix."brewing");
		if ($interval > 0) $query_check .= " AND brewUpdated < DATE_SUB( NOW(), INTERVAL 1 DAY)";
		$check = mysql_query($query_check, $brewing) or die(mysql_error());
		$row_check = mysql_fetch_assoc($check);
		
		do { $a[] = $row_check['id']; } while ($row_check = mysql_fetch_assoc($check));
		
		foreach ($a as $id) {
			$deleteEntries = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $id);
			mysql_select_db($database, $brewing);
			$result = mysql_query($deleteEntries, $brewing) or die(mysql_error()); 
		}
	}
	
	if ($type == "special") {
		$query_check = sprintf("SELECT id,brewInfo FROM %s WHERE (brewInfo IS NULL OR brewInfo='') AND (
						(brewCategorySort = '06' AND brewSubCategory = 'D') OR 
						(brewCategorySort = '16' AND brewSubCategory = 'E') OR 
						(brewCategorySort = '17' AND brewSubCategory = 'F') OR 
						(brewCategorySort = '20' AND brewSubCategory = 'A') OR 
						(brewCategorySort = '21' AND brewSubCategory = 'A') OR 
						(brewCategorySort = '21' AND brewSubCategory = 'B') OR 
						(brewCategorySort = '22' AND brewSubCategory = 'C') OR 
						(brewCategorySort = '23' AND brewSubCategory = 'A') OR 
						(brewCategorySort = '25' AND brewSubCategory = 'C') OR 
						(brewCategorySort = '26' AND brewSubCategory = 'A') OR 
						(brewCategorySort = '26' AND brewSubCategory = 'C') OR 
						(brewCategorySort = '27' AND brewSubCategory = 'E') OR 
						(brewCategorySort = '28' AND brewSubCategory = 'B') OR
						(brewCategorySort = '28' AND brewSubCategory = 'C') OR 
						(brewCategorySort = '28' AND brewSubCategory = 'D') OR 
						brewCategorySort >  '28')", 
						$prefix."brewing");
		if ($interval > 0) $query_check .=" AND brewUpdated < DATE_SUB( NOW(), INTERVAL 1 DAY)";
		
		$check = mysql_query($query_check, $brewing) or die(mysql_error());
		$row_check = mysql_fetch_assoc($check);
		
		do { 
				if ($row_check['brewInfo'] == "") {
					$deleteEntries = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $row_check['id']);
					mysql_select_db($database, $brewing);
					$result = mysql_query($deleteEntries, $brewing) or die(mysql_error()); 
				}
		} while ($row_check = mysql_fetch_assoc($check));
	}
}

// function to generate random number
function random_generator($digits,$method){
	srand ((double) microtime() * 10000000);

	//Array of alphabet
	if ($method == "1") $input = array ("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9");
	if ($method == "2") $input = array ("1","2","3","4","5","6","7","8","9");
	if ($method == "3") $input = array ("1","2","3","4");

	$random_generator = "";// Initialize the string to store random numbers
	for ($i=1;$i<$digits+1;$i++) { // Loop the number of times of required digits
		if(rand(1,2) == 1){ // to decide the digit should be numeric or alphabet
		// Add one random alphabet 
		$rand_index = array_rand($input);
		$random_generator .=$input[$rand_index]; // One char is added
		}
		
		if ($method == "3")
		{
		// Add one numeric digit between 1 and 10
		$random_generator = rand(1,4); // one number is added
		}
		
		else {
		// Add one numeric digit between 1 and 10
		$random_generator .=rand(1,10); // one number is added
		} // end of if else
	} // end of for loop 

	return $random_generator;
} // end of function

function relocate($referer,$page,$msg,$id) {
	
	include(CONFIG.'config.php');
	
	// Break URL into an array
	$parts = parse_url($referer);
	$referer = $parts['query'];	
	
	// Remove $msg=X from query string
	$pattern = array("/[0-9]/", "/&msg=/");
	$referer = preg_replace($pattern, "", $referer);

	// Remove $id=X from query string
	$pattern = array("/[0-9]/", "/&id=/");
	$referer = preg_replace($pattern, "", $referer);
	
	// Remove $pg=X from query string and add back in
	if ($page != "default") { 
		$pattern = array("/[0-9]/", "/&pg=/");
		$referer = str_replace($pattern,"",$referer);
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
	mysql_select_db($database, $brewing);
	$query_check = sprintf("SELECT COUNT(*) as count FROM %s WHERE brewJudgingNumber IS NULL", $prefix."brewing");
	$check = mysql_query($query_check, $brewing) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	if ($row_check['count'] == 0) return true; else return false;
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

// ---------------------------- Date -----------------------------------------
// All date functions moved to date_time.inc.php

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

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
  }
  return $theValue;
}

function currency_info($input,$method) {
	
	if ($method == 1) {
		
		switch ($input) {
			case "&pound;": $currency_code = $input."^GBP";
			break;
			case "&euro;": $currency_code = $input."^EUR";
			break;
			case "&yen;": $currency_code = $input."^JPY";
			break;
			case "C$": $currency_code = "$^CAD";
			break;
			case "kr": $currency_code = $input."^DKK";
			break;
			case "&#8356;": $currency_code = $input."^TRY";
			break;
			case "R$": $currency_code = "R$^BRL";
			break;
			case "A$": $currency_code = "$^AUD";
			break;
			case "N$": $currency_code = "$^NZD";
			break;
			case "H$": $currency_code = "$^HKD";
			break;
			case "S$": $currency_code = "$^SGD";
			break;
			case "skr": $currency_code = "kr^SEK";
			break;
			case "z&#322;": $currency_code = $input."^PLN";
			break;
			case "nkr": $currency_code = "kr^NOK";
			break;
			case "Ft": $currency_code = $input."^HUF";
			break;
			case "K&#269;": $currency_code = $input."^CZK";
			break;
			case "&#8362;": $currency_code = $input."^ILS";
			break;
			case "M$": $currency_code = "$^MXM";
			break;
			case "RM": $currency_code = $input."^MYR";
			break;
			case "T$": $currency_code = "$^TWD";
			break;
			case "&#3647;": $currency_code = $input."^THB";
			break;
			case "&#8378;": $currency_code = $input."^TRY";
			break;
			case "p.": $currency_code = $input."^RUB";
			break;
			default: $currency_code = $input."^USD";
		
		}
	
	}
	
	if ($method == 2) {
		
	$currency_code = array(	   
			"R$^Brazilian Real^BRL",
			"&pound;^British Pound^GBP",
			"K&#269;^Czech Koruna^CZK",
			"&euro;^Euro^EUR",
			"kr^Danish Krone^DKK",
			"A$^Dollar - Australian^AUD",
			"C$^Dollar - Canadian^CAD",
			"H$^Dollar -Hong Kong^HKD",
			"N$^Dollar - New Zealand^NZD",
			"S$^Dollar- Singapore^SGD",
			"$^Dollar - U.S.^USD",
			"Ft^Hungarian Forint^HUF",
			"&#8362;^Israeli New Shekel^ILS",
			"&yen;^Japanese Yen^JPY",
			"RM^Malaysian Ringgit^MYR",
			"M$^Mexican Peso^MXM",
			"nkr^Norwegian Krone^NOK",
			"&#8369;^Philippine Peso^PHP",
			"z&#322;^Polish Zloty^PLN",
			"p.^Russian Ruble^RUB",
			"skr^Swedish Krona^SEK",
			"&8355;Swiss Franc^CHF",
			"T$^Taiwan Dollar (New)^TWD",
			"&#3647;^Thai Baht^THB",
			"&#8378;^Turkish Lira^TRY"
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

# Pagination
/*
function paginate($display, $pg, $total) {
  if(isset($_SERVER['QUERY_STRING']) && trim(
    $_SERVER['QUERY_STRING']) != '') {
    	if(stristr($_SERVER['QUERY_STRING'], 'pg='))
      	$query_str = '?'.preg_replace('/pg=\d+/', 'pg=', 
        $_SERVER['QUERY_STRING']);
    	else
      	$query_str = '?'.$_SERVER['QUERY_STRING'].'&pg=';
  		} 	
	else
    $query_str = '?pg=';
    
  $pages = ($total <= $display) ? 1 : ceil($total / $display);

  $first = '<span id="sortable_first" class="first paginate_button"><a href="'.$_SERVER['PHP_SELF'].$query_str.'1">First</a></span>';
  $prev =  '<span id="sortable_previous" class="previous paginate_button"><a href="'.$_SERVER['PHP_SELF'].$query_str.($pg - 1).'">Previous</a></span>';
  $next =  '<span id="sortable_next" class="next paginate_button"><a href="'.$_SERVER['PHP_SELF'].$query_str.($pg + 1).'">Next</a></span>';
  $last =  '<span id="sortable_last" class="last paginate_button"><a href="'.$_SERVER['PHP_SELF'].$query_str.$pages.'">Last</a></span>';
   
  echo '<div id="sortable_paginate" class="dataTables_paginate paging_full_numbers">';
  echo ($pg > 1) ? "$first$prev" : '<span id="sortable_first" class="first paginate_button">First</span><span id="sortable_previous" class="previous paginate_button">Previous</span>';
  
  // limit the number of page links displayed  
  $begin = $pg - 8;
  while($begin < 1)
    $begin++;
  $end = $pg + 8;
  while($end > $pages)
    $end--;
  for($i=$begin; $i<=$end; $i++)
    echo ($i == $pg) ? ' <span class="paginate_active">'.$i.'</span> ' : '<span class="paginate_button"><a href="'.
      $_SERVER['PHP_SELF'].$query_str.$i.'">'.$i.'</a></span>';
      echo ($pg < $pages) ? "$next$last" : '<span id="sortable_next" class="next paginate_button">Next</span><span id="sortable_last" class="last paginate_button">Last</span>';
  echo '</div>';
}
*/

function total_fees($entry_fee, $entry_fee_discount, $entry_discount, $entry_discount_number, $cap_no, $special_discount_number, $bid, $filter) {
	require(CONFIG.'config.php');
	
	// ----------------------------------------------------------------------
	if (($bid == "default") && ($filter == "default")) { 
		mysql_select_db($database, $brewing);
		$query_users = sprintf("SELECT id,user_name FROM %s",$prefix."users");
		$users = mysql_query($query_users, $brewing) or die(mysql_error());
		$row_users = mysql_fetch_assoc($users);
		$totalRows_users = mysql_num_rows($users);

		do { $user_id_1[] = $row_users['id']; } while ($row_users = mysql_fetch_assoc($users));
		sort($user_id_1);
			
		foreach ($user_id_1 as $id_1) {
			// Get each entrant's number of entries
			mysql_select_db($database, $brewing);
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s'",$prefix."brewing", $id_1);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];
			
			$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'",$prefix."brewer", $id_1);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_array($brewer);
			
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
	mysql_select_db($database, $brewing);
		$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s'", $prefix."brewing", $bid);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_array($entries);
		$totalRows_entries = $row_entries['count'];
		//echo $totalRows_entries."<br>";
		
		$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'", $prefix."brewer", $bid);
		$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
		$row_brewer = mysql_fetch_array($brewer);
			
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
	
	mysql_select_db($database, $brewing);
		$query_users = sprintf("SELECT id,user_name FROM %s",$prefix."users");
		$users = mysql_query($query_users, $brewing) or die(mysql_error());
		$row_users = mysql_fetch_assoc($users);
		$totalRows_users = mysql_num_rows($users);

		do { $user_id_1[] = $row_users['id']; } while ($row_users = mysql_fetch_assoc($users));
		sort($user_id_1);
			
		foreach ($user_id_1 as $id_1) {
			// Get each entrant's number of entries
			mysql_select_db($database, $brewing);
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewCategorySort='%s'",$prefix."brewing",$id_1,$filter);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];
			
			$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'",$prefix."brewer", $id_1);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_array($brewer);
			
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

function total_fees_paid($entry_fee, $entry_fee_discount, $entry_discount, $entry_discount_number, $cap_no, $special_discount_number, $bid, $filter) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
 	// echo "<br>entry_fee:".$entry_fee."<br>entry_fee_discount:".$entry_fee_discount."<br>entry_discount:".$entry_discount."<br>entry_discount_number:".$entry_discount_number."<br>cap_no:".$cap_no."<br>special_discount_amount:".$special_discount_number."<br>bid:".$bid."<br>filter:".$filter."<br>";
	// ----------------------------------------------------------------------
	if (($bid == "default") && ($filter == "default")) { 
		$query_users = sprintf("SELECT id,user_name FROM %s", $prefix."users");
		$users = mysql_query($query_users, $brewing) or die(mysql_error());
		$row_users = mysql_fetch_assoc($users);
		$totalRows_users = mysql_num_rows($users);
	
		do { $user_id_2[] = $row_users['id']; } while ($row_users = mysql_fetch_assoc($users));
		sort($user_id_2);
		
		foreach ($user_id_2 as $id_2) {
			// Get each entrant's number of entries
			mysql_select_db($database, $brewing);
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='1'", $prefix."brewing", $id_2);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];
			
			$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewPaid='1' AND brewConfirmed='1'",$prefix."brewing", $id_2);
			$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
			$row_paid = mysql_fetch_array($paid);
			$totalRows_paid = $row_paid['count'];
			
			$totalRows_not_paid = ($row_entries['count'] - $row_paid['count']);
			
			$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'",$prefix."brewer", $id_2);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_array($brewer);

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
	mysql_select_db($database, $brewing);
	$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='1'", $prefix."brewing", $bid);
	$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
	$row_entries = mysql_fetch_array($entries);
	$totalRows_entries = $row_entries['count'];
			
	$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewPaid='1' AND brewConfirmed='1'", $prefix."brewing", $bid);
	$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
	$row_paid = mysql_fetch_array($paid);
	$totalRows_paid = $row_paid['count'];
	
	$totalRows_not_paid = ($totalRows_entries - $totalRows_paid);
	//echo "Entries: ".$totalRows_entries."<br>";
	//echo "Paid: ".$totalRows_paid."<br>";
	//echo "Not Paid: ".$totalRows_not_paid."<br>";	
	
	$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'", $prefix."brewer", $bid);
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_array($brewer);
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
		$users = mysql_query($query_users, $brewing) or die(mysql_error());
		$row_users = mysql_fetch_assoc($users);
		$totalRows_users = mysql_num_rows($users);
	
		do { $user_id_2[] = $row_users['id']; } while ($row_users = mysql_fetch_assoc($users));
		sort($user_id_2);
		
		foreach ($user_id_2 as $id_2) {
			// Get each entrant's number of entries
			mysql_select_db($database, $brewing);
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewCategorySort='%s' AND brewConfirmed='1'",$prefix."brewing", $id_2, $filter);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];
			
			$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewPaid='1' AND brewCategorySort='%s' AND brewConfirmed='1'",$prefix."brewing", $id_2, $filter);
			$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
			$row_paid = mysql_fetch_array($paid);
			$totalRows_paid = $row_paid['count'];
			
			$totalRows_not_paid = ($row_entries['count'] - $row_paid['count']);
			
			$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'", $prefix."brewer", $id_2);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_array($brewer);

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
/*
function unpaid_fees($total_not_paid, $discount_amt, $entry_fee, $entry_fee_disc, $cap, $secret, $secret_num) {
	switch($discount) {
		case "N": 
			if (($secret == "Y") && ($secret_num != "")) $entry_total =  $total_not_paid * $secret_num; 
			else $entry_total = $total_not_paid * $entry_fee;
		break;
		case "Y":
			if ($total_not_paid > $discount_amt) {
				if (($secret == "Y") && ($secret_num != "")) $reg_fee = $discount_amt * $secret_num;
				else $reg_fee = $discount_amt * $entry_fee;
				$disc_fee = ($total_not_paid - $discount_amt) * $entry_fee_disc;
				$entry_subtotal = $reg_fee + $disc_fee;
				}
			if ($total_not_paid <= $discount_amt) {
				if (($total_not_paid > 0) && ($secret != "Y")) $entry_total = $total_not_paid * $entry_fee;
				elseif (($total_not_paid > 0) && ($secret == "Y") && ($secret_num != "")) $entry_total = $total_not_paid * $secret_num;
				else $entry_subtotal = "0";
				}
		break;			
		} // end switch
		
		if ($cap == "0") $entry_total = $entry_subtotal;
		else { 
			if ($entry_subtotal > $cap) $entry_total = $cap;
			else $entry_total = $entry_subtotal;
		}
		return $entry_total;
	
} // end function

function discount_display($total_not_paid, $discount_amt, $entry_fee, $entry_fee_disc, $cap) { 
	if ($total_not_paid > $discount_amt) {
		$disc_fee = (($total_not_paid - $discount_amt) * $entry_fee_disc);
		$reg_fee = ($discount_amt * $entry_fee);
		$total = $disc_fee + $reg_fee;
		$array["a"] = $total_not_paid - $discount_amt;
		$array["b"] = $reg_fee;
		$array["c"] = $disc_fee;
		if (($cap != "0") && ($total <= $cap)) {
			$array["d"] = $total;
			}
		elseif (($cap != "0") && ($total > $cap)) { 
			$array["d"] = $cap;
			}
		else {
			$array["d"] = $total;
			}
		
		}
	if ($total_not_paid <= $discount_amt) {
		if ($total_not_paid > 0) $array = $total_not_paid * $entry_fee;
		else $array = "0";
		}
	return $array;
} // end funtion
*/

function total_not_paid_brewer($bid) { 
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);

	$query_all = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s'", $prefix."brewing", $bid);
	$all = mysql_query($query_all, $brewing) or die(mysql_error());
	$row_all = mysql_fetch_assoc($all);
	$totalRows_all = $row_all['count'];

	$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewPaid='1'", $prefix."brewing", $bid);
	$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
	$row_paid = mysql_fetch_assoc($paid);
	$totalRows_paid = $row_paid['count'];

	$total_not_paid = ($totalRows_all - $totalRows_paid);
	return $total_not_paid;
}

function total_paid_received($go,$id) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_entry_count =  sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."brewing");
	if (($go == "judging_scores") || ($go == "judging_tables")) $query_entry_count .= " WHERE brewPaid='1' AND brewReceived='1' AND brewConfirmed='1'";
	//if (($go == "entries") && ($id != "default")) $query_entry_count .= " WHERE brewCategorySort='$id'"; 
	if ($id == 0)  $query_entry_count .= "";
	elseif ($id > 0) $query_entry_count .= " WHERE brewBrewerID='$id' AND brewPaid='1' AND brewReceived='1' AND brewConfirmed='1'";
	$result = mysql_query($query_entry_count, $brewing) or die(mysql_error());
	$row = mysql_fetch_array($result);
	return $row['count'];
}

function style_convert($number,$type,$base_url="") {
	require(CONFIG.'config.php');
	$styles_db_table = $prefix."styles";
	
	switch ($type) {
		
		case "1": 
		
		require(CONFIG.'config.php');
		mysql_select_db($database, $brewing);
		$query_style = sprintf("SELECT brewStyle FROM %s WHERE brewStyleGroup='%s'",$prefix."styles",$number); 
		$style = mysql_query($query_style, $brewing) or die(mysql_error());
		$row_style = mysql_fetch_assoc($style);
		
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
			case "21": $style_convert = "Spice/Herb/Vegatable Beer"; break;
			case "22": $style_convert = "Smoke-Flavored and Wood-Aged Beer"; break;
			case "23": $style_convert = "Specialty Beer"; break;
			case "24": $style_convert = "Traditional Mead"; break;
			case "25": $style_convert = "Melomel (Fruit Mead)"; break;
			case "26": $style_convert = "Other Mead"; break;
			case "27": $style_convert = "Standard Cider and Perry"; break;
			case "28": $style_convert = "Specialty Cider and Perry"; break;
			default: $style_convert = $row_style['brewStyle']; break;
		}
		break;
		
		case "2":
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
		break;
		
		case "3":
		$n = preg_replace('/[^0-9]+/', '', $number);
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
		break;
		
		case "4":
		$a = explode(",",$number);
		require(CONFIG.'config.php');
	    mysql_select_db($database, $brewing);
		foreach ($a as $value) {
			$styles_db_table = $prefix."styles";
			$query_style = "SELECT brewStyleGroup,brewStyleNum,brewStyle FROM $styles_db_table WHERE id='$value'"; 
			$style = mysql_query($query_style, $brewing) or die(mysql_error());
			$row_style = mysql_fetch_assoc($style);
			$trimmed = ltrim($row_style['brewStyleGroup'],"0");
			$style_convert[] = "<a id='modal_window_link' href='".$base_url."output/styles.php#".$trimmed.$row_style['brewStyleNum']."' title='View ".$row_style['brewStyle']."'>".$trimmed.$row_style['brewStyleNum']."</a>";
		}
		break;
		
		case "5":
		$n = preg_replace('/[^0-9]+/', '', $number);
		if ($n >= 24) $style_convert = TRUE;
		break;
		
		case "6":
		$a = explode(",",$number);
		require(CONFIG.'config.php');
	    mysql_select_db($database, $brewing);
		foreach ($a as $value) {
			$styles_db_table = $prefix."styles";
			$query_style = "SELECT brewStyleGroup,brewStyleNum FROM $styles_db_table WHERE id='$value'"; 
			$style = mysql_query($query_style, $brewing) or die(mysql_error());
			$row_style = mysql_fetch_assoc($style);
			$style_convert1[] = ltrim($row_style['brewStyleGroup'],"0").$row_style['brewStyleNum'];
		}
		$style_convert = rtrim(implode(", ",$style_convert1),", ");
		break;
		
		case "7":
		$a = explode(",",$number);
		require(CONFIG.'config.php');
	    mysql_select_db($database, $brewing);
		$style_convert = "";
		$style_convert .= "<ul>";
		foreach ($a as $value) {
			$styles_db_table = $prefix."styles";
			$query_style = "SELECT brewStyleGroup,brewStyleNum,brewStyle FROM $styles_db_table WHERE id='$value'"; 
			$style = mysql_query($query_style, $brewing) or die(mysql_error());
			$row_style = mysql_fetch_assoc($style);
			if ($row_style['brewStyleGroup'] > 28) $style_convert .= "<li>Custom Style: ".$row_style['brewStyle']."</li>";
			else $style_convert .= "<li>".ltrim($row_style['brewStyleGroup'],"0").$row_style['brewStyleNum'].": ".$row_style['brewStyle']."</li>";
		}
		$style_convert .= "</ul>";
		
		break;
		
		// Get Style Name
		case "8":
		require(CONFIG.'config.php');
	    mysql_select_db($database, $brewing);
		$query_styles = sprintf("SELECT brewStyle FROM $styles_db_table WHERE id='%s'", $number);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$style_convert = $row_styles['brewStyle'];
		
		break;
	}
	return $style_convert;
}

function get_table_info($input,$method,$id,$dbTable,$param) {	
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	if ($dbTable == "default") {
		$judging_tables_db_table = $prefix."judging_tables";
		$judging_locations_db_table = $prefix."judging_locations";
		$judging_scores_db_table = $prefix."judging_scores";
		$judging_scores_bos_db_table = $prefix."judging_scores_bos";
		$styles_db_table = $prefix."styles";
		$brewing_db_table = $prefix."brewing";
	}
	
	else {
		$suffix = ltrim(get_suffix($dbTable), "_");
		$suffix = "_".$suffix;
		$judging_tables_db_table = $prefix."judging_tables".$suffix;
		$judging_locations_db_table = $prefix."judging_locations".$suffix;
		$judging_scores_db_table = $prefix."judging_scores".$suffix;
		$judging_scores_bos_db_table = $prefix."judging_scores_bos".$suffix;
		$styles_db_table = $prefix."styles";
		$brewing_db_table = $prefix."brewing".$suffix;

		
	}
	
	$query_table = "SELECT * FROM $judging_tables_db_table";
	if ($id != "default") $query_table .= " WHERE id='$id'";
	if ($param != "default") $query_table .= " WHERE tableLocation='$param'";
	$table = mysql_query($query_table, $brewing) or die(mysql_error());
	$row_table = mysql_fetch_assoc($table);
	
	if ($method == "basic") {
		$return = $row_table['tableNumber']."^".$row_table['tableName']."^".$row_table['tableLocation'];
		return $return;
	}
	
	if ($method == "location") { // used in output/assignments.php and output/pullsheets.php
		$query_judging_location = sprintf("SELECT * FROM %s WHERE id='%s'", $prefix."judging_locations", $input);
		$judging_location = mysql_query($query_judging_location, $brewing) or die(mysql_error());
		$row_judging_location = mysql_fetch_assoc($judging_location);
		
		$return = $row_judging_location['judgingDate']."^".$row_judging_location['judgingTime']."^".$row_judging_location['judgingLocName'];
		return $return;
	}
	
	if ($method == "unassigned") {
		$return = "";
		$query_styles = "SELECT id,brewStyle FROM $styles_db_table";
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		do { $a[] = $row_styles['id']; } while ($row_styles = mysql_fetch_assoc($styles));
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
				//$styles1 = mysql_query($query_styles1, $brewing) or die(mysql_error());
				//$row_styles1 = mysql_fetch_assoc($styles1);
				//echo "<p>".$row_styles1['brewStyle']."</p>";
				}
			
				//else echo "No.<br>";
		}
	return $return;
	}
	
	if ($method == "styles") {
		do { 
			$a = explode(",", $row_table['tableStyles']);
			$b = $input;
			foreach ($a as $value) {
				if ($value == $input) return TRUE;
			}
		} while ($row_table = mysql_fetch_assoc($table));
	}
	
	if ($method == "assigned") {
		do { 
			$a = explode(",", $row_table['tableStyles']);
			$b = $input;
			foreach ($a as $value) {
				if ($value == $input) $c = "<br><em>Assigned to Table #".$row_table['tableNumber'].": <a href='index.php?section=admin&go=judging_tables&action=edit&id=".$row_table['id']."'>".$row_table['tableName']."</a></em>";
			}
		} while ($row_table = mysql_fetch_assoc($table));
	return $c;
  	}
	
	if ($method == "list") {
		$a = explode(",", $row_table['tableStyles']);
			foreach ($a as $value) {
				require(CONFIG.'config.php');
				mysql_select_db($database, $brewing);
				$query_styles = "SELECT * FROM $styles_db_table WHERE id='$value'";
				$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
				$row_styles = mysql_fetch_assoc($styles);
				
				$c[] = ltrim($row_styles['brewStyleGroup'].$row_styles['brewStyleNum'],"0").",&nbsp;";
			}
	$d = array($c);
	return $d;
  	}
	
	if (($method == "count_total") && ($param == "default")) {
		
		$a = explode(",", $row_table['tableStyles']);
			foreach ($a as $value) {
				require(CONFIG.'config.php');
				mysql_select_db($database, $brewing);
				$query_styles = "SELECT brewStyle FROM $styles_db_table WHERE id='$value'";
				$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
				$row_styles = mysql_fetch_assoc($styles);
				
				$query_style_count = sprintf("SELECT COUNT(*) as count FROM $brewing_db_table WHERE brewStyle='%s' AND brewReceived='1'", $row_styles['brewStyle']);
				$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
				$row_style_count = mysql_fetch_assoc($style_count);
				$totalRows_style_count = $row_style_count['count'];
				$c[] = $totalRows_style_count ;
			}
	$d = array_sum($c);
	return $d; 
  	}
	
	
	if (($method == "score_total") && ($param == "default")) {
		
		$query_score_count = sprintf("SELECT COUNT(*) as 'count' FROM $judging_scores_db_table WHERE scoreTable='%s'", $id);
		$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
		$row_score_count = mysql_fetch_assoc($score_count);
		$totalRows_score_count = $row_score_count['count'];
		
		return $totalRows_score_count; 
  	}
	
	if (($method == "count_total") && ($param != "default")) {
	
	do {	
		$a = explode(",", $row_table['tableStyles']);
			foreach ($a as $value) {
				require(CONFIG.'config.php');
				mysql_select_db($database, $brewing);
				$query_styles = "SELECT brewStyle FROM $styles_db_table WHERE id='$value'";
				$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
				$row_styles = mysql_fetch_assoc($styles);
				
				$query_style_count = sprintf("SELECT COUNT(*) as count FROM $brewing_db_table WHERE brewStyle='%s' AND brewReceived='1'", $row_styles['brewStyle']);
				$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
				$row_style_count = mysql_fetch_assoc($style_count);
				$totalRows_style_count = $row_style_count['count'];
				$c[] = $totalRows_style_count ;
			}
	} while ($row_table = mysql_fetch_assoc($table));
	$d = array_sum($c);
	return $d; 
  	}
	
	
	if ($method == "count") {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_style = "SELECT brewStyle FROM $styles_db_table WHERE brewStyle='$input'";
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	//echo $query_style."<br>";
	
	$query = sprintf("SELECT COUNT(*) as 'count' FROM $brewing_db_table WHERE brewStyle='%s' AND brewReceived='1'",$row_style['brewStyle']);
	$result = mysql_query($query, $brewing) or die(mysql_error());
	$num_rows = mysql_fetch_array($result);
	// echo $query;
	//$num_rows = mysql_num_rows($result);
	return $num_rows['count'];
	}
	
	if ($method == "count_scores") {
		$a = explode(",", $row_table['tableStyles']);
			foreach ($a as $value) {
				require(CONFIG.'config.php');
				mysql_select_db($database, $brewing);
				$query_styles = "SELECT brewStyle FROM $styles_db_table WHERE id='$value'";
				$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
				$row_styles = mysql_fetch_assoc($styles);
				
				$query_style_count = sprintf("SELECT COUNT(*) as 'count' FROM $brewing_db_table WHERE brewStyle='%s' AND brewReceived='1'", $row_styles['brewStyle']);
				$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
				$row_style_count = mysql_fetch_assoc($style_count);
				$totalRows_style_count = $row_style_count['count'];
									
				$c[] = $totalRows_style_count;
				
			}
		$query_score_count = sprintf("SELECT COUNT(*) as 'count' FROM $judging_scores_db_table WHERE scoreTable='%s'", $input);
		$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
		$row_score_count = mysql_fetch_assoc($score_count);
		$totalRows_score_count = $row_score_count['count'];
		$e = array_sum($c);
		if ($e == $totalRows_score_count) return true;
  	}
	
	if ($method == "count_single_table") {
		
		$query_score_count = sprintf("SELECT COUNT(*) as 'count' FROM $judging_scores_db_table WHERE scoreTable='%s'", $input);
		$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
		$row_score_count = mysql_fetch_assoc($score_count);
		return $row_score_count['count'];
		
	}
}

function style_type($type,$method,$source) { 
	if ($method == "1") { 
		switch($type) { 
			case "Mead": $type = "3";
			break;
			
			case "Cider": $type = "2";
			break;
			
			case "Mixed": $type = "1";
			break;
			
			case "Ale": $type = "1";
			break;
			
			case "Lager": $type = "1";
			break;
			
			default: $type = $type;
			break;
		}
	}
	
	if (($method == "2") && ($source == "bcoe")) { 
		switch($type) {
			case "3": $type = "Mead";
			break;
			
			case "2": $type = "Cider";
			break;
			
			case "1": $type = "Beer";
			break;
			
			case "Lager": $type = "Beer";
			break;
			
			case "Ale": $type = "Beer";
			break;
			
			case "Mixed": $type = "Beer";
			break;
			
			default: $type = $type;
			break;
		}
	}
	
	if (($method == "2") && ($source == "custom")) { 
		require(CONFIG.'config.php');
		mysql_select_db($database, $brewing);
		
		$query_style_type = sprintf("SELECT styleTypeName FROM %s WHERE id='%s'", $prefix."style_types", $type); 
		$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
		$row_style_type = mysql_fetch_assoc($style_type);
		$type = $row_style_type['styleTypeName'];
	}
	
	if ($method == "3") { 
		require(CONFIG.'config.php');
		mysql_select_db($database, $brewing);
		
		$query_style_type = sprintf("SELECT styleTypeName FROM %s WHERE id='%s'", $prefix."style_types", $type); 
		$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
		$row_style_type = mysql_fetch_assoc($style_type);
		$type = $row_style_type['styleTypeName'];
	}
	return $type;
}
/*
function check_bos_loc($id) { 
	require(CONFIG.'config.php');
	$query_judging = sprintf("SELECT judgingLocName,judgingDate FROM %s WHERE id='$id'", $prefix."judging_locations");
	$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
	$row_judging = mysql_fetch_assoc($judging);
	$totalRows_judging = mysql_num_rows($judging);
	$bos_loc = $row_judging['judgingLocName']." (".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time").")";
	return $bos_loc;
}
*/


function table_location($table_id,$date_format,$time_zone,$time_format,$method) { 
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_table = sprintf("SELECT tableLocation FROM %s WHERE id='%s'", $prefix."judging_tables", $table_id);
	$table = mysql_query($query_table, $brewing) or die(mysql_error());
	$row_table = mysql_fetch_assoc($table);
	
	if ($method == "some-variable") {
		// Future use 
	}
	
	if ($method == "default") {
	
		$query_location = sprintf("SELECT judgingLocName,judgingDate,judgingTime FROM %s WHERE id='%s'", $prefix."judging_locations", $row_table['tableLocation']);
		$location = mysql_query($query_location, $brewing) or die(mysql_error());
		$row_location = mysql_fetch_assoc($location);
		$totalRows_location = mysql_num_rows($location);
		
		if ($totalRows_location == 1) {
			$table_location = $row_location['judgingLocName']." - ".getTimeZoneDateTime($time_zone, $row_location['judgingDate'], $date_format,  $time_format, "long", "date-time-no-gmt");
		}
		else $table_location = ""; 
	}
	
	return $table_location;
} 

function score_count($table_id,$method) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_scores = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE scoreTable='%s'", $prefix."judging_scores", $table_id);
	$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
	$row_scores = mysql_fetch_assoc($scores);
	
	switch($method) {
		case "1": if ($row_scores['count'] > 0) return true; else return false;
		break;
		
		case "2": return $row_scores['count'];
		break;
	}
	
}

function bjcp_rank($rank,$method) {
    if ($method == "1") {
		switch($rank) {
			case "Apprentice": 
			case "Provisional":
			case "Rank Pending":
			$return = "Level 1:";
			break;
			case "Recognized": $return = "Level 2:";
			break;
			case "Certified": $return = "Level 3:";
			break;
			case "National": $return = "Level 4:";
			break;
			case "Master": $return = "Level 5:";
			break;
			case "Grand Master": $return = "Level 6:";
			break;
			case "Honorary Master": $return = "Level 5:";
			break;
			case "Honorary Grand Master": $return = "Level 6:";
			break;
			case "Experienced": $return = "Level 0:";
			break;
			case "Professional Brewer":
			case "Beer Sommelier":
			case "Certified Cicerone":
			case "Master Cicerone":
			case "Judge with Sensory Training":
			$return = "Level 2:";
			break;
			case "Mead Judge": $return = "Level 3:";
			break;
			default: $return = "Level 0:";
		}
	if (($rank != "None") && ($rank != "")) $return .= " ".$rank;
	}
	
	if ($method == "2") {
		switch($rank) {
			case "None": 
			case "":
			case "Experienced":
			$return = "Experienced Judge";
			break;
			case "Professional Brewer":
			case "Beer Sommelier":
			case "Certified Cicerone":
			case "Master Cicerone":
			case "Judge with Sensory Training":
			$return = $rank;
			break;
			default: $return = "BJCP ".$rank." Judge";
		}
	}
	
	return $return;
}

function srm_color($srm,$method) {
	if ($method == "ebc") $srm = (1.97 * $srm); else $srm = $srm;
	
    if ($srm >= 01 && $srm < 02) $return = "#f3f993";
	elseif ($srm >= 02 && $srm < 03) $return = "#f5f75c";
	elseif ($srm >= 03 && $srm < 04) $return = "#f6f513";
	elseif ($srm >= 04 && $srm < 05) $return = "#eae615";
	elseif ($srm >= 05 && $srm < 06) $return = "#e0d01b";
	elseif ($srm >= 06 && $srm < 07) $return = "#d5bc26";
	elseif ($srm >= 07 && $srm < 08) $return = "#cdaa37";
	elseif ($srm >= 08 && $srm < 09) $return = "#c1963c";
	elseif ($srm >= 09 && $srm < 10) $return = "#be8c3a";
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
	mysql_select_db($database, $brewing);
	$query_contact_count = sprintf("SELECT COUNT(*) as 'count' FROM %s",$prefix."contacts");
	$result = mysql_query($query_contact_count, $brewing) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
	$contactCount = $row["count"];
	return $contactCount;
}

function brewer_info($uid) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_brewer_info = sprintf("SELECT brewerFirstName,brewerLastName,brewerPhone1,brewerJudgeRank,brewerJudgeID,brewerJudgeBOS,brewerEmail,uid,brewerClubs,brewerDiscount,brewerAddress,brewerCity,brewerState,brewerZip,brewerCountry FROM %s WHERE uid='%s'", $prefix."brewer", $uid);
	$brewer_info = mysql_query($query_brewer_info, $brewing) or die(mysql_error());
	$row_brewer_info = mysql_fetch_assoc($brewer_info);
	$r = 
	$row_brewer_info['brewerFirstName']."^". 		// 0
	$row_brewer_info['brewerLastName']."^". 		// 1
	$row_brewer_info['brewerPhone1']."^". 			// 2
	$row_brewer_info['brewerJudgeRank']."^".		// 3
	$row_brewer_info['brewerJudgeID']."^".			// 4
	$row_brewer_info['brewerJudgeBOS']."^".			// 5
	$row_brewer_info['brewerEmail']."^".			// 6
	$row_brewer_info['uid']."^".					// 7
	$row_brewer_info['brewerClubs']."^".			// 8
	$row_brewer_info['brewerDiscount']."^".			// 9
	$row_brewer_info['brewerAddress']."^".			// 10
	$row_brewer_info['brewerCity']."^".				// 11
	$row_brewer_info['brewerState']."^".			// 12
	$row_brewer_info['brewerZip'];					// 13
	$row_brewer_info['brewerCountry'];				// 14
	return $r;
}

function get_entry_count($method) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewConfirmed='1'",$prefix."brewing");
	if ($method == "received") $query_paid .= " AND brewReceived='1'";
	if ($method == "paid-received") $query_paid .= " AND brewReceived='1' AND brewPaid='1'";
	if ($method == "unpaid-received") $query_paid .= " AND brewReceived='1' AND brewPaid='0'";
	if ($method == "paid-not-received") $query_paid .= " AND brewReceived='0' AND brewPaid='1'";
	$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
	$row_paid = mysql_fetch_assoc($paid);
	$r = $row_paid['count'];
	return $r;
}

function get_participant_count($type) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	if ($type == 'default') $query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s",$prefix."brewer");
	if ($type == 'judge') $query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewerJudge='Y'",$prefix."brewer");
	if ($type == 'steward') $query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewerSteward='Y'",$prefix."brewer");
	$participant_count = mysql_query($query_participant_count, $brewing) or die(mysql_error());
	$row_participant_count = mysql_fetch_assoc($participant_count);
	
	return $row_participant_count['count'];

}

function display_place($place,$method) {
	
	require(CONFIG.'config.php');
	
	if ($method == "0") { 
		$place = addOrdinalNumberSuffix($place);
	}
	
	if ($method == "1") { 
		switch($place){
			case "1": $place = addOrdinalNumberSuffix($place);
			break;
			case "2": $place = addOrdinalNumberSuffix($place);
			break;
			case "3": $place = addOrdinalNumberSuffix($place);
			break;
			case "4": $place = addOrdinalNumberSuffix($place);
			break;
			case "5": $place = "HM";
			break;
		default: $place = "N/A";
		}
	}
	if ($method == "2") { 
		switch($place){
			case "1": $place = "<span class=\"icon\"><img src=\"".$base_url."images/medal_gold_3.png\"></span>".addOrdinalNumberSuffix($place);
			break;
			case "2": $place = "<span class=\"icon\"><img src=\"".$base_url."images/medal_silver_3.png\"></span>".addOrdinalNumberSuffix($place);
			break;
			case "3": $place = "<span class=\"icon\"><img src=\"".$base_url."images/medal_bronze_3.png\"></span>".addOrdinalNumberSuffix($place);
			break;
			case "4": $place = "<span class=\"icon\"><img src=\"".$base_url."images/rosette.png\"></span>".addOrdinalNumberSuffix($place);
			break;
			case "5": $place = "<span class=\"icon\"><img src=\"".$base_url."images/rosette.png\"></span>HM";
			break;
			default: $place = "N/A";
			}
	}
	
	if ($method == "3") { 
		switch($place){
			case "1": $place = "<span class=\"icon\"><img src=\"".$base_url."images/medal_gold_3.png\"></span>".addOrdinalNumberSuffix($place);
			break;
			case "2": $place = "<span class=\"icon\"><img src=\"".$base_url."images/medal_silver_3.png\"></span>".addOrdinalNumberSuffix($place);
			break;
			case "3": $place = "<span class=\"icon\"><img src=\"".$base_url."images/medal_bronze_3.png\"></span>".addOrdinalNumberSuffix($place);
			break;
			default: $place = "<span class=\"icon\"><img src=\"".$base_url."images/rosette.png\"></span>".addOrdinalNumberSuffix($place);
			}
	}
	
	return $place;
}

function entry_info($id) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_entry_info = sprintf("SELECT brewName,brewCategory,brewCategorySort,brewSubCategory,brewStyle,brewCoBrewer,brewJudgingNumber FROM %s WHERE id='%s'", $prefix."brewing", $id);
	$entry_info = mysql_query($query_entry_info, $brewing) or die(mysql_error());
	$row_entry_info = mysql_fetch_assoc($entry_info);
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
	mysql_select_db($database, $brewing);
	$query_scores = sprintf("SELECT scoreEntry FROM %s WHERE eid='%s'",$judging_scores_db_table,$id);
	$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
	$row_scores = mysql_fetch_assoc($scores);
	
	$r = $row_scores['scoreEntry'];
	return $r;
}

function minibos_check($id,$judging_scores_db_table) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_scores = sprintf("SELECT scoreMiniBOS FROM %s WHERE eid='%s'",$judging_scores_db_table,$id);
	$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
	$row_scores = mysql_fetch_assoc($scores);
	
	if ($row_scores['scoreMiniBOS'] == "1") return TRUE;
	else return FALSE;
}

function winner_check($id,$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$method) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	if ($method == 6) { // reserved for NHC admin advance
		$r = "Administrative Advance";
	}
	
	if ($method == "") {
		$r = "";	
	}
	
	
	if ($method < 6) {
	$query_scores = sprintf("SELECT eid,scorePlace,scoreTable FROM %s WHERE eid='%s'",$judging_scores_db_table,$id);
	$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
	$row_scores = mysql_fetch_assoc($scores);
	
	if ($row_scores['scorePlace'] >= "1") {
		
		if ($method == "0") {  // Display by Table
		
		$query_table = sprintf("SELECT tableName FROM $judging_tables_db_table WHERE id='%s'", $row_scores['scoreTable']);
		$table = mysql_query($query_table, $brewing) or die(mysql_error());
		$row_table = mysql_fetch_assoc($table);
		$r = display_place($row_scores['scorePlace'],1).": ".$row_table['tableName'];
		} 
		
		if ($method == "1") {  // Display by Category
		
		$query_entry = sprintf("SELECT brewCategorySort FROM $brewing_db_table WHERE id='%s'", $row_scores['eid']);
		$entry = mysql_query($query_entry, $brewing) or die(mysql_error());
		$row_entry = mysql_fetch_assoc($entry);
		$r = display_place($row_scores['scorePlace'],1).": ".style_convert($row_entry['brewCategorySort'],1);
		}
		
		if ($method == "2") {  // Display by Category
		
		$query_entry = sprintf("SELECT brewCategorySort,brewCategory,brewSubCategory FROM $brewing_db_table WHERE id='%s'", $row_scores['eid']);
		$entry = mysql_query($query_entry, $brewing) or die(mysql_error());
		$row_entry = mysql_fetch_assoc($entry);
		
		$query_style = sprintf("SELECT brewStyle FROM %s WHERE brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles", $row_entry['brewCategorySort'],$row_entry['brewSubCategory']);
		$style = mysql_query($query_style, $brewing) or die(mysql_error());
		$row_style = mysql_fetch_assoc($style);
		
		$r = display_place($row_scores['scorePlace'],1).": ".$row_style['brewStyle']." (".$row_entry['brewCategory'].$row_entry['brewSubCategory'].")";
		}
	} 
	else $r = "";
	}
	//$r = "<td class=\"dataList\">".$query_scores."<br>".$query_table."</td>";
	return $r;
}

function brewer_assignment($uid,$method){
	
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);	
	$query_staff_check = sprintf("SELECT * FROM %s WHERE uid='%s'", $prefix."staff", $uid);
	$staff_check = mysql_query($query_staff_check, $brewing) or die(mysql_error());
	$row_staff_check = mysql_fetch_assoc($staff_check);
	$totalRows_staff_check = mysql_num_rows($staff_check);
	
	if ($totalRows_staff_check > 0) {
	$r[] = "";
		switch($method) {
			case "1": // 
				if ($row_staff_check['staff_organizer'] == "1") $r[] .= "Organizer";
				if ($row_staff_check['staff_judge'] == "1") $r[] .= "Judge";
				if ($row_staff_check['staff_judge_bos'] == "1") $r[] .= "BOS Judge";
				if ($row_staff_check['staff_steward'] == "1") $r[] .= "Steward"; 
				if ($row_staff_check['staff_staff'] == "1") $r[] .= "Staff";
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
		if ($uid == "judges") $r = "Judges"; 
		elseif ($uid == "stewards") $r = "Stewards"; 
		elseif ($uid == "staff") $r = "Staff";
		elseif ($uid == "bos") $r = "BOS Judges";
		else $r = "";
	}

return $r;
}

function entries_unconfirmed($user_id) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);	
	$query_entry_check = sprintf("SELECT id FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='0'", $prefix."brewing", $user_id);
	$entry_check = mysql_query($query_entry_check, $brewing) or die(mysql_error());
	$row_entry_check = mysql_fetch_assoc($entry_check);
	$totalRows_entry_check = mysql_num_rows($entry_check); 
	
	if ($totalRows_entry_check > 0)	return $totalRows_entry_check; else return 0;
}

function check_special_ingredients($style) {
	
	include(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
		
	$style = explode("-",$style);

	$query_brews = sprintf("SELECT brewStyleReqSpec FROM %s WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $prefix."styles", $style[0], $style[1]);
	$brews = mysql_query($query_brews, $brewing) or die(mysql_error());
	$row_brews = mysql_fetch_assoc($brews);
	
	if ($row_brews['brewStyleReqSpec'] == 1) return TRUE;
	else return FALSE;
}

function entries_no_special($user_id) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_entry_check = sprintf("SELECT brewCategorySort, brewSubCategory FROM %s WHERE brewBrewerID='%s' AND brewInfo IS NULL", $prefix."brewing", $user_id);
	$entry_check = mysql_query($query_entry_check, $brewing) or die(mysql_error());
	$row_entry_check = mysql_fetch_assoc($entry_check);
	
	do {
		$brew_style[] = $row_entry_check['brewCategorySort']."-".$row_entry_check['brewSubCategory'];
	} while ($row_entry_check = mysql_fetch_assoc($entry_check));
	
	foreach ($brew_style as $style) {
		
		if (check_special_ingredients($style)) $totalRows_entry_check[] = 1; else $totalRows_entry_check[] = 0;
		
	}
	
	if (array_sum($totalRows_entry_check) > 0)	return TRUE; 
	else return FALSE;
}


function data_integrity_check() {
	
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	// Match user emails against the record in the brewer table,
	// Compare user's id against uid,
	// If no match, replace uid with user's id
	
	$query_user_check = sprintf("SELECT id,user_name FROM %s", $prefix."users");
	$user_check = mysql_query($query_user_check, $brewing) or die(mysql_error());
	$row_user_check = mysql_fetch_assoc($user_check);
	
	do { 
	
		// Get Brewer Info
		$query_brewer = sprintf("SELECT id,uid,brewerEmail,brewerFirstName,brewerLastname FROM %s WHERE brewerEmail='%s'",$prefix."brewer",$row_user_check['user_name']);
		$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
		$row_brewer = mysql_fetch_assoc($brewer);
		$totalRows_brewer = mysql_num_rows($brewer);
		
		// Check to see if info is matching up. If not...
		if (($row_brewer['brewerEmail'] == $row_user_check['user_name']) && ($row_brewer['uid'] != $row_user_check['id']) && ($totalRows_brewer == 1)) {
			// ...Update to the correct uid
			$updateSQL = sprintf("UPDATE %s SET uid='%s' WHERE id='%s'", $prefix."brewer", $row_user_check['id'], $row_brewer['id']);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			// Change all associated entries to the correct uid (brewBrewerID row) in the "brewing" table
			$query_brewer_entries = sprintf("SELECT id FROM %s WHERE brewBrewerLastName='%s' AND brewBrewerFirstName='%s'",$prefix."brewing",$row_brewer['brewerLastName'],$row_brewer['brewerLastName']);
			$brewer_entries = mysql_query($query_brewer_entries, $brewing) or die(mysql_error());
			$row_brewer_entries = mysql_fetch_assoc($brewer_entries);
			$totalRows_brewer_entries = mysql_num_rows($brewer_entries);
			
			if ($totalRows_brewer_entries > 0) {
				do {
					$updateSQL = sprintf("UPDATE %s SET brewBrewerID='%s' WHERE id='%s'", $prefix."brewing", $row_brewer_entries['id'], $row_brewer['id']);
					$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
				} while ($row_brewer_entries = mysql_fetch_assoc($brewer_entries));
			}
		} // end if (($row_brewer['brewerEmail'] == $row_user_check['user_name']) && ($row_brewer['uid'] != $row_user_check['id']) && ($totalRows_brewer == 1))
		
		
		// Delete user record if no record of the user's extended information is found in the "brewer" table
		if ($totalRows_brewer == 0) {	
			$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."users", $row_user_check['id']);
  			$result = mysql_query($deleteSQL, $brewing) or die(mysql_error());		
			
			// Check to see if there are entries under that uid. If so, delete.
			$query_brewer_entries = sprintf("SELECT id FROM %s WHERE brewBrewerID='%s'",$prefix."brewing",$row_user_check['id']);
			$brewer_entries = mysql_query($query_brewer_entries, $brewing) or die(mysql_error());
			$row_brewer_entries = mysql_fetch_assoc($brewer_entries);
			$totalRows_brewer_entries = mysql_num_rows($brewer_entries);
			
			if ($totalRows_brewer_entries > 0) {
				do {
					$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $row_brewer_entries['id']);
					$result = mysql_query($deleteSQL, $brewing) or die(mysql_error());		
				} while ($row_brewer_entries = mysql_fetch_assoc($brewer_entries));
			}
		} // end if ($totalRows_brewer == 0)
	} while ($row_user_check = mysql_fetch_assoc($user_check));
	
	// Check if there are "blank" entries. If so, delete.
	$query_blank = sprintf("SELECT id FROM %s WHERE 
							 (brewStyle IS NULL OR brewStyle = '')
							 AND 
							 (brewCategory IS NULL OR brewCategory = '')
							 AND 
							 (brewCategorySort IS NULL OR brewCategorySort = '')
							 AND 
							 (brewBrewerID IS NULL OR brewBrewerID = '')
							 ",$prefix."brewing");
	$blank = mysql_query($query_blank, $brewing) or die(mysql_error());
	$row_blank = mysql_fetch_assoc($blank);
	$totalRows_blank = mysql_num_rows($blank);
	
	if ($totalRows_blank > 0) {
		do {
			$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $row_blank['id']);
			$result = mysql_query($deleteSQL, $brewing) or die(mysql_error());
		} while ($row_blank = mysql_fetch_assoc($blank));	
	}
	
	
	// Check if there are "blanks" in the brewer table. If so, delete.
	$query_blank1 = sprintf("SELECT id FROM %s WHERE 
							 (brewerFirstName IS NULL OR brewerFirstName = '')
							 AND 
							 (brewerLastName IS NULL OR brewerLastName = '')
							 ",$prefix."brewer");
	$blank1 = mysql_query($query_blank1, $brewing) or die(mysql_error());
	$row_blank1 = mysql_fetch_assoc($blank1);
	$totalRows_blank1 = mysql_num_rows($blank1);
	
	if ($totalRows_blank1 > 0) {
		do {
			$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewer", $row_blank1['id']);
			$result = mysql_query($deleteSQL, $brewing) or die(mysql_error());
		} while ($row_blank1 = mysql_fetch_assoc($blank1));	
	}
	
	
	// Look for duplicate entries in the judging_scores table
	$query_judging_duplicates = sprintf("SELECT eid FROM %s",$prefix."judging_scores");
	$judging_duplicates = mysql_query($query_judging_duplicates, $brewing) or die(mysql_error());
	$row_judging_duplicates = mysql_fetch_assoc($judging_duplicates);
	$totalRows_judging_duplicates = mysql_num_rows($judging_duplicates);
	
	if ($totalRows_judging_duplicates > 2) {
	
	do { $a[] = $row_judging_duplicates['eid']; } while ($row_judging_duplicates = mysql_fetch_assoc($judging_duplicates));
																								  
		foreach ($a as $eid) {
			
			$query_duplicates = sprintf("SELECT id FROM %s WHERE eid='%s'",$prefix."judging_scores",$eid);
			$duplicates = mysql_query($query_duplicates, $brewing) or die(mysql_error());
			$row_duplicates = mysql_fetch_assoc($duplicates);
			$totalRows_duplicates = mysql_num_rows($duplicates);
			
			if ($totalRows_duplicates > 1) {
				
				for($i=1; $i<$totalRows_duplicates; $i++) {
				
					$query_duplicate = sprintf("SELECT id FROM %s WHERE eid='%s'",$prefix."judging_scores",$eid);
					$duplicate = mysql_query($query_duplicate, $brewing) or die(mysql_error());
					$row_duplicate = mysql_fetch_assoc($duplicate);
				
					$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."judging_scores", $row_duplicate['id']);
					$result = mysql_query($deleteSQL, $brewing) or die(mysql_error());
				}
				
			}
		}
	}
	
	/* Erase judging and stewarding assignments of the organizer if any
	// INTERIM MEASURE. Incorporate this check once the 
	// judging/stewarding/staff methodology is reworked
	$query_org = sprintf("SELECT uid FROM %s WHERE brewerAssignment='O'",$prefix."brewer");
	$org = mysql_query($query_org, $brewing) or die(mysql_error());
	$row_org = mysql_fetch_assoc($org);
	$totalRows_org = mysql_num_rows($org);
	
	if ($totalRows_org > 0) {
		
		$query_org_judging = sprintf("SELECT id FROM %s WHERE bid='%s'",$prefix."judging_assignments", $row_org['uid']);
		$org_judging = mysql_query($query_org_judging, $brewing) or die(mysql_error());
		$row_org_judging = mysql_fetch_assoc($org_judging);
		$totalRows_org_judging = mysql_num_rows($org_judging);
		
		if ($totalRows_org_judging > 0) {
			
			do {
				$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."judging_assignments", $row_org_judging['id']);
				$result = mysql_query($deleteSQL, $brewing) or die(mysql_error());
			} while ($row_org_judging = mysql_fetch_assoc($org_judging));

		}
		
	}
	*/
	
	// Next, purge all entries that are unconfirmed
	purge_entries("unconfirmed", 1);
	purge_entries("special", 1);
	
	// Last update the "system" table with the date/time the function ended
	$updateSQL = sprintf("UPDATE %s SET data_check=%s WHERE id='1'", $prefix."system", "NOW( )");
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
} // END function


function readable_number($a){

// http://www.iamcal.com/publish/articles/php/readable_numbers/

	$bits_a = array("thousand", "million", "billion", "trillion", "quadrillion");
	$bits_b = array("ten", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety");
	$bits_c = array("one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen");

	if ($a==0){return 'zero';}

	$out = ($a<0)?'minus ':'';

	$a = abs($a);
	for($i=count($bits_a); $i>0; $i--){
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
	if ($a){
		$out .= $bits_c[$a-1];
	}
	return $out;
}

function winner_method($type,$output_type) {
	
	$output = "";
	
	if ($output_type == 1) {
		switch ($type) {
			case 0: $output = "By Table";
			break;
			case 1: $output = "By Category";
			break;
			case 3: $output = "By Sub-Category";
			break;
		}
	}
	
	if ($output_type == 2) {
		switch ($type) {
			case 0: $output = "<p>Your chosen award structure is to award places <strong>by table</strong>. Select the award places for the table as a whole below.</p>";
			break;
			case 1: $output = "<p>Your chosen award structure is to award places <strong>by category</strong>. Select the award places for each overall category below (there may be more than one at this table).</p>";
			break;
			case 3: $output = "<p>Your chosen award structure is to award places <strong>by sub-category</strong>. Select the award places for each sub-category below (there may be more than one at this table).</p>";
			break;
		}
	}
	return $output;
}


function table_exists($table_name) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	// taken from http://snippets.dzone.com/posts/show/3369
	$query_exists = "SHOW TABLES LIKE '".$table_name."'";
	$exists = mysql_query($query_exists, $brewing) or die(mysql_error());
	$totalRows_exists = mysql_num_rows($exists);
	if ($totalRows_exists > 0) return TRUE;
	else return FALSE;
}


function table_assignments($uid,$method,$time_zone,$date_format,$time_format,$method2=0) {
	
	// Gather and output the judging or stewarding assignments for a user
	
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$output = "";
	
	$query_table_assignments = sprintf("SELECT assignTable FROM %s WHERE bid='%s' AND assignment='%s'",$prefix."judging_assignments",$uid,$method);
	$table_assignments = mysql_query($query_table_assignments, $brewing) or die(mysql_error());
	$row_table_assignments = mysql_fetch_assoc($table_assignments);
	$totalRows_table_assignments = mysql_num_rows($table_assignments);
	
	if ($totalRows_table_assignments > 0) {
		do {
			$table_info = explode("^",get_table_info(1,"basic",$row_table_assignments['assignTable'],"default","default"));
			$location = explode("^",get_table_info($table_info[2],"location",$row_table_assignments['assignTable'],"default","default"));
			//$output .= "\t<table class='dataTableCompact' style='margin-left: -5px'>\n";
			$output .= "\t\t<tr>\n";
			if ($method2 == 0) {
				$output .= "\t\t\t<td class='dataList'>".$location[2]."</td>\n";
				$output .= "\t\t\t<td class='dataList'>".getTimeZoneDateTime($time_zone, $location[0], $date_format,  $time_format, "long", "date-time")."</td>\n";
				$output .= "\t\t\t<td class='dataList'>Table #".$table_info[0]." - ".$table_info[1]."</td>\n";
			}
			else {
				$output .= "\t\t\t<td class='dataList bdr1B'>".$location[2]."</td>\n";
				$output .= "\t\t\t<td class='dataList bdr1B'>".getTimeZoneDateTime($time_zone, $location[0], $date_format,  $time_format, "long", "date-time")."</td>\n";
				$output .= "\t\t\t<td class='dataList bdr1B'>Table #".$table_info[0]." - ".$table_info[1]."</td>\n";	
				
			}
			$output .= "\t\t</tr>\n";
			//$output .= "\t</table>\n";
			
		} while ($row_table_assignments = mysql_fetch_assoc($table_assignments));
	}
	return $output;
}

function available_at_location($location,$role,$round) {
	// Returnds the number of judges available per location/date
	// Takes into account assignments in the judging_assignments table
	// and returns a total number available less those who have been 
	// assigned to the location and round.
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	if ($role == "judges") $query_available = sprintf("SELECT brewerJudgeLocation FROM %s WHERE brewerJudgeLocation IS NOT NULL", $prefix."brewer");
	if ($role == "stewards") $query_available = sprintf("SELECT brewerStewardLocation FROM %s WHERE brewerStewardLocation IS NOT NULL", $prefix."brewer");
	$available = mysql_query($query_available, $brewing) or die(mysql_error());
	$row_available = mysql_fetch_assoc($available);
	$totalRows_available = mysql_num_rows($available);
	
	$return = "";
	
	do {
		if ($role == "judges") $available_location = explode(",",$row_available['brewerJudgeLocation']); 
		if ($role == "stewards") $available_location =  explode(",",$row_available['brewerStewardLocation']);
		if (in_array("Y-".$location,$available_location)) $count[] = 1; else $count[] = 0;
		
		//$return .= $row_available['brewerJudgeLocation']."<br>";
		
	} while ($row_available = mysql_fetch_assoc($available));
	
	$return = array_sum($count);
	//$return = print_r;
	return $return;
}

function str_osplit($string, $offset){
    return isset($string[$offset]) ? array(substr($string, 0, $offset), substr($string, $offset)) : false;
 }
 
function readable_judging_number($style,$number) {
	/*
	if (strlen($number) == 5) {
		if ($style < 10) $number = "0".$number;
		else $number = $number;
		$judging_number = str_osplit($number, 2);
		return $judging_number[0]."-".$judging_number[1];
	}
	*/
	
	// NHC and Barcode usage
	if (strlen($number) == 6) {
		return $number;
	}
	
	if (strlen($number) == 5) {
		$judging_number = str_osplit($number, 2);
		return sprintf("%06s",$judging_number[0]."-".$judging_number[1]);
	}
	
	if (strlen($number) == 4) {
		$judging_number = str_osplit($number, 1);
		return sprintf("%06s",$judging_number[0]."-".$judging_number[1]);
	}
}

function dropoff_location($input) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_dropoff = sprintf("SELECT dropLocationName FROM %s WHERE id='%s'",$prefix."drop_off",$input);
	$dropoff = mysql_query($query_dropoff, $brewing) or die(mysql_error());
	$row_dropoff = mysql_fetch_assoc($dropoff);
	if ($input > 0)	return $row_dropoff['dropLocationName'];
	else return "Shipping Entries";
}


function judge_steward_availability($input,$method) {
	if (($input == "Y-") || ($input == "")) {
		if ($method == "1") $return = "No availability defined.";
		else $return = "";
	}
	else {
		$a = explode(",",$input);
		arsort($a);
		foreach ($a as $value) {
		if ($value != "") {
			$b = substr($value, 2);
			$c = substr($value, 0, 1);
				if ($c == "Y") {
				require(CONFIG.'config.php');
				$query_location = sprintf("SELECT * FROM %s WHERE id='%s'", $prefix."judging_locations", $b);
				$location = mysql_query($query_location, $brewing) or die(mysql_error());
				$row_location = mysql_fetch_assoc($location);
				if (!empty($row_location['judgingLocName'])) {
					$return .= $row_location['judgingLocName']." ";
					//$return .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_location['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt");
					if ($method == "1") $return .= "<br>";
					elseif ($method == "2") $return .= " | ";
					else $return .= " ";
					}
					else $return .= "";
				}
			}
		}
	}
	if ($method == "1")	return rtrim($return,"<br>");
	elseif ($method == "2") return rtrim($return," | ");
	else return $return;
}

function judge_entries($uid,$method) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_judge_entries = sprintf("SELECT brewStyle, brewCategory, brewSubCategory, brewCategorySort FROM %s WHERE brewBrewerID='%s' ORDER BY brewCategorySort ASC",$prefix."brewing",$uid);
	$judge_entries = mysql_query($query_judge_entries, $brewing) or die(mysql_error());
	$row_judge_entries = mysql_fetch_assoc($judge_entries);
	$totalRows_judge_entries = mysql_num_rows($judge_entries);
	
	if ($totalRows_judge_entries > 0) {
		do { 
			if ($method == 1) $entries[] = "<a href='".$base_url."index.php?section=admin&amp;go=entries&amp;filter=".$row_judge_entries['brewCategorySort']."' title='View the ".$row_judge_entries['brewStyle']." Entries'>".$row_judge_entries['brewCategory'].$row_judge_entries['brewSubCategory']."</a>"; 
			else $entries[] = $row_judge_entries['brewCategory'].$row_judge_entries['brewSubCategory'];
			} 
			while ($row_judge_entries = mysql_fetch_assoc($judge_entries));
		$return = implode(", ",$entries);
		$return = rtrim($return,", ");
	}
	else $return = "";
	return $return;
}

function judging_winner_display($delay) {
			include(CONFIG.'config.php');
			mysql_select_db($database, $brewing);
			$query_check = sprintf("SELECT judgingDate FROM %s ORDER BY judgingDate DESC LIMIT 1", $prefix."judging_locations");
			$check = mysql_query($query_check, $brewing) or die(mysql_error());
			$row_check = mysql_fetch_assoc($check);
			$today = strtotime("now");
			$r = $row_check['judgingDate'] + $delay;
			if ($r > $today) return FALSE; else return TRUE;
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
	
	include(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_check_tables = sprintf("SELECT COUNT(*) AS 'count' FROM %s", $prefix."judging_tables");
	$check_tables = mysql_query($query_check_tables, $brewing) or die(mysql_error());
	$row_check_tables = mysql_fetch_assoc($check_tables);
	
	$query_check_received = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewReceived='1'", $prefix."brewing");
	$check_received = mysql_query($query_check_received, $brewing) or die(mysql_error());
	$row_check_received = mysql_fetch_assoc($check_received);
	
	$query_check_flights = sprintf("SELECT COUNT(*) AS 'count' FROM %s", $prefix."judging_flights");
	$check_flights = mysql_query($query_check_flights, $brewing) or die(mysql_error());
	$row_check_flights = mysql_fetch_assoc($check_flights);
	
	if (($row_check_received['count'] > 0) && ($row_check_flights['count'] > 0) && ($row_check_tables['count'] > 0) && ($row_check_received['count'] == $row_check_flights['count'])) return TRUE;
	if (($row_check_received['count'] > 0) && ($row_check_flights['count'] > 0) && ($row_check_tables['count'] > 0) && ($row_check_received['count'] != $row_check_flights['count'])) return FALSE; 

}

function get_archive_count($table) {
	include(CONFIG.'config.php'); 	
	mysql_select_db($database, $brewing);
	$query_archive_count = "SELECT COUNT(*) as 'count' FROM `$table`";
	$archive_count = mysql_query($query_archive_count, $brewing) or die(mysql_error());
	$row_archive_count = mysql_fetch_assoc($archive_count);
	return $row_archive_count['count'];
}

function number_pad($number,$n) {
	return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
}

	
function open_or_closed($now,$date1,$date2) {
		// First date has not passed yet
		if ($now < $date1) $output = "0";
		
		// First date has passed, but second has not
		elseif (($now >= $date1) && ($now <= $date2)) $output = "1";
		
		// Both dates have passed
		else $output = "2";
		
		return $output;
}

function open_limit($total_entries,$limit,$registration_open) {
	// Check to see if the limit of entries has been reached
	if ($limit != "") {
		if (($total_entries >= $limit) && ($registration_open == "1")) return TRUE;
		else return FALSE;
	}
	else return FALSE;
}

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
	
		include(CONFIG.'config.php');
		$query_check = sprintf("SELECT * FROM %s WHERE brewStyleActive='Y' AND brewStyleGroup > '28' AND brewStyleReqSpec = '1'", $prefix."styles");
		$check = mysql_query($query_check, $brewing) or die(mysql_error());
		$row_check = mysql_fetch_assoc($check);
		$totalRows_check = mysql_num_rows($check);
	
		if ($totalRows_check > 0) {
			do { 
				$style_special = ltrim($row_check['brewStyleGroup'],"0");
				$special_required[] = $style_special."-".$row_check['brewStyleNum']; 
			}  while ($row_check = mysql_fetch_assoc($check));
			
			$trimmed = ltrim($msg,"1-");
			if (in_array($trimmed,$special_required)) return TRUE;
		}
	
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

function user_check($user_name) {
	
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_userCheck = sprintf("SELECT * FROM %s WHERE user_name = '%s'",$prefix."users",$user_name);
	$userCheck = mysql_query($query_userCheck, $brewing) or die(mysql_error());
	$row_userCheck = mysql_fetch_assoc($userCheck);
	$totalRows_userCheck = mysql_num_rows($userCheck);
	//$return = $query_userCheck;
	$return = $totalRows_userCheck."^".$row_userCheck['userQuestion']."^".$row_userCheck['id'];
	
	return $return;
	
}

?>