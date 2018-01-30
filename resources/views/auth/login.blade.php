@extends('auth.layouts.auth')

@section('body_class', 'login')

@section('content')
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <img class="logo" src="{{ URL::to('/') }}/assets/admin/img/myevent_logo.png" alt="Logo">

                    {{ Form::open(['route' => 'login']) }}
                        <h1>Admin Login</h1>

                        <div>
                            <input id="username" type="text" class="form-control" name="username" value="{{ old('email') }}"
                                   placeholder="Username" required autofocus>
                        </div>
                        <div>
                            <input id="password" type="password" class="form-control" name="password"
                                   placeholder="Password" required>
                        </div>
                        <div class="checkbox al_left">
                            <label>
                                <input type="checkbox"
                                       name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                            </label>
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
                        </div>

                        <div class="separator">

                        </div>

                    {{ Form::close() }}
                </section>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    @parent

    {{ Html::style(mix('assets/auth/css/login.css')) }}
@endsection
