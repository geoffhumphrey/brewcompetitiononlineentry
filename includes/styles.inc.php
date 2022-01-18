<?php

/**
 * If you would like to add a custom style set for your competition, you
 * will need to import the style set's actual data into the styles table in the
 * BCOE&M database (style name, OG, FG, etc.).
 *
 * The information you provide below serves as a way to identify the style
 * set within the application. Scripting will not recognize the style set
 * without this master information, even if all data is present in the database.
 * 
 * There are various ways to import style data into the styles database. An easy
 * way is by filling out a comma-separated-value (CSV) file with relevant data
 * points. Common spreadsheet applications can open, edit, and save CSV files,
 * which can then be imported into the MySQL DB via online applications such as
 * phpMyAdmin, etc.
 *
 * Full directions for input of style set data and an available download of a 
 * CSV template with the correct column headings are can be found here:
 * https://www.brewcompetition.com/add-a-custom-style-set
 * 
 * In a future version, a GUI interface will be provided for building these 
 * necessary data points and mass import of styles. For now, this will provide
 * most admininstrators a relatively easy way to begin using a custom style set
 * without any further custom programming in the core code.
 * 
 * ------------------- To Add a Style Set's Master Information -------------------
 * 1) Import the style set data into the styles table in the database. See the
 *    URL above for more information.
 * 2) Uncomment the array below.
 * 3) Being mindful of the prompts, add all relevant values to the key/value 
 *    pairs. Place values in double quotes AFTER the => operator.
 *      - Example key/value pair: "style_set_short_name" => "BJCP 2015",
 *      - Look at the built-in style set arrays for examples.
 * 4) Some warnings/reminders:
 *      - DO NOT remove any array items. This will break scripting and cause errors.  
 *        If there is no value for a particular key, leave the value empty ("")
 *        or the default as noted. Otherwise, this will result in errors and 
 *        unexpected functionality.
 *      - DO NOT add or remove any commas after the array values. Again, this will 
 *        result in errors and unexpected functionality.
 *      - If any REQUIRED items are removed or their values are blank, this will 
 *        result in - say it with me - errors and unexpected functionality.
 */

