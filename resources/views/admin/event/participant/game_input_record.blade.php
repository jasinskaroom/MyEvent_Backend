@extends('admin.layouts.admin')

@section('title', $participant->name."'s Profile")

@section('content')

    <div class="row">

              <div class="x_title">
                <h2>User's input for game: {{ $participatedGame->game->name }} </h2>
                <div class="clearfix"></div>
              </div>

               <div class="x_content">

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Game field</th>
                            <th>Input</th>
                        </tr>
                        </thead>

                        <tbody>
                              @foreach($participatedGame->inputs as $input)
                                <tr class="record">
                                    <td>{{$input->field->name}} </td>
                                    <td>{{$input->value}} </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

               </div>
            </div>
        </div>






@endsection
