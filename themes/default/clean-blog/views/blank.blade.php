@extends('layout')

@section('content')
    {!! isset($content) ? $content : 'This is blank content' !!}
@endsection
