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
    span.form-control{color:#8a9198;border:none;padding:inherit;height:auto;}
    .head {font-weight: bold;color: black;}
    .body {font-weight: normal;color: black;}
    a .kt-notification-v2__item {margin-top: -10px;}
    .first-div {margin-top: 20px;}
    .next-div {margin-top: 30px;}
    .fa-angle-right {font-size: 19px;}
    .fa-check-circle {color: #2A7DE9;}
    .small-padding-right {padding-right: 5px;}
    .required {color: red;}
    .circle-icon {color: #2A7DE9;}
    .btn-review {background-color: #EA5252;color: white;}
    .btn-review:hover {background-color: #f52222;}
    .button {border: none;padding: 22px 52px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;margin: 4px 2px;transition-duration: 0.4s;cursor: pointer;border-radius: 3px;}
    .article_padding {padding: 25px;}
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
        <!-- Start of the main body -->
        <div class="kt-portlet">
            <!-- Section Body -->
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-toolbar">
                    <ul class="nav nav-pills nav-pills-sm " id="ul_list" role="tablist">

                        <li class="nav-item">
                            <a class="nav-link" id="add_section" style="cursor: pointer;" role="tab">
                                Add Section
                            </a>
                        </li>
                        @if(count($sections) == 0)
                        <li class="nav-item">
                            <a class="nav-link section_link active" id="1" data-toggle="tab" href="#kt_tabs_7-1" role="tab">
                                Section 1
                            </a>
                        </li>
                        @endif
                        @if(count($sections) > 0)
                        @foreach($sections as $key => $section)
                        <li class="nav-item">
                            <a class="nav-link section_link <?= $key == 0 ? 'active' : '' ?>" id="<?= $section->section_number ?>" data-toggle="tab" href="#kt_tabs_7-<?= $section->section_number ?>" role="tab">
                                Section <?= $section->section_number ?>
                            </a>
                        </li>
                        @endforeach
                        @endif

                    </ul>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="tab-content" id="tab_content">
                    <!-- Main Section -->
                    <!-- Pag walang data sections na ire-retrieve eto yung mag lo-load -->
                    @if(count($sections) == 0) 
                    <div class="tab-pane active" id="kt_tabs-7_1" role="tabpanel">
                        <div class="kt-portlet__body" id="section-1">
                            <!-- Section Form -->
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
                                        <div class="kt-typeahead section_name_div-1">
                                            <input class="form-control" type="text" name="section_name" style="display:none;" id="section_name-1" placeholder="Introduction">
                                            <span class="form-control section_name" id="section_name_label-1">Introduction</span>
                                        </div>
                                        <span class="form-text text-muted section_name_muted-1" style="display:none;">The maximum characters allowed is 80 </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Objective <span class="required">*<span></label>
                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                        <div class="kt-typeahead section_objective_div-1">
                                            <input class="form-control" type="text" name="objective" style="display:none;" id="objective-1" placeholder="Objectives">
                                            <span class="form-control section_objective" id="section_objective_label-1">Objective</span>
                                        </div>
                                        <span class="form-text text-muted section_objective_muted-1" style="display:none;">The maximum characters allowed is 200 </span>
                                    </div>
                                </div>
                                <input type="hidden" name="section_id" id="section_id_1" />
                                <div class="form-group row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" style="text-align:center;">
                                        <button id="submit_content_form-1" class="btn btn-success submit_section">Save Section</button>
                                        <button type="reset" class="btn btn-secondary">Clear</button>
                                    </div>
                                </div>
                            </form>
                            <!-- End Section Form -->
                            <!-- Video Article Quiz Content -->
                            <div id="part_clone-1_1" class="part_count-1 part" style="display:none;">
                                <div class="kt-portlet__head-toolbar selection" id="selection_tab-1_1">
                                    <ul class="nav nav-pills nav-pills-sm section_content" id="ul_list-1_1" role="tablist">
                                        <li class="nav-items">
                                            <div id="add_video_parent-1_1">
                                                <a data-repeater-create class="btn btn-bold btn-sm btn-label-brand create_video" id="create_video-1_1">
                                                    <i style="text-align:right;" class="fa fa-plus-circle"></i>
                                                </a>
                                            </div>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#video_content-1_1" id="video_tab-1_1" role="tab">
                                                Video
                                            </a>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#article_content-1_1" id="article_tab-1_1" role="tab">
                                                Article
                                            </a>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#quiz_content-1_1" id="quiz_tab-1_1" role="tab">
                                                Quiz
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                                <div class="tab-content" id="tab_content-1_1">
                                    <!-- Article tab -->
                                    <div class="tab-pane" id="article_content-1_1" role="tabpanel">
                                        <div class="accordion accordion-solid  accordion-svg-icon" id="accordionArticle-1_1">
                                            <div class="card">
                                                <div class="card-header accordion_header_part" style="background-color:white;color:red;" id="headingArticle-1_1">
                                                    <div class="card-title collapsy_article" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_article-1_1" aria-expanded="true" aria-controls="collapse_article-1_1">
                                                        <div style="float:left;width:40%;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                </g>
                                                            </svg> <span id="article_label-1_1">Sample Article</span>
                                                        </div>
                                                        <div style="text-align:right;float:right;width:55%;" id="remove_content_part_article_div-1_1" class="remove_content_part_div">
                                                            <a href="javascript:;" id="removePart_article-1_1" style="display:none;" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="collapse_article-1_1" class="collapse show" aria-labelledby="headingArticle-1_1" data-parent="#accordionArticle-1_1">
                                                    <div class="card-body">
                                                        <form class="kt-form kt-form--label-left" id="user_add_article_title_form-1_1">
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
                                                                    <div class="kt-typeahead article_title_div-1_1">
                                                                        <input class="form-control" type="text" name="article" id="article-1_1" style="display:none;" placeholder="Title">
                                                                        <span class="form-control article_title_span" id="article_input-1_1">Title</span>
                                                                    </div>
                                                                    <span class="form-text text-muted">The maximum characters allowed is 80 </span>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="article_id" id="article_id-1_1" />
                                                            <div class="form-group row">
                                                                <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" id="btn_article_form-1_1" style="text-align:center;">
                                                                    <button id="submit_article_form-1_1" class="btn btn-success submit_article_title">Save Title</button>
                                                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                                                </div>
                                                            </div>

                                                        </form>
                                                        <form class="kt-form kt-form--label-left" id="user_add_article_body_form-1_1">
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
                                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 about_group_" id="about_group-1_1">
                                                                    <div class="form-group">
                                                                        <label>Article Title: <span id="article_label_title-1_1"></span></label>

                                                                        <div id="textarea_about-1_1" class="textarea" style="display:none;">
                                                                            <textarea class="form-control" minlength="50" id="about-1_1" name="about">{{isset($data) ? "" : ''}}</textarea>
                                                                        </div>
                                                                        <div id="div_about-1_1" class="box">
                                                                            <?= isset($data) ? "" : "" ?>
                                                                        </div>
                                                                        <span class="form-text text-muted">Please provide the your article.</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" id="btn_article_body_form-1_1" style="text-align:center;">
                                                                    <button id="submit_article_body_form-1_1" class="btn btn-success submit_article_body">Save Article</button>
                                                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Article Tab -->
                                    <!-- Video Tab -->
                                    <div class="tab-pane" id="video_content-1_1" role="tabpanel">
                                        <form class="kt-form kt-form--label-left" id="user_add_video_form-1_1">
                                            {{csrf_field()}}
                                            <div class="form-group form-group-last row" id="kt_repeater-1">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div id="kt_repeater-1">
                                                        <div class="accordion accordion-solid  accordion-svg-icon" data-repeater-list id="accordionVideo-1_1">

                                                            <div class="card clone_identification-1" id="clone_board-1_1">
                                                                <div class="card-header accordion_header_part" style="background-color:white;color:red;" id="headingVideo-1_1">
                                                                    <div class="card-title collapsy" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_video-1_1" aria-expanded="true" aria-controls="collapse_video-1_1">
                                                                        <div style="float:left;width:40%;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                                </g>
                                                                            </svg> <span id="video_label-1_1">Sample Video</span>
                                                                        </div>
                                                                        <div style="text-align:right;float:right;width:55%;" id="remove_content_part_video_div-1_1" class="remove_content_part_div">
                                                                            <a href="javascript:;" id="removePart_video-1_1" style="display:none;" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                                <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                                            </a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="collapse_video-1_1" class="collapse show" aria-labelledby="headingOne-1_1" data-parent="#accordionVideo-1_1">
                                                                    <div class="card-body">
                                                                        <div class="form-group row" style="padding-top:10px; padding-bottom:10px;">
                                                                            <label class="col-form-label col-lg-3 col-sm-12">Video Title <span class="required">*<span></label>
                                                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                <div class="kt-typeahead video_title_div-1_1">
                                                                                    <input class="form-control" type="text" name="video_name" id="video-1_1" value="" style="display:none;" placeholder="Video Title">
                                                                                    <span class="form-control video_title_span" id="video_input-1_1">Video Title</span>
                                                                                </div>
                                                                                <span class="form-text text-muted">The maximum characters allowed is 80 </span>

                                                                            </div>
                                                                            <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12 col-xs-12" id="video_div_retrieve-1_1" style="display:none;">
                                                                                <div style="float:right;">
                                                                                    <button type="button" class="btn btn-outline-warning btn-sm btn_retrieve_video" id="btn_retrieve_video-1_1">
                                                                                        <i class="fa fa-retweet"></i> Retrieve Video
                                                                                    </button>&nbsp;
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="kt-uppy video_identification-1" id="kt_uppy-1_1">
                                                                            <div class="kt-uppy__drag"></div>
                                                                            <div class="kt-uppy__informer remove_double_i_1"></div>
                                                                            <div class="kt-uppy__progress remove_double_p_1"></div>
                                                                            <div class="kt-uppy__thumbnails"></div>
                                                                        </div>
                                                                        <div id="video_div_remove-1_1" style="display:none;text-align:center;">
                                                                            <span class="col-lg-9 col-xl-9 col-md-9" id="video_exist-1_1">Video Title</span><button type="button" class="btn btn-label-google btn-sm btn_remove_video" id="btn_remove_video-1_1"><i class="fa fa-video-slash"></i> Remove Video</button>&nbsp;
                                                                        </div>

                                                                        <input type="hidden" name="video" id="video_cdn-1_1" value="" />
                                                                        <input type="hidden" name="video_filename" id="video_filename-1_1" value="" />
                                                                        <input type="hidden" name="video_size" id="video_size-1_1" value="" />
                                                                        <input type="hidden" name="video_length" id="video_length-1_1" value="" />
                                                                        <input type="hidden" name="video_poster" id="video_poster-1_1" value="" />
                                                                        <input type="hidden" name="video_thumbnail" id="video_thumbnail-1_1" value="" />
                                                                        <input type="hidden" name="video_resolution" id="video_resolution-1_1" value="" />
                                                                        <input type="hidden" name="video_id" id="video_id-1_1" value="" />
                                                                        <div class="row" style="margin-top:20px;">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" style="text-align:center;">
                                                                                <button id="submit_video_form-1_1" class="btn btn-success submit_video">Save Video</button>
                                                                                <button type="reset" class="btn btn-secondary">Clear</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Video Tab -->
                            </div>
                            <!-- Hidden Part For Cloning -->
                            <div id="part_clone_hidden" style="display:none;margin-top:20px;">
                                <div class="part_count_hidden" id="selection_tab_hidden">
                                    <ul class="nav nav-pills nav-pills-sm section_content" id="ul_list_hidden" role="tablist">
                                        <li class="nav-items">
                                            <div id="add_video_parent_hidden">
                                                <a data-repeater-create class="btn btn-bold btn-sm btn-label-brand create_video" id="create_video_hidden">
                                                    <i style="text-align:right;" class="fa fa-plus-circle"></i>
                                                </a>
                                            </div>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#video_content_hidden" id="video_tab_hidden" role="tab">
                                                Video
                                            </a>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#article_content_hidden" id="article_tab_hidden" role="tab">
                                                Article
                                            </a>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#quiz_content_hidden" id="quiz_tab_hidden" role="tab">
                                                Quiz
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                                <div class="tab-content" id="tab_content_hidden">
                                    <!-- Arttcle Tab Hidden -->
                                    <div class="tab-pane" id="article_content_hidden" role="tabpanel">
                                        <div class="accordion accordion-solid  accordion-svg-icon" id="accordionArticle_hidden">
                                            <div class="card">
                                                <div class="card-header accordion_header_part" style="background-color:white;color:red;" id="headingArticle_hidden">
                                                    <div class="card-title collapsy_article " style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_article_hidden" aria-expanded="true" aria-controls="collapse_article_hidden">
                                                        <div style="float:left;width:40%;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                </g>
                                                            </svg> <span id="article_label_hidden">Sample Article</span>
                                                        </div>
                                                        <div style="text-align:right;float:right;width:55%;" id="remove_content_part_article_div_hidden" class="remove_content_part_div">
                                                            <a href="javascript:;" id="removePart_article_hidden" style="display:none;" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="collapse_article_hidden" class="collapse show " aria-labelledby="headingArticle_hidden" data-parent="#accordionArticle_hidden">
                                                    <div class="card-body">
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
                                                                    <div class="kt-typeahead article_title_div_hidden">
                                                                        <input class="form-control" type="text" name="article" id="article_hidden" style="display:none;" placeholder="Title">
                                                                        <span class="form-control article_title_span" id="article_input_hidden">Title</span>
                                                                    </div>
                                                                    <span class="form-text text-muted">The maximum characters allowed is 80 </span>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="article_id" id="article_id_hidden" />
                                                            <div class="form-group row">
                                                                <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" id="btn_article_form_hidden" style="text-align:center;">
                                                                    <button id="submit_article_form_hidden" class="btn btn-success submit_article_title">Save Article</button>
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
                                                                    <button id="submit_article_body_form_hidden" class="btn btn-success submit_article_body">Save Article</button>
                                                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Article Tab Hidden -->
                                    <!-- Video Tab -->
                                    <div class="tab-pane" id="video_content_hidden" role="tabpanel">
                                        <form class="kt-form kt-form--label-left" id="user_add_video_form_hidden">
                                            {{csrf_field()}}
                                            <div class="form-group form-group-last row" id="kt_repeater_hidden">
                                                <div class=" col-lg-12 col-md-12 col-sm-12">
                                                    <div id="kt_repeater_1">
                                                        <div class="accordion accordion-solid  accordion-svg-icon" data-repeater-list id="accordionVideo_hidden">

                                                            <div class="card clone_identification_hidden" id="video_board_hidden">
                                                                <div class="card-header accordion_header_part" style="background-color:white;color:red;" id="headingVideo_hidden">
                                                                    <div class="card-title collapsy_video" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_video_hidden" aria-expanded="true" aria-controls="collapse_video_hidden">
                                                                        <div style="float:left;width:40%;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                                </g>
                                                                            </svg> <span id="video_label_hidden">Sample Video</span>
                                                                        </div>
                                                                        <div style="text-align:right;float:right;width:55%;" id="remove_content_part_div_video_hidden" class="remove_content_part_div">
                                                                            <a href="javascript:;" id="removePart_video_hidden" style="display:none;" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                                <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                                            </a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="collapse_video_hidden" class="collapse show" aria-labelledby="headingVideo_hidden" data-parent="#accordionVideo_hidden">
                                                                    <div class="card-body">
                                                                        <div class="form-group row" style="padding-top:10px; padding-bottom:10px;">
                                                                            <label class="col-form-label col-lg-3 col-sm-12">Video Title <span class="required">*<span></label>
                                                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                <div class="kt-typeahead video_title_div_hidden">
                                                                                    <input class="form-control" type="text" name="video_name" id="video_hidden" value="" style="display:none;" placeholder="Video Title">
                                                                                    <span class="form-control video_title_span" id="video_input_hidden">Video Title</span>
                                                                                </div>
                                                                                <span class="form-text text-muted">The maximum characters allowed is 80 </span>

                                                                            </div>
                                                                            <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12 col-xs-12" id="video_div_retrieve_hidden" style="display:none;">
                                                                                <div style="float:right;">
                                                                                    <button type="button" class="btn btn-outline-warning btn-sm btn_retrieve_video" id="btn_retrieve_video_hidden">
                                                                                        <i class="fa fa-retweet"></i> Retrieve Video
                                                                                    </button>&nbsp;
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="kt-uppy video_identification_1" id="kt_uppy_hidden">
                                                                            <div class="kt-uppy__drag"></div>
                                                                            <div class="kt-uppy__informer remove_double_i_1"></div>
                                                                            <div class="kt-uppy__progress remove_double_p_1"></div>
                                                                            <div class="kt-uppy__thumbnails"></div>
                                                                        </div>
                                                                        <div id="video_div_remove_hidden" style="display:none;text-align:center;">
                                                                            <span class="col-lg-9 col-xl-9 col-md-9" id="video_exist_hidden">Video Title</span><button type="button" class="btn btn-label-google btn-sm btn_remove_video" id="btn_remove_video_hidden"><i class="fa fa-video-slash"></i> Remove Video</button>&nbsp;
                                                                        </div>

                                                                        <input type="hidden" name="video" id="video_cdn_hidden" value="" />
                                                                        <input type="hidden" name="video_filename" id="video_filename_hidden" value="" />
                                                                        <input type="hidden" name="video_size" id="video_size_hidden" value="" />
                                                                        <input type="hidden" name="video_length" id="video_length_hidden" value="" />
                                                                        <input type="hidden" name="video_poster" id="video_poster_hidden" value="" />
                                                                        <input type="hidden" name="video_thumbnail" id="video_thumbnail_hidden" value="" />
                                                                        <input type="hidden" name="video_resolution" id="video_resolution_hidden" value="" />
                                                                        <input type="hidden" name="video_id" id="video_id_hidden" value="" />
                                                                        <div class="row" style="margin-top:20px;">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" style="text-align:center;">
                                                                                <button id="submit_video_form_hidden" class="btn btn-success submit_video">Save Video</button>
                                                                                <button type="reset" class="btn btn-secondary">Clear</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                    <!-- End Video Tab -->
                                </div>
                            </div>
                            <!-- End Part Hidden Clonning -->
                            <!-- End Video Article Quiz Content -->

                        </div>

                    </div>
                    @endif
                     <!-- Pag may data sections na ire-retrieve eto yung mag lo-load -->
                    @if(count($sections) > 0)
                    @foreach($sections as $key => $section)
                    <div class="tab-pane <?= $key == 0 ? 'active' : '' ?>" id="kt_tabs_7-<?= $section->section_number ?>" role="tabpanel">
                        <div class="kt-portlet__body" id="section-<?= $section->section_number ?>">
                            <!-- Section Form -->
                            <form class="kt-form kt-form--label-left" id="user_add_content_form-<?= $section->section_number ?>">
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
                                        <div class="kt-typeahead section_name_div-<?= $section->section_number ?>">
                                            <input class="form-control" type="text" name="section_name" style="display:none;" id="section_name-<?= $section->section_number ?>" value="<?= $section->name ?>" placeholder="Introduction">
                                            <span class="form-control section_name" id="section_name_label-<?= $section->section_number ?>"><?= $section->name ?></span>
                                        </div>
                                        <span class="form-text text-muted section_name_muted-<?= $section->section_number ?>" style="display:none;">The maximum characters allowed is 80 </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Objective <span class="required">*<span></label>
                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                        <div class="kt-typeahead section_objective_div-<?= $section->section_number ?>">
                                            <input class="form-control" type="text" name="objective" style="display:none;" id="objective-<?= $section->section_number ?>" value="<?= $section->objective ?>" placeholder="Objectives">
                                            <span class="form-control section_objective" id="section_objective_label-<?= $section->section_number ?>"><?= $section->objective ?></span>
                                        </div>
                                        <span class="form-text text-muted section_objective_muted-<?= $section->section_number ?>" style="display:none;">The maximum characters allowed is 200 </span>
                                    </div>
                                </div>
                                <input type="hidden" name="section_id" id="section_id-<?= $section->section_number ?>" value="<?= $section->id ?>" />
                                <div class="form-group row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" style="text-align:center;">
                                        <button id="submit_content_form-<?= $section->section_number ?>" class="btn btn-success submit_section">Save Section</button>
                                        <button type="reset" class="btn btn-secondary">Clear</button>
                                    </div>
                                </div>
                            </form>
                            <!-- End Section Form -->
                            <!-- Video Article Quiz Content -->
                            @if(count(_get_video_and_content_part($data->id,$section->id)) > 0)
                             <!-- Pag may data part na ire-retrieve eto yung mag lo-load -->
                            @foreach(_get_video_and_content_part($data->id,$section->id) as $key => $content)
                            <div id="part_clone-<?= $section->section_number ?>_<?= $key + 1 ?>" style="margin-top:20px;" class="part_count-<?= $section->section_number ?> part">
                                <div class="kt-portlet__head-toolbar selection" id="selection_tab-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                    <ul class="nav nav-pills nav-pills-sm section_content" id="ul_list-<?= $section->section_number ?>_<?= $key + 1 ?>" style="display:none;" role="tablist">
                                        <li class="nav-items">
                                            <div id="add_video_parent-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                <a data-repeater-create class="btn btn-bold btn-sm btn-label-brand create_video" id="create_video-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                    <i style="text-align:right;" class="fa fa-plus-circle"></i>
                                                </a>
                                            </div>
                                        </li>
                                        <li class="nav-items" style="display:none;">
                                            <a class="nav-link section_links <?= isset($content->video_url) ? "active" : "" ?>" data-toggle="tab" href="#video_content-<?= $section->section_number ?>_<?= $key + 1 ?>" id="video_tab-<?= $section->section_number ?>_<?= $key + 1 ?>" role="tab">
                                                Video
                                            </a>
                                        </li>
                                        <li class="nav-items" style="display:none;">
                                            <a class="nav-link section_links <?= isset($content->article_title) ? "active" : "" ?>" data-toggle="tab" href="#article_content-<?= $section->section_number ?>_<?= $key + 1 ?>" id="article_tab-<?= $section->section_number ?>_<?= $key + 1 ?>" role="tab">
                                                Article
                                            </a>
                                        </li>
                                        <li class="nav-items" style="display:none;">
                                            <a class="nav-link section_links <?= isset($content->quizes_title) ? "active" : "" ?>" data-toggle="tab" href="#quiz_content-<?= $section->section_number ?>_<?= $key + 1 ?>" id="quiz_tab-<?= $section->section_number ?>_<?= $key + 1 ?>" role="tab">
                                                Quiz
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                                <div class="tab-content" id="tab_content-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                    <!-- Article tab -->
                                    <div class="tab-pane <?= isset($content->article_title) ? "active" : "" ?>" id="article_content-<?= $section->section_number ?>_<?= $key + 1 ?>" role="tabpanel">
                                        <div class="accordion accordion-solid  accordion-svg-icon" id="accordionArticle-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                            <div class="card">
                                                <div class="card-header accordion_header_part" style="background-color:white;color:red;" id="headingArticle-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                    <div class="card-title collapsy_article " style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_article-<?= $section->section_number ?>_<?= $key + 1 ?>" aria-expanded="true" aria-controls="collapse_article-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                        <div style="float:left;width:40%;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                </g>
                                                            </svg> <span id="article_label-<?= $section->section_number ?>_<?= $key + 1 ?>"><?= isset($content->article_title) ? $content->article_title : "" ?></span>
                                                        </div>
                                                        <div style="text-align:right;float:right;width:55%;" id="remove_content_part_article_div-<?= $section->section_number ?>_<?= $key + 1 ?>" class="remove_content_part_div">
                                                            <a href="javascript:;" id="removePart_article-<?= $section->section_number ?>_<?= $key + 1 ?>" style="display:none;" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                            </a>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="collapse_article-<?= $section->section_number ?>_<?= $key + 1 ?>" class="collapse show" aria-labelledby="headingArticle-<?= $section->section_number ?>_<?= $key + 1 ?>" data-parent="#accordionArticle-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                    <div class="card-body">
                                                        <form class="kt-form kt-form--label-left" id="user_add_article_title_form-<?= $section->section_number ?>_<?= $key + 1 ?>">
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
                                                                    <div class="kt-typeahead article_title_div-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                                        <input class="form-control" type="text" name="article" id="article-<?= $section->section_number ?>_<?= $key + 1 ?>" style="display:none;" value="<?= isset($content->article_title) ? $content->article_title : "" ?>" placeholder="Title">
                                                                        <span class="form-control article_title_span" id="article_input-<?= $section->section_number ?>_<?= $key + 1 ?>"><?= isset($content->article_title) ? $content->article_title : "Title" ?></span>
                                                                    </div>
                                                                    <span class="form-text text-muted">The maximum characters allowed is 80 </span>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="article_id" id="article_id-<?= $section->section_number ?>_<?= $key + 1 ?>" value="<?= isset($content->article_id) ? $content->article_id : "" ?>" />
                                                            <div class="form-group row">
                                                                <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" id="btn_article_form-<?= $section->section_number ?>_1" style="text-align:center;">
                                                                    <button id="submit_article_form-<?= $section->section_number ?>_<?= $key + 1 ?>" class="btn btn-success submit_article_title">Save Title</button>
                                                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                                                </div>
                                                            </div>

                                                        </form>
                                                        <form class="kt-form kt-form--label-left" id="user_add_article_body_form-<?= $section->section_number ?>_<?= $key + 1 ?>">
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
                                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 about_group_" id="about_group-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                                    <div class="form-group">
                                                                        <label>Article Title: <span id="article_label_title-<?= $section->section_number ?>_<?= $key + 1 ?>"></span></label>

                                                                        <div id="textarea_about-<?= $section->section_number ?>_<?= $key + 1 ?>" class="textarea" style="display:none;">
                                                                            <textarea class="form-control" minlength="50" id="about-<?= $section->section_number ?>_<?= $key + 1 ?>" name="about">{{isset($content->article_description) ? htmlspecialchars_decode($content->article_description) : ""}}</textarea>
                                                                        </div>
                                                                        <div id="div_about-<?= $section->section_number ?>_<?= $key + 1 ?>" class="box">
                                                                            <?= isset($content->article_description) ? htmlspecialchars_decode($content->article_description) : "" ?>
                                                                        </div>
                                                                        <span class="form-text text-muted">Please provide the your article.</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" id="btn_article_body_form-<?= $section->section_number ?>_<?= $key + 1 ?>" style="text-align:center;">
                                                                    <button id="submit_article_body_form-<?= $section->section_number ?>_<?= $key + 1 ?>" class="btn btn-success submit_article_body">Save Article</button>
                                                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Article Tab -->
                                    <!-- Video Tab -->
                                    <div class="tab-pane <?= isset($content->video_url) ? "active" : "" ?>" id="video_content-<?= $section->section_number ?>_<?= $key + 1 ?>" role="tabpanel">
                                        <form class="kt-form kt-form--label-left" id="user_add_video_form-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                            {{csrf_field()}}
                                            <div class="form-group form-group-last row" id="kt_repeater_1">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div id="kt_repeater_1">
                                                        <div class="accordion accordion-solid  accordion-svg-icon" data-repeater-list id="accordionVideo-<?= $section->section_number ?>_<?= $key + 1 ?>">

                                                            <div class="card clone_identification_1" id="clone_board-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                                <div class="card-header accordion_header_part" style="background-color:white;color:red;" id="headingVideo-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                                    <div class="card-title collapsy" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_video-<?= $section->section_number ?>_<?= $key + 1 ?>" aria-expanded="true" aria-controls="collapse_video-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                                        <div style="float:left;width:40%;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                                </g>
                                                                            </svg> <span id="video_label-<?= $section->section_number ?>_<?= $key + 1 ?>"><?= isset($content->video_filename) ? $content->video_filename : "Video Title" ?></span>
                                                                        </div>
                                                                        <div style="text-align:right;float:right;width:55%;" id="remove_content_part_video_div-<?= $section->section_number ?>_<?= $key + 1 ?>" class="remove_content_part_div">
                                                                            <a href="javascript:;" id="removePart_video-<?= $section->section_number ?>_<?= $key + 1 ?>" style="display:none;" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                                <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                                            </a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="collapse_video-<?= $section->section_number ?>_<?= $key + 1 ?>" class="collapse show" aria-labelledby="headingOne-<?= $section->section_number ?>_<?= $key + 1 ?>" data-parent="#accordionVideo-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                                    <div class="card-body">
                                                                        <div class="form-group row" style="padding-top:10px; padding-bottom:10px;">
                                                                            <label class="col-form-label col-lg-3 col-sm-12">Video Title <span class="required">*<span></label>
                                                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                <div class="kt-typeahead video_title_div-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                                                    <input class="form-control" type="text" name="video_name" id="video-<?= $section->section_number ?>_<?= $key + 1 ?>" value="<?= isset($content->video_filename) ? $content->video_filename : "" ?>" style="display:none;" placeholder="Video Title">
                                                                                    <span class="form-control video_title_span" id="video_input-<?= $section->section_number ?>_<?= $key + 1 ?>"><?= isset($content->video_filename) ? $content->video_filename : "Video Title" ?></span>
                                                                                </div>
                                                                                <span class="form-text text-muted">The maximum characters allowed is 80 </span>

                                                                            </div>
                                                                            <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12 col-xs-12" id="video_div_retrieve-<?= $section->section_number ?>_<?= $key + 1 ?>" style="display:none;">
                                                                                <div style="float:right;">
                                                                                    <button type="button" class="btn btn-outline-warning btn-sm btn_retrieve_video" id="btn_retrieve_video-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                                                        <i class="fa fa-retweet"></i> Retrieve Video
                                                                                    </button>&nbsp;
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="kt-uppy video_identification_1" <?= isset($content->video_id) ? "style='display:none;'" : "" ?> id="kt_uppy-<?= $section->section_number ?>_<?= $key + 1 ?>">
                                                                            <div class="kt-uppy__drag"></div>
                                                                            <div class="kt-uppy__informer remove_double_i_1"></div>
                                                                            <div class="kt-uppy__progress remove_double_p_1"></div>
                                                                            <div class="kt-uppy__thumbnails"></div>
                                                                        </div>
                                                                        <div id="video_div_remove-<?= $section->section_number ?>_<?= $key + 1 ?>" style="display:<?= !isset($content->video_id) ? "none" : "absolute" ?>;text-align:center;">
                                                                            <span class="col-lg-9 col-xl-9 col-md-9" id="video_exist-<?= $section->section_number ?>_<?= $key + 1 ?>"><?= isset($content->video_filename) ? $content->video_filename : "Video Title" ?></span><button type="button" class="btn btn-label-google btn-sm btn_remove_video" id="btn_remove_video-<?= $section->section_number ?>_<?= $key + 1 ?>"><i class="fa fa-video-slash"></i> Remove Video</button>&nbsp;
                                                                        </div>

                                                                        <input type="hidden" name="video" id="video_cdn-<?= $section->section_number ?>_<?= $key + 1 ?>" value="<?= isset($content->video_url) ? $content->video_url : "" ?>" />
                                                                        <input type="hidden" name="video_filename" id="video_filename-<?= $section->section_number ?>_<?= $key + 1 ?>" value="<?= isset($content->video_filename) ? $content->video_filename : "" ?>" />
                                                                        <input type="hidden" name="video_size" id="video_size-<?= $section->section_number ?>_<?= $key + 1 ?>" value="<?= isset($content->video_size) ? $content->video_size : "" ?>" />
                                                                        <input type="hidden" name="video_length" id="video_length-<?= $section->section_number ?>_<?= $key + 1 ?>" value="<?= isset($content->video_length) ? $content->video_length : "" ?>" />
                                                                        <input type="hidden" name="video_poster" id="video_poster-<?= $section->section_number ?>_<?= $key + 1 ?>" value="<?= isset($content->video_poster) ? $content->video_poster : "" ?>" />
                                                                        <input type="hidden" name="video_thumbnail" id="video_thumbnail-<?= $section->section_number ?>_<?= $key + 1 ?>" value="<?= isset($content->video_thumbnail) ? $content->video_thumbnail : "" ?>" />
                                                                        <input type="hidden" name="video_resolution" id="video_resolution-<?= $section->section_number ?>_<?= $key + 1 ?>" value="<?= isset($content->video_resolution) ? json_decode($content->video_resolution) : "" ?>" />
                                                                        <input type="hidden" name="video_id" id="video_id-<?= $section->section_number ?>_<?= $key + 1 ?>" value="<?= isset($content->video_id) ? $content->video_id : "" ?>" />
                                                                        <div class="row" style="margin-top:20px;">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" style="text-align:center;">
                                                                                <button id="submit_video_form-<?= $section->section_number ?>_<?= $key + 1 ?>" class="btn btn-success submit_video">Save Video</button>
                                                                                <button type="reset" class="btn btn-secondary">Clear</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Video Tab -->
                            </div>
                            @endforeach
                            @endif
                            @if(count(_get_video_and_content_part($data->id,$section->id)) == 0)
                             <!-- Pag walang data part na ire-retrieve eto yung mag lo-load -->
                            <div id="part_clone-<?= $section->section_number ?>_1" class="part_count-<?= $section->section_number ?> part">
                                <div class="kt-portlet__head-toolbar selection" id="selection_tab-<?= $section->section_number ?>_1">
                                    <ul class="nav nav-pills nav-pills-sm section_content" id="ul_list-<?= $section->section_number ?>_1" role="tablist">
                                        <li class="nav-items">
                                            <div id="add_video_parent-<?= $section->section_number ?>_1">
                                                <a data-repeater-create class="btn btn-bold btn-sm btn-label-brand create_video" id="create_video-<?= $section->section_number ?>_1">
                                                    <i style="text-align:right;" class="fa fa-plus-circle"></i>
                                                </a>
                                            </div>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#video_content-<?= $section->section_number ?>_1" id="video_tab-<?= $section->section_number ?>_1" role="tab">
                                                Video
                                            </a>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#article_content-<?= $section->section_number ?>_1" id="article_tab-<?= $section->section_number ?>_1" role="tab">
                                                Article
                                            </a>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#quiz_content-<?= $section->section_number ?>_1" id="quiz_tab-<?= $section->section_number ?>_1" role="tab">
                                                Quiz
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                                <div class="tab-content" id="tab_content-<?= $section->section_number ?>_1">
                                    <!-- Article tab -->
                                    <div class="tab-pane" id="article_content-<?= $section->section_number ?>_1" role="tabpanel">
                                        <div class="accordion accordion-solid  accordion-svg-icon" id="accordionArticle-<?= $section->section_number ?>_1">
                                            <div class="card">
                                                <div class="card-header" style="background-color:white;color:red;" id="headingArticle_1_1">
                                                    <div class="card-title collapsy_article accordion_header_part" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_article-<?= $section->section_number ?>_1" aria-expanded="true" aria-controls="collapse_article-<?= $section->section_number ?>_1">
                                                        <div style="float:left;width:40%;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                </g>
                                                            </svg> <span id="article_label-<?= $section->section_number ?>_1">Sample Article</span>
                                                        </div>
                                                        <div style="text-align:right;float:right;width:55%;" id="remove_content_part_article_div-<?= $section->section_number ?>_1" class="remove_content_part_div">
                                                            <a href="javascript:;" id="removePart_article-<?= $section->section_number ?>_1" style="display:none;" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                            </a>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="collapse_article-<?= $section->section_number ?>_1" class="collapse show" aria-labelledby="headingArticle-<?= $section->section_number ?>_1" data-parent="#accordionArticle-<?= $section->section_number ?>_1">
                                                    <div class="card-body">
                                                        <form class="kt-form kt-form--label-left" id="user_add_article_title_form-<?= $section->section_number ?>_1">
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
                                                                    <div class="kt-typeahead article_title_div-<?= $section->section_number ?>_1">
                                                                        <input class="form-control" type="text" name="article" id="article-<?= $section->section_number ?>_1" style="display:none;" value="<?= isset($content->article_title) ? $content->article_title : "" ?>" placeholder="Title">
                                                                        <span class="form-control article_title_span" id="article_input-<?= $section->section_number ?>_1"><?= isset($content->article_title) ? $content->article_title : "Title" ?></span>
                                                                    </div>
                                                                    <span class="form-text text-muted">The maximum characters allowed is 80 </span>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="article_id" id="article_id-<?= $section->section_number ?>_1" value="<?= isset($content->article_id) ? $content->article_id : "" ?>" />
                                                            <div class="form-group row">
                                                                <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" id="btn_article_form-<?= $section->section_number ?>_1" style="text-align:center;">
                                                                    <button id="submit_article_form-<?= $section->section_number ?>_1" class="btn btn-success submit_article_title">Save Title</button>
                                                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                                                </div>
                                                            </div>

                                                        </form>
                                                        <form class="kt-form kt-form--label-left" id="user_add_article_body_form_<?= $section->section_number ?>_1">
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
                                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 about_group_" id="about_group-<?= $section->section_number ?>_1">
                                                                    <div class="form-group">
                                                                        <label>Article Title: <span id="article_label_title-<?= $section->section_number ?>_1"></span></label>

                                                                        <div id="textarea_about-<?= $section->section_number ?>_1" class="textarea" style="display:none;">
                                                                            <textarea class="form-control" minlength="50" id="about-<?= $section->section_number ?>_1" name="about">{{isset($content->article_description) ? htmlspecialchars_decode($content->article_description) : ""}}</textarea>
                                                                        </div>
                                                                        <div id="div_about-<?= $section->section_number ?>_1" class="box">
                                                                            <?= isset($content->article_description) ? htmlspecialchars_decode($content->article_description) : "" ?>
                                                                        </div>
                                                                        <span class="form-text text-muted">Please provide the your article.</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" id="btn_article_body_form-<?= $section->section_number ?>_1" style="text-align:center;">
                                                                    <button id="submit_article_body_form-<?= $section->section_number ?>_1" class="btn btn-success submit_article_body">Save Article</button>
                                                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Article Tab -->
                                    <!-- Video Tab -->
                                    <div class="tab-pane" id="video_content-<?= $section->section_number ?>_1" role="tabpanel">
                                        <form class="kt-form kt-form--label-left" id="user_add_video_form-<?= $section->section_number ?>_1">
                                            {{csrf_field()}}
                                            <div class="form-group form-group-last row" id="kt_repeater_1">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div id="kt_repeater_1">
                                                        <div class="accordion accordion-solid  accordion-svg-icon" data-repeater-list id="accordionVideo-<?= $section->section_number ?>_1">

                                                            <div class="card clone_identification_1" id="clone_board-<?= $section->section_number ?>_1">
                                                                <div class="card-header accordion_header_part" style="background-color:white;color:red;" id="headingVideo-<?= $section->section_number ?>_1">
                                                                    <div class="card-title collapsy" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_video-<?= $section->section_number ?>_1" aria-expanded="true" aria-controls="collapse_video-<?= $section->section_number ?>_1">
                                                                        <div style="float:left;width:40%;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                                </g>
                                                                            </svg> <span id="video_label-<?= $section->section_number ?>_1">Sample Video</span>
                                                                        </div>
                                                                        <div style="text-align:right;float:right;width:55%;" id="remove_content_part_video_div-<?= $section->section_number ?>_1" class="remove_content_part_div">
                                                                            <a href="javascript:;" id="removePart_video-<?= $section->section_number ?>_1" style="display:none;" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                                <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                                            </a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="collapse_video-<?= $section->section_number ?>_1" class="collapse show" aria-labelledby="headingOne-<?= $section->section_number ?>_1" data-parent="#accordionVideo-<?= $section->section_number ?>_1">
                                                                    <div class="card-body">
                                                                        <div class="form-group row" style="padding-top:10px; padding-bottom:10px;">
                                                                            <label class="col-form-label col-lg-3 col-sm-12">Video Title <span class="required">*<span></label>
                                                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                <div class="kt-typeahead video_title_div-<?= $section->section_number ?>_1">
                                                                                    <input class="form-control" type="text" name="video_name" id="video-<?= $section->section_number ?>_1" value="" style="display:none;" placeholder="Video Title">
                                                                                    <span class="form-control video_title_span" id="video_input-<?= $section->section_number ?>_1">Video Title</span>
                                                                                </div>
                                                                                <span class="form-text text-muted">The maximum characters allowed is 80 </span>

                                                                            </div>
                                                                            <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12 col-xs-12" id="video_div_retrieve-<?= $section->section_number ?>_1" style="display:none;">
                                                                                <div style="float:right;">
                                                                                    <button type="button" class="btn btn-outline-warning btn-sm btn_retrieve_video" id="btn_retrieve_video-<?= $section->section_number ?>_1">
                                                                                        <i class="fa fa-retweet"></i> Retrieve Video
                                                                                    </button>&nbsp;
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="kt-uppy video_identification_1" id="kt_uppy-<?= $section->section_number ?>_1">
                                                                            <div class="kt-uppy__drag"></div>
                                                                            <div class="kt-uppy__informer remove_double_i_1"></div>
                                                                            <div class="kt-uppy__progress remove_double_p_1"></div>
                                                                            <div class="kt-uppy__thumbnails"></div>
                                                                        </div>
                                                                        <div id="video_div_remove-<?= $section->section_number ?>_1" style="display:none;text-align:center;">
                                                                            <span class="col-lg-9 col-xl-9 col-md-9" id="video_exist-<?= $section->section_number ?>_1">Video Title</span><button type="button" class="btn btn-label-google btn-sm btn_remove_video" id="btn_remove_video-<?= $section->section_number ?>_1"><i class="fa fa-video-slash"></i> Remove Video</button>&nbsp;
                                                                        </div>

                                                                        <input type="hidden" name="video" id="video_cdn-<?= $section->section_number ?>_1" value="" />
                                                                        <input type="hidden" name="video_filename" id="video_filename-<?= $section->section_number ?>_1" value="" />
                                                                        <input type="hidden" name="video_size" id="video_size-<?= $section->section_number ?>_1" value="" />
                                                                        <input type="hidden" name="video_length" id="video_length-<?= $section->section_number ?>_1" value="" />
                                                                        <input type="hidden" name="video_poster" id="video_poster-<?= $section->section_number ?>_1" value="" />
                                                                        <input type="hidden" name="video_thumbnail" id="video_thumbnail-<?= $section->section_number ?>_1" value="" />
                                                                        <input type="hidden" name="video_resolution" id="video_resolution-<?= $section->section_number ?>_1" value="" />
                                                                        <input type="hidden" name="video_id" id="video_id-<?= $section->section_number ?>_1" value="" />
                                                                        <div class="row" style="margin-top:20px;">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" style="text-align:center;">
                                                                                <button id="submit_video_form-<?= $section->section_number ?>_1" class="btn btn-success submit_video">Save Video</button>
                                                                                <button type="reset" class="btn btn-secondary">Clear</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Video Tab -->
                            </div>
                            @endif

                            <!-- Hidden Part For Cloning -->
                            <div id="part_clone_hidden" style="display:none;margin-top:20px;">
                                <div class="part_count_hidden" id="selection_tab_hidden">
                                    <ul class="nav nav-pills nav-pills-sm section_content" id="ul_list_hidden" role="tablist">
                                        <li class="nav-items">
                                            <div id="add_video_parent_hidden">
                                                <a data-repeater-create class="btn btn-bold btn-sm btn-label-brand create_video" id="create_video_hidden">
                                                    <i style="text-align:right;" class="fa fa-plus-circle"></i>
                                                </a>
                                            </div>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#video_content_hidden" id="video_tab_hidden" role="tab">
                                                Video
                                            </a>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#article_content_hidden" id="article_tab_hidden" role="tab">
                                                Article
                                            </a>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#quiz_content_hidden" id="quiz_tab_hidden" role="tab">
                                                Quiz
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                                <div class="tab-content" id="tab_content_hidden">
                                    <!-- Arttcle Tab Hidden -->
                                    <div class="tab-pane" id="article_content_hidden" role="tabpanel">
                                        <div class="accordion accordion-solid  accordion-svg-icon" id="accordionArticle_hidden">
                                            <div class="card">
                                                <div class="card-header accordion_header_part" style="background-color:white;color:red;" id="headingArticle_hidden">
                                                    <div class="card-title collapsy_article" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_article_hidden" aria-expanded="true" aria-controls="collapse_article_hidden">
                                                        <div style="float:left;width:40%;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                </g>
                                                            </svg> <span id="article_label_hidden">Sample Article</span>
                                                        </div>
                                                        <div style="text-align:right;float:right;width:55%;" id="remove_content_part_aricle_div_hidden" class="remove_content_part_div">
                                                            <a href="javascript:;" id="removePart_article_hidden" style="display:none;" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="collapse_article_hidden" class="collapse show " aria-labelledby="headingArticle_hidden" data-parent="#accordionArticle_hidden">
                                                    <div class="card-body">
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
                                                                    <div class="kt-typeahead article_title_div_hidden">
                                                                        <input class="form-control" type="text" name="article" id="article_hidden" style="display:none;" placeholder="Title">
                                                                        <span class="form-control article_title_span" id="article_input_hidden">Title</span>
                                                                    </div>
                                                                    <span class="form-text text-muted">The maximum characters allowed is 80 </span>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="article_id" id="article_id_hidden" />
                                                            <div class="form-group row">
                                                                <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" id="btn_article_form_hidden" style="text-align:center;">
                                                                    <button id="submit_article_form_hidden" class="btn btn-success submit_article_title">Save Article</button>
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
                                                                    <button id="submit_article_body_form_hidden" class="btn btn-success submit_article_body">Save Article</button>
                                                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Article Tab Hidden -->
                                    <!-- Video Tab -->
                                    <div class="tab-pane" id="video_content_hidden" role="tabpanel">
                                        <form class="kt-form kt-form--label-left" id="user_add_video_form_hidden">
                                            {{csrf_field()}}
                                            <div class="form-group form-group-last row" id="kt_repeater_hidden">
                                                <div class=" col-lg-12 col-md-12 col-sm-12">
                                                    <div id="kt_repeater_1">
                                                        <div class="accordion accordion-solid  accordion-svg-icon" data-repeater-list id="accordionVideo_hidden">

                                                            <div class="card clone_identification_hidden" id="video_board_hidden">
                                                                <div class="card-header accordion_header_part" style="background-color:white;color:red;" id="headingVideo_hidden">
                                                                    <div class="card-title collapsy_video" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_video_hidden" aria-expanded="true" aria-controls="collapse_video_hidden">
                                                                        <div style="float:left;width:40%;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                                </g>
                                                                            </svg> <span id="video_label_hidden">Sample Video</span>
                                                                        </div>
                                                                        <div style="text-align:right;float:right;width:55%;" id="remove_content_part_video_div_hidden" class="remove_content_part_div">
                                                                            <a href="javascript:;" id="removePart_video_hidden" style="display:none;" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                                <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                                            </a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="collapse_video_hidden" class="collapse show" aria-labelledby="headingVideo_hidden" data-parent="#accordionVideo_hidden">
                                                                    <div class="card-body">
                                                                        <div class="form-group row" style="padding-top:10px; padding-bottom:10px;">
                                                                            <label class="col-form-label col-lg-3 col-sm-12">Video Title <span class="required">*<span></label>
                                                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                <div class="kt-typeahead video_title_div_hidden">
                                                                                    <input class="form-control" type="text" name="video_name" id="video_hidden" value="" style="display:none;" placeholder="Video Title">
                                                                                    <span class="form-control video_title_span" id="video_input_hidden">Video Title</span>
                                                                                </div>
                                                                                <span class="form-text text-muted">The maximum characters allowed is 80 </span>

                                                                            </div>
                                                                            <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12 col-xs-12" id="video_div_retrieve_hidden" style="display:none;">
                                                                                <div style="float:right;">
                                                                                    <button type="button" class="btn btn-outline-warning btn-sm btn_retrieve_video" id="btn_retrieve_video_hidden">
                                                                                        <i class="fa fa-retweet"></i> Retrieve Video
                                                                                    </button>&nbsp;
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="kt-uppy video_identification_1" id="kt_uppy_hidden">
                                                                            <div class="kt-uppy__drag"></div>
                                                                            <div class="kt-uppy__informer remove_double_i_1"></div>
                                                                            <div class="kt-uppy__progress remove_double_p_1"></div>
                                                                            <div class="kt-uppy__thumbnails"></div>
                                                                        </div>
                                                                        <div id="video_div_remove_hidden" style="display:none;text-align:center;">
                                                                            <span class="col-lg-9 col-xl-9 col-md-9" id="video_exist_hidden">Video Title</span><button type="button" class="btn btn-label-google btn-sm btn_remove_video" id="btn_remove_video_hidden"><i class="fa fa-video-slash"></i> Remove Video</button>&nbsp;
                                                                        </div>

                                                                        <input type="hidden" name="video" id="video_cdn_hidden" value="" />
                                                                        <input type="hidden" name="video_filename" id="video_filename_hidden" value="" />
                                                                        <input type="hidden" name="video_size" id="video_size_hidden" value="" />
                                                                        <input type="hidden" name="video_length" id="video_length_hidden" value="" />
                                                                        <input type="hidden" name="video_poster" id="video_poster_hidden" value="" />
                                                                        <input type="hidden" name="video_thumbnail" id="video_thumbnail_hidden" value="" />
                                                                        <input type="hidden" name="video_resolution" id="video_resolution_hidden" value="" />
                                                                        <input type="hidden" name="video_id" id="video_id_hidden" value="" />
                                                                        <div class="row" style="margin-top:20px;">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" style="text-align:center;">
                                                                                <button id="submit_video_form_hidden" class="btn btn-success submit_video">Save Video</button>
                                                                                <button type="reset" class="btn btn-secondary">Clear</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- End Video Tab -->
                                </div>
                            </div>
                            <!-- End Part Hidden Clonning -->
                            <!-- End Video Article Quiz Content -->

                        </div>

                    </div>

                    @endforeach
                    @endif
                    <!-- Hidden Section for Clone -->
                    <div class="tab-pane" id="kt_tabs_hidden" role="tabpanel" style="display:none;">
                        <div class="kt-portlet__body" id="section_hidden">
                            <!-- Section Form -->
                            <form class="kt-form kt-form--label-left" id="user_add_content_form_hidden">
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
                                        <div class="kt-typeahead section_name_div_hidden">
                                            <input class="form-control" type="text" name="section_name" style="display:none;" id="section_name_hidden" placeholder="Introduction">
                                            <span class="form-control section_name" id="section_name_label_hidden">Introduction</span>
                                        </div>
                                        <span class="form-text text-muted" style="display:none;">The maximum characters allowed is 80 </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Objective <span class="required">*<span></label>
                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                        <div class="kt-typeahead section_objective_div_hidden">
                                            <input class="form-control" type="text" name="objective" style="display:none;" id="objective_hidden" placeholder="Objectives">
                                            <span class="form-control section_objective" id="section_objective_label_hidden">Objective</span>
                                        </div>
                                        <span class="form-text text-muted" style="display:none;">The maximum characters allowed is 200 </span>

                                    </div>
                                </div>
                                <input type="hidden" name="section_id" id="section_id_hidden" />
                                <div class="form-group row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" style="text-align:center;">
                                        <button id="submit_content_form_hidden" class="btn btn-success submit_section">Save Section</button>
                                        <button type="reset" class="btn btn-secondary">Clear</button>
                                    </div>
                                </div>
                            </form>
                            <!-- End Section Form -->
                            <!-- Video Article Quiz Content -->
                            <div id="part_clone_hidden" class="part_count_hidden" style="display:none;">
                                <div class="kt-portlet__head-toolbar selection" id="selection_tab_hidden">
                                    <ul class="nav nav-pills nav-pills-sm section_content" id="ul_list_hidden" role="tablist">
                                        <li class="nav-items">
                                            <div id="add_video_parent_hidden">
                                                <a data-repeater-create class="btn btn-bold btn-sm btn-label-brand create_video" id="create_video_hidden">
                                                    <i style="text-align:right;" class="fa fa-plus-circle"></i>
                                                </a>
                                            </div>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#video_content_hidden" id="video_tab_hidden" role="tab">
                                                Video
                                            </a>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#article_content_hidden" id="article_tab_hidden" role="tab">
                                                Article
                                            </a>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links" data-toggle="tab" href="#quiz_content_hidden" id="quiz_tab_hidden" role="tab">
                                                Quiz
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                                <div class="tab-content" id="tab_content_hidden">
                                    <!-- Article tab -->
                                    <div class="tab-pane" id="article_content_hidden" role="tabpanel">
                                        <div class="accordion accordion-solid  accordion-svg-icon" id="accordionArticle_hidden">
                                            <div class="card">
                                                <div class="card-header accordion_header_part" style="background-color:white;color:red;" id="headingArticle_hidden">
                                                    <div class="card-title collapsy_article" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_article_hidden" aria-expanded="true" aria-controls="collapse_article_hidden">
                                                        <div style="float:left;width:40%;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                </g>
                                                            </svg> <span id="article_label_hidden">Sample Article</span>
                                                        </div>
                                                        <div style="text-align:right;float:right;width:55%;" id="remove_content_part_article_div_hidden" class="remove_content_part_div">
                                                            <a href="javascript:;" id="removePart_hidden" style="display:none;" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                            </a>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="collapse_article_hidden" class="collapse show" aria-labelledby="headingArticle_hidden" data-parent="#accordionArticle_hidden">
                                                    <div class="card-body">
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
                                                                    <div class="kt-typeahead article_title_div_hidden">
                                                                        <input class="form-control" type="text" name="article" id="article_hidden" style="display:none;" placeholder="Title">
                                                                        <span class="form-control article_title_span" id="article_input_hidden">Title</span>
                                                                    </div>
                                                                    <span class="form-text text-muted">The maximum characters allowed is 80 </span>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="article_id" id="article_id_hidden" />
                                                            <div class="form-group row">
                                                                <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" id="btn_article_form_hidden" style="text-align:center;">
                                                                    <button id="submit_article_form_hidden" class="btn btn-success submit_article_title">Save Title</button>
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
                                                                    <button id="submit_article_body_form_hidden" class="btn btn-success submit_article_body">Save Article</button>
                                                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Article Tab -->
                                    <!-- Video Tab -->
                                    <div class="tab-pane" id="video_content_hidden" role="tabpanel">
                                        <form class="kt-form kt-form--label-left" id="user_add_video_form_hidden">
                                            {{csrf_field()}}
                                            <div class="form-group form-group-last row" id="kt_repeater_hidden">
                                                <div class=" col-lg-12 col-md-12 col-sm-12">
                                                    <div id="kt_repeater_1">
                                                        <div class="accordion accordion-solid  accordion-svg-icon" data-repeater-list id="accordionVideo_hidden">

                                                            <div class="card clone_identification_hidden" id="video_board_hidden">
                                                                <div class="card-header accordion_header_part" style="background-color:white;color:red;" id="headingVideo_hidden">
                                                                    <div class="card-title collapsy_video" style="background-color:white;border:1px solid #DCDCDC	;" data-toggle="collapse" data-target="#collapse_video_hidden" aria-expanded="true" aria-controls="collapse_video_hidden">
                                                                        <div style="float:left;width:40%;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                                </g>
                                                                            </svg> <span id="video_label_hidden">Sample Video</span>
                                                                        </div>
                                                                        <div style="text-align:right;float:right;width:55%;" id="remove_content_part_video_div_hidden" class="remove_content_part_div">
                                                                            <a href="javascript:;" id="removePart_video_hidden" style="display:none;" class="btn-sm btn btn-label-danger btn-bold remove_video_con_1">
                                                                                <i style="text-align:right;" class="fa fa-minus-circle"></i>
                                                                            </a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="collapse_video_hidden" class="collapse show" aria-labelledby="headingVideo_hidden" data-parent="#accordionVideo_hidden">
                                                                    <div class="card-body">
                                                                        <div class="form-group row" style="padding-top:10px; padding-bottom:10px;">
                                                                            <label class="col-form-label col-lg-3 col-sm-12">Video Title <span class="required">*<span></label>
                                                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                <div class="kt-typeahead video_title_div_hidden">
                                                                                    <input class="form-control" type="text" name="video_name" id="video_hidden" value="" style="display:none;" placeholder="Video Title">
                                                                                    <span class="form-control video_title_span" id="video_input_hidden">Video Title</span>
                                                                                </div>
                                                                                <span class="form-text text-muted">The maximum characters allowed is 80 </span>

                                                                            </div>
                                                                            <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12 col-xs-12" id="video_div_retrieve_hidden" style="display:none;">
                                                                                <div style="float:right;">
                                                                                    <button type="button" class="btn btn-outline-warning btn-sm btn_retrieve_video" id="btn_retrieve_video_hidden">
                                                                                        <i class="fa fa-retweet"></i> Retrieve Video
                                                                                    </button>&nbsp;
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="kt-uppy video_identification_1" id="kt_uppy_hidden">
                                                                            <div class="kt-uppy__drag"></div>
                                                                            <div class="kt-uppy__informer remove_double_i_1"></div>
                                                                            <div class="kt-uppy__progress remove_double_p_1"></div>
                                                                            <div class="kt-uppy__thumbnails"></div>
                                                                        </div>
                                                                        <div id="video_div_remove_hidden" style="display:none;text-align:center;">
                                                                            <span class="col-lg-9 col-xl-9 col-md-9" id="video_exist_hidden">Video Title</span><button type="button" class="btn btn-label-google btn-sm btn_remove_video" id="btn_remove_video_hidden"><i class="fa fa-video-slash"></i> Remove Video</button>&nbsp;
                                                                        </div>

                                                                        <input type="hidden" name="video" id="video_cdn_hidden" value="" />
                                                                        <input type="hidden" name="video_filename" id="video_filename_hidden" value="" />
                                                                        <input type="hidden" name="video_size" id="video_size_hidden" value="" />
                                                                        <input type="hidden" name="video_length" id="video_length_hidden" value="" />
                                                                        <input type="hidden" name="video_poster" id="video_poster_hidden" value="" />
                                                                        <input type="hidden" name="video_thumbnail" id="video_thumbnail_hidden" value="" />
                                                                        <input type="hidden" name="video_resolution" id="video_resolution_hidden" value="" />
                                                                        <input type="hidden" name="video_id" id="video_id_hidden" value="" />
                                                                        <div class="row" style="margin-top:20px;">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" style="text-align:center;">
                                                                                <button id="submit_video_form_hidden" class="btn btn-success submit_video">Save Video</button>
                                                                                <button type="reset" class="btn btn-secondary">Clear</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- End Video Tab -->
                                </div>

                                <!-- End Video Article Quiz Content -->

                            </div>
                        </div>
                        <!-- End Hidden Section for Clone -->
                        <!-- End Main Section -->
                    </div>
                </div>
                <!-- End Section Body -->
            </div>
            <!-- End of the Main Body -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var toolbar_show = [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
    ];
    var global_video_id = 'kt_uppy-1_1';
    var section_id = "1";
    var create_video_id = "create_video-1";
    var id_edit_video_name = "name_of_video-1_1";
    var video_array = [];
    var submit_form_id = "submit_video_form-1";
    var form_id = "user_add_video_form-1";
    var div_textarea = "textarea_about-1_1";
    var textarea = "about-1_1";
    var box = "div_about-1_1";
    var section_div_id = 1;
    var part_id = "1_1";
    var deleteFile = 0;
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
        $(document).on("click", "button.submit_section", function(e) {
            submit_form_id = $(this).attr("id");
            form_id = $(e.target).closest("form").attr("id");
            FormDesignContent.init();
        });
        $(document).on("click", "button.submit_article_title", function(e) {
            submit_form_id = $(this).attr("id");
            form_id = $(e.target).closest("form").attr("id");
            FormDesignArticle.init();
        });
        $(document).on("click", "button.submit_article_body", function(e) {
            submit_form_id = $(this).attr("id");
            form_id = $(e.target).closest("form").attr("id");
            FormDesignArticleBody.init();
        });
        $(document).on("click", "button.submit_video", function(e) {
            submit_form_id = $(this).attr("id");
            form_id = $(e.target).closest("form").attr("id");

            FormDesignVideo.init();
        });
        $(document).on("click", "span.section_name", function() {
            var label_id = $(this).attr("id");

            section_div_id = label_id.substr(label_id.indexOf("-") + 1); // => "1_1"
            $("#section_name-" + section_div_id).show();
            $(".section_name_muted-" + section_div_id).show();
            $("#section_name_label-" + section_div_id).hide();
        });
        $(document).on("click", "span.section_objective", function() {
            var label_id = $(this).attr("id");
            section_div_id = label_id.substr(label_id.indexOf("-") + 1); // => "1_1"
            $("#objective-" + section_div_id).show();
            $(".section_objective_muted-" + section_div_id).show();
            $("#section_objective_label-" + section_div_id).hide();
        });
        $(document).on("click", "span.article_title_span", function() {
            var label_id = $(this).attr("id");
            // alert(label_id);
            part_id = label_id.substr(label_id.indexOf("-") + 1); // => "1_1"
            $("#article-" + part_id).show();
            $("#article_input-" + part_id).hide();
        });
        $(document).on("click", "span.video_title_span", function() {
            var label_id = $(this).attr("id");

            part_id = label_id.substr(label_id.indexOf("-") + 1); // => "1_1"
            $("#video-" + part_id).show();
            $("#video_input-" + part_id).hide();
        });
        $(document).on("click", "button.btn_remove_video", function() {
            var label_id = $(this).attr("id");

            part_id = label_id.substr(label_id.indexOf("-") + 1); // => "1_1"
            $("#kt_uppy-" + part_id).show();
            $("#video_div_retrieve-" + part_id).show();
            $("#video_div_remove-" + part_id).hide();
        });
        $(document).on("click", "button.btn_retrieve_video", function() {
            var label_id = $(this).attr("id");
            part_id = label_id.substr(label_id.indexOf("-") + 1); // => "1_1"
            $("#kt_uppy-" + part_id).hide();
            $("#video_div_retrieve-" + part_id).hide();
            $("#video_div_remove-" + part_id).show();
        });
        $(document).on("click", "ul.section_content", function() {
            var li_id = $(this).attr("id");
            // $("#"+li_id+" li").not(":eq(0)").hide();
            $("#" + li_id).hide();
        });
        $(document).on("click", "a.remove_video_con_1", function() {
            var li_id = $(this).attr("id");
            // $("#"+li_id+" li").not(":eq(0)").hide();
            var div_id = li_id.substr(li_id.indexOf("-") + 1);
            var content = li_id.substr(li_id.indexOf("_") + 1);
            content = content.substr(0, content.indexOf("-"));
            if (content === "video") {
                var video_id = $("#video_id-" + div_id).val();
                removeVideoAjax(video_id, div_id);
            } else if (content === "article") {
                var article_id = $("#article_id-" + div_id).val();
                removeArticleAjax(article_id, div_id);
            } else {
                alert("quiz");
            }

        });

        $(document).on("click", function(e) {
            if ($(e.target).closest(".section_name_div-" + section_div_id).length === 0) {
                $("#section_name-" + section_div_id).hide();
                $(".section_name_muted-" + section_div_id).hide();
                $("#section_name_label-" + section_div_id).html($("#section_name-" + section_div_id).val());
                $("#section_name_label-" + section_div_id).show();
            }
            if ($(e.target).closest(".section_objective_div-" + section_div_id).length === 0) {
                $("#objective-" + section_div_id).hide();
                $(".section_objective_muted-" + section_div_id).hide();
                $("#section_objective_label-" + section_div_id).html($("#objective-" + section_div_id).val());
                $("#section_objective_label-" + section_div_id).show();
            }
            if ($(e.target).closest(".article_title_div-" + part_id).length === 0) {
                $("#article-" + part_id).hide();
                $("#article_input-" + part_id).html($("#article-" + part_id).val());
                $("#article_label-" + part_id).html($("#article-" + part_id).val());
                $("#article_input-" + part_id).show();
            }
            if ($(e.target).closest(".video_title_div-" + part_id).length === 0) {
                $("#video-" + part_id).hide();
                $("#video_input-" + part_id).html($("#video-" + part_id).val());
                $("#video_label-" + part_id).html($("#video-" + part_id).val());
                $("#video_input-" + part_id).show();
            }
        });


        //For the summernote
        $(document).on("click", "div.about_group_", function(e) {
            var div_id = $(this).attr("id");
            var part_section_id = div_id.substr(div_id.indexOf("-") + 1); // => "Tabs1"
            // var parent = $("#"+div_id).find("*").toArray();
            div_textarea = "textarea_about-" + part_section_id;
            textarea = "about-" + part_section_id
            box = "div_about-" + part_section_id;
            console.log(parent);
            $("#" + div_textarea).show();
            $("#" + box).hide();
            $('textarea#' + textarea).summernote({
                height: 80,
                toolbar: toolbar_show,
            });
        });
        $(document).on("click", function(e) {
            // alert(textarea);
            if ($(e.target).closest(".about_group_").length === 0) {
                $("#" + box).html($.trim($("#" + textarea).val())).show();
                $("#" + div_textarea).hide();
            }
        });

        //Change in Section 
        $('#ul_list').on("click", ".section_link", function() {
            var global = $(this).attr("id");
            global_video_id = "kt_uppy-" + global + "_1";
            section_id = global;
            create_video_id = "create_video-" + global;
            FormDesignContent.init();
            FormDesignVideo.init();
            FormDesignArticle.init();
            $(document).on('click', function(e) {
                if ($(e.target).closest("#about_group-" + global).length === 0) {
                    $("#div_about-" + global).html($.trim($("#about-" + global).val())).show();
                    $("#textarea_about-" + global).hide();
                }
            });
            $("#about_group-" + global).click(function() {
                $("#textarea_about-" + global).show();
                $("#div_about-" + global).hide();
            });
            KTUppy.init();
            checkSectionDetails(section_id)
            hoverAccordion();
            hoverRemove();
        });

        //Cloning the Part
        $("#tab_content").on('click', "a.create_video", function() {
            var id = section_id;
            var getCountClonePart = $('.part_count-' + id).length + 1;
            var clone = $('#part_clone_hidden').clone();
            //main part 
            clone.attr("id", "part_clone-" + id + "_" + getCountClonePart);
            clone.attr("class", "part_count-" + id + " part");
            //selection content part
            clone.find(".part_count_hidden").attr("class", "kt-portlet__head-toolbar part_count-" + id + "_" + getCountClonePart + " selection");
            clone.find("#selection_tab_hidden").attr("id", "selection_tab-" + id + "_" + getCountClonePart);
            clone.find("#ul_list_hidden").attr("id", "ul_list-" + id + "_" + getCountClonePart);
            clone.find("#add_video_parent_hidden").attr("id", "add_video-" + id + "_" + getCountClonePart);
            clone.find("#create_video_hidden").attr("id", "create_video-" + id + "_" + getCountClonePart);
            clone.find("#video_tab_hidden").attr("href", "#video_content-" + id + "_" + getCountClonePart);
            clone.find("#article_tab_hidden").attr("href", "#article_content-" + id + "_" + getCountClonePart);
            clone.find("#quiz_tab_hidden").attr("href", "#quiz_content-" + id + "_" + getCountClonePart);
            clone.find("#video_tab_hidden").attr("id", "video_tab-" + id + "_" + getCountClonePart);
            clone.find("#article_tab_hidden").attr("id", "article_tab-" + id + "_" + getCountClonePart);
            clone.find("#quiz_tab_hidden").attr("id", "quiz_tab-" + id + "_" + getCountClonePart);
            //article content part
            clone.find("#remove_content_part_article_div_hidden").attr("id", "remove_content_part_article_div-" + id + "_" + getCountClonePart);
            clone.find("#accordionArticle_hidden").attr("id", "accordionArticle-" + id + "_" + getCountClonePart);
            clone.find("#headingArticle_hidden").attr("id", "headingArticle-" + id + "_" + getCountClonePart);
            clone.find(".article_title_div_hidden").attr("class", "kt-typeahead article_title_div-" + id + "_" + getCountClonePart);
            clone.find(".collapsy_article").attr("data-target", "#collapse_article-" + id + "_" + getCountClonePart);
            clone.find(".collapsy_article").attr("aria-controls", "collapse_article-" + id + "_" + getCountClonePart);
            clone.find("#article_label_hidden").attr("id", "article_label-" + id + "_" + getCountClonePart);
            clone.find("#collapse_article_hidden").attr("id", "collapse_article-" + id + "_" + getCountClonePart);
            clone.find("#collapse_article_hidden").attr("aria-labelledby", "headingArticle-" + id + "_" + getCountClonePart);
            clone.find("#collapse_article_hidden").attr("data-parent", "#accordionArticle-" + id + "_" + getCountClonePart);
            clone.find("#article_content_hidden").attr("id", "article_content-" + id + "_" + getCountClonePart);
            clone.find("#user_add_article_title_form_hidden").attr("id", "user_add_article_title_form-" + id + "_" + getCountClonePart);
            clone.find("#removePart_article_hidden").attr("id", "removePart_article-" + id + "_" + getCountClonePart);
            clone.find("#article_hidden").attr("id", "article-" + id + "_" + getCountClonePart);
            clone.find("#btn_article_form_hidden").attr("id", "btn_article_form-" + id + "_" + getCountClonePart);
            clone.find("#submit_article_form_hidden").attr("id", "submit_article_form-" + id + "_" + getCountClonePart);
            clone.find("#user_add_article_body_form_hidden").attr("id", "user_add_article_body_form-" + id + "_" + getCountClonePart);
            clone.find("#about_group_hidden").attr("id", "about_group-" + id + "_" + getCountClonePart);
            clone.find("#article_label_title_hidden").attr("id", "article_label_title-" + id + "_" + getCountClonePart);
            clone.find("#article_id_hidden").attr("id", "article_id-" + id + "_" + getCountClonePart);
            clone.find("#textarea_about_hidden").attr("id", "textarea_about-" + id + "_" + getCountClonePart);
            clone.find("#about_hidden").attr("id", "about-" + id + "_" + getCountClonePart);
            clone.find("#div_about_hidden").attr("id", "div_about-" + id + "_" + getCountClonePart);
            clone.find("#btn_article_body_form_hidden").attr("id", "btn_article_body_form-" + id + "_" + getCountClonePart);
            clone.find("#submit_article_body_form_hidden").attr("id", "submit_article_body_form-" + id + "_" + getCountClonePart);
            clone.find("#article_input_hidden").attr("id", "article_input-" + id + "_" + getCountClonePart);
            //video content part
            clone.find("#remove_content_part_video_div_hidden").attr("id", "remove_content_part_video_div-" + id + "_" + getCountClonePart);
            clone.find("#kt_uppy_hidden").attr("class", "kt-uppy video_identification-" + id);
            clone.find("#kt_uppy_hidden").attr("id", "kt_uppy-" + id + "_" + (getCountClonePart));
            global_video_id = "kt_uppy-" + id + "_" + (getCountClonePart);
            clone.find("#video_hidden").attr("id", "video-" + id + "_" + getCountClonePart);
            clone.find(".video_title_div_hidden").attr("class", "kt-typeahead video_title_div-" + id + "_" + getCountClonePart);
            clone.find("#video_cdn_hidden").attr("id", "video_cdn-" + id + "_" + getCountClonePart);
            clone.find("#video_filename_hidden").attr("id", "video_filename-" + id + "_" + getCountClonePart);
            clone.find("#video_size_hidden").attr("id", "video_size-" + id + "_" + getCountClonePart);
            clone.find("#removePart_video_hidden").attr("id", "removePart_video-" + id + "_" + getCountClonePart);
            clone.find("#video_length_hidden").attr("id", "video_length-" + id + "_" + getCountClonePart);
            clone.find("#video_poster_hidden").attr("id", "video_poster-" + id + "_" + getCountClonePart);
            clone.find("#video_thumbnail_hidden").attr("id", "video_thumbnail-" + id + "_" + getCountClonePart);
            clone.find("#video_resolution_hidden").attr("id", "video_resolution-" + id + "_" + getCountClonePart);
            clone.find("#video_id_hidden").attr("id", "video_id-" + id + "_" + getCountClonePart);
            clone.find("#video_input_hidden").attr("id", "video_input-" + id + "_" + getCountClonePart);
            clone.find("#video_content_hidden").attr("id", "video_content-" + id + "_" + getCountClonePart);
            clone.find("#user_add_video_form_hidden").attr("id", "user_add_video_form-" + id + "_" + getCountClonePart);
            clone.find("#accordionVideo_hidden").attr("id", "accordionVideo-" + id + "_" + getCountClonePart);
            clone.find("#headingVideo_hidden").attr("id", "headingVideo-" + id + "_" + getCountClonePart);
            clone.find(".collapsy_video").attr("data-target", "#collapse_video-" + id + "_" + getCountClonePart);
            clone.find(".collapsy_video").attr("aria-controls", "collapse_video-" + id + "_" + getCountClonePart);
            clone.find("#video_label_hidden").attr("id", "video_label-" + id + "_" + getCountClonePart);
            clone.find("#collapse_video_hidden").attr("id", "collapse_video-" + id + "_" + getCountClonePart);
            clone.find("#collapse_video_hidden").attr("aria-labelledby", "headingVideo-" + id + "_" + getCountClonePart);
            clone.find("#collapse_video_hidden").attr("data-parent", "#accordionVideo-" + id + "_" + getCountClonePart);
            clone.find("#video_div_retrieve_hidden").attr("id", "video_div_retrieve-" + id + "_" + getCountClonePart);
            clone.find("#btn_retrieve_video_hidden").attr("id", "btn_retrieve_video-" + id + "_" + getCountClonePart);
            clone.find("#video_div_remove_hidden").attr("id", "video_div_remove-" + id + "_" + getCountClonePart);
            clone.find("#video_exist_hidden").attr("id", "video_exist-" + id + "_" + getCountClonePart);
            clone.find("#btn_remove_video_hidden").attr("id", "btn_remove_video-" + id + "_" + getCountClonePart);
            // clone.find("#section_id_hidden").attr("id", "#section_id_" + id + "_" + getCountClonePart);
            clone.find("#submit_video_form_hidden").attr("id", "submit_video_form-" + id + "_" + getCountClonePart);
            //clone.find("#selection_tab_hidden").attr("id","selection_tab_"+section_id+"_"+getCountClonePart);
            clone.appendTo("#section-" + id);
            console.log(clone.find("#part_clone_hidden").attr("id", "part_clone-" + id + "_" + getCountClonePart));
            clone.show();
            KTUppy.init();
            hoverAccordion();
            hoverRemove();
        });

        //Cloning for the section
        $('#add_section').click(function() {
            var li_count = $('.nav-item').length;
            $("#ul_list").append(' <li class="nav-item hidden_tab"><a class="nav-link section_link" data-toggle="tab" id="' + li_count + '" href="#kt_tabs_7-' + li_count + '" role="tab">Section ' + li_count + ' </a></li>');
            var clone = $("#kt_tabs_hidden").clone();
            //Main Section
            clone.attr("style", "display:absolute;");
            clone.attr("id", "kt_tabs_7-" + li_count)
            clone.find("#section_hidden").attr("id", "section-" + li_count);
            clone.find("#user_add_content_form_hidden").attr("id", "user_add_content_form-" + li_count);
            clone.find("#section_name_hidden").attr("id", "section_name-" + li_count);
            clone.find("#objective_hidden").attr("id", "objective-" + li_count);
            clone.find("#submit_content_form_hidden").attr("id", "submit_content_form-" + li_count);
            clone.find("#objective_hidden").attr("id", "objective-" + li_count);
            clone.find("#part_clone_hidden").attr("id", "part_clone-" + li_count + "_1");
            clone.find(".part_count_hidden").attr("class", "part_count-" + li_count + " part");
            clone.find("#section_id_hidden").attr("id", "section_id-" + li_count);
            clone.find(".section_objective_div_hidden").attr("class", "kt-typeahead section_objective_div-" + li_count);
            clone.find(".section_name_div_hidden").attr("class", "kt-typeahead section_name_div-" + li_count);
            clone.find("#section_name_label_hidden").attr("id", "section_name_label-" + li_count);
            clone.find("#section_objective_label_hidden").attr("id", "section_objective_label-" + li_count);
            //selection content part
            clone.find(".part_count_hidden").attr("class", "kt-portlet__head-toolbar part_count-" + li_count + "_1 selection part");
            clone.find("#selection_tab_hidden").attr("id", "selection_tab-" + li_count + "_1");
            clone.find("#ul_list_hidden").attr("id", "ul_list-" + li_count + "_1");
            clone.find("#add_video_parent_hidden").attr("id", "add_video-" + li_count + "_1");
            clone.find("#create_video_hidden").attr("id", "create_video-" + li_count + "_1");
            clone.find("#video_tab_hidden").attr("href", "#video_content-" + li_count + "_1");
            clone.find("#article_tab_hidden").attr("href", "#article_content-" + li_count + "_1");
            clone.find("#quiz_tab_hidden").attr("href", "#quiz_content-" + li_count + "_1");
            clone.find("#video_tab_hidden").attr("id", "video_tab-" + li_count + "_1");
            clone.find("#article_tab_hidden").attr("id", "article_tab-" + li_count + "_1");
            clone.find("#quiz_tab_hidden").attr("id", "quiz_tab-" + li_count + "_1");
            //article content part
            clone.find("#remove_content_part_article_div_hidden").attr("id", "remove_content_part_article_div-" + li_count + "_1");
            clone.find("#removePart_article_hidden").attr("id", "removePart_article-" + li_count + "_1");
            clone.find("#accordionArticle_hidden").attr("id", "accordionArticle-" + li_count + "_1");
            clone.find("#headingArticle_hidden").attr("id", "headingArticle-" + li_count + "_1");
            clone.find(".article_title_div_hidden").attr("class", "kt-typeahead article_title_div-" + li_count + "_1");
            clone.find(".collapsy_article").attr("data-target", "#collapse_article-" + li_count + "_1");
            clone.find(".collapsy_article").attr("aria-controls", "collapse_article-" + li_count + "_1");
            clone.find("#article_label_hidden").attr("id", "article_label-" + li_count + "_1");
            clone.find("#collapse_article_hidden").attr("id", "collapse_article-" + li_count + "_1");
            clone.find("#collapse_article_hidden").attr("aria-labelledby", "headingArticle-" + li_count + "_1");
            clone.find("#collapse_article_hidden").attr("data-parent", "#accordionArticle-" + li_count + "_1");
            clone.find("#article_content_hidden").attr("id", "article_content-" + li_count + "_1");
            clone.find("#user_add_article_title_form_hidden").attr("id", "user_add_article_title_form-" + li_count + "_1");
            clone.find("#article_hidden").attr("id", "article-" + li_count + "_1");
            clone.find("#btn_article_form_hidden").attr("id", "btn_article_form-" + li_count + "_1");
            clone.find("#submit_article_form_hidden").attr("id", "submit_article_form-" + li_count + "_1");
            clone.find("#user_add_article_body_form_hidden").attr("id", "user_add_article_body_form-" + li_count + "_1");
            clone.find("#about_group_hidden").attr("id", "about_group-" + li_count + "_1");
            clone.find("#article_label_title_hidden").attr("id", "article_label_title-" + li_count + "_1");
            clone.find("#article_id_hidden").attr("id", "article_id-" + li_count + "_1");
            clone.find("#textarea_about_hidden").attr("id", "textarea_about-" + li_count + "_1");
            clone.find("#about_hidden").attr("id", "about-" + li_count + "_1");
            clone.find("#div_about_hidden").attr("id", "div_about-" + li_count + "_1");
            clone.find("#btn_article_body_form_hidden").attr("id", "btn_article_body_form-" + li_count + "_1");
            clone.find("#submit_article_body_form_hidden").attr("id", "submit_article_body_form-" + li_count + "_1");
            clone.find("#article_input_hidden").attr("id", "article_input-" + li_count + "_1");
            //video content part
            clone.find("#remove_content_part_video_div_hidden").attr("id", "remove_content_part_video_div-" + li_count + "_1");
            clone.find("#removePart_video_hidden").attr("id", "removePart_video-" + li_count + "_1");
            clone.find("#kt_uppy_hidden").attr("class", "kt-uppy video_identification-" + li_count);
            clone.find("#kt_uppy_hidden").attr("id", "kt_uppy-" + li_count + "_1");
            clone.find("#video_cdn_hidden").attr("id", "video_cdn-" + li_count + "_1");
            clone.find(".video_title_div_hidden").attr("class", "kt-typeahead video_title_div-" + li_count + "_1");
            clone.find("#video_filename_hidden").attr("id", "video_filename-" + li_count + "_1");
            clone.find("#video_size_hidden").attr("id", "video_size-" + li_count + "_1");
            clone.find("#video_length_hidden").attr("id", "video_length-" + li_count + "_1");
            clone.find("#video_poster_hidden").attr("id", "video_poster-" + li_count + "_1");
            clone.find("#video_thumbnail_hidden").attr("id", "video_thumbnail-" + li_count + "_1");
            clone.find("#video_resolution_hidden").attr("id", "video_resolution-" + li_count + "_1");
            clone.find("#video_id_hidden").attr("id", "video_id-" + li_count + "_1");
            clone.find("#video_hidden").attr("id", "video-" + li_count + "_1");
            clone.find("#video_input_hidden").attr("id", "video_input-" + li_count + "_1");
            clone.find("#video_content_hidden").attr("id", "video_content-" + li_count + "_1");
            clone.find("#user_add_video_form_hidden").attr("id", "user_add_video_form-" + li_count + "_1");
            clone.find("#accordionVideo_hidden").attr("id", "accordionVideo-" + li_count + "_1");
            clone.find("#headingVideo_hidden").attr("id", "headingVideo-" + li_count + "_1");
            clone.find(".collapsy_video").attr("data-target", "#collapse_video-" + li_count + "_1");
            clone.find(".collapsy_video").attr("aria-controls", "collapse_video-" + li_count + "_1");
            clone.find("#video_label_hidden").attr("id", "video_label-" + li_count + "_1");
            clone.find("#collapse_video_hidden").attr("id", "collapse_video-" + li_count + "_1");
            clone.find("#collapse_video_hidden").attr("aria-labelledby", "headingVideo-" + li_count + "_1");
            clone.find("#collapse_video_hidden").attr("data-parent", "#accordionVideo-" + li_count + "_1");
            clone.find("#section_id_hidden").attr("id", "section_id-" + li_count + "_1");
            clone.find("#submit_video_form_hidden").attr("id", "submit_video_form-" + li_count + "_1");
            clone.find("#video_div_retrieve_hidden").attr("id", "video_div_retrieve-" + li_count + "_1");
            clone.find("#btn_retrieve_video_hidden").attr("id", "btn_retrieve_video-" + li_count + "_1");
            clone.find("#video_div_remove_hidden").attr("id", "video_div_remove-" + li_count + "_1");
            clone.find("#video_exist_hidden").attr("id", "video_exist-" + li_count + "_1");
            clone.find("#btn_remove_video_hidden").attr("id", "btn_remove_video-" + li_count + "_1");
            clone.appendTo("#tab_content");
            global_video_id = 'kt_uppy-' + li_count + '_1';
            KTUppy.init();
            hoverAccordion();
            hoverRemove();
        });

        //Summernote Bug Error
        $('form').each(function() {
            if ($(this).data('validator'))
                $(this).data('validator').settings.ignore = ".note-editor *";
        });
        checkSectionDetails(section_id);
        hoverAccordion();
        hoverRemove();

        //Eto function is for generating uppy ui pag may data na i-loop
        <?php
        if (count($sections) > 0) {
            foreach ($sections as $section) {
                if (count(_get_video_and_content_part($data->id, $section->id)) > 0) {
                    foreach (_get_video_and_content_part($data->id, $section->id) as $key => $content) {
                        echo "global_video_id='kt_uppy-" . $section->section_number . "_" . ($key+1) . "';";
                        echo "KTUppy.init();";
                    }
                }
            }
        }
        ?>
    });
    //For deleting part on hover
    function hoverRemove() {
        $("div.accordion_header_part").hover(function() {
            var div_id = $(this).attr("id");
            var li_id = div_id.substr(div_id.indexOf("-") + 1);
            var part_type = div_id.substr(0, div_id.indexOf("-"));
            if (part_type === "headingVideo") {
                $("#removePart_video-" + li_id).show();
            } else if (part_type === "headingArticle") {
                $("#removePart_article-" + li_id).show();
            } else {
                $("#removePart_quiz-" + li_id).show();
            }
        }, hover_out_remove);
    }
    //For selecting content part hover
    function hoverAccordion() {
        $("div.part_count-" + section_id).hover(function() {
            var div_id = $(this).attr("id");
            var li_id = div_id.substr(div_id.indexOf("-") + 1);
            $("#ul_list-" + li_id).attr("style", "display:absolute;");
        }, hover_out);
    }
    function hover_out() {
        var div_id = $(this).attr("id");
        var li_id = div_id.substr(div_id.indexOf("-") + 1);
        var find_active = $("#ul_list-" + li_id).find(".active");
        if (find_active == 1) {
            $("#ul_list-" + li_id).attr("style", "display:none;");
        }

    }
    function hover_out_remove() {
        var div_id = $(this).attr("id");
        var li_id = div_id.substr(div_id.indexOf("-") + 1);
        var part_type = div_id.substr(0, div_id.indexOf("-"));
        if (part_type === "headingVideo") {
            $("#removePart_video-" + li_id).hide();
        } else if (part_type === "headingArticle") {
            $("#removePart_article-" + li_id).hide();
        } else {
            $("#removePart_quiz-" + li_id).hide();
        }
    }
    function checkSectionDetails(section_id) {
        if ($("#section_name-" + section_id).val() != "") {
            $("#part_clone-" + section_id + "_1").show();
        } else {
            $("#part_clone-" + section_id + "_1").hide();
        }
    }
    function sortable_section(section_id) {
        $("#section-" + section_id).sortable();
        $("#section-" + section_id).disableSelection();

    }
    //For Deleting part ( UI and DB )
    function removeVideoAjax(id, div_id) {
        if (id != "") {
            $.ajax({
                url: '/course_management/api/delete_video_part',
                method: 'post',
                data: {
                    video_id: id,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.status == 200) {
                        toastr.success('Success!', response.message + '. Thank you!');
                        $("#part_clone-" + div_id).remove();
                    } else {
                        toastr.error('Course Incomplete!', "List of Incomplete Details: " + response.message + ', please complete it first. Thank you!');
                    }
                }
            });
        } else {
            $("#part_clone-" + div_id).remove();
        }

    }
    //For Deleting part ( UI and DB )
    function removeArticleAjax(id, div_id) {
        $.ajax({
            url: '/course_management/api/delete_article_part',
            method: 'post',
            data: {
                article_id: id,
                _token: "{{ csrf_token() }}",
            },
            success: function(response) {
                if (response.status == 200) {
                    toastr.success('Success!', response.message + '. Thank you!');
                    $("#part_clone-" + div_id).remove();
                } else {
                    toastr.error('Course Incomplete!', "List of Incomplete Details: " + response.message + ', please complete it first. Thank you!');
                }
            }
        });
    }
    //Validator and Submit for the Section
    var FormDesignContent = function() {

        var input_validations = function() {
            validator = $("#" + form_id).validate({
                // define validation rules
                rules: {
                    section_name: {
                        required: true,
                        maxlength: 80,
                    },
                    objective: {
                        required: true,
                        maxlength: 200,
                    }

                },

                //display error alert on form submit  
                invalidHandler: function(event, validator) {
                    var alert = $('#form_msg');
                    alert.removeClass('kt-hidden').show();
                    KTUtil.scrollTop();
                },

                submitHandler: function(form) {

                    $("#" + submit_form_id).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                    var submit_form = $.ajax({
                        url: '/course_management/video_and_content/storeSection',
                        type: 'POST',
                        data: {
                            section_name: $("#section_name-" + section_id).val(),
                            objective: $("#objective-" + section_id).val(),
                            section_id: section_id,
                            _token: $('input[name*="_token"]').val(),
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                toastr.success('Success!', response.message + '. Thank you!');
                                $("#submit_content_form_" + section_id).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                $("#section_id_" + section_id).val(response.section_id);

                                checkSectionDetails(section_id);
                                // setTimeout(() => {
                                //     window.location = "/course_management/submit_for_accreditation";
                                // }, 1000);
                            } else {
                                $("#" + submit_form_id).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                toastr.error('Error!', response.message);
                            }
                        },
                        error: function(response) {
                            const body = response.responseJSON;

                            $("#" + submit_form_id).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                            toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');

                        }
                    });
                }
            });
        }

        return {
            init: function() {
                input_validations();
            }
        };
    }();
    //Validator and Submit for the Video Part
    var FormDesignVideo = function() {

        var input_validations = function() {
            validator = $("#" + form_id).validate({
                // define validation rules
                rules: {
                    video_name: {
                        required: true,
                    },
                    video: {
                        required: true,
                    }
                },

                //display error alert on form submit  
                invalidHandler: function(event, validator) {
                    var alert = $('#form_msg');
                    section_part_id = form_id.substr(form_id.indexOf("-") + 1); // => "Tabs1"
                    $("#video_error-" + section_part_id).remove();
                    $.each(validator.invalid, function(index, value) {
                        if (index == "video") {
                            console.log("#kt_uppy-" + section_part_id);
                            $("#kt_uppy-" + section_part_id).append('<span  id="video_error-' + section_part_id + '" style="color:red;">Video is required!</span>');
                        }
                    });
                    alert.removeClass('kt-hidden').show();
                },
                submitHandler: function(form) {
                    var part_section_id = submit_form_id.substr(submit_form_id.indexOf("-") + 1);
                    var sequence_number = form_id.substr(form_id.lastIndexOf("_") + 1);
                    $("#" + submit_form_id).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                    var submit_form = $.ajax({
                        url: '/course_management/video_and_content/storeVideo',
                        type: 'POST',
                        data: {
                            video: $("#video_cdn-" + part_section_id).val(),
                            filename: $("#video_filename-" + part_section_id).val(),
                            filesize: $("#video_size-" + part_section_id).val(),
                            length: $("#video_length-" + part_section_id).val(),
                            poster: $("#video_poster-" + part_section_id).val(),
                            thumbnail: $("#video_thumbnail-" + part_section_id).val(),
                            resolution: $("#video_resolution-" + part_section_id).val(),
                            video_id: $("#video_id-" + part_section_id).val(),
                            section_id: $("#section_id-" + section_id).val(),
                            section_number: section_id,
                            video_name: $("#video-" + part_section_id).val(),
                            sequence_number: sequence_number,
                            _token: $('input[name*="_token"]').val(),
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                toastr.success('Success!', response.message + '. Thank you!');
                                $("#" + submit_form_id).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                $("#video_div_remove-" + part_section_id).show();
                                $("#kt_uppy-" + part_section_id).hide();
                                $("#btn_remove_video-" + part_section_id).html("<i class='fa fa-retweet'></i>Change File");
                                $("#video_exist-" + part_section_id).html($("#video-" + part_section_id).val());
                                $("#ul_list-" + part_section_id + " li").not(":eq(0)").hide();
                            } else {
                                $("#" + submit_form_id).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                toastr.error('Error!', response.message);
                            }
                        },
                        error: function(response) {
                            const body = response.responseJSON;

                            $("#" + submit_form_id).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                            toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');

                        }
                    });
                }
            });
        }
        return {
            init: function() {

                input_validations();
            }
        };
    }();
 //Validator and Submit for the Article Title
    var FormDesignArticle = function() {
        var input_validations = function() {
            validator = $("#" + form_id).validate({
                // define validation rules
                rules: {
                    article: {
                        required: true,
                        maxlength: 80,
                    }

                },
                invalidHandler: function(event, validator) {
                    var alert = $('#form_msg');
                    alert.removeClass('kt-hidden').show();
                },
                submitHandler: function(form) {
                    var form_value = $("#" + form_id).find("*").toArray();
                    var part_section = submit_form_id;
                    var part_section_id = part_section.substr(part_section.indexOf("-") + 1); // => "Tabs1"
                    var section_part_id = form_id.substr(form_id.indexOf("-") + 1);
                    var sequence_number = form_id.substr(form_id.lastIndexOf("_") + 1);

                    $("#" + submit_form_id).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');

                    var submit_form = $.ajax({
                        url: '/course_management/video_and_content/storeArticle',
                        type: 'POST',
                        data: {
                            section_id: $("#section_id-" + section_id).val(),
                            section_number: section_id,
                            article_title: $("#article-" + part_section_id).val(),
                            article_id: $("#article_id-" + part_section_id).val(),
                            sequence_number: sequence_number,
                            _token: $('input[name*="_token"]').val(),
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                toastr.success('Success!', response.message + '. Thank you!');
                                $("#" + submit_form_id).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                $("#article_input-" + part_section_id).val(response.article_title);
                                if ($("#article_id-" + part_section_id).val() === null || $("#article_id-" + part_section_id).val() === "") {
                                    $("#article_id-" + part_section_id).val(response.article_id);
                                }
                                $("#ul_list-" + part_section_id + " li").not(":eq(0)").hide();
                            } else {
                                $("#" + submit_form_id).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                toastr.error('Error!', response.message);
                            }
                        },
                        error: function(response) {
                            const body = response.responseJSON;

                            $("#" + submit_form_id).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                            toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');

                        }
                    });
                }
            });
        }

        return {
            init: function() {

                input_validations();
            }
        };
    }();
     //Validator and Submit for the Article Body
    var FormDesignArticleBody = function() {

        var input_validations = function() {
            validator = $("#" + form_id).validate({
                // define validation rules
                rules: {
                    about: {
                        required: true,
                    }
                },

                //display error alert on form submit  
                invalidHandler: function(event, validator) {
                    var alert = $('#form_msg');
                    alert.removeClass('kt-hidden').show();
                },

                submitHandler: function(form) {
                    form_value = $("#" + form_id).find("*").toArray();
                    var part_section = form_value[13].id;
                    var part_section_id = part_section.substr(part_section.indexOf("-") + 1); // => "Tabs1"
                    var sequence_number = form_id.substr(form_id.lastIndexOf("_") + 1);
                    $("#" + submit_form_id).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                    var submit_form = $.ajax({
                        url: '/course_management/video_and_content/storeArticle',
                        type: 'POST',
                        data: {
                            section_id: $("#section_id-" + section_id).val(),
                            section_number: section_id,
                            article_title: $("#article-" + part_section_id).val(),
                            article_id: $("#article_id-" + part_section_id).val(),
                            about: $("#" + form_value[14].id).val(),
                            sequence_number: sequence_number,
                            _token: $('input[name*="_token"]').val(),
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                toastr.success('Success!', response.message + '. Thank you!');
                                $("#" + submit_form_id).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                $("#ul_list-" + part_section_id + " li").not(":eq(0)").hide();
                            } else {
                                $("#" + submit_form_id).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                toastr.error('Error!', response.message);
                            }
                        },
                        error: function(response) {
                            const body = response.responseJSON;

                            $("#" + submit_form_id).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                            toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');

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


 //Uppy Function
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

        var initUppy3 = function() {
            var id = '#' + global_video_id;

            var uppyDrag = Uppy.Core({
                autoProceed: true,
                restrictions: {
                    maxFileSize: 1000000000000, // 1mb = 1000000
                    maxNumberOfFiles: 1,
                    minNumberOfFiles: 1,
                    allowedFileTypes: ['image/*', 'video/*']
                }
            });
            if ($(id + " .kt-uppy__drag button").length) {

            } else {
                uppyDrag.use(Uppy.DragDrop, {
                    target: id + ' .kt-uppy__drag'
                });

                uppyDrag.use(ProgressBar, {
                    target: id + ' .kt-uppy__progress',
                    hideUploadButton: false,
                    hideAfterFinish: false
                });
                $(".kt-uppy__progress .uppy-ProgressBar .uppy-ProgressBar-inner").css("height", "5px");

            }
            uppyDrag.use(Informer, {
                target: id + ' .kt-uppy__informer'
            });
            uppyDrag.use(XHRUpload, {
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

            uppyDrag.on('complete', function(file) {
                var imagePreview = "";
                console.log(file.successful);
                $.each(file.successful, function(index, value) {
                    var imageType = /image/;
                    var thumbnail = "";
                    if (imageType.test(value.type)) {
                        thumbnail = '<div class="kt-uppy__thumbnail"><img src="' + value.uploadURL + '"/></div>';
                    }
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

                    imagePreview += '<div class="kt-uppy__thumbnail-container" data-id="' + value.id + '">' + thumbnail + '<i class="fa fa-check-circle" style="color:#00b764;height:10px;"></i> <span class="kt-uppy__thumbnail-label">' + value.name + ' (' + Math.round(filesize, 2) + ' ' + sizeLabel + ')</span><span data-id="' + value.id + '" class="kt-uppy__remove-thumbnail"><i class="flaticon2-cancel-music"></i></span></div>';
                    // console.log(value.response.body.pathname);

                    var part_section_id = global_video_id.substr(global_video_id.indexOf("-") + 1);
                    $("#video_div_retrieve-" + part_section_id).hide();
                    $("#video_cdn-" + part_section_id).val(value.response.body.pathname);
                    $("#video_filename-" + part_section_id).val(value.response.body.filename);
                    $("#video_size-" + part_section_id).val(value.response.body.filesize);

                });
                if (file.failed) {
                    //console.log(file.failed);
                    $.each(file.failed, function(index, value) {
                        var imageType = /image/;
                        var thumbnail = "";
                        if (imageType.test(value.type)) {
                            thumbnail = '<div class="kt-uppy__thumbnail"><img src="' + value.uploadURL + '"/></div>';
                        }
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
                        imagePreview += '<div class="kt-uppy__thumbnail-container" data-id="' + value.id + '">' + thumbnail + '<i class="fa fa-exclamation-circle" style="color:red;height:10px;"></i> <span class="kt-uppy__thumbnail-label" style="color:red;">' + value.name + ' (' + Math.round(filesize, 2) + ' ' + sizeLabel + ')</span><span data-id="' + value.id + '" class="kt-uppy__remove-thumbnail"><i class="flaticon2-cancel-music"></i></span></div>';
                    });
                }

                $(id + ' .kt-uppy__thumbnails').append(imagePreview);
            });



            $(document).on('click', id + ' .kt-uppy__thumbnails .kt-uppy__remove-thumbnail', function() {
                var imageId = $(this).attr('data-id');

                uppyDrag.removeFile(imageId);
                $(id + ' .kt-uppy__thumbnail-container[data-id="' + imageId + '"').remove();

            });
        }

        return {
            // public functions
            init: function() {
                initUppy3();

            }
        };
    }();
</script>
@endsection