<div id="header">	
	<div id="header-inner"><h1><?php echo $row_contest_info['contestName']; ?> Rules</h1></div>
</div>
<?php if ($action != "print") { ?>
<p><span class="icon"><img src="images/printer.png" align="absmiddle" /></span><a class="data" href="#" onClick="window.open('print.php?section=<?php echo $section; ?>&action=print','','height=600,width=800,toolbar=no,resizable=yes,scrollbars=yes'); return false;">Print This Page</a></p>
<?php } ?>
<?php if (($row_contest_info['contestLogo'] != "") && (file_exists('user_images/'.$row_contest_info['contestLogo']))) { // display competition's logo if name present in DB and in the correct folder on the server ?>
<img src="user_images/<?php echo $row_contest_info['contestLogo']; ?>" width="<?php echo $row_prefs['prefsCompLogoSize']; ?>" align="right" hspace="3" vspace="3" />
<?php } ?>
<?php echo $row_contest_info['contestRules']; ?>