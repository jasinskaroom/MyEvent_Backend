@extends('admin.layouts.admin')

@section('title', $user->name."'s Profile")

@section('content')


    <div class="pull-right">
        <a class="btn btn-xs btn-info" href="{{ action('Admin\UserController@showEditUserForm', ['id' => $user->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Edit">
            <i class="fa fa-pencil"></i>
        </a>
        {{ Form::open(['url' => action('Admin\UserController@deleteUser', ['id' => $user->id]), 'method' => 'delete', 'class' => 'form-delete']) }}
            <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" data-title="Delete">
                <i class="fa fa-trash"></i>
            </button>
        {{ Form::close() }}
    </div>

    <div class="row">
        <table class="table table-striped table-hover">
            <tbody>
            <tr>
                <th>Name</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    <a href="mailto:{{ $user->email }}">
                        {{ $user->email }}
                    </a>
                </td>
            </tr>
            <tr>
                <th>Username</th>
                <td>{{ $user->username }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if ($user->activated)
                        <span class="label label-primary">Active</span>
                    @else
                        <span class="label label-danger">Inactive</span>
                    @endif

                    @if ($user->pre_registration && !$user->activated)
                        <span class="label label-warning">Pre-registration</span>
                    @elseif ($user->pre_registration && $user->activated)
                        <span class="label label-success">Pre-registration</span>
                    @endif
                </td>
            </tr>

            <tr>
                <th>Created at</th>
                <td>{{ $user->created_at }} ({{ $user->created_at->diffForHumans() }})</td>
            </tr>

            <tr>
                <th>Last updated</th>
                <td>{{ $user->updated_at }} ({{ $user->updated_at->diffForHumans() }})</td>
            </tr>
            @foreach($fields as $field)
                <tr>
                    <th>{{ $field->getAllLocalesString()}}</th>
                    <td>{{ $user->getFieldValue($field->id) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
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
        });
    </script>
@endsection

@section('styles')
    @parent

@endsection
