<?php

/*
// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
  $redirect = "../../403.php";
  $redirect_go_to = sprintf("Location: %s", $redirect);
  header($redirect_go_to);
  exit();
}
*/

$common_descriptors = array(
  $label_alcoholic => $descr_alcoholic,
  $label_metallic => $descr_metallic,
  $label_oxidized => $descr_oxidized,
  $label_phenolic => $descr_phenolic,
  $label_vegetal => $descr_vegetal
);

$beer_descriptors = array(
  $label_astringent => $descr_astringent,
  $label_acetaldehyde => $descr_acetaldehyde,
  $label_diacetyl => $descr_diacetyl,
  $label_dms => $descr_dms,
  $label_estery => $label_estery,
  $label_grassy => $descr_grassy,
  $label_light_struck => $descr_light_struck,
  $label_musty => $descr_musty,
  $label_solvent => $descr_solvent,
  $label_sour_acidic => $descr_sour_acidic,
  $label_sulfur => $descr_sulfur,
  $label_yeasty => $descr_yeasty
);

$cider_descriptors = array(
  $label_acetaldehyde => $descr_acetaldehyde,
  $label_acetified => $descr_acetified,
  $label_acidic => $descr_acidic,
  $label_astringent => $descr_astringent_cider,
  $label_bitter => $descr_bitter,
  $label_diacetyl => $descr_diacetyl_cider,
  $label_farmyard => $descr_farmyard,
  $label_fruity => $descr_fruity,
  $label_mousy => $descr_mousy,
  $label_oaky => $descr_oaky,
  $label_oily_ropy => $descr_oily_ropy,
  $label_oxidized => $descr_oxidized_cider,
  $label_spicy_smoky => $descr_spicy_smoky,
  $label_sulfide => $descr_sulfide,
  $label_sulfite => $descr_sulfite,
  $label_sweet => $descr_sweet,
  $label_thin => $descr_thin
);

$mead_descriptors = array(
  $label_acetic => $descr_acetic,
  $label_acidic => $descr_acidic_mead,
  $label_alcoholic => $descr_alcoholic_mead,
  $label_chemical => $descr_chemical,
  $label_cloying => $descr_cloying,
  $label_floral => $descr_floral,
  $label_fruity => $descr_fruity_mead,
  $label_moldy => $descr_moldy,
  $label_solvent => $descr_solvent,
  $label_sulfury => $descr_sulfur,
  $label_tannic => $descr_tannic,
  $label_waxy => $descr_waxy,
  $label_yeasty => $descr_yeasty
);

$flaws_mouthfeel = array(
  $label_alcoholic,
  $label_astringent,
  $label_diacetyl,
  $label_medicinal,
  $label_metallic,
  $label_solvent,
  $label_sour_acidic,
  $label_spicy,
  $label_vinegary
);

$flaws = array(
  $label_acetaldehyde,
  $label_alcoholic,
  $label_astringent,
  $label_diacetyl,
  $label_dms,
  $label_estery,
  $label_grassy,
  $label_light_struck,
  $label_medicinal,
  $label_metallic,
  $label_musty,
  $label_oxidized,
  $label_plastic,
  $label_solvent,
  $label_sour_acidic,
  $label_smoky,
  $label_spicy,
  $label_sulfury,
  $label_vegetal,
  $label_vinegary,
  $label_yeasty
);

$flaws_structured_beer = array(
  $label_acetaldehyde,
  $label_alcoholic,
  $label_astringent,
  $label_brettanomyces,
  $label_diacetyl,
  $label_dms,
  $label_estery,
  $label_grassy,
  $label_light_struck,
  $label_medicinal,
  $label_metallic,
  $label_musty,
  $label_oxidized,
  $label_plastic,
  $label_solvent,
  $label_sour_acidic,
  $label_smoky,
  $label_spicy,
  $label_sulfury,
  $label_vegetal
);

