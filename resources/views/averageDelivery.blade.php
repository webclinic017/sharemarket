@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4>Higher Delivery than XX days average </h4>

                        <form>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search" name="stockName">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="submit">
                                        <!--i class="glyphicon glyphicon-search"></i-->GO
                                    </button>
                                </div>
                            </div>
                        </form>


                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <table class="table">
                            <tr>
                                <th>Date</th>
                                <th>Symbol</th>
                                <th>Delivery %</th>
                                <th>Volume</th>
                                <th>FnO</th>
                                <th>FnO Watchlist</th>
                            </tr>
                            @if(isset($avgDelivery))
                                @foreach($avgDelivery as $oi)
                                    <tr>
                                        <th>{{$oi->date}}</th>
                                        <td>{{$oi->symbol}}</td>
                                        <td>{{$oi->per_delqty_to_trdqty}}</td>
                                        <td>{{$oi->total_traded_qty}}</td>
                                        <?php $fnoData = \App\Model\OpenInterest::where('date', $oi->date)->
                                        where('symbol',$oi->symbol)->pluck('watchlist', 'symbol')->toArray()?>
                                        <td>@if(array_key_exists($oi->symbol, $fnoData))
                                                Y @else N @endif</td>
                                        <td>@if(array_key_exists($oi->symbol, $fnoData) && !empty($fno[$oi->symbol]))
                                                Y @endif</td>
                                    </tr>
                                @endforeach
                                    {{ $avgDelivery->links() }}
                            @endif
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
