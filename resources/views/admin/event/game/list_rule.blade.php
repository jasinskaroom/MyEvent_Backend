@extends('admin.layouts.admin')

@section('title', $stage->getAllLocalesString())

@section('content')

    <div class="pull-right">
        <a class="btn-link" href="{{ action('Admin\GameController@showCreateRuleGameForm', ['stageId' => $stage->id]) }}">
            <button class="btn btn-sm btn-info" type="button">Add Game</button>
        </a>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Games</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered dt-responsive nowrap">
                      <thead>
                        <tr>
                            <th>Ordering</th>
                            <th>Name</th>
                            <th>Image</th>
                            @foreach($locales as $locale)
                              <th>Rules ({{ $locale->name }})</th>
                            @endforeach
                            <th>Number of scan allowed</th>
                            <th>Created</th>
                            <th>Last updated</th>
                            <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                          @foreach($games as $game)
                              <tr data-game-id="{{ $game->id }}" class="game-holder">
                                  <td>
                                      <a class="btn btn-xs btn-primary btn-swap-up" data-toggle="tooltip" data-placement="top" data-title="View">
                                          <i class="fa fa-arrow-up"></i>
                                      </a>
                                      <a class="btn btn-xs btn-primary btn-swap-down" data-toggle="tooltip" data-placement="top" data-title="View">
                                          <i class="fa fa-arrow-down"></i>
                                      </a>
                                  </td>
                                  <td>{{ $game->getAllLocalesString() }}</td>
                                  <td>
                                      <img class="thumbnail" src="{{ $game->ruleGame->getPreviewImageUrl() }}" alt="Game Image">
                                  </td>
                                  @foreach($locales as $locale)
                                      <td>
                                          @if (!is_null($game->ruleGame->translate($locale->code)))
                                            {{ $game->ruleGame->translate($locale->code)->rule }}
                                          @endif
                                      </td>
                                  @endforeach
                                  <td>{{ $game->number_of_scan }}</td>
                                  <td>{{ $game->created_at }}</td>
                                  <td>{{ $game->updated_at }}</td>
                                  <td>
                                      <a class="btn btn-xs btn-info" href="{{ action('Admin\GameController@showEditRuleGameForm', ['id' => $game->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Edit">
                                          <i class="fa fa-pencil"></i>
                                      </a>
                                      {{ Form::open(['url' => action('Admin\GameController@deleteRuleGame', ['id' => $game->id]), 'method' => 'delete', 'class' => 'form form-delete']) }}
                                          <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" data-title="Delete">
                                              <i class="fa fa-trash"></i>
                                          </button>
                                      {{ Form::close() }}
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                    </table>

                    @if (count($games) <= 0)
                        <h5 class="text-center">No Games</h5>
                    @endif
                </div>
              </div>
            </div>
          </div>
    </div>

@endsection

@section('scripts')
    @parent

    <script type="text/javascript">
        var reorderUrl = "{{ URL::to('/').'/admin/event/stage/game/{id}/reorder' }}";
    </script>
    {{ Html::script(mix('assets/admin/js/game-ordering.js')) }}
@endsection

@section('styles')
    @parent

@endsection
