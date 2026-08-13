<?php
declare(strict_types=1);

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

// $section and its associated columns are restricted to a fixed allow-list matching the
// values used by this endpoint's real callers (fetchRecordCount() in admin/sidebar.admin.php,
// index.pub.php, eval/dashboard.eval.php).
$allowed_sections = array("evaluation", "brewing", "updated-display");
$allowed_columns = array(
    "evaluation" => array("evalTable"),
    "brewing" => array("brewConfirmed", "brewPaid", "brewReceived")
);

if ((isset($_SESSION['session_set_'.$prefix_session])) && (in_array($section, $allowed_sections))) {

    try {

        $do_query = TRUE;
        $no_query_value = "";
        $row_count = null;

        // For evals only
        if ($section == "evaluation") {

            $db_table = $prefix."evaluation";

            if ($p1 == "eid") {

                if (($c1 == "table") && (in_array($p2, $allowed_columns["evaluation"])) && ($c2 != "default")) {
                    $db_conn->where($p2, $c2);
                }

                $row_count = $db_conn->getOne($db_table, "COUNT(DISTINCT eid) as 'count'");

            }

            else $row_count = $db_conn->getOne($db_table, "COUNT(*) as 'count'");

        }

        elseif ($section == "brewing") {

            $db_table = $prefix."brewing";

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

            else {

                if ((in_array($p1, $allowed_columns["brewing"])) && ($c1 != "default")) {
                    $db_conn->where($p1, $c1);
                    if ((in_array($p2, $allowed_columns["brewing"])) && ($c2 != "default")) $db_conn->where($p2, $c2);
                    if ((in_array($p3, $allowed_columns["brewing"])) && ($c3 != "default")) $db_conn->where($p3, $c3);
                }

                $row_count = $db_conn->getOne($db_table, "COUNT(*) as 'count'");

            }

        }

        elseif ($section == "updated-display") {

            $do_query = FALSE;
            $no_query_value = sprintf("%s %s", $current_date_display_short, $current_time);

        }

        if ($do_query) {

            if ($row_count) {

                $response['success'] = true;
                $response['count'] = $row_count['count'];
                $response['updated'] = sprintf("%s %s", $current_date_display_short, $current_time);

            }

            else {

                throw new Exception("Query failed: " . $db_conn->getLastError());

            }

        }

        else {

            if (empty($no_query_value)) {

                throw new Exception("Fetch failed: " . $db_conn->getLastError());

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