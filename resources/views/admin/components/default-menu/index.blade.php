@php
    $is_child = isset($is_child) ? $is_child : false;
@endphp

@if($menu)
    <ul class="@if($is_child) menu__child @else navbar-nav @endif">
        @foreach($menu as $item)
            @include('admin.components.default-menu.item', ['item' => $item, 'is_child' => $is_child])
        @endforeach
    </ul>
@endif