<div class="mb-3 {{ $viewClass['form-group'] }} {{ $viewClass['field'] }}" data-codemirror="{{$name}}">
    <label for="{{ $id }}" class="{{ $viewClass['label'] }} form-label">
        {{ $label }}
        @if (strpos($attributes, 'required') !== false)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    <div>
        <textarea {!! $attributes !!}>{!! $value !!}</textarea>
        @include('admin.form.help-block')
        @include('admin.form.error')
    </div>
</div>
