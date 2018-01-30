<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
 

class Gift extends Model
{
    use SearchableTrait;
     

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'gifts';

    protected $fillable = ['name', 'point'];

    public $timestamps = false;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'gifts.name' => 5,
            'gifts.point' => 4
        ]
    ];

    public function participants()
    {
        return $this->belongsToMany('App\Models\Participant', 'participants_rewards', 'gift_id', 'participant_id');
    }
}
