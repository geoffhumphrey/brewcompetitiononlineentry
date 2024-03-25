<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

$html = "";
$html_testing = "";

$style_other_count[] = 0;
$style_beer_count[] = 0;
$style_mead_count[] = 0;
$style_mead_cider_count[] = 0;
$style_cider_count[] = 0;

$style_beer_count_logged[] = 0;
$style_mead_count_logged[] = 0;
$style_mead_cider_count_logged[] = 0;
$style_cider_count_logged[] = 0;
$style_other_count_logged[] = 0;

include (DB.'styles.db.php');

do {
	if (array_key_exists($row_styles['id'], $styles_selected)) {
		$accepted_categories[] = $row_styles['brewStyleGroup'];
	} 
} while ($row_styles = mysqli_fetch_assoc($styles));

sort($accepted_categories);
$total_cat = array_unique($accepted_categories);

if ($_SESSION['prefsStyleSet'] == "BA") {

	include (INCLUDES.'ba_constants.inc.php');

	foreach ($style_sets as $style_set_data) {
		if ($style_set_data['style_set_name'] === "BA") {
			$style_set_cat = $style_set_data['style_set_categories'];
			// print_r($style_set_cat);
			
			foreach ($style_set_cat as $key => $value) {
				include (DB.'entries_by_style.db.php');

				if ($row_style_count_logged['count'] > 0) {
					if ($filter == "default") $html .= "<tr class=\"success text-success\">";
					else $html .= "<tr>";
				} 
				else {
					if ($filter == "no_zeros") $html .= "<tr class=\"hidden\">";
					else $html .= "<tr>";
				}

				$html .= "<td>".$value."</td>";
				$html .= "<td>".$row_style_count_logged['count']."</td>";
				$html .= "<td>".$row_style_count['count']."</td>";
				$html .= "<td class=\"hidden-xs hidden-sm\">".$style_type."</td>";
				$html .= "</tr>";
			}
			
		}
	}

}

else {
	foreach ($style_sets as $style_set_data) {
		if ($style_set_data['style_set_name'] === $_SESSION['prefsStyleSet']) {
			$style_set_cat = $style_set_data['style_set_categories'];
			//print_r($style_set_cat);
			
			foreach ($style_set_cat as $key => $value) {
				
				include (DB.'entries_by_style.db.php');
				
				if ($row_style_count_logged['count'] > 0) {
					if ($filter == "default") $html .= "<tr class=\"success text-success\">";
					else $html .= "<tr>";
				} 
				
				else {
					if ($filter == "no_zeros") $html .= "<tr class=\"hidden\">";
					else $html .= "<tr>";
				}
				
				if (is_numeric($key)) $cat_number = sprintf('%02d', $key);
				else $cat_number = $key;

				$html .= "<td>".$cat_number." - ".$value."</td>";
				$html .= "<td>".$row_style_count_logged['count']."</td>";
				$html .= "<td>".$row_style_count['count']."</td>";
				$html .= "<td class=\"hidden-xs hidden-sm\">".$style_type."</td>";
				$html .= "</tr>";
			}
			
		}
	}
}

foreach ($total_cat as $key) {

	if ($key >= 35) {
		include (DB.'entries_by_style.db.php');
		if ($row_style_count_logged['count'] > 0) $html .= "<tr class=\"success text-success\">";
		else $html .= "<tr>";
		$html .= "<td>";
		$html .= $cat_name;
		$html .= "</td>";
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

$mead_cider_total = array_sum($style_mead_cider_count);
$mead_cider_total_logged = array_sum($style_mead_cider_count_logged);

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

if (($beer_total > 0) || ($beer_total_logged > 0)) {
	if ($action == "print") $html_count.= "<tr>";
	else $html_count .= "<tr>";
	$html_count .= "<td width='25%' nowrap='nowrap'>Beer</td>";
	$html_count .= "<td>".$beer_total_logged."</td>";
	$html_count .= "<td>".$beer_total."</td>";
	$html_count .= "</tr>";
}

if ($_SESSION['prefsStyleSet'] == "BA") {

	if (($mead_cider_total > 0) || ($mead_cider_total_logged > 0)) {
		if ($action == "print") $html_count.= "<tr>";
		else $html_count .= "<tr>";
		$html_count .= "<td width='25%' nowrap='nowrap'>Mead/Cider</td>";
		$html_count .= "<td>".$mead_cider_total_logged."</td>";
		$html_count .= "<td>".$mead_cider_total."</td>";
		$html_count .= "</tr>";
	}

}

else {

	if (($mead_total > 0) || ($mead_total_logged > 0)) {
		if ($action == "print") $html_count.= "<tr>";
		else $html_count .= "<tr>";
		$html_count .= "<td width='25%' nowrap='nowrap'>Mead</td>";
		$html_count .= "<td>".$mead_total_logged."</td>";
		$html_count .= "<td>".$mead_total."</td>";
		$html_count .= "</tr>";
	}

	if (($cider_total > 0) || ($cider_total_logged > 0)) {

		if ($action == "print") $html_count.= "<tr>";
		else $html_count .= "<tr>";
		$html_count .= "<td width='25%'>Cider</td>";
		$html_count .= "<td>".$cider_total_logged."</td>";
		$html_count .= "<td>".$cider_total."</td>";
		$html_count .= "</tr>";
	}

}

if (($other_total > 0) || ($other_total_logged > 0)) {

	if ($action == "print") $html_count.= "<tr>";
	else $html_count .= "<tr>";
	$html_count .= "<td width='25%'>Other</td>";
	$html_count .= "<td>".$other_total."</td>";
	$html_count .= "<td>".$other_total_logged."</td>";
	$html_count .= "</tr>";
}

if ($_SESSION['prefsStyleSet'] == "BA") {
	$total_style_count = $beer_total + $mead_cider_total + $other_total;
	$total_style_count_logged = $beer_total_logged + $mead_cider_total_logged + $other_total_logged;
}

else {
	$total_style_count = $beer_total + $mead_total + $cider_total + $other_total;
	$total_style_count_logged = $beer_total_logged + $mead_total_logged + $cider_total_logged + $other_total_logged;
}

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
if (($total_style_count > 0) || ($total_style_count_logged > 0)) { ?>
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
<table class="table table-responsive table-bordered" id="sortable1">
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
<div class="row" style="margin-top: 20px;">
	<div class="col-md-9 col-sm-7 col-xs-12"><h3>Breakdown By Style</h3></div>
	<div class="col-md-3 col-sm-5 hidden-xs">
		<?php if ($filter == "default") { ?><a class="btn btn-primary pull-right" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=count_by_style&amp;filter=no_zeros">Hide Styles with Zero Entries</a><?php } ?>
		<?php if ($filter == "no_zeros") { ?><a class="btn btn-primary pull-right" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=count_by_style">Show Styles with Zero Entries</a><?php } ?>
	</div>
</div>
<table class="table table-responsive table-bordered" id="sortable2">
<thead>
	<tr>
		<th>Style</th>
        <th>Logged</th>
		<th>Paid &amp; Received</th>
        <th class="hidden-xs hidden-sm">Style Type</th>
	</tr>
</thead>
<tbody>
<?php echo $html; ?>
</tbody>
</table>