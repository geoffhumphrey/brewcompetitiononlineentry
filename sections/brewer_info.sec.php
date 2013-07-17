<?php 
/**
 * Module:      brewer_info.sec.php 
 * Description: This module displays user-related data including personal information,
 *              judging/stewarding information
 * 
 */

// Build useful variables
if (($_SESSION['brewerDiscount'] == "Y") && ($_SESSION['contestEntryFeePasswordNum'] != "")) $entry_discount = TRUE; else $entry_discount = FALSE;

if (($_SESSION['brewerJudge'] == "Y") && ($totalRows_judging3 > 1)) $assigned_as_judge = TRUE; else $assigned_as_judge = FALSE;

// Build Thank You Message
$thank_you = "<p>Thank you for entering the ".$_SESSION['contestName'].", ".$_SESSION['brewerFirstName']."."; 
if (($totalRows_log > 0) && ($action != "print")) $thank_you .= " <a href='#list'>View your entries</a>.</p>";

$user_edit_links = "";

if ($action != "print") { 

	// Build Edit My Info link
	$edit_user_info_link = "<a href='".$base_url."index.php?";
	if ($_SESSION['brewerID'] != "") $edit_user_info_link .= "section=brewer&amp;action=edit&amp;id=".$_SESSION['brewerID']; 
	else $edit_user_info_link .= "action=add&amp;section=brewer&amp;go=judge";
	$edit_user_info_link .= "'>Edit My Info</a>";
	
	// Build Change My Email Address link
	$edit_email_link = "<a href='".$base_url."index.php?section=user&amp;action=username&amp;id=".$_SESSION['brewerID']."'>Change My Email Address</a>";
	
	// Build Change My Email Address link
	$edit_password_link = "<a href='".$base_url."index.php?section=user&amp;action=password&amp;id=".$_SESSION['brewerID']."'>Change My Password</a>";
	
	
	$user_edit_links .= "<div class='adminSubNavContainer'>";
	
	$user_edit_links .= "<span class='adminSubNav'>";
	$user_edit_links .= "<span class='icon'><img src='".$base_url."images/user_edit.png' /></span>";
	$user_edit_links .= $edit_user_info_link;
	$user_edit_links .= "</span>";
	
	if (!NHC) {
	$user_edit_links .= "<span class='adminSubNav'>";
	$user_edit_links .= "<span class='icon'><img src='".$base_url."images/email_edit.png' /></span>";
	$user_edit_links .= $edit_email_link;
	$user_edit_links .= "</span>";
	}
	
	$user_edit_links .= "<span class='adminSubNav'>";
	$user_edit_links .= "<span class='icon'><img src='".$base_url."images/key.png' /></span>";
	$user_edit_links .= $edit_password_link;
	$user_edit_links .= "</span>";
	
	$user_edit_links .= "</div>";

} // end if ($action != "print")

// Build User Info
$name = $_SESSION['brewerFirstName']." ".$_SESSION['brewerLastName'];
$email = $_SESSION['brewerEmail'];
if (!empty($_SESSION['brewerAddress'])) $address = $_SESSION['brewerAddress']; else $address = "None entered";
if (!empty($_SESSION['brewerCity'])) $city = $_SESSION['brewerCity']; else $city = "None entered";
if (!empty($_SESSION['brewerState'])) $state = $_SESSION['brewerState']; else $state = "None entered";
if (!empty($_SESSION['brewerZip'])) $zip = $_SESSION['brewerZip']; else $zip = "None entered";
if (!empty($_SESSION['brewerCountry'])) $country = $_SESSION['brewerCountry']; else $country = "None entered";
if ($_SESSION['brewerCountry'] == "United States") $us_phone = TRUE; else $us_phone = FALSE;
$phone = "";
if (!empty($_SESSION['brewerPhone1'])) {
	if ($us_phone) $phone .= format_phone_us($_SESSION['brewerPhone1'])." (1)"; 
	else $phone .= $_SESSION['brewerPhone1']." (1)"; 
}
if (!empty($_SESSION['brewerPhone2'])) {
	if ($us_phone) $phone .= "<br>".format_phone_us($_SESSION['brewerPhone2'])." (2)";
	else $phone .= "<br>".$_SESSION['brewerPhone2']." (2)";
}
if (!empty($_SESSION['brewerClubs'])) $club = $_SESSION['brewerClubs']; else $club = "None entered";
$discount = "Yes (".$_SESSION['prefsCurrency'].$_SESSION['contestEntryFeePasswordNum']." per entry)";
if (!empty($_SESSION['brewerAHA'])) {
	if ($_SESSION['brewerAHA'] < "999999994") $aha_number = $_SESSION['brewerAHA']; 
	elseif ($_SESSION['brewerAHA'] >= "999999994") $aha_number = "Pending"; 
} else $aha_number = "None entered";



