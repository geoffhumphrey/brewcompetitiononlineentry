<?php 
/**
 * Module:      brewer.sec.php 
 * Description: This module houses the functionality for users to add/edit their personal 
 *              information - references the "brewer" database table.
 * 
 */
mysql_select_db($database, $brewing);
if ($section != "step2") {
include(DB.'judging_locations.db.php');
include(DB.'stewarding.db.php'); 
include(DB.'styles.db.php');
}
include(DB.'brewer.db.php');
if ($section != "step2") {
	mysql_select_db($database, $brewing);
	$query_brewerID = sprintf("SELECT * FROM $brewer_db_table WHERE id = '%s'", $id); 
	$brewerID = mysql_query($query_brewerID, $brewing) or die(mysql_error());
	$row_brewerID = mysql_fetch_assoc($brewerID);
	$totalRows_brewerID = mysql_num_rows($brewerID);
} 
if ($section == "step2")  {
	mysql_select_db($database, $brewing);
	$query_brewerID = sprintf("SELECT * FROM $users_db_table WHERE user_name = '%s'", $go); 
	$brewerID = mysql_query($query_brewerID, $brewing) or die(mysql_error());
	$row_brewerID = mysql_fetch_assoc($brewerID);
	$totalRows_brewerID = mysql_num_rows($brewerID);
}
if (($action != "print") && ($msg != "default")) echo $msg_output; 
if (($section == "step2") || ($action == "add") || (($action == "edit") && (($_SESSION["loginUsername"] == $row_brewerID['brewerEmail'])) || ($row_user['userLevel'] <= "1")))  { ?>
<?php if ($section == "step2") { ?>
<form action="<?php echo $base_url; ?>/includes/process.inc.php?section=setup&amp;action=add&amp;dbTable=<?php echo $brewer_db_table; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()"> 
<input name="brewerSteward" type="hidden" value="N" />
<input name="brewerJudge" type="hidden" value="N" />
<input name="brewerEmail" type="hidden" value="<?php echo $go; ?>" />
<input name="uid" type="hidden" value="<?php echo $row_brewerID['id']; ?>" />
<?php } else { ?>
<form action="<?php echo $base_url; ?>/includes/process.inc.php?section=<?php if ($section == "brewer") echo "list"; else echo "admin"; echo "&amp;go=".$go."&amp;filter=".$filter; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $brewer_db_table; ?><?php if ($action == "edit") echo "&amp;id=".$row_brewer['id']; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<?php } 
$query_countries = "SELECT * FROM $countries_db_table ORDER BY id ASC";
$countries = mysql_query($query_countries, $brewing) or die(mysql_error());
$row_countries = mysql_fetch_assoc($countries);
?>
<p><span class="icon"><img src="<?php echo $base_url; ?>/images/help.png"  /></span><a id="modal_window_link" href="http://help.brewcompetition.com/files/my_info.html" title="BCOE&amp;M Help: My Info and Entries">My Info and Entries Help</a></p>
<div class="info">The information here beyond your first name, last name, and club is strictly for record-keeping and contact purposes. A condition of entry into the competition is providing this information. Your name and club may be displayed should one of your entries place, but no other information will be made public.</div>
<?php include(INCLUDES.'mods_top.inc.php'); ?>
<p><input name="submit" type="submit" class="button" value="Submit Brewer Information" /></p>
<table class="dataTable">
<tr>
      <td class="dataLabel" width="5%">First Name:</td>
      <td class="data" width="20%"><input type="text" id="brewerFirstName" name="brewerFirstName" value="<?php if ($action == "edit") echo $row_brewer['brewerFirstName']; ?>" size="32" maxlength="20"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
      <td rowspan="2" class="data">Please enter only <em>one</em> person's name.<br />
      You will be able to identify a co-brewer when adding your entries.</td>
</tr>
<tr>
      <td class="dataLabel">Last Name:</td>
      <td class="data"><input type="text" name="brewerLastName" value="<?php if ($action == "edit") echo $row_brewer['brewerLastName']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
    </tr>
<tr>
      <td class="dataLabel">Street Address:</td>
      <td class="data"><input type="text" name="brewerAddress" value="<?php if ($action == "edit") echo $row_brewer['brewerAddress']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">City:</td>
      <td class="data"><input type="text" name="brewerCity" value="<?php if ($action == "edit") echo $row_brewer['brewerCity']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">State or Province:</td>
      <td class="data"><input type="text" name="brewerState" value="<?php if ($action == "edit") echo $row_brewer['brewerState']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Zip or Postal Code:</td>
      <td class="data"><input type="text" name="brewerZip" value="<?php if ($action == "edit") echo $row_brewer['brewerZip']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
  	<td class="dataLabel">Country:</td>
  	<td class="data">
    <select name="brewerCountry">
    	<?php do { ?>
        <option value="<?php echo $row_countries['name']; ?>" <?php if (($action == "edit") && ($row_brewer['brewerCountry'] == $row_countries['name'])) echo "selected"; ?>><?php echo $row_countries['name']; ?></option>
        <?php } while ($row_countries = mysql_fetch_assoc($countries)); ?>
    </select>
    </td>
  	<td nowrap="nowrap" class="data"><span class="required">Required</span></td>
  	<td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Phone 1:</td>
      <td class="data"><input type="text" name="brewerPhone1" value="<?php if ($action == "edit") echo $row_brewer['brewerPhone1']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Phone 2:</td>
      <td class="data"><input type="text" name="brewerPhone2" value="<?php if ($action == "edit") echo $row_brewer['brewerPhone2']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data">&nbsp;</td>
      <td class="data">&nbsp;</td>
</tr>
<?php if (table_exists("nhcClubs")) { 

// Custom code for AHA - possiblity of inclusion in a future version
$query_clubs = "SELECT * FROM nhcClubs ORDER BY IDClub ASC";
$clubs = mysql_query($query_clubs, $brewing) or die(mysql_error());
$row_clubs = mysql_fetch_assoc($clubs);

?>
<tr>
      <td class="dataLabel">Club Name:</td>
      <td class="data" colspan="3">
      <select name="brewerClubs" id="brewerClubs">
      <?php do { ?>
      	<option value="<?php echo $row_clubs['ClubName']; ?>" <?php if ($row_brewer['brewerClubs'] == $row_clubs['ClubName']) echo "SELECTED"; ?>><?php echo $row_clubs['ClubName']; ?></option>
      <?php } while ($row_clubs = mysql_fetch_assoc($clubs)); ?>
      </select>
      </td>
</tr>
<?php } else { ?>
<tr>
      <td class="dataLabel">Club Name:</td>
      <td class="data"><input type="text" name="brewerClubs" value="<?php if ($action == "edit") echo $row_brewer['brewerClubs']; ?>" size="32" maxlength="200"></td>
      <td width="5%" nowrap="nowrap" class="data">&nbsp;</td>
      <td class="data">&nbsp;</td>
</tr>
<?php } ?>
<tr>
  <td class="dataLabel">AHA Member Number:</td>
  
  <td class="data">
  <?php if ($row_brewer['brewerAHA'] >= "999999994") { // For use with NHC ?>
  Pending<input type="hidden" name="brewerAHA" value="<?php echo $row_brewer['brewerAHA']; ?>" size="11" maxlength="9" />
  <?php } else { ?>
  <input type="text" name="brewerAHA" value="<?php if ($action == "edit") echo $row_brewer['brewerAHA']; ?>" size="11" maxlength="9" />
  <?php } ?>
  </td>
  <td colspan="2" class="data">To be considered for a GABF Pro-Am brewing opportunity you must be an AHA member.</td>
</tr>
<?php if (($go != "entrant") && ($section != "step2")) { ?>
<tr>
      <td class="dataLabel">Stewarding:</td>
      <td class="data">Are you willing be a steward in this competition?</td>
      <td width="5%" nowrap="nowrap" class="data"><input type="radio" name="brewerSteward" value="Y" id="brewerSteward_0"  <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "Y")) echo "CHECKED"; ?> /> Yes<br /><input type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "N")) echo "CHECKED"; ?>/> No</td>
      <td class="data">&nbsp;</td>
