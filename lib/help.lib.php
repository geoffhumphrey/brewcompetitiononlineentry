<?php

$bcoem_dashboard_help_array = array("comp-prep","entries-participants","sorting","organizing","scoring","preferences","reports","data-exports","data-mgmt","comp-logo","sponsor-logo","check-in","tables","assign-tables","materials","bos-judges","bos-results","winning","pro-am");

function bcoem_dashboard_help($content) {
	require(CONFIG.'config.php');
	require(INCLUDES.'url_variables.inc.php');
	$bcoem_dashboard_help_title = "";
	$bcoem_dashboard_help_body = "";
	$return = "";
		
	switch($content) {
		case "comp-prep":
		$bcoem_dashboard_help_title .= "Competition Preparation Help";
		$bcoem_dashboard_help_body .= "<p>The Competition Preparation category houses links to areas of your site that are intended to be completed before account or entry registration opens. Much of this information was completed during the set up process.</p>";
		$bcoem_dashboard_help_body .= "<p>Expand the category by clicking the heading or icon. Once expanded, you are presented with several choices. You can:</p>";
		$bcoem_dashboard_help_body .= "<ol>";
  		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=contacts\">Manage</a> or <a href=\"".$base_url."index.php?section=admin&amp;go=contacts&amp;action=add\">add</a> competition contacts.";
  		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=special_best\">Manage</a> or <a href=\"".$base_url."index.php?section=admin&amp;go=special_best&amp;action=add\">add</a> custom categories.";
  		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=dropoff\">Manage</a> or <a href=\"".$base_url."index.php?section=admin&amp;go=dropoff&amp;action=add\">add</a> drop-off locations.";
  		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=judging\">Manage</a> or <a href=\"".$base_url."index.php?section=admin&amp;go=judging&amp;action=add\">add</a> judging locations.";
  		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=sponsors\">Manage</a> or <a href=\"".$base_url."index.php?section=admin&amp;go=sponsors&amp;action=add\">add</a> sponsors.</li>";
		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=styles\">Manage</a> or <a href=\"".$base_url."index.php?section=admin&amp;go=styles&amp;action=add\">add</a> accepted styles.</li>";
  		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=style_types\">Manage</a> or <a href=\"".$base_url."index.php?section=admin&amp;go=style_types&amp;action=add\">add</a> style types.</li>";
  		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=contest_info\">Edit</a> competition info.</a></li>";
  		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=upload\">Upload</a> a competition logo or sponsor logos.</li>";
		$bcoem_dashboard_help_body .= "</ol>";
		break;
		
		case "entries-participants":
		$bcoem_dashboard_help_title .= "Entries and Participants Help";
		$bcoem_dashboard_help_body .= "<p>Once registration has opened for your competition, you can keep track of ongoing participant and entry data by using the functions under the Entries and Participants heading.";
		$bcoem_dashboard_help_body .= "<p>Expand the category by clicking the heading or icon. Once expanded, you are presented with several choices. You can:</p>";
		$bcoem_dashboard_help_body .= "<ol>";
  		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=entries\">Manage</a> entries.";
  		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=participants\">Manage</a> participants.";
  		$bcoem_dashboard_help_body .= "<li>Assign <a href=\"".$base_url."index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=judges\">judges</a>, <a href=\"".$base_url."index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=stewards\">stewards</a>, or <a href=\"".$base_url."index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=staff\">staff</a> to their respective pools.</li>";
		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=entrant&amp;action=register\">Register</a> a participant.</li>";
		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=judge&amp;action=register\">Register</a> a judge or steward, inputting all registration data.</li>";
		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=judge&amp;action=register&amp;view=quick\">Quick Register</a> a judge or steward, inputting minimal registration data. This option is optimal for adding judges or stewards to the pool on-the-fly (usually on the day of the judging).</li>";
		$bcoem_dashboard_help_body .= "</ol>";
		break;
		
		case "sorting":
		$bcoem_dashboard_help_title .= "Entry Sorting Help";
		$bcoem_dashboard_help_body .= "<p>Once the registration period and entry window for your competition passes, the next step in the process, after picking up entries from various drop off points, is to sort them. BCOE&amp;M provides tools to assist in sorting entries.</p>";
		$bcoem_dashboard_help_body .= "<p>Under the Entry Sorting heading, administrators are presented with some options specific to the task, namely:";
		$bcoem_dashboard_help_body .= "<ul>";
		$bcoem_dashboard_help_body .= "<li>Checking-in entries (marking them as paid and received) <a href=\"".$base_url."index.php?section=admin&amp;go=entries\">manually</a> or, if enabled, <a href=\"".$base_url."index.php?section=admin&amp;go=checkin\">via barcode scanner</a>.</li>";
		$bcoem_dashboard_help_body .= "<li>Printing sorting sheets by entry numbers or judging numbers.</li>";
		$bcoem_dashboard_help_body .= "<li>Printing entry number/judging number cheat sheets organized by style.</li>";
		$bcoem_dashboard_help_body .= "<li>Printing labels for individual bottles with their unique entry or judging number. Available formats are letter (<a href=\"http://www.avery.com/avery/en_us/Products/Labels/Addressing-Labels/Easy-Peel-White-Address-Labels_05160.htm\" target=\"_blank\">Avery 5160</a>) and A4 (<a href=\"http://www.avery.se/avery/en_se/Products/Labels/Multipurpose/White-Multipurpose-Labels-Permanent/General-Usage-Labels-White_3422.htm\" target=\"_blank\">Avery 3422</a>) for rectangular labels, 0.50 inch/13mm round (<a href=\"http://www.onlinelabels.com/Products/OL32.htm\" target=\"_blank\">Online Labels OL32</a>) or 0.75 inch/19 mm round (<a href=\"http://www.onlinelabels.com/Products/OL5275WR.htm\" target=\"_blank\">Online Lables OL5275WR</a>).</li>";
		$bcoem_dashboard_help_body .= "</ul>";
		$bcoem_dashboard_help_body .= "<p>Of note is the Regenerate Judging Numbers function. If your competition is not utilizing the barcode check-in feature, administrators can make extra sure that all entries in the database are assigned a unique judging number by activating this function.</p>";
		
		break;
		
		case "organizing":
		$bcoem_dashboard_help_title .= "Organizing Help";
		$bcoem_dashboard_help_body .= "<p>After sorting has been accomplished, the next task for administrators is to organize the competition data in preparation for judging. Organization is really where the <em>M</em> in BCOE&amp;M shows its utility.</p>";
		$bcoem_dashboard_help_body .= "<p>Organization in BCOE&amp;M begins with assigning individual participants as a <a href=\"".$base_url."index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=staff\">staff</a> member and/or <a href=\"".$base_url."index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=judges\">judge</a> or <a href=\"".$base_url."index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=stewards\">steward</a>. This builds a pool of available participants to assign to various duties in the competition.</p>";
		$bcoem_dashboard_help_body .= "<p>Once assignments have been given, the next steps are to:</p>";
		$bcoem_dashboard_help_body .= "<ol>";
		$bcoem_dashboard_help_body .= "<li><a href=\"".$base_url."index.php?section=admin&amp;go=judging_tables\">Define tables</a> where specific sub-styles will be judged.</li>";
		$bcoem_dashboard_help_body .= "<li>Verify that <a href=\"".$base_url."index.php?section=admin&amp;go=judging_tables&amp;filter=orphans\">all accepted style categories with entries are assigned to tables</a>.</li>";
		$bcoem_dashboard_help_body .= "<li>Add flights to tables (if queued judging is disabled).</li>";
		$bcoem_dashboard_help_body .= "<li>Assign <a href=\"".$base_url."index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds\">tables to rounds</a>.</li>";
		$bcoem_dashboard_help_body .= "<li>Assign judges and stewards to tables (and flights, if applicable).</li>";
		$bcoem_dashboard_help_body .= "</ol>";
		$bcoem_dashboard_help_body .= "<p>Once these tasks have been completed, you are ready to print reports you need prior to judging: pullsheets, table cards, assignment lists, sign-in sheets, scoresheet lables, and name tags. See the Reports section.</p>";
		break;
		
		case "scoring":
		$bcoem_dashboard_help_title .= "Scoring Help";
		$bcoem_dashboard_help_body .= "<p>During or after the initial round of judging, competition organizers can record the assigned score and place, if applicable, for each entry. From this pool of scores, BCOE&amp;M then determines which entry or entries move on to the Best of Show round by style type (see the competition&rsquo;s organization preferences).<p>";
		$bcoem_dashboard_help_body .= "<p>For instance, administrators can designate that only the 1st place entries for the Beer style type may move to the BOS round, but the 1st, 2nd, and 3rd place from the Mead and Cider categories move on.";
		$bcoem_dashboard_help_body .= "<p>Once all scores and places have been recorded for the initial round of judging, Administrators can then view and print pullsheets for the Best of Show round.</p>";
		$bcoem_dashboard_help_body .= "<p>After the BOS round has completed, administrators can then enter the BOS winners for each style type.</p>";
		break;
		
		case "preferences":
		$bcoem_dashboard_help_title .= "Preferences Help";
		$bcoem_dashboard_help_body .= "<p><a href=\"".$base_url."index.php?section=admin&amp;go=preferences\">Website Preferences</a> are those that affect the behavior of your BCOE&amp;M installation, such as the overall site theme, styleset to use, entry limit, per-participant entry limit, units of measurement, currency and localization, etc.</p>";
		$bcoem_dashboard_help_body .= "<p><a href=\"".$base_url."index.php?section=admin&amp;go=judging_preferences\">Competition Organization Preferences</a> are those affect how BCOE&amp;M behaves with regard to tables, flights, and best of show functions.</p>";
		break;
		
		case "reports":
		$bcoem_dashboard_help_title .= "Reports Help";
		$bcoem_dashboard_help_body .= "<p>BCOE&amp;M offers several options for printing and reporting competition related data and results. In general, the reports generated fall into three categories: before judging, during judging, and after judging.</p>";
		$bcoem_dashboard_help_body .= "<p><strong>Before Judging</strong></p>";
		$bcoem_dashboard_help_body .= "<ul>";
		$bcoem_dashboard_help_body .= "<li>Pullsheets for each table defined in the system, using entry numbers or judging numbers.</li>";
		$bcoem_dashboard_help_body .= "<li>Table cards for each table.</li>";
		$bcoem_dashboard_help_body .= "<li>Assignment lists for judges and stewards.</li>";
		$bcoem_dashboard_help_body .= "<li>Sign-in sheets for judges and stewards.</li>";
		$bcoem_dashboard_help_body .= "<li>Judge, steward, and staff name tags.</li>";
		$bcoem_dashboard_help_body .= "<li>Judge scoresheet labels in letter (<a href=\"http://www.avery.com/avery/en_us/Products/Labels/Addressing-Labels/Easy-Peel-White-Address-Labels_05160.htm\" target=\"_blank\">Avery 5160</a>) and A4 (<a href=\"http://www.avery.se/avery/en_se/Products/Labels/Multipurpose/White-Multipurpose-Labels-Permanent/General-Usage-Labels-White_3422.htm\" target=\"_blank\">Avery 3422</a>) format.</li>";
		$bcoem_dashboard_help_body .= "</ul>";
		$bcoem_dashboard_help_body .= "<p><strong>During Judging</strong></p>";
		$bcoem_dashboard_help_body .= "<ul>";
		$bcoem_dashboard_help_body .= "<li>Best of Show (BOS) pullsheets for all designated style types.</li>";
		$bcoem_dashboard_help_body .= "<li>Best of Show (BOS) cup mats for judges to use while judging the BOS round. These are inteneded to be printed in the landscape format.</li>";
		$bcoem_dashboard_help_body .= "</ul>";
		$bcoem_dashboard_help_body .= "<p><strong>After Judging</strong></p>";
		$bcoem_dashboard_help_body .= "<ul>";
		$bcoem_dashboard_help_body .= "<li>A results report, with and without scores - PDF or HTML.</li>";
		$bcoem_dashboard_help_body .= "<li>A Best of Show (BOS) results report - PDF or HTML.</li>";
		$bcoem_dashboard_help_body .= "<li>A BJCP points report in three formats - PDF, HTML, or XML.</li>";
		$bcoem_dashboard_help_body .= "<li>Award lables to affix to ribbons, prizes, etc.</li>";
		$bcoem_dashboard_help_body .= "<li>Participant address labels for mailing scoresheets.</li>";
		$bcoem_dashboard_help_body .= "<li>Participant summaries - cover sheets to include with mailed scoresheets.</li>";
		$bcoem_dashboard_help_body .= "<li>A post-judging inventory detailing possible leftover bottles and their corresponding entry number, judging number, and style.</li>";
		$bcoem_dashboard_help_body .= "</ul>";
		break;
		
		case "data-exports":
		$bcoem_dashboard_help_title .= "Data Exports Help";
		$bcoem_dashboard_help_body .= "<p>Under the Data Exports heading, BCOE&amp;M features exporting options intended to provide administrators competition related data in easy to use formats such as CSV, HTML, Word, BBC, and PDF.</p>";
		$bcoem_dashboard_help_body .= "<ul>";
		$bcoem_dashboard_help_body .= "<li>CSV (comma separated value) files are easily read and manipulated by spreadsheet programs such as Microsoft Excel or Open Office Calc.</li>";
		$bcoem_dashboard_help_body .= "<li>HTML files are useful for posting to another website or blog using standardized tags.</li>";
		$bcoem_dashboard_help_body .= "<li>BBC (bulletin board code) files contain specialized markup for forum websites that utilize standardized bulletin board code. The <a href=\"https://www.homebrewersassociation.org/forum/\" target=\"_blank\">AHA Forum</a> accepts BBC code for posts. This is useful if you want to announce your competition using the Promo Materials BBC export option.</li>";
		$bcoem_dashboard_help_body .= "<li>Word files are downloads that are easily read and edited by word processing programs such as Microsoft Word or Open Office Writer.</li>";
		$bcoem_dashboard_help_body .= "</ul>";
		break;
		
		case "data-mgmt":
		$bcoem_dashboard_help_title .= "Data Management Help";
		$bcoem_dashboard_help_body .= "<p>Under the Data Management heading, top-level administrators can periodically make sure that data integrity is correct for their installation.</p>";
		$bcoem_dashboard_help_body .= "<p>Included is the ability to manually trigger BCOE&amp;M&rsquo;s Data Clean-up function that checks for any duplicate or empty rows in the user/participant, entry, and all associated judging tables.</p>";
		$bcoem_dashboard_help_body .= "<p>Also included is the ability to confirm all unconfirmed entries, ";
		$bcoem_dashboard_help_body .= "";
		break;
		
		case "comp-logo":
		$bcoem_dashboard_help_title .= "How Do I Display the Competition Logo?";
		$bcoem_dashboard_help_body .= "<p>First, use BCOE&amp;M&rsquo;s <a href=\"".$base_url."index.php?section=admin&amp;go=upload\">upload</a> function to upload the competition&rsquo;s logo image. Acceptable formats are .jpg, .gif, and .png.<p>";
		$bcoem_dashboard_help_body .= "<p>Then, under the Competition Preparation heading, <a href=\"".$base_url."index.php?section=admin&amp;go=contest_info\">edit your competition info</a>, selecting the logo images&rsquo;s file name from the Logo File Name drop-down menu under the General section.</p>";
		break;
		
		case "sponsor-logo":
		$bcoem_dashboard_help_title .= "How Do I Display Sponsors with Logos?";
		$bcoem_dashboard_help_body .= "<p>First, use BCOE&amp;M&rsquo;s <a href=\"".$base_url."index.php?section=admin&amp;go=upload\">upload</a> function to upload each sponsor&rsquo;s logo image. Acceptable formats are .jpg, .gif, and .png.<p>";
		$bcoem_dashboard_help_body .= "<p>Then, under the Competition Preparation heading, <a href=\"".$base_url."index.php?section=admin&amp;go=sponsors&amp;action=add\">add</a> each sponsor, selecting the logo images&rsquo;s file name from the Logo File Name drop-down menu.</p>";
		break;
		
		case "check-in":
		$bcoem_dashboard_help_title .= "How Do I Check In Received Entries?";
		$bcoem_dashboard_help_body .= "<p>If your competition is utilizing the <a href=\"http://brewcompetition.com/barcode-labels\" target=\"_blank\">barcode option</a> on bottle labels, you can check-in entries using a barcode scanner via the <a href=\"".$base_url."index.php?section=admin&amp;go=checkin\">Check-In Entries with a Barcode Reader/Scanner</a> function.</p>";
		$bcoem_dashboard_help_body .= "<p>If your competition is <em>not</em> utilizing the barcode option on bottle labels, you can check-in entries via the <a href=\"".$base_url."index.php?section=admin&amp;go=entries\">Manage Entries</a> function.</p>";
		break;
		
		case "tables":
		$bcoem_dashboard_help_title .= "How Do I Set Up Judging Tables?";
		$bcoem_dashboard_help_body .= "<p>In BCOE&amp;M, setting up judging tables does not necessarily mean a physical table, although it can be if administrators are so inclined to organize judging that way. &ldquo;Tables&rdquo; refers to a collection of sub-styles to be judged by a particular set of judges.</p>";
		$bcoem_dashboard_help_body .= "<p>To set up tables, you will first need to assign participants as <a href=\"".$base_url."index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=judges\">judges</a> or <a href=\"".$base_url."index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=stewards\">stewards</a>. Then, access the <a href=\"".$base_url."index.php?section=admin&amp;go=judging_tables&amp;action=add\">Add Table</a> function.</p>";
		$bcoem_dashboard_help_body .= "<p>As you set up each table, you will be chosing one or more sub-styles to judge and providing a unique name.</p>";
		break;
		
		case "assign-tables":
		$bcoem_dashboard_help_title .= "How Do I Assign Judges/Stewards to Tables?";
		$bcoem_dashboard_help_body .= "<p>After setting up tables, you can then assign judges and stewards to each of them in one of two ways:</p>";
		$bcoem_dashboard_help_body .= "<ol>";
		$bcoem_dashboard_help_body .= "<li>By accessing the <a href=\"".$base_url."index.php?section=admin&amp;go=judging_tables&amp;action=assign\">Assign Judges or Stewards to Tables</a> screen an choosing a table from one of the drop-down lists.</li>";
		$bcoem_dashboard_help_body .= "<li>By clicking the icon next to the number in the &ldquo;Judges&rdquo; or &ldquo;Stewards&rdquo; column from the tables list on the <a href=\"".$base_url."index.php?section=admin&amp;go=judging_tables\">Administration: Tables</a> screen. The icon will either be <span class=\"fa fa-plus-circle\"></span> (no assignments at table) or <span class=\"fa fa-pencil-square-o\"></span> (assignments at table).</li>";
		$bcoem_dashboard_help_body .= "</ol>";
		$bcoem_dashboard_help_body .= "<p>BCOE&amp;M has built in logic that will not allow you to assign a judge or steward to a table who has been:</p>";
		$bcoem_dashboard_help_body .= "<ul>";
		$bcoem_dashboard_help_body .= "<li>Assigned to another table at the same location in the same round.</li>";
		$bcoem_dashboard_help_body .= "<li>Has an entry that would be judged at the table.</li>";
		$bcoem_dashboard_help_body .= "</ul>";
		break;
		
		case "materials":
		$bcoem_dashboard_help_title .= "How Do I Print Judging Day Materials?";
		$bcoem_dashboard_help_body .= "<p>After all tables have been defined and judges and stewards have been assigned to them, you are now ready to print the materials needed to help run the competition.</p>";
		$bcoem_dashboard_help_body .= "<p>Available materials are:</p>";
		$bcoem_dashboard_help_body .= "<ul>";
		$bcoem_dashboard_help_body .= "<li>Pullsheets detailing the entries cellarpeople retrieve for each table.</li>";
		$bcoem_dashboard_help_body .= "<li>Table cards to identify physical tables, detailing the table name and the judges and stewards assigned to it.</li>";
		$bcoem_dashboard_help_body .= "<li>Sign-in sheets for judges and stewards.</li>";
		$bcoem_dashboard_help_body .= "<li>Assignment sheets for judges and stewards.</li>";
		$bcoem_dashboard_help_body .= "<li>Scoresheet labels for each judge to affix to scoresheets.</li>";
		$bcoem_dashboard_help_body .= "<li>Nametags for judges, stewards, and staff.</li>";
		$bcoem_dashboard_help_body .= "</ul>";
		break;
		
		case "bos-judges":
		$bcoem_dashboard_help_title .= "How Do I Assign Best of Show Judges?";
		$bcoem_dashboard_help_body .= "<p>To assign judges for the best of show (BOS) round, you can do so via the <a href=\"".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos\">Assign Participants as Best of Show Judges</a> function.</p>";
		$bcoem_dashboard_help_body .= "<p>For a BJCP sanctioned competition, it is best practice to only choose ranked judges for the BOS panel of judges.</p>"; 
		$bcoem_dashboard_help_body .= "<p>The Assign Participants as Best of Show Judges screen details the available judges and sorts them by rank. It also details whether each judge has any entries that have placed in the competition. Judges cannot serve on the panel if one or more of their entries will be judged in the best of show round.</p>";
		break;
		
		case "bos-results":
		$bcoem_dashboard_help_title .= "How Do I Enter Scores and BOS Results?";
		$bcoem_dashboard_help_body .= "<p>After entries have been judged and scores and places have been awarded for each table, administrators can enter into scores into the system from the <a href=\"".$base_url."index.php?section=admin&amp;go=judging_scores\">Manage Scores</a> function. Choose the table from the &ldquo;Add or Update Scores For...&rdquo; drop-down menu.<p>";
		$bcoem_dashboard_help_body .= "<p>Scores for each entry and apporopriate places designated can be input. At a minimum, places must be entered for Best of Show entries to be recognized by the system for the Best of Show round pullsheets.</p>";
		$bcoem_dashboard_help_body .= "<p>Once the Best of Show round has been judged, administrators can input the results by selecting the appropriate style type from the &ldquo;Add or Update...&rdquo; drop-down menu from the <a href=\"".$base_url."index.php?section=admin&amp;go=judging_scores_bos\">Manage Best of Show (BOS) Entries and Places</a> function.</p>";
		$bcoem_dashboard_help_body .= "";
		break;
				
		case "winning":
		$bcoem_dashboard_help_title .= "How Do I Display Winning Entries?";
		$bcoem_dashboard_help_body .= "<p>Once scores and Best of Show results have been entered, the system will automatically display the results on the <a href=\"".$base_url."\">home page</a> after the designated Winner Display Delay prescribed by administrators has passed. This delay is input via the <a href=\"".$base_url."index.php?section=admin&amp;go=preferences\">Website Preferences</a> function.";
		break;
		
		case "pro-am":
		$bcoem_dashboard_help_title .= "How Do I Display Pro-Am Winner(s)?";
		$bcoem_dashboard_help_body .= "<p>Pro-am winners can be input and displayed using the <a href=\"".$base_url."index.php?section=admin&amp;go=special_best\">Custom Categories</a> function. Custom categories are not limited to pro-am opportunities, however.</p>";
		$bcoem_dashboard_help_body .= "<p>Any overall winning category can be defined by administrators - the sky is the limit here. Best Entry Name? Sure. Most Unique Ingredient? Absolutely. Custom categories are where the personality of your competition can be displayed.</p>";
		$bcoem_dashboard_help_body .= "<p>All custom category winning entries are displayed on the home page with the overall results of the competition, just below the Best of Show winners.</p>";
		break;
	}
	
	// Build modal window code
	$return .= "<div class=\"modal fade\" id=\"dashboard-help-modal-".$content."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"dashboard-help-modal-label-".$content."\">\n";
	$return .= "\t<div class=\"modal-dialog modal-lg\" role=\"document\">\n";
	$return .= "\t\t<div class=\"modal-content\">\n";
	$return .= "\t\t\t<div class=\"modal-header bcoem-admin-modal\">\n";
	$return .= "\t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>\n";
	$return .= "\t\t\t\t<h4 class=\"modal-title\" id=\"dashboard-help-modal-label-".$content."\">".$bcoem_dashboard_help_title."</h4>\n";
	$return .= "\t\t\t</div><!-- ./modal-header -->\n";
	$return .= "\t\t\t<div class=\"modal-body\">\n";
	$return .= "\t\t\t\t".$bcoem_dashboard_help_body."\n";
	$return .= "\t\t\t</div><!-- ./modal-body -->\n";
	$return .= "\t\t\t<div class=\"modal-footer\">\n";
	$return .= "\t\t\t\t<button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Close</button>\n";
	$return .= "\t\t\t</div><!-- ./modal-footer -->\n";
	$return .= "\t\t</div><!-- ./modal-content -->\n";
	$return .= "\t</div><!-- ./modal-dialog -->\n";
	$return .= "</div><!-- ./modal -->\n";
	
	return $return;
}

