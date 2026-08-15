<script>
  var base_url = "<?php echo $base_url; ?>";
  var ajax_url = "<?php echo $ajax_url; ?>";
  var section = "<?php echo $section; ?>";
  var go = "<?php echo $go; ?>";
</script>

<?php 
$import_button_text = "Import Score Data";
if (($section == "admin") && (($go == "default") || ($go == "judging_tables"))) { 
  $import_scores_display = "<a href=\"#\" data-toggle=\"modal\" data-target=\"#eval-import-modal\">".$import_button_text."</a>";
  $import_scores_display .= "<p id=\"import-scores-status-dashboard\"><i id=\"import-scores-status-icon\" class=\"\"></i> <span id=\"import-scores-status\"></span></p>";
  $import_scores_display .= "<p id=\"import-status-discrepency-dashboard\"><i id=\"import-status-discrepency-icon\" class=\"\"></i> <span id=\"import-status-discrepency\"></span></p>";
} else { 

?>
<div class="bcoem-admin-element hidden-print">
  <?php if (($section == "admin") && ($go == "evaluation")) { ?>
    <div class="btn-group" role="group">
        <a role="button" class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores"><span class="fa fa-chevron-circle-left"></span> Manage Scores</a>
        <button class="btn btn-default" data-toggle="modal" data-target="#eval-import-modal"><i class="fa fa-file-import"></i> Import Score Data</button>
        <a role="button" href="#" data-toggle="popover" title="Importing Judges' Score Data" data-content="<p>Judge evaluation scores <strong>are not official</strong> until an Administrator imports <strong>matching consensus scores entered by two or more judges</strong> into the scores database. Access Admin Dashboard > Scoring > Manage Scores to view, edit, and/or delete official consensus scores." data-trigger="hover" data-placement="auto" data-html="true" data-container="body" class="btn btn-default"><i class="fa fa-question-circle"></i></a>
    </div>
  <?php } ?>
    <div class="row bcoem-admin-element">
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
        <p>Importing scores and/or places listed on <?php if (($section == "admin") && ($go == "evaluation")) echo "<strong>this screen</strong>"; else echo "the <a href=\"".$base_url."index.php?section=admin&amp;go=evaluation&amp;filter=default&amp;view=admin\">Admin > Scoring > <strong>Manage Entry Evaluations</strong> screen</a>"; ?> will <strong>NOT</strong> overwrite any scores and/or entry places already listed on <?php if (($section == "admin") && ($go == "judging_scores")) echo "<strong>this screen</strong>"; else echo "the <a href=\"".$base_url."index.php?section=admin&amp;go=judging_scores\">Admin > Scoring > <strong>Manage Scores</strong> screen</a>"; ?>.</p>
        <p>Therefore, it is suggested that all scores and places for applicable entries be finalized via <?php if (($section == "admin") && ($go == "evaluation")) echo "<strong>this screen</strong>"; else echo "the <a href=\"".$base_url."index.php?section=admin&amp;go=evaluation&amp;filter=default&amp;view=admin\">Admin > Scoring > <strong>Manage Entry Evaluations</strong> screen</a>"; ?> <strong>prior</strong> to importing.</p>
      </div>
      <div class="modal-footer">
        <button id="import-scores" type="button" class="btn btn-success" data-dismiss="modal">I Understand - Continue Import</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel Import</button>
      </div>
    </div>
  </div>
</div>