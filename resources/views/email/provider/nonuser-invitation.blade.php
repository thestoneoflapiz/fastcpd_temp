
    @extends('template.mail')
    @section('styles')
    <style>
    </style>
    @endsection
    @section('content')

    <div class="center" style="margin: 30px;">
        <img alt="FastCPD Company Logo" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/logo-1.png" width="150">
    </div>
    
    <div class="header-box">
        <table width="100%;" class="header-bg">
            <tr>
                <td>
                    <h2 class="h2-style">
                        FastCPD SignUp Invitiation
                    </h2>
                </td>
            </tr>
        </table>
    </div>
    <div class="body-box">
        <div class="body-container">

            <br/>

            <div class="message">
                <b>Hi,</b><br/><br/>
                
                <b>{{ $provider->name }} has invited you to sign up in FastCPD.com</b><br/><br/>

                FastCPD lets professionals create video courses for other professionals while earning CPD units. You have been invited to become an instructor for a course.<br/><br/>

                View courses offered for your profession now. <br/> 
            </div>
        </div>
        <div class="body-footer center">
            <a href="{{ URL::to('/') }}" target="_blank" class="button center_div">Sign Up</a>
        </div>
    </div>
    @endsection