<?php
/**
 * Module:      default.admin.php
 * Description: This module houses links to all administration functions.
 *
 */

$server_environ = "<p><strong>Reporting an issue?</strong> Here's your server environment information:</p>";
$server_environ .= "<ul>";
$server_environ .= "<li>PHP Version &ndash; ".$php_version."</li>";
$server_environ .= "<li>MySQL Version &ndash; ".$connection -> server_info."</li>";
$server_environ .= "</ul>";

if ($_SESSION['prefsEval'] == 1) {
    include(EVALS.'admin_alert_empty_prefs.eval.php');
    include(EVALS.'import_scores.eval.php');
}

$show_best = FALSE;
if (($row_limits['prefsShowBestBrewer'] != 0) || ($row_limits['prefsShowBestClub'] != 0)) {
    if ($judging_started) $show_best = TRUE;
    elseif (($_SESSION['prefsEval'] == 1) && (($judging_started) || ($judge_window_open > 0))) $show_best = TRUE;
}

$judge_assign_links = array();
$steward_assign_links = array();

// Judge Inventory
$ji_loc_entry = "";
$ji_loc_judging = "";

// Pullsheet
$ps_loc_entry = "";
$ps_loc_entry_mbos = "";
$ps_loc_judging = "";
$ps_loc_judging_mbos = "";

$cards_loc_rnd = "";

if ($totalRows_judging > 0) {
    do {

        $judge_assign_links[$row_judging['judgingLocName']] = $base_url."output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;location=".$row_judging['id'];
        $steward_assign_links[$row_judging['judgingLocName']] = $base_url."output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;location=".$row_judging['id'];                                     
        for ($round=1; $round <= $row_judging['judgingRounds']; $round++) {
            
            $location_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt");
            $location_name = sprintf("%s - %s, Round %s", $row_judging['judgingLocName'], $location_date, $round);

            $ps_loc_j_link = $base_url."output/print.output.php?section=pullsheets&amp;go=judging_locations&amp;view=default&amp;location=".$row_judging['id']."&amp;round=".$round;

            $ps_loc_j_mbos_link = $base_url."output/print.output.php?section=pullsheets&amp;go=judging_locations&amp;view=default&amp;filter=mini_bos&amp;location=".$row_judging['id']."&amp;round=".$round;

            $ps_loc_e_link = $base_url."output/print.output.php?section=pullsheets&amp;go=judging_locations&amp;view=entry&amp;location=".$row_judging['id']."&amp;round=".$round;

            $ps_loc_e_mbos_link = $base_url."output/print.output.php?section=pullsheets&amp;go=judging_locations&amp;view=entry&amp;filter=mini_bos&amp;location=".$row_judging['id']."&amp;round=".$round;
            
            $ps_loc_judging .= sprintf("<li class=\"small\"><a id=\"modal_window_link\" class=\"hide-loader\" href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print Pullsheet for Session %s (Judging Numbers)\">%s</a></li>", $ps_loc_j_link, $location_name, $location_name);

            $ps_loc_judging_mbos .= sprintf("<li class=\"small\"><a id=\"modal_window_link\" class=\"hide-loader\" href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print Pullsheet for Session %s (Mini-BOS Judging Numbers)\">%s (Mini-BOS)</a></li>", $ps_loc_j_mbos_link, $location_name, $location_name);

            $ps_loc_entry .= sprintf("<li class=\"small\"><a id=\"modal_window_link\" class=\"hide-loader\" href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print Pullsheet for Session %s (Entry Numbers)\">%s</a></li>", $ps_loc_e_link, $location_name, $location_name);

            $ps_loc_entry_mbos .= sprintf("<li class=\"small\"><a id=\"modal_window_link\" class=\"hide-loader\" href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print Pullsheet for Session %s (Mini-BOS Entry Numbers)\">%s (Mini-BOS)</a></li>", $ps_loc_e_mbos_link, $location_name, $location_name);

            $cards_loc_rnd_link = $base_url."output/print.output.php?section=table-cards&amp;go=judging_locations&amp;location=".$row_judging['id']."&amp;round=".$round;

            $cards_loc_rnd .= sprintf("<li class=\"small\"><a id=\"modal_window_link\" class=\"hide-loader\" href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print Table Cards for %s\">%s</a></li>", $cards_loc_rnd_link, $location_name, $location_name);

            /*

            <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=table-cards&amp;go=judging_locations&amp;location=<?php echo $row_judging2['id']?>&amp;round=<?php echo $round; ?>" data-toggle="tooltip" data-placement="top" title="Print Table Cards for <?php echo $row_judging2['judgingLocName']. " - " . $location_date . ", Round " . $round; ?>"><?php echo $row_judging2['judgingLocName']. " - " . $location_date . ", Round " . $round; ?></a></li>

            $ps_loc_judging .= "<li class=\"small\"><a id=\"modal_window_link\" class=\"hide-loader\" href=\"".$base_url."output/print.output.php?section=pullsheets&amp;go=judging_locations&amp;view=default&amp;location=".$row_judging['id']."&amp;round=".$round."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print Pullsheet for Session ".$row_judging['judgingLocName'] . " - " . $location_date. ", Round " . $round."\">".$row_judging['judgingLocName'] . " - " . $location_date. ", Round " . $round."</a></li>";

            $ps_loc_judging_mbos .= "<li class=\"small\"><a id=\"modal_window_link\" class=\"hide-loader\" href=\"".$base_url."output/print.output.php?section=pullsheets&amp;go=judging_locations&amp;view=default&amp;filter=mini_bos&amp;location=".$row_judging['id']."&amp;round=".$round."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print Pullsheet for Session ".$row_judging['judgingLocName'] . " - " . $location_date. ", Round " . $round."\">".$row_judging['judgingLocName'] . " - " . $location_date. ", Round " . $round . " (Mini-BOS)</a></li>";
            */
        }

        $ji_link_entry = $base_url."output/print.output.php?section=pullsheets&amp;go=all_entry_info&amp;view=judge_inventory&amp;filter=J&amp;sort=entry&amp;location=".$row_judging['id'];
        $ji_loc_entry .= "<li class=\"small\">";
        $ji_loc_entry .= sprintf("<a id=\"modal_window_link\" class=\"hide-loader\" href=\"%s\">",$ji_link_entry);
        $ji_loc_entry .= $row_judging['judgingLocName'];
        $ji_loc_entry .= "</a>";
        $ji_loc_entry .= "</li>";

        $ji_loc_judging_link = $base_url."output/print.output.php?section=pullsheets&amp;go=all_entry_info&amp;view=judge_inventory&amp;filter=J&amp;location=".$row_judging['id'];
        $ji_loc_judging .= "<li class=\"small\">";
        $ji_loc_judging .= sprintf("<a id=\"modal_window_link\" class=\"hide-loader\" href=\"%s\">",$ji_loc_judging_link);
        $ji_loc_judging .= $row_judging['judgingLocName'];
        $ji_loc_judging .= "</a>";
        $ji_loc_judging .= "</li>";

    } while ($row_judging = mysqli_fetch_assoc($judging));
}

