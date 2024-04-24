
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
                        Invitation to become an instructor for {{ $provider->name ?? config('app.name')}}
                    </h2>
                </td>
            </tr>
        </table>
    </div>
    <div class="body-box">
        <div class="body-container">

            <div class="message">
                <b>Hi &nbsp; {{ $user->name ?? 'Mysterio'}},</b><br/><br/>
                
                You've been invited to be an instructor for <b>{{ $provider->name ?? config('app.name')  }}</b>.<br/><br/>
                Before you can access the instructor portal and start making courses, please click the ACCEPT button to 
                confirm your account as an official Fast CPD Instructor.
            </div>
        </div>
        <div class="body-footer center ">
            <a href="{{ URL::to('/provider/instructor/accept') }}/{{ $provider->url ?? config('app.link') }}/{{ $user->id ?? 0}}" target="_blank" class="button center_div">ACCEPT</a>
        </div>
    </div>
    @endsection