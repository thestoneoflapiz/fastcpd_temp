if(!window.location.href.includes("/auth/social/register")){
    checkUserProfile();
}

// Global Variable
var ajax_datatable = {};
var apiType;
var apiData;
var scRsrc = {};
function deleteLink(event) {

    var link = event.target.dataset;
    var route = link.route;
    var resource = {
        'name': link.resourceName
    }

    $('#delete_modal_body').html('\
        <p class="text-center">\
            You are removing\
            <span><b>' + resource.name + '</b></span>.<br/>\
            You <b>cannot undo</b> this action.\
        </p>\
    ');

    $('#confirm_delete').click(function () {
        window.location.href = route;
    });
}

function printPDF(resource) {
    $('#print_pdf_modal_label').html('Page Setup for ' + resource.name);
    $('#print_modal_form').attr('action', resource.route).attr('method', resource.method).attr('target', '_blank');
}

// Confirm Print is global please don't use #confirm_print to any asset
$("#confirm_print").click(function () {
    $('#print_modal_form').submit();
});

$("#hideshow_checklist").on("click", "input[type=checkbox]", function () {
    var children = $("#hideshow_checklist").children();
    var total_ = children.length;

    var filter = children.filter(function (key, child) {
        if (child.control.checked) {
            return 1;
        }
    });
    var count_ = filter.length;


    if (count_ == total_) {
        $(this).prop("checked", false);
    }
});

function showFilterBox(data, table, type) {
    apiType = type;
    apiData = data;
    ajax_datatable = table;
    var display = "";

    for (var value in data) {
        if ($("#filter_" + value).is(':checked')) {
            if (data[value]["values"]) {
                var filters = data[value]["values"].map(function (sub) {
                    return (" " + sub);
                });
            } else {
                var filters = null;
            }

            display = display + addFilterLabel(value, data[value]['filter'], filters, (value + '_append'), "default");
        }
    }

    $("#filter_row").empty().html(display);
}

function addFilterLabel(name, filter, values, target, type) {
    switch (filter) {

        case "=":
            if (!values) {
                return "";
            }
            values = "Is: " + values;
            break;

        case "!=":
            if (!values) {
                return "";
            }
            values = "Is Not: " + values;
            break;

        case "like":
            if (!values) {
                return "";
            }
            values = "C: " + values;
            break;

        case "nlike":
            if (!values) {
                return "";
            }
            values = "DNC: " + values;
            break;

        case "!empty":
            values = "Is Not Empty";
            break;

        case "empty":
            values = "Is Empty";
            break;

        case "!verified":
            values = "not verified";
            type = "status";
            break;

        default:
            values = filter;
            type = "status";
            break;
    }

    return '<div class="col-xl-3 col-md-3 col-xs-2" id="filter_row_' + name + '"> \
                <div class="input-group"> \
                    <div class="input-group-prepend"> \
                    <a data-toggle="modal" class="input-group-text" data-target="#kt_filter_modal"><span >'+ name + '</span></a> \
                    </div> \
                    <input type="text" class="form-control" aria-label="" disabled value="'+ values + '"> \
                    <div class="input-group-append"> \
                        <span class="input-group-text"> \
                            <a onclick="removeFilter({ target: \'#'+ target + '\', type: \'' + type + '\', name: \'' + name + '\' })"><i class="fa fa-window-close"></i></a>\
                        </span> \
                    </div> \
                </div> \
            </div>' +
        '&nbsp;';
}

function removeFilter(resource) {
    var { type, name, target } = resource;
    var target = $(resource.target);

    $("#filter_row_" + name).remove();
    var row = $("#filter_row");
    var not = row.children.length;
    if (not < 2) {
        row.empty();
    }

    switch (type) {
        case 'status':
            $("#filter_" + name).prop("checked", false);
            $("#" + name + "_append").hide();
            target.prop("value", "both");
            break;

        default:
            $("#filter_" + name).prop("checked", false);
            $("#" + name + "_append").hide();
            $("#" + name + "_append").find("select").prop("value", "=");
            $("#" + name + "_append").find("input").prop("value", null);

            break;
    }

    sendDataApi();
}

function sendDataApi() {
    var resource = getApiData(apiType);

    setTimeout(() => {
        $.ajax({
            url: resource.route,
            data: resource.dataApi,
            success: function (response) {
                ajax_datatable.reload();
            }
        });
    }, 100);
}

