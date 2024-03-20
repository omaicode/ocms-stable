<div class="mb-3 {{$viewClass['form-group']}}">
    <label for="{{$id['name']}}" class="{{$viewClass['label']}} form-label">
        {{$label}}
        @if(strpos($attributes, "required") !== false)
        <span class="text-danger">*</span>
        @endif
    </label>

    <div class="{{$viewClass['field']}}">
        <input type="hidden" name="{{ $name['slug'] }}" value="{{ old($name['slug'], isset($value['slug']) ? $value['slug'] : '') }}">
        <input class="form-control @if($errors->has($errorKey)) is-invalid @endif" name="{{ $name['name'] }}" value="{{ old($name['name'], isset($value['name']) ? $value['name'] : '') }}"/>
        <div class="small mt-1" style="cursor: pointer" id="{{$id['name']}}_btn">
            <span class="text-muted me-1">@lang('messages.permalink'):</span>
            <span class="text-info">{{ url('/') }}/<span id="{{$id['slug']}}" data-title="@lang('messages.enter_slug')">{{ old($name['slug'], isset($value['slug']) ? $value['slug'] : '')}}</span></span>
        </div>
        @if($errors->has($id['slug']))
            @foreach($errors->get($id['slug']) as $message)
                <div class="small text-danger" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</div>
            @endforeach
        @endif        
        @include('admin.form.error')
    </div>
</div>