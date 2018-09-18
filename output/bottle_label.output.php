<?php
require('../paths.php');
session_name($prefix_session);
require(CONFIG.'bootstrap.php');
require(LIB.'output.lib.php');

// Set up vars
$page_info0 = "";
$page_info1 = "";

if (isset($_SESSION['loginUsername'])) {

    if ($id == "default") {
      // Get id array from $_POST of entry label(s) user wishes to print.
      $entry_arr = array();
      foreach($_POST['id'] as $entry_id) {
        $entry_arr[] = $entry_id;
      }
    }

    if ($bid == "default") {
      $bid = $_SESSION['uid'];
      $brewerFirstName = $_SESSION['brewerFirstName'];
      $brewerLastName = $_SESSION['brewerLastName'];
      $brewerPhone1 = $_SESSION['brewerPhone1'];
      $brewerAddress = $_SESSION['brewerAddress'];
      $brewerCity = $_SESSION['brewerCity'];
      $brewerState = $_SESSION['brewerState'];
      $brewerZip = $_SESSION['brewerZip'];
      $brewerCountry = $_SESSION['brewerCountry'];
      $brewerEmail = $_SESSION['brewerEmail'];
    }

    else {
      $bid = $bid;
      $brewerFirstName = $row_brewer['brewerFirstName'];
      $brewerLastName = $row_brewer['brewerLastName'];
      $brewerPhone1 = $row_brewer['brewerPhone1'];
      $brewerAddress = $row_brewer['brewerAddress'];
      $brewerCity = $row_brewer['brewerCity'];
      $brewerState = $row_brewer['brewerState'];
      $brewerZip = $row_brewer['brewerZip'];
      $brewerCountry = $row_brewer['brewerCountry'];
      $brewerEmail = $row_brewer['brewerEmail'];
    }

    $query_brewer = sprintf("SELECT * FROM %s WHERE uid = '%s'", $brewer_db_table, $bid);
    $brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
    $row_brewer = mysqli_fetch_assoc($brewer);

    $query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s'", $brewing_db_table, $bid);
    $log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
    $row_log = mysqli_fetch_assoc($log);
    $totalRows_log = mysqli_num_rows($log);

    // Flag if rendering an anonymous label (no name/address/phone)
    $anon = FALSE;
    if ($_SESSION['prefsEntryForm'] == "6") $anon = TRUE;

    // Set up vars
    $page_info0 = "";
    $page_info1 = "";
    if (isset($_SESSION['jPrefsBottleNum'])) $label_count = $_SESSION['jPrefsBottleNum'];
    else $label_count = 3; // number of bottles required
    $label_columns = 3;  // number of columns
    $label_columns_bs = 12/$label_columns; // how many Bootstrap columns to show based upon 12 column grid
    $label_endRow = 0;
    $label_hloopRow1 = 0; // first row
    $label_colCount = 0;
    $label_colCountTotal = $totalRows_log * $label_count;

    if ($anon) {
      $label_colCountLimit = 12; // number of labels per page
      $label_height = 200;
      $page_info0 = "<strong>Use clear packing tape to attach a label to the barrel of each bottle.</strong> Please cover the label completely!";
    }

    else {
      $label_colCountLimit = 9; // number of labels per page
      $label_height = 290;
      $page_info0 = "<strong>Attach label to each bottle with a rubberband ONLY.</strong>";
    }

    if ($brewerCountry == "United States") $phone = format_phone_us($brewerPhone1); else $phone = $brewerPhone1;

    // Define Bootstrap Row
    $page_info1 .= "<div class=\"row\">";

    do {

      if ((($id == "default") && (in_array($row_log['id'], $entry_arr))) || (($id != "default") && ($row_log['id'] == $id))) {

        for ($i=1; $i<=$label_count; $i++) {

            // Generate Barcode
            $barcode = sprintf("%06s",$row_log['id']);

            $barcode_link = "http://www.brewcompetition.com/includes/barcode/html/image.php?filetype=PNG&dpi=300&scale=1&rotation=0&font_family=Arial.ttf&font_size=8&text=".$barcode."&thickness=20&code=BCGcode39";

            // Generate QR Code
            require_once (CLASSES.'qr_code/qrClass.php');
            $qr = new qRClas();

            $qrcode_url = $base_url."qr.php?id=".$row_log['id'];
            $qrcode_url = urlencode($qrcode_url);

            $qr->qRCreate($qrcode_url,"55x55","UTF-8");
            $qrcode_link = $qr->url;

            if (($label_endRow == 0) && ($label_hloopRow1++ != 0)) $page_info1 .= "<div class=\"row\">";

            // Layout Column div
            $page_info1 .= "<div class=\"col-xs-".$label_columns_bs." label-border\">";
            $page_info1 .= "<div class=\"label-inner\">";
            $page_info1 .= "<p class=\"text-center\"><strong>".$_SESSION['contestName']."</strong></p>";



            $page_info1 .= "<p><strong>".$label_entry_number.":</strong> ".$barcode."</br>";
            if (!$anon) $page_info1 .= "<strong>".$label_entry_name.":</strong> ".truncate($row_log['brewName'],100)."<br>";
            if ($_SESSION['prefsStyleSet'] == "BA") $page_info1 .= "<strong>Cat: ".$row_log['brewStyle']."<br>";
            else $page_info1 .= "<strong>".$label_category.":</strong> ".$row_log['brewCategory'].$row_log['brewSubCategory']." ".$row_log['brewStyle']."<br>";
            $page_info1 .= "</p>";



            if (!$anon) {
              $page_info1 .= "<small>";
              $page_info1 .= "<p>".$brewerFirstName." ".$brewerLastName."<br>";
              $page_info1 .= $brewerAddress."<br>".$brewerCity.", ".$brewerState." ".$brewerZip." "."<br>";
              $page_info1 .= $brewerEmail."<br>";
              $page_info1 .= $phone;
              $page_info1 .= "</p>";
              $page_info1 .= "</small>";
            }


            $page_info1 .= "<div align=\"center\" style=\"margin-top: 10px;\"><img src=\"".$barcode_link."\">&nbsp;&nbsp;<img src=\"".$qrcode_link."\"></div>";

            $page_info1 .= "<div align=\"center\" class=\"box\">Space Reserved for Competition Staff Use</div>";

            $page_info1 .= "</div>";
            $page_info1 .= "</div>";

            $label_colCount++;

            if (($label_colCount >= $label_colCountLimit) && ($label_colCountTotal > $label_colCountLimit)) {
              $page_info1 .= "<div style=\"page-break-before: always\"></div>";
              $label_colCount = 0;
            }

            $label_endRow++;

            if ($label_endRow >= $label_columns) {
              $page_info1 .= "</div> <!-- end row -->";
              $label_endRow = 0;
            }

        }

      } // end if (in_array($row_log['id'], $entry_arr))

    } while ($row_log = mysqli_fetch_assoc($log));

    // Insert Empty Column if No Content Available
    if ($label_endRow != 0) {
      while ($label_endRow < $label_columns) {
        $page_info1 .= "<div class=\"col-lg-3 col-md-3 col-sm-3\">&nbsp;</div>";
        $label_endRow++;
      }
    }

    // End Bootstrap Row
    $page_info1 .= "</div><!-- end row-->";
}

else {
  $page_info0 = "No user recognized.";
  $page_info1 = "Labels cannot be generated at this time.";
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $_SESSION['contestName']; ?> - Entry Bottle Labels</title>
    <!-- Load Bootstrap and jQuery -->
    <!-- Homepage URLs: http://www.getbootsrap.com and https://jquery.com -->
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Load Font Awesome -->
    <!-- Homepage URL: https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/templates.min.css">
    <style>

    body {
      font-size: 10px;
    }

    .label-border {
      border: 1px solid #000;
    }

    .label-inner {
      padding: 10px 0 10px 0;
      min-height: <?php echo $label_height; ?>px;
    }

    .box {
      font-size: .85em;
      height: 75px;
      vertical-align: middle;
    }

    </style>
</head>
<body>
<div class="container-fluid">
  <p class="lead"><?php echo $_SESSION['contestName']; ?> - <small><?php echo $page_info0; ?></small></p>
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