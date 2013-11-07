<?php

if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {
	
	$entry_list = "";
	
	foreach ($_POST['id'] as $id) {
		
		if ($_POST['eid'.$id] != "") {
			
			$judging_number = number_pad($_POST['judgingNumber'.$id],6);
			
			// Check to see if the judging number has already been used and if so, flag it
			$query_jnum = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewJudgingNumber='%s'",$prefix."brewing",$judging_number);
			$jnum = mysql_query($query_jnum, $brewing) or die(mysql_error());
			$row_jnum = mysql_fetch_assoc($jnum);
			
			$query_enum = sprintf("SELECT brewJudgingNumber FROM %s WHERE id='%s'",$prefix."brewing",$_POST['eid'.$id]);
			$enum = mysql_query($query_enum, $brewing) or die(mysql_error());
			$row_enum = mysql_fetch_assoc($enum);
			
			if (($row_enum['brewJudgingNumber'] != "") && (strlen($row_enum['brewJudgingNumber']) == 6)) $flag_enum[] = $row_enum['brewJudgingNumber']."*".$_POST['eid'.$id];
			elseif ($row_jnum['count'] > 0) $flag_jnum[] = $judging_number."*".$_POST['eid'.$id];
			
			else {
				
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
				
				if ($_POST['brewPaid'.$id] == 1) $brewPaid = 1; else $brewPaid = 0;
				
				$updateSQL = sprintf("UPDATE %s SET brewReceived='1', brewJudgingNumber='%s', brewBoxNum='%s', brewPaid='%s' WHERE id='%s';",$brewing_db_table,$judging_number, $_POST['box'.$id],$brewPaid,$eid);
				$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
				//echo $updateSQL."<br>";
			}
		}
	}
	$entry_list .= display_array_content($entries_updated,2);

}

?>