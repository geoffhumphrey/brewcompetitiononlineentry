<?php 
/**
 * Module:      reg_closed.sec.php
 * Description: This module houses information that will be displayed 
 *              after registration has closed but before judging ends.
 *
 */
 
include(DB.'judging_locations.db.php'); 
include(DB.'entries.db.php');
?>
<h2>Thanks and Good Luck To All Who Entered the <?php echo $_SESSION['contestName']; ?>!</h2>
<p>There are <strong><?php echo get_participant_count('default'); ?></strong> registered participants, judges, and stewards.</p>
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
            <?php if ($row_judging['judgingDate'] != "") echo "<br />".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time")."<br />"; ?>
			</p>
			<?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
<?php } ?>
