<?php
// ---------------- DEBUG SESSION VARS --------------------
// echo phpinfo();
$table_body = "";
foreach ($_SESSION as $key => $value) {
	if (is_array($value)) {
		$table_body .=  "<tr><td>".$key."</td><td>".display_array_content($value,2)."</td></tr>";
	}
	else $table_body .=  "<tr><td>".$key."</td><td>".$value."</td></tr>";
}

if ($logged_in) {
    if ($_SESSION['userLevel'] == 0) {
        echo "<p><button class='btn btn-primary' type='button' data-toggle='collapse' data-target='#sessionVars' aria-expanded='false' aria-controls='sessionVars'>View Session Vars</button> <a class=\"btn btn-danger\" href=\"".$base_url."includes/process.inc.php?section=".$section."&amp;action=clear_session\">Clear Session Vars</a></p>";
        echo "<div class='collapse bcoem-admin-element-bottom' id='sessionVars'>";
        echo "<table class='table table-striped table-bordered'";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Var Name</th>";
        echo "<th>Value</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        echo $table_body;
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    }
}
?>