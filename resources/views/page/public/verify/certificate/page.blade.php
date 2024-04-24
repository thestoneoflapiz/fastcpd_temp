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
    <div class="row">
        <div class="col-xl-3 col-md-3 col-12 kt-margin-b-20">
            <div class="image_container" style="margin:auto;">
                <img alt="FastCPD <?=ucwords($certificate->type)?> <?=$data->title?>" src="<?=$data->poster ?? asset('img/sample/poster-sample.png')?>" class="image"/>
            </div>
            <div class="row kt-margin-t-10">
                <div style="margin:auto;text-align:center;">
                    <h3><?=$data->title;?></h3>
                    <p><?=$data->headline;?></p>
                    <a href="/<?=$certificate->type?>/<?=$data->url;?>" target="_blank" class="btn btn-sm btn-secondary">View <?=ucwords($certificate->type)?> &nbsp; <i class="fa fa-share-square"></i></a>
                </div>
            </div>
        </div>
        <div class="col-xl-9 col-md-9 col-12">
            <div class="row">
                <div class="col-xl-12">
                    <div class="kt-portlet">
                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-8">
                                    <h3>
                                        <i class="fa fa-check-circle kt-font-success" style="font-size:2rem;"></i>&nbsp; Certificate is VERIFIED<br>
                                        <small style="color:#7e86af;font-size:1.2rem;">This digital certificate is valid and securely issued at <b><?=date("M. d, Y h:i A", strtotime($certificate->updated_at))?></b>.</small>
                                    </h3>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4">
                                    <button class="btn btn-lg btn-info" style="width:100%;" onclick="window.open('/verify/view/pdf/<?=$code?>')">VIEW CERTIFICATE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-md-6">
                    <div class="kt-portlet">
                        <div class="kt-portlet__body">
                            <div>
                                <h5><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i>&nbsp; The owner of this certificate has been verified</h5>
                            </div>
                            <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg kt-separator--portlet-fit"></div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="small-image_container">
                                        <img alt="FastCPD User Image <?=$user->name?>" src="<?=$user->image ?? asset('img/sample/noimage.png')?>" class="small-image"/>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5>
                                        <?=$user->name;?><br/>
                                        <button class="btn btn-secondary btn-sm kt-margin-t-10" onclick="window.location='/user/<?=$user->username?>'">View more details &nbsp; <i class="fa fa-share-square"></i></button>
                                    </h5>
                                </div>
                            </div>
                            @if( $credited_cpd_units && count($credited_cpd_units) > 0)
                                <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg kt-separator--portlet-fit"></div>
                                <h5><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i>&nbsp; Credited CPD Units</h5>
                                <ul>
                                @foreach($credited_cpd_units as $unit)
                                    <li>
                                        <b style="font-size:1.3rem;"><?=$unit->title?></b> — <?=$unit->program_no?> <br>
                                        CPD Units: <b style="font-size:1.3rem;"><?=$unit->units?></b>
                                    </li>
                                @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="kt-portlet">
                        <div class="kt-portlet__body">
                            <div>
                                <h5>
                                    <i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i>&nbsp; The provider has been verified
                                </h5>
                            </div>
                            <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg kt-separator--portlet-fit"></div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="small-image_container">
                                        <img alt="FastCPD Provider Logo <?=$provider->name?>" src="<?=$provider->logo ?? asset('img/sample/noimage.png')?>" class="small-image"/>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5>
                                        <?=$provider->name;?> — <?=$provider->accreditation_number?><br/>
                                        <small><?=$provider->headline;?></small><br/>
                                        <button class="btn btn-secondary btn-sm kt-margin-t-10" onclick="window.location='/provider/<?=$provider->url?>'">View more details &nbsp; <i class="fa fa-share-square"></i></button>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<!-- <script src="{{asset('js/overview/overview-page.js')}}" type="tex      t/javascript"></script> -->
@endsection
