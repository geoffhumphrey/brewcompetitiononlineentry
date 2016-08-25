<script type="text/javascript">
<!--
// From http://www.dynamicdrive.com/forums/showthread.php?33533-No-Duplicates-Chosen-in-Drop-Down-lists
function uniqueChoicesSetup(){
	var warning = 'The place you specified has already been input. Please choose another place or no place (blank).',
	s = document.getElementsByTagName('select'),
	f = function (e){
		var s = uniqueChoicesSetup.ar;
		for (var o = this.options.selectedIndex, i = s.length - 1; i > -1; --i)
		if(this != s[i] && o && o == s[i].options.selectedIndex){
			this.options.selectedIndex = 0;
			if(e && e.preventDefault)
			e.preventDefault();
			alert(warning);
			return false;
		}
	},
	add = function(el){
	uniqueChoicesSetup.ar[uniqueChoicesSetup.ar.length] = el;
	if ( typeof window.addEventListener != 'undefined' ) el.addEventListener( 'change', f, false );
	else if ( typeof window.attachEvent != 'undefined' ){
			var t = function() {
				return f.apply(el);
			};
		el.attachEvent( 'onchange', t );
		}
	};
	uniqueChoicesSetup.ar = [];
	for (var i = s.length - 1; i > -1; --i)
	if(/nodupe/.test(s[i].className))
	add(s[i]);
}

if(typeof window.addEventListener!='undefined')
window.addEventListener('load', uniqueChoicesSetup, false);
else if(typeof window.attachEvent!='undefined')
window.attachEvent('onload', uniqueChoicesSetup);
// -->
</script>

<p class="lead"><?php echo $_SESSION['contestName']; 
if (($action == "edit") && ($id != "default")) echo ": Edit Scores for Table ".$row_tables_edit['tableNumber']." - ".$row_tables_edit['tableName'];  
elseif (($action == "add") && ($id != "default")) echo ": Add Scores for Table ".$row_tables_edit['tableNumber']." - ".$row_tables_edit['tableName'];  
else echo " Scores"; 
if ($dbTable != "default") echo ": All Scores (Archive ".get_suffix($dbTable).")"; 
$totalRows_entry_count = total_paid_received($go,"default");
?></p>

<div class="bcoem-admin-element hidden-print">
	<?php if  ($dbTable != "default") { ?>
    <!-- Postion 1: View All Button -->
    <div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive"><span class="fa fa-arrow-circle-left"></span> Archives</a>
    </div><!-- ./button group -->
    <?php } ?>
    <?php if  ($dbTable == "default") { ?>    
    <?php if ($action != "default") { ?>
    <!-- Postion 1: View All Button -->
    <div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;dbTable=<?php echo $dbTable; ?>"><span class="fa fa-arrow-circle-left"></span> All Scores</a>
    </div><!-- ./button group -->
    <?php } ?>
    
    <?php if ($dbTable == "default") { ?>
    <!-- Postion 1: View All Button -->
    <div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;dbTable=<?php echo $dbTable; ?>"><span class="fa fa-arrow-circle-left"></span> All Tables</a>
    </div><!-- ./button group -->
    <?php } ?>
    
    <?php if ($dbTable == "default") { ?>
    <!-- Postion 1: View All Button -->
    <div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos"><span class="fa fa-eye"></span> View BOS Entries and Places</a>
    </div><!-- ./button group -->
    <?php } ?>

	<?php if (($action == "default") && ($totalRows_tables > 0)) { ?>
	<!-- Position 2: Enter/Edit Dropdown Button Group -->
    <div class="btn-group" role="group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-plus-circle"></span> Add or Update Scores For...  
            <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php do { 
                        $table_count_total = table_count_total($row_tables_edit_2['id']);
                    ?>
                    <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;action=<?php if ($table_count_total > 0) echo "edit&amp;id=".$row_tables_edit_2['id']; else echo "add&amp;id=".$row_tables_edit_2['id']; ?>"><?php echo "Table ".$row_tables_edit_2['tableNumber'].": ".$row_tables_edit_2['tableName']; ?></a></li>
                    <?php  } while ($row_tables_edit_2 = mysqli_fetch_assoc($tables_edit_2)); ?>
            </ul>
		</div>
	<?php } ?>
    <?php if ($id == "default") { ?>
    <!-- Postion 4: Print Button Dropdown Group -->
    <div class="btn-group hidden-xs hidden-sm" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-print"></span> Print...   
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
        	<?php do { 
			if ($row_style_type['styleTypeBOS'] == "Y") { ?>
				<li class="small"><a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_scores_bos&amp;id=<?php echo $row_style_type['id']; ?>"  title="Print the <?php echo $row_style_type['styleTypeName']; ?> BOS Pullsheet">BOS Pullsheet for <?php echo $row_style_type['styleTypeName']; ?></a></li>
		<?php }
			} while ($row_style_type = mysqli_fetch_assoc($style_type));
			?>
        </ul>
    </div>
    <?php } ?>
    <?php } ?>
