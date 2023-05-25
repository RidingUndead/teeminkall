
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' *; script-src 'self' 'unsafe-inline' 'unsafe-eval' *; style-src 'self' 'unsafe-inline' *; img-src 'self' data: *">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    
    <title>Teeminkall - {{$group->name}}</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/test.css') }}">
</head>

<body style="height:100vh">
    <a role="button" class="btn d-block d-md-none btn-slide-green fixed" onclick="document.querySelector('.sidebar').classList.toggle('active')">Menü megnyitása</a>
    
<div class="container-fluid tk-row-12">
        @if(Auth::user()->status == "Waiting")
        <div class="container d-flex align-items-center justify-content-center">
            <div class="tk-green text-center">
                    <h1>Még nem vagy tag!</h1>
                    <p>Az admin intézkedik effelől, addig is türelmedet kérjük...</p>
                </div>
        </div>
        @else
        <div class="col-md-10 col-12 tk-yellow mt-0 tk-row-12 float-left tk-messages">
            <div class="container-fluid tk-row-11 mt-0" id="messages-container">
                
                @foreach($messages as $message)
                    @if($message->user == Auth::user()->username)
                        <div class="container-fluid d-flex justify-content-end">
                            <div id="msg-con" class="tk-user-message">
                                <p class="username">{{ $message->user}}</p>
                                <p>{{ $message->text}}</p>
                            </div>
                        </div>
                    @else
                        <div class="container-fluid d-flex justify-content-left">
                            <div id="msg-con" class="tk-other-message">
                                <p class="username">{{ $message->user}}</p>
                                <p>{{ $message->text}}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="container-fluid tk-row-1 mt-0">
                <form id="message-form">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control tk-rounded-left" id="message" name="message" disabled>
                        <input type="submit" class="input-group-text tk-slide-green" id="basic-addon2" value="&#9658;">
                    </div>
                </form>

            </div>
            
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                
                var username = "{{ Auth::user()->username }}";
                var groupName = "{{ $group->name }}";
                var messageForm = $("#message-form");
                var messageInput = $("#message");
                var messagesContainer = $("#messages-container");


                messageForm.submit(function(e) {
                    e.preventDefault();

                    var message = messageInput.val();

                    $.ajax({
                        type: "POST",
                        url: "{{ route('message', ['groupname' => $group->name])}}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            message: message,
                        },
                        success: function(data) {
                            console.log(data);
                            messageInput.val("");
                        }
                    });
                });
                var lastSize = 0
                function getMessages() {
                    $.ajax({
                        type: 'GET',
                        url: '/messages/{{ $group->name }}',
                        dataType: 'json',
                        success: function(data) {
                            var currentSize = data.length;
                            messagesContainer.html('');
                            $.each(data, function(index, message) {
                                var messageElement = $('<div>').addClass('container-fluid d-flex');
                                var messageBodyElement = $('<div>').addClass('msg-con');
                                var badgeElement = $('<p>').addClass('username display-6').text(message.user);
                                var badgeElement = $('<p>').addClass('username').text(message.user);
                                var textElement = $('<p>').text(message.text);

                                if (message.user === "{{ Auth::user()->username }}") {
                                    messageElement.addClass('justify-content-end');
                                    messageBodyElement.addClass('tk-user-message');
                                } else {
                                    messageElement.addClass('justify-content-start');
                                    messageBodyElement.addClass('tk-other-message');
                                }

                                messageBodyElement.append(badgeElement);
                                messageBodyElement.append(textElement);
                                messageElement.append(messageBodyElement);
                                messagesContainer.append(messageElement);
                            });
                            if(lastSize < currentSize){
                                lastSize = currentSize;
                                messagesContainer.scrollTop(messagesContainer.prop("scrollHeight"));
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error:', textStatus, errorThrown);
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('canwrite', ['user' => Auth::user()->id]) }}",
                        dataType: 'json',
                        success: function(data) {
                            if (data.canwrite) {
                                messageInput.attr('disabled', 'disabled');
                            } else {
                                messageInput.removeAttr('disabled');
                            }
                        }
                    });
                }

                setInterval(function() {
                    getMessages();
                }, 500);
                    

            </script>
        </div>
        @endif
    </div>
    
    <div class="sidebar col-2 m-0 clearfix p-0 overflow-hidden">
        <a role="button" class="btn d-block d-md-none btn-slide-green" onclick="document.querySelector('.sidebar').classList.toggle('active')">Menü bezárása</a>
        <h6 class="display-6 side mt-0">{{$group->name}}</h6>
      <div class="h-45 side m-0 overflow-x-auto tk-row-6">
        @if(Auth::user()->rank == 'Admin')
            <ul class="list-group">
                @foreach($members as $member)
                    @if($member->username == Auth::user()->username)
                        <li class="list-group-item list-group-item-danger text-dark">
                            <div class="col-12">{{ $member->lastname }} {{ $member->firstname }}</div>
                        </li>
                    @elseif($member->status == "Waiting")
                        <li class="list-group-item list-group-item-secondary text-dark">
                            <div class="col-12">{{ $member->lastname }} {{ $member->firstname }}</div>
                            <div class="col-12">
                                <form action="{{ route('approve', ['id' => $member->id]) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="status" value="Member">
                                    <button type="submit">Felvétel</button>
                                </form>
                                <form action="{{ route('reject', ['id' => $member->id]) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="status" value="Rejected">
                                    <button type="submit">Elutasít</button>
                                </form>
                            </div>
                        </li>
                    @elseif($member->status == "Member")
                        <li class="list-group-item list-group-item-success text-dark">
                            <div class="col-12">{{ $member->lastname }} {{ $member->firstname }}</div>
                            <div class="col-12">
                                <form action="{{ route('kick', ['id' => $member->id]) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="status" value="Kicked">
                                    <button type="submit">Kickelés</button>
                                </form>
                                <form action="{{ route('ban', ['id' => $member->id]) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="status" value="Banned">
                                    <button type="submit">Bannolás</button>
                                </form>
                            </div>
                        </li>
                    @elseif($member->status == "Kicked")
                        <li class="list-group-item list-group-item-danger text-dark">
                            <div class="col-12">{{ $member->lastname }} {{ $member->firstname }}</div>
                            <div class="col-12">
                                <form action="{{ route('unkick', ['id' => $member->id]) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="status" value="Kicked">
                                    <button type="submit">Feloldás</button>
                                </form>
                                <form action="{{ route('ban', ['id' => $member->id]) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="status" value="Banned">
                                    <button type="submit">Bannolás</button>
                                </form>
                            </div>
                        </li>
                    @elseif($member->status == "Banned")
                    <li class="list-group-item list-group-item-danger text-dark">
                        <div class="col-12">{{ $member->lastname }} {{ $member->firstname }}</div>
                        <div class="col-12">
                            <form action="{{ route('kick', ['id' => $member->id]) }}" method="post">
                                @csrf
                                <input type="hidden" name="status" value="Kicked">
                                <button type="submit">Feloldás</button>
                            </form>
                            <form action="{{ route('permaban', ['id' => $member->id]) }}" method="post">
                                @csrf
                                <input type="hidden" name="status" value="Banned">
                                <button type="submit">Véglegesítés</button>
                            </form>
                        </div>
                    </li>
                    @else
                        <li class="list-group-item list-group-item-primary text-dark">{{ $member->lastname }} {{ $member->firstname }}</li>
                    @endif
                @endforeach
            </ul>
        @else
            <ul class="list-group">
                @foreach($members as $member)
                    @if($member->status != "Waiting")
                        @if($member->status == "Member")
                            <li class="list-group-item list-group-item-info text-dark">{{ $member->lastname }} {{ $member->firstname }}</li>
                        @else
                            <li class="list-group-item list-group-item-danger text-dark">{{ $member->lastname }} {{ $member->firstname }}</li>
                        @endif
                    @endif
                @endforeach
            </ul>
        @endif
      </div>

      <div class="h-45 side m-0 overflow-x-auto settings">
        <ul class="list-setting">
            <li class="list-setting-item">
                <a class="btn tk-slide-blue" role="button" id="change" onclick="changeDarkMode()">Sötét mód</a>
            </li>
            <li class="list-setting-item">
                <a class="btn tk-slide-blue"role="button" href="{{ route('logout') }}">Kilépés</a>
            </li>
            <li class="list-setting-item">
                <a class="btn tk-slide-blue" role="button" data-bs-toggle="modal" data-bs-target="#notes">Jegyzetek</a>
            </li>
        </ul>
        
    </div>
  </div>

  <div class="modal fade" id="notes" tabindex="-1" role="dialog" aria-labelledby="notes" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="notes">Jegyzetek</h5>
        </div>
        <div class="modal-body">
            @if($notes)
                @foreach ($notes as $note)
                    <div class="alert alert-info">
                        <h4 class="alert-heading">
                            {{ $note->title }}
                        </h4>
                        <div class="alert-body">
                            {{ $note->text }}
                            @if (Auth::user()->rank == 'Admin')
                                <a class="btn btn-primary tk-slide-red" href="{{ route('deletenote', ['note' => $note]) }}">
                                    Jegyzet törlése
                                </a>
                            @endif
                        </div>
                        
                    </div>
                @endforeach
            @else
                <div class="alert alert-danger">
                    <h4 class="alert-heading">
                        Nincs egy jegyzet sem.
                    </h4>
                    <div class="alert-body">
                        A csoport vezetője tud csak létrehozni.
                    </div>
                    
                </div>
            @endif

            @if(Auth::user()->rank == 'Admin')
                <div>
                    <form action="{{ route('createnote', ['group'=>$group]) }}" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" class="form-control tk-rounded-left" id="title" name="title" placeholder="Cím" style="font-weight: 300" onkeydown="return event.key != 'Enter';">
                            <input type="submit" class="input-group-text tk-slide-green" id="basic-addon2" value="&#9658;">
                        </div>
                        <div class="input-group mb-3">
                            <textarea class="form-control tk-rounded-left" id="text" name="text" placeholder="Szöveg"></textarea>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
    </div>
  </div>
    <script>

        function activemenu(){
            if($('.sidebar').hasClass('active')){
                $('.sidebar').removeClass('active');
            }else{
                $('.sidebar').addClass('active');
            }
        }

        let clickDisabled = false;
        $("#change").on("click",function() {
            if (clickDisabled) {
                event.stopPropagation();
                return;
            }
            clickDisabled = true;
            $("#change").prop("disabled", true);
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
                    } else {
                        $("body").removeClass("dark");
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                },
                
            });
            setTimeout(() => {
                clickDisabled = false;
                $("#change").prop("disabled", false);
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
                        $("body").addClass("dark");
                    } else {
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