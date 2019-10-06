@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4>Jabardast IV</h4>  </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-bordered" style="font-size: .7rem;">
                      <thead>
                        <tr>
                          <th scope="col">DATE</th>
                          <th scope="col">Symbol</th>
                          <th scope="col">expirydate</th>
                          <th scope="col">strikeprice</th>
                          <th scope="col">callchnginoi</th>
                          <th scope="col">putchnginoi</th>
                          <th scope="col">callltp</th>
                          <th scope="col">putltp</th>
                          <th scope="col">callIv</th>
                          <th scope="col">putiv</th>
                          <th scope="col">IV Ratio</th>
                          <th scope="col">OCI ID</th>
                        </tr>

                      </thead>
                      <tbody>
                        @foreach($action as $act)
                        <tr>
                          <th scope="row">{{$act->DATE}}</th>
                          <td>{{$act->symbol}}</td>
                          <td>{{$act->expirydate}}</td>
                          <td>{{$act->strikeprice}}</td>
                          <td>{{$act->callchnginoi}}</td>
                          <td>{{$act->putchnginoi}}</td>
                          <td>{{$act->callltp}}</td>
                          <td>{{$act->putltp}}</td>
                          <td>{{$act->calliv}}</td>
                          <td>{{$act->putiv}}</td>
                          <td>{{$act->ivratio}}</td>
                          <td>{{$act->oce_id}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
