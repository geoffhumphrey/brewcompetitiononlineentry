<?php

if (isset($_SERVER['HTTP_REFERER'])) {
	
	$url = str_replace("www.","",$_SERVER['SERVER_NAME']);
	
	$from_name = $_SESSION['contestName']." Competition Server";
	$from_email = "noreply@".$url;
	
	$headers = "";
	$message = "";
	
	if ($filter == "test-email") {
		
		$query_forgot = "SELECT * FROM $users_db_table WHERE id = '$id'";
		$forgot = mysqli_query($connection,$query_forgot) or die (mysqli_error($connection));
		$row_forgot = mysqli_fetch_assoc($forgot);
		$totalRows_forgot = mysqli_num_rows($forgot);
		
		$query_brewer = "SELECT brewerLastName,brewerFirstName FROM $brewer_db_table WHERE uid = '$id'";
		$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
		$row_brewer = mysqli_fetch_assoc($brewer);
		$totalRows_brewer = mysqli_num_rows($brewer);
	
		$first_name = ucwords(strtolower($row_brewer['brewerFirstName']));
		$last_name = ucwords(strtolower($row_brewer['brewerLastName']));
		
		$to_recipient = $first_name." ".$last_name;
		$to_email = strtolower($row_forgot['user_name']);
		$subject = $_SESSION['contestName']." - System Generated Email Test";
		
		$message = "<html>" . "\r\n";
		$message .= "<body>" . "\r\n";
		$message .= "<p>".$first_name.",</p>";
		$message .= "<p>A request was made to verify ability to send system-generated emails from the  ".$_SESSION['contestName']." competition website. If you are reading this, the test was <strong>successful</strong>!</p>";
		$message .= "<p>Thank you,</p><p>The  ".$_SESSION['contestName']." Competition Server</p>";
		$message .= "<p><small>".$paypal_response_text_003."</small></p>";
		$message .= "</body>" . "\r\n";
		$message .= "</html>";
		
		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "To: ".$to_recipient. " <".$to_email.">, " . "\r\n";
		$headers .= "From: ".$from_name." <".$from_email.">" . "\r\n";
		
		mail($to_email, $subject, $message, $headers);
		
		header(sprintf("Location: %s", $base_url."index.php?section=admin&go=preferences&msg=32"));
		
	}
	
	if ($filter == "table-assignments") {
		
		$query_brewer = "SELECT a.id,a.uid,a.brewerFirstName,a.brewerLastName,a.brewerJudgeID,a.brewerJudgeWaiver,a.brewerEmail,b.uid,b.staff_judge,b.staff_steward FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid AND (b.staff_steward='1' OR b.staff_judge='1') ORDER BY brewerLastName ASC"; 
		$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
		$row_brewer = mysqli_fetch_assoc($brewer);
		$totalRows_brewer = mysqli_num_rows($brewer);
		
		$query_organizer = "SELECT a.brewerFirstName,a.brewerLastName FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid AND staff_organizer='1'";
		$organizer = mysqli_query($connection,$query_organizer) or die (mysqli_error($connection));
		$row_organizer = mysqli_fetch_assoc($organizer);
		$totalRows_organizer = mysqli_num_rows($organizer);
		
		
		do {
			
			$message = "";
			$table_assignments = "";
		
			if ($row_brewer['staff_judge'] == 1) 		$table_assignments = table_assignments($row_brewer['uid'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);
			if ($row_brewer['staff_steward'] == 1) 	$table_assignments = table_assignments($row_brewer['uid'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);
		
			if (!empty($table_assignments)) {
				
				$first_name = ucwords(strtolower($row_brewer['brewerFirstName']));
				$last_name = ucwords(strtolower($row_brewer['brewerLastName']));
				
				$to_recipient = $first_name." ".$last_name;
				$to_email = strtolower($row_brewer['brewerEmail']);
				if ($row_brewer['staff_judge'] == 1) $subject = $_SESSION['contestName']." - Your Judging Assignments";
				if ($row_brewer['staff_steward'] == 1) $subject = $_SESSION['contestName']." - Your Stewarding Assignments";
			
				$message = "<html>" . "\r\n";
				$message .= "<body>" . "\r\n";
				if (isset($_SESSION['contestLogo'])) $message .= "<p align='center'><img src='".$base_url."user_images/".$_SESSION['contestLogo']."' height='150'></p>";
				$message .= "<p>".$first_name.",</p>";
				$message .= "<p>Thank you for volunteering to be a ";
				if ($row_brewer['staff_judge'] == 1) $message .= "judge ";
				if ($row_brewer['staff_steward'] == 1) $message .= "steward ";
				$message .= "in the ".$_SESSION['contestName'].". Your assignment(s) are detailed below.</p>";
				
				$message .= "<table cellpadding='5' border='0' cellspacing='0'>";
				$message .= "<thead>";
				$message .= "<tr>";
				$message .= "<th align='left'>Location</th>";
				$message .= "<th align='left'>Date/Time</th>";
				$message .= "<th align='left'>Table</th>";
				$message .= "</tr>";
				$message .= "</thead>";
				$message .= "<tbody>";
				$message .= $table_assignments;
				$message .= "</tbody>";
				$message .= "</table>";
				$message .= sprintf("<p>If you wish to change your availabilty and/or withdraw your role, <a href=\"%s\">please contact</a> the appropriate competition official.</p>",$base_url."index.php?section=contact");
				$message .= "<p>Cheers,</p>";
				$message .= "<p>".$row_organizer['brewerFirstName']." ".$row_organizer['brewerLastName'].", Organizer";
				$message .= sprintf("<br><a href=\"%s\">%s</a></p>",$base_url,$_SESSION['contestName']);
				$message .= "<p><small>".$paypal_response_text_003."</small></p>";
				$message .= "</body>" . "\r\n";
				$message .= "</html>";
				
				$headers  = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
				$headers .= "To: ".$to_recipient. " <".$to_email.">, " . "\r\n";
				$headers .= "From: ".$from_name." <".$from_email.">" . "\r\n";
				
				mail($to_email, $subject, $message, $headers);
				
				//echo $message."<br><br>";
				//echo $headers."<br><br><br><br>";
			
			}
		
		} while ($row_brewer = mysqli_fetch_assoc($brewer));
		
	}

}
else { 
	header(sprintf("Location: %s", $base_url."index.php?msg=98"));
	exit;
}
?>