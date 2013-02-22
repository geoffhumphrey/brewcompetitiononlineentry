<?php 
/**
 * Module:      report_tempate.php 
 * Description: Template for custom reports.
 * 
 */

// ---- REQUIRED Includes if using default CSS s footer and constants ----
require('../paths.php');
error_reporting(E_ALL ^ E_NOTICE); // comment out after debugging
// error_reporting(0); // uncomment when live
require(INCLUDES.'authentication_nav.inc.php'); session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php'); 
require(INCLUDES.'constants.inc.php'); // if used, must also use functions.inc.php
require(DB.'common.db.php');

// ---- Optional Includes ----
// require(INCLUDES.'headers.inc.php');
// require(DB.'admin_common.db.php');
// require(DB.'archive.db.php');
// require(DB.'brewer.db.php');
// require(DB.'contacts.db.php');
// require(DB.'dropoff.db.php');
// require(DB.'entries.db.php');
// require(DB.'judging_locations.db.php');
// require(DB.'sponsors.db.php');
// require(DB.'stewarding.db.php');
// require(DB.'sponsors.db.php');
// require(DB.'winners.db.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_contest_info['contestName']; ?> organized by <?php echo $row_contest_info['contestHost']; ?></title>
<link href="<?php echo $base_url; ?>/css/print.css" rel="stylesheet" type="text/css" />
<!-- jquery plugin - required for use with DataTables, FancyBox, DatePicker, TimePicker etc. -->
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.js"></script>

<!-- Required for jquery DatePicker -->
<!--
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.ui.tabs.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.ui.position.min.js"></script>
-->

<!-- Required for jquery DatePicker -->
<!--
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/jquery.ui.timepicker.css?v=0.3.0" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.ui.timepicker.js?v=0.3.0"></script>
-->

<!-- Required for Fancybox -->
<!--
<link rel="stylesheet" href="<?php echo $base_url; ?>/js_includes/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/fancybox/jquery.fancybox.pack.js"></script>
// To call a fancybox modal window, add id="modal_window_link" to the <a> tag
// For example: <a href="example.html" id="modal_window_link">Click for Popup</a>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#modal_window_link").fancybox({
				'width'				: '85%',
				'maxHeight'			: '85%',
				'fitToView'			: false,
				'scrolling'         : 'auto',
				'openEffect'		: 'elastic',
				'closeEffect'		: 'elastic',
				'openEasing'     	: 'easeOutBack',
				'closeEasing'   	: 'easeInBack',
				'openSpeed'         : 'normal',
				'closeSpeed'        : 'normal',
				'type'				: 'iframe',
				'helpers' 			: {	title : { type : 'inside' } },
			});

		});
	</script>
-->
<!-- For use with the DataTables jquery plugin -->
<!--
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/sorting.css" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript">
// The following is for demonstration purposes only. 
// Complete documentation and usage at http://www.datatables.net
	$(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[2,'asc']],
			"aoColumns": [
				null,
				null
				]
		} );
	} );
</script>
-->
</head>
<body>
<!-- Automatically print upon load...
<body>
<script type="text/javascript">
function selfPrint(){
    self.focus();
    self.print();
}
setTimeout('selfPrint()',200);
</script>
-->
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner"><h1>Header</h1></div>
	</div><!-- end header -->
    <!-- BEGIN content -->
    
    <!-- DataTables Table Format -->
    <!-- 
    <table class="dataTable">
    <thead>
    	<tr>
        	<th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    	<tr>
        	<td class="data"></td>
            <td class="data"></td>   
        </tr>
    </tbody>
    </table>
    -->
    
    <!-- END content -->
    </div><!-- end content-inner -->
</div><!-- end content -->
<div id="footer">
	<div id="footer-inner">Printed <?php echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], time(), $row_prefs['prefsDateFormat'], $row_prefs['prefsTimeFormat'], "long", "date-time"); ?>.</div>
</div>
</body>
</html>