<!-- Aroma -->
<h5>Aroma<span class="pull-right"><?php echo $row_eval['evalAromaScore']; ?>/12</span></h5>
<p><?php echo $row_eval['evalAromaChecklist']; ?></p>
<?php if (!empty($row_eval['evalAromaChecklistDesc'])) { ?>
<p><?php echo $row_eval['evalAromaChecklistDesc']; ?></p>
<?php } ?>
<p><?php echo $row_eval['evalAromaComments']; ?></p>
<!-- Appearance -->
<h5>Appearance<span class="pull-right"><?php echo $row_eval['evalAppearanceScore']; ?>/3</span></h5>
<p><?php echo $row_eval['evalAppearanceChecklist']; ?></p>
<?php if (!empty($row_eval['evalAppearanceChecklistDesc'])) { ?>
<p><?php echo $row_eval['evalAppearanceChecklistDesc']; ?></p>
<?php } ?>
<p><?php echo $row_eval['evalAppearanceComments']; ?></p>
<!-- Flavor -->
<h5>Flavor<span class="pull-right"><?php echo $row_eval['evalFlavorScore']; ?>/20</span></h5>
<p><?php echo $row_eval['evalFlavorChecklist']; ?></p>
<?php if (!empty($row_eval['evalFlavorChecklistDesc'])) { ?>
<p><?php echo $row_eval['evalFlavorChecklistDesc']; ?></p>
<?php } ?>
<p><?php echo $row_eval['evalFlavorComments']; ?></p>
<!-- Mouthfeel -->
<h5>Mouthfeel<span class="pull-right"><?php echo $row_eval['evalMouthfeelScore']; ?>/5</span></h5>
<p><?php echo $row_eval['evalMouthfeelChecklist']; ?></p>
<?php if (!empty($row_eval['evalMouthfeelChecklistDesc'])) { ?>
<p><?php echo $row_eval['evalMouthfeelChecklistDesc']; ?></p>
<?php } ?>
<p><?php echo $row_eval['evalMouthfeelComments']; ?></p>
<!-- Overall Impression -->
<h5>Overall Impression<span class="pull-right"><?php echo $row_eval['evalOverallScore']; ?>/10</span></h5>
<p><?php echo $row_eval['evalOverallComments']; ?></p>
<div class="row" style="padding: 20px 0 0 0;">
	<div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
    	<div class="row">
            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <strong>Stylistic Accuracy</strong>
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
            Low <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 2) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 3) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 4) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 5) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span> High
            </div>
        </div>
        <div class="row">
            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <strong>Technical Merit</strong>
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
            Low <span class="fa <?php if ($row_eval['evalTechMerit'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalTechMerit'] == 2) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalTechMerit'] == 3) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalTechMerit'] == 4) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalTechMerit'] == 5) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span> High
            </div>
        </div>
        <div class="row">
            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <strong>Intangibles</strong>
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
            Low <span class="fa <?php if ($row_eval['evalIntangibles'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalIntangibles'] == 2) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalIntangibles'] == 3) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalIntangibles'] == 4) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalIntangibles'] == 5) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span> High
            </div>
        </div>
    </div>
    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
    	<strong>Drinkability:</strong> <?php echo $row_eval['evalDrinkability']; ?>   
    </div>  
</div><!-- ./box -->
<!-- Total -->
<h5>Judge Total<span class="pull-right"><?php echo $score; ?>/50</span></h5>
<!-- Scoring Guide -->
<div class="row footer-descriptor" style="padding: 20px 0 0 0;">
	<div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
    	<div class="row <?php if ($score >= 45) echo "strong-text box-plain"; ?>">
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
        <div class="row <?php if (($score >= 38) && ($score <= 44)) echo "strong-text box-plain"; ?>">
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
        <div class="row <?php if (($score >= 30) && ($score <= 37)) echo "strong-text box-plain"; ?>">
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
    </div>
    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
    	<div class="row <?php if (($score >= 21) && ($score <= 29)) echo "strong-text box-plain"; ?>">
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
        <div class="row <?php if (($score >= 14) && ($score <= 20)) echo "strong-text box-plain"; ?>">
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            Fair
            </div>
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            (14-20)
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
            Off flavors/aromas or major style deficiencies. Unpleasant.
            </div>
        </div>
        <div class="row <?php if (($score >= 0) && ($score <= 13)) echo "strong-text box-plain"; ?>">
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
    </div>
