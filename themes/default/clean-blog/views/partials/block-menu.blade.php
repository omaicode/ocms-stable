@php
    $is_child = isset($is_child) ? $is_child : false;
@endphp

@if($menu)
    <ul @if($is_child) class="dropdown-menu" aria-labelledby="navbarDropdown" @else class="navbar-nav ml-auto" @endif>
        @foreach($menu as $item)
            @include('partials.block-menu-item', ['item' => $item, 'is_child' => $is_child])
        @endforeach
    </ul>
@endif 