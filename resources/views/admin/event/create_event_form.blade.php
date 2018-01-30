@extends('admin.layouts.admin')

@section('title', 'Create New Event')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="class-xs-12">
            @include('errors.errorHandler')
            <br />
            {{ Form::open(['action' => 'Admin\EventController@createEvent', 'files' => true, 'method' => 'post', 'class' => 'form-horizontal form-label-left']) }}
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Event Logo <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {{ Form::file('event_logo', ['class' => 'image-upload', 'accept' => 'image/*']) }}
                    </div>
                </div>
                @foreach($locales as $locale)
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Event Name ({{ $locale->name }}) <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ Form::text($locale->code, '', ['class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']) }}
                        </div>
                    </div>
                @endforeach
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="checkbox">
                            <label>
                                {{ Form::checkbox('activate', true, false, ['class' => 'js-switch']) }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Background (Show On App)</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {{ Form::file('background', ['class' => 'image-upload', 'accept' => 'image/*']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Font Color (Show On App)</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input class="form-control col-md-7 col-xs-12" type="color" name="font_color" value="">
                    </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Event Id (Secret Id)</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input class="form-control col-md-7 col-xs-12" type="text" name="event_secret_id" value="" id='event_secret_id'>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <button class="btn btn-primary" type="button" onclick="generateEventId()">Generate</button>
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn-link" href="{{ url()->previous() }}">
                        <button class="btn btn-primary" type="button">Cancel</button>
                    </a>
                    <button type="submit" class="btn btn-success">Create</button>
                  </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>

@endsection

@section('scripts')
    @parent

    {{ Html::script(mix('assets/admin/js/image-upload.js')) }}

    <script type="text/javascript">
            function generateEventId(){
                var string = Math.random().toString(36).substr(2, 10);
                $('#event_secret_id').val(string);
            }
    </script>
@endsection

@section('styles')
    @parent

@endsection
