<?php
/**
 * Module:      print.php
 * Description: This module is the delivery vehicle for pages that are meant to be print only (for output).
 *
 */

// Redirect if directly accessed without authenticated session
if (!isset($_SESSION['loginUsername'])) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

if ((isset($_SESSION['loginUsername'])) || ($token != "default")) {
$role_replace1 = array("HJ","LJ","MBOS",", ");
$role_replace2 = array("<span class=\"fa fa-gavel\"></span> Head Judge","<span class=\"fa fa-star\"></span> Lead Judge","<span class=\"fa fa-trophy\"></span> Mini-BOS Judge","&nbsp;&nbsp;&nbsp;");

// Queries for current data
if ($filter == "default") {
	$winner_method = $_SESSION['prefsWinnerMethod'];
	$style_set = $_SESSION['prefsStyleSet'];
}

// Or, for archived data
else {

	// Query the archive table for preferences
	$query_archive_prefs = sprintf("SELECT * FROM %s WHERE archiveSuffix='%s'",$prefix."archive", $filter);
	$archive_prefs = mysqli_query($connection,$query_archive_prefs) or die (mysqli_error($connection));
	$row_archive_prefs = mysqli_fetch_assoc($archive_prefs);
	$totalRows_archive_prefs = mysqli_num_rows($archive_prefs);

	if ($totalRows_archive_prefs > 0) {
		$winner_method = $row_archive_prefs['archiveWinnerMethod'];
		$style_set = $row_archive_prefs['archiveStyleSet'];
		$judging_scores_db_table = $prefix."judging_scores_".$filter;
		$brewing_db_table = $prefix."brewing_".$filter;
		$brewer_db_table = $prefix."brewer_".$filter;
	}

}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <title><?php echo $_SESSION['contestName']; ?> - Brew Competition Online Entry &amp; Management</title>

    <!-- Load Bootstrap and jQuery -->
    <!-- Homepage URLs: http://www.getbootsrap.com and https://jquery.com -->
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $css_url; ?>print.min.css">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Load DataTables -->
    <!-- Homepage URL: https://www.datatables.net -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.10/integration/font-awesome/dataTables.fontAwesome.css" />
		<script type="text/javascript" src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>

    <!-- Load Font Awesome -->
    <!-- Homepage URL: https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

  	</head>
	<body>
    <div class="container-fluid">
    <?php

		if ($_SESSION['userLevel'] <= 1) {
			if ($section == "assignments") 			include (OUTPUT.'assignments.output.php');
			if ($section == "bos-mat") 					include (OUTPUT.'bos_mat.output.php');
			if ($section == "dropoff") 					include (OUTPUT.'dropoff.output.php');
			if ($section == "summary") 					include (OUTPUT.'participant_summary.output.php');
			if ($section == "particpant-entries") 	include (OUTPUT.'participant_entries_list.output.php');
			if ($section == "inventory") 				include (OUTPUT.'post_judge_inventory.output.php');
			if ($section == "pullsheets") 			include (OUTPUT.'pullsheets.output.php');
			if ($section == "results") 					include (OUTPUT.'results.output.php');
			if ($section == "sorting") 					include (OUTPUT.'sorting.output.php');
			if ($section == "staff") 						include (OUTPUT.'staff_points.output.php');
			if ($section == "table-cards") 			include (OUTPUT.'table_cards.output.php');
			if ($section == "notes") 						include (OUTPUT.'judge_notes.output.php');
		}

		if (isset($_SESSION['loginUsername'])) {
			if ($section == "styles") 					include (OUTPUT.'styles.output.php');
			if ($section == "shipping-label")		include (OUTPUT.'shipping_label.output.php');
		}
		
		// Scoresheets are available without logging in if the $token url var is present.
		if ($section == "evaluation")					include (EVALS.'scoresheet_output.eval.php');

		if (($section == "admin") && ($_SESSION['userLevel'] <= 1)) {
			include (LIB.'admin.lib.php');
			include (DB.'admin_common.db.php');
			include (DB.'judging_locations.db.php');
			include (DB.'stewarding.db.php');
			include (DB.'dropoff.db.php');
			include (DB.'contacts.db.php');
			if ($go == "entries") 							include (ADMIN.'entries.admin.php');
			if ($go == "participants") 					include (ADMIN.'participants.admin.php');
		}

		?>
    </div><!-- ./container -->
</body>
</html>
<?php if (($tb == "default") || ($tb == "scores") || ($tb == "none") || ($tb == "bos")) { ?>
<script type="text/javascript">
function selfPrint(){
    self.focus();
    self.print();
}
setTimeout('selfPrint()',3000);
html.push('');
</script>
<?php 	}
} // end if logged in
?>