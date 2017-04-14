<?php 
include(DB.'styles.db.php'); 
include(DB.'admin_judging_tables.db.php');

if (strpos($section, "step") === FALSE) {
	if ($_SESSION['jPrefsQueued'] == "N") $assign_to = "Flights"; 
	else $assign_to = "Tables";
}

if ($action == "edit") $title = ": Edit a Table"; 
elseif ($action == "add") $title = ": Add a Table"; 
elseif (($action == "assign") && ($filter == "default")) $title = ": Assign Judges or Stewards to Tables";
elseif (($action == "assign") && ($filter == "judges")) $title = ": Assign Judges to a Table"; 
elseif (($action == "assign") && ($filter == "stewards")) $title = ": Assign Stewards a to Table"; 
else $title = " Judging Tables"; if ($dbTable != "default") $title .= ": All Judging Tables (Archive ".get_suffix($dbTable).")";
$output_at_table_modals = "";
?>
<p class="lead"><?php echo $_SESSION['contestName'].$title;  ?></p>
<?php if (($action == "default") && ($filter == "default") && ($dbTable == "default")) { ?>
    <p>To ensure accuracy, verify that all paid and received entries have been marked as so via the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Manage Entries</a> screen.</p>
<?php } ?>
<div class="bcoem-admin-element hidden-print">
	<?php if  ($dbTable != "default") { ?>
    <!-- Postion 1: View All Button -->
    <div class="btn-group" role="group" aria-label="archives">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive"><span class="fa fa-arrow-circle-left"></span> Archives</a>
    </div><!-- ./button group -->
	<?php } ?>
    <?php if ($dbTable == "default") { ?>
	<?php if (($action != "default") || ($filter != "default")) { ?>
    <!-- Postion 1: View All Button -->
    <div class="btn-group" role="group" aria-label="allTables">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables"><span class="fa fa-arrow-circle-left"></span> All Tables</a>
    </div><!-- ./button group -->
    <?php } ?>
    <?php if ($action == "default") { ?>
    <!-- Postion 2: Add Button -->
    <div class="btn-group" role="group" aria-label="addTable">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Table</a>
    </div><!-- ./button group -->
    <?php } ?>
	<!-- View Button Group Dropdown -->
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-eye"></span> View...   
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=name&amp;tb=view" title="View Assignments by Name">Judge Assignments By Last Name</a></li>
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=table&amp;tb=view" title="View Assignments by Table">Judge Assignments By Table</a></li>
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=name&amp;tb=view" title="View Assignments by Name">Steward Assignments By Last Name</a></li>
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=table&amp;tb=view" title="View Assignments by Table">Steward Assignments By Table</a></li>
            <li class="small"><a href="#" data-toggle="modal" data-target="#availJudgeModal">Judges Not Assigned to a Table</a></li>
            <li class="small"><a href="#" data-toggle="modal" data-target="#availStewardModal">Stewards Not Assigned to a Table</a></li>
        </ul>
    </div><!-- ./button group -->
	<?php if ($action == "default") { ?>
	<!-- Postion 4: Print Button Dropdown Group -->
    <div class="btn-group hidden-xs hidden-sm" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-print"></span> Print...   
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li class="small"><a href="javascript:window.print()">Tables List</a></li>
    		<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;id=default">Pullsheets by Table</a></li>
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=name" title="Print Judge Assignments by Name">Judge Assignments By Last Name</a></li>
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output//print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=table" title="Print Judge Assignments by Table">Judge Assignments By Table</a></li>
   			<?php if ($totalRows_judging > 1) { ?>
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output//print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=location" title="Print Judge Assignments by Location">Judge Assignments By Location</a></li>
    		<?php } ?>
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output//print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=name" title="Print Steward Assignments by Name">Steward Assignments By Last Name</a></li>
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output//print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=table" title="Print Steward Assignments by Table">Steward Assignments By Table</a></li>
   			<?php if ($totalRows_judging > 1) { ?>
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output//print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=location" title="Print Steward Assignments by Location">Steward Assignments By Location</a></li>
    		<?php } ?>
        </ul>
    </div>
	<?php } ?>
    <?php } ?>
