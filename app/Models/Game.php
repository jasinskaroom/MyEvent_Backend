<?php

namespace App\Models;

use AppLocale;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;


class Game extends Model
{
    const TYPE_TEXT_INPUT = 'game_type_text_input';
    const TYPE_IMAGE_RULE = 'game_type_image_rule';

    use Translatable;
    use SearchableTrait;


    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'games';

    protected $fillable = ['stage_id', 'order', 'type', 'number_of_scan'];

    public $translatedAttributes = ['name'];
    public $translationModel = 'App\Models\GameTranslation';
    public $translationForeignKey = 'game_id';

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'game_translations.name' => 5
        ],
        'joins' => [
            'game_translations' => ['games.id', 'game_translations.game_id']
        ]
    ];

    public function stage()
    {
        return $this->belongsTo('App\Models\Stage', 'stage_id');
    }

    public function inputGame()
    {
        return $this->hasOne('App\Models\InputGame', 'game_id');
    }

    public function ruleGame()
    {
        return $this->hasOne('App\Models\RuleGame', 'game_id');
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

    public static function getGameTypes()
    {
        return [
            static::TYPE_TEXT_INPUT,
            static::TYPE_IMAGE_RULE
        ];
    }
}
