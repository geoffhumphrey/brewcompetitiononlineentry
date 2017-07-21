<?php 
if ($dbTable == "default") $pro_edition = $_SESSION['prefsProEdition'];
else $pro_edition = $row_archive_prefs['archiveProEdition'];

if ($pro_edition == 0) $edition = $label_amateur." ".$label_edition;
if ($pro_edition == 1) $edition = $label_pro." ".$label_edition;
?>
<script type="text/javascript">
<!--
// From http://www.dynamicdrive.com/forums/showthread.php?33533-No-Duplicates-Chosen-in-Drop-Down-lists
function uniqueCoicesSetup(){
	var warning = 'The place you specified has already been input. Please choose another place or no place (blank).',
	s = document.getElementsByTagName('select'),
	f = function (e){
		var s = uniqueCoicesSetup.ar;
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
	uniqueCoicesSetup.ar[uniqueCoicesSetup.ar.length] = el;
	if ( typeof window.addEventListener != 'undefined' ) el.addEventListener( 'change', f, false );
	else if ( typeof window.attachEvent != 'undefined' ){
			var t = function() {
				return f.apply(el);
			};
		el.attachEvent( 'onchange', t );
		}
	};
	uniqueCoicesSetup.ar = [];
	for (var i = s.length - 1; i > -1; --i)
	if(/nodupe/.test(s[i].className))
	add(s[i]);
}

if(typeof window.addEventListener!='undefined')
window.addEventListener('load', uniqueCoicesSetup, false);
else if(typeof window.attachEvent!='undefined')
window.attachEvent('onload', uniqueCoicesSetup);
// -->
</script>
<p class="lead"><?php echo $_SESSION['contestName']; 
if ($action == "enter") echo ": Add or Update BOS Places for ".$row_style_type['styleTypeName']; else echo ": Best of Show (BOS) Entries and Places"; 
if ($dbTable != "default") echo " (Archive ".get_suffix($dbTable).")"; 
?></p>
<?php if ($dbTable != "default") { ?>
<p><?php echo $edition; ?></p>
<?php } ?>
<div class="bcoem-admin-element hidden-print">
	<?php if  ($dbTable != "default") { ?>
    <!-- Postion 1: View All Button -->
    <div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive"><span class="fa fa-arrow-circle-left"></span> Archives</a>
    </div><!-- ./button group -->
    <?php } ?>
    <?php if ($dbTable == "default") { ?>
    
		<?php if ($action == "enter") { ?>
        <!-- Postion 1: View All Button -->
        <div class="btn-group" role="group" aria-label="...">
            <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos"><span class="fa fa-arrow-circle-left"></span> All BOS Entries and Places</a>
        </div><!-- ./button group -->
        <?php } ?>
    
    	<!-- Postion 1: View All Button -->
        <div class="btn-group" role="group" aria-label="...">
            <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores"><span class="fa fa-arrow-circle-left"></span> All Scores</a>
        </div><!-- ./button group -->
        
        <!-- Postion 1: View All Button -->
        <div class="btn-group" role="group" aria-label="...">
            <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables"><span class="fa fa-arrow-circle-left"></span> All Tables</a>
        </div><!-- ./button group -->
        
        <?php if (($action == "default") && ($totalRows_style_type > 0)) { ?>
        <!-- Position 2: Enter/Edit Dropdown Button Group -->
        <div class="btn-group" role="group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="fa fa-plus-circle"></span> Add or Update...  
                <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                
                <?php do { 
                    if ($row_style_types_2['styleTypeBOS'] == "Y") { ?>
                    <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos&amp;action=enter&amp;filter=<?php echo $row_style_types_2['id'] ?>">BOS Places for <?php echo $row_style_types_2['styleTypeName']; ?></a>
                <?php 
                    }
                } while ($row_style_types_2 = mysqli_fetch_assoc($style_types_2));
                ?>
                </ul>
            </div>
        <?php } ?>
        
        
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
                <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=bos-mat" title="Print BOS Cup Mats">BOS Cup Mats (Judging Numbers)</a></li>
                <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=bos-mat&amp;filter=entry" title="Print BOS Cup Mats">BOS Cup Mats (Entry Numbers)</a></li>
            </ul>
    	</div>
    
    <?php } ?>

