<?php

if ($section != "step7") include (DB.'judging_locations.db.php');
include (DB.'styles.db.php');
if ($_SESSION['prefsStyleSet'] == "BA") include (INCLUDES.'ba_constants.inc.php');
//echo $query_styles;
//echo $row_styles['brewStyle'];

// Build style table body
$table_body = "";

if ((($action == "default") && ($filter == "default")) || ($section == "step7") || (($action == "default") && ($filter == "judging") && ($bid != "default"))) {

	$sorting_default = "[[2,'asc']]";

	do {

		if ($row_styles['id'] != "") {

			$brewStyleActive = "";
			if (isset($row_styles['brewStyleActive']) && ($row_styles['brewStyleActive'] == "Y")) $brewStyleActive = "CHECKED";

			$brewStyleJudgingLoc = "";
			if (isset($row_styles['brewStyleJudgingLoc']) && ($row_styles['brewStyleJudgingLoc'] == $bid)) $brewStyleJudgingLoc = "CHECKED";

			$brewStyleOwn_prefix = "";
			$brewStyleOwn_suffix = "";
			 if ($row_styles['brewStyleOwn'] != "bcoe") {
				 $brewStyleOwn_prefix = "*";
				 $brewStyleOwn_suffix = " - Custom Style";
			 }

			$style_own = "";
			if (style_type($row_styles['brewStyleType'],"1","") <= "3") $style_own = "bcoe";
			else $style_own = "custom";

			$brewStyleReqSpec = "";
			$brewStyleStrength = "";
			$brewStyleCarb = "";
			$brewStyleSweet = "";

			if ($row_styles['brewStyleReqSpec'] == 1) $brewStyleReqSpec = "<span class=\"fa fa-check-circle text-orange\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$entry_info_text_048."\"></span> ";

			if ($row_styles['brewStyleStrength'] == 1) $brewStyleStrength = "<span class=\"fa fa-check-circle text-purple\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$entry_info_text_049."\"></span> ";

			if ($row_styles['brewStyleCarb'] == 1) $brewStyleCarb = "<span class=\"fa fa-check-circle text-teal\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$entry_info_text_050."\"></span> ";

			if ($row_styles['brewStyleSweet'] == 1) $brewStyleSweet = "<span class=\"fa fa-check-circle text-gold\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$entry_info_text_051."\"></span> ";


			$table_body .= "<tr>";
			$table_body .= "<input type=\"hidden\" name=\"id[]\" value=\"".$row_styles['id']."\" />";
			if ($bid == "default") $table_body .= "<td width=\"1%\" nowrap><input name=\"brewStyleActive".$row_styles['id']."\" type=\"checkbox\" value=\"Y\" ".$brewStyleActive."></td>";
			if ($bid != "default") $table_body .= "<td width=\"1%\" nowrap><input name=\"brewStyleJudgingLoc".$row_styles['id']."\" type=\"checkbox\" value=\"".$bid."\" ".$brewStyleJudgingLoc."></td>";
			$table_body .= "<td>".$row_styles['brewStyle']."</td>";
			if ($_SESSION['prefsStyleSet'] == "BA") {
				if ($row_styles['brewStyleOwn'] == "custom") $table_body .= "<td>Custom Style</td>";
				else $table_body .= "<td>".$ba_category_names[ltrim($row_styles['brewStyleGroup'],"0")]."</td>";
			}
			else $table_body .= "<td>".$brewStyleOwn_prefix.$row_styles['brewStyleGroup'].$row_styles['brewStyleNum'].$brewStyleOwn_suffix."</td>";
			$table_body .= "<td>".style_type($row_styles['brewStyleType'],"2",$style_own)."</td>";
			$table_body .= "<td>".$brewStyleReqSpec.$brewStyleStrength.$brewStyleCarb.$brewStyleSweet."</td>";
			$table_body .= "<td class=\"hidden-print\">";
			if ($section != "step7") {
				if ($row_styles['brewStyleOwn'] != "bcoe") $table_body .= "<a class=\"hide-loader\" href=\"".$base_url."index.php?section=admin&amp;go=".$go."&amp;action=edit&amp;id=".$row_styles['id']."&amp;view=".$row_styles['brewStyleType']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit ".$row_styles['brewStyle']."\"><span class=\"fa fa-lg fa-pencil\"></span></a> <a href=\"".$base_url."includes/process.inc.php?section=admin&amp;go=".$go."&amp;dbTable=".$styles_db_table."&amp;action=delete&amp;id=".$row_styles['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete ".$row_styles['brewStyle']."\" data-confirm=\"Are you sure you want to delete ".$row_styles['brewStyle']."? This cannot be undone.\"><span class=\"fa fa-lg fa-trash-o\"></span></a> ";
				else $table_body .= "<span class=\"fa fa-lg fa-pencil text-muted\"></span> <span class=\"fa fa-lg fa-trash-o text-muted\"></span> ";
			}
			if ($row_styles['brewStyleLink'] !="") $table_body .= "<a class=\"hide-loader\" href=\"".$row_styles['brewStyleLink']."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Link to BJCP ".$row_styles['brewStyle']." sub-style on bjcp.org\"><span class=\"fa fa-lg fa-link\"></span></a>";
			$table_body .= "</td>";
			$table_body .= "</tr>";

		}

	} while($row_styles = mysqli_fetch_assoc($styles));

}

if ($section != "step7") { ?>
<p class="lead"><?php echo $_SESSION['contestName']; if ($action == "add") echo ": Add a Custom Style"; elseif ($action == "edit") echo ": Edit a Custom Style" ; elseif (($action == "default") && ($filter == "judging") && ($bid != "default")) echo ": Style Judged at ".$row_judging['judgingLocName']; else echo " Accepted Styles"; ?></p>
<?php if (($filter == "default") && ($action == "default")) { ?><p class="lead"><span class="small">Check or uncheck the styles <?php if (($action == "default") && ($filter == "judging") && ($bid != "default")) { echo "that will be judged at ".$row_judging['judgingLocName']." on "; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"); } else echo "your competition will accept (any custom styles are at the top of the list)"; ?>.</span></p><?php } ?>
<div class="bcoem-admin-element hidden-print">
	<?php if ($action != "default") { ?>
	<!-- Postion 1: View All Button -->
	<div class="btn-group" role="group" aria-label="all-styles">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles"><span class="fa fa-arrow-circle-left"></span> All Styles</a>
    </div><!-- ./button group -->
	<?php } ?>
	<?php if ($action == "default") { ?>
	<!-- Position 2: Add Dropdown Button Group -->
	<div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-plus-circle"></span> Add...
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style</a></li>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types&amp;action=add">Add a Style Type</a><li>
		</ul>
	</div>
	<?php } ?>
</div>
<?php } if ((($action == "default") && ($filter == "default")) || ($section == "step7") || (($action == "default") && ($filter == "judging") && ($bid != "default"))) { ?>
<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rft',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": <?php echo $sorting_default; ?>,
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				null,
				null,
				null,
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );

function checkUncheckAll(theElement) {
     var theForm = theElement.form, z = 0;
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall'){
	  theForm[z].checked = theElement.checked;
	  }
     }
    }
</script>

<form name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php if ($section == "step7") echo "setup"; else echo $section; ?>&amp;action=update&amp;dbTable=<?php echo $styles_db_table; ?>&amp;filter=<?php echo $filter; if ($bid != "default") echo "&amp;bid=".$bid; ?>">
<table class="table table-responsive table-striped table-bordered" id="sortable">
<thead>
 <tr>
  <th><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></th>
  <th>Style Name</th>
  <th><?php if (strpos($_SESSION['prefsStyleSet'],"BJCP") === false) echo "Overall Category"; else echo "#"; ?></th>
  <th>Style Type</th>
  <th>Requirements</th>
  <th class="hidden-print">Actions</th>
 </tr>
 </thead>
 <tbody>
 <?php echo $table_body; ?>
 </tbody>
 </table>
 <div class="bcoem-admin-element hidden-print">
	<input type="submit" name="Submit" id="helpUpdateStyles" class="btn btn-primary" aria-describedby="helpBlock" value="<?php if (($filter == "judging") && ($bid != "default")) echo "Update ".$row_judging['judgingLocName']; else echo "Update Accepted Styles"; ?>" />
    <span id="helpBlock" class="help-block">Click "<?php if (($filter == "judging") && ($bid != "default")) echo "Update ".$row_judging['judgingLocName']; else echo "Update Accepted Styles"; ?> <em>before</em> paging through records.</span>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=styles","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php } ?>

<?php if (($action == "add") || ($action == "edit")) {
$style_type_2 = style_type($row_styles['brewStyleType'],"1","bcoe");
?>
<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $styles_db_table; ?>&amp;go=<?php echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" id="form1" name="form1">
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="brewStyle" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Name</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="brewStyle" name="brewStyle" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyle']; ?>" placeholder="" data-error="The custom style category's name is required" autofocus required>
			<span class="input-group-addon" id="brewStyle-addon2"><span class="fa fa-star"></span></span>
		</div>
        <div class="help-block with-errors"></div>
	</div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group REQUIRED Select -->
	<label for="brewStyleType" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Style Type</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 has-warning">
	<!-- Input Here -->
	<select class="selectpicker" name="brewStyleType" id="brewStyleType" data-size="10" data-width="auto">
        <?php do {
        if ($row_style_type['styleTypeName'] != "Mead/Cider") { ?>
        <option value="<?php echo $row_style_type['id']; ?>" <?php if (($action == "edit") && ($row_styles['brewStyleType'] == $row_style_type['id'])) echo "SELECTED"; ?>><?php echo $row_style_type['styleTypeName']; ?></option>
    	<?php }
    	} while ($row_style_type = mysqli_fetch_assoc($style_type)); ?>
	</select>
	<span id="helpBlock" class="help-block"><a class="btn btn-sm btn-primary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Style Type</a></span>
	</div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group Radio INLINE -->
	<label for="brewStyleReqSpec" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Required Info</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group">
			<!-- Input Here -->
			<label class="radio-inline">
				<input type="radio" name="brewStyleReqSpec" value="1" id="brewStyleReqSpec_0" <?php if ($row_styles['brewStyleReqSpec'] == 1) echo "CHECKED"; ?> />Yes
			</label>
			<label class="radio-inline">
				<input type="radio" name="brewStyleReqSpec" value="0" id="brewStyleReqSpec_1" <?php if (($action == "add") || ($row_styles['brewStyleReqSpec'] == 0)) echo "CHECKED"; ?> />No
			</label>
		</div>
	</div>
</div><!-- ./Form Group -->
<div id="mead-cider">
	<div class="form-group"><!-- Form Group Radio INLINE -->
		<label for="brewStyleCarb" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Require Carbonation</label>
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
			<div class="input-group">
				<!-- Input Here -->
				<label class="radio-inline">
					<input type="radio" name="brewStyleCarb" value="1" id="brewStyleCarb_0" <?php if ($row_styles['brewStyleCarb'] == 1) echo "CHECKED"; ?> />Yes
				</label>
				<label class="radio-inline">
					<input type="radio" name="brewStyleCarb" value="0" id="brewStyleCarb_1" <?php if (($action == "add") || (($action == "edit") && ($row_styles['brewStyleCarb'] == 0))) echo "CHECKED"; ?> />No
				</label>
				<div class="help-block with-errors"></div>
			</div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group Radio INLINE -->
		<label for="brewStyleSweet" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Require Sweetness</label>
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
			<div class="input-group">
				<!-- Input Here -->
				<label class="radio-inline">
					<input type="radio" name="brewStyleSweet" value="1" id="brewStyleSweet_0" <?php if ($row_styles['brewStyleSweet'] == 1) echo "CHECKED"; ?> />Yes
				</label>
				<label class="radio-inline">
					<input type="radio" name="brewStyleSweet" value="0" id="brewStyleSweet_1" <?php if (($action == "add") || (($action == "edit") && ($row_styles['brewStyleSweet'] == 0))) echo "CHECKED"; ?> />No
				</label>
			</div>
			<div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
</div>
<div id="mead">
	<div class="form-group"><!-- Form Group Radio INLINE -->
		<label for="brewStyleStrength" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Require Strength</label>
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
			<div class="input-group">
				<!-- Input Here -->
				<label class="radio-inline">
					<input type="radio" name="brewStyleStrength" value="1" id="brewStyleStrength_0" <?php if ($row_styles['brewStyleStrength'] == 1) echo "CHECKED"; ?> />Yes
				</label>
				<label class="radio-inline">
					<input type="radio" name="brewStyleStrength" value="0" id="brewStyleStrength_1" <?php if (($action == "add") || (($action == "edit") && ($row_styles['brewStyleStrength'] == 0))) echo "CHECKED"; ?> />No
				</label>
			</div>
			<div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
</div>
<div id="brewStyleEntry">
<div class="form-group"><!-- Form Group NOT-REQUIRED Text Area -->
	<label for="brewStyleEntry" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Entry Info</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<textarea class="form-control" name="brewStyleEntry" id="brewStyleEntryTextArea" rows="6"><?php if ($action == "edit") echo $row_styles['brewStyleEntry']; ?></textarea>
		<div class="help-block with-errors"></div>
		<div class="help-block"><strong class="text-danger">Required:</strong> provide requirements for entry (e.g., <em>Entrant must specify yeast strain(s) used</em>, etc.).</div>
	 </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group NOT-REQUIRED Text Area -->
	<label for="brewStyleInfo" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Description</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<textarea class="form-control" name="brewStyleInfo" id="brewStyleInfoTextArea" rows="6"><?php if ($action == "edit") echo $row_styles['brewStyleInfo']; ?></textarea>
		<div class="help-block"><strong class="text-primary">Optional:</strong> provide a short description of the style.</div>
	 </div>

</div><!-- ./Form Group -->
	</div>
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="brewStyleOG" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">OG Minimum</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<input class="form-control" name="brewStyleOG" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleOG']; ?>" placeholder="">
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="brewStyleOGMax" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">OG Maximum</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<input class="form-control" name="brewStyleOGMax" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleOGMax']; ?>" placeholder="">
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="brewStyleFG" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">FG Minimum</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<input class="form-control" name="brewStyleFG" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleFG']; ?>" placeholder="">
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="brewStyleFGMax" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">FG Maximum</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<input class="form-control" name="brewStyleFGMax" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleFGMax']; ?>" placeholder="">
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="brewStyleABV" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">ABV Minimum</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<input class="form-control" name="brewStyleABV" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleABV']; ?>" placeholder="">
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="brewStyleABVMax" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">ABV Maximum</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<input class="form-control" name="brewStyleABVMax" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleABVMax']; ?>" placeholder="">
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="brewStyleIBU" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">IBU Minimum</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<input class="form-control" name="brewStyleIBU" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleIBU']; ?>" placeholder="">
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="brewStyleIBUMax" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">IBU Maximum</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<input class="form-control" name="brewStyleIBUMax" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleIBUMax']; ?>" placeholder="">
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="brewStyleSRM" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Color Minimum</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<input class="form-control" name="brewStyleSRM" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleSRM']; ?>" placeholder="">
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="brewStyleSRMMax" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Color Maximum</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<input class="form-control" name="brewStyleSRMMax" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleSRMMax']; ?>" placeholder="">
	</div>
</div><!-- ./Form Group -->



<input type="hidden" name="brewStyleOld" value="<?php if ($action == "edit") echo $row_styles['brewStyle'];?>">
<input type="hidden" name="brewStyleGroup" value="<?php if ($action == "edit") echo $row_styles['brewStyleGroup'];?>">
<input type="hidden" name="brewStyleNum" value="<?php if ($action == "edit") echo $row_styles['brewStyleNum'];?>" >
<input type="hidden" name="brewStyleActive" value="<?php if ($action == "edit") echo $row_styles['brewStyleActive']; else echo "Y"; ?>">
<input type="hidden" name="brewStyleOwn" value="<?php if ($action == "edit") echo $row_styles['brewStyleOwn']; else echo "custom"; ?>">
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=styles","default",$msg,$id); ?>">
<?php } ?>

<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" name="Submit" id="updateStyle" class="btn btn-primary" value="<?php if ($action == "add") echo "Add"; else echo "Edit"; ?> Custom Style" />
		</div>
	</div>
</div>
</form>
<?php

// Load Show/Hide
include (INCLUDES.'form_js.inc.php');

} ?>
<?php if (($action == "default") && ($filter == "orphans") && ($bid == "default")) { ?>
<h3>Styles Without a Valid Style Type</h3>
<?php
echo orphan_styles();
} ?>