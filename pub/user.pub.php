<?php
/**
 * Module:      user.pub.php 
 * Description: This module houses the functionality for users to add/update enter their
 *              user name and password information. 
 * 
 */

// Verify if current user is authorized to make changes to the user account

$edit_enable = FALSE;
$lead_msg = $header_text_113;
if ($_SESSION['userLevel'] == 0) $lead_msg .= " <small>".$header_text_114."</small>";

if ((($filter == "admin") && ($_SESSION['userLevel'] == 0)) || (($filter == "default") && ($id == $_SESSION['user_id']))) {
	$edit_user_enable = 1;
	$edit_enable = TRUE;
}

else $edit_user_enable = 0;

if (((($_SESSION['loginUsername'] == $_SESSION['user_name'])) || ($_SESSION['userLevel'] <= "1")) && ($edit_enable)) {

// Build Variables
$user_help_link = "";
$user_help_link_text = "";

if ($action == "password") {
	$user_help_link .= "change_password.html";
	$user_help_link_text .= "Change Password Help";
	if ($filter == "admin") $lead_msg = "You are changing ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s Password.";
	else $lead_msg = $user_text_004;
}

if ($action == "username") {
	$user_help_link .= "change_email_address.html";
	$user_help_link_text .= "Change Email Address Help";
	if ($filter == "admin") $lead_msg = "You are changing ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s Email Address (User Name).";
	else $lead_msg = sprintf("%s: <small class=\"text-muted\">%s</small>.", $user_text_005, $_SESSION['user_name']);
}

?>
<?php if ($action == "username") { ?>
<script src="<?php echo $js_url; ?>registration_checks.min.js"></script>
<?php } // end if ($action == "username") ?>
<p class="lead"><?php echo $lead_msg; ?></p>
<form id="submit-form" role="form" class="form-horizontal hide-loader-form-submit needs-validation" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $action; ?>&amp;action=edit&amp;dbTable=<?php echo $users_db_table; ?>&amp;filter=<?php echo $filter; ?>&amp;id=<?php if ($filter == "admin") echo $row_brewer['uid']; else echo $_SESSION['user_id']; ?>" method="POST" name="form1" novalidate>
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo $_SESSION['user_session_token']; ?>">
<input name="user_name_old" type="hidden" value="<?php if ($filter == "admin") echo $row_brewer['brewerEmail']; else echo $_SESSION['user_name']; ?>">
<input type="hidden" name="userEdit" value="<?php echo $edit_user_enable; ?>">
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=list","default",$msg,$id); ?>">
<?php } ?>

<?php if ($action == "username") { ?>

	<div class="row mb-3">
        <label for="user_name" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_new." ".$label_email; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="user_name" name="user_name" type="email" onBlur="checkAvailability()" onchange="AjaxFunction(this.value);" placeholder="" data-error="" required>
            <div class="help-block invalid-feedback text-danger"><?php echo $user_text_000; ?></div>
            <div id="msg_email" class="mt-2"></div>
			<div id="username-status" class="mt-2"></div>
        </div>
    </div>

	<div class="row mb-3">
        <label for="sure" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_sure; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="sure" value="Y" id="sure_0" required> 
                <label class="form-check-label"><?php echo $label_yes; ?></label>
            </div>
            <div class="help-block invalid-feedback text-danger"><?php echo $user_text_003; ?></div>
        </div>
    </div>

    <div class="bcoem-admin-element d-print-none">
        <div class="mb-3 mt-5 row">
            <div class="col-xs-12 col-sm-3 col-lg-2"></div>
            <div class="col-xs-12 col-sm-9 col-lg-10 d-grid">
                <button name="submit" type="submit" class="btn btn-lg btn-primary" ><?php echo $label_change_email; ?><i class="ms-2 fa fa-fw fa-envelope"></i></button>
            </div>
        </div>
    </div>

<?php } ?>

<?php if ($action == "password") { ?>	
    <div class="row mb-3">
        <label for="passwordOld" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_old." ".$label_password; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" name="passwordOld" type="password" placeholder="" id="passwordOld" required>
            <div class="help-block invalid-feedback text-danger"><?php echo $user_text_001; ?></div>
        </div>
    </div>

	<div class="row mb-3">
        <label for="password" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_new." ".$label_password; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" name="password" type="password" placeholder="" id="password-entry" required>
            <div class="help-block invalid-feedback text-danger"><?php echo $user_text_002; ?></div>
        </div>
    </div>
    
    <div class="row mb-3" id="pwd-container">
		<label class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_password_strength; ?></strong></label>
		<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
			<div class="pwd-strength-viewport-progress"></div>
			<div id="length-help-text" class="small"></div>
		</div>
	</div>

    <div class="row mb-3">
        <label for="password-confirm" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-2"></i><?php echo $label_confirm_password; ?></strong></label>
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
            <input class="form-control password-field" name="password-confirm" id="password-confirm" type="password" required>
            <div class="help-block mt-1 invalid-feedback text-danger"><?php echo $login_text_024; ?></div>
            <div id="password-error" class="help-block mt-1 text-danger"><?php echo $login_text_023; ?></div>
        </div>
    </div>

    <div class="bcoem-admin-element d-print-none">
        <div class="mb-3 mt-5 row">
            <div class="col-xs-12 col-sm-3 col-lg-2"></div>
            <div class="col-xs-12 col-sm-9 col-lg-10 d-grid">
                <button id="submit-button" name="submit" type="submit" class="btn btn-lg btn-primary" ><?php echo $label_change_password; ?><i class="ms-2 fa fa-fw fa-key"></i></button>
            </div>
        </div>
    </div>

<?php } ?>
</form>
<?php } // end if ((($_SESSION['loginUsername'] == $_SESSION['user_name'])) || ($_SESSION['userLevel'] <= "1"))
else echo "<div class='lead'>".$lead_msg."</div>"; ?>