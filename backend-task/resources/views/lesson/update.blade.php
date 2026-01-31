@extends('base')

@section('body')
    <x-back route="{{route('courses.lessons.index', $lesson)}}"/>
    <form action="{{route('courses.lessons.update', [$course, $lesson])}}" method="post" enctype="multipart/form-data">
        @method('put')
        @csrf
        <h3>Обновление урока</h3>
        <x-input value="{{$lesson->name}}" name="name" placeholder="Заголовок" />
        <x-input value="{{$lesson->description}}" name="description" placeholder="Текстовое сообщение" />
        <x-input value="{{$lesson->video_link}}" name="video_link" placeholder="Видеоссылка" />
        <x-input value="{{$lesson->hours}}" name="hours" type="number" placeholder="Длительность" />
        <button class="btn btn-primary">Сохранить</button>
    </form>
@endsection