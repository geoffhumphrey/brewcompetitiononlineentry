<?php
declare(strict_types=1);
if ($action == "edit") {
	$db_conn->where("id", $id);
	$row_contact = $db_conn->getOne($contacts_db_table);
	$totalRows_contact = $db_conn->count;
	$rows_contact = $row_contact ? array($row_contact) : array();
}
else {
	$db_conn->orderBy("contactLastName", "ASC");
	$db_conn->orderBy("contactPosition", "ASC");
	$rows_contact = $db_conn->get($contacts_db_table);
	$totalRows_contact = $db_conn->count;
	$row_contact = ($rows_contact && count($rows_contact) > 0) ? $rows_contact[0] : null;
}
?>