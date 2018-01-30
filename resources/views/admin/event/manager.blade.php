@extends('admin.layouts.admin')

@section('title', '')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                @if(!$manager)
                <h2>Add a Manager <small>Fill up all the fields</small></h2>
                @else
                <h2>{{$manager->name}}</h2>
                @endif
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                  @include('errors.errorHandler')
                <br />
                {{ Form::open(['url' => action('Admin\EventController@updateManager',['id' => $event->id]), 'method' => 'put', 'class' => 'form-horizontal form-label-left']) }}
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Full Name <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {{ Form::text('name', $manager?$manager->name:'', ['class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {{ Form::email('email', $manager?$manager->email:'', ['class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile_number">Username <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {{ Form::text('username', $manager?$manager->username:'', ['class' => 'form-control col-md-7 col-xs-12', 'required' => 'required']) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="identity_passport"> Password <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {{ Form::password('password', ['class' => 'form-control col-md-7 col-xs-12']) }}
                      </div>
                    </div>
                    

                  @if($manager)
                  <input type="hidden" name="manager_id" value="{{$manager->id}}">
                  @endif


                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn-link" href="{{ url()->previous() }}">
                            <button class="btn btn-primary" type="button">Cancel</button>
                        </a>
                        <button type="submit" class="btn btn-success">Submit</button>
                        @if($manager)
                           <button type="submit" class="btn btn-danger" name="delete_manager"  value="ok">Delete Manager</button>
                        @endif
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
