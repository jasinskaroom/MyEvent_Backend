@extends('auth.layouts.auth')

@section('body_class', 'password_email')

@section('content')

@endsection

@section('styles')
    @parent

    {{ Html::style(mix('assets/auth/css/passwords.css')) }}
@endsection
