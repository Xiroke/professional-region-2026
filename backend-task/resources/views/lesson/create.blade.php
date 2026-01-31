@extends('base')

@section('body')
    <x-back route="{{route('courses.lessons.index', $course)}}"/>
    <form action="{{ route('courses.lessons.store', $course) }}" method="post" enctype="multipart/form-data">
        @csrf
        <h3>Создание урока</h3>
        <x-input name="name" placeholder="Заголовок" />
        <x-input name="description" placeholder="Текстовое сообщение" />
        <x-input name="video_link" placeholder="Видеоссылка" />
        <x-input name="hours" type="number" placeholder="Длительность" />
        <button class="btn btn-primary">Добавить</button>
    </form>
@endsection