</tr>

<?php if ($totalRows_judging > 1) { ?>

<tr>
<td class="dataLabel">Stewarding<br />Location Availabilty:</td>
<td colspan="3" class="data">
<?php do { ?>
	<table class="dataTableCompact">
    	<tr>
        	<td width="1%" nowrap="nowrap">
                <select name="brewerStewardLocation[]" id="brewerStewardLocation">
					<option value="<?php echo "Y-".$row_stewarding['id']; ?>" <?php $a = explode(",", $row_brewer['brewerStewardLocation']); $b = "Y-".$row_stewarding['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>Yes</option>
                    <option value="<?php echo "N-".$row_stewarding['id']; ?>" <?php $a = explode(",", $row_brewer['brewerStewardLocation']); $b = "N-".$row_stewarding['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>No</option>
                </select>
            </td>
            <td class="data"><?php echo $row_stewarding['judgingLocName']." ("; echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_stewarding['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time").")"; ?></td>
        </tr>
    </table>
<?php }  while ($row_stewarding = mysql_fetch_assoc($stewarding));  ?>


</td>
</tr>
<?php } ?>
<tr>
      <td class="dataLabel">Judging:</td>
      <td class="data">Are you willing and qualified to judge in this competition?</td>
      <td width="5%" nowrap="nowrap" class="data"><input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0"  <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerJudge'] == "Y")) echo "CHECKED"; ?> /> Yes<br /><input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerJudge'] == "N")) echo "CHECKED"; ?>/> No</td>
      <td class="data">&nbsp;</td>
</tr>
<?php if ($totalRows_judging > 1) { ?>
<tr>
<td class="dataLabel">Judging<br />Location Availablity:</td>
<td class="data" colspan="3">
<?php do { ?>
	<table class="dataTableCompact">
    	<tr>
        	<td width="1%" nowrap="nowrap">
            <select name="brewerJudgeLocation[]" id="brewerJudgeLocation">
				<option value="<?php echo "Y-".$row_judging3['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "Y-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>Yes</option>
                <option value="<?php echo "N-".$row_judging3['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "N-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>No</option>
      		</select>
            </td>
            <td class="data"><?php echo $row_judging3['judgingLocName']." ("; echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging3['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time").")"; ?></td>
        </tr>
    </table>
<?php }  while ($row_judging3 = mysql_fetch_assoc($judging3)); ?>
</td>
</tr>
<?php } else { ?>
    <input name="brewerJudgeLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
    <input name="brewerStewardLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
<?php } } ?>
<?php if (($go == "judge") || ($go == "admin")) { ?>
<tr>
	<td colspan="4"><a name="judge"></a><div class="error">Please complete the following information and click "Submit Brewer Information."</div></td>
</tr>
<?php } ?>
<?php if ($action == "edit") include ('judge_info.sec.php'); ?>
</table>
<p><input name="submit" type="submit" class="button" value="Submit Brewer Information" /></p>
<?php if ($section != "step2") { ?>
	<input name="brewerEmail" type="hidden" value="<?php if ($filter != "default") echo $row_brewerID['brewerEmail']; else echo $row_user['user_name']; ?>" />
	<input name="uid" type="hidden" value="<?php if (($action == "edit") && ($row_brewerID['uid'] != "")) echo  $row_brewerID['uid']; elseif (($action == "edit") && ($row_user['userLevel'] <= "1") && (($_SESSION["loginUsername"]) != $row_brewerID['brewerEmail'])) echo $row_user_level['id']; else echo $row_user['id']; ?>" />
    <input name="brewerJudgeAssignedLocation" type="hidden" value="<?php echo $row_brewer['brewerJudgeAssignedLocation'];?>" />
    <input name="brewerStewardAssignedLocation" type="hidden" value="<?php echo $row_brewer['brewerStewardAssignedLocation'];?>" />
    <?php if ($go == "entrant") { ?>
	<input name="brewerJudge" type="hidden" value="N" />
	<input name="brewerSteward" type="hidden" value="N" /> 
	<?php } ?>
<?php } ?>
	<input type="hidden" name="relocate" value="<?php if ($go == "entrant") echo $base_url."/index.php?section=list"; else  echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php }
else echo "<div class=\"error\">You can only edit your own profile.</div>";
include(INCLUDES.'mods_bottom.inc.php');
?>