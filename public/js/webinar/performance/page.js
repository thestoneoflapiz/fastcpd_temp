var _refresh = false;
var submitForm = null;
var _selected_date = null;
var i = 0;

var fetchReportsAJAX = null;

var type = {
    in: {
        color: "kt-font-success",
        icon: "flaticon2-chronometer",
    },
    out: {
        color: "kt-font-danger",
        icon: "flaticon2-chronometer",
    },
    quiz: {
        color: "kt-font-warning",
        icon: "flaticon2-list-1",
    },
    article: {
        color: "kt-font-info",
        icon: "flaticon2-file",
    },
    rating: {
        color: "kt-font-warning",
        icon: "flaticon-star",
    },
    performance: {
        color: "kt-font-dark",
        icon: "flaticon2-line-chart",
    },
    registered: {
        color: "kt-font-warning",
        icon: "flaticon2-avatar",
    },
};

jQuery(document).ready(function(){
    $("#selected_date").select2({
        placeholder: "Please select a date",
        width: "100%"
    }).change(function(){
        $("#selection_date_modal").modal("hide");

        var value = $(this).val();

        _selected_date = value;
        fetchReports();
        manage_refresh();
        
    });

    $("[name='attendance_time']").timepicker();
    $("[name='select_registered_user']").select2({width:"100%", placeholder: "Select a registered user"});

    FormDesign.init();
});

function fetchReports(){
    
    loading(true);

    fetchReportsAJAX = $.ajax({
        data: { _selected_date },
        url: "/webinar/performance/reports",
        beforeSend : function()    {           
            if(fetchReportsAJAX != null) {
                fetchReportsAJAX.abort();
            }
        },
        success: function(response) {
            generateReports(response);
        },
        error:function(response){
            var body = response.responseJSON;
            if(body.hasOwnProperty("message")){
                toastr.error(body.message);
                return;
            }

            toastr.error("Something went wrong! Please refresh browser");
        }
    });
}

function generateReports(response){
    /**
     * Timeline
     */
    var tab = $("#tab-timeline-content");
    var sidemenu = $("#sidemenu-timeline-content");

    var timeline_items = ``;
    response.timeline.forEach(data => {
        timeline_items += `
        <div class="kt-widget4__item">
            <span class="kt-widget4__icon">
                <i class="${type[data.type].icon} ${type[data.type].color} "></i>
            </span>
            <a href="javascript:;" class="kt-widget4__title kt-widget4__title--light">${data.user_name} ${data.text}</a>
            <span class="kt-widget4__number ${type[data.type].color} ">${data.time_}</span>
        </div>
        `;
    });

    tab.empty().append(`<div class="kt-widget4 kt-margin-t-20">${timeline_items}</div>`);
    sidemenu.empty().append(`<div class="kt-widget4 kt-margin-t-20">${timeline_items}</div>`);

    /**
     * Attendees
     */
    var attendees_in_items = ``;
    response.attendees.in.forEach(data => {
        attendees_in_items += `
        <div class="kt-widget4__item">
            <span class="kt-widget4__icon">
                <i class="${type[data.type].icon} ${type[data.type].color}"></i>
            </span>
            <a href="javascript:;" class="kt-widget4__title kt-widget4__title--light">${data.user_name}</a>
            <span class="kt-widget4__number ${type[data.type].color}">${data.time_}</span>
            <span class="kt-widget4__number"><button onclick="window.open('${data.user_url}')" class="kt-font-light btn btn-sm btn-info">View Profile</button></span>
        </div>
        `;
    });

    var attendees_out_items = ``;
    response.attendees.out.forEach(data => {
        attendees_out_items += `
        <div class="kt-widget4__item">
            <span class="kt-widget4__icon">
                <i class="${type[data.type].icon} ${type[data.type].color}"></i>
            </span>
            <a href="javascript:;" class="kt-widget4__title kt-widget4__title--light">${data.user_name}</a>
            <span class="kt-widget4__number ${type[data.type].color}">${data.time_}</span>
            <span class="kt-widget4__number"><button onclick="window.open('${data.user_url}')" class="kt-font-light btn btn-sm btn-info">View Profile</button></span>
        </div>
        `;
    });
    
    $(`.attendance-for-in`).empty().append(`<div class="col-12"><div class="kt-widget4">${attendees_in_items}</div></div>`);
    $(`.attendance-for-out`).empty().append(`<div class="col-12"><div class="kt-widget4">${attendees_out_items}</div></div>`);
    $(`.attendees-total`).empty().append(`Attendees IN: ${response.attendees.in.length} — OUT: ${response.attendees.out.length}`);

    var registered_users = ``;
    var registered_selection = [];

    response.registered.forEach(data => {
        registered_users += `
        <div class="kt-widget4__item">
            <span class="kt-widget4__icon">
                <i class="${type[data.type].icon} `+(data.attendance ? (data.attendance.session_out ? `kt-font-danger` : `kt-font-success`): `kt-font-warning`)+`"></i>
            </span>
            <a href="javascript:;" class="kt-widget4__title kt-widget4__title--light">
                ${data.user_name} &nbsp; &nbsp; 
                <i class="fa fa-check `+(data.progress ? `kt-font-success` : ``)+`"></i>
                <i class="fa fa-check `+(data.rating ? `kt-font-success` : ``)+`"></i>
            </a>
            <span class="kt-widget4__number `+(data.attendance ? (data.attendance.session_out ? `kt-font-danger` : `kt-font-success`): `kt-font-warning`)+`">${data.time_}</span>
            <span class="kt-widget4__number"><button onclick="window.open('${data.user_url}')" class="kt-font-light btn btn-sm btn-info">View Profile</button></span>
        </div>
        `;
 
        registered_selection.push({id: data.user_id, text: data.user_name});
    });

    $(`.registered-users`).empty().append(`<div class="col-12"><div class="kt-widget4">${registered_users}</div></div>`);
    $(`.registered-total`).empty().append(`Registered: ${response.registered.length}`);
    
    $("[name='select_registered_user']").empty().select2({
        width: '100%',
        placeholder: 'Please select a registered user',
        data: registered_selection,
    });

    $("[name='select_registered_user']").select2({width:"100%", placeholder: "Select a registered user"});
    $(".form-attendance-content").removeClass("kt-hidden").prev().addClass("kt-hidden");

    loading(false);
}

