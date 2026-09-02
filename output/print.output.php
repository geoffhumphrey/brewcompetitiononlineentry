<?php
/**
 * Module:      print.php
 * Description: This module is the delivery vehicle for pages that are meant to be print only (for output).
 *
 */

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) && ($token == "default")) {
    $redirect = "../../index.php?section=403";
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
	$filter_clean = preg_replace("/[^a-zA-Z0-9]+/", "", $filter);
	$db_conn->where("archiveSuffix", $filter);
	$row_archive_prefs = $db_conn->getOne($prefix."archive");
	$totalRows_archive_prefs = $db_conn->count;

	if ($totalRows_archive_prefs > 0) {
		$winner_method = $row_archive_prefs['archiveWinnerMethod'];
		$style_set = $row_archive_prefs['archiveStyleSet'];
		// $filter is a separate request parameter from whatever validated this archive's suffix
		// upstream, so it isn't guaranteed to point at tables that actually exist.
		$judging_scores_archive_table = $prefix."judging_scores_".$filter_clean;
		$brewing_archive_table = $prefix."brewing_".$filter_clean;
		$brewer_archive_table = $prefix."brewer_".$filter_clean;
		if (table_exists($judging_scores_archive_table)) $judging_scores_db_table = $judging_scores_archive_table;
		if (table_exists($brewing_archive_table)) $brewing_db_table = $brewing_archive_table;
		if (table_exists($brewer_archive_table)) $brewer_db_table = $brewer_archive_table;
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
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $css_url; ?>print.min.css">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/v4-shims.min.css" />

  	</head>
	<body>
    <div class="container-fluid">
    <?php

    // Email contacts IF no form
    if (($section == "contact") && ($token != "default")) {
    	
    	function hide_email($email) { 
    	    
    	    $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
    	    $key = str_shuffle($character_set); 
    	    $cipher_text = ''; 
    	    $id = 'e'.rand(1,999999999);
    	    for ($i=0;$i<strlen($email);$i+=1) {
    	        $cipher_text.= $key[strpos($character_set,$email[$i])];
    	    }
    	    
    	    $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
    	    $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
    	    $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
    	    $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")"; 
    	    $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
    	   
    	    return '<span id="'.$id.'">[email address obfuscated]</span>'.$script;
    	}

    	// Include process library for encryption functions
    	if (!function_exists('simpleDecrypt')) include (LIB.'common.lib.php');

    	$secretKey = base64_encode(bin2hex($password));
    	$nacl = base64_encode(bin2hex($server_root));
    	$id = simpleDecrypt($token, $secretKey, $nacl);

    	include (DB.'contacts.db.php');

    	$page_info = "<div style=\"padding: 25px; min-height:400px\">";
    	
    	if ($row_contact) {

    		$page_info .= sprintf("<h2><strong>%s &ndash; %s %s</strong><br><small>%s</small></h2>",$label_contact,h($row_contact['contactFirstName']),h($row_contact['contactLastName']),h($row_contact['contactPosition']));
    		$page_info .= sprintf("<p><strong>%s</strong></p>",hide_email($row_contact['contactEmail']));
    		$page_info .= sprintf("<p>%s</p>",$contact_text_011);
    		$page_info .= sprintf("<p><small><em>%s</em></small></p>",$contact_text_012);

    	}

    	else {
    		$page_info .= sprintf("<h2>%s</h2>",$label_error);
    		$page_info .= sprintf("<p>%s</p>",$contact_text_012);
    	}

    	$page_info .= "</div>";

    	
    	
    	

    	echo $page_info;

    }

		if ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] <= 1)) {
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

		if (($section == "admin") && (isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] <= 1)) {
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
</script>
<?php 	}
} // end if logged in
?>