// BOS Pullsheets and Cup Mats for Individual Style Types
if ($totalRows_tables > 0) {
    
    $bos_pull_st_entry = "";
    $bos_pull_st_judging = "";
    $bos_pull_pro_am_st_entry = "";
    $bos_pull_pro_am_st_judging = "";
    $bos_cup_mat_st_entry = "";
    $bos_cup_mat_st_judging = "";

    do {

        if ($row_style_type['styleTypeBOS'] == "Y") {

            $bos_pull_entry_link = $base_url."output/print.output.php?section=pullsheets&amp;go=judging_scores_bos&amp;view=entry&amp;id=".$row_style_type['id'];
            $bos_pull_judging_link = $base_url."output/print.output.php?section=pullsheets&amp;go=judging_scores_bos&amp;id=".$row_style_type['id']; 

            $bos_pull_st_entry .= "<li>";
            $bos_pull_st_entry .= sprintf("<a id=\"modal_window_link\" class=\"hide-loader\" href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print the %s BOS Pullsheet Using Entry Numbers\">%s</a>",$bos_pull_entry_link,$row_style_type['styleTypeName'],$row_style_type['styleTypeName']);
            $bos_pull_st_entry .= "</li>";

            $bos_pull_st_judging .= "<li>";
            $bos_pull_st_judging .= sprintf("<a id=\"modal_window_link\" class=\"hide-loader\" href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print the %s BOS Pullsheet Using Judging Numbers\">%s</a>",$bos_pull_judging_link,$row_style_type['styleTypeName'],$row_style_type['styleTypeName']);
            $bos_pull_st_judging .= "</li>";


            for ($i=1; $i <= 3; $i++) {

                if ($i == 1) $pro_am_bos_method = "1st Place Only";
                if ($i == 2) $pro_am_bos_method = "1st and 2nd Places";
                if ($i == 3) $pro_am_bos_method = "1st, 2nd, and 3rd Places";

                $bos_pull_pro_am_entry_link = $base_url."output/print.output.php?section=pullsheets&amp;go=judging_scores_bos&amp;action=pro-am&amp;filter=".$i."&amp;view=entry&amp;id=".$row_style_type['id'];
                $bos_pull_pro_am_judging_link = $base_url."output/print.output.php?section=pullsheets&amp;go=judging_scores_bos&amp;action=pro-am&amp;filter=".$i."&amp;id=".$row_style_type['id']; 

                $bos_pull_pro_am_st_entry .= "<li>";
                $bos_pull_pro_am_st_entry .= sprintf("<a id=\"modal_window_link\" class=\"hide-loader\" href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print the %s BOS Pullsheet Using Entry Numbers\">%s - %s</a>",$bos_pull_pro_am_entry_link,$row_style_type['styleTypeName'],$row_style_type['styleTypeName'],$pro_am_bos_method);
                $bos_pull_pro_am_st_entry .= "</li>";

                $bos_pull_pro_am_st_judging .= "<li>";
                $bos_pull_pro_am_st_judging .= sprintf("<a id=\"modal_window_link\" class=\"hide-loader\" href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print the %s BOS Pullsheet Using Judging Numbers\">%s - %s</a>",$bos_pull_pro_am_judging_link,$row_style_type['styleTypeName'],$row_style_type['styleTypeName'],$pro_am_bos_method);
                $bos_pull_pro_am_st_judging .= "</li>";
            
            }

            $bos_mat_entry = $base_url."output/print.output.php?section=bos-mat&amp;filter=entry&amp;view=".$row_style_type['id'];
            $bos_mat_judging = $base_url."output/print.output.php?section=bos-mat&amp;view=".$row_style_type['id'];

            $bos_cup_mat_st_entry .= "<li>";
            $bos_cup_mat_st_entry .= sprintf("<a id=\"modal_window_link\" class=\"hide-loader\" href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print the %s BOS Cup Mat Using Entry Numbers\">%s</a>",$bos_mat_entry,$row_style_type['styleTypeName'],$row_style_type['styleTypeName']);
            $bos_cup_mat_st_entry .= "</li>";

            $bos_cup_mat_st_judging .= "<li>";
            $bos_cup_mat_st_judging .= sprintf("<a id=\"modal_window_link\" class=\"hide-loader\" href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print the %s BOS Cup Mat Using Judging Numbers\">%s</a>",$bos_mat_judging,$row_style_type['styleTypeName'],$row_style_type['styleTypeName']);
            $bos_cup_mat_st_judging .= "</li>";

        }

    } while ($row_style_type = mysqli_fetch_assoc($style_type));

}