function getApiData(type) {
    switch (type) {
        case "users":
            return {
                dataApi: {
                    name: {
                        filter: $("#filter_name").is(':checked') ? $("#name_append select").val() : "=",
                        values: $("#filter_name").is(':checked') ? ($("#name_append input").val() ? $("#name_append input").val().split(",") : null) : null,
                    },
                    position: {
                        filter: $("#filter_position").is(':checked') ? $("#position_append select").val() : "=",
                        values: $("#filter_position").is(':checked') ? ($("#position_append input").val() ? $("#position_append input").val().split(",") : null) : null,
                    },
                    role: {
                        filter: $("#filter_role").is(':checked') ? $("#role_append select").val() : "=",
                        values: $("#filter_role").is(':checked') ? ($("#role_append input").val() ? $("#role_append input").val().split(",") : null) : null,
                    },
                    contact: {
                        filter: $("#filter_contact").is(':checked') ? $("#contact_append select").val() : "=",
                        values: $("#filter_contact").is(':checked') ? ($("#contact_append input").val() ? $("#contact_append input").val().split(",") : null) : null,
                    },
                    email: {
                        filter: $("#filter_email").is(':checked') ? $("#email_append select").val() : "=",
                        values: $("#filter_email").is(':checked') ? ($("#email_append input").val() ? $("#email_append input").val().split(",") : null) : null,
                    },
                    added_by: {
                        filter: $("#filter_added_by").is(':checked') ? $("#added_by_append select").val() : "=",
                        values: $("#filter_added_by").is(':checked') ? ($("#added_by_append input").val() ? $("#added_by_append input").val().split(",") : null) : null,
                    },
                    status: {
                        filter: $("#filter_status").is(':checked') ? $("#status_append select").val() : "both",
                    },
                    verified: {
                        filter: $("#filter_verified").is(':checked') ? $("#verified_append select").val() : "both",
                    }
                },
                route: '/api/session'
            };

            break;

        case "logs":
            return {
                dataApi: {
                    module: {
                        filter: $("#filter_module").is(':checked') ? $("#module_append select").val() : "=",
                        values: $("#filter_module").is(':checked') ? ($("#module_append input").val() ? $("#module_append input").val().split(",") : null) : null,
                    },
                    activity: {
                        filter: $("#filter_activity").is(':checked') ? $("#activity_append select").val() : "=",
                        values: $("#filter_activity").is(':checked') ? ($("#activity_append input").val() ? $("#activity_append input").val().split(",") : null) : null,
                    },
                    by: {
                        filter: $("#filter_by").is(':checked') ? $("#by_append select").val() : "=",
                        values: $("#filter_by").is(':checked') ? ($("#by_append input").val() ? $("#by_append input").val().split(",") : null) : null,
                    },
                    created_at: {
                        filter: $("#filter_created_at").is(':checked') ? $("#created_at_append select").val() : "=",
                        values: $("#filter_created_at").is(':checked') ? ($("#created_at_append input").val() ? $("#created_at_append input").val().split(",") : null) : null,
                    },
                },
                route: '/api/session-logs'
            };
            break;

        case "vouchers":
            return {
                dataApi: {
                    voucher_code: {
                        filter: $("#filter_voucher_code").is(':checked') ? $("#voucher_code_append select").val() : "=",
                        values: $("#filter_voucher_code").is(':checked') ? ($("#voucher_code_append input").val() ? $("#voucher_code_append input").val().split(",") : null) : null,
                    },
                    discount: {
                        filter: $("#filter_discount").is(':checked') ? $("#discount_append select").val() : "=",
                        values: $("#filter_discount").is(':checked') ? ($("#discount_append input").val() ? $("#discount_append input").val().split(",") : null) : null,
                    },
                    start_date: {
                        filter: $("#filter_start_date").is(':checked') ? $("#start_date_append select").val() : "=",
                        values: $("#filter_start_date").is(':checked') ? ($("#start_date_append input").val() ? $("#start_date_append input").val().split(",") : null) : null,
                    },
                    end_date: {
                        filter: $("#filter_end_date").is(':checked') ? $("#end_date_append select").val() : "=",
                        values: $("#filter_end_date").is(':checked') ? ($("#end_date_append input").val() ? $("#end_date_append input").val().split(",") : null) : null,
                    },
                    created_by: {
                        filter: $("#filter_created_by").is(':checked') ? $("#created_by_append select").val() : "=",
                        values: $("#filter_created_by").is(':checked') ? ($("#created_by_append input").val() ? $("#created_by_append input").val().split(",") : null) : null,
                    },
                    status: {
                        filter: $("#filter_status").is(':checked') ? $("#status_append select").val() : null,
                    },
                },
                route: '/superadmin/verification/vouchers/api/session'
            };

            break;

        case "dragonpay":
            return {
                dataApi: {
                    reference_number: {
                        filter: $("#filter_reference_number").is(':checked') ? $("#reference_number_append select").val() : "=",
                        values: $("#filter_reference_number").is(':checked') ? ($("#reference_number_append input").val() ? $("#reference_number_append input").val().split(",") : null) : null,
                    },
                    customer: {
                        filter: $("#filter_customer").is(':checked') ? $("#customer_append select").val() : "=",
                        values: $("#filter_customer").is(':checked') ? ($("#customer_append input").val() ? $("#customer_append input").val().split(",") : null) : null,
                    },
                    email: {
                        filter: $("#filter_email").is(':checked') ? $("#email_append select").val() : "=",
                        values: $("#filter_email").is(':checked') ? ($("#email_append input").val() ? $("#email_append input").val().split(",") : null) : null,
                    },
                    amount: {
                        filter: $("#filter_amount").is(':checked') ? $("#amount_append select").val() : "=",
                        values: $("#filter_amount").is(':checked') ? ($("#amount_append input").val() ? $("#amount_append input").val().split(",") : null) : null,
                    },
                    payment_status: {
                        filter: $("#filter_payment_status").is(':checked') ? $("#payment_status_append select").val() : null,
                    },
                },
                route: '/superadmin/verification/dragonpay/api/session'
            };

        break;

        default:
            return apiData;
            break;
    }

}

