<?php

/*
Checked Single
2016-06-06

*/

function clean_up_text($text) {
	$r = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
	$r = preg_replace( "/\r|\n/", "", $r);
	return $r;
}

$styles_entry_text = array(
    "07-C" => $styles_entry_text_07C,
    "09-A" => $styles_entry_text_09A,
    "10-C" => $styles_entry_text_10C,
    "21-B" => $styles_entry_text_21B,
    "23-F" => $styles_entry_text_23F,
    "24-C" => $styles_entry_text_24C,
    "25-B" => $styles_entry_text_27A,
    "28-A" => $styles_entry_text_28A,
    "28-B" => $styles_entry_text_28C,
    "29-A" => $styles_entry_text_29A,
    "29-B" => $styles_entry_text_29B,
    "29-C" => $styles_entry_text_29C,
    "30-A" => $styles_entry_text_30A,
    "30-B" => $styles_entry_text_30B,
    "30-C" => $styles_entry_text_30C,
    "31-A" => $styles_entry_text_30A,
    "31-B" => $styles_entry_text_31B,
    "32-A" => $styles_entry_text_32A,
    "32-B" => $styles_entry_text_32B,
    "33-A" => $styles_entry_text_33A,
    "33-B" => $styles_entry_text_33B,
    "34-A" => $styles_entry_text_34A,
    "34-B" => $styles_entry_text_34B,
    "34-C" => $styles_entry_text_34C,
    "M1-A" => $styles_entry_text_M1A,
    "M1-B" => $styles_entry_text_M1B,
    "M1-C" => $styles_entry_text_M1C,
    "M2-A" => $styles_entry_text_M2A,
    "M2-B" => $styles_entry_text_M2B,
    "M2-C" => $styles_entry_text_M2C,
    "M2-D" => $styles_entry_text_M2D,
    "M2-E" => $styles_entry_text_M2E,
    "M3-A" => $styles_entry_text_M3A,
    "M3-B" => $styles_entry_text_M3B,
    "M4-A" => $styles_entry_text_M4A,
    "M4-B" => $styles_entry_text_M4B,
    "M4-C" => $styles_entry_text_M4C,
    "C1-E" => $styles_entry_text_C1E,
    "C2-A" => $styles_entry_text_C2A,
    "C2-B" => $styles_entry_text_C2B,
    "C2-C" => $styles_entry_text_C2C,
    "C2-D" => $styles_entry_text_C2D,
    "C2-E" => $styles_entry_text_C2E,
    "C2-F" => $styles_entry_text_C2F,
    "21-B1" => $styles_entry_text_21X,
    "21-B2" => $styles_entry_text_21X,
    "21-B3" => $styles_entry_text_21X,
    "21-B4" => $styles_entry_text_21X,
    "21-B5" => $styles_entry_text_21X,
    "21-B6" => $styles_entry_text_21X,
    "21-B7" => $styles_entry_text_21X,
    "PR-X4" => $styles_entry_text_PRX4,
);

if (($section == "admin") && ($go == "styles") && ($action != "default")) {

	if ($styleSet != "BA") {
		$specialty_ipa_subs = array("21-B1","21-B2","21-B3","21-B4","21-B5","21-B6");
		$historical_subs = array("27-A1","27-A2","27-A3","27-A4","27-A5","27-A6","27-A7","27-A8","27-A9");
	}

	// print_r($custom_entry_information); exit;
?>
<!-- Load Show/Hide Configuration -->
<script type="text/javascript">
$(document).ready(function() {

	$("#mead-cider").hide("fast");
	$("#mead").hide("fast");
	$("#brewStyleEntry").hide("fast");
	$("#brewStyleEntryTextArea").prop("required", false);
	$("input[name='brewStyleCarb']").prop("required", false);
	$("input[name='brewStyleSweet']").prop("required", false);
	$("input[name='brewStyleStrength']").prop("required", false);

	$("#brewStyleType").change(function() {

		$("#mead-cider").hide("fast");
		$("#mead").hide("fast");
		/*
		$("#brewStyleEntryTextArea").prop("required", false);
		$("input[name='brewStyleCarb']").prop("required", false);
		$("input[name='brewStyleSweet']").prop("required", false);
		$("input[name='brewStyleStrength']").prop("required", false);
		$("input[name='brewStyleCarb']").removeAttr('checked');
		$("input[name='brewStyleSweet']").removeAttr('checked');
		$("input[name='brewStyleStrength']").removeAttr('checked');
		$("#brewStyleEntryTextArea").val("");
		$("#brewStyleInfoTextArea").val("");
		*/

		if ($("#brewStyleType").val() == "3") {
			$("#mead-cider").show("fast");
			$("#mead").show("fast");
			$("input[name='brewStyleCarb']").prop("required", true);
			$("input[name='brewStyleSweet']").prop("required", true);
			$("input[name='brewStyleStrength']").prop("required", true);
		}

		else if ($("#brewStyleType").val() == "2") {
			$("#mead").hide("fast");
			$("#mead-cider").show("fast");
			$("input[name='brewStyleCarb']").prop("required", true);
			$("input[name='brewStyleSweet']").prop("required", true);
		}

	});

	$('input[name=brewStyleReqSpec]').click(function() {
       if($(this).attr('id') == 'brewStyleReqSpec_0') {
            $("#brewStyleEntry").show("fast");
		   	$("#brewStyleEntryTextArea").prop("required", true);
       }

       else {
         	$("#brewStyleEntry").hide("fast");
		    $("#brewStyleEntryTextArea").prop("required", false);
		    $("#brewStyleEntryTextArea").val("");
       }
   	});

	<?php if (($action == "edit") && ($row_styles['brewStyleReqSpec'] == 1)) { ?>
	$("#brewStyleEntry").show("fast");
	$("textarea[name='brewStyleEntry']").prop("required", true);
	<?php } ?>

	<?php if (($action == "edit") && ($row_styles['brewStyleType'] == 2)) { ?>
	$("#mead-cider").show("fast");
	$("input[name='brewStyleCarb']").prop("required", true);
	$("input[name='brewStyleSweet']").prop("required", true);
	<?php } ?>

	<?php if (($action == "edit") && ($row_styles['brewStyleType'] == 3)) { ?>
	$("#mead-cider").show("fast");
	$("#mead").show("fast");
	$("input[name='brewStyleCarb']").prop("required", true);
	$("input[name='brewStyleSweet']").prop("required", true);
	$("input[name='brewStyleStrength']").prop("required", true);
	<?php } ?>


});

</script>
<?php } ?>


