@extends('template.nosidemenu')
@section('title')
<?= isset($data) ? ($data->title ?? 'New Course') : "New Course" ?>
@endsection
@section('status')
<?= isset($data) ? (ucfirst($data->status) ?? 'Draft') : "Draft" ?>
@endsection
@section('min_video')
<?= isset($min_video) ? $min_video : '0' ?>
@endsection
@section('styles')
@endsection
<!-- <link href="{{asset('plugins/custom/uppy/dist/uppy.min.css')}}" rel="stylesheet" type="text/css" /> -->
<!-- <link rel="stylesheet" href="https://transloadit.edgly.net/releases/uppy/v1.10.1/uppy.min.css"> -->
<style>
    .head {
        font-weight: bold;
        color: black;
    }

    .body {
        font-weight: normal;
        color: black;
    }

    a .kt-notification-v2__item {
        margin-top: -10px;
    }

    .first-div {
        margin-top: 20px;
    }

    .next-div {
        margin-top: 30px;
    }

    .fa-angle-right {
        font-size: 19px;
    }

    .fa-check-circle {
        color: #2A7DE9;
    }

    .small-padding-right {
        padding-right: 5px;
    }

    .required {
        color: red;
    }

    .circle-icon {
        color: #2A7DE9;
    }

    .btn-review {
        background-color: #EA5252;
        color: white;
    }

    .btn-review:hover {
        background-color: #f52222;
    }

    .button {
        border: none;
        padding: 22px 52px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        transition-duration: 0.4s;
        cursor: pointer;
        border-radius: 3px;
    }
    .uppy-ProgressBar-inner{
        height:10px;
    }
</style>

@section('sideNav')
<div class="first-div">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h5 class="kt-portlet__head-title">
                <span class="head">About your course</span>
            </h5>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="kt-notification-v2">
            <a href="/course_management/course_details" class="kt-notification-v2__item ">
                <div class="">
                    <?= Request::segment(2) == 'course_details' ? '<i class="fa fa-angle-right" style="font-size:19px;"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                </div>
                <div class="kt-notification-v2__itek-wrapper">

                    <div class="kt-notification-v2__item-title body">
                        <span class="small-padding-right"><?= $course_details ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                        Course Details
                    </div>

                </div>
            </a>
            <a href="/course_management/attract_enrollments" class="kt-notification-v2__item">
                <div class="">
                    <?= Request::segment(2) == 'attract_enrollments' ? '<i class="fa fa-angle-right"></i>' : '<i class="la la-circle circle-icon" style="color:white;"></i>' ?>
                </div>
                <div class="kt-notification-v2__itek-wrapper">

                    <div class="kt-notification-v2__item-title body">
                        <span class="small-padding-right"><?= $attract_enrollments ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                        Attract Enrollments
                    </div>

                </div>
            </a>
            <a href="/course_management/instructors" class="kt-notification-v2__item">
                <div class="">
                    <?= Request::segment(2) == 'instructors' ? '<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                </div>
                <div class="kt-notification-v2__itek-wrapper">

                    <div class="kt-notification-v2__item-title body">
                        <span class="small-padding-right"><?= $instructors_permission ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                        Instructors
                    </div>

                </div>
            </a>
        </div>
    </div>
</div>
<div class="next-div">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h5 class="kt-portlet__head-title">
                <span class="head">Create your content</span>
            </h5>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="kt-notification-v2">
            <a href="/course_management/video_and_content" class="kt-notification-v2__item">
                <div class="">
                    <?= Request::segment(2) == 'video_and_content' ? '<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                </div>
                <div class="kt-notification-v2__itek-wrapper">

                    <div class="kt-notification-v2__item-title body">
                        <span class="small-padding-right"><?= $video_and_content ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                        Video & Content
                    </div>

                </div>
            </a>
            <a href="/course_management/handouts" class="kt-notification-v2__item">
                <div class="">
                    <?= Request::segment(2) == 'handouts' ? '<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                </div>
                <div class="kt-notification-v2__itek-wrapper">

                    <div class="kt-notification-v2__item-title body">
                        <span class="small-padding-right"><?= $handouts ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                        Handouts
                    </div>

                </div>
            </a>
            <a href="/course_management/grading_and_assessment" class="kt-notification-v2__item">
                <div class="">
                    <?= Request::segment(2) == 'grading_and_assessment' ? '<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                </div>
                <div class="kt-notification-v2__itek-wrapper">

                    <div class="kt-notification-v2__item-title body">
                        <span class="small-padding-right"><?= $grading_and_assessment ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                        Grading & Assessment
                    </div>

                </div>
            </a>
        </div>
    </div>
