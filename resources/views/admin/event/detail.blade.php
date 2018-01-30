@extends('admin.layouts.admin')

@section('title', $event->getAllLocalesString())

@section('content')

    <div class="pull-left">
        <img class="thumbnail small-logo" src="{{ $event->logoUrl() }}" alt="Event logo">
    </div>

    <div class="pull-right">
        <div style="margin-right: 80px;" class="dropdown">
            <a class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" href="#">Actions
            <span class="caret"></span></a>
            <ul class="dropdown-menu pull-left">
                @if(Auth::user()->isAdmin())
                <li><a href="{{ action('Admin\EventController@showEditEventForm', ['id' => $event->id]) }}">Edit</a></li>
                <li><a href="{{ action('Admin\EventController@showEventBanners', ['eventId' => $event->id]) }}">Manage Banner</a></li>
                <li style="height: 25px;">
                      {{ Form::open(['url' => action('Admin\EventController@markEventAsEnded', ['id' => $event->id]), 'method' => 'delete', 'class' => 'form form-delete form-delete-li']) }}
                              <button type="submit" class="btn btn-xs btn-warning" data-toggle="tooltip" data-placement="top" data-title="Delete">
                                  <i class="fa fa-flag-checkered"></i>End this event
                              </button>
                      {{ Form::close() }}
                </li>
                <li role="separator" class="divider"></li>
                @endif

                <li><a target="_blank" href="{{ action('Admin\EventController@showScoreBoard', ['id' => $event->id]) }}">Score Board</a></li>
                <li role='separator' class="divider"></li>

                <li><a target="_blank" href="{{ action('Admin\EventController@exportParticipantData', ['id' => $event->id]) }}">Export Members Data</a></li>
                <li><a href="{{ action('Admin\EventController@showImportMemberForm', ['id' => $event->id]) }}">Import Members (CSV)</a></li>
                <li><a target="_blank" href="{{ action('Admin\EventController@getImportMemberTemplate') }}">Get Import Template</a></li>

                @if(Auth::user()->isAdmin())
                    <li role='separator' class="divider"></li>
                    <li>
                        <a href="{{ action('Admin\EventController@activitiesTitle',['id' => $event->id])}} ">
                            Activities Title
                        </a>
                    </li>
                    <li>
                          {{ Form::open(['url' => action('Admin\EventController@deleteEvent', ['id' => $event->id]), 'method' => 'delete', 'class' => 'form form-delete form-delete-li']) }}
                                      <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" data-title="Delete">
                                          <i class="fa fa-trash"></i>Remove Event
                                      </button>
                          {{ Form::close() }}
                    </li>
                @endif

            </ul>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
                <div class="icon"><i class="fa fa-users"></i></div>
                <div class="count">
                    <span>{{ $event->participants->count() }}</span>
                    <a class="btn-link" href="{{ action('Admin\ParticipantController@showRegisterParticipantForm', ['event' => $event->id]) }}">
                        <button class="btn btn-sm btn-info" type="button">Create</button>
                    </a>
                </div>
                <h3>Total Participants</h3>
              </div>
            </div>
        </div>
        <div class="">
            <div class="animated flipInY col-lg-3 col-md-4 col-sm-6 col-xs-12">
              <div class="tile-stats">
                <div class="icon"><i class="fa fa-bullseye"></i></div>
                <div class="count">
                    <span>{{ $event->stages->count() }}</span>
                    @if(Auth::user()->isAdmin())
                    <a class="btn-link" href="{{ action('Admin\EventStageController@viewEventStages', ['eventId' => $event->id]) }}">
                        <button class="btn btn-sm btn-info" type="button">Manage</button>
                    </a>
                    @endif
                </div>
                <h3>
                    Stages Created
                </h3>
              </div>
            </div>
        </div>
         <div class="">
            <div class="animated flipInY col-lg-2 col-md-2 col-sm-6 col-xs-12">
              <div class="tile-stats">
                <div class="count">
                    <a class="btn-link" href="{{ action('Admin\EventController@eventSummary', ['eventId' => $event->id]) }}">
                        <button class="btn btn-sm btn-info" type="button">View Stats</button>
                    </a>

                </div>
                <h3>
                  Summary
                </h3>
              </div>
            </div>
        </div>

         <div class="">
            <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="tile-stats">
                <div class="count">
                    <span>{{ $event->event_secret_id }}</span>

                </div>
                <h3>
                   Event Id
                </h3>
              </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Participants</h2>
                <div class="col-md-5 col-sm-5 col-xs-12 panel_toolbox top_search">
                    {{ Form::open(['url' => action('Admin\EventController@viewEvent', ['id' => $event->id]), 'method' => 'GET', 'class' => 'form']) }}
                        <div class="input-group">
                            <input type="text" name="query" value="{{ Request::get('query') }}" class="form-control" placeholder="Search for...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">Go</button>
                            </span>

                        </div>
                    {{ Form::close() }}
                </div>
                <div class="filter-actions text-center">
                    {{ Form::open(['url' => action('Admin\EventController@viewEvent', ['id' => $event->id]), 'method' => 'get', 'class' => 'form']) }}
                        <div class="form-group">
                            <label class="control-label">Filter Status: </label>
                            <select name="type" onchange='this.form.submit()'>
                                <option {{ Request::get('type') == 'all' ? 'selected' : '' }} value="all">All</option>
                                <option {{ Request::get('type') == 'active' ? 'selected' : '' }} value="active">Active</option>
                                <option {{ Request::get('type') == 'inactive' ? 'selected' : '' }} value="inactive">Inactive</option>
                                <option {{ Request::get('type') == 'pre_registration' ? 'selected' : '' }} value="pre_registration">Pre Registration</option>
                                <option {{ Request::get('type') == 'rewarded' ? 'selected' : '' }} value="rewarded">Rewarded</option>
                                <option {{ Request::get('type') == 'not_rewarded' ? 'selected' : '' }} value="not_rewarded">Not Yet Rewarded</option>
                            </select>
                        </div>
                    {{ Form::close() }}
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered dt-responsive nowrap">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Gender</th>
                          <th>Mobile Number</th>
                          <th>IC / Passport</th>
                          <th>Status</th>
                          <th>Score</th>
                          <th>Rewarded</th>
                          <th>Created</th>
                          <th>Last updated</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                          @foreach($participants as $participant)
                              <tr>
                                  <td>{{ $participant->draw_id }}</td>
                                  <td>
                                      <a class="btn btn-link" href="{{ action('Admin\ParticipantController@viewProfile', ['id' => $participant->id]) }}">
                                          {{ $participant->name }}
                                      </a>
                                  </td>
                                  <td>{{ $participant->email }}</td>
                                  <td>{{ $participant->gender }}</td>
                                  <td>{{ $participant->mobile_number }}</td>
                                  <td>{{ $participant->identity_passport }}</td>
                                  <td>
                                      @if ($participant->activated)
                                          <span class="label label-primary">Active</span>
                                      @else
                                          <span class="label label-danger">Inactive</span>
                                      @endif

                                      @if ($participant->pre_registration && !$participant->activated)
                                          <span class="label label-warning">Pre-registration</span>
                                      @elseif ($participant->pre_registration && $participant->activated)
                                          <span class="label label-success">Pre-registration</span>
                                      @endif
                                  </td>
                                  <td>{{ $participant->totalPoints() }}</td>
                                  <td>
                                      @if ($participant->rewarded)
                                          <span class="label label-success">Yes</span>
                                      @else
                                          <span class="label label-danger">No</span>
                                      @endif
                                  </td>
                                  <td>{{ $participant->created_at }}</td>
                                  <td>{{ $participant->updated_at }}</td>
                                  <td>
                                    @if(Auth::user()->isAdmin())
                                      @if ($participant->rewarded == false)
                                          <a class="btn btn-xs btn-warning" href="{{ action('Admin\ParticipantController@showRewardParticipantForm', ['id' => $participant->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Reward">
                                              <i class="fa fa-gift"></i>
                                          </a>
                                      @endif
                                      <a class="btn btn-xs btn-primary" href="{{ action('Admin\ParticipantController@viewProfile', ['id' => $participant->id]) }}" data-toggle="tooltip" data-placement="top" data-title="View">
                                          <i class="fa fa-eye"></i>
                                      </a>
                                      <a class="btn btn-xs btn-info" href="{{ action('Admin\ParticipantController@showEditParticipantForm', ['id' => $participant->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Edit">
                                          <i class="fa fa-pencil"></i>
                                      </a>
                                      {{ Form::open(['url' => action('Admin\ParticipantController@deleteParticipant', ['id' => $participant->id]), 'method' => 'delete', 'class' => 'form form-delete']) }}
                                          <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" data-title="Delete" >
                                              <i class="fa fa-trash"></i>
                                          </button>
                                      {{ Form::close() }}

                                      @endif
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                    </table>

                    @if (count($participants) <= 0)
                        <h5 class="text-center">No Participant</h5>
                    @endif
                </div>

                <div class="pull-right">
                    {{ $participants->appends($_GET)->links() }}
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

                var status = confirm('Are you sure?');
                if (status) {
                    this.submit();
                }
            });

            @if(Auth::user()->isAdmin())
            $('#menu_toggle').click();
            @endif
        });
    </script>
@endsection

@section('styles')
    @parent

    <style type="text/css">
       .form-delete-li {
          padding: 3px 20px;
       }
    </style>

@endsection