<?php if ($section == "brew") { ?>
<!-- Load Show/Hide Configuration -->
<script type="text/javascript">//<![CDATA[

$(document).ready(function() {
	 <?php if ($action == "add") { ?>
		$("#special").hide("fast");
		$("#carbonation").hide("fast");
		$("#sweetness").hide("fast");
		$("#strength").hide("fast");
		$("#specialInfo").hide("fast");
		$("#strengthIPA").hide("fast");
		$("#strengthSaison").hide("fast");
		$("#darkLightColor").hide("fast");
		$("#sweetnessLambic").hide("fast");
		$("#carbLambic").hide("fast");
		$("#BDGColor").hide("fast");
		$("#optional").hide("fast");
		$("#carbonation").removeClass("has-error");
		$("#sweetness").removeClass("has-error");
		$("#strength").removeClass("has-error");
		$("#strengthIPA").removeClass("has-error");
		$("#strengthSaison").removeClass("has-error");
		$("#darkLightColor").removeClass("has-error");
		$("#sweetnessLambic").removeClass("has-error");
		$("#carbLambic").removeClass("has-error");
		$("#BDGColor").removeClass("has-error");
		$("#brewInfo").prop("required", false);
		$("#optional").prop("required", false);
		$("input[name='brewMead1']").prop("required", false);
		$("input[name='brewMead2']").prop("required", false);
		$("input[name='brewMead3']").prop("required", false);
		$("input[name='strengthIPA']").prop("required", false);
		$("input[name='strengthSaison']").prop("required", false);
		$("input[name='darkLightColor']").prop("required", false);
		$("input[name='sweetnessLambic']").prop("required", false);
		$("input[name='carbLambic']").prop("required", false);
		$("input[name='BDGColor']").prop("required", false);
	<?php } // end if ($action == "add") ?>

	$("#type").change(function() {

	 	$("#special").hide("fast");
		$("#carbonation").hide("fast");
		$("#sweetness").hide("fast");
		$("#strength").hide("fast");
		$("#specialInfo").hide("fast");
		$("#strengthIPA").hide("fast");
		$("#strengthSaison").hide("fast");
		$("#darkLightColor").hide("fast");
		$("#BDGColor").hide("fast");
		$("#optional").hide("fast");
		$("#sweetnessLambic").hide("fast");
		$("#carbLambic").hide("fast");
		$("#carbonation").removeClass("has-error");
		$("#sweetness").removeClass("has-error");
		$("#strength").removeClass("has-error");
		$("#strengthIPA").removeClass("has-error");
		$("#strengthSaison").removeClass("has-error");
		$("#darkLightColor").removeClass("has-error");
		$("#sweetnessLambic").removeClass("has-error");
		$("#carbLambic").removeClass("has-error");
		$("#BDGColor").removeClass("has-error");
		$("#brewInfo").prop("required", false);
		$("#optional").prop("required", false);
		$("input[name='brewMead1']").prop("required", false);
		$("input[name='brewMead2']").prop("required", false);
		$("input[name='brewMead3']").prop("required", false);
		$("input[name='strengthIPA']").prop("required", false);
		$("input[name='strengthSaison']").prop("required", false);
		$("input[name='darkLightColor']").prop("required", false);
		$("input[name='sweetnessLambic']").prop("required", false);
		$("input[name='carbLambic']").prop("required", false);
		$("input[name='BDGColor']").prop("required", false);

		if (
			$("#type").val() == "00-A"){
			<?php if ($action == "edit") { ?>
			$("#brewInfo").val("");
			$("#optional").prop("required", false);
			$("#brewComments").val("");
			$("input[name='brewMead1']").removeAttr('checked');
			$("input[name='brewMead2']").removeAttr('checked');
			$("input[name='brewMead3']").removeAttr('checked');
			$("input[name='strengthIPA']").removeAttr('checked');
			$("input[name='strengthSaison']").removeAttr('checked');
			$("input[name='darkLightColor']").removeAttr('checked');
			$("input[name='sweetnessLambic']").removeAttr('checked');
			$("input[name='carbLambic']").removeAttr('checked');
			$("input[name='BDGColor']").removeAttr('checked');
			<?php } ?>
			$("#special").hide("fast");
			$("#carbonation").hide("fast");
			$("#sweetness").hide("fast");
			$("#strength").hide("fast");
			$("#specialInfo").hide("fast");
			$("#strengthIPA").hide("fast");
			$("#strengthSaison").hide("fast");
			$("#darkLightColor").hide("fast");
			$("#sweetnessLambic").hide("fast");
			$("#carbLambic").hide("fast");
			$("#BDGColor").hide("fast");
			$("#optional").hide("fast");
			$("#carbonation").removeClass("has-error");
			$("#sweetness").removeClass("has-error");
			$("#strength").removeClass("has-error");
			$("#strengthIPA").removeClass("has-error");
			$("#strengthSaison").removeClass("has-error");
			$("#darkLightColor").removeClass("has-error");
			$("#sweetnessLambic").removeClass("has-error");
			$("#carbLambic").removeClass("has-error");
			$("#BDGColor").removeClass("has-error");
			$("#brewInfo").prop("required", false);
			$("input[name='brewMead1']").prop("required", false);
			$("input[name='brewMead2']").prop("required", false);
			$("input[name='brewMead3']").prop("required", false);
			$("input[name='strengthIPA']").prop("required", false);
			$("input[name='strengthSaison']").prop("required", false);
			$("input[name='darkLightColor']").prop("required", false);
			$("input[name='sweetnessLambic']").prop("required", false);
			$("input[name='carbLambic']").prop("required", false);
			$("input[name='BDGColor']").prop("required", false);
		}

		// Beer styles that require special ingredients
		<?php
		if (is_array($special_beer_info)) {
		foreach ($special_beer_info as $key => $value) { 
			if (array_key_exists($key, $styles_entry_text)) $entry_text = $styles_entry_text["$key"];
			else $entry_text = $value;
			?>
		else if (
			$("#type").val() == "<?php echo ltrim($key,"0"); ?>"){ // Special Beer

				<?php if ($action == "edit") { ?>
				$("#brewInfo").val("");
				$("#brewComments").val("");
				$("input[name='brewMead1']").removeAttr('checked');
				$("input[name='brewMead2']").removeAttr('checked');
				$("input[name='brewMead3']").removeAttr('checked');
				$("input[name='strengthIPA']").removeAttr('checked');
				$("input[name='strengthSaison']").removeAttr('checked');
				$("input[name='darkLightColor']").removeAttr('checked');
				$("input[name='sweetnessLambic']").removeAttr('checked');
				$("input[name='carbLambic']").removeAttr('checked');
				$("input[name='BDGColor']").removeAttr('checked');
				<?php } ?>
				$("#special").hide("fast");
				$("#carbonation").hide("fast");
				$("#sweetness").hide("fast");
				$("#strength").hide("fast");
				$("#strengthIPA").hide("fast");
				$("#strengthSaison").hide("fast");
				$("#darkLightColor").hide("fast");
				$("#BDGColor").hide("fast");
				$("#optional").hide("fast");
				$("#sweetnessLambic").hide("fast");
				$("#carbLambic").hide("fast");
				$("#special").removeClass("has-error");
				$("#carbonation").removeClass("has-error");
				$("#sweetness").removeClass("has-error");
				$("#strength").removeClass("has-error");
				$("#strengthIPA").removeClass("has-error");
				$("#strengthSaison").removeClass("has-error");
				$("#darkLightColor").removeClass("has-error");
				$("#sweetnessLambic").removeClass("has-error");
				$("#carbLambic").removeClass("has-error");
				$("#BDGColor").removeClass("has-error");
				$("#brewInfo").prop("required", false);
				$("input[name='brewMead1']").prop("required", false);
				$("input[name='brewMead2']").prop("required", false);
				$("input[name='brewMead3']").prop("required", false);
				$("input[name='strengthIPA']").prop("required", false);
				$("input[name='strengthSaison']").prop("required", false);
				$("input[name='darkLightColor']").prop("required", false);
				$("input[name='sweetnessLambic']").prop("required", false);
				$("input[name='carbLambic']").prop("required", false);
				$("input[name='BDGColor']").prop("required", false);
				$("#specialInfo").show("fast");
				$("#specialInfoText").html("<?php echo clean_up_text($entry_text); ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'><?php echo $label_this_style; ?></a>");
				<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($key,$optional_info_styles))) { ?>
				$("#optional").show("fast");
				
				<?php } ?>
			<?php if ($_SESSION['prefsStyleSet'] == "BJCP2015") { ?>
			<?php if (($key == "09-A") || ($key == "10-C") || ($key == "07-C")){ ?>
				$("#darkLightColor").show("fast");
				$("input[name='darkLightColor']").prop("required", true);
			<?php } elseif ($key == "24-C") { ?>
				$("#BDGColor").show("fast");
				$("input[name='BDGColor']").prop("required", true);
			<?php } elseif ($key == "21-B") { ?>
				$("#strengthIPA").show("fast");
				$("input[name='strengthIPA']").prop("required", true);
				$("#special").show("fast");
				$("#brewInfo").prop("required", true);
			<?php } elseif ($key == "21-B1") { ?>
				$("#strengthIPA").show("fast");
				$("input[name='strengthIPA']").prop("required", true);
				$("#special").hide("fast");
				$("#brewInfo").prop("required", true);
			<?php } elseif ($key == "21-B2") { ?>
				$("#strengthIPA").show("fast");
				$("input[name='strengthIPA']").prop("required", true);
				$("#special").hide("fast");
				$("#brewInfo").prop("required", true);
			<?php } elseif ($key == "21-B3") { ?>
				$("#strengthIPA").show("fast");
				$("input[name='strengthIPA']").prop("required", true);
				$("#special").hide("fast");
				$("#brewInfo").prop("required", true);
			<?php } elseif ($key == "21-B4") { ?>
				$("#strengthIPA").show("fast");
				$("input[name='strengthIPA']").prop("required", true);
				$("#special").hide("fast");
				$("#brewInfo").prop("required", true);
			<?php } elseif ($key == "21-B5") { ?>
				$("#strengthIPA").show("fast");
				$("input[name='strengthIPA']").prop("required", true);
				$("#special").hide("fast");
				$("#brewInfo").prop("required", true);
			<?php } elseif ($key == "21-B6") { ?>
				$("#strengthIPA").show("fast");
				$("input[name='strengthIPA']").prop("required", true);
				$("#special").hide("fast");
				$("#brewInfo").prop("required", true);
			<?php } elseif ($key == "21-B7") { ?>
				$("#strengthIPA").show("fast");
				$("input[name='strengthIPA']").prop("required", true);
				$("#special").hide("fast");
				$("#brewInfo").prop("required", true);
			<?php } elseif ($key == "23-F") { ?>
				$("#sweetnessLambic").show("fast");
				$("#carbLambic").show("fast");
				$("#special").show("fast");
				$("#brewInfo").prop("required", true);
				$("input[name='sweetnessLambic']").prop("required", true);
				$("input[name='carbLambic']").prop("required", true);
			<?php } elseif ($key == "25-B") { ?>
				$("#darkLightColor").show("fast");
				$("#strengthSaison").show("fast");
				$("#brewInfo").prop("required", true);
				$("input[name='strengthSaison']").prop("required", true);
				$("input[name='darkLightColor']").prop("required", true);
			<?php }	else { ?>
				$("#special").show("fast");
				$("#brewInfo").prop("required", true);
			<?php }	 ?>
			<?php }	else { ?>
				$("#special").show("fast");
				$("#brewInfo").prop("required", true);
			<?php } ?>

		}
		<?php }
		} ?>

		// Styles requiring special ingredients, strength, sweetness, and carbonation
		<?php if (is_array($carb_str_sweet_special_info)) {
		foreach ($carb_str_sweet_special_info as $key => $value) { 
			if (array_key_exists($key, $styles_entry_text)) $entry_text = $styles_entry_text["$key"];
			else $entry_text = $value;
		?>
		else if (
			$("#type").val() == "<?php echo ltrim($key,"0"); ?>"){
			<?php if ($action == "edit") { ?>
			$("#brewInfo").val("");
			$("#brewComments").val("");
			$("input[name='brewMead1']").removeAttr('checked');
			$("input[name='brewMead2']").removeAttr('checked');
			$("input[name='brewMead3']").removeAttr('checked');
			$("input[name='strengthIPA']").removeAttr('checked');
			$("input[name='strengthSaison']").removeAttr('checked');
			$("input[name='darkLightColor']").removeAttr('checked');
			$("input[name='sweetnessLambic']").removeAttr('checked');
			$("input[name='carbLambic']").removeAttr('checked');
			$("input[name='BDGColor']").removeAttr('checked');
			<?php } ?>
			$("#special").hide("fast");
			$("#carbonation").hide("fast");
			$("#sweetness").hide("fast");
			$("#strength").hide("fast");
			$("#strengthIPA").hide("fast");
			$("#strengthSaison").hide("fast");
			$("#darkLightColor").hide("fast");
			$("#BDGColor").hide("fast");
			$("#optional").hide("fast");
			$("#sweetnessLambic").hide("fast");
			$("#carbLambic").hide("fast");
			$("#carbonation").removeClass("has-error");
			$("#sweetness").removeClass("has-error");
			$("#strength").removeClass("has-error");
			$("#strengthIPA").removeClass("has-error");
			$("#strengthSaison").removeClass("has-error");
			$("#darkLightColor").removeClass("has-error");
			$("#sweetnessLambic").removeClass("has-error");
			$("#carbLambic").removeClass("has-error");
			$("#BDGColor").removeClass("has-error");
			$("#brewInfo").prop("required", false);
			$("input[name='brewMead1']").prop("required", false);
			$("input[name='brewMead2']").prop("required", false);
			$("input[name='brewMead3']").prop("required", false);
			$("input[name='strengthIPA']").prop("required", false);
			$("input[name='strengthSaison']").prop("required", false);
			$("input[name='darkLightColor']").prop("required", false);
			$("input[name='sweetnessLambic']").prop("required", false);
			$("input[name='carbLambic']").prop("required", false);
			$("input[name='BDGColor']").prop("required", false);
			$("#special").show("fast");
			$("#carbonation").show("fast");
			$("#sweetness").show("fast");
			$("#strength").show("fast");
			$("#specialInfo").show("fast");
			<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($key,$optional_info_styles))) { ?>
			$("#optional").show("fast");
			<?php } ?>
			$("#brewInfo").prop("required", true);
			$("input[name='brewMead1']").prop("required", true);
			$("input[name='brewMead2']").prop("required", true);
			$("input[name='brewMead3']").prop("required", true);
			$("#specialInfoText").html("<?php echo clean_up_text($entry_text); ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'><?php echo $label_this_style; ?></a>");
		}
		<?php }
		} ?>
		// Styles requiring strength and carb only
		<?php if (is_array($carb_str_only)) {
		foreach ($carb_str_only as $value) { ?>
		else if (
			$("#type").val() == "<?php echo ltrim($value,"0"); ?>"){
			<?php if ($action == "edit") { ?>
			$("#brewInfo").val("");
			$("#brewComments").val("");
			$("input[name='brewMead1']").removeAttr('checked');
			$("input[name='brewMead2']").removeAttr('checked');
			$("input[name='brewMead3']").removeAttr('checked');
			$("input[name='strengthIPA']").removeAttr('checked');
			$("input[name='strengthSaison']").removeAttr('checked');
			$("input[name='darkLightColor']").removeAttr('checked');
			$("input[name='sweetnessLambic']").removeAttr('checked');
			$("input[name='carbLambic']").removeAttr('checked');
			$("input[name='BDGColor']").removeAttr('checked');
			<?php } ?>

			$("#special").hide("fast");
			$("#carbonation").hide("fast");
			$("#sweetness").hide("fast");
			$("#strength").hide("fast");
			$("#strengthIPA").hide("fast");
			$("#strengthSaison").hide("fast");
			$("#darkLightColor").hide("fast");
			$("#sweetnessLambic").hide("fast");
			$("#carbLambic").hide("fast");
			$("#BDGColor").hide("fast");
			$("#optional").hide("fast");
			$("#brewInfo").prop("required", false);
			$("input[name='brewMead1']").prop("required", false);
			$("input[name='brewMead2']").prop("required", false);
			$("input[name='brewMead3']").prop("required", false);
			$("input[name='strengthIPA']").prop("required", false);
			$("input[name='strengthSaison']").prop("required", false);
			$("input[name='darkLightColor']").prop("required", false);
			$("input[name='sweetnessLambic']").prop("required", false);
			$("input[name='carbLambic']").prop("required", false);
			$("input[name='BDGColor']").prop("required", false);

			$("#carbonation").show("fast");
			$("#strength").show("fast");
			<?php if (in_array($value, $styles_entry_text)) {
			$entry_text = $styles_entry_text["$value"]; ?>
			$("#specialInfo").show("fast");
			$("#specialInfoText").html("<?php echo clean_up_text($entry_text); ?>");
			<?php } else { ?>
			$("#specialInfo").hide("fast");
			<?php } ?>
			<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($value,$optional_info_styles))) { ?>
			$("#optional").show("fast");
			<?php } ?>
			$("input[name='brewMead1']").prop("required", true);
			$("input[name='brewMead3']").prop("required", true);
		}
		<?php }
		}
		?>
		// Styles requiring sweetness and carb only
		<?php if (is_array($sweet_carb_only)) {
		foreach ($sweet_carb_only as $value) { ?>
		else if (
			$("#type").val() == "<?php echo ltrim($value,"0"); ?>"){
			<?php if ($action == "edit") { ?>
			$("#brewInfo").val("");
			$("#brewComments").val("");
			$("input[name='brewMead1']").removeAttr('checked');
			$("input[name='brewMead2']").removeAttr('checked');
			$("input[name='brewMead3']").removeAttr('checked');
			$("input[name='strengthIPA']").removeAttr('checked');
			$("input[name='strengthSaison']").removeAttr('checked');
			$("input[name='darkLightColor']").removeAttr('checked');
			$("input[name='sweetnessLambic']").removeAttr('checked');
			$("input[name='carbLambic']").removeAttr('checked');
			$("input[name='BDGColor']").removeAttr('checked');
			<?php } ?>
			$("#special").hide("fast");
			$("#carbonation").hide("fast");
			$("#sweetness").hide("fast");
			$("#strength").hide("fast");
			$("#strengthIPA").hide("fast");
			$("#strengthSaison").hide("fast");
			$("#darkLightColor").hide("fast");
			$("#sweetnessLambic").hide("fast");
			$("#carbLambic").hide("fast");
			$("#BDGColor").hide("fast");
			$("#optional").hide("fast");
			$("#specialInfo").hide("fast");
			$("#brewInfo").prop("required", false);
			$("input[name='brewMead1']").prop("required", false);
			$("input[name='brewMead2']").prop("required", false);
			$("input[name='brewMead3']").prop("required", false);
			$("input[name='strengthIPA']").prop("required", false);
			$("input[name='strengthSaison']").prop("required", false);
			$("input[name='darkLightColor']").prop("required", false);
			$("input[name='sweetnessLambic']").prop("required", false);
			$("input[name='carbLambic']").prop("required", false);
			$("input[name='BDGColor']").prop("required", false);
			$("#carbonation").show("fast");
			$("#sweetness").show("fast");
			<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($value,$optional_info_styles))) { ?>
			$("#optional").show("fast");
			<?php } ?>
			$("input[name='brewMead1']").prop("required", true);
			$("input[name='brewMead2']").prop("required", true);
		}
		<?php }
		} ?>
		// Styles requiring strength, carbonation, and sweetness (no special)
		<?php if (is_array($sweet_carb_str_only)) {
		foreach ($sweet_carb_str_only as $value) { ?>
		else if (
			$("#type").val() == "<?php echo ltrim($value,"0"); ?>"){
			<?php if ($action == "edit") { ?>
			$("#brewInfo").val("");
			$("#brewComments").val("");
			$("input[name='brewMead1']").removeAttr('checked');
			$("input[name='brewMead2']").removeAttr('checked');
			$("input[name='brewMead3']").removeAttr('checked');
			$("input[name='strengthIPA']").removeAttr('checked');
			$("input[name='strengthSaison']").removeAttr('checked');
			$("input[name='darkLightColor']").removeAttr('checked');
			$("input[name='sweetnessLambic']").removeAttr('checked');
			$("input[name='carbLambic']").removeAttr('checked');
			$("input[name='BDGColor']").removeAttr('checked');
			<?php } ?>
			$("#special").hide("fast");
			$("#carbonation").hide("fast");
			$("#sweetness").hide("fast");
			$("#strength").hide("fast");
			$("#strengthIPA").hide("fast");
			$("#strengthSaison").hide("fast");
			$("#darkLightColor").hide("fast");
			$("#sweetnessLambic").hide("fast");
			$("#carbLambic").hide("fast");
			$("#BDGColor").hide("fast");
			$("#optional").hide("fast");
			$("#brewInfo").prop("required", false);
			$("input[name='brewMead1']").prop("required", false);
			$("input[name='brewMead2']").prop("required", false);
			$("input[name='brewMead3']").prop("required", false);
			$("input[name='strengthIPA']").prop("required", false);
			$("input[name='strengthSaison']").prop("required", false);
			$("input[name='darkLightColor']").prop("required", false);
			$("input[name='sweetnessLambic']").prop("required", false);
			$("input[name='carbLambic']").prop("required", false);
			$("input[name='BDGColor']").prop("required", false);
			$("#carbonation").show("fast");
			$("#sweetness").show("fast");
			$("#strength").show("fast");
			$("#specialInfo").hide("fast");
			<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($value,$optional_info_styles))) { ?>
			$("#optional").show("fast");
			<?php } ?>
			$("input[name='brewMead1']").prop("required", true);
			$("input[name='brewMead2']").prop("required", true);
			$("input[name='brewMead3']").prop("required", true);
		}
		<?php }
		} ?>
		// Styles requiring special ingredients, carbonation, and sweetness
		<?php if (is_array($spec_sweet_carb_only)) {
		foreach ($spec_sweet_carb_only_info as $key => $value) { 
			if (array_key_exists($key, $styles_entry_text)) $entry_text = $styles_entry_text["$key"];
			else $entry_text = $value;
			?>
		else if (
			$("#type").val() == "<?php echo ltrim($key,"0"); ?>"){
			<?php if ($action == "edit") { ?>
			$("#brewInfo").val("");
			$("#brewComments").val("");
			$("input[name='brewMead1']").removeAttr('checked');
			$("input[name='brewMead2']").removeAttr('checked');
			$("input[name='brewMead3']").removeAttr('checked');
			$("input[name='strengthIPA']").removeAttr('checked');
			$("input[name='strengthSaison']").removeAttr('checked');
			$("input[name='darkLightColor']").removeAttr('checked');
			$("input[name='sweetnessLambic']").removeAttr('checked');
			$("input[name='carbLambic']").removeAttr('checked');
			$("input[name='BDGColor']").removeAttr('checked');
			<?php } ?>
			$("#special").hide("fast");
			$("#carbonation").hide("fast");
			$("#sweetness").hide("fast");
			$("#strength").hide("fast");
			$("#strengthIPA").hide("fast");
			$("#strengthSaison").hide("fast");
			$("#darkLightColor").hide("fast");
			$("#sweetnessLambic").hide("fast");
			$("#carbLambic").hide("fast");
			$("#BDGColor").hide("fast");
			$("#optional").hide("fast");
			$("#brewInfo").prop("required", false);
			$("input[name='brewMead1']").prop("required", false);
			$("input[name='brewMead2']").prop("required", false);
			$("input[name='brewMead3']").prop("required", false);
			$("input[name='strengthIPA']").prop("required", false);
			$("input[name='strengthSaison']").prop("required", false);
			$("input[name='darkLightColor']").prop("required", false);
			$("input[name='sweetnessLambic']").prop("required", false);
			$("input[name='carbLambic']").prop("required", false);
			$("input[name='BDGColor']").prop("required", false);
			$("#carbonation").show("fast");
			$("#sweetness").show("fast");
			$("#special").show("fast");
			$("#specialInfo").show("fast");
			<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($key,$optional_info_styles))) { ?>
			$("#optional").show("fast");
			<?php } ?>
			$("#brewInfo").prop("required", true);
			$("input[name='brewMead1']").prop("required", true);
			$("input[name='brewMead2']").prop("required", true);
			$("#specialInfoText").html("<?php echo clean_up_text($entry_text); ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'><?php echo $label_this_style; ?></a>");
		}
		<?php }
		} ?>
		// Styles requiring special ingredients and carbonation
		<?php if (is_array($spec_carb_only_info)) {
		foreach ($spec_carb_only_info as $key => $value) { 
			if (array_key_exists($key, $styles_entry_text)) $entry_text = $styles_entry_text["$key"];
			else $entry_text = $value;
			?>
		else if (
			$("#type").val() == "<?php echo ltrim($key,"0"); ?>"){
			<?php if ($action == "edit") { ?>
			$("#brewInfo").val("");
			$("#brewComments").val("");
			$("input[name='brewMead1']").removeAttr('checked');
			$("input[name='brewMead2']").removeAttr('checked');
			$("input[name='brewMead3']").removeAttr('checked');
			$("input[name='strengthIPA']").removeAttr('checked');
			$("input[name='strengthSaison']").removeAttr('checked');
			$("input[name='darkLightColor']").removeAttr('checked');
			$("input[name='sweetnessLambic']").removeAttr('checked');
			$("input[name='carbLambic']").removeAttr('checked');
			$("input[name='BDGColor']").removeAttr('checked');
			<?php } ?>
			$("#special").hide("fast");
			$("#carbonation").hide("fast");
			$("#sweetness").hide("fast");
			$("#strength").hide("fast");
			$("#strengthIPA").hide("fast");
			$("#strengthSaison").hide("fast");
			$("#darkLightColor").hide("fast");
			$("#sweetnessLambic").hide("fast");
			$("#carbLambic").hide("fast");
			$("#BDGColor").hide("fast");
			$("#optional").hide("fast");
			$("#brewInfo").prop("required", false);
			$("input[name='brewMead1']").prop("required", false);
			$("input[name='brewMead2']").prop("required", false);
			$("input[name='brewMead3']").prop("required", false);
			$("input[name='strengthIPA']").prop("required", false);
			$("input[name='strengthSaison']").prop("required", false);
			$("input[name='darkLightColor']").prop("required", false);
			$("input[name='sweetnessLambic']").prop("required", false);
			$("input[name='carbLambic']").prop("required", false);
			$("input[name='BDGColor']").prop("required", false);
			$("#carbonation").show("fast");
			$("#special").show("fast");
			<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($key,$optional_info_styles))) { ?>
			$("#optional").show("fast");
			<?php } ?>
			$("#brewInfo").prop("required", true);
			$("input[name='brewMead1']").prop("required", true);
			$("#specialInfo").show("fast");
			$("#specialInfoText").html("<?php echo clean_up_text($entry_text); ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'><?php echo $label_this_style; ?></a>");
		}
		<?php }
		} ?>
		else {
			$("#special").hide("fast");
			$("#carbonation").hide("fast");
			$("#sweetness").hide("fast");
			$("#strength").hide("fast");
			$("#strengthIPA").hide("fast");
			$("#strengthSaison").hide("fast");
			$("#darkLightColor").hide("fast");
			$("#sweetnessLambic").hide("fast");
			$("#carbLambic").hide("fast");
			$("#BDGColor").hide("fast");
			$("#optional").hide("fast");
			$("#brewInfo").prop("required", false);
			$("input[name='brewMead1']").prop("required", false);
			$("input[name='brewMead2']").prop("required", false);
			$("input[name='brewMead3']").prop("required", false);
			$("input[name='strengthIPA']").prop("required", false);
			$("input[name='strengthSaison']").prop("required", false);
			$("input[name='darkLightColor']").prop("required", false);
			$("input[name='sweetnessLambic']").prop("required", false);
			$("input[name='carbLambic']").prop("required", false);
			$("input[name='BDGColor']").prop("required", false);
		}
	}
	);
}
);

