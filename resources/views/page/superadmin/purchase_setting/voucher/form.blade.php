@extends('template.master_superadmin')
@section('title', 'Purchase Settings')
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
    {{ csrf_field() }}
    <input type="hidden" value="{{ $voucher_id }}" name="voucher_id" />
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-12">
                <form class="kt-form" id="voucher_form">
                    <div class="kt-portlet kt-portlet--height-fluid">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                {{ $voucher_id ? "Edit" : "Add" }} Voucher
                                </h3>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                    <li class="nav-item">
                                        <button type="button" class="btn btn-secondary btn-icon" data-toggle="kt-tooltip" data-placement="top" title="Go back to Promotions" onclick="window.location='/superadmin/settings/vouchers'"><i class="fa fa-arrow-left"></i></button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="row">  
                                <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label>Voucher Code <text class="kt-font-danger kt-font-bold">*</text></label>
                                        <input type="text" name="voucher_code" class="form-control" placeholder="VOUCHER{{ date('Y') }}" value="{{ $voucher->voucher_code ?? '' }}">
                                        <span class="form-text text-muted">Please use a unique voucher code</span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label>Discount Percentage(%) <text class="kt-font-danger kt-font-bold">*</text></label>
                                        <input type="text" name="discount" class="form-control" placeholder="30" value="{{ $voucher->discount ?? '' }}">
                                        <span class="form-text text-muted">Please enter a number from 1 to 100</span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label>Type <text class="kt-font-danger kt-font-bold">*</text></label>
                                        <select class="form-control kt-select" id="vtype" name="vtype" placeholder="Select Voucher type" value="{{ $voucher->type ?? '' }}">
                                            <option disabled selected>Select Voucher type</option>
                                            <option value="auto_applied">Auto Applied</option>
                                            <option value="auto_applied_when_loggedin">Auto Applied when user is logged in</option>
                                            <option value="manual_apply">Manual Apply</option>
                                        </select>
                                        <!-- <span class="form-text text-muted">Please use a unique voucher code</span> -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="description" class="form-control">
                                <span class="form-text text-muted">Please use a short description</span>
                            </div>
                            <div class="row">  
                                <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label>Start Date <text class="kt-font-danger kt-font-bold">*</text></label>
                                        <input type="text" name="start_date" class="form-control" value="{{ $voucher && $voucher->session_start ? date('m/d/Y', strtotime($voucher->session_start)) : '' }}">
                                        <span class="form-text text-muted">Please select a date minimum of today</span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label>End Date <text class="kt-font-danger kt-font-bold">*</text></label>
                                        <input type="text" name="end_date" class="form-control" value="{{ $voucher && $voucher->session_end ? date('m/d/Y', strtotime($voucher->session_end)) : '' }}">
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>
                            </div>                        
                        </div>  
                        <div class="kt-portlet__foot">
                            <div class="row justify-content-end">
                                <button type="submit" class="btn btn-success" id="voucher_form_submit">Submit</button> &nbsp; &nbsp;
                                <button type="reset" class="btn btn-secondary">Clear</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script src="{{asset('js/superadmin/purchase_settings/voucher-add-page.js')}}" type="text/javascript"></script>
@endsection
