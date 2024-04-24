
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
                        Accreditor Feedback 
                    </h2>
                </td>
            </tr>
        </table>
    </div>
    <div class="body-box">
        <div class="body-container">
            <table width="100%">
                <tr>
                    <td rowspan="3" style="width:70px;"><img alt="FastCPD Company Logo" src="{{ $provider->logo ?? URL::asset('img/sample/noimage.png')}}" width="55"></td>
                </tr>
                <tr>
                    <td style="font-size:18px;font-weight:500;">From {{ $provider->name ?? config('app.name')}}</td>
                    <td style="color:#5d78ff;font-size:20px;font-weight:800;text-align:right;">{{ date('M. d, Y') }}</td>
                </tr>
                <tr>
                    <td style="font-size:13px;color:#5a78c9 !important;">noreply@fastcpd.com</td>
                    <td style="font-size:13px;text-align:right;">Date</td>
                </tr>
            </table>

            <br/>

            <div class="message">
                <b>Hi &nbsp; {{ $provider->name ?? 'Mysterio'}},</b><br/><br/>
                
                Your {{ $data_record->type }} <b>"{{ $data_record->title ?? 'Mysterio'}}"</b> has been approved by the assigned accreditor. CPD Units and Program Accreditation Number is currently applied and can be reviewed in <b>{{ $data_record->type }} creation</b> page. <br/><br/>
                Reminder: <b>Publish {{ ucwords($data_record->type) }}</b> page is already enabled in <b>"{{ $data_record->title ?? 'Mysterio'}}"</b> {{ $data_record->type }} creation page
            </div>
        </div>
        <div class="body-footer center ">
            <a href="<?=URL::to('/provider/session/'.($provider->id ?? 0))?>" target="_blank" class="button center_div">VISIT PORTAL</a>
        </div>
    </div>
    @endsection