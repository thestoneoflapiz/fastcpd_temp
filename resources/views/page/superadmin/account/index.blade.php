@extends('template.master_superadmin')
@section('title', 'Account')
@section('styles')
<style>
    .centered{margin:auto;}
    .minimize > i{font-size:2rem !important;}
    .hidden{display:none;}
    .required{color:#fd397a;}
</style>
@endsection
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-8">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <!-- begin:Feature action buttons -->
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Update Account</h3>
                        </div>
                    </div>
                    <!-- end:Feature action buttons -->
                    <div class="kt-portlet__body">
                        <form class="kt-form kt-form--label-left" id="form" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="kt-portlet__body">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">First Name <text class="required">*</text></label></label>
                                    <div class="col-lg-7 col-md-9 col-sm-12">
                                        <div class="kt-typeahead">
                                            <input class="form-control" type="text" id="first_name" name="first_name" placeholder="First Name" value="<?=Auth::user()->first_name ?? ""?>">
                                        </div>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>

                                <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Middle Name </label></label>
                                    <div class="col-lg-7 col-md-9 col-sm-12">
                                        <div class="kt-typeahead">
                                            <input class="form-control" type="text" id="middle_name" name="middle_name" placeholder="Middle Name" value="<?=Auth::user()->middle_name ?? ""?>">
                                        </div>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>

                                <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Last Name <text class="required">*</text></label></label>
                                    <div class="col-lg-7 col-md-9 col-sm-12">
                                        <div class="kt-typeahead">
                                            <input class="form-control" type="text" id="last_name" name="last_name" placeholder="Last Name" value="<?=Auth::user()->last_name ?? ""?>">
                                        </div>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>

                                <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Email <text class="required">*</text></label></label>
                                    <div class="col-lg-7 col-md-9 col-sm-12">
                                        <div class="kt-typeahead">
                                            <input class="form-control" type="email" id="email" name="email" placeholder="Email"  value="<?=Auth::user()->email ?? ""?>">
                                        </div>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet__foot">
                                <div class=" ">
                                    <div class="row">
                                        <div class="col-lg-9 ml-lg-auto">
                                            <button id="submit_form" class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>   
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="kt-portlet">
                    <!-- begin:Feature action buttons -->
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Change Password</h3>
                        </div>
                    </div>
                    <!-- end:Feature action buttons -->
                    <div class="kt-portlet__body">
                        <form class="kt-form kt-form--label-left" id="form_password" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="kt-portlet__body">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="kt-typeahead">
                                            <input class="form-control" type="password" id="old_password" name="old_password" placeholder="Old Password">
                                        </div>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>

                                <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="kt-typeahead">
                                            <input class="form-control" type="password" id="new_password" name="new_password" placeholder="New Password">
                                        </div>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>

                                <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="kt-typeahead">
                                            <input class="form-control" type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password">
                                        </div>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet__foot">
                                <div class=" ">
                                    <div class="row">
                                        <div class="col-lg-9 ml-lg-auto">
                                            <button id="submit_form_password" class="btn btn-success">Change Password</button>
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
</div>
@endsection
@section("scripts")
<script>
    jQuery(document).ready(function(){
        FormDesign.init();
        PasswordFormDesign.init();
    });

    var FormDesign = function () {
        var input_validations = function () {
            validator = $("#form").validate({
                rules: {
                    first_name: {
                        required: true,
                        maxlength: 180,
                    },
                    middle_name: {
                        maxlength: 180,
                    },
                    last_name: {
                        required: true,
                        maxlength: 180,
                    },
                    email: {
                        email: true,
                        required: true,
                    },
                },

                invalidHandler: function (event, validator) {
                    toastr.error("Please fill required fields!");
                },

                submitHandler: function (form) {
                    var submit = $("#submit_form");
                    submit.html(`Saving...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);
                    $.ajax({
                        url: '/superadmin/account/save',
                        type: 'POST',
                        data: {
                            first_name: $(`input[name="first_name"]`).val(),
                            middle_name: $(`input[name="middle_name"]`).val(),
                            last_name: $(`input[name="last_name"]`).val(),
                            email: $(`input[name="email"]`).val(),
                            _token: $(`input[name="_token"]`).val(),
                        },
                        success: function (response) {
                            toastr.success('Success!', response.message);
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        },
                        error: function (response) {
                            var body = response.responseJSON;
                            if(body && body.hasOwnProperty("message")){
                                toastr.error(body.message);
                                return;
                            }
                            toastr.error('Something went wrong! Please refresh your browser');
                        },
                        complete: function(){
                            submit.html(`Save`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                        }
                    });
                }
            });
        }

        return {
            init: function () {
                input_validations();
            }
        };
    }();

    var PasswordFormDesign = function () {
        var input_validations = function () {
            validator = $("#form_password").validate({
                rules: {
                    old_password: {
                        required: true,
                    },
                    new_password: {
                        required: true,
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "[name='new_password']",
                    },
                },

                invalidHandler: function (event, validator) {
                    toastr.error("Please fill required password fields!");
                },

                submitHandler: function (form) {
                    var submit = $("#submit_form_password");
                    submit.html(`Saving...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);
                    $.ajax({
                        url: '/superadmin/account/password',
                        type: 'POST',
                        data: {
                            old_password: $(`input[name="old_password"]`).val(),
                            new_password: $(`input[name="new_password"]`).val(),
                            _token: $(`input[name="_token"]`).val(),
                        },
                        success: function (response) {
                            toastr.success('Success!', response.message);
                            setTimeout(() => {
                                window.location="/signout"
                            }, 1000);
                        },
                        error: function (response) {
                            var body = response.responseJSON;
                            if(body && body.hasOwnProperty("message")){
                                toastr.error(body.message);
                                return;
                            }
                            toastr.error('Something went wrong! Please refresh your browser');
                        },
                        complete: function (){
                            submit.html(`Save`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                        }
                    });
                }
            });
        }

        return {
            init: function () {
                input_validations();
            }
        };
    }();

</script>
@endsection