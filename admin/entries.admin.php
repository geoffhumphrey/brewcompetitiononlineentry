<h2>Entries</h2>
<?php if ($action != "print") { ?>
<form name="form1" method="post" action="includes/process.inc.php?action=update&dbTable=brewing&filter=<?php echo $filter; ?>&bid=<?php echo $bid; ?>&sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?>">
<table class="dataTable">
<tr>
  <td class="dataList" width="5%" nowrap="nowrap"><a href="index.php?section=admin">&laquo; Back to Admin</a></td>
  <?php if ($dbTable != "default") { ?>
  <td class="dataList" width="5%" nowrap="nowrap"><a href="index.php?section=admin&go=archive">&laquo; Back to Archives</a></td>
  <?php } ?>
  <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/page_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=brew&go=entries&action=add&filter=admin">Add Entry</a></td>
  <?php if ($dbTable == "default") { ?>
  <?php if (($filter != "default") || ($bid != "default")) echo "<td class=\"dataList\" width=\"5%\" nowrap=\"nowrap\"><a href=\"index.php?section=admin&go=entries\">View entries in all categories</a></td>"; ?>
  <?php } ?>
  <td class="dataList"><span class="icon"><img src="images/printer.png" align="absmiddle" /></span><a class="data" href="#" onClick="window.open('print.php?section=<?php echo $section; ?>&go=<?php echo $go; ?>&action=print&filter=<?php echo $filter; ?>&bid=<?php echo $bid; ?>&sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?>','','height=600,width=800,toolbar=no,resizable=yes,scrollbars=yes'); return false;">Print List of Entries</a></td>
</tr>
</table>
<?php } ?>
<table class="dataTable">
<tr>
  <td class="dataHeading" width="5%">Total Entries<?php if ($filter != "default") echo " In This Category"; ?>:</td>
  <td class="data"><?php echo $totalRows_log; ?></td>
</tr>
<tr>
  <td class="dataHeading">Total Entry Fees<?php if ($filter != "default") echo " for This Category"; ?>:</td>
  <td class="data">$<?php echo ($totalRows_log * $row_contest_info['contestEntryFee']); ?></td>
</tr>
<tr>
  <td class="dataHeading">Total Paid Entry Fees<?php if ($filter != "default") echo " for This Category"; ?>:</td>
  <td class="data">$<?php echo ($totalRows_log_paid * $row_contest_info['contestEntryFee']); ?></td>
