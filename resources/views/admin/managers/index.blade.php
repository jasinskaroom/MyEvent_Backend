@extends('admin.layouts.admin')

@section('title', 'Managers')

@section('content')

    <div class="pull-right">
        <div style="margin-right: 80px;" class="dropdown">
            <a class="btn btn-sm btn-primary " href="{{ action('Admin\EventManagerController@create',[])}}">Create Manager
            </a>


        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="">
      <div class="col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Managers</h2>

                <div class="clearfix"></div>
              </div>
              <div class="x_content">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered dt-responsive nowrap">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Username</th>
                          <th>Email</th>
                          <th>Events</th>
                          <th>Created</th>
                          <th>Last updated</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                          @foreach($managers as $manager)
                              <tr>
                                  <td>{{ $manager->id }}</td>
                                  <td>
                                      {{ $manager->name }}
                                  </td>
                                  <td>
                                      {{ $manager->username }}
                                  </td>
                                  <td>{{ $manager->email }}</td>
                                  <td>{!! $manager->manageEventsHtml() !!}</td>
                                  <td>{{ $manager->created_at }}</td>
                                  <td>{{ $manager->updated_at }}</td>
                                  <td>
                                    @if(Auth::user()->isAdmin())

                                      <a class="btn btn-xs btn-info" href="{{ action('Admin\EventManagerController@edit', ['id' => $manager->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Edit">
                                          <i class="fa fa-pencil"></i>
                                      </a>
                                      {{ Form::open(['url' => action('Admin\EventManagerController@delete', ['id' => $manager->id]), 'method' => 'delete', 'class' => 'form form-delete']) }}
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

                    @if (count($managers) <= 0)
                        <h5 class="text-center">No manager</h5>
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

                var status = confirm('Are you sure?');
                if (status) {
                    this.submit();
                }
            });

            $('#menu_toggle').click();
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
