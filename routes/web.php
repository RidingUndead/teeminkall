<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Message;
use App\Models\Note;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', function () {
    if(Auth::check()) {
        Auth::logout();
    }
    return view('Index');
})->name('/');

Route::get('/About', function () {
    if(Auth::check()) {
        Auth::logout();
    }
    return view('About');
})->name('/About');

Route::get('/Register', function () {
    if(Auth::check()) {
        Auth::logout();
    }
    $groups = Group::all();
    return view('Register', compact('groups'));
})->name('/Register');

Route::get('/Banned', function(){
    if(Auth::check() && Auth::user()->status == "Member") {
        return view('ChatPage');
    }
    return view('Banned');
});

Route::get('/Kicked', function(){
    if(Auth::check() && Auth::user()->status == "Member") {
        return view('ChatPage');
    }
    return view('Kicked');
});

Route::get('/ChatPage', function () {
    if (!Auth::check()) {
        return redirect('/');
    }
    $user = User::where('id', Auth::user()->id)->first();
    if(Auth::check() && $user->status == "Kicked" && $user->kicked_until <= time()){
        $user->update([
            'kicked_until' => null,
            'status' => 'Member',
        ]);
        $user->save();
    }
    if(Auth::check() && $user['status'] == 'Kicked') {
        return view('Kicked');
    }else if(Auth::check() && $user['status'] == 'Banned') {
        return view('Banned');
    }
    
    $members = User::where('groupid', Auth::user()->groupid)->get();
    $group = Group::where('id', Auth::user()->groupid)->first();
    $notes = Note::where('groupid', Auth::user()->groupid)->get();
    $messages = Message::where('group', $group->name)->get();
    return view('ChatPage', compact('group', 'members', 'messages','notes'));
});

Route::post("login", [\App\Http\Controllers\ChatController::class, 'login'])->name('login');

Route::get("logout", [\App\Http\Controllers\ChatController::class, 'logout'])->name('logout');

Route::post("regadmin", [\App\Http\Controllers\ChatController::class, 'regadmin'])->name('regadmin');
Route::post("reguser", [\App\Http\Controllers\ChatController::class, 'reguser'])->name('reguser');

Route::post("approve/{id}", [\App\Http\Controllers\ChatController::class, 'approve'])->name('approve');
Route::post("reject/{id}", [\App\Http\Controllers\ChatController::class, 'reject'])->name('reject');
Route::post("kick/{id}", [\App\Http\Controllers\ChatController::class, 'kick'])->name('kick');
Route::post("unkick/{id}", [\App\Http\Controllers\ChatController::class, 'unkick'])->name('unkick');
Route::post("ban/{id}", [\App\Http\Controllers\ChatController::class, 'ban'])->name('ban');
Route::post("permaban/{id}", [\App\Http\Controllers\ChatController::class, 'permaban'])->name('permaban');

Route::post("message/{groupname}", [\App\Http\Controllers\ChatController::class, 'message'])->name('message');
Route::get("messages/{groupname}", [\App\Http\Controllers\ChatController::class, 'getMessages'])->name('messages');
Route::get('canwrite/{user}', [\App\Http\Controllers\ChatController::class, 'canWrite'])->name('canwrite');

Route::get("toggleDarkMode", [\App\Http\Controllers\ChatController::class, 'toggleDarkMode'])->name('toggleDarkMode');
Route::get("checkDarkMode", [\App\Http\Controllers\ChatController::class, 'checkDarkMode'])->name('checkDarkMode');

Route::get("/ModPage", function () {
    return view('ModPage');
});
Route::get("/Modrate", function () {
    if(!Auth::guard('mod')->check()) {
        return redirect('/');
    }
    $groups = Group::all();
    $users = User::all();
    $messages = Message::all();
    return view('Modrate', compact('groups', 'users', 'messages'));
});

Route::post('modlogin', [App\Http\Controllers\ModController::class, 'modlogin'])->name('modlogin');
Route::get('groupdel/{group}', [App\Http\Controllers\ModController::class, 'groupdel'])->name('groupdel');
Route::get('groupselect/{group}', [\App\Http\Controllers\ModController::class, 'groupselect'])->name('groupselect');
Route::post('addmod', [\App\Http\Controllers\ModController::class, 'addmod'])->name('addmod');

Route::post('createnote/{group}', [\App\Http\Controllers\ChatController::class, 'createnote'])->name('createnote');
Route::get('deletenote/{note}', [\App\Http\Controllers\ChatController::class, 'deletenote'])->name('deletenote');
Route::get('modmessages/{group}', [\App\Http\Controllers\ModController::class, 'modmessages'])->name('modmessages');