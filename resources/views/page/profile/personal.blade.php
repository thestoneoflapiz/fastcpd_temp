@extends('template.master_noleft')
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
<link href="{{asset('css/pages/settings/user-signature.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('css/cropper.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
{{ csrf_field() }}
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Account Profile &nbsp;
                            @if(Auth::user()->instructor == "in-review")
                            <i class="flaticon2-hourglass-1 kt-font-warning"></i><small>Information you've previously submitted as Instructor is currently in-review. <a href="/data/request/profile" target="_blank">Download submitted Profile</a></small>
                            @elseif(Auth::user()->instructor == "denied")
                            <i class="flaticon2-refresh-arrow kt-font-danger"></i><small>Information you've previously submitted as Instructor has been denied. <a href="/data/request/profile" target="_blank">Download submitted Profile</a></small>
                            @else
                            @endif
                        </h3>
                    </div>
                </div> 
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--label-left" id="formdata">
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
                                <div class="col-lg-10 col-xl-10 col-10">
                                    <div class="kt-avatar kt-avatar--outline" id="user_profile">
                                        <div class="kt-avatar__holder" style="background-image: url({{Auth::user()->image ?? asset('img/sample/noimage.png')}})"></div>
                                        <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change avatar">
                                            <i class="fa fa-pen"></i>
                                            <input type="file" name="profile_avatar" accept="image/*">
                                        </label>
                                        <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
                                            <i class="fa fa-times"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-4 col-md-5 col-sm-5 col-12 form-group">
                                    <label class="form-label">First name <text class="required">*</text></label>
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="first_name" name="first_name" placeholder="{{ Auth::user()->first_name }}" value="{{ Auth::user()->first_name }}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-xl-2 col-md-3 col-sm-3 col-6 form-group">
                                    <label class="form-label">Middle name <text class="required">*</text></label>
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="middle_name" name="middle_name" placeholder="{{ Auth::user()->middle_name }}" value="{{ Auth::user()->middle_name }}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-4 col-12 form-group">
                                    <label class="form-label">Last name <text class="required">*</text></label>
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="last_name" name="last_name" placeholder="{{ Auth::user()->last_name }}" value="{{ Auth::user()->last_name }}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-xl-5 col-md-6 col-12 form-group">
                                    <label class="form-label">Usename <text class="required">*</text></label>
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="username" name="username" {{ Auth::user()->un_change > 0 ? "disabled" : "" }} placeholder="{{ Auth::user()->username }}" value="{{ Auth::user()->username }}">
                                    </div>
                                    <span class="form-text text-muted">Note* You can only change your username once</span>
                                </div>
                            </div>

                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                            <div class="row">
                                <div class="col-xl-5 col-md-6 col-12 form-group">
                                    <label class="form-label">Email <text class="required">*</text></label>
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="email" name="email" placeholder="{{ Auth::user()->email }}" value="{{ Auth::user()->email }}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-xl-5 col-md-6 col-12 form-group">
                                    <label class="form-label">Mobile No. <text class="required">*</text></label>
                                    <div class="kt-typeahead">
                                        <input class="form-control" id="contact" type="text" name="contact" placeholder="{{ Auth::user()->contact ?? '+63 999-9999-999'}}" value="{{ Auth::user()->contact ?? ''}}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>

                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                             

                            <div class="form-group row">
                                <label class="col-form-label col-12">Headline</label>
                                <div class="col-xl-10 col-12 ">
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="headline" name="headline" placeholder="{{ Auth::user()->headline }}" value="{{ Auth::user()->headline }}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>

                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                            <div class="form-group row"> 
                                <label class="col-form-label col-12">About</label>
                                <div class="col-xl-10 col-12 " id="about_group">
                                    <div id="textarea_about" style="display:none;">
                                        <textarea class="form-control" id="about" name="about">{{ htmlspecialchars_decode(Auth::user()->about) }}</textarea>
                                    </div>
                                    <div class="box" id="div_about">
                                        <?= htmlspecialchars_decode(Auth::user()->about) ?>
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>

                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                            <div class="row">
                                <div class="col-xl-3 col-sm-6 form-group">
                                    <label class="form-label">Website</label>
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="website" name="website" placeholder="{{ Auth::user()->website ?? 'https://mywebsite/com/' }}" value="{{ Auth::user()->website }}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-xl-3 col-sm-6 form-group">
                                    <label class="form-label">Facebook</label>
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="facebook" name="facebook" placeholder="{{ Auth::user()->facebook ?? 'https://facebook.com/' }}" value="{{ Auth::user()->facebook }}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-xl-3 col-sm-6 form-group">
                                    <label class="form-label">LinkedIn</label>
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="linkedin" name="linkedin" placeholder="{{ Auth::user()->linkedin ?? 'https://www.linkedin.com/' }}" value="{{ Auth::user()->linkedin }}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>                                
                            </div>

                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                            <div class="row form-group">
                                <label class="col-form-label col-12">PRC Identification <text class="required">*</text></label>
                                <div class="col-xl-10 col-sm-12">
                                    <div class="dropzone dropzone-default dropzone-info" id="dropzone_images">
                                        <div class="dropzone-msg dz-message needsclick">
                                            <h3 class="dropzone-msg-title">Drop one(1) image per profession here</h3>
                                            <h3 class="dropzone-msg-title">Total profession: <text id="pro_count">{{ Auth::user()->professions ? count(json_decode(Auth::user()->professions)) : 0 }}</text></h3>
                                            <span class="dropzone-msg-desc">Only .png, .jpg, .jpeg file type to upload</span>
                                        </div>
                                    </div>
                                    <input class="form-control" type="hidden" name="prc" value="{{ Auth::user()->prc_id }}">
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>

                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group row">
                                        <label class="col-form-label col-12">Profession <text class="required">*</text></label>
                                        <div class="col-xl-10 col-sm-12">
                                            <select class="form-control kt-select2" id="profession" name="profession" multiple="multiple">
                                                @foreach(_all_professions() as $pro)
                                                    @if (in_array($pro['id'], $data["profession_ids"]))
                                                    <option value="{{ $pro['id'] }}" selected>{{ $pro['profession'] }}</option>
                                                    @else
                                                    <option value="{{ $pro['id'] }}">{{ $pro['profession'] }}</option>
                                                    <!-- <option value="{{ $pro['id'] }}" disabled>{{ $pro['profession'] }}</option> -->
                                                    @endif
                                                @endforeach
                                            </select>
                                            <span class="form-text text-muted"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12" id="display_professions">
                                </div>
                            </div>

                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                            <div class="row form-group">
                                <label class="col-form-label col-12">Signature:</label>
                                <div class="col-xl-4 col-md-6 col-sm-6 col">
                                    @if(Auth::user()->signature)
                                    <img src="<?=Auth::user()->signature?>" style="width:100%;"/>
                                    @else
                                    <span class="form-text text-muted">You don't have signature yet</span>
                                    @endif
                                </div>
                            </div>
                                        
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="row">
                                <div class="col" style="text-align:right;">
                                    <button type="button" class="btn btn-info kt-margin-r-10 kt-margin-b-10" data-toggle="modal" data-target="#upload_signature_modal" data-backdrop="static" data-keyboard="false">Upload Signature</button>
                                    <button id="submit_form" class="btn btn-success">Update Account Profile</button>
                                </div>
                            </div>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
