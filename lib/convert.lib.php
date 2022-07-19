<?php 

// Map BJCP2008 Styles to BJCP2015 Styles

function bjcp_map_2008_2015($style, $method, $prefix, $id) {

	$return = "";

	switch($style) {

		// 1
		case "01A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","1","01","A","American Light Lager",$id);
			if ($method == 1) $return = "01A";
		break;

		case "01B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","1","01","B","American Lager",$id);
			if ($method == 1) $return = "01B";
		break;

		case "01C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","2","02","A","International Pale Lager",$id);
			if ($method == 1) $return = "02A";
		break;

		case "01D":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","4","04","A","Munich Helles",$id);
			if ($method == 1) $return = "04A";
		break;

		case "01E":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","5","05","C","Helles Exportbier",$id);
			if ($method == 1) $return = "05C";
		break;

		// 2
		case "02A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","5","05","D","German Pils",$id);
			if ($method == 1) $return = "05D";
		break;

		case "02B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","3","03","B","Czech Premium Pale Lager",$id);
			if ($method == 1) $return = "03B";
		break;

		case "02C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s', brewInfo='%s' WHERE id='%s'",$prefix."brewing","27","27","A","Historical Beer","Pre-Phohibition Lager",$id);
			if ($method == 1) $return = "27A";
		break;

		// 3
		case "03A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","7","07","A","Vienna Lager",$id);
			if ($method == 1) $return = "07A";
		break;

		case "03B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","6","06","A","Marzen",$id);
			if ($method == 1) $return = "06A";
		break;

		// 4
		case "04A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","2","02","C","International Dark Lager",$id);
			if ($method == 1) $return = "02C";
		break;

		case "04B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","8","08","A","Munich Dunkel",$id);
			if ($method == 1) $return = "08A";
		break;

		case "04C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","8","08","B","Schwarzbier",$id);
			if ($method == 1) $return = "08B";
		break;

		// 5
		case "05A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","4","04","C","Helles Bock",$id);
			if ($method == 1) $return = "04C";
		break;

		case "05B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","6","06","C","Dunkels Bock",$id);
			if ($method == 1) $return = "06C";
		break;

		case "05C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","9","09","A","Doppelbock",$id);
			if ($method == 1) $return = "09A";
		break;

		case "05D":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","9","09","B","Doppelbock",$id);
			if ($method == 1) $return = "09B";
		break;

		// 6
		case "06A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","1","01","C","Cream Ale",$id);
			if ($method == 1) $return = "01C";
		break;

		case "06B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","18","18","A","Blonde Ale",$id);
			if ($method == 1) $return = "18A";
		break;

		case "06C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","5","05","B","Kolsch",$id);
			if ($method == 1) $return = "05B";
		break;

		case "06D":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","1","01","D","American Wheat Beer",$id);
			if ($method == 1) $return = "01D";
		break;

		// 7
		case "07A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","2","02","B","International Amber Lager",$id);
			if ($method == 1) $return = "02B";
		break;

		case "07B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","19","19","B","California Common",$id);
			if ($method == 1) $return = "19B";
		break;

		case "07C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","7","07","B","Altbier",$id);
			if ($method == 1) $return = "07B";
		break;

		// 8
		case "08A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","A","Ordinary Bitter",$id);
			if ($method == 1) $return = "11A";
		break;

		case "08B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","B","Best Bitter",$id);
			if ($method == 1) $return = "11B";
		break;

		case "08C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","C","Strong Bitter",$id);
			if ($method == 1) $return = "11C";
		break;

		// 9
		case "09A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","14","14","A","Scottish Light",$id);
			if ($method == 1) $return = "14A";
		break;

		case "09B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","14","14","B","Scottish Heavy",$id);
			if ($method == 1) $return = "14B";
		break;

		case "09C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","14","14","C","Scottish Export",$id);
			if ($method == 1) $return = "14C";
		break;

		case "09D":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","15","15","A","Irish Red Ale",$id);
			if ($method == 1) $return = "15A";
		break;

		case "09E":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","17","17","C","Wee Heavy",$id);
			if ($method == 1) $return = "17C";
		break;

		// 10
		case "10A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","18","18","B","American Pale Ale",$id);
			if ($method == 1) $return = "18B";
		break;

		case "10B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","19","19","A","American Amber Ale",$id);
			if ($method == 1) $return = "19A";
		break;

		case "10C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","19","19","B","American Brown Ale",$id);
			if ($method == 1) $return = "19B";
		break;

		// 11
		case "11A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","13","13","A","Dark Mild",$id);
			if ($method == 1) $return = "13A";
		break;

		case "11B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s', brewInfo='%s' WHERE id='%s'",$prefix."brewing","27","27","B","Historical Beer","London Brown Ale",$id);
			if ($method == 1) $return = "27B";
		break;

		case "11C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","13","13","B","British Brown Ale",$id);
			if ($method == 1) $return = "13B";
		break;

		// 12
		case "12A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","13","13","C","English Porter",$id);
			if ($method == 1) $return = "13C";
		break;

		case "12B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","B","American Porter",$id);
			if ($method == 1) $return = "20B";
		break;

		case "12C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","9","09","C","Baltic Porter",$id);
			if ($method == 1) $return = "09C";
		break;

		// 13
		case "13A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","15","15","B","Irish Stout",$id);
			if ($method == 1) $return = "15B";
		break;

		case "13B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","16","16","A","Sweet Stout",$id);
			if ($method == 1) $return = "16A";
		break;

		case "13C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","16","16","B","Oatmeal Stout",$id);
			if ($method == 1) $return = "16B";
		break;

		case "13D":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","16","16","D","Foreign Export Stout",$id);
			if ($method == 1) $return = "16D";
		break;

		case "13E":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","B","American Stout",$id);
			if ($method == 1) $return = "20B";
		break;

		case "13F":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","C","Imperial Stout",$id);
			if ($method == 1) $return = "20C";
		break;

		// 14
		case "14A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","12","12","C","English IPA",$id);
			if ($method == 1) $return = "12C";
		break;

		case "14B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","21","21","A","American IPA",$id);
			if ($method == 1) $return = "21A";
		break;

		case "14C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","22","22","A","American IPA",$id);
			if ($method == 1) $return = "22A";
		break;

		// 15
		case "15A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","10","10","A","Weissbier",$id);
			if ($method == 1) $return = "10A";
		break;

		case "15B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","10","10","B","Dunkles Weissbier",$id);
			if ($method == 1) $return = "10B";
		break;

		case "15C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","10","10","C","Weizenbock",$id);
			if ($method == 1) $return = "10C";
		break;

		case "15D":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s', brewInfo='%s' WHERE id='%s'",$prefix."brewing","27","27","A","Historical Beer","Roggenbier",$id);
			if ($method == 1) $return = "27A";
		break;

		// 16
		case "16A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","24","24","A","Witbier",$id);
			if ($method == 1) $return = "24A";
		break;

		case "16B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","24","24","B","Belgian Pale Ale",$id);
			if ($method == 1) $return = "24B";
		break;

		case "16C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","25","25","B","Saison",$id);
			if ($method == 1) $return = "25B";
		break;

		case "16D":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","24","24","C","Biere de Garde",$id);
			if ($method == 1) $return = "24C";
		break;

		case "16E":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","34","34","A","Clone Beer",$id);
			if ($method == 1) $return = "34A";
		break;

		// 17
		case "17A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","23","23","A","Berliner Weisse",$id);
			if ($method == 1) $return = "23A";
		break;

		case "17B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","23","23","B","Flanders Red Ale",$id);
			if ($method == 1) $return = "23B";
		break;

		case "17C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","23","23","C","Oud Bruin",$id);
			if ($method == 1) $return = "23C";
		break;

		case "17D":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","23","23","D","Lambic",$id);
			if ($method == 1) $return = "23D";
		break;

		case "17E":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","23","23","E","Gueuze",$id);
			if ($method == 1) $return = "23E";
		break;

		case "17F":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","23","23","F","Fruit Lambic",$id);
			if ($method == 1) $return = "23F";
		break;

		// 18
		case "18A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","25","25","A","Belgian Blonde Ale",$id);
			if ($method == 1) $return = "25A";
		break;

		case "18B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","26","26","B","Belgian Dubbel",$id);
			if ($method == 1) $return = "26B";
		break;

		case "18C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","26","26","C","Belgian Tripel",$id);
			if ($method == 1) $return = "26C";
		break;

		case "18D":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","25","25","C","Belgian Golden Strong Ale",$id);
			if ($method == 1) $return = "25C";
		break;

		case "18E":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","26","26","D","Belgian Dark Strong Ale",$id);
			if ($method == 1) $return = "26D";
		break;

		// 19
		case "19A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","17","17","B","Old Ale",$id);
			if ($method == 1) $return = "17B";
		break;

		case "19B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","17","17","D","English Barleywine",$id);
			if ($method == 1) $return = "17D";
		break;

		case "19C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","22","22","C","American Barleywine",$id);
			if ($method == 1) $return = "22C";
		break;

		// 20
		case "20A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","29","29","A","Fruit Beer",$id);
			if ($method == 1) $return = "29A";
		break;

		// 21
		case "21A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","30","30","A","Spice, Herb, or Vegetable Beer",$id);
			if ($method == 1) $return = "30A";
		break;

		case "21B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","30","30","C","Winter Seasonal Beer",$id);
			if ($method == 1) $return = "30C";
		break;

		// 22
		case "22A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","6","06","B","Rauchbier",$id);
			if ($method == 1) $return = "06B";
		break;

		case "22B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","32","32","A","Classic Style Smoked Beer",$id);
			if ($method == 1) $return = "32A";
		break;

		case "22C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","33","33","A","Wood-Aged Beer",$id);
			if ($method == 1) $return = "33A";
		break;

		// 23
		case "23A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","34","34","C","Specialty Beer",$id);
			if ($method == 1) $return = "34C";
		break;

		// 24
		case "24A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M1","M1","A","Dry Mead",$id);
			if ($method == 1) $return = "M1A";
		break;

		case "24B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M1","M1","B","Semi-Sweet Mead",$id);
			if ($method == 1) $return = "M1B";
		break;

		case "24C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M1","M1","C","Sweet Mead",$id);
			if ($method == 1) $return = "M1C";
		break;

		// 25
		case "25A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M2","M2","A","Cyser",$id);
			if ($method == 1) $return = "M2A";
		break;

		case "25B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M2","M2","B","Pyment",$id);
			if ($method == 1) $return = "M2B";
		break;

		case "25C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M2","M2","E","Melomel",$id);
			if ($method == 1) $return = "M2E";
		break;

		// 26
		case "26A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M3","M3","A","Spice, Herb or Vegetable Mead",$id);
			if ($method == 1) $return = "M3A";
		break;

		case "26B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M4","M4","A","Braggot",$id);
			if ($method == 1) $return = "M4A";
		break;

		case "26C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M4","M4","C","Experimental Mead",$id);
			if ($method == 1) $return = "M4C";
		break;

		// 27
		case "27A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C1","C1","A","New World Cider",$id);
			if ($method == 1) $return = "C1A";
		break;

		case "27B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C1","C1","B","English Cider",$id);
			if ($method == 1) $return = "C1B";
		break;

		case "27C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C1","C1","C","French Cider",$id);
			if ($method == 1) $return = "C1C";
		break;

		case "27D":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C1","C1","D","New World Perry",$id);
			if ($method == 1) $return = "C1D";
		break;

		case "27E":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C1","C1","E","Traditional Perry",$id);
			if ($method == 1) $return = "C1E";
		break;

		// 28
		case "28A":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C2","C2","A","New England Cider",$id);
			if ($method == 1) $return = "C2A";
		break;

		case "28B":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C2","C2","B","Cider with Other Fruit",$id);
			if ($method == 1) $return = "C2B";
		break;

		case "28C":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C2","C2","C","Applewine",$id);
			if ($method == 1) $return = "C2C";
		break;

		case "28D":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C2","C2","F","Specialty Cider/Perry",$id);
			if ($method == 1) $return = "C2F";
		break;

	}

	if (($method == 1) && (empty($return))) $return = $style;
    return ($return);

}

