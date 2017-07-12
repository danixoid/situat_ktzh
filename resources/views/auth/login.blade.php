@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{!! trans('interface.login') !!}</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('iin') ? ' has-error' : '' }}">
                            <label for="iin" class="col-md-4 control-label">{!! trans('interface.iin') !!}</label>

                            <div class="col-md-6">
                                <input id="iin" type="number" class="form-control" name="iin" value="{{ old('iin') }}" required autofocus>

                                @if ($errors->has('iin'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('iin') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? 'errors' : '' }}">
                            <label for="password" class="col-md-4 control-label">{!! trans('interface.password') !!}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {!! trans('interface.remember_me') !!}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {!! trans('interface.login') !!}
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {!! trans('interface.forgot_password') !!}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
