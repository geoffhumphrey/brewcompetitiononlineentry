<?php 
/**
 * Module:      reg_open.sec.php
 * Description: This module houses information regarding registering for the competition,
 *              judging dates, etc. Shown while the registration window is open.
 *
 */
include(DB.'judging_locations.db.php'); 
?>
<h2>Registration</h2>
<?php if (!greaterDate($today,$row_contest_info['contestRegistrationDeadline'])) { ?>
	<p>Registration opened <?php echo date_convert($row_contest_info['contestRegistrationOpen'], 2, $row_prefs['prefsDateFormat']); ?> and will close  <?php echo date_convert($row_contest_info['contestRegistrationDeadline'], 2, $row_prefs['prefsDateFormat']); ?>. Please note: registered users will <em>not</em> be able to add, view, edit or delete entries after <?php $date = $row_contest_info['contestRegistrationDeadline']; echo date_convert($date, 2, $row_prefs['prefsDateFormat']); ?>.</p>
	<?php if (!isset($_SESSION['loginUsername'])) { ?>
    <p>If you have already registered, please <a href="index.php?section=login">log in</a> to add, view, edit, or delete your entries as well as indicate that you are willing to judge or  steward.</p>
    <?php } ?>
<?php } else { ?>
	<p>Registration for the <?php echo $row_contest_info['contestName']; ?> will open <?php echo date_convert($row_contest_info['contestRegistrationOpen'], 2, $row_prefs['prefsDateFormat']); ?> and will close on <?php echo date_convert($row_contest_info['contestEntryDeadline'], 2, $row_prefs['prefsDateFormat']); ?>. Please note: registered users will <em>not</em> be able to add, view, edit or delete entries after the registration close date.</p>
<?php } ?>
<h2>Judging and Stewarding</h2>
<?php if ((!lesserDate($today,$row_contest_info['contestRegistrationOpen'])) && (!isset($_SESSION['loginUsername']))) { ?>
	<p>If you <em>have not</em> registered and are willing to be a judge or steward, <a href="index.php?section=register&amp;go=judge">please register</a>.</p>
	<p>If you <em>have</em> registered, <a href="index.php?section=login">log in</a> and then choose <em>Edit Your Info</em> to indicate that you are willing to judge or  steward.</p>
<?php } elseif ((!lesserDate($today,$row_contest_info['contestRegistrationOpen'])) && (isset($_SESSION['loginUsername']))) { ?>
	<p>Since you have already registered, you can <a href="index.php?section=list">check your info</a> to see whether you have indicated that you are willing to judge and/or steward.</p>
<?php } else { ?>
    <p>If you are willing to judge or steward, please return to register on or after <?php echo date_convert($row_contest_info['contestRegistrationOpen'], 2, $row_prefs['prefsDateFormat']); ?>.</p>
<?php } ?>

<h2>Entries</h2>
<p>Entries will be accepted <?php echo date_convert($row_contest_info['contestEntryOpen'], 2, $row_prefs['prefsDateFormat'])." through "; echo date_convert($row_contest_info['contestEntryDeadline'], 2, $row_prefs['prefsDateFormat']); ?>. All entries must be received by our shipping location <?php if ($totalRows_dropoff > 0) echo "or at a drop-off location"; ?> by <?php $date = $row_contest_info['contestEntryDeadline']; echo date_convert($date, 2, $row_prefs['prefsDateFormat']); ?>. Entries will not be accepted beyond this date. For details, see the <a href="index.php?section=entry">Entry Information</a> page.</p> 
<?php if (!lesserDate($today,$row_contest_info['contestRegistrationOpen'])) { ?>
	<h3>Enter Your Brews</h3>
	<p>To enter your brews,  <?php if (!isset($_SESSION['loginUsername'])) { ?>please proceed through the <a href="index.php?section=register">registration process</a><?php } else { ?>use the <a href="index.php?section=brew&action=add">add an entry form</a><?php } ?>.</p>
<?php } ?>

<h2>Competition Date<?php if ($totalRows_judging > 1) echo "s"; ?></h2>
<?php if ($totalRows_judging == 0) echo "<p>The competition judging date is yet to be determined. Please check back later."; else { ?>
		<?php do { ?>
			<p>
				<?php 
				if ($row_judging['judgingDate'] != "") echo date_convert($row_judging['judgingDate'], 2, $row_prefs['prefsDateFormat'])." at "; echo $row_judging['judgingLocName']; 
				if ($row_judging['judgingTime'] != "") echo ", ".$row_judging['judgingTime']; if (($row_judging['judgingLocation'] != "") && ($action != "print"))  { ?>&nbsp;&nbsp;<span class="icon"><a class="thickbox" href="http://maps.google.com/maps/api/staticmap?center=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>&amp;zoom=13&amp;size=600x400&amp;markers=color:red|<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>&amp;sensor=false&amp;KeepThis=true&amp;TB_iframe=true&amp;height=420&amp;width=600" title="Map to <?php echo $row_judging['judgingLocName']; ?>"><img src="images/map.png"  border="0" alt="Map <?php echo $row_judging['judgingLocName']; ?>" title="Map <?php echo $row_judging['judgingLocName']; ?>" /></a></span>
				<span class="icon"><a class="thickbox" href="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;q=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=900" title="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>"><img src="images/car.png"  border="0" alt="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>" title="Driving Direcitons to <?php echo $row_judging['judgingLocName']; ?>" /></a></span><?php } ?>
    			<?php if ($row_judging['judgingLocation'] != "") echo "<br />".$row_judging['judgingLocation']; ?>
			</p>
		<?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
		</ul>
<?php } ?>