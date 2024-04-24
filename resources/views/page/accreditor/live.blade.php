@extends('template.master_accreditor_live')
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
</style>
@endsection 
@section('content')
{{ csrf_field() }}
<input type="hidden" name="course_id" value="{{ $course->id ?? 0 }}" />
<input type="hidden" name="preview_" value="{{ Request::segment(2) ?? 'preview' }}" />
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
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#handouts-content-tab-3" role="tab">
                                        Handouts
                                    </a>
                                </li>
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
                                        <div class="col-xl-2 col-md-3 col-4"><i class="fa fa-play-circle"></i> &nbsp;{{ $total['video_hours'] }} Video Length</div>
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
                                            <button onclick="toastr.info('This is an Accreditor preview!')" class="btn btn-sm btn-label-info btn-upper"><b>View Courses</b></button>    
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
                                                            <button type="button" class="btn btn-label-info btn-lg btn-upper" onclick="toastr.info('This is an Accreditor preview!')">view courses</button>
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
                            <div class="tab-pane" id="certificate-content-tab-4">
                                <div class="tab-div-contents">
                                    <div class="row justify-content-center">
                                        Get your CPD certificate by completing this course
                                    </div>
                                    <div class="kt-space-20"></div>
                                    <div class="row justify-content-center">
                                        <button class="btn-lg btn btn-warning" disabled>Get CPD Certificate</button>
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
                    <span class="rating"></span>
                </div>
                <div class="kt-space-20"></div>
                <div id="feedback-rating-textarea" class="row justify-content-center" style="display:none;">
                    <div class="col-md-9 col-11">
                        <textarea class="form-control" placeholder="Tell us about your own personal experience taking this course. What did you learn from it?"></textarea>
                        <div class="kt-space-20"></div>
                        <div class="row justify-content-center">
                            <button class="btn btn-info btn-sm" onclick="oneStepForward();">Save & Continue</button>
                        </div>
                    </div>
                </div>
                <div class="kt-space-20"></div>
            </div>
            <div class="modal-body" id="feedback-question-content" style="display:none;">
                <div class="row justify-content-center">
                    <h3>Please tell us more</h3>
                    <div class="col-md-9 col-11">
                        <div class="row">
                            <div class="col-8">Are you learning a valueble information?</div>    
                            <div class="col-4">
                                <button class="btn btn-sm btn-label-success">Yes</button>
                                <button class="btn btn-sm btn-label-danger">No</button>
                                <button class="btn btn-sm btn-label-warning">Unsure</button>
                            </div>    
                        </div>
                        <div class="kt-space-10"></div>
                        <div class="row">
                            <div class="col-8">Are the explanations of the concepts clear?</div>    
                            <div class="col-4">                                 
                                <button class="btn btn-sm btn-label-success">Yes</button>                                 
                                <button class="btn btn-sm btn-label-danger">No</button>                                 
                                <button class="btn btn-sm btn-label-warning">Unsure</button>                             
                            </div>    
                        </div>
                        <div class="kt-space-10"></div>
                        <div class="row">
                            <div class="col-8">Is the instructor's delivery engaging?</div>    
                            <div class="col-4">                                
                                <button class="btn btn-sm btn-label-success">Yes</button>                                 
                                <button class="btn btn-sm btn-label-danger">No</button>                                
                                <button class="btn btn-sm btn-label-warning">Unsure</button>                             
                            </div>    
                        </div>
                        <div class="kt-space-10"></div>
                        <div class="row">
                            <div class="col-8">Are there enough opportunities to apply what you are learning?</div>    
                            <div class="col-4">                                 
                                <button class="btn btn-sm btn-label-success">Yes</button>                                 
                                <button class="btn btn-sm btn-label-danger">No</button>                                 
                                <button class="btn btn-sm btn-label-warning">Unsure</button>                             
                            </div>    
                        </div>
                        <div class="kt-space-10"></div>
                        <div class="row">
                            <div class="col-8">Is the course delivering to your expectations?</div>    
                            <div class="col-4">                                 
                                <button class="btn btn-sm btn-label-success">Yes</button>                                 
                                <button class="btn btn-sm btn-label-danger">No</button>                                 
                                <button class="btn btn-sm btn-label-warning">Unsure</button>                             
                            </div>    
                        </div>
                        <div class="kt-space-10"></div>
                        <div class="row">
                            <div class="col-8">Is the instructor knowledgeable about the topic?</div>    
                            <div class="col-4">                                 
                                <button class="btn btn-sm btn-label-success">Yes</button>                                 
                                <button class="btn btn-sm btn-label-danger">No</button>                                 
                                <button class="btn btn-sm btn-label-warning">Unsure</button>                             
                            </div>    
                        </div>
                        <div class="kt-space-20"></div>
                        <div class="row justify-content-center">
                            <button class="btn btn-info btn-sm" onclick="oneStepForward();">Save & Continue</button> &nbsp; &nbsp; &nbsp;
                        </div>
                    </div>
                </div>
                <div class="kt-space-20"></div>
            </div>
            <div class="modal-body" id="feedback-final-content" style="display:none;">
                <div class="row justify-content-center">
                    <h3 style="margin:5px;">
                        Thanks for helping our community! <br>
                        <small>Your review will be public within 24 hours</small>
                    </h3>
                    <div class="col-md-6 col-12">
                        <div class="kt-space-20"></div>
                        <div class="row justify-content-center">
                            <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#share-modal">Share this Course</button> &nbsp; &nbsp; &nbsp;
                            <button class="btn btn-info btn-sm" data-dismiss="modal">Exit</button> 
                        </div>
                    </div>
                </div>
                <div class="kt-space-20"></div>
            </div>
            <div class="modal-footer" style="border-top:none;"></div>
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
<script src="{{asset('js/progressbar.js')}}" type="text/javascript"></script>
<script>
var _is_preview = $(`input[name="preview_"]`).val();
var progress_label = null;

