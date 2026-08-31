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
$substyle = "";

include (DB.'styles.db.php');

$subcats = array();

foreach ($rows_styles as $row_styles) {

	if (array_key_exists($row_styles['id'], $styles_selected)) {
		$subcats[] = array($row_styles['brewStyleGroup'],$row_styles['brewStyleNum'],$row_styles['brewStyle'],$row_styles['brewStyleCategory'],$row_styles['brewStyleActive']);
	}

}

// Pre-aggregate entry counts instead of running up to 2 queries per
// sub-style below (this used to be re-queried inside
// includes/db/entries_by_substyle.db.php on every loop iteration - a full
// style set can have 100+ sub-styles). Custom categories (brewCategorySort
// >= 50) are counted at the category level only (no sub-category
// breakdown), so they're aggregated separately from normal sub-styles.
$db_conn->where('brewPaid', '1');
$db_conn->where('brewReceived', '1');
$db_conn->where('brewConfirmed', '1');
$db_conn->groupBy('brewCategorySort');
$db_conn->groupBy('brewSubCategory');
$rows_substyle_counts = $db_conn->get($prefix."brewing", null, "brewCategorySort, brewSubCategory, COUNT(*) AS count");
$substyle_counts = array();
foreach ($rows_substyle_counts as $row_substyle_counts) {
	$substyle_counts[$row_substyle_counts['brewCategorySort']][$row_substyle_counts['brewSubCategory']] = $row_substyle_counts['count'];
}

$db_conn->where('brewConfirmed', '1');
$db_conn->groupBy('brewCategorySort');
$db_conn->groupBy('brewSubCategory');
$rows_substyle_counts_logged = $db_conn->get($prefix."brewing", null, "brewCategorySort, brewSubCategory, COUNT(*) AS count");
$substyle_counts_logged = array();
foreach ($rows_substyle_counts_logged as $row_substyle_counts_logged) {
	$substyle_counts_logged[$row_substyle_counts_logged['brewCategorySort']][$row_substyle_counts_logged['brewSubCategory']] = $row_substyle_counts_logged['count'];
}

$db_conn->where('brewPaid', '1');
$db_conn->where('brewReceived', '1');
$db_conn->where('brewConfirmed', '1');
$db_conn->groupBy('brewCategorySort');
$rows_style_counts = $db_conn->get($prefix."brewing", null, "brewCategorySort, COUNT(*) AS count");
$style_counts_by_cat = array();
foreach ($rows_style_counts as $row_style_counts) {
	$style_counts_by_cat[$row_style_counts['brewCategorySort']] = $row_style_counts['count'];
}

$db_conn->where('brewConfirmed', '1');
$db_conn->groupBy('brewCategorySort');
$rows_style_counts_logged = $db_conn->get($prefix."brewing", null, "brewCategorySort, COUNT(*) AS count");
$style_counts_logged_by_cat = array();
foreach ($rows_style_counts_logged as $row_style_counts_logged) {
	$style_counts_logged_by_cat[$row_style_counts_logged['brewCategorySort']] = $row_style_counts_logged['count'];
}

// Style type per category (only needed for custom categories below), keyed
// off the styles rows already fetched above instead of a fresh query per
// custom sub-style.
$style_type_by_cat = array();
foreach ($rows_styles as $row_styles_meta) {
	if (!isset($style_type_by_cat[$row_styles_meta['brewStyleGroup']])) {
		$style_type_by_cat[$row_styles_meta['brewStyleGroup']] = $row_styles_meta['brewStyleType'];
	}
}

