<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EventManager;
use App\Models\User;
use App\Models\Event;
use App\Models\Role;

class EventManagerController extends Controller
{
    //

    public function index(){
        $hasEvent = Event::all()->count() > 0;

        if ($hasEvent) {
            $managers = User::where('role_id', Role::lookupRole(Role::MANAGER)->id)->get();

        	return view('admin.managers.index', compact('managers'));
        }
        else {
            session()->flash('system_status', 'Please create an event before creating Manager.');

            return redirect()->action('Admin\EventController@showEventList');
        }
    }

    public function create(){

        $events  = event::all();

    	return view('admin.managers.create', compact('events'));
    }

    public function edit($id){
        $manager = User::find($id);
       $events  = event::all();
        return view('admin.managers.edit', compact('manager','events'));
    }

    public function store(Request $request){

         $this->validate($request,[
            'username'=>'required|unique:users',
            'email'   =>'required|unique:users',
            'password'=>'required'

        ]);
        $manager = new User;
        $manager->mobile_number = '';
        $manager->identity_passport = '';
        $manager->role_id = Role::lookupRole(Role::MANAGER)->id;

        $manager->name = $request->name;
        $manager->username = $request->username;
        $manager->password = $request->password;
        $manager->email   = $request->email;
        $manager->save();

        $this->updatePermission($manager->id,$request->event_id);

        return redirect()->action('Admin\EventManagerController@index');

    }

    public function update($id, request $request){

        $manager = User::find($id);
        $manager->name = $request->name;
        $manager->username = $request->username;
        if(!empty($manager->password))
        $manager->password = $request->password;

        $manager->email   = $request->email;
        $manager->save();


        $this->updatePermission($manager->id,$request->event_id);

        return redirect()->action('Admin\EventManagerController@index');
    }

    public function delete($id){
        $manager = user::find($id);
        $manager->delete();


        return redirect()->action('Admin\EventManagerController@index');
    }

    public function updatePermission($id,$event_ids){
        \DB::table('event_managers')->where(['manager_id'=>$id])->delete();

        foreach($event_ids as $event){
            $event_manager = new EventManager;
            $event_manager->manager_id = $id;
            $event_manager->event_id = $event;
            $event_manager->save();
        }
    }

}
