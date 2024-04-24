@extends('template.master_course_creation')

@section('styles')
<style>
    .course_poster{width:500px;border: 1px solid rgba(82, 63, 105, 0.1);border-radius:3px;box-shadow:0px 0px 13px 0px rgba(82, 63, 105, 0.05);}
</style>
@endsection

@section('content')
<form class="kt-form kt-form--label-left" id="form">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Attract Enrollments
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">

                <!--begin::Portlet-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <span class="kt-portlet__head-icon"><i class="fa fa-image circle-icon"></i></span>
                            <h3 class="kt-portlet__head-title">Course Poster</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-portlet__content">
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
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                                    <!--begin::Portlet-->
                                    <div class="kt-portlet kt-portlet--height-fluid">

                                        <div class="kt-portlet__body">
                                            <div class="kt-portlet__content">
                                                <img src="{{ _current_course()->course_poster ?? asset('img/system/logo-1.png')}}" class="fit-img fit-img-tight course_poster"/>
                                            </div>
                                        </div>
                                    </div>

                                    <!--end::Portlet-->
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                                    <!--begin::Portlet-->
                                    <div class="kt-portlet kt-portlet--height-fluid">
                                        <div class="kt-portlet__body">
                                            <div class="kt-portlet__content">
                                                <p> Make your course standout with a great image from our design team based on your preferences and style. <span class="color-green">Get your free image.</span> </p>
                                                <p>If you create your image, it must meet our <span class="color-green">course image quality standards</span> to be accepted.</p>
                                                <p> Important guidlines: 750x422 pixels; .jpg, jpeg, .gif, or .png. No text on the image.</p>

                                                <div class="col-lg-12" style="margin-top:75px;">
                                                    <div class="kt-uppy" id="kt_uppy_5">
                                                        <div class="kt-uppy__wrapper"></div>
                                                        <div class="kt-uppy__list"></div>
                                                        <div class="kt-uppy__status"></div>
                                                        <div class="kt-uppy__informer kt-uppy__informer--min"></div>
                                                    </div>
                                                    <span class="form-text text-muted">Max file size is 3MB and max number of files is 1.</span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Portlet-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <span class="kt-portlet__head-icon"><i class="fa fa-video circle-icon"></i></span>
                            <h3 class="kt-portlet__head-title">Promotional Video</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-portlet__content">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                                    <!--begin::Portlet-->
                                    <div class="kt-portlet kt-portlet--height-fluid">

                                        <div class="kt-portlet__body">
                                            <div class="kt-portlet__content">
                                                <img alt="FastCPD Company Logo Poster" src="{{asset('img/system/logo-1.png')}}" class="fit-img fit-img-tight" />
                                            </div>
                                        </div>
                                    </div>

                                    <!--end::Portlet-->
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                                    <!--begin::Portlet-->
                                    <div class="kt-portlet kt-portlet--height-fluid">
                                        <div class="kt-portlet__body">
                                            <div class="kt-portlet__content">
                                                <p>
                                                    Students who watch a well-made promo video are <b>5x more likely to enroll</b> in your course.
                                                    we've been seen that statistic to go up 10X for exceptionally awesome videos.
                                                    <span class="color-green">Learn how to make yours awesome.</span>
                                                </p>

                                                <div class="col-lg-12" style="margin-top:75px;">
                                                    <div class="kt-uppy" id="kt_uppy_5_1">
                                                        <div class="kt-uppy__wrapper"></div>
                                                        <div class="kt-uppy__list"></div>
                                                        <div class="kt-uppy__status"></div>
                                                        <div class="kt-uppy__informer kt-uppy__informer--min"></div>
                                                    </div>
                                                    <span class="form-text text-muted">Max file size is 3MB and max number of files is 1.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--end::Portlet-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <span class="kt-portlet__head-icon"><i class="fa fa-book-open circle-icon"></i></span>
                            <h3 class="kt-portlet__head-title">What will students learn in your course?</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-portlet__content">
                            <div id="repeater_objectives">
                                <div class="form-group form-group-last row" id="repeater_objectives">
                                    <label class="col-lg-2 col-form-label">Example: Identify AML suspicious transactions</label>
                                    <div data-repeater-list="" id="sortable_objectives" class="col-lg-10">
                                        <?php if (json_decode(_current_course()->objectives != null) && count(json_decode(_current_course()->objectives)) >= 1) { ?>
                                            @foreach(json_decode(_current_course()->objectives) as $obj)

                                            <div data-repeater-item class="form-group row align-items-center">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                    <div class="kt-form__group--inline">
                                                        <div class="kt-form__label">
                                                            <label>Objective:</label>
                                                        </div>
                                                        <div class="kt-form__control">
                                                            <input type="text" class="form-control" name="objective" value="{{ _current_course() ? $obj : ''}}" id="objective" placeholder="Enter your objective">
                                                        </div>
                                                    </div>
                                                    <div class="d-md-none kt-margin-b-10"></div>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                        <i class="la la-trash-o"></i>
                                                        Delete
                                                    </a>
                                                </div>
                                            </div>
                                            @endforeach
                                        <?php } else { ?>
                                            <div data-repeater-item class="form-group row align-items-center">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                    <div class="kt-form__group--inline">
                                                        <div class="kt-form__label">
                                                            <label>Objective:</label>
                                                        </div>
                                                        <div class="kt-form__control">
                                                            <input type="text" class="form-control" name="objective" id="objective" placeholder="Enter your objective">
                                                        </div>
                                                    </div>
                                                    <div class="d-md-none kt-margin-b-10"></div>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                        <i class="la la-trash-o"></i>
                                                        Delete
                                                    </a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div data-repeater-item class="form-group row align-items-center">
                                            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                <div class="kt-form__group--inline">
                                                    <div class="kt-form__label">
                                                        <label>Objective:</label>
                                                    </div>
                                                    <div class="kt-form__control">
                                                        <input type="text" class="form-control" name="objective" id="objective" placeholder="Enter your objective">
                                                    </div>
                                                </div>
                                                <div class="d-md-none kt-margin-b-10"></div>
                                            </div>

                                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                    <i class="la la-trash-o"></i>
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-last row">
                                    <label class="col-lg-2 col-form-label"></label>
                                    <div class="col-lg-4">
                                        <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                            <i class="la la-plus"></i> Add
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <span class="kt-portlet__head-icon"><i class="fa fa-scroll circle-icon"></i></span>
                            <h3 class="kt-portlet__head-title">Are there any course requirements or prerequisites?</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-portlet__content">
                            <div id="repeater_requirements">
                                <div class="form-group form-group-last row" id="repeater_requirements">
                                    <label class="col-lg-2 col-form-label">Example: Basic knowledge in medical terminology</label>
                                    <div data-repeater-list="" id="sortable_requirements" class="col-lg-10">
                                        <?php if (_current_course()->requirements != null && count(json_decode(_current_course()->requirements)) >= 1) { ?>
                                            @foreach(json_decode(_current_course()->requirements) as $req)

                                            <div data-repeater-item class="form-group row align-items-center">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                    <div class="kt-form__group--inline">
                                                        <div class="kt-form__label">
                                                            <label>Requirement:</label>
                                                        </div>
                                                        <div class="kt-form__control">
                                                            <input type="text" class="form-control" name="requirements" value="{{ _current_course() ? $req : ''}}" id="requirements" placeholder="Enter your requirement">
                                                        </div>
                                                    </div>
                                                    <div class="d-md-none kt-margin-b-10"></div>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                        <i class="la la-trash-o"></i>
                                                        Delete
                                                    </a>
                                                </div>
                                            </div>
                                            @endforeach
                                        <?php } else { ?>
                                            <div data-repeater-item class="form-group row align-items-center">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                    <div class="kt-form__group--inline">
                                                        <div class="kt-form__label">
                                                            <label>Requirement:</label>
                                                        </div>
                                                        <div class="kt-form__control">
                                                            <input type="text" class="form-control" name="requirements" id="requirements" placeholder="Enter your requirement">
                                                        </div>
                                                    </div>
                                                    <div class="d-md-none kt-margin-b-10"></div>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                        <i class="la la-trash-o"></i>
                                                        Delete
                                                    </a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div data-repeater-item class="form-group row align-items-center">
                                            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                <div class="kt-form__group--inline">
                                                    <div class="kt-form__label">
                                                        <label>Requirement:</label>
                                                    </div>
                                                    <div class="kt-form__control">
                                                        <input type="text" class="form-control" name="requirements" id="requirements" placeholder="Enter your requirement">
                                                    </div>
                                                </div>
                                                <div class="d-md-none kt-margin-b-10"></div>
                                            </div>

                                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                    <i class="la la-trash-o"></i>
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-last row">
                                    <label class="col-lg-2 col-form-label"></label>
                                    <div class="col-lg-4">
                                        <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                            <i class="la la-plus"></i> Add
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <span class="kt-portlet__head-icon"><i class="fa fa-crosshairs circle-icon"></i></span>
                            <h3 class="kt-portlet__head-title">Who are your target students?</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-portlet__content">
                            <div id="repeater_target_students">
                                <div class="form-group form-group-last row" id="repeater_target_students">
                                    <label class="col-lg-2 col-form-label">Engineers interested in robotics</label>
                                    <div data-repeater-list="" id="sortable_target_students" class="col-lg-10">
                                        <?php if (json_decode(_current_course()->target_students != null) && count(json_decode(_current_course()->target_students)) >= 1) { ?>
                                            @foreach(json_decode(_current_course()->target_students) as $trgt)

                                            <div data-repeater-item class="form-group row align-items-center">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                    <div class="kt-form__group--inline">
                                                        <div class="kt-form__label">
                                                            <label>Target Students:</label>
                                                        </div>
                                                        <div class="kt-form__control">
                                                            <input type="text" class="form-control" name="target_students" value="{{ _current_course() ? $trgt : ''}}" id="target_students" placeholder="Enter your target students">
                                                        </div>
                                                    </div>
                                                    <div class="d-md-none kt-margin-b-10"></div>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                        <i class="la la-trash-o"></i>
                                                        Delete
                                                    </a>
                                                </div>
                                            </div>
                                            @endforeach
                                        <?php } else { ?>
                                            <div data-repeater-item class="form-group row align-items-center">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                    <div class="kt-form__group--inline">
                                                        <div class="kt-form__label">
                                                            <label>Target Students:</label>
                                                        </div>
                                                        <div class="kt-form__control">
                                                            <input type="text" class="form-control" name="target_students" id="target_students" placeholder="Enter your target students">
                                                        </div>
                                                    </div>
                                                    <div class="d-md-none kt-margin-b-10"></div>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                        <i class="la la-trash-o"></i>
                                                        Delete
                                                    </a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div data-repeater-item class="form-group row align-items-center">
                                            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                <div class="kt-form__group--inline">
                                                    <div class="kt-form__label">
                                                        <label>Target Students:</label>
                                                    </div>
                                                    <div class="kt-form__control">
                                                        <input type="text" class="form-control" name="target_students" id="target_students" placeholder="Enter your target students">
                                                    </div>
                                                </div>
                                                <div class="d-md-none kt-margin-b-10"></div>
                                            </div>

                                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                    <i class="la la-trash-o"></i>
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-last row">
                                    <label class="col-lg-2 col-form-label"></label>
                                    <div class="col-lg-4">
                                        <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                            <i class="la la-plus"></i> Add
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="row" style="float:right">
                <div class="col-lg-12 ml-lg-xl-auto">
                    <button id="submit_form" class="btn btn-outline-success">Submit</button>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <button type="reset" class="btn btn-secondary">Clear</button>
                </div>
            </div>
        </div>
    </div>
    <input hidden name="course_image" id="course_image" value="" />
    <input hidden name="promotional_video" id="promotional_video" value="" />
</form>
@endsection

@section('scripts')
<!-- begin::UPPY -->
<script src="{{asset('plugins/custom/uppy/dist/uppy.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/pages/crud/file-upload/uppy.js')}}" type="text/javascript"></script>
<!-- end::UPPY -->
<script>
    jQuery(document).ready(function() {
       
        var KTFormRepeater = {
            init: function() {
                $("#repeater_objectives").repeater({
                    initEmpty: !1,
                    defaultValues: {
                        "text-input": "foo"
                    },
                    show: function() {
                        $(this).slideDown()
                    },
                    hide: function(e) {
                        $(this).slideUp(e)
                    }
                }), $("#repeater_requirements").repeater({
                    initEmpty: !1,
                    defaultValues: {
                        "text-input": "foo"
                    },
                    show: function() {
                        $(this).slideDown()
                    },
                    hide: function(e) {
                        $(this).slideUp(e)
                    }
                }), $("#repeater_target_students").repeater({
                    initEmpty: !1,
                    defaultValues: {
                        "text-input": "foo"
                    },
                    show: function() {
                        $(this).slideDown()
                    },
                    hide: function(e) {
                        $(this).slideUp(e)
                    }
                })
               
            }
        };
        KTFormRepeater.init()
        FormDesign.init();
        $(function() {
            $("#sortable_objectives").sortable();
            $("#sortable_objectives").disableSelection();
            $("#sortable_requirements").sortable();
            $("#sortable_requirements").disableSelection();
            $("#sortable_target_students").sortable();
            $("#sortable_target_students").disableSelection();
        });

        var KTUppy = function() {
            // Private functions
            const Tus = Uppy.Tus;
            const ProgressBar = Uppy.ProgressBar;
            const StatusBar = Uppy.StatusBar;
            const FileInput = Uppy.FileInput;
            const Informer = Uppy.Informer;
            const XHRUpload = Uppy.XHRUpload;

            // to get uppy companions working, please refer to the official documentation here: https://uppy.io/docs/companion/
            const Dashboard = Uppy.Dashboard;
            const Dropbox = Uppy.Dropbox;
            const GoogleDrive = Uppy.GoogleDrive;
            const Instagram = Uppy.Instagram;
            const Webcam = Uppy.Webcam;

            var initUppy5 = function() {
                // Uppy variables
                // For more info refer: https://uppy.io/
                var elemId = 'kt_uppy_5';
                var id = '#' + elemId;
                var $statusBar = $(id + ' .kt-uppy__status');
                var $uploadedList = $(id + ' .kt-uppy__list');
                var timeout;

                var uppyMin = Uppy.Core({
                    debug: true,
                    autoProceed: true,
                    showProgressDetails: true,
                    restrictions: {
                        maxFileSize: 5000000, // 1mb
                        maxNumberOfFiles: 1,
                        minNumberOfFiles: 1,
                        allowedFileTypes: ['.jpg', '.jpeg', '.gif', '.png', '.JPG', '.JPEG', '.GIF', '.PNG'],
                    }
                });

                uppyMin.use(FileInput, {
                    target: id + ' .kt-uppy__wrapper',
                    pretty: false
                });
                uppyMin.use(Informer, {
                    target: id + ' .kt-uppy__informer'
                });

                // demo file upload server
                uppyMin.use(XHRUpload, {
                    endpoint: '/course_management/upload_video',
                    method: 'post',
                    formData: true,
                    fieldName: 'files',
                    metaFields: null,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    bundle: false
                });
                uppyMin.use(StatusBar, {
                    target: id + ' .kt-uppy__status',
                    hideUploadButton: true,
                    hideAfterFinish: false
                });

                $(id + ' .uppy-FileInput-input').addClass('kt-uppy__input-control').attr('id', elemId + '_input_control');
                $(id + ' .uppy-FileInput-container').append('<label class="kt-uppy__input-label btn btn-label-brand btn-bold btn-font-sm" for="' + (elemId + '_input_control') + '">Attach files</label>');

                var $fileLabel = $(id + ' .kt-uppy__input-label');

                uppyMin.on('upload', function(data) {
                    $fileLabel.text("Uploading...");
                    $statusBar.addClass('kt-uppy__status--ongoing');
                    $statusBar.removeClass('kt-uppy__status--hidden');
                    clearTimeout(timeout);
                });

                uppyMin.on('complete', function(file) {
                    $.each(file.successful, function(index, value) {
                        var sizeLabel = "bytes";
                        var filesize = value.size;
                        if (filesize > 1024) {
                            filesize = filesize / 1024;
                            sizeLabel = "kb";

                            if (filesize > 1024) {
                                filesize = filesize / 1024;
                                sizeLabel = "MB";
                            }
                        }
                        
                        $("#course_image").attr("value", value.response.body.pathname);
                        var uploadListHtml = '<div class="kt-uppy__list-item" data-id="' + value.id + '"><div class="kt-uppy__list-label">' + value.name + ' (' + Math.round(filesize, 2) + ' ' + sizeLabel + ')</div><span class="kt-uppy__list-remove" data-id="' + value.id + '"><i class="flaticon2-cancel-music"></i></span></div>';
                        $uploadedList.append(uploadListHtml);
                    });
                    $.each(file.failed, function(index, value) {
                        var sizeLabel = "bytes";
                        var filesize = value.size;
                        if (filesize > 1024) {
                            filesize = filesize / 1024;
                            sizeLabel = "kb";

                            if (filesize > 1024) {
                                filesize = filesize / 1024;
                                sizeLabel = "MB";
                            }
                        }
                        var uploadListHtml = '<div class="kt-uppy__list-item" data-id="' + value.id + '"><div class="kt-uppy__list-label" style="color:red;"><i class="fa fa-exclamation-circle" style="color:red;height:10px;"></i> &nbsp;' + value.name + ' (' + Math.round(filesize, 2) + ' ' + sizeLabel + ')</div><span class="kt-uppy__list-remove" data-id="' + value.id + '"><i class="flaticon2-cancel-music"></i></span></div>';
                        $uploadedList.append(uploadListHtml);
                    });

                    $fileLabel.text("Add more files");

                    $statusBar.addClass('kt-uppy__status--hidden');
                    $statusBar.removeClass('kt-uppy__status--ongoing');
                });

                $(document).on('click', id + ' .kt-uppy__list .kt-uppy__list-remove', function() {
                    var itemId = $(this).attr('data-id');
                    uppyMin.removeFile(itemId);
                    $(id + ' .kt-uppy__list-item[data-id="' + itemId + '"').remove();
                });
            }
            var initUppy5_1 = function() {
                // Uppy variables
                // For more info refer: https://uppy.io/
                var elemId = 'kt_uppy_5_1';
                var id = '#' + elemId;
                var $statusBar = $(id + ' .kt-uppy__status');
                var $uploadedList = $(id + ' .kt-uppy__list');
                var timeout;

                var uppyMin = Uppy.Core({
                    debug: true,
                    autoProceed: true,
                    showProgressDetails: true,
                    restrictions: {
                        maxFileSize: 5000000, // 1mb
                        maxNumberOfFiles: 1,
                        minNumberOfFiles: 1,
                        allowedFileTypes: ['.mp4', '.avi', '.mov', '.wmv', '.amv', '.MP4', '.AVI', '.MOV', '.WMV', '.AMV'],
                    }
                });

                uppyMin.use(FileInput, {
                    target: id + ' .kt-uppy__wrapper',
                    pretty: false
                });
                uppyMin.use(Informer, {
                    target: id + ' .kt-uppy__informer'
                });

                // demo file upload server
                uppyMin.use(XHRUpload, {
                    endpoint: '/course_management/upload_video',
                    method: 'post',
                    formData: true,
                    fieldName: 'files',
                    metaFields: null,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    bundle: false
                });
                uppyMin.use(StatusBar, {
                    target: id + ' .kt-uppy__status',
                    hideUploadButton: true,
                    hideAfterFinish: false
                });

                $(id + ' .uppy-FileInput-input').addClass('kt-uppy__input-control').attr('id', elemId + '_input_control');
                $(id + ' .uppy-FileInput-container').append('<label class="kt-uppy__input-label btn btn-label-brand btn-bold btn-font-sm" for="' + (elemId + '_input_control') + '">Attach files</label>');

                var $fileLabel = $(id + ' .kt-uppy__input-label');

                uppyMin.on('upload', function(data) {
                    $fileLabel.text("Uploading...");
                    $statusBar.addClass('kt-uppy__status--ongoing');
                    $statusBar.removeClass('kt-uppy__status--hidden');
                    clearTimeout(timeout);
                });

                uppyMin.on('complete', function(file) {
                    $.each(file.successful, function(index, value) {

                        var sizeLabel = "bytes";
                        var filesize = value.size;
                        if (filesize > 1024) {
                            filesize = filesize / 1024;
                            sizeLabel = "kb";

                            if (filesize > 1024) {
                                filesize = filesize / 1024;
                                sizeLabel = "MB";
                            }
                        }
                        $("#promotional_video").attr("value", value.response.body.pathname);

                        var uploadListHtml = '<div class="kt-uppy__list-item" data-id="' + value.id + '"><div class="kt-uppy__list-label">' + value.name + ' (' + Math.round(filesize, 2) + ' ' + sizeLabel + ')</div><span class="kt-uppy__list-remove" data-id="' + value.id + '"><i class="flaticon2-cancel-music"></i></span></div>';
                        $uploadedList.append(uploadListHtml);

                    });
                    $.each(file.failed, function(index, value) {
                        var sizeLabel = "bytes";
                        var filesize = value.size;
                        if (filesize > 1024) {
                            filesize = filesize / 1024;
                            sizeLabel = "kb";

                            if (filesize > 1024) {
                                filesize = filesize / 1024;
                                sizeLabel = "MB";
                            }
                        }

                        var uploadListHtml = '<div class="kt-uppy__list-item" data-id="' + value.id + '"><div class="kt-uppy__list-label" style="color:red;"><i class="fa fa-exclamation-circle" style="color:red;height:10px;"></i> &nbsp;' + value.name + ' (' + Math.round(filesize, 2) + ' ' + sizeLabel + ')</div><span class="kt-uppy__list-remove" data-id="' + value.id + '"><i class="flaticon2-cancel-music"></i></span></div>';
                        $uploadedList.append(uploadListHtml);

                    });

                    $fileLabel.text("Add more files");

                    $statusBar.addClass('kt-uppy__status--hidden');
                    $statusBar.removeClass('kt-uppy__status--ongoing');
                });

                $(document).on('click', id + ' .kt-uppy__list .kt-uppy__list-remove', function() {
                    var itemId = $(this).attr('data-id');
                    uppyMin.removeFile(itemId);
                    $(id + ' .kt-uppy__list-item[data-id="' + itemId + '"').remove();
                });
            }

            return {
                // public functions
                init: function() {
                    initUppy5();
                    initUppy5_1();

                }
            };
        }();

        KTUtil.ready(function() {
            KTUppy.init();
        });
    });


    var FormDesign = function() {


        var input_validations = function() {

            validator = $("#form").validate({
                // define validation rules
                rules: {
               
                    objective: {
                        required: true,
                    },
            
                },

                //display error alert on form submit  
                invalidHandler: function(event, validator) {
                    $("#promotional_video_error").remove();
                    $("#course_image_error").remove();
                    var alert = $('#form_msg');
                    alert.removeClass('kt-hidden').show();
                    $.each(validator.invalid, function(index, value) {
                        if (index == "promotional_video") {
                            $("#kt_uppy_5_1").append('<span  id="promotional_video_error" style="color:red;">Promotional Video is required!</span>');
                        }

                        if (index == "course_image") {
                            $("#kt_uppy_5").append('<span id="course_image_error" style="color:red;">Course Image is required!</span>');
                        }
                    });
                    KTUtil.scrollTop();
                },

                submitHandler: function(form) {

                    $("#submit_form").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true).html("Saving...");
                    var objectives = [];
                    var requirements = [];
                    var target_students = [];
                    jQuery('input[name*="objective"]').each(function(e) {
                        objectives.push($(this).val());
                    });
                    jQuery('input[name*="requirements"]').each(function(e) {
                        requirements.push($(this).val());
                    });
                    jQuery('input[name*="target_students"]').each(function(e) {
                        target_students.push($(this).val());
                    }); 
                    var submit_form = $.ajax({
                        url: '/course_management/attract_enrollments/store',
                        type: 'POST',
                        data: {
                            course_image: $('#course_image').val(),
                            promotional_video: $('#promotional_video').val(),
                            objectives: objectives,
                            target_students: target_students,
                            requirements: requirements,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                toastr.success('Success!', response.message);
                                // setTimeout(() => {
                                //     window.location = "/course/management/attract";
                                // }, 1000);
                            } else {
                                $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                                toastr.error('Error!', response.message);
                            }
                        },
                        error: function(response) {
                            $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                            toastr.error('Error!', response.message);
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

  
</script>
@endsection