<?php
// --------------------------------------------------------------------------------------------------------------------------------------------
if ($action == "edit") {
// --------------------------------------------------------------------------------------------------------------------------------------------
?>

	$(document).ready(function() {
		$("#special").hide("fast");
		$("#carbonation").hide("fast");
		$("#sweetness").hide("fast");
		$("#strength").hide("fast");
		$("#strengthIPA").hide("fast");
		$("#strengthSaison").hide("fast");
		$("#darkLightColor").hide("fast");
		$("#sweetnessLambic").hide("fast");
		$("#carbLambic").hide("fast");
		$("#BDGColor").hide("fast");
		$("#optional").hide("fast");
		$("#specialInfo").hide("fast");
		$("#brewInfo").prop("required", false);
		$("input[name='brewMead1']").prop("required", false);
		$("input[name='brewMead2']").prop("required", false);
		$("input[name='brewMead3']").prop("required", false);
		$("input[name='strengthIPA']").prop("required", false);
		$("input[name='strengthSaison']").prop("required", false);
		$("input[name='darkLightColor']").prop("required", false);
		$("input[name='sweetnessLambic']").prop("required", false);
		$("input[name='carbLambic']").prop("required", false);
		$("input[name='BDGColor']").prop("required", false);
	});

	<?php if ((is_array($carb_str_sweet_special_info)) && (array_key_exists($view,$carb_str_sweet_special_info))) { 
		if (in_array($view, $styles_entry_text)) $entry_text = $styles_entry_text["$view"];
		else $entry_text = $carb_str_sweet_special_info["$view"];
		?>
	// Show fields for styles that require special ingredients, carb, sweetness, strength, etc.
	// Carb, sweetness, strength, and special info styles
	$(document).ready(function() {
		$("#special").show("fast");
		$("#carbonation").show("fast");
		$("#sweetness").show("fast");
		$("#strength").show("fast");
		$("#strengthIPA").hide("fast");
		$("#strengthSaison").hide("fast");
		$("#darkLightColor").hide("fast");
		$("#sweetnessLambic").hide("fast");
		$("#carbLambic").hide("fast");
		$("#BDGColor").hide("fast");
		$("#brewInfo").prop("required", true);
		$("#optional").hide("fast");
		$("input[name='brewMead1']").prop("required", true);
		$("input[name='brewMead2']").prop("required", true);
		$("input[name='brewMead3']").prop("required", true);
		$("input[name='strengthIPA']").prop("required", false);
		$("input[name='strengthSaison']").prop("required", false);
		$("input[name='darkLightColor']").prop("required", false);
		$("input[name='sweetnessLambic']").prop("required", false);
		$("input[name='carbLambic']").prop("required", false);
		$("input[name='BDGColor']").prop("required", false);
		$("#specialInfo").show("fast");
		$("#specialInfoText").html("<?php echo clean_up_text($entry_text); ?>");
		$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
		<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
		$("#optional").show("fast");
		<?php } ?>
	});
	<?php } ?>

	<?php if ((is_array($carb_str_only)) && (in_array($view,$carb_str_only))) { ?>
	// Carb and strength only styles
	$(document).ready(function() {
		$("#special").hide("fast");
		$("#carbonation").show("fast");
		$("#sweetness").hide("fast");
		$("#strength").show("fast");
		$("#strengthIPA").hide("fast");
		$("#strengthSaison").hide("fast");
		$("#darkLightColor").hide("fast");
		$("#sweetnessLambic").hide("fast");
		$("#carbLambic").hide("fast");
		$("#BDGColor").hide("fast");
		$("#optional").hide("fast");
		$("#specialInfo").hide("fast");
		$("#brewInfo").prop("required", false);
		$("input[name='brewMead1']").prop("required", true);
		$("input[name='brewMead2']").prop("required", false);
		$("input[name='brewMead3']").prop("required", true);
		$("input[name='strengthIPA']").prop("required", false);
		$("input[name='strengthSaison']").prop("required", false);
		$("input[name='darkLightColor']").prop("required", false);
		$("input[name='sweetnessLambic']").prop("required", false);
		$("input[name='carbLambic']").prop("required", false);
		$("input[name='BDGColor']").prop("required", false);
		<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
		$("#optional").show("fast");
		<?php } ?>
	});
	<?php } ?>

	<?php if ((is_array($sweet_carb_only)) && (in_array($view,$sweet_carb_only))) { ?>
	// Carb and strength only styles
	$(document).ready(function() {
		$("#special").hide("fast");
		$("#carbonation").show("fast");
		$("#sweetness").show("fast");
		$("#strength").hide("fast");
		$("#strengthIPA").hide("fast");
		$("#strengthSaison").hide("fast");
		$("#darkLightColor").hide("fast");
		$("#sweetnessLambic").hide("fast");
		$("#carbLambic").hide("fast");
		$("#BDGColor").hide("fast");
		$("#optional").hide("fast");
		$("#specialInfo").hide("fast");
		$("#brewInfo").prop("required", false);
		$("input[name='brewMead1']").prop("required", true);
		$("input[name='brewMead2']").prop("required", true);
		$("input[name='brewMead3']").prop("required", false);
		$("input[name='strengthIPA']").prop("required", false);
		$("input[name='strengthSaison']").prop("required", false);
		$("input[name='darkLightColor']").prop("required", false);
		$("input[name='sweetnessLambic']").prop("required", false);
		$("input[name='carbLambic']").prop("required", false);
		$("input[name='BDGColor']").prop("required", false);
		<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
		$("#optional").show("fast");
		<?php } ?>
	});
	<?php } ?>

	<?php if ((is_array($sweet_carb_str_only)) && (in_array($view,$sweet_carb_str_only))) { ?>
	// Sweet, carb, and strength only styles
	$(document).ready(function() {
		$("#special").hide("fast");
		$("#carbonation").show("fast");
		$("#sweetness").show("fast");
		$("#strength").show("fast");
		$("#strengthIPA").hide("fast");
		$("#strengthSaison").hide("fast");
		$("#darkLightColor").hide("fast");
		$("#sweetnessLambic").hide("fast");
		$("#carbLambic").hide("fast");
		$("#BDGColor").hide("fast");
		$("#optional").hide("fast");
		$("#specialInfo").hide("fast");
		$("#brewInfo").prop("required", false);
		$("input[name='brewMead1']").prop("required", true);
		$("input[name='brewMead2']").prop("required", true);
		$("input[name='brewMead3']").prop("required", true);
		$("input[name='strengthIPA']").prop("required", false);
		$("input[name='strengthSaison']").prop("required", false);
		$("input[name='darkLightColor']").prop("required", false);
		$("input[name='sweetnessLambic']").prop("required", false);
		$("input[name='carbLambic']").prop("required", false);
		$("input[name='BDGColor']").prop("required", false);
		<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
		$("#optional").show("fast");
		<?php } ?>
	});
	<?php } ?>

	<?php if ((is_array($spec_sweet_carb_only_info)) && (array_key_exists($view,$spec_sweet_carb_only_info))) { 
		if (in_array($view, $styles_entry_text)) $entry_text = $styles_entry_text["$view"];
		else $entry_text = $spec_sweet_carb_only_info["$view"];
		?>
	// Sweet and sweet only styles with special ingredients
	$(document).ready(function() {
		$("#special").show("fast");
		$("#carbonation").show("fast");
		$("#sweetness").show("fast");
		$("#strength").hide("fast");
		$("#strengthIPA").hide("fast");
		$("#strengthSaison").hide("fast");
		$("#darkLightColor").hide("fast");
		$("#sweetnessLambic").hide("fast");
		$("#carbLambic").hide("fast");
		$("#BDGColor").hide("fast");
		$("#optional").hide("fast");
		$("#specialInfo").show("fast");
		$("#brewInfo").prop("required", true);
		$("input[name='brewMead1']").prop("required", true);
		$("input[name='brewMead2']").prop("required", true);
		$("input[name='brewMead3']").prop("required", false);
		$("input[name='strengthIPA']").prop("required", false);
		$("input[name='strengthSaison']").prop("required", false);
		$("input[name='darkLightColor']").prop("required", false);
		$("input[name='sweetnessLambic']").prop("required", false);
		$("input[name='carbLambic']").prop("required", false);
		$("input[name='BDGColor']").prop("required", false);
		$("#specialInfoText").html("<?php echo clean_up_text($entry_text); ?>");
		$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
		<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
		$("#optional").show("fast");
		<?php } ?>
	});
	<?php } ?>

	<?php if ((is_array($spec_carb_only_info)) && (array_key_exists($view,$spec_carb_only_info))) { 
		if (in_array($view, $styles_entry_text)) $entry_text = $styles_entry_text["$view"];
		else $entry_text = $spec_carb_only_info["$view"];
		?>
	// Carb with special ingredients only styles
	$(document).ready(function() {
		$("#special").show("fast");
		$("#carbonation").show("fast");
		$("#sweetness").hide("fast");
		$("#strength").hide("fast");
		$("#strengthIPA").hide("fast");
		$("#strengthSaison").hide("fast");
		$("#darkLightColor").hide("fast");
		$("#sweetnessLambic").hide("fast");
		$("#carbLambic").hide("fast");
		$("#BDGColor").hide("fast");
		$("#optional").hide("fast");
		$("#specialInfo").show("fast");
		$("#brewInfo").prop("required", true);
		$("input[name='brewMead1']").prop("required", true);
		$("input[name='brewMead2']").prop("required", false);
		$("input[name='brewMead3']").prop("required", false);
		$("input[name='strengthIPA']").prop("required", false);
		$("input[name='strengthSaison']").prop("required", false);
		$("input[name='darkLightColor']").prop("required", false);
		$("input[name='sweetnessLambic']").prop("required", false);
		$("input[name='carbLambic']").prop("required", false);
		$("input[name='BDGColor']").prop("required", false);
		$("#specialInfoText").html("<?php echo clean_up_text($entry_text); ?>");
		$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
		<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
		$("#optional").show("fast");
		<?php } ?>
	});
	<?php } ?>

	<?php if ((is_array($special_beer_info)) && (array_key_exists($view,$special_beer_info))) {
		if (in_array($view, $styles_entry_text)) $entry_text = $styles_entry_text["$view"];
		else $entry_text = $special_beer_info["$view"];
	 ?>
	// Beer styles with special ingredients only
	$(document).ready(function() {
		$("#special").show("fast");
		$("#carbonation").hide("fast");
		$("#sweetness").hide("fast");
		$("#strength").hide("fast");
		$("#strengthIPA").hide("fast");
		$("#strengthSaison").hide("fast");
		$("#darkLightColor").hide("fast");
		$("#sweetnessLambic").hide("fast");
		$("#carbLambic").hide("fast");
		$("#BDGColor").hide("fast");
		$("#optional").hide("fast");
		$("#brewInfo").prop("required", true);
		$("input[name='brewMead1']").prop("required", false);
		$("input[name='brewMead2']").prop("required", false);
		$("input[name='brewMead3']").prop("required", false);
		$("input[name='strengthIPA']").prop("required", false);
		$("input[name='strengthSaison']").prop("required", false);
		$("input[name='darkLightColor']").prop("required", false);
		$("input[name='sweetnessLambic']").prop("required", false);
		$("input[name='carbLambic']").prop("required", false);
		$("input[name='BDGColor']").prop("required", false);
		$("#specialInfo").show("fast");
		$("#specialInfoText").html("<?php echo clean_up_text($entry_text); ?>");
		$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
		<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
		$("#optional").show("fast");
		<?php } ?>
	});
	<?php } ?>
	<?php if ($_SESSION['prefsStyleSet'] == "BJCP2015") { ?>
		<?php if (($view == "09-A") || ($view == "10-C") || ($view == "07-C")) { ?>
			$(document).ready(function() {
				$("#special").hide("fast");
				$("#carbonation").hide("fast");
				$("#sweetness").hide("fast");
				$("#strength").hide("fast");
				$("#strengthIPA").hide("fast");
				$("#strengthSaison").hide("fast");
				$("#darkLightColor").show("fast");
				$("#sweetnessLambic").hide("fast");
				$("#carbLambic").hide("fast");
				$("#BDGColor").hide("fast");
				$("#optional").hide("fast");
				$("#specialInfo").show("fast");
				$("#specialInfoText").html("<?php echo clean_up_text($styles_entry_text["$view"]); ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
				<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
				$("#optional").show("fast");
				
				<?php } ?>
			});
		<?php } elseif ($view == "24-C") { ?>
			$(document).ready(function() {
				$("#special").hide("fast");
				$("#carbonation").hide("fast");
				$("#sweetness").hide("fast");
				$("#strength").hide("fast");
				$("#strengthIPA").hide("fast");
				$("#strengthSaison").hide("fast");
				$("#darkLightColor").hide("fast");
				$("#sweetnessLambic").hide("fast");
				$("#carbLambic").hide("fast");
				$("#BDGColor").show("fast");
				$("#optional").hide("fast");
				$("#specialInfo").show("fast");
				$("#specialInfoText").html("<?php echo clean_up_text($styles_entry_text["$view"]); ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
				<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
				$("#optional").show("fast");
				
				<?php } ?>
			});
		<?php } elseif ($view == "21-B") { ?>
			$(document).ready(function() {
				$("#special").show("fast");
				$("#carbonation").hide("fast");
				$("#sweetness").hide("fast");
				$("#strength").hide("fast");
				$("#strengthIPA").show("fast");
				$("#strengthSaison").hide("fast");
				$("#darkLightColor").hide("fast");
				$("#sweetnessLambic").hide("fast");
				$("#carbLambic").hide("fast");
				$("#BDGColor").hide("fast");
				$("#optional").show("fast");
				$("#specialInfo").show("fast");
				$("#specialInfoText").html("<?php echo clean_up_text($styles_entry_text["$view"]); ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
				<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
				$("#optional").show("fast");
				
				<?php } ?>
			});
		<?php } elseif ($view == "21-B1") { ?>
			$(document).ready(function() {
				$("#special").hide("fast");
				$("#carbonation").hide("fast");
				$("#sweetness").hide("fast");
				$("#strength").hide("fast");
				$("#strengthIPA").show("fast");
				$("#strengthSaison").hide("fast");
				$("#darkLightColor").hide("fast");
				$("#sweetnessLambic").hide("fast");
				$("#carbLambic").hide("fast");
				$("#BDGColor").hide("fast");
				$("#specialInfo").hide("fast");
				$("#specialInfoText").html("<?php echo clean_up_text($styles_entry_text["$view"]); ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
				<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
				$("#optional").show("fast");
				
				<?php } ?>
			});
		<?php } elseif ($view == "21-B2") { ?>
			$(document).ready(function() {
				$("#special").hide("fast");
				$("#carbonation").hide("fast");
				$("#sweetness").hide("fast");
				$("#strength").hide("fast");
				$("#strengthIPA").show("fast");
				$("#strengthSaison").hide("fast");
				$("#darkLightColor").hide("fast");
				$("#sweetnessLambic").hide("fast");
				$("#carbLambic").hide("fast");
				$("#BDGColor").hide("fast");
				$("#specialInfo").hide("fast");
				$("#specialInfoText").html("<?php echo clean_up_text($styles_entry_text["$view"]); ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
				<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
				$("#optional").show("fast");
				
				<?php } ?>
			});
		<?php } elseif ($view == "21-B3") { ?>
			$(document).ready(function() {
				$("#special").hide("fast");
				$("#carbonation").hide("fast");
				$("#sweetness").hide("fast");
				$("#strength").hide("fast");
				$("#strengthIPA").show("fast");
				$("#strengthSaison").hide("fast");
				$("#darkLightColor").hide("fast");
				$("#sweetnessLambic").hide("fast");
				$("#carbLambic").hide("fast");
				$("#BDGColor").hide("fast");
				$("#specialInfo").hide("fast");
				$("#specialInfoText").html("<?php echo clean_up_text($styles_entry_text["$view"]); ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
				<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
				$("#optional").show("fast");
				
				<?php } ?>
			});
		<?php } elseif ($view == "21-B4") { ?>
			$(document).ready(function() {
				$("#special").hide("fast");
				$("#carbonation").hide("fast");
				$("#sweetness").hide("fast");
				$("#strength").hide("fast");
				$("#strengthIPA").show("fast");
				$("#strengthSaison").hide("fast");
				$("#darkLightColor").hide("fast");
				$("#sweetnessLambic").hide("fast");
				$("#carbLambic").hide("fast");
				$("#BDGColor").hide("fast");
				$("#specialInfo").hide("fast");
				$("#specialInfoText").html("<?php echo clean_up_text($styles_entry_text["$view"]); ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
				<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
				$("#optional").show("fast");
				
				<?php } ?>
			});
		<?php } elseif ($view == "21-B5") { ?>
			$(document).ready(function() {
				$("#special").hide("fast");
				$("#carbonation").hide("fast");
				$("#sweetness").hide("fast");
				$("#strength").hide("fast");
				$("#strengthIPA").show("fast");
				$("#strengthSaison").hide("fast");
				$("#darkLightColor").hide("fast");
				$("#sweetnessLambic").hide("fast");
				$("#carbLambic").hide("fast");
				$("#BDGColor").hide("fast");
				$("#specialInfo").hide("fast");
				$("#specialInfoText").html("<?php echo clean_up_text($styles_entry_text["$view"]); ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
				<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
				$("#optional").show("fast");
				
				<?php } ?>
			});
		<?php } elseif ($view == "21-B6") { ?>
			$(document).ready(function() {
				$("#special").hide("fast");
				$("#carbonation").hide("fast");
				$("#sweetness").hide("fast");
				$("#strength").hide("fast");
				$("#strengthIPA").show("fast");
				$("#strengthSaison").hide("fast");
				$("#darkLightColor").hide("fast");
				$("#sweetnessLambic").hide("fast");
				$("#carbLambic").hide("fast");
				$("#BDGColor").hide("fast");
				$("#specialInfo").hide("fast");
				$("#specialInfoText").html("<?php echo clean_up_text($special_beer_info["$view"]); ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
				<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
				$("#optional").show("fast");
				
				<?php } ?>
			});
		<?php } elseif ($view == "23-F") { ?>
			$(document).ready(function() {
				$("#special").hide("fast");
				$("#carbonation").hide("fast");
				$("#sweetness").hide("fast");
				$("#strength").hide("fast");
				$("#strengthIPA").hide("fast");
				$("#strengthSaison").hide("fast");
				$("#darkLightColor").hide("fast");
				$("#sweetnessLambic").show("fast");
				$("#carbLambic").show("fast");
				$("#BDGColor").hide("fast");
				$("#special").show("fast");
				$("#specialInfo").show("fast");
				$("#specialInfoText").html("<?php echo clean_up_text($special_beer_info["$view"]); ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
				<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
				$("#optional").show("fast");
				<?php } ?>
			});
		<?php } elseif ($view == "25-B") { ?>
			$(document).ready(function() {
				$("#special").hide("fast");
				$("#carbonation").hide("fast");
				$("#sweetness").hide("fast");
				$("#strength").hide("fast");
				$("#strengthIPA").hide("fast");
				$("#strengthSaison").show("fast");
				$("#darkLightColor").show("fast");
				$("#sweetnessLambic").hide("fast");
				$("#carbLambic").hide("fast");
				$("#BDGColor").hide("fast");
				$("#specialInfo").show("fast");
				$("#specialInfoText").html("<?php echo clean_up_text($special_beer_info["$view"]); ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'><?php echo $label_this_style; ?></a>");
				<?php if (($_SESSION['prefsStyleSet'] == "BJCP2015") && (in_array($view,$optional_info_styles))) { ?>
				$("#optional").show("fast");
				<?php } ?>
			});
		<?php }	?>
	<?php } // if ($_SESSION['prefsStyleSet'] == "BJCP2015") ?>
<?php } // end if ($action == "edit") ?>
</script>
<?php } // end if ($section == "brew") ?>