</div>
<?php if (($action == "default") && ($dbTable == "default")) { ?>

<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#sortableJ').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[0,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			null,
			null				]
		} );
	} );
</script>

<!-- Available Judges Modal -->
<!-- Modal -->
<div class="modal fade" id="availJudgeModal" tabindex="-1" role="dialog" aria-labelledby="availJudgeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="availJudgeModalLabel">Judges Not Assigned to a Table</h4>
            </div>
            <div class="modal-body">
                <?php echo not_assigned("J"); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->

<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#sortableS').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[0,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			null,
			null				]
		} );
	} );
</script>

<!-- Available Stewards Modal -->
<!-- Modal -->
<div class="modal fade" id="availStewardModal" tabindex="-1" role="dialog" aria-labelledby="availStewardModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="availStewardModalLabel">Stewards Not Assigned to a Table</span> </h4>
            </div>
            <div class="modal-body">
                <?php echo not_assigned("S"); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->

<div class="bcoem-admin-dashboard-accordion hidden-print">
<div class="row">
	<!-- Left Column -->
	<div class="col col-lg-9 col-md-8 col-sm-12 col-xs-12">
		<!-- Start 2 Column Accordion -->
		
			<div class="row">
				<!-- Accordion Right Column -->
				<div class="col col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<div class="panel-group" id="accordion1">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion1" href="#collapseStep1">Step 1: Assign Judges or Stewards<span class="fa fa-user pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep1" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Assign Judges</a></li>
            						<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Assign Stewards</a></li>
                                </ul>
							</div>
						</div>
					</div><!-- ./accordion -->
					
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion1" href="#collapseStep2">Step 2: Define All Tables<span class="fa fa-tasks pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep2" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;action=add">Add a Table</a></li>
									<li><a href="#" data-toggle="modal" data-target="#orphanModal">Style Sub-Categories Not Assigned to Tables</a></li>
                                </ul>
							</div>
						</div>
					</div><!-- ./accordion -->
                    
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion1" href="#collapseStep3">Step 3: Define All Flights<span class="fa fa-send pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep3" class="panel-collapse collapse">
							<div class="panel-body">
                            	<div class="row">
                                	<div class="col col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                     <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
                                        <select class="selectpicker" name="tables" id="tables" onchange="jumpMenu('self',this,0)" data-size="10" data-width="auto">
                                        <option value="" selected disabled>For Table...</option>
                                         <?php do { 
													$flight_count = table_choose($section,$go,$action,$row_tables_edit['id'],$view,"default","flight_choose");
													$flight_count = explode("^",$flight_count);
											?>
                                            <option value="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights&amp;filter=define&amp;action=<?php if ($flight_count[0] > 0) echo "edit"; else echo "add"; echo "&amp;id=".$row_tables_edit['id']; ?>"><?php echo "#".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?></option>
                                            <?php } while ($row_tables_edit = mysqli_fetch_assoc($tables_edit)); ?>
                                        </select>
                                        <?php } else { ?>
                                        <ul class="list-unstyled">
											<li>Disabled... Queued judging selected</li>
                                    	</ul>
                                    	<?php } ?>
                                	</div>
                                </div><!-- ./row -->
							</div>
						</div>
					</div><!-- ./accordion -->
					
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion1" href="#collapseStep4">Step 4: <?php echo "Assign ".$assign_to." to Rounds"; ?><span class="fa fa-exchange pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep4" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<li>	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds"><?php echo "Assign ".$assign_to." to Rounds"; ?></a></li>
                                </ul>
							</div>
						</div>
					</div><!-- ./accordion -->
				</div>
				</div><!-- ./accordion left column -->
				
				<!-- Accordion Left Column -->
				<div class="col col-lg-6 col-md-12 col-sm-12 col-xs-12">
				<div class="panel-group" id="accordion2">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapseStep5">Step 5: Assign Judges and Stewards to Tables<span class="fa fa-user pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep5" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<li>	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging_tables">Assign Judges and Stewards to Tables</a></li>
                                </ul>
							</div>
						</div>
					</div><!-- ./accordion -->
                    
                    <div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapseStep6">Step 6: Add or Update Scores<span class="fa fa-trophy pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep6" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores">All Scores</a></li>
                                </ul>
                            	<div class="row">
                                	<div class="col col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <select class="selectpicker" name="tables" id="tables" onchange="jumpMenu('self',this,0)" data-size="10" data-width="auto">
                                        <option value="" selected disabled>For Table...</option>
                                            <?php do { $score_count = table_count_total($row_tables_edit_2['id']); ?>
                                            <option value="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;action=<?php if ($score_count  > 0) echo "edit&amp;id=".$row_tables_edit_2['id']; else echo "add&amp;id=".$row_tables_edit_2['id']; ?>"><?php echo "#".$row_tables_edit_2['tableNumber'].": ".$row_tables_edit_2['tableName']; ?></option>
                                            <?php } while ($row_tables_edit_2 = mysqli_fetch_assoc($tables_edit_2)); ?>
                                        </select>
                                	</div>
                                </div><!-- ./row -->
							</div>
						</div>
					</div><!-- ./accordion -->
                    
                    <div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapseStep7">Step 7: Add or Update BOS Places<span class="fa fa-certificate pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep7" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<?php do { 
										if ($row_style_types['styleTypeBOS'] == "Y") { ?>
										<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos&amp;action=enter&amp;filter=<?php echo $row_style_types['id'] ?>">BOS Places - <?php echo $row_style_types['styleTypeName']; ?></a></li>
									<?php 
										}
									} while ($row_style_types = mysqli_fetch_assoc($style_types));
									?>
                                </ul>
							</div>
						</div>
					</div><!-- ./accordion -->
                    
				</div>
				</div><!-- ./accordion right column -->
				
			</div><!-- ./row -->
		
		<!-- End 2 Column Accordion -->
	</div><!-- ./left column -->
	
	<!-- Right Column -->
	<div class="sidebar col col-lg-3 col-md-4 col-sm-12 col-xs-12">
		<div class="panel panel-info">
          	<div class="panel-heading">
            	<h4 class="panel-title">Entries Assigned to Tables</h4>
          	</div>
          	<div class="panel-body">
            <?php if (($totalRows_judging > 0) && ($dbTable == "default") && ($action == "default")) { ?>
            	<?php do { ?>
            	<div class="bcoem-sidebar-panel">
                    <strong class="text-info"><?php echo $row_judging['judgingLocName']; ?></strong>
                    <span class="pull-right"><?php $loc_total = get_table_info(1,"count_total","default","default",$row_judging['id']); $all_loc_total[] = $loc_total; echo $loc_total; ?></span>
            	</div>
                <?php } while ($row_judging = mysqli_fetch_assoc($judging)); ?>
                <?php if ($totalRows_judging > 1) { ?>
                <div class="bcoem-sidebar-panel">
                	<strong class="text-info">All Sessions</strong>
                    <span class="pull-right"><?php echo array_sum($all_loc_total); ?> of <a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=entries" data-toggle="tooltip" data-placement="top" title="View all entries."><?php echo $row_entry_count['count']; ?></a></span>
                </div>
                <?php } ?>
            <?php } // end if (($totalRows_judging > 1) && ($dbTable == "default")); ?>
          	</div>
     	</div>        
    </div><!-- ./right sidebar -->
