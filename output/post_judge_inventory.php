<?php 
/**
 * Module:      report_tempate.php 
 * Description: Template for custom reports.
 * 
 */

require('../paths.php');
require(CONFIG.'bootstrap.php');
include(DB.'output_post_judge_inventory.db.php');
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
if (NHC) $base_url = "../";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['contestName']; ?> organized by <?php echo $_SESSION['contestHost']; ?></title>
<link href="<?php echo $base_url; ?>css/print.css" rel="stylesheet" type="text/css" />
<!-- jquery plugin - required for use with DataTables, FancyBox, DatePicker, TimePicker etc. -->
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.js"></script>
</head>
<!-- For use with the DataTables jquery plugin -->
<link rel="stylesheet" href="<?php echo $base_url; ?>css/sorting.css" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript">
// The following is for demonstration purposes only. 
// Complete documentation and usage at http://www.datatables.net
	$(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[3,'asc']],
			"aoColumns": [
				<?php if ($section == "scores") { ?>null,<?php } ?>
				null,
				null,
				null,
				null,
				null
				]
		} );
	} );
</script>
</head>
<body>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner"><h1><?php echo $_SESSION['contestName']; ?> Post-Judging Entry Inventory</h1></div>
	</div><!-- end header -->
    <!-- BEGIN content -->
    <!-- DataTables Table Format -->
    <table class="dataTable" id="sortable">
    <thead>
    	<tr>
        	<th class="bdr1B" width="5%" nowrap="nowrap">Entry #</th>
            <th class="bdr1B" width="5%" nowrap="nowrap">Judging #</th>
            <th class="bdr1B">Entry Name</th>
            <th class="bdr1B" width="25%">Category</th>
            <th class="bdr1B" width="40%">Special Ingredients / Classic Style</th>
            <?php if ($section == "scores") { ?> 
            <th class="bdr1B" width="5%" nowrap="nowrap">Score</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
    <?php do { ?>
    	<tr>
        	<td class="data bdr1B_gray"><?php echo sprintf("%04s",$row_post_inventory['id']); ?></td> 
            <td class="data bdr1B_gray"><?php echo readable_judging_number($row_post_inventory['brewCategory'],$row_post_inventory['brewJudgingNumber']); ?></td>
            <td class="data bdr1B_gray"><?php echo $row_post_inventory['brewName']; ?></td> 
            <td class="data bdr1B_gray"><?php echo $row_post_inventory['brewCategorySort'].$row_post_inventory['brewSubCategory'].": ".$row_post_inventory['brewStyle']; ?></td>
            <td class="data bdr1B_gray"><?php echo $row_post_inventory['brewInfo']; ?></td> 
            <?php if ($section == "scores") { ?> 
            <td class="data bdr1B_gray"><?php echo $row_post_inventory['scoreEntry']; ?></td>
            <?php } ?>
        </tr>
    <?php } while ($row_post_inventory = mysql_fetch_assoc($post_inventory)); ?>
    </tbody>
    </table>
    <!-- END page content -->
    </div><!-- end content-inner -->
</div><!-- end content -->
<div id="footer">
	<div id="footer-inner">Printed <?php echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "long", "date-time"); ?>.</div>
</div>
</body>
</html>
<?php if (!$fx) { ?>
<script type="text/javascript">
function selfPrint(){
    self.focus();
    self.print();
}
setTimeout('selfPrint()',3000);
html.push(''); 
</script>
<?php } ?>
<?php } else echo "<p>Not available.</p>"; ?>