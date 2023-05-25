@php
use Illuminate\Http\Request;
use App\Models\Mod;
use App\Models\User;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;    
@endphp


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeeminKall moderálás</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">


    <link rel="stylesheet" href="{{asset('css/test.css')}}">
</head>
<body>
    <div class="navbar d-flex align-items-center">
        <div class="form-switch" id="toggle">
            <div class="lighton"><i id="d-switch" class="fas fa-moon"></i></div>
          </div>
    </div>
    <div class="container">
        <h1>Moderáció</h1>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <h3>Csoportok</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Név</th>
                            <th scope="col">Tagok</th>
                            <th scope="col">Akció</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($groups)
                        @foreach ($groups as $group)
                            <tr>
                                <td>
                                    <a class="selectable" onclick="select({{$group->id}})">{{ $group->name }}</a>
                                </td>
                                <td>{{ User::where('groupid', $group->id)->count() }}</td>
                                <td>
                                    <a href="{{route('groupdel', ['group' => $group->id])}}">Törlés</a>
                                </td>
                            </tr>

                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-md-9">
                @if (isset($group))
                    <h3>{{ $group->name }}</h3>
                    <hr>
                    <h4>Felhasználók</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Név</th>
                                <th scope="col">E-mail cím</th>
                                <th scope="col">Státusz</th>
                                <th scope="col">Akció</th>
                            </tr>
                        </thead>
                        <tbody id="usertable">
                        </tbody>
                    </table>
                    <hr>
                    <h4>Üzenetek</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Feladó</th>
                                <th scope="col">Üzenet</th>
                            </tr>
                        </thead>
                        <tbody id="messages">
                        </tbody>
                    </table>
                @else
                    <p>Válassz egy csoportot a moderáláshoz!</p>
                @endif

                
            </div>
        </div>
    </div>

    <div class="container">
        <form class="form-horizontal" action="{{route('regadmin')}}" method="POST">
            @csrf
            <div class="container tk-form justify-content-center">
                <input type="text" name="lastname" id="lastname" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-right border-0 tk-rounded" placeholder="Vezetéknév" />
                <input type="text" name="firstname" id="firstname" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-left border-0 tk-rounded" placeholder="Keresztnév" />
            </div>
            <div class="container tk-form justify-content-center">
                <input type="text" name="username" id="username" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-left border-0 tk-rounded" placeholder="Felhasználónév" />
                
                <input type="email" name="email" id="email" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-left border-0 tk-rounded" placeholder="email@cim.com" />
            </div>
            <div class="container tk-form justify-content-center">
                <input type="password" name="password" id="password" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-right border-0 tk-rounded" placeholder="Jelszó" autocomplete="new-password" />
                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-right border-0 tk-rounded" placeholder="Jelszó megint" autocomplete="new-password" />
            </div>
            <div class="container tk-form justify-content-center">
                <input type="text" name="group" id="group" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-left border-0 tk-rounded" placeholder="Csoportnév" />
            </div>
            <div class="container tk-form justify-content-center">
                <input type="submit" role="button" class="btn tk-blue tk-slide-green" value="Csoport létrehozása" />
            </div>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var g = null;

        function select(group) {
            g = group;
            $.ajax({
                type: 'GET',
                url: "{{route('groupselect', ['group' => ':group']) }}".replace(':group', group),
                dataType: 'json',
                data: {group: group},
                success: function (data) {
                    $('#usertable').html('');
                    $.each(data, function (index, user) { 
                        var row = $('<tr>');
                        var name = $('<td>').text(user.lastname+" "+user.firstname);
                        var email = $('<td>').text(user.email);
                        var status = $('<td>').text(user.status);
                        var action = $('<td>');
                                    
                        if(user.status == "Member"){
                            action.append($("<a>").attr("href", "{{ route('kick', ['id' => ':id']) }}".replace(':id', user.id)).text("Kick"));
                            action.append($("<a>").attr("href", "{{ route('ban', ['id' => ':id']) }}".replace(':id', user.id)).text("Ban"));
                        } else if(user.status == "Banned" || user.status == "Kicked"){
                            action.append($("<a>").attr("href", "{{ route('unkick', ['id' => ':id']) }}".replace(':id', user.id)).text("Unban"));
                        }
                        action.append($("<a>").attr("href", "{{ route('permaban', ['id' => ':id']) }}".replace(':id', user.id)).text("Felhasználó törlése"));
                        row.append(name, email, status, action);
                        $('#usertable').append(row);
                    });
                }
            });
        }
        
        setInterval(()=>{
            if(g != null){
            $.ajax({
                type: 'GET',
                url: "{{route('modmessages', ['group' => ':g'])}}".replace(':g', g),
                dataType: 'json',
                success: function (data) {
                    $('#messages').html('');
                    $.each(data, function (index, message) {
                        var row = $('<tr>');
                        var user = $('<td>').text(message.user);
                        var text = $('<td>').text(message.text);
                                
                        row.append(user, text);
                        $('#messages').append(row);
                    })
                }
            })
        }
        }, 500)
        let clickDisabled = false;
        $("#toggle").on("click",function() {
            if (clickDisabled) {
                event.stopPropagation();
                return;
            }
            clickDisabled = true;
            $("#toggle").prop("disabled", true);
            $.ajax({
                url: "{{route('toggleDarkMode')}}",
                type: "GET",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log('function called')
                    if (response.dark) {
                        $("body").addClass("dark");
                        $("#d-switch").removeClass("fa-moon").addClass("fa-sun");
                    } else {
                        $("body").removeClass("dark");
                        $("#d-switch").removeClass("fa-sun").addClass("fa-moon");
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                },

            });
            setTimeout(() => {
                clickDisabled = false;
                $("#toggle").prop("disabled", false);
            }, 500);
        });

        setInterval(() => {
            $.ajax({
                url: "{{route('checkDarkMode')}}",
                type: "GET",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.dark) {
                        $("#toggle-label").prop("checked", true);
                        $("#d-switch").removeClass("fa-moon").addClass("fa-sun");
                        $("body").addClass("dark");
                    } else {
                        $("#toggle-label").prop("checked", false);
                        $("#d-switch").removeClass("fa-sun").addClass("fa-moon");
                        $("body").removeClass("dark");
                    }
                },
                error: function(xhr){
                    $("#error").text(xhr.responseText);
                }
            });
        }, 500);


    </script>
</body>
</html>