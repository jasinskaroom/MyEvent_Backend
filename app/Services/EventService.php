<?php

namespace App\Services;

use Storage;
use AppLocale;
use App\Models\Event;
use App\Models\EventActivityTranslation;
use App\Services\ParticipantService;

class EventService extends BaseService
{

    const STORAGE_EVENT_LOGO = 'event_logo';
    const STORAGE_EVENT = 'event';

    public function createEvent($data)
    {
        $eventData = [
            'font_color' => $data['font_color'],
            'event_secret_id' => $data['event_secret_id']
        ];

        // add name
        $locales = AppLocale::getAvailableLocales();
        foreach($locales as $locale) {
            $eventData[$locale->code] = [
                'name' => $data[$locale->code]
            ];
        }

        // add event logo
        if (isset($data['event_logo'])) {
            $fileMeta = $this->saveImageFile($data['event_logo'], static::STORAGE_EVENT_LOGO);

            $eventData['logo_filename'] = $fileMeta['filename'];
            $eventData['logo_mime'] = $fileMeta['mime'];
        }
        else {
            $eventData['logo_filename'] = '';
            $eventData['logo_mime'] = '';
        }

        // add app background
        if (isset($data['background'])) {
            $fileMeta = $this->saveImageFile($data['background'], static::STORAGE_EVENT);

            $eventData['app_background_filename'] = $fileMeta['filename'];
            $eventData['app_background_mime'] = $fileMeta['mime'];
        }


        $event = Event::create($eventData);

        if ($data['activate']) {
            $this->activateEvent($event);
        }
        else {
            $this->deactivateEvent($event);
        }

        return $event;
    }

    public function updateEvent($id, $data)
    {
        $event = Event::find($id);

        if (!is_null($event)) {
            $locales = AppLocale::getAvailableLocales();

            // update
            $event->font_color = $data['font_color'];
            $event->event_secret_id = $data['event_secret_id'];
            foreach($locales as $locale) {
                $event->translate($locale->code)->name = $data[$locale->code];
            }
            $event->save();

            // update logo if found changes
            if (isset($data['event_logo'])) {
                // Event Logo
                // Remove current logo
                $this->removeImageFile($event->logo_filename, static::STORAGE_EVENT_LOGO);
                // Create new file
                $eventLogoFileMeta = $this->saveImageFile($data['event_logo'], static::STORAGE_EVENT_LOGO);

                $event->update([
                    'logo_filename' => $eventLogoFileMeta['filename'],
                    'logo_mime' => $eventLogoFileMeta['mime']
                ]);
            }

            if (isset($data['background'])) {
                // Background Image
                // Remove current
                $this->removeImageFile($event->app_background_filename, static::STORAGE_EVENT);
                // Create new
                $backgroundImageFileMeta = $this->saveImageFile($data['background'], static::STORAGE_EVENT);

                $event->update([
                    'app_background_filename' => $backgroundImageFileMeta['filename'],
                    'app_background_mime' => $backgroundImageFileMeta['mime']
                ]);
            }

            if ($data['activate']) {
                $this->activateEvent($event);
            }
            else {
                $this->deactivateEvent($event);
            }

            return $event;
        }
        else {
            return null;
        }
    }

    public function activateEvent($eventToActivate){

        //clear all participants
        if(!$eventToActivate->activate){
            $participantService = new  ParticipantService ;
            $participantService->logoutParticipants();
        }
        // Shut down all activated event
        Event::where('activate', true)->update(['activate' => false]);

        // Activate this event
        $eventToActivate->activate = true;
        $eventToActivate->save();
    }

    public function deactivateEvent($event)
    {
        $event->activate = false;
        $event->save();


    }

    public function saveImageFile($uploadFile, $storage)
    {
        // Save photo
        $path = $uploadFile->store($storage);

        // Get filename
        $filename = explode('/', $path)[1];
        $mime = $uploadFile->getMimeType();

        return [
            'filename' => $filename,
            'mime' => $mime
        ];
    }

    public function removeImageFile($filename, $storage)
    {
        if (Storage::disk($storage)->exists($filename)) {
            Storage::disk($storage)->delete($filename);
        }
    }

     public function createActivitiesTitle($event_id){
        $locales = ['en','zh','my'];

        foreach($locales as $lang){
            $activity = new EventActivityTranslation; 
            $activity->title = 'Activities';
            $activity->locale = $lang;
            $activity->event_id = $event_id;
            $activity->save();
        }
    }
}
