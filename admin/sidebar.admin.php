<?php
/**
 * Module:      default.sec.php
 * Description: This module houses the intallation's landing page that includes
 *              information about the competition, registration dates/info, and
 *              winner display after all judging dates have passed.
 */

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}


$competition_logo = "<img src=\"".$base_url."user_images/".$_SESSION['contestLogo']."\" class=\"bcoem-comp-logo img-responsive hidden-print\" alt=\"Competition Logo\" title=\"Competition Logo\" />";

$admin_sidebar_header = "";
$admin_sidebar_body = "";

// If logged in, show the following
if ($logged_in) {

	// Conditional display of panel colors based upon open/closed dates
	if ($registration_open == 0) $reg_panel_display = "panel-default";
	elseif (($registration_open == 1) || ($judge_window_open == 1)) $reg_panel_display = "panel-success";
	elseif ($registration_open == 2) $reg_panel_display = "panel-danger";
	else $reg_panel_display = "panel-default";

	if ($entry_window_open == 0) $entry_panel_display = "panel-default";
	elseif ($entry_window_open == 1) $entry_panel_display = "panel-success";
	elseif ($entry_window_open == 2) $entry_panel_display = "panel-danger";
	else $entry_panel_display = "panel-default";

	$update_text_id = "";
	if ($entry_window_open == 1) $update_text_id = "admin-dashboard-entries-count-updated";
	if (($judging_started) && (!$show_presentation)) $update_text_id = "admin-dashboard-evaluation-count-total-updated";

	// Competition Status Panel
	$admin_sidebar_header .= "<div class=\"panel panel-info\">";
	$admin_sidebar_header .= "<div class=\"panel-heading\">";

	$admin_sidebar_header .= "<h4 style=\"margin: 0px; padding-bottom: 5px;\">Competition Status<span class=\"fa fa-2x fa-bar-chart text-info pull-right\"></span></h4>";
	$admin_sidebar_header .= "<p class=\"small\" style=\"margin: 0px;\"><span class=\"small text-muted\">Updated <span id=\"".$update_text_id."\">".getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time")."</span></span></p>";
	$admin_sidebar_header .= "<p class=\"updates-indicators small\" style=\"margin: 0px;\"><small><i class=\"fa fa-xs fa-circle text-success\"></i></small> <span class=\"small text-muted\"><span id=\"count-two-minute-info\"></span></span></p>";

	$admin_sidebar_header .= "<div class=\"updates-indicators small\" style=\"margin-top: 5px;\">";
	$admin_sidebar_header .= "<span class=\"small text-muted\" id=\"resume-updates\"><a href=\"#\" class=\"hide-loader\" onclick=\"resumeUpdates()\">Resume Updates</a></span>";
	$admin_sidebar_header .= "<span class=\"small text-muted\" id=\"stop-updates\"><a href=\"#\" class=\"hide-loader\" onclick=\"stopUpdates()\">Pause Updates</a> <a href=\"#\" class=\"hide-loader pull-right\" onclick=\"resumeUpdates()\">Update Now</a></span></div>";
	$admin_sidebar_header .= "</div>";
	$admin_sidebar_body .= "<div class=\"panel-body\">";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Confirmed Entries</strong> <i id=\"icon-sync-entries-count\" class=\"fa fa-xs fa-sync fa-spin ms-1 hidden\"></i>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><small><i class=\"fa fa-xs fa-circle text-success entry-update-indicator\"></i></small> <a href=\"".$base_url."index.php?section=admin&amp;go=entries\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View all entries\"><span id=\"admin-dashboard-entries-count\">".$totalRows_log_confirmed."</span></a>";
	if (!empty($row_limits['prefsEntryLimit'])) {
		$admin_sidebar_body .= " / ";
		if ($_SESSION['userLevel'] == 0) $admin_sidebar_body .= "<a href=\"".$base_url."index.php?section=admin&amp;go=preferences\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Change the limit of total entries\">".$row_limits['prefsEntryLimit']."</a>";
		else $admin_sidebar_body .= $row_limits['prefsEntryLimit'];
	}
	$admin_sidebar_body .= "</span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info me-1\">Unconfirmed Entries</strong> <i id=\"icon-sync-entries-unconfirmed-count\" class=\"fa fa-xs fa-sync fa-spin ms-1 hidden\"></i>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><small><i class=\"fa fa-xs fa-circle text-success entry-update-indicator\"></i></small> <span id=\"admin-dashboard-entries-unconfirmed-count\">".$entries_unconfirmed."</span></span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info me-1\">Paid Entries</strong> <i id=\"icon-sync-entries-paid-count\" class=\"fa fa-xs fa-sync fa-spin ms-1 hidden\"></i>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><small><i class=\"fa fa-xs fa-circle text-success entry-update-indicator\"></i></small> <span id=\"admin-dashboard-entries-paid-count\">".get_entry_count("paid")."</span>";
	if (!empty($row_limits['prefsEntryLimitPaid'])) {
		$admin_sidebar_body .= " / ";
		if ($_SESSION['userLevel'] == 0) $admin_sidebar_body .= "<a href=\"".$base_url."index.php?section=admin&amp;go=preferences\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Change the limit of paid entries\">".$row_limits['prefsEntryLimitPaid']."</a>";
		else $admin_sidebar_body .= $row_limits['prefsEntryLimitPaid'];
	}
	$admin_sidebar_body .= "</span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info me-1\">Paid/Rec'd Entries</strong> <i id=\"icon-sync-entries-paid-received-count\" class=\"fa fa-xs fa-sync fa-spin ms-1 hidden\"></i>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><small><i class=\"fa fa-xs fa-circle text-success entry-update-indicator\"></i></small> <span id=\"admin-dashboard-entries-paid-received-count\">".get_entry_count("paid-received")."</span></span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Entry Counts</strong>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=count_by_style\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View the entry counts broken down by style\"><small>Style</small></a> / <a href=\"".$base_url."index.php?section=admin&amp;go=count_by_substyle\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View the entry counts broken down by sub-style\"><small>Sub-Style</small></a></span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info me-1\">Total Fees</strong> <i id=\"icon-sync-total-fees\" class=\"fa fa-xs fa-sync fa-spin ms-1 hidden\"></i>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><small><i class=\"fa fa-xs fa-circle text-success entry-update-indicator\"></i></small> ".$currency_symbol."<span id=\"admin-dashboard-total-fees\">".number_format($total_fees,2)."</span></span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info me-1\">Total Fees Paid</strong> <i id=\"icon-sync-total-fees-paid\" class=\"fa fa-xs fa-sync fa-spin ms-1 hidden\"></i>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=entries&amp;view=paid\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View all paid entries\"><small><i class=\"fa fa-xs fa-circle text-success entry-update-indicator\"></i></small> ".$currency_symbol."<span id=\"admin-dashboard-total-fees-paid\">".number_format($total_fees_paid,2)."</span></a></span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<span><strong class=\"text-info\">Tables Planning Mode </strong>";
	$admin_sidebar_body .= "<a id=\"tables-planning-mode-help\" href=\"#\" data-toggle=\"popover\" title=\"Tables Planning Mode\" data-content=\"<p>When the Tables Planning Mode function is enabled, Admins can define tables, flights, rounds, judge/steward assignments, and, as of version 3.0.0 <strong>if enabled in Entry Preferences</strong>, associated entry limits <strong>prior</strong> to entries being marked as paid and/or received (i.e., prior to sorting).</p><p>Any table configurations and associated assignments <strong>will not be official</strong> until an Admin returns to Tables Competition Mode after entries have been sorted and marked as received in the system. Pullsheets will <strong>not</strong> be available.</p><p>To enable or disable, expand the Organizing section and select the &quot;Switch to...&quot; button.</p>\" data-trigger=\"hover click\" data-placement=\"right\" data-html=\"true\" data-container=\"body\"><i style=\"margin-left: 5px\" class=\"fa fa-sm fa-question-circle\"></i></a></span>";
	$admin_sidebar_body .= "<span id=\"tables-mode-indicator-sidebar\" class=\"pull-right\" class=\"\"><span id=\"tables-mode-indicator-sidebar-icon\" class=\"\"></span> <small><span id=\"tables-mode-indicator-sidebar-text\"></span></small></span>";
	$admin_sidebar_body .= "</div>";

	if ($_SESSION['prefsEval'] == 1) {

		$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
		$admin_sidebar_body .= "<strong class=\"text-info me-1\">Evaluations</strong> <i id=\"icon-sync-evaluation-count-total\" class=\"fa fa-xs fa-sync fa-spin ms-1 hidden\"></i>";
		$admin_sidebar_body .= "<span class=\"pull-right\"><small><i class=\"fa fa-xs fa-circle text-success eval-update-indicator\"></i></small> <a href=\"".$base_url."index.php?section=admin&amp;go=evaluation&amp;filter=default&amp;view=admin\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Total evaluations\"><span id=\"admin-dashboard-evaluation-count-total\">".get_evaluation_count('total')."</span></a> / <a href=\"".$base_url."index.php?section=admin&amp;go=evaluation&amp;filter=default&amp;view=admin\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Entries with evaluations\"><span id=\"admin-dashboard-evaluation-count\">".get_evaluation_count('unique')."</span></a></span>";
		$admin_sidebar_body .= "</div>";

	}

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Participants</strong>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=participants\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View all participants\"><span id=\"admin-dashboard-participant-count\">".get_participant_count('default')."</span></a></span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Participants with Entries</strong>";
	$admin_sidebar_body .= "<span class=\"pull-right\" id=\"admin-dashboard-participant-entries\">".$row_with_entries['count']."</span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Available Judges</strong>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=participants&amp;filter=judges\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View available judges\"><span id=\"admin-dashboard-avail-judges-count\">".get_participant_count('judge')."</span></a>";
	if (!empty($row_judge_limits['jprefsCapJudges'])) {
		$admin_sidebar_body .= " / ";
		if ($_SESSION['userLevel'] == 0) $admin_sidebar_body .= "<a href=\"".$base_url."index.php?section=admin&amp;go=judging_preferences\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Change the limit of judges\">".$row_judge_limits['jprefsCapJudges']."</a>";
		else $admin_sidebar_body .= $row_judge_limits['jprefsCapJudges'];
	}
	$admin_sidebar_body .= "</span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Assigned Judges</strong>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View assigned judges\"><span id=\"admin-dashboard-assigned-judges-count\">".get_participant_count('judge-assigned')."</span></a>";
	$admin_sidebar_body .= "</span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Available Stewards</strong>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=participants&amp;filter=stewards\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View available stewards\"><span id=\"admin-dashboard-avail-stewards-count\">".get_participant_count('steward')."</span></a>";
	if (!empty($row_judge_limits['jprefsCapStewards'])) {
		$admin_sidebar_body .= " / ";
		if ($_SESSION['userLevel'] == 0) $admin_sidebar_body .= "<a href=\"".$base_url."index.php?section=admin&amp;go=judging_preferences\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Change the limit of stewards\">".$row_judge_limits['jprefsCapStewards']."</a>";
		else $admin_sidebar_body .= $row_judge_limits['jprefsCapStewards'];
	}
	$admin_sidebar_body .= "</span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Assigned Stewards</strong>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View assigned stewards\"><span id=\"admin-dashboard-assigned-stewards-count\">".get_participant_count('steward-assigned')."</span></a>";
	$admin_sidebar_body .= "</span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Available Staff</strong>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff&amp;view=yes\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View available staff\"><span id=\"admin-dashboard-avail-staff-count\">".get_participant_count('staff')."</span></a>";
	$admin_sidebar_body .= "</span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Assigned Staff</strong>";
	$admin_sidebar_body .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View assigned staff\"><span id=\"admin-dashboard-assigned-staff-count\">".get_participant_count('staff-assigned')."</span></a>";
	$admin_sidebar_body .= "</span>";
	$admin_sidebar_body .= "</div>";

	if (!empty($organizer_assigned)) {
		$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
		$admin_sidebar_body .= "<strong class=\"text-info\">Organizer</strong>";
		$admin_sidebar_body .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View assigned staff and organizer\">".$organizer_assigned['first_name']."  ".$organizer_assigned['last_name']."</a>";
		$admin_sidebar_body .= "</span>";
		$admin_sidebar_body .= "</div>";
	}

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Entry Registration</strong>";
	if ($entry_window_open == 1) $admin_sidebar_body .= "<span class=\"pull-right text-success\"><span class=\"fa fa-lg fa-check\"></span> Open</span>";
	else $admin_sidebar_body .= "<span class=\"pull-right text-danger\"><span class=\"fa fa-lg fa-times\"></span> Closed</span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Drop-Off Window</strong>";
	if ($dropoff_window_open == 1) $admin_sidebar_body .= "<span class=\"pull-right text-success\"><span class=\"fa fa-lg fa-check\"></span> Open</span>";
	else $admin_sidebar_body .= "<span class=\"pull-right text-danger\"><span class=\"fa fa-lg fa-times\"></span> Closed</span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Shipping Window</strong>";
	if ($shipping_window_open == 1) $admin_sidebar_body .= "<span class=\"pull-right text-success\"><span class=\"fa fa-lg fa-check\"></span> Open</span>";
	else $admin_sidebar_body .= "<span class=\"pull-right text-danger\"><span class=\"fa fa-lg fa-times\"></span> Closed</span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Registration</strong>";
	if ($registration_open == 1) $admin_sidebar_body .= "<span class=\"pull-right text-success\"><span class=\"fa fa-lg fa-check\"></span> Open</span>";
	else $admin_sidebar_body .= "<span class=\"pull-right text-danger\"><span class=\"fa fa-lg fa-times\"></span> Closed</span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\">";
	$admin_sidebar_body .= "<strong class=\"text-info\">Judge Registration</strong>";
	if ($judge_window_open == 1) $admin_sidebar_body .= "<span class=\"pull-right text-success\"><span class=\"fa fa-lg fa-check\"></span> Open</span>";
	else $admin_sidebar_body .= "<span class=\"pull-right text-danger\"><span class=\"fa fa-lg fa-times\"></span> Closed</span>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\" style=\"margin-top: 10px;\"><a data-toggle=\"tooltip\" data-placement=\"top\" title=\"Like the software? Buy the author a beer via PayPal!\" class=\"btn btn-small btn-default btn-block\" href=\"https://www.brewingcompetitions.com/donation\" target=\"_blank\">Donate <span class=\"fa fa-lg fa-cc-paypal\"></span></a></div>";

	$admin_sidebar_body .= "<div class=\"bcoem-sidebar-panel\" style=\"margin-top: 10px; margin-bottom: 0px;\">";
	$admin_sidebar_body .= "<em><span class=\"text-muted\" style=\"font-size:.75em;\">".$server_environ_sidebar."</span></em>";
	$admin_sidebar_body .= "</div>";

	$admin_sidebar_body .= "</div>";
	$admin_sidebar_body .= "</div>";
}

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

