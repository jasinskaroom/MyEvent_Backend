<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
 

class User extends Authenticatable
{
    use Notifiable;
    use SearchableTrait;
     

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'mobile_number', 'identity_passport',
        'gender', 'pre_registration', 'activated'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'username'
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'users.name' => 5,
            'users.email' => 5,
            'users.gender' => 1,
            'users.mobile_number' => 5,
            'users.identity_passport' => 4
        ]
    ];

    /**
     * Automatically convert password to encrypted
     *
     * @param [type] $password [description]
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = !is_null($password) ? bcrypt($password) : null;
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    public function isAdmin()
    {
        return $this->role->name == Role::ADMIN;
    }

    public function isManager(){
        return $this->role->name == Role::MANAGER;
    }

    public function scopeUserOnly($query)
    {
        $roleId = Role::lookupRole(Role::USER)->id;

        return $query->where('role_id', $roleId);
    }

    public function myEvent(){
        return $this->hasOne('App\Models\Event', 'manager_id');
    }

    public function manageEvents(){
        return $this->hasMany('App\Models\EventManager', 'manager_id');
    }

    public function manageEventsHtml(){
        $str = '';
        foreach($this->manageEvents as $manager){
            if (!is_null($manager->event)) {
                $str.="<span class='label label-info'>{$manager->event->getAllLocalesString()}</span> ";
            }
        }

        return $str;
    }
}
