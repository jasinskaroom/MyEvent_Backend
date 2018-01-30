<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 

class RegisterFormFieldTranslation extends Model
{
	 
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'register_form_field_translations';

    public $timestamps = false;

    protected $fillable = ['name'];
}
