<?php 
if ($_SESSION['prefsProEdition'] == 1) {
    $db_conn->where('brewerBreweryName', NULL, 'IS NOT');
    $db_conn->orWhere('brewerAssignment', NULL, 'IS NOT');
    $db_conn->orderBy('brewerBreweryName', 'ASC');
    $rows_organizations = $db_conn->get($prefix."brewer", null, "brewerAssignment, brewerBreweryName");
}
else {
    $db_conn->where('brewerLastName', NULL, 'IS NOT');
    $db_conn->orWhere('brewerAssignment', NULL, 'IS NOT');
    $db_conn->orderBy('brewerLastName', 'ASC');
    $rows_organizations = $db_conn->get($prefix."brewer", null, "uid, brewerAssignment, brewerLastName, brewerFirstName");
}
$totalRows_organizations = $db_conn->count;

$org_options = "";

$org_array = array();

if ($totalRows_organizations > 0) {

    $affiliated_orgs = "";
    
    if (!empty($row_brewer['brewerAssignment'])) $affiliated_orgs = json_decode($row_brewer['brewerAssignment'],true);

        foreach ($rows_organizations as $row_organizations) {

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
                if ((isset($row_organizations['brewerBreweryName'])) && (!empty($row_organizations['brewerBreweryName']))) {
                    $org_name_safe = h(html_entity_decode($row_organizations['brewerBreweryName'], ENT_QUOTES, 'UTF-8'));
                    $org_options .= "<option value=\"".$org_name_safe."\"".$org_selected_dropdown.">".$org_name_safe."</option>\n";
                }
            }
            else {
                if ((isset($row_organizations['brewerLastName'])) && (!empty($row_organizations['brewerLastName']))) {
                    $org_name_safe = h(html_entity_decode($affiliated_brewer, ENT_QUOTES, 'UTF-8'));
                    $org_options .= "<option value=\"".$org_name_safe."\"".$org_selected_dropdown.">".$org_name_safe."</option>\n";
                }
            }

        }

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