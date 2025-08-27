<?php

// Redirect if directly accessed
if ((!isset($_SESSION['prefs'.$prefix_session])) || ((isset($_SESSION['prefs'.$prefix_session])) && (!isset($base_url)))) {
    $redirect = "../../index.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

// Build alerts
$alert_text_004 = sprintf("<strong>%s</strong> %s",$alert_text_002,$alert_text_003);
$alert_text_007 = sprintf("<strong>%s</strong> <a href=\"".$base_url."index.php?section=admin&amp;action=add&amp;go=dropoff\" class=\"alert-link\">%s</a>",$alert_text_005,$alert_text_006);
$alert_text_010 = sprintf("<strong>%s</strong> <a href=\"".$base_url."index.php?section=admin&amp;action=add&amp;go=judging\" class=\"alert-link\">%s</a>",$alert_text_008,$alert_text_009);
$alert_text_013 = sprintf("<strong>%s</strong> <a href=\"".$base_url."index.php?section=admin&amp;action=add&amp;go=contacts\" class=\"alert-link\">%s</a>",$alert_text_011,$alert_text_012);
$alert_text_018 = sprintf("<strong>%s</strong> <a class=\"alert-link hide-loader\" href=\"".$base_url."includes/process.inc.php?action=convert_bjcp\" data-confirm=\"%s\" data-confirm-title=\"%s\" data-confirm-cancel=\"%s\" data-confirm-proceed=\"%s\">%s</a> %s",$alert_text_014,$alert_text_016,$label_please_confirm,$label_cancel,$label_yes,$alert_text_015,$alert_text_017);
$alert_text_022 = sprintf("<strong>%s</strong> %s",$alert_text_020,$alert_text_021);
$alert_text_026 = sprintf("<strong>%s</strong> <a class=\"alert-link\" href=\"",$alert_text_023);
if ($section == "step6") $alert_text_026 .= "setup.php?section=step6";
else $alert_text_026 .= "index.php?section=admin&amp;go=dropoff";
$alert_text_026 .= sprintf(">%s</a>&nbsp;&nbsp;&nbsp;",$alert_text_024);
$alert_text_026 .= "<a class=\"alert-link\" href=\"";
if ($section == "step6") $alert_text_026 .= "setup.php?section=step7"; else $alert_text_026 .= "index.php?section=admin";
$alert_text_026 .= sprintf(">%s</a>",$alert_text_025);
$alert_text_035 = sprintf("<strong>%s</strong> %s",$alert_text_033,$alert_text_034);
$alert_text_038 = sprintf("<strong>%s</strong> %s",$alert_text_036,$alert_text_037);
$alert_text_041 = sprintf("<strong>%s</strong> %s",$alert_text_039,$alert_text_040);
$alert_text_045 = sprintf("<strong>%s</strong> %s %s %s.",$alert_text_042,$alert_text_043,$alert_text_044,$entry_closed);
$alert_text_048 = sprintf("<strong>%s</strong> %s",$alert_text_046,$alert_text_047);
$alert_text_051 = sprintf("<strong>%s</strong> %s",$alert_text_049,$alert_text_050);
$alert_text_054 = sprintf("<strong>%s</strong> %s",$alert_text_052,$alert_text_053);
$alert_text_058 = sprintf("<strong>%s</strong> %s",$alert_text_055,$alert_text_056);
$alert_text_061 = sprintf("<strong>%s</strong> %s",$alert_text_059,$alert_text_060);
$alert_text_064 = sprintf("<strong>%s</strong> %s",$alert_text_062,$alert_text_063);
$alert_text_067 = sprintf("<strong>%s</strong> %s",$alert_text_065,$alert_text_066);
$alert_text_071 = sprintf("<strong>%s</strong> %s",$alert_text_068,$alert_text_070);
$alert_text_075 = sprintf("<strong>%s</strong> %s",$alert_text_072,$alert_text_073);
if (!$steward_limit) $alert_text_075 .= " ".$alert_text_074;
$alert_text_079 = sprintf("<strong>%s</strong> %s ",$alert_text_076,$alert_text_077);
if (!$judge_limit) $alert_text_079 .= " ".$alert_text_078;

if ($msg != "default") { 

  /**
   *  
   * V3 TODO:
   * - Rework msg numbers for all public areas that are now combined. Do not reuse or repeat a number.
   * - V2 non-admin sections with msg: default, login, list (my account), contact, pay, user, evaluation, brew, register
   * - V2 non-admin sections WITHOUT msg: entry, volunteers, sponsors, brewer
   * - V3 pay section is deprecated. Pay functions are now part of the list section.
   * 
   * ---------------------------------
   * 
   * V2.7 Messages (Default section):
   * 1 - (Success) Info added successfully.
   * 2 - (Success) Info edited successfully.
   * 3 - (Danger)  There was an error. Please try again.
   * 4 - (Warning) You must be an administrator to access any admin functions.
   * 5 - (Success) You have been logged out. Log in again?
   * 6 - (Warning) A password reset token has been generated and emailed to the address associated with your account. If you do not receive the email within a reasonable amount of time, check your email account's SPAM folder. If it is not there, contact a competition official or site administrator to reset your password for you.
   * 7 - The Awards Presentation will be available publicly after results are published.
   * 8 - (Warning) Archived data is not available.
   * 9 - (Success) Info updated successfully.
   * 
   * ---------------------------------
   * 
   * V2.7 Messages (login section)
   * 1 - (Warning) Sorry, there was a problem with your last login attempt. Please make sure your email address and password are correct.
   * 2 - (Success) A password reset token has been generated and emailed to the address associated with your account. If you do not receive the email within a reasonable amount of time, check your email account's SPAM folder. If it is not there, contact a competition official or site administrator to reset your password for you.
   * 3 - (Danger)  You have been logged out. Log in again?
   * 4 - (Warning) Your verification question does not match what is in the database. Please try again.
   * 5 - (Success) Your ID verification information has been sent to the email address associated with your account.
   * 6 - (Warning) The passwords do not match. Please try again.
   * 7 - (Warning) The email you entered is not associated with the provided token. Please try again.
   * 8 - (Warning) Your password has been reset successfully. You may now log in with the new password.
   * 9 on - same as default
   * 99 - (Info)   Please log in to access your account.
   * 
   * ---------------------------------
   * 
   * V2.7 Messages (list section):
   * 1 and 2 same as default
   * 3  - (Success) Your email address has been updated.
   * 4  - (Warning) Your password has been updated.
   * 5  - (Danger)  Info deleted successfully.
   * 6  - (Warning) You should verify all your entries imported using BeerXML. ** DEPRECATE **
   * 7  - (Success) You have registered.
   * 8  - (Warning) You have reached the entry limit. Your entry was not added.
   * 9  - (Warning) You have reached the entry limit for the subcategory. Your entry was not added.
   * 10 - (Warning) You have registered as a steward.
   * 11 - (Danger)  Your scoresheets could not be properly generated. Please contact the organizers of the competition.
   * 12 - (Danger)  You can only edit your own account information.
   *******
   * V3 Add:
   * 13 - (Warning) Your online payment has been received and the transaction has been completed. Please note that you may need to wait a few minutes for the payment status to be updated here - be sure to refresh this page or access your entries list. You will receive a payment receipt via email from PayPal. Please make sure to print the receipt and attach it to one of your entries as proof of payment.
   * 14 - (Danger)  Your online payment has been cancelled.
   * 15 - (Success) The code has been verified.
   * 16 - (Danger)  Sorry, the code you entered was incorrect.

   * ---------------------------------
   * 
   * V2.7 Messages (contact section)
   * 1 same as default
   * 2 - (Danger) CAPTCHA was not successful. Please try again.
   * 3 on - same as default
   * 
   * V2.7 Messages (pay section)
   * 1-9 same as default
   * 10 - (Warning) Your online payment has been received and the transaction has been completed. Please note that you may need to wait a few minutes for the payment status to be updated here - be sure to refresh this page or access your entries list. You will receive a payment receipt via email from PayPal. Please make sure to print the receipt and attach it to one of your entries as proof of payment.
   * 11 - (Danger)  Your online payment has been cancelled.
   * 12 - (Success) The code has been verified.
   * 13 - (Danger)  Sorry, the code you entered was incorrect.
   * 
   * ---------------------------------
   * 
   * V2.7 Messages (user section)
   * 1  - (Warning) The email address provided is already in use, please provide another email address.
   * 2  - (Danger)  There was a problem with the last request, please try again.
   * 3  - (Danger)  Your current password was incorrect. Please try again.
   * 4 on - none
   * 
   * ---------------------------------
   * 
   * V2.7 Messages (evaluation section)
   * 1 and 2 - none
   * 3 - (Success) Info added successfully.
   * 4 - (Warning) Info edited successfully.
   * 5 - (Success) Info deleted successfully.
   * 6 on - none
   * 
   * ---------------------------------
   * 
   * V2.7 Messages (register section)
   * 1 - (Danger)  Sorry, there was a problem with your last login attempt. Please try again. ** DEPRECATE **
   * 2 - (Danger)  Sorry, the user name you entered is already in use. ** DEPRECATE **
   * 3 - (Danger)  The user name provided is not a valid email address. Please enter a valid email address. ** DEPRECATE **
   * 4 - (Warning) CAPTCHA was not successful. Please try again. 
   * 5 - (Danger)  The email addresses you entered do not match. Please try again. **DEPRECATE**
   * 6 - (Danger)  The AHA number you entered is already in the system.
   * 
   * ---------------------------------
   * 
   * V2.7 Messages (brew section)
   * Same as default
   */


  /**
   * Four types of alerts: success, info, warning, danger
   * Majority of $msg numbers for alerts are associated
   * with successful actions, thus no array after defining
   * the other three.
   * 
   * REWORK with new cross-app standard message numbers.
   */

  $info_msg_alerts = array(7,11,12,99);
  $warning_msg_alerts = array(0,4,6,8,37,10);
  $danger_msg_alerts = array(3,13,15,18,19,24,27,30,98,755);
  $success_msg_alerts = array();

  $success_msg = array();
  $info_msg = array();
  $warning_msg = array();
  $danger_msg = array();

  if ($section == "default") {

    $info_msg = array(15);
    $success_msg = array(13,18,19);
    $warning_msg = array(11,14,16,17,20);
    $danger_msg = array(0);
    unset($danger_msg_alerts[1]);
    unset($danger_msg_alerts[2]);
    unset($danger_msg_alerts[3]);
    unset($danger_msg_alerts[4]);
    unset($info_msg_alerts[1]);

  }

  if ($section == "login") {
    $warning_msg = array(1,4,7,9);
    $danger_msg = array(1);
  }

  if ($section == "list") {
    $success_msg = array(3,10,15);
    $danger_msg = array(5,11,12,14,16);
    $warning_msg = array(8,9,13);
    unset($danger_msg_alerts[0]);
    unset($danger_msg_alerts[1]);
    unset($danger_msg_alerts[2]);
    unset($warning_msg_alerts[3]);
    unset($info_msg_alerts[0]);
    unset($info_msg_alerts[1]);
    unset($info_msg_alerts[3]);
  }

  if ($section == "contact") $warning_msg[] = 2;

  if ($section == "pay") {
    $success_msg = array(10,12);
    $danger_msg = array(11);
    unset($warning_msg_alerts[3]);
    unset($info_msg_alerts[0]);
    unset($info_msg_alerts[1]);
  }

  if ($section == "user") {
    $warning_msg = array(1);
    $danger_msg = array(2);
  }

  if ($section == "evaluation") {
    $success_msg = array(3);
    unset($danger_msg_alerts[0]);
  }

  if ($section == "register") {
    $danger_msg = array(5);
    $warning_msg = array(1,2,4,6);
  }

  $success_msg_alerts = array_merge($success_msg_alerts,$success_msg);
  $info_msg_alerts = array_merge($info_msg_alerts,$info_msg);
  $warning_msg_alerts = array_merge($warning_msg_alerts,$warning_msg);
  $danger_msg_alerts = array_merge($danger_msg_alerts,$danger_msg);

  $alert_type = "success";
  $alert_icon = "fa-check-circle";

  if (in_array($msg,$info_msg_alerts)) {
      $alert_type = "primary";
      $alert_icon = "fa-info-circle";
  }

  elseif ((in_array($msg,$warning_msg_alerts)) || (strstr($msg,"1-"))) {
      $alert_type = "warning";
      $alert_icon = "fa-exclamation-circle";
  }

  elseif (in_array($msg,$danger_msg_alerts)) {
      $alert_type = "danger";
      $alert_icon = "fa-exclamation-circle";
  }

  elseif (in_array($msg,$success_msg_alerts)) {
      $alert_type = "success";
      $alert_icon = "fa-check-circle";
  }

  if (!empty($output)) {

    $errors_display = "";

    if ((!empty($_SESSION['error_output'])) || (!empty($error_output))) {
        
        $errors_display .= "<div class=\"bcoem-admin-element\">";
        $errors_display .= "<div class=\"alert alert-danger alert-dismissible d-print-none fade in\">";
        $errors_display .= "<p><span class=\"fa fa-lg fa-exclamation-circle\"></span> <strong>Error(s)</strong></p>";
        $errors_display .= "<p>The following errors were logged on the last MySQL server call:</p>";
        $errors_display .= "<ul>";
        
        if (!empty($error_output)) {
            foreach ($error_output as $key => $value) {
                $errors_display .= "<li>".$value."</li>";
            }
        }

        if (!empty($_SESSION['error_output'])) {
            foreach ($_SESSION['error_output'] as $key => $value) {
                $errors_display .= "<li>".$value."</li>";
            }
        }
            
        $errors_display .= "</ul>";
        $errors_display .= "</div>";
        $errors_display .= "</div>";
        
    }

    if (!empty($errors_display)) echo $errors_display;

    echo create_bs_alert("alert-auto-dismiss",$alert_type,"",$output,$alert_icon,"",FALSE);
    
    echo $output_extend;

  } // end if (!empty($output))

} // end if ($msg != "default")

// Stacked warnings are within a single alert
$stacked_alert_msg_info = "";
$stacked_alert_msg_warning = "";

if ($logged_in) { 
  
  if ($section == "brew") { 
      
    if (($registration_open != 1) && ($entry_window_open != 1) && ($_SESSION['userLevel'] > 1)) {
      if ($entry_window_open == 0) $alert_message_closed = "<strong>".$alert_text_029."</strong> ".$alert_text_027;
      if ($entry_window_open == 2) $alert_message_closed = "<strong>".$alert_text_029."</strong> ".$alert_text_028;
      $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_message_closed."</p>";
      //echo create_bs_alert("alert-add-edit-closed","warning","",$alert_message_closed,"fa-exclamation-circle","",TRUE);
    } 

    if (($entry_window_open == 1) && ($_SESSION['userLevel'] > 1) && (($comp_entry_limit) || ($comp_paid_entry_limit)) && ($action == "add") && ($go != "admin")) {
      $alert_message_closed = "<strong>".$alert_text_029."</strong> ".$alert_text_030; 
      $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_message_closed."</p>";
      //echo create_bs_alert("alert-add-edit-closed","warning","",$alert_message_closed,"fa-exclamation-circle","",TRUE);
    }

    if (($entry_window_open == 1) && ($_SESSION['userLevel'] > 1) && ($comp_entry_limit) && ($comp_paid_entry_limit) && ($remaining_entries == 0) && ($action == "add") && ($go != "admin")) { 
      $alert_message_closed = "<strong>".$alert_text_029."</strong> ".$alert_text_031; 
      $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_message_closed."</p>";
      //echo create_bs_alert("alert-add-edit-closed","warning","",$alert_message_closed,"fa-exclamation-circle","",TRUE);
    } 

    if (($registration_open == 1) && ($entry_window_open != 1) && ($_SESSION['userLevel'] > 1) && ($action == "add")) { 
      $alert_message_closed = "<strong>".$alert_text_029."</strong> ".$alert_text_032; 
      $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_message_closed."</p>";
      //echo create_bs_alert("alert-add-edit-closed","warning","",$alert_message_closed,"fa-exclamation-circle","",TRUE);
    } 
  
  } // end if ($section == "brew")

} // end if ($logged_in) 

else { 
  
  if (($registration_open == 0) && (!$ua) && ($section == "default") && ($msg == "default")) {
    $stacked_alert_msg_info .= "<p class=\"mb-1\">".$alert_text_035."</p>"; 
  } 

  if (($entry_window_open == 0) && (!$ua) && ($section == "default") && ($msg == "default")) { 
    $stacked_alert_msg_info .= "<p class=\"mb-1\">".$alert_text_038."</p>";
  } 

  if (($registration_open == 0) && (!$ua) && ($section != "admin") && ($judge_window_open == "0") && ($msg == "default")) { 
    $stacked_alert_msg_info .= "<p class=\"mb-1\">".$alert_text_041."</p>";
  } 

  if (($registration_open == 1) && ($entry_window_open == 1) && (!$ua) && ($section == "default") && ($comp_entry_limit) && ($msg == "default")) { 
    $stacked_alert_msg_info .= "<p class=\"mb-1\">".$alert_text_045."</p>";
  }

  if ((($registration_open == 0) || ($registration_open == 2)) && (!$ua) && ($section == "default") && ($judge_window_open == 1) && ($msg == "default")) { 
    $stacked_alert_msg_info .= "<p class=\"mb-1\">".$alert_text_071."</p>";
  }  

  if (($registration_open == 1) && ($entry_window_open == 1) && (!$ua) && ($section == "default") && ($comp_entry_limit_near_warning) && ($msg == "default")) { 
    $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_text_048."</p>";
  } 

  if (($registration_open == 1) && (!$ua) && ($section == "default") && ($comp_entry_limit) && ($msg == "default")) { 
    $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_text_051."</p>";
  } 
  
  if (($registration_open == 1) && (!$ua) && ($section == "default") && ($comp_paid_entry_limit) && ($msg == "default")) { 
    $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_text_054."</p>";
  }

  if (($registration_open == 2) && (!$ua) && ($section == "default") && ($judging_past > 0) && ($msg == "default")) { 
    $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_text_058."</p>";
  }   

  if (($entry_window_open == 2) && (!$ua) && ($section == "default") && ($judging_past > 0) && ($msg == "default")) { 
    $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_text_061."</p>";
  }   

  if (($dropoff_window_open == 2) && (!$ua) && ($section == "default") && ($judging_past > 0) && ($msg == "default")) { 
    $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_text_064."</p>";
  }   

  if (($shipping_window_open == 2) && (!$ua) && ($section == "default") && ($judging_past > 0) && ($msg == "default")) { 
    $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_text_067."</p>";
  }

  if (($judge_limit) && ($section == "register")) { 
    $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_text_075."</p>";
  }

  if (($steward_limit) && ($section == "register")) { 
    $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_text_079."</p>";
  } 

} // end if (!$logged_in) 

if (($registration_open == 1) && (!$ua) && (($section == "default") || ($section == "list")) && ((!$comp_entry_limit) || (!$comp_paid_entry_limit)) && ($msg == "default")) {

  $style_types_disabled = "";
    
  if (!empty($style_type_limits_alert)) {
      
    foreach ($style_type_limits_alert as $key => $value) {

      if ($value > 0) {
        if (array_key_exists($key, $style_types_translations)) $style_types_disabled .= strtolower($style_types_translations[$key])." (".strtolower($label_limit)." - ".$value."), ";
        else $style_types_disabled .= strtolower($key)." (".strtolower($label_limit)." - ".$value."), ";
      }

    }

    $style_types_disabled = rtrim($style_types_disabled,", ");

  }

  if (!empty($style_types_disabled)) { 
    $alert_text_999998 = "<strong>".$alert_text_093."</strong> ".$alert_text_094.": ".$style_types_disabled;
    $stacked_alert_msg_warning .= "<p class=\"mb-1\">".$alert_text_999998."</p>";
  } 

}

if ((MAINT) && ($logged_in) && ($_SESSION['userLevel'] == 0)) {
  $alert_text_999999 = "<strong>Your installation is in Maintenance Mode.</strong> As a Top-level Admin, you are able perform all administration functions as normal. All others who attempt to access the site will see the <a href=\"".$base_url."maintenance.php\">Maintenance page</a>.";
    echo create_bs_alert("alert-auto-dismiss","warning","",$alert_text_999999,"fa-exclamation-circle","",FALSE);
}

if (!empty($stacked_alert_msg_info)) {
  echo create_bs_alert("alert-multiple-stacked-info","info",$label_fyi,$stacked_alert_msg_info,"fa-info-circle","",TRUE);
}

if (!empty($stacked_alert_msg_warning)) {
  echo create_bs_alert("alert-multiple-stacked-warning","warning",$label_please_note,$stacked_alert_msg_warning,"fa-exclamation-circle","",TRUE);
}

?>