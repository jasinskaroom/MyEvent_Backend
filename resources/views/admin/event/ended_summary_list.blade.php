@extends('admin.layouts.admin')

@section('title', '')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Ended Events</h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li>
                      <a class="btn-link" href="{{ action('Admin\EventController@showCreateEventForm') }}">
                          <button class="btn btn-sm btn-primary btn-block" type="button">Add Event</button>
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
                            <th>Event Id</th>
                            <th>Name</th>
                            <th>Number of Participants</th>
                            <th>Number of Stages</th>
                            <th>Number of Games</th>
                            <th>Ended On</th>
                        </tr>
                      </thead>
                      <tbody>
                          @foreach($events as $event)
                              <tr>
                                  <td>{{ $event->event_id }}</td>
                                  <td>{{ $event->event_name }}</td>
                                  <td>{{ $event->num_participant }}</td>
                                  <td>{{ $event->num_stage }}</td>
                                  <td>{{ $event->num_game }}</td>
                                  <td>{{date('jS, M Y', strtotime($event->created_at))}}</td>
                              </tr>
                          @endforeach
                      </tbody>
                    </table>

                    @if (count($events) <= 0)
                        <h5 class="text-center">No Events</h5>
                    @endif
                </div>

                <div class="pull-right">
                    {{ $events->appends($_GET)->links() }}
                </div>
              </div>
            </div>
          </div>
    </div>

@endsection
