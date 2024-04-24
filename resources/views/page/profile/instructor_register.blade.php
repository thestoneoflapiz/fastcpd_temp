@extends('template.master_form')
@section('styles')
<style>
    .required{color:#fd397a;}
    .bold{font-weight:600;}
    .box{padding: 5px;border:1px solid #e2e5ec;border-radius:5px; min-height:120px}
    .padding-bottom{padding-bottom:10px;}
    .profile_image {display: block;max-width: 100%;}
    .preview {overflow: hidden;width: 200px; height: 200px;margin: 0px 10px 0px 10px; border:1px solid #fd397a;}
    .dropzone .dz-preview .dz-details .dz-size span { display:none !important;}
    .dz-image img { width: 100%!important; height: 100% !important;}

    /**
     * Icons on Headline
     * 
     */
    .icon-image{height:80px;}
    @media (max-width: 600px){p.bold{text-align:center;}}

    div.dropzone{min-height:250px !important;}

</style>
<link href="{{asset('css/cropper.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-md-12">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Become an Instructor
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
            @if(Auth::user()->email_verified_at != null)
                @if(Auth::user()->instructor != "none") 
                <div class="kt-portlet__body" style="text-align:center">
                    <h3>Sorry!<br>You're already registered as an Instructor.</h3>
                </div>
                @endif             

                @if(Auth::user()->instructor == "none")
                <div class="kt-portlet__body register-initial-page">
                    <div class="row justify-content-center">
                        <div class="col-12" style="text-align:center;">
                            <h3>We want to make an impact to over 1,000,000 professionals in our country</h3>
                            <div class="kt-space-30"></div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-12">
                            <div class="row">
                                <div class="col-xl-4 col-md-4 col-sm-4 col-12" align="center">
                                    <img src="{{ asset('img/system/organization.png') }}" class="icon-image">
                                </div>
                                <div class="col-xl-8 col-md-7 col-sm-7 col-12">
                                    <p class="bold">Allow accredited providers to add you and contribute to their organization</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-12">
                            <div class="row">
                                <div class="col-xl-4 col-md-4 col-sm-4 col-12" align="center">
                                    <img src="{{ asset('img/system/instructor.png') }}" class="icon-image">
                                </div>
                                <div class="col-xl-8 col-md-7 col-sm-7 col-12">
                                    <p class="bold">Get your own instructor's page and share courses where you are affiliated </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-12">
                            <div class="row">
                                <div class="col-xl-4 col-md-4 col-sm-4 col-12" align="center">
                                    <img src="{{ asset('img/system/website.png') }}" class="icon-image">
                                </div>
                                <div class="col-xl-8 col-md-7 col-sm-7 col-12">
                                    <p class="bold">Make meaningful education content for Professionals</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-space-20"></div>
                    <div class="kt-form__actions" style="text-align:center;">
                        <button class="btn btn-info btn-lg btn-tall btn-wide kt-font-bold kt-font-transform-u" id="register">
                            Become an Instructor
                        </button>
                    </div>    
                    <div class="kt-space-30"></div>
                    <div class="row justify-content-center">
                        <h5>or do you want to sign up your own provider organization? <a href="/provider/register">Become a Provider</a></h5> 
                    </div>
                </div>
                <div class="kt-portlet__body" style="display:none;" id="complete_details">
                    <div class="row justify-content-center">
                        <h3>Great! You are now an Instructor.</h3>
                        <h5>Complete your profile details to publish and make your profile viewable to the public</h5>
                    </div>
                    <div class="kt-space-10"></div>
                    <form class="kt-form kt-form--label-left" id="formdata">
                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <div class="col-lg-12 col-xl-12" style="text-align:center;">
                                    <div class="kt-avatar kt-avatar--outline" id="user_profile">
                                        <div class="kt-avatar__holder" style="background-image: url({{Auth::user()->image ?? asset('img/sample/noimage.png')}})"></div>
                                        <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change avatar">
                                            <i class="fa fa-pen"></i>
                                            <input type="file" name="profile_avatar" accept="image/*">
                                        </label>
                                        <label class="col-form-label">Image</label>
                                        <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
                                            <i class="fa fa-times"></i>
                                        </span>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                    <span class="form-text text-muted">Please upload a clean white background image</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="name" name="name" placeholder="Name" value="{{ Auth::user()->name }}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="username" name="username" placeholder="Username" value="{{ Auth::user()->username }}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>
                            <div class="form-group row"> 
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="kt-typeahead">
                                        <input class="form-control" type="text" id="headline" name="headline" placeholder="Headline" value="{{ Auth::user()->headline }}">
                                    </div>
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6" id="about_group">
                                    <textarea class="form-control" id="about" name="about" placeholder="About"></textarea>
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>
                            <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                            <div class="row form-group">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="dropzone dropzone-default dropzone-success" id="dropzone_pdf">
                                        <div class="dropzone-msg dz-message needsclick">
                                            <h2 class="dropzone-msg-title">Resume</h2>
                                            <h3 class="dropzone-msg-title">Drop one(1) PDF file here</h3>
                                            <span class="dropzone-msg-desc">Only pdf file type to upload</span>
                                        </div>
                                    </div>
                                    <input class="form-control" type="hidden" name="resume" value="{{ Auth::user()->resume }}">
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="dropzone dropzone-default dropzone-info" id="dropzone_images">
                                        <div class="dropzone-msg dz-message needsclick">
                                            <h2 class="dropzone-msg-title">PRC Identification</h2>
                                            <h3 class="dropzone-msg-title">Drop one(1) image per profession here</h3>
                                            <h3 class="dropzone-msg-title">Total profession: <text id="pro_count">{{ count(json_decode(Auth::user()->professions)) }}</text></h3>
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
                                        <div class="col-xl-12">
                                            <select class="form-control kt-select2" id="profession" name="profession" multiple="multiple" style="width:100%;">
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
                        </div>
                        <div class="kt-portlet__foot">
                            <div class=" ">
                                <div class="row">
                                    <div class="col-lg-12 ml-lg-auto kt-align-right">
                                        <button id="submit_form" class="btn btn-info">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>                   
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
                <!--end::Modal-->
                @endif  
            @else
                <div class="kt-portlet__body" style="text-align:center">
                    <h3>Sorry!<br>You're email isn't verified yet. Please check your email's inbox!</h3>
                </div>
            @endif  
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script src="{{asset('js/cropper.js')}}" type="text/javascript"></script>
<script>
    var existing_prc_images = <?=Auth::user()->prc_id ?? 0 ?>;
    var existing_pdf_resume = "<?=Auth::user()->resume ?>";
    var max_image = <?=count(json_decode(Auth::user()->professions)) ?? 0 ?>;
    var pdf_file = null;
    var image_files = [];
    var no_images = 0;

    var uploaded_image = null;
    var profession_array = [];

    profession_array = <?=json_encode($data['professions_user']); ?>;

    var toolbar_show = [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
    ];

    $(document).ready(function() {
        // phone number format
        Profile.init();
        FormDesign.init();

        $("#register").click(function(event){
            event.preventDefault();
            
            $.ajax({
                url: '/instructor/register/action',
                type: 'POST',
                data: {
                    user: <?=Auth::user()->id ?? 0 ?>,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response){
                    if(response.status==200){
                        toastr.success('Success!', 'Registration for Instructor is sent! Please wait for the approval for publicly display your profile. Thank you!');
                        setTimeout(() => {
                            window.location="/";
                        }, 1000);
                    }else if(response.status=="pending"){
                        toastr.success('Success!', 'Registration for Instructor is sent! Please complete your account and profile. Thank you!');
                        $('#complete_details').css('display', 'block');
                        $('.register-initial-page').css('display', 'none');
                    }else{
                        $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                        toastr.error('Error!', response.message);
                    }
                },
                error: function(response){
                    $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                    toastr.error('Error!', 'Something went wrong! Please try again later.');
                }
            });
        });

        $('#dropzone_pdf').dropzone({
            url: "/personal/register/action/pdf", // Set the url for your upload script location
            paramName: "file", // The name that will be used to transfer the file
            maxFiles: 1,
            maxFilesize: 5, // MB
            addRemoveLinks: true,
            acceptedFiles: "application/pdf",
            init: function(){

                if(existing_pdf_resume){
                    var mockFile = { 
                        name: `PDFResume.pdf`, 
                        type: 'application/pdf', 
                        status: Dropzone.ADDED, 
                        url: existing_pdf_resume 
                    };

                    this.emit("addedfile", mockFile);
                    this.emit("thumbnail", mockFile, "<?=asset('img/pdf.png')?>");
                    this.emit("complete", mockFile);
                    this.files.push(mockFile);

                    this.options.maxFiles = 0;
                }

                this.on("complete", function (response) {
                    if(response.hasOwnProperty("xhr")){
                        var file = response.xhr.response;
                        pdf_file = file;
                        $('input[name="resume"]').val(file);
                    }
                });

                this.on("removedfile", function (response) {
                    pdf_file = null;
                    $('input[name="resume"]').val(null);
                    this.options.maxFiles = 1;
                });
            }
        });

        $('#dropzone_images').dropzone({
            url: "/personal/register/action/images", // Set the url for your upload script location
            paramName: "file", // The name that will be used to transfer the file
            maxFiles: max_image,
            maxFilesize: 5, // MB
            addRemoveLinks: true,
            acceptedFiles: "image/*",
            init: function(){
                
                if(existing_prc_images){
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

                    this.options.maxFiles = 0;
                }

                this.on("sending", function(file, xhr, formData){
                    no_images += 1;
                    formData.append("no_images", no_images);
                });

                this.on("complete", function (response) {
                    if(response.hasOwnProperty("xhr")){
                        var file = response.xhr.response;
                        image_files.push(file);
                        $('input[name="prc"]').val(file);
                    }
                });

                this.on("removedfile", function (response) {
                    
                    this.options.maxFiles = profession_array.length;
                    
                    if(response.hasOwnProperty("xhr")){
                        var file = response.xhr.response;
                        const index = image_files.indexOf(file);
                        if (index > -1) {
                            image_files.splice(index, 1);

                            if(index == 0){
                                $('input[name="prc"]').val(null);
                            }
                        }
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

            if(selected.length != 0){
                for (let option = 0; option < selected.length; option++) {
                    var value = selected[option];
                    collect_selected.push({
                        id: value.value,
                        name: value.label,
                        prc_no: null,
                    })
                }
            }else{
                profession_array = [];
                $('#dropzone_images')[0].dropzone.removeAllFiles();  
            }
            
            renderProfessions(profession_array, collect_selected, false);
            $("#pro_count").html(collect_selected.length);
            $('#dropzone_images')[0].dropzone.options.maxFiles = collect_selected.length;
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
        // Private functions
        var validator;

        var input_validations = function () {
            validator = $( "#formdata" ).validate({
                // define validation rules
                rules: {
                    name: {
                        required: true,
                    },
                    username: {
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
                    prc: {
                        required: true,
                    },
                    resume: {
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
                    if((profession_array.length == image_files.length && image_files.length > 0 && pdf_file != null) || (existing_prc_images && existing_prc_images.length == profession_array.length)){
                        var submit_form = $.ajax({
                            url: '/instructor/register/complete_register',
                            type: 'POST',
                            method: 'POST',
                            data: {
                                image: uploaded_image,
                                name: $('#name').val(),
                                username: $('#username').val(),
                                headline: $("#headline").val(),
                                about: $("#about").val(),
                                pdf_file: pdf_file ? pdf_file : "same",
                                image_files: image_files.length > 0 ? image_files : "same",
                                professions: profession_array,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function(response){
                                if(response.status==200){
                                    toastr.success('Success!', 'Personal Information updated!');
                                    setTimeout(() => {
                                        window.location="/";
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
                    }else{
                        if(profession_array.length != image_files.length){
                            toastr.error('Required!', `Sorry! You cannot upload more IDs since you only have ${profession_array.length} professions`);
                        }
                        $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                    }
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
                console.log(pro);
                div += "\
                    <div class='row padding-bottom'>\
                        <div class='col-xl-3 col-md-3'></div>\
                        <div class='col-xl-3 col-md-3'>\
                            <input type='text' class='form-control' disabled value='"+pro.name+"'/>\
                        </div>\
                        <div class='col-xl-3 col-md-3'>\
                            <input type='text' class='form-control' id='input_"+pro.id+"' onchange='renderPRC("+pro.id+", \"#input_"+pro.id+"\")' value='"+(pro.prc_no == null ? "" : pro.prc_no)+"'/>\
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
                            <div class='row padding-bottom'>\
                                <div class='col-xl-3 col-md-3'></div>\
                                <div class='col-xl-3 col-md-3'>\
                                    <input type='text' class='form-control' disabled value='"+finding.name+"'/>\
                                </div>\
                                <div class='col-xl-3 col-md-3'>\
                                    <input type='text' class='form-control' id='input_"+finding.id+"' onchange='renderPRC("+finding.id+", \"#input_"+finding.id+"\")' value='"+(finding.prc_no == null ? "" : finding.prc_no)+"'/>\
                                </div>\
                            </div>\
                        ";
                    }else{
                        div += "\
                            <div class='row padding-bottom'>\
                                <div class='col-xl-3 col-md-3'></div>\
                                <div class='col-xl-3 col-md-3'>\
                                    <input type='text' class='form-control' disabled value='"+collect.name+"'/>\
                                </div>\
                                <div class='col-xl-3 col-md-3'>\
                                    <input type='text' class='form-control'  id='input_"+collect.id+"' onchange='renderPRC("+collect.id+", \"#input_"+collect.id+"\")'/>\
                                </div>\
                            </div>\
                        ";

                        profession_array.push({
                            id: collect.id,
                            name: collect.name,
                            prc_no: collect.prc_no,
                        });
                    }
                });
            }
        }
        
        $("#display_professions").empty().html(div);
    }

    function renderPRC(id, input){
        var newProfessionArray = profession_array.map((pro, key) => {
            if(pro.id == id){
                var input_value = $(input).val();
                return {
                    id: pro.id,
                    name: pro.name,
                    prc_no: input_value,
                };
            }

            return pro;
        });

        profession_array = newProfessionArray;
    }
</script>   
@endsection
