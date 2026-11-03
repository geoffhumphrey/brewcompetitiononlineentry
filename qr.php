<?php
require_once ('paths.php');
require_once (INCLUDES.'url_variables.inc.php');
// Build array - used in language files, so it needs to be before bootstrap.php
$checked_in_numbers = array();
if (strpos($view, "^") !== FALSE) $checked_in_numbers = explode("^",$view);
elseif (strpos($view, "%5E") !== FALSE) $checked_in_numbers = explode("%5E",$view);
require_once (CONFIG.'bootstrap.php');
require_once (LIB.'common.lib.php');
require_once (LIB.'output.lib.php');
require_once (INCLUDES.'db_tables.inc.php');

if (SINGLE) $db_conn->where("id", $_POST['comp_id']);
else $db_conn->where("id", 1);
$row_contest_info = $db_conn->getOne($prefix."contest_info");

if (SINGLE) $db_conn->where("comp_id", $row_contest_info['id']);
else $db_conn->where("id", 1);
$row_prefs = $db_conn->getOne($prefix."preferences");
$totalRows_prefs = $db_conn->count;

$_SESSION['prefsLanguage'] = $row_prefs['prefsLanguage'];

// Legacy installs may still have prefsLanguage stored as "English" (pre-locale-code
// installs, e.g. via the 2.1.5.0 update) rather than "en-US" - normalize before deriving
// the folder, or this falls through to strtolower() producing "english", which doesn't
// match the actual "en" folder on disk.
if (strtolower($_SESSION['prefsLanguage']) == "english") $_SESSION['prefsLanguage'] = "en-US";

if (($action != "update") && ($action != "password-check")) csrf_token_generate(false);

// Check if variation used (demarked with a dash)
if (strpos($_SESSION['prefsLanguage'], '-') !== FALSE) {
	$lang_folder = explode("-",$_SESSION['prefsLanguage']);
	$_SESSION['prefsLanguageFolder'] = strtolower($lang_folder[0]);
}

else $_SESSION['prefsLanguageFolder'] = strtolower($_SESSION['prefsLanguage']);

require (LANG.'language.lang.php');

$logged_in = FALSE;
if (!isset($_SESSION['qrPasswordOK'])) $logged_in = TRUE;

$header_output = $row_contest_info['contestName'];
$theme = $css_url.$row_prefs['prefsTheme']."-3.min.css";

$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

// Validate user input against password in DB
if ($action == "password-check") {

	$password_redirect = $base_url."qr.php?action=default";

	if ($id != "default") $password_redirect .= "&id=".$id;

	if ((isset($_POST['inputPassword'])) && (!empty($_POST['inputPassword']))) {
		
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');

		$password = sterilize($_POST['inputPassword']);

		if (strlen($password) > 72) {
			$password_redirect .= "&msg=1";
		}

		else {

			// Check user input against DB
			$db_conn->where("id", 1);
			$row_qr_password = $db_conn->getOne($prefix."contest_info", "contestCheckInPassword");

			// if no password set up, redirect
			if (!isset($row_qr_password['contestCheckInPassword'])) {
				$password_redirect .= "&msg=1";
			}

			else {

				$stored_hash = $row_qr_password['contestCheckInPassword'];
				$check = password_verify_legacy($password, $stored_hash);
				if (($check == 1) && (password_needs_legacy_upgrade($stored_hash))) upgrade_legacy_password_hash($db_conn, $prefix."contest_info", "id", 1, $password);

				// If successful, register a session variable and set the redirect
				if ($check == 1) {
					$password_redirect .= "&msg=2";
					$_SESSION['qrPasswordOK'] = TRUE;
					csrf_token_generate(true);
				}

				// If not successful, destroy session and redirect
				if ($check == 0) {
					session_destroy();
					$password_redirect .= "&msg=1";
				}

			}

		}

	}

	else {
		$password_redirect .= "&msg=1";
	}

	$redirect = prep_redirect_link($password_redirect);
	header(sprintf("Location: %s", $redirect));
	exit();

}

$paid_checked = "";

