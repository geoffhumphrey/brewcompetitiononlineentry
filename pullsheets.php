<?php 
require ('Connections/config.php');
require ('includes/authentication_nav.inc.php');  session_start(); 
require ('includes/url_variables.inc.php');
require ('includes/db_connect.inc.php');
include ('includes/plug-ins.inc.php');
include ('includes/headers.inc.php');
$today = date('Y-m-d');
$deadline = $row_contest_info['contestRegistrationDeadline'];
$tb = "default";
if (isset($_GET['tb'])) {
  $tb = (get_magic_quotes_gpc()) ? $_GET['tb'] : addslashes($_GET['tb']);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if ($tb == "default") { ?><meta http-equiv="refresh" content="0;URL=<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&tb=true"; ?>" /><?php } ?>
<title><?php echo $row_contest_info['contestName']; ?> organized by <?php echo $row_contest_info['contestHost']; ?></title>
<link href="css/print.css" rel="stylesheet" type="text/css" />
</head>
<body <?php if ($tb == "true") echo "onload=\"javascript:window.print()\""; ?>>
<?php if ($id == "default") do { ?>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
        	<h1>Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']." - ". get_table_info(1,"count_total",$row_tables['id'])." Entries"; ?></h1>
            <?php if ($row_tables['tableLocation'] != "") { 
			$query_location = sprintf("SELECT * FROM judging_locations WHERE id='%s'", $row_tables['tableLocation']);
			$location = mysql_query($query_location, $brewing) or die(mysql_error());
			$row_location = mysql_fetch_assoc($location);
			?>
            <h2><?php echo $row_location['judgingLocName'].", ".dateconvert($row_location['judgingDate'], 3)." - ".$row_location['judgingTime']; ?></h2>
            <?php } ?>
        </div>
	</div>
    <table class="dataTable" width="100%">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="30px">Pull Order</th>
        <th class="dataHeading bdr1B">Entry No.</th>
        <th class="dataHeading bdr1B">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables['tableStyles']); 
	
	foreach (array_unique($a) as $value) {
		$query_styles = sprintf("SELECT brewStyle FROM styles WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo FROM brewing WHERE brewStyle='%s' AND brewPaid='Y' AND brewReceived='Y' ORDER BY id", $row_styles['brewStyle']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
		do {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><?php echo $row_entries['id']; ?></td>
        <td class="data bdr1B_gray"><?php echo style_convert($row_entries['brewCategorySort'],1)."<br>".$style." ".$row_entries['brewStyle']; if (style_convert($style,"3")) echo "<br><strong>Special Ingredients/Classic Style:</strong><span class='data'>".$row_entries['brewInfo']."</span>"; ?></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
    </tr>
    <?php } while ($row_entries = mysql_fetch_assoc($entries));
	mysql_free_result($styles);
	mysql_free_result($entries);
	} // end foreach ?>
    </tbody>
    </table>
    </div>
</div>
<div style="page-break-after:always;"></div>
<?php } while ($row_tables = mysql_fetch_assoc($tables)); 
else { ?>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
        	<h1>Table <?php echo $row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']." - ". get_table_info(1,"count_total",$row_tables_edit['id'])." Entries"; ?></h1>
            <?php if ($row_tables_edit['tableLocation'] != "") { 
			$query_location = sprintf("SELECT * FROM judging_locations WHERE id='%s'", $row_tables_edit['tableLocation']);
			$location = mysql_query($query_location, $brewing) or die(mysql_error());
			$row_location = mysql_fetch_assoc($location);
			?>
            <h2><?php echo $row_location['judgingLocName'].", ".dateconvert($row_location['judgingDate'], 3)." - ".$row_location['judgingTime']; ?></h2>
            <?php } ?>
        </div>
	</div>
    <table class="dataTable" width="100%">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="30px">Pull Order</th>
        <th class="dataHeading bdr1B">Entry No.</th>
        <th class="dataHeading bdr1B">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables_edit['tableStyles']); 
	
	foreach (array_unique($a) as $value) {
		$query_styles = sprintf("SELECT brewStyle FROM styles WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id,brewStyle,brewCategory,brewCategorySort,brewSubCategory,brewInfo FROM brewing WHERE brewStyle='%s' ORDER BY id", $row_styles['brewStyle']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
		do {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><?php echo $row_entries['id']; ?></td>
        <td class="data bdr1B_gray"><?php echo "<em>".style_convert($row_entries['brewCategorySort'],1)."</em><br>".$style." ".$row_entries['brewStyle']; if (style_convert($style,"3")) echo "<p style='margin-top: 5px;'><strong>Special Ingredients/Classic Style:</strong><br>".$row_entries['brewInfo']."</p>"; ?></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
    </tr>
    <?php } while ($row_entries = mysql_fetch_assoc($entries));
	mysql_free_result($styles);
	mysql_free_result($entries);
	} // end foreach ?>
    </tbody>
    </table>
    </div>
</div>
<?php } ?>
</body>
</html>