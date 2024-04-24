@extends('template.master_promoter')
@section('title', 'Promoter')
@section('styles')
<style>
    .centered{margin:auto;}
    .minimize > i{font-size:2rem !important;}
    .hidden{display:none;}
    /* HIDE RADIO */
    input.checked[type="radio"]{visibility:hidden;}

    IMAGE STYLES
    [type=radio] + img {
    cursor: pointer;
    }

    /* CHECKED STYLES */
    [type=radio]:checked + img {
    outline: 2px solid #f00;
    border-radius: 3px;
    }
</style>
@endsection
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-md-12">
                <!--begin:: Widgets/Best Sellers-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Marketing Tools
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#" role="tab" aria-selected="true" onclick="updateLink()">
                                        Update
                                    </a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#kt_widget5_tab2_content" role="tab" aria-selected="false">
                                        Webinar
                                    </a>
                                </li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget5_courses_content" aria-expanded="true">
                                <!-- <div class="kt-datatable kt-datatable--default" id="ajax_datatable_payout"></div> -->
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $marketing_link ?? 'No link yet'}}" id="title" width="auto" readonly>
                                    <div class="input-group-append">
                                        <a class="input-group-text" href="{{$marketing_link ?? '/marketing_tool'}}" >Go</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end:: Widgets/Best Sellers-->
            </div>
        </div>
    </div>
</div>

<!-- begin:Modal -->
@include('template.modal.delete')
@include('template.modal.pdf')


<!--begin::Modal-->
<div class="modal fade" id="update_link_form">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reference Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <!-- <h5>Available Banks:</h5> -->
                <div style="text-align:center">
                    <div class="form-group ">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button class="btn btn-secondary" type="button">Link</button>
                            </div>
                            <input type="text" class="form-control" id="link" placeholder="{{$marketing_link ?? '. . . '}}">
                        </div>
                        <label>Paste here your reference link</label>

                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirm_update">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!--end::Modal-->


@endsection
@section("scripts")
<!-- <script src="{{asset('js/superadmin/announcement/announcement.js')}}" type="text/javascript"></script> -->
<script>
    var prole = "";

    $("#add").click(function(){
        window.location="/users/add";
    });

    "use strict";
    // Class definition

    jQuery(document).ready(function() {

        $('#confirm_update').click(function(e) {
            var link =$('#link').val();
            if( $("#link").val() != ""){
                $.ajax({
                url: '/api/update_link',
                data: {
                    link: link
                },
                success: function(response){
                    toastr.success('Success!', response.message);
                    setTimeout(() => {
                        window.location = "/marketing_tool";
                    }, 1000);
                }
            });
            }else{
                toastr.error('Error!', 'Please Complete the Form if you wish to continue');
            }
            
        });
        $(".selection").change(function(e){
            var value = e.target.value;
            if(value=="empty" || value=="!empty"){
                $(this).next("input").attr("disabled", "disabled");
                $(this).next("input").val("");
            }else{
                $(this).next("input").removeAttr("disabled", "disabled");
            }            
        });

    });
    function updateLink() {
        $('#update_link_form').modal(); 
    }

    function copySelectedField($field_id) {
        var copyText = document.getElementById($field_id);
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        toastr.success('Success!', "Copied the text ");
    }
</script>
@endsection
