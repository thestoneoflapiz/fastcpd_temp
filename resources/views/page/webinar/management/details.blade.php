@extends('template.webinar.master_creation')

@section('styles')
<style>
    .accordion .card .card-header .card-title.collapsed > i{color:#5d78ff;}
    .accordion.accordion-toggle-arrow .card .card-header .card-title{color:#343a40;}
    .fastcpd-background{background-size:cover;height:320px;width:100%;border:1px solid #F1F2F7;border-radius:5px;-webkit-box-shadow: 4px 4px 10px -9px rgba(0,0,0,0.49);-moz-box-shadow: 4px 4px 10px -9px rgba(0,0,0,0.49);box-shadow: 4px 4px 10px -9px rgba(0,0,0,0.49);}
</style>
@endsection

@section('content')
{{ csrf_field() }}
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Webinar Details
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <!--begin::Accordion-->
        <div class="accordion  accordion-toggle-arrow">
            <div class="card">
                <div class="card-header" id="webinar_info_parent">
                    <div class="card-title collapsed" data-toggle="collapse" data-target="#webinar_info_acc" aria-expanded="false" aria-controls="webinar_info_acc">
                        @if(_current_webinar()->description && _current_webinar()->headline && _current_webinar()->language) <span><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i> </span> &nbsp; &nbsp; @endif<i class="fa fa-book circle-icon"></i> Basic Information 
                    </div>
                </div>
                <div id="webinar_info_acc" class="collapse" aria-labelledby="webinar_info_parent" data-parent="#webinar_info_parent">
                    <div class="card-body">
                        <form class="kt-form kt-form--label-left" id="info_form">
                            <div class="row form-group">
                                <div class="col-xl-9 col-lg-9 col-12">
                                    <label class="bold">What is your webinar title? <text class="required">*</text></label>
                                    <input class="form-control" id="webinar_title" type="text" name="webinar_title" value="{{ _current_webinar()->title ??  ''}}">
                                    <span class="form-text text-muted">Please enter your webinar title.</span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-xl-9 col-lg-9 col-12">
                                    <label class="bold">Headline<text class="required">*</text></label>
                                    <input class="form-control" id="headline" type="text" name="headline" value="{{ _current_webinar()->headline ??  ''}}">
                                    <span class="form-text text-muted">Please enter your headline.</span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-xl-9 col-lg-9 col-12" id="about_group">
                                    <label>Description <text class="required">*</text></label>
                                    <div id="textarea_about" style="{{ _current_webinar()->description ? 'display:none;' : '' }}">
                                        <textarea class="form-control" minlength="50" id="about" name="about">{{ htmlspecialchars_decode(_current_webinar()->description) ?? '' }}</textarea>
                                    </div>
                                    <div id="div_about" class="box" style="{{ _current_webinar()->description ? '' : 'display:none;' }}">
                                        <?= htmlspecialchars_decode(_current_webinar()->description) ?? "" ?>
                                    </div>
                                    <span class="form-text text-muted">Please provide the decription of your webinar</span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-xl-9 col-lg-9 col-12">
                                    <label class="bold">What language will you use? <text class="required">*</text></label>
                                    <select class="form-control" id="language" name="language">

                                        <option value="english" <?= _current_webinar()->language == "english" ? "selected" : "" ?> >English</option>
                                        <option value="tagalog" <?= _current_webinar()->language == "tagalog" ? "selected" : "" ?>>Tagalog</option>
                                        <option value="mixed" <?= _current_webinar()->language == "mixed" ? "selected" : "" ?>>Mixed ( Tagalog & English )</option>

                                    </select>
                                    <span class="form-text text-muted">Please select your language.</span>
                                </div>
                            </div>
                            <div class="row kt-margin-b-10" style="float:right">
                                <div class="col-lg-12 ml-lg-xl-auto">
                                    <button class="btn btn-success" id="info_submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="webinar_schedule_parent">
                    <div class="card-title collapsed" data-toggle="collapse" data-target="#webinar_schedule_acc" aria-expanded="false" aria-controls="webinar_schedule_acc" id="webinar_schedule_acc_title">
                        @if($schedule) <span><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i> </span> &nbsp; &nbsp; @endif<i class="fa fa-calendar-day circle-icon"></i> Schedule
                    </div>
                </div>
                <div id="webinar_schedule_acc" class="collapse" aria-labelledby="headingTwo1" data-parent="#webinar_schedule_parent">
                    <div class="card-body">
                        <div class="alert alert-dark <?=$schedule ? "" : "kt-hidden"?>" role="alert">
                            <div class="alert-text">
                                <h4 class="alert-heading">The more you know!</h4>
                                <p>Removing or Editing your schedule may affect your <b>Webinar & Content!</b></p>
                                <hr>
                                <p class="mb-0">Please make sure your schedule is final before editing <b>Webinar & Content</b> to keep things nice and tidy.</p>
                            </div>
                        </div>
                        @if(_current_webinar()->event=="day")
                        <form class="kt-form kt-form--label-left" id="schedule_form" autocomplete="off">
                            <div class="row form-group">
                                <div class="col-xl-9 col-lg-9 col-12">
                                    <label class="bold">Add Date<text class="required">*</text></label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control datepicker" placeholder="Add Date" name="select_date" <?=$schedule ? "disabled" : ""?>/>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="la la-calendar-check-o"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="form-text text-muted">Please select a date fifteen(15) days after tomorrow</span>
                                </div>
                            </div>
                            <div class="row schedule-wrapper <?=$schedule ? "kt-hidden" : ""?>"></div>
                            <div class="row kt-margin-b-10" style="float:right">
                                <div class="col-lg-12 ml-lg-xl-auto">
                                    <span class="btn btn-info <?=$schedule ? "" : "kt-hidden"?>" id="schedule_edit">Edit</span>
                                    <button class="btn btn-success <?=$schedule ? "kt-hidden" : ""?>" id="schedule_submit">Submit</button>
                                </div>
                            </div>
                        </form>
                        @else
                        <form class="kt-form kt-form--label-left" id="schedule_form" autocomplete="off">
                            <div class="kt-portlet kt-portlet--tabs <?=$schedule ? "kt-hidden" : ""?>">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-toolbar">
                                        <ul class="nav nav-pills nav-pills-sm nav-tabs-bold series-list" role="tablist">
                                            <li class="nav-item kt-hidden" id="hidden_series_li" style="margin:auto">
                                                <a class="nav-link active" data-toggle="tab" href="#series_schedule_1" role="tab">1</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                        <ul class="nav nav-pills nav-pills-sm nav-pills-bold" role="tablist">
                                            <li class="nav-item" style="margin:auto">
                                                <button type="button" class="btn btn-info btn-sm btn-icon add-series" data-toggle="kt-tooltip" data-placement="top" title="Click here to add Series"><i class="fa fa-plus"></i></button>
                                                <button type="button" class="btn btn-label-danger btn-sm btn-icon delete-series" data-toggle="kt-tooltip" data-placement="top" title="Click here to delete Series"><i class="la la-times"></i></button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="tab-content content-list">
                                        <div class="tab-pane kt-hidden" id="hidden_series_schedule" role="tabpanel">
                                            <input type="hidden" value="1" name="series_number"/>
                                            <div class="row form-group">
                                                <div class="col-xl-9 col-lg-9 col-12">
                                                    <label class="bold">Add Date<text class="required">*</text></label>
                                                    <div class="input-group date">
                                                        <input type="text" class="form-control datepicker" name="hidden_select_date" placeholder="Add Date"  <?=$schedule ? "disabled" : ""?>/>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="la la-calendar-check-o"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <span class="form-text text-muted">Please select a date fifteen(15) days after tomorrow</span>
                                                </div>
                                            </div>
                                            <div class="row schedule-wrapper"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row kt-margin-b-10" style="float:right">
                                <div class="col-lg-12 ml-lg-xl-auto">
                                    <span class="btn btn-info <?=$schedule ? "" : "kt-hidden"?>" id="schedule_edit">Edit</span>
                                    <button class="btn btn-success <?=$schedule ? "kt-hidden" : ""?>" id="schedule_submit">Submit</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!--end::Accordion-->
    </div>
</div>
@endsection

@section('scripts')
<script>
    var schedule = <?=$schedule ? json_encode($format) : json_encode([]) ?>;
    var string_month = ["", "Jan.", "Feb.", "Mar.", "Apr.", "May", "June", "July", "Aug.", "Sept.", "Oct.", "Nov.", "Dec."];
    var toolbar_show = [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
    ];

    var arrows;
    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
    } else {
        arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }

    jQuery(document).ready(function() {
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
        
        $('textarea#about').summernote({
            height: 80,
            toolbar: toolbar_show,
        });

        $(document).on('click', function(e) {
            if ($(e.target).closest("#about_group").length === 0) {
                var about_value = $.trim($("#about").val());
                if(about_value && jQuery(about_value).text() != ""){
                    $("#div_about").html($.trim($("#about").val())).show();
                    $("#textarea_about").hide();
                }
            }
        });

        $("#about_group").click(function() {
            $("#textarea_about").show();
            $("#div_about").hide();
        });

        $('form').each(function() {
            if ($(this).data('validator'))
                $(this).data('validator').settings.ignore = ".note-editor *";
        });

        INFOFormDesign.init();
    });

    var INFOFormDesign = function() {
        var validator;
       
        var info_validation = function() {
            validator = $("#info_form").validate({ 
                // define validation rules
                rules: {
                    webinar_title: {
                        required: true,
                        maxlength: 80,
                    },
                    headline: {
                        required: true,
                        maxlength: 120,
                    },
                    about: {
                        required: true,
                        minlength: 50,
                    },
                    language: {
                        required: true,
                    }

                },

                //display error alert on form submit  
                invalidHandler: function(event, validator) {
                    toastr.error("Required fields in Basic Information!");
                },

                submitHandler: function(form) {
                    $btn_submit = $("#info_submit");
                    $btn_submit.addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true).html("Saving...");
                    var submit_form = $.ajax({
                        url: '/webinar/management/details/info',
                        type: 'POST',
                        data: {
                            title: $('#webinar_title').val(),
                            headline: $('#headline').val(),
                            about: $('#about').val(),
                            language:$("#language").val(),
                            _token: "{{ csrf_token() }}",
                        }, success: function(response) {
                            $btn_submit.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");

                            toastr.success("Basic Information successfully saved!");
                            $(`#webinar_info_parent`).find(".card-title").empty().html(`<span><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i> </span> &nbsp; &nbsp; <i class="fa fa-book circle-icon"></i> Basic Information `);
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
                info_validation();
            }
        };
    }();
</script>
@if(_current_webinar()->event=="day")
<script src="{{asset('js/webinar/creation/details/day.js')}}" type="text/javascript"></script>
@else
<script src="{{asset('js/webinar/creation/details/series.js')}}" type="text/javascript"></script>
@endif
@endsection