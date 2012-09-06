<form action="includes/process.inc.php?section=<?php echo $section; ?>&amp;action=edit&amp;dbTable=<?php echo $users_db_table; ?>&amp;go=make_admin" name="form1" method="post">
<div class="error">Grant users admin access with caution. Admin users are able to add, edit, and delete any information in the database.</div>
<div class="adminSubNavContainer">
  	<span class="adminSubNav">
		<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin">Back to Admin</a></td>
  	</span>
</div>
<table class="dataTable">
<tr>
  <td class="dataHeading" width="5%">Update User Level For:</td>
  <td class="data"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></td>
</tr>
<tr>
  <td class="dataHeading">User Level:</td>
  <td class="data">
  <select name="userLevel">
  <option value="2" <?php if ($row_user_level['userLevel'] == "2") echo "SELECTED"; ?>>Participant</option>
  <option value="1" <?php if ($row_user_level['userLevel'] == "1") echo "SELECTED"; ?>>Admin</option>
  </select>  
  </td>
</table>
<p><input name="Submit" type="submit" class="button" value="Submit"></p>
<input type="hidden" name="user_name" value="<?php echo $username; ?>">
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
