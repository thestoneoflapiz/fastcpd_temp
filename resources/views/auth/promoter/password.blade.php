@extends('template.auth')
@section('content')
<div class="kt-login__signin">
    <div class="kt-login__head">
        <h3 class="kt-login__title">
            Promoter<br/><br/>
            <small>Create Password</small><br/><br/>
            
            <small>Email: <b>{{$email ?? ''}}</b> </small>
        </h3>
    </div>
    <form class="kt-form" method="GET" action="">
        <div class="input-group">
            <input class="form-control" type="password" placeholder="New Password" name="new" id="new">
        </div>
        <div class="input-group">
            <input class="form-control" type="password" placeholder="Confirm Password" name="confirm" id="confirm">
        </div>
        <input class="form-control" type="hidden" value={{$id}} name="id" id="id">

        <div class="kt-login__actions">
            <button id="signin_submit" class="btn btn-success btn-elevate kt-login__btn-primary">Confirm</button>
        </div>
    </form>   
</div>
@endsection

@section('scripts')
<script>
    $("#signin_submit").click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest("form");

        form.validate({
            rules: {
                new: {
                    minlength: 6,
                    required: true,
                },
                confirm: {
                    minlength: 6,
                    required: true,
                    equalTo: "#new",
                }
            },
            messages:{
                confirm:{
                    equalTo: "Password do not match!"
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
            method: "POST",
            url: "/auth/promoter/verify/password",
            data: {
                id: $("#id").val(),
                password: $("#new").val(),
                _token: "{{ csrf_token() }}",
            },
            success: function(response, status, xhr, $form) {
                
                if(response.status == 200){
                    window.location="/";
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
                    }, 600);
                }
            }
        });
    });

    var showErrorMsg = function(form, type, msg, link) {
        var alert = $(
        '<div class="alert alert-' +
                type +
                ' alert-dismissible" role="alert">\
            <div class="alert-text">' +
                msg +'\
            </div>\
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