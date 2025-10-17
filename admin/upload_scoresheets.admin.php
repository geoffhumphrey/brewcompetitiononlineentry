<?php 

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 0))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

if ($action == "html") { 

?>
<p class="lead">If you want to upload mutiple files at once, use the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload_scoresheets">enhanced file upload function</a>.</p>
<?php } else { ?>
<p class="lead">The <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload_scoresheets&amp;action=html">single file upload function</a> is also available as an alternative to this multiple upload function.</p>
<?php } ?>
<?php if (!HOSTED) { ?>
<p class="lead"><small>If you are having issues with this browser-based file upload function, or wish to upload files that are larger than 15 MB, a fallback would be to use a File Transfer Protocol (FTP) application.</small></p>
<?php } ?>
<p>For entrants to be able to view their scoresheets, each PDF should:</p>
<ul style="margin-bottom: 30px;">
	<li>Contain all judges' scoresheets and other documentation (cover sheet, etc.) in <strong>a single file</strong>.</li>
	<?php if ($_SESSION['prefsDisplaySpecial'] == "J") { ?>
	<li>In accordance with your installation's Scoresheet Unique Identifier <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences">preference</a>, be named with a <strong>six (6) character <u>judging</u> number</strong> (e.g., 000012.pdf, 987654.pdf, 01-234.pdf, abc123.pdf. 123abc.pdf, etc.) that corresponds <strong>EXACTLY to the entry's judging number as stored in the system's database</strong>.</li>
	<?php } else { ?>
	<li>In accordance with your installation's Scoresheet Unique Identifier <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences">preference</a>, be named with the <strong><u>entry</u> number in six (6) digit format</strong> with leading zeroes (e.g., 000198.pdf, 000567.pdf, etc.).</li>
	<?php } ?>
	<li>Have a .pdf or .PDF extension. Please note that file names and extensions uploaded with this browser-based function will be converted to lower-case.<?php if (!HOSTED) { ?> If you choose to use an FTP program, make sure that all file names and extensions are lower case to ensure the application will recognize them.<?php } ?></li>
	<li>Be <strong><?php if (HOSTED) echo "10"; else echo "15"; ?> MB or less</strong> in size.</li>
</ul>
<?php if ($action == "html") { ?>
<form method="post" action="<?php echo $base_url; ?>handle.php?action=html_docs" ENCTYPE="multipart/form-data">
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo $_SESSION['user_session_token']; ?>">
<div class="fileinput fileinput-new" data-provides="fileinput">
    <span class="btn btn-default btn-file"><span>Choose PDF File</span><input type="file" name="file" /></span>
    <span class="fileinput-filename text-success"></span> <span class="fileinput-new text-danger">No file chosen...</span>
</div>
	<p><input type="submit" class="btn btn-primary" value="Upload PDF File"></p>
</form>
<?php } else { ?>
<form style="min-height: 300px;" id="upload-widget" method="post" action="<?php echo $base_url; ?>handle.php?action=docs" class="dropzone">
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo $_SESSION['user_session_token']; ?>">
<div class="fallback">
    <input name="file" type="file" multiple />
  </div>
</form>
<?php } ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : false,
			"iDisplayLength" :  <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'tp',
			"bStateSave" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				{ "asSorting": [  ] }
				]
			} );
		} );

		$("a.user_images").fancybox(
			{
			nextClick   : true,
			nextEffect  : 'elastic',
			prevEffect  : 'elastic',
			padding     : 20,
			helpers:  {
					title : {
						type : 'inside'
					},
					overlay : {
						showEarly : false
					}
				}
			}

		);
	</script>
<?php

$filelist_head = "";
$filelist_foot = "";
$filelist = "";
$filelist_heading = "";

$filelist_heading = "<h2>Files in the Directory</h2>";

if (!is_dir_empty(USER_DOCS)) {

	// List Files in the directory
	//$handle = opendir($upload_dir);
	$filelist_head = "<p><a class=\"btn btn-danger btn-sm hide-loader\" href=\"".$base_url."includes/process.inc.php?action=delete_scoresheets&amp;filter=".$action."\" data-confirm=\"Are you sure? This will delete all scoresheets listed below. This cannot be undone.\"><span class=\"fa fa-trash\"></span> Delete All Scoresheets</a></p><p>It is advised that you delete or archive scoresheets as you prepare for another competition iteration. Otherwise, your entrants may download incorrect PDFs from previous competition iterations, causing confusion.</p>";
	$filelist_head .= "<table class=\"table table-bordered table-responsive table-striped\" id=\"sortable\">\n";
	$filelist_head .= "<thead>\n";
	$filelist_head .= "<tr>\n";
	$filelist_head .= "<th>File Name</th>\n";
	$filelist_head .= "<th>Size</th>\n";
	$filelist_head .= "<th>Date</th>\n";
	$filelist_head .= "<th>Actions</th>\n";
	$filelist_head .= "</thead>\n";
	$filelist_head .= "<tbody>\n";

	$files = new FilesystemIterator(USER_DOCS);

	foreach($files as $file) {

		$mime = mime_content_type($file->getPathname());

		if (stripos($mime, "pdf") !== false) {

			$scoresheet_file_name = $file->getFilename();
			$random_num_str = random_generator(8,2);
			$random_file_name = $random_num_str.".pdf";
			$scoresheet_random_file_relative = "user_temp/".$random_file_name;
			$scoresheet_random_file = USER_TEMP.$random_file_name;
			$scoresheet_random_file_html = $base_url.$scoresheet_random_file_relative;
			$scoresheet_link = "";
			$scoresheet_link .= "<a class=\"hide-loader\" href=\"".$base_url."includes/output.inc.php?section=scoresheet";
			$scoresheet_link .= "&amp;scoresheetfilename=".urlencode(obfuscateURL($scoresheet_file_name,$_SESSION['encryption_key']));
			$scoresheet_link .= "&amp;randomfilename=".urlencode(obfuscateURL($random_file_name,$_SESSION['encryption_key']))."&amp;download=true";
			$scoresheet_link .= "\" data-download=\"true\">".$scoresheet_file_name."</a>";
			$scoresheet_file_size = number_format($file->getSize()/1000000,4);

			$filelist .= "<tr>\n";
			$filelist .= "<td>".$scoresheet_link."</td>\n";
			$filelist .= "<td>".$scoresheet_file_size." MB</td>";
			$filelist .= "<td>".getTimeZoneDateTime($_SESSION['prefsTimeZone'], filemtime($file), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time")."</td>\n";
			$filelist .= "<td><a class=\"hide-loader\" href=\"".$base_url."includes/process.inc.php?action=delete&amp;go=doc&amp;filter=".$scoresheet_file_name."&amp;view=".$action."\" data-confirm=\"Are you sure? This will remove the file named ".$scoresheet_file_name." from the server.\"><span class=\"fa fa-lg fa-trash\"></span></a></td>\n";
			$filelist .= "</tr>\n";

		}

	}

	$filelist_foot .= "</tbody>\n";
	$filelist_foot .= "</table>\n";

	if (!empty($filelist)) echo $filelist_heading.$filelist_head.$filelist.$filelist_foot;
	else {
		echo $filelist_heading;
		echo "<p>The directory does not contain any PDF files.</p>";
	}
}

?>

