/**
 * -------- Save Functions --------
 */

$("#alert-update-button-enabled").hide();

function generic_success(element_id,remove,msg) {
    
    $("#"+element_id+"-status").attr("class", "fa fa-lg fa-check text-success");
    $("#"+element_id+"-status-msg").html(msg);
    $("#"+element_id+"-status-msg").attr("class", "text-success small");
    
    // If remove var is zero will stay visible in DOM
    if (remove == 0) {
        $("#"+element_id+"-status").show();
        $("#"+element_id+"-status-msg").show(); 
    }

    // If the remove var is greater than zero, elements hide after X seconds
    if (remove > 0) {
        $("#"+element_id+"-status").show().delay(remove).hide(100);
        $("#"+element_id+"-status-msg").show().delay(remove).hide(100); 
    }
    
}

function generic_failure(element_id,remove,msg) {
    
    $("#"+element_id+"-status").attr("class", "fa fa-lg fa-times text-danger");
    $("#"+element_id+"-status-msg").html(msg);
    $("#"+element_id+"-status-msg").attr("class", "text-danger small");
    
    // If remove var is zero will stay visible in DOM
    if (remove == 0) {
        $("#"+element_id+"-status").show();
        $("#"+element_id+"-status-msg").show(); 
    }

    // If the remove var is greater than zero, elements hide after X seconds
    if (remove > 0) {
        $("#"+element_id+"-status").show().delay(remove).hide(100);
        $("#"+element_id+"-status-msg").show().delay(remove).hide(100); 
    }

}

function generic_session_expired(element_id,remove,msg) {
    
    $("#"+element_id+"-status").attr("class", "fa fa-lg fa-exclamation-triangle text-danger");
    $("#"+element_id+"-status-msg").html(msg);
    $("#"+element_id+"-status-msg").attr("class", "text-danger small");
    
    // If remove var is zero will stay visible in DOM
    if (remove == 0) {
        $("#"+element_id+"-status").show();
        $("#"+element_id+"-status-msg").show(); 
    }

    // If the remove var is greater than zero, elements hide after X seconds
    if (remove > 0) {
        $("#"+element_id+"-status").show().delay(remove).hide(100);
        $("#"+element_id+"-status-msg").show().delay(remove).hide(100); 
    }
    
}

function disable_update_button(action) {
    $("#"+action+"-update-button-disabled").show();
    $("#"+action+"-update-button-enabled").hide();
    $("#"+action+"-submit").prop("disabled", true);
    $("#alert-update-button-enabled").hide();
}

function enable_update_button(action) {
    $("#"+action+"-submit").prop("disabled", false);
    $("#"+action+"-update-button-disabled").hide();
    $("#"+action+"-update-button-enabled").show();
    $("#alert-update-button-enabled").show();
}

function save_failure(element_id,column,action,error) {
    // Error codes:
    // 0 = General error
    // 1 = Numbers only
    // 2 = Alpha only
    // 3 = SQL save error

    // Enable the "update all" button if there's an issue saving. Failsafe so users will not lose data.
    enable_update_button(action);

         if (error == 1) $("#"+element_id+"-"+column+"-status-msg").html("Error. Invalid numerical input.");
    else if (error == 2) $("#"+element_id+"-"+column+"-status-msg").html("Error. Alpha characters only.");
    else if (error == 3) $("#"+element_id+"-"+column+"-status-msg").html("Error saving. Please try again.");
    else $("#"+element_id+"-"+column+"-status-msg").html("Error.");
    
    $("#"+element_id+"-"+column+"-status").attr("class", "fa fa-lg fa-times text-danger");
    $("#"+element_id+"-"+column+"-status-msg").attr("class", "text-danger small");
    $("#"+element_id+"-"+column+"-form-group").removeClass("has-error"); // just in case failure happened before
    $("#"+element_id+"-"+column+"-form-group").removeClass("has-success"); // just in case success has happend before
    $("#"+element_id+"-"+column+"-form-group").addClass("has-error");
    $("#"+element_id+"-"+column+"-status").show();
    $("#"+element_id+"-"+column+"-status-msg").show();

    // $("#"+element_id+"-"+column+"-status").attr("class", "label label-danger");

};

function save_success(element_id,column,action,error) {

    disable_update_button(action);

    $("#"+element_id+"-"+column+"-status").attr("class", "fa fa-lg fa-check text-success");
    $("#"+element_id+"-"+column+"-status-msg").html("Saved");
    $("#"+element_id+"-"+column+"-status-msg").attr("class", "text-success small");
    $("#"+element_id+"-"+column+"-form-group").removeClass("has-error"); // just in case failure happened before
    $("#"+element_id+"-"+column+"-form-group").removeClass("has-success"); // just in case success has happend before
    $("#"+element_id+"-"+column+"-form-group").addClass("has-success");
    $("#"+element_id+"-"+column+"-status").show();
    $("#"+element_id+"-"+column+"-status-msg").show();

    // $("#"+element_id+"-"+column+"-status").attr("class", "label label-success");
    
};

