
@extends('layout')

@section('title', 'TeeminKall - Banned')

@section('content')


<div class="container-fluid mt-3 text-center justify-content-center">
    <img runat="server" src="{{ URL('Images/TeeminKall.png') }}">
    <h3 class="display-3 text-danger">@yield('title')</h3>
    <p class="mt-1">Véglegesen tiltva lettél R.Un és Darth Adonis által.</p>
    <p class="mt-1">Hacsak nem ok nélkül tiltottunk, nem léphetsz be.</p>
</div>

@endsection
