@extends('admin.layouts.admin')

@section('title', 'QRCode Management')

@section('content')

    <div class="pull-right">
        @if ($qrcodes->count() > 0)
            <a class="btn-link" target="_blank" href="{{ action('Admin\QRCodeController@printQRCodes', ['eventId' => $selectedEvent->id]) }}">
                <button class="btn btn-sm btn-info" type="button">Print</button>
            </a>
        @endif
        <a class="btn-link" href="{{ action('Admin\QRCodeController@showCreateForm', ['event' => $selectedEvent->id]) }}">
            <button class="btn btn-sm btn-success" type="button">Create QRCode</button>
        </a>
    </div>

    <div class="clearfix"></div>

    {{ Form::open(['url' => action('Admin\QRCodeController@index'), 'method' => 'get', 'class' => 'form-horizontal form-label-left']) }}
        <div class="form-group">
            <label class="control-label">Event: </label>
            {{ Form::select('event', $events, $selectedEvent->id, ['onchange' => 'this.form.submit()']) }}
        </div>
    {{ Form::close() }}

    @if ($qrcodes->count() <= 0)
        <p>No QRCodes</p>
    @endif

    <div class="row">
        <div class="container">
            @foreach($qrcodes as $qrcode)
                <div class="qrcode-holder col-md-4 col-sm-4 col-xs-6">
                    <div class="thumbnail">
                        <div class="image view">
                            <img src="{{ $qrcode->imageUrl() }}" alt="qrcode">
                            <div class="caption">
                                <h3>Point: {{ $qrcode->point }}</h3>
                                <div class="">
                                    <a class="btn btn-xs btn-info" target="_blank" href="{{ action('Admin\QRCodeController@printIndividualQRCode', ['id' => $qrcode->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Print">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    <a class="btn btn-xs btn-info" href="{{ action('Admin\QRCodeController@showEditForm', ['id' => $qrcode->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    {{ Form::open(['url' => action('Admin\QRCodeController@deleteQRCode', ['id' => $qrcode->id]), 'method' => 'delete', 'class' => 'form form-delete']) }}
                                        <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" data-title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
        });
    </script>
@endsection

@section('styles')
    @parent

    <style media="screen">
        .qrcode-holder img {
            width: 100%;
            display: block;
        }

        .thumbnail {
            height: 100%;
        }

        .thumbnail .image {
            height: 100%;
        }
    </style>
@endsection
