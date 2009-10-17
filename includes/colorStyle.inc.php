<?php
if ($action == "calculate") $colorSRMmin = $row_style1['brewStyleSRM'];
else $colorSRMmin = $row_styles['brewStyleSRM'];
if ($colorSRMmin >= 01 && $colorSRMmin < 02) { $beercolorMin ="#f3f993"; }
elseif ($colorSRMmin >= 02 && $colorSRMmin < 03) { $beercolorMin ="#f5f75c"; }
elseif ($colorSRMmin >= 03 && $colorSRMmin < 04) { $beercolorMin ="#f6f513"; }
elseif ($colorSRMmin >= 04 && $colorSRMmin < 05) { $beercolorMin ="#eae615"; }
elseif ($colorSRMmin >= 05 && $colorSRMmin < 06) { $beercolorMin ="#e0d01b"; }
elseif ($colorSRMmin >= 06 && $colorSRMmin < 07) { $beercolorMin ="#d5bc26"; }
elseif ($colorSRMmin >= 07 && $colorSRMmin < 08) { $beercolorMin ="#cdaa37"; }
elseif ($colorSRMmin >= 08 && $colorSRMmin < 09) { $beercolorMin ="#c1963c"; }
elseif ($colorSRMmin >= 09 && $colorSRMmin < 10) { $beercolorMin ="#be8c3a"; }
elseif ($colorSRMmin >= 10 && $colorSRMmin < 11) { $beercolorMin ="#be823a"; }
elseif ($colorSRMmin >= 11 && $colorSRMmin < 12) { $beercolorMin ="#c17a37"; }
elseif ($colorSRMmin >= 12 && $colorSRMmin < 13) { $beercolorMin ="#bf7138"; }
elseif ($colorSRMmin >= 13 && $colorSRMmin < 14) { $beercolorMin ="#bc6733"; }
elseif ($colorSRMmin >= 14 && $colorSRMmin < 15) { $beercolorMin ="#b26033"; }
elseif ($colorSRMmin >= 15 && $colorSRMmin < 16) { $beercolorMin ="#a85839"; }
elseif ($colorSRMmin >= 16 && $colorSRMmin < 17) { $beercolorMin ="#985336"; }
elseif ($colorSRMmin >= 17 && $colorSRMmin < 18) { $beercolorMin ="#8d4c32"; }
elseif ($colorSRMmin >= 18 && $colorSRMmin < 19) { $beercolorMin ="#7c452d"; }
elseif ($colorSRMmin >= 19 && $colorSRMmin < 20) { $beercolorMin ="#6b3a1e"; }
elseif ($colorSRMmin >= 20 && $colorSRMmin < 21) { $beercolorMin ="#5d341a"; }
elseif ($colorSRMmin >= 21 && $colorSRMmin < 22) { $beercolorMin ="#4e2a0c"; }
elseif ($colorSRMmin >= 22 && $colorSRMmin < 23) { $beercolorMin ="#4a2727"; }
elseif ($colorSRMmin >= 23 && $colorSRMmin < 24) { $beercolorMin ="#361f1b"; }
elseif ($colorSRMmin >= 24 && $colorSRMmin < 25) { $beercolorMin ="#261716"; }
elseif ($colorSRMmin >= 25 && $colorSRMmin < 26) { $beercolorMin ="#231716"; }
elseif ($colorSRMmin >= 26 && $colorSRMmin < 27) { $beercolorMin ="#19100f"; }
elseif ($colorSRMmin >= 27 && $colorSRMmin < 28) { $beercolorMin ="#16100f"; }
elseif ($colorSRMmin >= 28 && $colorSRMmin < 29) { $beercolorMin ="#120d0c"; }
elseif ($colorSRMmin >= 29 && $colorSRMmin < 30) { $beercolorMin ="#100b0a"; }
elseif ($colorSRMmin >= 30 && $colorSRMmin < 31) { $beercolorMin ="#050b0a"; }
elseif ($colorSRMmin > 31) { $beercolorMin ="#000000"; }
else { $beercolorMin ="#ffffff"; }

