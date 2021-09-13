<script>
  var base_url = "<?php echo $base_url; ?>";
  var section = "<?php echo $section; ?>";
</script>
<script src="<?php echo $base_url; ?>js_includes/import_scores.min.js"></script>
<?php 
if ($totalRows_scores > 0) $import_button_text = "Import <span class=\"hidden-xs\">Any</span> Newly Recorded <span class=\"hidden-xs\">Judges' Consensus</span> Scores";
else $import_button_text = "Import Judges' Consensus Scores";
if (($section == "admin") && (($go == "default") || ($go == "judging_tables"))) { 
$import_scores_display = "<a href=\"#\" id=\"import-scores\">".$import_button_text."</a>";
$import_scores_display .= "<p><i id=\"import-scores-status-icon\" class=\"\"></i> <span id=\"import-scores-status\" class=\"small\"></span></p>";
} else { 
if ($section == "evaluation") { ?>
<div class="bcoem-admin-element hidden-print">
  <div class="btn-group hidden-print" role="group">
  <a class="btn btn-block btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores"><span class="fa fa-chevron-circle-left"></span> Manage Scores</a>
  </div>
</div>
<?php } ?>
<div class="bcoem-admin-element hidden-print">
		<div class="row">
      <div class="col col-sm-12">
        <p><button id="import-scores" class="btn btn-primary"><?php echo $import_button_text; ?></button> <a href="#" data-toggle="popover" title="Importing Judges' Consensus Scores" data-content="<p>Judge evaluation scores <strong>are not official</strong> until an Administrator imports <strong>matching consensus scores entered by two or more judges</strong> into the scores database. Access Admin Dashboard > Scoring > Manage Scores to view, edit, and/or delete official consensus scores." data-trigger="hover" data-placement="right" data-html="true"><i class="fa fa-lg fa-question-circle"></i></a></p>
      </div>
    </div>
    <div class="row">
      <div class="col col-sm-12">
        <div id="import-scores-status-div" class="alert alert-grey">
            <i id="import-scores-status-icon"></i> <span id="import-scores-status"></span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col col-sm-12">
        <div class="well small" id="score-import-status"></div>
      </div>
    </div>
</div>
<?php } ?>