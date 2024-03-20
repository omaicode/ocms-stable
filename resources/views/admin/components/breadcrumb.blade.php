<div class="pt-4 pb-2">
    <nav aria-label="breadcrumb" class="d-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home icon icon-xxs"></i></a></li>
            @foreach($items as $item)
                @if(isset($item['enable']) && $item['enable'])
                    <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
                @elseif(!isset($item['enable']))
                    <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
    {{ $slot }}
</div>