</div>
<?php if ($dbTable == "default") { ?>

<p>Scores have been entered for <?php echo $totalRows_scores; ?> of <?php echo $totalRows_entry_count; ?> entries marked as paid and received.</p>

<?php } // end if ($dbTable == "default") ?>

<?php if (($action == "default") && ($id == "default")) { ?>
<?php if ($totalRows_scores > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" :  <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'frtp',
			"bStateSave" : false,
			<?php if ($filter == "category") { ?>
			"aaSorting": [[4,'asc'],[6,'asc'],[5,'desc']],
			<?php } elseif ($dbTable != "default") { ?>
			"aaSorting": [[2,'asc'],[8,'asc']],
			<?php } else { ?>
			"aaSorting": [[2,'asc'],[6,'asc'],[5,'desc']],
			<?php } ?>
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				null,
				<?php if ($dbTable != "default") { ?>
				null,
				null,
				<?php } ?>
				null,
				{ "asSorting": [  ] }<?php if ($dbTable == "default") { ?>,
				{ "asSorting": [  ] }
				<?php } ?>
				]
			} );
		} );
	</script>
<table class="table table-responsive table-bordered table-striped" id="sortable">
<thead>
	<tr>
    	<th nowrap>Entry</th>
    	<th nowrap>Judging</th>
        <th nowrap>Table</th>
        <th class="hidden-xs hidden-sm">Table Name</th>
        <th class="hidden-xs hidden-sm">Category</th>
        <?php if ($dbTable != "default") { ?>
        <th>Brewer</th>
        <th>Entry Name</th>
        <?php } ?>
    	<th>Score</th>
        <th>Place</th>
        <th>Mini-BOS?</th>
        <?php if ($dbTable == "default") { ?>
        <th>Actions</th>
        <?php } ?>
    </tr>
</thead>
<tbody>
	<?php
	do {
	
	$table_score_data = table_score_data($row_scores['eid'],$row_scores['scoreTable'],$filter); 
	$table_score_data = explode("^",$table_score_data);
	
	if ((NHC) && ($prefix == "final_")) $entry_number = sprintf("%06s",$table_score_data[0]); 
	else $entry_number = sprintf("%04s",$table_score_data[0]);
	
	if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) $judging_number = sprintf("%06s",$table_score_data[6]); 
	else $judging_number = readable_judging_number($table_score_data[1],$table_score_data[6]);
	
	if ($row_scores['scorePlace'] == "5") $score_place = "HM"; 
	elseif ($row_scores['scorePlace'] == "6") $score_place =  "Admin Advance"; 
	elseif ($row_scores['scorePlace'] == "") $score_place = "<span style=\"display:none\">N/A</span>"; 
	else $score_place =  $row_scores['scorePlace'];
	
	if ($row_scores['scoreMiniBOS'] == "1") $mini_bos = "<span class=\"fa fa-lg fa-check text-success\"></span>";
	else $mini_bos = "&nbsp;";
	
	
	?>
	<tr>
    	<td><?php echo $entry_number; ?></td>
        <td><?php echo $judging_number;  ?></td>
        <td><?php echo $table_score_data[11]; ?></td>
        <td class="hidden-xs hidden-sm"><?php echo $table_score_data[10]; ?></td>
        <td class="hidden-xs hidden-sm"><?php if ($filter == "default") echo $table_score_data[12]." ".style_convert($table_score_data[8],1).": ".$table_score_data[13]; else echo $table_score_data[12].": ".$table_score_data[1]; ?></td>
        
        <?php if ($dbTable != "default") { ?>
        <td><?php echo $table_score_data[5].", ".$table_score_data[4]; ?></td>
        <td><?php echo $table_score_data[3]; ?></td>
        <?php } ?>
        <td><?php echo $row_scores['scoreEntry']; ?></td>
        <td><?php echo $score_place; ?></td>  
        <td><?php echo $mini_bos; ?></td>
		<?php if ($dbTable == "default") { ?>
        <td><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $table_score_data[9]; ?>" data-toggle="tooltip" data-placement="top" title="Edit the <?php echo $table_score_data[10]; ?> scores"><span class="fa fa-lg fa-pencil"></span></a> 
        <a href="<?php echo $base_url; ?>includes/process.inc.php?action=delete&amp;go=<?php echo $go; ?>&amp;id=<?php echo $row_scores['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete this score for entry #<?php echo $row_scores['eid']; ?>" data-confirm="Are you sure? This will delete the score and/or place for this entry."><span class="fa fa-lg fa-trash-o"></span></a>
        </td>
        <?php } ?>
    </tr>
    <?php 
		//}
	} while ($row_scores = mysqli_fetch_assoc($scores)); ?>
