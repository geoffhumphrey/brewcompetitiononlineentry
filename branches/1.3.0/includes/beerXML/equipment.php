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



//{{{ Equipment
class Equipment extends Parser{
	// fields within EQUIPMENT tag
	public $name;
	public $version;
	public $boilSize;
	public $batchSize;
	public $tunVolume;
	public $tunWeight;
	public $tunSpecificHeat;
	public $topUpWater;
	public $trubChillerLoss;
	public $evapRate;
    public $boilTime;
    public $calcBoilVolume;
    public $lauterDeadspace;
    public $topUpKettle;
    public $hopUtilization;
	public $notes;

	// extensions
    public $displayBoilSize;
	public $displayBatchSize;
	public $displayTunVolume;
	public $displayTunWeight;
	public $displayTopUpWater;
	public $displayTrubChillerLoss;
    public $displayLauterDeadspace;
    public $displayTopUpKettle;

    function startElement($parser,$tagName,$attrs) {
		$this->tag = $tagName;
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "EQUIPMENT":
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
				case "BOIL_SIZE":
					$this->boilSize = $data;
					break;
                case "BATCH_SIZE":
					$this->batchSize = $data;
					break;
				case "TUN_VOLUME":
					$this->tunVolume = $data;
					break;
				case "TUN_WEIGHT":
					$this->tunWeight = $data;
					break;
				case "TUN_SPECIFIC_HEAT":
					$this->tunSpecificHeat = $data;
					break;
				case "TOP_UP_WATER":
					$this->topUpWater = $data;
					break;
				case "TRUB_CHILLER_LOSS":
					$this->trubChillerLoss = $data;
					break;
				case "EVAP_RATE":
					$this->evapRate = $data;
					break;
                case "BOIL_TIME":
					$this->boilTime = $data;
					break;
				case "CALC_BOIL_VOLUME":
					$this->calcBoilVolume = $data;
					break;
				case "LAUTER_DEADSPACE":
					$this->lauterDeadspace = $data;
					break;
				case "TOP_UP_KETTLE":
					$this->topUpKettle = $data;
					break;
				case "HOP_UTILIZATION":
					$this->hopUtilization = $data;
					break;
				case "NOTES":
					$this->notes .= $data;
					break;
                case "DISPLAY_BOIL_SIZE":
					$this->displayBoilSize = $data;
					break;
                case "DISPLAY_BATCH_SIZE":
					$this->displayBatchSize = $data;
					break;
				case "DISPLAY_TUN_VOLUME":
					$this->displayTunVolume = $data;
					break;
				case "DISPLAY_TUN_WEIGHT":
					$this->displayTunWeight = $data;
					break;
				case "DISPLAY_TOP_UP_WATER":
					$this->displayTopUpWater = $data;
					break;
				case "DISPLAY_TRUB_CHILLER_LOSS":
					$this->displayTrubChillerLoss = $data;
					break;
				case "DISPLAY_LAUTER_DEADSPACE":
					$this->displayLauterDeadspace = $data;
					break;
				case "DISPLAY_TOP_UP_KETTLE":
					$this->displayTopUpKettle = $data;
					break;
				default:
					break;
			}
		}
	}

}
//}}}



//{{{ Equipments
class Equipments extends Parser{
	//fields within EQUIPMENTS tag
	public $equipment = array();

	function startElement($parser,$tagName,$attrs) {
		switch($tagName){
			case "EQUIPMENT":
				$equipment = new Equipment();
				$equipment->parse($parser,$this);
				$this->equipment[] = $equipment;
				break;
			default:
				break;
		}
	}

	function endElement($parser,$tagName) {
		switch($tagName){
			case "EQUIPMENTS":
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
