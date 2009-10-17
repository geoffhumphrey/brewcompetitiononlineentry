<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */
//{{{ License
// +------------------------------------------------------------------------+
// | Input Beer XML - takes recipes objects from BeerXMLParser              |
// |                     and inserts recipes into database                  |
// | 							                                            |
// | NOTES - BeerXML standard is METRIC - converted to Imperial based       |
// |         upon administrative preferences              			        |
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

require ('Connections/config.php');
require ('includes/beerXML/parse_beer_xml.inc.php');
$query_prefs = "SELECT * FROM preferences WHERE id=1";
$prefs = mysql_query($query_prefs, $brewing) or die(mysql_error());
$row_prefs = mysql_fetch_assoc($prefs);
$totalRows_prefs = mysql_num_rows($prefs);
if ($row_prefs['prefsWeight2'] == "pounds") { 
//{{{ InputBeerXML
class InputBeerXML {
    var $recipes;
    var $insertedRecipes;
    var $brewer;
    //{{{InputBeerXML
    function InputBeerXML($filename) {
        $this->brewer = $GLOBALS['loginUsername'];
        $this->recipes = new BeerXMLParser($filename);
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
    function insertRecipe($recipe){
        $sqlQuery = "INSERT INTO brewing ";
        $fields = "(brewName";
        $values = " VALUES('" . $recipe->name . "'";
        $vf = array();
        $counter = array();
		$vf["brewBrewerFirstName"] = $_POST["brewBrewerFirstName"];
		$vf["brewBrewerLastName"] = $_POST["brewBrewerLastName"];
		// $vf["brewName"] = $recipe->name;
		$vf["brewStyle"] = $recipe->style->name;
		$vf["brewCategory"] = ltrim($recipe->style->categoryNumber, "0");
		$vf["brewCategorySort"] = $recipe->style->categoryNumber;
		$vf["brewSubCategory"] = $recipe->style->styleLetter;      
        $vf["brewYield"] = round(($recipe->batchSize * 0.26418), 0);
        // $vf["brewInfo"] = $recipe->notes;
        // $vf["brewMethod"] = $recipe->type; 
        $counter["grain"] = 0;
        $counter["extract"] = 0;
        $counter["adjunct"] = 0;
        foreach($recipe->fermentables->fermentables as $fermentable){
            switch($fermentable->type){
                case "Grain":
                    $counter["grain"]++;
                    if($counter["grain"] <= 9){
                        $vf["brewGrain" . $counter["grain"]] = $fermentable->name;
                        $vf["brewGrain" . $counter["grain"] . "Weight"] = round(($fermentable->amount * 2.20462262),2);
                    }
                    break;
                case "Extract":
                    $counter["extract"]++;
                    if($counter["extract"] <= 5){
                        $vf["brewExtract" . $counter["extract"]] = $fermentable->name;
                        $vf["brewExtract" . $counter["extract"] . "Weight"] = round(($fermentable->amount * 2.20462262),2);
                    }
                    break;
                case "Dry Extract":
                    $counter["extract"]++;
                    if($counter["extract"] <= 5){
                        $vf["brewExtract" . $counter["extract"]] = $fermentable->name;
                        $vf["brewExtract" . $counter["extract"] . "Weight"] = round(($fermentable->amount * 2.20462262),2);
                    }
                    break;
                case "Adjunct":
                    $counter["adjunct"]++;
                    if($counter["adjunct"] <= 9){
                        $vf["brewAddition" . $counter["adjunct"]] = $fermentable->name;
                        $vf["brewAddition" . $counter["adjunct"] . "Amt"] = round(($fermentable->amount * 2.20462262),2);
                    }
                    break;
                case "Sugar":
                    $counter["adjunct"]++;
                    if($counter["adjunct"] <= 9){
                        $vf["brewAddition" . $counter["adjunct"]] = $fermentable->name;
                        $vf["brewAddition" . $counter["adjunct"] . "Amt"] = round(($fermentable->amount * 2.204),2);
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
                $vf["brewMisc" . $counter["misc"] . "Name"] = $misc->name;
                $vf["brewMisc" . $counter["misc"] . "Type"] = $misc->type;
                $vf["brewMisc" . $counter["misc"] . "Use"] = $misc->useFor;
                $vf["brewMisc" . $counter["misc"] . "Time"] = $misc->time;
                $vf["brewMisc" . $counter["misc"] . "Amount"] = $misc->amount;
            }
        }
		*/
        $counter["hops"] = 0;
        foreach($recipe->hops->hops as $hop){
            $counter["hops"]++;
            if($counter["hops"] <= 9){
                $vf["brewHops" . $counter["hops"]] = $hop->name;
                $vf["brewHops" . $counter["hops"] . "Weight"] = round(($hop->amount * 35.27),2);
                $vf["brewHops" . $counter["hops"] . "IBU"] = $hop->alpha;
                $vf["brewHops" . $counter["hops"] . "Time"] = $hop->time;
                $vf["brewHops" . $counter["hops"] . "Use"] = $hop->use;
                $vf["brewHops" . $counter["hops"] . "Type"] = $hop->type;
                $vf["brewHops" . $counter["hops"] . "Form"] = $hop->form;
            }
        }

        $counter["yeast"] = 0;
        foreach($recipe->yeasts->yeasts as $yeast){
            $vf["brewYeast"] = $yeast->name;
            $vf["brewYeast" . "Man"] = $yeast->labratory;
            $vf["brewYeast" . "Form"] = $yeast->form;
            $vf["brewYeast" . "Type"] = $yeast->type;
            $vf["brewYeast" . "Amount"] = $yeast->amount;
        }

        $vf["brewOG"] = 1 . substr($recipe->estimatedOriginalGravity,1,4);
        $vf["brewFG"] = 1 . substr($recipe->estimatedFinalGravity,1,4);
        //$vf["brewProcedure"] = $recipe->notes;
        $vf["brewPrimary"] = $recipe->primaryAge;
        $vf["brewPrimaryTemp"] = round($recipe->primaryTemp * (9/5) + 32, 0);
        $vf["brewSecondary"] = $recipe->secondaryAge;
        $vf["brewSecondaryTemp"] = round($recipe->secondaryTemp * (9/5) + 32, 0);
        $vf["brewOther"] = $recipe->tertiaryAge;
        $vf["brewOtherTemp"] = round($recipe->teriaryTemp * (9/5) + 32, 0);
        //$vf["brewAge"] = $recipe->age;
        //$vf["brewAgeTemp"] = $recipe->ageTemp * (9/5) + 32;
        //$vf["brewBitterness"] = $recipe->ibu;
        //$vf["brewLovibond"] = $recipe->estimatedColor;
        $vf["brewBrewerID"] = $_POST["brewBrewerID"];


        foreach($vf as $field=>$value){
            $fields .= "," . $field;
            $values .= ",'" . $value . "'";
        }

        $fields .= ")";
        $values .= ")";
        $sqlQuery .= $fields . $values;
		require ('Connections/config.php');
        //echo "<br><br><br><br><br><br><br><br><br><br><br>".$sqlQuery . "<br />$database";
		
        mysql_select_db($database, $brewing) or die(mysql_error());
        $Result1 = mysql_query($sqlQuery, $brewing) or die(mysql_error());

        $this->insertedRecipes[mysql_insert_id()] = $recipe->name;
        }
    //}}}

}
} // end if ounces

else {
//{{{ InputBeerXML
class InputBeerXML {
    var $recipes;
    var $insertedRecipes;
    var $brewer;
    //{{{InputBeerXML
    function InputBeerXML($filename) {
        $this->brewer = $GLOBALS['loginUsername'];
        $this->recipes = new BeerXMLParser($filename);
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
    function insertRecipe($recipe){
        $sqlQuery = "INSERT INTO brewing ";
        $fields = "(brewName";
        $values = " VALUES('" . $recipe->name . "'";
        $vf = array();
        $counter = array();
		$vf["brewBrewerFirstName"] = $_POST["brewBrewerFirstName"];
		$vf["brewBrewerLastName"] = $_POST["brewBrewerLastName"];
		//$vf["brewName"] = $recipe->name;
		$vf["brewStyle"] = $recipe->style->name;
		$vf["brewCategory"] = ltrim($recipe->style->categoryNumber, "0");
		$vf["brewCategorySort"] = $recipe->style->categoryNumber;
		$vf["brewSubCategory"] = $recipe->style->styleLetter;      
        $vf["brewYield"] = $recipe->batchSize;
        $vf["brewInfo"] = $recipe->notes;
        // $vf["brewMethod"] = $recipe->type; 
        $counter["grain"] = 0;
        $counter["extract"] = 0;
        $counter["adjunct"] = 0;
        foreach($recipe->fermentables->fermentables as $fermentable){
            switch($fermentable->type){
                case "Grain":
                    $counter["grain"]++;
                    if($counter["grain"] <= 9){
                        $vf["brewGrain" . $counter["grain"]] = $fermentable->name;
                        $vf["brewGrain" . $counter["grain"] . "Weight"] = $fermentable->amount;
                    }
                    break;
                case "Extract":
                    $counter["extract"]++;
                    if($counter["extract"] <= 5){
                        $vf["brewExtract" . $counter["extract"]] = $fermentable->name;
                        $vf["brewExtract" . $counter["extract"] . "Weight"] = $fermentable->amount;
                    }
                    break;
                case "Dry Extract":
                    $counter["extract"]++;
                    if($counter["extract"] <= 5){
                        $vf["brewExtract" . $counter["extract"]] = $fermentable->name;
                        $vf["brewExtract" . $counter["extract"] . "Weight"] = $fermentable->amount;
                    }
                    break;
                case "Adjunct":
                    $counter["adjunct"]++;
                    if($counter["adjunct"] <= 9){
                        $vf["brewAddition" . $counter["adjunct"]] = $fermentable->name;
                        $vf["brewAddition" . $counter["adjunct"] . "Amt"] = $fermentable->amount;
                    }
                    break;
                case "Sugar":
                    $counter["adjunct"]++;
                    if($counter["adjunct"] <= 9){
                        $vf["brewAddition" . $counter["adjunct"]] = $fermentable->name;
                        $vf["brewAddition" . $counter["adjunct"] . "Amt"] = $fermentable->amount;
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
                $vf["brewMisc" . $counter["misc"] . "Name"] = $misc->name;
                $vf["brewMisc" . $counter["misc"] . "Type"] = $misc->type;
                $vf["brewMisc" . $counter["misc"] . "Use"] = $misc->useFor;
                $vf["brewMisc" . $counter["misc"] . "Time"] = $misc->time;
                $vf["brewMisc" . $counter["misc"] . "Amount"] = $misc->amount;
            }
        }
		*/
        $counter["hops"] = 0;
        foreach($recipe->hops->hops as $hop){
            $counter["hops"]++;
            if($counter["hops"] <= 9){
                $vf["brewHops" . $counter["hops"]] = $hop->name;
                $vf["brewHops" . $counter["hops"] . "Weight"] = $hop->amount * 1000; // BeerXML standard is kilograms
                $vf["brewHops" . $counter["hops"] . "IBU"] = $hop->alpha;
                $vf["brewHops" . $counter["hops"] . "Time"] = $hop->time;
                $vf["brewHops" . $counter["hops"] . "Use"] = $hop->use;
                $vf["brewHops" . $counter["hops"] . "Type"] = $hop->type;
                $vf["brewHops" . $counter["hops"] . "Form"] = $hop->form;
            }
        }

        $counter["yeast"] = 0;
        foreach($recipe->yeasts->yeasts as $yeast){
            $vf["brewYeast"] = $yeast->name;
            $vf["brewYeastMan"] = $yeast->labratory;
            $vf["brewYeastForm"] = $yeast->form;
            $vf["brewYeastType"] = $yeast->type;
            $vf["brewYeastAmount"] = $yeast->amount;
        }

        $vf["brewOG"] = 1 . substr($recipe->estimatedOriginalGravity,1,4);
        $vf["brewFG"] = 1 . substr($recipe->estimatedFinalGravity,1,4);
        //$vf["brewProcedure"] = $recipe->notes;
        $vf["brewPrimary"] = $recipe->primaryAge;
        $vf["brewPrimaryTemp"] = $recipe->primaryTemp;
        $vf["brewSecondary"] = $recipe->secondaryAge;
        $vf["brewSecondaryTemp"] = $recipe->secondaryTemp;
        $vf["brewOther"] = $recipe->tertiaryAge;
        $vf["brewOtherTemp"] = $recipe->tertiaryTemp;
        //$vf["brewAge"] = $recipe->age;
        //$vf["brewAgeTemp"] = $recipe->ageTemp * (9/5) + 32;
        //$vf["brewBitterness"] = $recipe->ibu;
        //$vf["brewLovibond"] = $recipe->estimatedColor;
        $vf["brewBrewerID"] = $_POST["brewBrewerID"];


        foreach($vf as $field=>$value){
            $fields .= "," . $field;
            $values .= ",'" . $value . "'";
        }

        $fields .= ")";
        $values .= ")";
        $sqlQuery .= $fields . $values;
		require ('Connections/config.php');

        mysql_select_db($database, $brewing) or die(mysql_error());
        $Result1 = mysql_query($sqlQuery, $brewing) or die(mysql_error());

        $this->insertedRecipes[mysql_insert_id()] = $recipe->name;
        }
    //}}}
}
//}}}
}
?>
