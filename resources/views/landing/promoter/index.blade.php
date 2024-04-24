@extends('template.auth')
@section('content')
<div class="kt-login__signin">
    <div class="kt-login__head">
        <h3 class="kt-login__title">
            Promoter Log In<br/><br/>
        </h3>
    </div>
    <form class="kt-form" id="signin" method="GET" action="/auth/promoter/login">
        <div class="modal-body">
            <div class="form-group row">
                <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;" style="padding-bottom:10px;">
                    <div class="kt-input-icon kt-input-icon--left">
                        <input type="text" class="form-control" placeholder="Email" autocomplete="off" name="email" id="email"/>
                        
                    </div>
                    <span class="form-text text-muted"></span>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;" style="padding-bottom:10px;">
                    <div class="kt-input-icon kt-input-icon--left">
                        <input type="password" class="form-control" placeholder="Password" name="password" id="password"/>
                        
                    </div>
                    <span class="form-text text-muted"></span>
                </div>
                <!-- <div class="col-lg-12 col-md-9 col-sm-12" style="padding-bottom:10px;padding-left:10px;">
                    <label class="kt-checkbox">
                        <input type="checkbox" name="remember"> Keep me logged in
                        <span></span>
                    </label>
                </div> -->
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button id="signin_submit" class="btn btn-info btn-elevate kt-login__btn-primary" style="width:100%;font-weight:bold;color:white;">Log In</button>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:center;">
            or <a href="javascript:;" id="kt_login_forgot" class="kt-login__link" data-toggle="modal" data-target="#forgot_password_modal"> Forgot Password ?</a>
            </div>
        </div>
        <div class="modal-footer" style="text-align:center;">
            <label class="col-form-label col-lg-12 col-sm-12">Don't have an account? <a href="#" id="signup_link" data-toggle="modal" data-target="#signup_modal">Sign Up</a></label>
        </div>
    </form>
</div>
</div>
@endsection

@section('scripts')
<script>

$("#signin_submit").click(function(e) { 
    e.preventDefault();
    var btn = $(this);
    var form = $("#signin");

    form.validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            }
        }
    });

    if (!form.valid()) {
        return;
    }

    btn.addClass(
        "kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
    ).attr("disabled", true);

    form.ajaxSubmit({
        url: "/auth/promoter/login",
        success: function(response, status, xhr, $form) {
            if(response.status=='401'){
                btn.removeClass(
                    "kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
                ).attr("disabled", false);

                showErrorMsg(
                    form,
                    "danger",
                    response.msg,
                );
        
                KTUtil.scrollTop();
            }else{
                window.location.href="/dashboard";
            }
        }
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
    //alert.animateClass('fadeIn animated');
    KTUtil.animateClass(alert[0], "fadeIn animated");
    alert.find("span").html(msg);
};

</script>
@endsection