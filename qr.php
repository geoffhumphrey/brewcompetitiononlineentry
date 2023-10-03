<?php
require_once ('paths.php');
require_once (INCLUDES.'url_variables.inc.php');
require_once (LIB.'common.lib.php');
require_once (INCLUDES.'db_tables.inc.php');
if ($view != "default") $checked_in_numbers = explode("^",$view);

$query_contest_info = sprintf("SELECT * FROM %s", $prefix."contest_info");
if (SINGLE) $query_contest_info .= sprintf(" WHERE id='%s'", $_POST['comp_id']);
else $query_contest_info .= " WHERE id='1'";
$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
$row_contest_info = mysqli_fetch_assoc($contest_info);

if (SINGLE) $query_prefs = sprintf("SELECT * FROM %s WHERE comp_id='%s'", $prefix."preferences",$row_contest_info['id']);
else $query_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."preferences");
$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
$row_prefs = mysqli_fetch_assoc($prefs);
$totalRows_prefs = mysqli_num_rows($prefs);

$_SESSION['prefsLanguage'] = $row_prefs['prefsLanguage'];

if (($action != "update") && ($action != "password-check")) {
	if (function_exists('random_bytes')) $_SESSION['token'] = bin2hex(random_bytes(32));
	elseif (function_exists('mcrypt_create_iv')) $_SESSION['token'] = bin2hex(mcrypt_create_iv(32,MCRYPT_DEV_URANDOM));
	else $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
}

// Check if variation used (demarked with a dash)
if (strpos($row_prefs['prefsLanguage'], '-') !== FALSE) {
	$lang_folder = explode("-",$_SESSION['prefsLanguage']);
	$_SESSION['prefsLanguageFolder'] = strtolower($lang_folder[0]);
}

else $_SESSION['prefsLanguageFolder'] = strtolower($_SESSION['prefsLanguage']);

require (LANG.'language.lang.php');

$logged_in = FALSE;
if (!isset($_SESSION['qrPasswordOK'])) $logged_in = TRUE;

