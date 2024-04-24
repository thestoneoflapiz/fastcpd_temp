@extends('template.master_provider')
@section('title', 'Provider Profile')
@section('styles')
<style>
    .required{color:#fd397a;}
    .box{padding: 5px;border:1px solid #e2e5ec;border-radius:5px; min-height:120px}
    .padding-bottom{padding-bottom:10px;}
    .profile_image {display: block;max-width: 100%;}
    .preview {overflow: hidden;width: 200px; height: 200px;margin: 0px 10px 0px 10px; border:1px solid #fd397a;}
    .dropzone .dz-preview .dz-details .dz-size span { display:none !important;}
    .dz-image img { width: 100%!important; height: 100% !important;}
</style>
<link href="{{asset('css/cropper.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Profile Information &nbsp;
                            @if(_current_provider()->request->status == "in-review")
                            <i class="flaticon2-hourglass-1 kt-font-warning"></i><small>Information you've previously registered as Provider is currently in-review. <a href="/data/request/provider" target="_blank">Download submitted Profile</a></small>
                            @elseif(_current_provider()->request->status == "blocked")
                            <i class="flaticon2-refresh-arrow kt-font-danger"></i><small>Information you've previously submitted as Provider has been denied. <a href="/data/request/provider" target="_blank">Download submitted Profile</a></small>
                            @else
                            @endif
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="btn btn-info" onclick="window.open('/provider/{{ _current_provider()->url }}')">View Provider Profile</button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--label-left" id="formdata">
                        <div class="kt-portlet__body">
                            <div class="kt-form__content">
                                <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="form_msg">
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

                            <div class="form-group  row">
                                <label class="col-form-label col-lg-3 col-sm-12">Provider Logo <text class="required">*</text></label>
                                <div class="col-lg-7 col-md-9 col-sm-12" style="justify-content:center;">
                                    <div class="kt-avatar kt-avatar--outline" id="provider_logo">
                                        <div class="kt-avatar__holder" style="background-image: url({{ _current_provider()->logo ?? asset('img/sample/noimage.png') }})"></div>
                                        <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change avatar">
                                            <i class="fa fa-pen"></i>
                                            <input type="file" name="logo" accept="image/*">
                                        </label>
                                        <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
                                            <i class="fa fa-times"></i>
                                        </span> 
                                    </div>
                                    <input class="form-control" type="hidden" name="logo_image" value="{{ _current_provider()->logo }}">
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Provider Name <text class="required">*</text></label></label>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <input class="form-control" id="name" type="text" name="name" placeholder="{{ _current_provider()->name }}" value="{{ _current_provider()->name }}">
                                    <span class="form-text text-muted">Please enter the provider's name</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Target Profession <text class="required">*</text></label>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <select class="form-control kt-select2" id="profession" name="profession" multiple="multiple">
                                        @foreach(_all_professions() as $pro)
                                            @if (in_array($pro['id'], json_decode(_current_provider()->profession_id) ))
                                            <option value="{{ $pro['id'] }}" selected>{{ $pro['profession'] }}</option>
                                            @else
                                            <option value="{{ $pro['id'] }}">{{ $pro['profession'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="form-text text-muted">Please select your provider's target profession</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Accreditation No. <text class="required">*</text></label>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <input class="form-control" id="accreditation" type="text" name="accreditation" value="{{ _current_provider()->accreditation_number }}">
                                    <span class="form-text text-muted">Please enter your unique accreditation number</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Accreditation Expiration Date <text class="required">*</text></label>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <input type="text" class="form-control" name="accreditation_expiration_date" id="accreditation_expiration_date" readonly placeholder="Select Expiration Date" value="{{ date('m/d/Y', strtotime(_current_provider()->accreditation_expiration_date))}}" />
                                    <span class="form-text text-muted">Please select the expiration of accreditation</span>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Headline <text class="required">*</text></label>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <input class="form-control" id="headline" type="text" name="headline" value="{{ _current_provider()->headline }}">
                                    <span class="form-text text-muted">Please enter a headline for your provider's page</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">About <text class="required">*</text></label>
                                <div class="col-lg-7 col-md-9 col-sm-12" id="about_group">
                                    <div id="textarea_about" style="display:none;">
                                        <textarea class="form-control" id="about" name="about">{{ htmlspecialchars_decode(_current_provider()->about) }}</textarea>
                                    </div>
                                    <div id="div_about" class="box">
                                        <?= htmlspecialchars_decode(_current_provider()->about) ?>
                                    </div>
                                    <span class="form-text text-muted">Please provide an about for your provider page</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Website</label>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <input class="form-control" id="website" type="text" name="website" placeholder="https://my-provider.com" value="{{ _current_provider()->website }}">
                                    <span class="form-text text-muted">Please enter your Provider's website link</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Facebook</label>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <input class="form-control" id="facebook" type="text" name="facebook" placeholder="https://facebook.com/" value="{{ _current_provider()->facebook }}">
                                    <span class="form-text text-muted">Please enter your Facebook Page/Profile link</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">LinkedIn</label>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <input class="form-control" id="linkedin" type="text" name="linkedin" placeholder="https://linkedin.com/" value="{{ _current_provider()->linkedin }}">
                                    <span class="form-text text-muted">Please enter your LinkedIn link</span>
                                </div>
                            </div>
                        </div>

                        <div class="kt-portlet__foot">
                            <div class="row" style="float:right">
                                <div class="col-lg-12 ml-lg-xl-auto">
                                    <button id="submit_form" class="btn btn-success">Update Provider Details</button>
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
        </div>
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
    var existing_logo = "<?=_current_provider()->logo?>";

    var FormDesign = function () {
        // Private functions
        var input_masks = function () {
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
            validator = $( "#formdata" ).validate({
                // define validation rules
                rules: {
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
                    accreditation_expiration_date: {
                        required: true,
                        date: "mm-dd-yyyy",
                        minDate: true,
                    },
                    logo_image:{
                        required: true,
                    }
                },
                
                //display error alert on form submit  
                invalidHandler: function(event, validator) {             
                    var alert = $('#form_msg');
                    alert.removeClass('kt-hidden').show();
                    KTUtil.scrollTop();
                },

                submitHandler: function (form) {

                    $("#submit_form").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                    if(uploaded_image || existing_logo){
                        var submit_form = $.ajax({
                            url: '/provider/profile/action',
                            type: 'POST',
                            data: {
                                provider_id: <?=_current_provider()->id?>,
                                logo: uploaded_image ? uploaded_image : "same",
                                profession: $('#profession').val(),
                                name: $('#name').val(),
                                accreditation: $('#accreditation').val(),
                                accreditation_expiration_date: $('#accreditation_expiration_date').val(),
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
                                        location.reload();
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

                                    if(errors.hasOwnProperty("name")){
                                        html = html.concat("* <b>Provider Name</b> has already been taken <br/>");
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

        var initUserForm = function() {
            profile = new KTAvatar('provider_logo');
        }

        return {
            // public functions
            init: function() {
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
    });

    $('.kt-avatar__cancel').click(function(){
        uploaded_image = null;
        $('input[name="logo_image"]').val(null);
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
                $('input[name="logo_image"]').val(base64data);

                cropper_modal.modal('hide');
                $('.kt-avatar__holder').css("background-image", "url("+base64data+")");
            }
        });
    });
</script>    
@endsection