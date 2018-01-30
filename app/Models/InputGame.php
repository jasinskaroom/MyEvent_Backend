<?php

namespace App\Models;

use AppLocale;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
 

class InputGame extends Model
{
    use Translatable;
    use SearchableTrait;
     

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'input_games';

    protected $fillable = ['game_id', 'order'];

    public $translatedAttributes = [''];
    public $translationModel = 'App\Models\InputGameTranslation';
    public $translationForeignKey = 'input_game_id';

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [

        ],
        'joins' => [
            'input_game_translations' => ['input_games.id', 'input_game_translations.input_game_id']
        ]
    ];

    public function game()
    {
        return $this->belongsTo('App\Models\Game', 'game_id');
    }

    public function fields()
    {
        return $this->hasMany('App\Models\InputGameField', 'input_game_id');
    }
}
