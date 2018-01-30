<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Auth;
use Response;
use App\Models\Event;
use App\Models\QRCode;
use Illuminate\Http\Request;
use App\Services\QRCodeService;
use App\Models\RegisterFormField;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class QRCodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $events = [];
        foreach (Event::all() as $event) {
            $events[$event->id] = $event->getAllLocalesString();
        }

        $event = Event::first();
        if ($request->has('event')) {
            $foundEvent = Event::find($request->get('event'));

            if (!is_null($foundEvent)) {
                $event = $foundEvent;
            }
        }

        if (!is_null($event)) {
            $qrcodes = $event->qrcodes()->orderBy('point', 'asc')->get();

            return view('admin.qr_code.view', [
                'qrcodes' => $qrcodes,
                'events' => $events,
                'selectedEvent' => $event
            ]);
        }
        else {
            session()->flash('system_status', 'Please create an event before creating QRCode.');

            return redirect()->action('Admin\EventController@showEventList');
        }
    }

    public function viewQRCodeImage($id)
    {
        $qrcode = QRCode::findOrFail($id);

        $file = Storage::disk('qrcode')->get($qrcode->image_filename);
        $mime = 'image/'.QRCode::IMAGE_FORMAT;

        $headers = [
            'Content-Type' => $mime
        ];

        return Response::make($file, 200, $headers);
    }

    public function showCreateForm()
    {
        $events = [];
        foreach (Event::all() as $event) {
            $events[$event->id] = $event->getAllLocalesString();
        }

        return view('admin.qr_code.create_form', ['events' => $events]);
    }

    public function createQRCode(Request $request, QRCodeService $service)
    {
        $this->validate($request, [
            'point' => 'required|numeric|min:1',
            'event' => 'required'
        ]);

        $service->createQRCode($request->get('event'), $request->get('point'));

        return redirect()->action('Admin\QRCodeController@index', ['event' => $request->get('event')]);
    }

    public function showEditForm($id)
    {
        $qrcode = QRCode::findOrFail($id);
        $events = [];
        foreach (Event::all() as $event) {
            $events[$event->id] = $event->getAllLocalesString();
        }

        return view('admin.qr_code.edit_form', ['qrcode' => $qrcode, 'events' => $events]);
    }

    public function updateQRCode($id, Request $request, QRCodeService $service)
    {
        $qrcode = QRCode::findOrFail($id);

        $this->validate($request, [
            'point' => 'required|numeric|min:1',
            'event' => 'required'
        ]);

        $service->updateQRCode($id, $request->get('event'), $request->get('point'));

        return redirect()->action('Admin\QRCodeController@index', ['event' => $request->get('event')]);
    }

    public function deleteQRCode($id, QRCodeService $service)
    {
        $service->deleteQRCode($id);

        return redirect()->action('Admin\QRCodeController@index');
    }

    public function printQRCodes($eventId)
    {
        $event = Event::findOrFail($eventId);
        $qrcodes = $event->qrcodes()->orderBy('point', 'asc')->get();

        $pdf = PDF::loadView('templates.pdf.qrcode', ['qrcodes' => $qrcodes]);
        $pdf->setPaper('a4');
        $pdf->setOrientation('portrait');
        return $pdf->stream('qrcode.pdf');
    }

    public function printIndividualQRCode($id)
    {
        $qrcode = QRCode::findOrFail($id);

        $pdf = PDF::loadView('templates.pdf.qrcode', ['qrcodes' => [$qrcode]]);
        $pdf->setPaper('a4');
        $pdf->setOrientation('portrait');
        return $pdf->stream('qrcode.pdf');
    }
}
