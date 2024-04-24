@extends('template.master_promoter')
@section('title', 'Settings')
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
            <!--Begin:: App Content-->
			<div class="kt-grid__item kt-grid__item--fluid kt-app__content">
                <!--Begin::Section-->
                <div class="row">
                    <div class="col-md-6 col-xl-4">
                        <!--Begin::Portlet-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__head kt-portlet__head--noborder">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">
                                    </h3>
                                </div>
                                <!-- <div class="kt-portlet__head-toolbar">
                                    <a href="#" class="btn btn-icon" data-toggle="dropdown">
                                        <i class="flaticon-more-1 kt-font-brand"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="kt-nav">
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                    <span class="kt-nav__link-text">Reports</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-send"></i>
                                                    <span class="kt-nav__link-text">Messages</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                    <span class="kt-nav__link-text">Charts</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                    <span class="kt-nav__link-text">Members</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                    <span class="kt-nav__link-text">Settings</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div> -->
                            </div>
                            <div class="kt-portlet__body">

                                <!--begin::Widget -->
                                <div class="kt-widget kt-widget--user-profile-2">
                                    <div class="kt-widget__head">
                                        <div class="kt-widget__media">
                                            <img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/bpi.png')}}"/>
                                            <div class="kt-widget__pic kt-widget__pic--success kt-font-success kt-font-boldest kt-hidden">
                                                ChS
                                            </div>
                                        </div>
                                        <div class="kt-widget__info">
                                            <a href="#" class="kt-widget__username">
                                                Bank of Philippine Island (BPI)
                                            </a>
                                            <span class="kt-widget__desc">
                                                Account Details
                                            </span>
                                        </div>
                                    </div>
                                    <div class="kt-widget__body">
                                        <div class="kt-widget__section">
                                            
                                        </div>
                                        <div class="kt-widget__item">
                                            <div class="kt-widget__contact">
                                                <span class="kt-widget__label">Account Name:</span>
                                                <i class="kt-widget__data"><i>{{ $account_info->bpi_acc_name ?? 'no data yet' }}</i></i>
                                                <input type="hidden" id="bpi_acc_name" value="{{ $account_info->bpi_acc_name ?? 'no data yet' }}">
                                            </div>
                                            <div class="kt-widget__contact">
                                                <span class="kt-widget__label">Account Number:</span>
                                                <i href="#" class="kt-widget__data">{{ $account_info->bpi_acc_number ?? 'no data yet' }}</i>
                                                <input type="hidden" id="bpi_acc_number" value="{{ $account_info->bpi_acc_number ?? 'no data yet' }}">
                                            </div>
                                            <div class="kt-widget__contact">
                                                <span class="kt-widget__label">Contact Number:</span>
                                                <i href="#" class="kt-widget__data">{{ $account_info->bpi_contact ?? 'no data yet' }}</i>
                                                <input type="hidden" id="bpi_contact" value="{{ $account_info->bpi_contact ?? 'no data yet' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget__footer">
                                        <button type="button" class="btn btn-label-danger btn-lg btn-upper" onclick="updateBank('bpi')">UPDATE</button>
                                    </div>
                                </div>

                                <!--end::Widget -->
                            </div>
                        </div>

                        <!--End::Portlet-->
                    </div>
                    <div class="col-md-6 col-xl-4">

                        <!--Begin::Portlet-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__head kt-portlet__head--noborder">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">
                                    </h3>
                                </div>
                                <!-- <div class="kt-portlet__head-toolbar">
                                    <a href="#" class="btn btn-icon" data-toggle="dropdown">
                                        <i class="flaticon-more-1 kt-font-brand"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="kt-nav">
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                    <span class="kt-nav__link-text">Reports</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-send"></i>
                                                    <span class="kt-nav__link-text">Messages</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                    <span class="kt-nav__link-text">Charts</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                    <span class="kt-nav__link-text">Members</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                    <span class="kt-nav__link-text">Settings</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div> -->
                            </div>
                            <div class="kt-portlet__body">

                                <!--begin::Widget -->
                                <div class="kt-widget kt-widget--user-profile-2">
                                    <div class="kt-widget__head">
                                        <div class="kt-widget__media">
                                            <img style="height:50px;border-radius:3px;" src="{{asset('img/bank-icons/bdo.jpg')}}"/>
                                            <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest  kt-hidden">
                                                MP
                                            </div>
                                        </div>
                                        <div class="kt-widget__info">
                                            <a href="#" class="kt-widget__username">
                                                Banco De Oro (BDO)
                                            </a>
                                            <span class="kt-widget__desc">
                                                Account Details
                                            </span>
                                        </div>
                                    </div>
                                    <div class="kt-widget__body">
                                        <div class="kt-widget__section">
                                            
                                        </div>
                                        <div class="kt-widget__item">
                                            <div class="kt-widget__contact">
                                                <span class="kt-widget__label">Account Name:</span>
                                                <i  class="kt-widget__data">{{ $account_info->bdo_acc_name ?? 'no data yet' }}</i>
                                                <input type="hidden" id="bdo_acc_name" value="{{ $account_info->bdo_acc_name ?? 'no data yet' }}">
                                            </div>
                                            <div class="kt-widget__contact">
                                                <span class="kt-widget__label">Account Number:</span>
                                                <i  class="kt-widget__data">{{ $account_info->bdo_acc_number ?? 'no data yet' }}</i>
                                                <input type="hidden" id="bdo_acc_number" value="{{ $account_info->bdo_acc_number ?? 'no data yet' }}">
                                            </div>
                                            <div class="kt-widget__contact">
                                                <span class="kt-widget__label">Contact Number:</span>
                                                <i  class="kt-widget__data">{{ $account_info->bdo_contact ?? 'no data yet' }}</i>
                                                <input type="hidden" id="bdo_contact" value="{{ $account_info->bdo_contact ?? 'no data yet' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget__footer">
                                        <button type="button" class="btn btn-label-primary btn-lg btn-upper" onclick="updateBank('bdo')">UPDATE</button>
                                    </div>
                                </div>

                                <!--end::Widget -->
                            </div>
                        </div>

                        <!--End::Portlet-->
                    </div>
                    <div class="col-md-6 col-xl-4">

                        <!--Begin::Portlet-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__head kt-portlet__head--noborder">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">
                                    </h3>
                                </div>
                                <!-- <div class="kt-portlet__head-toolbar">
                                    <a href="#" class="btn btn-icon" data-toggle="dropdown">
                                        <i class="flaticon-more-1 kt-font-brand"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="kt-nav">
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                    <span class="kt-nav__link-text">Reports</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-send"></i>
                                                    <span class="kt-nav__link-text">Messages</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                    <span class="kt-nav__link-text">Charts</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                    <span class="kt-nav__link-text">Members</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                    <span class="kt-nav__link-text">Settings</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div> -->
                            </div>
                            <div class="kt-portlet__body">

                                <!--begin::Widget -->
                                <div class="kt-widget kt-widget--user-profile-2">
                                    <div class="kt-widget__head">
                                        <div class="kt-widget__media">
                                            <img style="height:50px;border-radius:3px;" src="{{asset('img/system/gcash.png')}}"/>
                                            <div class="kt-widget__pic kt-widget__pic--brand kt-font-brand kt-font-boldest kt-hidden">
                                                JM
                                            </div>
                                        </div>
                                        <div class="kt-widget__info">
                                            <a href="#" class="kt-widget__username">
                                                Gcash
                                            </a>
                                            <span class="kt-widget__desc">
                                                Account Details
                                            </span>
                                        </div>
                                    </div>
                                    <div class="kt-widget__body">
                                        <div class="kt-widget__section">
                                        </div>
                                        <div class="kt-widget__item">
                                            <div class="kt-widget__contact">
                                                <span class="kt-widget__label">Account Name:</span>
                                                <i class="kt-widget__data">{{ $account_info->gcash_acc_name ?? 'no data yet' }}</i>
                                                <input type="hidden" id="gcash_acc_name" value="{{ $account_info->gcash_acc_name ?? 'no data yet' }}">
                                            </div>
                                            <div class="kt-widget__contact">
                                                <span class="kt-widget__label">Contact Number:</span>
                                                <i class="kt-widget__data">{{ $account_info->gcash_contact ?? 'no data yet' }}</i>
                                                <input type="hidden" id="gcash_contact" value="{{ $account_info->gcash_contact ?? 'no data yet' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget__footer">
                                        <button type="button" class="btn btn-label-primary btn-lg btn-upper" onclick="updateBank('gcash')">UPDATE</button>
                                    </div>
                                </div>
                                <!--end::Widget -->
                            </div>
                        </div>
                        <!--End::Portlet-->
                    </div>
                </div>
                <!--End::Section-->
            </div>
        </div>
    </div>
