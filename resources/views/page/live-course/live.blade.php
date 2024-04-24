@extends('template.master_live')
@section('styles')
<link href="{{asset('css/live-course-progress.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('css/video-js.css')}}" rel="stylesheet" />
<link href="{{asset('css/videojs-resolution-switcher.css')}}" rel="stylesheet" />
<link href="{{asset('css/videojs-watermark.css')}}" rel="stylesheet" />
<link href="{{asset('css/videojs-seek-buttons.css')}}" rel="stylesheet" />
<link href="https://players.brightcove.net/videojs-thumbnails/videojs.thumbnails.css" rel="stylesheet" />
<style>
    .icon-image{height:80px;border-radius:8px;}
    .centered{margin:auto;text-align:center;}
    .minimize > i{font-size:2rem !important;}
    .white-color{color:#fff !important;}
    .error {color:red;}
</style>
@endsection 
@section('content')
{{ csrf_field() }}
<input type="hidden" name="course_id" value="{{ $course->id ?? 0 }}" />
<input type="hidden" name="preview_" value="{{ Request::segment(2) ?? 'preview' }}" />
<input type="hidden" name="rating_step" id="rating_step"/>

<div class="row">
    <div class="col-xl-8 col-md-8 remove-pad-r" id="main-content">
        <div class="row">
            <div class="col-12 remove-pad-r transition-content-wrapper--video" id="transition-content-wrapper">
                <div class="row" id="non-video-content" style="display:none;"></div>
                <video id="live-course-video" class="video-js vjs-default-skin vjs-fluid" poster="{{asset('img/sample/poster-sample.png')}}"></video>
                <button style="display:none;" class="course-content-open-btn course-content-open--btn btn btn-icon btn-label-primary" onclick="courseContentEvent(true)"><i class="fa fa-arrow-left"></i><span class="btn-label-content"></span></button>
            </div>
            <button style="display:none;" onclick="displayLiveCourseContent('prev')" class="btn btn-icon btn-sm btn-label-dark course-nav-left--btn"><i class="fa fa-arrow-left"></i></button>
            <button onclick="displayLiveCourseContent('next')" class="btn btn-icon btn-sm btn-label-dark course-nav-right--btn"><i class="fa fa-arrow-right"></i></button>
        </div>
        <div class="row">
            <div class="col-12 remove-pad-r">
                <div class="kt-portlet kt-portlet--tabs">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-toolbar" style="padding:15px;">
                            <ul class="nav nav-tabs nav-tabs-bold nav-tabs-line nav-tabs-line-right nav-tabs-line-brand" role="tablist">
                                <li class="nav-item hidden-course-content" style="display:none;">
                                    <a class="nav-link" data-toggle="tab" href="#tab-course-content" role="tab">
                                        Course Content
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#overview-content-tab-2" role="tab">
                                        Overview
                                    </a>
                                </li>
                                @if($handouts)
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#handouts-content-tab-3" role="tab">
                                        Handouts
                                    </a>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#certificate-content-tab-4" role="tab">
                                        Certificate
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane" id="tab-course-content">
                                <div class="tab-div-contents">
                                    <div class="kt-portlet__body" style="padding:0px !important;">
                                        <div class="kt-scroll ps ps--active-y" data-scroll="true" data-scrollbar-shown="true" style="height:100vh; overflow: hidden;">
                                            <div class="accordion  accordion-toggle-arrow" id="tab-course-content-accordion"></div>                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane active" id="overview-content-tab-2">
                                <div class="tab-div-contents">
                                    <h3>About this Course <small class="header-title-display"><br />{{ $course->title ?? 'Undefined' }}</small></h3>
                                    <p>{{ $course->headline ?? '' }}</p>
                                    <div class="row">
                                        <div class="col-xl-2 col-md-3 col-4"><i class="fa fa-play-circle"></i> &nbsp;{{ $total['video_hours'] }} Video</div>
                                        <div class="col-xl-2 col-md-3 col-4"><i class="fa fa-list"></i> &nbsp;{{ $total['quizzes'] }} {{ $total['quizzes'] > 1 ? "Quizzes" : "Quiz" }}</div>
                                        <div class="col-xl-2 col-md-3 col-4"><i class="fa fa-file"></i> &nbsp;{{ $total['handouts'] }} {{ $total['handouts'] > 1 ? "Handouts" : "Handout"}}</div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-xl-2 col-md-3 col-4"><img class="icon-image" src="{{ $course->provider->logo ?? asset('img/sample/noimage.png') }}" /></div>
                                        <div class="col-xl-8 col-md-8 col-8">
                                            <b>{{ $course->provider->name ?? 'Undefined' }}</b>
                                            <p>{{ $course->provider->headline ?? '' }}</p>
                                            @if( $course->provider->website )
                                            <a href="{{ $course->provider->website }}" target="_blank" class="btn btn-icon btn-circle btn-label-google">
                                                <i class="flaticon2-world"></i>
                                            </a>
                                            @endif
                                            
                                            @if( $course->provider->facebook )
                                            <a href="{{ $course->provider->facebook }}" target="_blank" class="btn btn-icon btn-circle btn-label-facebook">
                                                <i class="socicon-facebook"></i>
                                            </a>
                                            @endif
                                            
                                            @if( $course->provider->linkedin )
                                            <a href="{{ $course->provider->linkedin }}" target="_blank" class="btn btn-icon btn-circle btn-label-twitter">
                                                <i class="socicon-linkedin"></i>
                                            </a>
                                            @endif
                                            <div class="kt-space-20"></div>
                                            @if($course->provider->about)
                                            <?=htmlspecialchars_decode($course->provider->about)?>
                                            @endif
                                            <div class="kt-space-20"></div>
                                            <button onclick="window.open('/provider/{{ $course->provider->url ?? `notfound` }}#our_courses')" class="btn btn-sm btn-label-info btn-upper"><b>View Courses</b></button>    
                                        </div>
                                    </div>
                                    <br/>
                                    @if($course->instructor_id!=null)
                                    <div class="row">
                                        @foreach($instructors as $inst)
                                        <div class="col-xl-4 col-sm-6 col-12">
                                            <!--Begin::Portlet-->
                                            <div class="kt-portlet kt-portlet--height-fluid kt-portlet--bordered">
                                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                                    <div class="kt-portlet__head-label">
                                                        <h3 class="kt-portlet__head-title">
                                                        </h3>
                                                    </div>
                                                </div>
                                                <div class="kt-portlet__body">
                                                    <!--begin::Widget -->
                                                    <div class="kt-widget kt-widget--user-profile-2">
                                                        <div class="kt-widget__head">
                                                            <div class="kt-widget__media">
                                                                <img class="kt-widget__img" src="{{ $inst->profile->image ?? asset('img/sample/noimage.png') }}" alt="image">
                                                            </div>
                                                            <div class="kt-widget__info">
                                                                <a href="#" class="kt-widget__username">
                                                                    {{ $inst->profile->name ?? "Undefined" }}
                                                                </a>
                                                                <span class="kt-widget__desc">
                                                                    {{ $inst->profile->headline ?? "Instructor" }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="kt-widget__body">
                                                            <div class="kt-widget__section">
                                                                <?= $inst->profile->about ? html_entity_decode($inst->profile->about) : ""?>
                                                            </div>
                                                            @if($inst->profile->facebook || $inst->profile->website || $inst->profile->linkedin)
                                                            <div class="kt-widget__section">
                                                                <div class="row justify-content-center">
                                                                    @if($inst->profile->website)
                                                                    <a href="{{ $inst->profile->website }}" target="_blank" class="btn btn-icon btn-circle btn-label-google">
                                                                        <i class="flaticon2-world"></i>
                                                                    </a> &nbsp;
                                                                    @endif
                                                                    @if($inst->profile->facebook)
                                                                    <a href="{{ $inst->profile->facebook }}" target="_blank" class="btn btn-icon btn-circle btn-label-facebook">
                                                                        <i class="socicon-facebook"></i>
                                                                    </a> &nbsp;
                                                                    @endif
                                                                    @if($inst->profile->linkedin)
                                                                    <a href="{{ $inst->profile->linkedin }}" target="_blank" class="btn btn-icon btn-circle btn-label-twitter">
                                                                        <i class="socicon-linkedin"></i>
                                                                    </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="kt-widget__footer">
                                                            <button type="button" class="btn btn-label-info btn-lg btn-upper" onclick="window.open('/instructor/{{ $inst->profile->username }}')">view courses</button>
                                                        </div>
                                                    </div>
                                                    <!--end::Widget -->
                                                </div>
                                            </div>
                                            <!--End::Portlet-->
                                        </div>
                                        @endforeach
                                    </div>
                                    <br/>
                                    @endif
                                    <div class="row">
                                        <p><?= $course->description ? html_entity_decode($course->description) : ""  ?></p>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-xl-2 col-md-3 col-4">
                                            <strong>Objectives</strong>
                                        </div>
                                        <div class="col-8">
                                            <div>
                                                <ul>
                                                    @foreach(json_decode($course->objectives) as $obj)
                                                    @if($obj!=null || $obj!="")
                                                    <li>{{ $obj }}</li>
                                                    @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-xl-2 col-md-3 col-4">
                                            <strong>Requirements</strong>
                                        </div>
                                        <div class="col-8">
                                            <div>
                                                <ul>
                                                    @foreach(json_decode($course->requirements) as $req)
                                                    @if($req!=null || $req!="")
                                                    <li>{{ $req }}</li>
                                                    @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-xl-2 col-md-3 col-4">
                                            <strong>Target Students</strong>
                                        </div>
                                        <div class="col-8">
                                            <div> 
                                                <ul>
                                                    @foreach(json_decode($course->target_students) as $target)
                                                    @if($target!=null || $target!="")
                                                    <li>{{ $target }}</li>
                                                    @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($handouts)
                            <div class="tab-pane" id="handouts-content-tab-3">
                                <div class="tab-div-contents">
                                    <div class="row">
                                        @foreach($handouts as $hnd)
                                        <div class="col-xl-3 col-md-3 col-4" style="text-align:center; cursor:pointer;">
                                            <h5>{{ $hnd->title }}</h5>
                                            <?php 
                                            $explode = explode("/", $hnd->url);
                                            $filename = end($explode);
                                            $clean = explode(".", $filename);
                                            $extension = strtolower(end($clean));
                                            ?>
                                            
                                            @if(in_array($extension, ["pdf", "xls", "zip", "csv"]))
                                            <img src="<?=$handout_img["{$extension}"] ?>" height="80" onclick="window.open(`{{ $hnd->url }}`)" />
                                            @else
                                            <img src="{{ $handout_img['other'] }}" height="80" onclick="window.open(`{{ $hnd->url }}`)" />
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="tab-pane" id="certificate-content-tab-4">
                                <div class="tab-div-contents">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-6 col-md-10 col-12"> 
                                            <h5>Get your CPD certificate by completing the following:</h5><br/>
                                            <span id="span_completed_course"><i class="fa fa-check-circle"></i>&nbsp; 100% completed course progress</span> <br/>
                                            <span id="span_completed_quiz"><i class="fa fa-check-circle"></i>&nbsp; A passing grade of <%>  in the quizzes</span> <br/>
                                            <span id="span_completed_rating"><i class="fa fa-check-circle"></i>&nbsp; Leave a rating and comment of the course (PRC requirement)</span>
                                        </div>
                                    </div>
                                    <div class="kt-space-20"></div>
                                    <div class="row justify-content-center">
                                        <input type="hidden" name="certificate_code" value="<?=$certificates ? $certificates->certificate_code : ""?>" id="certificate_hash" />
                                        <button class="btn-lg btn btn-warning get_cpd_certificate_button">Get CPD Certificate</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-4 remove-pad-r" id="sidemenu-course-content" style="position:fixed;right:0;">
        <div class="kt-portlet kt-portlet--bordered" style="margin-bottom:0px !important;">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Course Content
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-actions">
                        <a class="btn btn-secondary btn-sm btn-icon btn-icon-md" onclick="courseContentEvent(false)">
                            <i class="flaticon2-cross"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body" style="padding:0px !important;">
                <div class="kt-scroll ps ps--active-y" data-scroll="true" data-scrollbar-shown="true" style="height:100vh; overflow: hidden;">
                    <div class="accordion  accordion-toggle-arrow" id="sidemenu-course-content-accordion"></div>                    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="leave-a-rating-content" tabindex="-1" role="dialog" aria-labelledby="leave-a-rating-content-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:none;">
                <div class="modal-title"><a id="feedback-back-button" onclick="oneStepBack();" style="display:none;">Back</a></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="rating-content-body">
                <div class="row justify-content-center">
                    <h3>How would you rate this course?</h3>
                </div>    
                <div class="kt-space-10"></div>
                <div class="row justify-content-center">
                    <h5 class="live-rating-label"></h5>
                </div>    
                <div class="row justify-content-center">
                    <span id="ratings" class="rating"></span>
                    <input type="hidden" id="ratings_value" name="ratings_value"/>
                </div>
                <div class="kt-space-20"></div>
                <div id="feedback-rating-textarea" class="row justify-content-center" style="display:none;">
                    <div class="col-md-9 col-11">
                        <textarea class="form-control" id="remarks" placeholder="Tell us about your own personal experience taking this course. What did you learn from it?"></textarea>
                        <div class="kt-space-20"></div>
                        <div class="row justify-content-center">
                            <button class="btn btn-info btn-sm" id="btn_ratings_remarks" onclick="oneStepForward('btn_ratings_remarks');">Save & Continue</button>
                        </div>
                    </div>
                </div>
                <div class="kt-space-20"></div>
            </div>
            <form id="course_performance_form" >
                <div class="modal-body" id="feedback-question-content" style="display:none;">
                    
                    <div class="row justify-content-center">
                        
                        <h3>Please tell us more</h3>
                        <div class="col-md-9 col-11">
                            <div class="row">
                                <div class="col-8">Are you learning a valuable information?</div>    
                                <div class="col-4">
                                    <div class="btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-sm btn-label-success">
                                            <input type="radio" name="valuable_information" value="yes" id="valuable_information_1"> Yes
                                        </label>
                                        <label class="btn btn-sm btn-label-danger">
                                            <input type="radio" name="valuable_information" value="no" id="valuable_information_2"> No
                                        </label>
                                        <label class="btn btn-sm btn-label-warning">
                                            <input type="radio" name="valuable_information" value="unsure" id="valuable_information_3"> Unsure
                                        </label>
                                    </div>
                                </div>    
                            </div>
                            <div class="row" id="valuable_information_required" style="display: none;">
                                <div class="col-8"></div>    
                                <div class="col-4">
                                    <span>This field is required!</span>
                                </div>    
                            </div>
                            <div class="kt-space-10"></div>
                            <div class="row">
                                <div class="col-8">Are the explanations of the concepts clear?</div>    
                                <div class="col-4">                                 
                                    <div class="btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-sm btn-label-success">
                                            <input type="radio" name="concepts_clear" value="yes" id="concecpts_clear_1"> Yes
                                        </label>
                                        <label class="btn btn-sm btn-label-danger">
                                            <input type="radio" name="concepts_clear" value="no" id="concecpts_clear_2"> No
                                        </label>
                                        <label class="btn btn-sm btn-label-warning">
                                            <input type="radio" name="concepts_clear" value="unsure" id="concecpts_clear_3"> Unsure
                                        </label>
                                    </div>                           
                                </div>    
                            </div>
                            <div class="row" id="concecpts_clear_required" style="display: none;">
                                <div class="col-8"></div>    
                                <div class="col-4">
                                    <span>This field is required!</span>
                                </div>    
                            </div>
                            <div class="kt-space-10"></div>
                            <div class="row">
                                <div class="col-8">Is the instructor's delivery engaging?</div>    
                                <div class="col-4">                                
                                    <div class="btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-sm btn-label-success">
                                            <input type="radio" name="instructor_delivery" value="yes" id="instructor_delivery_1"> Yes
                                        </label>
                                        <label class="btn btn-sm btn-label-danger">
                                            <input type="radio" name="instructor_delivery" value="no" id="instructor_delivery_2"> No
                                        </label>
                                        <label class="btn btn-sm btn-label-warning">
                                            <input type="radio" name="instructor_delivery" value="unsure" id="instructor_delivery_3"> Unsure
                                        </label>
                                    </div>                             
                                </div>    
                            </div>
                            <div class="row" id="instructor_delivery_required" style="display: none;">
                                <div class="col-8"></div>    
                                <div class="col-4">
                                    <span>This field is required!</span>
                                </div>    
                            </div>
                            <div class="kt-space-10"></div>
                            <div class="row">
                                <div class="col-8">Are there enough opportunities to apply what you are learning?</div>    
                                <div class="col-4">                                 
                                    <div class="btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-sm btn-label-success">
                                            <input type="radio" name="opportunities" value="yes" id="opportunities_1"> Yes
                                        </label>
                                        <label class="btn btn-sm btn-label-danger">
                                            <input type="radio" name="opportunities" value="no" id="opportunities_2"> No
                                        </label>
                                        <label class="btn btn-sm btn-label-warning">
                                            <input type="radio" name="opportunities" value="unsure" id="opportunities_3"> Unsure
                                        </label>
                                    </div>                             
                                </div>    
                            </div>
                            <div class="row" id="opportunities_required" style="display: none;">
                                <div class="col-8"></div>    
                                <div class="col-4">
                                    <span>This field is required!</span>
                                </div>    
                            </div>
                            <div class="kt-space-10"></div>
                            <div class="row">
                                <div class="col-8">Is the course delivering to your expectations?</div>    
                                <div class="col-4">                                 
                                    <div class="btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-sm btn-label-success">
                                            <input type="radio" name="expectations" value="yes" id="expectations_1"> Yes
                                        </label>
                                        <label class="btn btn-sm btn-label-danger">
                                            <input type="radio" name="expectations" value="no" id="expectations_2"> No
                                        </label>
                                        <label class="btn btn-sm btn-label-warning">
                                            <input type="radio" name="expectations" value="unsure" id="expectations_3"> Unsure
                                        </label>
                                    </div>                           
                                </div>    
                            </div>
                            <div class="row" id="expectations_required" style="display: none;">
                                <div class="col-8"></div>    
                                <div class="col-4">
                                    <span>This field is required!</span>
                                </div>    
                            </div>
                            <div class="kt-space-10"></div>
                            <div class="row">
                                <div class="col-8">Is the instructor knowledgeable about the topic?</div>    
                                <div class="col-4">                                 
                                    <div class="btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-sm btn-label-success">
                                            <input type="radio" name="knowledgeable" value="yes" id="knowledgeable_1"> Yes
                                        </label>
                                        <label class="btn btn-sm btn-label-danger">
                                            <input type="radio" name="knowledgeable" value="no" id="knowledgeable_2"> No
                                        </label>
                                        <label class="btn btn-sm btn-label-warning">
                                            <input type="radio" name="knowledgeable" value="unsure" id="knowledgeable_3"> Unsure
                                        </label>
                                    </div>                           
                                </div>    
                            </div>
                            <div class="row" id="knowledgeable_required" style="display: none;">
                                <div class="col-8"></div>    
                                <div class="col-4">
                                    <span>This field is required!</span>
                                </div>    
                            </div>
                            <div class="kt-space-20"></div>
                            <div class="row justify-content-center">
                                <button class="btn btn-info btn-sm" id="btn_course_performance" onclick="oneStepForward('btn_course_performance');">Save & Continue</button> &nbsp; &nbsp; &nbsp;
                            </div>
                        </div>
                        
                    </div>
                    <div class="kt-space-20"></div>
                </div>
            </form>
            <div class="modal-body" id="feedback-final-content" style="display:none;">
                <div class="row justify-content-center">
                    <h3 style="margin:5px;">
                        Thanks for helping our community! <br>
                        <small>Your review will be public within 24 hours</small>
                    </h3>
                    <div class="col-md-6 col-12">
                        <div class="kt-space-20"></div>
                        <div class="row justify-content-center">
                            <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#share-modal" data-dismiss="modal">Share this Course</button> &nbsp; &nbsp;
                            <button class="btn btn-info btn-sm" id="btn_finished_rating" data-dismiss="modal">Save & Exit</button> &nbsp; &nbsp;
                            <button class="btn btn-warning btn-sm get_cpd_certificate_button">Get CPD Certificate</button>
                        </div>
                    </div>
                </div>
                <div class="kt-space-20"></div>
            </div>
            <div class="modal-footer" style="border-top:none;"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="print_pdf_modal" tabindex="-1" role="dialog" aria-labelledby="print_pdf_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="print_pdf_modal_label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="print_pdf_modal_body">
                <form id="print_modal_form">
                    <div class="form-group">
                        <label for="page_orientation">Orientation</label>
                        <select class="form-control form-control-sm" name="page_orientation">
                            <option value="P" selected>Portrait</option>
                            <option value="L">Landscape</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="page_size">Size</label>
                        <select class="form-control form-control-sm" name="page_size">
                            <option value="A4" selected>A4 (8.27" × 11.69")</option>
                            <option value="Letter">Letter (8.5" x 11")</option>
                            <option value="Legal">Legal (8.5" x 14")</option>
                            <option value="Ledger">Ledger (11" x 17")</option>
                            <option value="Tabloid">Tabloid (17" x 11")</option>
                        </select>
                    </div>
                    <input type="hidden" name="courseId" value="{{ $course->id ?? 0 }}" />
                 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="cancel_print" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="confirm_print" class="btn btn-primary" data-dismiss="modal">Confirm Setup</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('top_scripts')
<script src="{{asset('js/video.js')}}"></script>
<script src="{{asset('js/videojs-ie8.min.js')}}"></script>
<script src="https://players.brightcove.net/videojs-thumbnails/videojs.thumbnails.js"></script>
<script src="{{asset('js/videojs-resolution-switcher.js')}}"></script>
<script src="{{asset('js/videojs-http-streaming.js')}}"></script>
<script src="{{asset('js/videojs-watermark.js')}}"></script>
<script src="{{asset('js/videojs.hotkeys.min.js')}}"></script>
<script src="{{asset('js/videojs-seek-buttons.min.js')}}"></script>
@endsection
@section("scripts")
<script>
    jQuery(document).ready(function () {
        $.ajax({
            url: "/provider/review/api/checkProgressReview",
            data: {
                course_id: `<?= $course->id ?>`,
                
            },
            success:function(response){
                var progress = response.status;
                
                if(progress == "finished"){
                    has_completed_course=true;
                    live_completed_course();
                }
                
                var rating_step = response.rating_step;
                $("#rating_step").val(rating_step);
                if(rating_step == 2){
                    $(`#feedback-question-content`).show(
                    "slide",
                    { direction: "right" },
                    300
                    );
                    $(`#rating-content-body`).hide("slide", { direction: "right" }, 300);
                }else if (rating_step >= 3){
                    $(`#rating-content-body`).hide(
                    "slide",
                    { direction: "right" },
                    300
                    );
                    $(`#feedback-final-content`).show("slide", { direction: "right" }, 300);
                    
                    has_completed_rating=true;
                    live_completed_rating();
                }else{
                    
                }
            },
            error:function(){
                
            },
        });
    });
    
    
</script>
<script src="{{asset('js/progressbar.js')}}" type="text/javascript"></script>
<script src="{{asset('js/live-course/live-misc.js')}}" type="text/javascript"></script>
<script src="{{asset('js/live-course/live-certificate.js')}}" type="text/javascript"></script>
<script src="{{asset('js/live-course/live-course.js')}}" type="text/javascript"></script>
@endsection
