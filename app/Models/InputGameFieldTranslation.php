<?php

namespace App\Models;

use Storage;
use Illuminate\Database\Eloquent\Model;
 

class InputGameFieldTranslation extends Model
{

	 
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'input_game_field_translations';

    public $timestamps = false;

    protected $fillable = ['name'];
}
