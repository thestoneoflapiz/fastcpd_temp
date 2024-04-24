<html lang="en">
    <!-- begin::Head -->
	<head>
        <meta charset="utf-8" />
        <meta name="description" content="Updates and statistics">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
		<link href="{{asset('plugins/general/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-timepicker/css/bootstrap-timepicker.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-select/dist/css/bootstrap-select.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/ion-rangeslider/css/ion.rangeSlider.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/summernote/dist/summernote.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-markdown/css/bootstrap-markdown.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/animate.css/animate.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/toastr/build/toastr.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/dual-listbox/dist/dual-listbox.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/socicon/css/socicon.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/plugins/line-awesome/css/line-awesome.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/plugins/flaticon/flaticon.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/plugins/flaticon2/flaticon.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('css/style.bundle.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/plugins/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/@fullcalendar/core/main.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/@fullcalendar/daygrid/main.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/@fullcalendar/list/main.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/@fullcalendar/timegrid/main.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('css/skins/header/base/light.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('css/skins/header/menu/light.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('css/skins/brand/dark.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('css/skins/aside/dark.min.css')}}" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="{{asset('img/system/icon-1.png')}}" />
		<style>
			.toast-title{color:white !important; font-weight:500;}
            .body-positive{padding-top:3%;background-color:#f2f3f8;}
            .email-box{margin:5%;width:90%;border-radius:5px;overflow:hidden;}
            .email-footer{margin:5%;width:90%;padding:10px 0px 30px 0px;}
            @media only screen and (max-width: 800px) {
                body{margin:unset;}
                .email-box{right:0;width:100%;border:none;}
                .email-footer{margin-left:0;padding-top:10px 0px 30px 0px;padding-bottom:}
            }
            /* VERIFY EMAIL */
            .header-box{width:100%; margin:unset;}
            /* .header-bg{position:relative;border-top-left-radius:5px;border-top-right-radius:5px;min-height: 150px;background-color:#2a7de9;background-image: url("{{URL::asset('media/bg/400.jpg')}}"); background-size:cover; } */
            .header-msg{bottom:0;position:absolute;left:0;color:white;margin-left:40px;}
            .body-box{padding-bottom:30px;width:100%;background-color:white;border-bottom-left-radius:20px;border-bottom-right-radius:20px;}
            .body-container{padding:3.5%;}
            .body-footer{padding:3.5%;}
            .h2-style{padding-left:40px;font-size:25px;font-weight:600;color:white;}
            /* VERIFY EMAIL */
            .social-icons {margin:20px;}
            .header-bg{width:100% !important;padding-top:20px;border-top-left-radius:20px;border-top-right-radius:20px;min-height:70px;background-color:#2a7de9; }
            .right{text-align: right;}
            .left{text-align: left;}
            .center{text-align: center;margin:auto}
            .center_div{margin: auto;width: 70%;}
            .justify{text-align:justify;}
            del{color:red}
            .h2-style{font-size:25px;font-weight:600;color:white;}
		</style>
	</head>
	<!-- end::Head -->


	<body class="kt-page--loading">
        <div class="kt-grid kt-grid--hor kt-grid--root">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    <div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                        <div class="row">
                            <div class="email-box">

                                <div class="center" style="margin: 30px;">
                                    <img alt="FastCPD Company Logo" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/logo-1.png" width="150">
                                </div>
                            
                                <div class="header-box">
                                    <table width="100%;" class="header-bg">
                                        <tr>
                                            <td>
                                                <h2 class="h2-style">
                                                    PROGRAM DETAILS
                                                </h2>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="body-box kt-padding-20">
                                    <div class="form-group row">
                                        <label class="col-xl-2 col-md-2 col-12 form-label center">Title of Program:</label>
                                        <div class="col-xl-10 col-md-10 col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{$course_info['title_of_program']}}" id="title" width="auto" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="copySelectedField('title')" >Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-2 col-md-2 col-12 form-label center">Profession:</label>
                                        <div class="col-xl-10 col-md-10 col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{$course_info['course_profession_id']}}" id="profession" width="auto" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="copySelectedField('profession')" >Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-2 col-md-2 col-12 form-label center">Course Description:</label>
                                        <div class="col-xl-10 col-md-10 col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{$course_info['course_description']}}" id="description" width="auto" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="copySelectedField('description')" >Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-2 col-md-2 col-12 form-label center">Objectives:</label>
                                        <div class="col-xl-10 col-md-10 col-12">
                                            <div class="input-group">
                                                <textarea class="form-control" id="objectives" readonly>
                                                    <?php
                                                        if($course_info['objectives']){
                                                            foreach($course_info['objectives'] as $objective){
                                                                echo " - ". $objective."\n" ;
                                                            }
                                                        }
                                                    ?></textarea>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="copySelectedField('objectives')" >Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-2 col-md-2 col-12 form-label center">Registration Fee:</label>
                                        <div class="col-xl-10 col-md-10 col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{$course_info['registration_fee']}}" id="registration_fee" width="auto" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="copySelectedField('registration_fee')" >Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-2 col-md-2 col-12 form-label center">Target No. of Participants:</label>
                                        <div class="col-xl-10 col-md-10 col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{$course_info['target_number_students']}}" id="target_number_students" width="auto" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="copySelectedField('target_number_students')" >Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-2 col-md-2 col-12 form-label center">Program Total Hours:</label>
                                        <div class="col-xl-10 col-md-10 col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{$course_info['time']}}" id="time" width="auto" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="copySelectedField('time')" >Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="center" style="margin: 30px;">
                                        PROGRAM SCHEDULE
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-2 col-md-2 col-12 form-label center">Date From:</label>
                                        <div class="col-xl-4 col-md-4 col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{$course_info['start_date']}}" id="start_date" width="auto" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="copySelectedField('start_date')" >Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="col-xl-2 col-md-2 col-12 form-label center">Date To:</label>
                                        <div class="col-xl-4 col-md-4 col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{$course_info['end_date']}}" id="end_date" width="auto" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="copySelectedField('end_date')" >Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-2 col-md-2 col-12 form-label center">Venue:</label>
                                        <div class="col-xl-10 col-md-10 col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{$course_info['place_venue']}}" id="venue" width="auto" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="copySelectedField('venue')" >Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="header-box">
                                    <table width="100%;" class="header-bg">
                                        <tr>
                                            <td>
                                                <h2 class="h2-style">
                                                    ACCREDITATION
                                                </h2>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="body-box kt-padding-20">
                                    <div class="form-group row">
                                        <label class="col-xl-2 col-md-2 col-12 form-label center">Email:</label>
                                        <div class="col-xl-4 col-md-4 col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{$course_info['accreditors_email']}}" id="a_email" width="auto" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="copySelectedField('a_email')" >Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="col-xl-2 col-md-2 col-12 form-label center">Password:</label>
                                        <div class="col-xl-4 col-md-4 col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{$course_info['accreditors_pass']}}" id="a_pass" width="auto" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="copySelectedField('a_pass')" >Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="header-box">
                                    <table width="100%;" class="header-bg">
                                        <tr>
                                            <td>
                                                <h2 class="h2-style">
                                                    PROGRAM OF ACTIVITIES
                                                </h2>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="body-box kt-padding-20">
                                    <div class="form-group row">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Topic</th>
                                                <th>Time Frame</th>
                                                <th>Speaker</th>
                                            </tr>
                                            <?php  foreach($instructional_design as $key => $idesign){  ?>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" value="{{$idesign->section_objective}}" id="{{'topic-'.$key}}" width="auto" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" onclick="copySelectedField(`{{'topic-'.$key}}`)" >Copy</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" value="{{$idesign->video_length ? $idesign->video_length.' mins' : ' not measured '}}" id="{{'time-'.$key}}" width="auto" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" onclick="copySelectedField(`{{'time-'.$key}}`)" >Copy</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <textarea type="text" class="form-control"  id="{{'speaker-'.$key}}" width="auto" readonly>
                                                                @foreach(json_decode($idesign->instructors) as $instructor)
                                                                <?= $instructor ."\n"?>
                                                                @endforeach
                                                            </textarea>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" onclick="copySelectedField(`{{'speaker-'.$key}}`)" >Copy</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php }?>
                                        </table>
                                    </div>
                                    <b>Promotional Image Download</b><br/> <br/>
                                    <div class="center">
                                        <a href="{{$course_info['course_poster']}}" download>
                                        <img src="{{$course_info['course_poster']}}" alt="{{$course_info['title_of_program']}}" width="500px" height="280px">
                                    </div>
                                </div>
                            </div>
                                        
                            
                                    
                            <div class="email-footer" style="text-align: center;">
                                If you need help navigating around FastCPD, you can visit our help center for tutorials and guides on how to use the platform <br/><br/>
                                <a href="https://www.fastcpd.com/site"><button type="button" class="btn btn-default">Go to Help Center</button></a><br/><br/>
                                Follow us or contact FastCPD<br/>
                                <a href="https://www.facebook.com/fastcpd"><img class="social-icons" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/iconfinder_facebook_245987.png" width="30"></a>
                                <a href="mailto:help@fastcpd.com"><img class="social-icons" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/iconfinder_gmail_1220367.png" width="30"></a>
                                <a href="https://www.youtube.com/channel/UC-3v3AGXbogd7CmSyJQ6-jw"><img class="social-icons" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/iconfinder_Youtube_1298778.png" width="30"></a>
                                <a href="https://www.fastcpd.com/">	<img class="social-icons" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/icon-1.png" width="40"></a><br/><br/>
                                <p style="font-size:12px;text-align: center;">FastCPD.com 2020</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <iframe id="my_iframe" style="display:none;"></iframe>
		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#25cfa8",
						"dark": "#282a3c",
						"light": "#ffffff",
						"primary": "#25cfa8",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": [
							"#c5cbe3",
							"#a1a8c3",
							"#3d4465",
							"#3e4466"
						],
						"shape": [
							"#f0f3ff",
							"#d9dffa",
							"#afb4d4",
							"#646c9a"
						]
					}
				}
			};
		</script>

		<script src="{{asset('plugins/general/jquery/dist/jquery.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/perfect-scrollbar/dist/perfect-scrollbar.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/toastr/build/toastr.min.js')}}" type="text/javascript"></script>
        <script>
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            var success = "{{ Session::has('success') ? Session::get('success') : null }}";
            var error = "{{ Session::has('error') ? Session::get('error') : null }}"
            var warning = "{{ Session::has('warning') ? Session::get('warning') : null }}";
            var info = "{{ Session::has('info') ? Session::get('info') : null }}";
            if(success){
                toastr.success("Success!", success);
            }
            if(error){
                toastr.error("Error!", error);
            }
            if(warning){
                toastr.warning("Warning!", warning);
            }
            if(info){
                toastr.info("Pay Attention!", info);
            }
            function copySelectedField($field_id) {
                var copyText = document.getElementById($field_id);
                copyText.select();
                copyText.setSelectionRange(0, 99999)
                document.execCommand("copy");
                toastr.success('Success!', "Copied the text ");
            }
            function downloadImg($url) {
                var link =document.getElementById($url).val;
                document.getElementById('my_iframe').src = link;
            }
        </script>
	</body>
</html>