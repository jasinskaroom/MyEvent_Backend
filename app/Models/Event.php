<?php

namespace App\Models;

use AppLocale;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
 

class Event extends Model
{
    use Translatable;
    use SearchableTrait;
     

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'events';

    protected $fillable = ['activate', 'logo_filename', 'logo_mime', 'app_background_filename', 'app_background_mime', 'font_color','event_secret_id'];

    public $translatedAttributes = ['name'];
    public $translationModel = 'App\Models\EventTranslation';
    public $translationForeignKey = 'event_id';

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'event_translations.name' => 5
        ],
        'joins' => [
            'event_translations' => ['events.id', 'event_translations.event_id']
        ]
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // Remove logo files
            if (\Storage::disk('event_logo')->exists($model->logo_filename)) {
                \Storage::disk('event_logo')->delete($model->logo_filename);
            }
        });
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

    public function banners()
    {
        return $this->hasMany('App\Models\EventBanner', 'event_id');
    }

    public function stages()
    {
        return $this->hasMany('App\Models\Stage', 'event_id');
    }

    public function qrcodes()
    {
        return $this->hasMany('App\Models\QRCode', 'event_id');
    }

    public function participants()
    {
        return $this->hasMany('App\Models\Participant', 'event_id');
    }

    public function logoUrl()
    {
        return action('ImageViewController@viewImage', [
            'storage' => 'event_logo',
            'filename' => $this->logo_filename
        ]);
    }

    public function appBackgroundUrl()
    {
        return action('ImageViewController@viewImage', [
            'storage' => 'event',
            'filename' => $this->app_background_filename
        ]);
    }

    public function hasAppBackground()
    {
        return !is_null($this->app_background_filename) && !empty($this->app_background_filename);
    }

    public function scopeActiveOnly($query)
    {
        return $query->where('activate', true);
    }

    public function scopeClosedOnly($query)
    {
        return $query->where('activate', false);
    }

    public function getActivityTitle($locale=null){
        if(!$locale) $locale =\Request::get('locale');
        return $this->hasOne(\App\Models\EventActivityTranslation::class)->where('locale',$locale)->first();
    }

    public function managers(){
        return $this->hasMany(\App\Models\EventManager::class, 'event_id');
    }


}
