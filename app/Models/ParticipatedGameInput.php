<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 

class ParticipatedGameInput extends Model
{
     
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'participated_game_inputs';

    protected $fillable = ['participated_game_id', 'field_id', 'value'];

    public $timestamps = false;

    public function field()
    {
        return $this->belongsTo('App\Models\InputGameField', 'field_id');
    }
}
