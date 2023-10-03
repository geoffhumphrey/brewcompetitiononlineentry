<?php if (($section == "admin") && ($go == "styles") && ($action != "default")) { ?>
<script>
	var specialty_ipa_subs = <?php echo json_encode($specialty_ipa_subs); ?>;
	var historical_subs = <?php echo json_encode($historical_subs); ?>;
	var edit_style = "<?php echo $action; ?>";
	if (edit_style == "edit") {
		var req_special = "<?php echo $row_styles['brewStyleReqSpec']; ?>";
		var style_type = "<?php echo $row_styles['brewStyleType']; ?>";
	} else { 
		var req_special = "0";
		var style_type = "1";
	}
</script>
<script src="<?php echo $js_url; ?>add_edit_style.min.js"></script>
<?php } ?>
<?php if ($section == "brew") { ?>
<script type="text/javascript">
	var user_level = "<?php if ($bid != "default") echo $_SESSION['userLevel']; else echo "2"; ?>";
	var style_set = "<?php echo $_SESSION['prefsStyleSet']; ?>";
	var req_special_ing_styles = <?php echo json_encode($req_special_ing_styles); ?>;
	var req_strength_styles = <?php echo json_encode($req_strength_styles); ?>;
	var req_sweetness_styles = <?php echo json_encode($req_sweetness_styles); ?>;
	var req_carb_styles = <?php echo json_encode($req_carb_styles); ?>;
	var cider_sweetness_custom_styles = <?php echo json_encode($cider_sweetness_custom_styles); ?>;
	var mead_sweetness_custom_styles = <?php echo json_encode($mead_sweetness_custom_styles); ?>;
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
<script src="<?php echo $js_url; ?>add_edit_entry.min.js"></script>
<?php } // end if ($section == "brew") ?>