</div><!-- ./row -->
</div><!-- ./bcoem-admin-dashboard-accordion -->
<?php } ?>
<?php if ($action != "print") { ?>
	<?php if (($action == "default") && ($filter == "default") && ($dbTable == "default")) { ?>
    <div class="bcoem-admin-element hidden-print">
        <div class="btn-group" role="group" aria-label="compOrgModal">
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#compOrgModal">
               Competition Organization Info
            </button>
        </div>
        <?php if (((NHC) && ($prefix == "_final")) || (!NHC) && ($totalRows_style_type > 0)) { ?>
        <div class="btn-group" role="group" aria-label="BOSModal">
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#BOSModal">
               Best of Show Settings Info
            </button>
        </div>
		<!-- Modal -->
        <div class="modal fade" id="compOrgModal" tabindex="-1" role="dialog" aria-labelledby="compOrgModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bcoem-admin-modal">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="compOrgModalLabel">Competition Organization Info</h4>
                    </div>
                    <div class="modal-body">
                        <p><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences">Competition organization preferences</a> are set to:</p>
                        <ul>
                            <li><?php if ($_SESSION['jPrefsQueued'] == "Y") echo "Queued Judging (no flights)."; else echo "Non-Queued Judging (with flights)."; ?></li>
                            <li>Maximum Rounds <?php if ($totalRows_judging > 0) echo "(per location)"; ?>: <?php echo $_SESSION['jPrefsRounds']; ?>.</li>
                            <li>Maximum BOS Places (per style type): <?php echo $_SESSION['jPrefsMaxBOS']; ?>.</li> 
                            <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
                            <li>Maximum Entries per Flight: <?php echo $_SESSION['jPrefsFlightEntries']; ?>.</li> 
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences" class="btn btn-primary">Update Preferences</a> 
                    	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div><!-- ./modal -->
		<!-- Modal -->
        <div class="modal fade" id="BOSModal" tabindex="-1" role="dialog" aria-labelledby="BOSModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bcoem-admin-modal">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="entryFormModalLabel">Best of Show Settings Info</h4>
                    </div>
                    <div class="modal-body">
                        <p>A Best of Show round is enabled for the following <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">Style Types</a>:</p>
                    <ul>
                        <?php do { ?>
                        <li><?php echo $row_style_type['styleTypeName']." (".bos_method($row_style_type['styleTypeBOSMethod'])." from each table to BOS)."; ?></li>
                        <?php } while ($row_style_type = mysqli_fetch_assoc($style_type)); ?>
                    </ul>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types" class="btn btn-primary">Update BOS Settings</a>
                    	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div><!-- ./modal -->
    <?php } ?>
	<?php 
	$orphan_modal_body = "";
	$orphan_modal_body_2 = "";
	if ($totalRows_tables > 0) {
		
		do { 
		
		$a[] = 0;
			if (get_table_info($row_styles['brewStyleNum']."^".$row_styles['brewStyleGroup'],"count","",$dbTable,"default")) { 
				if (!get_table_info($row_styles['id'],"styles",$id,$dbTable,"default")) { 
					$a[] = $row_styles['id'];
					$orphan_modal_body_2 .= "<li>".$row_styles['brewStyleGroup'].$row_styles['brewStyleNum']." ".style_convert($row_styles['brewStyleGroup'],"1").": ".$row_styles['brewStyle']." (".get_table_info($row_styles['brewStyleNum']."^".$row_styles['brewStyleGroup'],"count","default",$dbTable,"default")." entries)</li>";  
				}
			} 
		} while ($row_styles = mysqli_fetch_assoc($styles));
		$b = array_sum($a);
		if ($b == 0) $orphan_modal_body .= "<p>All style sub-categories with entries have been assigned to tables.</p>";
		else $orphan_modal_body .= "<p>The following sub-categories with entries have not been assigned to tables:</p>";
	
	} // end if ($totalRows_tables > 0)
	
	else {
		$orphan_modal_body .= "<p>No tables have been defined.";
		if ($go == "judging_tables") $orphan_modal_body .= " <a href='index.php?section=admin&amp;go=judging_tables&amp;action=add'>Add a table?</a>";
		$orphan_modal_body .= "</p>";
	} // end else
?>
<!-- Orphan Styles Modal -->
<div class="modal fade" id="orphanModal" tabindex="-1" role="dialog" aria-labelledby="orphanModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="orphanModalLabel">Style Sub-Categories with Entries Not Assigned to Tables</h4>
            </div>
            <div class="modal-body">
                <?php
				echo $orphan_modal_body;
				if (!empty($orphan_modal_body_2)) echo "<ul>".$orphan_modal_body_2."</ul>"; 
				?>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
    <div class="btn-group" role="group" aria-label="orphanModal">
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#orphanModal">
               Style Sub-Categories Not Assigned to Tables
            </button>
        </div>
	<div class="btn-group" role="group" aria-label="availJudgeModal">
		<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#availJudgeModal">
		   Judges Not Assigned to Tables
		</button>
	</div>
	<div class="btn-group" role="group" aria-label="availStewardModal">
		<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#availStewardModal">
		   Stewards Not Assigned to Tables
		</button>
	</div>
</div>
    <?php } // end if (($action == "default") && ($filter == "default") && ($dbTable == "default")); ?>
<?php } // end if ($action != "print"); ?>
<?php 
if ((($action == "default") && ($filter == "default")) || ($action == "print")) { 
if ($totalRows_tables > 0) { ?>


<!--
<script>
$(document).ready(function() {
    $('#tables').DataTable( {
        "order": [[ 0, "asc" ]]
    } );
} );
</script>
-->

<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#judgingTables').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[0,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			{ "asSorting": [  ] }				
			]
		} );
	} );
