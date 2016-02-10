<?php

session_start();

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == "0")) {

	$max_size = 2000000;

	$file_mimes = array('image/jpeg','image/jpg','image/gif','image/png'); // Allowable file Mime Types.

	$file_exts  = array('.jpg','.png','.gif'); // Allowable file extension names.



	$ds = DIRECTORY_SEPARATOR;

	$storeFolder = 'user_images';



	if ((!empty($_FILES['file'])) && ($_FILES['file']['error'] == 0)) {

		$file_type = $_FILES['file']['type']; 

		$file_name = $_FILES['file']['name'];

		$file_ext = strtolower(substr($file_name,strrpos($file_name,".")));

		

		if (($_FILES['file']['size'] <= $max_size) && (in_array($file_type, $file_mimes)) && (in_array($file_ext, $file_exts)))  {

			$tempFile = $_FILES['file']['tmp_name'];          

			$targetPath = dirname( __FILE__ ).$ds.$storeFolder.$ds;

			$targetFile =  $targetPath. $_FILES['file']['name'];

			move_uploaded_file($tempFile,$targetFile);	

		}    

	}

}

else {

        HandleError('No Session was found.');

}

?> 