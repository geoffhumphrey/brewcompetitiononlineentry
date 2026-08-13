<?php
declare(strict_types=1);

$query_style_type = "SELECT * FROM $style_types_db_table";
$params_style_type = array();
if (($action == "edit") && ($filter != "default")) { $query_style_type .= " WHERE id=?"; $params_style_type[] = $filter; }
if (($action == "enter") && ($filter != "default")) { $query_style_type .= " WHERE id=?"; $params_style_type[] = $filter; }
if (($go != "styles") && ($id !="default")) { $query_style_type .= " WHERE id=?"; $params_style_type[] = $id; }
if ((($go == "judging_tables") || ($go == "judging_scores_bos")) && ($action == "default") && ($id == "default")) $query_style_type .= " WHERE styleTypeBOS='Y'";
if (($go == "judging_assignments") && ($action == "download") && ($filter == "default") && ($id == "default")) $query_style_type .= " WHERE styleTypeBOS='Y'";
$row_style_type = $db_conn->rawQueryOne($query_style_type, empty($params_style_type) ? null : $params_style_type);
$totalRows_style_type = $db_conn->count;


if ($action == "enter") {

	$db_conn->where('id', $filter);
	$row_style_types = $db_conn->getOne($style_types_db_table, "styleTypeName");

	// echo $row_style_types['styleTypeName'];

	if ($row_style_types['styleTypeName'] == "Mead/Cider") $mead_cider_combined = TRUE;
	else $mead_cider_combined = FALSE;

	$query_enter_bos = "SELECT * FROM $judging_scores_db_table";
	$params_enter_bos = array();
	if ($mead_cider_combined) $query_enter_bos .= " WHERE (scoreType='2' OR scoreType='3')";
	else {
		$query_enter_bos .= " WHERE scoreType=?";
		$params_enter_bos[] = $filter;
	}

	if ($row_style_type['styleTypeBOSMethod'] == "1") $query_enter_bos .= " AND scorePlace='1'";
	if ($row_style_type['styleTypeBOSMethod'] == "2") $query_enter_bos .= " AND (scorePlace='1' OR scorePlace='2')";
	if ($row_style_type['styleTypeBOSMethod'] == "3") $query_enter_bos .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3')";

	$query_enter_bos .= " ORDER BY scoreTable ASC";
	$rows_enter_bos = $db_conn->rawQuery($query_enter_bos, empty($params_enter_bos) ? null : $params_enter_bos);
	$row_enter_bos = ($rows_enter_bos && count($rows_enter_bos) > 0) ? $rows_enter_bos[0] : null;
	$totalRows_enter_bos = $db_conn->count;


}

else {

	$db_conn->where('id', $type);
	$row_style_types = $db_conn->getOne($style_types_db_table, "styleTypeName");

	if ($row_style_types['styleTypeName'] == "Mead/Cider") $mead_cider_combined = TRUE;
	else $mead_cider_combined = FALSE;

	if (SINGLE) {

		$query_bos = "SELECT * FROM $judging_scores_db_table WHERE comp_id=?";
		$params_bos = array($_SESSION['comp_id']);
		if ($mead_cider_combined) $query_bos .= " AND (scoreType='2' OR scoreType='3')";
		else {
			$query_bos .= " AND scoreType=?";
			$params_bos[] = $type;
		}

		if ($row_style_type['id'] <= 3) {
			$explode = explode(",",$style_type_info[1]);
			if (in_array("1-".$_SESSION['comp_id'],$explode)) $query_bos .= " AND scorePlace='1'";
			if (in_array("2-".$_SESSION['comp_id'],$explode)) $query_bos .= " AND (scorePlace='1' OR scorePlace='2')";
			if (in_array("3-".$_SESSION['comp_id'],$explode)) $query_bos .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3')";
		}

		else {
			if ($row_style_type['styleTypeBOSMethod'] == "1") $query_bos .= " AND scorePlace='1'";
			if ($row_style_type['styleTypeBOSMethod'] == "2") $query_bos .= " AND (scorePlace='1' OR scorePlace='2')";
			if ($row_style_type['styleTypeBOSMethod'] == "3") $query_bos .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3')";
		}

		$query_bos .= " ORDER BY scoreTable ASC";
		$rows_bos = $db_conn->rawQuery($query_bos, $params_bos);
		$row_bos = ($rows_bos && count($rows_bos) > 0) ? $rows_bos[0] : null;
		$totalRows_bos = $db_conn->count;

	}

	else {

		$query_bos = "SELECT * FROM $judging_scores_db_table";
		$params_bos = array();
		if ($mead_cider_combined) $query_bos .= " WHERE (scoreType='2' OR scoreType='3')";
		else {
			$query_bos .= " WHERE scoreType=?";
			$params_bos[] = $type;
		}
		if ($style_type_info[1] == "1") $query_bos .= " AND scorePlace='1'";
		if ($style_type_info[1] == "2") $query_bos .= " AND (scorePlace='1' OR scorePlace='2')";
		if ($style_type_info[1] == "3") $query_bos .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3')";
		$query_bos .= " ORDER BY scoreTable ASC";
		$rows_bos = $db_conn->rawQuery($query_bos, empty($params_bos) ? null : $params_bos);
		$row_bos = ($rows_bos && count($rows_bos) > 0) ? $rows_bos[0] : null;
		$totalRows_bos = $db_conn->count;

	}

}
?>