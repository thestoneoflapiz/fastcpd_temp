jQuery(document).ready(function () {
    KTFormRepeater.init();
    FormDesign.init();
});

var KTFormRepeater = function () {

    var course_objectives = function () {
        $('#repeater_objectives').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'Enter your objective'
            },

            show: function () {
                // limitation
                var obj_inputs = $(`input.objective_input`);
                if (obj_inputs.length < 10) {
                    $(this).slideDown();
                    FormDesign.init();
                } else {
                    toastr.error("Sorry! Objective limit is maximum of 10 lines");
                }
            },

            hide: function (deleteElement) {
                // limitation
                var obj_inputs = $(`input.objective_input`);
                if (obj_inputs.length == 1) {
                    toastr.error("Sorry! Objective needs at least 1 line");
                } else {
                    $(this).slideUp("normal", function () { $(deleteElement).remove(); });
                }
            }
        });
    }

    var course_requirements = function () {
        $('#repeater_requirements').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'Enter your requirement'
            },

            show: function () {
                // limitation
                var req_inputs = $(`input.requirement_input`);
                if (req_inputs.length < 10) {
                    $(this).slideDown();
                    FormDesign.init();
                } else {
                    toastr.error("Sorry! Requirement limit is maximum of 10 lines");
                }
            },

            hide: function (deleteElement) {
                // limitation
                var req_inputs = $(`input.requirement_input`);
                if (req_inputs.length == 1) {
                    toastr.error("Sorry! Requirement needs at least 1 line");
                } else {
                    $(this).slideUp("normal", function () { $(deleteElement).remove(); });
                }
            }
        });
    }

    var course_targets = function () {
        $('#repeater_targets').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'Enter your target student'
            },

            show: function () {
                // limitation
                var target_inputs = $(`input.target_input`);
                if (target_inputs.length < 10) {
                    $(this).slideDown();
                    FormDesign.init();
                } else {
                    toastr.error("Sorry! Target student limit is maximum of 10 lines");
                }
            },

            hide: function (deleteElement) {
                // limitation
                var target_inputs = $(`input.target_input`);
                if (target_inputs.length == 1) {
                    toastr.error("Sorry! Target student needs at least 1 line");
                } else {
                    $(this).slideUp("normal", function () { $(deleteElement).remove(); });
                }
            }
        });
    }

    return {
        init: function () {
            course_objectives();
            course_requirements();
            course_targets();
        }
    };
}();

var FormDesign = function () {


    var objective_validation = function () {
        validator = $("#course_objective_form").validate({
            rules: {
                objective: {
                    required: true,
                    minlength: 20,
                    maxlength: 80,
                },
            },

            invalidHandler: function (event, validator) {
                var alert = $('#course_objective_form_msg');
                alert.removeClass('kt-hidden').show();
            },

            submitHandler: function (form) {
                var objective_values = [];
                $(`input[name*="objective"]`).each(function () {
                    var extracted_value = $(this).val();
                    if (extracted_value && extracted_value != "") {
                        objective_values.push(extracted_value);
                    }
                });

                var $submit_button = $("#course_objective_form").find("button.btn.btn-success");
                $submit_button.addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true).html("Saving...");
                $.ajax({
                    url: '/course_management/attract_enrollments/store',
                    type: 'POST',
                    data: {
                        store: "objectives",
                        objectives: objective_values,
                        _token: $(`input[name="_token"]`).val(),
                    },
                    success: function (response) {
                        $submit_button.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                        if (response.status == 200) {
                            toastr.success('Success!', response.message);
                        } else {
                            toastr.error('Error!', response.message);
                        }
                    },
                    error: function (response) {
                        $submit_button.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                        toastr.error('Error!', response.message);
                    }
                });
            }
        });

        $(`input[name*="objective"]`).each(function () {
            $(this).rules("add", {
                required: true,
                minlength: 20,
                maxlength: 80,
            });
        });
    }

    var requirement_validation = function () {
        validator = $("#course_requirement_form").validate({
            rules: {
                requirement: {
                    required: true,
                    minlength: 20,
                    maxlength: 80,
                },
            },

            invalidHandler: function (event, validator) {
                var alert = $('#course_requirement_form_msg');
                alert.removeClass('kt-hidden').show();
            },

            submitHandler: function (form) {
                var requirement_values = [];
                $(`input[name*="requirement"]`).each(function () {
                    var extracted_value = $(this).val();
                    if (extracted_value && extracted_value != "") {
                        requirement_values.push(extracted_value);
                    }
                });

                var $submit_button = $("#course_requirement_form").find("button.btn.btn-success");
                $submit_button.addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true).html("Saving...");
                $.ajax({
                    url: '/course_management/attract_enrollments/store',
                    type: 'POST',
                    data: {
                        store: "requirements",
                        requirements: requirement_values,
                        _token: $(`input[name="_token"]`).val(),
                    },
                    success: function (response) {
                        $submit_button.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                        if (response.status == 200) {
                            toastr.success('Success!', response.message);
                        } else {
                            toastr.error('Error!', response.message);
                        }
                    },
                    error: function (response) {
                        $submit_button.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                        toastr.error('Error!', response.message);
                    }
                });
            }
        });

        $(`input[name*="requirement"]`).each(function () {
            $(this).rules("add", {
                required: true,
                minlength: 20,
                maxlength: 80,
            });
        });
    }

    var target_validation = function () {
        validator = $("#course_target_form").validate({
            rules: {
                target: {
                    required: true,
                    minlength: 20,
                    maxlength: 80,
                },
            },

            invalidHandler: function (event, validator) {
                var alert = $('#course_target_form_msg');
                alert.removeClass('kt-hidden').show();
            },

            submitHandler: function (form) {
                var target_values = [];
                $(`input[name*="target"]`).each(function () {
                    var extracted_value = $(this).val();
                    if (extracted_value && extracted_value != "") {
                        target_values.push(extracted_value);
                    }
                });

                var $submit_button = $("#course_target_form").find("button.btn.btn-success");
                $submit_button.addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true).html("Saving...");
                $.ajax({
                    url: '/course_management/attract_enrollments/store',
                    type: 'POST',
                    data: {
                        store: "target_students",
                        target_students: target_values,
                        _token: $(`input[name="_token"]`).val(),
                    },
                    success: function (response) {
                        $submit_button.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                        if (response.status == 200) {
                            toastr.success('Success!', response.message);
                        } else {
                            toastr.error('Error!', response.message);
                        }
                    },
                    error: function (response) {
                        $submit_button.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                        toastr.error('Error!', response.message);
                    }
                });
            }
        });

        $(`input[name*="target"]`).each(function () {
            $(this).rules("add", {
                required: true,
                minlength: 20,
                maxlength: 80,
            });
        });
    }

    return {
        // public functions
        init: function () {
            objective_validation();
            requirement_validation();
            target_validation();
        }
    };
}();