<?php 
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
        <p class="descriptor"><?php if (strlen($row_eval['evalAromaComments']) > 475) echo truncate($row_eval['evalAromaComments'],475,"..."); else echo $row_eval['evalAromaComments']; ?></p>
        </div>
        <!-- Appearance -->
        <div class="bcoem-scoresheet-element">
        <p><strong><?php echo $label_appearance; ?><span class="pull-right"><?php echo $row_eval['evalAppearanceScore']; ?> / <?php echo $appearance_points; ?></span></strong></p>
        <p class="descriptor"><?php if (strlen($row_eval['evalAppearanceComments']) > 475) echo truncate($row_eval['evalAppearanceComments'],475,"..."); else echo $row_eval['evalAppearanceComments']; ?></p>
        </div>
        <!-- Flavor -->
        <div class="bcoem-scoresheet-element">
        <p><strong><?php echo $label_flavor; ?><span class="pull-right"><?php echo $row_eval['evalFlavorScore']; ?> / <?php echo $flavor_points; ?></span></strong></p>
        <p class="descriptor"><?php if (strlen($row_eval['evalFlavorComments']) > 475) echo truncate($row_eval['evalFlavorComments'],475,"..."); else echo $row_eval['evalFlavorComments']; ?></p>
        </div>
        <!-- Mouthfeel -->
        <?php if ($beer) { ?>
        <div class="bcoem-scoresheet-element">
        <p><strong><?php echo $label_mouthfeel; ?><span class="pull-right"><?php echo $row_eval['evalMouthfeelScore']; ?> / <?php echo $mouthfeel_points; ?></span></strong></p>
        <p class="descriptor"><?php if (strlen($row_eval['evalMouthfeelComments']) > 475) echo truncate($row_eval['evalMouthfeelComments'],475,"..."); else echo $row_eval['evalMouthfeelComments']; ?></p>  
        </div>
        <?php } ?>
        <!-- Overall Impression -->
        <div class="bcoem-scoresheet-element">
        <p><strong><?php echo $label_overall_impression; ?><span class="pull-right"><?php echo $row_eval['evalOverallScore']; ?> / <?php echo $overall_points; ?></span></strong></p>
        <p class="descriptor"><?php if (strlen($row_eval['evalOverallComments']) > 800) echo truncate($row_eval['evalOverallComments'],800,"..."); else echo $row_eval['evalOverallComments']; ?></p>
        </div>
        <!-- Total -->
        <p><strong>Judge Total<span class="pull-right"><?php echo $score; ?> / 50</span></strong></p>
    </div>
</div><!-- /row (main body content row) -->
<div>&nbsp;</div>
<!-- Footer -->
<div class="row">
	<div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
    	<div class="box footer-descriptor">
        	<div class="row <?php if ($score >= 45) echo "strong-text"; ?>">
            	<div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                Outstanding
                </div>
                <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                (45-50)
                </div>
                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                World-class example of style.
                </div>
            </div>
            <div class="row <?php if (($score >= 38) && ($score <= 44)) echo "strong-text"; ?>">
            	<div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                Excellent
                </div>
                <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                (38-44)
                </div>
                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                Exempifies the style well, requires minor fine tuning.
                </div>
            </div>
            <div class="row <?php if (($score >= 30) && ($score <= 37)) echo "strong-text"; ?>">
            	<div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                V. Good
                </div>
                <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                (30-37)
                </div>
                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                Generally within style parameters, some minor flaws.
                </div>
            </div>
            <div class="row <?php if (($score >= 21) && ($score <= 29)) echo "strong-text"; ?>">
            	<div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                Good
                </div>
                <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                (21-29)
                </div>
                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                Misses the mark on style and/or minor flaws.
                </div>
            </div>
            <div class="row <?php if (($score >= 14) && ($score <= 20)) echo "strong-text"; ?>">
            	<div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap no-wrap">
                Fair
                </div>
                <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap no-wrap">
                (14-20)
                </div>
                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                Off flavors/aromas or major style deficiencies. Unpleasant.
                </div>
            </div>
            <div class="row <?php if (($score >= 0) && ($score <= 13)) echo "bg-primary"; ?>">
            	<div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                Problematic
                </div>
                <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
                (00-13)
                </div>
                <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
                Major off flavors and aromas dominate. Hard to drink.
                </div>
            </div>
        </div><!-- ./box -->
    </div>

    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="box footer-descriptor">
        	<p class="text-center"><strong>Stylistic Accuracy</strong></p>
            <div class="row">
            	<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <span class="pull-right"><strong>Classic Example</strong></span>
                </div>
                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 5) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 4) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 3) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 2) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>
                </div>
                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <span class="pull-left"><strong>Not to Style</strong></span>
                </div>
            </div>
            <p class="text-center"><strong>Technical Merit</strong></p>
            <div class="row">
            	<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <span class="pull-right"><strong>Flawless</strong></span>
                </div>
                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                <span class="fa <?php if ($row_eval['evalTechMerit'] == 5) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalTechMerit'] == 4) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalTechMerit'] == 3) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalTechMerit'] == 2) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalTechMerit'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>
                </div>
                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <span class="pull-left"><strong>Significant Flaws</strong></span>
                </div>
            </div>
            <p class="text-center"><strong>Intangibles</strong></p>
            <div class="row">
            	<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <span class="pull-right"><strong>Wonderful</strong></span>
                </div>
                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                <span class="fa <?php if ($row_eval['evalIntangibles'] == 5) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalIntangibles'] == 4) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalIntangibles'] == 3) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalIntangibles'] == 2) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa <?php if ($row_eval['evalIntangibles'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>
                </div>
                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <span class="pull-left"><strong>Lifeless</strong></span>
                </div>
            </div>
        </div><!-- ./box -->
    </div>

</div>
<div>&nbsp;</div>
<p><small><em>Based upon the BJCP <?php echo $scoresheet_type; ?> Scoresheet Copyright &copy;2017 Beer Judge Certification Program rev. 170612</em></small></p>
<?php if (!empty($row_eval['evalFinalScore'])) { ?>
<p><small><em>** At least two judges from the flight in which your submission was entered reached consensus on your final assigned score. It is not necessarily an average of the individual scores.</em></small></p>
<?php } ?>
<div style="page-break-after: always;"></div>