<?php
/**
 * Module:      nav.sec.php
 * Description: This module houses the main navigation.
 *
 */

// Turn off SEF for error pages
if ($section >= 400) $sef = "false";
else $sef = $sef;

$add_entry_link_show = FALSE;
$show_entries = TRUE;
$nav_register_entrant_show = TRUE;

if ($comp_entry_limit) $nav_register_entrant_show = FALSE;
if ($comp_paid_entry_limit) $nav_register_entrant_show = FALSE;

if ($entry_window_open == 1) {
	if ($comp_entry_limit) $add_entry_link_show = FALSE;
	elseif ($comp_paid_entry_limit) $add_entry_link_show = FALSE;
	elseif ($remaining_entries <= 0) $add_entry_link_show = FALSE;
	else $add_entry_link_show = TRUE;
}

$active_class = " class=\"active\"";

if ($section == "asdfasdfasdfasdfasd") {
	$admin_link = "#";
	$admin_tooltip = "Admin Menu";
}

else {
	$admin_link = $base_url."index.php?section=admin";
	$admin_tooltip = "Admin Dashboard";
}

$print_icon = FALSE;

if (isset($_SESSION['loginUsername']))  {
    $paid_icon = "";
    $paid_icon .= " <small class=\"text-muted\">".$currency_symbol.number_format($total_to_pay,2)."</small>";
    if ($total_to_pay > 0) $paid_icon .= " <span class=\"fa fa-lg fa-exclamation-circle text-danger\" aria-hidden=\"true\" data-toggle=\"tooltip\" title=\"".$pay_text_033."\" data-placement=\"auto top\" data-container=\"body\"></span>";
    else $paid_icon .= " <span class=\"fa fa-lg fa-check-circle text-success\" aria-hidden=\"true\" data-toggle=\"tooltip\" title=\"".$pay_text_024."\" data-placement=\"auto top\" data-container=\"body\"></span>";
}

$help = bcoem_help($section,$go,$action,$filter);
if (!empty($help)) $help_icon = TRUE;
else $help_icon = FALSE;

// Set up general nav link variables
if ($section == "default") {
	$link_home= "#";
	$print_icon = TRUE;
	$active_class = " class=\"active\"";
	}
else {
	if (empty($base_url)) $link_home= "index.php";
	else $link_home = $base_url;
}

if ($section == "rules") {
	$link_rules = "#";
	$print_icon = TRUE;
}
else $link_rules = build_public_url("rules","default","default","default",$sef,$base_url);

if ($section == "entry") {
	$link_entry_info = "#";
	$print_icon = TRUE;
}
else $link_entry_info = build_public_url("entry","default","default","default",$sef,$base_url);

if ($section == "volunteers") { $link_volunteer_info = "#";  $print_icon = TRUE; }
else $link_volunteer_info = build_public_url("volunteers","default","default","default",$sef,$base_url);

if (($_SESSION['prefsSponsors'] == "Y") && ($_SESSION['sponsorCount'] > 0)) {
	if ($section == "sponsors") {
		$link_sponsors = "#";
		$print_icon = TRUE;
	}
	else $link_sponsors = build_public_url("sponsors","default","default","default",$sef,$base_url);
	$sponsors = TRUE;
}
else $sponsors = FALSE;

if ($section == "contact") $link_contacts = "#";
else $link_contacts = build_public_url("contact","default","default","default",$sef,$base_url);

if ($section == "register") $link_register = "#";
elseif (($judge_limit) && ($steward_limit)) $link_register = build_public_url("register","entrant","default","default",$sef,$base_url);
elseif (($registration_open != 1) && (!$ua) && (!isset($_SESSION['loginUsername'])) && ($judge_window_open == 1) && ($msg == "default")) $link_register = build_public_url("register","judge","default","default",$sef,$base_url);
elseif (($judge_window_open == "1") && ($registration_open == "2")) $link_register = build_public_url("register","judge","default","default",$sef,$base_url);
else $link_register = build_public_url("register","default","default","default",$sef,$base_url);

if ($section == "login") $link_login = "#";
else $link_login = build_public_url("login","default","default","default",$sef,$base_url);

if ($section == "logout") $link_logout = "#";
else $link_logout = build_public_url("logout","default","default","default",$sef,$base_url);

$qr_enable = FALSE;
$link_qr = "";
if (!empty($row_contest_dates['contestCheckInPassword'])) {
	if (($entry_window_open == 2) && ($dropoff_window_open == 2) && ($shipping_window_open == 2) && ($judging_past > 0) && (in_array($_SESSION['prefsEntryForm'],$barcode_qrcode_array))) $qr_enable = TRUE;
	$link_qr .= build_public_url("qr","default","default","default",$sef,$base_url);
}

