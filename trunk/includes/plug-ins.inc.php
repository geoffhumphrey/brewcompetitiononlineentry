<?php
$phpVersion = phpversion();
$today = date('Y-m-d');
$agent = $_SERVER['HTTP_USER_AGENT']; 

$reg_open = $row_contest_info['contestRegistrationOpen'];
$reg_deadline = $row_contest_info['contestRegistrationDeadline'];

$ent_open = $row_contest_info['contestEntryOpen'];
$ent_deadline = $row_contest_info['contestEntDeadline'];

mysql_select_db($database, $brewing);
$query_check = "SELECT * FROM judging";
$check = mysql_query($query_check, $brewing) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
do {
 	if ($row_check['judgingDate'] > $today) $newDate[] = 1; 
 	else $newDate[] = 0;
	} while ($row_check = mysql_fetch_assoc($check));
	if (in_array(1, $newDate)) $judgingDateReturn = "false"; else $judgingDateReturn = "true";

function greaterDate($start_date,$end_date)
{
  $start = new Datetime($start_date);
  $end = new Datetime($end_date);
  if ($start > $end)
   return 1;
  else
   return 0;
}

function lesserDate($start_date,$end_date)
{
  $start = new Datetime($start_date);
  $end = new Datetime($end_date);
  if ($start < $end)
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
list($year, $month, $day) = explode("-", $date);
if ($month == "01" ) { $month = "January "; }
if ($month == "02" ) { $month = "February "; }
if ($month == "03" ) { $month = "March "; }
if ($month == "04" ) { $month = "April "; }
if ($month == "05" ) { $month = "May "; }
if ($month == "06" ) { $month = "June "; }
if ($month == "07" ) { $month = "July "; }
if ($month == "08" ) { $month = "August "; }
if ($month == "09" ) { $month = "September "; }
if ($month == "10" ) { $month = "October "; }
if ($month == "11" ) { $month = "November "; }
if ($month == "12" ) { $month = "December "; }
$day = ltrim($day, "0");
/* 
Future release: add logic to check if user preferences 
dictate "American" English date formats vs. "British" 
English date formats
*/
$date = "$month $day, $year";
return $date;
	}
	
