var $current_series = 1;
var $series = $(".series-list");
var $content = $(".content-list");

var hidden_series_li = $("#hidden_series_li");
var hidden_series_schedule = $("#hidden_series_schedule");

jQuery(document).ready(function() {
    $(`#schedule_edit`).click(function(){
        $(this).next().removeClass('kt-hidden'); 
        $(this).addClass("kt-hidden"); 
        $(`#schedule_form`).find(".kt-portlet").removeClass("kt-hidden");
        $(`[name*="select_date"]`).prop("disabled", false); 
    });

    generateSeries();
    SCHEDULEFormDesign.init();
});

var SCHEDULEFormDesign = function() {
    var validator;
    $.validator.addMethod( "minDate", function( dateInput, element ) {
        var dateGiven = new Date(dateInput);
        var dateMinimum = new Date();
        dateMinimum.setDate(dateMinimum.getDate() + 16);

        return this.optional( element ) || dateGiven >= dateMinimum;
    }, "Please choose a date at least fifteen(15) days after tommorrow" );

    var schedule_validation = function() {
        validator = $("#schedule_form").validate({ 
            // define validation rules
            rules: {
                select_date: {
                    minDate: true, 
                },
            },
            //display error alert on form submit  
            invalidHandler: function(event, validator) { toastr.error("Please select a date first!") },
            submitHandler: function(form) {
                $(`input[name*="session_start"], input[name*="session_end"]`).each(function () {
                    $(this).rules("add", {
                        required: true,
                    });
                });

                if(schedule.length==0){
                    toastr.error(`Creating a schedule atleast one(1) is required!`);
                    return;
                }

                var total_first_series_dates = schedule.filter(val=>val.series==1);
                if(total_first_series_dates.length==0){
                    toastr.error(`Please complete your schedule on your First Series!`);
                    return;
                }

                for (let every_series = 1; every_series < ($current_series+1); every_series++) {
                    var current_series_dates = schedule.filter(val=>val.series==every_series);

                    if(current_series_dates.length!=total_first_series_dates.length){
                        toastr.error(`Series #${every_series} should be equal to the number of dates on the First Series`);
                        return;
                    }
                }

                var blank = 0;
                schedule.forEach(val => {
                    if(val.series==1){
                        if(val.sessions.length==0){
                            blank+=1;
                            return;
                        }
    
                        val.sessions.forEach(session => {
                            if(!session.start || !session.end){
                                blank+=1;
                                return;
                            }
                        });
                    }
                });

                if(blank>0){
                    toastr.error(`Please complete the blank/missing schedule fields on the first series!`);
                    $(`input[name*="session_start"], input[name*="session_end"]`).each(function () {
                        $(this).rules("add", {
                            required: true,
                        });
                    });
                    return;
                }

                $btn_submit = $("#schedule_submit");
                $btn_submit.addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true).html("Saving...");

                var submit_form = $.ajax({
                    url: '/webinar/management/details/schedule',
                    type: 'POST',
                    data: {
                        series_count: $current_series,
                        schedule: schedule,
                        _token: $('[name="_token"]').val(),
                    }, success: function(response) {
                        $btn_submit.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                        
                        $("#schedule_edit").removeClass('kt-hidden'); 
                        $("#schedule_submit").addClass("kt-hidden"); 
                        $(`#schedule_form`).find(".kt-portlet").addClass("kt-hidden");
                        
                        toastr.success("Schedule successfully saved!");
                        $("[name='select_date']").datepicker('setDate','');
                        $(`#webinar_schedule_parent`).find(".card-title").empty().html(`<span><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i> </span> &nbsp; &nbsp; <i class="fa fa-book circle-icon"></i> Schedule`);
                    }, error: function(response) {
                        $btn_submit.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                        var body = response.responseJSON;
                        if(body.hasOwnProperty("message")){
                            toastr.error(body.message);
                            return;
                        }

                        toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
                    }
                });
            }
        });
    }

    return {
        init: function() {
            schedule_validation();
        }
    };
}();

$(".add-series").click(function(){
    $current_series+=1;
    addSeries($current_series);
});

$(".delete-series").click(function(){
    deleteSeries($current_series);
});