function bjcp_map_2015_2021($style, $method, $prefix, $id) {

    $return = "";

    switch($style) {

            // Märzen has umlaut
            case "06A":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","6","06","A","Märzen",$id);
                if ($method == 1) $return = "06A";
                if ($method == 2) $return = "06-A";
            break;

            // Kellerbier now part of Historical Beer
            case "07C":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","27","27","A1","Kellerbier",$id);
                if ($method == 1) $return = "27A1";
                if ($method == 2) $return = "27-A1";
            break;

            // English Barley Wine (2021 splits into two words)
            case "17D":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","17","17","D","English Barley Wine",$id);
                if ($method == 1) $return = "17D";
                if ($method == 2) $return = "17-D";
            break;

            // New England IPA changed to Hazy IPA
            // Now part of IPA category
            case "21B7":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","21","21","C","Hazy IPA",$id);
                if ($method == 1) $return = "21C";
                if ($method == 2) $return = "21-C";
            break;

            // Add accent for Bière de Garde
            case "24C":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","24","24","C","Bière de Garde",$id);
                if ($method == 1) $return = "24C";
                if ($method == 2) $return = "24-C";
            break;

            // Trappist Ale now Monastic Ale
            // Trappist Single now Belgian Single
            case "26A":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","26","26","A","Belgian Single",$id);
                if ($method == 1) $return = "26A";
                if ($method == 2) $return = "26-A";
            break;

            // Clone Beer is now Commercial Specialty Beer
            case "34A":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","34","34","A","Commercial Specialty Beer",$id);
                if ($method == 1) $return = "34A";
                if ($method == 2) $return = "34-A";
            break;

            // Provisional Styles prefix now LS for Local Styles
            case "PRX1":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","LS","LS","X1","Dorada Pampeana",$id);
                if ($method == 1) $return = "LSX1";
                if ($method == 2) $return = "LS-X1";
            break;

            case "PRX2":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","LS","LS","X2","IPA Argenta",$id);
                if ($method == 1) $return = "LSX2";
                if ($method == 2) $return = "LS-X2";
            break;

            case "PRX3":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","LS","LS","X3","Italian Grape Ale",$id);
                if ($method == 1) $return = "LSX3";
                if ($method == 2) $return = "LS-X3";
            break;

            case "PRX4":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","LS","LS","X4","Catharina Sour",$id);
                if ($method == 1) $return = "LSX4";
                if ($method == 2) $return = "LS-X4";
            break;

            case "PRX5":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","LS","LS","X5","New Zealand Pilsner",$id);
                if ($method == 1) $return = "LSX5";
                if ($method == 2) $return = "LS-X5";
            break;

            // Historical styles have been shuffled

            // Gose moved to European Sour Ale
            case "27A1":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","23","23","G","Gose",$id);
                if ($method == 1) $return = "23G";
                if ($method == 2) $return = "23-G";
            break;

            case "27A2":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","27","27","A5","Piwo Grodziskie",$id);
                if ($method == 1) $return = "27A5";
                if ($method == 2) $return = "27-A5";
            break;

            // 27A3 (Lichenhainer) is the same

            case "27A4":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","27","27","A8","Roggenbier",$id);
                if ($method == 1) $return = "27A8";
                if ($method == 2) $return = "27-A8";
            break;

            case "27A5":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","27","27","A9","Sahti",$id);
                if ($method == 1) $return = "27A9";
                if ($method == 2) $return = "27-A9";
            break;

            case "27A6":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","27","27","A2","Kentucky Common",$id);
                if ($method == 1) $return = "27A2";
                if ($method == 2) $return = "27-A2";
            break;

            case "27A7":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","27","27","A6","Pre-Prohibition Lager",$id);
                if ($method == 1) $return = "27A6";
                if ($method == 2) $return = "27-A6";
            break;

            case "27A8":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","27","27","A7","Pre-Prohibition Porter",$id);
                if ($method == 1) $return = "27A7";
                if ($method == 2) $return = "27-A7";
            break;

            case "27A9":
                if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","27","27","A4","London Brown Ale",$id);
                if ($method == 1) $return = "27A4";
                if ($method == 2) $return = "27-A4";
            break;

        }

    if (($method == 1) && (empty($return))) $return = $style;
    return ($return);

}

?>