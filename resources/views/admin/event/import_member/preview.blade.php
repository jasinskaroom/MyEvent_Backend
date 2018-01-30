@extends('admin.layouts.admin')

@section('title', 'Import Members')

@section('content')

    <div class="clearfix"></div>

    <div class="col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Summary</h2>
            <div class="clearfix"></div>
            <small>All member will added as pre-registration</small>
          </div>
          <div class="x_content">

            <div class="table-responsive">
                <table class="table table-bordered dt-responsive nowrap">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Mobile Number</th>
                      <th>IC / Passport</th>
                      <th>Gender</th>
                      @foreach ($fields as $field)
                        <th>{{ $field->name }}</th>
                      @endforeach
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($members as $member)
                        <tr {!! $member['same_email'] ? "data-toggle=tooltip data-placement=top data-title='Email exist in system' class=bg-danger" : "" !!}>
                            <td>{{ $member['name'] }}</td>
                            <td>{{ $member['email'] }}</td>
                            <td>{{ $member['mobile_number'] }}</td>
                            <td>{{ $member['ic_passport'] }}</td>
                            <td>{{ $member['gender_malefemale'] }}</td>
                            @foreach ($fields as $field)
                              <th>{{ $member[str_slug($field->name, '_')] }}</th>
                            @endforeach
                        </tr>
                      @endforeach
                  </tbody>
                </table>

                @if (count($members) <= 0)
                    <h5 class="text-center">No Members</h5>
                @endif
            </div>

            <form action="{{ action('Admin\EventController@saveImportMember', ['id' => $event->id]) }}" method="POST">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-primary" name="submit">Comfirm Import</button>
            </form>
          </div>
        </div>
      </div>

@endsection

@section('scripts')
    @parent

@endsection

@section('styles')
    @parent

@endsection
