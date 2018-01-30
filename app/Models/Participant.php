<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

use App\Models\Game;

class Participant extends Model
{

    use SearchableTrait;

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'participants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'mobile_number', 'identity_passport',
        'gender', 'pre_registration', 'activated', 'event_id','device_id'
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'participants.name' => 5,
            'participants.email' => 5,
            'participants.gender' => 1,
            'participants.mobile_number' => 5,
            'participants.identity_passport' => 4,
            'participants.draw_id' => 5,
            'registration_forms.value' => 1
        ],
        'joins' => [
            'registration_forms' => ['participants.id', 'registration_forms.participant_id']
        ]
    ];

    public function getIdAttribute($value) {
        return sprintf('%05d', $value);
    }

    public function getDrawIdAttribute($value) {
        return sprintf('%05d', $value);
    }

    public function generateApiToken() {
        $this->api_token = str_random(60);
        $this->save();
    }

    public function event()
    {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }

    public function fields()
    {
        return $this->belongsToMany('App\Models\RegisterFormField', 'registration_forms', 'participant_id', 'field_id')
                    ->withPivot('value')
                    ->using('App\Models\RegistrationForm');
    }

    public function participatedGames()
    {
        return $this->hasMany('App\Models\ParticipatedGame', 'participant_id');
    }

    public function gifts()
    {
        return $this->belongsToMany('App\Models\Gift', 'participants_rewards', 'participant_id', 'gift_id');
    }

    public function getFieldValue($fieldId)
    {
        $field = $this->fields()->where('id', $fieldId)->first();

        return !is_null($field) ? $field->pivot->value : null;
    }

    public function getAllGiftsString($separator = '|')
    {
        $giftString = '';
        $count = 0;
        foreach($this->gifts as $gift) {
            $giftString = $giftString . $gift->name;

            if ($count != count($this->gifts) - 1) {
                $giftString = $giftString . ' ' . $separator . ' ';
            }

            $count++;
        }

        return $giftString;
    }

    public function hasParticipatedInGame($gameId)
    {
        return $this->participatedGames()->where('game_id', $gameId)->first() != null;
    }
    public function hasParticipatedInGames($gameId)
    {
        return $this->participatedGames()->where('game_id', $gameId)->count();
    }

    public function hasParticipatedInGameModel($gameId){
        return $this->participatedGames()->where('game_id', $gameId)->first();
    }

    public function isParticipationInGameCompleted($gameId){

        if(!$this->hasParticipatedInGame($gameId)) return false;
        if($this->hasParticipatedInGames($gameId) >= Game::find($gameId)->number_of_scan){
            return true;
        }
        else {
            return false;
        }
    }

    public function numberOfScans(){
        return $this->participatedGames()->count();
    }

    public function totalPoints(){
        return $this->participatedGames()->sum('score');
    }
}
