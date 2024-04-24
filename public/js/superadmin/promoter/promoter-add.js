jQuery(document).ready(function () {
    $("input[name='start_date']").datetimepicker();
    $("input[name='end_date']").datetimepicker();

    $("input[name='voucher_code']").keyup(function(e) {
        var value = e.target.value;
        $(this).val(value.toUpperCase());
    });

    $(".selection").change(function (e) {
        var value = e.target.value;
        if (value == "empty" || value == "!empty") {
            $(this).next("input").attr("disabled", "disabled");
            $(this).next("input").val("");
        } else {
            $(this).next("input").removeAttr("disabled", "disabled");
        }
    });
    FormDesign.init();
});

var FormDesign = function () {
    var validator;
    var $voucher_id = $("input[name='voucher_id']").val();
    var input_masks = function () {
        //email address
        $("#email").inputmask({
            mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}].com",
            greedy: false,
            onBeforePaste: function (pastedValue, opts) {
                pastedValue = pastedValue.toLowerCase();
                return pastedValue.replace("mailto:", "");
            },
            definitions: {
                '*': {
                    validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
                    cardinality: 1,
                    casing: "lower"
                }
        }
        });
    }
    $.validator.addMethod("uniqueVoucher", function( input, element ) {
        var $unique = false;
        $.ajax({
            async: false,
            url: `/superadmin/settings/vouchers/unique?code=${input}&voucherId=${$voucher_id}`,
            success: function(){
                $unique =  true;
            }, error: function(){
                $unique = false;
            }
        });

        return $unique
    }, "Sorry! The voucher code you entered is already taken!" );

    var promotion = function () {
        validator = $( "#promoter_form" ).validate({
            // define validation rules
            rules: {
                voucher_code: {
                    required: true,
                    maxlength: 50,
                    minlength: 5,
                    uniqueVoucher: true,
                },
                firstname: {
                    required: true,
                },
                lastname: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                discount: {
                    required: true,
                    number: true,
                    min: 1,
                    max: 99,
                },
                
                
            },


            
            invalidHandler: function(event, validator) {             
                var alert = $('#promotion_form_alert');
                alert.removeClass('kt-hidden').show();
                KTUtil.scrollTop();
            },
            
            submitHandler: function (form) {
                var $submit = $("#save_promoter");
                $submit.addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);
                $.ajax({
                    url: "/superadmin/promoter/save",
                    method: "POST",
                    data: {
                        firstname: $("input[name='firstname']").val(),
                        middlename: $("input[name='middlename']").val(),
                        lastname: $("input[name='lastname']").val(),
                        email: $("input[name='email']").val(),
                        voucher_code: $("input[name='voucher_code']").val(),
                        voucher_id: $("input[name='voucher_id']").val(),
                        discount: $("input[name='discount']").val(),
                        _token: $("[name='_token']").val(),
                    }, success: function(response) {
                        toastr.success("Successfully invited a Promoter!");
                        $submit.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');

                        setTimeout(() => {
                            window.location="/superadmin/promoters";
                        }, 1500);
                        
                    }, error: function (){
                        toastr.error("Error", "Something went wrong! Please refresh your browser");
                        $submit.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                    }
                });
            }
        });       
    }

    return {
        // public functions
        init: function() {
            promotion(); 
            input_masks();
        }
    };
}();