</div>
<!--begin::Modal-->
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
<div class="modal fade" id="upload_signature_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Please SIGN here</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div id="signature-pad" class="signature-pad">
                        <div class="signature-pad--body">
                            <canvas width="420" height="200"></canvas>
                        </div>
                    </div>
                    <div class="col-12" style="text-align:center;">
                        <span>Please make adjustments on your signature's space</span>
                    </div>
                    <div class="signature-pad--footer " style="text-align:center;">
                        <div class="signature-pad--actions" style="margin:10px">
                            <button type="button" class="btn btn-sm btn-secondary kt-margin-r-10" id="clear_signature">Clear</button>
                            <button type="button" class="btn btn-sm btn-secondary" id="undo_signature"">Undo</button>
                        </div>
                        <div class="kt-checkbox-list" style="text-align:center;padding-left: 30;padding-right: 30;">
                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                <input type="checkbox" name="i_agree" id="i_agree" class ="permission"> <b style="color:white;"></b> 
                                <span></span>I am aware that FastCPD will use my signature for PRC application and certification purposes only. 
                                    I am aware that I am providing my personal data in accordance to the Data Privacy Act.
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save_signature">Submit</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
@endsection

@section('scripts') 
<script src="{{asset('js/cropper.js')}}" type="text/javascript"></script>
<script src="{{asset('js/signature_pad.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/settings/user-signature.js')}}" type="text/javascript"></script>
<script>
    var existing_prc_images = <?=Auth::user()->prc_id ?? 0 ?>;
    var max_image = <?=Auth::user()->professions ? count(json_decode(Auth::user()->professions)) : 0 ?>;
    var image_files = [];
    var no_images = 0;

    var uploaded_image = null;
    var profession_array = <?= json_encode($data['professions_user']); ?>;
    var before_profession_array = profession_array;

    var toolbar_show = [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
    ];

    $(document).ready(function() {
        if(existing_prc_images && existing_prc_images.length){
            existing_prc_images.forEach(epi => {
                image_files.push({file:epi, type:"old"});
            });
        }
        
        // phone number format
        Profile.init();
        FormDesign.init();
        
        $('#username').keyup(function(event){
            var value = event.target.value;
            $('#username').val(value.replace(/([~!@#$%^&*()+=`{}\[\]\|\\:;'<>,\/? ])+/g, ''));
        });

        $('#dropzone_images').dropzone({
            url: "/personal/register/action/images", // Set the url for your upload script location
            paramName: "file", // The name that will be used to transfer the file
            maxFiles: max_image,
            maxFilesize: 5, // MB
            addRemoveLinks: true,
            acceptedFiles: "image/*",
            init: function(){
                if(existing_prc_images && existing_prc_images.length){
                    for (var i = 0; i < existing_prc_images.length; i++) {
                        var re = /(?:\.([^.]+))?$/;
                        var ext = re.exec(existing_prc_images[i])[1];

                        var mockFile = { 
                            name: `PRCID${i+1}.${ext}`, 
                            type: `image/${ext}`, 
                            status: Dropzone.ADDED, 
                            url: existing_prc_images[i] 
                        };

                        this.emit("addedfile", mockFile);
                        this.emit("thumbnail", mockFile, existing_prc_images[i]);
                        this.emit("complete", mockFile);
                        this.files.push(mockFile);
                    } 

                    this.options.maxFiles = existing_prc_images.length;
                }

                this.on("sending", function(file, xhr, formData){
                    no_images += 1;
                    formData.append("no_images", no_images);
                });

                this.on("complete", function (response) {
                    if(response.hasOwnProperty("xhr")){
                        var file = response.xhr.response;
                        image_files.push({file: file, type: "new"});
                        
                        $('input[name="prc"]').val(file);
                    }
                });

                this.on("removedfile", function (file) {
                    this.options.maxFiles = profession_array.length;
                    image_files = image_files.filter(uploaded => file.url != uploaded.file);
                    if(image_files.length == 0){
                        $('input[name="prc"]').val(null);
                    }
                });
            }
        });

        renderProfessions(profession_array, [], true);
        
        $('#profession').select2({
            placeholder: "You can multiple-select Professions"
        }).change(function(e){
            var selected = e.target.selectedOptions; 
            var collect_selected = [];
            var profession_array_ids = [];

            if(selected.length != 0){
                for (let option = 0; option < selected.length; option++) {
                    var value = selected[option];
                    profession_array_ids.push(value.value); 

                    var is_selected_before = before_profession_array.filter(looking=>looking.id == value.value);
                    if(is_selected_before.length==1){
                        collect_selected.push({
                            id: value.value,
                            name: value.label,
                            prc_no: is_selected_before[0].prc_no,
                            expiration_date: is_selected_before[0].expiration_date,
                        });
                    }else{
                        collect_selected.push({
                            id: value.value,
                            name: value.label,
                            prc_no: null,
                        });
                    }
                }

                profession_array = profession_array.filter(fillout => jQuery.inArray(fillout.id, profession_array_ids));
            }else{
                profession_array = [];
                $('#dropzone_images')[0].dropzone.removeAllFiles();  
            }
            
            renderProfessions(profession_array, collect_selected, false);
            $("#pro_count").html(collect_selected.length);
            $('#dropzone_images')[0].dropzone.options.maxFiles = collect_selected.length;
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
    });

    var Profile = function () {
	// Base elements
        var profile;

        var initUserForm = function() {
            profile = new KTAvatar('user_profile');
        }

        return {
            // public functions
            init: function() {
                initUserForm();
            }
        };
    }();

    var FormDesign = function () {
        var input_masks = function () {
           // phone number format
           $("#contact").inputmask("mask", {
               "mask": "+63 999-999-9999",
           });        
       }

       // Private functions
        var validator;

        var input_validations = function () {
            validator = $( "#formdata" ).validate({
                // define validation rules
                rules: {
                    first_name: {
                        required: true,
                    },
                    middle_name: {
                        required: true,
                    },
                    last_name: {
                        required: true,
                    },
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
                    headline: {
                        maxlength: 250,
                    },
                    profession: {
                        required: true,
                    },
                    prc: {
                        required: true,
                    },
                    resume: {
                        required: true,
                    }
                },
                
                //display error alert on form submit  
                invalidHandler: function(event, validator) {     
                    toastr.error("Required!", "Sorry! Some of the fields are required");        
                },

                submitHandler: function (form) {
                    $("#submit_form").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light').prop("disabled", true);
                    if(profession_array.length == image_files.length && image_files.length > 0){
                        var submit_form = $.ajax({
                            url: '/personal/action',
                            type: 'POST',
                            data: {
                                image: uploaded_image,
                                first_name: $('#first_name').val(),
                                middle_name: $('#middle_name').val(),
                                last_name: $('#last_name').val(),
                                username: $('#username').val(),
                                contact: $('#contact').val(),
                                email: $('#email').val(),
                                headline: $("#headline").val(),
                                about: $.trim($("#about").val()),
                                website: $('#website').val(),
                                facebook: $('#facebook').val(),
                                linkedin: $('#linkedin').val(),
                                image_files: image_files,
                                professions: profession_array,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function(response){
                                toastr.success('Success!', 'Personal Information updated!');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            },
                            error: function(response){
                                var body = response.responseJSON;
                                if(body.hasOwnProperty("message")){
                                    toastr.error(body.message);
                                }else{
                                    toastr.error("Something went wrong! Please refresh your browser");
                                }
                                $("#submit_form").removeClass(`kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light`).prop("disabled", false);
                            }
                        });
                    }else{
                        if(profession_array.length != image_files.length){
                            toastr.error('Required!', `Sorry! The Number of ID's you need to upload should match the number of profession you have`);
                        }
                        $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light').prop("disabled", false);
                    }
                }
            });       

            $(`input[name^=input_prc_]`).each(function () {
                $(this).rules("add", {
                    required: true,
                });
            });
        }

        return {
            // public functions
            init: function() {
                input_validations(); 
                input_masks();
            }
        };
    }();
    
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

    function renderProfessions(profession, collected, from_start){
        var div = "";
        if(from_start){
            profession.map( (pro, key) => {
                div += "\
                    <div class='row kt-margin-b-10'>\
                        <div class='col-xl-3 col-md-3 col-sm-4 kt-margin-b-10'> \
                            <input type='text' class='form-control' disabled value='"+pro.name+"'/>\
                        </div>\
                        <div class='col-xl-3 col-md-3 col-sm-4 kt-margin-b-10'>\
                            <input type='text' class='form-control' name='input_prc_"+pro.id+"' id='input_"+pro.id+"' onchange='renderPRC("+pro.id+", \"#input_"+pro.id+"\")' value='"+(pro.prc_no == null ? "" : pro.prc_no )+"'/>\
                        </div>\
                        <div class='col-xl-3 col-md-3 col-sm-4  kt-margin-b-10'>\
                            <input type='text' class='form-control' id='input_expiration_"+pro.id+"' name='input_expiration["+pro.id+"]' onchange='renderPRC("+pro.id+", \"#input_expiration_"+pro.id+"\")' value='"+(pro.expiration_date == null ? "" : pro.expiration_date)+"' placeholder='License Expiration Date'/>\
                        </div>\
                    </div>\
                ";
            });
        }else{

            if(collected.length > 0){
                collected.map( (collect, key) => {
                    var finding = profession.find((pro, key)=>pro.id==collect.id);
                    if(finding){
                        div += "\
                            <div class='row kt-margin-b-10'>\
                                <div class='col-xl-3 col-md-3 col-sm-4 kt-margin-b-10'>\
                                    <input type='text' class='form-control' disabled value='"+finding.name+"'/>\
                                </div>\
                                <div class='col-xl-3 col-md-3 col-sm-4 kt-margin-b-10'>\
                                    <input type='text' class='form-control' name='input_prc_"+finding.id+"'  id='input_"+finding.id+"' onchange='renderPRC("+finding.id+", \"#input_"+finding.id+"\")' value='"+(finding.prc_no ? finding.prc_no : "")+"'/>\
                                </div>\
                                <div class='col-xl-3 col-md-3 col-sm-4 kt-margin-b-10'>\
                                    <input type='text' class='form-control' id='input_expiration_"+finding.id+"' name='input_expiration["+finding.id+"]' onchange='renderPRC("+finding.id+", \"#input_expiration_"+finding.id+"\")' value='"+(finding.expiration_date == null ? "" : finding.expiration_date)+"' placeholder='License Expiration Date'/>\
                                </div>\
                            </div>\
                        ";
                    }else{
                        div += "\
                            <div class='row kt-margin-b-10'>\
                                <div class='col-xl-3 col-md-3 col-sm-4 kt-margin-b-10'>\
                                    <input type='text' class='form-control' disabled value='"+collect.name+"'/>\
                                </div>\
                                <div class='col-xl-3 col-md-3 col-sm-4 kt-margin-b-10'>\
                                    <input type='text' class='form-control' name='input_prc_"+collect.id+"' id='input_"+collect.id+"' onchange='renderPRC("+collect.id+", \"#input_"+collect.id+"\")' value='"+(collect.prc_no ? collect.prc_no : "")+"'/>\
                                </div>\
                                <div class='col-xl-3 col-md-3 col-sm-4 kt-margin-b-10'>\
                                    <input type='text' class='form-control' id='input_expiration_"+collect.id+"' name='input_expiration["+collect.id+"]' onchange='renderPRC("+collect.id+", \"#input_expiration_"+collect.id+"\")' value='"+(collect.expiration_date == null ? "" : collect.expiration_date)+"' placeholder='License Expiration Date'/>\
                                </div>\
                            </div>\
                        ";

                        profession_array.push({
                            id: collect.id,
                            name: collect.name,
                            prc_no: collect.prc_no,
                            expiration_date: collect.expiration_date
                        });
                    }
                });
            }
        }
        $("#display_professions").empty().html(div);
        $(`[name^="input_expiration"]`).each(function(){
            $(this).datepicker({
                todayHighlight: true,
                defaultDate: "+1w",
                minDate: new Date(),
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                },
            });
        });
        FormDesign.init();
        
    }

    function renderPRC(id, input){
        var newProfessionArray = profession_array.map((pro, key) => {
            if(pro.id == id){
                var input_expiration = $('#input_expiration_'+id).val();
                var input_value = $('#input_'+id).val();

                return {
                    id: pro.id,
                    name: pro.name,
                    prc_no: input_value,
                    expiration_date: input_expiration
                };
            }

            return pro;
        });
        profession_array = newProfessionArray;
    }
</script>
@endsection

