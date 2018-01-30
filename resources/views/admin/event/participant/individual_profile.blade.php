@extends('admin.layouts.admin')

@section('title', $participant->name."'s Profile")

@section('content')


    <div class="pull-right">
        @if ($participant->rewarded == false)
            <a class="btn btn-xs btn-warning" href="{{ action('Admin\ParticipantController@showRewardParticipantForm', ['id' => $participant->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Reward">
                <i class="fa fa-gift"></i>
            </a>
        @endif
        <a class="btn btn-xs btn-info" href="{{ action('Admin\ParticipantController@showEditParticipantForm', ['id' => $participant->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Edit">
            <i class="fa fa-pencil"></i>
        </a>
        {{ Form::open(['url' => action('Admin\ParticipantController@deleteParticipant', ['id' => $participant->id]), 'method' => 'delete', 'class' => 'form-delete']) }}
            <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" data-title="Delete">
                <i class="fa fa-trash"></i>
            </button>
        {{ Form::close() }}
    </div>

    <div class="row">
        <table class="table table-striped table-hover">
            <tbody>
            <tr>
                <th>Participating Event</th>
                <td>
                    <a href="{{ action('Admin\EventController@viewEvent', ['id' => $participant->event->id]) }}">
                        {{ $participant->event->getAllLocalesString() }}
                    </a>
                </td>
            </tr>
            <tr>
                <th>ID</th>
                <td>{{ $participant->draw_id }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $participant->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    <a href="mailto:{{ $participant->email }}">
                        {{ $participant->email }}
                    </a>
                </td>
            </tr>
            <tr>
                <th>Mobile Number</th>
                <td>{{ $participant->mobile_number }}</td>
            </tr>
            <tr>
                <th>IC / Passport</th>
                <td>{{ $participant->identity_passport }}</td>
            </tr>
            <tr>
                <th>Gender</th>
                <td>{{ $participant->gender }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if ($participant->activated)
                        <span class="label label-primary">Active</span>
                    @else
                        <span class="label label-danger">Inactive</span>
                    @endif

                    @if ($participant->pre_registration && !$participant->activated)
                        <span class="label label-warning">Pre-registration</span>
                    @elseif ($participant->pre_registration && $participant->activated)
                        <span class="label label-success">Pre-registration</span>
                    @endif
                </td>
            </tr>

            <tr>
                <th>Score</th>
                <td>{{ $participant->totalPoints() }}</td>
            </tr>

            <tr>
                <th>Rewarded</th>
                <td>
                    @if ($participant->rewarded)
                        <span class="label label-success">Rewarded</span>
                    @else
                        <span class="label label-danger">Not Yet Rewarded</span>
                    @endif
                </td>
            </tr>

            @if ($participant->rewarded)
                <tr>
                    <th>Gift Awarded</th>
                    <td>{{ $participant->getAllGiftsString() }}</td>
                </tr>
            @endif

            <tr>
                <th>Created at</th>
                <td>{{ $participant->created_at }} ({{ $participant->created_at->diffForHumans() }})</td>
            </tr>

            <tr>
                <th>Last updated</th>
                <td>{{ $participant->updated_at }} ({{ $participant->updated_at->diffForHumans() }})</td>
            </tr>
            @foreach($fields as $field)
                <tr>
                    <th>{{ $field->getAllLocalesString()}}</th>
                    <td>{{ !is_null($participant->getFieldValue($field->id)) ? $participant->getFieldValue($field->id) : '-' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>


     <div class="clearfix"></div>

    <div class="row">


              <div class="x_title">
                <h2>{{$participant->name}}'s Activities </h2>
                <div class="clearfix"></div>
              </div>

               <div class="x_content">

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Stage</th>
                            <th>Activity</th>
                            <th>Score</th>
                            <th>Action</th>

                        </tr>
                        </thead>

                        <tbody>
                              @foreach($participant->participatedGames as $game)
                                <tr class="record">
                                    <td>{{$game->game->stage->getAllLocalesString()}} </td>
                                    <td>{{$game->game->getAllLocalesString()}} </td>
                                    <td>{{$game->score}} </td>
                                    <td>
                                        @if(Auth::user()->isAdmin())
                                            @if($game->game->type == \App\Models\Game::TYPE_TEXT_INPUT)
                                                <a target="_blank" href="{{ action('Admin\ParticipantController@viewGameInputDetail', ['participantId' => $participant->id, 'participationId' => $game->id]) }}" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i>View Inputs</a>
                                            @endif
                                        <button class="btn btn-xs btn-primary" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#activity-{{$game->id}}"><i class="fa fa-edit"></i>Edit Score</button>
                                        <button class="btn btn-xs btn-danger" activity-id="{{$game->id}}" onclick="resetActivity(this)"><i class="fa fa-remove"></i>Reset</button>
                                        @endif
                                    </td>

                                </tr>

                                <div id="activity-{{$game->id}}" class="modal fade" role="dialog">
                                      <div class="modal-dialog">

                                     {{ Form::model($participant, ['url' => action('Admin\ParticipantController@updateParticipantScore', ['id' => $participant->id,'activity_id'=>$game->id]), 'method' => 'put', 'class' => 'form-horizontal ']) }}

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">{{$game->game->stage->getAllLocalesString()}} </h4>
                                          </div>
                                          <div class="modal-body">

                                        <div class="row">
                                            <div class="form-group">
                                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Score<span class="required">*</span>
                                              </label>
                                              <div class="col-md-6 col-sm-6 col-xs-12">
                                                  {{ Form::number('score', $game->score, ['class' => 'form-control col-md-7 col-xs-12', 'required' => 'required','min'=>0]) }}
                                              </div>
                                            </div>
                                        </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Update Score</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                          </div>
                                    </form>
                                        </div>

                                      </div>
                                    </div>
                           @endforeach
                        </tbody>

                    </table>

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
        });
    </script>

    <script type="text/javascript">
        function resetActivity(that){
            if(confirm('Are you sure?')){
                var part_id = $(that).attr('activity-id');
                $.ajax({
                    url:'/admin/event/participant/{{$participant->id}}/'+part_id+'/reset_activity',
                    success:function(){
                        $(that).parents('.record').hide('slow');
                    }
                })
            }
        }
    </script>
@endsection

@section('styles')
    @parent

@endsection
