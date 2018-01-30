<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\Participant;
use Illuminate\Http\Request;
use App\Models\RegisterFormField;
use App\Http\Controllers\Controller;
use App\Services\ParticipantService;

class ProfileController extends Controller
{
    public function getProfile()
    {
        $participant = Auth::guard('api')->user();
        
        // Gether extra fields data
        $participantFields = $participant->fields;
        $form = [];
        foreach ($participantFields as $participantField) {
            array_push($form, [
                'id' => $participantField->getFormKey(),
                'name' => $participantField->name,
                'value' => $participantField->pivot->value
            ]);
        }

        if (!is_null($participant)) {
            return response()->json([
                'success' => true,
                'content' => [
                    'profile' => $participant,
                    'extra_fields' => $form
                ]
            ]);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found in the system.'
            ]);
        }
    }

    public function updateProfile(Request $request, ParticipantService $service)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required|numeric',
            'identity_passport' => 'required',
            'gender' => 'required'
        ]);

        $participant = Auth::guard('api')->user();

        $service->updateParticipant($request->all(), $participant->event->id, $participant);

        return response()->json([
            'success' => true,
            'content' => $participant
        ]);
    }
}
