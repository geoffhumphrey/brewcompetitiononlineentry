<?php
// Build alerts
$alert_text_004 = sprintf("<strong>%s</strong> %s",$alert_text_002,$alert_text_003);
$alert_text_007 = sprintf("<strong>%s</strong> <a href=\"".$base_url."index.php?section=admin&amp;action=add&amp;go=dropoff\" class=\"alert-link\">%s</a>",$alert_text_005,$alert_text_006);
$alert_text_010 = sprintf("<strong>%s</strong> <a href=\"".$base_url."index.php?section=admin&amp;action=add&amp;go=judging\" class=\"alert-link\">%s</a>",$alert_text_008,$alert_text_009);
$alert_text_013 = sprintf("<strong>%s</strong> <a href=\"".$base_url."index.php?section=admin&amp;action=add&amp;go=contacts\" class=\"alert-link\">%s</a>",$alert_text_011,$alert_text_012);
$alert_text_018 = sprintf("<strong>%s</strong> <a class=\"alert-link hide-loader\" href=\"".$base_url."includes/process.inc.php?action=convert_bjcp\" data-confirm=\"%s\">%s</a> %s",$alert_text_014,$alert_text_016,$alert_text_015,$alert_text_017);
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
$alert_text_058 = sprintf("<strong>%s</strong> %s <a class=\"alert-link\" href=\"%s\">%s</a>.",$alert_text_055,$alert_text_056,build_public_url("login","default","default","default",$sef,$base_url),$alert_text_057);
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
   * Four types of alerts: success, info, warning, danger
   * Majority of $msg numbers for alerts are associated
   * with successful actions, thus no array after defining
   * the other three.
   */

  $info_msg_alerts = array(11,12,99);
  $warning_msg_alerts = array(0,4,6,8,37,10);
  $danger_msg_alerts = array(3,13,15,18,19,24,27,30,98,755);
  $success_msg_alerts = array();

  $success_msg = array();
  $info_msg = array();
  $warning_msg = array();
  $danger_msg = array();

  if ($section == "login") {
    $warning_msg = array(1,4,7);
    $danger_msg = array(1);
  }

  if ($section == "list") {
    $success_msg = array(3,10);
    $danger_msg = array(5,11,12);
    $warning_msg = array(8,9);
    unset($danger_msg_alerts[0]);
    unset($warning_msg_alerts[3]);
    unset($info_msg_alerts[0]);
    unset($info_msg_alerts[1]);
  }

  if ($section == "contact") $danger_msg[] = 2;

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
    $danger_msg = array(1,2,5,6);
    unset($warning_msg_alerts[2]);
  }

  if ($section == "brew") {
    $warning_msg = array(1);
  }

  if ($section == "admin") {
    $success_msg = array(4);
    $danger_msg = array(8,10);
    unset($warning_msg_alerts[1]);
    unset($warning_msg_alerts[3]);
    unset($warning_msg_alerts[5]);
  }

  $success_msg_alerts = array_merge($success_msg_alerts,$success_msg);
  $info_msg_alerts = array_merge($info_msg_alerts,$info_msg);
  $warning_msg_alerts = array_merge($warning_msg_alerts,$warning_msg);
  $danger_msg_alerts = array_merge($danger_msg_alerts,$danger_msg);

  $alert_type = "alert-success";
  $alert_icon = "fa-check-circle";

  if (in_array($msg,$info_msg_alerts)) {
    $alert_type = "alert-info";
    $alert_icon = "fa-info-circle";
  }

  elseif ((in_array($msg,$warning_msg_alerts)) || (strstr($msg,"1-"))) {
    $alert_type = "alert-warning";
    $alert_icon = "fa-exclamation-triangle";
  }

  elseif (in_array($msg,$danger_msg_alerts)) {
    $alert_type = "alert-danger";
    $alert_icon = "fa-exclamation-circle";
  }

  elseif (in_array($msg,$success_msg_alerts)) {
    $alert_type = "alert-success";
    $alert_icon = "fa-check-circle";
  }

  if (!empty($output)) {

?>
    <!-- User action alerts -->
    <div class="alert <?php echo $alert_type; ?> alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg <?php echo $alert_icon; ?>"></span> <?php echo $output; ?>
    </div>
    <?php echo $output_extend; ?>
<?php } 
  }