var window_width = $(window).width();
var rating_step = 0;

var circle1 = null;
var circle2 = null;

var total_progress = 0;
var current_progress = 0;
/**
 * Video Area
 *
 */
var currentVideo = {};
var completed_video_sent = false;
var currentVideo_autosave = 0; // every 10 seconds

// control over main content's rotation
var currentRotation = 0;
// control over current quiz's rotation
var currentQuiz = {};
var currentQuizRotation = 0;
// this is for when taking a quiz
var quizScores = [];
var quizState = false;

// data for main content
var section_content_rotation = [];
// list data for course content
var section_content_progress = [];

var MyVideoJS = null;

jQuery(document).ready(function () {
    $(".rating").starRating({
        totalStars: 5,
        initialRating: 0,
        starShape: "rounded",
        starSize: 50,
        disableAfterRate: false,
        onHover: function (currentIndex, currentRating, $el) {
            var displayText = "";

            if (currentIndex == 5) {
                displayText = "Amazing, above expectation!";
            }

            if (currentIndex > 4 && currentIndex < 5) {
                displayText = "Good / Amazing";
            }

            if (currentIndex == 4) {
                displayText = "Good, what I expected";
            }

            if (currentIndex > 3 && currentIndex < 4) {
                displayText = "Average / Good";
            }

            if (currentIndex == 3) {
                displayText = "Average, could be better";
            }

            if (currentIndex > 2 && currentIndex < 3) {
                displayText = "Poor / Average";
            }

            if (currentIndex == 2) {
                displayText = "Poor, pretty disappointed";
            }

            if (currentIndex > 1 && currentIndex < 2) {
                displayText = "Awful / Poor";
            }

            if (currentIndex <= 1 && currentIndex > 0) {
                displayText = "Aww, not what I expected at all!";
            }

            $(".live-rating-label").text(displayText);
        },
        callback: function (currentRating, $el) {
            rating_step += 1;
            $(`#feedback-rating-textarea`).show(
                "slide",
                { direction: "right" },
                300
            );
            $(`#feedback-back-button`).show(
                "slide",
                { direction: "right" },
                300
            );
        },
    });

    get_sections();
    $('#live-course-video').bind('contextmenu',function() { return false; });
});

/**
 * Video JS
 * Should have the initial video, recommended: preview video
 *
 */
