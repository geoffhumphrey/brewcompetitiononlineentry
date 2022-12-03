<?php
$update_table = $prefix."styles";

/**
 * BA 2021 Updates
 * @see https://www.brewersassociation.org/press-releases/brewers-association-releases-2021-beer-style-guidelines/
 */

if (!check_new_style("11","181","Kentucky Common")) {

	$data =   array('brewStyleGroup' => '11','brewStyleNum' => '181','brewStyle' => 'Kentucky Common','brewStyleCategory' => 'Hybrid/Mixed Lagers or Ales','brewStyleVersion' => 'BA','brewStyleOG' => '1.040','brewStyleOGMax' => '1.055','brewStyleFG' => '1.010','brewStyleFGMax' => '1.018','brewStyleABV' => '4','brewStyleABVMax' => '5.5','brewStyleIBU' => '15','brewStyleIBUMax' => '30','brewStyleSRM' => '11','brewStyleSRMMax' => '20','brewStyleType' => '1','brewStyleInfo' => 'This American-born regional style proliferated around Louisville, Kentucky, from the Civil War era until Prohibition. Corn grits or flakes were commonly used at a rate or 25-35% of the total grist. Minerally attributes resulted from the use of hard brewing water. These beers were consumed very young, going from brewhouse to consumer in as little as one week. Early 20th century brewing literature mentions a slight tartness developing during fermentation as a characteristic attribute of this style. If tartness is present in modern versions, it should be at very low levels.','brewStyleLink' => 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/','brewStyleActive' => 'N','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => '2021','brewStyleComEx' => NULL,'brewStyleEntry' => NULL);
	if ($db_conn->insert ($update_table, $data)) $output_off_sched_update .= "<li>2021 BA style Kentucky Common added.</li>";
	else {
		$output_off_sched_update .= "<li>2021 BA style Kentucky Common NOT added. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
		$error_count += 1;
	}

}

if (!check_new_style("06","182","New Zealand-Style Pale Ale")) {

	$data = array('brewStyleGroup' => '06','brewStyleNum' => '182','brewStyle' => 'New Zealand-Style Pale Ale','brewStyleCategory' => 'Other Origin Ales','brewStyleVersion' => 'BA','brewStyleOG' => '1.04','brewStyleOGMax' => '1.052','brewStyleFG' => '1.006','brewStyleFGMax' => '1.01','brewStyleABV' => '4','brewStyleABVMax' => '6','brewStyleIBU' => '15','brewStyleIBUMax' => '40','brewStyleSRM' => '3','brewStyleSRMMax' => '9','brewStyleType' => '1','brewStyleInfo' => 'Overall impression is a well-integrated easy drinking, refreshing pale ale style with distinctive fruity hop aromas and flavors. Diacetyl is absent in these beers. DMS should not be present.','brewStyleLink' => 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/','brewStyleActive' => 'N','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => '2021','brewStyleComEx' => NULL,'brewStyleEntry' => NULL);
	if ($db_conn->insert ($update_table, $data)) $output_off_sched_update .= "<li>2021 BA style New Zealand-Style Pale Ale added.</li>";
	else {
		$output_off_sched_update .= "<li>2021 BA style New Zealand-Style Pale Ale NOT added. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
		$error_count += 1;
	}
	
}

if (!check_new_style("06","183","New Zealand-Style India Pale Ale")) {

	$data = array('brewStyleGroup' => '06','brewStyleNum' => '182','brewStyle' => 'New Zealand-Style India Pale Ale','brewStyleCategory' => 'Other Origin Ales','brewStyleVersion' => 'BA','brewStyleOG' => '1.060','brewStyleOGMax' => '1.070','brewStyleFG' => '1.010','brewStyleFGMax' => '1.016','brewStyleABV' => '6.3','brewStyleABVMax' => '7.5','brewStyleIBU' => '50','brewStyleIBUMax' => '70','brewStyleSRM' => '6','brewStyleSRMMax' => '12','brewStyleType' => '1','brewStyleInfo' => 'Diacetyl and DMS should not be present. The use of water with high mineral content may result in a crisp, dry beer rather than a malt-accentuated version. Hop attributes are dominant and balanced with malt character.','brewStyleLink' => 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/','brewStyleActive' => 'N','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => '2021','brewStyleComEx' => NULL,'brewStyleEntry' => NULL);
	if ($db_conn->insert ($update_table, $data)) $output_off_sched_update .= "<li>2021 BA style New Zealand-Style India Pale Ale added.</li>";
	else {
		$output_off_sched_update .= "<li>2021 BA style New Zealand-Style India Pale Ale NOT added. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
		$error_count += 1;
	}
	
}

