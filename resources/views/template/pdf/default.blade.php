<style>
table{ border-collapse:collapse;}
table td{padding:5px;}
.header td{border-bottom:1px solid #e1e8ea;border-top:1px solid #e1e8ea;}
table tr:nth-child(even) {background: #e1e8ea}
.body td{color:#2c2e38;}
</style>
<table width="100%">
    <tr class="header">
        @foreach ($headers as $key => $head)
        <td>{{$head}}</td>
        @endforeach
    </tr>
    @foreach ($records as $key => $record)
    <tr class="body">
        @foreach ($record as $index => $show)
        <td>{{$show}}</td>
        @endforeach
    </tr>
    @endforeach
</table>