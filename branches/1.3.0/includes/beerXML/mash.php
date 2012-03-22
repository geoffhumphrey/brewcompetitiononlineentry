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

//{{{ MashStep
class MashStep extends Parser {

	// fields within MASH_STEP tag
	public $name;
	public $version;
	public $type;
	public $infuseAmount;
	public $stepTime;
	public $stepTemp;
	public $rampTime;
	public $endTemp;

    // extensions
    public $description;
	public $waterGrainRatio;
	public $decoctionAmount;
	public $infuseTemp;
    public $displayStepTemp;
    public $displayInfuseTemp;

	function startElement($parser,$tagName,$attrs) {
		$this->tag = $tagName;
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "MASH_STEP":
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
				case "INFUSE_AMOUNT":
					$this->infuseAmount = $data;
					break;
				case "STEP_TIME":
					$this->stepTime = $data;
					break;
				case "STEP_TEMP":
					$this->stepTemp = $data;
					break;
				case "RAMP_TIME":
					$this->rampTime = $data;
					break;
				case "END_TEMP":
					$this->endTemp = $data;
					break;
				case "DESCRIPTION":
					$this->description = $data;
					break;
				case "WATER_GRAIN_RATIO":
					$this->waterGrainRatio = $data;
					break;
				case "DECOCTION_AMOUNT":
					$this->decoctionAmount = $data;
					break;
				case "INFUSE_TEMP":
					$this->infuseTemp = $data;
					break;
                case "DISPLAY_STEP_TEMP":
					$this->displayStepTemp = $data;
					break;
				case "DISPLAY_INFUSE_AMOUNT":
					$this->displayInfuseAmount = $data;
					break;
				default:
					break;
			}
		}
	}
}

//}}}



//{{{ Mash

class Mash extends Parser{

	// fields within MASH tag

	public $name;

	public $version;

	public $grainTemp;

	public $tunTemp;

	public $spargeTemp;

	public $ph;

	public $tunWeight;

	public $tunSpecificHeat;

	public $equipAdjust;

	public $notes;



    // extensions

    public $displayGrainTemp;

    public $displayTunTemp;

    public $displaySpageTemp;

    public $displayTunWeight;



	public $mashSteps = array(); // array of MashStep objects

	function startElement($parser,$tagName,$attrs) {

		$this->tag = $tagName;

		switch($tagName){

			case "MASH_STEP":

				$mashStep = new mashStep();

				$mashStep->parse($parser,$this);

				$this->mashSteps[] = $mashStep;

				break;

			default:

				break;

		}

	}



	function endElement($parser,$tagName) {

		switch($tagName){

			case "MASH":

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

				case "GRAIN_TEMP":

					$this->grainTemp = $data;

					break;

				case "TUN_TEMP":

					$this->tunTemp = $data;

					break;

				case "SPARGE_TEMP":

					$this->spargeTemp = $data;

					break;

				case "PH":

					$this->ph = $data;

					break;

				case "TUN_WEIGHT":

					$this->tunWeight = $data;

					break;

				case "TUN_SPECIFIC_HEAT":

					$this->tunSpecificHeat = $data;

					break;

				case "EQUIP_ADJUST":

					$this->equipAdjust = $data;

					break;

				case "NOTES":

					$this->notes .= $data;

					break;

                case "DISPLAY_GRAIN_TEMP":

					$this->displayGrainTemp = $data;

					break;

				case "DISPLAY_TUN_TEMP":

					$this->displayTunTemp = $data;

					break;

				case "DISPLAY_SPAGE_TEMP":

					$this->displaySpargeTemp = $data;

					break;

				case "DISPLAY_TUN_WEIGHT":

					$this->displayTunWeight = $data;

					break;

				default:

					break;

			}

		}

	}



}

//}}}



?>

