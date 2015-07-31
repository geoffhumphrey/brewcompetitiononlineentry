<?php 
/**
 * Module:      brewer_info.sec.php 
 * Description: This module displays user-related data including personal information,
 *              judging/stewarding information
 * 
 */
 
include (DB.'judging_locations.db.php');

// Build useful variables
if (($_SESSION['brewerDiscount'] == "Y") && ($_SESSION['contestEntryFeePasswordNum'] != "")) $entry_discount = TRUE; else $entry_discount = FALSE;
$brewer_assignment = brewer_assignment($_SESSION['user_id'],"1");
$assignment_array = str_replace(", ",",",$brewer_assignment);
$assignment_array = explode(",", $assignment_array);
if ((!in_array("Judge",$assignment_array)) && ($_SESSION['brewerJudge'] == "Y") && ($totalRows_judging3 > 1)) $judge_available_not_assigned = TRUE; else $judge_available_not_assigned = FALSE;
if ((!in_array("Steward",$assignment_array)) && ($_SESSION['brewerSteward'] == "Y") && ($totalRows_judging3 > 1)) $steward_available_not_assigned = TRUE; else $steward_available_not_assigned = FALSE;
if ((in_array("Judge",$assignment_array)) && ($_SESSION['brewerJudge'] == "Y") && ($totalRows_judging3 > 1)) $assignment = "judge";
elseif ((in_array("Steward",$assignment_array)) && ($_SESSION['brewerSteward'] == "Y") && ($totalRows_judging3 > 1)) $assignment = "steward"; 
else $assignment = "";


//echo $brewer_assignment;

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
$discount = "Yes (".$currency_symbol.$_SESSION['contestEntryFeePasswordNum']." per entry)";
if (!empty($_SESSION['brewerAHA'])) {
	if ($_SESSION['brewerAHA'] < "999999994") $aha_number = sprintf("%09s",$_SESSION['brewerAHA']); 
	elseif ($_SESSION['brewerAHA'] >= "999999994") $aha_number = "Pending"; 
} else $aha_number = "None entered";

// Build Judge Info Display

	$judge_info = "";
	$a = explode(",",$_SESSION['brewerJudgeLocation']);
	arsort($a);
	foreach ($a as $value) {
		if ($value != "0-0") {
			$b = substr($value, 2);
			
				$judging_location_info = judging_location_info($b);
				$judging_location_info = explode("^",$judging_location_info);
			
				if ($judging_location_info[0] > 0) {
					$judge_info .= "<tr>\n";
					if ($action == "print") $judge_info .= "<td class='dataList bdr1B'>"; else $judge_info .= "<td class='dataList'>";
					if ($action == "print") $judge_info .= yes_no(substr($value, 0, 1),$base_url,3); else $judge_info .= yes_no(substr($value, 0, 1),$base_url);
					$judge_info .= "</td>\n";
					if ($action == "print") $judge_info .= "<td class='dataList bdr1B'>"; else $judge_info .= "<td class='dataList'>";
					$judge_info .= $judging_location_info[1];
					$judge_info .= "</td>\n";
					if ($action == "print") $judge_info .= "<td class='dataList bdr1B'>"; else $judge_info .= "<td class='dataList'>";
					$judge_info .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_location_info[2], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
					$judge_info .= "</td>\n";
					$judge_info .= "</tr>";
				}
			}
		else $judge_info .= "";
	}

// Build Steward Info Display
$steward_info = "";
	$a = explode(",",$_SESSION['brewerStewardLocation']);
		arsort($a);
		foreach ($a as $value) {
			if ($value != "0-0") {
				$b = substr($value, 2);
				
				$judging_location_info = judging_location_info($b);
				$judging_location_info = explode("^",$judging_location_info);
				
				
					if ($judging_location_info[0] > 0) {
						$steward_info .= "<tr>\n";
						if ($action == "print") $steward_info .= "<td class='dataList bdr1B'>"; else $steward_info .= "<td class='dataList'>";
						if ($action == "print") $steward_info .= yes_no(substr($value, 0, 1),$base_url,3); else $steward_info .= yes_no(substr($value, 0, 1),$base_url);
						$steward_info .= "</td>\n";
						if ($action == "print") $steward_info .= "<td class='dataList bdr1B'>"; else $steward_info .= "<td class='dataList'>";
						$steward_info .= $judging_location_info[1];
						$steward_info .= "</td>\n";
						if ($action == "print") $steward_info .= "<td class='dataList bdr1B'>"; else $steward_info .= "<td class='dataList'>";
						$steward_info .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_location_info[2], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
						$steward_info .= "</tr>";
					}
				}
			else $steward_info .= "";
			}

if ($action == "print") $table_assign_judge = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],1);
else $table_assign_judge = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);

