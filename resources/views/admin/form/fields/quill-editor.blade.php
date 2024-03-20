<div class="mb-3 {{ $viewClass['form-group'] }}" data-quill-editor="{{$name}}">
    <label for="{{ $id }}" class="{{ $viewClass['label'] }} form-label">
        {{ $label }}
        @if (strpos($attributes, 'required') !== false)
            <span class="text-danger">*</span>
        @endif
    </label>

    <div class="{{ $viewClass['field'] }}">
        <div id="toolbar-container">
            <span class="ql-formats">
                <select class="ql-font"></select>
                <select class="ql-size"></select>
            </span>
            <span class="ql-formats">
                <button class="ql-bold"></button>
                <button class="ql-italic"></button>
                <button class="ql-underline"></button>
                <button class="ql-strike"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-script" value="sub"></button>
                <button class="ql-script" value="super"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-header" value="1"></button>
                <button class="ql-header" value="2"></button>
                <button class="ql-blockquote"></button>
                <button class="ql-code-block"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-list" value="ordered"></button>
                <button class="ql-list" value="bullet"></button>
                <button class="ql-indent" value="-1"></button>
                <button class="ql-indent" value="+1"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-direction" value="rtl"></button>
                <select class="ql-align"></select>
            </span>
            <span class="ql-formats">
                <button class="ql-link"></button>
                <button class="ql-image"></button>
                <button class="ql-video"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-clean"></button>
            </span>
            <span class="ql-formats">
                <select class="ql-color"></select>
                <select class="ql-background"></select>
            </span>
        </div>
        <div id="editor-container" style="height: 350px">{!! $value !!}</div>
        <input type="hidden" value="{{ $value }}" {!! $attributes !!}>
        @include('admin.form.help-block')
        @include('admin.form.error')
    </div>
</div>
