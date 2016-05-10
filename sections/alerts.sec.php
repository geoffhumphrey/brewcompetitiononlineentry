

<?php if ($msg != "default") { ?>
    <!-- User action alerts -->
    <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-exclamation-circle"></span> <?php echo $output; ?>
    </div>
    <?php echo $output_extend; ?>
<?php } ?>

<?php if (($logged_in) && ($_SESSION['userLevel'] <= 1) && ($section == "admin")) { 

	if (($totalRows_log  > 0) && ($_SESSION['prefsStyleSet'] == "BJCP2008") && ($_SESSION['userLevel'] == 0) && ($go == "default")) {
		
		include(DB.'admin_judging_tables.db.php');		
		$bjcp_2008 = TRUE;
		
	} // end if (($totalRows_log  > 0) && ($_SESSION['prefsStyleSet'] == "BJCP2008") && ($_SESSION['userLevel'] == 0))
	
	else $bjcp_2008 = FALSE;

?>
	<!-- Admin Alerts -->
	<?php if ($go == "make_admin") { ?>
		<div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<span class="fa fa-exclamation-circle"></span> <strong>Grant users top-level admin and admin access with caution.</strong>
        </div>
	<?php } ?>
	
	
	<?php if ($purge == "cleanup") { ?>
    	<!-- Data cleanup complete -->
    	<div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<span class="fa fa-exclamation-circle"></span> <strong>Data clean-up completed.</strong>
        </div>
    <?php } ?>
    
    <?php if (($setup_free_access == TRUE) && ($action != "print")) { ?>
    	<!-- Setup free access true -->
    	<div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<span class="fa fa-exclamation-circle"></span> <strong>The &#36;setup_free_access variable in config.php is currently set to TRUE.</strong> For security reasons, the setting should returned to FALSE. You will need to edit config.php directly and re-upload the file to your server.
        </div>
    <?php } ?>
    
    <?php if (($totalRows_dropoff == "0") && ($go == "default")) { ?>
    	<div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<span class="fa fa-exclamation-circle"></span> <strong>No drop-off locations have been specified.</strong> <a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=add&amp;go=dropoff" class="alert-link">Add a drop-off location</a>?
        </div>
    <?php } ?>
    
    <?php if (($totalRows_judging == "0") && ($go == "default")) { ?>
    	<!-- No judging dates -->
    	<div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<span class="fa fa-exclamation-circle"></span> <strong>No judging dates/locations have been specified.</strong> <a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=add&amp;go=judging" class="alert-link">Add a judging location</a>?
        </div>
    <?php } ?>
    
    <?php if (($totalRows_contact == "0") && ($go == "default")) { ?>
    	<!-- No competition dontacts -->
    	<div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<span class="fa fa-exclamation-circle"></span> <strong>No competition contacts have been specified.</strong> <a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=add&amp;go=contacts" class="alert-link">Add a competition contact</a>?
        </div>
    <?php } ?>    
    <?php if ($bjcp_2008) { ?>
    	<!-- BJCP 2008 convert to 2015 -->
    	<div class="alert alert-info alert-dismissible hidden-print fade in" role="alert">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<span class="fa fa-info-circle"></span> <strong>Your current style set is BJCP 2008.</strong> Do you want to <a class="alert-link" href="<?php echo $base_url; ?>includes/process.inc.php?action=convert_bjcp" data-confirm="Are you sure? This action will convert all entries in the database to conform to the BJCP 2015 style guidelines. Categories will be 1:1 where possible, however some specialty styles may need to be updated by the entrant.">convert all entries to BJCP 2015</a>? To retain functionality, the conversion must be performed <em>before</em> defining tables.
        </div>
    <?php } ?>
    
    <?php if (($go == "entries") && ($dbTable == "default") && ($totalRows_entry_count > $_SESSION['prefsRecordLimit']))	{ ?>
    	<!-- Recordset paging MOST LIKELY DEPRECATED -->
    	<div class="alert alert-info alert-dismissible hidden-print fade in" role="alert">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<span class="fa fa-info-circle"></span> <strong>The DataTables recordset paging limit of <?php echo $_SESSION['prefsRecordLimit']; ?> has been surpassed.</strong> Filtering and sorting capabilites are only available for this set of <?php echo $_SESSION['prefsRecordLimit']; ?> entries. To adjust this setting, <a href="index.php?section=admin&amp;go=preferences" class="alert-link">change your installation's DataTables Record Threshold</a> (under the &ldquo;Performance&rdquo; heading in preferences) to a number <em>greater</em> than the total number of entries (<?php echo $totalRows_entry_count; ?>).
        </div>
   <?php } ?>
   
   <?php if ($purge == "purge") { ?>
   		<!-- Purge completed -->
    	<div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<span class="fa fa-exclamation-circle"></span> <strong>All unconfirmed entries have been deleted from the database.</strong>
        </div>
   <?php } ?>
<?php } // end if (($logged_in) && ($_SESSION['userLevel'] <= 1) && ($section == "admin") && ($go == "default")) ?>

