<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 

class EventTranslation extends Model
{

	 

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'event_translations';

    public $timestamps = false;

    protected $fillable = ['name'];


}
