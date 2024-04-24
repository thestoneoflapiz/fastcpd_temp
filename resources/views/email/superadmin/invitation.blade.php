
    @extends('template.mail')
    @section('styles')
    <style>
        .header-bg{width:100% !important;padding-top:20px;border-top-left-radius:20px;border-top-right-radius:20px;min-height:70px;background-color:#2a7de9; }
        .button{color:white !important;border-radius:5px;background-color:#2a7de9;padding:10px 50px;font-size:20px;font-weight:600;width: 80%;display: block;}
        .right{text-align: right;}
        .left{text-align: left;}
        .center{text-align: center;}
        .center_div{margin: auto;width: 70%;}
        .justify{text-align:justify;}
        del{color:red}
        .password-box{padding:10px;margin:5px;border-radius:5px;text-align:center;background:#667f7a;color:white;}
    </style>
    @endsection
    @section('content')
    <div class="center" style="margin: 30px;">
        <img  alt="FastCPD Company Logo" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/logo-1.png" width="150">
    </div>

    <div class="header-box">
        <table width="100%;" class="header-bg">
            <tr>
                <td>
                    <h2 class="h2-style">
                        Superadmin Invitiation
                    </h2>
                </td>
            </tr>
        </table>
    </div>
    <div class="body-box">
        <div class="body-container">

            <div class="message">
                <b>Hi &nbsp; {{ $user->name ?? 'Mysterio'}},</b><br/><br/>
                
                You've been invited to be a superadmin user for <b><a href="<?=config('app.link')?>">www.FastCPD.com</a></b>.<br/>
                To <b>activate</b> your account, please login by using your default password as displayed below. <br/>
                <div class="password-box">
                    {{ $password ?? 'PASSWORD NOT FOUND! PLEASE CONTACT OUR SUPPORT'}}
                </div>
                Please click the <b>LOGIN</b> button to redirect you to our website application.
            </div>
        </div>
        <div class="body-footer center ">
            <a href="<?=config('app.link')?>" target="_blank" class="button center_div">LOGIN</a>
        </div>
    </div>
    @endsection