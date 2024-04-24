@extends('template.master_provider')
@section('title', _current_provider()->name)
@section('styles')
<style>
    .hidden{display:none;}
    .strong{font-weight:600;}
    .nav-link > br {display:block;content:}
    .navigation-div-label{margin:5px;}
</style>
@endsection
@section('content')
<div class="kt-container kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <span class="kt-portlet__head-icon kt-hidden">
                            <i class="la la-gear"></i>
                        </span>
                        <h3 class="kt-portlet__head-title">
                            PRC Completion Report<br>
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <span class="form-text text-muted"></span>
                                <select class="form-control kt-select2" id="prc-select-type">
                                    <option selected></option>
                                    <option value="video-on-demand">Video on Demand</option>
                                    <option value="webinar">Webinar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-12" id="month-year-row">
                            <div class="form-group">
                                <span class="form-text text-muted"></span>
                                <select class="form-control kt-select2" id="select-month-year">
                                    <option selected></option>
                                    <option value="january-2020">January 2020</option>
                                    <option value="february-2020">February 2020</option>
                                    <option value="march-2020">March 2020</option>
                                    <option value="april-2020">April 2020</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6 col-12" id="video-on-demand-row">
                            <div class="form-group">
                                <span class="form-text text-muted"></span>
                                <select class="form-control kt-select2" id="select-course">
                                    <option selected value="0">All Courses</option>
                                    @foreach (_provider_courses(_current_provider()->id) as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6 col-12" id="webinar-row">
                            <div class="form-group">
                                <span class="form-text text-muted"></span>
                                <select class="form-control kt-select2" id="select-webinar">
                                    <option selected value="0">All Courses</option>
                                    @foreach (_webinar_courses(_current_provider()->id) as $webinar)
                                    <option value="{{ $webinar->id }}">{{ $webinar->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet kt-portlet--bordered">
                        <div class="kt-portlet__body kt-portlet__body--fit">
                            <div class="kt-datatable kt-datatable--default" id="completion-datatable"></div>
                            <div class="kt-datatable kt-datatable--default" id="webinar-datatable"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script src="{{asset('js/prc/completion.js')}}" type="text/javascript"></script>
@endsection
