<div class="form-group mb-3">
    <textarea
        class="form-control @if($errors->has($name)) is-invalid @endif" 
        name="{{$name}}"
        id="{{$name}}"
        value="{{$value}}"
    >
    </textarea>
</div>