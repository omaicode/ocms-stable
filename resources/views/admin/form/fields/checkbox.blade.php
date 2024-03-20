<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input @if($errors->has($errorKey)) is-invalid @endif" {!! $attributes !!} @if($value == 1) checked @endif>
    <label for="{{$id}}" class="{{$viewClass['label']}} form-check-label">
        {{$label}}
        @if(strpos($attributes, "required") !== false)
            <span class="text-danger">*</span>
        @endif
    </label>

    @include('admin.form.help-block')
    @include('admin.form.error')
</div>
