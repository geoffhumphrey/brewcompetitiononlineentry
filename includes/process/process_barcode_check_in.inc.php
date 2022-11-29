<?php

if (isset($_SERVER['HTTP_REFERER'])) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	$entries_updated = array();
	$flag_jnum = array();

	foreach ($_POST['id'] as $id) {

		$judging_number = "";
		$entry_number = "";

		if ($_POST['eid'.$id] != "") {

			$judging_number = "";

			if ((isset($_POST['judgingNumber'.$id])) && (!empty($_POST['judgingNumber'.$id]))) $judging_number = number_pad(sterilize($_POST['judgingNumber'.$id]),6);
			$entry_number = sterilize($_POST['eid'.$id]);

			// Check to see if the judging number has already been used and if so, flag it
			$query_jnum = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewJudgingNumber='%s'",$prefix."brewing",$judging_number);
			$jnum = mysqli_query($connection,$query_jnum) or die (mysqli_error($connection));
			$row_jnum = mysqli_fetch_assoc($jnum);

			// Check to see if the entry number exists
			$query_enum = sprintf("SELECT brewJudgingNumber,brewPaid FROM %s WHERE id='%s'",$prefix."brewing",$entry_number);
			$enum = mysqli_query($connection,$query_enum) or die (mysqli_error($connection));
			$row_enum = mysqli_fetch_assoc($enum);
			$totalRows_enum = mysqli_num_rows($enum);

			if ($row_jnum['count'] > 0) {
				if (isset($_POST['judgingNumber'.$id])) $flag_jnum[] = $judging_number."*".$entry_number;
			}

			/**
			 * Some comps wish to scan entries in to receive them, 
			 * but use the system assigned judging number (no barcode
			 * stickers). If the POST var is not set or is blank, use the
			 * existing judging number in the system.
			 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1279
			 */

			if ((!isset($_POST['judgingNumber'.$id])) || (empty($_POST['judgingNumber'.$id]))) $judging_number = $row_enum['brewJudgingNumber'];

			if ($totalRows_enum > 0) {

				if ($prefix == "final_") {
					if ($entry_number < 9) $eid = ltrim($entry_number,"00000");
					elseif (($entry_number >= 10) && ($entry_number <= 99)) $eid = ltrim($entry_number,"0000");
					elseif (($entry_number >= 100) && ($entry_number <= 999)) $eid = ltrim($entry_number,"000");
					elseif (($entry_number >= 1000) && ($entry_number <= 9999)) $eid = ltrim($entry_number,"00");
					elseif (($entry_number >= 10000) && ($entry_number <= 99999)) $eid = ltrim($entry_number,"0");
					else $eid = $entry_number;
					$entries_updated[] = number_pad($entry_number,6);
				}

				else {
					if ($entry_number < 9) $eid = ltrim($entry_number,"000");
					elseif (($entry_number >= 10) && ($entry_number <= 99)) $eid = ltrim($entry_number,"00");
					elseif (($entry_number >= 100) && ($entry_number <= 999)) $eid = ltrim($entry_number,"0");
					else $eid = $entry_number;
					$entries_updated[] = number_pad($entry_number,6);
				}

				if ((isset($_POST['brewPaid'.$id])) && ($_POST['brewPaid'.$id] == 1)) $brewPaid = 1; 
				else $brewPaid = $row_enum['brewPaid'];

				$update_table = $prefix."brewing";
				$data = array(
					'brewReceived' => '1',
					'brewJudgingNumber' => blank_to_null($judging_number),
					'brewBoxNum' => blank_to_null(sterilize($_POST['box'.$id])),
					'brewPaid' => blank_to_null($brewPaid)
				);			
				$db_conn->where ('id', $eid);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			} // end if ($totalRows_enum > 0)

		} // end if ($_POST['eid'.$id] != "")

	} // end foreach

	$entry_list .= display_array_content($entries_updated,2);

	if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

}

else {
	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);
	exit();
}
?>