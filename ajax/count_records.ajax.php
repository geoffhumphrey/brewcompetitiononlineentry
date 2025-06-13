<?php

ob_start();

// Set headers to prevent caching and specify JSON response
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

require('../paths.php');
require(CONFIG.'bootstrap.php');

ini_set('display_errors', 0); // Change to 0 for prod; change to 1 for testing.
ini_set('display_startup_errors', 0); // Change to 0 for prod; change to 1 for testing.
error_reporting(0); // Change to error_reporting(0) for prod; change to E_ALL for testing.

$p1 = "default";
$c1 = "default";
$p2 = "default";
$c2 = "default";
$p3 = "default";
$c3 = "default";
if (isset($_GET['p1'])) $p1 = sterilize($_GET['p1']);
if (isset($_GET['c1'])) $c1 = sterilize($_GET['c1']);
if (isset($_GET['p2'])) $p2 = sterilize($_GET['p2']);
if (isset($_GET['c2'])) $c2 = sterilize($_GET['c2']);
if (isset($_GET['p3'])) $p3 = sterilize($_GET['p3']);
if (isset($_GET['c3'])) $c3 = sterilize($_GET['c3']);

$response = array(
    "success" => false,
    "count" => 0,
    "message" => ''
);

if ((isset($_SESSION['session_set_'.$prefix_session])) && ($section != "default")) {

    try {

        $db_table = $prefix.$section;
        $do_query = TRUE;
        $no_query_value = "";
        $query_count = "";

        // For evals only
        if ($section == "evaluation") {

            if ($p1 == "eid") {
                if ($c1 == "table") $query_count = sprintf("SELECT COUNT(DISTINCT `eid`) as 'count' FROM %s WHERE %s='%s'",$db_table,$p2,$c2);
                else $query_count = sprintf("SELECT COUNT(DISTINCT `eid`) as 'count' FROM %s",$db_table);
            }
            else $query_count = sprintf("SELECT COUNT(*) as 'count' FROM %s",$db_table);

            if (($p1 != "default") && ($p1 != "eid") && ($c1 != "default")) {
                
                $query_count .= sprintf(" WHERE %s='%s'",$p1,$c1);
                if (($p2 != "default") && ($c2 != "default")) $query_count .= sprintf(" AND %s='%s'",$p2,$c2);
                if (($p3 != "default") && ($c3 != "default")) $query_count .= sprintf(" AND %s='%s'",$p3,$c3);
            
            }

        }

        elseif ($section == "brewing") {

            if ($p1 == "total-fees") {
                $do_query = FALSE;
                $no_query_value = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], "default", "default", $_SESSION['comp_id']);
                $no_query_value = number_format($no_query_value,2);
            }

            elseif ($p1 == "total-fees-paid") {
                $do_query = FALSE;
                $no_query_value = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], "default", "default", $_SESSION['comp_id']);
                $no_query_value = number_format($no_query_value,2);
            }

            else $query_count = sprintf("SELECT COUNT(*) as 'count' FROM %s",$db_table);

            if (($p1 != "default") && ($c1 != "default")) {
                
                $query_count .= sprintf(" WHERE %s='%s'",$p1,$c1);
                if (($p2 != "default") && ($c2 != "default")) $query_count .= sprintf(" AND %s='%s'",$p2,$c2);
                if (($p3 != "default") && ($c3 != "default")) $query_count .= sprintf(" AND %s='%s'",$p3,$c3);
            
            }

        }

        else {

            $query_count = sprintf("SELECT COUNT(*) as 'count' FROM %s",$db_table);

            if (($p1 != "default") && ($c1 != "default")) {
                
                $query_count .= sprintf(" WHERE %s='%s'",$p1,$c1);
                if (($p2 != "default") && ($c2 != "default")) $query_count .= sprintf(" AND %s='%s'",$p2,$c2);
                if (($p3 != "default") && ($c3 != "default")) $query_count .= sprintf(" AND %s='%s'",$p3,$c3);
            
            }

        }
        
        if (($do_query) && (!empty($query_count))) {

            $count = mysqli_query($connection,$query_count);
            $row_count = mysqli_fetch_assoc($count);

            if ($row_count) {

                $response['success'] = true;
                $response['count'] = $row_count['count'];
                $response['updated'] = sprintf("%s %s", $current_date_display_short, $current_time);

            }

            else {

                throw new Exception("Query failed: " . $connnection->error);

            }

        }

        else {

            if (empty($no_query_value)) {

                throw new Exception("Fetch failed: " . $connnection->error);

            }

            else {

                $response['success'] = true;
                $response['count'] = $no_query_value;
                $response['updated'] = sprintf("%s %s", $current_date_display_short, $current_time);
                
            }

        }

    
    } catch (Exception $e) {
        
        $response['message'] = $e->getMessage();
    
    }

}

else {
    $response['message'] = "Not Authorized.";
}

// Return the response as JSON
echo json_encode($response);

?>