// Build Judge Info Display
if ($assigned_as_judge) { 
	$a = explode(",",$_SESSION['brewerJudgeLocation']);
	arsort($a);
	foreach ($a as $value) {
		if ($value != "0-0") {
			$b = substr($value, 2);
			$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation,judgingTime FROM $judging_locations_db_table WHERE id='%s'", $b);
			$judging_loc3 = mysql_query($query_judging_loc3, $brewing) or die(mysql_error());
			$row_judging_loc3 = mysql_fetch_assoc($judging_loc3);
			echo "<tr>\n";
			if ($action == "print") echo "<td class='dataList bdr1B'>"; else echo "<td class='dataList'>";
			echo substr($value, 0, 1);
			echo "</td>\n";
			if ($action == "print") echo "<td class='dataList bdr1B'>"; else echo "<td class='dataList'>";
			echo $row_judging_loc3['judgingLocName'];
			echo "</td>\n";
			if ($action == "print") echo "<td class='dataList bdr1B'>"; else echo "<td class='dataList'>";
			echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_loc3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
			echo "</td>\n";
			echo "</tr>";
			}
		else echo "";
	}
}

// ------------------------ Display -------------------------------

// Display NHC Final Round Instructions (NHC ONLY)
if ((NHC) && ($prefix == "final_") && ($action != "print") && ($totalRows_log > 0)) include (MODS.'nhc_final_round_instructions.php');

// Display Thank You
echo $thank_you;

echo "<h2>Info</h2>";

// Display User Edit Links
echo $user_edit_links;

