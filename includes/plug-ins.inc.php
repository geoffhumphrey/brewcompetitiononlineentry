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

if (($section == "admin") && ($go == "entries") || ($section == "pay") || ($section == "list")) {
	if (($section == "list") || ($section == "pay")) $bid = $row_brewer['uid'];
	$entry_fee = $row_contest_info['contestEntryFee']; // regular entry fee
	$entry_fee_discount = $row_contest_info['contestEntryFee2']; // price of each entry after entry threshold for discount is met
	$discount = $row_contest_info['contestEntryFeeDiscount']; // Y or N - is there a discount applied after a certain amount of entries?
	$entry_discount_number = $row_contest_info['contestEntryFeeDiscountNum']; // Minimum number of entries before discount
	$cap_no = $row_contest_info['contestEntryCap'];
	
	function total_fees($bid, $entry_fee, $entry_fee_discount, $discount, $entry_discount_number, $cap_no, $filter) {
		include ('Connections/config.php');
		
		if (($bid == "default") && ($filter == "default")) {
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
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM brewing WHERE brewBrewerID='%s'",$value);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];
			mysql_free_result($entries);
			
			//echo $cap_total;
			//echo "Query: ".$query_entries."<br>";
			//echo "Total Entries: ".$totalRows_entries."<br>";
			
			// Calculate the total entry fees taking into account any discounts after prescribed number of entries
			if ($totalRows_entries > 0) {
				if ($discount == "Y") {
				 	$a = $entry_discount_number * $entry_fee;
				 	$b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
					$c = $a + $b;
				 	$d = $totalRows_entries * $entry_fee;
				 	if ($totalRows_entries <= $entry_discount_number) $total = $d;
				 	if ($totalRows_entries > $entry_discount_number) $total = $c;
				 }
				else $total = $totalRows_entries * $entry_fee;
				
				if ($cap_no > 0) {
					if ($total < $cap_no) $total_calc = $total;
					if ($total >= $cap_no) $total_calc = $cap_no;
					}
				else $total_calc = $total;
				
				} 
				else $total_calc = 0;
			//print_r($total_array);
			$total_array[] = $total_calc;
			}
		}
		if (($bid != "default") && ($filter == "default")) {		
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM brewing WHERE brewBrewerID='%s'",$bid);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];
			mysql_free_result($entries);
			
			if ($totalRows_entries > 0) {
				if ($discount == "Y") {
				 	$a = $entry_discount_number * $entry_fee;
				 	$b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
					$c = $a + $b;
				 	$d = $totalRows_entries * $entry_fee;
				 	if ($totalRows_entries <= $entry_discount_number) $total = $d;
				 	if ($totalRows_entries > $entry_discount_number) $total = $c;
				 }
				else $total = $totalRows_entries * $entry_fee;
				
				if ($cap_no > 0) {
					if ($total < $cap_no) $total_calc = $total;
					if ($total >= $cap_no) $total_calc = $cap_no;
					}
				else $total_calc = $total;
			
			} else $total_calc = 0;
			$total_array[] = $total_calc;
		}
		
		if (($bid == "default") && ($filter != "default")) {
		
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
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM brewing WHERE brewBrewerID='%s' AND brewCategorySort='%s'",$value, $filter);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];
			mysql_free_result($entries);
			
			//echo $cap_total;
			//echo "Query: ".$query_entries."<br>";
			//echo "Total Entries: ".$totalRows_entries."<br>";
			
			// Calculate the total entry fees taking into account any discounts after prescribed number of entries
			if ($totalRows_entries > 0) {
				if ($discount == "Y") {
				 	$a = $entry_discount_number * $entry_fee;
				 	$b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
					$c = $a + $b;
				 	$d = $totalRows_entries * $entry_fee;
				 	if ($totalRows_entries <= $entry_discount_number) $total = $d;
				 	if ($totalRows_entries > $entry_discount_number) $total = $c;
				 }
				else $total = $totalRows_entries * $entry_fee;
				
				if ($cap_no > 0) {
					if ($total < $cap_no) $total_calc = $total;
					if ($total >= $cap_no) $total_calc = $cap_no;
					}
				else $total_calc = $total;
				
				} 
				else $total_calc = 0;
			//print_r($total_array);
			$total_array[] = $total_calc;
			}	
		}
		
   		//print_r($total_array);
		$total_fees = array_sum($total_array);
   		return $total_fees;
	} // end function
		

	function total_fees_paid($bid, $entry_fee, $entry_fee_discount, $discount, $entry_discount_number, $cap_no, $filter) {
		include ('Connections/config.php');
		if (($bid == "default") && ($filter == "default")) {
			
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
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM brewing WHERE brewBrewerID='%s'",$value);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];
			mysql_free_result($entries);
			
			$query_not_paid = sprintf("SELECT COUNT(*) as 'count' FROM brewing WHERE brewBrewerID='%s' AND NOT brewPaid='Y'",$value);
			$entries_not_paid = mysql_query($query_not_paid, $brewing) or die(mysql_error());
			$row_entries_not_paid = mysql_fetch_array($entries_not_paid);
			$totalRows_entries_not_paid = $row_entries_not_paid['count'];
			mysql_free_result($entries_not_paid);
			
			$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM brewing WHERE brewBrewerID='%s' AND brewPaid='Y'",$value);
			$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
			$row_paid = mysql_fetch_array($paid);
			$totalRows_paid = $row_paid['count'];
			mysql_free_result($paid);
				
			//echo "Query: ".$query_entries."<br>";
			//echo "Total Entries: ".$totalRows_entries."<br>";
			//echo $cap."<br>";
	
			// Calculate the total entry fees taking into account any discounts after prescribed number of entries
			if ($totalRows_entries > 0) {
				if ($discount == "Y") {
				 	$a = ($entry_discount_number - $totalRows_paid) * $entry_fee;
				 	$b = ($totalRows_not_paid - $entry_discount_number) * $entry_fee_discount;
				 	$c = $a + $b;
				 	$d = ($entry_discount_number * $entry_fee);
				 	$e = (($totalRows_paid - $entry_discount_number) * $entry_fee_discount);
				 	$f = $d + $e;
				 	if (($totalRows_paid < $entry_discount_number) && ($totalRows_entries > $entry_discount_number)) $total = $c;
				 	if ($totalRows_paid < $entry_discount_number) $total = $totalRows_paid * $entry_fee;
				 	if ($totalRows_paid == $entry_discount_number) $total = $entry_discount_number * $entry_fee;
				 	if ($totalRows_paid > $entry_discount_number) $total = $f ;
				 	}
				else $total = $totalRows_paid * $entry_fee;
				
				if ($cap_no > 0) {
					if ($total < $cap_no) $total_calc = $total;
					if ($total >= $cap_no) $total_calc = $cap_no;
					}
				else $total_calc = $total;
				
			}
			else $total_calc = 0;
			$total_array[] = $total_calc;
			}
		}
		if (($bid != "default") && ($filter == "default")) {
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM brewing WHERE brewBrewerID='%s'",$bid);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];
			mysql_free_result($entries);
			
			$query_not_paid = sprintf("SELECT COUNT(*) as 'count' FROM brewing WHERE brewBrewerID='%s' AND NOT brewPaid='Y'",$bid);
			$entries_not_paid = mysql_query($query_not_paid, $brewing) or die(mysql_error());
			$row_entries_not_paid = mysql_fetch_array($entries_not_paid);
			$totalRows_entries_not_paid = $row_entries_not_paid['count'];
			mysql_free_result($entries_not_paid);
			
			$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM brewing WHERE brewBrewerID='%s' AND brewPaid='Y'",$bid);
			$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
			$row_paid = mysql_fetch_array($paid);
			$totalRows_paid = $row_paid['count'];
			mysql_free_result($paid);
			
			// Calculate the total entry fees taking into account any discounts after prescribed number of entries
			if ($totalRows_entries > 0) {
				if ($discount == "Y") {
				 	$a = ($entry_discount_number - $totalRows_paid) * $entry_fee;
				 	$b = ($totalRows_not_paid - $entry_discount_number) * $entry_fee_discount;
				 	$c = $a + $b;
				 	$d = ($entry_discount_number * $entry_fee);
				 	$e = (($totalRows_paid - $entry_discount_number) * $entry_fee_discount);
				 	$f = $d + $e;
				 	if (($totalRows_paid < $entry_discount_number) && ($totalRows_entries > $entry_discount_number)) $total = $c;
				 	if ($totalRows_paid < $entry_discount_number) $total = $totalRows_paid * $entry_fee;
				 	if ($totalRows_paid == $entry_discount_number) $total = $entry_discount_number * $entry_fee;
				 	if ($totalRows_paid > $entry_discount_number) $total = $f ;
				 }
				else $total = $totalRows_paid * $entry_fee;
				
				if ($cap_no > 0) {
					if ($total < $cap_no) $total_calc = $total;
					if ($total >= $cap_no) $total_calc = $cap_no;
					}
				else $total_calc = $total;
			}
			else $total_calc = 0;
			$total_array[] = $total_calc;
		}
		
		if (($bid == "default") && ($filter != "default")) {
			
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
			$query_entries = sprintf("SELECT brewBrewerID FROM brewing WHERE brewBrewerID='%s' AND brewCategorySort='%s'",$value, $filter);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			$totalRows_entries = mysql_num_rows($entries);
			
			$query_not_paid = sprintf("SELECT brewBrewerID FROM brewing WHERE brewBrewerID='%s' AND brewCategorySort='%s' AND NOT brewPaid='Y'",$value, $filter);
			$entries_not_paid = mysql_query($query_not_paid, $brewing) or die(mysql_error());
			$row_entries_not_paid = mysql_fetch_assoc($entries_not_paid);
			$totalRows_entries_not_paid = mysql_num_rows($entries_not_paid);
			
			$query_paid = sprintf("SELECT brewBrewerID FROM brewing WHERE brewBrewerID='%s' AND brewCategorySort='%s' AND brewPaid='Y'",$value, $filter);
			$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
			$row_paid = mysql_fetch_assoc($paid);
			$totalRows_paid = mysql_num_rows($paid);
				
			//echo "Query: ".$query_entries."<br>";
			//echo "Total Entries: ".$totalRows_entries."<br>";
			//echo $cap."<br>";
	
			// Calculate the total entry fees taking into account any discounts after prescribed number of entries
			if ($totalRows_entries > 0) {
				if ($discount == "Y") {
				 	$a = ($entry_discount_number - $totalRows_paid) * $entry_fee;
				 	$b = ($totalRows_not_paid - $entry_discount_number) * $entry_fee_discount;
				 	$c = $a + $b;
				 	$d = ($entry_discount_number * $entry_fee);
				 	$e = (($totalRows_paid - $entry_discount_number) * $entry_fee_discount);
				 	$f = $d + $e;
				 	if (($totalRows_paid < $entry_discount_number) && ($totalRows_entries > $entry_discount_number)) $total = $c;
				 	if ($totalRows_paid < $entry_discount_number) $total = $totalRows_paid * $entry_fee;
				 	if ($totalRows_paid == $entry_discount_number) $total = $entry_discount_number * $entry_fee;
				 	if ($totalRows_paid > $entry_discount_number) $total = $f ;
				 	}
				else $total = $totalRows_paid * $entry_fee;
				
				if ($cap_no > 0) {
					if ($total < $cap_no) $total_calc = $total;
					if ($total >= $cap_no) $total_calc = $cap_no;
					}
				else $total_calc = $total;
			}
			else $total_calc = 0;
			$total_array[] = $total_calc;
			}
		}
   		//print_r($total_array);
		$total_fees = array_sum($total_array);
   		return $total_fees;
		} // end function

	/*
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
				if ($discount == "Y") {
				 if (($totalRows_paid <= $entry_discount_number) && ($totalRows_entries <= $entry_discount_number)) $total = $totalRows_entries * $entry_fee;
				 if (($totalRows_paid <= $entry_discount_number) && ($totalRows_entries > $entry_discount_number)) $total = ((($totalRows_entries - $entry_discount_number) * $entry_fee_discount) + ($entry_discount_number * $entry_fee));
				 if ($totalRows_paid > $entry_discount_number) $total = $totalRows_entries * $entry_fee_discount;
				 }
				else $total = $totalRows_entries * $entry_fee;
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
			
			$query_entries = sprintf("SELECT brewBrewerID FROM brewing WHERE brewBrewerID='%s'",$bid);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			$totalRows_entries = mysql_num_rows($entries);
			
			$query_not_paid = sprintf("SELECT brewBrewerID FROM brewing WHERE brewBrewerID='%s' AND NOT brewPaid='Y'",$bid);
			$entries_not_paid = mysql_query($query_not_paid, $brewing) or die(mysql_error());
			$row_entries_not_paid = mysql_fetch_assoc($entries_not_paid);
			$totalRows_entries_not_paid = mysql_num_rows($entries_not_paid);
			
			$query_paid = sprintf("SELECT brewBrewerID FROM brewing WHERE brewBrewerID='%s' AND brewPaid='Y'",$bid);
			$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
			$row_paid = mysql_fetch_assoc($paid);
			$totalRows_paid = mysql_num_rows($paid);
			
			// Calculate the total entry fees taking into account any discounts after prescribed number of entries
			if ($discount == "Y") {
				 $a = $entry_discount_number * $entry_fee;
				 $b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
				 $c = $a + $b;
				 $d = ($totalRows_entries_not_paid * $entry_fee);
				 $e = ($totalRows_entries_not_paid * $entry_fee_discount);
				 //echo $c;
				 if (($totalRows_paid == 0) && ($totalRows_entries >= $entry_discount_number)) $total = $c;
				 if (($totalRows_paid > 0) && ($totalRows_entries <= $entry_discount_number)) $total = $d;
				 if (($totalRows_paid > 0) && ($totalRows_entries > $entry_discount_number)) $total = $e;
				 }
			else $total = $totalRows_entries * $entry_fee;
				echo $entry_discount_number."<br>";
				echo $totalRows_paid."<br>";
				echo $totalRows_entries."<br>";
				echo "Total: ".$total."<br>";

			if ($totalRows_entries > 0) {
				if (($cap == "N") || (($cap == "Y") && ($total < $cap_total))) $total_calc = $total;
				else $total_calc = $cap_total;
				//echo "Total Caluclated: ".$total_calc."<br>";
				} else $total_calc = 0;
			$total_array[] = $total_calc;
			}
   		//print_r($total_array);
		$total_fees_to_pay = array_sum($total_array);
		//echo $total_fees_paid;
   		return $total_fees_to_pay;
		} // end function
		*/
	$total_entry_fees = total_fees($bid, $entry_fee, $entry_fee_discount, $discount, $entry_discount_number, $cap_no, $filter); 
	$total_paid_entry_fees = total_fees_paid($bid, $entry_fee, $entry_fee_discount, $discount, $entry_discount_number, $cap_no, $filter);
	$total_to_pay = $total_entry_fees - $total_paid_entry_fees; 
	//total_fees_to_pay($bid, $entry_fee, $entry_fee_discount, $discount, $entry_discount_number, $cap_no); 
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