function addToCart(data, e){
    $.ajax({
        url: "/cart/add",
        data: data,
        success: function(response){
            var data = response.data;
            toastr.success(`Added ${data.title} <button class='btn btn-sm btn-light' onclick='window.location="/cart"'>Go to Cart</button>`);
            $(e).html("Go to Cart").attr("onclick", "window.location='/cart'").removeClass("btn-success").addClass("btn-danger");

            /**
             * Add item on my-cart-ui
             * 
             */
            var $my_cart = $("#my-cart-ui");
            $my_cart.find(".default-item").remove();
            
            var course_accreditation = ``;
            if(data.accreditation){
                data.accreditation.forEach(acc => {
                    course_accreditation += `${acc.title.substring(0, 10)}... (${acc.program_no} &#9679; ${acc.units}) <br/>`;
                });
            }

            if(response.total.discounted_price){
                var total_prices_div = `
                    <span class="kt-font-brand">
                        <label class="kt-font-dark">₱${response.total.discounted_price}</label>
                        <label style="text-decoration:line-through;font-weight:none;" class="kt-label-font-color-1">₱${response.total.price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</label>
                    </span>	
                `;
            }else{
                var total_prices_div = `
                    <span class="kt-font-brand">
                        <label class="kt-font-dark">₱${response.total.price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</label>
                    </span>	
                `;
            }

            if(data.discount){
                var item_prices_div = `₱${data.discounted_price} <text style="text-decoration:line-through;font-weight:none;">₱${data.price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</text>`;
            }else{
                var item_prices_div = `₱${data.price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`;
            }

            $my_item = $(`
                <div class="kt-mycart__item">
                    <div class="kt-mycart__container">
                        <div class="kt-mycart__info">
                            <a href="javascript:;" class="kt-mycart__title">${data.title.length > 50 ? data.title.substr(0, 50)+`...`: data.title}</a>
                            <span class="kt-mycart__desc">${course_accreditation}</span>
                            <div class="kt-mycart__action">
                                <span class="kt-mycart__price">${item_prices_div}</span>
                            </div>
                        </div>
                        <a href="#" class="kt-mycart__pic">
                            <img alt="FastCPD Courses ${data.title}" src="${data.poster}">
                        </a>
                    </div>
                </div>
            `);
            
            $my_cart.find(`.kt-mycart__body`).append($my_item);
            $my_cart.find(`.kt-mycart__head .kt-mycart__button button`).html(`${response.total.items} ${response.total.items > 1 ? 'Items' : 'Item'}`);
            $my_cart.find(`.kt-mycart__footer .kt-mycart__section .kt-mycart__prices`).html(total_prices_div);
            $my_cart.find(`.kt-mycart__footer .kt-mycart__button`).empty().append(`
                <button type="button" class="btn btn-danger btn-lg btn-upper kt-font-bolder" style="width:100%;" onclick="window.location='/cart'">Go to Cart</button>
            `);
            
            $(`.my-cart-ui-icon`).find(`#my-cart-ui-badge`).remove();
            $(`.my-cart-ui-icon`).append(`
                <span class="kt-badge kt-badge--danger cart-badge" style="position:absolute;top:15;right:2;padding:5px;font-size:11px;" id="my-cart-ui-badge">${response.total.items}</span>
            `);
        },
        error: function(response){
            var body = response.responseJSON;
            toastr.error(body.hasOwnProperty("message") ? body.message : "Sorry! We're unable to add to cart this course!");

            if(body.hasOwnProperty("button")){
                $(e).html(body.button.label).attr("onclick", `window.location='/cart'`).removeClass("btn-success").addClass("btn-danger");
            }
        } 
    });
}