</script>

<table class="table table-responsive table-bordered table-striped" id="judgingTables"> 
	<thead>
    <tr>
    	<th class="hidden-xs hidden-sm">No.</th>
        <th>Name</th>
        <th>Style(s)</th>
        <th><em>Rec'd</em> Entries</th>
        <th class="hidden-xs hidden-sm"><em>Scored</em> Entries</th>
        <th>Judges</th>
        <th>Stew<span class="hidden-xs">ards</span></th>
        <?php if (($totalRows_judging > 1) && ($dbTable == "default"))  { ?>
        <th class="hidden-xs hidden-sm">Location</th>
        <?php } ?>
        <?php if (($action != "print") && ($dbTable == "default")) { ?>
        <th class="hidden-print">Actions</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php do { 
		$a = array(get_table_info(1,"list",$row_tables['id'],$dbTable,"default")); 
		$styles = display_array_content($a,1);
		$received = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
		$scored =  get_table_info(1,"score_total",$row_tables['id'],$dbTable,"default");
		if (($received > $scored) && ($dbTable == "default")) $scored = "<a class=\"hidden-print\" href=\"".$base_url."index.php?section=admin&amp;go=judging_scores&amp;action=edit&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Not all scores have been entered for this table. Click to add/edit scores.\"><span class=\"fa fa-lg fa-exlamation-circle text-danger\"></span></a> ".$scored.""; else $scored = $scored;
		$assigned_judges = assigned_judges($row_tables['id'],$dbTable,$judging_assignments_db_table);
		$assigned_stewards = assigned_stewards($row_tables['id'],$dbTable,$judging_assignments_db_table);
		/* $output_at_table_modals .= "
		
		<!-- At Table Judges Modal -->
<!-- Modal -->
<div class=\"modal fade\" id=\"atTableModal".$row_tables['tableNumber']."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"atTableModalLabel".$row_tables['tableNumber']."\">
    <div class=\"modal-dialog\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header bcoem-admin-modal\">
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
                <h4 class=\"modal-title\" id=\"atTableModalLabel".$row_tables['tableNumber']."\"><span class=\"text-capitalize\">Judges and Stewards Assigned to Table ".$row_tables['tableNumber']." ".$row_tables['tableName']."</h4>
            </div>
            <div class=\"modal-body\">
            	<p>There are ".$ranked." ranked judges and ".$nonranked." non-ranked judges at this table.</p>
                <p>The following have been assigned to this table.</p>
            	<table class=\"table table-responsive table-striped table-bordered table-condensed\" id=\"sortable".$row_tables['tableNumber']."\">
                <thead>
                    <th>Name</th>
                    <th>Rank</th>
					<th>Assignment</th>
                </thead>
                <tbody>
                    
                </tbody>
                </table>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
		
		";
		*/
	?>
    <tr>
    	<td class="hidden-xs hidden-sm"><?php echo $row_tables['tableNumber']; ?></td>
        <td><?php echo $row_tables['tableName']; ?></td>
        <td><?php echo $styles; ?></td>
        <td><?php echo $received; ?></td>
        <td class="hidden-xs hidden-sm"><?php echo $scored; ?></td>
        <td><?php echo $assigned_judges; ?></td>
        <td><?php echo $assigned_stewards; ?></td>
		<?php if (($totalRows_judging > 1) && ($dbTable == "default")) { ?>
        <td class="hidden-xs hidden-sm"><?php echo table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default") ?></td>
        <?php } ?>
        <?php if (($action != "print") && ($dbTable == "default")) { ?>
        <td nowrap="nowrap" class="hidden-print">
            <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?>"><span class="fa fa-lg fa-pencil"></span></a> 
            <a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;id=<?php echo $row_tables['id']; ?>" data-toggle="tooltip" data-placement="top" title="Print the pullsheet for Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?>"><span class="fa fa-lg fa-print"></span></a> 
            <?php if (($_SESSION['jPrefsQueued'] == "N") && (flight_count($row_tables['id'],1))) { ?>
            <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights&amp;filter=define&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>" data-toggle="tooltip" data-placement="top" title="Add/edit flights for Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?>"><span class="fa fa-lg fa-send"></span></a>
            <?php } ?>
            <?php if (score_count($row_tables['id'],1)) $scoreAction = "edit"; else $scoreAction = "add"; ?>
            <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;action=<?php echo $scoreAction; ?>&amp;id=<?php echo $row_tables['id']; ?>" data-toggle="tooltip" data-placement="top" title="Add/edit scores for Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?>"><span class="fa fa-lg fa-trophy"></span></a>   
            <a href="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $filter; ?>&amp;dbTable=<?php echo $judging_tables_db_table; ?>&amp;go=judging_tables&amp;action=delete&amp;id=<?php echo $row_tables['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?>" data-confirm="Are you sure you want to delete Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?>? ALL associated FLIGHTS and SCORES will be deleted as well. This cannot be undone."><span class="fa fa-lg fa-trash-o"></span></a> 
        </td>
        <?php } ?>
    </tr>
    <?php } while ($row_tables = mysqli_fetch_assoc($tables)); ?>
    </tbody>
</table>

<?php } 
else echo "<p>No tables have been defined yet.</p><p><a class=\"btn btn-primary\" role=\"button\" href=\"".$base_url."index.php?section=admin&amp;go=judging_tables&amp;action=add\"><span class=\"fa fa-plus-circle\"></span> Add a table?</a></p>";
} // end if ($action == "default") ?>
<?php if ($action == "add") { 
$table_numbers = "";

do {
	$table_numbers[] = $row_table_number['tableNumber'];
} while($row_table_number = mysqli_fetch_assoc($table_number)); 


?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[1,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				null,
				null,
				null,
				null
				]
			} );
		} );
	</script>
