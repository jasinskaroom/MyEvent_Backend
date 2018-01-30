@extends('admin.layouts.admin')

@section('title', 'Game: '.$game->getAllLocalesString())

@section('content')

    <div class="pull-right">
        <span><b>Number of scan allowed: </b> {{ $game->number_of_scan }}</span>
    </div>

    <div class="clearfix"></div>

    <div class="pull-right">
        <a class="btn-link" href="{{ action('Admin\GameController@showEditInputGameForm', ['id' => $game->id]) }}">
            <button class="btn btn-sm btn-info" type="button">Edit Game</button>
        </a>
        {{ Form::open(['url' => action('Admin\GameController@deleteInputGame', ['id' => $game->id]), 'method' => 'delete', 'class' => 'form form-delete']) }}
            <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" data-title="Delete">
                Delete Game
            </button>
        {{ Form::close() }}
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Fields</h2>
                <a class="btn-link pull-right" href="{{ action('Admin\GameController@showCreateInputGameFieldForm', ['gameId' => $game->id]) }}">
                    <button class="btn btn-sm btn-info" type="button">Add Field</button>
                </a>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered dt-responsive nowrap">
                      <thead>
                        <tr>
                            <th>Ordering</th>
                            @foreach($locales as $locale)
                              <th>Field Name ({{ $locale->name }})</th>
                            @endforeach
                            <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                          @foreach($game->inputGame->fields()->orderBy('order')->get() as $field)
                              <tr data-game-id="{{ $field->id }}" class="game-holder">
                                  <td>
                                      <a class="btn btn-xs btn-primary btn-swap-up" data-toggle="tooltip" data-placement="top" data-title="View">
                                          <i class="fa fa-arrow-up"></i>
                                      </a>
                                      <a class="btn btn-xs btn-primary btn-swap-down" data-toggle="tooltip" data-placement="top" data-title="View">
                                          <i class="fa fa-arrow-down"></i>
                                      </a>
                                  </td>
                                  @foreach($locales as $locale)
                                      <td>
                                          {{ $field->translate($locale->code)->name }}
                                      </td>
                                  @endforeach
                                  <td>
                                      <a class="btn btn-xs btn-info" href="{{ action('Admin\GameController@showEditInputGameFieldForm', ['id' => $field->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Edit">
                                          <i class="fa fa-pencil"></i>
                                      </a>
                                      {{ Form::open(['url' => action('Admin\GameController@deleteInputGameField', ['id' => $field->id]), 'method' => 'delete', 'class' => 'form form-delete']) }}
                                          <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" data-title="Delete">
                                              <i class="fa fa-trash"></i>
                                          </button>
                                      {{ Form::close() }}
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                    </table>

                    @if (count($game->inputGame->fields) <= 0)
                        <h5 class="text-center">No Fields</h5>
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
        var reorderUrl = "{{ URL::to('/').'/admin/event/stage/game-input/field/{id}/reorder' }}";
    </script>
    {{ Html::script(mix('assets/admin/js/game-ordering.js')) }}
@endsection

@section('styles')
    @parent

@endsection