function initialize_videoJS(video) {
    if (MyVideoJS == null) {
        currentVideo = video;
        var vext_ = extract_file_ext(video.filename);
        MyVideoJS = videojs(
            "live-course-video",
            {
                controls: true,
                fluid: true,
                responsive: true,
                aspectRatio: "600:250",
                playbackRates: [0.5, 1, 1.5],
                plugins: {
                    videoJsResolutionSwitcher: {
                        ui: true,
                        default: "low",
                        dynamicLabel: true,
                    },
                    watermark: {
                        file: "https://fastcpd.com/img/system/icon-9.png",
                        opacity: 0.3,
                        xpos: 100,
                        ypos: 0,
                    },
                },
            },
            function () {
                var player = this;
                window.player = player;

                player.src({ type: `video/${vext_}`, src: video.source });
                player.seekButtons({
                    forward: 0,
                    back: 10,
                });
                this.hotkeys({
                    volumeStep: 0.1,
                    seekStep: 5,
                    enableModifiersForNumbers: false,
                });
                player.controlBar.progressControl.disable();
                player.on("resolutionchange", function () {
                    // The resolution label was not updating or showing
                    // Create a customized reso label on evert change of resolution
                    $(
                        "button.vjs-resolution-button > span.vjs-icon-placeholder"
                    ).html(player.currentResolutionState.label);
                });
                player.thumbnails(video.thumbnail);
                player.poster(video.poster);

                player.on("pause", function (e) {
                    section_content_rotation = section_content_rotation.map(
                        (varData) => {
                            if (
                                varData.type == "video" &&
                                varData.id == currentVideo.id
                            ) {
                                $.ajax({
                                    url: "/course/live/progress",
                                    method: "POST",
                                    data: {
                                        course_id: $(
                                            `input[name="course_id"]`
                                        ).val(),
                                        preview_: _is_preview,
                                        _token: $(
                                            'input[name*="_token"]'
                                        ).val(),
                                        type: "video",
                                        data_: currentVideo,
                                    },
                                });

                                return currentVideo;
                            }

                            return varData;
                        }
                    );
                });
            }
        );

        MyVideoJS.on("timeupdate", function () {
            currentVideo.current_play_time = Math.floor(this.currentTime());
            let vlength = currentVideo.video_length;
            let split_v = vlength.toString().split(".");

            let mins = parseInt(split_v[0]);
            let secs = parseInt(split_v[1]);
            let seconds_ = (mins > 0 ? mins * 60 : 0) + secs;

            if (seconds_ - 5 == Math.round(this.currentTime())) {
                // save progress & as complete
                currentVideo.complete = true;
                completedVideo(currentVideo);
            }

            currentVideo_autosave += 1;
            if (currentVideo_autosave == 40) {
                $.ajax({
                    url: "/course/live/progress",
                    method: "POST",
                    data: {
                        course_id: $(`input[name="course_id"]`).val(),
                        preview_: _is_preview,
                        _token: $('input[name*="_token"]').val(),
                        type: "video",
                        data_: currentVideo,
                    },
                    success: function () {
                        setTimeout(() => {
                            currentVideo_autosave = 0;
                        }, 1000);
                    },
                });
            }
        });

        MyVideoJS.currentTime(video.current_play_time);
    }
}

function extract_file_ext(filename) {
    var split = filename.split(".");
    var file_ext = split[split.length - 1];
    var fext = file_ext;
    switch (file_ext.toLowerCase()) {
        case `mov`:
        case `mp4`:
            fext = `mp4`;
            break;
    }

    return fext.toLowerCase();
}

/**
 *
 * fetching data of course's sections
 */
function get_sections() {
    $.ajax({
        url: "/course/live/api/sections",
        data: {
            course_id: $(`input[name="course_id"]`).val(),
            preview_: _is_preview,
        },
        success: function (response) {
            generate_sections(response.data);
            var progress_ = response.progress;

            if (_is_preview == "live") {
                current_progress = progress_.current;
                total_progress = progress_.total;

                init_progress_circle();
                progress_label = $(`#live-course-progress-title`);
                progress_label.html(
                    `${current_progress} out of ${total_progress} complete`
                );
            }
        },
        error: function () {
            toastr.error(
                "Error!",
                "Something went wrong! Please refresh your browser"
            );
        },
    });
}