function style_convert($number,$type) {
	switch ($type) {
		case "1": 
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
			default: $style_convert = "Custom Style"; break;
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
		$n = ereg_replace('[^0-9]+', '', $number);
		if ($n > 23) $style_convert = TRUE;
		else {
		switch ($number) {
			case "6D": $style_convert = TRUE; break;
			case "21A": $style_convert = TRUE; break;
			case "22B": $style_convert = TRUE; break;
			case "22C": $style_convert = TRUE; break;
			case "23A": $style_convert = TRUE; break;
			case "25C": $style_convert = TRUE; break;
			case "26A": $style_convert = TRUE; break;
			case "26C": $style_convert = TRUE; break;
			case "27E": $style_convert = TRUE; break;
			case "28B": $style_convert = TRUE; break;
			default: $style_convert = FALSE; break;
		    }
		}
		break;
	}
	return $style_convert;
	
	
	
}

function get_table_info($input,$method,$id) {	
	include ('Connections/config.php');
	mysql_select_db($database, $brewing);
	$query_table = "SELECT * FROM judging_tables";
	if ($id != "default") $query_table .= " WHERE id='$id'"; 
	$table = mysql_query($query_table, $brewing) or die(mysql_error());
	$row_table = mysql_fetch_assoc($table);
	
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
				include ('Connections/config.php');
				mysql_select_db($database, $brewing);
				$query_styles = "SELECT * FROM styles WHERE id='$value'";
				$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
				$row_styles = mysql_fetch_assoc($styles);
				
				$c[] = ltrim($row_styles['brewStyleGroup'].$row_styles['brewStyleNum'],"0").",&nbsp;";
			}
	$d = array($c);
	return $d;
  	}
	
	if ($method == "count_total") {
		$a = explode(",", $row_table['tableStyles']);
			foreach ($a as $value) {
				include ('Connections/config.php');
				mysql_select_db($database, $brewing);
				$query_styles = "SELECT brewStyle FROM styles WHERE id='$value'";
				$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
				$row_styles = mysql_fetch_assoc($styles);
				
				$query_style_count = sprintf("SELECT COUNT(*) as count FROM brewing WHERE brewStyle='%s' AND brewPaid='Y' AND brewReceived='Y'", $row_styles['brewStyle']);
				$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
				$row_style_count = mysql_fetch_assoc($style_count);
				$totalRows_style_count = $row_style_count['count'];
				
				$c[] = $totalRows_style_count ;
			}
	$d = array_sum($c);
	return $d; 
  	}
	
	if ($method == "count") {
	include ('Connections/config.php');
	mysql_select_db($database, $brewing);
	$query_style = "SELECT brewStyle FROM styles WHERE brewStyle='$input'";
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	//echo $query_style."<br>";
	
	$query = sprintf("SELECT COUNT(*) FROM brewing WHERE brewStyle='%s' AND brewPaid='Y' AND brewReceived='Y'",$row_style['brewStyle']);
	$result = mysql_query($query, $brewing) or die(mysql_error());
	$num_rows = mysql_fetch_array($result);
	// echo $query;
	//$num_rows = mysql_num_rows($result);
	return $num_rows[0];
	}
	
	if ($method == "count_scores") {
		$a = explode(",", $row_table['tableStyles']);
			foreach ($a as $value) {
				include ('Connections/config.php');
				mysql_select_db($database, $brewing);
				$query_styles = "SELECT brewStyle FROM styles WHERE id='$value'";
				$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
				$row_styles = mysql_fetch_assoc($styles);
				
				$query_style_count = sprintf("SELECT COUNT(*) as 'count' FROM brewing WHERE brewStyle='%s' AND brewPaid='Y' AND brewReceived='Y'", $row_styles['brewStyle']);
				$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
				$row_style_count = mysql_fetch_assoc($style_count);
				$totalRows_style_count = $row_style_count['count'];
									
				$c[] = $totalRows_style_count;
				
			}
	$query_score_count = sprintf("SELECT COUNT(*) as 'count' FROM judging_scores WHERE scoreTable='%s'", $input);
	$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
	$row_score_count = mysql_fetch_assoc($score_count);
	$totalRows_score_count = $row_score_count['count'];
	$e = array_sum($c);
	if ($e == $totalRows_score_count) return true;
  	}
}

function displayArrayContent($arrayname) {
 	$a = "";
 	while(list($key, $value) = each($arrayname)) {
  		if (is_array($value)) {
   		$a .= displayArrayContent($value);
   		}
  	else $a .= "$value";
  	}
 	$b = rtrim($a, ",&nbsp;");
 	return $b;
}

function relocate($referer) {
	// determine if referrer has any msg=X variables attached
	if (strstr($referer,"&msg")) { 
	$pattern = array("/[0-9]/", "/&msg=/");
	$referer = preg_replace($pattern, "", $referer);
	}
	return $referer;
}
?>