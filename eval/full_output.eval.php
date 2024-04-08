<?php

/*
// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
*/
 
    if ($tb != "default") {
        echo "<style>body {font-size:13px;}.descriptor {font-size:.85em;}.footer-descriptor{font-size:.85em;}</style>";
    }
?>
<div class="row">
	<!-- Main Body Left Column -->
	<div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <p><strong><?php echo $label_descriptor_defs; ?></strong></p>
        <?php foreach ($descriptors as $key => $value) { ?>
           <p class="descriptor"><span class="fa <?php if (strpos($row_eval['evalDescriptors'],$key) !== false) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span> <strong><?php echo $key; ?></strong> &ndash; <?php echo $value; ?></p>
        <?php } ?>
    </div><!-- /col (overall left column) -->
    <!-- Main Body Right Column -->
    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <!-- Aroma -->
        <div class="bcoem-scoresheet-element">
        <p><strong><?php echo $label_aroma; ?><span class="pull-right"><?php echo $row_eval['evalAromaScore']; ?> / <?php echo $aroma_points; ?></span></strong></p>
        <p class="descriptor"><?php echo $row_eval['evalAromaComments']; ?></p>
        </div>
        <!-- Appearance -->
        <div class="bcoem-scoresheet-element">
        <p><strong><?php echo $label_appearance; ?><span class="pull-right"><?php echo $row_eval['evalAppearanceScore']; ?> / <?php echo $appearance_points; ?></span></strong></p>
        <p class="descriptor"><?php echo $row_eval['evalAppearanceComments']; ?></p>
        </div>
        <!-- Flavor -->
        <div class="bcoem-scoresheet-element">
        <p><strong><?php echo $label_flavor; ?><span class="pull-right"><?php echo $row_eval['evalFlavorScore']; ?> / <?php echo $flavor_points; ?></span></strong></p>
        <p class="descriptor"><?php echo $row_eval['evalFlavorComments']; ?></p>
        </div>
        <!-- Mouthfeel -->
        <?php if ($beer) { ?>
        <div class="bcoem-scoresheet-element">
        <p><strong><?php echo $label_mouthfeel; ?><span class="pull-right"><?php echo $row_eval['evalMouthfeelScore']; ?> / <?php echo $mouthfeel_points; ?></span></strong></p>
        <p class="descriptor"><?php echo $row_eval['evalMouthfeelComments']; ?></p>  
        </div>
        <?php } ?>
        <!-- Overall Impression -->
        <div class="bcoem-scoresheet-element">
        <p><strong><?php echo $label_overall_impression; ?><span class="pull-right"><?php echo $row_eval['evalOverallScore']; ?> / <?php echo $overall_points; ?></span></strong></p>
        <p class="descriptor"><?php echo $row_eval['evalOverallComments']; ?></p>
        </div>
        <!-- Total -->
        <p><strong><?php echo $label_total; ?><span class="pull-right"><?php echo $score; ?> / 50</span></strong></p>
    </div>
</div><!-- /row (main body content row) -->
<div>&nbsp;</div>
<!-- Footer -->
<div class="row">
	<div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
    	<div class="box footer-descriptor">
        	<div class="row <?php if ($score >= 45) echo "strong-text"; ?>">
            	<div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                <?php echo $label_outstanding; ?>
                </div>
                <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                (45-50)
                </div>
                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <?php echo $descr_outstanding; ?>
                </div>
            </div>
            <div class="row <?php if (($score >= 38) && ($score <= 44)) echo "strong-text"; ?>">
            	<div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                <?php echo $label_excellent; ?>
                </div>
                <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                (38-44)
                </div>
                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <?php echo $descr_excellent; ?>
                </div>
            </div>
            <div class="row <?php if (($score >= 30) && ($score <= 37)) echo "strong-text"; ?>">
            	<div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                <?php echo $label_very_good; ?>
                </div>
                <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                (30-37)
                </div>
                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <?php echo $descr_very_good; ?>
                </div>
            </div>
            <div class="row <?php if (($score >= 21) && ($score <= 29)) echo "strong-text"; ?>">
            	<div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                <?php echo $label_good; ?>
                </div>
                <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                (21-29)
                </div>
                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <?php echo $descr_good; ?>
                </div>
            </div>
            <div class="row <?php if (($score >= 14) && ($score <= 20)) echo "strong-text"; ?>">
            	<div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                <?php echo $label_fair; ?>
                </div>
                <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                (14-20)
                </div>
                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <?php echo $descr_fair; ?>
                </div>
            </div>
            <div class="row <?php if (($score >= 0) && ($score <= 13)) echo "bg-primary"; ?>">
            	<div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                <?php echo $label_problematic; ?>
                </div>
                <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                (00-13)
                </div>
                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <?php echo $descr_problematic; ?>
                </div>
            </div>
        </div><!-- ./box -->
    </div>

    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="box footer-descriptor">
        	<p class="text-center"><strong><?php echo $label_style_accuracy; ?></strong></p>
            <div class="row">
            	<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <span class="pull-right"><strong><?php echo $label_classic_example; ?></strong></span>
                </div>
                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 5) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 4) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 3) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 2) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>
                </div>
                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <span class="pull-left"><strong><?php echo $label_not_style; ?></strong></span>
                </div>
            </div>
            <p class="text-center"><strong><?php echo $label_tech_merit; ?></strong></p>
            <div class="row">
            	<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <span class="pull-right"><strong><?php echo $label_flawless; ?></strong></span>
                </div>
                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                <span class="fa <?php if ($row_eval['evalTechMerit'] == 5) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalTechMerit'] == 4) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalTechMerit'] == 3) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalTechMerit'] == 2) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalTechMerit'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>
                </div>
                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <span class="pull-left"><strong><?php echo $label_significant_flaws; ?></strong></span>
                </div>
            </div>
            <p class="text-center"><strong><?php echo $label_intangibles; ?></strong></p>
            <div class="row">
            	<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <span class="pull-right"><strong><?php echo $label_wonderful; ?></strong></span>
                </div>
                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                <span class="fa <?php if ($row_eval['evalIntangibles'] == 5) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalIntangibles'] == 4) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalIntangibles'] == 3) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalIntangibles'] == 2) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalIntangibles'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>
                </div>
                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <span class="pull-left"><strong><?php echo $label_lifeless; ?></strong></span>
                </div>
            </div>
        </div><!-- ./box -->
    </div>

</div>
<div>&nbsp;</div>
<p><small><em><?php echo sprintf("%s %s. &copy;%s Beer Judge Certification Program.",$evaluation_info_070,$scoresheet_type,date('Y')); ?></em></small></p>
<?php if (!empty($row_eval['evalFinalScore'])) { ?>
<p><small><em>** <?php echo $evaluation_info_069; ?></em></small></p>
<?php } ?>
<div style="page-break-after: always;"></div>