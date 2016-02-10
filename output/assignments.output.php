<?php 

if (NHC) $base_url = "../";
if ($filter == "stewards") $filter = "S"; else $filter = "J";

include(DB.'output_assignments.db.php');
$count = round((get_entry_count('received')/($_SESSION['jPrefsFlightEntries'])),0); 

?>

<?php if ($view != "sign-in") { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			<?php if ($view == "default") { ?>
			"aaSorting": [[0,'asc'],[2,'asc'],[5,'asc']],
			<?php } ?>
			
			<?php if ($view == "table") { ?>
			"aaSorting": [[3,'asc'],[4,'asc'],[2,'asc'],[0,'asc']],
			<?php } ?>
			
			<?php if ($view == "location") { ?>
			"aaSorting": [[2,'asc'],[4,'asc'],[5,'asc'],[0,'asc']],
			<?php } ?>
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>,
				{ "asSorting": [  ] }<?php } ?>
				]
			} );
		} );
	</script>    
    <div class="page-header">
        <h1><?php if ($filter == "S") echo "Steward "; else echo "Judge "; ?>Assignments <?php if ($view == "default") echo "By Last Name"; elseif ($view == "table") echo "By Table"; elseif ($view == "location") echo "By Location"; else echo ""; ?></h1>
    </div>
    
    <?php if ($totalRows_assignments > 0) { ?>
    <table class="table table-striped table-bordered" id="sortable">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="15%">Name</th>
        <th class="dataHeading bdr1B" width="10%">Rank</th>
        <th class="dataHeading bdr1B">Location</th>
        <th class="dataHeading bdr1B" width="5%">Table</th>
        <th class="dataHeading bdr1B" width="20%">Table Name</th>
        <th class="dataHeading bdr1B" width="5%">Round</th>
        <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
        <th class="dataHeading bdr1B" width="5%">Flight #</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php do { 
	$judge_info = explode("^",brewer_info($row_assignments['bid']));
	$table_info = explode("^",get_table_info("none","basic",$row_assignments['assignTable'],$dbTable,"default"));
	$location_info = explode("^",get_table_info($row_assignments['assignLocation'],"location","1",$dbTable,"default"));
	$judge_ranks = str_replace(",",", ",$judge_info['3']);
	?>
    <tr>
    	<td class="bdr1B_gray"><?php echo ucfirst(strtolower($judge_info['1'])).", ".ucfirst(strtolower($judge_info['0'])); ?></td>
        <td class="data bdr1B_gray"><?php echo $judge_ranks; ?></td>
        <td class="data bdr1B_gray"><?php echo table_location($row_assignments['assignTable'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); ?></td>
        <td class="data bdr1B_gray"><?php echo $table_info['0']; ?></td>
        <td class="data bdr1B_gray"><?php echo $table_info['1']; ?></td>
        <td class="data bdr1B_gray"><?php echo $row_assignments['assignRound']; ?></td>
        <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
        <td class="data bdr1B_gray"><?php echo $row_assignments['assignFlight']; ?></td>
		<?php } ?>
    </tr>
    <?php } while ($row_assignments = mysql_fetch_assoc($assignments)); ?>
    </tbody>
    </table>
    <?php } else { echo "<p class=\"lead\">No "; if ($filter == "S") echo "steward "; else echo "judge "; echo "assignments have been made.</p>"; } ?>   
<?php } // end if ($view != "sign-in") 
else { 
if ($totalRows_brewer > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			} );
		} );
	</script>
<?php } ?>
    <div class="page-header">
        <h1><?php echo $_SESSION['contestName']; if ($filter == "S") echo " Steward "; else echo " Judge "; ?>Sign In</h2>
    </div>
    <p>Please be sure to check if your BJCP Judge ID is correct. If it is not, or if you have one and it is not listed, please enter it on the form.</p>
    <p>If your name is not on the list below, sign in on the attached sheet(s).</p>
    <table class="table table-striped table-bordered" id="sortable">
    <thead>
    <tr>
    	<th width="30%">Name</th>
        <th width="20%">Judge ID</th>
        <?php if ($filter == "J") { ?>
        <th width="10%">Signed Waiver?</th>
        <?php } ?>
        <th>Signature</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($totalRows_brewer > 0) do { ?>
    <tr>
    	<td nowrap="nowrap"><?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?></td>
        <?php if ($filter == "J") { ?>
    	<td><?php echo strtoupper(strtr($row_brewer['brewerJudgeID'],$bjcp_num_replace)); ?></td>
        <td>Yes / No</td>
        <?php } ?>
        <td>&nbsp;</td>
    </tr>
    <?php } while ($row_brewer = mysql_fetch_assoc($brewer));	?>
    </tbody>
    </table>
    <div style="page-break-after:always;"></div>
    <div class="page-header">
        <h1><?php echo $_SESSION['contestName']; if ($filter == "S") echo " Steward "; else echo " Judge "; ?>Sign In</h2>
    </div>
     <?php if ($filter == "J") { ?>
    <p>To receive judging credit, please be sure to enter your BJCP Judge ID correctly and legibly.</p>
    <?php } ?>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable2').dataTable( {
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
				{ "asSorting": [  ] },
				<?php } ?>
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
   
    <table class="table table-striped table-bordered" id="sortable2">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="30%">Name</th>
        <?php if ($filter == "J") { ?>
        <th class="dataHeading bdr1B" width="20%">Judge ID</th>
        <th class="dataHeading bdr1B" width="10%">Signed Waiver?</th>
        <?php } ?>
        <th class="dataHeading bdr1B">Signature</th>
    </tr>
    </thead>
    <tbody>
    <?php for($i=1; $i<$count+1; $i++) { ?>
	<tr>
    	<td nowrap="nowrap" width="30%"></td>
        <?php if ($filter == "J") { ?>
    	<td width="20%">&nbsp;</td>
        <td width="10%">Yes / No</td>
        <?php } ?>
        <td>&nbsp;</td>
    </tr>
	<?php } ?>
    </tbody>
    </table>
    
<?php } ?>