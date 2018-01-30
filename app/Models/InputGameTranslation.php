<?php

namespace App\Models;

use Storage;
use Illuminate\Database\Eloquent\Model;
 

class InputGameTranslation extends Model
{

	 
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'input_game_translations';

    public $timestamps = false;

    protected $fillable = [];
}
