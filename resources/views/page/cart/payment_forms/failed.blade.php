@extends('template.master_purchase')
@section('styles')
<style>
	html {scroll-behavior: smooth;}
	.toast-title{color:white !important; font-weight:500;}
	.top{margin-top:30px;}
	.tab_icon {display: block;text-align: center;}
	.tab_text {display: block;text-align: center;}
	.my-group{float:left;padding:10px;}
	.kt-widget4 .kt-widget4__item .kt-widget4__pic img{width:8.5rem;}
	.price{color:#961f45 !important;}
</style>
@endsection
@section('content')
{{ csrf_field() }}
<div class="kt-container top" style="height:100vh;">
	<div class="row">
		<div class="col-12">
			<!--begin::Portlet-->
			<div class="kt-portlet">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title"><?="Complete Payment Transaction" ?? "Failed Payment Transaction";?></h3>
						</div>
                        <div class="kt-portlet__head-toolbar">
							<button class="btn btn-secondary" onclick="window.location='/profile/settings'"><i class="fa fa-arrow-left"></i> Go to Overview</button>
						</div>
					</div>
					<div class="kt-portlet__body">
                        <div class="kt-space-20"></div>
                        <div class="row justify-content-center">
                            <div class="col-xl-8 col-md-8 col-sm-10 col-12">
                                <p style="text-align:center;">
									@if(isset($message))
									<?=$message;?>
									@else
									<b>Transaction is being processed.</b> Please complete your payment, please check your email inbox and spam folder for the instructions. If you have completed payment, we’ll confirm it shortly. Please contact <b>payments@fastcpd.com</b> for any concerns.
									@endif
                                </p>
                            </div>
                        </div>
                        <div class="kt-space-20"></div>
					</div>
				</div>
			<!--end::Portlet-->
		</div>
	</div>
</div>
@endsection
@section("scripts")
@endsection
