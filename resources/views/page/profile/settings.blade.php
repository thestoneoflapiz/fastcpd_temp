@extends('template.master_noleft')
@section('title', 'Account & Profile')

@section('styles')
<style>
    .required{color:#fd397a;}
    .background_image-div{height:150px;width:260px;border-radius:5px;background-size:cover;background-position:center;}
    @media only screen and (max-width: 1150px) {
        .background_image-div{height:90px;width:180px;border-radius:5px;background-size:cover;background-position:center;}
    }
    @media only screen and (max-width: 750px) {
        .background_image-div{display:none;}
    }

    .waiting-status{}
    .pending-status{background-color:#f5d573 !important}
    .failed-status, .cancelled-status{background-color:#FD397A !important;color:white !important;}
    .paid-status{background-color:#85d8bf !important}
</style>
@endsection

@section('content')
{{ csrf_field() }}
<div class="kt-grid__item kt-grid__item--fluid kt-app__content">
    <div class="row kt-margin-b-15">
        <div class="col-xl-3 col-md-3 col-sm-4 col-6 kt-margin-b-5">
            <select class="form-control kt-select2" name="month_filter">
                <option value="01" <?=date("m") == 1 ? "selected" : ""?> >January</option>
                <option value="02" <?=date("m") == 2 ? "selected" : ""?> >February</option>
                <option value="03" <?=date("m") == 3 ? "selected" : ""?> >March</option>
                <option value="04" <?=date("m") == 4 ? "selected" : ""?> >April</option>
                <option value="05" <?=date("m") == 5 ? "selected" : ""?> >May</option>
                <option value="06" <?=date("m") == 6 ? "selected" : ""?> >June</option>
                <option value="07" <?=date("m") == 7 ? "selected" : ""?> >July</option>
                <option value="08" <?=date("m") == 8 ? "selected" : ""?> >August</option>
                <option value="09" <?=date("m") == 9 ? "selected" : ""?> >September</option>
                <option value="10" <?=date("m") == 10 ? "selected" : ""?> >October</option>
                <option value="11" <?=date("m") == 11 ? "selected" : ""?> >November</option>
                <option value="12" <?=date("m") == 12 ? "selected" : ""?> >December</option>
            </select>
        </div>
        <div class="col-xl-2 col-md-3 col-sm-3 col-6">
            <select class="form-control kt-select2" name="year_filter">
                @for($start_year = date("Y"); $start_year > 2014; $start_year--)
                <option value="<?=$start_year?>"><?=$start_year?></option>
                @endfor
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="row loading-div" style="display:none;"><div style="margin:auto;" class="kt-spinner kt-spinner--lg kt-spinner--dark"></div></div>
            <!--begin::Accordion-->
            <div class="accordion accordion-solid accordion-panel accordion-toggle-svg" id="overview-purchase-accordion" style="display:none;"></div>
            <!--end::Accordion-->
        </div>
    </div>
</div>
<!-- begin:modal for: part removal -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="cancel-order-modal" tabindex="-1" role="dialog" aria-labelledby="cancel-order-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancel-order-modal-label">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align:center;">You're trying to cancel your order reference# .<br>Are you sure you want to cancel it?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancel-order-modal-submit">Yes, I'm sure.</button>
            </div>
        </div>
    </div>
</div>
<!-- end:modal for: part removal -->

<!-- begin:modal for: view -->
<div class="modal fade" id="view-modal" tabindex="-1" role="dialog" aria-labelledby="view-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p style="text-align:center;">Credited CPD Units of << selected course >></p>
            </div>
        </div>
    </div>
</div>
<!-- end:modal for: view -->
@endsection
 
@section('scripts')
<script>
    var month = "<?=date("m") < 10 ? "0": ""?><?=date("m")?>";
    var year = "<?=date("Y")?>";
</script>
<script src="{{asset('js/settings/overview.js')}}" type="text/javascript"></script>
@endsection

