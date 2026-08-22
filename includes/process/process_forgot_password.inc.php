<?php
ob_start();
require (INCLUDES.'db_tables.inc.php');
require (CLASSES.'phpass/PasswordHash.php');
$hasher = new PasswordHash(8, false);

if ($action == "reset") {

	$user_name = normalize_email_username($_POST['loginUsername']);
	
	// First, check if the sanitized email entered corresponds to the token provided
	$db_conn->where('user_name', $user_name);
	$db_conn->where('userToken', $token);
	$row_reset = $db_conn->getOne($users_db_table, "id");
	$totalRows_reset = $db_conn->count;
	
	// If no match, redirect to try again
	if ($totalRows_reset == 0) {
		$updateGoTo = sprintf($base_url."index.php?section=login&go=password&action=reset-password&msg=7&token=%s",$token);
		$updateGoTo = prep_redirect_link($updateGoTo);
		header(sprintf("Location: %s", $updateGoTo)); 
		exit();
	}

	if ($totalRows_reset == 1) {
		
		// Check and see if both entered passwords match
		// If so, hash and insert hash into DB
		if (((string) $_POST['newPassword1'] === (string) $_POST['newPassword2'])) {

			// Hash
			$hash = password_hash($_POST['newPassword1'], PASSWORD_BCRYPT);

			// Insert the hash into the database
			$update_table = $prefix."users";
			$data = [
				'password' => $hash,
				'userToken' => NULL,
				'userTokenTime' => NULL
			];
			$db_conn->where ('id', $row_reset['id']);
			$result = $db_conn->update ($update_table, $data);

			if ($result) $updateGoTo = $base_url."index.php?msg=18";
			else $updateGoTo = sprintf($base_url."index.php?section=login&go=password&action=reset-password&msg=9&token=%s",$token);
			
			// Redirect
			
			$updateGoTo = prep_redirect_link($updateGoTo);
			header(sprintf("Location: %s", $updateGoTo)); 
			exit();
			
		}
        $updateGoTo = sprintf($base_url."index.php?section=login&go=password&action=reset-password&msg=6&token=%s",$token);
        header(sprintf("Location: %s", $updateGoTo));
        exit;	
		
	}	
	
}

?>