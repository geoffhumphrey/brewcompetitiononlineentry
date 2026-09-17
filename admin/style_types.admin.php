<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 0))) {
    if (function_exists('redirect_or_exit')) redirect_or_exit("../../403.php");
    else { header("Location: ../../403.php"); exit(); }
}

$mead_cider_combined = FALSE;

$db_conn->where ("styleTypeName", "Mead/Cider");
$row_mead_cider_present = $db_conn->getOne ($prefix."style_types");

if ($row_mead_cider_present['styleTypeBOS'] == "Y") $mead_cider_combined = TRUE;

$tbody = "";

if ($action == "default") {

	foreach ($rows_style_type as $row_style_type) {

		$display = TRUE;
		if (($mead_cider_combined) && (($row_style_type['styleTypeName'] == "Cider") || ($row_style_type['styleTypeName'] == "Mead"))) $display = FALSE;
		if ((!$mead_cider_combined) && ($row_style_type['styleTypeName'] == "Mead/Cider")) $display = FALSE;
		if ($display) {
			$tbody .= "<tr>";
			$tbody .= "<td>";
			$tbody .= h($row_style_type['styleTypeName']);
			if ($row_style_type['styleTypeOwn']  == "custom") $tbody .= " (Custom Style Type)";
			$tbody .= "</td>";
			$tbody .= "<td>";
			if (!empty($row_style_type['styleTypeEntryLimit'])) $tbody .= $row_style_type['styleTypeEntryLimit'];
			$tbody .= "</td>";
			$tbody .= "<td>";
			if ($row_style_type['styleTypeBOS'] == "Y") $tbody .= "<span class=\"fa fa-lg fa-check text-success\"></span>";
			else $tbody .= "<span class=\"fa fa-lg fa-times text-danger\"></span>";
			$tbody .= "</td>";
			$tbody .= "<td>";
			if ($row_style_type['styleTypeBOS'] == "Y") $tbody .= bos_method($row_style_type['styleTypeBOSMethod']);
			else $tbody .= "N/A";
			$tbody .= "</td>";
			$tbody .= "<td>";
			$tbody .= "<a href=\"".$base_url."index.php?section=admin&amp;go=".$go."&amp;action=edit&amp;id=".$row_style_type['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit ".h($row_style_type['styleTypeName'])."\"><span class=\"fa fa-lg fa-pencil\"></span></a>";
			if ($row_style_type['styleTypeOwn'] != "bcoe") $tbody .= " <a class=\"hide-loader\" href=\"".$base_url."includes/process.inc.php?section=admin&amp;go=default&amp;dbTable=".$style_types_db_table."&amp;action=delete&amp;id=".$row_style_type['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete ".h($row_style_type['styleTypeName'])."\" data-confirm=\"Are you sure you want to delete ".h($row_style_type['styleTypeName'])."? This cannot be undone.\"><span class=\"fa fa-lg fa-trash-o\"></span></a>";
			else $tbody .= " <span class=\"fa fa-lg fa-trash-o text-muted\"></span>";
			$tbody .= "</td>";
			$tbody .= "</tr>";
		}


	}

}

?>

<p class="lead"><?php echo $_SESSION['contestName']; if ($action == "add") echo ": Add a Style Type"; elseif ($action == "edit") echo ": Edit the ".h($row_style_type['styleTypeName'])." Style Type";  else echo " Style Types";  ?></p>

<div class="bcoem-admin-element hidden-print">
	<!-- Postion 1: View All Button -->
	<div class="btn-group" role="group" aria-label="all-styles">
        <a class="btn btn-secondary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles"><span class="fa fa-arrow-circle-left"></span> All Styles</a>
    </div><!-- ./button group -->

	<?php if (($action == "add") || ($action == "edit")) { ?>
	<!-- Postion 1: View All Button -->
	<div class="btn-group" role="group" aria-label="all-styles">
        <a class="btn btn-secondary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types"><span class="fa fa-arrow-circle-left"></span> All Style Types</a>
    </div><!-- ./button group -->
	<?php } ?>
	<?php if ($action == "default") { ?>
	<!-- Position 2: Add Dropdown Button Group -->
	<div class="btn-group" role="group">
		<button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-plus-circle"></span> Add...
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li class="small"><a class="dropdown-item" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types&amp;action=add">Add a Custom Style Type</a></li>
			<li class="small"><a class="dropdown-item" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style</a></li>
		</ul>
	</div>
	<!-- Combine/Separate Mead/Cider Buttons -->
	<div class="btn-group" role="group" aria-label="all-styles">
		<?php if ($mead_cider_combined) { ?>
			<a class="btn btn-success hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=edit&amp;go=separate&amp;dbTable=<?php echo $style_types_db_table ?>" data-confirm="Are you sure you want to separate mead and cider into two distinct style types? This will clear any Mead/Cider BOS scores/places already entered in the database."><span class="fa fa-expand"></span> Separate Mead and Cider?</a>
		<?php } else { ?>
        	<a class="btn btn-success hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=edit&amp;go=combine&amp;dbTable=<?php echo $style_types_db_table ?>" data-confirm="Are you sure you want to combine mead and cider into a single style type? This will also enable Best of Show (BOS) for the combined style type and clear any Mead or Cider BOS scores/places already in the database."><span class="fa fa-compress"></span> Combine Mead and Cider?</a>
    	<?php } ?>
    </div><!-- ./button group -->
	<?php } ?>
</div>
<?php if ($action == "default") { ?>
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
				null,
				null,
				null,
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
			]
			} );
		} );
