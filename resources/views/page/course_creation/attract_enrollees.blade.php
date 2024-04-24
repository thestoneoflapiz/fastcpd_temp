@extends('template.master_course_creation')
@section('styles')
<style>
    .accordion .card .card-header .card-title.collapsed > i{color:#5d78ff;}
    .accordion.accordion-toggle-arrow .card .card-header .card-title{color:#343a40;}
    .fastcpd-background{background-size:contain;background-repeat:no-repeat;background-position:center;height:320px;width:100%;border:1px solid #F1F2F7;border-radius:5px;-webkit-box-shadow: 4px 4px 10px -9px rgba(0,0,0,0.49);-moz-box-shadow: 4px 4px 10px -9px rgba(0,0,0,0.49);box-shadow: 4px 4px 10px -9px rgba(0,0,0,0.49);}
    @media (max-width: 720px) {
        .fastcpd-background{height:250px;}
    }
</style>
@endsection

@section('content')
{{ csrf_field() }}
<input type="hidden" name="digest" value="<?=_current_provider()->id.":"._current_course()->id.":".uniqid()?>" />
<input type="hidden" name="s3bname" value="<?=Storage::disk('s3')->getDriver()->getAdapter()->getBucket()?>" />
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Attract Enrollments
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <!--begin::Accordion-->
        <div class="accordion  accordion-toggle-arrow">
            <div class="card">
                <div class="card-header" id="course_poster_acc_parent">
                    <div class="card-title collapsed" data-toggle="collapse" data-target="#course_poster_acc" aria-expanded="false" aria-controls="course_poster_acc">
                        @if(_current_course()->course_poster) <span><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i> </span> &nbsp; &nbsp; @endif<i class="fa fa-image circle-icon"></i> Posters 
                    </div>
                </div>
                <div id="course_poster_acc" class="collapse" aria-labelledby="course_poster_acc_parent" data-parent="#course_poster_acc_parent">
                    <div class="card-body">
                       <div class="row">
                            <div class="col-xl-6 col-lg-8 col-md-10 col-12 course-poster">
                                <div class="form-group">
                                    <span class="form-text text-muted"></span>
                                    <input type="hidden" name="course_poster" value="{{ _current_course()->course_poster }}"/>
                                    @if(_current_course()->course_poster)
                                    <div class="fastcpd-background" style="background-image: url('{{ _current_course()->course_poster }}');"></div>
                                    @else
                                    <div class="fastcpd-background" style="background-image: url('{{ asset('img/sample/poster-sample.png') }}');"></div>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-xl-6 col-md-12 col-12">
                                <p>
                                    Make your course standout with a great image from our design team based on your preferences and style. Get your free image. <br/><br/>
                                    If you create your image, it must meet our course image quality standards to be accepted. <br/><br/>
                                    Important guidlines:<br/>
                                    <b>Preferably 1080x600 aspect ratio</br>Image file formats: .jpg, jpeg, .gif, or .png</b>
                                </p>
                                <div class="kt-space-20"></div>
                                <div class="kt-space-20"></div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="kt-uppy" id="uppy_course_poster">
                                            <div class="kt-uppy__wrapper"></div>
                                            <div class="kt-uppy__list"></div>
                                            <div class="kt-uppy__status"></div>
                                            <div class="kt-uppy__informer kt-uppy__informer--min"></div>
                                        </div>
                                        <span class="form-text">Max file size is 5MB and max number of files is 1.</span>
                                    </div>
                                </div>
                            </div>
                       </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="course_pv_acc_parent">
                    <div class="card-title collapsed" data-toggle="collapse" data-target="#course_pv_acc" aria-expanded="false" aria-controls="course_pv_acc" id="course_pv_acc_title">
                        @if(_current_course()->course_video) <span><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i> </span> &nbsp; &nbsp; @endif<i class="fa fa-video circle-icon"></i> Promotional Video
                    </div>
                </div>
                <div id="course_pv_acc" class="collapse" aria-labelledby="headingTwo1" data-parent="#course_pv_acc_parent">
                    <div class="card-body">
                       <div class="row">
                            <div class="col-xl-6 col-lg-8 col-md-10 col-12 course-poster">
                                <div class="form-group">
                                    <span class="form-text text-muted"></span>
                                    <input type="hidden" name="course_video" value="{{ _current_course()->course_video }}"/>
                                    @if(_current_course()->course_video)
                                    <div class="fastcpd-background video-background">
                                        <video controls style="height:320px;width:100%;" id="preview-video-source">
                                        <?php 
                                            $video_extension_course = explode('.',_current_course()->course_video);
                                            $video_extension_course = end($video_extension_course);
                                            $video_extension_course = strtolower($video_extension_course)=="mov" ? "mp4" : strtolower($video_extension_course);
                                        ?>
                                            <source src="{{ _current_course()->course_video }}" type="video/{{ $video_extension_course }}">
                                        </video>
                                    </div>
                                    @else
                                    <div class="fastcpd-background video-background" style="background-image: url({{ asset('img/sample/poster-sample.png') }});"></div>
                                    @endif

                                    <div class="progress" id="progress-bar-video" style="height:10px;display:none;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated " role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 1%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-12 col-12">
                                <p>
                                    Students who watch a well-made promo video are <b>5x more likely to enroll</b> in your course.<br>
                                    we've been seen that statistic to go up 10X for exceptionally awesome videos.<br>
                                    Learn how to make yours awesome.<br/><br/>
                                    Important guidlines: <br/>
                                    <b>Preferably 720p or lesser video resolution<br/>Less than 20MB video file size<br/>Video file formats: .mp4 or .mov </b>
                                </p>
                                <div class="kt-space-20"></div>
                                <div class="kt-space-20"></div>
                                
                                <div class="row">
                                    <div class="col-12" id="file-video-buttons">
                                        @if(_current_course()->course_video)
                                        <button class="btn btn-danger" id="remove-file-video" onclick="removeCourseVideo()">Remove file</button>
                                        @else
                                        <label class="btn btn-label-brand btn-bold btn-font-sm" for="video_file">Attach file</label>
                                        <input type="file" id="video_file" style="opacity: 0;position: absolute;z-index: -1;" accept=".mov, .mp4, video/quicktime"/>
                                        <span class="form-text text-muted">Max file size is 5MB and max number of files is 1.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                       </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="course_obj_acc_parent">
                    <div class="card-title collapsed" data-toggle="collapse" data-target="#course_obj_acc" aria-expanded="false" aria-controls="course_obj_acc">
                        @if(_current_course()->objectives) <span><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i> </span> &nbsp; &nbsp; @endif<i class="fa fa-book-open circle-icon"></i> What will students learn in your course?
                    </div>
                </div>
                <div id="course_obj_acc" class="collapse" aria-labelledby="headingThree1" data-parent="#course_obj_acc_parent">
                    <div class="card-body">
                        <form class="kt-form" id="course_objective_form">
                            <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="course_objective_form_msg">
                                <div class="kt-alert__icon">
                                    <i class="fa fa-exclamation-triangle"></i>
                                </div>
                                <div class="kt-alert__text">&nbsp; Sorry! You have to complete the form requirements first!</div>
                                <div class="kt-alert__close">
                                    <button type="button" class="close" data-close="alert" aria-label="Close">
                                    </button>
                                </div>
                            </div>
                            <div class="row" id="repeater_objectives">
                                <label class="col-lg-2 col-form-label">Example: Identify AML suspicious transactions</label>
                                <div class="col-lg-10" >
                                    <div data-repeater-list>
                                        @if(_current_course()->objectives != null && count(json_decode(_current_course()->objectives)) > 0)
                                            @foreach(json_decode(_current_course()->objectives) as $objective_value)
                                            <div data-repeater-item class="form-group row align-items-center">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                    <div class="kt-form__group--inline">
                                                        <div class="kt-form__label">
                                                            <label>Objective</label>
                                                        </div>
                                                        <div class="kt-form__control">
                                                            <input type="text" class="form-control objective_input" name="objective" placeholder="Enter your objective" value="{{ $objective_value }}">
                                                        </div>
                                                    </div>
                                                    <span class="form-text text-muted"></span>
                                                    <div class="d-md-none kt-margin-b-10"></div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <div class="kt-space-20"></div>
                                                    <a href="javascript:;" data-repeater-delete class="btn-sm btn btn-warning btn-bold"><i class="la la-trash-o"></i> Delete</a>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                        <div data-repeater-item class="form-group row align-items-center">
                                            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                <div class="kt-form__group--inline">
                                                    <div class="kt-form__label">
                                                        <label>Objective</label>
                                                    </div>
                                                    <div class="kt-form__control">
                                                        <input type="text" class="form-control objective_input" name="objective" placeholder="Enter your objective">
                                                    </div>
                                                </div>
                                                <span class="form-text text-muted"></span>
                                                <div class="d-md-none kt-margin-b-10"></div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                <div class="kt-space-20"></div>
                                                <a href="javascript:;" data-repeater-delete class="btn-sm btn btn-warning btn-bold"><i class="la la-trash-o"></i> Delete</a>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <a href="javascript:;" data-repeater-create class="btn btn-bold btn-sm btn-label-brand"> <i class="la la-plus"></i> Add</a>
                                </div>
                            </div>
                            <div class="row" style="float:right">
                                <div class="col-lg-12 ml-lg-xl-auto">
                                    <button class="btn btn-success">Submit</button>
                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                    <div class="kt-space-20"></div>
                                    <div class="kt-space-20"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="course_req_acc_parent">
                    <div class="card-title collapsed" data-toggle="collapse" data-target="#course_req_acc" aria-expanded="false" aria-controls="course_req_acc">
                        @if(_current_course()->requirements) <span><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i> </span> &nbsp; &nbsp; @endif<i class="fa fa-scroll circle-icon"></i> Are there any course requirements or prerequisites?
                    </div>
                </div>
                <div id="course_req_acc" class="collapse" aria-labelledby="headingThree1" data-parent="#course_req_acc_parent">
                    <div class="card-body">
                        <form class="kt-form" id="course_requirement_form">
                            <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="course_requirement_form_msg">
                                <div class="kt-alert__icon">
                                    <i class="fa fa-exclamation-triangle"></i>
                                </div>
                                <div class="kt-alert__text">&nbsp; Sorry! You have to complete the form requirements first!</div>
                                <div class="kt-alert__close">
                                    <button type="button" class="close" data-close="alert" aria-label="Close">
                                    </button>
                                </div>
                            </div>
                            <div class="row" id="repeater_requirements">
                                <label class="col-lg-2 col-form-label">Example: Basic knowledge in medical terminology</label>
                                <div class="col-lg-10" >
                                    <div data-repeater-list>
                                        @if(_current_course()->requirements != null && count(json_decode(_current_course()->requirements)) > 0)
                                            @foreach(json_decode(_current_course()->requirements) as $requirement_value)
                                            <div data-repeater-item class="form-group row align-items-center">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                    <div class="kt-form__group--inline">
                                                        <div class="kt-form__label">
                                                            <label>Requirement</label>
                                                        </div>
                                                        <div class="kt-form__control">
                                                            <input type="text" class="form-control requirement_input" name="requirement" placeholder="Enter your requirement" value="{{ $requirement_value }}">
                                                        </div>
                                                    </div>
                                                    <span class="form-text text-muted"></span>
                                                    <div class="d-md-none kt-margin-b-10"></div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <div class="kt-space-20"></div>
                                                    <a href="javascript:;" data-repeater-delete class="btn-sm btn btn-warning btn-bold"><i class="la la-trash-o"></i> Delete</a>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                        <div data-repeater-item class="form-group row align-items-center">
                                            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                <div class="kt-form__group--inline">
                                                    <div class="kt-form__label">
                                                        <label>Requirement</label>
                                                    </div>
                                                    <div class="kt-form__control">
                                                        <input type="text" class="form-control requirement_input" name="requirement" placeholder="Enter your requirement">
                                                    </div>
                                                </div>
                                                <span class="form-text text-muted"></span>
                                                <div class="d-md-none kt-margin-b-10"></div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                <div class="kt-space-20"></div>
                                                <a href="javascript:;" data-repeater-delete class="btn-sm btn btn-warning btn-bold"><i class="la la-trash-o"></i> Delete</a>
                                            </div>
                                        </div>
                                        @endif                                        
                                    </div>
                                    <a href="javascript:;" data-repeater-create class="btn btn-bold btn-sm btn-label-brand"> <i class="la la-plus"></i> Add</a>
                                </div>
                            </div>
                            <div class="row" style="float:right">
                                <div class="col-lg-12 ml-lg-xl-auto">
                                    <button class="btn btn-success">Submit</button>
                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                    <div class="kt-space-20"></div>
                                    <div class="kt-space-20"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="course_target_acc_parent">
                    <div class="card-title collapsed" data-toggle="collapse" data-target="#course_target_acc" aria-expanded="false" aria-controls="course_target_acc">
                        @if(_current_course()->target_students) <span><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i> </span> &nbsp; &nbsp; @endif<i class="fa fa-crosshairs circle-icon"></i> Who are your target students?
                    </div>
                </div>
                <div id="course_target_acc" class="collapse" aria-labelledby="headingThree1" data-parent="#course_target_acc_parent">
                    <div class="card-body">
                        <form class="kt-form" id="course_target_form">
                            <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="course_target_form_msg">
                                <div class="kt-alert__icon">
                                    <i class="fa fa-exclamation-triangle"></i>
                                </div>
                                <div class="kt-alert__text">&nbsp; Sorry! You have to complete the form requirements first!</div>
                                <div class="kt-alert__close">
                                    <button type="button" class="close" data-close="alert" aria-label="Close">
                                    </button>
                                </div>
                            </div>
                            <div class="row" id="repeater_targets">
                                <label class="col-lg-2 col-form-label">Example: Engineers interested in robotics</label>
                                <div class="col-lg-10" >
                                    <div data-repeater-list>
                                        @if(_current_course()->target_students != null && count(json_decode(_current_course()->target_students)) > 0)
                                            @foreach(json_decode(_current_course()->target_students) as $target_value)
                                            <div data-repeater-item class="form-group row align-items-center">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                    <div class="kt-form__group--inline">
                                                        <div class="kt-form__label">
                                                            <label>Target Student</label>
                                                        </div>
                                                        <div class="kt-form__control">
                                                            <input type="text" class="form-control target_input" name="target" placeholder="Enter your target student" value="{{ $target_value }}">
                                                        </div>
                                                    </div>
                                                    <span class="form-text text-muted"></span>
                                                    <div class="d-md-none kt-margin-b-10"></div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <div class="kt-space-20"></div>
                                                    <a href="javascript:;" data-repeater-delete class="btn-sm btn btn-warning btn-bold"><i class="la la-trash-o"></i> Delete</a>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                        <div data-repeater-item class="form-group row align-items-center">
                                            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                                <div class="kt-form__group--inline">
                                                    <div class="kt-form__label">
                                                        <label>Target Student</label>
                                                    </div>
                                                    <div class="kt-form__control">
                                                        <input type="text" class="form-control target_input" name="target" placeholder="Enter your target student">
                                                    </div>
                                                </div>
                                                <span class="form-text text-muted"></span>
                                                <div class="d-md-none kt-margin-b-10"></div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                <div class="kt-space-20"></div>
                                                <a href="javascript:;" data-repeater-delete class="btn-sm btn btn-warning btn-bold"><i class="la la-trash-o"></i> Delete</a>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <a href="javascript:;" data-repeater-create class="btn btn-bold btn-sm btn-label-brand"> <i class="la la-plus"></i> Add</a>
                                </div>
                            </div>
                            <div class="row" style="float:right">
                                <div class="col-lg-12 ml-lg-xl-auto">
                                    <button class="btn btn-success">Submit</button>
                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                    <div class="kt-space-20"></div>
                                    <div class="kt-space-20"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Accordion-->
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('plugins/custom/uppy/dist/uppy.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/aws-sdk-2.754.0.min.js')}}"></script>
<script src="{{asset('js/course-creation/course-details/attract_enrollees.js')}}" type="text/javascript"></script>
<script src="{{asset('js/course-creation/course-details/ae_image_intervention.js')}}" type="text/javascript"></script>
<script src="{{asset('js/course-creation/course-details/ae_video_upload_aws.js')}}" type="text/javascript"></script>
@endsection