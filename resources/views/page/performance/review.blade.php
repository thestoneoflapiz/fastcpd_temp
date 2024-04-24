@extends('template.master_provider')
@section('title', _current_provider()->name)
@section('styles')
<style>
    .hidden{display:none;}
    .strong{font-weight:600;}
    .kt-widget5 .kt-widget5__item{margin-bottom:0;padding-bottom:0;}
    @media (max-width: 1024px){.kt-widget5 .kt-widget5__item .kt-widget5__content .kt-widget5__pic img{max-width:8.5rem !important;}}
    .text-align-right{text-align:right;}
    .kt-widget31 .kt-widget31__item .kt-widget31__content .kt-widget31__pic > img{width:4rem;border-radius:3px;}
</style>
@endsection
@section('content')
<div class="kt-container kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Reviews
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="form-group">
                                <span class="form-text text-muted"></span>
                                <select class="form-control kt-select2" id="review-select-type" name="param">
                                    <option selected></option>
                                   
                                    <option value="video-on-demand">Video on Demand</option>
                                    <option value="webinar">Webinar</option>
                                   
                                </select>
                            </div>
                            <div class="form-group" id="course">
                                <span class="form-text text-muted"></span>
                                <select class="form-control kt-select2" id="review-select-course" name="param">
                                    <option selected></option>
                                    @foreach (_provider_courses(_current_provider()->id) as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="webinar">
                                <span class="form-text text-muted"></span>
                                <select class="form-control kt-select2" id="review-select-webinar" name="param">
                                    <option selected></option>
                                    @foreach (_provider_webinars(_current_provider()->id) as $webinar)
                                        <option value="{{ $webinar->id }}">{{ $webinar->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="radio_list" style="display: none;">
                                <label class="kt-checkbox">
                                    <input type="radio" name="response" value="0"> No Response
                                    <span></span>
                                </label> <br />
                                <label class="kt-checkbox">
                                    <input type="radio" name="response" value="1"> Has a Comment
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-9">
                            <div class="alert alert-secondary" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                                <div class="alert-text">It can take up 24 hours for approved ratings to show on your course landing page</div>
                            </div>
                            <div class="row" id="course_details" style="display: none;">
                                <div class="kt-portlet kt-portlet--bordered">
                                    <div class="kt-portlet__body">
                                        <div class="kt-widget5">
                                            <div class="kt-widget5__item">
                                                <div class="kt-widget5__content">
                                                    <div class="kt-widget5__pic" id="course_pic">
                                                        <img class="kt-widget7__img" src="{{ asset('media/products/product27.jpg') }}" alt="">
                                                    </div>
                                                    <div class="kt-widget5__section">
                                                        <a href="#" class="kt-widget5__title" id="course_title">
                                                            Course Title
                                                        </a>
                                                        <p class="kt-widget5__desc" id="course_instructors">
                                                            Course Instructors
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="kt-widget5__content"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row"  id="rating_details" style="display: none;">
                                <div class="kt-portlet kt-portlet--bordered kt-portlet--head-lg">
                                    <div class="kt-portlet__head">
                                        <div class="kt-portlet__head-label" style="margin:auto;">
                                            <h3 class="kt-portlet__head-title"  style="color:#595d6e;text-align:center;">
                                                <br/>Your Course Rating
                                                {{-- <span class="rating" id="star_rates" style="color:#ffd700" ></span> &nbsp; &nbsp;<b id="star_rating" style="font-size:20px;">0.0</b>  --}}
                                                    <!--begin:: Widgets/Revenue Change-->   
                                                    <div class="kt-widget14" style="margin:auto;">
                                                        <div class="kt-widget14__content" style="margin:auto;">
                                                            <div class="kt-widget14__chart">
                                                                <div class="kt-widget14__stat"  style="font-weight: bold;color:black;">
                                                                    <span id="rating_count" style="text-align: center;"></span>
                                                                </div>
                                                                <canvas id="ratingsChart" style="height: 120px; width: 120px;"></canvas>
                                                            </div>
                                                        </div>                                                       
                                                    </div>
                                                    <!--end:: Widgets/Revenue Change-->
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="kt-portlet__body">
                                        <h5>Performance by course attribute</h5>
                                        <div class="kt-space-10"></div>
                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 col-8 text-align-right">Are you learning valueble information?</div>
                                            <div class="col-4" style="margin:auto 0 auto 0;">
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar kt-bg-info" id="valuable_info" role="progressbar" style="width: 0%;" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-md-2 col-2 text-align-left" id="valuable_info_percentage">0%</div>
                                        </div>
                                        <div class="kt-space-10"></div>
                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 col-8 text-align-right">Are the explanation of the concepts clear?</div>
                                            <div class="col-4" style="margin:auto 0 auto 0;">
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar kt-bg-info" id="concepts" role="progressbar" style="width: 0%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-md-2 col-2 text-align-left" id="concepts_percentage">0%</div>
                                        </div>
                                        <div class="kt-space-10"></div>
                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 col-8 text-align-right">Is the instructor's delivery engaging?</div>
                                            <div class="col-4" style="margin:auto 0 auto 0;">
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar kt-bg-info" id="instructor_delivery" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-md-2 col-2 text-align-left" id="instructor_delivery_percentage">0%</div>
                                        </div>
                                        <div class="kt-space-10"></div>
                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 col-8 text-align-right">Are there enough opportunities to apply what you're learning?</div>
                                            <div class="col-4" style="margin:auto 0 auto 0;">
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar kt-bg-info" id="opportunities" role="progressbar" style="width: 0%;" aria-valuenow="94" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-md-2 col-2 text-align-left" id="opportunities_percentage">0%</div>
                                        </div>
                                        <div class="kt-space-10"></div>
                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 col-8 text-align-right">Is the course delivering in your expectations?</div>
                                            <div class="col-4" style="margin:auto 0 auto 0;">
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar kt-bg-info" id="expectations" role="progressbar" style="width: 0%;" aria-valuenow="81" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-md-2 col-2 text-align-left" id="expectations_percentage">0%</div>
                                        </div>
                                        <div class="kt-space-10"></div>
                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 col-8 text-align-right">Is the instructor knowledgeable about the topic?</div>
                                            <div class="col-4" style="margin:auto 0 auto 0;">
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar kt-bg-info" id="knowledgeable" role="progressbar" style="width: 0%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-md-2 col-2 text-align-left" id="knowledgeable_percentage">0%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- begin:Reviews by Users -->
                            <div id="loading" style="display:none;">
                                <div class="kt-portlet">
                                    <div class="kt-portlet__body" style="align-items:center;">
                                        <div class="kt-spinner kt-spinner--v2 kt-spinner--lg kt-spinner--info bigger-spin"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="review_list" style="display:none;">
                                <div class="row">
                                    <div class="kt-portlet kt-portlet--bordered">
                                        <div class="kt-portlet__body">
                                            <div class="kt-widget31">
                                                <div class="kt-widget31__item">
                                                    <div class="kt-widget31__content">
                                                        <div class="kt-widget31__pic">
                                                            <img src="{{ asset('media/users/100_4.jpg') }}" alt="">
                                                        </div>
                                                        <div class="kt-widget31__info">
                                                            <a href="#" class="kt-widget31__username">
                                                                Anna Strong
                                                            </a>
                                                            <p class="kt-widget31__text">
                                                                Yesterday
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="kt-widget31__content">
                                                        <span class="rating" style="margin:auto 0 auto auto"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="kt-space-20"></div>
                                            <p>Various educators teach rules governing the length of paragraphs. They may say that a paragraph should be 100 to 200 words long, or be no more than five or six sentences. But a good paragraph should not be measured in characters, words, or sentences. The true measure of your paragraphs should be ideas.<p>
                                                <button class="btn btn-label-success btn-sm">Respond</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="kt-portlet kt-portlet--bordered">
                                            <div class="kt-portlet__body">
                                                <div class="kt-widget31">
                                                    <div class="kt-widget31__item">
                                                        <div class="kt-widget31__content">
                                                            <div class="kt-widget31__pic">
                                                                <img src="{{ asset('media/users/100_5.jpg') }}" alt="">
                                                            </div>
                                                            <div class="kt-widget31__info">
                                                                <a href="#" class="kt-widget31__username">
                                                                    William Spansih
                                                                </a>
                                                                <p class="kt-widget31__text">
                                                                    Yesterday
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="kt-widget31__content">
                                                            <span class="rating" style="margin:auto 0 auto auto"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="kt-space-20"></div>
                                                <p>Various educators teach rules governing the length of paragraphs. They may say that a paragraph should be 100 to 200 words long, or be no more than five or six sentences. But a good paragraph should not be measured in characters, words, or sentences. The true measure of your paragraphs should be ideas.<p>
                                                    <button class="btn btn-label-success btn-sm">Respond</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="kt-portlet kt-portlet--bordered">
                                                <div class="kt-portlet__body">
                                                    <div class="kt-widget31">
                                                        <div class="kt-widget31__item">
                                                            <div class="kt-widget31__content">
                                                                <div class="kt-widget31__pic">
                                                                    <img src="{{ asset('media/users/100_1.jpg') }}" alt="">
                                                                </div>
                                                                <div class="kt-widget31__info">
                                                                    <a href="#" class="kt-widget31__username">
                                                                        Bearded Anon
                                                                    </a>
                                                                    <p class="kt-widget31__text">
                                                                        A week ago
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="kt-widget31__content">
                                                                <span class="rating" style="margin:auto 0 auto auto"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="kt-space-20"></div>
                                                    <p>Various educators teach rules governing the length of paragraphs. They may say that a paragraph should be 100 to 200 words long, or be no more than five or six sentences. But a good paragraph should not be measured in characters, words, or sentences. The true measure of your paragraphs should be ideas.<p>
                                                        <button class="btn btn-label-success btn-sm">Respond</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <!-- end:Reviews by Users -->
                                        <div class="row">
                                            <button class="btn btn-label-info btn-sm" id="see_more" style="margin:auto;">See More Reviews</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!--end::Portlet-->
                    </div>
                </div>
            </div>
            @endsection
            
            
            @section("scripts")
            <script>
                var counter = `<?= isset($counter) ? $counter + 5 : 5 ?>`;
                var filter = `<?= isset($filter) ? $filter : null?>`;
                function renderReviewRow(response){
                    $("#course_title").html(response.course_details.title);
                    $("#course_pic").html(`<img class="kt-widget7__img" src="`+(response.course_details.course_poster ? response.course_details.course_poster : `{{ asset('media/products/product27.jpg') }}`)+`" alt="">`);
                    var instructors = "";
                    
                    if(response.instructors != null){
                        var instructor_length = response.instructors.length;
                        response.instructors.forEach((instructor, index) => {
                            if(index == 0){
                                instructors = instructor.fullname;
                            }else if(index+1 == instructor_length ){
                                instructors = instructors + " and " + instructor.fullname;
                            } else{
                                instructors = instructors + " ," + instructor.fullname;
                            }
                        });
                    }else{
                        instructors = "No Instructor/s found.";
                    }
                    
                    $("#course_instructors").html(instructors);
                    var group = $("#review_list").empty();
                    response.data.forEach((review, index) => {
                        row = `<div class="row">
                            <div class="kt-portlet kt-portlet--bordered">
                                <div class="kt-portlet__body">
                                    <div class="kt-widget31">
                                        <div class="kt-widget31__item">
                                            <div class="kt-widget31__content">
                                                <div class="kt-widget31__pic">
                                                    <img src="`+(review.image ? review.image : `{{ asset('img/sample/noimage.png') }}`)+`" alt="">
                                                </div>
                                                <div class="kt-widget31__info">
                                                    <a href="#" class="kt-widget31__username">
                                                        ${review.customer}
                                                    </a>
                                                    <p class="kt-widget31__text">
                                                        ${review.feedback_date}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="kt-widget31__content">
                                                <span class="rating" style="margin:auto 0 auto auto"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-space-20"></div>
                                    <p>${review.feedback ? review.feedback : ""}<p>
                                        
                                    </div>
                                </div>
                            </div>`;
                            $("#review_list").append(row);
                        });
                        if(response.data.length == 0){
                            row = `<div class="kt-portlet" style="text-align:center;padding-left:200px;">
                                <div class="kt-portlet__head" style="text-align:center;">
                                    <div class="kt-portlet__head-label" style="text-align:center;">
                                        <span class="kt-portlet__head-icon">
                                            <i class="flaticon-menu-button" style="font-size:2em;"></i>
                                        </span>
                                        <h3 class="kt-portlet__head-title" style="text-align:center;">
                                            It seems like there's no review yet in this course. Try visiting again later!
                                        </h3>
                                    </div>
                                </div>
                            </div>`;
                            $("#review_list").append(row);
                        }
                    }
                </script>
                <script src="{{asset('js/review/course-review.js')}}" type="text/javascript"></script>
                
                @endsection
                