function generate_sections(data) {
    var sidemenu_sections = $(`#sidemenu-course-content-accordion`);
    var tabcontent_sections = $(`#tab-course-content-accordion`);

    var sections = data.section_content_info;
    sections.forEach((section) => {
        var sequence = section.detailed_sequence;
        var card = $(`<div class="card" />`);
        var card_header = $(`<div class="card-header">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#course-content-collapse-${section.id}" aria-expanded="false" aria-controls="course-content-collapse-${section.id}">
                <h5>
                    ${section.title} <br/>
                    <small>0/${sequence.length} | ${section.total_time} min</small>
                </h5>
            </div>
        </div>`);

        var card_body_wrapper = $(
            `<div id="course-content-collapse-${section.id}" class="collapse" data-parent="" />`
        );
        var card_body = $(`<div class="card-body" />`);
        var section_list = $(`<div class="kt-checkbox-list" />`);

        var section_item_completion_list = [];
        sequence.forEach((data, index) => {
            switch (data.type) {
                case `video`:
                    var video_tab = $(`<label class="kt-checkbox" onclick="renderMainContent({type:'video', id:${data.id}, section: ${section.id}, rotation:${data.rotation}})">
                        <input type="checkbox" name="section_part_checkbox" data-status="${data.complete}" data-rotation="${data.rotation}" data-type="video">
                        ${data.title}<br>
                        <i class="fa fa-play-circle"></i> &nbsp; ${data.video_length} min
                        <span></span>
                    </label>`);

                    section_list.append(video_tab);
                    initialize_videoJS(data);
                    break;

                case `article`:
                    var article_tab = $(`<label class="kt-checkbox" onclick="renderMainContent({type:'article', id:${data.id}, section: ${section.id}, rotation: ${data.rotation}})">
                        <input type="checkbox" name="section_part_checkbox" data-status="${data.complete}" data-rotation="${data.rotation}" data-type="article">
                        ${data.title} <br>
                        <i class="fa fa-newspaper"></i> &nbsp; ${data.reading_time} min read
                        <span></span>
                    </label>`);

                    section_list.append(article_tab);
                    break;

                case `quiz`:
                    var quiz_tab = $(`<label class="kt-checkbox" onclick="renderMainContent({type:'quiz', id:${
                        data.id
                    }, section: ${section.id}, rotation: ${data.rotation}})">
                        <input type="checkbox" name="section_part_checkbox" data-status="${
                            data.complete
                        }" data-rotation="${data.rotation}" data-type="quiz">
                        ${data.title} <br>
                        <i class="fa fa-list"></i> &nbsp; ${
                            data.items.length
                        } item${data.items.length > 1 ? "s" : ""}
                        <span></span>
                    </label>`);

                    section_list.append(quiz_tab);
                    break;
            }
        });

        card_body.append(section_list);
        card_body_wrapper.append(card_body);
        card.append(card_header).append(card_body_wrapper);

        var clone_tab = card.clone();
        var clone_sidemenu = card.clone();

        // assign to corresponding placement
        clone_tab
            .find(`div.card-title`)
            .attr(`data-target`, `#tab-course-content-collapse-${section.id}`)
            .attr(`aria-controls`, `tab-course-content-collapse-${section.id}`);
        clone_tab
            .find(`div#course-content-collapse-${section.id}`)
            .attr(`id`, `tab-course-content-collapse-${section.id}`);

        clone_sidemenu
            .find(`div.card-title`)
            .attr(
                `data-target`,
                `#sidemenu-course-content-collapse-${section.id}`
            )
            .attr(
                `aria-controls`,
                `sidemenu-course-content-collapse-${section.id}`
            );
        clone_sidemenu
            .find(`div#course-content-collapse-${section.id}`)
            .attr(`id`, `sidemenu-course-content-collapse-${section.id}`);

        tabcontent_sections.append(clone_tab);
        sidemenu_sections.append(clone_sidemenu);

        section_content_progress.push({
            id: section.id,
            title: section.name,
            complete: false,
            items: section_item_completion_list,
        });
    });

    section_content_progress = sections;
    section_content_rotation = data.section_content_rotation;

    showFirstRotation();
    validation_on_checkbox_list();
    // toastr.success("Your live course is ready!");
}

/**
 *
 * First incomplete section to parts
 */
function showFirstRotation() {
    /**
     *
     * control on foreach loop
     */
    var stop = false;
    section_content_progress.forEach((section) => {
        if (section.complete == false) {
            section.detailed_sequence.forEach((part) => {
                if (part.complete == false && stop == false) {
                    stop = true;
                    renderMainContent(part);
                }
            });
        }
    });
}

/**
 * Sample Main Content Rotation through prev and next buttons
 * action
 * prev, next
 *
 */
function displayLiveCourseContent(action) {
    if (action == "next") {
        currentRotation += 1;
        var data = section_content_rotation.find(
            (variable, index) => currentRotation == index
        );
        if (data) {
            renderMainContent({
                id: data.id,
                type: data.type,
                section: data.section_id,
                rotation: currentRotation,
            });
        } else {
            displayLiveCourseNavigators(false, true);
            $(`#leave-a-rating-content`).modal("show");
        }
    } else {
        currentRotation -= 1;
        currentRotation = currentRotation <= 0 ? 0 : currentRotation;
        var data = section_content_rotation.find(
            (v, index) => currentRotation == index
        );
        if (data) {
            renderMainContent({
                id: data.id,
                type: data.type,
                section: data.section_id,
                rotation: currentRotation,
            });
        }
    }
}

function displayLiveCourseNavigators(right, left) {
    var navleft_button = $(`.course-nav-left--btn`);
    var navright_button = $(`.course-nav-right--btn`);

    navright_button.css("display", right ? "block" : "none");
    navleft_button.css("display", left ? "block" : "none");
}

/**
 * Sample Main Content Rotation
 * type                 | id | rotation
 * video, quiz, article | id | index of the array
 *
 */
