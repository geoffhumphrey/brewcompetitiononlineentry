<h2><?php if ($action == "add") echo "Add a Judging Location"; elseif ($action == "edit") echo "Edit a Judging Location"; elseif ($action == "update") { echo "Make Final"; if ($filter == "judges") echo " Judge";  elseif ($filter == "stewards") echo " Steward"; else echo ""; echo " Location Assignments"; } elseif ($action == "assign") { echo "Assign Participants as"; if ($filter == "judges") echo " Judges";  elseif ($filter == "stewards") echo " Stewards"; else echo "";  } else echo "Judging Locations"; ?></h2>
<?php if (($action == "add") || ($section == "step3")) { ?>
<div class="info">If your competition judging will be held on the same day and location, but separated into two or more designated times (i.e., an AM session and a PM session), define each separately.</div>
<?php } if ($msg == "9"){ ?>
<div class="error">Add another judging location, date, or time?</div>
<p><a href="<?php if ($section == "step3") echo "setup.php?section=step3"; else echo "index.php?section=admin&amp;go=judging"; ?>">Yes</a>&nbsp;&nbsp;&nbsp;<a href="<?php if ($section == "step3") echo "setup.php?section=step4"; else echo "index.php?section=admin"; ?>">No</a>
<?php } 
else 
	{ 
	if ($section == "admin") include ('judging_links.admin.php');
 	if (($action == "default") && ($section != "step3")) include ('judging_default.admin.php'); 
	if ((($action == "add") || ($action == "edit")) || ($section == "step3")) include ('judging_add.admin.php'); 
	} // end else 
// Assign Judges by location
if ((($action == "update") && ($filter != "default") && ($bid != "default")) || ($action == "assign")) include ('judging_assign.admin.php');
// Choose judging locations
if (($action == "update") && ($bid == "default")) include ('judging_choose.admin.php'); 
?>