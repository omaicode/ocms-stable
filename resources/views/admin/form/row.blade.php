<div class="row">
    @foreach($fields as $field)
    <div class="col-12 {{ $field['width'] }}">
        {!! $field['element']->render() !!}
    </div>
    @endforeach
</div>