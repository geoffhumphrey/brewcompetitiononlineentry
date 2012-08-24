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

//{{{ Style
class Style extends Parser{
	// fields within STYLE tag
	public $name;
	public $version;
	public $category;
	public $categoryNumber;
	public $styleLetter;
	public $styleGuide;
	public $type;
	public $ogMin;
	public $ogMax;
	public $fgMin;
	public $fgMax;
	public $ibuMin;
	public $ibuMax;
	public $colorMin;
	public $colorMax;
	public $carbMin;
	public $carbMax;
	public $abvMin;
	public $abvMax;
	public $notes;
	public $profile;
	public $ingredients;
	public $examples;

    // extensions
    public $displayOGMin;
    public $displayOGMax;
    public $displayFGMin;
    public $displayFGMax;
    public $displayColorMin;
    public $displayColorMax;
    public $ogRange;
    public $fgRange;
    public $ibuRange;
    public $carbRange;
    public $colorRange;
    public $abvRange;
	function startElement($parser,$tagName,$attrs) {

		$this->tag = $tagName;
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "STYLE":
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
				case "CATEGORY":
					$this->category = $data;
					break;
				case "CATEGORY_NUMBER":
					$this->categoryNumber = $data;
					break;
				case "STYLE_LETTER":
					$this->styleLetter = $data;
					break;
				case "STYLE_GUIDE":
					$this->styleGuide = $data;
					break;
				case "TYPE":
					$this->type = $data;
					break;
				case "OG_MIN":
					$this->ogMin = $data;
					break;
				case "OG_MAX":
					$this->ogMax = $data;
					break;
				case "FG_MIN":
					$this->fgMin = $data;
					break;
				case "FG_MAX":
					$this->fgMax = $data;
					break;
				case "IBU_MIN":
					$this->ibuMin = $data;
					break;
				case "IBU_MAX":
					$this->ibuMax = $data;
					break;
				case "COLOR_MIN":
					$this->colorMin = $data;
					break;
				case "COLOR_MAX":
					$this->colorMax = $data;
					break;
				case "CARB_MIN":
					$this->carbMin = $data;
					break;
				case "CARB_MAX":
					$this->carbMax = $data;
					break;
				case "ABV_MIN":
					$this->abvMin = $data;
					break;
				case "ABV_MAX":
					$this->abvMax = $data;
					break;
				case "NOTES":
					$this->notes .= $data;
					break;
				case "PROFILE":
					$this->profile .= $data;
					break;
				case "INGREDIENTS":
					$this->ingredients .= $data;
					break;
				case "EXAMPLES":
					$this->examples .= $data;
					break;
                case "DISPLAY_OG_MIN":
					$this->displayOGMin = $data;
					break;
				case "DISPLAY_OG_MAX":
					$this->displayOGMax = $data;
					break;
				case "DISPLAY_FG_MIN":
					$this->displayFGMin = $data;
					break;
				case "DISPLAY_FG_MAX":
					$this->displayFGMax = $data;
					break;
                case "DISPLAY_COLOR_MIN":
					$this->displayColorMin = $data;
					break;
				case "DISPLAY_COLOR_MAX":
					$this->displayColorMax = $data;
					break;
                case "OG_RANGE":
                    $this->ogRange = $data;
                    break;
                case "FG_RANGE":
                    $this->fgRange = $data;
                    break;
                case "IBU_RANGE":
                    $this->ibuRange = $data;
                    break;
                case "CARB_RANGE":
                    $this->carbRange = $data;
                    break;
                case "COLOR_RANGE":
                    $this->colorRange = $data;
                    break;
                case "ABV_RANGE":
                    $this->abvRange = $data;
                    break;
				default:
					break;
			}
		}
	}

}
//}}}

?>
