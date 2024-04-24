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
                            Account Information
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
                                <label class="col-form-label col-lg-3 col-sm-12">Usename <text class="required">*</text></label>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="username" name="username" {{ Auth::user()->un_change > 0 ? "disabled" : "" }} placeholder="{{ Auth::user()->username }}" value="{{ Auth::user()->username }}">
                                    </div>
                                    <span class="form-text text-muted">Note* You can only change your username once</span>
                                </div>
                            </div>

                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Email <text class="required">*</text></label>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="email" name="email" placeholder="{{ Auth::user()->email }}" value="{{ Auth::user()->email }}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>

                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Contact No. <text class="required">*</text></label>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="contact" type="text" name="contact" placeholder="{{ Auth::user()->contact ?? '+63 999-9999-999'}}" value="{{ Auth::user()->contact ?? ''}}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>                            

                        </div>
                        <div class="kt-portlet__foot">
                            <div class=" ">
                                <div class="row">
                                    <div class="col-lg-9 ml-lg-auto">
                                        <button id="submit_form" class="btn btn-success">Update Account Information</button>
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
        var input_masks = function () {
           
            // phone number format
            $("#contact").inputmask("mask", {
                "mask": "+63 999-999-9999",
            });        
        }

        // Private functions
        var validator;

        var input_validations = function () {
            validator = $( "#user_edit_form" ).validate({
                // define validation rules
                rules: {
                    username: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    contact: {
                        required: true,
                    },
                },
                
                //display error alert on form submit  
                invalidHandler: function(event, validator) {             
                    var alert = $('#user_edit_form_msg');
                    $('kt-alert__text').html('&nbsp; &nbsp; Oh snap! Change a few things up and try submitting again.');
                    alert.removeClass('kt-hidden').show();
                    KTUtil.scrollTop();
                },

                submitHandler: function (form) {

                    $("#submit_form").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                    var submit_form = $.ajax({
                        url: '/account/action',
                        type: 'POST',
                        data: {
                            username: $('#username').val(),
                            contact: $('#contact').val(),
                            headline: $.trim($("#headline").val()),
                            about: $.trim($("#about").val()),
                            website: $('#website').val(),
                            facebook: $('#facebook').val(),
                            linkedin: $('#linkedin').val(),
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response){
                            if(response.status==200){
                                toastr.success('Success!', 'Account Information updated!');
                                setTimeout(() => {
                                    window.location="/profile/account";
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
                input_masks(); 
                input_validations(); 
            }
        };
    }();

    jQuery(document).ready(function() {
        FormDesign.init();

        $('#username').keyup(function(event){
            var value = event.target.value;
            $('#username').val(value.replace(/([~!@#$%^&*()+=`{}\[\]\|\\:;'<>,\/? ])+/g, ''));
        });
    });
</script>
@endsection

