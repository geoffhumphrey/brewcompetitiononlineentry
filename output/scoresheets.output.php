<?php 
/**
 * Module:      scoresheets.output.php 
 * Description: This module copies the pdf of the scoresheets of a given entry from a directory in which the pdfs are protected from direct access from the web (setup by the .htaccess in root) to a directory which is write-enabled and then show this temporary copy to the entrant. The name of the file in the temporary directory is appended by a pseudo-randomly generated sequence of numbers, to prevent unwelcomed users to guess the name of the file and access it. The pseudo-random number is generated and handed down from the brewers_entries.sec.php script. It is okay to use a pseudo-random number instead of a more robust random number since the respective files are temporary. Besides, the judging number itself is not publicly available, so it is not easily guessable either.
 * 
 */

require ('../paths.php');
require (CONFIG.'bootstrap.php');
require (LANG.'language.lang.php');

if (isset($_SESSION['loginUsername'])) {

	if (($brewer_info['brewerEmail'] != $_SESSION['loginUsername']) && ($row_logged_in_user['userLevel'] > 1)) { 
	  	echo sprintf("<html><head><title>%s</title></head><body>",$label_error);
  		echo sprintf("<p>%s</p>",$header_text_104);
	  	echo "</body></html>";
  		exit();
	}
   
	$scoresheetfilename = $_GET['scoresheetfilename'];
	$scoresheetfile = USER_DOCS.$scoresheetfilename;
	$randomfilename = $_GET['randomfilename'];
	$scoresheetrandomfilerelative = "user_temp/".$randomfilename;
	$scoresheetrandomfile = USER_TEMP.$randomfilename;
									
	if (copy($scoresheetfile, $scoresheetrandomfile)) {
		header('Content-type: application/pdf');
		
		// Force Download
		if (isset($_GET['download'])) header("Content-disposition: attachment; filename=$scoresheetfilename"); 
		else header('Content-Disposition: inline; filename="' . $scoresheetfilename . '"');
	
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($scoresheetrandomfile));
		header('Accept-Ranges: bytes');
   		ob_clean();
	    flush();
		
		
    	readfile($scoresheetrandomfile);
		
	}
	else {
  		echo sprintf("<html><head><title>%s</title></head><body>",$label_error);
	  	echo sprintf("<p>%s</p>",$output_text_004);
  		echo "</body></html>";
	  	exit();
	}
//	exit();
} // end if logged in

?>