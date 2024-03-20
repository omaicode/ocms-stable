<div class="form-group mb-3 has-validation @if($errors->has($name)) is-invalid @endif">
    @if($label && $mode != 'switch')
        <label for="{{$name}}" class="form-label">{{$label}} @if($required)<span class="text-danger">*</span>@endif</label>
    @endif
    @if ($slot->isNotEmpty() && $mode != 'select')
        {{$slot}}
    @else
        @if($mode == 'input')
            <input 
                class="form-control @if($errors->has($name)) is-invalid @endif" 
                name="{{$name}}"
                value="{{$value}}"
                type="{{$type}}"
                placeholder="{{$placeholder}}"
                @if($required)required @endif
                @if($readonly)readonly @endif
                @if($disabled)disabled @endif
            >
        @elseif($mode == 'textarea')
            <textarea 
                class="form-control @if($errors->has($name)) is-invalid @endif" 
                name="{{$name}}"
                type="{{$type}}"
                placeholder="{{$placeholder}}"
                @if($required)required @endif
                @if($readonly)readonly @endif
                @if($disabled)disabled @endif
            >{{$value}}</textarea>
        @elseif($mode == 'select')
            <select
                class="form-select @if($errors->has($name)) is-invalid @endif"
                data-select-mode="tomSelect"
                name="{{$name}}"
                placeholder="{{$placeholder}}"
                @if($required)required @endif
                @if($readonly)readonly @endif
                @if($disabled)disabled @endif
            >
                {{$slot}}
            </select>
        @elseif($mode == 'switch')
            <div class="form-check form-switch @if($errors->has($name)) is-invalid @endif">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    id="{{$name}}" 
                    name="{{$name}}" 
                    @if($readonly)readonly @endif 
                    @if($disabled)disabled @endif
                    @if($checked === true)checked @endif
                >
                <label class="form-check-label mb-0" for="{{$name}}">{{$label}}</label>
            </div>
        @endif
    @endif
    <small class="text-muted">{!! $help !!}</small>
    <div class="invalid-feedback">{{ $errors->first($name) }}</div>
</div>
