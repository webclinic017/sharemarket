@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4>Participant Wise Open Interest</h4>  </div>

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
    </div>
</div>
@endsection
