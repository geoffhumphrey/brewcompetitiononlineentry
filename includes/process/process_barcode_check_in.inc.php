<?php

/*
$process_allowed = FALSE;
if (isset($_SERVER['HTTP_REFERER'])) {
	$referrer = parse_url($_SERVER['HTTP_REFERER']);
	if (($referrer['host'] == $_SERVER['SERVER_NAME']) && (isset($_SESSION['prefs'.$prefix_session]))) $process_allowed = TRUE;
}

$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($request_method === "POST") {

	$token_hash = FALSE;
	$token = filter_input(INPUT_POST,'token',FILTER_SANITIZE_STRING);
	if (hash_equals($_SESSION['token'],$token)) $token_hash = TRUE;

	echo $request_method."<br>";
	echo $token."<br>";
	echo $_SESSION['token'];

	exit();

	if ((!$token) || (!$token_hash) || (!$process_allowed)) {
		session_unset();
		session_destroy();
		session_write_close();
		$redirect = $base_url."403.php";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);
		header($redirect_go_to);
		exit();
	}

}




echo $request_method."<br>";
echo $token."<br>";
echo $_SESSION['token'];
exit();

*/
if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";
	$_SESSION['barcode_entry_list'] = "";
	$_SESSION['flag_jnum'] = "";
	$_SESSION['flag_enum'] = "";

	$entries_updated = array();
	$flag_jnum = array();
	$flag_enum = array();

	foreach ($_POST['id'] as $id) {

		$judging_number = "";
		$entry_number = "";
		$box_number = "";
		$paid = 0;

		if ($_POST['eid'.$id] != "") {

			if ((isset($_POST['judgingNumber'.$id])) && (!empty($_POST['judgingNumber'.$id]))) $judging_number = (sterilize($_POST['judgingNumber'.$id]));
			$entry_number = sterilize($_POST['eid'.$id]);

			// Check to see if the judging number has already been used and if so, flag it
			$query_jnum = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewJudgingNumber='%s'",$prefix."brewing",$judging_number);
			$jnum = mysqli_query($connection,$query_jnum) or die (mysqli_error($connection));
			$row_jnum = mysqli_fetch_assoc($jnum);

			// echo $query_jnum; exit();

			// Check to see if the entry number exists
			$query_enum = sprintf("SELECT brewJudgingNumber,brewPaid FROM %s WHERE id='%s'",$prefix."brewing",$entry_number);
			$enum = mysqli_query($connection,$query_enum) or die (mysqli_error($connection));
			$row_enum = mysqli_fetch_assoc($enum);
			$totalRows_enum = mysqli_num_rows($enum);

			if ($totalRows_enum == 0) $flag_enum[] = $entry_number;

			if ($row_jnum['count'] > 0) {
				if (isset($_POST['judgingNumber'.$id])) $flag_jnum[$entry_number] = $judging_number;
			}

			/**
			 * Some comps wish to scan entries in to receive them, 
			 * but use the system assigned judging number (no barcode
			 * stickers). If the POST var is not set or is blank, use the
			 * existing judging number in the system.
			 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1279
			 */

			if ((!isset($_POST['judgingNumber'.$id])) || (empty($_POST['judgingNumber'.$id]))) $judging_number = $row_enum['brewJudgingNumber'];

			if (($totalRows_enum > 0) && ($row_jnum['count'] == 0)) {

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

				if ((isset($_POST['brewPaid'.$id])) && ($_POST['brewPaid'.$id] == 1)) $paid = 1; 
				else $paid = $row_enum['brewPaid'];

				if (isset($_POST['box'.$id])) $box_number = sterilize($_POST['box'.$id]);

				$update_table = $prefix."brewing";
				$data = array(
					'brewReceived' => '1',
					'brewJudgingNumber' => blank_to_null($judging_number),
					'brewBoxNum' => blank_to_null($box_number),
					'brewPaid' => blank_to_null($paid)
				);			
				$db_conn->where ('id', $eid);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			} // end if (($totalRows_enum > 0) && ($row_jnum['count'] == 0))

		} // end if ($_POST['eid'.$id] != "")

	} // end foreach

	$_SESSION['barcode_entry_list'] = $entries_updated;
	$_SESSION['flag_jnum'] = $flag_jnum;
	$_SESSION['flag_enum'] = $flag_enum;

	/*
	print_r($_SESSION['barcode_entry_list']);
	echo "<br>";
	print_r($_SESSION['flag_jnum']);
	echo "<br>";
	exit();
	*/

	if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

	$redirect = $base_url."index.php?section=admin&go=checkin";
	if ($go != "default") $redirect .= "&filter=".$go;
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}

else {
	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);
	exit();
}
?>