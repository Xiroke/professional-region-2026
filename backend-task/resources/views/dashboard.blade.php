@extends('base')

@section('body')
  <h2>Панель управления</h2>
  <h3 class="mt-4">Действия</h3>
  <div class="mt-2">
    <a href="{{route('courses.index')}}" class="btn btn-primary btn-lg">Курсы</a>
    <a href="{{route('students')}}" class="btn btn-primary btn-lg">Студенты</a>
  </div>
@endsection