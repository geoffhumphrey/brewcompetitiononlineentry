<?php
require('paths.php');
require('includes/url_variables.inc.php');

// Force download of uploaded scoresheet PDF
// Discourages random viewing of scoresheets by inputting direct URL
if ((isset($_SESSION['loginUsername'])) && ($section == "pdf-download")) {
	header("Content-disposition: attachment; filename=$id.pdf");
	header("Content-type: application/pdf");
	readfile(USER_DOCS."$id.pdf");
}

// Upload Function
elseif ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == "0") && ($section == "default")) {
	
	// Redirect if script accessed directly
	if (empty($_FILES['file'])) {
		$errorGoTo = "index.php?section=admin&go=upload&msg=0";
		header(sprintf("Location: %s", $errorGoTo));
		exit;
	}
	
	// Define variables directory to upload to
	$ds = DIRECTORY_SEPARATOR; // Directory separator
	if (($action == "default") || ($action == "html")) $storeFolder = USER_IMAGES;
	if (($action == "docs") || ($action == "html_docs")) $storeFolder = USER_DOCS;
	$backlist = array('php', 'php3', 'php4', 'phtml', 'exe'); // Restrict file extensions
	$valid_chars_regex = "A-Za-z0-9_-\s "; // Characters allowed in the file name (in a Regular Expression format)
	
	/* 
	// Commenting out by request - GitHub Issue #623
	// Change chmod permission if needed
	chmod($storeFolder, 0755);
	
	// Redirect if chmod can't be changed via php
	if (!chmod($storeFolder,0755)) {
		$errorGoTo = "index.php?section=admin&go=upload&msg=755";
		header(sprintf("Location: %s", $errorGoTo));
		exit;
	}
	*/
	
	// Security check variables
	$max_size = 10000000; // Limit size of upload to 10MB
	$file_mimes = array('image/jpeg','image/jpg','image/gif','image/png','application/pdf'); // Allowable file mime types
	$file_exts  = array('.jpeg','.jpg','.png','.gif','.pdf'); // Allowable file extensions
		
	// If file present without errors
	if ((!empty($_FILES['file'])) && ($_FILES['file']['error'] == 0)) { 
	
		$file_type = $_FILES['file']['type'];
		$file_ext = strtolower(substr($_FILES['file']['name'],strrpos($_FILES['file']['name'],".")));
		
		/*
		echo $file_type."<br>";
		echo $file_ext."<br>";
		echo $_FILES['file']['name']."<br>";
		echo $storeFolder;
		*/
		
		// If file type is on the blacklist
		$file_extension = explode('.', $_FILES['file']['name']);
		if ((in_array(end($file_extension), $backlist)) || (!in_array($file_ext, $file_exts))) {
			if ($action == "html") $errorGoTo = "index.php?section=admin&go=upload&action=html&msg=30";
			else $errorGoTo = "index.php?section=admin&go=upload&msg=30";
			header(sprintf("Location: %s", $errorGoTo));
			exit;
		}
		
		// Do upload if all parameters met
		if (($_FILES['file']['size'] <= $max_size) && (in_array($file_type, $file_mimes)) && (in_array($file_ext, $file_exts)))  {
			$tempFile = $_FILES['file']['tmp_name'];          
			$targetPath = $storeFolder;
			$targetFile =  $targetPath. $_FILES['file']['name'];
			//echo $targetFile;
			move_uploaded_file($tempFile,$targetFile);
			
			// Redirect if using single upload option
			if ($action == "html") {
				$updateGoTo = "index.php?section=admin&go=upload&action=html&msg=29";
				header(sprintf("Location: %s", $updateGoTo));
				exit;
			}
			
			if ($action == "html_docs") {
				$updateGoTo = "index.php?section=admin&go=upload_scoresheets&action=html&msg=29";
				header(sprintf("Location: %s", $updateGoTo));
				exit;
			}
		}
		
	}
	
	else {
		// Redirect if using single upload option
		if ($action == "html") {
			$updateGoTo = "index.php?section=admin&go=upload&action=html&msg=3";
			header(sprintf("Location: %s", $updateGoTo));
			exit;
		}
		
		if ($action == "html_docs") {
			$updateGoTo = "index.php?section=admin&go=upload_scoresheets&action=html_docs&msg=3";
			header(sprintf("Location: %s", $updateGoTo));
			exit;
		}
	}
	
}
// Redirect if script accessed directly and/or session parameters not met
else {
        $errorGoTo = "index.php?section=login&msg=0";
		header(sprintf("Location: %s", $errorGoTo));
}
?> 