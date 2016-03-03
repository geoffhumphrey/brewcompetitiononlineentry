<?php
require('paths.php');
require(CONFIG.'bootstrap.php');

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
			session_start();
			session_regenerate_id(true); 
			
			$password_redirect .= "&msg=2";
			
			$_SESSION['qrPasswordOK'] = "1";
			
			
			// If id was passed in URL, check in that entry
			if ($id != "default") {
				
				$updateSQL = sprintf("UPDATE %s SET brewReceived='1' WHERE id='%s';",$brewing_db_table,$id);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				
			}
			
			
			
		}
		
		// If not successful, destroy session and redirect
		else {
			
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

if (($action == "default") && ($go == "default") && ($id != "default") && (isset($_SESSION['qrPasswordOK']))) {
	
	$checkin_redirect = $base_url."qr.php?action=default&go=success";
	
	// Check to see if the entry is in the DB
	$query_entry = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE id='%s'",$prefix."brewing",$id);
	$entry = mysqli_query($connection,$query_entry) or die (mysqli_error($connection));
	$row_entry = mysqli_fetch_assoc($entry);
	
	$entry_found = FALSE;
	if ($row_entry['count'] > 0) $entry_found = TRUE;
	
	// If so, mark the entry as "received" and redirect (message 3)
	if ($entry_found) {
		
		$updateSQL = sprintf("UPDATE %s SET brewReceived='1' WHERE id='%s';",$brewing_db_table,$id);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		
		$checkin_redirect .= "&id=".$id."&msg=3";
		header(sprintf("Location: %s", $checkin_redirect));
		exit;
		
		
	}
	
	// If not, redirect to alert the user (message 4)
	else {
		
		$checkin_redirect .= "&id=".$id."&msg=4";
		header(sprintf("Location: %s", $checkin_redirect));
		exit;

	}
	
}


// Messages
$message = "";
$alert_type = "alert-danger";

if ($msg == "1") {
	$message .= "<span class=\"fa fa-exclamation-circle\"></span> <strong>Password incorrect.</strong> Please try again.";
}

if ($msg == "2") {
	$message .= "<span class=\"fa fa-check-circle\"></span> <strong>Password accepted.";
	if ($id != "default") $message .= " Entry number ".sprintf("%04d",$id)." is checked in!";
	$message .= "</strong>";
	$alert_type = "alert-success";
}

if ($msg == "3") {
	$message .= "<span class=\"fa fa-check-circle\"></span> <strong>Entry number ".sprintf("%04d",$id)." is checked in!</strong>";
	$alert_type = "alert-success";
}

if ($msg == "4") {
	$message .= "<span class=\"fa fa-exclamation-circle\"></span> <strong>Entry number ".sprintf("%04d",$id)." was not found in the database.</strong> Set the bottle(s) aside and alert the competition organizer.";
}

?>
<style>

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

.container-signin input[type="password"] {
  margin-bottom: 10px;
}

</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION['contestName']; ?> - Brew Competition Online Entry &amp; Management</title>
    
	<!-- Load jQuery / http://jquery.com/ -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	
    <!-- Load Bootstrap / http://www.getbootsrap.com -->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Load Bootstrap Form Validator / http://1000hz.github.io/bootstrap-validator -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.9.0/validator.min.js"></script>
    
    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    
    <!-- Load BCOE&M Custom Theme CSS - Contains Bootstrap overrides and custom classes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $theme; ?>" />
	
	<!-- Load BCOE&M Custom JS -->
    <script src="<?php echo $base_url; ?>js_includes/bcoem_custom.min.js"></script>
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
        <h1><?php echo $header_output; ?>: QR Code Check-In</h1>
    </div> 
    <?php if (!isset($_SESSION['qrPasswordOK'])) { ?>
    
        <div align="center" class="text-primary"><span class="fa fa-qrcode fa-5x"></span></div>
    
    <!-- Password Form if Not Signed In -->
	<form name="form1" action="<?php echo $base_url; ?>qr.php?action=password-check<?php if ($id != "default") echo "&amp;id=".$id; ?>" method="post">
        <p class="lead container-signin-heading">To check in entries via QR code, please provide the correct password.</p>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Password" autofocus required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign In</button>
   	</form>
	
    <?php } ?>
    
    <?php if (isset($_SESSION['qrPasswordOK'])) { ?>
    <p class="lead text-success"><strong>You are signed in.</strong></p>
    <p class="lead"><span class="fa fa-spinner fa-spin"></span> Waiting for the next QR input...</p>  
    <?php } ?>
    </div><!-- /container-signin -->
    
</div> <!-- /container -->
</body>
</html>