<form method="post" action="includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php if ($section == "step3") echo "add"; else echo $action; ?>&amp;dbTable=judging&amp;go=<?php if ($go == "default") echo "setup"; else echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1" onSubmit="return CheckRequiredFields()">
<table>
  <tr>
    <td class="dataLabel">Date:</td>
    <td class="data"><input name="judgingDate" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php if ($action == "edit") echo $row_judging['judgingDate']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Name:</td>
    <td class="data"><input name="judgingLocName" size="30" value="<?php if ($action == "edit") echo $row_judging['judgingLocName']; ?>"></td>
    <td class="data"><span class="required">Required</span> <em>Provide the name of the judging location</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Start Time:</td>
    <td class="data"><input name="judgingTime" size="30" value="<?php if ($action == "edit") echo $row_judging['judgingTime']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Address:</td>
    <td class="data"><textarea name="judgingLocation" cols="40" rows="7" class="mceNoEditor"><?php if ($action == "edit") echo $row_judging['judgingLocation']; ?></textarea></td>
    <td class="data"><span class="required">Required</span> <em>Provide the street address, city, and zip code</em></td>
  </tr>
  <tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" class="button" value="Submit"></td>
        <td class="data">&nbsp;</td>
  	</tr>
</table>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default"); ?>">
</form>