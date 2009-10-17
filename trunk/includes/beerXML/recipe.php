<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */
//{{{ License
// +------------------------------------------------------------------------+
// | BeerXML Parser - implements full BeerXML                               |
// |                     1.0 specification                                  |
// | 							                                            |
// | NOTES - measurements in METRIC                 			            |
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

require_once("parser.php");

//{{{ Recipe
class Recipe extends Parser{
	// fields within RECIPE tag
	public $name;
	public $version;
	public $type;
	public $brewer;
	public $assistantBrewer;
	public $batchSize;
	public $boilSize;
	public $boilTime;
	public $efficiency;
	public $og;
	public $fg;
	public $style; // style object
	public $hops; // hops object
	public $fermentables; //fermentables object
	public $yeasts; //yeasts object
	public $mash; // mash object
    public $water; // water object
    public $miscs; // miscs object
    public $equipments; // equipments object
	public $notes;
	public $tasteNotes;
	public $tasteRating;
	public $carbonation;
	public $fermentationStages;
	public $primaryAge;
	public $primaryTemp;
	public $secondaryAge;
	public $secondaryTemp;
	public $tertiaryAge;
	public $age;
	public $ageTemp;
	public $primingSugarName;
	public $primingSugarEquiv;
	public $kegPrimingFactor;
	public $carbonationTemp;
	public $date;

    // extensions
	public $estimatedOriginalGravity;
	public $estimatedFinalGravity;
	public $estimatedColor;
	public $ibu;
	public $ibuMethod;
	public $estimatedABV;
	public $abv;
	public $actualEfficiency;
	public $calories;
    public $displayBatchSize;
    public $displayBoilSize;
    public $displayOG;
    public $displayFG;
    public $displayPrimaryTemp;
    public $displaySecondaryTemp;
    public $displayTertiaryTemp;
    public $displayAgeTemp;
    public $carbonationUsed;
    public $displayCarbTemp;

	function startElement($parser,$tagName,$attrs) {
		$this->tag = $tagName;
	switch($tagName){
			case "HOPS":
				$hops = new Hops();
				$hops->parse($parser,$this);
				$this->hops = $hops;
				break;
			case "FERMENTABLES":
				$fermentables = new Fermentables();
				$fermentables->parse($parser,$this);
				$this->fermentables = $fermentables;
				break;
			case "YEASTS":
				$yeasts = new Yeasts();
				$yeasts->parse($parser,$this);
				$this->yeasts = $yeasts;
				break;
			case "STYLE":
				$style = new Style();
				$style->parse($parser,$this);
				$this->style = $style;
				break;
			case "MASH":
				$mash = new Mash();
				$mash->parse($parser,$this);
				$this->mash = $mash;
				break;
            case "WATER": // beerXML SPEC DOES NOT INCLUDE A WATERS TAG, ONLY WATER...BEERSMITH IS IMPLEMENTING INCORRECTLY
				$water = new Waters();
				$water->parse($parser,$this);
				$this->water = $water;
				break;
            case "MISCS":
				$miscs = new Miscs();
				$miscs->parse($parser,$this);
				$this->miscs = $miscs;
				break;
            case "EQUIPMENT": // SKIPPING EQUIPMENTS OBJECT AS BEERSMITH DOES NOT IMPLEMENT CORRECTLY
				$equipment = new Equipment();
				$equipment->parse($parser,$this);
				$this->equipments = $equipment;
				break;
			default:
				break;
		}
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "RECIPE":
				xml_set_object($parser,$this->parser);
				break;
			default:
				break;
		}
	}

	function nodeData($parser,$data) {
		$data = ltrim($data);
		if($data != ""){
			switch($this->tag){
				case "NAME":
					$this->name = $data;
					break;
				case "VERSION":
					$this->version = $data;
					break;
				case "TYPE":
					$this->type = $data;
					break;
				case "BREWER":
					$this->brewer = $data;
					break;
				case "ASST_BREWER":
					$this->assistantBrewer = $data;
					break;
				case "BATCH_SIZE":
					$this->batchSize = $data;
					break;
				case "BOIL_SIZE":
					$this->boilSize = $data;
					break;
				case "BOIL_TIME":
					$this->boilTime = $data;
					break;
				case "EFFICIENCY":
					$this->efficiency = $data;
					break;
				case "OG":
					$this->og = $data;
					break;
				case "FG":
					$this->fg = $data;
					break;
				case "NOTES":
					$this->notes .= $data;
					break;
				case "TASTE_NOTES":
					$this->tasteNotes .= $data;
					break;
				case "TASTE_RATING":
					$this->tasteRating = $data;
					break;
				case "CARBONATION":
					$this->carbonation = $data;
					break;
				case "FERMENTATION_STAGES":
					$this->fermentationStages = $data;
					break;
				case "PRIMARY_AGE":
					$this->primaryAge = $data;
					break;
				case "PRIMARY_TEMP":
					$this->primaryTemp = $data;
					break;
				case "SECONDARY_AGE":
					$this->secondaryAge = $data;
					break;
				case "SECONDARY_TEMP":
					$this->secondaryTemp = $data;
					break;
				case "TERTIARY_AGE":
					$this->tertiaryAge = $data;
					break;
				case "AGE":
					$this->age = $data;
					break;
				case "AGE_TEMP":
					$this->ageTemp = $data;
					break;
				case "CARBONATION_USED":
					$this->carbonationUsed = $data;
					break;
				case "PRIMING_SUGAR_NAME":
					$this->primingSugarName = $data;
					break;
				case "PRIMING_SUGAR_EQUIV":
					$this->primingSugarEquiv = $data;
					break;
				case "KEG_PRIMING_FACTOR":
					$this->kegPrimingFactor = $data;
					break;
				case "CARBONATION_TEMP":
					$this->carbonationTemp = $data;
					break;
				case "DATE":
					$this->date = $data;
					break;
				case "EST_OG":
					$this->estimatedOriginalGravity = $data;
					break;
				case "EST_FG":
					$this->estimatedFinalGravity = $data;
					break;
				case "EST_COLOR":
					$this->estimatedColor = $data;
					break;
				case "IBU":
					$this->ibu = $data;
					break;
				case "IBU_METHOD":
					$this->ibuMethod = $data;
					break;
				case "EST_ABV":
					$this->estimatedABV = $data;
					break;
				case "ABV":
					$this->abv = $data;
					break;
				case "ACTUAL_EFFICIENCY":
					$this->actualEfficiency = $data;
					break;
				case "CALORIES":
					$this->calories = $data;
					break;
                case "DISPLAY_BATCH_SIZE":
                    $this->displayBatchSize;
                    break;
				case "DISPLAY_BOIL_SIZE":
                    $this->displayBoilSize;
                    break;
				case "DISPLAY_OG":
                    $this->displayOG;
                    break;
				case "DISPLAY_FG":
                    $this->displayFG;
                    break;
				case "DISPLAY_PRIMARY_TEMP":
                    $this->displayPrimaryTemp;
                    break;
				case "DISPLAY_SECONDARY_TEMP":
                    $this->displaySecondaryTemp;
                    break;
				case "DISPLAY_TERTIARY_TEMP":
                    $this->displayTertiaryTemp;
                    break;
				case "DISPLAY_AGE_TEMP":
                    $this->displayAgeTemp;
                    break;
				case "CARBONATION_USED":
                    $this->carbonationUsed;
                    break;
				case "DISPLAY_CARB_TEMP":
                    $this->displayCarbTemp;
                    break;
                case "DATE":
                    $this->date;
                    break;
				default:
					break;
			}
		}
	}

}
//}}}
?>