$header_output = $row_contest_info['contestName'];
$theme = $base_url."css/".$row_prefs['prefsTheme'].".min.css";
$process_allowed = FALSE;

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

		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);

		$password = sterilize($_POST['inputPassword']);

		if (strlen($password) > 72) {
			$password_redirect .= "&msg=1";
		}

		else {

			mysqli_real_escape_string($connection,$password);
			$password = md5($password);

			// Check user input against DB
			$query_qr_password = sprintf("SELECT contestCheckInPassword FROM %s WHERE id = '1'",$prefix."contest_info");
			$qr_password = mysqli_query($connection,$query_qr_password) or die (mysqli_error($connection));
			$row_qr_password = mysqli_fetch_assoc($qr_password);

			// if no password set up, redirect
			if (!isset($row_qr_password['contestCheckInPassword'])) {
				$password_redirect .= "&msg=1";
			}

			else {
				
				$stored_hash = $row_qr_password['contestCheckInPassword'];
				$check = 0;
				$check = $hasher->CheckPassword($password, $stored_hash);

				// If successful, register a session variable and set the redirect
				if ($check == 1) {
					$password_redirect .= "&msg=2";
					$_SESSION['qrPasswordOK'] = $password;
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

if (isset($_SERVER['HTTP_REFERER'])) {
	$referrer = parse_url($_SERVER['HTTP_REFERER']);
	if (($referrer['host'] == $_SERVER['SERVER_NAME']) && (isset($_SESSION['qrPasswordOK']))) $process_allowed = TRUE;
}

$paid_checked = "";

if (($go == "default") && ($id != "default") && ($process_allowed)) {

	$checkin_redirect = $base_url."qr.php?action=default&go=success";
	
	// Check to see if the entry is in the DB
	$query_entry = sprintf("SELECT id,brewName,brewStyle,brewCategory,brewSubCategory,brewPaid,brewJudgingNumber FROM %s WHERE id='%s'",$prefix."brewing",$id);
	$entry = mysqli_query($connection,$query_entry) or die (mysqli_error($connection));
	$row_entry = mysqli_fetch_assoc($entry);
	$totalRows_entry = mysqli_num_rows($entry);
	
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
				$token = filter_input(INPUT_POST,'token',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				if (hash_equals($_SESSION['token'],$token)) $token_hash = TRUE;

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
				$query_judging_number = sprintf("SELECT id FROM %s WHERE brewJudgingNumber='%s' AND id <> %s ",$prefix."brewing",$brewJudgingNumber,$id);
				$judging_number = mysqli_query($connection,$query_judging_number) or die (mysqli_error($connection));
				$row_judging_number = mysqli_fetch_assoc($judging_number);
				$totalRows_judging_number = mysqli_num_rows($judging_number);

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
	$message .= sprintf("<span class=\"fa fa-exclamation-circle\"></span> <strong>%s</strong> %s",$alert_text_080,$header_text_008);
}

if ($msg == "2") {
	$message .= sprintf("<span class=\"fa fa-check-circle\"></span> <strong>%s</strong>",$alert_text_081);
	$alert_type = "alert-success";
}

if ($msg == "3") {
	$message .= sprintf("<p><span class=\"fa fa-check-circle\"></span> <strong>%s</strong></p><p>%s<p></strong>",$qr_text_002, $qr_text_003);
	$alert_type = "alert-success";
}

if ($msg == "4") {
	$message .= sprintf("<span class=\"fa fa-exclamation-circle\"></span> <strong>%s</strong>",$qr_text_005);
}

if ($msg == "5") {
	$message .= sprintf("<span class=\"fa fa-exclamation-circle\"></span> <strong>%s</strong>",$qr_text_006);
}

if ($msg == "6") {
	$message .= sprintf("<p><span class=\"fa fa-check-circle\"></span> <strong>%s</strong></p>",$qr_text_004);
	$alert_type = "alert-success";
}

if ($msg == "7") {
	$message .= sprintf("<span class=\"fa fa-exclamation-circle\"></span> <strong>%s</strong>",$header_text_014);
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

    <!-- Load Bootstrap / http://www.getbootsrap.com -->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Load Bootstrap Form Validator / http://1000hz.github.io/bootstrap-validator -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.9.0/validator.min.js"></script>

    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/v4-shims.min.css" integrity="sha512-NfhLGuxy6G12XHj7/bvm0RC3jmR25RdpImn8P19aIMmN5pndO0fvIg78ihN2WIJtVRs+AYVrnYF4AipVikGPLg==" crossorigin="anonymous" />

    <!-- Load BCOE&M Custom Theme CSS - Contains Bootstrap overrides and custom classes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $theme; ?>" />

	<!-- Load BCOE&M Custom JS -->
    <script src="<?php echo $js_url; ?>bcoem_custom.min.js"></script>

    <style>
	body {
		margin: 0;
		padding: 0;
	}

	.container-signin {
	  max-width: 400px;
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
<div class="container">
   	<?php if (DEBUG_SESSION_VARS) include (DEBUGGING.'session_vars.debug.php');	?>
    <div class="container-signin">
    <?php if ($msg != "default") { ?>
    <!-- Alerts -->
    <div class="alert <?php echo $alert_type; ?> alert-dismissible hidden-print fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php echo $message; ?>
    </div>
    <?php } ?>
    <div class="page-header clearfix">
    	<?php if (isset($_SESSION['qrPasswordOK'])) { ?>
        <h3><?php echo $header_output.": ".$qr_text_007; ?></h3>
        <?php } else { ?>
        <h1><?php echo $header_output.": ".$qr_text_007; ?></h1>
        <?php } ?>
    </div>
    <?php if (!isset($_SESSION['qrPasswordOK'])) { ?>
	<div align="center" class="text-primary"><span class="fa fa-qrcode fa-5x"></span></div>
	<p class="lead"><small><strong class=text-danger><?php echo $qr_text_017; ?></strong></small></p>
	<p><?php echo $qr_text_018; ?></p>
	<p style="margin-bottom: 15px;" class="container-signin-heading"><?php echo $qr_text_008; ?></p>
    <!-- Password Form if Not Signed In -->
	<form data-toggle="validator" name="form1" action="<?php echo $base_url; ?>qr.php?action=password-check<?php if ($id != "default") echo "&amp;id=".$id; ?>" method="post">
        <div class="form-group">
            <label for="inputPassword" class="sr-only"><?php echo $label_password; ?></label>
            <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="<?php echo $label_password; ?>" autofocus required>
            <div class="help-block with-errors"></div>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $label_log_in; ?></button>
   	</form>
    <p style="margin-top: 15px;" class="well"><small><?php echo $qr_text_016; ?></small></p>
	<?php } ?>
    <?php if (isset($_SESSION['qrPasswordOK'])) { ?>
	<?php if (($id == "default") && ($action == "default")) { ?>
    <p class="lead text-primary"><span class="fa fa-spinner fa-spin"></span> <strong><?php echo $qr_text_014; ?></strong></p>
    <p class="alert alert-info"><span class="fa fa-info-circle"></span> <?php echo $qr_text_015; ?></p>
    <p style="margin-top: 15px;" class="well"><small><?php echo $qr_text_016; ?></small></p>
    <?php } ?>
    <?php if (($id != "default") && ($action == "default") && ($go != "success")) { ?>
    <p class="lead text-primary"><strong><?php echo $qr_text_009; ?> <span class="badge"><?php echo sprintf("%06d",$id); ?></span></strong></p>
    <p class="lead text-danger"><small><strong><?php echo $qr_text_010; ?></strong></small></p>
    <form name="form1" data-toggle="validator" action="<?php echo $base_url; ?>qr.php?action=update<?php if ($id != "default") echo "&amp;id=".$id; ?>" method="post">
    <input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
    	<div class="form-group">
            <label for="inputJudgingNumber"><?php echo $label_judging_number; ?></label>
            <input type="tel" pattern="[^^]+"  maxlength="6" data-minlength="6" name="brewJudgingNumber" id="brewJudgingNumber" class="form-control" placeholder="<?php echo $qr_text_011; ?>" data-error="<?php echo $qr_text_013; ?>" autofocus>
            <div class="help-block with-errors"></div>
            <div class="help-block small"><?php echo $qr_text_012; ?></div>
        </div>
        <div class="form-group">
            <label for="inputBoxNumber"><?php echo $label_box_number; ?></label>
            <input type="text" name="brewBoxNum" id="brewBoxNum" class="form-control" placeholder="">
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label for="inputPaid"><input type="checkbox" name="brewPaid" id="brewPaid" value="1" <?php echo $paid_checked; ?>> <?php echo $label_paid; ?></label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $label_check_in; ?></button>
   	</form>
    <?php } ?>
    <?php } ?>
    </div><!-- /container-signin -->
</div> <!-- /container -->
</body>
</html>