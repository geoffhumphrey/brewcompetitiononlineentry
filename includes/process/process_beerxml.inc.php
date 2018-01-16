<?php
if ((isset($_SERVER['HTTP_REFERER'])) && ($_SESSION['loginUsername'])) {
include (INCLUDES.'beerXML/input_beer_xml.inc.php');
	//Mmaximum file size.
	$MAX_SIZE = 2000000;

	//Allowable file Mime Types.
	$FILE_MIMES = array('image/jpeg','image/jpg','image/gif','image/png','application/msword');

	//Allowable file ext. names.
	$FILE_EXTS  = array('.xml','.txt');

	//Allow file delete? no, if only allow upload only
	$DELETABLE  = false;

	//$url_this =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

	if ($_FILES['userfile']) {
		$file_type = $_FILES['userfile']['type'];
		$file_name = $_FILES['userfile']['name'];
		$file_ext = strtolower(substr($file_name,strrpos($file_name,".")));
		$message = $_FILES['userfile']['tmp_name'];

		//File Size Check
		if ($_FILES['userfile']['size'] > $MAX_SIZE) $message = "The file size is over 2MB.  Please adjust the size and try again.";

		//File Type/Extension Check
		elseif (!in_array($file_type, $FILE_MIMES) && !in_array($file_ext, $FILE_EXTS)) $message = "Sorry, that file type is not allowed to be uploaded.  Acceptable file type extensions are: .xml, .txt";

		elseif (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$input = new InputBeerXML($_FILES['userfile']['tmp_name']);
			$insertedRecipes = $input->insertRecipes();
		}
	}

	elseif (!$_FILES['userfile']) $message = "userfile check failed";
	else $message = "Invalid file specified.";
}
else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>