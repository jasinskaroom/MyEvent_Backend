@extends('admin.layouts.admin')

@section('title', '')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Reward {{ $participant->name }}</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                  @include('errors.errorHandler')
                <br />
                {{ Form::open(['url' => action('Admin\ParticipantController@rewardParticipant', ['id' => $participant->id]), 'method' => 'post', 'class' => 'form-horizontal form-label-left']) }}
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Event <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {{ Form::text('event', $participant->event->getAllLocalesString(), ['class' => 'form-control col-md-7 col-xs-12', 'disabled' => 'true', 'required' => 'required']) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Participant Name <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {{ Form::text('name', $participant->name, ['class' => 'form-control col-md-7 col-xs-12', 'disabled' => 'true', 'required' => 'required']) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Score Available <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {{ Form::text('score', $participant->score, ['class' => 'form-control col-md-7 col-xs-12', 'disabled' => 'true', 'required' => 'required']) }}
                      </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Reward with Gifts <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ Form::select('gifts[]', $gifts, null, ['required' => 'required', 'multiple' => 'multiple']) }}
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn-link" href="{{ url()->previous() }}">
                            <button class="btn btn-primary" type="button">Cancel</button>
                        </a>
                        <button type="submit" class="btn btn-success">Submit</button>
                      </div>
                    </div>
                {{ Form::close() }}
              </div>
            </div>
          </div>
    </div>

@endsection

@section('scripts')
    @parent

@endsection

@section('styles')
    @parent

    {{ Html::style(mix('assets/admin/css/registration-form.css')) }}
@endsection
