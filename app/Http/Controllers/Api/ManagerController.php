<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManagerController extends Controller
{
    public function getConfig()
    {
        // Get current active event
        $event = Event::activeOnly()->first();

        if (!is_null($event)) {
            return response()->json([
                'background_url' => $event->hasAppBackground() ? $event->appBackgroundUrl() : null,
                'font_color' => $event->font_color,
                'event_logo' => $event->logoUrl(),
                'has_event' => true
            ]);
        }
        else {
            return response()->json([
                'has_event' => false
            ]);
        }
    }
}
