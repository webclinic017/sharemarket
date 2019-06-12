@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST"
                              files="true" action="" id="adderroer">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="col-md-12 left-col">

                                    <div class="form-group">
                                        <label for="voyage" class="col-md-4 control-label">Current Stock Price</label>
                                        <div class="col-md-6 input-group">
                                            <input type="text" name="stockPrice" class="form-control">
                                        </div>
                                    </div>


                                    <div class="form-group {{ $errors->has('callIv') ? ' has-error' : '' }}">
                                        <label for="booking" class="col-md-4 control-label">Call Implied
                                            Volatility</label>
                                        <div class="input-group col-md-6">
                                            <input type="text" name="callIv" class="form-control">
                                            @if ($errors->has('callIv'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('callIv') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="customer" class="col-md-4 control-label">Put Implied
                                            Volatility</label>
                                        <div class="input-group col-md-6">
                                            <input type="text" name="putIv" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('daysToExpiration') ? ' has-error' : '' }}">
                                        <label for="booking" class="col-md-4 control-label">Days to Expiration</label>
                                        <div class="input-group col-md-6">
                                            <input type="text" name="callIv" class="form-control">
                                            @if ($errors->has('daysToExpiration'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('daysToExpiration') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('putSideValue') ? ' has-error' : '' }}">
                                        <label for="task" class="col-md-4 control-label">Strike Price < CMP (Put
                                            side)</label>
                                        <div class="input-group col-md-6">
                                            <input type="text" name="putSideValue" class="form-control">
                                            @if ($errors->has('putSideValue'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('putSideValue') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('callSideValue') ? ' has-error' : '' }}">
                                        <label for="task" class="col-md-4 control-label">Strike Price > CMP (Call
                                            side)</label>
                                        <div class="input-group col-md-6">
                                            <input type="text" name="putSideValue" class="form-control">
                                            @if ($errors->has('callSideValue'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('callSideValue') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('date') ? ' has-error' : '' }}">
                                        <label for="date" class="col-md-4 control-label">Date</label>
                                        <div class="input-group date col-md-6" id="date">
                                            <input data-format="yyyy-MM-dd" type='text' name='date' readonly="readonly">
                                            <span class="input-group-addon add-on">
                                          <i data-date-icon="fa fa-calendar">
                                          </i>
                                        </span><br><br>
                                        </div>
                                        @if ($errors->has('error_date'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('error_date') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="clear:both;"></div>
                            <div class="box-footer pull-center">
                                <div class="col-md-8 col-md-push-4">
                                    <button type="submit" class="btn btn-primary pull-right">
                                        Submit
                                    </button>
                                    <a href="{{}}" class="btn btn-primary">Cancel</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection