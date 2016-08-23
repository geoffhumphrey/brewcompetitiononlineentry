<?php 
/**
 * Module:      nav.sec.php 
 * Description: This module houses the main navigation. 
 * 
 */

// Turn off SEF for error pages
if ($section >= 400) $sef = "false"; 
else $sef = $sef;

//$sef = "false";

// TESTING
if (TESTING) {
	$logged_in = TRUE;
	$admin_user = TRUE;
}

$print_icon = FALSE;

// Set up general nav link variables
if ($section == "default") { $link_home= "#";  $print_icon = TRUE; }
else { 
	if (empty($base_url)) $link_home= "index.php";
	else $link_home = $base_url;
}

if ($section == "rules") { $link_rules = "#"; $print_icon = TRUE; }
else $link_rules = build_public_url("rules","default","default","default",$sef,$base_url);


if ($section == "entry") { $link_entry_info = "#";  $print_icon = TRUE; }
else $link_entry_info = build_public_url("entry","default","default","default",$sef,$base_url);

if ($section == "volunteers") { $link_volunteer_info = "#";  $print_icon = TRUE; }
else $link_volunteer_info = build_public_url("volunteers","default","default","default",$sef,$base_url);

if (($_SESSION['prefsSponsors'] == "Y") && ($_SESSION['sponsorCount'] > 0)) { 
	if ($section == "sponsors") { $link_sponsors = "#";  $print_icon = TRUE; }
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
	if ($section == "pay") { $link_pay = "#";  if ($msg != "default") $print_icon = TRUE; }
	else $link_pay = build_public_url("pay","default","default","default",$sef,$base_url);
	
	// Build My Account Link
	if ($section == "list") { $link_list = "#";  $print_icon = TRUE; }
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
}


?>

	<!-- Fixed navbar -->
    <nav class="navbar <?php echo $nav_container; ?> navbar-fixed-top">
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
                <li><a href="<?php echo $link_home; ?>">Home</a></li>
                <li><a href="<?php echo $link_entry_info; ?>">Info</a></li>
                <li><a href="<?php echo $link_volunteer_info; ?>">Volunteers</a></li>
                <?php if ($sponsors) { ?>
                <li><a href="<?php echo $link_sponsors ?>">Sponsors</a></li>
                <?php } ?>
                <li><a href="<?php echo $link_contacts; ?>">Contact</a></li>
                <?php if ((!$logged_in) && ($registration_open == "1") && (!$comp_entry_limit)) { ?>
                <li><a href="<?php echo $link_register; ?>">Register</a></li>
   				<?php } ?>
              </ul>
          <ul class="nav navbar-nav navbar-right">
          	<?php if ($print_icon) { ?>
          	<li><a href="javascript:window.print()" role="button" data-toggle="tooltip" data-placement="bottom" title="Print"><span class="glyphicon glyphicon-print"></span></a></li>
            <?php } ?>
          	<?php if ($logged_in) { ?>
            <?php if ($admin_user) { ?>
            <li>
                <a href="<?php echo $link_admin; ?>" role="button" data-toggle="tooltip" data-placement="bottom" title="Admin Dashboard"><span class="glyphicon glyphicon-dashboard"></span></a>
            </li>
            <?php } ?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                	<li class="dropdown-header">User: <?php echo $_SESSION['loginUsername']; ?></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php echo $link_list; ?>" tabindex="-1">My Account</a></li>
                    <li><a href="<?php echo $edit_user_info_link; ?>" tabindex="-1">Edit Account</a></li>
                    <li><a href="<?php echo $edit_user_email_link; ?>" tabindex="-1">Change Email</a></li>
                    <li><a href="<?php echo $edit_user_password_link; ?>" tabindex="-1">Change Password</a></li> 
                    <li><a href="<?php echo $link_user_entries; ?>" tabindex="-1">Entries</a></li>
                    <?php if (($entry_window_open == "1") && (!$comp_entry_limit)) { ?>
                    <li><a href="<?php echo $add_entry_link; ?>" tabindex="-1">Add an Entry</a></li>
                    <?php } ?> 
                    <li><a href="<?php echo $link_pay; ?>">Pay Entry Fees</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php echo $base_url; ?>includes/logout.inc.php">Log Out</a></li>
                </ul>
            </li>
            <?php } else { ?>
				<?php if ($section !=  "login") { ?>
                <li><a href="<?php echo $link_login; ?>" role="button">Log In</a></li>
                <?php } ?>
            <?php } ?>
            </ul>
          </div>
        </div><!--/.nav-collapse -->
      </div>
    </nav>