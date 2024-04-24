@extends('template.master_course_creation')

@section('styles')
@endsection

@section('content')
<form class="kt-form kt-form--label-left" id="form">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Instructors
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            Select Form Your List of Instructors. They will have access and will be listed as an instructor once the page is published. <br />
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Instructor</label>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <select class="form-control kt-select2" id="instructor" name="instructor" multiple="multiple" <?= _current_course() ?? "disabled='disabled'" ?>>
                                <!-- <option selected value="all">All Instructors</option> -->
                                
                                @if(count(_get_instructors(_current_provider()->id)) > 0)
                                    @foreach ( _get_instructors(_current_provider()->id) as $instructor)
                                    @if($instructor->status=="active") 
                                    <option value="{{$instructor->id}}" {{ $course_instructor_ids ? (in_array($instructor->id, $course_instructor_ids) ? "selected='selected'" : "") : "" }} ><?= $instructor->name; ?></option>
                                    @endif
                                    @endforeach
                                @else
                                <option disabled selected>No Instructors Found</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__body">
                    <span><i class="fa fa-user-check" style="color:#2A7DE9;"></i> All visible instructors of this course must complete their profile before the course can be published. This includes name,
                        image, and a short summary of your background 50 words minimum. </span>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <!-- begin:Feature action buttons -->
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Instructor/s List
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">

                                <li class="nav-item" data-toggle="kt-tooltip" data-placement="top" title="Print">
                                    <button type="button" class="btn btn-secondary btn-icon" id="print" onclick="printPDF({name:'Users',route:'data/pdf/users', method:'get'});" data-toggle="modal" data-target="#print_pdf_modal"><i class="fa fa-print"></i></button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="btn kt-label-bg-color-1 btn-icon" id="export" onclick="window.open('data/csv/users', '_blank');" data-toggle="kt-tooltip" data-placement="top" title="Export"><i class="fa fa-file-csv"></i></button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="btn kt-label-bg-color-2 btn-icon" id="import" onclick="window.location.href='data/import?rc=users'" data-toggle="kt-tooltip" data-placement="top" title="Import"><i class="fa fa-file-import"></i></button>&nbsp;&nbsp; &nbsp;&nbsp;
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="btn btn-info btn-icon" data-toggle="modal" data-target="#kt_filter_modal"><i class="fa fa-search"></i></button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="btn btn-secondary btn-icon" data-toggle="modal" data-target="#kt_hideshow_modal"><i class="fa fa-eye-slash"></i></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end:Feature action buttons -->

                    <!-- begin:Select All or Select Row Action buttons -->
                    <div class="kt-form kt-form--label-align-right kt-margin-t-20 collapse" id="kt_datatable_actions" style="margin-left:50px;">
                        <div class="row align-items-center">
                            <div class="col-xl-12">
                                <div class="kt-form__group kt-form__group--inline">
                                    <div class="kt-form__label kt-form__label-no-wrap">
                                        <label class="kt-font-bold kt-font-danger-">Selected
                                            <span id="kt_datatable_selected_ids">0</span> records:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <div class="btn-toolbar">
                                            <button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#kt_modal_fetch_id_server">Fetch Selected Records</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row kt-margin-t-20 kt-margin-l-20 kt-margin-r-20" id="filter_row"></div>

                    <!-- end:Select All or Select Row Action buttons -->
                    <div class="kt-portlet__body">
                        <div class="kt-portlet__body kt-portlet__body--fit">
                            <!--begin: Datatable -->
                            <div class="kt-datatable kt-datatable--default" id="ajax_data"></div>
                            <!--end: Datatable -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="kt-portlet__foot">
            <div class="row" style="float:right">
                <div class="col-lg-12 ml-lg-xl-auto">
                    <button id="submit_form" class="btn btn-outline-success">Update Permission</button>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <button type="button" id="reset" class="btn btn-secondary">Clear</button>
                </div>
            </div>
        </div> -->
    </div>
</form>
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
                <p style="text-align:center;">Are you sure you want to remove <b id="removing_user"></b> from the list? </p>
                <input type="hidden" id="selected_ins">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancel-removal-modal-submit">Cancel</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="removal-modal-submit">Yes, I'm sure.</button>
            </div>
        </div>
    </div>
