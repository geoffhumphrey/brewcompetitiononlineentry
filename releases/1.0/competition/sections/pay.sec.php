<div id="header">	
	<div id="header-inner"><h1>Pay Entry Fees</h1></div>
</div>
<?php 
if ($msg == "1") echo "<div class=\"error\">Your payment has been received. Thanks and best of luck in the contest.</div>"; 
if ($msg == "2") echo "<div class=\"error\">Your payment has been cancelled.</div>"; 
?>
<p>You currently have logged <a href="index.php?section=list"><?php echo $totalRows_log; ?> entries</a> in the <?php echo $row_contest_info['contestName']; ?> contest.  Your total entry fees are <?php echo $row_prefs['prefsCurrency']; echo ($totalRows_log * $row_contest_info['contestEntryFee']).".00" ;?>.</p>
<h2>Cash or Check</h2>
<p>Attach cash or a check made out to <?php echo $row_contest_info['contestHost']; ?> to your entries. See the <a href="index.php?section=entry">Entry Information</a> page for mailing instructions and drop off locations.</p>
<?php if  ($row_prefs['prefsPaypal'] == "Y") { 
if     ($row_prefs['prefsCurrency'] == "&pound;") $currency_code = "GBP";
elseif ($row_prefs['prefsCurrency'] == "&euro;")  $currency_code = "EUR";
elseif ($row_prefs['prefsCurrency'] == "&yen;")   $currency_code = "JPY";
else   $currency_code = "USD";
?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $row_prefs['prefsPaypalAccount']; ?>">
<input type="hidden" name="item_name" value="<?php echo $row_contest_info['contestName']; ?> Contest Entry Payment for <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']." (".$totalRows_log." Entries)"; ?>">
<input type="hidden" name="amount" value="<?php echo ($totalRows_log * $row_contest_info['contestEntryFee']).".00"; ?>">
<input type="hidden" name="cn" value="Message to the organizers:">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>">
<input type="hidden" name="rm" value="1">
<input type="hidden" name="return" value="<?php echo "http://".$_SERVER['SERVER_NAME']."/index.php?section=pay&msg=1"; ?>">
<input type="hidden" name="cancel_return" value="<?php echo "http://".$_SERVER['SERVER_NAME']."/index.php?section=pay&msg=2"; ?>">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
<h2>Pay Online</h2>
<p>Click the "Pay Now" button below to pay online using PayPal.</p>
<p><input align="left" type="image" src="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="Pay your contest entry fees with PayPal" title="Pay your contest entry fees with PayPal"></p>
</form>
<?php } ?>

