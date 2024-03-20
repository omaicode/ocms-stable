@extends('admin.layouts.master')

@section('title', $title)
@section('content')
    @if(count($breadcrumb) > 0)
        <x-breadcrumb :items="$breadcrumb"></x-breadcrumb>
    @else
        <div class="my-3"></div>
    @endif
    <div class="row mb-5">
        <div class="col-12">
            {!! $body !!}
        </div>
    </div>
@endsection

@foreach(\AdminAsset::getPushs() as $name => $content)
    @push($name)
        @if(is_array($content))
            @foreach($content as $sub_content)
                {!! $sub_content !!}
            @endforeach
        @else
            {!! $content !!}
        @endif
    @endpush
@endforeach