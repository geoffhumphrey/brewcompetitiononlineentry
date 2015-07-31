<?php
/**
 * Module:      pay_nhc.sec.php 
 * Description: This module dispays payment information based upon the competition-
                specific payment preferences for NHC.
 * Based upon:  pay.sec.php
 * 
 */

//echo "<div class='error'>The payment system is currently unavailable.</div><p><strong>Your entries remain intact.</strong> We are aware of the issue and are currently working on a solution. Please check back later today to make your payments.</p><p>We apologize for any inconvenience.</p>";

if ($totalRows_log_paid >= $row_limits['prefsEntryLimit']) echo "<div class='error'>The limit of paid entries has been reached. The payment system is no longer active.</div>";

else {

	include (MODS.'nhc_region.php');
	
	// make sure using SSL connection
	/*
	$https = ((!empty($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] != 'off')) ? true : false;
	
	if (!$https)  {
		$location = "https://www.brewingcompetition.com/region" . nhc_region($prefix,4) . "/index.php?section=pay";
		header("Location: $location");
		exit;
	}
	*/
	
	$bid = $_SESSION['user_id'];
	$non_member_price_difference = ($_SESSION['contestEntryFee'] - $_SESSION['contestEntryFeePasswordNum']);
	
	$memberships = $_POST['memberships'];

	// Extending url_variables.inc.php for this application only
	
	$x_cust_id = "default";
	if (isset($_GET['x_cust_id'])) {
	  	$x_cust_id = (get_magic_quotes_gpc()) ? $_GET['x_cust_id'] : addslashes($_GET['x_cust_id']);
	}
	
	$x_response_code = "default";
	if (isset($_GET['x_response_code'])) {
	  	$x_response_code = (get_magic_quotes_gpc()) ? $_GET['x_response_code'] : addslashes($_GET['x_response_code']);
	} 
	
	$x_description = "default";
	if (isset($_GET['x_description'])) {
	  	$x_description = (get_magic_quotes_gpc()) ? $_GET['x_description'] : addslashes($_GET['x_description']);
	} 
	
	$x_amount = "default";
	if (isset($_GET['x_amount'])) {
	  	$x_amount = (get_magic_quotes_gpc()) ? $_GET['x_amount'] : addslashes($_GET['x_amount']);
	}
	
	$x_trans_id = "default";
	if (isset($_GET['x_trans_id'])) {
	  	$x_trans_id = (get_magic_quotes_gpc()) ? $_GET['x_trans_id'] : addslashes($_GET['x_trans_id']);
	} 
	
	$x_invoice_num = "default";
	if (isset($_GET['x_invoice_num'])) {
	  	$x_invoice_num = (get_magic_quotes_gpc()) ? $_GET['x_invoice_num'] : addslashes($_GET['x_invoice_num']);
	} 
	
	$x_auth_code = "default";
	if (isset($_GET['x_auth_code'])) {
	  	$x_auth_code = (get_magic_quotes_gpc()) ? $_GET['x_auth_code'] : addslashes($_GET['x_auth_code']);
	} 
	
	$x_response_reason_text = "default";
	if (isset($_GET['x_response_reason_text'])) {
	  	$x_response_reason_text = (get_magic_quotes_gpc()) ? $_GET['x_response_reason_text'] : addslashes($_GET['x_response_reason_text']);
	} 
	
	$x_response_reason_code = "default";
	if (isset($_GET['x_response_reason_code'])) {
		$x_response_reason_code = (get_magic_quotes_gpc()) ? $_GET['x_response_reason_code'] : addslashes($_GET['x_response_reason_code']);
	}
	
	// AHA number is used as PO NUM to identify in the DB who is/not a AHA member
	$x_po_num = "default";
	if (isset($_GET['x_po_num'])) {
		$x_po_num = (get_magic_quotes_gpc()) ? $_GET['x_po_num'] : addslashes($_GET['x_po_num']);
	} 
	
	// ********** Check if AHA Member (gets discount) ****************

	if (($row_brewer['brewerDiscount'] == "Y") || ($psort != "default")) $entry_fee_adjust = $_SESSION['contestEntryFeePasswordNum'];
	else $entry_fee_adjust = $_SESSION['contestEntryFee'];
	
	$total_entry_fees = total_fees($entry_fee_adjust, $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter);
		
	$total_paid_entry_fees = total_fees_paid($entry_fee_adjust, $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter);
	
	// ********** End Check if AHA Member ****************
	
	$total_to_pay = $total_entry_fees - $total_paid_entry_fees; 
	$total_not_paid = total_not_paid_brewer($_SESSION['user_id']);
	
	$unconfirmed = entries_unconfirmed($_SESSION['user_id']);
	
	if (($_SESSION['prefsPayToPrint'] == "Y") && ($unconfirmed > 0)) echo "<div class='error'>You cannot pay for your entries because one or more of your entries is unconfirmed.</div><p>Click &ldquo;My Info and Entries&rdquo; above to review your unconfirmed entries.</p>"; 

	else {
		
		
		
		if ($total_entry_fees > 0) { 
			if ($unconfirmed > 0) echo "<div class='error'>You have unconfirmed entries that are <em>not</em> reflected in your fee totals below. Please go to <a href='".build_public_url("list","default","default",$sef,$base_url)."'>your entry list</a> to confirm all your entry data.<br />Unconfirmed entry data will be deleted every 24 hours.</div>";
			echo '<p><span class="icon"><img src="<?php echo $base_url; ?>images/help.png"  /></span><a id="modal_window_link" href="http://help.brewcompetition.com/files/pay_my_fees.html" title="BCOE&amp;M Help: Pay My Fees">Pay My Fees Help</a></p>';
		}
		
		if (($total_entry_fees > 0) && ($total_entry_fees == $total_paid_entry_fees)) echo '<p><span class="icon"><img src="'. $base_url. 'images/thumb_up.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>Your fees have been paid. Thank you!</p>';
		if ($total_entry_fees == 0) echo "<p>You have not logged any entries yet.</p>";
		
		if (($total_to_pay > 0) && ($go == "default")) {
			if ($row_brewer['brewerDiscount'] == "Y") { 
				$view = $total_to_pay;
				$id = $_SESSION['user_id'];
				include (MODS.'nhc_free.php'); 
			} else { 
				$view = $total_to_pay;
				$id = $_SESSION['user_id'];
				include (MODS.'nhc_non-member.php'); 
			} 
		} // end if (($total_to_pay > 0) && ($go == "default"));
		
		if (($total_to_pay > 0) && ($go == "free")) 	include (MODS.'nhc_free.php');
		if (($total_to_pay > 0) && ($go == "pay")) 		include (MODS.'nhc_pay.php');
		if (($total_to_pay > 0) && ($go == "process")) 	include (MODS.'nhc_paid.php');
		
	} // end if (($_SESSION['prefsPayToPrint'] == "Y") && ($unconfirmed > 0))
	
} // end else if ($totalRows_log_paid >= $row_limits['prefsEntryLimit'])

?>
