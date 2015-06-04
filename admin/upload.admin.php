<?php 
require('../paths.php');
require(CONFIG.'bootstrap.php');


$imageSrc = $base_url."images/";

	$fileCornfirm = "default";
	if (isset($_GET['fileConfirm'])) {
	  $fileConfirm = (get_magic_quotes_gpc()) ? $_GET['fileConfirm'] : addslashes($_GET['fileConfirm']);
	}
	
	//Mmaximum file size.
	$MAX_SIZE = 2000000;
								
	//Allowable file Mime Types.
	$FILE_MIMES = array('image/jpeg','image/jpg','image/gif','image/png');
	
	//Allowable file ext. names.         
	$FILE_EXTS  = array('.jpg','.png','.gif');                                
	
	$site_name = $_SERVER['HTTP_HOST'];
	$url_dir = "http://".$_SERVER['HTTP_HOST'];
	$url_this =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$upload_dir = (USER_IMAGES);
	$upload_url = $url_dir."/user_images/";
	
	$message = "";
	
	// Create Upload Directory
	 if (!is_dir($upload_dir)) {
	  if (!mkdir($upload_dir))
		die ("Upload_files directory doesn't exist and creation failed.");
	  if (!chmod($upload_dir,0755))
		die ("Change permission to 755 failed.  You will probably need to change the folder's permission manually.  Consult your FTP program or ISP's documentation for chmod (folder permissions).");
	
	}
	
	
	// Process User's Request
	if ($_REQUEST['del'] && $DELETABLE)  {
	  $resource = fopen("log.txt","a");
	  fwrite($resource,date("Ymd h:i:s")."DELETE - $_SERVER[REMOTE_ADDR]"."$_REQUEST[del]\n");
	  fclose($resource);
	  
	  if (strpos($_REQUEST[del],"/.")>0);                      // possible hacking
	  else if (strpos($_REQUEST[del],$upload_dir) === false);  // possible hacking
	  else if (substr($_REQUEST[del],0,6)==$upload_dir) {
		unlink($_REQUEST[del]);
		print "<script>window.location.href='?action=upload&amp;section=".$section."</script>";
	  }
	}
	else if ($_FILES['userfile']) {
		// Uncomment if you want a log file.
		//$resource = fopen("log.txt","a");
		//fwrite($resource,date("Ymd h:i:s")."UPLOAD - $_SERVER[REMOTE_ADDR]"
				//.$_FILES['userfile']['name']." "
				//.$_FILES['userfile']['type']."\n");
		// fclose($resource);
	
		$file_type = $_FILES['userfile']['type']; 
		$file_name = $_FILES['userfile']['name'];
		$file_ext = strtolower(substr($file_name,strrpos($file_name,".")));
	
	  //File Size Check
	  if ($_FILES['userfile']['size'] > $MAX_SIZE) 
		$message .= "The file size is over 2MB.  Please adjust the size and try again.";
	  //File Type/Extension Check
	  else if (!in_array($file_type, $FILE_MIMES) && !in_array($file_ext, $FILE_EXTS))
		$message .= "Sorry, that file type is not allowed to be uploaded.  Acceptable file type extensions are .jpg, .png, and .gif.";
	  else
		$message .= do_upload($upload_dir, $upload_url);
	  
	  print "<script>window.location.href='?action=upload&msg=$message'</script>";
	}
	else if (!$_FILES['userfile']);
	else 
		$message .= "Invalid file specified.";
	
	// List Files in the directory
	$handle=opendir($upload_dir);
	$filelist = "";
	while ($file = readdir($handle)) {
	   if(!is_dir($file) && !is_link($file)) {
		  	$filelist .= "<tr>\n";
			$filelist .= "<td width=\"25%\" nowrap class=\"data-left\"><a href=\"".$base_url."user_images/$file\"  id=\"modal_window_link\">".$file."</a></td>\n";
			$filelist .= "<td width=\"25%\" nowrap class=\"data\">".date("l, F j, Y H:i", filemtime($upload_dir.$file))."</td>\n";
			if ($_SESSION['userLevel'] <= "1") $filelist .= "<td class=\"data\"><a href =\"?action=upload&amp;section=confirm&fileConfirm=".$file."\"><img src=\"".$imageSrc."bin_closed.png\" border=\"0\"></a></td>\n";
			else $filelist .="<td>&nbsp;</td>\n";
			$filelist .= "</tr>\n";
	   }
	}
	
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
	  if (!chmod($file_path,0777))
		$message .= "Change permission to 777 failed.";
	  else
		$message .= ($result)?"The label image $file_name was uploaded successfully." : "An error has occurred, please try again.";
	  return $message;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload Image</title>
