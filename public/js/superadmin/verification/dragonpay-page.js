jQuery(document).ready(function() {
    $("#filter_reference_number").click(function(){
        if($("#filter_reference_number").is(":checked")){
            $("#reference_number_append").slideDown(200);
        }else{
            $("#reference_number_append").slideUp(200);
        }
    });


    $("#filter_customer").click(function(){
        if($("#filter_customer").is(":checked")){
            $("#customer_append").slideDown(200);
        }else{
            $("#customer_append").slideUp(200);
        }
    });

    $("#filter_email").click(function(){
        if($("#filter_email").is(":checked")){
            $("#email_append").slideDown(200);
        }else{
            $("#email_append").slideUp(200);
        }
    });

    $("#filter_amount").click(function(){
        if($("#filter_amount").is(":checked")){
            $("#amount_append").slideDown(200);
        }else{
            $("#amount_append").slideUp(200);
        }
    });

    $("#filter_payment_status").click(function(){
        if($("#filter_payment_status").is(":checked")){
            $("#payment_status_append").slideDown(200);
        }else{
            $("#payment_status_append").slideUp(200);
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
    
    KTDatatableRemote.init();
});

var KTDatatableRemote = function() {
    var recordTable = function() {

        var dataSource = {
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/superadmin/verification/dragonpay/api/list',
                        map: function(raw) {
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                        method: "get",
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
            },
        };

        var dataStructure = {
            extensions:{
                checkbox: {},
            },

            layout: {
                scroll: false,
                footer: false,
            },

            sortable: true,
            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            columns: [
                {
                    field: 'id',
                    title: '',
                    sortable: false,
                    width: 30,
                    overflow: 'visible',
                    template: function(row) {

                        if(jQuery.inArray(row.payment_status, ["waiting", "pending"]) >= 0){
                            return `
                            <div class="dropdown">
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">
                                    <i class="flaticon2-gear"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left" style="padding:15px 20px;">
                                    <button class="dropdown-item btn btn-sm btn-secondary" onclick='viewPurchase(${row.id})'><i class="fa fa-eye"></i> &nbsp; View Purchase</button>
                                    <div class="kt-space-10"></div>
                                    <button class="dropdown-item btn btn-sm btn-success" onclick='setPaymentStatus(${row.id}, "paid")'><i class="fa fa-check kt-font-info"></i> &nbsp; Confirm Payment</button>
                                    <div class="kt-space-10"></div>
                                    <button class="dropdown-item btn btn-sm btn-danger" onclick='setPaymentStatus(${row.id}, "failed")'><i class="fa fa-times kt-font-warning"></i> &nbsp; Failed Transaction</button>
                                </div>
                            </div>
                            `;
                        }

                        return `<button class="btn btn-icon btn-sm btn-secondary" onclick='viewPurchase(${row.id})'><i class="fa fa-eye"></i></button>`;
                    },
                }, {
                    field: 'reference_number',
                    title: 'Reference#',
                }, {
                    field: 'customer',
                    title: 'Customer',
                }, {
                    field: 'email',
                    title: 'Email',
                }, {
                    field: 'amount',
                    title: 'Amount',
                    template: function(row) {
                        return `&#8369;`+row.amount.toLocaleString(undefined, {minimumFractionDigits:2,maximumFractionDigits:2});
                    }
                }, {
                    field: 'payment_status',
                    title: 'Payment Status',
                    template: function(row) {
                        var status = {
                            "refund": {'class': 'warning'},
                            "waiting": {'class': 'dark'},
                            "paid": {'class': 'success'},
                            "pending": {'class': 'info'},
                            "failed": {'class': 'danger'},
                            "cancelled": {'class': 'danger'},
                        };
                        return `<span class="kt-badge kt-badge--${status[row.payment_status].class} kt-badge--dot"></span>&nbsp;<span class="kt-font-bold kt-font-${status[row.payment_status].class}">${row.payment_status.toUpperCase()}</span>`;
                    }
                }],
        };

        var datatable = $('#datatable').KTDatatable({...dataSource, ...dataStructure});
        
        datatable.columns('actions').visible(false);
        $("#kt_display").click(function(){
            
            if($("#check_reference_number").is(':checked')){
                datatable.columns('reference_number').visible(false);
            }else{
                datatable.columns('reference_number').visible(true);
            }

            if($("#check_customer").is(':checked')){
                datatable.columns('customer').visible(false);
            }else{
                datatable.columns('customer').visible(true);
            }

            if($("#check_email").is(':checked')){
                datatable.columns('email').visible(false);
            }else{
                datatable.columns('email').visible(true);
            }

            if($("#check_email").is(':checked')){
                datatable.columns('email').visible(false);
            }else{
                datatable.columns('email').visible(true);
            }

            if($("#check_amount").is(':checked')){
                datatable.columns('amount').visible(false);
            }else{
                datatable.columns('amount').visible(true);
            }

            if($("#check_payment_status").is(':checked')){
                datatable.columns('payment_status').visible(false);
            }else{
                datatable.columns('payment_status').visible(true);
            }
            
            datatable.reload();
            datatable.reload();
        });

        $("#kt_filter").click(function(){
            var dataApi = {
                reference_number: {
                    filter: $("#filter_reference_number").is(':checked') ? $("#reference_number_append select").val() : "=",
                    values: $("#filter_reference_number").is(':checked') ? ($("#reference_number_append input").val() ? $("#reference_number_append input").val().split(",") : null) : null,
                },
                customer: {
                    filter: $("#filter_customer").is(':checked') ? $("#customer_append select").val() : "=",
                    values: $("#filter_customer").is(':checked') ? ($("#customer_append input").val() ? $("#customer_append input").val().split(",") : null) : null,
                },
                email: {
                    filter: $("#filter_email").is(':checked') ? $("#email_append select").val() : "=",
                    values: $("#filter_email").is(':checked') ? ($("#email_append input").val() ? $("#email_append input").val().split(",") : null) : null,
                },
                amount: {
                    filter: $("#filter_amount").is(':checked') ? $("#amount_append select").val() : "=",
                    values: $("#filter_amount").is(':checked') ? ($("#amount_append input").val() ? $("#amount_append input").val().split(",") : null) : null,
                },
                payment_status: {
                    filter: $("#filter_payment_status").is(':checked') ? $("#payment_status_append select").val() : null,
                },
            };
        
            setTimeout(() => {
                $.ajax({
                    url: '/superadmin/verification/dragonpay/api/session',
                    data: dataApi,
                    success: function(response){
                        showFilterBox(dataApi, datatable, "dragonpay");
                        datatable.reload();
                    }
                });
            }, 500);
        });

    };

    return {
        // public functions
        init: function() {
            recordTable();
        },
    };
}();

function viewPurchase($purchase_id){
    var $purchase_modal = $(`#view-purchase-modal`);
    $.ajax({
        url: "/superadmin/verification/dragonpay/api/purchase/record",
        data: {
            purchase_id: $purchase_id,
        }, success: function(response){
            var $view_purchase_modal = $(`#view-purchase-body`);
            $view_purchase_modal.empty();
            var data = response.data;

            if(data){
                $view_purchase_modal.append(`
                    <table class="table table-striped" style="font-size:1rem;">
                        <thead class="thead-light">
                            <tr>
                                <th>Reference#</th>
                                <th>Total Discount</th>
                                <th>Processing Fee</th>
                                <th>Total Amount</th>
                                <th>P.Gateway</th>
                                <th>P.Method</th>
                                <th>Payment At</th>
                                <th>P.Status</th>
                                <th>P.Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${data.reference_number}</td>
                                <td>${data.total_discount ? data.total_discount.toLocaleString(undefined,{maximumFractionDigits:2}) : 0}%</td>
                                <td>&#8369;${data.processing_fee ? data.processing_fee.toLocaleString(undefined,{maximumFractionDigits:2}) : 0}</td>
                                <td>&#8369;${data.total_amount ? data.total_amount.toLocaleString(undefined,{maximumFractionDigits:2}) : 0}</td>
                                <td>${data.payment_gateway.toUpperCase()}</td>
                                <td>${data.payment_method.toUpperCase()}</td>
                                <td>${data.payment_at}</td>
                                <td>${data.payment_status.toUpperCase()}</td>
                                <td style="width:10%">${data.payment_notes ? data.payment_notes.toUpperCase() : ""}</td>
                            </tr>
                        </tbody>
                        <thead class="thead-light">
                            <tr>
                                <th style="width:2%">#</th>
                                <th colspan="2">Item</th>
                                <th colspan="2">Voucher</th>
                                <th>Discount</th>
                                <th colspan="2">Price</th>
                                <th>Channel</th>
                                <th>P.Status</th>
                            </tr>
                        </thead>
                    </table>
                `);

                data.items.forEach((d, i) => {
                    $view_purchase_modal.find("table").append(`
                        <tbody>
                            <tr>
                                <td>${i+1}.</td>
                                <td colspan="2">${d.title}</td>
                                <td colspan="2">${d.voucher ? d.voucher : "---"}</td>
                                <td>${d.discount ? d.discount.toLocaleString(undefined,{maximumFractionDigits:2}) : 0}%</td>
                                <td colspan="2">&#8369;${d.price ? d.price.toLocaleString(undefined,{maximumFractionDigits:2}) : 0}</td>
                                <td>${d.channel.toUpperCase()}</td>
                                <td>${d.payment_status.toUpperCase()}</td>
                            </tr>
                        </tbody> 
                    `);
                });

            }else{
                $view_purchase_modal.append(`No purchase record found!`);
            }
        }, error: function(){
            toastr.error("Error!", "Something went wrong! Please refresh your browser");
        }
    });

    $purchase_modal.modal("show");
}

function setPaymentStatus($purchase_id, $payment_status){
    toastr.info(`Please wait until we process the ${$payment_status} payment status!`);
    setTimeout(() => {
        $.ajax({
            url: "/superadmin/verification/dragonpay/change",
            method: "POST",
            data: {
                _token: $("[name='_token']").val(),
                purchase_id: $purchase_id,
                payment_status: $payment_status,
            }, success: function(){
                toastr.success("Feedback has been updated!");
                toastr.info("Please wait, we're reloading your page");
                setTimeout(() => {
                    location.reload();
                }, 2500);
            }, error: function(response){
                var body = response.responseJSON;
                if(body.hasOwnProperty("message")){
                    toastr.error("Error!", body.message);
                }else{
                    toastr.error("Error!", "Unable to update feedback to payment! Please refresh your browser");
                }
            }
        });
    }, 1500);
}