// Save a single column (data point)
function save_column(base_url,column,action,id,rid1,rid2,rid3,rid4,element_id) {

    /**
     * base_url   = site URL - global php var
     * action     = database table ("judging_scores", "brewing", etc.)
     * id         = the primary identification item
	 * rid1       = first relational id (optional)
	 * rid2       = second relational id (optional)
     * rid3       = third relational id (optional)
     * rid4       = fourth relational id (optional)
     * element_id = the html id of the element being acted upon
     */

    var value = $("#"+element_id).val();

    $("#"+element_id+"-"+column+"-status").attr("class", "fa fa-lg fa-cog fa-spin text-muted");
    $("#"+element_id+"-"+column+"-status-msg").html("Processing...");
    $("#"+element_id+"-"+column+"-status-msg").attr("class", "text-muted small");
    $("#"+element_id+"-"+column+"-status").show();
    $("#"+element_id+"-"+column+"-status-msg").show();

	let url = base_url+"ajax/save.ajax.php?action="+action+"&go="+column+"&id="+id;
	if (rid1 != "default") url += "&rid1="+rid1;
	if (rid2 != "default") url += "&rid2="+rid2;
    if (rid3 != "default") url += "&rid3="+rid3;
    if (rid4 != "default") url += "&rid4="+rid4;
	
    var data_const = column+"="+value;

    /*
    console.log(url);
    console.log(data_const);
    */

    jQuery.ajax({

        url: url,
        data: data_const,
        type: "POST",
        
        success:function(data) {

            var jsonData = JSON.parse(data);
            
            /*
            console.log(jsonData.status);
            console.log(jsonData.query);
            console.log(jsonData.post);
            console.log(jsonData.input);
            */

            // Error, score not recorded
            if (jsonData.status === "0") {
                // console.log('fail!');
                save_failure(element_id,column,action,jsonData.error_type);
            }

            // No error, score recorded
            if (jsonData.status === "1") {
                // console.log('success!');
                $("#"+element_id).val(jsonData.input);
                save_success(element_id,column,action,jsonData.error_type);
            }

            // Session expired
            if (jsonData.status === "9") {
                // console.log('session expired!');
                generic_session_expired(element_id,'0','Session expired. Sign in and try again.');
            }
            
        },

        error:function() {
            console.log("save_score error");
            save_failure(element_id,column,action,"99");
        },

    });

};

function select_place(base_url,column,action,id,rid1,rid2,rid3,rid4,element_id) {
    
    /**
     * Check if a place has been entered on all other select elements.
     * If so, compare to the current selection.
     *
     * If the value of the current selection is not null or empty, and 
     * the value has already been chosen, show BS modal and clear the
     * selection.
     *
     * Still need to save if input is blank, however.
     */
    
    if (($('select option[value="' + $('#'+element_id).val() + '"]:selected').length > 1) && $('select option[value="' + $('#'+element_id).val() + '"]:selected') !== "") {
        if ($('#'+element_id).val() !== "") {
            $('#'+element_id).val('-1').change();
            $('#noDupeModal').modal('show');
        } 
    }

    else {
        save_column(base_url,column,action,id,rid1,rid2,rid3,rid4,element_id);
    }

    if ($('#'+element_id).val() === "") {
        save_column(base_url,column,action,id,rid1,rid2,rid3,rid4,element_id);
    }
        
};

/**
 * -------- Regenerate Judging Number Functions --------
 */

function regenerate_judging_numbers(base_url,action,source,element_id) {

    /**
     * base_url   = site URL - global php var
     * action     = type of regeneration ("random", "brewing", etc.)
     * source     = where the action originated from ("admin", "entries", etc.)
     * element_id = the html id of the element being acted upon
     */
    
    $("#"+element_id+"-status").attr("class", "fa fa-lg fa-cog fa-spin text-muted");
    $("#"+element_id+"-status-msg").html("Processing...");
    $("#"+element_id+"-status-msg").attr("class", "text-muted small");
    $("#"+element_id+"-status").show();
    $("#"+element_id+"-status-msg").show();

    var url =  base_url+"ajax/regenerate.ajax.php?action="+action;

     jQuery.ajax({

        url:url,
        
        success:function(data) {

            var jsonData = JSON.parse(data);

            /*
            console.log(url);
            console.log(jsonData.status);
            console.log(jsonData.action); 
            console.log(jsonData.query);
            console.log(jsonData.post);
            console.log(jsonData.input);
            */

            // Error, numbers not regenerated
            if (jsonData.status === "0") {
                
                // console.log('fail!');
                // If the action was initiated on the admin dashboard screen,
                // display a simple notification.
                if (source == "admin-dashboard") {
                    generic_failure(element_id,'8000','There was an error, please try again.');
                }

            }

            // No error
            if (jsonData.status === "1") {
                
                // console.log('success!');
                // If the action was initiated on the admin dashboard screen,
                // display a simple notification.
                if (source == "admin-dashboard") {
                    generic_success(element_id,'8000','Regenerated successfully.');
                }

            }

            // Session expired
            if (jsonData.status === "9") {
                
                // console.log('session expired!');
                // If the action was initiated on the admin dashboard screen,
                // display a simple notification.
                if (source == "admin-dashboard") {
                    generic_session_expired(element_id,'0','Session expired. Sign in and try again.');
                }

            }
            
        },

        error:function() {
            console.log("reginerate judging numbers error");
        },

    });
 }

 /**
 * -------- Purge Functions --------
 */

