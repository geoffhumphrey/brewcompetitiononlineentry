<?php
if ($_SESSION['prefsStyleSet'] == "BJCP2008") {
	$beer_end = 23;
	$mead_array = array('24','25','26');
	$cider_array = array('27','28');
	$category_end = 28;
}

if ($_SESSION['prefsStyleSet'] == "BJCP2015") {
	$beer_end = 34;
	$mead_array = array('M1','M2','M3','M4');
	$cider_array = array('C1','C2');
	$category_end = 34;
}

include(DB.'styles.db.php');
do { $accepted_categories[] = $row_styles['brewStyleGroup']; } 
while ($row_styles = mysql_fetch_assoc($styles));
$total_cat = array_unique($accepted_categories);
//print_r($total_cat);
$html = "";
$style_other_count[] = 0;
$style_beer_count[] = 0;
$style_mead_count[] = 0;
$style_cider_count[] = 0;
$style_beer_count_logged[] = 0;
$style_mead_count_logged[] = 0;
$style_cider_count_logged[] = 0;
$style_other_count_logged[] = 0;

foreach ($total_cat as $cat) {
	
	
	
	$cat_convert = $cat;
	$cat_name = style_convert($cat_convert,1);
	
	// Perform query in appropriate db table rows
	$query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND brewReceived='1'",$prefix."brewing",$cat);
	$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
	$row_style_count = mysql_fetch_assoc($style_count);
	
	$query_style_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s'",$prefix."brewing",$cat);
	$style_count_logged = mysql_query($query_style_count_logged, $brewing) or die(mysql_error());
	$row_style_count_logged = mysql_fetch_assoc($style_count_logged);
	
	
	if ($cat > $category_end) {
	
		$query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE brewStyleGroup='%s'",$styles_db_table,$cat);
		$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
		$row_style_type = mysql_fetch_assoc($style_type);
	
	}
	
	if ($cat <= $beer_end) {
		$count_beer = TRUE;
		$count_mead = FALSE;
		$count_cider = FALSE;
		$other_count = FALSE;
	}
	
	if (in_array($cat,$mead_array)) {
		$count_beer = FALSE;
		$count_mead = TRUE;
		$count_cider = FALSE;
		$other_count = FALSE;
	}
	
	if (in_array($cat,$cider_array)) {
		$count_beer = FALSE;
		$count_mead = FALSE;
		$count_cider = TRUE;
		$other_count = FALSE;
	}
	
	if ($cat > $category_end) {
		$count_beer = FALSE;
		$count_mead = FALSE;
		$count_cider = FALSE;
		$other_count = TRUE;
	}
	
	if ($count_beer) { 
		$style_type = "Beer"; 
		$style_beer_count[] .= $row_style_count['count']; 
		$style_beer_count_logged[] .= $row_style_count_logged['count'];
		$style_mead_count[] .= 0; 
		$style_mead_count_logged[] .= 0;
		$style_cider_count[] .= 0;
		$style_cider_count_logged[] .= 0; 
		}
		
		
	if ($count_mead) { 
		$style_type = "Mead";
		$style_beer_count[] .= 0; 
		$style_beer_count_logged[] .= 0;
		$style_mead_count[] .= $row_style_count['count']; 
		$style_mead_count_logged[] .= $row_style_count_logged['count'];
		$style_cider_count[] .= 0;
		$style_cider_count_logged[] .= 0; 
	}
		
	if ($count_cider)  { 
		$style_type = "Cider";
		$style_beer_count[] .= 0; 
		$style_beer_count_logged[] .= 0;
		$style_mead_count[] .= 0; 
		$style_mead_count_logged[] .= 0;
		$style_cider_count[] .= $row_style_count['count'];
		$style_cider_count_logged[] .= $row_style_count_logged['count']; 
	}
	
	if ($other_count) {
		
		if ($row_style_type['brewStyleType'] <= 3) $source = "bcoe"; 
		else  $source = "custom"; 
		
		$style_type = style_type($row_style_type['brewStyleType'],"2",$source);
		
		if ($style_type == "Beer") {
			$style_beer_count[] .= $row_style_count['count']; 
			$style_beer_count_logged[] .= $row_style_count_logged['count'];  
		}
		
		elseif ($style_type == "Mead") {
			$style_mead_count[] .= $row_style_count['count']; 
			$style_mead_count_logged[] .= $row_style_count_logged['count']; 
		}
		
		elseif ($style_type == "Cider") {
			$style_cider_count[] .= $row_style_count['count'];
			$style_cider_count_logged[] .= $row_style_count_logged['count']; 
		}
		
		else {
			$style_other_count[] .= $row_style_count['count'];
			$style_other_count_logged[] .= $row_style_count_logged['count']; 
		}
		
		//$style_type_array[] = $style_type;
	}
	
	
	
	if (!empty($cat_name)) { 
	
		if ($action == "print") $html .= "<tr>"; 
		else $html .= "<tr>";
		$html .= "<td>".$cat_convert." - ".$cat_name."</td>";
		$html .= "<td>".$row_style_count_logged['count']."</td>";
		$html .= "<td>".$row_style_count['count']."</td>";
		$html .= "<td class=\"hidden-xs hidden-sm\">".$style_type."</td>";
		$html .= "</tr>";
	
	}
	
	
	
	// ------ DEBUG ------
	
	//echo "<br>";
	//echo $query_style_count."<br>";
	//echo $query_style_count_logged."<br>";
	//echo $cat_convert." ".$cat_name." Paid/Received: ".$row_style_count['count']." Logged: ".$row_style_count_logged['count']."<br>";
	//$style_display[] = $cat."-".$cat_name."-".$row_style_count['count']."-".$row_style_count_logged['count'];
	//echo $row_style_type['brewStyleType']."<br>";
	//echo $style_type."<br>";
	//echo $source."<br>";
	
} 



