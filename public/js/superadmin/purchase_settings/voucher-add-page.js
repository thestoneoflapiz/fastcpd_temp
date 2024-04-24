var $voucher_id = $("input[name='voucher_id']").val();

jQuery(document).ready(function() {

    $("input[name='start_date']").datepicker();
    $("input[name='end_date']").datepicker();

    $("input[name='voucher_code']").keyup(function(e) {
        var value = e.target.value;
        $(this).val(value.toUpperCase());
    });

    FormDesign.init();
});

/**
* Form Validation
* 
*/
var FormDesign = function () {
    var validator;

    $.validator.addMethod("minDateToday", function( dateInput, element ) {
        var dateGiven = new Date(dateInput);
        var dateMinimum = new Date();
        dateMinimum.setDate(dateMinimum.getDate() - 1);

        return this.optional( element ) || dateGiven >= dateMinimum;
    }, "Please choose a date starting from today" );

    $.validator.addMethod("greaterThan", function( dateInput, element ) {
        var starting_date = $(`input[name='start_date']`).val();
        if(starting_date!="" || starting_date!=null){
            var dateGiven = new Date(dateInput);
            var dateMinimum = new Date(starting_date);
    
            return dateGiven >= dateMinimum;
        }

        return false;
    }, "Please choose a date greater than the starting date" );

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
        validator = $( "#voucher_form" ).validate({
            // define validation rules
            rules: {
                voucher_code: {
                    required: true,
                    maxlength: 50,
                    minlength: 5,
                    uniqueVoucher: true,
                },
                discount: {
                    required: true,
                    number: true,
                    min: 1,
                    max: 100,
                },
                description: {
                    maxlength: 100,
                },
                start_date: {
                    required: true,
                    minDateToday: true,
                },
                end_date: {
                    required: true,
                    greaterThan: true
                },
                type: {
                    required: true,
                },
            },
            
            invalidHandler: function(event, validator) {             
                var alert = $('#promotion_form_alert');
                alert.removeClass('kt-hidden').show();
                KTUtil.scrollTop();
            },
            
            submitHandler: function (form) {
                var $submit = $("#voucher_form_submit");
                $submit.addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);

                $.ajax({
                    url: "/superadmin/settings/vouchers/save",
                    data: {
                        voucher_id: $voucher_id,
                        voucher_code: $("input[name='voucher_code']").val(),
                        discount: $("input[name='discount']").val(),
                        description: $("input[name='description']").val(),
                        start_date: $("input[name='start_date']").val(),
                        end_date: $("input[name='end_date']").val(),
                        type: $("#vtype").val(),
                    }, success: function(response) {
                        toastr.success("Successfully saved voucher!");
                        $submit.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');

                        setTimeout(() => {
                            window.location="/superadmin/settings/vouchers";
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
        }
    };
}();