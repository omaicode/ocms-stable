@if(isset($item))
    @php
        $active = request()->path() == $item->url;
        $is_child = isset($is_child) ? $is_child : false;
        $item_class = 'menu-item';
        $item_link_class = 'menu-link';

        if($is_child) {
            $item_class = '';
            $item_link_class = '';
        }
    @endphp
    <li class="{{$item_class}}{{$active ? ' active' : ''}}{{$item->childs->count() > 0 ? 'dropdown' : ''}}">
        <a 
            class="{{$item_link_class}} @if($item->childs->count() > 0) menu__btn @endif" 
            href="@if($item->childs->count() > 0) javascript:; @else {{$item->full_url}} @endif"
            @if($item->childs->count() > 0) role="button" data-bs-toggle="dropdown" aria-expanded="false" @endif
        >
            {{$item->name}}
            @if($item->childs->count() > 0)
                <i class="fa-solid fa-angle-down"></i>
            @endif
        </a>
        @if($item->childs->count() > 0)
            @include('admin.components.default-menu.index', ['menu' => $item->childs, 'is_child' => true])
        @endif
    </li>
@endif