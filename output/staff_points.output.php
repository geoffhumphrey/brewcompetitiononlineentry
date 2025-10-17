<?php

/**
 * Module:      staff_points.output.php
 * Description: This module calculates the BJCP points for staff, judges, and stewards
 *	            using the guidelines provided by the BJCP at https://bjcp.org/about/reference/experience-point-award-schedule/.
 * 
 * Revision History:
 * - fixed point output errors for judges and BOS judges
 * - programming now accounts for multiple roles (e.g., judge/staff, steward/staff, bos judge/staff, etc.)
 * - XML output is fully compliant with the BJCP Database Interface Specifications
 *   -- http://bjcp.org/it/docs/BJCP%20Database%20XML%20Interface%20Spec%202.1.pdf
 * 
 * To figure out judge points, need to assess:
 * - Which sessions the judge was assigned to
 * - Which day those sessions were on
 * - For each day:
 *   - Determine how many sessions the judge was assigned to and award 0.5 points for each
 *   - Make sure that number is a minimum of 1.0 and a maximum of 1.5
 * - Sum up the daily points
 * - Compare that sum to the maximum judge points based upon the table; if more use the max, if less, use the sum
 *
 * To figure out steward points, need to assess:
 * - Which sessions the steward was assigned to
 * - Which day those sessions were on
 * - For each day, determine how many sessions the steward was assigned to and award 0.5 points for each
 * - Sum up the daily points
 * - Compare that sum to the 1.0 maximum possible steward points for the entire competition; if more use maximum, if less use sum
 */

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 0))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

include (LIB.'admin.lib.php');
include (LIB.'output.lib.php');
include (DB.'judging_locations.db.php');
include (DB.'styles.db.php');
include (DB.'admin_common.db.php');
include (DB.'output_staff_points.db.php');

// Get total amount of paid and received entries
$total_entries = total_paid_received("judging_scores",0);
//$total_entries = 750;

$st_running_total = array();

/**
 * Figure out whether BOS Judge Points are awarded or not
 * "BOS points may only be awarded if a competition has at 
 * least 30 entries in at least five beer and/or three 
 * mead/cider categories."
 */

$beer_styles = array();
$mead_styles = array();
$cider_styles = array();

$beer_styles[] = array();
$mead_styles[] = array();
$cider_styles[] = array();

do {

    if (($row_styles2['brewStyleType'] == "Cider") || ($row_styles2['brewStyleType'] == "2")) {
        $beer_styles[] = 0;
        $mead_styles[] = 0;
        $cider_styles[] = 1;
    }

    elseif (($row_styles2['brewStyleType'] == "Mead") || ($row_styles2['brewStyleType'] == "3")) {
        $beer_styles[] = 0;
        $mead_styles[] = 1;
        $cider_styles[] = 0;
    }

    else  {
        $beer_styles[] = 1;
        $mead_styles[] = 0;
        $cider_styles[] = 0;
    }

} while ($row_styles2 = mysqli_fetch_assoc($styles2));

$beer_styles_total = array_sum($beer_styles);
$mead_styles_total = array_sum($mead_styles);
$cider_styles_total = array_sum($cider_styles);

$mead_cider_total = $mead_styles_total + $cider_styles_total;
$all_styles_total = $beer_styles_total + $mead_styles_total + $cider_styles_total;

if (($total_entries >= 30) && (($beer_styles_total >= 5) || ($mead_cider_total >= 3))) $bos_judge_points = 0.5;
else $bos_judge_points = 0.0;

$days = number_format(total_days(),1);
$sessions = number_format(total_sessions(),1);

$output_organizer = "";
$output_judges = "";
$output_stewards = "";
$output_staff = "";
$organ_bjcp_id = "";
$organ_uid = "";

$j = array();
$s = array();
$st = array();

