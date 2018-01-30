@extends('admin.layouts.admin')

@section('title', 'Event Summary')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Event Name</th>
                            <td>{{$event->name}}</td>
                        </tr>
                          <tr>
                            <th>Event Id</th>
                            <td>{{$event->event_secret_id}}</td>
                        </tr>
                        <tr>
                            <th>Number of Participants</th>
                            <td>{{$event->participants->count()}}</td>
                        </tr>
                        <tr>
                            <th>Number of Stages</th>
                            <td>{{$event->stages->count()}}</td>
                        </tr>
                        <tr>
                            <th>Managers</th>
                            <td>
                                @foreach($event->managers as $event_manager) {{$event_manager->user->name}}
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{date('jS, M Y', strtotime($event->created_at))}}</td>
                        </tr>
                        <tr>
                            <th>Last Updated At</th>
                            <td>{{date('jS, M Y', strtotime($event->created_at))}}</td>
                        </tr>
                        <tr>
                            <th>Status </th>
                            <td>  @if ($event->activate)
                                          <span class="label label-primary">Live</span>
                                @else
                                    <span class="label label-danger">Closed</span>
                                @endif  </td>
                        </tr>
                    </table>
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
