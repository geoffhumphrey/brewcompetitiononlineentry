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

//{{{ Misc
class Misc extends Parser{
	// fields within MISC tag
	public $name;
	public $version;
	public $amount;
    public $amountIsWeight;
    public $useFor;
	public $type;
    public $use;
	public $time;
	public $notes;

    // extensions
    public $displayAmount;
    public $inventory;
    public $displayTime;

	function startElement($parser,$tagName,$attrs) {
		$this->tag = $tagName;
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "MISC":
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
				case "USE":
					$this->use = $data;
					break;
				case "TIME":
					$this->time = $data;
					break;
				case "NOTES":
					$this->notes .= $data;
					break;
				case "TYPE":
					$this->type = $data;
					break;
                case "AMOUNT_IS_WEIGHT":
					$this->amountIsWeight = $data;
					break;
				case "USE_FOR":
					$this->useFor = $data;
					break;
                case "DISPLAY_AMOUNT":
					$this->displayAmount = $data;
					break;
                case "INVENTORY":
					$this->inventory = $data;
					break;
				case "DISPLAY_TIME":
					$this->displayTime = $data;
					break;
				default:
					break;
			}
		}
	}

}
//}}}

//{{{ Miscs
class Miscs extends Parser{
	//fields within MISCS tag
	public $miscs = array();

	function startElement($parser,$tagName,$attrs) {
		switch($tagName){
			case "MISC":
				$misc = new Misc();
				$misc->parse($parser,$this);
				$this->miscs[] = $misc;
				break;
			default:
				break;
		}
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "MISCS":
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
