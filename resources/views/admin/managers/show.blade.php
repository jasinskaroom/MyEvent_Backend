@extends('admin.layouts.admin')

@section('title', 'Manager Events')

@section('content')

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
           
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Event </th>
                            <tH>Action</tH>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($manager->manageEvents as $my_events)
                                  <tr>
                                    <td>{{$my_events->eventgetAllLocalesString()}}</td>
                                    <td><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                       
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
