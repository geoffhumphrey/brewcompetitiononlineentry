<?php

$query_spec_beer = sprintf("SELECT brewStyleGroup,brewStyleNum FROM $styles_db_table WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleGroup <='%s' OR brewStyleType ='1') AND brewStyleReqSpec='1'", $_SESSION['prefsStyleSet'],$beer_end);
$spec_beer = mysql_query($query_spec_beer, $brewing) or die(mysql_error());
$row_spec_beer = mysql_fetch_assoc($spec_beer);
do { $special_beer[] = $row_spec_beer['brewStyleGroup']."-".$row_spec_beer['brewStyleNum']; } while ($row_spec_beer = mysql_fetch_assoc($spec_beer));
//print_r($special_beer); echo "<br>";

$query_carb_mead = sprintf("SELECT brewStyleGroup,brewStyleNum FROM $styles_db_table WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Mead' OR brewStyleType ='3') AND brewStyleReqSpec='0' AND brewStyleCarb='1'", $_SESSION['prefsStyleSet']);
$carb_mead = mysql_query($query_carb_mead, $brewing) or die(mysql_error());
$row_carb_mead = mysql_fetch_assoc($carb_mead);
do { $mead[] = $row_carb_mead['brewStyleGroup']."-".$row_carb_mead['brewStyleNum']; } while ($row_carb_mead = mysql_fetch_assoc($carb_mead));
//print_r($mead); echo "<br>";

$query_strength_mead = sprintf("SELECT brewStyleGroup,brewStyleNum FROM $styles_db_table WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Mead' OR brewStyleType ='3') AND brewStyleReqSpec='0' AND brewStyleStrength='1'", $_SESSION['prefsStyleSet']);
$strength_mead = mysql_query($query_strength_mead, $brewing) or die(mysql_error());
$row_strength_mead = mysql_fetch_assoc($strength_mead);
do { $strength_mead[] = $row_strength_mead['brewStyleGroup']."-".$row_strength_mead['brewStyleNum']; } while ($row_strength_mead = mysql_fetch_assoc($strength_mead));
//print_r($mead); echo "<br>";

$query_spec_mead = sprintf("SELECT brewStyleGroup,brewStyleNum FROM $styles_db_table WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Mead' OR brewStyleType ='3') AND brewStyleReqSpec='1'", $_SESSION['prefsStyleSet']);
$spec_mead = mysql_query($query_spec_mead, $brewing) or die(mysql_error());
$row_spec_mead = mysql_fetch_assoc($spec_mead);
do { $special_mead[] = $row_spec_mead['brewStyleGroup']."-".$row_spec_mead['brewStyleNum']; } while ($row_spec_mead = mysql_fetch_assoc($spec_mead));
//print_r($special_mead); echo "<br>";

$query_carb_cider = sprintf("SELECT brewStyleGroup,brewStyleNum FROM $styles_db_table WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Cider' OR brewStyleType ='2') AND brewStyleReqSpec='0' AND brewStyleCarb='1'", $_SESSION['prefsStyleSet']);
$carb_cider = mysql_query($query_carb_cider, $brewing) or die(mysql_error());
$row_carb_cider = mysql_fetch_assoc($carb_cider);
do { $cider[] = $row_carb_cider['brewStyleGroup']."-".$row_carb_cider['brewStyleNum']; } while ($row_carb_cider = mysql_fetch_assoc($carb_cider));
//print_r($cider); echo "<br>";
	
$query_spec_cider = sprintf("SELECT brewStyleGroup,brewStyleNum FROM $styles_db_table WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND (brewStyleType='Cider' OR brewStyleType ='2') AND brewStyleReqSpec='1'", $_SESSION['prefsStyleSet']);
$spec_cider = mysql_query($query_spec_cider, $brewing) or die(mysql_error());
$row_spec_cider = mysql_fetch_assoc($spec_cider);

?>