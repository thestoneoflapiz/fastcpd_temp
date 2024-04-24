@extends('template.master_checkout')
@section('content')
<div class="kt-container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-8 col-md-10">
            <div class="kt-portlet">
                <form class="kt-form" id="form">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Complete Details <small>Please complete the details below to continue</small>
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="form-group row kt-margin-t-20">
                            <div class="col-lg-5 col-md-4 col-sm-4 col-8 form-group" style="margin-bottom:5px;">
                                <div class="kt-input-icon kt-input-icon--left">
                                    <input type="text" class="form-control" placeholder="First" autocomplete="off" name="register_first_name" id="register_first_name"  value="<?=Auth::user()->first_name?>"/>
                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                        <span><i class="flaticon2-user"></i></span>
                                    </span>
                                </div>
                                <span class="form-text text-muted"></span>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-4 form-group" style="margin-bottom:5px;">
                                <input type="text" class="form-control" placeholder="Middle" autocomplete="off" name="register_middle_name" id="register_middle_name"/>
                                <span class="form-text text-muted"></span>
                            </div>
                            <div class="col-lg-4 col-md-5 col-sm-5 col-12 form-group" style="margin-bottom:5px;">
                                <input type="text" class="form-control" placeholder="Last" autocomplete="off" name="register_last_name" id="register_last_name" value="<?=Auth::user()->last_name?>"/>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;">
                                <div class="kt-input-icon kt-input-icon--left">
                                    <input type="text" class="form-control" placeholder="Email" <?=Auth::check() ? "disabled" :""?> value="<?=Auth::check() ? Auth::user()->email :""?>" name="register_email" autocomplete="off" id="register_email"/>
                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                        <span><i class="flaticon2-send"></i></span>
                                    </span>
                                </div>
                                <span class="form-text text-muted"></span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;">
                                <div class="kt-input-icon kt-input-icon--left">
                                    <input type="password" class="form-control" placeholder="Password" name="register_password" id="register_password"/>
                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                        <span><i class="flaticon2-safe"></i></span>
                                    </span>
                                </div>
                                <span class="form-text text-muted"></span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;">
                                <div class="kt-input-icon kt-input-icon--left">
                                    <input type="password" class="form-control" placeholder="Confirm Password" name="register_confirm_password" id="register_confirm_password"/>
                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                        <span><i class="flaticon2-shield"></i></span>
                                    </span>
                                </div>
                                <span class="form-text text-muted"></span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <select class="form-control kt-select2" style="width:100%;" id="register_profession" name="register_profession">
                                    <option></option>
                                    @foreach(_all_professions() as $pro)
                                    <option value="{{ $pro['id'] }}">{{ $pro['profession'] }}</option>
                                    @endforeach
                                </select>
                                <span class="form-text text-muted">Knowing your profession helps us provide you with better offerings.</span>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-2 col-md-2 col-3">
                                <button type="submit" class="btn btn-success" id="form_submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $(`[name="register_profession"]`).select2({
            placeholder: "Select Profession",
        });
    });
    
    $("#form_submit").click(function(e) {
        e.preventDefault();

        var btn = $(this);
        var form = $(this).closest("form");
    
        form.validate({
            rules: {
                register_first_name: {
                    required: true
                },
                register_last_name: {
                    required: true,
                },
                register_password: {
                    required: true
                },
                register_confirm_password: {
                    required: true,
                    equalTo: "#register_password",
                },
                register_profession: {
                    required: true
                },
            }
        });

        if (!form.valid()) {
            return;
        }

        btn.addClass("kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light").attr("disabled", true);
        form.ajaxSubmit({
            url: "/auth/social/register/action",
            method: "post",
            data: {
                _token: "{{ csrf_token() }}",
            },
            success: function(response) {
                window.location="/";
            },
            error: function(response){
                var body = response.responseJSON;
                if(body.hasOwnProperty("message")){
                    toastr.error(body.message);
                    toastr.info("Page redirecting...");

                    setTimeout(() => {
                        window.location="/";
                    }, 2500);
                    return;
                }

                toastr.error("Something went wrong, please refresh your browser!");
                btn.removeClass("kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light").attr("disabled", false);
            }
        });
    });
</script>
@endsection