if (($go == "default") && ($id != "default") && (isset($_SESSION['qrPasswordOK']))) {

	$checkin_redirect = $base_url."qr.php?action=default&go=success";
	
	// Check to see if the entry is in the DB
	$db_conn->where("id", $id);
	$row_entry = $db_conn->getOne($prefix."brewing", "id,brewName,brewStyle,brewCategory,brewSubCategory,brewPaid,brewJudgingNumber");
	$totalRows_entry = $db_conn->count;
	
	if ($totalRows_entry == 1) {

		$brewBoxNum = NULL;
		$brewPaid = 0;
		
		$paid = FALSE;

		if ($row_entry['brewPaid'] == 1) {
			$paid = TRUE;
			$paid_checked = "checked disabled";
			$brewPaid = 1;
		}

		if ($action == "update") {

			/**
			 * Check for CSRF token.
			 * If tokens match, continue with process.
			 * If not, redirect to 403 page.
			 */

			if ($request_method === "POST") {

				$token_hash = FALSE;
				$token = filter_input(INPUT_POST,'user_session_token',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				if (hash_equals($_SESSION['user_session_token'],$token)) $token_hash = TRUE;

				if ((!$token) || (!$token_hash)) {
					session_unset();
					session_destroy();
					session_write_close();
					$redirect = $base_url."403.php";
					$redirect = prep_redirect_link($redirect);
					$redirect_go_to = sprintf("Location: %s", $redirect);
					header($redirect_go_to);
					exit();
				}

			}

			$update_table = $prefix."brewing";

			if ((isset($_POST['brewBoxNum'])) && (!empty($_POST['brewBoxNum']))) $brewBoxNum = sterilize($_POST['brewBoxNum']);
			if ((!$paid) && ((isset($_POST['brewPaid'])) && (!empty($_POST['brewPaid'])))) $brewPaid = 1;

			// Check if judging number has been input
			// If so, add to update query and perform redirect
			if ((isset($_POST['brewJudgingNumber'])) && ($_POST['brewJudgingNumber'] != "")) {

				$brewJudgingNumber = sprintf("%06s",sterilize($_POST['brewJudgingNumber']));
				$brewJudgingNumber = strtolower($brewJudgingNumber); // judging numbers are stored in all lower case

				// Check to see if judging number has already been assigned
				$db_conn->where("brewJudgingNumber", $brewJudgingNumber);
				$db_conn->where("id", $id, "<>");
				$row_judging_number = $db_conn->getOne($prefix."brewing", "id");
				$totalRows_judging_number = $db_conn->count;

				// If so, redirect with message
				if ($totalRows_judging_number > 0) {
					$redirect = $base_url."qr.php?action=default&go=default&view=".$row_judging_number['id']."^".$brewJudgingNumber."&msg=5";
					$redirect = prep_redirect_link($redirect);
					header(sprintf("Location: %s", $redirect));
					exit();
				}
				
				$data = array(
					'brewReceived' => 1,
					'brewJudgingNumber' => $brewJudgingNumber,
					'brewBoxNum' => $brewBoxNum,
					'brewPaid' => $brewPaid
				);

				$checkin_redirect .= "&view=".$id."^".$brewJudgingNumber."&msg=3";

			}

			else {

				$data = array(
					'brewReceived' => 1,
					'brewBoxNum' => $brewBoxNum,
					'brewPaid' => $brewPaid
				);

				$checkin_redirect .= "&view=".$id."^".$row_entry['brewJudgingNumber']."&msg=6";
				
			}

			$db_conn->where ('id', $id);
			if ($db_conn->update ($update_table, $data)) $redirect = $checkin_redirect;
			else $redirect = "&view=".$id."^000000&msg=7";
			$redirect = prep_redirect_link($redirect);
			header(sprintf("Location: %s", $redirect));
			exit();

		} // end if ($action == "update")

	} // end if ($totalRows_entry == 1)

	// If not, redirect to alert the user (message 4)
	else {

		$checkin_redirect .= "&view=".$id."^000000&msg=4";
		$redirect = prep_redirect_link($checkin_redirect);
		header(sprintf("Location: %s", $redirect));
		exit();

	}

}

// Messages
$message = "";
$alert_type = "alert-danger";

if ($msg == "1") {
	$message .= sprintf("<i class=\"fa fa-lg fa-exclamation-circle me-2\"></i><strong>%s</strong> %s",$alert_text_080,$header_text_008);
}

if ($msg == "2") {
	$message .= sprintf("<i class=\"fa fa-lg fa-check-circle me-2\"></i><strong>%s</strong>",$alert_text_081);
	$alert_type = "alert-success";
}

if ($msg == "3") {
	$message .= sprintf("<i class=\"fa fa-lg fa-check-circle me-2\"></i><strong>%s</strong><br>%s</strong>",$qr_text_002, $qr_text_003);
	$alert_type = "alert-success";
}

if ($msg == "4") {
	$message .= sprintf("<i class=\"fa fa-lg fa-exclamation-circle me-2\"></i><strong>%s</strong>",$qr_text_005);
}

if ($msg == "5") {
	$message .= sprintf("<i class=\"fa fa-lg fa-exclamation-circle me-2\"></i><strong>%s</strong>",$qr_text_006);
}

if ($msg == "6") {
	$message .= sprintf("<i class=\"fa fa-lg fa-check-circle me-2\"></i><strong>%s</strong>",$qr_text_004);
	$alert_type = "alert-success";
}

if ($msg == "7") {
	$message .= sprintf("<i class=\"fa fa-lg fa-exclamation-circle me-2\"></i><strong>%s</strong>",$header_text_014);
}

if (isset($_SESSION['last_action'])) {
    $seconds_inactive = time() - $_SESSION['last_action'];
    $session_expire_after_seconds = $session_expire_after * 60;
    if ($seconds_inactive >= $session_expire_after_seconds) {
        session_unset();
		session_destroy();
		session_write_close();
		setcookie(session_name($prefix_session),'',0,'/');
    }
}

$_SESSION['last_action'] = time();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $row_contest_info['contestName']; ?> - Brew Competition Online Entry &amp; Management</title>

	<!-- Load jQuery / http://jquery.com/ -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Load Bootstrap 5.X / https://getbootstrap.com/ -->
    <!-- Load Ninja Bootstrap (REPLACES and EXTENDS Bootstrap's CSS) / https://bootstrap.ninja/ninjabootstrap/ -->
    <link href="https://cdn.jsdelivr.net/gh/livecanvas-team/ninjabootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <script type="text/javascript">
        window.FontAwesomeConfig = { autoReplaceSvg: false }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/v4-shims.min.css" />

    <!-- Load BCOE&M Custom CSS - Contains Bootstrap overrides and custom classes common to all BCOE&M themes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $css_common_url; ?>" />
    <!-- Load BCOE&M Custom Theme CSS - Contains Bootstrap overrides and custom classes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $theme; ?>" />

    <style>
	body {
		margin: 0;
		padding: 0;
	}

	.container-signin {
	  padding: 15px;
	  margin: 0 auto;
	}

	.container-signin .container-signin-heading,
	.container-signin .checkbox {
	  margin-bottom: 10px;
	}

	.container-signin .checkbox {
	  font-weight: normal;
	}

	.container-signin .form-control {
	  position: relative;
	  height: auto;
	  -webkit-box-sizing: border-box;
		 -moz-box-sizing: border-box;
			  box-sizing: border-box;
	  padding: 10px;
	  font-size: 16px;
	}

	.container-signin .form-control:focus {
	  z-index: 2;
	}

	.container-signin input[type="password"],.container-signin input[type="tel"] {
	  margin-bottom: 10px;
	}
</style>
</head>
<body>
<!-- LOADER -->
<div id="loader-submit" class="d-print-none">
    <div class="center">
        <i class="fa fa-spinner fa-spin-pulse fa-spin-reverse fa-5x fa-fw mb-4"></i>
        <p><strong><?php echo $label_working; ?>.<br><?php echo $output_text_030." ".$output_text_031; ?></strong></p>
    </div>
</div>
<div class="container">
   	<?php if (DEBUG_SESSION_VARS) include (DEBUGGING.'session_vars.debug.php');	?>
    <div class="container-signin">
    <?php if ($msg != "default") { ?>
    <!-- Alerts -->
    <div class="alert <?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php } ?>
    <div class="page-header clearfix">
    	<?php if (isset($_SESSION['qrPasswordOK'])) { ?>
        <h1 class="display-4 fw-semibold"><?php echo $header_output."<br><span class=\"text-secondary display-6\">".$qr_text_007; ?></span></h1>
        <?php } else { ?>
        <h1 class="display-4 fw-semibold"><?php echo $header_output."<br><span class=\"text-secondary display-6\">".$qr_text_007; ?></span></h1>
        <?php } ?>
    </div>
    <?php if (!isset($_SESSION['qrPasswordOK'])) { ?>
	<p class="mt-3 fs-5"><strong class="text-primary"><?php echo $qr_text_017; ?></strong></p>
	<p><?php echo $qr_text_018; ?></p>
	<p style="mb-3" class="container-signin-heading"><?php echo $qr_text_008; ?></p>
    <!-- Password Form if Not Signed In -->
	<form class="needs-validation" novalidate name="form1" action="<?php echo $base_url; ?>qr.php?action=password-check<?php if ($id != "default") echo "&amp;id=".$id; ?>" method="post">
        <div class="mt-3 mb-3">
            <label for="inputPassword" class="visually-hidden"><strong><?php echo $label_password; ?><strong></label>
            <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="<?php echo $label_password; ?>" autofocus required>
        </div>
        <button class="btn btn-lg btn-primary w-100 mt-3 mb-3" type="submit"><?php echo $label_log_in; ?></button>
   	</form>
    <p class="mt-3 fw-normal"><small><?php echo $qr_text_016; ?></small></p>
	<?php } ?>
    
	<?php if (isset($_SESSION['qrPasswordOK'])) { ?>
	<?php if (($id == "default") && ($action == "default")) { ?>
	<p class="mt-5 mb-5"><span class="spinner-border text-primary me-2" role="status" style="width: 1.5rem; height: 1.5rem;"></span><strong class="mt-5 mb-5 fs-4 text-primary"><?php echo $qr_text_014; ?></strong></p>
    <p class="alert alert-info"><i class="fa fa-lg fa-info-circle me-2"></i><?php echo $qr_text_015; ?></p>
    <p class="mt-5"><small><?php echo $qr_text_016; ?></small></p>
    <?php } ?>

    <?php if (($id != "default") && ($action == "default") && ($go != "success")) { ?>
    <?php $table_info = get_flight_info($id); ?>
    <p class="fs-4 mt-4 mb-4"><strong><?php echo $qr_text_009; ?> <span class="text-primary fs-3"><?php echo sprintf("%06d",$id); ?></span>.</strong></p>
    <?php if ($table_info['response'] == "Assigned") { ?>
    <div class="fs-4">
		<div class="badge rounded-pill bg-warning text-dark p-3 fs-4 w-100">
			<div class="mb-2"><i class="fa fa-lg fa-rectangle-list me-2"></i><?php echo h($label_assigned_to_table)." ".h($table_info['tableNumber']); ?></div>
			<div class="fs-5 ms-2 fw-normal"><?php echo h($table_info['tableName']); ?></div>
		</div>
	</div>
    <?php } ?>
    <p class="fs-5 text-danger mt-4 mb-4"><strong><?php echo $qr_text_010; ?></strong></p>
    <form name="form1" class="needs-validation" novalidate action="<?php echo $base_url; ?>qr.php?action=update<?php if ($id != "default") echo "&amp;id=".$id; ?>" method="post">
    <input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
    	<div class="mb-3">
            <label for="inputJudgingNumber"><strong><?php echo $label_judging_number; ?></strong></label>
            <input type="tel" pattern="[^^]+" maxlength="6" minlength="6" name="brewJudgingNumber" id="brewJudgingNumber" class="form-control form-control-sm" placeholder="<?php echo $qr_text_011; ?>" autofocus>
            <div class="form-text small"><?php echo $qr_text_012; ?></div>
        </div>
        <div class="mb-3">
            <label for="inputBoxNumber"><strong><?php echo $label_box_number; ?></strong></label>
            <input type="text" name="brewBoxNum" id="brewBoxNum" class="form-control form-control-sm" placeholder="">
        </div>
        <div class="mb-3">
            <label for="inputPaid"><input class="form-check-input" type="checkbox" name="brewPaid" id="brewPaid" value="1" <?php echo $paid_checked; ?>> <strong><?php echo $label_paid; ?></strong></label>
        </div>
        <button class="btn btn-lg btn-primary w-100" type="submit"><i class="fa fa-lg fa-clipboard-check me-3"></i><?php echo $label_check_in; ?></button>
   	</form>
    <?php } ?>
    <?php } ?>
	
    </div><!-- /container-signin -->
</div> <!-- /container -->

<!-- Required Info Missing Modal -->
<div class="modal modal-lg fade" id="form-submit-button-disabled-msg-required" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="form-submit-button-disabled-msg-required-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="form-submit-button-disabled-msg-required-label"><?php echo $label_required_info; ?></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <?php echo sprintf("<p><strong>%s</strong></p><p>%s</p>",$form_required_fields_00,$form_required_fields_01); ?>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo $label_understand; ?></button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<!-- Load BCOE&M Custom JS -->
<script src="<?php echo $js_app_pub_url; ?>"></script>
<script src="<?php echo $js_invoke_url; ?>"></script>
</body>
</html>
