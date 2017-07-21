<?php include (DB.'contacts.db.php'); ?>
<p class="lead"><?php echo $_SESSION['contestName']; if ($action == "add") echo ": Add a Contact"; elseif ($action == "edit") echo ": Edit a Contact"; else echo " Contacts"; ?></p>

<!-- Button Element Container -->
<div class="bcoem-admin-element hidden-print">
	<?php if (($action == "add") || ($action == "edit")) { ?>
	<!-- Postion 1: View All Button -->
	<div class="btn-group" role="group" aria-label="...">
		<a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts"><span class="fa fa-eye"></span> View All Contacts</a>
    </div><!-- ./button group -->
	<?php } else { ?>
	<!-- Postion 1: View All Button -->
	<div class="btn-group" role="group" aria-label="...">
		<a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Contact</a>
    </div><!-- ./button group -->
	<?php } ?>
</div>
<?php if (get_contact_count() > 0) { ?>
<?php if ($action == "default") { ?>
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
				]
			} );
		} );
</script>
<table class="table table-responsive table-striped table-bordered dataTable" id="sortable">
<thead>
 <tr>
  <th>Name</th>
  <th>Position</th>
  <th>Email</th>
  <th>Actions</th>
 </tr>
</thead>
<tbody>
 <?php do { ?>
 <tr>
  <td><?php echo $row_contact['contactLastName'].", ".$row_contact['contactFirstName'] ; ?></td>
  <td><?php echo $row_contact['contactPosition']; ?></td>
  <td><?php echo $row_contact['contactEmail']; ?></td>
  <td>
  <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_contact['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit <?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName'] ; ?>&rsquo;s contact information"><span class="fa fa-lg fa-pencil"></span></a> <a href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $contacts_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_contact['id']; ?>" data-confirm="Are you sure you want to delete <?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName']; ?> as a contact? This cannot be undone."><span class="fa fa-lg fa-trash-o"></span></a>
  
  <!--<img src="<?php echo $base_url; ?>images/bin_closed.png"  border="0" alt="Delete <?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName'] ; ?>" title="Delete <?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName'] ; ?>"></a></span>
  --></td>
 </tr>
  <?php } while($row_contact = mysqli_fetch_assoc($contact)) ?>
</tbody>
</table>
<?php } } else { ?>
<p>There are no contacts in the database.</p>
<?php } ?>
<?php if (($action == "add") || ($action == "edit")) { 
$form_url = $base_url."includes/process.inc.php?action=".$action."&amp;dbTable=".$contacts_db_table; 
if ($action == "edit") $form_url .= "&amp;id=".$id;
?>
<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $form_url; ?>" name="form1">
<div class="bcoem-admin-element hidden-print">
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="contactFirstName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">First Name</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="contactFirstName" name="contactFirstName" type="text" value="<?php if ($action == "edit") echo $row_contact['contactFirstName']; ?>" placeholder="" data-error="The contact's first name is required" autofocus required>
			<span class="input-group-addon" id="contactFirstName-addon2"><span class="fa fa-star"></span></span>
		</div>
        <div class="help-block with-errors"></div>
	</div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="contactLastName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Last Name</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="contactLastName" name="contactLastName" type="text" value="<?php if ($action == "edit") echo $row_contact['contactLastName']; ?>" placeholder="" data-error="The contact's last name is required" required>
			<span class="input-group-addon" id="contactLastName-addon2"><span class="fa fa-star"></span></span>
		</div>
        <div class="help-block with-errors"></div>
	</div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="contactPosition" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Position</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="contactPosition" name="contactPosition" type="text" value="<?php if ($action == "edit") echo $row_contact['contactPosition']; ?>" placeholder="" data-error="The contact's position is required" required>
			<span class="input-group-addon" id="contactPosition-addon2"><span class="fa fa-star"></span></span>
		</div>
        <div class="help-block with-errors"></div>
	</div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="contactEmail" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Email</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="contactEmail" name="contactEmail" type="email" value="<?php if ($action == "edit") echo $row_contact['contactEmail']; ?>" placeholder="" data-error="The contact's email address is required or invalid" required>
			<span class="input-group-addon" id="contactEmail-addon2"><span class="fa fa-star"></span></span>
		</div>
        <div class="help-block with-errors"></div>
		<span id="helpBlock" class="help-block">Email addresses are <strong>not</strong> displayed. Used only for contact purposes via the site&rsquo;s <a href="<?php echo $base_url; ?>index.php?section=contact">contact form</a>.</span>
	</div>
</div><!-- ./Form Group -->
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=contacts","default",$msg,$id); ?>">
</div>
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" name="Submit" id="updateConatact" class="btn btn-primary" value="<?php if ($action == "add") echo "Add"; else echo "Edit"; ?> Contact" />
		</div>
	</div>
</div>
</form>
<?php } ?>