function renderMainContent(data) {
    currentRotation = data.rotation;
    if (MyVideoJS) {
        MyVideoJS.pause();
    }

    var wrapper = $(`#transition-content-wrapper`);
    var nonvideo_content = $("#non-video-content");
    var video_content = $(".video-js");

    if (data.type == "video") {
        if (data.rotation == 0) {
            displayLiveCourseNavigators(true, false);
        } else if (data.rotation + 1 == section_content_rotation.length) {
            displayLiveCourseNavigators(false, true);
        } else {
            displayLiveCourseNavigators(true, true);
        }

        currentVideo = section_content_rotation.find(
            (varData) => varData.type == "video" && varData.id == data.id
        );
        wrapper
            .addClass(`transition-content-wrapper--video`)
            .removeClass(`transition-content-wrapper--row`);
        video_content.show();
        nonvideo_content.hide();

        var vext_ = extract_file_ext(currentVideo.filename);
        MyVideoJS.poster(currentVideo.poster);
        MyVideoJS.src({
            type: `video/${vext_}`,
            src: `${currentVideo.source}#t=${currentVideo.current_play_time}`,
        });
        MyVideoJS.thumbnails(currentVideo.thumbnail);
    } else {
        displayLiveCourseNavigators(false, false);
        wrapper
            .addClass(`transition-content-wrapper--row`)
            .removeClass(`transition-content-wrapper--video`);
        video_content.hide();

        if (data.type == "quiz") {
            currentQuiz = section_content_rotation.find(
                (varData) => varData.type == "quiz" && varData.id == data.id
            );
            currentQuizRotation = 0;

            if (currentQuiz.status == "none") {
                nonvideo_content
                    .empty()
                    .append(
                        `
                    <div class="kt-portlet" id="quiz-start-page">
                        <div class="kt-portlet__body">
                            <div class="row justify-content-center">
                                <h3>
                                    ${currentQuiz.title} <br/>
                                    <small>${currentQuiz.items.length} item${
                            currentQuiz.items.length > 1 ? "s" : ""
                        }</small>
                                </h3>
                            </div>
                            <div class="kt-space-20"></div>
                            <div class="kt-space-20"></div>
                            <button class="btn btn-label-danger" onClick="startQuiz({
                                'section': ${currentQuiz.section_id},
                                'id': ${data.id},
                                'items': ${currentQuiz.items.length},
                                'status': '${currentQuiz.status}'
                            })">Start Quiz</button>
                        </div>
                    </div>
                `
                    )
                    .append(renderQuiz(currentQuiz));
            } else if (currentQuiz.status == "passed") {
                var QuizOverall = JSON.parse(currentQuiz.overall);
                nonvideo_content.empty()
                    .append(`<div class="kt-portlet" id="quiz-start-page">
                    <div class="kt-portlet__body">
                        <div id="quiz-completion-page" class="row justify-content-center">
                            <div class="col-10">
                                <h4>
                                    Quiz Completed! You're ready to move on to the next lecture<br/>
                                    <small>You got <b>${QuizOverall.total}/${QuizOverall.items} - ${QuizOverall.percentage}%</b> correct answers</small>
                                </h4>
                            </div>
                            <div class="col-10"><button class="btn btn-sm btn-label-success button-done-quiz" onclick="displayLiveCourseContent('next');">Done</button></div>
                        </div>
                    </div>
                </div>`);
            } else {
                nonvideo_content
                    .empty()
                    .append(
                        `
                    <div class="kt-portlet" id="quiz-start-page">
                        <div class="kt-portlet__body">
                            <div class="row justify-content-center">
                                <h3>
                                    ${currentQuiz.title} <br/>
                                    <small>${currentQuiz.items.length} item${
                            currentQuiz.items.length > 1 ? "s" : ""
                        }</small>
                                </h3>
                            </div>
                            <div class="kt-space-20"></div>
                            <div class="kt-space-20"></div>
                            <button class="btn btn-warning" onClick="startQuiz({
                                'section': ${currentQuiz.section_id},
                                'id': ${data.id},
                                'items': ${currentQuiz.items.length},
                                'status': '${currentQuiz.status}'
                            })">Continue Quiz</button>
                        </div>
                    </div>
                `
                    )
                    .append(renderQuiz(currentQuiz));
            }
        }

        if (data.type == "article") {
            var currentArticle = section_content_rotation.find(
                (varData) => varData.type == "article" && varData.id == data.id
            );
            nonvideo_content.empty().append(`
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">${currentArticle.title}</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-scroll" style="height:250px;overflow-y:scroll;">
                    ${currentArticle.body}
                    </div>
                    <br/>
                    <button class="btn btn-label-success button-done-article" onclick='completedArticle({
                        "id": ${data.id},
                        "section": ${currentArticle.section_id},
                        "rotation": ${data.rotation},
                    });'>Done</button>
                </div>
            </div>
            `);
        }

        nonvideo_content.show();
    }
}

/**
 * Quiz Rendering item bodies
 *
 */
