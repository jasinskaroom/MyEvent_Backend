@extends('admin.layouts.admin')

@section('title', 'Edit Banner')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="class-xs-12">
            @include('errors.errorHandler')
            <br />
            {{ Form::open(['url' => action('Admin\EventController@updateEventBanner', ['id' => $banner->id]), 'files' => true, 'method' => 'put', 'class' => 'form-horizontal form-label-left']) }}
                @foreach($locales as $locale)
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Banner Name ({{ $locale->name }}) <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ Form::text('name_'.$locale->code, $banner->translate($locale->code)->name, ['class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Banner ({{ $locale->name }}) <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ Form::file($locale->code, ['class' => 'image-upload', 'data-initial-preview' => $banner->getImageUrl($locale->code), 'accept' => 'image/*']) }}
                        </div>
                    </div>

                    <br / />
                @endforeach
                <div class="ln_solid"></div>
                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn-link" href="{{ url()->previous() }}">
                        <button class="btn btn-primary" type="button">Cancel</button>
                    </a>
                    <button type="submit" class="btn btn-success">Update</button>
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
