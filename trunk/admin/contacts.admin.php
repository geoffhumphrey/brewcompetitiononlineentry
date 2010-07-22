<h2><?php if ($action == "add") echo "Add a Contact"; elseif ($action == "edit") echo "Edit a Contact"; else echo "Contacts"; ?></h2>
<table class="dataTable">
 <tr>
   <?php if ($action == "default") { ?>
   <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin">Back to Admin</a></td>
   <?php } ?>
   <?php if (($action == "add") || ($action == "edit")) { ?>
   <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin&amp;go=contacts">Back to Contacts List</a></td>
   <?php } else { ?>
   <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/user_add.png"  /></span><a class="data" href="index.php?section=admin&amp;go=contacts&amp;action=add">Add a Contact</a></td>
   <?php } ?>
   </tr>
</table>
<?php if ($totalRows_contact > 0) { ?>
<?php if ($action == "default") { ?>
<table class="dataTable">
 <tr>
  <td class="dataHeading bdr1B">Name</td>
  <td class="dataHeading bdr1B">Position</td>
  <td class="dataHeading bdr1B">Email</td>
  <td class="dataHeading bdr1B">Actions</td>
 </tr>
 <?php do { ?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td width="15%" class="dataList"><?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName'] ; ?></td>
  <td width="15%" class="dataList"><?php echo $row_contact['contactPosition']; ?></td>
  <td width="25%" class="dataList"><?php echo $row_contact['contactEmail']; ?></td>
  <td class="dataList">
  <span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_contact['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit Contact" title="Edit Contact"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=contacts&amp;action=delete','id',<?php echo $row_contact['id']; ?>,'Are you sure you want to delete this contact? This cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName'] ; ?>" title="Delete <?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName'] ; ?>"></a></span></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while($row_contact = mysql_fetch_assoc($contact)) ?>
 <tr>
 	<td colspan="9" class="bdr1T">&nbsp;</td>
 </tr>
</table>
<?php } } else { ?>
<p>There are no contacts in the database.</p>
<?php } ?>
<?php if (($action == "add") || ($action == "edit")) { ?>
<form method="post" action="includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=contacts<?php if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1">
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
    <td class="data"><em>Email addresses are <strong>not</strong> displayed. Used only for contact purposes via the site's <a href="index.php?section=contact">contact form</a></em>.</td>
  </tr>
   <tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="submit" type="submit" class="button" value="<?php if ($action == "edit") echo "Edit"; else echo "Add"; ?> Contact"></td>
  </tr>
</table>
</form>
<?php } ?>