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
<h2>Thanks and Good Luck To All Who Entered the <?php echo $row_contest_info['contestName']; ?>!</h2>
<p>There are <strong><?php echo $totalRows_log; ?></strong> entries (<strong><?php echo get_entry_count(); ?></strong> paid and received) from <strong><?php echo get_participant_count('default'); ?></strong> registered participants, judges, and stewards.</p>
<h2>Judging Date<?php if ($totalRows_judging > 1) echo "s"; ?></h2>
<?php 
if ($totalRows_judging == 0) echo "<p>The competition judging date is yet to be determined. Please check back later."; 
else { 
  	do { 
		echo "<p>"; 
		if ($row_judging['judgingDate'] != "") echo date_convert($row_judging['judgingDate'], 2, $row_prefs['prefsDateFormat'])." at "; echo $row_judging['judgingLocName']; 
		if ($row_judging['judgingTime'] != "") echo ", ".$row_judging['judgingTime']; if (($row_judging['judgingLocation'] != "") && ($action != "print"))  { ?>
        	&nbsp;&nbsp;
            <span class="icon"><a id="modal_window_link" href="output/maps.php?section=map&amp;id=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=420&amp;width=600" title="Map to <?php echo $row_judging['judgingLocName']; ?>"><img src="images/map.png"  border="0" alt="Map <?php echo $row_judging['judgingLocName']; ?>" title="Map <?php echo $row_judging['judgingLocName']; ?>" /></a></span>
            <span class="icon"><a href="output/maps.php?section=driving&amp;id=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>" title="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>" target="_blank"><img src="images/car.png"  border="0" alt="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>" title="Driving Direcitons to <?php echo $row_judging['judgingLocName']; ?>" /></a></span>
    		<?php if ($row_judging['judgingLocation'] != "") echo "<br />".$row_judging['judgingLocation']; 
			} 
        echo "<p>"; 
		 } while ($row_judging = mysql_fetch_assoc($judging)); 
	} 
?>
