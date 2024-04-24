@extends('template.master')
@section('title', 'Help & System Settings')


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
                            Edit Company Information
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="btn btn-secondary btn-icon" onclick="goBack()" data-toggle="kt-tooltip" data-placement="top" title="Go Back to Help"><i class="fa fa-arrow-left"></i></button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--label-left" id="edit_form">
                        <div class="kt-portlet__body">
                            <div class="kt-form__content">
                                <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="edit_form_msg">
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
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">System <text class="required">*</text></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="system" type="text" name="system" placeholder="Enrich Apps Boilerplate System">
                                    </div>
                                    <span class="form-text text-muted">Please enter the project's name</span>
                                </div>
                            </div>
                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Name <text class="required">*</text></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="name" type="text" name="name" placeholder="Enrich Apps">
                                    </div>
                                    <span class="form-text text-muted">Please enter the company's name</span>
                                </div>
                            </div>
                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Address <text class="required">*</text></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="address" type="text" name="address" placeholder="30 Cabatuan St., corner Dome, Quezon City, MM, PH ">
                                    </div>
                                    <span class="form-text text-muted">Please enter company's address</span>
                                </div>
                            </div>
                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Email Address <text class="required">*</text></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="email" type="text" name="email" placeholder="noreply.mycompany@example.com">
                                    </div>
                                    <span class="form-text text-muted">Please enter a unique email-address</span>
                                </div>
                            </div>
                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Contact No. <text class="required">*</text></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="contact" type="text" name="contact" placeholder="+63 999-9999-999">
                                    </div>
                                    <span class="form-text text-muted">Please enter a contact number</span>
                                </div>
                            </div>
                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Google Play Store</label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="google" type="text" name="google" placeholder="https://play.google.com/store/">
                                    </div>
                                    <span class="form-text text-muted">Please provide link to mobile application</span>
                                </div>
                            </div>
                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Apple Store</label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="apple" type="text" name="apple" placeholder="https://www.apple.com/ios/app-store/">
                                    </div>
                                    <span class="form-text text-muted">Please provide link to mobile application</span>
                                </div>
                            </div>
                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Raw APK</label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="raw" type="text" name="raw" placeholder="https://">
                                    </div>
                                    <span class="form-text text-muted">Please provide link to mobile application</span>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class=" ">
                                <div class="row">
                                    <div class="col-lg-9 ml-lg-auto">
                                        <button id="submit_form" class="btn btn-success">Update</button>
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
    function goBack(){
        window.location="/help";
    }

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

        var input_widgets = function() {
            $('#role').on('role:change', function(){
                validator.element($(this)); // validate element
            });
        }

        var input_validations = function () {
            validator = $( "#edit_form" ).validate({
                // define validation rules
                rules: {
                    system: {
                        required: true,
                    },
                    name: {
                        required: true,
                    },
                    address: {
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
                    var alert = $('#edit_form_msg');
                    $('kt-alert__text').html('&nbsp; &nbsp; Oh snap! Change a few things up and try submitting again.');
                    alert.removeClass('kt-hidden').show();
                    KTUtil.scrollTop();
                },

                submitHandler: function (form) {

                    $("#submit_form").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                    var submit_form = $.ajax({
                        url: '/help/action/edit',
                        type: 'POST',
                        data: {
                            system: $('#system').val(),
                            name: $('#name').val(),
                            address: $('#address').val(),
                            email: $('#email').val(),
                            contact: $('#contact').val(),
                            google: $('#google').val(),
                            apple: $('#apple').val(),
                            raw: $('#raw').val(),
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response){
                            if(response==200){
                                window.location.href="/help";
                            }else{
                                $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                toastr.error('Error!', response);
                            }
                        },
                        error: function(){
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
                input_widgets(); 
                input_validations(); 
            }
        };
    }();

    jQuery(document).ready(function() {
        FormDesign.init();
    });
</script>
@endsection