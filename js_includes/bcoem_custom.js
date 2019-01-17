function jumpMenu(targ, selObj, restore) {
    eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'"), restore && (selObj.selectedIndex = 0);
}

function AjaxFunction(t) {
    var e;
    try {
        e = new XMLHttpRequest();
    } catch (t) {
        try {
            e = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (t) {
            try {
                e = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (t) {
                return !1;
            }
        }
    }
    var a = "/includes/email.inc.php";
    a = (a = a + "?email=" + t) + "&sid=" + Math.random(), e.onreadystatechange = function() {
        4 == e.readyState && (document.getElementById("msg_email").innerHTML = e.responseText);
    }, e.open("GET", a, !0), e.send(null);
}

$(function() {
    $('[data-toggle="tooltip"]').tooltip();
});

$(function() {
    $('[data-tooltip="true"]').tooltip();
});

$(function() {
    $('[data-toggle="popover"]').popover();
});

$(document).ready(function() {

    $("[data-confirm!=''][data-confirm]").ready(function() {
        addClass("hide-loader");
    });

    $("form").submit(function() {
        if (!$(this).hasClass("hide-loader-form-submit")) {
            $('#loader-submit').show(0).delay(30000).hide(0);
        }
    });

    $("a:not([href*='#'],[target='_blank'])").click(function() {
        if (!$(this).hasClass("hide-loader")) {
            $('#loader-submit').show(0).delay(30000).hide(0);
        }
    });

    $("#submitBtn").click(function() {
        $("#archiveName").html($("#archiveSuffix").val());
    });

    $("#submit").click(function() {
        $("#formfield").submit();
    });

    $("#admin-arrow").click(function() {
        $(this).find("i").toggleClass("fa-chevron-circle-left fa-chevron-circle-right"), $(this).find("i").toggleClass("text-teal text-orange");
    });

    $(".my-dropdown").dropdown(), $(".my-dropdown").tooltip();

    $("a[data-confirm]").click(function(t) {
        var e = $(this).attr("href");
        return $("#dataConfirmModal").length || $("body").append('<div class="modal fade" id="dataConfirmModal" aria-labelledby="dataConfirmLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 id="dataConfirmLabel" class="modal-title">Please Confirm</h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button><a class="btn btn-success" id="dataConfirmOK">Yes</a></div></div></div></div>'), $("#dataConfirmModal").find(".modal-body").text($(this).attr("data-confirm")), $("#dataConfirmOK").attr("href", e), $("#dataConfirmModal").modal({
            show: !0
        }), !1;
    });

    $("#modal_window_link").fancybox({
        width: "85%",
        maxHeight: "85%",
        fitToView: !1,
        scrolling: "auto",
        openEffect: "elastic",
        closeEffect: "elastic",
        openEasing: "easeOutBack",
        closeEasing: "easeInBack",
        openSpeed: "normal",
        closeSpeed: "normal",
        type: "iframe",
        helpers: {
            title: {
                type: "inside"
            }
        }
    });

});