// Session specific

$show_judge_steward_fields = TRUE;

if ($logged_in)  {

	if ($_SESSION['prefsProEdition'] == 1) {

		if (isset($_SESSION['brewerBreweryName'])) {
			// If registered as a brewery, will not be a judge/stewards/staff and may have entries
			// Only individuals can be judges, stewards, or staff; individuals will not have entries
			$show_judge_steward_fields = FALSE;
			$show_entries = TRUE;
			$add_entry_link_show = TRUE;

		}

		else {
			$show_entries = FALSE;
		}

	}

	if ($admin_user) {
		$link_admin = $base_url."index.php?section=admin";
		$print_icon = TRUE;
	}

	// Build Pay Link
	if ($section == "pay") {
		$link_pay = "#";
		if ($msg != "default") $print_icon = TRUE;
		}
	else $link_pay = build_public_url("pay","default","default","default",$sef,$base_url);

	// Build My Account Link
	if ($section == "list") {
		$link_list = "#";
		$print_icon = TRUE;
	}
	else $link_list = build_public_url("list","default","default","default",$sef,$base_url);

	// Build My Entries Link
	$link_user_entries = build_public_url("list","default","default","default",$sef,$base_url)."#entries";

	// Build Edit My Info link
    $edit_user_info_link = "";
	if ($_SESSION['brewerID'] != "") $edit_user_info_link .= build_public_url("brewer","account","edit",$_SESSION['brewerID'],$sef,$base_url);

	/*
	$edit_user_info_link = $base_url."index.php?";
	if ($_SESSION['brewerID'] != "") $edit_user_info_link .= "section=brewer&amp;action=edit&amp;id=".$_SESSION['brewerID'];
	else $edit_user_info_link .= "action=add&amp;section=brewer&amp;go=judge";
	*/

	// Build Change My Email Address link
	$edit_user_email_link = build_public_url("user","account","username",$_SESSION['user_id'],$sef,$base_url);
	//$edit_user_email_link = $base_url."index.php?section=user&amp;action=username&amp;id=".$_SESSION['brewerID'];

	// Build Change My Email Address link
	$edit_user_password_link = build_public_url("user","account","password",$_SESSION['user_id'],$sef,$base_url);
	//$edit_user_password_link = $base_url."index.php?section=user&amp;action=password&amp;id=".$_SESSION['brewerID'];

	// Build Add Entry Link
	$add_entry_link = "";
	$add_entry_link .= $base_url;
	if ($_SESSION['userLevel'] <= "1") $add_entry_link .= "index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin";
	else $add_entry_link .= "index.php?section=brew&amp;action=add";

}
if (($logged_in) && ($admin_user) && ($go != "error_page")) { ?>
<!-- Admin Push Menu -->
<div class="navbar-inverse navmenu navmenu-inverse navmenu-fixed-right offcanvas admin-nav-off-canvas">
<div class="navmenu-brand disabled off-canvas-header">Admin Essentials Menu</div>
        <ul class="nav navmenu-nav">
        	<li class="disabled"><a href="#"><em class="bcoem-admin-menu-disabled">This menu contains only essential functions. Select <strong>Admin Dashboard</strong> for all options.</em></a></li>
            <li><a href="<?php echo $base_url; ?>index.php?section=admin">Admin Dashboard</a></li>
			<?php if ($_SESSION['userLevel'] == "0") { ?>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Competition Preparation <span class="caret"></span></a>
                <ul class="dropdown-menu navmenu-nav">
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dates">Edit All Competition Dates</a></li>
					<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contest_info&amp;action=edit">Edit Competition Info</a></li>
					<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts">Manage Contacts</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">Manage Custom Categories</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff">Manage Drop-Off Locations</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging">Manage Judging Sessions</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=non-judging">Manage Non-Judging Sessions</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors">Manage Sponsors</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles">Manage Styles Accepted</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">Manage Style Types</a></li>
					<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload">Upload Logo Images</a></li>
                </ul>
            </li>
			<?php } ?>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Entries<?php if ($_SESSION['prefsPaypalIPN'] == 1) echo ", Payments,";?> and Participants <span class="caret"></span></a>
                <ul class="dropdown-menu navmenu-nav">
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Manage Entries</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=count_by_style">Entry Count By Style</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=count_by_substyle">Entry Count By Sub-Style</a></li>
                    <?php if ($_SESSION['prefsPaypalIPN'] == 1) { ?>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=payments">Manage Payments</a></li>
                    <?php } ?>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Manage Participants</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=judges">Assign Judges</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=stewards">Assign Stewards</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register&amp;view=quick">Quick Register a Judge</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=steward&amp;action=register&amp;view=quick">Quick Register Steward</a></li>
                </ul>
            </li>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sorting <span class="caret"></span></a>
                <ul class="dropdown-menu navmenu-nav">
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Manually</a></li>
                    <?php if (in_array($_SESSION['prefsEntryForm'],$barcode_qrcode_array)) { ?>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=checkin">Entry Check-in Via Barcode Scanner</a></li>
                    <li><a class="hide-loader" href="<?php echo $base_url; ?>qr.php" target="_blank">Entry Check-in Via Mobile Devices <span class="fa fa-external-link"></span></a></li>
                    <?php } ?>
                </ul>
            </li>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Organizing <span class="caret"></span></a>
                <ul class="dropdown-menu navmenu-nav">
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables">Manage Tables</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;action=assign">Assign Judges/Stewards to Tables</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=bos">Add BOS Judges</a></li>
                </ul>
            </li>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Scoring <span class="caret"></span></a>
                <ul class="dropdown-menu navmenu-nav">
                	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload_scoresheets">Upload Scoresheets</a></li>
                	<?php if ($_SESSION['prefsEval'] == 1) { ?><li><a href="<?php echo $base_url; ?>index.php?section=evaluation&amp;go=default&amp;filter=default&amp;view=admin">Manage Entry Evaluations</a></li><?php } ?>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores">Manage Scores</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos">Manage BOS Entries and Places</a></li>
                </ul>
            </li>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <span class="caret"></span></a>
                <ul class="dropdown-menu navmenu-nav">
                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=table-cards&amp;go=judging_tables&amp;id=default">Table Cards</a></li>
                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;view=entry&amp;id=default">Pullsheets - Entry Numbers</a></li>
                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;id=default">Pullsheets - Judging Numbers</a></li>
                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_scores_bos">BOS Pullsheets</a></li>
                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=bos-mat">BOS Cup Mats - Judging Numbers</a></li>
                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=bos-mat&amp;filter=entry">BOS Cup Mats - Entry Numbers</a></li>
                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores&amp;action=print&amp;filter=scores&amp;view=winners">Winners with Scores</a></li>
                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores&amp;action=print&amp;filter=none&amp;view=winners">Winners without Scores</a></li>
                </ul>
            </li>
			<?php if ($_SESSION['userLevel'] == "0") { ?>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Data Management <span class="caret"></span></a>
                <ul class="dropdown-menu navmenu-nav">
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive">Manage Archives</a></li>
                    <?php if (!HOSTED) { ?><li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive&amp;action=add">Archive Current Data</a></li><?php } ?>
                </ul>
            </li>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Preferences <span class="caret"></span></a>
                <ul class="dropdown-menu navmenu-nav">
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences">Website</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences">Judging/Competition Organization</a></li>
                </ul>
            </li>
			<?php } ?>
			<li><a class="hide-loader" href="https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues" target="_blank">Report an Issue</a></li>
        </ul>
    </div>
<!-- ./ Admin Push Menu -->
<?php } ?>
<script>
$(document).ready(function(){
	$("#login-modal-disable").hide();
    $("#login-modal-enable").show();
    $("#admin-disable").hide();
    $("#admin-enable").show();
	$("#user-menu-disable").hide();
    $("#logout-disable").hide();
    $("#user-menu-enable").show();
	$("#print-enable").show();
	$("#help-enable").show();
	$('#loginModal').on('shown.bs.modal', function() {
		 $(this).find('#loginUsername').focus();
	});
});
</script>
<!-- Login Form Modal -->
<?php if ((!$logged_in) && (($section != "login") || (($section == "login") && ($go != "default")))) { ?>
<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="loginModalLabel"><?php echo $label_log_in; ?></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" data-toggle="validator" role="form" action="<?php echo $base_url; ?>includes/logincheck.inc.php?section=login" method="POST" name="form1" id="form1">
					<div class="form-group">
						<div class="col-md-12">
							<div class="input-group has-warning">
								<span class="input-group-addon" id="login-addon1"><span class="fa fa-envelope"></span></span>
								<!-- Input Here -->
								<input id="loginUsername" class="form-control" name="loginUsername" type="email" required placeholder="<?php echo $label_email; ?>" data-error="<?php echo $login_text_018; ?>">
								<span class="input-group-addon" id="login-addon2"><span class="fa fa-star"></span></span>
							</div>
							<span class="help-block with-errors"></span>
						</div>
					</div><!-- Form Group -->
					<div class="form-group">
						<div class="col-md-12">
							<div class="input-group has-warning">
								<span class="input-group-addon" id="login-addon3"><span class="fa fa-key"></span></span>
								<!-- Input Here -->
								<input class="form-control" name="loginPassword" type="password" required placeholder="<?php echo $label_password; ?>" data-error="<?php echo $login_text_019; ?>">
								<span class="input-group-addon" id="login-addon4"><span class="fa fa-star"></span></span>
							</div>
							<span class="help-block with-errors"></span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<!-- Input Here -->
							<button name="submit" type="submit" class="btn btn-primary btn-block" ><?php echo $label_log_in; ?> <span class="fa fa-sign-in" aria-hidden="true"></span> </button>
						</div>
					</div><!-- Form Group -->
				</form>
			</div>
			<div class="modal-footer">
				<p align="center"><?php echo sprintf("<span class=\"fa fa-lg fa-exlamation-circle\"></span> %s <a href=\"%s\">%s</a>.", $login_text_004, $base_url."index.php?section=login&amp;go=password&amp;action=forgot", $login_text_005); ?></p>
				<?php if ((!$logged_in) && ($registration_open == 1)) { ?>
				<p align="center" class="small"><?php echo $label_or; ?></p>
				<a class="btn btn-block btn-default" href="<?php echo build_public_url("register","entrant","default","default",$sef,$base_url); ?>"><?php echo $label_register; ?></a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>
	<!-- Fixed navbar -->
    <div class="navbar <?php echo $nav_container; ?> navbar-fixed-top">
      <div class="<?php echo $container_main; ?>">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bcoem-navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="collapse navbar-collapse" id="bcoem-navbar-collapse">
              <ul class="nav navbar-nav">
                <li<?php if ($section == "default") echo $active_class; ?>><a class="hide-loader" href="<?php echo $link_home; ?>"><?php echo $label_home; ?></a></li>
                <li<?php if ($section == "entry") echo $active_class; ?>><a class="hide-loader" href="<?php echo $link_entry_info; ?>"><?php echo $label_info; ?></a></li>
                <li<?php if ($section == "volunteers") echo $active_class; ?>><a class="hide-loader" href="<?php echo $link_volunteer_info; ?>"><?php echo $label_volunteers; ?></a></li>
                <?php if ($sponsors) { ?>
                <li<?php if ($section == "sponsors") echo $active_class; ?>><a class="hide-loader" href="<?php echo $link_sponsors ?>"><?php echo $label_sponsors; ?></a></li>
                <?php } ?>
                <li<?php if ($section == "contact") echo $active_class; ?>><a class="hide-loader" href="<?php echo $link_contacts; ?>"><?php echo $label_contact; ?></a></li>
                <?php if ((!$logged_in) && (($registration_open == 1) || ($judge_window_open == 1))) { ?>
                <li class="dropdown">
                	<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $label_register; ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                    	<?php if (($registration_open == 1) && (!$ua) && (!isset($_SESSION['loginUsername']))) { ?>
                        <?php if ($nav_register_entrant_show) { ?>
                    	<li><a class="hide-loader" href="<?php echo build_public_url("register","entrant","default","default",$sef,$base_url); ?>"><?php echo $label_entrant; ?></a></li>
                        <?php } ?>
                        <?php } ?>
                        <?php if ((!$judge_limit) && ($judge_window_open == 1)) { ?>
                        <li><a class="hide-loader" href="<?php echo build_public_url("register","judge","default","default",$sef,$base_url); ?>"><?php echo $label_judge; ?></a></li>
                        <?php } if ((!$steward_limit) && ($judge_window_open == 1)) { ?>
                        <li><a class="hide-loader" href="<?php echo build_public_url("register","steward","default","default",$sef,$base_url); ?>"><?php echo $label_steward; ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
                <?php } // end if ((!$logged_in) && (($registration_open == 1) || ($judge_window_open == 1))) ?>
                <?php if ($qr_enable) { ?>
                <li><a class="hide-loader" href="<?php echo $link_qr; ?>" target="_blank"><?php echo $label_entry_check_in; ?></a></li>
                <?php } ?>
              </ul>
          <ul class="nav navbar-nav navbar-right">
          	<?php if ($help_icon) { ?>
            <li id="help-enable"><a class="hide-loader hidden-xs hidden-sm hidden-md" href="#" role="button" data-tooltip="true" data-toggle="modal" data-target="#helpModal"><span class="fa fa-question-circle"></span></a></li>
            <?php } ?>
          	<?php if ($print_icon) { ?>
          	<li id="print-enable"><a class="hide-loader hidden-xs hidden-sm hidden-md" href="#" onclick="window.print()" role="button"><span class="fa fa-print"></span></a></li>
            <?php } ?>
          	<?php if ($logged_in) { ?>
            <li id="user-menu-enable" class="dropdown">
                <a href="#" class="my-dropdown" data-toggle="dropdown"><span class="fa fa-user"></span> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                	<li class="dropdown-header"><strong><?php if (($_SESSION['prefsProEdition'] == 1) && (!empty($_SESSION['brewerBreweryName']))) echo $_SESSION['brewerBreweryName']; else echo $_SESSION['loginUsername']; ?></strong></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php echo $link_list; ?>" tabindex="-1"><?php echo $label_my_account; ?></a></li>
                    <li><a href="<?php echo $edit_user_info_link; ?>" tabindex="-1"><?php echo $label_edit_account; ?></a></li>
                    <li><a href="<?php echo $edit_user_email_link; ?>" tabindex="-1"><?php echo $label_change_email; ?></a></li>
                    <li><a href="<?php echo $edit_user_password_link; ?>" tabindex="-1"><?php echo $label_change_password; ?></a></li>
                    <?php if ($show_entries) { ?>
                    <li><a href="<?php echo $link_user_entries; ?>" tabindex="-1"><?php echo $label_entries; ?></a></li>
                    <?php if ($add_entry_link_show) { ?>
                    <li><a href="<?php echo $add_entry_link; ?>" tabindex="-1"><?php echo $label_add_entry; ?></a></li>
                    <?php } ?>
                    <?php } if ($_SESSION['prefsEval'] == 1) { 
                    	$brewer_assignment = brewer_assignment($_SESSION['user_id'],"1","blah",$dbTable,$filter);
						$assignment_array = str_replace(", ",",",$brewer_assignment);
						$assignment_array = explode(",", $assignment_array);
						if (((in_array($label_judge,$assignment_array)) && ($_SESSION['brewerJudge'] == "Y")) && (judging_winner_display($row_judging_prefs['jPrefsJudgingOpen']))) { 
                   	?>
					<li><a href="<?php echo build_public_url("evaluation","default","default","default",$sef,$base_url); ?>" tabindex="-1"><?php echo $label_judging_dashboard; ?></a></li>
                    <?php }
                    } if ((!$disable_pay) && ($show_entries)) { ?>
                        <?php if (!$comp_paid_entry_limit) { ?>
                            <li><a href="<?php echo $link_pay; ?>"><?php echo $label_pay.$paid_icon; ?></a></li>
                        <?php } ?>
                    <?php } ?>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php echo $base_url; ?>includes/logout.inc.php"><?php echo $label_log_out; ?></a></li>
                    <?php if ((!in_array($go,$datetime_load)) || ($go == "default")) { ?>
                    <li class="dropdown-header"><small><?php echo $label_auto_log_out; ?> <span id="session-end"></span></small></li>
                	<?php } ?>
                </ul>
            </li>
            <li id="user-menu-disable"><a href="<?php echo $link_list; ?>" tabindex="-1"><?php echo $label_my_account; ?></a></li>
            <li id="logout-disable"><a href="<?php echo $base_url; ?>includes/logout.inc.php"><?php echo $label_log_out; ?></a></li>  
            <?php if ($admin_user) { ?>
            <li id="admin-enable" id="admin-arrow"><a href="<?php if ($go == "error_page") echo $base_url."index.php?section=admin"; else echo "#"; ?>" class="admin-offcanvas" data-toggle="offcanvas" data-target=".navmenu" data-canvas="body"><i class="fa fa-chevron-circle-left"></i> <?php echo $label_admin_short; ?></a></li>
            <li id="admin-disable"><a href="<?php echo $base_url."index.php?section=admin"; ?>"><?php echo $label_admin_short; ?></a></li>
            <?php } ?>
            <?php } else { ?>
            <li id="login-modal-enable" <?php if ($section == "login") echo $active_class; ?>><a href="#" role="button" data-toggle="modal" data-target="#loginModal"><?php echo $label_log_in; ?></a></li>
            <li id="login-modal-disable"><a href="<?php echo $base_url; ?>index.php?section=login"><?php echo $label_log_in; ?></a></li>
            <?php } ?>
            <li class="small visible-xs"><a href="https://brewcompetition.com" target="_blank">v <?php echo $current_version_display; ?></a></li>
            </ul>

          </div>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    <?php if ($help_icon) echo $help; ?>