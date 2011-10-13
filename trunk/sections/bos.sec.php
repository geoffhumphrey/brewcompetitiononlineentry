<?php 
/**
 * Module:      bos.sec.php 
 * Description: This module houses public-facing display of the BEST of
 *              show results.
 * 
 */
?>
<h2>Best of Show Winners<?php if ($section == "past_winners") echo ": ".$trimmed; if ($row_bos_scores['count'] > 0) { if (($section == "default") && ($row_prefs['prefsCompOrg'] == "Y") && ($action != "print")) { ?><span class="icon">&nbsp;<a href="output/results_download.php?section=admin&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=pdf"><img src="images/page_white_acrobat.png" border="0" title="Download a PDF of the Best of Show Winner List"/></a></span><span class="icon"><a href="output/results_download.php?section=admin&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=html"><img src="images/html.png" border="0" title="Download the Best of Show Winner List in HTML format"/></a></span><?php } ?></h2>
<?php if ($row_prefs['prefsCompOrg'] == "Y") { 
	// Display BOS winners for each applicable style type
	do { $a[] = $row_style_types['id']; } while ($row_style_types = mysql_fetch_assoc($style_types));
	sort($a);
	foreach ($a as $type) {
		$query_style_type = sprintf("SELECT * FROM %s WHERE id='%s'", $style_type_db_table, $type);
		$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
		$row_style_type = mysql_fetch_assoc($style_type);
		
		if ($row_style_type['styleTypeBOS'] == "Y") { 
			$query_bos = "SELECT * FROM $scores_bos_db_table WHERE (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5') AND scoreType='$type' ORDER BY scorePlace ASC";
			$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
			$row_bos = mysql_fetch_assoc($bos);
			$totalRows_bos = mysql_num_rows($bos);
			if ($totalRows_bos > 0) { 
			
			$random = random_generator(6,2);
			
?>        
<h3>BOS - <?php echo $row_style_type['styleTypeName']; ?></h3>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $random; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
<table class="dataTable" id="sortable<?php echo $random; ?>">
<thead>
	<tr>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Place</th>
        <th class="dataList bdr1B" width="25%" nowrap="nowrap">Brewer(s)</th>
        <th class="dataList bdr1B" width="25%" nowrap="nowrap">Entry Name</th>
        <th class="dataList bdr1B" width="25%" nowrap="nowrap">Style</th>
        <th class="dataList bdr1B">Club</th>
    </tr>
</thead>
<tbody>
	<?php do {
	$query_entries = sprintf("SELECT brewBrewerID,brewBrewerFirstName,brewBrewerLastName,brewName,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewCoBrewer FROM $brewing_db_table WHERE id='%s'", $row_bos['eid']);
	$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
	$row_entries = mysql_fetch_assoc($entries);
	$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
	
	$query_brewer = sprintf("SELECT id,brewerClubs FROM $brewer_db_table WHERE uid='%s'", $row_entries['brewBrewerID']);
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	
	?>
	<tr>
        <td class="data"><?php echo display_place($row_bos['scorePlace'],1); ?></td>
        <td class="data"><?php echo $row_entries['brewBrewerFirstName']." ".$row_entries['brewBrewerLastName']; if ($row_entries['brewCoBrewer'] != "") echo "<br>Co-Brewer: ".$row_entries['brewCoBrewer']; ?></td>
        <td class="data"><?php echo $row_entries['brewName']; ?></td>
        <td class="data"><?php echo $style." ".style_convert($row_entries['brewCategorySort'],1).": ".$row_entries['brewStyle']; ?></td>
        <td class="data"><?php echo $row_brewer['brewerClubs']; ?></td>
    </tr>
    <?php } while ($row_bos = mysql_fetch_assoc($bos)); 
	mysql_free_result($bos);
	mysql_free_result($entries);
	?>
</tbody>
</table>
<?php 	} 
	else echo "<p style='margin: 0 0 40px 0'>No entries are eligible.</p>";
    } 
  }
//if ($row_contest_info['contestBOSAward'] != "") echo "<h3>Best of Show Award(s)</h3>".$row_contest_info['contestBOSAward']; 
} // end if ($row_prefs['prefsCompOrg'] == "Y") 

if ($row_prefs['prefsCompOrg'] == "N") { 
include(DB.'entries.db.php');
if ($totalRows_bos > 0) { 
if (($row_prefs['prefsBOSCider'] == "Y") || ($row_prefs['prefsBOSMead'] == "Y")) echo "<h3>Beer Categories</h3>"; 
if ($row_contest_info['contestBOSAward'] != "") echo $row_contest_info['contestBOSAward']; ?>
<table class="dataTable">
<thead>
 <tr>
  <th class="dataHeading bdr1B" width="5%" nowrap="nowrap">Place</th>
  <th class="dataHeading bdr1B" width="25%">Category</th>
  <th class="dataHeading bdr1B" width="25%">Brewer</th>
  <th class="dataHeading bdr1B" width="25%">Entry Name</th>
  <th class="dataHeading bdr1B">Club</th>
 </tr>
</thead>
<tbody>
 <?php do { 
    
	mysql_select_db($database, $brewing);
	//if ($row_log_bos['brewWinnerCat'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_bos['brewWinnerCat'], $row_bos['brewWinnerSubCat']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	$query_club = sprintf("SELECT brewerClubs FROM $brewer_db_table WHERE uid = '%s'", $row_bos['brewBrewerID']);
	$club = mysql_query($query_club, $brewing) or die(mysql_error());
	$row_club = mysql_fetch_assoc($club);
	?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td class="dataList" nowrap="nowrap"><?php echo $row_bos['brewBOSPlace']; ?></td>
  <td class="dataList">
  <?php 
  	echo style_convert($row_bos['brewWinnerCat'],"1"); if ($row_bos['brewWinnerSubCat']!= "") { echo ": ".$row_style['brewStyle']." (".$row_bos['brewWinnerCat']; if ($row_bos['brewWinnerSubCat']!= "") echo $row_bos['brewSubCategory']; echo ")"; } 
  	if ($row_bos['brewWinnerCat'] >= 29) {
		$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup='%s'", $row_bos['brewWinnerCat']);  
    	$style = mysql_query($query_style, $brewing) or die(mysql_error());
		$row_style = mysql_fetch_assoc($style);
		echo ": ".$row_style['brewStyle'];
  		} 
	 ?>
  </td>
  <td class="dataList"><?php echo $row_bos['brewBrewerFirstName']." ".$row_bos['brewBrewerLastName']; if ($row_log['brewCoBrewer'] != "") echo "<br>Co-Brewer: ".$row_log['brewCoBrewer']; ?></td>
  <td class="dataList"><?php echo $row_bos['brewName']; ?></td>
  <td class="dataList"><?php echo $row_club['brewerClubs']; ?></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_bos = mysql_fetch_assoc($bos)); ?>
  <tr>
  	<td class="bdr1T" colspan="5">&nbsp;</td>
  </tr>
</tbody>
</table>
<?php } 
if (($row_prefs['prefsBOSMead'] == "Y") && ($totalRows_bos3 > 0)) { ?>
<h3>Mead Categories</h3>
<table class="dataTable">
<thead>
 <tr>
  <th class="dataHeading bdr1B" width="5%" nowrap="nowrap">Place</th>
  <th class="dataHeading bdr1B" width="25%">Category</th>
  <th class="dataHeading bdr1B" width="25%">Brewer</th>
  <th class="dataHeading bdr1B" width="25%">Entry Name</th>
  <th class="dataHeading bdr1B">Club</th>
 </tr>
</thead>
<tbody>
 <?php do { 
    
	mysql_select_db($database, $brewing);
	//if ($row_log_bos['brewWinnerCat'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_bos3['brewWinnerCat'], $row_bos3['brewWinnerSubCat']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	$query_club = sprintf("SELECT brewerClubs FROM $brewer_db_table WHERE uid = '%s'", $row_bos3['brewBrewerID']);
	$club = mysql_query($query_club, $brewing) or die(mysql_error());
	$row_club = mysql_fetch_assoc($club);
	?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td class="dataList" nowrap="nowrap"><?php echo $row_bos3['brewBOSPlace']; ?></td>
  <td class="dataList">
  <?php 
  	echo $row_bos3['brewWinnerCat']; if ($row_bos3['brewWinnerSubCat']!= "") { echo ": ".$row_style['brewStyle']." (".$row_bos3['brewWinnerCat']; if ($row_bos3['brewWinnerSubCat']!= "") echo $row_bos3['brewSubCategory']; echo ")"; } 
  	if ($row_bos3['brewWinnerCat'] >= 29) {
		$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup='%s'", $row_bos3['brewWinnerCat']);  
    	$style = mysql_query($query_style, $brewing) or die(mysql_error());
		$row_style = mysql_fetch_assoc($style);
		echo ": ".$row_style['brewStyle'];
  		} 
	 ?>
  </td>
  <td class="dataList"><?php echo $row_bos3['brewBrewerFirstName']." ".$row_bos3['brewBrewerLastName']; if ($row_log['brewCoBrewer'] != "") echo "<br>Co-Brewer: ".$row_log['brewCoBrewer']; ?></td>
  <td class="dataList"><?php echo $row_bos3['brewName']; ?></td>
  <td class="dataList"><?php echo $row_club['brewerClubs']; ?></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_bos3 = mysql_fetch_assoc($bos3)); ?>
  <tr>
  	<td class="bdr1T" colspan="5">&nbsp;</td>
  </tr>
</tbody>
</table>
<?php } // end if BOS cider ?>

<?php if (($row_prefs['prefsBOSCider'] == "Y") && ($totalRows_bos2 > 0)) { ?>
<h3>Cider Categories</h3>
<table class="dataTable">
<thead>
 <tr>
  <th class="dataHeading bdr1B" width="5%" nowrap="nowrap">Place</th>
  <th class="dataHeading bdr1B" width="25%">Category</th>
  <th class="dataHeading bdr1B" width="25%">Brewer</th>
  <th class="dataHeading bdr1B" width="25%">Entry Name</th>
  <th class="dataHeading bdr1B">Club</th>
 </tr>
</thead>
<tbody>
 <?php do { 
	mysql_select_db($database, $brewing);
	//if ($row_log_bos['brewWinnerCat'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_bos2['brewWinnerCat'], $row_bos2['brewWinnerSubCat']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	$query_club = sprintf("SELECT brewerClubs FROM $brewer_db_table WHERE uid = '%s'", $row_bos2['brewBrewerID']);
	$club = mysql_query($query_club, $brewing) or die(mysql_error());
	$row_club = mysql_fetch_assoc($club);
	?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td class="dataList" nowrap="nowrap"><?php echo $row_bos2['brewBOSPlace']; ?></td>
  <td class="dataList">
  <?php 
  	echo $row_bos2['brewWinnerCat']; if ($row_bos2['brewWinnerSubCat']!= "") { echo ": ".$row_style['brewStyle']." (".$row_bos2['brewWinnerCat']; if ($row_bos2['brewWinnerSubCat']!= "") echo $row_bos2['brewSubCategory']; echo ")"; } 
  	if ($row_bos2['brewWinnerCat'] >= 29) {
		$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup='%s'", $row_bos2['brewWinnerCat']);  
    	$style = mysql_query($query_style, $brewing) or die(mysql_error());
		$row_style = mysql_fetch_assoc($style);
		echo ": ".$row_style['brewStyle'];
  		} 
	 ?>
  </td>
  <td class="dataList"><?php echo $row_bos2['brewBrewerFirstName']." ".$row_bos2['brewBrewerLastName']; if ($row_log['brewCoBrewer'] != "") echo "<br>Co-Brewer: ".$row_log['brewCoBrewer'];?></td>
  <td class="dataList"><?php echo $row_bos2['brewName']; ?></td>
  <td class="dataList"><?php echo $row_club['brewerClubs']; ?></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_bos2 = mysql_fetch_assoc($bos2)); ?>
  <tr>
  	<td class="bdr1T" colspan="5">&nbsp;</td>
  </tr>
</tbody>
</table>
<?php } 
  } // end if BOS cider
} else echo "</h2><p>No BOS places have been entered yet. Please check back later.</p>";
?>