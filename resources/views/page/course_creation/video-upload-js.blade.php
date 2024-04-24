@extends('template.master_course_creation')
 
@section('styles')
<style>
    .accordion.accordion-solid .card .card-header .card-title{background-color:white;border:1px solid #ebedf2;}
    .part{margin-bottom:15px;}
    .part .selection .nav li.nav-items{ margin-right:10px;}
    .part-video-btn-div button{margin-right:15px;}
    .box{padding: 5px;border:1px solid #e2e5ec;border-radius:5px; min-height:120px}
    .question-items{padding:10px;border-radius:3px;}
    .question-items:hover{background:#dedfe5;}
    .video-thumbnail{height:200px;border:1px solid #F1F2F7;border-radius:5px;-webkit-box-shadow: 4px 4px 10px -9px rgba(0,0,0,0.49);-moz-box-shadow: 4px 4px 10px -9px rgba(0,0,0,0.49);box-shadow: 4px 4px 10px -9px rgba(0,0,0,0.49);}

    ul.nav-pills li a.nav-link:hover{background-color:rgba(0,0,0,0.2)}
    ul.left-floated-tabbuttons{position: absolute;left: 0;margin-top: -10;}
    ul.left-floated-tabbuttons--down{position: absolute;left: 0;margin-top: -40;}
    .selection-bottom-margin{margin-bottom:50px;margin-top:10px}
    span.video-failed-span{font-weight:600;color:#FD1361 !important;}


    .drag-drop-video-wrapper{position: relative;border: 4px dashed #3C6EC6;border-radius:5px;width:100%;height:200px;text-align: center;}
    .drag-drop-video-input{opacity: 0.0;-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";filter: alpha(opacity=0);-moz-opacity: 0.0;-khtml-opacity: 0.0;position: absolute;top: 0;left: 0;bottom: 0;right: 0;width: 100%;height:100%;}
    .video-progress-span-update{font-weight:700;}
</style> 
@endsection

@section('content')
{{ csrf_field() }}
<input type="hidden" name="digest" value="<?=_current_provider()->id.":"._current_course()->id.":".uniqid()?>" />
<input type="hidden" name="s3bname" value="<?=Storage::disk('s3')->getDriver()->getAdapter()->getBucket()?>" />
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Video & Content 
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <!-- Start of the main body -->
        <div class="kt-portlet" style="-webkit-box-shadow:none;box-shadow:none;">
            <!-- Section Body -->
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-toolbar">
                    <ul class="nav nav-pills nav-pills-sm " id="section-tablist" role="tablist"></ul>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="tab-content" id="tab_content">
                    <!-- begin:: Hidden Section for Clone -->
                    <div class="tab-pane" id="hidden-section-clone" role="tabpanel">
                        <div class="kt-portlet__body" id="hidden-section-body">
                            <!-- begin::Section Form -->
                            <form class="kt-form kt-form--label-left" id="hidden-section-form">
                                <div class="kt-form__content">
                                    <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="hidden-section-form-alert">
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
                                {{ csrf_field() }}
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Section Name <span class="required">*<span></label>
                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                        <div class="kt-typeahead section-name-div">
                                            <input class="form-control" type="text" name="section_name" id="hidden-section-name-input" style="display:none;" placeholder="Introduction">
                                            <span class="form-control section-name-span" id="hidden-section-name-label">Introduction</span>
                                        </div>
                                        <span class="form-text text-muted hidden-section-name-muted" style="display:none;">The maximum characters allowed is 80 </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Objective <span class="required">*<span></label>
                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                        <div class="kt-typeahead section-objective-div">
                                            <input class="form-control" type="text" name="objective" id="hidden-section-objective-input" style="display:none;"placeholder="Objectives">
                                            <span class="form-control section-ojective-span" id="hidden-section-objective-label">Objective</span>
                                        </div>
                                        <span class="form-text text-muted hidden-section-objective-muted" style="display:none;">The maximum characters allowed is 200 </span>
                                    </div>
                                </div>
                                <input type="hidden" name="section_number" id="hidden-section-number" />
                                <div class="form-group row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 ml-lg-auto" style="text-align:center;">
                                        <button class="btn btn-success submit_section" id="hidden-section-form-submit">Save Section</button> &nbsp; &nbsp;
                                        <button type="button" class="btn btn-danger remove_section" id="hidden-section-form-remove"><i class="fa fa-trash"></i> Remove</button>
                                    </div>
                                </div>
                            </form>
                            <!-- end::Section Form -->

                            <!-- begin::Video Article Quiz Content -->
                            <div id="hidden-part-clone" class="part" style="display:none;">
                                <!-- begin:: APPEND BEFORE Selection of ADD, VIDEO, Article, & QUIZ -->
                                <div class="kt-portlet__head-toolbar selection">
                                    <ul class="nav nav-pills nav-pills-sm left-floated-tabbuttons" role="tablist">
                                        <li class="nav-items">
                                            <a class="btn btn-icon btn-bold btn-brand button-add-part">
                                                <i class="fa fa-plus-circle"></i>
                                            </a>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links button-add-video" href="#hidden-part-video-content" data-toggle="tab" role="tab">
                                                Video
                                            </a>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links button-add-article" href="#hidden-part-article-content" data-toggle="tab" role="tab">
                                                Article
                                            </a>
                                        </li>
                                        <li class="nav-items">
                                            <a class="nav-link section_links button-add-quiz" href="#hidden-part-quiz-content" data-toggle="tab" role="tab">
                                                Quiz
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- end:: APPEND BEFORE Selection of ADD, VIDEO, Article, & QUIZ -->

                                <!-- begin:: content for chosen part  -->
                                <div class="tab-content" id="hidden-part-content">

                                    <!-- begin:: Video Tab -->
                                    <div class="tab-pane" id="hidden-part-video-content" role="tabpanel">
                                        <div class="accordion accordion-solid accordion-svg-icon" id="hidden-part-video-accordion">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title" id="hidden-part-video-card-title" data-toggle="collapse" data-target="#hidden-part-video-collapse" aria-expanded="true">
                                                        <div style="float:left;width:40%;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                </g>
                                                            </svg><span id="part-video-title">Video</span>
                                                        </div>
                                                        <div style="float:right;margin-left:auto;">
                                                            <a class="btn btn-icon btn-bold btn-label-danger button-remove-part">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="hidden-part-video-collapse" class="collapse show" data-parent="#hidden-part-video-accordion">
                                                    <div class="card-body">
                                                        <form class="kt-form kt-form--label-left" id="hidden-part-video-form">
                                                            <div class="kt-form__content">
                                                                <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="hidden-part-video-form-alert">
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
                                                            {{ csrf_field() }}
                                                            <div class="form-group row" style="padding-top:10px; padding-bottom:10px;">
                                                                <label class="col-form-label col-lg-3 col-sm-12">Video Title <span class="required">*<span></label>
                                                                <div class="col-lg-6 col-md-9 col-sm-12">
                                                                    <div class="kt-typeahead part-video-div">
                                                                        <input class="form-control" type="text" name="video_name" id="hidden-part-video-input" style="display:none;" placeholder="Video Title">
                                                                        <span class="form-control" id="hidden-part-video-label">Video Title</span>
                                                                    </div>
                                                                    <span class="form-text text-muted" id="hidden-part-video-muted" style="display:none;">The maximum characters allowed is 80 </span>
                                                                </div>
                                                            </div>
                                                            <div class="row kt-margin-t-10 kt-margin-b-30">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" style="text-align:right;">
                                                                    <button id="hidden-part-video-form-submit" class="btn btn-success">Save Video Title</button>
                                                                </div>
                                                            </div>
                                                        </form>

                                                        <div id="hidden-part-video-upload" style="display:none;">
                                                            <div id="hidden-part-drag-drop-video-wrapper" class="drag-drop-video-wrapper">
                                                                <div style="position:absolute;top:35%;left:0;right:0;">
                                                                    <i class="fa fa-video" style="font-size:2em;"></i>
                                                                    <span class="form-text text-muted" style="font-size:1.2rem;font-weight:600;">Drop video file here or click to browse</span>
                                                                </div>
                                                                <input type="file" id="hidden-part-video-input-file" class="drag-drop-video-input" accept=".mov, .mp4, video/quicktime"/>
                                                            </div>
                                                            <div id="hidden-part-video-progress-bar-wrapper" style="display:none;">
                                                                <span id="hidden-part-video-progress-bar-percent" class="form-text text-muted video-progress-span-update">Preparing to upload video...Please do not close or refresh your browser!</span>
                                                                <div class="progress" style="height:10px;">
                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="hidden-part-video-progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 1%"></div>
                                                                </div>
                                                            </div>
                                                            <div><span class="form-text text-muted"><b>Note*</b> Preferably to upload <b>video resolution on 720p</b>, <b>video file size less than 2GB</b>, and <b>MP4</b> or <b>MOV video file format.</b></span></div>
                                                        </div>

                                                        <div class="part-video-btn-div row" id="hidden-part-video-exist-buttons-div" style="display:none;">
                                                            <div class="col-md-4">
                                                                <img src="{{ asset('img/sample/poster-sample.png') }}" class="video-thumbnail">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h5 class="video-filename">Name: Something MP4.mp4</h5>
                                                                <h5 class="video-length">Length: 2.5 mins</h5>
                                                                <div class="kt-space-20"></div>     
                                                                <button type="button" class="btn btn-success btn-sm" id="hidden-part-video-view-button"><i class="fa fa-eye"></i> View Uploaded Video</button>&nbsp;
                                                                <button type="button" class="btn btn-label-danger btn-sm" id="hidden-part-video-remove-button"><i class="fa fa-video-slash"></i> Remove Video</button>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="video_url" id="hidden-video-url"/>
                                                        <input type="hidden" name="video_filename" id="hidden-video-filename"/>
                                                        <input type="hidden" name="video_size" id="hidden-video-size"/>
                                                        <input type="hidden" name="video_size" id="hidden-video-length"/>
                                                        <input type="hidden" name="video_id" id="hidden-video-id"/>
                                                    </div>
                                                    <div class="card-body spinner-saving" style="display:none;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end:: End Video Tab -->

                                    <!-- begin:: Article tab -->
                                    <div class="tab-pane" id="hidden-part-article-content" role="tabpanel">
                                        <form class="kt-form kt-form--label-left" id="hidden-part-article-form">
                                            <div class="accordion accordion-solid accordion-svg-icon" id="hidden-part-article-accordion">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="card-title" id="hidden-part-article-card-title" data-toggle="collapse" data-target="#hidden-part-article-collapse" aria-expanded="true">
                                                            <div style="float:left;width:40%;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <polygon points="0 0 24 0 24 24 0 24" />
                                                                        <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                        <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                    </g>
                                                                </svg> <span id="part-article-title">Article</span>
                                                            </div>
                                                            <div style="float:right;margin-left:auto;">
                                                                <a class="btn btn-icon btn-bold btn-label-danger button-remove-part">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="hidden-part-article-collapse" class="collapse show" data-parent="#hidden-part-article-accordion">
                                                        <div class="card-body">
                                                            <div class="kt-form__content">
                                                                <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="hidden-part-article-form-alert">
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
                                                            {{ csrf_field() }}
                                                            <div class="form-group row">
                                                                <label class="col-form-label col-lg-3 col-sm-12">Article Title <span class="required">*<span></label>
                                                                <div class="col-lg-6 col-md-9 col-sm-12">
                                                                    <div class="kt-typeahead part-article-div">
                                                                        <input class="form-control" type="text" name="article" id="hidden-part-article-input" style="display:none;" placeholder="Article Title">
                                                                        <span class="form-control" id="hidden-part-article-label">Article Title</span>
                                                                    </div>
                                                                    <span class="form-text text-muted" id="hidden-part-article-muted" style="display:none;">The maximum characters allowed is 80 </span>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="article_id" id="hidden-part-article-id" />
                                                            <div class="row">
                                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 part-article-body-wrapper">
                                                                    <div class="form-group">
                                                                        <label>Article Body: <span class="required">*<span></label>
                                                                        <div id="hidden-part-article-textarea-div" style="display:none;">
                                                                            <textarea class="form-control" minlength="50" id="hidden-part-article-body-textarea" name="article_textarea"></textarea>
                                                                        </div>
                                                                        <div id="hidden-part-article-body-div" class="box">Article Body</div>
                                                                        <span class="form-text text-muted" id="hidden-part-article-body-muted" style="display:none;">Please provide information for your article.</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" style="text-align:right;">
                                                                    <button id="hidden-part-article-form-submit" class="btn btn-success">Save Article</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- end:: Article Tab -->

                                    <!-- begin:: Quiz tab -->
                                    <div class="tab-pane" id="hidden-part-quiz-content" role="tabpanel">
                                        <div class="accordion accordion-solid accordion-svg-icon" id="hidden-part-quiz-accordion">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title" id="hidden-part-quiz-card-title" data-toggle="collapse" data-target="#hidden-part-quiz-collapse" aria-expanded="true">
                                                        <div style="float:left;width:40%;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                                </g>
                                                            </svg> <span id="part-quiz-title">Quiz</span>
                                                        </div>
                                                        <div style="float:right;margin-left:auto;">
                                                            <a class="btn btn-icon btn-bold btn-label-danger button-remove-part">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="hidden-part-quiz-collapse" class="collapse show" data-parent="#hidden-part-quiz-accordion">
                                                    <div class="card-body">
                                                        <form class="kt-form kt-form--label-left" id="hidden-part-quiz-form">
                                                            <div class="kt-form__content">
                                                                <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="hidden-part-quiz-form-alert">
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
                                                            {{ csrf_field() }}
                                                            <div class="form-group row">
                                                                <label class="col-form-label col-lg-3 col-sm-12">Quiz Title <span class="required">*<span></label>
                                                                <div class="col-lg-6 col-md-9 col-sm-12">
                                                                    <div class="kt-typeahead part-quiz-div">
                                                                        <input class="form-control" type="text" name="quiz" id="hidden-part-quiz-input" style="display:none;" placeholder="Quiz Title">
                                                                        <span class="form-control" id="hidden-part-quiz-label">Quiz Title</span>
                                                                    </div>
                                                                    <span class="form-text text-muted" id="hidden-part-quiz-muted" style="display:none;">The maximum characters allowed is 80 </span>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="quiz_id" id="hidden-part-quiz-id" />
                                                            <div class="form-group row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" style="text-align:right;">
                                                                    <button id="hidden-part-quiz-form-submit" class="btn btn-success">Save Quiz</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <div class="kt-portlet"  id="hidden-part-quiz-item-wrapper" style="display:none;"> <!-- style="display:none;" -->
                                                            <div class="kt-portlet__head">
                                                                <div class="kt-portlet__head-toolbar">
                                                                    <button class="btn btn-outline-info button-add-quiz-item btn-sm"><i class="fa fa-plus"></i> Add Item</button>
                                                                    <button class="btn btn-outline-warning button-back-quiz-list btn-sm" style="display:none;"><i class="fa fa-angle-double-up"></i> Back to List</button>
                                                                </div>
                                                            </div>
                                                            <div class="kt-portlet__body" id="hidden-part-quiz-item-list"></div>
                                                            <div class="kt-portlet__body" style="display:none;" id="hidden-part-quiz-item-body-wrapper">
                                                                <div class="kt-portlet">
                                                                    <div class="kt-portlet__body">
                                                                        <form class="kt-form kt-form--label-left" id="hidden-part-quiz-item-form">
                                                                            <div class="kt-form__content">
                                                                                <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="hidden-part-quiz-item-form-alert">
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

                                                                            <input type="hidden" name="quiz_item_id" id="hidden-part-quiz-item-id" />
                                                                            <div class="row">
                                                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 part-quiz-body-wrapper">
                                                                                    <div class="form-group">
                                                                                        <label>Question: <span class="required">*<span></label>
                                                                                        <div id="hidden-part-quiz-textarea-div" style="display:none;">
                                                                                            <textarea class="form-control" minlength="10" id="hidden-part-quiz-body-textarea" name="quiz_textarea"></textarea>
                                                                                        </div>
                                                                                        <div id="hidden-part-quiz-body-div" class="box">Question</div>
                                                                                        <span class="form-text text-muted" id="hidden-part-quiz-body-muted" style="display:none;">Please provide a question for your quiz.</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <label class="col-xl-12 col-12">Choices <span class="required">*<span></label> <br>
                                                                                <div class="col-xl-12 col-12 kt-radio-list">
                                                                                    <div class="form-group">
                                                                                        <label class="kt-radio kt-radio--bold">
                                                                                            <input type="radio" name="hidden-choices" value="one" checked> Choice 1
                                                                                            <span></span>
                                                                                        </label>
                                                                                        <span class="form-text text-muted">The maximum characters allowed is 100 </span>
                                                                                        <input class="form-control" type="input" name="hidden-part-quiz-choice-1" placeholder="Description"/>
                                                                                        <input class="form-control" type="input" name="hidden-part-quiz-choice-explain-1" placeholder="Explain..."/>
                                                                                    </div>
                                                                                    <div class="kt-space-20"></div>
                                                                                    <div class="form-group">
                                                                                        <label class="kt-radio kt-radio--bold">
                                                                                            <input type="radio" name="hidden-choices" value="two"> Choice 2
                                                                                            <span></span>
                                                                                        </label>
                                                                                        <span class="form-text text-muted">The maximum characters allowed is 100 </span>
                                                                                        <input class="form-control" type="input" name="hidden-part-quiz-choice-2" placeholder="Description"/>
                                                                                        <input class="form-control" type="input" name="hidden-part-quiz-choice-explain-2" placeholder="Explain..."/>
                                                                                    </div>
                                                                                    <div class="kt-space-20"></div>
                                                                                    <div class="form-group">
                                                                                        <label class="kt-radio kt-radio--bold">
                                                                                            <input type="radio" name="hidden-choices" value="three"> Choice 3
                                                                                            <span></span>
                                                                                        </label>
                                                                                        <span class="form-text text-muted">The maximum characters allowed is 100 </span>
                                                                                        <input class="form-control" type="input" name="hidden-part-quiz-choice-3" placeholder="Description"/>
                                                                                        <input class="form-control" type="input" name="hidden-part-quiz-choice-explain-3" placeholder="Explain..."/>
                                                                                    </div>
                                                                                    <div class="kt-space-20"></div>
                                                                                    <div class="form-group">
                                                                                        <label class="kt-radio kt-radio--bold">
                                                                                            <input type="radio" name="hidden-choices" value="four"> Choice 4
                                                                                            <span></span>
                                                                                        </label>
                                                                                        <span class="form-text text-muted">The maximum characters allowed is 100 </span>
                                                                                        <input class="form-control" type="input" name="hidden-part-quiz-choice-4" placeholder="Description"/>
                                                                                        <input class="form-control" type="input" name="hidden-part-quiz-choice-explain-4" placeholder="Explain..."/>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <div class="col-lg-12 col-md-12 col-sm-12 ml-lg-auto" style="text-align:right;">
                                                                                    <button id="hidden-part-quiz-item-form-submit" class="btn btn-success">Save Quiz Item</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end:: Quiz Tab -->

                                </div>
                                <!-- end:: content for chosen part  -->

                                <!-- begin:: APPEND BEFORE Selection of ADD, VIDEO, Article, & QUIZ -->
                                <div class="kt-portlet__head-toolbar selection-after" style="display:none;">
                                    <ul class="nav nav-pills nav-pills-sm left-floated-tabbuttons--down" role="tablist">
                                        <li class="nav-items">
                                            <a class="btn btn-icon btn-bold btn-brand button-add-part-after">
                                                <i class="fa fa-plus-circle"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- end:: APPEND BEFORE Selection of ADD, VIDEO, Article, & QUIZ -->
                            </div>
                            <!-- end::Video Article Quiz Content -->
                        </div>
                    </div>
                    <!-- end::Hidden Section for Clone -->

                </div>
                <!-- End Section Body -->
            </div>
            <!-- End of the Main Body -->
        </div>
    </div>
</div>

<!-- begin:modal for: section removal -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="section-removal-modal" tabindex="-1" role="dialog" aria-labelledby="section-removal-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="section-removal-modal-label">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align:center;">You're removing a section.<br>Are you sure you want to remove it?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="section-removal-modal-submit">Yes, I'm sure.</button>
            </div>
        </div>
    </div>
</div>
<!-- end:modal for: part removal -->

<!-- begin:modal for: part removal -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="part-removal-modal" tabindex="-1" role="dialog" aria-labelledby="part-removal-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="part-removal-modal-label">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align:center;">You're removing a part from your current section.<br>Are you sure you want to remove it?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="part-removal-modal-submit">Yes, I'm sure.</button>
            </div>
        </div>
    </div>
</div>
<!-- end:modal for: part removal -->

<!-- begin:modal for: part's video removal -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="part-removal-video-modal" tabindex="-1" role="dialog" aria-labelledby="part-removal-video-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="part-removal-video-modal-label">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align:center;">You're removing a part's video from your current section.<br>Are you sure you want to remove it?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="part-removal-video-modal-submit">Yes, I'm sure.</button>
            </div>
        </div>
    </div>
</div>
<!-- end:modal for: part's video removal -->

<!-- begin:modal for: part's video cancel upload -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="part-cancel-video-modal" tabindex="-1" role="dialog" aria-labelledby="part-cancel-video-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="part-cancel-video-modal-label">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align:center;">You're trying to cancel the upload for this video.<br>Are you sure you want to cancel it?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="part-cancel-video-modal-submit">Yes, I'm sure.</button>
            </div>
        </div>
    </div>
</div>
<!-- end:modal for: part's video cancel upload -->
@endsection
@section('scripts')
<script src="{{asset('js/aws-sdk-2.754.0.min.js')}}"></script>
<script src="{{asset('js/course-creation/video-content/vcs-upload-js.js')}}" type="text/javascript"></script>
<script src="{{asset('js/course-creation/video-content/vcs-sub-video-design.js')}}" type="text/javascript"></script>
@endsection