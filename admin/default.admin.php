<?php
/**
 * Module:      default.admin.php
 * Description: This module houses links to all administration functions.
 *
 */

?>
<p class="lead">Hello, <?php echo $_SESSION['brewerFirstName']; ?>. <span class="small">Click or tap the headings or icons below to view the options available in each category.</span></p>

<?php if ((judging_date_return() == 0) && ($_SESSION['userLevel'] == 0))  { ?>
    <div class="row">
        <div class="col col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="bcoem-admin-element">
                <a class="btn btn-primary btn-block hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=publish" data-confirm="Are you sure? This will immediately publish any and all results that have been entered into the database. Results will be displayed on the home page.">Publish Results Now&nbsp;&nbsp;<span class="fa fa-bullhorn"></span></a>
            </div>
        </div>
        <div class="col col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="bcoem-admin-element">
                <a class="btn btn-info btn-block hide-loader" href="http://brewcompetition.com/reset-comp" target="_blank">Reset Competition Information&nbsp;&nbsp;<span class="fa fa-info-circle"></span></a>
            </div>
        </div>
    </div>
<?php if (($row_limits['prefsShowBestBrewer'] != 0) || ($row_limits['prefsShowBestClub'] != 0)) { ?>
<div class="bcoem-admin-element">
    <div class="row">
        <div class="col col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="bcoem-admin-element">
                    <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#previewBest">Preview Best Brewer/Best Club Results <span class="fa fa-trophy"></span>
            </button>
            </div>
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
    </div>
</div>
<?php } ?>
<?php } ?>
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
                                <div class="col col-lg-5 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Competition Info</strong>
                                </div>
                                <div class="col col-lg-7 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contest_info&amp;action=edit">Edit</a></li>
										<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload&amp;action=html" data-toggle="tooltip" data-placement="top" title="Upload your logo before editing your Competition Information">Upload Logo</a></li>
									</ul>
                                </div>
                            </div><!-- ./row -->

                            <div class="row">
                                <div class="col col-lg-5 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Contacts</strong>
                                </div>
                                <div class="col col-lg-7 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts&amp;action=add">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->

                            <div class="row">
                                <div class="col col-lg-5 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Custom Categories</strong>
                                </div>
                                <div class="col col-lg-7 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best&amp;action=add">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->

                            <div class="row">
                                <div class="col col-lg-5 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Drop-Off Locations</strong>
                                </div>
                                <div class="col col-lg-7 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff&amp;action=add">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->

                            <div class="row">
                                <div class="col col-lg-5 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Judging Sessions</strong>
                                </div>
                                <div class="col col-lg-7 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=add">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->

                            <?php if ($_SESSION['userLevel'] == "0") { ?>
                            <div class="row">
                                <div class="col col-lg-5 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Sponsors</strong>
                                </div>
                                <div class="col col-lg-7 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors&amp;action=add">Add</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload" data-toggle="tooltip" data-placement="top" title="Upload sponsor logo images BEFORE adding sponsor information">Upload Logos</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->

                            <div class="row">
                                <div class="col col-lg-5 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Styles Accepted</strong>
                                </div>
                                <div class="col col-lg-7 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles&amp;action=add">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->

                            <div class="row">
                                <div class="col col-lg-5 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Style Types</strong>
                                </div>
                                <div class="col col-lg-7 col-md-8 col-sm-8 col-xs-8">
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
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Entries</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Manage</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->

                            <?php if ($_SESSION['prefsPaypalIPN'] == 1) { ?>

                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Payments</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=payments">Manage</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->

                            <?php } ?>

                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Participants</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Register</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
                                <div class="col col-lg-4 col-md-8 col-sm-4 col-xs-4">
                                    <strong>Regenerate</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a class="hide-loader" data-confirm="Are you sure you want to regenerate judging numbers for all entries? This will over-write all judging numbers, including those that have been assigned via the barcode or QR Code scanning function. The process may take a while depending upon the number of entires in your database." href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=default&amp;action=generate_judging_numbers&amp;sort=default">Judging Numbers (Random)</a></li>
                                        <li><a class="hide-loader" data-confirm="Are you sure you want to regenerate judging numbers for all entries? This will over-write all judging numbers, including those that have been assigned via the barcode or QR Code scanning function. The process may take a while depending upon the number of entires in your database. PLEASE NOTE that judging numbers will be in the following format: XX-123 (where XX is the category number or name)." href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=default&amp;action=generate_judging_numbers&amp;sort=legacy">Judging Numbers (With Style Number Prefix)</a></li>
                                        <li><a class="hide-loader" data-confirm="Are you sure you want to regenerate judging numbers for all entries? This will over-write all judging numbers, including those that have been assigned via the barcode or QR Code scanning function. The process may take a while depending upon the number of entires in your database." href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=generate_judging_numbers&amp;sort=identical">Judging Numbers (Same as Entry Numbers)</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php if (in_array($_SESSION['prefsEntryForm'],$barcode_qrcode_array)) { ?>
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Using Barcodes/QR Codes?</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a class="hide-loader" href="http://brewcompetition.com/barcode-labels" target="_blank">Download Barcode and Round Judging Number Labels <span class="fa fa-external-link"></span></a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php } ?>
							<div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Entry Check-In</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Sorting Sheets</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=sorting&amp;go=default&amp;filter=default&amp;view=entry">Entry Numbers</a></li>
                                        <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=sorting&amp;go=default&amp;filter=default">Judging Numbers</a></li>
                                        <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=sorting&amp;go=cheat&amp;filter=default">Cheat Sheets</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                                <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h5>Print Bottle Labels (PDF)<hr></h5>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <a class="hide-loader" href="http://www.avery.com/avery/en_us/Products/Labels/Addressing-Labels/Easy-Peel-White-Address-Labels_05160.htm" target="_blank" data-toggle="tooltip" data-placement="right" title="Avery 5160">Letter</a>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a data-toggle="tooltip" title="6 entry numbers per label" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;psort=5160">Entry Numbers</a></li>
                                        <li><a data-toggle="tooltip" title="6 judging numbers per label" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;psort=5160">Judging Numbers</a></li>
                                    </ul>
									<ul class="list-unstyled">
										<li>With Required Info - All Styles (Entry Numbers)
										    <div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu1">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;view=all&amp;psort=5160&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
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
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;view=special&amp;psort=5160&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
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
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=all&amp;&amp;psort=5160&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
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
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=special&amp;&amp;psort=5160&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
										</li>
									</ul>
                                </div>
                             </div><!-- ./row -->
                             <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <a class="hide-loader" href="http://www.avery.se/avery/en_se/Products/Labels/Multipurpose/White-Multipurpose-Labels-Permanent/General-Usage-Labels-White_3422.htm" target="_blank" data-toggle="tooltip" data-placement="right" title="Avery 3422">A4</a>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a data-toggle="tooltip" title="6 entry numbers per label" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;psort=3422">Entry Numbers</a></li>
                                        <li><a data-toggle="tooltip" title="6 judging numbers per label" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;psort=3422">Judging Numbers</a></li>
                                    </ul>
                                    <ul class="list-unstyled">
										<li>With Required Info - All Styles (Entry Numbers)
										    <div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu1">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;view=all&amp;psort=3422&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
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
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;view=special&amp;psort=3422&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
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
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=all&amp;&amp;psort=3422&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
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
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=special&amp;&amp;psort=3422&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
										</li>
									</ul>
                                </div>
                             </div><!-- ./row -->
                             <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <a class="hide-loader" href="http://www.onlinelabels.com/Products/OL32.htm" target="_blank" data-toggle="tooltip" data-placement="right" title="Online Lables OL32">0.50 in/13 mm Round</a>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-unstyled">
										<li>Entry Numbers
											<div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu5">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></a></li>
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
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></a></li>
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
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-category-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></a></li>
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
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=recent&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></a></li>
													<?php } ?>
												</ul>
											</div>
                                        </li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->

                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <a class="hide-loader" href="http://www.onlinelabels.com/Products/OL5275WR.htm" target="_blank" data-toggle="tooltip" data-placement="right" title="Online Lables OL5275WR">0.75 in/19 mm Round</a>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-unstyled">
                                        <li>Entry Numbers
											<div class="dropdown bcoem-admin-dashboard-select">
												<button class="btn btn-default dropdown-toggle" type="button" id="sortingMenu9" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Number of Labels per Entry <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="sortingMenu9">
													<?php for($i=1; $i<=6; $i++) { ?>
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></a></li>
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
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></a></li>
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
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-category-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></a></li>
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
													<li class="small"><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=recent&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></a></li>
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
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Assign</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Judges</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Stewards</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Staff</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Tables</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Flights</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights">Manage</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php } ?>
                            <?php if ($totalRows_tables > 1) { ?>
							<div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>BOS Judges</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos">Add</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
							<?php } ?>
                            <!-- Staff, Judge, Steward Emails -->
                            <!--
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Emails</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a data-tooltip="true" title="Send a custom email message to judges" href="#" data-toggle="modal" data-target="#CustomMessage">Custom Message to Judges and/or Stewards</a><li>
                                        <?php if ($totalRows_tables > 1) { ?>
                                        <li><a class="hide-loader" data-confirm="Are you sure you want to send an email to each judge and steward assigned to a table? The email will detail their table assignment(s) and associated judging date(s), location(s), and time(s)." href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=default&amp;action=email&amp;filter=table-assignments">Judge and Steward Table Assignments</a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            -->
                            <!-- Send Message Modal -->
                            <!--
                            <div class="modal fade" id="CustomMessage" tabindex="-1" role="dialog" aria-labelledby="CustomMessageLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <form data-toggle="validator" role="form" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=default&amp;action=email&amp;filter=custom-message" name="form2">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="CustomMessageLabel">Compose a Custom Message</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="emailSubject">Subject</label>
                                                <div class="input-group has-warning">
                                                    <input class="form-control" id="emailSubject" name="emailSubject" type="text" maxlength="255" value="" placeholder="Add an email subject line" data-error="The email subject line is required." autofocus required>
                                                    <span class="input-group-addon" id="emailSubject-addon2"><span class="fa fa-star"></span></span>
                                                </div>
                                            <div class="help-block with-errors"></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="emailMessage">Message</label>
                                                <textarea style="height: 200px;" class="form-control" id="emailMessage" name="emailMessage" data-error="Please provide a message body" rows="15" required>
                                                    <p>[Compose your message here...]</p>
                                                    <p>Cheers,</p>
                                                    <p><?php echo $_SESSION['brewerFirstName']." ".$_SESSION['brewerLastName']; ?><br><a href="mailto:<?php echo $_SESSION['brewerEmail']; ?>"><?php echo $_SESSION['brewerEmail']; ?></a><br>[Your title]<br><?php echo $_SESSION['contestName']; ?></p>
                                                </textarea>
                                                <div class="help-block">Each email will be customized with the name and the role of the user as a greeting.</div>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="messageAudience">Audience</label>
                                                <div class="input-group">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="messageAudience" value="0" id="messageAudience_0" checked /> Judges
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="messageAudience" value="1" id="messageAudience_1" /> Stewards
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="messageAudience" value="2" id="messageAudience_2" /> Judges and Stewards
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="messageAudience" value="3" id="messageAudience_3" /> Staff
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="messageAudience" value="4" id="messageAudience_4" /> Judges, Stewards, and Staff
                                                    </label>
                                                </div>
                                            </div>
                                            <input type="hidden" name="relocate" value="<?php echo $base_url."index.php?section=admin"; ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <input name="submit" type="submit" class="btn btn-primary" value="Send Message">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            -->
                        </div>
                    </div>
                </div><!-- ./ Organizing Panel -->

                <!-- Scoring Panel -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseScoring">Scoring<span class="fa fa-trophy pull-right"></span></a>
                        </h4>
                    </div>
                    <div id="collapseScoring" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="row">
                                <div class="col col-lg-5 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Scoresheets and Docs</strong>
                                </div>
                                <div class="col col-lg-7 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-inline">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload_scoresheets" data-toggle="tooltip" data-placement="top" title="Upload scoresheets for judged entries">Upload Multiple</a></li>
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload_scoresheets&amp;action=html" data-toggle="tooltip" data-placement="top" title="Upload scoresheets for judged entries">Upload Individually</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->


                            <div class="row">
                                <div class="col col-lg-5 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Scores</strong>
                                </div>
                                <div class="col col-lg-7 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-unstyled">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores">Manage</a></li>
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
                                <div class="col col-lg-5 col-md-4 col-sm-4 col-xs-4">
                                    <strong>BOS Entries and Places</strong>
                                </div>
                                <div class="col col-lg-7 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-unstyled">
                                        <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos">Manage</a></li>
                                    </ul>
                                </div>
                            </div><!-- ./row -->
                            <?php if ($_SESSION['userLevel'] == "0") { ?>
                            <div class="row">
                                <div class="col col-lg-5 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Custom Categories</strong>
                                </div>
                                <div class="col col-lg-7 col-md-8 col-sm-8 col-xs-8">
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
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>Notes</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<ul class="list-inline">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=notes&amp;go=org_notes" data-toggle="tooltip" data-placement="top" title="A List of Notes Individual Judges Have Provided to the Organizer - Includes Reports of Allergies">Notes to Organizer</a></li>
                                </ul>
							</div>
						</div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <strong>Allergens</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <ul class="list-inline">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=notes&amp;go=allergens" data-toggle="tooltip" data-placement="top" title="A List of Entries with Allergen Information">Possible Allergens in Entries</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>Drop-Off</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<ul class="list-inline">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=dropoff" data-toggle="tooltip" data-placement="top" title="Print Entry Totals for Each Drop-Off Location">Entry Totals</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=dropoff&amp;go=check" data-toggle="tooltip" data-placement="top" title="Print Entries By Drop-Off Location">List of Entries</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
						<?php if ($totalRows_tables > 0) { ?>
						<div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <strong><?php echo $label_additional_info; ?></strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>Pullsheets</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
                                        <?php echo table_choose("pullsheets","judging_tables",$action,"mini_bos","entry","output/print.output.php","thickbox"); ?>
									</ul>
								</div>
								<div class="dropdown bcoem-admin-dashboard-select">
									<button class="btn btn-default dropdown-toggle" type="button" id="pullsheetMenu4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Judging Numbers for Table... <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" aria-labelledby="pullsheetnMenu4">
										<?php echo table_choose("pullsheets","judging_tables",$action,$filter,$view,"output/print.output.php","thickbox"); ?>
                                        <?php echo table_choose("pullsheets","judging_tables",$action,"mini_bos",$view,"output/print.output.php","thickbox"); ?>
									</ul>
								</div>
								<div class="dropdown bcoem-admin-dashboard-select">
									<button class="btn btn-default dropdown-toggle" type="button" id="pullsheetMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Entry Numbers for Location... <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
										<?php
										do {
											for ($round=1; $round <= $row_judging['judgingRounds']; $round++) {
										 $location_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt");
										 ?>
										<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_locations&amp;view=entry&amp;location=<?php echo $row_judging['id']?>&amp;round=<?php echo $round; ?>" data-toggle="tooltip" data-placement="top" title="Print Pullsheet for Location <?php echo $row_judging['judgingLocName'] . " - " . $location_date. ", Round " . $round; ?>"><?php echo $row_judging['judgingLocName'] . " - " . $location_date. ", Round " . $round; ?></a>
                                        <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_locations&amp;view=entry&amp;filter=mini_bos&amp;location=<?php echo $row_judging['id']?>&amp;round=<?php echo $round; ?>" data-toggle="tooltip" data-placement="top" title="Print Pullsheet for Location <?php echo $row_judging['judgingLocName'] . " - " . $location_date. ", Round " . $round; ?>"><?php echo $row_judging['judgingLocName'] . " - " . $location_date. ", Round " . $round . " (Mini-BOS)"; ?></a>
										<?php }
										}
										while ($row_judging = mysqli_fetch_assoc($judging));
										?>
									</ul>
								</div>
								<div class="dropdown bcoem-admin-dashboard-select">
									<button class="btn btn-default dropdown-toggle" type="button" id="pullsheetMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Judging Numbers for Location...<span class="caret"></span>
									</button>
									<ul class="dropdown-menu" aria-labelledby="pullsheetMenu2">
										<?php
										do {
											for ($round=1; $round <= $row_judging1['judgingRounds']; $round++) {
												$location_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging1['judgingDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt");
										 ?>
										<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_locations&amp;view=default&amp;location=<?php echo $row_judging1['id']?>&amp;round=<?php echo $round; ?>" data-toggle="tooltip" data-placement="top" title="Print Pullsheet for Location <?php echo $row_judging1['judgingLocName'] . " - " . $location_date. ", Round " . $round; ?>"><?php echo $row_judging1['judgingLocName'] . " - " . $location_date. ", Round " . $round; ?></a></li>
                                        <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_locations&amp;view=default&amp;filter=mini_bos&amp;location=<?php echo $row_judging1['id']?>&amp;round=<?php echo $round; ?>" data-toggle="tooltip" data-placement="top" title="Print Pullsheet for Location <?php echo $row_judging1['judgingLocName'] . " - " . $location_date. ", Round " . $round; ?>"><?php echo $row_judging1['judgingLocName'] . " - " . $location_date. ", Round " . $round . " (Mini-BOS)"; ?></a>
										<?php }
										} while ($row_judging1 = mysqli_fetch_assoc($judging1));
										?>
									</ul>
								</div>
							</div>
						</div><!-- ./row -->
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>Table Cards</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
									<button class="btn btn-default dropdown-toggle" type="button" id="cardsMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">For Location... <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" aria-labelledby="cardsMenu2">
										<?php
										do {
											for ($round=1; $round <= $row_judging2['judgingRounds']; $round++) {
												$location_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging2['judgingDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt");
										?>
											<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=table-cards&amp;go=judging_locations&amp;location=<?php echo $row_judging2['id']?>&amp;round=<?php echo $round; ?>" data-toggle="tooltip" data-placement="top" title="Print Table Cards for <?php echo $row_judging2['judgingLocName']. " - " . $location_date . ", Round " . $round; ?>"><?php echo $row_judging2['judgingLocName']. " - " . $location_date . ", Round " . $round; ?></a></li>
										<?php
											}
										} while ($row_judging2 = mysqli_fetch_assoc($judging2));
										?>

									</ul>
								</div>
							</div>
						</div><!-- ./row -->
                        <?php } ?>
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>Sign In Sheets</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<ul class="list-inline">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=sign-in" data-toggle="tooltip" data-placement="top" title="Print a Judge Sign-in Sheet">Judges</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=sign-in" data-toggle="tooltip" data-placement="top" title="Print a Steward Sign-in Sheet">Stewards</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
                        <?php if ($totalRows_tables > 0) { ?>
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>Assignments</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<ul class="list-unstyled">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=name" data-toggle="tooltip" data-placement="top" title="Print Judge Assignments by Name">Judges By Last Name</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=table" data-toggle="tooltip" data-placement="top" title="Print Judge Assignments by Table">Judges By Table</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=location" data-toggle="tooltip" data-placement="top" title="Print Judge Assignments by Location">Judges By Location</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=name" data-toggle="tooltip" data-placement="top" title="Print Steward Assignments by Name">Stewards Last Name</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=table" data-toggle="tooltip" data-placement="top" title="Print Steward Assignments by Table">Stewards By Table</a></li>
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=location" data-toggle="tooltip" data-placement="top" title="Print Steward Assignments by Location">Stewards By Location</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
                        <?php } ?>
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>Scoresheet Labels</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<ul class="list-inline">
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;psort=5160" data-toggle="tooltip" data-placement="top" title="Avery 5160">Letter</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;psort=3422" data-toggle="tooltip" data-placement="top" title="Avery 3422">A4</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>Name Tags</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<ul class="list-inline">
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=participants&amp;action=judging_nametags&amp;psort=5395" data-toggle="tooltip" data-placement="top" title="Avery 5395">Letter</a></li>
									<!-- <li><a href="#" target="_blank" data-toggle="tooltip" data-placement="top" title="Avery XXXX">A4</a></li> -->
								</ul>
							</div>
						</div><!-- ./row -->
                        <?php if ($totalRows_tables > 0) { ?>
						<div class="row">
							<div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h5>During Judging<hr></h5>
							</div>
						</div><!-- ./row -->
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>BOS Pullsheets</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<ul class="list-unstyled">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_scores_bos&amp;view=entry" data-toggle="tooltip" data-placement="top" title="Print All BOS Pullsheets Using Entry Numbers">All Style Types - Entry Numbers</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_scores_bos" data-toggle="tooltip" data-placement="top" title="Print All BOS Pullsheets Using Judging Numbers">All Style Types - Judging Numbers</a></li>
									<?php do {
										if ($row_style_type['styleTypeBOS'] == "Y") {
										?>

										<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_scores_bos&amp;view=entry&amp;id=<?php echo $row_style_type['id']; ?>"  data-toggle="tooltip" data-placement="top" title="Print the <?php echo $row_style_type['styleTypeName']; ?> BOS Pullsheet Using Entry Numbers"><?php echo $row_style_type['styleTypeName']; ?> - Entry Numbers</a></li>
                                        <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_scores_bos&amp;id=<?php echo $row_style_type['id']; ?>"  data-toggle="tooltip" data-placement="top" title="Print the <?php echo $row_style_type['styleTypeName']; ?> BOS Pullsheet Using Entry Numbers"><?php echo $row_style_type['styleTypeName']; ?> - Judging Numbers</a></li>

										<?php }
										} while ($row_style_type = mysqli_fetch_assoc($style_type));
									?>
							</div>
						</div><!-- ./row -->
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>BOS Cup Mats</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<ul class="list-unstyled">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=bos-mat&amp;filter=entry" data-toggle="tooltip" data-placement="top" title="Print all BOS Cup Mats with entry numbers only">All Style Types - Entry Numbers</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=bos-mat" data-toggle="tooltip" data-placement="top" title="Print all BOS Cup Mats with judging numbers only">All Style Types - Judging Numbers</a></li>

                                    <?php do {
                                        if ($row_style_types['styleTypeBOS'] == "Y") { ?>
                                        <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=bos-mat&amp;filter=entry&amp;view=<?php echo $row_style_types['id']; ?>" data-toggle="tooltip" data-placement="top" title="Print BOS Cup Mats with entry numbers only for <?php echo $row_style_types['styleTypeName'];?>"><?php echo $row_style_types['styleTypeName'];?> - Entry Numbers</a></li>
                                        <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=bos-mat&amp;view=<?php echo $row_style_types['id']; ?>" data-toggle="tooltip" data-placement="top" title="Print BOS Cup Mats with judging numbers only for <?php echo $row_style_types['styleTypeName'];?>"><?php echo $row_style_types['styleTypeName'];?> - Judging Numbers</a></li>
                                    <?php }
                                } while ($row_style_types = mysqli_fetch_assoc($style_types)); ?>
								</ul>
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
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>BOS Results</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<ul class="list-inline">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores_bos&amp;action=print&amp;filter=bos&amp;view=default" title="BOS Round(s) Results Report">Print</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=results&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=pdf">PDF</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=results&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=html">HTML</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
                        <?php if (($_SESSION['prefsShowBestBrewer'] != 0) || ($_SESSION['prefsShowBestClub'] != 0)) { ?>
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <strong>Best Brewer and/or Club</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <ul class="list-inline">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=best&amp;action=print&amp;filter=bos&amp;view=default" title="Best Brewer and/or Club Results Report">Print</a></li>
                                    <!--
                                    <li><a href="<?php echo $base_url; ?>output/export.output.php?section=results&amp;go=best&amp;action=download&amp;filter=default&amp;view=pdf">PDF</a></li>
                                    <li><a href="<?php echo $base_url; ?>output/export.output.php?section=results&amp;go=best&amp;action=download&amp;filter=default&amp;view=html">HTML</a></li>
                                    -->
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <?php } ?>
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <strong>Table Results (<?php echo $results_method[$_SESSION['prefsWinnerMethod']]; ?>)</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <ul class="list-inline">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores&amp;action=print&amp;filter=scores&amp;view=default" data-toggle="tooltip" data-placement="top" title="Print all entry results with scores listed">All with Scores</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores&amp;action=print&amp;filter=scores&amp;view=winners" data-toggle="tooltip" data-placement="top" title="Print winners only results with scores listed">Winners Only with Scores</a></li>
                                </ul>
                                <ul class="list-inline">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores&amp;action=print&amp;filter=none&amp;view=default" data-toggle="tooltip" data-placement="top" title="Print all entry results without scores listed">All without Scores</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=judging_scores&amp;action=print&amp;filter=none&amp;view=winners" data-toggle="tooltip" data-placement="top" title="Print winners only results without scores listed">Winners Only without Scores</a></li>
                                </ul>
                                <ul class="list-inline">
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=results&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=pdf" data-toggle="tooltip" data-placement="top" title="Download a PDF report of results - winners only without scores">PDF</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=results&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=html" data-toggle="tooltip" data-placement="top" title="Download a HTML report of results to copy/paste into another website - winners only without scores">HTML</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <strong>All Results (<?php echo $results_method[$_SESSION['prefsWinnerMethod']]; ?> - Single Report)</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <ul class="list-inline">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=all&amp;action=print&amp;filter=scores&amp;view=default" data-toggle="tooltip" data-placement="top" title="Print all entry results with scores listed">All with Scores</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=all&amp;action=print&amp;filter=scores&amp;view=winners" data-toggle="tooltip" data-placement="top" title="Print winners only results with scores listed">Winners Only with Scores</a></li>
                                </ul>
                                <ul class="list-inline">
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=all&amp;action=print&amp;filter=none&amp;view=default" data-toggle="tooltip" data-placement="top" title="Print all entry results without scores listed">All without Scores</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=all&amp;action=print&amp;filter=none&amp;view=winners" data-toggle="tooltip" data-placement="top" title="Print winners only results without scores listed">Winners Only without Scores</a></li>
                                </ul>
                            <!--
                                <ul class="list-inline">
                                    <li><a href="<?php echo $base_url; ?>output/export.output.php?section=results&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=pdf" data-toggle="tooltip" data-placement="top" title="Download a PDF report of results - winners only without scores">PDF</a></li>
                                    <li><a href="<?php echo $base_url; ?>output/export.output.php?section=results&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=html" data-toggle="tooltip" data-placement="top" title="Download a HTML report of results to copy/paste into another website - winners only without scores">HTML</a></li>
                                </ul>
                            -->
                            </div>
                        </div><!-- ./row -->
                        <?php } ?>
                        <div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>BJCP Points</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>Award Labels</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<ul class="list-inline">
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=default&amp;psort=5160" data-toggle="tooltip" data-placement="top" title="Avery 5160">Letter</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=default&amp;psort=3422" data-toggle="tooltip" data-placement="top" title="Avery 3422">A4</a></li>
								</ul>
							</div>
						</div><!-- ./row -->

                        <div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>Medal Labels (Round)</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<ul class="list-inline">
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=round&amp;psort=5293" data-toggle="tooltip" data-placement="top" title="1 2/3 inch Round Avery 5293">Letter</a></li>
                                    <!--
									<li><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=round&amp;psort=OL5375" data-toggle="tooltip" data-placement="top" title="2 inch (50.8 mm) Round Online Labels OL5375">Letter</a> (<a class="hide-loader" href="https://www.onlinelabels.com/OL5375.htm" target="_blank">OL5375</a>)</li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=round&amp;psort=OL3012" data-toggle="tooltip" data-placement="top" title="1.25 inch (31.75 mm) Round Online Labels OL3012">Letter</a> (<a class="hide-loader" href="https://www.onlinelabels.com/OL3012.htm" target="_blank">OL3012</a>)</li>
                                </ul>
                                <ul class="list-inline">
									<li><a href="<?php echo $base_url; ?>output/labels.output.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=round&amp;psort=EU30095" data-toggle="tooltip" data-placement="top" title="45 mm Round Online Labels EU30095">A4</a> (<a class="hide-loader" href="https://uk.onlinelabels.com/EU30095.htm" target="_blank">EU30095</a>)</li>
                                -->
								</ul>
							</div>
						</div>

						<?php } ?>
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>Address Labels</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>Summaries</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<ul class="list-inline">
									<li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=summary" data-toggle="tooltip" data-placement="top" title="Print participant summaries - each on a separate sheet of paper. Useful as a cover sheet for mailing entry scoresheets to participants.">All Participants with Entries</a></li>
                                    <li><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=particpant-entries" data-toggle="tooltip" data-placement="top" title="Print a list of all particpants with entries and associated judging numbers as assigned in the system. Useful for distributing scoresheets that are physically sorted by entry or judging numbers.">All Entries by Particpant</a></li>
								</ul>
							</div>
						</div><!-- ./row -->
						<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<strong>Inventory</strong>
							</div>
							<div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <strong>Emails (CSV)</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <strong>Participants (CSV)</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <ul class="list-unstyled">
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=participants&amp;go=csv">All Participants</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;filter=winners">Winners</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <strong>Entries (CSV)</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <ul class="list-unstyled">
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;action=all&amp;filter=all">All Entries: All Data</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv">All Entries: Limited Data</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;filter=brewer_contact_info">All Entries: Limited Data with Brewer Contact Info</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;filter=paid&amp;view=all">Paid Entries</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;filter=paid">Paid &amp; Received Entries</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;filter=paid&amp;view=not_received">Paid Entries Not Received</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;filter=nopay&amp;view=all">Non-Paid Entries</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;filter=nopay">Non-Paid &amp; Received Entries</a></li>
									<li><a class="hide-loader" href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;action=required&amp;filter=required">Entries with Required &amp; Optional Info</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <strong>Promo Materials</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <ul class="list-inline">
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
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <strong>Integrity</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <ul class="list-inline">
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=cleanup" data-toggle="tooltip" data-placement="top" title="Check and clear the database of duplicate entries, etc." data-confirm="Are you sure? This will check the database for duplicate entries, duplicate scores for a single entry, users without associated personal data [no first name, no last name], etc.">Clean-Up Data</a></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <strong>Entries</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <ul class="list-inline">
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=confirmed&amp;dbTable=<?php echo $brewing_db_table; ?>" data-toggle="tooltip" data-placement="top" title="Mark ALL unconfirmed entries as confirmed even if entries are incomplete" data-confirm="Are you sure? This will mark ALL entries as confirmed even if the entry is incomplete. It could be a large pain to undo.">Confirm All Unconfirmed</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=purge&amp;go=unconfirmed" data-toggle="tooltip" data-placement="top" title="Delete all unconfirmed entries" data-confirm="Are you sure? This will delete ALL unconfirmed entries and/or entries without special ingredients/classic style info that require them from the database - even those that are less than 24 hours old. This cannot be undone.">Purge All Unconfirmed</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <strong>Purge</strong>
                        </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <ul class="list-inline">
                                    <li><a href="#" data-toggle="modal" data-target="#purgeEntries">Entries</a></li>
                                    <?php if (check_setup($prefix."payments",$database)) { ?>
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#purgePayments">Payments</a></li>
                                    <?php } ?>
                                    <li><a class="hide-loader" href="#" data-toggle="modal" data-target="#purgeParticipants">Participants</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=purge&amp;go=tables" data-confirm="Are you sure you want to delete all judging tables and associated data including judging/stewarding table assignments and scores? This cannot be undone.">Judging Tables</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=purge&amp;go=scores" data-confirm="Are you sure you want to delete all scoring data from the database including best of show? This cannot be undone.">Scores</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=purge&amp;go=custom" data-confirm="Are you sure you want to delete all custom categories and associated data? This cannot be undone.">Custom Categories</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=purge&amp;go=availability" data-confirm="Are you sure you want to reset all entrant availability? All current judge, steward, and staff assignments will be cleared, judge/steward availability will be set to &ldquo;No,&rdquo; location preferences will be set to to &ldquo;No,&rdquo; and entrant staff interest will be set to &ldquo;No&rdquo; for all entrants. This is useful for sites that are carrying over user data to another competition instance, however, it is critical that all entrants be notified to update their judge, steward, and staff availability. This cannot be undone.">Entrant Availability</a></li>
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=delete_scoresheets&filter=admin-dashboard" data-confirm="Are you sure you want to delete all uploaded scoresheets in the root of the user_docs directory? This cannot be undone. Use the archive function if you wish to retain any uploaded scoresheets contained in the root of the user_docs directory.">Uploaded Scoresheets</li>
                                </ul>
                                <ul class="list-inline">
                                    <li><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=purge&amp;go=purge-all" data-confirm="Are you sure you want to delete entry, participant, judging table, score, and custom category data? This cannot be undone.">All of the Above</a> <span class="fa fa-hand-o-up small"></span></li>
                                </ul>
                            </div>
                        </div><!-- ./row -->


                        <!-- Purge Modals -->
                        <div class="modal fade" id="purgeParticipants" tabindex="-1" role="dialog" aria-labelledby="previewBestLabel">
                        	<form class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?action=purge&amp;go=participants" method="POST" name="form1" id="form1">
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
										<input class="form-control" id="dateThresholdParticipants" name="dateThreshold" type="text" value="" placeholder="">
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
									<input type="submit" class="btn btn-success" value="Yes">
								</div>
								</div>
							</div>
							</form>
						</div>

                       <div class="modal fade" id="purgePayments" tabindex="-1" role="dialog" aria-labelledby="purgePaymentsLabel">
                        	<form class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?action=purge&amp;go=payments" method="POST" name="form1" id="form1">
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
										<input class="form-control" id="dateThresholdPayments" name="dateThreshold" type="text" value="" placeholder="">
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
									<input type="submit" class="btn btn-success" value="Yes">
								</div>
								</div>
							</div>
							</form>
						</div>
                       <div class="modal fade" id="purgeEntries" tabindex="-1" role="dialog" aria-labelledby="purgeEntriesLabel">
                        	<form class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?action=purge&amp;go=entries" method="POST" name="form1" id="form1">
							<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="purgeEntriesLabel">Please Confirm</h4>
								</div>
								<div class="modal-body">
									<p>Are you sure you want to delete entries and associated data, including scores, BOS scores, and associated scoresheets (if present)? This cannot be undone.</p>
									<p>Optionally, choose a date threshold. Entries and associated data will not be purged <strong>if they were <em>updated</em> on or after</strong> the date you choose.</p>
									<p>Leave the field blank to purge all entries.</p>
									<div class="input-group">
										<input class="form-control" id="dateThresholdEntries" name="dateThreshold" type="text" value="" placeholder="">
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
									<input type="submit" class="btn btn-success" value="Yes">
								</div>
								</div>
							</div>
							</form>
						</div>
                        <?php if ($_SESSION['userLevel'] == "0") { ?>
                        <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <strong>Archives</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <ul class="list-inline">
                                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive">Manage</a></li>
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
                        <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <strong>Preferences</strong>
                        </div>
                        <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <ul class="list-inline">
                                <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences">Website</a></li>
                                <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences">Competition Organization</a></li>
                            </ul>
                        </div>
                        </div><!-- ./row -->
						<?php if (($_SESSION['prefsUseMods'] == "Y") && (!HOSTED)) { ?>
						 <div class="row">
                            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <strong>Custom Modules</strong>
                            </div>
                            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
                            <div class="row">
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>Post Setup</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>How Do I...</strong>
                                </div>
                                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <ul class="list-unstyled">
                                    	<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-comp-logo">Display the Competition Logo</a></li>
                                       	<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-sponsor-logo">Display Sponsors with Logos</a></li>
                                 		<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-check-in">Check In Received Entries</a></li>
                                       	<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-tables">Set Up Judging Tables</a></li>
                                     	<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-assign-tables">Assign Judges/Stewards to Tables</a></li>
                                 		<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-materials">Print Judging Day Materials</a></li>
                                		<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-bos-judges">Assign Best of Show Judges</a></li>
										<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-bos-results">Enter Scores and BOS Results</a></li>
                                  		<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-winning">Display Winning Entries</a></li>
                                    	<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-pro-am">Display Pro-Am Winner(s)</a></li>
										<li><a class="hide-loader" href="https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues" target="_blank">Report an Issue</a></li>
                                        <!--
                                  		<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-winner-rpt">Print a Winner Report</a></li>
                                		<li><a href="#" role="button" data-toggle="modal" data-target="#dashboard-help-modal-bjcp-points">Report BJCP Judging Points</a></li>
                                        -->
                                    </ul>
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