</div>
<?php 
if (($action == "default") && ($totalRows_style_type > 0)) {
do { $a[] = $row_style_types['id']; } while ($row_style_types = mysqli_fetch_assoc($style_types));
sort($a);

foreach ($a as $type) {
	$style_type_info = style_type_info($type);
	
	$style_type_info = explode("^",$style_type_info);
	
if ($style_type_info[0] == "Y") { 

include (DB.'admin_judging_scores_bos.db.php');

?>
<a name="<?php echo $type; ?>"></a><h3>BOS Entries and Places for <?php echo $style_type_info[2]; ?></h3>
<?php if ($totalRows_bos > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $type; ?>').dataTable( {
			"bPaginate" : false,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
		"iDisplayLength" :  <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'rt',
			"bStateSave" : false,
			"aaSorting": [[2,'asc'],[6,'asc'],[7,'asc']],
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				null,
				null,
			null,
				null
				]
			} );
		} );
	</script>
<table class="table table-striped table-bordered table-responsive" id="sortable<?php echo $type; ?>">
<thead>
	<tr>
    	<th nowrap>Entry</th>
        <th nowrap>Judging</th>
        <th nowrap>Table</th>
        <th class="hidden-xs hidden-sm">Table Name</th>
        <th>Style</th>
        <?php if ($dbTable == "default") { ?>
    	<th class="hidden-xs hidden-sm">Table Score</th>
        <th class="hidden-xs hidden-sm">Table Place</th>
        <?php } ?>
        <?php if ($dbTable != "default") { ?>
        <th><?php if ($pro_edition == 1) echo $label_organization; else echo $label_brewer; ?></th>
        <th>Entry Name</th>
        <?php } ?>
        <th>BOS Score</th>
        <th>BOS Place</th>
    </tr>
</thead>
<tbody>
	<?php do {
	
	$bos_entry_info = bos_entry_info($row_bos['eid'], $row_bos['scoreTable'],$filter);
	$bos_entry_info = explode("^",$bos_entry_info);
	$style = $bos_entry_info[1].$bos_entry_info[3];
	
	if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) $judging_number = sprintf("%06s",$bos_entry_info[6]); 
	else $judging_number = readable_judging_number($bos_entry_info[1],$bos_entry_info[6]);
	
	if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
		
		if ($filter == "default") $style_name = $style." ".style_convert($bos_entry_info[1],1).": ".$bos_entry_info[0];
		else $style_name = $style.": ".$bos_entry_info[0];
		
	}
	
	else $style_name = $bos_entry_info[0];
	?>
	<tr>
    	<td nowrap><?php echo sprintf("%04s",$row_bos['eid']); ?></td>
        <td><?php echo $judging_number; ?></td>
        <td><?php echo $bos_entry_info[9] ?></td>
        <td class="hidden-xs hidden-sm"><?php echo $bos_entry_info[8]; ?></td>
        <td>
		<?php echo $style_name;  ?>
        </td>
        <?php if ($dbTable == "default") { ?>
        <td class="hidden-xs hidden-sm"><?php echo $row_bos['scoreEntry']; ?></td>
        <td class="hidden-xs hidden-sm"><?php echo $row_bos['scorePlace']; ?></td>
        <?php } ?>
        <?php if ($dbTable != "default") { ?>
        <td><?php if ($pro_edition == 1) echo $bos_entry_info[16]; else echo $bos_entry_info[5].", ".$bos_entry_info[4]; ?></td>
        <td><?php echo $bos_entry_info[12]; ?></td>
        
        <?php } ?>
        <td><?php echo $bos_entry_info[11]; ?></td>
        <td><?php echo $bos_entry_info[10] ?></td>
    </tr>
    <?php } while ($row_bos = mysqli_fetch_assoc($bos)); ?>
</tbody>
</table>
<?php } else echo "<p style='margin: 0 0 40px 0'>No entries are eligible.</p>"; 
} 

