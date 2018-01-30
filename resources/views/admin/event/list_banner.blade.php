@extends('admin.layouts.admin')

@section('title', $event->getAllLocalesString())

@section('content')

    <div class="pull-right">
        <a class="btn-link" href="{{ action('Admin\EventController@showCreateEventBannerForm', ['eventId' => $event->id]) }}">
            <button class="btn btn-sm btn-info" type="button">Add Banner</button>
        </a>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Banners</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered dt-responsive nowrap">
                      <thead>
                        <tr>
                            <th>Ordering</th>
                            <th>Name</th>
                            @foreach($locales as $locale)
                              <th>{{ $locale->name }} ({{ $locale->code }})</th>
                            @endforeach
                            <th>Created</th>
                            <th>Last updated</th>
                            <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                          @foreach($banners as $banner)
                              <tr data-banner-id="{{ $banner->id }}" class="banner-holder">
                                  <td>
                                      <a class="btn btn-xs btn-primary btn-swap-up" data-toggle="tooltip" data-placement="top" data-title="View">
                                          <i class="fa fa-arrow-up"></i>
                                      </a>
                                      <a class="btn btn-xs btn-primary btn-swap-down" data-toggle="tooltip" data-placement="top" data-title="View">
                                          <i class="fa fa-arrow-down"></i>
                                      </a>
                                  </td>
                                  <td>{{ $banner->getAllLocalesString() }}</td>
                                  @foreach($locales as $locale)
                                      <td>
                                          <img class="thumbnail" src="{{ $banner->getImageUrl($locale->code) }}" alt="Banner ({{ $locale->code }})">
                                      </td>
                                  @endforeach
                                  <td>{{ $banner->created_at }}</td>
                                  <td>{{ $banner->updated_at }}</td>
                                  <td>
                                      <a class="btn btn-xs btn-info" href="{{ action('Admin\EventController@showEditEventBannerForm', ['id' => $banner->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Edit">
                                          <i class="fa fa-pencil"></i>
                                      </a>
                                      {{ Form::open(['url' => action('Admin\EventController@deleteEventBanner', ['id' => $banner->id]), 'method' => 'delete', 'class' => 'form form-delete']) }}
                                          <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" data-title="Delete">
                                              <i class="fa fa-trash"></i>
                                          </button>
                                      {{ Form::close() }}
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                    </table>

                    @if (count($banners) <= 0)
                        <h5 class="text-center">No Banners</h5>
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
        var reorderUrl = "{{ URL::to('/').'/admin/event/banner/{id}/reorder' }}";
    </script>
    {{ Html::script(mix('assets/admin/js/banner-listing.js')) }}
@endsection

@section('styles')
    @parent

@endsection