if (!check_new_style("05","184","Belgian-Style Session Ale")) {

	$data = array('brewStyleGroup' => '05','brewStyleNum' => '184','brewStyle' => 'Belgian-Style Session Ale','brewStyleCategory' => 'Belgian and French Origin Ale','brewStyleVersion' => 'BA','brewStyleOG' => '1.018','brewStyleOGMax' => '1.040','brewStyleFG' => '1.002','brewStyleFGMax' => '1.010','brewStyleABV' => '2.1','brewStyleABVMax' => '5','brewStyleIBU' => '5','brewStyleIBUMax' => '35','brewStyleSRM' => NULL,'brewStyleSRMMax' => NULL,'brewStyleType' => '1','brewStyleInfo' => 'Beers in this category recognize the uniqueness and traditions of Belgian brewing, but do not hew to any other Belgian-style categories defined in these guidelines. The most notable characteristic that these beers share is a modest alcohol content ranging from 2.1% – 5% abv. These beers can be lower gravity formulations of their own, or can be produced from second run wort from the production of higher gravity beers. Balance is a key component when assessing these beers. Wood-aged or fruited versions will exhibit attributes of wood-aging or fruit(s) in harmony with overall flavor profile.','brewStyleLink' => 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/','brewStyleActive' => 'N','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '1','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => '2021','brewStyleComEx' => NULL,'brewStyleEntry' => NULL);
	if ($db_conn->insert ($update_table, $data)) $output_off_sched_update .= "<li>2021 BA style Belgian-Style Session Ale added.</li>";
	else {
		$output_off_sched_update .= "<li>2021 BA style Belgian-Style Session Ale NOT added. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
		$error_count += 1;
	}
	
}

/**
 * BA 2022 Updates
 * @see https://www.brewersassociation.org/press-releases/brewers-association-updates-beer-style-guidelines-to-add-clarity-across-multiple-categories/
 */

// Update Experimental India Pale Ale
$data = array(
	'brewStyleInfo' => 'Beers in this category recognize the cutting edge of IPA brewing around the world. Experimental India Pale Ales are either 1) any of White, Red, Brown, Brut (fermented with champagne yeasts), Brett (fermented with Brettanomyces), Lager (fermented with lager yeasts), or many other IPA or Imperial IPA types or combinations thereof currently in production, and fruited or spiced versions of these, or 2) fruited, spiced, field (flavored with vegetables other than chili peppers), wood- and barrel-aged, or other elaborated versions of classic American, Juicy or Hazy, Imperial, British, or any other IPA categories. They range widely in color, hop, and malt intensity and attributes, hop bitterness, balance, alcohol content, body, and overall flavor experience. Dark versions of India Pale Ale that do not meet the specifications for American-Style Black Ale may be considered Experimental India Pale Ale. Fruited and spiced versions exhibit attributes typical of those ingredients, in harmony with hop impression and overall flavor experience. Lactose may be used to enhance body and balance, but should not lend to, or overwhelm, the flavor character of these beers. Classifying these beers can be complex. India Pale Ales brewed with honey are categorized here. Spiced or fruited versions of these beers, or those made with unusual fermentables or honey, are categorized as Experimental India Pale Ale. India Pale Ales flavored with nuts, coconut or other vegetables are categorized here rather than as Field Beers. However, within the framework of these guidelines, all beers brewed with chili peppers are categorized as Chili Beers; India Pale Ale brewed with chili peppers in any form are categorized as Chili Beer.',
	'brewStyleTags' => '2022'
);
$db_conn->where ('brewStyle', 'Experimental India Pale Ale');
if ($db_conn->update ($update_table, $data)) $output_off_sched_update .= "<li>2022 BA Experimental India Pale Ale style updated.</li>";
else {
	$output_off_sched_update .= "<li>2022 BA Experimental India Pale Ale style NOT updated. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
	$error_count += 1;
}

// Update Session Beer
$data = array(
	'brewStyleOG' => '1.008', 
	'brewStyleOGMax' => '1.040', 
	'brewStyleABV' => '0.5', 
	'brewStyleSRMMax' => '4',
	'brewStyleTags' => '2022',
	'brewStyleInfo' => 'Beers in this category recognize the cutting edge of IPA brewing around the world. Experimental India Pale Ales are either 1) any of White, Red, Brown, Brut (fermented with champagne yeasts), Brett (fermented with Brettanomyces), Lager (fermented with lager yeasts), or many other IPA or Imperial IPA types or combinations thereof currently in production, and fruited or spiced versions of these, or 2) fruited, spiced, field (flavored with vegetables other than chili peppers), wood- and barrel-aged, or other elaborated versions of classic American, Juicy or Hazy, Imperial, British, or any other IPA categories. They range widely in color, hop, and malt intensity and attributes, hop bitterness, balance, alcohol content, body, and overall flavor experience. Dark versions of India Pale Ale that do not meet the specifications for American-Style Black Ale may be considered Experimental India Pale Ale. Fruited and spiced versions exhibit attributes typical of those ingredients, in harmony with hop impression and overall flavor experience. Lactose may be used to enhance body and balance, but should not lend to, or overwhelm, the flavor character of these beers. Classifying these beers can be complex. India Pale Ales brewed with honey are categorized here. Spiced or fruited versions of these beers, or those made with unusual fermentables or honey, are categorized as Experimental India Pale Ale. India Pale Ales flavored with nuts, coconut or other vegetables are categorized here rather than as Field Beers. However, within the framework of these guidelines, all beers brewed with chili peppers are categorized as Chili Beers; India Pale Ale brewed with chili peppers in any form are categorized as Chili Beer.',
);
$db_conn->where ('brewStyle', 'Experimental India Pale Ale');
$db_conn->where ('brewStyleVersion', 'BA');
if ($db_conn->update ($update_table, $data)) $output_off_sched_update .= "<li>2022 BA Session Beer style updated.</li>";
else {
	$output_off_sched_update .= "<li>2022 BA Session Beer style NOT updated. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
	$error_count += 1;
}

// Update Session India Pale Ale
$data = array(
	'brewStyleOG' => '1.008', 
	'brewStyleOGMax' => '1.052', 
	'brewStyleFG' => '1.005', 
	'brewStyleFGMax' => '1.014', 
	'brewStyleABV' => '0.5', 
	'brewStyleABVMax' => '5', 
	'brewStyleIBU' => '20', 
	'brewStyleIBUMax' => '55', 
	'brewStyleSRM' => '3', 
	'brewStyleSRMMax' => '12',
	'brewStyleTags' => '2022',
	'brewStyleInfo' => 'Session India Pale Ales are lower alcohol versions of any of the various American, Juicy or Hazy, British or other India Pale Ale styles from around the world. Balance and high drinkability are key to a successful Session India Pale Ale. Hop aroma and flavor attributes hew to the underlying India Pale Ale style being made at lower strength. Beers exceeding 5.0% abv are not considered Session India Pale Ales. Beers under 5.0% abv (4.0% abw) which meet the criteria for another classic or traditional style category are not considered Session India Pale Ales. An India Pale Ale made to alcohol content below 0.5% abv (0.4% abw) is categorized as a Non-Alcohol Malt Beverage.'
);
$db_conn->where ('brewStyle', 'Session India Pale Ale');
$db_conn->where ('brewStyleVersion', 'BA');
if ($db_conn->update ($update_table, $data)) $output_off_sched_update .= "<li>2022 BA Session India Pale Ale style updated.</li>";
else {
	$output_off_sched_update .= "<li>2022 BA Session India Pale Ale style NOT updated. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
	$error_count += 1;
}

// Update Belgian-Style Gueuze Lambic
$data = array(
	'brewStyle' => 'Traditional Belgian-Style Gueuze', 
	'brewStyleOGMax' => '1.065', 
	'brewStyleABV' => '5', 
	'brewStyleABVMax' => '8',
	'brewStyleTags' => '2022',
	'brewStyleInfo' => 'Gueuze represents blends of aged and newly fermenting young Lambics. These unﬂavored blended and secondary fermented beers may range from very dry or mildly sweet. They are characterized by intense fruity ester, sour, and acidic attributes which only result from spontaneous fermentation. Diacetyl should not be present. Characteristic horsey, goaty, leathery and phenolic aromas and ﬂavors derived from Brettanomyces yeast are often present at moderate levels. Vanillin and other wood-derived ﬂavors may range from absent to present at up to low-medium levels. Carbonation can range from absent to high.'
);
$db_conn->where ('brewStyle', 'Belgian-Style Gueuze Lambic');
$db_conn->where ('brewStyleVersion', 'BA');
if ($db_conn->update ($update_table, $data)) $output_off_sched_update .= "<li>2022 BA Traditional Belgian-Style Gueuze style updated.</li>";
else {
	$output_off_sched_update .= "<li>2022 BA Traditional Belgian-Style Gueuze style NOT updated. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
	$error_count += 1;
}

// Update Bohemian-Style Pilsener
$data = array(
	'brewStyleOGMax' => '1.052', 
	'brewStyleABV' => '4.1', 
	'brewStyleABVMax' => '5.1', 
	'brewStyleIBU' => '25', 
	'brewStyleSRMMax' => '6',
	'brewStyleTags' => '2022',
	'brewStyleInfo' => 'Traditional Bohemian Pilseners are medium bodied, and they can be as dark as a light amber color. This style balances moderate bitterness and noble-type hop aroma and flavor with a malty, slightly sweet, medium body. Extremely low levels of diacetyl and low levels of sweet corn-like dimethylsulfide (DMS) character, if perceived, are characteristic of this style and both may accent malt aroma. A toasted-, biscuit-like, bready malt character along with low levels of sulfur compounds may be evident. There should be no chill haze. Its head should be dense and rich. The upper limit of original gravity of versions brewed in Czech Republic is 12.99 °Plato or 1.052 specific gravity. Esters are usually not present, but if present should be extremely low, at the limit of perception. Very low levels of diacetyl, if present, are acceptable and may accent malt character. Low levels of sulfur compounds may be present. DMS and acetaldehyde should not be present.'
);
$db_conn->where ('brewStyle', 'Bohemian-Style Pilsener');
$db_conn->where ('brewStyleVersion', 'BA');
if ($db_conn->update ($update_table, $data)) $output_off_sched_update .= "<li>2022 BA Bohemian-Style Pilsener style updated.</li>";
else {
	$output_off_sched_update .= "<li>2022 BA Bohemian-Style Pilsener style NOT updated. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
	$error_count += 1;
}

// Update American-Style India Pale Lager
$data = array(
	'brewStyleTags' => '2022',
	'brewStyleInfo' => 'This style of beer should exhibit the fresh character of hops. Some versions may be brewed with corn, rice, or other adjunct grains, and may exhibit attributes typical of those adjuncts.'
);
$db_conn->where ('brewStyle', 'American-Style India Pale Lager');
$db_conn->where ('brewStyleVersion', 'BA');
if ($db_conn->update ($update_table, $data)) $output_off_sched_update .= "<li>2022 BA American-Style India Pale Lager style updated.</li>";
else {
	$output_off_sched_update .= "<li>2022 BA American-Style India Pale Lager style NOT updated. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
	$error_count += 1;
}

// Update Specialty Beer
$data = array(
	'brewStyleOGMax' => '1.140', 
	'brewStyleIBU' => '1', 
	'brewStyleInfo' => 'Within the framework of these guidelines, Specialty Beer refers to beers brewed with atypical sources of fermentable sugar such as grains, tubers, starches, syrups, or other sources which contribute to alcohol content. The hallmark of Specialty Beers are the distinctive sensory attributes arising from these special fermentable ingredients, which should be present in the aroma, ﬂavor, and overall balance of the beer. Examples could include maple syrup, agave, potatoes, wild rice, or any other sources of carbohydrate not commonly used in modern beer styles. Beers containing wheat are categorized in one of several wheat beer styles. The use of rice or corn would not normally be considered unusual since these adjuncts are commonly used in beer production. However, beers made with rice or corn varieties which imbue highly distinctive flavor attributes might be categorized as Specialty Beers. Classifying these beers can be complex. Beers brewed with honey or rye are categorized as Specialty Honey Beer or Rye Beer respectively. Beers made with unusual fermentables, which also contain spices, fruits, or other ingredients, and which therefore represent a combination of two or more hybrid beer styles, are categorized as Experimental Beers. Within the framework of these guidelines, nuts generally impart much more ﬂavor than fermentables, and beers containing nuts are categorized as Field Beer. Likewise, within the framework of these guidelines, coconut is deﬁned as a vegetable and beers containing coconut are categorized as Field Beer. Beers brewed with roots, seeds, ﬂowers etc. which exhibit herbal or spicy characters are categorized as Herb and Spice Beers. While beers brewed with fruits or vegetables may derive fermentable carbohydrate from those sources, they are most appropriately categorized as Fruit Beer or Field Beer respectively. Beers representing various India Pale Ale or Imperial India Pale Ale styles brewed with unusual fermentables, and which may or may not also contain fruit(s), spice(s) or other ingredients, are categorized as Experimental India Pale Ale.', 
	'brewStyleTags' => '2022'
);
$db_conn->where ('brewStyle', 'Specialty Beer');
$db_conn->where ('brewStyleVersion', 'BA');
if ($db_conn->update ($update_table, $data)) $output_off_sched_update .= "<li>2022 BA Specialty Beer style updated.</li>";
else {
	$output_off_sched_update .= "<li>2022 BA Specialty Beer style NOT updated. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
	$error_count += 1;
}

// Update Chili Pepper Beer
$data = array(
	'brewStyleOG' => '1.030', 
	'brewStyleOGMax' => '1.11', 
	'brewStyleFG' => '1.006', 
	'brewStyleFGMax' => '1.030', 
	'brewStyleABV' => '2.5', 
	'brewStyleABVMax' => '13.3', 
	'brewStyleIBU' => '5', 
	'brewStyleIBUMax' => '7', 
	'brewStyleSRM' => '5', 
	'brewStyleSRMMax' => '50', 
	'brewStyleInfo' => 'Chili pepper aroma and ﬂavor attributes should be harmonious with the underlying beer style. Chili pepper character may be expressed as vegetal, spicy, or hot on the palate. Chili Beer is any beer using chili peppers for ﬂavor, aroma, or heat. Chili character can range from subtle to intense. Chili pepper aroma may or may not be present. Within the framework of these guidelines, all beers containing chili peppers are categorized as Chili Beers. A beer made with chili peppers which represents more than one style, such as a chili beer with chocolate for example, should nonetheless be categorized as Chili Beer rather than as Experimental Beer.', 
	'brewStyleTags' => '2022'
);
$db_conn->where ('brewStyle', 'Chili Pepper Beer');
$db_conn->where ('brewStyleVersion', 'BA');
if ($db_conn->update ($update_table, $data)) $output_off_sched_update .= "<li>2022 BA Chili Pepper Beer style updated.</li>";
else {
	$output_off_sched_update .= "<li>2022 BA Chili Pepper Beer style NOT updated. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
	$error_count += 1;
}
?>