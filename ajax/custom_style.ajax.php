<?php 
ob_start();
require('../paths.php');
require(CONFIG.'bootstrap.php');

ini_set('display_errors', 0); // Change to 0 for prod; change to 1 for testing.
ini_set('display_startup_errors', 0); // Change to 0 for prod; change to 1 for testing.
error_reporting(0); // Change to error_reporting(0) for prod; change to E_ALL for testing.

$rid1 = "default";
$rid2 = "default";

$status = 0;
$message = "Awaiting input.";

if (isset($_GET['rid1'])) $rid1 = sterilize($_GET['rid1']);
if (isset($_GET['rid2'])) $rid2 = sterilize($_GET['rid2']);
if (isset($_GET['rid3'])) $rid3 = sterilize($_GET['rid3']);
if (isset($_GET['rid4'])) $rid4 = sterilize($_GET['rid4']);

if ((isset($_SESSION['session_set_'.$prefix_session])) && (isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {

	$do_query = FALSE;
	$ok_edit_style = FALSE;
	if ((isset($rid1)) && ($rid1 != "default") && (isset($rid2)) && ($rid2 != "default")) $do_query = TRUE;
	if (($rid1 != $rid3) || ($rid2 != $rid4)) $ok_edit_style = TRUE;

	if ($do_query) {

		$under_fifty_flag = FALSE;
		
		$group = $rid1;
		$sub = $rid2;

		if ((is_numeric($group)) && ($group < 50)) $under_fifty_flag = TRUE;

		if ($under_fifty_flag) {
			$status = 1;
			$message = "<span class=\"text-primary\">All custom style category numbers must be at least 50 &ndash; 1-49 are reserved for system use. <i class=\"fa fa-info-circle\"></i></span>";
		}

		else {

			if ($ok_edit_style) {

				$db_conn->where("brewStyleGroup",$group);
				$db_conn->where("brewStyleNum",$sub);
				$style = $db_conn->getOne ($prefix."styles");
				
				if ($db_conn->count > 0) {
					$status = 2;
					$message = "<span class=\"text-danger\">Style and sub-style combination already in use. <i class=\"fa fa-exclamation-triangle\"></i></span>";
				}
				
				else {
					
					if ((!empty($group)) && (!empty($sub))) {
						$status = 3; 
						$message = "<span class=\"text-success\">Style and sub-style combination is available. <i class=\"fa fa-check-circle\"></i></span>";
					}

				}

			}

			else $status = 4;
		
		}

	} else {
		$message = "<span class=\"text-warning\">An identifier is needed in both fields. <i class=\"fa fa-exclamation-triangle\"></i></span>";
	}

} 

else $status = 9; // Session expired, not enabled, etc.

$return_json = array(
	"status" => "$status",
	"message" => "$message"
);

echo json_encode($return_json);
mysqli_close($connection);

?>