@extends('template.referral.master')

@section('title')
FastCPD Referer <?=Request::segments()[1] ?? ""?> — Join us and invite your friends to earn up to 50% discount!!
@endsection

@section('styles')
<style>
    .image{width:130px;height:130px;border:4px solid #fff;border-radius:3px;}
    .image_container{margin:auto;width:130px;height:130px;border-radius:5%;-webkit-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);-moz-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);}
</style>
@endsection

@section('metas')
<meta property="og:title" content="FastCPD Referral Code <?=Request::segments()[1] ?? ""?> — Join us and invite your friends to earn up to 50% discount!" />
<meta property="og:url" content="<?=URL::to("/referer/{$referral->referral_code}")?>" />
<meta property="og:type" content="fastcpd_com">
<meta property="og:image" content="https://fastcpd.com/img/sample/poster-sample.png" />
<meta property="og:site_name" content="FastCPD">
<meta name="description" content="In FastCPD get up to 50% discount by inviting your friends! Share your own unique referral code to your friends. Earn rewards for every sign up your friends make!">
<meta property="og:description" content="In FastCPD get up to 50% discount by inviting your friends! Share your own unique referral code to your friends. Earn rewards for every sign up your friends make!" />

@if(config("app.env") == "production")
<script type="application/ld+json">
    [{
      "@context": "https://schema.org",
      "@type": "Organization",
      "url": "https://www.fastcpd.com",
      "logo": "https://www.fastcpd.com/img/system/logo-1.png",
      "image": [
            "https://www.fastcpd.com/img/system/icon-1.png",
          	"https://www.fastcpd.com/img/system/logo-2.png",
          	"https://www.fastcpd.com/img/system/logo-1.png"
        ],
      "slogan":"The Best and Convenient Way to Earn CPD Units",
      "description":"In FastCPD get up to 50% discount by inviting your friends! Share your own unique referral code to your friends. Earn rewards for every sign up your friends make!",
      "email":"info@fastcpd.com",
      "address" : {
        "@type": "PostalAddress",
        "streetAddress": "30 Cabatuan Street",
        "addressLocality": "Quezon City",
        "addressRegion": "NCR",
        "postalCode": "1100",
        "addressCountry": "PH"
      },
      "knowsAbout":"Online CPD Courses, Referral Code Program",
      "makesOffer" :"Online CPD Courses, Referral Code Program",
      "telephone" : ["+63-2-332-6977","+63917-817-7388"],
      "sameAs": [
        "https://www.facebook.com/fastcpd",
        "https://www.instagram.com/fastcpd/",
        "https://www.fastcpd.com"
      ]
      },
      {
      "@context": "https://schema.org",
      "@type": "Brand",
      "name":"FastCPD",
      "url": "https://www.fastcpd.com",
      "logo": "https://www.fastcpd.com/img/system/logo-1.png",
      "image":"https://www.fastcpd.com/img/system/logo-1.png",
      "slogan":"The Best and Convenient Way to Earn CPD Units",
      "description":"In FastCPD get up to 50% discount by inviting your friends! Share your own unique referral code to your friends. Earn rewards for every sign up your friends make!",
      "sameAs": [
        "https://www.facebook.com/fastcpd",
        "https://www.instagram.com/fastcpd",
        "https://www.youtube.com/channel/UC-3v3AGXbogd7CmSyJQ6-jw",
        "https://www.fastcpd.com"]
      },
     {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "About FastCPD",
        "item": "https://www.fastcpd.com/site/about-fastcpd"
      }]
    }]
</script>
@endif

@endsection

@section('content')
<div class="kt-container">
    <div class="row justify-content-center">
        <div class="col-xl-2 col-lg-2 col-md-5 col-sm-3 col-4 kt-margin-b-20">
            <div class="image_container">
                <img alt="FastCPD User Image <?=$referer->name?> <?=$referer->username?>" src="{{ $referer->image ?? asset('img/sample/noimage.png') }}" class="image"/>
            </div>
        </div>
        <div class="col-xl-9 col-lg-9 col-md-10 col-sm-8 col-11">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                        <?=strtoupper($referer->name ?? $referer->first_name)?> <small>is inviting you to join FastCPD</small>
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row justify-content-center">
                        <button class="btn btn-info" id="join">Join FastCPD</button>
                    </div>
                    <div class="row justify-content-center kt-margin-t-10">
                        OR
                    </div>
                    <div class="row justify-content-center kt-margin-t-10">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?=URL::to("/referer/{$referral->referral_code}?action=rcs")?>?size=200x200" />
                    </div>  
                    <div class="row justify-content-center">
                        <span class="form-text text-muted">Scan this QR to proceed</span>
                    </div>  
                    <div class="kt-separator kt-separator--space-lg kt-separator--border-2x kt-separator--border-dashed"></div>
                    <div class="row justify-content-center kt-margin-b-10">
                        <span class="form-text text-muted" style="text-align:center;">Already signed up?<br/>Just login and copy this code: <h3><b><?=$referral->referral_code?></b></h3>
                        Go to your settings, click <b>"Referral Code"</b>.<br/>Enter and submit!</span>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script>
$(document).ready(function(){
    $("#join").click(function(){
        window.location="<?=URL::to("/referer/{$referral->referral_code}?action=rcs")?>";
    });
});
</script>
<script src="{{asset('js/referral/referer/page.js')}}" type="text/javascript"></script>
@endsection
