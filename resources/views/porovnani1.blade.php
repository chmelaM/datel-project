@extends('layouts.app')

@section('content')

    {{Form::open(['method'=>'POST', 'route'=>'porovnatPost', 'enctype'=>'multipart/form-data'])}}

    vyberte soubor symbolickych adres :
    <br/>
    <input type="file" cols="120" rows="24"  name="adresy" required></input>
    <input type="submit" value="Odeslat">

    {{Form::close()}}
@endsection
