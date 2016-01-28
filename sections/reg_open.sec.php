<?php 
/**
 * Module:      reg_open.sec.php
 * Description: This module houses information regarding registering for the competition,
 *              judging dates, etc. Shown while the registration window is open.
 *
 */
/* ---------------- PUBLIC Pages Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Public pages have certain variables in common that build the page:

	$warningX = any warnings
  
	$primary_page_info = any information related to the page
	
	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page
	
	$page_infoX = the bulk of the information on the page.
	$help_page_link = link to the appropriate page on help.brewcompetition.com
	$print_page_link = the "Print This Page" link
	$competition_logo = display of the competition's logo
	
	$labelX = the various labels in a table or on a form
	$messageX = various messages to display
	
	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";
	
Declare all variables empty at the top of the script. Add on later...
	$warning1 = "";
	$primary_page_info = "";
	$header1_1 = "";
	$page_info1 = "";
	$header1_2 = ""; 
	$page_info2 = "";
	
	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */

$message1 = "";
$header1_2 = ""; 
$page_info2 = "";
$header1_3 = ""; 
$page_info3 = "";
$header1_4 = ""; 
$page_info4 = "";
$header1_5 = ""; 
$page_info5 = "";
$header1_6 = ""; 
$page_info6 = "";
$header1_7 = ""; 
$page_info7 = "";
$header1_8 = ""; 
$page_info8 = "";






$header1_1 .= "<h2>Judge and Steward Registration is <span class='text-success'>Open</span></h2>"; 
if (($registration_open == "1") && (!isset($_SESSION['loginUsername']))) { 
	$page_info1 .= "<p>If you <em>have not</em> registered and are willing to be a judge or steward, <a href='".build_public_url("register","judge","default",$sef,$base_url)."'>please register</a>.</p>";
	$page_info1 .= sprintf("<p>If you <em>have</em> registered, <a href=\"%s\">log in</a> and then choose <em>Edit Account</em> from the My Account menu indicated by the <span class=\"fa fa-user\"></span> icon on the top menu.</p>",build_public_url("login","default","default","default",$sef,$base_url));
}

elseif (($registration_open == "1") && (isset($_SESSION['loginUsername']))) { 
	$page_info1 .= "<p>Since you have already registered, you can <a href='".build_public_url("list","default","default","default",$sef,$base_url)."'>check your account info</a> to see whether you have indicated that you are willing to judge and/or steward.</p>";
	$page_info1 .= "";
}
else $page_info1 .= sprintf("<p>If you are willing to judge or steward, please return to register on or after %s.</p>",$judge_open);


if ($entry_window_open == 1) {
	$header1_2 .= "<h2>Entry Registration is <span class='text-success'>Open</a></h2>"; 
	$page_info2 .= "<p>";
	$page_info2 .= "To add your entries into the system, ";
	if (!isset($_SESSION['loginUsername'])) $page_info2 .= "please proceed through the <a href='".build_public_url("register","default","default","default",$sef,$base_url)."'>registration process</a> or <a href='".build_public_url("login","default","default","default",$sef,$base_url)."'>log in</a> if you already have an account.";
	else $page_info2 .= "use the <a href='".build_public_url("brew","entry","add","default",$sef,$base_url)."'>add an entry form</a>.";
	$page_info2 .= "</p>";
}

$header1_3 .= "<a name='rules'></a><h2>Rules</h2>";
$page_info3 .= $row_contest_rules['contestRules'];




// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

echo $header1_2;
echo $page_info2;
echo $header1_1;
echo $page_info1;

echo $header1_3;
echo $page_info3;
echo $header1_4;
echo $page_info4;
echo $header1_5;
echo $page_info5;
echo $header1_6;
echo $page_info6;
echo $header1_6;
echo $page_info7;
echo $header1_8;
echo $page_info8;



?>