function generateSeries(){
    if(schedule.length == 0){
        addSeries($current_series);
        return;
    }

    schedule.forEach((sched, index) => {
        var $wrapper = null;
        var date_id  = null;
        var date_exp = null;

        var series_number = sched.series;
        $current_series = sched.series;

        if($(`#series_li_${series_number}`).length == 0){
            $series.find("a").each(function(){
                $(this).removeClass("active");
            });

            $content.find("div.tab-pane").each(function(){
                $(this).removeClass("active");
            });
            
            var clone_li = hidden_series_li.clone();
            var clone_schedule = hidden_series_schedule.clone();
            
            clone_li.attr("id", `series_li_${series_number}`).removeClass("kt-hidden");
            clone_li.find("a").attr("href", `#series_schedule_${series_number}`).addClass("active").html(series_number);
            $series.append(clone_li);

            clone_schedule.attr("id", `series_schedule_${series_number}`).removeClass("kt-hidden").addClass("active");
            clone_schedule.find("input[name='series_number']").attr("id", `series_number_${series_number}`).val(series_number);
            clone_schedule.find("input[name='hidden_select_date']").attr({
                id: `series_select_date_${series_number}`,
                name: `select_date`
            });
            clone_schedule.find(".schedule-wrapper").attr("id", `series_schedule_wrapper_${series_number}`);
            $content.append(clone_schedule);

            $(`#series_select_date_${series_number}`).datepicker({
                autoclose: true,
                gotoCurrent: false,
                todayHighlight: true,
                orientation: "bottom left",
                templates: arrows
            }).on("change", function() {
                var value = $(this).val();
                
                var date_selected = new Date(value);
                var today = new Date();
                var fifteen_days_after = today.setDate(today.getDate() + 16);

                if(date_selected < fifteen_days_after){
                    toastr.error(`Please choose a date at least fifteen(15) days after tommorrow`);
                    return;
                }

                createScheduleForm(date_selected, series_number);
            });
        }

        $wrapper = $(`#series_schedule_wrapper_${sched.series}`);
        date_id  = sched.id;
        date_exp = sched.date_.split("-");

        if(series_number==1){
            var session_time_strings = ``;
            sched.sessions.forEach(e => {
                session_time_strings += `
                    <div data-repeater-item class="form-group row align-items-center grouped-sessions">
                        <div class="col-md-4">
                            <div class="kt-form__group--inline">
                                <div class="kt-form__control">
                                    <input type="text" name="[session_start_${date_id}]" value="${e.start}" class="form-control" placeholder="(From) 08:00 AM">
                                </div>
                            </div>
                            <div class="d-md-none kt-margin-b-10"></div>
                        </div>

                        <div class="col-md-4">
                            <div class="kt-form__group--inline">
                                <div class="kt-form__control">
                                    <input type="text" name="[session_end_${date_id}]" value="${e.end}" class="form-control" placeholder="(To) 10:30 AM">
                                </div>
                            </div>
                            <div class="d-md-none kt-margin-b-10"></div>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:;" data-repeater-delete="" class="btn btn-icon btn-danger btn-sm"><i class="la la-times"></i></a>
                        </div>
                    </div>
                `;
            });
            
            $wrapper.append(`
                <div class="col-12 kt-margin-t-5 kt-margin-b-10 kt_repeater_${date_id}_div" id="kt_repeater_${date_id}">
                    <div class="form-group form-group-last row" id="kt_repeater_${date_id}">
                        <label class="col-12 col-form-label kt-font-bolder">
                            <h5><a class="btn btn-icon btn-danger btn-sm" onclick="deleteSchedule(${date_id})"><i class="la la-times"></i></a> &nbsp; Schedule for ${string_month[parseInt(date_exp[1])]} ${date_exp[2]}, ${date_exp[0]} <text class="required">*</text></h5>
                        </label>
                        <div data-repeater-list="" class="col">
                            ${session_time_strings}
                        </div>
                    </div>
                    <div class="form-group form-group-last row">
                        <div class="col">
                            <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                <i class="la la-plus"></i> Add Schedule
                            </a>
                        </div>
                    </div>
                </div>
            `);

            $(`[name="[session_start_${date_id}]"]`).timepicker({
                defaultTime: "8:00 AM"
            }).change(function(){
                var date_sessions = [];
                var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);
                $group.each(function(){
                    date_sessions.push({
                        start: $(this).find("[name*='session_start']").val(),
                        end: $(this).find("[name*='session_end']").val()
                    });
                });

                schedule = schedule.map(val => {
                    if(val.id == date_id){
                        val.sessions = date_sessions;
                    }

                    return val;
                });
                arrangeTimeScheduleDayOrder();
            });

            $(`[name="[session_end_${date_id}]"]`).timepicker({
                defaultTime: "10:30 AM"
            }).change(function(){
                var date_sessions = [];
                var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);
                $group.each(function(){
                    date_sessions.push({
                        start: $(this).find("[name*='session_start']").val(),
                        end: $(this).find("[name*='session_end']").val()
                    });
                });

                schedule = schedule.map(val => {
                    if(val.id == date_id){
                        val.sessions = date_sessions;
                    }

                    return val;
                });
                arrangeTimeScheduleDayOrder();
            });

            $(`#kt_repeater_${date_id}`).repeater({
                initEmpty: false,
                show: function () {
                    $(this).slideDown();
                    
                    $(`[name*="session_start"]`).each(function(){
                        $(this).timepicker({
                            defaultTime: "8:00 AM"
                        }).change(function(){
                            var date_sessions = [];
                            var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);

                            $group.each(function(){
                                date_sessions.push({
                                    start: $(this).find("[name*='session_start']").val(),
                                    end: $(this).find("[name*='session_end']").val()
                                });
                            });

                            schedule = schedule.map(val => {
                                if(val.id == date_id){
                                    val.sessions = date_sessions;
                                }

                                return val;
                            });
                            arrangeTimeScheduleDayOrder();
                        });
                    });

                    $(`[name*="session_end"]`).each(function(){
                        $(this).timepicker({
                            defaultTime: "10:30 AM"
                        }).change(function(){
                            var date_sessions = [];
                            var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);
                            $group.each(function(){
                                date_sessions.push({
                                    start: $(this).find("[name*='session_start']").val(),
                                    end: $(this).find("[name*='session_end']").val()
                                });
                            });

                            schedule = schedule.map(val => {
                                if(val.id == date_id){
                                    val.sessions = date_sessions;
                                }

                                return val;
                            });
                            arrangeTimeScheduleDayOrder();
                        });
                    });

                    var date_sessions = [];
                    var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);
                    $group.each(function(){
                        date_sessions.push({
                            start: $(this).find("[name*='session_start']").val(),
                            end: $(this).find("[name*='session_end']").val()
                        });
                    });

                    schedule = schedule.map(val => {
                        if(val.id == date_id){
                            val.sessions = date_sessions;
                        }

                        return val;
                    });
                    arrangeTimeScheduleDayOrder();
                },

                hide: function (deleteElement) {                
                    $(this).slideUp(deleteElement);     
                    var name = $(this).find(`[name*='[session_start_${date_id}]']`).attr("name");
                    var arr_index = parseInt(name.split("[")[1].toString().split("]")[0]);
                    schedule = schedule.map(val => {
                        if(val.id == date_id){
                            val.sessions = val.sessions.filter((ses,i) => i != arr_index);
                        }

                        return val;
                    });
                    arrangeTimeScheduleDayOrder();
                }   
            });
        }else{
            var session_time_strings = ``;
            sched.sessions.forEach(e => {
                session_time_strings += `<span class="form-text text-muted">From ${e.start} to ${e.end}</span>`;
            });
            $wrapper.append(`
                <div class="col-12 kt-margin-t-5 kt-margin-b-10 kt_repeater_${date_id}_div" id="kt_repeater_${date_id}">
                    <div class="form-group form-group-last row" id="kt_repeater_${date_id}">
                        <label class="col-12 col-form-label kt-font-bolder">
                            <h5><a class="btn btn-icon btn-danger btn-sm" onclick="deleteSchedule(${date_id})"><i class="la la-times"></i></a> &nbsp; Schedule for ${string_month[parseInt(date_exp[1])]} ${date_exp[2]}, ${date_exp[0]} <text class="required">*</text></h5>
                        </label>
                        <div class="col-12" id="time-labels-${series_number}-${date_id}">
                            ${session_time_strings}    
                        </div>
                    </div>
                </div>
            `);
        }
    });
}