function renderQuiz(params) {
    var quiz_wrapper = $(`<div class="kt-portlet" id="quiz-wrapper-items"/>`);
    var quiz_head = $(`<div class="kt-portlet__head" />`).append(
        `<div class="kt-portlet__head-label"><h3 class="kt-portlet__head-title">${
            params.title
        } <small id="current_quiz_pagination">0/${params.items.length} item${
            params.items.length > 1 ? "s" : ""
        }</small></h3></div>`
    );
    var quiz_body = $(`<div class="kt-portlet__body" />`);

    params.items.forEach((varData, index) => {
        var quiz_item_body = $(
            `<div class="row justify-content-center" id="question_item_${varData.id}" />`
        );
        var quiz_item_choices = ``;
        var item_choices = JSON.parse(varData.choices);

        item_choices.forEach((choice) => {
            quiz_item_choices += `<div class="col-xl-3 col-md-3 col-sm-3 col-6">
                <label class="kt-radio kt-radio--bold">
                    <input type="radio" value="${
                        choice.answer
                    }" name="choices_item_${varData.id}"> ${choice.choice}
                    <span></span>
                </label> <br/>
                <span class="kt-hidden btn btn-success">${
                    choice.explain ? choice.explain : ""
                }</span>
            </div>`;
        });

        var quiz_item_buttons = `<div class="col-10"><button class="btn btn-sm btn-outline-danger" style="float:right" onclick="quizAnswerItem({
            'id': ${varData.id}, 
            'index': ${index},
            'quiz': ${params.id},
        })">Answer</button></div>`;

        quiz_item_body.append(`
            <div class="col-10"><p>${varData.question}</p></div>
            <div class="col-10">
                <div class="kt-space-20"></div>
                <div class="kt-space-20"></div>
                <div class="row justify-content-center">${quiz_item_choices}</div>
                <div class="kt-space-20"></div>
                <div class="kt-space-20"></div>
            </div>${quiz_item_buttons}
        `);

        if (index > 0) {
            quiz_item_body.hide();
        }

        quiz_body.append(quiz_item_body);
    });

    var quiz_completion_page = $(`<div id="quiz-completion-page" class="row justify-content-center">
            <div class="col-10">
                <h4>
                    Quiz Completed! You're ready to move on to the next lecture<br/>
                    <small id="current_quiz_scoring"></small>
                </h4>
            </div>
            <div class="col-10"><button class="btn btn-sm btn-label-success button-done-quiz" onclick="completedQuiz({'id':${params.id}, 'rotation': ${params.rotation}});">Done</button></div>
        </div>`);
    quiz_completion_page.hide();
    quiz_body.append(quiz_completion_page);

    return quiz_wrapper.append(quiz_head).append(quiz_body).hide();
}

function startQuiz(data) {
    var new_status = data.status == "none" ? "in-progress" : data.status;
    if (quizScores.length == 0) {
        quizScores.push({
            section: data.section,
            id: data.id,
            total: 0,
            items: data.items,
            status: new_status,
        });

        saveQuizProgress({
            section: data.section,
            id: data.id,
            overall: {
                total: 0,
                items: data.items,
                percentage: 0,
            },
            status: new_status,
        });
    } else {
        var find_q = quizScores.find((varData) => varData.id == data.id);
        if (find_q) {
            console.log(`Quiz has been revisited!`);
        } else {
            quizScores.push({
                section: data.section,
                id: data.id,
                total: 0,
                items: data.items,
                status: new_status,
            });

            saveQuizProgress({
                section: data.section,
                id: data.id,
                overall: {
                    total: 0,
                    items: data.items,
                    percentage: 0,
                },
                status: new_status,
            });
        }
    }

    $("#quiz-wrapper-items").show();
    $("#quiz-start-page").hide();
}

/**
 * Quiz Rendering next item body
 *
 */
function quizNextItem() {
    currentQuizRotation += 1;

    var the_score = quizScores.find((find_q) => currentQuiz.id == find_q.id);
    $(`#current_quiz_pagination`).html(
        `${currentQuizRotation}/${currentQuiz.items.length} item${
            currentQuiz.items.length > 1 ? "s" : ""
        }`
    );
    if (currentQuiz.items.length == currentQuizRotation) {
        currentQuiz.items.forEach((item, index) => {
            $(`#question_item_${item.id}`).hide();
        });

        var score_percentage =
            the_score.total != 0
                ? (the_score.total / currentQuiz.items.length) * 100
                : 0;
        $(`#current_quiz_scoring`).html(
            `You got <b>${the_score.total}/${currentQuiz.items.length} - ${score_percentage}%</b> correct answers`
        );
        $(`#quiz-completion-page`).show();
    } else {
        currentQuiz.items.forEach((item, index) => {
            if (index == currentQuizRotation) {
                $(`#question_item_${item.id}`).show();
            } else {
                $(`#question_item_${item.id}`).hide();
            }
        });
    }
}

