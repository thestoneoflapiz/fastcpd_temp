@extends('template.master')
@section('title', 'Help & System Settings')

@section('styles')
@endsection

@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
		<div class="col-lg-6">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Help
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <!--iframe from eats-->
                    <iframe height='500px' width='100%' frameborder='0' allowTransparency='true' scrolling='auto' src='https://creator.zohopublic.com/pylim15/eats/form-embed/Ticket_form/YHkM5sdAgyHjrSMVt27W2UeKwghEpBmtkOasM6uUY7xTjvbJ4kHTbjs5RAx4PRYFBJYSaM3fNHSYauak71PGzaHmz81G4UYKRaWK?Status=iFrame-Help&App_User_Email=<?=Auth::user()->email ?? "noreply@fastcpd.com"?>'></iframe>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-12">
                    <div class="kt-portlet kt-portlet--height-fluid">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Company Information
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="kt-widget kt-widget--user-profile-3">
                                <div class="kt-widget__top">
                                    <div class="kt-widget__media kt-hidden-">
                                        <img alt="FastCPD Company Logo" src="{{asset('img/system/icon-1.png')}}" alt="image">
                                    </div>
                                    <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                                        JM
                                    </div>
                                    <div class="kt-widget__content">
                                        <div class="kt-widget__head">
                                            <a href="javascript:;" class="kt-widget__username">
                                                {{config('app.name') ?? '[undefined]'}} 
                                                <i class="flaticon2-correct"></i>
                                                <br/>
                                                {{config('app.name') ?? '[undefined]'}}
                                            </a>
                                            @if (_role() == "Superadmin")
                                            <div class="kt-widget__action">
                                                <a href="help/logo" class="btn btn-success btn-sm btn-upper">Update Logo</a>
                                                <a href="help/edit" class="btn btn-brand btn-sm btn-upper">EDIT</a>
                                            </div>
                                            
                                            @endif
                                        </div>
                                        <div class="kt-widget__subhead">
                                            <a href="javascript:;"><i class="flaticon2-new-email"></i>{{config('app.name') ?? '[undefined]'}}</a>
                                            <a href="javascript:;"><i class="flaticon2-calendar-3"></i>{{config('app.name') ?? '[undefined]'}}</a><br/>
                                            <a href="javascript:;"><i class="flaticon2-placeholder"></i>{{config('app.name') ?? '[undefined]'}}</a>
                                        </div>
                                        <!-- <div class="kt-widget__info">
                                            <div class="kt-widget__desc">
                                                I distinguish three main text objektive could be merely to inform people.
                                                <br> A second could be persuade people.You want people to bay objective
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="kt-widget__bottom">
                                    <div class="kt-widget__item">
                                        <div class="kt-widget__icon">
                                            <i class="flaticon-rocket"></i>
                                        </div>
                                        <div class="kt-widget__details">
                                            <span class="kt-widget__title">Google <br>Play Store</span>
                                            <a href="javascript:;" target="_blank" class="kt-widget__value kt-font-brand">View</a>
                                        </div>
                                    </div>
                                    <div class="kt-widget__item">
                                        <div class="kt-widget__icon">
                                            <i class="flaticon-confetti"></i>
                                        </div>
                                        <div class="kt-widget__details">
                                            <span class="kt-widget__title">Apple Store</span>
                                            <a href="javascript:;" target="_blank" class="kt-widget__value kt-font-brand">View</a>
                                        </div>
                                    </div>
                                    <div class="kt-widget__item">
                                        <div class="kt-widget__icon">
                                            <i class="flaticon-open-box"></i>
                                        </div>
                                        <div class="kt-widget__details">
                                            <span class="kt-widget__title">Raw APK</span>
                                            <a href="javascript:;"  target="_blank" class="kt-widget__value kt-font-brand">View</a>
                                        </div>
                                    </div>
                                    <div class="kt-widget__item">
                                        <div class="kt-widget__icon">
                                            <i class="flaticon-network"></i>
                                        </div>
                                        <div class="kt-widget__details">
                                            <span class="kt-widget__title">Credits</span>
                                            <span class="kt-widget__value">5</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
