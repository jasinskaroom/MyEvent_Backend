@extends('admin.layouts.admin')

@section('title', 'Fields Creation')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Default Fields <small class="text-warning">not editable</small></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <input type="text" placeholder="Name" disabled class="form-control">
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <input type="text" placeholder="Email" disabled class="form-control">
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <input type="text" placeholder="Account Password" disabled class="form-control">
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <input type="text" placeholder="Mobile Number" disabled class="form-control">
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <input type="text" placeholder="IC / Passport" disabled class="form-control">
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <input type="text" placeholder="Gender" disabled class="form-control">
                </div>
            </div>
        </div>

        <div class="x_panel">
            <div class="x_title">
                <h2>Extra Fields <small class="text-success">editable</small></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @foreach($fields as $field)
                    <div class="field-holder">
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <input type="text" name="{{ $field->key }}" value="{{ $field->getAllLocalesString() }}" disabled class="form-control">
                        </div>
                        <div data-field-id="{{ $field->id }}" class="actions col-md-12 col-sm-12 col-xs-12 form-group text-center">
                            <a class="btn btn-xs btn-primary btn-swap-up" data-toggle="tooltip" data-placement="top" data-title="View">
                                <i class="fa fa-arrow-up"></i>
                            </a>
                            <a class="btn btn-xs btn-primary btn-swap-down" data-toggle="tooltip" data-placement="top" data-title="View">
                                <i class="fa fa-arrow-down"></i>
                            </a>
                            <a class="btn btn-xs btn-success" href="{{ action('Admin\FieldCreationController@showEditFieldForm', ['id' => $field->id]) }}" data-toggle="tooltip" data-placement="top" data-title="View">
                                <i class="fa fa-pencil"></i>
                            </a>
                            {{ Form::open(['url' => action('Admin\FieldCreationController@deleteField', ['id' => $field->id]), 'method' => 'delete', 'class' => 'form form-delete']) }}
                                <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" data-title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            {{ Form::close() }}
                        </div>
                    </div>
                @endforeach
                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <a href="btn-link">
                        <a class="btn-link" href="{{ action('Admin\FieldCreationController@showCreateNewFieldForm') }}">
                            <button class="btn btn-sm btn-success btn-block" type="button">Add</button>
                        </a>
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @parent

    <script type="text/javascript">
        var reorderUrl = "{{ URL::to('/').'/admin/fc/field/{id}/reorder' }}";
    </script>
    {{ Html::script(mix('assets/admin/js/field-creation.js')) }}
@endsection

@section('styles')
    @parent

@endsection
