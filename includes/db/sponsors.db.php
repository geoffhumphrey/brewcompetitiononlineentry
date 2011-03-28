<?php
if ($action == "edit") $query_sponsors = "SELECT * FROM sponsors WHERE id='$id'"; 
else $query_sponsors = "SELECT * FROM sponsors ORDER BY sponsorLevel,sponsorName";
$sponsors = mysql_query($query_sponsors, $brewing) or die(mysql_error());
$row_sponsors = mysql_fetch_assoc($sponsors);
$totalRows_sponsors = mysql_num_rows($sponsors);
?>