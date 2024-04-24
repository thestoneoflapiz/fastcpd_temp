<?php $assessment = json_decode(_current_webinar()->assessment); ?>
@extends('template.webinar.master_creation')

@section('styles')
<style>
</style>
@endsection

@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Grading & Assessment
            </h3>
        </div>
    </div>
    <form class="kt-form kt-form--label-left" id="form">
        <div class="kt-portlet__body">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__body">
                    <div class="form-group">
                        <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success kt-switch--sm">
                            <label style="text-align:center;">
                                <input type="checkbox" id="assessment" name="assessment"> &nbsp; &nbsp;Do you want to implement a pass or fail assessment at the end of the program?
                                <span></span>
                            </label>
                        </span>
                        <span class="form-text text-muted"></span>
                    </div>

                    <div class="form-group " id="percentage_div" style="padding-left:30px;display:none;">
                        <label class="bold">Please input the percentage amount a student should pass to earn a passing mark for the webinar and receive the certificate at the end of the webinar.</label>
                        <input class="form-control col-lg-4 col-md-4 col-sm-6"  id="percentage" placeholder="75" value="{{ (_current_webinar()->pass_percentage * 100) ?? '' }}" type="text" name="percentage">
                        <span class="form-text text-muted">Please enter the percent number only.</span>
                    </div>

                    <div class="form-group" id="retry_div" style="display:none;">
                        <div class="kt-checkbox-list">
                            <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                                <input type="checkbox" checked name="retry" id="retry"> Allow users to retry the quiz if their score is below the percentage?
                                <span></span>
                            </label>
                        </div>
                        <span class="form-text text-muted"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
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
    </form>
</div>
@endsection

@section('scripts')
<script>
      Inputmask.extendAliases({
        pesos: {
            suffix: "%",
            groupSeparator: "",
            alias: "numeric",
            placeholder: "0",
            autoGroup: !0,
            digits: 0,
            digitsOptional: !1,
            clearMaskOnLostFocus: !1,
            autoUnmask: true,
            removeMaskOnSubmit: true,
        }
    });

    jQuery(document).ready(function() {
        $('#assessment').on("change", function() {
            if ($(this).prop("checked")) {
                $("#percentage_div").slideDown();
                $("#retry_div").slideDown();
            } else {
                $("#percentage_div").slideUp();
                $("#retry_div").slideUp();
                $("#retry").prop("checked",false);
                $("#percentage").val(null);
            }
        });

        if($("#percentage").val() != 0 && $("#percentage").val() != null ){
            $('#assessment').prop("checked", true);
            $("#percentage_div").slideDown();
            $("#retry_div").slideDown();
            var is_allowed = <?=_current_webinar()->allow_retry ?? 'false' ?>;
            if( is_allowed == true){
                $("#retry").prop("checked", true);
            }
        }

        $("#percentage").inputmask({
            alias: "pesos",
        });

        FormDesign.init();
    });

    var FormDesign = function() {
        jQuery.validator.addMethod( "greaterThan", function( value, element, param ) {
            return (value > jQuery(param).val());
        }, "Please enter a valid percentage ( less than 100% )" );
        jQuery.validator.addMethod( "lesserThan", function( value, element, param ) {
            return (value < jQuery(param).val());
        }, "Please enter a valid percentage ( less than 100% )" );


        var input_validations = function() {
            validator = $("#form").validate({
                // define validation rules
                rules: {
                   percentage: {
                       max: 100,
                       min:0,
                       number: true,
                       required:{
                            depends: function(element){
                                return $("#assessment").is(":checked");
                            }
                       }
                   }

                },

                //display error alert on form submit  
                invalidHandler: function(event, validator) {
                    var alert = $('#form_msg');
                    alert.removeClass('kt-hidden').show();
                    KTUtil.scrollTop();
                },

                submitHandler: function(form) {
                    var submit_button = $("#submit_form");
                    submit_button.html(`Saving...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);

                    if($("#assessment").is(":checked")){ 
                        var assessment = true; 
                        if($("#retry").is(":checked")){ var retry = true; }else{ var retry = false; }
                    }else{ 
                        var assessment = false; 
                        retry = true;
                    }

                    $.ajax({
                        url: '/webinar/management/grading/store',
                        type: 'POST',
                        data: {
                             assessment: assessment,
                             retry: retry,
                             percentage: $("#percentage").val(),
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            submit_button.html(`Submit`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                            if (response.status == 200) {
                                toastr.success('Success!', response.message + '. Thank you!');
                                // setTimeout(() => {
                                //     window.location = "/webinar/management/grading";
                                // }, 1000);
                            } else {  toastr.error('Error!', response.message); }
                        },
                        error: function(response) {
                            submit_button.html(`Submit`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                            toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
                        }
                    });
                }
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