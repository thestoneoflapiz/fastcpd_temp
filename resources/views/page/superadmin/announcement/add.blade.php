@extends('template.master_superadmin')
@section('title', 'Users')
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
                            <h3 class="kt-portlet__head-title">Create Announcement</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                <li class="nav-item"  data-toggle="kt-tooltip" data-placement="top" title="Go back to Superadmin Users">
                                    <button type="button" class="btn btn-secondary btn-icon" onclick="window.location='/superadmin/announcements'"><i class="fa fa-arrow-left"></i></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end:Feature action buttons -->
                    <div class="kt-portlet__body">
                        <form class="kt-form kt-form--label-left" id="announce_form" autocomplete="off">
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
                                    <label class="col-form-label col-lg-3 col-sm-12">Target Audience <text class="required">*</text></label></label>
                                    <div class="col-lg-7 col-md-9 col-sm-12">
                                        <select class="form-control kt-select" id="target_audience" name="target_audience">
                                            <option value="general">General</option>
                                            <option value="students">Students</option>
                                            <option value="provider">Provider</option>
                                            <option value="instructor">Instructor</option>
                                            <option value="course">Course</option>
                                        </select>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>

                                <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Banner Color <text class="required">*</text></label></label>
                                    <div class="col-lg-7 col-md-9 col-sm-12">
                                        <select class="form-control kt-select" id="banner_state" name="banner_state" placeholder="Design of the banner">
                                            <option value="primary">Primary</option>
                                            <option value="secondary">Secondary</option>
                                            <option value="danger">Danger</option>
                                            <option value="warning">Warning</option>
                                            <option value="success">Success</option>
                                            <option value="info">Info</option>
                                            <option value="light">Light</option>
                                            <option value="dark">Dark</option>
                                        </select>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>

                                <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Title <text class="required">*</text></label></label>
                                    <div class="col-lg-7 col-md-9 col-sm-12">
                                        <div class="kt-typeahead">
                                            <input class="form-control" type="text" id="title" name="title" placeholder="Title of the announcement" >
                                        </div>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>

                                <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Message <text class="required">*</text></label></label>
                                    <div class="col-lg-7 col-md-9 col-sm-12">
                                        <div class="kt-typeahead">
                                            <textarea class="form-control text-area" type="text" id="message" name="message" placeholder="Announement . . . ." ></textarea>
                                        </div>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>

                                <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Start Date</label></label>
                                    <div class="col-lg-7 col-md-9 col-sm-12">
                                        <div class="kt-typeahead">
                                            <input type="text" name="start_date" class="form-control" placeholder="Start date of announcement">
                                        </div>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>

                                <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">End Date</label></label>
                                    <div class="col-lg-7 col-md-9 col-sm-12">
                                        <div class="kt-typeahead">
                                            <input type="text" name="end_date" class="form-control" placeholder="End date of announcement" >
                                        </div>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>
   
                            </div>
                            <div class="kt-portlet__foot">
                                <div class=" ">
                                    <div class="row">
                                        <div class="col-lg-9 ml-lg-auto">
                                            <button id="submit_form" class="btn btn-success" id="save_announcement">Save</button>
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
<script src="{{asset('js/superadmin/announcement/announcement-add.js')}}" type="text/javascript"></script>
@endsection