?>
<?php } ?>

<?php 
//else echo "<p style='margin: 0 0 40px 0'>No Best of Show <a href='".$base_url."index.php?section=admin&amp;go=style_types' title='Enable Best of Show for one or more style types'>has been enabled</a> for any style type.</p>";
} // end if ($action == "default")
?>

<?php if ($action == "enter") { 

include (DB.'admin_judging_scores_bos.db.php');
?>
<?php if ($totalRows_enter_bos > 0) { ?>
<form name="scores" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_scores_bos_db_table; ?>">
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[2,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				null,
				null,
				null,
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
			]
			} );
		} );
</script>
<table class="table table-striped table-bordered table-responsive" id="sortable">
<thead>
	<tr>
    	<th nowrap>Entry</th>
        <th nowrap>Judging</th>
        <th>Style</th>
    	<th>Score</th>
        <th>Place</th>
    </tr>
</thead>
<tbody>
	<?php 
	do {
		
		$bos_entry_info = bos_entry_info($row_enter_bos['eid'], "default","default");
		$bos_entry_info = explode("^",$bos_entry_info);
		$style = $bos_entry_info[1].$bos_entry_info[3];
		if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) $judging_number = sprintf("%06s",$bos_entry_info[6]); 
		else $judging_number = readable_judging_number($bos_entry_info[1],$bos_entry_info[6]);
		
		if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
		
		if ($filter == "default") $style_name = $style." ".style_convert($bos_entry_info[1],1).": ".$bos_entry_info[0];
		else $style_name = $style.": ".$bos_entry_info[0];
		
	}
	
	else $style_name = $bos_entry_info[0];
		
	?>
	<tr>
		<?php $score_id = $bos_entry_info[13]; ?>
        <input type="hidden" name="score_id[]" value="<?php echo $score_id; ?>" />
        <input type="hidden" name="scorePrevious<?php echo $score_id; ?>" value="<?php if (!empty($bos_entry_info[14])) echo "Y"; else echo "N"; ?>" />
        <input type="hidden" name="eid<?php echo $score_id; ?>" value="<?php echo $score_id; ?>" />
        <input type="hidden" name="bid<?php echo $score_id; ?>" value="<?php echo $bos_entry_info[15]; ?>" />
        <input type="hidden" name="scoreType<?php echo $score_id; ?>" value="<?php echo $filter; ?>" />
        <?php if (!empty($bos_entry_info[14])) { ?>
        <input type="hidden" name="id<?php echo $score_id; ?>" value="<?php echo $bos_entry_info[14]; ?>" />
        <?php } ?>
        <td><?php echo sprintf("%04s",$row_enter_bos['eid']); ?></td>
        <td><?php echo $judging_number ?></td>
        <td><?php echo $style_name; ?></td>
    	<td><input class="form-control" type="text" name="scoreEntry<?php echo $score_id; ?>" size="5" maxlength="2" value="<?php echo $bos_entry_info[11]; ?>" /></td>
        <td>
        <select class="form-control nodupe" name="scorePlace<?php echo $score_id; ?>">
          <option value=""></option>
          <?php for($i=1; $i<$_SESSION['jPrefsMaxBOS']+1; $i++) { ?>
          <option value="<?php echo $i; ?>" <?php if ($bos_entry_info[10] == $i) echo "selected"; ?>><?php echo text_number($i); ?></option>
          <?php } ?>
          <option value="HM" <?php if ($bos_entry_info[10] == "HM") echo "selected"; ?>><?php echo "Hon. Men."; ?></option>
        </select>
        </td>
	</tr>
    <?php 
	} while ($row_enter_bos = mysqli_fetch_assoc($enter_bos)); 
	?>	
</tbody>
</table>
<div class="bcoem-admin-element hidden-print">
	<input type="submit" name="Submit" id="updateBOS" class="btn btn-primary" value="<?php if ($action == "enter") echo "Update BOS Places"; else echo "Add BOS Places"; ?>" />
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=judging_tables","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php } 
else echo "<p>There are no qualifying entries available.</p>";
}
?>