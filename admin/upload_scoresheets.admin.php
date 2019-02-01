<?php if ($action == "html") { ?>
<p class="lead">If you want to upload mutiple files at once, use the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload_scoresheets">enhanced file upload function</a>.</p>
<?php if (!HOSTED) { ?>
<p class="lead"><small>If you are having issues with this browser-based file upload function, a fallback would be to use a File Transfer Protocol (FTP) application.</small></p>
<?php } ?>
<p class="lead">For entrants to be able to view their scoresheets, each PDF should:</p>
	<ul>
    	<li>Contain all judge scoresheets and other documentation (cover sheet, etc.) in a single file.</li>
        <li>Depending on the setting in website preferences:
            <ul>
                <li>Be named with a <strong>six (6) character judging number</strong> (e.g., 000012.pdf, 987654.pdf, 01-012.pdf, abc123.pdf. 123abc.pdf, etc.) that corresponds EXACTLY to the entry's judging number as stored in the system's database.</li>
	        </ul>
	        <ul class="list-unstyled">
		        <li style="padding-left: 40px;"><strong>OR</strong></li>
	        </ul>
	        <ul>
                <li>Be named with the <strong>entry number in six (6) digit format</strong> with leading zeroes (e.g., 000198.pdf, 000567.pdf, etc.).</li>
            </ul>
        </li>
        <li>Have a .pdf or .PDF extension.</li>
    </ul>
<form method="post" action="<?php echo $base_url; ?>handle.php?action=html_docs" ENCTYPE="multipart/form-data">
<div class="fileinput fileinput-new" data-provides="fileinput">
    <span class="btn btn-default btn-file"><span>Choose PDF File</span><input type="file" name="file" /></span>
    <span class="fileinput-filename text-success"></span> <span class="fileinput-new text-danger">No file chosen...</span>
</div>
	<p><input type="submit" class="btn btn-primary" value="Upload PDF File"></p>
</form>
<?php } else { ?>
<p class="lead">The <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload_scoresheets&amp;action=html">single file upload function</a> is also available as an alternative to this multiple upload function.</p>
<?php if (!HOSTED) { ?>
<p class="lead"><small>If you are having issues with this browser-based file upload function, a fallback would be to use a File Transfer Protocol (FTP) application.</small></p>
<?php } ?>
<p class="lead">For entrants to be able to view their scoresheets, each PDF should:</p>
	<ul>
    	<li>Contain all judge scoresheets and other documentation (cover sheet, etc.) in a single file.</li>
        <li>Depending on the setting in website preferences:
            <ul>
                <li>Be named with a <strong>six (6) character judging number</strong> (e.g., 000012.pdf, 987654.pdf, 01-012.pdf, abc123.pdf. 123abc.pdf, etc.) that corresponds EXACTLY to the entry's judging number as stored in the system's database.</li>
	        </ul>
	        <ul class="list-unstyled">
		        <li style="padding-left: 40px;"><strong>OR</strong></li>
	        </ul>
	        <ul>
                <li>Be named with the <strong>entry number in six (6) digit format</strong> with leading zeroes (e.g., 000198.pdf, 000567.pdf, etc.).</li>
            </ul>
        </li>
        <li>Have a .pdf or .PDF extension.</li>
    </ul>
<p>Please note that file names and extensions uploaded with this browser-based function will be converted to lower-case.</p>
<form id="upload-widget" method="post" action="<?php echo $base_url; ?>handle.php?action=docs" class="dropzone">
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
	$filelist_head = "<p><a class=\"btn btn-danger btn-sm\" href=\"".$base_url."includes/process.inc.php?action=delete_scoresheets&amp;filter=".$action."\" data-confirm=\"Are you sure? This will delete all scoresheets listed below. This cannot be undone.\"><span class=\"fa fa-trash\"></span> Delete All Scoresheets</a></p><p>It is advised that you delete or archive scoresheets as you prepare for another competition iteration. Otherwise, your entrants may download incorrect PDFs from previous competition iterations, causing confusion.</p>";
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
			$scoresheet_link .= "<a class=\"hide-loader\" href=\"".$base_url."output/scoresheets.output.php?";
			$scoresheet_link .= "scoresheetfilename=".urlencode(obfuscateURL($scoresheet_file_name,$encryption_key));
			$scoresheet_link .= "&amp;randomfilename=".urlencode(obfuscateURL($random_file_name,$encryption_key))."&amp;download=true";
			$scoresheet_link .= "\">".$scoresheet_file_name."</a>";
			$scoresheet_file_size = number_format($file->getSize()/1000000,2);

			$filelist .= "<tr>\n";
			$filelist .= "<td>".$scoresheet_link."</td>\n";
			$filelist .= "<td>".$scoresheet_file_size." MB</td>";
			$filelist .= "<td>".getTimeZoneDateTime($_SESSION['prefsTimeZone'], filemtime($file), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time")."</td>\n";
			$filelist .= "<td><a href=\"".$base_url."includes/process.inc.php?action=delete&amp;go=doc&amp;filter=".$scoresheet_file_name."&amp;view=".$action."\" data-confirm=\"Are you sure? This will remove the file named ".$scoresheet_file_name." from the server.\"><span class=\"fa fa-lg fa-trash\"></span></a></td>\n";
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

