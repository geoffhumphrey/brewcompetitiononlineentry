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

//{{{ Hop
class Hop extends Parser{
	// fields within HOP tag
	public $name;
	public $version;
	public $origin;
	public $alpha;
	public $amount;
	public $use;
	public $type;
	public $time;
	public $form;
	public $notes;
	public $beta;
	public $hsi;
    public $substitutes;
    public $humulene;
    public $caryophyllene;
    public $cohumulone;
    public $myrcene;

    // extensions
    public $displayAmount;
	public $displayTime;
    public $inventory;

	function startElement($parser,$tagName,$attrs) {
		$this->tag = $tagName;
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "HOP":
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
				case "ORIGIN":
					$this->origin = $data;
					break;
				case "ALPHA":
					$this->alpha = $data;
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
                case "TYPE":
					$this->type = $data;
					break;
                case "FORM":
					$this->form = $data;
					break;
				case "NOTES":
					$this->notes .= $data;
					break;
				case "BETA":
					$this->beta = $data;
					break;
				case "HSI":
					$this->hsi = $data;
					break;
                case "SUBSTITUTES":
					$this->substitutes = $data;
					break;
				case "HUMULENE":
					$this->humulene = $data;
					break;
				case "CARYOPHYLLENE":
					$this->caryophyllene = $data;
					break;
				case "COHUMULONE":
					$this->cohumulone = $data;
					break;
                case "MYRCENE":
					$this->myrcene = $data;
					break;
				case "DISPLAY_AMOUNT":
					$this->displayAmount = $data;
					break;
				case "DISPLAY_TIME":
					$this->displayTime = $data;
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

//{{{ Hops
class Hops extends Parser{
	//fields within HOPS tag
	public $hops = array();

	function startElement($parser,$tagName,$attrs) {
		switch($tagName){
			case "HOP":
				$hop = new Hop();
				$hop->parse($parser,$this);
				$this->hops[] = $hop;
				break;
			default:
				break;
		}
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "HOPS":
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
