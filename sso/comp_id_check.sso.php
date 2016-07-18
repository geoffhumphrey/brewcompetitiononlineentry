<?php

if (empty($_SESSION['competition_id'.$prefix_session])) {
	
	// Get comp id from admin user form 
	$_SESSION['competition_id'.$prefix_session] = $prefix_session; 
	$_SESSION['comp_id'] = $_POST['comp_id']; 
		
}

?>