<?php if (($logged_in) && ($section == "admin")) { ?>

 <?php if (($entries_unconfirmed > 0) && ($go == "entries")) { ?>
   		<!-- Unconfirmed entries -->
    	<div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<span class="fa fa-exclamation-circle"></span> <strong>Unconfirmed entries are highlighted and denoted with a <span class="fa fa-exclamation-triangle text-danger"></span> below.</strong> Owners of these entries should be contacted. These entries are not included in fee calculations.
        </div>
   <?php } ?>

	<!-- Admin Alerts -->
	<?php  if ((($section == "step7") || (($section == "admin") && ($go == "dropoff"))) && ($msg == "11")) { ?>
		<div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span class="fa fa-exclamation-circle"></span> <strong>Add a Drop Off Location?</strong> <a class="alert-link" href="<?php if ($section == "step6") echo "setup.php?section=step6"; else echo "index.php?section=admin&amp;go=dropoff"; ?>">Yes</a>&nbsp;&nbsp;&nbsp;<a class="alert-link" href="<?php if ($section == "step6") echo "setup.php?section=step7"; else echo "index.php?section=admin"; ?>">No</a>
		</div>		
	<?php } ?>

<?php } // end if ($section == "admin") ?>



<?php if ($logged_in) { ?>

	<?php if ($section == "brew") { ?>

		<?php if (($registration_open != 1) && ($entry_window_open != 1) && ($_SESSION['userLevel'] > 1)) {  
			if ($entry_window_open == 0) $alert_message_closed = "Entry registration has not opened yet.";
			if ($entry_window_open == 2) $alert_message_closed = "Entry registration has closed.";
		?>
        <!-- Entry add/edit registration closed -->
        <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-exclamation-circle"></span> <strong>Adding entries is not available.</strong> <?php echo $alert_message_closed; ?>
        </div>
        <?php } ?>
        
        <?php if (($entry_window_open == 1) && ($_SESSION['userLevel'] > 1) && (($comp_entry_limit) || ($comp_paid_entry_limit)) && ($action == "add") && ($go != "admin")) { ?>
        <!-- Open but competition entry limit reached - only allow editing -->
        <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-exclamation-circle"></span> <strong>Adding entries is not available.</strong> The competition entry limit has been reached.
        </div>
        <?php } ?>
        
        <?php if (($entry_window_open == 1) && ($_SESSION['userLevel'] > 1) && ($comp_entry_limit) && ($comp_paid_entry_limit) && ($remaining_entries == 0) && ($action == "add") && ($go != "admin")) { ?>
        <!-- Open but personal entry limit reached - only allow editing -->
        <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-exclamation-circle"></span> <strong>Adding entries is not available.</strong> Your personal entry limit has been reached.
        </div>
        <?php } ?>
        
        <?php if (($registration_open == 1) && ($entry_window_open != 1) && ($_SESSION['userLevel'] > 1) && ($action == "add")) { ?>
        <!-- Registration open, but entry window not -->
        <div class="alert alert-success alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-check-circle"></span> <strong>Adding entries is not available.</strong> You will be able to add entries on or after <?php echo $entry_open; ?>.
        </div>
        <?php } ?>
        
        <?php if ((NHC) && ($_SESSION['userLevel'] > 1) && ($registration_open != 1) && ($prefix != "final_")) { ?>
        <!-- Special for NHC - close adding or editing during the entry window as well -->
        <div class="alert alert-success alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-check-circle"></span> <strong>Adding/editing entries is not available.</strong> NHC registration has closed.
        </div>
        <?php } ?>
        
        <?php if ((NHC) && ($_SESSION['userLevel'] > 1) && ($registration_open != 1) && ($entry_window_open != 1) && ($prefix == "final_")) { ?>
        <!-- Special for NHC - close adding or editing during the entry window as well -->
        <div class="alert alert-success alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-check-circle"></span> <strong>Adding/editing entries is not available.</strong> NHC registration has closed.
        </div>
        <?php } ?>

	<?php } // end if ($section == "brew") ?>
<?php } // end if ($logged_in) ?>
<?php if (!$logged_in) { ?>
	
     <?php if (($registration_open == 0) && (!$ua) && ($section == "default") && ($msg == "default")) { ?>
        <!-- Account registration not open yet -->
        <div class="alert alert-success alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-check-circle"></span> <strong>Account Registration Will Open <?php echo $reg_open; ?>.</strong> Please return then to register your account.
        </div>
    <?php } ?>
    
    <?php if (($entry_window_open == 0) && (!$ua) && ($section == "default") && ($msg == "default")) { ?>
        <!-- Entry registration not open yet -->
        <div class="alert alert-success alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-check-circle"></span> <strong>Entry Registration Will Open <?php echo $entry_open; ?>.</strong> Please return then to add your entries to the system.
        </div>
    <?php } ?>
    
    <?php if (($registration_open == 0) && (!$ua) && ($section != "admin") && ($judge_window_open == "0") && ($msg == "default")) { ?>
        <!-- Judge/steward registration not open yet -->
        <div class="alert alert-info alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-info-circle"></span> <strong>Judge/Steward Registration Will Open <?php echo $judge_open; ?>.</strong> Please return then to register as a judge or steward.
        </div>
    <?php } ?>
    
    <?php if (($registration_open == 1) && (!$ua) && ($section == "default") && ($comp_entry_limit) && ($msg == "default")) { ?>
        <!-- Account and entry registration open -->
        <div class="alert alert-success alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-check-circle"></span> <strong>Entry Registration is Open!</strong> A total of <?php echo $total_entries; ?> entries have been added to the system as of <?php echo $current_time; ?>. Registration will close <?php echo $entry_closed; ?>.
        </div>
    <?php } ?>
    
    <?php if (($registration_open == 1) && (!$ua) && ($section == "default") && ($comp_entry_limit_near_warning) && ($msg == "default")) { ?>
        <!-- Entry limit nearly reached -->
        <div class="alert alert-warning alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-info-circle"></span> <strong>Entry Limit Nearly Reached!</strong> <?php echo $total_entries; ?> of <?php echo $row_limits['prefsEntryLimit']; ?> maximum entries have been added into the system as of <?php echo $current_time; ?>.
        </div>
    <?php } ?>
    
    <?php if (($registration_open == 1) && (!$ua) && ($section == "default") && ($comp_entry_limit) && ($msg == "default")) { ?>
        <!-- Entry limit reached -->
        <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-exclamation-circle"></span> <strong>Entry Limit Reached.</strong> The limit of <?php echo $row_limits['prefsEntryLimit']; ?> entries has been reached. No further entries will be accepted.
        </div>
    <?php } ?>
    
    <?php if (($registration_open == 1) && (!$ua) && ($section == "default") && ($comp_paid_entry_limit) && ($msg == "default")) { ?>
        <!-- Entry limit reached -->
        <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-exclamation-circle"></span> <strong>Paid Entry Limit Reached.</strong> The limit of <?php echo $row_limits['prefsEntryLimitPaid']; ?> entries has been reached. No further entries will be accepted.
        </div>
    <?php } ?>
    
    <?php if (($registration_open == 2) && (!$ua) && ($section == "default") && (judging_date_return() > 0) && ($msg == "default")) { ?>
        <!-- Account registration closed -->
        <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-exclamation-circle"></span> <strong>Account Registration is Closed.</strong> If you already registered an account, <a class="alert-link" href="<?php echo build_public_url("login","default","default","default",$sef,$base_url); ?>">log in here</a>.
        </div>
    <?php } 	?>
    
    <?php if (($entry_window_open == 2) && (!$ua) && ($section == "default") && (judging_date_return() > 0) && ($msg == "default")) { ?>
        <!-- Entry registration closed -->
        <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-exclamation-circle"></span> <strong>Entry Registration is Closed.</strong> A total of <?php echo $total_entries; ?> entries were added into the system.
        </div>
    <?php } 	?>
    
     <?php if (($dropoff_window_open == 2) && (!$ua) && ($section == "default") && (judging_date_return() > 0) && ($msg == "default")) { ?>
        <!-- Drop-off window closed -->
        <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-exclamation-circle"></span> <strong>Entry Drop-Off is Closed.</strong> Entry bottles are no longer accepted at drop-off locations.
        </div>
    <?php } 	?>
    
    <?php if (($shipping_window_open == 2) && (!$ua) && ($section == "default") && (judging_date_return() > 0) && ($msg == "default")) { ?>
        <!-- Drop-off window closed -->
        <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <span class="fa fa-exclamation-circle"></span> <strong>Entry Shipping is Closed.</strong> Entry bottles are no longer accepted at the shipping location.
        </div>
    <?php } 	?>
    
    <?php if ((($registration_open == 0) || ($registration_open == 2)) && (!$ua) && ($section == "default") && ($judge_window_open == 1) && ($msg == "default")) { 
	
	if (($judge_limit) && (!$steward_limit)) $j_s_text = "Steward";
	elseif ((!$judge_limit) && ($steward_limit)) $j_s_text = "Judge";
	else $j_s_text = "Judge or Steward";
	
	?>
    <!-- Account and entry registration closed, but Judge/steward registration open -->
    <div class="alert alert-info alert-dismissible hidden-print fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="fa fa-info-circle"></span> <strong><?php echo $j_s_text; ?> Account Registration is Open.</strong> <a class="alert-link" href="<?php echo build_public_url("register","judge","default","default",$sef,$base_url); ?>">Register here</a> as a <?php echo strtolower($j_s_text); ?>. <?php echo $j_s_text; ?> registration will close <?php echo $judge_closed; ?>.
    </div>
    <?php } ?>
		<?php if (($judge_limit) && ($section == "register")) { ?>
            <!-- Limit of judges reached -->
            <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <span class="fa fa-exclamation-circle"></span> <strong>The limit of registered judges has been reached.</strong> No further judge registrations will be accepted. <?php if (!$steward_limit) { ?> Registering as a steward is still available.<?php } ?>
            </div>
        <?php } ?>
        <?php if (($steward_limit) && ($section == "register")) { ?>
            <!-- Limit of stewards reached -->
            <div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <span class="fa fa-exclamation-circle"></span> <strong>The limit of registered stewards has been reached.</strong> No further steward registrations will be accepted. <?php if (!$judge_limit) { ?> Registering as a judge is still available.<?php } ?>
            </div>
        <?php } ?>
<?php } // end if (!$logged_in) ?>