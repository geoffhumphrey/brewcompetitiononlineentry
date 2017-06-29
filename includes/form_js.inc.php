<?php 

/*
Checked Single
2016-06-06

*/

if (($section == "admin") && ($go == "styles") && ($action != "default")) { 

	if (strpos($styleSet,"BABDB") === false) {
		$specialty_ipa_subs = array("21-B1","21-B2","21-B3","21-B4","21-B5","21-B6");
		$historical_subs = array("27-A1","27-A2","27-A3","27-A4","27-A5","27-A6","27-A7","27-A8","27-A9");
	}	
?>
<!-- Load Show/Hide Configuration -->
<script type="text/javascript">
										  
$(document).ready(function() {
	$("#mead-cider").hide("fast");
	$("#mead").hide("fast");
	$("#brewStyleEntry").hide("fast");
});

</script>
<?php } ?>


<?php if ($section == "brew") { 
if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) $optional_info_styles = array("21-B","28-A","30-B","33-A","33-B","34-B","M2-C","M2-D","M2-E","M3-A","M3-B","M4-B","M4-C","07-C","M1-A","M1-B","M1-C","M2-A","M2-B","M4-A","C1-B","C1-C");
else $optional_info_styles = array();
?>
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
		$("#brewInfoOptional").prop("required", false);
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
		$("#brewInfoOptional").prop("required", false);
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
			$("#brewInfoOptional").prop("required", false);
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
		
		<?php if (strpos($styleSet,"BABDB") !== false) { 
		
		foreach ($ba_special_beer as $value) { ?>
		
		else if ( 
			$("#type").val() == "<?php echo $value; ?>"){
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
				
				$("#special").show("fast");
				$("#brewInfo").prop("required", true);
				
				
				// $("#specialInfo").show("fast");
				// $("#specialInfoText").html("<?php echo $value; ?>");
				// $("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'>Style <?php echo str_replace("-","",$key); ?></a>");
			}
		<?php } ?>
		
		// Styles requiring special ing, strength, sweetness, and carb
		<?php foreach ($ba_special_carb_str_sweet as $value) { ?>
		else if ( 
			$("#type").val() == "<?php echo $value; ?>"){
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
				
				$("#brewInfo").prop("required", true);
				$("input[name='brewMead1']").prop("required", true);
				$("input[name='brewMead2']").prop("required", true);
				$("input[name='brewMead3']").prop("required", true);
				
				//$("#specialInfo").show("fast");
				//$("#specialInfoText").html("<?php echo $value; ?>");
				//$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'>Style <?php echo str_replace("-","",$key); ?></a>");
		}
		<?php } ?>
		
		
		<?php foreach ($ba_carb_str as $value) { ?>
		else if ( 
			$("#type").val() == "<?php echo $value; ?>"){
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
				$("#specialInfo").hide("fast");
				
				$("input[name='brewMead1']").prop("required", true);
				$("input[name='brewMead3']").prop("required", true);
		
		}
		<?php } ?>
		
		<?php foreach ($ba_carb_sweet as $value) { ?>
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
				$("input[name='brewMead1']").prop("required", true);
				$("input[name='brewMead2']").prop("required", true);
		}
		<?php } ?>
		
		// Styles requiring strength, carbonation, and sweetness (no special)
		<?php foreach ($ba_carb_str_sweet as $value) { ?>
		else if ( 
			$("#type").val() == "<?php echo $value; ?>"){
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
				
				$("input[name='brewMead1']").prop("required", true);
				$("input[name='brewMead2']").prop("required", true);
				$("input[name='brewMead3']").prop("required", true);
			
		}
		<?php } ?>
		
		// Styles requiring special ingredients, carbonation, and sweetness
		<?php foreach ($ba_carb_sweet_special as $value) { ?>
		else if ( 
			$("#type").val() == "<?php echo $value; ?>"){
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
				
				
				$("input[name='brewMead1']").prop("required", true);
				$("input[name='brewMead2']").prop("required", true);
				
				//$("#specialInfo").show("fast");
				//$("#specialInfoText").html("<?php echo $value; ?>");
				//$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'>Style <?php echo str_replace("-","",$key); ?></a>");
		}
		<?php } ?>
		
		<?php foreach ($ba_carb_special as $key => $value) { ?>
		// Styles requiring special ingredients and carbonation
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
				$("input[name='brewMead1']").prop("required", true);
				
				//$("#specialInfo").show("fast");
				//$("#specialInfoText").html("<?php echo $value; ?>");
				//$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'>Style <?php echo str_replace("-","",$key); ?></a>");
		}
		<?php } ?>
		
		<?php } // END if (strpos($styleSet,"BABDB") !== false) ?>	
			
		
		<?php 
		
		// --------------------------------------------------------------------------------------- //
		
		if (is_array($special_beer_info)) {
		foreach ($special_beer_info as $key => $value) { ?>
		
		// Special Beer
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
				$("#specialInfoText").html("<?php echo $value; ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'>Style <?php echo str_replace("-","",$key); ?></a>");
			<?php if ($_SESSION['prefsStyleSet'] == "BJCP2015") { ?>
			
			<?php if (in_array($key,$optional_info_styles)) { ?>
				$("#optional").show("fast");
			<?php } ?>
			
			
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
		}
		
		if (is_array($carb_str_sweet_special_info)) {
		foreach ($carb_str_sweet_special_info as $key => $value) { ?>
		// Styles requiring special ing, strength, sweetness, and carb
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
			<?php if (in_array($key,$optional_info_styles)) { ?>
			$("#optional").show("fast");
			<?php } ?>
			
			$("#brewInfo").prop("required", true);
			$("input[name='brewMead1']").prop("required", true);
			$("input[name='brewMead2']").prop("required", true);
			$("input[name='brewMead3']").prop("required", true);
			
			$("#specialInfoText").html("<?php echo $value; ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'>Style <?php echo str_replace("-","",$key); ?></a>");
		}
		<?php } 
		}
		
		if (is_array($carb_str_only)) { 
		foreach ($carb_str_only as $value) { ?>
		// Styles requiring strength and carb only
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
			$("#specialInfo").hide("fast");
			<?php if (in_array($value,$optional_info_styles)) { ?>
			$("#optional").show("fast");
			<?php } ?>
			
			$("input[name='brewMead1']").prop("required", true);
			$("input[name='brewMead3']").prop("required", true);
			
			
		}
		<?php }
		} 
		?>
		
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
			<?php if (in_array($value,$optional_info_styles)) { ?>
			$("#optional").show("fast");
			<?php } ?>
			$("input[name='brewMead1']").prop("required", true);
			$("input[name='brewMead2']").prop("required", true);
			
		}
		<?php } 
		}
		
		if (is_array($sweet_carb_str_only)) { 
		foreach ($sweet_carb_str_only as $value) { ?>
		// Styles requiring strength, carbonation, and sweetness (no special)
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
			<?php if (in_array($value,$optional_info_styles)) { ?>
			$("#optional").show("fast");
			<?php } ?>
			
			$("input[name='brewMead1']").prop("required", true);
			$("input[name='brewMead2']").prop("required", true);
			$("input[name='brewMead3']").prop("required", true);
			
		}
		<?php }
		} 
		
		if (is_array($spec_sweet_carb_only)) {
		foreach ($spec_sweet_carb_only_info as $key => $value) { ?>
		// Styles requiring special ingredients, carbonation, and sweetness
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
			<?php if (in_array($key,$optional_info_styles)) { ?>
			$("#optional").show("fast");
			<?php } ?>
			
			$("input[name='brewMead1']").prop("required", true);
			$("input[name='brewMead2']").prop("required", true);
			
			$("#specialInfoText").html("<?php echo $value; ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'>Style <?php echo str_replace("-","",$key); ?></a>");
		}
		<?php }
		} ?>
		
		<?php if (is_array($spec_carb_only_info)) {
		foreach ($spec_carb_only_info as $key => $value) { ?>
		// Styles requiring special ingredients and carbonation
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
			<?php if (in_array($key,$optional_info_styles)) { ?>
			$("#optional").show("fast");
			<?php } ?>
			$("input[name='brewMead1']").prop("required", true);
			
			$("#specialInfo").show("fast");
			$("#specialInfoText").html("<?php echo $value; ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'>Style <?php echo str_replace("-","",$key); ?></a>");
		}
		<?php } 
		}
		?>
				
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
	
	
	<?php if (strpos($styleSet,"BABDB") !== false) { 	?>
	
		<?php if (in_array($view,$ba_special_carb_str_sweet)) { ?>
		// Show fields for styles that require special ingredients, carb, sweetness, strength, etc.
		
		// 1 - carb, sweetness, strength, and special styles
		$(document).ready(function() {
			$("#special").show("fast");
			$("#specialInfo").hide("fast");
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
			$("#brewInfo").prop("required", true);
			$("input[name='brewMead1']").prop("required", true);
			$("input[name='brewMead2']").prop("required", true);
			$("input[name='brewMead3']").prop("required", true);
			$("input[name='strengthIPA']").prop("required", false);
			$("input[name='strengthSaison']").prop("required", false);
			$("input[name='darkLightColor']").prop("required", false);
			$("input[name='sweetnessLambic']").prop("required", false);
			$("input[name='carbLambic']").prop("required", false);
			$("input[name='BDGColor']").prop("required", false);
		});
		
		<?php } ?>
		
		<?php if (in_array($view,$ba_carb_str)) { ?>
		
		// 2 - carb and strength only styles
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
		});
		
		<?php } ?>
		
		<?php if (in_array($view,$ba_carb_sweet)) { ?>
		
		// 3 - carb and strength only styles
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
		});
		
		<?php } ?>
		
		<?php if (in_array($view,$ba_carb_str_sweet)) { ?>
		
		// 4 - sweet, carb, and strength only styles 
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
		});
		
		<?php } ?>
		
		<?php if (in_array($view,$ba_carb_sweet_special)) { ?>
		
		// 5 - sweet and sweet only styles with special ingredients
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
			$("#specialInfo").hide("fast");
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
		});
		
		<?php } ?>
		
		<?php if (in_array($view,$ba_carb_special)) { ?>
		
		// 6 - carb with special ingredients only styles
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
			$("#specialInfo").hide("fast");
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
		});
		
		<?php } ?>
		
		<?php if (in_array($view,$ba_special_beer)) { ?>
		
		// 7 - beer styles with special ingredients only
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
			$("#specialInfo").hide("fast");
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
		});
		
		<?php } ?>
		
	<?php }  ?>
	
		<?php if ((is_array($carb_str_sweet_special_info)) && (array_key_exists($view,$carb_str_sweet_special_info))) { ?>
		// Show fields for styles that require special ingredients, carb, sweetness, strength, etc.
		
		// 1 - carb, sweetness, strength, and special styles
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
			$("#specialInfoText").html("<?php echo $carb_str_sweet_special_info["$view"]; ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
		});
		
		<?php } ?>
		
		<?php if ((is_array($carb_str_only)) && (in_array($view,$carb_str_only))) { ?>
		
		// 2 - carb and strength only styles
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
		});
		
		<?php } ?>
		
		<?php if ((is_array($sweet_carb_only)) && (in_array($view,$sweet_carb_only))) { ?>
		
		// 3 - carb and strength only styles
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
		});
		
		<?php } ?>
		
		<?php if ((is_array($sweet_carb_str_only)) && (in_array($view,$sweet_carb_str_only))) { ?>
		
		// 4 - sweet, carb, and strength only styles 
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
		});
		
		<?php } ?>
		
		<?php if ((is_array($spec_sweet_carb_only_info)) && (array_key_exists($view,$spec_sweet_carb_only_info))) { ?>
		
		// 5 - sweet and sweet only styles with special ingredients
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
			$("#specialInfoText").html("<?php echo $spec_sweet_carb_only_info["$view"]; ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
		});
		
		<?php } ?>
		
		<?php if ((is_array($spec_carb_only_info)) && (array_key_exists($view,$spec_carb_only_info))) { ?>
		
		// 6 - carb with special ingredients only styles
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
			$("#specialInfoText").html("<?php echo $spec_carb_only_info["$view"]; ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
		});
		
		<?php } ?>
		
		<?php if ((is_array($special_beer_info)) && (array_key_exists($view,$special_beer_info))) { ?>
		
		// 7 - beer styles with special ingredients only
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
			$("#specialInfoText").html("<?php echo $special_beer_info["$view"]; ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
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
					$("#specialInfoText").html("<?php echo $special_beer_info["$view"]; ?>");
					$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
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
					$("#specialInfoText").html("<?php echo $special_beer_info["$view"]; ?>");
					$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
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
					$("#specialInfoText").html("<?php echo $special_beer_info["$view"]; ?>");
					$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
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
					$("#specialInfoText").html("<?php echo $special_beer_info["$view"]; ?>");
					$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
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
					$("#specialInfoText").html("<?php echo $special_beer_info["$view"]; ?>");
					$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
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
					$("#specialInfoText").html("<?php echo $special_beer_info["$view"]; ?>");
					$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
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
					$("#specialInfoText").html("<?php echo $special_beer_info["$view"]; ?>");
					$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
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
					$("#specialInfoText").html("<?php echo $special_beer_info["$view"]; ?>");
					$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
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
					$("#specialInfoText").html("<?php echo $special_beer_info["$view"]; ?>");
					$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
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
					$("#specialInfo").show("fast");
					$("#specialInfoText").html("<?php echo $special_beer_info["$view"]; ?>");
					$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
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
					$("#specialInfoText").html("<?php echo $special_beer_info["$view"]; ?>");
					$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
				});
			<?php }	?>
		
		<?php } // if ($_SESSION['prefsStyleSet'] == "BJCP2015") ?>		
		
	<?php if (is_array($custom_entry_information)) { 
	if (array_key_exists($view,$custom_entry_information)) { 
	
		// Custom styles
		
		$explodies = explode("|",$custom_entry["$view"]);
		//print_r($explodies);
		?>
		
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
		$("#optional").hide("fast");
		$("#specialInfo").hide("fast");
		
		<?php if ($explodies[2] == 1) { ?>
			$("#brewInfo").prop("required", true);
			$("#special").show("fast");
			<?php } ?>
			
			<?php if (!empty($value)) { ?>
			$("#specialInfo").show("fast");
			$("#specialInfoText").html("<?php echo $custom_entry_information["$view"]; ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
			<?php } ?>
			
			<?php if ($explodies[3] == 1) { ?>
			$("input[name='brewMead3']").prop("required", true);
			$("#strength").show("fast");
			<?php } ?>
			
			<?php if ($explodies[4] == 1) { ?>
			$("input[name='brewMead1']").prop("required", true);
			$("#carbonation").show("fast");			
			<?php } ?>
			
			<?php if ($explodies[5] == 1) { ?>
			$("input[name='brewMead2']").prop("required", true);
			$("#sweetness").show("fast");
			<?php } ?>
		
		});
		<?php }
		}
		?>
	
<?php } // end if ($action == "edit") ?>
</script>
<?php } // end if ($section == "brew") ?>