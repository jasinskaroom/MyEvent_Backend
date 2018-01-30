<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use App\Models\RegisterFormField;
use App\Http\Controllers\Controller;
use App\Services\ParticipantService;

class RegisterController extends Controller
{
    public function registerParticipantForEvent(Request $request, ParticipantService $service)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required|numeric',
            'identity_passport' => 'required',
            'gender' => 'required'
        ]);

        $event = Event::activeOnly()->first();
        if($event->event_secret_id != $request->event_id){
            return response()->json([
                'success'   =>  false,
                'message'   =>  "Wrong Event ID",
            ]);
        }

        //return $request->all();
        if(participant::where(['event_id'=>$event->id,'device_id'=>!is_null($request->device_id) ? $request->device_id : ''])->first() ){
            return response()->json([
                'success'   =>  false,
                'message'   =>  "Sorry, you can not be register again for same event.",
            ]);
        }



        $data = $request->except(['event_id','old_event_id']);
        $data['pre_registration'] = false;
        // Add user record
        $participant = $service->createParticipant($data, $event->id);
        $participant->generateApiToken();

        return response()->json([
            'success' => true,
            'content' => $participant
        ]);
    }

    public function processPreregisterForEvent(Request $request)
    {
        $this->validate($request, [
            'mobile_number' => 'required'
        ]);

        $event = Event::activeOnly()->first();

        // Find mobile number
        $participant = $event->participants()->where('mobile_number', $request->mobile_number)->first();

        if ($participant != null) {
            if ($participant->pre_registration == false) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not pre-register user'
                ]);
            }
            else if ($participant->activated) {
                return response()->json([
                    'success' => false,
                    'message' => 'You\'ve activate the account before'
                ]);
            }
            else {
                $participant->generateApiToken();
                $participant->activated = true;
                $participant->save();

                return response()->json([
                    'success' => true,
                    'content' => $participant
                ]);
            }
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'You are not pre-register user'
            ]);
        }
    }

    public function getExtraRegisterForms(Request $request)
    {
        $locale = $request->has('locale') ? $request->get('locale') : 'en';

        $extraFields = RegisterFormField::all();

        $fields = [];
        foreach ($extraFields as $field) {
            array_push($fields, [
                'id' => $field->getFormKey(),
                'name' => $field->translate($locale)->name
            ]);
        }

        return response()->json([
            'success' => true,
            'content' => $fields
        ]);
    }
}
