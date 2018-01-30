@extends('admin.layouts.basic')

@section('title', $event->title)

@section('content')

    <div class="row">
          <div class="x_title">
              <h3>Event - {{ $event->name }}</h3>
            <div class="clearfix"></div>
          </div>

           <div class="x_content">
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Total Score</th>
                        <th>Number of stages completed</th>
                    </tr>
                    </thead>
                    <tbody>
                          @foreach($participants as $participant)
                            <tr class="record">
                                <td>{{ $participant->draw_id }}</td>
                                <td>
                                    <a class="btn btn-link" href="{{ action('Admin\ParticipantController@viewProfile', ['id' => $participant->id]) }}">
                                        {{ $participant->name }}
                                    </a>
                                </td>
                                <td>{{ $participant->totalPoints() }} </td>
                                <td>{{ $participant->participatedGames->count() }}</td>
                            </tr>
                       @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>


@endsection
