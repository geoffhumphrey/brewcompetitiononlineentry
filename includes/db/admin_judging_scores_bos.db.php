<?php

if (SINGLE) {
	
	$query_style_type = sprintf("SELECT * FROM $style_types_db_table WHERE comp_id='0' OR comp_id='%s'",$_SESSION['comp_id']); 
	if (($action == "edit") && ($filter != "default")) $query_style_type .= " AND id='$filter'";
	if (($action == "enter") && ($filter != "default")) $query_style_type .= " AND id='$filter'";
	if (($go != "styles") && ($id !="default")) $query_style_type .= " AND id='$id'";
	if ((($go == "judging_tables") || ($go == "judging_scores_bos")) && ($action == "default") && ($id == "default")) $query_style_type .= sprintf(" AND FIND_IN_SET('%s',styleTypeBOS) > 0","Y-".$_SESSION['comp_id']); 
	$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
	$row_style_type = mysqli_fetch_assoc($style_type);
	$totalRows_style_type = mysqli_num_rows($style_type);

}

else {
	
	$query_style_type = "SELECT * FROM $style_types_db_table"; 
	if (($action == "edit") && ($filter != "default")) $query_style_type .= " WHERE id='$filter'";
	if (($action == "enter") && ($filter != "default")) $query_style_type .= " WHERE id='$filter'";
	if (($go != "styles") && ($id !="default")) $query_style_type .= " WHERE id='$id'";
	if ((($go == "judging_tables") || ($go == "judging_scores_bos")) && ($action == "default") && ($id == "default")) $query_style_type .= " WHERE styleTypeBOS='Y'";
	$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
	$row_style_type = mysqli_fetch_assoc($style_type);
	$totalRows_style_type = mysqli_num_rows($style_type);
	
}


if ($action == "enter") {
	
	if (SINGLE) {
	
		$query_enter_bos = sprintf("SELECT * FROM $judging_scores_db_table WHERE comp_id='%s'",$_SESSION['comp_id']); 
		
		if ($row_style_type['id'] <= 3) {
			$explode = explode(",",$row_style_type['styleTypeBOSMethod']);
			if (in_array("1-".$_SESSION['comp_id'],$explode)) $query_enter_bos .= " AND scoreType='$filter' AND scorePlace='1'";
			if (in_array("2-".$_SESSION['comp_id'],$explode)) $query_enter_bos .= " AND scoreType='$filter' AND (scorePlace='1' OR scorePlace='2')";
			if (in_array("3-".$_SESSION['comp_id'],$explode)) $query_enter_bos .= " AND (scoreType='$filter' AND scorePlace='1') OR (scoreType='$filter' AND scorePlace='2') OR (scoreType='$filter' AND scorePlace='3')";
		}
		
		else {
			if ($row_style_type['styleTypeBOSMethod'] == "1") $query_enter_bos .= " AND scoreType='$filter' AND scorePlace='1'";
			if ($row_style_type['styleTypeBOSMethod'] == "2") $query_enter_bos .= " AND scoreType='$filter' AND (scorePlace='1' OR scorePlace='2')";
			if ($row_style_type['styleTypeBOSMethod'] == "3") $query_enter_bos .= " AND (scoreType='$filter' AND scorePlace='1') OR (scoreType='$filter' AND scorePlace='2') OR (scoreType='$filter' AND scorePlace='3')";			
		}
		
		$query_enter_bos .= " ORDER BY scoreTable ASC";
		$enter_bos = mysqli_query($connection,$query_enter_bos) or die (mysqli_error($connection));
		$row_enter_bos = mysqli_fetch_assoc($enter_bos);
		$totalRows_enter_bos = mysqli_num_rows($enter_bos);
		
	}
	
	else { 
	
		$query_enter_bos = "SELECT * FROM $judging_scores_db_table";
		if ($row_style_type['styleTypeBOSMethod'] == "1") $query_enter_bos .= " WHERE scoreType='$filter' AND scorePlace='1'";
		if ($row_style_type['styleTypeBOSMethod'] == "2") $query_enter_bos .= " WHERE scoreType='$filter' AND (scorePlace='1' OR scorePlace='2')";
		if ($row_style_type['styleTypeBOSMethod'] == "3") $query_enter_bos .= " WHERE (scoreType='$filter' AND scorePlace='1') OR (scoreType='$filter' AND scorePlace='2') OR (scoreType='$filter' AND scorePlace='3')";
		//if ($_SESSION['jPrefsBOSMethodBeer'] == "4") $query_enter_bos .= " WHERE scoreType='B' AND scorePlace='1'";
		$query_enter_bos .= " ORDER BY scoreTable ASC";
		$enter_bos = mysqli_query($connection,$query_enter_bos) or die (mysqli_error($connection));
		$row_enter_bos = mysqli_fetch_assoc($enter_bos);
		$totalRows_enter_bos = mysqli_num_rows($enter_bos);
	
	}
}

else {
	
	if (SINGLE) {
		
		$query_bos = sprintf("SELECT * FROM $judging_scores_db_table WHERE comp_id='%s'",$_SESSION['comp_id']); 
		
		if ($row_style_type['id'] <= 3) {
			$explode = explode(",",$style_type_info[1]);
			if (in_array("1-".$_SESSION['comp_id'],$explode)) $query_bos .= " AND scoreType='$type' AND scorePlace='1'";
			if (in_array("2-".$_SESSION['comp_id'],$explode)) $query_bos .= " AND scoreType='$type' AND (scorePlace='1' OR scorePlace='2')";
			if (in_array("3-".$_SESSION['comp_id'],$explode)) $query_bos .= " AND (scoreType='$type' AND scorePlace='1') OR (scoreType='$type' AND scorePlace='2') OR (scoreType='$type' AND scorePlace='3')";
		}
		
		else {
			if ($style_type_info[1] == "1") $query_bos .= " AND scoreType='$type' AND scorePlace='1'";
			if ($style_type_info[1] == "2") $query_bos .= " AND scoreType='$type' AND (scorePlace='1' OR scorePlace='2')";
			if ($style_type_info[1] == "3") $query_bos .= " AND (scoreType='$type' AND scorePlace='1') OR (scoreType='$type' AND scorePlace='2') OR (scoreType='$type' AND scorePlace='3')";			
		}
		
		$query_bos .= " ORDER BY scoreTable ASC";
		$bos = mysqli_query($connection,$query_bos) or die (mysqli_error($connection));
		$row_bos = mysqli_fetch_assoc($bos);
		$totalRows_bos = mysqli_num_rows($bos);
		
	}
	
	else {

		$query_bos = "SELECT * FROM $judging_scores_db_table";
		if ($style_type_info[1] == "1") $query_bos .= " WHERE scoreType='$type' AND scorePlace='1'";
		if ($style_type_info[1] == "2") $query_bos .= " WHERE scoreType='$type' AND (scorePlace='1' OR scorePlace='2')";
		if ($style_type_info[1] == "3") $query_bos .= " WHERE (scoreType='$type' AND scorePlace='1') OR (scoreType='$type' AND scorePlace='2') OR (scoreType='$type' AND scorePlace='3')";
		$query_bos .= " ORDER BY scoreTable ASC";
		$bos = mysqli_query($connection,$query_bos) or die (mysqli_error($connection));
		$row_bos = mysqli_fetch_assoc($bos);
		$totalRows_bos = mysqli_num_rows($bos);
	
	}

}
?>