foreach ($subcats as $key => $value) {

	$substyle = $value;

	if ((is_numeric($substyle[0])) && ($substyle[0] >= 50)) {
		$substyle_count = isset($style_counts_by_cat[$substyle[0]]) ? $style_counts_by_cat[$substyle[0]] : 0;
		$substyle_count_logged = isset($style_counts_logged_by_cat[$substyle[0]]) ? $style_counts_logged_by_cat[$substyle[0]] : 0;
	}
	else {
		$substyle_count = isset($substyle_counts[$substyle[0]][$substyle[1]]) ? $substyle_counts[$substyle[0]][$substyle[1]] : 0;
		$substyle_count_logged = isset($substyle_counts_logged[$substyle[0]][$substyle[1]]) ? $substyle_counts_logged[$substyle[0]][$substyle[1]] : 0;
	}

	include (DB.'entries_by_substyle.db.php');

	if (!empty($substyle)) {

		if ($row_substyle_count_logged['count'] > 0) {
			if ($filter == "default") $html .= "<tr class=\"success text-success\">";
			else $html .= "<tr>";
		} 
		else {
			if ($filter == "no_zeros") $html .= "<tr class=\"hidden\">";
			else $html .= "<tr>";
		}
		if (!empty($substyle[3])) $substyle_cat = $substyle[3];
		else $substyle_cat = "Custom Category";

		$html .= "<td>";
		$html .= "<span class=\"hidden\">".$substyle[0]."</span>";
		if (!$_SESSION['style_set_no_numbering']) {
			if ($_SESSION['prefsStyleSet'] == "AABC") $html .= ltrim($substyle[0],"0").".".ltrim($substyle[1],"0")." ";
			else $html .= $substyle[0].$substyle[1]." - ";
		}
		$html .= $substyle[2]."</td>";
		$html .= "<td class=\"hidden-xs hidden-sm\">".$substyle_cat."</td>";
		$html .= "<td>".$row_substyle_count_logged['count']."</td>";
		$html .= "<td>".$row_substyle_count['count']."</td>";
		$html .= "<td class=\"hidden-xs hidden-sm\">".$style_type."</td>";
		$html .= "</tr>";
		
	}

}

//print_r($style_type_array);

$mead_total = array_sum($style_mead_count);
$mead_total_logged = array_sum($style_mead_count_logged);

$mead_cider_total = array_sum($style_mead_cider_count);
$mead_cider_total_logged = array_sum($style_mead_cider_count_logged);

$cider_total = array_sum($style_cider_count);
$cider_total_logged = array_sum($style_cider_count_logged);

$beer_total = array_sum($style_beer_count);
$beer_total_logged = array_sum($style_beer_count_logged);

$other_total = array_sum($style_other_count);
$other_total_logged = array_sum($style_other_count_logged);

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
	$html_count .= "<tr>";
	$html_count .= "<td><strong>Totals</strong></td>";
	$html_count .= "<td>".$total_style_count_logged."</td>";
	$html_count .= "<td>".$total_style_count."</td>";
	$html_count .= "</tr>";
	$html_count .= "</tfoot>";


	$html .= "<tfoot>";
	$html .= "<tr>";
	$html .= "<td><strong>Totals</strong></td>";
	$html .= "<td class=\"hidden-xs hidden-sm\">&nbsp;</td>";
	$html .= "<td>".$total_style_count_logged."</td>";
	$html .= "<td>".$total_style_count."</td>";
	$html .= "<td class=\"hidden-xs hidden-sm\">&nbsp;</td>";
	$html .= "</tr>";
	$html .= "</tfoot>";
}

?>
<p class="lead"><?php echo h($_SESSION['contestName']); ?> entry count by broken down by sub-style.</p>
<?php if ($action != "print") { ?>
<div class="bcoem-admin-element hidden-print">
	<div class="btn-group" role="group" aria-label="add-custom-winning">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=count_by_style"><span class="fa fa-eye"></span> View Entry Count by Style</a>
    </div><!-- ./button group -->
</div>
<?php } echo $html_testing;
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
<div class="row" style="margin-top: 20px;">
	<div class="col-md-9 col-sm-7 col-xs-12"><h3>Breakdown By Sub-Style</h3></div>
	<div class="col-md-3 col-sm-5 hidden-xs">
		<?php if ($filter == "default") { ?><a class="btn btn-primary pull-right" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=count_by_substyle&amp;filter=no_zeros">Hide Sub-Styles with Zero Entries</a><?php } ?>
		<?php if ($filter == "no_zeros") { ?><a class="btn btn-primary pull-right" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=count_by_substyle">Show Sub-Styles with Zero Entries</a><?php } ?>
	</div>
</div>
<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		$('#sortable6').dataTable( {
			"bPaginate" : false,
			"sDom": 'fprtp',
			"bStateSave" : false,
			"bLengthChange" : false,
			<?php if ($_SESSION['style_set_no_numbering']) { ?>"aaSorting": [[1,'asc'],[0,'asc']],
			<?php } else { ?>"aaSorting": [[4,'asc'],[0,'asc']],<?php } ?>
			"aoColumns": [
				null,
				null,
				null,
				null,
				null
				]
		} );
	} );
</script>

<table class="table table-responsive table-bordered" id="sortable6">
<thead>
	<tr>
		<th>Sub-Style</th>
        <th class="hidden-xs hidden-sm">Style</th>
        <th>Logged</th>
		<th>Paid &amp; Received</th>
        <th class="hidden-xs hidden-sm">Style Type</th>
	</tr>
</thead>
<tbody>
<?php echo $html; ?>
</tbody>
</table>