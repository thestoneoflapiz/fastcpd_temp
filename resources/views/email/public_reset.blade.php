
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
                        Password Reset Link
                    </h2>
                </td>
            </tr>
        </table>
    </div>
    <div class="body-box">
        <div class="body-container">

            <div class="message">
                <b>Hi &nbsp; {{ $user->name ?? 'Mysterio'}},</b><br/><br/>
                
                You have requested to reset your password to your account in FastCPD</b>. 
                Please click the <b>RESET</b> button to create new password for your account.<br/>
                If you did not make this transaction, please contact help@fastcpd.com
            </div>
        </div>
        <div class="body-footer center">
            <a href="<?=config('app.link').'/auth/reset/password/'.($user->id ?? 'missing')?>" target="_blank" class="button center_div">RESET</a>
        </div>
    </div>
    @endsection