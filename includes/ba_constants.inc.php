<?php
$ba_category_names = array(
	"1" => "British Origin Ales",
	"2" => "Irish Origin Ales",
	"3" => "North American Origin Ales",
	"4" => "German Origin Ales",
	"5" => "Belgian And French Origin Ales",
	"6" => "International Ale Styles",
	"7" => "European-Germanic Lager",
	"8" => "North American Lager",
	"9" => "Other Lager",
	"10" => "International Styles",
	"11" => "Hybrid/Mixed Beer",
	"12" => "Mead, Cider, & Perry",
	"13" => "Other Origin",
	"14" => "Malternative Beverages"
);

$ba_beer_categories = array(1,2,3,4,5,6,7,8,9,10,11,13,14);
$ba_mead_cider_categories = array(12);
$ba_all_categories = array_merge($ba_beer_categories,$ba_mead_cider_categories);
$ba_category_end = 14; // 2015 had 14 style categories
$ba_id_end = 180; // 2015 had 170 styles - padding by 10 for future

// First number is the cat number, second is style id
$ba_special = array("3-27","3-28","3-44","4-56","5-70","11-114","11-117","11-119","11-120","11-121","11-124","11-125","11-126","11-128","11-130","11-131","11-132","11-133","11-134","11-135","11-136","11-137","12-145","12-146","12-148","12-151","12-153","12-154","12-155","12-157","14-161","14-162","12-170");
$ba_special_beer = array("3-27","3-28","3-44","4-56","5-70","11-114","11-117","11-119","11-120","11-121","11-124","11-125","11-126","11-128","11-130","11-131","11-132","11-133","11-134","11-135","11-136","11-137","14-161","14-162","12-170");
$ba_special_mead_cider = array("12-146","12-148","12-151","12-153","12-154","12-155","12-157");
$ba_carb = array("12-140","12-141","12-142","12-143","12-144","12-145","12-146","12-147","12-148","12-149","12-150","12-151","12-152","12-153","12-154","12-155","12-156","12-157");
$ba_strength = array("12-140","12-141","12-142","12-143","12-144","12-145","12-146","12-147","12-148");
$ba_sweetness = array("12-143","12-144","12-145","12-146","12-147","12-148","12-149","12-150","12-151","12-152","12-153","12-154","12-155","12-156","12-157");
$ba_special_carb_str_sweet = array("12-145","12-146","12-148");
$ba_carb_str_sweet = array("12-143","12-144","12-147");
$ba_carb_str = array("12-140","12-141","12-142");
$ba_carb_sweet = array("12-149","12-150","12-152","12-156");
$ba_carb_special = array();
$ba_carb_sweet_special = array("12-151","12-153","12-154","12-155","12-157");

$ba_special_ids = array(27,28,44,56,70,114,117,119,120,121,124,125,126,128,130,131,132,133,134,135,136,137,145,146,148,151,153,154,155,157,161,162,170);
$ba_special_beer_ids = array(27,28,44,56,70,114,117,119,120,121,124,125,126,128,130,131,132,133,134,135,136,137,161,162,170);
$ba_special_mead_cider_ids = array(145,146,148,151,153,154,155,157);
$ba_carb_ids = array(140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157);
$ba_strength_ids = array(140,141,142,143,144,145,146,147,148);
$ba_sweetness_ids = array(143,144,145,146,147,148,149,150,151,152,153,154,155,156,157);
$ba_special_carb_str_sweet_ids = array(145,146,148);
$ba_carb_str_sweet_ids = array(143,144,147);
$ba_carb_str_ids = array(140,141,142);
$ba_carb_sweet_ids = array(149,150,152,156);
$ba_carb_special_ids = array();
$ba_carb_sweet_special_ids = array();
$ba_mead_cider = array(140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157);
$ba_mead = array(140,141,142,143,144,145,146,147,148);
$ba_cider = array(149,150,151,152,153,154,155,156,157);
?>