function deleteSeries(){
    if($current_series==1){
        toastr.error("Required at least one(1) series!");
        return;
    }

    $(`#series_li_${$current_series}`).remove();
    $(`#series_schedule_${$current_series}`).remove();
    schedule = schedule.filter(val => val.series!=$current_series);
    $current_series-=1;

    $(`#series_li_${$current_series}`).find("a").addClass("active");
    $(`#series_schedule_${$current_series}`).addClass("active");

    arrangeTimeScheduleDayOrder();
}

function addSeries(series_number){
    $series.find("a").each(function(){
        $(this).removeClass("active");
    });

    $content.find("div.tab-pane").each(function(){
        $(this).removeClass("active");
    });
    
    var clone_li = hidden_series_li.clone();
    var clone_schedule = hidden_series_schedule.clone();
    
    clone_li.attr("id", `series_li_${series_number}`).removeClass("kt-hidden");
    clone_li.find("a").attr("href", `#series_schedule_${series_number}`).addClass("active").html(series_number);
    $series.append(clone_li);

    clone_schedule.attr("id", `series_schedule_${series_number}`).removeClass("kt-hidden").addClass("active");
    clone_schedule.find("input[name='series_number']").attr("id", `series_number_${series_number}`).val(series_number);
    clone_schedule.find("input[name='hidden_select_date']").attr({
        id: `series_select_date_${series_number}`,
        name: `select_date`
    });
    clone_schedule.find(".schedule-wrapper").attr("id", `series_schedule_wrapper_${series_number}`);
    $content.append(clone_schedule);

    $(`#series_select_date_${series_number}`).datepicker({
        autoclose: true,
        gotoCurrent: false,
        todayHighlight: true,
        orientation: "bottom left",
        templates: arrows
    }).on("change", function() {
        var value = $(this).val();
        
        var date_selected = new Date(value);
        var today = new Date();
        var fifteen_days_after = today.setDate(today.getDate() + 16);

        if(date_selected < fifteen_days_after){
            toastr.error(`Please choose a date at least fifteen(15) days after tommorrow`);
            return;
        }

        createScheduleForm(date_selected, series_number);
    });
}

