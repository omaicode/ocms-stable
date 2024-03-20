@extends('layout')

@section('content')
    {!! isset($content) ? $content : 'This is default content' !!}
@endsection
