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

do { $total_cat[] = $row_styles['brewStyleGroup']; } while ($row_styles = mysql_fetch_assoc($styles));
$total_cat = array_unique($total_cat);
//print_r($total_cat);

$html = "";
$style_other_count[] = 0;
$style_other_count_logged[] = 0;

foreach ($total_cat as $cat) {
	
	$cat_convert = $cat;
	$cat_name = style_convert($cat_convert,1);

	// Perform query in appropriate db table rows
	include(DB.'admin_entry_count_by_style.db.php');
	
	$style_beer_count[] = 0;
	$style_mead_count[] = 0;
	$style_cider_count[] = 0;
	
	$style_beer_count_logged[] = 0;
	$style_mead_count_logged[] = 0;
	$style_cider_count_logged[] = 0;
	
	//$style_display[] = $cat."-".$cat_name."-".$row_style_count['count']."-".$row_style_count_logged['count'];
	
	if ($cat <= $beer_end) { 
		$style_type = "Beer"; 
		$style_beer_count[] .= $row_style_count['count']; 
		$style_beer_count_logged[] .= $row_style_count_logged['count'];  
		}
		
	if (in_array($cat,$mead_array)) { 
		$style_type = "Mead"; 
		$style_mead_count[] .= $row_style_count['count']; 
		$style_mead_count_logged[] .= $row_style_count_logged['count']; 
		}
		
	if (in_array($cat,$cider_array))  { 
		$style_type = "Cider";
		$style_cider_count[] .= $row_style_count['count'];
		$style_cider_count_logged[] .= $row_style_count_logged['count']; 
		}
		
	if ($cat > $category_end) {
		if ($row_style_type['brewStyleType'] <= 3) $source = "bcoe"; 
		if ($row_style_type['brewStyleType'] > 3)  $source = "custom"; 
		
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
	
		if ($action == "print") $html .= "<tr class='bdr1B_gray'>"; 
		else $html .= "<tr>";
		$html .= "<td nowrap='nowrap' width='25%'>".$cat_convert." - ".$cat_name."</td>";
		$html .= "<td nowrap='nowrap'>".$row_style_count_logged['count']."</td>";
		$html .= "<td nowrap='nowrap'>".$row_style_count['count']."</td>";
		$html .= "<td class='data'>".$style_type."</td>";
		$html .= "</tr>";
	
	}
	
} 

//print_r($style_type_array);

$html_count = "";
if (array_sum($style_beer_count_logged) > 0) {
	if ($action == "print") $html_count.= "<tr class='bdr1B_gray'>";
	else $html_count .= "<tr>";
	$html_count .= "<td width='25%' nowrap='nowrap'>Beer</td>";
	$html_count .= "<td>".(array_sum($style_beer_count_logged))."</td>";
	$html_count .= "<td>".(array_sum($style_beer_count))."</td>";
	$html_count .= "</tr>";
}

if (array_sum($style_mead_count_logged) > 0) {
	if ($action == "print") $html_count.= "<tr class='bdr1B_gray'>"; 
	else $html_count .= "<tr>";
	$html_count .= "<td width='25%' nowrap='nowrap'>Mead</td>";
	$html_count .= "<td>".(array_sum($style_mead_count_logged))."</td>";
	$html_count .= "<td>".(array_sum($style_mead_count))."</td>";
	$html_count .= "</tr>";
}

if (array_sum($style_cider_count_logged) > 0) {
	
	if ($action == "print") $html_count.= "<tr class='bdr1B_gray'>"; 
	else $html_count .= "<tr>";
	$html_count .= "<td width='25%'>Cider</td>";
	$html_count .= "<td>".(array_sum($style_cider_count_logged))."</td>";
	$html_count .= "<td>".(array_sum($style_cider_count))."</td>";
	$html_count .= "</tr>";
}

if ((array_sum($style_other_count) > 0) || (array_sum($style_other_count_logged) > 0)) {
		
	if ($action == "print") $html_count.= "<tr class='bdr1B_gray'>"; 
	else $html_count .= "<tr>";
	$html_count .= "<td width='25%'>Other</td>";
	$html_count .= "<td>".(array_sum($style_other_count_logged))."</td>";
	$html_count .= "<td>".(array_sum($style_other_count))."</td>";
	$html_count .= "</tr>";		
}

$total_style_count = (array_sum($style_beer_count) + array_sum($style_mead_count) + array_sum($style_cider_count) + array_sum($style_other_count));
$total_style_count_logged = (array_sum($style_beer_count_logged) + array_sum($style_mead_count_logged) + array_sum($style_cider_count_logged) + array_sum($style_other_count_logged));
$total_style_count_all = (array_sum($style_beer_count) + array_sum($style_mead_count) + array_sum($style_other_count) + array_sum($style_cider_count) + array_sum($style_beer_count_logged) + array_sum($style_mead_count_logged) + array_sum($style_cider_count_logged) + array_sum($style_other_count_logged));

if (($total_style_count > 0) || ($total_style_count_logged > 0)) {
	
	$html_count .= "<tfoot>";
	$html_count .= "<tr class='bdr1T'>"; 
	$html_count .= "<td nowrap='nowrap'><strong>Totals</strong></td>";
	$html_count .= "<td nowrap='nowrap'>".$total_style_count_logged."</td>";
	$html_count .= "<td nowrap='nowrap'>".$total_style_count."</td>";
	$html_count .= "</tr>";
	$html_count .= "</tfoot>";
	
	$html .= "<tfoot>";
	$html .= "<tr class='bdr1T'>"; 
	$html .= "<td nowrap='nowrap'><strong>Totals</strong></td>";
	$html .= "<td nowrap='nowrap'>".$total_style_count_logged."</td>";
	$html .= "<td nowrap='nowrap'>".$total_style_count."</td>";
	$html .= "<td class='data'>&nbsp;</td>";
	$html .= "</tr>";
	$html .= "</tfoot>";
}
?>
<h2>Entry Count by Style</h2>
<?php if ($action != "print") { ?>
<div class="adminSubNavContainer">
   	<span class="adminSubNav"><span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin">Back to Admin Dashboard</a></span>
	<span class="adminSubNav"><span class="icon"><img src="<?php echo $base_url; ?>images/page.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=count_by_substyle">Entry Count By Sub-Style</a></span>
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
		<th class="bdr1B" width="25%" nowrap="nowrap">Style Type</th>
		<th class="bdr1B" width="15%">Logged</th>
		<th class="bdr1B">Paid &amp; Received</th>
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
		<th class="bdr1B" width="25%">Category</th>
        <th class="bdr1B" width="15%">Logged</th>
		<th class="bdr1B" width="15%">Paid &amp; Received</th>
        <th class="bdr1B">Style Type</th>
	</tr>
</thead>
<tbody>
<?php echo $html; ?>
</tbody>
</table>