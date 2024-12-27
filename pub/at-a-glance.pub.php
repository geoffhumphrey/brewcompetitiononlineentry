<?php

//$registration_open = 1;
//$entry_window_open = 1;
//$judge_window_open = 1;
//$logged_in = TRUE;

// Button 1 is for use when the user is NOT logged in
// Button 2 is for use when the user IS logged in

$glance_pill_open_text = "<i class=\"fa fa-circle-check pe-2\"></i>".$label_open;
$glance_pill_closed_text = "<i class=\"fa fa-circle-exclamation pe-2\"></i>".$label_closed;
$glance_open_color = "success";
$glance_closed_color = "danger";
$glance_disabled_color = "secondary";

if ($registration_open == 1) {

	$body_content = "<ul class=\"list-unstyled\"><li>".$label_open." - ".$reg_open_sidebar."</li><li>".$label_close." - ".$reg_closed_sidebar."</li></ul>";
	
	$button1 = array();
	$button2 = array();
	
	if ($logged_in) {
		$link = build_public_url("brewer","account","edit","default",$sef,$base_url,"default");
		$button1 = array("text" => $label_edit_account, "link" => $link);
	}

	else {
		$link = build_public_url("register","entrant","default","default",$sef,$base_url,"default");
		$button1 = array("text" => $label_create_account, "link" => $link);
	}

	$glance_account_reg = array(
		"color" => $glance_open_color,
		"status" => $glance_pill_open_text,
		"body-content" => $body_content,
		"button1" => $button1,
		"button2" => array(),
		"button-color" => $glance_open_color,
	);

}

else {

	$body_content = "<ul class=\"list-unstyled\"><li>".$label_open." - ".$reg_open_sidebar."</li><li>".$label_close." - ".$reg_closed_sidebar."</li></ul>";
	
	$glance_account_reg = array(
		"color" => $glance_closed_color,
		"status" => $glance_pill_closed_text,
		"body-content" => $body_content,
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_disabled_color,
	);

}

if ($entry_window_open == 1) {

	$button1 = array();
	$button2 = array();
	if ($logged_in) {
		$button1 = array("text" => $label_add_entry, "link" => "https://brewingcompetitions.com");
	}

	else {
		$button1 = array("text" => $label_log_in_to_enter, "link" => "");
	}

	$glance_entry_reg = array(
		"color" => $glance_open_color,
		"status" => $glance_pill_open_text,
		"body-content" => "<ul class=\"list-unstyled\"><li>".$label_open." - ".$entry_open_sidebar."</li><li>".$label_close." - ".$entry_closed_sidebar."</li></ul>",
		"button1" => $button1,
		"button2" => $button2,
		"button-color" => $glance_open_color,
	);

}

else {
	
	$glance_entry_reg = array(
		"color" => $glance_closed_color,
		"status" => $glance_pill_closed_text,
		"body-content" => "<ul class=\"list-unstyled\"><li>".$label_open." - ".$entry_open_sidebar."</li><li>".$label_close." - ".$entry_closed_sidebar."</li></ul>",
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_disabled_color,
	);

}

if ($dropoff_window_open == 1) {
	
	$glance_drop_off = array(
		"color" => $glance_open_color,
		"status" => $glance_pill_open_text,
		"body-content" => "<ul class=\"list-unstyled\"><li>".$label_open." - ".$dropoff_open_sidebar."</li><li>".$label_close." - ".$dropoff_closed_sidebar."</li></ul>",
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_open_color,
	);

}

else {
	
	$glance_drop_off = array(
		"color" => $glance_closed_color,
		"status" => $glance_pill_closed_text,
		"body-content" => "<ul class=\"list-unstyled\"><li>".$label_open." - ".$dropoff_open_sidebar."</li><li>".$label_close." - ".$dropoff_closed_sidebar."</li></ul>",
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_disabled_color,
	);

}

if ($shipping_window_open == 1) {

	$glance_shipping = array(
		"color" => $glance_open_color,
		"status" => $glance_pill_open_text,
		"body-content" => "<ul class=\"list-unstyled\"><li>".$label_open." - ".$shipping_open_sidebar."</li><li>".$label_close." - ".$shipping_closed_sidebar."</li></ul>",
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_open_color,
	);
	
}

