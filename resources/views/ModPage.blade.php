@extends('layout')

@section('title', 'Moderátor oldal')

@section('content')

<div class="d-flex justify-content-center">
    <form method="POST" action="{{route('modlogin')}}">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control" name="username" placeholder="Felhasználónév">
            <input type="password" class="form-control" name="password" placeholder="Jelszó">
        </div>
        <button type="submit" class="btn tk-green">Adminisztrálás</button>
    </form>
</div>

@endsection