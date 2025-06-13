<?php
/**
 * Module:      list.pub.php
 * Description: This module displays user-related data including personal information,
 *              judging/stewarding information, and the participant's associated entries.
 *
 */

function pay_to_print($prefs_pay,$entry_paid) {
	if (($prefs_pay == "Y") && ($entry_paid == "1")) return TRUE;
	elseif (($prefs_pay == "Y") && ($entry_paid == "0")) return FALSE;
	elseif ($prefs_pay == "N") return TRUE;
}

$pay_button_disable = "";
$add_entry_button_disable = "";

$judging_date = $judging_past;
$total_not_paid = total_not_paid_brewer($_SESSION['user_id']);

$user_edit_links = "<div class=\"d-grid gap-2 mb-5 d-print-none\">";
if (($show_entries) && ($totalRows_log > 0)) $user_edit_links .= sprintf("<a class=\"btn btn-primary\" href=\"#entries\"><i class=\"fa fa-list me-2\"></i>%s</a>",$label_entries);

if (!$add_entry_link_show) $add_entry_button_disable = "disabled";
if (($show_entries) && (!$show_scores)) $user_edit_links .= sprintf("<a class=\"btn btn-primary %s\" href=\"%s\"><i class=\"fa fa-plus-circle me-2\"></i>%s</a>",$add_entry_button_disable,$add_entry_link,$label_add_entry);

if (($total_to_pay == 0) || ($disable_pay)) $pay_button_disable = "disabled";
if (($show_entries) && (!$show_scores)) $user_edit_links .= sprintf("<a class=\"btn btn-primary hide-loader %s\" href=\"#pay-fees\"><i class=\"fa fa-lg fa-money-bill me-2\"></i>%s</a>", $pay_button_disable, $label_pay);

$user_edit_links .= sprintf("<a class=\"btn btn-dark\" href=\"%s\"><i class=\"fa fa-user me-2\"></i>%s</a>",$edit_user_info_link,$label_edit_account);
$user_edit_links .= sprintf("<a class=\"btn btn-dark\" href=\"".$edit_user_email_link."\"><i class=\"fa fa-envelope me-2\"></i>%s</a>",$label_change_email);
$user_edit_links .= sprintf("<a class=\"btn btn-dark\" href=\"%s\"><i class=\"fa fa-key me-2\"></i>%s</a>",$edit_user_password_link,$label_change_password);
if ((isset($assignment_array) && ((in_array($label_judge,$assignment_array)) && ($_SESSION['brewerJudge'] == "Y")) && (time() >= $row_judging_prefs['jPrefsJudgingOpen']))) {
	$user_edit_links .= sprintf("<a class=\"btn btn-primary\" href=\"%s\"><i class=\"fa fa-gavel me-2\"></i>%s</a>",build_public_url("evaluation","default","default","default",$sef,$base_url,"default"),$label_judging_dashboard);
}
$user_edit_links .= "</div>";

?>
<a name="my-account"></a>
<div class="row">
	<div class="col-12 col-md-8 col-lg-9 mb-3"> 
		<?php include (PUB.'brewer_info.pub.php'); ?>
	</div>
	<div class="col-12 col-md-4 col-lg-3 mb-3">
		<section class="d-none d-sm-none d-md-block d-lg-block d-xl-block d-xxl-block d-print-none">
		<?php
		echo $user_edit_links;	
		include (PUB.'at-a-glance.pub.php');
		?>
		</section>
	</div>
</div>
<?php 
if ($show_entries) include (PUB.'brewer_entries.pub.php'); 
if (!$disable_pay) include (PUB.'pay.pub.php');
?>
<script type="text/javascript" language="javascript">

$("#entry-table").hide();
$("#toggle-entry-cards").prop("disabled", true);