function purge_data(base_url,action,go,source,element_id) {

    /**
     * base_url   = site URL - global php var
     * action     = purge
     * go         = target data source
     * source     = where the action originated from ("admin", "entries", etc.)
     * element_id = the html id of the element being acted upon
     */
    
    $("#"+element_id+"-status").attr("class", "fa fa-lg fa-cog fa-spin text-muted");
    $("#"+element_id+"-status-msg").html("Processing...");
    $("#"+element_id+"-status-msg").attr("class", "text-muted small");
    $("#"+element_id+"-status").show();
    $("#"+element_id+"-status-msg").show();

    if (action == "") action = "purge";
    else action = action;

    let url = base_url+"ajax/purge.ajax.php?action="+action+"&go="+go;
    let data_const = "";
    if ((go == "entries") || (go == "payments") || (go == "participants")) {
        var value = $("#"+element_id+"-"+go+"-value").val();
        data_const += value;
        url += "&view="+ value;
    }

     jQuery.ajax({

        url: url,
        data: data_const,
        type: "POST",
        
        success:function(data) {

            var jsonData = JSON.parse(data);

            /*
            console.log(url);
            console.log(data_const);
            console.log(jsonData.action); 
            console.log(jsonData.status);
            console.log(jsonData.query);
            console.log(jsonData.post);
            console.log(jsonData.input);
            */

            // Error, data not purged
            if (jsonData.status === "0") {
                
                // console.log('fail!');
                // If the action was initiated on the admin dashboard screen,
                // display a simple notification.
                if (source == "admin-dashboard") {
                    generic_failure(element_id,'8000','There was an error, please try again.');
                }

            }

            // No error
            if (jsonData.status === "1") {

                var message = "";
                if ((jsonData.date !== "") || (jsonData.date.length > 1)) message = "Data prior to " + jsonData.date + " purged successfully.";
                else if (go == "confirmed") message = "All entries confirmed.";
                else if (go == "cleanup") message = "Data cleaned successfully."
                else message = "Purge completed successfully.";

                // console.log(message);
                // console.log('success!');
                // If the action was initiated on the admin dashboard screen,
                // display a simple notification.
                if (source == "admin-dashboard") {

                    // Update the counts in the Admin Dashboard sidebar for specific elements
                    if ((go == "participants") || (go == "purge-all")) {
                        var dom_ct_participants_calc = $("#admin-dashboard-participant-count").text() - jsonData.dom_ct_participants;
                        $("#admin-dashboard-participant-count").html(dom_ct_participants_calc);
                        $("#admin-dashboard-avail-judges-count").html(jsonData.dom_ct_judges_avail);
                        $("#admin-dashboard-assigned-judges-count").html(jsonData.dom_ct_judges_assigned);
                        $("#admin-dashboard-avail-stewards-count").html(jsonData.dom_ct_stewards_avail);
                        $("#admin-dashboard-assigned-stewards-count").html(jsonData.dom_ct_stewards_assigned);
                        $("#admin-dashboard-avail-staff-count").html(jsonData.dom_ct_staff_avail);
                        $("#admin-dashboard-assigned-staff-count").html(jsonData.dom_ct_staff_assigned);
                    }

                    if ((go == "participants") || (go == "entries") || (go == "purge-all")) {
                        $("#admin-dashboard-participant-entries").html(jsonData.dom_ct_participants_entries);
                        $("#admin-dashboard-entries-count").html(jsonData.dom_ct_entries);
                        $("#admin-dashboard-entries-unconfirmed-count").html(jsonData.dom_ct_entries_unconfirmed);
                        $("#admin-dashboard-entries-paid-count").html(jsonData.dom_ct_entries_paid);
                        $("#admin-dashboard-entries-paid-received-count").html(jsonData.dom_ct_entries_paid_received);
                        $("#admin-dashboard-total-fees").html(jsonData.dom_total_fees);
                        $("#admin-dashboard-total-fees-paid").html(jsonData.dom_total_fees_paid);
                    }

                    generic_success(element_id,'8000',message);
                }

            }

            // No data
            if (jsonData.status === "2") {
                
                // console.log('no records found!');
                // If the action was initiated on the admin dashboard screen,
                // display a simple notification.
                if ((jsonData.date !== "") || (jsonData.date.length > 1)) message = "No records updated prior to " + jsonData.date + " were found.";
                else message = "No non-admin participants found."

                if (source == "admin-dashboard") {
                    generic_failure(element_id,'8000',message);
                }

            }

            // Session expired
            if (jsonData.status === "9") {
                
                // console.log('session expired!');
                // If the action was initiated on the admin dashboard screen,
                // display a simple notification.
                if (source == "admin-dashboard") {
                    generic_session_expired(element_id,'0','Session expired. Sign in and try again.');
                }

            }
            
        },

        error:function() {
            console.log("reginerate judging numbers error");
        },

    });

 } // End purge function