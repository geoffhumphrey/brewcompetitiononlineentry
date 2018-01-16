<?php

if (isset($_SERVER['HTTP_REFERER'])) {

	$entries_updated[] = "";

	foreach ($_POST['id'] as $id) {

		$judging_number = "";
		$entry_number = "";

		if ($_POST['eid'.$id] != "") {

			$judging_number = number_pad(sterilize($_POST['judgingNumber'.$id]),6);
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

			if ($row_jnum['count'] > 0) $flag_jnum[] = $judging_number."*".$entry_number;

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
					$entries_updated[] = number_pad($entry_number,4);
				}

				if ((isset($_POST['brewPaid'.$id])) && ($_POST['brewPaid'.$id] == 1)) $brewPaid = 1; else $brewPaid = $row_enum['brewPaid'];

				$updateSQL = sprintf("UPDATE %s SET brewReceived='1', brewJudgingNumber='%s', brewBoxNum='%s', brewPaid='%s' WHERE id='%s';", $brewing_db_table, $judging_number, sterilize($_POST['box'.$id]), $brewPaid, $eid);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				//echo $updateSQL."<br>";
			}
		}
	}

	$entry_list .= display_array_content($entries_updated,2);

}
else {
	header(sprintf("Location: %s", $base_url."index.php?msg=98"));
	exit;
}
?>