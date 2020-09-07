function save_failure(element_id,column,action,error) {
    // Error codes:
    // 1 = Numbers only
    // 2 = Alpha only
    // 3 = SQL save error

    // Enable the update all button if there's an issue saving. Failsafe.
    $("#"+action+"_submit").prop("disabled", false);
    
         if (error == 1) $("#"+element_id+"-"+column+"-status-msg").html("Error. Invalid numerical input.");
    else if (error == 2) $("#"+element_id+"-"+column+"-status-msg").html("Error. Alpha characters only.");
    else if (error == 3) $("#"+element_id+"-"+column+"-status-msg").html("Error saving. Please try again.");
    else $("#"+element_id+"-"+column+"-status-msg").html("Error.");
    
    $("#"+element_id+"-"+column+"-status").attr("class", "fa fa-lg fa-times text-danger");
    $("#"+element_id+"-"+column+"-status-msg").attr("class", "text-danger small");
    $("#"+element_id+"-"+column+"-form-group").removeClass("has-danger"); // just in case failure happened before
    $("#"+element_id+"-"+column+"-form-group").removeClass("has-success"); // just in case success has happend before
    $("#"+element_id+"-"+column+"-form-group").addClass("has-danger");
    $("#"+element_id+"-"+column+"-status").show();
    $("#"+element_id+"-"+column+"-status-msg").show();
};

function save_success(element_id,column,action,error) {
    // $("#"+element_id+"-"+column+"-status").attr("class", "label label-success");
    // $("#"+action+"_submit").prop("disabled", true);
    $("#"+element_id+"-"+column+"-status").attr("class", "fa fa-lg fa-check text-success");
    $("#"+element_id+"-"+column+"-status-msg").html("Saved");
    $("#"+element_id+"-"+column+"-status-msg").attr("class", "text-success small");
    $("#"+element_id+"-"+column+"-form-group").removeClass("has-danger"); // just in case failure happened before
    $("#"+element_id+"-"+column+"-form-group").removeClass("has-success"); // just in case success has happend before
    $("#"+element_id+"-"+column+"-form-group").addClass("has-success");
    $("#"+element_id+"-"+column+"-status").show();
    $("#"+element_id+"-"+column+"-status-msg").show();
};


// Save a single column (data point)
function save_column(base_url,column,action,id,rid1,rid2,rid3,rid4,element_id) {

    // base_url   = site URL - global php var
    // action     = database table ("judging_scores", "brewing", etc.)
    // id         = the primary identification item
	// rid1       = first relational id (optional)
	// rid2       = second relational id (optional)
    // rid3       = third relational id (optional)
    // rid4       = fourth relational id (optional)
    // element_id = the html id of the element being acted upon

    var value = $("#"+element_id).val();

    // if (value !== "" && value !== null) {

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

        console.log(url);
        console.log(data_const);

        jQuery.ajax({

            url: url,
            data: data_const,
            type: "POST",
            
            success:function(data) {

                var jsonData = JSON.parse(data);
                console.log(jsonData.status);
                console.log(jsonData.query);
                console.log(jsonData.post);
                console.log(jsonData.input);

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
                
            },

            error:function() {
                console.log("save_score error");
            },

        });

   // }

};