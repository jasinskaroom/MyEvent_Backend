@extends('admin.layouts.admin')

@section('title', '')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                {{ Form::open(['url' => action('Admin\EventStageController@viewEventStages', ['eventId' => $event->id]), 'method' => 'GET', 'class' => 'form']) }}
                    <div class="input-group">
                        <input type="text" name="query" value="{{ Request::get('query') }}" class="form-control" placeholder="Search for...">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">Go</button>
                        </span>

                    </div>
                {{ Form::close() }}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Stages</h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li>
                      <a class="btn-link" href="{{ action('Admin\EventStageController@showCreateStageForm', ['eventId' => $event->id]) }}">
                          <button class="btn btn-sm btn-success btn-block" type="button">Add Stage</button>
                      </a>
                  </li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered dt-responsive nowrap">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Game Type</th>
                          <th>Created</th>
                          <th>Last updated</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($stages as $stage)
                            <tr data-stage-id="{{ $stage->id }}" class="stage-holder">
                                <td>
                                    <a class="btn btn-link" href="{{ action('Admin\GameController@viewGames', ['stageId' => $stage->id]) }}">
                                        {{ $stage->getAllLocalesString() }}
                                    </a>
                                </td>
                                <td>{{ $stage->game_type }}</td>
                                <td>{{ $stage->created_at }}</td>
                                <td>{{ $stage->updated_at }}</td>
                                <td>
                                    <a class="btn btn-xs btn-warning btn-swap-up" data-toggle="tooltip" data-placement="top" data-title="Move Up">
                                        <i class="fa fa-arrow-up"></i>
                                    </a>
                                    <a class="btn btn-xs btn-warning btn-swap-down" data-toggle="tooltip" data-placement="top" data-title="Move Down">
                                        <i class="fa fa-arrow-down"></i>
                                    </a>
                                    <a class="btn btn-xs btn-primary" href="{{ action('Admin\GameController@viewGames', ['stageId' => $stage->id]) }}" data-toggle="tooltip" data-placement="top" data-title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a class="btn btn-xs btn-info" href="{{ action('Admin\EventStageController@showEditStageForm', ['id' => $stage->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    {{ Form::open(['url' => action('Admin\EventStageController@deleteStage', ['id' => $stage->id]), 'method' => 'delete', 'class' => 'form form-delete']) }}
                                        <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" data-title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    {{ Form::close() }}
                                </td>
                            </tr>
                        @endforeach
                      </tbody>
                    </table>

                    @if(count($stages) <= 0)
                        <h5 class="text-center">No Stages</h5>
                    @endif
                </div>

                <div class="pull-right">
                    {{ $stages->appends($_GET)->links() }}
                </div>
              </div>
            </div>
          </div>
    </div>

@endsection

@section('scripts')
    @parent

    <script type="text/javascript">
        var reorderUrl = "{{ URL::to('/').'/admin/event/stage/{id}/reorder' }}";
    </script>
    {{ Html::script(mix('assets/admin/js/stage-listing.js')) }}
@endsection

@section('styles')
    @parent

@endsection
