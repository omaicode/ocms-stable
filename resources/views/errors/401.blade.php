@extends('errors::minimal')

@section('title', __($exception->getMessage() ?: 'Unauthorized'))
@section('code', '401')
@section('message', __($exception->getMessage() ?: 'Unauthorized'))
