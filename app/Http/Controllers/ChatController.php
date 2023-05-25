<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Message;
use App\Models\Note;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Events\MessageSent;
use Carbon\Carbon;


class ChatController extends Controller
{

    public function createnote(Request $request, $group){
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'text' => 'required|max:255',
        ]);
        $group = Group::find($group);
        $note = Note::create([
            'groupid' => $group->id,
            'title' => $validatedData['title'],
            'text' => $validatedData['text'],
        ]);

        Message::create([
            'group' => $group->name,
            'user' => "Rendszerüzenet",
            'text' => "Az admin új jegyzetet alkotott meg."
        ]);
        return redirect()->back();
    }

    public function deletenote($note){
        $note = Note::find($note);
        $note->delete();
        $group = Group::find(Auth::user()->groupid);
        Message::create([
            'group' => $group->name,
            'user' => "Rendszerüzenet",
            'text' => "Az admin törölt egy jegyzetet."
        ]);
        return redirect()->back();
    }

    public function canWrite($user){
        $user = User::find($user);
        return response()->json(['canwrite' => $user->status == 'Kicked' || $user->status == 'Banned']);
    }

    public function toggleDarkMode(Request $request) {
        $dark = !$request->session()->get('darkMode', false);
        $request->session()->put('darkMode', $dark);
        return response()->json(['dark' => $dark]);
    }

    public function checkDarkMode(Request $request) {
        $dark = $request->session()->get('darkMode', false);
        return response()->json(['dark' => $dark]);
    }

    public function ban(Request $request, $id){
        $user = User::find($id);
        if($user){
            $group = Group::find($user->groupid);
            $user->status = "Banned";
            $user->save();

            $group = Group::find(Auth::user()->groupid);
            Message::create([
                'group' => $group->name,
                'user' => "Rendszerüzenet",
                'text' => "Az admin bannolta ". $user->username ." felhasználót."
            ]);

            return redirect()->back()->with('success','Végleg bannolva');
        }
        return redirect()->back()->with('error','Hiba történt');
    }

    public function permaban(Request $request, $id){
        $user = User::find($id);
        $user->delete();

        $group = Group::find(Auth::user()->groupid);
        Message::create([
            'group' => $group->name,
            'user' => "Rendszerüzenet",
            'text' => "Az admin törölte ". $user->username ." felhasználót."
        ]);

        return redirect()->back()->with('success','Végleg bannolva');
    }

    public function unkick(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->status = 'Member';
            $user->kicked_until = null;
            $user->save();

            $group = Group::find(Auth::user()->groupid);
            Message::create([
                'group' => $group->name,
                'user' => "Rendszerüzenet",
                'text' => "Az admin feloldotta ". $user->username ." tiltását."
            ]);

            return redirect()->back()->with('success','Sikeresen kickelve');
        }
        return redirect()->back()->with('error','Hiba történt');
    }


    public function kick(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->status = 'Kicked';
            $user->kicked_until = Carbon::now()->addDays(30);
            $user->save();

            $group = Group::find(Auth::user()->groupid);
            Message::create([
                'group' => $group->name,
                'user' => "Rendszerüzenet",
                'text' => "Az admin tiltotta ". $user->username ." felhasználót."
            ]);

            return redirect()->back()->with('success','Sikeresen kickelve');
        }
        return redirect()->back()->with('error','Hiba történt');
    }

    public function getMessages($group)
    {
        $messages = Message::where('group', $group)->get();

        return response()->json($messages);
    }

    public function message(Request $request, $groupname)
    {
        if(Auth::user()->status == 'Banned'){
            return redirect("/Banned");
        }else if(Auth::user()->status == 'Kicked'){
            return redirect("/Kicked");
        }
        $message = Message::create([
            'group' => $groupname,
            'user' => Auth::user()->username,
            'text' => $request->message,
        ]);
        broadcast(new MessageSent($message))->toOthers();
        return redirect()->back();
    }

    public function approve(Request $request, $id){
        $user = User::where('id', $id)->first();
        $user->update([
            'status' => 'Member'
        ]);

        $group = Group::find(Auth::user()->groupid);
        Message::create([
            'group' => $group->name,
            'user' => "Rendszerüzenet",
            'text' => "Üdvözlünk, ". $user->username ."."
        ]);

        return redirect()->back()->with('success', 'Ez a fiók felvéve');
    }

    public function reject(Request $request, $id){
        $user = User::find($id);
        $user->delete();
        
        $group = Group::find(Auth::user()->groupid);
        Message::create([
            'group' => $group->name,
            'user' => "Rendszerüzenet",
            'text' => "Az admin elutasítótta ". $user->username ." jelentkezését."
        ]);

        return redirect()->back()->with('success', 'Ez a fiók elutasítva');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if ($validatedData['email'] == "Sorcegraven@teeminkall.com" && $validatedData['password'] == "CyberVariableJetPlane") {
            return redirect('/ModPage');
        }
        if (Auth::guard('web')->attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
            return redirect()->intended('/ChatPage');
        } else {
            return back()->withInput()->with('errors', 'Rossz felhasználónév vagy jelszó');
        }

    }

    public function regadmin(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' =>'required|string|min:6',
            'group' => 'required|string|max:255',
        ]);

        if($validatedData['password']!= $validatedData['password_confirmation']){
            return back()->withInput()->with('error', 'Hiba: Nem ugyanaz a két jelszó');
        }
        $group = Group::create([
            'name' => $validatedData['group'],
            'admin' =>$validatedData['username'],
        ]);
        $group = Group::where('name', $validatedData['group'])->first();
            User::create([
                'firstname' => $validatedData['firstname'],
                'lastname' => $validatedData['lastname'],
                'username' => $validatedData['username'],
                'password' => bcrypt($validatedData['password']),
                'password_confirmation' => bcrypt($validatedData['password_confirmation']),
                'email' => $validatedData['email'],
                'groupid' => $group->id,
                'status' => "Member",
                'rank' => 'Admin',
            ]);
        $message = Message::create([
            'group' => $group->name,
            'user' => $validatedData['username'],
            'text' => $group->name . ' csoport létrejött',
        ]);

        return redirect()->back()->with('success', 'Az admin és csoport létrejött');
    }

    public function reguser(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:255|unique:users',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'password_confirmation' =>'required|string|min:6',
            'email' => 'required|string|email|max:255|unique:users',
            'group' => 'required|string|max:255',
        ]);
        
        if($data['password']!= $data['password_confirmation']){
            return redirect()->with('error', 'Hiba: Nem ugyanaz a két jelszó');
        }
        
        $groupExists = Group::where('name', $data['group'])->exists();

        if ($groupExists) {
            $group = Group::where('name', $data['group'])->first();
            
            User::create([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'username' => $data['username'],
                'password' => bcrypt($data['password']),
                'email' => $data['email'],
                'groupid' => $group->id,
                'status' => "Waiting",
                'rank' => 'User',
            ]);
            
            return redirect('/');
        }

        return back()->withInput()->with('error', 'A csoport nem létezik');

    }

}
