<?php

//$registration_open = 1;
//$entry_window_open = 1;
//$judge_window_open = 1;
//$logged_in = TRUE;

// Button 1 is for use when the user is NOT logged in
// Button 2 is for use when the user IS logged in

$glance_pill_open_text = "<i class=\"fa fa-circle-check pe-2\"></i>".$label_open;
$glance_pill_closed_text = "<i class=\"fa fa-circle-exclamation pe-2\"></i>".$label_closed;
$glance_pill_status_text = "<i class=\"fa fa-circle-info pe-2\"></i>".$label_status;
$glance_open_color = "success";
$glance_closed_color = "danger";
$glance_disabled_color = "secondary";
$glance_status_color = "primary";


$entry_status_body_content = "<ul class=\"list-unstyled\">";
$entry_status_body_content .= "<li>";
$entry_status_body_content .= sprintf("<strong>%s</strong> &ndash; <span id=\"entry-total-count\" class=\"entry-total-count\">%s</span>",$label_total, $total_entries);
if ((isset($row_limits['prefsEntryLimit'])) && (!empty($row_limits['prefsEntryLimit']))) $entry_status_body_content .= sprintf(" / %s", $row_limits['prefsEntryLimit']);
$entry_status_body_content .= "</li>";
$entry_status_body_content .= "<li>";
$entry_status_body_content .= sprintf("<strong>%s</strong> &ndash; <span id=\"entry-paid-count\" class=\"entry-paid-count\">%s</span>", $label_paid, $total_paid, $row_limits['prefsEntryLimitPaid']);
if ((isset($row_limits['prefsEntryLimitPaid'])) && (!empty($row_limits['prefsEntryLimitPaid']))) $entry_status_body_content .= sprintf(" / %s", $row_limits['prefsEntryLimitPaid']);
$entry_status_body_content .= "</li>";
if ($entry_window_open == 0) $entry_status_body_content .= sprintf("<li class=\"small text-muted lh-1 pb-1 pt-1\">%s %s</li>", $label_opening, $entry_open_sidebar);
if ($entry_window_open == 1) {
	$entry_status_body_content .= sprintf("<li class=\"small text-muted lh-1 pt-1\">%s <span id=\"entry-paid-count-updated\" class=\"entry-paid-count-updated\">%s %s</span></li>", $label_updated, $current_date_display_short, $current_time);
	$entry_status_body_content .= "<li class=\"text-muted lh-1 pt-1\"><small class=\"count-two-minute-info\" id=\"count-two-minute-info\"></small></li>";
}
if ($entry_window_open == 2) $entry_status_body_content .= sprintf("<li class=\"small text-muted lh-1 pt-1\">%s %s</li>", $label_closed, $entry_closed_sidebar);
$entry_status_body_content .= "</ul>";


$glance_entry_status = array(
	"color" => $glance_status_color,
	"status" => $glance_pill_status_text,
	"body-content" => $entry_status_body_content,
	"button1" => array(),
	"button2" => array(),
	"button-color" => $glance_status_color
);