$flaws_structured_mead = array(
  $label_acetic,
  $label_acidic,
  $label_alcoholic,
  $label_cardboard,
  $label_chemical,
  $label_cloudy,
  $label_cloying,
  $label_floral,
  $label_fruity,
  $label_harsh,
  $label_metallic,
  $label_moldy,
  $label_phenolic,
  $label_sherry,
  $label_solvent,
  $label_sulfury,
  $label_sweet,
  $label_tannic,
  $label_thin,
  $label_vegetal,
  $label_waxy,
  $label_yeasty
);

$flaws_structured_cider = array(
  $label_acetaldehyde,
  $label_acetified,
  $label_acidic,
  $label_alcoholic,
  $label_astringent,
  $label_bitter,
  $label_diacetyl,
  $label_farmyard,
  $label_fruity,
  $label_metallic,
  $label_mousy,
  $label_oaky,
  $label_oily_ropy,
  $label_oxidized,
  $label_phenolic,
  $label_spicy_smoky,
  $label_sulfide,
  $label_sulfite,
  $label_sweet,
  $label_thin,
  $label_vegetal
);

// Scoresheet points (defaults to beer)
$aroma_points = 12;
$appearance_points = 3;
$flavor_points = 20;
$mouthfeel_points = 5;
$overall_points = 10;
$descriptors = array();

if (!empty($query_style)) {

  if ($row_style['brewStyleType'] == 2) {
    $aroma_points = 10;
    $appearance_points = 6;
    $flavor_points = 24;
    $overall_points = 10;
    $descriptors = array_merge($common_descriptors,$cider_descriptors);
  }

  elseif ($row_style['brewStyleType'] == 3) {
    $aroma_points = 10;
    $appearance_points = 6;
    $flavor_points = 24;
    $overall_points = 10;
    $descriptors = array_merge($common_descriptors,$mead_descriptors);
  }

  else {
    $descriptors = array_merge($common_descriptors,$beer_descriptors);
  }

  ksort($descriptors);

}

$aroma_ticks_beer = array(
  $label_malt => "evalAromaMalt",
  $label_hops => "evalAromaHops",
  $label_ferm_char => "evalAromaFerm"
);

$aroma_ticks_mead = array(
  $label_honey => "evalAromaHoney",
  $label_alcohol => "evalAromaAlcohol",
  $label_ferm_char => "evalAromaFerm",
  $label_complexity => "evalAromaComplexity"
);

$aroma_ticks_cider = array(
  $label_fruit => "evalAromaFruit",
  $label_alcohol => "evalAromaAlcohol",
  $label_ferm_char => "evalAromaFerm"
);

$flavor_ticks_beer = array(
  $label_malt => "evalFlavorMalt",
  $label_hops => "evalFlavorHops",
  $label_bitterness => "evalFlavorBitter",
  $label_ferm_char => "evalFlavorFerm"
);

$flavor_ticks_mead = array(
  $label_honey => "evalFlavorHoney",
  $label_sweetness => "evalFlavorSweetness",
  $label_acidity => "evalFlavorAcidity",
  $label_tannin => "evalFlavorTannin",
  $label_alcohol => "evalFlavorAlcohol",
  $label_carbonation => "evalFlavorCarb"
);

$flavor_ticks_cider = array(
  $label_fruit => "evalFlavorFruit",
  $label_sweetness => "evalFlavorSweetness",
  $label_acidity => "evalFlavorAcidity",
  $label_tannin => "evalFlavorTannin",
  $label_alcohol => "evalFlavorAlcohol",
  $label_carbonation => "evalFlavorCarb"
);

$mouthfeel_ticks_beer = array(
  $label_body => "evalMouthfeelBody",
  $label_carbonation => "evalMouthfeelCarb",
  $label_warmth => "evalMouthfeelWarmth",
  $label_creaminess => "evalMouthfeelCream",
  $label_astringency => "evalMouthfeelAstr"
);


?>