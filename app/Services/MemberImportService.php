<?php

namespace App\Services;

use Excel;
use App\Models\Event;
use App\Models\RegisterFormField;

class MemberImportService
{
    public function getTemplate()
    {
        Excel::create('Import Member Template', function($excel) {

            $excel->sheet('members', function($sheet) {

                $sheet->loadView('templates.excel.import_event_member', [
                ]);
            });

        })->export('xls');
    }

    public function checkMembersHasSameEmail($eventId, array $members)
    {
        $event = Event::find($eventId);
        if (is_null($event)) {
            return null;
        }

        $processedMembers = [];
        foreach ($members as $member) {
            $member['same_email'] = $event->participants()->where('email', $member['email'])->first() != null;
            array_push($processedMembers, $member);
        }

        return $processedMembers;
    }

    public function saveMembers($eventId, array $members)
    {
        $service = new ParticipantService();

        foreach ($members as $member) {
            if (!$member['same_email']) {
                $participant = $service->createPreRegisterParticipant([
                    'name' => $member['name'],
                    'email' => $member['email'],
                    'mobile_number' => $member['mobile_number'],
                    'identity_passport' => $member['ic_passport'],
                    'gender' => $member['gender_malefemale']
                ], $eventId);

                // attach extra fields
                // 1. Detach all
                $participant->fields()->detach();
                // 2. Add in
                $extraFields = RegisterFormField::all();
                // Because excel convert title to slug, so we need to convert too
                foreach($extraFields as $field) {
                    if (isset($member[ str_slug($field->name, '_') ])) {
                        $participant->fields()->attach($field->id, [
                            'value' => $member[ str_slug($field->name, '_') ]
                        ]);
                    }
                }
            }
        }
    }
}
