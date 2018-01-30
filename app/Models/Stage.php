<?php

namespace App\Models;

use AppLocale;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
 

class Stage extends Model
{
    use Translatable;
    use SearchableTrait;
     

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'stages';

    protected $fillable = ['event_id', 'game_type', 'order'];

    public $translatedAttributes = ['name'];
    public $translationModel = 'App\Models\StageTranslation';
    public $translationForeignKey = 'stage_id';

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'stage_translations.name' => 5
        ],
        'joins' => [
            'stage_translations' => ['stages.id', 'stage_translations.stage_id']
        ]
    ];

    public function event()
    {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }

    public function games()
    {
        return $this->hasMany('App\Models\Game', 'stage_id');
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
}