$mead_total = array_sum($style_mead_count);
$mead_total_logged = array_sum($style_mead_count_logged);

$cider_total = array_sum($style_cider_count);
$cider_total_logged = array_sum($style_cider_count_logged);

$beer_total = array_sum($style_beer_count);
$beer_total_logged = array_sum($style_beer_count_logged);

$other_total = array_sum($style_other_count);
$other_total_logged = array_sum($style_other_count_logged);

/*
// ------ DEBUG ------
print_r($style_type_array);
echo "<br>";
echo "Beer Total: ".$beer_total."<br>";
echo "Mead Total: ".$mead_total."<br>";
echo "Cider Total: ".$cider_total."<br>";
echo "Other Total: ".$other_total."<br>";
print_r($style_beer_count);
echo "<br>";
print_r($style_mead_count);
echo "<br>";
print_r($style_cider_count);
echo "<br>";
*/

$html_count = "";
if ($beer_total > 0) {
	if ($action == "print") $html_count.= "<tr>";
	else $html_count .= "<tr>";
	$html_count .= "<td width='25%' nowrap='nowrap'>Beer</td>";
	$html_count .= "<td>".$beer_total_logged."</td>";
	$html_count .= "<td>".$beer_total."</td>";
	$html_count .= "</tr>";
}

if ($mead_total > 0) {
	if ($action == "print") $html_count.= "<tr>"; 
	else $html_count .= "<tr>";
	$html_count .= "<td width='25%' nowrap='nowrap'>Mead</td>";
	$html_count .= "<td>".$mead_total_logged."</td>";
	$html_count .= "<td>".$mead_total."</td>";
	$html_count .= "</tr>";
}

if ($cider_total > 0) {
	
	if ($action == "print") $html_count.= "<tr>"; 
	else $html_count .= "<tr>";
	$html_count .= "<td width='25%'>Cider</td>";
	$html_count .= "<td>".$cider_total_logged."</td>";
	$html_count .= "<td>".$cider_total."</td>";
	$html_count .= "</tr>";
}

if (($other_total > 0) || ($other_total_logged > 0)) {
		
	if ($action == "print") $html_count.= "<tr>"; 
	else $html_count .= "<tr>";
	$html_count .= "<td width='25%'>Other</td>";
	$html_count .= "<td>".$other_total."</td>";
	$html_count .= "<td>".$other_total_logged."</td>";
	$html_count .= "</tr>";		
}

$total_style_count = $beer_total + $mead_total + $cider_total + $other_total;
$total_style_count_logged = $beer_total_logged + $mead_total_logged + $cider_total_logged + $other_total_logged;
$total_style_count_all = $total_style_count + $total_style_count_logged;

if (($total_style_count > 0) || ($total_style_count_logged > 0)) {
	
	$html_count .= "<tfoot>";
	$html_count .= "<tr class='bdr1T'>"; 
	$html_count .= "<td nowrap='nowrap'><strong>Totals</strong></td>";
	$html_count .= "<td nowrap='nowrap'>".$total_style_count_logged."</td>";
	$html_count .= "<td nowrap='nowrap'>".$total_style_count."</td>";
	$html_count .= "</tr>";
	$html_count .= "</tfoot>";
	
	$html .= "<tfoot>";
	$html .= "<tr>"; 
	$html .= "<td><strong>Totals</strong></td>";
	$html .= "<td>".$total_style_count_logged."</td>";
	$html .= "<td>".$total_style_count."</td>";
	$html .= "<td class=\"hidden-xs hidden-sm\">&nbsp;</td>";
	$html .= "</tr>";
	$html .= "</tfoot>";
}
?>

<p class="lead"><?php echo $_SESSION['contestName']; ?> entry count by broken down by style.</p>

<?php if ($action != "print") { ?>
<div class="bcoem-admin-element hidden-print">
	<div class="btn-group" role="group" aria-label="add-custom-winning">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=count_by_substyle"><span class="fa fa-eye"></span> View Entry Count by Sub-Style</a>
    </div><!-- ./button group -->
</div>
<?php } 
if ($total_style_count > 0) { ?>
<script type="text/javascript" language="javascript">
// The following is for demonstration purposes only. 
// Complete documentation and usage at http://www.datatables.net
	$(document).ready(function() {
		$('#sortable1').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"aoColumns": [
				null,
				null,
				null
				]
		} );
	} );
</script>
<table class="table table-responsive table-striped table-bordered" id="sortable1">
<thead>
	<tr>
		<th>Style Type</th>
		<th>Logged</th>
		<th>Paid &amp; Received</th>
	</tr>
</thead>
<tbody>
<?php echo $html_count; ?>
</tbody>
</table>

<?php } ?>

<h3>Breakdown By Style</h3>
<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		$('#sortable2').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"aoColumns": [
				null,
				null, 
				null,
				null
				]
		} );
	} );
</script>
<table class="table table-responsive table-striped table-bordered " id="sortable2">
<thead>
	<tr>
		<th>Category</th>
        <th>Logged</th>
		<th>Paid &amp; Received</th>
        <th class="hidden-xs hidden-sm">Style Type</th>
	</tr>
</thead>
<tbody>
<?php echo $html; ?>
</tbody>
</table>