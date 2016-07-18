<?php 

/*
Checked Single
2016-06-06
*/

if (($section == "admin") && ($go == "styles") && ($action != "default")) { 

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
		
		
		
		if ( 
			$("#type").val() == "00-A"){
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
			$("#specialInfo").hide("fast");
			$("#strengthIPA").hide("fast");
			$("#strengthSaison").hide("fast");
			$("#darkLightColor").hide("fast");
			$("#sweetnessLambic").hide("fast");
			$("#carbLambic").hide("fast");
			$("#BDGColor").hide("fast");
			
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
				
		// Special Beer
		<?php foreach ($special_beer_info as $key => $value) { ?>
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
			<?php if ($_SESSION['prefsStyleSet'] == "BJCP2015") { 
				if (($key == "09-A") || ($key == "10-C") || ($key == "07-C")){
			?>
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
		<?php } ?>
		
		// Styles requiring special ing, strength, sweetness, and carb
		<?php foreach ($carb_str_sweet_special_info as $key => $value) { ?>
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
			
			$("#brewInfo").prop("required", true);
			$("input[name='brewMead1']").prop("required", true);
			$("input[name='brewMead2']").prop("required", true);
			$("input[name='brewMead3']").prop("required", true);
			
			$("#specialInfoText").html("<?php echo $value; ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'>Style <?php echo str_replace("-","",$key); ?></a>");
		}
		<?php } ?>
		
		// Styles requiring strength and carb only
		<?php foreach ($carb_str_only as $value) { ?>
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
		
		<?php foreach ($sweet_carb_only as $value) { ?>
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
		<?php foreach ($sweet_carb_str_only as $value) { ?>
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
		<?php foreach ($spec_sweet_carb_only_info as $key => $value) { ?>
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
			
			$("input[name='brewMead1']").prop("required", true);
			$("input[name='brewMead2']").prop("required", true);
			
			$("#specialInfoText").html("<?php echo $value; ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'>Style <?php echo str_replace("-","",$key); ?></a>");
		}
		<?php } ?>
		
		// Styles requiring special ingredients and carbonation
		<?php foreach ($spec_carb_only_info as $key => $value) { ?>
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
			
			$("#specialInfo").show("fast");
			$("#specialInfoText").html("<?php echo $value; ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'>Style <?php echo str_replace("-","",$key); ?></a>");
		}
		<?php } ?>
		
		// Custom styles
		<?php 
		if (isset($custom_entry_information)) {
		foreach ($custom_entry_information as $key => $value) { 
		$explodies = explode("|",$custom_entry["$key"]);
		//print_r($explodies);
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
			
			<?php if ($explodies[2] == 1) { ?>
			$("#brewInfo").prop("required", true);
			$("#special").show("fast");
			<?php } ?>
			
			<?php if (!empty($value)) { ?>
			$("#specialInfo").show("fast");
			$("#specialInfoText").html("<?php echo $value; ?>");
			$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $key; ?>'>Style <?php echo str_replace("-","",$key); ?></a>");
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


<?php if ($action == "edit") { ?>

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
	
	<?php if (array_key_exists($view,$carb_str_sweet_special_info)) { ?>
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
		$("input[name='brewMead1']").prop("required", true);
		$("input[name='brewMead2']").prop("required", true);
		$("input[name='brewMead3']").prop("required", true);
		
		$("#specialInfo").show("fast");
		$("#specialInfoText").html("<?php echo $carb_str_sweet_special_info["$view"]; ?>");
		$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
		
	});
	<?php } ?>
	
	<?php if (in_array($view,$carb_str_only)) { ?>
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
	
	<?php if (in_array($view,$sweet_carb_only)) { ?>
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
	
	<?php if (in_array($view,$sweet_carb_str_only)) { ?>
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
	
	<?php if (array_key_exists($view,$spec_sweet_carb_only_info)) { ?>
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
	
	<?php if (array_key_exists($view,$spec_carb_only_info)) { ?>
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
	
	<?php if (array_key_exists($view,$special_beer_info)) { ?>
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
				$("#specialInfo").show("fast");
				$("#specialInfoText").html("<?php echo $special_beer_info["$view"]; ?>");
				$("#specialInfoName").html("<a href='#' data-tooltip='true' title='Click for specifics for this style.' data-toggle='modal' data-target='#<?php echo $view; ?>'>Style <?php echo str_replace("-","",$view); ?></a>");
			});
		<?php } elseif ($view == "23-F") { ?>
			$(document).ready(function() {
				$("#special").show("fast");
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
	
	<?php if ((isset($custom_entry_information)) && (array_key_exists($view,$custom_entry_information))) { 
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
		<?php } ?>
	
<?php } ?>
</script>
<?php } // end if ($section == "brew") ?>