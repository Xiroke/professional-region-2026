@extends('base')

@section('body')
    <form action="{{ route('user.login') }}" method="post">
        @csrf
        <h3>Авторизация</h3>
        <x-input name="email" type="email" placeholder="Email" />
        <x-input name="password" type="password" placeholder="Пароль" />
        <button class="btn btn-primary">Войти</button>
    </form>
@endsection