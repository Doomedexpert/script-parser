@extends('master')
@section('title', 'Главная страница')

@section('content')
<main>
    <form method="post" action="parser_load" enctype="multipart/form-data" name='scriptParser'>
        @csrf
        @if(Session::has('error'))
            {{Session::get('error')}} 
        @endif
        <h3 class="display-5">Парсер</h3>
        <span>Выберите access_log файл</span><br>
        <input type="file" name="logFile"><br>
        <input type="submit" value="Обработать">
    </form>
    @if(Session::has('json'))
        <h3 class="display-5">Вывод:</h3>
        {{Session::get('json')}} 
    @endif
</main>  
@endsection 