<?php

// Multiple file upload
// http://webrewrite.com/create-drag-drop-multiple-file-upload-using-dropzonejs/

/*

foreach($_FILES['file']['name'] as $index=>$name) {
	$filename = $name;
	
	if(!file_exists("images/".$filename)) {
		move_uploaded_file($_FILES["file"]["tmp_name"][$index],"images/" . $filename);
	}
} 

*/
 
/*
require('../paths.php');
require(CONFIG.'bootstrap.php');

function do_upload($upload_dir, $upload_url) {
	
	$temp_name = $_FILES['userfile']['tmp_name'];
	$file_name = $_FILES['userfile']['name']; 
	$file_name = str_replace("\\","",$file_name);
	$file_name = str_replace("'","",$file_name);
	$file_path = $upload_dir.$file_name;
	
	// File Name Check
	if ($file_name == "") { 
		$message .= "Invalid file name specified";
		return $message;
	}
	
	$result  =  move_uploaded_file($temp_name, $file_path);
	if (!chmod($file_path,0777)) $message .= "Change permission to 777 failed.";
	else $message .= ($result)?"<strong>The label image $file_name was uploaded successfully.</strong>" : "An error has occurred, please try again.";
	return $message;
}

$fileCornfirm = "default";

if (isset($_GET['fileConfirm'])) {
  $fileConfirm = (get_magic_quotes_gpc()) ? $_GET['fileConfirm'] : addslashes($_GET['fileConfirm']);
}

$max_size = 2000000; // Maximum file size.
$file_mimes = array('image/jpeg','image/jpg','image/gif','image/png'); // Allowable file Mime Types.
$file_exts  = array('.jpg','.png','.gif'); // Allowable file extension names.                                

$site_name = $_SERVER['HTTP_HOST'];

// Define URLs
$url_dir = "http://".$_SERVER['HTTP_HOST'];
$url_this =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

// Define destination folder
$upload_dir = (USER_IMAGES);
$upload_url = $url_dir."/user_images/";

// Build messaging
$message = "";

// Create upload directory if necessary
if (!is_dir($upload_dir)) {
  	if (!mkdir($upload_dir))
		$message .= "<p><strong>The user_images directory doesn't exist.</strong> Creation of folder failed.</p>";
  	if (!chmod($upload_dir,0755))
		$message .= "<p><strong>Change permission to 755 failed.</strong>  You will probably need to change the folder's permission manually.  Consult your FTP program or ISP's documentation for chmod (folder permissions).</p>";

}

// Process a delete request
if ($_REQUEST['del'])  {
	
	// Uncomment if you want a log file
	//$resource = fopen("log.txt","a");
	//fwrite($resource,date("Ymd h:i:s")."DELETE - $_SERVER[REMOTE_ADDR]"."$_REQUEST[del]\n");
	//fclose($resource);
	
	if (strpos($_REQUEST[del],"/.")>0);                      // possible hacking
	elseif (strpos($_REQUEST[del],$upload_dir) === false);  // possible hacking
	elseif (substr($_REQUEST[del],0,6)==$upload_dir) {
		unlink($_REQUEST[del]);
		$message .= "Deleted.";
		// header(sprintf("Location: %s", $redirect));
		
  	}
}

// Process the upload
elseif ($_FILES['userfile']) {
	
	// Uncomment if you want a log file
	//$resource = fopen("log.txt","a");
	//fwrite($resource,date("Ymd h:i:s")."UPLOAD - $_SERVER[REMOTE_ADDR]".$_FILES['userfile']['name']." ".$_FILES['userfile']['type']."\n");
	//fclose($resource);
	
	$file_type = $_FILES['userfile']['type']; 
	$file_name = $_FILES['userfile']['name'];
	$file_ext = strtolower(substr($file_name,strrpos($file_name,".")));

  // File size check
  if ($_FILES['userfile']['size'] > $max_size) 
	$message .= "<p><strong>The file size is over two (2) megabytes for $file_name.</strong> Please adjust the size and try again.</p>";
  
  // File type/extension check
  elseif (!in_array($file_type, $file_mimes) && !in_array($file_ext, $file_exts))
	$message .= "<p><strong>Sorry, the file type for $file_name is not allowed to be uploaded.</strong>  Acceptable file type extensions are .jpg, .png, and .gif.</p>";
  
  else	$message .= do_upload($upload_dir, $upload_url);
  
  // header(sprintf("Location: %s", $redirect));
  
}

elseif (!$_FILES['userfile']);
else $message .= "<p><strong>Invalid file specified for $file_name.</strong></p>";

// List Files in the directory
$handle = opendir($upload_dir);
$filelist = "";
while ($file = readdir($handle)) {
   if(!is_dir($file) && !is_link($file)) {
		$filelist .= "<ul>\n";
		$filelist .= "<li><a href=\"".$base_url."user_images/$file\"  id=\"modal_window_link\">".$file."</a> - ".date("l, F j, Y H:i", filemtime($upload_dir.$file))."</li>";
		$filelist .= "</ul>\n";
   }
}
*/
?>