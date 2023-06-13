<?php
$update_table = $prefix."styles";

/**
 * BA 2023 Updates
 * @see https://www.brewersassociation.org/association-news/brewers-association-releases-2023-beer-style-guidelines/
 */

// Update all 07 styles with the correct category name
$update_table = $prefix."styles";
$data = array(
	'brewStyleCategory' => 'European Origin Lagers',
	'brewStyleTags' => '2023'
);
$db_conn->where ('brewStyleVersion', 'BA');
$db_conn->where ('brewStyleGroup', '07');
if ($db_conn->update ($update_table, $data)) $output_off_sched_update .= "<li>BA European Origin Lagers styles updated.</li>";
else {
	$output_off_sched_update .= "<li>BA European Origin Lagers styles NOT updated. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
	$error_count += 1;
}

// Update European-Style Dark Lager
$update_table = $prefix."styles";
$data = array(
	'brewStyle' => 'European-Style Dark Lager',
	'brewStyleOG' => '1.048',
	'brewStyleOGMax' => '1.056',
	'brewStyleFG' => '1.014',
	'brewStyleFGMax' => '1.018',
	'brewStyleABV' => '4.8',
	'brewStyleABVMax' => '5.3',
	'brewStyleIBU' => '20',
	'brewStyleIBUMax' => '35',
	'brewStyleSRM' => '15',
	'brewStyleSRMMax' => '24',
	'brewStyleType' => '1',
	'brewStyleInfo' => 'These beers offer a ﬁne balance of sweet maltiness and hop bitterness.',
	'brewStyleTags' => '2023'
);
$db_conn->where ('brewStyleVersion', 'BA');
$db_conn->where ('brewStyleGroup', '07');
$db_conn->where ('brewStyleNum', '90');
if ($db_conn->update ($update_table, $data)) $output_off_sched_update .= "<li>European-Style Dark Lager style updated.</li>";
else {
	$output_off_sched_update .= "<li>European-Style Dark Lager style NOT updated. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
	$error_count += 1;
}

// Update American-Style India Pale Lager
$data = array(
	'brewStyleTags' => '2023',
	'brewStyleOG' => '1.050',
	'brewStyleOGMax' => '1.065',
	'brewStyleFG' => '1.006',
	'brewStyleFGMax' => '1.016',
	'brewStyleABV' => '5.6',
	'brewStyleABVMax' => '7.9',
	'brewStyleIBU' => '30',
	'brewStyleIBUMax' => '70',
	'brewStyleSRM' => '2.5',
	'brewStyleSRMMax' => '6',
	'brewStyleInfo' => 'This style of beer should exhibit the fresh character of hops. Some versions may be brewed with corn, rice, or other adjunct grains, and may exhibit attributes typical of those adjuncts.'
);
$db_conn->where ('brewStyleVersion', 'BA');
$db_conn->where ('brewStyleGroup', '08');
$db_conn->where ('brewStyleNum', '178');
if ($db_conn->update ($update_table, $data)) $output_off_sched_update .= "<li>2022 BA American-Style India Pale Lager style updated.</li>";
else {
	$output_off_sched_update .= "<li>2022 BA American-Style India Pale Lager style NOT updated. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
	$error_count += 1;
}

if (!check_new_style("03","184","West Coast-Style India Pale Ale")) {

	$data = array(
		'brewStyleGroup' => '03',
		'brewStyleNum' => '184',
		'brewStyle' => 'West Coast-Style India Pale Ale',
		'brewStyleCategory' => 'North American Origin Ales',
		'brewStyleVersion' => 'BA',
		'brewStyleOG' => '1.055',
		'brewStyleOGMax' => '1.070',
		'brewStyleFG' => '1.005',
		'brewStyleFGMax' => '1.016',
		'brewStyleABV' => '6.3',
		'brewStyleABVMax' => '7.5',
		'brewStyleIBU' => '50',
		'brewStyleIBUMax' => '75',
		'brewStyleSRM' => '11',
		'brewStyleSRMMax' => '20',
		'brewStyleType' => '1',
		'brewStyleInfo' => 'These beers are highly attenuated with an assertive hop character and a dry, crisp finish. While the West Coast India Pale Ale style has been around for some time, the style itself has progressed over time from original inception to modern day examples – this guideline serves to align directly with modern-day examples of the style.',
		'brewStyleLink' => 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/',
		'brewStyleActive' => 'N',
		'brewStyleOwn' => 'bcoe',
		'brewStyleReqSpec' => '0',
		'brewStyleStrength' => '0',
		'brewStyleCarb' => '0',
		'brewStyleSweet' => '0',
		'brewStyleTags' => '2023',
		'brewStyleComEx' => NULL,
		'brewStyleEntry' => NULL
	);
	if ($db_conn->insert ($update_table, $data)) $output_off_sched_update .= "<li>2023 BA style West Coast-Style India Pale Ale added.</li>";
	else {
		$output_off_sched_update .= "<li>2023 BA style West Coast-Style India Pale Ale NOT added. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
		$error_count += 1;
	}

}

if (!check_new_style("11","185","Dessert Stout or Pastry Stout")) {

	$data = array(
		'brewStyleGroup' => '11',
		'brewStyleNum' => '185',
		'brewStyle' => 'Dessert Stout or Pastry Stout',
		'brewStyleCategory' => 'Hybrid/Mixed Lagers or Ales',
		'brewStyleVersion' => 'BA',
		'brewStyleOG' => '1.080',
		'brewStyleOGMax' => '1.120',
		'brewStyleFG' => '1.020',
		'brewStyleFGMax' => '1.060',
		'brewStyleABV' => '7',
		'brewStyleABVMax' => '13',
		'brewStyleIBU' => '20',
		'brewStyleIBUMax' => '65',
		'brewStyleSRM' => '35',
		'brewStyleSRMMax' => '50',
		'brewStyleType' => '1',
		'brewStyleInfo' => 'Beers in this category build on a strong dark beer base and incorporate culinary ingredients to create rich, sweet flavor profiles mimicking the character of desserts, pastries or candies. Examples of culinary ingredients used in these beers include, but are not limited to, chocolate, coffee, coconut, vanilla, maple syrup, peanut butter and marshmallow as well as fruits, nuts and spices. The addition of sugars from any source may contribute to the pronounced sweetness of these beers. While this category may overlap several other styles defined in these guidelines such as Chocolate or Cocoa Beers, Coffee Beers, Field beers, and others, the combination of a dark beer base, elevated alcohol content and rich, sweet, dessert-like flavor profiles sets this style apart as a unique entity.',
		'brewStyleLink' => 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/',
		'brewStyleActive' => 'N',
		'brewStyleOwn' => 'bcoe',
		'brewStyleReqSpec' => '0',
		'brewStyleStrength' => '0',
		'brewStyleCarb' => '0',
		'brewStyleSweet' => '0',
		'brewStyleTags' => '2023',
		'brewStyleComEx' => NULL,
		'brewStyleEntry' => NULL
	);
	if ($db_conn->insert ($update_table, $data)) $output_off_sched_update .= "<li>2023 BA style Dessert Stout or Pastry Stout added.</li>";
	else {
		$output_off_sched_update .= "<li>2023 BA style Dessert Stout or Pastry Stout NOT added. <strong class=\"text-warning\">Error: ".$db_conn->getLastError()."</strong></li>";
		$error_count += 1;
	}

}
?>