</tbody>
</table>
<?php } // end if ($totalRows_scores > 0) 
else echo "<p>No scores have been entered. If tables have been defined, use the &ldquo;Add or Update Scores for...&rdquo; menu above to add scores.</p>"; ?>
<?php } // end if (($action == "default") && ($id == "default")) ?>


<?php if ((($action == "add") || ($action == "edit")) && ($dbTable == "default")) { 
if (NHC) echo "<div class='alert alert-danger'><span class=\"fa fa-exclamation-circle\"></span> A requirement for the NHC is to enter scores for <em>all</em> entries and the top three places for each BJCP category. For an entry to advance to the final round, it must be designated here as 1st, 2nd, or 3rd in its category and achieve a score of 30 or more.</div>";
?>
<?php if ($id != "default") { ?>
<form name="scores" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_scores_db_table; ?>&amp;id=<?php echo $id; ?>">
<script type="text/javascript" language="javascript">
$('.fDrop').live('change', function (event) {
    var cI = $(this);
    $('.fDrop option:selected').each(function (i, e) {
        //Check if values match AND if not default AND not match changed item to self
        if ($(e).val() == cI.val() && $(e).val() != '' && $(e).parent().index() != cI.index()) {
            alert('Duplicate place detected. Is this correct?');
            // cI.val('');
        }
    });
}); 
</script>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$('#sortable').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[2,'asc'],[1,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			null,
			null,
			null,
			{ "asSorting": [  ] },
			null,
			null
		]
	} );
} );
</script>
<table class="table table-responsive table-striped table-bordered" id="sortable">
<thead>
	<tr>
    	<th>Entry</th>
        <th>Judging</th>
        <th class="hidden-xs hidden-sm">Category</th>
        <th>Mini-BOS?</th>
    	<th>Score</th>
        <th>Place</th>
    </tr>