</script>
<table class="table table-responsive table-striped table-bordered" id="sortable">
<thead>
	<tr>
    	<th>Name</th>
    	<th nowrap="nowrap">Entry Limit</th>
        <th nowrap="nowrap">BOS Enabled?</th>
        <th nowrap="nowrap">BOS Pull Method</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
	<?php echo $tbody; ?>
</tbody>
</table>
<?php } // END if ($action == "default")?>
<?php if (($action == "add") || ($action == "edit")) { ?>
<form class="form-horizontal hide-loader-form-submit needs-validation" role="form" name="scores" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $style_types_db_table; if ($action == "edit") echo "&id=".$id; ?>" novalidate>
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
<div class="row mb-3">
	<label for="styleTypeName" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Name</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" id="styleTypeName" name="styleTypeName" type="text" value="<?php if ($action == "edit") echo h($row_style_type['styleTypeName']); ?>" placeholder="" autofocus <?php if (($action == "edit") && ($row_style_type['styleTypeOwn'] == "bcoe")) echo "disabled"; ?> required>
		<div class="help-block invalid-feedback text-danger">The style type's name is required.</div>
	</div>
</div>
<div class="row mb-3"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestEntryFee2" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Entry Limit</label>
    <div class="col-xs-12 col-sm-8 col-lg-6">
        <input class="form-control" id="styleTypeEntryLimit" name="styleTypeEntryLimit" type="number" min="0" value="<?php if ($action == "edit") echo $row_style_type['styleTypeEntryLimit']; ?>" placeholder="">
        <div class="help-block">Limit for this style type only.</div>
    </div>
</div>

<?php if (($action == "edit") && ($row_style_type['styleTypeOwn'] == "bcoe")) { ?>
<input type="hidden" name="styleTypeName" value="<?php echo h($row_style_type['styleTypeName']); ?>">
<?php } ?>

<div class="row mb-3"><!-- Form Group Radio INLINE -->
	<label for="brewStyleReqSpec" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">BOS for Style Type</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="styleTypeBOS" value="Y" id="styleTypeBOS_0" <?php if (($action == "edit") && ($row_style_type['styleTypeBOS'] == "Y")) echo "checked"; if ($action == "add") echo "checked"; ?>>
			<label class="form-check-label" for="styleTypeBOS_0">Yes</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="styleTypeBOS" value="N" id="styleTypeBOS_1" <?php if (($action == "edit") && ($row_style_type['styleTypeBOS'] == "N")) echo "checked"; ?>>
			<label class="form-check-label" for="styleTypeBOS_1">No</label>
		</div>
		<div class="help-block"><p>Indicate whether there will be a Best of Show round for this style type.</p></div>
	</div>
</div>
<div class="row mb-3">
	<label for="styleTypeBOSMethod" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">BOS Pull Method</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<div class="form-check">
			<input class="form-check-input" type="radio" name="styleTypeBOSMethod" value="1" id="styleTypeBOSMethod_0" <?php if (($action == "edit") && ($row_style_type['styleTypeBOSMethod'] == "1")) echo "checked"; if ($action == "add") echo "checked"; ?>>
			<label class="form-check-label" for="styleTypeBOSMethod_0">1st place only</label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="radio" name="styleTypeBOSMethod" value="2" id="styleTypeBOSMethod_1" <?php if (($action == "edit") && ($row_style_type['styleTypeBOSMethod'] == "2")) echo "checked"; ?>>
			<label class="form-check-label" for="styleTypeBOSMethod_1">1st and 2nd places</label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="radio" name="styleTypeBOSMethod" value="3" id="styleTypeBOSMethod_2" <?php if (($action == "edit") && ($row_style_type['styleTypeBOSMethod'] == "3")) echo "checked"; ?>>
			<label class="form-check-label" for="styleTypeBOSMethod_2">1st, 2nd, and 3rd places</label>
		</div>
		<div class="help-block"><p>Determine how many placing entries from each medal category should be pulled for this style type in the Best of Show round.</p></div>
	</div>
</div>
<div class="bcoem-admin-element hidden-print">
	<div class="row mb-3">
		<div class="col-xs-12 col-sm-8 col-lg-6 offset-sm-4 offset-lg-2">
			<input type="submit" name="Submit" id="updateStyle" class="btn btn-primary" value="<?php if ($action == "add") echo "Add"; else echo "Edit"; ?> Style Type">
		</div>
	</div>
</div>
<input type="hidden" name="styleTypeOwn" value="<?php if ($action == "add") echo "custom"; else echo $row_style_type['styleTypeOwn']; ?>">
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=style_types","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php } // END if (($action == "add") || ($action == "edit")) ?>
