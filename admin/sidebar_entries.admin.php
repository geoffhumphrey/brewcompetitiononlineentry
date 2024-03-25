<?php
// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
?>


			<div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">Entry Status<span class="fa fa-lg fa-beer text-primary pull-right"></span></h4>
                    <span class="small text-muted">As of <?php echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time"); ?></span>
                </div>
                <div class="panel-body">
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Confirmed Entries<?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $totalRows_log_confirmed; if (!empty($row_limits['prefsEntryLimit'])) echo " of ".$row_limits['prefsEntryLimit']; ?></span>
                    </div>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Unconfirmed Entries<?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $entries_unconfirmed; ?></span>
                    </div>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Paid Confirmed Entries<?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $sidebar_paid_entries; ?></span>
                    </div>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Unpaid Confirmed Entries<?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $sidebar_unpaid_entries ?></span>
                    </div>
                    <?php if (($filter == "default") && ($bid == "default") && ($view == "default")) { ?>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Received Entries</strong><span class="pull-right"><?php echo $total_nopay_received; ?></span>
                    </div>
                    <?php } ?>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Total Fees<?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $currency_symbol.$total_fees; ?></span>
                    </div>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Total Fees Paid<?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $currency_symbol.$total_fees_paid; ?></span>
                    </div>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Total Fees Unpaid <?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $currency_symbol.$total_fees_unpaid; ?></span>
                    </div>
                </div>
            </div>