if ($view == "default") {

	if ($totalRows_organizer > 0) {
		
		if ((isset($row_org['brewerJudgeID'])) && (!empty($row_org['brewerJudgeID']))) $organ_bjcp_id = strtoupper(strtr($row_org['brewerJudgeID'],$bjcp_num_replace));

		$organ_uid = $row_org['uid'];
		$org_name = ucwords(strtolower($row_org['brewerLastName'])).", ".ucwords(strtolower($row_org['brewerFirstName']));

		$output_organizer .= "<tr>";
		$output_organizer .= "<td>".$org_name."</td>";
		$output_organizer .= "<td>";
		if (validate_bjcp_id($row_org['brewerJudgeID'])) $output_organizer .= $organ_bjcp_id;
		$output_organizer .= "</td>";
		$output_organizer .= "<td>".$organ_max_points."</td>";
		$output_organizer .= "</tr>";

	}

	if ($totalRows_judges > 0) {

		do { 
			
			$j[] = $row_judges['uid']; 
		
		} while ($row_judges = mysqli_fetch_assoc($judges));

		$j = array_unique($j);

        foreach ($j as $uid) {

        	$judge_info = explode("^",brewer_info($uid));
        	$judge_bjcp_id = "";
			$judge_points = judge_points($uid,$judge_max_points);

			if ($judge_points > 0) {
				
				if (!empty($judge_info['1'])) {

					$judge_name = ucwords(strtolower($judge_info['1'])).", ".ucwords(strtolower($judge_info['0']));
					$bos_judge = bos_points($uid);
					if (!empty($judge_info['4'])) $judge_bjcp_id = strtoupper(strtr($judge_info['4'],$bjcp_num_replace));

					$output_judges .= "<tr>";
					$output_judges .= "<td>".$judge_name."</td>";
					
					$output_judges .= "<td>";
					if ((!empty($judge_bjcp_id)) && (validate_bjcp_id($judge_bjcp_id))) $output_judges .= $judge_bjcp_id;
					$output_judges .= "</td>";
					
					$output_judges .= "<td>";
					if ($uid == $organ_uid) $output_judges .= "0.0 (".$label_organizer.")";
					else {
						if ($bos_judge) $output_judges .= number_format(($judge_points+$bos_judge_points),1);
						else $output_judges .=  $judge_points;
					}
					$output_judges .= "</td>";

					$output_judges .= "<td>";
					if ($bos_judge) $output_judges .= "<span class=\"fa fa-lg fa-check\"></span>";
					else $output_judges .= "&nbsp;";
					$output_judges .= "</td>";

					$output_judges .= "</tr>";

				}

			}

		} // end foreach (array_unique($j) as $uid)

	} // endif ($totalRows_judges > 0)

	foreach (array_unique($bos_judge_no_assignment) as $uid) {

		if (($total_entries >= 30) && (($beer_styles >= 5) || ($mead_cider >= 3))) {
			
			// Best of Show judges criteria
			// "BOS Judges are eligible to receive 0.5 Best-of-Show (BOS) Judge Points if they judge in any BOS panel in a competition."
			// "BOS Judge Points may only be awarded if a competition has at least 30 entries in at least five beer and/or three mead/cider categories."

			$judge_info = explode("^",brewer_info($uid));
			$judge_bjcp_id = "";
            
			if ((!empty($uid)) && (!in_array($uid,$j)) && (!empty($judge_info['1']))) {

				if (!empty($judge_info['4'])) $judge_bjcp_id = strtoupper(strtr($judge_info['4'],$bjcp_num_replace));
				
				$judge_name = ucwords(strtolower($judge_info['1'])).", ".ucwords(strtolower($judge_info['0']));
				if (!empty($judge_info['4'])) $judge_bjcp_id = strtoupper(strtr($judge_info['4'],$bjcp_num_replace));

				$output_judges .= "<tr>";
				$output_judges .= "<td>".$judge_name."</td>";

				$output_judges .= "<td>";
				if ((!empty($judge_bjcp_id)) && (validate_bjcp_id($judge_bjcp_id))) $output_judges .= $judge_bjcp_id;
				$output_judges .= "</td>";
				$output_judges .= "<td>";
				if ($uid == $organ_uid) $output_judges .= "0.0 (".$label_organizer.")";
				else $output_judges .= "1.0";
				$output_judges .= "</td>";
				$output_judges .= "<td>";
				$output_judges .= "<span class=\"fa fa-lg fa-check\"></span>";
				$output_judges .= "</td>";
				$output_judges .= "</tr>";

			}

		}	
	
	}

	if ($totalRows_stewards > 0) {
        
		do { 
			
			$s[] = $row_stewards['uid']; 
		
		} while ($row_stewards = mysqli_fetch_assoc($stewards));

		foreach (array_unique($s) as $uid) {
			
			$steward_points = steward_points($uid);
			$steward_bjcp_id = "";
			
			if ($steward_points > 0) {
				$steward_info = explode("^",brewer_info($uid));
				if (!empty($steward_info['4'])) $steward_bjcp_id = strtoupper(strtr($steward_info['4'],$bjcp_num_replace));
				if (!empty($steward_info['1'])) {
					$steward_name = ucwords(strtolower($steward_info['1'])).", ".ucwords(strtolower($steward_info['0']));
					$output_stewards .= "<tr>";
					$output_stewards .= "<td>".$steward_name."</td>";
					$output_stewards .= "<td>";
					if (!empty($steward_bjcp_id)) $output_stewards .= $steward_bjcp_id;
					else $output_stewards .= "&nbsp;";
					$output_stewards .= "</td>";
					if ($uid == $organ_uid) $output_stewards .= "<td>0.0 (".$label_organizer.")</td>";
					else $output_stewards .= "<td>".$steward_points."</td>";
					$output_stewards .= "</tr>";
				}
			}

		}

	}

	if ($totalRows_staff > 0) {
        
		do {

			$st[] = $row_staff['uid']; 
		
		} while ($row_staff = mysqli_fetch_assoc($staff));
		
		$st_running_total = 0;
		
		foreach (array_unique($st) as $uid) {
				
			$staff_info = explode("^",brewer_info($uid));
			
			if (!empty($staff_info['1'])) {
				
				$staff_name = ucwords(strtolower($staff_info['1'])).", ".ucwords(strtolower($staff_info['0']));
				$staff_bjcp_id = "";
				if (!empty($staff_info['4'])) $staff_bjcp_id = strtoupper(strtr($staff_info['4'],$bjcp_num_replace));

				if ($st_running_total <= $staff_max_points) {

					$output_staff .= "<tr>";
					$output_staff .= "<td>".$staff_name."</td>";
					$output_staff .= "<td>";
					if ((!empty($staff_bjcp_id)) && (validate_bjcp_id($staff_bjcp_id))) $output_staff .= $staff_bjcp_id;
					$output_staff .= "</td>";
					$output_staff .= "<td>";
					if ($uid == $organ_uid) $output_staff .= "0.0 (".$label_organizer.")";
					else {
						if (($st_running_total <= $staff_max_points) && ($staff_points < $organ_max_points)) $output_staff .= $staff_points;
						else $output_staff .= $organ_max_points;
					}
					$output_staff .= "</td>";
					$output_staff .= "</tr>";

					$st_running_total += $staff_points;

				}
					
			}

		}

	} // end if ($totalRows_staff > 0)

?>
	
	<div class="page-header">
        	<h1><?php echo sprintf("%s %s",$_SESSION['contestName'],$output_text_024); ?> </h1>
    </div>
    <p class="lead"><?php echo sprintf("%s %s.",$output_text_022,"https://www.bjcp.org/competitions/rules-regulations/"); ?></p>
    <ul class="list-unstyled">
        <li><?php echo sprintf("<strong>%s:</strong> %s",$label_comp_id,$_SESSION['contestID']); ?></li>
        <li><?php echo sprintf("<strong>%s:</strong> %s",$label_entries,$total_entries_received); ?></li>
        <li><?php echo sprintf("<strong>%s:</strong> %s",$label_days,total_days()); ?></li>
        <li><?php echo sprintf("<strong>%s:</strong> %s",$label_sessions,total_sessions()); ?></li>
        <li><?php echo sprintf("<strong>%s:</strong> %s (%s)",$label_flights,total_flights(),$output_text_023); ?></li>
    </ul>
    <?php if (!empty($output_organizer)) { ?>
    <h2><?php echo $label_organizer; ?></h2>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#organizer_table').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable table table-striped table-bordered" id="organizer_table">
    <thead>
    <tr>
    	<th width="35%"><?php echo $label_name; ?></th>
        <th width="10%"><?php echo $label_bjcp_id; ?></th>
        <th><?php echo $label_points; ?></th>
    </tr>
    </thead>
    <tbody>
    <?php echo $output_organizer; ?>
    </tbody>
    </table>
    <?php }	?>
    <?php if (!empty($output_judges)) { ?>
    <h2><?php echo $label_judges; ?></h2>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#judges_table').dataTable( {
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
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable table table-striped table-bordered" id="judges_table">
    <thead>
    <tr>
    	<th width="35%"><?php echo $label_name; ?></th>
        <th width="10%"><?php echo $label_bjcp_id; ?></th>
        <th width="10%"><?php echo $label_points; ?></th>
        <th><?php echo $label_bos; ?></th>
    </tr>
    </thead>
    <tbody>
    <?php echo $output_judges; ?>
    </tbody>
    </table>
    <?php } ?>
	<?php if (!empty($output_stewards)) { ?>
    <h2><?php echo $label_stewards; ?></h2>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#stewards_table').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable table table-striped table-bordered" id="stewards_table">
    <thead>
    <tr>
    	<th width="35%"><?php echo $label_name; ?></th>
        <th width="10%"><?php echo $label_bjcp_id; ?></th>
        <th><?php echo $label_points; ?></th>
    </tr>
    </thead>
    <tbody>
    <?php echo $output_stewards; ?>
    </tbody>
    </table>
    <?php } ?>
    <?php if (!empty($output_staff)) { ?>
    <h2><?php echo $label_staff; ?></h2>
    <p><?php echo sprintf("%s: %s",$output_text_025,$staff_max_points); ?></p>
    <?php if ($st_running_total > $staff_max_points) echo sprintf("<p><em>%s</em></p>",$output_text_033); ?>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#staff_table').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable table table-striped table-bordered" id="staff_table">
    <thead>
    <tr>
    	<th width="35%"><?php echo $label_name; ?></th>
        <th width="10%"><?php echo $label_bjcp_id; ?></th>
        <th><?php echo $label_points; ?></th>
    </tr>
    </thead>
    <tbody>
    <?php echo $output_staff; ?>
    </tbody>
    </table>
    <?php }	?>
	</div>
</div>
</body>
</html>
<?php } // end if ($view == "default") ?>