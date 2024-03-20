@extends('layout')

@section('content')
    {!! isset($content) ? $content : 'This is blog content' !!}
@endsection
