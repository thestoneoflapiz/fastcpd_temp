@extends('template.master_superadmin')
@section('title', 'Top Professions')
@section('styles')
<style>
    .centered{margin:auto;}
    .minimize > i{font-size:2rem}
    ol{list-style: none;counter-reset: my-awesome-counter;}
    ol li{font-size:1.2rem !important;counter-increment: my-awesome-counter;}
    ol li::before{content: counter(my-awesome-counter) ". ";font-weight:700;font-size:1.3rem;}
</style>
@endsection
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-4 col-md-6 col-sm-6 col-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label"> 
                            <h3 class="kt-portlet__head-title">Current List</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        @if($top_professions)
                        <ol>
                            @foreach($top_professions as $tpp)
                            <li>&nbsp; {{ $tpp->profession }}</li>
                            @endforeach
                        </ol>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-md-6 col-sm-6 col-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label"> 
                            <h3 class="kt-portlet__head-title">Form</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <meta name="_token" content="{{ csrf_token() }}">
                        <form id="form">
                            <div class="row">  
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Top Professions <text class="kt-font-danger kt-font-bold">*</text></label>
                                        <select name="professions" class="form-control" multiple>
                                            <option disabled></option>
                                            @foreach(_professions() as $pr)
                                            <option value="{{ $pr['id'] }}/{{ $pr['profession'] }}">{{ $pr['profession'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-success" type="submit" id="submit_button">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script>
    jQuery(document).ready(function(){

        $("select[name='professions']").select2({
            placeholder: "Please select 10 professions",
            maximumSelectionLength: 10,
        });

        $("select[name='professions']").on("select2:select", function (evt) {
            var element = evt.params.data.element;
            var $element = $(element);
            
            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
        });
        FormDesign.init();
    });

    /**
    * Form Validation
    * 
    */
    var FormDesign = function () {
        var validator;

        var form = function () {
            validator = $( "#form" ).validate({
                // define validation rules
                rules: {
                    professions: {
                        required: true,
                        minlength: 5    
                    },
                },
                messages: {
                    professions: {
                        minlength: "Please select at least 5 professions"
                    },
                },
                
                invalidHandler: function(event, validator) {             
                    toastr.error("Oops! Something is not right with your selection!");
                },
                
                submitHandler: function (form) {
                    var $submit = $("#submit_button");
                    $submit.addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);

                    $.ajax({
                        url: "/superadmin/top_professions/save",
                        data: {
                            professions: $("select[name='professions']").val(),
                        }, success: function(response) {
                            toastr.success("Successfully saved!");
                            $submit.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');

                            setTimeout(() => {
                                window.location="/superadmin/top_professions";
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
                form(); 
            }
        };
    }();
</script>
@endsection
