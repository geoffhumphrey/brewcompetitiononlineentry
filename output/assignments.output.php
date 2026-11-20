<?php
if (NHC) $base_url = "../";
if ($filter == "stewards") $filter = "S"; 
elseif ($filter == "judges") $filter = "J";

include (DB.'output_assignments.db.php');
$count = round((get_entry_count('received')/($_SESSION['jPrefsFlightEntries'])),0);
$role_replace1 = array("HJ","TL","MBOS",", ");
$role_replace2 = array("<span class=\"fa fa-gavel\"></span> Table Head Judge<br>","<span class=\"fa fa-star\"></span> Team Lead<br>","<span class=\"fa fa-trophy\"></span> Mini-BOS Judge<br>","");

// Only sort by Team when teams are actually defined for this report - the
// "by table" view's combined list spans every table, so this is checked once
// up front rather than per-table like output/table_cards.output.php does.
$assignments_have_teams = false;
if ($filter == "J") {
	foreach ($rows_assignments as $row_assignments_check) {
		if (!empty($row_assignments_check['assignTeam'])) { $assignments_have_teams = true; break; }
	}
}

if ($view != "sign-in") {
include (LIB.'admin.lib.php');

if ($filter == "staff") {
?>
<script type="text/javascript" language="javascript">
 	$(document).ready(function() {
		$('#sortable').dataTable({
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			<?php if ($view == "name") { ?>
			"aaSorting": [[0,'asc'],[1,'asc']],
			<?php } else { ?>
			"aaSorting": [[1,'asc'],[0,'asc']],
			<?php } ?>
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
			]
		});
	});
</script>
<?php } else { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable({
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,

			<?php if (($view == "default") || ($view == "name")) { ?>
                <?php if (($filter == "J") && ($_SESSION['jPrefsQueued'] == "N")) { ?>
			"aaSorting": [[0,'asc'],[2,'asc'],[3,'asc'],[5,'asc']],
				<?php } elseif (($filter == "J") && ($_SESSION['jPrefsQueued'] == "Y")) { ?>
			"aaSorting": [[0,'asc'],[3,'asc'],[4,'asc'],[6,'asc']],		
                <?php } else { ?>
            "aaSorting": [[0,'asc'],[1,'asc'],[2,'asc'],[4,'asc']],
                <?php } ?>
			<?php } ?>

			<?php if ($view == "table") { ?>
				<?php if (($filter == "J") && ($_SESSION['jPrefsQueued'] == "N") && ($assignments_have_teams)) { ?>
			"aaSorting": [[4,'asc'],[8,'asc'],[0,'asc']],
				<?php } elseif (($filter == "J") && ($_SESSION['jPrefsQueued'] == "N")) { ?>
			"aaSorting": [[4,'asc'],[6,'asc'],[7,'asc'],[0,'asc']],
				<?php } elseif (($filter == "J") && ($_SESSION['jPrefsQueued'] == "Y") && ($assignments_have_teams)) { ?>
			"aaSorting": [[4,'asc'],[7,'asc'],[0,'asc']],
				<?php } elseif (($filter == "J") && ($_SESSION['jPrefsQueued'] == "Y")) { ?>
			"aaSorting": [[4,'asc'],[5,'asc'],[0,'asc']],
                <?php } else { ?>
			"aaSorting": [[2,'asc'],[0,'asc']],
				<?php } ?>
			<?php } ?>

			<?php if ($view == "location") { ?>
			"aaSorting": [[3,'asc'],[0,'asc']],
			<?php } ?>
			"bProcessing" : false,
			
			"aoColumns": [
				null,
				<?php if ($filter == "J") { ?>
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				<?php } ?>
				null,
				null,
				null,
				null<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>,
				null<?php } ?><?php if (($filter == "J") && ($view == "table")) { ?>,
				null<?php } ?>
				]

			});
		} );
	</script>
