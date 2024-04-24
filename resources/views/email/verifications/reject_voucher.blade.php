
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
                        Purchase Voucher Rejected
                    </h2>
                </td>
            </tr>
        </table>
    </div>
    <div class="body-box">
        <div class="body-container">

            <div class="message">
                <b>Hi &nbsp; {{ $user->name ?? 'Mysterio'}},</b><br/><br/>
                
                Your voucher <b>"{{ $voucher->voucher_code ?? 'Mysterio'}}"</b> was denied. 
                @if($message)
                <br/>
                Message: <br/>
                "{{ $message }}"
                @endif
            </div>
        </div>
    </div>
    @endsection