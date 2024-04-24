@extends('template.master_course_creation')

@section('styles')
@endsection

@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Course Details
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <form class="kt-form kt-form--label-left" id="form">
            <div class="kt-portlet__body">
                <div class="kt-form__content">
                    <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="form_msg">
                        <div class="kt-alert__icon">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="kt-alert__text">&nbsp; Sorry! You have to complete the form requirements first!</div>
                        <div class="kt-alert__close">
                            <button type="button" class="close" data-close="alert" aria-label="Close">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
                        <div class="form-group">
                            <label class="bold">What is your course title? <text class="required">*</text></label>
                            <input class="form-control" id="course_title" type="text" name="course_title" value="{{ _current_course()->title ??  ''}}">
                            <span class="form-text text-muted">Please enter your course title.</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
                        <div class="form-group">
                            <label class="bold">Headline<text class="required">*</text></label>
                            <input class="form-control" id="headline" type="text" name="headline" value="{{ _current_course()->headline ??  ''}}">
                            <span class="form-text text-muted">Please enter your headline.</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12" id="about_group">
                        <div class="form-group">
                            <label>Description <text class="required">*</text></label>
                            <div id="textarea_about" style="{{ _current_course()->description ? 'display:none;' : '' }}">
                                <textarea class="form-control" minlength="50" id="about" name="about">{{ htmlspecialchars_decode(_current_course()->description) ?? '' }}</textarea>
                            </div>
                            <div id="div_about" class="box" style="{{ _current_course()->description ? '' : 'display:none;' }}">
                                <?= htmlspecialchars_decode(_current_course()->description) ?? "" ?>
                            </div>
                            <span class="form-text text-muted">Please provide the decription of your course</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label class="bold">Start Date<text class="required">*</text></label>
                            <div class="input-group date">
                                <input type="text" class="form-control datepicker" <?=_current_course()->prc_status == "active" ? "disabled" : "" ?> name="start_date" value="{{ _current_course()->session_start ? date('m/d/Y',strtotime(_current_course()->session_start)) : '' }}" readonly placeholder="Select date" id="start_date" />
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="la la-calendar-check-o"></i>
                                    </span>
                                </div>
                            </div>
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label class="bold">End Date<text class="required">*</text></label>
                            <div class="input-group date">
                                <input type="text" class="form-control datepicker" disabled name="end_date" value="{{ _current_course()->session_end ? date('m/d/Y',strtotime(_current_course()->session_end)) : '' }}" readonly placeholder="Select Start Date First" id="end_date" />
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="la la-calendar-check-o"></i>
                                    </span>
                                </div>
                            </div>
                            <span class="form-text text-muted">End Date is automatically 1 year after the Start date.</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
                        <div class="form-group">
                            <label class="bold">What language will you use? <text class="required">*</text></label>
                            <select class="form-control" id="language" name="language">

                                <option value="english" <?= _current_course()->language == "english" ? "selected" : "" ?> >English</option>
                                <option value="tagalog" <?= _current_course()->language == "tagalog" ? "selected" : "" ?>>Tagalog</option>
                                <option value="mixed" <?= _current_course()->language == "mixed" ? "selected" : "" ?>>Mixed ( Tagalog & English )</option>

                            </select>
                            <span class="form-text text-muted">Please select your language.</span>
                        </div>
                    </div>

                </div>

            </div>
            <div class="kt-portlet__foot">
                <div class="row" style="float:right">
                    <div class="col-lg-12 ml-lg-xl-auto">
                        <button id="submit_form" class="btn btn-outline-success">Submit</button>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <button type="reset" class="btn btn-secondary">Clear</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var toolbar_show = [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
    ];

    jQuery(document).ready(function() {
        FormDesign.init();

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

        $(".datepicker").datepicker({
            autoclose: true,
            gotoCurrent: false,
            todayHighlight: true,
            orientation: "bottom left",
            templates: arrows
        });

        $("#start_date").datepicker();
        $("#end_date").datepicker();

        $("#start_date").on("change", function() {
            $("#start_date").datepicker("option", "showAnim", $(this).val());
            var d = $("#start_date").datepicker("getDate");
            d.setFullYear(d.getFullYear() + 1);
            $("#end_date").datepicker("setDate", d);
        });

        $("#about_group").click(function() {
            $("#textarea_about").show();
            $("#div_about").hide();
        });

        $('form').each(function() {
            if ($(this).data('validator'))
                $(this).data('validator').settings.ignore = ".note-editor *";
        });
    });

    var FormDesign = function() {
        var validator;
        $.validator.addMethod( "minDate", function( dateInput, element ) {
            var dateGiven = new Date(dateInput);
            var dateMinimum = new Date();
            dateMinimum.setDate(dateMinimum.getDate() + 16);

            return this.optional( element ) || dateGiven > dateMinimum;
        }, "Please choose a date at least 15 days after tommorrow" );

        var input_validations = function() {
        validator = $("#form").validate({ 
                // define validation rules
                rules: {
                    course_title: {
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
                    end_date: {
                        required: true,
                        date: "mm-dd-yyyy",
                    },
                    start_date: {
                        required: true,
                        date: "mm-dd-yyyy",
                        minDate: true,
                    },
                    language: {
                        required: true,
                    }

                },

                //display error alert on form submit  
                invalidHandler: function(event, validator) {
                    var alert = $('#form_msg');
                    alert.removeClass('kt-hidden').show();
                    KTUtil.scrollTop();
                },

                submitHandler: function(form) {
                    $("#submit_form").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true).html("Saving...");
                    var submit_form = $.ajax({
                        url: '/course_management/course_details/store',
                        type: 'POST',
                        data: {
                            course_title: $('#course_title').val(),
                            headline: $('#headline').val(),
                            about: $('#about').val(),
                            end_date: $('#end_date').val(),
                            start_date: $('#start_date').val(),
                            language:$("#language").val(),
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                toastr.success('Success!', response.message);
                                setTimeout(() => {
                                    window.location = "/course/management/details";
                                }, 1000);
                            } else {
                                $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                                toastr.error('Error!', response.message);
                            }
                        },
                        error: function(response) {
                            $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                            toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
                        }
                    });
                }
            });
        }

        return {
            init: function() {
                input_validations();
            }
        };
    }();
</script>
@endsection