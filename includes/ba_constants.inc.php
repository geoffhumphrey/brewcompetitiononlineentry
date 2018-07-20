<?php
$ba_category_names = array(
	"1" => "British Origin Ales",
	"2" => "Irish Origin Ales",
	"3" => "North American Origin Ales",
	"4" => "German Origin Ales",
	"5" => "Belgian And French Origin Ales",
	"6" => "Other Origin Ales",
	"7" => "European-Germanic Lagers",
	"8" => "North American Origin Lagers",
	"9" => "Other Origin Lagers",
	"10" => "International Styles",
	"11" => "Hybrid/Mixed Lagers or Ales",
	"12" => "Mead, Cider, and Perry",
	"13" => "Other Origin", // Deprecated in 2018
	"14" => "Malternative Beverages" // Deprecated in 2018
);

$ba_beer_categories = array(1,2,3,4,5,6,7,8,9,10,11,13,14);
$ba_mead_cider_categories = array(12);
$ba_all_categories = array_merge($ba_beer_categories,$ba_mead_cider_categories);

// 2015 had 14 style categories, 2018 had 12; keeping 14 for backward compatibility
$ba_category_end = 14;

// 2018 had 173 styles - padding for future
$ba_id_end = 180;

?>