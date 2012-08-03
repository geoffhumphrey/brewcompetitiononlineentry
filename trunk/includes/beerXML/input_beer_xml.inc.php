<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */

//{{{ License
// +------------------------------------------------------------------------+
// | Input Beer XML - takes recipe objects from BeerXMLParser               |
// |                  and inserts recipes into database                     |
// | 							                                            |
// | NOTES - Augmented by Geoff Humphrey for use in BrewBlogger	2.3         |
// |         <brewmeister@brewblogger.net>                                  |
// |       - Added conversion variables based upon BB preferences           |
// |       - Beer XML standards are in Metric for weight/volume, C for temp |
// +------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or          |
// | modify it under the terms of the GNU General Public License            |
// | as published by the Free Software Foundation; either version 2         |
// | of the License, or (at your option) any later version.                 |
// |                                                                        |
// | This program is distributed in the hope that it will be useful,        |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of         |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the          |
// | GNU General Public License for more details.                           |
// |                                                                        |
// | You should have received a copy of the GNU General Public License      |
// | along with this program; if not, write to the Free Software            |
// | Foundation, Inc., 59 Temple Place - Suite 330,                         |
// | Boston, MA  02111-1307, USA.                                           |
// +------------------------------------------------------------------------+
// | Author: Oskar Stephens <oskar.stephens@gmail.com>	                    |
// +------------------------------------------------------------------------+
//}}}

include ('parse_beer_xml.inc.php');
//{{{ InputBeerXML
class InputBeerXML {
    public $recipes; 
    public $insertedRecipes;
    public $brewer;
    //{{{InputBeerXML
    function InputBeerXML($filename) {
        $this->brewer = $GLOBALS['loginUsername'];
        $this->recipes = new BeerXMLParser($filename);
    }
    //}}}
	
	//{{{ convertUnit()
    function convertUnit($value,$type){
	    require(CONFIG.'config.php');
		mysql_select_db($database, $brewing);
		$query_pref_xml = sprintf("SELECT prefsWeight1,prefsTemp,prefsLiquid2,prefsWeight2 FROM %s", $prefix."preferences");
		$pref_xml = mysql_query($query_pref_xml, $brewing) or die(mysql_error());
		$row_pref_xml = mysql_fetch_assoc($pref_xml);
		$totalRows_pref_xml = mysql_num_rows($pref_xml);
		
        switch($type){
            case "hopWeight";
				if ($row_pref_xml['prefsWeight1'] == "grams") return round($value * 1000, 2);
				else return round($value * 35.27396, 2);
			case "temperature":
				if ($row_pref_xml['prefsTemp'] == "Fahrenheit") return round (($value * 1.8) + 32, 0);
				else return round($value, 0); 
            case "volume":
				if ($row_pref_xml['prefsLiquid2'] == "gallons") return round($value * 0.26417, 1);
				else return round($value, 1);
            case "weight":
                if ($row_pref_xml['prefsWeight2'] == "pounds") return round($value * 2.20462, 2); 
				else return round($value, 2);
            default:
            break;
        }
    }
    //}}}


    //{{{ insertRecipes
    function insertRecipes(){
        foreach($this->recipes->recipes as $recipe){
            $this->insertRecipe($recipe);
        }
        return $this->insertedRecipes;
    }
    //}}}

