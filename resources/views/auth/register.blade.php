@extends('auth.layouts.auth')

@section('body_class', 'register')

@section('content')
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    {{ Form::open(['route' => 'login']) }}
                        <h1>Login</h1>

                        <div>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                                   placeholder="Email" required autofocus>
                        </div>
                        <div>
                            <input id="password" type="password" class="form-control" name="password"
                                   placeholder="Password" required>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (!$errors->isEmpty())
                            <div class="alert alert-danger" role="alert">
                                {!! $errors->first() !!}
                            </div>
                        @endif

                        <div>
                            <button class="btn btn-default submit" type="submit">Login</button>
                            <a class="reset_pass" href="{{ route('password.request') }}">
                                Reset Password
                            </a>
                        </div>

                        @if(config('auth.users.registration'))
                            <div class="separator">
                                <p class="change_link">Ok
                                    <a href="{{ route('register') }}" class="to_register"> Register Now </a>
                                </p>

                                <div class="clearfix"></div>
                                <br/>
                            </div>
                        @endif
                    {{ Form::close() }}
                </section>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    @parent

    {{ Html::style(mix('assets/auth/css/register.css')) }}
@endsection
