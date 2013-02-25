<?php 
/**
 * Module:      reg_open.sec.php
 * Description: This module houses information regarding registering for the competition,
 *              judging dates, etc. Shown while the registration window is open.
 *
 */

include(DB.'judging_locations.db.php'); ?>

<h2>Registration</h2>
	<p>Registration open: <?php echo $reg_open; ?>.</p>
    <p>Registration close: <?php echo $reg_closed ?>. Please note: registered users will <em>not</em> be able to add, view, edit or delete entries after this date/time.</p>
	<?php if (!isset($_SESSION['loginUsername'])) { ?>
    <p>If you have already registered, please <a href="<?php echo build_public_url("login","default","default",$sef,$base_url); ?>">log in</a> to add, view, edit, or delete your entries as well as indicate that you are willing to judge or  steward.</p>
    <?php } ?>
<h2>Judging and Stewarding</h2>
<?php if (($registration_open == "1") && (!isset($_SESSION['loginUsername']))) { ?>
	<p>If you <em>have not</em> registered and are willing to be a judge or steward, <a href="<?php echo build_public_url("register","judge","default",$sef,$base_url); ?>">please register</a>.</p>
	<p>If you <em>have</em> registered, <a href="<?php echo build_public_url("login","default","default",$sef,$base_url); ?>">log in</a> and then choose <em>Edit Your Info</em> to indicate that you are willing to judge or  steward.</p>
<?php } elseif (($registration_open == "1") && (isset($_SESSION['loginUsername']))) { ?>
	<p>Since you have already registered, you can <a href="<?php echo build_public_url("list","default","default",$sef,$base_url); ?>">check your info</a> to see whether you have indicated that you are willing to judge and/or steward.</p>
<?php } else { ?>
    <p>If you are willing to judge or steward, please return to register on or after <?php echo $judge_open; ?>.</p>
<?php } ?>
<h2>Entries</h2>
<p>Entries will be accepted between <?php echo $entry_open; ?> and <?php echo $entry_closed; ?>. All entries must be received by our shipping location <?php if ($totalRows_dropoff > 0) echo "or at a drop-off location"; ?> by <?php echo $entry_closed; ?> and will not be accepted after this date/time. For details, see the <a href="<?php echo build_public_url("entry","default","default",$sef,$base_url); ?>">Entry Information</a> page.</p> 
<?php if ($row_prefs['prefsEntryLimit'] != "") { ?>
<p>There is a limit of <?php echo readable_number($row_prefs['prefsEntryLimit'])." (".$row_prefs['prefsEntryLimit'].")"; ?> entries for this competition. Currently, <?php echo $totalRows_log; if ($totalRows_log == 1) echo " entry has"; else echo " entries have"; ?>  been logged.</p>
<?php } ?>

<?php if ($registration_open == "1") { ?>
	<h2>Enter Your Brews</h2>
	<p>To enter your brews,  <?php if (!isset($_SESSION['loginUsername'])) { ?>please proceed through the <a href="<?php echo build_public_url("register","default","default",$sef,$base_url); ?>">registration process</a><?php } else { ?>use the <a href="<?php echo build_public_url("brew","entry","add",$sef,$base_url); ?>">add an entry form</a><?php } ?>.</p>
<?php } ?>

<h2>Judging Date<?php if ($totalRows_judging > 1) echo "s"; ?></h2>
<?php if ($totalRows_judging == 0) echo "<p>The competition judging date is yet to be determined. Please check back later."; else { ?>
		<?php do { ?>
			<p>
			<?php echo "<strong>".$row_judging['judgingLocName']."</strong>"; ?>
    		<?php if ($row_judging['judgingLocation'] != "") echo "<br />".$row_judging['judgingLocation']; ?>
            <?php if (($row_judging['judgingLocation'] != "") && ($action != "print"))  { ?>
            <span class="icon"><a id="modal_window_link" href="<?php echo $base_url; ?>output/maps.php?section=map&amp;id=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>" title="Map to <?php echo $row_judging['judgingLocName']; ?>"><img src="<?php echo $base_url; ?>images/map.png"  border="0" alt="Map <?php echo $row_judging['judgingLocName']; ?>" title="Map <?php echo $row_judging['judgingLocName']; ?>" /></a></span>
            <span class="icon"><a href="<?php echo $base_url; ?>output/maps.php?section=driving&amp;id=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>" title="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>" target="_blank"><img src="<?php echo $base_url; ?>images/car.png"  border="0" alt="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>" title="Driving Direcitons to <?php echo $row_judging['judgingLocName']; ?>" /></a></span>
			<?php } ?>
            <?php if ($row_judging['judgingDate'] != "") echo "<br />".getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time")."<br />"; ?>
			</p>
			<?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
<?php } ?>