?>

<?php if (($logged_in) && ($_SESSION['userLevel'] <= 1) && ($section == "admin")) {
  $bjcp_2008 = FALSE;
?>
  <!-- Admin Alerts -->
  <?php if ($go == "make_admin") { ?>
      <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-exclamation-circle"></span> <strong><?php echo $alert_text_000; ?></strong>
      </div>
  <?php } ?>

  <?php if ($purge == "cleanup") { ?>
      <!-- Data cleanup complete -->
      <div class="alert alert-success alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-check-circle"></span> <?php echo $alert_text_001; ?>
      </div>
  <?php } ?>

    <?php if (($setup_free_access) && ($action != "print")) { ?>
      <!-- Setup free access true -->
      <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-exclamation-circle"></span> <strong><?php echo $alert_text_002; ?></strong>
      </div>
    <?php } ?>

    <?php if (($totalRows_dropoff == "0") && ($go == "default")) { ?>
      <!-- No Drop-off Dates -->
      <div class="alert alert-warning alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-exclamation-triangle"></span> <?php echo $alert_text_007; ?>
      </div>
    <?php } ?>

    <?php if (($totalRows_judging == "0") && ($go == "default")) { ?>
      <!-- No judging dates -->
      <div class="alert alert-warning alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-exclamation-triangle"></span> <?php echo $alert_text_010; ?>
      </div>
    <?php } ?>

    <?php if (($totalRows_contact == "0") && ($go == "default")) { ?>
      <!-- No competition dontacts -->
      <div class="alert alert-warning alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-exclamation-triangle"></span> <?php echo $alert_text_013; ?>
        </div>
    <?php } ?>
    <?php if ($bjcp_2008) { ?>
      <!-- BJCP 2008 convert to 2015 -->
      <div class="alert alert-info alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-info-circle"></span> <?php echo $alert_text_018; ?>
        </div>
    <?php } ?>
    <?php if ($purge == "purge") { ?>
      <!-- Purge completed -->
      <div class="alert alert-success alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-check-circle"></span> <strong><?php echo $alert_text_019; ?></strong>
        </div>
    <?php } ?>
<?php } // end if (($logged_in) && ($_SESSION['userLevel'] <= 1) && ($section == "admin") && ($go == "default")) ?>

<?php if (($logged_in) && ($section == "admin")) { ?>
  <?php if (($entries_unconfirmed > 0) && ($go == "entries") && ($dbTable == "default")) { ?>
      <!-- Unconfirmed entries -->
      <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-exclamation-circle"></span> <?php echo $alert_text_022; ?>
        </div>
    <?php } ?>
  <?php  if ((($section == "step7") || (($section == "admin") && ($go == "dropoff"))) && ($msg == "11")) { ?>
    <!-- Setup Add More Dropoffs? -->
    <div class="alert alert-info alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-info-circle"></span> <?php echo $alert_text_026; ?>
    </div>
  <?php } ?>
<?php } // end if ($section == "admin") ?>