</tr>
</table>
<?php if ($totalRows_log > 0) { ?>
<table class="dataTable">
 <tr>
  <td class="dataHeading bdr1B"><a href="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?section=".$section."&dbTable=".$dbTable."&go=".$go."&action=".$action."&filter=".$filter."&bid=".$bid."&sort=id&dir="; if ($sort == "id") { if ($dir == "ASC") echo "DESC"; if ($dir == "DESC") echo "ASC"; } else echo $dir; ?>" title="Sort <?php if ($sort == "id") { if ($dir == "ASC") echo "Descending"; if ($dir == "DESC") echo "Ascending"; } else echo "Ascending"; ?>">Ent. #</a></td>
  <td class="dataHeading bdr1B"><a href="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?section=".$section."&dbTable=".$dbTable."&go=".$go."&action=".$action."&filter=".$filter."&bid=".$bid."&sort=brewName&dir="; if ($sort == "brewName") { if ($dir == "ASC") echo "DESC"; if ($dir == "DESC") echo "ASC"; } else echo $dir; ?>" title="Sort <?php if ($sort == "id") { if ($dir == "ASC") echo "Descending"; if ($dir == "DESC") echo "Ascending"; } else echo "Ascending"; ?>">Name</a></td>
  <td class="dataHeading bdr1B"><a href="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?section=".$section."&dbTable=".$dbTable."&go=".$go."&action=".$action."&filter=".$filter."&bid=".$bid."&sort=brewCategorySort,brewSubCategory&dir="; if ($sort == "brewCategorySort,brewSubCategory") { if ($dir == "ASC") echo "DESC"; if ($dir == "DESC") echo "ASC"; } else echo $dir; ?>" title="Sort <?php if ($sort == "id") { if ($dir == "ASC") echo "Descending"; if ($dir == "DESC") echo "Ascending"; } else echo "Ascending"; ?>">Category</a></td>
  <td class="dataHeading bdr1B"><?php if ($bid == "default") { ?><a href="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?section=".$section."&dbTable=".$dbTable."&go=".$go."&action=".$action."&filter=".$filter."&bid=".$bid."&sort=brewBrewerLastName&dir="; if ($sort == "brewBrewerLastName") { if ($dir == "ASC") echo "DESC"; if ($dir == "DESC") echo "ASC"; } else echo $dir; ?>" title="Sort <?php if ($sort == "id") { if ($dir == "ASC") echo "Descending"; if ($dir == "DESC") echo "Ascending"; } else echo "Ascending"; ?>"><?php } ?>Brewer<?php if ($bid == "default") { ?></a><?php } ?></td>
  <td class="dataHeading bdr1B"><a href="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?section=".$section."&dbTable=".$dbTable."&go=".$go."&action=".$action."&filter=".$filter."&bid=".$bid."&sort=brewReceived&dir="; if ($sort == "brewReceived") { if ($dir == "ASC") echo "DESC"; if ($dir == "DESC") echo "ASC"; } else echo "DESC"; ?>" title="Sort <?php if ($sort == "id") { if ($dir == "ASC") echo "Descending"; if ($dir == "DESC") echo "Ascending"; } else echo "Ascending"; ?>">Rec'd?</a></td>
  <td class="dataHeading bdr1B"><a href="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?section=".$section."&dbTable=".$dbTable."&go=".$go."&action=".$action."&filter=".$filter."&bid=".$bid."&sort=brewPaid&dir="; if ($sort == "brewPaid") { if ($dir == "ASC") echo "DESC"; if ($dir == "DESC") echo "ASC"; } else echo "DESC"; ?>" title="Sort <?php if ($sort == "id") { if ($dir == "ASC") echo "Descending"; if ($dir == "DESC") echo "Ascending"; } else echo "Ascending"; ?>">Paid?</a></td>
  <td class="dataHeading bdr1B"><a href="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?section=".$section."&dbTable=".$dbTable."&go=".$go."&action=".$action."&filter=".$filter."&bid=".$bid."&sort=brewWinner&dir="; if ($sort == "brewWinner") { if ($dir == "ASC") echo "DESC"; if ($dir == "DESC") echo "ASC"; } else echo "DESC"; ?>" title="Sort <?php if ($sort == "id") { if ($dir == "ASC") echo "Descending"; if ($dir == "DESC") echo "Ascending"; } else echo "Ascending"; ?>">Winner?</a></td>
  <td class="dataHeading bdr1B">Winner Cat./Sub.</td>
  <td class="dataHeading bdr1B">Place</td>
  <td class="dataHeading bdr1B"><a href="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?section=".$section."&dbTable=".$dbTable."&go=".$go."&action=".$action."&filter=".$filter."&bid=".$bid."&sort=brewBOSRound&dir="; if ($sort == "brewBOSRound") { if ($dir == "ASC") echo "DESC"; if ($dir == "DESC") echo "ASC"; } else echo "DESC"; ?>" title="Sort <?php if ($sort == "id") { if ($dir == "ASC") echo "Descending"; if ($dir == "DESC") echo "Ascending"; } else echo "Ascending"; ?>">BOS?</a></td>
  <td class="dataHeading bdr1B">BOS Place</td>
  <?php if ($action != "print") { ?>
  <td class="dataHeading bdr1B">Actions</td>
  <?php } ?>
 </tr>
 <?php 
 	do {
	{  
    include ('includes/style_convert.inc.php');
	mysql_select_db($database, $brewing);
	if ($row_log['brewCategory'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $fix.$row_log['brewCategory'], $row_log['brewSubCategory']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	
	?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
   <input type="hidden" name="id[]" value="<?php echo $row_log['id']; ?>" />
  <td width="5%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php echo $row_log['id']; ?></td>
  <td <?php if ($action != "print") { ?>width="20%"<?php } else { ?>width="20%"<?php } ?> class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php echo $row_log['brewName']; ?></td>
  <td <?php if ($action != "print") { ?>width="10%"<?php } else { ?>width="20%"<?php } ?> class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($filter == "default") && ($bid == "default")) { ?><a href="index.php?section=admin&go=entries&filter=<?php echo $row_log['brewCategorySort']; ?>" title="See only the <?php echo $styleConvert; ?> entries"><?php } if ($action != "print") echo $row_log['brewCategory'].$row_log['brewSubCategory']; else echo $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$styleConvert; if (($filter == "default") && ($bid == "default")) { ?></a><?php } ?></td>
  <td width="20%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if ($bid == "default") { ?><a href="index.php?section=admin&go=entries&bid=<?php echo $row_log['brewBrewerID']; ?>" title="See only the <?php echo $row_log['brewBrewerFirstName']." ".$row_log['brewBrewerLastName']."&rsquo;s"; ?> entries"><?php } echo  $row_log['brewBrewerFirstName']." ".$row_log['brewBrewerLastName']; ?><?php if ($bid == "default") { ?></a><?php } ?></td>
  <td width="5%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if ($action != "print") { ?><input id="brewReceived" name="brewReceived<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewReceived'] == "Y") echo "checked"; else ""; ?> /><?php } else { if ($row_log['brewReceived'] == "Y") echo "X"; } ?></td>
  <td width="5%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if ($action != "print") { ?><input id="brewPaid" name="brewPaid<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewPaid'] == "Y") echo "checked"; else ""; ?> /><?php } else { if ($row_log['brewPaid'] == "Y") echo "X"; } ?></td>
  <td width="5%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if ($action != "print") { ?><input id="brewWinner" name="brewWinner<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewWinner'] == "Y") echo "checked"; else ""; ?> /><?php } else { if ($row_log['brewWinner'] == "Y") echo "X"; } ?></td>
  <td width="5%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>">
  <?php if ($action != "print") { ?>
  	  <select name="brewWinnerCat<?php echo $row_log['id']; ?>" id="brewWinnerCat">
  	  <option value="">&nbsp;&nbsp;&nbsp;</option>
      <option value="01" <?php if ($row_log['brewWinnerCat'] == "01") echo "SELECTED"; ?>>1</option>
      <option value="02" <?php if ($row_log['brewWinnerCat'] == "02") echo "SELECTED"; ?>>2</option>
      <option value="03" <?php if ($row_log['brewWinnerCat'] == "03") echo "SELECTED"; ?>>3</option>
      <option value="04" <?php if ($row_log['brewWinnerCat'] == "04") echo "SELECTED"; ?>>4</option>
      <option value="05" <?php if ($row_log['brewWinnerCat'] == "05") echo "SELECTED"; ?>>5</option>
      <option value="06" <?php if ($row_log['brewWinnerCat'] == "06") echo "SELECTED"; ?>>6</option>
      <option value="07" <?php if ($row_log['brewWinnerCat'] == "07") echo "SELECTED"; ?>>7</option>
      <option value="08" <?php if ($row_log['brewWinnerCat'] == "08") echo "SELECTED"; ?>>8</option>
      <option value="09" <?php if ($row_log['brewWinnerCat'] == "09") echo "SELECTED"; ?>>9</option>
      <option value="10" <?php if ($row_log['brewWinnerCat'] == "10") echo "SELECTED"; ?>>10</option>
      <option value="11" <?php if ($row_log['brewWinnerCat'] == "11") echo "SELECTED"; ?>>11</option>
      <option value="12" <?php if ($row_log['brewWinnerCat'] == "12") echo "SELECTED"; ?>>12</option>
      <option value="13" <?php if ($row_log['brewWinnerCat'] == "13") echo "SELECTED"; ?>>13</option>
      <option value="14" <?php if ($row_log['brewWinnerCat'] == "14") echo "SELECTED"; ?>>14</option>
      <option value="15" <?php if ($row_log['brewWinnerCat'] == "15") echo "SELECTED"; ?>>15</option>
      <option value="16" <?php if ($row_log['brewWinnerCat'] == "16") echo "SELECTED"; ?>>16</option>
      <option value="17" <?php if ($row_log['brewWinnerCat'] == "17") echo "SELECTED"; ?>>17</option>
      <option value="18" <?php if ($row_log['brewWinnerCat'] == "18") echo "SELECTED"; ?>>18</option>
      <option value="19" <?php if ($row_log['brewWinnerCat'] == "19") echo "SELECTED"; ?>>19</option>
      <option value="20" <?php if ($row_log['brewWinnerCat'] == "20") echo "SELECTED"; ?>>20</option>
      <option value="21" <?php if ($row_log['brewWinnerCat'] == "21") echo "SELECTED"; ?>>21</option>
      <option value="22" <?php if ($row_log['brewWinnerCat'] == "22") echo "SELECTED"; ?>>22</option>
      <option value="23" <?php if ($row_log['brewWinnerCat'] == "23") echo "SELECTED"; ?>>23</option>
      <option value="24" <?php if ($row_log['brewWinnerCat'] == "24") echo "SELECTED"; ?>>24</option>
      <option value="25" <?php if ($row_log['brewWinnerCat'] == "25") echo "SELECTED"; ?>>25</option>
      <option value="26" <?php if ($row_log['brewWinnerCat'] == "26") echo "SELECTED"; ?>>26</option>
      <option value="27" <?php if ($row_log['brewWinnerCat'] == "27") echo "SELECTED"; ?>>27</option>
      <option value="28" <?php if ($row_log['brewWinnerCat'] == "28") echo "SELECTED"; ?>>28</option>
    </select>
    &nbsp;      
    <select name="brewWinnerSubCat<?php echo $row_log['id']; ?>" id="brewWinnerSubCat">
  	  <option value="">&nbsp;&nbsp;&nbsp;</option>
      <option value="A" <?php if ($row_log['brewWinnerSubCat'] == "A") echo "SELECTED"; ?>>A</option>
      <option value="B" <?php if ($row_log['brewWinnerSubCat'] == "B") echo "SELECTED"; ?>>B</option>
      <option value="C" <?php if ($row_log['brewWinnerSubCat'] == "C") echo "SELECTED"; ?>>C</option>
      <option value="D" <?php if ($row_log['brewWinnerSubCat'] == "D") echo "SELECTED"; ?>>D</option>
      <option value="E" <?php if ($row_log['brewWinnerSubCat'] == "E") echo "SELECTED"; ?>>E</option>
      <option value="F" <?php if ($row_log['brewWinnerSubCat'] == "F") echo "SELECTED"; ?>>F</option>
      <option value="G" <?php if ($row_log['brewWinnerSubCat'] == "G") echo "SELECTED"; ?>>G</option>
    </select>
    <?php } else echo $row_log['brewWinnerCat'].$row_log['brewWinnerSubCat']; ?>
    </td>
  <td width="3%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>">
      <?php if ($action != "print") { ?>
      <select name="brewWinnerPlace<?php echo $row_log['id']; ?>" id="brewWinnerPlace">
  	  <option value="">&nbsp;&nbsp;&nbsp;</option>
      <option value="1" <?php if ($row_log['brewWinnerPlace'] == "1") echo "SELECTED"; ?>>1</option>
      <option value="2" <?php if ($row_log['brewWinnerPlace'] == "2") echo "SELECTED"; ?>>2</option>
      <option value="3" <?php if ($row_log['brewWinnerPlace'] == "3") echo "SELECTED"; ?>>3</option>
      <option value="HM" <?php if ($row_log['brewWinnerPlace'] == "HM") echo "SELECTED"; ?>>HM</option>
      </select>
      <?php } else echo $row_log['brewWinnerPlace']; ?>  </td>
  <td width="3%" class="dataList<?php if ($action == "print") echo " bdr1B"; ?>"><?php if ($action != "print") { ?><input id="breBOSRound" name="brewBOSRound<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewBOSRound'] == "Y") echo "checked"; else ""; ?> /><?php } else { if ($row_log['brewBOSRound'] == "Y") echo "X"; } ?></td>
  <td width="3%" class="dataList<?php if ($action == "print") echo " bdr1B"; ?>">
  	  <?php if ($action != "print") { ?>
      <select name="brewBOSPlace<?php echo $row_log['id']; ?>" id="brewBOSPlace">
  	  <option value="">&nbsp;&nbsp;&nbsp;</option>
      <option value="1" <?php if ($row_log['brewBOSPlace'] == "1") echo "SELECTED"; ?>>1</option>
      <option value="2" <?php if ($row_log['brewBOSPlace'] == "2") echo "SELECTED"; ?>>2</option>
      <option value="3" <?php if ($row_log['brewBOSPlace'] == "3") echo "SELECTED"; ?>>3</option>
      <option value="HM" <?php if ($row_log['brewBOSPlace'] == "HM") echo "SELECTED"; ?>>HM</option>
      </select>
      <?php } else echo $row_log['brewBOSPlace']; ?>  </td>
  <?php if ($action != "print") { ?>
  <td class="dataList" nowrap="nowrap">
  <span class="icon"><a href="index.php?section=brew&go=<?php echo $go; ?>&filter=<?php echo $row_log['brewBrewerID']; ?>&action=edit&id=<?php echo $row_log['id']; ?>"><img src="images/pencil.png" align="absmiddle" border="0" alt="Edit <?php echo $row_log['brewName']; ?>" title="Edit <?php echo $row_log['brewName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=brew&go=<?php echo $go; ?>&filter=<?php echo $filter; ?>&dbTable=brewing&action=delete','id',<?php echo $row_log['id']; ?>,'Are you sure you want to delete the entry called <?php echo $row_log['brewName']; ?>? This cannot be undone.');"><img src="images/bin_closed.png" align="absmiddle" border="0" alt="Delete <?php echo $row_log['brewName']; ?>" title="Delete <?php echo $row_log['brewName']; ?>"></a></span><span class="icon"><a class="thickbox" href="sections/entry.sec.php?id=<?php echo $row_log['id']; ?>&bid=<?php echo $row_log['brewBrewerID']; ?>&KeepThis=true&TB_iframe=true&height=425&width=700" title="Print the Entry Forms for <?php echo $row_log['brewName']; ?>"><img src="images/printer.png" align="absmiddle" border="0" alt="Print the Entry Forms for <?php echo $row_log['brewName']; ?>" title="Print the Entry Forms for <?php echo $row_log['brewName']; ?>"></a></span></td>
  <?php } ?>
  </tr>

  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } } while($row_log = mysql_fetch_assoc($log)) ?>
 <?php if ($action != "print") { ?>
 <tr>
 	<td colspan="12" class="bdr1T">&nbsp;</td>
 </tr>
 <tr>
 	<td colspan="11">&nbsp;</td>
    <td colspan="2" align="left"><input type="submit" name="Submit" class="button" value="Update Entries" /></td>
 </tr>
<?php } ?>
</table>
<?php if ($action != "print") { ?>
</form>

<?php } 
} else echo "<div class=\"error\">There are no entires.</div>"; ?>