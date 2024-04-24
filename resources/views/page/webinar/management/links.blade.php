@extends('template.webinar.master_creation')
@section('styles')
<style>
    .card-title{margin-bottom:0;}
</style>
@endsection

@section('content')
    @if($sessions > 0)
    <form class="kt-form kt-form--label-left" id="form" autocomplete="off">
        {{ csrf_field() }}
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Webinar Links
                    </h3> 
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="kt-portlet kt-portlet--mobile">
                    <div class="kt-portlet__body">
                        <div class="kt-form__content">
                            <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="form_msg">
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
                        <div class="row kt-margin-b-15">
                            <div class="col-12">
                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                    <input type="checkbox" <?=!$has_links ? "checked" : ""?> name="fastcpd_provide_link">Let Fast CPD management provide links for my webinar
                                    <span></span>
                                </label>
                            </div>
                            <div class="col-12">
                                <div class="card" id="group-collapse-sched">
                                    <div class="card-header" id="headingcollapse-sched">
                                        <div class="card-title" data-toggle="collapse" data-target="#collapse-sched" aria-expanded="true" aria-controls="collapse-sched">
                                            <b>Schedule Details</b>
                                        </div>
                                    </div>
                                    <div id="collapse-sched" class="collapse show" aria-labelledby="headingcollapse-sched">
                                        <div class="card-body">
                                            @if($schedule)
                                                @foreach($schedule as $sched)
                                                <div class="form-group row">	
                                                    <div class="col-xl-4 col-12">
                                                        <label class="bold"><?=array_key_exists("series", $sched) ? "<b>[Series#{$sched["series"]}]</b> " : ""?><?=date("l — M. d, Y", strtotime($sched["date_"]))?> <text class="required">*</text></label>
                                                    </div>
                                                    <div class="col-xl-8 col-12">
                                                        <input class="form-control" type="text" name="[link][<?=$sched["id"]?>]" placeholder="<?=!$has_links ? "Fast CPD webinar link will be shown after approval" : "https://mywebinarlink.com"?>" value="<?=!$has_links ? "" : $sched["link"]?>" <?=!$has_links ? "disabled" : ""?>>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="row" style="float:right">
                    <div class="col-lg-12 ml-lg-xl-auto">
                        <button id="submit_form" class="btn btn-success">Submit</button>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <button type="reset" class="btn btn-secondary">Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </form> 
    @else
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <h5><i class="fa fa-clipboard-list" style="color:#2A7DE9;"></i> &nbsp;This form is currently disabled; It is required to complete the following:</h5>
            <ul id="list-of-errors">
                <li>Webinar Details</li>
            </ul>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
<script src="{{asset('js/webinar/creation/publish/links.js')}}" type="text/javascript"></script>
@endsection