// Account Registration Card
if ($registration_open == 1) {

	$body_content = "<ul class=\"list-unstyled\"><li><strong>".$label_open."</strong> &ndash; ".$reg_open_sidebar."</li><li><strong>".$label_close."</strong> &ndash; ".$reg_closed_sidebar."</li></ul>";
	
	$button1 = array();
	$button2 = array();
	
	if ($logged_in) {
		$link = build_public_url("brewer","account","edit","default",$sef,$base_url,"default");
		$button1 = array("text" => $label_edit_account, "link" => $link);
	}

	else {
		$link = build_public_url("register","entrant","default","default",$sef,$base_url,"default");
		$button1 = array("text" => $label_register, "link" => $link);
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

	$body_content = "<ul class=\"list-unstyled\"><li><strong>".$label_open."</strong> &ndash; ".$reg_open_sidebar."</li><li><strong>".$label_close."</strong> &ndash; ".$reg_closed_sidebar."</li></ul>";
	
	$glance_account_reg = array(
		"color" => $glance_closed_color,
		"status" => $glance_pill_closed_text,
		"body-content" => $body_content,
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_disabled_color,
	);

}

// Entry Registration Card
if ($entry_window_open == 1) {

	$button1 = array();
	$button2 = array();
	if ($logged_in) {

		if ($section == "list") {

		}

		if ($section == "default") {
			$link = "";
			if ($remaining_entries > 0) $link = build_public_url("brew","entry","add","default",$sef,$base_url,"default");
			$button1 = array("text" => $label_add_entry, "link" => $link);
		}
			
	}

	else {
		$button1 = array("text" => $label_log_in_to_enter, "link" => "");
	}

	$glance_entry_reg = array(
		"color" => $glance_open_color,
		"status" => $glance_pill_open_text,
		"body-content" => "<ul class=\"list-unstyled\"><li><strong>".$label_open."</strong> &ndash; ".$entry_open_sidebar."</li><li><strong>".$label_close."</strong> &ndash; ".$entry_closed_sidebar."</li></ul>",
		"button1" => $button1,
		"button2" => $button2,
		"button-color" => $glance_open_color,
	);

}

else {
	
	$glance_entry_reg = array(
		"color" => $glance_closed_color,
		"status" => $glance_pill_closed_text,
		"body-content" => "<ul class=\"list-unstyled\"><li><strong>".$label_open."</strong> &ndash; ".$entry_open_sidebar."</li><li><strong>".$label_close."</strong> &ndash; ".$entry_closed_sidebar."</li></ul>",
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_disabled_color,
	);

}

// Drop-Off Card

if ($dropoff_window_open == 1) {
	
	$glance_drop_off = array(
		"color" => $glance_open_color,
		"status" => $glance_pill_open_text,
		"body-content" => "<ul class=\"list-unstyled\"><li><strong>".$label_open."</strong> &ndash; ".$dropoff_open_sidebar."</li><li><strong>".$label_close."</strong> &ndash; ".$dropoff_closed_sidebar."</li></ul>",
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_open_color,
	);

}

else {
	
	$glance_drop_off = array(
		"color" => $glance_closed_color,
		"status" => $glance_pill_closed_text,
		"body-content" => "<ul class=\"list-unstyled\"><li><strong>".$label_open."</strong> &ndash; ".$dropoff_open_sidebar."</li><li><strong>".$label_close."</strong> &ndash; ".$dropoff_closed_sidebar."</li></ul>",
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_disabled_color,
	);

}

// Shipping Card
if ($shipping_window_open == 1) {

	$glance_shipping = array(
		"color" => $glance_open_color,
		"status" => $glance_pill_open_text,
		"body-content" => "<ul class=\"list-unstyled\"><li><strong>".$label_open."</strong> &ndash; ".$shipping_open_sidebar."</li><li><strong>".$label_close."</strong> &ndash; ".$shipping_closed_sidebar."</li></ul>",
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_open_color,
	);
	
}

else {
	
	$glance_shipping = array(
		"color" => $glance_closed_color,
		"status" => $glance_pill_closed_text,
		"body-content" => "<ul class=\"list-unstyled\"><li><strong>".$label_open."</strong> &ndash; ".$shipping_open_sidebar."</li><li><strong>".$label_close."</strong> &ndash; ".$shipping_closed_sidebar."</li></ul>",
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_disabled_color,
	);

}

// Judge Registration Card
if ($judge_window_open == 1) {

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
		"body-content" => "<ul class=\"list-unstyled\"><li><strong>".$label_open."</strong> &ndash; ".$judge_open_sidebar."</li><li><strong>".$label_close."</strong> &ndash; ".$judge_closed_sidebar."</li></ul>",
		"button1" => $button1,
		"button2" => array(),
		"button-color" => $glance_open_color,
	);

}

else {

	$glance_judge_reg = array(
		"color" => $glance_closed_color,
		"status" => $glance_pill_closed_text,
		"body-content" => "<ul class=\"list-unstyled\"><li><strong>".$label_open."</strong> &ndash; ".$judge_open_sidebar."</li><li><strong>".$label_close."</strong> &ndash; ".$judge_closed_sidebar."</li></ul>",
		"button1" => array(),
		"button2" => array(),
		"button-color" => $glance_disabled_color,
	);

}

// Steward Registration Card
if ($judge_window_open == 1) {

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
		"body-content" => "<ul class=\"list-unstyled\"><li><strong>".$label_open."</strong> &ndash; ".$judge_open_sidebar."</li><li><strong>".$label_close."</strong> &ndash; ".$judge_closed_sidebar."</li></ul>",
		"button1" => $button1,
		"button2" => array(),
		"button-color" => $glance_open_color,
	);

}

