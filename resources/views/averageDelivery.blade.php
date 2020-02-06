@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4>Higher Delivery than XX days average </h4></div>

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
                            </tr>
                            @if(isset($avgDelivery))
                                @foreach($avgDelivery as $oi)
                                    <tr>
                                        <th>{{$oi->date}}</th>
                                        <td>{{$oi->symbol}}</td>
                                        <td>{{$oi->per_delqty_to_trdqty}}</td>
                                        <td>{{$oi->total_traded_qty}}</td>
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