if ($action == "print") $table_assign_steward = table_assignments($_SESSION['user_id'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],1);
else $table_assign_steward = table_assignments($_SESSION['user_id'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);


// ------------------------ Display -------------------------------
// Display NHC Final Round Instructions (NHC ONLY)
if ((NHC) && ($prefix == "final_") && ($action != "print") && ($totalRows_log > 0)) include (MODS.'nhc_final_round_instructions.php');
// Display Thank You
echo $thank_you;
echo "<h2>Info</h2>";
// Display User Edit Links
echo $user_edit_links; 
/*
echo $brewer_assignment."<br>";
echo $judge_available_not_assigned."<br>";
echo $steward_available_not_assigned."<br>";
echo $_SESSION['brewerJudge']."<br>";
echo $_SESSION['brewerSteward']."<br>";
*/
?>
<table class="dataTable">
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
    <td class="dataLabel">AHA Number:</td>
    <td class="data"><?php echo $aha_number; ?>&nbsp;&nbsp;<em>*An <a href="http://www.homebrewersassociation.org/" target="_blank">American Homebrewers Association</a> (AHA) membership is required if one of your entries is selected for a <a href="http://www.homebrewersassociation.org/pages/competitions/great-american-beer-festival-pro-am" target="_blank">Great American Beer Festival Pro-Am</a>.</em></td>
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
<?php } // end if ($entry_discount)?>
<?php if (!empty($assignment)) { ?>
<tr>
    <td class="dataLabel">Assigned As:</td>
    <td class="data"><?php echo $brewer_assignment; ?></td>
</tr>
<?php } // end if (!empty($assignment))?>
<?php if ((in_array("Judge",$assignment_array)) && ($action != "print")) { ?>
<tr>
    <td class="dataLabel">&nbsp;</td>
    <td class="data"><span class="icon"><img src="<?php echo $base_url; ?>images/page_white_acrobat.png"  border="0" alt="Print your judging scoresheet labels" title="Judging scoresheet labels"></span><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;id=<?php echo $_SESSION['brewerID']; ?>">Print Judging Scoresheet Labels</a></span><span class="data">(Avery 5160 PDF Download)</span></td>
</tr>
<?php } // end if (in_array("Judge",$assignment_array)) && ($action != "print")) ?>
<?php if ($_SESSION['brewerJudge'] == "Y") { 
$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
$display_rank = bjcp_rank($bjcp_rank[0],2);
if (!empty($bjcp_rank[1])) {
	$display_rank .= designations($row_brewer['brewerJudgeRank'],$bjcp_rank[0]);
		}
?>
<tr>
    <td class="dataLabel">BJCP Judge ID:</td>
    <td class="data"><?php  if ($_SESSION['brewerJudgeID'] > "0") echo $_SESSION['brewerJudgeID']; else echo "N/A"; ?></td>
</tr>
<tr>
    <td class="dataLabel">Beer Judge Ranks and Designations:</td>
 <td class="data"><?php  if ($_SESSION['brewerJudgeRank'] != "") echo str_replace("<br />",", ",$display_rank); else echo "N/A"; ?></td>
</tr>
<tr>
  <td width="10%" class="dataLabel">Mead Judge Endorsement:</td>
  <td colspan="2" class="data"><?php if ($action == "print") echo yes_no($_SESSION['brewerJudgeMead'],$base_url,3); else echo yes_no($_SESSION['brewerJudgeMead'],$base_url) ?></td>
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
    if ($_SESSION['brewerJudgeDislikes'] != "") echo rtrim(display_array_content(style_convert($_SESSION['brewerJudgeDislikes'],4,$base_url),2),", "); 
    else echo "N/A";		
    ?>
    </td>
</tr>
<?php } // end if ($_SESSION['brewerJudge'] == "Y") ?>
<tr>
    <td class="dataLabel">Available to Judge?</td>
    <td class="data">
    <?php if ((!empty($_SESSION['brewerJudge'])) && ($action != "print")) echo yes_no($_SESSION['brewerJudge'],$base_url); elseif ((!empty($_SESSION['brewerJudge'])) && ($action == "print")) echo yes_no($_SESSION['brewerJudge'],$base_url,3); else echo "None entered"; ?>
    </td>