<?php if ($logged_in) { ?>

  <?php if ($section == "admin") { ?>

    <?php if (($go == "entries") || ($go == "judging_scores") || ($go == "judging_scores_bos")) { ?>
    <style type="text/css">
      .sticky-alert {
        position: fixed;
        width: 100%;
        left: 0;
        top: 70px;
        z-index: 999;
      }
    </style>
    <section id="alert-update-button-enabled" class="container-fluid sticky-alert">
    <div class="alert alert-warning alert-dismissible hidden-print fade in" role="alert">
      <span class="fa fa-lg fa-exclamation-triangle"></span> <strong>Due to an error saving the previous data input, the Update button has been enabled.</strong> Any unsaved input will be saved if you select the Update button now. Otherwise, try again.
    </div>
    </section>
    <?php } ?>

  <?php } ?>

  <?php if ($section == "brew") { ?>

    <?php 
    if (($registration_open != 1) && ($entry_window_open != 1) && ($_SESSION['userLevel'] > 1)) {
      if ($entry_window_open == 0) $alert_message_closed = $alert_text_027;
      if ($entry_window_open == 2) $alert_message_closed = $alert_text_028;
    ?>
        <!-- Entry add/edit registration closed -->
        <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-exclamation-circle"></span> <strong><?php echo $alert_text_029; ?></strong> <?php echo $alert_message_closed; ?>
        </div>
        <?php } ?>

        <?php if (($entry_window_open == 1) && ($_SESSION['userLevel'] > 1) && (($comp_entry_limit) || ($comp_paid_entry_limit)) && ($action == "add") && ($go != "admin")) { ?>
        <!-- Open but competition entry limit reached - only allow editing -->
        <div class="alert alert-warning alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-exclamation-triangle"></span> <strong><?php echo $alert_text_029; ?></strong> <?php echo $alert_text_030; ?>
        </div>
        <?php } ?>

        <?php if (($entry_window_open == 1) && ($_SESSION['userLevel'] > 1) && ($comp_entry_limit) && ($comp_paid_entry_limit) && ($remaining_entries == 0) && ($action == "add") && ($go != "admin")) { ?>
        <!-- Open but personal entry limit reached - only allow editing -->
        <div class="alert alert-warning alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-exclamation-triangle"></span> <strong><?php echo $alert_text_029; ?></strong> <?php echo $alert_text_031; ?>
        </div>
        <?php } ?>

        <?php if (($registration_open == 1) && ($entry_window_open != 1) && ($_SESSION['userLevel'] > 1) && ($action == "add")) { ?>
        <!-- Registration open, but entry window not -->
        <div class="alert alert-warning alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-exclamation-triangle"></span> <strong><?php echo $alert_text_029; ?></strong> <?php echo $alert_text_032; ?>
        </div>
        <?php } ?>

        <?php if ((NHC) && ($_SESSION['userLevel'] > 1) && ($registration_open != 1) && ($prefix != "final_")) { ?>
        <!-- Special for NHC - close adding or editing during the entry window as well -->
        <div class="alert alert-warning alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-exclamation-triangle"></span> <strong>Adding/editing entries is not available.</strong> NHC registration has closed.
        </div>
        <?php } ?>

        <?php if ((NHC) && ($_SESSION['userLevel'] > 1) && ($registration_open != 1) && ($entry_window_open != 1) && ($prefix == "final_")) { ?>
        <!-- Special for NHC - close adding or editing during the entry window as well -->
        <div class="alert alert-warning alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-lg fa-exclamation-triangle"></span> <strong>Adding/editing entries is not available.</strong> NHC registration has closed.
        </div>
        <?php } ?>

  <?php } // end if ($section == "brew") ?>
<?php } // end if ($logged_in) ?>

