@extends('admin.layouts.admin')

@section('title', '')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
            {{ Form::open(['action' => 'Admin\EventController@showEventList', 'method' => 'GET', 'class' => 'form']) }}
                <div class="input-group">
                    <input type="text" name="query" value="{{ Request::get('query') }}" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">Go</button>
                    </span>

                </div>
            {{ Form::close() }}
        </div>

        <div class="col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>My Events</h2>
            {{--
                <ul class="nav navbar-right panel_toolbox">
                  <li>
                      <a class="btn-link" href="{{ action('Admin\EventController@showCreateEventForm') }}">
                          <button class="btn btn-sm btn-primary btn-block" type="button">Add Event</button>
                      </a>
                  </li>
                </ul>
            --}}
                <div class="clearfix"></div>
              </div>
              <div class="x_content">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered dt-responsive nowrap">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Status</th>
                          <th>Event Id</th>
                          <th>Created</th>
                          <th>Last updated</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                          @foreach($events as $manager_event)
                            <?php $event = $manager_event->event; ?>
                              <tr>
                                  <td>
                                      <a class="btn btn-link" href="{{ action('Admin\EventController@viewEvent', ['id' => $event->id]) }}">
                                          {{ $event->getAllLocalesString() }}
                                      </a>
                                  </td>

                                  <td>
                                      @if ($event->activate)
                                          <span class="label label-primary">Active</span>
                                      @else
                                          <span class="label label-danger">Closed</span>
                                      @endif
                                  </td>
                                  <td>
                                  {{$event->event_secret_id}}
                                  </td>
                                  <td>{{ $event->created_at }}</td>
                                  <td>{{ $event->updated_at }}</td>
                                  <td>
                                      <a class="btn btn-xs btn-primary" href="{{ action('Admin\EventController@viewEvent', ['id' => $event->id]) }}" data-toggle="tooltip" data-placement="top" data-title="View">
                                          <i class="fa fa-eye"></i>
                                      </a>
                                      <a class="btn btn-xs btn-info" href="{{ action('Admin\EventController@showEditEventForm', ['id' => $event->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Edit">
                                          <i class="fa fa-pencil"></i>
                                      </a>
                                      {{ Form::open(['url' => action('Admin\EventController@deleteEvent', ['id' => $event->id]), 'method' => 'delete', 'class' => 'form form-delete']) }}
                                          <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" data-title="Delete">
                                              <i class="fa fa-trash"></i>
                                          </button>
                                      {{ Form::close() }}
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                    </table>

                    @if (count($events) <= 0)
                        <h5 class="text-center">No Events</h5>
                    @endif
                </div>

                <div class="pull-right">
                </div>
              </div>
            </div>
          </div>
    </div>

@endsection

@section('scripts')
    @parent

    <script type="text/javascript">
        $(function() {
            $('.form-delete').submit(function(e) {
                e.preventDefault();

                var status = confirm('Are you sure you want to delete this event?');
                if (status) {
                    this.submit();
                }
            });
        });
    </script>


@endsection

@section('styles')
    @parent

@endsection
