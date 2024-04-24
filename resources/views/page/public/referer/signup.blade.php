@extends('template.referral.master')

@section('title')
FastCPD Referer <?=Request::segments()[1] ?? ""?> — Invite your friends and earn 50% discount!
@endsection

@section('styles')
<style>
    .image{width:130px;height:130px;border:4px solid #fff;border-radius:3px;}
    .image_container{margin:auto;width:130px;height:130px;border-radius:5%;-webkit-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);-moz-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);}
</style>
@endsection

@section('content')
<div class="kt-container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-9 col-sm-9 col-11">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__body">
                    <div class="row justify-content-center kt-margin-b-10">
                        <h3 style="text-align:center;">
                        <small><?=strtoupper($referer->name ?? $referer->first_name)?> <small>is inviting you to join FastCPD </small></small><br/>
                        <b><?=$referral->referral_code?></b>
                        </h3>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;">
                            <button class="btn btn-facebook login_facebook" type="button" style="width:100%;">
                                <i class="flaticon-facebook-letter-logo"></i><span style="font-weight:bold;">Continue with Facebook</span>
                            </button>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;">
                            <div id="login_google"></div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;text-align:center;">
                            OR
                        </div>
                    </div>

                    <form class="kt-form" action="" id="signup">
                        <div class="form-group row">
                            <div class="col-lg-5 col-md-5 col-sm-12 form-group" style="margin-bottom:5px;">
                                <div class="kt-input-icon kt-input-icon--left">
                                    <input type="text" class="form-control" placeholder="First" autocomplete="off" name="first_name" id="first_name"/>
                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                        <span><i class="flaticon2-user"></i></span>
                                    </span>
                                </div>
                                <span class="form-text text-muted"></span>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 form-group" style="margin-bottom:5px;">
                                <input type="text" class="form-control" placeholder="Middle" autocomplete="off" name="middle_name" id="middle_name"/>
                                <span class="form-text text-muted"></span>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 form-group" style="margin-bottom:5px;">
                                <input type="text" class="form-control" placeholder="Last" autocomplete="off" name="last_name" id="last_name"/>
                                <span class="form-text text-muted"></span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;">
                                <div class="kt-input-icon kt-input-icon--left">
                                    <input type="text" class="form-control" placeholder="Email" name="upemail" autocomplete="off" id="upemail"/>
                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                        <span><i class="flaticon2-send"></i></span>
                                    </span>
                                </div>
                                <span class="form-text text-muted"></span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;">
                                <div class="kt-input-icon kt-input-icon--left">
                                    <input type="password" class="form-control" placeholder="Password" name="uppassword" id="uppassword"/>
                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                        <span><i class="flaticon2-safe"></i></span>
                                    </span>
                                </div>
                                <span class="form-text text-muted"></span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;">
                                <div class="kt-input-icon kt-input-icon--left">
                                    <input type="password" class="form-control" placeholder="Confirm Password" name="rpassword" id="rpassword"/>
                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                        <span><i class="flaticon2-shield"></i></span>
                                    </span>
                                </div>
                                <span class="form-text text-muted"></span>
                            </div>
                            <div class="col-lg-12 col-md-9 col-sm-12 form-group" style="padding-left:10px;">
                                <input type="checkbox" style="float:left;width:5%;" checked>
                                <span style="float:right;width:95%;font-size:12px;">I want to get notified for exclusive deals, sales promotions, and recommended courses.</span> 
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <select class="form-control kt-selectpicker" style="width:100%;" id="profession" name="profession">
                                    @foreach(_all_professions() as $pro)
                                    <option value="{{ $pro['id'] }}">{{ $pro['profession'] }}</option>
                                    @endforeach
                                </select>
                                <span class="form-text text-muted">Knowing your profession helps us provide you with better offerings.</span>
                            </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 form-group kt-login__actions">
                            <button id="signup_submit" class="btn btn-info btn-elevate kt-login__btn-primary" style="width:100%;">Sign Up</button>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="text-align:center;margin-bottom:-10px;margin-top:-30px;">
                            <label class="col-form-label col-lg-12 col-sm-12" style="font-size:10px;">By signing up, you agree to our <a href="">Terms of Service</a> and <a href="">Privacy Policy</a>.</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script>
$(document).ready(function(){
    $("#signup_submit").click(function() {
        signup(this);
    });
});

var showErrorMsg = function(form, type, msg) {
    var alert = $(
        '<div class="alert alert-' +
            type +
            ' alert-dismissible" role="alert">\
        <div class="alert-text">' +
            msg +
            '</div>\
        <div class="alert-close">\
            <i class="flaticon2-cross kt-icon-sm" data-dismiss="alert"></i>\
        </div>\
    </div>'
    );

    form.find(".alert").remove();
    alert.prependTo(form);
    KTUtil.animateClass(alert[0], "fadeIn animated");
    alert.find("span").html(msg);
};

function signup(e){

    var btn = $(e);
    var form = $("#signup");

    form.validate({
        rules: {
            first_name: {
                required: true
            },
            middle_name: {
                required: true
            },
            last_name: {
                required: true
            },
            upemail: {
                required: true,
                email: true
            },
            uppassword: {
                required: true
            },
            rpassword: {
                required: true,
                equalTo: "#uppassword",
            },
            profession: {
                required: true
            },
            
        }
    });

    if (!form.valid()) {
        return;
    }

    btn.addClass(
        "kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
    ).attr("disabled", true);

    form.serialize();
    form.ajaxSubmit({
        url: "/public/signup",
        method: "post", 
        data: {
            _token: "{{ csrf_token() }}",
            _rc_signed: "<?=$referral->referral_code?>",
        },
        success: function(response, status, xhr, $form) {
            if(response.status==200){
                toastr.success("Success!", "You've been registered! Instructions have been sent to your email");
                setTimeout(() => {
                    window.location="/";
                }, 1000);
            }else{
                setTimeout(function() {
                    btn.removeClass(
                        "kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
                    ).attr("disabled", false);

                    showErrorMsg(
                        form,
                        'danger',
                        response.msg,
                    );
                }, 800);

                KTUtil.scrollTop();
            }
        }
    });
}
</script>
@endsection