<?php if (!$logged_in) { ?>
    
  <?php if (($registration_open == 0) && (!$ua) && ($section == "default") && ($msg == "default")) { ?>
    <!-- Account registration not open yet -->
    <div class="alert alert-info alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-info-circle"></span> <?php echo $alert_text_035; ?>
    </div>
  <?php } ?>

  <?php if (($entry_window_open == 0) && (!$ua) && ($section == "default") && ($msg == "default")) { ?>
    <!-- Entry registration not open yet -->
    <div class="alert alert-info alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-info-circle"></span> <?php echo $alert_text_038; ?>
    </div>
  <?php } ?>

  <?php if (($registration_open == 0) && (!$ua) && ($section != "admin") && ($judge_window_open == "0") && ($msg == "default")) { ?>
    <!-- Judge/steward registration not open yet -->
    <div class="alert alert-info alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-info-circle"></span> <?php echo $alert_text_041; ?>
    </div>
  <?php } ?>

  <?php if (($registration_open == 1) && ($entry_window_open == 1) && (!$ua) && ($section == "default") && ($comp_entry_limit) && ($msg == "default")) { ?>
    <!-- Account and entry registration open -->
    <div class="alert alert-info alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-info-circle"></span> <?php echo $alert_text_045; ?>
    </div>
  <?php } ?>

  <?php if (($registration_open == 1) && ($entry_window_open == 1) && (!$ua) && ($section == "default") && ($comp_entry_limit_near_warning) && ($msg == "default")) { ?>
    <!-- Entry limit nearly reached -->
    <div class="alert alert-warning alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-exclamation-triangle"></span> <?php echo $alert_text_048; ?>
    </div>
  <?php } ?>

  <?php
  if (($registration_open == 1) && (!$ua) && ($section == "default") && ($comp_entry_limit) && ($msg == "default")) { ?>
    <!-- Entry limit reached -->
    <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-exclamation-circle"></span> <?php echo $alert_text_051; ?>
    </div>
  <?php } ?>

  <?php if (($registration_open == 1) && (!$ua) && ($section == "default") && ($comp_paid_entry_limit) && ($msg == "default")) { ?>
    <!-- Entry limit reached -->
    <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-exclamation-circle"></span> <?php echo $alert_text_054; ?>
    </div>
  <?php } ?>

  <?php if (($registration_open == 2) && (!$ua) && ($section == "default") && ($judging_past > 0) && ($msg == "default")) { ?>
    <!-- Account registration closed -->
    <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-exclamation-circle"></span> <?php echo $alert_text_058; ?>
    </div>
  <?php }   ?>

  <?php if (($entry_window_open == 2) && (!$ua) && ($section == "default") && ($judging_past > 0) && ($msg == "default")) { ?>
    <!-- Entry registration closed -->
    <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-exclamation-circle"></span> <?php echo $alert_text_061; ?>
    </div>
  <?php }   ?>

  <?php if (($dropoff_window_open == 2) && (!$ua) && ($section == "default") && ($judging_past > 0) && ($msg == "default")) { ?>
    <!-- Drop-off window closed -->
    <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-exclamation-circle"></span> <?php echo $alert_text_064; ?>
    </div>
  <?php }   ?>

  <?php if (($shipping_window_open == 2) && (!$ua) && ($section == "default") && ($judging_past > 0) && ($msg == "default")) { ?>
    <!-- Drop-off window closed -->
    <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-exclamation-circle"></span> <?php echo $alert_text_067; ?>
    </div>
  <?php }   ?>

  <?php if ((($registration_open == 0) || ($registration_open == 2)) && (!$ua) && ($section == "default") && ($judge_window_open == 1) && ($msg == "default")) { ?>
    <!-- Account and entry registration closed, but Judge/steward registration open -->
    <div class="alert alert-info alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-info-circle"></span> <?php echo $alert_text_071; ?>
    </div>
  <?php } ?>
  
  <?php if (($judge_limit) && ($section == "register")) { ?>
    <!-- Limit of judges reached -->
    <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-lg fa-exclamation-circle"></span> <?php echo $alert_text_075; ?>
    </div>
  <?php } ?>

  <?php if (($steward_limit) && ($section == "register")) { ?>
      <!-- Limit of stewards reached -->
      <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span class="fa fa-lg fa-exclamation-circle"></span> <?php echo $alert_text_079; ?>
      </div>
  <?php } ?>

<?php } // end if (!$logged_in) ?>

<?php if ($go == "default") { ?>
    <div id="no-js-alert" class="alert alert-danger">
        <i class="fa fa-lg fa-exclamation-circle"></i> <strong><?php echo $alert_text_087; ?></strong>
    </div>
<?php } ?>

<?php if (($recently_updated) && ((isset($_SESSION['update_summary'])) && (!empty($_SESSION['update_summary']))) && ($section == "admin") && ($go == "default")) { ?>
    <div class="alert alert-info alert-dismissible hidden-print fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <p><span class="fa fa-lg fa-info-circle"></span> Your installation was recently updated to <strong>BCOE&amp;M <?php echo $current_version_display; ?></strong>. Select the <?php echo $current_version_display; ?> Update Summary button below for a full account of what changes were made.</p>
    </div>
<?php } ?>

<?php if (($recently_updated) && ((isset($_SESSION['update_errors'])) && ($_SESSION['update_errors'] == 1)) && (($section == "admin") && ($go == "default"))) { ?>
    <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <p><span class="fa fa-lg fa-exclamation-circle"></span> <strong>Warning: Update Errors</strong></p>
        <p>Your installation was recently updated to <strong>BCOE&amp;M <?php echo $current_version_display; ?></strong>, but there were errors during the update process which may result in unexpected behaviors.</p>
        <p>Select the <?php echo $current_version_display; ?> Update Summary button below for details. Errors are in <span class="text-danger">red text</span>.</p>
    </div>
<?php } ?>