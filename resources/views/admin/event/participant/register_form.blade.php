@extends('admin.layouts.admin')

@section('title', '')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Add New Participant <small>Fill up all the fields</small></h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                  @include('errors.errorHandler')
                <br />
                {{ Form::open(['action' => 'Admin\ParticipantController@registerParticipant', 'method' => 'post', 'class' => 'form-horizontal form-label-left']) }}
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Full Name <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {{ Form::text('name', '', ['class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {{ Form::email('email', '', ['class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile_number">Mobile Number <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {{ Form::text('mobile_number', '', ['class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="identity_passport">IC / Passport <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {{ Form::text('identity_passport', '', ['class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Gender <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <label class="control-label">
                              M: {{ Form::radio('gender', 'male', true, ['class' => 'flat', 'required' => 'required']) }}
                              F: {{ Form::radio('gender', 'female', false, ['class' => 'flat']) }}
                          </label>
                      </div>
                  </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Pre-registration <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="checkbox">
                              <label>
                                  {{ Form::checkbox('pre_registration', true, false, ['class' => 'js-switch']) }}
                              </label>
                            </div>
                      </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Event <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ Form::select('event', $events) }}
                        </div>
                    </div>

                    @foreach($fields as $field)
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{ $field->getAllLocalesString() }}
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              {{ Form::text($field->getFormKey(), '', ['class' => 'form-control col-md-7 col-xs-12']) }}
                          </div>
                        </div>
                    @endforeach

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
