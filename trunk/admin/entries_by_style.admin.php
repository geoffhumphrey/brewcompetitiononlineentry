<?php include(DB.'styles.db.php'); 
do { $total_cat[] = $row_styles['brewStyleGroup']; } while ($row_styles = mysql_fetch_assoc($styles));
$total_cat = count(array_unique($total_cat));
for($cat=1; $cat<=$total_cat; $cat++)  { 
if ($cat <= 9) $cat_convert = "0".$cat; else $cat_convert = $cat;
$cat_name = style_convert($cat_convert,1);
	// Perform query in appropriate db table rows
	$query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND brewReceived='1'",$prefix."brewing",$cat_convert);
	$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
	$row_style_count = mysql_fetch_assoc($style_count);
	
	$style_beer_count[] = 0;
	$style_mead_count[] = 0;
	$style_cider_count[] = 0;
	
	if ($cat <= 23) { $style_type = "Beer"; $style_beer_count[] .= $row_style_count['count']; }
	if (($cat > 23) && ($cat <= 26)) { $style_type = "Mead"; $style_mead_count[] .= $row_style_count['count']; }
	if (($cat > 26) && ($cat <= 28)) { $style_type = "Cider"; $style_cider_count[] .= $row_style_count['count']; }
	if ($cat > 28) {
		$query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE brewStyleGroup='%s'",$prefix."styles",$cat_convert);
		$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
		$row_style_type = mysql_fetch_assoc($style_type);
		
		if ($row_style_type['brewStyleType'] <= 3) $source = "bcoe"; 
		if ($row_style_type['brewStyleType'] > 3)  $source = "custom"; 
		
		$style_type = style_type($row_style_type['brewStyleType'],"2",$source);
		
	}
	$html .= "<tr>";
	$html .= "<td width=\"10%\" nowrap=\"nowrap\""; 
	if ($action == "print") $html.= "class=\"bdr1B_gray\""; 
	$html .= ">".$cat_convert." - ".$cat_name; 
	$html .= "</td>";
    $html .= "<td width=\"10%\" nowrap=\"nowrap\""; 
	if ($action == "print") $html.= "class=\"bdr1B_gray\""; 
	$html .= ">".$row_style_count['count'];
	$html .= "</td>";
	$html .= "<td class=\"data\">".$style_type."</td>";
	$html .= "</tr>";
	
} 

$html_count = "";
if (array_sum($style_beer_count) > 0) {
	$html_count .= "<tr>";
	$html_count .= "<td width=\"10%\" nowrap=\"nowrap\""; 
	if ($action == "print") $html_count.= "class=\"bdr1B_gray\""; 
	$html_count .= ">Beer</td>";
	$html_count .= "<td>".array_sum($style_beer_count)."</td>";
	$html_count .= "</tr>";
}

if (array_sum($style_mead_count) > 0) {
	$html_count .= "<tr>";
	$html_count .= "<td width=\"10%\" nowrap=\"nowrap\""; 
	if ($action == "print") $html_count.= "class=\"bdr1B_gray\""; 
	$html_count .= ">Mead</td>";
	$html_count .= "<td>".array_sum($style_mead_count)."</td>";
	$html_count .= "</tr>";
}

if (array_sum($style_cider_count) > 0) {
	$html_count .= "<tr>";
	$html_count .= "<td width=\"10%\" nowrap=\"nowrap\""; 
	if ($action == "print") $html_count.= "class=\"bdr1B_gray\""; 
	$html_count .= ">Cider</td>";
	$html_count .= "<td>".array_sum($style_cider_count)."</td>";
	$html_count .= "</tr>";
}

$total_style_count = (array_sum($style_beer_count) + array_sum($style_mead_count) + array_sum($style_cider_count));

?>
<h2>Entry Counts by Style</h2>
<div class="adminSubNavContainer">
   	<span class="adminSubNav"><span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin">Back to Admin Dashboard</a></span>
</div>
<p><strong>Please note:</strong> the counts below reflect only entries marked as both paid <em>and</em> received in the database.</p>
<?php if ($total_style_count > 0) { ?>
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
				]
		} );
	} );
</script>
<table class="dataTable" id="sortable1">
<thead>
	<tr>
		<th class="bdr1B">Style Type</th>
		<th class="bdr1B">Count</th>
	</tr>
</thead>
<tbody>
<?php echo $html_count; ?>
</tbody>
</table>
<div style="margin-bottom: 20px;"></div>
<?php } ?>
<script type="text/javascript" language="javascript">
// The following is for demonstration purposes only. 
// Complete documentation and usage at http://www.datatables.net
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
				null
				]
		} );
	} );
</script>
<table class="dataTable" id="sortable2">
<thead>
	<tr>
		<th class="bdr1B">Category</th>
		<th class="bdr1B">Count</th>
        <th class="bdr1B">Style Type</th>
	</tr>
</thead>
<tbody>
<?php echo $html; ?>
</tbody>
</table>