</div>
<input type="text" id="aut_role" value="<?= _get_auth_role() ?>" style="display:none;">
@endsection

@section('scripts')
<script>
    var myRecordID = <?=Auth::user()->id ?? 0?>;
    $('#instructor').on('select2:unselect', function (e) {
    // Do something
        $("#removal-modal").modal()
        $('#instructor').select2('open');

        $("#removing_user").html(e.params.data.text);
        $("#selected_ins").val(e.params.data.id);
    });

    $(`#removal-modal-submit`).click(function (event) {
        var instant = {
            instructor: {
                values: $("#instructor").val(),
            }
        }
        var datatable = $('#ajax_data').KTDatatable();
        var user_id = <?= Auth::user()->id ?>;
        var auth_role = $("#aut_role").val();
            
        setTimeout(() => {
            $.ajax({
                url: '/course_management/api/instructors_filter',
                data: {
                    instructor: $("#instructor").val(),
                },
                success: function(response) {
                    if(response.status == 200){
                        if(($("#selected_ins").val() == user_id) && auth_role == "instructor"){
                            window.location=`/provider/courses`;
                        }else{
                            datatable.reload();
                        }
                    }else{
                        var existing = $("#instructor").val();
                        var id = $("#selected_ins").val();

                        existing.push(id);
                        $('#instructor').val(existing).trigger("change");
                        toastr.error("Error!",response.hasOwnProperty("message") ? response.message : "Something went wrong! Please refresh your browser");
                    }
                    
                }
            });
        }, 500);

        var sample = $('#ajax_data').KTDatatable().rows().data();
        $.each($(sample), function(key, value) {
        });
    });

    $(`#cancel-removal-modal-submit`).click(function (event) {
        var existing = $("#instructor").val();
        var id = $("#selected_ins").val();

        existing.push(id);
        $('#instructor').val(existing).trigger("change");

    });

    $('#instructor').on('select2:select', function (e) {
        $(this).each(function(){
            if($(this).val() != "all"){
                // $(this).attr('disabled', true);   
            }
        });
        var datatable = $('#ajax_data').KTDatatable();
        var instant = {
            instructor: {
                values: $("#instructor").val(),
            }
        }
        
        setTimeout(() => {
            $.ajax({
                url: '/course_management/api/instructors_filter',
                data: {
                    instructor: $("#instructor").val(),
                },
                success: function(response) {
                    datatable.reload();
                }
            });
        }, 500);

        var sample = $('#ajax_data').KTDatatable().rows().data();
        $.each($(sample), function(key, value) {
        });
    });
    var KTDatatableRemote = function() {
        var userTable = function() {
            var dataSource = {
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '/course_management/api/instructors',
                            // sample custom headers
                            // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                            map: function(raw) {
                                // sample data mapping
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
                extensions: {
                    // selector
                    checkbox: {},
                },

                // layout definition
                layout: {
                    scroll: false,
                    footer: false,
                },

                // column sorting
                sortable: true,

                pagination: true,

                search: {
                    input: $('#generalSearch'),
                },

                // columns definition
                columns: [
                    {
                        field: 'course_id',
                        title: '#',
                        sortable: 'asc',
                        width: 20,
                        type: 'number',
                        selector: {class: 'kt-checkbox--solid'},
                        sortable: false,
                        visible: false,
                        textAlign: 'center',
                    },    
                {
                    field: 'name',
                    title: 'Instructor',
                    sortable: 'asc',
                    // visible: false,
                    textAlign: 'center',
                    width: 150,
                }, {
                    field: 'status',
                    title: 'Status',
                    textAlign: "center",
                    template: function(row) {
                        if (row.status) {

                            if (row.status == "active" || row.status == "published") {
                                color = "#03c03c";
                                colortext = "white";
                                status = "Published";
                            } else if (row.status == "inactive" || row.status == "incomplete" || row.status == "draft") {
                                color = "#FF6961";
                                colortext = "white";
                                status = "Incomplete";
                            } else if (row.status == "In-Review" || row.status == "in-review" || row.status == "pending") {
                                color = "yellow";
                                colortext = "black";
                                status="In Review";
                            } else {
                                color = "grey";
                                colortext = "white";
                                status="Unidentified";
                            }

                            return "<span style='background-color:" + color + ";color:" + colortext + ";border-radius:2px;'> &nbsp; " + status + " &nbsp; </span>";
                        } else {
                            return "<span style='background-color:grey;color:white;border-radius:2px;'> &nbsp; Unidentified &nbsp; </span>";
                        }

                    }

                }, {
                    field: 'course_details',
                    title: 'Course Details',
                    template: function(row) {
                        var checked = "";
                        if (row.course_details === 1) {
                            checked = "checked";
                        }
                        return '\
                            <div class="kt-checkbox-list">\
                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">\
                                    <input '+(row.user_id==myRecordID ? "disabled" : "")+' type="checkbox" ' + checked + ' name="course_details_check" data-id="'+row.user_id+'" data-column="course_details" id="course_details_'+row.id+'" class ="permission" '+row.co_provider+' /> <b style="color:white;">.</b> \
                                    <span></span>\
                                </label>\
                            </div>\
                            ';
                    },
                }, {
                    field: 'attract_enrollments',
                    title: 'Attract Enrollments',
                    template: function(row) {
                        var checked = "";
                        if (row.attract_enrollments === 1) {
                            checked = "checked";
                        }
                        return '\
                            <div class="kt-checkbox-list">\
                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">\
                                    <input '+(row.user_id==myRecordID ? "disabled" : "")+' type="checkbox" ' + checked + ' name="attract_enrollments_check" data-id="'+row.user_id+'" data-column="attract_enrollments" id="attract_enrollments"  class ="permission" '+row.co_provider+'/> <b style="color:white;">.</b> \
                                    <span></span>\
                                </label>\
                            </div>\
                            ';
                    },
                }, {
                    field: 'instructor_permission',
                    title: 'Instructors',
                    template: function(row) {
                        var checked = "";
                        if (row.instructor_permission === 1) {
                            checked = "checked";
                        }
                        return '\
                            <div class="kt-checkbox-list">\
                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">\
                                    <input '+(row.user_id==myRecordID ? "disabled" : "")+' type="checkbox" ' + checked + '  name="instructors_check" data-id="'+row.user_id+'" data-column="instructors" id="instructors" class ="permission" '+row.co_provider+'/> <b style="color:white;">.</b> \
                                    <span></span>\
                                </label>\
                            </div>\
                            ';
                    },
                }, {
                    field: 'video_and_content',
                    title: 'Video & Content',
                    template: function(row) {
                        var checked = "";
                        if (row.video_and_content === 1) {
                            checked = "checked";
                        }
                        return '\
                            <div class="kt-checkbox-list">\
                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">\
                                    <input '+(row.user_id==myRecordID ? "disabled" : "")+' type="checkbox" ' + checked + ' name="video_and_content_check" data-id="'+row.user_id+'" data-column="video_and_content" id="video_and_content" class ="permission" '+row.co_provider+' /> <b style="color:white;">.</b> \
                                    <span></span>\
                                </label>\
                            </div>\
                            ';
                    },
                }, {
                    field: 'handouts',
                    title: 'Handouts',
                    template: function(row) {
                        var checked = "";
                        if (row.handouts === 1) {
                            checked = "checked";
                        }
                        return '\
                            <div class="kt-checkbox-list">\
                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">\
                                    <input '+(row.user_id==myRecordID ? "disabled" : "")+' type="checkbox" ' + checked + ' name="handouts_check" data-id="'+row.user_id+'" data-column="handouts" id="handouts" class ="permission" '+row.co_provider+'/> <b style="color:white;">.</b> \
                                    <span></span>\
                                </label>\
                            </div>\
                            ';
                    },

                }, {
                    field: 'grading_and_assessment',
                    title: 'Grading',
                    template: function(row) {
                        var checked = "";
                        if (row.grading_and_assessment === 1) {
                            checked = "checked";
                        }
                        return '\
                            <div class="kt-checkbox-list">\
                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">\
                                    <input '+(row.user_id==myRecordID ? "disabled" : "")+' type="checkbox" ' + checked + ' name="grading_check" data-id="'+row.user_id+'" data-column="grading_and_assessment" id="grading_and_assessment" class ="permission" '+row.co_provider+'/> <b style="color:white;">.</b> \
                                    <span></span>\
                                </label>\
                            </div>\
                            ';
                    },

                }, {
                    field: 'submit_for_accreditation',
                    title: 'Accreditation',
                    textAlign: "center",
                    template: function(row) {
                        var checked = "";
                        if (row.submit_for_accreditation === 1) {
                            checked = "checked";
                        }
                        return '\
                            <div class="kt-checkbox-list"  style="text-align:center;">\
                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success" style="text-align:center;">\
                                    <input '+(row.user_id==myRecordID ? "disabled" : "")+' type="checkbox" ' + checked + ' name="accreditation_check" data-id="'+row.user_id+'" data-column="submit_for_accreditation" id="submit_for_accreditation" class ="permission" '+row.co_provider+'/><b style="color:white;">.</b> \
                                    <span></span>\
                                </label>\
                            </div>\
                            ';
                    },


                }, {
                    field: 'price_and_publish',
                    title: 'Price & Publish',
                    template: function(row) {
                        var checked = "";
                        if (row.price_and_publish == 1) {
                            checked = "checked";
                        }
                        return '\
                            <div class="kt-checkbox-list" style="text-align:center;">\
                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success ">\
                                    <input '+(row.user_id==myRecordID ? "disabled" : "")+' type="checkbox" ' + checked + ' name="price_and_publish_check"  data-id="'+row.user_id+'" data-column="price_and_publish" class ="permission" '+row.co_provider+'/> <b style="color:white;">.</b>  \
                                    <span></span>\
                                </label>\
                            </div>\
                            ';
                    },

                    textAlign: 'center',

                }],
            };

            var datatable = $('#ajax_data').KTDatatable({
                ...dataSource,
                ...dataStructure

            });

            datatable.on('click', `input[class='permission']`,  function(){
                var selected = $(this);
                var id = selected.data("id");
                var column = selected.data("column");
                const cb = document.getElementById(column);
                var status = $(this).prop("checked");
                $.ajax({
                    url: '/course_management/instructors/update',
                    type: 'POST',
                    data: {
                        user_id: id,
                        column: column,
                        status: status,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // datatable.reload();
                    }
                });
            });

            $("select").on("change", function() {
                

            });

            $("#reset").on("click",function(){
                var sample = $('#ajax_data').KTDatatable().data();
                $.each($(sample), function(key, value) {
                });

            });
        };

        return {
            init: function() {
                userTable();
            },
        };
    }();
    
    var FormDesign = function() {
        var input_validations = function() {
            validator = $("#form").validate({
                invalidHandler: function(event, validator) {
                    var alert = $('#form_msg');
                    alert.removeClass('kt-hidden').show();
                    KTUtil.scrollTop();
                },

                submitHandler: function(form) {
                    $("#submit_form").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');

                    if($(`#instructor`).val()){
                        var submit_form = $.ajax({
                            url: '/course_management/instructors/action',
                            type: 'POST',
                            data: {
                                instructors: $("#instructor").val(),
                                _token: "{{ csrf_token() }}",
                            },
                            success: function(response) {
                                if (response.status == 200) {
                                    toastr.success('Success!', response.message);
                                    setTimeout(() => {
                                        window.location = "/course/management/instructors";
                                    }, 1000);
                                } else {
                                    $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                    toastr.error('Error!', response.message);
                                }
                            },
                            error: function(response) {
                                $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
                            }
                        });
                    }else{
                        $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                        toastr.error('Error!', 'Please choose atleast one instructor!');
                    }
                }
            });
        }

        return {
            // public functions
            init: function() {

                input_validations();
            }
        };
    }();

    jQuery(document).ready(function() {
        FormDesign.init();
        KTDatatableRemote.init();
        
        $("#instructor").select2({
            placeholder: "Select instructor",
            allowClear: false
        });
    });
   
    
</script>
@endsection