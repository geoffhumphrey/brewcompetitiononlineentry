<?php if ($judgingDateReturn == "false") { ?>
<h2>Thanks and Good Luck To All Who Entered the <?php echo $row_contest_info['contestName']; ?>!</h2>
<p>There are <?php echo $totalRows_entries; ?> entries and <?php echo $totalRows_brewers; ?> registered brewers, judges, and stewards.</p>
<h2>Judging Date<?php if ($totalRows_judging > 1) echo "s"; ?></h2>
<?php if ($totalRows_judging == 0) echo "<p>The competition judging date is yet to be determined. Please check back later."; else { 
  		do { ?>
  		<p>
	  	<?php 
			if ($row_judging['judgingDate'] != "") echo dateconvert($row_judging['judgingDate'], 2)." at "; echo $row_judging['judgingLocName']; 
			if ($row_judging['judgingTime'] != "") echo ", ".$row_judging['judgingTime']; if (($row_judging['judgingLocation'] != "") && ($action != "print"))  { ?>&nbsp;&nbsp;<span class="icon"><a class="thickbox" href="http://maps.google.com/maps/api/staticmap?center=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>&amp;zoom=13&amp;size=600x400&amp;markers=color:red|<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>&amp;sensor=false&amp;KeepThis=true&amp;TB_iframe=true&amp;height=420&amp;width=600" title="Map to <?php echo $row_judging['judgingLocName']; ?>"><img src="images/map.png"  border="0" alt="Map <?php echo $row_judging['judgingLocName']; ?>" title="Map <?php echo $row_judging['judgingLocName']; ?>" /></a></span>
			<span class="icon"><a class="thickbox" href="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;q=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=900" title="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>"><img src="images/car.png"  border="0" alt="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>" title="Driving Direcitons to <?php echo $row_judging['judgingLocName']; ?>" /></a></span>
    		<?php if ($row_judging['judgingLocation'] != "") echo "<br />".$row_judging['judgingLocation']; ?>
  			<?php } ?>
		</p>
		<?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
		<?php } ?>
<?php } ?>