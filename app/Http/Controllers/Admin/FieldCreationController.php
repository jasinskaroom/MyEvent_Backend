<?php

namespace App\Http\Controllers\Admin;

use Auth;
use AppLocale;
use Illuminate\Http\Request;
use App\Models\RegisterFormField;
use App\Http\Controllers\Controller;
use App\Services\RegisterFormService;

class FieldCreationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function view()
    {
        $fields = RegisterFormField::orderBy('order', 'asc')->get();

        return view('admin.field_creation.view', ['fields' => $fields]);
    }

    public function showCreateNewFieldForm()
    {
        $locales = AppLocale::getAvailableLocales();

        return view('admin.field_creation.create_field', ['locales' => $locales]);
    }

    public function showEditFieldForm($id)
    {
        $field = RegisterFormField::findOrFail($id);
        $locales = AppLocale::getAvailableLocales();

        return view('admin.field_creation.edit_field', [
            'locales' => $locales,
            'field' => $field
        ]);
    }

    public function createNewField(Request $request, RegisterFormService $service)
    {
        $locales = AppLocale::getAvailableLocales();

        $validations = [];
        foreach ($locales as $locale) {
            $validations[$locale->code] = 'required';
        }

        $this->validate($request, $validations);

        // Create field
        $service->createField($request->all());

        return redirect()->action('Admin\FieldCreationController@view');
    }

    public function updateField($id, Request $request, RegisterFormService $service)
    {
        $locales = AppLocale::getAvailableLocales();

        $validations = [];
        foreach ($locales as $locale) {
            $validations[$locale->code] = 'required';
        }

        $this->validate($request, $validations);

        // update field
        $service->updateField($id, $request->all());

        return redirect()->action('Admin\FieldCreationController@view');
    }

    public function deleteField($id)
    {
        $field = RegisterFormField::findOrFail($id);

        $field->delete();

        return redirect()->action('Admin\FieldCreationController@view');
    }

    public function reorderField($id, Request $request, RegisterFormService $service)
    {
        $this->validate($request, [
            'swapWithFieldId' => 'required'
        ]);

        $fieldToSwap = $request->get('swapWithFieldId');

        $status = $service->swapField($id, $fieldToSwap);

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
}
