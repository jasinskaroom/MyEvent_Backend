<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Session;
use App\Models\Gift;
use App\Models\Event;
use App\Models\Participant;
use App\Models\ParticipatedGame;
use App\Models\RegisterFormField;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Services\ParticipantService;

class ParticipantController extends Controller
{
    const REDIRECT_EDIT_SESSION = "REDIRECT_EDIT_URL";
    const REDIRECT_REWARD_SESSION = "REDIRECT_REWARD_URL";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function showRegisterParticipantForm(Request $request)
    {
        $fields = RegisterFormField::orderBy('order', 'asc')->get();
        $events = [];
        foreach (Event::all() as $event) {
            $events[$event->id] = $event->getAllLocalesString();
        }

        return view('admin.event.participant.register_form', [
            'fields' => $fields,
            'events' => $events
        ]);
    }

    public function registerParticipant(Request $request, ParticipantService $service)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required',
            'identity_passport' => 'required',
            'gender' => 'required',
            'event' => 'required'
        ]);

        $data = $request->all();
        $data['pre_registration'] = $request->get('pre_registration') ? $request->get('pre_registration') == '1' : false;
        // Add user record
        if ($data['pre_registration'] == true) {
            $service->createPreRegisterParticipant($data, $request->get('event'));
        }
        else {
            $service->createParticipant($data, $request->get('event'));
        }

        return redirect()->action('Admin\EventController@viewEvent', ['id' => $request->get('event')]);
    }

    public function showEditParticipantForm($id, Request $request)
    {
        $this->saveEditRedirect(url()->previous());

        $participant = Participant::findOrFail($id);
        $fields = RegisterFormField::orderBy('order', 'asc')->get();
        $events = [];
        foreach (Event::all() as $event) {
            $events[$event->id] = $event->getAllLocalesString();
        }

        return view('admin.event.participant.edit_participant_form', [
            'participant' => $participant,
            'fields' => $fields,
            'events' => $events
        ]);
    }

    public function updateParticipant($id, Request $request, ParticipantService $service)
    {
        $participant = Participant::findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required',
            'identity_passport' => 'required',
            'gender' => 'required',
            'event' => 'required'
        ]);

        $data = $request->all();
        $data['activated'] = !is_null($request->get('activated')) ? $request->get('activated') == 'on' : false;

        $service->updateParticipant($data, $request->get('event'), $participant);

        return Session::has(ParticipantController::REDIRECT_EDIT_SESSION) ?
                redirect(Session::get(ParticipantController::REDIRECT_EDIT_SESSION)) :
                redirect()->action('Admin\EventController@viewEvent', ['id' => $request->get('event')]);
    }

    public function viewProfile($id, Request $request)
    {
        $participant = Participant::findOrFail($id);
        $fields = RegisterFormField::orderBy('order', 'asc')->get();

        return view('admin.event.participant.individual_profile', [
            'participant' => $participant, 'fields' => $fields
        ]);
    }

    public function deleteParticipant($id)
    {
        $participant = Participant::findOrFail($id);
        $eventId = $participant->event_id;

        $participant->delete();

        return redirect()->action('Admin\EventController@viewEvent', ['id' => $eventId]);
    }

    public function showRewardParticipantForm($id)
    {
        $this->saveRewardRedirect(url()->previous());

        $participant = Participant::findOrFail($id);
        $gifts = [];
        foreach (Gift::orderBy('name')->get() as $gift) {
            $gifts[$gift->id] = $gift->name . ' (' . $gift->point . ')';
        }

        return view('admin.event.participant.reward_form', [
            'participant' => $participant,
            'gifts' => $gifts
        ]);
    }

    public function rewardParticipant($id, Request $request)
    {
        $participant = Participant::findOrFail($id);

        $this->validate($request, [
            'gifts' => 'required'
        ]);

        // Check enough points
        $participantSelection = $request->get('gifts');
        $totalPointsNeededForGift = 0;
        foreach ($participantSelection as $selection) {
            $gift = Gift::findOrFail($selection);

            $totalPointsNeededForGift += $gift->point;
        }
        if ($participant->score < $totalPointsNeededForGift) {
            return redirect()->back()
                        ->withErrors(['message' => 'Participant has no sufficient score to redeem the gifts.'])
                        ->withInput();
        }

        // set participant as rewarded
        $participant->gifts()->sync($participantSelection);
        $participant->rewarded = true;
        $participant->save();

        return Session::has(ParticipantController::REDIRECT_REWARD_SESSION) ?
                redirect(Session::get(ParticipantController::REDIRECT_REWARD_SESSION)) :
                redirect()->action('Admin\EventController@viewEvent', ['id' => $participant->event_id]);
    }

    public function viewGameInputDetail($participantId, $participationId)
    {
        $participant = Participant::findOrFail($participantId);
        $participatedGame = $participant->participatedGames()->where('id', $participationId)->firstOrFail();


        return view('admin.event.participant.game_input_record', [
            'participant' => $participant,
            'participatedGame' => $participatedGame
        ]);
    }

    /**
    * Helpers
    */
    public function saveEditRedirect($url)
    {
        Session::put(ParticipantController::REDIRECT_EDIT_SESSION, $url);
    }

    public function saveRewardRedirect($url)
    {
        Session::put(ParticipantController::REDIRECT_REWARD_SESSION, $url);
    }

    public function resetActivity($id,$activity_id){
        $activity = ParticipatedGame::find($activity_id);
        $activity->delete();
        return 'ok';
    }

    public function updateParticipantScore($id,$activity_id, request $request){
        $activity = ParticipatedGame::find($activity_id);
        $activity->score = $request->score;
        $activity->save();
        return redirect()->back();
    }
}
