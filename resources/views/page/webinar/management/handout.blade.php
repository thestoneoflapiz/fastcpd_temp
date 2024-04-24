@extends('template.webinar.master_creation')

@section('styles')
<style>
    .group-fields{border:1px solid #E6E9EF; border-radius:3px;padding:20px;}
</style>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<form class="kt-form kt-form--label-left" id="handout_form" style="display:none;">
    {{ csrf_field() }}
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Handouts
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-xl-12">
                    <span>
                        <i class="fa fa-file-upload" style="color:#2A7DE9;"></i> &nbsp;
                        Upload handouts for your students. They can download and view these files to guide them through your webinar.
                    </span>
                </div>
                <div class="col-xl-12">
                    <span>
                        <i class="fa fa-file" style="color:#2A7DE9;"></i> &nbsp;
                        The following files are accepted: XLS, PDF, ZIP, CSV, DOCX files.
                    </span>
                </div>
            </div>
            <div class="kt-space-20"></div>
            <div class="kt-space-20"></div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="form-group">
                        <span class="kt-switch kt-switch--sm kt-switch--outline kt-switch--icon kt-switch--success">
                            <label>
                                <input type="checkbox" checked="checked" name="allow_handout_in_form">
                                <span></span>
                                &nbsp; &nbsp; Allow to upload downloadable handouts for students who purchased your webinar?
                            </label>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row" id="handouts_div">
                <div class="col-12">
                    <div class="kt-space-20"></div>
                    <div class="kt-space-20"></div>
                </div>
            </div>
    
            <div class="kt-space-20"></div>
            <div class="kt-space-20"></div>
            <div class="kt-form__content">
                <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="form_msg">
                    <div class="kt-alert__icon">
                        <i class="fa fa-exclamation-triangle"></i>
                    </div>
                    <div class="kt-alert__text">&nbsp; Sorry! You have to complete the form requirements first!</div>
                    <div class="kt-alert__close">
                        <button type="button" class="close" data-close="alert" aria-label="Close">
                        </button>
                    </div>
                </div>
            </div>
            <div id="hidden-handout-group-wrapper" style="display:none;">
                <div class="row group-fields">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5">
                        <div class="form-group">
                            <label class="bold">Title <text class="required">*</text></label>
                            <input class="form-control" type="text" name="ti-tle">
                            <span class="form-text text-muted">Please enter your handout title</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5">
                        <div class="form-group">
                            <label class="bold">Notes</label>
                            <input class="form-control" type="text" name="no-tes">
                            <span class="form-text text-muted">Please enter your notes.</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="kt-space-20"></div>
                        <button type="button" class="btn btn-danger remove-handout-form">
                            <i class="fa fa-trash"></i> Remove
                        </a>
                    </div>
                    <div class="col-xl-12 col-12">
                        <div class="form-group">
                            <label class="bold">Upload File <text class="required">*</text></label>
                            <div class="kt-uppy" id="uppy_handout_fileupload">
                                <div class="kt-uppy__wrapper"></div>
                                <div class="kt-uppy__list"></div>
                                <div class="kt-uppy__status"></div>
                                <div class="kt-uppy__informer kt-uppy__informer--min"></div>
                            </div>
                            <input hidden name="hand-out-file"/>
                            <span class="form-text text-muted">Max file size is 5MB and max number of files is 1</span>
                        </div>
                    </div>
                </div>                
            </div>
            <div class="row">
                <div class="col">
                    <div class="kt-space-20"></div>
                    <button type="button" class="btn btn btn-info add-another-hd">
                        <i class="fa fa-plus"></i>
                        <span>Add another Handout</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div style="float:right;">
                <button class="btn btn-success" id="submit_form">Submit</button>
            </div>
        </div>
    </div>
</form>

<div class="kt-portlet" id="allow-handout" style="display:none;">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Handouts
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="row">
            <div class="col-xl-12">
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <span class="kt-switch kt-switch--sm kt-switch--outline kt-switch--icon kt-switch--success">
                                <label>
                                    <input type="checkbox" name="allow_handout">
                                    <span></span>
                                    &nbsp; &nbsp; Allow to upload downloadable handouts for students who purchased your webinar?
                                </label>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__foot">
        <div style="float:right;">
            <button class="btn btn-success" id="submit-allow">Submit</button>
        </div>
    </div>
</div>

<!-- begin:modal for: removal -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="removal-modal" tabindex="-1" role="dialog" aria-labelledby="removal-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removal-modal-label">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align:center;">You're removing a handout.<br>Are you sure you want to remove it?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="removal-modal-submit">Yes, I'm sure.</button>
            </div>
        </div>
    </div>
</div>
<!-- end:modal for: part removal -->
@endsection

@section('scripts')
<script src="{{asset('plugins/custom/uppy/dist/uppy.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/webinar/creation/handout/handout.js')}}" type="text/javascript"></script>
@endsection