function bcoem_help($section,$go,$action,$filter) {
	require(CONFIG.'config.php');
	require(INCLUDES.'url_variables.inc.php');
	// Define default variables
	$return = "";
	$content = FALSE;
	$bcoem_help_title = "";
	$bcoem_help_body = "";
	
	// --------------------- General user sections --------------------- 
	// My account
	if ($section == "list") { 
		$bcoem_help_title .= "My Account Help";
		$bcoem_help_body .= "<p>This is a comprehensive snapshot of your account information.</p>";
		$bcoem_help_body .= "<p>Here, you can view your personal information including name, address, phone number(s), clubs, AHA member number, BJCP ID, BJCP judge rank, judging preferences, and stewarding preferences.</p>";
		$bcoem_help_body .= "<ul>";
		$bcoem_help_body .= "<li>Click the &ldquo;Edit Account&rdquo; button to update your personal information.</li>";
		$bcoem_help_body .= "<li>Click the &ldquo;Change Email&rdquo; button to update your email address. <strong>Note:</strong> your email address is also your user name.</li>";
		$bcoem_help_body .= "<li>Click the &ldquo;Change Password&rdquo; button to update your account password.</li>";
		$bcoem_help_body .= "</ul>";
		if ($show_entires) {
			$bcoem_help_body .= "<p>At the bottom of the page is your list of entries.</p>";
			$bcoem_help_body .= "<ul>";
			$bcoem_help_body .= "<li>Click the printer icon <span class=\"fa fa-print\"></span> to print the necessary documentation for each entry (bottle labels, etc.).</li>";
			$bcoem_help_body .= "<li>Click the pencil icon <span class=\"fa fa-pencil\"></span> to edit the entry.</li>";
			$bcoem_help_body .= "<li>Click the trash can icon <span class=\"fa fa-trash-o\"></span> to delete the entry.</li>";
			$bcoem_help_body .= "</ul>";
		}
		$content = TRUE;
	}
	
	// Edit account
	if (($section == "brewer") && ($go == "account") && ($action == "edit")) {
		$bcoem_help_title .= "Edit Account Help";
		$bcoem_help_body .= "<p>Here, you can update your account information including address/phone, AHA member number, BJCP ID, BJCP judge rank, judging or stewarding location availability and preferences, and so on.";
		$content = TRUE; 
	}
	
	// Pay fees
	if ($section == "pay") { 
		$bcoem_help_title .= "Pay Entry Fees Help";
		$bcoem_help_body .= "<p>This screen details your unpaid entries and associated fees. If the competition organizers have designated a discount for participants with a code, you can enter the code before paying for your entries.</p>";
		$bcoem_help_body .= "<p>For the ".$_SESSION['contestName'].", accepted payment methods are:</p>";
		$bcoem_help_body .= "<ul>";
		if ($_SESSION['prefsCash'] == "Y") $bcoem_help_body .= "<li><strong>Cash.</strong> Put cash in an envelope and attach to one of your bottles. Please, for the sanity of the organizing staff, do not pay with coins.</li>";
		if ($_SESSION['prefsCheck'] == "Y") $bcoem_help_body .= "<li><strong>Check.</strong> Make your check out to ".$_SESSION['prefsCheckPayee']." for the full amount of your entry fees, place in an envelope, and attach to one of your bottles. It would be extremely helpful for competition staff if you would list your entry numbers in the memo section.</li>";
		if ($_SESSION['prefsPaypal'] == "Y") $bcoem_help_body .= "<li><strong>Credit/Debit Card via PayPal.</strong> To pay your entry fees with a credit or debit card, click the &ldquo;Pay with PayPal&rdquo; button. A PayPal account is not necessary. After you have paid, be sure to click the &ldquo;Return to...&rdquo; link on the PayPal confirmation screen. This will ensure that your entries are marked as paid for this competition.</li>";
		$bcoem_help_body .= "</ul>";
		$content = TRUE;
	}
	
	// Change username
	if (($section == "user") && ($go == "account") && ($action == "username")) {
		$bcoem_help_title .= "Change Email Address Help";
		$bcoem_help_body .= "<p>Here, you can change your email address.</p><p><strong>Please Note:</strong> your email address also serves as your user name to access your account on this site.</p>";
		$content = TRUE;
	}
	
	// Change password
	if (($section == "user") && ($go == "account") && ($action == "password")) {
		$bcoem_help_title .= "Change Password Help";
		$bcoem_help_body .= "<p>Here, you can change your access password to this site. The more secure, the better &ndash; include special characters and/or numbers.</p>";
		$content = TRUE;
	}
	
	// --------------------- Admin --------------------- 
	
	if ($section == "admin") {
		
		if ($go == "contacts") {
			$bcoem_help_title .= "Contract Help";
			$bcoem_help_body .= "<p>Define the contacts associated with the competition (e.g., the Competition Coordinator, Head Judge, Cellar Master, etc.). The names will be available via a drop-down list on the Contact page.</p>";
			$content = TRUE;	
		}
		
		if ($go == "contest_info") {
			$bcoem_help_title .= "Competition Info Help";
			$bcoem_help_body .= "<p>Here admins can define or change the vital information associated with the competition (e.g., the the competition name, entry and registration dates, rules, etc.). The information entered will be displayed on your site&rsquo;s home and info pages.</p><p>This information is retained even after running BCOE&amp;M&rsquo;s Archive function; therefore, it will need to be updated periodically for each new competition instance.</p>";
			$content = TRUE;
		}
		
		if ($go == "special_best") {
			$bcoem_help_title .= "Custom Categories Help";
			$bcoem_help_body .= "<p>View and define a winning category unique to your competition. (e.g., Steward's Choice, Best Name, etc.). This is especially useful to define and display Pro-Am winners.</p><p>Click the &ldquo;Add a Custom Style&rdquo; button to define a new category.<p>";
			$content = TRUE;	
		}
		
		if ($go == "dropoff") {
			$bcoem_help_title .= "Drop-Off Locations Help";
			$bcoem_help_body .= "<p>Define one or more entry drop-off locations for participants to hand-deliver their entries. Drop-off locations are displayed on the Info with a link* to a map and driving directions.</p><p>A drop-off location may or may not be the same as the Shipping Location, which is defined in Competition Info. There is only one shipping location defined for the competition, whereas there can be multiple drop-off locations.</p><p>Click the &ldquo;Add a Drop-Off Location&rdquo; button to enter a drop-off location.<p><p class=\"small\">* The mapping features will only work if an address is input.</p>";
			$content = TRUE;	
		}
		
		if (($go == "judging") && ($action == "default")) {
			$bcoem_help_title .= "Judging Locations Help";
			$bcoem_help_body .= "<p>Define one or more entry judging locations and associated dates and times. Each judging location is displayed on the public pages sidebar with a link* to a map and driving directions.</p><p>Click the &ldquo;Add a Judging Location&rdquo; button to enter a new judging location and its associated date/time.<p><p class=\"small\">* The mapping features will only work if an address is input.</p>";
			$content = TRUE;	
		}
		
		if ($go == "sponsors") {
			$bcoem_help_title .= "Sponsors Help";
			$bcoem_help_body .= "<p>View, add or edit competition sponsors for display on the public Home page and Sponsors page.</p><p>For each sponsor, enter their name, location, level (1 through 5, 1 being the highest level), website address, logo image name* from the drop-down and an optional short description.<p><p class=\"small\">*Logo images must be uploaded first to show up on the logo image drop-down list. Click the &ldquo;Upload Sponsor Logo Images&rdquo; button.</p>";
			$content = TRUE;	
		}
		
		if ($go == "styles") {
			$bcoem_help_title .= "Styles Help";
			$bcoem_help_body .= "<p>Define the accepted sub-styles for your competition based upon the 2008 or 2015 BJCP Style Guidelines. Check or uncheck the sub-styles your competition will accept.</p><p>Custom Styles will be at the top of the list. To define a custom style or custom style type, click the &ldquo;Add...&rdquo; drop-down menu.</p>";
			$content = TRUE;	
		}
		
		if ($go == "style_types") {
			$bcoem_help_title .= "Styles Types Help";
			$bcoem_help_body .= "<p>Here, you can designate the style types for use in the competition.</p><p>BCOE&M ships with three pre-defined style types: Beer, Cider, and Mead. Using the Style Types function, Administrators can add a custom style type, define whether a best of show round will be conducted for each style type (including pre-defined ones), and designate which placed entries from each table will be sent to the best of show round (1st place only, 1st and 2nd place, or 1st, 2nd, and 3rd places).</p><p>You can also add a custom style type (e.g., wine, soda, saki, etc.) and its associated best of show methodology by clicking the &ldquo;Add...&rdquo; drop-down menu.</p>";
			$content = TRUE;	
		}
		
		if ($go == "entries") {
			$bcoem_help_title .= "Entries Help";
			$bcoem_help_body .= "<p>From here, Administrators can view, add, edit, or delete any entry.</p>";
			$bcoem_help_body .= "<ul>";
			$bcoem_help_body .= "<li>View entries entered into a single category by clicking on the category name.</li>";
			$bcoem_help_body .= "<li>View entries for a particular user by clicking on their name in the Brewer column.</li>";
			$bcoem_help_body .= "<li>Sort entries by entry nubmer, judging number, name of entry, category, or brewer by clicking on the corresponding column headers. The default view is by ascending category.</li>";
			$bcoem_help_body .= "<li>View all paid or unpaid entries by clicking the &ldquo;View...&rdquo; drop-down menu.</li>";
			$bcoem_help_body .= "<li>Add an entry for any participant by selecting their name in the the &ldquo;Add an Entry For...&rdquo; drop-down menu.</li>";
			$bcoem_help_body .= "<li>Print the list of entries sorted in various ways using the &ldquo;Print Current View...&rdquo; drop-down menu.</li>";
			$bcoem_help_body .= "<li>Perform global entry-related actions by selecting a function in the &ldquo;Admin Actions...&rdquo; drop-down menu.</li>";
			$bcoem_help_body .= "<li>Designate entries as paid and/or received by checking the corresponding box for each entry.</li>";
			$bcoem_help_body .= "<li>Edit the entry by clicking the pencil icon <span class=\"fa fa-pencil\"></span>.</li>";
			$bcoem_help_body .= "<li>Delete the entry by clicking the trash can icon <span class=\"fa fa-trash-o\"></span>.</li>";
			$bcoem_help_body .= "<li>Print entry forms for any entry by clicking the printer icon <span class=\"fa fa-print\"></span>.</li>";
			$bcoem_help_body .= "<li>Email the participant who added the entry by clicking the envelope icon <span class=\"fa fa-envelope\"></span>.</li>";
			$bcoem_help_body .= "</ul>";
			$content = TRUE;	
		}
		
		if ($go == "participants") {
			$bcoem_help_title .= "Participants Help";
			$bcoem_help_body .= "<p>&ldquo;Participant&rdquo; is a term for any person who:</p>";
			$bcoem_help_body .= "<ul>";
			$bcoem_help_body .= "<li>Registered online with the intent to log entries and/or add themselves to the judging or stewarding pool.</li>";
			$bcoem_help_body .= "<li>Was registered by an administrator.</li>";
			$bcoem_help_body .= "</ul>";
			$bcoem_help_body .= "<p>Participants can be one or any combination of the following:</p>";
			$bcoem_help_body .= "<ul>";
			$bcoem_help_body .= "<li>Administrators of the BCOE&amp;M installation.</li>";
			$bcoem_help_body .= "<li>The competition organizer.</li>";
			$bcoem_help_body .= "<li>Entrants, judges, stewards, or staff.</li>";
			$bcoem_help_body .= "</ul>";
			$bcoem_help_body .= "<p>Admins can:</p>";
			$bcoem_help_body .= "<ul>";
			$bcoem_help_body .= "<li>Add and entry to the participant&rsquo;s account by clicking the beer stein icon <span class=\"fa fa-beer\"></span>.</li>";
			$bcoem_help_body .= "<li>Delete the particiapnt&rsquo;s account by clicking the trash can icon <span class=\"fa fa-trash-o\"></span>.</li>";
			$bcoem_help_body .= "<li>Change the participant&rsquo;s user level by clicking the lock icon <span class=\"fa fa-lock\"></span>.</li>";
			$bcoem_help_body .= "<li>Email the participant by clicking the envelope icon <span class=\"fa fa-envelope\"></span>.</li>";
			$bcoem_help_body .= "<li>See the participant&rsquo;s phone number by hovering over the telephone icon <span class=\"fa fa-phone\"></span>.</li>";
			$bcoem_help_body .= "<li>Change the participant&rsquo;s email address by clicking the user icon <span class=\"fa fa-user\"></span>.</li>";
			$bcoem_help_body .= "<li>Download the participant&rsquo;s judge scoresheet labels by clicking the one of the page icons <span class=\"fa fa-file\"></span> (Avery 5160 - Letter) or <span class=\"fa fa-file-text\"></span> (Avery 3422 - A4).</li>";
			$bcoem_help_body .= "</ul>";
			$content = TRUE;	
		}
		
		if (($go == "judging") && ($action == "assign")) {
			$bcoem_help_title .= "Assign Judges or Stewards Help";
			$bcoem_help_body .= "<p>The process to assign judges and stewards is three-fold:</p>";
			$bcoem_help_body .= "<ol>";
			$bcoem_help_body .= "<li>Participants, upon registering, indicate whether they are interested to be a judge, steward, or both.</li>";
			$bcoem_help_body .= "<li>Administrators view the available judges/stewards and assign them as either via the &quot;Assign Participants as Judges&quot; or &quot;Assign Participants as Stewards&quot; links.</li>";
			$bcoem_help_body .= "<li>After tables and flights (if using traditional, non-queued judging) are defined, Administrators then assign judges and stewards to specific tables and flights via the &quot;Assign Judges/Stewards to Tables&quot; link.</li>";
			$bcoem_help_body .= "</ol>";
			$bcoem_help_body .= "<p>Choose whether to assign participants as judges, stewards, or staff. Place a check in the box next to each participant's name that you wish to add to the designated pool.</p>";
			$content = TRUE;	
		}
		
		if (($go == "judging_tables") && ($action == "default")) {
			$bcoem_help_title .= "Tables Help";
			$bcoem_help_body .= "<p>Tables are where administrators group style categories together, defining where their associated entries will be physically judged.</p>";
			$bcoem_help_body .= "<p>To define style categories to a table, there must be at least one entry in each category.</p>";
			$bcoem_help_body .= "<p>Tables cannot be assigned to multiple locations, and each must be defined before it (and all associated flights) can be assigned to rounds.</p>
";
			$bcoem_help_body .= "<p>To assign judges and stewards to a table, participants must first be assigned to the available pool of judges or stewards.</p>";
			$bcoem_help_body .= "<p>Steps for setting up judging tables are defined in the accordion menus on this page.</p>";
			$content = TRUE;
		}
		
		if (($go == "judging_tables") && ($action == "assign") && ($id != "default")) {
			$bcoem_help_title .= "Assign Judges or Stewards to Tables Help";
			$bcoem_help_body .= "<p>Choose judges or stewards to assign to this table.</p>";
			$bcoem_help_body .= "<ul>";
			$bcoem_help_body .= "<li>If using queued judging, click the &quot;Assign to this Table&quot; radio button for each participant you wish to assign to the table.</li>";
			$bcoem_help_body .= "<li>If using traditional or non-queued judging, choose the flight to assign the participant to from the drop-down menu.</li>";
			$bcoem_help_body .= "</ul>";
			$content = TRUE;	
		}
		
		if ($go == "preferences") {
			$bcoem_help_title .= "Website Preferences Help";
			$bcoem_help_body .= "<p>Here, site admins can customize the display and function of the competition site from pre-defined variables. More information about particular preferences can be accessed by clicking the &ldquo;Info&rdquo; buttons below the line items.</p>";
			$content = TRUE;	
		}
		
		if ($go == "judging_preferences") {
			$bcoem_help_title .= "Competition Organization Preferences Help";
			$bcoem_help_body .= "<p>Here, site admins can define how their competition judging will be setup and organized, including flights, rounds, and Best of Show places. More information about particular preferences can be accessed by clicking the &ldquo;Info&rdquo; buttons below the line items.</p>";
			$content = TRUE;	
		}
		
		if ($go == "archive") {
			$bcoem_help_title .= "Archives Help";
			$bcoem_help_body .= "<p>BCOE&amp;M provides administrators the option to archive competition data to access at a later time. This is useful for organizers to be able to track trends from competition to competition using the same installation of BCOE&amp;M.</p>";
			$bcoem_help_body .= "<p>If no archives have been created, administrators can create one by naming providing a name of their archive. The current user, participant, entry, table, scoring, and result data will be archived Admins also have the option to retain sets of data for re-use such as users, participants, custom categories, custom style types, drop-off locations, judging locations, and sponsors.</p>";
			$bcoem_help_body .= "<p><strong class=\"text-danger\">Caution!</strong> Once an archive is created, it cannot be undone easily. It is suggested that administrators archive competition data only when they are ready to begin collecting entries for the competition&rsquo;s next iteration. A warning will pop up before an archive is created.</p>";
			$content = TRUE;	
		}
		
	}
	
	// --------------------- Output Modal --------------------- 
	if ($content) {
		// Build modal window code
		$return .= "<div class=\"modal fade\" id=\"helpModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"helpModalLabel\">";
		$return .= "<div class=\"modal-dialog modal-lg\" role=\"document\">";
		$return .= "<div class=\"modal-content\">";
		$return .= "<div class=\"modal-header bcoem-admin-modal\">";
		$return .= "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
		$return .= "<h4 class=\"modal-title\" id=\"helpModalLabel\">".$bcoem_help_title."</h4>";
		$return .= "</div><!-- ./modal-header -->";
		$return .= "<div class=\"modal-body\">";
		$return .= $bcoem_help_body;
		$return .= "</div><!-- ./modal-body -->";
		$return .= "<div class=\"modal-footer\">";
		$return .= "<button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Close</button>";
		$return .= "</div><!-- ./modal-footer -->";
		$return .= "</div><!-- ./modal-content -->";
		$return .= "</div><!-- ./modal-dialog -->";
		$return .= "</div><!-- ./modal -->";
	}
	
	// --------------------- Output --------------------- 
	if ($content) $output = $return;
	else $output = "";
	return $output;
}


?>