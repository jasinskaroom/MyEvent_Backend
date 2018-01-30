<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 

class Role extends Model
{
     
    const ADMIN = 'admin';
    const USER = 'user';
    const MANAGER = 'manager';

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'roles';

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany('App\Models\User', 'role_id');
    }

    public static function lookupRole($roleName) {
        return Role::where('name', $roleName)->first();
    }
}
