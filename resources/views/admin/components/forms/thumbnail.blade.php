@php
    $uid = uniqid();
@endphp

<x-forms::group :name="$name" :label="$label">
    <div class="d-flex align-items-center">
        <div class="thumbnail-preview">
            <img src="{{ $value ?: adminAsset('img/img-placeholder.png') }}" style="width: auto%; height: 100%; object-fit: contain" id="thumbnail-{{ $uid }}">
        </div>
        <div class="ps-2 w-100">
            <input 
                type="file" 
                class="form-control form-control-thumbnail {{$errors->first($name) ? 'is-invalid' : ''}}" 
                name="{{$name}}" 
                placeholder="{{$placeholder}}"
                accept="image/jpg,image/jpeg,image/png,image/gif,image/x-icon"
                id="thumbnail-input-{{$uid}}"
            >  
        </div>
    </div>
</x-forms::group>

<script>
    document.getElementById('thumbnail-input-{{$uid}}').addEventListener('change', function(el) {
        const file = el.target.files[0]
        const preview = document.getElementById('thumbnail-{{$uid}}')
        if(!['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/x-icon', 'image/vnd.microsoft.icon'].includes(file.type)) {
            el.target.value = null
            Notyf.error("{{__('validation.image', ['attribute' => $label])}}")
            return
        }
        
        preview.setAttribute('src', URL.createObjectURL(file))
    });
</script>