<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_tables_db_table; ?>&amp;go=<?php echo $go; ?>" name="form1" id="form1">
<div class="bcoem-admin-element hidden-print">

    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="tableName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Name</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <input class="form-control" id="tableName" name="tableName" type="text" value="" data-error="A table name is required" placeholder="" autofocus required>
                <span class="input-group-addon" id="tableName-addon2"><span class="fa fa-star"></span></span>
            </div>
            <span class="help-block with-errors"></span>
        </div>
    </div><!-- ./Form Group -->
    
    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="tableNumber" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Number</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="tableNumber" id="tableNumber" data-size="10" data-width="auto">
            <?php for($i=1; $i<50+1; $i++) { ?>
    		<option value="<?php echo $i; ?>" <?php if ((isset($table_numbers)) && (in_array($i,$table_numbers))) echo "DISABLED";  if (($row_table_number_last['tableNumber'] + 1) == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
        	<?php } ?>
        </select>
        </div>
    </div><!-- ./Form Group -->
    
    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="tableLocation" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Location</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="tableLocation" id="tableLocation" data-size="10" data-width="auto">
            <?php do { ?>
          	<option value="<?php echo $row_judging1['id']; ?>" <?php if ($row_tables_edit['tableLocation'] == $row_judging1['id']) echo "selected"; ?>><?php echo $row_judging1['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging1['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt").")"; ?></option>
          	<?php } while ($row_judging1 = mysqli_fetch_assoc($judging1)) ?>
        </select>
        </div>
    </div><!-- ./Form Group -->
    
    <div class="form-group">
        <label for="tableStyles" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Available Sub-style(s)</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <?php 
		if ($row_entry_count['count'] > 0) { ?>
			<table class="table table-responsive table-striped table-bordered small" id="sortable">
				<thead>
				<tr>
					<th width="1%">&nbsp;</th>
					<th width="1%">#</th>
					<th>Style</th>
					<th>Sub-Style</th>
					<th width="20%"><em>Received</em> Entries</th>
				</tr>
				</thead>
				<tbody>
				<?php do { ?>
				<?php 
				$received_entry_count_style = get_table_info($row_styles['brewStyleNum']."^".$row_styles['brewStyleGroup'],"count","default",$dbTable,"default");
				if ($received_entry_count_style > 0) { 
				//if (in_array($row_styles['brewStyle'],$with_received_entries)) {
				if (!get_table_info($row_styles['id'],"styles","default",$dbTable,"default")) {
				?>
				<tr>
					<td><input type="checkbox" name="tableStyles[]" value="<?php echo $row_styles['id']; ?>"></td>
					<td><?php echo $row_styles['brewStyleGroup'].$row_styles['brewStyleNum']; ?></td>
					<td><?php echo style_convert($row_styles['brewStyleGroup'],"1"); ?>
					<td><?php echo $row_styles['brewStyle']; //.get_table_info($row_styles['id'],"assigned","default",$dbTable,"default"); ?></td>
					<td><?php echo $received_entry_count_style; ?></td>
				</tr>
				<?php } } ?>
				<?php } while ($row_styles = mysqli_fetch_assoc($styles)); ?>
				</tbody>
			</table>
		<?php } else echo "There are no available sub-styles."; ?>
        </div>
    </div><!-- ./Form Group -->
    
</div><!-- ./bcoem-admin-element -->
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" class="btn btn-primary" value="Add Table">
		</div>
	</div>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=judging_tables","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php } // end if ($action == "add") ?>




