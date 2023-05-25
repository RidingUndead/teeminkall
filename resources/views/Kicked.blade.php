
@extends('layout')

@section('title', 'TeeminKall - Kicked')

@section('content')

<div class="container-fluid mt-3 text-center justify-content-center">
    <img runat="server" src="{{ URL('Images/TeeminKall.png') }}">
    <h3 class="display-3 text-danger">@yield('title')</h3>
    <p class="mt-1">Ideiglenesen tiltva lettél R.Un és Darth Adonis által.</p>
    <p class="mt-1">Visszaengedünk, ha nem lesz rád panasz, vagy ha ok nélküli a tiltás.</p>
</div>

@endsection
