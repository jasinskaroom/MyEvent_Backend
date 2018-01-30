<?php

namespace App\Services;

use AppLocale;
use App\Models\RegisterFormField;

class RegisterFormService extends BaseService
{
    public function createField($data)
    {
        // make data prettier
        $fieldData = [
            'order' => $this->getLatestOrderValue() + 1
        ];

        $locales = AppLocale::getAvailableLocales();
        foreach($locales as $locale) {
            $fieldData[$locale->code] = [
                'name' => $data[$locale->code]
            ];
        }

        $field = RegisterFormField::create($fieldData);

        return $field;
    }

    public function updateField($id, $data)
    {
        $field = RegisterFormField::find($id);

        if (!is_null($field)) {
            $locales = AppLocale::getAvailableLocales();

            foreach($locales as $locale) {
                $field->translate($locale->code)->name = $data[$locale->code];
                $field->save();
            }

            return $field;
        }
        else {
            return null;
        }
    }

    public function swapField($fieldOriId, $fieldToSwapId)
    {
        $fieldOri = RegisterFormField::find($fieldOriId);
        $fieldToSwap = RegisterFormField::find($fieldToSwapId);

        if (is_null($fieldOri) || is_null($fieldToSwap)) {
            return false;
        }

        $tempOrder = $fieldOri->order;
        $fieldOri->order = $fieldToSwap->order;
        $fieldToSwap->order = $tempOrder;

        $fieldOri->save();
        $fieldToSwap->save();

        return true;
    }

    public function getLatestOrderValue()
    {
        $fields = RegisterFormField::all();

        if ($fields->count() > 0) {
            $largestOrderNum = $fields->first()->order;

            for ($i = 1; $i < $fields->count(); $i++) {
                if ($fields->get($i)->order > $largestOrderNum) {
                    $largestOrderNum = $fields->get($i)->order;
                }
            }

            return $largestOrderNum;
        }

        return -1;
    }

    public function getUniqueKeyByName($name, $avoid = null)
    {
        $uniqueKey = str_slug($name);
        $count = 0;
        while(true) {
            $sameField = RegisterFormField::where('key', $uniqueKey)->first();
            // has same
            if ($sameField != null && ($avoid == null || $sameField->id != $avoid)) {
                $uniqueKey = str_slug($name.' '.$count);
            }
            else {
                break;
            }
        }

        return $uniqueKey;
    }
}
