<?php 
include(DB.'styles.db.php'); 
do { $total_cat[] = $row_styles['brewStyleGroup']; } while ($row_styles = mysql_fetch_assoc($styles));
$total_cat = array_unique($total_cat);
foreach ($total_cat as $cat) { 
	
	$cat_convert = $cat;
	$cat_name = style_convert($cat_convert,1);

	// Perform query in appropriate db table rows
	$query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND brewReceived='1'",$prefix."brewing",$cat_convert);
	$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
	$row_style_count = mysql_fetch_assoc($style_count);
	
	$query_style_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s'",$prefix."brewing",$cat_convert);
	$style_count_logged = mysql_query($query_style_count_logged, $brewing) or die(mysql_error());
	$row_style_count_logged = mysql_fetch_assoc($style_count_logged);
	
	$style_beer_count[] = 0;
	$style_mead_count[] = 0;
	$style_cider_count[] = 0;
	
	$style_beer_count_logged[] = 0;
	$style_mead_count_logged[] = 0;
	$style_cider_count_logged[] = 0;
	
	if ($cat <= 23) { 
		$style_type = "Beer"; 
		$style_beer_count[] .= $row_style_count['count']; 
		$style_beer_count_logged[] .= $row_style_count_logged['count'];  
		}
	if (($cat > 23) && ($cat <= 26)) { 
		$style_type = "Mead"; 
		$style_mead_count[] .= $row_style_count['count']; 
		$style_mead_count_logged[] .= $row_style_count_logged['count']; 
		}
	if (($cat > 26) && ($cat <= 28)) { 
		$style_type = "Cider";
		$style_cider_count[] .= $row_style_count['count'];
		$style_cider_count_logged[] .= $row_style_count_logged['count']; 
		}
	if ($cat > 28) {
		$query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE brewStyleGroup='%s'",$prefix."styles",$cat_convert);
		$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
		$row_style_type = mysql_fetch_assoc($style_type);
		
		if ($row_style_type['brewStyleType'] <= 3) $source = "bcoe"; 
		if ($row_style_type['brewStyleType'] > 3)  $source = "custom"; 
		
		$style_type = style_type($row_style_type['brewStyleType'],"2",$source);
		
	}
	
	if (!empty($cat_name)) { 
	
		if ($action == "print") $html .= "<tr class='bdr1B_gray'>"; 
		else $html .= "<tr>";
		$html .= "<td nowrap='nowrap'>".$cat_convert." - ".$cat_name."</td>";
		$html .= "<td nowrap='nowrap'>".$row_style_count_logged['count']."</td>";
		$html .= "<td nowrap='nowrap'>".$row_style_count['count']."</td>";
		$html .= "<td class='data'>".$style_type."</td>";
		$html .= "</tr>";
	}
	
} 

$html_count = "";
if (array_sum($style_beer_count_logged) > 0) {
	if ($action == "print") $html_count.= "<tr class='bdr1B_gray'>";
	else $html_count .= "<tr>";
	$html_count .= "<td width='10%' nowrap='nowrap'>Beer</td>";
	$html_count .= "<td>".(array_sum($style_beer_count_logged))."</td>";
	$html_count .= "<td>".(array_sum($style_beer_count))."</td>";
	$html_count .= "</tr>";
}

if (array_sum($style_mead_count_logged) > 0) {
	if ($action == "print") $html_count.= "<tr class='bdr1B_gray'>"; 
	else $html_count .= "<tr>";
	$html_count .= "<td width='10%' nowrap='nowrap'>Mead</td>";
	$html_count .= "<td>".(array_sum($style_mead_count_logged))."</td>";
	$html_count .= "<td>".(array_sum($style_mead_count))."</td>";
	$html_count .= "</tr>";
}

if (array_sum($style_cider_count_logged) > 0) {
	
	if ($action == "print") $html_count.= "<tr class='bdr1B_gray'>"; 
	else $html_count .= "<tr>";
	$html_count .= "<td>Cider</td>";
	$html_count .= "<td>".(array_sum($style_cider_count_logged))."</td>";
	$html_count .= "<td>".(array_sum($style_cider_count))."</td>";
	$html_count .= "</tr>";
}

$total_style_count = (array_sum($style_beer_count) + array_sum($style_mead_count) + array_sum($style_cider_count) + array_sum($style_beer_count_logged) + array_sum($style_mead_count_logged) + array_sum($style_cider_count_logged));

?>
<h2>Entry Counts by Style</h2>
<?php if ($action != "print") { ?>
<div class="adminSubNavContainer">
   	<span class="adminSubNav"><span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin">Back to Admin Dashboard</a></span>
	<span class="adminSubNav"><span class="icon"><img src="<?php echo $base_url; ?>images/printer.png" alt="Back"></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.php?section=admin&amp;go=count_by_style&amp;action=print">Print</a></span>
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
<table class="dataTable" id="sortable1">
<thead>
	<tr>
		<th class="bdr1B" width="20%" nowrap="nowrap">Style Type</th>
		<th class="bdr1B" width="15%"># Logged</th>
		<th class="bdr1B"># Paid &amp; Received</th>
	</tr>
</thead>
<tbody>
<?php echo $html_count; ?>
</tbody>
</table>
<div style="margin-bottom: 20px;"></div>
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
<table class="dataTable" id="sortable2">
<thead>
	<tr>
		<th class="bdr1B" width="20%">Category</th>
        <th class="bdr1B" width="15%"># Logged</th>
		<th class="bdr1B" width="15%"># Paid &amp; Received</th>
        <th class="bdr1B">Style Type</th>
	</tr>
</thead>
<tbody>
<?php echo $html; ?>
</tbody>
</table>