function generateSchedule(){
    var $wrapper = $(".schedule-wrapper");
    if(schedule.length > 0){
        schedule.forEach((val_, index) => {
            var date_id  = val_.id;
            var date_exp = val_.date_.split("-");

            if($current_series==1){
                $wrapper.append(`
                    <div class="col-12 kt-margin-t-5 kt-margin-b-10 kt_repeater_${date_id}_div" id="kt_repeater_${date_id}">
                        <div class="form-group form-group-last row" id="kt_repeater_${date_id}">
                            <label class="col-12 col-form-label kt-font-bolder">
                                <h5><a class="btn btn-icon btn-danger btn-sm" onclick="deleteSchedule(${date_id})"><i class="la la-times"></i></a> &nbsp; Schedule for ${string_month[parseInt(date_exp[1])]} ${date_exp[2]}, ${date_exp[0]} <text class="required">*</text></h5>
                            </label>
                            <div data-repeater-list="" class="col">
                                <div data-repeater-item class="form-group row align-items-center grouped-sessions">
                                    <div class="col-md-4">
                                        <div class="kt-form__group--inline">
                                            <div class="kt-form__control">
                                                <input type="text" name="[session_start_${date_id}]" class="form-control" placeholder="(From) 08:00 AM">
                                            </div>
                                        </div>
                                        <div class="d-md-none kt-margin-b-10"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="kt-form__group--inline">
                                            <div class="kt-form__control">
                                                <input type="text" name="[session_end_${date_id}]" class="form-control" placeholder="(To) 10:30 AM">
                                            </div>
                                        </div>
                                        <div class="d-md-none kt-margin-b-10"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="javascript:;" data-repeater-delete="" class="btn btn-icon btn-danger btn-sm"><i class="la la-times"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-last row">
                            <div class="col">
                                <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                    <i class="la la-plus"></i> Add Schedule
                                </a>
                            </div>
                        </div>
                    </div>
                `);

                $(`[name="[session_start_${date_id}]"]`).timepicker({
                    defaultTime: "8:00 AM"
                }).change(function(){
                    var date_sessions = [];
                    var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);
                    $group.each(function(){
                        date_sessions.push({
                            start: $(this).find("[name*='session_start']").val(),
                            end: $(this).find("[name*='session_end']").val()
                        });
                    });

                    schedule = schedule.map(val => {
                        if(val.id == date_id){
                            val.sessions = date_sessions;
                        }

                        return val;
                    });
                    arrangeTimeScheduleDayOrder();
                });

                $(`[name="[session_end_${date_id}]"]`).timepicker({
                    defaultTime: "10:30 AM"
                }).change(function(){
                    var date_sessions = [];
                    var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);
                    $group.each(function(){
                        date_sessions.push({
                            start: $(this).find("[name*='session_start']").val(),
                            end: $(this).find("[name*='session_end']").val()
                        });
                    });

                    schedule = schedule.map(val => {
                        if(val.id == date_id){
                            val.sessions = date_sessions;
                        }

                        return val;
                    });
                    arrangeTimeScheduleDayOrder();
                });

                $(`#kt_repeater_${date_id}`).repeater({
                    initEmpty: false,
                    show: function () {
                        $(this).slideDown();
                        

                        $(`[name*="session_start"]`).each(function(){
                            $(this).timepicker({
                                defaultTime: "8:00 AM"
                            }).change(function(){
                                var date_sessions = [];
                                var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);

                                $group.each(function(){
                                    date_sessions.push({
                                        start: $(this).find("[name*='session_start']").val(),
                                        end: $(this).find("[name*='session_end']").val()
                                    });
                                });

                                schedule = schedule.map(val => {
                                    if(val.id == date_id){
                                        val.sessions = date_sessions;
                                    }

                                    return val;
                                });
                                arrangeTimeScheduleDayOrder();
                            });
                        });

                        $(`[name*="session_end"]`).each(function(){
                            $(this).timepicker({
                                defaultTime: "10:30 AM"
                            }).change(function(){
                                var date_sessions = [];
                                var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);
                                $group.each(function(){
                                    date_sessions.push({
                                        start: $(this).find("[name*='session_start']").val(),
                                        end: $(this).find("[name*='session_end']").val()
                                    });
                                });

                                schedule = schedule.map(val => {
                                    if(val.id == date_id){
                                        val.sessions = date_sessions;
                                    }

                                    return val;
                                });
                                arrangeTimeScheduleDayOrder();
                            });
                        });

                        var date_sessions = [];
                        var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);
                        $group.each(function(){
                            date_sessions.push({
                                start: $(this).find("[name*='session_start']").val(),
                                end: $(this).find("[name*='session_end']").val()
                            });
                        });

                        schedule = schedule.map(val => {
                            if(val.id == date_id){
                                val.sessions = date_sessions;
                            }

                            return val;
                        });
                        
                        arrangeTimeScheduleDayOrder();
                    },

                    hide: function (deleteElement) {                
                        $(this).slideUp(deleteElement);     
                        var name = $(this).find(`[name*='[session_start_${date_id}]']`).attr("name");
                        var arr_index = parseInt(name.split("[")[1].toString().split("]")[0]);
                        schedule = schedule.map(val => {
                            if(val.id == date_id){
                                val.sessions = val.sessions.filter((ses,i) => i != arr_index);
                            }

                            return val;
                        });

                        arrangeTimeScheduleDayOrder();
                    }   
                });
            }else{
                $wrapper.append(`
                    <div class="col-12 kt-margin-t-5 kt-margin-b-10 kt_repeater_${date_id}_div">
                        <div class="form-group form-group-last row">
                            <label class="col-12 col-form-label kt-font-bolder">
                                <h5><a class="btn btn-icon btn-danger btn-sm" onclick="deleteSchedule(${date_id})"><i class="la la-times"></i></a> &nbsp; Schedule for ${string_month[parseInt(date_exp[1])]} ${date_exp[2]}, ${date_exp[0]} <text class="required">*</text></h5>
                            </label>
                            <div class="col-12" id="time-labels-${series}-${date_id}">
                                <span class="form-text text-muted">From 8:00 AM to 10:30 AM</span>
                            </div>
                        </div>
                    </div>
                `);

                arrangeTimeScheduleDayOrder();
            }
        });
    }
}   

