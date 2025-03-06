<?php
/**
 * Module:      sponsors.pub.php
 * Description: This module displays sponsors information housed in the
 *              sponsors database table.
 *
 */

?>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 justify-content-center mt-4">

<?php 

	do { 

		$sponsor_logo = "";

		if ($_SESSION['prefsSponsorLogos'] == "Y") {
			$sponsor_logo = "<img style=\"max-height: 200px\" class=\"img-fluid rounded mx-auto mt-4 d-block\" src=\"";
			if (($row_sponsors['sponsorImage'] != "") && (file_exists(USER_IMAGES.$row_sponsors['sponsorImage']))) $sponsor_logo .= $base_url."user_images/".$row_sponsors['sponsorImage'];
			else $sponsor_logo .= $images_url."no_image.png";
			$sponsor_logo .= sprintf("\" border=\"0\" alt=\"".$row_sponsors['sponsorName']."\" title=\"".$row_sponsors['sponsorName']."\"  />");
		}

?>
	<div class="col">
		<div class="card h-100 sponsor-card-bg">
			<div class="card-body">
				<header class="sponsor-header"><?php echo $row_sponsors['sponsorName']; ?></header>
				<?php if ($_SESSION['prefsSponsorLogos'] == "Y") { ?>
				<div class="d-flex align-content-center flex-wrap">
					<?php echo $sponsor_logo; ?>
				</div>
				<?php } ?>
			</div>
			<div class="card-footer" style="border:none; background-color: inherit;">
				<p class="glance-card-text mt-2 lh-sm small align-middle"><small><?php if (!empty($row_sponsors['sponsorText'])) echo $row_sponsors['sponsorText']; ?></small></p>
				<?php if ($row_sponsors['sponsorURL'] != "") { ?>
					<div class="d-grid mb-2"><a href="<?php echo $row_sponsors['sponsorURL']; ?>" class="btn btn-dark" target="_blank"><?php echo $label_visit; ?></a></div>
				<?php } else { ?>
					<div class="d-grid mb-2"><a href="<?php echo $row_sponsors['sponsorURL']; ?>" class="btn btn-outline-dark disabled"><?php echo $label_no_website; ?></a></div>
				<?php } ?>
			</div>
			
		</div>
	</div>

<?php } while ($row_sponsors = mysqli_fetch_assoc($sponsors)); ?>

</div>