<?php } ?>
    <div class="page-header">
        <h1>
		<?php
		if ($filter == "staff") echo $label_staff_availability;
		else {
			if ($filter == "S") echo sprintf("%s ",$label_steward);
			else echo sprintf("%s ",$label_judge);
			echo $label_assignments;
			if ($view == "table") echo sprintf(" %s",$label_by_table);
			elseif ($view == "location") echo sprintf(" %s",$label_by_location);
		}
        echo "<br><small>".$_SESSION['contestName']."</small>";
		?>
        </h1>
        <?php if ($location != "default") {

        	$location_header = explode("^",get_table_info($location,"location","1",$dbTable,"default"));
			//print_r($location_header);
			$location_start_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $location_header[0], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
        	echo sprintf("<h4>%s: %s</h4>", $location_header[2],$location_start_date);

        	} ?>
    </div>
    <?php if ($filter == "staff") { ?>

    <?php if (!empty($tbody_staff)) { ?>
    <p>Please note that the following people indicated that they are <em>available</em> to be a staff member for one or more non-judging locations. They may or may not be <em>assigned</em> as a staff member in the application by an Administrator, which is required for BJCP reporting purposes.</p>
    <table class="table table-striped table-bordered" id="sortable">
    	<thead>
    		<tr>
    			<th width="20%"><?php echo $label_name; ?></th>
    			<th><?php echo $label_session; ?></th>
    		</tr>
    	</thead>
    	<tbody>
    		<?php echo $tbody_staff; ?>
    	</tbody>
    </table>
	<?php } else echo sprintf("<p class=\"lead\">%s</p>",$output_text_011); ?>

    <?php } else {
    if ($totalRows_assignments > 0) { ?>
    <table class="table table-striped table-bordered" id="sortable">
    <thead>
    <tr>
    	<th width="10%"><?php echo $label_name; ?></th>
    	<?php if ($filter == "J") { ?>
        <th width="10%">Role</th>
        <?php } ?>
        <?php if ($filter == "J") { ?>
        <th width="10%">Rank</th>
        <?php } ?>
        <th><?php echo $label_session; ?></th>
        <?php if ($filter != "staff") { ?>
        <th width="5%"><?php echo $label_table; ?></th>
        <th width="20%"><?php echo $label_name; ?></th>
        <th width="5%"><?php echo $label_round; ?></th>
        <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
        <th width="5%"><?php echo $label_flight; ?></th>
        <?php } ?>
        <?php } ?>
        <?php if (($filter == "J") && ($view == "table")) { ?>
        <th width="5%">Team</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($rows_assignments as $row_assignments) {
    	$judge_info = "";
    	$table_info = "";
    	$location_info = "";
    	$judge_rank = "";
		$judge_info = explode("^",brewer_info($row_assignments['bid']));
		$table_info = explode("^",get_table_info("none","basic",$row_assignments['assignTable'],$dbTable,"default"));
		$location_info = explode("^",get_table_info($row_assignments['assignLocation'],"location","1",$dbTable,"default"));
		$judge_rank = explode(",",$judge_info[3]);
	    $cert_add = "";
	    if ($judge_info[16] != "&nbsp;") $cert_add .= ", ".$judge_info[16];
	    if (in_array("Master Cicerone", $judge_rank)) $cert_add .= ", Master Cicerone";
	    if (in_array("Advanced Cicerone", $judge_rank)) $cert_add .= ", Advanced Cicerone";
	    if (in_array("Certified Cicerone", $judge_rank)) $cert_add .= ", Certified Cicerone";
	    if (in_array("Professional Brewer", $judge_rank)) $cert_add .= ", Professional Brewer";
	    if (in_array("Certified Cider Guide", $judge_rank)) $cert_add .= ", Certified Cider Guide";
	    if (in_array("Certified Pommelier", $judge_rank)) $cert_add .= ", Certified Pommelier";
		$role = "";
		if (!empty($row_assignments['assignRoles'])) $role .= str_replace($role_replace1,$role_replace2,$row_assignments['assignRoles']);
		$team_class = (($filter == "J") && ($view == "table")) ? team_color_class($row_assignments['assignTeam']) : "";
	?>
    <tr<?php if (!empty($team_class)) echo " class=\"".$team_class."\""; ?>>
    	<td nowrap><?php echo sprintf("<strong>%s, %s</strong><br><small>%s</small>", $judge_info[1], $judge_info[0], $judge_info[6]); ?></td>
    	<?php if ($filter == "J") { ?>
        <td nowrap><?php if (!empty($role)) echo $role; ?></td>
		<?php } ?>
        <?php if ($filter == "J") { ?>
        	<td><?php if (isset($judge_rank[0])) echo $judge_rank[0]; echo $cert_add; ?></td>
        <?php } ?>
        <td><?php echo table_location($row_assignments['assignTable'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); ?></td>
        <td><?php if (isset($table_info[0])) echo $table_info[0]; ?></td>
        <td><?php if (isset($table_info[1])) echo $table_info[1]; ?></td>
        <td><?php echo $row_assignments['assignRound']; ?></td>
        <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
        <td><?php echo $row_assignments['assignFlight']; ?></td>
		<?php } ?>
        <?php if (($filter == "J") && ($view == "table")) { ?>
        <td data-order="<?php echo (int)$row_assignments['assignTeam']; ?>"><?php if (!empty($row_assignments['assignTeam'])) echo "Team ".h($row_assignments['assignTeam']); ?></td>
        <?php } ?>
    </tr>
    <?php } ?>
    </tbody>
    </table>
    <div style="page-break-after: always;"></div>
    <h1>Bull Pen</h1>
    <?php echo not_assigned($filter, $location); ?>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('.sortable-J, .sortable-S').each(function() {
			$(this).dataTable({
				"bPaginate" : false,
				"sDom": 'rt',
				"bStateSave" : false,
				"bLengthChange" : false,
				"aaSorting": [[0,'asc']],
				"bProcessing" : false,
				"aoColumns": [
					{ "asSorting": [  ] },
					{ "asSorting": [  ] }
					]
				});
		});
	 });
    </script>
    <?php } else {
    	echo sprintf("<p class=\"lead\">%s</p>",$output_text_011); 
    	}
    } // end else ?>
