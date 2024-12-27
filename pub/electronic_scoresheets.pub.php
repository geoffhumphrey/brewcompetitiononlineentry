<?php
if (($view == "admin") && ($filter == "default")) $container_eval = "container-fluid";
else $container_eval = $container_main;
?>
<!-- Electronic Scoresheets -->
<div class="<?php echo $container_eval; ?>">
    <div class="page-header">
            <h1><?php echo $header_output; ?></h1>
        </div>
    <?php 
        if ($go == "default") include (EVALS.'default.eval.php');
        if ($go == "scoresheet") include (EVALS.'scoresheet.eval.php');
    ?>
</div>