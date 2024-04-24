
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
                        We need to verify your email
                    </h2>
                </td>
            </tr>
        </table>
    </div>
    <div class="body-box">
        <div class="body-container">

            <br/>

            <div class="message">
                <b>Hi &nbsp; {{ $user->name ?? 'Mysterio'}},</b><br/><br/>
                
                To get started in your CPD journey and starting earning those units,  
                we need to verify your email address account.<br/><br/>

                Please click the VERIFY button to confirm your email. <br/>
            </div>
        </div>
        <div class="body-footer center ">
            <a href="<?=config('app.link').'/auth/public/verify/'.($user->id ?? 'missing')?>" target="_blank" class="button center_div">VERIFY</a>
        </div>
    </div>
    @endsection