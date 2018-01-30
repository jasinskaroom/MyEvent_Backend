@extends('admin.layouts.admin')

@section('title', 'Create New Game')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="class-xs-12">
            @include('errors.errorHandler')
            <br />
            {{ Form::open(['url' => action('Admin\GameController@createInputGame', ['stageId' => $stage->id]), 'method' => 'post', 'class' => 'form-horizontal form-label-left']) }}
                @foreach($locales as $locale)
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Game Name ({{ $locale->name }}) <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ Form::text('game_'.$locale->code, '', ['class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']) }}
                        </div>
                    </div>
                @endforeach
              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Max Number of Scans <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {{ Form::number('number_of_scan', 1, ['class' => 'form-control col-md-7 col-xs-12', 'required' => 'required','min'=>'1']) }}
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

@endsection

@section('styles')
    @parent

@endsection
