<?php
require('paths.php');
require(CONFIG.'bootstrap.php');
session_name($prefix_session);
session_start();
$section = "qr";
// Load language file
require(LANG.'language.lang.php');

// Validate user input against password in DB
if ($action == "password-check") {
	
	$password_redirect = $base_url."qr.php?action=default";
	
	if ($id != "default") $password_redirect .= "&id=".$id;
	
	if ((isset($_POST['inputPassword'])) && ($_POST['inputPassword'] != "")) {
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); 
		header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT'); 
		header('Cache-Control: no-store, no-cache, must-revalidate'); 
		header('Cache-Control: post-check=0, pre-check=0', false); 
		header('Pragma: no-cache'); 
		
		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		
		// Clean the data collected in the <form>
		function sterilize ($sterilize=NULL) {
			if ($sterilize==NULL) { return NULL; }
			$check = array (1 => "'", 2 => '"', 3 => '<', 4 => '>');
			foreach ($check as $value) {
			$sterilize=str_replace($value, '', $sterilize);
				}
				$sterilize=strip_tags ($sterilize);
				$sterilize=stripcslashes ($sterilize);
				$sterilize=stripslashes ($sterilize);
				$sterilize=addslashes ($sterilize);
			return $sterilize;
		}
		
		$password = sterilize($_POST['inputPassword']);
		
		if (strlen($password) > 72) { 
			$password_redirect .= "&msg=1";
			header(sprintf("Location: %s", $password_redirect));
			exit;
		}
		
		mysqli_real_escape_string($connection,$password);
		$password = md5($password);
		
		// Check user input against DB
		$query_qr_password = sprintf("SELECT contestCheckInPassword FROM %s WHERE id = '1'",$prefix."contest_info");
		$qr_password = mysqli_query($connection,$query_qr_password) or die (mysqli_error($connection));
		$row_qr_password = mysqli_fetch_assoc($qr_password);
		
		// if no password set up, redirect
		if (!isset($row_qr_password['contestCheckInPassword'])) {
			$password_redirect .= "&msg=1";
			header(sprintf("Location: %s", $password_redirect));
			exit;
		}
		
		else {
			$stored_hash = $row_qr_password['contestCheckInPassword'];
			$check = 0;
			$check = $hasher->CheckPassword($password, $stored_hash);
		}
		
		
		
		// If successful, start session and redirect
		if ($check == 1) {
			// Start the session and regenerate the session ID
			session_regenerate_id(true); 
			$password_redirect .= "&msg=2";
			$_SESSION['qrPasswordOK'] = "1";
			
			/*
			// If id was passed in URL, check in that entry
			if ($id != "default") {
				
				$updateSQL = sprintf("UPDATE %s SET brewReceived='1' WHERE id='%s';",$brewing_db_table,$id);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				
			}
			*/
		}
		
		// If not successful, destroy session and redirect
		else {
			session_destroy();
			$password_redirect .= "&msg=1";
			
		}
		
		header(sprintf("Location: %s", $password_redirect));
		exit;
	}
	
	else {
		$password_redirect .= "&msg=1";
		header(sprintf("Location: %s", $password_redirect));
	}
}

if (($go == "default") && ($id != "default") && (isset($_SESSION['qrPasswordOK']))) {
	
	$checkin_redirect = $base_url."qr.php?action=default&go=success";
	
	// Check to see if the entry is in the DB
	$query_entry = sprintf("SELECT id,brewName,brewStyle,brewCategory,brewSubCategory FROM %s WHERE id='%s'",$prefix."brewing",$id);
	$entry = mysqli_query($connection,$query_entry) or die (mysqli_error($connection));
	$row_entry = mysqli_fetch_assoc($entry);
	$totalRows_entry = mysqli_num_rows($entry);
	$entry_found = FALSE;
	if ($totalRows_entry > 0) $entry_found = TRUE;
	
	// If so, mark the entry as "received" and redirect (message 3)
	if ($entry_found) {
		
		
		if ($action == "update") {
			
			// Check if judging number has been input
			// If so, add to update query and perform redirect
			if ((isset($_POST['brewJudgingNumber'])) && ($_POST['brewJudgingNumber'] != "")) {
				$judgingNumber = sprintf("%06s",$_POST['brewJudgingNumber']);
				
				// Check to see if judging number has already been assigned
				$query_judging_number = sprintf("SELECT id FROM %s WHERE brewJudgingNumber='%s' AND id <> %s ",$prefix."brewing",$judgingNumber,$id);
				$judging_number = mysqli_query($connection,$query_judging_number) or die (mysqli_error($connection));
				$row_judging_number = mysqli_fetch_assoc($judging_number);
				$totalRows_judging_number = mysqli_num_rows($judging_number);
				
				// If so, redirect with message
				if ($totalRows_judging_number > 0) {
					$redirect = $base_url."qr.php?action=default&go=default&view=".$row_judging_number['id']."-".$judgingNumber."&msg=5";
					header(sprintf("Location: %s", $redirect));
					exit;	
				}
				
				// If not, update DB		
				$updateSQL = sprintf("UPDATE %s SET brewReceived='1',brewJudgingNumber='%s'",$brewing_db_table,$judgingNumber);
				if ((isset($_POST['brewBoxNum'])) && ($_POST['brewBoxNum'] != "")) $updateSQL .= sprintf(",brewBoxNum='%s'",$_POST['brewBoxNum']);
				$updateSQL .= sprintf(" WHERE id='%s';",$id);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
				$checkin_redirect .= "&view=".$id."-".$judgingNumber."&msg=3";
				header(sprintf("Location: %s", $checkin_redirect));
				exit;
			
			}
			
			else {
				
				$updateSQL = sprintf("UPDATE %s SET brewReceived='1'",$brewing_db_table,$judgingNumber);
				if ((isset($_POST['brewBoxNum'])) && ($_POST['brewBoxNum'] != "")) $updateSQL .= sprintf(",brewBoxNum='%s'",$_POST['brewBoxNum']);
				$updateSQL .= sprintf(" WHERE id='%s';",$id);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
				$checkin_redirect .= "&view=".$id."-".$judgingNumber."&msg=6";
				header(sprintf("Location: %s", $checkin_redirect));
				exit;
				
			}
		}
		
		
	}
	
	// If not, redirect to alert the user (message 4)
	else {
		
		$checkin_redirect .= "&view=".$id."&msg=4";
		header(sprintf("Location: %s", $checkin_redirect));
		exit;

	}
	
}


