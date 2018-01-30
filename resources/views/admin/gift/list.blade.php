@extends('admin.layouts.admin')

@section('title', '')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                {{ Form::open(['action' => 'Admin\GiftController@index', 'method' => 'GET', 'class' => 'form']) }}
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
                <h2>Gifts</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a target="_blank" class="btn-link" href="{{ action('Admin\GiftController@printGifts') }}">
                            <button class="btn btn-sm btn-info" type="button">Print</button>
                        </a>
                    </li>
                    <li>
                        <a class="btn-link" href="{{ action('Admin\GiftController@showCreateForm') }}">
                            <button class="btn btn-sm btn-success btn-block" type="button">Add Gift</button>
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
                          <th>Point</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($gifts as $gift)
                            <tr>
                                <td>{{ $gift->name }}</td>
                                <td>{{ $gift->point }}</td>
                                <td>
                                    <a class="btn btn-xs btn-info" href="{{ action('Admin\GiftController@showEditForm', ['id' => $gift->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    {{ Form::open(['url' => action('Admin\GiftController@deleteGift', ['id' => $gift->id]), 'method' => 'delete', 'class' => 'form form-delete']) }}
                                        <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" data-title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    {{ Form::close() }}
                                </td>
                            </tr>
                        @endforeach
                      </tbody>
                    </table>

                    @if(count($gifts) <= 0)
                        <h5 class="text-center">No Gifts</h5>
                    @endif
                </div>

                <div class="pull-right">
                    {{ $gifts->appends($_GET)->links() }}
                </div>
              </div>
            </div>
          </div>
    </div>

    <iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>
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
        });
    </script>
@endsection

@section('styles')
    @parent

@endsection
