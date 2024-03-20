@php
    $breadcrumb = [['title' => __('menu.system'), 'url' => '#'], ['title' => __('menu.system.administrators'), 'url' => '#', 'active' => true]];
@endphp
@extends('admin.layouts.master')

@section('title', __('menu.system.administrators'));
@section('content')
<x-breadcrumb :items="$breadcrumb"></x-breadcrumb>
<div class="row mb-5">
    <div class="col-12">
        {!! $table !!}
    </div>
</div>
@endsection