?>
<script src="<?php echo $base_url;?>js_includes/admin_ajax.min.js"></script>
<p class="lead">Hello, <?php echo $_SESSION['brewerFirstName']; ?>. <span class="small">Select the headings or icons below to view the options available in each category.</span></p>
<div class="row bcoem-admin-element">
    <?php if ($hosted_setup) { ?>
    <div class="col col-lg-3 col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 5px;">
        <a class="btn btn-info btn-block hide-loader" href="http://brewcompetition.com/customize-comp-info" target="_blank" data-toggle="popover" data-trigger="hover" data-placement="auto top" data-container="body" data-title="Reset Competition Information" data-content="Detailed instructions on how to customize your hosted BCOE&amp;M installation including defining registration dates, judging sessions, drop-off window and locations, shipping window and locations, sponsors, styles accepted, etc.">Customize Competition Info <span class="fa fa-info-circle"></span></a>
    </div>
    <?php } else { ?>
    <div class="col col-lg-3 col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 5px;">
        <a class="btn btn-info btn-block hide-loader" href="http://brewcompetition.com/reset-comp" target="_blank" data-toggle="popover" data-trigger="hover" data-placement="auto top" data-container="body" data-title="Reset Competition Information" data-content="Detailed instructions on how to reset the site information in preparation for an upcoming competition iteration.">Reset Competition Info <span class="fa fa-info-circle"></span></a>
    </div>
    <?php } ?>
    <?php if (($judging_started) && ($_SESSION['userLevel'] == 0)) { ?>
    <div class="col col-lg-3 col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 5px;">
        <a class="btn btn-primary btn-block hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=publish" data-toggle="popover" data-trigger="hover" data-placement="auto top" data-container="body" data-title="Publish Results" data-content="Immediately publish all results in the database to the home page." data-confirm="Are you sure you wish to publish the results now?">Publish Results Now <span class="fa fa-bullhorn"></span></a>
    </div>
    <?php } ?>
    <?php if (($judging_started) && ($_SESSION['userLevel'] == 0)) { ?>
    <div class="col col-lg-3 col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 5px;">
        <a class="btn btn-primary btn-block" href="<?php echo $base_url; ?>awards.php" target="_blank" data-toggle="popover" data-trigger="hover" data-placement="auto top" data-container="body" data-title="Awards Presentation" data-html="true" data-content="<p>PowerPoint-style presentation of placing entries and Best of Show winner(s). Intended to be projected or screen-shared during your awards ceremony.</p><p><strong>Only Admin-level users can access the presentation before results are published.</strong></p>"><?php echo $label_launch_pres; ?> <span class="fa fa-award"></span></a>
    </div>
    <?php } ?>
    <?php if ($show_best) { ?>
    <div class="col col-lg-3 col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 5px;">
        <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#previewBest">Best Brewer/Best Club Results <span class="fa fa-trophy"></span></button>
    </div>
    <div class="modal fade" id="previewBest" tabindex="-1" role="dialog" aria-labelledby="previewBestLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <?php include (SECTIONS.'bestbrewer.sec.php'); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<div class="bcoem-admin-dashboard-accordion">
    <div class="row">
        <div class="col col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="panel-group" id="accordion">
				<?php if ($_SESSION['userLevel'] == "0") { ?>  
                <!-- Preparing Panel -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapsePrep">Competition Preparation<span class="fa fa-wrench pull-right"></span></a>
                        </h4>
                    </div>
                    <div id="collapsePrep" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Competition Info</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contest_info&amp;action=edit">Edit</a></li>
										<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload&amp;action=html" data-toggle="tooltip" data-placement="top" title="Upload your logo before editing your Competition Information">Upload Logo</a></li>
									</ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                               <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Contacts</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts&amp;action=add">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                               <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Custom Categories</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best&amp;action=add">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                               <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Drop-Off Locations</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff&amp;action=add">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                               <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Judging Sessions</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=add">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php if ($_SESSION['userLevel'] == "0") { ?>
                            <div class="row">
                               <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Sponsors</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors&amp;action=add">Add</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload" data-toggle="tooltip" data-placement="top" title="Upload sponsor logo images BEFORE adding sponsor information">Upload Logos</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                               <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Styles Accepted</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles&amp;action=add">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                               <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Style Types</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types&amp;action=add">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php } // end if ($_SESSION['userLevel'] == "0") ?>
                        </div>
                    </div>
                </div><!-- ./ Preparing Panel -->
				<?php } ?>
                <!-- Entry and Data Gathering Panel -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseEntry">Entries<?php if ($_SESSION['prefsPaypalIPN'] == 1) echo ", Payments,";?> and Participants<span class="fa fa-beer pull-right"></span></a>
                        </h4>
                    </div>
                    <div id="collapseEntry" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Entries</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Manage</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php if ($_SESSION['prefsPaypalIPN'] == 1) { ?>
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Payments</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=payments">Manage</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php } ?>
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Participants</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Manage</a></li>
                                    </ul>
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=judges">Assign Judges</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=stewards">Assign Stewards</a></li>
                                        <?php if ($_SESSION['userLevel'] == "0") { ?>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=staff">Assign Staff</a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Register</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entrant&amp;action=register">A Participant</a></li>
                                    </ul>
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register&amp;view=quick" data-toggle="tooltip" title="Add a judge or steward inputting only necessary registration data">A Judge (Quick)</a><li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register" data-toggle="tooltip" title="Add a judge or steward inputting all registration data">A Judge (Standard)</a><li>
                                    </ul>
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=steward&amp;action=register&amp;view=quick" data-toggle="tooltip" title="Add a steward inputting only necessary registration data">A Steward (Quick)</a><li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=steward&amp;action=register" data-toggle="tooltip" title="Add a steward inputting all registration data">A Steward (Standard)</a><li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                        </div>
                    </div>
                </div><!-- ./ Entry and Data Gathering Panel -->
				<!-- Sorting Panel -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseSorting">Entry Sorting<span class="fa fa-exchange pull-right"></span></a>
                        </h4>
                    </div>
                    <div id="collapseSorting" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Regenerate</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-unstyled">
                                        <li>
                                            <a class="hide-loader"  href="#" data-toggle="modal" data-target="#jn-random-modal">Judging Numbers (Random)</a>
                                            <div>
                                                <span style="margin-bottom: 10px;" class="hidden" id="jn-random-status"></span>
                                                <span style="margin-bottom: 10px;" class="hidden" id="jn-random-status-msg"></span>
                                            </div>
                                        </li>
                                        <!-- Modal -->
                                        <div class="modal fade" id="jn-random-modal" tabindex="-1" role="dialog" aria-labelledby="jn-random-modal-label">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="jn-random-modal-label">Please Confirm</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to regenerate judging numbers for all entries?</p>
                                                        <p>This action will over-write all current judging numbers with randomly generated ones, <strong>including any that have been assigned via the barcode or QR Code scanning function</strong>.</p>
                                                        <p>The process may take a while depending upon the number of entires in your database.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger btn-default" data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="regenerate_judging_numbers('<?php echo $base_url; ?>','default','admin-dashboard','jn-random');">Yes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <li>
                                            <a class="hide-loader"  href="#" data-toggle="modal" data-target="#jn-style-modal">Judging Numbers (With Style Number Prefix)</a>
                                            <div>
                                                <span style="margin-bottom: 10px;" class="hidden" id="jn-style-status"></span>
                                                <span style="margin-bottom: 10px;" class="hidden" id="jn-style-status-msg"></span>
                                            </div>
                                        </li>

                                        <!-- Modal -->
                                        <div class="modal fade" id="jn-style-modal" tabindex="-1" role="dialog" aria-labelledby="jn-style-modal-label">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="jn-style-modal-label">Please Confirm</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to regenerate judging numbers for all entries?</p>
                                                        <p>This action will over-write all current judging numbers with those with style number prefixes, <strong>including any that have been assigned via the barcode or QR Code scanning function</strong>. Judging numbers will be in the following format: XX-123 (where XX is the 2-character category number or name).</p>
                                                        <p>The process may take a while depending upon the number of entires in your database.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger btn-default" data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="regenerate_judging_numbers('<?php echo $base_url; ?>','legacy','admin-dashboard','jn-style');">Yes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <li>
                                            <a class="hide-loader"  href="#" data-toggle="modal" data-target="#jn-entry-modal">Judging Numbers (Same as Entry Numbers)</a>
                                            <div>
                                                <span style="margin-bottom: 10px;" class="hidden" id="jn-entry-status"></span>
                                                <span style="margin-bottom: 10px;" class="hidden" id="jn-entry-status-msg"></span>
                                            </div>
                                        </li>
                                        <!-- Modal -->
                                        <div class="modal fade" id="jn-entry-modal" tabindex="-1" role="dialog" aria-labelledby="jn-entry-modal-label">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="jn-entry-modal-label">Please Confirm</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to regenerate judging numbers for all entries?</p>
                                                        <p>This action will over-write each entry's assigned judging number with one that matches its entry number, <strong>including any that have been assigned via the barcode or QR Code scanning function</strong>.</p>
                                                        <p>The process may take a while depending upon the number of entires in your database.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger btn-default" data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="regenerate_judging_numbers('<?php echo $base_url; ?>','identical','admin-dashboard','jn-entry');">Yes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php if (in_array($_SESSION['prefsEntryForm'],$barcode_qrcode_array)) { ?>
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Using Barcodes/QR Codes?</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a class="hide-loader" href="http://brewcompetition.com/barcode-labels" target="_blank">Download Barcode and Round Judging Number Labels <span class="fa fa-external-link"></span></a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php } ?>
							<div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Entry Check-In</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Manually</a></li>
                                        <?php if (in_array($_SESSION['prefsEntryForm'],$barcode_qrcode_array)) { ?>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=checkin">Via Barcode Scanner</a></li>
                                        <li><a class="hide-loader" href="<?php echo $base_url; ?>qr.php" target="_blank">Via Mobile Devices <span class="fa fa-external-link"></span></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Sorting Sheets</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=sorting&amp;go=default&amp;filter=default&amp;view=entry">Entry Numbers</a></li>
                                        <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=sorting&amp;go=default&amp;filter=default">Judging Numbers</a></li>
                                        <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=sorting&amp;go=cheat&amp;filter=default">Cheat Sheets</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Cellarmaster</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=judging_tables">Beer Box Labels</a></li>
                                        <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=judging_tables&amp;filter=judges">Virtual Judging Box Tags</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                                <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h5>Print Bottle Labels (PDF)<hr></h5>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <a class="hide-loader" href="http://www.avery.com/avery/en_us/Products/Labels/Addressing-Labels/Easy-Peel-White-Address-Labels_05160.htm" target="_blank" data-toggle="tooltip" data-placement="right" title="Avery 5160"><strong>Letter</strong></a>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a class="hide-loader" data-toggle="tooltip" title="6 entry numbers per label" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;psort=5160">Entry Numbers</a></li>
                                        <li><a class="hide-loader" data-toggle="tooltip" title="6 judging numbers per label" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;psort=5160">Judging Numbers</a></li>
                                        <li><a class="hide-loader" data-toggle="tooltip" title="Quicksort labels" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=quicksort&amp;psort=5160">Quicksort</a></li>
                                    </ul>
									<ul class="list-unstyled">
										<li>With Required Info - All Styles (Entry Numbers)
										    <div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu1">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;view=all&amp;psort=5160&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
										</li>
                                        <li>With Required Info - Only Styles Where Required (Entry Numbers)
										    <div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu2">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;view=special&amp;psort=5160&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
										</li>
									</ul>
                                    <ul class="list-unstyled">
										<li>With Required Info - All Styles (Judging Numbers)
										    <div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu3">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=all&amp;&amp;psort=5160&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
										</li>
										<li>With Required Info - Only Styles Where Required (Judging Numbers)
										    <div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu4">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=special&amp;&amp;psort=5160&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
										</li>
									</ul>
                                </div>
                             </div><!-- ./row -->
                             <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <a class="hide-loader" href="http://www.avery.se/avery/en_se/Products/Labels/Multipurpose/White-Multipurpose-Labels-Permanent/General-Usage-Labels-White_3422.htm" target="_blank" data-toggle="tooltip" data-placement="right" title="Avery 3422"><strong>A4</strong></a>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a class="hide-loader" data-toggle="tooltip" title="6 entry numbers per label" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;psort=3422">Entry Numbers</a></li>
                                        <li><a class="hide-loader" data-toggle="tooltip" title="6 judging numbers per label" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;psort=3422">Judging Numbers</a></li>
                                    </ul>
                                    <ul class="list-unstyled">
										<li>With Required Info - All Styles (Entry Numbers)
										    <div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu1">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;view=all&amp;psort=3422&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
										</li>
                                        <li>With Required Info - Only Styles Where Required (Entry Numbers)
										    <div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu2">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;view=special&amp;psort=3422&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
										</li>
									</ul>
                                    <ul class="list-unstyled">
										<li>With Required Info - All Styles (Judging Numbers)
										    <div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu3">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=all&amp;&amp;psort=3422&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
										</li>
										<li>With Required Info - Only Styles Where Required (Judging Numbers)
										    <div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu4">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=special&amp;&amp;psort=3422&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
										</li>
									</ul>
                                </div>
                             </div><!-- ./row -->
                             <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <a class="hide-loader" href="http://www.onlinelabels.com/Products/OL32.htm" target="_blank" data-toggle="tooltip" data-placement="right" title="Online Lables OL32"><strong>0.50 in/13 mm Round</strong></a>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-unstyled">
										<li>Entry Numbers
											<div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu5">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
										</li>
										<li>Judging Numbers
											<div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu6">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
                                        </li>
                                        <li>Style/Sub-Style Only
											<div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu7">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-category-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
                                        </li>
                                        <li>Entries Added By Admins
											<div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu8" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu8">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=recent&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
                                        </li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <a class="hide-loader" href="http://www.onlinelabels.com/Products/OL5275WR.htm" target="_blank" data-toggle="tooltip" data-placement="right" title="Online Lables OL5275WR"><strong>0.75 in/19 mm Round</strong></a>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-unstyled">
                                        <li>Entry Numbers
											<div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu9" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu9">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
                                        </li>
                                        <li>Judging Numbers
											<div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu10" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu10">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
                                        </li>
                                        <li>Style/Sub-Style Only
											<div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu11" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu11">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-category-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
                                        </li>
                                        <li>Entries Added By Admins
											<div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu12" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu12">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=recent&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
                                        </li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->

                        </div>
                    </div>
                </div>
                <!-- ./ Sorting Panel -->
                <!-- Organizing Panel -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOrg">Organizing<span class="fa fa-tasks pull-right"></span></a>
                        </h4>
                    </div>
                    <div id="collapseOrg" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Assign</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Judges</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Stewards</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Staff</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Tables</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><strong><?php if ($_SESSION['jPrefsTablePlanning'] == 1) echo "<span class=\"text-purple\">** Tables Planning Mode **</span>"; else echo "<span class=\"text-teal\">** Tables Competition Mode **</span>"; ?></strong></li>
                                    </ul>
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;action=add">Add</a></li>
                                        <?php if ($totalRows_tables > 1) { ?>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;action=assign">Assign Judges/Stewards</a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Flights</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php } ?>
                            <?php if ($totalRows_tables > 1) { ?>
							<div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>BOS Judges</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
							<?php } ?>
                        </div>
                    </div>
                </div><!-- ./ Organizing Panel -->
                <!-- Scoring Panel -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseScoring">Scoring<span class="fa fa-clipboard pull-right"></span></a>
                        </h4>
                    </div>
                    <div id="collapseScoring" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
                               <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Scoresheets and Docs</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-unstyled">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload_scoresheets" data-toggle="tooltip" data-placement="top" title="Upload scoresheets for judged entries">Upload Multiple</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload_scoresheets&amp;action=html" data-toggle="tooltip" data-placement="top" title="Upload scoresheets for judged entries">Upload Individually</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php if ($_SESSION['prefsEval'] == 1) {?>
                            <div class="row">
                               <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Entry Evaluations</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=evaluation&amp;go=default&amp;filter=default&amp;view=admin" data-toggle="tooltip" data-placement="top" title="Manage, View and Edit Judges' evaluations of received entries">Manage</a></li>
                                    </ul> 
                                </div>
                            </div><!-- ./row -->
                            <?php } ?>
                            <div class="row">
                               <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Scores</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-unstyled">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores">Manage</a></li>
                                    <?php if ($_SESSION['prefsEval'] == 1) { ?>
                                        <li><?php echo $import_scores_display; ?></li>
                                    <?php } ?>
                                    </ul>
									<div class="dropdown bcoem-admin-dashboard-select">
										<button class="btn btn-default dropdown-toggle" type="button" id="scoresMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Add Scores to... <span class="caret"></span>
										</button>
										<ul class="dropdown-menu" aria-labelledby="scoresMenu1">
											<?php echo score_table_choose($dbTable,$judging_tables_db_table,$judging_scores_db_table); ?>
										</ul>
									</div>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                               <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>BOS Entries and Places</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-unstyled">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos">Manage</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php if ($_SESSION['userLevel'] == "0") { ?>
                            <div class="row">
                               <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Custom Categories</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-unstyled">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best_data">Manage</a></li>
                                    </ul>

									<div class="dropdown bcoem-admin-dashboard-select">
										<button class="btn btn-default dropdown-toggle" type="button" id="scoresMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Add Entries to... <span class="caret"></span>
										</button>
										<ul class="dropdown-menu" aria-labelledby="scoresMenu2">
											<?php echo score_custom_winning_choose($special_best_info_db_table,$special_best_data_db_table); ?>
										</ul>
									</div>

								</div>
                            </div><!-- ./row -->
                            <?php } ?>
                        </div>
                    </div>
                </div><!-- ./ Scoring Panel -->
            </div><!-- ./ panel-group -->
        </div><!-- ./left column -->
        <!-- Purge Modals -->
        <div class="modal fade" id="cleanUp" tabindex="-1" role="dialog" aria-labelledby="cleanUpLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="cleanUpLabel">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure? This will check the database for duplicate entries, duplicate scores for a single entry, users without associated personal data (no first name, no last name), etc.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','cleanup','cleanup','admin-dashboard','clean-up');">Yes</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="confirmAll" tabindex="-1" role="dialog" aria-labelledby="confirmAllLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="confirmAllLabel">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure? This will mark ALL entries as confirmed even if the entry is incomplete. It could be a large pain to undo.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','confirmed','confirmed','admin-dashboard','conf');">Yes</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="purgeUnconfirmed" tabindex="-1" role="dialog" aria-labelledby="purgeUnconConfirm">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="purgeUnconConfirm">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure? This will delete ALL unconfirmed entries and/or entries without special ingredients/classic style info that require them from the database - even those that are less than 24 hours old. This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','','unconfirmed','admin-dashboard','purge-un');">Yes</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="purgeParticipants" tabindex="-1" role="dialog" aria-labelledby="previewBestLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="previewBestLabel">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete non-admin participants and associated data (including each user's entries, as well as their judge, steward, and staff assignments)? This cannot be undone.</p>
                    <p>Optionally, choose a date threshold. User accounts and associated data will not be purged <strong>if they were <em>updated</em> on or after</strong> the date you choose.</p>
                    <p>Leave the field blank to purge all non-admin participants.</p>
                    <div class="input-group">
                        <input class="form-control" id="purge-part-participants-value" name="dateThreshold" type="text" value="" placeholder="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','','participants','admin-dashboard','purge-part');">Yes</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="purgePayments" tabindex="-1" role="dialog" aria-labelledby="purgePaymentsLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="purgePaymentsLabel">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete payments and associated data? This cannot be undone.</p>
                    <p>Optionally, choose a date threshold. Payments and associated data will not be purged <strong>if they were <em>updated</em> on or after</strong> the date you choose.</p>
                    <p>Leave the field blank to purge all payment data.</p>
                    <div class="input-group">
                        <input class="form-control" id="purge-pay-payments-value" name="dateThreshold" type="text" value="" placeholder="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','','payments','admin-dashboard','purge-pay');">Yes</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="purgeEntries" tabindex="-1" role="dialog" aria-labelledby="purgeEntriesLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="purgeEntriesLabel">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete entries and associated data, including scores, BOS scores, and associated <?php if ($_SESSION['prefsEval'] == 1) echo "entry evaluations recorded by judges"; else echo "scoresheets"; ?> (if present)? This cannot be undone.</p>
                    <p>Optionally, choose a date threshold. Entries and associated data will not be purged <strong>if they were <em>updated</em> on or after</strong> the date you choose.</p>
                    <p>Leave the field blank to purge all entries.</p>
                    <div class="input-group">
                        <input class="form-control" id="purge-ent-entries-value" name="dateThreshold" type="text" value="" placeholder="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','','entries','admin-dashboard','purge-ent');">Yes</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="purgeScoresheets" tabindex="-1" role="dialog" aria-labelledby="purgeScoresheetsLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="purgeScoresheetsLabel">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <?php if ($_SESSION['prefsEval'] == 1) { ?>
                    <p>Are you sure you want to delete all recorded entry evaluations? This cannot be undone. </p>
                    <p>Use the archive function if you wish to retain any evaluations recorded for past competition iterations.</p>
                    <?php } else { ?>
                    <p>Are you sure you want to delete all uploaded scoresheets in the root of the user_docs directory? This cannot be undone. </p>
                    <p>Use the archive function if you wish to retain any uploaded scoresheets contained in the user_docs directory.</p>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <?php if ($_SESSION['prefsEval'] == 1) { ?>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','','evaluation','admin-dashboard','purge-sheets');">Yes</button>
                    <?php } else { ?>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','','scoresheets','admin-dashboard','purge-sheets');">Yes</button>
                    <?php } ?>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="purgeAvailabilty" tabindex="-1" role="dialog" aria-labelledby="purgeAvailabiltyLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="purgeAvailabiltyLabel">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reset all entrant availability? This cannot be undone.
                    <p>All current judge, steward, and staff assignments will be cleared, judge/steward availability will be set to &ldquo;No,&rdquo; location preferences will be set to to &ldquo;No,&rdquo; and entrant staff interest will be set to &ldquo;No&rdquo; for all entrants.
                    <p>This is useful for sites that are carrying over user data to another competition instance, however, it is critical that all entrants be notified to update their judge, steward, and staff availability. </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','','availability','admin-dashboard','purge-avail');">Yes</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="purgeCustom" tabindex="-1" role="dialog" aria-labelledby="purgeCustomLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="purgeCustomLabel">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete all custom categories and associated data? This cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','','custom','admin-dashboard','purge-cust');">Yes</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="purgeScores" tabindex="-1" role="dialog" aria-labelledby="purgeScoresLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="purgeScoresLabel">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete all scoring data from the database including best of show? This cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','','scores','admin-dashboard','purge-score');">Yes</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="purgeTables" tabindex="-1" role="dialog" aria-labelledby="purgeTablesLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="purgeTablesLabel">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete all judging tables and associated data including judging/stewarding table assignments and scores? This cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','','tables','admin-dashboard','purge-table');">Yes</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="purgeAll" tabindex="-1" role="dialog" aria-labelledby="purgeAllLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="purgeAllLabel">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong>ALL</strong> entry, participant, judging table, score, and custom category data, <?php if ($_SESSION['prefsEval'] == 1) echo "including all entry evaluations recorded by judges?"; else echo "including any scoresheets?"; ?></p>
                    <p>This cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','','purge-all','admin-dashboard','purge-total');">Yes</button>
                </div>
                </div>
            </div>
        </div>
        <!-- END Purge Modals -->
        <!-- BEGIN Right column accordions -->
		<div class="col col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="panel-group" id="accordion2">
			<!-- Reports Panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapseReports">Reports<span class="fa fa-file pull-right"></span></a>
					</h4>
				</div>
				<div id="collapseReports" class="panel-collapse collapse">
					<div class="panel-body">
						<div class="row">
							<div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h5>Before Judging<hr></h5>
							</div>
						</div><!-- ./row -->
                        <div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>Notes</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-inline">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=notes&amp;go=org_notes" data-toggle="tooltip" data-placement="top" title="A List of Notes Individual Judges Have Provided to the Organizer - Includes Reports of Allergies">Notes to Organizer</a></li>
                                </ul>
							</div>
						</div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Allergens</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-inline">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=notes&amp;go=allergens" data-toggle="tooltip" data-placement="top" title="A List of Entries with Allergen Information">Possible Allergens in Entries</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>Drop-Off</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-inline">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=dropoff" data-toggle="tooltip" data-placement="top" title="Print Entry Totals for Each Drop-Off Location">Entry Totals</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=dropoff&amp;go=check" data-toggle="tooltip" data-placement="top" title="Print Entries By Drop-Off Location">List of Entries</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
						<?php if ($totalRows_tables > 0) { ?>
						<div class="row">
                            <?php if ($_SESSION['jPrefsTablePlanning'] == 0) { ?>
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong><?php echo $label_additional_info; ?></strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-unstyled">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=all_entry_info&amp;view=entry&amp;id=default" data-toggle="tooltip" data-placement="top" title="Print Entries with Addtional Info by Table">Entry Numbers (By Table)</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=all_entry_info&amp;id=default" data-toggle="tooltip" data-placement="top" title="Print Entries with Addtional Info by Table">Judging Numbers (By Table)</a></li>
                                </ul>
                                <div class="dropdown bcoem-admin-dashboard-select">
                                <button class="btn btn-default dropdown-toggle" type="button" id="additionalInfoMenu0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Entry Numbers for Table... <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="additionalInfoMenu0">
                                        <?php echo table_choose("pullsheets","all_entry_info",$action,$filter,"entry","output/print.output.php","thickbox"); ?>
                                    </ul>
                                </div>
                                <div class="dropdown bcoem-admin-dashboard-select">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="additionalInfoMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Judging Numbers for Table... <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="additionalInfoMenu1">
                                        <?php echo table_choose("pullsheets","all_entry_info",$action,$filter,"default","output/print.output.php","thickbox"); ?>
                                    </ul>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if ($_SESSION['jPrefsTablePlanning'] == 0) { ?>
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>Pullsheets</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-unstyled">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;view=entry&amp;id=default" data-toggle="tooltip" data-placement="top" title="Print All Table Pullsheets with Entry Numbers">Entry Numbers</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=mini_bos&amp;view=entry" data-toggle="tooltip" data-placement="top" title="Print a Mini-BOS Table Pullsheet with Judging Numbers">Entry Numbers (Mini-BOS - All)</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;view=entry&amp;filter=mini_bos&amp;id=default" data-toggle="tooltip" data-placement="top" title="Print All Mini-BOS Table Pullsheets with Entry Numbers">Entry Numbers (Mini-BOS - By Table)</a></li>
                                </ul>
                                <ul class="list-unstyled">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;id=default" data-toggle="tooltip" data-placement="top" title="Print All Table Pullsheets with Judging Numbers">Judging Numbers</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=mini_bos" data-toggle="tooltip" data-placement="top" title="Print a Mini-BOS Table Pullsheet with Judging Numbers">Judging Numbers (Mini-BOS - All)</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;filter=mini_bos&amp;id=default" data-toggle="tooltip" data-placement="top" title="Print All Mini-BOS Table Pullsheets with Judging Numbers">Judging Numbers (Mini-BOS - By Table)</a></li>
								</ul>
								<div class="dropdown bcoem-admin-dashboard-select">
									<button class="btn btn-default dropdown-toggle" type="button" id="pullsheetMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Entry Numbers for Table... <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" aria-labelledby="pullsheetMenu3">
										<?php echo table_choose("pullsheets","judging_tables",$action,$filter,"entry","output/print.output.php","thickbox"); ?>
                                        <li role="separator" class="divider"></li>
                                        <?php echo table_choose("pullsheets","judging_tables",$action,"mini_bos","entry","output/print.output.php","thickbox"); ?>
									</ul>
								</div>
								<div class="dropdown bcoem-admin-dashboard-select">
									<button class="btn btn-default dropdown-toggle" type="button" id="pullsheetMenu4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Judging Numbers for Table... <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" aria-labelledby="pullsheetnMenu4">
										<?php echo table_choose("pullsheets","judging_tables",$action,$filter,$view,"output/print.output.php","thickbox"); ?>
                                        <li role="separator" class="divider"></li>
                                        <?php echo table_choose("pullsheets","judging_tables",$action,"mini_bos",$view,"output/print.output.php","thickbox"); ?>
									</ul>
								</div>
								<div class="dropdown bcoem-admin-dashboard-select">
									<button class="btn btn-default dropdown-toggle" type="button" id="pullsheetMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Entry Numbers for Session... <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
										<?php 
                                        if (!empty($ps_loc_entry)) echo $ps_loc_entry; 
                                        if (!empty($ps_loc_entry_mbos)) {
                                            echo "<li role=\"separator\" class=\"divider\"></li>";
                                            echo $ps_loc_entry_mbos;
                                        }
                                        ?>
									</ul>
								</div>
								<div class="dropdown bcoem-admin-dashboard-select">
									<button class="btn btn-default dropdown-toggle" type="button" id="pullsheetMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Judging Numbers for Session...<span class="caret"></span>
									</button>
									<ul class="dropdown-menu" aria-labelledby="pullsheetMenu2">
										<?php
                                        if (!empty($ps_loc_judging)) echo $ps_loc_judging;
                                        if (!empty($ps_loc_judging_mbos)) {
                                            echo "<li role=\"separator\" class=\"divider\"></li>";
                                            echo $ps_loc_judging_mbos;
                                        }
                                        ?>
									</ul>
								</div>
							</div>
                            <?php } ?>
						</div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Judge Inventories</strong> <a class="hide-loader" tabindex="0" type="button" role="button" data-toggle="popover" data-trigger="hover" data-placement="auto top" data-container="body" data-content="Judge Inventories are generally used judging sessions using the distributed methodology. Prints a list of all entries assigned to each judge for evaluation."><i class="fa fa-question-circle"></i></a>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <div class="dropdown bcoem-admin-dashboard-select">
                                <button class="btn btn-default dropdown-toggle" type="button" id="judging-inv-ent" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Entry Numbers for Session...<span class="caret"></span>
                                    </button>
                                <ul class="dropdown-menu" aria-labelledby="judging-inv-ent">
                                    <?php if (!empty($ji_loc_entry)) echo $ji_loc_entry; ?>
                                </ul>
                                </div>
                                <div class="dropdown bcoem-admin-dashboard-select">
                                <button class="btn btn-default dropdown-toggle" type="button" id="judging-inv-jnum" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Judging Numbers for Session...<span class="caret"></span>
                                    </button>
                                <ul class="dropdown-menu" aria-labelledby="judging-inv-jnum">
                                    <?php if (!empty($ji_loc_judging)) echo $ji_loc_judging; ?>
                                </ul>
                                </div>
                            </div>
                        </div>
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>Table Cards</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-unstyled">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=table-cards&amp;go=judging_tables&amp;id=default" data-toggle="tooltip" data-placement="top" title="Print Table Cards">All Tables</a></li>
								</ul>
								<div class="dropdown bcoem-admin-dashboard-select">
									<button class="btn btn-default dropdown-toggle" type="button" id="cardsMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">For Table... <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" aria-labelledby="cardsMenu1">
										<?php echo table_choose("table-cards","judging_tables",$action,$filter,$view,"output/print.output.php","thickbox"); ?>
									</ul>
								</div>
								<div class="dropdown bcoem-admin-dashboard-select">
									<button class="btn btn-default dropdown-toggle" type="button" id="cardsMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">For Session... <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" aria-labelledby="cardsMenu2">
										<?php if (!empty($cards_loc_rnd)) echo $cards_loc_rnd; ?>
									</ul>
								</div>
							</div>
						</div><!-- ./row -->
                        <?php } ?>
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>Sign In Sheets</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-inline">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=sign-in" data-toggle="tooltip" data-placement="top" title="Print a Judge Sign-in Sheet">Judges</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=sign-in" data-toggle="tooltip" data-placement="top" title="Print a Steward Sign-in Sheet">Stewards</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
                        <?php if ($totalRows_tables > 0) { ?>
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>Assignments</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-unstyled">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=name" data-toggle="tooltip" data-placement="top" title="Print Judge Assignments by Name">All Judges By Last Name</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=table" data-toggle="tooltip" data-placement="top" title="Print Judge Assignments by Table">All Judges By Table</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=location" data-toggle="tooltip" data-placement="top" title="Print Judge Assignments by Session">All Judges By Session</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=name" data-toggle="tooltip" data-placement="top" title="Print Steward Assignments by Name">All Stewards Last Name</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=table" data-toggle="tooltip" data-placement="top" title="Print Steward Assignments by Table">All Stewards By Table</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=location" data-toggle="tooltip" data-placement="top" title="Print Steward Assignments by Session">All Stewards By Session</a></li>
								</ul>
                                <div class="dropdown bcoem-admin-dashboard-select">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="judgeAssignMenuLoc" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Judges for Session... <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="judgeAssignMenuLoc">
                                        <?php foreach($judge_assign_links as $key => $value) { ?>
                                            <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $value."&amp;view=name"; ?>" data-toggle="tooltip" data-placement="top" title="Print Judge Assignments for <?php echo $key; ?>"><?php echo $key; ?> By Name</a></li>
                                        <?php } ?>
                                        <li role="separator" class="divider"></li>
                                        <?php foreach($judge_assign_links as $key => $value) { ?>
                                            <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $value."&amp;view=table"; ?>" data-toggle="tooltip" data-placement="top" title="Print Judge Assignments for <?php echo $key; ?>"><?php echo $key; ?> By Table</a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="dropdown bcoem-admin-dashboard-select">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="stewardAssignMenuLoc" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Stewards for Session... <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="stewardAssignMenuLoc">
                                        <?php foreach($steward_assign_links as $key => $value) { ?>
                                            <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $value."&amp;view=name"; ?>" data-toggle="tooltip" data-placement="top" title="Print Steward Assignments for <?php echo $key; ?>"><?php echo $key; ?> By Name</a></li>
                                        <?php } ?>
                                        <li role="separator" class="divider"></li>
                                        <?php foreach($steward_assign_links as $key => $value) { ?>
                                            <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $value."&amp;view=table"; ?>" data-toggle="tooltip" data-placement="top" title="Print Steward Assignments for <?php echo $key; ?>"><?php echo $key; ?> By Table</a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
							</div>
                                
						</div><!-- ./row -->
                        <?php } ?>
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>Judge Scoresheet Labels</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-inline">
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;psort=5160" data-toggle="tooltip" data-placement="top" title="Avery 5160">Letter</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;psort=3422" data-toggle="tooltip" data-placement="top" title="Avery 3422">A4</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Entry Required Info Scoresheet Labels</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-unstyled">
                                    <li>Letter (Entry Numbers)
                                        <div class="dropdown bcoem-admin-dashboard-select">
                                            <button class="btn btn-default dropdown-toggle" type="button" id="reqLablesMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="reqLablesMenu1">
                                                <?php for($i=1; $i<=6; $i++) { ?>
                                                <li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;view=special&amp;psort=5160&amp;sort=<?php echo $i; ?>&amp;tb=received"><?php echo $i; ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>Letter (Judging Numbers)
                                        <div class="dropdown bcoem-admin-dashboard-select">
                                            <button class="btn btn-default dropdown-toggle" type="button" id="reqLablesMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="reqLablesMenu2">
                                                <?php for($i=1; $i<=6; $i++) { ?>
                                                <li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=special&amp;&amp;psort=5160&amp;sort=<?php echo $i; ?>&amp;tb=received"><?php echo $i; ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>A4 (Entry Numbers)
                                        <div class="dropdown bcoem-admin-dashboard-select">
                                            <button class="btn btn-default dropdown-toggle" type="button" id="reqLablesMenu3reqLablesMenu4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="reqLablesMenu3reqLablesMenu4">
                                                <?php for($i=1; $i<=6; $i++) { ?>
                                                <li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;view=special&amp;psort=3422&amp;sort=<?php echo $i; ?>&amp;tb=received"><?php echo $i; ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>A4 (Judging Numbers)
                                        <div class="dropdown bcoem-admin-dashboard-select">
                                            <button class="btn btn-default dropdown-toggle" type="button" id="reqLablesMenu4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="reqLablesMenu4">
                                                <?php for($i=1; $i<=6; $i++) { ?>
                                                <li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=special&amp;&amp;psort=3422&amp;sort=<?php echo $i; ?>&amp;tb=received"><?php echo $i; ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>Name Tags</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-inline">
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=participants&amp;action=judging_nametags&amp;psort=5395" data-toggle="tooltip" data-placement="top" title="Avery 5395">Letter</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
                        <?php 
                        if ($totalRows_tables > 0) { 

                            

                        ?>
						<div class="row">
							<div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h5>During Judging<hr></h5>
							</div>
						</div><!-- ./row -->
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>BOS Pullsheets</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-unstyled">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_scores_bos&amp;view=entry" data-toggle="tooltip" data-placement="top" title="Print All BOS Pullsheets Using Entry Numbers">All Style Types - Entry Numbers</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_scores_bos" data-toggle="tooltip" data-placement="top" title="Print All BOS Pullsheets Using Judging Numbers">All Style Types - Judging Numbers</a></li>
                                </ul>
                                <div class="dropdown bcoem-admin-dashboard-select">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="bos-pullsheet-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Entry Numbers for Style Type... <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="bos-pullsheet-menu">
                                        <?php echo $bos_pull_st_entry; ?>
                                    </ul>
                                </div>
                                <div class="dropdown bcoem-admin-dashboard-select">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="bos-pullsheet-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Judging Numbers for Style Type... <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="bos-pullsheet-menu">
                                        <?php echo $bos_pull_st_judging; ?>
                                    </ul>
                                </div>
							</div>
						</div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Pro-Am/Scale-up Pullsheets</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <div class="dropdown bcoem-admin-dashboard-select">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="bos-pullsheet-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Entry Numbers for Style Type... <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="bos-pullsheet-menu">
                                        <?php echo $bos_pull_pro_am_st_entry; ?>
                                    </ul>
                                </div>
                                <div class="dropdown bcoem-admin-dashboard-select">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="bos-pullsheet-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Judging Numbers for Style Type... <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="bos-pullsheet-menu">
                                        <?php echo $bos_pull_pro_am_st_judging; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>BOS Cup Mats</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-unstyled">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=bos-mat&amp;filter=entry" data-toggle="tooltip" data-placement="top" title="Print all BOS Cup Mats with entry numbers only">All Style Types - Entry Numbers</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=bos-mat" data-toggle="tooltip" data-placement="top" title="Print all BOS Cup Mats with judging numbers only">All Style Types - Judging Numbers</a></li>
                                </ul>
                                <div class="dropdown bcoem-admin-dashboard-select">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="bos-pullsheet-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Entry Numbers for Style Type... <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="bos-pullsheet-menu">
                                        <?php echo $bos_cup_mat_st_entry; ?>
                                    </ul>
                                </div>
                                <div class="dropdown bcoem-admin-dashboard-select">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="bos-pullsheet-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Judging Numbers for Style Type... <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="bos-pullsheet-menu">
                                        <?php echo $bos_cup_mat_st_judging; ?>
                                    </ul>
                                </div>
                            </div>
                        </div><!-- ./row -->
                        <?php } ?>
						<div class="row">
							<div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h5>After Judging<hr></h5>
							</div>
						</div><!-- ./row -->
						<?php if ($totalRows_tables > 0) { ?>
                        <div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>BOS Results</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-inline">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores_bos&amp;action=print&amp;tb=bos&amp;view=default" title="BOS Round(s) Results Report">Print</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=results&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=pdf">PDF</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=results&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=html">HTML</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
                        <?php if (($_SESSION['prefsShowBestBrewer'] != 0) || ($_SESSION['prefsShowBestClub'] != 0)) { ?>
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Best Brewer and/or Club</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-inline">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=best&amp;action=print&amp;tb=bos&amp;view=default" title="Best Brewer and/or Club Results Report">Print</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <?php } ?>
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Table Results (<?php echo $results_method[$_SESSION['prefsWinnerMethod']]; ?>)</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-inline">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores&amp;action=print&amp;tb=scores&amp;view=default" data-toggle="tooltip" data-placement="top" title="Print all entry results with scores listed">All with Scores</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores&amp;action=print&amp;tb=scores&amp;view=winners" data-toggle="tooltip" data-placement="top" title="Print winners only results with scores listed">Winners Only with Scores</a></li>
                                </ul>
                                <ul class="list-inline">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores&amp;action=print&amp;view=default" data-toggle="tooltip" data-placement="top" title="Print all entry results without scores listed">All without Scores</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores&amp;action=print&amp;view=winners" data-toggle="tooltip" data-placement="top" title="Print winners only results without scores listed">Winners Only without Scores</a></li>
                                </ul>
                                <ul class="list-inline">
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=results&amp;go=judging_scores&amp;action=default&amp;tb=none&amp;view=pdf" data-toggle="tooltip" data-placement="top" title="Download a PDF report of results - winners only without scores">PDF</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=results&amp;go=judging_scores&amp;action=default&amp;tb=none&amp;view=html" data-toggle="tooltip" data-placement="top" title="Download a HTML report of results to copy/paste into another website - winners only without scores">HTML</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>All Results (<?php echo $results_method[$_SESSION['prefsWinnerMethod']]; ?> - Single Report)</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-inline">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=all&amp;action=print&amp;tb=scores&amp;view=default" data-toggle="tooltip" data-placement="top" title="Print all entry results with scores listed">All with Scores</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=all&amp;action=print&amp;tb=scores&amp;view=winners" data-toggle="tooltip" data-placement="top" title="Print winners only results with scores listed">Winners Only with Scores</a></li>
                                </ul>
                                <ul class="list-inline">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=all&amp;action=print" data-toggle="tooltip" data-placement="top" title="Print all entry results without scores listed">All without Scores</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=all&amp;action=print&amp;view=winners" data-toggle="tooltip" data-placement="top" title="Print winners only results without scores listed">Winners Only without Scores</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <?php } ?>
                        <div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>BJCP Points</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-inline">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=staff&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=default" data-toggle="tooltip" data-placement="top" title="Print the BJCP Points report for judges, stewards, and staff">Print</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=staff&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=pdf" data-toggle="tooltip" data-placement="top" title="Download a PDF of the BJCP points report for judges, stewards, and staff">PDF</a></li>
                                    <?php if (empty($_SESSION['contestID'])) { ?>
                                    <li><a href="#"  data-toggle="modal" data-target="#BJCPCompIDModal">XML</a></li>
                                    <?php } else { ?>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=staff&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=xml" data-toggle="tooltip" data-placement="top" title="Download a fully compliant XML version of the points report to submit to the BJCP">XML</a></li>
                                    <?php } ?>
								</ul>
							</div>
						</div><!-- ./row -->
                        <?php if (empty($_SESSION['contestID'])) { ?>
                        <!-- Modal -->
                        <div class="modal fade" id="BJCPCompIDModal" tabindex="-1" role="dialog" aria-labelledby="BJCPCompIDModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bcoem-admin-modal">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="BJCPCompIDModalLabel">BJCP Competition ID Not Found</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>An XML Report cannot be generated at this time</strong> - a BJCP Competition ID has not been entered via the competition info screen.</p>
                                        <p>You should have received a competition ID from the BJCP when you <a class="hide-loader" href="http://bjcp.org/apps/comp_reg/comp_reg.php" target="_blank">registered your competition</a>. If so, <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contest_info">edit your competition info</a> and enter it in the appropriate field. The BJCP will <em>not</em> accept an XML competition report without a competition ID.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div><!-- ./modal -->
                        <?php } ?>
                     	<?php if ($totalRows_tables > 0) { ?>
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>Award Labels</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-inline">
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=default&amp;psort=5160" data-toggle="tooltip" data-placement="top" title="Avery 5160">Letter</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=default&amp;psort=3422" data-toggle="tooltip" data-placement="top" title="Avery 3422">A4</a></li>
								</ul>
							</div>
						</div><!-- ./row -->

                        <div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>Medal Labels (Round)</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-inline">
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=round&amp;psort=5293" data-toggle="tooltip" data-placement="top" title="1 2/3 inch Round Avery 5293">Letter</a></li>
								</ul>
							</div>
						</div>
						<?php } ?>
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>Address Labels</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-inline">
									<li>All Participants</li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=default&amp;psort=5160" data-toggle="tooltip" data-placement="top" title="Avery 5160">Letter</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=default&amp;psort=3422" data-toggle="tooltip" data-placement="top" title="Avery 3422">A4</a></li>
								</ul>
								<ul class="list-inline">
									<li>All Participants with Entries
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=with_entries&psort=5160" data-toggle="tooltip" data-placement="top" title="Avery 5160">Letter</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=with_entries&psort=3422" data-toggle="tooltip" data-placement="top" title="Avery 3422">A4</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>Summaries</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-inline">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=summary" data-toggle="tooltip" data-placement="top" title="Print participant summaries - each on a separate sheet of paper. Useful as a cover sheet for mailing entry scoresheets to participants.">All Participants with Entries</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=particpant-entries" data-toggle="tooltip" data-placement="top" title="Print a list of all participants with entries and associated judging numbers as assigned in the system. Useful for distributing scoresheets that are physically sorted by entry or judging numbers.">All Entries by Particpant</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
								<strong>Inventory</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
								<ul class="list-inline">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=inventory&amp;go=scores" data-toggle="tooltip" data-placement="top" title="Print an inventory of entry bottles remaining after judging - with scores">With Scores</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=inventory" data-toggle="tooltip" data-placement="top" title="Print an inventory of entry bottles remaining after judging - without scores">Without Scores</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
					</div>
				</div>
			</div>
			<!-- ./ Reports Panel -->
            <!-- Data Exports Panel -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseExports">Data Exports<span class="fa fa-download pull-right"></span></a>
                    </h4>
                </div>
                <div id="collapseExports" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Email Addresses and Associated Contact Data (CSV)</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-unstyled">
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=emails">All Participants</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=emails&amp;go=csv&amp;filter=avail_judges&amp;action=email">Available Judges</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=emails&amp;go=csv&amp;filter=avail_stewards&amp;action=email">Available Stewards</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=emails&amp;go=csv&amp;filter=judges&amp;action=email">Assigned Judges</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=emails&amp;go=csv&amp;filter=stewards&amp;action=email">Assigned Stewards</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=emails&amp;go=csv&amp;filter=staff&amp;action=email">Assigned Staff</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Participant Data (CSV)</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-unstyled">
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=participants&amp;go=csv">All Participants</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;tb=winners">Winners</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Entries and Associated Data (CSV)</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-unstyled">
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;action=all&amp;tb=all">All Entries: All Data</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv">All Entries: Limited Data</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;tb=brewer_contact_info">All Entries: Limited Data with Brewer Contact Info</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;tb=paid&amp;view=all">Paid Entries</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;tb=paid">Paid &amp; Received Entries</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;tb=paid&amp;view=not_received">Paid Entries Not Received</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;tb=nopay&amp;view=all">Non-Paid Entries</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;tb=nopay">Non-Paid &amp; Received Entries</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;action=required&amp;tb=required">Entries with Required &amp; Optional Info</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Promo Materials</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-unstyled">
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=promo&amp;go=html&amp;action=html">HTML</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=promo&amp;go=word&amp;action=word">Word</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=promo&amp;go=word&amp;action=bbcode">Bulletin Board Code (BBC)</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                    </div>
                </div>
            </div>
            <!-- ./ Data Exports Panel -->
			<?php if ($_SESSION['userLevel'] == "0") { ?>
            <!-- Database Maintenance Panel -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseMaint">Data Management<span class="fa fa-archive pull-right"></span></a>
                    </h4>
                </div>
                <div id="collapseMaint" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Integrity</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-inline">
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#cleanUp">Clean-Up Data</a></li>
                                    <div>
                                        <span style="margin-bottom: 10px;" class="hidden" id="clean-up-status"></span>
                                        <span style="margin-bottom: 10px;" class="hidden" id="clean-up-status-msg"></span>
                                    </div>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Entries</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-unstyled">
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#confirmAll">Confirm All Unconfirmed</a></li>
                                    <div>
                                        <span style="margin-bottom: 10px;" class="hidden" id="conf-status"></span>
                                        <span style="margin-bottom: 10px;" class="hidden" id="conf-status-msg"></span>
                                    </div>
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#purgeUnconfirmed">Purge All Unconfirmed</a></li>
                                    <div>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-un-status"></span>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-un-status-msg"></span>
                                    </div>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                            <strong>Purge</strong>
                        </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-unstyled">
                                    <li><a href="#" data-toggle="modal" data-target="#purgeEntries">Entries</a></li>
                                    <div>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-ent-status"></span>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-ent-status-msg"></span>
                                    </div>
                                    <?php if (check_setup($prefix."payments",$database)) { ?>
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#purgePayments">Payments</a></li>
                                    <div>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-pay-status"></span>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-pay-status-msg"></span>
                                    </div>
                                    <?php } ?> 
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#purgeParticipants">Participants</a></li>
                                    <div>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-part-status"></span>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-part-status-msg"></span>
                                    </div>
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#purgeTables">Judging Tables</a></li>
                                    <div>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-table-status"></span>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-table-status-msg"></span>
                                    </div>                            
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#purgeScores">Scores</a></li>
                                    <div>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-score-status"></span>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-score-status-msg"></span>
                                    </div>
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#purgeCustom">Custom Categories</a></li>
                                    <div>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-cust-status"></span>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-cust-status-msg"></span>
                                    </div>
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#purgeAvailabilty">Entrant Availability</a></li>
                                    <div>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-avail-status"></span>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-avail-status-msg"></span>
                                    </div>
                                    <?php if ($_SESSION['prefsEval'] == 1) { ?>
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#purgeScoresheets">Entry Evaluations</a></li>
                                    <?php } else { ?>
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#purgeScoresheets">Uploaded Scoresheets</a></li>
                                    <?php } ?>
                                    <div>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-sheets-status"></span>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-sheets-status-msg"></span>
                                    </div>
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#purgeAll">All Purge Functions</a> <span class="fa fa-hand-o-up small"></span></li>
                                    <div>
                                        <span class="hidden" id="purge-total-status"></span>
                                        <span style="margin-bottom: 10px;" class="hidden" id="purge-total-status-msg"></span>
                                    </div>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <?php if ($_SESSION['userLevel'] == "0") { ?>
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Archives</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-inline">
                                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive">Manage</a></li>
                                    <?php if (!HOSTED) { ?>
                                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive&amp;action=add">Archive Current Data</a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <?php } ?>
                    </div>
                </div>
            </div><!-- ./ Database Maintenance Panel -->
            <!-- Preferences Panel -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapsePrefs">Preferences<?php if (($_SESSION['prefsUseMods'] == "Y") && (!HOSTED)) { ?>/Customization<?php } ?><span class="fa fa-cog pull-right"></span></a>
                    </h4>
                </div>
                <div id="collapsePrefs" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="row">
                        <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                            <strong>Preferences</strong>
                        </div>
                        <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                            <ul class="list-inline">
                                <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences">Website</a></li>
                                <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences">Competition Organization</a></li>
                            </ul>
                        </div>
                        </div><!-- ./row -->
						<?php if (($_SESSION['prefsUseMods'] == "Y") && (!HOSTED)) { ?>
						 <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                <strong>Custom Modules</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                <ul class="list-inline">
                                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=mods">Manage</a></li>
									<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=mods&amp;action=add">Add</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
						<?php } ?>
                    </div>
                </div>
            </div>
            <!-- ./ Preferences Panel -->
            <?php } ?>
            <!-- Help Panel -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapseHelp">Help<span class="fa fa-question-circle pull-right"></span></a>
                        </h4>
                    </div>
                    <div id="collapseHelp" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php if ($hosted_setup) { ?>
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Customize</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-unstyled">
                                        <li><a href="http://brewcompetition.com/customize-comp-info" target="_blank">Competition Information</a></li>
                                    </ul>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Guides</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-unstyled">
                                        <?php if (HOSTED) { ?>
                                        <li><a href="https://brewcompetition.com/customize-competition-info" target="_blank">Customize Competition Information Guide</a></li>
                                        <?php } ?>
                                        <li><a href="https://brewcompetition.com/comp-org" target="_blank">Competition Organizer's Guide</a></li>
                                        <li><a href="https://brewcompetition.com/reset-comp" target="_blank">Reset Competition Information Guide</a></li>
                                        <li><a href="https://brewcompetition.com/paypal-ipn" target="_blank">Implement PayPal Instant Payment Notifications Guide</a></li>
                                        <li><a href="https://brewcompetition.com/upload-scoresheets" target="_blank">Upload Scanned Judges’ Scoresheets Guide</a></li>
                                        <li><a href="https://brewcompetition.com/barcode-check-in" target="_blank">Barcode or QR Code Entry Check-In Guide</a></li>
                                        <?php if ($_SESSION['prefsEval'] == 1) { ?>
                                        <li><a href="https://brewcompetition.com/setup-electronic-scoresheets" target="_blank">Setup BCOE&M Electronic Scoresheets Guide</a></li>
                                        <li><a href="https://brewcompetition.com/judging-with-electronic-scoresheets" target="_blank">Judging with BCOE&M Electronic Scoresheets Guide</a></li>
                                        <li><a href="https://brewcompetition.com/virtual-judging" target="_blank">Virtual Judging Guide</a></li>
                                        <li><a href="https://brewcompetition.com/virtual-judging/tips" target="_blank">Virtual Judging - Tips for Judges</a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>Customize Installation</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-unstyled">
                                        <li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-comp-prep">Competition Preparation</a></li>
                                        <li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-entries-participants">Entries and Participants</a></li>
                                        <li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-sorting">Entry Sorting</a></li>
										<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-organizing">Organizing</a></li>
                                        <li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-scoring">Scoring</a></li>
                                        <li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-preferences">Preferences</a></li>
                                        <li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-reports">Reports</a></li>
										<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-data-exports">Data Exports</a></li>
                                        <li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-data-mgmt">Data Management</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 small">
                                    <strong>How Do I...</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-12 small">
                                    <ul class="list-unstyled">
                                    	<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-comp-logo">Display the Competition Logo?</a></li>
                                       	<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-sponsor-logo">Display Sponsors with Logos?</a></li>
                                 		<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-check-in">Check In Received Entries?</a></li>
                                       	<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-tables">Set Up Judging Tables?</a></li>
                                     	<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-assign-tables">Assign Judges/Stewards to Tables?</a></li>
                                 		<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-materials">Print Judging Day Materials?</a></li>
                                		<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-bos-judges">Assign Best of Show Judges?</a></li>
										<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-bos-results">Enter Scores and BOS Results?</a></li>
                                  		<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-winning">Display Winning Entries?</a></li>
                                    	<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-pro-am">Display Pro-Am Winner(s)?</a></li>
										<li><a class="hide-loader" href="https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues" target="_blank">Report an Issue?</a></li>
                                        <!--
                                  		<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-winner-rpt">Print a Winner Report</a></li>
                                		<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-bjcp-points">Report BJCP Judging Points</a></li>
                                        -->
                                    </ul>
                                    <div class="alert alert-info">
                                        <?php echo $server_environ; ?>
                                    </div>
                                </div>
                            </div><!-- ./row -->
                        </div>
                    </div>
                </div>
                <!-- ./ Help Panel -->
            </div><!--./ panel-group" -->
        </div><!-- ./ right column -->
    </div>
</div><!-- end bcoem-admin-dashboard-accordion -->
<!-- Dashboard Help Modals -->
<?php foreach ($bcoem_dashboard_help_array as $content)  {
	echo bcoem_dashboard_help($content);
}
?>
