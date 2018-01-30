<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Gift;
use Illuminate\Http\Request;
use App\Services\GiftService;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

class GiftController extends Controller
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
        // Check has query
        $resultPerPage = 15;
        if ($request->has('query')) {
            $gifts = Gift::search($request->get('query'))->distinct();
        }
        else {
            $gifts = Gift::query();
        }

        $giftPaginate = $gifts->orderBy('name', 'asc')->paginate($resultPerPage);

        return view('admin.gift.list', ['gifts' => $giftPaginate]);
    }

    public function showCreateForm()
    {
        return view('admin.gift.create_gift_form');
    }

    public function createGift(Request $request, GiftService $service)
    {
        $this->validate($request, [
            'name' => 'required',
            'point' => 'required|numeric|min:1'
        ]);

        $service->createGift($request->all());

        return redirect()->action('Admin\GiftController@index');
    }

    public function showEditForm($id)
    {
        $gift = Gift::findOrFail($id);

        return view('admin.gift.edit_gift_form', ['gift' => $gift]);
    }

    public function updateGift($id, Request $request, GiftService $service)
    {
        $this->validate($request, [
            'name' => 'required',
            'point' => 'required|numeric|min:1'
        ]);

        $service->updateGift($id, $request->all());

        return redirect()->action('Admin\GiftController@index');
    }

    public function deleteGift($id)
    {
        $gift = Gift::findOrFail($id);

        $gift->delete();

        return redirect()->action('Admin\GiftController@index');
    }

    public function printGifts()
    {
        $gifts = Gift::orderBy('name', 'asc')->get();

        $itemPerPage = 25;
        $giftPages = new Collection();
        // separate into pages because pdf not very well align if one table across pages
        $count = 0;
        $giftPaging = new Collection();

        foreach ($gifts as $gift) {
            $giftPaging->push($gift);

            $count++;

            if ($count % $itemPerPage == 0) {
                $giftPages->push($giftPaging);

                $giftPaging = new Collection();
            }
            else if ($count >= $gifts->count()) {
                $giftPages->push($giftPaging);
            }
        }

        $pdf = PDF::loadView('templates.pdf.gifts', ['giftPages' => $giftPages]);
        $pdf->setPaper('a4');
        $pdf->setOrientation('portrait');
        return $pdf->stream('gifts.pdf');
    }
}