?>
<table class="dataTableCompact">
  	<tr>
    	<td class="dataLabel" width="5%">Name:</td>
    	<td class="data"><?php echo $name; ?></td>
  	</tr>
  	<tr>
   	 	<td class="dataLabel">Email:</td>
   	 	<td class="data"><?php echo $email; ?></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Address:</td>
    	<td class="data"><?php echo $address; ?></td>
  	</tr>
  	<tr>
  	  <td class="dataLabel">City:</td>
  	  <td class="data"><?php echo $city; ?></td>
  </tr>
  	<tr>
  	  <td class="dataLabel">State or Province:</td>
  	  <td class="data"><?php echo $state; ?></td>
  </tr>
  	<tr>
  	  <td class="dataLabel">Zip or Postal Code:</td>
  	  <td class="data"><?php echo $zip; ?></td>
  </tr>
  	<tr>
  	  <td class="dataLabel">Country:</td>
  	  <td class="data"><?php echo $country; ?></td>
  </tr>
  	<tr>
    	<td class="dataLabel">Phone Number(s):</td>
    	<td class="data"><?php echo $phone; ?></td>
  	</tr>
  	<tr>
  	  <td class="dataLabel">Drop Off Location:</td>
  	  <td class="data"><?php echo dropoff_location($_SESSION['brewerDropOff']); ?></td>
  </tr>
  	<tr>
    	<td class="dataLabel">Club:</td>
    	<td class="data"><?php echo $club; ?></td>
  	</tr>
    <?php if ($entry_discount) { ?>
    <tr>
    	<td class="dataLabel">Entry Fee Discount:</td>
    	<td class="data"><?php echo $discount; ?></td>
  	</tr>
    <?php } ?>
  	<tr>
    	<td class="dataLabel">AHA Member Number:</td>
    	<td class="data"><?php echo $aha_number; ?></td>
  	</tr>
  	<?php if ($_SESSION['brewerAssignment'] == "") { ?>
  	<tr>
    	<td class="dataLabel">Judge?</td>
    	<td class="data">
		<?php if ($_SESSION['brewerJudge'] != "") echo  $_SESSION['brewerJudge']; else echo "None entered"; ?>
    	</td>
  	</tr>
  	<?php if ($assigned_as_judge) { ?>
  	<tr>
    	<td class="dataLabel">Judging Availability:</td>
    	<td>
        <script type="text/javascript" language="javascript">
			 $(document).ready(function() {
				$('#sortable_judge').dataTable( {
					"bPaginate" : false,
					"sDom": 'rt',
					"bStateSave" : false,
					"bLengthChange" : false,
					"aaSorting": [[2,'asc']],
					"aoColumns": [
						null,
						null,
						null
						]
					} );
				} );
		</script> 
    	<table id="sortable_judge" class="dataTable" style="width:50%;float:left;">
        <thead>
        <tr>
        	<th class="dataHeading bdr1B" width="10%">Yes/No</th>
            <th class="dataHeading bdr1B" width="35%">Location</th>
            <th class="dataHeading bdr1B">Date/Time</th>
        </tr>
        </thead>
        <tbody>
		<?php echo $judge_info;	?>
        </tbody>
    	</table>
    	</td>
  	</tr>
  	<?php } ?>
  	<?php } else { 
	$brewer_assignment = brewer_assignment($_SESSION['user_id'],"1");
	if ($brewer_assignment != "") {
	?>
  	<tr>
    	<td class="dataLabel">Assigned As:</td>
		<td class="data"><?php echo $brewer_assignment; ?></td>
  	</tr>
    <?php if (strstr($brewer_assignment,"Judge") && ($action != "print")) { ?>
    <tr>
    	<td class="dataLabel">&nbsp;</td>
		<td class="data"><span class="icon"><img src="<?php echo $base_url; ?>images/page_white_acrobat.png"  border="0" alt="Print your judging scoresheet labels" title="Judging scoresheet labels"></span><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;id=<?php echo $_SESSION['brewerID']; ?>">Print Judging Scoresheet Labels</a></span><span class="data">(Avery 5160 PDF Download)</span></td>
  	</tr>
  	<?php } ?>
	<?php 
	$table_assignments = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat']);
	if (($totalRows_judging3 > 1) && ($table_assignments != "")) { ?>
  	<tr>
    	<td class="dataLabel">Location<?php if ($totalRows_judging > 1) echo "(s)"; ?>:</td>
    	<td>
    	<?php echo $table_assignments; ?>
    	</td>
  	</tr>
  	<?php } ?>
  	<?php } // end if (strstr($brewer_assignment,"Judge"))?>
    <?php } ?>
  	<?php if ($_SESSION['brewerJudge'] == "Y") { ?>
  	<tr>
    	<td class="dataLabel">BJCP Judge ID:</td>
    	<td class="data"><?php  if ($_SESSION['brewerJudgeID'] != "0") echo $_SESSION['brewerJudgeID']; else echo "N/A"; ?></td>
	</tr>
    <tr>
      <td width="10%" class="dataLabel">Mead Judge Endorsement:</td>
      <td colspan="2" class="data"><?php echo $_SESSION['brewerJudgeMead']; ?></td>
    </tr>
  	<tr>
    	<td class="dataLabel">BJCP Judge Rank:</td>
   	 <td class="data"><?php  if ($_SESSION['brewerJudgeRank'] != "") echo  $_SESSION['brewerJudgeRank']; else echo "N/A"; ?></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Categories Preferred:</td>
    	<td class="data">
		<?php 
		if ($_SESSION['brewerJudgeLikes'] != "") echo rtrim(display_array_content(style_convert($_SESSION['brewerJudgeLikes'],4),2),", ");
	    else echo "N/A";		
		?>
        </td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Categories Not Preferred:</td>
    	<td class="data">
        <?php 
		if ($_SESSION['brewerJudgeDislikes'] != "") echo rtrim(display_array_content(style_convert($_SESSION['brewerJudgeDislikes'],4),2),", "); 
	    else echo "N/A";		
		?>
        </td>
  	</tr>
  	<?php } ?>
  	<?php if ($_SESSION['brewerAssignment'] == "") { ?>
  	<tr>
    	<td class="dataLabel">Steward?</td>
    	<td class="data"><?php if ($_SESSION['brewerSteward'] != "") echo $_SESSION['brewerSteward']; else echo "None entered"; ?></td>
  	</tr>
  	<?php  if (($_SESSION['brewerSteward'] == "Y") && ($totalRows_judging3 > 1)) { ?>
  	<tr>
    	<td class="dataLabel">Stewarding Availability:</td>
    	<td>
    	<script type="text/javascript" language="javascript">
			 $(document).ready(function() {
				$('#sortable_steward').dataTable( {
					"bPaginate" : false,
					"sDom": 'rt',
					"bStateSave" : false,
					"bLengthChange" : false,
					"aaSorting": [[2,'asc']],
					"aoColumns": [
						null,
						null,
						null
						]
					} );
				} );
		</script>
        <table id="sortable_steward" class="dataTable"  style="width:50%;float:left;">
        <thead>
        <tr>
        	<th class="dataHeading bdr1B" width="10%">Yes/No</th>
            <th class="dataHeading bdr1B" width="35%">Location</th>
            <th class="dataHeading bdr1B">Date/Time</th>
        </tr>
        </thead>
        <tbody>
		<?php 
		$a = explode(",",$_SESSION['brewerStewardLocation']);
		arsort($a);
		foreach ($a as $value) {
			if ($value != "0-0") {
				$b = substr($value, 2);
				$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation,judgingTime FROM $judging_locations_db_table WHERE id='%s'", $b);
				$judging_loc3 = mysql_query($query_judging_loc3, $brewing) or die(mysql_error());
				$row_judging_loc3 = mysql_fetch_assoc($judging_loc3);
				echo "<tr>\n";
				if ($action == "print") echo "<td class='dataList bdr1B'>"; else echo "<td class='dataList'>";
				echo substr($value, 0, 1);
				echo "</td>\n";
				if ($action == "print") echo "<td class='dataList bdr1B'>"; else echo "<td class='dataList'>";
				echo $row_judging_loc3['judgingLocName'];
				echo "</td>\n";
				if ($action == "print") echo "<td class='dataList bdr1B'>"; else echo "<td class='dataList'>";
				echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_loc3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
				echo "</td>\n";
				echo "</tr>";
				}
			else echo "";
			}
		?>
        </tbody>
    	</table>
    	</td>
  	</tr>
  	<?php } ?>
  	<?php } ?>
</table>