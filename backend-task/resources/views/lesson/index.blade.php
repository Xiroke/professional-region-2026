@extends('base')

@section('body')
    <x-back route="{{route('courses.index')}}"/>
    <div class="list-page__top">
        <h3>Уроки</h3>
        <div class="list-page__top-actions">
            <a href="{{route('courses.lessons.create', $course)}}" class="btn btn-primary">Создать</a>
        </div>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>Заголовок</th>
            <th>Текстовое сообщение</th>
            <th>Видеоссылка</th>
            <th>Длительность</th>
            <th>Редактирование</th>
            <th>Удаление</th>
        </tr>
        </thead>
        <tbody>
        @forelse($lessons as $item)
            <tr>
                <td>{{$item->name}}</td>
                <td>{{$item->description}}</td>
                <td>{{$item->video_link}}</td>
                <td>{{$item->hours}}</td>
                <td>
                    <a href="{{route('courses.lessons.edit', [$course, $item])}}" class="btn btn-primary">Редактировать</a>
                </td>
                <td>
                    <form class="form-empty" action="{{route('courses.lessons.destroy', [$course, $item])}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
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
