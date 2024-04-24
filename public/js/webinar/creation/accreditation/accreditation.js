// var evalutation_ = $("#evaluation_checkbox");

jQuery(document).ready(function () {
    Inputmask.extendAliases({
        pesos: {
            prefix: "₱ ",
            groupSeparator: ".",
            alias: "numeric",
            placeholder: "0",
            autoGroup: !0,
            digits: 2,
            digitsOptional: !1,
            clearMaskOnLostFocus: !1,
            autoUnmask: true,
            removeMaskOnSubmit: true,
        }
    });

    can_submit();
    
    $("#price").inputmask({
        alias: "pesos"
    });
    
    $(`select[name*="instructors"]`).each(function () {
        $(this).select2({
            placeholder: "Select instructors",
            allowClear: false
        });
    });

    
    $(`select[name*="objectives"]`).each(function () {
        $(this).select2({
            placeholder: "Select objectives",
            allowClear: false
        });
    });
    $('.select2-search__field').css('width', '90%');
    

    
    getTotal();
    FormDesign.init();
    InstructionalFormDesign.init();
    ExpensesFormDesign.init();
    var objectives = [];
    
    $(`select[name*="objectives"]`).each(function () {
        var extracted_value = $(this).val();
        if (extracted_value && extracted_value != "") {
            objectives.push(extracted_value);
        }
    });
    if(objectives.length){
        $("#print_app_form").show();
        $("#view_cpdas").show();
        $("#edit_inst").show();
        $("#submit_form").hide();
        $("#reset").hide();

        $(`select[name*="objectives"]`).prop('disabled', true);
        $(`select[name*="instructors"]`).prop('disabled', true);

        
    }else{
        $("#print_app_form").hide();
        $("#view_cpdas").hide();
        $("#submit_form").show();
        $("#reset").show();
        $("#edit_inst").hide();
    }

    $( "#print_app_form" ).click(function() {
        id = $("#webinar_id").val();
        window.open("/data/pdf/user/webinar_application_form/"+id);
    });
    $( "#view_cpdas" ).click(function() {
        id = $("#webinar_id").val();
        window.open("/webinar/management/cpdas/"+id);
    });
    $( "#edit_inst" ).click(function() {
        $("#print_app_form").hide();
        $("#view_cpdas").hide();
        $("#submit_form").show();
        // $("#reset").show();
        $("#edit_inst").hide();
        $(`select[name*="objectives"]`).prop('disabled', false);
        $(`select[name*="instructors"]`).prop('disabled', false);
    });
});