$(document).ready(function() {
    /**
     * Toggle State Manager
     * A generic jQuery solution for toggling between two states and remembering the selection
     * 
     * @param {Object} config Configuration object with the following properties:
     * - cookieName: Name of the cookie to store the state
     * - states: Array of state objects, each containing:
     *   - name: Unique identifier for this state
     *   - element: jQuery selector for the element to show when this state is active
     *   - button: jQuery selector for the button that activates this state
     *   - isDefault: Boolean indicating if this is the default state
     * - fadeSpeed: (Optional) Speed for fade transitions in milliseconds
     * - fadeDelay: (Optional) Delay before showing the new element in milliseconds
     */
    $.toggleStateManager = function(config) {
        // Set default values if not provided
        config = $.extend({
            fadeSpeed: 'slow',
            fadeDelay: 500
        }, config);

        // Function to set a session cookie (expires when browser closes)
        $.setCookie = function(name, value) {
            document.cookie = name + "=" + value + "; path=/";
        };

        // Function to get cookie value by name
        $.getCookie = function(name) {
            const cookieName = name + "=";
            const cookies = document.cookie.split(';');
            let cookieValue = "";
            
            $.each(cookies, function(i, cookie) {
                cookie = $.trim(cookie);
                if (cookie.indexOf(cookieName) === 0) {
                    cookieValue = cookie.substring(cookieName.length);
                    return false; // Break the loop
                }
            });
            
            return cookieValue;
        };

        // Function to set the active state
        function setActiveState(stateName) {
            // Find the active state
            let activeState = null;
            let inactiveStates = [];
            
            $.each(config.states, function(i, state) {
                if (state.name === stateName) {
                    activeState = state;
                } else {
                    inactiveStates.push(state);
                }
            });
            
            if (!activeState) return;

            // Hide all inactive elements
            $.each(inactiveStates, function(i, state) {
                $(state.element).fadeOut(config.fadeSpeed);
                $(state.button).prop("disabled", false);
            });

            // Show active element after delay
            setTimeout(function() {
                $(activeState.element).fadeIn(config.fadeSpeed);
                $(activeState.button).prop("disabled", true);
            }, config.fadeDelay);

            // Save state to cookie
            $.setCookie(config.cookieName, stateName);
        }

        // Initialize event handlers for all buttons
        $.each(config.states, function(i, state) {
            $(state.button).on('click', function() {
                setActiveState(state.name);
            });
        });

        // Find the default state
        let defaultState = null;
        $.each(config.states, function(i, state) {
            if (state.isDefault) {
                defaultState = state;
                return false; // Break the loop
            }
        });
        
        // If no default is specified, use the first state
        if (!defaultState && config.states.length > 0) {
            defaultState = config.states[0];
        }

        // Set initial state based on cookie or default
        const savedState = $.getCookie(config.cookieName);
        let initialState = null;
        
        if (savedState) {
            $.each(config.states, function(i, state) {
                if (state.name === savedState) {
                    initialState = state;
                    return false; // Break the loop
                }
            });
        }
        
        // If no saved state or invalid state, use default
        if (!initialState) {
            initialState = defaultState;
        }
        
        // Apply initial state immediately without animation on page load
        $.each(config.states, function(i, state) {
            if (state.name !== initialState.name) {
                $(state.element).hide();
                $(state.button).prop("disabled", false);
            } else {
                $(state.element).show();
                $(state.button).prop("disabled", true);
            }
        });
    };

    // Initialize the toggle state manager for the entry view
    $.toggleStateManager({
        cookieName: "entryViewType",
        states: [
            {
                name: "cards",
                element: "#entry-cards",
                button: "#toggle-entry-cards",
                isDefault: true
            },
            {
                name: "table",
                element: "#entry-table",
                button: "#toggle-entry-table",
                isDefault: false
            }
        ],
        fadeSpeed: "slow",
        fadeDelay: 500
    });


    /*
    $("#toggle-entry-cards").click(function() {
    	$("#entry-table").fadeOut("slow");
        $("#entry-cards").delay(500).fadeIn("slow");
        $("#toggle-entry-cards").prop("disabled", true);
        $("#toggle-entry-table").prop("disabled", false);
    });

    $("#toggle-entry-table").click(function() {
    	$("#entry-cards").fadeOut("slow");
        $("#entry-table").delay(500).fadeIn("slow");
        $("#toggle-entry-cards").prop("disabled", false);
        $("#toggle-entry-table").prop("disabled", true);
    });
    */


 	$(".entry-print").on("click", function() {
		
		if ($(".entry-print:checked").length > 0) {
			$('#btn').prop('disabled', false);
		}
		
		else {
			$('#btn').prop('disabled', true);
		}
		
	});

	$(".entry-print").change(function() {
	    
	    //uncheck "select all", if one of the listed checkbox item is unchecked
	    if (false == $(this).prop("checked")) {
	        $("#select_all").prop('checked', false);
	    }
	    
	    //check "select all" if all checkbox items are checked
	    if ($('.checkbox:checked').length == $('.checkbox').length) {
	        $("#select_all").prop('checked', true);
	    }
	
	});

	$("#select_all").change(function() {
		
		$("input:checkbox").prop('checked', $(this).prop("checked"));
		
		if ($(".entry-print:checked").length > 0) {
			$('#btn').prop('disabled', false);
		}
		
		else {
			$('#btn').prop('disabled', true);
		}
	
	});

	$('#sortable').dataTable( {
	"bPaginate" : false,
	"sDom": 'rt',
	"bStateSave" : false,
	"bLengthChange" : false,
	"aaSorting": [[2,'asc']],
	"aoColumns": [
		null,
		<?php if ($show_scores) { ?>
		null,
		<?php } ?>
		null,
		null,
		<?php if (!$show_scores) { ?>
			null,
		null,
		null,
		null,
		<?php if ($print_bottle_labels) { ?>{ "asSorting": [  ] },<?php } ?>
		<?php } ?>
		<?php if ($show_scores) { ?>
		null,
		{ "asSorting": [  ] },
		null,
		<?php } ?>
		<?php if ($action != "print") { ?>
		{ "asSorting": [  ] }
		<?php } ?>
		]
	} );

	$('#sortable_judge').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[2,'asc']],
		"aoColumns": [
			null,
			null,
			null,
			null
			]
		} );

	$('#judge_assignments').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[1,'asc']],
		"aoColumns": [
			null,
			null,
			null,
			null
			]
		} );

	$('#sortable_steward').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[2,'asc']],
		"aoColumns": [
			null,
			null,
			null,
			null
			]
		} );

	$('#sortable_staff').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[2,'asc']],
		"aoColumns": [
			null,
			null,
			null,
			null
			]
		} );

	$('#steward_assignments').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[0,'asc']],
		"aoColumns": [
			null,
			null,
			null,
			null
			]
		});
	
});
</script>