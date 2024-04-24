@extends('template.master_form')
@section('styles')
<link href="{{ asset('css/pages/wizard/wizard-3.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/cropper.css' )}}" rel="stylesheet" type="text/css" />
<style>
    .required{color:red;}
    .box{padding:10px;border:1px solid #e2e5ec;border-radius:5px; min-height:120px}
    .required{color:#fd397a;}
    .bold{font-weight:600;}
    .box{padding: 5px;border:1px solid #e2e5ec;border-radius:5px; min-height:120px}
    .padding-bottom{padding-bottom:10px;}
    .profile_image {display: block;max-width: 100%;}
    .preview {overflow: hidden;width: 200px; height: 200px;margin: 0px 10px 0px 10px; border:1px solid #fd397a;}
    .dropzone .dz-preview .dz-details .dz-size span { display:none !important;}
    .dz-image img { width: 100%!important; height: 100% !important;}

    /**
     * Icons on Headline
     * 
     */
    .icon-image{height:80px;}
    @media (max-width: 600px){p.bold{text-align:center;}}
</style>
@endsection
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Become an Instructor
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="btn btn-secondary btn-icon" onclick="window.location='/'" data-toggle="kt-tooltip" data-placement="top" title="Go Back"><i class="fa fa-arrow-left"></i></button>
                    </li>
                </ul>
            </div>
        </div>
        @if(Auth::user()->email_verified_at!=null)
        <div class="kt-portlet__body register-initial-page">
            <div class="row justify-content-center">
                <div class="col-12" style="text-align:center;">
                    <h3>We want to make an impact to over 1,000,000 professionals in our country</h3>
                    <div class="kt-space-30"></div>
                </div>
                <div class="col-xl-4 col-md-4 col-12">
                    <div class="row">
                        <div class="col-xl-4 col-md-4 col-sm-4 col-12" align="center">
                            <img src="{{ asset('img/system/organization.png') }}" class="icon-image">
                        </div>
                        <div class="col-xl-8 col-md-7 col-sm-7 col-12">
                            <p class="bold">Allow accredited providers to add you and contribute to their organization</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-12">
                    <div class="row">
                        <div class="col-xl-4 col-md-4 col-sm-4 col-12" align="center">
                            <img src="{{ asset('img/system/instructor.png') }}" class="icon-image">
                        </div>
                        <div class="col-xl-8 col-md-7 col-sm-7 col-12">
                            <p class="bold">Get your own instructor's page and share courses where you are affiliated </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-12">
                    <div class="row">
                        <div class="col-xl-4 col-md-4 col-sm-4 col-12" align="center">
                            <img src="{{ asset('img/system/website.png') }}" class="icon-image">
                        </div>
                        <div class="col-xl-8 col-md-7 col-sm-7 col-12">
                            <p class="bold">Make meaningful education content for Professionals</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-space-20"></div>
            <div class="kt-form__actions" style="text-align:center;">
                <button class="btn btn-info btn-lg btn-tall btn-wide kt-font-bold kt-font-transform-u" id="start_wizard">
                    Become an Instructor
                </button>
            </div>    
            <div class="kt-space-30"></div>
            <div class="row justify-content-center">
                <h5>or do you want to sign up your own provider organization? <a href="/provider/register">Become a Provider</a></h5> 
            </div>
        </div>
        <div class="kt-portlet__body kt-portlet__body--fit wizard-page" style="display:none;">
            <div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="register_wizard" data-ktwizard-state="step-first">
                <div class="kt-grid__item">
                    <!--begin: Form Wizard Nav -->
                    <div class="kt-wizard-v3__nav">
                        <!--doc: Remove "kt-wizard-v3__nav-items--clickable" class and also set 'clickableSteps: false' in the JS init to disable manually clicking step titles -->
                        <div class="kt-wizard-v3__nav-items kt-wizard-v3__nav-items--clickable">
                            <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step" data-ktwizard-state="current">
                                <div class="kt-wizard-v3__nav-body">
                                    <div class="kt-wizard-v3__nav-label">
                                        <span>1</span> Basic Info
                                    </div>
                                    <div class="kt-wizard-v3__nav-bar"></div>
                                </div>
                            </div>
                            <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step">
                                <div class="kt-wizard-v3__nav-body">
                                    <div class="kt-wizard-v3__nav-label">
                                        <span>2</span> Personal
                                    </div>
                                    <div class="kt-wizard-v3__nav-bar"></div>
                                </div>
                            </div>
                            <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step">
                                <div class="kt-wizard-v3__nav-body">
                                    <div class="kt-wizard-v3__nav-label">
                                        <span>3</span> Track Record
                                    </div>
                                    <div class="kt-wizard-v3__nav-bar"></div>
                                </div>
                            </div>
                            <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step">
                                <div class="kt-wizard-v3__nav-body">
                                    <div class="kt-wizard-v3__nav-label">
                                        <span>4</span> Education & Employment
                                    </div>
                                    <div class="kt-wizard-v3__nav-bar"></div>
                                </div>
                            </div>
                            <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step">
                                <div class="kt-wizard-v3__nav-body">
                                    <div class="kt-wizard-v3__nav-label">
                                        <span>5</span> Relevant Info
                                    </div>
                                    <div class="kt-wizard-v3__nav-bar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end: Form Wizard Nav -->
                </div>
                <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v3__wrapper">
                    <!--begin: Form Wizard Form-->
                    <form class="kt-form" id="kt_form">
                        <!--begin: Form Wizard Step 1-->
                        <div class="kt-wizard-v3__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v3__form">
                                    <div class="row">
                                        <div class="col-lg-6 col-xl-6" style="text-align:center;">
                                            <div class="form-group"> 
                                                <div class="kt-avatar kt-avatar--outline" id="user_profile">
                                                    <div class="kt-avatar__holder" style="background-image: url({{ Auth::user()->image ?? asset('img/sample/noimage.png' )}})"></div>
                                                    <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change avatar">
                                                        <i class="fa fa-pen"></i>
                                                        <input type="file" name="profile_avatar" accept="image/*" value="{{ Auth::user()->image ?? '' }}">
                                                        <input type="hidden" id="user_profile_avatar" accept="image/*" value="{{ Auth::user()->image ?? '' }}">
                                                    </label>
                                                    <label class="col-form-label">Image <text class="required">*</text></label>
                                                    <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
                                                        <i class="fa fa-times"></i>
                                                    </span>
                                                </div>
                                                <span class="form-text text-muted">Please upload a clean white background image</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                    <label class="col-form-label">Name <text class="required">*</text></label>
                                                <div class="col-12">
                                                    <div class="kt-typeahead">
                                                        <input class="form-control" type="text" id="name" name="name" placeholder="Name" value="{{ Auth::user()->name }}">
                                                    </div>
                                                    <span class="form-text text-muted"></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-lg-6 col-sm-6 col-12">
                                                    <label class="col-form-label">Username <text class="required">*</text></label>
                                                    <div class="kt-typeahead">
                                                        <input class="form-control" type="text" id="username" name="username" placeholder="Username" value="{{ Auth::user()->username }}">
                                                    </div>
                                                    <span class="form-text text-muted"></span>
                                                </div>
                                                <div class="kt-space-20"></div>
                                                <div class="form-group col-lg-6 col-sm-6 col-12">
                                                    <label class="col-form-label">Nickname <text class="required">*</text></label>  
                                                    <div class="kt-typeahead">
                                                        <input class="form-control" type="text" id="nickname" name="nickname" placeholder="Nickname" value="">
                                                    </div>
                                                    <span class="form-text text-muted"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row"> 
                                        <label class="col-form-label">Headline <text class="required">*</text></label>
                                        <div class="col-12">
                                            <div class="kt-typeahead">
                                                <input class="form-control" type="text" id="headline" name="headline" placeholder="Headline" value="{{ Auth::user()->headline }}">
                                            </div>
                                            <span class="form-text text-muted"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row"> 
                                        <label class="col-form-label">About <text class="required">*</text></label>
                                        <div class="col-12" id="about_group">
                                            <textarea class="form-control" id="about" name="about" placeholder="About">{{ Auth::user()->about }}</textarea>
                                            <span class="form-text text-muted"></span>
                                        </div>
                                    </div>
                                    <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                                    <div class="row form-group">
                                        <div class="col-12">
                                            <div class="dropzone dropzone-default dropzone-info" id="dropzone_images">
                                                <div class="dropzone-msg dz-message needsclick">
                                                    <h2 class="dropzone-msg-title">PRC Identification <text class="required">*</text></h2>
                                                    <h3 class="dropzone-msg-title">Drop one(1) image per profession here</h3>
                                                    <h3 class="dropzone-msg-title">Total profession: <text id="pro_count">{{ count(json_decode(Auth::user()->professions)) }}</text></h3>
                                                    <span class="dropzone-msg-desc">Only .png, .jpg, .jpeg file type to upload</span>
                                                </div>
                                            </div>
                                            <span class="form-text text-muted"></span>
                                            <input class="form-control" type="text" style="transform:scale(0.0001)" name="prc" value="{{ Auth::user()->prc_id ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="form-group row">
                                                <div class="col-xl-12">
                                                    <select class="form-control kt-select2" id="profession" name="profession" multiple="multiple" style="width:100%;">
                                                    @foreach(_all_professions() as $pro)
                                                        @if (in_array($pro['id'], $data["profession_ids"]))
                                                        <option value="{{ $pro['id'] }}" selected>{{ $pro['profession'] }}</option>
                                                        @else
                                                        <option value="{{ $pro['id'] }}">{{ $pro['profession'] }}</option>
                                                        <!-- <option value="{{ $pro['id'] }}" disabled>{{ $pro['profession'] }}</option> -->
                                                        @endif
                                                    @endforeach
                                                    </select>
                                                    <span class="form-text text-muted"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12" id="display_professions">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end: Form Wizard Step 1-->

                        <!--begin: Form Wizard Step 2-->
                        <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v3__form">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Residence Address</label>
                                                <input class="form-control" type="text" name="residence_address">
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Business Address</label>
                                                <input class="form-control" type="text" name="business_address">
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Mobile Number</label>
                                                <input class="form-control" type="text" name="mobile_number" placeholder="+63 9**-****-***">
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Landline Number</label>
                                                <input class="form-control" type="text" name="landline_number">
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Nationality/Citizenship</label>
                                                <input class="form-control" type="text" name="nationality">
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end: Form Wizard Step 2-->

                        <!--begin: Form Wizard Step 3-->
                        <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">
                                Major Competency Areas
                            </div>
                            <div class="kt-form__section kt-form__section--first" id="repeater_form_mca">
                                <div class="kt-wizard-v3__form" data-repeater-list>
                                    <div class="row" data-repeater-item>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Specialization</label>
                                                <input class="form-control" type="text" name="specialization">
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-8">
                                            <div class="form-group">
                                                <label>Sub-Specialization</label>
                                                <input type="text" class="form-control" name="sub_specialization"/>
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-2 col-2">
                                            <a href="javascript:;" class="btn btn-danger btn-icon" data-repeater-delete><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div data-repeater-create class="btn btn btn-info">
                                            <i class="fa fa-plus"></i>
                                            <span>Add</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-heading kt-heading--md">
                                Relevant Seminars/Training Programs <b>Conducted</b> in the last five(5) years
                            </div>
                            <div class="kt-form__section kt-form__section--first" id="repeater_form_cp">
                                <div class="kt-wizard-v3__form" data-repeater-list>
                                    <div class="row" data-repeater-item>
                                        <div class="col-xl-4 col-md-4 col-6">
                                            <div class="form-group">
                                                <label>Date</label>
                                                <input class="form-control" type="text" name="conducted_date">
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-8">
                                            <div class="form-group">
                                                <label>Title of the Program</label>
                                                <input type="text" class="form-control" name="conducted_title"/>
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-2 col-2">
                                            <a href="javascript:;" class="btn btn-danger btn-icon" data-repeater-delete><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div data-repeater-create class="btn btn btn-info">
                                            <i class="fa fa-plus"></i>
                                            <span>Add</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-heading kt-heading--md">
                                Relevant Seminars/Training Programs <b>Attended</b> in the last five(5) years
                            </div>
                            <div class="kt-form__section kt-form__section--first" id="repeater_form_ap">
                                <div class="kt-wizard-v3__form" data-repeater-list>
                                    <div class="row" data-repeater-item>
                                        <div class="col-xl-4 col-md-4 col-6">
                                            <div class="form-group">
                                                <label>Date</label>
                                                <input class="form-control" type="text" name="attended_date">
                                                <span class="form-text text-muted">Please enter your unique accreditation number</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-8">
                                            <div class="form-group">
                                                <label>Title of the Program</label>
                                                <input type="text" class="form-control" name="attended_title"/>
                                                <span class="form-text text-muted">Please select the expiration of accreditation</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-2 col-2">
                                            <a href="javascript:;" class="btn btn-danger btn-icon" data-repeater-delete><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div data-repeater-create class="btn btn btn-info">
                                            <i class="fa fa-plus"></i>
                                            <span>Add</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-heading kt-heading--md">
                                Major Achievements, Citations, Recognition, and Awards
                            </div>
                            <div class="kt-form__section kt-form__section--first" id="repeater_form_ma">
                                <div class="kt-wizard-v3__form" data-repeater-list>
                                    <div class="row" data-repeater-item>
                                        <div class="col-xl-4 col-md-4 col-6">
                                            <div class="form-group">
                                                <label>Date</label>
                                                <input class="form-control" type="text" name="major_date">
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-6">
                                            <div class="form-group">
                                                <label>Title of the Program</label>
                                                <input type="text" class="form-control" name="major_title"/>
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-2 col-2">
                                            <a href="javascript:;" class="btn btn-danger btn-icon" data-repeater-delete><i class="fa fa-trash"></i></a>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Awarding Body</label>
                                                <input type="text" class="form-control" name="major_award"/>
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div data-repeater-create class="btn btn btn-info">
                                            <i class="fa fa-plus"></i>
                                            <span>Add</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end: Form Wizard Step 3-->

                         <!--begin: Form Wizard Step 4-->
                         <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">
                                Educational Background in College
                            </div>
                            <div class="kt-form__section kt-form__section--first" id="repeater_form_college">
                                <div class="kt-wizard-v3__form" data-repeater-list>
                                    <div class="row" data-repeater-item>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>University</label>
                                                        <input class="form-control" type="text" name="college_university">
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Address</label>
                                                        <input type="text" class="form-control" name="college_university_address"/>
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-4 col-md-4">
                                                    <div class="form-group">
                                                        <label>Inclusive Date</label>
                                                        <input class="form-control" type="text" name="college_date">
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Degree</label>
                                                        <input type="text" class="form-control" name="college_degree"/>
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-md-2 col-2">
                                                    <a href="javascript:;" class="btn btn-danger btn-icon" data-repeater-delete><i class="fa fa-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div data-repeater-create class="btn btn btn-info">
                                            <i class="fa fa-plus"></i>
                                            <span>Add</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-heading kt-heading--md">
                                Educational Background in Post-Graduate
                            </div>
                            <div class="kt-form__section kt-form__section--first" id="repeater_form_post_grad">
                                <div class="kt-wizard-v3__form" data-repeater-list>
                                    <div class="row" data-repeater-item>
                                        <div class="col-12">   
                                            <div class="row">
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>University</label>
                                                        <input class="form-control" type="text" name="post_grad_university">
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Address</label>
                                                        <input type="text" class="form-control" name="post_grad_university_address"/>
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-4 col-md-4">
                                                    <div class="form-group">
                                                        <label>Inclusive Date</label>
                                                        <input class="form-control" type="text" name="post_grad_date">
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Degree</label>
                                                        <input type="text" class="form-control" name="post_grad_degree"/>
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-md-2 col-2">
                                                    <a href="javascript:;" class="btn btn-danger btn-icon" data-repeater-delete><i class="fa fa-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div data-repeater-create class="btn btn btn-info">
                                            <i class="fa fa-plus"></i>
                                            <span>Add</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-heading kt-heading--md">
                                Work Experience: five(5) most recent
                            </div>
                            <div class="kt-form__section kt-form__section--first" id="repeater_form_work">
                                <div class="kt-wizard-v3__form" data-repeater-list>
                                    <div class="row" data-repeater-item>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Position</label>
                                                        <input class="form-control" type="text" name="work_position">
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Company</label>
                                                        <input type="text" class="form-control" name="work_company"/>
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Inclusive Date</label>
                                                        <input class="form-control" type="text" name="work_date">
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-md-2 col-2">
                                                    <a href="javascript:;" class="btn btn-danger btn-icon" data-repeater-delete><i class="fa fa-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div data-repeater-create class="btn btn btn-info">
                                            <i class="fa fa-plus"></i>
                                            <span>Add</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end: Form Wizard Step 4-->

                         <!--begin: Form Wizard Step 4-->
                         <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                            <div class="kt-form__section kt-form__section--first" id="repeater_form_aipo_mem">
                                <div class="kt-heading kt-heading--md">
                                    AIPO Membership
                                </div>
                                <div class="kt-wizard-v3__form" data-repeater-list>
                                    <div class="row" data-repeater-item>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>AIPO Membership</label>
                                                        <input class="form-control" type="text" name="aipo_membership">
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>National/Chapter</label>
                                                        <input type="text" class="form-control" name="aipo_national_chapter"/>
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Position</label>
                                                        <input class="form-control" type="text" name="aipo_position">
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-md-4">
                                                    <div class="form-group">
                                                        <label>Date</label>
                                                        <input type="text" class="form-control" name="aipo_date" val=""/>
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-md-2 col-2">
                                                    <a href="javascript:;" class="btn btn-danger btn-icon" data-repeater-delete><i class="fa fa-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div data-repeater-create class="btn btn btn-info">
                                            <i class="fa fa-plus"></i>
                                            <span>Add</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-form__section kt-form__section--first" id="repeater_form_other_mem">
                                <div class="kt-heading kt-heading--md">
                                    Other Major Affiliations(Professional,Civic)
                                </div>
                                <div class="kt-wizard-v3__form" data-repeater-list>
                                    <div class="row" data-repeater-item>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Membership</label>
                                                        <input class="form-control" type="text" name="other_membership">
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>National/Chapter</label>
                                                        <input type="text" class="form-control" name="other_national_chapter"/>
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Position</label>
                                                        <input class="form-control" type="text" name="other_position">
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-md-4">
                                                    <div class="form-group">
                                                        <label>Date</label>
                                                        <input type="text" class="form-control" name="other_date"/>
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-md-2 col-2">
                                                    <a href="javascript:;" class="btn btn-danger btn-icon" data-repeater-delete><i class="fa fa-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div data-repeater-create class="btn btn btn-info">
                                            <i class="fa fa-plus"></i>
                                            <span>Add</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end: Form Wizard Step 4-->

                        <!--begin: Form Actions -->
                        <div class="kt-form__actions">
                            <button class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-prev">
                                Previous
                            </button>
                            <button class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit" id="submit_form">
                                Submit
                            </button>
                            <button class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-next">
                                Next
                            </button>
                        </div>
                        <!--end: Form Actions -->
                    </form>
                    <!--end: Form Wizard Form-->
                </div>
            </div>
        </div>
        @else
        <div class="kt-portlet__body" style="text-align:center">
            <h3>Sorry!<br>You're email isn't verified yet. Please check your email's inbox!</h3>
        </div>
        @endif
    </div>
</div>
<div class="modal fade" id="cropper_modal" tabindex="-1" role="dialog" aria-labelledby="profile_image_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <img id="profile_image" height="400">
                    </div>
                    <div class="col-md-4">
                        <div class="preview"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" id="crop_submit">CROP</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script src="{{asset('js/cropper.js')}}" type="text/javascript"></script>
<script>
    var submitCounter = true;
    var daysOfWeek = ["Su","Mo","Tu","We","Th","Fr","Sa"];
    var monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    var existing_prc_images = <?=Auth::user()->prc_id ?? 0 ?>;
    var max_image = <?=count(json_decode(Auth::user()->professions)) ?? 0 ?>;
    var image_files = [];
    var no_images = 0;

    var uploaded_image = null;
    var profession_array = [];

    profession_array = <?=json_encode($data['professions_user']); ?>;

    var Profile = function () {
        var profile;
        var initUserForm = function() {
            profile = new KTAvatar('user_profile');
        }
        return {
            init: function() {
                initUserForm();
            }
        };
    }();

    var WizardForm = function () {
        // Base elements
        var wizardEl;
        var formEl;
        var validator;
        var wizard;

        var initWizard = function () {
            wizard = new KTWizard('register_wizard', {
                startStep: 1, // initial active step number
                clickableSteps: true  // allow step clicking
            });

            wizard.on('beforeNext', function(wizardObj) {
                var user_profile_avatar = $("#user_profile_avatar").val();
                if (validator.form() !== true) {
                    wizardObj.stop();  // don't go to the next step
                }else{
                    if(wizardObj.currentStep == 1){
                        if((profession_array.length == image_files.length && image_files.length > 0) || (existing_prc_images && existing_prc_images.length == profession_array.length)){
                            if(uploaded_image == null && user_profile_avatar == "" ){
                                toastr.error('User Image is required! ');
                                wizardObj.stop();
                            }else{
                                var submit_form = $.ajax({
                                        url: '/instructor/register/complete_register',
                                        type: 'POST',
                                        method: 'POST',
                                        data: {
                                            image: uploaded_image,
                                            name: $('#name').val(),
                                            nickname: $('#nickname').val(),
                                            username: $('#username').val(),
                                            headline: $("#headline").val(),
                                            about: $("#about").val(),
                                            image_files: image_files.length > 0 ? image_files : "same",
                                            professions: profession_array,
                                            _token: "{{ csrf_token() }}",
                                        },
                                        success: function(response){
                                            if(response.status!=200){
                                                toastr.error('Error!', response.message);
                                                wizardObj.stop();
                                            }
                                        },
                                        error: function(response){
                                            toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
                                            wizardObj.stop();
                                        }
                                    });
                            }
                            
                        }else{
                            if(profession_array.length != image_files.length){
                                toastr.error('Required!', `Sorry! The Number of ID's you need to upload should match the number of profession you have`);
                            }
                            wizardObj.stop();
                        }   
                    }
                }
            });

            wizard.on('beforePrev', function(wizardObj) {
                if (validator.form() !== true) {
                    wizardObj.stop();  // don't go to the next step
                }
            });

            wizard.on('change', function(wizard) {
                KTUtil.scrollTop();
            });
        }

        var input_masks = function () {
           $(`input[name="mobile_number"]`).inputmask("mask", {
               "mask": "+63 ***-***-****",
           });        
        }

        $.validator.addMethod("minDate", function( dateInput, element ) {
            dateGiven = new Date(dateInput);
            dateMinimum = new Date(Date.now() + 12096e5);

            return this.optional( element ) || dateGiven > dateMinimum;
        }, "Please choose a date at least 2 weeks from now" );

        var initValidation = function() {
            validator = formEl.validate({
                ignore: ":hidden",
                rules: {
                    //= Step 1
                    name: {
                        required: true,
                    },
                    username: {
                        required: true,
                    },
                    nickname: {
                        required: true,
                    },
                    headline: {
                        required: true,
                        maxlength: 250,
                    },
                    about: {
                        required: true,
                    },
                    profession: {
                        required: true,
                    },
                    prc: {
                        required: true,
                    },
                },

                invalidHandler: function(event, validator) {
                    KTUtil.scrollTop();

                    swal.fire({
                        "title": "",
                        "text": "There are some errors in your submission. Please correct them.",
                        "type": "error",
                        "confirmButtonClass": "btn btn-secondary"
                    });
                },

                submitHandler: function (form) {
                }
            });

            $(`input[name^=input_prc]`).each(function () {
                $(this).rules("add", {
                    required: true,
                });
            });

            $(`input[name^=input_expiration]`).each(function () {
                $(this).rules("add", {
                    required: false,
                });
            });
        }

        var initSubmit = function() {
            var btn = formEl.find('[data-ktwizard-type="action-submit"]');
            
            btn.on('click', function(e) {
                e.preventDefault();

                if (validator.form() && submitCounter) {
                    submitCounter=false;
                    KTApp.progress(btn);

                    var mca_values = [];
                    var cp_values = [];
                    var ap_values = [];
                    var ma_values = [];
                    var college_values = [];
                    var pg_values = [];
                    var we_values = [];
                    var aipo_values = [];
                    var other_values = [];

                    $(`input[name*="[specialization]"]`).each(function(index, e){
                        if($(this).val()!=""){
                            mca_values.push({
                                specialization: $(this).val(),
                                sub_specialization: $(`input[name="[${index}][sub_specialization]"]`).val(),
                            });
                        }
                    });

                    $(`input[name*="[conducted_date]"]`).each(function(index, e){
                        if($(this).val()!="" && $(`input[name="[${index}][conducted_title]"]`).val()!=""){
                            cp_values.push({
                                conducted_date: $(this).val(),
                                conducted_title: $(`input[name="[${index}][conducted_title]"]`).val(),
                            });
                        }
                    });

                    $(`input[name*="[attended_date]"]`).each(function(index, e){
                        if($(this).val()!="" && $(`input[name="[${index}][attended_title]"]`).val()!=""){
                            ap_values.push({
                                attended_date: $(this).val(),
                                attended_title: $(`input[name="[${index}][attended_title]"]`).val(),
                            });
                        }
                    });

                    $(`input[name*="[major_date]"]`).each(function(index, e){
                        if($(this).val()!="" && $(`input[name="[${index}][major_title]"]`).val()!="" && $(`input[name="[${index}][major_award]"]`).val()!=""){
                            ma_values.push({
                                major_date: $(this).val(),
                                major_title: $(`input[name="[${index}][major_title]"]`).val(),
                                major_award: $(`input[name="[${index}][major_award]"]`).val(),
                            });
                        }
                    });

                    $(`input[name*="[college_university]"]`).each(function(index, e){
                        if($(this).val()!="" && $(`input[name="[${index}][college_university_address]"]`).val()!="" && $(`input[name="[${index}][college_date]"]`).val()!=""  && $(`input[name="[${index}][college_degree]"]`).val()!=""){
                            college_values.push({
                                college_university: $(this).val(),
                                college_university_address: $(`input[name="[${index}][college_university_address]"]`).val(),
                                college_date: $(`input[name="[${index}][college_date]"]`).val(),
                                college_degree: $(`input[name="[${index}][college_degree]"]`).val(),
                            });
                        }
                    });

                    $(`input[name*="[post_grad_university]"]`).each(function(index, e){
                        if($(this).val()!="" && $(`input[name="[${index}][post_grad_university_address]"]`).val()!="" && $(`input[name="[${index}][post_grad_date]"]`).val()!=""  && $(`input[name="[${index}][post_grad_degree]"]`).val()!=""){
                            pg_values.push({
                                post_grad_university: $(this).val(),
                                post_grad_university_address: $(`input[name="[${index}][post_grad_university_address]"]`).val(),
                                post_grad_date: $(`input[name="[${index}][post_grad_date]"]`).val(),
                                post_grad_degree: $(`input[name="[${index}][post_grad_degree]"]`).val(),
                            });
                        }
                    });

                    $(`input[name*="[work_position]"]`).each(function(index, e){
                        if($(this).val()!="" && $(`input[name="[${index}][work_company]"]`).val()!="" && $(`input[name="[${index}][work_date]"]`).val()!=""){
                            we_values.push({
                                work_position: $(this).val(),
                                work_company: $(`input[name="[${index}][work_company]"]`).val(),
                                work_date: $(`input[name="[${index}][work_date]"]`).val(),
                            });
                        }
                    });

                    $(`input[name*="[aipo_membership]"]`).each(function(index, e){
                        if($(this).val()!="" && $(`input[name="[${index}][aipo_national_chapter]"]`).val()!="" && $(`input[name="[${index}][aipo_position]"]`).val()!="" && $(`input[name="[${index}][aipo_date]"]`).val()!=""){
                            aipo_values.push({
                                aipo_membership: $(this).val(),
                                aipo_national_chapter: $(`input[name="[${index}][aipo_national_chapter]"]`).val(),
                                aipo_position: $(`input[name="[${index}][aipo_position]"]`).val(),
                                aipo_date: $(`input[name="[${index}][aipo_date]"]`).val(),
                            });
                        }
                    });

                    $(`input[name*="[other_membership]"]`).each(function(index, e){
                        if($(this).val()!="" && $(`input[name="[${index}][other_national_chapter]"]`).val()!="" && $(`input[name="[${index}][other_position]"]`).val()!="" && $(`input[name="[${index}][other_date]"]`).val()!=""){
                            other_values.push({
                                other_membership: $(this).val(),
                                other_national_chapter: $(`input[name="[${index}][other_national_chapter]"]`).val(),
                                other_position: $(`input[name="[${index}][other_position]"]`).val(),
                                other_date: $(`input[name="[${index}][other_date]"]`).val(),
                            });
                        }
                    });

                    formEl.ajaxSubmit({
                        method: "POST",
                        url: "/instructor/resume/action",
                        data: {
                            nickname: $('input[name="nickname"]').val(),
                            residence_address: $('input[name="residence_address"]').val(),
                            business_address: $('input[name="business_address"]').val(),
                            mobile_number: $('input[name="mobile_number"]').val(),
                            landline_number: $('input[name="landline_number"]').val(),
                            nationality: $('input[name="nationality"]').val(),
                            major_compentency_area: mca_values,
                            conducted_programs: cp_values,
                            attended_programs: ap_values,
                            major_awards: ma_values,
                            college_background: college_values,
                            post_graduate_background: pg_values,
                            work_experience: we_values,
                            aipo_membership: aipo_values,
                            other_affiliations: other_values,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            toastr.success(`Successfully saved!`);
                            setTimeout(() => {
                                window.location="/";
                            }, 1500);
                        },
                        error: function(response) {
                            submitCounter=true;
                        }
                    });
                }
            });
        }

        return {
            init: function() {
                wizardEl = KTUtil.get('register_wizard');
                formEl = $('#kt_form');

                initWizard();
                input_masks();
                initValidation();
                initSubmit();
            }
        };
    }();

    var RepeaterFields = function () {
        var input_repeaters_track_record = function() {
            $('#repeater_form_mca').repeater({
                initEmpty: false,
                defaultValues: {
                    'text-input': 'foo'
                },
                show: function () {
                    $(this).slideDown();
                },
                hide: function (deleteElement) {           
                    $(this).slideUp(deleteElement);                 
                }   
            }); 

            $('#repeater_form_cp').repeater({
                initEmpty: false,
                defaultValues: {
                    'text-input': 'foo'
                },
                show: function () {
                    if($(`[name*="conducted_date"]`).length > 5){
                        toastr.error(`Sorry! We only need five(5) most recent!`);
                    }else{
                        $(this).slideDown();
                        $(`[name*="conducted_date"]`).each(function(){
                            $(this).datepicker({
                                todayHighlight: true,
                                maxDate: new Date(),
                                templates: {
                                    leftArrow: '<i class="la la-angle-left"></i>',
                                    rightArrow: '<i class="la la-angle-right"></i>'
                                },
                            });
                        });
                    }
                    
                },
                hide: function (deleteElement) {           
                    $(this).slideUp(deleteElement);                 
                }   
            });

            $('#repeater_form_ap').repeater({
                initEmpty: false,
                defaultValues: {
                    'text-input': 'foo'
                },
                show: function () {
                    if($(`[name*="attended_date"]`).length > 5){
                        toastr.error(`Sorry! We only need five(5) most recent!`);
                    }else{
                        $(this).slideDown();
                        $(`[name*="attended_date"]`).each(function(){
                            $(this).datepicker({
                                todayHighlight: true,
                                maxDate: new Date(),
                                templates: {
                                    leftArrow: '<i class="la la-angle-left"></i>',
                                    rightArrow: '<i class="la la-angle-right"></i>'
                                },
                            });
                        });
                    }   
                },
                hide: function (deleteElement) {           
                    $(this).slideUp(deleteElement);                 
                }   
            });

            $('#repeater_form_ma').repeater({
                initEmpty: false,
                defaultValues: {
                    'text-input': 'foo'
                },
                show: function () {
                    $(this).slideDown();
                    $(`[name*="major_date"]`).each(function(){
                        $(this).datepicker({
                            todayHighlight: true,
                            maxDate: new Date(),
                            templates: {
                                leftArrow: '<i class="la la-angle-left"></i>',
                                rightArrow: '<i class="la la-angle-right"></i>'
                            },
                        });
                    });
                },
                hide: function (deleteElement) {           
                    $(this).slideUp(deleteElement);                 
                }   
            });

            $(`[name*="conducted_date"]`).each(function(){
                $(this).datepicker({
                    todayHighlight: true,
                    maxDate: new Date(),
                    templates: {
                        leftArrow: '<i class="la la-angle-left"></i>',
                        rightArrow: '<i class="la la-angle-right"></i>'
                    },
                });
            });

            $(`[name*="attended_date"]`).each(function(){
                $(this).datepicker({
                    todayHighlight: true,
                    maxDate: new Date(),
                    templates: {
                        leftArrow: '<i class="la la-angle-left"></i>',
                        rightArrow: '<i class="la la-angle-right"></i>'
                    },
                });
            });

            $(`[name*="major_date"]`).each(function(){
                $(this).datepicker({
                    todayHighlight: true,
                    maxDate: new Date(),
                    templates: {
                        leftArrow: '<i class="la la-angle-left"></i>',
                        rightArrow: '<i class="la la-angle-right"></i>'
                    },
                });
            });
        }

        var input_repeaters_ee = function() {
            $('#repeater_form_college').repeater({
                initEmpty: false,
                defaultValues: {
                    'text-input': 'foo'
                },
                show: function () {
                    $(this).slideDown();
                    $(`[name*="college_date"]`).each(function(){
                        $(this).inputmask("9999 / 9999", {
                            "placeholder": "yyyy / yyyy",
                        });
                    });
                },
                hide: function (deleteElement) {           
                    $(this).slideUp(deleteElement);                 
                }   
            }); 

            $('#repeater_form_post_grad').repeater({
                initEmpty: false,
                defaultValues: {
                    'text-input': 'foo'
                },
                show: function () {
                    $(this).slideDown();
                    $(`[name*="post_grad_date"]`).each(function(){
                        $(this).inputmask("9999 / 9999", {
                            "placeholder": "yyyy / yyyy",
                        });
                    });
                },
                hide: function (deleteElement) {           
                    $(this).slideUp(deleteElement);                 
                }   
            });

            $('#repeater_form_work').repeater({
                initEmpty: false,
                defaultValues: {
                    'text-input': 'foo'
                },
                show: function () {
                    if($(`[name*="work_date"]`).length > 5){
                        toastr.error(`Sorry! We only need five(5) most recent!`);
                    }else{
                        $(this).slideDown();
                        $(`[name*="work_date"]`).each(function(){
                            $(this).inputmask("99-9999 / 99-9999", {
                                "placeholder": "mm-yyyy / mm-yyyy",
                            });
                        });
                    }
                },
                hide: function (deleteElement) {           
                    $(this).slideUp(deleteElement);                 
                }   
            });

            $(`[name*="college_date"]`).each(function(){
                $(this).inputmask("9999 / 9999", {
                    "placeholder": "yyyy / yyyy",
                });
            });

            $(`[name*="post_grad_date"]`).each(function(){
                $(this).inputmask("9999 / 9999", {
                    "placeholder": "yyyy / yyyy",
                });
            });

            $(`[name*="work_date"]`).each(function(){
                $(this).inputmask("99-9999 / 99-9999", {
                    "placeholder": "mm-yyyy / mm-yyyy",
                });
            });
        }

        var input_repeaters_ri = function() {
            $('#repeater_form_aipo_mem').repeater({
                initEmpty: false,
                defaultValues: {
                    'text-input': 'foo'
                },
                show: function () {
                    $(this).slideDown();
                    $(`[name*="aipo_date"]`).each(function(){
                        $(this).inputmask("99-9999 / 99-9999", {
                            "placeholder": "mm-yyyy / mm-yyyy",
                        });
                    });
                },
                hide: function (deleteElement) {           
                    $(this).slideUp(deleteElement);                 
                }   
            }); 

            $('#repeater_form_other_mem').repeater({
                initEmpty: false,
                defaultValues: {
                    'text-input': 'foo'
                },
                show: function () {
                    $(this).slideDown();
                    $(`[name*="other_date"]`).each(function(){
                        $(this).inputmask("99-9999 / 99-9999", {
                            "placeholder": "mm-yyyy / mm-yyyy",
                        });
                    });
                },
                hide: function (deleteElement) {           
                    $(this).slideUp(deleteElement);                 
                }   
            });

            $(`[name*="aipo_date"]`).each(function(){
                $(this).inputmask("99-9999 / 99-9999", {
                    "placeholder": "mm-yyyy / mm-yyyy",
                });
            });

            $(`[name*="other_date"]`).each(function(){
                $(this).inputmask("99-9999 / 99-9999", {
                    "placeholder": "mm-yyyy / mm-yyyy",
                });
            });
        }

        return {
            init: function() {
                input_repeaters_track_record();
                input_repeaters_ee();
                input_repeaters_ri();
            }
        };
    }();
    
    jQuery(document).ready(function() {
        Profile.init();
        WizardForm.init();
        RepeaterFields.init();

        $('#dropzone_images').dropzone({
            url: "/personal/register/action/images", // Set the url for your upload script location
            paramName: "file", // The name that will be used to transfer the file
            maxFiles: max_image,
            maxFilesize: 5, // MB
            addRemoveLinks: true,
            acceptedFiles: "image/*",
            init: function(){
                console.log(existing_prc_images);
                if(existing_prc_images){
                    for (var i = 0; i < existing_prc_images.length; i++) {
                        var re = /(?:\.([^.]+))?$/;
                        var ext = re.exec(existing_prc_images[i])[1];

                        var mockFile = { 
                            name: `PRCID${i+1}.${ext}`, 
                            type: `image/${ext}`, 
                            status: Dropzone.ADDED, 
                            url: existing_prc_images[i] 
                        };

                        this.emit("addedfile", mockFile);
                        this.emit("thumbnail", mockFile, existing_prc_images[i]);
                        this.emit("complete", mockFile);
                        this.files.push(mockFile);
                    } 

                    this.options.maxFiles = 0;
                }

                this.on("sending", function(file, xhr, formData){
                    no_images += 1;
                    formData.append("no_images", no_images);
                });

                this.on("complete", function (response) {
                    if(response.hasOwnProperty("xhr")){
                        var file = response.xhr.response;
                        image_files.push(file);
                        $('input[name="prc"]').val(file);
                    }
                });

                this.on("removedfile", function (response) {
                    
                    this.options.maxFiles = profession_array.length;
                    
                    if(response.hasOwnProperty("xhr")){
                        var file = response.xhr.response;
                        const index = image_files.indexOf(file);
                        if (index > -1) {
                            image_files.splice(index, 1);

                            if(index == 0){
                                $('input[name="prc"]').val(null);
                            }
                        }
                    }
                });
            }
        });

        renderProfessions(profession_array, [], true);
        
        $('#profession').select2({
            placeholder: "You can multiple-select Professions"
        }).change(function(e){
            var selected = e.target.selectedOptions; 
            var collect_selected = [];

            if(selected.length != 0){
                for (let option = 0; option < selected.length; option++) {
                    var value = selected[option];
                    collect_selected.push({
                        id: value.value,
                        name: value.label,
                        prc_no: null,
                    })
                }
            }else{
                profession_array = [];
                $('#dropzone_images')[0].dropzone.removeAllFiles();  
            }
            
            renderProfessions(profession_array, collect_selected, false);
            $("#pro_count").html(collect_selected.length);
            $('#dropzone_images')[0].dropzone.options.maxFiles = collect_selected.length;
        });

        $(`input[name="residence_address"]`).change(function(e){
            var business_address = $(`input[name="business_address"]`);
            if(!business_address.val()){
                business_address.val(e.target.value);
            }
        });
    });

    $('.kt-avatar__cancel').click(function(){
        uploaded_image = null;
    });

    // CROPPER JS
    var cropper_modal = $('#cropper_modal');
    var image = document.getElementById('profile_image');
    var cropper;

    $("input[type=file]").change(function(e){
        var files = e.target.files;
        if (files && files.length > 0) {
            var file = files[0];
            var reader = new FileReader();
        
            reader.onloadend = function () {
                $('#profile_image').attr('src', reader.result);
                $('#cropper_modal').modal({backdrop: 'static', keyboard: false, show:true});
            }

            if (file) {
                reader.readAsDataURL(file);
            } 
        }
    });

    cropper_modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 2,
            zoomable: false,
            maxHeight: 100,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });
    
    $("#crop_submit").click(function(){
        canvas = cropper.getCroppedCanvas({
            width: 200,
            height: 200,
        });
        canvas.toBlob(function(blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob); 
            reader.onloadend = function() {
                var base64data = reader.result;	
                console.log(base64data);
                uploaded_image = base64data;
                cropper_modal.modal('hide');
                $('.kt-avatar__holder').css("background-image", "url("+base64data+")");
            }
        });
    });

    $(`#start_wizard`).click(function(){
        $(`.register-initial-page`).slideUp(300);
        $(`.wizard-page`).slideDown(200);
    });

    function renderProfessions(profession, collected, from_start){
        var div = "";

        if(from_start){
            profession.map( (pro, key) => {
                div += "\
                    <div class='row padding-bottom'>\
                        <div class='col-xl-4 col-md-4'>\
                            <input type='text' class='form-control' disabled value='"+pro.name+"'/>\
                        </div>\
                        <div class='col-xl-4 col-md-4'>\
                            <input type='text' class='form-control' name='input_prc["+pro.id+"]' onchange='renderPRC("+pro.id+")' value='"+(pro.prc_no == null ? "" : pro.prc_no)+"' placeholder='License(PRC) Number'/>\
                        </div>\
                        <div class='col-xl-4 col-md-4'>\
                            <input type='text' class='form-control' name='input_expiration["+pro.id+"]' onchange='renderPRC("+pro.id+")' value='"+(pro.expiration_date == null ? "" : pro.expiration_date)+"' placeholder='License Expiration Date'/>\
                        </div>\
                    </div>\
                ";
            });
        }else{
            if(collected.length > 0){
                collected.map( (collect, key) => {
                    var finding = profession.find(pro=>pro.id==collect.id);
                    if(finding){
                        div += ` <div class='row padding-bottom'>
                            <div class='col-xl-4 col-md-4'>
                                <input type='text' class='form-control' disabled value='${finding.name}'/>
                            </div>
                            <div class='col-xl-4 col-md-4'>
                                <input type='text' class='form-control' name='input_prc[${finding.id}]' onchange='renderPRC(${finding.id})' value='${(finding.prc_no ? finding.prc_no : "")}' placeholder='License(PRC) Number'/>
                            </div>

                            <div class='col-xl-4 col-md-4'>
                                <input type='text' class='form-control' name='input_expiration[${finding.id}]' onchange='renderPRC(${finding.id})' value='${(finding.expiration_date ? finding.expiration_date : "")}' placeholder='License Expiration Date'/>
                            </div>
                        </div>`;    
                    }else{
                        div += `<div class='row padding-bottom'>
                                <div class='col-xl-4 col-md-4'>
                                    <input type='text' class='form-control' disabled value='${collect.name}'/>
                                </div>
                                <div class='col-xl-4 col-md-4'>
                                    <input type='text' class='form-control' name='input_prc[${collect.id}]' onchange='renderPRC(${collect.id})' placeholder='License(PRC) Number'/>
                                </div>

                                <div class='col-xl-4 col-md-4'>
                                    <input type='text' class='form-control' name='input_expiration[${collect.id}]' onchange='renderPRC(${collect.id})' placeholder='License Expiration Date'/>
                                </div>
                            </div>`;

                        profession_array.push({
                            id: collect.id,
                            name: collect.name,
                            prc_no: collect.prc_no,
                            expiration_date: collect.expiration_date,
                        });
                    }
                });
            }
        }
        
        $("#display_professions").empty().html(div);
            $(`[name^="input_expiration"]`).each(function(){
                $(this).datepicker({
                    todayHighlight: true,
                    defaultDate: "+1w",
                    minDate: new Date(),
                    templates: {
                        leftArrow: '<i class="la la-angle-left"></i>',
                        rightArrow: '<i class="la la-angle-right"></i>'
                    },
                });
            });
        WizardForm.init();
    }

    function renderPRC(id){
        var input = $(`[name="input_prc[${id}]"]`);
        var input_expiration = $(`[name="input_expiration[${id}]"]`);

        var newProfessionArray = profession_array.map((pro, key) => {
            if(pro.id == id){
                return {
                    id: pro.id,
                    name: pro.name,
                    prc_no: input.val(),
                    expiration_date: input_expiration.val(),
                };
            }

            return;
        });

        profession_array = newProfessionArray;
    }
</script>    
@endsection
