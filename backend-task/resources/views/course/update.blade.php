@extends('base')

@section('body')
    <x-back route="{{route('courses.index')}}"/>
    <form action="{{ route('courses.update', $course) }}" method="post" enctype="multipart/form-data">
        @method('put')
        @csrf
        <h3>Обновление курса</h3>
        <x-input value="{{$course->name}}" name="name" placeholder="Название" />
        <x-input value="{{$course->description}}" name="description" placeholder="Описание" />
        <x-input value="{{$course->hours}}" name="hours" type="number" placeholder="Продолжительность" />
        <x-input value="{{$course->price}}" name="price" type="number" placeholder="Цена" />
        <div>
            <label class="form-label" for="start_date">Дата начала</label>
            <x-input value="{{$course->start_date->format('Y-m-d')}}" id="start_date" name="start_date" type="date" />
        </div>
        <div>
            <label class="form-label" for="end_date">Дата окончания</label>
            <x-input value="{{$course->end_date->format('Y-m-d')}}" id="end_date" name="end_date" type="date" />
        </div>
        <div>
            <label for="img">Обложка</label>
            <x-input value="{{$course->img}}" id="img" name="img" type="file" accept="image/jpeg"/>
        </div>
        <button class="btn btn-primary">Сохранить</button>
    </form>
@endsection