<?php if ($action == "edit") { ?>
<!-- Edit a Table -->
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[1,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				null,
				null,
				null,
				null
				]
			} );
		} );
	</script>
<form class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_tables_db_table; ?>&amp;go=<?php echo $go."&amp;id=".$row_tables_edit['id']; ?>" name="form1" id="form1" onSubmit="return CheckRequiredFields()">

<div class="bcoem-admin-element hidden-print">

	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="tableName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Name</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <input class="form-control" id="tableName" name="tableName" type="text" value="<?php echo $row_tables_edit['tableName']; ?>" placeholder="" autofocus>
                <span class="input-group-addon" id="tableName-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->
    
    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="tableNumber" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Number</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="tableNumber" id="tableNumber" data-size="10" data-width="auto">
            <?php for($i=1; $i<150+1; $i++) { ?>
    		<option value="<?php echo $i; ?>" <?php if ($row_tables_edit['tableNumber'] == $i) echo "selected"; elseif ((isset($a)) && (in_array($i,$a))) echo "DISABLED"; ?>><?php echo $i; ?></option>
        	<?php } ?>
        </select>
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="tableLocation" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Location</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="tableLocation" id="tableLocation" data-size="10" data-width="auto">
            <?php do { ?>
          		<option value="<?php echo $row_judging1['id']; ?>" <?php if ($row_tables_edit['tableLocation'] == $row_judging1['id']) echo "selected"; ?>><?php echo $row_judging1['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging1['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt").")"; ?></option>
          <?php } while ($row_judging1 = mysqli_fetch_assoc($judging1)) ?>
        </select>
        </div>
    </div><!-- ./Form Group -->
    
    
    <div class="form-group">
        <label for="tableStyles" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Sub-style(s)</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <?php 
		if ($row_entry_count['count'] > 0) { ?>
			<table class="table table-responsive table-bordered small" id="sortable">
				<thead>
				<tr>
					<th width="1%">&nbsp;</th>
					<th width="1%">#</th>
					<th>BJCP Style</th>
					<th>Sub-Style</th>
					<th><em>Received</em> Entries</th>
				</tr>
				</thead>
				<tbody>
				<?php do { 
				
				$style_assigned_this = get_table_info($row_styles['id'],"styles",$row_tables_edit['id'],$dbTable,"default");
				$style_assigned_other = get_table_info($row_styles['id'],"styles","default",$dbTable,"default");
				$style_assigned_location = get_table_info($row_styles['id'],"assigned","default",$dbTable,"default");
				
				$table_row_class = "bg-success";
				if (!empty($style_assigned_location)) $table_row_class = "bg-info"; 
				if ($style_assigned_this) $table_row_class = "bg-warning";
				
				?>
					<?php if (get_table_info($row_styles['brewStyleNum']."^".$row_styles['brewStyleGroup'],"count","",$dbTable,"default") > 0) { ?>
                    <tr class="<?php echo $table_row_class; ?>">
                        <td><input type="checkbox" name="tableStyles[]" value="<?php echo $row_styles['id']; ?>" <?php if ($style_assigned_this) echo " checked"; elseif ($style_assigned_other) echo "disabled"; ?>></td>
                        <td><?php echo $row_styles['brewStyleGroup'].$row_styles['brewStyleNum']; ?></td>
                        <td><?php echo style_convert($row_styles['brewStyleGroup'],"1"); ?></td>
                        <td><?php echo $row_styles['brewStyle'].$style_assigned_location; ?></td>
                        <td><span class="pull-right"><?php echo get_table_info($row_styles['brewStyleNum']."^".$row_styles['brewStyleGroup'],"count","default",$dbTable,"default"); ?></span></td>
                    </tr>
                    <?php } ?>
                    <?php } while ($row_styles = mysqli_fetch_assoc($styles)); ?>
				</tbody>
			</table>
		<?php } else echo "There are no available sub-styles."; ?>
        </div>
    </div><!-- ./Form Group -->