<?php } // end if ($view != "sign-in")
else {

	$list_tbody = "";

	// Issue #1775/#1774: judges/stewards had no way to see their table
	// assignment on the sign-in sheet, so they had to walk table to table to
	// find their name. $rows_assignments (loaded by output_assignments.db.php,
	// unfiltered by location/table for this view) is batched here into one
	// bid+location keyed map instead of a per-brewer query, so each sign-in
	// row can list every table that person is assigned to within this sheet's
	// location, plus (for judges) their Table Head Judge/Mini-BOS Judge/Team
	// Lead roles - mirroring the role-token convention output/pullsheets.output.php
	// already uses for its judge roster.
	$sign_in_assignments_by_bid_loc_ps = array();
	foreach ($rows_assignments as $row_sign_in_assign_ps) {
		$sign_in_assignments_by_bid_loc_ps[$row_sign_in_assign_ps['bid']."|".$row_sign_in_assign_ps['assignLocation']][] = $row_sign_in_assign_ps;
	}

	foreach ($judging_sessions as $key => $value) {

		$query_brewer = "SELECT a.id,a.brewerFirstName,a.brewerLastName,a.brewerJudgeID,a.brewerJudgeWaiver,b.uid,b.staff_judge,b.staff_steward,b.staff_staff,b.staff_organizer,c.assignLocation FROM ".$prefix."brewer"." a, ".$prefix."staff"." b, ".$prefix."judging_assignments"." c WHERE a.uid = b.uid AND a.uid = c.bid AND c.assignLocation = ?";
		$params_brewer = array($value['loc-id']);
		if (SINGLE) { $query_brewer .= " AND comp_id=?"; $params_brewer[] = $_SESSION['comp_id']; }
		if ($filter == "S") $query_brewer .= " AND b.staff_steward='1'";
		else $query_brewer .= " AND b.staff_judge='1'";
		// A judge/steward assigned to more than one flight or table within this
		// location matches judging_assignments more than once, which without
		// grouping duplicates their row on the sign-in sheet once per
		// assignment - a sign-in sheet only needs one signature line per
		// person. Filtering assignLocation in SQL (rather than the previous
		// fetch-everyone-then-filter-per-location loop) is what makes this
		// grouping safe for someone assigned across multiple locations too -
		// each location's query only ever sees that location's own rows to
		// collapse, never a different location's for the same person.
		$query_brewer .= " GROUP BY a.uid";
		$query_brewer .= " ORDER BY a.brewerLastName ASC";
		$rows_brewer = $db_conn->rawQuery($query_brewer, $params_brewer);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;

		$list_tbody = "";


		if ($totalRows_brewer > 0) {

		foreach ($rows_brewer as $row_brewer) {

				$sila_rows_ps = $sign_in_assignments_by_bid_loc_ps[$row_brewer['uid']."|".$value['loc-id']] ?? array();
				$sila_table_names_ps = array();
				$sila_role_tokens_ps = array();
				foreach ($sila_rows_ps as $sila_row_ps) {
					if (!empty($sila_row_ps['assignTable'])) {
						$sila_table_info_ps = explode("^", get_table_info("none","basic",$sila_row_ps['assignTable'],$dbTable,"default"));
						$sila_table_label_ps = (!empty($sila_table_info_ps[0])) ? sprintf("%s %s", $label_table, $sila_table_info_ps[0]) : "";
						if ((!empty($sila_table_label_ps)) && (!in_array($sila_table_label_ps, $sila_table_names_ps))) $sila_table_names_ps[] = $sila_table_label_ps;
					}
					if (!empty($sila_row_ps['assignRoles'])) {
						if ((strpos($sila_row_ps['assignRoles'],"HJ") !== FALSE) && (!in_array("HJ",$sila_role_tokens_ps))) $sila_role_tokens_ps[] = "HJ";
						if ((strpos($sila_row_ps['assignRoles'],"MBOS") !== FALSE) && (!in_array("MBOS",$sila_role_tokens_ps))) $sila_role_tokens_ps[] = "MBOS";
						if ((strpos($sila_row_ps['assignRoles'],"TL") !== FALSE) && (!in_array("TL",$sila_role_tokens_ps))) $sila_role_tokens_ps[] = "TL";
					}
				}
				$sila_table_display_ps = implode(", ", $sila_table_names_ps);
				$sila_role_display_ps = (!empty($sila_role_tokens_ps)) ? str_replace($role_replace1, $role_replace2, implode(", ", $sila_role_tokens_ps)) : "";

				$list_tbody .= "<tr class=\"show-me-hide-me\">";
				$list_tbody .= "<td nowrap=\"nowrap\">".$row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName'];
				if ((!empty($sila_role_display_ps))) $list_tbody .= "<br><small style=\"font-size:.9em;\">".$sila_role_display_ps."</small>";
				$list_tbody .= "</td>";
				$list_tbody .= "<td>".((!empty($sila_table_display_ps)) ? $sila_table_display_ps : "&nbsp;")."</td>";
				if ($filter == "J") $list_tbody .= "<td>".strtoupper(strtr($row_brewer['brewerJudgeID'],$bjcp_num_replace))."</td>";
				$list_tbody .= "<td>";
				if ($row_brewer['brewerJudgeWaiver'] == "Y") $list_tbody .= $label_yes;
				else $list_tbody .= $label_no;
				$list_tbody .= "</td>";
				$list_tbody .= "<td>&nbsp;</td>";
				$list_tbody .= "</tr>";

		}
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	// $key is judgingLocName (purify()-only, no sterilize()), used as an id below - h()
	// applied consistently here and at the matching id="..." so the selector still matches.
	$('#sortable<?php echo h($key); ?>').dataTable({
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[0,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			{ "asSorting": [  ] },
			{ "asSorting": [  ] },
			<?php if ($filter == "J") { ?>
			{ "asSorting": [  ] },
			<?php } ?>
			{ "asSorting": [  ] },
			{ "asSorting": [  ] }
			]
		});
	});
</script>
<?php } // end if ($totalRows_brewer > 0) ?>
<?php if (!empty($list_tbody)) { ?>
    <div class="page-header">
        <h1><?php echo $_SESSION['contestName']; if ($filter == "S") echo sprintf(" %s ",$label_steward); else echo sprintf(" %s ",$label_judge); echo $label_sign_in; ?><br><small><em><?php echo $value['loc-name']; ?></em></small></h1>
    </div>
    <p><?php echo $output_text_008; ?></p>
    <p><?php echo $output_text_009; ?></p>
    <table class="table table-striped table-bordered" id="sortable<?php echo h($key); ?>">
    <thead>
    <tr>
    	<th width="25%"><?php echo $label_name; ?></th>
    	<th width="20%"><?php echo $label_table; ?></th>
        <?php if ($filter == "J") { ?>
        <th width="15%"><?php echo $label_bjcp_id; ?></th>
        <?php } ?>
        <th width="10%"><?php echo $label_waiver; ?></th>
        <th>Signature</th>
    </tr>
    </thead>
    <tbody>
    <?php echo $list_tbody; ?>
    </tbody>
    </table>
    <div style="page-break-after:always;"></div>
    <?php } // end foreach ?>
