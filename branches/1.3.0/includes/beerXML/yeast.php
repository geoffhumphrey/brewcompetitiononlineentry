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

//{{{ Yeast
class Yeast extends Parser{
	// fields within YEAST tag
	public $name;
	public $version;
	public $type;
	public $form;
	public $amount;
	public $amountIsWeight;
	public $laboratory;
	public $productID;
	public $minTemp;
	public $maxTemp;
	public $flocculation;
	public $notes;
	public $bestFor;
	public $maxReuse;
	public $timesCultured;
	public $addToSecondary;

    // extensions
    public $cultureDate;
    public $displayAmount;
    public $dispMinTemp;
    public $dispMaxTemp;
    public $inventory;

	function startElement($parser,$tagName,$attrs) {
		$this->tag = $tagName;
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "YEAST":
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
				case "FORM":
					$this->form = $data;
					break;
				case "AMOUNT":
					$this->amount = $data;
					break;
				case "AMOUNT_IS_WEIGHT":
					$this->amountIsWeight = $data;
					break;
				case "LABORATORY":
					$this->laboratory = $data;
					break;
				case "PRODUCT_ID":
					$this->productID = $data;
					break;
				case "MIN_TEMPERATURE":
					$this->minTemp = $data;
					break;
				case "MAX_TEMPERATURE":
					$this->maxTemp = $data;
					break;
				case "FLOCCULATION":
					$this->flocculation = $data;
					break;
				case "NOTES":
					$this->notes .= $data;
					break;
				case "BEST_FOR":
					$this->bestFor = $data;
					break;
				case "MAX_REUSE":
					$this->maxReuse = $data;
					break;
				case "TIMES_CULTURED":
					$this->timesCultured = $data;
					break;
				case "ADD_TO_SECONDARY":
					$this->addToSecondary = $data;
					break;
				case "CULTURE_DATE":
					$this->cultureDate = $data;
					break;
                case "DISPLAY_AMOUNT":
					$this->displayAmount = $data;
					break;
				case "DISPLAY_MIN_TEMP":
					$this->displayMinTemp = $data;
					break;
				case "DISPLAY_MAX_TEMP":
					$this->displayMaxTemp = $data;
					break;
				case "INVENTORY":
					$this->inventory = $data;
					break;
				default:
					break;
			}
		}
	}
}
//}}}



//{{{ Yeasts

class Yeasts extends Parser{
	// fields within YEASTS tag
	public $yeasts = array(); // array of yeast objects

	function startElement($parser,$tagName,$attrs) {
		switch($tagName){
			case "YEAST":
				$yeast = new Yeast();
				$yeast->parse($parser,$this);
				$this->yeasts[] = $yeast;
				break;
			default:
				break;
		}
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "YEASTS":
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
