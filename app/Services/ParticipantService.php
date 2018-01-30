<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Participant;
use App\Models\RegisterFormField;
use DB;

class ParticipantService extends BaseService
{
    public function createParticipant(array $data, $eventId)
    {
        $participant = new Participant();
        $participant->fill($data);
        $participant->activated = !isset($data['activated']) || is_null($data['activated']) ? true : $data['activated']; // default is true
        $participant->event_id = $eventId;
        $participant->draw_id = Participant::where('event_id', $eventId)->max('draw_id') + 1; // latest draw id
        $participant->save();

        // attach extra fields
        $this->attachFieldsToParticipant($participant, $data);

        return $participant;
    }

    public function createPreRegisterParticipant(array $data, $eventId)
    {
        $data['pre_registration'] = true;
        $data['activated'] = false;

        return $this->createParticipant($data, $eventId);
    }

    public function updateParticipant(array $data, $eventId, Participant $participant)
    {
        $participant->fill($data);
        $participant->event_id = $eventId;
        $participant->activated = !isset($data['activated']) || is_null($data['activated']) ? true : $data['activated']; // default is true

        // attach extra fields
        $this->attachFieldsToParticipant($participant, $data);

        $participant->save();

        return $participant;
    }

    public function attachFieldsToParticipant($participant, $fieldData)
    {
        // attach extra fields
        // 1. Detach all
        $participant->fields()->detach();
        // 2. Add in
        $extraFields = RegisterFormField::all();
        foreach($extraFields as $field) {
            if (isset($fieldData[$field->getFormKey()])) {
                $participant->fields()->attach($field->id, ['value' => $fieldData[$field->getFormKey()]]);
            }
        }
    }

    public function logoutParticipants(){
        DB::table('participants')->update(['api_token'=>null]);
    }
}
