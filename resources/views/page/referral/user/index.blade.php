@extends('template.master_noleft')
@section('styles')
<style>
    .required{color:#fd397a;}
</style>
@endsection

@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Referral Code
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row justify-content-center">
                        @if($referral)
                            @if($referral->status=="blocked")
                            <div class="col-12 kt-margin-t-20"> 
                                <div class="alert alert-dark alert-bold" role="alert">
                                    <div class="alert-text">
                                        <h4 class="alert-heading">Why are you <b>BLOCKED</b> from the <b>Referral Code</b> program?</h4>
                                        <p><b>FastCPD management</b> have investigated your referral invitations and you've been reported as <b>abuse or fraud</b>. If you have any questions regarding on this issue, you can contact us on our <a href="https://www.facebook.com/fastcpd" target="_blank">Facebook page</a></p>
                                    </div>
                                </div>
                            </div>     
                            @elseif($referral->total_redeemed==10)
                            <div class="col-12 kt-margin-t-20"> 
                                <div class="alert alert-success alert-success" role="alert">
                                    <div class="alert-text">
                                        <h4 class="alert-heading">Hooray! You've <b>invited</b> ten users to <b>FastCPD!</b></h4>
                                        <p>Good job <b><?=Auth::user()->first_name ?? Auth::user()->name;?></b> for completing the <b>Referral Code</b> program! You can <b>redeem</b> your rewards below. Have a nice day!</p>
                                    </div>
                                </div>
                            </div>     
                            @else
                            <div class="col-12 kt-space-20"></div>
                            <h3 style="text-align:center;">YOU REFERRAL CODE IS:<br><b><?=$referral->referral_code?></b></h3>
                            <div class="col-12"> 
                                <div class="row justify-content-center kt-margin-t-10">
                                    <div class="col-xl-5 col-md-6 col-sm-6 col-10">
                                        <div class="form-group form-group-marginless">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="copy_referral_code" value="<?=URL::to("/referer/$referral->referral_code")?>" placeholder="REFERRAL-CODE" aria-describedby="copy_referral_code" readonly>
                                                <div class="input-group-append"><button class="btn btn-info input-group-text" onclick="copy_()">Copy</button></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center kt-margin-t-10"><p>OR</p></div>
                                <div class="row justify-content-center">
                                    <div class="fb-share-button" data-size="large" data-layout="button" data-href="<?=URL::to("/referer/$referral->referral_code")?>" data-layout="button_count"></div>
                                </div>
                                <div class="row justify-content-center kt-margin-t-20">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?=URL::to("/referer/{$referral->referral_code}?action=rcs")?>?size=800x800" />
                                </div>  
                                <div class="row justify-content-center">
                                   <span class="form-text text-muted">Let Referral scan this QR to proceed to signup</span>
                                </div>  
                            </div>
                            @endif
                            
                            @if(($referral->total_redeemed==10 && $referral->discount->v50==0 ) || ($referral->total_redeemed>=5 && $referral->discount->v30==0))
                            <div class="col-12 kt-margin-t-20">
                                <div class="row justify-content-center">
                                    <button class="btn btn-success" data-target="#redeem_voucher_modal" data-toggle="modal">REDEEM voucher for reaching <?=$referral->total_redeemed?> invites!</button>
                                </div>  
                            </div>
                            @endif

                            @if($referrals && $referrals->count()>0)
                            <!-- users, rewards and statistics -->
                            <div class="col-10 kt-margin-t-20">
                                <table class="table table-sm" width="100%">
                                    <thead class="">
                                        <tr>
                                            <th colspan="4">&nbsp;&nbsp; REFERRALS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($referrals as $no => $rf)
                                        <tr class="">
                                            <th scope="row"><?=$no+1?></th>
                                            <td><?=$rf->name ?? $rf->first_name?></td>
                                            <td><?=date("M. d, Y h:i A", strtotime($rf->created_at))?></td>
                                            <td><?=$rf->status=="redeemed" ? "<i class=\"fa fa-check kt-font-success\"></i>" :""?><i class="fa fa-check kt-font-<?=$rf->status=="waiting" ? "info" : ($rf->status=="approved"?"success" : "success")?>"></i> </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            
                        @else
                        <button class="btn btn-success" id="generate_code">Generate your code and redeem exciting voucher discounts!</button>
                        @endif   
                        @if(!$joined && $new_user)
                        <div class="col-12 kt-space-20"></div>
                        <p>OR</p>
                        <div class="col-12 kt-space-10"></div>
                        <p>Enter a referral code here to help your referer redeem rewards!<p>
                        <div class="col-12"> 
                            <form id="referral_code_form">
                                <div class="row justify-content-center">
                                    <div class="col-xl-3 col-4">
                                        <input class="form-control kt-font-bolder" type="text" style="text-align:center;font-size:1.3rem;" name="referral_code"/>
                                    </div>
                                </div>
                                <div class="row justify-content-center kt-margin-t-5">
                                    <button class="btn btn-success" id="referral_code_submit">Join</button>
                                </div>
                            </form>
                        </div>
                        @endif
                        <!--info  -->
                        <div class="col-12 kt-space-10"></div>
                        <div class="col-12 kt-margin-t-20"> 
                            <div class="alert alert-solid-info alert-bold" role="alert">
                                <div class="alert-text">
                                    <h4 class="alert-heading">Get up to <b>50% discount</b> by inviting your friends!</h4>
                                    <p>Share your own unique referral code to your friends. Earn rewards for every sign up your friends make.</p>
                                    </hr>
                                    <p class="mb-0">
                                        <b>TERMS AND CONDITIONS</b>
                                        <ul>
                                            <li> You need to be a fully verify your email address and personal information to join.</li>
                                            <li> Get 30% discount for 5 invited users and 50% discount for 10 invited users who sign up. </li>
                                            <li> FastCPD will be verifying each and every invited user who signs up. Only users who verify their email address and provide their PRC information are counted.</li>
                                        </ul>
                                    </p>
                                </div>
                            </div>
                        </div>     
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if($referral)
<!-- Modal:: Begin -->
<div class="modal fade" id="redeem_voucher_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-m" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Redeem Voucher</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    This voucher is unique and for your use only!
                </div>
                <div class="row justify-content-center">
                    <h3><b><?=$referral->voucher_code;?></b></h3>                    
                </div>
                <div class="row justify-content-center">
                    <ul>
                        <li>This voucher is at <b><?=$referral->total_redeemed==10 ? "50" :( $referral->total_redeemed>=5 && $referral->total_redeemed<10 ? "30": "")?>%</b> discount</li>
                        <li>You can only use this once at <b>30%</b> and at <b>50%</b></li>
                    </ul>                  
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
@section('scripts') 
<script src="{{asset('js/referral/user/page.js')}}" type="text/javascript"></script>
@endsection

