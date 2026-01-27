@include('base')

@section('body')
    <div class="container-center">
        <form action="{{ route('user.login') }}" method="post">
            <h3>Авторизация</h3>
            @csrf
            <x-input type="email" placeholder="Email" />
            <x-input type="password" placeholder="Пароль" />
            <button>Войти</button>
        </form>
    </div>
@endsection