</tr>
<?php if ($_SESSION['brewerJudge'] == "Y") { ?>
<tr>
    <td class="dataLabel">Judging Availability:</td>
    <td>
    <?php if (($assignment == "judge") || (empty($assignment))) { ?>
	<?php if (empty($table_assign_judge)) { ?>
    <script type="text/javascript" language="javascript">
         $(document).ready(function() {
            $('#sortable_judge').dataTable( {
                "bPaginate" : false,
                "sDom": 'rt',
                "bStateSave" : false,
                "bLengthChange" : false,
                "aaSorting": [[1,'asc']],
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
        <th class="dataHeading bdr1B" width="15%">Yes/No</th>
        <th class="dataHeading bdr1B" width="40%">Location</th>
        <th class="dataHeading bdr1B">Date/Time</th>
    </tr>
    </thead>
    <tbody>
    <?php echo $judge_info;	?>
    </tbody>
    </table>
    <?php }  
	 	}  
		if ((!empty($table_assign_judge)) && (!empty($assignment)))  echo "** You have already been assigned as a $assignment to a table. If you wish to change your availabilty and/or withdraw your role, <a href=\"".build_public_url("contact","default","default",$sef,$base_url)."\">contact</a> the competition organizer or judge coordinator."; 
		?>
    </td>
</tr>
<?php if ((!$judge_available_not_assigned) && (!empty($table_assign_judge))) { ?>
<tr>
	<td class="dataLabel">Table Assignment(s):</td>
	<td>
	<script type="text/javascript" language="javascript">
		 $(document).ready(function() {
			$('#sortable_assignments').dataTable( {
				"bPaginate" : false,
				"sDom": 'rt',
				"bStateSave" : false,
				"bLengthChange" : false,
				"aaSorting": [[0,'asc']],
				"aoColumns": [
					null,
					null,
					null
					]
				} );
			} );
	</script> 
	<table id="sortable_assignments" class="dataTable" style="width:75%;float:left;">
	<thead>
	<tr>
		<th class="dataHeading bdr1B" width="33%">Location</th>
		<th class="dataHeading bdr1B" width="34%">Date/Time</th>
		<th class="dataHeading bdr1B">Table</th>
	</tr>
	</thead>
	<tbody>
	<?php echo $table_assign_judge; ?>
	</tbody>
	</table>
	</td>
</tr>
<?php } // end ((!$judge_available_not_assigned) && (!empty($table_assign_judge))) 
}
?>
<tr>
    <td class="dataLabel">Available to Steward?</td>
    <td class="data"><?php if ((!empty($_SESSION['brewerSteward'])) && ($action != "print")) echo yes_no($_SESSION['brewerSteward'],$base_url); elseif ((!empty($_SESSION['brewerSteward'])) && ($action == "print")) echo yes_no($_SESSION['brewerSteward'],$base_url,3); else echo "None entered"; ?></td>
</tr>
<?php if ($_SESSION['brewerSteward'] == "Y") { ?>
<tr>
		
    	<td class="dataLabel">Stewarding Availability:</td>
    	
        <td>
        <?php if (($assignment == "steward") || (empty($assignment)))  { ?>
        <?php if (empty($table_assign_steward)) { ?>
    	<script type="text/javascript" language="javascript">
			 $(document).ready(function() {
				$('#sortable_steward').dataTable( {
					"bPaginate" : false,
					"sDom": 'rt',
					"bStateSave" : false,
					"bLengthChange" : false,
					"aaSorting": [[1,'asc']],
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
        	<th class="dataHeading bdr1B" width="15%">Yes/No</th>
            <th class="dataHeading bdr1B" width="40%">Location</th>
            <th class="dataHeading bdr1B">Date/Time</th>
        </tr>
        </thead>
        <tbody>
		<?php echo $steward_info; ?>
        </tbody>
    	</table>
        <?php }  
	 	}  
		if ((!empty($table_assign_steward)) && (!empty($assignment))) echo "** You have already been assigned as a $assignment to a table. If you wish to change your availabilty and/or withdraw your role, <a href=\"".build_public_url("contact","default","default",$sef,$base_url)."\">contact</a> the competition organizer or judge coordinator."; 
		?>
        </td>
  	</tr>
<?php if ((!$steward_available_not_assigned) && (!empty($table_assign_steward))) { ?>
<tr>
	<td class="dataLabel">Table Assignment(s):</td>
	<td>
	<script type="text/javascript" language="javascript">
		 $(document).ready(function() {
			$('#sortable_assignments').dataTable( {
				"bPaginate" : false,
				"sDom": 'rt',
				"bStateSave" : false,
				"bLengthChange" : false,
				"aaSorting": [[0,'asc']],
				"aoColumns": [
					null,
					null,
					null
					]
				} );
			} );
	</script> 
	<table id="sortable_assignments" class="dataTable" style="width:50%;float:left;">
	<thead>
	<tr>
		<th class="dataHeading bdr1B" width="35%">Location</th>
		<th class="dataHeading bdr1B" width="35%">Date/Time</th>
		<th class="dataHeading bdr1B">Table</th>
	</tr>
	</thead>
	<tbody>
	<?php echo $table_assign_steward; ?>
	</tbody>
	</table>
	</td>
</tr>
<?php } // end if ((!$steward_available_not_assigned) && (!empty($table_assign_steward))) 
}
?>
</table>