</thead>
<tbody>
<?php
	$a = explode(",", $row_tables_edit['tableStyles']); 
	
	foreach (array_unique($a) as $value) {
		
		$score_style_data = score_style_data($value);
		$score_style_data = explode("^",$score_style_data);
		
		include(DB.'admin_judging_scores.db.php'); // moved to a separate document to not have MySQL queries within loops
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		
		do {

			if ($totalRows_entries > 0) {
				
				if ($action == "edit") {
					$score_entry_data = score_entry_data($row_entries['id']);
					$score_entry_data = explode("^",$score_entry_data);
				}
				
				if (($action == "edit") && (!empty($score_entry_data[0]))) $score_id = $score_entry_data[0]; 
				else $score_id = $row_entries['id'];
				
				if (!empty($score_entry_data[3])) $score_previous = "Y";
				elseif (!empty($score_entry_data[4])) $score_previous = "Y";
				else $score_previous = "N";
				
				$eid = $row_entries['id'];
				
				// $bid is the brewBrewerID/uid
				$bid = $row_entries['brewBrewerID'];
				
				if ((NHC) && ($prefix == "final_")) $entry_number = sprintf("%06s",$row_entries['id']);
				else $entry_number = sprintf("%04s",$row_entries['id']);
				
				$judging_number = sprintf("%06s",$row_entries['brewJudgingNumber']);
				 
				$style_display = $style." ".style_convert($row_entries['brewCategorySort'],1).": ".$score_style_data[2];
			
	?>
	<tr>
        <input type="hidden" name="score_id[]" value="<?php echo $score_id; ?>" />
        <?php if ($action == "edit") { ?>
        <input type="hidden" name="scorePrevious<?php echo $score_id; ?>" value="<?php echo $score_previous; ?>" />
    	<?php } ?>
        <input type="hidden" name="eid<?php echo $score_id; ?>" value="<?php echo $eid; ?>" />
        <input type="hidden" name="bid<?php echo $score_id; ?>" value="<?php echo $bid; ?>" />
        <input type="hidden" name="scoreTable<?php echo $score_id; ?>" value="<?php echo $id; ?>" />
        <input type="hidden" name="scoreType<?php echo $score_id; ?>" value="<?php echo style_type($score_style_data[3],"1","bcoe"); ?>" />
        <td><?php echo $entry_number; ?></td>
        <td><?php echo $judging_number; ?></td>
        <td class="hidden-xs hidden-sm"><?php echo $style_display; ?></td>
        <td><input type="checkbox" name="scoreMiniBOS<?php echo $score_id; ?>" value="1" <?php if (($action == "edit") && ($score_entry_data[5] == "1")) echo "CHECKED"; ?> /></td>
    	<td>
        	<span class="hidden"><?php if ($action == "edit") echo $score_entry_data[3]; ?></span>
        	<input class="form-control" type="text" name="scoreEntry<?php echo $score_id; ?>" size="6" maxlength="6" value="<?php if ($action == "edit") echo $score_entry_data[3]; ?>" />
        </td>
        <td>
        <span class="hidden"><?php if (($action == "edit") && ($score_entry_data[4] == "1")) echo $score_entry_data[4]; ?></span>
            <select class="form-control nodupe" name="scorePlace<?php echo $score_id; ?>">
              	<option value=""></option>
                  <option value="1" <?php if (($action == "edit") && ($score_entry_data[4] == "1")) echo "SELECTED"; ?>>1st</option>
                  <option value="2" <?php if (($action == "edit") && ($score_entry_data[4] == "2")) echo "SELECTED"; ?>>2nd</option>
                  <option value="3" <?php if (($action == "edit") && ($score_entry_data[4] == "3")) echo "SELECTED"; ?>>3rd</option>
                  <?php if (!NHC) { ?>
                  <option value="4" <?php if (($action == "edit") && ($score_entry_data[4] == "4")) echo "SELECTED"; ?>>4th</option>
                  <option value="5" <?php if (($action == "edit") && ($score_entry_data[4] == "5")) echo "SELECTED"; ?>>Hon. Men.</option>
                  <?php } ?>
            </select>
        </td>
	</tr>
    <?php }
		} while ($row_entries = mysqli_fetch_assoc($entries));
	} // end foreach ?> 
</tbody>
</table>
<div class="bcoem-admin-element hidden-print">
	<input type="submit" name="Submit" id="helpUpdateEntries" class="btn btn-primary" aria-describedby="helpBlock" value="<?php if ($action == "edit") echo "Update Scores"; else echo "Add Scores"; ?>" />
    <span id="helpBlock" class="help-block">Click "<?php if ($action == "edit") echo "Update Scores"; else echo "Add Scores"; ?>" <em>before</em> paging through records.</span>
</div>	
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=judging_tables","default",$msg,$id); ?>">
<?php } ?>
</form>

<?php } // end if ($id != "default") ?>
<?php } // end if ((($action == "add") || ($action == "edit")) && ($dbTable == "default")) ?>