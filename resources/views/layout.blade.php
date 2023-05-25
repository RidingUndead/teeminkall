<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <title>@yield('title')</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link rel="stylesheet" type="text/css" href="{{ asset('css/test.css') }}">
</head>

<body>

  

    <div class="tk-navbar container-fluid justify-content-center">
        <div class="tk-header">
          <ul class="tk-nav">
            <li class="{{ Request::routeIs('/') ? 'active' : '' }}">
              <a href="/">
                <span>Főoldal</span>
              </a>
            </li>

            <li class="{{ Request::routeIs('/About') ? 'active' : '' }}">
              <a href="/About">
                <span>Rólunk</span>
              </a>
            </li>

            <li class="{{ Request::routeIs('/Register') ? 'active' : '' }}">
              <a href="/Register">
                <span>Regisztráció</span>
              </a>
            </li>
          </ul>
          <div class="toggle-menu">
            <a><i class="fa fa-bars" aria-hidden="true"></i></a>
          </div>
          <div class="form-switch" id="toggle">
            <div class="lighton"><i id="d-switch" class="fas fa-moon"></i></div>
          </div>
        </div>
      </div>

    <div id="error">

    </div>

    <div class="container-fluid">
            @yield('content')
    </div>

    <script>
      $(document).ready(function () {
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          url: "{{route('addmod')}}",
          type: "POST",
          success: function (data) {
            return 0;
          }
        })
      });
      
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
        const toggleMenu = document.querySelector('.toggle-menu');
        const nav = document.querySelector('.tk-nav');

        toggleMenu.addEventListener('click', () => {
          nav.classList.toggle('show');
        });
    </script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <footer class="footer container-fluid">
        <a href="https://github.com/RidingUndead/TeeminKall" class="text-center">Github oldalunk</a>
    </footer>
</body>

</html>
