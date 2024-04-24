@extends('template.master_course_creation')

@section('styles')
<style>
</style>
@endsection

@section('content')
<form class="kt-form kt-form--label-left" id="form">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Publish Course
                </h3> 
            </div>
        </div>

        @if(_current_course()->prc_status == "approved" && _current_course()->approved_at)
        <div class="kt-portlet__body">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__body">
                    <span><i class="fa fa-clipboard-list" style="color:#2A7DE9;"></i> By filling out the information on this form. You are indicating that you have made all the necessary accreditation needed for this program from the appropriate accreditation bodies. </span>
                </div>
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
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="bold">Published Date<text class="required">*</text>  <i class="fa fa-question-circle"  data-toggle="kt-popover" data-content="This is the date that the course will be published and viewable in public. Enrolees can enroll but will not be allowed to start the course."></i></label>
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker" name="published_date" placeholder="Select date" id="published_date" value="{{ _current_course()->published_at ? date('m/d/Y', strtotime(_current_course()->published_at)) : '' }}"/>
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
                                <label class="bold">Date Approved<text class="required">*</text></label>
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker" name="date_approved" value="{{ _current_course()->approved_at ? date('m/d/Y', strtotime(_current_course()->approved_at)) : '' }}" id="date_approved" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="form-text text-muted"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="bold">Start Date<text class="required">*</text></label>
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker" name="start_date" value="{{ date('m/d/Y', strtotime(_current_course()->session_start)) ?? '' }}" readonly placeholder="Select date" id="start_date" />
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
                                    <input type="text" class="form-control datepicker" disabled name="end_date" value="{{ date('m/d/Y', strtotime(_current_course()->session_end)) ?? '' }}" readonly placeholder="Select Start Date First" id="end_date" />
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
                    @if( _current_course()->profession_id)
                    <div class="form-group row">
                        <div class="col-12">
                            <label class="bold">PRC Accreditation Details<text class="required">*</text></label>
                            <span class="form-text text-muted">Please enter the course accreditation details for each profession applied.</span>
                        </div>
                    </div>
                    @foreach($professions as $profession)
                    <div class="card" id="profession-group-{{ $profession->id }}">
                        <div class="card-header" id="heading{{ $profession->id }}">
                            <div class="card-title" data-toggle="collapse" data-target="#collapse{{ $profession->id }}" aria-expanded="true" aria-controls="collapse{{ $profession->id }}">
                               <b>{{ $profession->title }}</b> <text class="kt-font-danger kt-font-bolder">*</text>
                            </div>
                        </div>
                        <div id="collapse{{ $profession->id }}" class="collapse show" aria-labelledby="heading{{ $profession->id }}">
                            <div class="card-body">
                                <div class="row">	
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="bold">Credit Units<text class="required">*</text></label>
                                            <input class="form-control" type="text" name="[units][{{ $profession->id }}]" value="{{ $profession->units }}"> 
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="form-group">
                                            <label class="bold">Accreditation No.<text class="required">*</text></label>
                                            <input class="form-control" type="text" name="[program_no][{{ $profession->id }}]" value="{{ $profession->program_no }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="row justify-content-end">
                <p class="bold">Once submitted, no changes shall be made to the program to comply with the guidlines of online accreditation.</p>
            </div>
            <div class="row" style="float:right">
                <div class="col-lg-12 ml-lg-xl-auto">
                    <button id="submit_form" class="btn btn-success">Submit</button>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <button type="reset" class="btn btn-secondary">Clear</button>
                </div>
            </div>
        </div>
        @else
        <div class="kt-portlet__body">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__body">
                    <h5><i class="fa fa-clipboard-list" style="color:#2A7DE9;"></i> &nbsp;This form will be available once the assigned accreditor has approved your PRC Accreditation.</h5>
                </div>
            </div>
        </div>
        @endif
    </div>
</form>
@endsection

@section('scripts')
<script>
    Inputmask.extendAliases({
        pesos: {
            prefix: "₱ ",
            groupSeparator: ".",
            alias: "numeric",
            placeholder: "0",
            autoGroup: !0,
            digits: 2,
            digitsOptional: !1,
            clearMaskOnLostFocus: !1,
            autoUnmask: true,
            removeMaskOnSubmit: true,
        }
    });

    jQuery(document).ready(function() {
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
     
        $(".datepicker").datepicker({
            autoclose: true,
            gotoCurrent: false,
            todayHighlight: true, 
            orientation: "bottom left",
            templates: arrows
        });

        $("#end_date").datepicker();
        $("#start_date").on("change", function() {
            $("#start_date").datepicker("option", "showAnim", $(this).val());
            var d = $("#start_date").datepicker("getDate");

            d.setFullYear(d.getFullYear() + 1);
            $("#end_date").datepicker("setDate", d);
        });

        FormDesign.init();
    });

    var $professions = <?=json_encode($professions) ?? []?>;
    var FormDesign = function() {

        var input_validations = function() {
            validator = $("#form").validate({
                rules: {
                    published_date: {
                        required: true,
                    },
                    date_approved: {
                        required: true,
                    },
                    start_date: {
                        required: true,
                    },
                    end_date: {
                        required: true,
                    },

                },

                invalidHandler: function(event, validator) {
                    var alert = $('#form_msg');
                    alert.removeClass('kt-hidden').show();
                    KTUtil.scrollTop();
                },

                submitHandler: function(form) {
                    var $accreditation = [];

                    var submit_button = $("#submit_form");
                    submit_button.html(`Saving...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);

                    $professions.forEach(pro => {
                        var $form_group = $(`#profession-group-${pro.id}`);
                        var units = $form_group.find(`input[name="[units][${pro.id}]"]`).val();
                        var program_no = $form_group.find(`input[name="[program_no][${pro.id}]"]`).val();

                        $accreditation.push({
                            id: pro.id,
                            units: units,
                            program_no: program_no,
                        });
                    });
                    
                    $.ajax({
                        url: '/course_management/price_and_publish/store',
                        type: 'POST',
                        data: {
                            published_date: $("#published_date").val(),
                            date_approved: $("#date_approved").val(),
                            start_date: $("#start_date").val(),
                            end_date: $("#end_date").val(),
                            accreditation: $accreditation,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            submit_button.html(`Submit`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                            if (response.status == 200) {
                                toastr.success('Success!', response.message);
                            } else {  toastr.error('Error!', response.message); }
                        },
                        error: function(response) {
                            submit_button.html(`Submit`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                            toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
                        }
                    });
                }
            });

            $(`input[name*="units"]`).each(function () {
                $(this).inputmask('decimal', {
                    rightAlignNumerics: false
                }); 

                $(this).rules("add", {
                    required: true,
                    max: 100,
                    min: 1,
                });
            });
            
            $(`input[name*="program_no"]`).each(function () {
                $(this).rules("add", {
                    required: true,
                    maxlength: 150,
                    minlength: 5,
                });
            });
        }

        return {
            // public functions
            init: function() {

                input_validations();
            }
        };
    }();
</script>
@endsection