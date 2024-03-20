<div class="card card-avatar mb-3">
    <div class="card-body">
        <div class="card-title fw-bold">{{$label}}</div>
        <div data-type="media-button" data-trigger="showSelectMediaModal" data-accept="{{ isset($accept) ? $accept : 'image/jpeg,image/jpg,image/png,image/gif,image/webp,image/avif,image/bmp,image/vnd.microsoft.icon' }}">
            <div class="media-form-button-wrapper">
                <div class="media-form-button-wrapper--content form-button-preview" @if($preview) style="background-image: url('{{$preview}}')" @endif>
                    <div class="form-button-placeholder" @if($preview) style="display: none" @endif>{{$placeholder}}</div>
                </div>
            </div>
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        </div>
        @include('admin.form.help-block')
        @include('admin.form.error')
    </div>
</div>
