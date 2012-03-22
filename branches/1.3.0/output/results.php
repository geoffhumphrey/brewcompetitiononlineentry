<?php 
session_start(); 
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(DB.'common.db.php');
require(DB.'admin_common.db.php');
require(INCLUDES.'version.inc.php');
require(INCLUDES.'headers.inc.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if ($tb == "default") { ?><meta http-equiv="refresh" content="0;URL=<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&tb=true"; ?>" /><?php } ?>
<title>Brew Competition Online Entry and Management - brewcompetition.com</title>
<link href="../css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js_includes/jquery.dataTables.js"></script>
</head>
<body <?php if ($tb == "true") echo "onload=\"javascript:window.print()\""; ?>>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
			<h1><?php echo $row_contest_info['contestName']." Results"; ?></h1>
        </div>
	</div>
<?php if (($go == "judging_scores") && ($action == "default"))  { ?>
<?php do { 
$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
?>
    <div id="header">	
		<div id="header-inner">
        	<h2>Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']." (".$entry_count." Entries)"; ?></h2>
        </div>
	</div>
    <?php if ($entry_count > 0) { ?>
     <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $row_tables['id']; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc'],[1,'asc'],[2,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }<?php if ($filter == "scores") { ?>,
				{ "asSorting": [  ] }
				<?php } ?>
				]
			} );
		} );
	</script>
    <table class="dataTable" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
    <tr>
    	<th width="1%" class="dataHeading bdr1B">Place</th>
        <th width="20%" class="dataHeading bdr1B">Brewer(s)</th>
        <th class="dataHeading bdr1B">Entry Name</th>
        <th width="25%" class="dataHeading bdr1B">Style</th>
        <th width="25%" class="dataHeading bdr1B">Club</th>
        <?php if ($filter == "scores") { ?>
        <th width="1%" class="dataHeading bdr1B">Score</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php 
		$query_scores = sprintf("SELECT * FROM %s WHERE scoreTable='%s'", $go, $row_tables['id']);
		if ($view == "winners") {
			$query_scores .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5')";	
			}
		$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
		$row_scores = mysql_fetch_assoc($scores);
		$totalRows_scores = mysql_num_rows($scores);
		do { 
			$query_entries = sprintf("SELECT id,brewBrewerID,brewName,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewBrewerFirstName,brewBrewerLastName,brewCoBrewer FROM brewing WHERE id='%s'", $row_scores['eid']);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
		
			$query_styles = sprintf("SELECT style_name FROM $styles_active WHERE id='%s'", $value);
			$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
			$row_styles = mysql_fetch_assoc($styles);
	?>
    <tr>
        <td class="data bdr1B_gray"><?php echo display_place($row_scores['scorePlace'],1); ?></td>
        <td class="data bdr1B_gray">
		<?php 
			$query_brewer = sprintf("SELECT brewerLastName,brewerFirstName,brewerClubs FROM brewer WHERE uid='%s'", $row_entries['brewBrewerID']);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_assoc($brewer);
		echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; if ($row_entries['brewCoBrewer'] != "") echo "<br>".$row_entries['brewCoBrewer']; ?>
        </td>
        <td class="data bdr1B_gray"><?php echo $row_entries['brewName']; ?></td>
        <td class="data bdr1B_gray"><?php echo $row_entries['brewStyle']; ?></td>
        <td class="data bdr1B_gray"><?php echo $row_brewer['brewerClubs']; ?></td>
        <?php if ($filter == "scores") { ?>
        <td class="data bdr1B_gray"><?php echo $row_scores['scoreEntry']; ?></td>
        <?php } ?>
    </tr>
    <?php 
			mysql_free_result($styles);
			mysql_free_result($entries);
		} while ($row_scores = mysql_fetch_assoc($scores)); ?>
    </tbody>
    </table>
    <?php } ?>
<div <?php if ($view == "winners") echo "style='margin-bottom: 4em;'"; else echo "style='page-break-after:always;'"; ?>></div>
<?php } while ($row_tables = mysql_fetch_assoc($tables)); ?>
<?php } ?>
<?php if (($go == "judging_scores_bos") && ($action == "default")) { 
do { $a[] = $row_style_types['id']; } while ($row_style_types = mysql_fetch_assoc($style_types));

sort($a);
foreach ($a as $type) {
	$query_style_type = "SELECT * FROM style_types WHERE id='$type'";
	$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
	$row_style_type = mysql_fetch_assoc($style_type);

	if ($row_style_type['styleTypeBOS'] == "Y") { 
	$query_bos = "SELECT * FROM judging_scores_bos WHERE (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5') AND scoreType='$type' ORDER BY scorePlace ASC";
	$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
	$row_bos = mysql_fetch_assoc($bos);
	$totalRows_bos = mysql_num_rows($bos);
?>
<?php if ($totalRows_bos > 0) { ?>
    <div id="header">	
		<div id="header-inner">
			<h2>BOS - <?php echo $row_style_type['styleTypeName']; ?></h2>
        </div>
	</div>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $type; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
<table class="dataTable" id="sortable<?php echo $type; ?>">
<thead>
	<tr>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Place</th>
        <th class="dataList bdr1B" width="20%" nowrap="nowrap">Brewer(s)</th>
        <th class="dataList bdr1B" width="25%" nowrap="nowrap">Entry Name</th>
        <th class="dataList bdr1B" width="25%" nowrap="nowrap">Club</th>
        <th class="dataList bdr1B">Style</th>
    </tr>
</thead>
<tbody>
	<?php do {
	
	$query_entries = sprintf("SELECT brewBrewerID,brewBrewerFirstName,brewBrewerLastName,brewName,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewCoBrewer FROM brewing WHERE id='%s'", $row_bos['eid']);
	$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
	$row_entries = mysql_fetch_assoc($entries);
	$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
	
	?>
	<tr>
        <td class="data bdr1B_gray"><?php echo display_place($row_bos['scorePlace'],1); ?></td>
        <td class="data bdr1B_gray">
		<?php 
			$query_brewer = sprintf("SELECT brewerLastName,brewerFirstName,brewerClubs FROM brewer WHERE uid='%s'", $row_entries['brewBrewerID']);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_assoc($brewer);
		echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; if ($row_entries['brewCoBrewer'] != "") echo "<br>".$row_entries['brewCoBrewer']; ?>
        </td>
        <td class="data bdr1B_gray"><?php echo $row_entries['brewName']; ?></td>
        <td class="data bdr1B_gray"><?php echo $row_entries['brewerClubs']; ?></td>
		<td class="data bdr1B_gray"><?php echo $row_entries['brewStyle']; ?></td>   
    </tr>
    <?php } while ($row_bos = mysql_fetch_assoc($bos)); 
	mysql_free_result($bos);
	mysql_free_result($entries);
	?>
</tbody>
</table>
<?php 	} 
	else echo "<p style='margin: 0 0 40px 0'>No entries are eligible.</p>";
    } 
  }  
} 
?>
</div>
</div>