<?php

namespace App\Models;

use AppLocale;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
 

class InputGameField extends Model
{
    use Translatable;
    use SearchableTrait;
     

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'input_game_fields';

    protected $fillable = ['input_game_id'];

    public $translatedAttributes = ['name'];
    public $translationModel = 'App\Models\InputGameFieldTranslation';
    public $translationForeignKey = 'field_id';

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'input_game_field_translations.name' => 5
        ],
        'joins' => [
            'input_game_field_translations' => ['input_game_fields.id', 'input_game_field_translations.field_id']
        ]
    ];

    public function inputGame()
    {
        return $this->belongsTo('App\Models\InputGame', 'input_game_id');
    }
}
