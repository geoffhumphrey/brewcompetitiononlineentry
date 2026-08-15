<?php

$update_table = $prefix."styles";

$data = array('brewStyleEntry' => 'The entrant must specify whether the entry is a pale or a dark variant.');
$db_conn->where ('brewStyleGroup', '09');
$db_conn->where ('brewStyleNum', 'A');
$db_conn->where ('brewStyleVersion', 'BJCP2021');
$result = $db_conn->update ($update_table, $data);
if ($result) $v3000_update .= "<li>Updated BJCP 2021 style 9A's entry instructions.</li>"; 
else {
  $v3000_update .= "<li>Update of BJCP 2021 style 9A's entry instructions failed. <strong class=\"text-danger\">Error: ".$db_conn->getLastError()."</strong></li>";
  $error_count += 1;
}

$nw_cider_update_errors = 0;
$nw_cider_update_output = "";

$data = array('brewStyleInfo' => 'FG &#62; 1.007 (&#62; 1.8 Brix).', 'brewStyleReqSpec' => '1', 'brewStyleEntry' => '<p>Entrants <strong><u>MUST</u></strong> specify apples used. Entrants <strong><u>MAY</u></strong> include additions.</p>');
$db_conn->where ('brewStyleGroup', 'C3');
$db_conn->where ('brewStyleNum', 'A');
$db_conn->where ('brewStyleVersion', 'NWCiderCup');
$result = $db_conn->update ($update_table, $data);
if (!$result) {
	$nw_cider_update_errors++;
	$nw_cider_update_output .= "<li>NW Cider Cup Style C3 A was NOT updated.</li>";
}

$data = array('brewStyleInfo' => 'FG &#62; 1.007 (&#62; 1.8 Brix).', 'brewStyleReqSpec' => '1', 'brewStyleEntry' => '<p>Entrants <strong><u>MUST</u></strong> specify apples used. Entrants <strong><u>MAY</u></strong> include additions.</p>');
$db_conn->where ('brewStyleGroup', 'C3');
$db_conn->where ('brewStyleNum', 'B');
$db_conn->where ('brewStyleVersion', 'NWCiderCup');
$result = $db_conn->update ($update_table, $data);
if (!$result) {
	$nw_cider_update_errors++;
	$nw_cider_update_output .= "<li>NW Cider Cup Style C9 C was NOT updated.</li>";
}

$data = array('brewStyleInfo' => NULL, 'brewStyleEntry' => '<p>Entrants <strong><u>MUST</u></strong> specify wood used, including the type of wine, beer, or spirits barrel, if applicable.</p><p>Entrants <strong><u>MAY</u></strong> specify apples and process used, and additions.</p>');
$db_conn->where ('brewStyleGroup', 'C5');
$db_conn->where ('brewStyleNum', 'A');
$db_conn->where ('brewStyleVersion', 'NWCiderCup');
$result = $db_conn->update ($update_table, $data);
if (!$result) {
	$nw_cider_update_errors++;
	$nw_cider_update_output .= "<li>NW Cider Cup Style C5 A was NOT updated.</li>";
}

$data = array('brewStyleInfo' => NULL, 'brewStyleEntry' => '<p>Entrants <strong><u>MUST</u></strong> specify apple or pear variety used. Entrants <strong><u>MAY</u></strong> include additions.</p>');
$db_conn->where ('brewStyleGroup', 'C6');
$db_conn->where ('brewStyleNum', 'A');
$db_conn->where ('brewStyleVersion', 'NWCiderCup');
$result = $db_conn->update ($update_table, $data);
if (!$result) {
	$nw_cider_update_errors++;
	$nw_cider_update_output .= "<li>NW Cider Cup Style C6 A was NOT updated.</li>";
}

$data = array('brewStyleInfo' => NULL, 'brewStyleEntry' => '<p>Entrants <strong><u>MUST</u></strong> specify all fruits used. Entrants <strong><u>MUST</u></strong> specify primary fruit. Entrants <strong><u>MAY</u></strong> include additions.</p>');
$db_conn->where ('brewStyleGroup', 'C7');
$db_conn->where ('brewStyleNum', 'A');
$db_conn->where ('brewStyleVersion', 'NWCiderCup');
$result = $db_conn->update ($update_table, $data);
if (!$result) {
	$nw_cider_update_errors++;
	$nw_cider_update_output .= "<li>NW Cider Cup Style C7 A was NOT updated.</li>";
}

$data = array('brewStyleInfo' => 'FG &#62; 1.007 (&#62; 1.8 Brix).', 'brewStyleEntry' => '<p>Entrants <strong><u>MUST</u></strong> specify all fruits used. Entrants <strong><u>MUST</u></strong> specify primary fruit. Entrants <strong><u>MAY</u></strong> include additions.</p>' );
$db_conn->where ('brewStyleGroup', 'C7');
$db_conn->where ('brewStyleNum', 'B');
$db_conn->where ('brewStyleVersion', 'NWCiderCup');
$result = $db_conn->update ($update_table, $data);
if (!$result) {
	$nw_cider_update_errors++;
	$nw_cider_update_output .= "<li>NW Cider Cup Style C7 B was NOT updated.</li>";
}