else {
	
	$glance_shipping = array(
		"color" => $glance_closed_color,
		"status" => $glance_pill_closed_text,
		"body-content" => "<ul class=\"list-unstyled\"><li>".$label_open." - ".$shipping_open_sidebar."</li><li>".$label_close." - ".$shipping_closed_sidebar."</li></ul>",
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_disabled_color,
	);

}

if ($judge_window_open== 1) {

	$button1 = array();
	$button2 = array();
	if ($logged_in) {
		$link = build_public_url("brewer","account","edit","default",$sef,$base_url,"default");
		$button1 = array("text" => $label_edit_account, "link" => $link);
	}

	else {
		$link = build_public_url("register","judge","default","default",$sef,$base_url,"default");
		$button1 = array("text" => $label_register_as_judge, "link" => $link);
	}

	$glance_judge_reg = array(
		"color" => $glance_open_color,
		"status" => $glance_pill_open_text,
		"body-content" => "<ul class=\"list-unstyled\"><li>".$label_open." - ".$judge_open_sidebar."</li><li>".$label_close." - ".$judge_closed_sidebar."</li></ul>",
		"button1" => $button1,
		"button2" => array(),
		"button-color" => $glance_open_color,
	);

}

else {

	$glance_judge_reg = array(
		"color" => $glance_closed_color,
		"status" => $glance_pill_closed_text,
		"body-content" => "<ul class=\"list-unstyled\"><li>".$label_open." - ".$judge_open_sidebar."</li><li>".$label_close." - ".$judge_closed_sidebar."</li></ul>",
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_disabled_color,
	);

}

if ($judge_window_open== 1) {

	$button1 = array();
	$button2 = array();
	if ($logged_in) {
		$link = build_public_url("brewer","account","edit","default",$sef,$base_url,"default");
		$button1 = array("text" => $label_edit_account, "link" => $link);
	}

	else {
		$link = build_public_url("register","steward","default","default",$sef,$base_url,"default");
		$button1 = array("text" => $label_register_as_steward, "link" => $link);
	}
	
	$glance_steward_reg = array(
		"color" => $glance_open_color,
		"status" => $glance_pill_open_text,
		"body-content" => "<ul class=\"list-unstyled\"><li>".$label_open." - ".$judge_open_sidebar."</li><li>".$label_close." - ".$judge_closed_sidebar."</li></ul>",
		"button1" => $button1,
		"button2" => array(),
		"button-color" => $glance_open_color,
	);

}

else {

	$glance_steward_reg = array(
		"color" => $glance_closed_color,
		"status" => $glance_pill_closed_text,
		"body-content" => "<ul class=\"list-unstyled\"><li>".$label_open." - ".$judge_open_sidebar."</li><li>".$label_close." - ".$judge_closed_sidebar."</li></ul>",
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_disabled_color,
	);

}

// Judging
// 0 = Not Started
// 1 = In Progress
// 2 = Completed

$judging_start = 0;
$glance_judging = "";

if (!empty($date_arr)) {

	if (time () > $first_judging_date) {

		if (empty($last_judging_date)) {

			// Check to see if there's an award date/time
			// If the current time is before the listed award time, denote as in progress
			if ((isset($_SESSION['contestAwardsLocDate'])) && (!empty($_SESSION['contestAwardsLocDate']))) {
				if (time() < $_SESSION['contestAwardsLocDate']) $judging_start = 1;
				else $judging_start = 2;
			}

			// If not, take the first judging date and add 6 hours
			// Should be long enough for judging a single session
			else {
				if (time() < ($first_judging_date + 21600)) $judging_start = 1;
				else $judging_start = 2;
			}

		}

		else {

			if (time() < $last_judging_date) $judging_start = 1;
			
			else {
				if (time() < ($last_judging_date + 21600)) $judging_start = 1;
				else $judging_start = 2;
			}

		}

	}

	$judging_open_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $first_judging_date, $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time");
	if (!empty($last_judging_date)) $judging_close_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $last_judging_date, $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time");
	else $judging_close_date = "";

	if ($judging_start == 0) {

		$body_content = "<ul class=\"list-unstyled\"><li>".$label_start." - ".$judging_open_date."</li>";
		if (!empty($judging_close_date)) $body_content .= "<li>".$label_end." - ".$judging_close_date."</li></ul>";

		$glance_judging = array(
			"color" => $glance_disabled_color,
			"status" => "<i class=\"fa fa-clock pe-2\"></i>".$label_not_started,
			"body-content" => $body_content,
			"button1" => array(),
			"button2" => array(),
			"button-color" => $glance_disabled_color,
		);

	}

	if ($judging_start == 1) {

		$body_content = "<ul class=\"list-unstyled\"><li>".$label_start." - ".$judging_open_date."</li>";
		if (!empty($judging_close_date)) $body_content .= "<li>".$label_end." - ".$judging_close_date."</li></ul>";

		$glance_judging = array(
			"color" => "primary",
			"status" => "<i class=\"fa fa-spinner fa-spin-pulse\">></i><span class=\"ps-2\">".$label_in_progress."</span>",
			"body-content" => $body_content,
			"button1" => array(),
			"button2" => array(),
			"button-color" => $glance_disabled_color,
		);

	}

	if ($judging_start == 2) {

		$body_content = "<ul class=\"list-unstyled\"><li>".$label_start." - ".$judging_open_date."</li>";
		if (!empty($judging_close_date)) $body_content .= "<li>".$label_end." - ".$judging_close_date."</li></ul>";

		$glance_judging = array(
			"color" => "success",
			"status" => "<i class=\"fa fa-circle-check pe-2\"></i>".$label_concluded,
			"body-content" => $body_content,
			"button1" => array(),
			"button2" => array(),
			"button-color" => $glance_disabled_color,
		);

	}

}

