<?php

namespace App\Models;

use Storage;
use Illuminate\Database\Eloquent\Model;
 

class GameTranslation extends Model
{

	 
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'game_translations';

    public $timestamps = false;

    protected $fillable = ['name'];
}
