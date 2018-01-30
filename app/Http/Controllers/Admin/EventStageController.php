<?php

namespace App\Http\Controllers\Admin;

use Auth;
use AppLocale;
use App\Models\Game;
use App\Models\Event;
use App\Models\Stage;
use Illuminate\Http\Request;
use App\Services\EventStageService;
use App\Http\Controllers\Controller;

class EventStageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function viewEventStages($eventId, Request $request)
    {
        $event = Event::findOrFail($eventId);

        $resultPerPage = 15;
        if ($request->has('query')) {
            $stages = $event->stages()->search($request->get('query'))->distinct();
        }
        else {
            $stages = $event->stages();
        }

        $stagePaginate = $stages->orderBy('order')->paginate($resultPerPage);

        return view('admin.event.stage.list', [
            'event' => $event,
            'stages' => $stagePaginate
        ]);
    }

    public function showCreateStageForm($eventId)
    {
        $event = Event::findOrFail($eventId);
        $locales = AppLocale::getAvailableLocales();
        $types = Game::getGameTypes();

        return view('admin.event.stage.create_stage_form', [
            'event' => $event,
            'locales' => $locales,
            'types' => $types
        ]);
    }

    public function showEditStageForm($id)
    {
        $stage = Stage::findOrFail($id);
        $locales = AppLocale::getAvailableLocales();
        $types = Game::getGameTypes();

        return view('admin.event.stage.edit_stage_form', [
            'stage' => $stage,
            'locales' => $locales,
            'types' => $types
        ]);
    }

    public function createStage($eventId, Request $request, EventStageService $service)
    {
        $event = Event::findOrFail($eventId);
        $locales = AppLocale::getAvailableLocales();

        $validations = [
            'type' => 'required'
        ];
        foreach ($locales as $locale) {
            $validations[$locale->code] = 'required';
        }

        $this->validate($request, $validations);

        $data = $request->all();
        $data['type'] = Game::getGameTypes()[$data['type']];
        // create stage
        $service->createStage($event, $data);

        return redirect()->action('Admin\EventStageController@viewEventStages', ['eventId' => $eventId]);
    }

    public function updateStage($id, Request $request, EventStageService $service)
    {
        $stage = Stage::findOrFail($id);
        $locales = AppLocale::getAvailableLocales();

        $validations = [];
        foreach ($locales as $locale) {
            $validations[$locale->code] = 'required';
        }

        $this->validate($request, $validations);

        $data = $request->all();
        // update stage
        $service->updateStage($id, $data);

        return redirect()->action('Admin\EventStageController@viewEventStages', ['eventId' => $stage->event->id]);
    }

    public function deleteStage($id)
    {
        $stage = Stage::findOrFail($id);
        $eventId = $stage->event->id;

        $stage->delete();

        return redirect()->action('Admin\EventStageController@viewEventStages', ['eventId' => $eventId]);
    }

    public function reorderStage($id, Request $request, EventStageService $service)
    {
        $this->validate($request, [
            'swapWithStageId' => 'required'
        ]);

        $stageToSwap = $request->get('swapWithStageId');

        $status = $service->swapStage($id, $stageToSwap);

        if ($status) {
            return response()->json([
                'data' => [
                    'message' => 'Successfully swap stage'
                ]
            ]);
        }
        else {
            return response()->json([
                'error' => [
                    'message' => 'Not able to swap. Stage not found'
                ]
            ]);
        }
    }
}
