@extends('admin.layouts.admin')

@section('title', 'No Live Event')

@section('content')

    <div class="pull-right">
        <a class="btn-link" href="{{ action('Admin\EventController@showCreateEventForm') }}">
            <button class="btn btn-sm btn-info" type="button">Add Event</button>
        </a>
    </div>

    <div class="clearfix"></div>

    <div class="row">

    </div>

@endsection

@section('scripts')
    @parent

@endsection

@section('styles')
    @parent

@endsection
