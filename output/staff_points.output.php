<?php

/**
 * Module:      staff_points.php
 * Description: This module calculates the BJCP points for staff, judges, and stewards
 *	            using the guidelines provided by the BJCP at http://www.bjcp.org/rules.php.
 * Revision History:
 * - fixed point output errors for judges and BOS judges
 * - programming now accounts for multiple roles (e.g., judge/staff, steward/staff, bos judge/staff, etc.)
 * - XML output is fully compliant with the BJCP Database Interface Specifications
 *   -- http://www.bjcp.org/it/docs/BJCP%20Database%20XML%20Interface%20Spec%202.1.pdf
 */

require (DB.'judging_locations.db.php');
require (DB.'styles.db.php');
require (DB.'admin_common.db.php');
require (LIB.'output.lib.php');
require (DB.'output_staff_points.db.php');
// Get total amount of paid and received entries
$total_entries = total_paid_received("judging_scores","default");
//$total_entries = 750;

// Figure out whether BOS Judge Points are awarded or not
// "BOS points may only be awarded if a competition has at least 30 entries in at least five beer and/or three mead/cider categories."
$beer_styles = array();
$mead_styles = array();
$cider_styles = array();
$beer_styles[] = 0;
$mead_styles[] = 0;
$cider_styles[] = 0;

do {

	if ($row_styles2['brewStyleType'] = "Cider") { $beer_syles[] = 0; $mead_styles[] = 0; $cider_styles[] = 1; }
	elseif ($row_styles2['brewStyleType'] = "Mead") { $beer_syles[] = 0; $mead_styles[] = 1; $cider_styles[] = 0; }
	else { $beer_syles[] = 1; $mead_styles[] = 0; $cider_styles[] = 0; }

} while ($row_styles2 = mysqli_fetch_assoc($styles2));

$beer_styles_total = array_sum($beer_styles);
$mead_styles_total = array_sum($mead_styles);
$cider_styles_total = array_sum($cider_styles);
$mead_cider_total = $mead_styles_total+$cider_styles_total;

if (($total_entries >= 30) && (($beer_styles_total >= 5) || ($mead_cider_total >= 3))) $bos_judge_points = 0.5;
else $bos_judge_points = 0.0;

do { $a[] = $row_judging['id']; } while ($row_judging = mysqli_fetch_assoc($judging));

$days = number_format(total_days(),1);
$sessions = number_format(total_sessions(),1);

