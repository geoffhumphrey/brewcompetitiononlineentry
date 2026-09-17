<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (strpos($section, "step") === FALSE) && ($_SESSION['userLevel'] > 0))) {
    if (function_exists('redirect_or_exit')) redirect_or_exit("../../403.php");
    else { header("Location: ../../403.php"); exit(); }
}

if ($section != "step7") include (DB.'judging_locations.db.php');
include (DB.'styles.db.php');
if ($_SESSION['style_set_no_numbering']) include (INCLUDES.'ba_constants.inc.php');

// Broader "Overall Category" grouping (optional, currently only ever set
// by admin-uploaded style sets - e.g. GABF's "Lager Beer Styles" spanning
// many numbered categories). Looked up once here rather than per-row.
require (INCLUDES.'styles.inc.php');
$active_overall_categories = array();
foreach ($style_sets as $style_set_data) {
	if ((!empty($style_set_data)) && ($style_set_data['style_set_name'] === $_SESSION['prefsStyleSet'])) {
		if (!empty($style_set_data['style_set_overall_categories'])) $active_overall_categories = $style_set_data['style_set_overall_categories'];
		break;
	}
}

// Build style table body
$table_body = "";

if ((($action == "default") && ($filter == "default")) || ($section == "step7") || (($action == "default") && ($filter == "judging") && ($bid != "default"))) {

	$sorting_default = "[[2,'asc']]";

	$current_styles_active = json_decode($_SESSION['prefsSelectedStyles'],true);

	foreach ($rows_styles as $row_styles) {

		if ($row_styles['id'] != "") {

			$saving_random_num = random_generator(8,2);

			$brewStyleActive = "";
			if (array_key_exists($row_styles['id'],$current_styles_active)) $brewStyleActive = "CHECKED";

			$brewStyleAtLimit = "";
			if ((!empty($row_styles['brewStyleAtLimit'])) && ($row_styles['brewStyleAtLimit'] == 1)) $brewStyleAtLimit = "CHECKED";

			$brewStyleOwn_prefix = "";
			$brewStyleOwn_suffix = "";
			 if ($row_styles['brewStyleOwn'] == "custom") {
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
			if ($bid == "default") {
				$table_body .= "<td nowrap>";
				$table_body .= "<div id=\"active-ajax-".$saving_random_num."-brewStyleActive-form-group\">";
				$table_body .= "<input class=\"enable-style\" name=\"brewStyleActive".$row_styles['id']."\" id=\"active-ajax-".$saving_random_num."\" data-style-id=\"".$row_styles['id']."\" type=\"checkbox\" value=\"Y\" onclick=\"$(this).attr('value', this.checked ? 'Y' : '');return save_column('".$ajax_url."','brewStyleActive','styles','".$row_styles['id']."','default','default','default','default','active-ajax-".$saving_random_num."','value')\" ".$brewStyleActive.">";
				$table_body .= "</div>";
				$table_body .= "<span style=\"margin-left:5px;\" id=\"active-ajax-".$saving_random_num."-brewStyleActive-status\"></span>";
				$table_body .= "<span style=\"margin-left:5px;\" id=\"active-ajax-".$saving_random_num."-brewStyleActive-status-msg\"></span>";
				$table_body .= "</td>";
			}
			if ($bid != "default") $table_body .= "<td nowrap><input class=\"enable-style\" name=\"brewStyleJudgingLoc".$row_styles['id']."\" type=\"checkbox\" value=\"".$bid."\" ".$brewStyleJudgingLoc."></td>";
			// brewStyle is already HTML-entity-encoded at save time (process_styles.inc.php's
			// purify()) - h() here would double-encode it, same as the edit form below (which
			// already displays it without h()).
			$table_body .= "<td>".$row_styles['brewStyle']."</td>";
			if ($row_styles['brewStyleOwn'] == "custom") $table_body .= "<td>*Custom Style</td>";
			elseif (isset($active_overall_categories[$row_styles['brewStyleGroup']])) $table_body .= "<td>".h($active_overall_categories[$row_styles['brewStyleGroup']])."</td>";
			elseif ($_SESSION['style_set_no_numbering']) {
				$table_body .= "<td>".$ba_category_names[ltrim($row_styles['brewStyleGroup'],"0")]."</td>";
			}
			elseif ($_SESSION['prefsStyleSet'] == "AABC") {
				$table_body .= "<td>".ltrim($row_styles['brewStyleGroup'], "0").".".ltrim($row_styles['brewStyleNum'], "0")."</td>";
			}
			else $table_body .= "<td>".$brewStyleOwn_prefix.$row_styles['brewStyleGroup'].$row_styles['brewStyleNum'].$brewStyleOwn_suffix."</td>";
			$table_body .= "<td>".style_type($row_styles['brewStyleType'],"2",$style_own)."</td>";
			$table_body .= "<td>".$brewStyleReqSpec.$brewStyleStrength.$brewStyleCarb.$brewStyleSweet."</td>";
			$table_body .= "<td nowrap>";
			$table_body .= "<div id=\"limit-ajax-".$saving_random_num."-brewStyleAtLimit-form-group\">";
			$table_body .= "<input class=\"limit-style\" name=\"brewStyleAtLimit".$row_styles['id']."\" id=\"limit-ajax-".$saving_random_num."\" data-style-id=\"".$row_styles['id']."\" type=\"checkbox\" value=\"1\" onclick=\"$(this).attr('value', this.checked ? 1 : 0);return save_column('".$ajax_url."','brewStyleAtLimit','styles','".$row_styles['id']."','default','default','default','default','limit-ajax-".$saving_random_num."','value')\" ".$brewStyleAtLimit.">";
			$table_body .= "</div>";
			$table_body .= "<span style=\"margin-left:5px;\" id=\"limit-ajax-".$saving_random_num."-brewStyleAtLimit-status\"> </span>";
			$table_body .= "<span style=\"margin-left:5px;\" id=\"limit-ajax-".$saving_random_num."-brewStyleAtLimit-status-msg\"></span> ";
			$table_body .= "</td>";
			$table_body .= "<td class=\"hidden-print\">";
			if ($section != "step7") {
				// brewStyle is purify()'d (HTMLPurifier) at save time, which sanitizes
				// markup structure but does NOT entity-encode incidental characters like
				// a bare double-quote - safe to echo as-is in TEXT content (the <td> cell
				// above), but still needs h() wherever it's embedded inside an HTML
				// attribute (title=/data-confirm= below), or a style name containing a
				// literal " would break out of the attribute value. brewStyleLink IS
				// sterilize()'d (htmlspecialchars), so it's attribute-safe without h()
				// in its own href= just below.
				if ($row_styles['brewStyleOwn'] != "bcoe") $table_body .= "<a href=\"".$base_url."index.php?section=admin&amp;go=".$go."&amp;action=edit&amp;id=".$row_styles['id']."&amp;view=".$row_styles['brewStyleType']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit ".h($row_styles['brewStyle'])."\"><span class=\"fa fa-lg fa-pencil\"></span></a> <a class=\"hide-loader\" href=\"".$base_url."includes/process.inc.php?section=admin&amp;go=".$go."&amp;dbTable=".$styles_db_table."&amp;action=delete&amp;id=".$row_styles['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete ".h($row_styles['brewStyle'])."\" data-confirm=\"Are you sure you want to delete ".h($row_styles['brewStyle'])."? This cannot be undone. Deleting a custom style will remove it and associated entries from any public past winner lists. To avoid this, simply deactivate the style.\"><span class=\"fa fa-lg fa-trash-o\"></span></a> ";
				else $table_body .= "<span class=\"fa fa-lg fa-pencil text-muted\"></span> <span class=\"fa fa-lg fa-trash-o text-muted\"></span> ";
			}
			if (($row_styles['brewStyleLink'] != "") && (preg_match('#^https?://#i', $row_styles['brewStyleLink']))) $table_body .= "<a class=\"hide-loader\" href=\"".$row_styles['brewStyleLink']."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Link to BJCP ".h($row_styles['brewStyle'])." sub-style on bjcp.org\"><span class=\"fa fa-lg fa-link\"></span></a>";
			$table_body .= "</td>";
			$table_body .= "</tr>";

		}

	}

}

if ($section != "step7") { ?>
<p class="lead"><?php echo $_SESSION['contestName']; if ($action == "add") echo ": Add a Custom Style"; elseif ($action == "edit") echo ": Edit a Custom Style" ; elseif (($action == "default") && ($filter == "judging") && ($bid != "default")) echo ": Style Judged at ".h($row_judging['judgingLocName']); else echo " Accepted Styles"; ?></p>
<?php if (($filter == "default") && ($action == "default")) { ?><p class="lead"><span class="small">Check or uncheck the styles <?php if (($action == "default") && ($filter == "judging") && ($bid != "default")) { echo "that will be judged at ".$row_judging['judgingLocName']." on "; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"); } else echo "your competition will accept (any custom styles are at the top of the list)"; ?>.</span></p><?php } ?>
<div class="bcoem-admin-element hidden-print">
	<?php if ($action != "default") { ?>
	<!-- Postion 1: View All Button -->
	<div class="btn-group" role="group" aria-label="all-styles">
        <a class="btn btn-secondary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles"><span class="fa fa-arrow-circle-left"></span> All Styles</a>
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
			<li class="small"><a class="dropdown-item" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style</a></li>
			<li class="small"><a class="dropdown-item" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types&amp;action=add">Add a Style Type</a></li>
		</ul>
	</div>
	<?php } ?>
</div>
<?php } if ((($action == "default") && ($filter == "default")) || ($section == "step7") || (($action == "default") && ($filter == "judging") && ($bid != "default"))) { ?>

<script type="text/javascript" language="javascript">
var stylesAjaxUrl = "<?php echo $ajax_url; ?>";

function syncSelectAll($selectAll, $group) {
  const allChecked = $group.length === $group.filter(':checked').length;
  $selectAll.prop('checked', allChecked);
}

function handleSelectAll($selectAll, $group) {
  const allChecked = $group.length === $group.filter(':checked').length;
  const newState = !allChecked;
  $selectAll.prop('checked', newState);

  // Every checkbox in this column saves to a MyISAM-engine table
  // (table-level locking, not row-level - both {prefix}styles and
  // {prefix}preferences use it). Saving each row as its own request -
  // whether all at once (concurrent) or one after another (sequential) -
  // means one PHP/DB round trip per row: concurrently, that's dozens/
  // hundreds of requests queued on the same table lock and can exceed the
  // DB's max_connections for a large style set (confirmed live: ~150
  // simultaneous saves silently fail a handful of requests); sequentially
  // it's reliable but still N round trips (confirmed live: ~195 styles
  // took ~25 seconds). Since "select all" always applies the exact same
  // new state to every row, the whole batch instead goes through as ONE
  // request carrying every affected style's id, which the server folds
  // into one SQL statement - one DB round trip and one MyISAM table-lock
  // acquisition total, regardless of how many rows changed.
  var toChange = $group.toArray().filter(function (el) {
    return el.checked !== newState;
  });
  if (toChange.length === 0) return;

  toChange.forEach(function (el) { el.checked = newState; });

  var ids = toChange.map(function (el) { return $(el).data('style-id'); });
  var is_active_column = $(toChange[0]).hasClass('enable-style');
  var column = is_active_column ? 'brewStyleActive' : 'brewStyleAtLimit';
  var value = is_active_column ? (newState ? 'Y' : '') : (newState ? 1 : 0);

  // One status indicator next to the "select all" checkbox itself, not
  // one per row - this is a single batched save covering potentially
  // hundreds of rows, so flashing "Saved" on every individual row's
  // status spans wouldn't reflect what's actually happening (one request)
  // and is a lot of needless DOM churn for a large style set.
  save_column_batch(stylesAjaxUrl, column, 'styles', ids, [$selectAll.attr('id')], value);
}

$(document).ready(function () {

  const $selectAllEnable = $('#select-all-enable');
  const $selectAllLimit  = $('#select-all-limit');
  const $enableBoxes     = $('.enable-style');
  const $limitBoxes      = $('.limit-style');

  <?php if ($bid == "default") { ?>
  disable_update_button('styles');
  <?php } ?>

  // Select-all checkbox click handlers
  $selectAllEnable.on('change', function () {
    handleSelectAll($selectAllEnable, $enableBoxes);
  });

  $selectAllLimit.on('change', function () {
    handleSelectAll($selectAllLimit, $limitBoxes);
  });

  // Individual checkbox change handlers — keep select-all in sync
  $enableBoxes.on('change', function () {
    syncSelectAll($selectAllEnable, $enableBoxes);
  });

  $limitBoxes.on('change', function () {
    syncSelectAll($selectAllLimit, $limitBoxes);
  });

  syncSelectAll($selectAllEnable, $enableBoxes);
  syncSelectAll($selectAllLimit, $limitBoxes);

  $('#sortable').dataTable({
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
  		{ "asSorting": [  ] },
  		{ "asSorting": [  ] }
  		]
  	});

});
</script>
<script src="<?php echo $js_url; ?>admin_ajax.min.js"></script>
<form name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php if ($section == "step7") echo "setup"; else echo $section; ?>&amp;action=update&amp;dbTable=<?php echo $styles_db_table; ?>&amp;filter=<?php echo $filter; if ($bid != "default") echo "&amp;bid=".$bid; ?>">
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
<table class="table table-responsive table-striped table-bordered" id="sortable">
<thead>
 <tr>
  <th width="10%"><input type="checkbox" id="select-all-enable" /> Enable/Disable All <span style="margin-left:5px;" id="select-all-enable-brewStyleActive-status"></span><span style="margin-left:5px;" id="select-all-enable-brewStyleActive-status-msg"></span></th>
  <th>Style Name</th>
  <th><?php if (strpos($_SESSION['prefsStyleSet'],"BJCP") === false) echo "Overall Category"; else echo "#"; ?></th>
  <th>Style Type</th>
  <th>Requirements</th>
  <th nowrap="nowrap"><input type="checkbox" id="select-all-limit" /> Restrict Entries <span style="margin-left:5px;"  id="select-all-limit-brewStyleAtLimit-status"></span><span style="margin-left:5px;" id="select-all-limit-brewStyleAtLimit-status-msg"></span> <a tabindex="0" type="button" role="button" data-toggle="popover" data-bs-html="true" data-bs-trigger="hover" data-bs-placement="top" data-bs-container="body" data-bs-content="If you want to restrict further entries for a style on the fly, check its corresponding box in this column - it saves automatically. <strong>Please Note:</strong> This will override any table-level restriction if using Table Limits in Tables Planning Mode."><i class="fa fa-question-circle text-info"></i></a></th>
  <th class="hidden-print">Actions</th>
 </tr>
 </thead>
 <tbody>
 <?php echo $table_body; ?>
 </tbody>
 </table>
 <div class="bcoem-admin-element hidden-print">
	<?php if ($bid == "default") { ?>
	<input type="submit" name="Submit" id="styles-submit" class="btn btn-primary" aria-describedby="helpBlock" value="Update Accepted Styles" disabled />
	<span id="styles-update-button-enabled" class="help-block">Select "Update Accepted Styles" <em>before</em> paging through records.</span>
	<span id="styles-update-button-disabled" class="help-block">The "Update Accepted Styles" button has been disabled since data is being saved automatically as it is entered. It will re-enable itself if a save fails, so you can retry from here.</span>
	<?php } else { ?>
	<input type="submit" name="Submit" id="helpUpdateStyles" class="btn btn-primary" aria-describedby="helpBlock" value="Update <?php echo $row_judging['judgingLocName']; ?>" />
	<span id="helpBlock" class="help-block">Select "Update <?php echo $row_judging['judgingLocName']; ?>" <em>before</em> paging through records.</span>
	<?php } ?>
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

<script>
// Check if the entered style/sub-style combination of identifiers are in use
var style_url = "<?php echo $ajax_url; ?>custom_style.ajax.php";
var action = "<?php echo $action; ?>";

$("#style-identifier-status").hide();

function checkStyleIdentifier() {

	$("#style-identifier-status").hide();

	var rid1 = $("#brewStyleGroup").val();
	var rid2 = $("#brewStyleNum").val();

	let url = style_url + "?rid1=" + rid1 + "&rid2=" + rid2;

	<?php if ($action == "edit") { ?>
	// If editing, establish constants for ajax script to compare against
	var rid3 = "<?php echo $row_styles['brewStyleGroup']; ?>";
	var rid4 = "<?php echo $row_styles['brewStyleNum']; ?>";
	url += "&rid3=" + rid3
	url += "&rid4=" + rid4
	<?php } ?>

	if ((rid1) && (rid2)) {

		var disabled = $('#updateStyle').is(':disabled');

		jQuery.ajax({
			url: url,
			data: "",
			type: "POST",
			success:function(data) {

				var jsonData = JSON.parse(data);

				if (jsonData.status <= "2") {
					$('#updateStyle').prop("disabled", true);
				}

				else {
					$('#updateStyle').prop("disabled", false);
				}

				if (jsonData.status <= "3") {
					$("#style-identifier-status").show();
					$("#style-identifier-status").html(jsonData.message);
				}

			},

			error:function () {

			}
		});
	}


}
</script>

<form class="form-horizontal hide-loader-form-submit needs-validation" role="form" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $styles_db_table; ?>&amp;go=<?php echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" id="form1" name="form1" novalidate>
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
<div class="row mb-3"><!-- Form Group REQUIRED Text Input -->
	<label for="brewStyle" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Name</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" id="brewStyle" name="brewStyle" type="text" value="<?php if ($action == "edit") echo h($row_styles['brewStyle']); ?>" placeholder="" autofocus required>
		<div class="help-block invalid-feedback text-danger">The custom style's name is required.</div>
	</div>
</div>
<div class="row mb-3">
	<label for="brewStyleGroup" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Style Number or Identifier</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" id="brewStyleGroup" name="brewStyleGroup" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleGroup']; ?>" placeholder="" maxlength="3" required onInput="checkStyleIdentifier()" onKeypress="checkStyleIdentifier()">
		<div class="help-block invalid-feedback text-danger">The custom style number or identifier is required.</div>
		<div class="help-block">Provide the overall identifier for the style. Three (3) character limit.</div>
	</div>
</div>
<div class="row mb-3">
	<label for="brewStyleNum" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Sub-Style Number or Identifier</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" id="brewStyleNum" name="brewStyleNum" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleNum']; ?>" placeholder="" maxlength="2" required onInput="checkStyleIdentifier()" onKeypress="checkStyleIdentifier()">
		<div class="help-block invalid-feedback text-danger">The custom style category's sub-style identifer is required.</div>
		<div class="help-block">Provide a <strong>unique</strong> identifier for this style. Two (2) character limit.</div>
       	<div>Style Status: <span id="style-identifier-status">Awaiting input.</span></div>
	</div>
</div>
<div class="row mb-3"><!-- Form Group REQUIRED Select -->
	<label for="brewStyleType" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Style Type</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
	<select class="form-select bootstrap-select" name="brewStyleType" id="brewStyleType">
        <?php foreach ($rows_style_type as $row_style_type) {
        if ($row_style_type['styleTypeName'] != "Mead/Cider") { ?>
        <option value="<?php echo $row_style_type['id']; ?>" <?php if (($action == "edit") && ($row_styles['brewStyleType'] == $row_style_type['id'])) echo "SELECTED"; ?>><?php echo h($row_style_type['styleTypeName']); ?></option>
    	<?php }
    	} ?>
	</select>
	<div class="help-block"><a class="btn btn-sm btn-primary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Style Type</a></div>
	</div>
</div>
<div class="row mb-3"><!-- Form Group Radio INLINE -->
	<label for="brewStyleReqSpec" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Required Info</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="brewStyleReqSpec" value="1" id="brewStyleReqSpec_0" <?php if ($row_styles['brewStyleReqSpec'] == 1) echo "checked"; ?>>
			<label class="form-check-label" for="brewStyleReqSpec_0">Yes</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="brewStyleReqSpec" value="0" id="brewStyleReqSpec_1" <?php if (($action == "add") || ($row_styles['brewStyleReqSpec'] == 0)) echo "checked"; ?>>
			<label class="form-check-label" for="brewStyleReqSpec_1">No</label>
		</div>
	</div>
</div>
<div id="mead-cider">
	<div class="row mb-3"><!-- Form Group Radio INLINE -->
		<label for="brewStyleCarb" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Require Carbonation</label>
		<div class="col-xs-12 col-sm-8 col-lg-6">
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="brewStyleCarb" value="1" id="brewStyleCarb_0" <?php if ($row_styles['brewStyleCarb'] == 1) echo "checked"; ?>>
				<label class="form-check-label" for="brewStyleCarb_0">Yes</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="brewStyleCarb" value="0" id="brewStyleCarb_1" <?php if (($action == "add") || (($action == "edit") && ($row_styles['brewStyleCarb'] == 0))) echo "checked"; ?>>
				<label class="form-check-label" for="brewStyleCarb_1">No</label>
			</div>
			<div class="help-block invalid-feedback text-danger">A carbonation requirement is required for Mead/Cider styles.</div>
		</div>
	</div>
	<div class="row mb-3"><!-- Form Group Radio INLINE -->
		<label for="brewStyleSweet" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Require Sweetness</label>
		<div class="col-xs-12 col-sm-8 col-lg-6">
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="brewStyleSweet" value="1" id="brewStyleSweet_0" <?php if ($row_styles['brewStyleSweet'] == 1) echo "checked"; ?>>
				<label class="form-check-label" for="brewStyleSweet_0">Yes</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="brewStyleSweet" value="0" id="brewStyleSweet_1" <?php if (($action == "add") || (($action == "edit") && ($row_styles['brewStyleSweet'] == 0))) echo "checked"; ?>>
				<label class="form-check-label" for="brewStyleSweet_1">No</label>
			</div>
			<div class="help-block invalid-feedback text-danger">A sweetness requirement is required for Mead/Cider styles.</div>
		</div>
	</div>
</div>
<div id="mead">
	<div class="row mb-3"><!-- Form Group Radio INLINE -->
		<label for="brewStyleStrength" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Require Strength</label>
		<div class="col-xs-12 col-sm-8 col-lg-6">
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="brewStyleStrength" value="1" id="brewStyleStrength_0" <?php if ($row_styles['brewStyleStrength'] == 1) echo "checked"; ?>>
				<label class="form-check-label" for="brewStyleStrength_0">Yes</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="brewStyleStrength" value="0" id="brewStyleStrength_1" <?php if (($action == "add") || (($action == "edit") && ($row_styles['brewStyleStrength'] == 0))) echo "checked"; ?>>
				<label class="form-check-label" for="brewStyleStrength_1">No</label>
			</div>
			<div class="help-block invalid-feedback text-danger">A strength requirement is required for Mead styles.</div>
		</div>
	</div>
</div>

<div id="brewStyleEntry" class="row mb-3">
	<label for="brewStyleEntry" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Entry Info</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<textarea class="form-control" name="brewStyleEntry" id="brewStyleEntryTextArea" rows="6"><?php if ($action == "edit") echo $row_styles['brewStyleEntry']; ?></textarea>
		<div class="help-block invalid-feedback text-danger">Entry requirements are required when Required Info is set to Yes.</div>
		<div class="help-block"><strong class="text-danger">Required:</strong> provide requirements for entry (e.g., <em>Entrant must specify yeast strain(s) used</em>, etc.).</div>
	 </div>
</div>

<div class="row mb-3">
	<label for="brewStyleInfo" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Description</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<textarea class="form-control" name="brewStyleInfo" id="brewStyleInfoTextArea" rows="6"><?php if ($action == "edit") echo $row_styles['brewStyleInfo']; ?></textarea>
		<div class="help-block">Provide a short description of the style.</div>
	 </div>
</div>

<div class="row mb-3">
	<label for="brewStyleOG" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">OG Minimum</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" name="brewStyleOG" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleOG']; ?>" placeholder="">
	</div>
</div>

<div class="row mb-3">
	<label for="brewStyleOGMax" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">OG Maximum</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" name="brewStyleOGMax" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleOGMax']; ?>" placeholder="">
	</div>
</div>

<div class="row mb-3">
	<label for="brewStyleFG" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">FG Minimum</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" name="brewStyleFG" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleFG']; ?>" placeholder="">
	</div>
</div>

<div class="row mb-3">
	<label for="brewStyleFGMax" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">FG Maximum</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" name="brewStyleFGMax" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleFGMax']; ?>" placeholder="">
	</div>
</div>

<div class="row mb-3">
	<label for="brewStyleABV" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">ABV Minimum</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" name="brewStyleABV" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleABV']; ?>" placeholder="">
	</div>
</div>

<div class="row mb-3">
	<label for="brewStyleABVMax" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">ABV Maximum</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" name="brewStyleABVMax" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleABVMax']; ?>" placeholder="">
	</div>
</div>

<div class="row mb-3">
	<label for="brewStyleIBU" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">IBU Minimum</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" name="brewStyleIBU" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleIBU']; ?>" placeholder="">
	</div>
</div>

<div class="row mb-3">
	<label for="brewStyleIBUMax" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">IBU Maximum</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" name="brewStyleIBUMax" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleIBUMax']; ?>" placeholder="">
	</div>
</div>

<div class="row mb-3">
	<label for="brewStyleSRM" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Color Minimum</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" name="brewStyleSRM" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleSRM']; ?>" placeholder="">
	</div>
</div>

<div class="row mb-3">
	<label for="brewStyleSRMMax" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Color Maximum</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<input class="form-control" name="brewStyleSRMMax" type="text" value="<?php if ($action == "edit") echo $row_styles['brewStyleSRMMax']; ?>" placeholder="">
	</div>
</div>

<input type="hidden" name="brewStyleOld" value="<?php if ($action == "edit") echo h($row_styles['brewStyle']);?>">
<input type="hidden" name="brewStyleActive" value="<?php if ($action == "edit") echo $row_styles['brewStyleActive']; else echo "Y"; ?>">
<input type="hidden" name="brewStyleOwn" value="<?php if ($action == "edit") echo $row_styles['brewStyleOwn']; else echo "custom"; ?>">
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=styles","default",$msg,$id); ?>">
<?php } ?>

<div class="bcoem-admin-element hidden-print">
	<div class="row mb-3">
		<div class="col-xs-12 col-sm-8 col-lg-6 offset-sm-4 offset-lg-2">
			<input type="submit" name="Submit" id="updateStyle" class="btn btn-primary" value="<?php if ($action == "add") echo "Add"; else echo "Edit"; ?> Custom Style">
		</div>
	</div>
</div>

</form>

<?php }
if (($action == "default") && ($filter == "orphans") && ($bid == "default")) { ?>
<h3>Styles Without a Valid Style Type</h3>
<?php
echo orphan_styles();
} ?>
