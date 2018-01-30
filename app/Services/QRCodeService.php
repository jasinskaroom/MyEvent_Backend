<?php

namespace App\Services;

use QRCodeFactory;
use App\Models\QRCode;
use Illuminate\Support\Facades\Storage;

class QRCodeService extends BaseService
{
    public function createQRCode($eventId, $point)
    {
        $qrcode = new QRCode();
        $qrcode->event_id = $eventId;
        $qrcode->point = $point;
        $qrcode->secret = bcrypt(time());
        $qrcode->image_filename = $this->storeQRCodeImage($qrcode, $this->generateQRCodeImage($qrcode->secret));
        $qrcode->save();

        return $qrcode;
    }

    public function updateQRCode($id, $eventId, $point)
    {
        $qrcode = QRCode::find($id);

        if (!is_null($qrcode)) {
            $qrcode->event_id = $eventId;
            $qrcode->point = $point;
            $qrcode->save();

            return $qrcode;
        }
        else {
            return null;
        }
    }

    public function deleteQRCode($id)
    {
        $qrcode = QRCode::findOrFail($id);
        if (is_null($qrcode)) {
            return;
        }

        $imageFilename = $qrcode->image_filename;

        // remove
        $qrcode->delete();

        // remove image
        Storage::disk('qrcode')->delete($imageFilename);
    }

    public function generateQRCodeImage($value)
    {
        $qrcodeImage = QRCodeFactory::format(QRCode::IMAGE_FORMAT)
                ->size(QRCode::IMAGE_SIZE)
                ->generate($value);

        return $qrcodeImage;
    }

    public function storeQRCodeImage($qrcode, $image)
    {
        $filename = md5($qrcode->secret);
        $extension = '.'.QRCode::IMAGE_FORMAT;

        $fullname = $filename.$extension;

        Storage::disk('qrcode')->put($fullname, $image);

        return $fullname;
    }
}
