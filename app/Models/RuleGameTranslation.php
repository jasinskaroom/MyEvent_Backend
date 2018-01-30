<?php

namespace App\Models;

use Storage;
use Illuminate\Database\Eloquent\Model;
 

class RuleGameTranslation extends Model
{

	 
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'rule_game_translations';

    public $timestamps = false;

    protected $fillable = ['rule'];
}
