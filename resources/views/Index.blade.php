@extends('layout')

@section('title', 'TeeminKall')

@section('content')

    @if($errors->any())
        <div class="alert alert-danger">
            {{ implode('', $errors->all(':message')) }}
        </div>
    @endif
    <div class="d-flex justify-content-center align-items-center">
        <img src="/Images/TeeminKall.png" class="col-11 col-md-3 align-self-center" />
    </div>
    <div class="container col-12 col-sm-7 justify-content-center text-center">
    <div class="col-12 col-sm-7 justify-content-center">

        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="container-lg col-12 tk-form">
                <input type="email" name="email" id="email" class="mt-1 mx-0 col-10 col-sm-5 tk-rounded-sm-left tk-rounded" placeholder="email@cim.com" required />
                <input type="password" name="password" id="password" class="mt-1 mx-0 col-12 col-md-5 tk-rounded-sm-right tk-rounded" placeholder="Jelszó" autocomplete="new-password" required />
            </div>
            <div class="row align-items-center container-fluid">
                <input type="submit" role="button" class="btn tk-slide-blue tk-green tk-rounded col-10 col-sm-3 tk-row-4" value="Belépés" />
            </div>
        </form>
    </div>

    <div class="container justify-content-center">
        <h3 class="text-center display-4">Kommunikálj a céggel, a rugalmasabb munkáért!</h3>
        <h5 class="text-center display-4">Ez egy sulis projekt. Regisztrálás saját felelősségedre csak!</h5>
        <p class="text-center text-muted">A TeeminKall egy cégek és csoportok számára kitalált Chat alkalmazás, mely a rugalmasságra és egyszerűségre összpontosít. Ez a webalkalmazás még béta fázisban van, így elődordulhatnak hibák.</p>
    </div>

    <div class="text-center col-12">
        <h3 class="display-4">TeeminKall</h3>
        <p class="display-5">A múlt része, és a jövő kulcsa</p>
    </div>

@endsection
