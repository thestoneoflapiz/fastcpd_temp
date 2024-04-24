@extends('template.master_superadmin')
@section('title', 'Promoters')
@section('styles')
<style>
    .centered{margin:auto;}
    .minimize > i{font-size:2rem !important;}
    .hidden{display:none;}
    .required{color:#fd397a;}
</style>
@endsection
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <!-- begin:Feature action buttons -->
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Invite Promoter</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                <li class="nav-item"  data-toggle="kt-tooltip" data-placement="top" title="Go back to Superadmin Users">
                                    <button type="button" class="btn btn-secondary btn-icon" onclick="window.location='/superadmin/promoters'"><i class="fa fa-arrow-left"></i></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end:Feature action buttons -->
                    <div class="kt-portlet__body">
                        <form class="kt-form kt-form--label-left" id="promoter_form" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="kt-portlet__body">
                                <div class="kt-form__content">
                                    <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="user_edit_form_msg">
                                        <div class="kt-alert__icon">
                                            <i class="fa fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="kt-alert__text">&nbsp;Sorry! You have to complete the form requirements first!</div>
                                        <div class="kt-alert__close">
                                            <button type="button" class="close" data-close="alert" aria-label="Close">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-xl-4 col-md-4">
                                        <div class="form-group">
                                            <label>First Name <text class="kt-font-danger kt-font-bold">*</text></label>
                                            <input class="form-control" type="text" id="firstname" name="firstname" placeholder="First Name" value="{{$promoter->first_name ?? ''}}">
                                            <input class="form-control" type="hidden" id="promoter_id" name="promoter_id" placeholder="First Name" value="{{$promoter->promoter_id ?? ''}}">
                                            <span class="form-text text-muted"></span>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-4">
                                        <div class="form-group">
                                            <label>Middle Name </label>
                                            <input class="form-control" type="text" id="middlename" name="middlename" placeholder="Middle Name" value="{{$promoter->middle_name ?? ''}}" >
                                            <span class="form-text text-muted"></span>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-4">
                                        <div class="form-group">
                                            <label>Last Name <text class="kt-font-danger kt-font-bold">*</text></label>
                                            <input class="form-control" type="text" id="lastname" name="lastname" placeholder="Last Name " value="{{$promoter->last_name ?? ''}}" >
                                            <span class="form-text text-muted"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-6 col-md-6">
                                        <div class="form-group">
                                            <label>Voucher Code <text class="kt-font-danger kt-font-bold">*</text></label>
                                            <input type="text" name="voucher_code" id="voucher_code" class="form-control" placeholder="PROMOTER{{ date('Y') }}" value="{{ $promoter->voucher_code ?? '' }}">
                                            <input type="hidden" name="voucher_id" id="voucher_id" class="form-control" placeholder="PROMOTER{{ date('Y') }}" value="{{ $promoter->voucher_id ?? '' }}">
                                            <span class="form-text text-muted">Please use a unique voucher code</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <div class="form-group">
                                            <label>Discount Percentage(%) <text class="kt-font-danger kt-font-bold">*</text></label>
                                            <input class="form-control" type="text" id="discount" name="discount" placeholder="50" value="{{ $promoter->discount ?? '' }}">
                                            <span class="form-text text-muted">Please enter a number from 1 to 99</span>
                                        </div>
                                    </div>
                                </div>   
                                <div class="form-group row">
                                    <div class="col-xl-12 col-md-12">
                                        <div class="form-group">
                                            <label>Email <text class="kt-font-danger kt-font-bold">*</text></label>
                                            <input class="form-control" type="text" id="email" name="email" placeholder="Email" value="{{ $promoter->email ?? '' }}">
                                            <span class="form-text text-muted">Please use a unique email</span>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                            <div class="kt-portlet__foot">
                                <div class=" ">
                                    <div class="row">
                                        <div class="col-lg-9 ml-lg-auto">
                                            <button id="submit_form" class="btn btn-success" id="save_promoter">Save</button>
                                            <button type="reset" class="btn btn-secondary">Clear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script src="{{asset('js/superadmin/promoter/promoter-edit.js')}}" type="text/javascript"></script>
@endsection