$style_sets = array(

/**
 * ------------------------------------------------------------------------------
 * Uncomment and edit the array below to add the style set's master information.
 * 
 * Failure to include any REQUIRED variables will result in unexpected behavior
 * and errors. It is HIGHLY suggested you test these settings in a non-production
 * environment prior to deployment.
 * ------------------------------------------------------------------------------
 */

/*
	array(
		
		// REQUIRED. The next incremental *whole* number, NO leading zero.
		"id" => 4,
		
		// REQUIRED. Style name for system use, with NO SPACES. 
		// Must EXACTLY MATCH value of the brewStyleVersion column 
		// FOR EACH style record in the styles table of the database.
		"style_set_name" => "", 

		// REQUIRED. Entire name of the style set, spelled out.
		"style_set_long_name" => "",

		// REQUIRED. Common abbreviation of the style set.
		"style_set_short_name" => "",

		// OPTIONAL. Character used to separate the overall style
		// identifier and sub-style identifier.
		"style_set_display_separator" => "",

		// ALWAYS set to a dash. DO NOT change or you'll break stuff!
		"style_set_system_separator" => "-",

		// REQUIRED. Sub-styles identification method. Set to "0" for alpha 
		// or "1" for numeric
		"style_set_sub_style_method" => "0",
		
		// REQUIRED. Array of style category names and their corresponding idenifiers.
		// If using numbers, ALWAYS use a leading zero for single-digit numerals
		// (e.g., "01" => "European Lager", "06" => "Farmhouse Style Ales", etc.).
		// No spaces, but separators are allowed.
		"style_set_categories" => array(
			"01" => "XXX",
			"02" => "XXX",
			"03" => "XXX",
			"04" => "XXX",
			"05" => "XXX",
			"06" => "XXX",
			"07" => "XXX",
			"08" => "XXX",
			"09" => "XXX",
			"10" => "XXX",
			"11" => "XXX",
			"12" => "XXX",
			"13" => "XXX",
			"14" => "XXX",
			"15" => "XXX",
			"16" => "XXX",
			"17" => "XXX",
			"18" => "XXX",
			"19" => "XXX", 
			"20" => "XXX",  
			"21" => "XXX",
			"22" => "XXX",
			"23" => "XXX",
			"24" => "XXX",
			"25" => "XXX",
			"26" => "XXX",
			"27" => "XXX",
			"28" => "XXX",
			"29" => "XXX",
			"30" => "XXX" // The LAST item in this array should NOT have a following comma
		),

		// REQUIRED. The number/identifier of last BEER category.
		// If there are no beer categories, or if pattern uses alpha characters, use "00" (leave as is).
		"style_set_beer_end" => "00",

		// OPTIONAL. An array of MEAD-specific style numbers/identifiers.
		// Leave as is if no mead categories.
		"style_set_mead" => array(),

		// OPTIONAL. An array of CIDER-specific style numbers/identifiers.
		// Leave as is if no cider categories
		"style_set_cider" => array(),
		
		// REQURIED. MUST BE A 2-DIGIT WHOLE NUMBER WITH LEADING ZERO. NOT ALPHANUMERIC. 
		// The number of last category in the style set. If none or alpha, use "01" (leave as is).
		"style_set_category_end" => "01"
	),

 */
	
/**
 * ------------------------------------------------------------------------------
 * Built-in style set master information arrays.
 * DO NOT EDIT BELOW THIS LINE.
 * ------------------------------------------------------------------------------
 */

	/*
	array(
		"id" => 0,
		"style_set_name" => "BJCP2008",
		"style_set_long_name" => "Beer Judge Certification Program 2008",
		"style_set_short_name" => "BJCP 2008",
		"style_set_display_separator" => "",
		"style_set_system_separator" => "-",
		"style_set_sub_style_method" => "0",
		"style_set_categories" => array(
			"01" => "Light Lager",
			"02" => "Pilsner",
			"03" => "European Amber Lager",
			"04" => "Dark Lager",
			"05" => "Bock",
			"06" => "Light Hybrid Beer",
			"07" => "Amber Hybrid Beer",
			"08" => "English Pale Ale",
			"09" => "Scottish and Irish Ale",
			"10" => "American Ale",
			"11" => "English Brown Ale",
			"12" => "Porter",
			"13" => "Stout",
			"14" => "India Pale Ale (IPA)",
			"15" => "German Wheat and Rye Beer",
			"16" => "Belgian and French Ale",
			"17" => "Sour Ale",
			"18" => "Belgian Strong Ale",
			"19" => "Strong Ale",
			"20" => "Fruit Beer",
			"21" => "Spice/Herb/Vegetable Beer",
			"22" => "Smoke-Flavored and Wood-Aged Beer",
			"23" => "Specialty Beer",
			"24" => "Traditional Mead",
			"25" => "Melomel (Fruit Mead)",
			"26" => "Other Mead",
			"27" => "Standard Cider and Perry",
			"28" => "Specialty Cider and Perry"
		),
		"style_set_beer_end" => "23",
		"style_set_mead" => array("24","25","26"),
		"style_set_cider" => array("27","28"),
		"style_set_category_end" => "28"
	),

	*/

	array(
		"id" => 4,
		"style_set_name" => "BJCP2021",
		"style_set_long_name" => "Beer Judge Certification Program 2021",
		"style_set_short_name" => "BJCP 2021",
		"style_set_display_separator" => "",
		"style_set_system_separator" => "-",
		"style_set_sub_style_method" => "0",
		"style_set_categories" => array(
			"01" => "Standard American Beer",
			"02" => "International Lager",
			"03" => "Czech Lager",
			"04" => "Pale Malty European Lager",
			"05" => "Pale Bitter European Beer",
			"06" => "Amber Malty European Lager",
			"07" => "Amber Bitter European Beer",
			"08" => "Dark European Lager",
			"09" => "Strong European Beer",
			"10" => "German Wheat Beer",
			"11" => "British Bitter",
			"12" => "Pale Commonwealth Beer",
			"13" => "Brown British Beer",
			"14" => "Scottish Ale",
			"15" => "Irish Beer",
			"16" => "Dark British Beer",
			"17" => "Strong British Ale",
			"18" => "Pale American Ale",
			"19" => "Amber and Brown American Beer",
			"20" => "American Porter and Stout",
			"21" => "IPA",
			"22" => "Strong American Ale",
			"23" => "European Sour Ale",
			"24" => "Belgian Ale",
			"25" => "Strong Belgian Ale",
			"26" => "Monastic Ale",
			"27" => "Historical Beer",
			"28" => "American Wild Ale",
			"29" => "Fruit Beer",
			"30" => "Spiced Beer",
			"31" => "Alternative Fermentables Beer",
			"32" => "Smoked Beer",
			"33" => "Wood Beer",
			"34" => "Specialty Beer",
			"M1" => "Traditional Mead",
			"M2" => "Fruit Mead",
			"M3" => "Spiced Mead",
			"M4" => "Specialty Mead",
			"C1" => "Standard Cider and Perry",
			"C2" => "Specialty Cider and Perry",
			"LS" => "Local Styles"
		),
		"style_set_beer_end" => "34",
		"style_set_mead" => array("M1","M2","M3","M4"),
		"style_set_cider" => array("C1","C2"),
		"style_set_category_end" => "34"
	),

	array(
		"id" => 1,
		"style_set_name" => "BJCP2015",
		"style_set_long_name" => "Beer Judge Certification Program 2015",
		"style_set_short_name" => "BJCP 2015",
		"style_set_display_separator" => "",
		"style_set_system_separator" => "-",
		"style_set_sub_style_method" => "0",
		"style_set_categories" => array(
			"01" => "Standard American Beer",
			"02" => "International Lager",
			"03" => "Czech Lager",
			"04" => "Pale Malty European Lager",
			"05" => "Pale Bitter European Beer",
			"06" => "Amber Malty European Lager",
			"07" => "Amber Bitter European Beer",
			"08" => "Dark European Lager",
			"09" => "Strong European Beer",
			"10" => "German Wheat Beer",
			"11" => "British Bitter",
			"12" => "Pale Commonwealth Beer",
			"13" => "Brown British Beer",
			"14" => "Scottish Ale",
			"15" => "Irish Beer",
			"16" => "Dark British Beer",
			"17" => "Strong British Ale",
			"18" => "Pale American Ale",
			"19" => "Amber and Brown American Beer",
			"20" => "American Porter and Stout",
			"21" => "IPA",
			"22" => "Strong American Ale",
			"23" => "European Sour Ale",
			"24" => "Belgian Ale",
			"25" => "Strong Belgian Ale",
			"26" => "Trappist Ale",
			"27" => "Historical Beer",
			"28" => "American Wild Ale",
			"29" => "Fruit Beer",
			"30" => "Spiced Beer",
			"31" => "Alternative Fermentables Beer",
			"32" => "Smoked Beer",
			"33" => "Wood Beer",
			"34" => "Specialty Beer",
			"M1" => "Traditional Mead",
			"M2" => "Fruit Mead",
			"M3" => "Spiced Mead",
			"M4" => "Specialty Mead",
			"C1" => "Standard Cider and Perry",
			"C2" => "Specialty Cider and Perry",
			"PR" => "Provisional Styles"
		),
		"style_set_beer_end" => "34",
		"style_set_mead" => array("M1","M2","M3","M4"),
		"style_set_cider" => array("C1","C2"),
		"style_set_category_end" => "34"
	),

	array(
		"id" => 2,
		"style_set_name" => "BA",
		"style_set_long_name" => "Brewers Association",
		"style_set_short_name" => "BA",
		"style_set_display_separator" => "",
		"style_set_system_separator" => "-",
		"style_set_sub_style_method" => "1",
		"style_set_categories" => array(
			"01" => "British Origin Ales",
			"02" => "Irish Origin Ales",
			"03" => "North American Origin Ales",
			"04" => "German Origin Ales",
			"05" => "Belgian And French Origin Ales",
			"06" => "Other Origin Ales",
			"07" => "European Origin Lagers",
			"08" => "North American Origin Lagers",
			"09" => "Other Origin Lagers",
			"10" => "International Styles", // Deprecated
			"11" => "Hybrid/Mixed Lagers or Ales",
			"12" => "Mead, Cider, and Perry", 
			"13" => "Other Origin", // Deprecated
			"14" => "Malternative Beverages" // Deprecated
		),
		"style_set_beer_end" => "11",
		"style_set_mead" => array("12"),
		"style_set_cider" => array("12"),
		"style_set_category_end" => "14"
	),

	array(
		"id" => 3,
		"style_set_name" => "AABC",
		"style_set_long_name" => "Australian Amateur Brewing Championship",
		"style_set_short_name" => "AABC",
		"style_set_display_separator" => ".",
		"style_set_system_separator" => "-",
		"style_set_sub_style_method" => "1",
		"style_set_categories" => array(
			"01" => "Low Alcohol",
			"02" => "Pale Lager",
			"03" => "Amber and Dark Lager",
			"04" => "Pale Ale",
			"05" => "American Pale Ale",
			"06" => "Bitter Ale",
			"07" => "Brown Ale",
			"08" => "Porter",
			"09" => "Stout",
			"10" => "Strong Stout",
			"11" => "India Pale Ale",
			"12" => "Specialty IPA",
			"13" => "Wheat and Rye Ale",
			"14" => "Sour Ale",
			"15" => "Belgian Ale",
			"16" => "Strong Ales and Lagers",
			"17" => "Fruit/Spice/Herb/Vegetable Beer",
			"18" => "Specialty Beer",
			"19" => "Mead",
			"20" => "Cider"
		),
		"style_set_beer_end" => "18",
		"style_set_mead" => array("19"),
		"style_set_cider" => array("20"),
		"style_set_category_end" => "20"
	)
);
?>