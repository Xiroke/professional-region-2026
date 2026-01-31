@extends('base')

@section('body')
    <x-alert/>
    <x-back route="{{route('dashboard')}}"/>
    <div class="list-page__top">
        <h3>Курсы</h3>
        <div class="list-page__top-actions">
            <a href="{{route('courses.create')}}" class="btn btn-primary">Создать</a>
        </div>
    </div>
  <table class="table">
      <thead>
      <tr>
          <th>Название</th>
          <th>Описание</th>
          <th>Продолжительность</th>
          <th>Цена</th>
          <th>Дата начала</th>
          <th>Дата окончания</th>
          <th>Обложка</th>
          <th>Уроки</th>
          <th>Редактирование</th>
          <th>Удаление</th>
      </tr>
      </thead>
      <tbody>
        @forelse($courses as $item)
            <tr>
                <td>{{$item->name}}</td>
                <td>{{$item->description}}</td>
                <td>{{$item->hours}}</td>
                <td>{{$item->price}}</td>
                <td>{{$item->start_date->format('d-m-Y')}}</td>
                <td>{{$item->end_date->format('d-m-Y')}}</td>
                <td>
                    <img src="{{$item->img_url}}" alt="фото" class="img-thumbnail">
                </td>
                <td>
                    <a href="{{route('courses.lessons.index', $item->id)}}" class="btn btn-success">Уроки</a>
                </td>
                <td>
                    <a href="{{route('courses.edit', $item->id)}}" class="btn btn-primary">Редактировать</a>
                </td>
                <td>
                    <form class="form-empty" action="{{route('courses.destroy', $item->id)}}" method="post">
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
  {{ $courses->links() }}
@endsection