$data = array('brewStyleInfo' => 'FG &#60; 1.007 (&#60; 1.8 Brix).', 'brewStyleEntry' => '<p>Entrants <strong><u>MUST</u></strong> specify all fruits used. Entrants <strong><u>MUST</u></strong> specify primary fruit. Entrants <strong><u>MAY</u></strong> include additions.</p>');
$db_conn->where ('brewStyleGroup', 'C7');
$db_conn->where ('brewStyleNum', 'C');
$db_conn->where ('brewStyleVersion', 'NWCiderCup');
$result = $db_conn->update ($update_table, $data);
if (!$result) {
	$nw_cider_update_errors++;
	$nw_cider_update_output .= "<li>NW Cider Cup Style C7 C was NOT updated.</li>";
}

$data = array('brewStyleInfo' => NULL, 'brewStyleEntry' => '<p>Entrants <strong><u>MUST</u></strong> specify hop variety or varieties. Entrants <strong><u>MAY</u></strong> specify apples or pears used and additions.</p>');
$db_conn->where ('brewStyleGroup', 'C8');
$db_conn->where ('brewStyleNum', 'A');
$db_conn->where ('brewStyleVersion', 'NWCiderCup');
$result = $db_conn->update ($update_table, $data);
if (!$result) {
	$nw_cider_update_errors++;
	$nw_cider_update_output .= "<li>NW Cider Cup Style C8 A was NOT updated.</li>";
}

$data = array('brewStyleInfo' => NULL, 'brewStyleEntry' => '<p>Entrants <strong><u>MUST</u></strong> specify herbs and and/or spices used.</p> Entrants <strong><u>MAY</u></strong> specify apples or pears used and additions.</p>');
$db_conn->where ('brewStyleGroup', 'C8');
$db_conn->where ('brewStyleNum', 'B');
$db_conn->where ('brewStyleVersion', 'NWCiderCup');
$result = $db_conn->update ($update_table, $data);
if (!$result) {
	$nw_cider_update_errors++;
	$nw_cider_update_output .= "<li>NW Cider Cup Style C8 B was NOT updated.</li>";
}

$data = array('brewStyleInfo' => NULL, 'brewStyleEntry' => '<p>Entrants <strong><u>MUST</u></strong> specify spices and/or herbs used. Entrants <strong><u>MAY</u></strong> specify apples or pears used and additions.</p>');
$db_conn->where ('brewStyleGroup', 'C8');
$db_conn->where ('brewStyleNum', 'C');
$db_conn->where ('brewStyleVersion', 'NWCiderCup');
$result = $db_conn->update ($update_table, $data);
if (!$result) {
	$nw_cider_update_errors++;
	$nw_cider_update_output .= "<li>NW Cider Cup Style C8 C was NOT updated.</li>";
}

$data = array('brewStyleInfo' => 'ABV &#62; 10%.', 'brewStyleEntry' => '<p>Entrants <strong><u>MUST</u></strong> specify the process used. Entrants <strong><u>MAY</u></strong> specify apples or pears used and additions.</p>');
$db_conn->where ('brewStyleGroup', 'C9');
$db_conn->where ('brewStyleNum', 'A');
$db_conn->where ('brewStyleVersion', 'NWCiderCup');
$result = $db_conn->update ($update_table, $data);
if (!$result) {
	$nw_cider_update_errors++;
	$nw_cider_update_output .= "<li>NW Cider Cup Style C9 A was NOT updated.</li>";
}

$data = array('brewStyleInfo' => 'ABV range: 0.5% - 4.5%.', 'brewStyleEntry' => '<p>Entrants <strong><u>MUST</u></strong> specify process used (i.e., alcohol removed or ciderkin production). Entrants <strong><u>MAY</u></strong> specify apples or pears used.</p>');
$db_conn->where ('brewStyleGroup', 'C9');
$db_conn->where ('brewStyleNum', 'B');
$db_conn->where ('brewStyleVersion', 'NWCiderCup');
$result = $db_conn->update ($update_table, $data);
if (!$result) {
	$nw_cider_update_errors++;
	$nw_cider_update_output .= "<li>NW Cider Cup Style C9 B was NOT updated.</li>";
}

$data = array('brewStyleInfo' => 'A cider or perry not suitable for any other category. Entrant notes are of utmost importance for ciders in this category.', 'brewStyleEntry' => '<p>Entrants <strong><u>MUST</u></strong> specify fruit and additions. Entrants <strong><u>MUST</u></strong> specify commercial yeast cultures or wild yeast. Entrants <strong><u>MUST</u></strong> specify processes and ingredients that make the cider not suitable for any other category.');
$db_conn->where ('brewStyleGroup', 'C9');
$db_conn->where ('brewStyleNum', 'C');
$db_conn->where ('brewStyleVersion', 'NWCiderCup');
$result = $db_conn->update ($update_table, $data);
if (!$result) {
	$nw_cider_update_errors++;
	$nw_cider_update_output .= "<li>NW Cider Cup Style C9 C was NOT updated.</li>";
}

if ($nw_cider_update_errors > 0) {
	$v3000_update .= $nw_cider_update_output;
	$error_count++;
}

else $v3000_update .= "<li>2025 changes to NW Cider Cup styles updated successfully.</li>";

?>