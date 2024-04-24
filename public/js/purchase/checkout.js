var total_amount = $(`[name="real_price"]`).val();

jQuery(document).ready(function(){    
    $(`[name="card_number"]`).inputmask("9999 9999 9999 9999", {
        "placeholder": "**** **** **** ****",
    });

    $(`[name="expiration_month"]`).inputmask("99", {
        "placeholder": "99",
    });

    $(`[name="expiration_year"]`).inputmask("99", {
        "placeholder": "99",
    });

    $(`[name="cvv"]`).inputmask("999", {
        "placeholder": "999",
    });

    $(`[name="postal_code"]`).inputmask("9999", {
        "placeholder": "9999",
    });
 
    $(`#select-e_wallet-source, #select-online_banking-source, #select-online_banking_otc-source, #select-payment_center-source`).select2({
        placeholder: "Please choose from the list"
    });

    PM_CARD.init();
    PM_Wallet.init();
    DP_OB.init();
    DP_OBOTC.init();
    DP_PC.init();
});

var PM_CARD = (function () {
    var validator;
    var input_validations = function () {
        validator = $(`#form-pm-card`).validate(
            {
                rules: {
                    card_number: {
                        required: true,
                        minlength: 19,
                        maxlength: 19, //  including the spaces
                    },
                    name: {
                        required: true,
                    },
                    expiration_month: {
                        required: true,
                        min: 1, 
                        max: 12,
                    },
                    expiration_year: {
                        required: true,
                    },
                    cvv: {
                        required: true,
                        number: true,
                        minlength: 3,
                        maxlength: 3,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                },
                invalidHandler: function (event, validator) {
                    toastr.error("You need to fill up all fields!");
                },
                submitHandler: function (form) {
                    var monthInput = $("[name='expiration_month']").val();
                    var yearInput = $("[name='expiration_year']").val();
                    var exp_date = monthInput+" / 01 / "+yearInput;
                    var dateGiven = new Date(exp_date);
                    var dateToday = new Date();

                    if(dateToday > dateGiven){
                        toastr.error("Expiration date is not valid! Please input greater than this month");
                    }else{
                        var $submit = $(`button#paymongo-pm-card`);
                        $submit.addClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning`).prop("disabled", true);
    
                        if(total_amount < 100){
                            toastr.error("Sorry! You're unable to use this method of payment. Minimum amount should be at ₱100.00");
                            $submit.removeClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning`).prop("disabled", false);
                            return;
                        }
    
                        toastr.info("Processing Payment...Please do not reload the page while we're processing your transaction!");
                        $.ajax({
                            method: "POST",
                            url: "/checkout/pmongo/card-payment/process",
                            data: {
                                _token: $("[name='_token']").val(),
                                card_number: $("[name='card_number']").val(),
                                name: $("[name='name']").val(),
                                expiration_month: $("[name='expiration_month']").val(),
                                expiration_year: $("[name='expiration_year']").val(),
                                cvv: $("[name='cvv']").val(),
                                email: $("[name='email']").val(),
                                phone: $("[name='phone']").val(),
                            },
                            success: function(response){
                                if(response.hasOwnProperty("message")){
                                    toastr.success(response.message);
                                }
                                
                                if(response.hasOwnProperty("redirect")){
                                    if(response.hasOwnProperty("redirect_link")){
                                        setTimeout(() => {
                                            window.location=response.redirect_link;
                                        }, 1500);
                                    }else{
                                        setTimeout(() => {
                                            window.location="/profile/settings";
                                        }, 1500);
                                    }
                                }
                            },
                            error: function(response){
                                var body = response.responseJSON;
                                if(body.hasOwnProperty("message")){
                                    toastr.error(body.message);
                                }else{
                                    toastr.error("Something went wrong! Please refresh your browser");
                                }
                                $submit.removeClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning`).prop("disabled", false);
                            }
                        });
                    }
                },
            }
        );
    };

    return {
        init: function () {
            input_validations();
        },
    };
})();

var PM_Wallet = (function () {
    var validator;

    var input_validations = function () {
        validator = $(`#form-pm-wallet`).validate(
            {
                rules: {
                    e_method: {
                        required: true,
                    },
                },
                invalidHandler: function (event, validator) {
                    toastr.error("You need to fill up all fields!");
                },
                submitHandler: function (form) {
                    var $submit = $(`button#paymongo-pm-e_wallet`);
                    $submit.addClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning`).prop("disabled", true);

                    if(total_amount < 100){
                        toastr.error("Sorry! You're unable to use this method of payment. Minimum amount should be at ₱100.00");
                        $submit.removeClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning`).prop("disabled", false);
                        return;
                    }

                    toastr.info("Processing Payment Method...Please do not reload the page while we're processing!");
                    $.ajax({
                        method: "POST",
                        url: "/checkout/pmongo/e-wallet-payment/process",
                        data: {
                            _token: $("[name='_token']").val(),
                            method: $("[name='e_method']").val(),
                        },
                        success: function(response){
                            if(response.hasOwnProperty("message")){
                                toastr.success(body.message);
                            }

                            if(response.hasOwnProperty("redirect")){
                                setTimeout(() => {
                                    window.location=response.redirect;
                                }, 1500);
                            }
                        },
                        error: function(response){
                            var body = response.responseJSON;
                            if(body.hasOwnProperty("message")){
                                toastr.error(body.message);
                            }else{
                                toastr.error("Something went wrong! Please refresh your browser");
                            }
                            $submit.removeClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning`).prop("disabled", false);
                        }
                    });
                },
            }
        );
    };

    return {
        init: function () {
            input_validations();
        },
    };
})();

var DP_OB = (function () {
    var validator;

    var input_validations = function () {
        validator = $(`#form-ob`).validate(
            {
                rules: {
                    method: {
                        required: true,
                    },
                    agreement: {
                        required: true,
                    },
                },
                invalidHandler: function (event, validator) {
                    toastr.error("You need to fill up all fields!");
                },
                submitHandler: function (form) {
                    var $submit = $(`button#dragonpay-pm-online_banking`);
                    $submit.addClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning`).prop("disabled", true);
                    $.ajax({
                        method: "POST",
                        url: "/checkout/pdragon/payment/process",
                        data: {
                            _token: $("[name='_token']").val(),
                            method: $("#select-online_banking-source").val(),
                        },
                        success: function(response){
                            setTimeout(() => {
                                window.location=response.redirect;
                            }, 1500);
                        },
                        error: function(response){
                            var body = response.responseJSON;
                            if(body.hasOwnProperty("message")){
                                toastr.error(body.message);
                            }else{
                                toastr.error("Something went wrong! Please refresh your browser");
                            }
                            $submit.removeClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning`);
                        }
                    });
                },
            }
        );
    };

    return {
        init: function () {
            input_validations();
        },
    };
})();

var DP_OBOTC = (function () {
    var validator;

    var input_validations = function () {
        validator = $(`#form-obotc`).validate(
            {
                rules: {
                    method: {
                        required: true,
                    },
                    agreement: {
                        required: true,
                    },
                },
                invalidHandler: function (event, validator) {
                    toastr.error("You need to fill up all fields!");
                },
                submitHandler: function (form) {
                    var $submit = $(`button#dragonpay-pm-online_banking_otc`);
                    $submit.addClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning`).prop("disabled", true);
                    $.ajax({
                        method: "POST",
                        url: "/checkout/pdragon/payment/process",
                        data: {
                            _token: $("[name='_token']").val(),
                            method: $("#select-online_banking_otc-source").val(),
                        },
                        success: function(response){
                            setTimeout(() => {
                                window.location=response.redirect;
                            }, 1500);
                        },
                        error: function(response){
                            var body = response.responseJSON;
                            if(body.hasOwnProperty("message")){
                                toastr.error(body.message);
                            }else{
                                toastr.error("Something went wrong! Please refresh your browser");
                            }
                            $submit.removeClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning`);
                        }
                    });
                },
            }
        );
    };

    return {
        init: function () {
            input_validations();
        },
    };
})();

var DP_PC = (function () {
    var validator;

    var input_validations = function () {
        validator = $(`#form-pc`).validate(
            {
                rules: {
                    method: {
                        required: true,
                    },
                    agreement: {
                        required: true,
                    },
                },
                invalidHandler: function (event, validator) {
                    toastr.error("You need to fill up all fields!");
                },
                submitHandler: function (form) {
                    var $submit = $(`button#dragonpay-pm-payment_centers`);
                    $submit.addClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning`).prop("disabled", true);
                    $.ajax({
                        method: "POST",
                        url: "/checkout/pdragon/payment/process",
                        data: {
                            _token: $("[name='_token']").val(),
                            method: $("#select-payment_center-source").val(),
                        },
                        success: function(response){
                            setTimeout(() => {
                                window.location=response.redirect;
                            }, 1500);
                        },
                        error: function(response){
                            var body = response.responseJSON;
                            if(body.hasOwnProperty("message")){
                                toastr.error(body.message);
                            }else{
                                toastr.error("Something went wrong! Please refresh your browser");
                            }
                            $submit.removeClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning`);
                        }
                    });
                },
            }
        );
    };

    return {
        init: function () {
            input_validations();
        },
    };
})();