function manage_refresh(){
    var today = new Date();
    var month_ = (today.getMonth()+1) < 10 ? `0${(today.getMonth()+1)}` : (today.getMonth()+1);
    var date_ = (today.getDate()) < 10 ? `0${(today.getDate())}` : (today.getDate());
    var full_date = `${today.getFullYear()}-${month_}-${date_}`;
    
    if(full_date ==  _selected_date){
       _refresh = true;
    }else{
        _refresh = false;
    }

    refresh();
}

function refresh() {
    setInterval(() => {
        if(_refresh){
            fetchReports();
        }
    }, 1000*60);
}

/**
 * Show & Hide Timeline
 *
 */
function contentEvent(open) {
    if (open) {
        $(`#main-content`).removeClass(`col-12`).addClass(`col-xl-8 col-lg-8 col-md-12`);
        $(`#sidemenu-webinar-content`).show(`slide`, { direction: `right` }, 350);
        $(`.webinar-content-open-btn`).hide(`slide`, { direction: `right` }, 350);
        $(`.hidden-webinar-content`).hide(`slide`, { direction: `left` }, 350);
        $(`#tab-webinar-content`).removeClass("active").next().addClass("active");

        return;
    }

    $(`#main-content`).removeClass(`col-xl-8 col-lg-8 col-md-12`).addClass(`col-12`);
    $(`#sidemenu-webinar-content`).hide(`slide`, { direction: `right` }, 350);
    $(`.hidden-webinar-content`).show(`slide`, { direction: `left` }, 350);
    $(`.webinar-content-open-btn`).show(`slide`, { direction: `right` }, 350)
    .hover(
        function () {
            $(`.btn-label-content`).html(`Show Webinar Content`);
            $(this).removeClass(`btn-icon`);
        },
        function () {
            $(`.btn-label-content`).html(``);
            $(this).addClass(`btn-icon`);
        }
    );
}

/**
 * loading show/hide
 */
function loading(show){
    var loading_div = $(".loading-div");
    if(show){
        loading_div.show();
        loading_div.next().hide();
        return;
    }

    loading_div.hide();
    loading_div.next().show();
}

var FormDesign = function() {
    var validator;

    var form_validation = function() {
        validator = $("#manual-attendance-form").validate({ 
            // define validation rules
            rules: {
                select_registered_user: {
                    required: true,
                },
                attendance_for: {
                    required: true,
                },
                attendance_time: {
                    required: true,
                },
            },
            //display error alert on form submit  
            invalidHandler: function(event, validator) { },
            submitHandler: function(form) {
                $btn_submit = $("[name='submit_attendance_btn']");
                $btn_submit.addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true).html("Saving...");

                submitForm = $.ajax({
                    url: '/webinar/performance/manual',
                    type: 'POST',
                    data: {
                        type: "attendance",
                        _selected_date: _selected_date,
                        select_registered_user: $("[name='select_registered_user']").val(),
                        attendance_for: $("[name='attendance_for']:checked").val(),
                        attendance_time: $("[name='attendance_time']").val(),
                        _token: $('[name="_token"]').val(),
                    }, beforeSend: function(){
                        if(submitForm!=null){   
                            submitForm.abort();
                        }
                    }, success: function(response) {
                        toastr.success("Successfully attended user!");
                        fetchReports();
                    }, error: function(response) {
                        var body = response.responseJSON;
                        if(body && body.hasOwnProperty("message")){
                            toastr.error(body.message);
                            return;
                        }

                        toastr.error('Error!', 'Something went wrong! Please refresh your browser or contact our support team to help you.');
                    }, complete: function(){
                        $btn_submit.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                    }
                });
            }
        });
    }

    return {
        init: function() {
            form_validation();
        }
    };
}();