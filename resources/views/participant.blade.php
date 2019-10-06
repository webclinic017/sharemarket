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



                    <table class="table table-bordered" style="font-size: .7rem;"> 
                      <thead>
                        <tr>
                          <th scope="col">DATE</th>
                          <th scope="col" colspan="3">CLIENT</th>
                          <th scope="col" colspan="3">FII</th>
                          <th scope="col" colspan="3">PRO</th>
                        </tr>
                        <tr>
                          <th scope="row"></th>
                          <th scope="row">FUT INDEX</th>
                          <th scope="row">OPTION CALL</th>
                          <th scope="row">OPTION PUT</th>
                          <th scope="row">FUT INDEX</th>
                          <th scope="row">OPTION CALL</th>
                          <th scope="row">OPTION PUT</th>
                          <th scope="row">FUT INDEX</th>
                          <th scope="row">OPTION CALL</th>
                          <th scope="row">OPTION PUT</th>
                        </tr>
                      </thead>
                      <tbody>
                        @for($i=0; $i<$limit; $i++)
                        <tr>
                          <th scope="row">{{$segmentWiseData['CLIENT'][$i]->date}}</th>
                          <td>{{$segmentWiseData['CLIENT'][$i]->index_fut}}</td>
                          <td>{{$segmentWiseData['CLIENT'][$i]->option_call}}</td>
                          <td>{{$segmentWiseData['CLIENT'][$i]->option_put}}</td>
                          <td>{{$segmentWiseData['FII'][$i]->index_fut}}</td>
                          <td>{{$segmentWiseData['FII'][$i]->option_call}}</td>
                          <td>{{$segmentWiseData['FII'][$i]->option_put}}</td>
                          <td>{{$segmentWiseData['PRO'][$i]->index_fut}}</td>
                          <td>{{$segmentWiseData['PRO'][$i]->option_call}}</td>
                          <td>{{$segmentWiseData['PRO'][$i]->option_put}}</td>
                        </tr>
                        @endfor

                      </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
