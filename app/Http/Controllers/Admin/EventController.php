<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Excel;
use Session;
use Response;
use AppLocale;
use App\Models\User;
use App\Models\Event;
use App\Models\Participant;
use App\Models\EventBanner;
use App\Models\SummaryEvent;
use App\Models\EventActivityTranslation;
use Illuminate\Http\Request;
use App\Services\EventService;
use App\Models\RegisterFormField;
use Illuminate\Support\Collection;
use App\Helpers\EventMemberImport;
use App\Services\EventBannerService;
use App\Http\Controllers\Controller;
use App\Services\MemberImportService;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    const UPDATE_EVENT_REDIRECT_SESSION = "USER_UPDATE_EVENT_REDIRECT_URL";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function viewLiveEvent(Request $request)
    {
        // User for redirection after update event
        $this->saveUpdateEventRedirect($request->fullUrl());

        //if user is manager
        if(Auth::user()->isManager()){
            return redirect()->action('Admin\EventController@showEventList');
        }

        //else is admin

        $event = Event::activeOnly()->first();
        if (!is_null($event)) {
            return redirect()->action('Admin\EventController@viewEvent', ['id' => $event->id]);
        }
        else {
            return view('admin.event.no_live');
        }
    }

    public function showEventList(Request $request)
    {

        if(Auth::user()->isManager()){
            $events = Auth::user()->manageEvents;
            return view('admin.event.managerlist', compact('events'));
        }

        // User for redirection after update event
        $this->saveUpdateEventRedirect($request->fullUrl());

        // Check has query
        $resultPerPage = 15;
        if ($request->has('query')) {
            $events = Event::closedOnly()->search($request->get('query'))->distinct();
        }
        else {
            $events = Event::closedOnly();
        }

        $eventPaginate = $events->paginate($resultPerPage);

        return view('admin.event.list', ['events' => $eventPaginate]);
    }

    public function showScoreBoard($id)
    {
        $event = Event::findOrFail($id);
        $unSortedParticipants = $event->participants;

        $sortedParticipants = [];
        // sort by total points
        while(count($unSortedParticipants) > 0) {
            $highestParticipant = $unSortedParticipants->get(0);
            $highestIndex = 0;
            for ($i = 1; $i < count($unSortedParticipants); $i++) {
                $currentParticipant = $unSortedParticipants->get($i);
                $currentIndex = $i;
                if ($highestParticipant->totalPoints() < $currentParticipant->totalPoints()) {
                    $highestParticipant = $currentParticipant;
                    $highestIndex = $currentIndex;
                }
            }

            array_push($sortedParticipants, $highestParticipant);
            $unSortedParticipants->splice($highestIndex, 1);
        }

        return view('admin.event.score_board', [
            'event' => $event,
            'participants' => $sortedParticipants
        ]);
    }

    public function exportParticipantData($id)
    {
        $event = Event::findOrFail($id);
        $fields = RegisterFormField::orderBy('order', 'asc')->get();

        Excel::create($event->translate('en')->name . ' Members Data', function($excel) use($event, $fields) {

            $excel->sheet('participants', function($sheet) use($event, $fields) {

                $sheet->loadView('templates.excel.event_member_data', [
                    'event' => $event,
                    'fields' => $fields
                ]);
            });

        })->export('xls');
    }

    public function showImportMemberForm($id)
    {
        $event = Event::findOrFail($id);

        return view('admin.event.import_member.upload', [
            'event' => $event
        ]);
    }

    public function importMemberWithCSV($id, EventMemberImport $import)
    {
        $fields = RegisterFormField::orderBy('order', 'asc')->get();
        $service = new MemberImportService();

        $event = Event::findOrFail($id);
        $members = $service->checkMembersHasSameEmail($id, $import->get()->toArray());

        session()->put('import_members', $members);

        return view('admin.event.import_member.preview', [
            'event' => $event,
            'members' => $members,
            'fields' => $fields
        ]);
    }

    public function saveImportMember($id)
    {
        if (session()->has('import_members') == false) {
            abort(404);
        }

        $event = Event::findOrFail($id);
        $members = session()->pull('import_members');

        $service = new MemberImportService();
        $service->saveMembers($id, $members);

        return redirect()->action('Admin\EventController@viewEvent', ['id' => $id]);
    }

    public function getImportMemberTemplate()
    {
        $service = new MemberImportService();

        $service->getTemplate();
    }

    public function viewEvent($id, Request $request)
    {
        // User for redirection after update event
        $this->saveUpdateEventRedirect($request->fullUrl());

        $event = Event::findOrFail($id);

        if(Auth::user()->isManager()){
            if(!in_array($id, Auth::user()->manageEvents->pluck('event_id')->toArray())){
                abort(401);
            }
        }

        // Query participant
        $resultPerPage = 15;
        if ($request->has('query')) {
            $participants = $event->participants()->search($request->get('query'))->distinct();
            if ($participants->count() <= 0) {
                $participants = $event->participants()->search(intval($request->get('query')))->distinct();
            }
        }
        else {
            $participants = $event->participants();
        }
        // Do filtering for status
        if ($request->has('type')) {
            $type = $request->get('type');

            if ($type == 'active') {
                $participants = $participants->where('activated', true);
            }
            else if ($type == 'inactive') {
                $participants = $participants->where('activated', false);
            }
            else if ($type == 'pre_registration') {
                $participants = $participants->where('pre_registration', true);
            }
            else if ($type == 'rewarded') {
                $participants = $participants->where('rewarded', true);
            }
            else if ($type == 'not_rewarded') {
                $participants = $participants->where('rewarded', false);
            }
            // else 'all'
        }
        $participantPaginate = $participants->where('event_id',$id)->paginate($resultPerPage);

        return view('admin.event.detail', [
            'event' => $event,
            'participants' => $participantPaginate
        ]);
    }

    public function showCreateEventForm()
    {
        $locales = AppLocale::getAvailableLocales();

        return view('admin.event.create_event_form', ['locales' => $locales]);
    }

    public function showEditEventForm($id)
    {
        $event = Event::findOrFail($id);
        $locales = AppLocale::getAvailableLocales();

        return view('admin.event.edit_event_form', ['locales' => $locales, 'event' => $event]);
    }

    public function createEvent(Request $request, EventService $service)
    {
        $locales = AppLocale::getAvailableLocales();

        $validations = [];
        foreach ($locales as $locale) {
            $validations[$locale->code] = 'required';
        }

        $this->validate($request, $validations);

        // create event
        $eventData = $request->all();
        $eventData['activate'] = $request->get('activate') ? $request->get('activate') == '1' : false;
        $service->createEvent($eventData);

        return redirect()->action('Admin\EventController@showEventList');
    }

    public function updateEvent($id, Request $request, EventService $service)
    {
        $locales = AppLocale::getAvailableLocales();

        $validations = [];
        foreach ($locales as $locale) {
            $validations[$locale->code] = 'required';
        }

        $this->validate($request, $validations);

        // update event
        $eventData = $request->all();
        $eventData['activate'] = $request->get('activate') ? $request->get('activate') == '1' : false;
        $service->updateEvent($id, $eventData);

        if ($eventData['activate']) {
            return redirect()->action('Admin\EventController@viewLiveEvent');
        }
        else {
            return Session::has(EventController::UPDATE_EVENT_REDIRECT_SESSION) ?
                    redirect(Session::get(EventController::UPDATE_EVENT_REDIRECT_SESSION)) :
                    redirect()->action('Admin\EventController@showEventList');
        }
    }

    public function markEventAsEnded($id)
    {
        $event = Event::findOrFail($id);

        $stages = $event->stages;
        // Num of games
        $numGame = 0;
        foreach ($stages as $stage) {
            $numGame += $stage->games->count();
        }

        $summary = SummaryEvent::create([
            'event_name' => $event->name,
            'event_id' => $event->event_secret_id,
            'num_participant' => $event->participants->count(),
            'num_stage' => $stages->count(),
            'num_game' => $numGame
        ]);

        $event->delete();

        return redirect()->action('Admin\EventController@eventEndedSummary');
    }

    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);

        $event->delete();

        return redirect()->action('Admin\EventController@showEventList');
    }

    public function showEventBanners($eventId)
    {
        $event = Event::findOrFail($eventId);
        $locales = AppLocale::getAvailableLocales();

        return view('admin.event.list_banner', [
            'event' => $event,
            'locales' => $locales,
            'banners' => $event->banners()->orderBy('order', 'asc')->get()
        ]);
    }

    public function showCreateEventBannerForm($eventId)
    {
        $event = Event::findOrFail($eventId);
        $locales = AppLocale::getAvailableLocales();

        return view('admin.event.create_banner_form', [
            'event' => $event,
            'locales' => $locales
        ]);
    }

    public function showEditEventBannerForm($id)
    {
        $banner = EventBanner::findOrFail($id);
        $locales = AppLocale::getAvailableLocales();

        return view('admin.event.edit_banner_form', [
            'banner' => $banner,
            'locales' => $locales
        ]);
    }

    public function createEventBanner($eventId, Request $request, EventBannerService $service)
    {
        $event = Event::findOrFail($eventId);
        $locales = AppLocale::getAvailableLocales();

        $validations = [];
        foreach ($locales as $locale) {
            $validations[$locale->code] = 'required|image';
        }

        $this->validate($request, $validations);

        $service->createBanner($event, $request->all());

        return redirect()->action('Admin\EventController@showEventBanners', ['eventId' => $event->id]);
    }

    public function updateEventBanner($id, Request $request, EventBannerService $service)
    {
        $banner = EventBanner::findOrFail($id);

        $banner = $service->updateBanner($banner, $request->all());

        return redirect()->action('Admin\EventController@showEventBanners', ['eventId' => $banner->event->id]);
    }

    public function deleteEventBanner($id)
    {
        $banner = EventBanner::findOrFail($id);

        $banner->delete();

        return redirect()->action('Admin\EventController@showEventBanners', ['eventId' => $banner->event->id]);
    }

    public function reorderBanner($id, Request $request, EventBannerService $service)
    {
        $this->validate($request, [
            'swapWithBannerId' => 'required'
        ]);

        $bannerToSwap = $request->get('swapWithBannerId');

        $status = $service->swapBanner($id, $bannerToSwap);

        if ($status) {
            return response()->json([
                'data' => [
                    'message' => 'Successfully swap field'
                ]
            ]);
        }
        else {
            return response()->json([
                'error' => [
                    'message' => 'Not able to swap. Field not found'
                ]
            ]);
        }
    }

    public function viewEventBannerImage($id, $locale)
    {
        $banner = EventBanner::findOrFail($id);

        if ($banner->hasTranslation($locale)) {
            $file = Storage::disk('event_banner')->get($banner->translate($locale)->filename);
            $mime = $banner->translate($locale)->mime;

            $headers = [
                'Content-Type' => $mime
            ];

            return Response::make($file, 200, $headers);
        }

        abort(404);
    }

    /**
    * Helpers
    */
    public function saveUpdateEventRedirect($url)
    {
        Session::put(EventController::UPDATE_EVENT_REDIRECT_SESSION, $url);
    }


    public function activitiesTitle($id){
        $locales = AppLocale::getAvailableLocales();
        $event = Event::find($id);
        return view('admin.event.activities_title', compact('locales','event'));

    }

    public function updateActivitiesTitle($id, Request $request){
         $locales = AppLocale::getAvailableLocales();
         foreach($locales as $local){
            $activity_title = EventActivityTranslation::where(['locale'=>$local->code,'event_id'=>$id])->first();
            if(!$activity_title){
                $activity_title = new EventActivityTranslation;
                $activity_title->locale = $local->code;
                $activity_title->event_id = $id;
            }
            $activity_title->title = $request->{$local->code};
            $activity_title->save();
         }

        return redirect()->action('Admin\EventController@viewLiveEvent');
    }

    public function showManager($id){
        $event = Event::find($id);
        $manager = $event->manager;
        return view('admin.event.manager', compact('event','manager_id','manager'));
    }

    public function updateManager($id, Request $request){

        if($request->delete_manager){
            $event = Event::findOrFail($id);
            $event->manager_id=null;
            $event->save();

            return redirect()->action('Admin\EventController@viewLiveEvent');
        }
        if($request->manager_id){
            $manager = User::find($request->manager_id);
        }
        else{
            $manager = User::where(['email'=>$request->email])->first();
        }
        if(!$manager){

             $this->validate($request,[
                'username'=>'required|unique:users',
                'email'   =>'required|unique:users',
                'password'=>'required'

            ]);
            $manager = new User;
            $manager->mobile_number = '';
            $manager->identity_passport = '';
            $manager->role_id = 3;
        }


        $manager->name = $request->name;
        $manager->username = $request->username;
        $manager->password = bcrypt($request->password);
        $manager->email   = $request->email;
        $manager->save();

        $event = event::find($id);
        $event->manager_id = $manager->id;
        $event->save();


        return redirect()->action('Admin\EventController@viewLiveEvent');
    }

    public function eventSummary($id)
    {
        $event = Event::find($id);

        return view('admin.event.summary', compact('event'));
    }


    public function eventEndedSummary()
    {
        $events = SummaryEvent::paginate(15);

        return view('admin.event.ended_summary_list', [
            'events' => $events
        ]);
    }
}
