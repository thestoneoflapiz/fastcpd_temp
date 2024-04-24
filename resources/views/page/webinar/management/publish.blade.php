@extends('template.webinar.master_creation')
@section('styles')
<style>
    .card-title{margin-bottom:0;}
</style>
@endsection

@section('content')
<form class="kt-form kt-form--label-left" id="form" autocomplete="off">
    {{ csrf_field() }}
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Publish Webinar
                </h3> 
            </div>
        </div>

        @if((_current_webinar()->prc_status == "approved" && _current_webinar()->approved_at) || _current_webinar()->offering_units == "without" || _current_provider()->status != "approved")
        <div class="kt-portlet__body">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__body">
                    <span><i class="fa fa-clipboard-list" style="color:#2A7DE9;"></i> By filling out the information on this form. You are indicating that you have made all the necessary accreditation needed for this program from the appropriate accreditation bodies. </span>
                </div>
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
                    @if(_current_webinar()->offering_units!="with" || _current_provider()->status != "approved")
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="bold"><?=_current_provider()->status != "approved" ? "Promotional Price" : "Price without CPD Units"?><text class="required">*</text> <i class="fa fa-question-circle"  data-toggle="kt-popover" data-content="Price offering for students who will avail your webinar without CPD Units."></i></label>
                                <input class="form-control" type="text" id="without_price" name="without_price" value="{{$without_price}}">
                                <span class="form-text text-muted"></span>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="bold">Published Date<text class="required">*</text> <i class="fa fa-question-circle"  data-toggle="kt-popover" data-content="This is the date that the webinar will be published and viewable in public. Enrolees can enroll but will not be allowed to start the webinar."></i></label>
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker" name="published_date" placeholder="Select date" id="published_date" value="{{ _current_webinar()->published_at ? date('m/d/Y', strtotime(_current_webinar()->published_at)) : '' }}"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="form-text text-muted"></span>
                            </div>
                        </div>
                        @if(_current_webinar()->offering_units!="without" && _current_provider()->status == "approved")
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="bold">Date Approved<text class="required">*</text></label>
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker" name="date_approved" value="{{ _current_webinar()->approved_at ? date('m/d/Y', strtotime(_current_webinar()->approved_at)) : '' }}" id="date_approved" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="form-text text-muted"></span>
                            </div>
                        </div>
                        @endif
                    </div>
                    @if(_current_webinar()->profession_id && count($professions)>0)
                    <div class="form-group row">
                        <div class="col-12">
                            <label class="bold">PRC Accreditation Details<text class="required">*</text></label>
                            <span class="form-text text-muted">Please enter the webinar accreditation details for each profession applied.</span>
                        </div>
                        <div class="col-12">
                            @foreach($professions as $profession)
                            <div class="card" id="profession-group-{{ $profession->id }}">
                                <div class="card-header" id="heading{{ $profession->id }}">
                                    <div class="card-title" data-toggle="collapse" data-target="#collapse{{ $profession->id }}" aria-expanded="true" aria-controls="collapse{{ $profession->id }}">
                                    <b>{{ $profession->title }}</b> <text class="kt-font-danger kt-font-bolder">*</text>
                                    </div>
                                </div>
                                <div id="collapse{{ $profession->id }}" class="collapse show" aria-labelledby="heading{{ $profession->id }}">
                                    <div class="card-body">
                                        <div class="row">	
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label class="bold">Credit Units<text class="required">*</text></label>
                                                    <input class="form-control" type="text" name="[units][{{ $profession->id }}]" value="{{ $profession->units }}"> 
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="form-group">
                                                    <label class="bold">Accreditation No.<text class="required">*</text></label>
                                                    <input class="form-control" type="text" name="[program_no][{{ $profession->id }}]" value="{{ $profession->program_no }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="row justify-content-end">
                <p class="bold">Once submitted, no changes shall be made to the program to comply with the guidlines of online accreditation.</p>
            </div>
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
        @else
        <div class="kt-portlet__body">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__body">
                    <h5><i class="fa fa-clipboard-list" style="color:#2A7DE9;"></i> &nbsp;This form will be available once the assigned accreditor has approved your PRC Accreditation.</h5>
                </div>
            </div>
        </div>
        @endif
    </div>
</form>
@endsection

@section('scripts')
<script>
    var $professions = <?=json_encode($professions) ?? []?>;
</script>
<script src="{{asset('js/webinar/creation/publish/publish.js')}}" type="text/javascript"></script>
@endsection