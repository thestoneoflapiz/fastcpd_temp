jQuery(document).ready(function() {
    $(`[name="fastcpd_provide_link"]`).click(function(){
        if($(this).is(":checked")){
            $(`[name*="[link]"]`).each(function(){
                $(this).prop("disabled", true).attr("placeholder", "Fast CPD webinar link will be shown after approval").val(null);
            });

            FormDesign.init();
            return;
        }

        $(`[name*="[link]"]`).each(function(){
            $(this).prop("disabled", false).attr("placeholder", "https://mywebinarlink.com");
        });

        FormDesign.init();
        return;
    });

    $(`[name*="[link]"]`).inputmask({
        mask: "https://*{1,100}[.*{1,100}][.*{1,100}][.*{1,100}]",
        greedy: false,
        onBeforePaste: function (pastedValue, opts) {
            pastedValue = pastedValue.toLowerCase();
            return pastedValue.replace("mailto:", "");
        },
        definitions: {
            '*': {
                validator: "[0-9A-Za-z#%&'*+/=?_{}\-]",
                cardinality: 1,
                casing: "lower"
            }
        }
    });

    FormDesign.init();
});

var FormDesign = function() {

    var input_validations = function() {
        validator = $("#form").validate({
            rules: {},

            invalidHandler: function(event, validator) {
                var alert = $('#form_msg');
                alert.removeClass('kt-hidden').show();
                KTUtil.scrollTop();
            },

            submitHandler: function(form) {
                var $links = [];

                var submit_button = $("#submit_form");
                submit_button.html(`Saving...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);

                if(!$(`[name="fastcpd_provide_link"]`).is(":checked")){
                    $(`[name*="[link]"]`).each(function(){
                        var name = $(this).attr("name");
                        var id = name.split(/[\[\]]+/)[2];

                        $links.push({
                            id: id,
                            value: $(this).val()
                        });
                    });
                }
                
                $.ajax({
                    url: '/webinar/management/links/store',
                    type: 'POST',
                    data: {
                        links: $links,
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

        $(`input[name*="[link]"]`).each(function () {
            $(this).rules("add", {
                required: {
                    depends: function(element) {
                        return !$(`[name="fastcpd_provide_link"]`).is(":checked");
                    }
                },
                minlength: {
                    depends: function(element) {
                        return !$(`[name="fastcpd_provide_link"]`).is(":checked");
                    }
                },
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