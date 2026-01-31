@extends('base')

@section('body')
    <x-back route="{{route('courses.index')}}"/>
    <form action="{{ route('courses.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <h3>Создание курса</h3>
        <x-input name="name" placeholder="Название" />
        <x-input name="description" placeholder="Описание" />
        <x-input name="hours" type="number" placeholder="Продолжительность" />
        <x-input name="price" type="number" placeholder="Цена" />
        <div>
            <label class="form-label" for="start_date">Дата начала</label>
            <x-input id="start_date" name="start_date" type="date" />
        </div>
        <div>
            <label class="form-label" for="end_date">Дата окончания</label>
            <x-input id="end_date" name="end_date" type="date" />
        </div>
        <div>
            <label for="img">Обложка</label>
            <x-input id="img" name="img" type="file" accept="image/jpeg"/>
        </div>
        <button class="btn btn-primary">Добавить</button>
    </form>
@endsection