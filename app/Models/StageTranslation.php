<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 

class StageTranslation extends Model
{

	 
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'stage_translations';

    public $timestamps = false;

    protected $fillable = ['name'];


}
