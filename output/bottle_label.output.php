<?php

// Redirect if directly accessed without authenticated session
if (!isset($_SESSION['loginUsername'])) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

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
    $brewer = mysqli_query($connection,$query_brewer) or die ("A database error occurred.");
    $row_brewer = mysqli_fetch_assoc($brewer);

    $query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s'", $brewing_db_table, $bid);
    $log = mysqli_query($connection,$query_log) or die ("A database error occurred.");
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
    $standard = FALSE;
    $barcode_qr = FALSE;
    $large_entry_num = FALSE;
    $large_text = FALSE;
    $entry_number_disp = FALSE;
    $judging_number_disp = FALSE;

    /**
     * 0 = Anon, smaller printed number, with barcode [judging number] --New 3.1--
     * 1 = Anon, larger printed number, with barcode/qr [entry number] --New 3.1--
     * 2 = Anon, larger printed number [entry number] --New 3.1--
     * 3 = Anon, larger printed number, with barcode [judging number] --New 3.1--
     * 4 = Anon, larger printed number [judging number] --New 3.1--
     * 5 = Standard with barcode/qr [entry number] --Legacy--
     * 6 = Anon with barcode/qr [entry number] --Legacy--
     * 7 = Standard [entry number] --Legacy--
     * 8 = Anon [entry number] --Legacy--
     * 9 = Anon, smaller printed number [judging number] --New 3.1--
     * 10 = Standard, large number and style [entry number]
     * 11 = Standard, large number and style with barcode/qr [entry number]  
     */

    $anon_label_arr = array(1,2,3,4,6,8,9,0);
    $standard_label_arr = array(5,7,10,11);
    $barcode_qr_arr = array(1,3,5,6,0,11);
    $large_label_arr = array(1,2,3,4);
    $large_text_arr = array(10,11);
    $judging_number_label_arr = array(3,4,9,0);
    $entry_number_label_arr = array(1,2,5,6,7,8,10,11);

    if (in_array($_SESSION['prefsEntryForm'],$anon_label_arr)) { $anon = TRUE; $standard = FALSE; }
    if (in_array($_SESSION['prefsEntryForm'],$standard_label_arr)) { $standard = TRUE; $anon = FALSE; }
    if (in_array($_SESSION['prefsEntryForm'],$barcode_qr_arr)) $barcode_qr = TRUE;
    if (in_array($_SESSION['prefsEntryForm'],$large_label_arr)) $large_entry_num = TRUE;
    if (in_array($_SESSION['prefsEntryForm'],$large_text_arr)) $large_text = TRUE;
    if (in_array($_SESSION['prefsEntryForm'],$judging_number_label_arr)) { $judging_number_disp = TRUE; $entry_number_disp = FALSE; }
    if (in_array($_SESSION['prefsEntryForm'],$entry_number_label_arr)) { $judging_number_disp = FALSE; $entry_number_disp = TRUE; }
    
    // Set up vars
    if (isset($_SESSION['jPrefsBottleNum'])) $bottle_label_count = $_SESSION['jPrefsBottleNum'];
    else $bottle_label_count = 3; // number of bottles required
    $bottle_label_columns = 3;  // number of columns
    $bottle_label_columns_bs = 12/$bottle_label_columns; // how many Bootstrap columns to show based upon 12 column grid
    $bottle_label_endRow = 0;
    $bottle_label_hloopRow1 = 0; // first row
    $bottle_label_colCount = 0;
    $bottle_label_colCountTotal = $totalRows_log * $bottle_label_count;

    if ($barcode_qr) {
      $bottle_label_colCountLimit = 9;
      $bottle_label_height = 290;
    }
    
    else {
      $bottle_label_colCountLimit = 12;
      $bottle_label_height = 200;
    }
   
    $page_info0 = sprintf("<strong>%s</strong> %s", strtoupper($bottle_labels_001), $bottle_labels_008);

    if ($brewerCountry == "United States") $phone = format_phone_us($brewerPhone1);
    else $phone = $brewerPhone1;

    // Define Bootstrap Row
    $page_info1 .= "<div class=\"row\">";

    do {

      if ((($id == "default") && (in_array($row_log['id'], $entry_arr))) || (($id != "default") && ($row_log['id'] == $id))) {

        for ($i=1; $i<=$bottle_label_count; $i++) {

          if ($judging_number_disp) $barcode = sprintf("%06s",$row_log['brewJudgingNumber']);
          if ($entry_number_disp) $barcode = sprintf("%06s",$row_log['id']);

            if ($barcode_qr) {
              
              // Generate Barcode
              $barcode_link = "https://admin.brewingcompetitions.com/includes/barcode/html/image.php?filetype=PNG&dpi=300&scale=1&rotation=0&font_family=Arial.ttf&font_size=8&text=".$barcode."&thickness=20&code=BCGcode39";

              // Generate QR Code
              require_once (CLASSES.'qr_code/qrClass.php');
              $qr = new qRClas();

              $qrcode_url = $base_url."qr.php?id=".$row_log['id'];
              $qrcode_url = urlencode($qrcode_url);

              $qr->qRCreate($qrcode_url,"75x75","UTF-8");
              $qrcode_link = $qr->url;
            
            }

            $entry_name = html_entity_decode($row_log['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
            $entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

            if (($bottle_label_endRow == 0) && ($bottle_label_hloopRow1++ != 0)) $page_info1 .= "<div class=\"row\">";

            // Layout Column div
            $page_info1 .= "<div class=\"col-xs-".$bottle_label_columns_bs." label-border\">";
            
            $page_info1 .= "<div class=\"label-inner\">";

            $page_info1 .= "<div style=\"margin: 0 0 10px 0;\" class=\"text-center label-title\"><strong>".$_SESSION['contestName']."</strong></div>";
            if ($large_entry_num) $page_info1 .= "<div style=\"margin: 10px 0 10px 0; padding: 0;\" class=\"text-center label-entry-num\">".$barcode."</div>";
            elseif ($large_text) $page_info1 .= "<div style=\"margin: 10px 0 10px 0; padding: 0;\" class=\"text-center label-category-name\">".$barcode."</div>";
            else $page_info1 .= "<div class=\"text-center\" style=\"margin: 0 0 10px 0; padding: 5px 0 5px 0; font-size: 1.2em; border: 1px solid #dedede; border-radius: 5px;\"><strong>".$label_entry_number.":</strong> ".$barcode."</span></div>";

            if ($large_text) { 
              
              $page_info1 .= "<div class=\"text-center label-category-name\" style=\"margin: 0 0 20px 0; padding: 0 5px 0 5px;\" >";
              
              if ($_SESSION['prefsStyleSet'] == "BA") {
                $page_info1 .= "<div>".$row_log['brewStyle']."</div>";
              }
              
              elseif ($_SESSION['prefsStyleSet'] == "AABC") {
                $page_info1 .= "<div>".$label_category.": ".ltrim($row_log['brewCategory'],"0").".".ltrim($row_log['brewSubCategory'],"0")."</div>";
                $page_info1 .= "<div style=\"font-size: .7em;\">".$row_log['brewStyle']."</div>";
              } 
              
              else {
                $page_info1 .= "<div>".$row_log['brewCategory'].$row_log['brewSubCategory']."</div>";
                $page_info1 .= "<div style=\"font-size: .7em;\">".$row_log['brewStyle']."</div>";
              }

              $page_info1 .= "</div>";

            }
            
            if (!$anon) $page_info1 .= "<div style=\"margin: 0 0 5px 0;\"><strong>".$label_entry_name.":</strong> ".truncate($entry_name,30,"&hellip;")."</div>";

            if (!$large_text) { 

              $page_info1 .= "<div style=\"margin: 0 0 5px 0;\">";
              
              if ($_SESSION['prefsStyleSet'] == "BA") $page_info1 .= "<strong>Cat:</strong> ".$row_log['brewStyle'];
              elseif ($_SESSION['prefsStyleSet'] == "AABC")  $page_info1 .= "<strong>".$label_category.":</strong> ".ltrim($row_log['brewCategory'],"0").".".ltrim($row_log['brewSubCategory'],"0")." ".$row_log['brewStyle']."</span>";
              else $page_info1 .= "<strong>".$label_category.":</strong> ".$row_log['brewCategory'].$row_log['brewSubCategory']." ".$row_log['brewStyle']."</span>";

              $page_info1 .= "</div>";

            }

            if ($anon) {
              
              $brewInfo = "";
              $brewMeadCider = "";

              if ((!empty($row_log['brewMead1'])) || (!empty($row_log['brewMead2'])) || (!empty($row_log['brewMead3']))) {
                if (!empty($row_log['brewMead1'])) $brewMeadCider .= $row_log['brewMead1']."&nbsp;&nbsp;";
                if (!empty($row_log['brewMead2'])) $brewMeadCider .= $row_log['brewMead2']."&nbsp;&nbsp;";
                if (!empty($row_log['brewMead3'])) $brewMeadCider .= $row_log['brewMead3'];
              }
              
              if (!empty($row_log['brewInfo'])) {
                $brewInfo = $row_log['brewInfo'];
                if (strpos($row_log['brewInfo'],"^") !== FALSE) $brewInfo = str_replace("^", "&nbsp;", $brewInfo);
                if (empty($brewMeadCider)) $brewInfo = truncate($brewInfo,200,"&hellip;");
                else $brewInfo = truncate($brewInfo,150,"&hellip;");
              }

              if (!empty($brewInfo)) {
                $page_info1 .= "<div style=\"margin: 0 0 5px 0;\">";
                if ((($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2025")) && ($row_log['brewCategorySort'] == "02") && ($row_log['brewSubCategory'] == "A")) $page_info1 .= "<strong>".$label_regional_variation.":</strong> <span class=\"break-long\">".$brewInfo."</span>";
                else $page_info1 .= "<strong>".$label_required_info.":</strong> <span class=\"break-long\">".$brewInfo."</span>";
                $page_info1 .= "</div>";
              }

              if (!empty($brewMeadCider)) {
                $page_info1 .= "<div style=\"margin: 0 0 5px 0;\">".$brewMeadCider."</div>";
              }
            
            }

            if ($standard) {
              
              $page_info1 .= "<div>";
              
              if ($_SESSION['prefsProEdition'] == 1) {
                $page_info1 .= $row_brewer['brewerBreweryName']."<br>";
                $page_info1 .= $label_contact.": ".$brewerFirstName." ".$brewerLastName."<br>";
              }
              
              else $page_info1 .= $brewerFirstName." ".$brewerLastName."<br>";
              $page_info1 .= $brewerEmail."<br>";
              $page_info1 .= $phone;
              $page_info1 .= "</div>";
            
            }

            if ($barcode_qr) {
              $page_info1 .= "<div align=\"center\" style=\"margin: 15px 0 15px 0;\">";
              $page_info1 .= "<img src=\"".$barcode_link."\"> ";       
              $page_info1 .= "&nbsp;&nbsp;<img src=\"".$qrcode_link."\">";
              $page_info1 .= "</div>";
            }
            
            // if ((!$anon) && ($barcode_qr)) $page_info1 .= "<div align=\"center\" class=\"box\">".$bottle_labels_006."</div>";

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
    <link rel="stylesheet" href="<?php echo $css_url."templates.min.css"; ?>">
    <style>

  <?php if (($large_entry_num) || ($large_text)) { ?>

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

  <?php } elseif ($barcode_qr) { ?>
    
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