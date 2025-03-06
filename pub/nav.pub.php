<?php
/**
 * Module:      nav.pub.php
 * Description: This module houses the main navigation.
 *
 */
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


if ($section == "default") {
	$link_bs_target_toggle = "data-bs-toggle=\"collapse\" data-bs-target=\".navbar-collapse\"";
	$link_prefix = "";
}
else {
	$link_bs_target_toggle = "";
	$link_prefix = $base_url;
}


if ($logged_in) {

	// Build My Account Link
	if ($section == "list") {
		$link_list = "#";
		$print_icon = TRUE;
	}

	else $link_list = build_public_url("list","default","default","default",$sef,$base_url);

	// Build My Entries Link
	$link_user_entries = build_public_url("list","default","default","default",$sef,$base_url,"default")."#entries";

	$link_pay = build_public_url("list","default","default","default",$sef,$base_url,"default");
	$link_pay .= "#pay-fees";
	
	$add_entry_link = "";
	//if ($_SESSION['userLevel'] <= "1") $add_entry_link .= $base_url."index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin";
	$add_entry_link .= build_public_url("brew","entries","add",$_SESSION['user_id'],$sef,$base_url);

	$user_remaining_entries = 0;
	if (!empty($row_limits['prefsUserEntryLimit'])) $user_remaining_entries = ($row_limits['prefsUserEntryLimit'] - $totalRows_log);
	else $user_remaining_entries = 1;

	// Build Change My Email Address link
	$edit_user_password_link = build_public_url("user","account","password",$_SESSION['user_id'],$sef,$base_url);

	// Build Edit My Info link
	$edit_user_info_link = "";
	if ($_SESSION['brewerID'] != "") $edit_user_info_link .= build_public_url("brewer","account","edit",$_SESSION['brewerID'],$sef,$base_url);

	// Build Change My Email Address link
	$edit_user_email_link = build_public_url("user","account","username",$_SESSION['user_id'],$sef,$base_url);
}

