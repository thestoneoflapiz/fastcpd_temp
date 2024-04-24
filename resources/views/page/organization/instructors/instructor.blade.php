@extends('template.master_provider')
@section('title', _current_provider()->name.' — Instructors')
@section('styles')
<style>
    .image{width:130px;height:130px;border:4px solid #fff;border-radius:3px;}
    .image_container{margin:auto;width:130px;height:130px;border-radius:5%;-webkit-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);-moz-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);}
    .strong{font-weight:600;}
    img.select-img{width:40px;height:40px;border-radius:3px;}
    #sub_form{display:none;}
    .status.plain{font-weight:600;}
    .status.green{font-weight:600;color:#20c997;}
    .status.orange{font-weight:600;color:#fd7e14;}
</style>
@endsection
@section('content')
<div id="main_form" class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label"></div>
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="btn btn-info" onclick="reverseShow('#sub', '#main')" data-toggle="kt-tooltip" data-placement="top" title="Invite User to Sign Up at FastCPD">User not in FastCPD?</button>
                                &nbsp; <button type="button" class="btn btn-secondary btn-icon" onclick="window.location='/provider/instructors'" data-toggle="kt-tooltip" data-placement="top" title="Go Back to Instructors"><i class="fa fa-arrow-left"></i></button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Invite FastCPD User as Instructor
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form" id="user_invite_form">
                        <div class="kt-portlet__body">
                            <div class="kt-form__content">
                                <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="user_invite_form_alert">
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
                            <div class="alert alert-secondary" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                                <div class="alert-text">
                                They will receive an invitation email from FastCPD once you've invited them. Please remind them to check their spam or promotion inbox. Users who are not registered as Instructors in FastCPD are unable to accept the invitation.
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-12 col-md-12 col-12">
                                    <select class="form-control kt-select2" id="searches" name="searches">
                                    </select>
                                    <span class="form-text text-muted">Note* Input the user's name, username, or email address to filter selection.</span>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class=" ">
                                <div class="row">
                                    <div class="col-lg-9 ml-lg-auto">
                                        <button id="submit_user_invitation" class="btn btn-success">Invite</button>
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

<div id="sub_form" class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label"></div>
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="btn btn-info" onclick="reverseShow('#main', '#sub')" data-toggle="kt-tooltip" data-placement="top" title="Invite User to Sign Up at FastCPD">User in FastCPD?</button>
                                &nbsp; <button type="button" class="btn btn-secondary btn-icon" onclick="window.location='/provider/instructors'" data-toggle="kt-tooltip" data-placement="top" title="Go Back to Instructors"><i class="fa fa-arrow-left"></i></button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Invite User to SignUp at FastCPD
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form" id="nonuser_invite_form">
                        <div class="kt-portlet__body">
                            <div class="kt-form__content">
                                <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="nonuser_invite_form_alert">
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
                            <div class="alert alert-secondary" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                                <div class="alert-text">
                                They will receive an invitation email from FastCPD once you've invited them. Please remind them to check their spam or promotion inbox.
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-12 col-md-12 col-12">
                                    <select class="form-control kt-select2" id="taggs" name="taggs">
                                    </select>
                                    <span class="form-text text-muted">Note* Input the user's <b>valid</b> email address.</span>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class=" ">
                                <div class="row">
                                    <div class="col-lg-9 ml-lg-auto">
                                        <button id="submit_nonuser_invitation" class="btn btn-success">Invite</button>
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

    function reverseShow(show, hide){
        $(hide+`_form`).slideUp(500);
        $(show+`_form`).slideDown(500);
    }

    /**
     * Select-Search User : Ajax
     * 
     */
    $('#searches').select2({
        width: '100%',
        placeholder: 'Search for FastCPD USER',
        multiple: true,
        ajax: {
            url: '/provider/instructor/api/search',
            dataType: 'json',
            type: "GET",
            delay: 150,
            data: function (params) {
                return {
                    search: params.term,
                };
            },
            processResults: function (data) {
                var res = data.map(function (item) {
                    if(item.instructor_details && jQuery.inArray(item.instructor_details.status, ["active", "inactive", "pending"]) >= 0){
                        return {idd: item.id, status:item.instructor, istatus:item.instructor_details.status, text: item.name, image: item.image ? item.image : '{{ asset("img/sample/noimage.png") }}'};
                    }

                    return {id: item.id, idd: item.id, status:item.instructor, istatus:item.instructor_details ? item.instructor_details.status : 'none', text: item.name, image: item.image ? item.image : '{{ asset("img/sample/noimage.png") }}'};
                });

                return {
                    results: res
                };
            }
        },
        templateResult: function (option) {
            if (!option.idd) { return option.text; }
            
            var status_span = `${option.istatus == "active" || option.istatus == "inactive" ? ("<span class='status green'>Accepted</span>") : (option.istatus == "pending" ? ("<span class='status orange'>Email Sent</span>") : (option.status == "active" ? "<span class='status plain'>Instructor</span>" : "<span class='status plain'>Not instructor</span>") )}`;
            var div = $('<div />').html(` &nbsp; <span class="strong">${option.text}</span> | ${status_span}`).prepend('<img class="select-img" src="'+option.image+'" />');
			return div;
		},
    });

    /**
     * Select-Tag Email Addresses with Email Validation
    */
    $('#taggs').select2({
        width: '100%',
        placeholder: 'Enter a valid Email Address',
        multiple: true,
        tags: true,
        createTag: function(term, data) {
            var value = term.term;
            if(validateEmail(value)) {
                return {
                id: value,
                text: value
                };
            }
            return null;            
        }
    });

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    /**
     * Form Validation
     * 
     */
    var FormDesign = function () {

        var users = function () {
            validator = $( "#user_invite_form" ).validate({
                // define validation rules
                rules: {
                    searches: {
                        required: true,
                    },
                },
                messages: {
                    searches: {
                        required: 'Please choose at least one(1) user.',
                    },
                },
                
                //display error alert on form submit  
                invalidHandler: function(event, validator) {             
                    var alert = $('#user_invite_form_alert');
                    alert.removeClass('kt-hidden').show();
                    KTUtil.scrollTop();
                },
                
                submitHandler: function (form) {

                    $("#submit_user_invitation").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                    var submit_user_invitation = $.ajax({
                        method: 'POST',
                        url: '/provider/instructor/invite/action',
                        data: {
                            users: $('#searches').val(),
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(){
                            toastr.success('Success!', 'Selected users has been invited successfully.');
                            
                            setTimeout(() => {
                                location.reload();
                            }, 3500);
                        },
                        error: function(response){
                            $("#submit_user_invitation").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');

                            var body = response.responseJSON;
                            if(body.hasOwnProperty("message")){

                                toastr.error('Error!', body.message);
                                return;
                            }

                           toastr.error('Error!', 'Something went wrong! Unable to send invite. Please try again later.');
                        }
                    });
                }
            });       
        }

        var nonusers = function () {
            validator = $( "#nonuser_invite_form" ).validate({
                // define validation rules
                rules: {
                    taggs: {
                        required: true,
                    },
                },
                messages: {
                    taggs: {
                        required: 'Please enter atleast one(1) email address.',
                    },
                },
                
                //display error alert on form submit  
                invalidHandler: function(event, validator) {             
                    var alert = $('#nonuser_invite_form_alert');
                    alert.removeClass('kt-hidden').show();
                    KTUtil.scrollTop();
                },
                
                submitHandler: function (form) {

                    $("#submit_nonuser_invitation").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                    var submit_nonuser_invitation = $.ajax({
                        method: 'POST',
                        url: '/provider/nonuser/invite/action',
                        data: {
                            emails: $('#taggs').val(),
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(){
                            toastr.success('Success!', 'Listed email addresses has been invited successfully.');
                            
                            setTimeout(() => {
                                location.reload();
                            }, 3500);
                        },
                        error: function(response){
                            $("#submit_nonuser_invitation").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');

                            var body = response.responseJSON;
                            if(body.hasOwnProperty("message")){

                                toastr.error('Error!', body.message);
                                return;
                            }

                           toastr.error('Error!', 'Something went wrong! Unable to send invite. Please try again later.');
                        }
                    });
                }
            });       
        }

        return {
            // public functions
            init: function() {
                users(); 
                nonusers(); 
            }
        };
    }();

    jQuery(document).ready(function() {
        FormDesign.init();
    });
</script>
@endsection 
