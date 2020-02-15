@extends('layouts.app')

@section('content')

    <div class="content">
        <div class="title m-b-md">
            {{config("app.name")}}
        </div>

        <div class="links">
            <a href="{{route('rozdelitGet')}}">rozdelit</a>
            {{--<a href="https://laracasts.com">Laracasts</a>
            <a href="https://laravel-news.com">News</a>
            <a href="https://blog.laravel.com">Blog</a>
            <a href="https://nova.laravel.com">Nova</a>
            <a href="https://forge.laravel.com">Forge</a>
            <a href="https://vapor.laravel.com">Vapor</a>
            <a href="https://github.com/laravel/laravel">GitHub</a>--}}
        </div>
    </div>
@endsection
