<?php include(DB.'contacts.db.php'); ?>
<h2><?php if ($action == "add") echo "Add a Contact"; elseif ($action == "edit") echo "Edit a Contact"; else echo "Contacts"; ?></h2>
<div class="adminSubNavContainer">
	<?php if ($action == "default") { ?>
   	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin">Back to Admin Dashboard</a>
    </span>
   	<?php } ?>
   	<?php if (($action == "add") || ($action == "edit")) { ?>
   <span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts">Back to Contacts List</a>
   </span>
   <?php } else { ?>
   <span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/user_add.png"  /></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts&amp;action=add">Add a Contact</a>
   </span>
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
			"sDom": 'irtip',
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
<table class="dataTable" id="sortable">
<thead>
 <tr>
  <th class="dataHeading bdr1B">Name</th>
  <th class="dataHeading bdr1B">Position</th>
  <th class="dataHeading bdr1B">Email</th>
  <th class="dataHeading bdr1B">Actions</th>
 </tr>
</thead>
<tbody>
 <?php do { ?>
 <tr>
  <td width="15%" class="dataList"><?php echo $row_contact['contactLastName'].", ".$row_contact['contactFirstName'] ; ?></td>
  <td width="15%" class="dataList"><?php echo $row_contact['contactPosition']; ?></td>
  <td width="25%" class="dataList"><?php echo $row_contact['contactEmail']; ?></td>
  <td class="dataList">
  <span class="icon"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_contact['id']; ?>"><img src="<?php echo $base_url; ?>images/pencil.png"  border="0" alt="Edit Contact" title="Edit Contact"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $contacts_db_table; ?>&amp;action=delete','id',<?php echo $row_contact['id']; ?>,'Are you sure you want to delete this contact? This cannot be undone.');"><img src="<?php echo $base_url; ?>images/bin_closed.png"  border="0" alt="Delete <?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName'] ; ?>" title="Delete <?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName'] ; ?>"></a></span></td>
 </tr>
  <?php } while($row_contact = mysql_fetch_assoc($contact)) ?>
</tbody>
</table>
<?php } } else { ?>
<p>There are no contacts in the database.</p>
<?php } ?>
<?php if (($action == "add") || ($action == "edit")) { ?>
<form method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $contacts_db_table; ?><?php if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1">
<table>
  <tr>
    <td class="dataLabel">First Name:</td>
    <td class="data"><input name="contactFirstName" type="text" size="50" maxlength="255" value="<?php if ($action == "edit") echo $row_contact['contactFirstName']; ?>"></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Last Name:</td>
    <td class="data"><input name="contactLastName" type="text" size="50" maxlength="255" value="<?php if ($action == "edit") echo $row_contact['contactLastName']; ?>"></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Position:</td>
    <td class="data"><input name="contactPosition" type="text" size="50" maxlength="255" value="<?php if ($action == "edit") echo $row_contact['contactPosition']; ?>"></td>
    <td class="data"><em>Competition Coordinator, Head Judge, Cellar Master, etc.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Email Address:</td>
    <td class="data"><input name="contactEmail" type="text" size="50" maxlength="255" value="<?php if ($action == "edit") echo $row_contact['contactEmail']; ?>"></td>
    <td class="data"><em>Email addresses are <strong>not</strong> displayed. Used only for contact purposes via the site's <a href="<?php echo $base_url; ?>index.php?section=contact">contact form</a></em>.</td>
  </tr>
   <tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="submit" type="submit" class="button" value="<?php if ($action == "edit") echo "Edit"; else echo "Add"; ?> Contact"></td>
  </tr>
</table>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } ?>