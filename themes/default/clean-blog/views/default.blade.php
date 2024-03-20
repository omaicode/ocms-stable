@extends('layout')

@section('content')
    [partial name="navbar"][/partial]

    {!! isset($content) ? $content : 'This is default content' !!}
    
    [partial name="footer"][/partial]
@endsection
