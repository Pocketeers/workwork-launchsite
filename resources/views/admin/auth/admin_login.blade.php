@extends('layouts.app')
@section('content')
<div class="panel-ww-login panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Login</div>
    <div class="panel-body">

        @if (session()->has('flash_message'))
            <div class="flash">
                @include('messages.flash')
            </div>
        @endif

        <form role="form" method="POST" action="{{ url('/a/login') }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label class="control-label">Email Address</label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="control-label">Password</label>
                <input type="password" class="form-control" name="password">

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block btn-ww-md">
                    <i class="fa fa-btn fa-sign-in"></i>Login
                </button>
                <div class="text-center"><a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot password?</a></div>
            </div>
        </form>
    </div>
</div>
@endsection
