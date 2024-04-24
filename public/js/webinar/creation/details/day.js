jQuery(document).ready(function() {
    $(`#schedule_edit`).click(function(){
        $(this).next().removeClass('kt-hidden'); 
        $(this).addClass("kt-hidden"); 
        $(`[name="select_date"]`).prop("disabled", false);
        $(`.schedule-wrapper`).removeClass("kt-hidden");
    });

    $("[name='select_date']").datepicker({
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

        createScheduleForm(date_selected);
    });

    generateSchedule();
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
            invalidHandler: function(event, validator) { },
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

                var blank = 0;
                schedule.forEach((val, i) => {
                    if(i==0){
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
                    toastr.error(`Please complete the blank/missing schedule fields!`);
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
                        schedule: schedule,
                        _token: $('[name="_token"]').val(),
                    }, success: function(response) {
                        $btn_submit.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                        $(`[name="select_date"]`).prop("disabled", true);
                        $(`.alert, #schedule_edit`).removeClass("kt-hidden");
                        $(`.schedule-wrapper, #schedule_submit`).addClass("kt-hidden");

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

function generateSchedule(){
    var $wrapper = $(".schedule-wrapper");
    if(schedule.length > 0){
        schedule.forEach((val_, index)=> {
            var date_id  = val_.id;
            var date_exp = val_.date_.split("-");

            var session_time_strings = ``;
            var session_time_fields = ``;
            val_.sessions.forEach(e => {
                session_time_strings += `<span class="form-text text-muted">From ${e.start} to ${e.end}</span>`;
                session_time_fields += `
                    <div data-repeater-item class="form-group row align-items-center grouped-sessions">
                        <div class="col-md-4">
                            <div class="kt-form__group--inline">
                                <div class="kt-form__control">
                                    <input type="text" name="[session_start]" value="${e.start}" class="form-control" placeholder="(From) 08:00 AM">
                                </div>
                            </div>
                            <div class="d-md-none kt-margin-b-10"></div>
                        </div>

                        <div class="col-md-4">
                            <div class="kt-form__group--inline">
                                <div class="kt-form__control">
                                    <input type="text" name="[session_end]" value="${e.end}" class="form-control" placeholder="(To) 10:30 AM">
                                </div>
                            </div>
                            <div class="d-md-none kt-margin-b-10"></div>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:;" data-repeater-delete="" class="btn btn-icon btn-danger btn-sm"><i class="la la-trash-o"></i></a>
                        </div>
                    </div>
                `;
            });
            
            $(`[name*="[session_start"]`).timepicker({
                defaultTime: "8:00 AM"
            }).change(function(){
                generateScheduleUI();
            });
    
            $(`[name*="[session_end]"]`).timepicker({
                defaultTime: "10:30 AM"
            }).change(function(){
                generateScheduleUI();
            });

            if(index==0){
                $wrapper.append(`
                <div class="col-12 kt-margin-t-5 kt-margin-b-10" id="kt_repeater_time">
                    <div class="form-group form-group-last row" id="kt_repeater_time">
                        <label class="col-12 col-form-label kt-font-bolder">
                            <h5><i class="la la-clock-o"></i> Schedule for each day <text class="required">*</text></h5>
                        </label>
                        <div data-repeater-list="" class="col">
                            ${session_time_fields}
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

                $(`#kt_repeater_time`).repeater({
                    initEmpty: false,
                    show: function () {
                        $(this).slideDown();
                        
                        $(`[name*="session_start"]`).each(function(){
                            $(this).timepicker({
                                defaultTime: "8:00 AM"
                            }).change(function(){
                                generateScheduleUI();
                            });
                        });
                
                        $(`[name*="session_end"]`).each(function(){
                            $(this).timepicker({
                                defaultTime: "10:30 AM"
                            }).change(function(){
                                generateScheduleUI();
                            });
                        });
                
                        generateScheduleUI();
                    },
                
                    hide: function (deleteElement) {                
                        $(this).slideUp(deleteElement);     
                        setTimeout(() => {
                            generateScheduleUI();
                        }, 1000);
                    }   
                });
            }

            $wrapper.append(`
                <div class="col-12 kt-margin-t-5 kt-margin-b-10 kt_schedule_date${date_id}_div">
                    <div class="form-group form-group-last row">
                        <label class="col-12 col-form-label kt-font-bolder">
                            <h5><a class="btn btn-icon btn-danger btn-sm" onclick="deleteSchedule(${date_id})"><i class="la la-trash-o"></i></a> &nbsp; Schedule for ${string_month[parseInt(date_exp[1])]} ${date_exp[2]}, ${date_exp[0]} <text class="required">*</text></h5>
                        </label>
                        <div class="col-12 time-labels">
                            ${session_time_strings}
                        </div>
                    </div>
                </div>
            `);
        });
    }
}   

function createScheduleForm(selected_date){
    if(selected_date=="Invalid Date"){
        return;
    }

    var $wrapper = $(".schedule-wrapper");

    var date_id = `${selected_date.getFullYear()}${selected_date.getMonth()+1<10 ? "0": ""}${selected_date.getMonth()+1}${selected_date.getDate()<10? "0": ""}${selected_date.getDate()}`;
    var date_formatted = `${selected_date.getFullYear()}-${selected_date.getMonth()+1}-${selected_date.getDate()}`;

    if(schedule.length > 0){
        if(schedule.find(val => val.id==date_id)){
            return;
        }
    }

    if(schedule.length == 0){
        schedule.push({
            id: date_id,
            date_: date_formatted,
            sessions: [{
                start: "8:00 AM",
                end: "10:30 AM",
            }]
        });
        
        $wrapper.append(`
            <div class="col-12 kt-margin-t-5 kt-margin-b-10" id="kt_repeater_time">
                <div class="form-group form-group-last row" id="kt_repeater_time">
                    <label class="col-12 col-form-label kt-font-bolder">
                        <h5><i class="la la-clock-o"></i> Schedule for each day <text class="required">*</text></h5>
                    </label>
                    <div data-repeater-list="" class="col">
                        <div data-repeater-item class="form-group row align-items-center grouped-sessions">
                            <div class="col-md-4">
                                <div class="kt-form__group--inline">
                                    <div class="kt-form__control">
                                        <input type="text" name="[session_start]" class="form-control" placeholder="(From) 08:00 AM">
                                    </div>
                                </div>
                                <div class="d-md-none kt-margin-b-10"></div>
                            </div>

                            <div class="col-md-4">
                                <div class="kt-form__group--inline">
                                    <div class="kt-form__control">
                                        <input type="text" name="[session_end]" class="form-control" placeholder="(To) 10:30 AM">
                                    </div>
                                </div>
                                <div class="d-md-none kt-margin-b-10"></div>
                            </div>
                            <div class="col-md-4">
                                <a href="javascript:;" data-repeater-delete="" class="btn btn-icon btn-danger btn-sm"><i class="la la-trash-o"></i></a>
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
        $wrapper.append(`
            <div class="col-12 kt-margin-t-5 kt-margin-b-10 kt_schedule_date${date_id}_div">
                <div class="form-group form-group-last row">
                    <label class="col-12 col-form-label kt-font-bolder">
                        <h5><a class="btn btn-icon btn-danger btn-sm" onclick="deleteSchedule(${date_id})"><i class="la la-trash-o"></i></a> &nbsp; Schedule for ${string_month[selected_date.getMonth()+1]} ${selected_date.getDate()}, ${selected_date.getFullYear()} <text class="required">*</text></h5>
                    </label>
                    <div class="col-12 time-labels">
                        <span>From 8:00 AM to 10:30 AM</span>
                    </div>
                </div>
            </div>
        `);

        $(`[name*="[session_start]"]`).timepicker({
            defaultTime: "8:00 AM"
        }).change(function(){
            generateScheduleUI();
        });

        $(`[name*="[session_end]"]`).timepicker({
            defaultTime: "10:30 AM"
        }).change(function(){
            generateScheduleUI();
        });

        $(`#kt_repeater_time`).repeater({
            initEmpty: false,
            show: function () {
                $(this).slideDown();

                $(`[name*="session_start"]`).each(function(){
                    $(this).timepicker({
                        defaultTime: "8:00 AM"
                    }).change(function(){
                        generateScheduleUI();
                    });
                });

                $(`[name*="session_end"]`).each(function(){
                    $(this).timepicker({
                        defaultTime: "10:30 AM"
                    }).change(function(){
                        generateScheduleUI();
                    });
                });

                generateScheduleUI();
            },            

            hide: function (deleteElement) {                
                $(this).slideUp(deleteElement);  
                setTimeout(() => {
                    generateScheduleUI();
                }, 1000);
            }   
        });
    }else{
        schedule.push({
            id: date_id,
            date_: date_formatted,
        });

        $wrapper.append(`
            <div class="col-12 kt-margin-t-5 kt-margin-b-10 kt_schedule_date${date_id}_div">
                <div class="form-group form-group-last row">
                    <label class="col-12 col-form-label kt-font-bolder">
                        <h5><a class="btn btn-icon btn-danger btn-sm" onclick="deleteSchedule(${date_id})"><i class="la la-trash-o"></i></a> &nbsp; Schedule for ${string_month[selected_date.getMonth()+1]} ${selected_date.getDate()}, ${selected_date.getFullYear()} <text class="required">*</text></h5>
                    </label>
                    <div class="col-12 time-labels">
                    </div>
                </div>
            </div>
        `);

        generateScheduleUI();
    }
}

function deleteSchedule(id){
    $(`.kt_schedule_date${id}_div`).remove();
    schedule = schedule.filter(val => val.id!=id);
}

function generateScheduleUI(){
    var date_sessions = [];
    var $group = $(`#kt_repeater_time`).find(`.grouped-sessions`);
    var session_time_strings = ``;
    
    $group.each(function(){
        var start = $(this).find("[name*='session_start']").val();
        var end = $(this).find("[name*='session_end']").val();
        date_sessions.push({
            start: start,
            end: end,
        });

        session_time_strings += `<span class="form-text text-muted">From ${start} to ${end}</span>`;
    });

    $(".time-labels").empty().append(session_time_strings);
    schedule = schedule.map(val => {
        val.sessions = date_sessions;
        return val;
    });
}