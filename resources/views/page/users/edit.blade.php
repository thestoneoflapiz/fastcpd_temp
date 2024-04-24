@extends('template.master')
@section('title', 'Users Management')

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
                            Edit User <?="— ".$name ?? ""?>
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="btn btn-secondary btn-icon" onclick="goBack()" data-toggle="kt-tooltip" data-placement="top" title="Go Back to Users"><i class="fa fa-arrow-left"></i></button>
                            </li>
                        </ul>
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
                                    <div class="kt-alert__text">&nbsp; Sorry! You have to complete the form requirements first!</div>
                                    <div class="kt-alert__close">
                                        <button type="button" class="close" data-close="alert" aria-label="Close">
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Name <text class="required">*</text></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="first" type="text" name="first" placeholder="{{ $user->first_name ?? 'Señora Trinidad'}}" value="{{$user->first_name ?? ''}}">
                                    </div>
                                    <span class="form-text text-muted">First name</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12"></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="last" type="text" name="last" placeholder="{{$user->last_name ?? 'Dela Cruz'}}" value="{{$user->last_name ?? ''}}">
                                    </div>
                                    <span class="form-text text-muted">Last name</span>
                                </div>
                            </div>
                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Position <text class="required">*</text></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="position" type="text" name="position" placeholder="{{ $user->position ?? 'Barangay Secretary'}}" value="{{$user->position ?? ''}}">
                                    </div>
                                    <span class="form-text text-muted">Please enter your title</span>
                                </div>
                            </div>
                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Permission Role <text class="required">*</text></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <select class="form-control kt-select2" id="role" name="role">
                                        <option selected disabled></option>
                                        <optgroup label="Select a Permission Role">
                                            @foreach ($roles as $role)
                                            <option value="{{$role->id}}" <?=$user->role && $user->role==$role->id ? 'selected' : ''?>><?=$role->role;?></option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    <span class="form-text text-muted">Select an option</span>
                                </div>
                            </div>
                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Email Address <text class="required">*</text></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="email" type="text" name="email" placeholder="{{ $user->email ?? 'senyora.delacruz@example.com'}}" value="{{$user->email ?? ''}}">
                                    </div>
                                    <span class="form-text text-muted">Please enter your unique email-address</span>
                                </div>
                            </div>
                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Contact No. <text class="required">*</text></label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="contact" type="text" name="contact" placeholder="{{ $user->contact ?? '+63 999-9999-999'}}" value="{{$user->contact ?? ''}}">
                                    </div>
                                    <span class="form-text text-muted">Please enter your contact number</span>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class=" ">
                                <div class="row">
                                    <div class="col-lg-9 ml-lg-auto">
                                        <button id="submit_form" class="btn btn-success">Edit User</button>
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
        window.location="/users";
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
            validator = $( "#user_edit_form" ).validate({
                // define validation rules
                rules: {
                    first: {
                        required: true,
                    },
                    last: {
                        required: true,
                    },
                    position: {
                        required: true,
                    },
                    role: {
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
                        url: '/users/edit/action',
                        type: 'POST',
                        data: {
                            user: "{{ $id }}",
                            first: $('#first').val(),
                            last: $('#last').val(),
                            position: $('#position').val(),
                            role: $('#role').val(),
                            email: $('#email').val(),
                            contact: $('#contact').val(),
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response){
                            if(response==200){
                                window.location = "/users";
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