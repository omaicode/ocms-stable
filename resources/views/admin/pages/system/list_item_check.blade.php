<li class="list-group-item list-group-item-action rounded-0">
    <div class="d-flex justify-content-between align-items-center">
        <div>{{$title}}</div>
        @if($enabled)
            <div class="fw-bold text-success"><i class="fas fa-check"></i></div>
        @else
            <div class="fw-bold text-danger"><i class="fas fa-times"></i></div>
        @endif
    </div>
</li>