if ($view == "default") {
	$output_organizer = "";
	$output_judges = "";
	$output_stewards = "";
	$output_staff = "";

	if ($totalRows_organizer > 0) {
		$org_name = ucwords(strtolower($row_org['brewerLastName'])).", ".ucwords(strtolower($row_org['brewerFirstName']));
		$output_organizer .= "<tr>";
		$output_organizer .= "<td>".$org_name."</td>";
		$output_organizer .= "<td>";
		if (validate_bjcp_id($row_org['brewerJudgeID'])) $output_organizer .= strtoupper(strtr($row_org['brewerJudgeID'],$bjcp_num_replace));
		$output_organizer .= "</td>";
		$output_organizer .= "<td>".$organ_max_points."</td>";
		$output_organizer .= "</tr>";
	}

	if ($totalRows_judges > 0) {
        $j = array();

		do { $j[] = $row_judges['uid']; } while ($row_judges = mysqli_fetch_assoc($judges));

        foreach (array_unique($j) as $uid) {

            unset($b);

            $judge_info = explode("^",brewer_info($uid));
			// $judge_points = judge_points($uid,$judge_info['5'],$judge_max_points);

            foreach (array_unique($a) as $location) {

                $query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE bid='%s' AND assignLocation='%s' AND assignment='J'", $prefix."judging_assignments", $uid, $location);
                $assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
                $row_assignments = mysqli_fetch_assoc($assignments);

                // 0.5 points per session
                $number = $row_assignments['count'] * 0.5;
                if ($number > 0.5) $b[] = 0.5;
                else $b[] = $number;

            }

            $points = array_sum($b);

            // Minimum of 0.5 points per session
            // "Judges earn points at a rate of 0.5 Judge Points per session."
            $max_comp_points = ($sessions * 0.5);

            // Cannot exceed more than 1.5 points per *day*
            // "Judges earn a maximum of 1.5 Judge Points per day."
            if ($points > ($days * 1.5)) $points = ($days * 1.5);
            else $points = $points;

            // Cannot exceed the maximum amount of points possible for the entire competition
            if ($points > $max_comp_points) $points = $max_comp_points;
            else $points = $points;

            // Cannot exceed the maximum allowable points for judges for the competition
            if ($points > $judge_max_points) $points = $judge_max_points;
            else $points = $points;

            // If points are below the 1.0 minimum, award minimum
            if ($points < 1) $points = 1;
            else $points = $points;

            $judge_points = number_format($points,1);

			if ($judge_points > 0) {
				if (!empty($judge_info['1'])) {
					$judge_name = ucwords(strtolower($judge_info['1'])).", ".ucwords(strtolower($judge_info['0']));
					$bos_judge = bos_points($uid);
					$output_judges .= "<tr>";
					$output_judges .= "<td>".$judge_name."</td>";
					$output_judges .= "<td>";
					if (validate_bjcp_id($judge_info['4'])) $output_judges .= strtoupper(strtr($judge_info['4'],$bjcp_num_replace));
					$output_judges .= "</td>";
					$output_judges .= "<td>";
					if ($bos_judge) $output_judges .= number_format(($judge_points+$bos_judge_points),1);
					else $output_judges .=  $judge_points;
					$output_judges .= "</td>";
					$output_judges .= "<td>";
					if ($bos_judge) $output_judges .= "<span class=\"fa fa-lg fa-check\"></span>";
					else $output_judges .= "&nbsp;";
					$output_judges .= "</td>";
					$output_judges .= "</tr>";
				}
			}
		}

		foreach (array_unique($bos_judge_no_assignment) as $uid) {
			$judge_info = explode("^",brewer_info($uid));
			if ((!empty($uid)) && (!empty($judge_info['1']))) {
				$judge_name = ucwords(strtolower($judge_info['1'])).", ".ucwords(strtolower($judge_info['0']));

				$output_judges .= "<tr>";
				$output_judges .= "<td>".$judge_name."</td>";
				$output_judges .= "<td>";
				if (validate_bjcp_id($judge_info['4'])) $output_judges .= strtoupper(strtr($judge_info['4'],$bjcp_num_replace));
				$output_judges .= "</td>";
				$output_judges .= "<td>";
				$output_judges .= "1.0";
				$output_judges .= "</td>";
				$output_judges .= "<td>";
				$output_judges .= "<span class=\"fa fa-lg fa-check\"></span>";
				$output_judges .= "</td>";
				$output_judges .= "</tr>";
			}
		}
	}

	if ($totalRows_stewards > 0) {
        $s = array();
		do { $s[] = $row_stewards['uid']; } while ($row_stewards = mysqli_fetch_assoc($stewards));

		foreach (array_unique($s) as $uid) {
			$steward_points = steward_points($uid);
			if ($steward_points > 0) {
				$steward_info = explode("^",brewer_info($uid));
				if (!empty($steward_info['1'])) {
					$steward_name = ucwords(strtolower($steward_info['1'])).", ".ucwords(strtolower($steward_info['0']));
					$output_stewards .= "<tr>";
					$output_stewards .= "<td>".$steward_name."</td>";
					$output_stewards .= "<td>";
					if (validate_bjcp_id($steward_info['4'])) $output_stewards .= strtoupper(strtr($steward_info['4'],$bjcp_num_replace));
					else $output_staff .= "&nbsp;";
					$output_stewards .= "</td>";
					$output_stewards .= "<td>".steward_points($uid)."</td>";
					$output_stewards .= "</tr>";
				}
			}

		}

	}

	if ($totalRows_staff > 0) {
        $st = array();
		do { $st[] = $row_staff['uid']; } while ($row_staff = mysqli_fetch_assoc($staff));
		$st_running_total[] = "";
		foreach (array_unique($st) as $uid) {

			if (array_sum($st_running_total) < $staff_max_points) {
				$staff_info = explode("^",brewer_info($uid));
				$st_running_total[] = $staff_points;

				if (!empty($staff_info['1'])) {
					$staff_name = ucwords(strtolower($staff_info['1'])).", ".ucwords(strtolower($staff_info['0']));

					$output_staff .= "<tr>";
					$output_staff .= "<td>".$staff_name."</td>";
					$output_staff .= "<td>";
					if (validate_bjcp_id($staff_info['4'])) $output_staff .= strtoupper(strtr($staff_info['4'],$bjcp_num_replace));

					$output_staff .= "</td>";
					$output_staff .= "<td>";
					if ((array_sum($st_running_total) <= $staff_max_points) && ($staff_points < $organ_max_points)) $output_staff .= $staff_points;
					else $output_staff .= $organ_max_points;
					$output_staff .= "</td>";
					$output_staff .= "</tr>";
				}
			}
		}
	} // end if ($totalRows_staff > 0)

?>
	<div class="page-header">
        	<h1><?php echo sprintf("%s %s",$_SESSION['contestName'],$output_text_024); ?> </h1>
    </div>
    <p class="lead"><?php echo sprintf("%s %s.",$output_text_022,"http://www.bjcp.org/rules.php"); ?></p>
    <ul class="list-unstyled">
        <li><?php echo sprintf("<strong>%s:</strong> %s",$label_comp_id,$_SESSION['contestID']); ?></li>
        <li><?php echo sprintf("<strong>%s:</strong> %s",$label_entries,$total_entries); ?></li>
        <li><?php echo sprintf("<strong>%s:</strong> %s",$label_days,total_days()); ?></li>
        <li><?php echo sprintf("<strong>%s:</strong> %s",$label_sessions,total_sessions()); ?></li>
        <li><?php echo sprintf("<strong>%s:</strong> %s (%s)",$label_flights,total_flights(),$output_text_023); ?></li>
    </ul>
    <?php if (!empty($output_organizer)) { ?>
    <h2><?php echo $label_organizer; ?></h2>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable0').dataTable( {
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
    <table class="dataTable table table-striped table-bordered" id="sortable0">
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
		$('#sortable1').dataTable( {
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
    <table class="dataTable table table-striped table-bordered" id="sortable1">
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
		$('#sortable2').dataTable( {
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
    <table class="dataTable table table-striped table-bordered" id="sortable2">
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
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable99').dataTable( {
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
    <table class="dataTable table table-striped table-bordered" id="sortable99">
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
<?php } // end if ($view == "print") ?>