<?php } ?>	
    <div class="page-header">
        <h1><?php echo $_SESSION['contestName']; if ($filter == "S") echo sprintf(" %s ",$label_steward); else echo sprintf(" %s ",$label_judge); echo $label_sign_in; ?></h1>
    </div>
     <?php if ($filter == "J") { ?>
    <p><?php echo $output_text_010; ?></p>
    <?php } ?>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable999').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				<?php if ($filter == "J") { ?>
				{ "asSorting": [  ] },
				<?php } ?>
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>

    <table class="table table-striped table-bordered" id="sortable2">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="30%"><?php echo $label_name; ?></th>
        <?php if ($filter == "J") { ?>
        <th class="dataHeading bdr1B" width="20%"><?php echo $label_bjcp_id; ?></th>
        <?php } ?>
        <th class="dataHeading bdr1B" width="10%"><?php echo $label_waiver; ?></th>
        <th class="dataHeading bdr1B"><?php echo $label_signature; ?></th>
    </tr>
    </thead>
    <tbody>
    <?php for($i=1; $i<$count+1; $i++) { ?>
	<tr>
    	<td nowrap="nowrap" width="30%"></td>
        <?php if ($filter == "J") { ?>
    	<td width="20%">&nbsp;</td>
        <?php } ?>
        <td width="10%"><?php echo sprintf("%s / %s",$label_yes,$label_no); ?></td>
        <td>&nbsp;</td>
    </tr>
	<?php } ?>
    </tbody>
    </table>

<?php } ?>