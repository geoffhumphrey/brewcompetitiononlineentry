<?php
/** -------------------------- Languages Available ----------------------------------------------
 * Array of languages available for translation
 * Associative array of available translation languages.
 * First in the pair is the label name (should be in native language)
 * Second in the pair is the official WWW3 language tag, exactly as it appears there
 * This WWW3 language tag should be the beginining of the language file (e.g., en-US.lang.php)
 * See https://www.loc.gov/standards/iso639-2/php/code_list.php
 * located in its parent language subfolder in the lang folder (e.g., /lang/en/)
 * More at https://www.w3.org/International/articles/language-tags/
 *
 */

$languages = array(
    "pt-BR" => "Brazilian Portuguese",
    "cs-CZ" => "Czech",
    "en-US" => "English (US)",
    "fr-FR" => "French",
    "es-419" => "Spanish (Latin America)"
);

/** -------------------------- Theme File names and  Display Name -------------------------------
 * The first item is the the CSS file name (without .css)
 * The second item is the display name for use in Site Preferences
 * The file name will be stored in the preferences DB table row called prefsTheme and called by all pages
 */

$theme_name = array(
    "default" => "BCOE&amp;M Default (Gray)",
    "bruxellensis" => "Bruxellensis (Blue-Gray)",
    // "claussenii" => "Claussenii (Green)",
    // "naardenensis" => "Naardenensis (Teal)"
);

// Failsafe fallback if prefsTheme session var value is a deprecated theme.
if (($_SESSION['prefsTheme'] == "claussenii") || ($_SESSION['prefsTheme'] == "naardenensis")) $_SESSION['prefsTheme'] == "default";

// -------------------------- Countries List ----------------------------------------------------
// Array of countries to utilize when users sign up and for competition info
// Replaces countries DB table for better performance
$countries = array("United States","Australia","Canada","Ireland","United Kingdom","Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Antigua and Barbuda","Argentina","Armenia","Aruba","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Bouvet Island","Brazil","British Indian Ocean Territory","Brunei Darussalam","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Christmas Island","Cocos (Keeling) Islands","Colombia","Comoros","Congo","Congo, The Democratic Republic of The","Cook Islands","Costa Rica","Cote D'ivoire","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Easter Island","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands (Malvinas)","Faroe Islands","Fiji","Finland","France","French Guiana","French Polynesia","French Southern Territories","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guinea","Guinea-bissau","Guyana","Haiti","Heard Island and Mcdonald Islands","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea, North","Korea, South","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libyan Arab Jamahiriya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Micronesia, Federated States of","Moldova, Republic of","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","Northern Mariana Islands","Norway","Oman","Pakistan","Palau","Palestinian Territory","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Pitcairn","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Helena","Saint Kitts and Nevis","Saint Lucia","Saint Pierre and Miquelon","Saint Vincent and The Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia and Montenegro","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Georgia/South Sandwich Islands","Spain","Sri Lanka","Sudan","Suriname","Svalbard and Jan Mayen","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania, United Republic of","Thailand","Timor-leste","Togo","Tokelau","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Turks and Caicos Islands","Tuvalu","Uganda","Ukraine","United Arab Emirates","United States Minor Outlying Islands","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands, British","Virgin Islands, U.S.","Wallis and Futuna","Western Sahara","Yemen","Zambia","Zimbabwe","Other");

$us_state_abbrevs_names = array(
    'AL' => 'Alabama',
    'AK' => 'Alaska',
    'AS' => 'American Samoa',
    'AZ' => 'Arizona',
    'AR' => 'Arkansas',
    'CA' => 'California',
    'CO' => 'Colorado',
    'CT' => 'Connecticut',
    'DE' => 'Delaware',
    'DC' => 'District of Columbia',
    'FL' => 'Florida',
    'FM' => 'Federated States of Micronesia',
    'GA' => 'Georgia',
    'GU' => 'Guam',
    'HI' => 'Hawaii',
    'ID' => 'Idaho',
    'IL' => 'Illinois',
    'IN' => 'Indiana',
    'IA' => 'Iowa',
    'KS' => 'Kansas',
    'KY' => 'Kentucky',
    'LA' => 'Louisiana',
    'ME' => 'Maine',
    'MD' => 'Maryland',
    'MA' => 'Massachusetts',
    'MH' => 'Marshall Islands',
    'MI' => 'Michigan',
    'MN' => 'Minnesota',
    'MP' => 'Northern Mariana Islands',
    'MS' => 'Mississippi',
    'MO' => 'Missouri',
    'MT' => 'Montana',
    'NE' => 'Nebraska',
    'NV' => 'Nevada',
    'NH' => 'New Hampshire',
    'NJ' => 'New Jersey',
    'NM' => 'New Mexico',
    'NY' => 'New York',
    'NC' => 'North Carolina',
    'ND' => 'North Dakota',
    'OH' => 'Ohio',
    'OK' => 'Oklahoma',
    'OR' => 'Oregon',
    'PA' => 'Pennsylvania',
    'PR' => 'Puerto Rico',
    'PW' => 'Palau',
    'RI' => 'Rhode Island',
    'SC' => 'South Carolina',
    'SD' => 'South Dakota',
    'TN' => 'Tennessee',
    'TX' => 'Texas',
    'UT' => 'Utah',
    'VT' => 'Vermont',
    'VI' => 'Virgin Islands',
    'VA' => 'Virginia',
    'WA' => 'Washington',
    'WV' => 'West Virginia',
    'WI' => 'Wisconsin',
    'WY' => 'Wyoming'
);

$ca_state_abbrevs_names = array(
    'AB' => 'Alberta', 
    'BC' => 'British Columbia', 
    'MB' => 'Manitoba', 
    'NB' => 'New Brunswick', 
    'NL' => 'Newfoundland and Labrador', 
    'NT' => 'Northwest Territories', 
    'NS' => 'Nova Scotia', 
    'NU' => 'Nunavut',
    'ON' => 'Ontario', 
    'PE' => 'Prince Edward Island', 
    'QC' => 'Quebec', 
    'SK' => 'Saskatchewan', 
    'YT' => 'Yukon Territory'
);

$aus_state_abbrevs_names = array(
    'ACT' => 'Australian Capital Territory',
    'NSW' => 'New South Wales',
    'NT' => 'Northern Territory',
    'QLD' => 'Queensland',
    'SA' => 'South Australia',
    'TAS' => 'Tasmania',
    'VIC' => 'Victoria',
    'WA' => 'Western Australia'
);

// -------------------------- Tie break rules ---------------------------------------------------
// List of existing rules for the tie break for ordering the best brewers.
// The order of the rules will be chosen during setup
$tie_break_rules = array(
    "",
    "TBTotalPlaces",
    "TBTotalExtendedPlaces",
    "TBFirstPlaces",
    "TBNumEntries",
    "TBMinScore",
    "TBMaxScore",
    "TBAvgScore"
    //,"TBRandom"
);

/**
 * -------------------------- Clubs List ---------------------------
 * Future release:
 * Convert the array to JSON array and move to contest_info DB table - 
 * contestClubs column.
 * 
 * Updated January 11, 2024
 */