</div>
<div class="next-div">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h5 class="kt-portlet__head-title">
                <span class="head">Publishing your course</span>
            </h5>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="kt-notification-v2">
            <a href="/course_management/submit_for_accreditation" class="kt-notification-v2__item">
                <div class="">
                    <?= Request::segment(2) == 'submit_for_accreditation' ? '<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                </div>
                <div class="kt-notification-v2__itek-wrapper">

                    <div class="kt-notification-v2__item-title body">
                        <span class="small-padding-right"><?= $submit_for_accreditation ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                        Submit for Accreditation
                    </div>

                </div>
            </a>
            <a href="/course_management/price_and_publish" class="kt-notification-v2__item">
                <div class="">
                    <?= Request::segment(2) == 'price_and_publish' ? '<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                </div>
                <div class="kt-notification-v2__itek-wrapper">

                    <div class="kt-notification-v2__item-title body">
                        <span class="small-padding-right"><?= $price_and_publish ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                        Price & Publish
                    </div>

                </div>
            </a>
        </div>
    </div>
</div>
<div class="next-div">
    <button id="submit_review" class="button btn-review ">
        Submit for Review
    </button>
</div>

@endsection
@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Video & Content <small>portlet sub title</small>
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-toolbar">
                    <ul class="nav nav-pills nav-pills-sm " id="ul_list" role="tablist">

                        <li class="nav-item">
                            <a class="nav-link" id="add_section" style="cursor: pointer;" role="tab">
                                Add Section
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link section_link active" id="1" data-toggle="tab" href="#kt_tabs_7_1" role="tab">
                                Section 1
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="tab-content" id="tab_content">
                    <div class="tab-pane active" id="kt_tabs_7_1" role="tabpanel">

                        <div class="kt-portlet__body">
                            <form class="kt-form kt-form--label-left" id="user_add_content_form_1">
                                {{csrf_field()}}
                                <div class="kt-form__content">
                                    <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="user_add_form_msg_1">
                                        <div class="kt-alert__icon">
                                            <i class="fa fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="kt-alert__text">Sorry! You have to complete the form requirements first!</div>
                                        <div class="kt-alert__close">
                                            <button type="button" class="close" data-close="alert" aria-label="Close">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Section Name <span class="required">*<span></label>
                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                        <div class="kt-typeahead">
                                            <input class="form-control" type="text" name="section_name" id="section_name_1" placeholder="Introduction">
                                        </div>
                                        <span class="form-text text-muted">The maximum characters allowed is 80 </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Objective <span class="required">*<span></label>
                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                        <div class="kt-typeahead">
                                            <input class="form-control" type="text" name="objective" id="objective_1" placeholder="Objectives">
                                        </div>
                                        <span class="form-text text-muted">The maximum characters allowed is 200 </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" style="text-align:center;">
                                        <button id="submit_content_form_1" class="btn btn-success">Save Section</button>
                                        <button type="reset" class="btn btn-secondary">Clear</button>
                                    </div>
                                </div>
                            </form>
                            <div class="kt-portlet__head-toolbar" id="selection_tab_1">
                                <ul class="nav nav-pills nav-pills-sm " id="ul_list_1" role="tablist">
                                    <li class="nav-items">
                                        <a class="nav-link section_links" data-toggle="tab" href="#video_content_1" id="video_tab_1" role="tab">
                                            Video
                                        </a>
                                    </li>
                                    <li class="nav-items">
                                        <a class="nav-link section_links" data-toggle="tab" href="#article_content_1" id="article_tab_1" role="tab">
                                            Article
                                        </a>
                                    </li>
                                    <li class="nav-items">
                                        <a class="nav-link section_links" data-toggle="tab" href="#quiz_content_1" id="quiz_tab_1" role="tab">
                                            Quiz
                                        </a>
                                    </li>

                                </ul>
                            </div>
                            <div class="tab-content" id="tab_content_1">
                                <!--  -->
                                <div class="tab-pane" id="article_content_1" role="tabpanel">

                                    <form class="kt-form kt-form--label-left" id="user_add_article_title_form_1">
                                        {{csrf_field()}}
                                        <div class="kt-form__content">
                                            <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="user_add_form_msg">
                                                <div class="kt-alert__icon">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                </div>
                                                <div class="kt-alert__text">Sorry! You have to complete the form requirements first!</div>
                                                <div class="kt-alert__close">
                                                    <button type="button" class="close" data-close="alert" aria-label="Close">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-3 col-sm-12">Article Title <span class="required">*<span></label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <div class="kt-typeahead">
                                                    <input class="form-control" type="text" name="article" id="article_1" placeholder="Title">
                                                </div>
                                                <span class="form-text text-muted">The maximum characters allowed is 80 </span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" id="btn_article_form_1" style="text-align:center;">
                                                <button id="submit_article_form_1" class="btn btn-success">Save Article</button>
                                                <button type="reset" class="btn btn-secondary">Clear</button>
                                            </div>
                                        </div>

                                    </form>
                                    <form class="kt-form kt-form--label-left" id="user_add_article_body_form_1">
                                        {{csrf_field()}}
                                        <div class="kt-form__content">
                                            <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="user_add_form_msg">
                                                <div class="kt-alert__icon">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                </div>
                                                <div class="kt-alert__text">Sorry! You have to complete the form requirements first!</div>
                                                <div class="kt-alert__close">
                                                    <button type="button" class="close" data-close="alert" aria-label="Close">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 about_group_" id="about_group_1">
                                                <div class="form-group">
                                                    <label>Article Title: <span id="article_label_title_1"></span></label>
                                                    <input type="hidden" name="article_id" id="article_id_1" />
                                                    <div id="textarea_about_1" style="display:none;">
                                                        <textarea class="form-control" minlength="50" id="about_1" name="about">{{isset($data) ? "" : ''}}</textarea>
                                                    </div>
                                                    <div id="div_about_1" class="box">
                                                        <?= isset($data) ? "" : "" ?>
                                                    </div>
                                                    <span class="form-text text-muted">Please provide the your article.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" id="btn_article_body_form_1" style="text-align:center;">
                                                <button id="submit_article_body_form_1" class="btn btn-success">Save Article</button>
                                                <button type="reset" class="btn btn-secondary">Clear</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                <div class="tab-pane" id="quiz_content_1" role="tabpanel">

                                    <form class="kt-form kt-form--label-left" id="user_add_quiz_form_1">
                                        <div>This is Quiz</div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="video_content_1" role="tabpanel">
                                    <form class="kt-form kt-form--label-left" id="user_add_video_form_1">
                                        {{csrf_field()}}
                                        <div class="form-group form-group-last row" id="kt_repeater_1">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div id="add_video_parent_1">
                                                    <a data-repeater-create class="btn btn-bold btn-sm btn-label-brand create_video" id="create_video_1">
                                                        <i style="text-align:right;" class="fa fa-plus-circle"></i>
                                                    </a>
                                                </div>
                                                <div id="kt_repeater_1">
                                                    <div class="accordion accordion-solid  accordion-svg-icon" data-repeater-list id="accordionExample_1_1">

                                                        <div class="card clone_identification_1" id="clone_board_1_1">
                                                            <div class="card-header" style="background-color:white;color:red;" id="headingOne_1_1">
                                                                <div class="card-title collapsy" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_vid_1_1" aria-expanded="true" aria-controls="collapse_vid_1_1">
                                                                    <div style="float:left;width:40%;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                            </g>
                                                                        </svg> <span id="video_label_1_1">Sample Video</span>
                                                                        <input class="focus input_video_1 name_of_video_1_1" hidden type="text" name="video_name_1_1" id="1" placeholder="Video Title">
                                                                        <a href="javascript:;" id="1" class="btn-sm btn btn-label-default btn-bold edit_video_title_con_1">
                                                                            <i style="text-align:right;" class="fa fa-edit"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div style="text-align:right;float:right;width:55%;">
                                                                        <a href="javascript:;" id="remove_video" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                            <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                                        </a>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="collapse_vid_1_1" class="collapse show" aria-labelledby="headingOne_1_1" data-parent="#accordionExample_1_1">
                                                                <div class="card-body">
                                                                    <div class="kt-uppy video_identification_1" id="kt_uppy_1_3">
                                                                        <div class="kt-uppy__drag"></div>
                                                                        <div class="kt-uppy__informer remove_double_i_1"></div>
                                                                        <div class="kt-uppy__progress remove_double_p_1"></div>
                                                                        <div class="kt-uppy__thumbnails"></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="card clone_identification_1" hidden id="clone_board_hidden_1">
                                                            <div class="card-header" style="background-color:white;color:red;" id="headingOne_1_1">
                                                                <div class="card-title collapsy" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_vid_1_1" aria-expanded="true" aria-controls="collapse_vid_1_1">
                                                                    <div style="float:left;width:40%;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                            </g>
                                                                        </svg> <span id="video_label_1_1">Sample Video</span>
                                                                        <input class="focus input_video_1 name_of_video_1_1" hidden type="text" name="video_name_1_1" id="1" placeholder="Video Title">
                                                                        <a href="javascript:;" id="1" class="btn-sm btn btn-label-default btn-bold edit_video_title_con_1">
                                                                            <i style="text-align:right;" class="fa fa-edit"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div style="text-align:right;float:right;width:55%;">
                                                                        <a href="javascript:;" id="remove_video" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                            <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                                        </a>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="collapse_vid_1_1" class="collapse show" aria-labelledby="headingOne_1_1" data-parent="#accordionExample_1_1">
                                                                <div class="card-body">
                                                                    <div class="kt-uppy " id="kt_uppy_1_3_hidden">
                                                                        <div class="kt-uppy__drag"></div>
                                                                        <div class="kt-uppy__informer remove_double_i_1"></div>
                                                                        <div class="kt-uppy__progress remove_double_p_1"></div>
                                                                        <div class="kt-uppy__thumbnails"></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <input type="hidden" name="video[]" id="video_cdn_1" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="section_id" id="section_id_1" />
                                        <div class="row" style="margin-top:20px;">
                                            <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" style="text-align:center;">
                                                <button id="submit_video_form_1" class="btn btn-success">Save Video</button>
                                                <button type="reset" class="btn btn-secondary">Clear</button>
                                            </div>
                                        </div>



                                    </form>
                                </div>
                            </div>


                        </div>


                    </div>
                    <div class="tab-pane" id="hidden_tab_body" role="tabpanel">

                        <div class="kt-portlet__body">
                            <form class="kt-form kt-form--label-left" id="user_add_content_hidden_form">
                                {{csrf_field()}}
                                <div class="kt-form__content">
                                    <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="user_add_form_msg">
                                        <div class="kt-alert__icon">
                                            <i class="fa fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="kt-alert__text">Sorry! You have to complete the form requirements first!</div>
                                        <div class="kt-alert__close">
                                            <button type="button" class="close" data-close="alert" aria-label="Close">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Section Name <span class="required">*<span></label>
                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                        <div class="kt-typeahead">
                                            <input class="form-control" type="text" name="section_name" id="section_name_hidden" placeholder="Introduction">
                                        </div>
                                        <span class="form-text text-muted">The maximum characters allowed is 80 </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Objective <span class="required">*<span></label>
                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                        <div class="kt-typeahead">
                                            <input class="form-control name_of_vid_hidden" type="text" name="objective" id="objective_hidden" placeholder="Objectives">
                                        </div>
                                        <span class="form-text text-muted">The maximum characters allowed is 200 </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" id="btn_section_form_hidden" style="text-align:center;">
                                        <button id="submit_section_form_1" class="btn btn-success">Save Section</button>
                                        <button type="reset" class="btn btn-secondary">Clear</button>
                                    </div>
                                </div>
                            </form>
                            <div class="kt-portlet__head-toolbar" id="selection_tab_hidden">
                                <ul class="nav nav-pills nav-pills-sm " id="ul_list_hidden" role="tablist">
                                    <li class="nav-items">
                                        <a class="nav-link section_links" data-toggle="tab" href="#video_content_1" id="video_tab_hidden" role="tab">
                                            Video
                                        </a>
                                    </li>
                                    <li class="nav-items">
                                        <a class="nav-link section_links" data-toggle="tab" href="#article_content_1" id="article_tab_hidden" role="tab">
                                            Article
                                        </a>
                                    </li>
                                    <li class="nav-items">
                                        <a class="nav-link section_links" data-toggle="tab" href="#quiz_content_1" id="quiz_tab_hidden" role="tab">
                                            Quiz
                                        </a>
                                    </li>

                                </ul>
                            </div>
                            <div class="tab-content" id="tab_content_hidden">
                                <!--  -->
                                <div class="tab-pane" id="article_content_hidden" role="tabpanel">

                                    <form class="kt-form kt-form--label-left" id="user_add_article_title_form_hidden">
                                        {{csrf_field()}}
                                        <div class="kt-form__content">
                                            <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="user_add_form_msg">
                                                <div class="kt-alert__icon">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                </div>
                                                <div class="kt-alert__text">Sorry! You have to complete the form requirements first!</div>
                                                <div class="kt-alert__close">
                                                    <button type="button" class="close" data-close="alert" aria-label="Close">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-3 col-sm-12">Article Title <span class="required">*<span></label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <div class="kt-typeahead">
                                                    <input class="form-control" type="text" name="article" id="article_hidden" placeholder="Title">
                                                </div>
                                                <span class="form-text text-muted">The maximum characters allowed is 80 </span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" id="btn_article_form_hidden" style="text-align:center;">
                                                <button id="submit_article_form_hidden" class="btn btn-success">Save Article</button>
                                                <button type="reset" class="btn btn-secondary">Clear</button>
                                            </div>
                                        </div>

                                    </form>
                                    <form class="kt-form kt-form--label-left" id="user_add_article_body_form_hidden">
                                        {{csrf_field()}}
                                        <div class="kt-form__content">
                                            <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="user_add_form_msg">
                                                <div class="kt-alert__icon">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                </div>
                                                <div class="kt-alert__text">Sorry! You have to complete the form requirements first!</div>
                                                <div class="kt-alert__close">
                                                    <button type="button" class="close" data-close="alert" aria-label="Close">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 about_group_" id="about_group_hidden">
                                                <div class="form-group">
                                                    <label>Article Title: <span id="article_label_title_hidden"></span></label>
                                                    <input type="hidden" name="article_id" id="article_id_hidden" />
                                                    <div id="textarea_about_hidden" style="display:none;">
                                                        <textarea class="form-control" minlength="50" id="about_hidden" name="about">{{isset($data) ? "" : ''}}</textarea>
                                                    </div>
                                                    <div id="div_about_hidden" class="box">
                                                        <?= isset($data) ? "" : "" ?>
                                                    </div>
                                                    <span class="form-text text-muted">Please provide the your article.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" id="btn_article_body_form_hidden" style="text-align:center;">
                                                <button id="submit_article_body_form_hidden" class="btn btn-success">Save Article</button>
                                                <button type="reset" class="btn btn-secondary">Clear</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                <div class="tab-pane" id="quiz_content_hidden" role="tabpanel">

                                    <form class="kt-form kt-form--label-left" id="user_add_quiz_form_hidden">
                                        <div>This is Quiz</div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="video_content_hidden" role="tabpanel">
                                    <form class="kt-form kt-form--label-left" id="user_add_video_hidden_form">
                                        <div class="form-group form-group-last row" id="kt_repeater_1">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div id="hidden_vid">
                                                    <a data-repeater-create class="btn btn-bold btn-sm btn-label-brand create_video" id="create_video_hidden">
                                                        <i style="text-align:right;" class="fa fa-plus-circle"></i>
                                                    </a>
                                                </div>
                                                <div id="kt_repeater_hidden">
                                                    <div class="accordion accordion-solid  accordion-svg-icon" data-repeater-list id="accordionExample">
                                                        <div class="card clone_identification" id="clone_board">
                                                            <div class="card-header" style="background-color:white;color:red;" id="headingOne7">
                                                                <div class="card-title collapsy" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapseOne7" aria-expanded="true" aria-controls="collapseOne7">
                                                                    <div style="float:left;width:40%;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                            </g>
                                                                        </svg> <span id="video_label_hidden">Sample Video</span>
                                                                        <input class="focus name_of_video_hidden" hidden type="text" name="video_name_hidden" id="1" placeholder="Video Title">
                                                                        <a href="javascript:;" id="1" class="btn-sm btn btn-label-default btn-bold edit_video_title_con_1">
                                                                            <i style="text-align:right;" class="fa fa-edit"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div style="text-align:right;float:right;width:55%;">
                                                                        <a href="javascript:;" id="remove_video" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                            <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                                        </a>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="collapseOne7" class="collapse show" aria-labelledby="headingOne7" data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <div class="kt-uppy video_identification" id="kt_uppy_3">
                                                                        <div class="kt-uppy__drag"></div>
                                                                        <div class="kt-uppy__informer remove_double_i"></div>
                                                                        <div class="kt-uppy__progress remove_double_p"></div>
                                                                        <div class="kt-uppy__thumbnails"></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="hidden_clone" hidden id="clone_board_hidden">
                                                            <div class="card-header" style="background-color:white;color:red;" id="headingOne7">
                                                                <div class="card-title collapsy" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapseOne7" aria-expanded="true" aria-controls="collapseOne7">
                                                                    <div style="float:left;width:40%;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                            </g>
                                                                        </svg> <span id="video_label_hidden">Sample Video</span>
                                                                        <input class="focus name_of_video_hidden" hidden type="text" name="video_name_hidden" id="1" placeholder="Video Title">
                                                                        <a href="javascript:;" id="1" class="btn-sm btn btn-label-default btn-bold edit_video_title_con_1">
                                                                            <i style="text-align:right;" class="fa fa-edit"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div style="text-align:right;float:right;width:55%;">
                                                                        <a href="javascript:;" id="remove_video" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                            <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                                        </a>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="collapseOne7" class="collapse show" aria-labelledby="headingOne7" data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <div class="kt-uppy video_identification" id="kt_uppy_1_3_hidden">
                                                                        <div class="kt-uppy__drag"></div>
                                                                        <div class="kt-uppy__informer remove_double_i"></div>
                                                                        <div class="kt-uppy__progress remove_double_p"></div>
                                                                        <div class="kt-uppy__thumbnails"></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <input type="hidden" name="video[]" id="video_cdn_hidden" />
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="section_id" id="section_id_hidden" />
                                        <div class="row" style="margin-top:20px;">
                                            <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" id="btn_video_form_hidden" style="text-align:center;">
                                                <button id="submit_video_form_hidden" class="btn btn-success">Save Section</button>
                                                <button type="reset" class="btn btn-secondary">Clear</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>


                        </div>

                        <div class="kt-portlet__foot">

                        </div>



                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection



