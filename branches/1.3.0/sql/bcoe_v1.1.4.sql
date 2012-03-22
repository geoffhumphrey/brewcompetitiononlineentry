-- Brew Contest Online Signup
-- http://www.brewcompetition.com
-- Direct inquiries to prost@brewcompetition.com
-- Release 1.1.4 June 2010
-- This software is free, open source and is covered under the 
-- General Public License (GPL) from the Open Source Initiative.
-- As such, you are permitted to download the full source code of 
-- the software for your own use. Feel free to customize it for 
-- your own purposes.

-- --------------------------------------------------------

--
-- Table structure for table `styles`
--

CREATE TABLE IF NOT EXISTS `styles` (
  `id` int(8) NOT NULL auto_increment,
  `style_subcat` char(3)  default NULL,
  `brewStyle` varchar(250)  default NULL,
  `style_og_min` varchar(20)  default NULL,
  `style_og_max` varchar(25)  default NULL,
  `style_fg_min` varchar(20)  default NULL,
  `style_fg_max` varchar(25)  default NULL,
  `style_abv_min` varchar(250)  default NULL,
  `style_abv_max` varchar(25)  default NULL,
  `style_ibu_min` varchar(250)  default NULL,
  `style_ibu_max` varchar(25)  default NULL,
  `style_srm_min` varchar(250)  default NULL,
  `style_srm_max` varchar(25)  default NULL,
  `style_type` varchar(25)  default NULL,
  `style_info` text ,
  `style_link` varchar(200)  default NULL,
  `style_cat` char(2)  default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- 2008 BJCP Styles

INSERT INTO `styles` (`id`, `style_subcat`, `brewStyle`, `style_og_min`, `style_og_max`, `style_fg_min`, `style_fg_max`, `style_abv_min`, `style_abv_max`, `style_ibu_min`, `style_ibu_max`, `style_srm_min`, `style_srm_max`, `style_type`, `style_info`, `style_link`, `style_cat`) VALUES
(1, 'A', 'Lite American Lager', '1.028', '1.040', '0.998', '1.008', '3.2', '4.2', '08', '12', '02', '03', 'Lager', 'A lower gravity and lower calorie beer than standard international lagers. Strong flavors are a fault. Designed to appeal to the broadest range of the general public as possible.<p>Commercial Examples: Bitburger Light, Sam Adams Light, Heineken Premium Light, Miller Lite, Bud Light, Coors Light, Baltika #1 Light, Old Milwaukee Light, Amstel Light. </p>', 'http://www.bjcp.org/2008styles/style01.php#1a', '01'),
(2, 'B', 'Standard American Lager', '1.040', '1.050', '1.004', '1.010', '4.2', '5.3', '08', '15', '02', '04', 'Lager', 'Strong flavors are a fault. An international style including the standard mass-market lager from most countries.<p>Commercial Examples: Pabst Blue Ribbon, Miller High Life, Budweiser, Baltika #3 Classic, Kirin Lager, Grain Belt Premium Lager, Molson Golden, Labatt Blue, Coors Original, Foster&rsquo;s Lager.</p>', 'http://www.bjcp.org/2008styles/style01.php#1b', '01'),
(3, 'C', 'Premium American Lager', '1.046', '1.056', '1.008', '1.012', '4.6', '6.0', '15', '25', '02', '06', 'Lager', 'Premium beers tend to have fewer adjuncts than standard/lite lagers, and can be all-malt. Strong flavors are a fault, but premium lagers have more flavor than standard/lite lagers. A broad style of international mass-market lagers ranging from up-scale American lagers to the typical ''import'' or ''green bottle'' international beers found in America.<p>Commercial Examples: Full Sail Session Premium Lager, Miller Genuine Draft, Corona Extra, Michelob, Coors Extra Gold, Birra Moretti, Heineken, Beck&rsquo;s, Stella Artois, Red Stripe, Singha.</p>', 'http://www.bjcp.org/2008styles/style01.php#1c', '01'),
(4, 'D', 'Munich Helles', '1.045', '1.051', '1.008', '1.012', '4.7', '5.4', '16', '22', '03', '05', 'Lager', 'Unlike Pilsner but like its cousin, Munich Dunkel, Helles is a malt-accentuated beer that is not overly sweet, but rather focuses on malt flavor with underlying hop bitterness in a supporting role.<p>Commercial Examples: Weihenstephaner Original, Hacker-Pschorr M&uuml;nchner Gold, B&uuml;rgerbr&auml;u Wolznacher Hell Naturtr&uuml;b, Mahr&rsquo;s Hell, Paulaner Premium Lager, Spaten Premium Lager, Stoudt&rsquo;s Gold Lager.</p>', 'http://www.bjcp.org/2008styles/style01.php#1d', '01'),
(5, 'E', 'Dortmunder Export', '1.048', '1.056', '1.010', '1.015', '4.8', '6.0', '23', '30', '04', '06', 'Lager', 'Brewed to a slightly higher starting gravity than other light lagers, providing a firm malty body and underlying maltiness to complement the sulfate-accentuated hop bitterness.  The term ''Export'' is a beer strength category under German beer tax law, and is not strictly synonymous with the ''Dortmunder'' style.  Beer from other cities or regions can be brewed to Export strength, and labeled as such. </p><p>Commercial Examples: DAB Export, Dortmunder Union Export, Dortmunder Kronen, Ayinger Jahrhundert, Great Lakes Dortmunder Gold, Barrel House Duveneck&rsquo;s Dortmunder, Bell&rsquo;s Lager, Dominion Lager, Gordon Biersch Golden Export, Flensburger Gold.</p>', 'http://www.bjcp.org/2008styles/style01.php#1e', '01'),
(6, 'A', 'German Pilsner (Pils)', '1.044', '1.050', '1.008', '1.013', '4.4', '5.2', '25', '45', '02', '05', 'Lager', 'Drier and crisper than a Bohemian Pilsener with a bitterness that tends to linger more in the aftertaste due to higher attenuation and higher-sulfate water. Lighter in body and color, and with higher carbonation than a Bohemian Pilsener. Modern examples of German pilsners tend to become paler in color, drier in finish, and more bitter as you move from South to North in Germany.<p>Commercial Examples: Victory Prima Pils, Bitburger, Warsteiner, Trumer Pils, Old Dominion Tupper&rsquo;s Hop Pocket Pils, K&ouml;nig Pilsener, Jever Pils, Left Hand Polestar Pilsner, Holsten Pils, Spaten Pils, Brooklyn Pilsner.  </p>', 'http://www.bjcp.org/2008styles/style02.php#1a', '02'),
(7, 'B', 'Bohemian Pilsener', '1.044', '1.056', '1.013', '1.017', '4.2', '5.4', '35', '45', '03.5', '06', 'Lager', 'Uses Moravian malted barley and a decoction mash for rich, malt character. Saaz hops and low sulfate, low carbonate water provide a distinctively soft, rounded hop profile. Traditional yeast sometimes can provide a background diacetyl note. Dextrins provide additional body, and diacetyl enhances the perception of a fuller palate.</p> <p>Commercial Examples: Pilsner Urquell, Kru?ovice Imperial 12&deg;, Budweiser Budvar (Czechvar in the US), Czech Rebel, Staropramen, Gambrinus Pilsner, Zlaty Bazant Golden Pheasant, Dock Street Bohemian Pilsner.</p> ', 'http://www.bjcp.org/2008styles/style02.php#1b', '02'),
(8, 'C', 'Classic American Pilsner', '1.044', '1.060', '1.010', '1.015', '4.5', '6.0', '25', '40', '03', '06', 'Lager', 'A substantial Pilsner that can stand up to the classic European Pilsners, but exhibiting the native American grains and hops available to German brewers who initially brewed it in the USA. Refreshing, but with the underlying malt and hops that stand out when compared to other modern American light lagers. Maize lends a distinctive grainy sweetness. Rice contributes a crisper, more neutral character. A version of Pilsner brewed in the USA by immigrant German brewers who brought the process and yeast with them when they settled in America.  They worked with the ingredients that were native to America to create a unique version of the original Pilsner.  This style died out after Prohibition but was resurrected as a home-brewed style by advocates of the hobby<p>Commercial Examples: Occasional brewpub and microbrewery specials. </p>', 'http://www.bjcp.org/2008styles/style02.php#1c', '02'),
(9, 'A', 'Vienna Lager', '1.046', '1.052', '1.010', '1.014', '4.5', '5.7', '18', '30', '10', '16', 'Lager', 'Characterized by soft, elegant maltiness that dries out in the finish to avoid becoming sweet.<p>Commercial Examples: Great Lakes Eliot Ness, Boulevard Bob''s 47 Munich-Style Lager, Negra Modelo, Old Dominion Aviator Amber Lager, Gordon Biersch Vienna Lager, Capital Wisconsin Amber, Olde Saratoga Lager, Penn Pilsner.</p> ', 'http://www.bjcp.org/2008styles/style03.php#1a', '03'),
(10, 'B', 'Oktoberfest/Marzen', '1.050', '1.057', '1.012', '1.016', '4.8', '5.7', '20', '28', '07', '14', 'Lager', 'Smooth, clean, and rather rich, with a depth of malt character. This is one of the classic malty styles, with a maltiness that is often described as soft, complex, and elegant but never cloying.<p>Commercial Examples: Paulaner Oktoberfest, Ayinger Oktoberfest-M&auml;rzen, Hacker-Pschorr Original Oktoberfest, Hofbr&auml;u Oktoberfest, Victory Festbier, Great Lakes Oktoberfest, Spaten Oktoberfest, Capital Oktoberfest, Gordon Biersch M&auml;rzen, Goose Island Oktoberfest, Samuel Adams Oktoberfest.</p> ', 'http://www.bjcp.org/2008styles/style03.php#1b', '03'),
(11, 'A', 'Dark American Lager', '1.044', '1.056', '1.008', '1.012', '4.2', '6.0', '08', '20', '14', '22', 'Lager', 'A somewhat sweeter version of standard/premium lager with a little more body and flavor.<p>Commercial Examples: Dixie Blackened Voodoo, Shiner Bock, San Miguel Dark, Baltika #4, Beck''s Dark, Saint Pauli Girl Dark, Warsteiner Dunkel, Heineken Dark Lager, Crystal Diplomat Dark Beer.</p> ', 'http://www.bjcp.org/2008styles/style04.php#1a', '04'),
(12, 'B', 'Munich Dunkel', '1.048', '1.056', '1.010', '1.016', '4.5', '5.6', '18', '28', '14', '28', 'Lager', 'Characterized by depth and complexity of Munich malt and the accompanying melanoidins. Rich Munich flavors, but not as intense as a bock or as roasted as a schwarzbier. <p>Commercial Examples: Ayinger Altbairisch Dunkel, Hacker-Pschorr Alt Munich Dark, Paulaner Alt M&uuml;nchner Dunkel, Weltenburger Kloster Barock-Dunkel, Ettaler Kloster Dunkel, Hofbr&auml;u Dunkel, Penn Dark Lager, K&ouml;nig Ludwig Dunkel, Capital Munich Dark, Harpoon Munich-type Dark Beer, Gordon Biersch Dunkels, Dinkel Acker Dark.  In Bavaria, Ettaler Dunkel, L&ouml;wenbr&auml;u Dunkel, Hartmann Dunkel, Kneitinger Dunkel, Augustiner Dunkel.</p>', 'http://www.bjcp.org/2008styles/style04.php#1b', '04'),
(13, 'C', 'Schwarzbier', '1.046', '1.052', '1.010', '1.016', '4.4', '5.4', '22', '32', '17', '30', 'Lager', 'A dark German lager that balances roasted yet smooth malt flavors with moderate hop bitterness.<p>Commercial Examples: K&ouml;stritzer Schwarzbier, Kulmbacher M&ouml;nchshof Premium Schwarzbier, Samuel Adams Black Lager, Kru?ovice Cerne, Original Badebier, Einbecker Schwarzbier, Gordon Biersch Schwarzbier, Weeping Radish Black Radish Dark Lager, Sprecher Black Bavarian.</p>', 'http://www.bjcp.org/2008styles/style04.php#1c', '04'),
(14, 'A', 'Maibock/Helles Bock', '1.064', '1.072', '1.011', '1.018', '6.3', '7.4', '23', '35', '06', '11', 'Lager', 'A relatively pale, strong, malty lager beer. Designed to walk a fine line between blandness and too much color. Hop character is generally more apparent than in other bocks.<p>Commercial Examples: Ayinger Maibock, Mahr&rsquo;s Bock, Hacker-Pschorr Hubertus Bock, Capital Maibock, Einbecker Mai-Urbock, Hofbr&auml;u Maibock, Victory St. Boisterous, Gordon Biersch Blonde Bock, Smuttynose Maibock.</p>', 'http://www.bjcp.org/2008styles/style05.php#1a', '05'),
(15, 'B', 'Traditional Bock', '1.064', '1.072', '1.013', '1.019', '6.3', '7.2', '20', '27', '14', '22', 'Lager', 'A dark, strong, malty lager beer.<p>Commercial Examples: Einbecker Ur-Bock Dunkel, Pennsylvania Brewing St. Nick Bock, Aass Bock, Great Lakes Rockefeller Bock, Stegmaier Brewhouse Bock.</p>', 'http://www.bjcp.org/2008styles/style05.php#1b', '05'),
(16, 'C', 'Doppelbock', '1.072', '1.096', '1.016', '1.024', '7.0', '10.0', '16', '25', '06', '25', 'Lager', 'A very strong and rich lager. A bigger version of either a traditional bock or a helles bock.<p>Commercial Examples: Paulaner Salvator, Ayinger Celebrator, Weihenstephaner Korbinian, Andechser Doppelbock Dunkel, Spaten Optimator, Tucher Bajuvator, Weltenburger Kloster Asam-Bock, Capital Autumnal Fire, EKU 28, Eggenberg Urbock 23&ordm;, Bell&rsquo;s Consecrator, Moretti La Rossa, Samuel Adams Double Bock.</p>', 'http://www.bjcp.org/2008styles/style05.php#1c', '05'),
(17, 'D', 'Eisbock', '1.078', '1.120', '1.020', '1.035', '9.0', '14.0', '25', '35', '18', '30', 'Lager', 'An extremely strong, full and malty dark lager.<p>Commercial Examples:  Kulmbacher Reichelbr&auml;u Eisbock, Eggenberg Urbock Dunkel Eisbock, Niagara Eisbock, Capital Eisphyre, Southampton Eisbock.</p>', 'http://www.bjcp.org/2008styles/style05.php#1d', '05'),
(18, 'A', 'Cream Ale', '1.042', '1.055', '1.006', '1.012', '4.2', '5.6', '15', '20', '02.5', '05', 'Mixed', 'A clean, well-attenuated, flavorful American lawnmower beer.<p>Commercial Examples: Genesee Cream Ale, Little Kings Cream Ale (Hudepohl), Anderson Valley Summer Solstice Cerveza Crema, Sleeman Cream Ale, New Glarus Spotted Cow, Wisconsin Brewing Whitetail Cream Ale.</p>', 'http://www.bjcp.org/2008styles/style06.php#1a', '06'),
(19, 'B', 'Blonde Ale', '1.038', '1.054', '1.008', '1.013', '4.2', '5.5', '15', '28', '03', '06', 'Mixed', 'Easy-drinking, approachable, malt-oriented American craft beer.<p>Commercial Examples: Pelican Kiwanda Cream Ale, Russian River Aud Blonde, Rogue Oregon Golden Ale, Widmer Blonde Ale, Fuller&rsquo;s Summer Ale, Hollywood Blonde, Redhook Blonde.</p>', 'http://www.bjcp.org/2008styles/style06.php#1b', '06'),
(20, 'C', 'Kolsch', '1.044', '1.050', '1.007', '1.011', '4.4', '5.2', '20', '30', '03.5', '05', 'Mixed', 'A clean, crisp, delicately balanced beer usually with very subtle fruit flavors and aromas. Subdued maltiness throughout leads to a pleasantly refreshing tang in the finish. To the untrained taster easily mistaken for a light lager, a somewhat subtle pilsner, or perhaps a blonde ale.<p>Commercial Examples: Available in Cologne only: PJ Fr&uuml;h, Hellers, Malzm&uuml;hle, Paeffgen, Sion, Peters, Dom; import versions available in parts of North America: Reissdorf, Gaffel; Non-German versions: Eisenbahn Dourada, Goose Island Summertime, Alaska Summer Ale, Harpoon Summer Beer, New Holland Lucid, Saint Arnold Fancy Lawnmower, Capitol City Capitol K&ouml;lsch, Shiner K&ouml;lsch.</p>', 'http://www.bjcp.org/2008styles/style06.php#1c', '06'),
(21, 'D', 'American Wheat or Rye Beer', '1.040', '1.055', '1.008', '1.013', '4.0', '5.5', '15', '30', '03', '06', 'Mixed', 'Refreshing wheat or rye beers that can display more hop character and less yeast character than their German cousins.<p>Commercial Examples: Bell&rsquo;s Oberon, Harpoon UFO Hefeweizen, Three Floyds Gumballhead, Pyramid Hefe-Weizen, Widmer Hefeweizen, Sierra Nevada Unfiltered Wheat Beer, Anchor Summer Beer, Redhook Sunrye, Real Ale Full Moon Pale Rye.</p>', 'http://www.bjcp.org/2008styles/style06.php#1d', '06'),
(22, 'A', 'Northern German Altbier', '1.046', '1.054', '1.010', '1.015', '4.5', '5.2', '25', '40', '13', '19', 'Ale', 'A very clean and relatively bitter beer, balanced by some malt character.  Generally darker, sometimes more caramelly, and usually sweeter and less bitter than D&uuml;sseldorf Altbier.<p>Commercial Examples: DAB Traditional, Hannen Alt, Schwelmer Alt, Grolsch Amber, Alaskan Amber, Long Trail Ale, Otter Creek Copper Ale, Schmaltz&rsquo; Alt. </p>', 'http://www.bjcp.org/2008styles/style07.php#1a', '07'),
(23, 'B', 'California Common Beer', '1.048', '1.054', '1.011', '1.014', '4.5', '5.5', '30', '45', '10', '14', 'Ale', 'A lightly fruity beer with firm, grainy maltiness, interesting toasty and caramel flavors, and showcasing the signature Northern Brewer varietal hop character. <p>Commercial Examples: Anchor Steam, Southampton Steem Beer, Flying Dog Old Scratch Amber Lager. </p>', 'http://www.bjcp.org/2008styles/style07.php#1b', '07'),
(24, 'C', 'Dusseldorf Altbier', '1.046', '1.054', '1.010', '1.015', '4.5', '5.2', '35', '50', '11', '17', 'Ale', 'A well balanced, bitter yet malty, clean, smooth, well-attenuated copper-colored German ale.<p>Commercial Examples: Altstadt brewpubs: Zum Uerige, Im F&uuml;chschen, Schumacher, Zum Schl&uuml;ssel; other examples: Diebels Alt, Schl&ouml;sser Alt, Frankenheim Alt.</p>', 'http://www.bjcp.org/2008styles/style07.php#1c', '07'),
(25, 'A', 'Standard/Ordinary Bitter', '1.032', '1.040', '1.007', '1.011', '3.2', '3.8', '25', '35', '04', '14', 'Ale', 'Low gravity, low alcohol levels and low carbonation make this an easy-drinking beer. Some examples can be more malt balanced, but this should not override the overall bitter impression. Drinkability is a critical component of the style; emphasis is still on the bittering hop addition as opposed to the aggressive middle and late hopping seen in American ales.<p>Commercial Examples: Fuller''s Chiswick Bitter, Adnams Bitter, Young''s Bitter, Greene King IPA, Oakham Jeffrey Hudson Bitter (JHB), Brain''s Bitter, Tetley''s Original Bitter, Brakspear Bitter, Boddington''s Pub Draught.</p>', 'http://www.bjcp.org/2008styles/style08.php#1a', '08'),
(26, 'B', 'Special/Best/Premium Bitter', '1.040', '1.048', '1.008', '1.012', '3.8', '4.6', '25', '40', '05', '16', 'Ale', 'A flavorful, yet refreshing, session beer. Some examples can be more malt balanced, but this should not override the overall bitter impression. Drinkability is a critical component of the style; emphasis is still on the bittering hop addition as opposed to the aggressive middle and late hopping seen in American ales.<p>Commercial Examples: Fuller''s London Pride, Coniston Bluebird Bitter, Timothy Taylor Landlord, Adnams SSB, Young&rsquo;s Special, Shepherd Neame Masterbrew Bitter, Greene King Ruddles County Bitter, RCH Pitchfork Rebellious Bitter, Brains SA, Black Sheep Best Bitter, Goose Island Honkers Ale, Rogue Younger&rsquo;s Special Bitter.</p>', 'http://www.bjcp.org/2008styles/style08.php#1b', '08'),
(27, 'C', 'Extra Special/Strong Bitter (English Pale Ale)', '1.048', '1.060', '1.010', '1.016', '4.6', '6.2', '30', '50', '06', '18', 'Ale', 'An average-strength to moderately-strong English ale. The balance may be fairly even between malt and hops to somewhat bitter. Drinkability is a critical component of the style; emphasis is still on the bittering hop addition as opposed to the aggressive middle and late hopping seen in American ales. A rather broad style that allows for considerable interpretation by the brewer.<p>Commercial Examples: Fullers ESB, Adnams Broadside, Shepherd Neame Bishop''s Finger, Young&rsquo;s Ram Rod, Samuel Smith&rsquo;s Old Brewery Pale Ale, Bass Ale, Whitbread Pale Ale, Shepherd Neame Spitfire, Marston&rsquo;s Pedigree, Black Sheep Ale, Vintage Henley, Mordue Workie Ticket, Morland Old Speckled Hen, Greene King Abbot Ale, Bateman''s  XXXB, Gale&rsquo;s Hordean Special Bitter (HSB), Ushers 1824 Particular Ale, Hopback Summer Lightning, Great Lakes Moondog Ale, Shipyard Old Thumper, Alaskan ESB, Geary&rsquo;s Pale Ale, Cooperstown Old Slugger, Anderson Valley Boont ESB, Avery 14&rsquo;er ESB, Redhook ESB. </p>', 'http://www.bjcp.org/2008styles/style08.php#1c', '08'),
(28, 'A', 'Scottish Light 60/-', '1.030', '1.035', '1.010', '1.013', '2.5', '3.2', '10', '20', '09', '17', 'Ale', 'Cleanly malty with a drying finish, perhaps a few esters, and on occasion a faint bit of peaty earthiness (smoke). Most beers finish fairly dry considering their relatively sweet palate, and as such have a different balance than strong Scotch ales.<p>Commercial Examples: Belhaven 60/-, McEwan&rsquo;s 60/-, Maclay 60/- Light (all are cask-only products not exported to the US).</p>', 'http://www.bjcp.org/2008styles/style09.php#1a', '09'),
(29, 'B', 'Scottish Heavy 70/-', '1.035', '1.040', '1.010', '1.015', '3.2', '3.9', '10', '25', '09', '17', 'Ale', 'Cleanly malty with a drying finish, perhaps a few esters, and on occasion a faint bit of peaty earthiness (smoke). Most beers finish fairly dry considering their relatively sweet palate, and as such have a different balance than strong Scotch ales.<p>Commercial Examples: Caledonian 70/- (Caledonian Amber Ale in the US), Belhaven 70/-, Orkney Raven Ale, Maclay 70/-, Tennents Special, Broughton Greenmantle Ale.</p>', 'http://www.bjcp.org/2008styles/style09.php#1b', '09'),
(30, 'C', 'Scottish Export 80/-', '1.040', '1.054', '1.010', '1.016', '3.9', '5.0', '15', '30', '09', '17', 'Ale', 'Cleanly malty with a drying finish, perhaps a few esters, and on occasion a faint bit of peaty earthiness (smoke). Most beers finish fairly dry considering their relatively sweet palate, and as such have a different balance than strong Scotch ales.<p>Commercial Examples: Orkney Dark Island, Caledonian 80/- Export Ale, Belhaven 80/- (Belhaven Scottish Ale in the US), Southampton 80 Shilling, Broughton Exciseman&rsquo;s 80/-, Belhaven St. Andrews Ale, McEwan''s Export (IPA), Inveralmond Lia Fail, Broughton Merlin&rsquo;s Ale, Arran Dark</p>', 'http://www.bjcp.org/2008styles/style09.php#1c', '09'),
(31, 'D', 'Irish Red Ale', '1.044', '1.060', '1.010', '1.014', '4.0', '6.0', '17', '28', '09', '18', 'Ale', 'An easy-drinking pint. Malt-focused with an initial sweetness and a roasted dryness in the finish.<p>Commercial Examples: Three Floyds Brian Boru Old Irish Ale, Great Lakes Conway&rsquo;s Irish Ale (a bit strong at 6.5%), Kilkenny Irish Beer, O&rsquo;Hara&rsquo;s Irish Red Ale, Smithwick&rsquo;s Irish Ale, Beamish Red Ale, Caffrey&rsquo;s Irish Ale, Goose Island Kilgubbin Red Ale, Murphy&rsquo;s Irish Red (lager), Boulevard Irish Ale, Harpoon Hibernian Ale.</p>', 'http://www.bjcp.org/2008styles/style09.php#1d', '09'),
(32, 'E', 'Strong Scotch Ale', '1.070', '1.130', '1.018', '1.056', '6.5', '10.0', '17', '35', '14', '25', 'Ale', 'Rich, malty and usually sweet, which can be suggestive of a dessert. Complex secondary malt flavors prevent a one-dimensional impression. Strength and maltiness can vary.<p>Commercial Examples: Traquair House Ale, Belhaven Wee Heavy, McEwan''s Scotch Ale, Founders Dirty Bastard, MacAndrew''s Scotch Ale, AleSmith Wee Heavy, Orkney Skull Splitter, Inveralmond Black Friar, Broughton Old Jock, Gordon Highland Scotch Ale, Dragonmead Under the Kilt.</p>', 'http://www.bjcp.org/2008styles/style09.php#1e', '09'),
(33, 'A', 'American Pale Ale', '1.045', '1.060', '1.010', '1.015', '4.5', '6.0', '30', '45', '05', '14', 'Ale', 'Refreshing and hoppy, yet with sufficient supporting malt.<p>Commercial Examples: Sierra Nevada Pale Ale, Stone Pale Ale, Great Lakes Burning River Pale Ale, Bear Republic XP Pale Ale, Anderson Valley Poleeko Gold Pale Ale, Deschutes Mirror Pond, Full Sail Pale Ale, Three Floyds X-Tra Pale Ale, Firestone Pale Ale, Left Hand Brewing Jackman&rsquo;s Pale Ale. </p>', 'http://www.bjcp.org/2008styles/style10.php#1a', '10'),
(34, 'B', 'American Amber Ale', '1.045', '1.060', '1.010', '1.015', '4.5', '6.2', '25', '40', '10', '17', 'Ale', 'Like an American pale ale with more body, more caramel richness, and a balance more towards malt than hops (although hop rates can be significant).<p>Commercial Examples: North Coast Red Seal Ale, Tr&ouml;egs HopBack Amber Ale, Deschutes Cinder Cone Red, Pyramid Broken Rake, St. Rogue Red Ale, Anderson Valley Boont Amber Ale, Lagunitas Censored Ale, Avery Redpoint Ale, McNeill&rsquo;s Firehouse Amber Ale, Mendocino Red Tail Ale, Bell''s Amber.</p>', 'http://www.bjcp.org/2008styles/style10.php#1b', '10'),
(35, 'C', 'American Brown Ale', '1.045', '1.060', '1.010', '1.016', '4.3', '6.2', '20', '40', '18', '35', 'Ale', 'Can be considered a bigger, maltier, hoppier interpretation of Northern English brown ale or a hoppier, less malty Brown Porter, often including the citrus-accented hop presence that is characteristic of American hop varieties.<p>Commercial Examples: Bell&rsquo;s Best Brown, Smuttynose Old Brown Dog Ale, Big Sky Moose Drool Brown Ale, North Coast Acme Brown, Brooklyn Brown Ale, Lost Coast Downtown Brown, Left Hand Deep Cover Brown Ale.</p>', 'http://www.bjcp.org/2008styles/style10.php#1c', '10'),
(36, 'A', 'Mild', '1.030', '1.038', '1.008', '1.013', '2.8', '4.5', '10', '25', '12', '25', 'Ale', 'A light-flavored, malt-accented beer that is readily suited to drinking in quantity. Refreshing, yet flavorful. Some versions may seem like lower gravity brown porters.<p>Commercial Examples: Moorhouse Black Cat, Gale&rsquo;s Festival Mild, Theakston Traditional Mild, Highgate Mild, Sainsbury Mild, Brain&rsquo;s Dark, Banks''s Mild, Coach House Gunpowder Strong Mild, Woodforde&rsquo;s Mardler&rsquo;s Mild, Greene King XX Mild, Motor City Brewing Ghettoblaster.</p>', 'http://www.bjcp.org/2008styles/style11.php#1a', '11'),
(37, 'B', 'Southern English Brown Ale', '1.033', '1.042', '1.011', '1.014', '2.8', '4.2', '12', '20', '19', '35', 'Ale', 'A luscious, malt-oriented brown ale, with a caramel, dark fruit complexity of malt flavor. May seem somewhat like a smaller version of a sweet stout or a sweet version of a dark mild.<p>Commercial Examples: Mann''s Brown Ale (bottled, but not available in the US), Harvey&rsquo;s Nut Brown Ale, Woodeforde&rsquo;s Norfolk Nog.</p>', 'http://www.bjcp.org/2008styles/style11.php#1b', '11'),
(38, 'C', 'Northern English Brown Ale', '1.040', '1.052', '1.008', '1.013', '4.2', '5.4', '20', '30', '12', '22', 'Ale', 'English mild ale or pale ale malt base with caramel malts. May also have small amounts darker malts (e.g., chocolate) to provide color and the nutty character. English hop varieties are most authentic. Moderate carbonate water.<p>Commercial Examples: Newcastle Brown Ale, Samuel Smith&rsquo;s Nut Brown Ale, Riggwelter Yorkshire Ale, Wychwood Hobgoblin, Tr&ouml;egs Rugged Trail Ale, Alesmith Nautical Nut Brown Ale, Avery Ellie&rsquo;s Brown Ale, Goose Island Nut Brown Ale, Samuel Adams Brown Ale.</p>', 'http://www.bjcp.org/2008styles/style11.php#1c', '11'),
(39, 'A', 'Brown Porter', '1.040', '1.052', '1.008', '1.014', '4.0', '5.4', '18', '35', '20', '30', 'Ale', 'A fairly substantial English dark ale with restrained roasty characteristics.<p>Commercial Examples: Fuller''s London Porter, Samuel Smith Taddy Porter, Burton Bridge Burton Porter, RCH Old Slug Porter, Nethergate Old Growler Porter, Hambleton Nightmare Porter, Harvey&rsquo;s Tom Paine Original Old Porter, Salopian Entire Butt English Porter, St. Peters Old-Style Porter, Shepherd Neame Original Porter, Flag Porter, Wasatch Polygamy Porter.</p>', 'http://www.bjcp.org/2008styles/style12.php#1a', '12'),
(40, 'B', 'Robust Porter', '1.048', '1.065', '1.012', '1.016', '4.8', '6.5', '25', '50', '22', '35', 'Ale', 'A substantial, malty dark ale with a complex and flavorful roasty character.<p>Commercial Examples: Great Lakes Edmund Fitzgerald Porter, Meantime London Porter, Anchor Porter, Smuttynose Robust Porter, Sierra Nevada Porter, Deschutes Black Butte Porter,  Boulevard Bully! Porter, Rogue Mocha Porter, Avery New World Porter, Bell&rsquo;s Porter, Great Divide Saint Bridget&rsquo;s Porter.</p>', 'http://www.bjcp.org/2008styles/style12.php#1b', '12'),
(41, 'C', 'Baltic Porter', '1.060', '1.090', '1.016', '1.024', '5.5', '9.5', '20', '40', '17', '30', 'Ale', 'A Baltic Porter often has the malt flavors reminiscent of an English brown porter and the restrained roast of a schwarzbier, but with a higher OG and alcohol content than either. Very complex, with multi-layered flavors.<p>Commercial Examples: Sinebrychoff Porter (Finland), Okocim Porter (Poland), Zywiec Porter (Poland), Baltika #6 Porter (Russia), Carnegie Stark Porter (Sweden), Aldaris Porteris (Latvia), Utenos Porter (Lithuania), Stepan Razin Porter (Russia), N&oslash;gne &oslash; porter (Norway), Neuzeller Kloster-Br&auml;u Neuzeller Porter (Germany), Southampton Imperial Baltic Porter.</p>', 'http://www.bjcp.org/2008styles/style12.php#1c', '12'),
(42, 'A', 'Dry Stout', '1.036', '1.050', '1.007', '1.011', '4.0', '5.0', '30', '45', '25', '40', 'Ale', 'A very dark, roasty, bitter, creamy ale.<p>Commercial Examples: Guinness Draught Stout (also canned), Murphy''s Stout, Beamish Stout, O&rsquo;Hara&rsquo;s Celtic Stout, Russian River O.V.L. Stout, Three Floyd&rsquo;s Black Sun Stout, Dorothy Goodbody&rsquo;s Wholesome Stout, Orkney Dragonhead Stout, Old Dominion Stout, Goose Island Dublin Stout, Brooklyn Dry Stout.</p>', 'http://www.bjcp.org/2008styles/style13.php#1a', '13'),
(43, 'B', 'Sweet Stout', '1.044', '1.060', '1.012', '1.024', '4.0', '6.0', '20', '40', '30', '40', 'Ale', 'A very dark, sweet, full-bodied, slightly roasty ale. Often tastes like sweetened espresso.<p>Commercial Examples: Mackeson''s XXX Stout, Watney''s Cream Stout, Farson&rsquo;s Lacto Stout, St. Peter&rsquo;s Cream Stout, Marston&rsquo;s Oyster Stout, Sheaf Stout, Hitachino Nest Sweet Stout (Lacto), Samuel Adams Cream Stout, Left Hand Milk Stout, Widmer Snowplow Milk Stout.</p>', 'http://www.bjcp.org/2008styles/style13.php#1b', '13'),
(44, 'C', 'Oatmeal Stout', '1.048', '1.065', '1.010', '1.018', '4.2', '5.9', '25', '40', '22', '40', 'Ale', 'A very dark, full-bodied, roasty, malty ale with a complementary oatmeal flavor.<p>Commercial Examples: Samuel Smith Oatmeal Stout, Young''s Oatmeal Stout, McAuslan Oatmeal Stout, Maclay&rsquo;s Oat Malt Stout, Broughton Kinmount Willie Oatmeal Stout, Anderson Valley Barney Flats Oatmeal Stout, Tr&ouml;egs Oatmeal Stout, New Holland The Poet, Goose Island Oatmeal Stout, Wolaver&rsquo;s Oatmeal Stout.</p>', 'http://www.bjcp.org/2008styles/style13.php#1c', '13'),
(45, 'D', 'Foreign Extra Stout', '1.056', '1.075', '1.010', '1.018', '5.5', '8.0', '30', '70', '30', '40', 'Ale', 'A very dark, moderately strong, roasty ale. Tropical varieties can be quite sweet, while export versions can be drier and fairly robust.<p>Commercial Examples: Tropical-Type: Lion Stout (Sri Lanka), Dragon Stout (Jamaica), ABC Stout (Singapore), Royal Extra &ldquo;The Lion Stout&rdquo; (Trinidad), Jamaica Stout (Jamaica), Export-Type: Freeminer Deep Shaft Stout, Guinness Foreign Extra Stout (bottled, not sold in the US), Ridgeway of Oxfordshire Foreign Extra Stout, Coopers Best Extra Stout, Elysian Dragonstooth Stout.</p>', 'http://www.bjcp.org/2008styles/style13.php#1d', '13'),
(46, 'E', 'American Stout', '1.050', '1.075', '1.010', '1.022', '5.0', '7.0', '35', '75', '30', '40', 'Ale', 'A hoppy, bitter, strongly roasted Foreign-style Stout (of the export variety).<p>Commercial Examples: Rogue Shakespeare Stout, Deschutes Obsidian Stout, Sierra Nevada Stout, North Coast Old No. 38, Bar Harbor Cadillac Mountain Stout, Avery Out of Bounds Stout, Lost Coast 8 Ball Stout, Mad River Steelhead Extra Stout.</p>', 'http://www.bjcp.org/2008styles/style13.php#1e', '13'),
(47, 'F', 'Imperial Stout', '1.075', '1.115', '1.018', '1.030', '8.0', '12.0', '50', '90', '30', '40', 'Ale', 'An intensely flavored, big, dark ale. Roasty, fruity, and bittersweet, with a noticeable alcohol presence. Dark fruit flavors meld with roasty, burnt, or almost tar-like sensations. Like a black barleywine with every dimension of flavor coming into play.<p>Commercial Examples: Three Floyd&rsquo;s Dark Lord, Bell&rsquo;s Expedition Stout, North Coast Old Rasputin Imperial Stout, Stone Imperial Stout, Samuel Smith Imperial Stout, Scotch Irish Tsarina Katarina Imperial Stout, Thirsty Dog Siberian Night, Deschutes The Abyss, Great Divide Yeti, Southampton Russian Imperial Stout, Rogue Imperial Stout, Bear Republic Big Bear Black Stout, Great Lakes Blackout Stout, Avery The Czar, Founders Imperial Stout, Victory Storm King, Brooklyn Black Chocolate Stout. </p>', 'http://www.bjcp.org/2008styles/style13.php#1f', '13'),
(48, 'A', 'English IPA', '1.050', '1.075', '1.010', '1.018', '5.0', '7.5', '40', '60', '08', '14', 'Ale', 'A hoppy, moderately strong pale ale that features characteristics consistent with the use of English malt, hops and yeast. Has less hop character and a more pronounced malt flavor than American versions.<p>Commercial Examples: Meantime India Pale Ale, Freeminer Trafalgar IPA, Fuller''s IPA, Ridgeway Bad Elf, Summit India Pale Ale, Samuel Smith''s India Ale, Hampshire Pride of Romsey IPA, Burton Bridge Empire IPA,Middle Ages ImPailed Ale, Goose Island IPA, Brooklyn East India Pale Ale.</p>', 'http://www.bjcp.org/2008styles/style14.php#1a', '14'),
(49, 'B', 'American IPA', '1.056', '1.075', '1.010', '1.018', '5.5', '7.5', '40', '70', '06', '15', 'Ale', 'A decidedly hoppy and bitter, moderately strong American pale ale.<p>Commercial Examples: Bell&rsquo;s Two-Hearted Ale, AleSmith IPA, Russian River Blind Pig IPA, Stone IPA, Three Floyds Alpha King, Great Divide Titan IPA, Bear Republic Racer 5 IPA, Victory Hop Devil, Sierra Nevada Celebration Ale, Anderson Valley Hop Ottin&rsquo;,  Dogfish Head 60 Minute IPA, Founder&rsquo;s Centennial IPA, Anchor Liberty Ale, Harpoon IPA, Avery IPA.</p>', 'http://www.bjcp.org/2008styles/style14.php#1b', '14'),
(50, 'C', 'Imperial IPA', '1.075', '1.090', '1.010', '1.020', '7.5', '10.0', '60', '120', '08', '15', 'Ale', 'An intensely hoppy, very strong pale ale without the big maltiness and/or deeper malt flavors of an American barleywine.  Strongly hopped, but clean, lacking harshness, and a tribute to historical IPAs.  Drinkability is an important characteristic; this should not be a heavy, sipping beer.  It should also not have much residual sweetness or a heavy character grain profile.<p>Commercial Examples: Russian River Pliny the Elder, Three Floyd&rsquo;s Dreadnaught, Avery Majaraja, Bell&rsquo;s Hop Slam, Stone Ruination IPA, Great Divide Hercules Double IPA, Surly Furious, Rogue I2PA, Moylan&rsquo;s Hopsickle Imperial India Pale Ale, Stoudt&rsquo;s Double IPA, Dogfish Head 90-minute IPA, Victory Hop Wallop.</p>', 'http://www.bjcp.org/2008styles/style14.php#1c', '14'),
(51, 'A', 'Weizen/Weissbier', '1.044', '1.052', '1.010', '1.014', '4.3', '5.6', '08', '15', '02', '08', 'Ale', 'A pale, spicy, fruity, refreshing wheat-based ale.<p>Commercial Examples: Weihenstephaner Hefeweissbier, Schneider Weisse Weizenhell, Paulaner Hefe-Weizen, Hacker-Pschorr Weisse, Plank Bavarian Hefeweizen, Ayinger Br&auml;u Weisse, Ettaler Weissbier Hell, Franziskaner Hefe-Weisse, Andechser Weissbier Hefetr&uuml;b, Kapuziner Weissbier, Erdinger Weissbier, Penn Weizen, Barrelhouse Hocking Hills HefeWeizen, Eisenbahn Weizenbier.</p>', 'http://www.bjcp.org/2008styles/style15.php#1a', '15'),
(52, 'B', 'Dunkelweizen', '1.044', '1.056', '1.010', '1.014', '4.3', '5.6', '10', '18', '14', '23', 'Ale', 'A moderately dark, spicy, fruity, malty, refreshing wheat-based ale. Reflecting the best yeast and wheat character of a hefe-weizen blended with the malty richness of a Munich dunkel.<p>Commercial Examples: Weihenstephaner Hefeweissbier Dunkel, Ayinger Ur-Weisse, Franziskaner Dunkel Hefe-Weisse, Schneider Weisse (Original), Ettaler Weissbier Dunkel, Hacker-Pschorr Weisse Dark, Tucher Dunkles Hefe Weizen, Edelweiss Dunkel Weissbier, Erdinger Weissbier Dunkel, Kapuziner Weissbier Schwarz. </p>', 'http://www.bjcp.org/2008styles/style15.php#1b', '15'),
(53, 'C', 'Weizenbock', '1.064', '1.090', '1.015', '1.022', '6.5', '8.0', '15', '30', '12', '25', 'Ale', 'A strong, malty, fruity, wheat-based ale combining the best flavors of a dunkelweizen and the rich strength and body of a bock.<p>Commercial Examples: Schneider Aventinus, Schneider Aventinus Eisbock, Plank Bavarian Dunkler Weizenbock, Plank Bavarian Heller Weizenbock, AleSmith Weizenbock, Erdinger Pikantus, Mahr&rsquo;s Der Weisse Bock, Victory Moonglow Weizenbock, High Point Ramstein Winter Wheat, Capital Weizen Doppelbock, Eisenbahn Vigorosa.</p>', 'http://www.bjcp.org/2008styles/style15.php#1c', '15'),
(54, 'D', 'Roggenbier (German Rye Beer)', '1.046', '1.056', '1.010', '1.014', '4.5', '6.0', '10', '20', '14', '19', 'Ale', 'A dunkelweizen made with rye rather than wheat, but with a greater body and light finishing hops.<p>Commercial Examples: Paulaner Roggen, B&uuml;rgerbr&auml;u Wolznacher Roggenbier. </p>', 'http://www.bjcp.org/2008styles/style15.php#1d', '15'),
(55, 'A', 'Witbier', '1.044', '1.052', '1.008', '1.012', '4.5', '5.5', '10', '20', '02', '04', 'Ale', 'A refreshing, elegant, tasty, moderate-strength wheat-based ale.<p>Commercial Examples: Hoegaarden Wit, St. Bernardus Blanche, Celis White, Vuuve 5, Brugs Tarwebier (Blanche de Bruges), Wittekerke, Allagash White, Blanche de Bruxelles, Ommegang Witte, Avery White Rascal, Unibroue Blanche de Chambly, Sterkens White Ale, Bell&rsquo;s Winter White Ale, Victory Whirlwind Witbier, Hitachino Nest White Ale.</p>', 'http://www.bjcp.org/2008styles/style16.php#1a', '16'),
(56, 'B', 'Belgian Pale Ale', '1.048', '1.054', '1.010', '1.014', '4.8', '5.5', '20', '30', '08', '14', 'Ale', 'A fruity, moderately malty, somewhat spicy, easy-drinking, copper-colored ale. <p>Commercial Examples: De Koninck, Speciale Palm, Dobble Palm, Russian River Perdition, Ginder Ale, Op-Ale, St. Pieters Zinnebir, Brewer&rsquo;s Art House Pale Ale, Avery Karma, Eisenbahn Pale Ale, Ommegang Rare Vos.</p>', 'http://www.bjcp.org/2008styles/style16.php#1b', '16'),
(57, 'C', 'Saison', '1.048', '1.065', '1.002', '1.012', '5.0', '7.0', '20', '35', '05', '14', 'Ale', 'A medium to strong ale with a distinctive yellow-orange color, highly carbonated, well hopped, fruity and dry with a quenching acidity.<p>Commercial Examples: Saison Dupont Vieille Provision; Fant&ocirc;me Saison D&rsquo;Erez&eacute;e - Printemps; Saison de Pipaix; Saison Regal; Saison Voisin; Lefebvre Saison 1900; Ellezelloise Saison 2000; Saison Silly; Southampton Saison; New Belgium Saison; Pizza Port SPF 45; Lost Abbey Red Barn Ale; Ommegang Hennepin.</p>', 'http://www.bjcp.org/2008styles/style16.php#1c', '16'),
(58, 'D', 'Biere de Garde', '1.060', '1.080', '1.008', '1.016', '6.0', '8.5', '18', '28', '06', '19', 'Ale', 'A fairly strong, malt-accentuated, lagered artisanal farmhouse beer.<p>Commercial Examples: Jenlain (amber), Jenlain Bi&egrave;re de Printemps (blond), St. Amand (brown), Ch&rsquo;Ti Brun (brown), Ch&rsquo;Ti Blond (blond), La Choulette (all 3 versions), La Choulette Bi&egrave;re des Sans Culottes (blond), Saint Sylvestre 3 Monts (blond), Biere Nouvelle (brown), Castelain (blond), Jade (amber), Brasseurs Bi&egrave;re de Garde (amber), Southampton Bi&egrave;re de Garde (amber), Lost Abbey Avante Garde (blond).</p>', 'http://www.bjcp.org/2008styles/style16.php#1d', '16'),
(59, 'E', 'Belgian Specialty Ale', '', '', '', '', '', '', '', '', '', '', 'Ale', 'Variable. This category encompasses a wide range of Belgian ales produced by truly artisanal brewers more concerned with creating unique products than in increasing sales.<p>Commercial Examples: Orval; De Dolle&rsquo;s Arabier, Oerbier, Boskeun and Stille Nacht; La Chouffe, McChouffe, Chouffe Bok and N&rsquo;ice Chouffe; Ellezelloise Hercule Stout and Quintine Amber; Unibroue Ephemere, Maudite, Don de Dieu, etc.; Minty; Zatte Bie; Caracole Amber, Saxo and Nostradamus; Silenrieu Sara and Joseph; Fant&ocirc;me Black Ghost and Speciale No&euml;l; Dupont Moinette, Moinette Brune, and Avec Les Bons Voeux de la Brasserie Dupont; St. Fullien No&euml;l; Gouden Carolus No&euml;l; Affligem N&ouml;el; Guldenburg and Pere No&euml;l; De Ranke XX Bitter and Guldenberg; Poperings Hommelbier; Bush (Scaldis); Moinette Brune; Grottenbier; La Trappe Quadrupel; Weyerbacher QUAD; Bi&egrave;re de Miel; Verboden Vrucht; New Belgium 1554 Black Ale; Cantillon Iris; Russian River Temptation; Lost Abbey Cuvee de Tomme and Devotion, Lindemans Kriek and Framboise, and many more.</p>', 'http://www.bjcp.org/2008styles/style16.php#1e', '16'),
(60, 'A', 'Berliner Weisse', '1.028', '1.032', '1.003', '1.006', '2.8', '3.8', '03', '08', '02', '03', 'Ale', 'A very pale, sour, refreshing, low-alcohol wheat ale.<p>Commercial Examples: Schultheiss Berliner Weisse, Berliner Kindl Weisse, Nodding Head Berliner Weisse, Weihenstephan 1809 (unusual in its 5% ABV), Bahnhof Berliner Style Weisse, Southampton Berliner Weisse, Bethlehem Berliner Weisse, Three Floyds Deesko.</p>', 'http://www.bjcp.org/2008styles/style17.php#1a', '17'),
(61, 'B', 'Flanders Red Ale', '1.048', '1.057', '1.002', '1.012', '4.6', '6.5', '10', '25', '10', '16', 'Ale', 'A complex, sour, red wine-like Belgian-style ale.<p>Commercial Examples: Rodenbach Klassiek, Rodenbach Grand Cru, Bellegems Bruin, Duchesse de Bourgogne, New Belgium La Folie, Petrus Oud Bruin, Southampton Flanders Red Ale, Verhaege Vichtenaar, Monk&rsquo;s Cafe Flanders Red Ale, New Glarus Enigma, Panil Barriqu&eacute;e, Mestreechs Aajt.</p>', 'http://www.bjcp.org/2008styles/style17.php#1b', '17'),
(62, 'C', 'Flanders Brown Ale/Oud Bruin', '1.040', '1.074', '1.008', '1.012', '4.0', '8.0', '20', '25', '15', '22', 'Ale', 'A malty, fruity, aged, somewhat sour Belgian-style brown ale.<p>Commercial Examples: Liefman&rsquo;s Goudenband, Liefman&rsquo;s Odnar, Liefman&rsquo;s Oud Bruin, Ichtegem Old Brown, Riva Vondel. </p>', 'http://www.bjcp.org/2008styles/style17.php#1c', '17'),
(63, 'D', 'Straight (Unblended) Lambic', '1.040', '1.054', '1.001', '1.010', '5.0', '6.5', '00', '10', '03', '07', 'Ale', 'Complex, sour/acidic, pale, wheat-based ale fermented by a variety of Belgian microbiota.<p>Commercial Example: Cantillon Grand Cru Bruocsella.</p>', 'http://www.bjcp.org/2008styles/style17.php#1d', '17'),
(64, 'E', 'Gueuze', '1.040', '1.060', '1.000', '1.006', '5.0', '8.0', '00', '10', '03', '07', 'Ale', 'Complex, pleasantly sour/acidic, balanced, pale, wheat-based ale fermented by a variety of Belgian microbiota.<p>Commercial Examples: Boon Oude Gueuze, Boon Oude Gueuze Mariage Parfait, De Cam Gueuze, De Cam/Drei Fonteinen Millennium Gueuze, Drie Fonteinen Oud Gueuze, Cantillon Gueuze, Hanssens Oude Gueuze, Lindemans Gueuze Cuv&eacute;e Ren&eacute;, Girardin Gueuze (Black Label), Mort Subite (Unfiltered) Gueuze, Oud Beersel Oude Gueuze.</p>', 'http://www.bjcp.org/2008styles/style17.php#1e', '17'),
(65, 'F', 'Fruit Lambic', '1.040', '1.060', '1.000', '1.010', '5.0', '7.0', '00', '10', '03', '07', 'Ale', 'Complex, fruity, pleasantly sour/acidic, balanced, pale, wheat-based ale fermented by a variety of Belgian microbiota. A lambic with fruit, not just a fruit beer.<p>Commercial Examples: Boon Framboise Marriage Parfait, Boon Kriek Mariage Parfait, Boon Oude Kriek, Cantillon Fou&rsquo; Foune (apricot), Cantillon Kriek, Cantillon Lou Pepe Kriek, Cantillon Lou Pepe Framboise, Cantillon Rose de Gambrinus, Cantillon St. Lamvinus (merlot grape), Cantillon Vigneronne (Muscat grape), De Cam Oude Kriek, Drie Fonteinen Kriek, Girardin Kriek, Hanssens Oude Kriek, Oud Beersel Kriek, Mort Subite Kriek.</p>', 'http://www.bjcp.org/2008styles/style17.php#1f', '17'),
(66, 'A', 'Belgian Blond Ale', '1.062', '1.075', '1.008', '1.018', '6.0', '7.5', '15', '30', '04', '07', 'Ale', 'A moderate-strength golden ale that has a subtle Belgian complexity, slightly sweet flavor, and dry finish. History: Relatively recent development to further appeal to European Pils drinkers, becoming more popular as it is widely marketed and distributed.<p>Commercial Examples: Leffe Blond, Affligem Blond, La Trappe (Koningshoeven) Blond, Grimbergen Blond, Val-Dieu Blond, Straffe Hendrik Blonde, Brugse Zot, Pater Lieven Blond Abbey Ale, Troubadour Blond Ale.</p>', 'http://www.bjcp.org/2008styles/style18.php#1a', '18'),
(67, 'B', 'Belgian Dubbel', '1.062', '1.075', '1.008', '1.018', '6.0', '7.6', '15', '25', '10', '17', 'Ale', 'A deep reddish, moderately strong, malty, complex Belgian ale.<p>Commercial Examples: Westmalle Dubbel, St. Bernardus Pater 6, La Trappe Dubbel, Corsendonk Abbey Brown Ale, Grimbergen Double, Affligem Dubbel, Chimay Premiere (Red), Pater Lieven Bruin, Duinen Dubbel, St. Feuillien Brune, New Belgium Abbey Belgian Style Ale, Stoudts Abbey Double Ale, Russian River Benediction, Flying Fish Dubbel, Lost Abbey Lost and Found Abbey Ale, Allagash Double.</p>', 'http://www.bjcp.org/2008styles/style18.php#1b', '18'),
(68, 'C', 'Belgian Tripel', '1.075', '1.085', '1.008', '1.014', '7.5', '9.5', '20', '40', '04.5', '07', 'Ale', 'Strongly resembles a Strong Golden Ale but slightly darker and somewhat fuller-bodied. Usually has a more rounded malt flavor but should not be sweet.<p>Commercial Examples: Westmalle Tripel, La Rulles Tripel, St. Bernardus Tripel, Chimay Cinq Cents (White), Watou Tripel, Val-Dieu Triple, Affligem Tripel, Grimbergen Tripel, La Trappe Tripel, Witkap Pater Tripel, Corsendonk Abbey Pale Ale, St. Feuillien Tripel, Bink Tripel, Tripel Karmeliet, New Belgium Trippel, Unibroue La Fin du Monde, Dragonmead Final Absolution, Allagash Tripel Reserve, Victory Golden Monkey.</p>', 'http://www.bjcp.org/2008styles/style18.php#1c', '18'),
(69, 'D', 'Belgian Golden Strong Ale', '1.070', '1.095', '1.005', '1.016', '7.5', '10.5', '22', '35', '03', '06', 'Ale', 'A golden, complex, effervescent, strong Belgian-style ale.<p>Commercial Examples: Duvel, Russian River Damnation, Hapkin, Lucifer, Brigand, Judas, Delirium Tremens, Dulle Teve, Piraat, Great Divide Hades, Avery Salvation, North Coast Pranqster, Unibroue Eau Benite, AleSmith Horny Devil.</p>', 'http://www.bjcp.org/2008styles/style18.php#1d', '18'),
(70, 'E', 'Belgian Dark Strong Ale', '1.075', '1.110', '1.010', '1.024', '8.0', '11.0', '20', '35', '12', '22', 'Ale', 'A dark, very rich, complex, very strong Belgian ale. Complex, rich, smooth and dangerous.<p>Commercial Examples: Westvleteren 12 (yellow cap), Rochefort 10 (blue cap), St. Bernardus Abt 12, Gouden Carolus Grand Cru of the Emperor, Achel Extra Brune, Rochefort 8 (green cap), Southampton Abbot 12, Chimay Grande Reserve (Blue), Brasserie des Rocs Grand Cru, Gulden Draak, Kasteelbier Bi&egrave;re du Chateau Donker, Lost Abbey Judgment Day, Russian River Salvation.</p>', 'http://www.bjcp.org/2008styles/style18.php#1e', '18'),
(71, 'A', 'Old Ale', '1.060', '1.090', '1.015', '1.022', '6.0', '9.0', '30', '60', '10', '22', 'Ale', 'An ale of significant alcoholic strength, bigger than strong bitters and brown porters, though usually not as strong or rich as barleywine. Usually tilted toward a sweeter, maltier balance. &ldquo;It should be a warming beer of the type that is best drunk in half pints by a warm fire on a cold winter&rsquo;s night&rdquo; &ndash; Michael Jackson.<p>Commercial Examples: Gale&rsquo;s Prize Old Ale, Burton Bridge Olde Expensive, Marston Owd Roger, Greene King Olde Suffolk Ale , J.W. Lees Moonraker, Harviestoun Old Engine Oil, Fuller&rsquo;s Vintage Ale, Harvey&rsquo;s Elizabethan Ale, Theakston Old Peculier (peculiar at OG 1.057), Young''s Winter Warmer, Sarah Hughes Dark Ruby Mild, Samuel Smith&rsquo;s Winter Welcome, Fuller&rsquo;s 1845, Fuller&rsquo;s Old Winter Ale, Great Divide Hibernation Ale, Founders Curmudgeon, Cooperstown Pride of Milford Special Ale, Coniston Old Man Ale, Avery Old Jubilation.</p>', 'http://www.bjcp.org/2008styles/style19.php#1a', '19'),
(72, 'B', 'English Barleywine', '1.080', '1.120', '1.018', '1.030', '8.0', '12.0', '35', '70', '08', '22', 'Ale', 'The richest and strongest of the English Ales. A showcase of malty richness and complex, intense flavors. The character of these ales can change significantly over time; both young and old versions should be appreciated for what they are. The malt profile can vary widely; not all examples will have all possible flavors or aromas.<p>Commercial Examples: Thomas Hardy&rsquo;s Ale, Burton Bridge Thomas Sykes Old Ale, J.W. Lee&rsquo;s Vintage Harvest Ale, Robinson&rsquo;s Old Tom, Fuller&rsquo;s Golden Pride, AleSmith Old Numbskull, Young&rsquo;s Old Nick (unusual in its 7.2% ABV), Whitbread Gold Label, Old Dominion Millenium, North Coast Old Stock Ale (when aged), Weyerbacher Blithering Idiot.</p>', 'http://www.bjcp.org/2008styles/style19.php#1b', '19'),
(73, 'C', 'American Barleywine', '1.080', '1.120', '1.016', '1.030', '8.0', '12.0', '50', '120', '10', '19', 'Ale', 'A well-hopped American interpretation of the richest and strongest of the English ales. The hop character should be evident throughout, but does not have to be unbalanced. The alcohol strength and hop bitterness often combine to leave a very long finish. <p>Commercial Examples: Sierra Nevada Bigfoot, Great Divide Old Ruffian, Victory Old Horizontal, Rogue Old Crustacean, Avery Hog Heaven Barleywine, Bell&rsquo;s Third Coast Old Ale, Anchor Old Foghorn, Three Floyds Behemoth, Stone Old Guardian, Bridgeport Old Knucklehead, Hair of the Dog Doggie Claws, Lagunitas Olde GnarleyWine, Smuttynose Barleywine, Flying Dog Horn Dog.</p>', 'http://www.bjcp.org/2008styles/style19.php#1c', '19'),
(74, 'A', 'Fruit Beer', '', '', '', '', '', '', '', '', '', '', 'Ale', 'A harmonious marriage of fruit and beer. The key attributes of the underlying style will be different with the addition of fruit; do not expect the base beer to taste the same as the unadulterated version. Judge the beer based on the pleasantness and balance of the resulting combination.<p>Commercial Examples: New Glarus Belgian Red and Raspberry Tart, Bell&rsquo;s Cherry Stout, Dogfish Head Aprihop, Great Divide Wild Raspberry Ale, Founders R&uuml;b&aelig;us, Ebulum Elderberry Black Ale, Stiegl Radler, Weyerbacher Raspberry Imperial Stout, Abita Purple Haze, Melbourne Apricot Beer and Strawberry Beer, Saxer Lemon Lager, Magic Hat #9, Grozet Gooseberry and Wheat Ale,  Pyramid Apricot Ale, Dogfish Head Fort.</p>', 'http://www.bjcp.org/2008styles/style20.php#1a', '20'),
(75, 'A', 'Spice, Herb, or Vegetable Beer', '', '', '', '', '', '', '', '', '', '', 'Ale', 'A harmonious marriage of spices, herbs and/or vegetables and beer. The key attributes of the underlying style will be different with the addition of spices, herbs and/or vegetables; do not expect the base beer to taste the same as the unadulterated version. Judge the beer based on the pleasantness and balance of the resulting combination.<p>Commercial Examples: Alesmith Speedway Stout, Founders Breakfast Stout, Traquair Jacobite Ale, Rogue Chipotle Ale, Young&rsquo;s Double Chocolate Stout, Bell&rsquo;s Java Stout, Fraoch Heather Ale, Southampton Pumpkin Ale, Rogue Hazelnut Nectar, Hitachino Nest Real Ginger Ale, Breckenridge Vanilla Porter, Left Hand JuJu Ginger Beer, Dogfish Head Punkin Ale, Dogfish Head Midas Touch, Redhook Double Black Stout, Buffalo Bill''s Pumpkin Ale,  BluCreek Herbal Ale, Christian Moerlein Honey Almond,  Rogue Chocolate Stout, Birrificio Baladin Nora, Cave Creek Chili Beer.</p>', 'http://www.bjcp.org/2008styles/style21.php#1a', '21'),
(76, 'B', 'Christmas/Winter Specialty Spiced Beer', '', '', '', '', '', '', '', '', '', '', 'Ale', 'A stronger, darker, spiced beer that often has a rich body and warming finish suggesting a good accompaniment for the cold winter season.<p>Commercial Examples: Anchor Our Special Ale, Harpoon Winter Warmer, Weyerbacher Winter Ale, Nils Oscar Jul&ouml;l, Goose Island Christmas Ale, North Coast Wintertime Ale, Great Lakes Christmas Ale, Lakefront Holiday Spice Lager Beer, Samuel Adams Winter Lager, Troegs The Mad Elf, Jamtlands Jul&ouml;l.</p>', 'http://www.bjcp.org/2008styles/style21.php#1b', '21'),
(77, 'A', 'Classic Rauchbier', '1.050', '1.057', '1.012', '1.016', '4.8', '6.0', '20', '30', '12', '22', 'Ale', 'M&auml;rzen/Oktoberfest-style beer with a sweet, smoky aroma and flavor and a somewhat darker color.<p>Commercial Examples: Schlenkerla Rauchbier M&auml;rzen, Kaiserdom Rauchbier, Eisenbahn Rauchbier, Victory Scarlet Fire Rauchbier, Spezial Rauchbier M&auml;rzen, Saranac Rauchbier.</p>', 'http://www.bjcp.org/2008styles/style22.php#1a', '22');
INSERT INTO `styles` (`id`, `style_subcat`, `brewStyle`, `style_og_min`, `style_og_max`, `style_fg_min`, `style_fg_max`, `style_abv_min`, `style_abv_max`, `style_ibu_min`, `style_ibu_max`, `style_srm_min`, `style_srm_max`, `style_type`, `style_info`, `style_link`, `style_cat`) VALUES
(78, 'B', 'Other Smoked Beer', '', '', '', '', '', '', '', '', '', '', 'Ale', 'This is any beer that is exhibiting smoke as a principle flavor and aroma characteristic other than the Bamberg-style Rauchbier (i.e. beechwood-smoked M&auml;rzen). Balance in the use of smoke, hops and malt character is exhibited by the better examples.<p>Commercial Examples: Alaskan Smoked Porter, O&rsquo;Fallons Smoked Porter, Spezial Lagerbier, Weissbier and Bockbier, Stone Smoked Porter, Schlenkerla Weizen Rauchbier and Ur-Bock Rauchbier, Rogue Smoke, Oskar Blues Old Chub, Left Hand Smoke Jumper, Dark Horse Fore Smoked Stout, Magic Hat Jinx. </p>', 'http://www.bjcp.org/2008styles/style22.php#1b', '22'),
(79, 'C', 'Wood-Aged Beer', '', '', '', '', '', '', '', '', '', '', 'Ale', 'A harmonious blend of the base beer style with characteristics from aging in contact with wood (including any alcoholic products previously in contact with the wood).  The best examples will be smooth, flavorful, well-balanced and well-aged. <em>Beers made using either limited wood aging or products that only provide a subtle background character may be entered in the base beer style categories as long as the wood character isn&rsquo;t prominently featured.</em><p>Commercial Examples: The Lost Abbey Angel&rsquo;s Share Ale, J.W. Lees Harvest Ale in Port, Sherry, Lagavulin Whisky or Calvados Casks, Bush Prestige, Petrus Aged Pale, Firestone Walker Double Barrel Ale, Dominion Oak Barrel Stout, New Holland Dragons Milk, Great Divide Oak Aged Yeti Imperial Stout, Goose Island Bourbon County Stout, Le Coq Imperial Extra Double Stout, Harviestoun Old Engine Oil Special Reserve, many microbreweries have specialty beers served only on premises often directly from the cask.</p>', 'http://www.bjcp.org/2008styles/style22.php#1c', '22'),
(80, 'A', 'Specialty Beer', '', '', '', '', '', '', '', '', '', '', 'Ale', 'A harmonious marriage of ingredients, processes and beer. The key attributes of the underlying style (if declared) will be atypical due to the addition of special ingredients or techniques; do not expect the base beer to taste the same as the unadulterated version. Judge the beer based on the pleasantness and harmony of the resulting combination. The overall uniqueness of the process, ingredients used, and creativity should be considered. The overall rating of the beer depends heavily on the inherently subjective assessment of distinctiveness and drinkability.<p>Commercial Examples: Bell&rsquo;s Rye Stout, Bell&rsquo;s Eccentric Ale, Samuel Adams Triple Bock and Utopias, Hair of the Dog Adam, Great Alba Scots Pine, Tommyknocker Maple Nut Brown Ale, Great Divide Bee Sting Honey Ale, Stoudt&rsquo;s Honey Double Mai Bock, Rogue Dad&rsquo;s Little Helper, Rogue Honey Cream Ale, Dogfish Head India Brown Ale, Zum Uerige Sticke and Doppel Sticke Altbier, Yards Brewing Company General Washington Tavern Porter, Rauchenfels Steinbier, Odells 90 Shilling Ale, Bear Republic Red Rocket Ale, Stone Arrogant Bastard.</p>', 'http://www.bjcp.org/2008styles/style23.php#1a', '23'),
(81, 'A', 'Dry Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'Similar in balance, body, finish and flavor intensity to a dry white wine, with a pleasant mixture of subtle honey character, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol and honey character is the essential final measure of any mead.<p>Commercial Examples: White Winter Dry Mead, Sky River Dry Mead, Intermiel Bouquet Printanier.</p>', 'http://www.bjcp.org/2008styles/style24.php#1a', '24'),
(82, 'B', 'Semi-Sweet Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'Similar in balance, body, finish and flavor intensity to a semisweet (or medium-dry) white wine, with a pleasant mixture of honey character, light sweetness, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol and honey character is the essential final measure of any mead.<p>Commercial Examples: Lurgashall English Mead, Redstone Traditional Mountain Honey Wine, Sky River Semi-Sweet Mead, Intermiel Verge d&rsquo;Or and M&eacute;lilot. </p>', 'http://www.bjcp.org/2008styles/style24.php#1b', '24'),
(83, 'C', 'Sweet Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'Similar in balance, body, finish and flavor intensity to a well-made dessert wine (such as Sauternes), with a pleasant mixture of honey character, residual sweetness, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol and honey character is the essential final measure of any mead.<p>Commercial Examples: Lurgashall Christmas Mead, Chaucer&rsquo;s Mead, Rabbit&rsquo;s Foot Sweet Wildflower Honey Mead, Intermiel Beno&icirc;te.</p>', 'http://www.bjcp.org/2008styles/style24.php#1c', '24'),
(84, 'A', 'Cyser', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'In well-made examples of the style, the fruit is both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Some of the best strong examples have the taste and aroma of an aged Calvados (apple brandy from northern France), while subtle, dry versions can taste similar to many fine white wines.<p>Commercial Examples: White Winter Cyser, Rabbit&rsquo;s Foot Apple Cyser, Long Island Meadery Apple Cyser.</p>', 'http://www.bjcp.org/2008styles/style25.php#1a', '25'),
(85, 'B', 'Pyment', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'In well-made examples of the style, the grape is both distinctively vinous and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. White and red versions can be quite different, and the overall impression should be characteristic of the type of grapes used and suggestive of a similar variety wine.<p>Commercial Examples: Redstone Pinot Noir and White Pyment Mountain Honey Wines.</p>', 'http://www.bjcp.org/2008styles/style25.php#1b', '25'),
(86, 'C', 'Other Fruit Melomel', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'In well-made examples of the style, the fruit is both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Different types of fruit can result in widely different characteristics; allow for a variation in the final product.<p>Commercial Examples: White Winter Blueberry, Raspberry and Strawberry Melomels, Redstone Black Raspberry and Sunshine Nectars, Bees Brothers Raspberry Mead, Intermiel Honey Wine and Raspberries, Honey Wine and Blueberries, and Honey Wine and Blackcurrants, Long Island Meadery Blueberry Mead, Mountain Meadows Cranberry and Cherry Meads.</p>', 'http://www.bjcp.org/2008styles/style25.php#1c', '25'),
(87, 'A', 'Metheglin', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'Often, a blend of spices may give a character greater than the sum of its parts. The better examples of this style use spices/herbs subtly and when more than one are used, they are carefully selected so that they blend harmoniously. See standard description for entrance requirements. Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MUST specify the types of spices used.<p>Commercial Examples: Bonair Chili Mead, Redstone Juniper Mountain Honey Wine, Redstone Vanilla Beans and Cinnamon Sticks Mountain Honey Wine, Long Island Meadery Vanilla Mead, iQhilika Africa Birds Eye Chili Mead, Mountain Meadows Spice Nectar.</p>', 'http://www.bjcp.org/2008styles/style26.php#1a', '26'),
(88, 'B', 'Braggot', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'A harmonious blend of mead and beer, with the distinctive characteristics of both. A wide range of results are possible, depending on the base style of beer, variety of honey and overall sweetness and strength. Beer flavors tend to somewhat mask typical honey flavors found in other meads.<p>Commercial Examples: Rabbit&rsquo;s Foot Diabhal and Bi&egrave;re de Miele, Magic Hat Braggot, Brother Adams Braggot Barleywine Ale, White Winter Traditional Brackett.</p>', 'http://www.bjcp.org/2008styles/style26.php#1b', '26'),
(89, 'C', 'Open Category Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'See standard description for entrance requirements. Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MUST specify the special nature of the mead, whether it is a combination of existing styles, an experimental mead, a historical mead, or some other creation. Any special ingredients that impart an identifiable character MAY be declared.<p>Commercial Examples: Jadwiga, Hanssens/Lurgashall Mead the Gueuze, Rabbit&rsquo;s Foot Private Reserve Pear Mead, White Winter Cherry Bracket, Saba Tej, Mountain Meadows Trickster&rsquo;s Treat Agave Mead, Intermiel Ros&eacute;e.</p>', 'http://www.bjcp.org/2008styles/style26.php#1c', '26'),
(90, 'A', 'Common Cider', '1.045', '1.065', '1.000', '1.020', '5', '8', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Variable, but should be a medium, refreshing drink. Sweet ciders must not be cloying. Dry ciders must not be too austere. An ideal cider serves well as a &quot;session&quot; drink, and suitably accompanies a wide variety of food.<p>Commercial Examples: [US] Red Barn Cider Jonagold Semi-Dry and Sweetie Pie (WA), AEppelTreow Barn Swallow Draft Cider (WI), Wandering Aengus Heirloom Blend Cider (OR), Uncle John&rsquo;s Fruit House Winery Apple Hard Cider (MI), Bellwether Spyglass (NY), West County Pippin (MA), White Winter Hard Apple Cider (WI), Harpoon Cider (MA)</p>', 'http://www.bjcp.org/2008styles/style27.php#1a', '27'),
(91, 'B', 'English Cider', '1.050', '1.075', '0.995', '1.010', '6', '9', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Generally dry, full-bodied, austere.<p>Commercial Examples: [US] Westcott Bay Traditional Very Dry, Traditional Dry and Traditional Medium Sweet (WA), Farnum Hill Extra-Dry, Dry, and Farmhouse (NH), Wandering Aengus Dry Cider (OR), Red Barn Cider Burro Loco (WA), Bellwether Heritage (NY); [UK] Oliver&rsquo;s Herefordshire Dry Cider, various from Hecks, Dunkerton, Burrow Hill, Gwatkin Yarlington Mill, Aspall Dry Cider</p>', 'http://www.bjcp.org/2008styles/style27.php#1b', '27'),
(92, 'C', 'French Cider', '1.050', '1.065', '1.010', '1.020', '3', '6', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Medium to sweet, full-bodied, rich.<p>Commercial Examples: [US] West County Reine de Pomme (MA), Rhyne Cider (CA); [France] Eric Bordelet (various), Etienne Dupont, Etienne Dupont Organic, Bellot.</p>', 'http://www.bjcp.org/2008styles/style27.php#1c', '27'),
(93, 'D', 'Common Perry', '1.050', '1.060', '1.000', '1.020', '5', '7', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Mild. Medium to medium-sweet. Still to lightly sparkling. Only very slight acetification is acceptable. Mousiness, ropy/oily characters are serious faults.<p>[US] White Winter Hard Pear Cider (WI), AEppelTreow Perry (WI), Blossomwood Laughing Pig Perry (CO), Uncle John&rsquo;s Fruit House Winery Perry (MI).</p>', 'http://www.bjcp.org/2008styles/style27.php#1d', '27'),
(94, 'E', 'Traditional Perry', '1.050', '1.070', '1.000', '1.020', '5', '9', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Tannic. Medium to medium-sweet. Still to lightly sparkling. Only very slight acetification is acceptable. Mousiness, ropy/oily characters are serious faults.<p>Commercial Examples:  [France] Bordelet Poire Authentique and Poire Granit, Christian Drouin Poire, [UK] Gwatkin Blakeney Red Perry, Oliver&rsquo;s Blakeney Red Perry and Herefordshire Dry Perry.</p>', 'http://www.bjcp.org/2008styles/style27.php#1e', '27'),
(95, 'A', 'New England Cider', '1.060', '1.100', '0.995', '1.010', '7', '13', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Adjuncts may include white and brown sugars, molasses, small amounts of honey, and raisins. Adjuncts are intended to raise OG well above that which would be achieved by apples alone. This style is sometimes barrel-aged, in which case there will be oak character as with a barrel-aged wine. If the barrel was formerly used to age spirits, some flavor notes from the spirit (e.g., whisky or rum) may also be present, but must be subtle. Entrants MUST specify if the cider was barrel-fermented or aged. Entrants MUST specify carbonation level (still, petillant, or sparkling). Entrants MUST specify sweetness (dry, medium, or sweet). ', 'http://www.bjcp.org/2008styles/style28.php#1a', '28'),
(96, 'B', 'Fruit Cider', '1.045', '1.070', '0.995', '1.010', '5', '9', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Like a dry wine with complex flavors. The apple character must marry with the added fruit so that neither dominates the other.<p>Commercial Examples: [US] West County Blueberry-Apple Wine (MA), AEppelTreow Red Poll Cran-Apple Draft Cider (WI), Bellwether Cherry Street (NY), Uncle John&rsquo;s Fruit Farm Winery Apple Cherry Hard Cider (MI).</p>', 'http://www.bjcp.org/2008styles/style28.php#1b', '28'),
(97, 'C', 'Applewine', '1.070', '1.100', '0.995', '1.010', '9', '12', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Like a dry white wine, balanced, and with low astringency and bitterness.<p>Commercial Examples: [US] AEppelTreow Summer&rsquo;s End (WI), Wandering Aengus Pommeau (OR), Uncle John&rsquo;s Fruit House Winery Fruit House Apple (MI), Irvine''s Vintage Ciders (WA).</p>', 'http://www.bjcp.org/2008styles/style28.php#1c', '28'),
(98, 'D', 'Other Specialty Cider or Perry', '1.045', '1.100', '0.995', '1.020', '5', '12', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Entrants MUST specify all major ingredients and adjuncts. Entrants MUST specify carbonation level (still, petillant, or sparkling). Entrants MUST specify sweetness (dry or medium).<p>Commercial Examples: [US] Red Barn Cider Fire Barrel (WA), AEppelTreow Pear Wine and Sparrow Spiced Cider (WI).</p>', 'http://www.bjcp.org/2008styles/style28.php#1d', '28');

-- --------------------------------------------------------

--
-- Table structure for table `brewer`
--

CREATE TABLE IF NOT EXISTS `brewer` (
  `id` int(8) NOT NULL auto_increment,
  `brewerFirstName` varchar(200) default NULL,
  `brewerLastName` varchar(200) default NULL,
  `brewerAddress` varchar(255) default NULL,
  `brewerCity` varchar(255) default NULL,
  `brewerState` varchar(255) default NULL,
  `brewerZip` varchar(255) default NULL,
  `brewerCountry` varchar(255) default NULL,
  `brewerPhone1` varchar(255) default NULL,
  `brewerPhone2` varchar(255) default NULL,
  `brewerClubs` text,
  `brewerEmail` varchar(255) default NULL,
  `brewerNickname` varchar(255) default NULL,
  `brewerSteward` char(1) default NULL,
  `brewerJudge` char(1) default NULL,
  `brewerJudgeID` varchar(255) default NULL,
  `brewerJudgeRank` varchar(255) default NULL,
  `brewerJudgeLikes` text,
  `brewerJudgeDislikes` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


-- --------------------------------------------------------

--
-- Table structure for table `brewing`
--

CREATE TABLE IF NOT EXISTS `brewing` (
  `id` int(8) NOT NULL auto_increment,
  `brewName` varchar(250) default NULL,
  `brewStyle` varchar(250) default NULL,
  `brewCategory` char(2) default NULL,
  `brewCategorySort` char(2) default NULL,
  `brewSubCategory` char(1) default NULL,
  `brewBottleDate` date default NULL,
  `brewDate` date default NULL,
  `brewYield` varchar(10) default NULL,
  `brewInfo` text,
  `brewMead1` varchar(255) default NULL,
  `brewMead2` varchar(255) default NULL,
  `brewMead3` varchar(255) default NULL,
  `brewExtract1` varchar(100) default NULL,
  `brewExtract1Weight` varchar(10) default NULL,
  `brewExtract2` varchar(100) default NULL,
  `brewExtract2Weight` varchar(10) default NULL,
  `brewExtract3` varchar(100) default NULL,
  `brewExtract3Weight` varchar(4) default NULL,
  `brewExtract4` varchar(100) default NULL,
  `brewExtract4Weight` varchar(10) default NULL,
  `brewExtract5` varchar(100) default NULL,
  `brewExtract5Weight` varchar(10) default NULL,
  `brewGrain1` varchar(100) default NULL,
  `brewGrain1Weight` varchar(10) default NULL,
  `brewGrain2` varchar(100) default NULL,
  `brewGrain2Weight` varchar(10) default NULL,
  `brewGrain3` varchar(100) default NULL,
  `brewGrain3Weight` varchar(10) default NULL,
  `brewGrain4` varchar(100) default NULL,
  `brewGrain4Weight` varchar(10) default NULL,
  `brewGrain5` varchar(100) default NULL,
  `brewGrain5Weight` varchar(4) default NULL,
  `brewGrain6` varchar(100) default NULL,
  `brewGrain6Weight` varchar(10) default NULL,
  `brewGrain7` varchar(100) default NULL,
  `brewGrain7Weight` varchar(10) default NULL,
  `brewGrain8` varchar(100) default NULL,
  `brewGrain8Weight` varchar(10) default NULL,
  `brewGrain9` varchar(100) default NULL,
  `brewGrain9Weight` varchar(10) default NULL,
  `brewAddition1` varchar(100) default NULL,
  `brewAddition1Amt` varchar(20) default NULL,
  `brewAddition2` varchar(100) default NULL,
  `brewAddition2Amt` varchar(20) default NULL,
  `brewAddition3` varchar(100) default NULL,
  `brewAddition3Amt` varchar(20) default NULL,
  `brewAddition4` varchar(100) default NULL,
  `brewAddition4Amt` varchar(20) default NULL,
  `brewAddition5` varchar(100) default NULL,
  `brewAddition5Amt` varchar(20) default NULL,
  `brewAddition6` varchar(100) default NULL,
  `brewAddition6Amt` varchar(20) default NULL,
  `brewAddition7` varchar(100) default NULL,
  `brewAddition7Amt` varchar(20) default NULL,
  `brewAddition8` varchar(100) default NULL,
  `brewAddition8Amt` varchar(20) default NULL,
  `brewAddition9` varchar(100) default NULL,
  `brewAddition9Amt` varchar(20) default NULL,
  `brewHops1` varchar(100) default NULL,
  `brewHops1Weight` varchar(10) default NULL,
  `brewHops1IBU` varchar(10) default NULL,
  `brewHops1Time` varchar(25) default NULL,
  `brewHops2` varchar(100) default NULL,
  `brewHops2Weight` varchar(10) default NULL,
  `brewHops2IBU` varchar(10) default NULL,
  `brewHops2Time` varchar(25) default NULL,
  `brewHops3` varchar(100) default NULL,
  `brewHops3Weight` varchar(10) default NULL,
  `brewHops3IBU` varchar(10) default NULL,
  `brewHops3Time` varchar(25) default NULL,
  `brewHops4` varchar(100) default NULL,
  `brewHops4Weight` varchar(10) default NULL,
  `brewHops4IBU` varchar(10) default NULL,
  `brewHops4Time` varchar(25) default NULL,
  `brewHops5` varchar(100) default NULL,
  `brewHops5Weight` varchar(10) default NULL,
  `brewHops5IBU` varchar(10) default NULL,
  `brewHops5Time` varchar(25) default NULL,
  `brewHops6` varchar(100) default NULL,
  `brewHops6Weight` varchar(10) default NULL,
  `brewHops6IBU` varchar(10) default NULL,
  `brewHops6Time` varchar(25) default NULL,
  `brewHops7` varchar(100) default NULL,
  `brewHops7Weight` varchar(10) default NULL,
  `brewHops7IBU` varchar(10) default NULL,
  `brewHops7Time` varchar(25) default NULL,
  `brewHops8` varchar(100) default NULL,
  `brewHops8Weight` varchar(10) default NULL,
  `brewHops8IBU` varchar(10) default NULL,
  `brewHops8Time` varchar(25) default NULL,
  `brewHops9` varchar(100) default NULL,
  `brewHops9Weight` varchar(10) default NULL,
  `brewHops9IBU` varchar(10) default NULL,
  `brewHops9Time` varchar(25) default NULL,
  `brewHops1Use` varchar(25) default NULL,
  `brewHops2Use` varchar(25) default NULL,
  `brewHops3Use` varchar(25) default NULL,
  `brewHops4Use` varchar(25) default NULL,
  `brewHops5Use` varchar(25) default NULL,
  `brewHops6Use` varchar(25) default NULL,
  `brewHops7Use` varchar(25) default NULL,
  `brewHops8Use` varchar(25) default NULL,
  `brewHops9Use` varchar(25) default NULL,
  `brewHops1Type` varchar(25) default NULL,
  `brewHops2Type` varchar(25) default NULL,
  `brewHops3Type` varchar(25) default NULL,
  `brewHops4Type` varchar(25) default NULL,
  `brewHops5Type` varchar(25) default NULL,
  `brewHops6Type` varchar(25) default NULL,
  `brewHops7Type` varchar(25) default NULL,
  `brewHops8Type` varchar(25) default NULL,
  `brewHops9Type` varchar(25) default NULL,
  `brewHops1Form` varchar(25) default NULL,
  `brewHops2Form` varchar(25) default NULL,
  `brewHops3Form` varchar(25) default NULL,
  `brewHops4Form` varchar(25) default NULL,
  `brewHops5Form` varchar(25) default NULL,
  `brewHops6Form` varchar(25) default NULL,
  `brewHops7Form` varchar(25) default NULL,
  `brewHops8Form` varchar(25) default NULL,
  `brewHops9Form` varchar(25) default NULL,
  `brewYeast` varchar(250) default NULL,
  `brewYeastMan` varchar(250) default NULL,
  `brewYeastForm` varchar(25) default NULL,
  `brewYeastType` varchar(25) default NULL,
  `brewYeastAmount` varchar(25) default NULL,
  `brewYeastStarter` char(1) default NULL,
  `brewYeastNutrients` text,
  `brewOG` varchar(10) default NULL,
  `brewFG` varchar(10) default NULL,
  `brewPrimary` varchar(10) default NULL,
  `brewPrimaryTemp` varchar(10) default NULL,
  `brewSecondary` varchar(10) default NULL,
  `brewSecondaryTemp` varchar(10) default NULL,
  `brewOther` varchar(10) default NULL,
  `brewOtherTemp` varchar(10) default NULL,
  `brewComments` text,
  `brewMashStep1Name` varchar(250) default NULL,
  `brewMashStep1Temp` char(3) default NULL,
  `brewMashStep1Time` char(3) default NULL,
  `brewMashStep2Name` varchar(250) default NULL,
  `brewMashStep2Temp` char(3) default NULL,
  `brewMashStep2Time` char(3) default NULL,
  `brewMashStep3Name` varchar(250) default NULL,
  `brewMashStep3Temp` char(3) default NULL,
  `brewMashStep3Time` char(3) default NULL,
  `brewMashStep4Name` varchar(250) default NULL,
  `brewMashStep4Temp` char(3) default NULL,
  `brewMashStep4Time` char(3) default NULL,
  `brewMashStep5Name` varchar(250) default NULL,
  `brewMashStep5Temp` char(3) default NULL,
  `brewMashStep5Time` char(3) default NULL,
  `brewFinings` varchar(250) default NULL,
  `brewWaterNotes` varchar(250) default NULL,
  `brewBrewerID` varchar(250) default NULL,
  `brewCarbonationMethod` varchar(255) default NULL,
  `brewCarbonationVol` varchar(10) default NULL,
  `brewCarbonationNotes` text,
  `brewBoilHours` varchar(255) default NULL,
  `brewBoilMins` varchar(255) default NULL,
  `brewBrewerFirstName` varchar(255) default NULL,
  `brewBrewerLastName` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

 

--
-- Table structure for table `contest_info`
--

CREATE TABLE IF NOT EXISTS `contest_info` (
  `id` int(1) NOT NULL default '0',
  `contestName` varchar(255) default NULL,
  `contestHost` varchar(255) default NULL,
  `contestHostWebsite` varchar(255) default NULL,
  `contestHostLocation` varchar(255) default NULL,
  `contestDate` date default NULL,
  `contestRegistrationDeadline` date default NULL,
  `contestEntryDeadline` date default NULL,
  `contestRules` text,
  `contestJudgingLocation` text,
  `contestAwardsLocation` text,
  `contestContactName` varchar(255) default NULL,
  `contestContactEmail` varchar(255) default NULL,
  `contestEntryFee` varchar(10) default NULL,
  `contestCategories` text,
  `contestBottles` text,
  `contestShippingAddress` text,
  `contestDropOff` text,
  `contestAwards` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


-- --------------------------------------------------------

--
-- Table structure for table `preferences`
--

CREATE TABLE IF NOT EXISTS `preferences` (
  `id` int(8) NOT NULL auto_increment,
  `prefsTemp` varchar(255) default NULL,
  `prefsWeight1` varchar(20) default NULL,
  `prefsWeight2` varchar(20) default NULL,
  `prefsLiquid1` varchar(20) default NULL,
  `prefsLiquid2` varchar(20) default NULL,
  `prefsPaypal` char(1) default NULL,
  `prefsPaypalAccount` varchar(255) default NULL,
  `prefsCurrency` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) NOT NULL auto_increment,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(250) NOT NULL default '',
  `userLevel` char(1) default NULL,
  `userQuestion` varchar(255) default NULL,
  `userQuestionAnswer` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


-- --------------------------------------------------------

--
-- Table structure for table `archive`
--

CREATE TABLE IF NOT EXISTS `archive` (
  `id` int(8) NOT NULL auto_increment,
  `archiveUserTableName` varchar(255) default NULL,
  `archiveBrewerTableName` varchar(255) default NULL,
  `archiveBrewingTableName` varchar(255) default NULL,
  `archiveSuffix` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;



-- ********************************************************************************
--
-- Version 1.1.0 Updates Follow
-- 
-- ********************************************************************************

ALTER TABLE `brewing`
ADD `brewExtract1Use` VARCHAR(255) NULL, 
ADD `brewExtract2Use` VARCHAR(255) NULL, 
ADD `brewExtract3Use` VARCHAR(255) NULL, 
ADD `brewExtract4Use` VARCHAR(255) NULL, 
ADD `brewExtract5Use` VARCHAR(255) NULL,
ADD `brewGrain1Use` VARCHAR(255) NULL, 
ADD `brewGrain2Use` VARCHAR(255) NULL, 
ADD `brewGrain3Use` VARCHAR(255) NULL, 
ADD `brewGrain4Use` VARCHAR(255) NULL, 
ADD `brewGrain5Use` VARCHAR(255) NULL,
ADD `brewGrain6Use` VARCHAR(255) NULL, 
ADD `brewGrain7Use` VARCHAR(255) NULL, 
ADD `brewGrain8Use` VARCHAR(255) NULL, 
ADD `brewGrain9Use` VARCHAR(255) NULL,
ADD `brewAddition1Use` VARCHAR(255) NULL, 
ADD `brewAddition2Use` VARCHAR(255) NULL, 
ADD `brewAddition3Use` VARCHAR(255) NULL, 
ADD `brewAddition4Use` VARCHAR(255) NULL, 
ADD `brewAddition5Use` VARCHAR(255) NULL,
ADD `brewAddition6Use` VARCHAR(255) NULL, 
ADD `brewAddition7Use` VARCHAR(255) NULL, 
ADD `brewAddition8Use` VARCHAR(255) NULL, 
ADD `brewAddition9Use` VARCHAR(255) NULL,
ADD `brewPaid` CHAR( 1 ) NULL ,
ADD `brewWinner` CHAR( 1 ) NULL ,
ADD `brewWinnerCat` VARCHAR( 3 ) NULL ,
ADD `brewWinnerSubCat` VARCHAR( 3 ) NULL ,
ADD `brewWinnerPlace` VARCHAR( 3 ) NULL ,
ADD `brewBOSRound` CHAR( 1 ) NULL ,
ADD `brewBOSPlace` VARCHAR( 3 ) NULL ,
ADD `brewReceived` CHAR ( 1 ) NULL ;

ALTER TABLE `preferences` 
ADD `prefsCash` CHAR( 1 ) NULL ,
ADD `prefsCheck` CHAR( 1 ) NULL ,
ADD `prefsCheckPayee` VARCHAR( 255 ) NULL,
ADD `prefsTransFee` CHAR( 1 ) NULL ,
ADD `prefsSponsors` CHAR( 1 ) NULL ,
ADD `prefsSponsorLogos` CHAR( 1 ) NULL ,
ADD `prefsSponsorLogoSize` VARCHAR( 255 ) NULL,
ADD `prefsCompLogoSize` VARCHAR( 255 ) NULL,
ADD `prefsDisplayWinners` CHAR( 1 ) NULL ;

UPDATE `preferences` 
SET 
`prefsCash` = 'Y',
`prefsCheck` = 'Y',
`prefsCheckPayee` = '',
`prefsTransFee` = 'N', 
`prefsSponsors`= 'Y',
`prefsSponsorLogos` = 'N',
`prefsSponsorLogoSize` = '175',
`prefsCompLogoSize` = '250',
`prefsDisplayWinners` = 'Y'
WHERE `id` = 1 LIMIT 1 ;

ALTER TABLE `contest_info` 
ADD `contestLogo` VARCHAR( 255 ) NULL, 
ADD `contestBOSAward` TEXT NULL;

 CREATE TABLE `sponsors` (
`id` INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`sponsorName` VARCHAR( 255 ) NULL ,
`sponsorURL` VARCHAR( 255 ) NULL ,
`sponsorImage` VARCHAR( 255 ) NULL,
`sponsorText` TEXT NULL,
`sponsorLocation` TEXT NULL
) ENGINE = MYISAM;

-- ********************************************************************************
--
-- Version 1.1.1 Updates Follow
-- 
-- ********************************************************************************

ALTER TABLE `contest_info` ADD `contestWinnersComplete` TEXT NULL ;


-- ********************************************************************************
--
-- Version 1.1.2 Updates Follow
-- 
-- ********************************************************************************

ALTER TABLE `contest_info` ADD `contestEntryCap` INT( 8 ) NULL;


-- ********************************************************************************
--
-- Version 1.1.3 Updates Follow
-- 
-- ********************************************************************************

ALTER TABLE `contest_info` 
DROP `contestDate`,
DROP `contestJudgingLocation`,
ADD `contestEntryFee2` VARCHAR( 255 ) NULL AFTER `contestEntryFee`,
ADD `contestEntryFeeDiscount` CHAR( 1 ) NULL AFTER `contestEntryFee2`,
ADD `contestEntryFeeDiscountNum` CHAR( 4 ) NULL AFTER `contestEntryFeeDiscount`,
ADD `contestAwardsLocName` VARCHAR( 255 ) NULL AFTER `contestAwardsLocation`,
ADD `contestAwardsLocDate` DATE NULL AFTER `contestAwardsLocName`,
ADD `contestAwardsLocTime` VARCHAR( 255 ) NULL AFTER `contestAwardsLocDate`;

UPDATE `contest_info` SET `contestEntryFeeDiscount` = 'N' WHERE `id` =1;

CREATE TABLE `judging` (
`id` INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`judgingDate` DATE NOT NULL,
`judgingTime` VARCHAR( 255 ) NOT NULL,
`judgingLocName` VARCHAR( 255 ) NOT NULL,
`judgingLocation` TEXT NOT NULL 
) ENGINE = MYISAM ;

ALTER TABLE `brewer` 
ADD `uid` INT (8) NULL AFTER `id`,
ADD `brewerJudgeLocation` TEXT NULL,
ADD `brewerJudgeLocation2` TEXT NULL,
ADD `brewerStewardLocation` TEXT NULL,
ADD `brewerStewardLocation2` TEXT NULL,
ADD `brewerJudgeAssignedLocation` INT( 8 ) NULL,
ADD `brewerStewardAssignedLocation` INT( 8 ) NULL,
ADD `brewerAssignment` CHAR( 1 ) NULL;

ALTER TABLE `styles` 
ADD `style_active` CHAR( 1 ) NULL DEFAULT 'Y',
ADD `style_owner` VARCHAR( 255 ) NULL DEFAULT 'bcoe',
ADD `brewStyleJudgingLoc` INT( 8 ) NULL;

ALTER TABLE `brewing` 
ADD `brewJudgingLocation` INT( 8 ) NULL;

CREATE TABLE `drop_off` (
`id` INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`dropLocation` TEXT NULL ,
`dropLocationName` VARCHAR( 255 ) NULL ,
`dropLocationPhone` VARCHAR( 255 ) NULL,
`dropLocationWebsite` VARCHAR( 255 ) NULL
) ENGINE = MYISAM ;

ALTER TABLE `contest_info` CHANGE `contestDropOff` `contestShippingName` VARCHAR( 255 ) NULL;
UPDATE `contest_info` SET `contestShippingName` = '' WHERE `id` =1;

ALTER TABLE `preferences` ADD `prefsDisplaySpecial` CHAR( 1 ) NULL;

-- ********************************************************************************
--
-- Version 1.1.4 Updates Follow
-- 
-- ********************************************************************************

ALTER TABLE `contest_info` 
ADD `contestRegistrationOpen` DATE NULL AFTER `contestHostLocation`,
ADD `contestEntryOpen` DATE NULL AFTER `contestRegistrationDeadline`;

ALTER TABLE `preferences` ADD `prefsBOSMead` CHAR( 1 ) NULL DEFAULT 'N',
ADD `prefsBOSCider` CHAR( 1 ) NULL DEFAULT 'N';