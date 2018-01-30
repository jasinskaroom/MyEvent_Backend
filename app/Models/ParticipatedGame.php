<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 

class ParticipatedGame extends Model
{
     
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'participated_games';

    protected $fillable = ['participant_id', 'game_id', 'score'];

    public function participant()
    {
        return $this->belongsTo('App\Models\Participant', 'participant_id');
    }

    public function game()
    {
        return $this->belongsTo('App\Models\Game', 'game_id');
    }

    public function inputs()
    {
        return $this->hasMany('App\Models\ParticipatedGameInput', 'participated_game_id');
    }
}
