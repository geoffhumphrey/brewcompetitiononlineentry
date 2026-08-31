<?php 

// Map BJCP2008 Styles to BJCP2015 Styles

function bjcp_map_2008_2015($style, $method, $prefix, $id) {

	// $id is always a brewing-table auto-increment primary key; cast to int
	// so it can be safely spliced into the UPDATE strings built below.
	$id = (int) $id;

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

    // $id is always a brewing-table auto-increment primary key; cast to int
    // so it can be safely spliced into the UPDATE strings built below.
    $id = (int) $id;

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

function bjcp_map_2021_2025($style, $method, $prefix, $id) {

	// January 15, 2025 update was cider only.

	// $id is always a brewing-table auto-increment primary key; cast to int
	// so it can be safely spliced into the UPDATE strings built below.
	$id = (int) $id;

    $return = "";

    switch($style) {

        case "C1A":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C1","C1","A","Common Cider",$id);
            if ($method == 1) $return = "C1A";
            if ($method == 2) $return = "C1-A";
        break;

        case "C1B":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C1","C1","C","English Cider",$id);
            if ($method == 1) $return = "C1C";
            if ($method == 2) $return = "C1-C";
        break;

        case "C1C":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C1","C1","D","French Cider",$id);
            if ($method == 1) $return = "C1D";
            if ($method == 2) $return = "C1-D";
        break;

        case "C1D":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C4","C4","A","Common Perry",$id);
            if ($method == 1) $return = "C4A";
            if ($method == 2) $return = "C4-A";
        break;

        case "C1E":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C4","C4","B","Heirloom Perry",$id);
            if ($method == 1) $return = "C4B";
            if ($method == 2) $return = "C4-B";
        break;

        case "C2B":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C3","C3","A","Fruit Cider",$id);
            if ($method == 1) $return = "C3A";
            if ($method == 2) $return = "C3-A";
        break;

        case "C2C":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C2","C2","B","Applewine",$id);
            if ($method == 1) $return = "C2B";
            if ($method == 2) $return = "C2-B";
        break;

        case "C2D":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C2","C2","C","Ice Cider",$id);
            if ($method == 1) $return = "C2C";
            if ($method == 2) $return = "C2-C";
        break;

        case "C2E":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C3","C3","B","Spiced Cider",$id);
            if ($method == 1) $return = "C3B";
            if ($method == 2) $return = "C3-B";
        break;

        case "C2F":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C3","C3","C","Experimental Cider",$id);
            if ($method == 1) $return = "C3C";
            if ($method == 2) $return = "C3-C";
        break;

    }

    if (($method == 1) && (empty($return))) $return = $style;
    return ($return);

}

function aabc_map_2022_2025($style, $method, $prefix, $id) {

	// July 31, 2025 update was cider only.

	// $id is always a brewing-table auto-increment primary key; cast to int
	// so it can be safely spliced into the UPDATE strings built below.
	$id = (int) $id;

    $return = "";

    switch($style) {

        case "20-01":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","01","Common Cider [BJCP C1A]",$id);
            if ($method == 1) $return = "20-01";
            if ($method == 2) $return = "20-01";
        break;

        case "20-02":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","02","English Cider [BJCP C1C]",$id);
            if ($method == 1) $return = "20-02";
            if ($method == 2) $return = "20-02";
        break;

        case "20-03":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","03","French Cider [BJCP C1D]",$id);
            if ($method == 1) $return = "20-03";
            if ($method == 2) $return = "20-03";
        break;

        case "20-04":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","13","Common Perry [BJCP C4A]",$id);
            if ($method == 1) $return = "20-13";
            if ($method == 2) $return = "20-13";
        break;

        case "20-05":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","14","Heirloom Perry [BJCP C4A]",$id);
            if ($method == 1) $return = "20-14";
            if ($method == 2) $return = "20-14";
        break;

        case "20-06":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","06","New England Cider [BJCP C2A]",$id);
            if ($method == 1) $return = "20-06";
            if ($method == 2) $return = "20-06";
        break;

        case "20-07":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","10","Fruit Cider [BJCP C3A]",$id);
            if ($method == 1) $return = "20-10";
            if ($method == 2) $return = "20-10";
        break;

        case "20-08":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","07","Applewine [BJCP C2B]",$id);
            if ($method == 1) $return = "20-07";
            if ($method == 2) $return = "20-07";
        break;

        case "20-09":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","09","Ice Cider [BJCP C2C]",$id);
            if ($method == 1) $return = "20-08";
            if ($method == 2) $return = "20-08";
        break;

        case "20-10":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","10","Spiced Cider [BJCP C3B]",$id);
            if ($method == 1) $return = "20-11";
            if ($method == 2) $return = "20-11";
        break;

        case "20-11":
            if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","11","Experimental Perry [BJCP C4D]",$id);
            if ($method == 1) $return = "20-11";
            if ($method == 2) $return = "20-11";
        break;

    }

    if (($method == 1) && (empty($return))) $return = $style;
    return ($return);

}

function ba_map_2026($style, $method, $prefix, $id) {

	// July 2026 update replaces the old 'BA' style set entirely with a
	// fresh BA2026 style set (see includes/styles.inc.php) - group/num
	// values are NOT shared between the two versions, so unlike the
	// cider-only AABC/BJCP delta maps, there is no safe "leave $style
	// unchanged" fallback here: any style with no case below has no
	// confident BA2026 counterpart and must be left on 'BA', untouched
	// (empty return for every method - see convert_ba_2026.inc.php).

	// $id is always a brewing-table auto-increment primary key; cast to int
	// so it can be safely spliced into the UPDATE strings built below.
	$id = (int) $id;

	$return = "";

	switch($style) {

		case "01-012":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","04","04","054",'Contemporary-Style Gose',$id);
			if ($method == 1) $return = "04-054";
			if ($method == 2) $return = "04-054";
		break;

		case "01-015":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","041",'Double Hoppy Red Ale',$id);
			if ($method == 1) $return = "03-041";
			if ($method == 2) $return = "03-041";
		break;

		case "01-153":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","018",'Oatmeal Stout',$id);
			if ($method == 1) $return = "01-018";
			if ($method == 2) $return = "01-018";
		break;

		case "01-154":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","017",'Sweet Stout or Cream Stout',$id);
			if ($method == 1) $return = "01-017";
			if ($method == 2) $return = "01-017";
		break;

		case "01-155":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","015",'Brown Porter',$id);
			if ($method == 1) $return = "01-015";
			if ($method == 2) $return = "01-015";
		break;

		case "01-156":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","016",'Robust Porter',$id);
			if ($method == 1) $return = "01-016";
			if ($method == 2) $return = "01-016";
		break;

		case "01-157":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","020",'British-Style Imperial Stout',$id);
			if ($method == 1) $return = "01-020";
			if ($method == 2) $return = "01-020";
		break;

		case "01-158":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","021",'British-Style Barley Wine Ale',$id);
			if ($method == 1) $return = "01-021";
			if ($method == 2) $return = "01-021";
		break;

		case "01-159":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","010",'Strong Ale',$id);
			if ($method == 1) $return = "01-010";
			if ($method == 2) $return = "01-010";
		break;

		case "01-160":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","019",'Scotch Ale or Wee Heavy',$id);
			if ($method == 1) $return = "01-019";
			if ($method == 2) $return = "01-019";
		break;

		case "01-161":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","011",'Old Ale',$id);
			if ($method == 1) $return = "01-011";
			if ($method == 2) $return = "01-011";
		break;

		case "01-162":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","013",'English-Style Dark Mild Ale',$id);
			if ($method == 1) $return = "01-013";
			if ($method == 2) $return = "01-013";
		break;

		case "01-163":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","012",'English-Style Pale Mild Ale',$id);
			if ($method == 1) $return = "01-012";
			if ($method == 2) $return = "01-012";
		break;

		case "01-164":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","004",'Scottish-Style Light Ale',$id);
			if ($method == 1) $return = "01-004";
			if ($method == 2) $return = "01-004";
		break;

		case "01-165":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","014",'English-Style Brown Ale',$id);
			if ($method == 1) $return = "01-014";
			if ($method == 2) $return = "01-014";
		break;

		case "01-166":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","006",'Scottish-Style Export Ale',$id);
			if ($method == 1) $return = "01-006";
			if ($method == 2) $return = "01-006";
		break;

		case "01-167":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","005",'Scottish-Style Heavy Ale',$id);
			if ($method == 1) $return = "01-005";
			if ($method == 2) $return = "01-005";
		break;

		case "01-168":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","008",'Classic English-Style Pale Ale',$id);
			if ($method == 1) $return = "01-008";
			if ($method == 2) $return = "01-008";
		break;

		case "01-169":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","009",'British-Style India Pale Ale',$id);
			if ($method == 1) $return = "01-009";
			if ($method == 2) $return = "01-009";
		break;

		case "01-170":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","001",'Ordinary Bitter',$id);
			if ($method == 1) $return = "01-001";
			if ($method == 2) $return = "01-001";
		break;

		case "01-171":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","002",'Special Bitter or Best Bitter',$id);
			if ($method == 1) $return = "01-002";
			if ($method == 2) $return = "01-002";
		break;

		case "01-172":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","003",'Extra Special Bitter',$id);
			if ($method == 1) $return = "01-003";
			if ($method == 2) $return = "01-003";
		break;

		case "01-173":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","01","01","007",'English-Style Summer Ale',$id);
			if ($method == 1) $return = "01-007";
			if ($method == 2) $return = "01-007";
		break;

		case "02-149":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","02","02","024",'Export-Style Stout',$id);
			if ($method == 1) $return = "02-024";
			if ($method == 2) $return = "02-024";
		break;

		case "02-151":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","02","02","023",'Classic Irish-Style Dry Stout',$id);
			if ($method == 1) $return = "02-023";
			if ($method == 2) $return = "02-023";
		break;

		case "02-152":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","02","02","022",'Irish-Style Red Ale',$id);
			if ($method == 1) $return = "02-022";
			if ($method == 2) $return = "02-022";
		break;

		case "03-002":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","029",'Juicy or Hazy Pale Ale',$id);
			if ($method == 1) $return = "03-029";
			if ($method == 2) $return = "03-029";
		break;

		case "03-003":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","034",'Juicy or Hazy India Pale Ale',$id);
			if ($method == 1) $return = "03-034";
			if ($method == 2) $return = "03-034";
		break;

		case "03-004":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","044",'Juicy or Hazy Imperial or Double India Pale Ale',$id);
			if ($method == 1) $return = "03-044";
			if ($method == 2) $return = "03-044";
		break;

		case "03-011":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","026",'Session India Pale Ale',$id);
			if ($method == 1) $return = "03-026";
			if ($method == 2) $return = "03-026";
		break;

		case "03-020":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","039",'American-Style Imperial Porter',$id);
			if ($method == 1) $return = "03-039";
			if ($method == 2) $return = "03-039";
		break;

		case "03-131":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","040",'American-Style Imperial Stout',$id);
			if ($method == 1) $return = "03-040";
			if ($method == 2) $return = "03-040";
		break;

		case "03-132":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","048",'American-Style Sour Ale',$id);
			if ($method == 1) $return = "03-048";
			if ($method == 2) $return = "03-048";
		break;

		case "03-134":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","038",'American-Style Stout',$id);
			if ($method == 1) $return = "03-038";
			if ($method == 2) $return = "03-038";
		break;

		case "03-135":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","037",'American-Style Black Ale',$id);
			if ($method == 1) $return = "03-037";
			if ($method == 2) $return = "03-037";
		break;

		case "03-136":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","036",'American-Style Brown Ale',$id);
			if ($method == 1) $return = "03-036";
			if ($method == 2) $return = "03-036";
		break;

		case "03-137":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","047",'Smoke Porter',$id);
			if ($method == 1) $return = "03-047";
			if ($method == 2) $return = "03-047";
		break;

		case "03-138":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","046",'American-Style Wheat Wine Ale',$id);
			if ($method == 1) $return = "03-046";
			if ($method == 2) $return = "03-046";
		break;

		case "03-139":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","025",'Golden or Blonde Ale',$id);
			if ($method == 1) $return = "03-025";
			if ($method == 2) $return = "03-025";
		break;

		case "03-140":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","045",'American-Style Barley Wine Ale',$id);
			if ($method == 1) $return = "03-045";
			if ($method == 2) $return = "03-045";
		break;

		case "03-141":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","027",'American-Style Amber/Red Ale',$id);
			if ($method == 1) $return = "03-027";
			if ($method == 2) $return = "03-027";
		break;

		case "03-142":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","042",'Imperial Red Ale',$id);
			if ($method == 1) $return = "03-042";
			if ($method == 2) $return = "03-042";
		break;

		case "03-143":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","043",'American-Style Imperial or Double India Pale Ale',$id);
			if ($method == 1) $return = "03-043";
			if ($method == 2) $return = "03-043";
		break;

		case "03-144":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","030",'American-Style Strong Pale Ale',$id);
			if ($method == 1) $return = "03-030";
			if ($method == 2) $return = "03-030";
		break;

		case "03-145":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","032",'American-Style India Pale Ale',$id);
			if ($method == 1) $return = "03-032";
			if ($method == 2) $return = "03-032";
		break;

		case "03-146":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","035",'American-Belgo-Style Ale',$id);
			if ($method == 1) $return = "03-035";
			if ($method == 2) $return = "03-035";
		break;

		case "03-147":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","158",'Fresh Hop Beer',$id);
			if ($method == 1) $return = "11-158";
			if ($method == 2) $return = "11-158";
		break;

		case "03-148":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","035",'American-Belgo-Style Ale',$id);
			if ($method == 1) $return = "03-035";
			if ($method == 2) $return = "03-035";
		break;

		case "03-150":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","028",'American-Style Pale Ale',$id);
			if ($method == 1) $return = "03-028";
			if ($method == 2) $return = "03-028";
		break;

		case "03-175":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","031",'Juicy or Hazy Strong Pale Ale',$id);
			if ($method == 1) $return = "03-031";
			if ($method == 2) $return = "03-031";
		break;

		case "03-184":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","03","03","033",'West Coast-Style India Pale Ale',$id);
			if ($method == 1) $return = "03-033";
			if ($method == 2) $return = "03-033";
		break;

		case "04-016":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","06","06","083",'Adambier',$id);
			if ($method == 1) $return = "06-083";
			if ($method == 2) $return = "06-083";
		break;

		case "04-117":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","140",'Kellerbier or Zwickelbier',$id);
			if ($method == 1) $return = "11-140";
			if ($method == 2) $return = "11-140";
		break;

		case "04-119":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","04","04","062",'Bamberg-Style Weiss Rauchbier',$id);
			if ($method == 1) $return = "04-062";
			if ($method == 2) $return = "04-062";
		break;

		case "04-120":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","04","04","051",'German-Style Altbier',$id);
			if ($method == 1) $return = "04-051";
			if ($method == 2) $return = "04-051";
		break;

		case "04-121":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","04","04","060",'South German-Style Weizenbock',$id);
			if ($method == 1) $return = "04-060";
			if ($method == 2) $return = "04-060";
		break;

		case "04-122":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","04","04","058",'South German-Style Bernsteinfarbenes Weizen',$id);
			if ($method == 1) $return = "04-058";
			if ($method == 2) $return = "04-058";
		break;

		case "04-123":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","04","04","059",'South German-Style Dunkel Weizen',$id);
			if ($method == 1) $return = "04-059";
			if ($method == 2) $return = "04-059";
		break;

		case "04-124":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","04","04","055",'South German-Style Hefeweizen',$id);
			if ($method == 1) $return = "04-055";
			if ($method == 2) $return = "04-055";
		break;

		case "04-125":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","04","04","056",'South German-Style Kristal Weizen',$id);
			if ($method == 1) $return = "04-056";
			if ($method == 2) $return = "04-056";
		break;

		case "04-126":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","04","04","057",'German-Style Leichtes Weizen',$id);
			if ($method == 1) $return = "04-057";
			if ($method == 2) $return = "04-057";
		break;

		case "04-127":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","04","04","053",'Leipzig-Style Gose',$id);
			if ($method == 1) $return = "04-053";
			if ($method == 2) $return = "04-053";
		break;

		case "04-128":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","04","04","052",'Berliner-Style Weisse',$id);
			if ($method == 1) $return = "04-052";
			if ($method == 2) $return = "04-052";
		break;

		case "04-130":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","04","04","050",'German-Style Koelsch',$id);
			if ($method == 1) $return = "04-050";
			if ($method == 2) $return = "04-050";
		break;

		case "05-102":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","073",'Classic French & Belgian-Style Saison',$id);
			if ($method == 1) $return = "05-073";
			if ($method == 2) $return = "05-073";
		break;

		case "05-103":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","081",'Other Belgian-Style Ale',$id);
			if ($method == 1) $return = "05-081";
			if ($method == 2) $return = "05-081";
		break;

		case "05-104":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","075",'French-Style Bière de Garde',$id);
			if ($method == 1) $return = "05-075";
			if ($method == 2) $return = "05-075";
		break;

		case "05-105":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","063",'Belgian-Style Table Beer',$id);
			if ($method == 1) $return = "05-063";
			if ($method == 2) $return = "05-063";
		break;

		case "05-106":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","080",'Belgian-Style Fruit Lambic',$id);
			if ($method == 1) $return = "05-080";
			if ($method == 2) $return = "05-080";
		break;

		case "05-107":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","078",'Traditional Belgian-Style Gueuze',$id);
			if ($method == 1) $return = "05-078";
			if ($method == 2) $return = "05-078";
		break;

		case "05-108":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","077",'Belgian-Style Lambic',$id);
			if ($method == 1) $return = "05-077";
			if ($method == 2) $return = "05-077";
		break;

		case "05-109":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","072",'Belgian-Style Witbier',$id);
			if ($method == 1) $return = "05-072";
			if ($method == 2) $return = "05-072";
		break;

		case "05-110":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","068",'Belgian-Style Strong Dark Ale',$id);
			if ($method == 1) $return = "05-068";
			if ($method == 2) $return = "05-068";
		break;

		case "05-111":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","065",'Belgian-Style Speciale Belge',$id);
			if ($method == 1) $return = "05-065";
			if ($method == 2) $return = "05-065";
		break;

		case "05-112":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","067",'Belgian-Style Strong Blonde Ale',$id);
			if ($method == 1) $return = "05-067";
			if ($method == 2) $return = "05-067";
		break;

		case "05-113":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","071",'Belgian-Style Quadrupel',$id);
			if ($method == 1) $return = "05-071";
			if ($method == 2) $return = "05-071";
		break;

		case "05-114":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","066",'Belgian-Style Blonde Ale',$id);
			if ($method == 1) $return = "05-066";
			if ($method == 2) $return = "05-066";
		break;

		case "05-115":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","070",'Belgian-Style Tripel',$id);
			if ($method == 1) $return = "05-070";
			if ($method == 2) $return = "05-070";
		break;

		case "05-116":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","076",'Belgian-Style Flanders Oud Bruin or Oud Red Ale',$id);
			if ($method == 1) $return = "05-076";
			if ($method == 2) $return = "05-076";
		break;

		case "05-118":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","069",'Belgian-Style Dubbel',$id);
			if ($method == 1) $return = "05-069";
			if ($method == 2) $return = "05-069";
		break;

		case "05-176":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","079",'Contemporary Belgian-Style Spontaneous Fermented Ale',$id);
			if ($method == 1) $return = "05-079";
			if ($method == 2) $return = "05-079";
		break;

		case "05-184":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","05","05","064",'Belgian-Style Session Ale',$id);
			if ($method == 1) $return = "05-064";
			if ($method == 2) $return = "05-064";
		break;

		case "06-001":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","06","06","086",'Classic Australian-Style Pale Ale',$id);
			if ($method == 1) $return = "06-086";
			if ($method == 2) $return = "06-086";
		break;

		case "06-009":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","06","06","084",'Dutch-Style Kuit, Kuyt or Koyt',$id);
			if ($method == 1) $return = "06-084";
			if ($method == 2) $return = "06-084";
		break;

		case "06-017":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","06","06","082",'Grodziskie',$id);
			if ($method == 1) $return = "06-082";
			if ($method == 2) $return = "06-082";
		break;

		case "06-099":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","06","06","087",'Australian-Style Pale Ale',$id);
			if ($method == 1) $return = "06-087";
			if ($method == 2) $return = "06-087";
		break;

		case "06-101":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","06","06","085",'International-Style Pale Ale',$id);
			if ($method == 1) $return = "06-085";
			if ($method == 2) $return = "06-085";
		break;

		case "06-182":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","06","06","088",'New Zealand-Style Pale Ale',$id);
			if ($method == 1) $return = "06-088";
			if ($method == 2) $return = "06-088";
		break;

		case "06-183":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","06","06","089",'New Zealand-Style India Pale Ale',$id);
			if ($method == 1) $return = "06-089";
			if ($method == 2) $return = "06-089";
		break;

		case "07-082":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","115",'German-Style Eisbock',$id);
			if ($method == 1) $return = "07-115";
			if ($method == 2) $return = "07-115";
		break;

		case "07-083":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","140",'Kellerbier or Zwickelbier',$id);
			if ($method == 1) $return = "11-140";
			if ($method == 2) $return = "11-140";
		break;

		case "07-084":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","112",'German-Style Heller Bock/Maibock',$id);
			if ($method == 1) $return = "07-112";
			if ($method == 2) $return = "07-112";
		break;

		case "07-085":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","114",'German-Style Doppelbock',$id);
			if ($method == 1) $return = "07-114";
			if ($method == 2) $return = "07-114";
		break;

		case "07-086":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","111",'Bamberg-Style Bock Rauchbier',$id);
			if ($method == 1) $return = "07-111";
			if ($method == 2) $return = "07-111";
		break;

		case "07-087":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","113",'Traditional German-Style Bock',$id);
			if ($method == 1) $return = "07-113";
			if ($method == 2) $return = "07-113";
		break;

		case "07-088":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","110",'Bamberg-Style Maerzen Rauchbier',$id);
			if ($method == 1) $return = "07-110";
			if ($method == 2) $return = "07-110";
		break;

		case "07-089":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","109",'Bamberg-Style Helles Rauchbier',$id);
			if ($method == 1) $return = "07-109";
			if ($method == 2) $return = "07-109";
		break;

		case "07-090":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","107",'European-Style Dark Lager',$id);
			if ($method == 1) $return = "07-107";
			if ($method == 2) $return = "07-107";
		break;

		case "07-091":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","108",'German-Style Schwarzbier',$id);
			if ($method == 1) $return = "07-108";
			if ($method == 2) $return = "07-108";
		break;

		case "07-092":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","105",'German-Style Oktoberfest/Festbier',$id);
			if ($method == 1) $return = "07-105";
			if ($method == 2) $return = "07-105";
		break;

		case "07-093":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","098",'Vienna-Style Lager',$id);
			if ($method == 1) $return = "07-098";
			if ($method == 2) $return = "07-098";
		break;

		case "07-094":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","104",'German-Style Maerzen',$id);
			if ($method == 1) $return = "07-104";
			if ($method == 2) $return = "07-104";
		break;

		case "07-095":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","101",'Munich-Style Helles',$id);
			if ($method == 1) $return = "07-101";
			if ($method == 2) $return = "07-101";
		break;

		case "07-096":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","102",'Dortmunder/European-Style Export',$id);
			if ($method == 1) $return = "07-102";
			if ($method == 2) $return = "07-102";
		break;

		case "07-097":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","099",'German-Style Leichtbier',$id);
			if ($method == 1) $return = "07-099";
			if ($method == 2) $return = "07-099";
		break;

		case "07-098":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","094",'Czech-Style Pale Lager',$id);
			if ($method == 1) $return = "07-094";
			if ($method == 2) $return = "07-094";
		break;

		case "07-100":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","100",'German-Style Pilsener',$id);
			if ($method == 1) $return = "07-100";
			if ($method == 2) $return = "07-100";
		break;

		case "07-177":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","103",'Franconian-Style Rotbier',$id);
			if ($method == 1) $return = "07-103";
			if ($method == 2) $return = "07-103";
		break;

		case "08-005":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","08","08","121",'Contemporary American-Style Pilsener',$id);
			if ($method == 1) $return = "08-121";
			if ($method == 2) $return = "08-121";
		break;

		case "08-073":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","08","08","126",'American-Style Dark Lager',$id);
			if ($method == 1) $return = "08-126";
			if ($method == 2) $return = "08-126";
		break;

		case "08-074":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","08","08","125",'American-Style Maerzen/Oktoberfest',$id);
			if ($method == 1) $return = "08-125";
			if ($method == 2) $return = "08-125";
		break;

		case "08-075":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","08","08","123",'American-Style Malt Liquor',$id);
			if ($method == 1) $return = "08-123";
			if ($method == 2) $return = "08-123";
		break;

		case "08-076":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","08","08","124",'American-Style Amber Lager',$id);
			if ($method == 1) $return = "08-124";
			if ($method == 2) $return = "08-124";
		break;

		case "08-077":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","08","08","120",'American-Style Pilsener',$id);
			if ($method == 1) $return = "08-120";
			if ($method == 2) $return = "08-120";
		break;

		case "08-079":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","08","08","118",'American-Style Light Lager',$id);
			if ($method == 1) $return = "08-118";
			if ($method == 2) $return = "08-118";
		break;

		case "08-081":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","08","08","116",'American-Style Lager',$id);
			if ($method == 1) $return = "08-116";
			if ($method == 2) $return = "08-116";
		break;

		case "08-178":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","08","08","122",'American-Style India Pale Lager',$id);
			if ($method == 1) $return = "08-122";
			if ($method == 2) $return = "08-122";
		break;

		case "08-179":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","08","08","117",'Contemporary American-Style Lager',$id);
			if ($method == 1) $return = "08-117";
			if ($method == 2) $return = "08-117";
		break;

		case "08-180":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","08","08","119",'Contemporary American-Style Light Lager',$id);
			if ($method == 1) $return = "08-119";
			if ($method == 2) $return = "08-119";
		break;

		case "09-071":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","08","08","127",'Mexican-Style Light Lager',$id);
			if ($method == 1) $return = "08-127";
			if ($method == 2) $return = "08-127";
		break;

		case "09-072":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","07","07","093",'Baltic-Style Porter',$id);
			if ($method == 1) $return = "07-093";
			if ($method == 2) $return = "07-093";
		break;

		case "10-068":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","09","09","133",'International-Style Pilsener',$id);
			if ($method == 1) $return = "09-133";
			if ($method == 2) $return = "09-133";
		break;

		case "11-006":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","165",'Wild Beer',$id);
			if ($method == 1) $return = "11-165";
			if ($method == 2) $return = "11-165";
		break;

		case "11-007":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","150",'Chili Pepper Beer',$id);
			if ($method == 1) $return = "11-150";
			if ($method == 2) $return = "11-150";
		break;

		case "11-008":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","156",'Mixed-Culture Brett Beer',$id);
			if ($method == 1) $return = "11-156";
			if ($method == 2) $return = "11-156";
		break;

		case "11-010":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","143",'Belgian-Style Fruit Beer',$id);
			if ($method == 1) $return = "11-143";
			if ($method == 2) $return = "11-143";
		break;

		case "11-037":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","161",'Aged Beer',$id);
			if ($method == 1) $return = "11-161";
			if ($method == 2) $return = "11-161";
		break;

		case "11-038":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","167",'Other Strong Ale or Lager',$id);
			if ($method == 1) $return = "11-167";
			if ($method == 2) $return = "11-167";
		break;

		case "11-039":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","169",'Non-Alcohol Malt Beverage',$id);
			if ($method == 1) $return = "11-169";
			if ($method == 2) $return = "11-169";
		break;

		case "11-040":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","160",'Wood- and Barrel-Aged Sour Beer',$id);
			if ($method == 1) $return = "11-160";
			if ($method == 2) $return = "11-160";
		break;

		case "11-041":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","159",'Wood- and Barrel-Aged Beer',$id);
			if ($method == 1) $return = "11-159";
			if ($method == 2) $return = "11-159";
		break;

		case "11-042":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","159",'Wood- and Barrel-Aged Beer',$id);
			if ($method == 1) $return = "11-159";
			if ($method == 2) $return = "11-159";
		break;

		case "11-043":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","159",'Wood- and Barrel-Aged Beer',$id);
			if ($method == 1) $return = "11-159";
			if ($method == 2) $return = "11-159";
		break;

		case "11-044":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","159",'Wood- and Barrel-Aged Beer',$id);
			if ($method == 1) $return = "11-159";
			if ($method == 2) $return = "11-159";
		break;

		case "11-045":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","164",'Historical Beer',$id);
			if ($method == 1) $return = "11-164";
			if ($method == 2) $return = "11-164";
		break;

		case "11-046":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","166",'Smoke Beer',$id);
			if ($method == 1) $return = "11-166";
			if ($method == 2) $return = "11-166";
		break;

		case "11-047":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","162",'Experimental Beer',$id);
			if ($method == 1) $return = "11-162";
			if ($method == 2) $return = "11-162";
		break;

		case "11-049":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","168",'Gluten-Free Beer',$id);
			if ($method == 1) $return = "11-168";
			if ($method == 2) $return = "11-168";
		break;

		case "11-050":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","153",'Specialty Honey Beer',$id);
			if ($method == 1) $return = "11-153";
			if ($method == 2) $return = "11-153";
		break;

		case "11-051":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","152",'Specialty Beer',$id);
			if ($method == 1) $return = "11-152";
			if ($method == 2) $return = "11-152";
		break;

		case "11-052":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","151",'Herb and Spice Beer',$id);
			if ($method == 1) $return = "11-151";
			if ($method == 2) $return = "11-151";
		break;

		case "11-053":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","147",'Chocolate or Cocoa Beer',$id);
			if ($method == 1) $return = "11-147";
			if ($method == 2) $return = "11-147";
		break;

		case "11-054":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","149",'Coffee Beer',$id);
			if ($method == 1) $return = "11-149";
			if ($method == 2) $return = "11-149";
		break;

		case "11-055":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","146",'Pumpkin/Squash Beer',$id);
			if ($method == 1) $return = "11-146";
			if ($method == 2) $return = "11-146";
		break;

		case "11-056":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","144",'Field Beer',$id);
			if ($method == 1) $return = "11-144";
			if ($method == 2) $return = "11-144";
		break;

		case "11-057":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","04","04","061",'German-Style Rye Ale',$id);
			if ($method == 1) $return = "04-061";
			if ($method == 2) $return = "04-061";
		break;

		case "11-058":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","141",'American-Style Fruit Beer',$id);
			if ($method == 1) $return = "11-141";
			if ($method == 2) $return = "11-141";
		break;

		case "11-059":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","154",'Rye Beer',$id);
			if ($method == 1) $return = "11-154";
			if ($method == 2) $return = "11-154";
		break;

		case "11-060":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","139",'American-Style Wheat Beer',$id);
			if ($method == 1) $return = "11-139";
			if ($method == 2) $return = "11-139";
		break;

		case "11-061":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","139",'American-Style Wheat Beer',$id);
			if ($method == 1) $return = "11-139";
			if ($method == 2) $return = "11-139";
		break;

		case "11-062":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","142",'Fruit Wheat Beer',$id);
			if ($method == 1) $return = "11-142";
			if ($method == 2) $return = "11-142";
		break;

		case "11-063":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","139",'American-Style Wheat Beer',$id);
			if ($method == 1) $return = "11-139";
			if ($method == 2) $return = "11-139";
		break;

		case "11-064":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","139",'American-Style Wheat Beer',$id);
			if ($method == 1) $return = "11-139";
			if ($method == 2) $return = "11-139";
		break;

		case "11-065":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","157",'Ginjo Beer or Sake-Yeast Beer',$id);
			if ($method == 1) $return = "11-157";
			if ($method == 2) $return = "11-157";
		break;

		case "11-066":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","136",'American-Style Cream Ale',$id);
			if ($method == 1) $return = "11-136";
			if ($method == 2) $return = "11-136";
		break;

		case "11-067":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","137",'California Common Beer',$id);
			if ($method == 1) $return = "11-137";
			if ($method == 2) $return = "11-137";
		break;

		case "11-070":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","135",'Session Beer',$id);
			if ($method == 1) $return = "11-135";
			if ($method == 2) $return = "11-135";
		break;

		case "11-133":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","155",'Brett Beer',$id);
			if ($method == 1) $return = "11-155";
			if ($method == 2) $return = "11-155";
		break;

		case "11-174":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","163",'Experimental India Pale Ale',$id);
			if ($method == 1) $return = "11-163";
			if ($method == 2) $return = "11-163";
		break;

		case "11-181":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","138",'Kentucky Common Beer',$id);
			if ($method == 1) $return = "11-138";
			if ($method == 2) $return = "11-138";
		break;

		case "11-185":
			if ($method == 0) $return = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","148",'Dessert Stout or Pastry Beer',$id);
			if ($method == 1) $return = "11-148";
			if ($method == 2) $return = "11-148";
		break;

	}

	return ($return);

}



?>