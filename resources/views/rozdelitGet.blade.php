@extends('layouts.app')

@section('content')
{{Form::open(['method'=>'POST', 'route'=>'rozdelitPost', 'enctype'=>'multipart/form-data'])}}

        vlozte stitky :
        <br/>
        <textarea cols="120" rows="24"  name="stitky" required></textarea>
        <input type="submit" value="Odeslat">

    {{Form::close()}}
@endsection
