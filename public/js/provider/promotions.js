jQuery(document).ready(function() {
    $(`input[name="joining"]`).change(function(){
        $.ajax({
            method: 'POST',
            url: '/provider/promotion/join',
            data: {
                join: $(this).is(":checked") ? 1 : 0 , 
                _token:  $('input[name="_token"]').val(),
            },
            success: function(){
                toastr.success('Updated marketing program!');
            },
            error: function(response){
                $("#submit_promotion").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');

                var body = response.responseJSON;
                if(body.hasOwnProperty("message")){

                    toastr.error('Error!', body.message);
                    return;
                }

            toastr.error('Error!', 'Something went wrong! Please try again later.');
            }
        });

        if($(this).is(":checked")){
            var $accordion = $(`#join-accordion`);
            $accordion.find(`.card-title`).addClass("collapsed").attr("aria-expanded", false);
            $accordion.find(`.collapse`).removeClass("show");
        }
    });

    $("#filter_voucher_code").click(function(){
        if($("#filter_voucher_code").is(":checked")){
            $("#voucher_code_append").slideDown(200);
        }else{
            $("#voucher_code_append").slideUp(200);
        }
    });

    $("#filter_discount").click(function(){
        if($("#filter_discount").is(":checked")){
            $("#discount_append").slideDown(200);
        }else{
            $("#discount_append").slideUp(200);
        }
    });

    $("#filter_start_date").click(function(){
        if($("#filter_start_date").is(":checked")){
            $("#start_date_append").slideDown(200);
        }else{
            $("#start_date_append").slideUp(200);
        }
    });

    $("#filter_end_date").click(function(){
        if($("#filter_end_date").is(":checked")){
            $("#end_date_append").slideDown(200);
        }else{
            $("#end_date_append").slideUp(200);
        }
    });

    $("#filter_created_by").click(function(){
        if($("#filter_created_by").is(":checked")){
            $("#created_by_append").slideDown(200);
        }else{
            $("#created_by_append").slideUp(200);
        }
    });

    $("#filter_status").click(function(){
        if($("#filter_status").is(":checked")){
            $("#status_append").slideDown(200);
        }else{
            $("#status_append").slideUp(200);
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
                        url: '/provider/promotions/api/list',
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
                    // autoHide: false,
                    template: function(row) {
                        return `
                            <div class="dropdown">
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">
                                    <i class="flaticon2-gear"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left">
                                    <a class="dropdown-item" onclick='viewCourses(${row.id})'>
                                        <i class="la la-eye"></i> 
                                        View Voucher Details
                                    </a>
                                    <a class="dropdown-item" onclick="window.location='/provider/promotions/form?voucher_id=${row.id}'">
                                        <i class="la la-edit"></i> 
                                        Edit
                                    </a>
                                    <a class="dropdown-item" onclick="deleteLink(event);" data-toggle="modal" data-target="#delete_modal" data-resource-name="${row.voucher_code}" data-route="/provider/promotions/delete/${row.id}">
                                        <i class="la la-trash"></i> 
                                        Delete
                                    </a>
                                </div>
                            </div>
                        `;
                    },
                }, {
                    field: 'voucher_code',
                    title: 'Voucher',
                }, {
                    field: 'discount',
                    title: 'Discount(%)',
                }, {
                    field: 'session_start',
                    title: 'Start Date',
                    width: 150,
                }, {
                    field: 'session_end',
                    title: 'End Date',
                    width: 150,
                }, {
                    field: 'created_by',
                    title: 'Created By',
                    width: 150,
                }, {
                    field: 'status',
                    title: 'Status',
                    width: 85,
                    textAlign: 'center',
                    template: function(row) {
                        var status = {
                            "upcoming": {'class': 'warning'},
                            "ended": {'class': 'dark'},
                            "active": {'class': 'success'},
                            "in-review": {'class': 'info'},
                            "rejected": {'class': 'danger'},
                        };
                        return `<span class="kt-badge kt-badge--${status[row.status].class} kt-badge--dot"></span>&nbsp;<span class="kt-font-bold kt-font-${status[row.status].class}">${row.status.toUpperCase()}</span>`;
                    },
                }],
        };

        var datatable = $('#promotion_datatable').KTDatatable({...dataSource, ...dataStructure});
        
        datatable.columns('actions').visible(false);
        $("#kt_display").click(function(){
            if($("#check_voucher_code").is(':checked')){
                datatable.columns('voucher_code').visible(false);
            }else{
                datatable.columns('voucher_code').visible(true);
            }
            
            if($("#check_discount").is(':checked')){
                datatable.columns('discount').visible(false);
            }else{
                datatable.columns('discount').visible(true);
            }

            if($("#check_start_date").is(':checked')){
                datatable.columns('session_start').visible(false);
            }else{
                datatable.columns('session_start').visible(true);
            }

            if($("#check_end_date").is(':checked')){
                datatable.columns('session_end').visible(false);
            }else{
                datatable.columns('session_end').visible(true);
            }

            if($("#check_created_by").is(':checked')){
                datatable.columns('created_by').visible(false);
            }else{
                datatable.columns('created_by').visible(true);
            }

            if($("#check_status").is(':checked')){
                datatable.columns('status').visible(false);
            }else{
                datatable.columns('status').visible(true);
            }
            datatable.reload();
            datatable.reload();
        });

        $("#kt_filter").click(function(){
            var dataApi = {
                voucher_code: {
                    filter: $("#filter_voucher_code").is(':checked') ? $("#voucher_code_append select").val() : "=",
                    values: $("#filter_voucher_code").is(':checked') ? ($("#voucher_code_append input").val() ? $("#voucher_code_append input").val().split(",") : null) : null,
                },
                discount: {
                    filter: $("#filter_discount").is(':checked') ? $("#discount_append select").val() : "=",
                    values: $("#filter_discount").is(':checked') ? ($("#discount_append input").val() ? $("#discount_append input").val().split(",") : null) : null,
                },
                start_date: {
                    filter: $("#filter_start_date").is(':checked') ? $("#start_date_append select").val() : "=",
                    values: $("#filter_start_date").is(':checked') ? ($("#start_date_append input").val() ? $("#start_date_append input").val().split(",") : null) : null,
                },
                end_date: {
                    filter: $("#filter_end_date").is(':checked') ? $("#end_date_append select").val() : "=",
                    values: $("#filter_end_date").is(':checked') ? ($("#end_date_append input").val() ? $("#end_date_append input").val().split(",") : null) : null,
                },
                created_by: {
                    filter: $("#filter_created_by").is(':checked') ? $("#created_by_append select").val() : "=",
                    values: $("#filter_created_by").is(':checked') ? ($("#created_by_append input").val() ? $("#created_by_append input").val().split(",") : null) : null,
                },
                status: {
                    filter: $("#filter_status").is(':checked') ? $("#status_append select").val() : null,
                },
            };
        
            setTimeout(() => {
                $.ajax({
                    url: '/provider/promotions/api/session',
                    data: dataApi,
                    success: function(response){
                        showFilterBox(dataApi, datatable, "users");
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

function viewCourses($voucher_id){
    var $course_modal = $(`#view-course-modal`);
    $.ajax({
        url: "/api/voucher/courses",
        data: {
            voucher_id: $voucher_id,
        }, success: function(response){
            var $view_course_modal = $(`#view-course-body`);
            $view_course_modal.empty();
            var data = response.data;

            if(data.length>0){
                $view_course_modal.append(`
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Course</th>
                                <th>Discount</th>
                                <th>Original Price</th>
                                <th>Discounted Price</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                `);
    
                data.forEach((d, i) => {
                    $view_course_modal.find(`tbody`).append(`
                    <tr>
                        <th scope="row">${(i+1)}</th>
                        <td>${d.title}</td>
                        <td>${d.discount_percentage}%</td>
                        <td>${d.price}</td>
                        <td>${d.discount_price}</td>
                    </tr>
                    `);
                });
            }else{
                $view_course_modal.append(`No courses found!`);
            }
        }, error: function(){
            toastr.error("Error!", "Something went wrong! Please refresh your browser");
        }
    });


    $course_modal.modal("show");
}