$club_array = array(
    "1.090",
    "#sovai",
    "10 Paces Brewing",
    "1000 IBUS",
    "1337 Brewing Collective",
    "The 1620 Home Brew Club",
    "2 Live Brew",
    "235 Brewing",
    "3 Bridges City Homebrew Club",
    "3-5-4 Brewers",
    "395brewers",
    "3Branch Brewing",
    "3CZN Clase Combativa Cervecera Zona Norte, Buenos Aires, Argentina",
    "3D Home Brewing",
    "3DK Brewers",
    "3M Homebrew Club",
    "3rd Coast Homebrewers",
    "39N Homebrewers",
    "4 Guys &amp; a Keg",
    "400 North Brewing",
    "45th ParaAlers",
    "50 West",
    "5095 Brew Crew",
    "5150 Brewing Club",
    "5280 Fermentation Society",
    "542 Brewing",
    "60 Minutemen",
    "616 Brewing",
    "808brewing",
    "A Few Who Brew",
    "A Tribe Called Grist",
    "ABBA (Association Belge Des Brasseurs Amateurs)",
    "ABC Agway Brewing Club",
    "ABREWCADABREW",
    "Acerva Baiana",
    "Acerva Gaucha",
    "Adams County Brew Crew (AC/BC)",
    "Addis Ababa Beer Club",
    "Adrian Brewers Guild",
    "AEgir Brew Club",
    "Affinity Home Brewing Club",
    "Aggie All-Grainers",
    "Agravain Home Brewers",
    "A-HELAF",
    "AHP Brew Club",
    "Albany Brew Crafters (ABC)",
    "Alchemists of Buena Vista - Local 220",
    "Alchemy Brewing Club of St. Louis",
    "Alchemy Fermentations",
    "Alcohol Through Fermentation",
    "Alderly Bootleggers",
    "Ale &amp; Lager Enthusiasts of Saskatchewan",
    "Ale &amp; Lager Independent Enthusiasts Deutschland (Ale.L.I.E.D.)",
    "Ale Abiders",
    "Ale and Lager Enthusiasts of Streator (ALES)",
    "Ale Crafters",
    "Ale Riders Homebrew Club",
    "Ale Satan Homebrew Club",
    "Alechemie",
    "Ale-ian Society",
    "ALEiens Homebrew Club",
    "Alementary Brewing Society",
    "ALERS",
    "Aleuminati Alechemists",
    "All Things Fermented",
    "Allegan Homebrewers Club",
    "All-Grain Gangsters",
    "Almaden Brewers",
    "Aloha Brewers Club",
    "Alpha Bleeders",
    "Alpine Brewer's Guild",
    "Altus Homebrew Club",
    "Amateur Brewers Of Victoria",
    "Amateursville Brew Club",
    "American Breweriana Assn.",
    "American Institute of Chemical Engineers At the CU Boulder Homebrew Team",
    "Americus Homebrewers Association (AHA!)",
    "Ames Brew Club",
    "Ames Brewers League",
    "Amylase",
    "Anderson Homebrew Club",
    "Andover-Alfred Homebrewers Guild",
    "Anglican Brewing Club",
    "Angry Beaver Brewers",
    "Animas Alers",
    "Ankeny Area Brewer's Club (AABC)",
    "Ann Arbor Beer Club",
    "Ann Arbor Brewers Guild",
    "Annapolis Homebrew Club",
    "ANNiHiLATED",
    "Anonymous Alers",
    "Anti-Gravity Brew Club",
    "Antioch Sud Suckers",
    "Apex BBQ Brew Club",
    "Appalachian Brew Club (ABC)",
    "Appalachian Brewers Association",
    "Appleton Libation Enthusiasts",
    "Ararat Shrine \"Beer Masters\" Home Brewing Club",
    "Argyle Suds Society",
    "Aristocrats Beer Society",
    "Arizona Society of Homebrewers",
    "Arkansas Hillbilly Homebrew Club",
    "Arkansas Valley Homebrewing",
    "Ashe Brew Club",
    "Ashtabula Area Homebrewers",
    "Asociacion Civil Somos Cerveceros",
    "Asociacion De Cerveceros Caseros Ecuador",
    "Association of Bloomington-Normal Brewers",
    "Association of Long Island Homebrew Clubs",
    "Athens Homebrew Club",
    "Athens League of Extraordinary Zymurgists (ALEZ)",
    "Atlanta Brewers Club",
    "Atlantic County Mashers",
    "Auburn Brew Club",
    "Auckland Guild Of Winemakers",
    "Augusta Homebrewers Association",
    "Aurora Brew Club",
    "Aurora Breweralis / Northern Lights",
    "Aurora City Brew Club",
    "Austin Zealots",
    "Awesome Brewers, Great Job!",
    "Awesome Brewing Club, Defenders of Everything Fine, Good, and Homebrewed, Imbibe",
    "AY Base Brewing",
    "B.A.S.H. (Butler Area Society of Homebrewers)",
    "B.Ar.F. (Brewing Around Friends)",
    "B.O.G. (Brewers of the Gorge)",
    "B.O.O.B.S. (Bluejays of Ohio Brewing Society)",
    "B.R.E.W. FIU",
    "B.R.E.W.S. (Border Residents Engineering Wicked Suds)",
    "B.U.Z. Bootheel United Zymurgist",
    "B3 Brewing Co.",
    "BABBLE Homerewing Club of Lake County",
    "Back Bay Brew Krewe",
    "Back Yard Obsessive Brewers (BYOB)",
    "Back Yard Outlaw Brewers (BYOB)",
    "Backcountry Homebrew Club",
    "Backwoods Carboys",
    "Backyard Brewers",
    "Backyard Brewing Club",
    "Bad Decisions",
    "Bad News Home Brew Club",
    "Badger Shack Brewers",
    "Badger's Den Brewing Club",
    "Bakerfield FOAM",
    "Baltibrew",
    "Band of Brewers",
    "Band of Media Brewers (BOMB)",
    "Barb City Brew Club",
    "Bare Bones Brewers",
    "Bare-Knuckled Brewers",
    "Barley Bandits",
    "Barley Coherent",
    "Barley Legal Homebrew Club",
    "Barley Legal Homebrewers",
    "Barley Literates Homebrew Club",
    "Barley Mob Brewers",
    "Barley Monks",
    "Barley Sober Brewing Club",
    "Barleyhoppers Brewing Club",
    "Barley's Angels Milwaukee",
    "Barndoor Brewers",
    "Barrel Feels",
    "Bartlesville Area Brewing Enthusiasts (BABE)",
    "Basic Science Brewers",
    "Basin Brewers Homebrew Club",
    "Basking Ridge Brewers Assn",
    "Batavia Brew Club",
    "Bath Water Homebrewers",
    "Bathtub Brews",
    "Battleground Brewers Guild",
    "Bay Area Barley's Angels",
    "Bay Area Brew Crew",
    "Bay Area Driveway Brewers",
    "Bay Area Mashers",
    "Bay Area Mashtronauts",
    "Bay Area Society of Homebrewers (BASH)",
    "Bay de Noc Brewers",
    "Bayou Beer Society",
    "Bayside Brewers Club",
    "BDBREWERS",
    "Beacon Pointe Brew Club",
    "Bearded Brewers BC",
    "Beaver County Homebrewers",
    "BEAVR",
    "Beer Alchemists of Coastal Carolina",
    "Beer Appreication Club (BAC)",
    "Beer Barons of Milwaukee",
    "Beer Brew Girls",
    "Beer Can Island Brewers",
    "Beer Club Of Japan Inc",
    "Beer Enthusiast",
    "Beer Enthusiasts League of Conneticut Homebrewers",
    "Beer Geek Brewers",
    "Beer Here, Now.",
    "Beer Is My Passion (BIMP)",
    "BEER MAFIA!",
    "Beer Me Brew Club",
    "Beer Minds",
    "Beer Necessities, The",
    "Beer Pressure of the Nature Coast",
    "Beer Quest",
    "Beer Renegades of Everett Washington (BREW)",
    "Beer Republic-Monroe Homebrew Club",
    "Beer UnderGround",
    "Beerded Clan, The",
    "BeerKnobbers",
    "BeerShack Brewers",
    "BEERZ",
    "Beeston Beer Circle",
    "Bell Gardens Brothers Brewing Co.",
    "Belle City Home Brewers and Vintners",
    "Belle River Brewery",
    "Bellevegas Homebrew Club",
    "Bellingham Homebrewers Guild",
    "Bells Flats Brewers",
    "Benson Homebrew Club",
    "Bergen County Brew Crew",
    "Bergen County Brewmasters",
    "Berks County Homebrew Club",
    "Berks/Schuylkill Brewers Guild",
    "Berkshire Homebrew Association",
    "Bermuda Wort Hogges",
    "Berthoud Barn Burners",
    "Best Florida Brewers",
    "BeST Homebrewers",
    "Best Villains Brewing Club",
    "Better Brewers Board",
    "Bexar Brewers",
    "BHHBC",
    "BIABers",
    "Bicycle Brew Club",
    "Bidal Society Of Kenosha",
    "BIER-Brewers In the Endicott Region",
    "Bierkraft",
    "Biermeister Brewers Club",
    "Biers Dewees",
    "Big Ash Brewing Company",
    "Big Bear Homebrew Club",
    "Big Beer Buff Society",
    "Big Break Brewing",
    "Big Brew Theory",
    "Big Country Homebrewers Association",
    "Big Easy Homebrewers",
    "BIG Homebrew Club",
    "Big Horn Basin Brew Club",
    "Big Horn Brewing Club",
    "Big Lebrewskis",
    "Big Lick Brewers",
    "Big Rapids Area Master Mashers (BAMM)",
    "Big Sioux Brewing Society",
    "Big Sky Basement Brewers",
    "Big Thicket Brewers",
    "Big Thompson Brewing Collective",
    "Big Wave Homebrew Club",
    "Bighorn Homebrew Club",
    "BigPBrewing",
    "Billtown Brewers Guild",
    "Bird Dog Brewers",
    "Birdtown Brew Crew",
    "Birmingham Brewmasters",
    "Birthday Biscuits ",
    "Bitches &amp; Studs Brew Club",
    "Bitches Brew Crew MN",
    "Bitchin' Ale Masters",
    "Bitter North Brewers",
    "Black Bear Homebrew Club",
    "Black Bottom Brew Club",
    "Black Canyon Homebrewers Association",
    "Black Hills Brewers",
    "Black River Brewing Club",
    "Black River Homebrew Club",
    "Black Swamp Trub Club",
    "Blackwater Brewers",
    "Blackwater Brewers Guild",
    "Blakely's Bare Frog Brewers",
    "Blind Willie Brewers",
    "Bloatarian Brewing League",
    "Blood, Sweat &amp; Beers",
    "Bloomington Hop Jockeys",
    "Blount County Homebrewers",
    "Blue County Brew Club",
    "Blue Mountain Brewers Club",
    "Blue Ox Brewers Society",
    "Blue Ridge Brewers Association",
    "Blue Ridge Homebrewers",
    "Bluff City Brewers &amp; Connoisseurs",
    "Bluff Hoppers",
    "Boars Head Brewing Club",
    "Bock N Ale-Ians",
    "Boeing Employees Wine &amp; Beer Makers Club",
    "Boerne Brewers Guild",
    "Bohemian Brewing Club",
    "Boil Over Boys of Sebastian",
    "Boiler Room Brewing",
    "Boise Brewers",
    "Bone Diggler Brew Club",
    "Boneyard Union of Zymurgical Zealots (BUZZ) ",
    "Bookcliff Homebrew Club",
    "Bootleggers Brewers and Vintners",
    "Border Brewers",
    "Borderline Brewers",
    "Boreal Brewers",
    "Boston Brewin'",
    "Boston Wort Processors",
    "Bottles &amp; Pints",
    "Bottomless Pint Brewers",
    "Bowling Green Homebrew Club",
    "Bradenton Brewskis",
    "Bradentucky Brew Barons",
    "Branch Area Brewers (BAB)",
    "Brandon Bootleggers Homebrew Club, Inc.",
    "Brassage d'lle-de-France",
    "Brasseurs a la Maison",
    "Brasseurs Amateur Réunis",
    "Brauer Unterstutzungs Verein",
    "Braufreunde Münster",
    "Bräukline",
    "Breaking Bar Brewers",
    "Brethren Brewing Company",
    "Brew 22",
    "Brew Angels",
    "Brew Bayou",
    "Brew Brothers of Pikes Peak",
    "Brew Carrollton",
    "Brew Club of Seminole County",
    "Brew Club Simi Valley",
    "Brew Club [NE]",
    "Brew Club [MN]",
    "Brew Crew [OH]",
    "Brew Crew [CO]",
    "Brew Cru",
    "Brew Dude Brew Club",
    "Brew Ferm",
    "Brew Free or Die",
    "Brew Gadgeteers",
    "Brew Haven",
    "Brew It, Drink It, Talk About It",
    "Brew Jersey Devils",
    "Brew Jersey HBC",
    "Brew Jersey Homebrew Club",
    "Brew Knights",
    "Brew Labs of Indiana",
    "Brew Man Group",
    "Brew Maui",
    "Brew Mississippi",
    "Brew Rats",
    "Brew School",
    "Brew Scouts of America",
    "Brew Shed",
    "Brew Something!",
    "Brew Stooges",
    "Brew Tang Clan",
    "Brew the Adirondacks",
    "Brew World Order",
    "Brew Your Own Beer (BYOB) Club",
    "Brew20",
    "Brewaceuticals",
    "Brewathon",
    "Brewbirds Of Hoppiness",
    "Brewboard (Greenboard)",
    "Brewbonic Plague",
    "BrewChatter",
    "BrewCommune",
    "Brewcrafters Hong Kong ",
    "Brewdies",
    "Brewer 27",
    "Brewer's Rite",
    "Brewers And Drinkers Around Silver Spring (BADASS)",
    "Brewers Anonymous",
    "Brewers Association of Richmond",
    "Brewer's Basement",
    "Brewers Collective",
    "Brewers East End Revival (BEER)",
    "Brewers Gathering",
    "Brewers In Chaos",
    "Brewers In the Eastern Shore Region (BIER)",
    "Brewers of Anarchy",
    "Brewers of Bremer County (BoBCo)",
    "Brewers of Central Kentucky (BOCK)",
    "Brewers of Ohios Zymurgists Enclave (BOOZE)",
    "Brewers of Okaloosa",
    "Brewers of Order 66 Whittier Original",
    "Brewers of Our Beaches",
    "Brewers of Paradise",
    "Brewers of South Suburbia (BOSS)",
    "Brewers of the Gorge (BOG)",
    "Brewer's of the Hood",
    "Brewers of Tomorrow",
    "Brewers of Zymurgical Offerings Society",
    "Brewers On The Bluff",
    "Brewers On The Lake (BOTL)",
    "Brewers On the Souris",
    "Brewers Regionally Encompassing Woodmoor (BREW)",
    "Brewers United for Real Beer",
    "Brewers United for Real Potables (BURP)",
    "Brewers United For Zany Zymurgy (BUZZ)",
    "Brewers/Central Coast",
    "Brewface Killahs",
    "Brewfaction",
    "BrewGadgeteers",
    "Brewgarous",
    "Brewglers",
    "Brewhart",
    "BrewHead",
    "Brewhopsters",
    "Brewhounds of Michigan",
    "Brewing Among Regional Friends (BAR Friends)",
    "Brewing Borrachos",
    "Brewing Enthusiasts of the Antelope Valley Region",
    "Brewing Excellence in the Erie Region",
    "Brewing Innovators and Enthusiasts of Rhinelander (BIER)",
    "Brewing Network, The",
    "Brewing Ring In Medina (The BRIM)",
    "Brewins",
    "Brewligans",
    "Brewluminati [CA]",
    "Brewluminati [WI]",
    "Brewmania",
    "Brewmasters of Alpharetta",
    "Brewmeisters Anonymous",
    "Brewmigo",
    "Brewminaries",
    "Brewnion Colony",
    "Brewnosers",
    "BrewRats",
    "Brews Brothers",
    "Brews Brothers Brew Club",
    "Brews From The Dip",
    "Brews-Bros.com",
    "BrewsClub.com",
    "Brewshiners of Georgian Bay",
    "Brewsquitos Homebrewing Club",
    "Brewstie Boys",
    "Brewstoria",
    "Brewtherville Labs Brew Club",
    "BREWtopia",
    "Brewtopians of Port Jeff",
    "Brewtubers",
    "BrewVIC",
    "Brick City Brewers",
    "Brick Street Brewers",
    "Bridger Brew Crew",
    "Brighton Brew Club",
    "Brisbane Amateur Beer Brewers",
    "Bristol Brewery",
    "Brixie's Brewers",
    "Broad Ripple Celebration Club",
    "Broad River Brewers",
    "Bronx Homebrewers Association",
    "BrooKings BrewKings",
    "Brooklyn Brewsers",
    "Brooksville Brewers",
    "Broome County Fermenters Assn.",
    "Broomtail Brew Club",
    "Brothasandbeer",
    "Brotherhood of Brewers",
    "Brotherhood of Wort",
    "Brouhaha",
    "Bruclear Homebrew Club",
    "BRUDE",
    "Bruehol Brewers",
    "Brunswick Brew Club",
    "BT6",
    "Bubba Lou Brewery",
    "Buckeye Brewer of the Year",
    "Bucks County Brewers",
    "Bucky Badger's Brewing Club",
    "Buffalo Brew Club",
    "Buffalo Brewers",
    "Buffalo Originated Craft Brewers",
    "Bull Falls Homebrew Club",
    "Burgh Brewers Cooperative",
    "Burlington MOB (makers of Beer)",
    "Burning Rabbit Home Brew Club",
    "BURNT",
    "Burque Brew Crew",
    "Burt County Brewmasters",
    "C.A.R.B (Craft Alliance of Roosevelt Brewers)",
    "C.H.A.O.S. Brew Club",
    "Cabarrus Homebrewers Society (CABREW)",
    "Cache Brewing Society",
    "Cache Valley Brew Club",
    "Cal Poly Brew Crew",
    "Calaveras Hoppy Brewers",
    "Calgary Homebrewers Association",
    "Calhoun Brewers Society",
    "California Mead Makers",
    "Campaign for Real Ale",
    "Canberra Brewers Club",
    "Cane Island Alers (CIA)",
    "Caney Fork Brew Works",
    "Canton Brew Club",
    "Canton Brewer's Union",
    "Cap and Hare Homebrew Club",
    "Cape Cod Lager And Ale Makers (CCLAM)",
    "Cape Fear Homebrewers Assoc",
    "Cape May Brewers Guild",
    "Capitol Brewers",
    "Capitol Hill Urban-Brewers Guild",
    "Carbon Nation",
    "Carbon Valley Brewers",
    "CARBOY II MEN",
    "Carboy Junkies",
    "Caribbean Unified Brewers Association",
    "Carolina Brewmasters",
    "Carolina Carboyz (SC)",
    "Carpe Bierum",
    "Carpinteria And Rincon Point (CARP) Homebrewers",
    "Cary-Apex-Raleigh-Brewers-Of-Yore (CARBOY)",
    "CASCADE (Canton Area's Sinister Crew of Ale Drinking Enthusiasts)",
    "Cascade Brewers Guild",
    "Cascade Brewers Society",
    "Cascade Fermentation Association (CFA)",
    "Cascadia Brewers Alliance",
    "Cass River Home Brew Club",
    "Catawba Lager &amp; Ale Sampling Society",
    "Catawba River Area Fermenters Team (CRAFT)",
    "Cavern City Brewers",
    "CB&amp;C Brewing",
    "CCCZN: Clase Combativa Cervecera Zona Norte [Argentina]",
    "CCSD",
    "CDRT Beer Club",
    "Cedar Rapids Beer Nuts",
    "The Cellar Rats",
    "Cenosilicaphobia Brewers",
    "Central Alabama Brewers Society",
    "Central Arkansas Fermenters",
    "Central Coast Zymurgeeks",
    "Central Connecticut Draught Kings",
    "Central District Brewing Collective",
    "Central Florida Homebrewers",
    "Central Illinois Brewers Association",
    "Central MA Homebrewers",
    "Central Montana Homebrew Club",
    "Central NJ Homebrew Meetup",
    "Central Nowhere Homebrewers Guild",
    "Central Oregon Homebrewing Organization (COHO)",
    "Central Phoenix Homebrew Club",
    "Central Valley Alechemists",
    "Central Valley Brewer's Guild",
    "Central Wisconsin Amateur Wine",
    "Central Wisconsin Draught Board",
    "Central Wisconsin Vintners and Brewers",
    "Cerveceros Artesanales De Mexico",
    "Cerveceros Caseros De Chihuahua",
    "Cerveceros Caseros del Comahue",
    "Cerveceros De Paila",
    "Cerveceros Queretanos",
    "Cervesa Cevada Club",
    "CerveTech",
    "Cerveza Casera Chile",
    "CF Brewing Cooperative",
    "CG Brewing Guild",
    "Chalmette Home Brewers",
    "Chambersburg/Hagerstown Organization For Perfect Suds",
    "Charleston Brew Collective",
    "Charlotte Harbor Unified Brewing Society (CHUBS)",
    "Charlottesville Area Masters of Real Ale (CAMRA)",
    "Chatham Hoppers Homebrew Club",
    "Chautauqua Lake Ultimate Brewers",
    "Cheese City Brewers and Vintners",
    "Chelsea Brewers Guild",
    "Cherokee Brewers",
    "Cherokee Highlands Beer Society",
    "Chesapeake Real Ale Brewers Society (CRABS)",
    "Chicago Beer Society",
    "Chicago Home Brewers Group",
    "Chicken City Ale Raisers",
    "Chico Home Brew Club",
    "Chilebruers",
    "Chillisquaque Homebrewers Association",
    "Chippewa Valley Better Brewers",
    "Chop &amp; Brew",
    "CHUG",
    "Church of the St Pauli Girl",
    "Cincinnati Malt Infusers",
    "Cinema 16:9's Brew &amp; Share",
    "Circle City Zymurgy",
    "Circle of Trust",
    "Clarksville Carboys",
    "CLASS",
    "Clawson Brew Club",
    "Clayton Area Homebrewers",
    "Clergy Of Zymurgy",
    "Clinton River Assn of Fermenting Trendsetters",
    "Cloudy Town Brewers",
    "Club Birrero De TicoBirra",
    "Club De Amigos De La Cerveza (CAC)",
    "Club De Cerveceros Caseros Del Uruguay",
    "Club De Homebrewers De GDL",
    "Club Guillón",
    "Club Maillard",
    "Club Wort",
    "Cluster Fuggle Brew Crew",
    "Coachella Valley Homebrew Club",
    "Coalhouse Home Brewers",
    "Coast Masters of Pacifica",
    "COBRA [MD]",
    "COBrA Homebrewing [GA]",
    "Cold Creek Brew Club (CCBC)",
    "College Brewers of Nevada",
    "Colonial Ale Smiths &amp; Keggers (CASK)",
    "Colonial Brew Club",
    "Colonial Brewers",
    "Colorado Ale Lab",
    "Colorado Libations Union Brewers (The C.L.U.B.)",
    "Colorado Wine Club",
    "Columbia Homebrewers Club",
    "Columbus Area Classic Alers",
    "CoMo Homebrew",
    "Conbrewence Brew Crew",
    "Conch Republic Bubbas",
    "Concord Area Homebrewers",
    "Connecticut Valley Hop Dogs",
    "Conquer",
    "Conversion Brew Club",
    "Corazon Brew",
    "Corks-n-caps",
    "Cornwall HomeBrew Club",
    "Cotati Home Brewer's Collective",
    "Country Cannery Homebrew Shop",
    "Courtland Fermentation Club",
    "Covert Hops Society",
    "Cowford Ale Sharing Klub (CASK)",
    "Cowtown Yeast Wranglers",
    "CRABS",
    "Craft Alliance Of Roosevelt Brewers",
    "Craft Brew Club [Singapore]",
    "CRAFT Homebrew Club",
    "Cram Hill Brewers",
    "Cranky Alers of Southern Kentucky (CASK)",
    "Crash Test Brewers",
    "Crawfish Brew Club",
    "CRAZE",
    "Crazy Girl Beach Brew",
    "Crescent City Homebrewers",
    "Crestview Brewers",
    "Crooked Lamp Post Brewing Club",
    "Cross River Alliance of Zymurgists (CRAZY)",
    "Cross Street Irregulars",
    "Crossroads Brewers Guild",
    "Crown of the Valley Brewing Society",
    "Crude Brew Crew",
    "Crystal River Area Brewing Society",
    "CUF Monks",
    "Cullman Brewers Guild",
    "Culpeper Brewing Society",
    "Cumberland Brews Homebrew Club",
    "Curry Co. Brew Crew",
    "Curvy Sticks",
    "Cymurgy Society",
    "Cypress Suds",
    "D' Brew Crew",
    "D.N.Y. Beverage Club of St. Marys",
    "D.O.M.",
    "D.R.A.F.T. (Deutsch-American Ramstein Area Fermenting Technologists)",
    "Dallas Homebrew Collective",
    "Dammit",
    "Dampf Brewers",
    "Das Bier Verein",
    "Das Hausbrauers Von Buffalo",
    "Daydream Brewing",
    "Dayton Regional Amateur Fermentation Technologists",
    "DC Homebrewers",
    "Dead End Brewers",
    "Dead Fellows",
    "Dead Goats Brewing",
    "Dead Horse Brewing",
    "Dead Yeast Society [LA]",
    "Dead Yeast Society [IN]",
    "Deer Island Brewers",
    "Defiant Homebrewers",
    "Definitive Ales",
    "Defying Gravity",
    "Delaware Hausbrauers",
    "Delaware Mazers",
    "Delaware Ohio Homebrewers (DOH!)",
    "Delco Wooder Works",
    "Delhi Mashers Homebrewers Guild",
    "Dells Unified Frequent Fermenters (D.U.F.F)",
    "Delmarva United Homebrewers",
    "Delta Brewing Club",
    "Delta Brews",
    "Demented Fermenters",
    "Denton County Homebrewers Guild",
    "Denver Homebrew Club",
    "DenverDrinkTank",
    "Department of Homebrew Security",
    "Desch Labs",
    "Desert Quenchers",
    "DesiBrew",
    "Destination Draught",
    "Devil's Slide Brew Club",
    "Diablo Order of Zymiracle Enthusiasts (DOZE)",
    "Dirty Bastards Homebrew Club",
    "Displaced Brewery Fanatics",
    "District 8 Brewing Company",
    "Do Wort!",
    "Dockside Brew Club",
    "DOMALI Brewing Group",
    "Don Diego Brewing Club",
    "Douglas Island Homebrew Club",
    "Down East Alers (DEA)",
    "Down River Brewers Guild",
    "Downers Grove Murphy Ale",
    "Downright Obsessed Homebrewers (DOH)",
    "Downtown Brew Crew",
    "Draft Punk",
    "Draught Board 15",
    "Draught Board Homebrew Club",
    "Draughts-Men",
    "Dregs of Society Homebrewers",
    "Driftless Area Brew Club",
    "Dry Town Alers",
    "Dublin M.A.L.T.S",
    "Dubois County Suds Club",
    "Dukes Of Ale",
    "Dulles Reg Brewing Society",
    "Dunedin Brewers Guild",
    "DuPont Brewers Club",
    "Durango MAFIA",
    "Durango Pour Friends",
    "Durham Homebrewers Club",
    "Dust N Ash's",
    "EarthTone Brew Club",
    "East Coast Homebrew",
    "East Greenwich Hopheads",
    "East of Elon Home Brewing Cooperative",
    "East Side Brewers [IL]",
    "East Side Brewers HC [CA]",
    "East Texas Brewers Guild",
    "East Valley Brew Club",
    "East Valley Homebrewers",
    "Eastern Panhandle Brewers Society",
    "Eastern Sierra Brewers",
    "Eastern Upper Peninsula Home Brewers Club",
    "Eclectic Ales",
    "Edgarbrew",
    "Edmonton Homebrewers Guild",
    "Egyptian Zymotic (EZ) Brewers",
    "Eidolon Brewing Club",
    "El Paso City Home Brewers",
    "El Riad Shrine Mystic Brewers",
    "Elba Mar Brewers Club",
    "Elk County Brewers",
    "Elk Grove Brewers Guild",
    "Elkhorn Valley Society of Brewers",
    "Elm Paddle Brewers",
    "Elmendorf Home Brewers Association (EHBA)",
    "Elmhurst Craft Brewers",
    "Emerald City Homebrewers",
    "Emerald Coast Homebrewers Organization (ECHO)",
    "Empire Brewing Club",
    "Engineers of Beers",
    "Enright Brewers Guild",
    "Erie County Brewers Association",
    "Erie County Homebrewers Association",
    "Erie Village Brewing Collective",
    "ESB (East Sacramento Brewers)",
    "Escambia Bay Homebrewers",
    "Et Tu Brew Te",
    "Evanston Homebrew Club",
    "Everglades Craft Brewers Guild",
    "F &amp; G's Brewery",
    "F.A.B. Football's A Brewin'",
    "F.E.R.M.",
    "Fabricators Of Grog (FOG)",
    "Fairfield Fermentors Brew Club",
    "Fairmont Homebrewers Club",
    "Fallbrook Home Brewers Guild",
    "Fan Brew Exchange",
    "Far North Brewers &amp; Vintners",
    "Farmhouse Homebrewers Club",
    "Farmington Valley Brewers",
    "Fat Kid Brew Club",
    "Fayetteville Homebrew Club",
    "FBI: Five Brewers Inebriated",
    "Fear No Beer",
    "FederALE Breweau of IBU (FBIBU)",
    "Feisty Goat Brewing",
    "Fellowship Of Ale Makers",
    "Fellowship of Oklahoma Ale Makers (FOAM)",
    "Fellowship of the Brew",
    "Ferm [WI]",
    "Ferm [IL]",
    "Ferment the World",
    "Fermental Order of Renaissance Draughtsmen (FORD)",
    "Fermentals",
    "Fermentation Association of Derby",
    "Fermentation Nation",
    "Fermentation Organization At Mines",
    "Fermentation Society University College Cork",
    "Fermented Souls",
    "Fermenters Of Central CT (FOCCT)",
    "Fermenting Leaders of Coastal Carolina (FLOCC)",
    "Fermentologists",
    "Fermentors",
    "Ferrnenters Local #35",
    "Filthy Brewing Alliance",
    "Final Gravity Craft Brewers",
    "Finger Lakes Fermentation Leaque",
    "Finger Lakes Homebrewers Club",
    "Firelands Homebrew Club",
    "Firkin A",
    "Firkin Homerackers",
    "First State Brewers",
    "Fish &amp; Yak Brewing Club",
    "Fitchburg Order of Ale Makers",
    "Five Guys Inebriated",
    "Flathead Valley Brewers",
    "Flat Rock Brew Club",
    "Fletcher Street Brews and Social Club",
    "Fleuchaus Brauhaus",
    "Flint Urban Brewing",
    "Flocculant Basterds",
    "FLOPS",
    "Florida Back Yard Brewers",
    "Flower City Hop-Heads",
    "Flying Tiger Home Brew",
    "Foam Blowers of Indiana (FBI)",
    "Foam on the Brain",
    "Foam On The Range",
    "Foam Rangers Homebrew Club",
    "Foamy Express Ryeders",
    "Foggy Coast Brewers",
    "Foothill Spargers Homebrewers",
    "Foothills Brewers Co-Operative",
    "For Bitter Or Wort",
    "Forest City Brewers",
    "Forest City Brewing Club",
    "Fort Belvoir Homebrewers",
    "Fort Collins HopHeads",
    "Fort Lauderdale Area Brewers",
    "FOSSILS",
    "Four Corners Celtic Homebrewers Club",
    "Fourth Street Brewing",
    "Franklin Brewers Club",
    "Franklin Park Irregulars Brew Crew",
    "Fraternal Order of Wayward Brewers",
    "Fredericks Original Ale Makers (FOAM)",
    "Fredericksburg Area R T Brew Club",
    "Fredericksburg Brewing &amp; Tasting Society",
    "Fredericksburg Brewing Insiders (FBI)",
    "Free State Brewers' League",
    "Fresh Lake Brewing",
    "Friends Brewing Independently (FBI)",
    "Friends Of Fermentation",
    "Frogtown Hoppers",
    "Front Deutscher Hopfen",
    "Fuhmentaboudit!",
    "Full Volume Homebrew",
    "Fun Undertaking Advanced Brewing Recipes (FUBAR)",
    "Funky Brewster",
    "Funky Monk Brew Club",
    "Furry Brewers of Dixie",
    "Galilee Homebrew Club",
    "Galveston Area Brewers Federation",
    "Garage Brewers Society",
    "Garage Tavern Brewers",
    "Garden City Homebrew Club",
    "Garden State Homebrewers",
    "Garlic City Brewmasters",
    "Garrison City Brewers Collective",
    "Gate City Homebrewers",
    "Gateway North Brewers",
    "Geaux Brewing",
    "Geelong Craft Brewers",
    "Genesee Area Brewers Club",
    "Genesee Labs Brewing",
    "Geneva A-lers",
    "Ghetto Spoon Spargers",
    "Ghetto System Brewers",
    "Giants Lot Brewing Collective Of Doom",
    "Gillette Brewers Guild",
    "Glacial Lake Brewers",
    "Glass City Mashers",
    "Glen Ridge Homebrewers Association",
    "Global Brew Tribe",
    "Gloucester County Homebrewers",
    "GLUB Club",
    "Gnarly Bear Brewing",
    "Gnarly Beard Brewery",
    "Gneiss Brewers",
    "GO-BREW",
    "Gold City HopMasters",
    "Gold Country Brewers Association",
    "Gold Country Mashers",
    "GoldCLUB",
    "Golden Anti-Sobriety Society (ASS)",
    "Golden Isles Brew Club",
    "Golden Triangle Brewers",
    "Golden Triangle Homebrewers Club",
    "GONZO Brewers",
    "Good Heathens",
    "Good Libations",
    "Good River Brewing Co-op",
    "Goshen Homebrew Club",
    "GotMead International Brewmasters",
    "GR Homebrewers Club",
    "Grain Bang",
    "Grain Trust",
    "Grained Haven",
    "Grand Crew",
    "Grand Order of Beverage Enthusiasts",
    "Grand River Cru",
    "Grande Tete Homebrewers",
    "Granite Run Brewers",
    "Grateful Shed",
    "Gravity Homebrewing Club",
    "Great Northern Brewers Club",
    "Great Order of Shady Homebrewers",
    "Great Southern Beer Festival",
    "Greater Charleston Area Zymurgists Guild",
    "Greater Denver Yeast Infection",
    "Greater Everett Brewers League",
    "Greater Huntington Homebrewers Assoc",
    "Greater Lansing Brewtopian Society",
    "Greater Omaha Homebrewers Anonymous",
    "Greater Topeka Hall of Foamers",
    "Green Bay Brewers Guild",
    "Green Bay Rackers",
    "Green Mountain Mashers",
    "Greenbelt Brewers Association",
    "Greenway Vintners &amp; Brewers",
    "Gresham Homebrew Club",
    "GRIST",
    "Grist Busters",
    "Groton Growlers",
    "Growlers Brewing Club",
    "Growlies Growlers Beer Club",
    "Grupo Cervecero Chelangos",
    "GTA Brews",
    "GUAM Homebrew Club",
    "GuangZhou Homebrew and Craft Beer Club",
    "Guerilla Brewing Coalition",
    "Gulf Coast Brewers Guild",
    "Gulf Coast Brewers League",
    "Gunnison Valley Homebrewers",
    "Guten Tag Growler Club",
    "Gutless Brewers",
    "H.A.L.F. Brewers &amp; H.A.L.F. Drinkers",
    "H.A.Z.A.R.D. Homebrewing",
    "H.O.O.C.H. (Homebrewers Of Ohiovalley Club House)",
    "Hackettstown Homebrewers Club",
    "Hagerstown Organization for Perfect Suds (HOPS)",
    "Half Ass Brewery",
    "Half Mashed Brew Club ",
    "Hall Pass Homebrewers",
    "Halsingland Hotel",
    "Hamivshelan",
    "Hampton Roads Brewing &amp; Tasting Society",
    "Hancock County Home Brewers Assoc.",
    "Handgrenades Homebrew and Craft Beer Club",
    "Hangar 41 Brew Club",
    "Hangtown Association of Zymurgy Enthusiasts (HAZE)",
    "Hannibal Area Homebrewers Association",
    "Harding House Brewing CooP",
    "Harford Brotherhood of Homebrewing",
    "Harlem Brewers Club",
    "Harrison County Homebrew Club",
    "Harrisonburg Hombrewers",
    "Harvest Moon Brewers",
    "Hattiesburg Beer Club",
    "Hawkes Bay Amateur Winemakers &amp; Brewers Club",
    "Hawks Crossing Brew Group",
    "Hawthorne Brew Club",
    "Hays Homebrewers",
    "HBF United",
    "Headhunters Brewing Club",
    "HeadQuarters",
    "Heart Of The Valley",
    "Heart River Home Brewers",
    "Heavens Bounty Homebrewing Club",
    "Hefe Hitters",
    "Hell On The Border Homebrew Club",
    "Helle and Bock Homebrew Club",
    "Hellenic Homebrewers Association",
    "Hellgate Homebrewers",
    "Hells Canyon Homebrewers Club",
    "Heros-2-Hopheads",
    "Hetch Hetchy Hop Heads",
    "HI GRAVITY",
    "Hibiscus Winemakers &amp; Brewers",
    "High Altitude Home Brewers Guild",
    "High Country Homebrewers Association",
    "High Desert Brewers",
    "High Desert Brewgade",
    "High Desert Home Brewers Anonymous",
    "High Gravity Homebrew Club",
    "High Gravity Homebrewers",
    "High Mountain Hoppers",
    "High Plains Drafters [WY]",
    "High Plains Drafters [TX]",
    "High Plains Draughters",
    "High Point Observers of Pint Science (HOPS)",
    "High Street Homebrew Club",
    "Hill City Home Brew Club",
    "Hill City Homebrewers",
    "Hill Country Homebrew Club",
    "Hill County Homebrewers",
    "Hill Hoparazzi",
    "Hills Brewers Guild",
    "Hilltop Brewhaha Brewmasters",
    "Hobby Brewer Club Vietnam",
    "Hoboken Homebrewers Club",
    "Hogtown Brewers",
    "HoliSpirits",
    "Hollister Hoppers Homebrew Club",
    "Hollywood Hopheads",
    "Holy Cross Brewers Guild",
    "Holykowsky Brewers",
    "Hombrewers Of The Lower Columbia",
    "Home Brew Boys",
    "HOME Brewers",
    "Home Brewers Club at The University of Colorado",
    "Home Brewer's Guild of Beer Sheva",
    "Home Brewers of Greater Bangor",
    "Home Brewers South Suburbs",
    "Home Brewing Research Engineers of Willits (Home B.R.E.W.)",
    "Homebrew Association of Manatee &amp; Sarasota (HAMS)",
    "Homebrew Battleground Brewers",
    "Homebrew Club [WA]",
    "Homebrew Club At Virginia Tech",
    "Homebrew Collab (Bristol)",
    "Homebrew Connection",
    "Homebrew Hawaii",
    "Homebrew Heroes",
    "Homebrew Korea",
    "Homebrew Ms",
    "Homebrewers Above The Clouds",
    "Homebrewers Club [Russia]",
    "Homebrewers Club of McDowell and Surrounding Counties",
    "Homebrewers De Puerto Rico",
    "Homebrewers Guild of Seattle Proper",
    "Homebrewers Local 402",
    "Homebrewers Occasionally Producing Zirconium",
    "Homebrewers Of Ohiovalley Club House (HOOCH)",
    "Homebrewers Of Otsego County Proper Society (HOPS)",
    "Homebrewers of Pagosa Springs (HOPS)",
    "Homebrewers Of Pearland Society",
    "Homebrewers of Peoria",
    "Homebrewers of Philadelphia and Suburbs (HOPS)",
    "Homebrewers of Southeastern Wisconsin",
    "Homebrewers of Staten Island",
    "Homebrewers of the Arkansas Valley Area Brew Club",
    "Homebrewers Of The Palouse",
    "Homebrewers of the Panhandle Society (HOPS)",
    "Homebrewers of Verrado",
    "Homebrewers of Western Loudoun (HOWL)",
    "Homebrewers on Pacific Shores (HOPS)",
    "Homebrewers Organization of Putnam and Danbury",
    "Homebrewers Over Pacific Seas (HOPS)",
    "Homebrewers Pride of the Southside",
    "Homebrewers Underground",
    "HomeBrewTalk Brewers",
    "Homebrewtopians",
    "Homebros",
    "Honoring Of Patriotic Service (HOPS)",
    "Hop Barley &amp; the Alers",
    "Hop Blooded",
    "Hop Bombshells",
    "Hop Booya",
    "Hop Breakers",
    "Hop Devils Brew Club",
    "Hop Heads",
    "Hop Heads Brewers",
    "Hop Heads Local #1400",
    "Hop Heads of Southeast Missouri",
    "Hop Heaven Homebrew",
    "Hop Lords",
    "Hop Prophets Homebrew Club",
    "Hop Religion Brew Club",
    "Hop River Brewers",
    "Hop Spirit",
    "Hop To It Homebrew Club ",
    "Hop To No Good",
    "Hop Yours!",
    "Hop-Aholics",
    "HopGun Brew Club",
    "HopHead Homebrew Fanatics",
    "Hopheads [GA]",
    "The Hopheads [AL]",
    "Hop'n Mad Hatters",
    "Hopp Headzz",
    "Hopped Up On Alpha Acid",
    "Hopping Frog",
    "Hopportunists Of Clemson",
    "Hoppy Ending Brewing",
    "Hoppy Trails Brew Club",
    "Hoppy Valley Homebrewers",
    "Hops &amp; Barleys Homestyle",
    "HoPS (HomeBrewers of Puget Sound)",
    "Hops and Flops",
    "Hops On the Plains",
    "Hops Unlimited",
    "Hopsdale Homebrewers",
    "Hops-N-Lagers",
    "Horse Thief Brewers Association",
    "Horsemen of the Hopocalypse",
    "Hosier Brewers",
    "Hot Springs Homebrewers",
    "Housatonic Area Zymurgical Enthusiasts (HAZE)",
    "Houston United Group of Zymurgists (THUGZ)",
    "Howard County Homebrew Club",
    "HOWL Homebrewers of Western Loudoun County",
    "HOZER (Hamilton Ontario Zymurgy Enthusiast Ring)",
    "Hub City Home Brewers",
    "Hudson Homebrew Club",
    "Hudson Valley Homebrewers",
    "Hudson Valley Wine and Homebrew Club",
    "Humboldt Homebrewers",
    "Humbrewers Guild",
    "Hunterdon County Brewers Club",
    "Huntington Beer Collective",
    "Huntsville Barnhouse Brew Club",
    "Huntsville Hopheads Homebrewing Club (H3C)",
    "I Do Brew Crew",
    "I.B.U. [IL]",
    "I.B.U. (Inverness Brewers Union) [FL]",
    "IBUs (Illawarra Brewers Union)",
    "Idaho Brewing Association",
    "If It Feels Good, Brew It",
    "Illiana Beer Rackers Union (IBRU)",
    "IMBIB (Independant Master Brewers of Innumerable Beverages)",
    "Immersion Chillers",
    "Impalers",
    "Impaling Alers",
    "Impassioned Brewers United (IBU)",
    "Improving Brewers United (IBU) [NC]",
    "Improving Brewers United (IBU) HSV [AL]",
    "In Good Company Brewing",
    "Indian Hills Brewers Guild",
    "Indian Peaks Alers",
    "Indiana Brewing And Drinking Society",
    "Indiana Pennsylvania Alesmiths",
    "Inebriati",
    "Infamous Parkersburg Alers",
    "Inland Brewers Unite",
    "Inland Empire Brewers",
    "Innovative Brewers of Saint Louis",
    "Insert Name Here Brew Club",
    "Inspiration Brewing",
    "INSTABHility",
    "Intellectual Brewers Union (IBU)",
    "International Brewers Union",
    "International Christmas Beer Exchange Brewer Guild",
    "Interstate Brewers Unlimited (IBU)",
    "Investigative Brewers Unit (IBU)",
    "Iowa Beer Geeks",
    "Iowa Brewers Union",
    "Iowa/Minn. Society of Brewers (IAMNSOB)",
    "IRAPUATO HOMEBREW CLUB",
    "Iredell Brewers United (IBU)",
    "Ithaca Practitioners of Alemaking",
    "IUPUI Homebrew Club",
    "IZ Brew Club",
    "JA Eagle",
    "Jack Of All Brews",
    "James River Homebrewers",
    "Japanese Homebrewers Assoc",
    "Jarhead Homebrewers",
    "JayHops",
    "JbreW",
    "Jefferson County Home Brewers (JCHB)",
    "Jengklong Mander",
    "Jersey Assoc of Homebrewers",
    "Jersey City Brew Club",
    "Jersey Shore Homebrewers",
    "Jerusalem Brewing Syndrome",
    "Jesse James Brew Gang",
    "Jockey Hollow Brewers Guild",
    "Johnson County Brewing Society",
    "Johnston County Homebrew Association",
    "Johnstown Area Homebrewers",
    "Joliet Brewers Guild",
    "Jonesboro Area Brewers",
    "Joplin Homebrew Club",
    "Julian Homebrewers Association",
    "Juneau Homebrewers Club",
    "Junius Heights Brew Club",
    "Junkshow Brewing Club",
    "Just Brew It Anderson",
    "Kailua Elixir Guild",
    "Kalamazoo Libation Organization of Brewers (KLOB)",
    "Kanawha Regional Association of Zymurgy Enthusiasts",
    "Kansas City Bier Meisters",
    "Kauai Brew Club",
    "KC Brew Crew",
    "KC Nanobrews, LLC",
    "Kearney Area Brewers",
    "Keg Creek Homebrewers",
    "Kena Shriners Homebrewing Club",
    "Kenai Peninsula Brewers and Tasters Society (KPBTS)",
    "Kent Guild of Brewers",
    "Kettle-2-Keg",
    "Key Tech Brew Crew",
    "Keystone Hops",
    "KGB",
    "KHBC Kenosha Homebrew Club",
    "Killer Ales",
    "Kiltlifters",
    "King of the Mountain Brewing",
    "Kings County Brewers Association",
    "Kirksville Guild of Brewers",
    "Kittitas Valley Fermentation Project",
    "Knights of the Brown Bottle",
    "Knights of the Mashing Fork",
    "Knights of the Tap Handle",
    "Knoxville Brewers' Exchange",
    "Kona Coast Barley Boys",
    "Kosciusko Kettleheads",
    "Krauseners",
    "Krausen Commandos of NW Connecticut",
    "Krausen Growing Bureau",
    "Krewe du Brew",
    "KROC (Keg Ran Out Club)",
    "Ktown HomEbrew Guild",
    "Kuhnhenn Guild of Brewers (KGB)",
    "Kutztown Brew Crew",
    "Kyiv Homebrew Club",
    "L A Lagers",
    "L.O.A.D.E.D. (Local Organization For Alcoholic Drink Enhnancement and Development)",
    "La Abadía, Club De Cerveceros",
    "La Cofradia De La Cerveza",
    "La Comarca",
    "La Crosse Area Grain Enthusiasts and Related Specialties",
    "LAB (Lafayette Area Brewers)",
    "LabRat Homebrew Society",
    "Labrewtory Homebrew Club",
    "Lady Brew Portland",
    "Lagerhead Brewers",
    "Lagerheads",
    "Lager-Rythmics",
    "Laguna Beach Homebrewers",
    "Lake County Homebrewers",
    "Lakeland Brewers Guild",
    "Lakes Region Homebrewers",
    "Lakeside Brewers Guild",
    "Lakewood Brewers Guild",
    "LAMBIC",
    "Lamplighter Homebrew Club",
    "Lancaster County Brewers",
    "Lancaster Fairfield Homebrew Crew",
    "Lansing Brew Crew",
    "Lapeer Area Brewers (LAB)",
    "Laramie Brew Club",
    "Las Flores Brew Club",
    "Late Start Homebrewers",
    "Latter Day Suds",
    "Laughing Waters Brew Club",
    "Lauter Day Brewers",
    "Lawrence Brewers Guild",
    "League of Extraordinary Brewers [MN]",
    "League of Extraordinary Brewers [CA]",
    "League of Extraordinary Pintsman",
    "Lebanon Area Fermenters",
    "Lederhosen Illuminati",
    "Left Coast Brewers of Florida",
    "Lehigh Valley Home Brewers",
    "Les Brasseurs Bon Temps (The Good Time Brewers)",
    "Levee Break Brew Crew",
    "Libation Association of Northern Maryland",
    "Libations League of LaGrange",
    "Liberty Corner Home Brewing Club",
    "Liedtke Brewing Consortium",
    "Limerick Lagerheads",
    "Lincoln Lagers",
    "Lincolnton Homebrewers Association",
    "Linkville Brewers Association",
    "Lino Lakes Brew Club",
    "Liquid Poets",
    "Little Apple Brew Crew",
    "Little Mountain Homebrewers Association",
    "Little Red Barn Brewing",
    "Livingston Social Brew Crew",
    "Ljungens Bryggeri (Heather Brewery)",
    "Local Order of BoSTon Area Homebrewers (LOBSTAH)",
    "LoCo Hopheads",
    "LOCOZ (Lorain County Zymurgists)",
    "Logan Brewlevard Society",
    "LOLZ (Lyme-Old Lyme Zymurgists)",
    "Lompoc Brew Crew",
    "London Amateur Brewers",
    "London Homebrewers Guild",
    "Lone Tree Brew Club",
    "Long Beach Homebrewers",
    "Long Island Beer &amp; Malt Enthusiasts",
    "Long Island Fermentation Experiment",
    "Long Islanders For Fermentation",
    "Longfellow Homebrew Club",
    "Lookout Mountain Brewery",
    "Lorain County Brewers Club",
    "Los Alamos Atom Mashers",
    "Los Cebadartistas",
    "Loudoun Creators of Ale &amp; Lager (LOCAL) 19382",
    "Louisville Area Grain and Extract Research Society, LTD",
    "Louisville Homebrew Club",
    "Lovers Of Creative Alcoholic Libations (LOCAL)",
    "Lowcountry Libations",
    "Lowcountry MALTS",
    "LowenSlow Brewing",
    "Lower Hudson Valley Homebrewers Club",
    "Lu Lu Shrine Brewmeisters",
    "Luxembourg Homebrew Club",
    "Luzerne County Brewers ",
    "Lynchwood Brewers",
    "M.A.S.H. (Missouri Association of Serious Homebrewers)",
    "M.A.S.H. 956",
    "M.A.S.H. Marietta Association of Schoolhouse Homebrewers",
    "M.A.S.H. Myrtle Beach Area Society of Homebrewers",
    "M.O.B.S. Beer Club",
    "Macarthur Ale and Lager Enthusiasts",
    "Macclesfield Homebrew",
    "MACOMB WORTHOGS",
    "Mad Brew",
    "Mad Brewers",
    "Mad Zymurgists",
    "Madison Homebrewers &amp; Tasters",
    "Mahoning Area Grain Mashers Association (MAGMA)",
    "Main Line Brewers Association",
    "Main Street Brew Club",
    "Maine Ale &amp; Libation Tasters (MALT)",
    "Mainland Brewers",
    "Makelab Charleston",
    "Malheur Mashers",
    "Malt Masters",
    "Malt Munching Mash Monsters (Mmmm)",
    "Malted Barley Appreciation Society",
    "Maltose Falcons",
    "Malty Dogs",
    "Maltytaskers",
    "Manchester and the Mountains Brewers Alliance (MAMBA)",
    "Manchester Area Society of Homebrewers (MASH)",
    "Manhattan Mash Militia",
    "Maniacal Association of Shoreline Homebrewers (MASH)",
    "Manifest Destiny",
    "Manticoran Beer Association",
    "Manty Malters",
    "Manukau Winemakers &amp; Apiarists",
    "Marie Waite's Coverage Club",
    "Marin Society of Homebrewers (MaSH)",
    "Marion Area Lager Tasting &amp; Zymurgy (MALTZ)",
    "Marquette Home Brewers",
    "Marseilles Area Society for Homebrewers (MASH)",
    "Marshall County Brew Club",
    "Marshfield Area Society of Homebrewers (MASH)",
    "Maryland Ale and Lager Technicians (MALT)",
    "MASH Fort Wayne Homebrew Club",
    "Mash Heads",
    "MASH Marshalltown Area Soiety Homebrewers",
    "MASH- Muskegon Area Society of Homebrewers",
    "Mash Tun 64",
    "MASH*Brew",
    "MASH831",
    "Masholes",
    "Mashrunners",
    "Mash-Ter-Minds Brewing Club",
    "Mason/Dixon Brewers",
    "Massachusetts Electric Brewers",
    "Massey University Brewing Society (MUBS)",
    "Mazeppa Malts",
    "McMinnville Homebrew Club",
    "McSwiggin Brewing",
    "MEAD (Mead Enthusiast and Drinkers)",
    "Mead Mamas",
    "Meadville Brewing Society",
    "Meadworks Brewing Club",
    "Meat &amp; Beard Homebrew Club",
    "MECA Brewers",
    "Meisters of Brew",
    "Meisters of the Brewniverse",
    "Memphis Brewers Association",
    "Men of Beer",
    "Men Who Stare At Airlocks",
    "Menominee Brewing Club",
    "Menomonie Homebrewers",
    "Mentoring Advanced Standards of Homebrewing (MASH)",
    "Mercer Area Society of Homebrewers (MASH)",
    "Merrimack Valley Homebrew Club",
    "Mesa Verde Mashers",
    "Mesilla Valley Homebrew Club",
    "Metro Enologists N Zymurgists (MENZ)",
    "Metro South Homebrew League (MASH HOLES)",
    "Mexicali Homebrewers",
    "MF Brewers",
    "Miami Area Society of Homebrewers (MASH)",
    "Miami Beach Home Brew",
    "Miami County Brewing Club",
    "Michiana Extract &amp; Grain Association (MEGA)",
    "Michigan Mead Coalition",
    "Michigan Occasional Brewers (MOB)",
    "Mid Columbia Zymurgy Association",
    "Mid Michigan Ale and Lager Team (MMALT)",
    "Mid Michigan Malt Meisters",
    "Mid-Atlantic Society of Brewing Curiosity",
    "Middle Georgia Hops Society",
    "Middlesex Malters",
    "Middletown Area Society of Homebrewers",
    "Mid-Mass Malt Masters (M4)",
    "Midnight Carboys",
    "Midnight Homebrewers' League",
    "Mid-State Brew Crew",
    "Mid-State Brewsters",
    "Midwest Fermentation Union",
    "Midwestern Order of Nin-Kasi (MONK)",
    "Mighty Vorlauf",
    "Mike Brewing Co.",
    "Mile High Mashers",
    "Mile High Monks",
    "Military Academy Society of Homebrewers (MASH)",
    "Military Home Brewers Club",
    "Milltown Mashers",
    "Milwaukee (Eastside) Brewers",
    "Milwaukee Area and Chicago Area Brewers Group",
    "Milwaukee Beer Society",
    "Mimer #33 Brew Lodge",
    "Minnesota Craft Beer Club",
    "Minnesota Home Brewers Association",
    "Minnesota Timberworts",
    "Mintwood Collective",
    "Misfit Home Brewers",
    "Misfits of Brewing Science",
    "Mission Brewiejo Homebrews Club",
    "Mississippi Pine Belt Brewers Alliance",
    "MissLou HomeBrewers",
    "Missouri Association of Serious Homebrewers (MASH)",
    "Missouri Mashers",
    "Missouri Mead Makers Society",
    "Missouri Winemaking Society",
    "MoBrew",
    "Modesto Mashers",
    "Mohave Ale &amp; Lager Tasting Society (MALTS)",
    "Mohonk Home Brewers Association",
    "Mojave Desert Brewers Guild",
    "MoKan Association of Saccharomyces Helpers (MASH)",
    "Monadnock Original Nurturing Krausen Squad (MONKS)",
    "Monarch Brew Club",
    "Monmouth County Homebrewers Association",
    "Monster Mashers Brew Club",
    "Montana Homebrewers Association",
    "MOOLA",
    "Moose Brew Club",
    "Mooseknuckle Brewing",
    "More Alcohol Should Help",
    "Morgantown Area Society of Homebrewers",
    "Morris Area Society of Homebrewers",
    "Mos Eisley Cantina Brewers Society",
    "Moses Lake Union of Great Zymurgist (MUGZ)",
    "MOST",
    "Mother Earth Brew Crew",
    "Motley Brew [NE]",
    "Motley Brew [FL]",
    "Motley Brew [CO]",
    "Motley Brue - Twin Cities Home Brew Club",
    "Motor City Mashers",
    "Mount Si Brewing Society",
    "Mount Vernon Brew Club",
    "Mountain Ale and Lager Tasters (MALT)",
    "Mountain Brew Club",
    "Mountain Mashers",
    "Mountain Top Brewers",
    "Mountain Top Mashers",
    "Mountain Top Trails and Ales",
    "Mountain View Brew Club",
    "Mox Nix Brewery",
    "MT Bottle Brewers",
    "Mt. Airy Society of Homebrewers",
    "Mt. Pleasant Brewers",
    "Mucc Town Brewers",
    "Muddy River Mashers",
    "Muddy Waters Brew Club",
    "MUGZ",
    "MUGZ2",
    "Muktown Brewers",
    "Mule Mountain Homebrewers",
    "Muscle Shoals Mashers Homebrew Club",
    "Music City Brewers",
    "Muskegon Ottawa Brewers (MOB)",
    "Muskrat Mashers",
    "Musquito County Brew Club",
    "Mutually United Grain &amp; Grape Zymurgists",
    "MyBrew",
    "Mystic Krewe of Brew",
    "N2BC (Northeast Nebraska Brew Crew)",
    "Nac Brew Club",
    "Nagging Wife Ale Cooperative",
    "NanJing Stray Birds Homebrew and Craft Beer Club",
    "Nantucket Homebrewer's Association",
    "Napa Bung Brewers (BUNG)",
    "Naperbrew",
    "Nash St Homebrew Club",
    "Natchez Brew Club",
    "National Homebrew Club (Ireland)",
    "Naugatuck Valley Homebrewers",
    "NB Brewers",
    "Nefarious Union",
    "Nevada County Homebrewing Association",
    "Nevada Homebrewers' Club",
    "Nevada Independent Brewer's Union",
    "Nevada Local Amateur Brewers &amp; Recog. Ale Tasters",
    "New Bohemian Brewers",
    "New Brighton Homebrew Society",
    "New Colony Brewers",
    "New England Brewing Association",
    "New London Brew Club",
    "New River Brewers",
    "New River Valley Brewers Guild",
    "New York City Homebrewers Guild",
    "Niagara Association of Homebrewers",
    "Ninkasi Fan Club",
    "Ninkasis Brood",
    "NJHOPZ",
    "NKY Hombrewers Guild",
    "No Bollocks Brewing / B.A.R.E.",
    "No Hassle",
    "No Restraits Brew Club",
    "Noble Hopsmen of Indiana",
    "Noble Order of the Tenuously Affiliated Homebrew Club (NOTA HBC)",
    "NoIL Brews",
    "Nomadic Church of Fermentation",
    "NoPo Brews",
    "Norbrygg - norsk hjemmebryggerforening",
    "Nor-Cal Brew Crew",
    "Nordeast Brewers Alliance",
    "North Avenue Brewing Club",
    "North Beach Brewers Association",
    "North Chautauqua Homebrewers",
    "North Coast Brew Club",
    "North Country Home Brewers",
    "North County Home Brewers Association",
    "North Florida Brewers League",
    "North Fork Brewers",
    "North Georgia Mountain Mashers",
    "North Iowa Wine Club",
    "North Olympic Brewers Guild",
    "North Seattle Homebrew Club",
    "North Shore Brewers",
    "North Shore Brewing Club",
    "North Tahoe Brew Crew",
    "North Texas Homebrewers Association",
    "North Urban Brewing Society (NUBS)",
    "Northeast Colorado Home Brew Club",
    "Northern Ale Stars Homebrewers Guild",
    "Northern Brewer Fermentation Brigade",
    "Northern Colorado HomeBrewers Organization",
    "Northern Craft Brewers",
    "Northern Hills Home Brew Club",
    "Northern Illinois Homebrewer Collective",
    "Northern Light Brewers",
    "Northern Michigan Homebrewers Guild",
    "Northern New England Homebrewers Club",
    "Northern Rhode Island Home Brewers Guild",
    "Northern Virginia Home Brew",
    "Northern Washington Homebrewers Guild",
    "Northern Westchester Brewers Guild At the Birdsall House",
    "Northern Westchester Homebrewers Association",
    "Northland Brew Crew",
    "Northside Microbes",
    "Northside Social Homebrew Club",
    "Northside Wine/Beermakers Circ [Australia]",
    "Northwest Amateur Wine",
    "Northwest Colorado Brew Club",
    "Northwest Georgia's First Runnings",
    "Northwest Indiana Brewers Society (NIBS)",
    "NorthWest Zymurgists",
    "Northwoods Homebrewing Guild",
    "Northwoods LUSH, INC",
    "Nosepoke Brewery",
    "Not An Ivy League University Brew Club",
    "Novice Homebrewers Assoc. of New Orleans LLC",
    "Nuclear Brews",
    "NYC Resistor",
    "O.G.s",
    "Oak Hill Brewers Club",
    "Oak Park Homebrewers",
    "Oak Spring Cap &amp; Cork Club",
    "Oakland Brewers Club",
    "Oberlin Area Fermentation Specialists",
    "OBX Homebrewers",
    "OC Mashups",
    "OCBCO",
    "Ocean City Homebrew Club",
    "Ocean County Home Brewers",
    "Ocoee Brewing",
    "Oconto County Brew Club",
    "Odd Fellow Homebrewers",
    "Odd Grogs",
    "Off Rail Brew Society",
    "Official Charleston Brew Club",
    "OG Homebrewers",
    "OG: St. Louis Women's Craft Beer Collective",
    "Ohio Valley Homebrewers Association",
    "Ojai Beer Barons",
    "Okaloosa Brew Club",
    "OKBrewers",
    "Okinawa Brewing Enthusiasts Entertainment and Recreation (OBEER)",
    "Okoboji Homebrewers Guild",
    "Old 81 Homebrewers",
    "Old Man Brewery",
    "Old Town Brewing Club",
    "Olde Town Mash Paddlers",
    "Ole' Buzzard Brewing",
    "Olentangy Brewing Club",
    "Olney Springs Brewers",
    "OmaHops",
    "One Man Brew Club",
    "Orange County Mash Ups",
    "Orchid Isle Alers",
    "Order of Humulus Lupulus",
    "Oregon Brew Crew",
    "Oriental Region Brewing Society",
    "Original Gravitas (OG)",
    "Original Gravity",
    "Orlando Beer Drinkers Guild",
    "Orlando BrewCrafters",
    "Oskaloosa Beer Brewers ",
    "O-Town Hop Heads",
    "Ouachita Brewing Company",
    "Our Lager Boys Wanna Brew Club",
    "Outcrop Brewers",
    "OUTLAW BREW CLUB",
    "Overmountain Brewers",
    "Oxford Brewers Group [United Kingdom]",
    "Oxford Homebrewers",
    "Ozark Vintners and Brewers",
    "Ozark Zymurgists",
    "Ozarks Brew Crew",
    "PA Alers Homebrew Club Inc",
    "PA Wilds Homebrewers (PAWS)",
    "Pacific Brewers Alliance",
    "Pacific Gravity Home Brewers Club",
    "Pacific Northwest Brewers",
    "Pacific Northwest Homebrewers",
    "PALE ALES (Princeton and Local Environs Ale and Lager Enthusiast Society)",
    "Palestine United Brewers",
    "Palm Beach Draughtsmen",
    "Palmetto State Brewers",
    "Palo Brew Crew",
    "Panomaju",
    "Parker Hop-Aholics",
    "Parkside Homebrew Club",
    "PartTimeBrewers",
    "Pasco Brewers Guild",
    "Pasco-Hernando Homebrewers Association",
    "Patriot Brewing Regiment",
    "Patriots Brew Club",
    "Patrons of the Union of Briggitt and Bartholomew",
    "PDX Brewers",
    "Peak Brew Crew",
    "Peak to Peak Hoppers",
    "Pecos Valley Brewers",
    "Pendleton Ale and Lager Enthusiasts (PALE)",
    "Peninsula Fermentation Society",
    "Peoples Ale And Lager Society",
    "Petoskey Homebrew Club",
    "Phantom Homebrew",
    "Philadelphia Homebrew Club",
    "Philadelphias Unofficial Beer Sippers",
    "Phoenix Brewing Group",
    "Pikes Peak Zymurgist",
    "Pine Ridge Homebrewers Club",
    "Pinebelt Outlaw Homebrew Club",
    "Pinellas Urban Brewers Guild",
    "Pints and Quarters Brewing Club",
    "Pints Templar",
    "Pitt Beer Club",
    "Pittsburgh Brewers Gathering",
    "Placer Ultimate Brewing Society (PUBS)",
    "Placerville Brewing Rebels (PBR)",
    "Plainfield Ale and Lager Enthusiasts (PALE)",
    "The Plato Republic",
    "Pleasant Prairie Brew Club",
    "Pluff Mudd Hop Heads",
    "Plymouth Brewtherhood",
    "Plymouth Pride Brew Club [PA]",
    "Plymouth Pride Brew Club [MO]",
    "Pole Barn Brewers",
    "PoleBarn Brewing",
    "Polk County Brew Crew",
    "Pompano Beach Hopaholics",
    "Pontiac Brewing Tribe",
    "Port Elizabeth Brewers Guild",
    "Portland Brewers Collective",
    "Portland Homebrew Club",
    "Portland Mashing Maineiacs Homebrew Club",
    "Portland Underground Dregs Society (PUDS)",
    "Post Modern Brewers",
    "Pot Belly Brewers",
    "Potion Devotion",
    "Potosi Homebrew",
    "Pour Boys Brew Club",
    "Pour Soul Society",
    "Pour Standards - Richmond County Brew Society",
    "Powder Keggers",
    "Prairie Homebrewing Companions",
    "Prairie Schooners",
    "Prairie Water Brewing Club",
    "Prestige Worldwide",
    "Preston Brew Club",
    "Primary Fermenters Brewers &amp; Vintners",
    "PrimeTime Brewers",
    "Prince Frederick Society of Homebrewers",
    "Prince William Brewers Guild",
    "Prison City Brewers",
    "Project XXX Chicago Brew Crew",
    "Prost! Brew Club",
    "PUBS",
    "Puert Rico Homebrewers Association",
    "Puget Sound Amateur Wine and Beer Makers Club",
    "Puget Sound Beerkrafters",
    "Pumpkin Hook Brew Club",
    "Purgatory SOBs",
    "Puyallup Brew Crew",
    "Q and Q Brewers Guild",
    "QUAFF",
    "Queen City Homebrew Club",
    "Queers Makin' Beers",
    "Quick's Brew Club",
    "Quiet Corner Hombrew Club",
    "Rabbit River Brewing",
    "Raccoon River Brewers Association",
    "Radium City Brewers",
    "Raging Blue Penguin",
    "Railroaders Brew Club",
    "Rainy Day Brewing",
    "Raleigh Home Brewers Association",
    "Ramapo Valley Ruffians",
    "RASCALS",
    "Rat City Homebrewers",
    "Ravenna Brew Club",
    "Ray's Brew Club",
    "Rebellion Brew Club",
    "Red Deer Brewers",
    "Red Earth Brewers",
    "Red Hat RDU Homebrewers",
    "Red Ledge Brewers",
    "Red River Brewers",
    "Red River Homebrew Club",
    "Red White and Bru",
    "Redlands MASH",
    "Redstick Brewmasters",
    "Redwood Coast Brewers [Australia]",
    "Redwood Coast Brewers Association (RCBA)",
    "Reefwalker Brewing",
    "Reformed Brewers Fellowship",
    "Regional Harrisburg Area Brewers",
    "Regios Maltosos",
    "Reinheitsgebot Publican Brewers",
    "Reinheitsgebot Rejects",
    "Renton Area Homebrewers",
    "Revilla Brewers",
    "Rhode Island Brewing Society",
    "Rhode Island Fermentation Technicians (RIFT)",
    "Rhody Bloviates",
    "Richland Brew Haus",
    "Richmond Worthogs",
    "Ridgerunners Homebrew Group",
    "Ridgewood Beer Society",
    "Rim Country Homebrewers",
    "Rimrock Brewers Guild",
    "Ripperside Brewpunx",
    "RivBrewers",
    "River Cities Brew Krewe",
    "River City Brewers [MN]",
    "River City Brewers [OR]",
    "River City of Manitoba Brewers (RCMB)",
    "River Falls Home Brew Club",
    "River Valley Ale Raisers",
    "River's Edge Fermentation Society [IL]",
    "River's Edge Fermentation Society [IA]",
    "Riverside Homebrew Crew Club",
    "RiverSide Homebrew Society",
    "Rivertown Homebrewers",
    "Riverwest Beer Appreciation Society",
    "Roaring Fork HAMs",
    "Rochester Area Zymurgy Enthusiasts (RAZE)",
    "Rock Hoppers Brew Club",
    "Rocket City Brewers",
    "Rockwall Brewers",
    "Rocky Hop Tennessee Brewers",
    "Rocky Mountain Foamin' Homies",
    "Rogue Brewers Coalition",
    "Rogue Valley Home Brewers",
    "Rolling Brewery Homebrew Club",
    "Rondout Valley Fermentation Association",
    "Roosevelt Home Brewers",
    "Round Rock Homebrewers Guild",
    "Rude Boy Brewing",
    "Rum River Wort Hogs",
    "Rutledge-Morton-Ridley Park-Swartmore (RuMoRs) Zymurgist's",
    "S.A.A.Z. (Scranton Area Amateur Zymologist)",
    "S.B.A. (The Suburbian Beer Association)",
    "S.O.B.A. (Southwest Oklahoma Brewers Association)",
    "Saccharomyces Saints",
    "Sacramento Home Winemakers",
    "Saint Arnolds Homebrewers",
    "Saint Paul Homebrewers Club",
    "Saints Arnold Society",
    "Salacious Homebrewers In Toledo",
    "Salem County Home Brewers Association",
    "Salina Brewers Guild",
    "Salisbury Homebrewers Club",
    "Salmon Creek Beer Shed",
    "Salmonid Homebrew Club",
    "Salt City Brew Club",
    "Salt City Homebrewers",
    "San Antonio Area Zymurgists (SAAZ)",
    "San Antonio Cerveceros",
    "San Diego-Orange County Brewers Alliance",
    "San Francisco Homebrewer's Guild",
    "San Hop-Keen Homebrew Club",
    "San Joaquin Worthogs",
    "San Luis Obispo Brewers (SLOB)",
    "Sand Box Brew Club",
    "Sandfish Homebrew Club",
    "Sandstone City Brewing Club",
    "Sandusky Order Of Gambrinus (SOOG)",
    "Sangre De Cristo Craft Brewers",
    "Santa Barbara County Homebrewers Association",
    "Santa Clara Valley Brewers",
    "Santabarbeerians",
    "Saratoga Thoroughbrews",
    "Saskatoon Berry Brewers",
    "Saugerties Homebrewers",
    "Savage Beercat Homebrewers-North Pend Oreille County",
    "Savage Homebrews Club",
    "Savannah Brewers League",
    "Schleswig Wine &amp; Bier Club",
    "SCHOLARS",
    "School of Homebrew",
    "Scioto Olentangy Darby Zymurgists (SODZ)",
    "Sconnie Suds Homebrewing Club",
    "Scottish Craft Brewers",
    "Scranton Brewers Guild",
    "SCW Zymurgy Club",
    "Seacoast Homebrew Club",
    "Seattle Beer Society",
    "Seattle Metropolitans Brewers Union and Social Club",
    "Secret Wielders of the Enchanted Mash Paddle",
    "Seismic-Micro Brewers",
    "Seven City Brewers",
    "SF Base Malts",
    "Shade Tree Brewers of Shreveport",
    "Shasta Society of Brewers",
    "Sheboygan Sudzzers Homebrew Club",
    "Shenandoah Valley Homebrewers Guild",
    "Shenyang Homebrewers Club",
    "Shiawassee County Area Brewers Society (S.C.A.B.S.)",
    "Shreveport Urban Diastatic Spargers (SUDS)",
    "Shut Your Mouth &amp; Move Your Ass",
    "Sideways Brewing Club",
    "Silicon Valley Sudzers",
    "Silverado Homebrew Club",
    "Sing Sing Brew Club",
    "Single Speed Brewers",
    "Sir Brewing Club",
    "Sirwisa Brewing Collective",
    "Siskiyou C.R.A.F.T. Brew Club",
    "Sister Freddy's Brew Club",
    "Siuslaw River Brewer's Society",
    "Sixth Street Stouts",
    "SJ Homebrew Club",
    "Skunk Works Home Brew Club",
    "Skyline Brewers Club",
    "SLAM Southern Tier Lager and Ale Makers",
    "Slate Belt Homebrewers Club",
    "Small Batch Home Brew Club",
    "SMaSH Brewery Homebrew Club",
    "SMASH",
    "Smegma Scoopers",
    "Smithfield Hop and Malt Society (H.A.M.S.)",
    "Smoky Mountain Homebrewers Association",
    "Smyrna Belgium Brewers",
    "Snake River Brewers",
    "Sneaky Fox Brewing Club",
    "SNIZZLE",
    "Snohomish Brew Crew",
    "Snolligoster Brew Club",
    "SOBs (Society of Brewers)",
    "SoCal Cerveceros (SCC)",
    "Soci dea Bira Club",
    "Social Slurs Brewing Society",
    "Societe Du Lambic",
    "Society for the Prevention of Beer Blindness",
    "Society for TRUB",
    "Society O Brewers",
    "Society of Akron Area Zymurgist",
    "Society of Akron Area Zymurgists (SAAZ)",
    "Society of Alcoholic Professionals (SOAPs)",
    "Society of Barley Alchemists",
    "Society of Barley Engineers",
    "Society of Beer Enthusiasts Recruiters New York",
    "Society of Brewers",
    "Society of North Oakland Brewers (SNOBs)",
    "Society of Northeast Ohio Brewers",
    "Society of Oshkosh Brewers",
    "Solano Bike and Brew Boyz",
    "Solano Garage Brewers Brew Club",
    "Sonoma Beerocrats",
    "Sonoma Valley Homebrewers Alliance",
    "Sonoran Association of Affiliated Zymurgists (SAAZ)",
    "Sons of Ægir",
    "Sons of Alchemy",
    "Sons of Liberty Homebrew Club",
    "Sons of the Old NorthWest (SONW)",
    "Sons of Zymurgy",
    "South Atlanta Homebrewers",
    "South Austin Area Zymurgists (SAAZ)",
    "South Austin Homebrewers Association",
    "South Berkeley Brewing",
    "South Central PA Homebrewers Association",
    "South Coast Homebrewers Association",
    "South Dayton Brew Club",
    "South Jersey Fermenters",
    "South Lyon Area Brewers",
    "South Omaha Brewers",
    "South Philadelphia Homebrew Club",
    "South Shore Brew Club",
    "South Side Barley Crushers",
    "South Sound Suds Society",
    "South Valley Brewers Unite",
    "South West Area Malt Processors (SWAMP)",
    "Southern Brewers",
    "Southern Homebrewers [Italy]",
    "Southern HopHeads",
    "Southern Illinois Brewers",
    "Southern Maine Homebrewers",
    "Southern Nevada Ale Fermenters Union (SNAFU)",
    "Southern Ontario Brewers (SOBs)",
    "Southern Oregon Homebrewers",
    "Southern Virginia Brewers' Collective",
    "Southernmost Homebrew Club",
    "Southwest Florida Brew Crafters",
    "SouthYeasters",
    "Space Coast Assoc. for the Advancement of Zymurgy",
    "Spartanburg Zymurgists",
    "Speak Easy In Bangor",
    "Special Hoperations",
    "Spent Grain Society",
    "Spotted Dog Brew Club",
    "Spring Hill Brewers",
    "Springfield Pioneers of Ales w/ Regal Grand Esters",
    "Spruce Tips",
    "Square Kegs",
    "Squirrel Bastard Brewing Co",
    "St. Charles Brew Club",
    "St. Augustine Home Brewers Club",
    "St. Croix River Vally Home Brewers Assn",
    "St. Louis Brews",
    "St. Munsee Order of Brewers",
    "St. Pete Home Brew Club",
    "Stabbing Goat Brewing",
    "Stache House",
    "Stafford Brewers Club (SBC)",
    "Stanford Brewing Club",
    "Star City Brewers Guild",
    "Stars &amp; Bars Brewing Club",
    "State College Homebrew Club",
    "State of Franklin Homebrewers",
    "State Road 1 Homebrewers",
    "Stateline Brewing Society",
    "Staten Island Brew Crew",
    "Staunton Brew Crew",
    "Steamboat Homebrewers",
    "Steel City Brewers",
    "STHLM Homebrewer Cooperative",
    "Stillwater Brewers' League",
    "Stilly Mashers",
    "STL Brewminati",
    "STL Hops Homebrew Club",
    "Stonecutters",
    "Stoney Creek Homebrewers",
    "Stooges Brew Club",
    "Stout Brew Crew",
    "Stout-Hearted Brewers-Umpqua",
    "Strand Brewers Club",
    "Strange Brew Homebrew Club",
    "Strange Brewers",
    "Strauss Brew Club",
    "Stray Dog Brew Club",
    "Suburban Brew Club",
    "Subvert Ales",
    "Suds of Anarchy",
    "Suds Of The Pioneers",
    "Sugar Land Imperialists",
    "Sultans Of Swig",
    "Sumter Homebrewers",
    "Sun Prairie Worthogs",
    "Sunblest Brewers Association",
    "Suncoast Barley Mashers",
    "Suncoast Brewers Guild",
    "SUNY Poly Brewers Club",
    "Superior Homebrewers",
    "Surf City Brewers",
    "Susanville Homebrewers",
    "Sussex County United Brewers &amp; Alchemists (SCUBA)",
    "SW (Fla) Ale & Mead Posse (SWAMP)",
    "SWABBeD",
    "Swampwater Brewers Guild",
    "Swiss Homebrewing Society",
    "SymBeer",
    "Symposium",
    "T.A.B. (Thomas Avenue Brewers)",
    "Tahoe Homebrewers Club (THC)",
    "Tailspin Brewing Club",
    "Talk Beer Homebrew Club",
    "Tampa Bay BEERS",
    "Tampa Bay Girls Pint Out",
    "Taos Homebrew Club",
    "TapHeads Homebrew and Tasting Club",
    "Tappers Brew Club",
    "TAPS: Tyler Area Pints & Suds",
    "Tarbenders",
    "TAS Brewing",
    "TCHOPS (Tulare County Hombrewers for Perfect Suds)",
    "Team Gringo",
    "Teays Valley Beer Drinkers",
    "Temecula Valley Homebrewers Association",
    "Tennessee Valley Homebrewers",
    "Texas Aggie Brewing Club",
    "Texas Beer Brigade",
    "Texas Beer Camp Club",
    "Texas Carboys",
    "Texoma Area Brewing Club",
    "Texoma Brews",
    "That Dam Brew Club",
    "THIRSTY",
    "Thorn and Spear",
    "Thousand Oaked Homebrewers",
    "Three Rivers Alliance of Serious Homebrewers (TRASH)",
    "Three Rivers Underground Brewers (TRUB)",
    "Three Rivers United Brewers",
    "Three Rivers Wine and Brew",
    "Three Sheets Brew Club",
    "Thunder Bay Home Brewers Assn.",
    "Thunderbolt Island",
    "Tijuana Homebrew Club",
    "Tip of the Beer Brew Club",
    "Tippecanoe Homebrewers Circle",
    "Tippecanoe River Valley Brewers Guild",
    "Titans of Brewing",
    "To Helles and Bock",
    "Toasted Barley",
    "Toma Hawk",
    "Tomball Regional Urban Brewers Club (TRUB)",
    "Towniology Brewing",
    "Toxic Pirates",
    "Train Wreck Brewers",
    "Traverse City-Homebrewers Order of Practicing Zymurgists",
    "Treasure Coast Brewmasters",
    "Treasure Valley Brew Club of Idaho",
    "Treehouse Brewing Club",
    "Treeline Brew Club",
    "Triangles Unabashed HomeBrewers (TRUB)",
    "TriCities Beer Alliance",
    "Tricities Brew Club",
    "Tri-County Fermenters",
    "Trout Gulch Brewers",
    "Trub Grubbers",
    "Trubmeisters Homebrew Club",
    "True Brewing",
    "True Grist",
    "Tucson Homebrew Club",
    "Tuns of Fun",
    "Turkey River Utopian Brewers (TRUBs)",
    "Turn Turtle Brewing",
    "Tuscaloosa Homebrewers Association",
    "Twin State Brewers",
    "Twisted River Brew Club",
    "Two Trees Brewing",
    "UBC Brewing Club",
    "UConn Zymurgy Club",
    "Uffdah Brewers",
    "Ulster Guild of Homebrewers",
    "UMass Home Brew Club",
    "Umpqua Valley Brewers Guild",
    "Undead Nanobrews",
    "Underground Brew Crew",
    "Underground Brewers",
    "Underground Brewers of Connecticut",
    "Underground Guild of Homebrewers",
    "UnderGroundBrewSquad",
    "United Federation of Fermenting Messinks (U of M)",
    "Universal Brewing",
    "UNL Association of Brewers and Fermenters",
    "Unsettled Ale Association",
    "Upper Cumberland Brewers",
    "Upper Keys Brewers",
    "Upper Palmetto Zymurgy Society",
    "Upper Valley Beer Society",
    "Upstate NY Homebrewers Assn",
    "UpstateBrewtopians",
    "Upstream Homebrewers",
    "Urban Knaves of Grain",
    "Urban Monk",
    "Valdosta Order of Real Lagers, Ales and Unique Fermentables (VORLAUF)",
    "Valley Brew Crew",
    "Valley Brewers",
    "Valley HopHeads",
    "Valpo Brewers Association",
    "VanBrewers",
    "Vandenbosch Brewing Club",
    "Ventura Independent Beer Enthusiasts (VIBE)",
    "Verein Unser Bier",
    "Vereinigung Der Haus-und Hobbybrauer In Deutschland",
    "Veterans Brew",
    "Villages Home Brewers Club",
    "Vintage Brewer's Society",
    "Virtual Brew Club",
    "Vitesse Brew Club",
    "Volusia County Home Brewer's Guild",
    "Vortens Vanner",
    "W.A.M.M. Wort and Must Makers of the Miami River Valley",
    "W5 Homebrew",
    "Wabash Valley Fermentation 'N Ale",
    "Wagner Area Fermenting Fun Love Enthusiasts (WAFFLE)",
    "Walter Brew-On-Premises Co-op",
    "Walworth County Intergalactic Zymurgical Society \"WIZS\"",
    "Warrenton Brewers Guild",
    "Warriors of the Rotating Tap (WORT)",
    "Washington Heights Brewery Club",
    "Washington Rebel Association of Serious Homebrewers",
    "Washoe Zephyr Zymurgists",
    "Watchtower Brewing",
    "Water To Fire Brewers Collective",
    "Waukesha Homebrew Club",
    "Wayland Area Home Brewer's Club",
    "Wayne County Brew Club (WCBC)",
    "We Ned To Check This Out!",
    "Weinkeller Beer Enthusiasts",
    "Weiss Guys",
    "Wenatchee Area Zymurgist United Party (W.A.Z.U.P.)",
    "West Adams Society of Hombrewers (WASH)",
    "West Aussie Brew Crew",
    "West Branch Zymurgysts",
    "West Coast Brewers [CA]",
    "West Coast Brewers [Australia]",
    "West Oahu Brewers Club - Makakilo Brew Crew",
    "West Sac Brew Club",
    "West Seattle Hombrewers",
    "West Sound Brewers",
    "West Tennessee Homebrewers",
    "Westchester Homebrewers Organization",
    "Westchester Homebrewers Organization (WHO)",
    "Western Kentucky Homebrewers Guild",
    "Western Sydney Brewers",
    "Western Wisconsin Homebrewer's Guild",
    "Westmoreland Area Zymology Enthusiasts",
    "Wet City Fermenters",
    "Whale of an Ale Brewers Association",
    "WHALES (Woodbridge Homebrewers Ale and Lager Enthusiast Society)",
    "Whaling City Alers",
    "What Ales You",
    "Wheeling Alers",
    "Wheeling Area Craft Beer Club (WACBC)",
    "Whiskey Row Brew Club",
    "White Mountain Brewers",
    "White Mountain Fermenters",
    "White Street Brewer's Guild",
    "Wichita Homebrewers Organization (WHO)",
    "Wichita's Only Real Tasty Suds (WORTS)",
    "Wild Hops Homebrew Club",
    "Willow Hill Home Brew Club",
    "Wilshire Farms Homebrewers",
    "Wilson Homebrewing Club",
    "Wing Nut Brew Club",
    "Winnipeg Brew Bombers",
    "Winnipesaukee Area Brew Crew",
    "Winona Area Homebrewers",
    "Winston-Salem Wort Hawgs Homebrew Club",
    "Wiregrass Brewers Club",
    "Wisconsin Agriculture Educators Brewing Cooperative (WAEBC)",
    "Wisconsin Homebrewers Association",
    "Wisconsin Vintners Association",
    "Wise Rd .brewers",
    "Wiza Whidbey Island Zymurgy Association",
    "WIZARDS",
    "Wizards of Ale Homebrewers (WOAH)",
    "Woo Brew",
    "North Georgia Malt Monkeys",
    "Wootown Brewers",
    "Worry Worts",
    "Worshipful Co. Of Brewers",
    "Wort & Wine Mongers",
    "Wort City Brewers",
    "Wort Dreams",
    "Wort Hog Brewers [South Africa]",
    "Wort Hog Homebrewers",
    "Wort Hogs [MI]",
    "Wort Hogs [VA]",
    "Wort Lords",
    "Wort Mongers",
    "Worthogs of New Mexico",
    "Worts",
    "Worts of Wisdom Homebrewers",
    "WTF Beer Club",
    "Wylie Growlers",
    "Wynoochee Valley Homebrews Club",
    "Wyoming Valley Homebrewers Club",
    "Yakima Brewers",
    "Yampa Valley Homebrewers",
    "Yard of Ale Brew Club",
    "YBI Beer Club",
    "Ye Olde Deseret Mead Makers Guild",
    "Yeast Affection Project",
    "Yeast Coast Brewers",
    "Yeast Coasters",
    "Yeast Culture Club",
    "Yeast of Eden",
    "Yeast Side DC",
    "Yeastie Boys [OR]",
    "Yeastie Boys [OK]",
    "Yeastie Boys [IA]",
    "Yeastside Brewers",
    "Yeasty Bottoms",
    "Yeasty Boys [MD]",
    "York Area Homebrewers Association",
    "Youngstown Area Homebrewer's Of Ohio (YAHOO)",
    "YTM Homebrew Club",
    "Zapata Brewing (ZB)",
    "Zion Zymurgist Homebrew OPerative Society",
    "Zoo City Zymurgists",
    "Zymurgical Initiative of North Georgia (ZING)",
    "Zymurgical Obsessive Order",
    "Zymurgists Borealis",
    "Zymurgists of the Ozarks",
    "Zymurgy Enthusiasts of Eastern Pennsylvania",
    "Zymurnauts",
    "Zythum Veritas",
    "ZZHops Homebrewing Club",
    "Weiz Guys",
    "East Bay Homebrew Club",
    "National Homebrew Club [Ireland]",
    "Belfast Homebrew Club [Ireland]",
    "Capital Brewers [Ireland]",
    "Erne Homebrew Club [Ireland]",
    "Galway Homebrew Club [Ireland]",
    "Garden County Homebrew Club [Ireland]",
    "Kerry Brewers [Ireland]",
    "Kilkenny Homebrew Club [Ireland]",
    "Lee Valley Homebrew Club [Ireland]",
    "Liffey Brewers [Ireland]",
    "Limerick Brewers [Ireland]",
    "Maiden City Brewers [Ireland]",
    "Midland Brewers [Ireland]",
    "North County Brewers [Ireland]",
    "Rebel Brewers [Ireland]",
    "Royal County Brewers [Ireland]",
    "Sligo Brewers [Ireland]",
    "South Central Brewers [Ireland]",
    "South Dublin Brewers [Ireland]",
    "South Eastern Brewers",
    "South Kildare Homebrew Club [Ireland]",
    "Wee County Brewers [Ireland]",
    "Amateur Winemakers and Brewers Club of Adelaide [Australia]",
    "Ballarat and Region Craft Brewers (B.A.R) [Australia]",
    "Bayside Brewers [Australia]",
    "Bendigo and Districts Home Brew Club (BAD) [Australia]",
    "Brewmasters Grafton [Australia]",
    "Brewversity [Australia]",
    "Brisbane Amateur Beer Brewers (BABBs) [Australia]",
    "Brisbane Brewers Club (BBC) [Australia]",
    "Bubbles n Chalk brew club (BnC) [Australia]",
    "Canberra Brewers [Australia]",
    "Central Coast Brewers [Australia]",
    "Central Queensland Craft Brewers (CQCB) [Australia]",
    "Coffs Region Amateur Brewers [Australia]",
    "Extra Special Brewers (ESB) [Australia]",
    "Fraser Coast Bayside Brewers [Australia]",
    "Geelong Craft Brewers [Australia]",
    "GoldCLUB (Gold Coast) [Australia]",
    "Grog Cobras - Geelong [Australia]",
    "Hills Brewers Guild [Australia]",
    "Hobart Brewers [Australia]",
    "Hunter All Grain (HAG) [Australia]",
    "Hunter United Brewers (HUB) [Australia]",
    "IBUs (Illawarra Brewers Union) [Australia]",
    "Inner Sydney Brewers (ISB) [Australia]",
    "Inner West Homebrewers Club [Australia]",
    "Ipswich Brewers Union (IBU) [Australia]",
    "Lockyer Amateur Brewer’s Guild (LABG) [Australia]",
    "Macarthur Ale and Lager Enthusiasts - NSW [Australia]",
    "Macedon Ranges Brew Club [Australia]",
    "Mackay And District (MAD) Brewers [Australia]",
    "Melbourne Brewers [Australia]",
    "Merri Mashers [Australia]",
    "Noosa Home Brew Club [Australia]",
    "Northern Beaches Homebrew Club [Australia]",
    "Northern Brewers Homebrew Club [Australia]",
    "Northside Wine/Beermakers Circ [Australia]",
    "Peninsula Brewers Union (PBU) [Australia]",
    "Perth Home Brew Share (PHBS) [Australia]",
    "Pine Rivers Underground Brewing Society (PUBS) [Australia]",
    "Redwood Coast Brewers [Australia]",
    "Righteous Brewers of Townsville (RBT) [Australia]",
    "Small Batch Home Brew Club [Australia]",
    "South Australian Brewing Club [Australia]",
    "Southern Lager & Ale Brewers [Australia]",
    "Sunshine Coast Amateur Brewers (SCABs) [Australia]",
    "Tamworth & New England Craft Brewers [Australia]",
    "Tasmanian Brewers Club [Australia]",
    "The Border Brewers [Australia]",
    "The Worthogs [Australia]",
    "Toowoomba Society Of Beer Appreciation (TooSOBA) [Australia]",
    "Tuns of Anarchy (ToA) [Australia]",
    "West Aussie Brew Crew [Australia]",
    "Western Sydney Brewers [Australia]",
    "Westgate Brewers [Australia]",
    "Worthogs [Australia]",
    "Browns Point Homebrew Club",
    "MontreAlers",
    "Keepers of Craft",
    "STLBrewhogs",
    "Brewly Homebrew Club",
    "Butler County Brewing Society (BCBS)",
    "The Brü Club",
    "NINJA Homebrewers",
    "Master Homebrewer Program",
    "Cider, Homebrew, And Mead Production Specialists (CHAMPS)",
    "Ottawa's Homebrew Society",
    "Garner Ale Society",
    "256 Brewers"
);