?>

	<nav id="site-nav" class="site-nav family-sans navbar navbar-expand-md navbar-dark fixed-top bg-dark" style="z-index: 1000;">

    	<div class="container-fluid">

	        <a class="navbar-brand" href="<?php if ($section == "default") echo "#home"; else echo $link_prefix; ?>"><i class="fas fa-home me-2"></i></a>

	        <button type="button" class="navbar-toggler" aria-controls="#nav-toggler" aria-label="Toggle Navigation" data-bs-toggle="collapse" data-bs-target="#nav-toggler">
	          <span class="navbar-toggler-icon"></span>
	        </button>

	        <section class="collapse navbar-collapse" id="nav-toggler">
	            
	            <div class="navbar-nav ms-auto">
	            	<?php if (!$judging_started) { ?>
	                <a class="nav-item nav-link" <?php echo $link_bs_target_toggle; ?> href="<?php echo $link_prefix; ?>#rules"><?php echo $label_rules; ?></a>
	                <a class="nav-item nav-link" <?php echo $link_bs_target_toggle; ?> href="<?php echo $link_prefix; ?>#entry-info"><?php echo $label_entry_info; ?></a>
	                <a class="nav-item nav-link" <?php echo $link_bs_target_toggle; ?> href="<?php echo $link_prefix; ?>#volunteers"><?php echo $label_volunteers; ?></a>
	            	<?php } ?>
	            	<?php if (($_SESSION['prefsSponsors'] == "Y") && ($totalRows_sponsors > 0)) { ?>
	                <a class="nav-item nav-link" <?php echo $link_bs_target_toggle; ?> href="<?php echo $link_prefix; ?>#sponsors"><?php echo $label_sponsors; ?></a>
	                <?php } ?>
	                <a class="nav-item nav-link" <?php echo $link_bs_target_toggle; ?> href="<?php echo $link_prefix; ?>#contact"><?php echo $label_contact; ?></a>
	                <?php if ($admin_user) { ?>
	                <a class="nav-item nav-link" href="<?php echo $base_url."index.php?section=admin"; ?>"><?php echo $label_admin_short; ?></a>
	                <?php } ?>
	                <?php if ($logged_in) { ?>
                	<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-lg fa-fw fa-user"></i></a>
            	        <ul class="dropdown-menu dropdown-menu-end" data-bs-theme="dark">
							<li class="small"><a class="dropdown-item <?php if ($section == "list") echo "disabled"; ?>" href="<?php echo $link_list; ?>"><?php echo $label_my_account; ?></a></li>
							<?php if ($show_entries) { ?>
							<li class="small"><a class="dropdown-item" href="<?php echo $link_user_entries; ?>"><?php echo $label_entries; ?></a></li>
							<?php if ($add_entry_link_show) { ?>
							<li class="small"><a class="dropdown-item <?php if ($section == "brew") echo "disabled"; ?>" href="<?php echo $add_entry_link; ?>"><?php echo $label_add_entry; ?></a></li>
							<?php } ?>
							<?php } ?>

							<?php if ((!$disable_pay) && ($show_entries) && (!$comp_paid_entry_limit)) { ?>
		                	<li class="small"><a class="dropdown-item" href="<?php echo $link_pay; ?>"><?php echo $label_pay; ?></a></li>
		                	<?php } ?>

							<?php if ($_SESSION['prefsEval'] == 1) { 
		                    	$brewer_assignment = brewer_assignment($_SESSION['user_id'],"1","blah",$dbTable,$filter);
								$assignment_array = str_replace(", ",",",$brewer_assignment);
								$assignment_array = explode(",", $assignment_array);
								if (((in_array($label_judge,$assignment_array)) && ($_SESSION['brewerJudge'] == "Y")) && (judging_winner_display($row_judging_prefs['jPrefsJudgingOpen']))) { 
		                   	?>
		                   	<li class="small"><a class="dropdown-item <?php if ($section == "evaluation") echo "disabled"; ?>" href="<?php echo build_public_url("evaluation","default","default","default",$sef,$base_url,"default"); ?>"><?php echo $label_judging_dashboard; ?></a></li>
		                   	<?php } ?>
		                	<?php } ?>
		                	<li class="small"><hr class="dropdown-divider"></li>
							<li class="small"><a class="dropdown-item <?php if ($action == "username") echo "disabled"; ?>" href="<?php echo $edit_user_email_link; ?>"><?php echo $label_change_email; ?></a></li>
							<li class="small"><a class="dropdown-item <?php if ($action == "password") echo "disabled"; ?>" href="<?php echo $edit_user_password_link; ?>"><?php echo $label_change_password; ?></a></li>
							<li class="small"><hr class="dropdown-divider"></li>
							<li class="small" style="font-size: .75em;"><span class="dropdown-item-text text-body-secondary"><?php echo $label_auto_log_out; ?> <span id="session-end"></span></span></li>
						</ul>
            	    </li>
                	<a class="nav-item nav-link" href="<?php echo $base_url."/includes/process.inc.php?section=logout&action=logout"; ?>"><i class="fa fa-lg fa-fw fa-sign-out-alt"></i></a>	
                	<?php } else { ?>
                	<a class="nav-item nav-link hide-loader" data-bs-toggle="modal" data-bs-target="#login-modal" href=""><?php echo $label_log_in; ?></a>
                	<?php } ?>
	            </div>
	        </section>
	    </div>
	</nav>

	<!--
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
					<li><a href="<?php echo build_public_url("evaluation","default","default","default",$sef,$base_url,"default"); ?>" tabindex="-1"><?php echo $label_judging_dashboard; ?></a></li>
                    <?php }
                    } if ((!$disable_pay) && ($show_entries)) { ?>
                        <?php if (!$comp_paid_entry_limit) { ?>
                            <li><a href="<?php echo $link_pay; ?>"><?php echo $label_pay.$paid_icon; ?></a></li>
                        <?php } ?>
                    <?php } ?>


	-->