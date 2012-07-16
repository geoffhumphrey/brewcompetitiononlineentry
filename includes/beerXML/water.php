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

//{{{ Water
class Water extends Parser{
	// fields within WATER tag
	public $name;
	public $version;
	public $amount;
	public $calcium;
	public $bicarbonate;
	public $sulfate;
	public $chloride;
	public $sodium;
	public $magnesium;
	public $ph;
	public $notes;

    //extensions
    public $displayAmount;

	function startElement($parser,$tagName,$attrs) {
		$this->tag = $tagName;
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "WATER":
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
				case "AMOUNT":
					$this->amount = $data;
					break;
                case "CALCIUM":
					$this->calcium = $data;
					break;
				case "BICARBONATE":
					$this->bicarbonate = $data;
					break;
				case "SULFATE":
					$this->sulfate = $data;
					break;
				case "CHLORIDE":
					$this->chloride = $data;
					break;
				case "SODIUM":
					$this->sodium = $data;
					break;
				case "MAGNESIUM":
					$this->magnesium = $data;
					break;
				case "PH":
					$this->ph = $data;
					break;
				case "NOTES":
					$this->notes .= $data;
					break;
                case "DISPLAY_AMOUNT":
					$this->displayAmount = $data;
					break;
				default:
					break;
			}
		}
	}

}
//}}}

//{{{ Waters
class Waters extends Parser{
	//fields within WATERS tag
	public $waters = array();

	function startElement($parser,$tagName,$attrs) {
		switch($tagName){
			case "WATER":
				$water = new Water();
				$water->parse($parser,$this);
				$this->waters[] = $water;
				break;
			default:
				break;
		}
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "WATERS":
				xml_set_object($parser,$this->parser);
				break;
			default: break;
		}
		$this->tag = $tagName;
	}

	function nodeData($parser,$data) {
	
    }

}
//}}}

?>
