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
//{{{ includes
include("style.php");
include("misc.php");
include("water.php");
include("equipment.php");
include("hops.php");
include("fermentables.php");
include("yeast.php");
include("mash.php");
include("recipe.php");
//}}}
//{{{ BeerXMLParser
class BeerXMLParser {
	
	public $inNode = false;
   	public $insideitem = false;
	public $tag = "";
	public $recipes = array();
	
	function startElement($parser,$tagName,$attrs) {
		$this->tag = $tagName;
		switch($tagName){
			case "RECIPE":
				$recipe = new Recipe();
				$recipe->parse($parser,$this);
				$this->recipes[] = $recipe;
				break;
			default:
				break;
		}
	}
	
	function endElement($parser,$tagName){
    }
	
	function nodeData($parser,$data) {
    }
	
	function BeerXMLParser($filename) {
		
		$xml_parser = xml_parser_create();
		xml_set_object($xml_parser,$this);
		xml_set_element_handler($xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($xml_parser, "nodeData");
        xml_parser_set_option($xml_parser,XML_OPTION_TARGET_ENCODING,"UTF-8");
		
		if (!($fp = fopen($filename,"r"))) {
		  die ("could not open RSS for input");
		  }
		  while ($data = fread($fp, 4096)) {
		    if (!xml_parse($xml_parser, $data, feof($fp))) {
		        die(sprintf("XML error: %s at line %d",
			    	xml_error_string(xml_get_error_code($xml_parser)),
			    	xml_get_current_line_number($xml_parser)));
			}
		  }
        
		xml_parser_free($xml_parser);
        fclose($fp);
        return $this->recipes;
	}
}
//}}}
?>
