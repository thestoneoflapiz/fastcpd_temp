<html lang="en">
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="Updates and statistics">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
		<!--end::Fonts -->
		<style>
		
			html, body {font-family:Poppins !important;color:#74788d!important;}
			a{color: #5a78c9 !important; text-decoration:none;}
			.body-positive{padding-top:3%;background-color:#f2f3f8;}
			.email-box{margin:5%;width:90%;border-radius:5px;overflow:hidden;-webkit-box-shadow: 10px 5px 10px rgba(0, 0, 0, 0.15);-moz-box-shadow: 10px 5px 10px rgba(0, 0, 0, 0.15);box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.05);}
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
			.button-default{color:white !important;background-color:white;padding:20px;font-size:20px;font-weight:600;}

			.btn {
				font-size: 16px;
				padding: 10px 24px;
				margin-bottom: 0;

				display: inline-block;
				text-decoration: none;
				text-align: center;
				white-space: nowrap;
				vertical-align: middle;
				-ms-touch-action: manipulation;
				touch-action: manipulation;
				cursor: pointer;
				-webkit-user-select: none;
				-moz-user-select: none;
				-ms-user-select: none;
				user-select: none;
				background-image: none;
				border: 1px solid transparent;
			}
			.btn:focus,
			.btn:active:focus {
				outline: thin dotted;
				outline: 5px auto -webkit-focus-ring-color;
				outline-offset: -2px;
			}
			.btn:hover,
			.btn:focus {
				color: #333;
				text-decoration: none;
			}
			.btn:active {
				background-image: none;
				outline: 0;
				-webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
				box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
			}

			/* default
			---------------------------- */
			.btn-default {
				color: #2B7DE9;
				background-color: #fff;
				border-color: #2B7DE9;
			}
			.btn-default:focus {
				color: #2B7DE9;
				background-color: #e6e6e6;
				border-color: #2B7DE9;
			}
			.btn-default:hover {
				color: #2B7DE9;
				background-color: #e6e6e6;
				border-color: #2B7DE9;
			}
			.btn-default:active {
				color: #2B7DE9;
				background-color: #e6e6e6;
				border-color: #2B7DE9;
			}

			.header-bg{width:100% !important;padding-top:20px;border-top-left-radius:20px;border-top-right-radius:20px;min-height:70px;background-color:#2a7de9; }
			.button{color:white !important;border-radius:5px;background-color:#2a7de9;padding:10px 50px;font-size:20px;font-weight:600;width: 80%;display: block;}
			.right{text-align: right;}
			.left{text-align: left;}
			.center{text-align: center;}
			.center_div{margin: auto;width: 70%;}
			.justify{text-align:justify;}
			del{color:red}

			.button:hover{background-color:#29a58a;color:white !important;}
			.h2-style{font-size:25px;font-weight:600;color:white;}

			
		</style>
		@yield('styles')				
	</head>
	<!-- end::Head -->
	<body class="body-positive">
		<div class="email-box">
			@yield('content')				
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
	</body>
</html>