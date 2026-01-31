@php use App\Models\Order; @endphp
@extends('base')

@section('body')
    <x-back route="{{route('dashboard')}}"/>
    <div class="list-page__top">
        <h3>Студенты</h3>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>Email</th>
            <th>Имя</th>
            <th>Курс</th>
            <th>Дата записи</th>
            <th>Статус оплаты</th>
            <th>Распечатать сертификат</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            @foreach($user->courses as $course)
                <tr>
                    <td>{{$user->email}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$course->name}}</td>
                    <td>{{$course->pivot->created_at->format('d-m-Y')}}</td>
                    <td>{{Order::getRuStatus($course->pivot->payment_status)}}</td>
                    <td>
                        <a href="{{route('certificate.print', $course->id)}}" class="btn btn-success">Распечатать сертификат</a>
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td class="p-4 text-muted text-center" colspan="100">
                    Пусто
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