if ($section == "list") {

	$glance_cards = array(
		$label_entry_registration => $glance_entry_reg,
		$label_entry_drop_off => $glance_drop_off,
		$label_entry_shipping => $glance_shipping,
		$label_judging => $glance_judging,
	);

	$row_class = "row row-cols-1 g-4 justify-content-center";
}

else {

	$glance_cards = array(
		$label_account_registration => $glance_account_reg,
		$label_judge_reg => $glance_judge_reg,
		$label_steward_reg => $glance_steward_reg,
		$label_entry_registration => $glance_entry_reg,
		$label_entry_drop_off => $glance_drop_off,
		$label_entry_shipping => $glance_shipping,
		$label_judging => $glance_judging,
		$label_awards => array(),
	);

	$row_class = "row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 justify-content-center mt-1";
}


?>

<div class="<?php echo $row_class; ?>">

<?php
	
	foreach ($glance_cards as $key => $card_state) {  
		
		if (!empty($card_state)) { 

			$button1 = "";
			$button2 = "";

			if ($logged_in) {
				
				if ((!empty($card_state['button2'])) && (!empty($card_state['button2']['link']))) $button2 = sprintf("<div class=\"d-grid\"><a href=\"%s\" class=\"btn btn-%s\">%s</a></div>",$card_state['button2']['link'],$card_state['button-color'],$card_state['button2']['text']);
				if ((!empty($card_state['button2'])) && (empty($card_state['button2']['link']))) $button2 = sprintf("<div class=\"d-grid\"><button class=\"btn btn-%s disabled\">%s</button></div>",$card_state['button-color'],$card_state['button2']['text']);
				
			} else {

				if ((!empty($card_state['button1'])) && (!empty($card_state['button1']['link']))) $button1 = sprintf("<div class=\"d-grid\"><a href=\"%s\" class=\"btn btn-%s\">%s</a></div>",$card_state['button1']['link'],$card_state['button-color'],$card_state['button1']['text']);
				if ((!empty($card_state['button1'])) && (empty($card_state['button1']['link']))) $button1 = sprintf("<div class=\"d-grid\"><button class=\"btn btn-%s disabled\">%s</button></div>",$card_state['button-color'],$card_state['button1']['text']);
				
			}

?>
	<div class="col">
		<div class="card h-100 glance-card-bg">
			<div class="card-body glance-card-body">
				<h5 class="card-title pt-2 pb-2 glance-header text-<?php echo $card_state['color']; ?>-glance-header"><?php echo $key ?></h5>
				<div class="position-absolute top-0 start-50 translate-middle badge bg-<?php echo $card_state['color']; ?>-glance-pill dark rounded-pill glance-status-pill"><?php echo $card_state['status']; ?></div>
				<p class="card-text glance-card-text"><small><?php echo $card_state['body-content']; ?></small></p>
				<?php echo $button1; echo $button2; ?>
			</div>
		</div>
	</div>
<?php 
		} 
	}
?>

</div>