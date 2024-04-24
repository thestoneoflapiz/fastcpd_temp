
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
                    Invitation to manage {{ $provider->name ?? config('app.name')}} account
                    </h2>
                </td>
            </tr>
        </table>
    </div>
    <div class="body-box">
        <div class="body-container">

            <div class="message">
            
                <b>Hi &nbsp; {{ $user->name ?? 'Mysterio'}},</b><br/><br/>
                You've been invited to be access and manage {{ $provider->name ?? config('app.name')}}. 
                <div class="center">
                    <img alt="FastCPD Provider Logo <?=$provider->name ?? ""?>" src="{{ $provider->logo ?? URL::asset('img/system/icon-1.png')}}" width="55"><br/>
                    {{ $provider->name ?? config('app.name')}}<br/><br/>
                </div>
                

                To access the provider portal, we need you to accept this invitation.<br/><br/>

                Please click the ACCEPT button to confirm your access and officially be a co-provider.<br/><br/>

            </div>
        </div>
        <div class="body-footer center ">
            <a href="{{ URL::to('/provider/user/accept') }}/{{ $provider->url }}/{{ $user->id }}" target="_blank" class="button center_div">ACCEPT</a>
        </div>
    </div>
    @endsection