$club_array_json = json_encode($club_array);
if ((isset($_SESSION['contestClubs'])) && (!empty($_SESSION['contestClubs']))) {
    $club_additions = json_decode($_SESSION['contestClubs'],true);
    $club_array = array_merge($club_array,$club_additions);
}
asort($club_array);

$sidebar_date_format = "short";
$suggested_open_date = time();
$suggested_close_date = time() + 604800;
$judging_past = 0;
$comp_paid_entry_limit = FALSE;
$comp_entry_limit = FALSE;

if (((strpos($section, "step") === FALSE) && ($section != "setup")) && ($section != "update")) {

    if ((isset($row_contest_dates)) && (!empty($row_contest_dates))) {

        $reg_closed_date = $row_contest_dates['contestRegistrationDeadline'];
        $entry_closed_date = $row_contest_dates['contestEntryDeadline'];

        $registration_open = open_or_closed(time(), $row_contest_dates['contestRegistrationOpen'], $row_contest_dates['contestRegistrationDeadline']);
        $entry_window_open = open_or_closed(time(), $row_contest_dates['contestEntryOpen'], $row_contest_dates['contestEntryDeadline']);
        $judge_window_open = open_or_closed(time(), $row_contest_dates['contestJudgeOpen'], $row_contest_dates['contestJudgeDeadline']);
        if ((!empty($row_contest_dates['contestDropoffOpen'])) && (!empty($row_contest_dates['contestDropoffDeadline']))) $dropoff_window_open = open_or_closed(time(), $row_contest_dates['contestDropoffOpen'], $row_contest_dates['contestDropoffDeadline']);
        else $dropoff_window_open = 1;
        if ((!empty($row_contest_dates['contestShippingOpen'])) && (!empty($row_contest_dates['contestShippingDeadline']))) $shipping_window_open = open_or_closed(time(), $row_contest_dates['contestShippingOpen'], $row_contest_dates['contestShippingDeadline']);
        else $shipping_window_open = 1;
        
        $judging_past = judging_date_return();
        $judging_started = FALSE;

        if ((check_setup($prefix."judging_locations",$database)) && (check_update("judgingDateEnd", $prefix."judging_locations"))) {

            $query_judging_dates = sprintf("SELECT judgingDate,judgingDateEnd FROM %s WHERE judgingLocType < '2'",$judging_locations_db_table);
            $judging_dates = mysqli_query($connection,$query_judging_dates) or die (mysqli_error($connection));
            $row_judging_dates = mysqli_fetch_assoc($judging_dates);
            $totalRows_judging_dates = mysqli_num_rows($judging_dates);

            $date_arr = array();
            $first_judging_date = "";
            $last_judging_date = "";

            if ($totalRows_judging_dates > 0) {
                do {
                    if (!empty($row_judging_dates['judgingDate'])) $date_arr[] = $row_judging_dates['judgingDate'];
                    if (!empty($row_judging_dates['judgingDateEnd'])) $date_arr[] = $row_judging_dates['judgingDateEnd'];
                } while($row_judging_dates = mysqli_fetch_assoc($judging_dates));
            }

            if (!empty($date_arr)) {
                $first_judging_date = min($date_arr);
                $last_judging_date = max($date_arr);
                if (time() > $first_judging_date) {
                    $judging_started = TRUE;
                    $reg_closed_date = $first_judging_date;
                    $entry_closed_date = $first_judging_date;
                }
            }
            
            $pay_window_open = open_or_closed(time(),$row_contest_dates['contestEntryOpen'],$last_judging_date);

        }
        
        /**
         * If any judging session has started, close the entry
         * and account registration windows.
         * This ensures that any entries that are being judged 
         * aren't modified or deleted by non-admin users.
         */
        
        if ($judging_started) {
            $entry_window_open = 2;
            $registration_open = 2;
        }

        if (strpos($_SESSION['prefsLanguage'],"en-") !== false) $sidebar_date_format = "long";

        $reg_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationOpen'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
        $reg_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $reg_closed_date, $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
        $reg_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationOpen'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time");
        $reg_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $reg_closed_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");

        $entry_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
        $entry_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $entry_closed_date, $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
        $entry_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
        $entry_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $entry_closed_date, $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "short", "date-time"); 

        $dropoff_open = "";
        $dropoff_open_sidebar = "";
        $dropoff_closed = "";
        $dropoff_closed_sidebar = "";
        
        if (!empty($row_contest_dates['contestDropoffOpen'])) {
            $dropoff_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
            $dropoff_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
        }

        if (!empty($row_contest_dates['contestDropoffDeadline'])) {
            $dropoff_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
            $dropoff_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "short", "date-time");
        }

        $shipping_open = "";
        $shipping_open_sidebar = "";
        $shipping_closed = "";
        $shipping_closed_sidebar = "";

        if (!empty($row_contest_dates['contestShippingOpen'])) {
            $shipping_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
            $shipping_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
        }

        if (!empty($row_contest_dates['contestShippingDeadline'])) {
            $shipping_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
            $shipping_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "short", "date-time");
        }

        $judge_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
        $judge_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");

        $judge_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
        $judge_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "short", "date-time");
    
        if ($_SESSION['prefsEval'] == 1) {

            if ((empty($row_judging_prefs['jPrefsJudgingOpen'])) || (empty($row_judging_prefs['jPrefsJudgingClosed']))) {
                
                if (!empty($date_arr)) {
                    $suggested_open_date = min($date_arr); // Get the start time of the first judging location chronologically
                    $suggested_close_date = (max($date_arr) + 28800); // Add eight hours to the start time at the final judging location
                }
                
                else {
                    $suggested_close_date = (time()  + 28800);
                    $suggested_open_date = time();
                }

                if (empty($row_judging_prefs['jPrefsJudgingOpen'])) $judging_evals_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $suggested_open_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
                else $judging_evals_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_prefs['jPrefsJudgingOpen'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
                if (empty($row_judging_prefs['jPrefsJudgingClosed'])) $judging_evals_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $suggested_close_date, $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
                else $judging_evals_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_prefs['jPrefsJudgingClosed'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");

            }

            else {
                $judging_evals_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_prefs['jPrefsJudgingOpen'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
                $judging_evals_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_prefs['jPrefsJudgingClosed'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
            }
            
        }

        $currency = explode("^",currency_info($_SESSION['prefsCurrency'],1));
        $currency_symbol = $currency[0];
        $currency_code = $currency[1];

        $totalRows_entry_count = total_paid_received("",0);
        $total_entries = $totalRows_entry_count;
        $total_paid = get_entry_count("paid");
        $total_entries_received = get_entry_count("received");

        // Get styles types and their associated entry limits
        // If a style type has an entry limit, get an entry count from the db for that style type
        // If that style type's entry limit is equal to the count, disable the fields and flag
        // If the flag is present, message the user
        $style_type_limits = array();
        $style_type_limits_display = array();
        $style_type_limits_alert = array();

        $query_style_type_entry_limits = sprintf("SELECT * FROM %s WHERE styleTypeEntryLimit > 0",$prefix."style_types");
        $style_type_entry_limits = mysqli_query($connection,$query_style_type_entry_limits) or die (mysqli_error($connection));
        $row_style_type_entry_limits = mysqli_fetch_assoc($style_type_entry_limits);
        $totalRows_style_type_entry_limits = mysqli_num_rows($style_type_entry_limits);

        $style_type_entry_count_display = array();
        $style_type_running_count = 0;
        $style_type_limit_running_count = 0;

        if ($totalRows_style_type_entry_limits > 0) {

            // Build style type count array
            
            do {

                // Default entry limit flag is 0 (false)
                $style_type_limits[$row_style_type_entry_limits['id']] = 0;

                $style_type_limits_display[$row_style_type_entry_limits['styleTypeName']] = $row_style_type_entry_limits['styleTypeEntryLimit'];

                if ($row_style_type_entry_limits['id'] == 4) $query_style_type_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewStyleType='2' OR brewStyleType='3'",$prefix."brewing",$row_style_type_entry_limits['id']);
                else $query_style_type_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewStyleType='%s'",$prefix."brewing",$row_style_type_entry_limits['id']);
                $style_type_entry_count = mysqli_query($connection,$query_style_type_entry_count) or die (mysqli_error($connection));
                $row_style_type_entry_count = mysqli_fetch_assoc($style_type_entry_count);

                $style_type_entry_count_display[$row_style_type_entry_limits['styleTypeName']] = array($row_style_type_entry_count['count'],$row_style_type_entry_limits['styleTypeEntryLimit']);

                $style_type_running_count += $row_style_type_entry_count['count'];
                
                // Check to see if style type has an entry limit AND if that value is numeric
                // If so, perform various actions
                if ((isset($row_style_type_entry_limits['styleTypeEntryLimit'])) && (is_numeric($row_style_type_entry_limits['styleTypeEntryLimit']))) {

                    $style_type_limit_running_count += $row_style_type_entry_limits['styleTypeEntryLimit'];
                    
                    // If entry limit reached flag with a 1 (true)
                    if ($row_style_type_entry_count['count'] >= $row_style_type_entry_limits['styleTypeEntryLimit']) {

                        if ($row_style_type_entry_limits['id'] == 4) {
                            $style_type_limits[2] = 1;
                            $style_type_limits[3] = 1;
                        }

                        else $style_type_limits[$row_style_type_entry_limits['id']] = 1;
                        
                        if ($row_style_type_entry_limits['id'] <= 9) $style_type_limits_alert[$row_style_type_entry_limits['id']] = $row_style_type_entry_limits['styleTypeEntryLimit'];
                        else $style_type_limits_alert[$row_style_type_entry_limits['styleTypeName']] = $row_style_type_entry_limits['styleTypeEntryLimit'];
                    
                    }

                }
            
            } while ($row_style_type_entry_limits = mysqli_fetch_assoc($style_type_entry_limits));

        }

        if ((!empty($row_limits['prefsEntryLimit'])) && (is_numeric($row_limits['prefsEntryLimit'])) && ($style_type_running_count >= $row_limits['prefsEntryLimit'])) $comp_entry_limit = TRUE;

        if ((!empty($row_limits['prefsEntryLimit'])) && (is_numeric($row_limits['prefsEntryLimit']))) $comp_entry_limit_near = ($row_limits['prefsEntryLimit']*.9); else $comp_entry_limit_near = "";
        if ((!empty($row_limits['prefsEntryLimit'])) && (is_numeric($row_limits['prefsEntryLimit'])) && (($total_entries > $comp_entry_limit_near) && ($total_entries < $row_limits['prefsEntryLimit']))) $comp_entry_limit_near_warning = TRUE; else $comp_entry_limit_near_warning = FALSE;

        $remaining_entries = 0;
        if ((($section == "brew") || ($section == "list") || ($section == "pay")) && (!empty($row_limits['prefsUserEntryLimit']))) $remaining_entries = ($row_limits['prefsUserEntryLimit'] - $totalRows_log);
        else $remaining_entries = 1;

        if (isset($totalRows_entry_count)) {
            if ((!empty($row_limits['prefsEntryLimit'])) && ($totalRows_entry_count >= $row_limits['prefsEntryLimit'])) $comp_entry_limit = TRUE;
            if ((!empty($row_limits['prefsEntryLimitPaid'])) && ($total_paid >= $row_limits['prefsEntryLimitPaid'])) $comp_paid_entry_limit = TRUE;
        }

        if (open_limit($row_judge_count['count'],$row_judging_prefs['jPrefsCapJudges'],$judge_window_open)) $judge_limit = TRUE;
        else $judge_limit = FALSE;

        if (open_limit($row_steward_count['count'],$row_judging_prefs['jPrefsCapStewards'],$judge_window_open)) $steward_limit = TRUE;
        else $steward_limit = FALSE;

        if (($judge_limit) && ($steward_limit)) $judge_window_open = 2;
        if (($comp_entry_limit) || ($comp_paid_entry_limit)) $entry_window_open = 2;

        $current_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "system", "date");
        $current_date_display = getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date");
        $current_time = getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "system", "time-gmt");

    } // end if ((isset($row_contest_dates)) && (!empty($row_contest_dates)))

} // end if (((strpos($section, "step") === FALSE) && ($section != "setup")) && ($section != "update"))