    //{{{ insertRecipe
    function insertRecipe($recipe){  // inserts into `recipes` DB table
	include(CONFIG.'config.php');
	include (INCLUDES.'scrubber.inc.php');
	include (INCLUDES.'url_variables.inc.php');
        $brewing = $connection;
        $sqlQuery = "INSERT INTO ".$prefix."brewing";
        $fields = "(brewName";
        $values = " VALUES('" .  
		strtr($recipe->name, $html_string) 
		. "'";
        $vf = array();
        $counter = array();
		$vf["brewBrewerFirstName"] = $_POST["brewBrewerFirstName"];
		$vf["brewBrewerLastName"] = $_POST["brewBrewerLastName"];
        $vf["brewStyle"] = strtr($recipe->style->name, $html_string);
		$vf["brewCategory"] = ltrim($recipe->style->categoryNumber, "0");
		$vf["brewCategorySort"] = $recipe->style->categoryNumber;
		$vf["brewSubCategory"] = $recipe->style->styleLetter;
        //$vf["brewSource"] = $recipe->brewer;
        //$vf["brewYield"] = $this->convertUnit($recipe->batchSize, "volume");
        //$vf["brewNotes"] = strtr($recipe->notes, $html_string);
        //$vf["brewMethod"] = $recipe->type; 
        $counter["grain"] = 0;
        $counter["extract"] = 0;
        $counter["adjunct"] = 0;
        foreach($recipe->fermentables->fermentables as $fermentable){
            switch($fermentable->type){
                case "Grain":
                    $counter["grain"]++;
                    if($counter["grain"] <= 15){
                        $vf["brewGrain" . $counter["grain"]] = strtr($fermentable->name, $html_string);
						$vf["brewGrain" . $counter["grain"] . "Weight"] = number_format($this->convertUnit($fermentable->amount,"weight"),2); 
						$vf["brewGrain" . $counter["grain"] . "Use"] = "Mash";
						
					}                    
				break;
                case "Extract":
                    $counter["extract"]++;
                    if($counter["extract"] <= 5){
                        $vf["brewExtract" . $counter["extract"]] = strtr($fermentable->name, $html_string);
                        $vf["brewExtract" . $counter["extract"] . "Weight"] = number_format($this->convertUnit($fermentable->amount,"weight"),2);
						$vf["brewExtract" . $counter["extract"] . "Use"] = "Other";
                    }
                    break;
                case "Dry Extract":
                    $counter["extract"]++;
                    if($counter["extract"] <= 5){
                        $vf["brewExtract" . $counter["extract"]] = strtr($fermentable->name, $html_string);
                        $vf["brewExtract" . $counter["extract"] . "Weight"] = number_format($this->convertUnit($fermentable->amount,"weight"),2);
						$vf["brewExtract" . $counter["extract"] . "Use"] = "Other";
                    }
                    break;
                case "Adjunct":
                    $counter["adjunct"]++;
                    if($counter["adjunct"] <= 9){
                        $vf["brewAddition" . $counter["adjunct"]] = strtr($fermentable->name, $html_string);
						$vf["brewAddition" . $counter["adjunct"] . "Amt"] = number_format($this->convertUnit($fermentable->amount,"weight"),2);
						$vf["brewAddition" . $counter["adjunct"] . "Use"] = "Other"; 
						}
                    break;
                case "Sugar":
                    $counter["adjunct"]++;
                    if($counter["adjunct"] <= 9){
                        $vf["brewAddition" . $counter["adjunct"]] = strtr($fermentable->name, $html_string);
                        $vf["brewAddition" . $counter["adjunct"] . "Amt"]  = number_format($this->convertUnit($fermentable->amount,"weight"),2);
						$vf["brewAddition" . $counter["adjunct"] . "Use"] = "Other";  
                    }
                    break;
                default:
                    break;
            }
        }
		/*
        $counter["misc"] = 0;
        foreach($recipe->miscs->miscs as $misc){
            $counter["misc"]++;
            if($counter["misc"] <= 4){
                $vf["brewMisc" . $counter["misc"] . "Name"] = strtr($misc->name, $html_string);
                $vf["brewMisc" . $counter["misc"] . "Type"] = $misc->type;  // BeerXML differntiates between liquid and volume - BB 2.2 does not - item for future release
                $vf["brewMisc" . $counter["misc"] . "Use"] = $misc->useFor;
                $vf["brewMisc" . $counter["misc"] . "Time"] = round($misc->time, 0);
                $vf["brewMisc" . $counter["misc"] . "Amount"] = number_format(round($misc->amount, 2),2);  // Beer XML standard is kg or liters - will need to address in subsequent release
            }
        }
		*/

        $counter["hops"] = 0;
        foreach($recipe->hops->hops as $hop){
            $counter["hops"]++;
            if($counter["hops"] <= 15){
                $vf["brewHops" . $counter["hops"]] = strtr($hop->name, $html_string);
				$vf["brewHops" . $counter["hops"] . "Weight"] = number_format($this->convertUnit($hop->amount,"hopWeight"),2);
				$vf["brewHops" . $counter["hops"] . "IBU"] = number_format($hop->alpha,1);
                $vf["brewHops" . $counter["hops"] . "Time"] = round($hop->time, 0);
                $vf["brewHops" . $counter["hops"] . "Use"] = $hop->use;
                $vf["brewHops" . $counter["hops"] . "Type"] = $hop->type;
                $vf["brewHops" . $counter["hops"] . "Form"] = $hop->form;
            }
        }

        $counter["yeast"] = 0;
        foreach($recipe->yeasts->yeasts as $yeast){
            $vf["brewYeast"] = strtr($yeast->name, $html_string);
            $vf["brewYeast" . "Man"] = $yeast->laboratory;
            $vf["brewYeast" . "Form"] = $yeast->form;
            $vf["brewYeast" . "Type"] = $yeast->type;
            if($yeast->amountIsWeight == "TRUE"){
                $vf["brewYeast" . "Amount"] = number_format($this->convertUnit($yeast->amount,"weight"),0); 
            }else {
                $vf["brewYeast" . "Amount"] = $this->convertUnit($yeast->amount,"volume"); 
            }        
		}

        $vf["brewOG"] = number_format($recipe->og,3);
        $vf["brewFG"] = number_format($recipe->fg,3);
        // $vf["brewProcedure"] = $recipe->notes;
        if ($recipe->primaryAge != "") { $vf["brewPrimary"] = number_format(round($recipe->primaryAge, 0),0); }
        if ($recipe->primaryTemp != "") { $vf["brewPrimaryTemp"] = number_format($this->convertUnit($recipe->primaryTemp,"temperature"),0); }
        if ($recipe->secondaryAge != "") { $vf["brewSecondary"] = number_format(round($recipe->secondaryAge, 0),0); }
		if ($recipe->secondaryTemp != "") { $vf["brewSecondaryTemp"] = number_format($this->convertUnit($recipe->secondaryTemp,"temperature"),0); }
		
		$vf["brewBrewerID"] = $_POST["brewBrewerID"];
		$vf["brewConfirmed"] = "0";
		
        foreach($vf as $field=>$value){
            $fields .= ", " . $field;
            $values .= ", '" . $value . "'";
        }
        $fields .= ", brewUpdated";
        $fields .= ")";
		$values .= ", NOW( )";
        $values .= ")";
        $sqlQuery .= $fields . $values;
        //echo $sqlQuery . "<br />";
		//exit;
        mysql_select_db($database, $brewing) or die(mysql_error());
        $Result1 = mysql_query($sqlQuery, $brewing) or die(mysql_error());
		$this->insertedRecipes[mysql_insert_id()] = $recipe->name;
		//header("Location: ../../index.php?section=list");
        }
    //}}}
  }

//}}}
//}}}
//}}}
//}}}
?>

