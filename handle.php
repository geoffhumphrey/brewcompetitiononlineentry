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
	
	// Define variables directory to upload to
	$ds = DIRECTORY_SEPARATOR; // Directory separator
	
	if (($action == "default") || ($action == "html")) $target_path = USER_IMAGES;
	else $target_path = USER_DOCS;
	
	// Limit size of upload
	if (HOSTED) $max_size = 4000000;
	else $max_size = 10000000;

	// Allowable file mime types and extensions for images
	if (($action == "default") || ($action == "html")) {	
		$file_mimes_allowed = array('image/jpeg','image/jpg','image/gif','image/png');
		$file_extensions_allowed  = array('.jpeg','.jpg','.png','.gif');
	}

	// Allowable file and mime types for documents (PDF only)
	else {
		$file_mimes_allowed = array('application/pdf');
		$file_extensions_allowed = array('.pdf');
	}

	// Restricted file extensions
	$backlist = array('php', 'php3', 'php4', 'phtml', 'exe');

	$restrict_upload = FALSE;
		
	// If file present without errors and target path is defined
	if ((!empty($_FILES['file'])) && ($_FILES['file']['error'] == 0) && (!empty($target_path))) {

		$file_type = $_FILES['file']['type'];
		$file_extension = strtolower(substr($_FILES['file']['name'],strrpos($_FILES['file']['name'],".")));
		$file_extension_array = explode('.', $_FILES['file']['name']);

		if (in_array(end($file_extension_array), $backlist)) $restrict_upload = TRUE;
		if (!in_array($file_extension, $file_extensions_allowed)) $restrict_upload = TRUE;
		if ($_FILES['file']['size'] > $max_size) $restrict_upload = TRUE;
		
		if ($restrict_upload) {

			// Redirect (only for SINGLE file upload function)
			if (($action == "html") || ($action == "html_docs")) {
				
				if (($action == "default") || ($action == "html")) $errorGoTo = "index.php?section=admin&go=upload&action=html&msg=30";
				else $errorGoTo = "index.php?section=admin&go=upload_scoresheets&action=html&msg=30";
				$redirect = prep_redirect_link($errorGoTo);
				$redirect_go_to = sprintf("Location: %s", $redirect);
				header($redirect_go_to);
				exit();

			}
		
		}

		// Upload not restricted
		else {

			// Perform upload if file mime type and extension is allowed
			if ((in_array($file_type, $file_mimes_allowed)) && (in_array($file_extension, $file_extensions_allowed)))  {

				// Call filename clean function
				$renamed_file = clean_filename($_FILES['file']['name']);

				// Make the filename all lowercase
				$renamed_file = strtolower($renamed_file);
				
				// Generate temp file
				$temp_file = $_FILES['file']['tmp_name']; 
				
				// Define the target file and path
				$target_file = $target_path.$renamed_file;
				
				// Delete any file that has the same name as uploaded file in the target directory
				if (file_exists($target_path.$_FILES['file']['name'])) unlink($target_path.$_FILES['file']['name']);
				
				// Delete any file that has the same name as the target file in the target directory
				if (file_exists($target_file)) unlink($target_file);
				
				// Move the temp file to the target directory
				move_uploaded_file($temp_file,$target_file);

				// Redirect (only for SINGLE file upload function)
				if (($action == "html") || ($action == "html_docs")) {

					if (($action == "default") || ($action == "html")) $updateGoTo = "index.php?section=admin&go=upload&action=html&msg=29";
					else $updateGoTo = "index.php?section=admin&go=upload_scoresheets&action=html&msg=29";
					$redirect = prep_redirect_link($updateGoTo);
					$redirect_go_to = sprintf("Location: %s", $redirect);
					header($redirect_go_to);
					exit();

				}

			}

		}
		
	}
	
	// Redirect if error
	else {
		
		if (($action == "default") || ($action == "html")) $updateGoTo = "index.php?section=admin&go=upload&action=html&msg=3";
		else $updateGoTo = "index.php?section=admin&go=upload_scoresheets&action=html&msg=3";
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