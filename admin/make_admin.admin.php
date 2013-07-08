<?php 
$query_username = sprintf("SELECT user_name FROM %s WHERE id='%s'",$users_db_table,$row_brewer['uid']);
$username = mysql_query($query_username, $brewing) or die(mysql_error());
$row_username = mysql_fetch_assoc($username);

?>

<form action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=edit&amp;dbTable=<?php echo $users_db_table; ?>&amp;go=make_admin" name="form1" method="post">
<div class="error">Grant users top-level admin and admin access with caution.</div>
<div class="adminSubNavContainer">
  	<span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin">Back to Admin Dashboard</a></td>
  	</span>
</div>
<p>Top-level admins are able have full access to add, change, and delete all information in the database, including preferences, competition information, and archival data.  Admin users are able to add, edit, and most information in the database, including participants, entries, tables, scores, etc.</p>
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
  <option value="0" <?php if ($row_user_level['userLevel'] == "0") echo "SELECTED"; ?>>Top-Level Admin</option>
  </select>  
  </td>
</table>
<p><input name="Submit" type="submit" class="button" value="Submit"></p>
<input type="hidden" name="user_name" value="<?php echo $row_username['user_name']; ?>">
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>