function quizAnswerItem(item) {
    var question_item_ = $(`#question_item_${item.id}`);
    var answer = $(`input[name="choices_item_${item.id}"]:checked`);
    var items = $(`input[name="choices_item_${item.id}"]:not(:checked)`);

    question_item_
        .find(`button.btn-outline-danger`)
        .removeClass(`btn-outline-danger`)
        .addClass(`btn-success`)
        .html(`Next`)
        .attr(`onclick`, `quizNextItem()`);
    if (answer.length != 0) {
        items.each(function () {
            $(this).attr(`disabled`, `disabled`);
        });

        if (answer.val() == "false") {
            answer.parent().addClass(`kt-radio--danger`);
            items.each(function () {
                var current = $(this);
                if (current.val() == "true") {
                    current
                        .parent()
                        .addClass(`kt-radio--success`)
                        .next()
                        .next()
                        .removeClass(function () {
                            if ($(this).html() != "") {
                                return `kt-hidden`;
                            }
                        });
                }
            });
        } else {
            var current = answer;
            if (current.val() == "true") {
                current
                    .parent()
                    .addClass(`kt-radio--success`)
                    .next()
                    .next()
                    .removeClass(function () {
                        if ($(this).html() != "") {
                            return `kt-hidden`;
                        }
                    });
            }
        }

        quizScores = quizScores.map((data) => {
            if (data.id == item.quiz) {
                data.current = data.current + 1;
                data.total =
                    answer.val() == "true" ? data.total + 1 : data.total;

                var score_percentage =
                    data.total != 0 ? (data.total / data.items) * 100 : 0;
                if (score_percentage < 75) {
                    data.status = "failed";
                } else {
                    data.status = "passed";
                }

                saveQuizProgress({
                    section: data.section,
                    id: data.id,
                    overall: {
                        total: data.total,
                        items: data.items,
                        percentage: score_percentage,
                    },
                    status: data.status,
                });
            }

            return data;
        });
    } else {
        toastr.error(`Please provide an answer!`);
    }
}

function completedQuiz(data) {
    var done_button = $(`button.button-done-quiz`);
    done_button.addClass(
        "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
    );

    var checkbox_ = $(`input[data-rotation="${currentRotation}"]`);
    if (checkbox_.data("status") == false) {
        if (currentQuiz.status == "passed") {
            if (_is_preview == "live") {
                current_progress += 1;
                circle2.animate(current_progress / total_progress);
                circle1.animate(current_progress / total_progress);
                progress_label.html(
                    `${current_progress} out of ${total_progress} complete`
                );
            }

            checkbox_.prop("checked", true);
            checkbox_.data("status", true);
        }
    }

    setTimeout(() => {
        done_button.removeClass(
            "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
        );
        displayLiveCourseContent("next");
    }, 1000);

    currentQuizRotation = 0;
    currentQuiz = {};
}

function saveQuizProgress(data) {
    $.ajax({
        url: "/course/live/progress",
        method: "POST",
        data: {
            course_id: $(`input[name="course_id"]`).val(),
            preview_: _is_preview,
            _token: $('input[name*="_token"]').val(),
            type: "quiz",
            data_: data,
        },
    });

    currentQuiz.status = data.status;
    currentQuiz.overall = JSON.stringify(data.overall);
}

function completedArticle(article) {
    var done_button = $(`button.button-done-article`);
    done_button.addClass(
        "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
    );

    var checkbox_ = $(`input[data-rotation="${currentRotation}"]`);
    if (checkbox_.data("status") == false) {
        if (_is_preview == "live") {
            current_progress += 1;
            circle2.animate(current_progress / total_progress);
            circle1.animate(current_progress / total_progress);
            progress_label.html(
                `${current_progress} out of ${total_progress} complete`
            );
        }

        checkbox_.prop("checked", true);
        checkbox_.data("status", true);

        $.ajax({
            url: "/course/live/progress",
            method: "POST",
            data: {
                course_id: $(`input[name="course_id"]`).val(),
                preview_: _is_preview,
                _token: $('input[name*="_token"]').val(),
                type: "article",
                data_: article,
            },
            success: function () {
                toastr.success("Good!", "You've finished an article!");

                displayLiveCourseContent("next");
                done_button.removeClass(
                    "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
                );
            },
            error: function () {
                toastr.error(
                    "Error!",
                    "Something went wrong! Please refresh your browser"
                );
                done_button.removeClass(
                    "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
                );
            },
        });
    } else {
        displayLiveCourseContent("next");
    }
}

function completedVideo(video) {
    if (completed_video_sent == false) {
        completed_video_sent = true;

        var checkbox_ = $(`input[data-rotation="${currentRotation}"]`);
        if (checkbox_.data("status") == false) {
            if (_is_preview == "live") {
                current_progress += 1;
                circle2.animate(current_progress / total_progress);
                circle1.animate(current_progress / total_progress);
                progress_label.html(
                    `${current_progress} out of ${total_progress} complete`
                );
            }

            checkbox_.prop("checked", true);
            checkbox_.data("status", true);
        }

        $.ajax({
            url: "/course/live/progress",
            method: "POST",
            data: {
                course_id: $(`input[name="course_id"]`).val(),
                preview_: _is_preview,
                _token: $('input[name*="_token"]').val(),
                type: "video",
                data_: video,
            },
            success: function () {
                setTimeout(() => {
                    completed_video_sent = false;
                }, 1000);
            },
        });
    }
}

/**
 * Show & Hide Course Content
 *
 */
