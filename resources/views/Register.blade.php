
@extends('layout')

@section('title', 'TeeminKall - Regisztráció')

@section('content')

        <div class="container col-12 col-md-3 justify-content-center">
            <img src="/Images/TeeminKall.png" />
            <h5 class="text-center display-4">Ez egy sulis projekt. Regisztrálás saját felelősségedre csak!</h5>
        </div>
        <div class="container-fluid col-12 col-sm-6">
            <div class="row justify-content-center row-sm">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {!! implode('<br>', $errors->all()) !!}
                @endif
                
                <h3 class="display-4 text-center">Csatlakozás csoporthoz</h3>
                <form action="{{ route('reguser') }}" method="post">
                    @csrf
                    <div class="container tk-form justify-content-center">
                        <input type="text" name="lastname" id="lastname" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-right tk-rounded" placeholder="Vezetéknév" />
                        <input type="text" name="firstname" id="firstname" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-left tk-rounded" placeholder="Keresztnév" />
                    </div>
                    <div class="container tk-form justify-content-center">
                        <input type="text" name="username" id="username" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-left tk-rounded" placeholder="Felhasználónév" />
                        <input type="email" name="email" id="email" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-left border-0 tk-rounded" placeholder="email@cim.com" />
                    </div>
                    <div class="container tk-form justify-content-center">
                        <input type="password" name="password" id="password" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-right border-0 tk-rounded" placeholder="Jelszó" autocomplete="new-password" />
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-right border-0 tk-rounded" placeholder="Jelszó megint" autocomplete="new-password" />
                    </div>
                    <div class="container tk-form justify-content-center">
                        
                        @if($groups)
                        <label for="group">Csoportok</label>
                            <select name="group">
                            @foreach ($groups as $group)
                                <option value="{{ $group->name }}">{{ $group->name }}</option>
                            @endforeach
                            </select>
                        @else
                            <p>Nincs még csoport</p>
                        @endif
                    </div>
                    <div class="row align-items-center container-fluid">
                        <input type="submit" role="button" class="btn tk-blue tk-slide-green" value="Csatlakozás csoporthoz" />
                    </div>
                </form>
                            
            </div>
        </div>

@endsection