<link href="<?php echo $base_url; ?>css/print.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $base_url; ?>css/sorting.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
<link rel="stylesheet" href="<?php echo $base_url; ?>css/jquery.ui.timepicker.css?v=0.3.0" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.ui.timepicker.js?v=0.3.0"></script>
<link rel="stylesheet" href="<?php echo $base_url; ?>js_includes/fancybox/jquery.fancybox.css?v=2.0.2" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/fancybox/jquery.fancybox.pack.js?v=2.0.2"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#modal_window_link").fancybox({
				'width'				: '85%',
				'maxHeight'			: '85%',
				'fitToView'			: false,
				'scrolling'         : 'auto',
				'openEffect'		: 'elastic',
				'closeEffect'		: 'elastic',
				'openEasing'     	: 'easeOutBack',
				'closeEasing'   	: 'easeInBack',
				'openSpeed'         : 'normal',
				'closeSpeed'        : 'normal',
				'type'				: 'iframe',
				'helpers' 			: {	title : { type : 'inside' } },
				<?php if ($modal_window == "false") { ?>
				'afterClose': 		function() { parent.location.reload(true); }
				<?php } ?>
			});

		});
	</script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#sortable').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[0,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			null,
			null,
			{ "asSorting": [  ] }
			]
		} );
	} );
</script>
<style type="text/css">
	#content-inner a:link {
		color:#00F;
	}
	#content-inner a:visited, a:active {
		
	}
	
	#content-inner a:hover {
		text-decoration: underline;
	}
	
	.button { 
	border: 1px solid #aaaaaa;
	background-color: #cccccc;
	font-weight: bold;
	padding: 3px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	} 
	
	.button:hover {
	border: 1px solid #aaaaaa;
	background-color: #bec8d8;
	cursor: pointer;
	}
	
	input, textarea, select, submit {
	font-size: 1em;
	border: 1px solid #c0c0c0;
	background-color: #eeeeee;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	}
</style>
</head>
<body>
<div id="container">
<div id="content">
	<div id="content-inner">
    <?php if ($msg != "default") echo "<div class='error'>".$msg."</div>"; ?>
	<h2>Upload Images</h2>
    <?php if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0)) { ?>
	<?php if ($section == "default") { ?>
	<form name="upload" id="upload" ENCTYPE="multipart/form-data" method="post">
	<table class="dataTable">
	<tr>
		<td class="dataLabel" width="5%">Image File:</td>
		<td class="data" width="10%"><input name="userfile" type="file" class="submit" id="userfile" size="60"></td>
    	<td class="data"><input type="submit" class="button" value="Upload"></td>
	</tr>
	</table>
	</form>
	<h2>Files in the Directory</h2>
	<table class="dataTable" id="sortable">
    <thead>
    	<tr>
            <th>File Name</th>
            <th>Date Uploaded</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        	<?php echo $filelist; ?>
    </tbody>
	</table>
	<?php } 
	if ($section == "confirm") { ?>
	<h3>Delete Image</h3>
	<table>
  	<tr>
		<td width="5%" rowspan="2" nowrap><a href="<?php echo $base_url; ?>user_images/<?php echo $fileConfirm; ?>" id="modal_window_link"><img src="<?php echo $base_url; ?>user_images/<?php echo $fileConfirm; ?>" border="0" width="100"></a></td>
		<td width="5%" nowrap class="data">Are you sure you want to delete <?php echo $fileConfirm; ?>?</td>
   		<td width="5%" nowrap class="data"><a href="?action=upload&amp;section=delete&fileConfirm=<?php echo $fileConfirm; ?>">Yes</a></td>
    	<td class="data"><a href="?action=upload">No</a></td>
	</tr>
  	<tr>
    	<td width="5%" nowrap>&nbsp;</td>
    	<td width="5%" nowrap class="data">&nbsp;</td>
    	<td class="data">&nbsp;</td>
  	</tr>
	</table>
	<?php } ?>
	<?php if ($section == "delete") { unlink($upload_dir.$fileConfirm); ?>
	<h2>Delete Image</h2>
	<table>
	<tr>
		<td class="error"><?php echo $fileConfirm; ?> deleted.</td>
    	<td class="data"><a href="?action=upload">Back to List</a></td>
	</td>
	</table>
	<?php } ?>
    <?php } else echo "<div class='error'>Only Top-Level Admin users can upload images.</div>"; ?>
	</div>
</div>
</div>
</body>
</html>