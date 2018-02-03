<style>
.half-sheet {
	height: 425px;
}

.spacer {
	height: 75px;
}

.lead {
	padding: 0;
	margin: 0;
}

h3 {
	margin: 0 1em 0 0;
	padding: 0;
}
</style>

<div class="container-fluid">
	<div class="half-sheet">
        <div class="row">
            <div class="col-md-5">
                <p class="lead">	<?php if (isset($_SESSION['brewerBreweryName'])) echo $_SESSION['brewerBreweryName']."<br>";
                    echo $_SESSION['brewerFirstName']." ".$_SESSION['brewerLastName']; ?></p>
                <p><?php
                echo $_SESSION['brewerAddress']."<br>".$_SESSION['brewerCity'].", ".$_SESSION['brewerState']." ".$_SESSION['brewerZip'];
                if ($_SESSION['brewerCountry'] != "United States") echo "<br>".$_SESSION['brewerCountry'];
                ?>
            </div>
            <div class="col-md-7">
            </div>
        </div>
        <div class="spacer"></div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-9">
                <h3><?php echo $_SESSION['contestShippingName']; ?></h3>
                <p class="lead">	<?php echo $_SESSION['contestShippingAddress']; ?></p>
            </div>
        </div>
    </div>
<hr>
	<div class="half-sheet">
        <div class="row">
            <div class="col-md-5">
                <p class="lead">	<?php if (isset($_SESSION['brewerBreweryName'])) echo $_SESSION['brewerBreweryName']."<br>";
                    echo $_SESSION['brewerFirstName']." ".$_SESSION['brewerLastName']; ?></p>
                <p><?php
                echo $_SESSION['brewerAddress']."<br>".$_SESSION['brewerCity'].", ".$_SESSION['brewerState']." ".$_SESSION['brewerZip'];
                if ($_SESSION['brewerCountry'] != "United States") echo "<br>".$_SESSION['brewerCountry'];
                ?>
            </div>
            <div class="col-md-7">
            </div>
        </div>
        <div class="spacer"></div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-9">
                <h3><?php echo $_SESSION['contestShippingName']; ?></h3>
                <p class="lead">	<?php echo $_SESSION['contestShippingAddress']; ?></p>
            </div>
        </div>
    </div>
</div>