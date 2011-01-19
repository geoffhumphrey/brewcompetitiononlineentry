<?php if (($action == "add") || ($action == "edit")) { ?>
<form method="post" action="includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=judging_tables&amp;go=<?php echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1" onSubmit="return CheckRequiredFields()">
<table summary="Define a judging table and its associated name, number, style(s), and location.">
  <tbody>
  <tr>
    <td class="dataLabel">Table Name:</td>
    <td class="data"><input name="tableName" size="30" value="<?php if ($action == "edit") echo $row_tables['tableName']; ?>"></td>
  </tr>
  <tr>
    <td class="dataLabel">Table Number:</td>
    <td class="data"><input name="tableNumber" size="5" value="<?php if ($action == "edit") echo $row_tables['tableNumber']; ?>"></td>
  </tr>
  <?php if ($totalRows_judging1 > 1) { ?>
  <tr>
    <td class="dataLabel">Location:</td>
    <td class="data">
    <select name="tableLocation" id="tableLocation">
          <option value=""></option>
          <?php do { ?>
          <option value="<?php echo $row_judging1['id']; ?>"><?php echo $row_judging1['judgingLocName']." ("; echo dateconvert($row_judging1['judgingDate'], 3).")"; ?></option>
          <?php } while ($row_judging1 = mysql_fetch_assoc($judging1)) ?>
    </select>
    </td>
  </tr>
  <?php } ?>
  <tr>
    <td class="dataLabel">Style(s):</td>
    <td class="data">
    	<table class="dataTableCompact">
        	<?php do { ?>
            <tr>
            	<td width="1%"><input name="tableStyles[]" type="checkbox" value="<?php echo $row_styles['id']; ?>" <?php if ($action == "edit") { if (get_table_info($row_styles['id'],"styles")) echo "checked"; if (!get_table_info($row_styles['id'],"styles")) echo "disabled"; }  if (get_table_info($row_styles['id'],"styles")) echo "disabled";  ?>></td>
                <td width="1%"><?php echo ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum'].":"; ?></td>
                <td><?php echo style_convert($row_styles['brewStyleGroup'],"1")." - ".$row_styles['brewStyle'].get_table_info($row_styles['id'],"assigned"); ?></td>
            </tr>
            <?php } while ($row_styles = mysql_fetch_assoc($styles)); ?>
        </table>
    </td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td class="data"><input type="submit" class="button" value="Submit"></td>
  </tr>
  </tbody>
</table>
</form>
<?php } // end if add or edit ?>