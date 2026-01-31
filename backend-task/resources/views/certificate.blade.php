@extends('base')

@section('body')
  <x-back class="d-print-none" route="{{route('students')}}"/>
  <div class="text-center d-flex flex-column align-items-center justify-content-center">
    <h2>СЕРТИФИКАТ</h2>
    <br>
    Выдан студенту <h3 class="text-decoration-underline">{{$user->name}} <br> ({{$user->email}})</h3>
    <br>
    За прохождение курса <h3 class="text-decoration-underline">{{$course->name}}</h3>

    Дата: {{now()->format('d-m-Y')}}

    <button class="btn btn-primary mt-5 d-print-none" onclick="window.print()">Распечатать</button>
  </div>
@endsection