@php
    $detail = $menu->toArray();
    unset($detail['childs']);
@endphp

<li class="list-group-item border hover-none" id="menu_item_{{ $menu->id }}" data-id="{{ $menu->id }}">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="menu-arrow" style="cursor: pointer">
            <span><i class="fas fa-arrows-alt"></i></span>
            <span class="fw-bold">
                {{ $menu->name }}
            </span>
        </div>
        <div class="d-flex">
            <button
                type="button"
                class="btn btn-sm btn-warning btn-menu-edit me-2"
                data-json="{{ json_encode($detail) }}"
            >
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-sm btn-danger btn-menu-delete" data-id="{{ $menu->id }}">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
    <ul class="list-group border-0 menu" data-parent="{{ $menu->id }}">
    @if(!blank($menu->childs))
        @foreach($menu->childs as $submenu)
            @component('admin.pages.menu.menu-item', ['menu' => $submenu, 'is_child' => true])@endcomponent
        @endforeach
    @endif
    </ul>
</li>
