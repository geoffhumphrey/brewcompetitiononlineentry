<?php
declare(strict_types=1);
require(__DIR__ . '/paths.php');
require(INCLUDES.'url_variables.inc.php');
require(LIB.'common.lib.php');

// Force download of uploaded scoresheet PDF
// Discourages random viewing of scoresheets by inputting direct URL
if ((isset($_SESSION['loginUsername'])) && ($section == "pdf-download")) {

	// $id becomes part of a filesystem path below, so it's restricted to a safe
	// character set (no slashes, no "..") before being used at all. That alone
	// isn't trusted as the only guard: the resolved real path is also required
	// to still be inside USER_DOCS, so even a bug in the character check above
	// can't turn this into a way to read arbitrary files on the server.
	$pdf_path = NULL;

	if ((preg_match('/^[a-zA-Z0-9._-]+$/', $id)) && (!str_contains($id, '..'))) {

		$user_docs_real = realpath(USER_DOCS);
		$candidate_real = realpath(USER_DOCS.$id.".pdf");

		if (($user_docs_real !== FALSE) && ($candidate_real !== FALSE) && (str_starts_with($candidate_real, $user_docs_real.DIRECTORY_SEPARATOR))) {
			$pdf_path = $candidate_real;
		}

	}

	if ($pdf_path !== NULL) {
		header("Content-disposition: attachment; filename=$id.pdf");
		header("Content-type: application/pdf");
		readfile($pdf_path);
	}

	else {
		header("HTTP/1.1 404 Not Found");
	}

}

// Upload Function
elseif ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0) && ($section == "default")) {
	
	// Define variables directory to upload to
	$ds = DIRECTORY_SEPARATOR; // Directory separator
	
	if (($action == "default") || ($action == "html")) $target_path = USER_IMAGES;
	else $target_path = USER_DOCS;
	
	// Limit size of upload
	$max_size = 20000000;

	// Allowable file mime types and extensions for images
	if (($action == "default") || ($action == "html")) {	
		$file_mimes_allowed = ['image/jpeg','image/jpg','image/gif','image/png','image/webp','image/svg+xml'];
		$file_extensions_allowed  = ['.jpeg','.jpg','.png','.gif','.webp','.svg'];
	}

	// Allowable file and mime types for documents (PDF only)
	else {
		$file_mimes_allowed = ['application/pdf'];
		$file_extensions_allowed = ['.pdf'];
	}

	// Restricted file extensions
	$backlist = ['php', 'php3', 'php4', 'phtml', 'exe'];

	$restrict_upload = FALSE;
		
	// If file present without errors and target path is defined
	if ((!empty($_FILES['file'])) && ($_FILES['file']['error'] == 0) && (!empty($target_path))) {

		// $_FILES['file']['type'] is client-supplied and unreliable (browsers/OSes report PDFs
		// as application/octet-stream often enough that the single-value PDF allow-list below
		// would silently reject a real PDF) - detect the type server-side from the file itself,
		// matching the mime_content_type() check admin/upload_scoresheets.admin.php already uses.
		$file_type = mime_content_type($_FILES['file']['tmp_name']);
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

			// Perform upload if file mime type and extension is allowed.
			// A "contains" match, not an exact one: libmagic (what mime_content_type() wraps)
			// isn't perfectly consistent across server configs about what it appends/how it
			// formats a type string, and admin/upload_scoresheets.admin.php's own PDF detection
			// already relies on stripos() rather than an exact match for exactly this reason.
			$file_mime_ok = FALSE;
			foreach ($file_mimes_allowed as $file_mime_allowed) {
				if (stripos($file_type, $file_mime_allowed) !== FALSE) { $file_mime_ok = TRUE; break; }
			}

			if (($file_mime_ok) && (in_array($file_extension, $file_extensions_allowed)))  {

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
				
				// Move the temp file to the target directory. Its return value was previously
				// never checked, so a failure here (permissions, disk space, path) still
				// reported success below - report the real outcome instead.
				$move_ok = move_uploaded_file($temp_file,$target_file);

				// Redirect (only for SINGLE file upload function)
				if (($action == "html") || ($action == "html_docs")) {

					if ($move_ok) {
						if (($action == "default") || ($action == "html")) $updateGoTo = "index.php?section=admin&go=upload&action=html&msg=29";
						else $updateGoTo = "index.php?section=admin&go=upload_scoresheets&action=html&msg=29";
					}
					else {
						if (($action == "default") || ($action == "html")) $updateGoTo = "index.php?section=admin&go=upload&action=html&msg=3";
						else $updateGoTo = "index.php?section=admin&go=upload_scoresheets&action=html&msg=3";
					}
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