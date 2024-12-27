<?php
/**
 * Module:      sponsors.pub.php
 * Description: This module displays sponsors information housed in the
 *              sponsors database table.
 *
 */

include (DB.'sponsors.db.php');

?>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 justify-content-center mt-4">

<?php 

	do { 

		if ($_SESSION['prefsSponsorLogos'] == "Y") {
			$sponsor_logo = "<img class=\"img-fluid rounded mx-auto mt-3 d-block\" src=\"";
			if (($row_sponsors['sponsorImage'] != "") && (file_exists(USER_IMAGES.$row_sponsors['sponsorImage']))) $sponsor_logo .= $base_url."user_images/".$row_sponsors['sponsorImage'];
			else $sponsor_logo .= $images_url."no_image.png";
			$sponsor_logo .= sprintf("\" border=\"0\" alt=\"".$row_sponsors['sponsorName']."\" title=\"".$row_sponsors['sponsorName']."\" />");
		}

?>
	<div class="col">
		<div class="card h-100 sponsor-card-bg">
			<div class="card-body">
				<header class="sponsor-header"><?php echo $row_sponsors['sponsorName']; ?></header>
				<?php echo $sponsor_logo; ?>
			</div>
			<div class="card-footer" style="border:none; background-color: inherit;">
				<p class="glance-card-text mt-2"><small><?php if ($row_sponsors['sponsorText'] != "") echo $row_sponsors['sponsorText']; ?></small></p>
				<?php if ($row_sponsors['sponsorURL'] != "") { ?>
					<div class="d-grid mb-1"><a href="<?php echo $row_sponsors['sponsorURL']; ?>" class="btn btn-secondary" target="_blank"><?php echo $label_visit; ?></a></div>
				<?php } else { ?>
					<div class="d-grid mb-1"><a href="<?php echo $row_sponsors['sponsorURL']; ?>" class="btn btn-outline-secondary disabled"><?php echo $label_no_website; ?></a></div>
				<?php } ?>
			</div>
			
		</div>
	</div>

<?php } while ($row_sponsors = mysqli_fetch_assoc($sponsors)); ?>

</div>