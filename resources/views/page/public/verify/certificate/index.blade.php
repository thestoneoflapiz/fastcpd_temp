@extends('template.master_verify_certificate')
@section('title', "Verify Certificate - Details")

@section('styles')
<style>
    .image{max-width:220px;max-height:150px;border:4px solid #fff;border-radius:3px;}
    .image_container{background-color:white;max-width:220px;max-height:150px;border-radius:5%;-webkit-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);-moz-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);}

    .small-image{width:80px;height:80px;border:4px solid #fff;border-radius:3px;}
    .small-image_container{background-color:white;width:80px;height:80px;border-radius:5%;-webkit-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);-moz-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);}
</style>
@endsection

@section('content')
<div class="kt-container">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-md-8 col-sm-10 col-12">
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <div class="row justify-content-center">
                        <h3>Enter certificate URL or code</h3>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12 form-group form-group-marginless">
                            <div class="input-group">
                                <input type="text" name="search_code" class="form-control" placeholder="<?=URL::to("/verify/codeHere")?>   or  codeHere" aria-describedby="basic-addon2">
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon2"><i class="fa fa-search"></i></span></div>
                            </div>
                            <div class="progress kt-hidden" id="progress-bar" style="height: 10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated " role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                            </div>
                            <span class="form-text text-muted">Note* Press enter to submit URL or code</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script src="{{asset('js/verify/certificate/index-page.js')}}" type="text/javascript"></script>
@endsection