echo $admin_sidebar_header;
echo $admin_sidebar_body;

?>

<script type="text/javascript">

	var interval_entry_onload = null;
	var interval_eval_onload = null;
	var interval_entry_onfocus = null;
	var interval_eval_onfocus = null;

	var base_url = "<?php echo $base_url; ?>";
	var count_update_text = "<?php echo $brew_text_061; ?>";
    var count_paused_text = "<?php echo $brew_text_062; ?>";
    var count_paused_manually_text = "<?php echo $brew_text_064; ?>";
    var base_url = "<?php echo $base_url; ?>";
	var entry_open = "<?php echo $entry_window_open; ?>";
	var judging_started = "<?php if ($judging_started) echo "1"; else echo "0"; ?>";;
	var results_published = "<?php if ($show_presentation) echo "1"; else echo "0"; ?>";

	$("#resume-updates").hide();

    function updateEntryCounters(base_url) {

        fetchRecordCount(base_url,'admin-dashboard-entries-count','0','brewing','brewConfirmed','1');
        
        $("#icon-sync-entries-count").removeClass('hidden');
        $("#icon-sync-entries-count").fadeIn();
    	
    	setInterval(function() { 
            $("#icon-sync-entries-count").fadeOut();
        }, 5000);
        
        setTimeout(function() {
            
            fetchRecordCount(base_url,'admin-dashboard-entries-unconfirmed-count','0','brewing','brewConfirmed','2');
	        
	        $("#icon-sync-entries-unconfirmed-count").removeClass('hidden');
	        $("#icon-sync-entries-unconfirmed-count").fadeIn();
        	
        	setInterval(function() { 
                $("#icon-sync-entries-unconfirmed-count").fadeOut();
            }, 5000);

        }, 1000);

        setTimeout(function() {
            
            fetchRecordCount(base_url,'admin-dashboard-entries-paid-count','0','brewing','brewPaid','1');
	        
	        $("#icon-sync-entries-paid-count").removeClass('hidden');
	        $("#icon-sync-entries-paid-count").fadeIn();
        	
        	setInterval(function() { 
                $("#icon-sync-entries-paid-count").fadeOut();
            }, 5000);

        }, 2000);

        setTimeout(function() {
            
            fetchRecordCount(base_url,'admin-dashboard-entries-paid-received-count','0','brewing','brewPaid','1','brewReceived','1');
	        
	        $("#icon-sync-entries-paid-received-count").removeClass('hidden');
	        $("#icon-sync-entries-paid-received-count").fadeIn();
        	
        	setInterval(function() { 
                $("#icon-sync-entries-paid-received-count").fadeOut();
            }, 5000);

        }, 3000);
    
    }

    function updateEvalCounters(base_url) {

        fetchRecordCount(base_url,'admin-dashboard-evaluation-count-total','0','evaluation');

        setTimeout(function() {
        	fetchRecordCount(base_url,'admin-dashboard-evaluation-count','0','evaluation','eid','1');
        }, 1000)

        $("#icon-sync-evaluation-count-total").removeClass('hidden');
        $("#icon-sync-evaluation-count-total").fadeIn();
    	
    	setInterval(function() { 
            $("#icon-sync-evaluation-count-total").fadeOut();
        }, 5000);

    }

	function updateFees(base_url) {
		
		fetchRecordCount(base_url,'admin-dashboard-total-fees','0','brewing','total-fees','1');
       
        $("#icon-sync-total-fees").removeClass('hidden');
        $("#icon-sync-total-fees").fadeIn();
    	
    	setInterval(function() { 
            $("#icon-sync-total-fees").fadeOut();
        }, 5000);
		
		fetchRecordCount(base_url,'admin-dashboard-total-fees-paid','0','brewing','total-fees-paid','1');
        
        $("#icon-sync-total-fees-paid").removeClass('hidden');
        $("#icon-sync-total-fees-paid").fadeIn();
    	
    	setInterval(function() { 
            $("#icon-sync-total-fees-paid").fadeOut();
        }, 5000);

	}

	function stopUpdates() {
		
		clearInterval(interval_entry_onload);
		clearInterval(interval_eval_onload);
		clearInterval(interval_entry_onfocus);
		clearInterval(interval_eval_onfocus);
    	
    	$("#stop-updates").hide();
    	$("#resume-updates").show();
    	$("#count-two-minute-info").text(count_paused_manually_text);
    	$(".fa-sync").addClass('hidden');

    }

    function resumeUpdates() {
        
        if (entry_open == 1) {
        	
        	$(".eval-update-indicator").hide();
        	
        	updateEntryCounters(base_url); 
	        updateFees(base_url);
        	
        	interval_entry_onfocus = setInterval(function() { 
	            updateEntryCounters(base_url); 
	            updateFees(base_url);
	        }, 120000);

	    }

		if ((judging_started == 1) && (results_published == 0)) {
			
			$(".entry-update-indicator").hide();
			
			updateEvalCounters(base_url); 
	        
	        interval_eval_onfocus = setInterval(function() { 
	            updateEvalCounters(base_url); 
	        }, 120000);

	    }

    	$("#stop-updates").show();
    	$("#resume-updates").hide();
    	$("#count-two-minute-info").text(count_update_text);
    
    }

	$(document).ready(function() {

		if (results_published == 1) {
			$(".eval-update-indicator").hide();
			$(".entry-update-indicator").hide();
			$(".updates-indicators").hide();
		}
	    
	    window.onload = function () {
	    	
	        if (entry_open == 1) {
	        	
	        	$(".eval-update-indicator").hide();
	        	
	        	interval_entry_onload = setInterval(function() { 
		            updateEntryCounters(base_url); 
		            updateFees(base_url);
		        }, 120000);

		        $("#count-two-minute-info").text(count_update_text);
		    }

			if ((judging_started == 1) && (results_published == 0)) {
				
				$(".entry-update-indicator").hide();
		        
		        interval_eval_onload = setInterval(function() { 
		            updateEvalCounters(base_url); 
		        }, 120000);

		        $("#count-two-minute-info").text(count_update_text);
		    }
	    
	    };
	    
	    window.onfocus = function () {
	        
	        clearInterval(interval_entry_onload);
	        clearInterval(interval_eval_onload);
	        clearInterval(interval_entry_onfocus);
	        clearInterval(interval_eval_onfocus);
	        
	        if (entry_open == 1) {
	        	
	        	updateEntryCounters(base_url);
	        	updateFees(base_url);
		        
		        interval_entry_onfocus = setInterval(function() { 
		            updateEntryCounters(base_url); 
		            updateFees(base_url);
		        }, 120000);

		        $("#count-two-minute-info").text(count_update_text);
		    
		    }

		    if ((judging_started == 1) && (results_published == 0)) {
	        	
	        	updateEvalCounters(base_url);
		        
		        interval_eval_onfocus = setInterval(function() { 
		            updateEvalCounters(base_url); 
		        }, 120000);

		        $("#count-two-minute-info").text(count_update_text);
		    
		    }

	    };

	    window.onblur = function () {
	        
	        clearInterval(interval_entry_onload);
	        clearInterval(interval_eval_onload);
	        clearInterval(interval_entry_onfocus);
	        clearInterval(interval_eval_onfocus);
	        $("#count-two-minute-info").text(count_paused_text);
	    
	    };
	
	});

</script>