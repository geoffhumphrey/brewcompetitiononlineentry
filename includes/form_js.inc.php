<?php
if (($section == "admin") && ($go == "styles") && ($action != "default")) {
	if ($styleSet != "BA") {
		$specialty_ipa_subs = array("21-B1","21-B2","21-B3","21-B4","21-B5","21-B6","21-B7");
		$historical_subs = array("27-A1","27-A2","27-A3","27-A4","27-A5","27-A6","27-A7","27-A8","27-A9");
	}
?>
<script src="<?php echo $base_url; ?>js_includes/add_edit_style.min.js"></script>
<?php } ?>
<?php if ($section == "brew") { ?>
<script type="text/javascript">
	var style_set = "<?php echo $_SESSION['prefsStyleSet']; ?>";
	var req_special_ing_styles = <?php echo json_encode($req_special_ing_styles); ?>;
	var req_strength_styles = <?php echo json_encode($req_strength_styles); ?>;
	var req_sweetness_styles = <?php echo json_encode($req_sweetness_styles); ?>;
	var req_carb_styles = <?php echo json_encode($req_carb_styles); ?>;
	var optional_info_styles = <?php echo json_encode($optional_info_styles); ?>;
	var edit_style = "<?php echo ltrim($view,"0"); ?>";
	var label_this_style = "<?php echo $label_this_style; ?>";
	var req_special_ing_style_info = <?php echo json_encode($styles_entry_text, JSON_UNESCAPED_SLASHES); ?>;
	<?php if (($action == "edit") && (!empty($row_log['brewPossAllergens']))) { ?>
	var possible_allergens = "<?php echo $row_log['brewPossAllergens']; ?>";
	<?php } else { ?>
	possible_allergens = null;		
	<?php } ?>
</script>
<script src="<?php echo $base_url; ?>js_includes/add_edit_entry.min.js"></script>
<?php } // end if ($section == "brew") ?>