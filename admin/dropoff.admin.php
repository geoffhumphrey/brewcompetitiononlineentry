<?php 

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (strpos($section, "step") === FALSE) && ($_SESSION['userLevel'] > 0))) {
    if (function_exists('redirect_or_exit')) redirect_or_exit("../../403.php");
    else { header("Location: ../../403.php"); exit(); }
}

include (DB.'dropoff.db.php');
$dropoff_loc_url_yes = "";
$dropoff_loc_url_no = "";
if (($section != "step6") && ($_SESSION['brewerCountry'] != "United States")) $us_phone = TRUE; else $us_phone = FALSE;
if ($section != "step6") {
?>
<p class="lead"><?php echo h($_SESSION['contestName']); if ($action == "add") echo ": Add a Drop-Off Location"; elseif ($action == "edit") echo ": Edit a Drop-Off Location"; else echo " Drop-Off Locations"; ?></p>
<?php  } if ($msg == "11") {
	if ($section == "step6") $dropoff_loc_url_yes .= "setup.php?section=step6";
	else $dropoff_loc_url_yes .= "index.php?section=admin&amp;go=judging";
	if ($section == "step6") $dropoff_loc_url_no .= "setup.php?section=step7";
	else $dropoff_loc_url_no .= "index.php?section=admin";
?>
<p class="lead">Add another drop-off location?</p>
<div class="btn-group" role="group" aria-label="judge-loc-yes">
    <a class="btn btn-success" href="<?php echo $base_url.$dropoff_loc_url_yes; ?>"><span class="fa fa-check"></span> Yes</a>
</div><!-- ./button group -->
<div class="btn-group" role="group" aria-label="judge-loc-no">
    <a class="btn btn-danger" href="<?php echo $base_url.$dropoff_loc_url_no; ?>"><span class="fa fa-times"></span> No</a>
</div><!-- ./button group -->
<?php } else { ?>
<?php if (($action == "update") || ($action == "assign")) { ?>
<p><?php if ($bid == "default") echo "Choose ".$filter." to assign.";  else echo "Check below which ".$filter." will be assigned to the ".$row_dropoff['dropLocationName']. " location."; ?></p>
<?php }?>
<?php if (($totalRows_dropoff == 0) && ($section == "step6")) { ?>
<!-- data-toggle (not data-bs-toggle) is deliberate here: app.js manually initializes
     popovers via $('[data-toggle="popover"]').popover(), matching the old attribute
     name, not Bootstrap 5's native data-bs-toggle auto-scan. The option attributes
     BS5's Popover component itself reads (trigger/placement/container/content) DO
     need the -bs- prefix, unlike the toggle selector. -->
<a class="btn btn-primary" type="button" role="button" data-toggle="popover" data-bs-trigger="hover" data-bs-placement="auto right" data-bs-container="body"  data-bs-content="Skip this step if your competition does not have any drop-off locations." href="<?php echo $base_url; ?>includes/process.inc.php?section=setup&amp;action=add&amp;dbTable=<?php echo $drop_off_db_table; ?>&amp;go=skip">Skip This Step&nbsp;&nbsp;<span class="fa fa-lg fa-arrow-circle-right"></span></a>
<?php } ?>
<div class="bcoem-admin-element hidden-print">
<?php if ((($action == "add") || ($action == "edit")) && ($section != "step6")) { ?>
<div class="btn-group" role="group" aria-label="...">
	<a class="btn btn-secondary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff"><span class="fa fa-arrow-circle-left"></span> All Drop-Off Locations</a>
</div><!-- ./button group -->
<?php } elseif (($section != "step6") && ($filter == "default")) { ?>
<div class="btn-group" role="group" aria-label="...">
	<a class="btn btn-secondary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Drop-Off Location</a>
</div><!-- ./button group -->
<?php } ?>
</div>

<?php if ((($action == "add") || ($action == "edit")) || ($section == "step6")) { ?>
<form class="form-horizontal hide-loader-form-submit needs-validation" role="form" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php if ($section == "step6") echo "setup"; else echo $section; ?>&amp;action=<?php if ($section == "step6") echo "add"; else echo $action; ?>&amp;dbTable=<?php echo $drop_off_db_table; ?>&amp;go=<?php if ($go == "default") echo "setup"; else echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1" novalidate>
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
<div class="bcoem-admin-element hidden-print">
<div class="row mb-3">
	<label for="dropLocationName" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Name</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<!-- dropLocationName is already HTML-entity-encoded at save time
		     (process_drop_off.inc.php's sterilize()) - h() here would double-encode it. -->
		<input class="form-control" id="dropLocationName" name="dropLocationName" type="text" value="<?php if ($action == "edit") echo $row_dropoff['dropLocationName']; ?>" placeholder="" autofocus required>
        <div class="help-block invalid-feedback text-danger">The dropoff location's name is required.</div>
	</div>
</div>

<div class="row mb-3">
	<label for="dropLocationPhone" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Phone</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<!-- dropLocationPhone is already HTML-entity-encoded at save time
		     (process_drop_off.inc.php's sterilize()) - h() here would double-encode it. -->
		<input class="form-control" id="dropLocationPhone" name="dropLocationPhone" type="tel" value="<?php if ($action == "edit") echo $row_dropoff['dropLocationPhone']; ?>" placeholder="" required>
        <div class="help-block invalid-feedback text-danger">The dropoff location's phone number is required.</div>
	</div>
</div>

<div class="row mb-3">
	<label for="dropLocation" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Address</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<!-- dropLocation is already HTML-entity-encoded at save time
		     (process_drop_off.inc.php's sterilize()) - h() here would double-encode it. -->
		<input class="form-control" id="dropLocation" name="dropLocation" type="text" value="<?php if ($action == "edit") echo $row_dropoff['dropLocation']; ?>" placeholder="" maxlength="255" required>
		<div class="help-block invalid-feedback text-danger">The dropoff location's address is required.</div>
		<span class="help-block">Provide the street address, city, and zip code. 255 character limit.</span>
	</div>
</div>

<div class="row mb-3">
	<label for="dropLocationWebsite" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Website</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<!-- dropLocationWebsite is already HTML-entity-encoded at save time
		     (process_drop_off.inc.php's sterilize()) - h() here would double-encode it. -->
		<input class="form-control" id="dropLocationWebsite" name="dropLocationWebsite" type="url" value="<?php if ($action == "edit") echo $row_dropoff['dropLocationWebsite']; ?>" placeholder="http://www.yoursite.com" pattern="^(http(s?)\:\/\/)*[0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*(:(0-9)*)*(\/?)([a-zA-Z0-9\-\.\?\,\'\/\\\+&amp;%\$#_]*)?$">
		<div class="help-block invalid-feedback text-danger">Make sure the web address is valid and includes http:// or https://</div>
		<span class="help-block">Be sure to include the full website URL including the http:// or https://</span>
	</div>
</div>

<div class="row mb-3">
	<label for="dropLocationNotes" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Notes</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
		<!-- dropLocationNotes is already HTML-entity-encoded at save time
		     (process_drop_off.inc.php's sterilize()) - h() here would double-encode it. -->
		<input class="form-control" id="dropLocationWNotes" name="dropLocationNotes" type="text" value="<?php if ($action == "edit") echo $row_dropoff['dropLocationNotes']; ?>" placeholder="" maxlength="255">
		<span id="helpBlock" class="help-block">Catch-all for items such as when entries will be picked up at the location, etc. 255 character limit.</span>
	</div>
</div>

</div>
<div class="bcoem-admin-element hidden-print">
	<div class="row mb-3">
		<div class="col-xs-12 col-sm-8 col-lg-6 offset-sm-4 offset-lg-2">
			<input type="submit" name="Submit" id="updateConatact" class="btn btn-primary" value="<?php if (($action == "add") || ($section == "step6")) echo "Add"; else echo "Edit"; ?> Drop-Off Location" />
		</div>
	</div>
</div>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin","default",$msg,$id); ?>">
</form>
<?php }
 }
if (($action == "default") && ($section != "step6")) {
if ($totalRows_dropoff > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'rtp',
			"bStateSave" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : true,
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
  <th>Phone</th>
  <th>Address</th>
  <th>Notes</th>
  <th>Actions</th>
 </tr>
</thead>
<tbody>
 <?php foreach ($rows_dropoff as $row_dropoff) {
 if ($us_phone) $phone = format_phone_us($row_dropoff['dropLocationPhone']);
 else $phone = $row_dropoff['dropLocationPhone'];
 ?>
 <!-- dropLocationName/dropLocation/dropLocationNotes/dropLocationWebsite are already
      HTML-entity-encoded at save time (process_drop_off.inc.php's sterilize()) - h()
      here would double-encode them. dropLocationPhone is sterilize()'d too, but phone
      numbers never contain HTML-special characters, so its h() calls are harmless. -->
 <tr>
	<td><?php echo $row_dropoff['dropLocationName']; ?></td>
	<td nowrap><?php echo h($phone); ?></td>
	<td><?php echo $row_dropoff['dropLocation']; ?></td>
	<td><?php echo $row_dropoff['dropLocationNotes']; ?></td>
	<td>
		<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_dropoff['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit <?php echo $row_dropoff['dropLocationName']; ?>"><span class="fa fa-lg fa-pencil"></span></a>
		<a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $drop_off_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_dropoff['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete <?php echo $row_dropoff['dropLocationName']; ?>" data-confirm="Are you sure you want to delete the <?php echo $row_dropoff['dropLocationName']; ?> location? This cannot be undone."><span class="fa fa-lg fa-trash-o"></span></a>
		<?php if (($row_dropoff['dropLocationWebsite'] != "") && (preg_match('#^https?://#i', $row_dropoff['dropLocationWebsite']))) echo "<a class=\"hide-loader\" href=\"".$row_dropoff['dropLocationWebsite']."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Visit the ".$row_dropoff['dropLocationName']." website\"><span class=\"fa fa-lg fa-link\"></span></a> "; ?>
	</tr>
  <?php } ?>
</tbody>
</table>
<?php } else echo "<p>".$alert_text_005."</p>";
} ?>