<?php
require('paths.php');
require(INCLUDES.'url_variables.inc.php');
require(LIB.'common.lib.php');

// Force download of uploaded scoresheet PDF
// Discourages random viewing of scoresheets by inputting direct URL
if ((isset($_SESSION['loginUsername'])) && ($section == "pdf-download")) {
	header("Content-disposition: attachment; filename=$id.pdf");
	header("Content-type: application/pdf");
	readfile(USER_DOCS."$id.pdf");
}

// Upload Function
elseif ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == "0") && ($section == "default")) {

	/**
	 * Check for CSRF token.
	 * If tokens match, continue with process.
	 * If not, redirect to 403 page.
	 */

	$token = filter_input(INPUT_POST,'token',FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	if (((!$token) || ($token !== $_SESSION['token'])) || (empty($_FILES['file']))) {
		session_unset();
		session_destroy();
		session_write_close();
		$redirect = $base_url."403.php";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);
		header($redirect_go_to);
		exit();
	}
	
	// Define variables directory to upload to
	$ds = DIRECTORY_SEPARATOR; // Directory separator
	if (($action == "default") || ($action == "html")) $target_path = USER_IMAGES;
	if (($action == "docs") || ($action == "html_docs")) $target_path = USER_DOCS;
	$backlist = array('php', 'php3', 'php4', 'phtml', 'exe'); // Restrict file extensions
	$valid_chars_regex = "A-Za-z0-9_-\s "; // Characters allowed in the file name (in a Regular Expression format)
	
	// Security check variables
	if (HOSTED) $max_size = 4000000;
	else $max_size = 5000000; // Limit size of upload to 5MB
	$file_mimes = array('image/jpeg','image/jpg','image/gif','image/png','application/pdf'); // Allowable file mime types
	$file_exts  = array('.jpeg','.jpg','.png','.gif','.pdf'); // Allowable file extensions
		
	// If file present without errors
	if ((!empty($_FILES['file'])) && ($_FILES['file']['error'] == 0)) { 
	
		$file_type = $_FILES['file']['type'];
		$file_ext = strtolower(substr($_FILES['file']['name'],strrpos($_FILES['file']['name'],".")));
		
		// If file type is on the blacklist
		$file_extension = explode('.', $_FILES['file']['name']);
		if ((in_array(end($file_extension), $backlist)) || (!in_array($file_ext, $file_exts))) {
			if ($action == "html") $errorGoTo = "index.php?section=admin&go=upload&action=html&msg=30";
			else $errorGoTo = "index.php?section=admin&go=upload&msg=30";
			$redirect = prep_redirect_link($errorGoTo);
			$redirect_go_to = sprintf("Location: %s", $redirect);
			header($redirect_go_to);
			exit();
		}
		
		// Upload if all parameters met
		if (($_FILES['file']['size'] <= $max_size) && (in_array($file_type, $file_mimes)) && (in_array($file_ext, $file_exts)))  {

			$renamed_file = str_replace(' ', '_', $_FILES['file']['name']);
			$renamed_file = preg_replace('/\s+/', '', $renamed_file);
			$renamed_file = strtolower($renamed_file);
			$renamed_file = filter_var($renamed_file, FILTER_UNSAFE_RAW, FILTER_ENCODE_LOW | FILTER_ENCODE_HIGH);
			$renamed_file = strip_tags($renamed_file);
			$renamed_file = stripcslashes($renamed_file);
        	$renamed_file = stripslashes($renamed_file);
        	$renamed_file = scrub_filename($renamed_file);
			$renamed_file = remove_accents($renamed_file);
			
			// Generate temp file
			$temp_file = $_FILES['file']['tmp_name']; 
			
			// Define the target file and path, convert to lowercase
			$target_file = $target_path.$renamed_file;
			
			// Delete any file that has the same name as uploaded file in the target directory
			if (file_exists($target_path.$_FILES['file']['name'])) unlink($target_path.$_FILES['file']['name']);
			
			// Delete any file that has the same name as the target file in the target directory
			if (file_exists($target_file)) unlink($target_file);
			
			// Move the temp file to the target directory
			move_uploaded_file($temp_file,$target_file);
			
			if ($action == "html") $updateGoTo = "index.php?section=admin&go=upload&action=html&msg=29";
			else $updateGoTo = "index.php?section=admin&go=upload_scoresheets&action=html&msg=29";
			$redirect = prep_redirect_link($updateGoTo);
			$redirect_go_to = sprintf("Location: %s", $redirect);
			header($redirect_go_to);
			exit();
		
		}
		
	}
	
	else {
		
		if ($action == "html") $updateGoTo = "index.php?section=admin&go=upload&action=html&msg=3";
		else $updateGoTo = "index.php?section=admin&go=upload_scoresheets&action=html_docs&msg=3";
		$redirect = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $redirect);
		header($redirect_go_to);
		exit();
	
	}
	
}

// Redirect if script accessed directly and/or session parameters not met
else {
    session_unset();
    session_destroy();
    session_write_close();
    $redirect = $base_url."403.php";
    $redirect = prep_redirect_link($redirect);
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
?> 