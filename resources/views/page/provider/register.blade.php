@extends('template.master_form')
@section('styles')
    <style>
        .required{color:red;}
        .box{padding:10px;border:1px solid #e2e5ec;border-radius:5px; min-height:120px}
    </style>
<link href="{{asset('css/cropper.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="kt-portlet kt-portlet--height-fluid">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Become a Provider
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                <li class="nav-item">
                    <button type="button" class="btn btn-secondary btn-icon" onclick="window.location='/'" data-toggle="kt-tooltip" data-placement="top" title="Go Back"><i class="fa fa-arrow-left"></i></button>
                </li>
            </ul>
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
                    <div class="col-xl-6 col-md-6">
                        <label>Provider Logo <text class="required">*</text></label>
                        <div class="form-group row" style="justify-content:center;">
                            <div class="kt-avatar kt-avatar--outline" id="provider_logo">
                                <div class="kt-avatar__holder" style="background-image: url({{asset('img/sample/noimage.png')}})"></div>
                                <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change avatar">
                                    <i class="fa fa-pen"></i>
                                    <input type="file" name="logo" accept="image/*">
                                </label>
                                <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
                                    <i class="fa fa-times"></i>
                                </span> 
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label>Name <text class="required">*</text></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder="The Fast CPD Inc.">
                            <span class="form-text text-muted">Please enter the provider's name</span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label>Provider URL <text class="required">*</text></label>
                            <input class="form-control" id="seo_url" type="text" name="seo_url" placeholder="my-provider-since1998">
                            <span class="form-text text-muted">Please enter a <b>user-friendly</b> url to help you <b>boost</b> your provider's page.</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        <div class="form-group">
                            <label>Target Profession <text class="required">*</text></label>
                            <select class="form-control kt-select2" id="profession" name="profession" multiple="multiple">
                                @foreach(_professions() as $pro)
                                <option value="{{ $pro['id'] }}">{{ $pro['profession'] }}</option>
                                @endforeach
                            </select>
                            <span class="form-text text-muted">Please select your provider's target profession</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label>Accreditation No. <text class="required">*</text></label>
                            <input class="form-control" id="accreditation" type="text" name="accreditation" placeholder="YYYY-000">
                            <span class="form-text text-muted">Please enter your unique accreditation number</span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label>Accreditation Expiration Date <text class="required">*</text></label>
                            <input type="text" class="form-control" name="accreditation_expiration_date" id="accreditation_expiration_date" readonly placeholder="Select Expiration Date" />
                            <span class="form-text text-muted">Please select the expiration of accreditation</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label>Email Address <text class="required">*</text></label>
                            <input class="form-control" id="email" type="text" name="email" placeholder="my-provider@example.com">
                            <span class="form-text text-muted">Please enter your unique email-address</span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label>Contact No. <text class="required">*</text></label>
                            <input class="form-control" id="contact" type="text" name="contact" placeholder="+63 999-9999-999">
                            <span class="form-text text-muted">Please enter your unique contact number</span>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xl-4 col-md-4">
                        <label>Website</label>
                        <input class="form-control" id="website" type="text" name="website" placeholder="https://my-provider.com">
                        <span class="form-text text-muted">Please enter your Provider's website link</span>
                    </div>
                    <div class="col-xl-4 col-md-4">
                        <label>Facebook</label>
                        <input class="form-control" id="facebook" type="text" name="facebook" placeholder="https://facebook.com/">
                        <span class="form-text text-muted">Please enter your Facebook Page/Profile link</span>
                    </div>
                    <div class="col-xl-4 col-md-4">
                        <label>LinkedIn</label>
                        <input class="form-control" id="linkedin" type="text" name="linkedin" placeholder="https://linkedin.com/">
                        <span class="form-text text-muted">Please enter your LinkedIn link</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-md-6">
                        <div class="form-group">
                            <label>Headline <text class="required">*</text></label>
                            <input class="form-control" id="headline" type="text" name="headline">
                            <span class="form-text text-muted">Please enter a headline for your provider's page</span>
                        </div>
                    </div>
                    <div class="col-xl-8 col-md-6" id="about_group">
                        <div class="form-group">
                            <label>About <text class="required">*</text></label>
                            <div id="textarea_about" style="display:none;">
                                <textarea class="form-control" id="about" name="about"></textarea>
                            </div>
                            <div id="div_about" class="box">
                            
                            </div>
                            <span class="form-text text-muted">Please provide an about for your provider page</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="row" style="float:right">
                    <div class="col-lg-12 ml-lg-xl-auto">
                        <button id="submit_form" class="btn btn-outline-success">Register Provider Details</button>
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
<div class="modal fade" id="cropper_modal" tabindex="-1" role="dialog" aria-labelledby="profile_image_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <img id="profile_image" height="400">
                    </div>
                    <div class="col-md-4">
                        <div class="preview"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" id="crop_submit">CROP</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script src="{{asset('js/cropper.js')}}" type="text/javascript"></script>
<script>
    var uploaded_image = null;

    var FormDesign = function () {
        // Private functions
        var input_masks = function () {
           
            // phone number format
            $("#contact").inputmask("mask", {
                "mask": "+63 999-999-9999",
            }); 

            $("#website, #facebook, #linkedin").inputmask({
                mask: "https://*{1,100}[.*{1,100}][.*{1,100}][.*{1,100}]",
                greedy: false,
                onBeforePaste: function (pastedValue, opts) {
                    pastedValue = pastedValue.toLowerCase();
                    return pastedValue.replace("mailto:", "");
                },
                definitions: {
                    '*': {
                        validator: "[0-9A-Za-z#%&'*+/=?_{}\-]",
                        cardinality: 1,
                        casing: "lower"
                    }
                }
            }); 
        }

        // Private functions
        var validator;

        $.validator.addMethod( "minDate", function( dateInput, element ) {
            dateGiven = new Date(dateInput);
            dateMinimum = new Date(Date.now() + 12096e5);

            return this.optional( element ) || dateGiven > dateMinimum;
        }, "Please choose a date at least 2 weeks from now" );


        var input_validations = function () {
            validator = $( "#form" ).validate({
                // define validation rules
                rules: {
                    seo_url: {
                        required: true,
                        minlength: 3,
                        maxlength: 50,
                    },
                    name: {
                        required: true,
                    },
                    headline: {
                        required: true,
                    },
                    about: {
                        required: true,
                    },
                    profession: {
                        required: true,
                    },
                    accreditation: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    contact: {
                        required: true,
                    },
                    accreditation_expiration_date: {
                        required: true,
                        date: "mm-dd-yyyy",
                        minDate: true,
                    },
                },
                
                //display error alert on form submit  
                invalidHandler: function(event, validator) {             
                    var alert = $('#form_msg');
                    alert.removeClass('kt-hidden').show();
                    KTUtil.scrollTop();
                },

                submitHandler: function (form) {

                    $("#submit_form").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                    if(uploaded_image){
                        var submit_form = $.ajax({
                            url: '/provider/register/action',
                            type: 'POST',
                             data: {
                                logo: uploaded_image,
                                seo_url: $('#seo_url').val(),
                                profession: $('#profession').val(),
                                name: $('#name').val(),
                                accreditation: $('#accreditation').val(),
                                accreditation_expiration_date: $('#accreditation_expiration_date').val(),
                                email: $('#email').val(),
                                contact: $('#contact').val(),
                                headline: $("#headline").val(),
                                about: $.trim($("#about").val()),
                                website: $('#website').val(),
                                facebook: $('#facebook').val(),
                                linkedin: $('#linkedin').val(),
                                _token: "{{ csrf_token() }}",
                            },
                            success: function(response){
                                if(response.status==200){
                                    toastr.success('Success!', 'Registration for Provider is sent! Please wait for the approval for posting publicly. Thank you!');
                                    setTimeout(() => {
                                        window.location="/";
                                    }, 1000);
                                }else{
                                    $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                    toastr.error('Error!', response.message);
                                }
                            },
                            error: function(response){
                                const body = response.responseJSON;
                                if(body.hasOwnProperty("errors")){
                                    const errors = body.errors;
                                    var alert = $('#form_msg');
                                    var html = "&nbsp; &nbsp; Some of the details you've given are already taken by other Providers. <br/><br/>";
                                    
                                    if(errors.hasOwnProperty("seo_url")){
                                        html = html.concat("* <b>Provider URL</b> has already been taken <br/>");
                                    }

                                    if(errors.hasOwnProperty("name")){
                                        html = html.concat("* <b>Provider Name</b> has already been taken <br/>");
                                    }

                                    if(errors.hasOwnProperty("email")){
                                        html = html.concat("* <b>Provider Email</b> has already been taken <br/>");
                                    }

                                    if(errors.hasOwnProperty("contact")){
                                        html = html.concat("* <b>Provider Contact</b> has already been taken <br/>");
                                    }

                                    if(errors.hasOwnProperty("accreditation")){
                                        html = html.concat("* <b>Provider Accreditation Number</b> has already been taken <br/>");
                                    }

                                    $('.kt-alert__text').html(html);
                                    alert.removeClass('kt-hidden').show();
                                    KTUtil.scrollTop();
                                    $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                    toastr.warning('Warning!', 'Some fields are already taken!');

                                }else{
                                    $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                    toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
                                }
                            }
                        });
                    }else{
                        if(uploaded_image==null){
                            toastr.error('Required!', 'Sorry! Logo is required!');
                        }

                        var alert = $('#form_msg');
                        alert.removeClass('kt-hidden').show();
                        KTUtil.scrollTop();
                    }
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

    var Profile = function () {
	// Base elements
        var profile;
        var offcanvas;

        // Private functions
        var initAside = function () {
            offcanvas = new KTOffcanvas('kt_user_profile_aside', {
                overlay: true,
                baseClass: 'kt-app__aside',
                closeBy: 'kt_user_profile_aside_close',
                toggleBy: 'kt_subheader_mobile_toggle'
            });
        }

        var initUserForm = function() {
            profile = new KTAvatar('provider_logo');
        }

        return {
            // public functions
            init: function() {
                initAside();
                initUserForm();
            }
        };
    }();

    var toolbar_show = [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
    ];

    jQuery(document).ready(function() {
        FormDesign.init();
        Profile.init();

        $("#accreditation_expiration_date").datepicker({
            todayHighlight: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            },
        });

        $('textarea#about').summernote({
            height: 80,
            toolbar: toolbar_show,
        });
    
        $(document).on('click', function (e) {
            if ($(e.target).closest("#about_group").length === 0) {
                $("#div_about").html($.trim($("#about").val())).show();
                $("#textarea_about").hide();
            }
        });

        $("#about_group").click(function(){
            $("#textarea_about").show();
            $("#div_about").hide();
        }); 

        $('#profession').select2({
            placeholder: "You can multiple-select Professions"
        });

        $('#seo_url').keyup(function(event){
            var value = event.target.value;
            $('#seo_url').val(value.replace(/([~!@#$%^&*()+=`{}\[\]\|\\:;'<>,\/? ])+/g, ''));
        });

    });

    $('.kt-avatar__cancel').click(function(){
        uploaded_image = null;
    });

    // CROPPER JS
    var cropper_modal = $('#cropper_modal');
    var image = document.getElementById('profile_image');
    var cropper;

    $("input[type=file]").change(function(e){
        var files = e.target.files;
        if (files && files.length > 0) {
            var file = files[0];
            var reader = new FileReader();
        
            reader.onloadend = function () {
                $('#profile_image').attr('src', reader.result);
                $('#cropper_modal').modal({backdrop: 'static', keyboard: false, show:true});
            }

            if (file) {
                reader.readAsDataURL(file);
            } 
        }
    });

    cropper_modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 2,
            zoomable: false,
            maxHeight: 100,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });
    
    $("#crop_submit").click(function(){
        canvas = cropper.getCroppedCanvas({
            width: 200,
            height: 200,
        });

        canvas.toBlob(function(blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob); 
            reader.onloadend = function() {
                var base64data = reader.result;	
                uploaded_image = base64data;
                cropper_modal.modal('hide');
                $('.kt-avatar__holder').css("background-image", "url("+base64data+")");
            }
        });
    });
</script>    
@endsection