@section('scripts')
<!-- <script src="{{asset('plugins/general/jquery/dist/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/pages/crud/forms/widgets/bootstrap-maxlength.js')}}" type="text/javascript"></script> -->
<!-- <script src="{{asset('plugins/custom/uppy/dist/uppy.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/pages/crud/file-upload/uppy.js')}}" type="text/javascript"></script> -->
<!-- <script src="https://transloadit.edgly.net/releases/uppy/v1.10.1/uppy.min.js"></script>
<script src="https://transloadit.edgly.net/releases/uppy/locales/v1.11.5/ru_RU.min.js"></script> -->

<script src="{{asset('js/course-management/section-clone.js')}}" type="text/javascript"></script>
<script src="{{asset('js/course-management/part-clone.js')}}" type="text/javascript"></script>
<script src="{{asset('js/course-management/form-design.js')}}" type="text/javascript"></script>
<script src="{{asset('js/course-management/upload_video_uppy.js')}}" type="text/javascript"></script>

<script>
    var toolbar_show = [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
    ];
    var global_video_id = 'kt_uppy_1_3';
    var section_id = "1";
    var create_video_id = "create_video_1";
    var id_edit_video_name = "name_of_video_1_1";
    var video_array = [];
    $(document).ready(function() {
        $("#submit_review").on("click", function() {
            $.ajax({
                url: '/course_management/api/submit_review',
                method: 'get',
                success: function(response) {
                    if (response.status == 200) {
                        toastr.success('Success!', response.message + '. Thank you!');
                    } else {
                        toastr.warning('Course Incomplete!', "List of Incomplete Details: " + response.message + ', please complete it first. Thank you!');
                    }
                }
            });
        });


        $('#ul_list').on("click", ".section_link", function() {
            var global = $(this).attr("id");
            global_video_id = "kt_uppy_" + global + "_3";
            section_id = global;
            create_video_id = "create_video_" + global;
            FormDesignContent.init();
            FormDesignVideo.init();
            FormDesignArticle.init();
            FormDesignArticleBody.init();
            $('textarea#about_' + global).summernote({
                height: 80,
                toolbar: toolbar_show,
            });

            $(document).on('click', function(e) {
                if ($(e.target).closest("#about_group_" + global).length === 0) {
                    $("#div_about_" + global).html($.trim($("#about_" + global).val())).show();
                    $("#textarea_about_" + global).hide();
                }
            });
            $("#about_group_" + global).click(function() {
                $("#textarea_about_" + global).show();
                $("#div_about_" + global).hide();
            });



        });
        //Summernote


        $("#tab_content").on('click', "a.create_video", function() {
            //     var $div = $('div[id^="kt_uppy_5"]:last');
            //     var num = parseInt($div.prop("id").match(/\d+/g),10) + 1;
            //     var $clone_board = $('div[id^="clone_board"]:last');
            //     var clone_num = parseInt($clone_board.prop("id").match(/\d+/g),10) + 1;

            //     console.log($div);
            // //   $('#clone_board').clone().appendTo("#accordionExample7");
            // $clone_board.clone().prop('id','clone_board'+clone_num).appendTo("#accordionExample7");
            // $div.prop('id',"kt_uppy_5"+num);
            //   console.log($clone_board);
            var id = section_id;
            var getCountClone = $('.clone_identification_' + id).length + 1;
            var getCountVideo = $(".video_identification_" + id).length + 1;

          
            var count_vid = getCountVideo;

            part_clone(section_id, id, getCountClone, getCountVideo, count_vid);
           
           
                KTUppy.init();
      



        });


        $('#add_section').click(function() {
            var li_count = $('.nav-item').length;
            $("#ul_list").append(' <li class="nav-item hidden_tab"><a class="nav-link section_link" data-toggle="tab" id="' + li_count + '" href="#kt_tabs_7_' + li_count + '" role="tab">Section ' + li_count + ' </a></li>');

            add_section(li_count);

            global_video_id = 'kt_uppy_' + li_count + '_3';
           
                KTUppy.init();
         

        });

        $("#tab_content").on('click', "a.remove_video_con_1", function() {
            $(this).closest(".clone_identification_" + section_id).remove();
        });
        $("#tab_content").on('click', "a.edit_video_title_con_1", function() {
            var id = $(this).attr("id");

            $(".name_of_video_" + section_id + "_" + id).removeAttr("hidden");
            $(".name_of_video_" + section_id + "_" + id).focus();
            $("span#video_label_" + section_id + "_" + id).attr("hidden", true);
            $(this).attr("hidden", true);
            $("a.save_video_title_con_1").removeAttr("hidden");
            id_edit_video_name = id;
        });

        $("#tab_content").on("focusout", "input.focus", function() {
            var inputValue = $(this).val();
            if (inputValue == "" || inputValue == null) {
                inputValue = "Sample Video Name";
                // $(this).val();
            }
            $(this).attr("hidden", true);
            $("span#video_label_" + section_id + "_" + id_edit_video_name).html(inputValue);
            $("span#video_label_" + section_id + "_" + id_edit_video_name).attr("hidden", false);
            $("a.edit_video_title_con_1").attr("hidden", false);



        });

        $('textarea#about_' + section_id).summernote({
            height: 80,
            toolbar: toolbar_show,
        });

        $(document).on('click', function(e) {
            if ($(e.target).closest("#about_group_" + section_id).length === 0) {
                $("#div_about_" + section_id).html($.trim($("#about_" + section_id).val())).show();
                $("#textarea_about_" + section_id).hide();
            }
        });
        $("#about_group_" + section_id).click(function() {
            $("#textarea_about_" + section_id).show();
            $("#div_about_" + section_id).hide();
        });
        $('form').each(function() {
            if ($(this).data('validator'))
                $(this).data('validator').settings.ignore = ".note-editor *";
        });


        FormDesignContent.init();
        FormDesignVideo.init();
        FormDesignArticle.init();
        FormDesignArticleBody.init();


        // Class definition


     
            // KTUppy.init();
     
        $('form').each(function() {
            if ($(this).data('validator'))
                $(this).data('validator').settings.ignore = ".note-editor *";
        });

        $('.edit_video_title_con').click(function() {
            alert($(this).attr("id"));
        });

    });


</script>
@endsection