var FormDesign = function() {
    jQuery.validator.addMethod("lesserThan", function(value, element, param) {
        return (value < jQuery(param).val());
    }, "Please enter a valid percentage ( less than 100% )");


    var input_validations = function() {
        validator = $("#price_target_students_form").validate({
            // define validation rules

            rules: {
                price: {
                    required: true,
                    min:100,
                },
                number_students: {
                    required: true,
                    min:10,
                    max:150,
                },

            },

            //display error alert on form submit  
            invalidHandler: function(event, validator) {
                var alert = $('#form_msg');
                alert.removeClass('kt-hidden').show();
                KTUtil.scrollTop();
            },

            submitHandler: function(form) {
                // if($("#evaluation_checkbox").is(":checked")){ var eval_checkbox = 1; }else{  var eval_checkbox = 0; }
                
                var submit_button = $("#submit_form");
                submit_button.html(`Saving...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);
                $.ajax({
                    url: '/webinar/management/accreditation/store',
                    method: 'POST',
                    data: {
                        price: $("#price").val(),
                        target_number_students: $("#number_students").val(),
                        _token: $(`input[name="_token"]`).val(),
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            toastr.success('Success!', response.message);
                            setTimeout(() => {
                                window.location = "/webinar/management/accreditation";
                            }, 1000);
                        } else {
                            submit_button.html(`Submit for Accreditation`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                            toastr.error('Error!', response.message);
                        }
                    },
            });
               
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

var InstructionalFormDesign = function() {
    jQuery.validator.addMethod("lesserThan", function(value, element, param) {
        return (value < jQuery(param).val());
    }, "Please enter a valid percentage ( less than 100% )");

    var inst_input_validations = function() {
        validator = $("#instructional_form").validate({
            // define validation rules
            rules: {
                objectives: {
                    required: true,
                },
                instructors: {
                    required: true,
                }
            },

            //display error alert on form submit  
            invalidHandler: function(event, validator) {
                var alert = $('#form_msg');
                alert.removeClass('kt-hidden').show();
                KTUtil.scrollTop();
            },

            submitHandler: function(form) {
                
                var submit_button = $("#ins_submit");
                submit_button.html(`Saving...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);
                var objectives = [];
                $(`select[name*="objectives"]`).each(function () {
                    var extracted_value = $(this).val();
                    if (extracted_value && extracted_value != "") {
                        objectives.push(extracted_value);
                    }
                });

                var section_objective = [];
                $(`input[name*="section_objective"]`).each(function () {
                    var extracted_value = $(this).val();
                    if (extracted_value && extracted_value != "") {
                        section_objective.push(extracted_value);
                    }
                });

                var instructors = [];
                $(`select[name*="instructors"]`).each(function () {
                    var extracted_value = $(this).val();
                    if (extracted_value && extracted_value != "") {
                        instructors.push(extracted_value);
                    }
                });

                var video_length = [];
                $(`input[name*="video_length"]`).each(function () {
                    var extracted_value = $(this).val();
                    if (extracted_value && extracted_value != "") {
                        video_length.push(extracted_value);
                    }
                });
                var video_counter = [];
                $(`input[name*="video_counter"]`).each(function () {
                    var extracted_value = $(this).val();
                    if (extracted_value && extracted_value != "") {
                        video_counter.push(extracted_value);
                    }
                });
                var article_counter = [];
                $(`input[name*="article_counter"]`).each(function () {
                    var extracted_value = $(this).val();
                    if (extracted_value && extracted_value != "") {
                        article_counter.push(extracted_value);
                    }
                });
                var evaluation_quiz_count = [];
                $(`input[name*="evaluation_quiz_count"]`).each(function () {
                    var extracted_value = $(this).val();
                    if (extracted_value && extracted_value != "") {
                        evaluation_quiz_count.push(extracted_value);
                    }
                });
                var evaluation_question_count = [];
                $(`input[name*="evaluation_question_count"]`).each(function () {
                    var extracted_value = $(this).val();
                    if (extracted_value && extracted_value != "") {
                        evaluation_question_count.push(extracted_value);
                    }
                });

                var evaluation_no_assessment = [];
                $(`input[name*="evaluation_no_assessment"]`).each(function () {
                    var extracted_value = $(this).val();
                    if (extracted_value && extracted_value != "") {
                        evaluation_no_assessment.push(extracted_value);
                    }
                });

                $.ajax({
                    url: '/webinar/management/instructional_design/store',
                    type: 'POST',
                    data: {
                        objectives: objectives,
                        section_objective: section_objective,
                        instructors: instructors,
                        video_length: video_length,
                        video_counter: video_counter,
                        article_counter: article_counter,
                        evaluation_quiz_count: evaluation_quiz_count,
                        evaluation_question_count: evaluation_question_count,
                        evaluation_no_assessment: evaluation_no_assessment,
                        _token: $(`input[name="_token"]`).val(),
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            toastr.success('Success!', response.message);
                            setTimeout(() => {
                                window.location = "/webinar/management/accreditation";
                            }, 1000);
                        } else {
                            submit_button.html(`Submit for Accreditation`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                            toastr.error('Error!', response.message);
                        }
                    },
                    error: function(response) {
                        submit_button.html(`Submit for Accreditation`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                        toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
                    }
                });
            }
           
        });

        $(`select[name*="objectives"]`).each(function () {
            $(this).rules("add", {
                required: true,
            });
        });
    
        $(`select[name*="instructors"]`).each(function () {
            $(this).rules("add", {
                required: true,
            });
        });
    }

    return {
        // public functions
        init: function() {
            inst_input_validations();
        }
    };
}();
var ExpensesFormDesign = function() {

    var expns_input_validations = function() {
        validator = $("#expendses_breakdown_form").validate({
            // define validation rules
            rules: {
                vat_details: {
                    required: true,
                },
                vat_cost: {
                    required: true,
                    number:true
                },
                advertising_expenses_details: {
                    required: true,
                },
                advertising_expenses_cost: {
                    required: true,
                    number:true
                },
                item_materials_details: {
                    required: true,
                },
                item_materials_cost: {
                    required: true,
                    number:true
                },
                honoraria_details: {
                    required: true,
                },
                honoraria_cost: {
                    required: true,
                    number:true
                }
            },

            //display error alert on form submit  
            invalidHandler: function(event, validator) {
                var alert = $('#form_msg');
                alert.removeClass('kt-hidden').show();
                KTUtil.scrollTop();
            },

            submitHandler: function(form) {

                var submit_button = $("#ins_submit");
                submit_button.html(`Saving...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);
                var objectives = [];


                $.ajax({
                    url: '/webinar/management/expenses_breakdown/store',
                    method: 'POST',
                    data: {
                        venue_details: $(`textarea[name="venue_details"]`).val(),
                        venue_cost: $(`input[name="venue_cost"]`).val(),
                        meal_details: $(`textarea[name="meal_details"]`).val(),
                        meal_cost: $(`input[name="meal_cost"]`).val(),
                        honoraria_details: $(`textarea[name="honoraria_details"]`).val(),
                        honoraria_cost: $(`input[name="honoraria_cost"]`).val(),
                        item_materials_details: $(`textarea[name="item_materials_details"]`).val(),
                        item_materials_cost: $(`input[name="item_materials_cost"]`).val(),
                        advertising_expenses_details: $(`textarea[name="advertising_expenses_details"]`).val(),
                        advertising_expenses_cost: $(`input[name="advertising_expenses_cost"]`).val(),
                        transportation_details: $(`textarea[name="transportation_details"]`).val(),
                        transportation_cost: $(`input[name="transportation_cost"]`).val(),
                        accommodation_details: $(`textarea[name="accommodation_details"]`).val(),
                        accommodation_cost: $(`input[name="accommodation_cost"]`).val(),
                        process_fee_details: $(`textarea[name="process_fee_details"]`).val(),     
                        process_fee_cost: $(`input[name="process_fee_cost"]`).val(),
                        supplies_equip_details: $(`textarea[name="supplies_equip_details"]`).val(), 
                        supplies_equip_cost: $(`input[name="supplies_equip_cost"]`).val(), 
                        laboratory_details: $(`textarea[name="laboratory_details"]`).val(), 
                        laboratory_cost: $(`input[name="laboratory_cost"]`).val(),               
                        vat_details: $(`textarea[name="vat_details"]`).val(), 
                        vat_cost: $(`input[name="vat_cost"]`).val(), 
                        entrance_fee_details: $(`textarea[name="entrance_fee_details"]`).val(), 
                        entrance_fee_cost: $(`input[name="entrance_fee_cost"]`).val(), 
                        facilitator_fee_details: $(`textarea[name="facilitator_fee_details"]`).val(), 
                        facilitator_fee_cost: $(`input[name="facilitator_fee_cost"]`).val(), 
                        misc_details: $(`textarea[name="misc_details"]`).val(), 
                        misc_cost: $(`input[name="misc_cost"]`).val(), 
                        total_breakdown: $(`input[name="total_breakdown_val"]`).val(), 
                        _token: $(`input[name="_token"]`).val(),
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            toastr.success('Success!', response.message);
                            setTimeout(() => {
                                window.location = "/webinar/management/accreditation";
                            }, 1000);

                        } else {
                            submit_button.html(`Submit for Accreditation`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                            toastr.error('Error!', response.message);
                        }
                    },
                    error: function(response) {
                        submit_button.html(`Submit for Accreditation`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                        toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
                    }
                });
            }

        });
    }

    return {
        // public functions
        init: function() {
            expns_input_validations();
        }
    };
}();

function can_submit(){
    $.ajax({
        url: "/webinar/management/is/prc-acc",
        success: function(response){
            if(response.allow){
                $(`#form`).show();
                $(`#instructional_form`).show();
            }else{
                var errors = response.errors;
                var ul_list = $(`#list-of-errors`);
                ul_list.empty();
                errors.forEach(e => {
                    ul_list.append(`<li>${e}</li>`);
                });
                $(`#disabled-form`).show();
                $(`#disabled-form`).next().hide();
            }
        },
        error: function(){
            toastr.error("Something went wrong! please try again later.");
        }
    });
}

function getTotal() {
    var venue_cost = parseInt($(`input[name="venue_cost"]`).val());
    var meal_cost = parseInt($(`input[name="meal_cost"]`).val());
    var honoraria_cost = parseInt($(`input[name="honoraria_cost"]`).val());
    var item_materials_cost = parseInt($(`input[name="item_materials_cost"]`).val());
    var advertising_expenses_cost = parseInt($(`input[name="advertising_expenses_cost"]`).val());
    var transportation_cost = parseInt($(`input[name="transportation_cost"]`).val());
    var accommodation_cost = parseInt($(`input[name="accommodation_cost"]`).val());
    var process_fee_cost = parseInt($(`input[name="process_fee_cost"]`).val());
    var supplies_equip_cost = parseInt($(`input[name="supplies_equip_cost"]`).val()); 
    var laboratory_cost = parseInt($(`input[name="laboratory_cost"]`).val());               
    var vat_cost = parseInt($(`input[name="vat_cost"]`).val()); 
    var entrance_fee_cost = parseInt($(`input[name="entrance_fee_cost"]`).val()); 
    var facilitator_fee_cost = parseInt($(`input[name="facilitator_fee_cost"]`).val()); 
    var misc_cost = parseInt($(`input[name="misc_cost"]`).val()); 

    var total = venue_cost + meal_cost + honoraria_cost+ item_materials_cost + advertising_expenses_cost +transportation_cost + 
    accommodation_cost +process_fee_cost +supplies_equip_cost +laboratory_cost+vat_cost+entrance_fee_cost+facilitator_fee_cost+ misc_cost;
    document.getElementById("total_breakdown").innerHTML= total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    document.getElementById("total_breakdown_val").value = total;
}