else {

    if (($section == "step4") || ($section == "step5") || ($section == "step6")) {

        $query_prefs_tz = sprintf("SELECT prefsTimeZone,prefsDateFormat,prefsTimeFormat FROM %s WHERE id='1'", $prefix."preferences");
        $prefs_tz = mysqli_query($connection,$query_prefs_tz) or die (mysqli_error($connection));
        $row_prefs_tz = mysqli_fetch_assoc($prefs_tz);

        $current_date = getTimeZoneDateTime($row_prefs_tz['prefsTimeZone'], time(), $row_prefs_tz['prefsDateFormat'], $row_prefs_tz['prefsTimeFormat'], "system", "date");
        $current_date_display = getTimeZoneDateTime($row_prefs_tz['prefsTimeZone'], time(), $row_prefs_tz['prefsDateFormat'], $row_prefs_tz['prefsTimeFormat'], $sidebar_date_format, "date");
        $current_time = getTimeZoneDateTime($row_prefs_tz['prefsTimeZone'], time(), $row_prefs_tz['prefsDateFormat'], $row_prefs_tz['prefsTimeFormat'], "system", "time-gmt");

    }

}

$logged_in = FALSE;
$admin_user = FALSE;
$disable_pay = FALSE;
$show_scores = FALSE;
$show_scoresheets = FALSE;
$show_presentation = FALSE;

