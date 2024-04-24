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
	.iconic-space{margin-bottom:20px;font-size:1.5rem !important;color:inherit !important;}
	.price{color:#961f45 !important;}
</style>
@endsection
@section('content')
{{ csrf_field() }}
<div class="kt-container top" style="margin-y">
	<div class="row checkout-container" style="display:none;">
		<div class="col-xl-8 col-lg-8 col-md-8 order-lg-3 order-xl-1">
			<!--begin::Portlet-->
			<div class="kt-portlet">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">Checkout</h3>
						</div>
					</div>
					<div class="kt-portlet kt-portlet--tabs">
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-toolbar">
								<ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#credit_debit" role="tab">
											<div class="my-group">
												<div class="tab_text">
													<div class="tab_icon">
														<i class="fa fa-credit-card iconic-space"></i>
													</div>
													Credit/Debit Card 
												</div>
												<div class="tab_text">
													No fees
												</div>
											</div>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#e_wallet" role="tab">
											<div class="my-group">
												<div class="tab_text">
													<div class="tab_icon">
														<i class="fa fa-wallet iconic-space"></i>
													</div>
													E-Wallet
												</div>
												<div class="tab_text">
													GCash, GrabPay
												</div>
											</div>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#online_banking" role="tab">
											<div class="my-group">
												<div class="tab_text">
													<div class="tab_icon">
														<i class="fa fa-tablet-alt iconic-space"></i>
													</div>
													Online Banking
												</div>
												<div class="tab_text">
													BPI, BDO, others
												</div>
											</div>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#dragonpay" role="tab">
											<div class="my-group">
												<div class="tab_text">
													<div class="tab_icon">
														<i class="fa fa-landmark iconic-space"></i>
													</div>
													Bank Deposit OTC
												</div>
												<div class="tab_text">
													BPI, BDO, others
												</div>
											</div>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#paypal" role="tab">
											<div class="my-group">
												<div class="tab_text">
													<div class="tab_icon">
														<i class="fa fa-cash-register iconic-space"></i>
													</div>
													Payment Centers
												</div>
												<div class="tab_text">
													SM, Bayad Center, others
												</div>
											</div>
										</a>
									</li>
								</ul>
							</div>
						</div>
						<div class="kt-portlet__body">
							<div class="tab-content">
								<div class="tab-pane active" id="credit_debit" role="tabpanel">
									<div class="kt-portlet__body">
										<div class="row">
											<div class="kt-padding-l-20">
												<img style="height:50px;" src="{{asset('img/system/visa.png')}}"/>
											</div>
											<div class="kt-padding-l-20">
												<img style="height:50px;" src="{{asset('img/system/mastercard.png')}}"/>
											</div>
											<div class="kt-padding-l-20">
												<img style="height:50px;" src="{{asset('img/system/jcb.png')}}"/>
											</div>
										</div>
										<br/>
										<form id="form-pm-card" autocomplete="off">
											<div class="row">
												<div class="form-group col-xl-4 col-md-5 col-6">
													<label class="col-form-label">Card Number <text class="kt-font-danger kt-font-bolder">*</text></label>
													<input class="form-control" type="text" name="card_number" placeholder="**** **** **** ****">
													<span class="form-text text-muted"></span>
												</div>
												<div class="form-group col-xl-4 col-md-5 col-6">
													<label class="col-form-label">Name on Card <text class="kt-font-danger kt-font-bolder">*</text></label>
													<input class="form-control" type="text" name="name" value="{{ Auth::user()->name }}">
													<span class="form-text text-muted"></span>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-xl-4 col-md-4 col-4">
													<label class="col-form-label">Expiration Month <text class="kt-font-danger kt-font-bolder">*</text></label>
													<input class="form-control" type="text" name="expiration_month" placeholder="Month">
													<span class="form-text text-muted"></span>
												</div>
												<div class="form-group col-xl-4 col-md-4 col-4">
													<label class="col-form-label">Expiration Year <text class="kt-font-danger kt-font-bolder">*</text></label>
													<input class="form-control" type="text" name="expiration_year" placeholder="Year">
													<span class="form-text text-muted"></span>
												</div>
												<div class="form-group col-xl-2 col-md-2 col-3">
													<label class="col-form-label"><i class="fa fa-question-circle" data-toggle="kt-tooltip" title="" data-html="true" data-content="<img src='<?=asset("img/bank-icons/card-cvc.jpg")?>' height='80'/>" data-original-title="<img src='<?=asset("img/bank-icons/card-cvc.jpg")?>' height='80'/>"></i> CVC <text class="kt-font-danger kt-font-bolder">*</text></label>
													<input class="form-control" type="text" name="cvv" placeholder="***">
													<span class="form-text text-muted"></span>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-xl-4 col-md-5 col-6">
													<label class="col-form-label">Email <text class="kt-font-danger kt-font-bolder">*</text></label>
													<input class="form-control" type="text" name="email" value="{{ Auth::user()->email }}">
													<span class="form-text text-muted"></span>
												</div>
												<div class="form-group col-xl-4 col-md-5 col-6">
													<label class="col-form-label">Phone Number</label>
													<input class="form-control" type="text" name="phone" value="{{ Auth::user()->contact }}">
													<span class="form-text text-muted"></span>
												</div>
											</div>
											<div class="row">
												<label class="col-12 col-form-label">
													Required payment minimum of &#8369;100.00 <br/>
													You will be redirected to a secure website to complete your payment <br/>
													<i class="fa fa-lock"></i>&nbsp; We do not store or access your credit/debit card information. FastCPD use services of a secure payment gateway to provide safe and secure payment procedures.
												</label>
												<br/><br/>
												<div class="col-8">
													<button id="paymongo-pm-card" class="btn btn-lg btn-info btn-upper kt-font-bolder" {{ $total_amount < 100 ? "disabled" : "" }} style="width:100%;">pay now</button>
												</div>
											</div>
										</form>
									</div>
								</div>
								<div class="tab-pane" id="e_wallet" role="tabpanel">
									<div class="kt-portlet__body">
										<div class="row">
											<div class="kt-padding-l-20">
												<img style="height:50px;" src="{{asset('img/system/gcash.png')}}"/>
											</div>
											<div class="kt-padding-l-20">
												<img style="height:50px;" src="{{asset('img/system/grab.png')}}"/>
											</div>
										</div>
										<br/>
										<form id="form-pm-wallet" autocomplete="off">
											<div class="form-group row">
												<div class="col-8">
													<label for="select-e_wallet-source" class="col-form-label">Select GCash or GrabPay to complete your purchase</label>
													<select class="form-control" name="e_method" id="select-e_wallet-source" style="width:100%;">
														<option></option>
														<option value="gcash">GCash</option>
														<option value="grab_pay">GrabPay</option>
													</select>
												</div>
											</div>
											<div class="row">
												<label class="col-12 col-form-label">
													Required payment minimum of &#8369;100.00 <br/>
													You will be redirected to a secure website to complete your payment
												</label>
												<br/><br/>
												<div class="col-8">
													<button id="paymongo-pm-e_wallet" class="btn btn-lg btn-info btn-upper kt-font-bolder" {{ $total_amount < 100 ? "disabled" : "" }} style="width:100%;">pay now</button>
												</div>
											</div>
										</form>
									</div>
								</div>
								<div class="tab-pane" id="online_banking" role="tabpanel">
									<form id="form-ob" autocomplete="off">
										<div class="kt-portlet__body">
											<div class="row">
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/bdo.jpg')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/bpi.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/cbank.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;border:1px solid green;" src="{{asset('img/bank-icons/landbank.jpg')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/maybank.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/mbank.jpg')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;border:1px solid blue;" src="{{asset('img/bank-icons/psbank.jpg')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/rcbc.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/rbank.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/unionbank.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/ucpb.png')}}"/>
												</div>
											</div>
											<br/>
											<div class="form-group row">
												<div class="col-8">
													<label for="select-online_banking-source" class="col-form-label">Select a Bank to complete your purchase</label>
													<select class="form-control" id="select-online_banking-source" name="method" style="width:100%;">
														<option></option>
														<option value="BDO">Banco de Oro (BDO)</option>
														<option value="BPIB">Bank of the Philippine Islands (BPI)</option>
														<option value="CBC">Chinabank</option>
														<option value="LBPA">Landbank</option>
														<option value="MAYB">Maybank</option>
														<option value="MBTC">Metrobank</option>
														<option value="PSB">PSBank</option>
														<option value="RCBC">Rizal Commercial Banking Corp (RCBC)</option>
														<option value="RSB">Robinsons Bank</option>
														<option value="UBPB">Unionbank</option>
														<option value="UCPB">United Coconut Planters Bank (UCPB)</option>
													</select>
													<span class="form-text text-muted"></span>
												</div>
											</div>
											<div class="row">
												<label class="col-12 col-form-label">
													You will be redirected to a secure website to complete your payment<br/>
													Online banking fee may be included<br/>
													You will receive a confirmation email once we receive confirmation of your payment
												</label>
												<div class="col-12">
													<label class="kt-checkbox">
														<input type="checkbox" name="agreement"/> I agree to the <a href="https://www.dragonpay.ph/terms-and-conditions" target="_blank"> Terms and Conditions</a>
														<span></span>
													</label>
												</div>
												<br/><br/>
												<div class="col-8">
													<button type="submit" id="dragonpay-pm-online_banking" class="btn btn-lg btn-info btn-upper kt-font-bolder" {{ $total_amount ? "" : "disabled" }} style="width:100%;">pay now</button>
												</div>
											</div>
										</div>
									</form>
								</div>
								<div class="tab-pane" id="dragonpay" role="tabpanel">
									<form id="form-obotc" autocomplete="off">
										<div class="kt-portlet__body">
											<div class="row">
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/aub.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/bdo.jpg')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/cbank.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/eastwest.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;border:1px solid green;" src="{{asset('img/bank-icons/landbank.jpg')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/mbank.jpg')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;border:1px solid blue;" src="{{asset('img/bank-icons/pnb.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/sbank.jpg')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/rcbc.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/rbank.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/unionbank.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/ucpb.png')}}"/>
												</div>
											</div>
											<br/>
											<div class="form-group row">
												<div class="col-8">
													<label for="select-online_banking_otc-source" class="col-form-label">Select a Bank to complete your purchase</label>
													<select class="form-control" id="select-online_banking_otc-source" name="method" style="width:100%;">
														<option></option>
														<option value="AUB">Asia United Bank (AUB)</option>
														<option value="BDRX">Banco de Oro (BDO)</option>
														<option value="CBCX">Chinabank</option>
														<option value="EWXB">EastWest Bank</option>
														<option value="LBXB">Landbank</option>
														<option value="MBXB">Metrobank</option>
														<option value="PNXB">Philippine National Bank (PNB)</option>
														<option value="SBCB">Security Bank</option>
														<option value="RCXB">Rizal Commercial Banking Corp (RCBC)</option>
														<option value="RSBB">Robinsons Bank</option>
														<option value="UBXB">Unionbank</option>
														<option value="UCXB">United Coconut Planters Bank (UCPB)</option>
													</select>
													<span class="form-text text-muted"></span>
												</div>
											</div>
											<div class="row">
												<label class="col-12 col-form-label">
													You will be redirected to a secure website to complete your payment<br/>
													Over the Counter banking fee may be included<br/>
													Please expect an email with instructions on how to complete the deposit <br/>
													You will receive a confirmation email once we receive confirmation of your payment
												</label>
												<div class="col-12">
													<label class="kt-checkbox">
														<input type="checkbox" name="agreement"/> I agree to the <a href="https://www.dragonpay.ph/terms-and-conditions" target="_blank"> Terms and Conditions</a>
														<span></span>
													</label>
												</div>
												<br/><br/>
												<div class="col-8">
													<button type="submit" id="dragonpay-pm-online_banking_otc" class="btn btn-lg btn-info btn-upper kt-font-bolder" {{ $total_amount ? "" : "disabled" }} style="width:100%;">pay now</button>
												</div>
											</div>
										</div>
									</form>
								</div>
								<div class="tab-pane" id="paypal" role="tabpanel">
									<form id="form-pc" autocomplete="off">
										<div class="kt-portlet__body">
											<div class="row">
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;border:1px solid blue;" src="{{asset('img/bank-icons/sm.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/bcenter.png')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/clhulier.jpg')}}"/>
												</div>
												<div class="kt-padding-l-20 kt-padding-b-10">
													<img style="height:50px;border-radius:3px;border:1px solid red;" src="{{asset('img/bank-icons/mlhulier.jpg')}}"/>
												</div>
											</div>
											<br/>
											<div class="form-group row">
												<div class="col-8">
													<label for="select-payment_center-source" class="col-form-label">Select a Bank to complete your purchase</label>
													<select class="form-control" id="select-payment_center-source" name="method" style="width:100%;">
														<option></option>
														<option value="SMR">SM Payment Center</option>
														<option value="BAYD">Bayad Center</option>
														<option value="CEBL">Cebuana Lhullier</option>
														<option value="MLH">M. Lhullier</option>
													</select>
													<span class="form-text text-muted"></span>
												</div>
											</div>
											<div class="row">
												<label class="col-12 col-form-label">
													You will be redirected to a secure website to complete your payment<br/>
													Please expect an email with instructions on how to complete the deposit <br/>
													You will receive a confirmation email once we receive confirmation of your payment
												</label>
												<div class="col-12">
													<label class="kt-checkbox">
														<input type="checkbox" name="agreement"/> I agree to the <a href="https://www.dragonpay.ph/terms-and-conditions" target="_blank"> Terms and Conditions</a>
														<span></span>
													</label>
												</div>
												<br/><br/>
												<div class="col-8">
													<button type="submit" id="dragonpay-pm-payment_centers" class="btn btn-lg btn-info btn-upper kt-font-bolder" {{ $total_amount ? "" : "disabled" }} style="width:100%;">pay now</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			<!--end::Portlet-->
			<!--begin:: Widgets/New Users-->
			<div class="kt-portlet kt-portlet--tabs">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<span class="kt-badge kt-badge--danger">{{ $total_items }}</span> &nbsp; &nbsp;
						<h3 class="kt-portlet__head-title">Order Details </h3>
					</div>
				</div>
				<div class="kt-portlet__body kt-scroll" data-scroll="true" >
					<div class="kt-widget4">
						@foreach($my_cart as $mca)
						<div class="kt-widget4__item">
							<div class="kt-widget4__pic">
								<img alt="FastCPD Courses <?=$mca["title"]?>" src="{{ $mca['poster'] ?? asset('img/sample/noimage.png') }}" class="course-poster-img">
							</div>
							<div class="kt-widget4__info">
								<span class="kt-widget4__text kt-font-bolder">{{ $mca["title"] }}</span>
								<p class="kt-widget4__text ">
									@if($mca["accreditation"])
									@foreach($mca["accreditation"] as $acc)
									{{ $acc->title }} ({{ $acc->program_no }} &#9679; {{ $acc->units }}) <br/>
									@endforeach
									@endif
								</p>
							</div>
							<div class="kt-widget5__stats" style="text-align:right;">
								@if($mca["discount"])
								<span class="kt-widget5__number kt-font-bold price" style="font-size:14px;"><b>&#8369;{{ number_format($mca["total_amount"], 2, '.', ',') }}</b></span> <br/>
								<span class="kt-widget5__votes" style="text-decoration:line-through;">&#8369;{{ number_format($mca["price"], 2, '.', ',') }}</span>
								@else
								<span class="kt-widget5__number kt-font-bold price" style="font-size:14px;"><b>&#8369;{{ number_format($mca["total_amount"], 2, '.', ',') }}</b></span> <br/>
								@endif
								<input type="hidden" name="real_price" value="<?=$total_amount?>" />
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
			<!--end:: Widgets/New Users-->
		</div>
		<div class="col-xl-4 col-lg-4 col-md-4 order-lg-3 order-xl-1" style="height:400px;">
			<!--begin:: Widgets/New Users-->
			<div class="kt-portlet">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h1 class="kt-portlet__head-title">
							Summary
						</h1>
					</div>
				</div>
				<div class="kt-portlet__body">
					<div class="kt-widget4">
						<div class="kt-widget4__item">
							<div class="kt-widget4__info">
								<span class="kt-widget4__username kt-font-dark" style="font-size:16px;">
									Original Price
								</span>
								<span class="kt-widget4__username kt-font-dark" style="font-size:16px;">
									Voucher Discount
								</span>
							</div>
							<div class="kt-widget5__stats" style="text-align:right;">
								<span class="kt-widget5__number kt-font-dark" style="font-size:16px;">&#8369;{{ number_format($total_original_price, 2, '.', ',') }}</span> <br/>
								<span class="kt-widget5__number kt-font-dark" style="font-size:16px;">- &#8369;{{ $total_discounted_amount ? number_format($total_discounted_amount, 2, '.', ',') : 0}}</span>
							</div>
						</div>
						<div class="kt-widget4__item">
							<div class="kt-widget4__info">
								<span class="kt-widget4__username kt-font-dark" style="font-size:16px;font-weight:bold;">
									Total
								</span>
							</div>
							<div class="kt-widget5__stats">
								<span class="kt-widget5__number price" style="font-size:18px;font-weight:bold;">&#8369;{{ number_format($total_amount, 2, '.', ',') }}</span>
							</div>
						</div>
						<div class="kt-widget4__item">
							<div class="kt-widget4__info">
								<div class="kt-align-left">
									<span class="kt-widget4__text kt-font-dark">
										CPD units you’ll earn with this purchase:
									</span>
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
								<div class="kt-align-left">
									<span class="kt-widget4__text kt-font-dark" style="font-size:11px;">
										By completing your purchase you agree to these <a href="/terms">Terms of Service</a>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--end:: Widgets/New Users-->
		</div>
	</div>
</div>
@endsection
@section("scripts")
<script>
	var total = <?=$total_amount ?? 0?>;
	if(total<=0){
		window.location="/";
	}else{
		$(".checkout-container").show();
	}
</script>
<script src="{{asset('js/purchase/checkout.js')}}" type="text/javascript"></script>
@endsection
