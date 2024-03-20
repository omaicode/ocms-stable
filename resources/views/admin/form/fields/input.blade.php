<div class="mb-3 {{$viewClass['form-group']}}">
    <label for="{{$id}}" class="{{$viewClass['label']}} form-label">
        {{$label}}
        @if(strpos($attributes, "required") !== false)
        <span class="text-danger">*</span>
        @endif
    </label>

    <div class="{{$viewClass['field']}}">
        <input {!! $attributes !!} class="form-control @if($errors->has($errorKey)) is-invalid @endif"/>
        @include('admin.form.help-block')
        @include('admin.form.error')
    </div>
</div>