<?php 
require ('../Connections/config.php');
include ('../includes/url_variables.inc.php'); 
$query_brewer = "SELECT * FROM brewer WHERE id='$id'";
mysql_select_db($database, $brewing);
$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
$row_brewer = mysql_fetch_assoc($brewer);
$totalRows_brewer = mysql_num_rows($brewer);

switch ($row_brewer['brewerJudgeRank']) {
	case "": 
	case "Experienced":
	case "None":
		$rank = "Experienced Judge";
	break;
	case "Professional Brewer":
		$rank = $row_brewer['brewerJudgeRank'];
	break;
	default:
		$rank = "BJCP ".$row_brewer['brewerJudgeRank']." Judge"; if (($row_brewer['brewerJudgeID'] != "") || ($row_brewer['brewerJudgeID'] != "0")) $rank .= "&nbsp;&nbsp;&nbsp;".$row_brewer['brewerJudgeID'];
	break;
	
}
/*
To print properly on Avery 5160 label sheets, the margins must be set to:

---- FireFox 3+ ----
Top: 0.2 in
Right: 0 in
Left: 0 in
Bottom: 0 in
NO header or footer items


*/

?>
<html xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<title>Judge Scoresheet Labels for <?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></title>
<style>
<!--
body {
	line-height: .85;
}
.name {
	font-size:9.0pt;
	font-family: "Arial", "sans-serif";
	font-weight: bold;
}
.rank, .address {
	font-size: 8.0pt;
	font-family: "Arial", "sans-serif";
}

.label {
	width: 254px;
	height: auto;
}

.label_table {
	border-collapse: collapse;
	padding: 0;
	margin: 0;
	border: 0;
}

.label_inner {
	margin: 32px 25px 20px 25px;	
}

.label_inner_mid {
	margin: 36px 25px 20px 25px;	
}

.label_inner_bottom {
	margin: 32px 25px 20px 25px;	
}

.spacer {
	width: 12px;
}
-->
</style>
</head>
<body>
  <table class="label_table">
    <tr>
      <td class="label">
  		<p class="label_inner"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
    </tr>
    <tr>
      <td class="label">
  		<p class="label_inner"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
    </tr>
    <tr>
      <td class="label">
  		<p class="label_inner"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
    </tr>
    <tr>
      <td class="label">
  		<p class="label_inner_mid"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td width=256>
  		<p class="label_inner_mid"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner_mid"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
    </tr>
   <tr>
      <td class="label">
  		<p class="label_inner_mid"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td width=256>
  		<p class="label_inner_mid"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner_mid"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
    </tr>
    <tr>
      <td class="label">
  		<p class="label_inner_mid"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td width=256>
  		<p class="label_inner_mid"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner_mid"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
    </tr>
    <tr>
      <td class="label">
  		<p class="label_inner_mid"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td width=256>
  		<p class="label_inner_mid"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner_mid"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
    </tr>
    <tr>
      <td class="label">
  		<p class="label_inner_bottom"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner_bottom"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner_bottom"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
    </tr>
    <tr>
      <td class="label">
  		<p class="label_inner_bottom"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner_bottom"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner_bottom"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
    </tr>
    <tr>
      <td class="label">
  		<p class="label_inner_bottom"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner_bottom"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
      <td class="spacer">&nbsp;</td>
      <td class="label">
  		<p class="label_inner_bottom"><span class="name"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></span><br><span class="rank"><?php echo $rank." ".$judge_id; ?><br></span><span class="address"><?php echo $row_brewer['brewerEmail']; ?></span></p>
      </td>
    </tr>
  </table>
</body>
</html>
