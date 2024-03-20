<div class="mb-3 {{$viewClass['form-group']}}">
    <label for="{{$id}}" class="{{$viewClass['label']}} form-label">
        {{$label}}
    </label>

    <div class="{{$viewClass['field']}}">
        <input {!! $attributes !!} class="form-control" value="{{$value}}" disabled readonly/>
    </div>
</div>