else {

	$glance_steward_reg = array(
		"color" => $glance_closed_color,
		"status" => $glance_pill_closed_text,
		"body-content" => "<ul class=\"list-unstyled\"><li><strong>".$label_open."</strong> &ndash; ".$judge_open_sidebar."</li><li><strong>".$label_close."</strong> &ndash; ".$judge_closed_sidebar."</li></ul>",
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

		$body_content = "<ul class=\"list-unstyled\"><li><strong>".$label_start."</strong> &ndash; ".$judging_open_date."</li>";
		if (!empty($judging_close_date)) $body_content .= "<li><strong>".$label_end."</strong> &ndash; ".$judging_close_date."</li></ul>";

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

		$body_content = "<ul class=\"list-unstyled\"><li><strong>".$label_start."</strong> &ndash; ".$judging_open_date."</li>";
		if (!empty($judging_close_date)) $body_content .= "<li><strong>".$label_end."</strong> &ndash; ".$judging_close_date."</li></ul>";

		$glance_judging = array(
			"color" => "primary",
			"status" => "<i class=\"fa fa-sync fa-spin\" style=\"--fa-animation-duration: 3s;\"></i><span class=\"ps-2\">".$label_in_progress."</span>",
			"body-content" => $body_content,
			"button1" => array(),
			"button2" => array(),
			"button-color" => $glance_disabled_color,
		);

	}

	if ($judging_start == 2) {

		$body_content = "<ul class=\"list-unstyled\"><li><strong>".$label_start."</strong> &ndash; ".$judging_open_date."</li>";
		if (!empty($judging_close_date)) $body_content .= "<li><strong>".$label_end."</strong> &ndash; ".$judging_close_date."</li></ul>";

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

	$glance_cards = array();

	$glance_cards[$label_entries] = $glance_entry_status;
	$glance_cards[$label_entry_registration] = $glance_entry_reg;
	if (!empty($dropoff_open_sidebar)) $glance_cards[$label_entry_drop_off] = $glance_drop_off;
	if (!empty($shipping_open_sidebar)) $glance_cards[$label_entry_shipping] = $glance_shipping;
	$glance_cards[$label_judging] = $glance_judging;

	$row_class = "row row-cols-1 g-4 justify-content-center";

}

else {

	$glance_cards = array();

	$glance_cards[$label_entries] = $glance_entry_status;
	if (!empty($dropoff_open_sidebar)) $glance_cards[$label_entry_drop_off] = $glance_drop_off;
	if (!empty($shipping_open_sidebar)) $glance_cards[$label_entry_shipping] = $glance_shipping;
	$glance_cards[$label_judging] = $glance_judging;
	$glance_cards[$label_entry_registration] = $glance_entry_reg;
	$glance_cards[$label_account_registration] = $glance_account_reg;
	$glance_cards[$label_judge_reg] = $glance_judge_reg;
	$glance_cards[$label_steward_reg] = $glance_steward_reg;
	$glance_cards[$label_awards] = array();

	$row_class = "row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 justify-content-center mt-1";

}

?>

<div class="<?php echo $row_class; ?> d-print-none">

<?php
	
	foreach ($glance_cards as $key => $card_state) {  
		
		if (!empty($card_state)) { 

			$button1 = "";
			$button2 = "";

			if ((!empty($card_state['button1'])) && (!empty($card_state['button1']['link']))) $button1 = sprintf("<div class=\"d-grid\"><a href=\"%s\" class=\"btn btn-%s\">%s</a></div>",$card_state['button1']['link'],$card_state['button-color'],$card_state['button1']['text']);
			if ((!empty($card_state['button1'])) && (empty($card_state['button1']['link']))) $button1 = sprintf("<div class=\"d-grid\"><button class=\"btn btn-%s disabled\">%s</button></div>",$card_state['button-color'],$card_state['button1']['text']);

			if ((!empty($card_state['button2'])) && (!empty($card_state['button2']['link']))) $button2 = sprintf("<div class=\"d-grid\"><a href=\"%s\" class=\"btn btn-%s\">%s</a></div>",$card_state['button1']['link'],$card_state['button-color'],$card_state['button1']['text']);
			if ((!empty($card_state['button2'])) && (empty($card_state['button2']['link']))) $button2 = sprintf("<div class=\"d-grid\"><button class=\"btn btn-%s disabled\">%s</button></div>",$card_state['button-color'],$card_state['button2']['text']);

?>
	<div class="col">
		<div class="card h-100 glance-card-bg <?php if ($section == "default") echo "reveal-element"; ?>">
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