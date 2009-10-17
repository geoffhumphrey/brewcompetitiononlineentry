<ul id="nav">
  <li><a href="index.php">Home</a></li>
  <li><a href="index.php?section=rules">Contest Rules</a></li>
  <li><a href="index.php?section=entry">Entry Information</a></li>
  <?php if (greaterDate($today,$deadline)) echo ""; else { ?>
  <?php if (!isset($_SESSION["loginUsername"])){ ?><li><a href="index.php?section=register">Register</a></li><?php } else { ?><li>||</li><li><a href="index.php?section=list">My List of Entries</a></li><?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_contest_info['contestEntryFee'] > 0)) { ?><li><a href="index.php?section=pay">Pay My Entry Fees</a></li><?php } ?>
  <?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?><li>||</li><li><a href="index.php?section=admin">Contest Admin</a></li><?php } ?>
  <li><?php sessionAuthenticateNav(); ?></li>
</ul>