<?php 
/**
 * Module:      nav.sec.php 
 * Description: This module houses the main navigation. 
 * 
 */

// Turn off SEF for error pages
if ($section >= 400) $sef = "false"; 
else $sef = $sef;

$active_class = " class=\"active\"";

if ($section == "asdfasdfasdfasdfasd") {
	$admin_link = "#";
	$admin_tooltip = "Admin Menu";
}

else {
	$admin_link = $base_url."index.php?section=admin";
	$admin_tooltip = "Admin Dashboard";
}

// TESTING
if (TESTING) {
	$logged_in = TRUE;
	$admin_user = TRUE;
}

$print_icon = FALSE;

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
else $link_register = build_public_url("register","default","default","default",$sef,$base_url);

if ($section == "login") $link_login = "#"; 
else $link_login = build_public_url("login","default","default","default",$sef,$base_url);

if ($section == "logout") $link_logout = "#"; 
else $link_logout = build_public_url("logout","default","default","default",$sef,$base_url);

// Session specific

if ($logged_in)  {
	
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
	if ($_SESSION['brewerID'] != "") $edit_user_info_link = build_public_url("brewer","account","edit",$_SESSION['brewerID'],$sef,$base_url);
	
	/*
	$edit_user_info_link = $base_url."index.php?";
	if ($_SESSION['brewerID'] != "") $edit_user_info_link .= "section=brewer&amp;action=edit&amp;id=".$_SESSION['brewerID']; 
	else $edit_user_info_link .= "action=add&amp;section=brewer&amp;go=judge";
	*/
	
	// Build Change My Email Address link
	$edit_user_email_link = build_public_url("user","account","username",$_SESSION['brewerID'],$sef,$base_url);
	//$edit_user_email_link = $base_url."index.php?section=user&amp;action=username&amp;id=".$_SESSION['brewerID'];
	
	// Build Change My Email Address link
	$edit_user_password_link = build_public_url("user","account","password",$_SESSION['brewerID'],$sef,$base_url);
	//$edit_user_password_link = $base_url."index.php?section=user&amp;action=password&amp;id=".$_SESSION['brewerID'];
	
	// Build Add Entry Link
	$add_entry_link = "";
	$add_entry_link .= $base_url;
	if ($_SESSION['userLevel'] <= "1") $add_entry_link .= "index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin"; 
	else $add_entry_link .= "index.php?section=brew&amp;action=add";
	
	// Build Add Entry BeerXML Link
	$add_entry_beerxml_link = "index.php?section=beerxml";
	
}


