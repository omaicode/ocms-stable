<form class="@if($errors->any()) was-validated @endif" action="{{ $action }}" method="{{ $method }}" {{ $multipart ? 'enctype=multipart/form-data' : ''}}>
    @csrf
    {{ $slot }}
</form>