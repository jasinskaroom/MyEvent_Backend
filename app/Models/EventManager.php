<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventManager extends Model
{
    //
    public function user(){
    	return $this->belongsTo(\App\Models\User::class,'manager_id');
    }

    public function event(){
    	return $this->belongsTo(\App\Models\Event::class);
    }
}