function getNotifications(){
    var notification_display = $('#notification_display');
    $.ajax({
        url: "/notification/getNotif",
        data: {
            page: $("#notification_page").val(),
        },
        success: function(response){
            if(response.data.length === 0){
                $('#seemore').hide('slow');
                $('#nothingMore').show('slow');
            }else{
                $.each( response.data, function ( key, notif){
                    if(notif.read_at == null){
                        notification_display.append('\
                            <a href="/notification/redirect/'+ notif.notif_id + '" class="kt-notification__item" style="background:#daedf4;">\
                                <div class="kt-notification__item-icon">\
                                    <i class="flaticon2-line-chart kt-font-success"></i>\
                                </div>\
                                <div class="kt-notification__item-details">\
                                    <div class="kt-notification__item-title">\
                                        '+notif.description+'\
                                    </div>\
                                    <div class="kt-notification__item-time">\
                                        '+notif.time_ago+'\
                                    </div>\
                                </div>\
                            </a>\
                        ');
                    }else{
                        notification_display.append('\
                            <a href="/notification/redirect/'+ notif.notif_id + '" class="kt-notification__item">\
                                <div class="kt-notification__item-icon">\
                                    <i class="flaticon2-line-chart kt-font-success"></i>\
                                </div>\
                                <div class="kt-notification__item-details">\
                                    <div class="kt-notification__item-title">\
                                        '+notif.description+'\
                                    </div>\
                                    <div class="kt-notification__item-time">\
                                        '+notif.time_ago+'\
                                    </div>\
                                </div>\
                            </a>\
                        ');
                    }
                    
                });
            }
            
        },
        error: function(response){
            var body = response.responseJSON;
            toastr.error(body.hasOwnProperty("message") ? body.message : "Sorry! We're unable to add to cart this course!");
        } 
    });

}

function update_nofis(){
    var notification_display = $('#notification_display');
    $.ajax({
        url: "/notification/getUnseenedNotif",
        data: {
            page:"",
        },
        success: function(response){
            $(`.my-notif-ui-icon`).find(`#my-notif-ui-badge`).remove();
        },
        error: function(response){
            var body = response.responseJSON;
            toastr.error(body.hasOwnProperty("message") ? body.message : "Sorry! We're unable to add to cart this course!");
        } 
    });

}

function seeMore() {
    var current_page = $('#notification_page').val()
    $('#notification_page').val(parseInt(current_page) + 1);
    $('#notif_dropdown').parent().toggleClass('open');
    getNotifications();
}

$('#notif_button').on('click', function (event) {
    $(this).parent().toggleClass('show');

    $('#notif_dropdown').toggleClass('show');
    $('#notif_dropdown').css({"position": "absolute", "transform": "translate3d(-246px, 5px, 0px)", "top":" 0px; left: 0px", "will-change": "transform"});
});

jQuery( document ).ready(function( $ ) {
    //Use this inside your document ready jQuery 
    $(window).on('popstate', function() {
       location.reload();
    });

    if (window.location.href.indexOf("#request") > -1) {
       $("#request_modal").modal("show");
    }
 });

 function checkUserProfile(){
    $.ajax({
        url: "/auth/user", 
        success: function(response){
            if(response.hasOwnProperty("redirect")){
                if(response.redirect=="/"){
                    location.reload();
                    return;
                }
                
                window.location=response.redirect;
                return;
            }
            
            return;
        }
    });
}

function signOUTuser(social){
    toastr.info("Logging out...");
    switch (social) {
        case "facebook":
            try {
                FB.getLoginStatus(function(response) {
                    if (response.status === 'connected') {
                        FB.logout(function(response) {
                            window.location="/signout";
                        });
                    }
                    
                    window.location="/signout";
                });
            } catch (error) {
                window.location="/signout";
            }
            break;

        case "google":

            try {
                if(GOOGLESignOut()){
                    window.location="/signout";
                }
            } catch (error) {
                window.location="/signout";
            }
            break;
    
        default:
                window.location="/signout";
            break;
    }
}