<?php if ($action == "html") { ?>
<p class="lead">If you want to upload mutiple images at once, use the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload">enhanced image upload function</a>.</p>
<form method="post" action="<?php echo $base_url; ?>handle.php?action=html" ENCTYPE="multipart/form-data">
<div class="fileinput fileinput-new" data-provides="fileinput">
    <span class="btn btn-default btn-file"><span>Choose Image File</span><input type="file" name="file" /></span>
    <span class="fileinput-filename text-success"></span> <span class="fileinput-new text-danger">No file chosen...</span>
</div>
	<p><input type="submit" class="btn btn-primary" value="Upload Logo Image"></p>
</form>
<?php } else { ?>
<p class="lead">The <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload&amp;action=html">single image upload function</a> is also available as an alternative to this multiple upload function.</p>
<form id="upload-widget" method="post" action="<?php echo $base_url; ?>handle.php" class="dropzone">
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
$upload_dir = (USER_IMAGES);

if (!is_dir_empty($upload_dir)) {

	// List Files in the directory
	$handle = opendir($upload_dir);
	$filelist = "<h2>Files in the Directory</h2>";
	$filelist .= "<table class=\"table table-bordered table-responsive table-striped\" id=\"sortable\">\n";
	$filelist .= "<thead>\n";
	$filelist .= "<tr>\n";
	$filelist .= "<th>File Name</th>\n";
	$filelist .= "<th>Date/Time Uploaded</th>\n";
	$filelist .= "<th>Actions</th>\n";
	$filelist .= "</thead>\n";
	$filelist .= "<tbody>\n";
	while ($file = readdir($handle)) {
	   if(!is_dir($file) && !is_link($file)) {
			$filelist .= "<tr>\n";
			$filelist .= "<td><a class=\"user_images hide-loader\" rel=\"group1\" href=\"".$base_url."user_images/$file\" title=\"".$file."\" >".$file."</a></td>\n";
			$filelist .= "<td>".date("l, F j, Y H:i", filemtime($upload_dir.$file))."</td>\n";
			$filelist .= "<td><a class=\"hide-loader\" href=\"".$base_url."includes/process.inc.php?action=delete&amp;go=image&amp;filter=".$file."&amp;view=".$action."\" data-confirm=\"Are you sure? This will remove the image named ".$file." from the server.\"><span class=\"fa fa-lg fa-trash\"></span></a></td>\n";
			$filelist .= "</tr>\n";
	   }
	}
	$filelist .= "</tbody>\n";
	$filelist .= "</table>\n";
	echo $filelist;

}

?>