// Messages
$message = "";
$alert_type = "alert-danger";

if ($msg == "1") {
	$message .= sprintf("<span class=\"fa fa-exclamation-circle\"></span> <strong>%s</strong> %s",$qr_text_000,$header_text_008);
}

if ($msg == "2") {
	$message .= sprintf("<span class=\"fa fa-check-circle\"></span> <strong>%s</strong>",$qr_text_001);
	$alert_type = "alert-success";
}

if ($msg == "3") {
	$view = explode("-",$view);
	$message .= sprintf("<p><span class=\"fa fa-check-circle\"></span> <strong>%s</strong></p><p>%s<p>",$qr_text_002,$qr_text_003);
	$alert_type = "alert-success";
}

if ($msg == "6") {
	$message .= sprintf("<p><span class=\"fa fa-check-circle\"></span> <strong>%s</strong></p>",$qr_text_004);
	$alert_type = "alert-success";
}

if ($msg == "4") {
	$message .= sprintf("<span class=\"fa fa-exclamation-circle\"></span> <strong>%s</strong>",$qr_text_005);
}

if ($msg == "5") {
	$view = explode("-",$view);
	$message .= sprintf("<span class=\"fa fa-exclamation-circle\"></span> <strong>%s</strong> %s",$qr_text_000,$header_text_008);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION['contestName']; ?> - Brew Competition Online Entry &amp; Management</title>
    
	<!-- Load jQuery / http://jquery.com/ -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	
    <!-- Load Bootstrap / http://www.getbootsrap.com -->
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Load Bootstrap Form Validator / http://1000hz.github.io/bootstrap-validator -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.9.0/validator.min.js"></script>
    
    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    
    <!-- Load BCOE&M Custom Theme CSS - Contains Bootstrap overrides and custom classes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $theme; ?>" />
	
	<!-- Load BCOE&M Custom JS -->
    <script src="<?php echo $base_url; ?>js_includes/bcoem_custom.min.js"></script>
    
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
    	<p class="lead"><small><?php echo sprintf("<strong class=\"text-danger\">%s</strong> %s",$qr_text_017,$qr_text_018); ?></small></p>
        <p class="lead"><small><?php echo sprintf("%s",$qr_text_016); ?></small></p>
        <p class="lead container-signin-heading"><small><?php echo $qr_text_008; ?></small></p>
        
    <!-- Password Form if Not Signed In -->
	<form data-toggle="validator" name="form1" action="<?php echo $base_url; ?>qr.php?action=password-check<?php if ($id != "default") echo "&amp;id=".$id; ?>" method="post">
        <div class="form-group">
            <label for="inputPassword" class="sr-only"><?php $label_password; ?></label>
            <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Password" autofocus required>
            <div class="help-block with-errors"></div>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $label_log_in; ?></button>
   	</form>
    <?php } ?>
    
    <?php if (isset($_SESSION['qrPasswordOK'])) { ?>    
	<?php if (($id == "default") && ($action == "default")) { ?>
    <p class="lead text-primary"><span class="fa fa-spinner fa-spin"></span> <strong><?php echo $qr_text_014; ?></strong></p>
    <p class="lead text-danger"><small><span class="fa fa-exclamation-triangle"></span> <?php echo $qr_text_015; ?></small></p>
    <p><?php echo $qr_text_016; ?></p>
    <?php } if (($id != "default") && ($action == "default") && ($go != "success")) { ?>
    <p class="lead text-primary"><strong><?php echo $qr_text_009; ?> <span class="text-success"><?php echo sprintf("%04d",$id); ?></span></strong>.</p>
    <p class="lead text-danger"><small><strong><?php echo $qr_text_010; ?></strong></small></p>
    <form name="form1" data-toggle="validator" action="<?php echo $base_url; ?>qr.php?action=update<?php if ($id != "default") echo "&amp;id=".$id; ?>" method="post">
    	<div class="form-group">
            <label for="inputJudgingNumber"><?php echo $label_judging_number; ?></label>
            <input type="tel" maxlength="6" data-minlength="6" name="brewJudgingNumber" id="brewJudgingNumber" class="form-control" placeholder="<?php echo $qr_text_011; ?>" data-error="<?php echo $qr_text_013; ?>" autofocus>
            <div class="help-block with-errors"></div>
            <div class="help-block small"><?php echo $qr_text_012; ?></div>
        </div>
        <div class="form-group">
            <label for="inputBoxNumber"><?php echo $label_box_number; ?></label>
            <input type="number" name="brewBoxNum" id="brewBoxNum" class="form-control" placeholder="">
            <div class="help-block with-errors"></div>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $label_check_in; ?></button>
   	</form>	    
    <?php } ?>
    
    <?php } ?>
    </div><!-- /container-signin -->
    
</div> <!-- /container -->
</body>
</html>