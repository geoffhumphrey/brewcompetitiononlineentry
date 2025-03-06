<script>
<?php if ($go == "default") {  ?>

var judging_end = moment.tz("<?php echo $judging_end; ?>","<?php echo get_timezone($_SESSION['prefsTimeZone']); ?>");
var label_weeks = "<?php echo strtolower($label_weeks); ?>";
var label_days = "<?php echo strtolower($label_days); ?>";
var label_hours = "<?php echo strtolower($label_hours); ?>"; 
var next_session_open_min = "<?php echo ($next_date - (time() + $diff)); ?>";
var next_session_open = moment.tz("<?php echo $next_judging_date_open; ?>","<?php echo get_timezone($_SESSION['prefsTimeZone']); ?>");

$("#judging-ends").countdown(judging_end.toDate(), function(event) {
    $(this).text(event.strftime('%-w '+label_weeks+' %-d '+label_days+' %-H:%M:%S '+label_hours));
});

$("#next-session-open").countdown(next_session_open.toDate(), function(event) {

    if (next_session_open_min > 604800) {
      var end_time = (event.strftime('%W:%D:%H:%M:%S'));
      $(this).text(event.strftime('%-w '+label_weeks+' %-d '+label_days+' %-H:%M:%S '+label_hours+'.'));
    }

    else if ((next_session_open_min > 86400) && (next_session_open_min < 604800)) {
      var end_time = (event.strftime('%D:%H:%M:%S'));
      $(this).text(event.strftime('%-d '+label_days+' %-H:%M:%S '+label_hours+'.'));
    }

    else if ((next_session_open_min > 3600) && (next_session_open_min < 86400)) {
      var end_time = (event.strftime('%H:%M:%S'));
      $(this).text(event.strftime('%-H:%M:%S '+label_hours+'.'));
    }

    else {
      var end_time = (event.strftime('%M:%S'));
      $(this).text(event.strftime('%M:%S'));
    }

    if ((end_time == "02:00") || (end_time == "00:02:00") || (end_time == "00:00:02:00")) {
      $("#next-session-open").attr("class", "text-orange");
    }

    if ((end_time == "01:00") || (end_time == "00:01:00") || (end_time == "00:00:01:00")) {
      $("#next-session-open").attr("class", "text-danger");
    }
    
    if ((end_time == "00:00") || (end_time == "00:00:00") || (end_time == "00:00:00:00")) {
        $("#next-session-open-modal").modal('show');
        $("#next-session-refresh-button").show('fast');
    }
});
<?php } ?>

<?php if ($go == "scoresheet") { ?>

  var elapsedTimeStart = Date.now();
  
  setInterval(function() {
    
    var elapsedTime = ((Date.now() - elapsedTimeStart) / 1000);
    var elapsedTimeDisp = formatTimeDisplay(elapsedTime,1);
    if (elapsedTime > 3600) {
      var elapsedTimeDisp = formatTimeDisplay(elapsedTime,0);
    }
    
    $("#elapsed-time").html(elapsedTimeDisp);
    
    if ((elapsedTime > 600) && (elapsedTime < 900)) {
      $("#elapsed-time-p").attr("class", "text-warning");
      $("#warning-indicator-icon").attr("class", "fa fa-exclamation-triangle text-warning");
      $("#warning-indicator-icon").show();
      $("#session-end-eval-p").attr("class", "text-warning");
    }
    
    if (elapsedTime >= 900) {
      $("#elapsed-time-p").attr("class", "text-danger");
      $("#courtesy-alert-warning-15").show();
      $("#courtesy-alert-warning-15").attr("class", "text-danger");
      $("#warning-indicator-icon").attr("class", "fa fa-beat-fade fa-exclamation-triangle text-danger");
      $("#warning-indicator-icon").show();
      $("#session-end-eval-p").attr("class", "text-danger");
    }
  }, 1);
  
  $("#session-end-eval").countdown(session_end.toDate(), function(event) {
      if (session_end_min > 1440) var end_time = (event.strftime('%D:%H:%M:%S'));
      else if (session_end_min > 60) var end_time = (event.strftime('%H:%M:%S'));
      else var end_time = (event.strftime('%M:%S'));
      $(this).html(end_time); 
      if (end_time == "15:00") {
          $("#session-end-eval-p").attr("class", "text-danger");
      }
  });

<?php } ?>
</script>