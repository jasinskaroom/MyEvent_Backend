@extends('admin.layouts.admin')

@section('title', 'Create Banner')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="class-xs-12">
            @include('errors.errorHandler')
            <br />
            {{ Form::open(['url' => action('Admin\EventController@createEventBanner', ['eventId' => $event->id]), 'files' => true, 'method' => 'post', 'class' => 'form-horizontal form-label-left']) }}
                @foreach($locales as $locale)
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Banner Name ({{ $locale->name }}) <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ Form::text('name_'.$locale->code, '', ['class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Banner Image ({{ $locale->name }}) <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ Form::file($locale->code, ['class' => 'image-upload', 'accept' => 'image/*', 'required' => 'required']) }}
                        </div>
                    </div>

                    <br />
                @endforeach
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

    {{ Html::script(mix('assets/admin/js/banner-form.js')) }}
    {{ Html::script(mix('assets/admin/js/image-upload.js')) }}
@endsection

@section('styles')
    @parent

    {{ Html::style(mix('assets/admin/css/banner-form.css')) }}
@endsection
