<?php 
    $img_handout = [
        "pdf" => "https://www.fastcpd.com/img/pdf.png",
        "xls" => "https://www.fastcpd.com/img/excel.png",
        "csv" => "https://www.fastcpd.com/img/excel.png",
        "zip" => "https://www.fastcpd.com/img/folder.png",
        "other" => "https://www.fastcpd.com/img/document.png"
    ];
?>
@extends('template.webinar.master_performance')
@section('styles')
<link href="{{asset('css/webinar/performance.css')}}" rel="stylesheet" type="text/css" />
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
<div class="row">
    <div class="col-xl-8 col-lg-8 col-md-12 remove-pad-r" id="main-content">
        <div class="row">
            <div class="col-12 remove-pad-r transition-content-wrapper--row" id="transition-content-wrapper">
                <div class="row loading-div" style="display:none;"><div style="margin:auto;" class="kt-spinner kt-spinner--lg kt-spinner--dark"></div></div>
                <div class="row" id="content" style="display:none;">
                    <div class="col-xl-6 col-md-6"> 
                        <div class="kt-portlet">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title attendees-total">Attendees</h3>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="kt-scroll ps ps--active-y" data-scroll="true" data-scrollbar-shown="true" data-height="150">
                                    <div class="row attendance-for-in">IN</div>
                                </div>
                                    <div class="kt-separator kt-separator--space-lg"></div>
                                <div class="kt-scroll ps ps--active-y" data-scroll="true" data-scrollbar-shown="true" data-height="150">
                                    <div class="row attendance-for-out">OUT</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6"> 
                        <div class="kt-portlet">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title registered-total">Registered</h3>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="kt-scroll ps ps--active-y" data-scroll="true" data-scrollbar-shown="true" data-height="300">
                                    <div class="row registered-users">Registered</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button style="display:none;" class="webinar-content-open-btn webinar-content-open--btn btn btn-icon btn-label-primary" onclick="contentEvent(true)"><i class="fa fa-arrow-left"></i><span class="btn-label-content"></span></button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 remove-pad-r">
                <div class="kt-portlet kt-portlet--tabs">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-toolbar" style="padding:15px;">
                            <ul class="nav nav-tabs nav-tabs-bold nav-tabs-line nav-tabs-line-right nav-tabs-line-brand" role="tablist">
                                <li class="nav-item hidden-webinar-content" style="display:none;">
                                    <a class="nav-link" data-toggle="tab" href="#tab-webinar-content" role="tab">
                                    Live Webinar Timeline & Updates
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
                                    <a class="nav-link" data-toggle="tab" href="#manual-attendance-content-tab-4" role="tab">
                                        Manual Attendance
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane" id="tab-webinar-content">
                                <div class="tab-div-contents">
                                    <div class="row loading-div" style="display:none;"><div style="margin:auto;" class="kt-spinner kt-spinner--lg kt-spinner--dark"></div></div>
                                    <div class="kt-portlet__body" style="padding:0px !important;">
                                        <div class="kt-scroll ps ps--active-y" data-scroll="true" data-scrollbar-shown="true" style="height:100vh; overflow: hidden;">
                                            <div class="row justify-content-center">
                                                <div class="col-11" id="tab-timeline-content"></div>
                                            </div>                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane active" id="overview-content-tab-2">
                                <div class="tab-div-contents">
                                    <h3>About this Webinar <small class="header-title-display"><br />{{ $webinar->title ?? 'Undefined' }}</small></h3>
                                    <p>{{ $webinar->headline ?? '' }}</p>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-xl-2 col-md-3 col-4"><img class="icon-image" src="{{ $webinar->provider->logo ?? asset('img/sample/noimage.png') }}" /></div>
                                        <div class="col-xl-8 col-md-8 col-8">
                                            <b>{{ $webinar->provider->name ?? 'Undefined' }}</b>
                                            <p>{{ $webinar->provider->headline ?? '' }}</p>
                                            @if( $webinar->provider->website )
                                            <a href="{{ $webinar->provider->website }}" target="_blank" class="btn btn-icon btn-circle btn-label-google">
                                                <i class="flaticon2-world"></i>
                                            </a>
                                            @endif
                                            
                                            @if( $webinar->provider->facebook )
                                            <a href="{{ $webinar->provider->facebook }}" target="_blank" class="btn btn-icon btn-circle btn-label-facebook">
                                                <i class="socicon-facebook"></i>
                                            </a>
                                            @endif
                                            
                                            @if( $webinar->provider->linkedin )
                                            <a href="{{ $webinar->provider->linkedin }}" target="_blank" class="btn btn-icon btn-circle btn-label-twitter">
                                                <i class="socicon-linkedin"></i>
                                            </a>
                                            @endif
                                            <div class="kt-space-20"></div>
                                            @if($webinar->provider->about)
                                            <?=htmlspecialchars_decode($webinar->provider->about)?>
                                            @endif
                                            <div class="kt-space-20"></div>
                                            <button onclick="window.open('/provider/{{ $webinar->provider->url ?? `notfound` }}#our_webinars')" class="btn btn-sm btn-label-info btn-upper"><b>View Webinars</b></button>    
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <p><?= $webinar->description ? html_entity_decode($webinar->description) : ""  ?></p>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-xl-2 col-md-3 col-4">
                                            <strong>Objectives</strong>
                                        </div>
                                        <div class="col-8">
                                            <div>
                                                <ul>
                                                    @foreach(json_decode($webinar->objectives) as $obj)
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
                                                    @foreach(json_decode($webinar->requirements) as $req)
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
                                                    @foreach(json_decode($webinar->target_students) as $target)
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
                                            <img src="<?=$img_handout["{$extension}"] ?>" height="80" onclick="window.open(`{{ $hnd->url }}`)" />
                                            @else
                                            <img src="{{ $img_handout['other'] }}" height="80" onclick="window.open(`{{ $hnd->url }}`)" />
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="tab-pane" id="manual-attendance-content-tab-4">
                                <div class="tab-div-contents">
                                    <div class="row default-attendance-content">
                                        <h3>No Selected date</h3>                                        
                                    </div>
                                    <div class="row justify-content-center form-attendance-content kt-hidden">
                                        <div class="col-xl-10 col-md-10 col-sm-12">
                                            <form id="manual-attendance-form" class="kt-form">
                                                <div class="form-group row">
                                                    <div class="col-xl-6 col-md-6 col-12">
                                                        <label>Select registered users</label>
                                                        <select class="form-control kt-select" name="select_registered_user">
                                                            <option></option>
                                                        </select>
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                        <label>Attendance for</label>
                                                        <div class="kt-radio-inline">
                                                            <label class="kt-radio kt-radio--bold kt-radio--success">
                                                                <input type="radio" name="attendance_for" value="in"> IN
                                                                <span></span>
                                                            </label>
                                                            <label class="kt-radio kt-radio--bold kt-radio--danger">
                                                                <input type="radio" name="attendance_for" value="out"> OUT
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                        <span class="form-text text-muted">If you choose <b>OUT</b>, make sure the user has already attended <b>IN</b> and completed <b>quizzes</b> and <b>articles</b>.</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-12">Time</label>
                                                    <div class="col-xl-4 col-md-4 col-sm-5 col-6">
                                                        <input class="form-control" name="attendance_time" readonly="" placeholder="Select time" type="text">
                                                    </div>
                                                    <span class="form-text text-muted"></span>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-3 col-md-3 col-sm-4 col-5">
                                                        <button type="submit" name="submit_attendance_btn" class="btn btn-success">Save</button>
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
    </div>
    <div class="col-xl-4 col-lg-4 remove-pad-r" id="sidemenu-webinar-content" style="position:fixed;right:0;">
        <div class="kt-portlet kt-portlet--bordered" style="margin-bottom:0px !important;">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Live Webinar Timeline & Updates
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-actions">
                        <a class="btn btn-secondary btn-sm btn-icon btn-icon-md" onclick="contentEvent(false)">
                            <i class="flaticon2-cross"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body" style="padding:0px !important;">
                <div class="kt-scroll ps ps--active-y" data-scroll="true" data-scrollbar-shown="true" style="height:100vh; overflow: hidden;">
                    <div class="row loading-div" style="display:none;margin-top:10%;"><div style="margin:auto;" class="kt-spinner kt-spinner--lg kt-spinner--dark"></div></div>
                    <div class="row justify-content-center">
                        <div class="col-11" id="sidemenu-timeline-content"></div>
                    </div>           
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="selection_date_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Date Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row form-group justify-content-center">
                    <div class="col-11">
                        <select class="form-control kt-select2" id="selected_date" placeholder="Please select a date">
                            <option></option>
                            @foreach($schedule as $sched)
                            <option value="<?=$sched->session_date?>"><?=date("M. d Y", strtotime($sched->session_date))?></option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script src="{{asset('js/webinar/performance/page.js')}}" type="text/javascript"></script>
<script>
</script>
@endsection