// User constants
if (isset($_SESSION['loginUsername']))  {

    $logged_in = TRUE;
    $logged_in_name = $_SESSION['loginUsername'];

    if (((strpos($section, "step") === FALSE) && ($section != "setup")) && ($section != "update")) {

        if ($_SESSION['userLevel'] <= "1") {
            if ($section == "admin") $link_admin = "#";
            else $link_admin = "";
            $admin_user = TRUE;
        }

        if ((isset($_SESSION['contestEntryFee'])) && (!empty($_SESSION['contestEntryFee']))) {

            // Get Entry Fees
           $total_entry_fees = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $_SESSION['user_id'], $filter, $_SESSION['comp_id']);
           if ($bid == "default") $user_id_paid = $_SESSION['user_id'];
           else $user_id_paid = $bid;
           $total_paid_entry_fees = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $user_id_paid, $filter, $_SESSION['comp_id']);
           $total_to_pay = $total_entry_fees - $total_paid_entry_fees;
        
        }

        // Disable pay?
        if (($registration_open == 2) && ($shipping_window_open == 2) && ($dropoff_window_open == 2) && ($entry_window_open == 2) && ($pay_window_open == 2)) $disable_pay = TRUE;

    }

}

if ((strpos($section, "step") === FALSE) && ($section != "setup") && ($judging_past == 0)) {
    if (($_SESSION['prefsDisplayWinners'] == "Y") && (judging_winner_display($_SESSION['prefsWinnerDelay']))) {
        $show_presentation = TRUE;
        if ($logged_in) {
            $show_scores = TRUE;
            $show_scoresheets = TRUE;
        }
    }
}