</div>
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" class="btn btn-primary" value="Edit Table">
		</div>
	</div>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=judging_tables","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php 
if ($already_scored) {
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('input[type="checkbox"]').click(function(){
            if($(this).is(":checked")){
                confirm("Are you sure you want to change this table\'s styles? All scores entered for the table will be deleted if you add this style.");
            }
            else if($(this).is(":not(:checked)")){
                confirm("Are you sure you want to change this table\'s styles? All scores entered for the table will be deleted if you remove this style.");
            }
        });
    });
</script>
<?php } 
} // end if ($action == "edit") ?>
<?php if (($action == "assign") && ($filter == "default")) { ?>
<div class="form-horizontal">
    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="assign_judges" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Assign Judges To</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="assign_judges" id="assign_judges" onchange="jumpMenu('self',this,0)" data-live-search="true" data-size="10" data-width="auto">
            <option value="" disabled selected>Choose Below...</option>
            <?php do { ?>
            <option value="index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=judges&amp;id=<?php echo $row_tables['id']; ?>"><?php echo "Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></option>
            <?php } while ($row_tables = mysqli_fetch_assoc($tables)); ?>
       </select>
        </div>
    </div><!-- ./Form Group -->
    
    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="assign_judges" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Assign Stewards To</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="assign_judges" id="assign_judges" onchange="jumpMenu('self',this,0)" data-live-search="true" data-size="10" data-width="auto">
            <option value="" disabled selected>Choose Below...</option>
            <?php do { ?>
            <option value="index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=stewards&amp;id=<?php echo $row_tables_edit['id']; ?>"><?php echo "Table ".$row_tables_edit['tableNumber']." ".$row_tables_edit['tableName']; ?></option>
            <?php } while ($row_tables_edit = mysqli_fetch_assoc($tables_edit)); ?>
       </select>
        </div>
    </div><!-- ./Form Group -->
</div>
<?php } ?>
<?php if (($action == "assign") && ($filter != "default") && ($id != "default")) include ('judging_assign.admin.php'); ?>