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

jQuery(document).ready(function() {
    $("#without_price").inputmask({
        alias: "pesos"
    });

    var arrows;
    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
    } else {
        arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }
    
    $(".datepicker").datepicker({
        autoclose: true,
        gotoCurrent: false,
        todayHighlight: true, 
        orientation: "bottom left",
        templates: arrows
    });
    
    FormDesign.init();
});

var FormDesign = function() {

    var input_validations = function() {
        validator = $("#form").validate({
            rules: {
                published_date: {
                    required: true,
                },
                date_approved: {
                    required: true,
                },
            },

            invalidHandler: function(event, validator) {
                var alert = $('#form_msg');
                alert.removeClass('kt-hidden').show();
                KTUtil.scrollTop();
            },

            submitHandler: function(form) {
                var $accreditation = [];

                var submit_button = $("#submit_form");
                submit_button.html(`Saving...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);

                $professions.forEach(pro => {
                    var $form_group = $(`#profession-group-${pro.id}`);
                    var units = $form_group.find(`input[name="[units][${pro.id}]"]`).val();
                    var program_no = $form_group.find(`input[name="[program_no][${pro.id}]"]`).val();

                    $accreditation.push({
                        id: pro.id,
                        units: units,
                        program_no: program_no,
                    });
                });
                
                $.ajax({
                    url: '/webinar/management/publish/store',
                    type: 'POST',
                    data: {
                        published_date: $("#published_date").val(),
                        date_approved: $("#date_approved").val(),
                        accreditation: $accreditation,
                        without_price: $("#without_price").length > 0 ? $("#without_price").val() : 0,
                        _token: $('[name="_token"]').val(),
                    },
                    success: function(response) {
                        submit_button.html(`Submit`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                        if (response.status == 200) {
                            toastr.success('Success!', response.message);
                        } else {  toastr.error('Error!', response.message); }
                    },
                    error: function(response) {
                        submit_button.html(`Submit`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                        toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
                    }
                });
            }
        });

        $(`input[name*="units"]`).each(function () {
            $(this).inputmask('decimal', {
                rightAlignNumerics: false
            }); 

            $(this).rules("add", {
                required: true,
                max: 100,
                min: 1,
            });
        });
        
        $(`input[name*="program_no"]`).each(function () {
            $(this).rules("add", {
                required: true,
                maxlength: 150,
                minlength: 5,
            });
        });
    }

    return {
        // public functions
        init: function() {

            input_validations();
        }
    };
}();