</div>

<!-- begin:Modal -->
@include('template.modal.delete')
@include('template.modal.pdf')

<!--begin::Modal-->
<div class="modal fade" id="updateBankAccount">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Bank Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <h5><p id="name_of_bank"></p></h5>
                <div>
                    <div class="form-group">
                        <label><b>Account Name</b></label>
                        <input type="text" class="form-control" id="account_name" placeholder="Account Name">
                        <span class="form-text text-muted">Account Name used on this account</span>
                    </div>
                    <div class="form-group" id="acc_number">
                        <label><b>Account Number</b></label>
                        <input type="number" class="form-control" id="account_number" placeholder="Account Number">
                        <span class="form-text text-muted">We'll never share your account number with anyone else.</span>
                    </div>
                    <div class="form-group">
                        <label><b>Contact Number</b></label>
                        <input type="number" class="form-control" id="account_contact" placeholder="Contact Number">
                        <span class="form-text text-muted">Contact number used on this account</span>
                    </div>
                    <input type="hidden" value="" id="bank_code">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update" onclick="updateBank('update')">Update</button>
            </div>
        </div>
    </div>
</div>

<!--end::Modal-->


@endsection
@section("scripts")
<!-- <script src="{{asset('js/superadmin/announcement/announcement.js')}}" type="text/javascript"></script> -->
<script>

    jQuery(document).ready(function() {

        $('#updateBankAccount').click(function(e) {
            
            var balance = parseInt($('#current_balance').val());
            if( balance >= 5000){
                $('#paymentMethod').modal(); 
            }else{
                $('#validate').modal(); 
            }
        });

        
    });

    function updateBank(bank) {
        switch (bank){
            case 'bpi':
                document.getElementById("name_of_bank").innerHTML = "Bank of Philippine Island (BPI)";
                document.getElementById("bank_code").value = "bpib";
                $('#updateBankAccount').modal(); 
                break;
            case 'bdo':
                document.getElementById("name_of_bank").innerHTML = "Banco De Oro (BDO)";
                document.getElementById("bank_code").value = "bdo";
                $('#updateBankAccount').modal();
                break;
            case 'gcash':
                document.getElementById("name_of_bank").innerHTML = "Gcash";
                document.getElementById("bank_code").value = "gcash";
                document.getElementById("account_number").value = "0000000000000000";
                $('#acc_number').hide();
                $('#updateBankAccount').modal();
                break;
            case 'update':
                var type = $('#bank_code').val();
                var bpi_acc_name = $("#bpi_acc_name").val();
                var bpi_acc_number = $("#bpi_acc_number").val();
                var bpi_contact = $("#bpi_contact").val();

                var bdo_acc_name = $("#bdo_acc_name").val();
                var bdo_acc_number = $("#bdo_acc_number").val();
                var bdo_contact = $("#bdo_contact").val();

                var gcash_acc_name = $("#gcash_acc_name").val();
                var gcash_contact = $("#gcash_contact").val();

                
                if(type == "bpib"){
                    var bpi_acc_name = $("#account_name").val();
                    var bpi_acc_number = $("#account_number").val();
                    var bpi_contact = $("#account_contact").val();
                    
                }else if(type == "bdo"){
                    var bdo_acc_name = $("#account_name").val();
                    var bdo_acc_number = $("#account_number").val();
                    var bdo_contact = $("#account_contact").val();
                    
                }else if(type == "gcash"){

                    var gcash_acc_name = $("#account_name").val();
                    var gcash_contact = $("#account_contact").val();
                    console.log($("#account_name").val() + " - "+$("#account_number").val()+" - "+$("#account_contact").val())
                }
                if( $("#account_name").val() != "" && $("#account_number").val() != "" && $("#account_contact").val() != "" ){
                    $.ajax({
                        url: '/api/update_payout_setting',
                        data: {
                            bpi_acc_name: bpi_acc_name,
                            bpi_acc_number: bpi_acc_number,
                            bpi_contact: bpi_contact,

                            bdo_acc_name: bdo_acc_name,
                            bdo_acc_number: bdo_acc_number,
                            bdo_contact: bdo_contact,

                            gcash_acc_name: gcash_acc_name,
                            gcash_contact: gcash_contact,
                        },
                        success: function(response){
                            toastr.success('Success!', response.message);
                            setTimeout(() => {
                                window.location = "/payout_setting";
                            }, 1000);
                        }
                    });
                }else{
                    toastr.error('Error!', 'Please Complete the Form if you wish to continue');
                }
                
                break;
        }
       
    }
</script>
@endsection
