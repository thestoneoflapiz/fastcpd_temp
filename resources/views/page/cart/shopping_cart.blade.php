@extends('template.master_purchase')
@section('styles')
<style>
    .hero-banner{
        background: #250412;  /* fallback for old browsers */
        background: -webkit-linear-gradient(to right, #250412, #ec008c);  /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to right, #250412, #ec008c); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        width:100%;height:80px;margin:0;}
    .white-color{color:white;}
    .course-poster-img{object-fit:cover;}
    .price{color:#961f45 !important;}
</style>
@endsection
@section('content')
{{ csrf_field() }}
<div class="row hero-banner">
    <h3 style="margin:auto 0 auto 30px" class="white-color">My Cart</h3>
</div>
<div class="kt-space-20"></div>
<div class="row" style="min-height:600px;">
    <div class="kt-container  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label" id="my-cart-item-count">
                            <h3 class="kt-portlet__head-title">
                                Total of "{{ $total_items }}" {{ $total_items > 1 ? "items" : "item" }}
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-widget5" id="my-cart-list">
                            @if($total_items>0)
                            @foreach($my_cart as $mca)
                            <div class="kt-widget5__item" id="my-cart-item-<?=$mca['type']?>-<?=$mca['data_id']?>">
                                <div class="kt-widget5__content">
                                    <div class="kt-widget5__pic">
                                        <img alt="FastCPD Courses <?=$mca["title"]?>" src="{{ $mca['poster'] ?? asset('img/sample/noimage.png') }}" class="course-poster-img">
                                    </div>
                                    <div class="kt-widget5__section">
                                        <a href="#" class="kt-widget5__title">{{ $mca["title"] }}</a>
                                        <p class="kt-widget5__desc row">
                                            @if($mca["accreditation"])
                                            @foreach($mca["accreditation"] as $acc)
                                            {{ $acc->title }} ({{ $acc->program_no }} &#9679; {{ $acc->units }}) <br/>
                                            @endforeach
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="kt-widget5__content">
                                    <div class="kt-widget5__stats">
                                        @if($mca["discounted_price"])
                                        <span class=" kt-font-info"><b>{{ $mca["discount"] }}%—{{ $mca["voucher"] }} Applied!</b></span>
                                        <span class="kt-widget5__number price"><b>&#8369;{{ number_format($mca["discounted_price"], 2, '.', ',') }}</b></span>
                                        <span class="kt-widget5__votes kt-font-bold" style="text-decoration:line-through;">&#8369;{{ number_format($mca["price"], 2, '.', ',') }}</span>
                                        @else
                                        <span class="kt-widget5__number price"><b>&#8369;{{ number_format($mca["price"], 2, '.', ',') }}</b></span>
                                        @endif
                                    </div>
                                    <button data-type="{{ $mca['type'] }}" data-id="{{ $mca['data_id'] }}" data-title="{{ $mca['title'] }}" onclick="openRemoveModal({ type: '<?=$mca['type']?>', data_id: <?=$mca['data_id']?>, title: '<?=$mca['title']?>'})" type="button" class="btn btn-sm btn-icon btn-outline-hover kt-label-font-color-1"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12">
                <!--begin::Portlet-->
                <div class="kt-portlet kt-portlet--mobile">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Total:
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-align-left" id="my-cart-totals-div">
                            @if($total_discounted_price > 0)
                            <h1 class="kt-font-dark kt-font-boldest">
                                &#8369;{{ number_format($total_discounted_price, 2, '.', ',') }}
                                <small class="kt-label-font-color-1" style="text-decoration:line-through;">&#8369;{{ number_format($total_price, 2, '.', ',') }}</small>
                            </h1>
                            <h5 style="color:#961f45;">{{ number_format((($total_price - $total_discounted_price) / $total_price) * 100, 2, '.', ',') }}% off</h5>
                            @else
                            <h1 class="kt-font-dark kt-font-boldest">&#8369;{{ number_format($total_price, 2, '.', ',') }}</h1>
                            @endif
                            <div class="kt-space-15"></div>
                            <div class="kt-space-15" style="border-bottom:2px dashed #ebedf2"></div>
                            <h5>CPD Units you’ll earn:</h5>
                            <div class="accordion accordion-light accordion-toggle-arrow">
                                @foreach($units as $key => $unit) 
                                <div class="card">
                                    <div class="card-header" id="heading-<?=$key?>">
                                        <div class="card-title collapsed" data-toggle="collapse" data-target="#collapse-heading-<?=$key?>" aria-expanded="false" aria-controls="collapse-heading-<?=$key?>">
                                            {{ $unit["title"] }}
                                        </div>
                                    </div>
                                    <div id="collapse-heading-<?=$key?>" class="collapse" aria-labelledby="heading-<?=$key?>">
                                        <div class="card-body">
                                            <ul>
                                                @foreach($unit["units"] as $un) 
                                                <li>{{ $un }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="kt-align-center">
                            @if(Auth::check())
                            <button id="btn-checkout" onclick="window.location='/checkout'" class="btn btn-success btn-upper kt-font-bolder btn-lg <?=$total_items == 0 ? 'disabled' : ''?>"  <?=$total_items == 0 ? 'disabled' : ''?> style="width:100%;">Checkout</button>
                            @else
                            <button id="btn-checkout" onclick="toastr.info('Please logged in first before checking out')" class="btn btn-success btn-upper kt-font-bolder btn-lg  <?=$total_items == 0 ? 'disabled' : ''?>"  <?=$total_items == 0 ? 'disabled' : ''?> style="width:100%;" data-toggle="modal" data-target="#checkout_login_modal">Checkout</button>
                            @endif
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="input-group input_coupon">
                            <input type="text" class="form-control" name="apply_voucher_code" placeholder="Enter Voucher">
                            <div class="input-group-append">
                                <button class="btn btn-info" type="button" id="apply-voucher-button" onclick="addVoucher()">Apply</button>
                            </div>
                        </div>
                        <br/>
                        <div class="kt-align-left" id="applied-voucher-list">
                            @if(count($vouchers) > 0)
                            @foreach($vouchers as $voucher)
                            <h5><i class="flaticon2-delete" onclick="removeVoucher('<?=$voucher?>')"></i>&nbsp;&nbsp; <b>{{ $voucher }}</b> is applied</h5>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <!--end::Portlet-->
            </div>
        </div>
    </div>
</div>

<!-- begin:: modals -->
<div class="modal fade" id="remove_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Remove Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this item?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary yes-button" data-dismiss="modal">Yes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="checkout_login_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-m" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Log In to Your FastCPD Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form" id="signin_my_cart" method="GET" action="/signin">
                <div class="modal-body">
                    <div class="form-group row kt-margin-t-20">
                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;">
                            <button class="btn btn-facebook login_facebook" type="button" style="width:100%;">
                                <i class="flaticon-facebook-letter-logo"></i><span style="font-weight:bold;">Continue with Facebook</span>
                            </button>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;text-align:center;">
								<div id="login_google"></div>
							</div>
                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;text-align:center;">
                            OR
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;" style="padding-bottom:10px;">
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="text" class="form-control" placeholder="Email" autocomplete="off" name="email" id="email"/>
                                <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                    <span><i class="flaticon2-send"></i></span>
                                </span>
                            </div>
                            <span class="form-text text-muted"></span>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;" style="padding-bottom:10px;">
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="password" class="form-control" placeholder="Password" name="password" id="password"/>
                                <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                    <span><i class="flaticon2-safe"></i></span>
                                </span>
                            </div>
                            <span class="form-text text-muted"></span>
                        </div>
                        <div class="col-lg-12 col-md-9 col-sm-12" style="padding-bottom:10px;padding-left:10px;">
                            <label class="kt-checkbox">
                                <input type="checkbox" name="remember"> Keep me logged in
                                <span></span>
                            </label>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <button id="signin_submit_mycart" class="btn btn-info btn-elevate kt-login__btn-primary" style="width:100%;font-weight:bold;color:white;">Log In</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                    <label class="col-form-label col-lg-12 col-sm-12">Don't have an account? <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#checkout_signup_modal">Sign Up</a></label>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="checkout_signup_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-m" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Sign up and start earning CPD units!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row kt-margin-t-10">
                    <!-- Temporary hide social links -->
                    <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;">
                        <button class="btn btn-facebook login_facebook" type="button" style="width:100%;">
                            <i class="flaticon-facebook-letter-logo"></i><span style="font-weight:bold;">Continue with Facebook</span>
                        </button>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;text-align:center;">
                        <div id="signup_google"></div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;text-align:center;">
                        OR
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                        <button type="button" class="btn btn-info" style="width:100%;" data-dismiss="modal" data-toggle="modal" data-target="#checkout_register_signup_modal">Register</button>
                    </div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                    <label class="col-form-label col-lg-12 col-sm-12">Already have an account? <a href="" id="login_link" data-dismiss="modal" data-toggle="modal" data-target="#checkout_login_modal">Log In</a></label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="checkout_register_signup_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-m" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Sign up and start earning CPD units!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form" action="" id="signup_my_cart">
                <div class="modal-body">
                    <div class="form-group row kt-margin-t-20">
                        <div class="col-lg-5 col-md-5 col-sm-12 form-group" style="margin-bottom:5px;">
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="text" class="form-control" placeholder="First" autocomplete="off" name="c_first_name" id="c_first_name"/>
                                <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                    <span><i class="flaticon2-user"></i></span>
                                </span> 
                            </div>
                            <span class="form-text text-muted"></span>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 form-group" style="margin-bottom:5px;">
                            <input type="text" class="form-control" placeholder="Middle" autocomplete="off" name="c_middle_name" id="c_middle_name"/>
                            <span class="form-text text-muted"></span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 form-group" style="margin-bottom:5px;">
                            <input type="text" class="form-control" placeholder="Last" autocomplete="off" name="c_last_name" id="c_last_name"/>
                            <span class="form-text text-muted"></span>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;">
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="text" class="form-control" placeholder="Email" name="cupemail" autocomplete="off" id="cupemail"/>
                                <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                    <span><i class="flaticon2-send"></i></span>
                                </span>
                            </div>
                            <span class="form-text text-muted"></span>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;">
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="password" class="form-control" placeholder="Password" name="cuppassword" id="cuppassword"/>
                                <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                    <span><i class="flaticon2-safe"></i></span>
                                </span>
                            </div>
                            <span class="form-text text-muted"></span>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;">
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="password" class="form-control" placeholder="Confirm Password" name="crpassword" id="crpassword"/>
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
                            <select class="form-control kt-selectpicker" style="width:100%;" id="cprofession" name="cprofession">
                                @foreach(_all_professions() as $pro)
                                <option value="{{ $pro['id'] }}">{{ $pro['profession'] }}</option>
                                @endforeach
                            </select>
                            <span class="form-text text-muted">Knowing your profession helps us provide you with better offerings.</span>
                        </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 form-group kt-login__actions">
                        <button id="signup_submit_mycart" class="btn btn-info btn-elevate kt-login__btn-primary" style="width:100%;">Sign Up</button>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="text-align:center;margin-bottom:-10px;margin-top:-30px;">
                        <label class="col-form-label col-lg-12 col-sm-12" style="font-size:10px;">By signing up, you agree to our <a href="">Terms of Service</a> and <a href="">Privacy Policy</a>.</label>
                    </div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                    <label class="col-form-label col-lg-12 col-sm-12">Already have an account? <a href="" id="checkout_login_modal" data-dismiss="modal" data-toggle="modal" data-target="#checkout_login_modal">Log In</a></label>
                </div>
            </form> 
        </div>
    </div>
</div>
<!-- end:: modals -->

@endsection
@section("scripts")
<script src="{{asset('js/purchase/mycart.js')}}" type="text/javascript"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VF9ED1G1M5"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-0770ZRBPZX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-0770ZRBPZX');
</script>
@endsection