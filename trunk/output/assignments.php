<?php 
require('output.bootstrap.php'); 
include(INCLUDES.'functions.inc.php');
include(INCLUDES.'url_variables.inc.php');
require(DB.'common.db.php');
include(DB.'admin_common.db.php');
include(INCLUDES.'version.inc.php');
include(INCLUDES.'headers.inc.php');

if ($filter == "stewards") $filter = "S"; else $filter = "J";

$query_assignments = sprintf("SELECT * FROM judging_assignments WHERE assignment='%s'", $filter);
$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
$row_assignments = mysql_fetch_assoc($assignments);
$totalRows_assignments = mysql_num_rows($assignments);

$count = round((get_entry_count()/($row_judging_prefs['jPrefsFlightEntries'])),0); 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if ($tb == "default") { ?><meta http-equiv="refresh" content="0;URL=<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&tb=true"; ?>" /><?php } ?>
<title>Brew Competition Online Entry and Management - brewcompetition.com</title>
<link href="../css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js_includes/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js_includes/jquery.dataTables.js"></script>
</head>
<body <?php if ($tb == "true") echo "onload=\"javascript:window.print()\""; ?>>
<?php if ($view != "sign-in") { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			<?php if ($view == "name") { ?>
			"aaSorting": [[0,'asc'],[3,'asc'],[6,'asc'],[7,'asc']],
			<?php } ?>
			
			<?php if ($view == "table") { ?>
			"aaSorting": [[5,'asc'],[6,'asc'],[3,'asc'],[0,'asc']],
			<?php } ?>
			
			<?php if ($view == "location") { ?>
			"aaSorting": [[3,'asc'],[6,'asc'],[5,'asc'],[0,'asc']],
			<?php } ?>
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
        	<h1><?php if ($filter == "S") echo "Steward "; else echo "Judge "; ?>Assignments <?php if ($view == "name") echo "By Last Name"; elseif ($view == "table") echo "By Table"; elseif ($view == "location") echo "By Location"; else echo ""; ?></h1>
        </div>
    </div>
    <?php if ($totalRows_assignments > 0) { ?>
    <table class="dataTable" id="sortable">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="15%">Name</th>
        <th class="dataHeading bdr1B" width="10%">Phone</th>
        <th class="dataHeading bdr1B" width="10%">Rank</th>
        <th class="dataHeading bdr1B">Location</th>
        <th class="dataHeading bdr1B" width="20%">Table Name</th>
        <th class="dataHeading bdr1B" width="5%">Table #</th>
        <th class="dataHeading bdr1B" width="5%">Round #</th> 
        <th class="dataHeading bdr1B" width="5%">Flight #</th>
        
    </tr>
    </thead>
    <tbody>
    <?php do { 
	$judge_info = explode("^",brewer_info($row_assignments['bid']));
	$table_info = explode("^",get_table_info("none","basic",$row_assignments['assignTable'],$dbTable));
	$location_info = explode("^",get_table_info($row_assignments['assignLocation'],"location","1",$dbTable));
	?>
    <tr>
    	<td class="bdr1B_gray"><?php echo $judge_info['1'].", ".$judge_info['0']; ?></td>
    	<td class="data bdr1B_gray"><?php echo $judge_info['2']; ?></td>
        <td class="data bdr1B_gray"><?php echo $judge_info['3']; ?></td>
        <td class="data bdr1B_gray"><?php echo $location_info['2']."<br>".date_convert($location_info['0'], 3, $row_prefs['prefsDateFormat'])."<br>".$location_info['1']; ?></td>
        <td class="data bdr1B_gray"><?php echo $table_info['1']; ?></td>
        <td class="data bdr1B_gray"><?php echo $table_info['0']; ?></td>
        <td class="data bdr1B_gray"><?php echo $row_assignments['assignRound']; ?></td>
        <td class="data bdr1B_gray"><?php echo $row_assignments['assignFlight']; ?></td>
		
    </tr>
    <?php } while ($row_assignments = mysql_fetch_assoc($assignments)); ?>
    </tbody>
    </table>
    <?php } else { echo "<p>No "; if ($filter == "S") echo "steward "; else echo "judge "; echo "assignments have been made.</p>"; } ?>   
	</div>
</div>
<?php } // end if ($view != "sign-in") 
else { 

$query_brewer = sprintf("SELECT * FROM brewer WHERE brewerAssignment='%s'", $filter);
$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
$row_brewer = mysql_fetch_assoc($brewer);
$totalRows_brewer = mysql_num_rows($brewer);

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
<?php } ?>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
        	<h1><?php echo $row_contest_info['contestName']; ?></h1>
        	<h2><?php if ($filter == "S") echo "Steward "; else echo "Judge "; ?>Sign In</h2>
        </div>
    </div>
    <?php if ($filter == "J") { ?>
    <p>Please be sure to check if your BJCP Judge ID is correct. If it is not, or if you have one and it is not listed, please enter it on the form.</p>
    <p>If your name is not on the list below, sign in on the attached sheet(s).</p>
    <?php } ?>
    <table class="dataTable" id="sortable">
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
    <?php if ($totalRows_brewer > 0) do { ?>
    <tr>
    	<td class="bdr1B_gray" nowrap="nowrap"><?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?></td>
        <?php if ($filter == "J") { ?>
    	<td class="data bdr1B_gray"><?php echo $row_brewer['brewerJudgeID']; ?></td>
        <td class="data bdr1B_gray">Yes / No</td>
        <?php } ?>
        <td class="data bdr1B_gray">&nbsp;</td>
    </tr>
    <?php } while ($row_brewer = mysql_fetch_assoc($brewer));	?>
    </tbody>
    </table>
    <div style="page-break-after:always;"></div>
    <div id="header">	
		<div id="header-inner">
        	<h2><?php if ($filter == "S") echo "Steward "; else echo "Judge "; ?>Sign In</h2>
        </div>
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
   
    <table class="dataTable" id="sortable2">
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
    	<td class="bdr1B_gray" nowrap="nowrap" width="30%"></td>
        <?php if ($filter == "J") { ?>
    	<td class="data bdr1B_gray" width="20%">&nbsp;</td>
        <td class="data bdr1B_gray" width="10%">Yes / No</td>
        <?php } ?>
        <td class="data bdr1B_gray">&nbsp;</td>
    </tr>
	<?php } ?>
    </tbody>
    </table>
    </div>
</div>
<?php } ?>
</body>
</html>