if ($func == 3)	{ //output conversion
list($year, $month, $day) = explode("-", $date);
if ($month == "01" ) { $month = "Jan"; }
if ($month == "02" ) { $month = "Feb"; }
if ($month == "03" ) { $month = "Mar"; }
if ($month == "04" ) { $month = "Apr"; }
if ($month == "05" ) { $month = "May"; }
if ($month == "06" ) { $month = "Jun"; }
if ($month == "07" ) { $month = "Jul"; }
if ($month == "08" ) { $month = "Aug"; }
if ($month == "09" ) { $month = "Sep"; }
if ($month == "10" ) { $month = "Oct"; }
if ($month == "11" ) { $month = "Nov"; }
if ($month == "12" ) { $month = "Dec"; }
$day = ltrim($day, "0");
/* 
Future release: add logic to check if user preferences 
dictate "American" English date formats vs. "British" 
English date formats
*/
$date = "$month $day, $year";
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

# Pagination

function paginate($display, $pg, $total) {
  /* make sure pagination doesn't interfere with other query string variables */
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
    
  /* find out how many pages we have */
  $pages = ($total <= $display) ? 1 : ceil($total / $display);
    
  /* create the links */
  $first = '<span id="sortable_first" class="first paginate_button"><a href="'.$_SERVER['PHP_SELF'].$query_str.'1">First</a></span>';
  $prev =  '<span id="sortable_previous" class="previous paginate_button"><a href="'.$_SERVER['PHP_SELF'].$query_str.($pg - 1).'">Previous</a></span>';
  $next =  '<span id="sortable_next" class="next paginate_button"><a href="'.$_SERVER['PHP_SELF'].$query_str.($pg + 1).'">Next</a></span>';
  $last =  '<span id="sortable_last" class="last paginate_button"><a href="'.$_SERVER['PHP_SELF'].$query_str.$pages.'">Last</a></span>';
   
  /* display opening navigation */
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
    
  /* display ending navigation */
  echo ($pg < $pages) ? "$next$last" : '<span id="sortable_next" class="next paginate_button">Next</span><span id="sortable_last" class="last paginate_button">Last</span>';
  echo '</div>';
}

if (($section == "admin") && ($go == "entries") || ($section == "pay")) {
	$entry_fee = $row_contest_info['contestEntryFee']; // regular entry fee
	$entry_fee_discount = $row_contest_info['contestEntryFee2']; // price of each entry after entry threshold for discount is met
	$discount = $row_contest_info['contestEntryFeeDiscount']; // Y or N - is there a discount applied after a certain amount of entries?
	$entry_discount_number = $row_contest_info['contestEntryFeeDiscountNum']; // Minimum number of entries before discount
	$cap_no = $row_contest_info['contestEntryCap'];
	
	function total_fees($bid, $entry_fee, $entry_fee_discount, $discount, $entry_discount_number, $cap_no) {
		include ('Connections/config.php');
		
		if ($bid == "default") {
			
			mysql_select_db($database, $brewing);
			$query_users = "SELECT id,user_name FROM users";
			$users = mysql_query($query_users, $brewing) or die(mysql_error());
			$row_users = mysql_fetch_assoc($users);
			$totalRows_users = mysql_num_rows($users);
	
			do { $d[] = $row_users['id']; } while ($row_users = mysql_fetch_assoc($users));
			sort($d);
			
			foreach (array_unique($d) as $value) {
			
			// Get each entrant's number of entries
			mysql_select_db($database, $brewing);
			$query_entries = sprintf("SELECT brewBrewerID FROM brewing WHERE brewBrewerID='%s'",$value);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			$totalRows_entries = mysql_num_rows($entries);
		
			if ($cap_no > 0) { $cap = "Y";  $cap_total = $cap_no;  }
			else { $cap = "N"; $cap_total = "0"; }
			//echo "Query: ".$query_entries."<br>";
			//echo "Total Entries: ".$totalRows_entries."<br>";
			
	
			// Calculate the total entry fees taking into account any discounts after prescribed number of entries
			if ($totalRows_entries > 0) {
				if ($discount == "Y") $total = ((($totalRows_entries - $entry_discount_number) * $entry_fee_discount) + ($entry_discount_number * $entry_fee));
				else $total = $totalRows_entries * $entry_fee; 
				//echo "Total: ".$total."<br>";
				if (($cap == "N") || (($cap == "Y") && ($total < $cap_total))) $total_calc = $total;
				else $total_calc = $cap_total;
				} else $total_calc = 0;
			$total_array[] = $total_calc;
			}
		}
		else {
			$query_contest_info = "SELECT * FROM contest_info WHERE id=1";
			$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
			$row_contest_info = mysql_fetch_assoc($contest_info);

			if ($cap_no > 0) { $cap = "Y"; $cap_total = $row_contest_info['contestEntryCap']; }
			else { $cap = "N"; $cap_total = "0"; }
			
			$query_entries = sprintf("SELECT brewBrewerID FROM brewing WHERE brewBrewerID='%s'",$bid);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			$totalRows_entries = mysql_num_rows($entries);
			
			// Calculate the total entry fees taking into account any discounts after prescribed number of entries
			if ($discount == "Y") $total = ((($totalRows_entries - $entry_discount_number) * $entry_fee_discount) + ($entry_discount_number * $entry_fee));
			else $total = $totalRows_entries * $entry_fee; 
			//echo "Total: ".$total."<br>";
			if ($totalRows_entries > 0) {
				if (($cap == "N") || (($cap == "Y") && ($total < $cap_total))) $total_calc = $total;
				else $total_calc = $cap_total;
				} else $total_calc = 0;
			$total_array[] = $total_calc;
			}
   		//print_r($total_array);
		$total_fees = array_sum($total_array);
   		return $total_fees;
		} // end function
		

	function total_fees_paid($bid, $entry_fee, $entry_fee_discount, $discount, $entry_discount_number, $cap_no) {
		include ('Connections/config.php');
		if ($bid == "default") {
			
			mysql_select_db($database, $brewing);
			$query_users = "SELECT id,user_name FROM users";
			$users = mysql_query($query_users, $brewing) or die(mysql_error());
			$row_users = mysql_fetch_assoc($users);
			$totalRows_users = mysql_num_rows($users);
	
			do { $d[] = $row_users['id']; } while ($row_users = mysql_fetch_assoc($users));
			sort($d);
			
			foreach (array_unique($d) as $value) {
			// Get each entrant's number of entries
			mysql_select_db($database, $brewing);
			//if ($paid == "N") $query_entries = sprintf("SELECT brewBrewerID FROM brewing WHERE brewBrewerID='%s'",$value);
			$query_entries = sprintf("SELECT brewBrewerID,brewPaid FROM brewing WHERE brewBrewerID='%s' AND brewPaid='%s'",$value,"Y");
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			$totalRows_entries = mysql_num_rows($entries);
				
			if ($cap_no > 0) { $cap = "Y"; $cap_total = $cap_no; }
			else { $cap = "N"; $cap_total = "0"; }
			//echo "Query: ".$query_entries."<br>";
			//echo "Total Entries: ".$totalRows_entries."<br>";
			//echo $cap."<br>";
	
			// Calculate the total entry fees taking into account any discounts after prescribed number of entries
			if ($totalRows_entries > 0) {
				if ($discount == "Y") $total = ((($totalRows_entries - $entry_discount_number) * $entry_fee_discount) + ($entry_discount_number * $entry_fee));
				else $total = $totalRows_entries * $entry_fee; 
				//echo "Total: ".$total."<br>";
				if (($cap == "N") || (($cap == "Y") && ($total < $cap_total))) $total_calc = $total;
				else $total_calc = $cap_total;
			} else $total_calc = 0;
			$total_array[] = $total_calc;
			}
		}
		else {
			$query_contest_info = "SELECT * FROM contest_info WHERE id=1";
			$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
			$row_contest_info = mysql_fetch_assoc($contest_info);

			if ($cap_no > 0) { $cap = "Y"; $cap_total = $cap_no; }
			else { $cap = "N"; $cap_total = "0"; }
			
			$query_entries = sprintf("SELECT brewBrewerID,brewPaid FROM brewing WHERE brewBrewerID='%s' AND brewPaid='%s'",$bid,"Y");
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			$totalRows_entries = mysql_num_rows($entries);
			
			// Calculate the total entry fees taking into account any discounts after prescribed number of entries
			if ($discount == "Y") $total = ((($totalRows_entries - $entry_discount_number) * $entry_fee_discount) + ($entry_discount_number * $entry_fee));
			else $total = $totalRows_entries * $entry_fee; 
			if ($totalRows_entries > 0) {
				if (($cap == "N") || (($cap == "Y") && ($total < $cap_total))) $total_calc = $total;
				else $total_calc = $cap_total;
				} else $total_calc = 0;
			$total_array[] = $total_calc;
			}
   		//print_r($total_array);
		$total_fees = array_sum($total_array);
   		return $total_fees;
		} // end function

	function total_fees_to_pay($bid, $entry_fee, $entry_fee_discount, $discount, $entry_discount_number, $cap_no) {
		include ('Connections/config.php');
		
		if ($bid == "default") {
			
			mysql_select_db($database, $brewing);
			$query_users = "SELECT id,user_name FROM users";
			$users = mysql_query($query_users, $brewing) or die(mysql_error());
			$row_users = mysql_fetch_assoc($users);
			$totalRows_users = mysql_num_rows($users);
	
			do { $d[] = $row_users['id']; } while ($row_users = mysql_fetch_assoc($users));
			sort($d);
			
			foreach (array_unique($d) as $value) {
			
			// Get each entrant's number of entries
			mysql_select_db($database, $brewing);
			$query_entries = sprintf("SELECT brewBrewerID FROM brewing WHERE brewBrewerID='%s' AND NOT brewPaid='Y'",$value);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			$totalRows_entries = mysql_num_rows($entries);
			
			$query_paid = sprintf("SELECT brewBrewerID FROM brewing WHERE brewBrewerID='%s' AND brewPaid='Y'",$value);
			$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
			$row_paid = mysql_fetch_assoc($paid);
			$totalRows_paid = mysql_num_rows($paid);
		
			if ($cap_no > 0) { $cap = "Y"; $cap_total = $cap_no; }
			else { $cap = "N"; $cap_total = "0"; }
	
			// Calculate the total entry fees taking into account any discounts after prescribed number of entries
			if ($totalRows_entries > 0) {
				if (($discount == "Y") && ($totalRows_paid < $entry_discount_number)) $total = ((($totalRows_entries - $entry_discount_number) * $entry_fee_discount) + ($entry_discount_number * $entry_fee));
				elseif (($discount == "Y") && ($totalRows_paid > $entry_discount_number)) $total = $totalRows_entries * $entry_fee_discount;
				else $total = $totalRows_entries * $entry_fee; 
				//echo "Total: ".$total."<br>"; 
				if (($cap == "N") || (($cap == "Y") && ($total < $cap_total))) $total_calc = $total;
				else $total_calc = $cap_total;
				} else $total_calc = 0;
			$total_array[] = $total_calc;
			}
		}
		else {
			$query_contest_info = "SELECT * FROM contest_info WHERE id=1";
			$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
			$row_contest_info = mysql_fetch_assoc($contest_info);

			if ($cap_no > 0) { $cap = "Y"; $cap_total = $cap_no; }
			else { $cap = "N"; $cap_total = "0"; }
			
			$query_entries = sprintf("SELECT brewBrewerID FROM brewing WHERE brewBrewerID='%s' AND NOT brewPaid='Y'",$bid);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			$totalRows_entries = mysql_num_rows($entries);
			
			$query_paid = sprintf("SELECT brewBrewerID FROM brewing WHERE brewBrewerID='%s' AND brewPaid='Y'",$bid);
			$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
			$row_paid = mysql_fetch_assoc($paid);
			$totalRows_paid = mysql_num_rows($paid);
			
			// Calculate the total entry fees taking into account any discounts after prescribed number of entries
			if (($discount == "Y") && ($totalRows_paid < $entry_discount_number)) $total = ((($totalRows_entries - $entry_discount_number) * $entry_fee_discount) + ($entry_discount_number * $entry_fee));
				elseif (($discount == "Y") && ($totalRows_paid > $entry_discount_number)) $total = $totalRows_entries * $entry_fee_discount;
				else $total = $totalRows_entries * $entry_fee; 
				//echo "Total: ".$total."<br>";
			if ($totalRows_entries > 0) {
				if (($cap == "N") || (($cap == "Y") && ($total < $cap_total))) $total_calc = $total;
				else $total_calc = $cap_total;
				//echo "Total Caluclated: ".$total_calc."<br>";
				} else $total_calc = 0;
			$total_array[] = $total_calc;
			}
   		//print_r($total_array);
		$total_fees_paid = array_sum($total_array);
   		return $total_fees_paid;
		} // end function

	$total_entry_fees = total_fees($bid, $entry_fee, $entry_fee_discount, $discount, $entry_discount_number, $cap_no); 
	$total_paid_entry_fees = total_fees_paid($bid, $entry_fee, $entry_fee_discount, $discount, $entry_discount_number, $cap_no);
	$total_to_pay = total_fees_to_pay($bid, $entry_fee, $entry_fee_discount, $discount, $entry_discount_number, $cap_no); 
}





function unpaid_fees($total_not_paid, $discount_amt, $entry_fee, $entry_fee_disc, $cap) {
	switch($discount) {
		case "N": 
			$entry_total = $total_not_paid * $entry_fee;
		break;
		case "Y":
			if ($total_not_paid > $discount_amt) {
				$reg_fee = $discount_amt * $entry_fee; // 
				$disc_fee = ($total_not_paid - $discount_amt) * $entry_fee_disc;
				$entry_subtotal = $reg_fee + $disc_fee;
				}
			if ($total_not_paid <= $discount_amt) {
				if ($total_not_paid > 0) $entry_total = $total_not_paid * $entry_fee;
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

?>