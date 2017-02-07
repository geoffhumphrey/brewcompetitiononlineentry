<?php

if (isset($_SERVER['HTTP_REFERER'])) {
	
	$entries_updated[] = "";
	
	foreach ($_POST['id'] as $id) {
		
		if ($_POST['eid'.$id] != "") {
			
			$judging_number = number_pad($_POST['judgingNumber'.$id],6);
			
			// Check to see if the judging number has already been used and if so, flag it
			$query_jnum = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewJudgingNumber='%s'",$prefix."brewing",$judging_number);
			$jnum = mysqli_query($connection,$query_jnum) or die (mysqli_error($connection));
			$row_jnum = mysqli_fetch_assoc($jnum);
			
			// Check to see if the entry number exists
			$query_enum = sprintf("SELECT brewJudgingNumber,brewPaid FROM %s WHERE id='%s'",$prefix."brewing",$_POST['eid'.$id]);
			$enum = mysqli_query($connection,$query_enum) or die (mysqli_error($connection));
			$row_enum = mysqli_fetch_assoc($enum);
			$totalRows_enum = mysqli_num_rows($enum);
			
			if ($row_jnum['count'] > 0) $flag_jnum[] = $judging_number."*".$_POST['eid'.$id];
			
			if ($totalRows_enum > 0) {
				
				if ($prefix == "final_") {
					if ($_POST['eid'.$id] < 9) $eid = ltrim($_POST['eid'.$id],"00000");
					elseif (($_POST['eid'.$id] >= 10) && ($_POST['eid'.$id] <= 99)) $eid = ltrim($_POST['eid'.$id],"0000");
					elseif (($_POST['eid'.$id] >= 100) && ($_POST['eid'.$id] <= 999)) $eid = ltrim($_POST['eid'.$id],"000");
					elseif (($_POST['eid'.$id] >= 1000) && ($_POST['eid'.$id] <= 9999)) $eid = ltrim($_POST['eid'.$id],"00");
					elseif (($_POST['eid'.$id] >= 10000) && ($_POST['eid'.$id] <= 99999)) $eid = ltrim($_POST['eid'.$id],"0");
					else $eid = $_POST['eid'.$id];
					$entries_updated[] = number_pad($_POST['eid'.$id],6);
				}
				
				else {
					if ($_POST['eid'.$id] < 9) $eid = ltrim($_POST['eid'.$id],"000");
					elseif (($_POST['eid'.$id] >= 10) && ($_POST['eid'.$id] <= 99)) $eid = ltrim($_POST['eid'.$id],"00");
					elseif (($_POST['eid'.$id] >= 100) && ($_POST['eid'.$id] <= 999)) $eid = ltrim($_POST['eid'.$id],"0");
					else $eid = $_POST['eid'.$id];
					$entries_updated[] = number_pad($_POST['eid'.$id],4);
				}
				
				if ((isset($_POST['brewPaid'.$id])) && ($_POST['brewPaid'.$id] == 1)) $brewPaid = 1; else $brewPaid = $row_enum['brewPaid'];
				
				$updateSQL = sprintf("UPDATE %s SET brewReceived='1', brewJudgingNumber='%s', brewBoxNum='%s', brewPaid='%s' WHERE id='%s';",$brewing_db_table,$judging_number, $_POST['box'.$id],$brewPaid,$eid);
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