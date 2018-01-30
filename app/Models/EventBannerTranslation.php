<?php

namespace App\Models;

use Storage;
use Illuminate\Database\Eloquent\Model;
 

class EventBannerTranslation extends Model
{
     

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'event_banner_translations';

    public $timestamps = false;

    protected $fillable = ['filename', 'mime', 'name'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // Remove image files
            $model->deleteFile();
        });
    }

    public function deleteFile()
    {
        if (Storage::disk('event_banner')->exists($this->filename)) {
            Storage::disk('event_banner')->delete($this->filename);
        }
    }
}
