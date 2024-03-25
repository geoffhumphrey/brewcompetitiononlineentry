<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

/**
 * Module:      report_tempate.php
 * Description: Template for custom reports.
 *
 */

include (DB.'output_post_judge_inventory.db.php');

if (NHC) $base_url = "../";
if ($_SESSION['prefsStyleSet'] == "BA") include (INCLUDES.'ba_constants.inc.php');
?>

<script type="text/javascript" language="javascript">
// The following is for demonstration purposes only.
// Complete documentation and usage at http://www.datatables.net
	$(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[3,'asc']],
			"aoColumns": [
				<?php if ($go == "scores") { ?>null,<?php } ?>
				{ "asSorting": [  ] },
					{ "asSorting": [  ] },
					{ "asSorting": [  ] },
					{ "asSorting": [  ] },
					{ "asSorting": [  ] }
				]
		} );
	} );
</script>
    <div class="page-header">
		<h1><?php echo sprintf("%s %s",$_SESSION['contestName'],$output_text_016); ?></h1>
	</div><!-- end header -->
    <!-- BEGIN content -->
    <!-- DataTables Table Format -->
    <table class="table table-striped table-bordered" id="sortable">
    <thead>
    	<tr>
        	<th width="5%" nowrap><?php echo $label_entry; ?></th>
            <th width="5%" nowrap><?php echo $label_judging; ?></th>
            <th><?php echo $label_name; ?></th>
            <th width="25%"><?php echo $label_style; ?></th>
            <th width="40%"><?php echo $label_required_info; ?></th>
            <?php if ($go == "scores") { ?>
            <th width="5%" nowrap><?php echo $label_score; ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
    <?php 

    do {

		include (DB.'output_post_judge.db.php');
		if ((($totalRows_post_inventory_entry > 0) && ($row_post_inventory_entry['scorePlace'] == "")) || ($totalRows_post_inventory_entry == 0)) {

	?>
    	<tr>
        	<td><?php echo sprintf("%06s",$row_post_inventory['id']); ?></td>
            <td><?php echo readable_judging_number($row_post_inventory['brewCategory'],$row_post_inventory['brewJudgingNumber']); ?></td>
            <td>
                <?php 
                $entry_name = html_entity_decode($row_post_inventory['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
                $entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");
                echo $entry_name; 
                ?>
            </td>
            <td>
                <?php if ($_SESSION['prefsStyleSet'] == "BA") echo $ba_category_names[$row_post_inventory['brewCategory']].": ".$row_post_inventory['brewStyle'];
                else echo $row_post_inventory['brewCategorySort'].$row_post_inventory['brewSubCategory'].": ".$row_post_inventory['brewStyle']; ?>
            </td>
            <td>
                <?php
                echo str_replace("^"," | ",$row_post_inventory['brewInfo'])." ";
                if (!empty($row_post_inventory['brewMead1'])) echo "*".$row_post_inventory['brewMead1']."* ";
                if (!empty($row_post_inventory['brewMead2'])) echo "*".$row_post_inventory['brewMead2']."* ";
                if (!empty($row_post_inventory['brewMead3'])) echo "*".$row_post_inventory['brewMead3']."*";
                ?>

            </td>
            <?php if ($go == "scores") { ?>
            <td><?php 
            if (isset($row_post_inventory_entry['scoreEntry'])) {
                if (strpos($row_post_inventory_entry['scoreEntry'], '.') !== false) echo rtrim(number_format($row_post_inventory_entry['scoreEntry'],2),"0"); else echo $row_post_inventory_entry['scoreEntry'];
            } ?></td>
            <?php } ?>
        </tr>
    <?php
		}
	} while ($row_post_inventory = mysqli_fetch_assoc($post_inventory)); ?>
    </tbody>
    </table>