</div><!-- ./ scoring guide -->
<?php if (!empty($row_eval['evalFlaws'])) { ?>
<h5 style="padding-bottom: 10px">Flaws</h5>
<table class="table-bordered table-condensed descriptor" width="100%">
<thead>
	<tr>
        <th>Fault</th>
        <th>Aroma</th>
        <th>Flavor</th>
        <th>Mouth</th>
        <th>Fault</th>
        <th>Aroma</th>
        <th>Flavor</th>
        <th>Mouth</th>
        <th>Fault</th>
        <th>Aroma</th>
        <th>Flavor</th>
        <th>Mouth</th>
    </tr>
</thead>
<tbody>
	<tr>
    	<td>Acetaldehyde</td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Acetaldehyde Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Acetaldehyde Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%">&nbsp;</td>
        <td>Light-Struck</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Light-Struck Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Light-Struck Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%">&nbsp;</td>
        <td>Sour/Acidic</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Sour/Acidic Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Sour/Acidic Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Sour/Acidic Mouthfeel") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    </tr>
    <tr>
    	<td>Alcohol/Hot</td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Alcohol/Hot Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Acetaldehyde Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Alcohol/Hot Mouthfeel") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td>Medicinal</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Medicinal Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Medicinal Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Medicinal Mouthfeel") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td>Smoky</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Smoky Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Smoky Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%">&nbsp;</td>
    </tr>
    <tr>
    	<td>Astringent</td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Astringent Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Astringent Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Astringent Mouthfeel") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td>Metallic</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Metallic Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Metallic Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Metallic Mouthfeel") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td>Spicy</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Spicy Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Spicy Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Spicy Mouthfeel") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    </tr>
    <tr>
    	<td>Diacetyl</td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Diacetyl Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Diacetyl Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Diacetyl Mouthfeel") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td>Musty</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Musty Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Musty Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%">&nbsp;</td>
        <td>Sulfur</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Sulfur Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Sulfur Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%">&nbsp;</td>
    </tr>
    <tr>
    	<td>DMS</td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"DMS Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"DMS Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%">&nbsp;</td>
        <td>Oxidized</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Oxidized Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Oxidized Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%">&nbsp;</td>
        <td>Vegetal</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Vegetal Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Vegetal Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%">&nbsp;</td>
    </tr>
    <tr>
    	<td>Estery</td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Estery Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Estery Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%">&nbsp;</td>
        <td>Plastic</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Plastic Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Plastic Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%">&nbsp;</td>
        <td>Vinegary</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Vinegary Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Vinegary Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Vinegary Mouthfeel") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    </tr>
    <tr>
    	<td>Grassy</td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Grassy Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Grassy Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%">&nbsp;</td>
        <td>Solvent/Fusel</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Solvent/Fusel Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Solvent/Fusel Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Solvent/Fusel Mouthfeel") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td>Yeasty</td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Yeasty Aroma") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
    	<td width="8%"><?php if (strpos($row_eval['evalFlaws'],"Yeasty Flavor") !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%">&nbsp;</td>
    </tr>
</tbody>
</table>
<?php } ?>
<!-- Footer -->
<p style="padding-top: 30px;"><small><em>Based upon the BJCP Beer Scoresheet Copyright &copy;2012 Beer Judge Certification Program rev. 120213</em></small></p>
<?php if (!empty($row_eval['evalFinalScore'])) { ?>
<p><small><em>** At least two judges from the flight in which your submission was entered reached consensus on your final assigned score. It is not necessarily an average of the individual scores.</em></small></p>
<?php } ?>
<div style="page-break-after: always;"></div>