<script>
function import_scores() {
 
  $("#score-entered-status-default").show();
  $("#score-import-status").hide();
  var base_url = "<?php echo $base_url; ?>";
  var section = "<?php echo $section; ?>";
	
	jQuery.ajax({
        
        url: base_url+"eval/import_scores.ajax.php",

        success:function(data) {
        	var jsonData = JSON.parse(data);
            
            if (jsonData.status === "0") {
            	$("#import-scores-status").html("No evaluations have been recorded yet.");
              $("#import-scores-status-div").attr("class", "alert alert-purple");
              $("#import-scores-status-icon").attr("class", "fa fa-asterisk text-purple");
              $("#import-scores-status").attr("class", "text-purple small");
            }

            else if (jsonData.status === "1") {
            	
              var imported_count = jsonData.scores_imported_count;
              var not_imported_count = jsonData.scores_not_imported_count;
              var flagged_count = jsonData.flagged_count;

              let import_status = "";
              import_status += "<h4>Import Summary</h4>";
              
              if (imported_count == 1) import_status += "<p>Scores for <strong class=\"text-success\">" + imported_count + " entry</strong> has been imported and/or its place awarded has been updated and is now officially accepted.</strong></p>";
              if (imported_count > 1) import_status += "<p>Scores for <strong class=\"text-success\">" + imported_count + " entries</strong> have been imported and/or their places awarded have been updated and are now officially accepted.</strong></p>";
              
              import_status += "<p>";
              if (not_imported_count == 1) import_status += "The score for <strong class=\"text-danger\">" + not_imported_count + " entry</strong> was <em>not</em> imported because, at this time, <a href=\"" + base_url + "/index.php?section=evaluation&amp;go=default&amp;filter=default&amp;view=admin\">only a single judge evaluation exists in the database</a> for that entry.</strong>";
              import_status += "</p>";
              
              import_status += "<p>";
              if (not_imported_count > 1) import_status += "Scores for <strong class=\"text-danger\">" + not_imported_count + " entries</strong> were <em>not</em> imported because, at this time, <a href=\"" + base_url + "/index.php?section=evaluation&amp;go=default&amp;filter=default&amp;view=admin\">only a single judge evaluation exists in the database</a> for each of those entries.</strong>";
              if ((not_imported_count > 0) && (section == "evaluation")) import_status += " Check the notes column below for the number of evaluations submitted per entry.";
              import_status += "</p>";

              import_status += "<p>";
              if (imported_count > 0) import_status += "<a href=\"" + base_url + "index.php?section=admin&amp;go=judging_scores\">View all imported scores</a>.";    
              if (flagged_count == 1) import_status += " <strong>Please note: <span class=\"text-danger\">" + flagged_count + " entry</span></strong> with consensus scores entered by judges that <em>did not match</em> was not imported.";
              if (flagged_count > 1) import_status += " <strong>Please note: <span class=\"text-danger\">" + flagged_count + " entries</span></strong> with consensus scores entered by judges that <em>did not match</em> were not imported. Please check the entries, reconcile the differences, and re-import.";
              if ((flagged_count > 0) && (section == "evaluation")) import_status += " Refresh this page to see the flagged entries.";
              import_status += "</p>";

            	if (imported_count > 0) {
                $("#import-scores-status").html("Successfully imported/updated " + imported_count + " scores.");
                $("#score-entered-status-default").hide();
                $("#score-import-status").html(import_status);
                $("#score-import-status").show("fast");
                $("#import-scores-status-div").attr("class", "alert alert-forest-green");
                $("#import-scores-status-icon").attr("class", "fa fa-check-circle text-forest-green");
                $("#import-scores-status").attr("class", "text-forest-green small");
              }
              else {
                $("#score-import-status").hide();
                $("#import-scores-status").html("There are no updates to make and/or no scores to import that meet the minimum criteria (at least two evaluations recorded).");
                $("#import-scores-status-div").attr("class", "alert alert-purple");
                $("#import-scores-status-icon").attr("class", "fa fa-asterisk text-purple");
                $("#import-scores-status").attr("class", "text-purple small");
              } 
        			$("#no-scores-entered").hide();
        			$("#import-scores").removeAttr('href');
            }
            else if (jsonData.status === "2") {
            	$("#score-import-status").hide();
              $("#import-scores-status").html("There was a problem. Please try again.");
              $("#import-scores-status-div").attr("class", "alert alert-warning");
              $("#import-scores-status-icon").attr("class", "fa fa-exclamation-circle text-warning");
      			  $("#import-scores-status").attr("class", "text-warning small");
            }
            else {
            	$("#score-import-status").hide();
              $("#import-scores-status").html("There was an error.");
              $("#import-scores-status-div").attr("class", "alert alert-danger");
              $("#import-scores-status-icon").attr("class", "fa fa-times-circle text-danger");
      			  $("#import-scores-status").attr("class", "text-danger small");
            }
        },

        error:function() {
        	console.log('Error');
        }

    });
}

$(document).ready(function(){
  $("#score-import-status").hide();
  $("#import-scores-status-div").hide();
	$("#import-scores").click(function(){
    $("#score-import-status").hide();
    $("#import-scores-status-div").show();
    $("#import-scores-status").html("Loading...");
    $("#import-scores-status-div").attr("class", "alert alert-grey");
    $("#import-scores-status-icon").attr("class", "fa fa-cog fa-spin text-grey");
    $("#import-scores-status").attr("class", "text-grey small");
		import_scores()
	});
});
</script>
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