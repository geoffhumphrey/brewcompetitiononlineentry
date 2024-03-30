<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

if (NHC) $base_url = "../";
if ($filter == "stewards") $filter = "S"; 
elseif ($filter == "judges") $filter = "J";

include (DB.'output_assignments.db.php');
$count = round((get_entry_count('received')/($_SESSION['jPrefsFlightEntries'])),0);
$role_replace1 = array("HJ","LJ","MBOS",", ");
$role_replace2 = array("<span class=\"text-primary\"><span class=\"fa fa-gavel\"></span> Table Head Judge</span><br>","<span class=\"text-warning\"><span class=\"fa fa-star\"></span> Lead Judge</span><br>","<span class=\"text-success\"><span class=\"fa fa-trophy\"></span> Mini-BOS Judge</span><br>","");

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
				<?php if (($filter == "J") && ($_SESSION['jPrefsQueued'] == "N")) { ?>
			"aaSorting": [[4,'asc'],[6,'asc'],[7,'asc'],[0,'asc']],
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
				null<?php } ?>
				]
			
			});
		} );
	</script>
<?php } ?>
    <div class="page-header">
        <h1>
		<?php
		if ($filter == "staff") echo "Staff Availability";
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
    			<th width="30%"><?php echo $label_name; ?></th>
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
    </tr>
    </thead>
    <tbody>
    <?php do {
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
		$role = "";
		if (!empty($row_assignments['assignRoles'])) $role .= str_replace($role_replace1,$role_replace2,$row_assignments['assignRoles']);
	?>
    <tr>
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
    </tr>
    <?php } while ($row_assignments = mysqli_fetch_assoc($assignments)); ?>
    </tbody>
    </table>
    <div style="page-break-after: always;"></div>
    <h1>Bull Pen</h1>
    <?php echo not_assigned($filter); ?>
    <?php } else { 
    	echo sprintf("<p class=\"lead\">%s</p>",$output_text_011); 
    	}
    } // end else ?>
<?php } // end if ($view != "sign-in")
else {

	$list_tbody = "";

	foreach ($judging_sessions as $key => $value) {

		$query_brewer = sprintf("SELECT a.id,a.brewerFirstName,a.brewerLastName,a.brewerJudgeID,a.brewerJudgeWaiver,b.uid,b.staff_judge,b.staff_steward,b.staff_staff,b.staff_organizer,c.assignLocation FROM %s a, %s b, %s c WHERE a.uid = b.uid AND a.uid = c.bid",$prefix."brewer",$prefix."staff",$prefix."judging_assignments");
		if (SINGLE) $query_brewer .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
		if ($filter == "S") $query_brewer .= " AND b.staff_steward='1'";
		else $query_brewer .= " AND b.staff_judge='1'"; 
		$query_brewer .= " ORDER BY a.brewerLastName ASC";
		$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
		$row_brewer = mysqli_fetch_assoc($brewer);
		$totalRows_brewer = mysqli_num_rows($brewer);

		$list_tbody = "";


		if ($totalRows_brewer > 0) {

		do {

			if ($row_brewer['assignLocation'] == $value['loc-id']) {
				$list_tbody .= "<tr class=\"show-me-hide-me\">";
				$list_tbody .= "<td nowrap=\"nowrap\">".$row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']."</td>";
				if ($filter == "J") $list_tbody .= "<td>".strtoupper(strtr($row_brewer['brewerJudgeID'],$bjcp_num_replace))."</td>";
				$list_tbody .= "<td>";
				if ($row_brewer['brewerJudgeWaiver'] == "Y") $list_tbody .= $label_yes; 
				else $list_tbody .= $label_no;
				$list_tbody .= "</td>";
				$list_tbody .= "<td>&nbsp;</td>";
				$list_tbody .= "</tr>";
			}

		} while($row_brewer = mysqli_fetch_assoc($brewer));
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$('#sortable<?php echo $key; ?>').dataTable({
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
    <table class="table table-striped table-bordered" id="sortable<?php echo $key; ?>">
    <thead>
    <tr>
    	<th width="30%"><?php echo $label_name; ?></th>
        <?php if ($filter == "J") { ?>
        <th width="20%"><?php echo $label_bjcp_id; ?></th>
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