// DataTables Default Values
$output_datatables_bPaginate = "true";
$output_datatables_sPaginationType = "full_numbers";
$output_datatables_bLengthChange = "true";
if ((strpos($section, "step") === FALSE) && ($section != "setup")) $output_datatables_iDisplayLength = round($_SESSION['prefsRecordPaging']);
if ($action == "print") $output_datatables_sDom = "it";
else $output_datatables_sDom = "rftp";
$output_datatables_bStateSave = "false";
$output_datatables_bProcessing = "false";

// Disable stuff on participants, entries, tables, and other screens when looking at archived data
$archive_display = FALSE;
if ($dbTable != "default") $archive_display = TRUE;

$totalRows_mods = "";

// Get unconfirmed entry count
if (((strpos($section, "step") === FALSE) && ($section != "setup")) && ($section != "update")) {
    if (($section == "admin") && (($filter == "default") && ($bid == "default") && ($view == "default"))) $entries_unconfirmed = ($totalRows_entry_count - $totalRows_log_confirmed);
    else $entries_unconfirmed = ($totalRows_log - $totalRows_log_confirmed);
}

$barcode_qrcode_array = array("0","2","N","C","3","4","5","6","1");
$no_entry_form_array = array("0","1","2","E","C");

if ($logged_in) $location_target = "_blank";
else $location_target = "_self";

if ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "BA")) {
    $optional_info_styles = array();
}
elseif ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "AABC")) {
    $optional_info_styles = array("12-01","14-08","17-03","18-04","18-05","19-05","19-07","16-01","19-01","19-02","19-03","19-04","19-06","20-02","20-03");
}
elseif ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "AABC2022")) {
    $optional_info_styles = array("07-03","12-01","14-08","17-03","18-04","18-05","16-01","19-01","19-02","19-03","19-04","19-05","19-06","19-07","19-08","19-09","19-10","19-11","19-12","19-13","20-02","20-03","16-08");
}
elseif ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "NWCiderCup")) {
    $optional_info_styles = array("C4-A","C4-B","C5-A","C8-A","C8-B","C8-C","C9-A","C9-B","C9-C");
}
else {
    $optional_info_styles = array("21-B","28-A","30-B","33-A","33-B","34-B","M2-C","M2-D","M2-E","M3-A","M3-B","M4-B","M4-C","7-C","M1-A","M1-B","M1-C","M2-A","M2-B","M4-A","C1-A","C1-B","C1-C");
    if ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "BJCP2021")) $optional_info_styles[] = "25-B";
}

$results_method = array("0" => "By Table/Medal Group", "1" => "By Style", "2" => "By Sub-Style");

