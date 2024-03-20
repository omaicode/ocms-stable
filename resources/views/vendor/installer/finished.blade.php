@extends('vendor.installer.layouts.master')

@section('template_title')
    Installation Finished
@endsection

@section('title')
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
  Installation Finished
@endsection

@section('container')

	{{-- @if(session('message')['dbOutputLog'])
		<p><strong><small>Migration &amp; Seed Console Output:</small></strong></p>
		<pre><code>{{ session('message')['dbOutputLog'] }}</code></pre>
	@endif --}}

	<p><strong><small>Admin Login Information:</small></strong></p>
	<p>Username: <b>administrator</b></p>
	<p>Password: <b>123456</b></p>

	<p><strong><small>Application Console Output:</small></strong></p>
	<pre><code>{{ $finalMessages }}</code></pre>

	<p><strong><small>Installation Log Entry:</small></strong></p>
	<pre><code>{{ $finalStatusMessage }}</code></pre>

	<p><strong><small>Final .env File:</small></strong></p>
	<pre><code>{{ $finalEnvFile }}</code></pre>

    <div class="buttons">
        <a href="{{ route('admin.auth.login') }}" class="button">Click here to exit</a>
    </div>

@endsection