if (($logged_in) && ($admin_user)) { ?>




<div class="navbar-inverse navmenu navmenu-inverse navmenu-fixed-right offcanvas">
<div class="navmenu-brand">Admin Essentials Menu</div>
        <ul class="nav navmenu-nav">
        	<li class="disabled"><a href="#"><em class="bcoem-admin-menu-disabled">Click Admin Dashboard for All Options</em></a></li>
            <li><a href="<?php echo $base_url; ?>index.php?section=admin">Admin Dashboard</a></li>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Competition Preparation <span class="caret"></span></a>
                <ul class="dropdown-menu navmenu-nav">
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contest_info">Edit Competition Info</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts">Manage Contacts</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">Manage Custom Categories</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff">Manage Drop-Off Locations</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging">Manage Judging Locations</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors">Manage Sponsors</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles">Manage Styles Accepted</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">Manage Style Types</a></li>
                </ul>
            </li>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Entries and Participants <span class="caret"></span></a>
                <ul class="dropdown-menu navmenu-nav">
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Manage Entries</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Manage Participants</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=judges">Assign Judges</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=stewards">Assign Stewards</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register&amp;view=quick">Quick Register a Judge/Steward</a></li>
                </ul>
            </li>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sorting <span class="caret"></span></a>
                <ul class="dropdown-menu navmenu-nav">
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Manually</a></li>
                    <?php if ($_SESSION['prefsEntryForm'] == "N") { ?>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=checkin">Via Barcode Scanner</a></li>
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
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores">Manage Scores</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos">Manage BOS Entries and Places</a></li>
                </ul>
            </li>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reporting <span class="caret"></span></a>
                <ul class="dropdown-menu navmenu-nav">
                    <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=table-cards&amp;go=judging_tables&amp;id=default">Table Cards</a></li>
                    <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;view=entry&amp;id=default">Pullsheets - Entry Numbers</a></li>
                    <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;id=default">Pullsheets - Judging Numbers</a></li>
                    <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_scores_bos">BOS Pullsheets</a></li>
                    <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=bos-mat">BOS Cup Mats - Judging Numbers</a></li>
                    <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=bos-mat&amp;filter=entry">BOS Cup Mats - Entry Numbers</a></li>
                    <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores&amp;action=print&amp;filter=scores&amp;view=winners">Winners with Scores</a></li>
                    <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores&amp;action=print&amp;filter=none&amp;view=winners">Winners without Scores</a></li>
                </ul>
            </li>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Preferences <span class="caret"></span></a>
                <ul class="dropdown-menu navmenu-nav">
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences">Website</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences">Competition Organization</a></li>
                </ul>
            </li>
        </ul>
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
                <li<?php if ($section == "default") echo $active_class; ?>><a href="<?php echo $link_home; ?>">Home</a></li>
                <li<?php if ($section == "entry") echo $active_class; ?>><a href="<?php echo $link_entry_info; ?>">Info</a></li>
                <li<?php if ($section == "volunteers") echo $active_class; ?>><a href="<?php echo $link_volunteer_info; ?>">Volunteers</a></li>
                <?php if ($sponsors) { ?>
                <li<?php if ($section == "sponsors") echo $active_class; ?>><a href="<?php echo $link_sponsors ?>">Sponsors</a></li>
                <?php } ?>
                <li<?php if ($section == "contact") echo $active_class; ?>><a href="<?php echo $link_contacts; ?>">Contact</a></li>
                <?php if ((!$logged_in) && ($registration_open == "1") && (($total_entries <= $row_limits['prefsEntryLimit']))) { ?>
                <li<?php if ($section == "register") echo $active_class; ?>><a href="<?php echo $link_register; ?>">Register</a></li>
   				<?php } ?>
              </ul>
          <ul class="nav navbar-nav navbar-right">
          	<?php if ($help_icon) { ?>
            <li><a href="#" role="button" data-tooltip="true" data-toggle="modal" data-placement="bottom" title="Help" data-target="#helpModal"><span class="fa fa-question-circle"></span></a></li>
            <?php } ?>
          	<?php if ($print_icon) { ?>
          	<li><a href="javascript:window.print()" role="button" data-toggle="tooltip" data-placement="bottom" title="Print"><span class="fa fa-print"></span></a></li>
            <?php } ?>
          	<?php if ($logged_in) { ?>
            <li class="dropdown">
                <a href="#" title="My Account" class="my-dropdown" data-toggle="dropdown" data-placement="bottom"><span class="fa fa-user"></span> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                	<li class="dropdown-header">User: <?php echo $_SESSION['loginUsername']; ?></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php echo $link_list; ?>" tabindex="-1">My Account</a></li>
                    <li><a href="<?php echo $edit_user_info_link; ?>" tabindex="-1">Edit Account</a></li>
                    <li><a href="<?php echo $edit_user_email_link; ?>" tabindex="-1">Change Email</a></li>
                    <li><a href="<?php echo $edit_user_password_link; ?>" tabindex="-1">Change Password</a></li> 
                    <li><a href="<?php echo $link_user_entries; ?>" tabindex="-1">Entries</a></li>
                    <?php if (($entry_window_open == "1") && (($total_entries <= $row_limits['prefsEntryLimit']))) { ?>
                    <li><a href="<?php echo $add_entry_link; ?>" tabindex="-1">Add an Entry</a></li>
                    <?php if ((!NHC) && ($_SESSION['prefsHideRecipe'] == "N")) { ?><li tabindex="-1"><a href="<?php echo $add_entry_beerxml_link; ?>">Import an Entry Using BeerXML</a><?php } ?>
                    <?php } ?> 
                    <li><a href="<?php echo $link_pay; ?>">Pay Entry Fees</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php echo $base_url; ?>includes/logout.inc.php">Log Out</a></li>
                </ul>
            </li>
            <?php if ($admin_user) { ?>
            <li id="admin-arrow"><a href="#" class="admin-offcanvas" data-toggle="offcanvas" data-target=".navmenu" data-canvas="body" title="<?php echo $admin_tooltip; ?>"><i class="fa fa-chevron-circle-left"></i> Admin</a></li>
            <?php } ?>
            <?php } else { ?>
            
            
            
            <li<?php if ($section == "login") echo $active_class; ?>><a href="<?php echo $link_login; ?>" role="button">Log In</a></li>
            <?php } ?>
            </ul>
          </div>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    
    <?php if ($help_icon) echo $help; ?>