function createScheduleForm(selected_date, series){
    if(selected_date=="Invalid Date"){
        return;
    }

    if(series > 1){
        $first_series_dates = schedule.filter(val=>val.series==1);
        $current_series_dates = schedule.filter(val=>val.series==series);

        if($first_series_dates.length == $current_series_dates.length){
            toastr.error("Can't add more date on this series!");
            return;
        }
    }

    var $wrapper = $(`#series_schedule_wrapper_${series}`);
    var date_id = `${selected_date.getFullYear()}${selected_date.getMonth()+1<10 ? "0": ""}${selected_date.getMonth()+1}${selected_date.getDate()<10? "0": ""}${selected_date.getDate()}`;
    var date_formatted = `${selected_date.getFullYear()}-${selected_date.getMonth()+1}-${selected_date.getDate()}`;

    if(schedule.length > 0){
        if(schedule.find(val => val.id==date_id)){
            toastr.info(`Date already added!`);
            return;
        }
    }

    if(series==1){
        schedule.push({
            series: series, 
            id: date_id,
            date_: date_formatted,
            sessions: [{
                start: "8:00 AM",
                end: "10:30 AM",
            }]
        });

        $wrapper.append(`
            <div class="col-12 kt-margin-t-5 kt-margin-b-10 kt_repeater_${date_id}_div" id="kt_repeater_${date_id}">
                <div class="form-group form-group-last row" id="kt_repeater_${date_id}">
                    <label class="col-12 col-form-label kt-font-bolder">
                        <h5><a class="btn btn-icon btn-danger btn-sm" onclick="deleteSchedule(${date_id})"><i class="la la-times"></i></a> &nbsp; Schedule for ${string_month[selected_date.getMonth()+1]} ${selected_date.getDate()}, ${selected_date.getFullYear()} <text class="required">*</text></h5>
                    </label>
                    <div data-repeater-list="" class="col">
                        <div data-repeater-item class="form-group row align-items-center grouped-sessions">
                            <div class="col-md-4">
                                <div class="kt-form__group--inline">
                                    <div class="kt-form__control">
                                        <input type="text" name="[session_start_${date_id}]" class="form-control" placeholder="(From) 08:00 AM">
                                    </div>
                                </div>
                                <div class="d-md-none kt-margin-b-10"></div>
                            </div>

                            <div class="col-md-4">
                                <div class="kt-form__group--inline">
                                    <div class="kt-form__control">
                                        <input type="text" name="[session_end_${date_id}]" class="form-control" placeholder="(To) 10:30 AM">
                                    </div>
                                </div>
                                <div class="d-md-none kt-margin-b-10"></div>
                            </div>
                            <div class="col-md-4">
                                <a href="javascript:;" data-repeater-delete="" class="btn btn-icon btn-danger btn-sm"><i class="la la-times"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-last row">
                    <div class="col">
                        <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                            <i class="la la-plus"></i> Add Schedule
                        </a>
                    </div>
                </div>
            </div>
        `);

        $(`[name="[session_start_${date_id}]"]`).timepicker({
            defaultTime: "8:00 AM"
        }).change(function(){
            var date_sessions = [];
            var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);
            $group.each(function(){
                date_sessions.push({
                    start: $(this).find("[name*='session_start']").val(),
                    end: $(this).find("[name*='session_end']").val()
                });
            });

            schedule = schedule.map(val => {
                if(val.id == date_id){
                    val.sessions = date_sessions;
                }

                return val;
            });

            arrangeTimeScheduleDayOrder();
        });

        $(`[name="[session_end_${date_id}]"]`).timepicker({
            defaultTime: "10:30 AM"
        }).change(function(){
            var date_sessions = [];
            var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);
            $group.each(function(){
                date_sessions.push({
                    start: $(this).find("[name*='session_start']").val(),
                    end: $(this).find("[name*='session_end']").val()
                });
            });

            schedule = schedule.map(val => {
                if(val.id == date_id){
                    val.sessions = date_sessions;
                }

                return val;
            });

            arrangeTimeScheduleDayOrder();
        });

        $(`#kt_repeater_${date_id}`).repeater({
            initEmpty: false,
            show: function () {
                $(this).slideDown();
                $(`[name*="session_start"]`).each(function(){
                    $(this).timepicker({
                        defaultTime: "8:00 AM"
                    }).change(function(){
                        var date_sessions = [];
                        var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);

                        $group.each(function(){
                            date_sessions.push({
                                start: $(this).find("[name*='session_start']").val(),
                                end: $(this).find("[name*='session_end']").val()
                            });
                        });

                        schedule = schedule.map(val => {
                            if(val.id == date_id){
                                val.sessions = date_sessions;
                            }

                            return val;
                        });

                        arrangeTimeScheduleDayOrder();
                    });
                });

                $(`[name*="session_end"]`).each(function(){
                    $(this).timepicker({
                        defaultTime: "10:30 AM"
                    }).change(function(){
                        var date_sessions = [];
                        var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);
                        $group.each(function(){
                            date_sessions.push({
                                start: $(this).find("[name*='session_start']").val(),
                                end: $(this).find("[name*='session_end']").val()
                            });
                        });

                        schedule = schedule.map(val => {
                            if(val.id == date_id){
                                val.sessions = date_sessions;
                            }

                            return val;
                        });

                        arrangeTimeScheduleDayOrder();
                    });
                });

                var date_sessions = [];
                var $group = $(`#kt_repeater_${date_id}`).find(`.grouped-sessions`);
                $group.each(function(){
                    date_sessions.push({
                        start: $(this).find("[name*='session_start']").val(),
                        end: $(this).find("[name*='session_end']").val()
                    });
                });

                schedule = schedule.map(val => {
                    if(val.id == date_id){
                        val.sessions = date_sessions;
                    }

                    return val;
                });

                arrangeTimeScheduleDayOrder();
            },

            hide: function (deleteElement) {                
                $(this).slideUp(deleteElement);     
                var name = $(this).find(`[name*='[session_start_${date_id}]']`).attr("name");
                var arr_index = parseInt(name.split("[")[1].toString().split("]")[0]);
                schedule = schedule.map(val => {
                    if(val.id == date_id){
                        val.sessions = val.sessions.filter((ses,i) => i != arr_index);
                    }

                    return val;
                });

                arrangeTimeScheduleDayOrder();
            }     
        });

    }else{
        schedule.push({
            series: series, 
            id: date_id,
            date_: date_formatted,
        });

        $wrapper.append(`
            <div class="col-12 kt-margin-t-5 kt-margin-b-10 kt_repeater_${date_id}_div">
                <div class="form-group form-group-last row">
                    <label class="col-12 col-form-label kt-font-bolder">
                        <h5><a class="btn btn-icon btn-danger btn-sm" onclick="deleteSchedule(${date_id})"><i class="la la-times"></i></a> &nbsp; Schedule for ${string_month[selected_date.getMonth()+1]} ${selected_date.getDate()}, ${selected_date.getFullYear()} <text class="required">*</text></h5>
                    </label>
                    <div class="col-12" id="time-labels-${series}-${date_id}">
                        <span class="form-text text-muted">From 8:00 AM to 10:30 AM</span>
                    </div>
                </div>
            </div>
        `);

        arrangeTimeScheduleDayOrder();
    }
}

