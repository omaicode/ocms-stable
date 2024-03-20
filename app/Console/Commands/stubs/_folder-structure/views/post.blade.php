@extends('layout')

@section('content')
    {!! isset($content) ? $content : 'This is post content' !!}
@endsection
