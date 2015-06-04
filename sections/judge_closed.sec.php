<?php 
/**
 * Module:      judge_closed.sec.php 
 * Description: This module houses the information that will be displayded
 *              once judging dates have passed. 
 * 
 */
?>
<h2>Thanks To All Who Participated in the <?php echo $_SESSION['contestName']; ?></h2>
<p>There were <strong><?php echo get_entry_count('received'); ?></strong> entries judged and <strong><?php echo get_participant_count('default'); ?></strong> registered participants, judges, and stewards.</p>