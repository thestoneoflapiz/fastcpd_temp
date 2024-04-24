@extends('template.master_accreditor_live')
@section('styles')
<link href="{{asset('css/live-webinar-progress.css')}}" rel="stylesheet" type="text/css" />
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
<input type="hidden" name="attendance_in" value="1" />
<input type="hidden" name="attendance_out" value="1" />
<input type="hidden" name="webinar_id" value="{{ $webinar->id ?? 0 }}" /> 
<input type="hidden" name="preview_" value="{{ Request::segment(2) ?? 'preview' }}" />

<div class="row">
    <div class="col-xl-8 col-md-8 remove-pad-r" id="main-content">
        <div class="row">
            <div class="col-12 remove-pad-r transition-content-wrapper--row" id="transition-content-wrapper">
                <div class="row" id="content" style="display:none;"></div>
                <button style="display:none;" class="webinar-content-open-btn webinar-content-open--btn btn btn-icon btn-label-primary" onclick="webinarContentEvent(true)"><i class="fa fa-arrow-left"></i><span class="btn-label-content"></span></button>
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
                                        Webinar Content
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
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane" id="tab-webinar-content">
                                <div class="tab-div-contents">
                                    <div class="kt-portlet__body" style="padding:0px !important;">
                                        <div class="kt-scroll ps ps--active-y" data-scroll="true" data-scrollbar-shown="true" style="height:100vh; overflow: hidden;">
                                            <div class="accordion  accordion-toggle-arrow" id="tab-webinar-content-accordion"></div>                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane active" id="overview-content-tab-2">
                                <div class="tab-div-contents">
                                    <h3>About this Webinar <small class="header-title-display"><br />{{ $webinar->title ?? 'Undefined' }}</small></h3>
                                    <p>{{ $webinar->headline ?? '' }}</p>
                                    <div class="row">
                                        <div class="col-xl-2 col-md-3 col-4"><i class="fa fa-list"></i> &nbsp;{{ $total['quizzes'] }} {{ $total['quizzes'] > 1 ? "Quizzes" : "Quiz" }}</div>
                                        <div class="col-xl-2 col-md-3 col-4"><i class="fa fa-file"></i> &nbsp;{{ $total['handouts'] }} {{ $total['handouts'] > 1 ? "Handouts" : "Handout"}}</div>
                                    </div>
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
                                            <button onclick="toastr.info('This is an Accreditor preview!')" class="btn btn-sm btn-label-info btn-upper"><b>View Webinars</b></button>    
                                        </div>
                                    </div>
                                    <br/>
                                    @if($webinar->instructor_id!=null)
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
                                                            <button type="button" class="btn btn-label-info btn-lg btn-upper" onclick="toastr.info('This is an Accreditor preview!')">view webinars</button>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-4 remove-pad-r" id="sidemenu-webinar-content" style="position:fixed;right:0;">
        <div class="kt-portlet kt-portlet--bordered" style="margin-bottom:0px !important;">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Webinar Content
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-actions">
                        <a class="btn btn-secondary btn-sm btn-icon btn-icon-md" onclick="webinarContentEvent(false)">
                            <i class="flaticon2-cross"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body" style="padding:0px !important;">
                <div class="kt-scroll ps ps--active-y" data-scroll="true" data-scrollbar-shown="true" style="height:100vh; overflow: hidden;">
                    <div class="accordion  accordion-toggle-arrow" id="sidemenu-webinar-content-accordion"></div>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script src="{{asset('js/webinar/accreditor/live/live.js')}}" type="text/javascript"></script>
<script>
    var webinar_info = {
        webinar: {
            id: "<?=$webinar->id?>",
            title: "<?=$webinar->title?>",
            event: "<?=$webinar->event?>",
            poster: "<?=$webinar->webinar_poster?>",
        },
        session: <?=json_encode($session)?>,
        attendance: {
            session_in: 1,
            session_out: 1,
        }
    };
</script>
@endsection
