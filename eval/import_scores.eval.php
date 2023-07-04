<script>
  var base_url = "<?php echo $base_url; ?>";
  var section = "<?php echo $section; ?>";
  var go = "<?php echo $go; ?>";
</script>
<script src="<?php echo $base_url; ?>js_includes/import_scores.min.js"></script>
<?php 
if ($totalRows_scores > 0) $import_button_text = "Import <span class=\"hidden-xs\">Any</span> Newly Recorded <span class=\"hidden-xs\">Judges' Consensus</span> Scores";
else $import_button_text = "Import Judges' Consensus Scores";
if (($section == "admin") && (($go == "default") || ($go == "judging_tables"))) { 

  $import_scores_display = "<a href=\"#\" data-toggle=\"modal\" data-target=\"#eval-import-modal\">".$import_button_text."</a>";
  $import_scores_display .= "<p id=\"import-scores-status-dashboard\"><i id=\"import-scores-status-icon\" class=\"\"></i> <span id=\"import-scores-status\"></span></p>";
  $import_scores_display .= "<p id=\"import-status-discrepency-dashboard\"><i id=\"import-status-discrepency-icon\" class=\"\"></i> <span id=\"import-status-discrepency\"></span></p>";


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
        <p><button class="btn btn-primary" data-toggle="modal" data-target="#eval-import-modal"><?php echo $import_button_text; ?></button> <a href="#" data-toggle="popover" title="Importing Judges' Consensus Scores" data-content="<p>Judge evaluation scores <strong>are not official</strong> until an Administrator imports <strong>matching consensus scores entered by two or more judges</strong> into the scores database. Access Admin Dashboard > Scoring > Manage Scores to view, edit, and/or delete official consensus scores." data-trigger="hover" data-placement="right" data-html="true"><i class="fa fa-lg fa-question-circle"></i></a></p>
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
<div class="modal fade" id="eval-import-modal" tabindex="-1" role="dialog" aria-labelledby="eval-import-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="eval-import-modal-label"><?php echo $label_please_note; ?></h4>
      </div>
      <div class="modal-body">
        <p>Importing scores and/or places listed on <?php if ($section == "evaluation") echo "<strong>this screen</strong>"; else echo "the <a href=\"".$base_url."index.php?section=evaluation&amp;go=default&amp;filter=default&amp;view=admin\">Admin > Scoring > <strong>Manage Entry Evaluations</strong> screen</a>"; ?> will <strong>NOT</strong> overwrite any scores and/or entry places already listed on <?php if (($section == "admin") && ($go == "judging_scores")) echo "<strong>this screen</strong>"; else echo "the <a href=\"".$base_url."index.php?section=admin&amp;go=judging_scores\">Admin > Scoring > <strong>Manage Scores</strong> screen</a>"; ?>.</p>
        <p>Therefore, it is suggested that all scores and places for applicable entries be finalized via <?php if ($section == "evaluation") echo "<strong>this screen</strong>"; else echo "the <a href=\"".$base_url."index.php?section=evaluation&amp;go=default&amp;filter=default&amp;view=admin\">Admin > Scoring > <strong>Manage Entry Evaluations</strong> screen</a>"; ?> <strong>prior</strong> to importing.</p>
      </div>
      <div class="modal-footer">
        <button id="import-scores" type="button" class="btn btn-success" data-dismiss="modal">I Understand - Continue Import</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel Import</button>
      </div>
    </div>
  </div>
</div>