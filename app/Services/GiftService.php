<?php

namespace App\Services;

use AppLocale;
use App\Models\Gift;

class GiftService extends BaseService
{
    public function createGift($data)
    {
        $gift = Gift::create($data);

        return $gift;
    }

    public function updateGift($id, $data)
    {
        $gift = Gift::find($id);

        if (!is_null($gift)) {
            $gift->update($data);

            return $gift;
        }
        else {
            return null;
        }
    }
}