function deleteSchedule(id){
    $(`.kt_repeater_${id}_div`).remove();
    schedule = schedule.filter(val => val.id!=id);
    
    arrangeTimeScheduleDayOrder();
}

function arrangeTimeScheduleDayOrder(){
    var series = {};

    var first_sessions = [];
    var current_series_index = 0;
    var current_ss_index = 0;
    schedule.forEach((sched,index) => {
        if(sched.series == 1){
            first_sessions.push(sched.sessions);
        }else{
            if(current_series_index==sched.series){
                series[current_series_index].push({
                    series: sched.series,
                    id: sched.id,
                    date_: sched.date_,
                    sessions: first_sessions[current_ss_index]
                });
            }else{
                current_series_index = sched.series;
                current_ss_index = 0;

                series[current_series_index] = [];
                series[current_series_index].push({
                    series: sched.series,
                    id: sched.id,
                    date_: sched.date_,
                    sessions: first_sessions[current_ss_index]
                });
            }
            current_ss_index++;
        }
    });

    for (let i = 1; i < (current_series_index+1); i++) {
        var current_series = series[i];

        if(current_series){
            current_series.forEach(sched => {
                $(`#time-labels-${sched.series}-${sched.id}`).empty();
                sched.sessions.forEach(sess=>{
                   $(`#time-labels-${sched.series}-${sched.id}`).append(`<span class="form-text text-muted">From ${sess.start} to ${sess.end}</span>`);
                });
            });
        }
    }
}