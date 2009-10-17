<?php
$phpVersion = phpversion();

function greaterDate($start_date,$end_date)
{
  $start = new Datetime($start_date);
  $end = new Datetime($end_date);
  if ($start > $end)
   return 1;
  else
   return 0;
}

$color = "#eeeeee";
$color1 = "#e0e0e0";
$color2 = "#eeeeee";


// ---------------------------- Temperature, Weight, and Volume Conversion ----------------------------------

function tempconvert($temp,$t) { // $t = desired output, defined at function call
if ($t == "F") { // Celsius to F if source is C
	$tcon = (($temp - 32) / 1.8); 
    return round ($tcon, 1);
	}
	
if ($t == "C") { // F to Celsius
	$tcon = (($temp - 32) * (5/9)); 
    return round ($tcon, 1);
	}
}

function weightconvert($weight,$w) { // $w = desired output, defined at function call
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

function volumeconvert($volume,$v) {  // $v = desired output, defined at function call
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

// ---------------------------- Date Conversion -----------------------------------------
// http://www.phpbuilder.com/annotate/message.php3?id=1031006
function dateconvert($date,$func) {
if ($func == 1)	{ //insert conversion
list($day, $month, $year) = split('[/.-]', $date); 
$date = "$year-$month-$day"; 
return $date;
	}
if ($func == 2)	{ //output conversion
list($year, $month, $day) = split('[-.]', $date); 
$date = "$day, $year"; 
if ($month == "01" ) { echo "January "; }
if ($month == "02" ) { echo "February "; }
if ($month == "03" ) { echo "March "; }
if ($month == "04" ) { echo "April "; }
if ($month == "05" ) { echo "May "; }
if ($month == "06" ) { echo "June "; }
if ($month == "07" ) { echo "July "; }
if ($month == "08" ) { echo "August "; }
if ($month == "09" ) { echo "September "; }
if ($month == "10" ) { echo "October "; }
if ($month == "11" ) { echo "November "; }
if ($month == "12" ) { echo "December "; }
return $date;
	}
if ($func == 3)	{ //output conversion
list($year, $month, $day) = split('[-.]', $date); 
$date = "$day, $year"; 
if ($month == "01" ) { echo "Jan "; }
if ($month == "02" ) { echo "Feb "; }
if ($month == "03" ) { echo "Mar "; }
if ($month == "04" ) { echo "Apr "; }
if ($month == "05" ) { echo "May "; }
if ($month == "06" ) { echo "Jun "; }
if ($month == "07" ) { echo "Jul "; }
if ($month == "08" ) { echo "Aug "; }
if ($month == "09" ) { echo "Sep "; }
if ($month == "10" ) { echo "Oct "; }
if ($month == "11" ) { echo "Nove "; }
if ($month == "12" ) { echo "Dec "; }
return $date;
	}
}

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

?>