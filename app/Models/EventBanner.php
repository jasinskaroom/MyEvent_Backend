<?php

namespace App\Models;

use AppLocale;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
 

class EventBanner extends Model
{
    use Translatable;
     

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'event_banners';

    protected $fillable = ['event_id', 'order'];

    public $translatedAttributes = ['filename', 'mime', 'name'];
    public $translationModel = 'App\Models\EventBannerTranslation';
    public $translationForeignKey = 'banner_id';

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // Remove image files
            $model->deleteFiles();
        });
    }

    public function event()
    {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }

    public function getAllLocalesString($separator = '|')
    {
        $locales = AppLocale::getAvailableLocales();

        $localeString = '';
        $count = 0;
        foreach($locales as $locale) {
            $localeString = $localeString . $this->translate($locale->code)->name;

            if ($count != count($locales) - 1) {
                $localeString = $localeString . ' ' . $separator . ' ';
            }

            $count++;
        }

        return $localeString;
    }

    public function getImageUrl($locale)
    {
        return action('Admin\EventController@viewEventBannerImage', ['id' => $this->id, 'locale' => $locale]);
    }

    public function deleteFiles()
    {
        $this->translations()->get()->each(function($translation) {
            $translation->deleteFile();
        });
    }
}