if ($action == "calculate") $colorSRMmax = $row_style1['brewStyleSRMMax'];
else $colorSRMmax = $row_styles['brewStyleSRMMax'];
if ($colorSRMmax >= 01 && $colorSRMmax < 02) { $beercolorMax ="#f3f993"; }
elseif ($colorSRMmax >= 02 && $colorSRMmax < 03) { $beercolorMax ="#f5f75c"; }
elseif ($colorSRMmax >= 03 && $colorSRMmax < 04) { $beercolorMax ="#f6f513"; }
elseif ($colorSRMmax >= 04 && $colorSRMmax < 05) { $beercolorMax ="#eae615"; }
elseif ($colorSRMmax >= 05 && $colorSRMmax < 06) { $beercolorMax ="#e0d01b"; }
elseif ($colorSRMmax >= 06 && $colorSRMmax < 07) { $beercolorMax ="#d5bc26"; }
elseif ($colorSRMmax >= 07 && $colorSRMmax < 08) { $beercolorMax ="#cdaa37"; }
elseif ($colorSRMmax >= 08 && $colorSRMmax < 09) { $beercolorMax ="#c1963c"; }
elseif ($colorSRMmax >= 09 && $colorSRMmax < 10) { $beercolorMax ="#be8c3a"; }
elseif ($colorSRMmax >= 10 && $colorSRMmax < 11) { $beercolorMax ="#be823a"; }
elseif ($colorSRMmax >= 11 && $colorSRMmax < 12) { $beercolorMax ="#c17a37"; }
elseif ($colorSRMmax >= 12 && $colorSRMmax < 13) { $beercolorMax ="#bf7138"; }
elseif ($colorSRMmax >= 13 && $colorSRMmax < 14) { $beercolorMax ="#bc6733"; }
elseif ($colorSRMmax >= 14 && $colorSRMmax < 15) { $beercolorMax ="#b26033"; }
elseif ($colorSRMmax >= 15 && $colorSRMmax < 16) { $beercolorMax ="#a85839"; }
elseif ($colorSRMmax >= 16 && $colorSRMmax < 17) { $beercolorMax ="#985336"; }
elseif ($colorSRMmax >= 17 && $colorSRMmax < 18) { $beercolorMax ="#8d4c32"; }
elseif ($colorSRMmax >= 18 && $colorSRMmax < 19) { $beercolorMax ="#7c452d"; }
elseif ($colorSRMmax >= 19 && $colorSRMmax < 20) { $beercolorMax ="#6b3a1e"; }
elseif ($colorSRMmax >= 20 && $colorSRMmax < 21) { $beercolorMax ="#5d341a"; }
elseif ($colorSRMmax >= 21 && $colorSRMmax < 22) { $beercolorMax ="#4e2a0c"; }
elseif ($colorSRMmax >= 22 && $colorSRMmax < 23) { $beercolorMax ="#4a2727"; }
elseif ($colorSRMmax >= 23 && $colorSRMmax < 24) { $beercolorMax ="#361f1b"; }
elseif ($colorSRMmax >= 24 && $colorSRMmax < 25) { $beercolorMax ="#261716"; }
elseif ($colorSRMmax >= 25 && $colorSRMmax < 26) { $beercolorMax ="#231716"; }
elseif ($colorSRMmax >= 26 && $colorSRMmax < 27) { $beercolorMax ="#19100f"; }
elseif ($colorSRMmax >= 27 && $colorSRMmax < 28) { $beercolorMax ="#16100f"; }
elseif ($colorSRMmax >= 28 && $colorSRMmax < 29) { $beercolorMax ="#120d0c"; }
elseif ($colorSRMmax >= 29 && $colorSRMmax < 30) { $beercolorMax ="#100b0a"; }
elseif ($colorSRMmax >= 30 && $colorSRMmax < 31) { $beercolorMax ="#050b0a"; }
elseif ($colorSRMmax > 31) { $beercolorMax ="#000000"; }
else { $beercolorMax ="#ffffff"; }
?>