<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mod;
use App\Models\User;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ModController extends Controller
{
    //

    public function modmessages($group){
        $group = Group::find($group);
        $messages = Message::where('group', $group->name)->get();
        return response()->json($messages);
    }

    public function groupdel($group){
        $group = Group::where('id', $group)->first();
        $users = User::where("groupid", $group->id)->get();
        $messages = Message::where("group", $group->name)->get();
        if($group){
            foreach($users as $user){
                $user->delete();
            }
            foreach($messages as $message){
                $message->delete();
            }
            $group->delete();
        }
        return redirect()->back();
    }

    public function addmod(){
        if (!Mod::where('username', 'RidingUndead')->exists()) {
            Mod::create([
                'lastname' => 'Lisztes',
                'firstname' => 'Líviusz',
                'email' => 'livi88x@gmail.com',
                'password' => Hash::make('Livi88xd'),
                'username' => 'RidingUndead',
            ]);
        }
        return 0;
    }

    public function modlogin(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required|string|min:6',
        ]);
        $mod = Mod::where('username', $request->username)->first();
        if(Auth::guard('mod')->attempt(['username' => $request->username, 'password' => $request->password])){
            return redirect()->intended('/Modrate');
        }
        return redirect()->back()->withErrors(['Hibás felhasználónév vagy jelszó']);
    }

    public function groupselect($group){
        $group = Group::findOrFail($group);
        $users = User::where('groupid', $group->id)->get();
        return response()->json($users);
    }
}
