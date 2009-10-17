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

//{{{ Fermentable
class Fermentable extends Parser{
	// fields within FERMENTABLES tag
	public $name;
	public $version;
	public $type;
	public $amount;
	public $yield;
	public $color;
	public $addAfterBoil;
	public $origin;
	public $supplier;
	public $notes;
	public $coarseFineDiff;
	public $moisture;
	public $diastaticPower;
	public $protein;
	public $maxInBatch;
	public $recommendMash;
	public $ibuGalPerLb;
	public $extractSubstitute;

    // extensions
    public $inventory;
	public $potential;
	public $displayColor;
    public $displayAmount;
	function startElement($parser,$tagName,$attrs) {

		$this->tag = $tagName;
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "FERMENTABLE":
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
				case "AMOUNT":
					$this->amount = $data;
					break;
				case "YIELD":
					$this->yield = $data;
					break;
				case "COLOR":
					$this->color = $data;
					break;
				case "ADD_AFTER_BOIL":
					$this->addAfterBoil = $data;
					break;
				case "ORIGIN":
					$this->origin = $data;
					break;
				case "SUPPLIER":
					$this->supplier = $data;
					break;
				case "NOTES":
					$this->notes = $data;
					break;
				case "COARSE_FINE_DIFF":
					$this->coarseFineDiff = $data;
					break;
				case "MOISTURE":
					$this->moisture = $data;
					break;
				case "DIASTATIC_POWER":
					$this->diastaticPower = $data;
					break;
				case "PROTEIN":
					$this->protein = $data;
					break;
				case "MAX_IN_BATCH":
					$this->maxInBatch = $data;
			 	break;
				case "RECCOMENDED_MASH":
					$this->reccomendedMash = $data;
				break;
				case "IBU_GAL_PER_LB":
					$this->ibuGalPerLb = $data;
					break;
				case "INVENTORY":
					$this->inventory = $data;
					break;
				case "POTENTIAL":
					$this->potential = $data;
				break;
				case "DISPLAY_COLOR":
					$this->displayColor = $data;
				break;
                case "DISPLAY_AMOUNT":
					$this->displayAmount = $data;
					break;
				case "EXTRACT_SUBSTITUTE":

				$this->extractSubstitute = $data;
					break;

			default:
					break;
		}

		}

	}


}
//}}}

//{{{ Fermentables
class Fermentables extends Parser{
	// fields within FERMENTABLES tag
	public $fermentables = array(); // array of fermentable objects

	function startElement($parser,$tagName,$attrs) {
		switch($tagName){
			case "FERMENTABLE":
				$fermentable = new Fermentable();
				$fermentable->parse($parser,$this);
				$this->fermentables[] = $fermentable;
				break;
			default:
				break;
		}
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "FERMENTABLES":
				xml_set_object($parser,$this->parser);
				break;
			default:
				break;
		}

	}

	function nodeData($parser,$data) {

	}

}
//}}}

?>