function courseContentEvent(open) {
    if (open) {
        $(`#main-content`).removeClass(`col-12`).addClass(`col-xl-8 col-md-8`);
        $(`#sidemenu-course-content`).show(
            `slide`,
            { direction: `right` },
            350
        );
        $(`.course-content-open-btn`).hide(
            `slide`,
            { direction: `right` },
            350
        );
        $(`.hidden-course-content`).hide(`slide`, { direction: `left` }, 350);
        $(`#tab-course-content`)
            .removeClass("active")
            .next()
            .addClass("active");

        return;
    }

    $(`#main-content`).removeClass(`col-xl-8 col-md-8`).addClass(`col-12`);
    $(`#sidemenu-course-content`).hide(`slide`, { direction: `right` }, 350);
    $(`.hidden-course-content`).show(`slide`, { direction: `left` }, 350);
    $(`.course-content-open-btn`)
        .show(`slide`, { direction: `right` }, 350)
        .hover(
            function () {
                $(`.btn-label-content`).html(`Show Course Content`);
                $(this).removeClass(`btn-icon`);
            },
            function () {
                $(`.btn-label-content`).html(``);
                $(this).addClass(`btn-icon`);
            }
        );
}

/**
 * Leave a Rating
 *
 */
function oneStepBack() {
    rating_step -= 1;

    if (rating_step == 0) {
        $(`#feedback-rating-textarea`).hide(
            "slide",
            { direction: "right" },
            300
        );
        $(`#feedback-back-button`).hide("slide", { direction: "right" }, 300);
    }

    if (rating_step == 1) {
        $(`#feedback-question-content`).hide(
            "slide",
            { direction: "right" },
            300
        );
        $(`#rating-content-body`).show("slide", { direction: "right" }, 300);
    }

    if (rating_step == 2) {
        $(`#feedback-question-content`).show(
            "slide",
            { direction: "right" },
            300
        );
        $(`#feedback-final-content`).hide("slide", { direction: "right" }, 300);
    }
}

function oneStepForward() {
    rating_step += 1;

    if (rating_step == 2) {
        $(`#feedback-question-content`).show(
            "slide",
            { direction: "right" },
            300
        );
        $(`#rating-content-body`).hide("slide", { direction: "right" }, 300);
    }

    if (rating_step == 3) {
        $(`#feedback-question-content`).hide(
            "slide",
            { direction: "right" },
            300
        );
        $(`#feedback-final-content`).show("slide", { direction: "right" }, 300);
    }

    if (rating_step >= 4) {
        $(`#feedback-final-content`).hide("slide", { direction: "right" }, 300);
        $(`#leave-a-rating-content`).modal("hide");

        /**
         * Reset
         *
         */
        $(`#feedback-rating-textarea`).hide(
            "slide",
            { direction: "right" },
            300
        );
        $(`#rating-content-body`).show("slide", { direction: "right" }, 300);
        $(`#feedback-back-button`).hide("slide", { direction: "right" }, 300);

        rating_step = 0;
    }
}

/**
 *
 * Pass the selector in any form
 *
 */
function copy_(element) {
    var copyText = $(element);
    copyText.select();
    document.execCommand("copy", false);

    toastr.info("Copied!");
}

function open_send_email_modal(element) {
    var link = $(element).val();
    $(`#share-to-email-modal`).modal("show");
}

function send_email() {
    toastr.success("Email has been sent!");
}

function validation_on_checkbox_list() {
    /**
     *
     * Section Part checkbox validation and uix
     *
     */
    $(`input[name="section_part_checkbox"]`).each(function () {
        var current = $(this);
        if (current.data("status") == false) {
            current.prop("checked", false);
        } else {
            current.prop("checked", true);
        }
    });

    $(`input[name="section_part_checkbox"]`).click(function () {
        var current = $(this);
        if (current.data("status") == false) {
            current.prop("checked", false);
        } else {
            current.prop("checked", true);
        }
    });
}

function init_progress_circle() {
    // small screen
    circle1 = new ProgressBar.Circle("#live-course-progress-circle1", {
        strokeWidth: 10,
        easing: "easeInOut",
        duration: 1400,
        color: "#20c997",
        trailColor: "#fff",
        trailWidth: 1,
        svgStyle: null,
        step: function (state, circle) {
            circle.setText('<i class="fa fa-trophy"></i>');
        },
    });

    // big screen
    circle2 = new ProgressBar.Circle("#live-course-progress-circle2", {
        strokeWidth: 10,
        easing: "easeInOut",
        duration: 1400,
        color: "#20c997",
        trailColor: "#fff",
        trailWidth: 1,
        svgStyle: null,
        step: function (state, circle) {
            circle.setText('<i class="fa fa-trophy"></i>');
        },
    });

    circle2.animate(current_progress / total_progress);
    circle1.animate(current_progress / total_progress);
}

</script>
@endsection