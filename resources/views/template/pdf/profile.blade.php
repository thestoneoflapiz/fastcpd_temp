<style>
    table{ border-collapse:collapse;}
    table td{padding:5px;font-size:12px;}
    table th{padding:10px;font-size:12px;}
    .header td{border-bottom:1px solid #e1e8ea;border-top:1px solid #e1e8ea;}
    table tr:nth-child(even) {background: #e1e8ea}
    .body td{color:#2c2e38;}

    img{width:90px;height:90px;}
    .center{text-align:center;}
</style>

@if($type=="provider")
<br/>
<h3>{{ $data->name }}</h3>
<table width="100%">
    <tr>
        <td rowspan="3"><img src="{{ $data->image }}"/></td>
        <td><b>URL<b></td>
        <td>{{ $data->link }}</td>
    </tr>
    <tr>
        <td><b>Accreditation<br/>No.</b></td>
        <td>{{ $data->accreditation_number }}</td>
        <td><b>Expiration</b></td>
        <td>{{ date("M. d, Y", strtotime($data->accreditation_expiration_date)) }}</td>
    </tr>
</table>
<table width="100%">
    <tr><th colspan="3" class="center">Targeted Professions</th></tr>
    @if($professions!=null)
    <?php $count = 0;?>
        @foreach($professions as $pr)
            @if($count==0)
            <tr>
            <td>{{$pr->title}}</td>
            @elseif($count==3)
            <?php $count = 0;?>
            <td>{{$pr->title}}</td>
            </tr>
            @else
            <td>{{$pr->title}}</td>
            @endif
        <?php $count++;?>
        @endforeach
    @else
    <tr><td>NONE</td></tr>
    @endif
    <tr><th colspan="3" class="center">Social Links</th></tr>
    <tr>
        <td>Website:<br>{{ $data->website }}</td>
        <td>Facebook:<br>{{ $data->facebook }}</td>
        <td>LinkedIn:<br/>{{ $data->linkedin }}</td>
    </tr>
    <tr><th colspan="3" class="center">Headline</th></tr>
    <tr><td colspan="3" class="center">{{ $data->headline }}</td></tr>
    <tr><th colspan="3" class="center">About</th></tr>
    <tr><td colspan="3"><?= htmlspecialchars_decode($data->about) ?></td></tr>
</table>
@elseif($type=="profile")
<br/>
<h3>{{ $data->name }}</h3>
<table width="100%">
    <tr>
        <td rowspan="3"><img src="{{ $data->image }}"/></td>
        <td><b>URL<b></td>
        <td>{{ $data->link }}</td>
    </tr>
    <tr><th colspan="3" class="center">Social Links</th></tr>
    <tr>
        <td>Website:<br>{{ $data->website }}</td>
        <td>Facebook:<br>{{ $data->facebook }}</td>
        <td>LinkedIn:<br/>{{ $data->linkedin }}</td>
    </tr>
</table>
<table width="100%">
    <tr><th colspan="3" class="center">Targeted Professions</th></tr>
    @if($professions!=null)
    <?php $count = 0;?>
        @foreach($professions as $pr)
            @if($count==0)
            <tr>
            <td>{{ $pr->prc_no }} :: {{$pr->title}}</td>
            @elseif($count==3)
            <?php $count = 0;?>
            <td>{{ $pr->prc_no }} :: {{$pr->title}}</td>
            </tr>
            @else
            <td>{{ $pr->prc_no }} :: {{$pr->title}}</td>
            @endif
        <?php $count++;?>
        @endforeach
    @else
    <tr><td>NONE</td></tr>
    @endif
    <tr><th colspan="3" class="center">Headline</th></tr>
    <tr><td colspan="3" class="center">{{ $data->headline }}</td></tr>
    <tr><th colspan="3" class="center">About</th></tr>
    <tr><td colspan="3"><?= htmlspecialchars_decode($data->about) ?></td></tr>
    <tr><th colspan="3" class="center">Resume</th></tr>
    <tr><td colspan="3" class="center">Link :: {{ $data->resume ?? "none" }}</td></tr>
    <tr><th colspan="3" class="center">PRC ID</th></tr>
    @if((json_decode($data->prc_certificate)))
    <?php $count = 0;?>
        @foreach(json_decode($data->prc_certificate) as $pr)
            @if($count==0)
            <tr>
            <td>Link :: {{ $pr ?? "none" }}</td>
            @elseif($count==1)
            <?php $count = 0;?>
            <td>Link :: {{ $pr ?? "none" }}</td>
            </tr>
            @else
            <td>Link :: {{ $pr ?? "none" }}</td>
            @endif
        <?php $count++;?>
        @endforeach
    @else
    <tr><td>Link :: none</td></tr>
    @endif
</table>
@else
<h1>Module not Found</h1>
@endif