<!-- Sub Menu Dropdowns http://behigh.github.io/bootstrap_dropdowns_enhancement/ -->
            <!-- Begin Admin Dropdown Menu -->
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Admin <span class="caret"></span></a>
              <ul class="dropdown-menu pull-right" role="menu">
              		<li><a href="<?php echo $base_url."index.php?section=admin"; ?>" tabindex="-1">Admin Dashboard</a></li>
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Preferences</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Define</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=preferences"; ?>" tabindex="-1">Global</a></li>
                        			<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging_preferences"; ?>" tabindex="-1">Organization</a></li>
                    			</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- End Defining Preferences Sub-Menu -->
                    <!-- Preparing Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Preparing</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=style_types"; ?>" tabindex="-1">Style Types</a></li>
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=styles"; ?>" tabindex="-1">Accepted Style Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=special_best"; ?>" tabindex="-1">Custom Category Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging"; ?>" tabindex="-1">Judging Locations/Dates</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contacts"; ?>" tabindex="-1">Competition Contacts</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=dropoff"; ?>" tabindex="-1">Drop-Off Locations</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=sponsors"; ?>" tabindex="-1">Sponsors</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Add</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=style_types&amp;action=add"; ?>" tabindex="-1">Style Types</a></li>
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=styles&amp;action=add"; ?>" tabindex="-1">Custom Style Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=special_best&amp;action=add"; ?>" tabindex="-1">Custom Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging&amp;action=add"; ?>" tabindex="-1">Judging Locations/Dates</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contacts&amp;action=add"; ?>" tabindex="-1">Competition Contacts</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=dropoff&amp;action=add"; ?>" tabindex="-1">Drop-Off Locations</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=sponsors&amp;action=add"; ?>" tabindex="-1">Sponsors</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Edit</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contest_info"; ?>" tabindex="-1">Competition Info</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Upload</a>
                            	<ul class="dropdown-menu">
                                	<!-- The following will should be changed to utilize a "smart" upload function -->
                              		<li><a href="<?php echo $base_url."admin/upload.admin.php"; ?>" tabindex="-1">Competition Logo</a></li>
                                    <li><a href="<?php echo $base_url."admin/upload.admin.php"; ?>" tabindex="-1">Sponsor Logos</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Preparing Sub-Menu -->
                    <!-- Entry and Data Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Accepting Entries</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=style_types"; ?>" tabindex="-1">Style Types</a></li>
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=styles"; ?>" tabindex="-1">Accepted Style Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=special_best"; ?>" tabindex="-1">Custom Category Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entries"; ?>" tabindex="-1">Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging&amp;action=add"; ?>" tabindex="-1">Judging Locations/Dates</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants&amp;filter=judges"; ?>" tabindex="-1">Available Judges</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants&amp;filter=stewards"; ?>" tabindex="-1">Available Stewards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contacts"; ?>" tabindex="-1">Competition Contacts</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=dropoff"; ?>" tabindex="-1">Drop-Off Locations</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=sponsors"; ?>" tabindex="-1">Sponsors</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Add</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=style_types&amp;action=add"; ?>" tabindex="-1">Style Types</a></li>
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=styles&amp;action=add"; ?>" tabindex="-1">Custom Style Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=special_best&amp;action=add"; ?>" tabindex="-1">Custom Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entrant&amp;action=register"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judge&amp;action=register"; ?>" tabindex="-1">Participants as Judges or Stewards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging&amp;action=add"; ?>" tabindex="-1">Judging Locations/Dates</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contacts&amp;action=add"; ?>" tabindex="-1">Competition Contacts</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=dropoff&amp;action=add"; ?>" tabindex="-1">Drop-Off Locations</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=sponsors&amp;action=add"; ?>" tabindex="-1">Sponsors</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Assign</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges"; ?>" tabindex="-1">Participants as Judges</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards"; ?>" tabindex="-1">Participants as Stewards</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Entry and Data Sub-Menu -->
                    <!-- Sorting Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Sorting Entries</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entries"; ?>" tabindex="-1">Entries</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Add</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entrant&amp;action=register"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judge&amp;action=register"; ?>" tabindex="-1">Participants as Judges or Stewards</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Check In</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=checkin"; ?>" tabindex="-1">Bar-Coded Entries</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Print</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."output/sorting.php?section=admin&amp;go=default&amp;filter=default"; ?>" tabindex="-1">Sorting Sheets</a></li>
                                    <li><a href="<?php echo $base_url."output/sorting.php?section=admin&amp;go=cheat&amp;filter=default"; ?>" tabindex="-1">Entry/Judging Number Cheat Sheet</a></li>
                                    <li><a href="<?php echo $base_url."output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default"; ?>" tabindex="-1">Bottle Labels (Entry Numbers)</a></li>
                                    <li><a href="<?php echo $base_url."output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default"; ?>" tabindex="-1">Bottle Labels (Judging Numbers)</a>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Sorting Sub-Menu -->
                    <!-- Organizing Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Organizing</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entries"; ?>" tabindex="-1">Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judging Tables</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Styles Not Assigned to Tables</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Assigned Judges</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Assigned Stewards</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Add</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entrant&amp;action=register"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judge&amp;action=register"; ?>" tabindex="-1">Participants as Judges or Stewards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">A Judging Table</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Assign</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Participants as Judges</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Participants as Stewards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Tables to Rounds</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judges or Stewards to a Table</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Best of Show Judges</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Organizing Sub-Menu -->
                    <!-- Scoring Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Scoring</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entrant&amp;action=register"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entries"; ?>" tabindex="-1">Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judging Tables</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Scores by Table</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Scores by Category</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Best of Show Entries/Places</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Custom Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Custom Category Entries</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Scoring Sub-Menu -->
                    <!-- Printing and Reporting Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Printing and Reporting</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Before Judging</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Entry Totals by Drop-Off Location</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">List of Entries by Drop-Off Location</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Pullsheets</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Table Cards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judge Assignments</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Steward Assignments</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judge Sign In Sheet</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Steward Sign In Sheet</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Name Tags</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">During Judging</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">BOS Pullsheets</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">BOS Cup Mats</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">After Judging</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Results Report with Scores</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Results Report without Scores</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">BOS Results Report</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">BJCP Points Report</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Participant Address Labels</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Participant Summaries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Post-Judging Inventory</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Printing Sub-Menu -->
                    <!-- Exporting Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Exporting</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Email CSV</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."output/email_export.php"; ?>" tabindex="-1">All Participants</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=avail_judges&amp;action=email"; ?>" tabindex="-1">Available Judges</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=avail_stewards&amp;action=email"; ?>" tabindex="-1">Available Stewards</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=judges&amp;action=email"; ?>" tabindex="-1">Assigned Judges</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=stewards&amp;action=email"; ?>" tabindex="-1">Assigned Stewards</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=staff&amp;action=email"; ?>" tabindex="-1">Assigned Staff</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=winners"; ?>" tabindex="-1">Winners</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;action=email"; ?>" tabindex="-1">All Entries</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email&amp;view=all"; ?>" tabindex="-1">All Paid Entries</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email"; ?>" tabindex="-1">All Paid &amp; Received Entries</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email&amp;view=all"; ?>" tabindex="-1">All Non-Paid Entries</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email"; ?>" tabindex="-1">All Non-Paid &amp; Received Entries</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Data CSV</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;action=all&amp;filter=all"; ?>" tabindex="-1">All Entries (All Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv"; ?>" tabindex="-1">All Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;view=all"; ?>" tabindex="-1">All Paid Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid"; ?>" tabindex="-1">All Paid &amp; Received Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;view=all"; ?>" tabindex="-1">All Non-Paid Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay"; ?>" tabindex="-1">All Non-Paid &amp; Received Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/participants_export.php?section=admin&amp;go=csv"; ?>" tabindex="-1">All Participants</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=winners"; ?>" tabindex="-1">Winners</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Promo Materials</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."output/promo_export.php?section=admin&amp;go=html&amp;action=html"; ?>" tabindex="-1">HTML</a></li>
                                    <li><a href="<?php echo $base_url."output/promo_export.php?section=admin&amp;go=word&amp;action=word"; ?>" tabindex="-1">Word</a></li>
                                    <li><a href="<?php echo $base_url."output/promo_export.php?section=admin&amp;go=word&amp;action=bbcode"; ?>" tabindex="-1">Bulletin Board Code (BBC)</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Exporting Sub-Menu -->
                    <!-- Exporting Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Archiving</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=archives"; ?>" tabindex="-1">Archives</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Exporting Sub-Menu -->
              	</ul>
            </li>
            <!-- END Admin Menu -->