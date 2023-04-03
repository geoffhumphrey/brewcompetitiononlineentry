<?php
require('../paths.php');
require(CONFIG.'bootstrap.php');
require(LIB.'output.lib.php');

// Set up vars
$page_info0 = "";
$page_info1 = "";

$_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_NUMBER_INT);

if (isset($_SESSION['loginUsername'])) {

    // Get id array from $_POST of the entry label(s) user wishes to print.
    if ($id == "default") {
      $entry_arr = array();
      foreach($_POST['id'] as $entry_id) {
        $entry_id = filter_var($entry_id,FILTER_SANITIZE_NUMBER_INT);
        $entry_arr[] = $entry_id;
      }
    }

    if ($bid == "default") $bid = $_SESSION['uid'];

    $query_brewer = sprintf("SELECT * FROM %s WHERE uid = '%s'", $brewer_db_table, $bid);
    $brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
    $row_brewer = mysqli_fetch_assoc($brewer);

    $query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s'", $brewing_db_table, $bid);
    $log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
    $row_log = mysqli_fetch_assoc($log);
    $totalRows_log = mysqli_num_rows($log);

    if ($bid == "default") {
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

    // Flag if rendering an anonymous label (no name/address/phone)
    $anon = FALSE;
    if ($_SESSION['prefsEntryForm'] == "6") $anon = TRUE;

    // Set up vars
    if (isset($_SESSION['jPrefsBottleNum'])) $bottle_label_count = $_SESSION['jPrefsBottleNum'];
    else $bottle_label_count = 3; // number of bottles required
    $bottle_label_columns = 3;  // number of columns
    $bottle_label_columns_bs = 12/$bottle_label_columns; // how many Bootstrap columns to show based upon 12 column grid
    $bottle_label_endRow = 0;
    $bottle_label_hloopRow1 = 0; // first row
    $bottle_label_colCount = 0;
    $bottle_label_colCountTotal = $totalRows_log * $bottle_label_count;

    if ($anon) {
      $bottle_label_colCountLimit = 12; // number of labels per page
      $bottle_label_height = 200;
      $page_info0 = sprintf("%s <strong>%s</strong>", $bottle_labels_002, strtoupper($bottle_labels_001));
    }

    else {
      $bottle_label_colCountLimit = 9; // number of labels per page
      $bottle_label_height = 290;
      $page_info0 = sprintf("%s <strong>%s</strong>", $bottle_labels_003, strtoupper($bottle_labels_001));
    }

    if ($brewerCountry == "United States") $phone = format_phone_us($brewerPhone1);
    else $phone = $brewerPhone1;

    // Define Bootstrap Row
    $page_info1 .= "<div class=\"row\">";

    do {

      if ((($id == "default") && (in_array($row_log['id'], $entry_arr))) || (($id != "default") && ($row_log['id'] == $id))) {

        for ($i=1; $i<=$bottle_label_count; $i++) {

            // Generate Barcode
            $barcode = sprintf("%06s",$row_log['id']);

            $barcode_link = "http://www.brewcompetition.com/includes/barcode/html/image.php?filetype=PNG&dpi=300&scale=1&rotation=0&font_family=Arial.ttf&font_size=8&text=".$barcode."&thickness=20&code=BCGcode39";

            // Generate QR Code
            require_once (CLASSES.'qr_code/qrClass.php');
            $qr = new qRClas();

            $qrcode_url = $base_url."qr.php?id=".$row_log['id'];
            $qrcode_url = urlencode($qrcode_url);

            $qr->qRCreate($qrcode_url,"85x85","UTF-8");
            $qrcode_link = $qr->url;

            $entry_name = html_entity_decode($row_log['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
            $entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

            if (($bottle_label_endRow == 0) && ($bottle_label_hloopRow1++ != 0)) $page_info1 .= "<div class=\"row\">";

            // Layout Column div
            $page_info1 .= "<div class=\"col-xs-".$bottle_label_columns_bs." label-border\">";
            $page_info1 .= "<div class=\"label-inner\">";
            $page_info1 .= "<p class=\"text-center\"><strong>".$_SESSION['contestName']."</strong></p>";

            $page_info1 .= "<p><strong>".$label_entry_number.":</strong> ".$barcode."</br>";
            if (!$anon) $page_info1 .= "<strong>".$label_entry_name.":</strong> ".truncate($entry_name,45,"&hellip;")."<br>";
            if ($_SESSION['prefsStyleSet'] == "BA") $page_info1 .= "<strong>Cat: ".$row_log['brewStyle']."<br>";
            elseif ($_SESSION['prefsStyleSet'] == "AABC")  $page_info1 .= "<strong>".$label_category.":</strong> ".ltrim($row_log['brewCategory'],"0").".".ltrim($row_log['brewSubCategory'],"0")." ".$row_log['brewStyle']."<br>";
            else $page_info1 .= "<strong>".$label_category.":</strong> ".$row_log['brewCategory'].$row_log['brewSubCategory']." ".$row_log['brewStyle']."<br>";

            if ($anon) {
              
              $brewInfo = "";
              
              if (!empty($row_log['brewInfo'])) {
                $brewInfo = $row_log['brewInfo'];
                if (strpos($row_log['brewInfo'],"^") !== FALSE) $brewInfo = str_replace("^", "&nbsp;&nbsp;", $brewInfo);
                $brewInfo = truncate($brewInfo,70,"&hellip;");
              }

              if ((!empty($row_log['brewMead1'])) || (!empty($row_log['brewMead2'])) || (!empty($row_log['brewMead3']))) {
                $brewInfo .= "<br>";
                if (!empty($row_log['brewMead1'])) $brewInfo .= $row_log['brewMead1']."&nbsp;&nbsp;";
                if (!empty($row_log['brewMead2'])) $brewInfo .= $row_log['brewMead2']."&nbsp;&nbsp;";
                if (!empty($row_log['brewMead3'])) $brewInfo .= $row_log['brewMead3'];
              }
              
              if (!empty($brewInfo)) {
                if (($_SESSION['prefsStyleSet'] == "BJCP2021") && ($row_log['brewCategorySort'] == "02") && ($row_log['brewSubCategory'] == "A")) $page_info1 .= "<strong>".$label_regional_variation.":</strong> <span class=\"break-long\">".$brewInfo."</span>";
                else $page_info1 .= "<strong>".$label_required_info.":</strong> <span class=\"break-long\">".$brewInfo."</span>";
              }
            
            }
              
            $page_info1 .= "</p>";

            if (!$anon) {
              $page_info1 .= "<small>";
              $page_info1 .= "<p>";
              if ($_SESSION['prefsProEdition'] == 1) {
                $page_info1 .= $row_brewer['brewerBreweryName']."<br>";
                $page_info1 .= $label_contact.": ".$brewerFirstName." ".$brewerLastName."<br>";
              }
              else $page_info1 .= $brewerFirstName." ".$brewerLastName."<br>";
              //$page_info1 .= $brewerAddress."<br>".$brewerCity.", ".$brewerState." ".$brewerZip." "."<br>";
              $page_info1 .= $brewerEmail."<br>";
              $page_info1 .= $phone;
              $page_info1 .= "</p>";
              $page_info1 .= "</small>";
            }

            $page_info1 .= "<div align=\"center\">";
            $page_info1 .= "<img src=\"".$barcode_link."\"> ";
            if ($anon) {
              $page_info1 .= "</div>";
              $page_info1 .= "<div align=\"center\">";
            }
            $page_info1 .= "<img src=\"".$qrcode_link."\">";
            $page_info1 .= "</div>";
            if (!$anon) $page_info1 .= "<div align=\"center\" class=\"box\">".$bottle_labels_006."</div>";

            $page_info1 .= "</div>";
            $page_info1 .= "</div>";

            $bottle_label_colCount++;

            if (($bottle_label_colCount >= $bottle_label_colCountLimit) && ($bottle_label_colCountTotal > $bottle_label_colCountLimit)) {
              $page_info1 .= "<div style=\"page-break-before: always\"></div>";
              $bottle_label_colCount = 0;
            }

            $bottle_label_endRow++;

            if ($bottle_label_endRow >= $bottle_label_columns) {
              $page_info1 .= "</div> <!-- end row -->";
              $bottle_label_endRow = 0;
            }

        }

      } // end if (in_array($row_log['id'], $entry_arr))

    } while ($row_log = mysqli_fetch_assoc($log));

    // Insert Empty Column if No Content Available
    if ($bottle_label_endRow != 0) {
      while ($bottle_label_endRow < $bottle_label_columns) {
        $page_info1 .= "<div class=\"col-lg-3 col-md-3 col-sm-3\">&nbsp;</div>";
        $bottle_label_endRow++;
      }
    }

    // End Bootstrap Row
    $page_info1 .= "</div><!-- end row-->";
}

else {
  $page_info0 = "No user recognized.";
  $page_info1 = $bottle_labels_000;
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
      font-size: .95em;
    }

    .label-border {
      border: 1px solid #000;
    }

    .label-inner {
      padding: 10px 0 10px 0;
      min-height: <?php echo $bottle_label_height; ?>px;
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
  <p style="font-size: 1.3em;"><?php echo $page_info0; ?></p>
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