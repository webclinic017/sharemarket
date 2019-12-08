@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Nifty 50 @if($dataLivePrice[1]['change'] > 0) <span
                                class="badge badge-success">{{$dataLivePrice[1]['lastPrice']}}</span>@endif
                        @if($dataLivePrice[1]['change'] < 0) <span
                                class="badge badge-danger">{{$dataLivePrice[1]['lastPrice']}}</span>@endif
                    </h4>
                    @if(isset($dataLivePrice))
                        @if($dataLivePrice[1]['change'] > 0) <h5><span
                                    class="badge badge-success">{{$dataLivePrice[1]['change'] }} || {{ $dataLivePrice[1]['pChange']}}%</span>
                        </h5>
                        @elseif($pcr >= 0.95 && $pcr <= 1.09)<h5><span
                                    class="badge badge-success">{{$dataLivePrice[1]['change'] }} || {{ $dataLivePrice[1]['pChange']}}%</span>
                        </h5>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Imp Ratios &nbsp;<span
                                class="badge badge-success"> PE {{$lastRec->pe}}</span>
                    <span class="badge badge-success"> PB {{$lastRec->pb}}</span>
                    <span class="badge badge-success"> Div Yield {{$lastRec->divyield}}</span></h4>
{{--                    <div class="row">--}}
{{--                        <div class="col-md-2">--}}
{{--                            <h4><span class="badge badge-danger">PE {{$pcr}}<br><br>0.6 to 0.85</span></h4>--}}
{{--                        </div>--}}
{{--                        <div class="offset-md-2 col-md-2">--}}
{{--                            <h4><span class="badge badge-warning">PB<br><br>0.95 to 1.05</span></h4>--}}
{{--                        </div>--}}
{{--                        <div class="offset-md-2 col-md-2">--}}
{{--                            <h4><span class="badge badge-success">Div Yield<br><br>1.10 to 1.3</span></h4>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">PCR Ratio Range &nbsp;
                        @if(isset($pcr))
                            @if($pcr >1.10) <span class="badge badge-success"> Nifty {{$pcr}}</span>
                            @elseif($pcr >= 0.95 && $pcr <= 1.09)<span
                                    class="badge badge-warning"> Nifty {{$pcr}}</span>
                            @elseif($pcr < 0.95)<h4><span class="badge badge-danger"> Nifty {{$pcr}}</span></h4>
                            @endif
                        @endif
                    </h4>
                    <div class="row">
                        <div class="col-md-2">
                            <h4><span class="badge badge-danger">Bearish <br><br>0.6 to 0.85</span></h4>
                        </div>
                        <div class="offset-md-2 col-md-2">
                            <h4><span class="badge badge-warning">Sideway<br><br>0.95 to 1.05</span></h4>
                        </div>
                        <div class="offset-md-2 col-md-2">
                            <h4><span class="badge badge-success">Bullish<br><br>1.10 to 1.3</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <table class="table">
                    <tr>
                        <th>Client Type</th>
                        <th>Index Future</th>
                        <th>Index Call Option</th>
                        <th>Index Put Option</th>
                        <th>Stock Future</th>
                    </tr>
                    @foreach($mood as $type => $md)
                        <tr>
                            <th>{{$type}}</th>
                            <td>{{$md['indexFuture']}}</td>
                            <td>{{$md['indexOptionCall']}}</td>
                            <td>{{$md['indexOptionPut']}}</td>
                            <td>{{$md['stockFuture']}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="col-md-5">
                <table class="table" style="font-size: .8rem;">
                    <tr>
                        <th colspan="5">Trading activity on NSE,BSE and MSEI in Capital Market Segment<br/>(In Rs.
                            Crores)
                        </th>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Buy Value</th>
                        <th>Sell Value</th>
                        <th>Net Value</th>
                    </tr>

                    @foreach($partipantData as $type => $md)
                        <tr>
                            <td>{{$md[0]}}</td>
                            <td>{{$md[1]}}</td>
                            <td>{{$md[2]}}</td>
                            <td>{{$md[3]}}</td>
                            <td>{{$md[4]}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-primary" role="alert">
                    <h3>TOP 20 highest Cumulative Open Interest Positions<h3/>
                </div>
            </div>
            <div class="col-md-8">
                <table class="table">
                    <tr>
                        <th>Date</th>
                        <th>Symbol</th>
                        <th>open_interest</th>
                    </tr>
                    @if(isset($avgOi))
                        @foreach($avgOi as $oi)
                            <tr>
                                <th>{{$oi['date']}}</th>
                                <td>{{$oi['symbol']}}</td>
                                <td>{{$oi['open_interest']}}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>

@endsection
