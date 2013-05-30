<?php 
/**
 * Module:      footer.sec.php 
 * Description: This module houses the footer displayed on all pages. 
 * 
 */
 
if (NHC) {
?>
<a href="http://www.brewcompetition.com" target="_blank">BCOE&amp;M</a> - NHC Edition*  &copy;<?php echo date('Y'); ?>
<?php }  else { ?>
<a href="http://www.brewcompetition.com" target="_blank">BCOE&amp;M</a> v<?php echo $version; ?> &copy;<?php  echo "2009-".date('Y'); ?> by <a href="http://www.zkdigital.com" target="_blank">zkdigital.com</a>.
<?php } ?>