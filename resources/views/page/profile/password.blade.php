@extends('template.master_noleft')
@section('styles')
<style>
    .required{color:#fd397a;}
</style>
@endsection

@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Change Password
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--label-left" id="user_edit_form">
                        <div class="kt-portlet__body">
                            <div class="kt-form__content">
                                <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="user_edit_form_msg">
                                    <div class="kt-alert__icon">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="kt-alert__text">&nbsp;Sorry! You have to complete the form requirements first!</div>
                                    <div class="kt-alert__close">
                                        <button type="button" class="close" data-close="alert" aria-label="Close">
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Old Password <text class="required">*</text></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="password" id="old" name="old">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>

                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">New Password <text class="required">*</text></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="password" id="new" name="new">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>

                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Confirm New Password <text class="required">*</text></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="password" id="confirm" name="confirm">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>

                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                        </div>
                        <div class="kt-portlet__foot">
                            <div class=" ">
                                <div class="row">
                                    <div class="col-lg-9 ml-lg-auto">
                                        <button id="submit_form" class="btn btn-success">Update Password</button>
                                        <button type="reset" class="btn btn-secondary">Clear</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts') 
<script>
    var FormDesign = function () {
        // Private functions
        var validator;

        var input_validations = function () {
            validator = $( "#user_edit_form" ).validate({
                // define validation rules
                rules: {
                    old: {
                        required: true,
                        minlength: 6,
                        maxlength: 250,
                    },
                    new: {
                        required: true,
                        minlength: 6,
                        maxlength: 250,
                    },
                    confirm: {
                        required: true,
                        equalTo: "#new",
                        minlength: 6,
                        maxlength: 250,
                    },
                },
                messages :{
                    confirm: {

                        equalTo: "New & Confirm password didn't match!"
                    }
                },
                
                //display error alert on form submit  
                invalidHandler: function(event, validator) {             
                    var alert = $('#user_edit_form_msg');
                    alert.removeClass('kt-hidden').show();
                    KTUtil.scrollTop();
                },

                submitHandler: function (form) {
                    $("#submit_form").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                    var submit_form = $.ajax({
                        url: '/password/action',
                        type: 'POST',
                        data: {
                            old: $('#old').val(),
                            new: $('#new').val(),
                            confirm: $('#confirm').val(),
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response){
                            if(response.status==200){
                                toastr.success('Success!', 'Password has been changed!');
                                setTimeout(() => {
                                    window.location="/profile/password";
                                }, 1000);
                            }else{
                                $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                toastr.error('Error!', response.message);
                            }
                        },
                        error: function(response){
                            $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
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

    jQuery(document).ready(function() {
        FormDesign.init();
    });
</script>
@endsection

