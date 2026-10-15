<?php
/**
 * Module:      bottle_label_document.inc.php
 * Description: The printable HTML document shell shared by
 *              output/bottle_label.output.php (single brewer) and
 *              output/bottle_label_batch.output.php (admin batch reprint),
 *              so the printed CSS/self-print script can never drift between
 *              the two flows. Expects $page_info0, $page_info1, and
 *              $label_prefs (from get_bottle_label_prefs()) already set in
 *              the including scope.
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo h($_SESSION['contestName']); ?> - Entry Bottle Labels</title>
    <!-- Load Bootstrap and jQuery -->
    <!-- Homepage URLs: http://www.getbootsrap.com and https://jquery.com -->
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Load Font Awesome -->
    <!-- Homepage URL: https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/v4-shims.min.css" />
    <link rel="stylesheet" href="<?php echo $css_url."templates.min.css"; ?>">
    <style>

  <?php if (($label_prefs['large_entry_num']) || ($label_prefs['large_text'])) { ?>

    body {
      font-size: 1em;
    }

    .label-title {
      font-size: 1.6em;
      margin-bottom: 0;
    }

    .label-entry-num {
      font-size: 3em;
      font-weight: 900;
      line-height: 1.1;
      margin: 10px 0 10px 0;
    }

    .label-category-name {
      font-size: 2em;
      font-weight: 900;
      line-height: 1.0;
      padding: 5px;
    }

    .label-inner p:not(.label-entry-num):not(.label-title) {
      font-size: 1.5em;
    }

  <?php } elseif ($label_prefs['barcode_qr']) { ?>

    body {
      font-size: .95em;
    }

    .label-title {
      font-size: 1.15em;
    }

    .label-entrant {
      font-size: .95em;
    }

  <?php } else { ?>

    body {
      font-size: 1em;
    }

    .label-title {
      font-size: 1.25em;
    }


  <?php } ?>

    .label-entrant {
      font-size: 1em;
    }

    .label-border {
      border: 1px solid #000;
    }

    .label-inner {
      padding: 10px 0 10px 0;
      min-height: <?php echo $label_prefs['bottle_label_height']; ?>px;
    }

    .box {
      font-size: .85em;
      height: 65px;
      vertical-align: middle;
      margin-top: 15px;
    }

    .break-long {
      overflow-wrap: break-word;
      word-wrap: break-word;

      -ms-word-break: break-all;
      word-break: break-all;
      word-break: break-word;

      -ms-hyphens: auto;
      -moz-hyphens: auto;
      -webkit-hyphens: auto;
      hyphens: auto;

    }
    </style>
</head>
<body>
<div class="container-fluid">
  <p style="font-size: 1.1em;"><?php echo $page_info0; ?></p>
  <?php echo $page_info1; ?>
</div><!-- end container -->
</body>
<?php if (isset($_SESSION['loginUsername'])) { ?>
<script type="text/javascript">
function selfPrint(){
    self.focus();
    self.print();
}
setTimeout('selfPrint()',3000);
html.push('');
</script>
<?php } ?>
</html>
