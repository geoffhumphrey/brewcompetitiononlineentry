<ul id="nav">
  <li><a href="index.php"><?php echo $row_contest_info['contestName']; ?> Home</a></li>
  <li><a href="index.php?section=rules">Rules</a></li>
  <li><a href="index.php?section=entry">Entry Info</a></li>
  <?php if (($row_prefs['prefsSponsors'] == "Y") && ($row_prefs['prefsSponsorLogos'] == "Y") && ($totalRows_sponsors > 0)) { ?><li><a href="index.php?section=sponsors">Sponsors</a></li><?php } ?>
  <?php if (greaterDate($today,$deadline)) echo ""; else { ?>
  <?php if (!isset($_SESSION["loginUsername"])){ ?><li><a href="index.php?section=register">Register</a></li><?php } else { ?><li>||</li><li><a href="index.php?section=list">List of Entries</a></li><?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_contest_info['contestEntryFee'] > 0)) { ?><li><a href="index.php?section=pay">Pay Entry Fees</a></li><?php } ?>
  <?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?><li>||</li><li><a href="index.php?section=admin">Admin</a></li><?php } ?>
  <li><?php sessionAuthenticateNav(); ?></li>
</ul>