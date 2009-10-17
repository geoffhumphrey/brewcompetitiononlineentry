<div id="header">	
	<div id="header-inner"><h1><?php echo $row_contest_info['contestName']; ?></h1></div>
</div>
<?php if (greaterDate($today,$deadline)) 
	{ 
	if (greaterDate($today,$row_contest_info['contestDate'])) 
				{ ?>
    		<p>Judging has already taken place for the <?php echo $row_contest_info['contestName']; ?>. Thank you for your interest.</p>
        <?php 	} else 
				include ('sections/closed.sec.php');
	} 
	else 
	{ 
?>
<p>Thank you for your interest in the <?php echo $row_contest_info['contestName']; ?> organized by <?php if ($row_contest_info['contestHostWebsite'] != "") { ?><a href="<?php echo $row_contest_info['contestHostWebsite']; ?>" target="_blank"><?php } echo $row_contest_info['contestHost']; if ($row_contest_info['contestHostWebsite'] != "") { ?></a><?php } if ($row_contest_info['contestHostLocation'] != "") echo ", ".$row_contest_info['contestHostLocation']; ?>.  Full contest rules are available by accessing <a href="index.php?section=rules">this page</a> or by using the navigation above.</p>
<?php if (!isset($_SESSION['loginUsername'])){ ?>
<h2>Register</h2>
<p>To enter your brews, please proceed through the <a href="index.php?section=register">registration process</a>. You only need to register your informaiton once and can return to this site to enter more brews or edit the brews you've entered. <?php if ($rowPref['paypal'] == "Y") { ?>You can even pay your entry fees online if you wish.<?php } ?></p>
<p>If you have already registered, please <a href="index.php?section=login">log in</a> to add, view, edit, or delete your entires.</p>
<?php } ?>
<h2>Contest Date</h2>
<p>Judging for the <?php echo $row_contest_info['contestName']; ?> will take place 
<?php $date = $row_contest_info['contestDate']; echo dateconvert($date, 2); 
if ($row_contest_info['contestDate2'] != "") { 
	if ($row_contest_info['contestDate3'] != "") { echo ", "; echo dateconvert($row_contest_info['contestDate2'], 2); }
	else { echo " and "; echo dateconvert($row_contest_info['contestDate2'], 2); }
	} 
if ($row_contest_info['contestDate3'] != "") { 
	if ($row_contest_info['contestDate2'] != "") { echo ", and "; echo dateconvert($row_contest_info['contestDate3'], 2); }
    else { echo " and "; echo dateconvert($row_contest_info['contestDate3'], 2); }
	} 
	?>.</p>
<h2>Registration Deadline</h2>
<p>Registration will close on <?php $date = $row_contest_info['contestRegistrationDeadline']; echo dateconvert($date, 2); ?>. Please note: registered users will <em>not</em> be able to add, view, edit or delete entries after <?php $date = $row_contest_info['contestRegistrationDeadline']; echo dateconvert($date, 2); ?>.</p>
<h2>Entry Deadline</h2>
<p>All entries must be received by our shipping location or at a drop-off location by <?php $date = $row_contest_info['contestEntryDeadline']; echo dateconvert($date, 2); ?>. Entries will not be accepted beyond this date.</p> 
<?php } ?>