if (HOSTED) $_SESSION['prefsCAPTCHA'] = 1;

// Load libraries only when needed - for performance
$tinymce_load = array("contest_info","default","step4","default");
$datetime_load = array("contest_info","evaluation","testing","preferences","step4","step5","step6","default","judging","judging_preferences","dates","non-judging");
$datatables_load = array("admin","list","default","step4","evaluation");

$specialty_ipa_subs = array();
$historical_subs = array();

if (isset($_SESSION['prefsStyleSet'])) {
    // Set vars for backwards compatibility
    if (isset($_SESSION['style_set_beer_end'])) $beer_end = $_SESSION['style_set_beer_end'];
    if (isset($_SESSION['style_set_mead'])) $mead_array = $_SESSION['style_set_mead'];
    if (isset($_SESSION['style_set_cider'])) $cider_array = $_SESSION['style_set_cider'];
    if (isset($_SESSION['style_set_category_end'])) $category_end = $_SESSION['style_set_category_end'];
    if (($_SESSION['prefsStyleSet'] == "BJCP2015") || ($_SESSION['prefsStyleSet'] == "BJCP2021")) {
        $specialty_ipa_subs = array("21-B1","21-B2","21-B3","21-B4","21-B5","21-B6","21-B7");
        $historical_subs = array("27-A1","27-A2","27-A3","27-A4","27-A5","27-A6","27-A7","27-A8","27-A9");
    }
}

// Determine if MariaDB is being used instead of MySQL.
$db_version = $connection -> server_info;
$db_maria = FALSE;
if (strpos(strtolower($db_version), "mariadb") !== false) $db_maria = TRUE;

// Generate a unique encryption key on each page load.
if ((!isset($_SESSION['encryption_key'])) || (empty($_SESSION['encryption_key']))) $_SESSION['encryption_key'] = base64_encode(openssl_random_pseudo_bytes(32));

/**
 * Failsafe for selected styles.
 * If the session variable is empty, check the DB table column.
 * If the column is empty, regenerate.
 * If the column has data, check if it JSON. If so, repopulate
 * session variable. If not, regenerate.
 */
 
$regenerate_selected_styles = FALSE;

if ((strpos($section, 'step') === FALSE) && (check_setup($prefix."bcoem_sys",$database))) {
    
    if ((check_update("prefsSelectedStyles", $prefix."preferences")) && (empty($_SESSION['prefsSelectedStyles']))) {

        $query_selected_styles = sprintf("SELECT prefsSelectedStyles FROM %s WHERE id='1';",$prefix."preferences");
        $selected_styles = mysqli_query($connection,$query_selected_styles) or die (mysqli_error($connection));
        $row_selected_styles = mysqli_fetch_assoc($selected_styles);

        if (empty($row_selected_styles['prefsSelectedStyles'])) $regenerate_selected_styles = TRUE;
        
        else {
            
            $is_styles_json = json_decode($row_selected_styles['prefsSelectedStyles']);
            if (json_last_error() === JSON_ERROR_NONE) $styles_json_data = TRUE;
            else $styles_json_data = FALSE;

            if ($styles_json_data) $_SESSION['prefsSelectedStyles'] = $row_cted_styles['prefsSelectedStyles'];
            else $regenerate_selected_styles = TRUE;
        
        }

        if ($regenerate_selected_styles) {

            $update_selected_styles = array();
            $prefsStyleSet = $_SESSION['prefsStyleSet'];

            if (HOSTED) {
                
                $query_styles_default = sprintf("SELECT id, brewStyle, brewStyleGroup, brewStyleNum, brewStyleVersion FROM `bcoem_shared_styles` WHERE brewStyleVersion='%s'", $prefsStyleSet);
                $styles_default = mysqli_query($connection,$query_styles_default);
                $row_styles_default = mysqli_fetch_assoc($styles_default);

                if ($row_styles_default) {

                    do {

                        $update_selected_styles[$row_styles_default['id']] = array(
                            'brewStyle' => $row_styles_default['brewStyle'],
                            'brewStyleGroup' => $row_styles_default['brewStyleGroup'],
                            'brewStyleNum' => $row_styles_default['brewStyleNum'],
                            'brewStyleVersion' => $row_styles_default['brewStyleVersion']
                        );

                    } while($row_styles_default = mysqli_fetch_assoc($styles_default));

                        
                }
                
                $query_styles_custom = sprintf("SELECT id, brewStyle, brewStyleGroup, brewStyleNum, brewStyleVersion FROM %s WHERE brewStyleOwn='custom'", $prefix."styles");
                $styles_custom = mysqli_query($connection,$query_styles_custom);
                $row_styles_custom = mysqli_fetch_assoc($styles_custom);

                if ($row_styles_custom) {

                    do {

                        $update_selected_styles[$row_styles_custom['id']] = array(
                            'brewStyle' => sterilize($row_styles_custom['brewStyle']),
                            'brewStyleGroup' => sterilize($row_styles_custom['brewStyleGroup']),
                            'brewStyleNum' => sterilize($row_styles_custom['brewStyleNum']),
                            'brewStyleVersion' => sterilize($row_styles_custom['brewStyleVersion'])
                        );

                    } while($row_styles_custom = mysqli_fetch_assoc($styles_custom));

                    
                }
            
            } // end if (HOSTED)
                
            else {

                $query_styles_default = sprintf("SELECT id, brewStyle, brewStyleGroup, brewStyleNum, brewStyleVersion FROM %s WHERE brewStyleVersion='%s'", $prefix."styles", $prefsStyleSet);
                $styles_default = mysqli_query($connection,$query_styles_default);
                $row_styles_default = mysqli_fetch_assoc($styles_default);

                if ($row_styles_default) {
                    do {
                        $update_selected_styles[$row_styles_default['id']] = array(
                            'brewStyle' => sterilize($row_styles_default['brewStyle']),
                            'brewStyleGroup' => sterilize($row_styles_default['brewStyleGroup']),
                            'brewStyleNum' => sterilize($row_styles_default['brewStyleNum']),
                            'brewStyleVersion' => sterilize($row_styles_default['brewStyleVersion'])
                        );
                    } while($row_styles_default = mysqli_fetch_assoc($styles_default));
                }

            } // end else

            $update_selected_styles = json_encode($update_selected_styles);

            $update_table = $prefix."preferences";
            $data = array(
                'prefsSelectedStyles' => $update_selected_styles
            );
            $db_conn->where ('id', 1);
            $result = $db_conn->update ($update_table, $data);
            if (!$result) {
                $error_output[] = $db_conn->getLastError();
                $errors = TRUE;
            }

            // Empty the prefs session variable
            // Will trigger the session to reset the variables in common.db.php upon reload after redirect
            unset($_SESSION['prefs'.$prefix_session]);

        }

    }

}

$default_to = "prost";
$default_from = "noreply";

$drop_ship_dates = array();
if ($row_contest_dates) {

    // Get drop-off and shipping deadlines, if any.

    $drop_off_deadline = "9999999999";
    $shipping_deadline = "9999999999";

    if (!empty($row_contest_dates['contestDropoffDeadline'])) $drop_off_deadline = $row_contest_dates['contestDropoffDeadline'];
    if (!empty($row_contest_dates['contestShippingDeadline'])) $shipping_deadline = $row_contest_dates['contestShippingDeadline'];

    $drop_ship_dates = array(
        $drop_off_deadline, 
        $shipping_deadline
    );

    // Determine the earliest of the two dates.
    // If no drop-off and shipping deadlines specified, default to entry deadline date since it's required.
    if (!empty($drop_ship_dates)) {

        if ((min($drop_ship_dates)) == "9999999999") $drop_ship_deadline = $row_contest_dates['contestEntryDeadline'];
        else $drop_ship_deadline = min($drop_ship_dates);

    }

    else $drop_ship_deadline = $row_contest_dates['contestEntryDeadline'];

    // Specify the latest date users can edit their entries.
    // If the contestEntryEditDeadline column has a value, and it's value is less than the drop_ship_deadline var value, default to it.
    // Otherwise, use the drop_ship_deadline var value.
    if ((!empty($row_contest_dates['contestEntryEditDeadline'])) && ($row_contest_dates['contestEntryEditDeadline'] < $drop_ship_deadline)) $entry_edit_deadline = $row_contest_dates['contestEntryEditDeadline'];
    else $entry_edit_deadline = $drop_ship_deadline;
    $entry_edit_deadline_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $entry_edit_deadline, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");

}


?>
