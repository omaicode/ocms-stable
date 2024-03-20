@extends('admin.layouts.blank')

@section('title', $title)
@section('content')
    {!! $body !!}
@endsection

@foreach(\AdminAsset::getPushs() as $name => $content)
    @push($name)
        {!! $content !!}
    @endpush
@endforeach