<ul id="nav">
  <li><?php if ($section != "default") { ?><a href="index.php"><?php echo $row_contest_info['contestName']; ?> Home</a><?php } else { echo $row_contest_info['contestName']; ?> Home<?php } ?></li>
  <li><?php if ($section != "rules") { ?><a href="index.php?section=rules">Rules</a><?php } else { ?>Rules<?php } ?></li>
  <li><?php if ($section != "entry") { ?><a href="index.php?section=entry">Entry Info</a><?php } else { ?>Entry Info<?php } ?></li>
  <?php if (($row_prefs['prefsSponsors'] == "Y") && ($row_prefs['prefsSponsorLogos'] == "Y") && ($totalRows_sponsors > 0)) { ?><li><?php if ($section != "sponsors") { ?><a href="index.php?section=sponsors">Sponsors</a><?php } else { ?>Sponsors<?php } ?></li><?php } ?>
  <?php if (greaterDate($today,$deadline)) echo ""; else { ?>
  <?php if (!isset($_SESSION["loginUsername"])){ ?><li><?php if ($section != "register") { ?><a href="index.php?section=register">Register</a><?php } else { ?>Register<?php } ?></li><?php } else { ?><li>||</li><li><?php if ($section != "list") { ?><a href="index.php?section=list">List of Entries</a><?php } else { ?>List of Entries<?php } ?></li><?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_contest_info['contestEntryFee'] > 0)) { ?><li><?php if ($section != "pay") { ?><a href="index.php?section=pay">Pay Entry Fees</a><?php } else { ?>Pay Entry Fees<?php } ?></li><?php } ?>
  <?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?><li>||</li><li><a href="index.php?section=admin">Admin</a></li><?php } ?>
  <li><?php sessionAuthenticateNav(); ?></li>
</ul>