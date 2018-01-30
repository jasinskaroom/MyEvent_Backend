<?php

namespace App\Models;

use QRCodeFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
 

class QRCode extends Model
{
     
    const IMAGE_SIZE = 400;
    const IMAGE_FORMAT = 'png';

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'qr_codes';

    protected $fillable = ['point', 'secret'];

    public function event()
    {
        return $this->hasMany('App\Models\Event', 'event_id');
    }

    public function imageUrl()
    {
        return action('Admin\QRCodeController@viewQRCodeImage', ['id' => $this->id]);
    }

    public function base64Image()
    {
        $image = Storage::disk('qrcode')->get($this->image_filename);

        $base64Image = 'data:image/'.QRCode::IMAGE_FORMAT.';base64,'.base64_encode($image);

        return $base64Image;
    }
}
