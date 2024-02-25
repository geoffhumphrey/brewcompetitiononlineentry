<?php 
if ($_SESSION['prefsProEdition'] == 1) $query_organizations = sprintf("SELECT brewerAssignment, brewerBreweryName FROM %s WHERE brewerBreweryName IS NOT NULL OR brewerAssignment IS NOT NULL ORDER BY brewerBreweryName ASC", $prefix."brewer");
else $query_organizations = sprintf("SELECT uid, brewerAssignment, brewerLastName, brewerFirstName FROM %s WHERE brewerLastName IS NOT NULL OR brewerAssignment IS NOT NULL ORDER BY brewerLastName ASC", $prefix."brewer");
$organizations = mysqli_query($connection,$query_organizations) or die (mysqli_error($connection));
$row_organizations = mysqli_fetch_assoc($organizations);
$totalRows_organizations = mysqli_num_rows($organizations);

$org_options = "";

$org_array = array();

if ($totalRows_organizations > 0) {

    $affiliated_orgs = "";
    
    if (!empty($row_brewer['brewerAssignment'])) $affiliated_orgs = json_decode($row_brewer['brewerAssignment'],true);

        do {

            if ($_SESSION['prefsProEdition'] == 1) {
                if (!empty($row_organizations['brewerBreweryName'])) $org_array[] = $row_organizations['brewerBreweryName']; 
            }

            else {
                $affiliated_brewer = $row_organizations['brewerFirstName']." ".$row_organizations['brewerLastName'];
                if (!empty($row_organizations['brewerLastName'])) $org_array[] = $affiliated_brewer; 
            }
              
            $org_selected_dropdown = "";
            
            if ($section != "step2") {

                if ((!empty($affiliated_orgs) && (is_array($affiliated_orgs)))) {

                    if ($_SESSION['prefsProEdition'] == 1) {

                        if ((isset($affiliated_orgs['affilliated'])) && (is_array($affiliated_orgs['affilliated']))) {
                            if (in_array($row_organizations['brewerBreweryName'],$affiliated_orgs['affilliated'])) $org_selected_dropdown = "SELECTED";
                        }

                        if ((isset($affiliated_orgs['affilliatedOther'])) && (is_array($affiliated_orgs['affilliatedOther']))) {
                            if (in_array($row_organizations['brewerBreweryName'],$affiliated_orgs['affilliatedOther'])) $org_selected_dropdown = "SELECTED";
                        }

                    }

                    else {

                        if ((isset($affiliated_orgs['affilliated'])) && (is_array($affiliated_orgs['affilliated']))) {

                            if ($row_organizations['uid'] == $_SESSION['user_id']) {
                                $org_selected_dropdown = "DISABLED";
                            }

                            else {
                                if (in_array($affiliated_brewer,$affiliated_orgs['affilliated'])) $org_selected_dropdown = "SELECTED";
                            }
                            
                        }

                        if ((isset($affiliated_orgs['affilliatedOther'])) && (is_array($affiliated_orgs['affilliatedOther']))) {
                            if (in_array($affiliated_brewer,$affiliated_orgs['affilliatedOther'])) $org_selected_dropdown = "SELECTED";
                        }

                    }
                
                }

            }

            if ($_SESSION['prefsProEdition'] == 1) {
                if ((isset($row_organizations['brewerBreweryName'])) && (!empty($row_organizations['brewerBreweryName']))) $org_options .= "<option value=\"".$row_organizations['brewerBreweryName']."\"".$org_selected_dropdown.">".$row_organizations['brewerBreweryName']."</option>\n";
            }
            else {
                if ((isset($row_organizations['brewerLastName'])) && (!empty($row_organizations['brewerLastName']))) $org_options .= "<option value=\"".$affiliated_brewer."\"".$org_selected_dropdown.">".$affiliated_brewer."</option>\n";
            }

        } while($row_organizations = mysqli_fetch_assoc($organizations));

}

$org_other = array();

if ((!empty($affiliated_orgs)) && (!empty($affiliated_orgs['affilliatedOther']))) {
    foreach($affiliated_orgs['affilliatedOther'] as $value) {
        if (!in_array($value,$org_array)) $org_other[] = $value;
    }
}

if (!empty($org_other)) {
    asort($org_other);
    $org_other = implode(",",$org_other);
}

?>