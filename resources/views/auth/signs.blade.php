@extends('template.auth')
@section('content')
<div class="kt-login__signin">
    <div class="kt-login__head">
        <h3 class="kt-login__title">Sign In</h3>
    </div>
    <form class="kt-form" method="GET" action="/signin">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Email" name="email" autocomplete="off">
        </div>
        <div class="input-group">
            <input class="form-control" type="password" placeholder="Password" name="password">
        </div>
        <div class="row kt-login__extra">
            <div class="col">
                <label class="kt-checkbox">
                    <input type="checkbox" name="remember"> Keep me logged in
                    <span></span>
                </label>
            </div>
            <div class="col kt-align-right">
                <a href="javascript:;" id="kt_login_forgot" class="kt-login__link">Forget Password ?</a>
            </div>
        </div>
        <div class="kt-login__actions">
            <button id="signin_submit" class="btn btn-info btn-elevate kt-login__btn-primary">Sign In</button>
        </div>
    </form>
</div>
<div class="kt-login__signup">
    <div class="kt-login__head">
        <h3 class="kt-login__title">Sign Up</h3>
        <div class="kt-login__desc">Enter your details to create your account:</div>
    </div>
    <form class="kt-form" action="">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="First name" name="first" autocomplete="off" id="first">
        </div>
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Last name" name="last" autocomplete="off" id="last">
        </div>
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Email" name="upemail" autocomplete="off" id="upemail">
        </div>
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Contact No." name="contact" autocomplete="off" id="contact">
        </div>
        <div class="input-group">
            <input class="form-control" type="password" placeholder="Password" name="uppassword" id="uppassword">
        </div>
        <div class="input-group">
            <input class="form-control" type="password" placeholder="Confirm Password" name="rpassword" id="rpassword">
        </div>
        <!-- <div class="row kt-login__extra">
            <div class="col kt-align-left">
                <label class="kt-checkbox">
                    <input type="checkbox" name="agree">I Agree the <a href="#" class="kt-link kt-login__link kt-font-bold">terms and conditions</a>.
                    <span></span>
                </label>
                <span class="form-text text-muted"></span>
            </div>
        </div> -->
        <div class="kt-login__actions">
            <button id="signup_submit" class="btn btn-info btn-elevate kt-login__btn-primary">Sign Up</button>&nbsp;&nbsp;
            <button id="kt_login_signup_cancel" class="btn btn-light btn-elevate kt-login__btn-secondary">Cancel</button>
        </div>
    </form>
</div>
<div class="kt-login__forgot">
    <div class="kt-login__head">
        <h3 class="kt-login__title">Forgotten Password ?</h3>
        <div class="kt-login__desc">Enter your email to reset your password:</div>
    </div>
    <form class="kt-form" action="">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Email" name="email" id="kt_email" autocomplete="off">
        </div>
        <div class="kt-login__actions">
            <button id="forgot_submit" class="btn btn-info btn-elevate kt-login__btn-primary">Request</button>&nbsp;&nbsp;
            <button id="kt_login_forgot_cancel" class="btn btn-light btn-elevate kt-login__btn-secondary">Cancel</button>
        </div>
    </form>
</div>
<div class="kt-login__account">
    <span class="kt-login__account-msg">
        Don't have an account yet ?
    </span>
    &nbsp;&nbsp;
    <a href="javascript:;" id="kt_login_signup" class="kt-login__account-link">Sign Up!</a>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        input_masks();
    });
    
    var validator;

    $("#signin_submit").click(function(e) { 
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest("form");

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
            url: "/signin",
            success: function(response, status, xhr, $form) {
                if(response.status=='401'){
                    setTimeout(function() {
                        btn.removeClass(
                            "kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
                        ).attr("disabled", false);

                        showErrorMsg(
                            form,
                            "danger",
                            response.msg,
                        );
                    }, 800);
                
                KTUtil.scrollTop();
                }else{
                    window.location="/";
                }
            }
        });
    });

    $("#signup_submit").click(function(e) {
        e.preventDefault();

        var btn = $(this);
        var form = $(this).closest("form");
    
        form.validate({
            rules: {
                first: {
                    required: true
                },
                middle: {
                    required: true
                },
                last: {
                    required: true
                },
                upemail: {
                    required: true,
                    email: true
                },
                contact: {
                    required: true,
                },
                uppassword: {
                    required: true
                },
                rpassword: {
                    required: true,
                    equalTo: "#uppassword",
                },
            }
        });

        if (!form.valid()) {
            return;
        }

        btn.addClass(
            "kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
        ).attr("disabled", true);

        form.ajaxSubmit({
            url: "/public/signup",
            method: "post",
            data: {
                _token: "{{ csrf_token() }}",
            },
            success: function(response, status, xhr, $form) {

                if(response.status==200){
                    // toastr.success("Success!", "You've been registered! Instructions have been sent to your email");
                    toastr.success("Success!", "You've been registered!");
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }else{
                    setTimeout(function() {
                        btn.removeClass(
                            "kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
                        ).attr("disabled", false);

                        showErrorMsg(
                            form,
                            'success',
                            response.msg,
                        );
                    }, 800);

                    KTUtil.scrollTop();
                }
            }
        });
    });

    $("#forgot_submit").click(function(e) {
        e.preventDefault();

        var btn = $(this);
        var form = $(this).closest("form");

        form.validate({
            rules: {
                email: {
                    required: true,
                    email: true
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
            url: "/public/reset",
            method: "post",
            data: {
                _token: "{{ csrf_token() }}",
            },
            success: function(response, status, xhr, $form) {
                if(response.status == 200){
                    toastr.success("Success!", "Request Sent! Instructions have been sent to your email");
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }else{    
                    setTimeout(function() {
                        btn.removeClass(
                            "kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
                        ).attr("disabled", false); // remove
                        form.clearForm(); // clear form
                        form.validate().resetForm(); // reset validation states

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

    var input_masks = function () {

        // phone number format
        $("#contact").inputmask("mask", {
            "mask": "+63 999-999-9999",
        }); 
    }
    
</script>
@endsection